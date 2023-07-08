<?php
// +----------------------------------------------------------------------
// | saithink [ saithink快速开发框架 ]
// +----------------------------------------------------------------------
// | Author: sai <1430792918@qq.com>
// +----------------------------------------------------------------------
namespace saithink\core\app\model\system;

use saithink\core\basic\BaseModel;
/**
 * 部门模型
 * Class SystemDept
 * @package app\model
 */
class SystemDept extends BaseModel
{
    /**
     * 数据表主键
     * @var string
     */
    protected $pk = 'id';

    protected $table = 'eb_system_dept';

    /**
     * 关键字搜索
     */
    public function searchKeywordsAttr($query, $value)
    {
        $query->where('dept_name|leader|phone', 'LIKE', "%$value%");
    }

}