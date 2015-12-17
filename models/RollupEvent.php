<?php

class RollupEvent extends Eloquent
{
    const ROLLUP_SONG_DEAL = "rollup_song_deal";
    const ROLLUP_ROYALTY_STREAM_PERFORMANCE_SOURCE = "rollup_royalty_stream_performance_source";
    const ROLLUP_SOURCE_NAME_MIN_MAX_PERIOD = "rollup_source_name_mix_max_period";
    const ROLLUP_SONG_OR_ALBUM_TITLE_MIN_MAX_PERIOD = "rollup_song_or_album_title_min_max_period";
    const ROLLUP_SONG_DEAL_QTR_AMT = "rollup_song_deal_qtr_amt";
    const ROLLUP_LOAD_ROLLUP_PROVIDER_SOURCE_EPS = "rollup_load_rollup_provider_source_eps";

    const CREATED_AT = 'created';
    const UPDATED_AT = 'updated';

    protected $table = 'rollup_event';

    public static function EXECUTE_ROLLUP_SONG_DEAL($modelId) { RollupEvent::execute(RollupEvent::ROLLUP_SONG_DEAL, $modelId); }
    public static function EXECUTE_ROLLUP_ROYALTY_STREAM_PERFORMANCE_SOURCE() { RollupEvent::execute(RollupEvent::ROLLUP_ROYALTY_STREAM_PERFORMANCE_SOURCE); }
    public static function EXECUTE_ROLLUP_SOURCE_NAME_MIN_MAX_PERIOD() { RollupEvent::execute(RollupEvent::ROLLUP_SOURCE_NAME_MIN_MAX_PERIOD); }
    public static function EXECUTE_ROLLUP_SONG_OR_ALBUM_TITLE_MIN_MAX_PERIOD() { RollupEvent::execute(RollupEvent::ROLLUP_SONG_OR_ALBUM_TITLE_MIN_MAX_PERIOD); }
    public static function EXECUTE_ROLLUP_SONG_DEAL_QTR_AMT($modelId) { RollupEvent::execute(RollupEvent::ROLLUP_SONG_DEAL_QTR_AMT, $modelId); }
    public static function EXECUTE_ROLLUP_LOAD_ROLLUP_PROVIDER_SOURCE_EPS() { RollupEvent::execute(RollupEvent::ROLLUP_LOAD_ROLLUP_PROVIDER_SOURCE_EPS); }

    public static function EXECUTE_ROLLUP_TABLES($dealId)
    {
        self::EXECUTE_DEAL_ROLLUPS($dealId);
        self::EXECUTE_OVERALL_ROLLUPS();
    }

    public static function EXECUTE_OVERALL_ROLLUPS()
    {
        self::EXECUTE_ROLLUP_ROYALTY_STREAM_PERFORMANCE_SOURCE();
        self::EXECUTE_ROLLUP_SOURCE_NAME_MIN_MAX_PERIOD();
        self::EXECUTE_ROLLUP_SONG_OR_ALBUM_TITLE_MIN_MAX_PERIOD();
        self::EXECUTE_ROLLUP_LOAD_ROLLUP_PROVIDER_SOURCE_EPS();
    }

    public static function EXECUTE_DEAL_ROLLUPS($dealId)
    {
        self::EXECUTE_ROLLUP_SONG_DEAL($dealId);
        self::EXECUTE_ROLLUP_SONG_DEAL_QTR_AMT($dealId);
    }

    public static function execute($eventName, $modelId = 0)
    {
        $model = new RollupEvent();
        $model->name = $eventName;
        $model->model_id = $modelId;
        $model->save();
    }
}