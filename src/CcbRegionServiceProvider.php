<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/2/22
 * Time: 11:51
 */

namespace Ccb\Region;


use Illuminate\Support\ServiceProvider;
use Ccb\Region\Commands\RegionGenerate;

class CcbRegionServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                RegionGenerate::class
            ]);
        }
        $this->loadMigrationsFrom(__DIR__.'/../migrations');
    }
}
