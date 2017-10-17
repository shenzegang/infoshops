<?php defined('CorShop') or exit('Access Invalid!');?>
<div id="header"></div>
<div class="magazine-list">
	<dl>
    <?php
    foreach ($output['list'] as $k => $v) {
        ?>
    <dd>
			<a
				href="index.php?act=life_magazine&op=view&id=<?php echo $v['id']; ?>"><img
				src="<?php echo DS.DIR_UPLOAD.DS.ATTACH_ARTICLE.DS.$v['thumb']; ?>">
			<p><?php echo $v['title']; ?></p></a>
		</dd>
    <?php
    }
    ?>
  </dl>
</div>
<div class="pages"><?php echo $output['show_page']; ?></div>
<script type="text/javascript">
var header_title = '本地通——电子DM杂志';
</script>
<script type="text/javascript" src="js/template.js"></script>
<script type="text/javascript" src="js/tmpl/common-top.js"></script>