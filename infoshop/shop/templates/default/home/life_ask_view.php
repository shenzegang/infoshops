<?php defined('CorShop') or exit('Access Invalid!');?>
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/home_life.css"
	rel="stylesheet" type="text/css">
<script
	src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.SuperSlide.2.1.1.js"
	charset="utf-8"></script>
<div class="life-box">
	<div class="life-list">
    <?php include template('home/cur_local');?>
    <div class="ask-item">
			<div class="ask-title">
				<span>问题：</span><?php echo $output['info']['title']; ?></div>
			<div class="ask-content"><?php echo $output['info']['content']; ?></div>
		</div>
	</div>
	<div class="list-right">
		<div class="quick-top" style="margin: 0px;">
			<div class="life-title">
				<p>热门问题</p>
				<span>HOT</span>
			</div>
			<ul>
      <?php
    foreach ($output['hot'] as $k => $v) {
        ?>
        <li><span <?php echo ($k <= 2) ? ' class="three"' : ''; ?>><?php echo ($k + 1); ?></span>
				<p>
						<a
							href="<?php echo urlShop('life_ask', 'view', array('id' => $v['id']));?>"><?php echo $v['title']; ?></a>
					</p></li>
      <?php
    }
    ?>
      </ul>
		</div>
		<div class="quick-top">
			<div class="life-title">
				<p>推荐问题</p>
				<span>RECOMMEND ARTICLE</span>
			</div>
			<ul>
      <?php
    foreach ($output['best'] as $k => $v) {
        ?>
        <li><span <?php echo ($k <= 2) ? ' class="three"' : ''; ?>><?php echo ($k + 1); ?></span>
				<p>
						<a
							href="<?php echo urlShop('life_ask', 'view', array('id' => $v['id']));?>"><?php echo $v['title']; ?></a>
					</p></li>
      <?php
    }
    ?>
      </ul>
		</div>
		<div class="ad300x370"><?php echo loadadv(386);?></div>
	</div>
</div>