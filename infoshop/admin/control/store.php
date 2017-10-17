<?php
/**
 * 店铺管理界面
 *
 *
 * @copyright  Copyright (c) 2003-2015 B2B2C Inc. (http://www.infol.com.cn)
 * @license    http://www.infol.com.cn
 * @link       http://www.infol.com.cn
 * @since      File available since Release v1.1
 */
defined('CorShop') or exit('Access Invalid!');

class storeControl extends SystemControl
{

    const EXPORT_SIZE = 5000;

    public function __construct()
    {
        parent::__construct();
        Language::read('store,store_grade');
    }

    /**
     * 店铺
     */
    public function storeOp()
    {
        $lang = Language::getLangContent();

        $model_store = Model('store');

        if (trim($_GET['owner_and_name']) != '') {
            $condition['member_name'] = array(
                'like',
                '%' . trim($_GET['owner_and_name']) . '%'
            );
            Tpl::output('owner_and_name', trim($_GET['owner_and_name']));
        }
        if (trim($_GET['store_name']) != '') {
            $condition['store_name'] = array(
                'like',
                '%' . trim($_GET['store_name']) . '%'
            );
            Tpl::output('store_name', trim($_GET['store_name']));
        }
        if (intval($_GET['grade_id']) > 0) {
            $condition['grade_id'] = intval($_GET['grade_id']);
            Tpl::output('grade_id', intval($_GET['grade_id']));
        }

        switch ($_GET['store_type']) {
            case 'close':
                $condition['store_state'] = 0;
                break;
            case 'open':
                $condition['store_state'] = 1;
                break;
            case 'expired':
                $condition['store_end_time'] = array(
                    'between',
                    array(
                        1,
                        TIMESTAMP
                    )
                );
                $condition['store_state'] = 1;
                break;
            case 'expire':
                $condition['store_end_time'] = array(
                    'between',
                    array(
                        TIMESTAMP,
                        TIMESTAMP + 864000
                    )
                );
                $condition['store_state'] = 1;
                break;
        }
        // 店铺列表
        $month_start = mktime(0, 0, 0, date('m'), 1, date('Y'));
        $month_end = mktime(23, 59, 59, date('m'), date('t'), date('Y'));

        $store_list = $model_store->getStoreList($condition, 10);
        foreach ($store_list as $key => $value) {
            $store_list[$key]['sms_count'] = $model_store->table('sms_log')
                ->where(array(
                    'store_id' => $value['store_id'],
                    'add_time' => array(
                        'egt',
                        $month_start
                    ),
                    'add_time' => array(
                        'elt',
                        $month_end
                    )
                ))
                ->count();
        }

        // 店铺等级
        $model_grade = Model('store_grade');
        $grade_list = $model_grade->getGradeList($condition);
        if (!empty($grade_list)) {
            $search_grade_list = array();
            foreach ($grade_list as $k => $v) {
                $search_grade_list[$v['sg_id']] = $v['sg_name'];
            }
        }

        Tpl::output('search_grade_list', $search_grade_list);
        Tpl::output('grade_list', $grade_list);
        Tpl::output('store_list', $store_list);
        Tpl::output('store_type', $this->_get_store_type_array());
        Tpl::output('page', $model_store->showpage('2'));
        Tpl::showpage('store.index');
    }

    private function _get_store_type_array()
    {
        return array(
            'open' => '开启',
            'close' => '关闭',
            'expire' => '即将到期',
            'expired' => '已到期'
        );
    }

