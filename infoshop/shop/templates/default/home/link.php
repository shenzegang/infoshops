<?php defined('CorShop') or exit('Access Invalid!');?>
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/layout.css"
	rel="stylesheet" type="text/css">
<div class="nch-container wrapper">
	<div class="left">
		<div class="nch-module nch-module-style01">
			<div class="title">
				<h3><?php echo $lang['article_article_article_class'];?></h3>
			</div>
			<div class="content">
				<ul class="nch-sidebar-article-class">
          <?php foreach ($output['sub_class_list'] as $k=>$v){?>
          <li><a
						href="<?php echo urlShop('article', 'article', array('ac_id'=>$v['ac_id']));?>"><?php echo $v['ac_name']?></a></li>
          <?php }?>
        </ul>
			</div>
		</div>
	</div>
	<div class="right">
		<div class="nch-article-con">
			<div class="title-bar">
				<h3>友情链接</h3>
			</div>
			<dl class="linkBox">
				<dt>
        <?php
        if (is_array($output['link_list']) && ! empty($output['link_list'])) {
            foreach ($output['link_list'] as $val) {
                if ($val['link_pic'] != '') {
                    ?>
            <a href="<?php echo $val['link_url']; ?>" target="_blank"><img
						src="<?php echo $val['link_pic']; ?>"
						title="<?php echo $val['link_title']; ?>"
						alt="<?php echo $val['link_title']; ?>"></a>
            <?php
                }
            }
        }
        ?>
        </dt>
				<dd>
        <?php
        if (is_array($output['link_list']) && ! empty($output['link_list'])) {
            foreach ($output['link_list'] as $val) {
                if ($val['link_pic'] == '') {
                    ?>
            <a href="<?php echo $val['link_url']; ?>" target="_blank"
						title="<?php echo $val['link_title']; ?>"><?php echo str_cut($val['link_title'],16);?></a>
            <?php
                }
            }
        }
        ?>
        </dd>
			</dl>
		</div>
	</div>
</div>