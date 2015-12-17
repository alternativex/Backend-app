<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Deal extends ApiModel implements ApiQueryableInterface {

    use SoftDeletingTrait;
    const CREATED_AT = 'created';
    const UPDATED_AT = 'updated';

    protected $table = 'deal';
    protected $guarded = [Deal::CREATED_AT, Deal::UPDATED_AT];
    
    const ETL_STATUS_PROCESSING = 'processing';
    const ETL_STATUS_PROCESSED = 'processed';
    const ETL_STATUS_ERROR = 'error';

    const STATUS_UNREVIEWED = 'unreviewed';
    const STATUS_REVIEWED = 'reviewed';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_REJECTED = 'rejected';
    const STATUS_PASS = 'pass';
    const STATUS_LOST = 'lost';
    const STATUS_CONTACTED = 'contacted';

    public static function boot()
    {
        parent::boot();
        Deal::deleting(function ($deal) {
            $deal->deleted = 1;
            $deal->update();
        });
    }

    public function royaltyStreamFiles() {
        return $this->hasMany('RoyaltyStreamFile');
    }

    public function company() {
        return $this->belongsTo('Company','company_id','id');
    }

    public static function apiQuery() {

        $query = self::query();
        if (Auth::user()->isPublisherAdmin() || Auth::user()->isPublisher())
        {
            $query->where('company_id', "=", Auth::user()["company_id"]);
        }

        if (Auth::user()->isPayee())
        {
            $query->where(Deal::table().".payee_code", '=', Auth::user()["code"]);
        }
        return $query;
    }

    public static function apiProviders($id) {
        $query = self::query();
        return $query->select('royalty_provider.id as id', 'royalty_provider.royalty_provider_name as provider', 'royalty_share.royalty_share_name as share', 'royalty_type.royalty_type_name as type')
                                  ->join(RoyaltyStreamFile::table()." as royalty_stream_file", 'royalty_stream_file.deal_id', '=', 'deal_id')
                                  ->join(RoyaltyProvider::table()." as royalty_provider", 'royalty_provider.id','=','royalty_provider_id')
                                  ->join(RoyaltyShare::table()." as royalty_share", 'royalty_share.id','=','royalty_share_id')
                                  ->join(RoyaltyType::table()." as royalty_type", 'royalty_type.id','=','royalty_type_id')
                                  ->groupBy('royalty_provider.royalty_provider_name', 'royalty_share.royalty_share_name', 'royalty_type.royalty_type_name')
                                  ->where('deal_id',$id)->get();
    }


    public static function apiRoyaltiesEarnedByPerformanceDate($id) {
        $query = self::query();
        return $query->select('royalty_stream_file.period_year_quarter as date', DB::raw("SUM(royalty_stream.royalty_amount) as amount"))
                                  ->join(RoyaltyStreamFile::table()." as royalty_stream_file", 'royalty_stream_file.deal_id', '=', 'deal_id')
                                  ->join(RoyaltyStream::table()." as royalty_stream", 'royalty_stream_file.id','=','stream_file_id')
                                  ->groupBy('royalty_stream_file.period_year_quarter')
                                  ->where('deal_id',$id)->get();

    }

    public static function apiStats($id){
        $query = self::query();


        $statsStartDate = new DateTime();
        //get average

        if (sizeof(RoyaltyStreamFile::lastRoyaltyFile($id)->get())>0) {
            $lastRoyaltyStreamFile = RoyaltyStreamFile::lastRoyaltyFile($id)->get()[0];
            $statsStartDate->setDate($lastRoyaltyStreamFile->period_year,
                $lastRoyaltyStreamFile->period_month, 1);
        }


        $last12monthDate = $statsStartDate->modify('-12 month');
        $last12monthAmount = $query->select(DB::raw("SUM(royalty_stream.royalty_amount) as amount"))
                                  ->join(RoyaltyStreamFile::table()." as royalty_stream_file", 'royalty_stream_file.deal_id', '=', 'deal.id')
                                  ->join(RoyaltyStream::table()." as royalty_stream", 'royalty_stream_file.id','=','stream_file_id')
                                  ->where('deal_id',$id)
                                  ->where(DB::raw("CONCAT(royalty_stream_file.period_year, '-',royalty_stream_file.period_month)"),'>=',$last12monthDate)
                                  ->first()["amount"];

        $query = self::query();

        $last12monthAmountTop5Songs = $query->select('royalty_stream.song_title', DB::raw("SUM(royalty_stream.royalty_amount) as amount"))
                                  ->join(RoyaltyStreamFile::table()." as royalty_stream_file", 'royalty_stream_file.deal_id', '=', 'deal.id')
                                  ->join(RoyaltyStream::table()." as royalty_stream", 'royalty_stream_file.id','=','stream_file_id')
                                  ->groupBy('royalty_stream.song_title')
                                  ->where('deal_id',$id)
                                  ->orderBy('amount','desc')
                                  ->limit(5)
                                  ->where(DB::raw("CONCAT(royalty_stream_file.period_year, '-',royalty_stream_file.period_month)"),'>=',$last12monthDate)
                                  ->get();

        $sum = 0;
        foreach ($last12monthAmountTop5Songs as $song) {
            $sum += $song->amount;
        }

        return ['last_12_month_avg'=>$last12monthAmount, 'top_5_song_avg'=>$sum];       
    }

    public static function apiPdfs($id){
        return [['date'=>'11/11/2014','url'=>'xxx.pdf','name'=>'xxx.pdf'],
                ['date'=>'11/11/2014','url'=>'xxx.pdf','name'=>'www.pdf']];       
    }

    public static function apiStatuses() {
        return [self::STATUS_UNREVIEWED, self::STATUS_REVIEWED, self::STATUS_ACCEPTED, 
        self::STATUS_REJECTED, 
        self::STATUS_PASS, self::STATUS_LOST, self::STATUS_CONTACTED];
    }

    public static function apiSendAdviserContactEmail($id)
    {
        $deal = Deal::find($id);
        $user = User::apiUserByPayeeCode($deal->payee_code);

        Mail::send('emails.deal.adviser_contact_email', ['deal' => $deal, "user" => $user],
            function($message) {
                $message->from('no-reply@royaltyexchange.com')
                    ->to(Config::get("mail.advisers"))
                    ->subject('Royalty Analysis - Publisher Contact Request');
            }
        );
        return ['success' => true];
    }
}
