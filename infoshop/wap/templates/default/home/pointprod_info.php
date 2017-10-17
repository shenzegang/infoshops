<?php defined('CorShop') or exit('Access Invalid!');?>
<div id="header"></div>
<div class="gift-pic">
	<img src="<?php echo thumb($output['goods'], '1280');?>">
</div>
<div class="gift-item">
	<dl class="gift-name">
		<dt><?php echo $output['goods']['goods_name'];?></dt>
	</dl>
	<div class="gift-price">
		<dl>
			<dt>礼品价格</dt>
			<dd>
				<span><?php echo $output['goods']['goods_price']; ?>元</span>
			</dd>
		</dl>
		<dl>
			<dt>所需积分</dt>
			<dd>
				<span><?php echo $output['goods']['gift_points']; ?><?php echo $lang['points_unit']; ?></span>
			</dd>
		</dl>
		<dl>
			<dt>礼品库存</dt>
			<dd><?php echo $output['goods']['goods_storage']; ?></dd>
		</dl>
	</div>
	<div class="gift-detail">
		<a
			href="tmpl/product_info.html?goods_id=<?php echo $output['goods']['goods_id']; ?>"
			class="pddetail-go-title clearfix">
			<p>图文详情</p> <span></span>
		</a>
	</div>
	<div class="gift-button">
		<a href="javascript:add_to_cart()">我要兑换</a>
	</div>
</div>
<input type="hidden" id="limitnum"
	value="<?php echo $output['goods']['pgoods_limitnum']; ?>" />
<input type="hidden" name="exnum" class="text" id="exnum" value='1'
	size="4" />
<input type="hidden" id="storagenum"
	value="<?php echo $output['goods']['pgoods_storage']; ?>" />
<script type="text/javascript">
var header_title = '礼品详情';
</script>
<script type="text/javascript" src="js/template.js"></script>
<script type="text/javascript" src="js/tmpl/common-top.js"></script>
<script type="text/javascript" src="js/simple-plugin.js"></script>
<script type="text/javascript">
var goods_id = <?php echo $output['goods']['goods_id']; ?>;
function add_to_cart(quantity){

	var storagenum = parseInt($("#storagenum").val());//库存数量
	var limitnum = parseInt($("#limitnum").val());//限制兑换数量
	var quantity = parseInt($("#exnum").val());//兑换数量
	//验证数量是否合法
	var checkresult = true;
	var msg = '';
	if(!quantity >=1 ){//如果兑换数量小于1则重新设置兑换数量为1
		quantity = 1;
	}
	if(limitnum > 0 && quantity > limitnum){
		checkresult = false;
		msg = '<?php echo $lang['pointprod_info_goods_exnummaxlimit_error']; ?>';
	}
	if(storagenum > 0 && quantity > storagenum){
		checkresult = false;
		msg = '<?php echo $lang['pointprod_info_goods_exnummaxlast_error']; ?>';
	}
	if(checkresult == false){
		alert(msg);
		return false;
	}else{
    <?php if ($_SESSION['is_login'] !== '1'){?>
        window.location = 'tmpl/member/login.html';
    <?php }else{?>
       var key = getcookie('key');//登录标记
       if(key==''){
          window.location.href = WapSiteUrl+'/tmpl/member/login.html';
       }else{
          var json = {};
          var buynum = $('.buy-num').val();
          json.key = key;
          json.cart_id = goods_id+'|'+quantity;
          $.ajax({
              type:'post',
              url:ApiUrl+'/index.php?act=member_buy&op=buy_step1',
              data:json,
              dataType:'json',
              success:function(result){
                  if(typeof(result.datas.error) == 'undefined'){
                      location.href = WapSiteUrl+'/tmpl/order/buy_step1.html?goods_id='+goods_id+'&buynum='+quantity;
                  }else{
                      $.sDialog({
                          skin:"red",
                          content:result.datas.error,
                          okBtn:false,
                          cancelBtn:false
                      });
                  }
              }
          });
       }
    <?php }?>
	}
}
</script>