<?php
/**
 * 支付入口
 *
 * 
 *
 * @copyright  Copyright (c) 2003-2015 B2B2C Inc. (http://www.infol.com.cn)
 * @license    http://www.infol.com.cn
 * @link       http://www.corshop.net
 * @since      File available since Release v1.1
 */
defined('CorShop') or exit('Access Invalid!');

class paymentControl extends BaseHomeControl
{

    public function indexOp()
    {
        // 购买商品和预存款充值分流
        if ($_POST['order_type'] == 'product_buy') {
            $this -> _product_buy();
        } elseif ($_POST['order_type'] == 'pd_rechange') {
            $this -> pd_recharge();
        }
    }

    /**
     * 商品购买
     */
    private function _product_buy()
    {
        $pay_sn = $_POST['pay_sn'];
        $payment_code = $_POST['payment_code'];
        $url = 'index.php?act=member_order';
        $valid = ! preg_match('/^\d{18}$/', $pay_sn) || ! preg_match('/^[a-z]{1,20}$/', $payment_code) || in_array($payment_code, array(
            'offline',
        ));
        if ($valid) {
            showMessage(Language::get('para_error'), '', 'html', 'error');
        }
        
        $model_payment = Model('payment');
        $pay_info = $model_payment -> productBuy($pay_sn, $payment_code, $_SESSION['member_id']);
        
        if (! empty($pay_info['error'])) {
            showMessage($pay_info['error'], $url, 'html', 'error');
        }
        
        if($payment_code == 'predeposit'){				//预存款支付入口
        	/*
        	 * 检查前台是否进行过支付密码验证
        	 */
        	if(!isset($_POST['predeposit'])){
        		showMessage('支付密码错误，无法完成支付', $url, 'html', 'error');
        	}
        	$result = $model_payment -> pdPay($pay_info, $_POST, $_SESSION['member_id'], $_SESSION['member_name']);
        	if(!empty($result['error'])){
        		showMessage($result['error'], $url, 'html', 'error');
        	}else{
        		//redirect('index.php?act=buy&op=pay_ok&pay_sn=' . $pay_sn . '&pay_amount=' . ncPriceFormat($result['pay_amount']));
				//sj 20150902 预存款支付成功 发送消息
				$this -> sendMessage($_SESSION['member_id'],$pay_sn);
        		$this -> payment_successOp();
        	}
        	unset($result);
        }else if($payment_code == 'alipay'){			//支付宝支付入口
        	$this -> aliPay('product_buy', $pay_info['order_pay_info'], $pay_info['payment_info']);
        }else if($payment_code == 'unionpay'){			//银联支付入口
        	$this -> unionPay('product_buy', $pay_info['order_pay_info'], $pay_info['payment_info']);
        }else if($payment_code == 'weichat'){
        	$this -> weichat('product_buy', $pay_info['order_pay_info'], $pay_info['payment_info']);
        }else if($payment_code == "constrbank"){
            $this -> constrPay('product_buy', $pay_info['order_pay_info'], $pay_info['payment_info']);
        }
        unset($pay_info);
    }
    
    /*
     * 建行支付接口
     */
    private function constrPay($pay_type, $order_info, $payment_info){
        $resp = array();
        if($pay_type != 'product_buy'){
            $req = array(
                'userId' => $_SESSION['member_id'],
                'orderId' => $order_info['pay_sn'],
                'orderType' => $payment_info['payment_code'],
                'payment' => $order_info['pay_amount'],
                'orderDesc' => $order_info['subject']
            );
        }else{
            $req = array(
                'userId' => $_SESSION['member_id'],
                'orderId' => $order_info['pay_sn'],
                'orderType' => $payment_info['payment_code'],
                'payment' => $order_info['pay_amount'],
                'orderDesc' => $order_info['subject']
            );
        }
        require_once BASE_CORE_PATH . DS . 'framework' . DS . 'function' . DS . 'payapi.php';
        $validResp = trade(C('constrbank.req_url'), $req, $resp);
        if($validResp && $resp['returnStatus'] == '000'){
            
        }else{
            echo "建行支付接口调用失败";
        }
    }
    
