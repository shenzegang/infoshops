<?php defined('CorShop') or exit('Access Invalid!');?>
<script language="JavaScript">
var tms = [];
var day = [];
var hour = [];
var minute = [];
var second = [];
function takeCount() {
    setTimeout("takeCount()", 1000);
    for (var i = 0, j = tms.length; i < j; i++) {
        tms[i] -= 1;
        //计算天、时、分、秒、
        var days = Math.floor(tms[i] / (1 * 60 * 60 * 24));
        var hours = Math.floor(tms[i] / (1 * 60 * 60)) % 24;
        var minutes = Math.floor(tms[i] / (1 * 60)) % 60;
        var seconds = Math.floor(tms[i] / 1) % 60;
        if (days < 0)
            days = 0;
        if (hours < 0)
            hours = 0;
        if (minutes < 0)
            minutes = 0;
        if (seconds < 0)
            seconds = 0;
        //将天、时、分、秒插入到html中
        document.getElementById(day[i]).innerHTML = days;
        document.getElementById(hour[i]).innerHTML = hours;
        document.getElementById(minute[i]).innerHTML = minutes;
        document.getElementById(second[i]).innerHTML = seconds;
    }
}
</script>
<header>
	<div id="header"></div>
</header>
<div class="groupbuy-pic">
	<img
		src="<?php echo gthumb($output['groupbuy_info']['groupbuy_image'],'max');?>">
</div>
<div class="groupbuy-item">
	<dl class="groupbuy-name">
		<dt><?php echo $output['groupbuy_info']['groupbuy_name'];?></dt>
		<dd><?php echo $output['groupbuy_info']['remark'];?></dd>
	</dl>
	<div class="groupbuy-price">
		<dl>
			<dt>团购价</dt>
			<dd>
				<span><?php echo $lang['currency'];?><?php echo $output['groupbuy_info']['groupbuy_price'];?></span>
			</dd>
		</dl>
		<dl>
			<dt><?php echo $lang['text_goods_price'];?></dt>
			<dd>
				<del><?php echo $lang['currency'];?><?php echo $output['groupbuy_info']['goods_price'];?></del>
			</dd>
		</dl>
		<dl>
			<dt><?php echo $lang['text_discount'];?></dt>
			<dd>
				<em><?php echo $output['groupbuy_info']['groupbuy_rebate'];?><?php echo $lang['text_zhe'];?></em>
			</dd>
		</dl>
		<dl>
			<dt><?php echo $lang['text_save'];?></dt>
			<dd>
				<em><?php echo $lang['currency'];?><?php echo sprintf("%01.2f",$output['groupbuy_info']['goods_price']-$output['groupbuy_info']['groupbuy_price']);?></em>
			</dd>
		</dl>
	</div>
	<div class="groupbuy-require">
		<h4><?php echo $lang['text_goods_buy'];?><em><?php echo $output['groupbuy_info']['virtual_quantity']+$output['groupbuy_info']['buy_quantity']; ?></em><?php echo $lang['text_piece'];?></h4>
		<p>
      <?php if(!empty($output['groupbuy_info']['upper_limit'])) { ?>
      每人最多购买<em><?php echo $output['groupbuy_info']['upper_limit'];?></em>件，
      <?php } ?>
      数量有限，欲购从速!
    </p>
	</div>
	<div class="groupbuy-time">
    <?php if(!empty($output['groupbuy_info']['count_down'])) { ?>
    <i class="icon-time"></i>剩余时间：<span id="d1">0</span><strong><?php echo $lang['text_tian'];?></strong><span
			id="h1">0</span><strong><?php echo $lang['text_hour'];?></strong><span
			id="m1">0</span><strong><?php echo $lang['text_minute'];?></strong><span
			id="s1">0</span><strong><?php echo $lang['text_second'];?></strong>
		<script type="text/javascript">
      tms[tms.length] = "<?php echo $output['groupbuy_info']['count_down'];?>";
      day[day.length] = "d1";
      hour[hour.length] = "h1";
      minute[minute.length] = "m1";
      second[second.length] = "s1";
    </script>
    <?php } ?>
  </div>
	<div class="groupbuy-detail">
		<a
			href="tmpl/product_info.html?goods_id=<?php echo $output['groupbuy_info']['goods_id']; ?>"
			class="pddetail-go-title clearfix">
			<p>图文详情</p> <span></span>
		</a>
	</div>
	<div class="groupbuy-button">
		<a
			href="tmpl/product_detail.html?goods_id=<?php echo $output['groupbuy_info']['goods_id']; ?>">我要团购</a>
	</div>
</div>
<script type="text/javascript">
takeCount();
setTimeout("takeCount()", 1000);
var header_title = '团购详情';
</script>
<script type="text/javascript" src="js/template.js"></script>
<script type="text/javascript" src="js/tmpl/common-top.js"></script>