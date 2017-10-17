$(function(){
	var key = getcookie('key');
	
	if(key==''){
		window.location.href = WapSiteUrl+'/tmpl/member/login.html';
	}
    $(".gotop").click(function (){
        $(window).scrollTop(0);
    });
	$('#logoutbtn').click(function(){
		var username = getcookie('username');
		var key = getcookie('key');
		var client = 'wap';
		$.ajax({
			type:'get',
			url:ApiUrl+'/index.php?act=logout',
			data:{username:username,key:key,client:client},
			success:function(result){
				if(result){
					delCookie('username');
					delCookie('key');
					location.href = WapSiteUrl+'/tmpl/member/login.html';
				}
			}
		});
	});	
	
	var referurl = document.referrer;//上级网址
	$("input[name=referurl]").val(referurl);
	$.sValid.init({
        rules:{
            pay_passwd:"required"
        },
        messages:{
            pay_passwd:" 使用预存款支付，请输入支付密码！"
        },
        callback:function (eId,eMsg,eRules){
            if(eId.length >0){
                var errorHtml = "";
                $.map(eMsg,function (idx,item){
                    errorHtml += "<p>"+idx+"</p>";
                });
                $(".error-tips").html(errorHtml).show();
            }else{
                 $(".error-tips").html("").hide();
            }
        }  
    });
	$('#pay_submit').click(function(){//会员登陆
		var passwd = $('#pay_passwd').val();
		var client = 'wap';
		var pay_sn = GetQueryString("pay_sn");
		if($.sValid()){
	          $.ajax({
				type:'post',
				url:ApiUrl+"/index.php?act=member_buy&op=isValidPaypasswd",	
				data:{pay_passwd:passwd,client:client,key:key},
				dataType:'json',
				success:function(result){
					
					if(!result.datas.error){
						$("#tips").html("<p>支付密码输入正确</p>");
						$("#tips").removeClass('error-tips').addClass('succ-tips').show();
						window.location.href = ApiUrl+'/index.php?act=member_payment&op=pay&key='+key+'&pay_code=predeposit&pay_sn='+pay_sn;
					}else{
						$("#tips").html("<p>"+result.datas.error+"</p>");
						$("#tips").removeClass('succ-tips').addClass('error-tips').show();
					}
				}
			 });  
        }
	});
});