    /**
     * 微信支付(适用于预存款充值，直接购买支付)
     */
    public function weichatOp(){
    	$resp = $req_trade = array();                                                                      
    	$pay_sn = $_GET['pay_sn'];
    	$pay_type = $_GET['pay_type'];
    	$model_payment = Model('payment');
    	$pay_info = $model_payment -> productBuy($pay_sn, 'weichat', $_SESSION['member_id']);
    	$order_info = $pay_info['order_pay_info'];
    	//请求参数
    	if($pay_type != 'product_buy'){
    		$req_trade = array(
    				'userId' => $_SESSION['member_id'],
    				'spbillCreateIp' => $order_info['client_ip'],				
    				'productId' => time(),
    				'totalFee' => $order_info['pay_amount'],
    				'body' => $order_info['subject'],
    		        'orderType' => 'predeposit',
    				'notifyUrl' => C('weichat.notify_url'),
    				'tradeType' => 'NATIVE'
    		);
    		$url = "index.php?act=payment&op=payment_success&predeposit=1";
    		$order_type = 1;
    	}else{
    		$req_trade = array(
    				'userId' => $_SESSION['member_id'],
    				'spbillCreateIp' => $order_info['client_ip'],	
    				'outTradeNo' => $order_info['pay_sn'],			
    				'productId' => $order_info['pay_sn'],
    				'totalFee' => $order_info['pay_amount'],
    				'body' => $order_info['subject'],
    				'notifyUrl' => C('weichat.notify_url_buy'),
    				'tradeType' => 'NATIVE'
    		);
    		$url = "index.php?act=payment&op=payment_success";
    		$order_type = 0;
    		
    	}
    	
    	require_once BASE_CORE_PATH . DS . 'framework' . DS . 'function' . DS . 'payapi.php';
    	$validResp = trade(C('weichat.req_url'), $req_trade, $resp);
    	if($validResp && $resp['returnStatus'] == '000'){
    		$codeUrl = $resp['codeUrl'];
    		Tpl :: output('codeUrl', $codeUrl);
    		Tpl :: output('pay_sn', $resp['outTradeNo']);
    		Tpl :: output('url', $url);
    		Tpl :: output('order_type', $order_type);
    		Tpl :: showpage('weichat', 'null_layout');
    	}else{
    		echo '微信支付调用失败';
    	}
    	
    }
    
    /*
     * 使用微信扫码支付，间隔5（s）后调用该方法查询订单支付状态
     * @param outTradeNo bigint 订单交易单号
     * return (
     * 		'1' 交易成功,
     * 		'0' 失败
     * )
     */
    public function validWeichatPayStatusOp(){
    	$resp = array();
    	$req = array('outTradeNo' => trim($_GET['out_trade_no']));
    	$order_type = $_GET['order_type'];
    	require_once BASE_CORE_PATH . DS . 'framework' . DS . 'function' . DS . 'payapi.php';
    	$validResp = trade(C('pay.validTrade'), $req, $resp);
    	
    	if($validResp && $resp['returnStatus'] == '000'){
    		echo 1;
    	}else{
    		//为充值订单
    		if($order_type){
    			Model('predeposit') -> delPdRecharge(array('pdr_sn' => trim($_GET['out_trade_no'])));
    		}
    		echo 0;
    	}
    }
    
    /**
     * 银联支付(适应于预存款充值,直接购买支付)
     */
    private function unionPay($pay_type, $order_info, $payment_info){
    	$resp = array();
    	$req_trade = array();
    	//请求参数
    	if($pay_type != 'product_buy'){
    		//为预存款充值
    		$req_trade = array(
    		        'userId' => $_SESSION['member_id'],
        		    'orderDesc' => $order_info['subject'],
        		    'txnAmt' => $order_info['pay_amount'],
        		    'txnTime' => gmdate('YmdHis', time()),
        		    'backUrl' => C('unionpay.notify_url'),
    		        'orderType' => 'predeposit',
        		    'frontUrl' => C('unionpay.return_url'),
        		    'bizType' => '000201'
    		);
    	}else{
    		$req_trade = array(
    				'userId' => $_SESSION['member_id'],
    				'orderId' => $order_info['pay_sn'],
    				'orderDesc' => $order_info['subject'],
    				'txnAmt' => $order_info['pay_amount'],
    				'txnTime' => gmdate('YmdHis', time()),
    				'backUrl' => C('unionpay.notify_url_buy'),
    				'frontUrl' => C('unionpay.return_url_buy'),
    				'bizType' => '000201'
    		);
    	}
    	
    	require_once BASE_CORE_PATH . DS . 'framework' . DS . 'function' . DS . 'payapi.php';
    	$validResp = trade(C('unionpay.req_url'), $req_trade, $resp);
    	if($validResp){
    	    Model('order') -> editOrder(array('pay_sn' => $resp['payOtn']), array('pay_sn' => $order_info['pay_sn']));
    	    Model('order') -> editOrderPay(array('pay_sn' => $resp['payOtn']), array('pay_sn' => $order_info['pay_sn']));
    		echo $resp['unionPayHtml'];
    	}else{
    		echo '银联调用失败';
    	}
    }
    
