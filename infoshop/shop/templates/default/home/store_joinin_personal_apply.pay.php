<?php defined('CorShop') or exit('Access Invalid!'); ?>
<script type="text/javascript"
        src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery-ui/i18n/zh-CN.js"
        charset="utf-8"></script>
<script type="text/javascript"
        src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery.nyroModal/custom.min.js"
        charset="utf-8"></script>
<link
    href="<?php echo RESOURCE_SITE_URL; ?>/js/jquery.nyroModal/styles/nyroModal.css"
    rel="stylesheet" type="text/css" id="cssfile2"/>
<script type="text/javascript">
    String.prototype.endWith = function (endStr) {
        var d = this.length - endStr.length;
        return (d >= 0 && this.lastIndexOf(endStr) == d)
    }
    //下载word文件
    function downloadFile(url) {
        $("#error_download").html('');
        if (url.trim().endWith("/")) {
            $("#error_download").html('<label for="company_registered_capital" class="error">暂无可下载的合同模板</label>');
            return;
        } else {
            $("#error_download").html('');
            window.open(url, '_blank');
        }
    }
    $(document).ready(function () {
        $('a[nctype="nyroModal"]').nyroModal();

        $('#form_paying_money_certificate').validate({
            errorPlacement: function (error, element) {
                element.nextAll('span').first().after(error);
            },
            rules: {
                paying_money_certificate: {
                    required: true,
                    accept: "gif|jpg|png|bmp|gif"
                },
                paying_money_certificate_explain: {
                    maxlength: 100
                },
                agreement_name: {
                    required: true,
                    accept: "pdf|doc|docx"
                }
            },
            messages: {
                paying_money_certificate: {
                    required: '请上传图片格式（png,jpg,jpeg,bmp,gif等）文件',
                    accept: "请上传图片格式（png,jpg,jpeg,bmp,gif等）文件"
                },
                paying_money_certificate_explain: {
                    maxlength: jQuery.validator.format("最多{0}个字")
                },
                agreement_name: {
                    required: '请上传文件大小20M以下PDF或doc格式文档',
                    accept: "请上传文件大小20M以下PDF或doc格式文档"
                }
            }
        });

        $('#btn_paying_money_certificate').on('click', function () {
            $('#form_paying_money_certificate').submit();
        });

    });
</script>
<link rel="stylesheet" type="text/css"
      href="<?php echo RESOURCE_SITE_URL; ?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"/>
