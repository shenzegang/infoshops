<?php
/**
 * Created by PhpStorm.
 * User: shijian
 * Date: 2015/8/26
 * Time: 9:07
 */
defined('CorShop') or exit('Access Invalid!');

class agreement_templateModel extends Model
{

    public function __construct()
    {
        parent::__construct('agreement_template');
    }

    public function getAgreementTemplateList($condition = array(), $fields = '*', $group = '')
    {
        return $this->where($condition)
            ->field($fields)
            ->limit(false)
            ->group($group)
            ->select();
    }

    public function updateAgreementTemplate($data, $condition){
        $result = $this -> where($condition) -> update($data);
    }

    public function insertAgreementTemplate($data){
        return $this->table('agreement_template')->insert($data);
    }
}