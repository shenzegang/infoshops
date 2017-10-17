<?php
/**
 * 支付宝通知地址
 *
 * 
 * @copyright  Copyright (c) 2003-2015 B2B2C Inc. (http://www.infol.com.cn)
 * @license    http://www.infol.com.cn
 * @link       http://www.infol.com.cn
 * @since      File available since Release v1.1
 */
$_GET['act'] = 'payment';
$_GET['op'] = 'notify';
$_GET['payment_code'] = 'alipay';
require_once (dirname(__FILE__) . '/../../../index.php');
?>