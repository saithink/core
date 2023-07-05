<?php
// +----------------------------------------------------------------------
// | saithink [ saithink快速开发框架 ]
// +----------------------------------------------------------------------
// | Author: sai <1430792918@qq.com>
// +----------------------------------------------------------------------
namespace saithink\core\service;

use EasyWeChat\Factory;

/**
 * 微信小程序服务类
 */
class AppletService
{
    protected static $instance;

    public static function application($cache = false)
    {
        (self::$instance === null || $cache === true) && (self::$instance = Factory::miniProgram(self::options()));
        return self::$instance;
    }

    public static function options()
    {
        $mini = SystemConfigService::more(['mini_name', 'mini_appid', 'mini_appsecret', 'mini_logo']);
        $config = [
            // 必要配置
            'app_id' => isset($mini['mini_appid']) ? trim($mini['mini_appid']) : '',
            'secret' => isset($mini['mini_appsecret']) ? trim($mini['mini_appsecret']) : '',

            // 下面为可选项
            // 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
            'response_type' => 'array',
        ];
        return $config;
    }

    /**
     * 登录
     */
    public static function login($code)
    {
        $app = self::application();
        $result = $app->auth->session($code);
        if (isset($result['errcode'])) {
            return app('json')->fail($result['errmsg']);
        }
        return app('json')->success($result);
    }

    /**
     * 解密
     */
    public static function decryptData($session, $iv, $encryptedData)
    {
        $app = self::application();
        $result = $app->encryptor->decryptData($session, $iv, $encryptedData);
        if (isset($result['errcode'])) {
            return app('json')->fail($result['errmsg']);
        }
        return app('json')->success($result);
    }

}