    /**
     * 支付宝支付(适应于预存款充值,直接购买支付)
     */
    private function aliPay($pay_type, $order_info, $payment_info){
    	$resp = array();
    	$req_trade = array();
    	//请求参数
    	if($pay_type != 'product_buy'){
    	    //为预存款充值
    		$req_trade = array(
    				'userId' => $_SESSION['member_id'],
    				'exterInvokeIp' => $order_info['client_ip'],
    				'subject' => $order_info['subject'],
    				'totalFee' => $order_info['pay_amount'],
    				'body' => $order_info['subject'],
    				'notifyUrl' => C('alipay.notify_url'),
    				'orderType' => 'predeposit',
    				'returnUrl' => C('alipay.return_url')
    		);
    	}else{
    	    //为商品直接购买
    		$req_trade = array(
    				'userId' => $_SESSION['member_id'],
    				'exterInvokeIp' => $order_info['client_ip'],
    				'subject' => $order_info['subject'],
    				'totalFee' => $order_info['pay_amount'],
    				'body' => $order_info['pay_sn'],
    				'orderType' => 'product',
    				'notifyUrl' => C('alipay.notify_url_buy'),
    				'returnUrl' => C('alipay.return_url_buy')
    		);
    	}
    	require_once BASE_CORE_PATH . DS . 'framework' . DS . 'function' . DS . 'payapi.php';
    	try{
	    	$validResp = trade(C('alipay.req_url'), $req_trade, $resp);
	    	if($validResp){
	    	    Model('order') -> editOrder(array('pay_sn' => $resp['payOtn']), array('pay_sn' => $order_info['pay_sn']));
	    	    Model('order') -> editOrderPay(array('pay_sn' => $resp['payOtn']), array('pay_sn' => $order_info['pay_sn']));
	    		Tpl :: show($resp['aliPayHtml']);
	    	}else{
	    		throw new Exception("数据错误");
	    	}
    	}catch(Exception $e){
    		echo $e -> getMessage();
    	}
    }
    
    /**
     * 支付宝异步通知（适用于立即购买）
     */
    public function aliNotify_buyOp(){
    	$req = $_POST;
    	unset($req['act']);
    	unset($req['op']);
    	
    	$resp = array();
    	$out_trade_no = $req['out_trade_no'];
    	// 商品购买
    	$model_order = Model('order');
    	$order_pay_info = $model_order->getOrderPayInfo(array(
    			'pay_sn' => $out_trade_no
    	));
    	
    	if (! is_array($order_pay_info) || empty($order_pay_info))
    		exit('fail');
    	if (intval($order_pay_info['api_pay_state']))
    		exit('success');
    	
    	// 取得订单列表和API支付总金额
    	$order_list = $model_order -> getOrderList(array(
    			'pay_sn' => $out_trade_no,
    			'order_state' => ORDER_STATE_NEW
    	));
    	
    	$pay_amount = 0;
    	foreach ($order_list as $order_info) {
    		$pay_amount += ncPriceFormat(floatval($order_info['order_amount']) - floatval($order_info['pd_amount']) - floatval($order_info['welfare_amount']));
    	}
    	$order_pay_info['pay_amount'] = $pay_amount;
    	//进行签名验证
    	require_once BASE_CORE_PATH . DS . 'framework' . DS . 'function' . DS . 'payapi.php';
    	$validResp = trade(C('alipay.rep_url'), $req, $resp);
    	
    	if($validResp){
    		$model_payment = Model('payment');
    		$result = $model_payment -> updateProductBuy($out_trade_no, 'alipay', $order_list, $_POST['trade_no'], $order_pay_info);
    		
    		if (! empty($result['error'])) {
    			exit('fail');
    		}
			//sj 20150902 支付宝支付成功 发送消息
			$this -> sendMessage($order_pay_info['buyer_id'], $out_trade_no);
    		exit('success');
    	}else{
    		exit('fail');
    	}
    }
    
