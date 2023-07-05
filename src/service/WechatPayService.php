<?php
// +----------------------------------------------------------------------
// | saithink [ saithink快速开发框架 ]
// +----------------------------------------------------------------------
// | Author: sai <1430792918@qq.com>
// +----------------------------------------------------------------------
namespace saithink\core\service;

use EasyWeChat\Factory;

/**
 * 微信支付服务类
 */
class WechatPayService
{
    protected static $instance;

    public static function application($cache = false)
    {
        (self::$instance === null || $cache === true) && (self::$instance = Factory::payment(self::options()));
        return self::$instance;
    }

    public static function options()
    {
        $config = [
            // 必要配置
            'app_id'             => 'wx876ca04a2e75a981',
            'mch_id'             => '1602483469',
            'key'                => '9b819ec15beb8a1cca2390ae4e28987a',   // API 密钥
            // 如需使用敏感接口（如退款、发送红包等）需要配置 API 证书路径(登录商户平台下载 API 证书)
            'cert_path'          => 'path/to/your/cert.pem', // XXX: 绝对路径！！！！
            'key_path'           => 'path/to/your/key',      // XXX: 绝对路径！！！！
            'notify_url'         => '默认的订单回调地址',     // 你也可以在下单时单独设置来想覆盖它
        ];
        return $config;
    }

    /**
     * Native支付
     */
    public static function payNative($order)
    {
        $payment = self::application(true);
        $result = $payment->order->unify([
            'trade_type' => 'NATIVE',
            'body' => $order['body'],
            'out_trade_no' => $order['out_trade_no'],
            'total_fee' => $order['total_fee'],
        ]);
        return $result;
    }

    /**
     * JSAPI支付
     */
    public static function payJSAPI($order, $openid)
    {
        $payment = self::application(true);
        $result = $payment->order->unify([
            'trade_type' => 'JSAPI',
            'openid' => $openid,
            'body' => $order['body'],
            'out_trade_no' => $order['out_trade_no'],
            'total_fee' => $order['total_fee'],
        ]);
        return $result;
    }

    /**
     * 微信支付成功回调接口
     */
    public static function handleNotify($callable)
    {
        $payment = self::application(true);
        $response = $payment->handlePaidNotify(function ($message) {
            // 执行回调函数
            $callable();
            return true;
        });
        $response->send();
    }

}
