<?php
/**
 * 默认展示页面
 *
 *
 *
 * @copyright  Copyright (c) 2003-2015 B2B2C Inc. (http://www.infol.com.cn)
 * @license    http://www.infol.com.cn
 * @link       http://www.infol.com.cn
 * @since      File available since Release v1.1
 */
defined('CorShop') or exit('Access Invalid!');

class albumControl extends MircroShopControl
{

    public function __construct()
    {
        parent::__construct();
        Tpl::output('index_sign', 'album');
    }
    
    // 首页
    public function indexOp()
    {
        Tpl::showpage('album');
    }
}
