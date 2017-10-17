<?php
/**
 * 网站设置
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

class performControl extends SystemControl
{

    public function __construct()
    {
        parent::__construct();
        Language::read('setting');
    }

    /**
     * 性能优化
     */
    public function performOp()
    {
        if ($_GET['type'] == 'clear') {
            $lang = Language::getLangContent();
            $cache = Cache::getInstance(C('cache.type'));
            $cache->clear();
            showMessage($lang['nc_common_op_succ']);
        }
        Tpl::showpage('setting.perform_opt');
    }
}
