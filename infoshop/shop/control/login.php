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

class loginControl extends BaseHomeControl
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 登录操作
     */
    public function indexOp()
    {
        Language::read("home_login_index");
        $lang = Language::getLangContent();
        $model_member = Model('member');
        // 检查登录状态
        $model_member->checkloginMember();
        
        $result = chksubmit(true, C('captcha_status_login'), 'num');
        if ($result !== false) {
            if ($result === -11) {
                showDialog($lang['login_index_login_illegal']);
            } elseif ($result === - 12) {
                showDialog($lang['login_index_wrong_checkcode']);
            }
            if (processClass::islock('login')) {
                showDialog($lang['nc_common_op_repeat'], SHOP_SITE_URL);
            }
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array(
                    "input" => $_POST["user_name"],
                    "require" => "true",
                    "message" => $lang['login_index_username_isnull']
                ),
                array(
                    "input" => $_POST["password"],
                    "require" => "true",
                    "message" => $lang['login_index_password_isnull']
                )
            );
            $error = $obj_validate->validate();
            if ($error != '') {
                showValidateError($error);
                exit();
            }
            $array = array();
            $array['member_name'] = $_POST['user_name'];
            $array['member_passwd'] = md5($_POST['password']);
            $member_info = $model_member->infoMember($array);
            if (is_array($member_info) and ! empty($member_info)) {
                if (! $member_info['member_state']) {
                    showDialog($lang['login_index_account_stop']);
                }
            } else {
                processClass::addprocess('login');
                //showDialog($lang['login_index_login_fail'])；
                //提示重新登录
                showDialog($lang['login_index_login_again']);
            }
            $model_member->createSession($member_info);
            processClass::clear('login');
            // cookie中的cart存入数据库
            $this->mergecart($member_info);
            // 添加会员哈金豆
            if (C('points_isuse')) {
                // 一天内只有第一次登录赠送哈金豆
                if (trim(@date('Y-m-d', $member_info['member_login_time'])) != trim(date('Y-m-d'))) {
                    $points_model = Model('points');
                    $points_model->savePointsLog('login', array(
                        'pl_memberid' => $member_info['member_id'],
                        'pl_membername' => $member_info['member_name']
                    ), true);
                }
            }
            if(preg_match('/act=seller_login/', $_POST['ref_url']) && !$_SESSION['seller_id']){
            	$_POST['ref_url'] = C('shop_site_url') . '/index.php?act=show_joinin'; 
            }
            
            //检查登陆用户是否存在于内网支付系统，如果不存在则添加
            require_once BASE_CORE_PATH . DS . 'framework' . DS . 'function' . DS . 'payapi.php';
            $resp = array();
            $req = array('userId' => $_SESSION['member_id']);
            $validResp = trade(C('alipay.checkUser_url'), $req, $resp);
            
            if(empty($resp['userInfo']) || count($resp['userInfo']) == 0){
            	$req = array('userId' => $member_info['member_id'], 'userName' => $member_info['member_name'], 'userMobile' => $member_info['member_tel'], 'userCode' => $member_info['member_id']);
            	$validResp = trade(C('alipay.add_user_url'), $req, $resp);
            	
            }
            
            showDialog($lang['login_index_login_success'], $_POST['ref_url'], 'succ', $extrajs);
        } else {
            
            // 登录表单页面
            $_pic = @unserialize(C('login_pic'));
            if ($_pic[0] != '') {
                Tpl::output('lpic', UPLOAD_SITE_URL . '/' . ATTACH_LOGIN . '/' . $_pic[array_rand($_pic)]);
            } else {
                Tpl::output('lpic', UPLOAD_SITE_URL . '/' . ATTACH_LOGIN . '/' . rand(1, 4) . '.jpg');
            }
            
            if (empty($_GET['ref_url'])) {
                $ref_url = getReferer();
                if (! preg_match('/act=login&op=logout/', $ref_url)) {
                    $_GET['ref_url'] = $ref_url;
                }
            }
            Tpl::output('html_title', C('site_name') . ' - ' . $lang['login_index_login']);
            if ($_GET['inajax'] == 1) {
                Tpl::showpage('login_inajax', 'null_layout');
            } else {
                Tpl::showpage('login');
            }
        }
    }

    /**
     * 退出操作
     *
     * @param int $id
     *            记录ID
     * @return array $rs_row 返回数组形式的查询结果
     */
    public function logoutOp()
    {
        Language::read("home_login_index");
        $lang = Language::getLangContent();
        session_unset();
        session_destroy();
        setNcCookie('goodsnum', '', - 3600);
        if (empty($_GET['ref_url'])){
            $ref_url = getReferer();
        } else {
            $ref_url = $_GET['ref_url'];
        }
        showMessage($lang['login_logout_success'], 'index.php?act=login&ref_url=' . urlencode($ref_url), 'html', 'succ', 1, 2000);
    }

    /**
     * 会员注册页面
     *
     * @param            
     *
     * @return
     *
     */
    public function registerOp()
    {
        Language::read("home_login_register");
        $lang = Language::getLangContent();
        $model_member = Model('member');
        $model_member->checkloginMember();
        Tpl::output('html_title', C('site_name') . ' - ' . $lang['login_register_join_us']);
        Tpl::showpage('register');
    }
    /**
     * 发送短信
     * @param $sendTo
     * @param $content
     * @param string $from
     */
    public function sendSMSOp(){
        $model_sms_log = Model('member');
        $insert_array = array();
        $insert_array['mobile_phone'] = $_GET['mobile_phone'];
        $insert_array['code'] = trim($_GET['code']);
        $insert_array['sender'] = 'SYS';
        $insert_array['send_date'] = date("Y-m-d H:i:s");
        $result = $model_sms_log->add($insert_array);
        if(!$result)
        {
            return false;
        }
        if(is_numeric($_GET['mobile_phone'])){
            $sendTo = $_GET['mobile_phone'];
            $content = '短信验证码'.$insert_array['code'];
            Log::record($sendTo.':'.$content);
        }else{
            $member_id = 1;
            $model_cron = Model('cron');
            $condition = array();
            $condition['type'] = 2;
            $condition['exeid'] = $member_id;
            $condition['code'] = 'sms_toseller_new_vip';
            $cron_data = array(
                'exetime' => TIMESTAMP,
                'exeid' => $member_id,
                'type' => 2,
                'code' => 'sms_toseller_new_vip',
                'content' => array(
                    array(
                        'site_name' => $GLOBALS['setting_config']['site_name'],
                        'seller_name' => $_GET['code']
                    ),
                    false
                )
            );
            //
            $this->addcron($cron_data, true);
            $cron_info = $model_cron->getCronInfo($condition);
            if (empty($cron_info))
                return;
            $content = unserialize($cron_info['content']);
            if (! $content[1])
                $content[1] = false;
            $result = $this->send_notice_e($_GET['mobile_phone'], $cron_info['code'], $content[0], $content[1]);
            //判断邮件是否发送成
            if($result == true){
                $model_cron->delCron($condition);
                showDialog('111111111', SHOP_SITE_URL .'/index.php', 'succ');
            }else{
                $model_cron->delCron($condition);
                //showDialog($lang['login_password_email_error'], SHOP_SITE_URL .'/index.php', 'error');
            }
        }

    }
    /**
     * 会员添加操作
     *
     * @param            
     *
     * @return
     *
     */
    public function usersaveOp()
    {
        // 重复注册验证
        //if (processClass::islock('reg')) {
            //showDialog(Language::get('nc_common_op_repeat'), 'index.php');
        //}
        Language::read("home_login_register");
        $lang = Language::getLangContent();
        $model_member = Model('member');
        $model_member->checkloginMember();

        $result = chksubmit(true, C('captcha_status_login'), 'num');
        if ($result !== false) {
            if ($result === - 11) {
                showDialog($lang['invalid_request']);
            } elseif ($result === - 12) {
                showDialog($lang['login_usersave_wrong_code']);
            }
        }
        $register_info = array();
        $register_info['username'] = $_POST['user_name'];
        $register_info['password'] = $_POST['password'];
        $register_info['password_confirm'] = $_POST['password_confirm'];
        $register_info['email'] = $_GET['email'];
        $register_info['member_tel'] = $_GET['mobile'];
        $mobile_phone = $_GET['mobile'];
        if(($_GET['cur'] != 1 || $_GET['cur'] == 'mob') && $_GET['cur'] != 'email') {
            Tpl::showpage('register');
        }
        else if($_GET['cur'] == 'email' && $_GET['cur'] != 1) {
            Tpl::showpage('register_email');
        }
        else{
            $member_info = $model_member->register($register_info);
            if (!isset($member_info['error'])) {
                //注册的用户同步添加到内网支付系统
                require_once BASE_CORE_PATH . DS . 'framework' . DS . 'function' . DS . 'payapi.php';
                $resp = array();
                $req = array('userId' => $member_info['member_id'], 'userName' => $member_info['member_name'], 'userMobile' => $member_info['member_tel'], 'userCode' => $member_info['member_id']);
                $validResp = trade(C('alipay.add_user_url'), $req, $resp);

                $model_member->createSession($member_info);
                processClass::addprocess('reg');
                $this->mergecart();
                $_POST['ref_url'] = (strstr($_POST['ref_url'], 'logout') === false && ! empty($_POST['ref_url']) ? $_POST['ref_url'] : 'index.php?act=home&op=member');
                showDialog(str_replace('site_name', C('site_name'), $lang['login_usersave_regist_success_ajax']), $_POST['ref_url'], 'succ', $synstr, 3);
            } else {
                showDialog($member_info['error']);
            }
        }
    }

    /**
     * 手机验证码是否正确
     */
    public function check_codeOp()
    {
        $mobile_phone = $_GET['mobile_phone'];
        $model_member = Model('member');
        $code = $model_member->getOneSMS($mobile_phone);
        if ($code[0]['code'] == $_GET['code'] && time() - strtotime($code[0]['send_date']) < C('sms_invalid')) {
            echo 1;
        } else {
            echo 0;
        }
    }


    /**
     * 会员名称检测
     *
     * @param            
     *
     * @return
     *
     */
    public function check_memberOp()
    {
        /**
         * 实例化模型
         */
        $model_member = Model('member');
        
        $check_member_name = $model_member->infoMember(array(
            'member_name' => trim($_GET['user_name'])
        ));
        if (is_array($check_member_name) and count($check_member_name) > 0) {
            echo 'false';
        } else {
            echo 'true';
        }
    }

    /**
     * 登录之后,把登录前购物车内的商品加到购物车表
     */
    private function mergecart($member_info = array())
    {
        if (! $member_info['member_id'])
            return;
        $model_cart = Model('cart');
        $save_type = C('cache.type') != 'file' ? 'cache' : 'cookie';
        $cart_new_list = $model_cart->listCart($save_type);
        if (empty($cart_new_list))
            return;
            // 取出当前DB购物车已有信息
        $cart_cur_list = $model_cart->listCart('db', array(
            'buyer_id' => $_SESSION['member_id']
        ));
        // 数据库购物车已经有的商品，不再添加
        if (! empty($cart_cur_list) && is_array($cart_cur_list) && is_array($cart_new_list)) {
            foreach ($cart_new_list as $k => $v) {
                if (! is_numeric($k) || in_array($k, array_keys($cart_cur_list))) {
                    unset($cart_new_list[$k]);
                }
            }
        }
        // 查询在购物车中,不是店铺自己的商品，未禁售，上架，有库存的商品,并加入DB购物车
        $mode_goods = Model('goods');
        $condition = array();
        if (! empty($_SESSION['store_id'])) {
            $condition['store_id'] = array(
                'neq',
                $_SESSION['store_id']
            );
        }
        $condition['goods_id'] = array(
            'in',
            array_keys($cart_new_list)
        );
        $goods_list = Model('goods')->getGoodsOnlineList($condition);
        if (! empty($goods_list)) {
            foreach ($goods_list as $goods_info) {
                $goods_info['buyer_id'] = $member_info['member_id'];
                $model_cart->addCart($goods_info, 'db', $cart_new_list[$goods_info['goods_id']]['goods_num']);
            }
        }
        // 最后清空登录前购物车内容
        $model_cart->clearCart($save_type);
    }

    /**
     * 电子邮箱检测
     *
     * @param            
     *
     * @return
     *
     */
    public function check_emailOp()
    {
        $model_member = Model('member');
        $check_member_email = $model_member->infoMember(array(
            'member_email' => trim($_GET['email'])
        ));
        if (is_array($check_member_email) and count($check_member_email) > 0) {
            echo 'false';
        } else {
            echo 'true';
        }
    }
    /**
     * 手机号码验证
     */
    public function check_mobileOp()
    {
    	$model_member = Model('member');
    	$check_member_tel = $model_member->infoMember(array(
    			'member_tel' => trim($_GET['mobile'])
    	));
    	if (is_array($check_member_tel) and count($check_member_tel) > 0) {
    		echo 'false';
    	} else {
    		echo 'true';
    	}
    }
    
    
    /**
     * 校验身份证号码的有效性
     * 
     */
    public function check_idcardOp()
    {
    	$model_member = Model('member');
    	$check_member_idcard = $model_member->infoMember(array(
    			'idcard' => trim($_GET['idcard'])
    	));
    	if (is_array($check_member_idcard) && count($check_member_idcard) > 0) {
    		echo 'false';
    	} else {
    		echo 'true';
    	}
    }

    public function check_idcartd_editOp(){
        $model_member = Model('member');
        $condition['idcard'] = trim($_GET['idcard']);
        $condition['no_member_id'] = intval($_GET['member_id']);
        $list = $model_member->infoMember($condition);
        if (empty($list)) {
            echo 'true';
            exit();
        } else {
            echo 'false';
            exit();
        }
    }
    /**
     * 手机号码检测
     *
     * @param            
     *
     * @return
     *
     */
    public function check_telOp()
    {
        /**
         * 实例化模型
         */
        $model_member = Model('member');
        
        $check_member_name = $model_member->infoMember(array(
            'member_tel' => trim($_GET['member_tel'])
        ));
        if($_GET['member_tel'] == $_GET['m_tel']) {
            echo 'true';
        }
        else{
            if (is_array($check_member_name) and count($check_member_name) > 0) {
                echo 'false';
            } else {
                echo 'true';
            }
        }
    }

    /**
     * 忘记密码页面
     */
    public function forget_passwordOp()
    {
        /**
         * 读取语言包
         */
        Language::read('home_login_register');
        $_pic = @unserialize(C('login_pic'));
        if ($_pic[0] != '') {
            Tpl::output('lpic', UPLOAD_SITE_URL . '/' . ATTACH_LOGIN . '/' . $_pic[array_rand($_pic)]);
        } else {
            Tpl::output('lpic', UPLOAD_SITE_URL . '/' . ATTACH_LOGIN . '/' . rand(1, 4) . '.jpg');
        }
        Tpl::output('html_title', C('site_name') . ' - ' . Language::get('login_index_find_password'));
        Tpl::showpage('find_password');
    }

    /**
     * 找回密码的发邮件处理
     */
    public function find_passwordOp()
    {
        Language::read('home_login_register');
        $lang = Language::getLangContent();
        
        $result = chksubmit(true, true, 'num');
        if (! $result) {
            showDialog('非法提交');
        } elseif ($result === - 11) {
            showDialog('非法提交');
        } elseif ($result === - 12) {
            showDialog('验证码错误');
        }
        
        if (empty($_POST['username'])) {
            showDialog($lang['login_password_input_username']);
        }
        
        if (processClass::islock('forget')) {
            showDialog($lang['nc_common_op_repeat'], 'reload');
        }
        
        $member_model = Model('member');
        $member = $member_model->infoMember(array(
            'member_name' => $_POST['username']
        ));
        if (empty($member) or ! is_array($member)) {
            processClass::addprocess('forget');
            showDialog($lang['login_password_username_not_exists'], 'reload');
        }
        
        if (empty($_POST['email'])) {
            showDialog($lang['login_password_input_email'], 'reload');
        }
        
        if (strtoupper($_POST['email']) != strtoupper($member['member_email'])) {
            processClass::addprocess('forget');
            showDialog($lang['login_password_email_not_exists'], 'reload');
        }
        processClass::clear('forget');
        //根据登录名获取member_id
        $member = $member_model->getMemberInfo(array('member_name' => $_POST['username']));
        //发送邮件
        showDialog($lang['login_password_email_sending'], SHOP_SITE_URL . "/index.php?act=login&op=send_forget_password_email&member_id=".$member["member_id"], 'succ');

    }

    /**
     * 异步发送邮件
     */
    public function send_forget_password_emailOp()
    {
        Language::read('home_login_register');
        $lang = Language::getLangContent();
        $member_model = Model('member');

        $member_id = $_GET['member_id'];
        $model_cron = Model('cron');
        $condition = array();
        $condition['type'] = 2;
        $condition['exeid'] = $member_id;

        $condition['code'] = 'email_touser_find_password';
        //生成新密码
        $new_password = random(15);
        $cron_data = array(
            'exetime' => TIMESTAMP,
            'exeid' => $member_id,
            'type' => 2,
            'code' => 'email_touser_find_password',
            'content' => array(
                array(
                    'site_name' => $GLOBALS['setting_config']['site_name'],
                    'site_url' => SHOP_SITE_URL,
                    'user_name' => $_POST['username'],
                    'new_password' => $new_password
                ),
                false
            )
        );
        //
        $this->addcron($cron_data, true);
        $cron_info = $model_cron->getCronInfo($condition);
        if (empty($cron_info))
            return;
        $content = unserialize($cron_info['content']);
        if (! $content[1])
            $content[1] = false;
        $result = $this->send_notice($cron_info['exeid'], $cron_info['code'], $content[0], $content[1]);
        //判断邮件是否发送成
        if($result == true){
            //更新密码
            if (! ($member_model->updateMember(array(
                //新密码
                'member_passwd' => md5($new_password)
            ),$member_id))) {
                showDialog($lang['login_password_email_fail'], 'reload');
            }
            $model_cron->delCron($condition);
            showDialog($lang['login_password_email_success'], SHOP_SITE_URL .'/index.php', 'succ');
        }else{
            $model_cron->delCron($condition);
            showDialog($lang['login_password_email_error'], SHOP_SITE_URL .'/index.php', 'error');
        }
    }

    /**
     * 异步发送邮件
     */
    public function send_emailOp()
    {
        // Model('member')->checkloginMember();
        $member_id = $_GET['member_id'];
        $model_cron = Model('cron');
        $condition = array();
        $condition['type'] = 2;
        //$condition['exeid'] = $_SESSION['member_id'];
        $condition['exeid'] = $member_id;

        $condition['code'] = 'email_touser_find_password';
        $cron_info = $model_cron->getCronInfo($condition);
        if (empty($cron_info))
            return;

        $content = unserialize($cron_info['content']);
        if (! $content[1])
            $content[1] = false;

        $this->send_notice($cron_info['exeid'], $cron_info['code'], $content[0], $content[1]);
        $model_cron->delCron($condition);
        showDialog('请注意查收邮件获取新密码', 'index.php?act=login&op=index', 'succ');
    }
}
