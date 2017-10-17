<?php
/**
 * 消息通知
 *
 *
 *
 *
 * @copyright  Copyright (c) 2003-2015 B2B2C Inc. (http://www.infol.com.cn)
 * @license    http://www.infol.com.cn
 * @link       http://www.infol.com.cn
 * @since      File available since Release v1.1
 */
defined('CorShop') or exit('Access Invalid!');

class messageControl extends SystemControl
{

    private $links = array(
        array(
            'url' => 'act=message&op=email',
            'lang' => 'email_set'
        ),
        array(
            'url' => 'act=message&op=email_tpl',
            'lang' => 'email_tpl'
        ),
        array(
            'url' => 'act=message&op=mass_email',
            'lang' => 'mass_email'
        )
    );

    private $smslinks = array(
        array(
            'url' => 'act=message&op=sms',
            'lang' => 'sms_set'
        ),
        array(
            'url' => 'act=message&op=sms_tpl',
            'lang' => 'sms_tpl'
        ),
        array(
            'url' => 'act=message&op=sms_log',
            'lang' => 'sms_log'
        ),
        array(
            'url' => 'act=message&op=mass_sms',
            'lang' => 'mass_sms'
        )
    );

    public function __construct()
    {
        parent::__construct();
        Language::read('setting,message');
    }

    /**
     * 邮件设置
     */
    public function emailOp()
    {
        $model_setting = Model('setting');
        if (chksubmit()) {
            $update_array = array();
            $update_array['email_enabled'] = $_POST['email_enabled'];
            $update_array['email_type'] = $_POST['email_type'];
            $update_array['email_host'] = $_POST['email_host'];
            $update_array['email_port'] = $_POST['email_port'];
            $update_array['email_addr'] = $_POST['email_addr'];
            $update_array['email_id'] = $_POST['email_id'];
            $update_array['email_pass'] = $_POST['email_pass'];
            
            $result = $model_setting->updateSetting($update_array);
            if ($result === true) {
                $this->log(L('nc_edit,email_set'), 1);
                showMessage(L('nc_common_save_succ'));
            } else {
                $this->log(L('nc_edit,email_set'), 0);
                showMessage(L('nc_common_save_fail'));
            }
        }
        $list_setting = $model_setting->getListSetting();
        Tpl::output('list_setting', $list_setting);
        
        Tpl::output('top_link', $this->sublink($this->links, 'email'));
        Tpl::showpage('message.email');
    }

    /**
     * 邮件模板列表
     */
    public function email_tplOp()
    {
        $model_templates = Model('mail_templates');
        $condition['type'] = '0';
        $templates_list = $model_templates->getTemplatesList($condition);
        Tpl::output('templates_list', $templates_list);
        Tpl::output('top_link', $this->sublink($this->links, 'email_tpl'));
        Tpl::showpage('message.email_tpl');
    }

