<?php
/**
 * 买家 我的订单
 *
 * @copyright  Copyright (c) 2007-2014 B2B2C Inc. (http://www.infol.com.cn)
 * @license    http://www.infol.com.cn
 * @link       http://www.infol.com.cn
 * @since      File available since Release v1.1
 */
defined('CorShop') or exit('Access Invalid!');

class member_orderControl extends BaseMemberControl
{

    public function __construct()
    {
        parent::__construct();
        Language::read('member_member_index');
    }

    /**
     * 买家我的订单，以总订单pay_sn来分组显示
     */
    public function indexOp()
    {
        $model_order = Model('order');

        $model_complain=Model('complain');

        $model_evaluate_goods = Model('evaluate_goods');
        
        // 搜索
        $condition = array();
        $condition['buyer_id'] = $_SESSION['member_id'];
        if ($_GET['order_sn'] != '') {
            $condition['order_sn'] = $_GET['order_sn'];
        }
        $if_start_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/', $_GET['query_start_date']);
        $if_end_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/', $_GET['query_end_date']);
        $start_unixtime = $if_start_date ? strtotime($_GET['query_start_date']) : null;
        $end_unixtime = $if_end_date ? strtotime($_GET['query_end_date']) : null;
        if ($start_unixtime || $end_unixtime) {
            $condition['add_time'] = array(
                'time',
                array(
                    $start_unixtime,
                    $end_unixtime
                )
            );
        }
        if ($_GET['state_type'] != '') {
            $condition['order_state'] = str_replace(array(
                'state_new',
                'state_pay',
                'state_send',
                'state_success',
                'state_noeval',
                'state_cancel'
            ), array(
                ORDER_STATE_NEW,
                ORDER_STATE_PAY,
                ORDER_STATE_SEND,
                ORDER_STATE_SUCCESS,
                ORDER_STATE_SUCCESS,
                ORDER_STATE_CANCEL
            ), $_GET['state_type']);
        }
        if ($_GET['state_type'] == 'state_noeval') {
            $condition['evaluation_state'] = 0;
            $condition['order_state'] = ORDER_STATE_SUCCESS;
            $condition['finnshed_time'] = array(
                'gt',
                TIMESTAMP - ORDER_EVALUATE_TIME
            );
        }
        $order_list = $model_order->getOrderList($condition, 20, '*', 'order_id desc', '', array(
            'order_common',
            'order_goods',
            'store'
        ));
        
        $model_refund_return = Model('refund_return');
        $order_list = $model_refund_return->getGoodsRefundList($order_list);
        
        // 订单列表以支付单pay_sn分组显示
        $order_group_list = array();
        $order_pay_sn_array = array();
	$i = -1;
        foreach ($order_list as $order_id => $order) {
            $i++;
            // 显示取消订单
            $order['if_cancel'] = $model_order->getOrderOperateState('buyer_cancel', $order);
            
            // 显示退款取消订单
            $order['if_refund_cancel'] = $model_order->getOrderOperateState('refund_cancel', $order);

            //20150812 tjz 增加 判断订单是否投诉过
            $conditionC=array();
            $conditionC['order_id']=$order['order_id'];
            $isComplain=$model_complain->getCount($conditionC);

            if($isComplain==0){
                // 没有投诉过
                $order['if_complain'] = $model_order->getOrderOperateState('complain', $order);
            }else{
                $order['if_complain']=3;
                $con = array();
                $con['order_id'] = $order['order_id'];
                $complain = $model_complain -> getComplain($con);
                $order['complain_id'] = $complain[0]['complain_id'];
            }

            foreach ($order['extend_order_goods'] as $order_goodsInfo) {
                if ($order_goodsInfo['is_refund'] == 0) {
                    $order['if_receive'] = true;
                }
            }

            /* //20150812 tjz 增加 判断订单是否有退款
             if($order['refund_state']==0){
                 // 显示收货
                 $order['if_receive'] = $model_order->getOrderOperateState('receive', $order);
             }*/

            // 显示锁定中
            $order['if_lock'] = $model_order->getOrderOperateState('lock', $order);
            
            // 显示物流跟踪
            $order['if_deliver'] = $model_order->getOrderOperateState('deliver', $order);


            //判断是否有追加过


            // 显示评价
           $order['if_evaluation'] = $model_order->getOrderOperateState('evaluation', $order);

            // 显示分享
            $order['if_share'] = $model_order->getOrderOperateState('share', $order);
            
            $order_group_list[$order['pay_sn']]['order_list'][] = $order;
            
            // 如果有在线支付且未付款的订单则显示合并付款链接
            if ($order['order_state'] == ORDER_STATE_NEW) {
                $order_group_list[$order['pay_sn']]['pay_amount'] += $order['order_amount'];
            }
            $order_group_list[$order['pay_sn']]['add_time'] = $order['add_time'];
            
            // 记录一下pay_sn，后面需要查询支付单表
            $order_pay_sn_array[] = $order['pay_sn'];
        }
        
        // 取得这些订单下的支付单列表
        $condition = array(
            'pay_sn' => array(
                'in',
                array_unique($order_pay_sn_array)
            )
        );
        $order_pay_list = $model_order->getOrderPayList($condition, '', '*', '', 'pay_sn');
        foreach ($order_group_list as $pay_sn => $pay_info) {
            $order_group_list[$pay_sn]['pay_info'] = $order_pay_list[$pay_sn];
        }
        $this->get_member_info();
        Tpl::output('order_group_list', $order_group_list);
        Tpl::output('order_pay_list', $order_pay_list);
        Tpl::output('show_page', $model_order->showpage());
        //2015-8-31 tjz增加 订单列表动态变化
        self::profile_menu('member_order',$_GET['state_type']);
        Tpl::showpage('member_order.index');
    }



