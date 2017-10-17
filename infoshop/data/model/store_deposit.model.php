<?php
/**
 * 店铺保证金模型
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

class store_depositModel
{

    /**
 * 列表
 *
 * @param array $condition
 *            检索条件
 * @return array 数组结构的返回结果
 */
    public function getDepositList($condition = array())
    {
        $condition_str = $this->_condition($condition);
        $param = array();
        $param['table'] = 'deposit_level';
        $param['where'] = $condition_str;
        // $param['order'] = 'id';
        $param['order'] = $condition['order'] ? $condition['order'] : 'id';
        $result = Db::select($param);
        return $result;
    }

    /**
     * 构造检索条件
     *
     * @param int $id
     *            记录ID
     * @return string 字符串类型的返回结果
     */
    private function _condition($condition)
    {
        $condition_str = '';

        if ($condition['like_dl_name'] != '') {
            $condition_str .= " and level_name like '%" . $condition['like_dl_name'] . "%'";
        }
        if ($condition['no_id'] != '') {
            $condition_str .= " and id != '" . intval($condition['no_id']) . "'";
        }
        if ($condition['level_name'] != '') {
            $condition_str .= " and level_name = '" . $condition['level_name'] . "'";
        }
        return $condition_str;
    }

    /**
     * 取单个内容
     *
     * @param int $id
     *            分类ID
     * @return array 数组类型的返回结果
     */
    public function getOneDeposit($id)
    {
        if (intval($id) > 0) {
            $param = array();
            $param['table'] = 'deposit_level';
            $param['field'] = 'id';
            $param['value'] = intval($id);
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
            $result = Db::insert('deposit_level', $tmp);
            return $result;
        } else {
            return false;
        }
    }

    /**
     * 更新信息
     *
     * @param array $param
     *            更新数据
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
            $where = " id = '{$param['id']}'";
            $result = Db::update('deposit_level', $tmp, $where);
            return $result;
        } else {
            return false;
        }
    }

    /**
     * 更新seller_deposit表信息
     *
     * @param array $param
     *            更新数据
     * @return bool 布尔类型的返回结果
     */
    public function update_deposit($param)
    {
        if (empty($param)) {
            return false;
        }
        if (is_array($param)) {
            $tmp = array();
            foreach ($param as $k => $v) {
                $tmp[$k] = $v;
            }
            $where = " deposit_id = '{$param['deposit_id']}'";
            $result = Db::update('seller_deposit', $tmp, $where);
            return $result;
        } else {
            return false;
        }
    }

    /**
     * 删除保证金等级
     *
     * @param int $id
     *            记录ID
     * @return bool 布尔类型的返回结果
     */
    public function del($id)
    {
        if (intval($id) > 0) {
            $where = " id = '" . intval($id) . "'";
            $result = Db::delete('deposit_level', $where);
            return $result;
        } else {
            return false;
        }
    }

    /**
     * 删除商家申请的保证金等级
     *
     * @param int $id
     *            记录ID
     * @return bool 布尔类型的返回结果
     */
    public function del_deposit($deposit_id)
    {
        if (intval($deposit_id) > 0) {
            $where = " deposit_id = '" . intval($deposit_id) . "'";
            $result = Db::delete('seller_deposit', $where);
            return $result;
        } else {
            return false;
        }
    }
}