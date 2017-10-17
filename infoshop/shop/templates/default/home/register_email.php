<?php defined('CorShop') or exit('Access Invalid!');?>
<style type="text/css">
.public-top-layout, .head-app, .head-search-bar, .head-user-menu,
	.public-nav-layout, .nch-breadcrumb-layout, #faq {
	display: none !important;
}

.public-head-layout {
	margin: 10px auto -10px auto;
}

.wrapper {
	width: 1000px;
}

#footer {
	border-top: none !important;
	padding-top: 30px;
}
</style>
<div class="nc-login-layout" style="width: 1000px;">
	<div id="login_ol">
		<ol>
			<li class="active"><i>1</i><span><?php echo $lang['login_usersave_regist_username'];?></span></li>
			<li<?php if($_GET['email'] !="" || $_GET['cur'] ==2) {?> class="active"<?php } ?>><i>2</i><span><?php echo $lang['login_usersave_regist_info'];?></span></li>
			<!--li><i>√</i><span><?php echo $lang['login_usersave_regist_succ'];?></span></li-->
		</ol>
	</div>
	<div class="nc-login-content" style="border: none; padding: 50px 0; width: 358px; margin: 0 auto;">
		<form id="register_form" method="post" action="<?php if($_GET['email'] != ""){?><?php echo SHOP_SITE_URL;?>/index.php?act=login&op=usersave&cur=1&email=<?php echo $_GET['email'];?><?php } ?>">
			<?php if($_GET['email'] == ""){?>
			<dl>
				<dt><?php echo $lang['login_register_email'];?></dt>
				<dd style="min-height: 54px;">
					<input type="text" id="email" name="email" class="text tip"
						   onpaste="return false" onblur="emailCheck(this.value)" title="<?php echo $lang['login_register_input_valid_mobile'];?>" />
					<span id="error_mail" ></span>
				</dd>
			</dl>
			<dl>
				<dt><?php echo $lang['login_register_sendsms'];?></dt>
				<dd style="min-height: 54px;">
					<input type="text" id="code" name="code" class="text tip" title="<?php echo $lang['login_register_input_valid_sms'];?>" style="width: 100px; float: left;" onblur="codeCheck(this.value)"/>
					<input type="button" id="sendsms" value="免费获取验证码" onclick="settime(this)" value="<?php echo $_POST['code'];?>"/>
					<span id="error_code" ></span>
				</dd>
			</dl>
			<dl>
				<dt></dt>
				<dd>
					<input type="submit" id="Submit_next"
						   value="<?php echo $lang['login_register_regist_next'];?>"
						   class="submit"
						   title="<?php echo $lang['login_register_regist_next'];?>" />
				</dd>
			</dl>
				<dl>
					<dt></dt>
					<dd>
						<a href="index.php?act=login&op=usersave&cur=mob" style="color: #c53801;"><?php echo $lang['login_register_regist_mobile'];?></a>
					</dd>
				</dl>
			</div>
			<?php }else{?>
			<?php Security::getToken();?>
			<dl>
				<dt><?php echo $lang['login_register_username'];?></dt>
				<dd style="min-height: 54px;">
					<input type="text" id="user_name" name="user_name" class="text tip"
						   onpaste="return false" onblur="nameCheck(this.value)" onfocus="nameFocus();"
						   title="<?php echo $lang['login_register_username_to_login'];?>" />
					<span id="error_name" ></span>
				</dd>
			</dl>
			<dl>
				<dt><?php echo $lang['login_register_pwd'];?></dt>
				<dd style="min-height: 54px;">
					<input type="password" id="password" name="password"
						   class="text tip"
						   title="<?php echo $lang['login_register_password_to_login'];?>" />
					<label></label>
				</dd>
			</dl>
			<dl>
				<dt><?php echo $lang['login_register_ensure_password'];?></dt>
				<dd style="min-height: 54px;">
					<input type="password" id="password_confirm"
						   name="password_confirm" class="text tip"
						   title="<?php echo $lang['login_register_input_password_again'];?>" />
					<label></label>
				</dd>
			</dl>
			<?php if(C('captcha_status_register') == '1') { ?>
				<dl>
					<dt><?php echo $lang['login_register_code'];?></dt>
					<dd style="min-height: 54px;">
						<input type="text" id="captcha" name="captcha"
							   class="text w50 fl tip" maxlength="4" size="10"
							   title="<?php echo $lang['login_register_input_code'];?>" /> <img
							src="index.php?act=seccode&op=makecode&nchash=<?php echo getNchash();?>"
							title="" name="codeimage" border="0" id="codeimage"
							class="fl ml5" /> <a href="javascript:void(0)" class="ml5"
												 onclick="javascript:document.getElementById('codeimage').src='index.php?act=seccode&op=makecode&nchash=<?php echo getNchash();?>&t=' + Math.random();"><?php echo $lang['login_register_click_to_change_code'];?></a>
						<label></label>
					</dd>
				</dl>
			<dl>
				<dt>&nbsp;</dt>
				<dd>
					<input type="submit" id="Submit"
						   value="<?php echo $lang['login_register_regist_now'];?>"
						   class="submit"
						   title="<?php echo $lang['login_register_regist_now'];?>" /> <input
						name="agree" type="checkbox" class="vm ml10" id="clause"
						value="1" checked="checked" /> <span for="clause" class="ml5"><?php echo $lang['login_register_agreed'];?><a
							href="<?php echo urlShop('document', 'index',array('code'=>'agreement'));?>"
							target="_blank" class="agreement"
							title="<?php echo $lang['login_register_agreed'];?>"><?php echo $lang['login_register_agreement'];?></a></span>
					<label></label>
				</dd>
			</dl>
			<input type="hidden" value="<?php echo $_GET['ref_url']?>"
				   name="ref_url"> <input name="nchash" type="hidden"
										  value="<?php echo getNchash();?>" />
			<?php }?>
			<?php } ?>
		</form>
	</div>
