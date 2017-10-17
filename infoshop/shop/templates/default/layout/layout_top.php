<?php defined('CorShop') or exit('Access Invalid!');?>
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<div class="public-top-layout w">
	<div class="topbar wrapper">
		<div class="user-entry">
    <?php if($_SESSION['is_login'] == '1'){?>
      <?php echo $lang['nc_hello'];?><span><a
				href="<?php echo urlShop('member_snsindex');?>" class="member_name"><?php echo $_SESSION['member_name'];?></a></span><?php echo $lang['nc_comma'],$lang['welcome_to_site'];?>
      <a href="<?php echo SHOP_SITE_URL;?>"
				title="<?php echo $lang['homepage'];?>"
				alt="<?php echo $lang['homepage'];?>"><span><?php echo $GLOBALS['setting_config']['site_name']; ?></span></a>
			<span><a href="<?php echo urlShop('login','logout');?>"><?php echo $lang['nc_logout'];?></a></span>
    <?php }else{?>
      <a href="<?php echo SHOP_SITE_URL;?>"
				title="<?php echo $lang['homepage'];?>"
				alt="<?php echo $lang['homepage'];?>"><?php echo $lang['nc_hello'].$lang['nc_comma'].$lang['welcome_to_site'];?><?php echo $GLOBALS['setting_config']['site_name']; ?></a>
			<span><a href="<?php echo urlShop('login');?>" class="lg"><?php echo $lang['nc_login'];?></a></span>
			<span><a href="<?php echo urlShop('login','register');?>"><?php echo $lang['nc_register'];?></a></span>
    <?php }?></div>
		<div class="quick-menu">
			<dl class="mobile-menu">
				<dt>
					手机B2B2C电商平台<i></i>
				</dt>
				<dd>
          <?php echo loadadv(379);?>
        </dd>
			</dl>
			<dl>
				<dt>
					<a href="<?php echo SHOP_SITE_URL;?>/index.php?act=member_order">我的订单</a><i></i>
				</dt>
				<dd>
					<ul>
						<li><a
							href="<?php echo SHOP_SITE_URL;?>/index.php?act=member_order">所有订单</a></li>
						<li><a
							href="<?php echo SHOP_SITE_URL;?>/index.php?act=member_order&state_type=state_new">待付款订单</a></li>
						<li><a
							href="<?php echo SHOP_SITE_URL;?>/index.php?act=member_order&state_type=state_send">待确认收货</a></li>
						<li><a
							href="<?php echo SHOP_SITE_URL;?>/index.php?act=member_order&state_type=state_noeval">待评价交易</a></li>
					</ul>
				</dd>
			</dl>
			<dl>
				<dt>
					<a
						href="<?php echo SHOP_SITE_URL;?>/index.php?act=member_favorites&op=fglist"><?php echo $lang['nc_favorites'];?></a><i></i>
				</dt>
				<dd>
					<ul>
						<li><a
							href="<?php echo SHOP_SITE_URL;?>/index.php?act=member_favorites&op=fglist">商品收藏</a></li>
						<li><a
							href="<?php echo SHOP_SITE_URL;?>/index.php?act=member_favorites&op=fslist">店铺收藏</a></li>
					</ul>
				</dd>
			</dl>
			<dl>
				<dt>
					客户服务<i></i>
				</dt>
				<dd>
					<ul>
						<li><a
							href="<?php echo urlShop('article', 'article', array('ac_id' => 2));?>">帮助中心</a></li>
						<li><a
							href="<?php echo urlShop('article', 'article', array('ac_id' => 5));?>">售后服务</a></li>
						<li><a
							href="<?php echo urlShop('article', 'article', array('ac_id' => 6));?>">客服中心</a></li>
					</ul>
				</dd>
			</dl>
			<dl>
				<dt>
					<a href="<?php echo urlShop('seller_login', 'show_login');?>"
						title="申请商家入驻；已提交申请，可查看当前审核状态。" target="_blank">商家中心</a><i></i>
				</dt>
			</dl>
			<dl class="qrcode-menu">
				<dt>关注我们：</dt>
				<dd>
					<div class="qrcode-menu-box">
						<strong>关注B2B2C电商平台</strong>
            <?php echo loadadv(376);?>
            <?php echo loadadv(377);?>
            <strong>关注B2B2C电商平台微信</strong>
            <?php echo loadadv(378);?>
          </div>
				</dd>
			</dl>
      <?php
    if (! empty($output['nav_list']) && is_array($output['nav_list'])) {
        foreach ($output['nav_list'] as $nav) {
            if ($nav['nav_location'] < 1) {
                $output['nav_list_top'][] = $nav;
            }
        }
    }
    if (! empty($output['nav_list_top']) && is_array($output['nav_list_top'])) {
        ?>
      <dl>
				<dt>
					站点导航<i></i>
				</dt>
				<dd>
					<ul>
              <?php foreach($output['nav_list_top'] as $nav){?>
              <li><a
							<?php
            if ($nav['nav_new_open']) {
                echo ' target="_blank"';
            }
            echo ' href="';
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
            echo '"';
            ?>><?php echo $nav['nav_title'];?></a></li>
              <?php }?>
          </ul>
				</dd>
			</dl>
      <?php }?>
    </div>
	</div>
</div>
<script type="text/javascript">
$(function(){
	$(".quick-menu dl").hover(function() {
		$(this).addClass("hover");
	},
	function() {
		$(this).removeClass("hover");
	});
});
</script>