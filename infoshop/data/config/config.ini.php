<?php
defined('CorShop') or exit('Access Invalid!');
$config = array();
$config['shop_site_url'] = 'http://127.0.0.1/shop';
$config['cms_site_url'] = 'http://127.0.0.1/cms';
$config['microshop_site_url'] = 'http://127.0.0.1/microshop';
$config['circle_site_url'] = 'http://127.0.0.1/circle';
$config['admin_site_url'] = 'http://127.0.0.1/admin';
$config['mobile_site_url'] = 'http://127.0.0.1/mobile';
$config['wap_site_url'] = 'http://127.0.0.1/wap';
$config['upload_site_url'] = 'http://127.0.0.1/data/upload';
$config['resource_site_url'] = 'http://127.0.0.1/data/resource';
$config['version'] = '201401162490';
$config['setup_date'] = '2015-01-01 15:28:42';
$config['gip'] = 0;
$config['dbdriver'] = 'mysqli';
$config['tablepre'] = 'cor_';
$config['db'][1]['dbhost'] = '127.0.0.1';
$config['db'][1]['dbport'] = '3306';
$config['db'][1]['dbuser'] = 'root';
$config['db'][1]['dbpwd'] = '';
$config['db'][1]['dbname'] = 'infol_shop';
$config['db'][1]['dbcharset'] = 'UTF-8';
$config['db']['slave'] = array();
$config['session_expire'] = 3600;
$config['lang_type'] = 'zh_cn';
$config['cookie_pre'] = 'BB49_';
$config['tpl_name'] = 'default';
$config['thumb']['cut_type'] = 'gd';
$config['thumb']['impath'] = '';
$config['cache']['type'] = 'file';

//发送手机短信的间隔和有效时间
$config['sms_send'] = 1*60;
$config['sms_invalid'] = 3*60;

// $config['memcache']['prefix'] = 'nc_';
// $config['memcache'][1]['port'] = 11211;
// $config['memcache'][1]['host'] = '127.0.0.1';
// $config['memcache'][1]['pconnect'] = 0;
// $config['redis']['prefix'] = 'nc_';
// $config['redis']['master']['port'] = 6379;
// $config['redis']['master']['host'] = '127.0.0.1';
// $config['redis']['master']['pconnect'] = 0;
// $config['redis']['slave'] = array();
// $config['fullindexer']['open'] = false;
// $config['fullindexer']['appname'] = 'shopnc';
$config['debug'] = false;
$config['default_store_id'] = '1';
// 是否开启伪静态
$config['url_model'] = false;
// 二级域名后缀
$config['subdomain_suffix'] = '';
//账单结算周期(默认30天)
$config['settlement_period'] = 30;
//本期结算天数(默认7天)
$config['settlement_day'] = 7;