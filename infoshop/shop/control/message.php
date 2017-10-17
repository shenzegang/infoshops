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

class messageControl extends BaseHomeControl
{

    private $templatestate_arr;

    public function __construct()
    {
        parent::__construct();
        
        Language::read('home_article_index');
        $lang = Language::getLangContent();
        
        $article_class_model = Model('article_class');
        
        // 面包屑
        $nav_link = array(
            array(
                'title' => $lang['homepage'],
                'link' => 'index.php'
            ),
            array(
                'title' => '留言反馈'
            )
        );
        Tpl::output('nav_link_list', $nav_link);
        
        /**
         * 左侧分类导航
         */
        $condition = array();
        $condition['ac_parent_id'] = $article_class['ac_id'];
        $sub_class_list = $article_class_model->getClassList($condition);
        if (empty($sub_class_list) || ! is_array($sub_class_list)) {
            $condition['ac_parent_id'] = $article_class['ac_parent_id'];
            $sub_class_list = $article_class_model->getClassList($condition);
        }
        Tpl::output('sub_class_list', $sub_class_list);
    }

    public function indexOp()
    {
        if (chksubmit()) {
            
            $insert['name'] = trim($_POST['name']);
            $insert['sex'] = intval($_POST['sex']);
            $insert['email'] = trim($_POST['email']);
            $insert['tel'] = trim($_POST['tel']);
            $insert['content'] = trim($_POST['content']);
            $insert['add_time'] = time();
            
            if (Db::insert('feedback', $insert)) {
                showMessage('留言提交成功！', 'index.php?act=message&op=index');
            } else {
                showMessage('留言提交失败，请重试！', 'index.php?act=message&op=index', '', 'error');
            }
        }
        
        Tpl::showpage('message');
    }
}
