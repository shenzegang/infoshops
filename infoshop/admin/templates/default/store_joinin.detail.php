<?php defined('CorShop') or exit('Access Invalid!'); ?>
<script type="text/javascript"
        src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery.nyroModal/custom.min.js"
        charset="utf-8"></script>
<script type="text/javascript"
        src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery.poshytip.min.js"
        charset="utf-8"></script>
<link
    href="<?php echo RESOURCE_SITE_URL; ?>/js/jquery.nyroModal/styles/nyroModal.css"
    rel="stylesheet" type="text/css" id="cssfile2"/>
<script src="<?php echo RESOURCE_SITE_URL.'/js/class_area_array.js';?>"></script>
<script type="text/javascript">
    $(document).ready(function () {

        var area_name=$("#area_name").val();
        area_name=area_name.substring(0,2);
        $("#area_id").val(nc_class_a.indexOf(area_name));

        $('a[nctype="nyroModal"]').nyroModal();
        $('#btn_fail').on('click', function () {
            if ($('#joinin_message').val() == '') {
                $('#validation_message').text('请输入审核意见');
                $('#validation_message').show();
                return false;
            } else {
                $('#validation_message').hide();
            }
            if (confirm('确认拒绝申请？')) {
                $('#verify_type').val('fail');
                $('#form_store_verify').submit();
            }
        });
        $('#btn_pass').on('click', function () {
            var valid = true;
            $('[nctype="commis_rate"]').each(function (commis_rate) {
                rate = $(this).val();
                if (rate == '') {
                    valid = false;
                    return false;
                }

                var rate = Number($(this).val());
                if (isNaN(rate) || rate < 0 || rate >= 100) {
                    valid = false;
                    return false;
                }
            });
            if (valid) {
                $('#validation_message').hide();
                if (confirm('确认通过申请？')) {
                    $('#verify_type').val('pass');
                    $('#form_store_verify').submit();
                }
            } else {
                $('#validation_message').text('请正确填写分佣比例');
                $('#validation_message').show();
            }
        });
    });
    //下载pdf文件
    function downloadFile(url) {
        window.open(url,'_blank');
    }

</script>

