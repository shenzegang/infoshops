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
        
        // 杂志
        $magazine = $life_magazine->getList(array(
            'limit' => 5,
            'where' => ' AND is_show = 1'
        ));
        Tpl::output('magazine', $magazine);
        
        // 分类
        $class = $life_article_class->getClassList(array());
        foreach ($class as $key => $val) {
            $class_list[$val['ac_id']] = $val;
        }
        Tpl::output('class_list', $class_list);
        
        // 汇生活区块
        $area = $life_article_class->getClassList(array(
            'where' => ' AND ac_parent_id = 0'
        ));
        foreach ($area as $k => $v) {
            
            $son = $life_article_class->getChildClass($v['ac_id']);
            $son_list = array();
            foreach ($son as $k1 => $v1) {
                $son_list[] = $v1['ac_id'];
            }
            
            $son_list = empty($son_list) ? 0 : join(',', $son_list);
            
            $area[$k]['list'] = $life_article->getList(array(
                'limit' => 6,
                'where' => ' AND article_show = 1 AND ac_id in (' . $son_list . ')'
            ));
            $area[$k]['best'] = $life_article->getList(array(
                'limit' => 2,
                'where' => ' AND is_best = 1 AND article_show = 1 AND ac_id in (' . $son_list . ')'
            ));
        }
        Tpl::output('area', $area);
        
        // 便民电话
        $life_info = $life_info->getList(array(
            'limit' => 6,
            'where' => 'start_date <= ' . time() . ' AND end_date >= ' . time()
        ));
        Tpl::output('life_info', $life_info);
        
        Tpl::showpage('life');
    }
}
