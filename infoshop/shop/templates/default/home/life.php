<?php defined('CorShop') or exit('Access Invalid!');?>
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/home_life.css"
	rel="stylesheet" type="text/css">
<script
	src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.SuperSlide.2.1.1.js"
	charset="utf-8"></script>
<?php
$ap_cache_file = BASE_DATA_PATH . '/cache/adv/380.php';
if (file_exists($ap_cache_file)) {
    $ap_info = require ($ap_cache_file);
} else {
    $ap_info = array();
}
if (! empty($ap_info)) {
    $adv_list = $ap_info['adv_list'];
    unset($ap_info['adv_list']);
    foreach ($adv_list as $key => $value) {
        $adv_list[$key]['adv_content'] = unserialize($value['adv_content']);
    }
} else {
    $adv_list = array();
}
?>
<div class="life-box">
	<div class="life-left">
		<div id="slideBox" class="slideBox">
			<div class="bd">
				<ul>
          <?php
        foreach ($adv_list as $key => $value) {
            ?>
          <li><a
						href="<?php echo $value['adv_content']['adv_pic_url']; ?>"
						target="_blank"><img
							src="<?php echo UPLOAD_SITE_URL."/".ATTACH_ADV."/".$value['adv_content']['adv_pic']; ?>" />
						<p><?php echo $value['adv_title']; ?></p></a></li>
          <?php
        }
        ?>
        </ul>
			</div>
			<div class="hd">
				<ul>
          <?php
        foreach ($adv_list as $key => $value) {
            ?>
          <li><?php echo ($key + 1); ?></li>
          <?php
        }
        ?>
        </ul>
			</div>
			<a class="prev" href="javascript:void(0)"></a> <a class="next"
				href="javascript:void(0)"></a>
		</div>
		<script type="text/javascript">
    $('#slideBox').slide({mainCell:'.bd ul',effect:'fold',autoPlay:true});
    </script>
		<div class="life-title">
			<p>
				问吧<em>ASK</em>
			</p>
		</div>
		<div class="life-ask">
			<div class="ask-search">
				<form action="index.php">
					<input type="hidden" name="act" value="life_ask"> <input
						type="hidden" name="op" value="list">
					<p>热门搜索：</p>
					<input type="text" name="keyword" id="ask-keyword"
						class="text empty" value="小孩如何上户口？"> <input type="submit"
						class="sub" value="搜索">
				</form>
				<script type="text/javascript">
        $(function(){
            $('#ask-keyword').focus(function(){
                if($(this).val() == '小孩如何上户口？'){
                    $(this).removeClass('empty');
                    $(this).val('');
                }
            });
            $('#ask-keyword').focusout(function(){
                if($(this).val().length <= 0){
                    $(this).addClass('empty');
                    $(this).val('小孩如何上户口？');
                }
            });
        });
        </script>
			</div>
			<div class="vote-title"></div>
			<div class="index-ask">
				<div class="ask-pic"></div>
				<div class="ask-text">
					如果您通过热门搜索，没有找到您想办事的答案信息，您可以通过提交用户关心的热点问题，反馈给我们。我们会根据您提交的热点问题，以最快的时间搜集完善答案信息库。<br>
					您也可以进入“百事通”用户关心的热点问题答案讨论区，积极参与讨论，为造福一方百姓添砖加瓦。
				</div>
				<div class="ask-button">
					<a href="index.php?act=message&op=index" class="button1">热点问题提交</a><a
						href="http://www.infol.com.cn/circle/index.php?act=group&c_id=2"
						class="button2">进入百事通讨论区</a>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
  function vote(id){
      if($('#vote' + id)){
          var item_id = $('#vote' + id).find('input:checked').val();
          if(!item_id){
              alert('请选择投票项！');
              return false;
          }
          $.ajax({
              type: 'get',
              dataType: 'json',
              url: 'index.php?act=life_vote&op=vote',
              data: {
                  id : item_id
              },
              cache:false,
              success: function(data){
                  if(data.error == 1){
                      alert('投票成功！');
                  }else{
                      alert(data.msg);
                  }
              }
          });
      }
  }
  </script>
	<div class="life-right">
		<div class="life-new">
			<div class="life-title">
				<p>
					新鲜头条<em>LATEST NEWS</em>
				</p>
			</div>
      <?php
    foreach ($output['new'] as $k => $v) {
        ?>
      <a
				href="<?php echo urlShop('life_article', 'view', array('id' => $v['article_id']));?>">
				<dl>
					<dt>
						<strong><?php echo $v['article_title']; ?></strong>
						<p><?php echo str_cut($v['description'], 50, '...'); ?></p>
					</dt>
					<dd>
						<img
							src="<?php echo DS.DIR_UPLOAD.DS.ATTACH_ARTICLE.DS.$v['thumb']; ?>">
					</dd>
				</dl>
			</a>
      <?php
    }
    ?>
    </div>
		<div class="ad300x390">
      <?php echo loadadv(381);?>
    </div>
	</div>
	<div class="life-magazine">
		<div class="life-title">
			<p>
				本地通——电子DM杂志<em>MAGAZINE</em>
			</p>
			<span><a href="index.php?act=life_magazine&op=list">更多>></a></span>
		</div>
		<ul>
      <?php
    foreach ($output['magazine'] as $k => $v) {
        ?>
      <li><a
				href="<?php echo urlShop('life_magazine', 'view', array('id' => $v['id']));?>"><img
					src="<?php echo DS.DIR_UPLOAD.DS.ATTACH_ARTICLE.DS.$v['thumb']; ?>"></a></li>
      <?php
    }
    ?>
    </ul>
	</div>
	<div class="ad1000x100">
    <?php echo loadadv(382);?>
  </div>
