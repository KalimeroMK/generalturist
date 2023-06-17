<?php
namespace Modules\Core;
use Illuminate\Support\Facades\Event;
use Modules\Core\Events\CreatedServicesEvent;
use Modules\Core\Events\CreateReviewEvent;
use Modules\Core\Events\UpdatedServiceEvent;
use Modules\Core\Helpers\HookManager;
use Modules\Core\Helpers\SitemapHelper;
use Modules\Core\Listeners\CreatedServicesListen;
use Modules\Core\Listeners\CreateReviewListen;
use Modules\Core\Listeners\UpdatedServicesListen;
use Modules\ModuleServiceProvider;

class ModuleProvider extends ModuleServiceProvider
{

    public function boot(){

        $this->loadMigrationsFrom(__DIR__ . '/Migrations');
        Event::listen(CreatedServicesEvent::class,CreatedServicesListen::class);
        Event::listen(UpdatedServiceEvent::class,UpdatedServicesListen::class);
        Event::listen(CreateReviewEvent::class,CreateReviewListen::class);


    }
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouterServiceProvider::class);
        $this->app->register(BladeServiceProvider::class);

        $this->app->singleton(SitemapHelper::class,function($app){
            return new SitemapHelper();
        });
        $this->app->singleton('hook_manager',function(){
            return $this->app->make(HookManager::class);
        });
    }


    public static function getAdminSubmenu()
    {
        return [
            /*[
                'id'=>'updater',
                'parent'=>'tools',
                'title'=>__("Updater"),
                'url'=>'admin/module/core/updater',
                'icon'=>'icon ion-md-download',
                'permission'=>'system_log_view'
            ],*/
            [
                'id'=>'plugin',
                'parent'=>'tools',
                'title'=>__("Plugins"),
                'url'=>route('core.admin.plugins.index'),
                'icon'=>'icon ion-md-color-wand',
                'permission'=>'plugin_manage'
            ]
        ];
    }
}
