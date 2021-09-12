<?php

    namespace Modules\Api;

    use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
    use Illuminate\Support\Facades\Route;

    class RouterServiceProvider extends ServiceProvider
    {
        /**
         * The module namespace to assume when generating URLs to actions.
         *
         * @var string
         */
        protected string $moduleNamespace = 'Modules\Api\Controllers';


        /**
         * Called before routes are registered.
         *
         * Register any model bindings or pattern based filters.
         *
         * @return void
         */
        public function boot()
        {
            parent::boot();
        }

        /**
         * Define the routes for the application.
         *
         * @return void
         */
        public function map()
        {
            $this->mapApiRoutes();
        }

        /**
         * Define the "api" routes for the application.
         *
         * These routes are typically stateless.
         *
         * @return void
         */
        protected function mapApiRoutes()
        {
            Route::prefix('api')
                ->middleware(['api', 'set_language_for_api'])
                ->namespace($this->moduleNamespace)
                ->group(__DIR__.'/Routes/api.php');
        }

        /**
         * Define the "web" routes for the application.
         *
         * These routes all receive session state, CSRF protection, etc.
         *
         * @return void
         */
        protected function mapWebRoutes()
        {
            Route::middleware('web')
                ->namespace($this->moduleNamespace)
                ->group(__DIR__.'/Routes/web.php');
        }

        /**
         * Define the "lang" routes for the application.
         *
         * These routes all receive session state, CSRF protection, etc.
         *
         * @return void
         */
        protected function mapLanguageRoutes()
        {
            Route::middleware('web')
                ->namespace($this->moduleNamespace)
                ->prefix(app()->getLocale())
                ->group(__DIR__.'/Routes/language.php');
        }
    }
