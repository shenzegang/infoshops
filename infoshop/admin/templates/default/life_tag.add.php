<?php defined('CorShop') or exit('Access Invalid!');?>
<div class="page">
	<div class="fixed-bar">
		<div class="item-title">
			<h3>新增TAG</h3>
			<ul class="tab-base">
				<li><a href="index.php?act=life_tag&op=list"><span>管理</span></a></li>
				<li><a href="JavaScript:void(0);" class="current"><span>新增</span></a></li>
			</ul>
		</div>
	</div>
	<div class="fixed-empty"></div>
	<form id="form" method="post" name="articleForm">
		<input type="hidden" name="form_submit" value="ok" />
		<table class="table tb-type2 nobdb">
			<tbody>
				<tr class="noborder">
					<td colspan="2" class="required"><label class="validation">TAG:</label></td>
				</tr>
				<tr class="noborder">
					<td class="vatop rowform"><input type="text" value="" name="title"
						id="ask_title" class="txt"></td>
					<td class="vatop tips"></td>
				</tr>
				<tr>
					<td colspan="2" class="required">排序:
				
				</tr>
				<tr class="noborder">
					<td class="vatop rowform"><input type="text" value="255"
						name="sort" id="sort" class="txt"></td>
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
<script>
//按钮先执行验证再提交表单
$(function(){
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
            sort : {
                number   : true
            }
        },
        messages : {
            title : {
                required   : '标题不能为空'
            },
            sort  : {
                number   : 'TAG排序仅能为数字'
            }
        }
    });
});
</script>