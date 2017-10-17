<?php defined('CorShop') or exit('Access Invalid!');?>
<div class="tabmenu">
	<ul id="listpj" class="tab">
		<li class="active"><a
			href="<?php echo urlShop('store_evaluate', 'list');?>"><?php echo $lang['member_evaluation_frombuyer'];?></a></li>
	</ul>
</div>
<form method="get">
	<table class="search-form">
		<input type="hidden" name="act" value="store_evaluate" />
		<input type="hidden" name="op" value="list" />
		<tr>
			<td>&nbsp;</td>
			<th class="w110">商品名称</th>
			<td class="w160"><input type="text" class="text w150"
				name="goods_name" value="<?php echo $_GET['goods_name'];?>" /></td>
			<th class="w110">评价人</th>
			<td class="w160"><input type="text" class="text w150"
				name="member_name" value="<?php echo $_GET['member_name'];?>" /></td>
			<td class="w70 tc"><label class="submit-border"><input type="submit"
					class="submit" value="<?php echo $lang['nc_search'];?>" /></label></td>
		</tr>
	</table>
</form>
<table class="ncsc-table-style">
	<thead>
		<tr>
			<th class="w10"></th>
			<th class="w10" style="width:50px;"><label> <input type="checkbox" value="1" id="selectAll" > 全选</label></th>
			<th class="tl">评价信息</th>
			<th class="w90"><?php echo $lang['nc_handle'];?>&nbsp;&nbsp;&nbsp;&nbsp;<label><a href="javascript:void(0)" onclick="batch_explain();">批量解释</a></label></th>
		</tr>
	</thead>
	<tbody>
        <?php if (is_array($output['goodsevallist']) && !empty($output['goodsevallist'])) { ?>
        <?php foreach ((array)$output['goodsevallist'] as $k=>$v){?>
        <tr>
			<th ></th>
			<th class="w10"  style="width: 30px;text-align: center;"><input type="checkbox" <?php echo $v['geval_explain'] == "" ? '' : 'unchecked' ?> name="<?php echo $v['geval_explain'] == "" ? 'show' : 'hidden' ?>" id="goodseval_<?php echo $v['geval_id']?>" <?php echo $v['geval_explain'] == "" ? '' : 'style="display:none;"' ?>></th>
			<th><span class="goods-name"><a target="_blank"
					href="<?php echo urlShop('goods', 'index', array('goods_id' => $v['geval_goodsid']));?>"><?php echo $v['geval_goodsname']?></a></span>
				<span>商品评价：<em class="raty"
					data-score="<?php echo $v['geval_scores'];?>"></em></span> <span>评价人：
			<!-- sj 20150827 匿名评价-->
			<?php if ($v['geval_isanonymous'] == 1) { ?>
				<?php echo mb_substr($v['geval_frommembername'], 0, 1,"utf-8") . "***" . mb_substr($v['geval_frommembername'],mb_strlen($v['geval_frommembername'],"utf-8")-1 ,1,"utf-8"); ?>
			<?php } else { ?>
				<?php echo $v['geval_frommembername'];?>
			<?php } ?>
					<time>[<?php echo date('Y-m-d H:i:s',$v['geval_addtime']);?>]</time></span>
			</th>
			<th></th>
		</tr>
		<tr>
			<td colspan="2"></td>
			<td class="tl"><strong>评价内容：</strong> <span><?php echo $v['geval_content'];?></span>
			</td>
			<td rowspan="2" class="nscs-table-handle vt">
				<?php
				//20150813 tjz增加 判断是否有追加解释
				$evaCount = strpos($v['geval_explain'], "|");
				if ($evaCount == false){
				?>
				<span><a
						nctype="btn_show_explain_dialog"
						data-geval-id="<?php echo $v['geval_id']; ?>"
						data-geval-content="<?php echo $v['geval_content']; ?>"
						href="javascript:;" class="btn-acidblue"> <i
							class="icon-comments-alt "></i>
						<p><?php echo $lang['member_evaluation_explain']; ?></p>
					</a></span></td>
			<?php } ?>
		</tr>
		<tr class="bd-line">
			<td colspan="2"></td>
			<td class="tl" colspan="18">
				<div
					<?php echo empty($v['geval_explain']) ? 'style="display:none;"' : '' ?>>
					<strong>解释内容：</strong> <span nctype="explain"><?php
						//20150813 tjz 增加 判断是否有追加解释
						$evaCount = strpos($v['geval_explain'], "|");
						if ($evaCount == false) {
							echo $v['geval_explain'];
						} else {
							echo substr($v['geval_explain'], 0, strpos($v['geval_explain'], "|"));
						}
						?></span>
				</div>

				<div <?php echo $evaCount == false ? 'style="display:none;"' : '' ?>>

					<strong>追加解释：</strong> <span nctype="explain"><?php
						$evaCount = strpos($v['geval_explain'], "|");
						if ($evaCount != false) {
							?>
							【<?php echo substr($v['geval_explain'], strpos($v['geval_explain'], "|") + 1); ?>】
						<?php } ?></span>
				</div>
			</td>
		</tr>
        <?php }?>
        <?php } else { ?>
        <tr>
			<td colspan="20" class="norecord"><div class="warning-option">
					<i class="icon-warning-sign"></i><span><?php echo $lang['no_record'];?></span>
				</div></td>
		</tr>
        <?php } ?>
    </tbody>
	<tfoot>
		<tr>
			<td colspan="20"><div class="pagination"><?php echo $output['show_page']; ?></div></td>
		</tr>
	</tfoot>
