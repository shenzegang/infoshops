<?php
//接收微信回调数据
$sReqData = file_get_contents("php://input");
$data = (array)simplexml_load_string($sReqData, 'SimpleXMLElement', LIBXML_NOCDATA);
$resp = array();
//进行签名验证
define('BASE_PATH', str_replace('\\', '/', dirname(__FILE__)));
if (! @include (dirname(dirname(__FILE__)) . '/global.php'))
	exit('global.php isn\'t exists!');
if (! @include (BASE_CORE_PATH . '/shopnc.php'))
	exit('shopnc.php isn\'t exists!');
require_once BASE_CORE_PATH . DS . 'framework' . DS . 'function' . DS . 'payapi.php';

$validResp = trade("http://121.41.113.184:9090/xnpayws/ws/pay/wxPay/WxpayNotifyResponse", $sReqData, $resp);
if($validResp){
	/*
	 * 连接mysql信息
	*/
	$con = mysql_connect('127.0.0.1', 'root', 'root');
	if(!$con){
		logResult('Could not connect: ' . mysql_error());
		exit('failed');
	}
	/*
	 * 选择数据库
	*/
	$db_selecct = mysql_select_db('infol_shop9999');
	if(!$db_selecct){
	    logResult("could not to the database</br>" . mysql_error());
	    exit('failed');
	}
	/*
	$sql = "SELECT * FROM `cor_order_pay` WHERE pay_sn='" . $data['out_trade_no'] . "'";
	$result = mysql_query($sql);
	if(!$result){
		die("could not to the database</br>" . mysql_error());
		logResult('查询出错，SQL语句为:' . $sql);
	}
	$order_pay_info = mysql_fetch_array($result);
	if (!is_array($order_pay_info) || empty($order_pay_info)){
		exit();
	}
	if (intval($order_pay_info['pdr_payment_state'])){
		exit();
	}
	*/
	// 取得订单列表和API支付总金额
	$sql = "SELECT * FROM `cor_order` WHERE pay_sn='" . $data['out_trade_no'] . "' and order_state='10'";
	$result = mysql_query($sql);
	if(!$result){
		logResult('查询出错，SQL语句为:' . $sql);
		exit('failed');
	}
	$order_list = array();
	while ($row = mysql_fetch_array($result)){
		$order_list[] = $row;
	}
	
	if (empty($order_list)) exit();
	$pay_amount = 0;
	foreach ($order_list as $order_info) {
		$pay_amount += ncPriceFormat(floatval($order_info['order_amount']) - floatval($order_info['pd_amount']) - floatval($order_info['welfare_amount']));
	}
	$order_pay_info['pay_amount'] = $pay_amount;
	//查询会员信息
	$sql = "SELECT * FROM `cor_member` WHERE member_id='" . $order_pay_info['buyer_id'] . "'";
	$result = mysql_query($sql);
	if(!$result){
		logResult('查询出错，SQL语句为:' . $sql);
		exit('failed');
	}
	$mem_info = mysql_fetch_array($result);
	if (empty($mem_info)) {
		exit('failed');
	}
	try{
		mysql_query("START TRANSACTION");
		/*
		 * 更改支付订单状态
		*/
		$sql = "UPDATE `cor_order_pay` set api_pay_state='1' WHERE pay_id='" . $order_pay_info['pay_id'] . "'";
		$update = mysql_query($sql);
		if(!$update) {
			throw new Exception('更新支付订单状态失败, SQL语句为：' . $sql);
		}
		/*
		 * 更改订单状态
		*/
		$time = TIMESTAMP;
		$sql = "UPDATE `cor_order` set order_state='20', payment_time='$time', payment_code='weichat' WHERE pay_sn='" . $order_pay_info['pay_sn'] . "' and order_state='10'";
		$update = mysql_query($sql);
		if(!$update) {
			throw new Exception('更新订单状态失败, SQL语句为：' . $sql);
		}
		/*
		 * 生成预存款充值订单
		*/
		/*
		$pay_sn = makeSn();
		
		$sql = "INSERT INTO `cor_pd_recharge` (pdr_sn, pdr_member_id, pdr_member_name, pdr_amount, pdr_add_time, pdr_payment_state, pdr_payment_code, pdr_payment_name, pdr_trade_sn)
					VALUES ('$pay_sn', '" . $order_pay_info['buyer_id'] . "', '" . $mem_info['member_name'] . "', '" . $order_pay_info['pay_amount'] . "', '$time', '1', 'weichat', '微信支付', '')";
		$state = mysql_query($sql);
		if (!$state){
			throw new Exception('记录预存款操作失败, SQL语句为：' . $sql);
		}
		*/
		/*
		 * 操作日志
		*/
		/*
		$amount = $order_pay_info['pay_amount'];
		$member_id = $order_pay_info['buyer_id'];
		$member_name = $mem_info['member_name'];
		$lg_desc = '充值，充值单号为：' . $pay_sn;
		$sql = "INSERT INTO `cor_pd_log` (`lg_av_amount`, `lg_member_id`, `lg_member_name`, `lg_add_time`, `lg_type`, `lg_desc`, `lg_admin_name`) VALUES ('$amount', $member_id, '$member_name', '$time', 'recharge', '$lg_desc', '')";
		$state = mysql_query($sql);
		if (!$state){
			throw new Exception('记录预存款操作日志失败, SQL语句为：' . $sql);
		}
		*/
		foreach ($order_list as $order_info) {
			$sql = "INSERT INTO `cor_order_log` (order_id, log_role, log_msg, log_orderstate) VALUES ('" . $order_info['order_id'] . "', 'buyer', '订单支付，支付单号为" . $order_info['order_sn'] . "', '20')";
			$state = mysql_query($sql);
			if (!$state){
				throw new Exception('记录订单支付日志失败, SQL语句为：' . $sql);
			}
			
			$amount = $order_info['order_amount'];
			$member_id = $order_info['buyer_id'];
			$member_name = $order_info['member_name'];
			$lg_desc = '下单，冻结预存款，订单号为：' . $order_info['order_sn'];
			/*
			$sql = "INSERT INTO `cor_pd_log` (`lg_av_amount`, `lg_member_id`, `lg_member_name`, `lg_add_time`, `lg_type`, `lg_desc`, `lg_admin_name`) VALUES ('$amount', $member_id, '$member_name', '$time', 'recharge', '$lg_desc', '')";
			$state = mysql_query($sql);
			if (!$state){
				throw new Exception('记录预存款操作日志失败, SQL语句为：' . $sql);
			}
			*/
			$sql = "UPDATE `cor_member` SET available_predeposit=available_predeposit-$amount, freeze_predeposit=freeze_predeposit+$amount WHERE member_id='$member_id'";
			$state = mysql_query($sql);
			if (!$state){
				throw new Exception('更新用户账户失败, SQL语句为：' . $sql);
			}
			
			/*
			 * 调用支付系统，使用预存款支付
			*/
			require_once BASE_CORE_PATH . DS . 'framework' . DS . 'function' . DS . 'payapi.php';
			$resp = array();
			$req = array('orderCode' => $order_info['order_sn'], 'payPassword' => $mem_info['member_paypasswd'], 'payType' => 'weichat');
			$validResp = trade("http://121.41.113.184:9090/xnpayws/ws/pay/balancePay/payOrder", $req, $resp);
			if(!$validResp || $resp['returnStatus'] != '000'){
				throw new Exception('提交支付订单失败' . $resp['returnMsg']);
			}
		}
		mysql_query("COMMIT");
	}catch(Exception $e){
		mysql_query("ROLLBACK");
		logResult($e -> getMessage());
	}
	//关闭数据库连接
	mysql_close($con);
	//sj 20150902 支付宝支付成功 发送消息
	//sendMessage($order_pay_info['buyer_id'], $out_trade_no);
	exit('success');
}else{
	exit('failed');
}

