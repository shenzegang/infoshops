<?php defined('CorShop') or exit('Access Invalid!');?>
<div id="header"></div>
<div class="life-title">
	<a href="index.php?act=life_ask&op=list">
		<p>问吧</p> <span>更多...</span>
	</a>
</div>
<form action="index.php">
	<input type="hidden" name="act" value="life_ask"> <input type="hidden"
		name="op" value="list">
	<div class="lfei-info-search ask-search">
		<p>
			<input type="text" name="keyword" id="info-keyword" class="text">
		</p>
		<input type="submit" class="sub" value="搜 索">
	</div>
</form>
<?php
foreach ($output['area'] as $k => $v) {
    ?>
<div class="life-title">
	<a
		href="index.php?act=life_article&op=list&id=<?php echo $v['ac_id']; ?>">
		<p><?php echo $v['ac_name']; ?></p> <span>更多...</span>
	</a>
</div>
<div class="life-list">
	<ul>
    <?php
    foreach ($v['best'] as $k1 => $v1) {
        ?>
    <li><a
			href="index.php?act=life_article&op=view&id=<?php echo $v1['article_id']; ?>"><img
				src="<?php echo DS.DIR_UPLOAD.DS.ATTACH_ARTICLE.DS.$v1['thumb']; ?>">
			<p><?php echo $v1['article_title']; ?></p></a></li>
    <?php
    }
    ?>
  </ul>
	<dl>
    <?php
    foreach ($v['list'] as $k1 => $v1) {
        ?>
    <dd>
			<p>
				<em><a
					href="index.php?act=life_article&op=list&id=<?php echo $v['ac_id']; ?>">[<?php echo $output['class_list'][$v1['ac_id']]['ac_name']; ?>]</a></em><a
					href="index.php?act=life_article&op=view&id=<?php echo $v1['article_id']; ?>"><?php echo $v1['article_title']; ?></a>
			</p>
			<span><?php echo date('m-d', $v1['article_time']);?></span>
		</dd>
    <?php
    }
    ?>
  </dl>
</div>
<?php
}
?>
<div class="life-title">
	<a href="index.php?act=life_magazine&op=list">
		<p>本地通——电子DM杂志</p> <span>更多...</span>
	</a>
</div>
<div class="life-magazine">
	<dl>
    <?php
    foreach ($output['magazine'] as $k => $v) {
        ?>
    <dd>
			<a
				href="index.php?act=life_magazine&op=view&id=<?php echo $v['id']; ?>"><img
				src="<?php echo DS.DIR_UPLOAD.DS.ATTACH_ARTICLE.DS.$v['thumb']; ?>"></a>
		</dd>
    <?php
    }
    ?>
  </dl>
</div>
<div class="life-title">
	<a href="index.php?act=life_info&op=list">
		<p>便民电话</p> <span>更多...</span>
	</a>
</div>
<form action="index.php">
	<input type="hidden" name="act" value="life_info"> <input type="hidden"
		name="op" value="list">
	<div class="lfei-info-search">
		<p>
			<input type="text" name="keyword" id="info-keyword" class="text">
		</p>
		<input type="submit" class="sub" value="搜 索">
	</div>
</form>
<div class="life-info">
  <?php
foreach ($output['life_info'] as $k => $v) {
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
<script type="text/javascript">
var header_title = '汇生活';
</script>
<script type="text/javascript" src="js/template.js"></script>
<script type="text/javascript" src="js/tmpl/common-top.js"></script>