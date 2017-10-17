<?php defined('CorShop') or exit('Access Invalid!');?>
<div class="ncs-message-bar">
	<div class="default">
		<h5><?php echo $output['store_info']['store_name']; ?></h5>
		<span member_id="<?php echo $output['store_info']['member_id'];?>"></span>
    <?php if(!empty($output['store_info']['store_qq'])){?>
    <a target="_blank"
			href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $output['store_info']['store_qq'];?>&site=qq&menu=yes"
			title="QQ: <?php echo $output['store_info']['store_qq'];?>"><img
			border="0"
			src="http://wpa.qq.com/pa?p=2:<?php echo $output['store_info']['store_qq'];?>:52"
			style="vertical-align: middle;" /></a>
    <?php }?>
    <?php if(!empty($output['store_info']['store_ww'])){?>
    <a target="_blank"
			href="http://amos.im.alisoft.com/msg.aw?v=2&amp;uid=<?php echo $output['store_info']['store_ww'];?>&site=cntaobao&s=1&charset=<?php echo CHARSET;?>"><img
			border="0"
			src="http://amos.im.alisoft.com/online.aw?v=2&uid=<?php echo $output['store_info']['store_ww'];?>&site=cntaobao&s=2&charset=<?php echo CHARSET;?>"
			alt="<?php echo $lang['nc_message_me'];?>"
			style="vertical-align: middle;" /></a>
    <?php }?>
  </div>
  <?php if((defined('CHAT_SITE_URL') && !empty($output['seller_list'])) || !empty($output['store_info']['store_presales']) || !empty($output['store_info']['store_aftersales']) || $output['store_info']['store_workingtime'] !=''){?>
  <div class="service-list">
    <?php if(defined('CHAT_SITE_URL') && !empty($output['seller_list']) && is_array($output['seller_list'])) {?>
    <dl>
			<dt><?php echo '在线客服';?></dt>
    <?php foreach($output['seller_list'] as $value) {?>
    <dd>
				<span><?php echo $value['seller_name'];?></span><span
					member_id="<?php echo $value['member_id'];?>"></span>
			</dd>
    <?php } ?>
    </dl>
    <?php } ?>
    <?php if(!empty($output['store_info']['store_presales'])){?>
    <dl>
			<dt><?php echo $lang['nc_message_presales'];?></dt>
      <?php foreach($output['store_info']['store_presales'] as $val){?>
      <dd>
				<span><?php echo $val['name']?></span><span>
        <?php if($val['type'] == 1){?>
        <a target="_blank"
					href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $val['num'];?>&site=qq&menu=yes"
					title="QQ: <?php echo $val['num'];?>"><img border="0"
						src="http://wpa.qq.com/pa?p=2:<?php echo $val['num'];?>:51"
						style="vertical-align: middle;" /></a>
        <?php }elseif($val['type'] == 2){?>
        <a target="_blank"
					href="http://amos.im.alisoft.com/msg.aw?v=2&amp;uid=<?php echo $val['num'];?>&site=cntaobao&s=1&charset=<?php echo CHARSET;?>"><img
						border="0"
						src="http://amos.im.alisoft.com/online.aw?v=2&uid=<?php echo $val['num'];?>&site=cntaobao&s=1&charset=<?php echo CHARSET;?>"
						alt="<?php echo $lang['nc_message_me'];?>" /></a>
        <?php }?>
        </span>
			</dd>
      <?php }?>
    </dl>
    <?php }?>
    <?php if(!empty($output['store_info']['store_aftersales'])){?>
    <dl>
			<dt><?php echo $lang['nc_message_service'];?></dt>
      <?php foreach($output['store_info']['store_aftersales'] as $val){?>
      <dd>
				<span><?php echo $val['name']?></span><span>
        <?php if($val['type'] == 1){?>
        <a
					href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $val['num'];?>&site=qq&menu=yes"
					title="QQ: <?php echo $val['num'];?>" target="_blank"><img
						border="0"
						src="http://wpa.qq.com/pa?p=2:<?php echo $val['num'];?>:51"
						alt="<?php echo $lang['nc_message_me'];?>"
						style="vertical-align: middle;"></a>
        <?php }elseif($val['type'] == 2){?>
        <a target="_blank"
					href="http://amos.im.alisoft.com/msg.aw?v=2&amp;uid=<?php echo $val['num'];?>&site=cntaobao&s=1&charset=<?php echo CHARSET;?>"><img
						border="0"
						src="http://amos.im.alisoft.com/online.aw?v=2&uid=<?php echo $val['num'];?>&site=cntaobao&s=1&charset=<?php echo CHARSET;?>"
						alt="<?php echo $lang['nc_message_me'];?>" /></a>
        <?php }?>
        </span>
			</dd>
      <?php }?>
    </dl>
    <?php }?>
    <?php if($output['store_info']['store_workingtime'] !=''){?>
    <dl>
			<dt><?php echo $lang['nc_message_working'];?></dt>
			<dd>
				<p><?php echo html_entity_decode($output['store_info']['store_workingtime']);?></p>
			</dd>
		</dl>
    <?php }?>
  </div>
  <?php }?>
</div>