    /**
     * 店铺编辑
     */
    public function store_editOp()
    {
        require_once(BASE_CORE_PATH . '/phpqrcode.php');

        $lang = Language::getLangContent();

        $model_store = Model('store');
        // 保存

        if (chksubmit()) {
            // 取店铺等级的审核
            $model_grade = Model('store_grade');
            $grade_array = $model_grade->getOneGrade(intval($_POST['grade_id']));
            if (empty($grade_array)) {
                showMessage($lang['please_input_store_level']);
            }
            // 结束时间
            $time = '';
            if (trim($_POST['end_time']) != '') {
                $time = strtotime($_POST['end_time']);
            }
            $update_array = array();
            $update_array['store_name'] = trim($_POST['store_name']);
            $update_array['store_score'] = intval($_POST['store_score']) > 5 ? 5 : intval($_POST['store_score']);
            $update_array['sc_id'] = intval($_POST['sc_id']);
            $update_array['grade_id'] = intval($_POST['grade_id']);
            $update_array['store_end_time'] = $time;
            $update_array['store_state'] = intval($_POST['store_state']);
            if ($_POST['store_state'] == '0') {
                // 根据店铺状态修改该店铺所有商品状态
                $model_goods = Model('goods');
                $model_goods->editProducesOffline(array(
                    'store_id' => $update_array['store_id']
                ));
                $update_array['store_close_info'] = trim($_POST['store_close_info']);
                $update_array['store_recommend'] = 0;
            } else {
                // 店铺开启后商品不在自动上架，需要手动操作
                $update_array['store_close_info'] = '';
                $update_array['store_recommend'] = intval($_POST['store_recommend']);
            }
            $result = $model_store->editStore($update_array, array(
                'store_id' => $_POST['store_id']
            ));
            if ($result) {
                $url = array(
                    array(
                        'url' => 'index.php?act=store&op=store',
                        'msg' => $lang['back_store_list']
                    ),
                    array(
                        'url' => 'index.php?act=store&op=store_edit&store_id=' . intval($_POST['store_id']),
                        'msg' => $lang['countinue_add_store']
                    )
                );
                $this->log(L('nc_edit,store') . '[' . $_POST['store_name'] . ']', 1);

                $qrcode_url = urlShop('show_store', 'index', array(
                    'store_id' => $_POST['store_id']
                ));

                if (!file_exists(BASE_ROOT_PATH . '/' . DIR_SHOP . '/qrcode/' . $_POST['store_id'] . '.png')) {
                    QRcode::png($qrcode_url, BASE_ROOT_PATH . '/' . DIR_SHOP . '/qrcode/' . $_POST['store_id'] . '.png', 3, 150, 10);
                }

                showMessage($lang['nc_common_save_succ'], $url);
            } else {
                $this->log(L('nc_edit,store') . '[' . $_POST['store_name'] . ']', 1);
                showMessage($lang['nc_common_save_fail']);
            }
        }
        // 取店铺信息
        $store_array = $model_store->getStoreInfoByID($_GET['store_id']);
        if (empty($store_array)) {
            showMessage($lang['store_no_exist']);
        }
        // 整理店铺内容
        $store_array['store_end_time'] = $store_array['store_end_time'] ? date('Y-m-d', $store_array['store_end_time']) : '';
        // 店铺分类
        $model_store_class = Model('store_class');
        $parent_list = $model_store_class->getTreeClassList(2);
        if (is_array($parent_list)) {
            foreach ($parent_list as $k => $v) {
                $parent_list[$k]['sc_name'] = str_repeat("&nbsp;", $v['deep'] * 2) . $v['sc_name'];
            }
        }
        // 店铺等级
        $model_grade = Model('store_grade');
        $grade_list = $model_grade->getGradeList();
        Tpl::output('grade_list', $grade_list);
        Tpl::output('class_list', $parent_list);
        Tpl::output('store_array', $store_array);
        Tpl::showpage('store.edit');
    }

