<?php defined('CorShop') or exit('Access Invalid!'); ?>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3><?php echo $lang['store_category_apply_check']; ?></h3>
        </div>
    </div>
    <div class="fixed-empty"></div>
    <table class="table tb-type2">
        <thead>
        <tr class="thead">
            <th style="width: 30px;">分类1</th>
            <th style="width: 30px;">分类2</th>
            <th style="width: 30px;">分类3</th>
            <th style="width: 300px;">分佣比例</th>
            <th style="width: 30px;">店铺名称</th>
            <th style="width: 30px;"><?php echo $lang['nc_handle']; ?></th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($output['store_bind_class_list']) && is_array($output['store_bind_class_list'])) { ?>
            <?php foreach ($output['store_bind_class_list'] as $key => $value) { ?>
                <tr class="hover edit">
                    <td><?php echo $value['class_1_name']; ?></td>
                    <td><?php echo $value['class_2_name']; ?></td>
                    <td><?php echo $value['class_3_name']; ?></td>
                    <td class="sort">
                        <span id="commis_rate" nc_type="commis_rate" column_id="<?php echo $value['bid']; ?>"
                              title="<?php echo $lang['nc_editable']; ?>" class="editable " style="vertical-align: middle; margin-right: 4px;"><?php echo $value['commis_rate']; ?></span>%
                    </td>
                    <td><?php echo $value['store_name']; ?></td>
                    <td><a nctype="btn_category_success" href="javascript:;"
                           data-bid="<?php echo $value['bid']; ?>">通过</a>
                        <a nctype="btn_category_falid" href="javascript:;"
                           data-bid="<?php echo $value['bid']; ?>">|拒绝</a></td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr class="no_data">
                <td colspan="10"><?php echo $lang['nc_no_record']; ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

    <form id="add_form"
          action="<?php echo urlAdmin('store', 'category_update');?>"
          method="post">
         <input id="bid" name="bid" type="hidden" value="">
         <input id="status" name="status" type="hidden" value="">
    </form>

</div>
<script type="text/javascript"
        src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery.edit.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/common_select.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/dialog/dialog.js" id="dialog_js" charset="utf-8"></script>
<script type="text/javascript">
    $(document).ready(function () {
        // 审核分类成功
        $('[nctype="btn_category_success"]').on('click', function () {
            if (confirm('是否确认审核？')) {
                var bid = $(this).attr('data-bid');
                $this = $(this);
                var  commis=$("#commis_rate").text();
                var commis_rate = parseInt(commis, 10);
                if(isNaN(commis_rate) || commis_rate < 0 || commis_rate > 100||commis_rate == 0) {
                    showDialog("请填写正确的分佣比例！","error","","","","","","","",2);
                    return;
                }
                $("#bid").val(bid);
                $("#status").val(0);
                $('#add_form').submit();
            }
        });

        // 审核分类拒绝
        $('[nctype="btn_category_falid"]').on('click', function () {
            if (confirm('是否确认拒绝？')) {
                var bid = $(this).attr('data-bid');
                $this = $(this);
                $("#bid").val(bid);
                $("#status").val(2);
                $('#add_form').submit();
            }
        });

        // 修改分佣比例
        $('span[nc_type="commis_rate"]').inline_edit({act: 'store', op: 'store_bind_class_update'});
    });
</script>
