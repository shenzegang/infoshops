<?php
/**
 * 前台模块编辑(首页)
 *
 * @copyright  Copyright (c) 2003-2015 B2B2C Inc. (http://www.infol.com.cn)
 * @license    http://www.infol.com.cn
 * @link       http://www.infol.com.cn
 * @since      File available since Release v1.1
 */
defined('CorShop') or exit('Access Invalid!');

class web_foodControl extends SystemControl
{

    public function __construct()
    {
        parent::__construct();
        if (strtoupper(CHARSET) == 'GBK') {
            $_GET = Language::getGBK($_GET);
            $_POST = Language::getGBK($_POST);
        }
        Language::read('web_config');
    }

    /**
     * 板块列表
     */
    public function web_configOp()
    {
        $model_web_config = Model('web_config');
        $style_array = $model_web_config->getStyleList(); // 板块样式数组
        Tpl::output('style_array', $style_array);
        $web_list = $model_web_config->getWebList(array(
            'web_page' => 'food_index'
        ));
        Tpl::output('web_list', $web_list);
        Tpl::showpage('web_food.index');
    }

    /**
     * 基本设置
     */
    public function web_editOp()
    {
        $model_web_config = Model('web_config');
        $web_id = intval($_GET["web_id"]);
        if (chksubmit()) {
            $web_array = array();
            $web_id = intval($_POST["web_id"]);
            $web_array['web_name'] = $_POST["web_name"];
            $web_array['style_name'] = $_POST["style_name"];
            $web_array['web_sort'] = intval($_POST["web_sort"]);
            $web_array['web_show'] = intval($_POST["web_show"]);
            $web_array['update_time'] = time();
            $model_web_config->updateWeb(array(
                'web_id' => $web_id
            ), $web_array);
            $model_web_config->updateWebHtml($web_id, $web_array['style_name']); // 更新前台显示的html内容
            $this->log(l('web_config_code_edit') . '[' . $_POST["web_name"] . ']', 1);
            showMessage(Language::get('nc_common_save_succ'), 'index.php?act=web_food&op=web_config');
        }
        $web_list = $model_web_config->getWebList(array(
            'web_id' => $web_id
        ));
        Tpl::output('web_array', $web_list[0]);
        Tpl::showpage('web_food.edit');
    }

    /**
     * 板块编辑
     */
    public function code_editOp()
    {
        $model_web_config = Model('web_config');
        $web_id = intval($_GET["web_id"]);
        $code_list = $model_web_config->getCodeList(array(
            'web_id' => "$web_id"
        ));
        if (is_array($code_list) && ! empty($code_list)) {
            $model_class = Model('goods_class');
            $parent_goods_class = $model_class->getTreeClassList(2); // 商品分类父类列表，只取到第二级
            if (is_array($parent_goods_class) && ! empty($parent_goods_class)) {
                foreach ($parent_goods_class as $k => $v) {
                    $parent_goods_class[$k]['gc_name'] = str_repeat("&nbsp;", $v['deep'] * 2) . $v['gc_name'];
                }
            }
            Tpl::output('parent_goods_class', $parent_goods_class);
            
            $goods_class = $model_class->getTreeClassList(1); // 第一级商品分类
            Tpl::output('goods_class', $goods_class);
            
            foreach ($code_list as $key => $val) { // 将变量输出到页面
                $var_name = $val["var_name"];
                $code_info = $val["code_info"];
                $code_type = $val["code_type"];
                $val['code_info'] = $model_web_config->get_array($code_info, $code_type);
                Tpl::output('code_' . $var_name, $val);
            }
            $style_array = $model_web_config->getStyleList(); // 样式数组
            Tpl::output('style_array', $style_array);
            $web_list = $model_web_config->getWebList(array(
                'web_id' => $web_id
            ));
            Tpl::output('web_array', $web_list[0]);
            Tpl::showpage('web_food.code_edit');
        } else {
            showMessage(Language::get('nc_no_record'));
        }
    }

    /**
     * 更新前台显示的html内容
     */
    public function web_htmlOp()
    {
        $model_web_config = Model('web_config');
        $web_id = intval($_GET["web_id"]);
        $web_list = $model_web_config->getWebList(array(
            'web_id' => $web_id
        ));
        $web_array = $web_list[0];
        if (! empty($web_array) && is_array($web_array)) {
            $model_web_config->updateWebHtml($web_id, $web_array['style_name']);
            showMessage(Language::get('nc_common_op_succ'), 'index.php?act=web_food&op=web_config');
        } else {
            showMessage(Language::get('nc_common_op_fail'));
        }
    }

