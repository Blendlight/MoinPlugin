<?php

namespace MoinPlugin;

use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function register()
    {
        // $this->app->singleton(ResponseFactory::class);
    }

    public function boot()
    {
        // $this->registerBladeDirective();
        $this->registerConsoleCommands();
        // $this->registerRequestMacro();
        // $this->registerRouterMacro();
    }

    protected function registerConsoleCommands()
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->commands([
            Console\SetupPluginSystemCommand::class,
        ]);
    }

    protected function registerRequestMacro()
    {
        // Request::macro('inertia', function () {
        //     return boolval($this->header('X-Inertia'));
        // });
    }

    protected function registerRouterMacro()
    {
        // Router::macro('inertia', function ($uri, $component, $props = []) {
        //     return $this->match(['GET', 'HEAD'], $uri, '\Inertia\Controller')
        //         ->defaults('component', $component)
        //         ->defaults('props', $props);
        // });
    }
}