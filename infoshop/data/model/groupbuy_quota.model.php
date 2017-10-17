<?php
/**
 * 团购套餐模型 
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

class groupbuy_quotaModel extends Model
{

    public function __construct()
    {
        parent::__construct('groupbuy_quota');
    }

    /**
     * 读取团购套餐列表
     * 
     * @param array $condition
     *            查询条件
     * @param int $page
     *            分页数
     * @param string $order
     *            排序
     * @param string $field
     *            所需字段
     * @return array 团购套餐列表
     *        
     */
    public function getGroupbuyQuotaList($condition, $page = null, $order = '', $field = '*')
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
    public function getGroupbuyQuotaInfo($condition)
    {
        $result = $this->where($condition)->find();
        return $result;
    }

    /**
     * 获取当前可用套餐
     * 
     * @param int $store_id            
     * @return array
     *
     */
    public function getGroupbuyQuotaCurrent($store_id)
    {
        $condition = array();
        $condition['store_id'] = $store_id;
        $condition['end_time'] = array(
            'gt',
            TIMESTAMP
        );
        $xianshi_quota_list = $this->getGroupbuyQuotaList($condition, null, 'end_time desc');
        $xianshi_quota_info = $xianshi_quota_list[0];
        return $xianshi_quota_info;
    }

    /*
     * 增加
     * @param array $param
     * @return bool
     *
     */
    public function addGroupbuyQuota($param)
    {
        return $this->insert($param);
    }

    /*
     * 更新
     * @param array $update
     * @param array $condition
     * @return bool
     *
     */
    public function editGroupbuyQuota($update, $condition)
    {
        return $this->where($condition)->update($update);
    }

    /*
     * 删除
     * @param array $condition
     * @return bool
     *
     */
    public function delGroupbuyQuota($condition)
    {
        return $this->where($condition)->delete();
    }
}
