<?php
/**
 * 圈子板块初始化文件
 *
 * 圈子板块初始化文件，引用框架初始化文件
 *
 *
 * @copyright  Copyright (c) 2003-2015 B2B2C Inc. (http://www.infol.com.cn)
 * @license    http://www.infol.com.cn
 * @link       http://www.infol.com.cn
 * @since      File available since Release v1.1
 */
define('APP_ID', 'circle');
define('BASE_PATH', str_replace('\\', '/', dirname(__FILE__)));
if (! @include (dirname(dirname(__FILE__)) . '/global.php'))
    exit('global.php isn\'t exists!');
if (! @include (BASE_CORE_PATH . '/shopnc.php'))
    exit('shopnc.php isn\'t exists!');

if (! @include (BASE_PATH . '/config/config.ini.php')) {
    @header("Location: install/index.php");
    die();
}

define('APP_SITE_URL', CIRCLE_SITE_URL);
define('TPL_NAME', TPL_CIRCLE_NAME);
define('CIRCLE_TEMPLATES_URL', CIRCLE_SITE_URL . '/templates/' . TPL_NAME);
define('CIRCLE_RESOURCE_SITE_URL', CIRCLE_SITE_URL . '/resource');
require (BASE_PATH . '/framework/function/function.php');
require (BASE_PATH . '/control/control.php');
Base::run();