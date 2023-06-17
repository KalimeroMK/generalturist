<?php

namespace Modules\Contact;

use Modules\ModuleServiceProvider;

class ModuleProvider extends ModuleServiceProvider
{
    public static function getTemplateBlocks()
    {
        return [
            'contact_block' => "\\Modules\\Contact\\Blocks\\Contact",
        ];
    }

    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/Migrations');
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouterServiceProvider::class);
    }
}
