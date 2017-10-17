<?php
/**
 * 我的商城
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

class member_indexControl extends mobileMemberControl
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 我的商城
     */
    public function indexOp()
    {
        if ($_SESSION['member_id'] == '')
            return;
            // 判断cookie是否存在
        $cookie_name = 'msgnewnum' . $_SESSION['member_id'];
        if (cookie($cookie_name) != null && intval(cookie($cookie_name)) >= 0) {
            $countnum = intval(cookie($cookie_name));
        } else {
            $message_model = Model('message');
            $countnum = $message_model->countNewMessage($_SESSION['member_id']);
            setNcCookie($cookie_name, "$countnum", 2 * 3600); // 保存1天
        }
        
        $member_info = array();
        $member_info['user_name'] = $this->member_info['member_name'];
        $member_info['avator'] = getMemberAvatarForID($this->member_info['member_id']);
        $member_info['point'] = $this->member_info['member_points'];
        $member_info['predepoit'] = $this->member_info['available_predeposit'];
        $member_info['message_num'] = $countnum;
        
        output_data(array(
            'member_info' => $member_info
        ));
    }
}