    /**
     * 店铺经营类目管理
     */
    public function store_bind_classOp()
    {
        $store_id = intval($_GET['store_id']);

        $model_store = Model('store');
        $model_store_bind_class = Model('store_bind_class');
        $model_goods_class = Model('goods_class');

        $gc_list = $model_goods_class->getClassList(array(
            'gc_parent_id' => '0'
        ));
        Tpl::output('gc_list', $gc_list);

        $store_info = $model_store->getStoreInfoByID($store_id);
        if (empty($store_info)) {
            showMessage(L('param_error'), '', '', 'error');
        }
        Tpl::output('store_info', $store_info);

        $store_bind_class_list = $model_store_bind_class->getStoreBindClassList(array(
            'store_id' => $store_id
        ), null);
        $goods_class = H('goods_class') ? H('goods_class') : H('goods_class', true);
        for ($i = 0, $j = count($store_bind_class_list); $i < $j; $i++) {
            $store_bind_class_list[$i]['class_1_name'] = $goods_class[$store_bind_class_list[$i]['class_1']]['gc_name'];
            $store_bind_class_list[$i]['class_2_name'] = $goods_class[$store_bind_class_list[$i]['class_2']]['gc_name'];
            $store_bind_class_list[$i]['class_3_name'] = $goods_class[$store_bind_class_list[$i]['class_3']]['gc_name'];
        }
        Tpl::output('store_bind_class_list', $store_bind_class_list);

        Tpl::showpage('store_bind_class');
    }


    /**
     * 20150824 tjz增加
     * 店铺经营类目审核
     */
    public function  category_apply_checkOp()
    {
        $model_store = Model('store');
        $model_store_bind_class = Model('store_bind_class');

        $store_bind_class_list = $model_store_bind_class->getStoreBindClassList(array(
            'status' => 1
        ), null);
        $goods_class = H('goods_class') ? H('goods_class') : H('goods_class', true);
        for ($i = 0, $j = count($store_bind_class_list); $i < $j; $i++) {
            $store_bind_class_list[$i]['class_1_name'] = $goods_class[$store_bind_class_list[$i]['class_1']]['gc_name'];
            $store_bind_class_list[$i]['class_2_name'] = $goods_class[$store_bind_class_list[$i]['class_2']]['gc_name'];
            $store_bind_class_list[$i]['class_3_name'] = $goods_class[$store_bind_class_list[$i]['class_3']]['gc_name'];
            $store_info = $model_store->getStoreInfoByID($store_bind_class_list[$i]['store_id']);
            $store_bind_class_list[$i]['store_name'] = $store_info['store_name'];
        }
        Tpl::output('store_bind_class_list', $store_bind_class_list);
        Tpl::showpage('store_category_apply_check_class');
    }

    /**
     * 20150825 sj增加
     * 合同审核
     */
    public function  agreement_checkOp()
    {
        $model_agreement_template = Model('agreement_template');
        $agreement_template_list = $model_agreement_template->getAgreementTemplateList(array(), null, null);
        foreach ($agreement_template_list as $val) {
            //企业
            if ($val["type"] == "0") {
                Tpl::output('agreement_template_0', $val);
            }
            //个体户
            if ($val["type"] == "1") {
                Tpl::output('agreement_template_1', $val);
            }
            //个人
            if ($val["type"] == "2") {
                Tpl::output('agreement_template_2', $val);
            }
        }
//       Tpl::output('agreement_template_list', $agreement_template_list);
        Tpl::showpage('store_agreement_check');
    }

    /**
     * 20150826 sj增加
     * 上传合同模板
     */
    public function upload_agreement_templateOp()
    {
        $param = array();
        $type = $_POST['type'];
        $param['file_name'] = $this->upload_file('file_name');
        if (empty($param['file_name'])) {
            showMessage('请上传文件大小20M以下的word格式合同模板上传', '', '', 'error');
        }
        $model_agreement_template = Model('agreement_template');
        $agreement_template_list = $model_agreement_template->getAgreementTemplateList(array(
            "type" => $type
        ), null, null);
        //根据type，判断是否存在，如果不存在执行新增，否则执行更新
        if (empty($agreement_template_list)) {
            $param['type'] = $type;
            $model_agreement_template->insertAgreementTemplate($param);
        } else {
            $model_agreement_template->updateAgreementTemplate($param, array(
                'type' => $type
            ));
        }
        showMessage('上传合同模板成功', '', '', 'succ');
    }

