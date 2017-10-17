<?php defined('CorShop') or exit('Access Invalid!'); ?>
<!doctype html>
<html lang="zh">
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=<?php echo CHARSET;?>">
<title><?php echo $output['html_title'];?></title>
<meta name="keywords" content="<?php echo $output['seo_keywords']; ?>" />
<meta name="description"
	content="<?php echo $output['seo_description']; ?>" />
<meta name="author" content="CorShop">
<meta name="copyright" content="B2B2C Inc. All Rights Reserved">
<?php echo html_entity_decode($GLOBALS['setting_config']['qq_appcode'],ENT_QUOTES); ?><?php echo html_entity_decode($GLOBALS['setting_config']['sina_appcode'],ENT_QUOTES); ?><?php echo html_entity_decode($GLOBALS['setting_config']['share_qqzone_appcode'],ENT_QUOTES); ?><?php echo html_entity_decode($GLOBALS['setting_config']['share_sinaweibo_appcode'],ENT_QUOTES); ?>
<style type="text/css">
body {
	_behavior: url(<?php echo SHOP_TEMPLATES_URL;
?>/
	css
	/csshover.htc);
}
</style>
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/base.css"
	rel="stylesheet" type="text/css">
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/home_header.css"
	rel="stylesheet" type="text/css">
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/home_login.css"
	rel="stylesheet" type="text/css">
<link
	href="<?php echo SHOP_RESOURCE_SITE_URL;?>/font/font-awesome/css/font-awesome.min.css"
	rel="stylesheet" />
<!--[if IE 7]>
  <link rel="stylesheet" href="<?php echo SHOP_RESOURCE_SITE_URL;?>/font/font-awesome/css/font-awesome-ie7.min.css">
<![endif]-->
<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
      <script src="<?php echo RESOURCE_SITE_URL;?>/js/html5shiv.js"></script>
      <script src="<?php echo RESOURCE_SITE_URL;?>/js/respond.min.js"></script>
<![endif]-->
<!--[if IE 6]>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/IE6_PNG.js"></script>
<script>
DD_belatedPNG.fix('.pngFix');
</script>
<script>
// <![CDATA[
if((window.navigator.appName.toUpperCase().indexOf("MICROSOFT")>=0)&&(document.execCommand))
try{
document.execCommand("BackgroundImageCache", false, true);
   }
catch(e){}
// ]]>
</script>
<![endif]-->
<script>
var COOKIE_PRE = '<?php echo COOKIE_PRE;?>';var _CHARSET = '<?php echo strtolower(CHARSET);?>';var SITEURL = '<?php echo SHOP_SITE_URL;?>';var SHOP_SITE_URL = '<?php echo SHOP_SITE_URL;?>';var RESOURCE_SITE_URL = '<?php echo RESOURCE_SITE_URL;?>';var RESOURCE_SITE_URL = '<?php echo RESOURCE_SITE_URL;?>';var SHOP_TEMPLATES_URL = '<?php echo SHOP_TEMPLATES_URL;?>';
</script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/common.js"
	charset="utf-8"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script
	src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.validation.min.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.masonry.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/dialog/dialog.js"
	id="dialog_js" charset="utf-8"></script>
</head>
<body>
<?php require_once template('layout/layout_top');?>
<?php if($output['index_sign'] == 'index' && $output['index_sign'] != '0') { ?>
<div class="top_ad_box">
		<div class="top_ad"><?php echo loadadv(374);?></div>
		<div class="top_ad" style="display: none;"><?php echo loadadv(373);?></div>
		<div class="top_ad_close">x</div>
	</div>
<?php } ?>
<div class="life-header">
		<div class="life-logo">
			<a href="<?php echo SHOP_SITE_URL;?>"><img
				src="<?php echo UPLOAD_SITE_URL.DS.ATTACH_COMMON.DS.$GLOBALS['setting_config']['site_logo']; ?>"
				class="pngFix"></a>
		</div>
		<div class="life-tip"></div>
		<div class="life-search">
			<form action="index.php">
				<input type="hidden" name="act" value="life_article"> <input
					type="hidden" name="op" value="list"> <input type="text"
					name="keyword" class="text"
					value="<?php echo $output['keyword']; ?>"> <input type="submit"
					class="sub">
			</form>
		</div>
		<div class="life-nav">
			<ul>
				<li><a href="<?php echo SHOP_SITE_URL;?>"
					<?php if($output['index_sign'] == 'index' && $output['index_sign'] != '0') {echo 'class="current"';} ?>><?php echo $lang['nc_index'];?></a></li>
      <?php if(!empty($output['nav_list']) && is_array($output['nav_list'])){?>
      <?php foreach($output['nav_list'] as $nav){?>
      <?php if($nav['nav_location'] == '1'){?>
      <li><a
					<?php
                if ($nav['nav_new_open']) {
                    echo ' target="_blank"';
                }
                switch ($nav['nav_type']) {
                    case '0':
                        echo ' href="' . $nav['nav_url'] . '"';
                        break;
                    case '1':
                        echo ' href="' . urlShop('search', 'index', array(
                            'cate_id' => $nav['item_id']
                        )) . '"';
                        if (isset($_GET['cate_id']) && $_GET['cate_id'] == $nav['item_id']) {
                            echo ' class="current"';
                        }
                        break;
                    case '2':
                        echo ' href="' . urlShop('article', 'article', array(
                            'ac_id' => $nav['item_id']
                        )) . '"';
                        if (isset($_GET['ac_id']) && $_GET['ac_id'] == $nav['item_id']) {
                            echo ' class="current"';
                        }
                        break;
                    case '3':
                        echo ' href="' . urlShop('activity', 'index', array(
                            'activity_id' => $nav['item_id']
                        )) . '"';
                        if (isset($_GET['activity_id']) && $_GET['activity_id'] == $nav['item_id']) {
                            echo ' class="current"';
                        }
                        break;
                }
                ?>><?php echo $nav['nav_title'];?></a></li>
      <?php }?>
      <?php }?>
      <?php }?>
    </ul>
			<div class="life-join">
				<a href="<?php echo urlShop('seller_login', 'show_login');?>"
					title="申请商家入驻；已提交申请，可查看当前审核状态。" class="store-join-btn"
					target="_blank">申请商家入驻</a>
			</div>
		</div>
	</div>
<?php require_once($tpl_file);?>
<?php require_once template('life_footer');?>
</body>
</html>