    /**
     * 头部切换图设置
     */
    public function focus_editOp()
    {
        $model_web_config = Model('web_config');
        $web_id = '201';
        $code_list = $model_web_config->getCodeList(array(
            'web_id' => $web_id
        ));
        if (is_array($code_list) && ! empty($code_list)) {
            foreach ($code_list as $key => $val) { // 将变量输出到页面
                $var_name = $val['var_name'];
                $code_info = $val['code_info'];
                $code_type = $val['code_type'];
                $val['code_info'] = $model_web_config->get_array($code_info, $code_type);
                Tpl::output('code_' . $var_name, $val);
            }
        }
        
        Tpl::showpage('web_food.focus');
    }

    /**
     * 更新html内容
     */
    public function html_updateOp()
    {
        $model_web_config = Model('web_config');
        $web_id = intval($_GET["web_id"]);
        $web_list = $model_web_config->getWebList(array(
            'web_id' => $web_id
        ));
        $web_array = $web_list[0];
        if (! empty($web_array) && is_array($web_array)) {
            $model_web_config->updateWebHtml($web_id);
            showMessage(Language::get('nc_common_op_succ'));
        } else {
            showMessage(Language::get('nc_common_op_fail'));
        }
    }

    /**
     * 头部促销区
     */
    public function sale_editOp()
    {
        $model_web_config = Model('web_config');
        $web_id = '221';
        $code_list = $model_web_config->getCodeList(array(
            'web_id' => $web_id
        ));
        if (is_array($code_list) && ! empty($code_list)) {
            $model_class = Model('goods_class');
            $goods_class = $model_class->getTreeClassList(1); // 第一级商品分类
            Tpl::output('goods_class', $goods_class);
            foreach ($code_list as $key => $val) { // 将变量输出到页面
                $var_name = $val['var_name'];
                $code_info = $val['code_info'];
                $code_type = $val['code_type'];
                $val['code_info'] = $model_web_config->get_array($code_info, $code_type);
                Tpl::output('code_' . $var_name, $val);
            }
        }
        Tpl::showpage('web_food.sale');
    }

    /**
     * 商品分类
     */
    public function category_listOp()
    {
        $model_class = Model('goods_class');
        $gc_parent_id = intval($_GET['id']);
        $goods_class = $model_class->getClassList(array(
            'gc_parent_id' => $gc_parent_id
        ));
        Tpl::output('goods_class', $goods_class);
        Tpl::showpage('web_goods_class', 'null_layout');
    }

    /**
     * 商品推荐
     */
    public function recommend_listOp()
    {
        $model_web_config = Model('web_config');
        $condition = array();
        $gc_id = intval($_GET['id']);
        if ($gc_id > 0) {
            $condition['gc_id'] = $gc_id;
        }
        $goods_name = trim($_GET['goods_name']);
        if (! empty($goods_name)) {
            $condition['goods_name'] = array(
                'like',
                '%' . $goods_name . '%'
            );
        }
        $goods_list = $model_web_config->getGoodsList($condition, 'goods_id desc', 6);
        Tpl::output('show_page', $model_web_config->showpage(2));
        Tpl::output('goods_list', $goods_list);
        Tpl::showpage('web_goods.list', 'null_layout');
    }

    /**
     * 商品排序查询
     */
    public function goods_listOp()
    {
        $model_web_config = Model('web_config');
        $condition = array();
        $order = 'goods_salenum desc,goods_id desc'; // 销售数量
        $goods_order = trim($_GET['goods_order']);
        if (! empty($goods_order)) {
            $order = $goods_order . ' desc,goods_id desc';
        }
        $gc_id = intval($_GET['id']);
        if ($gc_id > 0) {
            $condition['gc_id'] = $gc_id;
        }
        $goods_name = trim($_GET['goods_name']);
        if (! empty($goods_name)) {
            $condition['goods_name'] = array(
                'like',
                '%' . $goods_name . '%'
            );
        }
        $goods_list = $model_web_config->getGoodsList($condition, $order, 6);
        Tpl::output('show_page', $model_web_config->showpage(2));
        Tpl::output('goods_list', $goods_list);
        Tpl::showpage('web_goods_order', 'null_layout');
    }

    /**
     * 品牌
     */
    public function brand_listOp()
    {
        $model_brand = Model('brand');
        /**
         * 检索条件
         */
        $condition = array();
        $condition['brand_apply'] = '1';
        $condition['order'] = ' brand_sort,brand_id desc ';
        $condition['like_brand_name'] = trim($_GET['brand_name']);
        /**
         * 分页
         */
        $page = new Page();
        $page->setEachNum(6);
        $page->setStyle('admin');
        $brand_list = $model_brand->getBrandList($condition, $page);
        Tpl::output('show_page', $page->show());
        Tpl::output('brand_list', $brand_list);
        Tpl::showpage('web_brand.list', 'null_layout');
    }

