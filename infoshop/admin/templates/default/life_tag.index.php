<?php defined('CorShop') or exit('Access Invalid!');?>

<div class="page">
	<div class="fixed-bar">
		<div class="item-title">
			<h3>TAG管理</h3>
			<ul class="tab-base">
				<li><a href="JavaScript:void(0);" class="current"><span>管理</span></a></li>
				<li><a href="index.php?act=life_tag&op=add"><span>新增</span></a></li>
			</ul>
		</div>
	</div>
	<div class="fixed-empty"></div>
	<form method="get" name="formSearch">
		<input type="hidden" value="life_tag" name="act"> <input type="hidden"
			value="list" name="op">
		<table class="tb-type1 noborder search">
			<tbody>
				<tr>
					<th><label for="search_title">标题</label></th>
					<td><input type="text"
						value="<?php echo $output['search_title'];?>" name="search_title"
						id="search_title" class="txt"></td>
					<td><a href="javascript:document.formSearch.submit();"
						class="btn-search " title="查询">&nbsp;</a>
            <?php if($output['search_title'] != '' or $output['search_ac_id'] != ''){?>
            <a href="index.php?act=life_tag&op=list" class="btns "
						title="撤销检索"><span>撤销检索</span></a>
            <?php }?></td>
				</tr>
			</tbody>
		</table>
	</form>
	<table class="table tb-type2" id="prompt">
		<tbody>
			<tr class="space odd">
				<th colspan="12"><div class="title">
						<h5>操作提示</h5>
						<span class="arrow"></span>
					</div></th>
			</tr>
			<tr>
				<td><ul>
						<li>区别于系统TAG，可在TAG列表页点击查看</li>
					</ul></td>
			</tr>
		</tbody>
	</table>
	<form method="post" id="form_article">
		<input type="hidden" name="form_submit" value="ok" />
		<table class="table tb-type2">
			<thead>
				<tr class="thead">
					<th class="w24"></th>
					<th class="w48">ID</th>
					<th>TAG</th>
					<th class="w60 align-center">热门</th>
					<th class="w60 align-center">统计</th>
					<th class="w60 align-center">排序</th>
					<th class="w60 align-center">操作</th>
				</tr>
			</thead>
			<tbody>
        <?php if(!empty($output['article_list']) && is_array($output['article_list'])){ ?>
        <?php foreach($output['article_list'] as $k => $v){ ?>
        <tr class="hover">
					<td><input type="checkbox" name='del_id[]'
						value="<?php echo $v['id']; ?>" class="checkitem"></td>
					<td><?php echo $v['id']; ?></td>
					<td><?php echo $v['title']; ?></td>
					<td class="align-center yes-onoff"><a href="JavaScript:void(0);"
						class="<?php echo empty($v['is_hot']) ? ' disabled' : ' enabled'; ?>"
						ajax_branch="is_hot" nc_type="inline_edit" fieldname="is_hot"
						fieldid="<?php echo $v['id']?>"
						fieldvalue="<?php echo $v['is_hot']?>"
						title="<?php echo $lang['nc_editable'];?>"><img
							src="<?php echo ADMIN_TEMPLATES_URL;?>/images/transparent.gif"></a>
					</td>
					<td class="align-center"><?php echo $v['num']; ?></td>
					<td class="align-center"><?php echo $v['sort']; ?></td>
					<td class="align-center"><a
						href="index.php?act=life_tag&op=edit&id=<?php echo $v['id']; ?>">编辑</a></td>
				</tr>
        <?php } ?>
        <?php }else { ?>
        <tr class="no_data">
					<td colspan="10">没有符合条件的记录</td>
				</tr>
        <?php } ?>
      </tbody>
			<tfoot>
        <?php if(!empty($output['article_list']) && is_array($output['article_list'])){ ?>
        <tr class="tfoot">
					<td><input type="checkbox" class="checkall" id="checkallBottom"></td>
					<td colspan="16"><label for="checkallBottom">全选</label>
						&nbsp;&nbsp;<a href="JavaScript:void(0);" class="btn"
						onclick="if(confirm('您确定要删除吗?')){$('#form_article').submit();}"><span>删除</span></a>
						<div class="pagination"> <?php echo $output['page'];?> </div></td>
				</tr>
        <?php } ?>
      </tfoot>
		</table>
	</form>
</div>
<script type="text/javascript"
	src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.edit.js" charset="utf-8"></script>
<script type="text/javascript"
	src="<?php echo RESOURCE_SITE_URL;?>/js/dialog/dialog.js"
	id="dialog_js" charset="utf-8"></script>
<script type="text/javascript"
	src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>