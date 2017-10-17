<?php defined('CorShop') or exit('Access Invalid!');?>
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/home_life.css"
	rel="stylesheet" type="text/css">
<script
	src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.SuperSlide.2.1.1.js"
	charset="utf-8"></script>
<div class="life-box">
  <?php include template('home/cur_local');?>
  <div class="info-search" style="margin-top: 0px;">
		<form action="index.php">
			<input type="hidden" name="act" value="life_info"> <input
				type="hidden" name="op" value="list"> <input type="text"
				name="keyword" class="text"
				value="<?php echo $output['keyword']; ?>"> <input type="submit"
				class="sub" value="搜 索"> <a href="index.php?act=message&op=index">申请入驻便民电话</a>
		</form>
	</div>
	<div class="life-info">
		<div class="info-list" id="info-list">
      <?php
    echo empty($output['list']) ? '<div class="life-empty">暂无相关信息</div>' : '';
    ?>
      <?php
    foreach ($output['list'] as $k => $v) {
        ?>
      <dl>
				<dt><?php echo $v['title']; ?></dt>
				<dd>
					<strong>主营范围：</strong>
					<p class="info"><?php echo str_cut($v['content'], 120, '...'); ?></p>
					<p class="tel"><?php echo $v['tel']; ?></p>
				</dd>
			</dl>
      <?php
    }
    ?>
    </div>
    <?php
    echo empty($output['list']) ? '' : '<div class="tc mt20 mb20"><div class="pagination">' . $output['show_page'] . '</div></div>';
    ?>
  </div>
</div>