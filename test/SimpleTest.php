<?php

/**
 * 简单测试
 * 
 * Class SimpleTest 简单测试类
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

namespace Spool\Test;

use PHPUnit\Framework\TestCase;
use Spool\Config\Env;

/**
 * 简单测试
 * 
 * Class SimpleTest 简单测试类
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
class SimpleTest extends TestCase
{
    /**
     * 简单测试
     * 
     * @return void
     */
    public function testSimple()
    {
        $env = Env::getInstance();
        $rootDir = dirname(__DIR__);
        $env->setRootDir(__DIR__);
        var_dump($env('APP_ENV'), $env('APP', 'app'));
    }
}
