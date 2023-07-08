<?php
// +----------------------------------------------------------------------
// | saithink [ saithink快速开发框架 ]
// +----------------------------------------------------------------------
// | Author: sai <1430792918@qq.com>
// +----------------------------------------------------------------------
namespace saithink\core\app\logic\system;

use saithink\core\basic\BaseLogic;
use saithink\core\exception\ApiException;
use saithink\core\app\model\system\SystemAdmin;
use saithink\core\utils\JwtAuth;

/**
 * 系统管理员逻辑层
 */
class SystemAdminLogic extends BaseLogic
{
    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->model = new SystemAdmin();
    }

    /**
     * 解析token
     * @param string $token
     * @return void
     */
    public function parseToken(string $token)
    {
        $jwt = new JwtAuth();
        [$id, $type] = $jwt->parseToken($token);

        $admin = $this->model->find($id);

        return $admin->hidden(['pwd', 'status'])->toArray();
    }

    /**
     * 用户登录
     * @param string $account
     * @param string $password
     * @param string $type
     * @return void
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function login(string $account, string $password, string $type)
    {
        $adminInfo = $this->model->where('account', $account)->find();
        if (!$adminInfo) {
        throw new ApiException('用户不存在!');
    }
        if (!$adminInfo->status) {
            throw new ApiException('您已被禁止登录!');
        }
        if (!password_verify($password, $adminInfo->pwd)) {
            throw new ApiException('账号或密码错误，请重新输入');
        }
        $adminInfo->last_time = date('Y-m-d H:i:s');
        $adminInfo->last_ip = app('request')->ip();
        $adminInfo->login_count++;
        $adminInfo->save();

        $jwt = new JwtAuth();
        $token = $jwt->createToken($adminInfo->id, $type);

        return [
            'token' => $token['token'],
            'expires_time' => $token['params']['exp'],
            'user_info' => $adminInfo->hidden(['pwd', 'delete_time', 'update_time'])->toArray(),
        ];
    }

}