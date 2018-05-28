<?php

namespace Nawie\Eloquent\Config;

$files = glob(__DIR__ . DIRECTORY_SEPARATOR . '*.php');

if ($files === false) {
    throw new RuntimeException("Failed to glob for function files");
}

$isAutoload = function($res, $file) {
    if ($file !== __FILE__){
        array_push($res, $file);
    }
    return $res;
};

foreach (array_reduce($files, $isAutoload, array()) as $file) {
    require_once $file;
}

unset($file);
unset($files);
