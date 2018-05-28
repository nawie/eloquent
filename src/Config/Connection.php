<?php

namespace Nawie\Eloquent\Config{

    function Oracle(){
        return array(
                'driver'        => 'oracle',
                'tns'           => env('DB_TNS', ''),
                'host'          => env('DB_HOST', ''),
                'port'          => env('DB_PORT', '1521'),
                'database'      => env('DB_DATABASE', ''),
                'username'      => env('DB_USERNAME', ''),
                'password'      => env('DB_PASSWORD', ''),
                'charset'       => env('DB_CHARSET', 'AL32UTF8'),
                'prefix'        => env('DB_PREFIX', ''),
                'prefix_schema' => env('DB_SCHEMA_PREFIX', ''),
            );
    };

    function Sqlite(){
        return array(
                'driver'   => 'sqlite',
                'database' => __DIR__ . DIRECTORY_SEPARATOR .'../Test/dummy.sqlite3',
                'prefix'   => ''
            );
    };
}

