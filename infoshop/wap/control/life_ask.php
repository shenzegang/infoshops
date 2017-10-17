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

class life_askControl extends BaseHomeControl
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
        
        $life_ask = Model('life_ask');
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
                'title' => '问吧'
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
        
        $where = ' is_show = 1 ';
        
        if (! empty($keyword)) {
            $condition['where'] = $where . " AND title LIKE '%" . $keyword . "%'";
        }
        
        $list = $life_ask->getList($condition, $page);
        Tpl::output('list', $list);
        Tpl::output('show_page', $page->show(1));
        
        Tpl::showpage('life_ask');
    }

    public function viewOp()
    {
        $id = intval($_GET['id']);
        
        $life_ask = Model('life_ask');
        $life_article_class = Model('life_article_class');
        
        Db::update('life_ask', array(
            'click' => array(
                'sign' => 'increase',
                'value' => 1
            )
        ), 'id = ' . $id);
        
        $info = $life_ask->getOne($id);
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
                'title' => '问吧'
            )
        );
        Tpl::output('nav_link_list', $nav_link);
        
        Tpl::showpage('life_ask_view');
    }
}
