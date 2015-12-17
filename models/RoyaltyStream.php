<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class RoyaltyStream extends ApiModel
{
    use SoftDeletingTrait;

    const CREATED_AT = 'created';
    const UPDATED_AT = 'updated';

    protected $guarded = [RoyaltyStreamFile::CREATED_AT, RoyaltyStreamFile::UPDATED_AT];
    protected $table = 'royalty_stream';

    public static function boot()
    {
        parent::boot();
        RoyaltyStream::deleting(function ($royaltyStream) {
            $royaltyStream->deleted = 1;
            $royaltyStream->update();
        });
    }

    public static function loadFromFile($royaltyStreamFiles)
    {
        $query = "LOAD DATA LOCAL INFILE ".DB::connection()->getPdo()->quote($royaltyStreamFiles[0]->stream_file_name )."
            INTO TABLE ".RoyaltyStream::table()."
            CHARACTER SET 'utf8'
            FIELDS TERMINATED BY ','
            ENCLOSED BY '\"'
            LINES TERMINATED BY '\n'
            IGNORE 1 LINES
            (@col".implode(",@col", array_keys(CsvFileService::getFileHeaders($royaltyStreamFiles[0]->stream_file_name ))).")
            SET ";
        foreach (self::fieldsToFileHeaderPositions() as $field => $position)
            if (array_key_exists($field, self::fieldFilters()))
                $query .= " $field=".self::fieldFilter($field, "@col".$position).", ";
            else
                $query .= " $field=TRIM(@col$position), ";
        $query = rtrim($query, ", ");
        $query .= ", created = now(), updated = now(), period_year = '".$royaltyStreamFiles[0]->period_year."',
         period_month = '".$royaltyStreamFiles[0]->period_month."', period_quarter = '".$royaltyStreamFiles[0]->period_quarter."' ";

        //@col3 == payee code
        $streamFileIdsFilter = "CASE @col3";
        foreach ($royaltyStreamFiles as $royaltyStreamFile)
            $streamFileIdsFilter .= " WHEN '".$royaltyStreamFile->deal()->first()->payee_code."' THEN '".$royaltyStreamFile->id."' ";
        $streamFileIdsFilter .= "ELSE NULL END";

        $query .= ", stream_file_id = ".$streamFileIdsFilter;

//        echo "<pre>";
//        print_r($query);
//        echo "</pre>";
//        die("moare aici");

        DB::connection()->getpdo()->exec($query);
    }

    private static function fieldsToFileHeaderPositions()
    {
        return [
            "royalty_country_iso" => 25,
            "royalty_currency" => 30,
            "song_number" => 7,
            "song_title" => 6,
            "royalty_amount" => 19,
            "party_name" => 8,
            "performance_source" => 9,
            "serial_or_film" => 29,
            "region" => 25,
            "number_of_plays" => 21,
            "statement_period_from" => 23,
            "statement_period_to" => 24,
            "episode_name" => 27,
            "participant_percent" => 15,
            "performance_year" => 23,
            "performance_month" => 23,
            "performance_quarter" => 23,
            "royalty_type" => 11,
            "account_name" => 4,
            "company_name" => 0,
            "company_code" => 1,
            "payee_name" => 2,
            "payee_code" => 3
        ];
    }

    public static function countryColumnFilterString($placeholder)
    {
        $countryInfos = DB::table('countryinfo')->get();
        $filterString = "CASE ".$placeholder;
        foreach ($countryInfos as $countryInfo)
            $filterString .= " WHEN '".$countryInfo["name"]."' THEN '".$countryInfo["iso_alpha2"]."' ";
        $filterString .= "ELSE 'US' END";
        return $filterString;
    }

    private static function fieldFilters()
    {
        return [
            "region" => self::countryColumnFilterString("%VALUE%"),
            "royalty_country_iso" => self::countryColumnFilterString("%VALUE%"),
            "statement_period_from" => "STR_TO_DATE(%VALUE%, '%M-%Y' )",
            "statement_period_to"   => "STR_TO_DATE(%VALUE%, '%M-%Y' )",
            "performance_year" => "YEAR(STR_TO_DATE(%VALUE%, '%M-%Y' ))",
            "performance_month" => "MONTH(STR_TO_DATE(%VALUE%, '%M-%Y' ))",
            "performance_quarter" => "QUARTER(STR_TO_DATE(%VALUE%, '%M-%Y' ))",
        ];
    }

    private static function fieldFilter($field, $value)
    {
        return str_replace("%VALUE%", $value, self::fieldFilters()[$field]);
    }

    public static function cleanEmptyStreams()
    {
        RoyaltyStream::whereNull("stream_file_id")->delete();
    }
}
