<?php defined('CorShop') or exit('Access Invalid!'); ?>
<?php if (!empty($output['goodsevallist']) && is_array($output['goodsevallist'])) { ?>
    <?php foreach ($output['goodsevallist'] as $k => $v) { ?>
        <div id="t" class="ncs-commend-floor">
            <div class="user-avatar">
                <?php if ($v['geval_isanonymous'] == 1 && $v['geval_frommembername'] == '系统默认评价') { ?>
                    <!-- sj 20150827 匿名评价鼠标放上去不显示具体信息-->
                    <a
                        target="_blank"> <img
                            src="<?php echo getMemberAvatarForID($v['geval_frommemberid']); ?>">
                    </a>
                <?php } else { ?>
                    <a
                        href="index.php?act=member_snshome&mid=<?php echo $v['geval_frommemberid']; ?>"
                        target="_blank"
                        data-param="{'id':<?php echo $v['geval_frommemberid']; ?>}"
                        nctype="mcard"> <img
                            src="<?php echo getMemberAvatarForID($v['geval_frommemberid']); ?>">
                    </a>
                <?php } ?>
            </div>
            <dl class="detail">
                <dt>
			<span class="user-name">
      <?php if ($v['geval_frommembername'] == '系统默认评价') { ?>
          <?php echo $v['geval_frommembername'] ?>
      <?php } else if ($v['geval_isanonymous'] == 1) { ?>
          <?php echo mb_substr($v['geval_frommembername'], 0, 1, "utf-8") . "***" . mb_substr($v['geval_frommembername'], mb_strlen($v['geval_frommembername'], "utf-8") - 1, 1, "utf-8"); ?>
      <?php } else { ?>
          <a
              href="index.php?act=member_snshome&mid=<?php echo $v['geval_frommemberid']; ?>"
              target="_blank"
              data-param="{'id':<?php echo $v['geval_frommemberid']; ?>}"
              nctype="mcard"><?php echo $v['geval_frommembername']; ?></a>
      <?php } ?>
      </span>
                    <time pubdate="pubdate">[<?php echo @date('Y-m-d', $v['geval_addtime']); ?>]</time>
                </dt>
                <dd>
                    用户评分：<span class="raty" data-score="<?php echo $v['geval_scores']; ?>"></span>
                </dd>
                <dd class="content">
                    评价详情：<span>
				<?php
                $gevalCount = strpos($v['geval_content'], "|");
                if ($gevalCount == false) {
                    echo $v['geval_content'];
                } else {
                    echo substr($v['geval_content'], 0, strpos($v['geval_content'], "|"));
                }
                ?>
			</span>
                </dd>

                <dd class="content">
                    <?php
                    if ($gevalCount != false) { ?>
                    【追加评论】<span>【
                        <?php
                        echo substr($v['geval_content'], strpos($v['geval_content'], "|") + 1); ?>
                        】
                        <?php } ?>
			</span>
                </dd>
                <?php if (!empty($v['geval_explain'])) { ?>
                    <dd class="explain"><?php echo $lang['nc_credit_explain']; ?>：<span>
			<?php
            $evaCount = strpos($v['geval_explain'], "|");
            if ($evaCount == false) {
                echo $v['geval_explain'];
            } else {
                echo substr($v['geval_explain'], 0, strpos($v['geval_explain'], "|"));
            }
            ?></span>
                    </dd>
                    <dd class="explain" style="font-weight: bold;"><?php
                        $evaCount = strpos($v['geval_explain'], "|");
                        if ($evaCount != false) { ?>
                        【追加解释】<span>【
                            <?php
                            echo substr($v['geval_explain'], strpos($v['geval_explain'], "|") + 1); ?>
                            】
                            <?php } ?>
			</span>
                    </dd>
                <?php } ?>
                <?php if (!empty($v['geval_image'])) { ?>
                    <dd>
                        晒单图片：
                        <ul class="photos-thumb"><?php $image_array = explode(',', $v['geval_image']); ?>
                            <?php foreach ($image_array as $value) { ?>
                                <li><a nctype="nyroModal"
                                       href="<?php echo snsThumb($value, 1024); ?>"> <img
                                            src="<?php echo snsThumb($value); ?>">
                                    </a></li>
                            <?php } ?></ul>
                    </dd>
                <?php } ?>
            </dl>
        </div>
    <?php } ?>
    <div class="tr pr5 pb5 pr">
        <a
            href="<?php echo urlShop('goods', 'comments_list', array('goods_id' => $_GET['goods_id'])); ?>"
            target="_blank" class="more-commend">查看全部评价>></a>

        <div class="pagination"> <?php echo $output['show_page']; ?></div>
    </div>
<?php } else { ?>
    <div class="ncs-norecord"><?php echo $lang['no_record']; ?></div>
<?php } ?>
<script type="text/javascript">
    $(document).ready(function () {
        $('.raty').raty({
            path: "<?php echo RESOURCE_SITE_URL;?>/js/jquery.raty/img",
            readOnly: true,
            score: function () {
                return $(this).attr('data-score');
            }
        });

        $('a[nctype="nyroModal"]').nyroModal();

        $('#goodseval').find('.demo').ajaxContent({
            event: 'click', //mouseover
            loaderType: "img",
            loadingMsg: "<?php echo SHOP_TEMPLATES_URL;?>/images/transparent.gif",
            target: '#goodseval'
        });
    });
</script>
