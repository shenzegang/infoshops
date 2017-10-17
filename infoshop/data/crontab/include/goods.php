<?php
/**
 * 任务计划 - 通用任务、促销处理
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

class goodsControl
{

    public function __construct()
    {
        register_shutdown_function(array(
            $this,
            "shutdown"
        ));
    }

    /**
     * 更新商品促销到期状态
     */
    public function promotionOp()
    {
        // 团购活动过期
        Model('groupbuy')->editExpireGroupbuy();
        // 限时折扣过期
        Model('p_xianshi')->editExpireXianshi();
        // 满即送过期
        Model('p_mansong')->editExpireMansong();
    }

    /**
     * 更新首页的商品价格信息
     */
    public function web_updateOp()
    {
        Model('web_config')->updateWebGoods();
    }

    /**
     * 执行通用任务
     */
    public function commonOp()
    {

        // 查找待执行任务
        $model_cron = Model('cron');
        $cron = $model_cron->getCronList(array(
            'exetime' => array(
                'elt',
                TIMESTAMP
            )
        ));
        if (!is_array($cron))
            return;
        $cron_array = array();
        $cronid = array();
        foreach ($cron as $v) {
            $cron_array[$v['type']][$v['exeid']] = $v;
        }
        foreach ($cron_array as $k => $v) {
            if (!method_exists($this, '_cron_' . $k)) {
                $tmp = current($v);
                $cronid[] = $tmp['id'];
                continue;
            }
            $result = call_user_func_array(array(
                $this,
                '_cron_' . $k
            ), array(
                $v
            ));
            if (is_array($result)) {
                $cronid = array_merge($cronid, $result);
            }
        }
        // 删除执行完成的cron信息
        if (!empty($cronid) && is_array($cronid)) {
            $model_cron->delCron(array(
                'id' => array(
                    'in',
                    $cronid
                )
            ));
        }
    }

    /**
     * 上架
     *
     * @param array $cron
     */
    private function _cron_1($cron = array())
    {
        $condition = array(
            'goods_commonid' => array(
                'in',
                array_keys($cron)
            )
        );
        $update = Model('goods')->editProducesOnline($condition);
        if ($update) {
            // 返回执行成功的cronid
            $cronid = array_keys($cron);
        } else {
            return false;
        }
        return $cronid;
    }

    /**
     * 优惠套装过期
     *
     * @param array $cron
     */
    private function _cron_3($cron = array())
    {
        $condition = array(
            'store_id' => array(
                'in',
                array_keys($cron)
            )
        );
        $update = Model('p_bundling')->editBundlingQuotaClose($condition);
        if ($update) {
            // 返回执行成功的cronid
            $cronid = array_keys($cron);
        } else {
            return false;
        }
        return $cronid;
    }

    /**
     * 推荐展位过期
     *
     * @param array $cron
     */
    private function _cron_4($cron = array())
    {
        $condition = array(
            'store_id' => array(
                'in',
                array_keys($cron)
            )
        );
        $update = Model('p_booth')->editBoothClose($condition);
        if ($update) {
            // 返回执行成功的cronid
            $cronid = array_keys($cron);
        } else {
            return false;
        }
        return $cronid;
    }

    /**
     * 20150826 tjz增加 15天自动确认收货
     */
    public function autoConfirmOp()
    {
        $model_order = Model('order');
        $condition = array();
        $condition['order_state'] = 30;
        $order_list = $model_order->getOrderList($condition, '', '*', 'order_id desc', '', array(
            'order_common'
        ));
        foreach ($order_list as $order_info) {
            //获取当前时间戳
            $result = TIMESTAMP - intval($order_info["extend_order_common"]["shipping_time"]) <= ORDER_EVALUATE_TIME;
            if (!$result) {
                //表示需要自动确认
                $member_model = Model('member');
                $member_info = $member_model->getMemberInfo(array(
                    'member_id' => $order_info['buyer_id']
                ), 'member_paypasswd');
                $update = $model_order->autoMemberChangeState($order_info, $order_info['buyer_id'], $order_info['buyer_name'], "签收了货物", $member_info['member_paypasswd'],"http://121.41.113.184:6060/xnpayws/ws/pay/balancePay/finshOrder");
                if ($update) {
                    // 添加订单日志
                    $data = array();
                    $data['order_id'] = $order_info['order_id'];
                    $data['log_role'] = 'system';
                    $data['log_user'] = 'system';
                    $data['log_msg'] = '自动确认收货';
                    $data['log_orderstate'] = ORDER_STATE_SUCCESS;
                    $model_order->addOrderLog($data);
                }
            }
        }
    }


    /**
     * 20150826 tjz 增加 确认收货15天后自动默认好评
     */
    public function  autoGoodsEvaluationOp()
    {
        //获取需要好评的订单信息
        $model_order = Model('order');
        $model_store = Model('store');
        $model_evaluate_goods = Model('evaluate_goods');
        $model_evaluate_store = Model('evaluate_store');
        $condition = array();
        $condition['order_state'] = 40;
        $condition['evaluation_state'] = 0;
        $condition['finnshed_time'] = array(
            'elt',
            TIMESTAMP - ORDER_EVALUATE_TIME
        );
        //需要执行好评的商品
        $order_list = $model_order->getOrderList($condition, '', '*', 'order_id desc', '', array(
            'order_common',
            'order_goods'
        ));


        foreach ($order_list as $order_info) {
            // 查询店铺信息
            $store_info = $model_store->getStoreInfoByID($order_info['store_id']);
            if (empty($store_info)) {
                return;
            }
            // 获取订单商品
            $order_goods = $model_order->getOrderGoodsList(array(
                'order_id' => $order_info['order_id']
            ));
            if (empty($order_goods)) {
                return;
            }

            $evaluate_goods_array = array();

            foreach ($order_goods as $value) {
                // 默认评语
                $goods_content = '好评！';
                //整理数据
                $evaluate_goods_info = array();
                $evaluate_goods_info['geval_orderid'] = $order_info['order_id'];
                $evaluate_goods_info['geval_orderno'] = $order_info['order_sn'];
                $evaluate_goods_info['geval_ordergoodsid'] = $value['rec_id'];
                $evaluate_goods_info['geval_goodsid'] = $value['goods_id'];
                $evaluate_goods_info['geval_goodsname'] = $value['goods_name'];
                $evaluate_goods_info['geval_goodsprice'] = $value['goods_price'];
                $evaluate_goods_info['geval_scores'] = 5;
                $evaluate_goods_info['geval_content'] = $goods_content;
                $evaluate_goods_info['geval_isanonymous'] = 1;
                $evaluate_goods_info['geval_addtime'] = TIMESTAMP;
                $evaluate_goods_info['geval_storeid'] = $store_info['store_id'];
                $evaluate_goods_info['geval_storename'] = $store_info['store_name'];
                $evaluate_goods_info['geval_frommemberid'] = $order_info['buyer_id'];
                $evaluate_goods_info['geval_frommembername'] = "系统默认评价";
                $evaluate_goods_array[] = $evaluate_goods_info;
            }

            //评价入库
            $model_evaluate_goods->addEvaluateGoodsArray($evaluate_goods_array);
            // 添加店铺评价
            $evaluate_store_info = array();
            $evaluate_store_info['seval_orderid'] = $order_info['order_id'];
            $evaluate_store_info['seval_orderno'] = $order_info['order_sn'];
            $evaluate_store_info['seval_addtime'] = TIMESTAMP;
            $evaluate_store_info['seval_storeid'] = $store_info['store_id'];
            $evaluate_store_info['seval_storename'] = $store_info['store_name'];
            $evaluate_store_info['seval_memberid'] = $order_info['buyer_id'];
            $evaluate_store_info['seval_membername'] = "系统默认评价";
            $evaluate_store_info['seval_desccredit'] = 5;
            $evaluate_store_info['seval_servicecredit'] = 5;
            $evaluate_store_info['seval_deliverycredit'] = 5;
            $model_evaluate_store->addEvaluateStore($evaluate_store_info);

            // 更新订单信息并记录订单日志
            $state = $model_order->editOrder(array(
                'evaluation_state' => 2//第一次评价 状态改成2 表示其还有机会追加评价
            ), array(
                'order_id' => $order_info['order_id']
            ));
            $model_order->editOrderCommon(array(
                'evaluation_time' => TIMESTAMP
            ), array(
                'order_id' => $order_info['order_id']
            ));
            if ($state) {
                $data = array();
                $data['order_id'] = $order_info['order_id'];
                $data['log_role'] = 'system';
                $data['log_msg'] = '系统默认好评';
                $model_order->addOrderLog($data);
            }
            // 添加会员哈金豆
            if ($GLOBALS['setting_config']['points_isuse'] == 1) {
                $points_model = Model('points');
                $points_model->savePointsLog('comments', array(
                    'pl_memberid' => $order_info['buyer_id'],
                    'pl_membername' => $order_info['buyer_name']
                ));
            }
        }
    }


    /**
     * 执行完成提示信息
     */
    public function shutdown()
    {
        exit("\nsuccess");
    }
}