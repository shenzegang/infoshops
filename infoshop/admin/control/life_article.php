<?php
/**
 * 文章管理
 *
 * @copyright  Copyright (c) 2003-2015 B2B2C Inc. (http://www.infol.com.cn)
 * @license    http://www.infol.com.cn
 * @link       http://www.infol.com.cn
 * @since      File available since Release v1.1
 */
defined('CorShop') or exit('Access Invalid!');

class life_articleControl extends SystemControl
{

    public function __construct()
    {
        parent::__construct();
    }
    
    // 文章管理
    public function articleOp()
    {
        $lang = Language::getLangContent();
        $model = Model('life_article');
        
        // 删除
        if (chksubmit()) {
            if (is_array($_POST['del_id']) && ! empty($_POST['del_id'])) {
                $model_upload = Model('upload');
                foreach ($_POST['del_id'] as $k => $v) {
                    $v = intval($v);
                    
                    $article_array = $model->getOne($v);
                    if (! empty($article_array['thumb'])) {
                        @unlink(BASE_UPLOAD_PATH . DS . ATTACH_ARTICLE . DS . $article_array['thumb']);
                    }
                    
                    // 删除图片
                    $condition['upload_type'] = '11';
                    $condition['item_id'] = $v;
                    $upload_list = $model_upload->getUploadList($condition);
                    if (is_array($upload_list)) {
                        foreach ($upload_list as $k_upload => $v_upload) {
                            $model_upload->del($v_upload['upload_id']);
                            @unlink(BASE_UPLOAD_PATH . DS . ATTACH_ARTICLE . DS . $v_upload['file_name']);
                        }
                    }
                    $model->del($v);
                }
                showMessage('删除文章成功');
            } else {
                showMessage('请选择要删除的内容');
            }
        }
        
        // 检索条件
        $ac_id = intval($_GET['search_ac_id']);
        $like_title = trim($_GET['search_title']);
        if (! empty($ac_id)) {
            $condition['where'] .= ' AND ac_id = ' . $ac_id;
        }
        if (! empty($like_title)) {
            $condition['where'] .= " AND article_title LIKE '%" . $like_title . "%'";
        }
        
        // 分页
        $page = new Page();
        $page->setEachNum(10);
        $page->setStyle('admin');
        
        // 列表
        $list = $model->getList($condition, $page);
        
        // 整理列表内容
        if (is_array($list)) {
            
            // 取文章分类
            $model_class = Model('life_article_class');
            $class_list = $model_class->getClassList($condition);
            $tmp_class_name = array();
            if (is_array($class_list)) {
                foreach ($class_list as $k => $v) {
                    $tmp_class_name[$v['ac_id']] = $v['ac_name'];
                }
            }
            
            foreach ($list as $k => $v) {
                
                // 发布时间
                $list[$k]['article_time'] = date('Y-m-d H:i:s', $v['article_time']);
                
                // 所属分类
                if (@array_key_exists($v['ac_id'], $tmp_class_name)) {
                    $list[$k]['ac_name'] = $tmp_class_name[$v['ac_id']];
                }
            }
        }
        
        // 分类列表
        $model_class = Model('life_article_class');
        $parent_list = $model_class->getTreeClassList(2);
        if (is_array($parent_list)) {
            $unset_sign = false;
            foreach ($parent_list as $k => $v) {
                $parent_list[$k]['ac_name'] = str_repeat("&nbsp;", $v['deep'] * 2) . $v['ac_name'];
            }
        }
        
        Tpl::output('article_list', $list);
        Tpl::output('page', $page->show());
        Tpl::output('search_title', trim($_GET['search_title']));
        Tpl::output('search_ac_id', intval($_GET['search_ac_id']));
        Tpl::output('parent_list', $parent_list);
        Tpl::showpage('life_article.index');
    }
    
