<?php defined('CorShop') or exit('Access Invalid!');?>

<div class="page">
	<div class="fixed-bar">
		<div class="item-title">
			<h3>留言反馈</h3>
			<ul class="tab-base">
				<li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['nc_manage'];?></span></a></li>
			</ul>
		</div>
	</div>
	<div class="fixed-empty"></div>
	<form method="get" name="formSearch">
		<input type="hidden" value="feedback" name="act"> <input type="hidden"
			value="list" name="op">
		<table class="tb-type1 noborder search">
			<tbody>
				<tr>
					<th><label for="search_title">搜索</label></th>
					<td><input type="text" value="<?php echo $output['keywords'];?>"
						name="keywords" id="keywords" class="txt"></td>
					<td><a href="javascript:document.formSearch.submit();"
						class="btn-search " title="<?php echo $lang['nc_query'];?>">&nbsp;</a>
            <?php if(!empty($output['keywords'])){?>
            <a href="index.php?act=feedback&op=list" class="btns "
						title="<?php echo $lang['nc_cancel_search'];?>"><span><?php echo $lang['nc_cancel_search'];?></span></a>
            <?php }?></td>
				</tr>
			</tbody>
		</table>
	</form>
	<form method="post" id="form_article">
		<input type="hidden" name="form_submit" value="ok" />
		<table class="table tb-type2">
			<thead>
				<tr class="thead">
					<th class="w48 align-center">ID</th>
					<th class="align-center">姓名</th>
					<th class="align-center">性别</th>
					<th class="align-center">邮箱</th>
					<th class="align-center">电话</th>
					<th class="align-center">内容</th>
					<th class="align-center">时间</th>
					<th class="align-center">操作</th>
				</tr>
			</thead>
			<tbody>
        <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
        <?php foreach($output['list'] as $k => $v){ ?>
        <tr class="hover">
					<td class="align-center"><?php echo $v['id']; ?></td>
					<td class="align-center"><?php echo $v['name']; ?></td>
					<td class="align-center"><?php echo empty($v['sex']) ? '男' : '女'; ?></td>
					<td class="align-center"><?php echo $v['email']; ?></td>
					<td class="align-center"><?php echo $v['tel']; ?></td>
					<td class="align-center"><?php echo $v['content']; ?></td>
					<td class="align-center"><?php echo date('Y-m-d H:i:s',$v['add_time']); ?></td>
					<td class="align-center"><a
						href="index.php?act=feedback&op=del&id=<?php echo $v['id']; ?>">删除</a></td>
				</tr>
        <?php } ?>
        <?php }else { ?>
        <tr class="no_data">
					<td colspan="10"><?php echo $lang['nc_no_record'];?></td>
				</tr>
        <?php } ?>
      </tbody>
			<tfoot>
        <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
        <tr class="tfoot">
					<td colspan="16">
						<div class="pagination"> <?php echo $output['page'];?> </div>
					</td>
				</tr>
        <?php } ?>
      </tfoot>
		</table>
	</form>
</div>
