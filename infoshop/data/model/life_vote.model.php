<?php
/**
 * 投票管理
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

class life_voteModel
{

    /**
     * 列表
     *
     * @param array $condition
     *            检索条件
     * @param obj $page
     *            分页
     * @return array 数组结构的返回结果
     */
    public function getList($condition, $page = '')
    {
        $param = array();
        $param['table'] = 'life_vote';
        $param['where'] = $condition['where'];
        $param['limit'] = $condition['limit'];
        $param['order'] = (empty($condition['order']) ? 'sort asc,time desc' : $condition['order']);
        $result = Db::select($param, $page);
        $result = empty($result) ? array() : $result;
        return $result;
    }

    /**
     * 取单个内容
     *
     * @param int $id
     *            ID
     * @return array 数组类型的返回结果
     */
    public function getOne($id)
    {
        if (intval($id) > 0) {
            $param = array();
            $param['table'] = 'life_vote';
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
            $result = Db::insert('life_vote', $tmp);
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
            $where = " id = '" . $param['id'] . "'";
            $result = Db::update('life_vote', $tmp, $where);
            return $result;
        } else {
            return false;
        }
    }

    /**
     * 删除
     *
     * @param int $id
     *            记录ID
     * @return bool 布尔类型的返回结果
     */
    public function del($id)
    {
        if (intval($id) > 0) {
            $where = " id = '" . intval($id) . "'";
            $result = Db::delete('life_vote', $where);
            return $result;
        } else {
            return false;
        }
    }
}