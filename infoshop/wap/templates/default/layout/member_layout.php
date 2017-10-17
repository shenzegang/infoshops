<?php
defined('CorShop') or exit('Access Invalid!');
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?php echo $output['html_title'];?></title>
<meta name="keywords" content="<?php echo $output['seo_keywords']; ?>" />
<meta name="description"
	content="<?php echo $output['seo_description']; ?>" />
<meta name="viewport"
	content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">
<link rel="stylesheet" type="text/css" href="css/reset.css">
<link rel="stylesheet" type="text/css" href="css/main.css">
<script type="text/javascript" src="js/config.js"></script>
<script type="text/javascript" src="js/zepto.min.js"></script>
<script type="text/javascript" src="js/common.js"></script>
</head>
<body>
<?php require_once($tpl_file);?>
<?php require_once template('footer');?>
</body>
</html>