<?php
// +----------------------------------------------------------------------
// | saithink [ saithink快速开发框架 ]
// +----------------------------------------------------------------------
// | Author: sai <1430792918@qq.com>
// +----------------------------------------------------------------------
namespace saithink\core\app\logic\system;

use saithink\core\basic\BaseLogic;
use saithink\core\exception\ApiException;
use saithink\core\app\model\system\SystemDicd;
use saithink\core\utils\Helper;

/**
 * 字典数据逻辑层
 */
class SystemDicdLogic extends BaseLogic
{
    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->model = new SystemDicd();
    }

}
