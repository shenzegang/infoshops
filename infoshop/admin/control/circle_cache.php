<?php
/**
 * 圈子话题管理
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

class circle_cacheControl extends SystemControl
{

    public function __construct()
    {
        parent::__construct();
    }

    public function indexOp()
    {
        H('circle_level', true);
        showMessage(L('nc_common_op_succ'), 'index.php?act=circle_setting');
    }
}