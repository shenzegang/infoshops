<?php defined('CorShop') or exit('Access Invalid!');?>

<div class="wrap">
    <div class="ncu-table-style1">
    <?php if(!$output['auth']){ ?>
    	<span></span><h2>支付密码</h2>
        您的支付密码尚未设置，请点击 <a href="javascript:void(0)" uri="index.php?act=member&op=paypasswd&type=add" dialog_width="480" dialog_id="my_paypasswd_add" dialog_title="<?php echo $lang['member_paypasswd'];?>"  nc_type="dialog" title="<?php echo $lang['member_paypasswd'];?>"><b>这里</b></a> 开始设置
    <?php }else if(isset($_GET['type'])){ ?>
    	<span class="fin"></span><h2>支付密码</h2>
        您的支付密码忘记，请点击 <a href="javascript:void(0)" uri="index.php?act=member&op=paypasswd&status=1" dialog_width="480" dialog_id="my_paypasswd_edit" dialog_title="<?php echo $lang['member_paypasswd_edit'];?>"  nc_type="dialog" title="<?php echo $lang['member_paypasswd_edit'];?>"><b>这里</b></a> 开始修改
	<?php }else{ ?>
    	<span class="fin"></span><h2>支付密码</h2>
        您的支付密码已经启用，请点击 <a href="javascript:void(0)" uri="index.php?act=member&op=paypasswd&type=edit" dialog_width="480" dialog_id="my_paypasswd_edit" dialog_title="<?php echo $lang['member_paypasswd_edit'];?>"  nc_type="dialog" title="<?php echo $lang['member_paypasswd_edit'];?>"><b>这里</b></a> 开始修改
    	&nbsp;  &nbsp;  <a href="javascript:void(0)" uri="index.php?act=member_account&op=mobile_sms" dialog_width="480" dialog_id="mobile_sms" dialog_title="修改支付密码"  nc_type="dialog" title="短信验证" class="forget_pass"><strong>找回支付密码?</strong></a>
	<?php } ?>
    </div>
</div>


