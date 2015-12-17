<?php

use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\UserTrait;

class RoyaltyPaymentFile extends ApiModel implements ApiQueryableInterface
{
    protected $table = 'royalty_payment_file';
    protected $guarded = ['id', 'created_at', 'updated_at'];

    const STATUS_UPLOADED = 'uploaded';
    const STATUS_PAYMENTS_PROCESSED = 'payments_processed';
    const STATUS_PROCESSED = 'processed';

    public static function apiQuery()
    {
        $query = self::query();
        if (!Auth::user()->isAdmin())
            $query->where('company_id', '=', Auth::user()["company_id"]);
        return $query;
    }

    public static function upload()
    {
        $uploadPath = storage_path('temp')."/".basename($_FILES['file']['name']);
        if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadPath)) {
            $service = App::make("RoyaltyPaymentFileService");
            return $service->process($uploadPath, Auth::user()["company_id"], Input::all());
        } else {
            return null;
        }
    }

    public static function paymentProcessed()
    {
        return RoyaltyPaymentFile::where("status", "=", self::STATUS_PAYMENTS_PROCESSED);
    }

    public static function apiDownloadFromDropbox($fileId)
    {
        $service = App::make("RoyaltyPaymentFileService");
        return $service->downloadFromDropbox($fileId);
    }

    public static function apiDownloadHeadersFile()
    {
        $service = App::make("RoyaltyPaymentFileService");
        return $service->downloadHeadersFile();
    }
}