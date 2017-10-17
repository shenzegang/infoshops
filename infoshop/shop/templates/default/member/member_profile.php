<?php defined('CorShop') or exit('Access Invalid!');?>
<link rel="stylesheet" type="text/css"
	href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css" />
<style type="text/css">
dl dd span {
	display: inline-block;
}
#sendsms {
	border: 1px solid #ccc;
	display: block;
	width: 100px;
	height: 25px;
	text-align: center;
	float: left;
	margin-left: 10px;
	line-height: 25px;
	color: #999;
	cursor:pointer;
}
</style>

<div class="wrap">
	<div class="tabmenu">
    <?php include template('layout/submenu');?>
  </div>
	<div class="ncu-form-style">
		<form method="post" id="profile_form"
			action="index.php?act=home&op=member">
			<input type="hidden" name="form_submit" value="ok" /> <input
				type="hidden" name="old_member_avatar"
				value="<?php echo $output['member_info']['member_avatar']; ?>" />
			<dl>
				<dt class="required"><em class="pngFix"></em><?php echo $lang['home_member_username'].$lang['nc_colon'];?></dt>
				<dd>
					<span class="w340"><?php echo $output['member_info']['member_name']; ?></span><span>&nbsp;&nbsp;<?php echo $lang['home_member_privacy_set'];?></span>
				</dd>
			</dl>
			<dl>
				<dt></em><?php echo $lang['home_member_email'].$lang['nc_colon'];?></dt>
				<dd>
					<span class="w340"><?php echo $output['member_info']['member_email']; ?></span><span>
						<select name="privacy[email]">
							<option value="0"
								<?php if($output['member_info']['member_privacy']['email'] == 0){?>
								selected="selected" <?php }?>><?php echo $lang['home_member_public'];?></option>
							<option value="1"
								<?php if($output['member_info']['member_privacy']['email'] == 1){?>
								selected="selected" <?php }?>><?php echo $lang['home_member_friend'];?></option>
							<option value="2"
								<?php if($output['member_info']['member_privacy']['email'] == 2){?>
								selected="selected" <?php }?>><?php echo $lang['home_member_privary']?></option>
					</select>
					</span>
				</dd>
			</dl>

			<dl>
				<dt class="required"><em class="pngFix"></em><?php echo $lang['home_member_sex'].$lang['nc_colon'];?></dt>
				<dd>
					<span class="w340"> <label> <input type="radio" name="member_sex"
							value="3"
							<?php if($output['member_info']['member_sex']==3 or ($output['member_info']['member_sex']!=2 and $output['member_info']['member_sex']!=1)) { ?>
							checked="checked" <?php } ?> />
            <?php echo $lang['home_member_secret'];?></label>
						&nbsp;&nbsp; <label> <input type="radio" name="member_sex"
							value="2" <?php if($output['member_info']['member_sex']==2) { ?>
							checked="checked" <?php } ?> />
            <?php echo $lang['home_member_female'];?></label>
						&nbsp;&nbsp; <label> <input type="radio" name="member_sex"
							value="1" <?php if($output['member_info']['member_sex']==1) { ?>
							checked="checked" <?php } ?> />
            <?php echo $lang['home_member_male'];?></label>
					</span><span> <select name="privacy[sex]">
							<option value="0"
								<?php if($output['member_info']['member_privacy']['sex'] == 0){?>
								selected="selected" <?php }?>><?php echo $lang['home_member_public'];?></option>
							<option value="1"
								<?php if($output['member_info']['member_privacy']['sex'] == 1){?>
								selected="selected" <?php }?>><?php echo $lang['home_member_friend'];?></option>
							<option value="2"
								<?php if($output['member_info']['member_privacy']['sex'] == 2){?>
								selected="selected" <?php }?>><?php echo $lang['home_member_privary']?></option>
					</select>
					</span>
				</dd>
			</dl>
			<dl>
				<dt><?php echo $lang['home_member_birthday'].$lang['nc_colon'];?></dt>
				<dd>
					<span class="w340"> <input type="text" class="text" name="birthday"
						maxlength="10" id="birthday"
						value="<?php echo $output['member_info']['member_birthday']; ?>" />
					</span><span> <select name="privacy[birthday]">
							<option value="0"
								<?php if($output['member_info']['member_privacy']['birthday'] == 0){?>
								selected="selected" <?php }?>><?php echo $lang['home_member_public'];?></option>
							<option value="1"
								<?php if($output['member_info']['member_privacy']['birthday'] == 1){?>
								selected="selected" <?php }?>><?php echo $lang['home_member_friend'];?></option>
							<option value="2"
								<?php if($output['member_info']['member_privacy']['birthday'] == 2){?>
								selected="selected" <?php }?>><?php echo $lang['home_member_privary']?></option>
					</select>
					</span>
				</dd>
			</dl>
			<dl>
				<dt class="required"><?php echo $lang['home_member_areainfo'].$lang['nc_colon'];?></dt>
				<dd>
					<span id="region" class="w340"> <input type="hidden"
						value="<?php echo $output['member_info']['member_provinceid'];?>"
						name="province_id" id="province_id"> <input type="hidden"
						value="<?php echo $output['member_info']['member_cityid'];?>"
						name="city_id" id="city_id"> <input type="hidden"
						value="<?php echo $output['member_info']['member_areaid'];?>"
						name="area_id" id="area_id" class="area_ids" /> <input
						type="hidden"
						value="<?php echo $output['member_info']['member_areainfo'];?>"
						name="area_info" id="area_info" class="area_names" />
          <?php if(!empty($output['member_info']['member_areaid'])){?>
          <span><?php echo $output['member_info']['member_areainfo'];?></span>
						<input type="button" value="<?php echo $lang['nc_edit'];?>"
						class="edit_region" /> <select style="display: none;">
					</select>
          <?php }else{?>
          <select>
					</select>
          <?php }?>
          </span><span> <select name="privacy[area]">
							<option value="0"
								<?php if($output['member_info']['member_privacy']['area'] == 0){?>
								selected="selected" <?php }?>><?php echo $lang['home_member_public'];?></option>
							<option value="1"
								<?php if($output['member_info']['member_privacy']['area'] == 1){?>
								selected="selected" <?php }?>><?php echo $lang['home_member_friend'];?></option>
							<option value="2"
								<?php if($output['member_info']['member_privacy']['area'] == 2){?>
								selected="selected" <?php }?>><?php echo $lang['home_member_privary']?></option>
					</select>
					</span>
				</dd>
			</dl>
			<dl>
				<dt>QQ<?php echo $lang['nc_colon'];?></dt>
				<dd>
					<span class="w340"> <input type="text" class="text" maxlength="30"
						name="member_qq"
						value="<?php echo $output['member_info']['member_qq']; ?>" />
					</span><span> <select name="privacy[qq]">
							<option value="0"
								<?php if($output['member_info']['member_privacy']['qq'] == 0){?>
								selected="selected" <?php }?>><?php echo $lang['home_member_public'];?></option>
							<option value="1"
								<?php if($output['member_info']['member_privacy']['qq'] == 1){?>
								selected="selected" <?php }?>><?php echo $lang['home_member_friend'];?></option>
							<option value="2"
								<?php if($output['member_info']['member_privacy']['qq'] == 2){?>
								selected="selected" <?php }?>><?php echo $lang['home_member_privary']?></option>
					</select>
					</span>
				</dd>
			</dl>
			<dl>
				<dt><?php echo $lang['home_member_wangwang'].$lang['nc_colon'];?></dt>
				<dd>
					<span class="w340"> <input name="member_ww" type="text"
						class="text" maxlength="50" id="member_ww"
						value="<?php echo $output['member_info']['member_ww'];?>" />
					</span><span> <select name="privacy[ww]">
							<option value="0"
								<?php if($output['member_info']['member_privacy']['ww'] == 0){?>
								selected="selected" <?php }?>><?php echo $lang['home_member_public'];?></option>
							<option value="1"
								<?php if($output['member_info']['member_privacy']['ww'] == 1){?>
								selected="selected" <?php }?>><?php echo $lang['home_member_friend'];?></option>
							<option value="2"
								<?php if($output['member_info']['member_privacy']['ww'] == 2){?>
								selected="selected" <?php }?>><?php echo $lang['home_member_privary']?></option>
					</select>
					</span>
				</dd>
			</dl>
			<dl>
				<dt class="required"><em class="pngFix"></em><?php echo $lang['home_member_tel'].$lang['nc_colon'];?></dt>
				<dd>
					<span class="w340"> <input name="member_tel" type="text"
						class="text" maxlength="50" id="member_tel"
						value="<?php echo $output['member_info']['member_tel'];?>" onkeyup="this.value=this.value.replace(/\D/g,'');return(kup())" onafterpaste="this.value=this.value.replace(/\D/g,'')"/>
						<label for="member_tel" class="error" style="display: none;"></label>
					<span id="error_tel" ></span></span><span> <select name="privacy[tel]">
							<option value="0"
								<?php if($output['member_info']['member_privacy']['tel'] == 0){?>
								selected="selected" <?php }?>><?php echo $lang['home_member_public'];?></option>
							<option value="1"
								<?php if($output['member_info']['member_privacy']['tel'] == 1){?>
								selected="selected" <?php }?>><?php echo $lang['home_member_friend'];?></option>
							<option value="2"
								<?php if($output['member_info']['member_privacy']['tel'] == 2){?>
								selected="selected" <?php }?>><?php echo $lang['home_member_privary']?></option>
					</select>
					</span>
				</dd>
			</dl>
			<dl id="code_dl" style="display: none;">
				<dt class="required"><em class="pngFix"></em><?php echo $lang['login_register_sendsms'].$lang['nc_colon'];?></dt>
				<dd style="min-height: 54px;">
					<input type="text" id="code" name="code" class="text tip" title="<?php echo $lang['login_register_input_valid_sms'];?>" style="width: 100px; float: left;"/>
					<input type="button" id="sendsms" value="免费获取验证码" onclick="settime(this)" />
					<p id="error_code" ></p>
				</dd>
			</dl>
			<dl class="bottom">
				<dt>&nbsp;</dt>
				<dd>
					<input type="submit" class="submit"
						value="<?php echo $lang['home_member_save_modify'];?>" />
				</dd>
			</dl>
		</form>
	</div>
