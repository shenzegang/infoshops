<?php
/**
 * 默认展示页面
 *
 *
 *
 * @copyright  Copyright (c) 2003-2015 B2B2C Inc. (http://www.infol.com.cn)
 * @license    http://www.infol.com.cn
 * @link       http://www.infol.com.cn
 * @since      File available since Release v1.1
 */
defined('CorShop') or exit('Access Invalid!');

class indexControl extends BaseHomeControl
{

    public function indexOp()
    {
        $model_mb_ad = Model('mb_ad');
        
        // 广告
        $adv_list = array();
        $mb_ad_list = $model_mb_ad->getMbAdList(array(), null, 'link_sort asc');
        foreach ($mb_ad_list as $value) {
            $adv = array();
            $adv['image'] = $value['link_pic_url'];
            $adv['keyword'] = $value['link_keyword'];
            $adv_list[] = $adv;
        }
        Tpl::output('adv_list', $adv_list);
        
        // 促销
        $goods = Model('goods');
        $top_list = $goods->getGoodsList(array(), '*', '', 'goods_click DESC', 3);
        foreach ($top_list as $key => $value) {
            $top_list[$key]['discount'] = intval(10 / ($value['goods_marketprice'] / $value['goods_price']));
        }
        Tpl::output('top_list', $top_list);
        
        $model_mb_category = Model('mb_category');
        $index_category = $model_mb_category->getLinkList(array());
        
        foreach ($index_category as $key => $val) {
            $param['table'] = 'goods_class';
            $param['field'] = 'gc_id';
            $param['value'] = intval($val['gc_id']);
            
            $index_category[$key]['cat'] = Db::getRow($param);
        }
        
        Tpl::output('index_category', $index_category);
        
        Model('seo')->type('index')->show();
        Tpl::showpage('index');
    }
}
