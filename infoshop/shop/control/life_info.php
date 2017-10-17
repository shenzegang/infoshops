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

class life_infoControl extends BaseHomeControl
{

    private $templatestate_arr;

    public function __construct()
    {
        parent::__construct();
        Tpl::setLayout('life_layout');
    }

    public function listOp()
    {
        $id = intval($_GET['id']);
        $keyword = trim($_GET['keyword']);
        
        Tpl::output('keyword', $keyword);
        
        $life_info = Model('life_info');
        
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
                'title' => '便民电话'
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
        
        $where = ' is_show = 1 AND start_date <= ' . time() . ' AND end_date >= ' . time();
        
        if (! empty($keyword)) {
            $where .= " AND (title LIKE '%" . $keyword . "%' OR content LIKE '%" . $keyword . "%')";
        }
        
        $condition['where'] = $where;
        
        $list = $life_info->getList($condition, $page);
        Tpl::output('list', $list);
        Tpl::output('show_page', $page->show());
        
        Tpl::showpage('life_info');
    }

    public function viewOp()
    {
        $id = intval($_GET['id']);
        
        $life_ask = Model('life_ask');
        $life_article_class = Model('life_article_class');
        
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
        
        // 热门文章
        $hot = $life_ask->getList(array(
            'limit' => 10,
            'order' => 'click DESC',
            'where' => $where
        ));
        Tpl::output('hot', $hot);
        
        // 推荐文章
        $best = $life_ask->getList(array(
            'limit' => 6,
            'where' => $where . " AND is_hot = 1 "
        ));
        Tpl::output('best', $best);
        
        Tpl::showpage('life_info_view');
    }
}
