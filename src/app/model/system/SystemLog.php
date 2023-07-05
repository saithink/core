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
}