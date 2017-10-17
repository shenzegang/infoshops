<?php
/**
 * 支付方式
 * @copyright  Copyright (c) 2003-2015 B2B2C Inc. (http://www.infol.com.cn)
 * @license    http://www.infol.com.cn
 * @link       http://www.infol.com.cn
 * @since      File available since Release v1.1
 */
defined('CorShop') or exit('Access Invalid!');

class paymentModel extends Model
{

    /**
     * 开启状态标识
     *
     * @var unknown
     */
    const STATE_OPEN = 1;

    public function __construct()
    {
        parent::__construct('payment');
    }

    /**
     * 读取单行信息
     *
     * @param
     *
     * @return array 数组格式的返回结果
     */
    public function getPaymentInfo($condition = array())
    {
        return $this->where($condition)->find();
    }

    /**
     * 读开启中的取单行信息
     *
     * @param
     *
     * @return array 数组格式的返回结果
     */
    public function getPaymentOpenInfo($condition = array())
    {
        $condition['payment_state'] = self::STATE_OPEN;
        return $this->where($condition)->find();
    }

    /**
     * 读取多行
     *
     * @param
     *
     * @return array 数组格式的返回结果
     */
    public function getPaymentList($condition = array())
    {
        return $this->where($condition)->select();
    }

    /**
     * 读取开启中的支付方式
     *
     * @param
     *
     * @return array 数组格式的返回结果
     */
    public function getPaymentOpenList($condition = array())
    {
        $condition['payment_state'] = self::STATE_OPEN;
        return $this->where($condition)
            ->key('payment_code')
            ->select();
    }

    /**
     * 更新信息
     *
     * @param array $param
     *            更新数据
     * @return bool 布尔类型的返回结果
     */
    public function editPayment($data, $condition)
    {
        return $this->where($condition)->update($data);
    }

    /**
     * 读取支付方式信息by Condition
     *
     * @param
     *
     * @return array 数组格式的返回结果
     */
    public function getRowByCondition($conditionfield, $conditionvalue)
    {
        $param = array();
        $param['table'] = 'payment';
        $param['field'] = $conditionfield;
        $param['value'] = $conditionvalue;
        $result = Db::getRow($param);
        return $result;
    }

    /**
     * author:brady
     * createData:2015-09-02
     * 购买商品
     * @return array(	返回支付相关信息
     * 				$order_pay_info	array( 支付信息，订单内容
     * 						$pay_amount float 订单总金额
     * 						$order_list array(...) 订单信息列表
     * 					)
     * 				$payment_info	支付方式信息
     * 			),
     * 			array(
     * 				$error 返回错误信息	
     * 			)
     */
    public function productBuy($pay_sn, $payment_code, $member_id){
    	/**
		 * @package $pay_sn bigint, 支付单号
		 * @package $payment_code string, 支付方式
    	 */
    	//更新用户账户信息
    	list($accountBalance, $blockedBalance) = Model('UserService') -> getAmount($member_id);
    	$update = Model('member') -> updateMember(array('available_predeposit' => ncPriceFormat(floatval($accountBalance) + floatval($blockedBalance))), $member_id);
        if(!$update){
        	return array('error' => '更新账户信息时出错'); 
        }
    	$condition = array();
        $condition['payment_code'] = $payment_code;
        //查询支付方式信息
        $payment_info = $this->getPaymentOpenInfo($condition);
        if (!$payment_info) {
            return array('error' => '系统不支持选定的支付方式');
        }

        // 验证订单信息
        $model_order = Model('order');
        $order_pay_info = $model_order->getOrderPayInfo(array(
            'pay_sn' => $pay_sn,
            'buyer_id' => $member_id
        ));
        
        if (empty($order_pay_info)) {
            return array(
                'error' => '该订单不存在'
            );
        }
        $order_pay_info['subject'] = '商品购买_' . $order_pay_info['pay_sn'];
        $order_pay_info['order_type'] = 'product_buy';
        $order_pay_info['buyer_id'] = $member_id;
        $order_pay_info['client_ip'] = getIp();
        // 重新计算在线支付且处于待支付状态的订单总额
        $condition = array();
        $condition['pay_sn'] = $pay_sn;
        $condition['order_state'] = ORDER_STATE_NEW;
        $order_list = $model_order->getOrderList($condition, '', 'order_id,order_sn,order_amount,pd_amount,is_gift');
        
        if (empty($order_list)) {
            return array('error' => '该订单不存在');
        }

        // 计算本次需要在线支付的订单总金额
        $pay_amount = 0;
        foreach ($order_list as $order_info) {
            $pay_amount += ncPriceFormat(floatval($order_info['order_amount']) - floatval($order_info['pd_amount']) - floatval($order_info['welfare_amount']));
        }

        // 如果为空，说明已经都支付过了或已经取消或者是价格为0的商品订单，全部返回
        if (empty($pay_amount)) {
            return array(
                'error' => '订单金额为0，不需要支付'
            );
        }
        $order_pay_info['pay_amount'] = $pay_amount;
        $order_pay_info['order_list'] = $order_list;
		
        return array('order_pay_info' => $order_pay_info, 'payment_info' => $payment_info);
    }
	