    /**
     * 保存设置
     */
    public function code_updateOp()
    {
        $code_id = intval($_POST['code_id']);
        $web_id = intval($_POST['web_id']);
        $model_web_config = Model('web_config');
        $code = $model_web_config->getCodeRow($code_id, $web_id);
        if (! empty($code)) {
            $code_type = $code['code_type'];
            $var_name = $code['var_name'];
            $code_info = $_POST[$var_name];
            $code_info = $model_web_config->get_str($code_info, $code_type);
            $state = $model_web_config->updateCode(array(
                'code_id' => $code_id
            ), array(
                'code_info' => $code_info
            ));
        }
        if ($state) {
            echo '1';
            exit();
        } else {
            echo '0';
            exit();
        }
    }

    /**
     * 保存图片
     */
    public function upload_picOp()
    {
        $code_id = intval($_POST['code_id']);
        $web_id = intval($_POST['web_id']);
        $model_web_config = Model('web_config');
        $code = $model_web_config->getCodeRow($code_id, $web_id);
        if (! empty($code)) {
            $code_type = $code['code_type'];
            $var_name = $code['var_name'];
            $code_info = $_POST[$var_name];
            
            $file_name = 'web-' . $web_id . '-' . $code_id;
            $pic_name = $this->_upload_pic($file_name); // 上传图片
            if (! empty($pic_name)) {
                $code_info['pic'] = $pic_name;
            }
            
            Tpl::output('var_name', $var_name);
            Tpl::output('pic', $code_info['pic']);
            Tpl::output('type', $code_info['type']);
            Tpl::output('ap_id', $code_info['ap_id']);
            $code_info = $model_web_config->get_str($code_info, $code_type);
            $state = $model_web_config->updateCode(array(
                'code_id' => $code_id
            ), array(
                'code_info' => $code_info
            ));
            Tpl::showpage('web_upload_pic', 'null_layout');
        }
    }

    /**
     * 中部推荐图片
     */
    public function recommend_picOp()
    {
        $code_id = intval($_POST['code_id']);
        $web_id = intval($_POST['web_id']);
        $model_web_config = Model('web_config');
        $code = $model_web_config->getCodeRow($code_id, $web_id);
        $key_id = intval($_POST['key_id']);
        $pic_id = intval($_POST['pic_id']);
        if (! empty($code) && $key_id > 0 && $pic_id > 1) {
            $code_info = $code['code_info'];
            $code_type = $code['code_type'];
            $code_info = $model_web_config->get_array($code_info, $code_type); // 原数组
            
            $var_name = "pic_list";
            $pic_info = $_POST[$var_name];
            $pic_info['pic_id'] = $pic_id;
            if (! empty($code_info[$key_id]['pic_list'][$pic_id]['pic_img'])) { // 原图片
                $pic_info['pic_img'] = $code_info[$key_id]['pic_list'][$pic_id]['pic_img'];
            }
            
            $file_name = 'web-' . $web_id . '-' . $code_id . '-' . $key_id . '-' . $pic_id;
            $pic_name = $this->_upload_pic($file_name); // 上传图片
            if (! empty($pic_name)) {
                $pic_info['pic_img'] = $pic_name;
            }
            
            $recommend_list = array();
            $recommend_list = $_POST['recommend_list'];
            $recommend_list['pic_list'] = $code_info[$key_id]['pic_list'];
            $code_info[$key_id] = $recommend_list;
            $code_info[$key_id]['pic_list'][$pic_id] = $pic_info;
            
            Tpl::output('pic', $pic_info);
            $code_info = $model_web_config->get_str($code_info, $code_type);
            $state = $model_web_config->updateCode(array(
                'code_id' => $code_id
            ), array(
                'code_info' => $code_info
            ));
            Tpl::showpage('web_recommend_pic', 'null_layout');
        }
    }

