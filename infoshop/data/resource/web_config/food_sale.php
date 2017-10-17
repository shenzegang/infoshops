<?php defined('CorShop') or exit('Access Invalid!');?>

<div class="food-sale">
	<div class="food-left">
		<div class="food-left-top">人气排行榜</div>
    <?php
    $gc_id = 593;
    $goods_class = H('goods_class') ? H('goods_class') : H('goods_class', true);
    $child = (! empty($goods_class[$gc_id]['child'])) ? explode(',', $goods_class[$gc_id]['child']) : array();
    $childchild = (! empty($goods_class[$gc_id]['childchild'])) ? explode(',', $goods_class[$gc_id]['childchild']) : array();
    $gcid_array = array_merge(array(
        $gc_id
    ), $child, $childchild);
    
    $goods_class = Model('goods_class');
    $top_list = $this->table('goods')
        ->field('*')
        ->where(array(
        'gc_id' => array(
            'in',
            $gcid_array
        )
    ))
        ->limit(6)
        ->select();
    ?>
      <dl>
      <?php
    foreach ($top_list as $key => $val) {
        ?>
      	<?php
        if ($key == 0) {
            ?>
      	<dt>
				<a target="_blank"
					href="<?php echo urlShop('goods','index',array('goods_id'=> $val['goods_id'])); ?>"><img
					src="<?php echo thumb($val, 240);?>" /><span><?php echo $key + 1;?></span></a>
				<p class="ti">
					<a target="_blank"
						href="<?php echo urlShop('goods','index',array('goods_id'=> $val['goods_id'])); ?>"
						title="<?php echo $val['goods_name']; ?>"><?php echo $val['goods_name']; ?></a>
				</p>
				<p class="price"><?php echo ncPriceFormatForList($val['goods_price']);?></p>
			</dt>
      	<?php
        } else {
            ?>
      	<dd>
				<span <?php echo ($key > 2) ? ' class="gray"' : '';?>><?php echo $key + 1;?></span><a
					target="_blank"
					href="<?php echo urlShop('goods','index',array('goods_id'=> $val['goods_id'])); ?>"
					title="<?php echo $val['goods_name']; ?>"><?php echo $val['goods_name']; ?></a>
			</dd>
      	<?php
        }
        ?>
      <?php
    }
    ?>
      </dl>
	</div>
	<div class="food-right">
		<ul class="food-sale-tab">
        <?php
        
if (! empty($output['code_sale_list']['code_info']) && is_array($output['code_sale_list']['code_info'])) {
            $i = 0;
            ?>
        <?php
            
foreach ($output['code_sale_list']['code_info'] as $key => $val) {
                $i ++;
                ?>
        <li
				class="<?php echo $i==1 ? 'hover first':'';?><?php echo $i==2 ? 'no-bg':'';?>"><?php echo $val['recommend']['name'];?></li>
        <?php } ?>
        <?php } ?>
      </ul>
                  <?php
                
if (! empty($output['code_sale_list']['code_info']) && is_array($output['code_sale_list']['code_info'])) {
                    $i = 0;
                    ?>
                  <?php
                    
foreach ($output['code_sale_list']['code_info'] as $key => $val) {
                        $i ++;
                        ?>
                          <?php if(!empty($val['goods_list']) && is_array($val['goods_list'])) { ?>
      <div class="food-sale-list"
			<?php echo $i==1 ? ' id="food-sale-list"':'';?>>
      <?php foreach($val['goods_list'] as $k => $v){ ?>
        <dl>
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
                                        </div>
                          <?php } ?>
                  <?php } ?>
                  <?php } ?>
    </div>
</div>