<?php defined('CorShop') or exit('Access Invalid!');?>
<script type="text/javascript">
$(document).ready(function(){

    $('#company_address').nc_region();
    $('#business_licence_address').nc_region();
    
    $('#business_licence_start').datepicker();
    $('#business_licence_end').datepicker();

    $('#btn_apply_agreement_next').on('click', function() {
        if($('#input_apply_agreement').prop('checked')) {
            $('#apply_agreement').hide();
            $('#apply_company_info').show();
        } else {
            alert('请阅读并同意协议');
        }
    });

    $('#form_company_info').validate({
        errorPlacement: function(error, element){
            element.nextAll('span').first().after(error);
        },
        rules : {
            company_name: {
                required: true,
                maxlength: 50
            },
            company_address: {
                required: true,
                maxlength: 50
            },
            company_address_detail: {
                required: true,
                maxlength: 50
            },
            contacts_name: {
                required: true,
                maxlength: 20
            },
            idcard_electronic: {
                required: true
            },
            business_licence_number: {
                required: true,
                maxlength: 20
            },
            business_licence_address: {
                required: true,
                maxlength: 50
            },
            business_licence_start: {
                required: true
            },
            business_licence_end: {
                required: true
            },
            business_sphere: {
                required: true,
                maxlength: 500
            },
            business_licence_number_electronic: {
                required: true
            },
            tax_registration_certificate: {
                required: true,
                maxlength: 20
            },
            tax_registration_certificate_electronic: {
                required: true
            }
        },
        messages : {
            company_name: {
                required: '请输入店铺名字',
                maxlength: jQuery.validator.format("最多{0}个字")
            },
            company_address: {
                required: '请选择区域地址',
                maxlength: jQuery.validator.format("最多{0}个字")
            },
            company_address_detail: {
                required: '请输入目前详细住址或办公地址',
                maxlength: jQuery.validator.format("最多{0}个字")
            },
            contacts_name: {
                required: '请输入联系人姓名',
                maxlength: jQuery.validator.format("最多{0}个字")
            },

            idcard_electronic: {
                required: '请选择上传身份证扫描件'
            },
            business_licence_number: {
                required: '请输入营业执照号',
                maxlength: jQuery.validator.format("最多{0}个字")
            },
            business_licence_address: {
                required: '请选择营业执照所在地',
                maxlength: jQuery.validator.format("最多{0}个字")
            },
            business_licence_start: {
                required: '请选择生效日期'
            },
            business_licence_end: {
                required: '请选择结束日期'
            },
            business_sphere: {
                required: '请填写营业执照法定经营范围',
                maxlength: jQuery.validator.format("最多{0}个字")
            },
            business_licence_number_electronic: {
                required: '请选择上传营业执照电子版文件'
            },
            tax_registration_certificate: {
                required: '请输入税务登记证号',
                maxlength: jQuery.validator.format("最多{0}个字")
            },
            tax_registration_certificate_electronic: {
                required: '请选择上传税务登记证扫描件'
            }
        }
    });

    $('#btn_apply_company_next').on('click', function() {
        if(!addressCheck($("#company_address").val())){
            return;
        }
        if(!phoneCheck($("#contacts_phone").val())){
            return;
        }
        if(!emailCheck($("#contacts_email").val())){
            return;
        }
        if(!numberCheck($("#idcard_number").val())){
            return;
        }
        if(!business_licence_addressCheck($("#business_licence_address").val())){

            return;
        }
        if($('#form_company_info').valid()) {
            $('#form_company_info').submit();
        }
    });
});
</script>

<!-- 公司信息 -->

