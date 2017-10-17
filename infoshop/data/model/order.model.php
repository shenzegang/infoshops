<?php
/**
 * 订单管理
 *
 *
 *
 *
 * @copyright  Copyright (c) 2003-2015 B2B2C Inc. (http://www.infol.com.cn)
 * @license    http://www.infol.com.cn
 * @link       http://www.infol.com.cn
 * @since      File available since Release v1.1
 * @since      File available since Release v1.1
 */
defined('CorShop') or exit('Access Invalid!');

class orderModel extends Model
{

    /**
     * 取单条订单信息
     *
     * @param unknown_type $condition
     * @param array $extend
     *            追加返回那些表的信息,如array('order_common','order_goods','store')
     * @return unknown
     */
    public function getOrderInfo($condition = array(), $extend = array(), $fields = '*', $order = '', $group = '')
    {
        $order_info = $this->table('order')
            ->field($fields)
            ->where($condition)
            ->group($group)
            ->order($order)
            ->find();
        if (empty($order_info)) {
            return array();
        }
        $order_info['state_desc'] = orderState($order_info);
        $order_info['payment_name'] = orderPaymentName($order_info['payment_code']);


        // 追加返回订单扩展表信息
        if (in_array('order_common', $extend)) {
            $order_info['extend_order_common'] = $this->getOrderCommonInfo(array(
                'order_id' => $order_info['order_id']
            ));
            $order_info['extend_order_common']['reciver_info'] = unserialize($order_info['extend_order_common']['reciver_info']);
            $order_info['extend_order_common']['invoice_info'] = unserialize($order_info['extend_order_common']['invoice_info']);
        }

        // 追加返回店铺信息
        if (in_array('store', $extend)) {
            $order_info['extend_store'] = Model('store')->getStoreInfo(array(
                'store_id' => $order_info['store_id']
            ));
        }

        // 返回买家信息
        if (in_array('member', $extend)) {
            $order_info['extend_member'] = Model('member')->getMemberInfo(array(
                'member_id' => $order_info['buyer_id']
            ));
        }

        // 追加返回商品信息
        if (in_array('order_goods', $extend)) {
            // 取商品列表
            $order_goods_list = $this->getOrderGoodsList(array(
                'order_id' => $order_info['order_id']
            ));
            foreach ($order_goods_list as $value) {
                $order_info['extend_order_goods'][] = $value;
            }
        }

        return $order_info;
    }

    public function getOrderCommonInfo($condition = array(), $field = '*')
    {
        return $this->table('order_common')
            ->where($condition)
            ->find();
    }

    public function getOrderPayInfo($condition = array())
    {
        return $this->table('order_pay')
            ->where($condition)
            ->find();
    }

    /**
     * 取得支付单列表
     *
     * @param unknown_type $condition
     * @param unknown_type $pagesize
     * @param unknown_type $filed
     * @param unknown_type $order
     * @param string $key
     *            以哪个字段作为下标,这里一般指pay_id
     * @return unknown
     */
    public function getOrderPayList($condition, $pagesize = '', $filed = '*', $order = '', $key = '')
    {
        return $this->table('order_pay')
            ->field($filed)
            ->where($condition)
            ->order($order)
            ->page($pagesize)
            ->key($key)
            ->select();
    }

