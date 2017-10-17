<?php defined('CorShop') or exit('Access Invalid!');?>
<header id="header"></header>
<div class="groupbuy-list">
  <?php foreach($output['groupbuy_list'] as $groupbuy) { ?>
  <a
		href="index.php?act=show_groupbuy&op=groupbuy_detail&group_id=<?php echo $groupbuy['groupbuy_id'];?>">
		<dl>
			<dt>
				<img src="<?php echo gthumb($groupbuy['groupbuy_image'],'mid');?>">
			</dt>
			<dd>
				<p class="title"><?php echo $groupbuy['groupbuy_name'];?></p>
      <?php list($integer_part, $decimal_part) = explode('.', $groupbuy['groupbuy_price']);?>
      <p class="price">
					<em><?php echo $lang['currency'];?><?php echo $integer_part;?></em><span><?php echo $groupbuy['groupbuy_rebate'];?><?php echo $lang['text_zhe'];?></span>
					<del><?php echo $lang['currency'].$groupbuy['goods_price'];?></del>
				</p>
			</dd>
		</dl>
	</a>
  <?php } ?>
  <div class="pages"><?php echo $output['show_page'];?></div>
</div>
<script type="text/javascript">
var header_title = '正在团购';
</script>
<script type="text/javascript" src="js/template.js"></script>
<script type="text/javascript" src="js/tmpl/common-top.js"></script>