/**
 * 生成充值编号
 *
 * @return string
 */
function makeSn()
{
	return mt_rand(10, 99) . sprintf('%010d', time() - 946656000) . sprintf('%03d', (float) microtime() * 1000) . sprintf('%03d', (int) $_SESSION['member_id'] % 1000);
}

/**发送消息*/
function sendMessage($buyer_id,$pay_sn){
	$model_order = Model('order');
	$ord_info = $model_order -> getOrderInfo(array(
			'pay_sn' => $pay_sn,
			'buyer_id' => $buyer_id
	));
	$order_goods_list = $model_order->getOrderGoodsList(array(
			'order_id' => $ord_info['order_id']
	));
	$goodshtml = '<table width="700" border="0" cellspacing="1" cellpadding="1"><tr><td width="70"></td>
				    <td height="30">商品名称</td>
				    <td>实付金额</td>
				    <td>购买数量</td>
				   </tr>';
	foreach ($order_goods_list as $key => $val) {
		$goodshtml .= ' <tr>
				   <td height="70"><img src="' . SHOP_SITE_URL . '/data/upload/shop/store/goods/' . $val['store_id'] . '/' . $val['goods_image'] . '" width="60" height="60" /></td>
				    <td>' . $val['goods_name'] . '</td>
				    <td>' . $val['goods_pay_price'] . '</td>
					<td>' . $val['goods_num'] . '</td>
				  </tr>';
	}
	$goodshtml .= '</table>';
	// 给买家邮件
	$this->send_notice($_SESSION['member_id'], 'email_tobuyer_new_order_notify', array(
			'site_name' => $GLOBALS['setting_config']['site_name'],
			'site_url' => SHOP_SITE_URL,
			'goods_list' => $goodshtml,
			'buyer_name' => $_SESSION['member_name'],
			'order_id' => $ord_info['order_id'],
			//shijian 8-21 发送邮件的订单号不应该为支付单号
			'order_sn' => $ord_info['order_sn']
	), false);

	// 给卖家邮件
	$rsst = Model('store')->getStoreInfo(array(
			'store_id' => $ord_info['store_id']
	));
	$this->send_notice($rsst['member_id'], 'email_toseller_new_order_notify', array(
			'site_name' => $GLOBALS['setting_config']['site_name'],
			'goods_list' => $goodshtml,
			'site_url' => SHOP_SITE_URL,
			'order_id' => $ord_info['order_id'],
			'seller_name' => $ord_info['store_name'],
			//shijian 8-21 发送邮件的订单号不应该为支付单号
			'order_sn' => $ord_info['order_sn']
	), false);

	// 给买家发送短信
	$this->send_sms($_SESSION['member_id'], 'sms_tobuyer_new_order_notify', array(
			'site_name' => $GLOBALS['setting_config']['site_name'],
			'site_url' => SHOP_SITE_URL,
			'buyer_name' => $_SESSION['member_name'],
			'order_id' => $ord_info['order_id'],
			//shijian 8-21 发送邮件的订单号不应该为支付单号
			'order_sn' => $ord_info['order_sn']
	), false, array(
			'store_id' => $ord_info['store_id'],
			'dateline' => time(),
			'tomember_id' => $_SESSION['member_id'],
			'tomember_name' => $_SESSION['member_name']
	));

	// 给卖家发送短信
	$rsst = Model('store')->getStoreInfo(array(
			'store_id' => $ord_info['store_id']
	));
	$this->send_sms($rsst['member_id'], 'sms_toseller_new_order_notify', array(
			'site_name' => $GLOBALS['setting_config']['site_name'],
			'site_url' => SHOP_SITE_URL,
			'order_id' => $ord_info['order_id'],
			'seller_name' => $ord_info['store_name'],
			//shijian 8-21 发送邮件的订单号不应该为支付单号
			'order_sn' => $ord_info['order_sn']
	), false, array(
			'store_id' => $ord_info['store_id'],
			'dateline' => time(),
			'tomember_id' => $rsst['member_id'],
			'tomember_name' => $ord_info['store_name']
	));
}

function logResult($word='') {
	$fp = fopen("log.txt","a");
	flock($fp, LOCK_EX) ;
	fwrite($fp,"执行日期：".strftime("%Y%m%d%H%M%S",time())."\n".$word."\n");
	flock($fp, LOCK_UN);
	fclose($fp);
}
?>