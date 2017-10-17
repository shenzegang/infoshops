<?php
/**
 * 店铺动态自动发布
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

class store_sns_settingModel extends Model
{

    public function __construct()
    {
        parent::__construct('store_sns_setting');
    }

    /**
     * 获取单条动态设置设置信息
     *
     * @param unknown $condition            
     * @param string $field            
     * @return array
     */
    public function getStoreSnsSettingInfo($condition, $field = '*')
    {
        return $this->field($field)
            ->where($condition)
            ->find();
    }

    /**
     * 保存店铺动态设置
     *
     * @param unknown $insert            
     * @return boolean
     */
    public function saveStoreSnsSetting($insert)
    {
        return $this->insert($insert);
    }
}