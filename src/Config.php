<?php

/**
 * 用于处理配置信息
 * 
 * Class Config 配置类
 * 
 * PHP version 7.2
 * 
 * @category Spool
 * @package  Config
 * @author   yydick Chen <yydick@sohu.com>
 * @license  https://spdx.org/licenses/Apache-2.0.html Apache-2.0
 * @link     http://url.com
 * @DateTime 2020-10-20
 */

namespace Spool\Config;

use Spool\Config\Data;
use Spool\Exception\SpoolException;

/**
 * 用于处理配置信息
 * 
 * Class Config 配置类
 * 
 * PHP version 7.2
 * 
 * @category Spool
 * @package  Config
 * @author   yydick Chen <yydick@sohu.com>
 * @license  https://spdx.org/licenses/Apache-2.0.html Apache-2.0
 * @link     http://url.com
 * @DateTime 2020-10-20
 */
class Config extends Data
{
    protected static $path = '';
    /**
     * 判断键名的配置是否存在
     * 
     * @param array|string $keys 要判断的键名
     * 
     * @return boolean
     */
    public function has($keys): bool
    {
        if (is_string($keys)) {
            return isset($this->data[$keys]);
        } elseif (is_array($keys)) {
            foreach ($keys as $value) {
                $has = isset($this->data[$value]);
                if (!$has) {
                    return $has;
                }
            }
            return $has;
        }
        return false;
    }
    /**
     * 获取配置的值
     * 
     * @param array|string $keys    要获取的键名
     * @param mixed        $default 默认值
     * 
     * @return mixed
     */
    public function get($keys, $default = null)
    {
        if (is_array($keys)) {
            $config = [];
            foreach ($keys as $key => $value) {
                $config[$key] = isset($this->data[$key]) ? $this->data[$key] : $default;
            }
            return $config;
        }
        if (is_string($keys)) {
            if (strpos($keys, '.') === false) {
                return isset($this->data[$keys]) ? $this->data[$keys] : $default;
            }
            $array = $this->toArray();
            foreach (explode('.', $keys) as $k) {
                if (isset($array[$k])) {
                    $array = $array[$k];
                } else {
                    return $default;
                }
            }
            return $array;
        }
        return $this->toArray();
    }
    /**
     * 设置配置项
     * 
     * @param array|string $keys  要设置的键名,可以是数组
     * @param mixed        $value 要设置的值
     * 
     * @return void
     */
    public function set($keys, $value = null): void
    {
        if (is_string($keys)) {
            $this->data[$keys] = $value;
        }
        if (is_array($keys)) {
            foreach ($keys as $key => $val) {
                $this->data[$key] = $val;
            }
        }
    }
    /**
     * 获取配置目录下所有配置信息
     * 
     * @param string $path 配置文件所在目录
     * 
     * @return array
     */
    public function getConfigFiles(string $path): array
    {
        $config = $files = [];
        if (!is_dir($path)) {
            throw new SpoolException("{$path} not a directory.");
        }
        $files = scandir($path);
        static::$path = $path;
        foreach ($files as $file) {
            $fileInfo = pathinfo($file);
            $res = $this->checkConfigFiles($fileInfo);
            if (!$res) {
                continue;
            }
            $key = $fileInfo['filename'];
            $config[$key] = $res;
        }
        static::$data = $config;
        return $config;
    }
    /**
     * 检测配置文件是否能够解析
     * 
     * @param array $fileInfo pathinfo函数返回的文件信息
     * 
     * @return array
     */
    protected function checkConfigFiles(array $fileInfo): array
    {
        $res = [];
        if (in_array($fileInfo['extension'], ['ini', 'php'], true)) {
            $ext = $fileInfo['extension'];
            $filename = static::$path .
                DIRECTORY_SEPARATOR .
                $fileInfo['basename'];
            if ($ext == 'ini') {
                $res = parse_ini_file($filename, true);
            }
            if ($ext == 'php') {
                $res = require $filename;
            }
        }
        return $res;
    }
}
