<?php

namespace Modules\Theme;

use Modules\ModuleServiceProvider;

class ModuleProvider extends ModuleServiceProvider
{


    public static function getAdminMenu()
    {
        return [
            'theme' => [
                'title' => __("Themes"),
                'url' => route("theme.admin.index"),
                "permission" => "theme_manage",
                "position" => 70,
                'icon' => "fa fa-paint-brush",
                "group" => "system",
            ]
        ];
    }

    public function register()
    {
        $this->app->register(RouterServiceProvider::class);
    }
}
