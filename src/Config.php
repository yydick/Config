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
    /**
     * 初始化
     * 
     * @param array $data 要设置的配置
     */
    public function __construct(array $data = [])
    {
        $this->bindArray($data);
    }
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
}