    private function upload_file($file)
    {
        //文件大小限制20M
        $file_size = 20;
        $file_name = '';
        $upload = new UploadFile();
        $uploaddir = DIR_ADMIN . DS . 'agreement_template' . DS;
        $upload->set('default_dir', $uploaddir);
        $upload->set('allow_type', array(
            'word'
        ));
        if (!empty($_FILES[$file]['name'])) {
            $result = $upload->upfile($file, $file_size);
            if ($result) {
                $file_name = $upload->file_name;
                $upload->file_name = '';
            }
        }
        return $file_name;
    }

    /**
     * 20150824tjz增加 审核分类
     */
    public function  category_updateOp()
    {
        $lang = Language::getLangContent();
        $bid = intval($_POST['bid']);
        $status = intval($_POST['status']);
        $update = array(
            'status' => $status
        );
        $condition = array(
            'bid' => $bid
        );
        $model_store_bind_class = Model('store_bind_class');
        $result = $model_store_bind_class->editStoreBindClass($update, $condition);
        if ($result) {
            $this->log('审核店铺经营类目，类目编号:' . $bid);
            showMessage($lang['store_category_apply_check_success'], '', '', 'succ');
        } else {
            showMessage($lang['store_category_apply_check_failed'], '', '', 'error');
        }

    }

    /**
     * 添加经营类目
     */
    public function store_bind_class_addOp()
    {
        $store_id = intval($_POST['store_id']);
        $commis_rate = intval($_POST['commis_rate']);
        if ($commis_rate < 0 || $commis_rate > 100) {
            showMessage(L('param_error'), '');
        }
        list ($class_1, $class_2, $class_3) = explode(',', $_POST['goods_class']);

        $model_store_bind_class = Model('store_bind_class');

        $param = array();
        $param['store_id'] = $store_id;
        $param['class_1'] = $class_1;
        if (!empty($class_2)) {
            $param['class_2'] = $class_2;
        }
        if (!empty($class_3)) {
            $param['class_3'] = $class_3;
        }

        // 检查类目是否已经存在
        $store_bind_class_info = $model_store_bind_class->getStoreBindClassInfo($param);
        if (!empty($store_bind_class_info)) {
            showMessage('该类目已经存在', '', '', 'error');
        }

        $param['commis_rate'] = $commis_rate;
        $result = $model_store_bind_class->addStoreBindClass($param);

        if ($result) {
            $this->log('添加店铺经营类目，类目编号:' . $result . ',店铺编号:' . $store_id);
            showMessage(L('nc_common_save_succ'), '');
        } else {
            showMessage(L('nc_common_save_fail'), '');
        }
    }

    /**
     * 删除经营类目
     */
    public function store_bind_class_delOp()
    {
        $bid = intval($_POST['bid']);

        $data = array();
        $data['result'] = true;

        $model_store_bind_class = Model('store_bind_class');
        $model_goods = Model('goods');

        $store_bind_class_info = $model_store_bind_class->getStoreBindClassInfo(array(
            'bid' => $bid
        ));
        if (empty($store_bind_class_info)) {
            $data['result'] = false;
            $data['message'] = '经营类目删除失败';
            echo json_encode($data);
            die();
        }

        // 商品下架
        $condition = array();
        $condition['store_id'] = $store_bind_class_info['store_id'];
        $gc_id = $store_bind_class_info['class_1'] . ',' . $store_bind_class_info['class_2'] . ',' . $store_bind_class_info['class_3'];
        $update = array();
        $update['goods_stateremark'] = '管理员删除经营类目';
        $condition['gc_id'] = array(
            'in',
            rtrim($gc_id, ',')
        );
        $model_goods->editProducesLockUp($update, $condition);

        $result = $model_store_bind_class->delStoreBindClass(array(
            'bid' => $bid
        ));

        if (!$result) {
            $data['result'] = false;
            $data['message'] = '经营类目删除失败';
        }
        $this->log('删除店铺经营类目，类目编号:' . $bid . ',店铺编号:' . $store_bind_class_info['store_id']);
        echo json_encode($data);
        die();
    }

