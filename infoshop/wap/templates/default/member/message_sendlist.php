<?php defined('CorShop') or exit('Access Invalid!');?>
<div class="header-wrap">
	<a href="javascript:history.back();" class="header-back"><span>返回</span></a>
	<h2>站内消息</h2>
	<a href="javascript:void(0)" id="btn-opera" class="i-main-add"><span></span></a>
</div>
<div class="user-tab">
	<ul>
      <?php
    
if (is_array($output['member_menu']) and ! empty($output['member_menu'])) {
        foreach ($output['member_menu'] as $key => $val) {
            $classname = 'hover';
            if ($val['menu_key'] == $output['menu_key']) {
                $classname = 'active';
            }
            if ($val['menu_key'] == 'message') {
                echo '<li class="' . $classname . '"><a href="' . $val['menu_url'] . '">我收到(<span style="color: red;">' . $output['newcommon'] . '</span>)</a></li>';
            } elseif ($val['menu_key'] == 'system') {
                echo '<li class="' . $classname . '"><a href="' . $val['menu_url'] . '">' . $val['menu_name'] . '(<span style="color: red;">' . $output['newsystem'] . '</span>)</a></li>';
            } elseif ($val['menu_key'] == 'close') {
                echo '<li class="' . $classname . '"><a href="' . $val['menu_url'] . '">' . $val['menu_name'] . '(<span style="color: red;">' . $output['newpersonal'] . '</span>)</a></li>';
            } else {
                echo '<li class="' . $classname . '"><a href="' . $val['menu_url'] . '">我发送</a></li>';
            }
        }
    }
    ?>
  </ul>
</div>
<div class="user-sms">
  <?php if (!empty($output['message_array'])) { ?>
  <?php foreach($output['message_array'] as $k => $v){ ?>
  <dl>
		<dt><?php echo parsesmiles($v['message_body']); ?></dt>
		<dd>
			<span>收件人：<?php if ($output['drop_type'] == 'msg_seller'){echo $v['from_member_name'];}else { echo $v['from_member_name']; }?></span><span><?php echo @date("Y-m-d H:i:s",$v['message_update_time']); ?></span>
		</dd>
	</dl>
  <?php } ?>
  <?php } ?>
</div>
<script type="text/javascript">
var header_title = '站内消息';
</script>
<script type="text/javascript" src="js/template.js"></script>
<script type="text/javascript" src="js/tmpl/common-top.js"></script>