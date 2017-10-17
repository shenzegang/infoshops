<?php defined('CorShop') or exit('Access Invalid!');?>
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/home_life.css"
	rel="stylesheet" type="text/css">
<script
	src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.SuperSlide.2.1.1.js"
	charset="utf-8"></script>
<div class="life-box">
	<div class="life-list">
    <?php include template('home/cur_local');?>
    <?php
    echo empty($output['list']) ? '<div class="life-empty">暂无相关信息</div>' : '';
    ?>
    <div class="list-magazine">
    <?php
    foreach ($output['list'] as $k => $v) {
        ?>
      <dl>
				<dt>
					<a
						href="<?php echo urlShop('life_magazine', 'view', array('id' => $v['id']));?>"><img
						src="<?php echo DS.DIR_UPLOAD.DS.ATTACH_ARTICLE.DS.$v['thumb']; ?>"></a>
				</dt>
				<dd>
					<a
						href="<?php echo urlShop('life_magazine', 'view', array('id' => $v['id']));?>"><?php echo $v['title']; ?></a>
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
	<div class="list-right">
		<div class="life-title">
			<p>标签</p>
			<span>TAG</span>
		</div>
		<div class="tag-list">
      <?php
    foreach ($output['tag'] as $k => $v) {
        ?>
      <a
				href="<?php echo urlShop('life_article', 'list', array('keyword' => $v['title']));?>"><?php echo $v['title']; ?></a>
      <?php
    }
    ?>
    </div>
		<div class="quick-top">
			<div class="life-title">
				<p>热门文章</p>
				<span>HOT</span>
			</div>
			<ul>
      <?php
    foreach ($output['hot'] as $k => $v) {
        ?>
        <li><span <?php echo ($k <= 2) ? ' class="three"' : ''; ?>><?php echo ($k + 1); ?></span>
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
				<p>推荐文章</p>
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
      <?php
    }
    ?>
      </ul>
		</div>
		<div class="ad300x370"><?php echo loadadv(386);?></div>
	</div>
</div>