<?php defined('CorShop') or exit('Access Invalid!');?>
<link rel="stylesheet" type="text/css" href="css/child.css">
<header id="header"></header>
<div class="content">
	<div class="product-cnt">
		<div id="product_list">
			<ul class="product-list">
        <?php foreach($output['list'] as $value){?>
        <li class="pdlist-item" goods_id="314"><a
					href="tmpl/product_detail.html?goods_id=<?php echo $value['goods_id'];?>"
					class="pdlist-item-wrap clearfix"> <span class="pdlist-iw-imgwp"><img
							src="<?php echo thumb($value, 240);?>"
							title="<?php echo $value['goods_name'];?>" /></span>
						<div class="pdlist-iw-cnt">
							<p class="pdlist-iwc-pdname"><?php echo $value['goods_name'];?></p>
							<p class="pdlist-iwc-pdprice"><?php echo ncPriceFormatForList($value['goods_price']);?></p>
							<p class="pdlist-iwc-pdcomment clearfix">
								<span class="evaluation_good_swp mr5 fleft">
                  <?php for($i = 1; $i <= $value['evaluation_good_star']; $i++){ ?>
                  <span class="evaluation_good_star fleft"></span>
                  <?php } ?>
                </span> <span class="fleft">(<?php echo $value['evaluation_count'];?>人)</span>
							</p>
						</div>
				</a></li>
        <?php }?>
      </ul>
			<div class="pages"><?php echo $output['show_page']; ?></div>
		</div>
	</div>
</div>
<script type="text/javascript">
var header_title = '<?php echo $output['detail']['store_name'];?>商品';
</script>
<script type="text/javascript" src="js/template.js"></script>
<script type="text/javascript" src="js/tmpl/common-top.js"></script>