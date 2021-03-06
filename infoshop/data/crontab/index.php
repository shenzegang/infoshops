<?php
/**
 * 任务计划执行入口
 *
 * 
 *
 *
 * @copyright  Copyright (c) 2007-2014 B2B2C Inc. (http://www.infol.com.cn)
 * @license    http://www.infol.com.cn
 * @link       http://www.infol.com.cn
 * @since      File available since Release v1.1
 */
define('CorShop', true);

// $_SERVER['argv'][1] = 'xs';
// $_SERVER['argv'][2] = 'create';
//$_SERVER['argv'][1] = 'goods';
//$_SERVER['argv'][2] = 'common';

if (empty($_SERVER['argv'][1]) || empty($_SERVER['argv'][2]))
    exit('parameter error');

require (dirname(__FILE__) . '/../../global.php');
if (! @include (BASE_CORE_PATH . '/shopnc.php'))
    exit('shopnc.php isn\'t exists!');

Base::init();

$file_name = strtolower($_SERVER['argv'][1]);

$method = $_SERVER['argv'][2] . 'Op';

if (! @include (dirname(__FILE__) . '/include/' . $file_name . '.php'))
    exit($file_name . '.php isn\'t exists!');

$class_name = $file_name . 'Control';
$cron = new $class_name();

if (method_exists($cron, $method)) {
    $cron->$method();
} else {
    exit('method ' . $method . ' isn\'t exists');
}