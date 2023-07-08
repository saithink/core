<?php
// +----------------------------------------------------------------------
// | saithink [ saithink快速开发框架 ]
// +----------------------------------------------------------------------
// | Author: sai <1430792918@qq.com>
// +----------------------------------------------------------------------
namespace saithink\core\app\logic\system;

use saithink\core\basic\BaseLogic;
use saithink\core\exception\ApiException;
use saithink\core\app\model\system\SystemMenu;
use saithink\core\utils\Helper;
use saithink\lib\Arr;

/**
 * 菜单逻辑层
 */
class SystemMenuLogic extends BaseLogic
{
    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->model = new SystemMenu();
    }

    public function tree($where = [])
    {
        $data = $this->getAll($where);
        return Helper::makeTree($data['data']);
    }

    /**
     * 获取菜单
     */
    public function getMenus($role, $level)
    {
        if ($level === 0) {
            $data = $this->getAll(['auth_type' => 1]);
            return Helper::makeEleMenus($data['data']);
        }
        $roleLogic = new SystemRoleLogic();
        $roleList = $roleLogic->getAll(['id' => $role]);
        $arr = Arr::getArrayColumn($roleList['data'], 'rules');
        $str = Arr::unique($arr);
        $data = $this->getAll(['id' => $str, 'auth_type' => 1]);
        return Helper::makeEleMenus($data['data']);
    }

    /**
     * 获取按钮
     */
    public function getBtns($role, $level)
    {
        if ($level === 0) {
            return $this->getAllBtns();
        }
        $roleLogic = new SystemRoleLogic();
        $roleList = $roleLogic->getAll(['id' => $role]);
        $arr = Arr::getArrayColumn($roleList['data'], 'rules');
        $str = Arr::unique($arr);
        $data = $this->getAll(['id' => $str, 'auth_type' => 2]);
        return Helper::makeEleBtns($data['data']);
    }

    /**
     * 获取所有按钮
     */
    public function getAllBtns()
    {
        $data = $this->getAll(['auth_type' => 2]);
        return Helper::makeEleBtns($data['data']);
    }

}