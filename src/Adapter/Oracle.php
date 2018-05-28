<?php

namespace Nawie\Eloquent\Adapter;

use Yajra\Oci8\Connectors\OracleConnector;
use Yajra\Oci8\Oci8Connection;

use Nawie\Eloquent\Skeleton\AdapterFactory;
use Nawie\Eloquent\Exception;

class Oracle extends AdapterFactory
{

    /**
     * Additional implementation of Oracle driver for [Illuminate/Database]
     * by using yajra/laravel-oci8
     */

    /**
     * Extending Illuminate\Database\Capsule\Manager to provide oracle driver
     * @param  Illuminate\Database\Capsule\Manager $capsule laravel database facade
     * @return Yajra\Oci8\Oci8Connection                    oracle driver instance
     */
    protected function provider($capsule){
        $manager = $capsule->getDatabaseManager();

        $manager->extend('oracle', function($config)
        {
            $connector = new OracleConnector();
            $connection = $connector->connect($config);
            $db = new Oci8Connection($connection, $config["database"], $config["prefix"]);

            // set oracle session variables

            $sessionVars = [
                'NLS_TIME_FORMAT'         => 'HH24:MI:SS',
                'NLS_DATE_FORMAT'         => 'YYYY-MM-DD HH24:MI:SS',
                'NLS_TIMESTAMP_FORMAT'    => 'YYYY-MM-DD HH24:MI:SS',
                'NLS_TIMESTAMP_TZ_FORMAT' => 'YYYY-MM-DD HH24:MI:SS TZH:TZM',
                'NLS_NUMERIC_CHARACTERS'  => '.,',
            ];

            /*
            // Like Postgres, Oracle allows the concept of "schema"
            if (isset($config['schema']))
            {
                $sessionVars['CURRENT_SCHEMA'] = $config['schema'];
            }
            */

            $db->setSessionVars($sessionVars);

            return $db;
        });
    }

}
