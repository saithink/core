<?php
// +----------------------------------------------------------------------
// | saithink [ saithink快速开发框架 ]
// +----------------------------------------------------------------------
// | Author: sai <1430792918@qq.com>
// +----------------------------------------------------------------------
namespace saithink\core\app\logic\system;

use saithink\core\basic\BaseLogic;
use saithink\core\exception\ApiException;
use saithink\core\app\model\system\SystemLog;

/**
 * 系统日志逻辑层
 */
class SystemLogLogic extends BaseLogic
{
    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->model = new SystemLog();
    }

    /**
     * 记录日志
     * @return void
     */
    public function recordLog($admin_id, $admin_name)
    {
        $request = app()->request;
        if ($request->isGet()) {
            return true;
        }
        $module = app('http')->getName();
        $rule = trim(strtolower($request->baseUrl()));
        $data = [
            'app' => $module,
            'admin_id' => $admin_id,
            'add_time' => time(),
            'admin_name' => $admin_name,
            'path' => $rule,
            'page' => '未知',
            'ip' => $request->ip(),
            'request_param'=>$this->filterParams($request->param()),
            'method' => $request->method()
        ];
        if ($this->model->save($data)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 过滤字段
     */
    protected function filterParams($params)
    {
        $blackList = ['pwd', 'conf_pwd', 'new_pwd', 'password','content'];
        foreach ($params as $key => $value) {
            if (in_array($key, $blackList)) {
                $params[$key] = '******';
            }
        }
        return serialize($params);
    }

}