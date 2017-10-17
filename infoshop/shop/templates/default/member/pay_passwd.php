<div class="eject_con">
  
  <form
		action="index.php?act=member&op=loadPaywd"
		method="post" id="pay_passwd_form">
		<input type="hidden" name="form_submit" value="ok" />
		<p class="tips">使用站内预存款进行支付时，需输入您的支付密码进行安全验证。</p>
        <dl>
			<dt>请输入支付密码<?php echo $lang['nc_colon'];?></dt>
			<dd>
				<span class="num"><input type="password" class="text" autocomplete="off" id="pay_passwd" name="pay_passwd" value="" /></span>
			</dd>
		</dl>
         <!-- 错误提示栏 -->
		<div id="warning" class="alert alert-error"></div>
		<dl class="bottom">
			<dt>&nbsp;</dt>
			<dd style="text-align:center; width:50%">
				<input type="submit" nctype="pay_passwd_submit"  class="submit" id="confirm_yes" value="确认提交支付" />
			</dd>
		</dl>
	</form>
    
</div>
<script type="text/javascript">
$(document).ready(function(){
	
	$('input[nctype="pay_passwd_submit" ]').click(function(){
		if($('#pay_passwd_form').valid()){
			//ajaxpost('pay_passwd_form', '', '', 'onerror');
		}
		
	});
	
	$('#pay_passwd_form').validate({
		onfocusout : false,
		onkeyup : false,
		errorLabelContainer: $('#warning'),
		submitHandler:function(form){
            ajaxpost('pay_passwd_form', '', '', 'onerror') 
        },
		invalidHandler: function(form, validator) {
           var errors = validator.numberOfInvalids();
           if(errors)
           {
			   $('#warning').show();
           }
           else
           {
               $('#warning').hide();
           }
        },
		rules : {
			pay_passwd : {
                required   : true,
                minlength  : 6,
				remote : {
					url : 'index.php?act=member&op=isValidPaypasswd',
					type : 'get',
					data : {
						pay_passwd : function(){
							return document.getElementById('pay_passwd').value;
						}
					}
				}
            }
		},
		messages : {
			 pay_passwd  : {
                required   : '<i></i>支付密码不能为空，请输入有效的支付密码',
				minlength : '<i></i>支付密码长度至少为6位，请重新输入',
				remote : '<i></i>支付密码输入错误，请重新输入'
            }
		}
	})
})
</script>