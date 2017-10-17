<?php
/**
 * 文章管理
 *
 * @copyright  Copyright (c) 2003-2015 B2B2C Inc. (http://www.infol.com.cn)
 * @license    http://www.infol.com.cn
 * @link       http://www.infol.com.cn
 * @since      File available since Release v1.1
 */
defined('CorShop') or exit('Access Invalid!');

class life_articleModel
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
        $param['table'] = 'life_article';
        $param['where'] = $condition['where'];
        $param['limit'] = $condition['limit'];
        $param['order'] = (empty($condition['order']) ? 'article_sort asc,article_time desc' : $condition['order']);
        $result = Db::select($param, $page);
        $result = empty($result) ? array() : $result;
        return $result;
    }

    /**
     * 连接查询列表
     *
     * @param array $condition
     *            检索条件
     * @param obj $page
     *            分页
     * @return array 数组结构的返回结果
     */
    public function getJoinList($condition, $page = '')
    {
        $result = array();
        $param = array();
        $param['table'] = 'life_article, life_article_class';
        $param['field'] = empty($condition['field']) ? '*' : $condition['field'];
        ;
        $param['join_type'] = empty($condition['join_type']) ? 'left join' : $condition['join_type'];
        $param['join_on'] = array(
            'life_article.ac_id = article_class.ac_id'
        );
        $param['where'] = $condition['where'];
        $param['limit'] = $condition['limit'];
        $param['order'] = empty($condition['order']) ? 'life_article.article_sort' : $condition['order'];
        $result = Db::select($param, $page);
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
            $param['table'] = 'life_article';
            $param['field'] = 'article_id';
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
            $result = Db::insert('life_article', $tmp);
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
            $where = " article_id = '" . $param['article_id'] . "'";
            $result = Db::update('life_article', $tmp, $where);
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
            $where = " article_id = '" . intval($id) . "'";
            $result = Db::delete('life_article', $where);
            return $result;
        } else {
            return false;
        }
    }
}