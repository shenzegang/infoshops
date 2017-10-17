<?php
/**
 * cms首页
 *
 *
 *
 * @copyright  Copyright (c) 2003-2015 B2B2C Inc. (http://www.infol.com.cn)
 * @license    http://www.infol.com.cn
 * @link       http://www.infol.com.cn
 * @since      File available since Release v1.1
 */
defined('CorShop') or exit('Access Invalid!');

class indexControl extends CMSHomeControl
{

    public function __construct()
    {
        parent::__construct();
        Tpl::output('index_sign', 'index');
    }

    public function indexOp()
    {
        Tpl::showpage('index');
    }
}
