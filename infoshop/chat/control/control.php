<?php
/**
 * 前台control父类
 *
 *
 * @copyright  Copyright (c) 2003-2015 B2B2C Inc. (http://www.infol.com.cn)
 * @license    http://www.infol.com.cn
 * @link       http://www.infol.com.cn
 * @since      File available since Release v1.1
 */
defined('CorShop') or exit('Access Invalid!');

/**
 * ******************************** 前台control父类 *********************************************
 */
class BaseControl
{

    public function __construct()
    {
        Language::read('common');
    }
}
