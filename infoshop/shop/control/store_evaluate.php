<?php
/**
 * 会员中心——卖家评价
 *
 * @copyright  Copyright (c) 2003-2015 B2B2C Inc. (http://www.infol.com.cn)
 * @license    http://www.infol.com.cn
 * @link       http://www.infol.com.cn
 * @since      File available since Release v1.1
 */
defined('CorShop') or exit('Access Invalid!');

class store_evaluateControl extends BaseSellerControl
{

    public function __construct()
    {
        parent::__construct();
        Language::read('member_layout,member_evaluate');
        Tpl::output('pj_act', 'store_evaluate');
    }

    /**
     * 评价列表
     */
    public function listOp()
    {
        $model_evaluate_goods = Model('evaluate_goods');
        
        $condition = array();
        if (! empty($_GET['goods_name'])) {
            $condition['geval_goodsname'] = array(
                'like',
                '%' . $_GET['goods_name'] . '%'
            );
        }
        if (! empty($_GET['member_name'])) {
            $condition['geval_frommembername'] = array(
                'like',
                '%' . $_GET['member_name'] . '%'
            );
        }
        $condition['geval_storeid'] = $_SESSION['store_id'];
        $goodsevallist = $model_evaluate_goods->getEvaluateGoodsList($condition, 10, 'geval_id desc');
        
        Tpl::output('goodsevallist', $goodsevallist);
        Tpl::output('show_page', $model_evaluate_goods->showpage());
        Tpl::showpage('evaluation.index');
    }

    /**
     * 解释来自买家的评价
     */
    public function explain_saveOp()
    {
        $geval_id = intval($_POST['geval_id']);
        $geval_explain = $_POST['geval_explain'];
        $data = array();
        $data['result'] = true;
        $model_evaluate_goods = Model('evaluate_goods');
        $evaluate_info = $model_evaluate_goods->getEvaluateGoodsInfoByID($geval_id);
        if (empty($evaluate_info)) {
            $data['result'] = false;
            $data['message'] = L('param_error');
            echo json_encode($data);
            die();
        }
        //20150813 tjz 修改 追加回复  以|作为两次解释的分割
        //判断该评价是否为第一次
        if(!empty($evaluate_info['geval_explain'])){
            //判断追加次数是否超过两次
            str_replace('|','',$evaluate_info['geval_explain'],$count);
            if($count<1){
                $geval_explain=$evaluate_info['geval_explain']."|".$geval_explain;
            }else{
                $data['result'] = false;
                $data['message'] = '追加解释已达上限！';
                echo json_encode($data);
                die();
            }
        }
        $update = array(
            'geval_explain' =>$geval_explain
        );
        $condition = array(
            'geval_id' => $geval_id
        );
        $result = $model_evaluate_goods->editEvaluateGoods($update, $condition);
        
        if ($result) {
            $data['message'] = '解释成功';
            $data['content']=$geval_explain;
        } else {
            $data['result'] = false;
            $data['message'] = '解释保存失败';
        }
        echo json_encode($data);
        die();
    }

    /**
     * 批量解释来自买家的评价
     */
    public function batch_explain_saveOp()
    {
        //解析ids字符串
        $geval_ids = explode(",", $_POST['geval_ids']);
        $geval_explain = $_POST['geval_batch_explain'];
        $data = array();
        $data['result'] = true;
        $model_evaluate_goods = Model('evaluate_goods');
        foreach ($geval_ids as $geval_id) {
            $evaluate_info = $model_evaluate_goods->getEvaluateGoodsInfoByID($geval_id);
            if (empty($evaluate_info)) {
                $data['result'] = false;
                $data['message'] = L('param_error');
                echo json_encode($data);
                die();
            }
            //20150813 tjz 修改 追加回复  以|作为两次解释的分割
            //判断该评价是否为第一次
            if(!empty($evaluate_info['geval_explain'])){
                //判断追加次数是否超过两次
                str_replace('|','',$evaluate_info['geval_explain'],$count);
                if($count<1){
                    $geval_explain=$evaluate_info['geval_explain']."|".$geval_explain;
                }else{
                    $data['result'] = false;
                    $data['message'] = '追加解释已达上限！';
                    echo json_encode($data);
                    die();
                }
            }
            $update = array(
                'geval_explain' =>$geval_explain
            );
            $condition = array(
                'geval_id' => $geval_id
            );
            $result = $model_evaluate_goods->editEvaluateGoods($update, $condition);
            if ($result) {
                $data['message'] = '解释成功';
                $data['content']=$geval_explain;
            } else {
                $data['result'] = false;
                $data['message'] = '解释保存失败';
            }
        }
        echo json_encode($data);
        die();
    }
}
