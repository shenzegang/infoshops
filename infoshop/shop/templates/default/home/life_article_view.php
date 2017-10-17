<?php defined('CorShop') or exit('Access Invalid!');?>
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/home_life.css"
	rel="stylesheet" type="text/css">
<script
	src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.SuperSlide.2.1.1.js"
	charset="utf-8"></script>
<div class="life-box">
	<div class="life-list">
    <?php include template('home/cur_local');?>
    <div class="article-item">
			<div class="article-title"><?php echo $output['info']['article_title']; ?></div>
			<div class="article-info">
				<span>时间：<?php echo date('Y-m-d H:i:s'); ?></span>
        <?php
        if (! empty($output['info']['source'])) {
            ?>
        <span>来源：<?php echo $output['info']['source']; ?></span>
        <?php
        }
        ?>
        <span>浏览数：<?php echo $output['info']['click']; ?></span> <span>分享到：</span>
				<div class="share-box">
					<div class="bdsharebuttonbox">
						<A class=bds_more href="http://share.baidu.com/code#"
							data-cmd="more"></A><A class=bds_qzone title=分享到QQ空间
							href="http://share.baidu.com/code#" data-cmd="qzone"></A><A
							class=bds_tsina title=分享到新浪微博 href="http://share.baidu.com/code#"
							data-cmd="tsina"></A><A class=bds_tqq title=分享到腾讯微博
							href="http://share.baidu.com/code#" data-cmd="tqq"></A><A
							class=bds_renren title=分享到人人网 href="http://share.baidu.com/code#"
							data-cmd="renren"></A><A class=bds_weixin title=分享到微信
							href="http://share.baidu.com/code#" data-cmd="weixin"></A>
					</div>
					<script>window._bd_share_config={"common":{"bdSnsKey":{},"bdText":"","bdMini":"2","bdMiniList":false,"bdPic":"","bdStyle":"1","bdSize":"24"},"share":{}};with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion='+~(-new Date()/36e5)];</script>
				</div>
			</div>
			<div class="article-desc"><?php echo str_cut($output['info']['description'], 250, '...'); ?></div>
			<div class="article-content"><?php echo $output['info']['article_content']; ?></div>
      <?php
    if (! empty($output['info']['tag'])) {
        ?>
      <div class="article-tag">
				<span>标签：</span>
        <?php
        $tag = explode(',', $output['info']['tag']);
        foreach ($tag as $v) {
            if (empty($v)) {
                continue;
            }
            ?>
        <a
					href="<?php echo urlShop('life_article', 'list', array('keyword' => $v));?>"><?php echo $v; ?></a>
        <?php
        }
        ?>
      </div>
      <?php
    }
    ?>
      <?php
    if (! empty($output['related'])) {
        ?>
      <div class="article-related">
				<ul>
          <?php
        foreach ($output['related'] as $k => $v) {
            ?>
            <li><a
						href="<?php echo urlShop('life_article', 'view', array('id' => $v['article_id']));?>"><span>·</span><?php echo $v['article_title']; ?></a></li>
          <?php
        }
        ?>
        </ul>
			</div>
      <?php
    }
    ?>
    </div>
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