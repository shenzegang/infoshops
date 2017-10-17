<?php defined('CorShop') or exit('Access Invalid!');?>
<div class="ncc-bottom">
	<a href="javascript:void(0)" id='submitOrder'
		class="ncc-btn ncc-btn-acidblue fr"><?php echo $lang['cart_index_submit_order'];?></a>
</div>
<script>
function submitNext(){
    if ($('#address_id').val() == ''){
		showDialog('<?php echo $lang['cart_step1_please_set_address'];?>', 'error','','','','','','','','',2);
		return;
	}
	//sj 20150908
	if ($('#storage_error').val() == '1'){
		showDialog('商品库存不足或已下架', 'error','','','','','','','','',2);
		return;
	}
	if ($('#buy_city_id').val() == '') {
		showDialog('正在计算运费,请稍后', 'error','','','','','','','','',2);
		return;
	}
	if ($('input[name="points-check"]').attr('checked') && $('#points_num').val() == '') {
		showDialog(' 使用积分支付，输入积分点数', 'error','','','','','','','','',2);
		return;
	}
	
	$('#order_form').submit();
}
$(function(){
	$('#submitOrder').on('click',function(){submitNext()});
	calcOrder();
});
</script>