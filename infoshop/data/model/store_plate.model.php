<?php
/**
 * 店铺模型管理
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

class store_plateModel extends Model
{

    public function __construct()
    {
        parent::__construct('store_plate');
    }

    /**
     * 版式列表
     * 
     * @param array $condition            
     * @param string $field            
     * @param int $page            
     * @return array
     */
    public function getPlateList($condition, $field = '*', $page = 0)
    {
        return $this->field($field)
            ->where($condition)
            ->page($page)
            ->select();
    }

    /**
     * 版式详细信息
     * 
     * @param array $condition            
     * @return array
     */
    public function getPlateInfo($condition)
    {
        return $this->where($condition)->find();
    }

    /**
     * 添加版式
     * 
     * @param unknown $insert            
     * @return boolean
     */
    public function addPlate($insert)
    {
        return $this->insert($insert);
    }

    /**
     * 更新版式
     * 
     * @param array $update            
     * @param array $condition            
     * @return boolean
     */
    public function editPlate($update, $condition)
    {
        return $this->where($condition)->update($update);
    }

    /**
     * 删除版式
     * 
     * @param array $condition            
     * @return boolean
     */
    public function delPlate($condition)
    {
        return $this->where($condition)->delete();
    }
}
