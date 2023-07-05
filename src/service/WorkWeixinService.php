<?php
// +----------------------------------------------------------------------
// | saithink [ saithink快速开发框架 ]
// +----------------------------------------------------------------------
// | Author: sai <1430792918@qq.com>
// +----------------------------------------------------------------------
namespace saithink\core\service;

/**
 * 企业微信服务类
 */
class WorkWeixinService
{
    protected static $instance;

    public static function application()
    {
        (self::$instance === null) && (self::$instance = self::options());
        return self::$instance;
    }

    /**
     * 获取token
     * access_token的有效期为7200秒（2小时），有效期内重复获取会返回相同结果并自动续期，过期后获取会返回新的access_token
     * @return mixed
     **/
    public static function options()
    {
        // $ding = SystemConfigService::more(['corp_id', 'agent_id', 'app_key', 'app_secret']);
        $config = [
            'corpid' => 'wwa47ad57f785985b6',
            'agentid' => '1000003',
            'corpsecret' => 'tcY_S2oynQ2600tDHBvkDuCC8BXZwJY3K4sa7OolP8c',
            'redirect' => 'https://jifen.saithink.top/weixin/'
        ];
        $params = [
            'corpid' => isset($config['corpid']) ? trim($config['corpid']) : '',
            'corpsecret' => isset($config['corpsecret']) ? trim($config['corpsecret']) : '',
        ];
        $rt = self::httpRequest("https://qyapi.weixin.qq.com/cgi-bin/gettoken", $params, 'get');
        return [
            'corpid' => isset($config['corpid']) ? trim($config['corpid']) : '',
            'agentid' => isset($config['agentid']) ? trim($config['agentid']) : '',
            'access_token' => $rt['access_token']
        ];
    }

    /**
     * 构造网页授权
     */
    public function OAuth2($jump)
    {
        $app = [
            'corpid' => 'wwa47ad57f785985b6',
            'agentid' => '1000003',
            'corpsecret' => 'tcY_S2oynQ2600tDHBvkDuCC8BXZwJY3K4sa7OolP8c',
            'redirect' => empty($jump) ? 'https://jifen.saithink.top/weixin/' : $jump
        ];
        return 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$app['corpid'].'&redirect_uri='.$app['redirect'].'&response_type=code&scope=snsapi_privateinfo&state=STATE&agentid='.$app['agentid'].'#wechat_redirect';
    }

    /**
     * 获取访问用户身份
     */
    public function getUserInfo($code)
    {
        $app = self::application();
        $data = [
            'access_token' => $app['access_token'],
            'code' => $code
        ];
        $rt = $this->httpRequest("https://qyapi.weixin.qq.com/cgi-bin/auth/getuserinfo", $data, 'get');
        return $rt;
    }

    /**
     * 获取访问用户敏感信息
     */
    public function getUserDetail($user_ticket)
    {
        $app = self::application();
        $data = [
            'user_ticket' => $user_ticket
        ];
        $rt = $this->httpRequest("https://qyapi.weixin.qq.com/cgi-bin/auth/getuserdetail?access_token=".$app['access_token'], $data, 'json');
        return $rt;
    }

    /**
     * 读取成员
     */
    public function getMember($userid)
    {
        $app = self::application();
        $data = [
            'access_token' => $app['access_token'],
            'userid' => $userid
        ];
        $rt = $this->httpRequest("https://qyapi.weixin.qq.com/cgi-bin/user/get", $data, 'get');
        return $rt;
    }

    /**
     * 发送消息
     **/
    public function sendMsg($user, $msg)
    {
        $app = self::application();
        $data=[
            'touser' => $user,
            'msgtype' => 'text',
            'agentid' => $app['agentid'],
            'text' => [
                'content' => $msg
            ]
        ];
        $rt = $this->httpRequest("https://qyapi.weixin.qq.com/cgi-bin/message/send?access_token=".$app['access_token'], $data, 'json');
        return $rt;
    }

    /**
     * 发送文本卡片消息
     */
    public function sendCard($user, $msg)
    {
        $app = self::application();
        $data=[
            'touser' => $user,
            'msgtype' => 'textcard',
            'agentid' => $app['agentid'],
            'textcard' => [
                'title' => $msg['title'],
                'description' => $msg['description'],
                'url' => isset($msg['url']) ? $msg['url'] : $app['redirect'],
                'btntxt' => isset($msg['btn']) ? $msg['btn'] : '前往处理',
            ]
        ];
        $rt = $this->httpRequest("https://qyapi.weixin.qq.com/cgi-bin/message/send?access_token=".$app['access_token'], $data, 'json');
        return $rt;
    }

    /**
     * HTTP请求（支持HTTP/HTTPS，支持GET/POST）
     * 默认post
     * @return mixed
     **/
    public static function httpRequest($url, $data = null, $type=null)
    {
        $curl = curl_init();
        if ($type == 'json') {
            $data = json_encode($data, JSON_UNESCAPED_UNICODE);
            curl_setopt($curl, CURLOPT_HEADER, false);
        } elseif ($type=='get') {
            $url .= '?';
            foreach ($data as $k=>$v) {
                $url .= \sprintf("%s=%s&", $k, $v);
            }
            $data = null;
            $url = rtrim($url, "?");
        }
        // 当遇到location跳转时，直接抓取跳转的页面，防止出现301
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        if (!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $output = curl_exec($curl);
        curl_close($curl);
        $rt = json_decode($output, true);
        if (empty($rt)) {
            $rt = $output;
        }
        return $rt;
    }


}
