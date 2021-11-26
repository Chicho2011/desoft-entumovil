<?php

namespace Desoft;

use Desoft\Classes\ConexionEnTuMovil;
use Desoft\Services\ConexionServices;
use Desoft\Services\EnTuMovilServices;
use Illuminate\Support\ServiceProvider;

class EnTuMovilServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ConexionEnTuMovil::class, function($app)
        {
            $conexionServices = new ConexionServices();
            $enTuMovilServices = new EnTuMovilServices(
                        config('enTuMovil.keyword'),
                        config('enTuMovil.hasKeyword'),
                        config('enTuMovil.user'),
                        config('enTuMovil.pass'),
                        config('enTuMovil.smscId'),
            );

            return new ConexionEnTuMovil($enTuMovilServices, $conexionServices);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/enTuMovil.php' => config_path('enTuMovil.php'),
        ]);
    }
}
