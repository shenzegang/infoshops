<?php defined('CorShop') or exit('Access Invalid!');?>

<div class="page">
	<div class="fixed-bar">
		<div class="item-title">
			<h3>文章分类</h3>
			<ul class="tab-base">
				<li><a href="index.php?act=life_article_class&op=article_class"><span>管理</span></a></li>
				<li><a href="JavaScript:void(0);" class="current"><span>新增</span></a></li>
			</ul>
		</div>
	</div>
	<div class="fixed-empty"></div>
	<form id="article_class_form" method="post">
		<input type="hidden" name="form_submit" value="ok" />
		<table class="table tb-type2">
			<tbody>
				<tr class="noborder">
					<td colspan="2" class="required"><label class="validation"
						for="ac_name">分类名称:</label></td>
				</tr>
				<tr class="noborder">
					<td class="vatop rowform"><input type="text" value=""
						name="ac_name" id="ac_name" class="txt"></td>
					<td class="vatop tips"></td>
				</tr>
				<tr>
					<td colspan="2" class="required"><label for="parent_id">上级分类:</label></td>
				</tr>
				<tr class="noborder">
					<td class="vatop rowform"><select name="ac_parent_id"
						id="ac_parent_id">
							<option value="0">请选择...</option>
              <?php if(!empty($output['parent_list']) && is_array($output['parent_list'])){ ?>
              <?php foreach($output['parent_list'] as $k => $v){ ?>
              <option
								<?php if($output['ac_parent_id'] == $v['ac_id']){ ?>
								selected='selected' <?php } ?> value="<?php echo $v['ac_id'];?>"><?php echo $v['ac_name'];?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
					<td class="vatop tips">如果选择上级分类，那么新增的分类则为被选择上级分类的子分类</td>

				</tr>
				<tr>
					<td colspan="2" class="required"><label for="ac_sort">排序:</label></td>
				</tr>
				<tr class="noborder">
					<td class="vatop rowform"><input type="text" value="255"
						name="ac_sort" id="ac_sort" class="txt"></td>
					<td class="vatop tips">更新排序</td>
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
$(function(){$("#submitBtn").click(function(){
    if($("#article_class_form").valid()){
     $("#article_class_form").submit();
	}
	});
});
//
$(document).ready(function(){
	$('#article_class_form').validate({
        errorPlacement: function(error, element){
			error.appendTo(element.parent().parent().prev().find('td:first'));
        },
        rules : {
            ac_name : {
                required : true,
                remote   : {                
                url :'index.php?act=life_article_class&op=ajax&branch=check_class_name',
                type:'get',
                data:{
                    ac_name : function(){
                        return $('#ac_name').val();
                    },
                    ac_parent_id : function() {
                        return $('#ac_parent_id').val();
                    },
                    ac_id : ''
                  }
                }
            },
            ac_sort : {
                number   : true
            }
        },
        messages : {
            ac_name : {
                required : '分类名称不能为空',
                remote   : '该分类名称已经存在了，请您换一个'
            },
            ac_sort  : {
                number   : '分类排序仅能为数字'
            }
        }
    });
});
</script>