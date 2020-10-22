<?php


/**
 * 通用ENV处理类
 * 
 * Class Env 用于处理本地.env文件
 * 
 * PHP version 7.2
 * 
 * @category Spool
 * @package  Confg
 * @author   yydick Chen <yydick@sohu.com>
 * @license  https://spdx.org/licenses/Apache-2.0.html Apache-2.0
 * @link     http://url.com
 */

namespace Spool\Config;

/**
 * 通用ENV处理类
 * 
 * Class Env 用于处理本地.env文件
 * 
 * PHP version 7.2
 * 
 * @category Spool
 * @package  Confg
 * @author   yydick Chen <yydick@sohu.com>
 * @license  https://spdx.org/licenses/Apache-2.0.html Apache-2.0
 * @link     http://url.com
 */
class Env
{
    //.env文件所在的目录
    protected static $rootDir = '';
    //.env文件的缓存
    protected static $env = [];
    //.env文件环境选择
    protected static $envSelect = '';
    //判断.env文件是否有变更, 以便更新
    protected static $envMd5 = '';
    //判断.env指定环境文件是否有变更, 以便更新
    protected static $envSelectMd5 = '';
    //单例模式
    protected static $instance = null;
    /**
     * 私有化构建函数,禁止外部新建类
     */
    private function __construct()
    {
    }
    /**
     * 单例模式生成器
     * 
     * @return Env
     */
    public static function getInstance(): Env
    {
        if (!self::$instance) {
            self::$instance = new Env();
        }
        return self::$instance;
    }
    /**
     * 默认方法，当做函数来使用
     * 
     * @param string $key    要获取的配置的键名
     * @param string $define 如果未定义则默认该值
     * 
     * @return string
     */
    public function __invoke(string $key, string $define = ''): string
    {
        return $this->get($key, $define);
    }
    /**
     * 获取键名对应的值
     * 
     * @param string $key    要获取的配置的键名
     * @param string $define 如果未定义则默认该值
     * 
     * @return string
     */
    public function get(string $key, string $define = ''): string
    {
        $this->checkEnvFile();
        return isset(self::$env[$key]) ? self::$env[$key] : $define;
    }
    /**
     * 检测.env文件是否存在,且是否有更新
     * 
     * @return void
     */
    protected function checkEnvFile(): void
    {
        $env = $envSelectArr = [];
        $select = $envSelect = $filename = $envMd5 = $envSelectMd5 = '';
        if (!self::$env) {
            self::$env = [];
        }
        $filename = self::$rootDir . '.env';
        if (is_file($filename)) {
            $envMd5 = md5_file($filename);
            if ($envMd5 != self::$envMd5) {
                $env = parse_ini_file($filename, true);
            }
        }
        if (self::$envSelect || isset($env['APP_ENV']) || isset(self::$env['APP_ENV'])) {
            $select = $env['APP_ENV'] ?? self::$env['APP_ENV'];
            $envSelect = self::$envSelect ?: self::$rootDir . '.env' . '.' . $select;
        }
        if (is_file($envSelect)) {
            $envSelectMd5 = md5_file($envSelect);
            if ($envSelectMd5 != self::$envSelectMd5) {
                $envSelectArr = parse_ini_file($envSelect, true);
            }
        }
        $env = array_merge($env, $envSelectArr);
        if ($env) {
            foreach ($env as $key => $value) {
                self::$env[$key] = is_array($value) ? implode('.', $value) : $value;
            }
            self::$envSelect = $envSelect;
            self::$envMd5 = $envMd5;
            self::$envSelectMd5 = $envSelectMd5;
        }
    }
    /**
     * 设置.env文件的路径
     * 
     * @param string $rootDir 要设置的文件路径
     * 
     * @return string|null
     */
    public function setRootDir(string $rootDir): ?string
    {
        $rootDir .= substr($rootDir, -1) == '/' ? '' : '/';
        if (self::$rootDir) {
            $tmp = self::$rootDir;
            self::$rootDir = $rootDir;
            return $tmp;
        }
        self::$rootDir = $rootDir;
        return null;
    }
    /**
     * 获取已设置的.env文件的路径，未设置返回空字符
     * 
     * @return string
     */
    public function getRootDir(): string
    {
        return self::$rootDir ?: '';
    }
}
