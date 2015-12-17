<?php

use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\UserTrait;

class EmailNotification extends ApiModel
{
    const COMPANY_NO_CREDIT = "COMPANY_NO_CREDIT";
    protected $table = 'email_notification';
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public static function createUpdateNotification($model, $modelId, $type)
    {
        $en = self::findNotification($model, $modelId, $type);
        if (!empty($en))
        {
            $en->notification_sent = date("Y-m-d H:i:s");
            $en->save();
            return $en;
        }
        else
            return EmailNotification::create(["model" => $model, "model_id" => $modelId, "type" => $type, "notification_sent" => date("Y-m-d H:i:s")]);
    }

    public static function findNotification($model, $modelId, $type)
    {
        return EmailNotification::where('model', '=', $model)->where("model_id", "=", $modelId)->where("type", "=", $type)->first();
    }

    public function isValidNotification($seconds = 86400/* seconds in a day */)
    {
        if ($this->notification_sent > date('Y-m-d H:i:s', time()-$seconds))
            return true;
        else
            return false;
    }
}