    /**
     * 编辑邮件模板
     */
    public function email_tpl_editOp()
    {
        $model_templates = Model('mail_templates');
        if (chksubmit()) {
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array(
                    "input" => $_POST["code"],
                    "require" => "true",
                    "message" => L('mailtemplates_edit_no_null')
                ),
                array(
                    "input" => $_POST["title"],
                    "require" => "true",
                    "message" => L('mailtemplates_edit_title_null')
                ),
                array(
                    "input" => $_POST["content"],
                    "require" => "true",
                    "message" => L('mailtemplates_edit_content_null')
                )
            );
            $error = $obj_validate->validate();
            if ($error != '') {
                showMessage($error);
            } else {
                $update_array = array();
                $update_array['code'] = $_POST["code"];
                $update_array['title'] = $_POST["title"];
                $update_array['content'] = $_POST["content"];
                $result = $model_templates->update($update_array);
                if ($result === true) {
                    $this->log(L('nc_edit,email_tpl'), 1);
                    showMessage(L('mailtemplates_edit_succ'), 'index.php?act=message&op=email_tpl');
                } else {
                    $this->log(L('nc_edit,email_tpl'), 0);
                    showMessage(L('mailtemplates_edit_fail'));
                }
            }
        }
        if (empty($_GET['code'])) {
            showMessage(L('mailtemplates_edit_code_null'));
        }
        $templates_array = $model_templates->getOneTemplates($_GET['code']);
        Tpl::output('templates_array', $templates_array);
        Tpl::output('top_link', $this->sublink($this->links, 'email_tpl'));
        Tpl::showpage('message.email_tpl.edit');
    }

    public function email_tpl_ajaxOp()
    {
        $this->ajax();
    }

    private function ajax()
    {
        $model_templates = Model('mail_templates');
        if (chksubmit()) {
            if ($_POST['submit_type'] == 'mail_switchON' || $_POST['submit_type'] == 'mail_switchOFF') {
                if (is_array($_POST['del_id'])) {
                    $param = array();
                    $param['mail_switch'] = $_POST['submit_type'] == 'mail_switchON' ? '1' : '0';
                    foreach ($_POST['del_id'] as $k => $v) {
                        $param['code'] = $v;
                        $model_templates->update($param);
                    }
                    $this->log(L('nc_edit,message_tpl_state'), 1);
                    showMessage(L('nc_common_op_succ'));
                } else {
                    $this->log(L('nc_edit,message_tpl_state'), 0);
                    showMessage(L('nc_common_op_fail'));
                }
            }
        }
    }

    /**
     * 测试邮件发送
     *
     * @param            
     *
     * @return
     *
     */
    public function email_testingOp()
    {
        /**
         * 读取语言包
         */
        $lang = Language::getLangContent();
        
        $email_type = trim($_POST['email_type']);
        $email_host = trim($_POST['email_host']);
        $email_port = trim($_POST['email_port']);
        $email_addr = trim($_POST['email_addr']);
        $email_id = trim($_POST['email_id']);
        $email_pass = trim($_POST['email_pass']);
        
        $email_test = trim($_POST['email_test']);
        $subject = $lang['test_email'];
        $site_url = SHOP_SITE_URL;
        
        $site_title = $GLOBALS['setting_config']['site_name'];
        $message = '<p>' . $lang['this_is_to'] . "<a href='" . $site_url . "' target='_blank'>" . $site_title . '</a>' . $lang['test_email_send_ok'] . '</p>';
        if ($email_type == '1') {
            $obj_email = new Email();
            $obj_email->set('email_server', $email_host);
            $obj_email->set('email_port', $email_port);
            $obj_email->set('email_user', $email_id);
            $obj_email->set('email_password', $email_pass);
            $obj_email->set('email_from', $email_addr);
            $obj_email->set('site_name', $site_title);
            $result = $obj_email->send($email_test, $subject, $message);
        } else {
            $result = @mail($email_test, $subject, $message);
        }
        if ($result === false) {
            $message = $lang['test_email_send_fail'];
            if (strtoupper(CHARSET) == 'GBK') {
                $message = Language::getUTF8($message);
            }
            showMessage($message, '', 'json');
        } else {
            $message = $lang['test_email_send_ok'];
            if (strtoupper(CHARSET) == 'GBK') {
                $message = Language::getUTF8($message);
            }
            showMessage($message, '', 'json');
        }
    }

    /**
     * 短信设置
     */
    public function smsOp()
    {
        $model_setting = Model('setting');
        if (chksubmit()) {
            $update_array = array();
            $update_array['sms_enabled'] = $_POST['sms_enabled'];
            $update_array['sms_type'] = $_POST['sms_type'];
            $update_array['sms_type_user'] = $_POST['sms_type_user'];
            $update_array['sms_type_pass'] = $_POST['sms_type_pass'];
            $update_array['sms_type_key'] = $_POST['sms_type_key'];
            $update_array['sms_smallbuynum'] = $_POST['sms_smallbuynum'];
            $update_array['sms_sellprice'] = $_POST['sms_sellprice'];
            $result = $model_setting->updateSetting($update_array);
            if ($result === true) {
                $this->log(L('nc_edit,sms_set'), 1);
                showMessage(L('nc_common_save_succ'));
            } else {
                $this->log(L('nc_edit,sms_set'), 0);
                showMessage(L('nc_common_save_fail'));
            }
        }
        $list_setting = $model_setting->getListSetting();
        Tpl::output('list_setting', $list_setting);
        
        Tpl::output('top_link', $this->sublink($this->smslinks, 'sms'));
        Tpl::showpage('message.sms');
    }

    public function sms_testingOp()
    {
        
        /**
         * 读取语言包
         */
        $lang = Language::getLangContent();
        
        $sms_type = trim($_POST['sms_type']);
        $sms_user = trim($_POST['sms_user']);
        $sms_pass = trim($_POST['sms_pass']);
        $sms_key = trim($_POST['sms_key']);
        $sms_test = trim($_POST['sms_test']);
        if ($sms_test) {
            
            $result = model('sms')->sendSms('测试成功！', $sms_test);
        }
        
        if (is_array($result) && ! empty($result)) {
            echo '代码：' . $result['SubmitResult']['code'] . "\n结果：" . $result['SubmitResult']['msg'];
        } else {
            echo '发送失败';
        }
        exit();
    }

    /**
     * 短信模板列表
     */
    public function sms_tplOp()
    {
        $model_templates = Model('mail_templates');
        $condition['type'] = '1';
        $templates_list = $model_templates->getTemplatesList($condition);
        Tpl::output('templates_list', $templates_list);
        Tpl::output('top_link', $this->sublink($this->smslinks, 'sms_tpl'));
        Tpl::showpage('message.sms_tpl');
    }

    /**
     * 编辑邮件模板
     */
    public function sms_tpl_editOp()
    {
        $model_templates = Model('mail_templates');
        if (chksubmit()) {
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array(
                    "input" => $_POST["code"],
                    "require" => "true",
                    "message" => L('mailtemplates_edit_no_null')
                ),
                array(
                    "input" => $_POST["title"],
                    "require" => "true",
                    "message" => L('mailtemplates_edit_title_null')
                ),
                array(
                    "input" => $_POST["content"],
                    "require" => "true",
                    "message" => L('mailtemplates_edit_content_null')
                )
            );
            $error = $obj_validate->validate();
            if ($error != '') {
                showMessage($error);
            } else {
                $update_array = array();
                $update_array['code'] = $_POST["code"];
                $update_array['title'] = $_POST["title"];
                $update_array['content'] = $_POST["content"];
                $result = $model_templates->update($update_array);
                if ($result === true) {
                    $this->log(L('nc_edit,email_tpl'), 1);
                    showMessage(L('mailtemplates_edit_succ'), 'index.php?act=message&op=sms_tpl');
                } else {
                    $this->log(L('nc_edit,email_tpl'), 0);
                    showMessage(L('mailtemplates_edit_fail'));
                }
            }
        }
        if (empty($_GET['code'])) {
            showMessage(L('mailtemplates_edit_code_null'));
        }
        $templates_array = $model_templates->getOneTemplates($_GET['code']);
        Tpl::output('templates_array', $templates_array);
        Tpl::output('top_link', $this->sublink($this->smslinks, 'sms_tpl'));
        Tpl::showpage('message.sms_tpl.edit');
    }

    /**
     * 短信记录
     */
    public function sms_logOp()
    {
        $model_sms_log = Model('store_smslog');
        $condition = array();
        
        $condition['dateline'] = array(
            'time',
            array(
                strtotime($_GET['time_from']),
                strtotime($_GET['time_to'])
            )
        );
        $log_list = $model_sms_log->getSellerLogList($condition, 10, 'id desc');
        Tpl::output('list', $log_list);
        Tpl::output('show_page', $model_sms_log->showpage(2));
        Tpl::output('top_link', $this->sublink($this->smslinks, 'sms_log'));
        Tpl::showpage('message.sms_log');
    }

    /**
     * 邮件群发
     */
    public function mass_emailOp()
    {
        Tpl::output('top_link', $this->sublink($this->links, 'mass_email'));
        
        if (chksubmit()) {
            
            $type = intval($_POST['type']);
            $subject = trim($_POST['subject']);
            $content = stripslashes(trim($_POST['content']));
            
            if (empty($type)) {
                $email = new Email();
                
                $param['table'] = 'member';
                $list = Db::select($param);
                
                foreach ($list as $key => $value) {
                    $result = $email->send_sys_email($value['member_email'], $subject, $content);
                }
            } else {
                $email = new Email();
                
                $param['table'] = 'store_joinin';
                $param['where'] = 'joinin_state = 40';
                $list = Db::select($param);
                
                foreach ($list as $key => $value) {
                    $result = $email->send_sys_email($value['contacts_email'], $subject, $content);
                }
            }
            
            showMessage('短信群发完成！');
        }
        
        Tpl::showpage('message.mass_email');
    }

    /**
     * 短信群发
     */
    public function mass_smsOp()
    {
        Tpl::output('top_link', $this->sublink($this->smslinks, 'mass_sms'));
        
        if (chksubmit()) {
            
            $type = intval($_POST['type']);
            $content = stripslashes(trim($_POST['content']));
            
            $sms = Model('sms');
            
            if (empty($type)) {
                $param['table'] = 'member';
                $list = Db::select($param);
                
                foreach ($list as $key => $value) {
                    if (! empty($value['member_tel'])) {
                        $return = $sms->sendSms($content, $value['member_tel']);
                        if ($return['SubmitResult']['code'] != 2) {
                            showMessage($return['SubmitResult']['msg']);
                        }
                    }
                }
            } else {
                $param['table'] = 'store_joinin';
                $param['where'] = 'joinin_state = 40';
                $list = Db::select($param);
                
                foreach ($list as $key => $value) {
                    if (! empty($value['contacts_phone'])) {
                        $return = $sms->sendSms($content, $value['contacts_phone']);
                        if ($return['SubmitResult']['code'] != 2) {
                            showMessage($return['SubmitResult']['msg']);
                        }
                    }
                }
            }
            
            showMessage('短信群发完成！');
        }
        
        Tpl::showpage('message.mass_sms');
    }
}