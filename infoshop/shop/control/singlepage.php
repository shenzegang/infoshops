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

class singlepageControl extends BaseHomeControl
{

    private $templatestate_arr;

    public function __construct()
    {
        parent::__construct();
        
        Language::read('home_article_index');
        $lang = Language::getLangContent();
        
        $article_class_model = Model('article_class');
        
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

    public function linkOp()
    {
        
        // 面包屑
        $nav_link = array(
            array(
                'title' => $lang['homepage'],
                'link' => 'index.php'
            ),
            array(
                'title' => '友情链接'
            )
        );
        Tpl::output('nav_link_list', $nav_link);
        
        $model_link = Model('link');
        $link_list = $model_link->getLinkList($condition, $page);
        
        if (is_array($link_list)) {
            foreach ($link_list as $k => $v) {
                if (! empty($v['link_pic'])) {
                    $link_list[$k]['link_pic'] = UPLOAD_SITE_URL . '/' . ATTACH_PATH . '/common/' . DS . $v['link_pic'];
                }
            }
        }
        
        Tpl::output('link_list', $link_list);
        
        Tpl::showpage('link');
    }
}