    /**
     * 取得订单列表
     *
     * @param unknown $condition
     * @param string $pagesize
     * @param string $field
     * @param string $order
     * @param string $limit
     * @param unknown $extend
     *            追加返回那些表的信息,如array('order_common','order_goods','store')
     * @return Ambigous <multitype:boolean Ambigous <string, mixed> , unknown>
     */
    public function getOrderList($condition, $pagesize = '', $field = '*', $order = 'order_id desc', $limit = '', $extend = array())
    {
        $list = $this->table('order')
            ->field($field)
            ->where($condition)
            ->page($pagesize)
            ->order($order)
            ->limit($limit)
            ->select();
        if (empty($list))
            return array();
        $order_list = array();
        foreach ($list as $order) {
            $order['state_desc'] = orderState($order);
            $order['payment_name'] = orderPaymentName($order['payment_code']);
            if (!empty($extend))
                $order_list[$order['order_id']] = $order;
        }
        if (empty($order_list))
            $order_list = $list;

        // 追加返回订单扩展表信息
        if (in_array('order_common', $extend)) {
            $order_common_list = $this->getOrderCommonList(array(
                'order_id' => array(
                    'in',
                    array_keys($order_list)
                )
            ));
            foreach ($order_common_list as $value) {
                $order_list[$value['order_id']]['extend_order_common'] = $value;
                $order_list[$value['order_id']]['extend_order_common']['reciver_info'] = @unserialize($value['reciver_info']);
                $order_list[$value['order_id']]['extend_order_common']['invoice_info'] = @unserialize($value['invoice_info']);
            }
        }
        // 追加返回店铺信息
        if (in_array('store', $extend)) {
            $store_id_array = array();
            foreach ($order_list as $value) {
                if (!in_array($value['store_id'], $store_id_array))
                    $store_id_array[] = $value['store_id'];
            }
            $store_list = Model('store')->getStoreList(array(
                'store_id' => array(
                    'in',
                    $store_id_array
                )
            ));
            $store_new_list = array();
            foreach ($store_list as $store) {
                $store_new_list[$store['store_id']] = $store;
            }
            foreach ($order_list as $order_id => $order) {
                $order_list[$order_id]['extend_store'] = $store_new_list[$order['store_id']];
            }
        }

        // 追加返回买家信息
        if (in_array('member', $extend)) {
            $member_id_array = array();
            foreach ($order_list as $value) {
                if (!in_array($value['buyer_id'], $member_id_array))
                    $member_id_array[] = $value['buyer_id'];
            }
            $member_list = Model()->table('member')
                ->where(array(
                    'member_id' => array(
                        'in',
                        $member_id_array
                    )
                ))
                ->limit($pagesize)
                ->key('member_id')
                ->select();
            foreach ($order_list as $order_id => $order) {
                $order_list[$order_id]['extend_member'] = $member_list[$order['buyer_id']];
            }
        }

        // 追加返回商品信息
        if (in_array('order_goods', $extend)) {
            // 取商品列表
            $order_goods_list = $this->getOrderGoodsList(array('order_id' => array('in', array_keys($order_list))));
            foreach ($order_goods_list as $value) {
                $value['goods_image_url'] = cthumb($value['goods_image'], 240, $value['store_id']);
                $order_list[$value['order_id']]['extend_order_goods'][] = $value;
            }
        }
        
        /*
         * 追加订单福利信息
         *  $order_list 订单列表  array(
         *      'order_id' int(10) => array(
         *            'extend_order_welfares' (当前订单使用的福利列表) => array(
         *                  'points' (当前订单使用的福利为积分) => array(
         *                      'welfare_code' => 'points'      当前福利的类型为积分,
         *                      'points_num' => int(10)     当前订单使用福利数量,
         *                      'points_amount' => float(10,2)    当前订单使用的福利抵用的现金
         *                  )
         *            )
         *      )
         *  )
         */
        if (in_array('order_welfare', $extend)) {
            $order_welfare_list = $this -> getOrderWelfaresList(array('order_id' => array('in', array_keys($order_list))));
            if(!empty($order_welfare_list)){
                foreach ($order_welfare_list as $value) {
                    $order_list[$value['order_id']]['extend_order_welfares'][$value['welfare_code']] = $value;
                }
            }else{
                $order_list[$value['order_id']]['extend_order_welfares'] = null;
            }
        }

        return $order_list;
    }

    /**
     * 待付款订单数量
     *
     * @param unknown $condition
     */
    public function getOrderStateNewCount($condition = array())
    {
        $condition['order_state'] = ORDER_STATE_NEW;
        $condition['del_state'] = 0;
        return $this->getOrderCount($condition);
    }