    public function store_bind_class_updateOp()
    {
        $bid = intval($_GET['id']);
        if ($bid <= 0) {
            echo json_encode(array(
                'result' => FALSE,
                'message' => Language::get('param_error')
            ));
            die();
        }
        $new_commis_rate = intval($_GET['value']);
        if ($new_commis_rate < 0 || $new_commis_rate >= 100) {
            echo json_encode(array(
                'result' => FALSE,
                'message' => Language::get('param_error')
            ));
            die();
        } else {
            $update = array(
                'commis_rate' => $new_commis_rate
            );
            $condition = array(
                'bid' => $bid
            );
            $model_store_bind_class = Model('store_bind_class');
            $result = $model_store_bind_class->editStoreBindClass($update, $condition);
            if ($result) {
                $this->log('更新店铺经营类目，类目编号:' . $bid);
                echo json_encode(array(
                    'result' => TRUE
                ));
                die();
            } else {
                echo json_encode(array(
                    'result' => FALSE,
                    'message' => L('nc_common_op_fail')
                ));
                die();
            }
        }
    }

    /**
     * 店铺 待审核列表
     */
    public function store_joininOp()
    {
        // 店铺列表
        if (!empty($_GET['onwer_and_name'])) {
            $condition['member_name'] = array(
                'like',
                '%' . $_GET['owner_and_name'] . '%'
            );
        }
        if (!empty($_GET['store_name'])) {
            $condition['store_name'] = array(
                'like',
                '%' . $_GET['store_name'] . '%'
            );
        }
        if (!empty($_GET['grade_id']) && intval($_GET['grade_id']) > 0) {
            $condition['sg_id'] = $_GET['grade_id'];
        }
        if (!empty($_GET['joinin_state']) && intval($_GET['joinin_state']) > 0) {
            $condition['joinin_state'] = $_GET['joinin_state'];
        } else {
            $condition['joinin_state'] = array(
                'gt',
                0
            );
        }
        $model_store_joinin = Model('store_joinin');
        $store_list = $model_store_joinin->getList($condition, 10, 'joinin_state asc');
        Tpl::output('store_list', $store_list);
        Tpl::output('joinin_state_array', $this->get_store_joinin_state());

        // 店铺等级
        $model_grade = Model('store_grade');
        $grade_list = $model_grade->getGradeList();
        Tpl::output('grade_list', $grade_list);

        Tpl::output('page', $model_store_joinin->showpage('2'));
        Tpl::showpage('store_joinin');
    }

    private function get_store_joinin_state()
    {
        $joinin_state_array = array(
            STORE_JOIN_STATE_NEW => '新申请',
            STORE_JOIN_STATE_PAY => '已付款',
            STORE_JOIN_STATE_VERIFY_SUCCESS => '待付款',
            STORE_JOIN_STATE_VERIFY_FAIL => '审核失败',
            STORE_JOIN_STATE_PAY_FAIL => '付款审核失败',
            STORE_JOIN_STATE_FINAL => '开店成功'
        );
        return $joinin_state_array;
    }

    /**
     * 审核详细页
     */
    public function store_joinin_detailOp()
    {
        $model_store_joinin = Model('store_joinin');
        $joinin_detail = $model_store_joinin->getOne(array(
            'member_id' => $_GET['member_id']
        ));
        $joinin_detail_title = '查看';
        if (in_array(intval($joinin_detail['joinin_state']), array(
            STORE_JOIN_STATE_NEW,
            STORE_JOIN_STATE_PAY
        ))) {
            $joinin_detail_title = '审核';
        }
        Tpl::output('joinin_detail_title', $joinin_detail_title);
        Tpl::output('joinin_detail', $joinin_detail);
        Tpl::showpage('store_joinin.detail');
    }