</div>
<?php if($_GET['email'] == "") {?>
<script>
	$(function(){
		$('#Submit_next').click(function(){
			if(!emailCheck($("#email").val())){
				return false;
			}
			if(!codeCheck($("#code").val())){
				return false;
			}
			else if($("label[for='email']").css("display") == 'block'){
				return false;
			}
			else{
				var Member_email_val = document.getElementById("email").value;
				var Member_code_val = document.getElementById("code").value;
				$.ajax({
					type: "GET",
					url:'index.php?act=login&op=check_code',
					data: {mobile_phone:Member_email_val ,code:Member_code_val},
					success: function(data){
						if(data == 1){
							location.href = "<?php echo SHOP_SITE_URL;?>/index.php?act=login&op=usersave&cur=email&email="+Member_email_val;
						}
						else{
							$("#error_code").html('<label for="company_registered_capital" class="error"><?php echo $lang['login_usersave_wrong_code'];?></label>');
						}
					},
				});
			}
		});
	});
	var countdown=<?php echo C('sms_send');?>;
	function settime(val) {
		var Member_email_val = document.getElementById("email").value;

		if(document.getElementById("email").value == ""){
			$("#error_email").html('<label for="company_registered_capital" class="error">手机号码不能为空</label>');
		}
		else if($("#error_mail").html()!= "" || $("label[for='email']").css("display") == 'block'){
		}
		else {
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
						data: {mobile_phone:Member_email_val, code:co},
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
	$("#register_form").validate({
		errorPlacement: function(error, element){
			var error_td = element.parent('dd');
			error_td.find('label').hide();
			error_td.append(error);
		},
		submitHandler:function(form){
			ajaxpost('register_form', '', '', 'onerror')
		},
		rules : {
			email : {
				remote   : {
					url :'index.php?act=login&op=check_email',
					type:'get',
					data:{
						email : function(){
							return $('#email').val();
						}
					}
				}
			}
		},
		messages : {
			email : {
				remote	 : '<?php echo $lang['login_usersave_your_email_exists'];?>'
			}
		}
	});

	function emailCheck(email) {
		var pattern = /^(\w)+(\.\w+)*@(\w)+((\.\w+)+)$/;
		if (!pattern.test(email)) {
			$("#error_mail").html('<label for="company_registered_capital" class="error">请输入正确的邮箱</label>');
			return false;
		} else {
			$("#error_mail").html("");
			return true;
		}
	}
	function codeCheck(code) {
		if(document.getElementById("code").value == ''){
			$("#error_code").html('<label for="company_registered_capital" class="error"><?php echo $lang['login_register_code_null'];?></label>');
			return false;
		}
		else{
			$("#error_code").html('');
			return true;
		}
	}
</script>
<?php }else{?>
<script>
$(function(){
    $('#Submit').click(function(){
		if(!nameCheck($("#user_name").val())){
			return false;
		}
        if($("#register_form").valid()){
        	ajaxpost('register_form', '', '', 'onerror');
        } else{
        	document.getElementById('codeimage').src='<?php echo SHOP_SITE_URL?>/index.php?act=seccode&op=makecode&nchash=<?php echo getNchash();?>&t=' + Math.random();
			return false;
        }
		//$("ol li").eq(2).addClass("active");
    });

    $("#register_form").validate({
        errorPlacement: function(error, element){
            var error_td = element.parent('dd');
            error_td.find('label').hide();
            error_td.append(error);
        },
        submitHandler:function(form){
            ajaxpost('register_form', '', '', 'onerror')
        },
        rules : {
            user_name : {
                required : true,
				minlength: 3,
				maxlength: 20,

                remote   : {
                    url :'index.php?act=login&op=check_member&column=ok',
                    type:'get',
                    data:{
                        user_name : function(){
                            return $('#user_name').val();
                        }
                    }
                }
            },
            password : {
                required : true,
                minlength: 6,
				maxlength: 20
            },
            password_confirm : {
                required : true,
                equalTo  : '#password'
            },
			<?php if(C('captcha_status_register') == '1') { ?>
            captcha : {
                required : true,
                minlength: 4,
                remote   : {
                    url : 'index.php?act=seccode&op=check&nchash=<?php echo getNchash();?>',
                    type: 'get',
                    data:{
                        captcha : function(){
                            return $('#captcha').val();
                        }
                    }
                }
            },
			<?php } ?>
            agree : {
                required : true
            }
        },
        messages : {
            user_name : {
                required : '<?php echo $lang['login_register_input_username'];?>',
				remote	 : '<?php echo $lang['login_register_username_exists'];?>',
				minlength: '<?php echo $lang['login_register_username_range'];?>',
				maxlength: '<?php echo $lang['login_register_username_range'];?>'
            },
            password  : {
                required : '<?php echo $lang['login_register_input_password'];?>',
                minlength: '<?php echo $lang['login_register_password_range'];?>',
				maxlength: '<?php echo $lang['login_register_password_range'];?>'
            },
            password_confirm : {
                required : '<?php echo $lang['login_register_input_password_again'];?>',
                equalTo  : '<?php echo $lang['login_register_password_not_same'];?>'
            },
			<?php if(C('captcha_status_register') == '1') { ?>
            captcha : {
                required : '<?php echo $lang['login_register_input_text_in_image'];?>',
                minlength: '<?php echo $lang['login_register_code_wrong'];?>',
				remote	 : '<?php echo $lang['login_register_code_wrong'];?>'
            },
			<?php } ?>
            agree : {
                required : '<?php echo $lang['login_register_must_agree'];?>'
            }
        }
    });
});
	function check(str){
		var temp=""
		for(var i=0;i<str.length;i++)
			if(str.charCodeAt(i)>0&&str.charCodeAt(i)<255)
				temp+=str.charAt(i)
		return temp
	}
	function nameCheck(name) {
		var pattern = /^[a-zA-Z0-9\u4e00-\u9fa5\_]+$/;;
		if (!pattern.test(name)) {
			if(document.getElementById("user_name").value == '' && document.getElementById("member_tel").value == '') {
				$("#error_name").html('<label for="company_registered_capital" class="error"><?php echo $lang['login_register_username_lettersonly'];?></label>');
				return false;
			}
			else if (!pattern.test(name) && document.getElementById("member_tel").value == '') {
				$("#error_name").html('<label for="company_registered_capital" class="error">用户名不能包含敏感字符</label>');
				return false;
			}else{

			}
		} else {
			$("#error_name").html("");
			return true;
		}
	}
	function nameFocus(){
		$("#error_name").val("");
	}
</script>
<?php }?>
