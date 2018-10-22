<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ImageRecognition extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'App\ImageRecognitionInterface',
            'App\Services\ImageRecognition\ClarifaiApiService'
        );
    }
}