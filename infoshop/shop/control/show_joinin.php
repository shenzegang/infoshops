<?php
/**
 * 商家入住
 *
 * 
 *
 *
 * @copyright  Copyright (c) 2003-2015 B2B2C Inc. (http://www.infol.com.cn)
 * @license    http://www.infol.com.cn
 * @link       http://www.infol.com.cn
 * @since      File available since Release v1.1
 */
defined('CorShop') or exit('Access Invalid!');

class show_joininControl extends BaseHomeControl
{

    private $joinin_detail = NULL;

    public function __construct()
    {
        parent::__construct();
        Tpl::setLayout('store_joinin_layout');
    }

    public function indexOp()
    {
        Tpl::showpage('show_joinin');
    }
}