    /**
     * 银联异步通知（适用于预存款直接购买）
     */
    public function uniNotify_buyOp(){
    	$req = $_POST;
    	unset($req['act']);
    	unset($req['op']);
    	$model_pd = Model('predeposit');
    	$out_trade_no = $req['orderId'];
    	logResult(print_r($req, 1));
    	// 商品购买
    	$model_order = Model('order');
    	$order_pay_info = $model_order->getOrderPayInfo(array(
    			'pay_sn' => $out_trade_no
    	));
    	
    	if (!is_array($order_pay_info) || empty($order_pay_info))
    		exit();
    	if (intval($order_pay_info['api_pay_state']))
    		exit();
    	// 取得订单列表和API支付总金额
    	$order_list = $model_order -> getOrderList(array(
    			'pay_sn' => $out_trade_no,
    			'order_state' => ORDER_STATE_NEW
    	));
    	
    	if (empty($order_list)) exit();
    	$pay_amount = 0;
    	foreach ($order_list as $order_info) {
    		$pay_amount += ncPriceFormat(floatval($order_info['order_amount']) - floatval($order_info['pd_amount']));
    	}
    	$order_pay_info['pay_amount'] = $pay_amount;
    	
    	//进行签名验证
    	require_once BASE_CORE_PATH . DS . 'framework' . DS . 'function' . DS . 'payapi.php';
    	$validResp = trade(C('unionpay.rep_url'), $req, $resp);
    	
    	if($validResp){
    		$model_payment = Model('payment');
    		$result = $model_payment -> updateProductBuy($out_trade_no, 'unionpay', $order_list, $_POST['queryId'], $order_pay_info);
    		if (! empty($result['error'])) {
    			exit();
    		}
			//sj 20150902 银联支付成功 发送消息
			$this -> sendMessage($order_pay_info['buyer_id'],$out_trade_no);
    		exit();
    	}else{
    		exit();
    	}
    }
    
    /**
     * 银联异步通知（适用于预存款充值）
     */
    public function uniNotifyOp(){
    $req = $_POST;
    	unset($req['act']);
    	unset($req['op']);
    	$resp = array(); 
    	$succ = $fail = '';
    	$model_pd = Model('predeposit');
    	//进行签名验证
		require_once BASE_CORE_PATH . DS . 'framework' . DS . 'function' . DS . 'payapi.php';
    	$validResp = trade(C('unionpay.rep_url'), $req, $resp);
    	if($validResp){
    	    $data = (array)$resp['orderRechargeRecord'];
    	    $succ = str_replace(array('alipay', 'unionpay'), array('success', ''), strtolower($data['paymentChannel']));
    		$error = str_replace(array('alipay', 'unionpay'), array('fail', ''), strtolower($data['paymentChannel']));
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
    		try {
    			$model_pd -> beginTransaction();
    			// 更改充值状态
    			$state = $model_pd -> addPdRecharge($update);
    			if (!$state) {
    				new Exception('充值记录插入失败');
    			}
    			// 变更会员预存款
    			$data = array();
    			$data['member_id'] = $update['pdr_member_id'];
    			$data['member_name'] = $update['pdr_member_name'];
    			$data['amount'] = $update['pdr_amount'];
    			$data['pdr_sn'] = $update['pdr_sn'];
    			$model_pd -> changePd('recharge', $data);
    			$model_pd -> commit();
    			exit($succ);
    		} catch (Exception $e) {
    			$model_pd -> rollback();
    			logResult($e -> getMessage());
    			exit($fail);
    		}
    	}else{
    		exit($fail);
    	}
    }
    
