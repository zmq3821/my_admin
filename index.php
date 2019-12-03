<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]

// 定义应用目录
define('APP_PATH', __DIR__ . '/application/');

// 自定义配置文件目录
//define('CONF_PATH', __DIR__.'config/');

// 规定当前版本不得低于5.5.0
if  (!version_compare(PHP_VERSION, '5.5.0', 'ge')) {
    die('php版本不得低于5.5.0，当前版本：'.PHP_VERSION);
}

// 加载框架引导文件
require __DIR__ . '/think_core/start.php';



