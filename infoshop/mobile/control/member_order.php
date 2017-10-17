<?php
/**
 * 我的订单
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

class member_orderControl extends mobileMemberControl
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 订单列表
     */
    public function order_listOp()
    {
        $model_order = Model('order');
        
        $condition = array();
        $condition['buyer_id'] = $this->member_info['member_id'];
        
        $order_list_array = $model_order->getOrderList($condition, $this->page, '*', 'order_id desc', '', array(
            'order_goods'
        ));
        
        $order_group_list = array();
        $order_pay_sn_array = array();
        foreach ($order_list_array as $value) {
            // 显示取消订单
            $value['if_cancel'] = $model_order->getOrderOperateState('buyer_cancel', $value);
            // 显示收货
            $value['if_receive'] = $model_order->getOrderOperateState('receive', $value);
            // 显示锁定中
            $value['if_lock'] = $model_order->getOrderOperateState('lock', $value);
            // 显示物流跟踪
            $value['if_deliver'] = $model_order->getOrderOperateState('deliver', $value);
            
            $order_group_list[$value['pay_sn']]['order_list'][] = $value;
            
            // 如果有在线支付且未付款的订单则显示合并付款链接
            if ($value['order_state'] == ORDER_STATE_NEW) {
                $order_group_list[$value['pay_sn']]['pay_amount'] += $value['order_amount'];
            }
            $order_group_list[$value['pay_sn']]['add_time'] = $value['add_time'];
            $order_group_list[$value['pay_sn']]['pay_code'] = $value['payment_code'];
            // 记录一下pay_sn，后面需要查询支付单表
            $order_pay_sn_array[] = $value['pay_sn'];
        }
        
        $new_order_group_list = array();
        foreach ($order_group_list as $key => $value) {
            $value['pay_sn'] = strval($key);
            $new_order_group_list[] = $value;
        }
        
        $page_count = $model_order->gettotalpage();
        
        output_data(array(
            'order_group_list' => $new_order_group_list
        ), mobile_page($page_count));
    }

    /**
     * 取消订单
     */
    public function order_cancelOp()
    {
        $extend_msg = '其它原因';
        $this->change_order_state('order_cancel', $extend_msg);
    }

    /**
     * 订单确认收货
     */
    public function order_receiveOp()
    {
        $this->change_order_state('order_receive');
    }

    /**
     * 修改订单状态
     */
    private function change_order_state($state_type, $extend_msg = '')
    {
        $order_id = intval($_POST['order_id']);
        
        $model_order = Model('order');
        
        $condition = array();
        $condition['order_id'] = $order_id;
        $condition['buyer_id'] = $this->member_info['member_id'];
        $order_info = $model_order->getOrderInfo($condition);
        
        $result = $model_order->memberChangeState($state_type, $order_info, $this->member_info['member_id'], $this->member_info['member_name'], $extend_msg);
        
        if (empty($result['error'])) {
            // 暂时不处理
            $rsst = Model('store')->getStoreInfo(array(
                'store_id' => $order_info['store_id']
            ));
            if ($state_type == 'order_cancel') {
                // 邮箱
                $this->send_notice($rsst['member_id'], 'email_toseller_cancel_order_notify', array(
                    
                    'site_name' => $GLOBALS['setting_config']['site_name'],
                    
                    'site_url' => SHOP_SITE_URL,
                    'seller_name' => $order_info['store_name'],
                    'reason' => $extend_msg,
                    'buyer_name' => $order_info['buyer_name'],
                    'order_id' => $order_id,
                    'order_sn' => $order_info['order_sn']
                )
                , false);
                // 短信
                $this->send_sms($rsst['member_id'], 'sms_toseller_cancel_order_notify', array(
                    
                    'site_name' => $GLOBALS['setting_config']['site_name'],
                    
                    'site_url' => SHOP_SITE_URL,
                    'seller_name' => $order_info['store_name'],
                    'reason' => $extend_msg,
                    'buyer_name' => $order_info['buyer_name'],
                    'order_id' => $order_id,
                    'order_sn' => $order_info['order_sn']
                )
                , false, array(
                    'store_id' => $order_info['store_id'],
                    'dateline' => mktime(),
                    'tomember_id' => $rsst['member_id'],
                    'tomember_name' => $rsst['member_name']
                ));
            } elseif ($state_type == 'order_receive') {
                // 邮箱
                $this->send_notice($rsst['member_id'], 'email_toseller_finish_notify', array(
                    
                    'site_name' => $GLOBALS['setting_config']['site_name'],
                    
                    'site_url' => SHOP_SITE_URL,
                    'seller_name' => $order_info['store_name'],
                    
                    'buyer_name' => $order_info['buyer_name'],
                    'order_id' => $order_id,
                    'order_sn' => $order_info['order_sn']
                )
                , false);
                // 短信
                $this->send_sms($rsst['member_id'], 'sms_toseller_finish_notify', array(
                    
                    'site_name' => $GLOBALS['setting_config']['site_name'],
                    
                    'site_url' => SHOP_SITE_URL,
                    'seller_name' => $order_info['store_name'],
                    
                    'buyer_name' => $order_info['buyer_name'],
                    'order_id' => $order_id,
                    'order_sn' => $order_info['order_sn']
                )
                , false, array(
                    'store_id' => $order_info['store_id'],
                    'dateline' => mktime(),
                    'tomember_id' => $rsst['member_id'],
                    'tomember_name' => $rsst['member_name']
                ));
            }
            output_data('1');
        } else {
            output_error($result['error']);
        }
    }
}
