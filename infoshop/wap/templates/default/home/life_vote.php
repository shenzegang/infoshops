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
    <div class="vote-list">
			<ul>
    <?php
    foreach ($output['list'] as $k => $v) {
        ?>
        <li><p>
						<a
							href="<?php echo urlShop('life_vote', 'view', array('id' => $v['id']));?>"><?php echo $v['title']; ?></a>
					</p><?php echo date('Y-m-d', $v['time']); ?></li>
    <?php
    }
    ?>
      </ul>
		</div>
    <?php
    echo empty($output['list']) ? '' : '<div class="tc mt20 mb20"><div class="pagination">' . $output['show_page'] . '</div></div>';
    ?>
  </div>
	<div class="list-right">
		<div class="quick-top" style="margin: 0px;">
			<div class="life-title">
				<p>热门投票</p>
				<span>HOT</span>
			</div>
			<ul>
      <?php
    foreach ($output['hot'] as $k => $v) {
        ?>
        <li><span <?php echo ($k <= 2) ? ' class="three"' : ''; ?>><?php echo ($k + 1); ?></span>
				<p>
						<a
							href="<?php echo urlShop('life_vote', 'view', array('id' => $v['id']));?>"><?php echo $v['title']; ?></a>
					</p></li>
      <?php
    }
    ?>
      </ul>
		</div>
		<div class="quick-top">
			<div class="life-title">
				<p>推荐投票</p>
				<span>RECOMMEND ARTICLE</span>
			</div>
			<ul>
      <?php
    foreach ($output['best'] as $k => $v) {
        ?>
        <li><span <?php echo ($k <= 2) ? ' class="three"' : ''; ?>><?php echo ($k + 1); ?></span>
				<p>
						<a
							href="<?php echo urlShop('life_vote', 'view', array('id' => $v['id']));?>"><?php echo $v['title']; ?></a>
					</p></li>
      <?php
    }
    ?>
      </ul>
		</div>
		<div class="ad300x370"><?php echo loadadv(386);?></div>
	</div>
</div>