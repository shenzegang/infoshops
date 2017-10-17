<?php
/**
 * 哈金豆礼品
 *
 * @copyright  Copyright (c) 2003-2015 B2B2C Inc. (http://www.infol.com.cn)
 * @license    http://www.infol.com.cn
 * @link       http://www.infol.com.cn
 * @since      File available since Release v1.1
 */
defined('CorShop') or exit('Access Invalid!');

class lifeControl extends BaseHomeControl
{

    private $templatestate_arr;

    public function __construct()
    {
        parent::__construct();
        Tpl::setLayout('life_layout');
    }

    public function indexOp()
    {
        Tpl::output('html_title', C('site_name') . ' - 汇生活');
        
        $life_article = Model('life_article');
        $life_article_class = Model('life_article_class');
        $life_ask = Model('life_ask');
        $life_info = Model('life_info');
        $life_magazine = Model('life_magazine');
        $life_vote = Model('life_vote');
        
        // 新鲜头条
        $new = $life_article->getList(array(
            'limit' => 3,
            'where' => ' AND is_head = 1 AND article_show = 1'
        ));
        Tpl::output('new', $new);
        
        // 投票
        $vote_list = $life_vote->getList(array(
            'limit' => 2,
            'where' => ' AND is_show = 1 AND is_best = 1'
        ));
        foreach ($vote_list as $k => $v) {
            $param['table'] = 'life_vote_item';
            $param['where'] = ' AND vid = ' . $v['id'];
            $param['order'] = 'sort asc';
            $vote_list[$k]['list'] = Db::select($param);
        }
        Tpl::output('vote_list', $vote_list);
        
        // 杂志
        $magazine = $life_magazine->getList(array(
            'limit' => 5,
            'where' => ' AND is_show = 1'
        ));
        Tpl::output('magazine', $magazine);
        
        // 汇生活最新
        $son = $life_article_class->getChildClass(1);
        $son_list = array();
        foreach ($son as $k => $v) {
            $son_list[] = $v['ac_id'];
        }
        $hui_pic = $life_article->getList(array(
            'limit' => 1,
            'order' => 'click DESC',
            'where' => " AND thumb !='' AND ac_id IN (" . join(',', $son_list) . ")"
        ));
        Tpl::output('hui_pic', $hui_pic);
        
        $pic_id = intval($hui_pic[0]['article_id']);
        
        $hui_new = $life_article->getList(array(
            'limit' => 5,
            'order' => 'click DESC',
            'where' => ' AND article_id != ' . $pic_id . ' AND ac_id IN (' . join(',', $son_list) . ')'
        ));
        Tpl::output('hui_new', $hui_new);
        
        // 汇生活区块
        $area = $life_article_class->getClassList(array(
            'where' => ' AND ac_parent_id = 1'
        ));
        foreach ($area as $k => $v) {
            $area[$k]['best'] = $life_article->getList(array(
                'limit' => 1,
                'where' => ' AND is_best = 1 AND article_show = 1 AND ac_id = ' . $v['ac_id']
            ));
            $area[$k]['new'] = $life_article->getList(array(
                'limit' => 3,
                'where' => ' AND article_show = 1 AND ac_id = ' . $v['ac_id']
            ));
        }
        Tpl::output('area', $area);
        
        // 便民资讯
        $son = $life_article_class->getChildClass(2);
        $son_list = array();
        foreach ($son as $k => $v) {
            $son_list[] = $v['ac_id'];
        }
        $news = $life_article->getList(array(
            'limit' => 10,
            'where' => " AND ac_id IN (" . join(',', $son_list) . ")"
        ));
        Tpl::output('news', $news);
        
        // 微商城
        $model_goods_class = Model('micro_goods_class');
        $goods_class_list = $model_goods_class->getList(TRUE, NULL, 'class_sort asc');
        $class_list = array();
        foreach ($goods_class_list as $k => $v) {
            if (empty($v['class_parent_id'])) {
                $class_list[$v['class_id']] = $v;
            }
        }
        foreach ($goods_class_list as $k => $v) {
            if (! empty($v['class_parent_id'])) {
                $class_list[$v['class_parent_id']]['son'][] = $v;
            }
        }
        $model_microshop_goods = Model('micro_goods');
        $order = 'microshop_sort asc,commend_time desc';
        foreach ($class_list as $k => $v) {
            if (empty($v['son'])) {
                $condition['class_id'] = $v['class_id'];
            } else {
                $son = array();
                foreach ($v['son'] as $v1) {
                    $son[] = $v1['class_id'];
                }
                $condition['class_id'] = array(
                    'in',
                    $v['class_id'] . ',' . join(',', $son)
                );
            }
            $class_list[$k]['list'] = $model_microshop_goods->getListWithUserInfo($condition, null, '', '*', 3);
        }
        Tpl::output('class_list', $class_list);
        
        // 店铺街
        $model_micro_store = Model('micro_store');
        $micro_top = $model_micro_store->getListWithStoreInfo(array(), null, 'click_count desc', '*', 8);
        Tpl::output('micro_top', $micro_top);
        
        // 便民电话
        $life_info = $life_info->getList(array(
            'limit' => 6,
            'where' => 'start_date <= ' . time() . ' AND end_date >= ' . time()
        ));
        Tpl::output('life_info', $life_info);
        
        Tpl::showpage('life');
    }
}
