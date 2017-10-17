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

class life_voteControl extends BaseHomeControl
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
        
        $life_vote = Model('life_vote');
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
                'title' => '投票'
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
        
        $where = ' is_show = 1 ';
        
        if (! empty($keyword)) {
            $condition['where'] = $where . " AND title LIKE '%" . $keyword . "%'";
        }
        
        $list = $life_vote->getList($condition, $page);
        Tpl::output('list', $list);
        Tpl::output('show_page', $page->show());
        
        // 热门投票
        $hot = $life_vote->getList(array(
            'limit' => 10,
            'where' => $where . " AND is_hot = 1 "
        ));
        Tpl::output('hot', $hot);
        
        // 推荐投票
        $best = $life_vote->getList(array(
            'limit' => 6,
            'where' => $where . " AND is_best = 1 "
        ));
        Tpl::output('best', $best);
        
        Tpl::showpage('life_vote');
    }

    public function viewOp()
    {
        $id = intval($_GET['id']);
        
        $life_vote = Model('life_vote');
        $life_article_class = Model('life_article_class');
        
        $info = $life_vote->getOne($id);
        $param['table'] = 'life_vote_item';
        $param['where'] = ' AND vid = ' . $info['id'];
        $param['order'] = 'sort asc';
        $item_list = Db::select($param);
        
        Tpl::output('item_list', $item_list);
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
                'title' => '投票'
            )
        );
        Tpl::output('nav_link_list', $nav_link);
        
        // 热门投票
        $hot = $life_vote->getList(array(
            'limit' => 10,
            'where' => $where . " AND is_hot = 1 "
        ));
        Tpl::output('hot', $hot);
        
        // 推荐投票
        $best = $life_vote->getList(array(
            'limit' => 10,
            'where' => $where . " AND is_best = 1 "
        ));
        Tpl::output('best', $best);
        
        Tpl::showpage('life_vote_view');
    }

    public function voteOp()
    {
        $id = intval($_GET['id']);
        
        $result = array(
            'error' => 1
        );
        
        $param['table'] = 'life_vote_item';
        $param['field'] = 'id';
        $param['value'] = $id;
        $item = Db::getRow($param);
        
        if (empty($item)) {
            $result['error'] = - 1;
            $result['msg'] = '投票选项不存在！';
        } else {
            $life_vote = Model('life_vote');
            $info = $life_vote->getOne($item['vid']);
            
            if (empty($info)) {
                $result['error'] = - 1;
                $result['msg'] = '投票选项不存在！';
            }
            
            $vote_record = cookie('vote_list');
            if (empty($vote_record)) {
                $vote_record = array();
            } else {
                $vote_record = explode('|', $vote_record);
            }
            
            if (in_array($info['id'], $vote_record)) {
                $result['error'] = - 1;
                $result['msg'] = '您已经投票过了！';
            } else {
                Db::update('life_vote_item', array(
                    'num' => $item['num'] + 1
                ), ' id = ' . $id);
                
                $vote_record[] = $info['id'];
                $vote_record = array_unique($vote_record);
                
                setNcCookie('vote_list', join('|', $vote_record));
            }
        }
        
        echo json_encode($result);
        exit();
    }
}
