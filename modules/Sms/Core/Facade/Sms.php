<?php

    namespace Modules\Sms\Core\Facade;

    use Illuminate\Support\Facades\Facade;
    use RuntimeException;

    class Sms extends Facade
    {
        /**
         * Get the registered name of the component.
         *
         * @return string
         *
         * @throws RuntimeException
         */
        protected static function getFacadeAccessor()
        {
            return 'sms';
        }
    }