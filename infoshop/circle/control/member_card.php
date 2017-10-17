<?php

/**
 * The AJAX call member information
 *
 *
 *
 *
 * @copyright  Copyright (c) 2003-2015 B2B2C Inc. (http://www.infol.com.cn)
 * @license    http://www.infol.com.cn
 * @link       http://www.infol.com.cn
 * @since      File available since Release v1.1
 */
class member_cardControl extends BaseCircleControl
{

    public function mcard_infoOp()
    {
        $uid = intval($_GET['uid']);
        $member_list = Model()->table('circle_member')
            ->field('member_id,circle_id,circle_name,cm_level,cm_exp')
            ->where(array(
            'member_id' => $uid,
            'cm_state' => 1
        ))
            ->select();
        if (empty($member_list)) {
            echo 'false';
            exit();
        }
        echo json_encode($member_list);
        exit();
    }
}