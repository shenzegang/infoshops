<?php defined('CorShop') or exit('Access Invalid!');?>
<link rel="stylesheet" type="text/css" href="css/idangerous.swiper.css">
<header>
	<div class="header-wrap">
		<h2>B2B2C电商平台</h2>
	</div>
</header>
<div class="swiper-container index-swiper">
	<div class="swiper-wrapper">
    <?php
    foreach ($output['adv_list'] as $value) {
        ?>
    <div class="swiper-slide">
			<img src="<?php echo $value['image']; ?>">
		</div>
    <?php
    }
    ?>
  </div>
	<div class="pagination"></div>
	<div class="index-search-box">
		<div class="index-search">
			<form action="tmpl/product_list.html">
				<input type="text" name="keyword" class="text"> <input type="submit"
					class="sub" value="搜索">
			</form>
		</div>
	</div>
</div>
<!--<div class="index-nav">
  <ul>
    <li><a href="tmpl/cart_list.html"><span class="ico1"></span>购物车</a></li>
    <li><a href="index.php?act=show_groupbuy&op=index"><span class="ico2"></span>团购</a></li>
	<li><a href="#"><span class="ico2"></span>微商城</a></li>
    <li><a href="index.php?act=special&op=index"><span class="ico3"></span>狂欢购</a></li>
    <li><a href="index.php?act=brand&op=index"><span class="ico4"></span>品牌</a></li>
    <li><a href="index.php?act=store&op=list"><span class="ico5"></span>逛店铺</a></li>
    <li><a href="index.php?act=pointprod&op=plist"><span class="ico6"></span>兑换礼品</a></li>
    <li><a href="index.php?act=life&op=index"><span class="ico7"></span>汇生活</a></li>
    <li><a href="index.php?act=home&op=message"><span class="ico8"></span>站内消息</a></li>
  </ul>
</div>-->
<div class="index-ad1">
  <?php echo loadadv(392);?>
  <?php echo loadadv(393);?>
</div>
<div class="index-ad2">
	<div class="ad-left"><?php echo loadadv(387);?></div>
	<div class="ad-right">
		<p><?php echo loadadv(388);?></p>
		<p><?php echo loadadv(389);?></p>
	</div>
</div>
<div class="index-title">
	<p>促销专区</p>
</div>
<div class="index-goods">
  <?php
foreach ($output['top_list'] as $key => $val) {
    ?>
  <a
		href="tmpl/product_detail.html?goods_id=<?php echo $val['goods_id']; ?>">
		<dl>
			<dt>
				<img src="<?php echo thumb($val, 240);?>" />
			</dt>
			<dd>
				<p>￥<?php echo $val['goods_price']; ?></p>
				<span><?php echo $val['discount']; ?>折</span>
			</dd>
		</dl>
	</a>
  <?php
}
?>
</div>
<div class="index-banner"><?php echo loadadv(391);?></div>
<div class="index-cat-box">
	<div class="index-cat">
		<div class="index-cat-title">全部分类</div>
    <?php
    foreach ($output['index_category'] as $key => $val) {
        ?>
    <a
			href="tmpl/product_second_categroy.html?gc_id=<?php echo $val['gc_id']; ?>">
			<dl>
				<dt>
        <?php echo $val['cat']['gc_name']; ?>
        <p><?php echo $val['gc_desc']; ?></p>
				</dt>
				<dd>
					<img
						src="/data/upload/mobile/category/<?php echo $val['gc_thumb']; ?>">
				</dd>
			</dl>
		</a>
    <?php
    }
    ?>
  </div>
</div>
<div class="index-footer">
	<ul>
		<li><a href="/wap/"><span></span>首页</a></li>
		<li><a href="tmpl/product_first_categroy.html"><span></span>分类</a></li>
		<li><a href="tmpl/cart_list.html"><span></span>购物车</a></li>
		<li><a href="tmpl/member/member.html"><span></span>我的</a></li>
	</ul>
</div>
<script type="text/javascript" src="js/idangerous.swiper.min.js"></script>
<script type="text/javascript">
var mySwiper;
$(function() {
    mySwiper = new Swiper(".swiper-container", {
        pagination:".pagination",
        loop:true,
        paginationClickable:true,
        calculateHeight:true,
        cssWidthAndHeight:'height',
        autoplay: 2000
    });
    $('#more').click(function(){
        mySwiper.swipeNext();
    });
});
</script>