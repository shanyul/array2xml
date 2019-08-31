<?php
/**
 * Created by PhpStorm.
 * User: Shanyuliang
 * Date: 2019/8/31
 * Time: 14:47
 */

namespace Shanyuliang\Array2xml;


class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    protected $defer = true;

    public function register()
    {
        $this->app->singleton(Array2xml::class, function(){
            return new Array2xml();
        });

        $this->app->alias(Array2xml::class, 'array2xml');
    }

    public function provides()
    {
        return [Array2xml::class, 'array2xml'];
    }
}