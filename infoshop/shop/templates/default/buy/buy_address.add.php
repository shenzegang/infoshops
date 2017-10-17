<?php defined('CorShop') or exit('Access Invalid!');?>
<div class="ncc-form-default">
	<form method="POST" id="addr_form" action="index.php">
		<input type="hidden" value="buy" name="act"> <input type="hidden"
			value="add_addr" name="op"> <input type="hidden" name="form_submit"
			value="ok" />
		<dl>
			<dt>
				<i class="required">*</i><?php echo $lang['cart_step1_input_true_name'].$lang['nc_colon'];?></dt>
			<dd>
				<input type="text" class="text w100" name="true_name" maxlength="20"
					id="true_name" value="" />
			</dd>
		</dl>
		<dl>
			<dt>
				<i class="required">*</i><?php echo $lang['cart_step1_area'].$lang['nc_colon'];?></dt>
			<dd>
				<div id="region">
					<select class="w110">
					</select> <input type="hidden" value="" name="city_id" id="city_id">
					<input type="hidden" name="area_id" id="area_id" class="area_ids" />
					<input type="hidden" name="area_info" id="area_info"
						class="area_names" />
				</div>
			</dd>
		</dl>
		<dl>
			<dt>
				<i class="required">*</i><?php echo $lang['cart_step1_whole_address'].$lang['nc_colon'];?></dt>
			<dd>
				<input type="text" class="text w500" name="address" id="address"
					maxlength="80" value="" />
				<p class="hint"><?php echo $lang['cart_step1_true_address'];?></p>
			</dd>
		</dl>
		<dl>
			<dt>
				<i class="required">*</i><?php echo $lang['cart_step1_mobile_num'].$lang['nc_colon'];?></dt>
			<dd><input name="mobile_num" id="mobile_num" type="text" class="text w200" maxlength="15" value=""
                       onblur="numCheck(this.value)" onfocus="numFocus();"/><span id="error_num"></span>
        &nbsp;&nbsp;(或)&nbsp;<?php echo $lang['cart_step1_phone_num'].$lang['nc_colon'];?>
        <input name="tel_phone" id="tel_phone" type="text" class="text w100"  maxlength="20" value=""
                     onblur="phoneCheck(this.value)" onfocus="phoneFocus();"/> <span id="error_phone"></span>
			</dd>
		</dl>
	</form>
</div>

<script type="text/javascript">
$(document).ready(function(){
	regionInit("region");
    $('#addr_form').validate({
        rules : {
            true_name : {
                required : true
            },
            area_id : {
                required : true,
                min   : 1,
                checkarea:true
            },
            address : {
                required : true
            },

        },
        messages : {
            true_name : {
                required : '<i class="icon-exclamation-sign"></i><?php echo $lang['cart_step1_input_receiver'];?>'
            },
            area_id : {
                required : '<i class="icon-exclamation-sign"></i><?php echo $lang['cart_step1_choose_area'];?>',
                min  : '<i class="icon-exclamation-sign"></i><?php echo $lang['cart_step1_choose_area'];?>',
                checkarea : '<i class="icon-exclamation-sign"></i><?php echo $lang['cart_step1_choose_area'];?>'
            },
            address : {
                required : '<i class="icon-exclamation-sign"></i><?php echo $lang['cart_step1_input_address'];?>'
            },

        },
        groups : {
            phone:'mobile_num tel_phone'
        }
    });
});
function checkPhone(){
    return ($('input[name="mob_phone"]').val() == '' && $('input[name="tel_phone"]').val() == '');
}
function submitAddAddr(){
    if ($('#addr_form').valid()){
        $('#buy_city_id').val($('#region').find('select').eq(1).val());
        $('#city_id').val($('#region').find('select').eq(1).val());
        var datas=$('#addr_form').serialize();
        $("#error_phone").html("");
        if($.trim($("#mobile_num").val()) == "" && $.trim($("#tel_phone").val()) == "") {
            $("#error_phone").html('<cite style="color: red">手机号码和固定号码有一项不能为空</cite>');
            return false;
        }
        else{
            var flag1 = numCheck($("#mobile_num").val());
            var flag2 = phoneCheck($("#tel_phone").val());
            if(!flag1){
                $("#error_num").html('<cite style="color: red">请输入正确的手机号</cite>');
                return false;
            }
            if(!flag2){
                $("#error_phone").html('<cite style="color: red">请输入正确的座机号</cite>')
                return false;
            }
            $("#error_phone").html("");
            $.post('index.php', datas, function (data) {
                if (data.state) {
                    var true_name = $.trim($("#true_name").val());
                    var tel_phone = $.trim($("#tel_phone").val());
                    var mobile_num = $.trim($("#mobile_num").val());
                    var area_info = $.trim($("#area_info").val());
                    var address = $.trim($("#address").val());
                    showShippingPrice($('#city_id').val(), $('#area_id').val());
                    hideAddrList(data.addr_id, true_name, area_info + '&nbsp;&nbsp;' + address, (mobile_num != '' ? mobile_num : tel_phone));
                } else {
                    alert(data.msg);
                }
            }, 'json');
        }
    }else{
        return false;
    }
}
function numCheck(num) {
    var pattern = /^(13[0-9]|14[0-9]|15[0-9]|18[0-9])\d{8}$/
    if ($.trim(num)!=""&&(!pattern.test(num))) {
        $("#error_num").html('<cite style="color: red">请输入正确的手机号</cite>');
        return false;
    }else{
        $("#error_num").html("");
        $("#error_phone").html("");
        return true;
    }
}
function numFocus(){
    $("#error_num").val("");
    $("#error_phone").val("");
}
function phoneCheck(phone) {
    var pattern = /^((0\d{2,3})-)(\d{7,8})(-(\d{3,}))?$/
    if ($.trim(phone)!=""&&(!pattern.test(phone))) {
        $("#error_phone").html('<cite style="color: red">请输入正确的座机号</cite>')
        return false;
    }else{
        $("#error_num").html("");
        $("#error_phone").html("");
        return true;
    }
}
function phoneFocus(){
    $("#error_num").val("");
    $("#error_phone").val("");
}

</script>