<?php namespace Vis\ApplyForms;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class ApplyFormsServiceProvider extends ServiceProvider
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

        $this->loadViewsFrom(realpath(__DIR__ . '/resources/views'), 'apply_forms');

        $this->publishes([
            __DIR__ . '/config' => config_path('apply-form/')
        ], 'apply-form-config');

        $this->publishes([
            __DIR__ . '/published/js/apply_forms.js' => public_path('packages/vis/apply_forms/apply_forms.js'),
        ], 'public');

        $this->publishes([
            __DIR__ . '/published/js/apply_forms_rules.js' => public_path('js/apply_forms_rules.js'),
        ], 'apply-form-rules');

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



