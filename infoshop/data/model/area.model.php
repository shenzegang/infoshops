<?php
/**
 * 地区模型
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

class areaModel extends Model
{

    public function __construct()
    {
        parent::__construct('area');
    }

    public function getAreaList($condition = array(), $fields = '*', $group = '')
    {
        return $this->where($condition)
            ->field($fields)
            ->limit(false)
            ->group($group)
            ->select();
    }
}