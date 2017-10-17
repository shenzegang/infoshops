<?php defined('CorShop') or exit('Access Invalid!'); ?>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3><?php echo $lang['store_agreement_check']; ?></h3>
        </div>
    </div>
    <div class="fixed-empty"></div>
    <table class="table tb-type2">
        <thead>
        <tr class="thead">
            <th>合同类型</th>
            <th>名称</th>
            <th>下载</th>
            <th>上传(不超过20M的word格式文件)</th>
        </tr>
        </thead>
        <tbody>
        <!-- 企业-->
        <form id="fileForm_0"
              action="index.php?act=store&op=upload_agreement_template" method="post"
              enctype="multipart/form-data">
            <tr class="hover edit">
                <td style="width: 50px;">企业</td>
                <td style="width: 200px;"><?php echo $output['agreement_template_0']['file_name']; ?></td>
                <td style="width: 100px;">
                    <?php if( $output['agreement_template_0'] != null){?>
                    <input type="button" value="下载"
                           onClick="downloadFile('<?php echo getAgreementTemplateUrl($output['agreement_template_0']['file_name']); ?>')">
                    <?php }?>
                    <input name="type" type="hidden" value="0"/>
                </td>
                <td style="width: 200px;"><input id="file_0" name="file_name" type="file"
                           onchange="change(0)"/><input
                        id="upload_0" type="button" value="上传" hidden="hidden"
                        onClick="upload(0)"/><span id="error_0"></span></td>
            </tr>
        </form>
        <!-- 个体户-->
        <form id="fileForm_1"
              action="index.php?act=store&op=upload_agreement_template" method="post"
              enctype="multipart/form-data">
            <tr class="hover edit">
                <td style="width: 50px;">个体户</td>
                <td style="width: 200px;"><?php echo $output['agreement_template_1']['file_name']; ?></td>
                <td style="width: 100px;">
                    <?php if( $output['agreement_template_1'] != null){?>
                    <input type="button" value="下载"
                           onClick="downloadFile('<?php echo getAgreementTemplateUrl($output['agreement_template_1']['file_name']); ?>')">
                    <?php }?>
                    <input name="type" type="hidden" value="1"/>
                </td>
                <td style="width: 200px;"><input id="file_1" name="file_name" type="file"
                           onchange="change(1)"/><input
                        id="upload_1" type="button" value="上传" hidden="hidden"
                        onClick="upload(1)"/><span id="error_1"></span></td>
            </tr>
        </form>
        <!-- 个人-->
        <form id="fileForm_2"
              action="index.php?act=store&op=upload_agreement_template" method="post"
              enctype="multipart/form-data">
            <tr class="hover edit">
                <td style="width: 50px;">个人</td>
                <td style="width: 200px;"><?php echo $output['agreement_template_2']['file_name']; ?></td>
                <td style="width: 100px;">
                    <?php if( $output['agreement_template_2'] != null){?>
                    <input type="button" value="下载"
                           onClick="downloadFile('<?php echo getAgreementTemplateUrl($output['agreement_template_2']['file_name']); ?>')">
                    <?php }?>
                    <input name="type" type="hidden" value="2"/>
                </td>
                <td style="width: 200px;"><input id="file_2" name="file_name" type="file"
                           onchange="change(2)"/><input
                        id="upload_2" type="button" value="上传" hidden="hidden"
                        onClick="upload(2)"/><span  id="error_2"></span></td>
            </tr>
        </form>

        </tbody>
    </table>

</div>
<script type="text/javascript"
        src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery.edit.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/common_select.js" charset="utf-8"></script>
<script type="text/javascript"
        src="<?php echo RESOURCE_SITE_URL;?>/js/dialog/dialog.js"
        id="dialog_js" charset="utf-8"></script>
<script type="text/javascript">
    String.prototype.endWith=function(endStr){
        var d=this.length-endStr.length;
        return (d>=0&&this.lastIndexOf(endStr)==d)
    }
    //下载word文件
    function downloadFile(url) {
        window.open(url, '_blank');
    }
    function change(type) {
        $("#upload_" + type).show();
    }
    $(document).ready(function(){
        $("#error_0").html('');
        $("#error_1").html('');
        $("#error_2").html('');

    });
    //上传合同模板事件
    function upload(type) {
        if(!($("#file_"+type).val().endWith("doc") ||  $("#file_"+type).val().endWith("docx"))){
            $("#error_"+type).html('<label for="company_registered_capital" class="error">请选择word格式合同模板上传</label>');
            return;
        }else{
            $("#error_"+type).html('');
        }
        showDialog('上传后若模板已存在将被替换，确定要上传吗？', 'confirm', '', function(){
            var file_name = "file_" + type;
            $('#fileForm_' + type).validate({
                errorPlacement: function(error, element){
                    element.nextAll('span').first().after(error);
                },
                rules: {
                    file_name: {
                        required: true,
                        accept:"doc|docx"
                    }
                },
                messages: {
                    file_name: {
                        required: '请选择word格式合同模板上传',
                        accept:'请选择word格式合同模板上传'
                    }
                }
            });
            $('#fileForm_'+type).submit();
        });
    }
</script>
