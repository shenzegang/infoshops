<?php defined('CorShop') or exit('Access Invalid!');?>

<div class="page">
	<div class="fixed-bar">
		<div class="item-title">
			<h3><?php echo $lang['nc_message_set'];?></h3>
      <?php echo $output['top_link'];?>
    </div>
	</div>
	<div class="fixed-empty"></div>
	<form method="post" id="form_email" name="settingForm"
		onSubmit="return alert(123);">
		<input type="hidden" name="form_submit" value="ok" />
		<table class="table tb-type2">
			<tbody>
				<tr class="noborder">
					<td colspan="2" class="required"><label for="content">短息内容:</label></td>
				</tr>
				<tr class="noborder">
					<td class="vatop rowform"><textarea name="content" rows="6"
							class="tarea" id="content"></textarea></td>
					<td class="vatop tips"></td>
				</tr>
				<tr>
					<td colspan="2" class="required">目标:</td>
				</tr>
				<tr class="noborder">
					<td class="vatop rowform"><input type="radio" name="type" value="0"
						checked> 所有会员 <input type="radio" name="type" value="1"> 所有商铺</td>
					<td class="vatop tips"><span class="vatop rowform"></span></td>
				</tr>
			</tbody>
			<tfoot>
				<tr class="tfoot">
					<td colspan="2"><a href="JavaScript:void(0);" class="btn"
						onclick="check()"><span><?php echo $lang['nc_submit'];?></span></a></td>
				</tr>
			</tfoot>
		</table>
	</form>
</div>
<script>
function check(){
    if($('#content').val().length <= 0){
        alert('请填写群发内容！');
        return false;
    }
    document.settingForm.submit();
}
</script>