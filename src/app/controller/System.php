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
        $btnList = $logic->getBtns($this->adminInfo['roles'], $this->adminInfo['level']);
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

    /**
     * 更新个人资料
     */
    public function modifyUser()
    {
        $data = $this->request->more([
            ['real_name', ''],
            ['phone', ''],
            ['email', ''],
            ['sex', ''],
        ]);
        $logic = new SystemAdminLogic();
        $result = $logic->update($data, ['id' => $this->adminId]);
        if ($result) {
            return $this->app['json']->success('操作成功');
        } else {
            return $this->app['json']->fail('操作失败');
        }
    }

    /**
     * 修改密码
     */
    public function modifyPwd()
    {
        $data = $this->request->more([
            ['pwd', ''],
            ['new_pwd', ''],
        ]);
        $logic = new SystemAdminLogic();
        $info = $logic->find($this->adminId);
        if(!password_verify($data['pwd'], $info->pwd)) {
            return $this->app['json']->fail('修改失败，原始密码错误！');
        }
        $info->pwd = password_hash($data['new_pwd'], PASSWORD_DEFAULT);
        $result = $info->save();
        if ($result) {
            return $this->app['json']->success('操作成功');
        } else {
            return $this->app['json']->fail('操作失败');
        }
    }
}