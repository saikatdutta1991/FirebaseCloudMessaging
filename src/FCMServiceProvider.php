<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class FCMServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('PushManager', function($app)
        {
            return new \Saikat\FirebaseCloudMessaging\PushManager;
        });

    }


    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [\Saikat\FirebaseCloudMessaging\PushManager::class];
    }

}
