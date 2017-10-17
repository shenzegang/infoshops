<?php defined('CorShop') or exit('Access Invalid!');?>
<style type="text/css">
body {
	background-color: #e8e8e8;
}
</style>
<div id="header"></div>
<?php
if (empty($_GET['id'])) {
    ?>

<?php if(!empty($output['brand_class'])){?>
<?php foreach($output['brand_class'] as $key => $val){?>
<?php

            if (empty($key)) {
                ?>
<div class="brand-cat">
	<p><?php echo $val['brand_class'];?></p>
</div>
<?php
            } else {
                ?>
<div class="brand-cat">
	<a href="index.php?act=brand&op=index&id=<?php echo $key; ?>"><p><?php echo $val['brand_class'];?></p>
		<span>更多...</span></a>
</div>
<?php
            }
            ?>
<div class="brand-list">
  <?php $i = 0; foreach($output['brand_c'][$key] as $key1 => $val2){ if($i >= 6){break;} $i++;?>
  <a
		href="index.php?act=brand&op=list&brand=<?php echo $val2['brand_id']; ?>">
		<dl>
			<dt>
				<img src="/data/upload/shop/brand/<?php echo $val2['brand_pic']; ?>">
			</dt>
			<dd><?php echo $val2['brand_name']; ?></dd>
		</dl>
	</a>
  <?php }?>
</div>
<?php }?>
<?php }?>

<script type="text/javascript">
var header_title = '品牌';
</script>
<script type="text/javascript" src="js/template.js"></script>
<script type="text/javascript" src="js/tmpl/common-top.js"></script>
<?php
} else {
    ?>

<?php if(!empty($output['list'])){?>
<div class="brand-list">
  <?php foreach($output['list'] as $key => $val){ ?>
  <a
		href="index.php?act=brand&op=list&brand=<?php echo $val2['brand_id']; ?>">
		<dl>
			<dt>
				<img src="/data/upload/shop/brand/<?php echo $val['brand_pic']; ?>">
			</dt>
			<dd><?php echo $val['brand_name']; ?></dd>
		</dl>
	</a>
<?php }?>
</div>
<?php }?>

<script type="text/javascript">
var header_title = '<?php echo $output['list'][0]['brand_class']; ?>品牌';
</script>
<script type="text/javascript" src="js/template.js"></script>
<script type="text/javascript" src="js/tmpl/common-top.js"></script>
<?php
}
?>