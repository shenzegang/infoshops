<?php defined('CorShop') or exit('Access Invalid!');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>

<div class="ncsc-form-default">
	<form method="post" action="index.php?act=store_sms_conf&op=buysms"
		enctype="multipart/form-data" id="my_store_form">
		<input type="hidden" name="form_submit" value="ok" /> <input
			type="hidden" name="id" value="<?php echo $output['rsInfo']['id'];?>" />
		<dl>
			<dt> <?php echo  $lang['store_sms_conf_smstel'].$lang['nc_colon'];?></dt>
			<dd>
        <?php echo $output['rsInfo']['tel'];?>
      </dd>
		</dl>
		<dl>

			<dl>
				<dt><?php echo $lang['store_sms_conf_buynum'].$lang['nc_colon'];?></dt>
				<dd>
					<input class="w200 text" name="buynum" type="text" id="buynum"
						onkeyup="value=value.replace(/[^\d]/g,'') "
						onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d.]/g,''))"
						value="" /> <?php echo $lang['store_sms_conf_tiao'];?>
		<p>短信每次购买数量不能少于<?php echo $output['list_setting']['sms_smallbuynum']?>条，每条的短信售价为<?php echo $output['list_setting']['sms_sellprice']?>元</p>
					<p class="hint">
						<strong style="color: red">相关费用会在店铺的账期结算中扣除</strong>
					</p>
				</dd>
			</dl>
			<div class="bottom">
				<label class="submit-border"><input type="submit" class="submit"
					value="<?php echo $lang['store_buysms_class_submit'];?>" /></label>
			</div>
	
	</form>
</div>
