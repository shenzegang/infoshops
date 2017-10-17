<?php defined('CorShop') or exit('Access Invalid!');?>
<div id="header"></div>

<div class="gift-list">
  <?php if (is_array($output['pointprod_list']) && count($output['pointprod_list'])){?>
  <?php foreach ($output['pointprod_list'] as $v){?>
  <a
		href="index.php?act=pointprod&op=pinfo&id=<?php echo $v['goods_id']; ?>">
		<dl>
			<dt>
				<img src="<?php echo thumb($v, 160);?>">
			</dt>
			<dd class="info">
				<p><?php echo $v['goods_name']; ?></p>
			</dd>
			<dd class="price"><?php echo $v['goods_price']; ?>元 + <?php echo $v['gift_points']; ?>积分</dd>
		</dl>
	</a>
  <?php } ?>
  <?php }?>
</div>
<div class="pages"><?php echo $output['show_page'];?></div>
<script type="text/javascript">
var header_title = '礼品兑换';
</script>
<script type="text/javascript" src="js/template.js"></script>
<script type="text/javascript" src="js/tmpl/common-top.js"></script>