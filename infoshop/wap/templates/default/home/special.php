<?php
defined('CorShop') or exit('Access Invalid!');

$ap_cache_file = BASE_DATA_PATH . '/cache/adv/390.php';
if (file_exists($ap_cache_file)) {
    $ap_info = require ($ap_cache_file);
} else {
    $ap_info = array();
}
if (! empty($ap_info)) {
    $adv_list = $ap_info['adv_list'];
    unset($ap_info['adv_list']);
    foreach ($adv_list as $key => $value) {
        $adv_list[$key]['adv_content'] = unserialize($value['adv_content']);
    }
} else {
    $adv_list = array();
}
?>
<style type="text/css">
body {
	background-color: #e8e8e8;
}
</style>
<div id="header"></div>
<div class="special-list">
  <?php
foreach ($adv_list as $key => $val) {
    ?>
  <a href="<?php echo $val['adv_content']['adv_pic_url']; ?>">
		<dl>
			<dt>
				<img
					src="/data/upload/shop/adv/<?php echo $val['adv_content']['adv_pic']; ?>" />
			</dt>
			<dd><?php echo $val['adv_title']; ?></dd>
		</dl>
	</a>
  <?php
}
?>
</div>
<script type="text/javascript">
var header_title = '专题活动';
</script>
<script type="text/javascript" src="js/template.js"></script>
<script type="text/javascript" src="js/tmpl/common-top.js"></script>