    //删除已完成的订单 字段del_state变成1
    public function delOp()
    {
        $order_id = intval($_GET['order_id']);
        $model_order = Model('order');
        $update_order = array();
        $update_order['del_state'] = 1;
        $update = $model_order->editOrder($update_order, array(
            'order_id' => $order_id
        ));

    }

    /**
     * 物流跟踪
     */
    public function search_deliverOp()
    {
        Language::read('member_member_index');
        $lang = Language::getLangContent();
        $order_id = intval($_GET['order_id']);
        if ($order_id <= 0) {
            showMessage(Language::get('wrong_argument'), '', 'html', 'error');
        }
        
        $model_order = Model('order');
        $condition['order_id'] = $order_id;
        $condition['buyer_id'] = $_SESSION['member_id'];
        $order_info = $model_order->getOrderInfo($condition, array(
            'order_common',
            'order_goods'
        ));
        if (empty($order_info) || !in_array($order_info['order_state'], array(
                ORDER_STATE_SEND,
                ORDER_STATE_SUCCESS,
                //20150819 tjz修改 增加交易取消物流显示
                ORDER_STATE_CLOSE
            ))
        ) {
            showMessage('未找到信息', '', 'html', 'error');
        }
        Tpl::output('order_info', $order_info);
        // 卖家信息
        $model_store = Model('store');
        $store_info = $model_store->getStoreInfoByID($order_info['store_id']);
        Tpl::output('store_info', $store_info);
        
        // 卖家发货信息
        $daddress_info = Model('daddress')->getAddressInfo(array(
            'address_id' => $order_info['extend_order_common']['daddress_id']
        ));
        Tpl::output('daddress_info', $daddress_info);
        
        $this->get_member_info();
        // 取得配送公司代码
        $express = ($express = H('express')) ? $express : H('express', true);
        Tpl::output('e_code', $express[$order_info['extend_order_common']['shipping_express_id']]['e_code']);
        Tpl::output('e_name', $express[$order_info['extend_order_common']['shipping_express_id']]['e_name']);
        Tpl::output('e_url', $express[$order_info['extend_order_common']['shipping_express_id']]['e_url']);
        Tpl::output('shipping_code', $order_info['shipping_code']);
        self::profile_menu('search', 'search');
        Tpl::output('left_show', 'order_view');
        Tpl::showpage('member_order_deliver.detail');
    }

    /**
     * 从第三方取快递信息
     */
    public function get_expressOp()
    {
        $url = 'http://www.kuaidi100.com/query?type=' . $_GET['e_code'] . '&postid=' . $_GET['shipping_code'] . '&id=1&valicode=&temp=' . random(4) . '&sessionid=&tmp=' . random(4);
        import('function.ftp');
        $content = dfsockopen($url);
        $content = json_decode($content, true);
        
        if ($content['status'] != 200)
            exit(json_encode(false));
        $content['data'] = array_reverse($content['data']);
        $output = '';
        if (is_array($content['data'])) {
            foreach ($content['data'] as $k => $v) {
                if ($v['time'] == '')
                    continue;
                $output .= '<li>' . $v['time'] . '&nbsp;&nbsp;' . $v['context'] . '</li>';
            }
        }
        if ($output == '')
            exit(json_encode(false));
        if (strtoupper(CHARSET) == 'GBK') {
            $output = Language::getUTF8($output); // 网站GBK使用编码时,转换为UTF-8,防止json输出汉字问题
        }
        echo json_encode($output);
    }

    /**
     * 订单详细
     */
    public function show_orderOp()
    {
        $order_id = intval($_GET['order_id']);
        if ($order_id <= 0) {
            showMessage(Language::get('member_order_none_exist'), '', 'html', 'error');
        }
        $model_order = Model('order');
        $condition = array();
        $condition['order_id'] = $order_id;
        $condition['buyer_id'] = $_SESSION['member_id'];
        $order_info = $model_order->getOrderInfo($condition, array(
            'order_goods',
            'order_common',
            'store'
        ));
        if (empty($order_info)) {
            showMessage(Language::get('member_order_none_exist'), '', 'html', 'error');
        }
        Tpl::output('order_info', $order_info);
        Tpl::output('left_show', 'order_view');
        
        // 卖家发货信息
        if (! empty($order_info['extend_order_common']['daddress_id'])) {
            $daddress_info = Model('daddress')->getAddressInfo(array(
                'address_id' => $order_info['extend_order_common']['daddress_id']
            ));
            Tpl::output('daddress_info', $daddress_info);
        }
        
        // 订单变更日志
        $log_list = $model_order->getOrderLogList(array(
            'order_id' => $order_info['order_id']
        ));
        Tpl::output('order_log', $log_list);
        
        // 退款退货信息
        $model_refund = Model('refund_return');
        $condition = array();
        $condition['order_id'] = $order_info['order_id'];
        $condition['seller_state'] = 2;
        $condition['admin_time'] = array(
            'gt',
            0
        );
        $return_list = $model_refund->getReturnList($condition);
        Tpl::output('return_list', $return_list);
        
        // 退款信息
        $refund_list = $model_refund->getRefundList($condition);
        Tpl::output('refund_list', $refund_list);
        Tpl::showpage('member_order.show');
    }

