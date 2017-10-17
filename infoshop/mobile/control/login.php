<?php
/**
 * 前台登录 退出操作
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

class loginControl extends mobileHomeControl
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 登录
     */
    public function indexOp()
    {
        if (empty($_POST['username']) || empty($_POST['password']) || ! in_array($_POST['client'], $this->client_type_array)) {
            output_error('登陆失败');
        }
        
        $model_member = Model('member');
        
        $array = array();
        $array['member_name'] = $_POST['username'];
        $array['member_passwd'] = md5($_POST['password']);
        $member_info = $model_member->getMemberInfo($array);
        
        if (! empty($member_info)) {
            $token = $this->_get_token($member_info['member_id'], $member_info['member_name'], $_POST['client']);
            if ($token) {
                
                $model_member->createSession($member_info);
                
                // 添加会员哈金豆
                if (C('points_isuse')) {
                    // 一天内只有第一次登录赠送哈金豆
                    if (trim(@date('Y-m-d', $member_info['member_login_time'])) != trim(date('Y-m-d'))) {
                        $points_model = Model('points');
                        $points_model->savePointsLog('login', array(
                            'pl_memberid' => $member_info['member_id'],
                            'pl_membername' => $member_info['member_name']
                        ), true);
                    }
                }
                
                output_data(array(
                    'username' => $member_info['member_name'],
                    'paypasswd' => $member_info['member_paypasswd'],
                    'key' => $token
                ));
            } else {
                output_error('登陆失败');
            }
        } else {
            output_error('用户名密码错误');
        }
    }

    /**
     * 登陆生成token
     */
    private function _get_token($member_id, $member_name, $client)
    {
        $model_mb_user_token = Model('mb_user_token');
        
        // 重新登陆后以前的令牌失效
        // 暂时停用
        // $condition = array();
        // $condition['member_id'] = $member_id;
        // $condition['client_type'] = $_POST['client'];
        // $model_mb_user_token->delMbUserToken($condition);
        
        // 生成新的token
        $mb_user_token_info = array();
        $token = md5($member_name . strval(TIMESTAMP) . strval(rand(0, 999999)));
        $mb_user_token_info['member_id'] = $member_id;
        $mb_user_token_info['member_name'] = $member_name;
        $mb_user_token_info['token'] = $token;
        $mb_user_token_info['login_time'] = TIMESTAMP;
        $mb_user_token_info['client_type'] = $_POST['client'];
        
        $result = $model_mb_user_token->addMbUserToken($mb_user_token_info);
        
        if ($result) {
            return $token;
        } else {
            return null;
        }
    }

    /**
     * 注册
     */
    public function registerOp()
    {
        $model_member = Model('member');
        
        $register_info = array();
        $register_info['username'] = $_POST['username'];
        $register_info['password'] = $_POST['password'];
        $register_info['password_confirm'] = $_POST['password_confirm'];
        $register_info['email'] = $_POST['email'];
        $member_info = $model_member->register($register_info);
        if (! isset($member_info['error'])) {
            $token = $this->_get_token($member_info['member_id'], $member_info['member_name'], $_POST['client']);
            if ($token) {
                output_data(array(
                    'username' => $member_info['member_name'],
                    'key' => $token
                ));
            } else {
                output_error('注册失败');
            }
        } else {
            output_error($member_info['error']);
        }
    }
}