	/**
	 * author:Brady
	 * createDate:2015-09-02
     * 购买订单支付成功后修改订单状态
     * @param $out_trade_no int 异步回调返回的订单号
     * @param $payment_code string 支付类型（alipay => 支付宝， unionpay => 银联支付, weichat => 微信支付）
     * @param $order_list Array 订单列表
     * @param $trade_no 平台交易单号，通过此单号即可去支付宝等平台查询交易
     * @param $order_pay_info Array 支付信息
     * @return array(
     * 			'success' => 成功,
     * 			'error' => 返回错误，回滚
     * 		)
     */
    public function updateProductBuy($out_trade_no, $payment_code, $order_list, $trade_no, $order_pay_info){
    	/**
    	 * @package $order_pay_info array
    	 * 			buyer_id  int,  买家id
    	 * 			pay_amount float, 支付金额					
    	 * @package $out_trade_no bigint 支付单号						
    	 */
    	//实例化相应的模型和初始化相应变量
    	$model_order = Model('order');
    	$model_pd = Model('predeposit');
    	$mem_info = Model('member') -> infoMember(array('member_id' => $order_pay_info['buyer_id']));
    	$payment = array('alipay' => '支付宝', 'unionpay' => '银联支付');
    	try {
            $model_order -> beginTransaction();
			/*
			 * 更改支付订单状态
			 */
            $data = array();
            $data['api_pay_state'] = 1;
            $update = $model_order -> editOrderPay($data, array(
                'pay_sn' => $out_trade_no
            ));
            if(!$update) {
                throw new Exception('更新订单状态失败');
            }
			/*
			 * 更改订单状态
			 */
            $data = array();
            $data['order_state'] = ORDER_STATE_PAY;
            $data['payment_time'] = TIMESTAMP;
            $data['payment_code'] = $payment_code;
            $update = $model_order -> editOrder($data, array(
                'pay_sn' => $out_trade_no,
                'order_state' => ORDER_STATE_NEW
            ));
            if (! $update) {
                throw new Exception('更新订单状态失败');
            }
            
            /*
             * 生成预存款充值订单
            */
            /*
            $data = array();
            $data['pdr_sn'] = $pay_sn = $model_pd -> makeSn();
            $data['pdr_member_id'] = $order_pay_info['buyer_id'];
            $data['pdr_member_name'] = $mem_info['member_name'];
            $data['pdr_amount'] = $order_pay_info['pay_amount'];
            $data['pdr_add_time'] = TIMESTAMP;
            $data['pdr_payment_state'] = 1;
            $data['pdr_payment_code'] = $payment_code;
            $data['pdr_payment_name'] = $payment[$payment_code];
            $data['pdr_trade_sn'] = $trade_no;
            
            $insert = $model_pd -> addPdRecharge($data);
            if(!$insert){
            	throw new Exception('提交充值订单失败');
            }
            */
            //变更商城系统预存款账户,预存款充值
         
            $data = array();
            $data['member_id'] = $order_pay_info['buyer_id'];
            $data['member_name'] = $mem_info['member_name'];
            $data['amount'] = $order_pay_info['pay_amount'];
            //$data['pdr_sn'] = $pay_sn;
            $model_pd -> changePd('recharge', $data, false);
           
            foreach ($order_list as $order_info) {
            	// 如果有预存款支付的，彻底扣除冻结的预存款
            	/*
                	$pd_amount = floatval($order_info['pd_amount']);
               		if ($pd_amount > 0) {
	                    $data_pd = array();
	                    $data_pd['member_id'] = $order_info['buyer_id'];
	                    $data_pd['member_name'] = $order_info['buyer_name'];
	                    $data_pd['amount'] = $order_info['pd_amount'];
	                    $data_pd['order_sn'] = $order_info['order_sn'];
	                    $model_pd->changePd('order_comb_pay', $data_pd);
	                }
	            */
                /*
                 * 记录订单日志
                 */
                $data = array();
                $data['order_id'] = $order_info['order_id'];
                $data['log_role'] = 'buyer';
                $data['log_msg'] = L('order_log_pay') . ' ( 支付平台交易号 : ' . $trade_no . ' )';
                $data['log_orderstate'] = ORDER_STATE_PAY;
                $insert = $model_order -> addOrderLog($data);
                if (!$insert) {
                    throw new Exception('记录订单日志出现错误');
                }
                //使用预存款支付，冻结预存款
                $data = array();
                $data['member_id'] = $order_pay_info['buyer_id'];
                $data['member_name'] = $mem_info['member_name'];
                $data['amount'] = $order_info['order_amount'];
                $data['order_sn'] = $order_info['order_sn'];
                $model_pd -> changePd('order_freeze', $data, false);
                
                /*
                 * 调用支付系统，使用预存款支付
                */
                require_once BASE_CORE_PATH . DS . 'framework' . DS . 'function' . DS . 'payapi.php';
                $resp = array();
                $req = array('orderCode' => $order_info['order_sn'], 'payPassword' => $mem_info['member_paypasswd'], 'payType' => $payment_code);
               	$validResp = trade(C('predeposit.pay_url'), $req, $resp);
               	if(!$validResp || $resp['returnStatus'] != '000'){
                	throw new Exception('提交支付订单失败' . $resp['returnMsg']);
                }
                
            }
            unset($mem_info, $payment);
            $model_order->commit();
            return array(
                'success' => true
            );
        } catch (Exception $e) {
            $model_order->rollback();
            return array(
                'error' => $e -> getMessage()
            );
        }
    }