    /**
     * 微信异步通知（适用于预存款充值）
     */
    public function whatNotifyOp(){
    	$req = $_POST;
    	$resp = array();
    	unset($req['act']);
    	unset($req['op']);
    	$model_pd = Model('predeposit');
    	$out_trade_no = $req['out_trade_no'];
    	//获取预存款充值页面
    	$order_pay_info = $model_pd -> getPdRechargeInfo(array('pdr_sn' => $out_trade_no));
    	if (!is_array($order_pay_info) || empty($order_pay_info)){
    		exit();
    	}
    	if (intval($order_pay_info['pdr_payment_state'])){
    		exit();
    	}
    	//进行签名验证
    	require_once BASE_CORE_PATH . DS . 'framework' . DS . 'function' . DS . 'payapi.php';
    	$validResp = trade(C('weichat.rep_url'), $req, $resp);
    	if($validResp){
    		$condition = array();
    		$condition['pdr_sn'] = $out_trade_no;
    		$condition['pdr_payment_state'] = 0;
    		$recharge_info = $model_pd -> getPdRechargeInfo($condition);
    		if (!$recharge_info) exit();
    		$update = array();
    		$update['pdr_payment_state'] = 1;
    		$update['pdr_payment_time'] = TIMESTAMP;
    		$update['pdr_payment_code'] = 'weichat';
    		$update['pdr_payment_name'] = '微信支付';
    		$update['pdr_trade_sn'] = $_POST['transaction_id'];
    
    		try{
    			$model_pd -> beginTransaction();
    			// 更改充值状态
    			$state = $model_pd -> editPdRecharge($update, $condition);
    			if (!$state) exit();
    			// 变更会员预存款
    			$data = array();
    			$data['member_id'] = $recharge_info['pdr_member_id'];
    			$data['member_name'] = $recharge_info['pdr_member_name'];
    			$data['amount'] = $recharge_info['pdr_amount'];
    			$data['pdr_sn'] = $recharge_info['pdr_sn'];
    			$model_pd -> changePd('recharge', $data);
    			$model_pd -> commit();
    		}catch(Exception $e){
    			$model_pd -> rollback();
    			exit();
    		}
    	}else{
    		exit();
    	}
    }
    
    /**
     * @author Brady
     * @createTime 2015-09-25 15:32
     * 支付宝,银联异步通知(适应于立即充值)
     */
    public function aliNotifyOp(){
    	$req = $_POST;
    	unset($req['act']);
    	unset($req['op']);
    	$resp = array(); 
    	$succ = $fail = '';
    	$model_pd = Model('predeposit');
    	//进行签名验证
		require_once BASE_CORE_PATH . DS . 'framework' . DS . 'function' . DS . 'payapi.php';
    	$validResp = trade(C('alipay.rep_url'), $req, $resp);
    	if($validResp){
    	    $data = (array)$resp['orderRechargeRecord'];
    	    $succ = str_replace(array('alipay', 'unionpay'), array('success', ''), strtolower($data['paymentChannel']));
    		$error = str_replace(array('alipay', 'unionpay'), array('fail', ''), strtolower($data['paymentChannel']));
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
    		try {
    			$model_pd -> beginTransaction();
    			// 更改充值状态
    			$state = $model_pd -> addPdRecharge($update);
    			if (!$state) {
    				new Exception('充值记录插入失败');
    			}
    			// 变更会员预存款
    			$data = array();
    			$data['member_id'] = $update['pdr_member_id'];
    			$data['member_name'] = $update['pdr_member_name'];
    			$data['amount'] = $update['pdr_amount'];
    			$data['pdr_sn'] = $update['pdr_sn'];
    			$model_pd -> changePd('recharge', $data);
    			$model_pd -> commit();
    			exit($succ);
    		} catch (Exception $e) {
    			$model_pd -> rollback();
    			logResult($e -> getMessage());
    			exit($fail);
    		}
    	}else{
    		exit($fail);
    	}
    }
    
