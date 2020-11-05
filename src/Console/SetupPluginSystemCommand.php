<?php

namespace MoinPlugin\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Exception\InvalidOptionException;

class SetupPluginSystemCommand extends Command
{
    /**
    * The name and signature of the console command.
    *
    * @var string
    */
    protected $signature = 'plugSys';


    /**
    * The console command description.
    *
    * @var string
    */
    protected $description = 'Setup Plugin system in your laravel(by Moin)';

    /**
    * Create a new command instance.
    *
    * @return void
    */
    public function __construct()
    {
        parent::__construct();
    }

    /**
    * Execute the console command.
    *
    * @return int
    */
    public function handle()
    {
        $appPath = app_path();
        $basePath = base_path();
        $routesPath = base_path('routes');
        $webRoutes = $routesPath.'\\web.php';
        $status = $this->addCallerFnToWeb($webRoutes);
        if(!$status)
        {
            return;
        }
        //add Plugin folder to app composer.json
        $composer = base_path('composer.json');
        $content = file_get_contents($composer);
        $composerJson = json_decode($content, true);

        if(!isset($composerJson['autoload']['psr-4']['MoinPluginPlugins\\']))
        {
            echo 'Puting Plugin path in composer.json';
            $composerJson['autoload']['psr-4']['MoinPlugins\\'] = "plugins";
            file_put_contents($composer, json_encode($composerJson, JSON_PRETTY_PRINT));
        }

    }

    public function addCallerFnToWeb($webRoutes)
    {
        if(!file_exists($webRoutes))
        {
            $this->warn("File does not exists ". $webRoutes);
            return false;
        }
        $content = file_get_contents($webRoutes);
//        echo $content;
        if(false === strpos($content, "MoinPlugin\Controller::handlePluginRoutes();"))
        {
            //add line at end
            file_put_contents($webRoutes, $content."\n//Call Plugin manger handle\n\\MoinPlugin\\Controller::handlePluginRoutes();");
        }
        return true;
    }


}
