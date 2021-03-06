<?php
/**
 * 便民信息管理
 *
 * @copyright  Copyright (c) 2003-2015 B2B2C Inc. (http://www.infol.com.cn)
 * @license    http://www.infol.com.cn
 * @link       http://www.infol.com.cn
 * @since      File available since Release v1.1
 */
defined('CorShop') or exit('Access Invalid!');

class life_infoControl extends SystemControl
{

    public function __construct()
    {
        parent::__construct();
    }
    
    // 便民信息管理
    public function listOp()
    {
        $lang = Language::getLangContent();
        $model = Model('life_info');
        
        // 删除
        if (chksubmit()) {
            if (is_array($_POST['del_id']) && ! empty($_POST['del_id'])) {
                $model_upload = Model('upload');
                foreach ($_POST['del_id'] as $k => $v) {
                    $v = intval($v);
                    
                    // 删除图片
                    $condition['upload_type'] = '14';
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
                showMessage('删除便民信息成功');
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
        Tpl::showpage('life_info.index');
    }

    /**
     * 便民信息添加
     */
    public function addOp()
    {
        $model = Model('life_info');
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
                    'input' => $_POST['content'],
                    'require' => 'true',
                    'message' => '主营内容不能为空'
                ),
                array(
                    'input' => $_POST['tel'],
                    'require' => 'true',
                    'message' => '联系电话不能为空'
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
                $insert_array['is_show'] = trim($_POST['is_show']);
                $insert_array['sort'] = trim($_POST['sort']);
                $insert_array['content'] = trim($_POST['content']);
                $insert_array['tel'] = trim($_POST['tel']);
                $insert_array['time'] = time();
                $insert_array['start_date'] = $this->getunixtime(trim($_POST['start_date']));
                $insert_array['end_date'] = $this->getunixtime(trim($_POST['end_date']));
                
                $result = $model->add($insert_array);
                
                if ($result) {
                    
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
                            'url' => 'index.php?act=life_info&op=list',
                            'msg' => "返回便民信息列表"
                        ),
                        array(
                            'url' => 'index.php?act=life_info&op=add',
                            'msg' => '继续新增便民信息'
                        )
                    );
                    showMessage('新增便民信息成功', $url);
                } else {
                    showMessage('新增便民信息失败');
                }
            }
        }
        
        // 上传输出
        $model_upload = Model('upload');
        $condition['upload_type'] = '14';
        $condition['item_id'] = '0';
        $file_upload = $model_upload->getUploadList($condition);
        if (is_array($file_upload)) {
            foreach ($file_upload as $k => $v) {
                $file_upload[$k]['upload_path'] = UPLOAD_SITE_URL . '/' . ATTACH_ARTICLE . '/' . $file_upload[$k]['file_name'];
            }
        }
        
        Tpl::output('PHPSESSID', session_id());
        Tpl::output('file_upload', $file_upload);
        Tpl::showpage('life_info.add');
    }

    /**
     * 便民信息编辑
     */
    public function editOp()
    {
        $model = Model('life_info');
        
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
                    'input' => $_POST['content'],
                    'require' => 'true',
                    'message' => '主营内容不能为空'
                ),
                array(
                    'input' => $_POST['tel'],
                    'require' => 'true',
                    'message' => '联系电话不能为空'
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
                $update_array['is_show'] = trim($_POST['is_show']);
                $update_array['sort'] = trim($_POST['sort']);
                $update_array['content'] = trim($_POST['content']);
                $update_array['tel'] = trim($_POST['tel']);
                $update_array['start_date'] = $this->getunixtime(trim($_POST['start_date']));
                $update_array['end_date'] = $this->getunixtime(trim($_POST['end_date']));
                
                $result = $model->update($update_array);
                
                if ($result) {
                    // 更新图片信息ID
                    $model_upload = Model('upload');
                    if (is_array($_POST['file_id'])) {
                        foreach ($_POST['file_id'] as $k => $v) {
                            $update_array = array();
                            $update_array['upload_id'] = intval($v);
                            $update_array['item_id'] = intval($_POST['id']);
                            $model_upload->update($update_array);
                            unset($update_array);
                        }
                    }
                    
                    $url = array(
                        array(
                            'url' => $_POST['ref_url'],
                            'msg' => '返回便民信息列表'
                        ),
                        array(
                            'url' => 'index.php?act=life_info&op=edit&id=' . intval($_POST['id']),
                            'msg' => '重新编辑该便民信息'
                        )
                    );
                    showMessage('编辑便民信息成功', $url);
                } else {
                    showMessage('编辑便民信息失败');
                }
            }
        }
        
        $article_array = $model->getOne(intval($_GET['id']));
        
        if (empty($article_array)) {
            showMessage('参数错误');
        }
        
        // 上传输出
        $model_upload = Model('upload');
        $condition['upload_type'] = '14';
        $condition['item_id'] = $article_array['id'];
        $file_upload = $model_upload->getUploadList($condition);
        if (is_array($file_upload)) {
            foreach ($file_upload as $k => $v) {
                $file_upload[$k]['upload_path'] = UPLOAD_SITE_URL . '/' . ATTACH_ARTICLE . '/' . $file_upload[$k]['file_name'];
            }
        }
        
        Tpl::output('PHPSESSID', session_id());
        Tpl::output('file_upload', $file_upload);
        Tpl::output('article_array', $article_array);
        Tpl::showpage('life_info.edit');
    }
    
    // 文章图片上传
    public function pic_uploadOp()
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
        $insert_array['upload_type'] = '14';
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
            'is_show'
        ))) {
            $model = Model('life_info');
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

    /**
     * 获取UNIX时间戳
     */
    public function getunixtime($time)
    {
        $array = explode("-", $time);
        $unix_time = mktime(0, 0, 0, $array[1], $array[2], $array[0]);
        return $unix_time;
    }
}