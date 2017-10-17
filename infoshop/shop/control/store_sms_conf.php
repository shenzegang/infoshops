<?php
/**
 * 短信配置
 *
 *
 *
 * @copyright  Copyright (c) 2003-2015 B2B2C Inc. (http://www.infol.com.cn)
 * @license    http://www.infol.com.cn
 * @link       http://www.infol.com.cn
 * @since      File available since Release v1.1
 */
defined('CorShop') or exit('Access Invalid!');

class store_sms_confControl extends BaseSellerControl
{

    public function __construct()
    {
        parent::__construct();
        Language::read('member_store_sms_index');
    }
    // 短信配置
    public function indexOp()
    {
        // 读取网站配置
        $model_setting = Model('setting');
        $list_setting = $model_setting->getListSetting();
        Tpl::output('list_setting', $list_setting);
        // 短信模板列表
        $model_setting = Model('setting');
        $model_templates = Model('mail_templates');
        $condition['type'] = '1';
        $condition['mail_switch'] = '1';
        $templates_list = $model_templates->getTemplatesList($condition);
        $model_store_smsconf = Model('store_smsconf');
        $rsInfo = $model_store_smsconf->getOne($_SESSION['store_id']);
        $openarr = array();
        if ($rsInfo) {
            $openarr = json_decode($rsInfo['opensend']);
        }
        if (chksubmit()) {
            $quantity = intval($_POST['bundling_quota_quantity']); // 购买数量（月）
            $price_quantity = $quantity * intval($list_setting['sms_sellprice']); // 扣款数
            if ($quantity <= 0 || $quantity > 12 || $quantity < intval($list_setting['sms_smallbuynum'])) {
                showDialog('您购买的时间不符合规则', 'index.php?act=store_sms_conf&op=index', '', 'error');
            }
            // 实例化模型
            $model_bundling = Model('p_bundling');
            
            $data = array();
            $data['store_id'] = $_SESSION['store_id'];
            $data['store_name'] = $_SESSION['store_name'];
            $data['member_id'] = $_SESSION['member_id'];
            $data['member_name'] = $_SESSION['member_name'];
            $data['bl_quota_month'] = $quantity;
            $data['bl_quota_starttime'] = TIMESTAMP;
            $data['bl_quota_endtime'] = TIMESTAMP + 60 * 60 * 24 * 30 * $quantity;
            $data['bl_state'] = 1;
            
            $return = $model_bundling->addBundlingQuota($data);
            if ($return) {
                // 添加店铺费用记录
                $this->recordStoreCost($price_quantity, '购买短信包月套装');
                
                // 添加任务队列
                $end_time = TIMESTAMP + 60 * 60 * 24 * 30 * $quantity;
                $this->addcron(array(
                    'exetime' => $end_time,
                    'exeid' => $_SESSION['store_id'],
                    'type' => 5
                ), true);
                
                $this->recordSellerLog('购买' . $quantity . '套短信包月套装，单位元');
                $intoarr['tel'] = trim($_POST['tel']);
                $id = $_POST['id'] * 1;
                $intoarr['opensend'] = json_encode($data);
                $intoarr['store_id'] = $_SESSION['store_id'];
                if ($id) {
                    $model_store_smsconf->update($intoarr);
                } else {
                    $model_store_smsconf->add($intoarr);
                }
                showDialog('开通成功', 'index.php?act=store_sms_conf&op=index', 'succ');
            } else {
                showDialog('开通失败', 'index.php?act=store_sms_conf&op=index');
            }
        }
        
        Tpl::output('bl_quota_endtime', $openarr->bl_quota_endtime);
        Tpl::output('bl_quota_starttime', $openarr->bl_quota_starttime);
        Tpl::output('rsInfo', $rsInfo);
        Tpl::output('templates_list', $templates_list);
        $this->profile_menu('index');
        /**
         * 页面输出
         */
        Tpl::showpage('store_sms_form');
    }
    // 购买短信
    public function buysmsOp()
    {
        // 读取用户金额
        $model_member = Model('member');
        $rsUser = $model_member->getMemberInfo('member_id=' . $_SESSION['member_id'], 'available_predeposit');
        
        Tpl::output('available_predeposit', $rsUser['available_predeposit']);
        // 读取网站配置
        $model_setting = Model('setting');
        $list_setting = $model_setting->getListSetting();
        Tpl::output('list_setting', $list_setting);
        // 读取店铺短信配置
        $model_store_smsconf = Model('store_smsconf');
        $rsInfo = $model_store_smsconf->getOne($_SESSION['store_id']);
        Tpl::output('rsInfo', $rsInfo);
        
        // 提交处理
        if (chksubmit()) {
            $quantity = intval($_POST['buynum']); // 购买数量（条）
            $price_quantity = $quantity * intval($list_setting['sms_sellprice']); // 扣款数
            if ($quantity < intval($list_setting['sms_smallbuynum'])) {
                showDialog('您购买的短信数量不能少于' . $list_setting['sms_smallbuynum'] . '条', 'index.php?act=store_sms_conf&op=buysms', '', 'error');
            }
            // 实例化模型
            $model_bundling = Model('p_bundling');
            
            $data = array();
            $data['store_id'] = $_SESSION['store_id'];
            $data['store_name'] = $_SESSION['store_name'];
            $data['member_id'] = $_SESSION['member_id'];
            $data['member_name'] = $_SESSION['member_name'];
            $data['bl_quota_month'] = $quantity;
            $data['bl_quota_starttime'] = TIMESTAMP;
            $data['bl_quota_endtime'] = TIMESTAMP + 60 * 60 * 24 * 30 * $quantity;
            $data['bl_state'] = 1;
            
            $return = $model_bundling->addBundlingQuota($data);
            if ($return) {
                // 添加店铺费用记录
                $this->recordStoreCost($price_quantity, '购买优惠套装');
                
                // 添加任务队列
                $end_time = TIMESTAMP + 60 * 60 * 24 * 30 * $quantity;
                $this->addcron(array(
                    'exetime' => $end_time,
                    'exeid' => $_SESSION['store_id'],
                    'type' => 3
                ), true);
                
                $this->recordSellerLog('购买' . $quantity . '套优惠套装，单位元');
                showDialog(L('bundling_quota_price_succ'), urlShop('store_promotion_bundling', 'bundling_list'), 'succ');
            } else {
                showDialog(L('bundling_quota_price_fail'), urlShop('store_promotion_bundling', 'bundling_quota_add'));
            }
        }
        $this->profile_menu('sms_pay');
        Tpl::showpage('store_smsbuy_form');
    }

