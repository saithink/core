<?php
// +----------------------------------------------------------------------
// | saithink [ saithink快速开发框架 ]
// +----------------------------------------------------------------------
// | Author: sai <1430792918@qq.com>
// +----------------------------------------------------------------------
namespace saithink\core\basic;

use think\Db;

/**
 * 逻辑层基础类
 * @package app\service
 * @method static find($id) think-orm的find方法
 * @method static save($data) think-orm的save方法
 * @method static create($data) think-orm的create方法
 * @method static saveAll($data) think-orm的saveAll方法
 * @method static update($data, $where) think-orm的update方法
 * @method static destroy($id) think-orm的destroy方法
 * @method static select($data) think-orm的select方法
 * @method static count($data) think-orm的count方法
 * @method static max($data) think-orm的max方法
 * @method static min($data) think-orm的min方法
 * @method static sum($data) think-orm的sum方法
 * @method static avg($data) think-orm的avg方法
 */
class BaseLogic
{
    /**
     * 模型注入
     * @var object
     */
    protected $model;

    /**
     * 数据库事务操作
     * @param callable $closure
     * @param bool $isTran
     * @return mixed
     */
    public function transaction(callable $closure, bool $isTran = true): mixed
    {
        return $isTran ? Db::transaction($closure) : $closure();
    }

    /**
     * 获取当前模型
     * @return object
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * 搜索器搜索
     * @param array $searchWhere
     * @return mixed
     */
    public function search(array $searchWhere = [])
    {
        $withSearch = array_keys($searchWhere);
        $data = $searchWhere;
        foreach ($withSearch as $k => $v) {
            if ($data[$v] === '') {
                unset($data[$v]);
                unset($withSearch[$k]);
            }
        }
        return $this->model->withSearch($withSearch, $data);
    }

    /**
     * 查询全部数据
     * @param $searchWhere 搜索器
     * @param $attach join|with|field|where|order
     * @return array
     */
    public function getAll($searchWhere = [], $attach = [])
    {
        $model = $this->search($searchWhere);
        if (isset($attach['join'])) {
            $model = $model->alias('a')->join($attach['join']);
        }
        if (isset($attach['with'])) {
            $model = $model->with($attach['with']);
        }
        if (isset($attach['field'])) {
            $model = $model->field($attach['field']);
        }
        if (isset($attach['where'])) {
            $model = $model->where($attach['where']);
        }
        if (isset($attach['order'])) {
            $model = $model->order($attach['order']);
        }
        if (isset($attach['hidden'])) {
            $model = $model->hidden($attach['hidden']);
        }
        $data = $model->select()->toArray();
        return compact('data');
    }

    /**
     * 分页查询数据
     * @param $searchWhere 搜索器
     * @param $attach join|with|field|where|order
     * @return mixed
     */
    public function getList($searchWhere = [], $attach = [])
    {
        $page = input('page') ? input('page') : 1;
        $limit = input('limit') ? input('limit') : 20;
        $model = $this->search($searchWhere);
        if (isset($attach['join'])) {
            $model = $model->alias('a')->join($attach['join']);
        }
        if (isset($attach['with'])) {
            $model = $model->with($attach['with']);
        }
        if (isset($attach['field'])) {
            $model = $model->field($attach['field']);
        }
        if (isset($attach['where'])) {
            $model = $model->where($attach['where']);
        }
        if (isset($attach['order'])) {
            $model = $model->order($attach['order']);
        }
        if (isset($attach['hidden'])) {
            $model = $model->hidden($attach['hidden']);
        }
        return $model->paginate($limit, false, ['page' => $page])->toArray();
    }

    /**
     * 方法调用
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
        return call_user_func_array([$this->model, $name], $arguments);
    }
}
