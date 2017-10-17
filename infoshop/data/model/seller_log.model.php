<?php
/**
 * 卖家日志模型
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

class seller_logModel extends Model
{

    public function __construct()
    {
        parent::__construct('seller_log');
    }

    /**
     * 读取列表
     * 
     * @param array $condition            
     *
     */
    public function getSellerLogList($condition, $page = '', $order = '', $field = '*')
    {
        $result = $this->field($field)
            ->where($condition)
            ->page($page)
            ->order($order)
            ->select();
        return $result;
    }

    /**
     * 读取单条记录
     * 
     * @param array $condition            
     *
     */
    public function getSellerLogInfo($condition)
    {
        $result = $this->where($condition)->find();
        return $result;
    }

    /*
     * 增加
     * @param array $param
     * @return bool
     */
    public function addSellerLog($param)
    {
        return $this->insert($param);
    }

    /*
     * 删除
     * @param array $condition
     * @return bool
     */
    public function delSellerLog($condition)
    {
        return $this->where($condition)->delete();
    }
}
