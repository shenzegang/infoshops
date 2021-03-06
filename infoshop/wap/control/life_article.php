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

class life_articleControl extends BaseHomeControl
{

    private $templatestate_arr;

    public function __construct()
    {
        parent::__construct();
    }

    public function listOp()
    {
        $id = intval($_GET['id']);
        $keyword = trim($_GET['keyword']);
        
        Tpl::output('keyword', $keyword);
        
        $life_tag = Model('life_tag');
        $life_article = Model('life_article');
        $life_article_class = Model('life_article_class');
        
        $class_info = $life_article_class->getOneClass($id);
        
        // 面包屑
        $nav_link = array(
            array(
                'title' => '首页',
                'link' => 'index.php'
            ),
            array(
                'title' => '汇生活',
                'link' => 'index.php?act=life&op=index'
            )
        );
        
        if (empty($keyword)) {
            $nav_link[] = array(
                'title' => $class_info['ac_name']
            );
        } else {
            $nav_link[] = array(
                'title' => '搜素与 “' . $keyword . '” 相关的信息'
            );
        }
        
        $title = end($nav_link);
        
        Tpl::output('html_title', C('site_name') . ' - ' . $title['title']);
        Tpl::output('nav_link_list', $nav_link);
        
        $page = new Page();
        $page->setEachNum(10);
        $page->setStyle('admin');
        
        if (empty($id)) {
            $where = 'article_show = 1 ';
        } else {
            
            $son = $life_article_class->getChildClass($id);
            $son_list = array();
            foreach ($son as $k => $v) {
                $son_list[] = $v['ac_id'];
            }
            $where = 'article_show = 1 AND ac_id IN (' . join(',', $son_list) . ')';
        }
        
        $info = $life_article_class->getOneClass($id);
        if (! empty($info['ac_parent_id'])) {
            $son = $life_article_class->getChildClass($info['ac_parent_id']);
        }
        Tpl::output('info', $info);
        Tpl::output('son_list', $son);
        
        if (! empty($keyword)) {
            $where .= " AND article_title LIKE '%" . $keyword . "%'";
        }
        
        $condition['where'] = $where;
        
        $list = $life_article->getList($condition, $page);
        Tpl::output('list', $list);
        
        Tpl::output('show_page', $page->show(1));
        
        Tpl::showpage('life_article');
    }

    public function viewOp()
    {
        $id = intval($_GET['id']);
        
        $life_tag = Model('life_tag');
        $life_article = Model('life_article');
        $life_article_class = Model('life_article_class');
        
        Db::update('life_article', array(
            'click' => array(
                'sign' => 'increase',
                'value' => 1
            )
        ), 'article_id = ' . $id);
        
        $info = $life_article->getOne($id);
        $class_info = $life_article_class->getOneClass($info['ac_id']);
        Tpl::output('info', $info);
        Tpl::output('class_info', $class_info);
        Tpl::output('html_title', $info['article_title'] . ' - ' . C('site_name'));
        
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
                'title' => $class_info['ac_name']
            )
        );
        Tpl::output('nav_link_list', $nav_link);
        
        Tpl::showpage('life_article_view');
    }
}