<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3><?php echo $lang['store']; ?></h3>
            <ul class="tab-base">
                <li><a href="index.php?act=store&op=store"><span><?php echo $lang['manage']; ?></span></a></li>
                <li><a href="index.php?act=store&op=store_joinin"><span><?php echo $lang['pending']; ?></span></a></li>
                <li><a href="JavaScript:void(0);"
                       class="current"><span><?php echo $output['joinin_detail_title']; ?></span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>


    <?php if ($output['joinin_detail']['personal'] == 0) { ?>
        <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
            <thead>
            <tr>
                <th colspan="20">公司及法人信息</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <th class="w150">公司名称：</th>
                <td colspan="20"><?php echo $output['joinin_detail']['company_name']; ?></td>
            </tr>
            +
            <tr>
                <th>公司所在地：</th>
                <td><?php echo $output['joinin_detail']['company_address']; ?></td>
            </tr>
            <tr>
                <th>公司详细地址：</th>
                <td colspan="20"><?php echo $output['joinin_detail']['company_address_detail']; ?></td>
            </tr>
            <tr>
                <th>公司电话：</th>
                <td><?php echo $output['joinin_detail']['company_phone']; ?></td>
            </tr>
            <tr>
                <th>联系人电话：</th>
                <td><?php echo $output['joinin_detail']['contacts_phone']; ?></td>
            </tr>
            <tr>
                <th>电子邮箱：</th>
                <td><?php echo $output['joinin_detail']['contacts_email']; ?></td>
            </tr>
            <tr>
                <th>员工总数：</th>
                <td><?php echo $output['joinin_detail']['company_employee_count']; ?>&nbsp;人</td>
            </tr>
            <tr>
                <th>注册资金：</th>
                <td><?php echo $output['joinin_detail']['company_registered_capital']; ?>&nbsp;万元</td>
            </tr>
            <tr>
                <th>联系人姓名：</th>
                <td><?php echo $output['joinin_detail']['contacts_name']; ?></td>
            </tr>
            </tbody>
        </table>
        <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
            <thead>
            <tr>
                <th colspan="20">身份证信息</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <th class="w150">法人姓名：</th>
                <td><?php echo $output['joinin_detail']['contacts_name']; ?></td>
            </tr>
            <tr>
            </tr>
            <tr>
                <th class="w150">法人身份证号：</th>
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
                <th colspan="20">营业执照信息（副本）</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <th class="w150">营业执照号：</th>
                <td><?php echo $output['joinin_detail']['business_licence_number']; ?></td>
            </tr>
            <tr>

                <th>营业执照所在地：</th>
                <td><?php echo $output['joinin_detail']['business_licence_address']; ?></td>
            </tr>
            <tr>

                <th>营业执照有效期：</th>
                <td><?php echo $output['joinin_detail']['business_licence_start']; ?>
                    - <?php echo $output['joinin_detail']['business_licence_end']; ?></td>
            </tr>
            <tr>
                <th>法定经营范围：</th>
                <td colspan="20"><?php echo $output['joinin_detail']['business_sphere']; ?></td>
            </tr>
            <tr>
                <th>营业执照号<br/> 电子版：
                </th>
                <td colspan="20"><a nctype="nyroModal"
                                    href="<?php echo getStoreJoininImageUrl($output['joinin_detail']['business_licence_number_electronic']); ?>">
                        <img
                            src="<?php echo getStoreJoininImageUrl($output['joinin_detail']['business_licence_number_electronic']); ?>"
                            alt=""/>
                    </a></td>
            </tr>
            </tbody>
        </table>
        <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
            <thead>
            <tr>
                <th colspan="20">组织机构代码证</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <th>组织机构代码：</th>
                <td colspan="20"><?php echo $output['joinin_detail']['organization_code']; ?></td>
            </tr>
            <tr>
                <th>组织机构代码证<br/> 电子版：
                </th>
                <td colspan="20"><a nctype="nyroModal"
                                    href="<?php echo getStoreJoininImageUrl($output['joinin_detail']['organization_code_electronic']); ?>">
                        <img
                            src="<?php echo getStoreJoininImageUrl($output['joinin_detail']['organization_code_electronic']); ?>"
                            alt=""/>
                    </a></td>
            </tr>
            </tbody>
        </table>
        <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
            <thead>
            <tr>
                <th colspan="20">税务登记证</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <th class="w150">税务登记证号：</th>
                <td><?php echo $output['joinin_detail']['tax_registration_certificate']; ?></td>
            </tr>
            <tr>
                <th>税务登记证号<br/> 电子版：
                </th>
                <td><a nctype="nyroModal"
                       href="<?php echo getStoreJoininImageUrl($output['joinin_detail']['tax_registration_certificate_electronic']); ?>">
                        <img
                            src="<?php echo getStoreJoininImageUrl($output['joinin_detail']['tax_registration_certificate_electronic']); ?>"
                            alt=""/>
                    </a></td>
            </tr>
            </tbody>
        </table>
        <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
            <thead>
            <tr>
                <th colspan="20">开户银行信息：</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <th class="w150">银行开户名：</th>
                <td><?php echo $output['joinin_detail']['bank_account_name']; ?></td>
            </tr>
            <tr>
                <th>公司银行账号：</th>
                <td><?php echo $output['joinin_detail']['bank_account_number']; ?></td>
            </tr>
            <tr>
                <th>开户银行支行名称：</th>
                <td><?php echo $output['joinin_detail']['bank_name']; ?></td>
            </tr>
            <tr>
                <th>开户银行所在地：</th>
                <td colspan="20"><?php echo $output['joinin_detail']['bank_address']; ?></td>
            </tr>
            </tbody>
        </table>
        <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
            <thead>
            <tr>
                <th colspan="20">结算账号信息：</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <th class="w150">银行开户名：</th>
                <td><?php echo $output['joinin_detail']['settlement_bank_account_name']; ?></td>
            </tr>
            <tr>
                <th>公司银行账号：</th>
                <td><?php echo $output['joinin_detail']['settlement_bank_account_number']; ?></td>
            </tr>
            <tr>
                <th>开户银行支行名称：</th>
                <td><?php echo $output['joinin_detail']['settlement_bank_name']; ?></td>
            </tr>
            <tr>
                <th>开户银行所在地：</th>
                <td><?php echo $output['joinin_detail']['settlement_bank_address']; ?></td>
            </tr>
            </tbody>
        </table>
    <?php } ?>
    <?php if ($output['joinin_detail']['personal'] == 1) { ?>
        <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
            <thead>
            <tr>
                <th colspan="20">店铺及负责人信息</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <th class="w150">店铺名称：</th>
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
                <th colspan="20">身份证信息</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <th class="w150">法人姓名：</th>
                <td><?php echo $output['joinin_detail']['contacts_name']; ?></td>
            </tr>
            <tr>
            </tr>
            <tr>
                <th class="w150">法人身份证号：</th>
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
                <th colspan="20">营业执照信息（副本）</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <th class="w150">营业执照号：</th>
                <td><?php echo $output['joinin_detail']['business_licence_number']; ?></td>
            </tr>
            <tr>

                <th>营业执照所在地：</th>
                <td><?php echo $output['joinin_detail']['business_licence_address']; ?></td>
            </tr>
            <tr>

                <th>营业执照有效期：</th>
                <td><?php echo $output['joinin_detail']['business_licence_start']; ?>
                    - <?php echo $output['joinin_detail']['business_licence_end']; ?></td>
            </tr>
            <tr>
                <th>法定经营范围：</th>
                <td colspan="20"><?php echo $output['joinin_detail']['business_sphere']; ?></td>
            </tr>
            <tr>
                <th>营业执照号<br/> 电子版：
                </th>
                <td colspan="20"><a nctype="nyroModal"
                                    href="<?php echo getStoreJoininImageUrl($output['joinin_detail']['business_licence_number_electronic']); ?>">
                        <img
                            src="<?php echo getStoreJoininImageUrl($output['joinin_detail']['business_licence_number_electronic']); ?>"
                            alt=""/>
                    </a></td>
            </tr>
            </tbody>
        </table>
        <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
            <thead>
            <tr>
                <th colspan="20">税务登记证</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <th class="w150">税务登记证号：</th>
                <td><?php echo $output['joinin_detail']['tax_registration_certificate']; ?></td>
            </tr>
            <tr>
                <th>税务登记证号<br/> 电子版：
                </th>
                <td><a nctype="nyroModal"
                       href="<?php echo getStoreJoininImageUrl($output['joinin_detail']['tax_registration_certificate_electronic']); ?>">
                        <img
                            src="<?php echo getStoreJoininImageUrl($output['joinin_detail']['tax_registration_certificate_electronic']); ?>"
                            alt=""/>
                    </a></td>
            </tr>
            </tbody>
        </table>
        <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
            <thead>
            <tr>
                <th colspan="20">开户银行信息：</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <th class="w150">银行开户名：</th>
                <td><?php echo $output['joinin_detail']['bank_account_name']; ?></td>
            </tr>
            <tr>
                <th>银行账号：</th>
                <td><?php echo $output['joinin_detail']['bank_account_number']; ?></td>
            </tr>
            <tr>
                <th>开户银行支行名称：</th>
                <td><?php echo $output['joinin_detail']['bank_name']; ?></td>
            </tr>
            <tr>
                <th>开户银行所在地：</th>
                <td colspan="20"><?php echo $output['joinin_detail']['bank_address']; ?></td>
            </tr>
            </tbody>
        </table>
        <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
            <thead>
            <tr>
                <th colspan="20">结算账号信息：</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <th class="w150">银行开户名：</th>
                <td><?php echo $output['joinin_detail']['settlement_bank_account_name']; ?></td>
            </tr>
            <tr>
                <th>银行账号：</th>
                <td><?php echo $output['joinin_detail']['settlement_bank_account_number']; ?></td>
            </tr>
            <tr>
                <th>开户银行支行名称：</th>
                <td><?php echo $output['joinin_detail']['settlement_bank_name']; ?></td>
            </tr>
            <tr>
                <th>开户银行所在地：</th>
                <td><?php echo $output['joinin_detail']['settlement_bank_address']; ?></td>
            </tr>
            </tbody>
        </table>
    <?php } ?>
    <?php if ($output['joinin_detail']['personal'] == 2) { ?>
        <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
            <thead>
            <tr>
                <th colspan="20">店铺及负责人信息</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <th class="w150">店铺名称：</th>
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
                <th colspan="20">身份证信息</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <th class="w150">法人姓名：</th>
                <td><?php echo $output['joinin_detail']['contacts_name']; ?></td>
            </tr>
            <tr>
            </tr>
            <tr>
                <th class="w150">法人身份证号：</th>
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
                <th colspan="20">开户银行信息：</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <th class="w150">银行开户名：</th>
                <td><?php echo $output['joinin_detail']['bank_account_name']; ?></td>
            </tr>
            <tr>
                <th>银行账号：</th>
                <td><?php echo $output['joinin_detail']['bank_account_number']; ?></td>
            </tr>
            <tr>
                <th>开户银行支行名称：</th>
                <td><?php echo $output['joinin_detail']['bank_name']; ?></td>
            </tr>
            <tr>
                <th>开户银行所在地：</th>
                <td colspan="20"><?php echo $output['joinin_detail']['bank_address']; ?></td>
            </tr>
            </tbody>
        </table>
        <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
            <thead>
            <tr>
                <th colspan="20">结算账号信息：</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <th class="w150">银行开户名：</th>
                <td><?php echo $output['joinin_detail']['settlement_bank_account_name']; ?></td>
            </tr>
            <tr>
                <th>银行账号：</th>
                <td><?php echo $output['joinin_detail']['settlement_bank_account_number']; ?></td>
            </tr>
            <tr>
                <th>开户银行支行名称：</th>
                <td><?php echo $output['joinin_detail']['settlement_bank_name']; ?></td>
            </tr>
            <tr>
                <th>开户银行所在地：</th>
                <td><?php echo $output['joinin_detail']['settlement_bank_address']; ?></td>
            </tr>
            </tbody>
        </table>
    <?php } ?>
    <form id="form_store_verify"
          action="index.php?act=store&op=store_joinin_verify" method="post">
        <input id="verify_type" name="verify_type" type="hidden"/>
        <input name="member_id" type="hidden" value="<?php echo $output['joinin_detail']['member_id']; ?>"/>
        <input type="hidden" value="<?php echo $output['joinin_detail']['company_address']; ?>" id="area_name">
        <input type="hidden" value="" id="area_id" name="area_id">
        <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
            <thead>
            <tr>
                <th colspan="20">店铺经营信息</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <th class="w150">卖家帐号：</th>
                <td><?php echo $output['joinin_detail']['seller_name']; ?></td>
            </tr>
            <tr>
                <th class="w150">店铺名称：</th>
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
                <td colspan="2">
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
                        <?php if (!empty($store_class_names) && is_array($store_class_names)) { ?>
                            <?php $store_class_commis_rates = explode(',', $output['joinin_detail']['store_class_commis_rates']); ?>
                            <?php for ($i = 0, $length = count($store_class_names); $i < $length; $i++) { ?>
                                <?php list($class1, $class2, $class3) = explode(',', $store_class_names[$i]); ?>
                                <tr>
                                    <td><?php echo $class1; ?></td>
                                    <td><?php echo $class2; ?></td>
                                    <td><?php echo $class3; ?></td>
                                    <td>
                                        <?php if (intval($output['joinin_detail']['joinin_state']) === 10) { ?>
                                            <input type="text" nctype="commis_rate"
                                                   value="<?php echo $store_class_commis_rates[$i]; ?>"
                                                   name="commis_rate[]" class="w100"/>%
                                        <?php } else { ?>
                                            <?php echo $store_class_commis_rates[$i]; ?>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                        </tbody>
                    </table>
                </td>
            </tr>
            <?php if (in_array(intval($output['joinin_detail']['joinin_state']), array(STORE_JOIN_STATE_PAY, STORE_JOIN_STATE_FINAL))) { ?>
                <tr>
                    <th>付款凭证：</th>
                    <td><a nctype="nyroModal"
                           href="<?php echo getStoreJoininImageUrl($output['joinin_detail']['paying_money_certificate']); ?>">
                            <img
                                src="<?php echo getStoreJoininImageUrl($output['joinin_detail']['paying_money_certificate']); ?>"
                                alt=""/>
                        </a></td>
                </tr>
                <tr>
                    <th>付款凭证说明：</th>
                    <td><?php echo $output['joinin_detail']['paying_money_certificate_explain']; ?></td>
                </tr>
            <?php } ?>
            <?php if (in_array(intval($output['joinin_detail']['joinin_state']), array(STORE_JOIN_STATE_NEW, STORE_JOIN_STATE_PAY))) { ?>
                <tr>
                    <th>审核意见：</th>
                    <td colspan="2"><textarea id="joinin_message" name="joinin_message"></textarea></td>
                </tr>
            <?php } ?>
            </tbody>

            <tr>
                <th>合同电子档：</th>
                <td>
                    <!-- sj 20150825-->
                    <?php if (($output['joinin_detail']['agreement_name']) != null) { ?>
                    <input type="button" value="点击下载" onClick = "downloadFile('<?php echo getStoreJoininAgreementUrl($output['joinin_detail']['agreement_name']); ?>')">
                    <?php } ?>
                    <?php if (($output['joinin_detail']['agreement_name']) == null) { ?>
                        暂未上传
                    <?php } ?>
                </td>
            </tr>
        </table>
        <?php if (in_array(intval($output['joinin_detail']['joinin_state']), array(STORE_JOIN_STATE_NEW, STORE_JOIN_STATE_PAY))) { ?>
            <div id="validation_message" style="color: red; display: none;"></div>
            <div>
                <a id="btn_fail" class="btn" href="JavaScript:void(0);"><span>拒绝</span></a>
                <a id="btn_pass" class="btn" href="JavaScript:void(0);"><span>通过</span></a>
            </div>
        <?php } ?>
    </form>
</div>


