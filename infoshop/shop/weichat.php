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
logResult(print_r($resp, 1));
if($validResp){
    $data = (array)$resp['orderRechargeRecord'];
    if(empty($data)){
        exit('failed');
    }
    logResult(print_r($data, 1));
	/*
	 * 连接mysql信息
	 */
	$con = mysql_connect('127.0.0.1', 'root', 'root');
	if(!$con){
		exit('failed');
		logResult('Could not connect: ' . mysql_error());
	}
	/*
	 * 选择数据库
	 */
	$db_selecct = mysql_select_db('infol_shop9999');
	if(!$db_selecct){
	    exit('failed');
		logResult("could not to the database</br>" . mysql_error());
	}
	//支付1.0
	/*
	$sql = "SELECT * FROM `cor_pd_recharge` WHERE pdr_sn='" . $data['out_trade_no'] . "'";
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
	
	$time = TIMESTAMP;
	$pdr_trade_sn = $data['transaction_id'];
	$sql = "UPDATE `cor_pd_recharge` SET pdr_payment_state='1', 
										pdr_payment_time=$time, 
										pdr_payment_code='weichat', 
									    pdr_payment_name='微信支付', 
										pdr_trade_sn='$pdr_trade_sn' WHERE pdr_sn='" . $data['out_trade_no'] . "'";
	*/
	
	//支付2.0系统变更，提取预存款充值记录
	$update = array();
	$update['pdr_sn'] = $data['orderRechargeRecordId'];
	$update['pdr_member_id'] = $data['userId'];
	$member_name = Model('member') -> infoMember(array('member_id' => $update['pdr_member_id']), 'member_name');
	$update['pdr_member_name'] = $member_name;
	$update['pdr_amount'] = ncPriceFormat(floatval($data['orderTotal']));
	$update['pdr_add_time'] = strtotime($data['createTime']);
	$update['pdr_payment_code'] = strtolower($data['paymentChannel']);
	$update['pdr_payment_name'] = orderPaymentName($update['pdr_payment_code']);
	$update['pdr_trade_sn'] = $resp['tradeNo'];
	$update['pdr_payment_state'] = 1;
	$update['pdr_payment_time'] = strtotime($data['createTime']);
	$update['pdr_admin'] = '';
	$update['pdr_state'] = 1;
	
	/*
	 * 在数据表新增一行
	 * 数组的键是数据表的字段名，键对应的值为需要新增的数据
	 */
    foreach($update as $key => $value){
        $cols[] = $key;
        $vals[] = "'{$value}'";
    }
    $sql = "INSERT INTO `cor_pd_recharge` (" . join(',', $cols) . ") VALUES (" . join(',', $vals) . ")";
	try{
		mysql_query("START TRANSACTION");
		// 更改充值状态
		$state = mysql_query($sql);
		if (!$state){
			throw new Exception('更新充值订单失败, SQL语句为：' . $sql);
		}
		/*
		 * 变更预存款
		 */
		$amount = $update['pdr_amount'];
		$member_id = $update['pdr_member_id'];
		$member_name = $update['pdr_member_name'];
		$time = $update['pdr_add_time'];
		$lg_desc = '充值，充值单号为：' . $update['pdr_sn'];
		$sql = "UPDATE `cor_member` SET available_predeposit=available_predeposit+$amount WHERE member_id='$member_id'";
		$state = mysql_query($sql);
		if (!$state){
			throw new Exception('更新用户账户失败, SQL语句为：' . $sql);
		}
		/*
		 * 操作日志
		 */
		$sql = "INSERT INTO `cor_pd_log` (`lg_av_amount`, `lg_member_id`, `lg_member_name`, `lg_add_time`, `lg_type`, `lg_desc`, `lg_admin_name`) VALUES ('$amount', $member_id, '$member_name', '$time', 'recharge', '$lg_desc', '')";
		$state = mysql_query($sql);
		if (!$state){
			throw new Exception('记录预存款操作日志失败, SQL语句为：' . $sql);
		}
		mysql_query("COMMIT");
	}catch(Exception $e){
		mysql_query("ROLLBACK");
		logResult($e -> getMessage());
	}
	//关闭数据库连接
	mysql_close($con);
	unset($sql, $data, $amount, $member_id, $member_name, $state);
	exit('success');
}else{
	exit('failed');
}

function logResult($word='') {
	$fp = fopen("log.txt","a");
	flock($fp, LOCK_EX) ;
	fwrite($fp,"执行日期：".strftime("%Y%m%d%H%M%S",time())."\n".$word."\n");
	flock($fp, LOCK_UN);
	fclose($fp);
}
?>