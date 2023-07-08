<?php
// +----------------------------------------------------------------------
// | saithink [ saithink快速开发框架 ]
// +----------------------------------------------------------------------
// | Author: sai <1430792918@qq.com>
// +----------------------------------------------------------------------
namespace saithink\core\app\controller;

use saithink\core\utils\Request;
use saithink\core\utils\Captcha;
use saithink\core\app\logic\system\SystemAdminLogic;

/**
 * 登录控制器
 */
class Login
{
    /**
     * 获取验证码
     * @return \think\Response
     */
    public function captcha()
    {
        $captcha = new Captcha();
        return $captcha->create();
    }

    /**
     * 管理员登录
     * @return void
     */
    public function login(Request $request)
    {
        $username = $request->param('username');
        $password = $request->param('password');
        $code = $request->param('code');

//        $captcha = new Captcha();
//        if (!$captcha->check($code)) {
//            return app('json')->fail('验证码错误');
//        }

        $logic = new SystemAdminLogic();
        $data = $logic->login($username, $password, 'pc');

        return app('json')->success($data);
    }
}