<?php
// +----------------------------------------------------------------------
// | saithink [ saithink快速开发框架 ]
// +----------------------------------------------------------------------
// | Author: sai <1430792918@qq.com>
// +----------------------------------------------------------------------
namespace saithink\core\app\controller\system;

use think\App;
use saithink\core\basic\BaseController;
use saithink\core\app\logic\system\SystemAdminLogic;

/**
 * 管理员控制器
 */
class SystemAdmin extends BaseController
{
    /**
     * 构造
     * @param SystemAdminLogic $logic
     */
    public function __construct(App $app, SystemAdminLogic $logic)
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
            ['keywords', '']
        ]);
        $data = $this->logic->getList($where, ['hidden' => ['pwd', 'delete_time']]);
        return $this->app['json']->success($data);
    }

    /**
     * 保存数据
     * @return mixed
     */
    public function save()
    {
        $data = $this->request->more([
            ['account', ''],
            ['real_name', ''],
            ['pwd', ''],
            ['dept_id', ''],
            ['phone', ''],
            ['email', ''],
            ['sex', ''],
            ['status', ''],
            ['roles', []],
        ]);
        $data['pwd'] = password_hash($data['pwd'], PASSWORD_DEFAULT);
        $data['dept_id'] = $data['dept_id'][count($data['dept_id']) - 1];
        $data['roles'] = implode(',', $data['roles']);
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
            ['account', ''],
            ['real_name', ''],
            ['pwd', ''],
            ['dept_id', ''],
            ['phone', ''],
            ['email', ''],
            ['sex', ''],
            ['status', ''],
            ['roles', []],
        ]);
        if (!empty($data['pwd'])) {
            $data['pwd'] = password_hash($data['pwd'], PASSWORD_DEFAULT);
        } else {
            unset($data['pwd']);
        }
        $data['dept_id'] = is_array($data['dept_id']) ? $data['dept_id'][count($data['dept_id']) - 1] : $data['dept_id'];
        $data['roles'] = implode(',', $data['roles']);
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