    // 文章添加
    public function article_addOp()
    {
        $lang = Language::getLangContent();
        $model = Model('life_article');
        
        // 保存
        if (chksubmit()) {
            
            // 验证
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array(
                    "input" => $_POST["article_title"],
                    "require" => "true",
                    "message" => '文章标题不能为空'
                ),
                array(
                    "input" => $_POST["ac_id"],
                    "require" => "true",
                    "message" => '文章分类不能为空'
                ),
                array(
                    "input" => $_POST["article_url"],
                    'validator' => 'Url',
                    "message" => '链接格式不正确'
                ),
                array(
                    "input" => $_POST["article_content"],
                    "require" => "true",
                    "message" => '文章内容不能为空'
                ),
                array(
                    "input" => $_POST["article_sort"],
                    "require" => "true",
                    'validator' => 'Number',
                    "message" => '文章排序仅能为数字'
                )
            );
            $error = $obj_validate->validate();
            if ($error != '') {
                showMessage($error);
            } else {
                
                $insert_array = array();
                $insert_array['article_title'] = trim($_POST['article_title']);
                $insert_array['ac_id'] = intval($_POST['ac_id']);
                $insert_array['article_url'] = trim($_POST['article_url']);
                $insert_array['article_show'] = trim($_POST['article_show']);
                $insert_array['article_sort'] = trim($_POST['article_sort']);
                $insert_array['article_content'] = trim($_POST['article_content']);
                $insert_array['description'] = empty($insert_array['description']) ? str_cut(SpHtml2Text($insert_array['article_content']), 250) : $_POST['description'];
                $insert_array['tag'] = trim($_POST['tag']);
                $insert_array['source'] = trim($_POST['source']);
                $insert_array['thumb'] = trim($_POST['thumb']);
                $insert_array['article_time'] = time();
                $result = $model->add($insert_array);
                
                if ($result) {
                    
                    $tag = $_POST['tag'];
                    $tag = str_replace('，', ',', $tag);
                    $tag = explode(',', $tag);
                    foreach ($tag as $k => $v) {
                        $is = Db::getRow(array(
                            'table' => 'life_tag',
                            'field' => 'title',
                            'value' => $v
                        ));
                        if ($is) {
                            Db::update('life_tag', array(
                                'num' => ($is['num'] + 1)
                            ), ' id = ' . $is['id']);
                        } else {
                            Db::insert('life_tag', array(
                                'title' => $v
                            ));
                        }
                    }
                    
                    // 更新图片信息ID
                    $model_upload = Model('upload');
                    if (is_array($_POST['file_id'])) {
                        foreach ($_POST['file_id'] as $k => $v) {
                            $v = intval($v);
                            $update_array = array();
                            $update_array['upload_id'] = $v;
                            $update_array['item_id'] = $result;
                            $model_upload->update($update_array);
                            unset($update_array);
                        }
                    }
                    
                    $url = array(
                        array(
                            'url' => 'index.php?act=life_article&op=article',
                            'msg' => "返回文章列表"
                        ),
                        array(
                            'url' => 'index.php?act=life_article&op=article_add&ac_id=' . intval($_POST['ac_id']),
                            'msg' => '继续新增文章'
                        )
                    );
                    showMessage('新增文章成功', $url);
                } else {
                    showMessage('新增文章失败');
                }
            }
        }
        
        // 分类列表
        $model_class = Model('life_article_class');
        $parent_list = $model_class->getTreeClassList(2);
        if (is_array($parent_list)) {
            $unset_sign = false;
            foreach ($parent_list as $k => $v) {
                $parent_list[$k]['ac_name'] = str_repeat("&nbsp;", $v['deep'] * 2) . $v['ac_name'];
            }
        }
        
        // 上传输出
        $model_upload = Model('upload');
        $condition['upload_type'] = '11';
        $condition['item_id'] = '0';
        $file_upload = $model_upload->getUploadList($condition);
        if (is_array($file_upload)) {
            foreach ($file_upload as $k => $v) {
                $file_upload[$k]['upload_path'] = UPLOAD_SITE_URL . '/' . ATTACH_ARTICLE . '/' . $file_upload[$k]['file_name'];
            }
        }
        
