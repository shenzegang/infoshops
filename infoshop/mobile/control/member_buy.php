<?php
/**
 * 购买
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

class member_buyControl extends mobileMemberControl
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 购物车、直接购买第一步:选择收获地址和配置方式
     */
    public function buy_step1Op()
    {
        $cart_id = explode(',', $_POST['cart_id']);
        
        $model_buy = Model('buy');
        
        $result = $model_buy->buyStep1($cart_id, $_POST['ifcart'], $_POST['invalid_cart'], $this->member_info['member_id'], $this->member_info['store_id']);
        if (isset($result['error'])) {
            output_error($result['error']);
        }
        
        
        // 整理数据
        $store_cart_list = array();
        foreach ($result['store_cart_list'] as $key => $value) {
            $store_cart_list[$key]['goods_list'] = $value;
            $store_cart_list[$key]['store_goods_total'] = $result['store_goods_total'][$key];
            $store_cart_list[$key]['store_gift_total'] = $result['store_gift_total'][$key];
            if (! empty($result['store_premiums_list'][$key])) {
                $result['store_premiums_list'][$key][0]['premiums'] = true;
                $result['store_premiums_list'][$key][0]['goods_total'] = 0.00;
                $store_cart_list[$key]['goods_list'][] = $result['store_premiums_list'][$key][0];
            }
            $store_cart_list[$key]['store_mansong_rule_list'] = $result['store_mansong_rule_list'][$key];
            $store_cart_list[$key]['store_voucher_list'] = $result['store_voucher_list'][$key];
            if (! empty($result['cancel_calc_sid_list'][$key])) {
                $store_cart_list[$key]['freight'] = '0';
                $store_cart_list[$key]['freight_message'] = $result['cancel_calc_sid_list'][$key]['desc'];
            } else {
                $store_cart_list[$key]['freight'] = '1';
            }
            $store_cart_list[$key]['store_name'] = $value[0]['store_name'];
        }
        
        $buy_list = array();
        $buy_list['store_cart_list'] = $store_cart_list;
        $buy_list['freight_hash'] = $result['freight_list'];
        $buy_list['address_info'] = $result['address_info'];
        $buy_list['ifshow_offpay'] = $result['ifshow_offpay'];
        $buy_list['vat_hash'] = $result['vat_hash'];
        $buy_list['inv_info'] = $result['inv_info'];
        $buy_list['available_predeposit'] = $result['available_predeposit'];
        output_data($buy_list);
    }

    /**
     * 购物车、直接购买第二步:保存订单入库，产生订单号，开始选择支付方式
     */
    public function buy_step2Op()
    {
        $param = array();
        $param['ifcart'] = $_POST['ifcart'];
        $param['cart_id'] = explode(',', $_POST['cart_id']);
        $param['address_id'] = $_POST['address_id'];
        $param['vat_hash'] = $_POST['vat_hash'];
        $param['offpay_hash'] = $_POST['offpay_hash'];
        $param['pay_name'] = $_POST['pay_name'];
        $param['invoice_id'] = $_POST['invoice_id'];
        $param['voucher'] = $_POST['voucher'];
        // 手机端暂时不做支付留言，页面内容太多了
        // $param['pay_message'] = json_decode($_POST['pay_message']);
        $param['pd_pay'] = $_POST['pd_pay'] ? $_POST['pd_pay'] : 0;
        $param['password'] = $_POST['password'] ? $_POST['password'] : '';
        $param['order_from'] = $_POST['order_from'];
        $model_buy = Model('buy');
        
        $pay_sn = $model_buy -> buyStep2($param, $this->member_info['member_id'], $this->member_info['member_name'], $this->member_info['member_email']);
        if (! empty($pay_sn['error'])) {
            output_error($pay_sn['error']);
        }
        $model_order = Model('order');
        $ord_info = $model_order->getOrderInfo(array(
            'pay_sn' => $pay_sn['pay_sn'],
            'buyer_id' => $this->member_info['member_id']
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
        $this->send_notice($this->member_info['member_id'], 'email_tobuyer_new_order_notify', array(
            
            'site_name' => $GLOBALS['setting_config']['site_name'],
            
            'site_url' => SHOP_SITE_URL,
            'goods_list' => $goodshtml,
            'buyer_name' => $this->member_info['member_name'],
            'order_id' => $ord_info['order_id'],
            'order_sn' => $pay_sn['pay_sn']
        )
        , false);
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
            
            'order_sn' => $pay_sn['pay_sn']
        )
        , false);
        // 给买家发送短信
        $this->send_sms($this->member_info['member_id'], 'sms_tobuyer_new_order_notify', array(
            
            'site_name' => $GLOBALS['setting_config']['site_name'],
            
            'site_url' => SHOP_SITE_URL,
            
            'buyer_name' => $this->member_info['member_name'],
            'order_id' => $ord_info['order_id'],
            'order_sn' => $pay_sn['pay_sn']
        )
        , false, array(
            'store_id' => $ord_info['store_id'],
            'dateline' => mktime(),
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
            
            'order_sn' => $pay_sn['pay_sn']
        )
        , false, array(
            'store_id' => $ord_info['store_id'],
            'dateline' => mktime(),
            'tomember_id' => $rsst['member_id'],
            'tomember_name' => $ord_info['store_name']
        ));
        
        output_data(array(
            'pay_sn' => $pay_sn
        ));
    }

    /**
     * 验证密码
     */
    public function check_passwordOp()
    {
        if (empty($_POST['password'])) {
            output_error('参数错误');
        }
        
        $model_member = Model('member');
        
        $member_info = $model_member->getMemberInfo(array(
            'member_id' => $this->member_info['member_id']
        ));
        if ($member_info['member_passwd'] == md5($_POST['password'])) {
            output_data('1');
        } else {
            output_error('密码错误');
        }
    }
    
    /**
     * 验证支付密码
     *
     */
    public function isValidPaypasswdOp(){
        //$model_member = Model('member'); 
        //$member_info = $model_member->getMemberInfo(array('member_id' => $this->member_info['member_id']));
        $mem_info = Model('UserService') -> findUserinfo($this->member_info['member_id']);
        $paypasswd = md5(trim($_POST['pay_passwd']));
       
        if(!empty($paypasswd) && $paypasswd == $mem_info['payPassword']){
            
            output_data('1');
        }else{
            output_error('支付密码错误');
        }
    }
    
    /*
     *  设置支付密码
     */
    public function paypasswdOp(){
        $model_member = Model('member');
        $member_info = $model_member->getMemberInfo(array(
            'member_id' => $this->member_info['member_id']
        ));
        $update = Model('member') -> updateMember(array('member_paypasswd' => md5($_POST["paypasswd"])), $this->member_info['member_id']);
        if(Model('UserService') -> editPay_passwd($this->member_info['member_id'], md5($_POST["paypasswd"])) === false){
            output_error('支付密码设置失败');
        }else{
            output_data('1');
        }
    }

    /**
     * 更换收货地址
     */
    public function change_addressOp()
    {
        $model_buy = Model('buy');
        
        $data = $model_buy->changeAddr($_POST['freight_hash'], $_POST['city_id'], $_POST['area_id'], $this->member_info['member_id']);
        if (! empty($data) && $data['state'] == 'success') {
            output_data($data);
        } else {
            output_error('地址修改失败');
        }
    }
}

