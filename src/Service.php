<?php

namespace saithink\core;

use think\Route;
use think\Service as TpService;
use saithink\core\utils\Json;

class Service extends TpService
{
    public $bind = [
        'json' => Json::class,
    ];

    public function boot()
    {
        // 服务启动
    }
}