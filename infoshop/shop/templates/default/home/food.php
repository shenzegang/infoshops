<?php defined('CorShop') or exit('Access Invalid!');?>
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/home_food.css"
      rel="stylesheet" type="text/css">
<div class="food-focus">
    <div class="food-cat">
        <?php
        $index = 0;
        foreach ($output['show_goods_class']['593']['class2'] as $key => $val) {
            $index ++;
            if ($index > 6) {
                break;
            }
            ?>
            <div class="food-cat-item<?php if($index%2 == 0){echo ' even';} ?>">
                <div class="food-cat-title">
                    <p class="title"><?php if(!empty($val['pic'])){ ?><span class="ico"><img
                                src="<?php echo $val['pic'];?>"></span><?php } ?><a
                            href="<?php echo urlShop('search','index',array('cate_id'=> $val['gc_id']));?>"><?php echo $val['gc_name'];?></a>
                    </p>
                    <p class="child">
                        <?php if (!empty($val['class3']) && is_array($val['class3'])) { ?>
                            <?php
                            $i = 0;
                            foreach ($val['class3'] as $k => $v) {
                                $i ++;
                                if ($i > 3) {
                                    break;
                                }
                                ?>
                                <a
                                    href="<?php echo urlShop('search','index',array('cate_id'=> $v['gc_id']));?>"><?php echo $v['gc_name'];?></a>
                            <?php } ?>
                        <?php } ?>
                    </p>
                </div>
                <div class="food-cat-son">
                    <dl>
                        <dt>
                            <a
                                href="<?php echo urlShop('search','index',array('cate_id'=> $val['gc_id']));?>"><?php echo $val['gc_name'];?></a>
                        </dt>
                        <dd>
                            <?php if (!empty($val['class3']) && is_array($val['class3'])) { ?>
                                <?php foreach ($val['class3'] as $k => $v) { ?>
                                    <a
                                        href="<?php echo urlShop('search','index',array('cate_id'=> $v['gc_id']));?>"><?php echo $v['gc_name'];?></a>
                                <?php } ?>
                            <?php } ?>
                        </dd>
                    </dl>
                </div>
            </div>
        <?php
        }
        ?>
    </div>
    <?php echo $output['web_html']['food_focus'];?>
    <div class="food-sidebar">
        <div class="sidebar-tip">
            <a href="<?php echo urlShop('show_joinin', 'index');?>"
               title="申请商家入驻；已提交申请，可查看当前审核状态。" target="_blank">商家登录</a>
        </div>
        <div class="sidebar-sale">
            <?php
            if (! empty($output['xianshi_item']) && is_array($output['xianshi_item'])) {
                ?>
                <?php
                $index = 0;
                foreach ($output['xianshi_item'] as $val) {
                    $index ++;
                    if ($index > 1) {
                        break;
                    }
                    ?>
                    <dl>
                        <dt>
                            <a
                                href="<?php echo urlShop('goods','index',array('goods_id'=> $val['goods_id']));?>"><img
                                    src="<?php echo thumb($val, 240);?>"><span><?php echo $val['goods_name']; ?></span>
                                <p></p></a>
                        </dt>
                        <dd>
                            <span><?php echo ncPriceFormatForList($val['xianshi_price']); ?></span><a
                                href="<?php echo urlShop('goods','index',array('goods_id'=> $val['goods_id']));?>">抢购</a>
                        </dd>
                    </dl>
                <?php } ?>
            <?php } ?>
            <div class="sale-time">限时折扣</div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $('.food-cat-item').hover(function(){
        $(this).addClass('food-cat-hover');
    }, function(){
        $(this).removeClass('food-cat-hover');
    });
</script>
<script type="text/javascript"
        src="<?php echo SHOP_RESOURCE_SITE_URL;?>/js/home_index.js"
        charset="utf-8"></script>
<div class="wrapper">
    <?php echo $output['food_sale']['food_sale'];?>
    <?php echo $output['food_area']['food_index'];?>
</div>
<script type="text/javascript">
    $(function(){
        $('.food-sale-tab li').hover(function(index){
            var index = $(this).index();
            $('.food-sale-tab li').removeClass('hover no-bg');
            $(this).addClass('hover');
            $(this).next().addClass('no-bg');
            $('.food-sale-list').hide();
            $('.food-sale-list').eq(index).show();
        });
    });
</script>