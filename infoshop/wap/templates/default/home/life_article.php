<?php defined('CorShop') or exit('Access Invalid!');?>
<div id="header"></div>
<div class="life-son">
	<ul>
    <?php
    foreach ($output['son_list'] as $k => $v) {
        ?>
    <li
			<?php if($info['ac_id'] == $v['ac_id']){echo ' class="hover"';} ?>><a
			href="index.php?act=life_article&op=list&id=<?php echo $v['ac_id']; ?>"><?php echo $v['ac_name']; ?></a></li>
    <?php
    }
    ?>
  </ul>
</div>
<div class="life-article">
  <?php
foreach ($output['list'] as $k => $v) {
    ?>
  <a
		href="index.php?act=life_article&op=view&id=<?php echo $v['article_id']; ?>">
		<dl>
    <?php
    if (! empty($v['thumb'])) {
        ?>
    <dt>
				<img
					src="<?php echo DS.DIR_UPLOAD.DS.ATTACH_ARTICLE.DS.$v['thumb']; ?>">
			</dt>
    <?php
    }
    ?>
    <dd>
				<p class="title"><?php echo $v['article_title']; ?></p>
				<p class="info"><?php echo str_cut($v['description'], 180, '...'); ?></p>
				<p class="time"><?php echo date('Y-m-d', $v['article_time']);?></p>
			</dd>
		</dl>
	</a>
  <?php
}
?>
</div>
<div class="pages"><?php echo $output['show_page']; ?></div>
<script type="text/javascript">
var header_title = '<?php echo $output['info']['ac_name']; ?>';
</script>
<script type="text/javascript" src="js/template.js"></script>
<script type="text/javascript" src="js/tmpl/common-top.js"></script>