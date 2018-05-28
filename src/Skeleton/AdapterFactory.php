<?php

namespace Nawie\Eloquent\Skeleton;

use Illuminate\Database\Capsule\Manager;

use Nawie\Eloquent\Skeleton\AdapterInterface;

abstract class AdapterFactory implements AdapterInterface{
    public  $name = 'default';
    private $config = null;

    public function __construct(array $config = []){
        $this->config = $config;
    }

    /**
     * Set up Illuminate\Database per database configuration, with support for oracle drivers
     * @param  Illuminate\Database $capsule Illuminate\Database facade
     * @return void
     */
    public function boot(Manager $capsule){
        if(method_exists($this, 'provider')){ // extending driver, ex: oracle
            $this->provider($capsule);
        }
        $capsule->addConnection($this->config, $this->name);
    }
}