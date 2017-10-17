<?php defined('CorShop') or exit('Access Invalid!'); ?>
<style type="text/css">
    .d_inline {
        display: inline;
    }
</style>

<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3><?php echo $lang['store']; ?></h3>
            <ul class="tab-base">
                <li><a href="index.php?act=store&op=store"><span><?php echo $lang['manage']; ?></span></a></li>
                <li><a href="index.php?act=store&op=store_joinin"><span><?php echo $lang['pending']; ?></span></a></li>
                <li><a href="JavaScript:void(0);" class="current"><span>资料修改</span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>
    <form id="store_form" method="post" enctype="multipart/form-data">
        <input type="hidden" name="form_submit" value="ok"/> <input
            type="hidden" name="member_id"
            value="<?php echo $output['joinin_detail']['member_id']; ?>"/> <input
            type="hidden" name="store_id"
            value="<?php echo $output['store_detail']['store_id']; ?>"/>
        <?php if (($output['joinin_detail']['personal']) == 0) { ?>
            <table class="table tb-type2">
                <tbody>
                <tr>
                    <td colspan="2" class="required"
                        style="font-size: 14px; color: #09c;">公司及法人信息
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="required"><label class="validation"
                                                            for="company_name">公司名称:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><input type="text"
                                                     value="<?php echo $output['joinin_detail']['company_name']; ?>"
                                                     id="company_name" name="company_name" class="txt"></td>
                    <td class="vatop tips"></td>
                </tr>
                <tr>
                    <td colspan="2" class="required"><label class="validation"
                                                            for="company_address">公司所在地:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><input type="text"
                                                     value="<?php echo $output['joinin_detail']['company_address']; ?>"
                                                     id="company_address" name="company_address" class="txt"></td>
                    <td class="vatop tips"></td>
                </tr>
                <tr>
                    <td colspan="2" class="required"><label class="validation"
                                                            for="company_address_detail">公司详细地址:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><input type="text"
                                                     value="<?php echo $output['joinin_detail']['company_address_detail']; ?>"
                                                     id="company_address_detail" name="company_address_detail"
                                                     class="txt"></td>
                    <td class="vatop tips"></td>
                </tr>
                <tr>
                    <td colspan="2" class="required"><label class="validation"
                                                            for="company_phone">公司电话:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><input type="text"
                                                     value="<?php echo $output['joinin_detail']['company_phone']; ?>"
                                                     id="company_phone" name="company_phone" class="txt"></td>
                    <td class="vatop tips"></td>
                </tr>
                <tr>
                    <td colspan="2" class="required"><label class="validation"
                                                            for="contacts_phone">法人电话:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><input type="text"
                                                     value="<?php echo $output['joinin_detail']['contacts_phone']; ?>"
                                                     id="contacts_phone" name="contacts_phone" class="txt"></td>
                    <td class="vatop tips"></td>
                </tr>
                <tr>
                    <td colspan="2" class="required"><label class="validation"
                                                            for="contacts_email">电子邮箱:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><input type="text"
                                                     value="<?php echo $output['joinin_detail']['contacts_email']; ?>"
                                                     id="contacts_email" name="contacts_email" class="txt"></td>
                    <td class="vatop tips"></td>
                </tr>
                <tr>
                    <td colspan="2" class="required"><label class="validation"
                                                            for="company_employee_count">员工总数:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><input type="text"
                                                     value="<?php echo $output['joinin_detail']['company_employee_count']; ?>"
                                                     id="contacts_email" name="company_employee_count" class="txt"></td>
                    <td class="vatop tips"></td>
                </tr>
                <tr>
                    <td colspan="2" class="required"><label class="validation"
                                                            for="company_registered_capital">注册资金:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><input type="text"
                                                     value="<?php echo $output['joinin_detail']['company_registered_capital']; ?>"
                                                     id="company_registered_capital" name="company_registered_capital"
                                                     class="txt"></td>
                    <td class="vatop tips"></td>
                </tr>
                <tr>
                    <td colspan="2" class="required"
                        style="font-size: 14px; color: #09c;">身份证信息
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="required"><label class="validation"
                                                            for="contacts_name">法人姓名:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><input type="text"
                                                     value="<?php echo $output['joinin_detail']['contacts_name']; ?>"
                                                     id="contacts_name" name="contacts_name" class="txt"></td>
                    <td class="vatop tips"></td>
                </tr>
                <tr>
                    <td colspan="2" class="required"><label class="validation"
                                                            for="idcard_number">法人身份证号:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><input type="text"
                                                     value="<?php echo $output['joinin_detail']['idcard_number']; ?>"
                                                     id="idcard_number" name="idcard_number" class="txt"></td>
                    <td class="vatop tips"></td>
                </tr>
                <tr>
                    <td colspan="2" class="required"><label class="validation"
                                                            for="idcard_electronic">法人身份证电子版:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><span class="type-file-box"><input
                                type='text' name='textfield' id='textfield1'
                                class='type-file-text'/> <input type='button' name='button'
                                                                id='button1' value='' class='type-file-button'/> <input
                                name="idcard_electronic" type="file" class="type-file-file"
                                id="idcard_electronic" size="30"> </span></td>
                    <td class="vatop tips"></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><a nctype="nyroModal"
                                                 href="<?php echo getStoreJoininImageUrl($output['joinin_detail']['idcard_electronic']); ?>">
                            <img
                                src="<?php echo getStoreJoininImageUrl($output['joinin_detail']['idcard_electronic']); ?>"
                                width="100"/>
                        </a></td>
                    <td class="vatop tips"></td>
                </tr>
                <tr>
                    <td colspan="2" class="required"
                        style="font-size: 14px; color: #09c;">营业执照信息（副本）
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="required"><label class="validation"
                                                            for="business_licence_number">营业执照号:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><input type="text"
                                                     value="<?php echo $output['joinin_detail']['business_licence_number']; ?>"
                                                     id="business_licence_number" name="business_licence_number"
                                                     class="txt"></td>
                    <td class="vatop tips"></td>
                </tr>
                <tr>
                    <td colspan="2" class="required"><label class="validation"
                                                            for="business_licence_address">营业执照所在地:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><input type="text"
                                                     value="<?php echo $output['joinin_detail']['business_licence_address']; ?>"
                                                     id="business_licence_address" name="business_licence_address"
                                                     class="txt"></td>
                    <td class="vatop tips"></td>
                </tr>
                <tr>
                    <td colspan="2" class="required"><label class="validation"
                                                            for="business_licence_start">营业执照有效期:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><input type="text"
                                                     value="<?php echo $output['joinin_detail']['business_licence_start']; ?>"
                                                     id="business_licence_start" name="business_licence_start"
                                                     class="txt" style="width: 100px;"><input type="text"
                                                                                              value="<?php echo $output['joinin_detail']['business_licence_end']; ?>"
                                                                                              id="business_licence_end"
                                                                                              name="business_licence_end"
                                                                                              class="txt"
                                                                                              style="width: 100px;">
                    </td>
                    <td class="vatop tips"></td>
                </tr>
                <tr>
                    <td colspan="2" class="required"><label class="validation"
                                                            for="business_sphere">法定经营范围:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><input type="text"
                                                     value="<?php echo $output['joinin_detail']['business_sphere']; ?>"
                                                     id="business_sphere" name="business_sphere" class="txt"></td>
                    <td class="vatop tips"></td>
                </tr>
                <tr>
                    <td colspan="2" class="required"><label class="validation"
                                                            for="business_licence_number_electronic">营业执照号:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><span class="type-file-box"><input
                                type='text' name='textfield' id='textfield2'
                                class='type-file-text'/> <input type='button' name='button'
                                                                id='button1' value='' class='type-file-button'/> <input
                                name="business_licence_number_electronic" type="file"
                                class="type-file-file" id="business_licence_number_electronic"
                                size="30" hidefocus="true" nc_type="change_pic"> </span></td>
                    <td class="vatop tips"></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><a nctype="nyroModal"
                                                 href="<?php echo getStoreJoininImageUrl($output['joinin_detail']['business_licence_number_electronic']); ?>">
                            <img
                                src="<?php echo getStoreJoininImageUrl($output['joinin_detail']['business_licence_number_electronic']); ?>"
                                width="100"/>
                        </a></td>
                    <td class="vatop tips"></td>
                </tr>
                <tr>
                    <td colspan="2" class="required"
                        style="font-size: 14px; color: #09c;">组织机构代码证
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="required"><label class="validation"
                                                            for="organization_code">组织机构代码:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><input type="text"
                                                     value="<?php echo $output['joinin_detail']['organization_code']; ?>"
                                                     id="organization_code" name="organization_code" class="txt"></td>
                    <td class="vatop tips"></td>
                </tr>
                <tr>
                    <td colspan="2" class="required"><label class="validation"
                                                            for="organization_code_electronic">组织机构代码证电子版:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><span class="type-file-box"><input
                                type='text' name='textfield' id='textfield3'
                                class='type-file-text'/> <input type='button' name='button'
                                                                id='button1' value='' class='type-file-button'/> <input
                                name="organization_code_electronic" type="file"
                                class="type-file-file" id="organization_code_electronic"
                                size="30" hidefocus="true" nc_type="change_pic"> </span></td>
                    <td class="vatop tips"></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><a nctype="nyroModal"
                                                 href="<?php echo getStoreJoininImageUrl($output['joinin_detail']['organization_code_electronic']); ?>">
                            <img
                                src="<?php echo getStoreJoininImageUrl($output['joinin_detail']['organization_code_electronic']); ?>"
                                width="100"/>
                        </a></td>
                    <td class="vatop tips"></td>
                </tr>
                <tr>
                    <td colspan="2" class="required"
                        style="font-size: 14px; color: #09c;">税务登记证
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="required"><label class="validation"
                                                            for="tax_registration_certificate">税务登记证号:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><input type="text"
                                                     value="<?php echo $output['joinin_detail']['tax_registration_certificate']; ?>"
                                                     id="tax_registration_certificate"
                                                     name="tax_registration_certificate" class="txt"></td>
                    <td class="vatop tips"></td>
                </tr>
                <tr>
                    <td colspan="2" class="required"><label class="validation"
                                                            for="tax_registration_certificate_electronic">组织机构代码证电子版:</label>
                    </td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><span class="type-file-box"><input
                                type='text' name='textfield' id='textfield4'
                                class='type-file-text'/> <input type='button' name='button'
                                                                id='button1' value='' class='type-file-button'/> <input
                                name="tax_registration_certificate_electronic" type="file"
                                class="type-file-file"
                                id="tax_registration_certificate_electronic" size="30"
                                hidefocus="true" nc_type="change_pic"> </span></td>
                    <td class="vatop tips"></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><a nctype="nyroModal"
                                                 href="<?php echo getStoreJoininImageUrl($output['joinin_detail']['tax_registration_certificate_electronic']); ?>">
                            <img
                                src="<?php echo getStoreJoininImageUrl($output['joinin_detail']['tax_registration_certificate_electronic']); ?>"
                                width="100"/>
                        </a></td>
                    <td class="vatop tips"></td>
                </tr>
                <tr>
                    <td colspan="2" class="required"
                        style="font-size: 14px; color: #09c;">开户银行信息
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="required"><label class="validation"
                                                            for="bank_account_name">银行开户名:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><input type="text"
                                                     value="<?php echo $output['joinin_detail']['bank_account_name']; ?>"
                                                     id="bank_account_name" name="bank_account_name" class="txt"></td>
                    <td class="vatop tips"></td>
                </tr>
                <tr>
                    <td colspan="2" class="required"><label class="validation"
                                                            for="bank_account_number">银行账号:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><input type="text"
                                                     value="<?php echo $output['joinin_detail']['bank_account_number']; ?>"
                                                     id="bank_account_number" name="bank_account_number" class="txt">
                    </td>
                    <td class="vatop tips"></td>
                </tr>
                <tr>
                    <td colspan="2" class="required"><label class="validation"
                                                            for="bank_name">开户银行支行名称:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><input type="text"
                                                     value="<?php echo $output['joinin_detail']['bank_name']; ?>"
                                                     id="bank_name" name="bank_name" class="txt"></td>
                    <td class="vatop tips"></td>
                </tr>
                <tr>
                    <td colspan="2" class="required"><label class="validation"
                                                            for="bank_address">开户银行所在地:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><input type="text"
                                                     value="<?php echo $output['joinin_detail']['bank_address']; ?>"
                                                     id="bank_address" name="bank_address" class="txt"></td>
                    <td class="vatop tips"></td>
                </tr>

                <tr>
                    <td colspan="2" class="required"
                        style="font-size: 14px; color: #09c;">结算账号信息
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="required"><label class="validation"
                                                            for="settlement_bank_account_name">银行开户名:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><input type="text"
                                                     value="<?php echo $output['joinin_detail']['settlement_bank_account_name']; ?>"
                                                     id="settlement_bank_account_name"
                                                     name="settlement_bank_account_name" class="txt"></td>
                    <td class="vatop tips"></td>
                </tr>
                <tr>
                    <td colspan="2" class="required"><label class="validation"
                                                            for="settlement_bank_account_number">银行账号:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><input type="text"
                                                     value="<?php echo $output['joinin_detail']['settlement_bank_account_number']; ?>"
                                                     id="settlement_bank_account_number"
                                                     name="settlement_bank_account_number" class="txt"></td>
                    <td class="vatop tips"></td>
                </tr>
                <tr>
                    <td colspan="2" class="required"><label class="validation"
                                                            for="settlement_bank_name">开户银行支行名称:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><input type="text"
                                                     value="<?php echo $output['joinin_detail']['settlement_bank_name']; ?>"
                                                     id="settlement_bank_name" name="settlement_bank_name" class="txt">
                    </td>
                    <td class="vatop tips"></td>
                </tr>
                <tr>
                    <td colspan="2" class="required"><label class="validation"
                                                            for="settlement_bank_address">开户银行所在地:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><input type="text"
                                                     value="<?php echo $output['joinin_detail']['settlement_bank_address']; ?>"
                                                     id="bank_address" name="settlement_bank_address" class="txt"></td>
                    <td class="vatop tips"></td>
                </tr>
                </tbody>
                <tfoot>
                <tr class="tfoot">
                    <td colspan="15"><a href="JavaScript:void(0);" class="btn"
                                        id="submitBtn"><span><?php echo $lang['nc_submit']; ?></span></a></td>
                </tr>
                </tfoot>
            </table>
        <?php } ?>
        <?php if (($output['joinin_detail']['personal']) == 1) { ?>
            <table class="table tb-type2">
                <tbody>
                <tr>
                    <td colspan="2" class="required"
                        style="font-size: 14px; color: #09c;">店铺及负责人信息
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="required"><label class="validation"
                                                            for="company_name">店铺名称:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><input type="text"
                                                     value="<?php echo $output['joinin_detail']['company_name']; ?>"
                                                     id="company_name" name="company_name" class="txt"></td>
                    <td class="vatop tips"></td>
                </tr>
                <tr>
                    <td colspan="2" class="required"><label class="validation"
                                                            for="company_address">所在地:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><input type="text"
                                                     value="<?php echo $output['joinin_detail']['company_address']; ?>"
                                                     id="company_address" name="company_address" class="txt"></td>
                    <td class="vatop tips"></td>
                </tr>
                <tr>
                    <td colspan="2" class="required"><label class="validation"
                                                            for="company_address_detail">详细地址:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><input type="text"
                                                     value="<?php echo $output['joinin_detail']['company_address_detail']; ?>"
                                                     id="company_address_detail" name="company_address_detail"
                                                     class="txt"></td>
                    <td class="vatop tips"></td>
                </tr>
                <tr>
                    <td colspan="2" class="required"><label class="validation"
                                                            for="contacts_phone">联系电话:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><input type="text"
                                                     value="<?php echo $output['joinin_detail']['contacts_phone']; ?>"
                                                     id="contacts_phone" name="contacts_phone" class="txt"></td>
                    <td class="vatop tips"></td>
                </tr>
                <tr>
                    <td colspan="2" class="required"><label class="validation"
                                                            for="contacts_email">电子邮箱:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><input type="text"
                                                     value="<?php echo $output['joinin_detail']['contacts_email']; ?>"
                                                     id="contacts_email" name="contacts_email" class="txt"></td>
                    <td class="vatop tips"></td>
                </tr>
                <tr>
                    <td colspan="2" class="required"
                        style="font-size: 14px; color: #09c;">身份证信息
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="required"><label class="validation"
                                                            for="contacts_name">法人姓名:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><input type="text"
                                                     value="<?php echo $output['joinin_detail']['contacts_name']; ?>"
                                                     id="contacts_name" name="contacts_name" class="txt"></td>
                    <td class="vatop tips"></td>
                </tr>
                <tr>
                    <td colspan="2" class="required"><label class="validation"
                                                            for="idcard_number">法人身份证号:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><input type="text"
                                                     value="<?php echo $output['joinin_detail']['idcard_number']; ?>"
                                                     id="idcard_number" name="idcard_number" class="txt"></td>
                    <td class="vatop tips"></td>
                </tr>
                <tr>
                    <td colspan="2" class="required"><label class="validation"
                                                            for="idcard_electronic">法人身份证电子版:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><span class="type-file-box"><input
                                type='text' name='textfield' id='textfield1'
                                class='type-file-text'/> <input type='button' name='button'
                                                                id='button1' value='' class='type-file-button'/> <input
                                name="idcard_electronic" type="file" class="type-file-file"
                                id="idcard_electronic" size="30"> </span></td>
                    <td class="vatop tips"></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><a nctype="nyroModal"
                                                 href="<?php echo getStoreJoininImageUrl($output['joinin_detail']['idcard_electronic']); ?>">
                            <img
                                src="<?php echo getStoreJoininImageUrl($output['joinin_detail']['idcard_electronic']); ?>"
                                width="100"/>
                        </a></td>
                    <td class="vatop tips"></td>
                </tr>
                <tr>
                    <td colspan="2" class="required"
                        style="font-size: 14px; color: #09c;">营业执照信息（副本）
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="required"><label class="validation"
                                                            for="business_licence_number">营业执照号:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><input type="text"
                                                     value="<?php echo $output['joinin_detail']['business_licence_number']; ?>"
                                                     id="business_licence_number" name="business_licence_number"
                                                     class="txt"></td>
                    <td class="vatop tips"></td>
                </tr>
                <tr>
                    <td colspan="2" class="required"><label class="validation"
                                                            for="business_licence_address">营业执照所在地:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><input type="text"
                                                     value="<?php echo $output['joinin_detail']['business_licence_address']; ?>"
                                                     id="business_licence_address" name="business_licence_address"
                                                     class="txt"></td>
                    <td class="vatop tips"></td>
                </tr>
                <tr>
                    <td colspan="2" class="required"><label class="validation"
                                                            for="business_licence_start">营业执照有效期:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><input type="text"
                                                     value="<?php echo $output['joinin_detail']['business_licence_start']; ?>"
                                                     id="business_licence_start" name="business_licence_start"
                                                     class="txt" style="width: 100px;"><input type="text"
                                                                                              value="<?php echo $output['joinin_detail']['business_licence_end']; ?>"
                                                                                              id="business_licence_end"
                                                                                              name="business_licence_end"
                                                                                              class="txt"
                                                                                              style="width: 100px;">
                    </td>
                    <td class="vatop tips"></td>
                </tr>
                <tr>
                    <td colspan="2" class="required"><label class="validation"
                                                            for="business_sphere">法定经营范围:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><input type="text"
                                                     value="<?php echo $output['joinin_detail']['business_sphere']; ?>"
                                                     id="business_sphere" name="business_sphere" class="txt"></td>
                    <td class="vatop tips"></td>
                </tr>
                <tr>
                    <td colspan="2" class="required"><label class="validation"
                                                            for="business_licence_number_electronic">营业执照号:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><span class="type-file-box"><input
                                type='text' name='textfield' id='textfield2'
                                class='type-file-text'/> <input type='button' name='button'
                                                                id='button1' value='' class='type-file-button'/> <input
                                name="business_licence_number_electronic" type="file"
                                class="type-file-file" id="business_licence_number_electronic"
                                size="30" hidefocus="true" nc_type="change_pic"> </span></td>
                    <td class="vatop tips"></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><a nctype="nyroModal"
                                                 href="<?php echo getStoreJoininImageUrl($output['joinin_detail']['business_licence_number_electronic']); ?>">
                            <img
                                src="<?php echo getStoreJoininImageUrl($output['joinin_detail']['business_licence_number_electronic']); ?>"
                                width="100"/>
                        </a></td>
                    <td class="vatop tips"></td>
                </tr>
                <tr>
                    <td colspan="2" class="required"
                        style="font-size: 14px; color: #09c;">税务登记证
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="required"><label class="validation"
                                                            for="tax_registration_certificate">税务登记证号:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><input type="text"
                                                     value="<?php echo $output['joinin_detail']['tax_registration_certificate']; ?>"
                                                     id="tax_registration_certificate"
                                                     name="tax_registration_certificate" class="txt"></td>
                    <td class="vatop tips"></td>
                </tr>
                <tr>
                    <td colspan="2" class="required"><label class="validation"
                                                            for="tax_registration_certificate_electronic">组织机构代码证电子版:</label>
                    </td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><span class="type-file-box"><input
                                type='text' name='textfield' id='textfield4'
                                class='type-file-text'/> <input type='button' name='button'
                                                                id='button1' value='' class='type-file-button'/> <input
                                name="tax_registration_certificate_electronic" type="file"
                                class="type-file-file"
                                id="tax_registration_certificate_electronic" size="30"
                                hidefocus="true" nc_type="change_pic"> </span></td>
                    <td class="vatop tips"></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><a nctype="nyroModal"
                                                 href="<?php echo getStoreJoininImageUrl($output['joinin_detail']['tax_registration_certificate_electronic']); ?>">
                            <img
                                src="<?php echo getStoreJoininImageUrl($output['joinin_detail']['tax_registration_certificate_electronic']); ?>"
                                width="100"/>
                        </a></td>
                    <td class="vatop tips"></td>
                </tr>
                <tr>
                    <td colspan="2" class="required"
                        style="font-size: 14px; color: #09c;">开户银行信息
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="required"><label class="validation"
                                                            for="bank_account_name">银行开户名:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><input type="text"
                                                     value="<?php echo $output['joinin_detail']['bank_account_name']; ?>"
                                                     id="bank_account_name" name="bank_account_name" class="txt"></td>
                    <td class="vatop tips"></td>
                </tr>
                <tr>
                    <td colspan="2" class="required"><label class="validation"
                                                            for="bank_account_number">银行账号:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><input type="text"
                                                     value="<?php echo $output['joinin_detail']['bank_account_number']; ?>"
                                                     id="bank_account_number" name="bank_account_number" class="txt">
                    </td>
                    <td class="vatop tips"></td>
                </tr>
                <tr>
                    <td colspan="2" class="required"><label class="validation"
                                                            for="bank_name">开户银行支行名称:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><input type="text"
                                                     value="<?php echo $output['joinin_detail']['bank_name']; ?>"
                                                     id="bank_name" name="bank_name" class="txt"></td>
                    <td class="vatop tips"></td>
                </tr>
                <tr>
                    <td colspan="2" class="required"><label class="validation"
                                                            for="bank_address">开户银行所在地:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><input type="text"
                                                     value="<?php echo $output['joinin_detail']['bank_address']; ?>"
                                                     id="bank_address" name="bank_address" class="txt"></td>
                    <td class="vatop tips"></td>
                </tr>

                <tr>
                    <td colspan="2" class="required"
                        style="font-size: 14px; color: #09c;">结算账号信息
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="required"><label class="validation"
                                                            for="settlement_bank_account_name">银行开户名:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><input type="text"
                                                     value="<?php echo $output['joinin_detail']['settlement_bank_account_name']; ?>"
                                                     id="settlement_bank_account_name"
                                                     name="settlement_bank_account_name" class="txt"></td>
                    <td class="vatop tips"></td>
                </tr>
                <tr>
                    <td colspan="2" class="required"><label class="validation"
                                                            for="settlement_bank_account_number">银行账号:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><input type="text"
                                                     value="<?php echo $output['joinin_detail']['settlement_bank_account_number']; ?>"
                                                     id="settlement_bank_account_number"
                                                     name="settlement_bank_account_number" class="txt"></td>
                    <td class="vatop tips"></td>
                </tr>
                <tr>
                    <td colspan="2" class="required"><label class="validation"
                                                            for="settlement_bank_name">开户银行支行名称:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><input type="text"
                                                     value="<?php echo $output['joinin_detail']['settlement_bank_name']; ?>"
                                                     id="settlement_bank_name" name="settlement_bank_name" class="txt">
                    </td>
                    <td class="vatop tips"></td>
                </tr>
                <tr>
                    <td colspan="2" class="required"><label class="validation"
                                                            for="settlement_bank_address">开户银行所在地:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><input type="text"
                                                     value="<?php echo $output['joinin_detail']['settlement_bank_address']; ?>"
                                                     id="bank_address" name="settlement_bank_address" class="txt"></td>
                    <td class="vatop tips"></td>
                </tr>
                </tbody>
                <tfoot>
                <tr class="tfoot">
                    <td colspan="15"><a href="JavaScript:void(0);" class="btn"
                                        id="submitBtn"><span><?php echo $lang['nc_submit']; ?></span></a></td>
                </tr>
                </tfoot>
            </table>
        <?php } ?>
        <?php if (($output['joinin_detail']['personal']) == 2) { ?>
            <table class="table tb-type2">
                <tbody>
                <tr>
                    <td colspan="2" class="required"
                        style="font-size: 14px; color: #09c;">店铺及负责人信息
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="required"><label class="validation"
                                                            for="company_name">店铺名称:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><input type="text"
                                                     value="<?php echo $output['joinin_detail']['company_name']; ?>"
                                                     id="company_name" name="company_name" class="txt"></td>
                    <td class="vatop tips"></td>
                </tr>
                <tr>
                    <td colspan="2" class="required"><label class="validation"
                                                            for="company_address">所在地:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><input type="text"
                                                     value="<?php echo $output['joinin_detail']['company_address']; ?>"
                                                     id="company_address" name="company_address" class="txt"></td>
                    <td class="vatop tips"></td>
                </tr>
                <tr>
                    <td colspan="2" class="required"><label class="validation"
                                                            for="company_address_detail">详细地址:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><input type="text"
                                                     value="<?php echo $output['joinin_detail']['company_address_detail']; ?>"
                                                     id="company_address_detail" name="company_address_detail"
                                                     class="txt"></td>
                    <td class="vatop tips"></td>
                </tr>
                <tr>
                    <td colspan="2" class="required"><label class="validation"
                                                            for="contacts_phone">联系电话:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><input type="text"
                                                     value="<?php echo $output['joinin_detail']['contacts_phone']; ?>"
                                                     id="contacts_phone" name="contacts_phone" class="txt"></td>
                    <td class="vatop tips"></td>
                </tr>
                <tr>
                    <td colspan="2" class="required"><label class="validation"
                                                            for="contacts_email">电子邮箱:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><input type="text"
                                                     value="<?php echo $output['joinin_detail']['contacts_email']; ?>"
                                                     id="contacts_email" name="contacts_email" class="txt"></td>
                    <td class="vatop tips"></td>
                </tr>
                <tr>
                    <td colspan="2" class="required"
                        style="font-size: 14px; color: #09c;">身份证信息
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="required"><label class="validation"
                                                            for="contacts_name">法人姓名:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><input type="text"
                                                     value="<?php echo $output['joinin_detail']['contacts_name']; ?>"
                                                     id="contacts_name" name="contacts_name" class="txt"></td>
                    <td class="vatop tips"></td>
                </tr>
                <tr>
                    <td colspan="2" class="required"><label class="validation"
                                                            for="idcard_number">法人身份证号:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><input type="text"
                                                     value="<?php echo $output['joinin_detail']['idcard_number']; ?>"
                                                     id="idcard_number" name="idcard_number" class="txt"></td>
                    <td class="vatop tips"></td>
                </tr>
                <tr>
                    <td colspan="2" class="required"><label class="validation"
                                                            for="idcard_electronic">法人身份证电子版:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><span class="type-file-box"><input
                                type='text' name='textfield' id='textfield1'
                                class='type-file-text'/> <input type='button' name='button'
                                                                id='button1' value='' class='type-file-button'/> <input
                                name="idcard_electronic" type="file" class="type-file-file"
                                id="idcard_electronic" size="30"> </span></td>
                    <td class="vatop tips"></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><a nctype="nyroModal"
                                                 href="<?php echo getStoreJoininImageUrl($output['joinin_detail']['idcard_electronic']); ?>">
                            <img
                                src="<?php echo getStoreJoininImageUrl($output['joinin_detail']['idcard_electronic']); ?>"
                                width="100"/>
                        </a></td>
                    <td class="vatop tips"></td>
                </tr>
                <tr>
                    <td colspan="2" class="required"
                        style="font-size: 14px; color: #09c;">开户银行信息
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="required"><label class="validation"
                                                            for="bank_account_name">银行开户名:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><input type="text"
                                                     value="<?php echo $output['joinin_detail']['bank_account_name']; ?>"
                                                     id="bank_account_name" name="bank_account_name" class="txt"></td>
                    <td class="vatop tips"></td>
                </tr>
                <tr>
                    <td colspan="2" class="required"><label class="validation"
                                                            for="bank_account_number">银行账号:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><input type="text"
                                                     value="<?php echo $output['joinin_detail']['bank_account_number']; ?>"
                                                     id="bank_account_number" name="bank_account_number" class="txt">
                    </td>
                    <td class="vatop tips"></td>
                </tr>
                <tr>
                    <td colspan="2" class="required"><label class="validation"
                                                            for="bank_name">开户银行支行名称:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><input type="text"
                                                     value="<?php echo $output['joinin_detail']['bank_name']; ?>"
                                                     id="bank_name" name="bank_name" class="txt"></td>
                    <td class="vatop tips"></td>
                </tr>
                <tr>
                    <td colspan="2" class="required"><label class="validation"
                                                            for="bank_address">开户银行所在地:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><input type="text"
                                                     value="<?php echo $output['joinin_detail']['bank_address']; ?>"
                                                     id="bank_address" name="bank_address" class="txt"></td>
                    <td class="vatop tips"></td>
                </tr>

                <tr>
                    <td colspan="2" class="required"
                        style="font-size: 14px; color: #09c;">结算账号信息
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="required"><label class="validation"
                                                            for="settlement_bank_account_name">银行开户名:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><input type="text"
                                                     value="<?php echo $output['joinin_detail']['settlement_bank_account_name']; ?>"
                                                     id="settlement_bank_account_name"
                                                     name="settlement_bank_account_name" class="txt"></td>
                    <td class="vatop tips"></td>
                </tr>
                <tr>
                    <td colspan="2" class="required"><label class="validation"
                                                            for="settlement_bank_account_number">银行账号:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><input type="text"
                                                     value="<?php echo $output['joinin_detail']['settlement_bank_account_number']; ?>"
                                                     id="settlement_bank_account_number"
                                                     name="settlement_bank_account_number" class="txt"></td>
                    <td class="vatop tips"></td>
                </tr>
                <tr>
                    <td colspan="2" class="required"><label class="validation"
                                                            for="settlement_bank_name">开户银行支行名称:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><input type="text"
                                                     value="<?php echo $output['joinin_detail']['settlement_bank_name']; ?>"
                                                     id="settlement_bank_name" name="settlement_bank_name" class="txt">
                    </td>
                    <td class="vatop tips"></td>
                </tr>
                <tr>
                    <td colspan="2" class="required"><label class="validation"
                                                            for="settlement_bank_address">开户银行所在地:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><input type="text"
                                                     value="<?php echo $output['joinin_detail']['settlement_bank_address']; ?>"
                                                     id="bank_address" name="settlement_bank_address" class="txt"></td>
                    <td class="vatop tips"></td>
                </tr>
                </tbody>
                <tfoot>
                <tr class="tfoot">
                    <td colspan="15"><a href="JavaScript:void(0);" class="btn"
                                        id="submitBtn"><span><?php echo $lang['nc_submit']; ?></span></a></td>
                </tr>
                </tfoot>
            </table>
        <?php } ?>
    </form>
