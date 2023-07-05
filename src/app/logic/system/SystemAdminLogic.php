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

}