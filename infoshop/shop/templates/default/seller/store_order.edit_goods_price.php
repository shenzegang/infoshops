<div class="eject_con">
    <?php if ($output['order_info']) { ?>

        <form id="changeform" method="post" action="index.php?act=store_order&op=change_state&state_type=goods_price&order_id=<?php echo $output['order_info']['order_id']; ?>" onsubmit="return checkPrice();">
            <input type="hidden" name="form_submit" value="ok"/>
            <dl>
                <dt><?php echo $lang['store_order_buyer_with'] . $lang['nc_colon']; ?></dt>
                <dd><?php echo $output['order_info']['buyer_name']; ?></dd>
            </dl>
            <dl>
                <dt><?php echo $lang['store_order_sn'] . $lang['nc_colon']; ?></dt>
                <dd>
                    <span class="num"><?php echo $output['order_info']['order_sn']; ?></span>
                </dd>
            </dl>
            <dl>
                <dt><?php echo '商品价格' . $lang['nc_colon']; ?></dt>
                <dd>
                    <input type="text" class="text" id="goods_amount" name="goods_amount" onchange="checkPrice();"
                           value="<?php echo $output['order_info']['goods_amount']; ?>"/>
                    <span id="warning"></span>
                </dd>
            </dl>
            <dl class="bottom">
                <dt>&nbsp;</dt>
                <dd>
                    <input type="submit" class="submit" id="confirm_button"
                           value="<?php echo $lang['nc_ok']; ?>"/>
                </dd>
            </dl>
        </form>
    <?php } else { ?>
        <p style="line-height: 80px; text-align: center">该订单并不存在，请检查参数是否正确!</p>
    <?php } ?>
</div>
<script type="text/javascript">

    function checkPrice() {
        var price = $("#goods_amount").val();
        if (isNaN(price)) {
            $("#warning").html("<cite style='color: red;'>请输入数字</cite>");
            return false;
        }else{
            if(price<0.01){
                $("#warning").html("<cite style='color: red;'>金额不能低于0.01元</cite>");
                return false;
            }else{
                $("#warning").html("");
                return true;
            }
        }
    }


</script>