</div>
<script type="text/javascript"
        src="<?php echo RESOURCE_SITE_URL; ?>/js/common_select.js"
        charset="utf-8"></script>
<script type="text/javascript"
        src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery-ui/jquery.ui.js"></script>
<script
    src="<?php echo RESOURCE_SITE_URL . "/js/jquery-ui/i18n/zh-CN.js"; ?>"
    charset="utf-8"></script>
<link rel="stylesheet" type="text/css"
      href="<?php echo RESOURCE_SITE_URL; ?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"/>
<script type="text/javascript">
    var SITEURL = "<?php echo SHOP_SITE_URL; ?>";
    function del_auth(key) {
        var store_id = '<?php echo $output['store_array']['store_id'];?>';
        $.get("index.php?act=store&&op=del_auth", {'key': key, 'store_id': store_id}, function (date) {
            if (date) {
                $("#" + key).remove();
                $("#" + key + "_del").remove();
                alert('<?php echo $lang['certification_del_success'];?>');
            }
            else {
                alert('<?php echo $lang['certification_del_fail'];?>');
            }
        });
    }
    $(function () {

        $("#idcard_electronic").change(function () {
            $("#textfield1").val($(this).val());
        });
        $("#business_licence_number_electronic").change(function () {
            $("#textfield2").val($(this).val());
        });
        $("#organization_code_electronic").change(function () {
            $("#textfield3").val($(this).val());
        });
        $("#tax_registration_certificate_electronic").change(function () {
            $("#textfield4").val($(this).val());
        });

        $("#submitBtn").click(function () {
            if ($("#store_form").valid()) {
                $("#store_form").submit();
            }
        });
    });
</script>