    /**
     * 待发货订单数量
     *
     * @param unknown $condition
     */
    public function getOrderStatePayCount($condition = array())
    {
        $condition['order_state'] = ORDER_STATE_PAY;
        return $this->getOrderCount($condition);
    }

    /**
     * 待收货订单数量
     *
     * @param unknown $condition
     */
    public function getOrderStateSendCount($condition = array())
    {
        $condition['order_state'] = ORDER_STATE_SEND;
        return $this->getOrderCount($condition);
    }

    /**
     * 待评价订单数量
     *
     * @param unknown $condition
     */
    public function getOrderStateEvalCount($condition = array())
    {
        $condition['order_state'] = ORDER_STATE_SUCCESS;
        $condition['evaluation_state'] = 0;
        $condition['del_state'] = 0;
        $condition['finnshed_time'] = array(
            'gt',
            TIMESTAMP - ORDER_EVALUATE_TIME
        );
        return $this->getOrderCount($condition);
    }

    /**
     * 取得订单数量
     *
     * @param unknown $condition
     */
    public function getOrderCount($condition)
    {
        return $this->table('order')
            ->where($condition)
            ->count();
    }

    /**
     * 单笔订单未退款的商品数量
     */
    public function getGoodsCountByRefund($condition)
    {
        return $this->table('order_goods')
            ->where($condition)
            ->count();
    }
    /**
     * 取得订单商品表详细信息
     *
     * @param unknown $condition
     * @param string $fields
     * @param string $order
     */
    public function getOrderGoodsInfo($condition = array(), $fields = '*', $order = '')
    {
        return $this->table('order_goods')
            ->where($condition)
            ->field($fields)
            ->order($order)
            ->find();
    }

    /**
     * 取得订单商品表列表
     *
     * @param unknown $condition
     * @param string $fields
     * @param string $limit
     * @param string $page
     * @param string $order
     * @param string $group
     * @param string $key
     */
    public function getOrderGoodsList($condition = array(), $fields = '*', $limit = null, $page = null, $order = 'rec_id desc', $group = null, $key = null)
    {
        return $this->table('order_goods')
            ->field($fields)
            ->where($condition)
            ->limit($limit)
            ->order($order)
            ->group($group)
            ->key($key)
            ->page($page)
            ->select();
    }
    
    /**
     * 取得订单福利表列表
     *
     * @param unknown $condition
     * @param string $fields
     * @param string $limit
     * @param string $page
     * @param string $order
     * @param string $group
     * @param string $key
     */
    public function getOrderWelfaresList($condition = array(), $fields = '*', $limit = null, $page = null, $order = 'id desc', $group = null, $key = null)
    {
        return $this->table('order_welfare')->field($fields)->where($condition)->limit($limit)->order($order)->group($group)->key($key)->page($page)->select();
        //$sql = "SELECT $fi FROM `cor_order_welfare` WHERE order_id IN '" . implode("', '", $condition) . "'";
        //return $this -> query($sql);
    }

    /**
     * 取得订单扩展表列表
     *
     * @param unknown $condition
     * @param string $fields
     * @param string $limit
     */
    public function getOrderCommonList($condition = array(), $fields = '*', $limit = null)
    {
        return $this->table('order_common')
            ->field($fields)
            ->where($condition)
            ->limit($limit)
            ->select();
    }

    /**
     * 插入订单支付表信息
     *
     * @param array $data
     * @return int 返回 insert_id
     */
    public function addOrderPay($data)
    {
        return $this->table('order_pay')->insert($data);
    }

    /**
     * 插入订单表信息
     *
     * @param array $data
     * @return int 返回 insert_id
     */
    public function addOrder($data)
    {
        return $this->table('order')->insert($data);
    }

    /**
     * 插入订单扩展表信息
     * @param array $data
     * @return int 返回 insert_id
     */
    public function addOrderCommon($data)
    {
        return $this->table('order_common')->insert($data);
    }
    
