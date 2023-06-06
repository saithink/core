<?php
// +----------------------------------------------------------------------
// | saithink [ saithink快速开发框架 ]
// +----------------------------------------------------------------------
// | Author: sai <1430792918@qq.com>
// +----------------------------------------------------------------------
namespace saithink\core\utils;

use think\exception\HttpResponseException;
use think\Response;
use think\facade\Request;

/**
 * Json输出类
 * Class Json
 * @package saithink\core\utils
 */
class Json
{
    private $code = 200;

    public function make(int $code, string $msg, ?array $data = null): Response
    {
        $request_url = Request::baseUrl();
        $res = compact('code', 'msg', 'request_url');
        if (!is_null($data))
            $res['data'] = $data;
        return Response::create($res, 'json', $this->code);
    }

    /**
     * 输出操作成功json数据
     */
    public function success($msg = 'ok', ?array $data = null): Response
    {
        if (is_array($msg)) {
            $data = $msg;
            $msg = 'ok';
        }
        return $this->make(200, $msg, $data);
    }

    /**
     * 输出操作失败json数据
     */
    public function fail($msg = 'fail', ?array $data = null): Response
    {
        if (is_array($msg)) {
            $data = $msg;
            $msg = 'fail';
        }
        return $this->make(400, $msg, $data);
    }
}
