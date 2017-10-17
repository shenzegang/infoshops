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
        <li><span><?php echo ($k + 1); ?></span>
				<p>
						<a
							href="<?php echo urlShop('life_article', 'view', array('id' => $v['article_id']));?>"><?php echo $v['article_title']; ?></a>
					</p><?php echo date('Y-m-d', $v['article_time']);?></li>
      <?php
    }
    ?>
      </ul>
		</div>
		<div class="right-best">
			<div class="life-title">
				<p>推荐问题</p>
				<span>RECOMMEND ARTICLE</span>
			</div>
			<ul>
      <?php
    foreach ($output['best'] as $k => $v) {
        ?>
        <li><a
					href="<?php echo urlShop('life_article', 'view', array('id' => $v['article_id']));?>"><img
						src="<?php echo DS.DIR_UPLOAD.DS.ATTACH_ARTICLE.DS.$v['thumb']; ?>">
					<p><?php echo $v['article_title']; ?></p></a></li>
				<li><a
					href="<?php echo urlShop('life_article', 'view', array('id' => $v['article_id']));?>"><img
						src="<?php echo DS.DIR_UPLOAD.DS.ATTACH_ARTICLE.DS.$v['thumb']; ?>">
					<p><?php echo $v['article_title']; ?></p></a></li>
      <?php
    }
    ?>
      </ul>
		</div>
		<div class="ad300x370"><?php echo loadadv(386);?></div>
	</div>
</div>