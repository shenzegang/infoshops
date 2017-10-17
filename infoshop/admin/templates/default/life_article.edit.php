<?php defined('CorShop') or exit('Access Invalid!');?>

<div class="page">
	<div class="fixed-bar">
		<div class="item-title">
			<h3>文章管理</h3>
			<ul class="tab-base">
				<li><a href="index.php?act=life_article&op=article"><span>管理</span></a></li>
				<li><a href="index.php?act=life_article&op=article_add"><span>新增</span></a></li>
				<li><a href="JavaScript:void(0);" class="current"><span>编辑</span></a></li>
			</ul>
		</div>
	</div>
	<div class="fixed-empty"></div>
	<form id="article_form" method="post">
		<input type="hidden" name="form_submit" value="ok" /> <input
			type="hidden" name="article_id"
			value="<?php echo $output['article_array']['article_id'];?>" /> <input
			type="hidden" name="ref_url" value="<?php echo getReferer();?>" /> <input
			type="hidden" name="old_tag" class="txt"
			value="<?php echo $output['article_array']['tag'];?>">
		<table class="table tb-type2">
			<tbody>
				<tr class="noborder">
					<td colspan="2" class="required"><label class="validation"
						for="article_title">标题:</label></td>
				</tr>
				<tr class="noborder">
					<td class="vatop rowform"><input type="text"
						value="<?php echo $output['article_array']['article_title'];?>"
						name="article_title" id="article_title" class="txt"></td>
					<td class="vatop tips"></td>
				</tr>
				<tr class="noborder">
					<td colspan="2" class="required"><label class="tag">TAG:</label></td>
				</tr>
				<tr class="noborder">
					<td class="vatop rowform"><input type="text" name="tag" id="tag"
						class="txt" value="<?php echo $output['article_array']['tag'];?>"></td>
					<td class="vatop tips">多个TAG逗号分隔</td>
				</tr>
				<tr class="noborder">
					<td colspan="2" class="required"><label class="source">来源:</label></td>
				</tr>
				<tr class="noborder">
					<td class="vatop rowform"><input type="text" name="source"
						id="source" class="txt"
						value="<?php echo $output['article_array']['source'];?>"></td>
					<td class="vatop tips"></td>
				</tr>
				<tr>
					<td colspan="2" class="required"><label class="validation"
						for="cate_id">所属分类:</label></td>
				</tr>
				<tr class="noborder">
					<td class="vatop rowform"><select name="ac_id" id="ac_id">
							<option value="">请选择...</option>
              <?php if(!empty($output['parent_list']) && is_array($output['parent_list'])){ ?>
              <?php foreach($output['parent_list'] as $k => $v){ ?>
              <option
								<?php if($output['article_array']['ac_id'] == $v['ac_id']){ ?>
								selected='selected' <?php } ?> value="<?php echo $v['ac_id'];?>"><?php echo $v['ac_name'];?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
					<td class="vatop tips"></td>
				</tr>
				<tr>
					<td colspan="2" class="required">缩略图:</td>
				</tr>
				<tr class="noborder">
					<td class="vatop rowform"><span class="type-file-show"> <img
							class="show_image"
							src="<?php echo ADMIN_TEMPLATES_URL;?>/images/preview.png">
							<div class="type-file-preview" style="display: none;">
								<img id="view_img"
									src="<?php echo DS.DIR_UPLOAD.DS.ATTACH_ARTICLE.DS.$output['article_array']['thumb'] ?>">
							</div>
					</span> <span class="type-file-box"> <input type='text'
							name='thumb' id='thumb' class='type-file-text'
							value="<?php echo $output['article_array']['thumb'];?>" /> <input
							type='button' name='button' id='button' value=''
							class='type-file-button' /> <input name="_pic" type="file"
							class="type-file-file" id="_pic" size="30" hidefocus="true" />
					</span></td>
					<td class="vatop tips"><?php echo $lang['brand_index_upload_tips'].$lang['brand_add_support_type'];?>gif,jpg,png</td>
				</tr>
				<tr>
					<td colspan="2" class="required"><label for="article_url">链接:</label></td>
				</tr>
				<tr class="noborder">
					<td class="vatop rowform"><input type="text"
						value="<?php echo $output['article_array']['article_url'];?>"
						name="article_url" id="article_url" class="txt"></td>
					<td class="vatop tips">当填写&quot;链接&quot;后点击文章标题将直接跳转至链接地址，不显示文章内容。链接格式请以http://开头</td>
				</tr>
				<tr>
					<td colspan="2" class="required"><label for="if_show">显示:</label></td>
				</tr>
				<tr class="noborder">
					<td class="vatop rowform onoff"><label for="article_show1"
						class="cb-enable <?php if($output['article_array']['article_show'] == '1'){ ?>selected<?php } ?>"><span><?php echo '是';?></span></label>
						<label for="article_show0"
						class="cb-disable <?php if($output['article_array']['article_show'] == '0'){ ?>selected<?php } ?>"><span><?php echo '否';?></span></label>
						<input id="article_show1" name="article_show"
						<?php if($output['article_array']['article_show'] == '1'){ ?>
						checked="checked" <?php } ?> value="1" type="radio"> <input
						id="article_show0" name="article_show"
						<?php if($output['article_array']['article_show'] == '0'){ ?>
						checked="checked" <?php } ?> value="0" type="radio"></td>
					<td class="vatop tips"></td>
				</tr>
				<tr>
					<td colspan="2" class="required">排序:</td>
				</tr>
				<tr class="noborder">
					<td class="vatop rowform"><input type="text"
						value="<?php echo $output['article_array']['article_sort'];?>"
						name="article_sort" id="article_sort" class="txt"></td>
					<td class="vatop tips"></td>
				</tr>
				<tr>
					<td colspan="2" class="required"><label for="description">文章摘要:</label></td>
				</tr>
				<tr class="noborder">
					<td class="vatop rowform"><textarea name="description" rows="10"
							class="description" id="description"
							style="width: 500px; height: 80px;"><?php echo $output['article_array']['description'];?></textarea></td>
					<td class="vatop tips"><span class="vatop rowform"></span></td>
				</tr>
				<tr>
					<td colspan="2" class="required"><label class="validation">文章内容:</label></td>
				</tr>
				<tr class="noborder">
					<td colspan="2" class="vatop rowform"><?php showEditor('article_content',$output['article_array']['article_content'], '700px', '300px', 'visibility:hidden;', true, true);?></td>
				</tr>
				<tr>
					<td colspan="2" class="required">图片上传:</td>
				</tr>
				<tr class="noborder">
					<td colspan="3" id="divComUploadContainer"><input type="file"
						multiple id="fileupload" name="fileupload" /></td>
				</tr>
				<tr>
					<td colspan="2" class="required">已传图片:</td>
				</tr>
				<tr class="noborder">
					<td colspan="2">
						<ul id="thumbnails" class="thumblists">
              <?php if(is_array($output['file_upload'])){?>
              <?php foreach($output['file_upload'] as $k => $v){ ?>
              <li id="<?php echo $v['upload_id'];?>" class="picture"><input
								type="hidden" name="file_id[]"
								value="<?php echo $v['upload_id'];?>" />
								<div class="size-64x64">
									<span class="thumb"><i></i><img
										src="<?php echo $v['upload_path'];?>"
										alt="<?php echo $v['file_name'];?>"
										onload="javascript:DrawImage(this,64,64);" /></span>
								</div>
								<p>
									<span><a
										href="javascript:insert_editor('<?php echo $v['upload_path'];?>');">插入</a></span><span><a
										href="javascript:del_file_upload('<?php echo $v['upload_id'];?>');">删除</a></span>
								</p></li>
              <?php } ?>
              <?php } ?>
            </ul>
					</td>
				</tr>
			</tbody>
			<tfoot>
				<tr class="tfoot">
					<td colspan="15"><a href="JavaScript:void(0);" class="btn"
						id="submitBtn"><span>提交</span></a></td>
				</tr>
			</tfoot>
		</table>
	</form>
</div>
<script type="text/javascript"
	src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript"
	src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.iframe-transport.js"
	charset="utf-8"></script>
<script type="text/javascript"
	src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.ui.widget.js"
	charset="utf-8"></script>
<script type="text/javascript"
	src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.fileupload.js"
	charset="utf-8"></script>

<script type="text/javascript"
	src="<?php echo RESOURCE_SITE_URL;?>/js/dialog/dialog.js"
	id="dialog_js" charset="utf-8"></script>
<script type="text/javascript"
	src="<?php echo RESOURCE_SITE_URL;?>/js/ajaxfileupload/ajaxfileupload.js"></script>
<script type="text/javascript"
	src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.Jcrop/jquery.Jcrop.js"></script>
<link
	href="<?php echo RESOURCE_SITE_URL;?>/js/jquery.Jcrop/jquery.Jcrop.min.css"
	rel="stylesheet" type="text/css" id="cssfile2" />
<script type="text/javascript"
	src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js"
	charset="utf-8"></script>

<script>
//裁剪图片后返回接收函数
function call_back(picname){
	$('#thumb').val(picname);
	$('#view_img').attr('src','<?php echo UPLOAD_SITE_URL.'/'.ATTACH_ARTICLE;?>/'+picname);
}
//按钮先执行验证再提交表单
$(function(){
    $("#submitBtn").click(function(){
        if($("#article_form").valid()){
            $("#article_form").submit();
    	}
	});
	$('input[class="type-file-file"]').change(uploadChange);
	function uploadChange(){
		var filepatd=$(this).val();
		var extStart=filepatd.lastIndexOf(".");
		var ext=filepatd.substring(extStart,filepatd.lengtd).toUpperCase();		
		if(ext!=".PNG"&&ext!=".GIF"&&ext!=".JPG"&&ext!=".JPEG"){
			alert("file type error");
			$(this).attr('value','');
			return false;
		}
		if ($(this).val() == '') return false;
		ajaxFileUpload();
	}
	function ajaxFileUpload()
	{
		$.ajaxFileUpload
		(
			{
				url:'index.php?act=common&op=pic_upload&form_submit=ok&uploadpath=<?php echo ATTACH_ARTICLE;?>&w=450&h=450',
				secureuri:false,
				fileElementId:'_pic',
				dataType: 'json',
				success: function (data, status)
				{
					if (data.status == 1){
						ajax_form('cutpic','<?php echo $lang['nc_cut'];?>','index.php?act=common&op=pic_cut&type=life&x=200&y=150&resize=1&ratio=1.3333&url='+data.url,690);
					}else{
						alert(data.msg);
					}$('input[class="type-file-file"]').bind('change',uploadChange);
				},
				error: function (data, status, e)
				{
					alert('upload failed');$('input[class="type-file-file"]').bind('change',uploadChange);
				}
			}
		)
	};
});
//
$(document).ready(function(){
	$('#article_form').validate({
        errorPlacement: function(error, element){
			error.appendTo(element.parent().parent().prev().find('td:first'));
        },
        rules : {
            article_title : {
                required   : true
            },
			ac_id : {
                required   : true
            },
			article_url : {
				url : true
            },
			article_content : {
                required   : true
            },
            article_sort : {
                number   : true
            }
        },
        messages : {
            article_title : {
                required   : '文章标题不能为空'
            },
			ac_id : {
                required   : '文章分类不能为空'
            },
			article_url : {
				url : '链接格式不正确'
            },
			article_content : {
                required   : '文章内容不能为空'
            },
            article_sort  : {
                number   : '文章排序仅能为数字'
            }
        }
    });
    // 图片上传
    $('#fileupload').each(function(){
        $(this).fileupload({
            dataType: 'json',
            url: 'index.php?act=life_article&op=article_pic_upload&item_id=<?php echo $output['article_array']['article_id'];?>',
            done: function (e,data) {
                if(data != 'error'){
                	add_uploadedfile(data.result);
                }
            }
        });
    });
});
function add_uploadedfile(file_data)
{
	var newImg = '<li id="' + file_data.file_id + '" class="picture"><input type="hidden" name="file_id[]" value="' + file_data.file_id + '" /><div class="size-64x64"><span class="thumb"><i></i><img src="<?php echo UPLOAD_SITE_URL.'/'.ATTACH_ARTICLE.'/';?>' + file_data.file_name + '" alt="' + file_data.file_name + '" width="64px" height="64px"/></span></div><p><span><a href="javascript:insert_editor(\'<?php echo UPLOAD_SITE_URL.'/'.ATTACH_ARTICLE.'/';?>' + file_data.file_name + '\');">插入</a></span><span><a href="javascript:del_file_upload(' + file_data.file_id + ');">删除</a></span></p></li>';
    $('#thumbnails').prepend(newImg);
}
function insert_editor(file_path){
	KE.appendHtml('article_content', '<img src="'+ file_path + '" alt="'+ file_path + '">');
}
function del_file_upload(file_id)
{
    if(!window.confirm('您确定要删除吗?')){
        return;
    }
    $.getJSON('index.php?act=life_article&op=ajax&branch=del_file_upload&file_id=' + file_id, function(result){
        if(result){
            $('#' + file_id).remove();
        }else{
            alert('删除失败');
        }
    });
}
</script>