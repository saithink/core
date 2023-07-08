<?php
// +----------------------------------------------------------------------
// | saithink [ saithink快速开发框架 ]
// +----------------------------------------------------------------------
// | Author: sai <1430792918@qq.com>
// +----------------------------------------------------------------------
namespace saithink\core\basic;

use think\App;
use think\facade\Config;
use saithink\core\exception\ApiException;
use saithink\core\app\logic\system\SystemMenuLogic;

/**
 * 基类 控制器继承此类
 */
class BaseController
{

    /**
     * 当前登陆管理员信息
     */
    protected $adminInfo;

    /**
     * 当前登陆管理员ID
     */
    protected $adminId;

    /**
     * 当前登陆管理员账号
     */
    protected $adminName;

    /**
     * Request实例
     */
    protected $request;

    /**
     * 逻辑层注入
     */
    protected $logic;

    /**
     * 构造方法
     * @access public
     * @param App $app 应用对象
     */
    public function __construct(App $app)
    {
        $this->app = $app;
        $this->request = app('request');
        // 控制器初始化
        $this->init();
    }

    /**
     * 初始化
     */
    protected function init()
    {
        $this->adminId = $this->request->adminId();
        $this->adminName = $this->request->adminName();
        $this->adminInfo = $this->request->adminInfo();
        // 接口权限认证
        $server_auth = Config::get('saithink.server_auth', false);
        if ($server_auth) {
            $this->checkAuth();
        }
    }

    /**
     * 接口权限认证
     */
    protected function checkAuth()
    {
        // 接口请求权限判断
        $rule = trim(strtolower($this->request->rule()->getRule()));
        $method = trim(strtoupper($this->request->method()));
        // 当前请求 接口权限
        $auth = $method . ' ' . $rule;
        $logic = new SystemMenuLogic();
        $btns = $logic->getAllBtns();
        if (in_array($auth, $btns)) {
            // 请求接口有权限配置则进行验证
            $allowBtns = $logic->getBtns($this->adminInfo['roles'], $this->adminInfo['level']);
            if (!in_array($auth, $allowBtns)) {
                throw new ApiException('您没有权限进行访问');
            }
        }
    }

}