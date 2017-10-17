<?php defined('CorShop') or exit('Access Invalid!');?>
<style>
	.text{ margin-right: 5px; !important;}
	.w300{ width: 250px !important;}
</style>
<div class="eject_con">
	<div class="adds">
		<form method="post" action="index.php?act=member&op=address"
			id="address_form" target="_parent">
			<input type="hidden" name="form_submit" value="ok" /> <input
				type="hidden" name="id"
				value="<?php echo $output['address_info']['address_id'];?>" />
			<dl>
				<dt class="required">
					<em class="pngFix"></em><?php echo $lang['member_address_receiver_name'].$lang['nc_colon'];?></dt>
				<dd>
					<p>
						<input type="text" class="text" name="true_name"
							value="<?php echo $output['address_info']['true_name'];?>" />
					</p>
					<p class="hint"><?php echo $lang['member_address_input_name'];?></p>
				</dd>
			</dl>
			<dl>
				<dt class="required">
					<em class="pngFix"></em><?php echo $lang['member_address_location'].$lang['nc_colon'];?></dt>
				<dd>
					<div id="region">

            <?php if(!empty($output['address_info']['area_id'])){?>
            <span><?php echo $output['address_info']['area_info'];?></span>
						<input type="button" value="<?php echo $lang['nc_edit'];?>"
							class="edit_region" /> <select style="display: none;">
						</select>
            <?php }else{?>
            <select>
						</select>
            <?php }?>
						<input type="hidden"
							   value="<?php echo $output['address_info']['city_id'];?>"
							   name="city_id" id="city_id"> <input type="hidden" name="area_id"
																   id="area_id"
																   value="<?php echo $output['address_info']['area_id'];?>"
																   class="area_ids" /> <input type="hidden" name="area_info"
																							  id="area_info"
																							  value="<?php echo $output['address_info']['area_info'];?>"
																							  class="area_names" />
          </div>
				</dd>
			</dl>
			<dl>
				<dt class="required">
					<em class="pngFix"></em><?php echo $lang['member_address_address'].$lang['nc_colon'];?></dt>
				<dd>
						<input class="text w300" type="text" name="address"
							value="<?php echo $output['address_info']['address'];?>" />
				</dd>
			</dl>
			<dl>
				<dt class="required">
					<em class="pngFix"></em><?php echo $lang['member_address_mobile_num'].$lang['nc_colon'];?></dt>
				<dd>
					<p>
						<input type="hidden" name="form_submit" value="ok" /> <input
							type="hidden" name="tel_phone"
							value="<?php echo $output['address_info']['tel_phone'];?>" />
						<input id='tel_phone' name="tel_phone" type="text" class="text"
							   onblur="numCheck(this.value)" onfocus="numFocus();"
							   value="<?php echo $output['address_info']['tel_phone'];?>" maxlength="11"/>
						<span id="error_num"></span>
					</p>
					<p class="hint"><?php echo $lang['member_address_area_num'];?> - <?php echo $lang['member_address_phone_num'];?> - <?php echo $lang['member_address_sub_phone'];?></p>
				</dd>
			</dl>
			<dl>
				<dt class="required">
					<?php echo $lang['member_address_phone_num'].$lang['nc_colon'];?></dt>
				<dd>
					<input type="hidden" name="form_submit" value="ok" /> <input
						type="hidden" name="mob_phone"
						value="<?php echo $output['address_info']['mob_phone'];?>" />
					<input id='mob_phone'name="mob_phone" type="text" class="text"
						   value="<?php echo $output['address_info']['mob_phone'];?>"
						   onblur="phoneCheck(this.value);" onfocus="phoneFocus();" maxlength="13"/> <span id="error_phone"></span>
					</p></dd>
			</dl>
			<dl class="bottom">
				<dt>&nbsp;</dt>
				<dd>
					<input type="submit" class="submit"
						value="<?php if($output['type'] == 'add'){?><?php echo $lang['member_address_new_address'];?><?php }else{?><?php echo $lang['member_address_edit_address'];?><?php }?>" />
				</dd>
			</dl>
		</form>
	</div>
</div>
<script type="text/javascript"
	src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js"
	charset="utf-8"></script>
<script type="text/javascript">
var SITEURL = "<?php echo SHOP_SITE_URL; ?>";
$(document).ready(function(){
	regionInit("region");
    $('#address_form').validate({
    	submitHandler:function(form){
			if($.trim($("#tel_phone").val()) == "") {
				$("#error_num").html('<cite style="color: #F30">手机号码不能为空</cite>');
				return false;
			}
			if($.trim($("#mob_phone").val()) !="" && $.trim($("#error_phone").html()) !="") {
				return false;
			}

			if(!numCheck($("#tel_phone").val())){
				return;
			}
    		if ($('select[class="valid"]').eq(1).val()>0) $('#city_id').val($('select[class="valid"]').eq(1).val());
    		ajaxpost('address_form', '', '', 'onerror');
    	},

        rules : {
            true_name : {
                required : true
            },
            area_id : {
                required : true,
                min : 1,
                checkarea : true
            },
            address : {
                required : true
            },
        },
        messages : {
            true_name : {
                required : '<?php echo $lang['member_address_input_receiver'];?>'
            },
            area_id : {
                required : '<?php echo $lang['member_address_choose_location'];?>',
                min  :     '<?php echo $lang['member_address_choose_location'];?>',
                checkarea :'<?php echo $lang['member_address_choose_location'];?>'
            },
            address : {
                required : '<?php echo $lang['member_address_input_address'];?>'
            },
        },
        groups : {
            phone:'mobile_num tel_phone'
        }
    });
});
function numCheck(num) {
	var pattern = /^(13[0-9]|14[0-9]|15[0-9]|18[0-9])\d{8}$/
	if (!pattern.test(num)) {
		$("#error_num").html('<cite style="color: #F30">请输入正确的手机号</cite>');
		return false;
	}else{
		$("#error_num").html("");
		return true;
	}
}
function numFocus(){
	$("#error_num").val("");
}
function phoneCheck(phone) {
	var pattern = /^((0\d{2,3})-)(\d{7,8})(-(\d{3,}))?$/
	if (!pattern.test(phone)) {
		$("#error_phone").html('<cite style="color: #F30">请输入正确的座机号</cite>')
		return false;
	}else{
		$("#error_phone").html("");
		return true;
	}
}

function phoneFocus(){
    $("#error_phone").val("");
}

</script>