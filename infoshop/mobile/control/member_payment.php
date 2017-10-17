<?php
/**
 * 支付
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

class member_paymentControl extends mobileMemberControl
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 支付
     */
    public function payOp()
    {
        $pay_sn = $_GET['pay_sn'];
        $payment_code = $_GET['pay_code'];
        $model_payment = Model('payment');
        $result = $model_payment->productBuy($pay_sn, $payment_code, $this->member_info['member_id']);
       
        if (! empty($result['error'])) { 
            output_error($result['error']);
        }
        
        if($payment_code == 'alipay'){
        	$this -> aliPay('product_buy', $result['order_pay_info'], $result['payment_info']);
        }else if($payment_code == 'unionpay'){			//银联支付入口
        	$this -> unionPay('product_buy', $result['order_pay_info'], $result['payment_info']);
        }else if($payment_code == 'predeposit'){
            $result = $model_payment -> pdPay($result, array(), $this->member_info['member_id'], $this->member_info['member_name']);
            
            if(!empty($result['error'])){
                showMessage($result['error'], $url, 'html', 'error');
            }else{
                //redirect('index.php?act=buy&op=pay_ok&pay_sn=' . $pay_sn . '&pay_amount=' . ncPriceFormat($result['pay_amount']));
                //sj 20150902 预存款支付成功 发送消息
                $this -> sendMessage($this->member_info['member_id'],$pay_sn);
                $this -> payment_successOp();
            }
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
    		$req_trade = array(
    				'userId' => $this->member_info['member_id'],
    				'exterInvokeIp' => $order_info['client_ip'],
    				'outTradeNo' => $order_info['pay_sn'],
    				'subject' => $order_info['subject'],
    				'totalFee' => $order_info['pay_amount'],
    				'body' => $order_info['pay_sn'],
    				'notifyUrl' => C('alipay.notify_url'),
    				'orderType' => $payment_info['payment_code'],
    				'returnUrl' => C('alipay.return_url')
    		);
    	}else{
    		//为商品直接购买
    		$req_trade = array(
    				'userId' => $this->member_info['member_id'],
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
    	logResult(print_r($order_pay_info, 1));
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
     * 第三方在线支付接口
     */
    private function _api_pay($order_pay_info, $payment_info)
    {
        $inc_file = BASE_PATH . DS . 'api' . DS . 'payment' . DS . $payment_info['payment_code'] . DS . $payment_info['payment_code'] . '.php';
        if (! file_exists($inc_file)) {
            output_error('支付接口不存在');
        }
        require_once ($inc_file);
        $param = array();
        $param = unserialize($payment_info['payment_config']);
        $param['order_sn'] = $order_pay_info['pay_sn'];
        $param['order_amount'] = $order_pay_info['pay_amount'];
        $param['sign_type'] = 'MD5';
        $payment_api = new $payment_info['payment_code']($param);
        $return = $payment_api->submit();
        echo $return;
        exit();
    }
    
    /**
     * 支付成功
     */
    public function payment_successOp()
    {
        
        $url = SHOP_SITE_URL . "/index.php?act=member_order";
        redirect($url);
        
        
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
            'buyer_name' => $this->member_info['member_name'],
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
            'buyer_name' => $this->member_info['member_name'],
            'order_id' => $ord_info['order_id'],
            //shijian 8-21 发送邮件的订单号不应该为支付单号
            'order_sn' => $ord_info['order_sn']
        ), false, array(
            'store_id' => $ord_info['store_id'],
            'dateline' => time(),
            'tomember_id' => $this->member_info['member_id'],
            'tomember_name' => $this->member_info['member_name']
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
