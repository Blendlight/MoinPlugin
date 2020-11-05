<?php

namespace MoinPlugin;
use Illuminate\Support\Facades\URL;
use MoinPlugin\Models\Plugin;
use Illuminate\Support\Facades\Route;


class Controller{
    private function parseInfo($content)
    {
        preg_match_all("/\s*(.+): \s*(.+?)(\n)/", $content, $matches);
        $keyValues = array_combine($matches[1], $matches[2]);
        foreach ($keyValues as $key=>&$value)
        {
            $value = trim($value);
            //is array
            if($value[0] == "[" && $value[strlen($value)-1] == "]")
            {
                $value = json_decode($value);
            }
        }
        return $keyValues;
    }

    public function index()
    {

        $plugins = [];

        $dir_path = public_path() . '/../plugins';

        $dir = new \DirectoryIterator($dir_path);
        foreach ($dir as $fileinfo) {
            if (!$fileinfo->isDot()) {
                if($fileinfo->isDir())
                {
                    //plugin info
                    $plugin = [];

                    $plugin_name = $fileinfo->getFilename();
                    $plugin_path = $fileinfo->getPathname();
                    $infoFile = $plugin_path.'\\'.$plugin_name.'.info';
                    $pluginFile = $plugin_path.'\\'.$plugin_name.'.php';
                    if(file_exists($infoFile))
                    {
                        $screenshot = $plugin_path.'\\screenshot.png';
                        $screenshot_target= public_path()."/plugins_screenshots/${plugin_name}_screenshot.png";
                        if(file_exists($screenshot) && !file_exists($screenshot_target))
                        {
                            $success = copy($screenshot, $screenshot_target);
                            $plugin['screenshot'] = URL::asset("/plugins_screenshots/${plugin_name}_screenshot.png");
                        }
                        elseif(file_exists($screenshot_target))
                        {
                            $plugin['screenshot'] = URL::asset("/plugins_screenshots/${plugin_name}_screenshot.png");
                        }else{
//                        default image;
                            $plugin['screenshot'] = false;
                        }

                        $content = file_get_contents(($infoFile));
                        $keyValues = $this->parseInfo($content);

                        $plugin['machine_name'] = $plugin_name;
                        $plugin['name'] = $keyValues['Plugin Name'];
                        $plugin['description'] = $keyValues['Description'];
                        $plugin['author'] = $keyValues['Author'];
                        $plugin['version'] = $keyValues['Version'];
                        $plugin['path'] = $plugin_path;
                        $plugin['enabled'] = false;
                        $plugins[] = $plugin;
                    }
                }
            }
            else {

            }
        }

        //save all to database
        $this->storeToDB($plugins);

        return \Inertia\Inertia::render('Admin/Plugins', ['plugins'=>$plugins]);
    }

    public function storeToDB(&$plugins)
    {
        $_plugs = Plugin::all();
        $plugs = [];
        foreach($_plugs as $p)
        {
            $plugs[$p->name] = $p->enabled;
        }
        $plugs_names = array_keys($plugs);
        foreach($plugins as $key=>$plugin)
        {
            $machine_name = $plugin['machine_name'];
            if(in_array($machine_name, $plugs_names))
            {
                $plugins[$key]['enabled'] = $plugs[$machine_name];
            }else{
                //add to database
                $PluginModel = new Plugin(['name'=>$machine_name, 'enabled'=>false]);
                $PluginModel->save();
            }
        }
    }

    public static function handlePluginRoutes()
    {

        Route::get("/admin/modules", [self::class, 'index']);

        $plugins = Plugin::all()->where('enabled', '=', true);

        foreach($plugins as $plugin)
        {
            $file_path = public_path() . '/../plugins/'.$plugin->name.'/routes/web.php';
//            echo $file_path;
            if(file_exists($file_path)) {
                require $file_path;

            }else{
            }
        }
    }
}
