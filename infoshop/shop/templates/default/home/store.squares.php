<?php defined('CorShop') or exit('Access Invalid!'); ?>
<style type="text/css">
    #box {
        background: #FFF;
        width: 238px;
        height: 410px;
        margin: -390px 0 0 0;
        display: block;
        border: solid 4px #D93600;
        position: absolute;
        z-index: 999;
        opacity: .5
    }

    .shopMenu {
        position: fixed;
        z-index: 1;
        right: 25%;
        top: 0;
    }

    #store_info p {
        padding: 0px 0px 10px 0px;
    }

    #store_goodsInfo a {
        float: left;
    }

    #store_goodsInfo {
        width: 488px;
        padding-top: 30px;
    }
</style>
<div class="squares" nc_type="current_display_mode">

    <?php if (empty($output['store_list']) && is_array($output['store_list'])) { ?>
        <div id="no_results" class="no-results">
            <i></i>没有找到符合条件的店铺
        </div>
    <?php } else { ?>
        <?php
        $g = -1;
        foreach ($output['store_list'] as $value) {
            $g++;
            ?>
            <ul class="list_pic" style="">
                <li class="item">
                    <div class="goods-content"
                         nctype_goods=" <?php echo $value['goods_id']; ?>"
                         nctype_store="<?php echo $value['store_id']; ?>">
                        <div class="goods-pic">
                            <a
                                href="<?php echo urlShop('show_store', 'index', array('store_id' => $value['store_id']), $value['store_domain']); ?>"
                                target="_blank" title="<?php echo $value['store_name']; ?>"><img
                                    src="<?php echo UPLOAD_SITE_URL . '/' . ATTACH_STORE . '/' . $value['store_label']; ?>"
                                    title="<?php echo $value['store_name']; ?>"
                                    alt="<?php echo $value['store_name']; ?>"/></a>
                        </div>
                        <div class="goods-info">
                            <div class="store">
                                <a
                                    href="<?php echo urlShop('show_store', 'index', array('store_id' => $value['store_id']), $value['store_domain']); ?>"
                                    title="<?php echo $value['store_name']; ?>"
                                    class="name"><?php echo $value['store_name']; ?></a>
                            </div>
                        </div>
                    </div>
                </li>
                <li id="store_info">
                    <a href="<?php echo urlShop('show_store', 'index', array('store_id' => $value['store_id']), $value['store_domain']); ?>" target="_blank"
                       style="color: #0063DC;text-decoration: none;cursor: pointer;;font-size: 14px;font-weight: bold;line-height: 24px;"><?php echo $value['store_name']; ?></a>

                    <p style="padding-top: 10px;">卖家:<?php echo $value['seller_name']; ?></p>

                    <p>地区:<?php echo $value['area_info']; ?></p>

                    <div class="store-score" style="border-bottom: none;">
                        <strong>信用等级：</strong>

                        <p>
                            <?php for ($i = 1; $i <= intval($value['store_score']); $i++) { ?>
                                <span></span>
                            <?php } ?>
                        </p>
                    </div>
                    <p>销量：<?php echo $value['store_sales'] ?></p>
                    <p>
                        共：<?php echo $value['goods_count'][$g] ?>件宝贝
                    </p>
                </li>
                <li id="store_goodsInfo">
                    <?php
                    foreach ($value['recommended_goods_list'][$g] as $store_goods_list) {
                        ?>
                        <a style="text-decoration: none;"
                           href="<?php echo urlShop('goods', 'index', array('goods_id' => $store_goods_list['goods_id'])); ?>"
                           target="_blank" title="<?php echo $store_goods_list['goods_name']; ?>">
                            <img width="150px;" height="150px;" src="<?php echo thumb($store_goods_list, 150); ?>"/>

                            <p style="clear: left;padding-left: 20px;color: #C00; font-size: 16px;">
                                价格：<?php echo ncPriceFormatForList($store_goods_list['goods_price']); ?></p>
                        </a>
                    <?php } ?>
                </li>
                <div class="clear"></div>

            </ul>
        <?php } ?>
    <?php } ?>
</div>
<form id="buynow_form" method="post"
      action="<?php echo SHOP_SITE_URL; ?>/index.php" target="_blank">
    <input id="act" name="act" type="hidden" value="buy"/> <input id="op"
                                                                  name="op" type="hidden" value="buy_step1"/> <input
        id="goods_id"
        name="cart_id[]" type="hidden"/>
</form>
<script type="text/javascript"
        src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery.raty/jquery.raty.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('.raty').raty({
            path: "<?php echo RESOURCE_SITE_URL;?>/js/jquery.raty/img",
            readOnly: true,
            width: 80,
            score: function () {
                return $(this).attr('data-score');
            }
        });
    });
</script>

