<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class RoyaltyStreamFile extends ApiModel
{
    use SoftDeletingTrait;

    const CREATED_AT = 'created';
    const UPDATED_AT = 'updated';

    protected $guarded = [RoyaltyStreamFile::CREATED_AT, RoyaltyStreamFile::UPDATED_AT];
    protected $table = 'royalty_stream_file';

    const PERIOD_TYPE_MONTH = 0;
    const PERIOD_TYPE_QUARTER = 1;

    public static function boot()
    {
        parent::boot();
        RoyaltyStreamFile::deleting(function ($royaltyFile) {
            $royaltyFile->deleted = 1;
            $royaltyFile->update();
        });
    }

    public function deal()
    {
        return $this->belongsTo('Deal', 'deal_id', 'id');
    }

    protected function accessors()
    {
        $accessors = [];
        $accessors['pdf_url'] = $this->getPdfUrlAttribute();
        return $accessors;
    }

    public function getPdfUrlAttribute() {
        if ($this->has_pdf) {
            return URL::route('api.royalty_stream_files.pdf', ['id' => $this->id]);
        }
        return null;
    }

    public function scopeLastRoyaltyFile($query, $dealId)
    {
        return $query->where('deal_id', '=', $dealId)->orderby('period_year', 'desc')->orderby('period_month', 'desc')->first();
    }
}
