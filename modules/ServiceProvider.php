<?php

namespace Modules;

use Illuminate\Support\Facades\File;
use Modules\Theme\ModuleProvider;
use Modules\Theme\ThemeManager;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public static function getModules()
    {
        return array_keys(static::getActivatedModules());
    }

    public static function getActivatedModules()
    {
        $res = [];

        $class = ThemeManager::currentProvider();
        if (class_exists($class)) {
            $modules = $class::getModules();
            $coreModules = static::getCoreModules();
            foreach ($modules as $module => $class) {
                if (class_exists($class)) {
                    $res[$module] = [
                        'id' => $module,
                        'class' => $class,
                        'parent' => $coreModules[$module]['class'] ?? ''
                    ];
                }
            }
        }

        return $res;
    }

    public static function getCoreModules()
    {
        $res = [];

        $class = ThemeManager::currentProvider();
        if (class_exists($class)) {
            foreach ($class::getCoreModules() as $module => $class) {
                if (class_exists($class)) {
                    $res[$module] = [
                        'id' => $module,
                        'class' => $class
                    ];
                }
            }
        }

        return $res;
    }

    public static function getThemeModules()
    {
        $res = [];

        $class = ThemeManager::currentProvider();
        if (class_exists($class)) {
            foreach ($class::getThemeModules() as $module => $class) {
                if (class_exists($class)) {
                    $res[$module] = [
                        'id' => $module,
                        'class' => $class
                    ];
                }
            }
        }

        return $res;
    }

    public function boot()
    {
        $listModule = array_map('basename', File::directories(__DIR__));
        foreach ($listModule as $module) {
            if (is_dir(__DIR__.'/'.$module.'/Views')) {
                $this->loadViewsFrom(__DIR__.'/'.$module.'/Views', $module);
            }
        }
        if (is_dir(__DIR__.'/Layout')) {
            $this->loadViewsFrom(__DIR__.'/Layout', 'Layout');
        }
    }

    public function register()
    {
        $this->app->register(ModuleProvider::class);
    }

}