    /**
     * 审核
     */
    public function store_joinin_verifyOp()
    {
        $model_store_joinin = Model('store_joinin');
        $joinin_detail = $model_store_joinin->getOne(array(
            'member_id' => $_POST['member_id']
        ));

        switch (intval($joinin_detail['joinin_state'])) {
            case STORE_JOIN_STATE_NEW:
                $this->store_joinin_verify_pass($joinin_detail);
                break;
            case STORE_JOIN_STATE_PAY:
                $this->store_joinin_verify_open($joinin_detail);
                break;
            default:
                showMessage('参数错误', '');
                break;
        }
    }

    private function store_joinin_verify_pass($joinin_detail)
    {
        $param = array();
        $param['joinin_state'] = $_POST['verify_type'] === 'pass' ? STORE_JOIN_STATE_VERIFY_SUCCESS : STORE_JOIN_STATE_VERIFY_FAIL;
        $param['joinin_message'] = $_POST['joinin_message'];
        $param['store_class_commis_rates'] = implode(',', $_POST['commis_rate']);
        $model_store_joinin = Model('store_joinin');
        $model_store_joinin->modify($param, array(
            'member_id' => $_POST['member_id']
        ));
        showMessage('店铺入驻申请审核完成', 'index.php?act=store&op=store_joinin');
    }

