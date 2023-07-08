<?php
// +----------------------------------------------------------------------
// | saithink [ saithink快速开发框架 ]
// +----------------------------------------------------------------------
// | Author: sai <1430792918@qq.com>
// +----------------------------------------------------------------------
namespace saithink\core\app\model\system;

use saithink\core\basic\BaseModel;
/**
 * 字典类型模型
 * Class SystemDict
 * @package app\model
 */
class SystemDict extends BaseModel
{
    /**
     * 数据表主键
     * @var string
     */
    protected $pk = 'id';

    protected $table = 'eb_system_dict';

    /**
     * 关键字搜索
     */
    public function searchKeywordsAttr($query, $value)
    {
        $query->where('dict_name|dict_type', 'LIKE', "%$value%");
    }

    /**
     * 关联字典数据
     * @return SystemDict|\think\model\relation\HasMany
     */
    public function list()
    {
        return $this->hasMany(SystemDicd::class, 'dict_type', 'dict_type');
    }
}