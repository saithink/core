<?php
// +----------------------------------------------------------------------
// | saithink [ saithink快速开发框架 ]
// +----------------------------------------------------------------------
// | Author: sai <1430792918@qq.com>
// +----------------------------------------------------------------------
namespace saithink\core\basic;

use think\facade\Config;

/**
 * 文件上传基类
 * @package saithink\core\basic
 */
abstract class BaseUpload
{

    /**
     * 图片信息
     * @var array
     */
    protected $fileInfo;

    /**
     * 验证配置
     * @var string
     */
    protected $validate;

    /**
     * 配置文件
     * @var array
     */
    protected $configFile;

    /**
     * 保存路径
     * @var string
     */
    protected $path = '';

    protected function __construct(array $config)
    {
        $this->configFile = isset($config['upload']) ? $config['upload'] : 'saithink.upload';
        $this->fileInfo = new \StdClass();
    }


    /**
     * 上传文件路径
     * @param string $path
     * @return $this
     */
    public function to(string $path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * 获取文件信息
     * @return array
     */
    public function getFileInfo()
    {
        return $this->fileInfo;
    }

    /**
     * 验证合法上传域名
     * @param string $url
     * @return string
     */
    protected function checkUploadUrl(string $url)
    {
        if ($url && strstr($url, 'http') === false) {
            $url = 'http://' . $url;
        }
        return $url;
    }

    /**
     * 获取系统配置
     * @return mixed
     */
    protected function getConfig()
    {
        return Config::get($this->configFile, []);;
    }

    /**
     * 设置验证规则
     * @param array|null $validate
     * @return $this
     */
    public function validate(?array $validate = null)
    {
        if (is_null($validate)) {
            $validate = $this->getConfig();
        }
        $this->extractValidate($validate);
        return $this;
    }

    /**
     * 提取上传验证
     */
    protected function extractValidate(array $validateArray)
    {
        $validate = [];
        foreach ($validateArray as $key => $value) {
            $validate[] = $key . ':' . (is_array($value) ? implode(',', $value) : $value);
        }
        $this->validate = implode('|', $validate);
        unset($validate);
    }

    /**
     * 提取文件名
     * @param string $path
     * @param string $ext
     * @return string
     */
    protected function saveFileName(string $path = null, string $ext = 'jpg')
    {
        return ($path ? substr(md5($path), 0, 5) : '') . date('YmdHis') . rand(0, 9999) . '.' . $ext;
    }

    /**
     * 获取上传信息
     * @return array
     */
    public function getUploadInfo()
    {
        if (isset($this->fileInfo->filePath)) {
            if (strstr($this->fileInfo->filePath, 'http') === false) {
                $url = app()->request->domain() . $this->fileInfo->filePath;
            } else {
                $url = $this->fileInfo->filePath;
            }
            return [
                'name' => $this->fileInfo->fileName,
                'real_name' => $this->fileInfo->realName ?? '',
                'size' => $this->fileInfo->size,
                'type' => $this->fileInfo->type,
                'dir' => $this->fileInfo->filePath,
                'url' => $url,
                'time' => time(),
            ];
        } else {
            return [];
        }
    }

    /**
     * 文件上传
     * @return mixed
     */
    abstract public function move(string $file = 'file');

    /**
     * 文件流上传
     * @return mixed
     */
    abstract public function stream(string $fileContent, string $key = null);

    /**
     * 删除文件
     * @return mixed
     */
    abstract public function delete(string $filePath);

}
