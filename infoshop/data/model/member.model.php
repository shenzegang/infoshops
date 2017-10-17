<?php
/**
 * 会员管理
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

class memberModel extends Model
{

    public function __construct()
    {
        parent::__construct('member');
    }

    /**
     * 会员详细信息
     *
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getMemberInfo($condition, $field = '*')
    {
        return $this->field($field)
            ->where($condition)
            ->find();
    }

    /**
     * 会员列表
     *
     * @param array $condition
     * @param string $field
     * @param number $page
     * @param string $order
     */
    public function getMemberList($condition = array(), $field = '*', $page = 0, $order = 'member_id desc')
    {
        return $this->where($condition)
            ->page($page)
            ->order($order)
            ->select();
    }

    /**
     * 会员数量
     *
     * @param array $condition
     * @return int
     */
    public function getMemberCount($condition)
    {
        return $this->where($condition)->count();
    }

    /**
     * 登录时创建会话SESSION
     *
     * @param array $member_info
     *            会员信息
     */
    public function createSession($member_info = array())
    {
        if (empty($member_info) || !is_array($member_info))
            return;


        $_SESSION['is_login'] = '1';
        $_SESSION['member_id'] = $member_info['member_id'];
        $_SESSION['member_name'] = $member_info['member_name'];
        $_SESSION['member_email'] = $member_info['member_email'];
        $_SESSION['is_buy'] = $member_info['is_buy'];
        $_SESSION['avatar'] = $member_info['member_avatar'];
        $_SESSION['member_points'] = $member_info['member_points'];
        $store_joinin_model = Model('store_joinin');
        //sj 20150826 将店铺类型personal存入session
        $store_joinin = $store_joinin_model->getOne(array('member_id' => $_SESSION['member_id']));
        $_SESSION['personal'] = $store_joinin['personal'];

        if (trim($member_info['member_qqopenid'])) {
            $_SESSION['openid'] = $member_info['member_qqopenid'];
        }
        if (trim($member_info['member_sinaopenid'])) {
            $_SESSION['slast_key']['uid'] = $member_info['member_sinaopenid'];
        }
        if (!empty($member_info['member_login_time'])) { // 登录时间更新
            $update_info = array(
                'member_login_num' => ($member_info['member_login_num'] + 1),
                'member_login_time' => time(),
                'member_old_login_time' => $member_info['member_login_time'],
                'member_login_ip' => getIp(),
                'member_old_login_ip' => $member_info['member_login_ip']
            );
            $this->updateMember($update_info, $member_info['member_id']);
        }

        $seller_info = Model('seller')->getSellerInfo(array(
            'member_id' => $_SESSION['member_id']
        ));

        if (!empty($seller_info)) $this->createSession_seller($seller_info);
    }

    /**
     * 登录时创建会话SESSION
     * @param array $seller_info 商家信息
     */
    protected function createSession_seller($seller_info = array())
    {
        /**
         *    获取商家对应的店铺信息
         */
        $model_store = Model('store');
        $store_info = $model_store->getStoreInfoByID($seller_info['store_id']);
        /*
         * 	获取商铺对应的商铺组信息
         */
        $model_seller_group = Model('seller_group');
        $seller_group_info = $model_seller_group->getSellerGroupInfo(array('group_id' => $seller_info['seller_group_id']));

        $_SESSION['grade_id'] = $store_info['grade_id'];
        $_SESSION['seller_id'] = $seller_info['seller_id'];
        $_SESSION['seller_name'] = $seller_info['seller_name'];
        $_SESSION['seller_is_admin'] = intval($seller_info['is_admin']);
        $_SESSION['store_id'] = intval($seller_info['store_id']);
        $_SESSION['store_name'] = $store_info['store_name'];
        $_SESSION['seller_limits'] = explode(',', $seller_group_info['limits']);
        if ($seller_info['is_admin']) {
            $_SESSION['seller_group_name'] = '管理员';
        } else {
            $_SESSION['seller_group_name'] = $seller_group_info['group_name'];
        }
        if (!$seller_info['last_login_time']) {
            $seller_info['last_login_time'] = TIMESTAMP;
        }
        $_SESSION['seller_last_login_time'] = date('Y-m-d H:i', $seller_info['last_login_time']);
        $seller_menu = $this->getSellerMenuList($seller_info['is_admin'], explode(',', $seller_group_info['limits']));
        $_SESSION['seller_menu'] = $seller_menu['seller_menu'];
        $_SESSION['seller_function_list'] = $seller_menu['seller_function_list'];
        if (!empty($seller_info['seller_quicklink'])) {
            $quicklink_array = explode(',', $seller_info['seller_quicklink']);
            foreach ($quicklink_array as $value) {
                $_SESSION['seller_quicklink'][$value] = $value;
            }
        }

        // 更新卖家登陆时间
        Model('seller')->editSeller(
            array('last_login_time' => TIMESTAMP),
            array('seller_id' => $seller_info['seller_id'])
        );
    }

    protected function getSellerMenuList($is_admin, $limits)
    {
        $seller_menu = array();
        if (intval($is_admin) !== 1) {
            $menu_list = $this->_getMenuList();
            foreach ($menu_list as $key => $value) {
                foreach ($value['child'] as $child_key => $child_value) {
                    if (!in_array($child_value['act'], $limits)) {
                        unset($menu_list[$key]['child'][$child_key]);
                    }
                }

                if (count($menu_list[$key]['child']) > 0) {
                    $seller_menu[$key] = $menu_list[$key];
                }
            }
        } else {
            $seller_menu = $this->_getMenuList();
        }
        $seller_function_list = $this->_getSellerFunctionList($seller_menu);
        return array(
            'seller_menu' => $seller_menu,
            'seller_function_list' => $seller_function_list
        );
    }

    private function _getCurrentMenu($seller_function_list)
    {
        $current_menu = $seller_function_list[$_GET['act']];
        if (empty($current_menu)) {
            $current_menu = array(
                'model' => 'index',
                'model_name' => '首页'
            );
        }
        return $current_menu;
    }

    private function _getMenuList()
    {
        $menu_list = array(
            'goods' => array(
                'name' => '商品',
                'child' => array(
                    array(
                        'name' => '商品发布',
                        'act' => 'store_goods_add',
                        'op' => 'index'
                    ),
                    array(
                        'name' => '淘宝导入',
                        'act' => 'taobao_import',
                        'op' => 'index'
                    ),
                    array(
                        'name' => '出售中的商品',
                        'act' => 'store_goods_online',
                        'op' => 'index'
                    ),
                    array(
                        'name' => '仓库中的商品',
                        'act' => 'store_goods_offline',
                        'op' => 'index'
                    ),
                    array(
                        'name' => '库存警报',
                        'act' => 'store_storage_alarm',
                        'op' => 'index'
                    ),
                    array(
                        'name' => '关联板式',
                        'act' => 'store_plate',
                        'op' => 'index'
                    ),
                    array(
                        'name' => '商品规格',
                        'act' => 'store_spec',
                        'op' => 'index'
                    ),
                    array(
                        'name' => '图片空间',
                        'act' => 'store_album',
                        'op' => 'album_cate'
                    )
                )
            ),
            'order' => array(
                'name' => '订单',
                'child' => array(
                    array(
                        'name' => '订单管理',
                        'act' => 'store_order',
                        'op' => 'index'
                    ),
                    array(
                        'name' => '发货',
                        'act' => 'store_deliver',
                        'op' => 'index'
                    ),
                    array(
                        'name' => '发货设置',
                        'act' => 'store_deliver_set',
                        'op' => 'daddress_list'
                    ),
                    array(
                        'name' => '评价管理',
                        'act' => 'store_evaluate',
                        'op' => 'list'
                    ),
                    array(
                        'name' => '打印设置',
                        'act' => 'store_printsetup',
                        'op' => 'index'
                    )
                )
            ),
            'promotion' => array(
                'name' => '促销',
                'child' => array(
                    array(
                        'name' => '团购管理',
                        'act' => 'store_groupbuy',
                        'op' => 'index'
                    ),
                    array(
                        'name' => '限时折扣',
                        'act' => 'store_promotion_xianshi',
                        'op' => 'xianshi_list'
                    ),
                    array(
                        'name' => '满即送',
                        'act' => 'store_promotion_mansong',
                        'op' => 'mansong_list'
                    ),
                    array(
                        'name' => '优惠套装',
                        'act' => 'store_promotion_bundling',
                        'op' => 'bundling_list'
                    ),
                    array(
                        'name' => '推荐展位',
                        'act' => 'store_promotion_booth',
                        'op' => 'booth_goods_list'
                    ),
                    array(
                        'name' => '代金券管理',
                        'act' => 'store_voucher',
                        'op' => 'templatelist'
                    ),
                    array(
                        'name' => '活动管理',
                        'act' => 'store_activity',
                        'op' => 'store_activity'
                    )
                )
            ),
            'store' => array(
                'name' => '店铺',
                'child' => array(
                    array(
                        'name' => '店铺设置',
                        'act' => 'store_setting',
                        'op' => 'store_setting'
                    ),
                    array(
                        'name' => '店铺导航',
                        'act' => 'store_navigation',
                        'op' => 'navigation_list'
                    ),
                    array(
                        'name' => '店铺动态',
                        'act' => 'store_sns',
                        'op' => 'index'
                    ),
                    array(
                        'name' => '店铺信息',
                        'act' => 'store_info',
                        'op' => 'store_info'
                    ),
                    array(
                        'name' => '店铺分类',
                        'act' => 'store_goods_class',
                        'op' => 'index'
                    ),
                    array(
                        'name' => '品牌申请',
                        'act' => 'store_brand',
                        'op' => 'brand_list'
                    ),
                    array(
                        'name' => '店铺保证金',
                        'act' => 'seller_deposit',
                        'op' => 'seller_deposit'
                    ),
                    array(
                        'name' => '申请经营类目',
                        'act' => 'store_apply_business',
                        'op' => 'category_list'
                    )
                )
            ),
            'transport' => array(
                'name' => '物流',
                'child' => array(
                    array(
                        'name' => '物流工具',
                        'act' => 'store_transport',
                        'op' => 'index'
                    ),
                    array(
                        'name' => '免运费额度',
                        'act' => 'store_free_freight',
                        'op' => 'index'
                    )
                )
            ),
            'consult' => array(
                'name' => '客服',
                'child' => array(
                    array(
                        'name' => '客服设置',
                        'act' => 'store_callcenter',
                        'op' => 'index'
                    ),
                    array(
                        'name' => '咨询管理',
                        'act' => 'store_consult',
                        'op' => 'consult_list'
                    ),
                    array(
                        'name' => '投诉管理',
                        'act' => 'store_complain',
                        'op' => 'list'
                    ),
                    array(
                        'name' => '客服工作台',
                        'act' => 'store_workbench',
                        'op' => 'call_workbench'
                    )
                )
            ),
            'service' => array(
                'name' => '售后',
                'child' => array(
                    array(
                        'name' => '退款记录',
                        'act' => 'store_refund',
                        'op' => 'index'
                    ),
                    array(
                        'name' => '退货记录',
                        'act' => 'store_return',
                        'op' => 'index'
                    )
                )
            ),
            'settle' => array(
                'name' => '结算',
                'child' => array(
                    array(
                        'name' => '结算管理',
                        'act' => 'store_bill',
                        'op' => 'index'
                    )
                )
            ),
            'statistics' => array(
                'name' => '统计',
                'child' => array(
                    array(
                        'name' => '流量统计',
                        'act' => 'statistics_flow',
                        'op' => 'flow_statistics'
                    ),
                    array(
                        'name' => '销量统计',
                        'act' => 'statistics_sale',
                        'op' => 'sale_statistics'
                    ),
                    array(
                        'name' => '购买率统计',
                        'act' => 'statistics_probability',
                        'op' => 'probability_statistics'
                    )
                )
            ),
            'account' => array(
                'name' => '帐号',
                'child' => array(
                    array(
                        'name' => '帐号列表',
                        'act' => 'store_account',
                        'op' => 'account_list'
                    ),
                    array(
                        'name' => '帐号组',
                        'act' => 'store_account_group',
                        'op' => 'group_list'
                    ),
                    array(
                        'name' => '帐号日志',
                        'act' => 'seller_log',
                        'op' => 'log_list'
                    ),
                    array(
                        'name' => '店铺消费',
                        'act' => 'store_cost',
                        'op' => 'cost_list'
                    ),
                    array(
                        'name' => '短信配置',
                        'act' => 'store_sms_conf',
                        'op' => 'index'
                    )
                )
            )
        );
        return $menu_list;
    }

    private function _getSellerFunctionList($menu_list)
    {
        $format_menu = array();
        foreach ($menu_list as $key => $menu_value) {
            foreach ($menu_value['child'] as $submenu_value) {
                $format_menu[$submenu_value['act']] = array(
                    'model' => $key,
                    'model_name' => $menu_value['name'],
                    'name' => $submenu_value['name'],
                    'act' => $submenu_value['act'],
                    'op' => $submenu_value['op']
                );
            }
        }
        return $format_menu;
    }

    /**
     * 注册
     */
    public function register($register_info)
    {
        // 注册验证
        $obj_validate = new Validate();
        $obj_validate->validateparam = array(
            array(
                "input" => $register_info["username"],
                "require" => "true",
                "message" => '用户名不能为空'
            ),
            array(
                "input" => $register_info["password"],
                "require" => "true",
                "message" => '密码不能为空'
            ),
            array(
                "input" => $register_info["password_confirm"],
                "require" => "true",
                "validator" => "Compare",
                "operator" => "==",
                "to" => $register_info["password"],
                "message" => '密码与确认密码不相同'
            )
        );
        if($register_info["member_tel"] == '') {
            $obj_validate->validateparam = array(
                array(
                    "input" => $register_info["email"],
                    "require" => "true",
                    "validator" => "email",
                    "message" => '电子邮件格式不正确'
                )
            );
        }
        if($register_info["email"] == '') {
            $obj_validate->validateparam = array(
                array(
                    "input" => $register_info["member_tel"],
                    "require" => "true",
                    "validator" => "mobile",
                    "message" => '手机号码格式不正确'
                )
            );
        }
        $error = $obj_validate->validate();
        if ($error != '') {
            return array(
                'error' => $error
            );
        }

        // 验证用户名是否重复
        $check_member_name = $this->infoMember(array(
            'member_name' => trim($register_info['username'])
        ));
        if (is_array($check_member_name) and count($check_member_name) > 0) {
            return array(
                'error' => '用户名已存在'
            );
        }

        require_once BASE_CORE_PATH . DS . 'framework' . DS . 'function' . DS . 'payapi.php';
        $resp = array();
        $req = array('userName' => trim($register_info['username']));
        $validResp = trade(C('alipay.check_user_url'), $req, $resp);
        if (!empty($resp['userInfos']) && count($resp['userInfos']) > 0) {
            return array('error' => '用户名已存在');
        }

        // 验证邮箱是否重复
        $check_member_email = $this->infoMember(array(
            'member_email' => trim($register_info['email'])
        ));
        //if (is_array($check_member_email) and count($check_member_email) > 0) {
            //return array(
               // 'error' => '邮箱已存在'
            //);
        //}
        // 验证邮箱是否重复
        $check_member_mobile = $this->infoMember(array(
            'member_tel' => trim($register_info['member_tel'])
        ));
        // 会员添加
        $member_info = array();
        $member_info['member_name'] = $register_info['username'];
        $member_info['member_passwd'] = $register_info['password'];
        $member_info['member_email'] = $register_info['email'];
        $member_info['member_tel'] = $register_info['member_tel'];

        $insert_id = $this->addMember($member_info);
        if ($insert_id) {
            // 添加会员哈金豆
            if ($GLOBALS['setting_config']['points_isuse'] == 1) {
                $points_model = Model('points');
                $points_model->savePointsLog('regist', array(
                    'pl_memberid' => $insert_id,
                    'pl_membername' => $register_info['username']
                ), false);
            }

            // 添加默认相册
            $insert['ac_name'] = '买家秀';
            $insert['member_id'] = $insert_id;
            $insert['ac_des'] = '买家秀默认相册';
            $insert['ac_sort'] = 1;
            $insert['is_default'] = 1;
            $insert['upload_time'] = TIMESTAMP;
            Model()->table('sns_albumclass')->insert($insert);

            $member_info['member_id'] = $insert_id;
            $member_info['is_buy'] = 1;

            return $member_info;
        } else {
            return array(
                'error' => '注册失败'
            );
        }
    }

    /**
     * 注册商城会员
     *
     * @param array $param
     *            会员信息
     * @return array 数组格式的返回结果
     */
    public function addMember($param)
    {
        if (empty($param)) {
            return false;
        }
        $member_info = array();
        $member_info['member_id'] = $param['member_id'];
        $member_info['member_name'] = $param['member_name'];
        $member_info['member_passwd'] = md5(trim($param['member_passwd']));
        $member_info['member_email'] = $param['member_email'];
        $member_info['member_tel'] = $param['member_tel'];
        $member_info['member_time'] = time();
        $member_info['member_login_time'] = $member_info['member_time'];
        $member_info['member_old_login_time'] = $member_info['member_time'];
        $member_info['member_login_ip'] = getIp();
        $member_info['member_old_login_ip'] = $member_info['member_login_ip'];

        $member_info['member_truename'] = $param['member_truename'];
        $member_info['member_qq'] = $param['member_qq'];
        $member_info['member_sex'] = $param['member_sex'];
        $member_info['member_avatar'] = $param['member_avatar'];
        $member_info['member_qqopenid'] = $param['member_qqopenid'];
        $member_info['member_qqinfo'] = $param['member_qqinfo'];
        $member_info['member_sinaopenid'] = $param['member_sinaopenid'];
        $member_info['member_sinainfo'] = $param['member_sinainfo'];
        $result = Db::insert('member', $member_info);
        if ($result) {
            return Db::getLastId();
        } else {
            return false;
        }
    }

    /**
     * 获取会员信息
     *
     * @param array $param
     *            会员条件
     * @param string $field
     *            显示字段
     * @return array 数组格式的返回结果
     */
    public function infoMember($param, $field = '*')
    {
        if (empty($param))
            return false;

        // 得到条件语句
        $condition_str = $this->getCondition($param);
        $param = array();
        $param['table'] = 'member';
        $param['where'] = $condition_str;
        $param['field'] = $field;
        $param['limit'] = 1;
        $member_list = Db::select($param);
        $member_info = $member_list[0];
        if (intval($member_info['store_id']) > 0) {
            $param = array();
            $param['table'] = 'store';
            $param['field'] = 'store_id';
            $param['value'] = $member_info['store_id'];
            $field = 'store_id,store_name,grade_id';
            $store_info = Db::getRow($param, $field);
            if (!empty($store_info) && is_array($store_info)) {
                $member_info['store_name'] = $store_info['store_name'];
                $member_info['grade_id'] = $store_info['grade_id'];
            }
        }
        return $member_info;
    }

    /**
     * 更新会员信息
     *
     * @param array $param
     *            更改信息
     * @param int $member_id
     *            会员条件 id
     * @return array 数组格式的返回结果
     */
    public function updateMember($param, $member_id)
    {
        if (empty($param)) {
            return false;
        }
        $update = false;
        // 得到条件语句
        $condition_str = " member_id='{$member_id}' ";
        $update = Db::update('member', $param, $condition_str);
        return $update;
    }

    /**
     * 会员登录检查
     */
    public function checkloginMember()
    {
        if ($_SESSION['is_login'] == '1') {
            @header("Location: index.php");
            exit();
        }
    }

    /**
     * 检查会员是否允许举报商品
     */
    public function isMemberAllowInform($member_id)
    {
        $condition = array();
        $condition['member_id'] = $member_id;
        $member_info = $this->infoMember($condition, 'inform_allow');
        if (intval($member_info['inform_allow']) === 1) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 将条件数组组合为SQL语句的条件部分
     *
     * @param array $conditon_array
     * @return string
     */
    private function getCondition($conditon_array)
    {
        $condition_sql = '';
        if ($conditon_array['member_id'] != '') {
            $condition_sql .= " and member_id= '" . intval($conditon_array['member_id']) . "'";
        }
        if ($conditon_array['member_name'] != '') {
            $condition_sql .= " and member_name='" . $conditon_array['member_name'] . "'";
        }
        if ($conditon_array['member_passwd'] != '') {
            $condition_sql .= " and member_passwd='" . $conditon_array['member_passwd'] . "'";
        }
        // 是否允许举报
        if ($conditon_array['inform_allow'] != '') {
            $condition_sql .= " and inform_allow='{$conditon_array['inform_allow']}'";
        }
        // 是否允许购买
        if ($conditon_array['is_buy'] != '') {
            $condition_sql .= " and is_buy='{$conditon_array['is_buy']}'";
        }
        // 是否允许发言
        if ($conditon_array['is_allowtalk'] != '') {
            $condition_sql .= " and is_allowtalk='{$conditon_array['is_allowtalk']}'";
        }
        // 是否允许登录
        if ($conditon_array['member_state'] != '') {
            $condition_sql .= " and member_state='{$conditon_array['member_state']}'";
        }
        if ($conditon_array['friend_list'] != '') {
            $condition_sql .= " and member_name IN (" . $conditon_array['friend_list'] . ")";
        }
        if ($conditon_array['member_email'] != '') {
            $condition_sql .= " and member_email='" . $conditon_array['member_email'] . "'";
        }
        if ($conditon_array['member_tel'] != '') {
            $condition_sql .= " and member_tel='" . $conditon_array['member_tel'] . "'";
        }
        if ($conditon_array['no_member_id'] != '') {
            $condition_sql .= " and member_id != '" . $conditon_array['no_member_id'] . "'";
        }
        if ($conditon_array['like_member_name'] != '') {
            $condition_sql .= " and member_name like '%" . $conditon_array['like_member_name'] . "%'";
        }
        if ($conditon_array['like_member_email'] != '') {
            $condition_sql .= " and member_email like '%" . $conditon_array['like_member_email'] . "%'";
        }
        if ($conditon_array['like_member_truename'] != '') {
            $condition_sql .= " and member_truename like '%" . $conditon_array['like_member_truename'] . "%'";
        }
        if ($conditon_array['in_member_id'] != '') {
            $condition_sql .= " and member_id IN (" . $conditon_array['in_member_id'] . ")";
        }
        if ($conditon_array['in_member_name'] != '') {
            $condition_sql .= " and member_name IN (" . $conditon_array['in_member_name'] . ")";
        }
        if ($conditon_array['member_qqopenid'] != '') {
            $condition_sql .= " and member_qqopenid = '{$conditon_array['member_qqopenid']}'";
        }
        if ($conditon_array['member_sinaopenid'] != '') {
            $condition_sql .= " and member_sinaopenid = '{$conditon_array['member_sinaopenid']}'";
        }
        if ($conditon_array['idcard'] != '') {
            $condition_sql .= " and idcard='" . $conditon_array['idcard'] . "'";
        }
        return $condition_sql;
    }

    // /**
    // * 会员列表
    // *
    // * @param array $condition 检索条件
    // * @param obj $obj_page 分页对象
    // * @return array 数组类型的返回结果
    // */
    // public function getMemberList($condition,$obj_page='',$field='*'){
    // $condition_str = $this->getCondition($condition);
    // $param = array();
    // $param['table'] = 'member';
    // $param['where'] = $condition_str;
    // $param['order'] = $condition['order'] ? $condition['order'] : 'member_id desc';
    // $param['field'] = $field;
    // $param['limit'] = $condition['limit'];
    // $member_list = Db::select($param,$obj_page);
    // return $member_list;
    // }

    /**
     * 删除会员
     *
     * @param int $id
     *            记录ID
     * @return array $rs_row 返回数组形式的查询结果
     */
    public function del($id)
    {
        if (intval($id) > 0) {
            $where = " member_id = '" . intval($id) . "'";
            $result = Db::delete('member', $where);
            return $result;
        } else {
            return false;
        }
    }

    /**
     * 查询会员总数
     */
    public function countMember($condition)
    {
        // 得到条件语句
        $condition_str = $this->getCondition($condition);
        $count = Db::getCount('member', $condition_str);
        return $count;
    }

    /**
     * 添加短信验证码
     * @param $param
     * @return bool|mixed
     */
    public function add($param)
    {
        if (empty($param)) {
            return false;
        }
        if (is_array($param)) {
            $tmp = array();
            foreach ($param as $k => $v) {
                $tmp[$k] = $v;
            }
            $result = Db::insert('sendsms', $tmp);
            return $result;
        } else {
            return false;
        }
    }

    /**获取该手机号最新短信验证码
     * @param $id
     * @return array|bool
     */
    public function getOneSMS($mobile_phone)
    {
        if ($mobile_phone != "") {
            $param = array();
            $param['table'] = 'sendsms';
            $param['field'] = '*';
            $param['where'] ='mobile_phone='."'$mobile_phone'";
            $param['order']="id desc";
            $param['limit']='0,1';
            $result = Db::select($param);
            return $result;
        } else {
            return false;
        }
    }
}
