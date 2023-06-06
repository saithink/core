<?php
// +----------------------------------------------------------------------
// | saithink [ saithink快速开发框架 ]
// +----------------------------------------------------------------------
// | Author: sai <1430792918@qq.com>
// +----------------------------------------------------------------------
namespace saithink\core\exception;

/**
 * API 接口错误信息
 * Class ApiException
 * @package saithink\core\exception
 */
class ApiException extends \RuntimeException
{
    public function __construct($message, $code = 0, \Throwable $previous = null)
    {
        if (is_array($message)) {
            $errInfo = $message;
            $message = $errInfo[1] ?? '未知错误';
            if ($code === 0) {
                $code = $errInfo[0] ?? 400;
            }
        }
        parent::__construct($message, $code, $previous);
    }
}