    public function smslogOp()
    {
        $model_sms_log = Model('store_smslog');
        $condition = array();
        $condition['store_id'] = $_SESSION['store_id'];
        
        $condition['dateline'] = array(
            'time',
            array(
                strtotime($_GET['add_time_from']),
                strtotime($_GET['add_time_to'])
            )
        );
        $log_list = $model_sms_log->getSellerLogList($condition, 10, 'id desc');
        Tpl::output('cost_list', $log_list);
        Tpl::output('show_page', $model_sms_log->showpage(2));
        $this->profile_menu('sms_log');
        Tpl::showpage('store_smslog_list');
    }

    /**
     * 用户中心右边，小导航
     *
     * @param string $menu_key
     *            当前导航的menu_key
     * @return
     *
     */
    private function profile_menu($menu_key = '')
    {
        $menu_array = array(
            1 => array(
                'menu_key' => 'index',
                'menu_name' => '短信配置',
                'menu_url' => 'index.php?act=store_sms_conf&op=index'
            ),
            // 4=>array('menu_key'=>'sms_pay','menu_name'=>'购买短信','menu_url'=>'index.php?act=store_sms_conf&op=buysms'),
            5 => array(
                'menu_key' => 'sms_log',
                'menu_name' => '短信记录',
                'menu_url' => 'index.php?act=store_sms_conf&op=smslog'
            )
        );
        Tpl::output('member_menu', $menu_array);
        Tpl::output('menu_key', $menu_key);
    }
}