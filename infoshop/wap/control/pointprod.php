<?php
/**
 * 哈金豆礼品
 *
 * @copyright  Copyright (c) 2003-2015 B2B2C Inc. (http://www.infol.com.cn)
 * @license    http://www.infol.com.cn
 * @link       http://www.infol.com.cn
 * @since      File available since Release v1.1
 */
defined('CorShop') or exit('Access Invalid!');

class pointprodControl extends BaseHomeControl
{

    private $templatestate_arr;

    public function __construct()
    {
        parent::__construct();
        // 读取语言包
        Language::read('home_pointprod,home_voucher');
        // 判断系统是否开启哈金豆和哈金豆中心功能
        if (C('points_isuse') != 1 || C('pointshop_isuse') != 1) {
            showMessage(Language::get('pointshop_unavailable'), 'index.php', 'html', 'error');
        }
        // 根据op判断哈金豆兑换功能是否开启
        if (in_array($_GET['op'], array(
            'plist',
            'pinfo'
        )) && C('pointprod_isuse') != 1) {
            showMessage(Language::get('pointprod_unavailable'), 'index.php', 'html', 'error');
        }
        Tpl::output('index_sign', 'pointprod');
        // 代金券模板状态
        $this->templatestate_arr = array(
            'usable' => array(
                1,
                Language::get('voucher_templatestate_usable')
            ),
            'disabled' => array(
                2,
                Language::get('voucher_templatestate_disabled')
            )
        );
        // 领取的代金券状态
        $this->voucherstate_arr = array(
            'unused' => array(
                1,
                Language::get('voucher_voucher_state_unused')
            ),
            'used' => array(
                2,
                Language::get('voucher_voucher_state_used')
            ),
            'expire' => array(
                3,
                Language::get('voucher_voucher_state_expire')
            )
        );
        if ($_SESSION['is_login'] == '1') {
            $model = Model();
            if (C('pointprod_isuse') == 1) {
                // 已选择兑换商品数
                
                $param = array();
                $param['table'] = 'order_goods, order';
                $param['join_type'] = 'left join';
                $param['field'] = 'count(*)';
                $param['join_on'] = array(
                    'order.order_id = order_goods.order_id'
                );
                $param['where'] = " order.order_state IN ('20', '30', '40') AND order.buyer_id = " . $_SESSION['member_id'];
                $pcartnum = Db::select($param);
                $pcartnum = $pcartnum[0]['count(*)'];
                
                Tpl::output('pcartnum', $pcartnum);
            }
            // 查询会员信息
            $member_info = $model->table('member')
                ->field('member_points,member_avatar')
                ->where(array(
                'member_id' => $_SESSION['member_id']
            ))
                ->find();
            Tpl::output('member_info', $member_info);
        }
    }

