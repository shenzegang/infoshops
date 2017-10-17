<?php defined('CorShop') or exit('Access Invalid!');?>
<?php if($output['seller_deposit']['deposit_id'] == "") {?>
<script type="text/javascript">
	$(document).ready(function() {
		$('#my_store_form').validate({
			errorPlacement: function (error, element) {
				element.nextAll('span').first().after(error);
			},
			rules: {
				deposit_voucher: {
					required: true
				}
			},
			messages: {
				deposit_voucher: {
					required: '请选择上传保证金付款凭证'
				},
			}
		});
	});
</script>
<?php }?>
<style>
	.ms_tab{border:1px solid #ccc; margin-top: 20px; font-size: 12px;}
	.ms_tab tr{ line-height: 30px;}
	.ms_tab tr td{ padding-left: 10px; padding-right: 10px; background: #fff;}
	.ms_tab tr.no_data td{ line-height: 100px; font-size: 14px; text-align: center; color: #09C; font-weight: bold;}
	.w200{ border:none !important;}
</style>
<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<div class="ncsc-form-default">
	<?php if($_GET['op'] == 're_add') {?>
	<form method="post"
		  action="index.php?act=seller_deposit&op=re_add&id=<?php echo $_GET['id'];?>"
		  enctype="multipart/form-data" id="my_store_form">
		<?php }else{?>
	<?php if($output['seller_deposit']['deposit_id'] != "") {?>
	<form method="post"
		  action="index.php?act=seller_deposit&op=edit&id=<?php echo $output['seller_deposit']['id'];?>"
		  enctype="multipart/form-data" id="my_store_form">
		<?php }else{?>
	<form method="post"
		action="index.php?act=seller_deposit&op=seller_deposit"
		enctype="multipart/form-data" id="my_store_form">
		<?php }?><?php }?>
		<input type="hidden" name="form_submit" value="ok" />
		<?php if($output['seller_deposit']['deposit_id'] != "") {?>
		<dl>
			<dt><?php echo $lang['store_create_store_name'].$lang['nc_colon'];?></dt>
			<dd>
				<p><?php echo $output['seller_deposit']['seller_name']; ?></p>
			</dd>
		</dl>
		<dl>
			<dt><?php echo $lang['store_deposit_level'].$lang['nc_colon'];?></dt>
			<dd>
				<p>
					<?php if($output['seller_deposit']['deposit_id'] != "") {?>
						<?php echo $output['seller_deposit']['deposit_level'];?>
					<?php }else{?>
					<select name="deposit_level" id="deposit_level">
						<?php foreach($output['deposit_list'] as $k => $v){ ?>
							<option<?php if($output['seller_deposit']['deposit_level'] == $v['level_name']) {?> selected="selected"<?php }?>><?php echo $v['level_name'];?></option>
						<?php }?>
					</select>
					<?php }?>
				</p>
			</dd>
		</dl>
			<dl>
				<dt><?php echo $lang['store_deposit_voucher'];?><?php echo $lang['nc_colon'];?></dt>
				<dd>
					<?php if($output['seller_deposit']['deposit_id'] != "") {?>
						<?php echo $output['seller_deposit']['deposit_voucher'];?>
					<?php }else{?>
					<input name="deposit_voucher" id="deposit_voucher" type="file" class="w200" /><span></span>
					<?php }?>
				</dd>
			</dl>
		<dl>
			<dt><?php echo $lang['store_deposit_amount'].$lang['nc_colon'];?></dt>
			<dd>
				<p><?php echo $output['seller_deposit']['deposit_amount']; ?>元</p>
			</dd>
		</dl>
		<dl>
			<dt><?php echo $lang['store_deposit_dz'].$lang['nc_colon'];?></dt>
			<dd>
				<p><?php echo $output['seller_deposit']['paid']; ?></p>
			</dd>
		</dl>
			<dl>
				<dt><?php echo $lang['store_deposit_date'].$lang['nc_colon'];?></dt>
				<dd>
					<p><?php echo $output['seller_deposit']['apply_date']; ?></p>
				</dd>
			</dl>
		<?php }else{ ?>
		<dl>
			<dt><?php echo $lang['store_deposit_level'];?><?php echo $lang['nc_colon'];?></dt>
			<dd>
				<select name="deposit_level" id="deposit_level">
					<?php foreach($output['deposit_list'] as $k => $v){ ?>
						<option><?php echo $v['level_name'];?></option>
					<?php }?>
				</select>
			</dd>
		</dl>
			<dl>
				<dt><?php echo $lang['store_deposit_voucher'];?><?php echo $lang['nc_colon'];?></dt>
				<dd>
					<input name="deposit_voucher" type="file" class="w200"/><span></span>
				</dd>
			</dl>

		<?php }?>
		<dl>
			<dt><?php echo $lang['store_deposit_isshow'];?><?php echo $lang['nc_colon'];?></dt>
			<dd>
				<input type='checkbox' name='is_show'<?php if($output['seller_deposit']['is_show'] == 1) {?> checked="checked"<?php }?>>
			</dd>
		</dl>
		<div class="bottom">
			<label class="submit-border"><input type="submit" class="submit"
												value="<?php echo $lang['store_goods_class_submit'];?>" /></label>
			<?php if($_GET['op'] != 're_add' && $output['seller_deposit']['deposit_id'] != ""){?>
				<a href="index.php?act=seller_deposit&op=re_add&id=<?php echo $output['seller_deposit']['id'];?>" style="margin-left: 10px;"><?php echo $lang['store_edit_deposit_voucher'];?></a>
			<?php }?>
		</div>
	</form>
	<table border="1" bgcolor="#ccc" width="100%" class="ms_tab">
		<tr>
			<td width="20%" style="font-weight: bold;"><?php echo $lang['store_deposit_name'];?></td>
			<td width="20%" style="font-weight: bold;"><?php echo $lang['store_deposit_amount'];?></td>
			<td style="font-weight: bold;"><?php echo $lang['store_deposit_memo'];?></td>
		</tr>
		<?php if(!empty($output['deposit_list']) && is_array($output['deposit_list'])){ ?>
			<?php foreach($output['deposit_list'] as $k => $v){ ?>
				<tr>
					<td><?php echo $v['level_name'];?></td>
					<td><?php echo $v['amount'];?></td>
					<td><?php echo $v['memo'];?></td>
				</tr>
			<?php }?>
		<?php }else { ?>
			<tr class="no_data">
				<td colspan="10"><?php echo $lang['nc_no_record'];?></td>
			</tr>
		<?php } ?>
	</table>
</div>
