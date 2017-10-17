<?php defined('CorShop') or exit('Access Invalid!');?>
<style type="text/css">
	.error{
		color:#F00;
		font-weight:bold;
	}
</style>
<div class="ncc-main">
	<div class="ncc-title">
		<h3>余额充值</h3>
		<h5>
			查看充值记录可以通过<a href="index.php?act=predeposit&op=index" target="_blank">我的充值列表
			</a>进行查看。
		</h5>
	</div>
	<form action="index.php?act=payment" method="POST" id="buy_form">
		<input type="hidden" id="payment_code" name="payment_code" value="">
         <input type="hidden" name="order_type" value="pd_rechange">
				<div class="ncc-receipt-info" style="display:block">
				<h3 style="float:left; display:inline">充值金额：</h3>
                <input name="pdr_amount" type="text" class="text w50 mr5" id="pdr_amount" maxlength="8" />
                <em>元</em>
		</div>
        <div style="clear:both; height:5px;"></div>
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
        <li payment_code="<?php echo $val['payment_code']; ?>"><label
					for="pay_<?php echo $val['payment_code']; ?>"> <i></i>
						<div class="logo" for="pay_<?php echo $val['payment_id']; ?>">
							<img
								src="<?php echo SHOP_TEMPLATES_URL?>/images/payment/<?php echo $val['payment_code']; ?>_logo.gif" />
						</div>
						<div class="predeposit" nc_type="predeposit" style="display: none">
            <?php if ($val['payment_code'] == 'predeposit') {?>
                <?php if ($output['available_predeposit']) {?>
                <p>
								当前预存款余额<br />￥<?php echo $output['available_predeposit'];?><br />不足以支付该订单<br />
								<a
									href="<?php echo SHOP_SITE_URL.'/index.php?act=predeposit';?>">马上充值</a>
							</p>
                <?php } else {?>
                <input type="password" class="text w120" name="password"
								maxlength="40" id="password" value="">
							<p>使用站内预存款进行支付时，需输入您的登录密码进行安全验证。</p>
                <?php } ?>
            <?php } ?>
          </div>
				</label></li>
        <?php } ?>
      </ul>
      <?php } ?>
    </div>
		<div class="ncc-bottom tc mb50">
			<!--<body onkeydown="checkEnterKey(event)">-->
			<a target="_blank" id="next_button" class="ncc-btn ncc-btn-green" ><i class="icon-shield" ></i>确认提交支付</a>
			
		</div>
	</form>
</div>
<script type="text/javascript">
$(function(){
    $('.ncc-payment-list > li').on('click',function(){
    	$('.ncc-payment-list > li').removeClass('using');
        $(this).addClass('using');
        $('#payment_code').val($(this).attr('payment_code'));
    });
    $('#next_button').on('click',function(){
		if($('#pdr_amount').val() == ''){
			showDialog('请输入充值金额', 'error','','','','','','','','',2);return false;
		}else if($('#payment_code').val() == ''){
			showDialog('请选择支付方式', 'error','','','','','','','','',2);return false;
		}else{
			 $('#buy_form').submit();
		}
    });
	
	$('#buy_form').validate({
        errorPlacement: function(error, element){
            
            $(element).parent('p').next('em').after(error);
        },
		
        rules : {
        	pdr_amount      : {
	        	required  : true,
	            number    : true,
	            min       : 0.01
            }
        },
        messages : {
        	pdr_amount		: {
            	required  :'&nbsp;&ensp;充值金额不能为空',
            	number    :'&nbsp;&ensp;充值金额必须为数字',
                min    	  :'&nbsp;&ensp;充值金额不能小于0.01'
            }
        }
    });
});
function checkEnterKey(evt) {
	if (evt.keyCode == 13){
		if ($('#payment_code').val() != '') {
			$('#buy_form').submit();
		}
}
}
</script>