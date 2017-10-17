<?php defined('CorShop') or exit('Access Invalid!');?>
<div id="header"></div>
<div class="user-sms">
  <?php if (!empty($output['message_array'])) { ?>
  <?php foreach($output['message_array'] as $k => $v){ ?>
  <dl>
		<dt><?php echo parsesmiles($v['message_body']); ?></dt>
		<dd>
			<span>发件人：<?php if ($output['drop_type'] == 'msg_seller'){echo $v['from_member_name'];}else { echo $v['from_member_name']; }?></span><span><?php echo @date("Y-m-d H:i:s",$v['message_update_time']); ?></span>
		</dd>
	</dl>
  <?php } ?>
  <?php } ?>
</div>
<div class="send-sms">
	<form method="post" id="send_form"
		action="index.php?act=home&op=savemsg">
		<input type="hidden" name="form_submit" value="ok" />
		<dl>
			<dt>
				<span>收件人：</span>
				<p>
					<input type="text" class="text" name="to_member_name"
						value="<?php echo $output['member_name']; ?>"
						<?php if (!empty($output['member_name'])){echo "readonly";}?> />
				</p>
			</dt>
			<dt>
				<span>类型：</span>
				<p>
					<input type="radio" value="2" name="msg_type" checked="checked" /> <?php echo $lang['home_message_open'];?>&nbsp;&nbsp;<input
						type="radio" name="msg_type" value="0" /> <?php echo $lang['home_message_close'];?></p>
			</dt>
			<dt>
				<span>内容：</span>
				<p>
					<textarea name="msg_content" rows="3" class="textarea"></textarea>
				</p>
			</dt>
			<dd>
				<input type="submit" class="submit"
					value="<?php echo $lang['home_message_ensure_send'];?>" />
			</dd>
		</dl>
	</form>
</div>
<script type="text/javascript">
var header_title = '发送信息';
</script>
<script type="text/javascript" src="js/template.js"></script>
<script type="text/javascript" src="js/tmpl/common-top.js"></script>