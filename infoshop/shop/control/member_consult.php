<?php
/**
 * 买家商品咨询
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

class member_consultControl extends BaseMemberControl
{

    public function __construct()
    {
        parent::__construct();
        Language::read('member_store_consult_index');
    }

    /**
     * 商品咨询首页
     */
    public function indexOp()
    {
        $this->my_consultOp();
    }

    /**
     * 查询买家商品咨询
     */
    public function my_consultOp()
    {
        // 实例化商品咨询模型
        $consult = Model('consult');
        $page = new Page();
        $page->setEachNum(10);
        $page->setStyle('admin');
        $list_consult = array();
        $search_array = array();
        $search_array['type'] = $_GET['type'];
        $search_array['member_id'] = "{$_SESSION['member_id']}";
        $list_consult = $consult->getConsultList($search_array, $page);
        Tpl::output('show_page', $page->show());
        Tpl::output('list_consult', $list_consult);
        $_GET['type'] = empty($_GET['type']) ? 'consult_list' : $_GET['type'];
        // 查询会员信息
        $this->get_member_info();
        self::profile_menu('my_consult', $_GET['type']);
        Tpl::output('menu_sign', 'consult');
        Tpl::output('menu_sign_url', 'index.php?act=member_consult&op=my_consult');
        Tpl::output('menu_sign1', $_GET['type']);
        Tpl::showpage('member_my_consult');
    }

    /**
     * 用户中心右边，小导航
     *
     * @param string $menu_type            
     * @param string $menu_key            
     * @param array $array            
     * @return
     *
     */
    private function profile_menu($menu_type, $menu_key = '', $array = array())
    {
        Language::read('member_layout');
        $menu_array = array();
        switch ($menu_type) {
            case 'my_consult':
                $menu_array = array(
                    1 => array(
                        'menu_key' => 'consult_list',
                        'menu_name' => Language::get('nc_member_path_all_consult'),
                        'menu_url' => 'index.php?act=member_consult&op=my_consult'
                    ),
                    2 => array(
                        'menu_key' => 'to_reply',
                        'menu_name' => Language::get('nc_member_path_unreplied_consult'),
                        'menu_url' => 'index.php?act=member_consult&op=my_consult&type=to_reply'
                    ),
                    3 => array(
                        'menu_key' => 'replied',
                        'menu_name' => Language::get('nc_member_path_replied_consult'),
                        'menu_url' => 'index.php?act=member_consult&op=my_consult&type=replied'
                    )
                );
                break;
        }
        if (! empty($array)) {
            $menu_array[] = $array;
        }
        Tpl::output('member_menu', $menu_array);
        Tpl::output('menu_key', $menu_key);
    }
}