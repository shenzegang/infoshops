<?php defined('CorShop') or exit('Access Invalid!');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>

<div class="ncsc-form-default">
	<form method="post" action="index.php?act=store_sms_conf&op=index"
		enctype="multipart/form-data" id="my_store_form">
		<input type="hidden" name="form_submit" value="ok" /> <input
			type="hidden" name="id" value="<?php echo $output['rsInfo']['id'];?>" />
		<dl>
			<dt> <?php echo  $lang['store_sms_conf_smstel'].$lang['nc_colon'];?></dt>
			<dd>
				<input class="w200 text" name="tel" type="text" id="store_tel"
					value="<?php echo $output['rsInfo']['tel'];?>" />
		<?php if ($output['bl_quota_endtime']<time()){?>
		<p class="hint">
					<strong style="color: red">您尚未开通或者已经过期</strong>
				</p>
		<?php }else{?>
		<p class="hint">
					<strong style="color: red">您已开启服务,服务时间<?php echo date('Y-m-d H:s', $output['bl_quota_starttime']);?> 至<?php echo date('Y-m-d H:s', $output['bl_quota_endtime']);?>截止</strong>
				</p>
		<?php }?>
      </dd>
		</dl>
		<!-- <dl>
      <dt><?php echo $lang['store_sms_conf_smsnum'].$lang['nc_colon'];?></dt>
      <dd>
        <?php if ($output['rsInfo']['smsnum']>0){echo $output['rsInfo']['smsnum'];}else{echo "0";}?>  <?php echo $lang['store_sms_conf_tiao'];?> &nbsp;&nbsp;&nbsp;<a href="index.php?act=store_sms_conf&op=sms_pay"><?php echo $lang['store_sms_conf_paytit'];?></a>
      </dd>
    </dl>-->
	<?php if ($output['bl_quota_endtime']<time()){?>
	<dl>
			<dt>
				<i class="required">*</i> 套餐购买数量：
			</dt>
			<dd>
				<input id="bundling_quota_quantity" class="text w50 error"
					type="text" onkeyup="value=value.replace(/[^\d]/g,'') "
					onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d.]/g,''))"
					value="" maxlength="2" name="bundling_quota_quantity"> <em
					class="add-on">月</em> <span>
					<p class="hint">购买单位为月(30天)，一次最多购买12个月，不能少于<?php echo $output['list_setting']['sms_smallbuynum']?>个月，购买后立即生效，即可发布优惠套装活动。</p>
					<p class="hint">每月您需要支付<?php echo $output['list_setting']['sms_sellprice']?>元。</p>
					<p class="hint">
						<strong style="color: red">相关费用会在店铺的账期结算中扣除</strong>
					</p>
			
			</dd>
		</dl>
<?php }?>
	 <?php if(is_array($output['templates_list']) && !empty($output['templates_list'])){?>
        
		 <dl>
			<dt>服务内容：</dt>
			<dd>
			 <?php foreach($output['templates_list'] as $k => $v){?>
			 <?php if ($k%2==0){?><br /><?php }?> <?php echo $v['name']; ?> &nbsp;&nbsp;&nbsp;&nbsp;
			  <?php } ?>
		  </dd>
		</dl>
       
        <?php } ?>
   
    <?php if ($output['bl_quota_endtime']<time()){?>
    <div class="bottom">
			<label class="submit-border"><input type="submit" class="submit"
				value="<?php echo $lang['store_buysms_class_submit'];?>" /></label>
		</div><?php }?>
  </form>
</div>
