<?php

namespace Qisti\UploadMultipleUi;

use Illuminate\Support\ServiceProvider;

class UploadMultipleUiServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/uploadmultipleui.php', 'uploadmultipleui');
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'uploadmultipleui');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/uploadmultipleui.php' => config_path('uploadmultipleui.php'),
            ], 'uploadmultipleui-config');

            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/vendor/uploadmultipleui'),
            ], 'uploadmultipleui-views');
        }
    }
}
