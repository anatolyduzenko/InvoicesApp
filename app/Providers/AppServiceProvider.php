<?php

namespace App\Providers;

use App\Services\Templates\AppTemplateProvider;
use App\Services\Templates\TemplateManager;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('template.providers', function ($app) {
            $providers = [
                $app->make(AppTemplateProvider::class),
            ];

            if (class_exists(\AnatolyDuzenko\AdvancedInvoiceTemplates\Services\PackageTemplateProvider::class)) {
                $providers[] = $app->make(\AnatolyDuzenko\AdvancedInvoiceTemplates\Services\PackageTemplateProvider::class);
            }

            return $providers;
        });

        $this->app->singleton(TemplateManager::class, function ($app) {
            return new TemplateManager($app->make('template.providers'));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
