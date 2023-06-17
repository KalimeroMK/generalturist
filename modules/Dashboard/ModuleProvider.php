<?php

namespace Modules\Dashboard;

use Modules\ModuleServiceProvider;

class ModuleProvider extends ModuleServiceProvider
{
    public static function getAdminMenu()
    {
        return [
        ];
    }

    public function boot()
    {
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/Config/config.php', 'admin'
        );
        $this->app->register(RouterServiceProvider::class);
    }

}
