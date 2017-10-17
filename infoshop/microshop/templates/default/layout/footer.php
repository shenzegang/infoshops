<?php defined('CorShop') or exit('Access Invalid!');?>
<div class="clear">&nbsp;</div>
<div id="footer" class="wrapper">
	<p>
		<a href="<?php echo SHOP_SITE_URL;?>"><?php echo $lang['nc_index'];?></a>
    <?php if(!empty($output['nav_list']) && is_array($output['nav_list'])){?>
    <?php foreach($output['nav_list'] as $nav){?>
    <?php if($nav['nav_location'] == '2'){?>
    | <a <?php if($nav['nav_new_open']){?> target="_blank" <?php }?>
			href="<?php
                
switch ($nav['nav_type']) {
                    case '0':
                        echo $nav['nav_url'];
                        break;
                    case '1':
                        echo urlShop('search', 'index', array(
                            'cate_id' => $nav['item_id']
                        ));
                        break;
                    case '2':
                        echo urlShop('article', 'article', array(
                            'ac_id' => $nav['item_id']
                        ));
                        break;
                    case '3':
                        echo urlShop('activity', 'index', array(
                            'activity_id' => $nav['item_id']
                        ));
                        break;
                }
                ?>"><?php echo $nav['nav_title'];?></a>
    <?php }?>
    <?php }?>
    <?php }?>
  </p>
	版权所有：盈放网上商城 增值电信业务经营许可证：苏B2-12345678 网站备案：苏ICP备12345678号<br> <img
		src="/shop/templates/default/images/footer.png">
</div>
<?php if (C('debug') == 1){?>
<div id="think_page_trace" class="trace">
	<fieldset id="querybox">
		<legend><?php echo $lang['nc_debug_trace_title'];?></legend>
		<div> <?php print_r(Tpl::showTrace());?> </div>
	</fieldset>
</div>
<?php }?>
</body>
</html>