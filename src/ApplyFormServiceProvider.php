<?php namespace Vis\ApplyForm;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class ApplyFormServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        require __DIR__ . '/../vendor/autoload.php';

        $this->setupRoutes($this->app->router);

        $this->loadViewsFrom(realpath(__DIR__ . '/resources/views'), 'apply_form');

        $this->publishes([
            __DIR__ . '/config' => config_path('apply_form/')
        ], 'apply_form_config');

        $this->publishes([
            __DIR__ . '/published/js/apply_form.js' => public_path('packages/vis/apply_form/apply_form.js'),
        ], 'public');

        $this->publishes([
            __DIR__ . '/published/js/apply_form_rules.js' => public_path('js/apply_form_rules.js'),
        ], 'apply_form_rules');

    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router $router
     *
     * @return void
     */
    public function setupRoutes(Router $router)
    {
        if (!$this->app->routesAreCached()) {
            require __DIR__ . '/Http/Routes/routes.php';
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
    }

    public function provides()
    {
    }
}



