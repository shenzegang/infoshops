<?php defined('CorShop') or exit('Access Invalid!');?>
<div class="wrap">
	<div class="tabmenu"><?php include template('layout/submenu');?></div>
	<div class="ncu-form-style">
		<form method="post" id="idcard_form" action="index.php?act=home&op=upload_idcard" enctype="multipart/form-data">
			<input type="hidden" name="form_submit" value="ok" />
			<?php if($output['mem_info']['idcard_chk'] == 0){ ?>
				<dl>
					<dt class="required"><em class="pngFix"></em><?php echo $lang['home_member_truename'].$lang['nc_colon'];?></dt>
					<dd>
					<span class="w340">
						<input type="text" class="text" maxlength="20" name="member_truename"
							   id="member_truename" value="<?php echo $output['mem_info']['member_truename']; ?>" /> <label for="member_truename" generated="true" class="error"></label>
					</span>
					</dd>
				</dl>
			<?php }else{ ?>
				<dl>
					<dt class="required">
						<em class="pngFix"></em><?php echo $lang['home_member_truename'].$lang['nc_colon'];?></dt>
					<dd>
						<?php echo $output['mem_info']['member_truename']; ?>
					</dd>
				</dl>
			<?php } ?>
			<?php if($output['mem_info']['idcard_chk'] == 0){ ?>
			<dl>
				<dt class="required">
					<em class="pngFix"></em><?php echo $lang['home_member_idcard'].$lang['nc_colon'];?></dt>
				<dd>
					<input type="text" class="text" maxlength="18" name="idcard"
						id="idcard" value="<?php echo $output['mem_info']['idcard']; ?>" /> <label for="idcard" generated="true" class="error"></label>
				</dd>
			</dl>
			<?php }else if($output['mem_info']['idcard_chk'] == 2){ ?>
			<dl>
				<dt class="required">
					<em class="pngFix"></em><?php echo $lang['home_member_idcard'].$lang['nc_colon'];?></dt>
				<dd>
					<?php echo substr_replace($output['mem_info']['idcard'],"**********",5,10);?>
				</dd>
			</dl>
			<?php } ?>

			<?php if($output['mem_info']['idcard_chk'] == 0){ ?>
			<dl>
				<dt><?php echo $lang['home_member_idcard_thumb'].$lang['nc_colon'];?></dt>
				<dd>
					<div class="member-avatar-thumb">
						
						<img
							src="<?php echo getMemberIdcard($output['mem_info']['idcard_photo']).'?'.microtime(); ?>"
							alt="" nc_type="idcard"  />
					</div>
					<p class="hint mt5"><?php echo $lang['nc_member_avatar_hint'];?></p>
				</dd>
			</dl>
			<?php }?>
			<?php if($output['mem_info']['idcard_chk'] == 2){ ?>
			<dl>
				<dt><?php echo $lang['home_member_idcard_status'].$lang['nc_colon'];?></dt>
				<dd>
					<font color="#009933"><b>已认证</b></font>
				</dd>
			</dl>
			<?php }else if($output['mem_info']['idcard_chk'] == 1){ ?>
			<dl>
				<dt><?php echo $lang['home_member_idcard_status'].$lang['nc_colon'];?></dt>
				<dd>
					
						<font color="#FF0000"><b>待审核</b></font>
					
				</dd>
			</dl>
			<?php }else{ ?>
				<dl>
					<dt><?php echo $lang['home_member_change_idcard'].$lang['nc_colon'];?></dt>
					<dd>
						<div class="upload-btn" style="width: auto; border: none;">
							<a href="javascript:void(0);"> <span style="width: auto;"> <input type="file"
																		 name="pic" id="pic" multiple=""  class="file"
										/>
						</span>
								<div class="upload-button" style="border: solid 1px #E7E7E7;"><?php echo $lang['home_member_idcard_upload'];?></div>
								<input id="submit_button" style="display: none" type="button"
									   value="&nbsp;" onClick="submit_form($(this))" />
							</a>
						</div>
					</dd>
				</dl>
				<dl>
					<dt><?php echo $lang['home_member_idcard_status'].$lang['nc_colon'];?></dt>
					<dd>

						<font color="#FF0000"><b>未认证</b></font>

					</dd>
				</dl>
				<dl>
					<dt>&nbsp</dt>
					<dd><input type="submit" id="sub_tj"></dd>
				</dl>
			<?php } ?>
		</form>
	</div>
</div>
<script type="text/javascript">
$(function(){
    $('#idcard_form').validate({
        submitHandler:function(form){
            ajaxpost('idcard_form', '', '', 'onerror') 
        },
        rules : {
			member_truename : {
				required   : true,
				minlength : 2,
				maxlength : 20
			},
           idcard : {
                required   : true,
                //idcard      : true,
                remote   : {
                    url : 'index.php?act=login&op=check_idcartd_edit',
                    type: 'get',
					data:{
						idcard : function(){
							return $('#idcard').val();
						},
						member_id : '<?php echo $_SESSION['member_id'];?>'
					}
                }
            },
			pic : {
				required   : true
			}
        },
        messages : {
			member_truename : {
				required : '<?php echo $lang['home_member_member_truename_null'];?>',
				minlength : '<?php echo $lang['home_member_username_range'];?>',
				maxlength : '<?php echo $lang['home_member_username_range'];?>'
			},
            idcard : {
                required : '<?php echo $lang['home_member_idcard_null'];?>',
                //idcard   : '<?php echo $lang['home_member_idcard_format_wrong'];?>',
				remote	 : '<?php echo $lang['home_member_idcard_exists'];?>'
            },
			pic : {
				required : '<?php echo $lang['home_member_idcard_pic_null'];?>'
			}
        }
    });
	
	$('#pic').change(function(){
		var filepatd=$(this).val();
		var extStart=filepatd.lastIndexOf(".");
		var ext=filepatd.substring(extStart,filepatd.lengtd).toUpperCase();		
		if(ext!=".PNG"&&ext!=".GIF"&&ext!=".JPG"&&ext!=".JPEG"){
			alert("file type error");
			$(this).attr('value','');
			return false;
		}
		if ($(this).val() == '') return false;
		//$("img[nc_type='idcard']").attr('src', $(this).val());
	});
	$('sub_tj').click(function(){
		$("#idcard_form").submit();
	});
});
</script>
