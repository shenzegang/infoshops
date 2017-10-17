<?php
/**
 * 前台登录 退出操作
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

class loginControl extends BaseCircleControl
{

    public function __construct()
    {
        parent::__construct();
        Language::read("login_index");
    }

    /**
     * 登录操作
     */
    public function indexOp()
    {
        $lang = Language::getLangContent();
        $model_member = Model('member');
        // 检查登录状态
        $model_member->checkloginMember();
        $script = "document.getElementsByName('codeimage')[0].src='" . APP_SITE_URL . "/index.php?act=seccode&op=makecode&nchash='+NC_HASH+'&t=' + Math.random();";
        $result = chksubmit(true, true, 'num');
        if ($result !== false) {
            if ($result === - 11) {
                showDialog(L('login_index_login_again'), '', 'error', $script, 2);
            } elseif ($result === - 12) {
                showDialog(L('login_index_wrong_checkcode'), '', 'error', $script, 2);
            }
            if (processClass::islock('login')) {
                showDialog(L('login_index_op_repeat'), APP_SITE_URL);
            }
            $array = array();
            $array['member_name'] = $_POST['user_name'];
            $array['member_passwd'] = md5($_POST['password']);
            $member_info = $model_member->infoMember($array);
            if (is_array($member_info) and ! empty($member_info)) {
                if (! $member_info['member_state']) {
                    showDialog($lang['login_index_account_disabled']);
                }
            } else {
                processClass::addprocess('login');
                showDialog($lang['login_index_login_fail'], '', 'error', $script, 2);
            }
            $model_member->createSession($member_info);
            processClass::clear('login');
            showDialog(L('login_index_login_success'), 'reload', 'succ', '', 2);
        }
        
        if (empty($_GET['ref_url']))
            $_GET['ref_url'] = getReferer();
        Tpl::output('html_title', C('site_name') . ' - ' . $lang['login_index_login']);
        Tpl::output('nchash', getNchash());
        if ($_GET['inajax'] == 1) {
            Tpl::showpage('login_inajax', 'null_layout');
        } else {
            return false;
        }
    }

    public function loginoutOp()
    {
        session_unset();
        session_destroy();
        setNcCookie('goodsnum', '', - 3600);
        showDialog(L('login_logout_success'), '', 'succ', '', 2);
    }
}