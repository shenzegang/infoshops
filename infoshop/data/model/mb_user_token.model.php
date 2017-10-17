<?php
/**
 * 手机端令牌模型
 *
 * 
 *
 *
 * @copyright  Copyright (c) 2007-2012 B2B2C Inc. (http://www.infol.com.cn)
 * @license    http://www.infol.com.cn
 * @link       http://www.infol.com.cn
 * @since      File available since Release v1.1
 */
defined('CorShop') or exit('Access Invalid!');

class mb_user_tokenModel extends Model
{

    public function __construct()
    {
        parent::__construct('mb_user_token');
    }

    /**
     * 查询
     *
     * @param array $condition
     *            查询条件
     * @return array
     */
    public function getMbUserTokenInfo($condition)
    {
        return $this->where($condition)->find();
    }

    public function getMbUserTokenInfoByToken($token)
    {
        if (empty($token)) {
            return null;
        }
        return $this->getMbUserTokenInfo(array(
            'token' => $token
        ));
    }

    /**
     * 新增
     *
     * @param array $param
     *            参数内容
     * @return bool 布尔类型的返回结果
     */
    public function addMbUserToken($param)
    {
        return $this->insert($param);
    }

    /**
     * 删除
     *
     * @param int $condition
     *            条件
     * @return bool 布尔类型的返回结果
     */
    public function delMbUserToken($condition)
    {
        return $this->where($condition)->delete();
    }
}
