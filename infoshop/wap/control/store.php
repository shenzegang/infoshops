<?php
/**
 * 微商城店铺街
 *
 *
 *
 * @copyright  Copyright (c) 2003-2015 B2B2C Inc. (http://www.infol.com.cn)
 * @license    http://www.infol.com.cn
 * @link       http://www.infol.com.cn
 * @since      File available since Release v1.1
 */
defined('CorShop') or exit('Access Invalid!');

class storeControl extends BaseHomeControl
{

    public function __construct()
    {
        parent::__construct();
        Tpl::output('index_sign', 'store');
    }

    public function indexOp()
    {
        $this->store_listOp();
    }

    /**
     * 店铺列表
     */
    public function store_listOp()
    {
        $model_store = Model('store');
        $model_microshop_store = Model('micro_store');
        $condition = array();
        if (! empty($_GET['keyword'])) {
            $condition['store_name'] = array(
                'like',
                '%' . trim($_GET['keyword']) . '%'
            );
        }
        $store_list = $model_microshop_store->getListWithStoreInfo($condition, 30, 'microshop_sort asc');
        Tpl::output('list', $store_list);
        Tpl::output('show_page', $model_store->showpage(2));
        
        Tpl::output('html_title', Language::get('nc_microshop_store') . '-' . Language::get('nc_microshop') . '-' . C('site_name'));
        Tpl::showpage('store_list');
    }

    /**
     * 店铺详细页
     */
    public function detailOp()
    {
        $store_id = intval($_GET['store_id']);
        if ($store_id <= 0) {
            header('location: ' . MICROSHOP_SITE_URL);
            die();
        }
        $model_store = Model('store');
        $model_goods = Model('goods');
        $model_microshop_store = Model('micro_store');
        
        $store_info = $model_microshop_store->getOneWithStoreInfo(array(
            'microshop_store_id' => $store_id
        ));
        if (empty($store_info)) {
            header('location: ' . MICROSHOP_SITE_URL);
        }
        
        // 点击数加1
        $update = array();
        $update['click_count'] = array(
            'exp',
            'click_count+1'
        );
        $model_microshop_store->modify($update, array(
            'microshop_store_id' => $store_id
        ));
        
        Tpl::output('detail', $store_info);
        
        $condition = array();
        $condition['store_id'] = $store_info['shop_store_id'];
        $goods_list = $model_goods->getGoodsListByColorDistinct($condition, 'goods_id,goods_commonid,goods_name,goods_jingle,store_id,store_name,goods_price,goods_marketprice,goods_storage,goods_image,goods_freight,goods_salenum,color_id,evaluation_good_star,evaluation_count', 'goods_id asc', 39);
        // 商品多图
        if (! empty($goods_list)) {
            $goodsid_array = array(); // 商品id数组
            $commonid_array = array(); // 商品公共id数组
            $storeid_array = array(); // 店铺id数组
            foreach ($goods_list as $value) {
                $goodsid_array[] = $value['goods_id'];
                $commonid_array[] = $value['goods_commonid'];
                $storeid_array[] = $value['store_id'];
            }
            $goodsid_array = array_unique($goodsid_array);
            $commonid_array = array_unique($commonid_array);
            $storeid_array = array_unique($storeid_array);
            // 商品多图
            $goodsimage_more = $model_goods->getGoodsImageList(array(
                'goods_commonid' => array(
                    'in',
                    $commonid_array
                )
            ));
            // 店铺
            $store_list = Model('store')->getStoreMemberIDList($storeid_array);
            // 团购
            $groupbuy_list = Model('groupbuy')->getGroupbuyListByGoodsCommonIDString(implode(',', $commonid_array));
            // 限时折扣
            $xianshi_list = Model('p_xianshi_goods')->getXianshiGoodsListByGoodsString(implode(',', $goodsid_array));
            foreach ($goods_list as $key => $value) {
                // 商品多图
                foreach ($goodsimage_more as $v) {
                    if ($value['goods_commonid'] == $v['goods_commonid'] && $value['store_id'] == $v['store_id'] && $value['color_id'] == $v['color_id']) {
                        $goods_list[$key]['image'][] = $v;
                    }
                }
                // 店铺的开店会员编号
                $store_id = $value['store_id'];
                $goods_list[$key]['member_id'] = $store_list[$store_id]['member_id'];
                $goods_list[$key]['store_domain'] = $store_list[$store_id]['store_domain'];
                // 团购
                if (isset($groupbuy_list[$value['goods_commonid']])) {
                    $goods_list[$key]['goods_price'] = $groupbuy_list[$value['goods_commonid']]['groupbuy_price'];
                    $goods_list[$key]['group_flag'] = true;
                }
                if (isset($xianshi_list[$value['goods_id']]) && ! $goods_list[$key]['group_flag']) {
                    $goods_list[$key]['goods_price'] = $xianshi_list[$value['goods_id']]['xianshi_price'];
                    $goods_list[$key]['xianshi_flag'] = true;
                }
            }
        }
        
        Tpl::output('comment_type', 'store');
        Tpl::output('comment_id', $store_id);
        Tpl::output('list', $goods_list);
        Tpl::output('show_page', $model_goods->showpage());
        
        Tpl::output('html_title', $store_info['store_name'] . '-' . Language::get('nc_microshop_store') . '-' . Language::get('nc_microshop') . '-' . C('site_name'));
        Tpl::showpage('store_detail');
    }
}