    /**
     * 买家订单状态操作
     */
    public function change_stateOp()
    {
    	Language::read('member_home_member');
    	$state_type = $_GET['state_type'];
        $order_id = intval($_GET['order_id']);
        
        $model_order = Model('order');
        
        $condition = array();
        $condition['order_id'] = $order_id;
        $condition['buyer_id'] = $_SESSION['member_id'];
        $order_info = $model_order->getOrderInfo($condition);
        
        if (! chksubmit()) {
            Tpl::output('order_info', $order_info);
            if ($state_type == 'order_cancel') {
                Tpl::showpage('member_order.cancel', 'null_layout');
                exit();
            } elseif ($state_type == 'order_receive') {
                Tpl::showpage('member_order.receive', 'null_layout');
                exit();
            }
        }
        /**
		 * 检查用户支付密码	
         */
        $pay_passwd = trim($_POST['paypasswd']) ? $_POST['paypasswd'] : '';
        $mem_info = Model('UserService') -> findUserinfo($_SESSION['member_id']);
	    if($state_type == 'order_receive'){  
	        if(empty($pay_passwd) || md5($pay_passwd) != $mem_info['payPassword']){
	        	$lang = Language::getLangContent();
	        	showDialog($lang['home_member_input_paypasswd_wrong_and_order_receive_fail'], '', 'error', empty($_GET['inajax']) ? '' : 'CUR_DIALOG.close();');
	        }
	    }
        $extend_msg = $_POST['state_info1'] != '' ? $_POST['state_info1'] : $_POST['state_info'];
        $result = $model_order -> memberChangeState($state_type, $order_info, $_SESSION['member_id'], $_SESSION['member_name'], $extend_msg, $pay_passwd);
        if (empty($result['error'])) {

            Model('UserService')->changeOrderReceive($order_info['order_sn'], md5($pay_passwd));

            $rsst = Model('store')->getStoreInfo(array(
                'store_id' => $order_info['store_id']
            ));
            if ($state_type == 'order_cancel') {
                // 邮箱
                $this->send_notice($rsst['member_id'], 'email_toseller_cancel_order_notify', array(
                    
                    'site_name' => $GLOBALS['setting_config']['site_name'],
                    
                    'site_url' => SHOP_SITE_URL,
                    'seller_name' => $order_info['store_name'],
                    'reason' => $_POST['state_info'],
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
                    'reason' => $_POST['state_info'],
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
            
            showDialog($result['success'], 'reload', 'succ', empty($_GET['inajax']) ? '' : 'CUR_DIALOG.close();');
        } else {
            showDialog($result['error'], '', 'error', empty($_GET['inajax']) ? '' : 'CUR_DIALOG.close();');
        }
    }

    /**
     * 用户中心右边，小导航
     *
     * @param string $menu_type            
     * @param string $menu_key            
     * @return
     *
     */
    private function profile_menu($menu_key = '',$tab="")
    {
        Language::read('member_layout');

        //2015-8-31 tjz增加 订单列表动态变化
        if($tab=='state_new'){
            $tabtitle="待付款订单";
            $url="index.php?act=member_order&state_type=state_new";
        }
        if($tab=='state_send'){
            $tabtitle="待收货订单";
            $url="index.php?act=member_order&state_type=state_send";
        }
        if($tab=='state_noeval'){
            $tabtitle="待评价订单";
            $url="index.php?act=member_order&state_type=state_noeval";
        }
        if($tab==''||$tab=='state_success'){
            $tabtitle="订单列表";
            $url="index.php?act=member_order";
        }

        $menu_array = array(
            array(
                'menu_key' => 'member_order',
                'menu_name' => $tabtitle,
                'menu_url' => $url
            ),
            array(
                'menu_key' => 'buyer_refund',
                'menu_name' => Language::get('nc_member_path_buyer_refund'),
                'menu_url' => 'index.php?act=member_refund&tab='.$tab
            ),
            array(
                'menu_key' => 'buyer_return',
                'menu_name' => Language::get('nc_member_path_buyer_return'),
                'menu_url' => 'index.php?act=member_return&tab='.$tab
            ),
            array(
                'menu_key' => 'member_gift',
                'menu_name' => '积分兑换订单',
                'menu_url' => 'index.php?act=member_gift&tab='.$tab
            )
        );
        Tpl::output('member_menu', $menu_array);
        Tpl::output('menu_key', $menu_key);
    }
}
