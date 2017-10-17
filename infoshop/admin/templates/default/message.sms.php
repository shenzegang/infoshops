<?php defined('CorShop') or exit('Access Invalid!');?>

<div class="page">
	<div class="fixed-bar">
		<div class="item-title">
			<h3><?php echo $lang['nc_message_set'];?></h3>
      <?php echo $output['top_link'];?>
    </div>
	</div>
	<div class="fixed-empty"></div>
	<form method="post" id="form_sms" name="settingForm">
		<input type="hidden" name="form_submit" value="ok" />
		<table class="table tb-type2">
			<tbody>
				<tr class="noborder">
					<td colspan="2" class="required"><label for="sms_type"><?php echo $lang['sms_type_open'];?>:</label></td>
				</tr>
				<tr class="noborder">
					<td class="vatop rowform onoff"><label for="sms_enabled_1"
						class="cb-enable <?php if($output['list_setting']['sms_enabled'] == '1'){ ?>selected<?php } ?>"
						title="<?php echo $lang['open'];?>"><span><?php echo $lang['open'];?></span></label>
						<label for="sms_enabled_0"
						class="cb-disable <?php if($output['list_setting']['sms_enabled'] == '0'){ ?>selected<?php } ?>"
						title="<?php echo $lang['close'];?>"><span><?php echo $lang['close'];?></span></label>
						<input type="radio"
						<?php if($output['list_setting']['sms_enabled'] == '1'){ ?>
						checked="checked" <?php } ?> value="1" name="sms_enabled"
						id="sms_enabled_1" /> <input type="radio"
						<?php if($output['list_setting']['sms_enabled'] == '0'){ ?>
						checked="checked" <?php } ?> value="0" name="sms_enabled"
						id="sms_enabled_0" /> <input type="hidden" name="sms_type"
						value="1" /></td>
					<td class="vatop tips">&nbsp;</td>
				</tr>

				<tr>
					<td colspan="2" class="required"><?php echo $lang['sms_type_user'];?>:</td>
				</tr>
				<tr class="noborder">
					<td class="vatop rowform"><input type="text"
						value="<?php echo $output['list_setting']['sms_type_user'];?>"
						name="sms_type_user" id="sms_type_user" class="txt"></td>
					<td class="vatop tips"><label class="field_notice"><?php echo $lang['sms_type_user_type'];?></label></td>
				</tr>
				<tr>
					<td colspan="2" class="required"><?php echo $lang['sms_type_pass'];?>:</td>
				</tr>
				<tr class="noborder">
					<td class="vatop rowform"><input type="password"
						value="<?php echo $output['list_setting']['sms_type_pass'];?>"
						name="sms_type_pass" id="sms_type_pass" class="txt"></td>
					<td class="vatop tips"><label class="field_notice"><?php echo $lang['sms_type_pass_tips'];?></label></td>
				</tr>
				<tr>
					<td colspan="2" class="required"><?php echo $lang['sms_type_key'];?>:</td>
				</tr>
				<tr class="noborder">
					<td class="vatop rowform"><input type="text"
						value="<?php echo $output['list_setting']['sms_type_key'];?>"
						name="sms_type_key" id="sms_type_key" class="txt"></td>
					<td class="vatop tips"><label class="field_notice"><?php echo $lang['sms_type_key_tips'];?></label></td>
				</tr>

				<tr>
					<td colspan="2" class="required"><?php echo $lang['sms_smallbuynum'];?>:</td>
				</tr>
				<tr class="noborder">
					<td class="vatop rowform"><input type="text"
						value="<?php echo $output['list_setting']['sms_smallbuynum'];?>"
						name="sms_smallbuynum" id="sms_smallbuynum" class="txt"></td>
					<td class="vatop tips"><label class="field_notice"><?php echo $lang['sms_smallbuynum_tips'];?></label></td>
				</tr>
				<tr>
					<td colspan="2" class="required"><?php echo $lang['sms_sellprice'];?>:</td>
				</tr>
				<tr class="noborder">
					<td class="vatop rowform"><input type="text"
						value="<?php echo $output['list_setting']['sms_sellprice'];?>"
						name="sms_sellprice" id="sms_sellprice" class="txt"></td>
					<td class="vatop tips"></td>
				</tr>

				<tr>
					<td colspan="2" class="required"><?php echo $lang['test_sms_tel'];?>:</td>
				</tr>
				<tr class="noborder">
					<td class="vatop rowform"><input type="text" value=""
						name="sms_test" id="sms_test" class="txt"></td>
					<td class="vatop tips"><input type="button"
						value="<?php echo $lang['test'];?>" name="send_test_sms"
						class="btn" id="send_test_sms"></td>
				</tr>
			</tbody>
			<tfoot>
				<tr class="tfoot">
					<td colspan="2"><a href="JavaScript:void(0);" class="btn"
						onclick="document.settingForm.submit()"><span><?php echo $lang['nc_submit'];?></span></a></td>
				</tr>
			</tfoot>
		</table>
	</form>
</div>
<script>
$(document).ready(function(){
	/*$("#form_sms").validate({
		rules: {
			sms_host: {required:"#sms_type_1:checked"},
			sms_port: {required:"#sms_type_1:checked"},
			sms_addr: {required:"#sms_type_1:checked"},
			sms_id: {required:"#sms_type_1:checked"},
			sms_pass: {required:"#sms_type_1:checked"}
		},
		messages: {
			sms_host: {required:""},
			sms_port: {required:""},
			sms_addr: {required:""},
			sms_id: {required:""},
			sms_pass: {required:""}
		}
	});*/
	$('#send_test_sms').click(function(){
		$.ajax({
			type:'POST',
			url:'index.php',
			data:'act=message&op=sms_testing&sms_user='+$('#sms_type_user').val()+'&sms_pass='+$('#sms_type_pass').val()+'&sms_key='+$('#sms_type_key').val()+'&sms_type='+1+'&sms_test='+$('#sms_test').val(),
			success:function(data){
				alert(data);
			}
		});
	});
});
</script>