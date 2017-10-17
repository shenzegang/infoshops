<?php
/**
 * 手机接口初始化文件
 *
 *
 * @copyright  Copyright (c) 2003-2015 B2B2C Inc. (http://www.infol.com.cn)
 * @license    http://www.infol.com.cn
 * @link       http://www.infol.com.cn
 * @since      File available since Release v1.1
 */
define('APP_ID', 'mobile');
define('IGNORE_EXCEPTION', true);
define('BASE_PATH', str_replace('\\', '/', dirname(__FILE__)));
if (! @include (dirname(dirname(__FILE__)) . '/global.php'))
    exit('global.php isn\'t exists!');
if (! @include (BASE_CORE_PATH . '/shopnc.php'))
    exit('shopnc.php isn\'t exists!');

if (! @include (BASE_PATH . '/config/config.ini.php')) {
    exit('config.ini.php isn\'t exists!');
}

// 框架扩展
require (BASE_PATH . '/framework/function/function.php');
if (! @include (BASE_PATH . '/control/control.php'))
    exit('control.php isn\'t exists!');

Base::run();
