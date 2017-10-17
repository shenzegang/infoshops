<?php
/**
 * 会员中心——买家评价
 *
 * @copyright  Copyright (c) 2003-2015 B2B2C Inc. (http://www.infol.com.cn)
 * @license    http://www.infol.com.cn
 * @link       http://www.infol.com.cn
 * @since      File available since Release v1.1
 */
defined('CorShop') or exit('Access Invalid!');

class member_evaluateControl extends BaseMemberControl
{

    public function __construct()
    {
        parent::__construct();
        Language::read('member_layout,member_evaluate');
        Tpl::output('pj_act', 'member_evaluate');
    }

    /**
     * 订单添加评价
     */
    public function addOp()
    {
        $model_sns_alumb = Model('sns_album');
        $ac_id = $model_sns_alumb->getSnsAlbumClassDefault($_SESSION['member_id']);
        Tpl::output('ac_id', $ac_id);
        //是否追加评价
        $if_add = 0;
        $order_id = intval($_GET['order_id']);
        if (!$order_id) {
            showMessage(Language::get('wrong_argument'), 'index.php?act=member_order', 'html', 'error');
        }

        $model_order = Model('order');
        $model_store = Model('store');
        $model_evaluate_goods = Model('evaluate_goods');
        $model_evaluate_store = Model('evaluate_store');

        // 获取订单信息
        // 订单为'已收货'状态，并且未评论
        $order_info = $model_order->getOrderInfo(array(
            'order_id' => $order_id
        ));
        $order_info['evaluate_able'] = $model_order->getOrderOperateState('evaluation', $order_info);
        if (empty($order_info) || !$order_info['evaluate_able']) {
            showMessage(Language::get('member_evaluation_order_notexists'), 'index.php?act=member_order', 'html', 'error');
        }

        // 查询店铺信息
        $store_info = $model_store->getStoreInfoByID($order_info['store_id']);
        if (empty($store_info)) {
            showMessage(Language::get('member_evaluation_store_notexists'), 'index.php?act=member_order', 'html', 'error');
        }

        // 获取订单商品
        $order_goods = $model_order->getOrderGoodsList(array(
            'order_id' => $order_id
        ));
        if (empty($order_goods)) {
            showMessage(Language::get('member_evaluation_order_notexists'), 'index.php?act=member_order', 'html', 'error');
        }
        //*****************************************20150819 tjz 买家追加评价修改开始********************************************************//
        //获取评论内容
        $condition = array();
        $condition['geval_frommemberid'] = $_SESSION['member_id'];
        $condition['geval_orderno'] = $order_info['order_sn'];
        $condition['geval_orderid'] = $order_id;
        $evaluate_goods_infol = $model_evaluate_goods->getEvaluateGoodsList($condition, null, "geval_id asc");

        // 判断是否为页面
        if (!$_POST) {
            for ($i = 0, $j = count($order_goods); $i < $j; $i++) {
                $order_goods[$i]['goods_image_url'] = cthumb($order_goods[$i]['goods_image'], 60, $store_info['store_id']);
            }
            // 不显示左菜单
            Tpl::output('left_show', 'order_view');
            Tpl::output('order_info', $order_info);
            Tpl::output('order_goods', $order_goods);
            Tpl::output('store_info', $store_info);
            Tpl::output('evaluate_goods', $evaluate_goods_infol);
            Tpl::output('menu_sign', 'evaluateadd');
            Tpl::showpage('evaluation.add');
        } else {
            $evaluate_goods_array = array();

            foreach ($order_goods as $value) {
                //评分
                $evaluate_score = intval($_POST['goods'][$value['goods_id']]['score']);
                //描述
                $store_desccredit = intval($_POST['store_desccredit']);
                //服务
                $store_servicecredit = intval($_POST['store_servicecredit']);
                //发货速度
                $store_deliverycredit = intval($_POST['store_deliverycredit']);
                // 默认评语
                $evaluate_comment = $_POST['goods'][$value['goods_id']]['comment'];
                if (empty($evaluate_comment)) {
                    $evaluate_comment = '';
                }
                $counts = 0;//0:表示执行新增操作  1：表示执行修改操作
                if (!empty($evaluate_goods_infol)) {
                    for ($i = 0, $j = count($evaluate_goods_infol); $i < $j; $i++) {
                        $goods_content = $evaluate_goods_infol[$i]['geval_content'];
                        if (!empty($goods_content)) {
                            //2015-9-17 tjz修改  追加评价 多个商品只追加最后一个问题
                            if ($evaluate_goods_infol[$i]['geval_goodsid'] == $value['goods_id']) {
                                $geval_id = $evaluate_goods_infol[$i]['geval_id'];
                                str_replace('|', '', $goods_content, $count);
                                if ($count < 1) {
                                    $evaluate_conent = $goods_content . "|" . $evaluate_comment;
                                    //执行更新操作
                                    $update = array();
                                    $update['geval_content'] = $evaluate_conent;
                                    $condition = array();
                                    $condition['geval_id'] = $geval_id;
                                    $result = $model_evaluate_goods->editEvaluateGoods($update, $condition);
                                    $state = $model_order->editOrder(array(
                                        'evaluation_state' => 1//第二次评价 表示其评价权利结束
                                    ), array(
                                        'order_id' => $order_id
                                    ));
                                    $counts = 1;
                                    $if_add = 1;
                                } else {
                                    showDialog('追加评价已达上限！', '', 'error');
                                    die();
                                }
                            }
                        }
                    }
                } else {
                    //无评论内容 首次评论
                    // 必须评分
                    if ($evaluate_score <= 0 || $evaluate_score > 5) {
                        showDialog(Language::get('member_evaluation_evaluate_score_null'), '', 'error');
                        return;
                    }
                    //描述评分
                    if ($store_desccredit <= 0 || $store_desccredit > 5) {
                        showDialog(Language::get('member_evaluation_seval_desccredit_null'), '', 'error');
                        return;
                    }
                    //服务评分
                    if ($store_servicecredit <= 0 || $store_servicecredit > 5) {
                        showDialog(Language::get('member_evaluation_evaluate_seval_servicecredit_null'), '', 'error');
                        return;
                    }
                    //发货速度评分
                    if ($store_deliverycredit <= 0 || $store_deliverycredit > 5) {
                        showDialog(Language::get('member_evaluation_evaluate_seval_deliverycredit_null'), '', 'error');
                        return;
                    }
                    //第一次评价 不做处理
                    $goods_content = $evaluate_comment;
                }

                if ($counts == 0) {
                    //整理数据
                    $evaluate_goods_info = array();
                    $evaluate_goods_info['geval_orderid'] = $order_id;
                    $evaluate_goods_info['geval_orderno'] = $order_info['order_sn'];
                    $evaluate_goods_info['geval_ordergoodsid'] = $value['rec_id'];
                    $evaluate_goods_info['geval_goodsid'] = $value['goods_id'];
                    $evaluate_goods_info['geval_goodsname'] = $value['goods_name'];
                    $evaluate_goods_info['geval_goodsprice'] = $value['goods_price'];
                    $evaluate_goods_info['geval_scores'] = $evaluate_score;
                    $evaluate_goods_info['geval_content'] = $goods_content;
                    $evaluate_goods_info['geval_isanonymous'] = $_POST['anony'] ? 1 : 0;
                    $evaluate_goods_info['geval_addtime'] = TIMESTAMP;
                    $evaluate_goods_info['geval_storeid'] = $store_info['store_id'];
                    $evaluate_goods_info['geval_storename'] = $store_info['store_name'];
                    $evaluate_goods_info['geval_frommemberid'] = $_SESSION['member_id'];
                    $evaluate_goods_info['geval_frommembername'] = $_SESSION['member_name'];
                    $evaluate_goods_array[] = $evaluate_goods_info;
                }
            }

            if ($counts == 0) {
                $if_add = 0;
                //评价入库
                $model_evaluate_goods->addEvaluateGoodsArray($evaluate_goods_array);


                // 添加店铺评价
                $evaluate_store_info = array();
                $evaluate_store_info['seval_orderid'] = $order_id;
                $evaluate_store_info['seval_orderno'] = $order_info['order_sn'];
                $evaluate_store_info['seval_addtime'] = time();
                $evaluate_store_info['seval_storeid'] = $store_info['store_id'];
                $evaluate_store_info['seval_storename'] = $store_info['store_name'];
                $evaluate_store_info['seval_memberid'] = $_SESSION['member_id'];
                $evaluate_store_info['seval_membername'] = $_SESSION['member_name'];
                $evaluate_store_info['seval_desccredit'] = $store_desccredit;
                $evaluate_store_info['seval_servicecredit'] = $store_servicecredit;
                $evaluate_store_info['seval_deliverycredit'] = $store_deliverycredit;
                $model_evaluate_store->addEvaluateStore($evaluate_store_info);

                // 更新订单信息并记录订单日志
                $state = $model_order->editOrder(array(
                    'evaluation_state' => 2//第一次评价 状态改成2 表示其还有机会追加评价
                ), array(
                    'order_id' => $order_id
                ));
                $model_order->editOrderCommon(array(
                    'evaluation_time' => TIMESTAMP
                ), array(
                    'order_id' => $order_id
                ));
                if ($state) {
                    $data = array();
                    $data['order_id'] = $order_id;
                    $data['log_role'] = 'buyer';
                    $data['log_msg'] = L('order_log_eval');
                    $model_order->addOrderLog($data);
                }
                // 添加会员哈金豆
                if ($GLOBALS['setting_config']['points_isuse'] == 1) {
                    $points_model = Model('points');
                    $points_model->savePointsLog('comments', array(
                        'pl_memberid' => $_SESSION['member_id'],
                        'pl_membername' => $_SESSION['member_name']
                    ));
                }
            }
            //sj 50150910 上传晒单图片开始
            //if($if_add == 1){
            //showDialog(Language::get('member_evaluation_evaluat_success'), 'index.php?act=member_order', 'succ');
            // }//判断是否有追加
            $geval_image = '';
            $num = 0;
            foreach ($_POST['evaluate_image'] as $value) {
                $num = $num + 1;
                if (!empty($value)) {
                    //截取402_344DPX后面的内容
                    $arr = explode("DPX", $value);
                    //"order_id"_"goods_id"
                    $val0 = $arr[0];

                    //文件名
                    $val1 = $arr[1];
                    $geval_image .= $val1 . ',';
                }
                if ($num % 5 == 0) {
                    //同一个评价的五张图片
                    $ids = explode("_", $val0);
                    $order_id = $ids[0];
                    $goods_id = $ids[1];
                    //根据两个id查询geval_id
                    $geval_image = rtrim($geval_image, ',');
                    $model_evaluate_goods = Model('evaluate_goods');
                    $con = array();
                    $con['geval_orderid'] = $order_id;
                    $con['geval_goodsid'] = $goods_id;
                    $geval_info = $model_evaluate_goods->getEvaluateGoodsList($con);
                    if (empty($geval_info)) {
                        showDialog(L('param_error'));
                    }
                    $update = array();
                    $update['geval_image'] = $geval_image;
                    $condition = array();
                    $condition['geval_id'] = $geval_info[0]["geval_id"];
//                    if($geval_info[0]['geval_image']!=''){
//                        return;
//                    }
                    //是否已经上传过晒单图片
                    if ($geval_info[0]['geval_image'] == '' || $geval_info[0]['geval_image'] == null) {
                        $model_evaluate_goods->editEvaluateGoods($update, $condition);
                    } else {
                        if ($geval_image == '' || $geval_image == null) {
                            $update['geval_image'] = $geval_info[0]['geval_image'];
                        } else {
                            $update['geval_image'] = $geval_info[0]['geval_image'] . ',' . $geval_image;
                        }
                        $model_evaluate_goods->editEvaluateGoods($update, $condition);
                    }

                    list ($sns_image) = explode(',', $geval_image);
                    //初始化
                    $geval_image = "";
                    $goods_url = urlShop('goods', 'index', array(
                        'goods_id' => $geval_info[0]['geval_goodsid']
                    ));
                    // 同步到sns
                    $content = "
            <div class='fd-media'>
            <div class='goodsimg'><a target=\"_blank\" href=\"{$goods_url}\"><img src=\"" . snsThumb($sns_image, 240) . "\" title=\"{$geval_info[0]['geval_goodsname']}\" alt=\"{$geval_info[0]['geval_goodsname']}\"></a></div>
            <div class='goodsinfo'>
            <dl>
            <dt><a target=\"_blank\" href=\"{$goods_url}\">{$geval_info[0]['geval_goodsname']}</a></dt>
            <dd>价格" . Language::get('nc_colon') . Language::get('currency') . $geval_info[0]['geval_goodsprice'] . "</dd>
            <dd><a target=\"_blank\" href=\"{$goods_url}\">去看看</a></dd>
            </dl>
            </div>
            </div>
            ";

                    $tracelog_model = Model('sns_tracelog');
                    $insert_arr = array();
                    $insert_arr['trace_originalid'] = '0';
                    $insert_arr['trace_originalmemberid'] = '0';
                    $insert_arr['trace_memberid'] = $_SESSION['member_id'];
                    $insert_arr['trace_membername'] = $_SESSION['member_name'];
                    $insert_arr['trace_memberavatar'] = $_SESSION['member_avatar'];
                    $insert_arr['trace_title'] = '发表了商品晒单';
                    $insert_arr['trace_content'] = $content;
                    $insert_arr['trace_addtime'] = TIMESTAMP;
                    $insert_arr['trace_state'] = '0';
                    $insert_arr['trace_privacy'] = 0;
                    $insert_arr['trace_commentcount'] = 0;
                    $insert_arr['trace_copycount'] = 0;
                    $insert_arr['trace_from'] = '1';
                    $result = $tracelog_model->tracelogAdd($insert_arr);
                }
            }

            //sj 20150909 将晒单照片更新到数据库
            //*********************************tjz 修改结束******************************************************//
            showDialog(Language::get('member_evaluation_evaluat_success'), 'index.php?act=member_order', 'succ');
        }
    }

