<?php
/**
 * 会员中心——账户概览
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

class memberControl extends BaseMemberControl
{

    /**
     * 会员地址
     *
     * @param            
     *
     * @return
     *
     */
    public function addressOp()
    {
        /**
         * 读取语言包
         */
        Language::read('member_member_index');
        $lang = Language::getLangContent();
        /**
         * 实例化模型
         */
        $address_class = Model('address');
        /**
         * 判断页面类型
         */
        if (! empty($_GET['type'])) {
            /**
             * 新增/编辑地址页面
             */
            if (intval($_GET['id']) > 0) {
                /**
                 * 得到地址信息
                 */
                $address_info = $address_class->getOneAddress(intval($_GET['id']));
                if ($address_info['member_id'] != $_SESSION['member_id']) {
                    showMessage($lang['member_address_wrong_argument'], 'index.php?act=member&op=address', 'html', 'error');
                }
                /**
                 * 输出地址信息
                 */
                Tpl::output('address_info', $address_info);
            }
            /**
             * 增加/修改页面输出
             */
            Tpl::output('type', $_GET['type']);
            Tpl::showpage('address_form', 'null_layout');
            exit();
        }
        /**
         * 判断操作类型
         */
        if (chksubmit()) {
            /**
             * 验证表单信息
             */
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array(
                    "input" => $_POST["true_name"],
                    "require" => "true",
                    "message" => $lang['member_address_receiver_null']
                ),
                array(
                    "input" => $_POST["area_id"],
                    "require" => "true",
                    "validator" => "Number",
                    "message" => $lang['member_address_wrong_area']
                ),
                array(
                    "input" => $_POST["city_id"],
                    "require" => "true",
                    "validator" => "Number",
                    "message" => $lang['member_address_wrong_area']
                ),
                array(
                    "input" => $_POST["area_info"],
                    "require" => "true",
                    "message" => $lang['member_address_area_null']
                ),
                array(
                    "input" => $_POST["address"],
                    "require" => "true",
                    "message" => $lang['member_address_address_null']
                ),
                array(
                    "input" => $_POST['tel_phone'] . $_POST['mob_phone'],
                    'require' => 'true',
                    'message' => $lang['member_address_phone_and_mobile']
                )
            );
            $error = $obj_validate->validate();
            if ($error != '') {
                showValidateError($error);
            }
            $data = array();
            $data['member_id'] = $_SESSION['member_id'];
            $data['true_name'] = $_POST['true_name'];
            $data['area_id'] = intval($_POST['area_id']);
            $data['city_id'] = intval($_POST['city_id']);
            $data['area_info'] = $_POST['area_info'];
            $data['address'] = $_POST['address'];
            $data['tel_phone'] = $_POST['tel_phone'];
            $data['mob_phone'] = $_POST['mob_phone'];
            
            if (intval($_POST['id']) > 0) {
                $rs = $address_class->editAddress($data, array(
                    'address_id' => $_POST['id']
                ));
                if (! $rs) {
                    showDialog($lang['member_address_modify_fail'], '', 'error');
                }
            } else {
                $rs = $address_class->addAddress($data);
                if (! $rs) {
                    showDialog($lang['member_address_add_fail'], '', 'error');
                }
            }
            showDialog($lang['nc_common_op_succ'], 'reload', 'succ', 'CUR_DIALOG.close()');
        }
        $del_id = isset($_GET['id']) ? intval(trim($_GET['id'])) : 0;
        if ($del_id > 0) {
            $rs = $address_class->delAddress(array(
                'address_id' => $del_id,
                'member_id' => $_SESSION['member_id']
            ));
            if ($rs) {
                showDialog(Language::get('member_address_del_succ'), 'index.php?act=member&op=address', 'succ');
            } else {
                showDialog(Language::get('member_address_del_fail'), '', 'error');
            }
        }
        $address_list = $address_class->getAddressList(array(
            'member_id' => $_SESSION['member_id']
        ));
        /**
         * 页面输出
         */
        self::profile_menu('address', 'address');
        Tpl::output('menu_sign', 'address');
        Tpl::output('address_list', $address_list);
        Tpl::output('menu_sign_url', 'index.php?act=member&op=address');
        Tpl::output('menu_sign1', 'address_list');
        Tpl::setLayout('member_pub_layout');
        Tpl::showpage('address_index');
    }
    
    public function securityOp(){
    	Language::read('member_home_member');
    	Tpl::setLayout('member_pub_layout');
    	$mem_info = Model('member') -> infoMember(array('member_id' => $_SESSION['member_id']));
    	$user_info = Model('UserService') -> findUserinfo($_SESSION['member_id']);
    	if(!empty($mem_info['member_paypasswd']) && $user_info['payPassword'] == $mem_info['member_paypasswd']) $auth = true;
    	Tpl::output('menu_sign', 'security');
    	Tpl::output('auth', $auth);
    	Tpl::showpage('member_amount_security');
    }
    
    /**
     * 判断支付密码是否和登陆密码一致
     */
    public function isEqualPasswdOp(){
    	$mem_info = Model('member') -> infoMember(array('member_id' => $_SESSION['member_id']));
    	$paypasswd = md5(trim($_GET['paypasswd']));
    	if(!empty($paypasswd) && $paypasswd != $mem_info['member_passwd']){
    		echo 'true';
    	}else{
    		echo 'false';
    	}
    }
    
    /**
     * 验证支付密码
     * 
     */
    public function isValidPaypasswdOp(){
    	$mem_info = Model('UserService') -> findUserinfo($_SESSION['member_id']);
    	$_GET['paypasswd'] = $_GET['paypasswd'] ? $_GET['paypasswd'] : $_GET['pay_passwd'];
    	$paypasswd = md5(trim($_GET['paypasswd']));
    	if(!empty($paypasswd) && $paypasswd == $mem_info['payPassword']){
    		echo 'true';
    	}else{
    		echo 'false';
    	}
    }
    
    public function paypasswdOp(){
    	Language::read('member_home_member');
    	if(!empty($_GET['type']) || !empty($_GET['status'])){
    		Tpl::output('type', $_GET['type']);
    		Tpl::output('status', $_GET['status']);
    		Tpl::showpage('paypasswd_form', 'null_layout');
    		exit();
    	}
    	
    	/**
    	 * 判断操作类型
    	 */
    	if (chksubmit()) {
    		$lang = Language::getLangContent();
    		/**
    		 * 验证表单信息
    		*/
    		$obj_validate = new Validate();
    		$obj_validate -> validateparam = array(
    				array(
    						"input" => $_POST["paypasswd"],
    						"require" => "true",
    						"message" => '支付密码不能为空'
    				),
    				array(
    						"input" => $_POST["paypasswd_again"],
    						"require" => "true",
    						"validator" => "Compare",
    						"operator" => "==",
    						"to" => $_POST["paypasswd"],
    						"message" => '支付密码与再次输入密码不一致'
    				)
    		);
    		$error = $obj_validate -> validate();
    		if ($error != '') showValidateError($error);
    		
    		$mem_info = Model('member') -> infoMember(array('member_id' => $_SESSION['member_id']));
    		
    		if($mem_info['member_passwd'] == md5($_POST["paypasswd"])){
    			showValidateError('登陆密码和支付密码不能相同，请重新输入');
    		}
    		if(isset($_POST['orig_paypasswd']) && $_POST['orig_paypasswd'] == $_POST["paypasswd"]){
    			showValidateError('原始支付密码和新的支付密码不能一样，请重新输入');
    		}
    		$update = Model('member') -> updateMember(array('member_paypasswd' => md5($_POST["paypasswd"])), $_SESSION['member_id']);
    		if(Model('UserService') -> editPay_passwd($_SESSION['member_id'], md5($_POST["paypasswd"])) === false){
    			showValidateError('支付密码保存失败');
    		}
    		showDialog($lang['home_member_paypasswd_save_succ'], '', 'succ', 'CUR_DIALOG.close()');
    		exit();
    	}
    }
    
    public function loadPaywdOp(){
    	if(chksubmit()){
    		$mem_info = Model('UserService') -> findUserinfo($_SESSION['member_id']);
    		$paypasswd = $_POST['pay_passwd'];
    		$paypasswd = md5(trim($paypasswd));
    		if(!empty($paypasswd) && $paypasswd == $mem_info['payPassword']){
    			showDialog('支付成功', '', 'succ', 'CUR_DIALOG.close();$("#buy_form").append("<input type=\"hidden\" name=\"predeposit\" value=\"1\" />");$("#buy_form").submit();', 2);
    		}else{
    			showDialog('支付密码不正确，支付失败', '', 'error');
    		}
    		exit();
    	}
    	Tpl::showpage('pay_passwd', 'null_layout');
    }

    /**
     * 用户中心右边，小导航
     *
     * @param string $menu_type            
     * @param string $menu_key            
     * @return
     *
     */
    private function profile_menu($menu_type, $menu_key = '')
    {
        /**
         * 读取语言包
         */
        Language::read('member_layout');
        $menu_array = array();
        switch ($menu_type) {
            case 'address':
                $menu_array = array(
                    1 => array(
                        'menu_key' => 'address',
                        'menu_name' => Language::get('nc_member_path_address_list'),
                        'menu_url' => 'index.php?act=member&op=address'
                    )
                );
                break;
            case 'member_order':
                $menu_array = array(
                    1 => array(
                        'menu_key' => 'member_order',
                        'menu_name' => Language::get('nc_member_path_order_list'),
                        'menu_url' => 'index.php?act=member_order'
                    ),
                    2 => array(
                        'menu_key' => 'buyer_refund',
                        'menu_name' => Language::get('nc_member_path_buyer_refund'),
                        'menu_url' => 'index.php?act=member_refund'
                    ),
                    3 => array(
                        'menu_key' => 'buyer_return',
                        'menu_name' => Language::get('nc_member_path_buyer_return'),
                        'menu_url' => 'index.php?act=member_return'
                    )
                );
                break;
        }
        Tpl::output('member_menu', $menu_array);
        Tpl::output('menu_key', $menu_key);
    }
    
    
    
}
