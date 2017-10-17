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
				<span>投票：</span><?php echo $output['info']['title']; ?></div>
			<div class="vote-item">
				<dl id="vote<?php echo $output['info']['id']; ?>">
          <?php
        foreach ($output['item_list'] as $k => $v) {
            ?>
          <dt>
						<input type="radio" name="vote_item" class="vote_item"
							value="<?php  echo $v['id']; ?>"><?php  echo $v['title']; ?></dt>
          <?php
        }
        ?>
          <dd>
						<input type="submit" value="投票"
							onclick="vote(<?php echo $output['info']['id']; ?>);">
					</dd>
				</dl>
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