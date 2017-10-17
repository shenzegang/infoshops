<?php
/**
 * 投诉
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

class store_workbenchControl extends BaseSellerControl
{
    public function __construct()
    {
        parent::__construct();
        Language::read('member_layout,member_complain');
    }

    /**
     * 2015-8-31 tjz增加 客服工作台
     */
    public function  call_workbenchOp(){
        Tpl::showpage("complain.workbench");
    }

}
