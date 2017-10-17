<?php defined('CorShop') or exit('Access Invalid!');?>
<div class="food-area">
	<div class="food-area-title">
  <?php if ($output['code_tit']['code_info']['type'] == 'txt') { ?>
  <?php if(!empty($output['code_tit']['code_info']['floor'])) { ?>
  <?php echo $output['code_tit']['code_info']['floor'];?>
  <?php } ?>
  <?php echo $output['code_tit']['code_info']['title'];?>
  <?php }else { ?>
  <img
			src="<?php echo UPLOAD_SITE_URL.'/'.$output['code_tit']['code_info']['pic'];?>">
  <?php } ?>
  </div>
	<div class="food-area-banner">
  <?php if(!empty($output['code_act']['code_info']['pic'])) { ?>
  <a href="<?php echo $output['code_act']['code_info']['url'];?>"
			title="<?php echo $output['code_act']['code_info']['title'];?>"
			target="_blank"> <img
			src="<?php  echo UPLOAD_SITE_URL.'/'.$output['code_act']['code_info']['pic'];?>"
			alt="<?php echo $output['code_act']['code_info']['title']; ?>">
		</a>
  <?php } ?>
  </div>
	<div class="food-area-list">
    <?php
    
foreach ($output['code_recommend_list']['code_info'] as $key => $val) {
        $i ++;
        ?>
      <?php if(!empty($val['goods_list']) && is_array($val['goods_list'])) { ?>
        <?php
            $index = 0;
            foreach ($val['goods_list'] as $k => $v) {
                $index ++;
                ?>
        <dl <?php if($index == 1){echo ' class="first"';} ?>>
			<dt>
				<a target="_blank"
					href="<?php echo urlShop('goods','index',array('goods_id'=>$v['goods_id'])); ?>">
					<img
					src="<?php echo strpos($v['goods_pic'],'http')===0 ? $v['goods_pic']:UPLOAD_SITE_URL."/".$v['goods_pic'];?>"
					alt="<?php echo $v['goods_name']; ?>" />
				</a>
			</dt>
			<dd>
				<a target="_blank"
					href="<?php echo urlShop('goods','index',array('goods_id'=>$v['goods_id'])); ?>"
					title="<?php echo $v['goods_name']; ?>">
                                          	<?php echo $v['goods_name']; ?></a>
			</dd>
			<dd>
				<span><?php echo ncPriceFormatForList($v['goods_price']); ?></span>
				<del><?php echo ncPriceFormatForList($v['market_price']); ?></del>
			</dd>
		</dl>
        <?php } ?>
      <?php } ?>
    <?php } ?>
  </div>
</div>