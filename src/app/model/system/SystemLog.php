<?php
// +----------------------------------------------------------------------
// | saithink [ saithink快速开发框架 ]
// +----------------------------------------------------------------------
// | Author: sai <1430792918@qq.com>
// +----------------------------------------------------------------------
namespace saithink\core\app\model\system;

use saithink\core\basic\BaseModel;

/**
 * 系统日志模型
 */
class SystemLog extends BaseModel
{
    // 完整数据库表名称
    protected $table  = 'eb_system_log';
    // 主键
    protected $pk = 'id';

    /**
     * 关键字搜索
     */
    public function searchKeywordsAttr($query, $value)
    {
        $query->where('admin_name|path|ip', 'LIKE', "%$value%");
    }

    /**
     * 时间范围搜索
     */
    public function searchCreateTimeAttr($query, $value)
    {
        $query->whereTime('create_time', 'between', $value);
    }
}