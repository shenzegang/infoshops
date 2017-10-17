<?php defined('CorShop') or exit('Access Invalid!');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
  <a href="javascript:void(0)" class="ncsc-btn ncsc-btn-green"
		nc_type="dialog"
		dialog_title="<?php echo $lang['store_apply_business_category'];?>"
		dialog_id="my_category_apply" dialog_width="480"
		uri="index.php?act=store_apply_business&op=category_add"><?php echo $lang['store_apply_business_category'];?></a>
</div>

<table class="ncsc-table-style">
	<thead>
		<tr>
			<th class="w150">分类1</th>
			<th>分类2</th>
			<th>分类3</th>
			<th>状态</th>
		</tr>
	</thead>
	<tbody>

	<?php if(!empty($output['store_bind_class_list']) && is_array($output['store_bind_class_list'])){ ?>
		<?php foreach($output['store_bind_class_list'] as $key => $value){ ?>
			<tr class="bd-line">
				<td class="w25pre"><?php echo $value['class_1_name'];?></td>
				<td class="w25pre"><?php echo $value['class_2_name'];?></td>
				<td class="w25pre"><?php echo $value['class_3_name'];?></td>
				<td class="w25pre">
					<?php
						if($value['status']==0){
							echo "<cite style='color: #5BB75B'>已通过<cite>";
						}else if($value['status']==1){
							echo "<cite style='color: #1c23f3'>待审核<cite>";
						}else{
							echo "<cite style='color: #f31a0d'>拒绝<cite>";
						}
					?></td>
			</tr>
		<?php } ?>
	<?php }else { ?>
		<tr>
			<td colspan="20" class="norecord"><div class="warning-option">
					<i class="icon-warning-sign"></i><span><?php echo $lang['no_record'];?></span>
				</div></td>
		</tr>
	<?php } ?>

  </tbody>

</table>
