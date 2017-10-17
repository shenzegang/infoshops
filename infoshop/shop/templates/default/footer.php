<?php defined('CorShop') or exit('Access Invalid!');?>
<?php //echo getChat($layout);?>
<div id="faq">
	<div class="footer-tip"></div>
	<div class="wrapper">
    <?php if(is_array($output['article_list']) && !empty($output['article_list'])){ ?>
    <ul>
    <?php foreach ($output['article_list'] as $k=> $article_class){ ?>
    <?php if(!empty($article_class)){ ?>
    <li class="s<?php echo ''.$k+1;?>">
				<dl>
					<dt>
        <?php if(is_array($article_class['class'])) echo $article_class['class']['ac_name'];?>
      </dt>
      <?php if(is_array($article_class['list']) && !empty($article_class['list'])){ ?>
      <?php foreach ($article_class['list'] as $article){ ?>
      <dd>
						<a
							href="<?php if($article['article_url'] != '')echo $article['article_url'];else echo urlShop('article', 'show',array('article_id'=> $article['article_id']));?>"
							title="<?php echo $article['article_title']; ?>"> <?php echo $article['article_title'];?> </a>
					</dd>
      <?php }?>
      <?php }?>
      </dl>
			</li>
    <?php }?>
    <?php }?>
    </ul>
    <?php }?>
    <?php
    $phone_array = explode(',', C('site_phone'));
    ?>
    <div class="footer-service">
			<dl>
				<dt>关注盈放网上商城</dt>
				<dd>
          <?php echo loadadv(376);?>
          <?php echo loadadv(377);?>
        </dd>
				<dt>关注盈放网上商城微信</dt>
				<dd><?php echo loadadv(378);?></dd>
			</dl>
		</div>
	</div>
</div>
<div class="index-link">
	<dl>
    <?php
    if (is_array($output['link_list']) && ! empty($output['link_list'])) {
        foreach ($output['link_list'] as $val) {
            if ($val['link_pic'] == '') {
                ?>
        <dt>
			<a href="<?php echo $val['link_url']; ?>" target="_blank"
				title="<?php echo $val['link_title']; ?>"><?php echo str_cut($val['link_title'],16);?></a>
		</dt>
        <?php
            }
        }
    }
    ?>
  </dl>
	<dl>
    <?php
    if (is_array($output['link_list']) && ! empty($output['link_list'])) {
        foreach ($output['link_list'] as $val) {
            if ($val['link_pic'] != '') {
                ?>
        <dd>
			<a href="<?php echo $val['link_url']; ?>" target="_blank"><img
				src="<?php echo $val['link_pic']; ?>"
				title="<?php echo $val['link_title']; ?>"
				alt="<?php echo $val['link_title']; ?>"></a>
		</dd>
        <?php
            }
        }
    }
    ?>
  </dl>
</div>
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
	版权所有：盈放网上商城 增值电信业务经营许可证：苏B2-12345678 网站备案：苏ICP备12345678号<br>
	<br> <a href="#" target="_blank"><img
		src="/shop/templates/default/images/footer.png" alt='网络经济主体信息'
		border='0' DRAGOVER='true' src='' /></a>
</div>
<?php if (C('debug') == 1){?>
<div id="think_page_trace" class="trace">
	<fieldset id="querybox">
		<legend><?php echo $lang['nc_debug_trace_title'];?></legend>
		<div> <?php print_r(Tpl::showTrace());?> </div>
	</fieldset>
</div>
<?php }?>
<script type="text/javascript"
	src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.cookie.js"></script>
<script type="text/javascript"
	src="<?php echo RESOURCE_SITE_URL;?>/js/perfect-scrollbar.min.js"></script>
<script type="text/javascript"
	src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.mousewheel.js"></script>