    private function store_joinin_verify_open($joinin_detail)
    {
        require_once(BASE_CORE_PATH . '/phpqrcode.php');

        $model_store_joinin = Model('store_joinin');
        $model_store = Model('store');
        $model_seller = Model('seller');

        // 验证卖家用户名是否已经存在
        if ($model_seller->isSellerExist(array(
            'seller_name' => $joinin_detail['seller_name']
        ))
        ) {
            showMessage('卖家用户名已存在', '');
        }

        $param = array();
        $param['joinin_state'] = $_POST['verify_type'] === 'pass' ? STORE_JOIN_STATE_FINAL : STORE_JOIN_STATE_PAY_FAIL;
        $param['joinin_message'] = $_POST['joinin_message'];
        $model_store_joinin->modify($param, array(
            'member_id' => $_POST['member_id']
        ));
        if ($_POST['verify_type'] === 'pass') {
            // 开店
            $shop_array = array();
            $shop_array['member_id'] = $joinin_detail['member_id'];
            $shop_array['member_name'] = $joinin_detail['member_name'];
            $shop_array['seller_name'] = $joinin_detail['seller_name'];
            $shop_array['grade_id'] = $joinin_detail['sg_id'];
            $shop_array['store_owner_card'] = '';
            $shop_array['store_name'] = $joinin_detail['store_name'];
            $shop_array['sc_id'] = $joinin_detail['sc_id'];
            $shop_array['store_company_name'] = $joinin_detail['company_name'];
            $shop_array['area_id'] = $_POST['area_id'];
            $shop_array['area_info'] = $joinin_detail['company_address'];
            $shop_array['store_address'] = $joinin_detail['company_address_detail'];
            $shop_array['store_zip'] = '';
            $shop_array['store_tel'] = '';
            $shop_array['store_zy'] = '';
            $shop_array['store_state'] = 1;
            $shop_array['store_time'] = time();
            $store_id = $model_store->addStore($shop_array);
            
            if ($store_id) {
                // 写入卖家帐号
                $seller_array = array();
                $seller_array['seller_name'] = $joinin_detail['seller_name'];
                $seller_array['member_id'] = $joinin_detail['member_id'];
                $seller_array['seller_group_id'] = 0;
                $seller_array['store_id'] = $store_id;
                $seller_array['is_admin'] = 1;
                $state = $model_seller->addSeller($seller_array);
            }
            
            if ($state) {
                // 添加相册默认
                $album_model = Model('album');
                $album_arr = array();
                $album_arr['aclass_name'] = Language::get('store_save_defaultalbumclass_name');
                $album_arr['store_id'] = $store_id;
                $album_arr['aclass_des'] = '';
                $album_arr['aclass_sort'] = '255';
                $album_arr['aclass_cover'] = '';
                $album_arr['upload_time'] = time();
                $album_arr['is_default'] = '1';
                $album_model->addClass($album_arr);

                $model = Model();
                // 插入店铺扩展表
                $model->table('store_extend')->insert(array(
                    'store_id' => $store_id
                ));
                $msg = Language::get('store_save_create_success') . ($store_grade['sg_confirm'] == 1 ? Language::get('store_save_waiting_for_review') : '');

                // 插入店铺绑定分类表
                $store_bind_class_array = array();
                $store_bind_class = unserialize($joinin_detail['store_class_ids']);
                $store_bind_commis_rates = explode(',', $joinin_detail['store_class_commis_rates']);
                for ($i = 0, $length = count($store_bind_class); $i < $length; $i++) {
                    list ($class1, $class2, $class3) = explode(',', $store_bind_class[$i]);
                    $store_bind_class_array[] = array(
                        'store_id' => $store_id,
                        'commis_rate' => $store_bind_commis_rates[$i],
                        'class_1' => $class1,
                        'class_2' => $class2,
                        'class_3' => $class3
                    );
                }
                $model_store_bind_class = Model('store_bind_class');
                $model_store_bind_class->addStoreBindClassAll($store_bind_class_array);

                $qrcode_url = urlShop('show_store', 'index', array(
                    'store_id' => $store_id
                ));

                if (!file_exists(BASE_ROOT_PATH . '/' . DIR_SHOP . '/qrcode/' . $store_id . '.png')) {
                    QRcode::png($qrcode_url, BASE_ROOT_PATH . '/' . DIR_SHOP . '/qrcode/' . $store_id . '.png', 3, 150, 10);
                }

                showMessage('店铺开店成功', 'index.php?act=store&op=store_joinin');
            } else {
                showMessage('店铺开店失败', 'index.php?act=store&op=store_joinin');
            }
        } else {
            showMessage('店铺开店拒绝', 'index.php?act=store&op=store_joinin');
        }
    }

