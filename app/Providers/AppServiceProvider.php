<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Request $request)
    {

        if(env('APP_HTTPS')) {
            \URL::forceScheme('https');
            $this->app['request']->server->set('HTTPS','on');
        }
        Schema::defaultStringLength(191);

        if(!app()->runningInConsole()) {
            // create symlink storage
            if (!is_link(public_path('storage'))) {
                if (!windows_os()) {
                    symlink(storage_path(), public_path('storage'));
                } else {
                    Artisan::call("storage:link", []);
                }
            }
        }

        app()->setLocale('en');

        if (strpos($request->path(), 'install') === false && file_exists(storage_path() . '/installed')) {
            $this->setLang();
        }
    }

    protected function setLang(){
        $request = \request();
        $locale = $request->segment(1);
        $languages = \Modules\Language\Models\Language::getActive();
        $localeCodes = Arr::pluck($languages,'locale');

        if(in_array($locale,$localeCodes)){
            app()->setLocale($locale);
        }else{
            app()->setLocale(setting_item('site_locale'));
        }

        if(!empty($locale) and $locale == setting_item('site_locale'))
        {
            $segments = $request->segments();
            if(!empty($segments) and count($segments) > 1) {
                array_shift($segments);
                return redirect()->to(implode('/', $segments))->send();
            }
        }
    }
}
