<?php
// +----------------------------------------------------------------------
// | saithink [ saithink快速开发框架 ]
// +----------------------------------------------------------------------
// | Author: sai <1430792918@qq.com>
// +----------------------------------------------------------------------
namespace saithink\core\basic;

use think\App;

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
    }

}