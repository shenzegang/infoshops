<?php
/**
 * 文章管理
 *
 * 
 *
 *
 * @copyright  Copyright (c) 2003-2015 B2B2C Inc. (http://www.infol.com.cn)
 * @license    http://www.infol.com.cn
 * @link       http://www.infol.com.cn
 * @since      File available since Release v1.1
 */
defined('CorShop') or exit('Access Invalid!');

class feedbackControl extends SystemControl
{

    public function __construct()
    {
        parent::__construct();
        Language::read('article');
    }

    /**
     * 文章管理
     */
    public function listOp()
    {
        $param = array();
        
        if (! empty($_GET['keywords'])) {
            $param['where'] = "`name` LIKE  '%" . $_GET['keywords'] . "%' OR `email` LIKE '%" . $_GET['keywords'] . "%' OR `tel` LIKE '%" . $_GET['keywords'] . "%' OR `content` LIKE '%" . $_GET['keywords'] . "%'";
        }
        
        /**
         * 分页
         */
        $page = new Page();
        $page->setEachNum(10);
        $page->setStyle('admin');
        
        /**
         * 列表
         */
        $param['table'] = 'feedback';
        $param['limit'] = $page;
        $param['order'] = 'id DESC';
        $list = Db::select($param, $page);
        
        Tpl::output('list', $list);
        Tpl::output('page', $page->show());
        Tpl::output('keywords', trim($_GET['keywords']));
        Tpl::showpage('feedback.list');
    }

    public function delOp()
    {
        $id = intval($_GET['id']);
        
        Db::delete('feedback', 'id = ' . $id);
        showMessage('留言删除成功！');
    }
}