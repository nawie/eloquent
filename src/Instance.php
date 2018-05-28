<?php

namespace Nawie\Eloquent;

require_once __DIR__ . './../vendor/autoload.php';

use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as Capsule;

use Nawie\Eloquent\Adapter;

class Instance
{
    private static $init = false;
    private static $capsule = null;
    private static $databaseInstance = [];

    /**
     * Set up database connection
     *
     * @param   array $databaseConfig Illuminate\Database standart database configuration
     * @param   string $databaseAlias provide name for connection instance
     * @return  void
     */
    public static function addConnection(array $databaseConfig, $databaseAlias = 'default'){
        $driver = 'Nawie\\Eloquent\\Adapter\\' . ucfirst($databaseConfig['driver']);
        if(class_exists($driver)){
            $databaseInstance = new $driver($databaseConfig);
            $databaseInstance->name = $databaseAlias;
            array_push(self::$databaseInstance, $databaseInstance);
            return __CLASS__;
        }
        throw new Exception\InvalidDatabaseDriverException("Error Invalid database driver:" . $databaseConfig['driver'], 1);
    }

    /**
     * Bootstrapping Illuminate\Database and provide support for eloquent model + global
     * @return void
     */
    public static function bootstrap(){
        if(!self::$init && !empty(self::$databaseInstance)){
            self::$capsule = new Capsule;

            // registering each connection
            array_map(function($driver) use(&$capsule){
                $driver->boot(self::$capsule);
            }, self::$databaseInstance);

            // bootstrapping Illuminate\Database & Make this Capsule instance available globally via static methods... (optional)
            self::$capsule->setEventDispatcher(new Dispatcher(new Container));
            self::$capsule->bootEloquent();
            self::$capsule->setAsGlobal();
            self::$init = true;
        }
        return __CLASS__;
    }

    /**
     * Methods to access specific connection instance or default one
     *
     * @param  string $connectionName database connection name
     * @return \Illuminate\Database\Connection
     */
    public static function getConnection($connectionName = 'default'){
        return self::$capsule ? self::$capsule::connection($connectionName) : null;
    }

    /**
     * Different methods to access specific connection instance
     *
     * @param  string $connectionName database connection name
     * @return \Illuminate\Database\Connection
     */
    public static function connection($connectionName = 'default')
    {
        return self::getConnection($connectionName);
    }
}