    /*
     * @version 2.0
     * 预存款充值
     */
    private function pd_recharge(){
        Language::read('home_payment_index');
        // 取支付方式信息
        $model_payment = Model('payment');
        $model_pd = Model('predeposit');
        $url = 'index.php?act=predeposit';
        $pdr_amount = abs(floatval($_POST['pdr_amount']));
        $payment_code = $_POST['payment_code'];
        //判断用户的金额是否为有效数字
        if ($pdr_amount <= 0 || !preg_match('/^[a-z]{1,20}$/', $payment_code)) {
            showMessage(Language::get('para_error'), $url, 'html', 'error');
        }
        //判断用户使用 的支付方式是不是为系统预设，如果不是，则不能充值
        $condition = array();
        $condition['payment_code'] = $payment_code;
        $payment_info = $model_payment->getPaymentOpenInfo($condition);
        if (!$payment_info || in_array($payment_info['payment_code'], array('offline', 'predeposit'))) {
            showMessage(L('payment_index_sys_not_support'), $url, 'html', 'error');
        }
        
        $order_info = array();
        $order_info['subject'] = '预存款充值';
        $order_info['order_type'] = 'predeposit';
        $order_info['client_ip'] = getIp();
        $order_info['pay_amount'] = $pdr_amount;
        $order_info['pdr_payment_time'] = time();
        
        if($payment_code == 'alipay'){
            $this -> aliPay('predeposit', $order_info, $payment_info);
        }else if($payment_code == 'unionpay'){
            $this -> unionPay('predeposit', $order_info, $payment_info);
        }else if($payment_code == 'weichat'){
            $this -> weichat('predeposit', $order_info, $payment_info);
        }
        unset($payment_code, $pdr_amount, $payment_info);
        
    }
    
    
    /**
     * @version 1.0
     * 预存款充值
     */
    private function _pd_rechange()
    {
        Language::read('home_payment_index');
        $url = 'index.php?act=predeposit';
        // pay_sn:充值单号
        $pay_sn = $_POST['pdr_sn'];
        $payment_code = $_POST['payment_code'];
        $pdr_amount = abs(floatval($_POST['pdr_amount']));
        if ($pdr_amount <= 0 || ! preg_match('/^\d{18}$/', $pay_sn) || ! preg_match('/^[a-z]{1,20}$/', $payment_code)) {
            showMessage(Language::get('para_error'), $url, 'html', 'error');
        }
        
        // 取支付方式信息
        $model_payment = Model('payment');
        $condition = array();
        $condition['payment_code'] = $payment_code;
        $payment_info = $model_payment->getPaymentOpenInfo($condition);
        if (! $payment_info || in_array($payment_info['payment_code'], array(
            'offline',
            'predeposit'
        ))) {
            showMessage(L('payment_index_sys_not_support'), $url, 'html', 'error');
        }
        $model_pd = Model('predeposit');
        $model_pd -> editPdRecharge(array('pdr_amount' => $pdr_amount), array('pdr_sn' => $pay_sn));
        $order_info = $model_pd -> getPdRechargeInfo(array(
            'pdr_sn' => $pay_sn,
            'pdr_member_id' => $_SESSION['member_id']
        ));
        $order_info['subject'] = '预存款充值_' . $order_info['pdr_sn'];
        $order_info['order_type'] = 'predeposit';
        $order_info['client_ip'] = getIp();
        $order_info['pay_sn'] = $order_info['pdr_sn'];
        $order_info['pay_amount'] = $order_info['pdr_amount'];
        $order_info['pdr_payment_time'] = time();
        if (empty($order_info) || $order_info['pdr_payment_state'] == 1) {
            showMessage(Language::get('cart_order_pay_not_exists'), $url, 'html', 'error');
        }
        
        $buy_id = $_SESSION['member_id'];
        //检查当前买家信息是否存在
        $req_user = array('userId' => $buy_id);
        $resp = array();
        require_once BASE_CORE_PATH . DS . 'framework' . DS . 'function' . DS . 'payapi.php';
        $validResp = trade(C('alipay.check_user_url'), $req_user, $resp);
        if(empty($resp['userInfos']) || count($resp['userInfos']) == 0){
        	//判断是否存在该用户，不存在则把当前用户添加到系统
        	$member_info = Model('member') -> infoMember(array('member_id' => $buy_id));
        	$req_user = array(
        			'userId' => $buy_id,
        			'userName' => $member_info['member_name'],
        			'userMobile' => empty($member_info['member_tel']) ? '1300' .  $buy_id : $member_info['member_tel'],
        			'userCode' => $buy_id
        	);
        	$validResp = trade(C('alipay.add_user_url'), $req_user, $resp);
        	if($validResp === false){
        		exit('添加用户失败');
        	}
        }
        if($payment_code == 'alipay'){
			$this -> aliPay('predeposit', $order_info, $payment_info);
        }else if($payment_code == 'unionpay'){
        	$this -> unionPay('predeposit', $order_info, $payment_info);
        }else if($payment_code == 'weichat'){
        	$this -> weichat('predeposit', $order_info, $payment_info);
        }
        unset($pay_sn, $payment_code, $pdr_amount, $payment_info, $order_info);
    }
    
