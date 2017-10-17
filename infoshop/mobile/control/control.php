<?php
/**
 * mobile父类
 *
 *
 * @copyright  Copyright (c) 2003-2015 B2B2C Inc. (http://www.infol.com.cn)
 * @license    http://www.infol.com.cn
 * @link       http://www.infol.com.cn
 * @since      File available since Release v1.1
 */
defined('CorShop') or exit('Access Invalid!');

/**
 * ******************************** 前台control父类 *********************************************
 */
class mobileControl
{
    
    // 客户端类型
    protected $client_type_array = array(
        'android',
        'wap',
        'wechat',
        'ios'
    );
    // 列表默认分页数
    protected $page = 5;

    public function __construct()
    {
        Language::read('mobile');
        
        // 分页数处理
        $page = intval($_GET['page']);
        if ($page > 0) {
            $this->page = $page;
        }
    }

    /**
     * 系统通知发送函数
     *
     * @param int $receiver_id
     *            接受人编号
     * @param string $tpl_code
     *            模板标识码
     * @param array $param
     *            内容数组
     * @param bool $flag
     *            是否遵从系统设置
     * @return boolean
     */
    protected function send_notice($receiver_id, $tpl_code, $param, $flag = true)
    {
        $mail_tpl_model = Model('mail_templates');
        $mail_tpl = $mail_tpl_model->getOneTemplates($tpl_code);
        if (empty($mail_tpl) || $mail_tpl['mail_switch'] == 0)
            return false;
        
        $member_model = Model('member');
        $receiver = $member_model->infoMember(array(
            'member_id' => $receiver_id
        ));
        if (empty($receiver))
            return false;
        
        $subject = ncReplaceText($mail_tpl['title'], $param);
        $message = ncReplaceText($mail_tpl['content'], $param);
        // 根据模板里面确定的通知类型采用对应模式发送通知
        $result = false;
        switch ($mail_tpl['type']) {
            case '0':
                $email = new Email();
                $result = true;
                if ($flag and $GLOBALS['setting_config']['email_enabled'] == '1' or $flag == false) {
                    $result = $email->send_sys_email($receiver['member_email'], $subject, $message);
                }
                break;
            case '1':
                $model_message = Model('message');
                $param = array(
                    'member_id' => $receiver_id,
                    'to_member_name' => $receiver['member_name'],
                    'msg_content' => $message,
                    'message_type' => 1
                ) // 表示系统消息
;
                $result = $model_message->saveMessage($param);
                break;
        }
        return $result;
    }

    /**
     * 系统发送短信
     */
    protected function send_sms($receiver_id, $tpl_code, $param, $flag = true, $logarray = array())
    {
        $model_setting = Model('setting');
        $list_setting = $model_setting->getListSetting();
        $mail_tpl_model = Model('mail_templates');
        $mail_tpl = $mail_tpl_model->getOneTemplates($tpl_code);
        if (intval($logarray['store_id']) < 1) {
            return false;
        }
        $smsconf = Model('store_smsconf')->getOne($logarray['store_id']);
        $smsarr = json_decode($smsconf['opensend']);
        if ($smsarr->bl_quota_starttime > time() || $smsarr->bl_quota_endtime < time()) {
            return false;
        }
        if (empty($mail_tpl) || $mail_tpl['mail_switch'] == 0)
            return false;
        
        $member_model = Model('member');
        $receiver = $member_model->infoMember(array(
            'member_id' => $receiver_id
        ));
        if (empty($receiver))
            return false;
        
        $subject = ncReplaceText($mail_tpl['title'], $param);
        $message = ncReplaceText($mail_tpl['content'], $param);
        // 根据模板里面确定的通知类型采用对应模式发送通知
        if ($GLOBALS['setting_config']['sms_enabled'] == '1') {
            $logarray['title'] = $subject;
            model('store_smslog')->add($logarray);
            $result = model('sms')->sendSms($message, $receiver['member_tel'], $list_setting['sms_type_pass']);
        }
        return $result;
    }
}

class mobileHomeControl extends mobileControl
{

    public function __construct()
    {
        parent::__construct();
    }
}

class mobileMemberControl extends mobileControl
{

    protected $member_info = array();

    public function __construct()
    {
        parent::__construct();
        
        $model_mb_user_token = Model('mb_user_token');
        $key = $_POST['key'];
        if (empty($key)) {
            $key = $_GET['key'];
        }
        $mb_user_token_info = $model_mb_user_token->getMbUserTokenInfoByToken($key);
        if (empty($mb_user_token_info)) {
            output_error('请登陆', array(
                'login' => '0'
            ));
        }
        
        $model_member = Model('member');
        $this->member_info = $model_member->getMemberInfo(array(
            'member_id' => $mb_user_token_info['member_id']
        ));
        if (empty($this->member_info)) {
            output_error('请登陆', array(
                'login' => '0'
            ));
        } else {
            // 读取卖家信息
            $seller_info = Model('seller')->getSellerInfo(array(
                'member_id' => $this->member_info['member_id']
            ));
            $this->member_info['store_id'] = $seller_info['store_id'];
        }
    }
} 
