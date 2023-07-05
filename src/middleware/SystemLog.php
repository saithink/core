<?php
// +----------------------------------------------------------------------
// | saithink [ saithink快速开发框架 ]
// +----------------------------------------------------------------------
// | Author: sai <1430792918@qq.com>
// +----------------------------------------------------------------------
namespace saithink\core\middleware;

use saithink\core\utils\Request;
use saithink\core\app\logic\system\SystemLogLogic;
use think\Exception;

/**
 * 系统日志中间件
 * Class SystemLog
 * @package saithink\core\middleware
 */
class SystemLog
{
    /**
     * @param Request $request
     * @param \Closure $next
     * @return Response
     */
    public function handle(Request $request, \Closure $next)
    {
        try {
            $logic = new SystemLogLogic();
            $logic->recordLog($request->adminId(), $request->adminName());
        } catch (\Throwable $e) {
//            throw new Exception($e->getMessage());
        }
        return $next($request);
    }
}
