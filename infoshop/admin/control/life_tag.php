<?php
/**
 * TAG管理
 *
 * @copyright  Copyright (c) 2003-2015 B2B2C Inc. (http://www.infol.com.cn)
 * @license    http://www.infol.com.cn
 * @link       http://www.infol.com.cn
 * @since      File available since Release v1.1
 */
defined('CorShop') or exit('Access Invalid!');

class life_tagControl extends SystemControl
{

    public function __construct()
    {
        parent::__construct();
    }
    
    // TAG管理
    public function listOp()
    {
        $lang = Language::getLangContent();
        $model = Model('life_tag');
        
        // 删除
        if (chksubmit()) {
            if (is_array($_POST['del_id']) && ! empty($_POST['del_id'])) {
                foreach ($_POST['del_id'] as $k => $v) {
                    $v = intval($v);
                    $model->del($v);
                }
                showMessage('删除TAG成功');
            } else {
                showMessage('请选择要删除的内容');
            }
        }
        
        // 检索条件
        if (! empty($_GET['search_title'])) {
            $condition['where'] .= " AND title like '%" . $_GET['search_title'] . "%'";
        }
        
        // 分页
        $page = new Page();
        $page->setEachNum(10);
        $page->setStyle('admin');
        
        // 列表
        $list = $model->getList($condition, $page);
        
        if ($list) {
            foreach ($list as $k => $v) {
                $list[$k]['time'] = date('Y-m-d H:i:s', $v['time']);
            }
        }
        
        Tpl::output('article_list', $list);
        Tpl::output('page', $page->show());
        Tpl::output('search_title', trim($_GET['search_title']));
        Tpl::showpage('life_tag.index');
    }

    /**
     * TAG添加
     */
    public function addOp()
    {
        $model = Model('life_tag');
        /**
         * 保存
         */
        if (chksubmit()) {
            /**
             * 验证
             */
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array(
                    'input' => $_POST['title'],
                    'require' => 'true',
                    'message' => '标题不能为空'
                ),
                array(
                    'input' => $_POST['sort'],
                    'require' => 'true',
                    'validator' => 'Number',
                    "message" => '排序仅能为数字'
                )
            );
            $error = $obj_validate->validate();
            if ($error != '') {
                showMessage($error);
            } else {
                
                $insert_array = array();
                $insert_array['title'] = trim($_POST['title']);
                $insert_array['sort'] = trim($_POST['sort']);
                $result = $model->add($insert_array);
                
                if ($result) {
                    
                    $url = array(
                        array(
                            'url' => 'index.php?act=life_tag&op=list',
                            'msg' => "返回TAG列表"
                        ),
                        array(
                            'url' => 'index.php?act=life_tag&op=add',
                            'msg' => '继续新增TAG'
                        )
                    );
                    showMessage('新增TAG成功', $url);
                } else {
                    showMessage('新增TAG失败');
                }
            }
        }
        
        Tpl::showpage('life_tag.add');
    }

    /**
     * TAG编辑
     */
    public function editOp()
    {
        $model = Model('life_tag');
        
        if (chksubmit()) {
            /**
             * 验证
             */
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array(
                    'input' => $_POST['title'],
                    'require' => 'true',
                    'message' => '标题不能为空'
                ),
                array(
                    'input' => $_POST['sort'],
                    'require' => 'true',
                    'validator' => 'Number',
                    'message' => '排序仅能为数字'
                )
            );
            $error = $obj_validate->validate();
            if ($error != '') {
                showMessage($error);
            } else {
                
                $update_array = array();
                $update_array['id'] = intval($_POST['id']);
                $update_array['title'] = trim($_POST['title']);
                $update_array['sort'] = trim($_POST['sort']);
                
                $result = $model->update($update_array);
                
                if ($result) {
                    
                    $url = array(
                        array(
                            'url' => $_POST['ref_url'],
                            'msg' => '返回TAG列表'
                        ),
                        array(
                            'url' => 'index.php?act=life_tag&op=edit&id=' . intval($_POST['id']),
                            'msg' => '重新编辑该TAG'
                        )
                    );
                    showMessage('编辑TAG成功', $url);
                } else {
                    showMessage('编辑TAG失败');
                }
            }
        }
        
        $article_array = $model->getOne(intval($_GET['id']));
        if (empty($article_array)) {
            showMessage('参数错误');
        }
        
        Tpl::output('article_array', $article_array);
        Tpl::showpage('life_tag.edit');
    }

    /**
     * ajax操作
     */
    public function ajaxOp()
    {
        if (in_array($_GET['branch'], array(
            'is_hot'
        ))) {
            $model = Model('life_tag');
            $update_array = array();
            $update_array['id'] = intval($_GET['id']);
            $update_array[trim($_GET['column'])] = intval($_GET['value']);
            if ($model->update($update_array)) {
                echo 'true';
            } else {
                echo 'false';
            }
            exit();
        }
    }
}