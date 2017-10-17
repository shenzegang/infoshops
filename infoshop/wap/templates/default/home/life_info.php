<?php defined('CorShop') or exit('Access Invalid!');?>
<div id="header"></div>
<form action="index.php">
	<input type="hidden" name="act" value="life_info"> <input type="hidden"
		name="op" value="list">
	<div class="lfei-info-search">
		<p>
			<input type="text" name="keyword" id="info-keyword" class="text"
				value="<?php echo $output['keyword']; ?>">
		</p>
		<input type="submit" class="sub" value="搜 索">
	</div>
</form>
<div class="life-info">
  <?php
foreach ($output['list'] as $k => $v) {
    ?>
  <a href="tel:<?php echo $v['tel']; ?>">
		<dl>
			<dt><?php echo $v['title']; ?></dt>
			<dd><?php echo $v['tel']; ?></dd>
		</dl>
	</a>
  <?php
}
?>
</div>
<div class="pages"><?php echo $output['show_page']; ?></div>
<script type="text/javascript">
var header_title = '便民电话';
</script>
<script type="text/javascript" src="js/template.js"></script>
<script type="text/javascript" src="js/tmpl/common-top.js"></script>