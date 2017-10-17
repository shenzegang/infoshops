<?php
/**
 * 商家申请保证金
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

class seller_depositControl extends BaseSellerControl
{

    public function __construct()
    {
        parent::__construct();
        Language::read('member_store_index');
    }

    /**
     * 卖家店铺设置
     *
     * @param
     *            string
     * @param
     *            string
     * @return
     *
     */
    public function seller_depositOp()
    {
        /**
         * 实例化模型
         */
        $model_class = Model('seller_deposit');
        /**
         * 获取设置
         */
        $store_id = $_SESSION['store_id']; // 当前店铺ID
        $seller_id = $_SESSION['seller_id']; // 当前卖家ID
        /**
         * 获取卖家信息
         */
        $deposit_list = $model_class->getSellerDepositList($condition);
        Tpl::output('deposit_list', $deposit_list);

        /**
         * 查看商家是否已申请保证金
         */
        $result = $model_class->getOneSellerDeposit(intval($seller_id));
        if($result != null)
        {
            Tpl::output('seller_deposit', $result);
        }

        /**
         * 保证金等级
         */
        $model_deposit = Model('store_deposit');
        $deposit_list = $model_deposit->getDepositList($condition);
        Tpl::output('deposit_list', $deposit_list);

        /**
         * 申请保证金
         */
        $model_deposit = Model('seller_deposit');
        if (chksubmit()) {
                $level_name = $_POST['deposit_level'];
                $deposit_array = $model_deposit->getOneDeposit($level_name);
                $seller_array = $model_deposit->getOneSeller(intval($store_id));
                $insert_array = array();
                $insert_array['seller_id'] = trim($seller_array['seller_id']);
                $insert_array['seller_name'] = trim($seller_array['seller_name']);
                $insert_array['deposit_id'] = trim($deposit_array['id']);
                $insert_array['deposit_level'] = trim($_POST['deposit_level']);
                $insert_array['deposit_amount'] = trim($deposit_array['amount']);
                $insert_array['deposit_voucher'] = $this->upload_image('deposit_voucher');
                $insert_array['paid'] = 'N';
                $insert_array['apply_date'] = date('Y-m-d');
                $insert_array['is_show'] = isset($_POST['is_show']) ? 1 : 0;
                $result = $model_deposit->add($insert_array);
                if ($result) {
                    H('seller_deposit', true);
                    showMessage('申请成功', 'index.php?act=seller_deposit&op=seller_deposit');
                } else {
                    showMessage('申请失败');
                }
        }

        /**
         * 输出店铺信息
         */
        self::profile_menu('store_setting');
        /**
         * 页面输出
         */
        Tpl::showpage('seller_deposit');
    }


    /**
     * 用户中心右边，小导航
     *
     * @param string $menu_type
     * @param string $menu_key
     * @return
     *
     */
    private function profile_menu($menu_key = '')
    {
        Language::read('member_layout');
        $menu_array = array(
            1 => array(
                'menu_key' => 'store_setting',
                'menu_name' => Language::get('nc_member_path_seller_deposit'),
                'menu_url' => 'index.php?act=store_setting&op=store_setting'
            )
        );
        Tpl::output('member_menu', $menu_array);
        Tpl::output('menu_key', $menu_key);
    }

    /**
     * 修改是否显示保证金给买家看
     *
     * @param string $menu_type
     * @param string $menu_key
     * @return
     *
     */
    public function editOp()
    {
        if (chksubmit()) {
            $model_class = Model('seller_deposit');
            $level_name = $_POST['deposit_level'];
            $deposit_array = $model_class->getOneDeposit($level_name);
            $update_array = array();
            $update_array['id'] = intval($_GET['id']);
            $update_array['is_show'] = isset($_POST['is_show']) ? 1 : 0;

            $result = $model_class->editDeposit($update_array);
            if ($result) {
                showMessage('修改成功', 'index.php?act=seller_deposit&op=seller_deposit');
            } else {
                showMessage('修改失败');
            }
        }
    }
    /**
     * 重新申请商家保证金
     *
     * @param string $menu_type
     * @param string $menu_key
     * @return
     *
     */
    public function re_addOp()
    {
        $model_deposit = Model('store_deposit');
        $deposit_list = $model_deposit->getDepositList($condition);
        Tpl::output('deposit_list', $deposit_list);
        if (chksubmit()) {
            $model_class = Model('seller_deposit');
            $level_name = $_POST['deposit_level'];
            $deposit_array = $model_class->getOneDeposit($level_name);
            $update_array = array();
            $update_array['id'] = intval($_GET['id']);
            $update_array['deposit_id'] = trim($deposit_array['id']);
            $update_array['deposit_amount'] = trim($deposit_array['amount']);
            $update_array['deposit_level'] = trim($_POST['deposit_level']);
            $update_array['paid'] = 'N';
            $pic = $this->upload_image('deposit_voucher');
            if($pic != "") {
                $update_array['deposit_voucher'] = $pic;
            }
            $update_array['is_show'] = isset($_POST['is_show']) ? 1 : 0;

            $result = $model_class->editDeposit($update_array);
            if ($result) {
                showMessage('修改成功', 'index.php?act=seller_deposit&op=seller_deposit');
            } else {
                showMessage('修改失败');
            }
        }
        /**
         * 输出店铺信息
         */
        self::profile_menu('store_setting');
        /**
         * 页面输出
         */
        Tpl::showpage('seller_deposit');
    }

    /*
     * 上传图片
     */
    private function upload_image($file)
    {
        $pic_name = '';
        $upload = new UploadFile();
        $uploaddir = ATTACH_PATH . DS . 'seller_deposit' . DS;
        $upload->set('default_dir', $uploaddir);
        $upload->set('allow_type', array(
            'jpg',
            'jpeg',
            'gif',
            'png'
        ));
        if (! empty($_FILES[$file]['name'])) {
            $result = $upload->upfile($file);
            if ($result) {
                $pic_name = $upload->file_name;
                $upload->file_name = '';
            }
        }
        return $pic_name;
    }

}
