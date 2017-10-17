<?php defined('CorShop') or exit('Access Invalid!');?>
<script type="text/javascript">
$(function(){
    $.fn.valueList = function(options) {
        $.fn.valueList.deflunt = {
            box: '',
            list: 0,
            name: ''
        };
        return this.each(function() {
            var opts = $.extend({},
            $.fn.valueList.deflunt, options);
            var addBox = opts.addBox;
            var addButton = $(opts.add);
            var title = opts.title;
            var box = $(opts.box);
            var list = box.find(opts.list);
            var id = opts.id;
            var num = opts.num;
            var fieldHtml = $(addBox).html();
            var index = list.size();
            
            //默认隐藏头尾操作
            op();
            
            //绑定添加事件
            addButton.click(add);
            
            //绑定操作事件
            list.find('.up').live('click', up);
            list.find('.down').live('click', down);
            list.find('.del').live('click', del);
            
            function re(){
                list = box.find(opts.list);
            }
            
            function add() {
                box.append(fieldHtml);
                op();
                index++;
                $.each(id, function(key, value) {
                    var newobj = list.last();
                    var val = value.val.replace('[i]', index)
                    if (value.type == 'html') {
                        newobj.find(value.obj).html(val);
                    } else {
                        newobj.find(value.obj).attr(value.type, val);
                    }
                });
            }
            function up() {
                var o = $(this).parents(opts.list);
                var n = o.prev();
                n.before(o.clone());
                o.remove();
                op();
            }
            function down() {
                var o = $(this).parents(opts.list);
                var n = o.next();
                n.after(o.clone());
                o.remove();
                op();
            }
            function del() {
                $(this).parents(opts.list).remove();
                op();
            }
            function op() {
                re();
                list.each(function(i) {
                    var obj = $(this);
                    $.each(num, function(key, value) {
                        var val = value.val.replace('[i]', i + 1)
                        if (value.type == 'html') {
                            obj.find(value.obj).html(val);
                        } else {
                            obj.find(value.obj).attr(value.type, val);
                        }
                    });
                });
                box.find('a').removeClass('disable');
                box.find('a.up:first,a.down:last').addClass('disable');
            }
        });
    }
});
</script>
<style type="text/css">
#fieldBox {
	padding: 0 0 20px 0;
}

#fieldBox li {
	float: left;
	width: 100%;
	padding: 5px 0;
}

#fieldBox li input {
	vertical-align: middle;
}

#fieldBox li a {
	margin-right: 10px;
	color: #0080FF;
}

#fieldBox li a.disable {
	color: #666;
}

#addBox {
	display: none;
}

#addButton {
	margin-top: 10px;
	margin-bottom: 20px;
}
</style>
<div class="page">
	<div class="fixed-bar">
		<div class="item-title">
			<h3>新增投票</h3>
			<ul class="tab-base">
				<li><a href="index.php?act=life_vote&op=list"><span>管理</span></a></li>
				<li><a href="JavaScript:void(0);" class="current"><span>新增</span></a></li>
			</ul>
		</div>
	</div>
	<div class="fixed-empty"></div>
	<form id="form" method="post" name="articleForm">
		<input type="hidden" name="form_submit" value="ok" />
		<table class="table tb-type2 nobdb">
			<tbody>
				<tr class="noborder">
					<td colspan="2" class="required"><label class="validation">投票:</label></td>
				</tr>
				<tr class="noborder">
					<td class="vatop rowform"><input type="text" value="" name="title"
						id="ask_title" class="txt"></td>
					<td class="vatop tips"></td>
				</tr>
				<tr>
					<td colspan="2" class="required"><label>显示:</label></td>
				</tr>
				<tr class="noborder">
					<td class="vatop rowform onoff"><label for="is_show1"
						class="cb-enable selected"><span><?php echo '是';?></span></label>
						<label for="is_show0" class="cb-disable"><span><?php echo '否';?></span></label>
						<input id="is_show1" name="is_show" checked="checked" value="1"
						type="radio"> <input id="is_show0" name="is_show" value="0"
						type="radio"></td>
					<td class="vatop tips"></td>
				</tr>
				<tr>
					<td colspan="2" class="required">排序:
				
				</tr>
				<tr class="noborder">
					<td class="vatop rowform"><input type="text" value="255"
						name="sort" id="sort" class="txt"></td>
					<td class="vatop tips"></td>
				</tr>
				<tr>
					<td colspan="2" class="required"><label class="validation">投票项目:</label></td>
				</tr>
				<tr class="noborder">
					<td colspan="2" class="vatop rowform">
						<ul id="fieldBox"></ul>
						<div id="addBox">
							<li class="fieldItem"><span class="t"></span>：<input
								name="item[]" type="text" class="text" style="width: 300px;"> <a
								href="javascript:;" class="up">上移</a><a href="javascript:;"
								class="down">下移</a><a href="javascript:;" class="del">删除</a> <input
								type="hidden" name="s[]" class="s"></li>
						</div> <a href="JavaScript:void(0);" class="btn" id="addButton"><span>添加</span></a>
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
<script type="text/javascript">
$(function(){
    $('#fieldBox').valueList({
        addBox:'#addBox',
        add:'#addButton',
        box:'#fieldBox',
        list:'.fieldItem',
        num:[
            {obj: '.t', type: 'html', val: '投票项目[i]'},
            {obj: '.s', type: 'value', val: '[i]'}
        ],
        id:[
        ],
        name:['fieldName']
    });
});
</script>
<script>
//按钮先执行验证再提交表单
$(function(){
    $("#submitBtn").click(function(){
        if($("#form").valid()){
            $("#form").submit();
        }
    });
});
$(document).ready(function(){
	$('#form').validate({
        errorPlacement: function(error, element){
			error.appendTo(element.parent().parent().prev().find('td:first'));
        },
        rules : {
            title : {
                required   : true
            },
			content : {
                required   : true
            },
			tel : {
                required   : true
            },
            sort : {
                number   : true
            }
        },
        messages : {
            title : {
                required   : '标题不能为空'
            },
			content : {
                required   : '主营内容不能为空'
            },
            tel : {
                required   : '联系电话不能为空'
            },
            sort  : {
                number   : '投票排序仅能为数字'
            }
        }
    });
});
</script>