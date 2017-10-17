<?php defined('CorShop') or exit('Access Invalid!');?>
<style type="text/css">
body {
	background-color: #F3F3F3;
}
</style>
<div id="header"></div>
<div class="stor-list">
  <?php foreach($output['list'] as $key => $value) {?>
  <a
		href="<?php echo 'index.php?act=store&op=detail&store_id='.$value['microshop_store_id'];?>">
		<dl>
			<dt><?php echo $value['store_name'];?></dt>
			<dd>
				<p>
					<span>店主：</span><i><?php echo $value['member_name'];?></i>
				</p>
				<p>
					<span>所在地：</span><?php echo $value['area_info'];?></p>
				<p>
					<span>主营商品：</span><?php echo $value['store_zy'];?></p>
				<p>
					<span>店铺收藏：</span><em><?php echo $value['store_collect'];?></em>
				</p>
			</dd>
		</dl>
	</a>
  <?php } ?>
</div>
<div class="pages"><?php echo $output['show_page'];?></div>
<script type="text/javascript">
var header_title = '逛店铺';
</script>
<script type="text/javascript" src="js/template.js"></script>
<script type="text/javascript" src="js/tmpl/common-top.js"></script>