    /**
     * 支付接口返回
     */
    public function returnOp()
    {
        Language::read('home_payment_index');
        if ($_GET['extra_common_param'] == 'product_buy') {
            $url = SHOP_SITE_URL . "/index.php?act=member_order";
        } else {
            $url = SHOP_SITE_URL . "/index.php?act=predeposit";
        }
        
        $out_trade_no = $_GET['out_trade_no'];
        // 对外部交易编号进行非空判断
        if (! preg_match('/^\d{18}$/', $out_trade_no)) {
            showMessage(Language::get('para_error'), $url, '', 'html', 'error');
        }
        if (! in_array($_GET['extra_common_param'], array(
            'predeposit',
            'product_buy'
        ))) {
            showMessage(Language::get('para_error'), $url, '', 'html', 'error');
        }
        
        $condition = array();
        if ($_GET['extra_common_param'] == 'product_buy') {
            
            // 取得订单信息
            $model_order = Model('order');
            $condition['pay_sn'] = $out_trade_no;
            $order_pay_info = $model_order->getOrderPayInfo($condition);
            
            // 对订单信息进行非空判断
            if (empty($order_pay_info)) {
                showMessage('返回的交易号不存', $url, 'html', 'error');
            }
            if (intval($order_pay_info['api_pay_state'])) {
                showMessage(Language::get('payment_index_deal_order_success'), $url);
            }
            
            // 取得订单列表和API支付总金额
            $order_list = $model_order->getOrderList(array(
                'pay_sn' => $out_trade_no,
                'order_state' => ORDER_STATE_NEW
            ));
            if (empty($order_list)) {
                showMessage(Language::get('payment_index_deal_order_success'), $url);
            }
            $pay_amount = $api_pay_amount = 0;
            foreach ($order_list as $order_info) {
                $api_pay_amount += ncPriceFormat(floatval($order_info['order_amount']) - floatval($order_info['pd_amount']));
                $pay_amount += floatval($order_info['order_amount']);
            }
            $order_pay_info['pay_amount'] = $api_pay_amount;
        } elseif ($_GET['extra_common_param'] == 'predeposit') {
            $model_pd = Model('predeposit');
            $condition['pdr_sn'] = $out_trade_no;
            $order_pay_info = $model_pd->getPdRechargeInfo($condition);
            // 对订单信息进行非空判断
            if (empty($order_pay_info)) {
                showMessage('返回的交易号不存', $url, 'html', 'error');
            }
            if (intval($order_pay_info['pdr_payment_state'])) {
                showMessage(Language::get('payment_index_deal_pdr_success'), $url);
            }
        }
        
        // 取得支付接口信息
        $payment_code = $_GET['payment_code'];
        unset($_GET['payment_code']);
        $model_payment = Model('payment');
        $condition = array();
        $condition['payment_code'] = $payment_code;
        $payment_info = $model_payment->getPaymentOpenInfo($condition);
        if (! is_array($payment_info) || empty($payment_info)) {
            showMessage(Language::get('payment_index_miss_pay_method_data'), $url, 'html', 'error');
        }
        $payment_info['payment_config'] = unserialize($payment_info['payment_config']);
        $inc_file = BASE_PATH . DS . 'api' . DS . 'payment' . DS . $payment_info['payment_code'] . DS . $payment_info['payment_code'] . '.php';
        if (! file_exists($inc_file)) {
            showMessage(Language::get('payment_index_lose_file'), $url, 'html', 'error');
        }
        require_once ($inc_file);
        $payment_api = new $payment_info['payment_code']($payment_info, $order_pay_info);
        
        // 返回参数判断
        $verify = $payment_api->return_verify();
        if (! $verify) {
            showMessage(Language::get('payment_index_identify_fail'), $url, 'html', 'error');
        }
        $order_type = $payment_api->order_type;
        if (! in_array($order_type, array(
            'product_buy',
            'predeposit'
        ))) {
            showMessage(Language::get('payment_index_identify_fail'), $url, 'html', 'error');
        }
        
        // 取得支付结果
        $pay_result = $payment_api->getPayResult($_GET);
        if (! $pay_result) {
            showMessage('非常抱歉，您的订单支付没有成功，请您后尝试', $url, 'html', 'error');
        }
        
        // 支付成功后处理
        if ($order_type == 'predeposit') {
            $this->_updatePredeposit($payment_info['payment_code']);
        } elseif ($order_type == 'product_buy') {
            $this->_updateProduct_buy($payment_info['payment_code'], $order_list, $pay_amount);
        }
    }

