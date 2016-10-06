<?php

namespace Nollaversio\SQSJobless;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Queue;
use Illuminate\Queue\Events\JobProcessed;

class JoblessSQSServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/sqs-jobless.php' => config_path('sqs-jobless.php')
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

         $this->app->booted(function () {

            $this->app['queue']->extend('sqs-jobless', function () {
               
                return new JoblessConnector();
            });
        });
    }
}
