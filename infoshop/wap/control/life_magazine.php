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

class life_magazineControl extends BaseHomeControl
{

    private $templatestate_arr;

    public function __construct()
    {
        parent::__construct();
    }

    public function listOp()
    {
        $id = intval($_GET['id']);
        
        $life_tag = Model('life_tag');
        $life_article = Model('life_article');
        $life_article_class = Model('life_article_class');
        $life_magazine = Model('life_magazine');
        
        // 面包屑
        $nav_link = array(
            array(
                'title' => '首页',
                'link' => 'index.php'
            ),
            array(
                'title' => '汇生活',
                'link' => 'index.php?act=life&op=index'
            ),
            array(
                'title' => '杂志'
            )
        );
        Tpl::output('nav_link_list', $nav_link);
        
        $page = new Page();
        $page->setEachNum(16);
        $page->setStyle('admin');
        
        $condition['where'] = ' AND is_show = 1 ';
        
        $list = $life_magazine->getList($condition, $page);
        Tpl::output('list', $list);
        Tpl::output('show_page', $page->show(1));
        
        // TAG
        $tag = $life_tag->getList(array(
            'limit' => 20,
            'where' => ' AND is_hot = 1 '
        ));
        Tpl::output('tag', $tag);
        
        $son = $life_article_class->getChildClass(1);
        $son_list = array();
        foreach ($son as $k => $v) {
            $son_list[] = $v['ac_id'];
        }
        
        // 热门文章
        $hot = $life_article->getList(array(
            'limit' => 10,
            'order' => 'click DESC',
            'where' => " AND ac_id IN (" . join(',', $son_list) . ")"
        ));
        Tpl::output('hot', $hot);
        
        // 推荐文章
        $best = $life_article->getList(array(
            'limit' => 6,
            'where' => " AND is_best = 1 AND ac_id IN (" . join(',', $son_list) . ")"
        ));
        Tpl::output('best', $best);
        
        Tpl::showpage('life_magazine');
    }

    public function viewOp()
    {
        $id = intval($_GET['id']);
        
        $life_tag = Model('life_tag');
        $life_article = Model('life_article');
        $life_article_class = Model('life_article_class');
        $life_magazine = Model('life_magazine');
        
        Db::update('life_magazine', array(
            'click' => array(
                'sign' => 'increase',
                'value' => 1
            )
        ), 'id = ' . $id);
        
        $info = $life_magazine->getOne($id);
        Tpl::output('info', $info);
        Tpl::output('html_title', $info['title'] . ' - ' . C('site_name'));
        
        // 面包屑
        $nav_link = array(
            array(
                'title' => '首页',
                'link' => 'index.php'
            ),
            array(
                'title' => '汇生活',
                'link' => 'index.php?act=life&op=index'
            ),
            array(
                'title' => '杂志'
            )
        );
        Tpl::output('nav_link_list', $nav_link);
        
        // TAG
        $tag = $life_tag->getList(array(
            'limit' => 20,
            'where' => ' AND is_hot = 1 '
        ));
        Tpl::output('tag', $tag);
        
        $son = $life_article_class->getChildClass(1);
        $son_list = array();
        foreach ($son as $k => $v) {
            $son_list[] = $v['ac_id'];
        }
        // 热门文章
        $hot = $life_article->getList(array(
            'limit' => 10,
            'order' => 'click DESC',
            'where' => " AND ac_id IN (" . join(',', $son_list) . ")"
        ));
        Tpl::output('hot', $hot);
        
        // 推荐文章
        $best = $life_article->getList(array(
            'limit' => 6,
            'where' => " AND is_best = 1 AND ac_id IN (" . join(',', $son_list) . ")"
        ));
        Tpl::output('best', $best);
        
        Tpl::showpage('life_magazine_view');
    }
}