    /**
     * 评价列表
     */
    public function listOp()
    {
        $model_evaluate_goods = Model('evaluate_goods');

        $condition = array();
        $condition['geval_frommemberid'] = $_SESSION['member_id'];
        $goodsevallist = $model_evaluate_goods->getEvaluateGoodsList($condition, 10, 'geval_id desc');
        Tpl::output('goodsevallist', $goodsevallist);
        Tpl::output('show_page', $model_evaluate_goods->showpage());

        $this->get_member_info();
        Tpl::output('menu_sign', 'evaluatemanage');
        Tpl::output('menu_sign_url', 'index.php?act=member_evaluate');
        Tpl::showpage('evaluation.index');
    }

    public function add_imageOp()
    {
        $geval_id = intval($_GET['geval_id']);

        $model_evaluate_goods = Model('evaluate_goods');
        $model_goods = Model('goods');
        $model_sns_alumb = Model('sns_album');

        $geval_info = $model_evaluate_goods->getEvaluateGoodsInfoByID($geval_id);

        if (!empty($geval_info['geval_image'])) {
            showMessage('该商品已经发表过晒单', '', '', 'error');
        }

        if ($geval_info['geval_frommemberid'] != $_SESSION['member_id']) {
            showMessage(L('param_error'), '', '', 'error');
        }
        Tpl::output('geval_info', $geval_info);

        $goods_info = $model_goods->getGoodsInfo(array(
            'goods_id' => $geval_info['geval_goodsid']
        ));
        Tpl::output('goods_info', $goods_info);

        $ac_id = $model_sns_alumb->getSnsAlbumClassDefault($_SESSION['member_id']);
        Tpl::output('ac_id', $ac_id);

        // 不显示左菜单
        Tpl::output('left_show', 'order_view');
        Tpl::showpage('evaluation.add_image');
    }

