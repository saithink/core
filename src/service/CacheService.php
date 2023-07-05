<?php
// +----------------------------------------------------------------------
// | saithink [ saithink快速开发框架 ]
// +----------------------------------------------------------------------
// | Author: sai <1430792918@qq.com>
// +----------------------------------------------------------------------
namespace saithink\core\service;

use think\facade\Config;
use think\facade\Cache as CacheStatic;

/**
 * 缓存服务
 */
class CacheService
{
    /**
     * 标签名
     * @var string
     */
    protected static $globalCacheName = '_cached_1890004400';

    /**
     * 过期时间
     * @var int
     */
    protected static $expire;

    /**
     * 获取缓存过期时间
     * @param int|null $expire
     * @return int
     */
    protected static function getExpire(int $expire = null): int
    {
        if (self::$expire) {
            return (int)self::$expire;
        }
        $expire = !is_null($expire) ? $expire : Config::get('cache.expire', 0);
        if (!is_int($expire))
            $expire = (int)$expire;
        return self::$expire = $expire;
    }

    /**
     * 写入缓存
     * @param string $name 缓存名称
     * @param mixed $value 缓存值
     * @param int $expire 缓存时间，为0读取系统缓存时间
     * @return bool
     */
    public static function set(string $name, $value, int $expire = null): bool
    {
        return self::handler()->set($name, $value, $expire ?? self::getExpire($expire));
    }

    /**
     * 如果不存在则写入缓存
     * @param string $name
     * @param bool $default
     * @return mixed
     */
    public static function get(string $name, $default = false, int $expire = null)
    {
        try {
            return self::handler()->remember($name, $default, $expire ?? self::getExpire($expire));
        } catch (\Throwable $e) {
            try {
                if (is_callable($default)) {
                    return $default();
                } else {
                    return $default;
                }
            } catch (\Throwable $e) {
                return null;
            }
        }
    }

    /**
     * 删除缓存
     * @param string $name
     * @return bool
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public static function delete(string $name)
    {
        return CacheStatic::delete($name);
    }

    /**
     * 缓存句柄
     *
     * @return \think\cache\TagSet|CacheStatic
     */
    public static function handler(?string $cacheName = null)
    {
        return CacheStatic::tag($cacheName ?: self::$globalCacheName);
    }

    /**
     * 清空缓存池
     * @return bool
     */
    public static function clear()
    {
        return self::handler()->clear();
    }

    /**
     * 魔术方法
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        return self::handler()->{$name}(...$arguments);
    }

    /**
     * 魔术方法
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return self::handler()->{$name}(...$arguments);
    }
}