    /**
     * 保存切换图片
     */
    public function slide_advOp()
    {
        $code_id = intval($_POST['code_id']);
        $web_id = intval($_POST['web_id']);
        $model_web_config = Model('web_config');
        $code = $model_web_config->getCodeRow($code_id, $web_id);
        if (! empty($code)) {
            $code_type = $code['code_type'];
            $var_name = $code['var_name'];
            $code_info = $_POST[$var_name];
            
            $pic_id = intval($_POST['slide_id']);
            if ($pic_id > 0) {
                $var_name = "slide_pic";
                $pic_info = $_POST[$var_name];
                $pic_info['pic_id'] = $pic_id;
                if (! empty($code_info[$pic_id]['pic_img'])) { // 原图片
                    $pic_info['pic_img'] = $code_info[$pic_id]['pic_img'];
                }
                $file_name = 'web-' . $web_id . '-' . $code_id . '-' . $pic_id;
                $pic_name = $this->_upload_pic($file_name); // 上传图片
                if (! empty($pic_name)) {
                    $pic_info['pic_img'] = $pic_name;
                }
                
                $code_info[$pic_id] = $pic_info;
                Tpl::output('pic', $pic_info);
            }
            $code_info = $model_web_config->get_str($code_info, $code_type);
            $model_web_config->updateCode(array(
                'code_id' => $code_id
            ), array(
                'code_info' => $code_info
            ));
            
            Tpl::showpage('web_upload_slide', 'null_layout');
        }
    }

    /**
     * 保存焦点区切换大图
     */
    public function screen_picOp()
    {
        $code_id = intval($_POST['code_id']);
        $web_id = intval($_POST['web_id']);
        $model_web_config = Model('web_config');
        $code = $model_web_config->getCodeRow($code_id, $web_id);
        if (! empty($code)) {
            $code_type = $code['code_type'];
            $var_name = $code['var_name'];
            $code_info = $_POST[$var_name];
            $pic_id = intval($_POST['screen_id']);
            if ($pic_id > 0) {
                $var_name = "screen_pic";
                $pic_info = $_POST[$var_name];
                $pic_info['pic_id'] = $pic_id;
                if (! empty($code_info[$pic_id]['pic_img'])) { // 原图片
                    $pic_info['pic_img'] = $code_info[$pic_id]['pic_img'];
                }
                $file_name = 'web-' . $web_id . '-' . $code_id . '-' . $pic_id;
                $pic_name = $this->_upload_pic($file_name); // 上传图片
                if (! empty($pic_name)) {
                    $pic_info['pic_img'] = $pic_name;
                }
                
                $code_info[$pic_id] = $pic_info;
                Tpl::output('pic', $pic_info);
            }
            $code_info = $model_web_config->get_str($code_info, $code_type);
            $model_web_config->updateCode(array(
                'code_id' => $code_id
            ), array(
                'code_info' => $code_info
            ));
            
            Tpl::showpage('web_upload_screen', 'null_layout');
        }
    }

    /**
     * 保存焦点区切换小图
     */
    public function focus_picOp()
    {
        $code_id = intval($_POST['code_id']);
        $web_id = intval($_POST['web_id']);
        $model_web_config = Model('web_config');
        $code = $model_web_config->getCodeRow($code_id, $web_id);
        if (! empty($code)) {
            $code_type = $code['code_type'];
            $var_name = $code['var_name'];
            $code_info = $_POST[$var_name];
            
            $slide_id = intval($_POST['slide_id']);
            $pic_id = intval($_POST['pic_id']);
            if ($pic_id > 0) {
                $var_name = "focus_pic";
                $pic_info = $_POST[$var_name];
                $pic_info['pic_id'] = $pic_id;
                if (! empty($code_info[$slide_id]['pic_list'][$pic_id]['pic_img'])) { // 原图片
                    $pic_info['pic_img'] = $code_info[$slide_id]['pic_list'][$pic_id]['pic_img'];
                }
                $file_name = 'web-' . $web_id . '-' . $code_id . '-' . $slide_id . '-' . $pic_id;
                $pic_name = $this->_upload_pic($file_name); // 上传图片
                if (! empty($pic_name)) {
                    $pic_info['pic_img'] = $pic_name;
                }
                
                $code_info[$slide_id]['pic_list'][$pic_id] = $pic_info;
                Tpl::output('pic', $pic_info);
            }
            $code_info = $model_web_config->get_str($code_info, $code_type);
            $model_web_config->updateCode(array(
                'code_id' => $code_id
            ), array(
                'code_info' => $code_info
            ));
            
            Tpl::showpage('web_upload_focus', 'null_layout');
        }
    }

    /**
     * 上传图片
     */
    private function _upload_pic($file_name)
    {
        $pic_name = '';
        if (! empty($file_name)) {
            if (! empty($_FILES['pic']['name'])) { // 上传图片
                $upload = new UploadFile();
                $ext = end(explode('.', $_FILES['pic']['name']));
                $upload->set('default_dir', ATTACH_EDITOR);
                $upload->set('file_name', $file_name . "." . $ext);
                $result = $upload->upfile('pic');
                if ($result) {
                    $pic_name = ATTACH_EDITOR . "/" . $upload->file_name . '?' . mt_rand(100, 999); // 加随机数防止浏览器缓存图片
                }
            }
        }
        return $pic_name;
    }
}
