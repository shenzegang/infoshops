<?php defined('CorShop') or exit('Access Invalid!');?>

<div class="eject_con">
	
		<div id="warning" class="alert alert-error"></div>
		<form method="post" action="index.php?act=member&op=paypasswd"
			id="paypasswd_form" target="_parent">
			<input type="hidden" name="form_submit" value="ok" />
            <input type="hidden" name="status" value="1" />
            <?php if($output['type'] == 'edit'){ ?>
            <dl>
				<dt class="required">
					<em class="pngFix"></em><?php echo $lang['member_paypasswd_orig_input'].$lang['nc_colon'];?></dt>
				<dd>
					<p>
						<input type="password" class="text" id="orig_paypasswd" name="orig_paypasswd"
							value="" />
					</p>
					<p class="hint"></p>
				</dd>
			</dl>
            <?php } ?>
			<dl>
				<dt class="required">
					<em class="pngFix"></em><?php echo $lang['member_paypasswd_input'].$lang['nc_colon'];?></dt>
				<dd>
					<p>
						<input type="password" class="text" id="paypasswd" name="paypasswd"
							value="" />
					</p>
					<p class="hint"></p>
				</dd>
			</dl>
			
			
			<dl>
				<dt class="required">
					<em class="pngFix"></em><?php echo $lang['member_paypasswd_input_again'].$lang['nc_colon'];?></dt>
				<dd>
					<p>
						<input type="password" class="text" id="paypasswd_again" name="paypasswd_again"
							value="" />
					</p>
					<p class="hint"></p>
				</dd>
			</dl>
			
			<dl class="bottom">
				<dt>&nbsp;</dt>
				<dd>
					<input type="submit" class="submit"
						value="<?php if($output['type'] == 'add'){?><?php echo $lang['member_paypasswd'];?><?php }else{?><?php echo $lang['member_paypasswd_edit'];?><?php }?>" />
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
	regionInit("region");
	jQuery.validator.addMethod("regex", function(value, element){
		return regex(value);
	}, '');
    $('#paypasswd_form').validate({
		onkeyup: false,
    	submitHandler:function(form){
    		ajaxpost('paypasswd_form', '', '', 'onerror');
    	},
		
        errorPlacement: function(error, element) {
			error.appendTo(element.parent("p").next('p'));
			error.parent("p").show();
   		},
		success:function(element){
			element.parent("p").hide();
		},
        rules : {
			<?php if($output['type'] == 'edit'){ ?>
			orig_paypasswd  : {
				required   : true,
				minlength  : 6,
				remote : {
					url : 'index.php?act=member&op=isValidPaypasswd',
					type : 'get',
					data : {
						paypasswd : function(){
							return $("#orig_paypasswd").val();
						}
					}
				}
			},
			<?php } ?>
            paypasswd : {
                required   : true,
                minlength  : 6,
				maxlength  : 20,
				regex : true,
				remote : {
					url : 'index.php?act=member&op=isEqualPasswd',
					type : 'get',
					data : {
						paypasswd : function(){
							return $("#paypasswd").val();
						}
					}
				}
            },
            paypasswd_again : {
                required   : true,
                equalTo    : '#paypasswd'
            }
        },
        messages : {
			<?php if($output['type'] == 'edit'){ ?>
			orig_paypasswd  : {
                required   : '<em></em><?php echo $lang['home_member_orig_paypassword_null'];?>',
				minlength  : '<em></em><?php echo $lang['home_member_paypasswd_range'];?>',
				remote : '<em></em><?php echo $lang['home_member_input_orig_paypasswd_wrong']; ?>'
            },
			<?php } ?>
            paypasswd  : {
                required   : '<em></em><?php echo $lang['home_member_new_password_null'];?>',
                minlength  : '<em></em><?php echo $lang['home_member_paypasswd_range'];?>',
				maxlength  : '<em></em>支付密码最大长度为20位',
				regex : '<em></em>支付密码不能有空格',
				remote : '<em></em><?php echo $lang['home_member_input_paypasswd_equal_password']; ?>'
            },
            paypasswd_again : {
                required   : '<em></em><?php echo $lang['home_member_ensure_password_null'];?>',
                equalTo    : '<em></em><?php echo $lang['home_member_diffent_paypasswd'];?>'
            }
        }
        
    });
	function regex(pm){
		if(/(^\s+)|(\s+$)/.test(pm) || pm.indexOf(" ") > -1){
			return false;
		}
		return true;
	}
});

</script>