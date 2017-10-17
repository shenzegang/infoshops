<?php defined('CorShop') or exit('Access Invalid!');?>

<div class="eject_con">
		<form method="post" action="index.php?act=member_account&op=mobile_sms"
			id="mobile_sms_form" target="_parent">
			<input type="hidden" name="form_submit" value="ok" />
            <input type="hidden" name="member_tel" id="member_tel" value="<?php echo $output['member_tel']; ?>" />
            <h2>手机短信安全验证</h2>
			<dl>
				<dt class="required">
					<em class="pngFix"></em>已验证的手机号码<?php echo $lang['nc_colon'];?></dt>
				<dd>
					<p>
						<h3><?php echo $output['member_tel']; ?></h3>
					</p>
                    
				</dd>
			</dl>
            <dl>
				<dt class="required">
					<em class="pngFix"></em>手机校验码<?php echo $lang['nc_colon'];?></dt>
				<dd>
					<p>
						<input type="text" class="text" id="mobile" name="mobile"
							value="" />
                        <input type="button" id="sendsms"  value="免费获取验证码"  />
					</p>
                    
				</dd>
			</dl>
			
			
			<dl>
				<dt class="required">
					<em class="pngFix"></em>验证码<?php echo $lang['nc_colon'];?></dt>
				<dd>
					<p>
						<input type="text" id="captcha" name="captcha"
							class="text w50 fl tip" maxlength="4" size="10" />  
                            <input name="nchash" type="hidden" value="<?php echo getNchash();?>" /> 
                        <img
							src="index.php?act=seccode&op=makecode&nchash=<?php echo getNchash();?>"
							title="" name="codeimage" border="0" id="codeimage"
							class="fl ml5" /> <a href="javascript:void(0)" class="ml5"
							onclick="javascript:document.getElementById('codeimage').src='index.php?act=seccode&op=makecode&nchash=<?php echo getNchash();?>&t=' + Math.random();">换一张</a>
					</p>
					
				</dd>
			</dl>
			
			<dl class="bottom">
				<dt>&nbsp;</dt>
				<dd style="width:65%">
					<input type="submit"  id="submit" class="submit"
						value="确定" />
				</dd>
			</dl>
		</form>
	
</div>
<script type="text/javascript"
	src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js"
	charset="utf-8"></script>
<script type="text/javascript">
var SITEURL = "<?php echo SHOP_SITE_URL; ?>";
$(document).ready(function(){
	$("#submit").click(function(){
		if($("#mobile_sms_form").valid()){
        	ajaxpost('mobile_sms_form', '', '', 'onerror');
        } else{
        	document.getElementById('codeimage').src='<?php echo SHOP_SITE_URL?>/index.php?act=seccode&op=makecode&nchash=<?php echo getNchash();?>&t=' + Math.random();
        }
	});
    $('#mobile_sms_form').validate({

    	submitHandler:function(form){
    		ajaxpost('mobile_sms_form', '', '', 'onerror');
    	},
		
		errorPlacement: function(error, element){
           error.appendTo(element.parent('p'));
        },
        rules : {
			mobile : {
				 required : true
			},
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
            }
        },
        messages : {
			mobile : {
				required : '<i></i>校验码不能为空',
			},
			captcha : {
                required : '<i></i>验证码不能为空',
				minlength: '',
				remote	 : '<i></i>验证码输入错误'
            }
        }
    });
	
	$("#sendsms").click(function(){
		$(this).attr('disabled', 'disabled');
		$(this).after("<label class='success'>短信已发送</label>");
		var Member_tphone_val = $("#member_tel").val();
		var co = <?php echo rand(1000,9999);?>;
		$.ajax({
			type: "GET",
			url:'index.php?act=login&op=sendSMS',
			data: {mobile_phone:Member_tphone_val, code:co},
			success: function(data){
				
			},
		});
		var me = this;	
		var count = 60;
		for(var i=60;i>0; i--){
			setTimeout(function (){$(me).val("重新发送(" + count-- + ")")},1000 * (60-i));
		}
				
		setTimeout(function(){
				$(me).removeAttr('disabled');
				$(me).val("免费获取验证码");
				$(me).next('label').remove();
		},60000);
	});
	
});

</script>