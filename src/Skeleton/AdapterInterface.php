<?php

namespace Nawie\Eloquent\Skeleton;

use Illuminate\Database\Capsule\Manager;

interface AdapterInterface {

    function boot(Manager $capsule);

}