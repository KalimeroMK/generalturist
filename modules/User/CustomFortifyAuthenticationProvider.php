<?php


namespace Modules\User;


use Illuminate\Support\ServiceProvider;
use Modules\User\Fortify\LoginRequest;

class CustomFortifyAuthenticationProvider extends ServiceProvider
{

    public function boot()
    {
    }

    public function register()
    {
        $this->app->bind(\Laravel\Fortify\Http\Requests\LoginRequest::class, LoginRequest::class);
    }

}
