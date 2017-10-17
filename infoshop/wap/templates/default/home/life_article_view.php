<?php defined('CorShop') or exit('Access Invalid!');?>
<div id="header"></div>
<div class="article">
	<div class="article-title">
		<p><?php echo $output['info']['article_title']; ?></p>
		<span>时间：<?php echo date('Y-m-d H:i:s'); ?></span>
    <?php
    if (! empty($output['info']['source'])) {
        ?>
    <span>来源：<?php echo $output['info']['source']; ?></span>
    <?php
    }
    ?>
    <span>浏览数：<?php echo $output['info']['click']; ?></span>
	</div>
	<div class="article-content"><?php echo $output['info']['article_content']; ?></div>
</div>
<script type="text/javascript">
var header_title = '<?php echo $output['class_info']['ac_name']; ?>';
</script>
<script type="text/javascript" src="js/template.js"></script>
<script type="text/javascript" src="js/tmpl/common-top.js"></script>