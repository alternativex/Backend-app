<?php

use Illuminate\Database\Migrations\Migration;

class CreateEtlTables extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        DB::unprepared("CREATE TABLE `etl_history_ascap_domestic` (
  `row_data_crt` int(11) DEFAULT NULL,
  `distribution_year` bigint(20) DEFAULT NULL,
  `distribution_quarter` bigint(20) DEFAULT NULL,
  `statement_recipient_id` bigint(20) DEFAULT NULL,
  `statement_recipient_name` varchar(256) DEFAULT NULL,
  `party_id` bigint(20) DEFAULT NULL,
  `party_name` varchar(256) DEFAULT NULL,
  `legal_earner_party_id` varchar(256) DEFAULT NULL,
  `legal_earner_party_name` varchar(256) DEFAULT NULL,
  `performance_source` varchar(256) DEFAULT NULL,
  `music_user_Genre` varchar(256) DEFAULT NULL,
  `music_user` varchar(256) DEFAULT NULL,
  `network_service` varchar(256) DEFAULT NULL,
  `performance_start_date` varchar(20) DEFAULT NULL,
  `performance_end_date` varchar(20) DEFAULT NULL,
  `survey_type` char(1) DEFAULT NULL,
  `day_part_code` char(1) DEFAULT NULL,
  `series_or_film` varchar(256) DEFAULT NULL,
  `program_name` varchar(256) DEFAULT NULL,
  `song_number` varchar(256) DEFAULT NULL,
  `song_title` varchar(256) DEFAULT NULL,
  `ca` double DEFAULT NULL,
  `classification_code` varchar(256) DEFAULT NULL,
  `number_of_plays` double DEFAULT NULL,
  `performance_type` varchar(256) DEFAULT NULL,
  `duration` varchar(256) DEFAULT NULL,
  `performing_artist` varchar(256) DEFAULT NULL,
  `composer_name` varchar(256) DEFAULT NULL,
  `ee_share` double DEFAULT NULL,
  `credits` double DEFAULT NULL,
  `dollars` decimal(11,6) DEFAULT NULL,
  `premium_credits` double DEFAULT NULL,
  `premium_dollars` varchar(256) DEFAULT NULL,
  `adjustment_indicator` varchar(256) DEFAULT NULL,
  `adjustment_reason_code` varchar(256) DEFAULT NULL,
  `original_distribution_date` varchar(20) DEFAULT NULL,
  `role_type` varchar(256) DEFAULT NULL,
  `match_field` tinytext,
  `value_field` bigint(20) DEFAULT NULL,
  `item_id` bigint(20) DEFAULT NULL,
  `item_name` varchar(256) DEFAULT NULL,
  `load_date` varchar(20) DEFAULT NULL,
  `stream_file_name` varchar(1000) DEFAULT NULL,
  `item_variation_id` int(11) DEFAULT NULL,
  `item_match_type` int(11) DEFAULT NULL
)");

        DB::unprepared("CREATE TABLE `etl_history_ascap_international` (
  `row_data_crt` int(11) DEFAULT NULL,
  `MatchField` tinytext,
  `valuefield` bigint(20) DEFAULT NULL,
  `item_id` bigint(20) DEFAULT NULL,
  `itemname` varchar(256) DEFAULT NULL,
  `file_type` varchar(256) DEFAULT NULL,
  `statement_recipient_name` varchar(20) DEFAULT NULL,
  `statement_recipient_id` bigint(20) DEFAULT NULL,
  `party_name` varchar(256) DEFAULT NULL,
  `party_id` bigint(20) DEFAULT NULL,
  `legal_earner_party_id` bigint(20) DEFAULT NULL,
  `legal_earner_party_name` varchar(256) DEFAULT NULL,
  `distribution_date` datetime DEFAULT NULL,
  `country_name` varchar(256) DEFAULT NULL,
  `society_name` varchar(256) DEFAULT NULL,
  `society_code` varchar(256) DEFAULT NULL,
  `performance_start_date` varchar(256) DEFAULT NULL,
  `performance_end_date` varchar(256) DEFAULT NULL,
  `song_title` varchar(256) DEFAULT NULL,
  `song_number` varchar(256) DEFAULT NULL,
  `member_share` varchar(256) DEFAULT NULL,
  `series_name` varchar(256) DEFAULT NULL,
  `program_name` varchar(256) DEFAULT NULL,
  `general` varchar(256) DEFAULT NULL,
  `radio` varchar(256) DEFAULT NULL,
  `tv` varchar(256) DEFAULT '0',
  `tv_film` varchar(256) DEFAULT NULL,
  `cinema` varchar(256) DEFAULT NULL,
  `total` decimal(11,6) DEFAULT NULL,
  `Adjustment_Distribution_Date` varchar(256) DEFAULT NULL,
  `adjustment_indicator` tinyint(1) DEFAULT NULL,
  `role_type` varchar(256) DEFAULT NULL,
  `stream_file_name` varchar(1000) DEFAULT NULL,
  `load_date` varchar(256) DEFAULT NULL,
  `result` tinyint(1) DEFAULT NULL,
  `item_variation_id` bigint(20) DEFAULT NULL,
  `item_match_type` int(11) DEFAULT NULL
)");

        DB::unprepared("CREATE TABLE `etl_history_bmi` (
  `row_data_crt` int(11) DEFAULT NULL,
  `period` bigint(20) DEFAULT NULL,
  `w_or_p` char(1) DEFAULT NULL,
  `party_name` varchar(256) DEFAULT NULL,
  `party_id` bigint(20) DEFAULT NULL,
  `ip_number` bigint(20) DEFAULT NULL,
  `song_title` varchar(256) DEFAULT NULL,
  `song_number` varchar(256) DEFAULT NULL,
  `perf_source` varchar(256) DEFAULT NULL,
  `country_of_performance` varchar(256) DEFAULT NULL,
  `show_name` varchar(256) DEFAULT NULL,
  `episode_name` varchar(256) DEFAULT NULL,
  `show_number` bigint(20) DEFAULT NULL,
  `use_code` varchar(3) DEFAULT NULL,
  `timing` varchar(256) DEFAULT NULL,
  `participant_percent` double DEFAULT NULL,
  `perf_count` bigint(20) DEFAULT NULL,
  `bonus_level` tinytext,
  `royalty_amout` decimal(20,6) DEFAULT NULL,
  `withhold` tinytext,
  `perf_period` bigint(20) DEFAULT NULL,
  `current_activity_amt` double DEFAULT NULL,
  `hits_song_or_tv_net_super_usage_bonus` double DEFAULT NULL,
  `standards_or_tv_net_theme_bonus` double DEFAULT NULL,
  `foreign_society_adjustment` tinytext,
  `item_id` bigint(20) DEFAULT NULL,
  `load_date` datetime DEFAULT NULL,
  `stream_file_name` varchar(1000) DEFAULT NULL,
  `item_variation_id` int(11) DEFAULT NULL,
  `item_match_type` int(11) DEFAULT NULL
)");

        DB::unprepared("CREATE TABLE `etl_history_orchard` (
  `row_data_crt` int(11) DEFAULT NULL,
  `period` varchar(256) DEFAULT NULL,
  `activity_period` varchar(256) DEFAULT NULL,
  `DMS` varchar(256) DEFAULT NULL,
  `territory` varchar(256) DEFAULT NULL,
  `Orchard_UPC` bigint(20) DEFAULT NULL,
  `Manufacturers_UPC` varchar(256) DEFAULT NULL,
  `album_number` varchar(256) DEFAULT NULL,
  `Imprint_Label` varchar(256) DEFAULT NULL,
  `Artist_Name` varchar(256) DEFAULT NULL,
  `album_title` varchar(256) DEFAULT NULL,
  `song_title` varchar(256) DEFAULT NULL,
  `ISRC` varchar(256) DEFAULT NULL,
  `Volume` bigint(20) DEFAULT NULL,
  `song_number` bigint(20) DEFAULT NULL,
  `Quantity` varchar(20) DEFAULT NULL,
  `Unit_Price` double DEFAULT NULL,
  `Gross` double DEFAULT NULL,
  `Trans_Type` varchar(256) DEFAULT NULL,
  `Adjusted_Gross` double DEFAULT NULL,
  `Split_Rate` double DEFAULT NULL,
  `Label_Share_Net_Receipts` decimal(20,6) DEFAULT NULL,
  `Ringtone_Publishing` double DEFAULT NULL,
  `Publishing` double DEFAULT NULL,
  `Mech._Administrative_Fee` double DEFAULT NULL,
  `Preferred_Currency` varchar(256) DEFAULT NULL,
  `stream_file_name` varchar(1000) DEFAULT NULL,
  `result` tinyint(1) DEFAULT NULL,
  `MatchField` tinytext,
  `ValueField` bigint(20) DEFAULT NULL,
  `item_id` bigint(20) DEFAULT NULL,
  `ItemName` varchar(256) DEFAULT NULL,
  `load_date` datetime DEFAULT NULL,
  `item_variation_id` int(11) DEFAULT NULL,
  `item_match_type` int(11) DEFAULT NULL,
  `party_id` varchar(256) DEFAULT NULL
)");

        DB::unprepared("CREATE TABLE `etl_history_sesac` (
  `row_data_crt` int(11) DEFAULT NULL,
  `Year` varchar(256) DEFAULT NULL,
  `Qtr` varchar(256) DEFAULT NULL,
  `song_title` varchar(256) DEFAULT NULL,
  `percent_Shares` varchar(6) DEFAULT NULL,
  `Synd` tinytext,
  `Strip` tinytext,
  `stream_file_name` varchar(1000) DEFAULT NULL,
  `MatchField` tinytext,
  `ValueField` double DEFAULT NULL,
  `item_id` bigint(20) DEFAULT NULL,
  `load_date` datetime DEFAULT NULL,
  `Song_number` varchar(256) DEFAULT NULL,
  `Production_Title` varchar(256) DEFAULT NULL,
  `Episode_Title` varchar(256) DEFAULT NULL,
  `Episode_Number` tinytext,
  `Network` varchar(256) DEFAULT NULL,
  `Album_Title` tinytext,
  `Artist` tinytext,
  `Payment_Type` tinytext,
  `Chart` tinytext,
  `POSITION` tinytext,
  `Pay_number` bigint(20) DEFAULT NULL,
  `End_Air_Dt` datetime DEFAULT NULL,
  `Medium` tinytext,
  `Coverage` tinytext,
  `Pay_Code` tinytext,
  `Day_Part` varchar(256) DEFAULT NULL,
  `Perf_Category` varchar(256) DEFAULT NULL,
  `Perf_Type` varchar(256) DEFAULT NULL,
  `Perf_Duration` varchar(256) DEFAULT NULL,
  `Performances` bigint(20) DEFAULT NULL,
  `Occur` bigint(20) DEFAULT NULL,
  `Society_Abbrv` varchar(256) DEFAULT NULL,
  `Society_Country` varchar(256) DEFAULT NULL,
  `Earnings` decimal(11,6) DEFAULT NULL,
  `Perf_Air_Date` datetime DEFAULT NULL,
  `Establishment_Source` varchar(256) DEFAULT NULL,
  `item_variation_id` int(11) DEFAULT NULL,
  `item_match_type` int(11) DEFAULT NULL,
  `party_id` varchar(256) DEFAULT NULL
)");

        DB::unprepared("CREATE TABLE `etl_stg_ascap_domestic` (
  `row_data_crt` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `distribution_year` bigint(20) DEFAULT NULL,
  `distribution_quarter` bigint(20) DEFAULT NULL,
  `statement_recipient_id` bigint(20) DEFAULT NULL,
  `statement_recipient_name` varchar(256) DEFAULT NULL,
  `party_id` bigint(20) DEFAULT NULL,
  `party_name` varchar(256) DEFAULT NULL,
  `legal_earner_party_id` varchar(256) DEFAULT NULL,
  `legal_earner_party_name` varchar(256) DEFAULT NULL,
  `performance_source` varchar(256) DEFAULT NULL,
  `music_user_Genre` varchar(256) DEFAULT NULL,
  `music_user` varchar(256) DEFAULT NULL,
  `network_service` varchar(256) DEFAULT NULL,
  `performance_start_date` varchar(20) DEFAULT NULL,
  `performance_end_date` varchar(20) DEFAULT NULL,
  `survey_type` char(1) DEFAULT NULL,
  `day_part_code` char(1) DEFAULT NULL,
  `series_or_film` varchar(256) DEFAULT NULL,
  `program_name` varchar(256) DEFAULT NULL,
  `song_number` varchar(256) DEFAULT NULL,
  `song_title` varchar(256) DEFAULT NULL,
  `ca` double DEFAULT NULL,
  `classification_code` varchar(256) DEFAULT NULL,
  `number_of_plays` double DEFAULT NULL,
  `performance_type` varchar(256) DEFAULT NULL,
  `duration` varchar(256) DEFAULT '',
  `performing_artist` varchar(256) DEFAULT NULL,
  `composer_name` varchar(256) DEFAULT NULL,
  `ee_share` double DEFAULT NULL,
  `credits` double DEFAULT NULL,
  `dollars` decimal(11,6) DEFAULT NULL,
  `premium_credits` double DEFAULT NULL,
  `premium_dollars` varchar(256) DEFAULT NULL,
  `adjustment_indicator` varchar(256) DEFAULT NULL,
  `adjustment_reason_code` varchar(256) DEFAULT NULL,
  `original_distribution_date` varchar(20) DEFAULT NULL,
  `role_type` varchar(256) DEFAULT NULL,
  `match_field` tinytext,
  `value_field` bigint(20) DEFAULT NULL,
  `item_id` bigint(20) DEFAULT NULL,
  `item_name` varchar(100) DEFAULT NULL,
  `load_date` varchar(20) DEFAULT NULL,
  `stream_file_name` varchar(1000) DEFAULT NULL,
  `item_variation_id` bigint(20) DEFAULT NULL,
  `item_match_type` int(11) DEFAULT NULL,
  PRIMARY KEY (`row_data_crt`)
)");

        DB::unprepared("CREATE TABLE `etl_stg_ascap_international` (
  `row_data_crt` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `MatchField` tinytext,
  `valuefield` bigint(20) DEFAULT NULL,
  `item_id` bigint(20) DEFAULT NULL,
  `ItemName` varchar(255) DEFAULT NULL,
  `file_type` varchar(255) DEFAULT NULL,
  `statement_recipient_name` varchar(256) DEFAULT NULL,
  `statement_recipient_id` bigint(20) DEFAULT NULL,
  `party_name` varchar(256) DEFAULT NULL,
  `party_id` bigint(20) DEFAULT NULL,
  `legal_earner_party_id` bigint(20) DEFAULT NULL,
  `legal_earner_party_name` varchar(256) DEFAULT NULL,
  `distribution_date` varchar(256) DEFAULT NULL,
  `country_name` varchar(256) DEFAULT NULL,
  `Society_Name` varchar(256) DEFAULT NULL,
  `society_code` varchar(256) DEFAULT NULL,
  `performance_start_date` varchar(256) DEFAULT NULL,
  `performance_end_date` varchar(256) DEFAULT NULL,
  `song_title` varchar(256) DEFAULT NULL,
  `song_number` varchar(256) DEFAULT NULL,
  `member_share` varchar(256) DEFAULT NULL,
  `series_name` varchar(256) DEFAULT NULL,
  `program_name` varchar(256) DEFAULT NULL,
  `general` varchar(256) DEFAULT '',
  `radio` varchar(256) DEFAULT NULL,
  `tv` varchar(256) DEFAULT NULL,
  `tv_film` varchar(256) DEFAULT NULL,
  `cinema` varchar(256) DEFAULT '0',
  `total` decimal(11,6) DEFAULT NULL,
  `Adjustment_Distribution_Date` varchar(256) DEFAULT NULL,
  `adjustment_indicator` tinyint(1) DEFAULT NULL,
  `role_type` varchar(256) DEFAULT NULL,
  `stream_file_name` varchar(300) DEFAULT NULL,
  `load_date` varchar(256) DEFAULT NULL,
  `result` tinyint(1) DEFAULT NULL,
  `item_variation_id` bigint(20) DEFAULT NULL,
  `item_match_type` int(11) DEFAULT NULL,
  PRIMARY KEY (`row_data_crt`)
)");

        DB::unprepared("CREATE TABLE `etl_stg_bmi` (
  `row_data_crt` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `period` bigint(20) DEFAULT NULL,
  `w_or_p` char(1) DEFAULT NULL,
  `party_name` varchar(256) DEFAULT NULL,
  `party_id` bigint(20) DEFAULT NULL,
  `ip_number` bigint(20) DEFAULT NULL,
  `song_title` varchar(256) DEFAULT NULL,
  `song_number` varchar(256) DEFAULT NULL,
  `perf_source` varchar(256) DEFAULT NULL,
  `country_of_performance` varchar(256) DEFAULT NULL,
  `show_name` varchar(256) DEFAULT NULL,
  `episode_name` varchar(256) DEFAULT NULL,
  `show_number` bigint(20) DEFAULT NULL,
  `use_code` varchar(3) DEFAULT NULL,
  `timing` varchar(256) DEFAULT NULL,
  `participant_percent` double DEFAULT NULL,
  `perf_count` bigint(20) DEFAULT NULL,
  `bonus_level` tinytext,
  `royalty_amout` decimal(20,6) DEFAULT NULL,
  `withhold` tinytext,
  `perf_period` bigint(20) DEFAULT NULL,
  `current_activity_amt` double DEFAULT NULL,
  `hits_song_or_tv_net_super_usage_bonus` double DEFAULT NULL,
  `standards_or_tv_net_theme_bonus` double DEFAULT NULL,
  `foreign_society_adjustment` tinytext,
  `item_id` bigint(20) DEFAULT NULL,
  `load_date` datetime DEFAULT NULL,
  `stream_file_name` varchar(1000) DEFAULT NULL,
  `item_variation_id` bigint(20) DEFAULT NULL,
  `item_match_type` int(11) DEFAULT NULL,
  PRIMARY KEY (`row_data_crt`)
)");

        DB::unprepared("CREATE TABLE `etl_stg_orchard` (
  `row_data_crt` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `period` varchar(256) DEFAULT NULL,
  `activity_period` varchar(256) DEFAULT NULL,
  `DMS` varchar(256) DEFAULT NULL,
  `territory` varchar(256) DEFAULT NULL,
  `Orchard_UPC` bigint(20) DEFAULT NULL,
  `Manufacturers_UPC` varchar(256) DEFAULT NULL,
  `album_number` varchar(256) DEFAULT NULL,
  `Imprint_Label` varchar(256) DEFAULT NULL,
  `Artist_Name` varchar(256) DEFAULT NULL,
  `album_title` varchar(256) DEFAULT NULL,
  `song_title` varchar(256) DEFAULT NULL,
  `ISRC` varchar(256) DEFAULT NULL,
  `Volume` bigint(20) DEFAULT NULL,
  `song_number` bigint(20) DEFAULT NULL,
  `Quantity` varchar(20) DEFAULT NULL,
  `Unit_Price` double DEFAULT NULL,
  `Gross` double DEFAULT NULL,
  `Trans_Type` varchar(256) DEFAULT NULL,
  `Adjusted_Gross` double DEFAULT NULL,
  `Split_Rate` double DEFAULT NULL,
  `Label_Share_Net_Receipts` decimal(20,6) DEFAULT NULL,
  `Ringtone_Publishing` double DEFAULT NULL,
  `Publishing` double DEFAULT NULL,
  `Mech._Administrative_Fee` double DEFAULT NULL,
  `Preferred_Currency` varchar(256) DEFAULT NULL,
  `stream_file_name` varchar(1000) DEFAULT NULL,
  `result` tinyint(1) DEFAULT NULL,
  `MatchField` tinytext,
  `ValueField` bigint(20) DEFAULT NULL,
  `item_id` bigint(20) DEFAULT NULL,
  `ItemName` varchar(256) DEFAULT NULL,
  `load_date` datetime DEFAULT NULL,
  `item_variation_id` bigint(20) DEFAULT NULL,
  `item_match_type` int(11) DEFAULT NULL,
  `party_id` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`row_data_crt`)
)");

        DB::unprepared("CREATE TABLE `etl_stg_sesac` (
  `row_data_crt` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `Year` varchar(256) DEFAULT NULL,
  `Qtr` varchar(256) DEFAULT NULL,
  `song_title` varchar(256) DEFAULT NULL,
  `percent_Shares` varchar(256) DEFAULT NULL,
  `Synd` tinytext,
  `Strip` tinytext,
  `stream_file_name` varchar(1000) DEFAULT NULL,
  `MatchField` tinytext,
  `ValueField` double DEFAULT NULL,
  `item_id` bigint(20) DEFAULT NULL,
  `load_date` datetime DEFAULT NULL,
  `song_number` varchar(256) DEFAULT NULL,
  `Production_Title` varchar(256) DEFAULT NULL,
  `Episode_Title` varchar(256) DEFAULT NULL,
  `Episode_Number` tinytext,
  `Network` varchar(256) DEFAULT NULL,
  `Album_Title` tinytext,
  `Artist` tinytext,
  `Payment_Type` tinytext,
  `Chart` tinytext,
  `POSITION` tinytext,
  `Pay_number` bigint(20) DEFAULT NULL,
  `End_Air_Dt` datetime DEFAULT NULL,
  `Medium` tinytext,
  `Coverage` tinytext,
  `Pay_Code` tinytext,
  `Day_Part` varchar(256) DEFAULT NULL,
  `Perf_Category` varchar(256) DEFAULT NULL,
  `Perf_Type` varchar(256) DEFAULT NULL,
  `Perf_Duration` varchar(256) DEFAULT NULL,
  `Performances` bigint(20) DEFAULT NULL,
  `Occur` bigint(20) DEFAULT NULL,
  `Society_Abbrv` varchar(256) DEFAULT NULL,
  `Society_Country` varchar(256) DEFAULT NULL,
  `Earnings` decimal(11,6) DEFAULT NULL,
  `Perf_Air_Date` datetime DEFAULT NULL,
  `Establishment_Source` varchar(256) DEFAULT NULL,
  `item_variation_id` bigint(20) DEFAULT NULL,
  `party_id` varchar(256) DEFAULT NULL,
  `item_match_type` int(11) DEFAULT NULL,
  PRIMARY KEY (`row_data_crt`)
)");

        DB::unprepared("CREATE PROCEDURE `sp_title_data_ascap_domestic`()
BEGIN


	CREATE TEMPORARY TABLE IF NOT EXISTS etl_stg_ascap_domestic_tmp AS (SELECT * FROM etl_stg_ascap_domestic);

	#TRUNCATE TABLE etl_stg_ascap_domestic;

	#find item id based on the item code
	UPDATE  etl_stg_ascap_domestic_tmp etl_stg_tmp Inner Join royalty_items B ON etl_stg_tmp.song_number = B.item_code SET etl_stg_tmp.item_id = B.id;

	#create variations if needed
	INSERT INTO `royalty_item_variations` (`royalty_provider_id`, `royalty_share_id`, `royalty_type_id`, `royalty_item_id`) SELECT DISTINCT royalty_stream_files.royalty_provider_id, royalty_stream_files.royalty_share_id, royalty_stream_files.royalty_type_id, etl_stg_tmp.item_id FROM etl_stg_ascap_domestic_tmp as etl_stg_tmp
		INNER JOIN royalty_stream_files on royalty_stream_files.stream_file_name=etl_stg_tmp.stream_file_name and royalty_stream_files.deleted_at is null and royalty_stream_files.status=0
		INNER JOIN deals on deals.id=royalty_stream_files.deal_id
		LEFT JOIN royalty_item_variations on royalty_item_variations.royalty_provider_id = royalty_stream_files.royalty_provider_id and royalty_item_variations.royalty_share_id = royalty_stream_files.royalty_share_id and royalty_item_variations.royalty_type_id = royalty_stream_files.royalty_type_id and etl_stg_tmp.item_id = royalty_item_variations.royalty_item_id
	 WHERE etl_stg_tmp.item_id is not null and royalty_item_variations.id is null
	 GROUP BY royalty_stream_files.royalty_provider_id, royalty_stream_files.royalty_share_id, royalty_stream_files.royalty_type_id, etl_stg_tmp.item_id;


	#update variations based on the song/album name and the party id
	UPDATE  etl_stg_ascap_domestic_tmp A Inner Join royalty_item_name_variations B
	ON A.song_title = B.item_name and A.party_id = B.party_id
	SET A.item_id = B.item_id, A.item_variation_id = B.item_variation_id;

	#update stream variations based on item id, type, share, provider
	UPDATE etl_stg_ascap_domestic_tmp A INNER JOIN royalty_stream_files D on A.stream_file_name = D.stream_file_name
	INNER JOIN royalty_item_variations F on D.royalty_type_id = F.royalty_type_id
	AND A.item_id = F.royalty_item_id AND D.royalty_share_id = F.royalty_share_id
	AND F.royalty_provider_id = D.royalty_provider_id
	SET A.item_variation_id = F.id;

	#create bucket variation if needed;
	INSERT INTO `royalty_bucket_variations` (`variation_id`, `bucket_id`, `variation_percentage`)
		SELECT DISTINCT etl_stg_tmp.item_variation_id, deals.royalty_bucket_id, royalty_stream_files.percent FROM `etl_stg_ascap_domestic_tmp` as etl_stg_tmp
		INNER JOIN royalty_stream_files on royalty_stream_files.stream_file_name=etl_stg_tmp.stream_file_name and royalty_stream_files.deleted_at is null and royalty_stream_files.status=0
		INNER JOIN deals on deals.id=royalty_stream_files.deal_id
		LEFT JOIN royalty_bucket_variations on royalty_bucket_variations.variation_id = etl_stg_tmp.item_variation_id and royalty_bucket_variations.bucket_id = deals.royalty_bucket_id
		WHERE etl_stg_tmp.item_variation_id IS NOT NULL and royalty_bucket_variations.id IS NULL
		GROUP BY etl_stg_tmp.item_variation_id, deals.royalty_bucket_id;


	#create variations names id needed
	INSERT INTO `royalty_item_name_variations` (`item_id`, `item_name`, `item_variation_id`, `party_id`)
		SELECT DISTINCT etl_stg_tmp.item_id, etl_stg_tmp.song_title, etl_stg_tmp.item_variation_id, etl_stg_tmp.party_id FROM `etl_stg_ascap_domestic_tmp` as etl_stg_tmp
		LEFT OUTER JOIN  royalty_item_name_variations on
			royalty_item_name_variations.party_id=etl_stg_tmp.party_id and royalty_item_name_variations.item_id=etl_stg_tmp.item_id and
			royalty_item_name_variations.item_variation_id=etl_stg_tmp.item_variation_id and royalty_item_name_variations.item_name=etl_stg_tmp.song_title
		WHERE  etl_stg_tmp.item_variation_id IS NOT NULL and royalty_item_name_variations.id IS NULL
		GROUP BY  etl_stg_tmp.item_id, etl_stg_tmp.song_title, etl_stg_tmp.item_variation_id, etl_stg_tmp.party_id;


	#copy data to history table
	INSERT INTO etl_history_ascap_domestic select * from etl_stg_ascap_domestic_tmp;

	INSERT INTO royalty_stream
	(
	`statement_period_from`,
	`statement_period_to`,
	`song_number`,
	`song_title`,
	`album`,
	`album_number`,
	`album_title`,
	`region`,
	`royalty_currency`,
	`royalty_amount`,
	`royalty_base_currency`,
	`exchange_rate`,
	`royalty_amount_base`,
	`party_id`,
	`party_name`,
	`performance_source`,
	`serial_or_film`,
	`number_of_plays`,
	`royalty_country_iso`,
	society_name,
	`load_date`,
	file_name,
	royalty_item_id,
	royalty_item_variation_id,
	deleted_at,
	updated_at,
	created_at,
	stream_file_id,
	row_data_crt,
    episode_name
	)

	SELECT  STR_TO_DATE(REPLACE(Performance_Start_Date, '-', '/'),'%m,%d,%Y') as statement_period_from,
			STR_TO_DATE(REPLACE(Performance_End_Date, '-', '/'),'%m,%d,%Y') as statement_period_to,
			song_number as song_number,
			song_title as song_title,
			false as album,
			null as album_number,
			null as album_title,
			NUll as Region,
			null as royalty_currency,
			dollars as royalty_amount,
			null as base_currency,
			null as exchange_rate,
			null as base_amount,
			party_id as party_id,
			party_name as party_name,
			case when network_service IS NOT NULL then network_service
				 when music_user IS NOT NULL then music_user
                 when music_user_Genre IS NOT NULL then music_user_Genre
			END as performance_source,
			series_or_film as series_or_film,
			number_of_plays as number_of_plays,
			null as country,
			null as society_name,
			load_date as load_date,
			A.stream_file_name,
			item_id as royalty_item_id,
			item_variation_id as royalty_item_variation_id,
			B.deleted_at,
			B.updated_at,
			B.created_at,
			B.id as royalty_stream_file_id,
			row_data_crt,
            program_name

	FROM etl_stg_ascap_domestic_tmp A
	INNER JOIN royalty_stream_files B on A.stream_file_name = B.stream_file_name and B.deleted_at is null and B.status = 0;

	UPDATE royalty_stream_files INNER JOIN (SELECT DISTINCT stream_file_name FROM etl_stg_ascap_domestic_tmp) as etl_stg_tmp on etl_stg_tmp.stream_file_name = royalty_stream_files.stream_file_name SET Status = 1 WHERE royalty_stream_files.deleted_at is null and royalty_stream_files.status = 0;

	#delete temporary entries
	DROP TABLE IF EXISTS etl_stg_ascap_domestic_tmp;

END;;");

        DB::unprepared("CREATE PROCEDURE `sp_title_data_ascap_international`()
BEGIN

	CREATE TEMPORARY TABLE IF NOT EXISTS etl_stg_ascap_international_tmp AS (SELECT * FROM etl_stg_ascap_international);

	#TRUNCATE TABLE etl_stg_ascap_international;

	#find item id based on the item code
	UPDATE  etl_stg_ascap_international_tmp etl_stg_tmp Inner Join royalty_items B ON etl_stg_tmp.song_number = B.item_code SET etl_stg_tmp.item_id = B.id;

	#create variations if needed
	INSERT INTO `royalty_item_variations` (`royalty_provider_id`, `royalty_share_id`, `royalty_type_id`, `royalty_item_id`) SELECT DISTINCT royalty_stream_files.royalty_provider_id, royalty_stream_files.royalty_share_id, royalty_stream_files.royalty_type_id, etl_stg_tmp.item_id FROM etl_stg_ascap_international_tmp as etl_stg_tmp
		INNER JOIN royalty_stream_files on royalty_stream_files.stream_file_name=etl_stg_tmp.stream_file_name and royalty_stream_files.deleted_at is null and royalty_stream_files.status=0
		INNER JOIN deals on deals.id=royalty_stream_files.deal_id
		LEFT JOIN royalty_item_variations on royalty_item_variations.royalty_provider_id = royalty_stream_files.royalty_provider_id and royalty_item_variations.royalty_share_id = royalty_stream_files.royalty_share_id and royalty_item_variations.royalty_type_id = royalty_stream_files.royalty_type_id and etl_stg_tmp.item_id = royalty_item_variations.royalty_item_id
	 WHERE etl_stg_tmp.item_id is not null and royalty_item_variations.id is null
	 GROUP BY royalty_stream_files.royalty_provider_id, royalty_stream_files.royalty_share_id, royalty_stream_files.royalty_type_id, etl_stg_tmp.item_id;


	#update variations based on the song/album name and the party id
	UPDATE  etl_stg_ascap_international_tmp A Inner Join royalty_item_name_variations B
	ON A.song_title = B.item_name and A.party_id = B.party_id
	SET A.item_id = B.item_id, A.item_variation_id = B.item_variation_id;

	#update stream variations based on item id, type, share, provider
	UPDATE etl_stg_ascap_international_tmp A INNER JOIN royalty_stream_files D on A.stream_file_name = D.stream_file_name
	INNER JOIN royalty_item_variations F on D.royalty_type_id = F.royalty_type_id
	AND A.item_id = F.royalty_item_id AND D.royalty_share_id = F.royalty_share_id
	AND F.royalty_provider_id = D.royalty_provider_id
	SET A.item_variation_id = F.id;

	#create bucket variation if needed;
	INSERT INTO `royalty_bucket_variations` (`variation_id`, `bucket_id`, `variation_percentage`)
		SELECT DISTINCT etl_stg_tmp.item_variation_id, deals.royalty_bucket_id, royalty_stream_files.percent FROM `etl_stg_ascap_international_tmp` as etl_stg_tmp
		INNER JOIN royalty_stream_files on royalty_stream_files.stream_file_name=etl_stg_tmp.stream_file_name and royalty_stream_files.deleted_at is null and royalty_stream_files.status=0
		INNER JOIN deals on deals.id=royalty_stream_files.deal_id
		LEFT JOIN royalty_bucket_variations on royalty_bucket_variations.variation_id = etl_stg_tmp.item_variation_id and royalty_bucket_variations.bucket_id = deals.royalty_bucket_id
		WHERE etl_stg_tmp.item_variation_id IS NOT NULL and royalty_bucket_variations.id IS NULL
		GROUP BY etl_stg_tmp.item_variation_id, deals.royalty_bucket_id;


	#create variations names id needed
	INSERT INTO `royalty_item_name_variations` (`item_id`, `item_name`, `item_variation_id`, `party_id`)
		SELECT DISTINCT etl_stg_tmp.item_id, etl_stg_tmp.song_title, etl_stg_tmp.item_variation_id, etl_stg_tmp.party_id FROM `etl_stg_ascap_international_tmp` as etl_stg_tmp
		LEFT OUTER JOIN  royalty_item_name_variations on
			royalty_item_name_variations.party_id=etl_stg_tmp.party_id and royalty_item_name_variations.item_id=etl_stg_tmp.item_id and
			royalty_item_name_variations.item_variation_id=etl_stg_tmp.item_variation_id and royalty_item_name_variations.item_name=etl_stg_tmp.song_title
		WHERE  etl_stg_tmp.item_variation_id IS NOT NULL and royalty_item_name_variations.id IS NULL
		GROUP BY  etl_stg_tmp.item_id, etl_stg_tmp.song_title, etl_stg_tmp.item_variation_id, etl_stg_tmp.party_id;


	#copy data to history table
	INSERT INTO etl_history_ascap_international select * from etl_stg_ascap_international_tmp;

	#copy data to stream table
	INSERT INTO royalty_stream
	(
	`statement_period_from`,
	`statement_period_to`,
	`song_number`,
	`song_title`,
	`album`,
	`album_number`,
	`album_title`,
	`region`,
	`royalty_currency`,
	`royalty_amount`,
	`royalty_base_currency`,
	`exchange_rate`,
	`royalty_amount_base`,
	`party_id`,
	`party_name`,
	`performance_source`,
	`serial_or_film`,
	`number_of_plays`,
	`royalty_country_iso`,
	society_name,
	`load_date`,
	file_name,
	royalty_item_id,
	royalty_item_variation_id,
	deleted_at,
	updated_at,
	created_at,
	stream_file_id,
	row_data_crt,
    episode_name
	)
	SELECT  STR_TO_DATE(REPLACE(Performance_Start_Date, '-', '/'),'%m,%d,%Y') as statement_period_from,
			STR_TO_DATE(REPLACE(Performance_End_Date, '-', '/'),'%m,%d,%Y') as statement_period_to,

		song_number as song_number,
		song_title as song_title,
		false as album,
		null as album_number,
		null as album_title,
		Country_Name as Region,
		null as royalty_currency,
		TOTAL as royalty_amount,
		null as base_currency,
		null as exchange_rate,
		null as base_amount,
		party_id as party_id,
		party_name as party_name,
		Series_Name as series_or_film,
		Program_Name as program_name,
		null as number_of_plays,
		Country_Name as country,
		Society_Name as society_name,
		load_date as load_date,
		A.stream_file_name,
		item_id as royalty_item_id,
	    item_variation_id as royalty_item_variation_id,
        B.deleted_at,
        B.updated_at,
		B.created_at,
		B.id as royalty_stream_file_id,
		row_data_crt,
        program_name
	FROM etl_stg_ascap_international_tmp A
	INNER JOIN royalty_stream_files B on A.stream_file_name = B.stream_file_name and B.deleted_at is null and B.status = 0;

	UPDATE royalty_stream_files INNER JOIN (SELECT DISTINCT stream_file_name FROM etl_stg_ascap_international_tmp) as etl_stg_tmp on etl_stg_tmp.stream_file_name = royalty_stream_files.stream_file_name SET Status = 1 WHERE royalty_stream_files.deleted_at is null and royalty_stream_files.status = 0;

	#delete temporary entries
	DROP TABLE IF EXISTS etl_stg_ascap_international_tmp;

END;;");

        DB::unprepared("CREATE PROCEDURE `sp_title_data_bmi`()
BEGIN

	CREATE TEMPORARY TABLE IF NOT EXISTS etl_stg_bmi_tmp AS (SELECT * FROM etl_stg_bmi);

	DELETE FROM etl_stg_bmi;

    SELECT 'find item id based on the item code' AS 'DEBUG:';
	#find item id based on the item code
	UPDATE  etl_stg_bmi_tmp etl_stg_tmp Inner Join royalty_items B ON etl_stg_tmp.song_number = B.item_code SET etl_stg_tmp.item_id = B.id;

    SELECT 'create variations if needed' AS 'DEBUG:';
	#create variations if needed
	INSERT INTO `royalty_item_variations` (`royalty_provider_id`, `royalty_share_id`, `royalty_type_id`, `royalty_item_id`) SELECT DISTINCT royalty_stream_files.royalty_provider_id, royalty_stream_files.royalty_share_id, royalty_stream_files.royalty_type_id, etl_stg_tmp.item_id FROM etl_stg_bmi_tmp as etl_stg_tmp
		INNER JOIN royalty_stream_files on royalty_stream_files.stream_file_name=etl_stg_tmp.stream_file_name and royalty_stream_files.deleted_at is null and royalty_stream_files.status=0
		INNER JOIN deals on deals.id=royalty_stream_files.deal_id
		LEFT JOIN royalty_item_variations on royalty_item_variations.royalty_provider_id = royalty_stream_files.royalty_provider_id and royalty_item_variations.royalty_share_id = royalty_stream_files.royalty_share_id and royalty_item_variations.royalty_type_id = royalty_stream_files.royalty_type_id and etl_stg_tmp.item_id = royalty_item_variations.royalty_item_id
	 WHERE etl_stg_tmp.item_id is not null and royalty_item_variations.id is null
	 GROUP BY royalty_stream_files.royalty_provider_id, royalty_stream_files.royalty_share_id, royalty_stream_files.royalty_type_id, etl_stg_tmp.item_id;

    SELECT 'update variations based on the song/album name and the party id BUT only if the variation is attached to a bucket' AS 'DEBUG:';
	#update variations based on the song/album name and the party id BUT only if the variation is attached to a bucket
	UPDATE  etl_stg_bmi_tmp A Inner Join royalty_item_name_variations B
	ON A.song_title = B.item_name and A.party_id = B.party_id INNER JOIN royalty_bucket_variations C ON C.variation_id = B.item_variation_id
	SET A.item_id = B.item_id, A.item_variation_id = B.item_variation_id where  C.deleted_at is null;

    SELECT 'update stream variations based on item id, type, share, provider' AS 'DEBUG:';
	#update stream variations based on item id, type, share, provider
	UPDATE etl_stg_bmi_tmp A INNER JOIN royalty_stream_files D on A.stream_file_name = D.stream_file_name
	INNER JOIN royalty_item_variations F on D.royalty_type_id = F.royalty_type_id
	AND A.item_id = F.royalty_item_id AND D.royalty_share_id = F.royalty_share_id
	AND F.royalty_provider_id = D.royalty_provider_id
	SET A.item_variation_id = F.id;

    SELECT 'create bucket variation if needed' AS 'DEBUG:';
	#create bucket variation if needed;
	INSERT INTO `royalty_bucket_variations` (`variation_id`, `bucket_id`, `variation_percentage`)
		SELECT DISTINCT etl_stg_tmp.item_variation_id, deals.royalty_bucket_id, royalty_stream_files.percent FROM `etl_stg_bmi_tmp` as etl_stg_tmp
		INNER JOIN royalty_stream_files on royalty_stream_files.stream_file_name=etl_stg_tmp.stream_file_name and royalty_stream_files.deleted_at is null and royalty_stream_files.status=0
		INNER JOIN deals on deals.id=royalty_stream_files.deal_id
		LEFT JOIN royalty_bucket_variations on royalty_bucket_variations.variation_id = etl_stg_tmp.item_variation_id and royalty_bucket_variations.bucket_id = deals.royalty_bucket_id
		WHERE etl_stg_tmp.item_variation_id IS NOT NULL and royalty_bucket_variations.id IS NULL
		GROUP BY etl_stg_tmp.item_variation_id, deals.royalty_bucket_id;


    SELECT 'create variations names id needed' AS 'DEBUG:';
	#create variations names id needed
	INSERT INTO `royalty_item_name_variations` (`item_id`, `item_name`, `item_variation_id`, `party_id`)
		SELECT DISTINCT etl_stg_tmp.item_id, etl_stg_tmp.song_title, etl_stg_tmp.item_variation_id, etl_stg_tmp.party_id FROM `etl_stg_bmi_tmp` as etl_stg_tmp
		LEFT OUTER JOIN  royalty_item_name_variations on
			royalty_item_name_variations.party_id=etl_stg_tmp.party_id and royalty_item_name_variations.item_id=etl_stg_tmp.item_id and
			royalty_item_name_variations.item_variation_id=etl_stg_tmp.item_variation_id and royalty_item_name_variations.item_name=etl_stg_tmp.song_title
		WHERE  etl_stg_tmp.item_variation_id IS NOT NULL and royalty_item_name_variations.id IS NULL
		GROUP BY  etl_stg_tmp.item_id, etl_stg_tmp.song_title, etl_stg_tmp.item_variation_id, etl_stg_tmp.party_id;


    SELECT 'copy the bmi data to history table' AS 'DEBUG:';
	#copy the bmi data to history table
	INSERT INTO etl_history_bmi select * from etl_stg_bmi_tmp;

    SELECT 'copy the bmi data to stream table' AS 'DEBUG:';
	#copy the bmi data to stream table
	INSERT INTO royalty_stream
	(
	`statement_period_from`,
	`statement_period_to`,
	`song_number`,
	`song_title`,
	`album`,
	`album_number`,
	`album_title`,
	`region`,
	`royalty_currency`,
	`royalty_amount`,
	`royalty_base_currency`,
	`exchange_rate`,
	`royalty_amount_base`,
	`party_id`,
	`party_name`,
	`performance_source`,
	`serial_or_film`,
	`number_of_plays`,
	`royalty_country_iso`,
	society_name,
	`load_date`,
	file_name,
	royalty_item_id,
	royalty_item_variation_id,
	deleted_at,
	updated_at,
	created_at,
	stream_file_id,
	row_data_crt,
    episode_name
	)

	SELECT  concat(Concat(concat(cast(left(A.period,4)as char(8)), '-' )  , case when length(A.period) = 5 then concat('0',cast(right(A.period,1)as char(8)))
				 when length(A.period) > 5 then cast(right(A.period,2)as char(8))
	end), '-01') as  statement_period_from,
			concat(Concat(concat(cast(left(A.period,4)as char(8)), '-' )  , case when length(A.period) = 5 then concat('0',cast(right(A.period,1)as char(8)))
				 when length(A.period) > 5 then cast(right(A.period,2)as char(8))
	end), '-01')  as statement_period_to,
			song_number as song_number,
			song_title as song_title,
			false as album,
			null as album_number,
			null as album_title,
			country_of_performance as Region,
			null as royalty_currency,
			royalty_amout as royalty_amount,
			null as base_currency,
			null as exchange_rate,
			null as base_amount,
			party_id as party_id,
			party_name as party_name,
			perf_source as performance_source,
			show_name as series_or_film,
			perf_count as number_of_plays,
			country_of_performance as country,
			null as society_name,
			load_date as load_date,
			A.stream_file_name,
			A.item_id as item_id,
			A.item_variation_id as item_variation_id,
			B.deleted_at,
			B.updated_at,
			B.created_at,
			B.id as royalty_stream_file_id,
			row_data_crt,
            episode_name
	FROM etl_stg_bmi_tmp A
	INNER JOIN royalty_stream_files B on A.stream_file_name = B.stream_file_name and B.deleted_at is null and B.status = 0;

	UPDATE royalty_stream_files INNER JOIN (SELECT DISTINCT stream_file_name FROM etl_stg_bmi_tmp) as etl_stg_tmp on etl_stg_tmp.stream_file_name = royalty_stream_files.stream_file_name SET Status = 1 WHERE royalty_stream_files.deleted_at is null and royalty_stream_files.status = 0;

    SELECT 'delete temporary entries' AS 'DEBUG:';
	#delete temporary entries
	DELETE from etl_stg_bmi_tmp;

END;;");

        DB::unprepared("CREATE PROCEDURE `sp_title_data_orchard`()
BEGIN

	CREATE TEMPORARY TABLE IF NOT EXISTS etl_stg_orchard_tmp AS (SELECT * FROM etl_stg_orchard);

	#TRUNCATE TABLE etl_stg_orchard;

	#find item id based on the item code
	UPDATE  etl_stg_orchard_tmp etl_stg_tmp Inner Join royalty_items B ON etl_stg_tmp.album_number = B.item_code SET etl_stg_tmp.item_id = B.id;

	#create variations if needed
	INSERT INTO `royalty_item_variations` (`royalty_provider_id`, `royalty_share_id`, `royalty_type_id`, `royalty_item_id`) SELECT DISTINCT royalty_stream_files.royalty_provider_id, royalty_stream_files.royalty_share_id, royalty_stream_files.royalty_type_id, etl_stg_tmp.item_id FROM etl_stg_orchard_tmp as etl_stg_tmp
		INNER JOIN royalty_stream_files on royalty_stream_files.stream_file_name=etl_stg_tmp.stream_file_name and royalty_stream_files.deleted_at is null and royalty_stream_files.status=0
		INNER JOIN deals on deals.id=royalty_stream_files.deal_id
		LEFT JOIN royalty_item_variations on royalty_item_variations.royalty_provider_id = royalty_stream_files.royalty_provider_id and royalty_item_variations.royalty_share_id = royalty_stream_files.royalty_share_id and royalty_item_variations.royalty_type_id = royalty_stream_files.royalty_type_id and etl_stg_tmp.item_id = royalty_item_variations.royalty_item_id
	 WHERE etl_stg_tmp.item_id is not null and royalty_item_variations.id is null
	 GROUP BY royalty_stream_files.royalty_provider_id, royalty_stream_files.royalty_share_id, royalty_stream_files.royalty_type_id, etl_stg_tmp.item_id;


	#update variations based on the song/album name and the party id
	UPDATE  etl_stg_orchard_tmp A Inner Join royalty_item_name_variations B
	ON A.album_title = B.item_name and A.party_id = B.party_id
	SET A.item_id = B.item_id, A.item_variation_id = B.item_variation_id;

	#update stream variations based on item id, type, share, provider
	UPDATE etl_stg_orchard_tmp A INNER JOIN royalty_stream_files D on A.stream_file_name = D.stream_file_name
	INNER JOIN royalty_item_variations F on D.royalty_type_id = F.royalty_type_id
	AND A.item_id = F.royalty_item_id AND D.royalty_share_id = F.royalty_share_id
	AND F.royalty_provider_id = D.royalty_provider_id
	SET A.item_variation_id = F.id;

	#create bucket variation if needed;
	INSERT INTO `royalty_bucket_variations` (`variation_id`, `bucket_id`, `variation_percentage`)
		SELECT DISTINCT etl_stg_tmp.item_variation_id, deals.royalty_bucket_id, royalty_stream_files.percent FROM `etl_stg_orchard_tmp` as etl_stg_tmp
		INNER JOIN royalty_stream_files on royalty_stream_files.stream_file_name=etl_stg_tmp.stream_file_name and royalty_stream_files.deleted_at is null and royalty_stream_files.status=0
		INNER JOIN deals on deals.id=royalty_stream_files.deal_id
		LEFT JOIN royalty_bucket_variations on royalty_bucket_variations.variation_id = etl_stg_tmp.item_variation_id and royalty_bucket_variations.bucket_id = deals.royalty_bucket_id
		WHERE etl_stg_tmp.item_variation_id IS NOT NULL and royalty_bucket_variations.id IS NULL
		GROUP BY etl_stg_tmp.item_variation_id, deals.royalty_bucket_id;


	#create variations names id needed
	INSERT INTO `royalty_item_name_variations` (`item_id`, `item_name`, `item_variation_id`, `party_id`)
		SELECT DISTINCT etl_stg_tmp.item_id, etl_stg_tmp.album_title, etl_stg_tmp.item_variation_id, etl_stg_tmp.party_id FROM `etl_stg_orchard_tmp` as etl_stg_tmp
		LEFT OUTER JOIN  royalty_item_name_variations on
			royalty_item_name_variations.party_id=etl_stg_tmp.party_id and royalty_item_name_variations.item_id=etl_stg_tmp.item_id and
			royalty_item_name_variations.item_variation_id=etl_stg_tmp.item_variation_id and royalty_item_name_variations.item_name=etl_stg_tmp.song_title
		WHERE  etl_stg_tmp.item_variation_id IS NOT NULL and royalty_item_name_variations.id IS NULL
		GROUP BY  etl_stg_tmp.item_id, etl_stg_tmp.song_title, etl_stg_tmp.item_variation_id, etl_stg_tmp.party_id;


	#copy data to history table
	INSERT INTO etl_history_orchard select * from etl_stg_orchard_tmp;


	INSERT INTO royalty_stream
	(
	`statement_period_from`,
	`statement_period_to`,
	`song_number`,
	`song_title`,
	`album`,
	`album_number`,
	`album_title`,
	`region`,
	`royalty_currency`,
	`royalty_amount`,
	`royalty_base_currency`,
	`exchange_rate`,
	`royalty_amount_base`,
	`party_id`,
	`party_name`,
	`performance_source`,
	`serial_or_film`,
	`number_of_plays`,
	`royalty_country_iso`,
	society_name,
	`load_date`,
	file_name,
	royalty_item_id,
	royalty_item_variation_id,
	deleted_at,
	updated_at,
	created_at,
	stream_file_id,
	row_data_crt
	)
SELECT  	concat(SUBSTRING(A.Period,1,4), '-01-01') as statement_period_from,
			concat(SUBSTRING(A.activity_period,1,4), '-01-01') as statement_period_to,
			song_number as song_number,
			song_title as song_title,
			true as album,
			album_number as album_number,
			album_title as album_title,
			territory as Region,
			null as royalty_currency,
			Label_Share_Net_Receipts as royalty_amount,
			Preferred_Currency as base_currency,
			null as exchange_rate,
			null as base_amount,
			party_id as party_id,
			party_id as party_name,
			DMS as performance_source,
			null as series_or_film,
			Quantity as number_of_plays,
			territory as country,
			null as society_name,
			load_date as load_date,
			A.stream_file_name,
			item_id as royalty_item_id,
			item_variation_id as royalty_item_variation_id,
			B.deleted_at,
			B.updated_at,
			B.created_at,
			B.id as royalty_stream_file_id,
			row_data_crt
	FROM etl_stg_orchard_tmp A
	INNER JOIN royalty_stream_files B on A.stream_file_name = B.stream_file_name and B.deleted_at is null and B.status = 0;


	UPDATE royalty_stream_files INNER JOIN (SELECT DISTINCT stream_file_name FROM etl_stg_orchard_tmp) as etl_stg_tmp on etl_stg_tmp.stream_file_name = royalty_stream_files.stream_file_name SET Status = 1 WHERE royalty_stream_files.deleted_at is null and royalty_stream_files.status = 0;

	#delete temporary entries
	DROP TABLE IF EXISTS etl_stg_orchard_tmp;


END;;");

        DB::unprepared("CREATE PROCEDURE `sp_title_data_sesac`()
BEGIN

	CREATE TEMPORARY TABLE IF NOT EXISTS etl_stg_sesac_tmp AS (SELECT * FROM etl_stg_sesac);

	#TRUNCATE TABLE etl_stg_sesac;

	#find item id based on the item code
	UPDATE  etl_stg_sesac_tmp etl_stg_tmp Inner Join royalty_items B ON etl_stg_tmp.song_number = B.item_code SET etl_stg_tmp.item_id = B.id;

	#create variations if needed
	INSERT INTO `royalty_item_variations` (`royalty_provider_id`, `royalty_share_id`, `royalty_type_id`, `royalty_item_id`) SELECT DISTINCT royalty_stream_files.royalty_provider_id, royalty_stream_files.royalty_share_id, royalty_stream_files.royalty_type_id, etl_stg_tmp.item_id FROM etl_stg_sesac_tmp as etl_stg_tmp
		INNER JOIN royalty_stream_files on royalty_stream_files.stream_file_name=etl_stg_tmp.stream_file_name and royalty_stream_files.deleted_at is null and royalty_stream_files.status=0
		INNER JOIN deals on deals.id=royalty_stream_files.deal_id
		LEFT JOIN royalty_item_variations on royalty_item_variations.royalty_provider_id = royalty_stream_files.royalty_provider_id and royalty_item_variations.royalty_share_id = royalty_stream_files.royalty_share_id and royalty_item_variations.royalty_type_id = royalty_stream_files.royalty_type_id and etl_stg_tmp.item_id = royalty_item_variations.royalty_item_id
	 WHERE etl_stg_tmp.item_id is not null and royalty_item_variations.id is null
	 GROUP BY royalty_stream_files.royalty_provider_id, royalty_stream_files.royalty_share_id, royalty_stream_files.royalty_type_id, etl_stg_tmp.item_id;


	#update variations based on the song/album name and the party id
	UPDATE  etl_stg_sesac_tmp A Inner Join royalty_item_name_variations B
	ON A.song_title = B.item_name and A.party_id = B.party_id
	SET A.item_id = B.item_id, A.item_variation_id = B.item_variation_id;

	#update stream variations based on item id, type, share, provider
	UPDATE etl_stg_sesac_tmp A INNER JOIN royalty_stream_files D on A.stream_file_name = D.stream_file_name
	INNER JOIN royalty_item_variations F on D.royalty_type_id = F.royalty_type_id
	AND A.item_id = F.royalty_item_id AND D.royalty_share_id = F.royalty_share_id
	AND F.royalty_provider_id = D.royalty_provider_id
	SET A.item_variation_id = F.id;

	#create bucket variation if needed;
	INSERT INTO `royalty_bucket_variations` (`variation_id`, `bucket_id`, `variation_percentage`)
		SELECT DISTINCT etl_stg_tmp.item_variation_id, deals.royalty_bucket_id, royalty_stream_files.percent FROM `etl_stg_sesac_tmp` as etl_stg_tmp
		INNER JOIN royalty_stream_files on royalty_stream_files.stream_file_name=etl_stg_tmp.stream_file_name and royalty_stream_files.deleted_at is null and royalty_stream_files.status=0
		INNER JOIN deals on deals.id=royalty_stream_files.deal_id
		LEFT JOIN royalty_bucket_variations on royalty_bucket_variations.variation_id = etl_stg_tmp.item_variation_id and royalty_bucket_variations.bucket_id = deals.royalty_bucket_id
		WHERE etl_stg_tmp.item_variation_id IS NOT NULL and royalty_bucket_variations.id IS NULL
		GROUP BY etl_stg_tmp.item_variation_id, deals.royalty_bucket_id;


	#create variations names id needed
	INSERT INTO `royalty_item_name_variations` (`item_id`, `item_name`, `item_variation_id`, `party_id`)
		SELECT DISTINCT etl_stg_tmp.item_id, etl_stg_tmp.song_title, etl_stg_tmp.item_variation_id, etl_stg_tmp.party_id FROM `etl_stg_sesac_tmp` as etl_stg_tmp
		LEFT OUTER JOIN  royalty_item_name_variations on
			royalty_item_name_variations.party_id=etl_stg_tmp.party_id and royalty_item_name_variations.item_id=etl_stg_tmp.item_id and
			royalty_item_name_variations.item_variation_id=etl_stg_tmp.item_variation_id and royalty_item_name_variations.item_name=etl_stg_tmp.song_title
		WHERE  etl_stg_tmp.item_variation_id IS NOT NULL and royalty_item_name_variations.id IS NULL
		GROUP BY  etl_stg_tmp.item_id, etl_stg_tmp.song_title, etl_stg_tmp.item_variation_id, etl_stg_tmp.party_id;


	#copy  data to history table
	INSERT INTO etl_history_sesac select * from etl_stg_sesac_tmp;


	INSERT INTO royalty_stream
	(
	`statement_period_from`,
	`statement_period_to`,
	`song_number`,
	`song_title`,
	`album`,
	`album_number`,
	`album_title`,
	`region`,
	`royalty_currency`,
	`royalty_amount`,
	`royalty_base_currency`,
	`exchange_rate`,
	`royalty_amount_base`,
	`party_id`,
	`party_name`,
	`serial_or_film`,
	`number_of_plays`,
	`royalty_country_iso`,
	society_name,
	`load_date`,
	file_name,
	royalty_item_id,
	royalty_item_variation_id,
	deleted_at,
	updated_at,
	created_at,
	stream_file_id,
	row_data_crt,
    performance_source,
    episode_name
	)

	SELECT  concat(Concat(cast(year as char(4)), case when Qtr = '4' then '-10'  when Qtr = '3' then '07' when Qtr = '2' then '04' when Qtr = '1' then '01' END), '-01')  as statement_period_from,
			concat(Concat(cast(year as char(4)), case when Qtr = '4' then '-12'  when Qtr = '3' then '09' when Qtr = '2' then '06' when Qtr = '1' then '03' END), '-01')  as statement_period_to,
			song_number  as song_number,
			song_title as song_title,
			false as album_or_single,
			null as album_number,
			null as album_title,
			Society_Country as Region,
			null as royalty_currency,
			Earnings as royalty_amount,
			null as base_currency,
			null as exchange_rate,
			null as base_amount,
			party_id as party_id,
			null as party_name,
			Production_Title as series_or_film,
			Performances as number_of_plays,
			Society_Country as country,
			Society_Abbrv as society_name,
			load_date as load_date,
			A.stream_file_name,
			item_id as royalty_item_id,
			item_variation_id as royalty_item_variation_id,
			B.deleted_at,
			B.updated_at,
			B.created_at,
			B.id as royalty_stream_file_id,
			row_data_crt,
            Perf_Category,
            Episode_Title
	FROM etl_stg_sesac_tmp A
	INNER JOIN royalty_stream_files B on A.stream_file_name = B.stream_file_name and B.deleted_at is null and B.status = 0;

	UPDATE royalty_stream_files INNER JOIN (SELECT DISTINCT stream_file_name FROM etl_stg_sesac_tmp) as etl_stg_tmp on etl_stg_tmp.stream_file_name = royalty_stream_files.stream_file_name SET Status = 1 WHERE royalty_stream_files.deleted_at is null and royalty_stream_files.status = 0;

	#delete temporary entries
	DROP TABLE IF EXISTS etl_stg_sesac_tmp;
END;;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('etl_history_ascap_domestic');
        Schema::drop('etl_history_ascap_international');
        Schema::drop('etl_history_bmi');
        Schema::drop('etl_history_orchard');
        Schema::drop('etl_history_sesac');
        Schema::drop('etl_stg_ascap_domestic');
        Schema::drop('etl_stg_ascap_international');
        Schema::drop('etl_stg_bmi');
        Schema::drop('etl_stg_orchard');
        Schema::drop('etl_stg_sesac');

        DB::unprepared('DROP PROCEDURE `sp_title_data_ascap_domestic`');
        DB::unprepared('DROP PROCEDURE `sp_title_data_ascap_international`');
        DB::unprepared('DROP PROCEDURE `sp_title_data_bmi`');
        DB::unprepared('DROP PROCEDURE `sp_title_data_orchard`');
        DB::unprepared('DROP PROCEDURE `sp_title_data_sesac`');
    }

}
