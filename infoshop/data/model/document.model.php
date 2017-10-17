<?php
/**
 * 系统文章
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

class documentModel
{

    /**
     * 查询所有系统文章
     */
    public function getList()
    {
        $param = array(
            'table' => 'document'
        );
        return Db::select($param);
    }

    /**
     * 根据编号查询一条
     *
     * @param unknown_type $id            
     */
    public function getOneById($id)
    {
        $param = array(
            'table' => 'document',
            'field' => 'doc_id',
            'value' => $id
        );
        return Db::getRow($param);
    }

    /**
     * 根据标识码查询一条
     *
     * @param unknown_type $id            
     */
    public function getOneByCode($code)
    {
        $param = array(
            'table' => 'document',
            'field' => 'doc_code',
            'value' => $code
        );
        return Db::getRow($param);
    }

    /**
     * 更新
     *
     * @param unknown_type $param            
     */
    public function update($param)
    {
        return Db::update('document', $param, "doc_id='{$param['doc_id']}'");
    }
}