<?php
defined('CorShop') or exit('Access Invalid!');
class member_accountControl extends BaseMemberControl{
	
	public function __construct(){
		parent::__construct();
	}
	
	/*
	 * 手机短信验证
	 */
	public function mobile_smsOp(){
		if(chksubmit()){
			$result = chksubmit(false, 1, 'num');
			if($result !== false){
				if($result === -12){
					showDialog('验证码错误');
				}
				$model_member = Model('member');
				$code = $model_member -> getOneSMS($_POST['member_tel']);
				if($code[0]['code'] != $_POST['mobile'] || time() - strtotime($code[0]['send_date']) > 3 * 60){
					showDialog('短信验证码无效，或者已经失效');
				}else{
					showDialog('短信验证成功,可以设置新的密码', 'index.php?act=member&op=security', 'succ', 'CUR_DIALOG.close();');
				}
			}
			exit();
		}
		Tpl::output('member_tel', $_SESSION['member_tel']);
		Tpl::showpage('mobile_sms', 'null_layout');
	}
}
