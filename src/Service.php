<?php

namespace saithink\core;

use think\facade\Route;
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
        Route::get("/core/captcha","\\saithink\\core\\app\\controller\\Login@captcha");
        Route::post("/core/login","\\saithink\\core\\app\\controller\\Login@login");

        Route::get("/core/system/menu","\\saithink\\core\\app\\controller\\System@menus");
        Route::get("/core/system/user","\\saithink\\core\\app\\controller\\System@userInfo");

        Route::resource("/core/menu", "\\saithink\\core\\app\\controller\\system\\SystemMenu");
        Route::resource("/core/admin", "\\saithink\\core\\app\\controller\\system\\SystemAdmin");
        Route::resource("/core/dept", "\\saithink\\core\\app\\controller\\system\\SystemDept");
        Route::resource("/core/role", "\\saithink\\core\\app\\controller\\system\\SystemRole");
        Route::resource("/core/dict", "\\saithink\\core\\app\\controller\\system\\SystemDict");
        Route::resource("/core/dicd", "\\saithink\\core\\app\\controller\\system\\SystemDicd");
    }
}