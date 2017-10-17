<?php
defined('CorShop') or exit('Access Invalid!');
$config['sys_log'] = false;
$config['alipay']['req_url'] = "http://121.41.113.184:6060/xnpayws/ws/pay/aliPay/AliPayGetPayHtmlResponse";
$config['alipay']['rep_url'] = "http://121.41.113.184:6060/xnpayws/ws/pay/aliPay/AlipayNotifyResponse";
$config['alipay']['notify_url'] = "http://121.41.113.184:9999/shop/index.php?act=payment&op=aliNotify";
$config['alipay']['return_url'] = "http://121.41.113.184:9999/shop/index.php?act=payment&op=payment_success&predeposit=1";
$config['alipay']['return_url_buy'] = "http://121.41.113.184:9999/shop/index.php?act=payment&op=payment_success";
$config['alipay']['notify_url_buy'] = "http://121.41.113.184:9999/shop/index.php?act=payment&op=aliNotify_buy";
$config['alipay']['check_user_url'] = "http://121.41.113.184:6060/xnpayws/ws/pay/userInfo/queryUserInfos";
$config['alipay']['checkUser_url'] = "http://121.41.113.184:6060/xnpayws/ws/pay/userInfo/queryUserInfo";
$config['alipay']['add_user_url'] = "http://121.41.113.184:6060/xnpayws/ws/pay/userInfo/addUserInfo";
$config['predeposit']['subOrder_url'] = "http://121.41.113.184:6060/xnpayws/ws/pay/balancePay/submitOrder";
$config['predeposit']['payOrder_url'] = "http://121.41.113.184:6060/xnpayws/ws/pay/balancePay/payOrder";
$config['predeposit']['pay_url'] = "http://121.41.113.184:6060/xnpayws/ws/pay/balancePay/payOrder";
$config['pay']['changeState_url'] = "http://121.41.113.184:6060/xnpayws/ws/pay/balancePay/changeOrderState";
$config['pay']['finshOrder'] = "http://121.41.113.184:6060/xnpayws/ws/pay/balancePay/finshOrder";
$config['pay']['password'] = "http://121.41.113.184:7070/xnpayws/ws/pay/userInfo/editUserPayPassword";
$config['pay']['returnOrder'] = "http://121.41.113.184:6060/xnpayws/ws/pay/balancePay/refundOrder";
?>