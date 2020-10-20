<?php

/**
 * 类似Spring的@Data注解
 * 
 * Class Data 用于自动生成调用方法
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

use ArrayAccess;
use \stdClass;

/**
 * 类似Spring的@Data注解
 * 
 * Class Data 用于自动生成调用方法
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
class Data extends stdClass implements ArrayAccess
{
    protected static $instance;
    protected static $data;
    /**
     * 单例模式
     * 
     * @return Data
     */
    public static function getInstance(): Data
    {
        if (is_null(static::$instance)) {
            static::$instance = new static;
        }
        return static::$instance;
    }
    /**
     * 绑定数组内容
     * 
     * @param array $items 要绑定的数组
     * 
     * @return array
     */
    public function bindArray(array $items = []): array
    {
        $backed = $this->toArray();
        static::$data = json_decode(json_encode($items));
        return $backed;
    }
    /**
     * 返回数组
     * 
     * @return array
     */
    public function toArray(): array
    {
        return json_decode(json_encode(static::$data), true);
    }
    /**
     * 实现接口
     * 
     * @param [string] $key 要确认的键名
     * 
     * @return boolean
     */
    public function offsetExists($key): bool
    {
        return isset(static::$data->$key);
    }
    /**
     * 实现接口
     * 
     * @param [string] $key 要获取的键名
     * 
     * @return void
     */
    public function offsetGet($key)
    {
        return static::$data->$key;
    }
    /**
     * 实现接口
     * 
     * @param [string] $key   要设置的键名
     * @param [type]   $value 要设置的值
     * 
     * @return void
     */
    public function offsetSet($key, $value): void
    {
        static::$data->key = $value;
    }
    /**
     * 实现接口
     * 
     * @param [string] $key 要删除的键名
     * 
     * @return void
     */
    public function offsetUnset($key): void
    {
        unset(static::$data->$key);
    }
}
