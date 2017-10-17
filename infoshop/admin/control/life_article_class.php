<?php
/**
 * 文章分类
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

class life_article_classControl extends SystemControl
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 文章管理
     */
    public function article_classOp()
    {
        $lang = Language::getLangContent();
        $model_class = Model('life_article_class');
        // 删除
        if (chksubmit()) {
            if (! empty($_POST['check_ac_id'])) {
                if (is_array($_POST['check_ac_id'])) {
                    $del_array = $model_class->getChildClass($_POST['check_ac_id']);
                    if (is_array($del_array)) {
                        foreach ($del_array as $k => $v) {
                            $model_class->del($v['ac_id']);
                        }
                    }
                }
                showMessage('分类排序仅能为数字');
            } else {
                showMessage('请选择要删除的内容!');
            }
        }
        /**
         * 父ID
         */
        $parent_id = $_GET['ac_parent_id'] ? intval($_GET['ac_parent_id']) : 0;
        /**
         * 列表
         */
        $tmp_list = $model_class->getTreeClassList(2);
        if (is_array($tmp_list)) {
            foreach ($tmp_list as $k => $v) {
                if ($v['ac_parent_id'] == $parent_id) {
                    /**
                     * 判断是否有子类
                     */
                    if ($tmp_list[$k + 1]['deep'] > $v['deep']) {
                        $v['have_child'] = 1;
                    }
                    $class_list[] = $v;
                }
            }
        }
        if ($_GET['ajax'] == '1') {
            /**
             * 转码
             */
            if (strtoupper(CHARSET) == 'GBK') {
                $class_list = Language::getUTF8($class_list);
            }
            $output = json_encode($class_list);
            print_r($output);
            exit();
        } else {
            Tpl::output('class_list', $class_list);
            Tpl::showpage('life_article_class.index');
        }
    }

    /**
     * 文章分类 新增
     */
    public function article_class_addOp()
    {
        $lang = Language::getLangContent();
        $model_class = Model('life_article_class');
        if (chksubmit()) {
            /**
             * 验证
             */
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array(
                    "input" => $_POST["ac_name"],
                    "require" => "true",
                    "message" => '分类名称不能为空'
                ),
                array(
                    "input" => $_POST["ac_sort"],
                    "require" => "true",
                    'validator' => 'Number',
                    "message" => '分类排序仅能为数字'
                )
            );
            $error = $obj_validate->validate();
            if ($error != '') {
                showMessage($error);
            } else {
                
                $insert_array = array();
                $insert_array['ac_name'] = trim($_POST['ac_name']);
                $insert_array['ac_parent_id'] = intval($_POST['ac_parent_id']);
                $insert_array['ac_sort'] = trim($_POST['ac_sort']);
                
                $result = $model_class->add($insert_array);
                if ($result) {
                    $url = array(
                        array(
                            'url' => 'index.php?act=life_article_class&op=article_class_add&ac_parent_id=' . intval($_POST['ac_parent_id']),
                            'msg' => '继续新增分类'
                        ),
                        array(
                            'url' => 'index.php?act=life_article_class&op=article_class',
                            'msg' => '返回分类列表'
                        )
                    );
                    showMessage('新增分类成功', $url);
                } else {
                    showMessage('新增分类失败');
                }
            }
        }
        /**
         * 父类列表，只取到第三级
         */
        $parent_list = $model_class->getTreeClassList(1);
        if (is_array($parent_list)) {
            foreach ($parent_list as $k => $v) {
                $parent_list[$k]['ac_name'] = str_repeat("&nbsp;", $v['deep'] * 2) . $v['ac_name'];
            }
        }
        
        Tpl::output('ac_parent_id', intval($_GET['ac_parent_id']));
        Tpl::output('parent_list', $parent_list);
        Tpl::showpage('life_article_class.add');
    }

    /**
     * 文章分类编辑
     */
    public function article_class_editOp()
    {
        $lang = Language::getLangContent();
        $model_class = Model('life_article_class');
        
        if (chksubmit()) {
            /**
             * 验证
             */
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array(
                    "input" => $_POST["ac_name"],
                    "require" => "true",
                    "message" => '分类名称不能为空'
                ),
                array(
                    "input" => $_POST["ac_sort"],
                    "require" => "true",
                    'validator' => 'Number',
                    "message" => '分类排序仅能为数字'
                )
            );
            $error = $obj_validate->validate();
            if ($error != '') {
                showMessage($error);
            } else {
                
                $update_array = array();
                $update_array['ac_id'] = intval($_POST['ac_id']);
                $update_array['ac_name'] = trim($_POST['ac_name']);
                // $update_array['ac_parent_id'] = intval($_POST['ac_parent_id']);
                $update_array['ac_sort'] = trim($_POST['ac_sort']);
                
                $result = $model_class->update($update_array);
                if ($result) {
                    $url = array(
                        array(
                            'url' => 'index.php?act=life_article_class&op=article_class',
                            'msg' => '返回分类列表'
                        ),
                        array(
                            'url' => 'index.php?act=life_article_class&op=article_class_edit&ac_id=' . intval($_POST['ac_id']),
                            'msg' => '重新编辑该分类'
                        )
                    );
                    showMessage('编辑分类成功', 'index.php?act=life_article_class&op=article_class');
                } else {
                    showMessage('编辑分类失败');
                }
            }
        }
        
        $class_array = $model_class->getOneClass(intval($_GET['ac_id']));
        if (empty($class_array)) {
            showMessage('参数错误');
        }
        
        Tpl::output('class_array', $class_array);
        Tpl::showpage('life_article_class.edit');
    }

    /**
     * 删除分类
     */
    public function article_class_delOp()
    {
        $lang = Language::getLangContent();
        $model_class = Model('life_article_class');
        if (intval($_GET['ac_id']) > 0) {
            $array = array(
                intval($_GET['ac_id'])
            );
            
            $del_array = $model_class->getChildClass($array);
            if (is_array($del_array)) {
                foreach ($del_array as $k => $v) {
                    $model_class->del($v['ac_id']);
                }
            }
            showMessage('分类排序仅能为数字', 'index.php?act=life_article_class&op=article_class');
        } else {
            showMessage('请选择要删除的内容!', 'index.php?act=life_article_class&op=article_class');
        }
    }

    /**
     * ajax操作
     */
    public function ajaxOp()
    {
        switch ($_GET['branch']) {
            /**
             * 分类：验证是否有重复的名称
             */
            case 'article_class_name':
                $model_class = Model('life_article_class');
                $class_array = $model_class->getOneClass(intval($_GET['id']));
                
                $name = trim($_GET['value']);
                $parent_id = $class_array['ac_parent_id'];
                $no_id = intval($_GET['id']);
                
                if (! empty($name)) {
                    $condition['where'] .= " AND ac_name LIKE '" . $name . "'";
                }
                
                if (! empty($parent_id)) {
                    $condition['where'] .= ' AND ac_parent_id = ' . $parent_id;
                }
                
                if (! empty($no_id)) {
                    $condition['where'] .= ' AND ac_id != ' . $no_id;
                }
                
                $class_list = $model_class->getClassList($condition);
                if (empty($class_list)) {
                    $update_array = array();
                    $update_array['ac_id'] = intval($_GET['id']);
                    $update_array['ac_name'] = trim($_GET['value']);
                    $model_class->update($update_array);
                    echo 'true';
                    exit();
                } else {
                    echo 'false';
                    exit();
                }
                break;
            /**
             * 分类： 排序 显示 设置
             */
            case 'article_class_sort':
                $model_class = Model('life_article_class');
                $update_array = array();
                $update_array['ac_id'] = intval($_GET['id']);
                $update_array[$_GET['column']] = trim($_GET['value']);
                $result = $model_class->update($update_array);
                echo 'true';
                exit();
                break;
            /**
             * 分类：添加、修改操作中 检测类别名称是否有重复
             */
            case 'check_class_name':
                $model_class = Model('life_article_class');
                $name = trim($_GET['ac_name']);
                $parent_id = intval($_GET['ac_parent_id']);
                $no_id = intval($_GET['ac_id']);
                
                if (! empty($name)) {
                    $condition['where'] .= " AND ac_name LIKE '" . $name . "'";
                }
                
                if (! empty($parent_id)) {
                    $condition['where'] .= ' AND ac_parent_id = ' . $parent_id;
                }
                
                if (! empty($no_id)) {
                    $condition['where'] .= ' AND ac_id != ' . $no_id;
                }
                
                $class_list = $model_class->getClassList($condition);
                if (empty($class_list)) {
                    echo 'true';
                    exit();
                } else {
                    echo 'false';
                    exit();
                }
                break;
        }
    }
}