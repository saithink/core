<?php
// +----------------------------------------------------------------------
// | saithink [ saithink快速开发框架 ]
// +----------------------------------------------------------------------
// | Author: sai <1430792918@qq.com>
// +----------------------------------------------------------------------
namespace saithink\core\middleware;

use saithink\core\utils\Request;
use think\facade\Config;
use saithink\core\app\logic\system\SystemAdminLogic;
use saithink\core\exception\ApiException;

/**
 * 系统Token中间件
 * Class SystemLog
 * @package saithink\core\middleware
 */
class SystemToken
{
    /**
     * @param Request $request
     * @param \Closure $next
     * @return Response
     */
    public function handle(Request $request, \Closure $next)
    {
        try {
            $rule = trim(strtolower($request->baseUrl()));
            $whiteList = Config::get('saithink.white_list', []);
            if (in_array($rule, $whiteList)) {
                Request::macro('adminId', function () {
                    return 0;
                });
                Request::macro('adminName', function () {
                    return 'anonymity';
                });
                return $next($request);
            }
            $token = trim($request->header(Config::get('saithink.cross.token_name', 'Authori-zation')));
            $jwt = new SystemAdminLogic();
            $result = $jwt->parseToken($token);
            Request::macro('adminId', function () use (&$result) {
                return $result['id'];
            });
            Request::macro('adminName', function () use (&$result) {
                return $result['account'];
            });
            Request::macro('adminInfo', function () use (&$result) {
                return $result;
            });
        } catch (\Exception $e) {
            throw new ApiException('您的登录凭证错误或者已过期，请重新登录', 401);
        }
        return $next($request);
    }
}
