<?php
// +----------------------------------------------------------------------
// | saithink [ saithink快速开发框架 ]
// +----------------------------------------------------------------------
// | Author: sai <1430792918@qq.com>
// +----------------------------------------------------------------------
namespace saithink\core\middleware;

use think\Response;
use think\facade\Config;
use saithink\core\utils\Request;

/**
 * 跨域中间件
 * Class AllowOrigin
 * @package saithink\core\middleware
 */
class AllowOrigin
{
    /**
     * 允许跨域的域名
     * @var string
     */
    protected $cookieDomain;

    /**
     * @param Request $request
     * @param \Closure $next
     * @return Response
     */
    public function handle(Request $request, \Closure $next)
    {
        $this->cookieDomain = Config::get('cookie.domain', '');
        $header = Config::get('saithink.cross.header');
        $origin = $request->header('origin');
        if ($origin && ('' == $this->cookieDomain || strpos($origin, $this->cookieDomain)))
            $header['Access-Control-Allow-Origin'] = $origin;
        if ($request->method(true) == 'OPTIONS') {
            $response = Response::create('ok')->code(200)->header($header);
        } else {
            $response = $next($request)->header($header);
        }
        $request->filter(['strip_tags', 'addslashes', 'trim']);
        return $response;
    }
}
