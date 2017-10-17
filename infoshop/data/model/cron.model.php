<?php
/**
 * 任务队列
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

class cronModel extends Model
{

    public function __construct()
    {
        parent::__construct('cron');
    }

    /**
     * 取单条任务信息
     * 
     * @param array $condition            
     */
    public function getCronInfo($condition = array())
    {
        return $this->where($condition)->find();
    }

    /**
     * 任务队列列表
     * 
     * @param array $condition            
     * @param number $limit            
     * @return array
     */
    public function getCronList($condition, $limit = 10)
    {
        return $this->where($condition)
            ->limit($limit)
            ->select();
    }

    /**
     * 保存任务队列
     *
     * @param unknown $insert            
     * @return array
     */
    public function addCronAll($insert)
    {
        return $this->insertAll($insert);
    }

    /**
     * 保存任务队列
     *
     * @param array $insert            
     * @return boolean
     */
    public function addCron($insert)
    {
        return $this->insert($insert);
    }

    /**
     * 删除任务队列
     *
     * @param array $condition            
     * @return array
     */
    public function delCron($condition)
    {
        return $this->where($condition)->delete();
    }
}