<script language="javascript">
$(function(){
	// Membership card
	$('[nctype="mcard"]').membershipCard({type:'shop'});
});
</script>
<div class="float-box">
	<div class="float-tools">
		<div class="float-tools-user">
			<a href="javascript:;" class="link">用户</a>
			<div id="float-login">
				<p class="tip">加载中...</p>
			</div>
			<div class="float-arrow"></div>
		</div>
		<div class="float-cart-ico">
			<a href="javascript:;" class="link">
				<p class="ico"></p>
				<p class="wz">购物车</p> <span>0</span>
			</a>
			<div class="float-arrow"></div>
		</div>
		<div class="tools-bottom">
			<div class="float-tools-service">
				<a href="javascript:;" class="link" title="在线客服">客服</a>
				<div id="float-service">
					<p>在线客服</p>
        <?php
        $qq_array = explode(',', C('site_qq'));
        foreach ($qq_array as $key => $value) {
            echo '<a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=' . $value . '&site=qq&menu=yes"><img border="0" src="http://wpa.qq.com/pa?p=2:' . $value . ':51" alt="点击这里给我发消息" title="点击这里给我发消息"/></a>';
        }
        ?>
        </div>
				<div class="float-arrow"></div>
			</div>
			<div class="float-tools-message">
				<a href="index.php?act=message&op=index" class="link" title="留言建议">留言</a>
				<div class="float-arrow"></div>
			</div>
			<div class="float-tools-qrcode">
				<a href="javascript:;" class="link" title="二维码">二维码</a>
				<div id="float-qrcode"><?php echo loadadv(375);?>扫一扫<br>更多好礼等你来分享！
				</div>
				<div class="float-arrow"></div>
			</div>
			<div class="float-tools-top">
				<a href="#" class="link">Top</a>
				<div class="float-arrow"></div>
			</div>
		</div>
	</div>
	<div class="float-cart-list">
		<div id="float-cart-list"></div>
		<div class="float-cart-total">
			<p>
				共<em>0</em>种商品，总计金额：<span>¥0</span>
			</p>
			<input type="button" style="cursor: pointer" value="去结算" onclick="window.open('<?php echo SHOP_SITE_URL;?>/index.php?act=cart')">
		</div>
	</div>
