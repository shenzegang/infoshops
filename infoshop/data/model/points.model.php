<?php
/**
 * 哈金豆及哈金豆日志管理
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

class pointsModel
{

    /**
     * 操作哈金豆
     * 
     * @author Corshop Develop Team
     * @param string $stage
     *            操作阶段 regist(注册),login(登录),comments(评论),order(下单),system(系统),other(其他),pointorder(哈金豆礼品兑换),app(同步哈金豆兑换)
     * @param array $insertarr
     *            该数组可能包含信息 array('pl_memberid'=>'会员编号','pl_membername'=>'会员名称','pl_adminid'=>'管理员编号','pl_adminname'=>'管理员名称','pl_points'=>'哈金豆','pl_desc'=>'描述','orderprice'=>'订单金额','order_sn'=>'订单编号','order_id'=>'订单序号','point_ordersn'=>'哈金豆兑换订单编号');
     * @param bool $if_repeat
     *            是否可以重复记录的信息,true可以重复记录，false不可以重复记录，默认为true
     * @return bool
     */
    function savePointsLog($stage, $insertarr, $if_repeat = true)
    {
        if (! $insertarr['pl_memberid']) {
            return false;
        }
        // 记录原因文字
        switch ($stage) {
            case 'regist':
                if (! $insertarr['pl_desc']) {
                    $insertarr['pl_desc'] = Language::get('pointsregistdesc');
                }
                $insertarr['pl_points'] = intval($GLOBALS['setting_config']['points_reg']);
                break;
            case 'login':
                if (! $insertarr['pl_desc']) {
                    $insertarr['pl_desc'] = Language::get('pointslogindesc');
                }
                $insertarr['pl_points'] = intval($GLOBALS['setting_config']['points_login']);
                break;
            case 'comments':
                if (! $insertarr['pl_desc']) {
                    $insertarr['pl_desc'] = Language::get('pointscommentsdesc');
                }
                $insertarr['pl_points'] = intval($GLOBALS['setting_config']['points_comments']);
                break;
            case 'order':
                if (! $insertarr['pl_desc']) {
                    $insertarr['pl_desc'] = Language::get('pointsorderdesc_1') . $insertarr['order_sn'] . Language::get('pointsorderdesc');
                }
                $insertarr['pl_points'] = 0;
                if ($insertarr['orderprice']) {
                    $insertarr['pl_points'] = @intval($insertarr['orderprice'] / $GLOBALS['setting_config']['points_orderrate']);
                    if ($insertarr['pl_points'] > intval($GLOBALS['setting_config']['points_ordermax'])) {
                        $insertarr['pl_points'] = intval($GLOBALS['setting_config']['points_ordermax']);
                    }
                }
                // 订单添加赠送哈金豆列
                $obj_order = Model('order');
                $data = array();
                $data['order_pointscount'] = array(
                    'exp',
                    'order_pointscount+' . $insertarr['pl_points']
                );
                $obj_order->editOrderCommon($data, array(
                    'order_id' => $insertarr['order_id']
                ));
                break;
            case 'system':
                break;
            case 'pointorder':
                if (! $insertarr['pl_desc']) {
                    $insertarr['pl_desc'] = '兑换礼品，订单' . $insertarr['point_ordersn'] . Language::get('points_pointorderdesc');
                }
                break;
            case 'cancelorder':
                if (! $insertarr['pl_desc']) {
                    $insertarr['pl_desc'] = '取消订单' . $insertarr['point_ordersn'] . '，返还礼品订单消耗的积分';
                }
                break;
            case 'app':
                if (! $insertarr['pl_desc']) {
                    $insertarr['pl_desc'] = Language::get('points_pointorderdesc_app');
                }
                break;
            case 'other':
                break;
        }
        $save_sign = true;
        if ($if_repeat == false) {
            // 检测是否有相关信息存在，如果没有，入库
            $condition['pl_memberid'] = $insertarr['pl_memberid'];
            $condition['pl_stage'] = $stage;
            $log_array = self::getPointsInfo($condition, $page);
            if (! empty($log_array)) {
                $save_sign = false;
            }
        }
        if ($save_sign == false) {
            return true;
        }
        // 新增日志
        $value_array = array();
        $value_array['pl_memberid'] = $insertarr['pl_memberid'];
        $value_array['pl_membername'] = $insertarr['pl_membername'];
        if ($insertarr['pl_adminid']) {
            $value_array['pl_adminid'] = $insertarr['pl_adminid'];
        }
        if ($insertarr['pl_adminname']) {
            $value_array['pl_adminname'] = $insertarr['pl_adminname'];
        }
        $value_array['pl_points'] = $insertarr['pl_points'];
        $value_array['pl_addtime'] = time();
        $value_array['pl_desc'] = $insertarr['pl_desc'];
        $value_array['pl_stage'] = $stage;
        $result = false;
        if ($value_array['pl_points'] != '0') {
            $result = self::addPointsLog($value_array);
        } else {
            return true;
        }
        if ($result) {
            // 更新member内容
            $obj_member = Model('member');
            $upmember_array = array();
            $upmember_array['member_points'] = array(
                'sign' => 'increase',
                'value' => $insertarr['pl_points']
            );
            $obj_member->updateMember($upmember_array, $insertarr['pl_memberid']);
            return true;
        } else {
            return false;
        }
    }

    /**
     * 添加哈金豆日志信息
     *
     * @param array $param
     *            添加信息数组
     */
    public function addPointsLog($param)
    {
        if (empty($param)) {
            return false;
        }
        $result = Db::insert('points_log', $param);
        return $result;
    }

    /**
     * 哈金豆日志列表
     *
     * @param array $condition
     *            条件数组
     * @param array $page
     *            分页
     * @param array $field
     *            查询字段
     * @param array $page
     *            分页
     */
    public function getPointsLogList($condition, $page = '', $field = '*')
    {
        $condition_str = $this->getCondition($condition);
        $param = array();
        $param['table'] = 'points_log';
        $param['where'] = $condition_str;
        $param['field'] = $field;
        $param['order'] = $condition['order'] ? $condition['order'] : 'points_log.pl_id desc';
        $param['limit'] = $condition['limit'];
        $param['group'] = $condition['group'];
        return Db::select($param, $page);
    }

    /**
     * 哈金豆日志详细信息
     *
     * @param array $condition
     *            条件数组
     * @param array $field
     *            查询字段
     */
    public function getPointsInfo($condition, $field = '*')
    {
        // 得到条件语句
        $condition_str = $this->getCondition($condition);
        $array = array();
        $array['table'] = 'points_log';
        $array['where'] = $condition_str;
        $array['field'] = $field;
        $list = Db::select($array);
        return $list[0];
    }

    /**
     * 将条件数组组合为SQL语句的条件部分
     *
     * @param array $condition_array            
     * @return string
     */
    private function getCondition($condition_array)
    {
        $condition_sql = '';
        // 哈金豆日志会员编号
        if ($condition_array['pl_memberid']) {
            $condition_sql .= " and `points_log`.pl_memberid = '{$condition_array['pl_memberid']}'";
        }
        // 操作阶段
        if ($condition_array['pl_stage']) {
            $condition_sql .= " and `points_log`.pl_stage = '{$condition_array['pl_stage']}'";
        }
        // 会员名称
        if ($condition_array['pl_membername_like']) {
            $condition_sql .= " and `points_log`.pl_membername like '%{$condition_array['pl_membername_like']}%'";
        }
        // 管理员名称
        if ($condition_array['pl_adminname_like']) {
            $condition_sql .= " and `points_log`.pl_adminname like '%{$condition_array['pl_adminname_like']}%'";
        }
        // 添加时间
        if ($condition_array['saddtime']) {
            $condition_sql .= " and `points_log`.pl_addtime >= '{$condition_array['saddtime']}'";
        }
        if ($condition_array['eaddtime']) {
            $condition_sql .= " and `points_log`.pl_addtime <= '{$condition_array['eaddtime']}'";
        }
        // 描述
        if ($condition_array['pl_desc_like']) {
            $condition_sql .= " and `points_log`.pl_desc like '%{$condition_array['pl_desc_like']}%'";
        }
        return $condition_sql;
    }
}