<?php
/**
 * 文件的详细描述
 *
 *
 * @copyright  Copyright (c) 2003-2015 B2B2C Inc. (http://www.infol.com.cn)
 * @license    http://www.infol.com.cn
 * @link       http://www.infol.com.cn
 * @since      File available since Release v1.1
 */
defined('CorShop') or exit('Access Invalid!');

class store_smslogModel extends Model
{

    public function __construct()
    {
        parent::__construct('sms_log');
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
     * 模板列表
     *
     * @param array $condition
     *            检索条件
     * @return array 数组形式的返回结果
     */
    public function getTemplatesList($condition)
    {
        $condition_str = $this->_condition($condition);
        $param = array();
        $param['table'] = 'mail_msg_temlates';
        $param['where'] = $condition_str;
        $result = Db::select($param);
        return $result;
    }

    /**
     * 构造检索条件
     *
     * @param array $condition
     *            检索条件
     * @return string 字符串形式的返回结果
     */
    private function _condition($condition)
    {
        $condition_str = '';
        
        if ($condition['type'] != '') {
            $condition_str .= " and type = '" . $condition['type'] . "'";
        }
        if ($condition['code'] != '') {
            $condition_str .= " and code = '" . $condition['code'] . "'";
        }
        return $condition_str;
    }

    /**
     * 取得配置内容
     *
     * @param string $code
     *            店铺id
     * @return array $rs_row 返回数组形式的查询结果
     */
    public function getOne($code)
    {
        if (! empty($code)) {
            $param = array();
            $param['table'] = 'sms_conf';
            $param['field'] = 'store_id';
            $param['value'] = $code;
            $result = Db::getRow($param);
            return $result;
        } else {
            return false;
        }
    }

    /**
     * 新增
     *
     * @param array $param
     *            参数内容
     * @return bool 布尔类型的返回结果
     */
    public function add($param)
    {
        if (empty($param)) {
            return false;
        }
        if (is_array($param)) {
            $tmp = array();
            foreach ($param as $k => $v) {
                $tmp[$k] = $v;
            }
            $result = Db::insert('sms_log', $tmp);
            return $result;
        } else {
            return false;
        }
    }

    /**
     * 更新模板内容
     *
     * @param array $param
     *            更新参数
     * @return bool 布尔类型的返回结果
     */
    public function update($param)
    {
        if (empty($param)) {
            return false;
        }
        if (is_array($param)) {
            $tmp = array();
            foreach ($param as $k => $v) {
                $tmp[$k] = $v;
            }
            $where = " store_id = '" . $param['store_id'] . "'";
            $result = Db::update('sms_conf', $tmp, $where);
            return $result;
        } else {
            return false;
        }
    }
}