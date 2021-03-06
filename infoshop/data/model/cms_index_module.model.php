<?php
/**
 * cms首页模块模型
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

class cms_index_moduleModel extends Model
{

    public function __construct()
    {
        parent::__construct('cms_index_module');
    }

    /**
     * 读取列表
     * 
     * @param array $condition            
     *
     */
    public function getList($condition, $page = null, $order = '', $field = '*')
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
    public function getOne($condition, $order = '')
    {
        $result = $this->where($condition)
            ->order($order)
            ->find();
        return $result;
    }

    /*
     * 判断是否存在
     * @param array $condition
     *
     */
    public function isExist($condition)
    {
        $result = $this->getOne($condition);
        if (empty($result)) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    /*
     * 增加
     * @param array $param
     * @return bool
     */
    public function save($param)
    {
        return $this->insert($param);
    }

    /*
     * 更新
     * @param array $update
     * @param array $condition
     * @return bool
     */
    public function modify($update, $condition)
    {
        return $this->where($condition)->update($update);
    }

    /*
     * 删除
     * @param array $condition
     * @return bool
     */
    public function drop($condition)
    {
        return $this->where($condition)->delete();
    }
}

