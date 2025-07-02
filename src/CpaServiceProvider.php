<?php

namespace Artjoker\Cpa;

use Artjoker\Cpa\Conversion\ConversionService;
use Artjoker\Cpa\Interfaces\Lead\LeadParser;
use Artjoker\Cpa\Lead\LeadService;
use Artjoker\Cpa\Lead\Parser\Chain;
use Illuminate\Support\ServiceProvider;
use App\Repositories\CpaNetworkRepository;

class CpaServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/cpa.php', 'cpa');

        // Register the service the package provides.
        $this->app->singleton('cpa', function ($app) {
            return new Cpa;
        });
        $this->app->singleton('cpaLead', LeadService::class);
        $this->app->singleton('cpaConversion', ConversionService::class);
        $this->app->singleton(LeadParser::class, Chain::class);
        $this->app->singleton(CpaNetworkRepository::class, function ($app) {
            return new CpaNetworkRepository();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['cpa'];
    }
    
    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/cpa.php' => config_path('cpa.php'),
        ], 'cpa.config');
    }
}