    public function indexOp()
    {
        $model = Model();
        // 开启代金券功能后查询代金券相应信息
        if (C('voucher_allow') == 1) {
            
            // 查询已兑换代金券券数量
            $vouchercount = 0;
            if ($_SESSION['is_login'] == '1') {
                $vouchercount = $model->table('voucher')
                    ->where(array(
                    'voucher_owner_id' => $_SESSION['member_id'],
                    'voucher_state' => $this->voucherstate_arr['unused'][0]
                ))
                    ->count();
            }
            Tpl::output('vouchercount', $vouchercount);
            
            // 查询代金券面额
            $pricelist = $model->table('voucher_price')
                ->order('voucher_price asc')
                ->select();
            Tpl::output('pricelist', $pricelist);
            
            // 查询代金券列表
            $field = 'voucher_template.*,store.store_id,store.store_label,store.store_name,store.store_domain';
            $on = 'voucher_template.voucher_t_store_id=store.store_id';
            $new_voucher = $model->table('voucher_template,store')
                ->field($field)
                ->join('left')
                ->on($on)
                ->where(array(
                'voucher_t_state' => $this->templatestate_arr['usable'][0],
                'voucher_t_end_date' => array(
                    'gt',
                    time()
                )
            ))
                ->limit(16)
                ->select();
            if (! empty($new_voucher)) {
                foreach ($new_voucher as $k => $v) {
                    if (! empty($v['voucher_t_customimg'])) {
                        $v['voucher_t_customimg'] = UPLOAD_SITE_URL . DS . ATTACH_VOUCHER . DS . $v['voucher_t_store_id'] . DS . $v['voucher_t_customimg'];
                    } else {
                        $v['voucher_t_customimg'] = UPLOAD_SITE_URL . DS . defaultGoodsImage(240);
                    }
                    $v['voucher_t_limit'] = intval($v['voucher_t_limit']);
                    $new_voucher[$k] = $v;
                }
            }
            Tpl::output('new_voucher', $new_voucher);
        }
        // 开启哈金豆兑换功能后查询代金券相应信息
        if (C('pointprod_isuse') == 1) {
            $model_pointsprod = Model('pointprod');
            
            // 最新哈金豆兑换商品
            $condition['where'] = 'is_gift = 1 AND goods_state = 1 AND goods_verify = 1';
            $condition['order'] = 'goods_id desc';
            $new_pointsprod = $model_pointsprod->getPointProdList($condition);
            Tpl::output('new_pointsprod', $new_pointsprod);
            
            // 兑换排行
            $condition['where'] = 'is_gift = 1 AND goods_state = 1 AND goods_verify = 1';
            $condition['order'] = 'goods_salenum desc';
            $converlist = $model_pointsprod->getPointProdList($condition);
            
            Tpl::output('converlist', $converlist);
        }
        // SEO
        Model('seo')->type('point')->show();
        Tpl::showpage('pointprod');
    }

    /**
     * 哈金豆商品列表
     */
    public function plistOp()
    {
        $model_pointsprod = Model('pointprod');
        
        $page = new Page();
        $page->setEachNum(12);
        $page->setStyle('admin');
        
        $condition['where'] = 'is_gift = 1 AND goods_state = 1 AND goods_verify = 1';
        $condition['order'] = 'goods_id desc';
        
        $pointprod_list = $model_pointsprod->getPointProdList($condition, $page, '*');
        
        Tpl::output('pointprod_list', $pointprod_list);
        
        Tpl::output('show_page', $page->show(1));
        
        // 兑换排行
        $condition['limit'] = 3;
        $condition['order'] = 'goods_salenum desc';
        $converlist = $model_pointsprod->getPointProdList($condition);
        
        Tpl::output('converlist', $converlist);
        Tpl::showpage('pointprod_list');
    }

    /**
     * 哈金豆礼品详细
     */
    public function pinfoOp()
    {
        $goods_id = intval($_GET['id']);
        
        // 商品详细信息
        $model_goods = Model('goods');
        $goods_detail = $model_goods->getGoodsDetail($goods_id, '*');
        
        $goods_info = $goods_detail['goods_info'];
        if (empty($goods_info)) {
            showMessage(L('goods_index_no_goods'), '', 'html', 'error');
        }
        
        Tpl::output('goods', $goods_info);
        Tpl::output('spec_list', $goods_detail['spec_list']);
        Tpl::output('spec_image', $goods_detail['spec_image']);
        Tpl::output('goods_image', $goods_detail['goods_image']);
        Tpl::output('groupbuy_info', $goods_detail['groupbuy_info']);
        Tpl::output('xianshi_info', $goods_detail['xianshi_info']);
        Tpl::output('mansong_info', $goods_detail['mansong_info']);
        
        $seo_param = array();
        $seo_param['name'] = $goods_detail['goods_name'];
        $seo_param['key'] = $goods_detail['goods_keywords'];
        $seo_param['description'] = $goods_detail['pgoods_description'];
        Model('seo')->type('point_content')
            ->param($seo_param)
            ->show();
        Tpl::showpage('pointprod_info');
    }

    /**
     * 推荐礼品
     */
    private function getCommendPointProd()
    {
        $condition_arr = array();
        $condition_arr['pgoods_show'] = '1';
        $condition_arr['pgoods_state'] = '0';
        $condition_arr['pgoods_commend'] = '1';
        $condition_arr['order'] = ' pgoods_sort asc,pgoods_id desc ';
        $condition_arr['limit'] = 4;
        $pointprod_model = Model('pointprod');
        $list = $pointprod_model->getPointProdList($condition_arr, $page);
        return $list;
    }
}