<?php
$ap_cache_file = BASE_DATA_PATH . '/cache/adv/383.php';
if (file_exists($ap_cache_file)) {
    $ap_info = require ($ap_cache_file);
} else {
    $ap_info = array();
}
if (! empty($ap_info)) {
    $adv_list = $ap_info['adv_list'];
    unset($ap_info['adv_list']);
    foreach ($adv_list as $key => $value) {
        $adv_list[$key]['adv_content'] = unserialize($value['adv_content']);
    }
} else {
    $adv_list = array();
}
?>
  <div class="life-hui">
		<div class="life-title">
			<p>
				汇生活<em>HUI LIFE</em>
			</p>
			<span><a href="index.php?act=life_article&op=list&id=1">更多>></a></span>
		</div>
		<div id="slideBox2" class="slideBox2">
			<div class="bd">
				<ul>
          <?php
        foreach ($adv_list as $key => $value) {
            ?>
          <li><a
						href="<?php echo $value['adv_content']['adv_pic_url']; ?>"
						target="_blank"><img
							src="<?php echo UPLOAD_SITE_URL."/".ATTACH_ADV."/".$value['adv_content']['adv_pic']; ?>" />
						<p><?php echo $value['adv_title']; ?></p></a></li>
          <?php
        }
        ?>
        </ul>
			</div>
			<div class="hd">
				<ul>
          <?php
        foreach ($adv_list as $key => $value) {
            ?>
          <li><?php echo ($key + 1); ?></li>
          <?php
        }
        ?>
        </ul>
			</div>
			<a class="prev" href="javascript:void(0)"></a> <a class="next"
				href="javascript:void(0)"></a>
		</div>
		<script type="text/javascript">
    $('#slideBox2').slide({mainCell:'.bd ul',effect:'fold',autoPlay:true});
    </script>
		<div class="hui-new">
      <?php
    foreach ($output['hui_pic'] as $k => $v) {
        ?>
      <dl>
				<dt>
					<a
						href="<?php echo urlShop('life_article', 'view', array('id' => $v['article_id']));?>"><img
						src="<?php echo DS.DIR_UPLOAD.DS.ATTACH_ARTICLE.DS.$v['thumb']; ?>"></a>
				</dt>
				<dd>
					<a
						href="<?php echo urlShop('life_article', 'view', array('id' => $v['article_id']));?>"><?php echo $v['article_title']; ?></a>
				</dd>
			</dl>
      <?php
    }
    ?>
      <ul>
      <?php
    foreach ($output['hui_new'] as $k => $v) {
        ?>
        <li><a
					href="<?php echo urlShop('life_article', 'view', array('id' => $v['article_id']));?>"><?php echo $v['article_title']; ?></a></li>
      <?php
    }
    ?>
      </ul>
		</div>
    <?php
    foreach ($output['area'] as $k => $v) {
        ?>
    <div class="hui-area">
      <?php
        foreach ($v['best'] as $k1 => $v1) {
            ?>
      <div class="pic">
				<a
					href="<?php echo urlShop('life_article', 'view', array('id' => $v1['article_id']));?>"><img
					src="<?php echo DS.DIR_UPLOAD.DS.ATTACH_ARTICLE.DS.$v1['thumb']; ?>"></a>
			</div>
			<div class="area-new">
				<span><a
					href="<?php echo urlShop('life_article', 'list', array('id' => $v['ac_id']));?>">[<?php echo $v['ac_name']; ?>]</a></span><a
					href="<?php echo urlShop('life_article', 'view', array('id' => $v1['article_id']));?>"><?php echo $v1['article_title']; ?></a>
			</div>
      <?php
        }
        ?>
      <div class="area-list">
				<ul>
          <?php
        foreach ($v['new'] as $k1 => $v1) {
            ?>
          <li><a
						href="<?php echo urlShop('life_article', 'view', array('id' => $v1['article_id']));?>"><span>·</span><?php echo $v1['article_title']; ?></a></li>
          <?php
        }
        ?>
        </ul>
			</div>
		</div>
    <?php
    }
    ?>
  </div>
	<div class="life-quick">
		<div class="life-title">
			<p>
				便民生活<em>CONVENIENCE OF LIFE</em>
			</p>
			<span><a href="index.php?act=life_article&op=list&id=2">更多>></a></span>
		</div>
