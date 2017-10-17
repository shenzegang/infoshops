<?php
/**
 * 申请店铺保证金模型管理
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

class seller_depositModel extends Model
{

    public function __construct()
    {
        parent::__construct('seller_deposit');
    }


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
        $param['table'] = 'seller_deposit';
        $param['where'] = $condition_str;
        // $param['order'] = 'id';
        $param['order'] = $condition['order'] ? $condition['order'] : 'id';
        $result = Db::select($param);
        return $result;
    }

    /**
     * 列表
     *
     * @param array $condition
     *            检索条件
     * @return array 数组结构的返回结果
     */
    public function getSellerDepositList($condition = array())
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
        if ($condition['id'] != '') {
            $condition_str .= " and id != '" . intval($condition['id']) . "'";
        }
        if ($condition['seller_name'] != '') {
            $condition_str .= " and seller_name like '%" . $condition['seller_name'] . "%'";
        }
        return $condition_str;
    }

    /**
     * 查询店铺列表
     *
     * @param array $condition
     *            查询条件
     * @param int $page
     *            分页数
     * @param string $order
     *            排序
     * @param string $field
     *            字段
     * @param string $limit
     *            取多少条
     * @return array
     */
    public function getStoreList($condition, $page = null, $order = '', $field = '*', $limit = '')
    {
        $result = $this->field($field)
            ->where($condition)
            ->order($order)
            ->limit($limit)
            ->page($page)
            ->select();
        return $result;
    }

    /**
     * 查询有效店铺列表
     *
     * @param array $condition
     *            查询条件
     * @param int $page
     *            分页数
     * @param string $order
     *            排序
     * @param string $field
     *            字段
     * @return array
     */
    public function getStoreOnlineList($condition, $page = null, $order = '', $field = '*')
    {
        $condition['store_state'] = 1;
        return $this->getStoreList($condition, $page, $order, $field);
    }

    /**
     * 店铺数量
     * 
     * @param array $condition            
     * @return int
     */
    public function getStoreCount($condition)
    {
        return $this->where($condition)->count();
    }

    /**
     * 按店铺编号查询店铺的开店信息
     *
     * @param array $storeid_array
     *            店铺编号
     * @return array
     */
    public function getStoreMemberIDList($storeid_array)
    {
        $store_list = $this->table('store')
            ->where(array(
            'store_id' => array(
                'in',
                $storeid_array
            )
        ))
            ->field('store_id,member_id,store_domain')
            ->key('store_id')
            ->select();
        return $store_list;
    }
    
    /**
     * 根据店铺ID查找对应的用户ID
     *
     * @param array $storeid
     *            店铺编号
     * @return array
     */
    public function getStoreMemberID($storeid = 0)
    {
    	$store_info = $this -> table('store') -> where(array('store_id' => $storeid)) -> field('member_id') -> find();
    	
    	return $store_info['member_id'];
    }
    
    

    /**
     * 查询店铺信息
     *
     * @param array $condition
     *            查询条件
     * @return array
     */
    public function getSellerInfo($condition)
    {
        $store_info = $this->where($condition)->find();
        if (! empty($store_info)) {
            if (! empty($store_info['store_presales']))
                $store_info['store_presales'] = unserialize($store_info['store_presales']);
            if (! empty($store_info['store_aftersales']))
                $store_info['store_aftersales'] = unserialize($store_info['store_aftersales']);
        }
        return $store_info;
    }



    /**
     * 取单个保证金等级内容
     *
     * @param int $id
     *            分类ID
     * @return array 数组类型的返回结果
     */
    public function getOneDeposit($level_name)
    {
        if ($level_name != "") {
            $param = array();
            $param['table'] = 'deposit_level';
            $param['field'] = 'level_name';
            $param['value'] = $level_name;
            $result = Db::getRow($param);
            return $result;
        } else {
            return false;
        }
    }

    /**
     * 取商家保证金内容
     *
     * @param int $id
     *            分类ID
     * @return array 数组类型的返回结果
     */
    public function getOneSellerDeposit($seller_id)
    {
        if (intval($seller_id) > 0) {
            $param = array();
            $param['table'] = 'seller_deposit';
            $param['field'] = 'seller_id';
            $param['value'] = intval($seller_id);
            $result = Db::getRow($param);
            return $result;
        } else {
            return false;
        }
    }

    /**
     * 取商家保证金内容
     *
     * @param int $id
     *            分类ID
     * @return array 数组类型的返回结果
     */
    public function getOneSDeposit($id)
    {
        if (intval($id) > 0) {
            $param = array();
            $param['table'] = 'seller_deposit';
            $param['field'] = 'id';
            $param['value'] = intval($id);
            $result = Db::getRow($param);
            return $result;
        } else {
            return false;
        }
    }

    /**
     * 取单个卖家内容
     *
     * @param int $id
     *            分类ID
     * @return array 数组类型的返回结果
     */
    public function getOneSeller($store_id)
    {
        if (intval($store_id) > 0) {
            $param = array();
            $param['table'] = 'seller';
            $param['field'] = 'store_id';
            $param['value'] = intval($store_id);
            $result = Db::getRow($param);
            return $result;
        } else {
            return false;
        }
    }

    /**
     * 通过店铺编号查询店铺信息
     *
     * @param int $store_id
     *            店铺编号
     * @return array
     */
    public function getSellerInfoByID($store_id)
    {
        $store_info = rcache($store_id, 'seller');
        if (empty($store_info)) {
            $store_info = $this->getSellerInfo(array(
                'store_id' => $store_id
            ));
            wcache($store_id, $store_info, 'store_info');
        }
        return $store_info;
    }


    public function getStoreIDString($condition)
    {
        $condition['store_state'] = 1;
        $store_list = $this->getStoreList($condition);
        $store_id_string = '';
        foreach ($store_list as $value) {
            $store_id_string .= $value['store_id'] . ',';
        }
        return $store_id_string;
    }

    /*
     * 申请保证金
     *
     * @param array $param 保证金信息
     * @return bool
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
            $result = Db::insert('seller_deposit', $tmp);
            return $result;
        } else {
            return false;
        }
    }

    /**保证金是否显示给买家看
     * @param $data
     * @param $condition
     * @return mixed
     */
    public function editDeposit($param)
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
            $result = Db::update('seller_deposit', $tmp, $where);
            return $result;
        } else {
            return false;
        }
    }

    /**
     * 删除商家保证金
     *
     * @param int $id
     *            记录ID
     * @return bool 布尔类型的返回结果
     */
    public function del($id)
    {
        if (intval($id) > 0) {
            $where = " id = '" . intval($id) . "'";
            $result = Db::delete('seller_deposit', $where);
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
            $where = " id = '{$param['id']}'";
            $result = Db::update('seller_deposit', $tmp, $where);
            return $result;
        } else {
            return false;
        }
    }


}


