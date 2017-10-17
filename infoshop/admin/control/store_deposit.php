<?php
/**
 * 店铺保证金等级管理
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

class store_depositControl extends SystemControl
{

    public function __construct()
    {
        parent::__construct();
        Language::read('store_deposit,store');
    }

    /**
     * 店铺保证金
     */
    public function store_depositOp()
    {
        /**
         * 读取语言包
         */
        $lang = Language::getLangContent();

        $model_deposit = Model('store_deposit');
        /**
         * 删除
         */
        if (chksubmit()) {
            if (! empty($_POST['check_id'])) {
                if (is_array($_POST['check_id'])) {
                    $model_store = Model('store');
                    foreach ($_POST['check_id'] as $k => $v) {
                            $model_deposit->del($v);
                    }
                }
                H('store_deposit', true);
                $this->log(L('nc_del,store_deposit') . '[ID:' . implode(',', $_POST['check_id']) . ']', 1);
                showMessage($lang['nc_common_del_succ']);
            } else {
                showMessage($lang['nc_common_del_fail']);
            }
        }
        $condition['like_dl_name'] = trim($_POST['like_dl_name']);
        $condition['order'] = 'id';

        $deposit_list = $model_deposit->getDepositList($condition);

        Tpl::output('like_dl_name', trim($_POST['like_dl_name']));
        Tpl::output('deposit_list', $deposit_list);
        Tpl::showpage('store_deposit.index');
    }

    /**
     * 新增等级
     */
    public function store_deposit_addOp()
    {
        $lang = Language::getLangContent();

        $model_deposit = Model('store_deposit');
        if (chksubmit()) {

            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array(
                    "input" => $_POST["level_name"],
                    "require" => "true",
                    "message" => $lang['store_deposit_name']
                ),
                array(
                    "input" => $_POST["amount"],
                    "require" => "true",
                    "message" => $lang['store_deposit_amount']
                ),
                array(
                    "input" => $_POST["memo"],
                    "require" => "true",
                    "message" => $lang['store_deposit_memo']
                )
            );
            $error = $obj_validate->validate();
            if ($error != '') {
                showMessage($error);
            } else {
                // 验证等级名称
                if (! $this->checkDepositName(array(
                    'level_name' => trim($_POST['level_name'])
                ))) {
                    showMessage($lang['now_store_deposit_name_is_there']);
                }
                $insert_array = array();
                $insert_array['level_name'] = trim($_POST['level_name']);
                $insert_array['amount'] = trim($_POST['amount']);
                $insert_array['memo'] = trim($_POST['memo']);
                $result = $model_deposit->add($insert_array);
                if ($result) {
                    H('store_deposit', true);
                    $this->log(L('nc_add,store_deposit') . '[' . $_POST['level_name'] . ']', 1);
                    showMessage($lang['nc_common_save_succ'], 'index.php?act=store_deposit&op=store_deposit');
                } else {
                    showMessage($lang['nc_common_save_fail']);
                }
            }
        }
        Tpl::showpage('store_deposit.add');
    }

    /**
     * 等级编辑
     */
    public function store_deposit_editOp()
    {
        $lang = Language::getLangContent();

        $model_deposit = Model('store_deposit');
        $deposit_array = $model_deposit->getOnedeposit(intval($_GET['id']));
        if (empty($deposit_array)) {
            showMessage($lang['deposit_parameter_error']);
        }
        if (chksubmit()) {
            if (! $_POST['id']) {
                showMessage($lang['deposit_parameter_error'], 'index.php?act=store_deposit&op=store_deposit');
            }
            /**
             * 验证
             */
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array(
                    "input" => $_POST["level_name"],
                    "require" => "true",
                    "message" => $lang['store_deposit_name_no_null']
                ),
                array(
                    "input" => $_POST["amount"],
                    "require" => "true",
                    "message" => $lang['deposit_amount_no_null']
                ),
                array(
                    "input" => $_POST["memo"],
                    "require" => "true",
                    "message" => $lang['deposit_memo_no_null']
                )
            );
            $error = $obj_validate->validate();
            if ($error != '') {
                showMessage($error);
            } else {
                // 验证等级名称
                if (! $this->checkDepositName(array(
                    'level_name' => trim($_POST['level_name']),
                    'id' => intval($_POST['id'])
                ))) {
                    showMessage($lang['now_store_deposit_name_is_there'], 'index.php?act=store_deposit&op=store_deposit_edit&id=' . intval($_POST['id']));
                }
                $update_array = array();
                $update_array['id'] = intval($_POST['id']);
                $update_array['level_name'] = trim($_POST['level_name']);
                $update_array['amount'] = trim($_POST['amount']);
                $update_array['memo'] = trim($_POST['memo']);

                $result = $model_deposit->update($update_array);

                $update_deposit = array();
                $update_deposit['deposit_id'] = intval($deposit_array['id']);
                $update_deposit['deposit_level'] = trim($_POST['level_name']);
                $update_deposit['deposit_amount'] = trim($_POST['amount']);

                $deposit_result = $model_deposit->update_deposit($update_deposit);

                if ($result && $deposit_result) {
                    H('store_deposit', true, 'file');
                    $this->log(L('nc_edit,store_deposit') . '[' . $_POST['level_name'] . ']', 1);
                    showMessage($lang['nc_common_save_succ']);
                } else {
                    showMessage($lang['nc_common_save_fail']);
                }
            }
        }

        Tpl::output('deposit_array', $deposit_array);
        Tpl::showpage('store_deposit.edit');
    }

    /**
     * 删除等级
     */
    public function store_deposit_delOp()
    {
        /**
         * 读取语言包
         */
        $lang = Language::getLangContent();
        $model_deposit = Model('store_deposit');
        if (intval($_GET['id']) > 0) {
            $model_deposit->del(intval($_GET['id']));


            H('store_deposit', true);
            $this->log(L('nc_del,store_deposit') . '[ID:' . intval($_GET['id']) . ']', 1);
            showMessage($lang['nc_common_del_succ'], 'index.php?act=store_deposit&op=store_deposit');
        } else {
            showMessage($lang['nc_common_del_fail'], 'index.php?act=store_deposit&op=store_deposit');
        }
    }



    /**
     * ajax操作
     */
    public function ajaxOp()
    {
        switch ($_GET['branch']) {
            /**
             * 保证金等级：验证是否有重复的名称
             */
            case 'check_deposit_name':
                if ($this->checkDepositName($_GET)) {
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
     * 查询保证金等级名称是否存在
     */
    private function checkDepositName($param)
    {
        $model_deposit = Model('store_deposit');
        $condition['level_name'] = $param['level_name'];
        $condition['no_id'] = $param['id'];
        $list = $model_deposit->getDepositList($condition);
        if (empty($list)) {
            return true;
        } else {
            return false;
        }
    }
}
