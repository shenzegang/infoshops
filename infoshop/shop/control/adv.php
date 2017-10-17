<?php
/**
 * 广告展示
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

class advControl
{

    /**
     * 广告展示
     */
    public function advshowOp()
    {
        import('function.adv');
        $ap_id = intval($_GET['ap_id']);
        echo advshow($ap_id, 'js');
    }
}