    /**
     * 更新哈金豆数量
     *
     * @param
     *            $input_voucher_list
     * @throws Exception
     */
    public function updatePoints($member_id, $member_name, $points, $sn)
    {

        // 扣除会员哈金豆
        $points_model = Model('points');
        $insert_arr['pl_memberid'] = $member_id;
        $insert_arr['pl_membername'] = $member_name;
        $insert_arr['pl_points'] = - $points;
        $insert_arr['point_ordersn'] = $sn;

        $return = $points_model->savePointsLog('pointorder', $insert_arr, true);

        if (! $return)
            throw new Exception('积分更新失败');
    }


    /**
     * author:Brady
     * createDate:2015-09-02
     * 预存款支付处理
     * 			(更新用户账户余额， 操作预存款操作日志， 更新订单操作)
     * @param Array $pay_info
     * @param Array $input
     * @param int $member_id
     * @param int $member_name
     * @throws Exception
     */

    public function pdPay($pay_info, $input, $member_id, $member_name)
    {
    	$pay_info = $pay_info['order_pay_info'];
    	$buyer_info = Model('member') -> infoMember(array('member_id' => $member_id));
		$model_order = Model('order');
    	try {
    		// 开始事务
    		$model_order -> beginTransaction();
    		//用户的可用预存款
    		$available_pd_amount = 0;
    		//查询用户可用余额
    		require_once BASE_CORE_PATH . DS . 'framework' . DS . 'function' . DS . 'payapi.php';
    		$resp = array();
    		$req = array('userId' => $member_id);
    		$validResp = trade(C('alipay.checkUser_url'), $req, $resp);
    		$userInfo = (array)$resp['userInfo'];
    		if($resp['returnStatus'] == '000' && !empty($userInfo)){
    			$available_pd_amount = ncPriceFormat(floatval($userInfo['accountBalance']) + floatval($userInfo['blockedBalance']));
    		}

    		if ($available_pd_amount <= 0){
    			throw new Exception('预存款账户余额不足，支付失败');
    		}
    		$pay_amount = floatval($pay_info['pay_amount']);

    		if($available_pd_amount >= $pay_amount){
    			$available_pd_amount -= $pay_amount;
    			$freeze_pd_predeposit += $pay_amount;
    			$data = array('available_predeposit' => $available_pd_amount, 'freeze_predeposit' => $freeze_pd_predeposit);
    			$update = $this -> table('member') -> where(array('member_id' => $member_id)) -> update($data);
				
    			$data_log = array('lg_member_id' => $member_id,'lg_member_name' => $member_name, 'lg_add_time' => TIMESTAMP,
    					'lg_av_amount' => -$pay_amount, 'lg_freeze_amount' => $pay_amount, 'lg_desc' => '下单，支付预存款，支付单号: ' . $pay_info['pay_sn']);
    			$insert = $this -> table('pd_log') -> insert($data_log);

    			if (!$update && !$insert) {
    				throw new Exception('更新用户账户余额失败');
    			}
    		}else{
    			throw new Exception('预存款账户余额不足，支付失败');
    		}
    		//更新支付订单状态
    		$data = array();
    		$data['api_pay_state'] = 1;
    		$update = $model_order->editOrderPay($data, array('pay_sn' => $pay_info['pay_sn']));
    		if(!$update) {
    			throw new Exception('更新订单状态失败');
    		}
			
    		//循环订单列表，修改订单状态，记录订单日志
    		foreach($pay_info['order_list'] as $order_info){
    			$data = array();
    			$data['order_id'] = $order_info['order_id'];
    			$data['log_role'] = 'buyer';
    			$data['log_msg'] = L('order_log_pay');
    			$data['log_orderstate'] = ORDER_STATE_PAY;
    			$insert = $model_order -> addOrderLog($data);
    			if (!$insert) {
    				throw new Exception('记录订单日志出现错误');
    			}

    			// 订单状态 置为已支付
    			$data_order = array();
    			$data_order['order_state'] = ORDER_STATE_PAY;
    			$data_order['payment_time'] = TIMESTAMP;
    			$data_order['payment_code'] = 'predeposit';
    			$data_order['pd_amount'] = $order_info['order_amount'];
    			$result = $model_order -> editOrder($data_order, array('order_id' => $order_info['order_id']));
    			if (! $result) {
    				throw new Exception('订单更新失败');
    			}

                //20150824 tjz修改
                /*
                if ($order_info['is_gift']==1){
                    // 是积分兑换 更新用户账户积分
                    $conditionOrderGood=array();
                    $conditionOrderGood['order_id']=$order_info['order_id'];
                    $order_Goods=$model_order->getOrderGoodsInfo($conditionOrderGood);
                    if (!empty($order_Goods['gift_points'])) {
                        $order_sn = $order_info['order_sn'];
                        $this->updatePoints($member_id, $member_name, $order_Goods['gift_points'], $order_sn);
                    }
                }
				*/
    			if($order_info['is_gift'] == 1){
    				$order_Goods = $model_order -> getOrderGoodsInfo(array('order_id' => $order_info['order_id']));
    				if(!empty($order_Goods['gift_points'])) {
    					$order_sn = $order_info['order_sn'];
    					$this -> updatePoints($member_id, $member_name, $order_Goods['gift_points'], $order_sn);
    				}
    			}
    			
    			require_once BASE_CORE_PATH . DS . 'framework' . DS . 'function' . DS . 'payapi.php';
    			$resp = array();
    			$req = array('orderCode' => $order_info['order_sn'], 'payPassword' => $buyer_info['member_paypasswd'], 'payType' => 'predeposit');
                
    			$validResp = trade(C('predeposit.pay_url'), $req, $resp);
    			if(!$validResp || $resp['returnStatus'] != '000'){
    				throw new Exception('操作失败!'.$resp['returnMsg']);
    			}
    		}
    		// 提交事务
    		$model_order -> commit();
    		return array('pay_amount' => $pay_amount);
    	} catch (Exception $e) {
    		// 回滚事务
    		$model_order -> rollback();
    		return array('error' => $e -> getMessage());
    	}
    }
}
