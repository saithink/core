<?php
// +----------------------------------------------------------------------
// | saithink [ saithink快速开发框架 ]
// +----------------------------------------------------------------------
// | Author: sai <1430792918@qq.com>
// +----------------------------------------------------------------------
namespace saithink\core\app\controller;

use think\App;
use saithink\core\basic\BaseController;
use saithink\core\app\logic\system\SystemMenuLogic;
use saithink\core\app\logic\system\SystemAdminLogic;

/**
 * 系统控制器
 */
class System extends BaseController
{
    /**
     * 构造
     * @param SystemMenuLogic $logic
     */
    public function __construct(App $app)
    {
        parent::__construct($app);
    }

    /**
     * 用户信息
     */
    public function userInfo()
    {
        $info = $this->adminInfo;
        $logic = new SystemMenuLogic();
        $btnList = $logic->getBtns($this->adminInfo['roles'], 1);
        $info['btnList'] = $btnList;
        $info['roles'] = ['admin'];
        return $this->app['json']->success($info);
    }

    /**
     * 获取菜单
     */
    public function menus()
    {
        $logic = new SystemMenuLogic();
        $menus = $logic->getMenus($this->adminInfo['roles'], $this->adminInfo['level']);
        return $this->app['json']->success($menus);
    }
}