<div id="apply_company_info" class="apply-company-info">
	<div class="alert">
		<h4>注意事项：</h4>
		以下所需要上传的电子版资质文件仅支持JPG\GIF\PNG格式图片，大小请控制在1M之内。
	</div>
	<form id="form_company_info"
		action="index.php?act=store_joinin_c2c&op=step2" method="post"
		enctype="multipart/form-data">
		<table border="0" cellpadding="0" cellspacing="0" class="all">
			<thead>
				<tr>
					<th colspan="2">店铺及负责人信息</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<th><i>*</i>店铺注册名称：</th>
					<td><input name="company_name" type="text" class="w200" /> <span></span></td>
				</tr>
				<tr>
					<th><i>*</i>店铺注册地：</th>
					<td id="bank_td_address"><input id="company_address" name="company_address"
						type="hidden" value="" /> <span id="addressError"></span></td>
				</tr>
				<tr>
					<th><i>*</i>详细地址：</th>
					<td><input name="company_address_detail" type="text" class="w200">
						<span></span></td>
				</tr>
				<tr>
					<th><i>*</i>联系电话：</th>
					<td><input id="contacts_phone" name="contacts_phone" type="text" class="w100"
                               onblur="phoneCheck(this.value)"
                               onfocus="phoneFocus();"/> <span id="error_phone"></span></td>
				</tr>
				<tr>
					<th><i>*</i>电子邮箱：</th>
					<td><input id="contacts_email"name=contacts_email" type="text" class="w200"
                               onblur="emailCheck(this.value)"
                               onfocus="emailFocus();"/> <span id="error_mail" ></span></td>
				</tr>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="20">&nbsp;</td>
				</tr>
			</tfoot>
		</table>
		<table border="0" cellpadding="0" cellspacing="0" class="all">
			<thead>
				<tr>
					<th colspan="20">身份证信息</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<th><i>*</i>负责人姓名：</th>
					<td><input name="contacts_name" type="text" class="w100" /> <span>请与身份证保持一致</span></td>
				</tr>
				<tr>
					<th><i>*</i>负责人身份证号：</th>
					<td><input id="idcard_number" name="idcard_number" type="text" class="w200" onblur="numberCheck(this.value)"
                               onfocus="numberFocus();"/> <span id="error_number">（请与身份证保持一致）</span>
                        </td>
				</tr>
				<tr>
					<th><i>*</i>负责人身份证电子版：</th>
					<td><input name="idcard_electronic" type="file" class="w200" /> <span
						class="block">请确保图片清晰，身份证上文字可辨（清晰照片也可使用）。</span></td>
				</tr>
				<tr>
					<th><i>*</i>身份证数码照示例：</th>
					<td><a href="/shop/templates/default/images/idcard.jpg"
						target="_blank"><img
							src="/shop/templates/default/images/idcard.jpg" width="300"></a></td>
				</tr>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="20">&nbsp;</td>
				</tr>
			</tfoot>
		</table>
		<table border="0" cellpadding="0" cellspacing="0" class="all">
			<thead>
				<tr>
					<th colspan="20">营业执照信息（副本）</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<th><i>*</i>营业执照号：</th>
					<td><input name="business_licence_number" type="text" class="w200" />
						<span></span></td>
				</tr>
				<tr>
					<th><i>*</i>营业执照所在地：</th>
					<td id="localaddress"><input id="business_licence_address"
						name="business_licence_address" type="hidden" /> <span id="error_business_licence_address"></span></td>
				</tr>
				<tr>
					<th><i>*</i>营业执照有效期：</th>
					<td><input id="business_licence_start"
						name="business_licence_start" type="text" class="w90" /> <span></span>-
						<input id="business_licence_end" name="business_licence_end"
						type="text" class="w90" onchange="endThanStart();"/> <span span id="error_end"></span></td>
				</tr>
				<tr>
					<th><i>*</i>法定经营范围：</th>
					<td><textarea name="business_sphere" rows="3" class="w200"></textarea>
						<span></span></td>
				</tr>
				<tr>
					<th><i>*</i>营业执照电子版：</th>
					<td><input name="business_licence_number_electronic" type="file"
						class="w200" /> <span class="block">请确保图片清晰，文字可辨并有清晰的红色公章。</span></td>
				</tr>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="20">&nbsp;</td>
				</tr>
			</tfoot>
		</table>
		<table border="0" cellpadding="0" cellspacing="0" class="all">
			<thead>
				<tr>
					<th colspan="20">税务登记信息</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<th><i>*</i>税务登记证号：</th>
					<td><input name="tax_registration_certificate" type="text"
						class="w200" /> <span></span></td>
				</tr>
				<tr>
					<th><i>*</i>税务登记证电子版：</th>
					<td><input name="tax_registration_certificate_electronic"
						type="file" /> <span class="block">请确保图片清晰，文字可辨并有清晰的红色公章。</span></td>
				</tr>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="20">&nbsp;</td>
				</tr>
			</tfoot>
		</table>
	</form>
	<div class="bottom">
		<a id="btn_apply_company_next" href="javascript:;" class="btn">下一步，提交财务资质信息</a>
	</div>
