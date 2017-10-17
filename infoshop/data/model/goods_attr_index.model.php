<?php
/**
 * 商品与属性对应
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

class goods_attr_indexModel extends Model
{

    public function __construct()
    {
        parent::__construct('goods_attr_index');
    }

    /**
     * 对应列表
     *
     * @param array $condition            
     * @param string $field            
     * @return array
     */
    public function getGoodsAttrIndexList($condition, $field = '*')
    {
        return $this->where($condition)
            ->field($field)
            ->select();
    }
}