    /**
     * 预存款充值在线支付成功后，更新数据表
     */
    private function _updatePredeposit($payment_code)
    {
        $url = SHOP_SITE_URL . "/index.php?act=predeposit&op=index";
        
        // 取得记录信息
        $model_pd = Model('predeposit');
        $condition = array();
        $condition['pdr_sn'] = $_GET['out_trade_no'];
        $condition['pdr_payment_state'] = 0;
        $recharge_info = $model_pd->getPdRechargeInfo($condition);
        if (! is_array($recharge_info) || empty($recharge_info)) {
            showMessage(Language::get('predeposit_payment_pay_fail'), $url, 'html', 'error');
        }
        
        // 取支付方式信息
        $model_payment = Model('payment');
        $condition = array();
        $condition['payment_code'] = $payment_code;
        $payment_info = $model_payment->getPaymentOpenInfo($condition);
        if (! $payment_info || $payment_info['payment_code'] == 'offline') {
            showMessage(L('payment_index_sys_not_support'), '', 'html', 'error');
        }
        
        $condition = array();
        $condition['pdr_sn'] = $recharge_info['pdr_sn'];
        $condition['pdr_payment_state'] = 0;
        $update = array();
        $update['pdr_payment_state'] = 1;
        $update['pdr_payment_time'] = TIMESTAMP;
        $update['pdr_payment_code'] = $payment_code;
        $update['pdr_payment_name'] = $payment_info['payment_name'];
        $update['pdr_trade_sn'] = $_GET['trade_no'];
        
        try {
            $model_pd->beginTransaction();
            // 更改充值状态
            $state = $model_pd->editPdRecharge($update, $condition);
            if (! $state) {
                throw Exception(Language::get('predeposit_payment_pay_fail'));
            }
            // 变更会员预存款
            $data = array();
            $data['member_id'] = $recharge_info['pdr_member_id'];
            $data['member_name'] = $recharge_info['pdr_member_name'];
            $data['amount'] = $recharge_info['pdr_amount'];
            $data['pdr_sn'] = $recharge_info['pdr_sn'];
            $model_pd->changePd('recharge', $data);
            $model_pd->commit();
        } catch (Exception $e) {
            $model_pd->rollback();
            showMessage($e->getMessage(), $url, 'html', 'error');
        }
        
        // 财付通需要输出反馈
        if ($payment_code == 'tenpay') {
            $url = SHOP_SITE_URL . "/index.php?act=payment&op=payment_success&predeposit=1";
            showMessage(Language::get('payment_index_deal_pdr_success'), $url, 'tenpay');
        } else {
            showMessage(Language::get('payment_index_deal_pdr_success'), $url);
        }
    }

    /**
     * 购买商品在线支付成功后，更新数据表(财付通异步也使用return,不能使用SESSION)
     */
    private function _updateProduct_buy($payment_code, $order_list, $pay_amount)
    {
        $url = SHOP_SITE_URL . "/index.php?act=member_order";
        $out_trade_no = $_GET['out_trade_no'];
        
        if ($_GET['trade_no'] != '') {
            $trade_no = $_GET['trade_no'];
        }
        
        $model_payment = Model('payment');
        $result = $model_payment->updateProductBuy($out_trade_no, $payment_code, $order_list, $trade_no);
        if (! empty($result['error'])) {
            showMessage($result['error'], $url, 'html', 'error');
        }
        
        // 财付通需要输出反馈
        if ($payment_code == 'tenpay') {
            $url = SHOP_SITE_URL . "/index.php?act=payment&op=payment_success";
            showMessage(Language::get('payment_index_deal_order_success'), $url, 'tenpay');
        } else {
            redirect(SHOP_SITE_URL . '/index.php?act=buy&op=pay_ok&pay_sn=' . $out_trade_no . '&pay_amount=' . ncPriceFormat($pay_amount));
        }
    }

    /**
     * 支付成功
     */
    public function payment_successOp()
    {
        Language::read('home_payment_index');
        if ($_GET['predeposit']) {
            $url = SHOP_SITE_URL . "/index.php?act=predeposit";
            $lang = Language::get('payment_index_deal_pdr_success');
        } else {
            $url = SHOP_SITE_URL . "/index.php?act=member_order";
            $lang = Language::get('payment_index_deal_order_success');
        }
        showMessage($lang, $url);
    }

	/**发送消息*/
	private function sendMessage($buyer_id,$pay_sn){
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
}

function logResult($word='') {
	$fp = fopen("log.txt","a");
	flock($fp, LOCK_EX) ;
	fwrite($fp,"执行日期：".strftime("%Y%m%d%H%M%S",time())."\n".$word."\n");
	flock($fp, LOCK_UN);
	fclose($fp);
}