</div>
<script>
    function check(str){
        var temp=""
        for(var i=0;i<str.length;i++)
            if(str.charCodeAt(i)>0&&str.charCodeAt(i)<255)
                temp+=str.charAt(i)
        return temp
    }
    function emailCheck(email) {
        var pattern = /^(\w)+(\.\w+)*@(\w)+((\.\w+)+)$/;
        if (!pattern.test(email)) {
            $("#error_mail").html('<label for="company_registered_capital" class="error">请输入正确的邮箱</label>');
        }else{
            $("#error_mail").html("");
            return true;
        }
    }
    function emailFocus(){
        $("#error_mail").val("");
    }
    function phoneCheck(phone) {
        var pattern = /^(13[0-9]|14[0-9]|15[0-9]|18[0-9])\d{8}$/
        if (!pattern.test(phone)) {
            $("#error_phone").html('<label for="company_registered_capital" class="error">请输入正确的手机号码</label>');
            return false;
        }else{
            $("#error_phone").html("");
            return true;
        }
    }
    function phoneFocus(){
        $("#error_phone").val("");
    }
    function numberCheck(number) {
        var pattern = /(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/
        if (!pattern.test(number)) {
            $("#error_number").html('<label for="company_registered_capital" class="error">请输入正确的身份证</label>');
            return false;
        }else{
            $("#error_number").html("");
            return true;
        }
    }
    function numberFocus(){
        $("#error_number").val("");
    }
    function addressCheck(address){

        if($("#bank_td_address select").size()==1){
            $("#addressError").html('<label for="company_registered_capital" class="error">请选择所在地</label>');
            return false;

        }else{
            if(address.length<10){
                $("#addressError").html('<label for="company_registered_capital" class="error">请继续选择</label>');
                return false;
            }else{
                if(address.indexOf("请选择")!=-1){
                    $("#addressError").html('<label for="company_registered_capital" class="error">请继续选择</label>');
                    return false;
                }else{
                    $("#addressError").html('');
                    return true;
                }
            }
        }

    }
    function addressFocus(){
        $("#error_address").val("");
    }
    function business_licence_addressCheck(business_address){
        if($("#localaddress select").size()==1){
            $("#error_business_licence_address").html('<label for="company_registered_capital" class="error">请选择所在地</label>');
            return false;
        }else{
            if(business_address.length<10){
                $("#error_business_licence_address").html('<label for="company_registered_capital" class="error">请继续选择</label>');
                return false;
            }else{
                if(business_address.indexOf("请选择")!=-1){
                    $("#error_business_licence_address").html('<label for="company_registered_capital" class="error">请继续选择</label>');
                    return false;
                }else{
                    $("#error_business_licence_address").html('');
                    return true;
                }
            }
        }

    }
    function business_licence_addressFocus(){
        $("#error_business_licence_address").val("");
    }
    function endThanStart(){
        var startTime=$("#business_licence_start").val();
        var endTime=$("#business_licence_end").val();
        var startdate = new Date((startTime).replace(/-/g,"/"));
        var enddate = new Date((endTime).replace(/-/g,"/"));
        if(enddate < startdate)
        {
            $("#error_end").html('<label for="company_registered_capital" class="error">请输入正确的有效期</label>');
            return false;
        }
        else
        {
            $("#error_end").html("");
            return true;
        }
    }
</script>
