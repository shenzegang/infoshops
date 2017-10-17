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
</head>
<body>
<div align="center" id="qrcode">
		<p >
		先农支付
		<br><br>
		扫一扫
		</p>
	</div>
    
</body>
<script>
 	//这个地址是Demo.java生成的code_url,这个很关键
	var url = "<?php echo $codeUrl ?>";
	
	//参数1表示图像大小，取值范围1-10；参数2表示质量，取值范围'L','M','Q','H'
	var qr = qrcode(10, 'M');
	qr.addData(url);
	qr.make();
	var dom=document.createElement('DIV');
	dom.innerHTML = qr.createImgTag();
	var element=document.getElementById("qrcode");
	element.appendChild(dom);
 </script>
</html>