</div>
<script type="text/javascript"
	src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js"
	charset="utf-8"></script>
<script type="text/javascript">
//注册表单验证
$(function(){
	regionInit("region");
	$('#birthday').datepicker({dateFormat: 'yy-mm-dd'});
    $('#profile_form').validate({
    	submitHandler:function(form){
    		if ($('select[class="valid"]').eq(0).val()>0) $('#province_id').val($('select[class="valid"]').eq(0).val());
			if ($('select[class="valid"]').eq(1).val()>0) $('#city_id').val($('select[class="valid"]').eq(1).val());
			ajaxpost('profile_form', '', '', 'onerror')
		},
        rules : {
            member_qq : {
				digits  : true,
                minlength : 5,
                maxlength : 12
            },
			member_tel : {
				maxlength : 11,
				minlength : 11,
				required : true,
				remote   : {
					url :'index.php?act=login&op=check_tel&m_tel=<?php echo $output['member_info']['member_tel'];?>',
					type:'get',
					data:{
						member_tel : function(){
							return $('#member_tel').val();
						}
					}
				}

			}
        },
        messages : {
            member_qq  : {
				digits    : '<?php echo $lang['home_member_input_qq'];?>',
				minlength: '<?php echo $lang['home_member_input_qq'];?>',
                maxlength : '<?php echo $lang['home_member_input_qq'];?>'
            },
			member_tel : {
				required : '<?php echo $lang['home_member_member_tel'];?>',
				minlength: '<?php echo $lang['home_member_tel_range'];?>',
				maxlength: '<?php echo$lang['home_member_tel_range'];?>',
				remote	 : '<?php echo $lang['login_register_tel_exists'];?>'
			}
    	}
	});
});


