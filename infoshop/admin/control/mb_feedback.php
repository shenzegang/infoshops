<?php
/**
 * 合作伙伴管理
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

class mb_feedbackControl extends SystemControl
{

    public function __construct()
    {
        parent::__construct();
        Language::read('mobile');
    }

    /**
     * 意见反馈
     */
    public function flistOp()
    {
        $lang = Language::getLangContent();
        $model_link = Model('mb_feedback');
        /**
         * 删除
         */
        if ($_POST['form_submit'] == 'ok') {
            if (is_array($_POST['del_id']) && ! empty($_POST['del_id'])) {
                foreach ($_POST['del_id'] as $k => $v) {
                    $model_link->del($v);
                }
                showMessage($lang['feedback_del_succ']);
            } else {
                showMessage($lang['feedback_del_fiald']);
            }
        }
        
        /**
         * 分页
         */
        $page = new Page();
        $page->setEachNum(10);
        $page->setStyle('admin');
        $list = $model_link->getList(array(), $page);
        
        Tpl::output('list', $list);
        Tpl::output('page', $page->show());
        Tpl::showpage('mb_feedback.index');
    }

    /**
     * 删除
     */
    public function delOp()
    {
        $lang = Language::getLangContent();
        if (intval($_GET['id']) > 0) {
            $model_link = Model('feedback');
            $model_link->del(intval($_GET['id']));
            showMessage($lang['feedback_del_succ'], 'index.php?act=feedback&op=flist');
        } else {
            showMessage($lang['feedback_del_fiald'], 'index.php?act=feedback&op=flist');
        }
    }
}
