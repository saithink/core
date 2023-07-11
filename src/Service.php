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
        Route::group('core', function () {

            Route::get("captcha","\\saithink\\core\\app\\controller\\Login@captcha");
            Route::post("login","\\saithink\\core\\app\\controller\\Login@login");

            Route::get("system/menu","\\saithink\\core\\app\\controller\\System@menus");
            Route::get("system/user","\\saithink\\core\\app\\controller\\System@userInfo");
            Route::post("system/modifyUser","\\saithink\\core\\app\\controller\\System@modifyUser");
            Route::post("system/modifyPwd","\\saithink\\core\\app\\controller\\System@modifyPwd");

            Route::get("log","\\saithink\\core\\app\\controller\\system\\SystemLog@index");
            Route::resource("menu", "\\saithink\\core\\app\\controller\\system\\SystemMenu");
            Route::resource("admin", "\\saithink\\core\\app\\controller\\system\\SystemAdmin");
            Route::resource("dept", "\\saithink\\core\\app\\controller\\system\\SystemDept");
            Route::resource("role", "\\saithink\\core\\app\\controller\\system\\SystemRole");
            Route::resource("dict", "\\saithink\\core\\app\\controller\\system\\SystemDict");
            Route::resource("dicd", "\\saithink\\core\\app\\controller\\system\\SystemDicd");

        })->middleware([
            \saithink\core\middleware\SystemToken::class,
            \saithink\core\middleware\SystemLog::class,
        ]);

    }
}