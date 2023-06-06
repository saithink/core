<?php
// +----------------------------------------------------------------------
// | saithink [ saithink快速开发框架 ]
// +----------------------------------------------------------------------
// | Author: sai <1430792918@qq.com>
// +----------------------------------------------------------------------
namespace saithink\core\basic;

use think\Model;
use think\model\concern\SoftDelete;

/**
 * 模型基类
 * @package saithink\basic
 * @mixin ModelTrait
 */
class BaseModel extends Model
{
    use SoftDelete;
    // 删除时间
    protected $deleteTime = 'delete_time';
    // 添加时间
    protected $createTime = 'create_time';
    // 更新时间
    protected $updateTime = 'update_time';
    // 隐藏字段
    protected $hidden = ['update_time','delete_time'];

}