<?php
// +----------------------------------------------------------------------
// | saithink [ saithink快速开发框架 ]
// +----------------------------------------------------------------------
// | Author: sai <1430792918@qq.com>
// +----------------------------------------------------------------------
namespace saithink\core\service;

use think\facade\Config;
use think\facade\Filesystem;
use think\facade\Validate;
use think\File;
use saithink\core\basic\BaseUpload;
use saithink\core\exception\ApiException;

/**
 * 文件上传类
 */
class UploadService extends BaseUpload
{

    /**
     * 默认存放路径
     * @var string
     */
    protected $defaultPath;


    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $this->defaultPath = Config::get('filesystem.disks.' . Config::get('filesystem.default') . '.url');
    }

    /**
     * 生成上传文件目录
     * @param $path
     * @param null $root
     * @return string
     */
    protected function uploadDir($path, $root = null)
    {
        if ($root === null) $root = Config::get('filesystem.disks.' . Config::get('filesystem.default') . '.root');
        return str_replace('\\', '/', $root . '/' . $path);
    }

    /**
     * 检查上传目录不存在则生成
     * @param $dir
     * @return bool
     */
    protected function validDir($dir)
    {
        return is_dir($dir) == true || mkdir($dir, 0777, true) == true;
    }

    /**
     * 文件上传
     * @param string $file
     * @return array|bool|mixed|\StdClass
     */
    public function move(string $file = 'file')
    {
        $fileHandle = app()->request->file($file);
        if (!$fileHandle) {
            throw new ApiException('上传文件不存在');;
        }
        if ($this->validate) {
            try {
                $error = [
                    $file . '.filesize' => '上传文件大小错误',
                    $file . '.fileExt' => '上传文件格式错误',
                    $file . '.fileMime' => '上传文件类型错误'
                ];
                validate([$file => $this->validate],$error)->check([$file => $fileHandle]);
            } catch (ApiException $e) {
                throw new ApiException($e->getMessage());
            }
        }
        $fileName = Filesystem::putFile($this->path, $fileHandle, 'md5');
        if (!$fileName){
            throw new ApiException('文件上传失败');
        }
        $filePath = Filesystem::path($fileName);
        $this->fileInfo->uploadInfo = new File($filePath);
        $this->fileInfo->realName = $fileHandle->getOriginalName();
        $this->fileInfo->fileName = $this->fileInfo->uploadInfo->getFilename();
        $this->fileInfo->size = $this->fileInfo->uploadInfo->getSize();
        $this->fileInfo->type = $this->fileInfo->uploadInfo->getExtension();
        $this->fileInfo->filePath = $this->defaultPath . '/' . str_replace('\\', '/', $fileName);
        return $this->fileInfo;
    }

    /**
     * 文件流上传
     * @param string $fileContent
     * @param string|null $key
     * @return array|bool|mixed|\StdClass
     */
    public function stream(string $fileContent, string $key = null)
    {
        if (!$key) {
            $key = $this->saveFileName();
        }
        if ($this->validate) {
            $fileExt = pathinfo($key, PATHINFO_EXTENSION);
            $validate = $this->getConfig();
            $checkRule = $validate['fileExt'];
            if (!in_array($fileExt, $checkRule)) {
                throw new ApiException('上传文件格式错误');
            }
        }
        $dir = $this->uploadDir($this->path);
        if (!$this->validDir($dir)) {
            throw new ApiException('文件目录生成失败，请检查权限');
        }
        $fileName = $dir . '/' . $key;
        file_put_contents($fileName, $fileContent);
        $this->fileInfo->uploadInfo = new File($fileName);
        $this->fileInfo->realName = $key;
        $this->fileInfo->fileName = $key;
        $this->fileInfo->size = $this->fileInfo->uploadInfo->getSize();
        $this->fileInfo->type = $this->fileInfo->uploadInfo->getExtension();
        $this->fileInfo->filePath = $this->defaultPath . '/' . $this->path . '/' . $key;
        return $this->fileInfo;
    }

    /**
     * 删除文件
     * @param string $filePath
     * @return bool|mixed
     */
    public function delete(string $filePath)
    {
        if (file_exists($filePath)) {
            try {
                unlink($filePath);
                return true;
            } catch (ApiException $e) {
                return new ApiException($e->getMessage());
            }
        }
        return false;
    }
}
