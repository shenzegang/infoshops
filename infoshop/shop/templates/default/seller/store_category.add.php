<div class="eject_con">
    <div id="warning" class="alert alert-error"></div>
    <form method="post" target="_parent"
          action="index.php?act=store_apply_business&op=category_apply"
          enctype="multipart/form-data" id="category_apply_form">
        <input type="hidden" name="form_submit" value="ok"/>
        <input type="hidden" name="brand_id" value="<?php echo $output['brand_array']['brand_id']; ?>"/>
        <input id="goods_class" name="goods_class" type="hidden" value=""/>
        <dl>
            <dt><?php echo $lang['store_apply_business_category_name'] . $lang['nc_colon']; ?></dt>
            <dd id="gcategory">
                <select name="category_add">
                    <option value="0"><?php echo $lang['nc_please_choose']; ?></option>
                    <?php if (!empty($output['gc_list'])) { ?>
                        <?php foreach ($output['gc_list'] as $k => $v) { ?>
                            <option value="<?php echo $v['gc_id']; ?>"><?php echo $v['gc_name']; ?></option>
                        <?php } ?>
                    <?php } ?>
                </select><span id="error_message" style="color: red;"></span></td>
                <td class="vatop tips">
            </dd>
        </dl>

        <div class="bottom">
            <label class="submit-border"><input type="button" id="btn_add_category" class="submit"
                                                value="<?php echo $lang['nc_submit']; ?>"/></label>
        </div>
    </form>
</div>
<script>
    $(function () {
        $.getScript('<?php echo RESOURCE_SITE_URL;?>/js/common_select.js', function () {
            gcategoryInit('gcategory');
        });
        // 提交新添加的类目
        $('#btn_add_category').on('click', function() {
            $('#error_message').hide();
            $('#error_message1').hide();
            var category_id = '';
            var validation = true;
            $('#gcategory').find('select').each(function() {
                if(parseInt($(this).val(), 10) > 0) {
                    category_id += $(this).val() + ',';
                } else {
                    validation = false;
                }
            });
            if(!validation) {
                $('#error_message').text('请选择分类');
                $('#error_message').show();
                return false;
            }

            $('#goods_class').val(category_id);
            $('#category_apply_form').submit();
        });


    });

</script>