<div class="joinin-pay">
    <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
        <thead>
        <tr>
            <th colspan="6">店铺及法人信息</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <th>店铺名称：</th>
            <td colspan="20"><?php echo $output['joinin_detail']['company_name']; ?></td>
        </tr>
        <tr>
            <th>所在地：</th>
            <td><?php echo $output['joinin_detail']['company_address']; ?></td>
        </tr>
        <tr>
            <th>详细地址：</th>
            <td colspan="20"><?php echo $output['joinin_detail']['company_address_detail']; ?></td>
        </tr>
        <tr>
            <th>公司电话：</th>
            <td><?php echo $output['joinin_detail']['company_phone']; ?></td>
        </tr>
        <tr>
            <th>联系电话：</th>
            <td><?php echo $output['joinin_detail']['contacts_phone']; ?></td>
        </tr>
        <tr>
            <th>电子邮箱：</th>
            <td><?php echo $output['joinin_detail']['contacts_email']; ?></td>
        </tr>
        </tbody>
    </table>
    <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
        <thead>
        <tr>
            <th colspan="2">身份证信息</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <th>法人姓名：</th>
            <td><?php echo $output['joinin_detail']['contacts_name']; ?></td>
        </tr>
        <tr>
        </tr>
        <tr>
            <th>法人身份证号：</th>
            <td><?php echo $output['joinin_detail']['idcard_number']; ?></td>
        </tr>
        <tr>
        </tr>
        <tr>
            <th>法人身份证<br>电子版：
            </th>
            <td colspan="20"><a nctype="nyroModal"
                                href="<?php echo getStoreJoininImageUrl($output['joinin_detail']['idcard_electronic']); ?>">
                    <img
                        src="<?php echo getStoreJoininImageUrl($output['joinin_detail']['idcard_electronic']); ?>"
                        alt=""/>
                </a></td>
        </tr>
        </tbody>
    </table>


    <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
        <thead>
        <tr>
            <th colspan="2">结算（支付宝）账号信息：</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <th>支付宝帐户名：</th>
            <td><?php echo $output['joinin_detail']['settlement_bank_account_name']; ?></td>
        </tr>
        <tr>
            <th>支付宝账号：</th>
            <td><?php echo $output['joinin_detail']['settlement_bank_account_number']; ?></td>
        </tr>
        </tbody>

    </table>

    <form id="form_paying_money_certificate"
          action="index.php?act=store_joinin_personal&op=pay_save" method="post"
          enctype="multipart/form-data">
        <input id="verify_type" name="verify_type" type="hidden"/> <input
            name="member_id" type="hidden"
            value="<?php echo $output['joinin_detail']['member_id']; ?>"/>
        <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
            <thead>
            <tr>
                <th colspan="2">店铺经营信息</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <th>卖家帐号：</th>
                <td><?php echo $output['joinin_detail']['seller_name']; ?></td>
            </tr>
            <tr>
                <th>店铺名称：</th>
                <td><?php echo $output['joinin_detail']['store_name']; ?></td>
            </tr>
            <tr>
                <th>店铺等级：</th>
                <td><?php echo $output['joinin_detail']['sg_name']; ?></td>
            </tr>
            <tr>
                <th>店铺分类：</th>
                <td><?php echo $output['joinin_detail']['sc_name']; ?></td>
            </tr>
            <tr>
                <th>经营类目：</th>
                <td>
                    <table border="0" cellpadding="0" cellspacing="0"
                           id="table_category" class="type">
                        <thead>
                        <tr>
                            <th>分类1</th>
                            <th>分类2</th>
                            <th>分类3</th>
                            <th>比例</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $store_class_names = unserialize($output['joinin_detail']['store_class_names']); ?>
                        <?php $store_class_commis_rates = explode(',', $output['joinin_detail']['store_class_commis_rates']); ?>
                        <?php if (!empty($store_class_names) && is_array($store_class_names)) { ?>
                            <?php for ($i = 0, $length = count($store_class_names); $i < $length; $i++) { ?>
                                <?php list($class1, $class2, $class3) = explode(',', $store_class_names[$i]); ?>
                                <tr>
                                    <td><?php echo $class1; ?></td>
                                    <td><?php echo $class2; ?></td>
                                    <td><?php echo $class3; ?></td>
                                    <td><?php echo $store_class_commis_rates[$i]; ?>%</td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <th>审核意见：</th>
                <td colspan="2"><?php echo $output['joinin_detail']['joinin_message']; ?></td>
            </tr>
            </tbody>
        </table>
        <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
            <tbody>
            <tr>
                <th>上传付款凭证：</th>
                <td><input name="paying_money_certificate" type="file"/><span></span>
                </td>
            </tr>
            <tr>
                <th>备注：</th>
                <td><textarea name="paying_money_certificate_explain" rows="10"
                              cols="30"></textarea> <span></span></td>
            </tr>
            </tbody>
        </table>
        <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
            <tbody>
            <tr>
                <th>下载合同模板：</th>
                <td><input type="button" value="下载"
                           onClick="downloadFile('<?php echo getAgreementTemplateUrl($output['file_name']); ?>')"><span
                        id="error_download"></span>
                </td>
            </tr>
            <tr>
                <th>上传合同电子档：</br>(大小不超过20M)</th>
                <td><input name="agreement_name" type="file"/><span></span>
                </td>
            </tr>
            </tbody>
        </table>
    </form>
    <div class="bottom">
        <a id="btn_paying_money_certificate" href="javascript:;" class="btn">提交</a>
    </div>
</div>
