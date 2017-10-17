<?php defined('CorShop') or exit('Access Invalid!');?>

<div class="ncc-main">
	<div class="ncc-title">
		<h3><?php echo $lang['cart_index_payment'];?></h3>
		<h5>
			订单详情内容可通过查看<a href="index.php?act=member_order" target="_blank">我的订单</a>进行核对处理。
		</h5>
	</div>
	<form action="index.php?act=payment" method="POST" id="buy_form" >
		<input type="hidden" name="pay_sn"
			value="<?php echo $output['pay_info']['pay_sn'];?>"> <input
			type="hidden" id="payment_code" name="payment_code" value=""> <input
			type="hidden" name="order_type" value="product_buy">
			<div class="ncc-receipt-info">
				<div class="ncc-receipt-info-title">
				<h3><?php echo $output['order_remind'];?><?php echo $output['pay_remind'];?></h3>
			</div>
			<table class="ncc-table-style">
				<thead>
					<tr>
						<th class="w50"></th>
						<th class="w250 tl">订单号</th>
						<th class="tl">支付方式</th>
						<th class="w150">金额</th>
						<th class="w150">物流</th>
					</tr>
				</thead>
				<tbody>
				  <?php if (count($output['order_list'])>1) {?>
                  <tr>
                        <th colspan="20">由于您的商品由不同商家发出，此单将分为2个不同子订单配送！</th>
                  </tr>
                  <?php }?>
          		<?php foreach ($output['order_list'] as $key => $order) {?>
          		  <tr>
						<td></td>
						<td class="tl"><?php echo $order['order_sn']; ?></td>
						<td class="tl"><?php echo $order['payment_state'];?></td>
						<td>￥<?php echo $order['order_amount'];?></td>
						<td>快递</td>
			     </tr>
          	<?php } ?>
        </tbody>
			</table>
		</div>
		<div class="ncc-receipt-info">
      <?php if (!isset($output['payment_list'])) {?>
      <?php }else if (empty($output['payment_list'])){?>
      <div class="nopay"><?php echo $lang['cart_step2_paymentnull_1']; ?> <a
					href="index.php?act=home&op=sendmsg&member_id=<?php echo $output['order']['seller_id'];?>"><?php echo $lang['cart_step2_paymentnull_2'];?></a> <?php echo $lang['cart_step2_paymentnull_3'];?></div>
      <?php } else {?>
      <div class="ncc-receipt-info-title">
				<h3>支付选择</h3>
			</div>
			<ul class="ncc-payment-list">
        <?php foreach($output['payment_list'] as $val) { ?>
        <li payment_code="<?php echo $val['payment_code']; ?>">
        	<label for="pay_<?php echo $val['payment_code']; ?>"> 
            	<i></i>
                    <div class="logo" for="pay_<?php echo $val['payment_id']; ?>">
                        <img
                            src="<?php echo SHOP_TEMPLATES_URL?>/images/payment/<?php echo $val['payment_code']; ?>_logo.gif" />
                    </div>
					<div class="predeposit" nc_type="predeposit" style="display:none">
            			<?php if ($val['payment_code'] == 'predeposit') {?>
                			<!--
                			<input type="password" class="text w120" name="password" maxlength="40" id="password" value="">
                            <input type="hidden" value="" name="password_callback" id="password_callback" />
							<p>使用站内预存款进行支付时，需输入您的支付密码进行安全验证。</p>
                			-->
                            <a id="paypasswd" href="javascript:void(0)" uri="index.php?act=member&op=loadPaywd" dialog_width="480" dialog_id="pay_passwd" dialog_title="安全验证"  nc_type="dialog" title="输入支付密码">></a>
            			<?php }else if($val['payment_code'] == 'weichat'){ ?>
                        	<a id="pay_weichat" href="javascript:void(0)" uri="index.php?act=payment&op=weichat&pay_type=product_buy&pay_sn=<?php echo $output['pay_info']['pay_sn'];?>" dialog_width="400" dialog_id="pay_weichat" dialog_title="微信支付"  nc_type="dialog" title="11"></a>
                        <?php } ?>
          			</div>
				</label></li>
        <?php } ?>
      </ul>
      <?php } ?>
    </div>
    <?php if ($output['pay_amount_online'] > 0) {?>
    <div class="ncc-bottom tc mb50">
      <body onkeydown="checkEnterKey(event)">
			<a href="javascript:void(0);" id="next_button" class="ncc-btn ncc-btn-green"><i class="icon-shield"></i>确认提交支付</a>
   	  </body>
    </div>
    <?php }?>
  </form>
</div>
<script type="text/javascript">
$(function(){
    $('.ncc-payment-list > li').on('click',function(){
    	$('.ncc-payment-list > li').removeClass('using');
		$('#password').val('');
        $(this).addClass('using');
		var paypasswd = '<?php echo $output['paypasswd']; ?>';
		if(paypasswd == ''){
			showDialog('您的支付密码尚未设置，不能使用预存款支付', 'error','','window.location.href=\'index.php?act=member&op=security&t=unpay\'','','','','','',1,2);return false;
		}
		if($(this).attr('payment_code') == 'predeposit'){
			$("#next_button").removeClass("ncc-btn-green");
			document.getElementById("paypasswd").click();
		}else if($(this).attr('payment_code') == 'weichat'){
			$("#next_button").removeClass("ncc-btn-green");
			document.getElementById("pay_weichat").click();
		}else{
			$("#next_button").addClass("ncc-btn-green");	
		}
        $('#payment_code').val($(this).attr('payment_code'));
		
    });
    $('#next_button').on('click',function(){
        if ($('#payment_code').val() == '') {
        	showDialog('请选择支付方式', 'error','','','','','','','','',2);return false;
        }
		if($('#payment_code').val() == 'predeposit'){
			//document.getElementById("confirm_yes").click();
		}else{
			$('#buy_form').submit();
		}    
    });
});

function checkEnterKey(evt) {
    if (evt.keyCode == 13){
        $('#next_button').click();
    }
}

</script>