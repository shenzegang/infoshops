<div class="eject_con">
	<!-- onsubmit="ajaxpost('confirm_order_form','','','onerror')" -->
	<div id="warning"></div>
<?php if ($output['order_info']) {?>
  <form
		action="index.php?act=member_order&op=change_state&state_type=order_receive&order_id=<?php echo $output['order_info']['order_id']; ?>"
		method="post" id="confirm_order_form">
		<input type="hidden" name="form_submit" value="ok" />
		<h2><?php echo $lang['member_change_ensure_receive1'];?>?</h2>
		<dl>
			<dt><?php echo $lang['member_change_order_no'].$lang['nc_colon'];?></dt>
			<dd>
				<span class="num"><?php echo trim($_GET['order_sn']); ?></span>
			</dd>
		</dl>
        <dl>
			<dt><?php echo $lang['member_paypasswd_input'].$lang['nc_colon'];?></dt>
			<dd>
				<span class="num"><input type="password" class="text" id="paypasswd" name="paypasswd" value="" /></span>
                <span style="color:#F00; margin-left:8px;"></span>
			</dd>
		</dl>
		<dl>
			<p class="hint pl10 pr10"><?php echo $lang['member_change_receive_tip'];?></p>
		</dl>
		<dl class="bottom">
			<dt>&nbsp;</dt>
			<dd>
				<input type="submit" class="submit" id="confirm_yes"
					value="<?php echo $lang['nc_ok'];?>" />
			</dd>
		</dl>
	</form>
<?php } else { ?>
<p style="line-height: 80px; text-align: center">该订单并不存在，请检查参数是否正确!</p>
<?php } ?>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$('#confirm_order_form').validate({
		submitHandler:function(form){
    		ajaxpost('confirm_order_form', '', '', 'onerror');
    	},
		errorPlacement: function(error, element) {
			error.appendTo(element.parent("span").next("span"));
   		},
		rules : {
			paypasswd : {
                required   : true,
                minlength  : 6,
				remote : {
					url : 'index.php?act=member&op=isValidPaypasswd',
					type : 'get',
					data : {
						paypasswd : function(){
							return $("#paypasswd").val();
						}
					}
				}
            }
		},
		messages : {
			 paypasswd  : {
                required   : '<?php echo $lang['member_paypasswd_input'];?>',
                minlength  : '<?php echo $lang['home_member_paypasswd_range'];?>',
				remote : '<?php echo $lang['home_member_input_paypasswd_wrong']; ?>'
            }
		}
	})
})
</script>