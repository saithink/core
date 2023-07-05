<?php

namespace saithink\core;

use think\Route;
use think\Service as TpService;
use saithink\core\utils\Json;
use saithink\core\utils\Request;

class Service extends TpService
{
    public $bind = [
        'json' => Json::class,
        'request' => Request::class,
    ];

    public function boot()
    {
        // 服务启动
    }
}