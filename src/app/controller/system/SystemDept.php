<?php
// +----------------------------------------------------------------------
// | saithink [ saithink快速开发框架 ]
// +----------------------------------------------------------------------
// | Author: sai <1430792918@qq.com>
// +----------------------------------------------------------------------
namespace saithink\core\app\controller\system;

use think\App;
use saithink\core\basic\BaseController;
use saithink\core\app\logic\system\SystemDeptLogic;

/**
 * 部门控制器
 */
class SystemDept extends BaseController
{
    /**
     * 构造
     * @param SystemDeptLogic $logic
     */
    public function __construct(App $app, SystemDeptLogic $logic)
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
            ['keywords', ''],
            ['status', '']
        ]);
        $data = $this->logic->tree($where);
        return $this->app['json']->success($data);
    }

    /**
     * 保存数据
     * @return mixed
     */
    public function save()
    {
        $data = $this->request->more([
            ['dept_name', ''],
            ['pid', 0],
            ['phone', ''],
            ['leader', ''],
            ['describe', ''],
            ['status', ''],
        ]);
        $data['pid'] = is_array($data['pid']) ? $data['pid'][count($data['pid']) - 1] : $data['pid'];
        $result = $this->logic->save($data);
        if ($result) {
            return $this->app['json']->success('操作成功');
        } else {
            return $this->app['json']->fail('操作失败');
        }
    }

    /**
     * 修改数据
     * @param $id
     * @return mixed
     */
    public function update($id)
    {
        $data = $this->request->more([
            ['dept_name', ''],
            ['pid', 0],
            ['phone', ''],
            ['leader', ''],
            ['describe', ''],
            ['status', ''],
        ]);
        $data['pid'] = is_array($data['pid']) ? $data['pid'][count($data['pid']) - 1] : $data['pid'];
        $result = $this->logic->update($data, ['id' => $id]);
        if ($result) {
            return $this->app['json']->success('操作成功');
        } else {
            return $this->app['json']->fail('操作失败');
        }
    }

    /**
     * 删除数据
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        if (!empty($id)) {
            $this->logic->destroy($id);
            return $this->app['json']->success('操作成功');
        } else {
            return $this->app['json']->fail('参数错误，请检查');
        }
    }
}
