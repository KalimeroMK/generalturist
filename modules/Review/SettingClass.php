<?php

namespace Modules\Review;

use Modules\Core\Abstracts\BaseSettingsClass;

class SettingClass extends BaseSettingsClass
{
    public static function getSettingPages()
    {
        return [
            [
                'id' => 'review',
                'title' => __("Review Advanced Settings"),
                'position' => 140,
                'view' => "Review::admin.settings.review",
                "keys" => [
                    'review_upload_picture',
                ],
            ]
        ];
    }
}
