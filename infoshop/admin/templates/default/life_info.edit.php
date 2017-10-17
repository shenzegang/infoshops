<?php defined('CorShop') or exit('Access Invalid!');?>
<div class="page">
	<div class="fixed-bar">
		<div class="item-title">
			<h3>编辑便民信息</h3>
			<ul class="tab-base">
				<li><a href="index.php?act=life_info&op=list"><span>管理</span></a></li>
				<li><a href="index.php?act=life_info&op=add"><span>新增</span></a></li>
				<li><a href="JavaScript:void(0);" class="current"><span>编辑</span></a></li>
			</ul>
		</div>
	</div>
	<div class="fixed-empty"></div>
	<form id="form" method="post">
		<input type="hidden" name="form_submit" value="ok" /> <input
			type="hidden" name="id"
			value="<?php echo $output['article_array']['id'];?>" /> <input
			type="hidden" name="ref_url" value="<?php echo getReferer();?>" />
		<table class="table tb-type2">
			<tbody>
				<tr class="noborder">
					<td colspan="2" class="required"><label class="validation"
						for="title">标题:</label></td>
				</tr>
				<tr class="noborder">
					<td class="vatop rowform"><input type="text"
						value="<?php echo $output['article_array']['title'];?>"
						name="title" id="ask_title" class="txt"></td>
					<td class="vatop tips"></td>
				</tr>
				<tr>
					<td colspan="2" class="required"><label for="if_is_show">显示:</label></td>
				</tr>
				<tr class="noborder">
					<td class="vatop rowform onoff"><label for="is_show1"
						class="cb-enable <?php if($output['article_array']['is_show'] == '1'){ ?>selected<?php } ?>"><span><?php echo '是';?></span></label>
						<label for="is_show0"
						class="cb-disable <?php if($output['article_array']['is_show'] == '0'){ ?>selected<?php } ?>"><span><?php echo '否';?></span></label>
						<input id="is_show1" name="is_show"
						<?php if($output['article_array']['is_show'] == '1'){ ?>
						checked="checked" <?php } ?> value="1" type="radio"> <input
						id="is_show0" name="is_show"
						<?php if($output['article_array']['is_show'] == '0'){ ?>
						checked="checked" <?php } ?> value="0" type="radio"></td>
					<td class="vatop tips"></td>
				</tr>
				<tr>
					<td colspan="2" class="required">排序:</td>
				</tr>
				<tr class="noborder">
					<td class="vatop rowform"><input type="text"
						value="<?php echo $output['article_array']['sort'];?>" name="sort"
						id="sort" class="txt"></td>
					<td class="vatop tips"></td>
				</tr>
				<tr>
					<td colspan="2" class="required"><label class="validation">主营范围:</label></td>
				</tr>
				<tr class="noborder">
					<td colspan="2" class="vatop rowform"><textarea name="content"
							rows="10" class="content" id="content"
							style="width: 500px; height: 80px;"><?php echo $output['article_array']['content']; ?></textarea></td>
				</tr>
				<tr class="noborder">
					<td colspan="2" class="required"><label class="validation">联系电话:</label></td>
				</tr>
				<tr class="noborder">
					<td class="vatop rowform"><input type="text" name="tel" id="tel"
						class="txt" value="<?php echo $output['article_array']['tel']; ?>"></td>
					<td class="vatop tips"></td>
				</tr>
				<tr>
					<td colspan="2" class="required"><label class="validation"
						for="start_date">开始时间:</label></td>
				</tr>
				<tr class="noborder">
					<td class="vatop rowform"><input type="text"
						value="<?php echo empty($output['article_array']['start_date']) ? '' : date('Y-m-d', $output['article_array']['start_date']); ?>"
						name="start_date" id="start_date" class="txt date"></td>
					<td class="vatop tips"></td>
				</tr>
				<tr>
					<td colspan="2" class="required"><label class="validation"
						for="end_date">结束时间:</label></td>
				</tr>
				<tr class="noborder">
					<td class="vatop rowform"><input type="text"
						value="<?php echo empty($output['article_array']['end_date']) ? '' : date('Y-m-d', $output['article_array']['end_date']); ?>"
						name="end_date" id="end_date" class="txt date"></td>
					<td class="vatop tips"></td>
				</tr>
			</tbody>
			<tfoot>
				<tr class="tfoot">
					<td colspan="15"><a href="JavaScript:void(0);" class="btn"
						id="submitBtn"><span>提交</span></a></td>
				</tr>
			</tfoot>
		</table>
	</form>
</div>
<script type="text/javascript"
	src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript"
	src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js"
	charset="utf-8"></script>
<link rel="stylesheet" type="text/css"
	href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css" />
<script>
//按钮先执行验证再提交表单
$(function(){
    $('#start_date').datepicker({dateFormat: 'yy-mm-dd'});
    $('#end_date').datepicker({dateFormat: 'yy-mm-dd'});
    
    $("#submitBtn").click(function(){
        if($("#form").valid()){
            $("#form").submit();
        }
	});
});
$(document).ready(function(){
	$('#form').validate({
        errorPlacement: function(error, element){
			error.appendTo(element.parent().parent().prev().find('td:first'));
        },
        rules : {
            title : {
                required   : true
            },
			content : {
                required   : true
            },
			tel : {
                required   : true
            },
            sort : {
                number   : true
            },
            start_date  : {
                required : true,
                date	 : false
            },
            end_date  : {
            	required : true,
                date	 : false
            }
        },
        messages : {
            title : {
                required   : '标题不能为空'
            },
			content : {
                required   : '主营内容不能为空'
            },
            tel : {
                required   : '联系电话不能为空'
            },
            sort  : {
                number   : '便民信息排序仅能为数字'
            },
            start_date  : {
                required : '开始时间不能为空'
            },
            end_date  : {
            	required   : '结束时间不能为空'
            }
        }
    });
});
</script>