    /**
     * 插入订单福利表信息
     * @param array $data
     * @return int 返回 insert_id
     */
    public function addOrderWelfare($data){
        return $this->table('order_welfare')->insert($data);
    }

    /**
     * 插入订单扩展表信息
     *
     * @param array $data
     * @return int 返回 insert_id
     */
    public function addOrderGoods($data)
    {
        return $this->table('order_goods')->insertAll($data);
    }

    /**
     * 添加订单日志
     */
    public function addOrderLog($data)
    {
        $data['log_role'] = str_replace(array(
            'buyer',
            'seller',
            'system'
        ), array(
            '买家',
            '商家',
            '系统'
        ), $data['log_role']);
        $data['log_time'] = TIMESTAMP;
        return $this->table('order_log')->insert($data);
    }

    /**
     * 更改订单信息
     *
     * @param unknown_type $data
     * @param unknown_type $condition
     */
    public function editOrder($data, $condition)
    {
        return $this->table('order')
            ->where($condition)
            ->update($data);
    }

    /**
     * 更改订单商品表
     *
     * @param unknown_type $data
     * @param unknown_type $condition
     */
    public function editOrderGoods($data, $condition)
    {
        return $this->table('order_goods')
            ->where($condition)
            ->update($data);
    }

    /**
     * 更改订单信息
     *
     * @param unknown_type $data
     * @param unknown_type $condition
     */
    public function editOrderCommon($data, $condition)
    {
        return $this->table('order_common')
            ->where($condition)
            ->update($data);
    }

    /**
     * 更改订单支付信息
     *
     * @param unknown_type $data
     * @param unknown_type $condition
     */
    public function editOrderPay($data, $condition)
    {
        return $this->table('order_pay')
            ->where($condition)
            ->update($data);
    }

    /**
     * 订单操作历史列表
     *
     * @param unknown $order_id
     * @return Ambigous <multitype:, unknown>
     */
    public function getOrderLogList($condition)
    {
        return $this->table('order_log')
            ->where($condition)
            ->select();
    }

    /**
     * 返回是否允许某些操作
     *
     * @param unknown $operate
     * @param unknown $order_info
     */
    public function getOrderOperateState($operate, $order_info)
    {
        if (!is_array($order_info) || empty($order_info))
            return false;

        switch ($operate) {

            // 买家取消订单
            case 'buyer_cancel':
                $state = ($order_info['order_state'] == ORDER_STATE_NEW) || ($order_info['payment_code'] == 'offline' && $order_info['order_state'] == ORDER_STATE_PAY);
                break;

            // 买家取消订单
            case 'refund_cancel':
                $state = $order_info['refund'] == 1 && !intval($order_info['lock_state']);
                break;

            // 商家取消订单
            case 'store_cancel':
                $state = ($order_info['order_state'] == ORDER_STATE_NEW) || ($order_info['payment_code'] == 'offline' && in_array($order_info['order_state'], array(
                            ORDER_STATE_PAY,
                            ORDER_STATE_SEND
                        )));
                break;

            // 平台取消订单
            case 'system_cancel':
                $state = ($order_info['order_state'] == ORDER_STATE_NEW) || ($order_info['payment_code'] == 'offline' && $order_info['order_state'] == ORDER_STATE_PAY);
                break;

            // 平台收款
            case 'system_receive_pay':
                $state = $order_info['order_state'] == ORDER_STATE_NEW && $order_info['payment_code'] == 'online';
                break;

            // 买家投诉
            case 'complain':
                $state = in_array($order_info['order_state'], array(
                        ORDER_STATE_PAY,
                        ORDER_STATE_SEND,
                        //20150819 tjz增加 交易取消显示
                        ORDER_STATE_CLOSE
                    )) || intval($order_info['finnshed_time']) > (TIMESTAMP - C('complain_time_limit'));
                break;

            // 调整运费
            case 'modify_price':
                $state = ($order_info['order_state'] == ORDER_STATE_NEW) || ($order_info['payment_code'] == 'offline' && $order_info['order_state'] == ORDER_STATE_PAY);
                $state = floatval($order_info['shipping_fee']) > 0 && $state;
                break;
            // 调整商品费用
            case 'goods_price':
                $state = ($order_info['order_state'] == ORDER_STATE_NEW) || ($order_info['payment_code'] == 'offline' && $order_info['order_state'] == ORDER_STATE_PAY);
                break;

            // 发货
            case 'send':
                $state = !$order_info['lock_state'] && $order_info['order_state'] == ORDER_STATE_PAY;
                break;

            // 收货
            case 'receive':
                $state = !$order_info['lock_state'] && $order_info['order_state'] == ORDER_STATE_SEND;
                break;

            // 评价
            case 'evaluation':
                //20150819 tjz修改 标识可追加评价
                $state = !$order_info['lock_state'] && intval($order_info['evaluation_state']) != 1 && $order_info['order_state'] == ORDER_STATE_SUCCESS && TIMESTAMP - intval($order_info['finnshed_time']) < ORDER_EVALUATE_TIME;
                break;

            // 锁定
            case 'lock':
                $state = intval($order_info['lock_state']) ? true : false;
                break;

            // 快递跟踪
            case 'deliver':
                $state = !empty($order_info['shipping_code']) && in_array($order_info['order_state'], array(
                        ORDER_STATE_SEND,
                        ORDER_STATE_SUCCESS,
                        //20150819 tjz增加 交易取消显示
                        ORDER_STATE_CLOSE
                    ));
                break;

            // 分享
            case 'share':
                $state = $order_info['order_state'] == ORDER_STATE_SUCCESS;
                break;
        }
        return $state;
    }

