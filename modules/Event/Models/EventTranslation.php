<?php

namespace Modules\Event\Models;

class EventTranslation extends Event
{
    protected $table = 'bravo_event_translations';

    protected $fillable = [
        'title',
        'content',
        'faqs',
        'address',
        'surrounding'
    ];

    protected $slugField = false;
    protected $seo_type = 'event_translation';

    protected $cleanFields = [
        'content'
    ];
    protected $casts = [
        'faqs' => 'array',
        'surrounding' => 'array',
    ];

    public static function boot()
    {
        parent::boot();
        static::saving(function ($table) {
            unset($table->extra_price);
            unset($table->price);
            unset($table->sale_price);
        });
    }

    public function getSeoType()
    {
        return $this->seo_type;
    }

    public function getRecordRoot()
    {
        return $this->belongsTo(Event::class, 'origin_id');
    }
}
