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
				<h3>留言反馈</h3>
			</div>
			<div class="messageBox">
				<form method="post" onSubmit="return check();">
					<input type="hidden" name="form_submit"  id="Submit" value="ok" />
					<dl>
						<dt>
							<span>您的姓名：</span>
							<p>
								<input type="text" name="name" id="name" class="text" onblur="nameCheck(this.value)" onfocus="nameFocus();"/><em>*</em>
							<p id="error_name"></p>

							</p>
						</dt>
						<dt>
							<span>您的性别：</span>
							<p>
								<input type="radio" name="sex" value="0" class="radio" checked>
								男 <input type="radio" name="sex" value="1" class="radio"> 女
							</p>
						</dt>
						<dt>
							<span>联系邮箱：</span>
							<p>
								<input type="text" name="email" id="email" class="text" onblur="emailCheck(this.value)" onfocus="emailFocus();">
							<em>*</em><p id="error_mail"></p>
							</p>
						</dt>
						<dt>
							<span>联系电话：</span>
							<p>
								<input type="text" name="tel" id="tel" class="text" onblur="telCheck(this.value)" onfocus="telFocus();" maxlength="11"/><em>*</em>
							<p id="error_tel"></p>

							</p>
						</dt>
						<dt>
							<span>内容：</span>
							<p>
								<textarea name="content" id="content" class="text" onblur="contentCheck(this.value)" onfocus="contentFocus();"></textarea>
								<em>*</em><p id="error_content"></p>
							</p>
						</dt>
						<dd>
							<input type="submit" value="提交">
						</dd>
					</dl>
				</form>
			</div>
		</div>
	</div>
</div>
<script>
	function nameCheck(name) {
		var pattern =/^[a-zA-Z0-9\u4e00-\u9fa5\_]+$/;
		if (!pattern.test(name)) {
			$("#error_name").html('<label for="company_registered_capital" class="error">不能包含敏感字符</label>');
			return false;
		}else{
			$("#error_name").html("");
			return true;
		}
	}
	function nameFocus(){
		$("#error_name").val("");
	}
	function emailCheck(email) {
		var pattern = /^(\w)+(\.\w+)*@(\w)+((\.\w+)+)$/;
		if (!pattern.test(email)) {
			$("#error_mail").html('<label for="company_registered_capital" class="error">请输入正确的邮箱</label>');
			return false;
		}else{
			$("#error_mail").html("");
			return true;
		}
	}
	function emailFocus(){
		$("#error_mail").val("");
	}
	function telCheck(tel) {
		var pattern = /^(13[0-9]|14[0-9]|15[0-9]|18[0-9])\d{8}$/;
		if (!pattern.test(tel)) {
			$("#error_tel").html('<label for="company_registered_capital" class="error">请输入正确的手机号码</label>');
			return false;
		}else{
			$("#error_tel").html("");
			return true;
		}
	}
	function telFocus(){
		$("#error_tel").val("");
	}
	function contentCheck(content) {
		var pattern =/^[a-zA-Z0-9\u4e00-\u9fa5\_]+$/;
		if (!pattern.test(content)) {
			$("#error_content").html('<label for="company_registered_capital" class="error">不能包含敏感字符</label>');
			return false;
		}else{
			$("#error_content").html("");
			return true;
		}
	}
	function contentFocus(){
		$("#error_content").val("");
	}
</script>
<script type="text/javascript">
function check(){
	if(!nameCheck($("#name").val())){
		return false;
	}
	if(!emailCheck($("#email").val())){
		return false;
	}
	if(!telCheck($("#tel").val())){
		return false;
	}

    if($('#content').val().length <= 0){
        alert('请填写内容！');
        $('#content').focus();
        return false;
    }
    return true;
}
</script>