<?php defined('CorShop') or exit('Access Invalid!'); ?>
<link href="<?php echo SHOP_TEMPLATES_URL; ?>/css/search_store.css" rel="stylesheet" type="text/css">
<script src="<?php echo RESOURCE_SITE_URL.'/js/class_area_array.js';?>"></script>
<style type="text/css">
    body {
        _behavior: url(<?php echo SHOP_TEMPLATES_URL; ?>/css/csshover.htc);
    }
</style>
<div class="nch-container wrapper">

        <div class="shop_con_list" id="main-nav-holder">

            <nav class="sort-bar" id="main-nav">
                <div class="nch-sortbar-array">
                    排序方式：
                    <ul>
                        <li <?php if (!$_GET['key']) { ?> class="selected" <?php } ?>><a
                                href="<?php echo dropParam(array('order', 'key')); ?>"
                                title="<?php echo $lang['goods_class_index_default_sort']; ?>"><?php echo $lang['goods_class_index_default']; ?></a>
                        </li>
                        <li <?php if ($_GET['key'] == '1') { ?> class="selected" <?php } ?>><a
                                href="<?php echo ($_GET['order'] == '2' && $_GET['key'] == '1') ? replaceParam(array('key' => '1', 'order' => '1')) : replaceParam(array('key' => '1', 'order' => '2')); ?>"
                                <?php if ($_GET['key'] == '1') { ?>
                                    class="<?php echo $_GET['order'] == 1 ? 'asc' : 'desc'; ?>"
                                <?php } ?>
                                title="<?php echo ($_GET['order'] == '2' && $_GET['key'] == '1') ? $lang['goods_class_index_sold_asc'] : $lang['goods_class_index_sold_desc']; ?>"><?php echo $lang['goods_class_index_sold']; ?>
                                <i></i></a></li>
                        <li <?php if ($_GET['key'] == '2') { ?> class="selected" <?php } ?>><a
                                href="<?php echo ($_GET['order'] == '2' && $_GET['key'] == '2') ? replaceParam(array('key' => '2', 'order' => '1')) : replaceParam(array('key' => '2', 'order' => '2')); ?>"
                                <?php if ($_GET['key'] == '2') { ?>
                                    class="<?php echo $_GET['order'] == 1 ? 'asc' : 'desc'; ?>"
                                <?php } ?>
                                title="<?php echo ($_GET['order'] == '2' && $_GET['key'] == '2') ? $lang['goods_class_index_click_asc'] : $lang['goods_class_index_click_desc']; ?>">信用
                                <i></i></a></li>

                    </ul>
                </div>
                <div class="nch-sortbar-location">
                    商品所在地：
                    <div class="select-layer">
                        <div class="holder">
                            <em nc_type="area_name"><?php echo $lang['goods_class_index_area']; ?>
                                <!-- 所在地 --></em>
                        </div>
                        <div class="selected">
                            <a nc_type="area_name"><?php echo $lang['goods_class_index_area']; ?>
                                <!-- 所在地 --></a>
                        </div>
                        <i class="direction"></i>
                        <ul class="options">
                            <?php require(BASE_TPL_PATH . '/home/goods_class_area.php'); ?>
                        </ul>
                    </div>
                </div>

            </nav>
            <!-- 商品列表循环  -->

            <div>
                <?php require_once(BASE_TPL_PATH . '/home/store.squares.php'); ?>
            </div>
            <div class="tc mt20 mb20">
                <div class="pagination"> <?php echo $output['show_page']; ?> </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/waypoints.js"></script>
<script>
    $(function(){
        //浮动导航  waypoints.js
        $('#main-nav-holder').waypoint(function(event, direction) {
            $(this).parent().toggleClass('sticky', direction === "down");
            event.stopPropagation();
        });
    });
    <?php if(isset($_GET['area_id']) && intval($_GET['area_id']) > 0){?>
    $(function(){
        // 选择地区后的地区显示
        $('[nc_type="area_name"]').html(nc_class_a[<?php echo intval($_GET['area_id']);?>]);
    });
    <?php }?>
</script>