<?php
$ap_cache_file = BASE_DATA_PATH . '/cache/adv/384.php';
if (file_exists($ap_cache_file)) {
    $ap_info = require ($ap_cache_file);
} else {
    $ap_info = array();
}
if (! empty($ap_info)) {
    $adv_list = $ap_info['adv_list'];
    unset($ap_info['adv_list']);
    foreach ($adv_list as $key => $value) {
        $adv_list[$key]['adv_content'] = unserialize($value['adv_content']);
    }
} else {
    $adv_list = array();
}
?>
    <div id="slideBox3" class="slideBox3">
			<div class="bd">
				<ul>
          <?php
        foreach ($adv_list as $key => $value) {
            ?>
          <li><a
						href="<?php echo $value['adv_content']['adv_pic_url']; ?>"
						target="_blank"><img
							src="<?php echo UPLOAD_SITE_URL."/".ATTACH_ADV."/".$value['adv_content']['adv_pic']; ?>" />
						<p><?php echo $value['adv_title']; ?></p></a></li>
          <?php
        }
        ?>
        </ul>
			</div>
			<div class="hd">
				<ul>
          <?php
        foreach ($adv_list as $key => $value) {
            ?>
          <li><?php echo ($key + 1); ?></li>
          <?php
        }
        ?>
        </ul>
			</div>
			<a class="prev" href="javascript:void(0)"></a> <a class="next"
				href="javascript:void(0)"></a>
		</div>
		<script type="text/javascript">
    $('#slideBox3').slide({mainCell:'.bd ul',effect:'fold',autoPlay:true});
    </script>
		<div class="quick-top">
			<div class="life-title">
				<p>
					便民资讯<em>INFORMATION</em>
				</p>
			</div>
			<ul>
      <?php
    foreach ($output['news'] as $k => $v) {
        ?>
        <li><span <?php echo ($k <= 2) ? ' class="three"' : ''; ?>><?php echo ($k + 1); ?></span>
				<p>
						<a
							href="<?php echo urlShop('life_article', 'view', array('id' => $v['article_id']));?>"><?php echo $v['article_title']; ?></a>
					</p>2014-01-05</li>
      <?php
    }
    ?>
      </ul>
		</div>
		<div class="life-micro">
			<div class="life-title">
				<p>
					微商城<em>MICRO SHOP</em>
				</p>
				<ul class="hd">
          <?php
        $index = 0;
        foreach ($output['class_list'] as $k => $v) {
            $index ++;
            ?>
          <li <?php echo ($index == 1) ? ' class="hover"' : ''; ?>><?php echo $v['class_name']; ?></li>
          <?php
        }
        ?>
        </ul>
			</div>
			<div class="bd">
        <?php
        foreach ($output['class_list'] as $k => $v) {
            ?>
        <div class="">
        <?php
            if (! empty($v['list'])) {
                foreach ($v['list'] as $k1 => $value) {
                    $personal_image_array = getMicroshopPersonalImageUrl($value, 'list');
                    ?>
          <dl <?php echo ($k1 == 2) ? ' class="last"' : ''; ?>>
						<dt>
							<img
								src="<?php echo cthumb($value['commend_goods_image'], 240,$value['commend_goods_store_id']); ?>">
						</dt>
						<dd class="face">
							<img src="<?php echo getMemberAvatar($value['member_avatar']);?>">
							<p>
								<a
									href="<?php echo MICROSHOP_SITE_URL;?>/index.php?act=home&member_id=<?php echo $value['commend_member_id'];?>"
									target="_blank"><?php echo $value['member_name'];?></a><span><?php echo date('Y-m-d', $value['commend_time']);?></span>
							</p>
						</dd>
						<dd class="info"><?php echo $value['commend_message'];?></dd>
						<dd class="button">
							<span class="like-btn"><a nc_type="microshop_like"
								like_id="<?php echo $value['commend_id'];?>"
								href="javascript:void(0)"><span>喜欢</span><em><?php echo $value['like_count']<=999?$value['like_count']:'999+';?></em></a></span>
						</dd>
					</dl>
        <?php
                }
            }
            ?>
        </div>
        <?php
        }
        ?>
      </div>
			<a class="next">下一页</a> <a class="prev">上一页</a>
			<script type="text/javascript">
      /*
       * 微商城计数加减
       */
      (function($) {
          $.fn.microshop_count = function(options) {
              var settings = $.extend({}, { type:'+',step:1}, options);
              var old_count = parseInt($(this).html());
              if(old_count >= 999) {
                  $(this).html('999+');
              } else {
                  var new_count = old_count;
                  if(settings.type == '-') {
                      new_count = old_count - settings.step;
                  } else {
                      new_count = old_count + settings.step;
                  }
                  if(new_count < 0) {
                      new_count = 0;
                  }
                  $(this).html(new_count);
              }
              return this;
          }
      })(jQuery);
      /*
       * 微商城喜欢
       */
      (function($) {
          $.fn.microshop_like = function(options) {
              var settings = $.extend({}, { type:null,count_target:'' }, options);
              if( settings.type == null ) return false;
              return this.each(function() {
                  $(this).parent().parent().append("<div class='like_tooltips' style='display:none;'></div>");
                  $(this).click(submit_like);
              });
              function submit_like() {
                  var item = $(this);
                  $.getJSON("/microshop/index.php?act=like&op=like_save", { type: settings.type, like_id: item.attr("like_id") }, function(json){
                      if(json.result == "true") {
                          if(settings.count_target == '') {
                              item.find("em").microshop_count({type:"+"});
                          } else {
                              settings.count_target.microshop_count({type:"+"});
                          }
                      }
                      $(".like_tooltips").hide();
                      var tooltips = item.parent().parent().find(".like_tooltips");
                      tooltips.html(json.message).show();
                      setTimeout(function(){tooltips.hide()},2000);
                  });
              }
          }
      })(jQuery);
      $("[nc_type=microshop_like]").microshop_like({type:'goods'});
      $('.life-micro').slide({effect:'left', trigger:"click"});
      </script>
		</div>
		<div class="micro-top">
			<div class="life-title">
				<p>
					店铺街<em>SHOP STREET</em>
				</p>
			</div>
			<dl>
      <?php
    $model_goods = Model('goods');
    
    foreach ($output['micro_top'] as $k => $v) {
        $value['goods_count'] = $model_goods->getGoodsCommonOnlineCount(array(
            'store_id' => $v['store_id']
        ));
        ?>
        <dt>
					<span <?php if($k > 2){ ?> class="gray" <?php } ?>><?php echo ($k + 1); ?></span>
					<p>
						<a
							href="<?php echo MICROSHOP_SITE_URL.DS;?>index.php?act=store&op=detail&store_id=<?php echo $v['microshop_store_id'];?>"><?php echo $v['store_name']; ?></a>
					</p><?php echo $value['goods_count'];?>件商品</dt>
				<dd <?php if(empty($k)){ ?> style="display: block;" <?php } ?>>
					<p>
						<a
							href="<?php echo MICROSHOP_SITE_URL.'/index.php?act=store&op=detail&store_id='.$v['microshop_store_id'];?>"><img
							src="<?php echo empty($v['store_label']) ? UPLOAD_SITE_URL.DS.ATTACH_COMMON.DS.$GLOBALS['setting_config']['default_store_logo'] : DS.DIR_UPLOAD.DS.ATTACH_STORE.DS.$v['store_label']; ?>"
							onload="javascript:DrawImage(this,60,60);" width="60" height="60"></a>
					</p>
					<span><a
						href="<?php echo MICROSHOP_SITE_URL.'/index.php?act=store&op=detail&store_id='.$v['microshop_store_id'];?>">立即查看该店铺</a><em><?php echo $v['store_collect']?></em></span>
				</dd>
      <?php
    }
    ?>
      </dl>
		</div>
	</div>
	<script type="text/javascript">
  $(function(){
      $('.micro-top dl dt').hover(function(){
          $('.micro-top dl dd').hide();
          $(this).next().show();
      });
  });
  </script>
	<div class="info-search">
		<form action="index.php">
			<input type="hidden" name="act" value="life_info"> <input
				type="hidden" name="op" value="list"> <input type="text"
				name="keyword" id="info-keyword" class="text empty" value="煤气电话"> <input
				type="submit" class="sub" value="搜 索"> <a
				href="index.php?act=message&op=index">申请入驻便民电话</a>
		</form>
		<script type="text/javascript">
    $(function(){
        $('#info-keyword').focus(function(){
            if($(this).val() == '煤气电话'){
                $(this).removeClass('empty');
                $(this).val('');
            }
        });
        $('#info-keyword').focusout(function(){
            if($(this).val().length <= 0){
                $(this).addClass('empty');
                $(this).val('煤气电话');
            }
        });
    });
    </script>
	</div>
	<div class="life-info">
		<div class="info-list">
      <?php
    foreach ($output['life_info'] as $k => $v) {
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
	</div>
	<div class="ad1000x100">
    <?php echo loadadv(385);?>
  </div>
</div>