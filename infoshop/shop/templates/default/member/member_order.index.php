<?php defined('CorShop') or exit('Access Invalid!'); ?>
<link rel="stylesheet" type="text/css"
      href="<?php echo RESOURCE_SITE_URL; ?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"/>
<style type="text/css">
    .store-name {
        width: 130px;
        display: inline-block;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }
</style>

<div class="wrap">
    <div class="tabmenu">
        <?php include template('layout/submenu'); ?>
    </div>
    <form method="get" action="index.php" target="_self">
        <table class="search-form">
            <input type="hidden" name="act" value="<?php echo $_GET['act']; ?>"/>
            <tr>
                <td></td>
                <th><?php echo $lang['member_order_time'] . $lang['nc_colon']; ?></th>
                <td class="w180"><input type="text" class="text"
                                        name="query_start_date" id="query_start_date"
                                        value="<?php echo $_GET['query_start_date']; ?>"/> &#8211; <input
                        type="text" class="text" name="query_end_date" id="query_end_date"
                        value="<?php echo $_GET['query_end_date']; ?>"/></td>
                <th><?php echo $lang['member_order_sn'] . $lang['nc_colon']; ?></th>
                <td class="w160"><input type="text" class="text" name="order_sn"
                                        value="<?php echo $_GET['order_sn']; ?>"></td>
                <th><?php echo $lang['member_order_state'] . $lang['nc_colon']; ?></th>
                <td class="w100"><select name="state_type">
                        <option value=""
                            <?php echo $_GET['state_type'] == '' ? 'selected' : ''; ?>><?php echo $lang['member_order_all']; ?></option>
                        <option value="state_new"
                            <?php echo $_GET['state_type'] == 'state_new' ? 'selected' : ''; ?>>待付款
                        </option>
                        <option value="state_pay"
                            <?php echo $_GET['state_type'] == 'state_pay' ? 'selected' : ''; ?>>待发货
                        </option>
                        <option value="state_send"
                            <?php echo $_GET['state_type'] == 'state_send' ? 'selected' : ''; ?>>待收货
                        </option>
                        <option value="state_success"
                            <?php echo $_GET['state_type'] == 'state_success' ? 'selected' : ''; ?>>已完成
                        </option>
                        <option value="state_noeval"
                            <?php echo $_GET['state_type'] == 'state_noeval' ? 'selected' : ''; ?>>待评价
                        </option>
                        <option value="state_cancel"
                            <?php echo $_GET['state_type'] == 'state_cancel' ? 'selected' : ''; ?>>已取消
                        </option>
                    </select></td>
                <td class="w90 tc"><input type="submit" class="submit"
                                          value="<?php echo $lang['member_order_search']; ?>"/></td>
            </tr>
        </table>
    </form>
    <table class="order ncu-table-style">
        <thead>
        <tr>
            <th class="w10"></th>
            <th colspan="2" align="left">商品/订单号</th>
            <th class="w120">单价（元）/数量</th>
            <th class="w120">实付款（元）</th>
            <th class="w70"><?php echo $lang['nc_handle']; ?></th>
        </tr>
        </thead>
        <?php if ($output['order_group_list']) { ?>
            <?php foreach ($output['order_group_list'] as $order_pay_sn => $group_info) { ?>
                <?php $p = 0; ?>
            <tbody <?php if (!empty($group_info['pay_amount']) && $p == 0) { ?>
                class="pay" <?php } ?>>
                <?php foreach ($group_info['order_list'] as $order_id => $order_info) { ?>
                    <?php if ($order_info['del_state'] == 0) { ?>
                        <?php if (empty($group_info['pay_amount'])) { ?>
                            <tr>
                                <td colspan="19" class="sep-row"></td>
                            </tr>
                        <?php } ?>
                        <?php if (!empty($group_info['pay_amount']) && $p == 0) { ?>
                            <tr>
                                <td colspan="19" class="sep-row"></td>
                            </tr>
                            <tr>
                            <td colspan="19" class="pay-td"><span class="mr50 ml15">下单时间：<time><?php echo date('Y-m-d H:i:s', $group_info['add_time']); ?></time></span>

                                <span>在线支付金额：<em>￥<?php echo ncPriceFormat($group_info['pay_amount']); ?></em></span>
                                <a class="ncu-btn7 fr mr15"
                                   href="index.php?act=buy&op=pay&pay_sn=<?php echo $order_pay_sn; ?>">订单支付</a></td>
                            </tr><?php } ?>
                        <?php $p++; ?>
                        <tr>
                            <th colspan="19"><span class="fl ml10"> <!-- order_sn -->
                                    <?php echo $lang['member_order_sn'] . $lang['nc_colon']; ?><span
                                        class="goods-num"><em><?php echo $order_info['order_sn']; ?></em></span></span>

                                <!-- order_time --> <span
                                    class="fl ml20"><?php echo $lang['member_order_time'] . $lang['nc_colon']; ?><em
                                        class="goods-time"><?php echo date("Y-m-d H:i:s", $order_info['add_time']); ?></em></span>

                                <!-- store_name --> <span class="fl ml10"> <a
                                        href="<?php echo urlShop('show_store', 'index', array('store_id' => $order_info['store_id']), $order_info['extend_store']['store_domain']); ?>"
                                        target="_blank"
                                        title="<?php echo $order_info['store_name']; ?>"><?php echo $order_info['store_name']; ?></a></span>

                                <!-- QQ --> <span class="fl"
                                                  member_id="<?php echo $order_info['extend_store']['member_id']; ?>"><?php if (!empty($order_info['extend_store']['store_qq'])) { ?>
                                        <a target="_blank"
                                           href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $order_info['extend_store']['store_qq']; ?>&site=qq&menu=yes"
                                           title="QQ: <?php echo $order_info['extend_store']['store_qq']; ?>"><img
                                                border="0"
                                                src="http://wpa.qq.com/pa?p=2:<?php echo $order_info['extend_store']['store_qq']; ?>:52"
                                                style="vertical-align: middle;"/></a>
                                    <?php } ?>

                                    <!-- wang wang -->
                                    <?php if (!empty($order_info['extend_store']['store_ww'])) { ?>
                                    <a target="_blank"
                                       href="http://amos.im.alisoft.com/msg.aw?v=2&uid=<?php echo $order_info['extend_store']['store_ww']; ?>&site=cntaobao&s=2&charset=<?php echo CHARSET; ?>"
                                       class="vm"><img border="0"
                                                       src="http://amos.im.alisoft.com/online.aw?v=2&uid=<?php echo $order_info['extend_store']['store_ww']; ?>&site=cntaobao&s=2&charset=<?php echo CHARSET; ?>"
                                                       alt="Wang Wang" style="vertical-align: middle;"/>
                                        </a><?php } ?></span>

                                <!-- 订单查看 --> <span class="fl ml10"><a
                                        href="index.php?act=member_order&op=show_order&order_id=<?php echo $order_info['order_id']; ?>"
                                        target="_blank"
                                        class="nc-show-order"><i></i><?php echo $lang['member_order_view_order']; ?>
                                    </a></span>


                            </th>
                        </tr>


                        <!-- S 商品列表 -->
                        <?php foreach ((array)$order_info['extend_order_goods'] as $k => $goods_info) { ?>
                            <tr>
                            <td class="w10 bdl"></td>
                            <td class="w70">
                                <div class="goods-pic-small">
						<span class="thumb size60"><i></i><a
                                href="<?php echo urlShop('goods', 'index', array('goods_id' => $goods_info['goods_id'])); ?>"
                                target="_blank"><img src="<?php echo thumb($goods_info, 60); ?>"/></a></span>
                                </div>
                            </td>
                            <td>
                                <dl class="goods-name">
                                    <dt><?php if (!empty($goods_info['is_gift'])) { ?><span
                                            title="该商品为积分兑换礼品，消耗<?php echo $goods_info['gift_points']; ?>积分">
                                                礼</span><?php } ?><a
                                            href="<?php echo urlShop('goods', 'index', array('goods_id' => $goods_info['goods_id'])); ?>"
                                            target="_blank"><?php echo $goods_info['goods_name']; ?></a>
                                    </dt>
                                    <?php if ($goods_info['goods_type'] > 1) { ?>
                                        <dd><?php echo orderGoodsType($goods_info['goods_type']); ?></dd>
                                    <?php } ?>
                                </dl>
                            </td>
                            <td class="goods-price w120"><i><?php echo $goods_info['goods_price']; ?>
                                    x <?php echo $goods_info['goods_num']; ?></i><?php if ($goods_info['refund'] == 1) { ?>
                                    <p>
                                        <a href="javascript:void(0)" nc_type="dialog"
                                           dialog_title="<?php if ($order_info['order_state'] == 40) {
                                               echo '申请售后';
                                           } else {
                                               echo '退款/退货';
                                           } ?>" dialog_id=" member_goods_refund" dialog_width="480"
                                           uri="index.php?act=member_refund&op=add_refund&order_id=<?php echo $order_info['order_id']; ?>
                                        &goods_id=<?php echo $goods_info['rec_id']; ?>"
                                           id="order<?php echo $order_info['order_id']; ?>
                                        _<?php echo $goods_info['goods_id']; ?>action_refund">
                                            <?php
                                            if ($order_info['order_state'] == 40) {
                                                echo '申请售后';
                                            } else {
                                                echo '退款/退货';
                                            }
                                            ?></a>
                                    </p>
                                <?php } ?>
                                <?php if ($goods_info['is_refund'] == 2) { ?><p
                                    style="color: #999;font-weight:inherit;">退款退货中</p><?php } ?>
                            </td>

                            <?php if ((count($order_info['extend_order_goods']) > 1 && $k == 0) || (count($order_info['extend_order_goods']) == 1)) { ?>
                                <td class="w120 bdl"
                                    rowspan="<?php echo count($order_info['extend_order_goods']); ?>">
                                    <?php if ($order_info['payment_name']) { ?>
                                        <p class="goods-pay"
                                           title="<?php echo $lang['member_order_pay_method'] . $lang['nc_colon']; ?><?php echo $order_info['payment_name']; ?>"><?php echo $order_info['payment_name']; ?></p>
                                    <?php } ?>
                                    <p class="goods-price">
                                        <strong><?php echo $order_info['order_amount']; ?></strong>
                                    </p>

                                    <p class="goods-freight">
                                        <?php if ($order_info['shipping_fee'] > 0) { ?>
                                            (<?php echo $lang['member_order_shipping_han']; ?>运费<?php echo $order_info['shipping_fee']; ?>)
                                        <?php } else { ?>
                                            <?php echo $lang['nc_common_shipping_free']; ?>
                                        <?php } ?>
                                    </p>
                                </td>
                                <td class="bdl bdr w120"
                                    rowspan="<?php echo count($order_info['extend_order_goods']); ?>">
                                    <p>
                                        <?php echo $order_info['state_desc']; ?>
                                        <br/><?php echo $order_info['evaluation_status'] ? $lang['member_order_evaluated'] . '<br/>' : ''; ?>
                                    </p>

                                    <!-- 取消订单 -->
                                    <?php if ($order_info['if_cancel']) { ?>
                                        <p>
                                            <a href="javascript:void(0)"
                                               style="color: #F30; text-decoration: underline;" nc_type="dialog"
                                               dialog_width="480"
                                               dialog_title="<?php echo $lang['member_order_cancel_order']; ?>"
                                               dialog_id="buyer_order_cancel_order"
                                               uri="index.php?act=member_order&op=change_state&state_type=order_cancel&order_id=<?php echo $order_info['order_id']; ?>"
                                               id="order<?php echo $order_info['order_id']; ?>_action_cancel"><?php echo $lang['member_order_cancel_order']; ?></a>
                                        </p>
                                    <?php } ?>

                                    <!-- 物流跟踪 -->
                                    <?php if ($order_info['if_deliver']) { ?>
                                        <p>
                                            <a
                                                href='index.php?act=member_order&op=search_deliver&order_id=<?php echo $order_info['order_id']; ?>&order_sn=<?php echo $order_info['order_sn']; ?>'
                                                target=‘_blank’><?php echo $lang['member_order_show_deliver'] ?></a>
                                        </p>
                                    <?php } ?>

                                    <!-- 投诉 -->
                                    <?php if ($order_info['if_complain'] === true) { ?>
                                        <p>
                                            <a
                                                href='index.php?act=member_complain&op=complain_new&order_id=<?php echo $order_info['order_id']; ?>'
                                                target="_blank">投诉</a>
                                        </p>
                                    <?php } else if ($order_info['if_complain'] == 3) { ?>
                                        <p>
                                            <a
                                                href='index.php?act=member_complain&op=complain_show&complain_id=<?php echo $order_info['complain_id']; ?>'
                                                target="_blank">已投诉</a>
                                        </p>
                                    <?php } ?>

                                    <!-- 取消订单 -->
                                    <?php if ($order_info['if_refund_cancel']) { ?>
                                        <p>
                                            <a href="javascript:void(0)"
                                               style="color: #F30; text-decoration: underline;" nc_type="dialog"
                                               dialog_title="取消订单" dialog_id="member_order_refund"
                                               dialog_width="480"
                                               uri="index.php?act=member_refund&op=add_refund_all&order_id=<?php echo $order_info['order_id']; ?>"
                                               id="order<?php echo $order_info['order_id']; ?>_action_refund">取消订单</a>
                                        </p>
                                    <?php } ?>

                                    <!-- 收货 -->
                                    <?php
                                    if ($order_info['if_receive']&&$order_info['order_state']==30) { ?>
                                        <p>
                                            <a href="javascript:void(0)" class="ncu-btn7 mt5" nc_type="dialog"
                                               dialog_id="buyer_order_confirm_order" dialog_width="480"
                                               dialog_title="<?php echo $lang['member_order_ensure_order']; ?>"
                                               uri="index.php?act=member_order&op=change_state&state_type=order_receive&order_sn=<?php echo $order_info['order_sn']; ?>&order_id=<?php echo $order_info['order_id']; ?>"
                                               id="order<?php echo $order_info['order_id']; ?>_action_confirm"><?php echo $lang['member_order_ensure_order']; ?></a>
                                        </p>
                                    <?php } ?>

                                    <!-- 评价 -->
                                    <?php if ($order_info['if_evaluation']) { ?>
                                        <p>
                                            <a class="ncu-btn6 mt5"
                                               href="index.php?act=member_evaluate&op=add&order_id=<?php echo $order_info['order_id']; ?>">

                                                <?php
                                                //20150820 tjz增加 追加评价选项
                                                if ($order_info['evaluation_state'] == 2) {
                                                    echo "追加评价";
                                                } else {
                                                    echo $lang['member_order_want_evaluate'];

                                                }
                                                ?></a>
                                        </p>
                                    <?php } ?>

                                    <!-- 已经评价 -->
                                    <?php if ($order_info['evaluation_state'] == 1) {
                                        echo $lang['order_state_eval'];
                                    } ?>

                                    <!--删除-->
                                    <?php
                                    if ($order_info['evaluation_state'] != 0 && $order_info['order_state'] == 40 || $order_info['order_state'] == 50) {
                                        ?>
                                        <p>
                                            <a
                                                href='javascript:;'
                                                onclick="drop_order_item(<?php echo $order_info['order_id']; ?>)">删除</a>
                                        </p>
                                    <?php } ?>

                                    <!-- 分享  -->
                                    <?php if ($order_info['if_share']) { ?>
                                        <p>
                                            <a href="javascript:void(0)" class="ncu-btn2 mt5"
                                               nc_type="sharegoods"
                                               data-param='{"gid":"<?php echo $order_info['extend_order_goods'][0]['goods_id']; ?>"}'><i></i>分享商品</a>
                                        </p>
                                    <?php } ?>
                                </td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>
                    </tbody>
                <?php } ?>
            <?php } ?>
        <?php } else { ?>
            <tbody>
            <tr>
                <td colspan="19" class="norecord"><i>&nbsp;</i><span><?php echo $lang['no_record']; ?></span></td>
            </tr>
            </tbody>
        <?php } ?>

        <?php if ($output['order_pay_list']) { ?>
            <tfoot>
            <tr>
                <td colspan="19">
                    <div class="pagination"> <?php echo $output['show_page']; ?> </div>
                </td>
            </tr>
            </tfoot>
        <?php } ?>
    </table>
</div>
<script charset="utf-8" type="text/javascript"
        src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery-ui/i18n/zh-CN.js"></script>
<script charset="utf-8" type="text/javascript"
        src="<?php echo RESOURCE_SITE_URL; ?>/js/sns.js"></script>
<script type="text/javascript">
    $(function () {
        $('#query_start_date').datepicker({dateFormat: 'yy-mm-dd'});
        $('#query_end_date').datepicker({dateFormat: 'yy-mm-dd'});
    });

    function drop_order_item(order_id) {
        showDialog('删除后不可恢复，是否确定？', 'confirm', '', function () {
            $.getJSON('index.php?act=member_order&op=del&order_id=' + order_id, function () {
                window.location.reload();
            });
        });

    }
</script>
