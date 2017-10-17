<?php defined('CorShop') or exit('Access Invalid!');?>

<div class="page">
	<div class="fixed-bar">
		<div class="item-title">
			<h3><?php echo $lang['nc_deposit_shop'];?></h3>
			<ul class="tab-base">
				<li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['manage'];?></span></a></li>
				<li><a href="index.php?act=store_deposit&op=store_deposit_add"><span><?php echo $lang['nc_new'];?></span></a></li>
			</ul>
		</div>
	</div>
	<div class="fixed-empty"></div>
	<form method="post" name="formSearch">
		<table class="tb-type1 noborder search">
			<tbody>
			<tr>
				<th><label for="like_dl_name"><?php echo $lang['store_deposit_name'];?></label></th>
				<td><input type="text"
						   value="<?php echo $output['like_dl_name'];?>" name="like_dl_name"
						   id="like_dl_name" class="txt"></td>
				<td><a href="javascript:document.formSearch.submit();"
					   class="btn-search " title="<?php echo $lang['nc_query']; ?>">&nbsp;</a>
					<?php if($output['like_dl_name'] != ''){?>
						<a class="btns "
						   href="index.php?act=store_deposit&op=store_deposit"
						   title="<?php echo $lang['cancel_search'];?>"><span><?php echo $lang['cancel_search'];?></span></a>
					<?php }?></td>
			</tr>
			</tbody>
		</table>
	</form>
	<form id="form_deposit" method='post' name="">
		<input type="hidden" name="form_submit" value="ok" />
		<table class="table tb-type2">
			<thead>
			<tr class="thead">
				<th class="w24">&nbsp;</th>
				<th style="width:20%;"><?php echo $lang['store_deposit_name']; ?></th>
				<th style="width:20%;"><?php echo $lang['store_deposit_amount'];?></th>
				<th style="width:40%;"><?php echo $lang['store_deposit_memo'];?></th>
				<th><?php echo $lang['nc_handle'];?></th>
			</tr>
			</thead>
			<tbody>
			<?php if(!empty($output['deposit_list']) && is_array($output['deposit_list'])){ ?>
				<?php foreach($output['deposit_list'] as $k => $v){ ?>
					<tr class="hover">
						<td><input type="checkbox" name='check_id[]'
									   value="<?php echo $v['id'];?>" class="checkitem"></td>
						<td><?php echo $v['level_name'];?></td>
						<td><?php echo $v['amount'];?></td>
						<td style="text-overflow:ellipsis; white-space:nowrap; overflow:hidden;"><?php echo $v['memo'];?></td>
						<td class="w270"><a
								href="index.php?act=store_deposit&op=store_deposit_edit&id=<?php echo $v['id'];?>"><?php echo $lang['nc_edit'];?></a> |
								<a
									href="javascript:if(confirm('<?php echo $lang['problem_del'];?>'))window.location = 'index.php?act=store_deposit&op=store_deposit_del&id=<?php echo $v['id'];?>';"><?php echo $lang['nc_del'];?></a>
							</td>
					</tr>
				<?php } ?>
			<?php }else { ?>
				<tr class="no_data">
					<td colspan="10"><?php echo $lang['nc_no_record'];?></td>
				</tr>
			<?php } ?>
			</tbody>
			<tfoot>
			<tr class="tfoot">
				<td><input type="checkbox" class="checkall" id="checkallBottom"></td>
				<td colspan="15"><label for="checkallBottom"><?php echo $lang['nc_select_all']; ?></label>
					&nbsp;&nbsp;<a href="JavaScript:void(0);" class="btn"
								   onclick="if(confirm('<?php echo $lang['problem_del'];?>')){$('#form_deposit').submit();}"><span><?php echo $lang['nc_del'];?></span></a></td>
			</tr>
			</tfoot>
		</table>
	</form>
</div>
