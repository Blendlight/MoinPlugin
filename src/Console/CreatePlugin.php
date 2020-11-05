<?php

namespace MoinPlugin\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Exception\InvalidOptionException;

class CreatePlugin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:plugin
                            {name? : The name of the plugin}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create New Plugin';

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
        $plugin_name = $this->argument('name');
        if(!$plugin_name)
        {
            throw new InvalidOptionException(\sprintf('Argument "name" not specified'));

        }
        //check if plugin already exists
        $path = base_path('plugins/'.$plugin_name);
        if(file_exists($path))
        {
            $this->warn("Plugin folder already exists:");
            throw new InvalidOptionException(\sprintf('Plugin "%s" folder already exists. delete if empty', $plugin_name));
        }

        if(!file_exists(base_path('plugins')))
        {
            mkdir(base_path('plugins'));
        }
        mkdir(base_path('plugins/'.$plugin_name));
        mkdir(base_path('plugins/'.$plugin_name.'/routes'));

        file_put_contents($path.'/'.$plugin_name.'.info', "Plugin Name: ${plugin_name}
Description: Plugin Description
Version: 1.0
Author: AuthorName
;Empty line at last");

        file_put_contents($path.'/routes/web.php', "<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
Route::get('/plugin/page/$plugin_name', function(){
    return 'Plugin ${plugin_name} plugin page.';
});
 ");

        file_put_contents($path.'/routes/api.php', "<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
//Route::get('/plugin/page/$plugin_name/api', function(){
//    return 'Plugin ${plugin_name} plugin page.';
//});
 ");
        $this->info("Plugin $plugin_name created Successfully");
        return 1;
    }


}
