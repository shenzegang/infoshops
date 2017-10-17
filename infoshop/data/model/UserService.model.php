<?php
/**
 * Copyright (C) 2014 - 2104
 * author shenzegang
 * time 2015 - 08 - 24
 */
class UserServiceModel{
	
	public function __construct(){
		require_once BASE_CORE_PATH . DS . 'framework' . DS . 'function' . DS . 'payapi.php';
	}
	
	/**
	 * 获取用户账户信息
	 * @param int $userid default(0)
	 * return array(
	 * 			账户余额	$available_amount int,
	 * 			被冻结的余额	$blocked_Balance int
	 * 		)
	 */
	function getAmount($userid = 0){
		if(!$userid) return NULL;
		$resp = array();
		$available_amount = $blocked_Balance = 0;
		$req = array('userId' => $userid);
		$validResp = trade(C('alipay.checkUser_url'), $req, $resp);
		$userInfo = $resp['userInfo'];
		if(is_object($userInfo)){
			$userInfo = (array)$userInfo;
		}
		if($resp['returnStatus'] == '000' && !empty($userInfo)){
			$available_amount = floatval($userInfo['accountBalance']);
			$blocked_Balance = floatval($userInfo['blockedBalance']);
		}
		return array($available_amount, $blocked_Balance);
	}
	
	/**
	 * 修改用户支付密码
	 * @param 用户ID $user_id int default 0
	 * @param 用户支付密码（MD5加密后）$pay_passwd String default ''
	 * return boolean
	 */
	public function editPay_passwd($user_id = 0, $pay_passwd = ''){
		if(!$user_id || !$pay_passwd){
			return false;
		}
		
		$resp = array();
		$req = array('userId' => $user_id, 'payPassword' => $pay_passwd);
		$validResp = trade(C('pay.password'), $req, $resp);
		if(!$validResp || $resp['returnStatus'] != '000'){
			return false;
		}
		return true;
	}
	
	/*
	 * 查询用户信息
	 * @param 用户ID $user_id int(0)
	 * @return array(
	 * 
	 * 		)
	 */
	public function findUserinfo($user_id = 0){
		$resp = array();
		$req = array('userId' => $user_id);
		$validResp = trade(C('alipay.checkUser_url'), $req, $resp);
		$userinfo = $resp['userInfo'];
		if(is_object($resp['userInfo'])){
			$userinfo = (array)$userinfo;
		}
		return $userinfo;
	}
	
	/**
	 * 买家退款操作
	 * 当订单状态为（待发货，已发货）调用
	 */
	public function refundOrder($order_code = ''){
		$resp = array();
		$req = array('orderCode' => $order_code);
		$validResp = trade(C('pay.returnOrder'), $req, $resp);
		if($validResp && $resp['returnStatus'] == '000'){
			return true;
		}else{
			return false;
		}
	}
	
	/**
	 * 买家退款操作
	 * 当订单状态为（未付款）调用
	 */
	public function changeOrderStatus($order_code = ''){
		$resp = array();
		$req = array('orderCode' => $order_code, 'orderStatus' => -1);
		$validResp = trade(C('pay.changeState_url'), $req, $resp);
		if($validResp && $resp['returnStatus'] == '000'){
			return true;
		}else{
			return false;
		}
	}
	/**
	 * 确认收货
	 */
	public function changeOrderReceive($order_code, $pay_passwd){
		$resp = array();
		$req = array('orderCode' => $order_code, 'payPassword' => $pay_passwd);
		$validResp = trade(C('pay.finshOrder'), $req, $resp);
		if($validResp && $resp['returnStatus'] == '000'){
			return true;
		}else{
			return array('error' => $resp['returnMsg']);
		}
	}
	
	/**
	 * 获取所有福利
	 */
	public function getWelfareList(){
	    $req = $resp = $list = array();
	    $valid = trade(C('welfare.findAll_url'), $req, $resp);
	    
	    if($valid && $resp['returnStatus'] == '000' && !empty($resp['welfares'])){
	        foreach($resp['welfares'] as $key => $val){
	            $list[$val -> welfareId] = (array)$val;
	        }
	    }
	    return $list;
	}
	
	public function getWelfare($welfareId = ''){
	    $list1 = $this -> cacheWelfare();
	    return $list1[$welfareId];
	}
	
	public function getUserWelfare($userId = '', $welfareId = ''){
	    $resp = array();
	    $valid = trade(C('welfare.find_url'), array('userId' => $userId, 'welfareId' => $welfareId), $resp);
	    if($valid && $resp['returnStatus'] == '000'){
	        return (array)$resp['userWelfares'][0];
	    }else{
	        return null;
	    }
	}
	
	public function cacheWelfare(){
	    $list = array();
	    $welfares_file = BASE_DATA_PATH . '/cache/welfares.config.php';
	    if(!file_exists($welfares_file)){
	        $list = $this -> getWelfareList();
	        
	        write_file($welfares_file, $list);
	    }
	    if(file_exists($welfares_file)){
	        $list = require $welfares_file;
	    }
	    return $list;
	}
	
	/*
	 * 用户领取福利
	 */
	public function grantUserWelfare($param = array()){
	    if(empty($param)) return; 
	    $resp = array();
	    $valid = trade(C('welfare.obtainWerfare_url'), $param, $resp);
	    if($validResp && $resp['returnStatus'] == '000'){
	        return true;
	    }else{
	        return false;
	    }
	}

	
	/*
	 * 日志记录函数
	 */
	function logResult($word='') {
		$fp = fopen("log.txt","a");
		flock($fp, LOCK_EX) ;
		fwrite($fp,"执行日期：".strftime("%Y%m%d%H%M%S",time())."\n".$word."\n");
		flock($fp, LOCK_UN);
		fclose($fp);
	}
	
	
}