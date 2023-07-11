<?php
// +----------------------------------------------------------------------
// | saithink [ saithink快速开发框架 ]
// +----------------------------------------------------------------------
// | Author: sai <1430792918@qq.com>
// +----------------------------------------------------------------------
namespace saithink\core\app\controller\system;

use think\App;
use saithink\core\basic\BaseController;
use saithink\core\app\logic\system\SystemLogLogic;

/**
 * 日志控制器
 */
class SystemLog extends BaseController
{
    /**
     * 构造
     * @param SystemLogLogic $logic
     */
    public function __construct(App $app, SystemLogLogic $logic)
    {
        parent::__construct($app);
        $this->logic = $logic;
    }

    /**
     * 数据列表
     * @return mixed
     */
    public function index()
    {
        $where = $this->request->more([
            ['create_time', ''],
            ['keywords', '']
        ]);
        $data = $this->logic->getList($where, ['order' => 'create_time DESC']);
        return $this->app['json']->success($data);
    }

}
