<?php
/**
 * 商品管理
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

class store_goods_offlineControl extends BaseSellerControl
{

    public function __construct()
    {
        parent::__construct();
        Language::read('member_store_goods_index');
    }

    public function indexOp()
    {
        $this->goods_storageOp();
    }

    /**
     * 仓库中的商品列表
     */
    public function goods_storageOp()
    {
        $model_goods = Model('goods');
        
        $where = array();
        $where['store_id'] = $_SESSION['store_id'];
        if (intval($_GET['stc_id']) > 0) {
            $where['goods_stcids'] = array(
                'like',
                '%' . intval($_GET['stc_id']) . '%'
            );
        }
        if (trim($_GET['keyword']) != '') {
            switch ($_GET['search_type']) {
                case 0:
                    $where['goods_name'] = array(
                        'like',
                        '%' . trim($_GET['keyword']) . '%'
                    );
                    break;
                case 1:
                    $where['goods_serial'] = array(
                        'like',
                        '%' . trim($_GET['keyword']) . '%'
                    );
                    break;
                case 2:
                    $where['goods_commonid'] = intval($_GET['keyword']);
                    break;
            }
        }
        
        switch ($_GET['type']) {
            // 违规的商品
            case 'lock_up':
                $this->profile_menu('goods_lockup');
                $goods_list = $model_goods->getGoodsCommonLockUpList($where);
                break;
            // 等待审核或审核失败的商品
            case 'wait_verify':
                $this->profile_menu('goods_verify');
                if (isset($_GET['verify']) && in_array($_GET['verify'], array(
                    '0',
                    '10'
                ))) {
                    $where['goods_verify'] = $_GET['verify'];
                }
                $goods_list = $model_goods->getGoodsCommonWaitVerifyList($where);
                break;
            // 仓库中的商品
            default:
                $this->profile_menu('goods_storage');
                $goods_list = $model_goods->getGoodsCommonOfflineList($where);
                break;
        }
        
        Tpl::output('show_page', $model_goods->showpage());
        Tpl::output('goods_list', $goods_list);
        
        // 计算库存
        $storage_array = $model_goods->calculateStorage($goods_list);
        Tpl::output('storage_array', $storage_array);
        
        // 商品分类
        $store_goods_class = Model('my_goods_class')->getClassTree(array(
            'store_id' => $_SESSION['store_id'],
            'stc_state' => '1'
        ));
        Tpl::output('store_goods_class', $store_goods_class);
        
        switch ($_GET['type']) {
            // 违规的商品
            case 'lock_up':
                Tpl::showpage('store_goods_list.offline_lockup');
                break;
            // 等待审核或审核失败的商品
            case 'wait_verify':
                Tpl::output('verify', array(
                    '0' => '未通过',
                    '10' => '等待审核'
                ));
                Tpl::showpage('store_goods_list.offline_waitverify');
                break;
            // 仓库中的商品
            default:
                Tpl::showpage('store_goods_list.offline');
                break;
        }
    }

    /**
     * 商品上架
     */
    public function goods_showOp()
    {
        $commonid = $_GET['commonid'];
        if (! preg_match('/^[\d,]+$/i', $commonid)) {
            showdialog(L('para_error'), '', 'error');
        }
        $commonid_array = explode(',', $commonid);
        if ($this->store_info['store_state'] != 1) {
            showdialog(L('store_goods_index_goods_show_fail') . '，店铺正在审核中或已经关闭', '', 'error');
        }
        $return = Model('goods')->editProducesOnline(array(
            'goods_commonid' => array(
                'in',
                $commonid_array
            ),
            'store_id' => $_SESSION['store_id']
        ));
        if ($return) {
            // 添加操作日志
            $this->recordSellerLog('商品上架，平台货号：' . $commonid);
            showdialog(L('store_goods_index_goods_show_success'), 'reload', 'succ');
        } else {
            showdialog(L('store_goods_index_goods_show_fail'), '', 'error');
        }
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
            array(
                'menu_key' => 'goods_storage',
                'menu_name' => L('nc_member_path_goods_storage'),
                'menu_url' => urlShop('store_goods_offline', 'index')
            ),
            array(
                'menu_key' => 'goods_lockup',
                'menu_name' => L('nc_member_path_goods_state'),
                'menu_url' => urlShop('store_goods_offline', 'index', array(
                    'type' => 'lock_up'
                ))
            ),
            array(
                'menu_key' => 'goods_verify',
                'menu_name' => L('nc_member_path_goods_verify'),
                'menu_url' => urlShop('store_goods_offline', 'index', array(
                    'type' => 'wait_verify'
                ))
            )
        );
        Tpl::output('member_menu', $menu_array);
        Tpl::output('menu_key', $menu_key);
    }

    /**
     * 编辑商品页面
     */
    public function edit_goodsOp()
    {
        $common_id = $_GET['commonid'];
        if ($common_id <= 0) {
            showMessage(L('wrong_argument'), '', 'html', 'error');
        }
        $model_goods = Model('goods');
        $where = array(
            'goods_commonid' => $common_id,
            'store_id' => $_SESSION['store_id']
        );
        $goodscommon_info = $model_goods->getGoodeCommonInfo($where);
        if (empty($goodscommon_info)) {
            showMessage(L('wrong_argument'), '', 'html', 'error');
        }

        $goodscommon_info['g_storage'] = $model_goods->getGoodsSum($where, 'goods_storage');
        $goodscommon_info['spec_name'] = unserialize($goodscommon_info['spec_name']);

        Tpl::output('goods', $goodscommon_info);

        if (intval($_GET['class_id']) > 0) {
            $goodscommon_info['gc_id'] = intval($_GET['class_id']);
        }
        $goods_class = Model('goods_class')->getGoodsClassLineForTag($goodscommon_info['gc_id']);
        Tpl::output('goods_class', $goods_class);

        $model_type = Model('type');
        // 获取类型相关数据
        if ($goods_class['type_id'] > 0) {
            $typeinfo = $model_type->getAttr($goods_class['type_id'], $_SESSION['store_id'], $goodscommon_info['gc_id']);
            list ($spec_json, $spec_list, $attr_list, $brand_list) = $typeinfo;
            Tpl::output('spec_json', $spec_json);
            Tpl::output('sign_i', count($spec_list));
            Tpl::output('spec_list', $spec_list);
            Tpl::output('attr_list', $attr_list);
            Tpl::output('brand_list', $brand_list);
        }

        // 取得商品规格的输入值
        $goods_array = $model_goods->getGoodsList($where, 'goods_id, goods_price,goods_storage,goods_serial,goods_spec');
        $sp_value = array();
        if (is_array($goods_array) && ! empty($goods_array)) {

            // 取得已选择了哪些商品的属性
            $attr_checked_l = $model_type->typeRelatedList('goods_attr_index', array(
                'goods_id' => intval($goods_array[0]['goods_id'])
            ), 'attr_value_id');
            if (is_array($attr_checked_l) && ! empty($attr_checked_l)) {
                $attr_checked = array();
                foreach ($attr_checked_l as $val) {
                    $attr_checked[] = $val['attr_value_id'];
                }
            }
            Tpl::output('attr_checked', $attr_checked);

            $spec_checked = array();
            foreach ($goods_array as $k => $v) {
                $a = unserialize($v['goods_spec']);
                if (! empty($a)) {
                    foreach ($a as $key => $val) {
                        $spec_checked[$key]['id'] = $key;
                        $spec_checked[$key]['name'] = $val;
                    }
                    $matchs = array_keys($a);
                    sort($matchs);
                    $id = str_replace(',', '', implode(',', $matchs));
                    $sp_value['i_' . $id . '|price'] = $v['goods_price'];
                    $sp_value['i_' . $id . '|id'] = $v['goods_id'];
                    $sp_value['i_' . $id . '|stock'] = $v['goods_storage'];
                    $sp_value['i_' . $id . '|sku'] = $v['goods_serial'];
                }
            }
            Tpl::output('spec_checked', $spec_checked);
        }
        Tpl::output('sp_value', $sp_value);

        // 实例化店铺商品分类模型
        $store_goods_class = Model('my_goods_class')->getClassTree(array(
            'store_id' => $_SESSION['store_id'],
            'stc_state' => '1'
        ));
        Tpl::output('store_goods_class', $store_goods_class);
        $goodscommon_info['goods_stcids'] = trim($goodscommon_info['goods_stcids'], ',');
        Tpl::output('store_class_goods', explode(',', $goodscommon_info['goods_stcids']));

        // 是否能使用编辑器
        if (checkPlatformStore()) { // 平台店铺可以使用编辑器
            $editor_multimedia = true;
        } else { // 三方店铺需要
            $editor_multimedia = false;
            if ($this->store_grade['sg_function'] == 'editor_multimedia') {
                $editor_multimedia = true;
            }
        }
        Tpl::output('editor_multimedia', $editor_multimedia);

        // 小时分钟显示
        $hour_array = array(
            '00',
            '01',
            '02',
            '03',
            '04',
            '05',
            '06',
            '07',
            '08',
            '09',
            '10',
            '11',
            '12',
            '13',
            '14',
            '15',
            '16',
            '17',
            '18',
            '19',
            '20',
            '21',
            '22',
            '23'
        );
        Tpl::output('hour_array', $hour_array);
        $minute_array = array(
            '05',
            '10',
            '15',
            '20',
            '25',
            '30',
            '35',
            '40',
            '45',
            '50',
            '55'
        );
        Tpl::output('minute_array', $minute_array);

        /**
         * 20150828 tjz增加 获取当前商品是否是定时上架
         */
        $model_cron = Model('cron');
        $condition['exeid'] = $common_id;
        $cron_info = $model_cron->getCronInfo($condition);
        if (!empty($cron_info)){
            $year= date('Y-m-d', $cron_info['exetime']);
            $hour= date('H', $cron_info['exetime']);
            $minute= date('i', $cron_info['exetime']);
            Tpl::output('cron_year', $year);
            Tpl::output('cron_hour', $hour);
            Tpl::output('cron_minute', $minute);

        }



        // 关联版式
        $plate_list = Model('store_plate')->getPlateList(array(
            'store_id' => $_SESSION['store_id']
        ), 'plate_id,plate_name,plate_position');
        $plate_list = array_under_reset($plate_list, 'plate_position', 2);
        Tpl::output('plate_list', $plate_list);

        $this->profile_menu('edit_detail', 'edit_detail');
        Tpl::output('edit_goods_sign', true);
        Tpl::showpage('store_goods_add.step2');
    }
}