    public function add_image_saveOp()
    {
        $geval_id = intval($_POST['geval_id']);
        $geval_image = '';
        foreach ($_POST['evaluate_image'] as $value) {
            if (!empty($value)) {
                $geval_image .= $value . ',';
            }
        }
        $geval_image = rtrim($geval_image, ',');

        $model_evaluate_goods = Model('evaluate_goods');

        $geval_info = $model_evaluate_goods->getEvaluateGoodsInfoByID($geval_id);
        if (empty($geval_info)) {
            showDialog(L('param_error'));
        }

        $update = array();
        $update['geval_image'] = $geval_image;
        $condition = array();
        $condition['geval_id'] = $geval_id;
        $result = $model_evaluate_goods->editEvaluateGoods($update, $condition);

        list ($sns_image) = explode(',', $geval_image);
        $goods_url = urlShop('goods', 'index', array(
            'goods_id' => $geval_info['geval_goodsid']
        ));
        // 同步到sns
        $content = "
            <div class='fd-media'>
            <div class='goodsimg'><a target=\"_blank\" href=\"{$goods_url}\"><img src=\"" . snsThumb($sns_image, 240) . "\" title=\"{$geval_info['geval_goodsname']}\" alt=\"{$geval_info['geval_goodsname']}\"></a></div>
            <div class='goodsinfo'>
            <dl>
            <dt><a target=\"_blank\" href=\"{$goods_url}\">{$geval_info['geval_goodsname']}</a></dt>
            <dd>价格" . Language::get('nc_colon') . Language::get('currency') . $geval_info['geval_goodsprice'] . "</dd>
            <dd><a target=\"_blank\" href=\"{$goods_url}\">去看看</a></dd>
            </dl>
            </div>
            </div>
            ";

        $tracelog_model = Model('sns_tracelog');
        $insert_arr = array();
        $insert_arr['trace_originalid'] = '0';
        $insert_arr['trace_originalmemberid'] = '0';
        $insert_arr['trace_memberid'] = $_SESSION['member_id'];
        $insert_arr['trace_membername'] = $_SESSION['member_name'];
        $insert_arr['trace_memberavatar'] = $_SESSION['member_avatar'];
        $insert_arr['trace_title'] = '发表了商品晒单';
        $insert_arr['trace_content'] = $content;
        $insert_arr['trace_addtime'] = TIMESTAMP;
        $insert_arr['trace_state'] = '0';
        $insert_arr['trace_privacy'] = 0;
        $insert_arr['trace_commentcount'] = 0;
        $insert_arr['trace_copycount'] = 0;
        $insert_arr['trace_from'] = '1';
        $result = $tracelog_model->tracelogAdd($insert_arr);

        if ($result) {
            showDialog(L('nc_common_save_succ'), urlShop('member_evaluate', 'list'), 'succ');
        } else {
            showDialog(L('nc_common_save_succ'), urlShop('member_evaluate', 'list'));
        }
    }
}
