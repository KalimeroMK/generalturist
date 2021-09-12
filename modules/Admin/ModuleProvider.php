<?php

    namespace Modules\Admin;

    use Exception;
    use Modules\ModuleServiceProvider;

    class ModuleProvider extends ModuleServiceProvider
    {
        /**
         * @throws Exception
         */
        public function boot()
        {
            Crud::register([
                'test' => TestCrud::class,
            ]);
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
