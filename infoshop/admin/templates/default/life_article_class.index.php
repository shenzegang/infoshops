<?php defined('CorShop') or exit('Access Invalid!');?>

<div class="page">
	<div class="fixed-bar">
		<div class="item-title">
			<h3>文章分类</h3>
			<ul class="tab-base">
				<li><a href="JavaScript:void(0);" class="current"><span>管理</span></a></li>
				<li><a href="index.php?act=life_article_class&op=article_class_add"><span>新增</span></a></li>
			</ul>
		</div>
	</div>
	<div class="fixed-empty"></div>
	<table class="table tb-type2" id="prompt">
		<tbody>
			<tr class="space odd">
				<th class="nobg" colspan="12"><div class="title">
						<h5>操作提示</h5>
						<span class="arrow"></span>
					</div></th>
			</tr>
			<tr>
				<td><ul>
						<li>管理员新增文章时，可选择文章分类。文章分类将在前台文章列表页显示</li>
						<li>默认的文章分类不可以删除</li>
					</ul></td>
			</tr>
		</tbody>
	</table>
	<form method='post'>
		<input type="hidden" name="form_submit" value="ok" />
		<table class="table tb-type2">
			<thead>
				<tr class="thead">
					<th class="w48"></th>
					<th class="w48">排序</th>
					<th>分类名称</th>
					<th class="w96 align-center">操作</th>
				</tr>
			</thead>
			<tbody id="treet1">
        <?php if(!empty($output['class_list']) && is_array($output['class_list'])){ ?>
        <?php foreach($output['class_list'] as $k => $v){ ?>
        <tr class="hover edit">
					<td><?php if($v['ac_code'] == ''){ ?>
            <input type="checkbox" name='check_ac_id[]'
						value="<?php echo $v['ac_id'];?>" class="checkitem">
            <?php }else{ ?>
            <input name="" type="checkbox" disabled="disabled" value="" />
            <?php }?>
            <?php if($v['have_child'] == '1'){ ?>
            <img
						src="<?php echo ADMIN_TEMPLATES_URL;?>/images/tv-expandable.gif"
						fieldid="<?php echo $v['ac_id'];?>" status="open" nc_type="flex">
            <?php }else{ ?>
            <img fieldid="<?php echo $v['ac_id'];?>" status="close"
						nc_type="flex"
						src="<?php echo ADMIN_TEMPLATES_URL;?>/images/tv-item.gif">
            <?php } ?></td>
					<td class="sort"><span title="可编辑" ajax_branch='article_class_sort'
						datatype="number" fieldid="<?php echo $v['ac_id'];?>"
						fieldname="ac_sort" nc_type="inline_edit" class="editable"><?php echo $v['ac_sort'];?></span></td>
					<td class="name"><span title="可编辑" required="1"
						fieldid="<?php echo $v['ac_id'];?>"
						ajax_branch='article_class_name' fieldname="ac_name"
						nc_type="inline_edit" class="editable "><?php echo $v['ac_name'];?></span>
						<a class='btn-add-nofloat marginleft'
						href="index.php?act=life_article_class&op=article_class_add&ac_parent_id=<?php echo $v['ac_id'];?>"><span>新增下级</span></a></td>
					<td class="align-center"><a
						href="index.php?act=life_article_class&op=article_class_edit&ac_id=<?php echo $v['ac_id'];?>">编辑</a>
            <?php if($v['ac_code'] == ''){?>
            | <a
						href="javascript:if(confirm('删除该分类将会同时删除该分类的所有下级分类，您确定要删除吗'))window.location = 'index.php?act=life_article_class&op=article_class_del&ac_id=<?php echo $v['ac_id'];?>';">删除</a>
            <?php }?></td>
				</tr>
        <?php } ?>
        <?php }else { ?>
        <tr class="no_data">
					<td colspan="10">没有符合条件的记录</td>
				</tr>
        <?php } ?>
      </tbody>
			<tfoot>
        <?php if(!empty($output['class_list']) && is_array($output['class_list'])){ ?>
        <tr>
					<td><label for="checkall1"> <input type="checkbox" class="checkall"
							id="checkall_2">
					</label></td>
					<td colspan="16"><label for="checkall_2">全选</label> &nbsp;&nbsp;<a
						href="JavaScript:void(0);" class="btn"
						onclick="if(confirm('删除该分类将会同时删除该分类的所有下级分类，您确定要删除吗')){$('form:first').submit();}"><span>删除</span></a></td>
				</tr>
        <?php } ?>
      </tfoot>
		</table>
	</form>
</div>
<script type="text/javascript"
	src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.edit.js" charset="utf-8"></script>
<script type="text/javascript"
	src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.life_article_class.js"
	charset="utf-8"></script>
