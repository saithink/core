<?php
// +----------------------------------------------------------------------
// | saithink [ saithink快速开发框架 ]
// +----------------------------------------------------------------------
// | Author: sai <1430792918@qq.com>
// +----------------------------------------------------------------------
namespace saithink\core\app\model\system;

use saithink\core\basic\BaseModel;

/**
 * 系统管理员模型
 */
class SystemAdmin extends BaseModel
{
    // 完整数据库表名称
    protected $table  = 'eb_system_admin';
    // 主键
    protected $pk = 'id';

    /**
     * 权限字段处理
     */
    public static function getRolesAttr($value)
    {
        return explode(',', $value);
    }

    /**
     * 关键字搜索
     */
    public function searchKeywordsAttr($query, $value)
    {
        $query->where('account|real_name|phone', 'LIKE', "%$value%");
    }
}