<?php if($output['member_info']['member_tel'] == ''){?>
function kup(){
	$("#error_tel").html('');
	if((document.getElementById("member_tel").value).length == 11){
		document.getElementById('code_dl').style.display = "block";
	}
	else{
		document.getElementById('code_dl').style.display = "none";
	}

}
<?php }else{?>
function kup(){
	$("#error_tel").html('');
	if((document.getElementById("member_tel").value).length == 11 && document.getElementById("member_tel").value != <?php echo $output['member_info']['member_tel'];?>){
		document.getElementById('code_dl').style.display = "block";
	}
	else{
		document.getElementById('code_dl').style.display = "none";
	}

}
<?php }?>
var countdown=<?php echo C('sms_send');?>;
function settime(val) {
	var Member_tphone_val = document.getElementById("member_tel").value;
		if($("label[for='member_tel']").css("display") == 'none'){
			if (countdown == 0) {
				val.removeAttribute("disabled");
				val.value = "免费获取验证码";
				countdown = <?php echo C('sms_send');?>;
			}
			else if (countdown == <?php echo C('sms_send');?> || countdown != 0) {
				if(countdown == <?php echo C('sms_send');?>)
				{
					var co = <?php echo rand(1000,9999);?>;
					$.ajax({
						type: "GET",
						url:'index.php?act=login&op=sendSMS',
						data: {mobile_phone:Member_tphone_val, code:co},
						success: function(data){
							alert('验证码是'+co);
						},
					});
				}
				val.setAttribute("disabled", true);
				val.value = "重新发送(" + countdown + ")";
				countdown--;
				setTimeout(function () {
					settime(val)
				}, 1000)
			}
			$("#error_code").html('<label for="company_registered_capital" class="error">短信验证码有效时间三分钟</label>');
		}
}
</script>
<script charset="utf-8" type="text/javascript"
	src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js"></script>