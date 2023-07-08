<?php
// +----------------------------------------------------------------------
// | saithink [ saithink快速开发框架 ]
// +----------------------------------------------------------------------
// | Author: sai <1430792918@qq.com>
// +----------------------------------------------------------------------
namespace saithink\core\utils;

use think\helper\Str;

/**
 * 帮助类
 */
class Helper
{
    /**
     * 数据树形化
     * @param array $data 数据
     * @param string $childrenname 子数据名
     * @param string $keyName 数据key名
     * @param string $pidName 数据上级key名
     * @return array
     */
    public static function makeTree(array $data, string $childrenname = 'children', string $keyName = 'id', string $pidName = 'pid')
    {
        $list = [];
        foreach ($data as $value) {
            $list[$value[$keyName]] = $value;
        }
        static $tree = []; //格式化好的树
        foreach ($list as $item) {
            if (isset($list[$item[$pidName]])) {
                $list[$item[$pidName]][$childrenname][] = &$list[$item[$keyName]];
            } else {
                $tree[] = &$list[$item[$keyName]];
            }
        }
        return $tree;
    }

    /**
     * 生成ElementPlus菜单
     * @param array $data 数据
     * @param string $childrenname 子数据名
     * @param string $keyName 数据key名
     * @param string $pidName 数据上级key名
     * @return array
     */
    public static function makeEleMenus(array $data, string $childrenname = 'children', string $keyName = 'id', string $pidName = 'pid')
    {
        $list = [];
        foreach ($data as $value) {
            if ($value['auth_type'] === 1){
                $component = '';
                $path = $value['path'];
                $temp = [
                    $keyName => $value[$keyName],
                    $pidName => $value[$pidName],
                    'name' => Str::camel(str_replace('/','_',$value['path'])),
                    'path' => $path,
                    'component' => $value['component'],
                    'meta' => [
                        'title' => $value['title'],
                        'isLink' => $value['link_url'],
                        'isKeepAlive' => $value['is_keep'] === 1 ? true : false,
                        'isAffix' => $value['is_affix'] === 1 ? true : false,
                        'isIframe' => $value['is_iframe'] === 1 ? true : false,
                        'isHide' => $value['is_hide'] === 1 ? true : false,
                        'icon' => $value['icon'],
                    ],
                ];
                $list[$value[$keyName]] = $temp;
            }
        }
        static $tree = []; //格式化好的树
        foreach ($list as $item) {
            if (isset($list[$item[$pidName]])) {
                $list[$item[$pidName]][$childrenname][] = &$list[$item[$keyName]];
            } else {
                $tree[] = &$list[$item[$keyName]];
            }
        }
        return $tree;
    }

    /**
     * 生成按钮权限数组
     * @param array $data 数据
     * @return array
     */
    public static function makeEleBtns(array $data)
    {
        $list = [];
        foreach ($data as $value) {
            if ($value['auth_type'] === 2){
                $str = $value['methods'].' '.$value['api_url'];
                array_push($list, $str);
            }
        }
        return $list;
    }
}