</div>
<script type="text/javascript">
$(function(){

    floatHeight();
    show_float_cart();

    $(window).resize(function(e) {
        floatHeight();
    });
    $('.float-cart-ico').click(function(){
        if($('.float-cart-list:visible').size() > 0){
            $('.float-cart-ico .link').removeClass('hover');
            $('.float-cart-ico .float-arrow').hide();
            $('.float-cart-list').hide();
        }else{
            $('.float-cart-list').show();
            $('.float-cart-ico .link').addClass('hover');
            $('.float-cart-ico .float-arrow').show();
        }
    });
    $('.float-box').click(function(e){
        e.stopPropagation();
    });
    $('body').click(function(){
        $('.float-cart-ico .link').removeClass('hover');
        $('.float-cart-ico .float-arrow').hide();
        $('.float-cart-list').hide();
        $('#float-login').hide();
        $('#float-login').html('<p class="tip">加载中...</p>');
    });
    function floatHeight(){
        var height = $(window).height();
        if(height < 400){
            $('.float-box').hide();
        }else{
            $('.float-box').height($(window).height()).show();
        }
    }
    $('.float-tools-user').hover(function(){
        show_float_login();
        $(this).find('div').show();
        $(this).find('.link').addClass('hover');
    }, function(){
        $(this).find('div').hide();
        $(this).find('.link').removeClass('hover');
        $('#float-login').html('<p class="tip">加载中...</p>');
    });
    $('.float-tools-service, .float-tools-qrcode').hover(function(){
        $(this).find('div').show();
        $(this).find('.link').addClass('hover');
    }, function(){
        $(this).find('.link').removeClass('hover');
        $(this).find('div').hide();
    });
});
function show_float_login(){
    $('#float-login').show();
    $.get('index.php?act=index&op=login', function(result){
        if(result=='0'){
            $.ajax({
                url: SITEURL+'/index.php?act=login&op=login&inajax=1',
                cache: false,
                success: function(html){
                    $("#float-login").html(html);
                }
            });
        }else{
            $("#float-login").html('<div class="float-user-info"><dl><dt><strong><?php echo $_SESSION['member_name'];?></strong><a href="<?php echo SHOP_SITE_URL;?>/index.php?act=member_snsindex">我的用户中心</a></dt><dd><a href="<?php echo SHOP_SITE_URL;?>/index.php?act=home&op=message">站内消息(<span><?php echo $output['message_num']>0 ? $output['message_num']:'0';?></span>)</a><a href="<?php echo SHOP_SITE_URL;?>/index.php?act=member_order" class="arrow">我的订单</a><a href="<?php echo SHOP_SITE_URL;?>/index.php?act=member_consult&op=my_consult">咨询回复(<span id="member_consult">0</span>)</a><a href="<?php echo SHOP_SITE_URL;?>/index.php?act=member_favorites&op=fglist" class="arrow">我的收藏</a><?php if (C('voucher_allow') == 1){?><a href="<?php echo SHOP_SITE_URL;?>/index.php?act=member_voucher">代金券(<span id="member_voucher">0</span>)</a><?php } ?><?php if (C('points_isuse') == 1){ ?><a href="<?php echo SHOP_SITE_URL;?>/index.php?act=member_points" class="arrow">我的积分</a><?php } ?></dd></dl></div>');
        }
    });
}
function show_float_cart(){
    var obj = $('#float-cart-list');
	$.getJSON(SITEURL+'/index.php?act=cart&op=ajax_load&callback=?', function(result){
	    if(result){
	       	var html = '';
	       	if(result.cart_goods_num >0){
	            for (var i = 0; i < result['list'].length; i++){
	                var goods = result['list'][i];
	            	html+='<dl id="float_item_'+goods['goods_id']+'">';
	            	html+='<dt class="goods-thumb"><a href="'+goods['goods_url']+'" title="'+goods['goods_name']+'"><img src="'+goods['goods_image']+'"></a></dt>';
	            	html+='<dd class="goods-name"><a href="'+goods['goods_url']+'">'+goods['goods_name']+'</a></dd>';
		          	html+='<dd class="handle"><span>&yen;'+goods['goods_price']+'×'+goods['goods_num']+'</span><a href="javascript:void(0);" onClick="drop_float_item('+goods['cart_id']+','+goods['goods_id']+');">删除</a></dd>';
		          	html+="</dl>";
		        }
		        obj.html(html);
                $('.float-cart-ico span').html(result.cart_goods_num);
                //sj 20150910
                $('.float-cart-total em').html(result.cart_goods_num);
                $('.float-cart-total span').html('￥' + result.cart_all_price);
	      } else {
	      	html = '<div class="float-cart-empty"><span>您的购物车中暂无商品<br>赶快选择心爱的商品吧！</span></div>';
	      	obj.html(html);
	      }
	   }
	});
}
function drop_float_item(cart_id,goods_id){
    $.getJSON(SITEURL+'/index.php?act=cart&op=del&cart_id='+cart_id+'&goods_id='+goods_id+'&callback=?', function(result){
        if(result.state){
            var obj = $('#float-cart-list');
            //删除成功
            if(result.quantity == 0){
    	      	obj.html('<div class="float-cart-empty"><span>您的购物车中暂无商品<br>赶快选择心爱的商品吧！</span></div>');
                $('.float-cart-ico span, .float-cart-total em').html(0);
                $('.float-cart-total span').html('￥0');
                $('.addcart-goods-num').remove();
            }else{
                $('#float_item_' + goods_id).remove();
                $('.float-cart-ico span, .float-cart-total em').html(result.quantity);
                $('.float-cart-total span').html('￥' + result.amount);
            }
            load_cart_information();
        }else{
            alert(result.msg);
        }
    });
}
</script>