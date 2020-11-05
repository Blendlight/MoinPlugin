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
    protected $signature = 'plugSys
    {path? : The files Path of templates for plugin system}';
    
    
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
        
        $path = realpath($this->argument('path'));
        
        //check for required files
    }
    
    
}
