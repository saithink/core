<?php
// +----------------------------------------------------------------------
// | saithink [ saithink快速开发框架 ]
// +----------------------------------------------------------------------
// | Author: sai <1430792918@qq.com>
// +----------------------------------------------------------------------
namespace saithink\core\app\logic\system;

use saithink\core\basic\BaseLogic;
use saithink\core\exception\ApiException;
use saithink\core\app\model\system\SystemDict;
use saithink\core\utils\Helper;

/**
 * 字典类型逻辑层
 */
class SystemDictLogic extends BaseLogic
{
    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->model = new SystemDict();
    }

    /**
     * 保存数据
     * @param $data
     */
    public function save($data)
    {
        $info = $this->model->where('dict_type', $data['dict_type'])->find();
        if ($info) {
            throw new ApiException('当前字典类型已经存在，请勿重复添加');
        }
        return $this->model->save($data);
    }

    /**
     * 修改数据
     * @param $data
     * @param $where
     */
    public function update($data, $where)
    {
        $info = $this->model->where($where)->find();
        if ($data['dict_type'] !== $info['dict_type']) {
            $temp = $this->model->where('dict_type', $data['dict_type'])->find();
            if ($temp) {
                throw new ApiException('当前字典类型已经存在，请勿修改');
            } else {
                $logic = new SystemDicdLogic();
                $logic->update(['dict_type' => $data['dict_type']], ['dict_type' => $info['dict_type']]);
            }
        }
        return $this->model->update($data, $where);
    }
}