        Tpl::output('PHPSESSID', session_id());
        Tpl::output('ac_id', intval($_GET['ac_id']));
        Tpl::output('parent_list', $parent_list);
        Tpl::output('file_upload', $file_upload);
        Tpl::showpage('life_article.add');
    }
    
    // 文章编辑
    public function article_editOp()
    {
        $lang = Language::getLangContent();
        $model = Model('life_article');
        
        if (chksubmit()) {
            
            // 验证
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array(
                    "input" => $_POST["article_title"],
                    "require" => "true",
                    "message" => '文章标题不能为空'
                ),
                array(
                    "input" => $_POST["ac_id"],
                    "require" => "true",
                    "message" => '文章分类不能为空'
                ),
                // array("input"=>$_POST["article_url"], 'validator'=>'Url', "message"=>'链接格式不正确'),
                array(
                    "input" => $_POST["article_content"],
                    "require" => "true",
                    "message" => '文章内容不能为空'
                ),
                array(
                    "input" => $_POST["article_sort"],
                    "require" => "true",
                    'validator' => 'Number',
                    "message" => '文章排序仅能为数字'
                )
            );
            $error = $obj_validate->validate();
            if ($error != '') {
                showMessage($error);
            } else {
                
                $article_array = $model->getOne($v);
                
                $update_array = array();
                $update_array['article_id'] = intval($_POST['article_id']);
                $update_array['article_title'] = trim($_POST['article_title']);
                $update_array['ac_id'] = intval($_POST['ac_id']);
                $update_array['article_url'] = trim($_POST['article_url']);
                $update_array['article_show'] = trim($_POST['article_show']);
                $update_array['article_sort'] = trim($_POST['article_sort']);
                $update_array['article_content'] = trim($_POST['article_content']);
                $update_array['description'] = empty($update_array['description']) ? str_cut(SpHtml2Text($update_array['article_content']), 250) : $_POST['description'];
                $update_array['tag'] = trim($_POST['tag']);
                $insert_array['source'] = trim($_POST['source']);
                if (! empty($_POST['thumb'])) {
                    $update_array['thumb'] = trim($_POST['thumb']);
                }
                
                $result = $model->update($update_array);
                
                if ($result) {
                    
                    $old_tag = $_POST['old_tag'];
                    $old_tag = explode(',', $old_tag);
                    
                    $tag = $_POST['tag'];
                    $tag = str_replace('，', ',', $tag);
                    $tag = explode(',', $tag);
                    foreach ($tag as $k => $v) {
                        if (in_array($v, $old_tag)) {
                            continue;
                        }
                        $is = Db::getRow(array(
                            'table' => 'life_tag',
                            'field' => 'title',
                            'value' => $v
                        ));
                        if ($is) {
                            Db::update('life_tag', array(
                                'num' => ($is['num'] + 1)
                            ), ' id = ' . $is['id']);
                        } else {
                            Db::insert('life_tag', array(
                                'title' => $v
                            ));
                        }
                    }
                    
                    if (! empty($_POST['thumb']) && ! empty($article_array['thumb'])) {
                        @unlink(BASE_UPLOAD_PATH . DS . ATTACH_ARTICLE . DS . $article_array['thumb']);
                    }
                    
                    // 更新图片信息ID
                    $model_upload = Model('upload');
                    if (is_array($_POST['file_id'])) {
                        foreach ($_POST['file_id'] as $k => $v) {
                            $update_array = array();
                            $update_array['upload_id'] = intval($v);
                            $update_array['item_id'] = intval($_POST['article_id']);
                            $model_upload->update($update_array);
                            unset($update_array);
                        }
                    }
                    
                    $url = array(
                        array(
                            'url' => $_POST['ref_url'],
                            'msg' => '返回文章列表'
                        ),
                        array(
                            'url' => 'index.php?act=life_article&op=article_edit&article_id=' . intval($_POST['article_id']),
                            'msg' => '重新编辑该文章'
                        )
                    );
                    showMessage('编辑文章成功', $url);
                } else {
                    showMessage('编辑文章失败');
                }
            }
        }
        
        $article_array = $model->getOne(intval($_GET['article_id']));
        if (empty($article_array)) {
            showMessage('参数错误');
        }
        
        // 文章类别模型实例化
        $model_class = Model('life_article_class');
        
        // 父类列表，只取到第一级
        $parent_list = $model_class->getTreeClassList(2);
        if (is_array($parent_list)) {
            $unset_sign = false;
            foreach ($parent_list as $k => $v) {
                $parent_list[$k]['ac_name'] = str_repeat("&nbsp;", $v['deep'] * 2) . $v['ac_name'];
            }
        }
        
        // 上传输出
        $model_upload = Model('upload');
        $condition['upload_type'] = '11';
        $condition['item_id'] = $article_array['article_id'];
        $file_upload = $model_upload->getUploadList($condition);
        if (is_array($file_upload)) {
            foreach ($file_upload as $k => $v) {
                $file_upload[$k]['upload_path'] = UPLOAD_SITE_URL . '/' . ATTACH_ARTICLE . '/' . $file_upload[$k]['file_name'];
            }
        }
        
        Tpl::output('PHPSESSID', session_id());
        Tpl::output('file_upload', $file_upload);
        Tpl::output('parent_list', $parent_list);
        Tpl::output('article_array', $article_array);
        Tpl::showpage('life_article.edit');
    }
    
    // 文章图片上传
    public function article_pic_uploadOp()
    {
        
        // 上传图片
        $upload = new UploadFile();
        $upload->set('default_dir', ATTACH_ARTICLE);
        $result = $upload->upfile('fileupload');
        if ($result) {
            $_POST['pic'] = $upload->file_name;
        } else {
            echo 'error';
            exit();
        }
        
        // 模型实例化
        $model_upload = Model('upload');
        
        // 图片数据入库
        $insert_array = array();
        $insert_array['file_name'] = $_POST['pic'];
        $insert_array['upload_type'] = '11';
        $insert_array['file_size'] = $_FILES['fileupload']['size'];
        $insert_array['upload_time'] = time();
        $insert_array['item_id'] = intval($_POST['item_id']);
        $result = $model_upload->add($insert_array);
        if ($result) {
            $data = array();
            $data['file_id'] = $result;
            $data['file_name'] = $_POST['pic'];
            $data['file_path'] = $_POST['pic'];
            
            // 整理为json格式
            $output = json_encode($data);
            echo $output;
        }
    }

    /**
     * ajax操作
     */
    public function ajaxOp()
    {
        if (in_array($_GET['branch'], array(
            'article_show',
            'is_head',
            'is_best',
            'is_hot'
        ))) {
            $model = Model('life_article');
            $update_array = array();
            $update_array['article_id'] = intval($_GET['id']);
            $update_array[trim($_GET['column'])] = intval($_GET['value']);
            if ($model->update($update_array)) {
                echo 'true';
            } else {
                echo 'false';
            }
            exit();
        }
        
        switch ($_GET['branch']) {
            
            // 删除文章图片
            case 'del_file_upload':
                if (intval($_GET['file_id']) > 0) {
                    $model_upload = Model('upload');
                    
                    // 删除图片
                    $file_array = $model_upload->getOneUpload(intval($_GET['file_id']));
                    @unlink(BASE_UPLOAD_PATH . DS . ATTACH_ARTICLE . DS . $file_array['file_name']);
                    
                    // 删除信息
                    $model_upload->del(intval($_GET['file_id']));
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