    /**
     * 联查订单表订单商品表
     *
     * @param array $condition
     * @param string $field
     * @param number $page
     * @param string $order
     * @return array
     */
    public function getOrderAndOrderGoodsList($condition, $field = '*', $page = 0, $order = 'rec_id desc')
    {
        return $this->table('order_goods,order')
            ->join('inner')
            ->on('order_goods.order_id=order.order_id')
            ->where($condition)
            ->field($field)
            ->page($page)
            ->order($order)
            ->select();
    }

    /**
     * 订单销售记录 订单状态为20、30、40时
     *
     * @param unknown $condition
     * @param string $field
     * @param number $page
     * @param string $order
     */
    public function getOrderAndOrderGoodsSalesRecordList($condition, $field = "*", $page = 0, $order = 'rec_id desc')
    {
        $condition['order_state'] = array(
            'in',
            array(
                ORDER_STATE_PAY,
                ORDER_STATE_SEND,
                ORDER_STATE_SUCCESS
            )
        );
        return $this->getOrderAndOrderGoodsList($condition, $field, $page, $order);
    }

    /**
     * 买家订单状态操作
     */
    public function memberChangeState($state_type, $order_info, $member_id, $member_name, $extend_msg, $pay_passwd)
    {
        try {

            $this->beginTransaction();

            if ($state_type == 'order_cancel') {
                $this->_memberChangeStateOrderCancel($order_info, $member_id, $member_name, $extend_msg);
                $message = '成功取消了订单';
            } elseif ($state_type == 'order_receive') {
                $this->_memberChangeStateOrderReceive($order_info, $member_id, $member_name, $extend_msg, $pay_passwd);
                $message = '订单交易成功,您可以评价本次交易';
            }

            $this->commit();
            return array(
                'success' => $message
            );
        } catch (Exception $e) {
            $this->rollback();
            return array(
                'error' => $e -> getMessage()
            );
        }
    }

