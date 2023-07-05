<?php
// +----------------------------------------------------------------------
// | saithink [ saithink快速开发框架 ]
// +----------------------------------------------------------------------
// | Author: sai <1430792918@qq.com>
// +----------------------------------------------------------------------
namespace saithink\core\service;

/**
 * 钉钉服务类
 */
class DingTalkService
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
        $ding = SystemConfigService::more(['corp_id', 'agent_id', 'app_key', 'app_secret']);
        $config = [
            'appkey' => isset($ding['app_key']) ? trim($ding['app_key']) : '',
            'appsecret' => isset($ding['app_secret']) ? trim($ding['app_secret']) : '',
        ];
        $rt = self::httpRequest("https://oapi.dingtalk.com/gettoken", $config, 'get');
        return [
            'corp_id' => isset($ding['corp_id']) ? trim($ding['corp_id']) : '',
            'agent_id' => isset($ding['agent_id']) ? trim($ding['agent_id']) : '',
            'token' => $rt['access_token']
        ];
    }

    /**
     * 通过免登码获取用户信息
     **/
    public function getUserInfo($code)
    {
        $app = self::application();
        $data = [
            'access_token' => $app['token'],
            'code' => $code
        ];
        $rt = $this->httpRequest("https://oapi.dingtalk.com/topapi/v2/user/getuserinfo", $data);
        return $rt;
    }

    /**
     * 获取用户详情
     **/
    public function getUserDetail($userid)
    {
        $app = self::application();
        $data = [
            'access_token' => $app['token'],
            'userid' => $userid
        ];
        $rt = $this->httpRequest("https://oapi.dingtalk.com/topapi/v2/user/get", $data);
        return $rt;
    }

    /**
     * 发送详情
     **/
    public function sendMsg($user, $msg)
    {
        $app = self::application();
        $data=[
            'access_token' => $app['token'],
            'agent_id'=> $app['agent_id'],
            'userid_list'=> $user,
            'msg'=>json_encode(['msgtype'=>'text', 'text'=>['content'=>$msg]])
        ];
        $rt = $this->httpRequest("https://oapi.dingtalk.com/topapi/message/corpconversation/asyncsend_v2", $data);
        return $rt;
    }

    /**
     * 发送图片详情
     **/
    public function sendImageMsg($user, $msg)
    {
        $app = self::application();
        $data=[
            'access_token' => $app['token'],
            'agent_id'=> $app['agent_id'],
            'userid_list'=> $user,
            'msg'=>json_encode(['msgtype'=>'link', 'link'=>[
                'messageUrl'=> 'https://yaxmdt.yuanan.gov.cn/dingding/',
                'picUrl'=> '@lALOACZwe2Rk',
                'title'=> '消息通知',
                'text'=> $msg,
            ]])
        ];
        $rt = $this->httpRequest("https://oapi.dingtalk.com/topapi/message/corpconversation/asyncsend_v2", $data);
        return $rt;
    }

    /**
     * 发送卡片详情
     **/
    public function sendCardMsg($user, $msg)
    {
        $app = self::application();
        $data=[
            'access_token' => $app['token'],
            'agent_id'=> $app['agent_id'],
            'userid_list'=> $user,
            'msg'=>json_encode(['msgtype'=>'action_card', 'action_card'=>[
                'single_url'=> 'https://yaxmdt.yuanan.gov.cn/dingding/',
                'single_title'=> '查看详情',
                'title'=> '待办工作通知',
                'markdown'=> $msg,
            ]])
        ];
        $rt = $this->httpRequest("https://oapi.dingtalk.com/topapi/message/corpconversation/asyncsend_v2", $data);
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
