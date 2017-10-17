<?php
/**
 * 店铺统计
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

class statisticsModel
{

    /**
     * 更新统计表
     *
     * @param array $param            
     */
    public function updatestat($param)
    {
        if (empty($param)) {
            return false;
        }
        $result = Db::update($param['table'], array(
            $param['field'] => array(
                'sign' => 'increase',
                'value' => $param['value']
            )
        ), $param['where']);
        return $result;
    }
}