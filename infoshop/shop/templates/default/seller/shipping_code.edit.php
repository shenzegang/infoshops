<div class="eject_con">
    <div id="warning" class="alert alert-error"></div>
    <form method="post" target="_parent"
          action="index.php?act=store_deliver&op=shipping_code_edit&order_id=<?php echo $output['order_id'];?>&order_sn=<?php echo $output['order_sn'];?>"
          enctype="multipart/form-data" id="shipping_code_form">
        <dl>
            <dt><?php echo $lang['member_show_order_shipping_no']; ?>：</dt>
            <dd>
                <input id="shipping_code" name="shipping_code" value="">
            </dd>
        </dl>

        <div class="bottom">
            <label class="submit-border"><input type="button" id="btn_shipping_code" class="submit"
                                                value="<?php echo $lang['nc_submit']; ?>"/></label>
        </div>
    </form>
</div>
<script>
    //按钮先执行验证再提交表单
    $(function(){$("#btn_shipping_code").click(function(){
        if($("#shipping_code_form").valid()){
            $("#shipping_code_form").submit();
        }
    });
    });
    //
    $(document).ready(function(){
        $('#shipping_code_form').validate({
            errorPlacement: function(error, element){
                var error_td = element.parent('dd');
                error_td.find('label').hide();
                error_td.append(error);
            },

            rules : {
                shipping_code : {
                    required  : true
                }
            },
            messages : {
                shipping_code : {
                    required : '<?php echo $lang['member_show_express_ship_code_null'];?>'
                }
            }
        });
    });
</script>

