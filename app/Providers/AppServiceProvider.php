<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Database\Eloquent\Model;
use App\Services\AuditLogger;
use App\Observers\AuditObserver;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        View::share('appThemePanel', false);

        $modelsDir = app_path('Models');
        if (is_dir($modelsDir)) {
            foreach (glob($modelsDir . DIRECTORY_SEPARATOR . '*.php') as $file) {
                $class = 'App\\Models\\' . basename($file, '.php');
                if ($class === 'App\\Models\\AuditLog') {
                    continue;
                }
                if (class_exists($class) && is_subclass_of($class, Model::class)) {
                    $class::observe(AuditObserver::class);
                }
            }
        }
    }

    public function register()
    {
        //
    }
}
