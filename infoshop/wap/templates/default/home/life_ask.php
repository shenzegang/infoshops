<?php defined('CorShop') or exit('Access Invalid!');?>
<div id="header"></div>
<div class="ask-list">
<?php
foreach ($output['list'] as $k => $v) {
    ?>
  <a href="index.php?act=life_ask&op=view&id=<?php echo $v['id']; ?>">
		<dl>
			<dt>
				<span>问</span><?php echo $v['title']; ?></dt>
			<dd>
				<span>答</span><?php echo str_cut($v['description'], 90, '...'); ?></dd>
		</dl>
	</a>
<?php
}
?>
</div>
<div class="pages"><?php echo $output['show_page']; ?></div>
<script type="text/javascript">
var header_title = '问吧';
</script>
<script type="text/javascript" src="js/template.js"></script>
<script type="text/javascript" src="js/tmpl/common-top.js"></script>