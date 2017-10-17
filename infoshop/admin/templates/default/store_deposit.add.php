<?php defined('CorShop') or exit('Access Invalid!');?>

<div class="page">
	<div class="fixed-bar">
		<div class="item-title">
			<h3><?php echo $lang['nc_deposit_shop'];?></h3>
			<ul class="tab-base">
				<li><a href="index.php?act=store_deposit&op=store_deposit"><span><?php echo $lang['manage'];?></span></a></li>
				<li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['nc_new'];?></span></a></li>
			</ul>
		</div>
	</div>
	<div class="fixed-empty"></div>
	<form id="deposit_form" method="post">
		<input type="hidden" name="form_submit" value="ok" />
		<table class="table tb-type2">
			<tbody>
				<tr class="noborder">
					<td colspan="2" class="required"><label class="validation"
						for="level_name"><?php echo $lang['store_deposit_name'];?>:</label></td>
				</tr>
				<tr class="noborder">
					<td class="vatop rowform"><input type="text" value="" id="level_name"
						name="level_name" class="txt"></td>
					<td class="vatop tips"></td>
				</tr>
				<tr>
					<td colspan="2" class="required"><label class="validation" for="amount"><?php echo $lang['store_deposit_amount'];?>:</label></td>
				</tr>
				<tr class="noborder">
					<td class="vatop rowform"><input type="text" value="0"
						id="amount" name="amount" class="txt"></td>
				</tr>
				<tr>
					<td colspan="2" class="required"><label class="validation"> <?php echo $lang['store_deposit_memo'];?>:</label></td>
				</tr>
				<tr class="noborder">
					<td class="vatop rowform"><textarea rows="6" class="tarea"
																	id="memo" name="memo"></textarea></td>
					<td class="vatop tips"></td>
				</tr>
			</tbody>
			<tfoot>
				<tr class="tfoot">
					<td colspan="15"><a href="JavaScript:void(0);" class="btn"
						id="submitBtn"><span><?php echo $lang['nc_submit'];?></span></a></td>
				</tr>
			</tfoot>
		</table>
	</form>
</div>
<script>
//按钮先执行验证再提交表单
$(function(){$("#submitBtn").click(function(){
    if($("#deposit_form").valid()){
     $("#deposit_form").submit();
	}
	});
});
//
$(document).ready(function(){
	$('#deposit_form').validate({
        errorPlacement: function(error, element){
			error.appendTo(element.parent().parent().prev().find('td:first'));
        },

        rules : {
			level_name : {
                required : true,
                remote   : {
                url :'index.php?act=store_deposit&op=ajax&branch=check_deposit_name',
                type:'get',
                data:{
					level_name : function(){
                        	return $('#level_name').val();
                        },
                        id  : ''
                    }
                }
            },
			amount : {
                required  : true
            },
			memo : {
				required  : true
            }
        },
        messages : {
			level_name : {
                required : '<?php echo $lang['store_deposit_name_no_null'];?>',
                remote   : '<?php echo $lang['now_store_deposit_name_is_there'];?>'
            },
			amount : {
                required  : "<?php echo $lang['deposit_amount_no_null'];?>"
            },
			memo : {
				required : '<?php echo $lang['deposit_memo_no_null'];?>'
            }
        }
    });
});
</script>
