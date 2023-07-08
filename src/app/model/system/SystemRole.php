<?php
// +----------------------------------------------------------------------
// | saithink [ saithink快速开发框架 ]
// +----------------------------------------------------------------------
// | Author: sai <1430792918@qq.com>
// +----------------------------------------------------------------------
namespace saithink\core\app\model\system;

use saithink\core\basic\BaseModel;
/**
 * 角色模型
 * Class SystemRole
 * @package app\model
 */
class SystemRole extends BaseModel
{
    /**
     * 数据表主键
     * @var string
     */
    protected $pk = 'id';

    protected $table = 'eb_system_role';

    /**
     * 关键字搜索
     */
    public function searchKeywordsAttr($query, $value)
    {
        $query->where('role_name', 'LIKE', "%$value%");
    }

    /**
     * Id搜索
     */
    public function searchIdAttr($query, $value)
    {
        $query->whereIn('id', $value);
    }

}