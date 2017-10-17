<?php defined('CorShop') or exit('Access Invalid!');?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=<?php echo CHARSET;?>">
<title><?php echo ($lang['nc_member_path_'.$output['menu_sign']]==''?'':$lang['nc_member_path_'.$output['menu_sign']].'_').$output['html_title'];?></title>
<meta name="keywords" content="<?php echo C('site_keywords'); ?>" />
<meta name="description" content="<?php echo C('site_description'); ?>" />
<meta name="author" content="CorShop">
<meta name="copyright" content="B2B2C Inc. All Rights Reserved">
<script src="<?php echo RESOURCE_SITE_URL;?>/js/qrcode.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.validation.min.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.masonry.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/dialog/dialog.js" id="dialog_js" charset="utf-8"></script>
</head>
<body>
<div align="center" id="qrcode">
		<p >
		先农支付
		<br><br>
		扫一扫
		</p>
	</div>
    <h3 style="height:25px; line-height:25px; text-align:center;"><font color="#FF0000">请在30秒内完成扫码支付，否则订单失效</font></h3>
</body>
<script language="javascript">
 	//这个地址是Demo.java生成的code_url,这个很关键
	var url = "<?php echo $output['codeUrl']; ?>";
	var pay_sn = "<?php echo $output['pay_sn']; ?>";
	var href = "<?php echo $output['url']; ?>";
	var order_type = "<?php echo $output['order_type']; ?>";
	//参数1表示图像大小，取值范围1-10；参数2表示质量，取值范围'L','M','Q','H'
	var qr = qrcode(10, 'M');
	qr.addData(url);
	qr.make();
	var dom=document.createElement('DIV');
	dom.innerHTML = qr.createImgTag();
	var element=document.getElementById("qrcode");
	element.appendChild(dom);
	
	$(document).ready(function(){
		//showDialog('请在5秒内扫码付款', 'error','','','','','','','','',6);return false;
		var t;
		var flag = true;
		if(t != null){
			clearTimeout(t);
		}
		t = setTimeout(function(){
			$.ajax({type:"GET", url: "index.php?act=payment&op=validWeichatPayStatus", data:{'out_trade_no':pay_sn,'order_type':order_type}, async: false,success: function(data){
				if(data != '1') {
					
					//window.location.href="<?php echo SHOP_SITE_URL; ?>/index.php?act=predeposit";
					showDialog('支付失败', 'error','','','','','','','','',2);return false;
					$("#next_button").addClass("ncc-btn-green");
				}else{
					window.location.href=href;
				}
		 	}});
		}, 30000);
	});
 </script>
</html>