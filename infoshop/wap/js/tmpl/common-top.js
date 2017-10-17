$(function (){
    if(typeof(header_title) == 'undefined'){
        var headTitle = document.title;
    }else{
        var headTitle = header_title;
    }
	var tmpl = '<div class="header-wrap">\
	        	  <a href="javascript:history.back();" class="header-back"><span>返回</span></a>\
				    <h2>'+headTitle+'</h2>\
				    <a href="javascript:void(0)" id="btn-opera" class="i-main-opera">\
				    <span></span>\
				  </a>\
    		    </div>\
		    	<div class="main-opera-pannel">\
                  <div class="index-footer">\
                      <ul>\
                        <li><a href="/wap/"><span></span>首页</a></li>\
                        <li><a href="/wap/tmpl/product_first_categroy.html"><span></span>分类</a></li>\
                        <li><a href="/wap/tmpl/cart_list.html"><span></span>购物车</a></li>\
                        <li><a href="/wap/tmpl/member/member.html"><span></span>我的</a></li>\
                      </ul>\
                    </div>\
		    	</div>';
    //渲染页面
	var html = template.compile(tmpl);
	$("#header").html(html);
	$("#btn-opera").click(function (){
		$(".main-opera-pannel").toggle();
	});
	//当前页面
	if(headTitle == "商品分类"){
		$(".i-categroy").parent().addClass("current");
	}else if(headTitle == "购物车列表"){
		$(".i-cart").parent().addClass("current");
	}else if(headTitle == "我的商城"){
		$(".i-mine").parent().addClass("current");
	}
});