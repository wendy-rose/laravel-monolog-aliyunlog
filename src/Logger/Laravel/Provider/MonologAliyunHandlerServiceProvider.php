<?php
/**
 * Created by PhpStorm.
 * User: Wendy
 * Date: 2019/12/19
 * Time: 16:14
 */

namespace Logger\Laravel\Provider;


use Illuminate\Support\ServiceProvider;

class MonologAliyunHandlerServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->mergeConfigFrom(
            __DIR__. '/../config/aliyunlog.php', 'aliyunlog'
        );
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array();
    }
}