    public function store_joinin_editOp()
    {
        $model_store = Model('store');
        $model_store_joinin = Model('store_joinin');

        if (chksubmit()) {

            $update_array = array();
            $update_array['company_name'] = trim($_POST['company_name']);
            $update_array['company_address'] = trim($_POST['company_address']);
            $update_array['company_address_detail'] = trim($_POST['company_address_detail']);
            $update_array['company_phone'] = trim($_POST['company_phone']);
            $update_array['contacts_phone'] = trim($_POST['contacts_phone']);
            $update_array['contacts_email'] = trim($_POST['contacts_email']);
            $update_array['company_employee_count'] = trim($_POST['company_employee_count']);
            $update_array['company_registered_capital'] = trim($_POST['company_registered_capital']);
            $update_array['contacts_name'] = trim($_POST['contacts_name']);
            $update_array['idcard_number'] = trim($_POST['idcard_number']);

            $idcard_electronic = $this->upload_image('idcard_electronic');
            if (!empty($idcard_electronic)) {
                $update_array['idcard_electronic'] = $idcard_electronic;
            }

            $update_array['business_licence_number'] = trim($_POST['business_licence_number']);
            $update_array['business_licence_address'] = trim($_POST['business_licence_address']);
            $update_array['business_licence_start'] = trim($_POST['business_licence_start']);
            $update_array['business_licence_end'] = trim($_POST['business_licence_end']);
            $update_array['business_sphere'] = trim($_POST['business_sphere']);

            $business_licence_number_electronic = $this->upload_image('business_licence_number_electronic');
            if (!empty($business_licence_number_electronic)) {
                $update_array['business_licence_number_electronic'] = $business_licence_number_electronic;
            }

            $update_array['organization_code'] = trim($_POST['organization_code']);

            $organization_code_electronic = $this->upload_image('organization_code_electronic');
            if (!empty($organization_code_electronic)) {
                $update_array['organization_code_electronic'] = $organization_code_electronic;
            }

            $update_array['tax_registration_certificate'] = trim($_POST['tax_registration_certificate']);

            $tax_registration_certificate_electronic = $this->upload_image('tax_registration_certificate_electronic');
            if (!empty($tax_registration_certificate_electronic)) {
                $update_array['tax_registration_certificate_electronic'] = $tax_registration_certificate_electronic;
            }

            $update_array['bank_account_name'] = trim($_POST['bank_account_name']);
            $update_array['bank_account_number'] = trim($_POST['bank_account_number']);
            $update_array['bank_name'] = trim($_POST['bank_name']);
            $update_array['bank_address'] = trim($_POST['bank_address']);
            $update_array['settlement_bank_account_name'] = trim($_POST['settlement_bank_account_name']);
            $update_array['settlement_bank_account_number'] = trim($_POST['settlement_bank_account_number']);
            $update_array['settlement_bank_name'] = trim($_POST['settlement_bank_name']);
            $update_array['settlement_bank_address'] = trim($_POST['settlement_bank_address']);

            $model_store_joinin = Model('store_joinin');
            $result = $model_store_joinin->modify($update_array, array(
                'member_id' => $_POST['member_id']
            ));

            if (!empty($_POST['member_id'])) {

                $update_array2['store_company_name'] = $update_array['company_name'];
                $update_array2['area_info'] = $update_array['company_address'];
                $update_array2['store_address'] = $update_array['company_address_detail'];

                $result2 = $model_store->editStore($update_array2, array(
                    'store_id' => $_POST['store_id']
                ));
            }

            if ($result) {
                $url = array(
                    array(
                        'url' => 'index.php?act=store&op=store',
                        'msg' => '返回列表'
                    ),
                    array(
                        'url' => 'index.php?act=store&op=store_joinin_edit&member_id=' . intval($_POST['member_id']),
                        'msg' => '继续修改'
                    )
                );

                showMessage('资料修改成功', $url);
            } else {
                showMessage('资料修改失败');
            }
        }

        $store_detail = $model_store->getStoreInfo(array(
            'member_id' => $_GET['member_id']
        ));
        $joinin_detail = $model_store_joinin->getOne(array(
            'member_id' => $_GET['member_id']
        ));

        $joinin_detail_title = '查看';
        if (in_array(intval($joinin_detail['joinin_state']), array(
            STORE_JOIN_STATE_NEW,
            STORE_JOIN_STATE_PAY
        ))) {
            $joinin_detail_title = '审核';
        }
        Tpl::output('joinin_detail_title', $joinin_detail_title);
        Tpl::output('joinin_detail', $joinin_detail);
        Tpl::output('store_detail', $store_detail);
        Tpl::showpage('store_joinin.edit');
    }

    private function upload_image($file)
    {
        $pic_name = '';
        $upload = new UploadFile();
        $uploaddir = ATTACH_PATH . DS . 'store_joinin' . DS;
        $upload->set('default_dir', $uploaddir);
        $upload->set('allow_type', array(
            'jpg',
            'jpeg',
            'gif',
            'png'
        ));

        if (!empty($_FILES[$file]['name'])) {
            $result = $upload->upfile($file);
            if ($result) {
                $pic_name = $upload->file_name;
                $upload->file_name = '';
            }
        }
        return $pic_name;
    }
}