</table>
<div id="dialog_explain" style="display: none;">
	<div class="eject_con">
		<input type="hidden" id="geval_id">
		<dl>
			<dt>评价内容:</dt>
			<dd id="geval_content"></dd>
		</dl>
		<dl>
			<dt>
				<i class="required">*</i>解释内容:
			</dt>
			<dd>
				<textarea id="geval_explain" cols="30" rows="10"></textarea>
			</dd>
		</dl>
		<div class="bottom">
			<a href="javascript:void(0);" id="btn_explain_submit" class="submit">确定</a>
		</div>
	</div>
</div>
<div id="dialog_batch_explain" style="display: none;">
	<div class="eject_con">
		<input type="hidden" id="geval_ids">
		<dl>
			<dt>
				<i class="required">*</i>解释内容:
			</dt>
			<dd>
				<textarea id="geval_batch_explain" cols="30" rows="10"></textarea>
			</dd>
		</dl>
		<div class="bottom">
			<a href="javascript:void(0);" id="btn_batch_explain_submit" class="submit">确定</a>
		</div>
	</div>
</div>
<script type="text/javascript"
	src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.raty/jquery.raty.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    $('.raty').raty({
        path: "<?php echo RESOURCE_SITE_URL;?>/js/jquery.raty/img",
            readOnly: true,
            score: function() {
                return $(this).attr('data-score');
                }
                });

    var $item = {};

    $('[nctype="btn_show_explain_dialog"]').on('click', function() {
        $item = $(this).parents('tr').next('tr').find('[nctype="explain"]');
        var geval_id = $(this).attr('data-geval-id');
        var geval_content = $(this).attr('data-geval-content');
        $('#geval_id').val(geval_id);
        $('#geval_content').text(geval_content);
        $('#geval_explain').val('');
        $('#dialog_explain').nc_show_dialog({title:'解释评价'});
        });

    $('#btn_explain_submit').on('click', function() {
        var geval_id = $('#geval_id').val();
        var geval_explain = $('#geval_explain').val();
        $.post("<?php echo urlShop('store_evaluate', 'explain_save');?>",{
            geval_id: geval_id,
                geval_explain: geval_explain 
                }, function(data) {
                    if(data.result) {
						console.log("data：",data);
                        $('#dialog_explain').hide();
                        /*$item.text(data.content);*/
                        //$item.parent().show();
                        showSucc(data.message);
						location.reload();
					} else {
                            showError(data.message);
                            }
                            }, 'json');
        });
	//批量回复评论
	$('#btn_batch_explain_submit').on('click', function() {
		var geval_ids = $('#geval_ids').val();
		var geval_batch_explain = $('#geval_batch_explain').val();
		//toDo获取批量回复的评论的id
		$.post("<?php echo urlShop('store_evaluate', 'batch_explain_save');?>",{
			geval_ids: geval_ids,
			geval_batch_explain: geval_batch_explain
		}, function(data) {
			console.log("data：",data);
			if(data.result) {
				console.log("data：",data);
				$('#dialog__batch_explain').hide();
				/*$item.text(data.content);*/
				//$item.parent().show();
				showSucc(data.message);
				location.reload();
			} else {
				showError(data.message);
			}
		}, 'json');
	});
	//checkbox全选事件
	$('#selectAll').on('click', function () {
		if ($(this).attr('checked')) {
			$('input[type="checkbox"]').attr('checked', true);
			$('input[type="checkbox"][name="hidden"]').attr('checked', false);
		} else {
			$('input[type="checkbox"]').attr('checked', false);
		}
	});
});
//页面初始化后，已经有评论的，前面的checkbox不显示
	function batch_explain(){
		var selectArray = $("input[type='checkbox'][name='show']:checked");
		var geval_ids = [];
		if(selectArray.length == 0){
			showError("请先选择需要回复的评论");
			return;
		}
		for (var i = 0; i < selectArray.length; i++) {
			var geval_id = selectArray[i].id.replace("goodseval_", "");
			//获得需要追加评论的id数组
			geval_ids.push(geval_id);
		}
		$item = $(this).parents('tr').next('tr').find('[nctype="explain"]');
		$('#geval_ids').val(geval_ids);
		$('#geval_batch_explain').val('');
		$('#dialog_batch_explain').nc_show_dialog({title:'解释评价'});
	}
</script>