    /**
     * 取消订单操作
     *
     * @param unknown $order_info
     */
    private function _memberChangeStateOrderCancel($order_info, $member_id, $member_name, $extend_msg)
    {
        $order_id = $order_info['order_id'];
        $if_allow = $this->getOrderOperateState('buyer_cancel', $order_info);

        if (!$if_allow) {
            throw new Exception('非法访问');
        }

        $goods_list = $this->getOrderGoodsList(array(
            'order_id' => $order_id
        ));
        $model_goods = Model('goods');
        if (is_array($goods_list) && !empty($goods_list)) {
            $data = array();
            foreach ($goods_list as $goods) {
                $data['goods_storage'] = array(
                    'exp',
                    'goods_storage+' . $goods['goods_num']
                );
                $data['goods_salenum'] = array(
                    'exp',
                    'goods_salenum-' . $goods['goods_num']
                );
                $gift_points += $goods['gift_points'];
                $update = $model_goods->editGoods($data, array(
                    'goods_id' => $goods['goods_id']
                ));
                if (!$update) {
                    throw new Exception('保存失败');
                }
            }
        }

        //20150818 tjz修改 判断订单是否支付 如果订单未支付取消 不应给其返还积分
        if ($order_info['order_state'] != 10 && $order_info['order_state'] != 0) {
            // 返回哈金豆
            if (!empty($gift_points)) {
                $points_model = Model('points');
                $insert_arr['pl_memberid'] = $order_info['buyer_id'];
                $insert_arr['pl_membername'] = $order_info['buyer_name'];
                $insert_arr['pl_points'] = +$gift_points;
                $insert_arr['point_ordersn'] = $order_info['order_sn'];
                $return = $points_model->savePointsLog('cancelorder', $insert_arr, true);
                if (!$return)
                    throw new Exception('积分更新失败');
            }
        }


        // 解冻预存款
        $pd_amount = floatval($order_info['pd_amount']);
        if ($pd_amount > 0) {
            $model_pd = Model('predeposit');
            $data_pd = array();
            $data_pd['member_id'] = $member_id;
            $data_pd['member_name'] = $member_name;
            $data_pd['amount'] = $pd_amount;
            $data_pd['order_sn'] = $order_info['order_sn'];
            $model_pd->changePd('order_cancel', $data_pd);
        }

        // 更新订单信息
        $update_order = array(
            'order_state' => ORDER_STATE_CANCEL,
            'pd_amount' => 0
        );
        $update = $this->editOrder($update_order, array(
            'order_id' => $order_id
        ));
        if (!$update) {
            throw new Exception('保存失败');
        }

        // 添加订单日志
        $data = array();
        $data['order_id'] = $order_id;
        $data['log_role'] = 'buyer';
        $data['log_msg'] = '关闭了订单';
        if ($extend_msg) {
            $data['log_msg'] .= ' ( ' . $extend_msg . ' )';
        }
        $data['log_orderstate'] = ORDER_STATE_CANCEL;
        $this->addOrderLog($data);
        
        if ($order_info['order_state'] == ORDER_STATE_NEW) {
            require_once BASE_CORE_PATH . DS . 'framework' . DS . 'function' . DS . 'payapi.php';
            $resp = array();
            $req = array('orderCode' => $order_info['order_sn']);
            $validResp = trade(C('order.closeOrder'), $req, $resp);
            if (!$validResp || $resp['returnStatus'] != '000') {
                throw new Exception('关闭订单操作失败' . $resp['returnMsg']);
            }
        }
    }


    /**
     * @param $order_info
     * @param $member_id
     * @param $member_name
     * @param $extend_msg
     * @param $pay_pwd
     * @return array
     * 20150826 tjz新增 自动确认收货的方法
     */
    public function autoMemberChangeState($order_info, $member_id, $member_name, $extend_msg, $pay_pwd, $apiwww)
    {
        try {
            $this->beginTransaction();
            $this->_memberChangeStateOrderReceive($order_info, $member_id, $member_name, $extend_msg);
            require_once BASE_CORE_PATH . DS . 'framework' . DS . 'function' . DS . 'payapi.php';
            $resp = array();
            $req = array('orderCode' => $order_info['order_sn'], 'payPassword' => $pay_pwd);
            $validResp = trade($apiwww, $req, $resp);
            if (!$validResp || $resp['returnStatus'] != '000') {
                throw new Exception('操作失败');
            }
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
        }
    }


