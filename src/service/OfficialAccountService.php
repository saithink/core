<?php
// +----------------------------------------------------------------------
// | saithink [ saithink快速开发框架 ]
// +----------------------------------------------------------------------
// | Author: sai <1430792918@qq.com>
// +----------------------------------------------------------------------
namespace saithink\core\service;

use EasyWeChat\Factory;

/**
 * 微信公众号服务类
 */
class OfficialAccountService
{
    protected static $instance;

    public static function application($cache = false)
    {
        (self::$instance === null || $cache === true) && (self::$instance = Factory::officialAccount(self::options()));
        return self::$instance;
    }

    public static function options()
    {
        $wechat = SystemConfigService::more(['wechat_appid', 'wechat_appsecret', 'wechat_token', 'wechat_encodingaeskey', 'wechat_encode']);
        $config = [
            'app_id' => isset($wechat['wechat_appid']) ? trim($wechat['wechat_appid']) : '',
            'secret' => isset($wechat['wechat_appsecret']) ? trim($wechat['wechat_appsecret']) : '',
            'token' => isset($wechat['wechat_token']) ? trim($wechat['wechat_token']) : '',
            'guzzle' => [
                'timeout' => 10.0, // 超时时间（秒）
                'verify' => false
            ],
        ];
        if (isset($wechat['wechat_encode']) && (int)$wechat['wechat_encode'] > 0 && isset($wechat['wechat_encodingaeskey']) && !empty($wechat['wechat_encodingaeskey']))
            $config['aes_key'] = $wechat['wechat_encodingaeskey'];
        return $config;
    }

    public static function serve()
    {
        $wechat = self::application(true);
        $server = $wechat->server;
        self::hook($server);
        return $server->serve()->send();
    }

    /**
     * 监听行为
     * @param Guard $server
     */
    private static function hook($server)
    {
        $server->push(function ($message) {
            switch ($message['MsgType']) {
                case 'event':
                    switch (strtolower($message->Event)) {
                        case 'subscribe':
                            $response = '订阅事件';
                            break;
                        case 'unsubscribe':
                            $response = '取消订阅事件';
                            break;
                        case 'scan':
                            $response = '扫码事件';
                            break;
                        case 'location':
                            $response = '地理位置事件';
                            break;
                        case 'click':
                            $response = '自定义菜单事件';
                            break;
                        case 'view':
                            $response = '跳转URL事件';
                            break;
                    }
                    break;
                case 'text':
                    $response = '收到文字消息';
                    break;
                case 'image':
                    $response = '收到图片消息';
                    break;
                case 'voice':
                    $response = '收到语音消息';
                    break;
                case 'video':
                    $response = '收到视频消息';
                    break;
                case 'location':
                    $response = '收到坐标消息';
                    break;
                case 'link':
                    $response = '收到链接消息';
                    break;
                case 'file':
                    $response = '收到文件消息';
                // ... 其它消息
                default:
                    $response = '收到其它消息';
                    break;
            }

            // ...
            return $response ?? false;
        });
    }

    /**
     * 微信公众号菜单接口
     * @return \EasyWeChat\Menu\Menu
     */
    public static function menuService()
    {
        return self::application()->menu;
    }

    /**
     * 上传永久素材接口
     * @return \EasyWeChat\Material\Material
     */
    public static function materialService()
    {
        return self::application()->material;
    }

    /**
     * 获取用户信息
     */
    public static function getUserInfo($openId)
    {
        $app = self::application();
        $user = $app->user->get($openId);
        return $user;
    }

    /**
     * 读取所有模板
     */
    public static function templates()
    {
        $wechat = self::application();
        $response = $wechat->template_message->getPrivateTemplates();
        return $response;
    }

    /**
     * 发送模板消息
     */
    public static function sendTemplateMsg($openid, $templateId, $url, $data)
    {
        $app = self::application();
        $app->template_message->send([
            'touser' => $openid,
            'template_id' => $templateId,
            'url' => $url,
            'data' => $data,
        ]);
    }

    /**
     * 发送订阅消息
     */
    public static function sendSubscriptMsg($openid, $templateId, $url, $data)
    {
        $app = self::application();
        $app->template_message->sendSubscription([
            'touser' => $openid,
            'template_id' => $templateId,
            'url' => $url,
            'scene' => 1000,
            'data' => $data,
        ]);
    }


}
