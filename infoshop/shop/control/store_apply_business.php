<?php
/**
 * 申请经营类目
 *20150824 tjz 新增
 * 
 *
 *
 * @copyright  Copyright (c) 2003-2015 B2B2C Inc. (http://www.infol.com.cn)
 * @license    http://www.infol.com.cn
 * @link       http://www.infol.com.cn
 * @since      File available since Release v1.1
 */
defined('CorShop') or exit('Access Invalid!');

class store_apply_businessControl extends BaseSellerControl
{

    public function __construct()
    {
        parent::__construct();
        Language::read('member_store_brand');
        $lang = Language::getLangContent();
    }

    /**
     * 店铺经营类目
     */
    public function category_listOp()
    {
        $store_id = "{$_SESSION['store_id']}";
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
        for ($i = 0, $j = count($store_bind_class_list); $i < $j; $i ++) {
            $store_bind_class_list[$i]['class_1_name'] = $goods_class[$store_bind_class_list[$i]['class_1']]['gc_name'];
            $store_bind_class_list[$i]['class_2_name'] = $goods_class[$store_bind_class_list[$i]['class_2']]['gc_name'];
            $store_bind_class_list[$i]['class_3_name'] = $goods_class[$store_bind_class_list[$i]['class_3']]['gc_name'];
        }
        Tpl::output('store_bind_class_list', $store_bind_class_list);

        Tpl::showpage('store_apply_business_category.list');
    }


    /**
     * 申请经营类目页面
     */
    public function  category_addOp(){
        $lang = Language::getLangContent();
        $model_goods_class = Model('goods_class');
        $gc_list = $model_goods_class->getClassList(array(
            'gc_parent_id' => '0'
        ));
        Tpl::output('gc_list', $gc_list);
        Tpl::showpage('store_category.add', 'null_layout');
    }

    /**
     * 提交申请
     */
    public  function  category_applyOp(){
        $lang = Language::getLangContent();
        $model_store = Model('store');
        $store_id = "{$_SESSION['store_id']}";
        $model_store_bind_class = Model('store_bind_class');
        list ($class_1, $class_2, $class_3) = explode(',', $_POST['goods_class']);
        $param = array();
        $param['store_id'] = $store_id;
        $param['class_1'] = $class_1;
        if (! empty($class_2)) {
            $param['class_2'] = $class_2;
        }
        if (! empty($class_3)) {
            $param['class_3'] = $class_3;
        }
        // 检查类目是否已经存在
        $store_bind_class_info = $model_store_bind_class->getStoreBindClassInfo($param);
        if (! empty($store_bind_class_info)) {
            showMessage('该类目已经存在', '', '', 'error');
        }
        $param['commis_rate'] = 0;
        $param['status'] = 1;
        $result = $model_store_bind_class->addStoreBindClass($param);
        if ($result) {
            showMessage($lang['store_apply_business_category_success'], '');
        } else {
            showMessage($lang['store_apply_business_category_failed'], '');
        }
    }

}