    /**
     * 收货操作
     * @param unknown $order_info
     */
    private function _memberChangeStateOrderReceive($order_info, $member_id, $member_name, $extend_msg, $pay_passwd)
    {
        $order_id = $order_info['order_id'];
        
        /*
         * 确认收货接口
         */
        /*
        $update = Model('UserService') -> changeOrderReceive($order_info['order_sn'], md5($pay_passwd));
        if ($update !== true) {
            throw new Exception('支付系统结算接口调用失败(' . $update['error'] . ')');
        }
        */
        // 更新订单状态
        $update_order = array();
        $update_order['finnshed_time'] = TIMESTAMP;
        $update_order['order_state'] = ORDER_STATE_SUCCESS;
        $update = $this->editOrder($update_order, array(
            'order_id' => $order_id
        ));
        
        if (!$update) {
            throw new Exception('保存失败');
        }
        
        /*
         * 更新订单单商品的状态
         *      1，判断当前订单是否可拆,如果可拆则到第二步
         *      2，再判断是否有商品在退款、退货中.如果有则统一更新每个商品的状态（is_refund为0），没有则无需更新
         */
        if($order_info['is_detach']){
            $order_goods = $this -> getOrderGoodsList(array('order_id' => $order_id), 'goods_id, is_refund');
            foreach($order_goods as $good){
                if($good['is_refund'] == 2){
                    $good_ids[] = $good['goods_id'];
                }
            }
            if(!empty($good_ids)){
                $update = $this -> editOrderGoods(array('is_refund' => 0), array('order_id' => $order_id, 'goods_id' => array('in', $good_ids)));
                if (!$update) {
                    throw new Exception('订单商品状态更新失败');
                }
            }
        }
      
        // 添加订单日志
        $data = array();
        $data['order_id'] = $order_id;
        $data['log_role'] = 'buyer';
        $data['log_msg'] = '签收了货物';
        if ($extend_msg) {
            $data['log_msg'] .= ' ( ' . $extend_msg . ' )';
        }
        $data['log_orderstate'] = ORDER_STATE_SUCCESS;
        $this->addOrderLog($data);

        // 确认收货时添加会员哈金豆
        if (C('points_isuse') == 1) {
                $points_model = Model('points');
                $points_model -> savePointsLog('order', array(
                    'pl_memberid' => $member_id,
                    'pl_membername' => $member_name,
                    //sj 20150901 修改计算积分规则
                    'orderprice' => $order_info['goods_amount'],
                    'order_sn' => $order_info['order_sn'],
                    'order_id' => $order_info['order_id']
                ), true);
                
                //给用户发放福利
                $welfare_points = floor($order_info['goods_amount'] / C("points_orderrate"));
                if($welfare_points > C("points_ordermax")){
                    $welfare_points = C("points_ordermax");
                }
                
                $update = Model('UserService') -> grantUserWelfare(array(
                                                            'welfareId' => '93C617D9ED6A467C8703BCE5B1891959',
                                                            'welfareCount' => $welfare_points,
                                                            'userId' => $member_id,
                                                            'useType' => '1'
                                         ));
                
                if($update === false){
                    throw new Exception('更新用户福利失败 ');
                }
        }

        //更新店铺销量
        $store=Model("store");
        $condition=array();
        $condition['store_id']=$order_info['store_id'];
        $store_info=$store->getStoreInfo($condition);
        //更新店铺销量
        $update=array();
        $update['store_sales']=$store_info['store_sales']+1;
        $store->updateStoreInfo($condition,$update);
    }


    /**
     * 2015-9-11 tjz增加 获得店铺商品销量
     * @param $condition
     * @return mixed
     */
    public function getOrderSuccessCount($condition)
    {
        return $this->table('order')
            ->where($condition)
            ->count();
    }
}
