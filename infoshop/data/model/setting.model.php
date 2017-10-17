<?php
/**
 * 系统设置内容
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

class settingModel extends Model
{

    public function __construct()
    {
        parent::__construct('setting');
    }

    /**
     * 读取系统设置信息
     *
     * @param string $name
     *            系统设置信息名称
     * @return array 数组格式的返回结果
     */
    public function getRowSetting($name)
    {
        $param = array();
        $param['table'] = 'setting';
        $param['where'] = "name='" . $name . "'";
        $result = Db::select($param);
        if (is_array($result) and is_array($result[0])) {
            return $result[0];
        }
        return false;
    }

    /**
     * 读取系统设置列表
     *
     * @param            
     *
     * @return array 数组格式的返回结果
     */
    public function getListSetting()
    {
        $param = array();
        $param['table'] = 'setting';
        $result = Db::select($param);
        /**
         * 整理
         */
        if (is_array($result)) {
            $list_setting = array();
            foreach ($result as $k => $v) {
                $list_setting[$v['name']] = $v['value'];
            }
        }
        return $list_setting;
    }

    /**
     * 更新信息
     *
     * @param array $param
     *            更新数据
     * @return bool 布尔类型的返回结果
     */
    public function updateSetting($param)
    {
        if (empty($param)) {
            return false;
        }
        
        if (is_array($param)) {
            /*
             * 检查是否有尚未添加的字段
             */
            $tab_name = C('tablepre') . 'setting';
            $data = $this -> query("SELECT * FROM $tab_name");
            $keys = array();
            foreach($data as $val){
                $keys[$val['name']] = $val['value'];
            }
           
            foreach ($param as $k => $v) {
                $tmp = array();
                $specialkeys_arr = array(
                    'statistics_code'
                );
                $tmp['value'] = (in_array($k, $specialkeys_arr) ? htmlentities($v, ENT_QUOTES) : $v);
                $where = " name = '" . $k . "'";
                $result = Db::update('setting', $tmp, $where);
                $temp = array();
                
                if(!array_key_exists($k, $keys)){
                    $this -> execute("INSERT INTO $tab_name (name, value) VALUES ('$k', '$v')");
                }
                
                if ($result !== true) {
                    return $result;
                }
            }
            H('setting', true);
            @unlink(BASE_DATA_PATH . DS . 'cache' . DS . 'setting.php');
            return true;
        } else {
            return false;
        }
    }
}
