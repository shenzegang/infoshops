<?php
defined('CorShop') or exit('Access Invalid!');
$config['alipay']['req_url'] = "http://121.41.113.184:9090/xnpayws/ws/pay/aliPay/AliPayGetPayHtmlResponse";
$config['alipay']['rep_url'] = "http://121.41.113.184:9090/xnpayws/ws/pay/aliPay/AlipayNotifyResponse";
$config['alipay']['notify_url'] = "http://121.41.113.184:8724/shop/index.php?act=payment&op=aliNotify";
$config['alipay']['return_url'] = "http://121.41.113.184:8724/shop/index.php?act=payment&op=payment_success&predeposit=1";
$config['alipay']['return_url_buy'] = "http://121.41.113.184:8724/shop/index.php?act=payment&op=payment_success";
$config['alipay']['notify_url_buy'] = "http://121.41.113.184:8724/shop/index.php?act=payment&op=aliNotify_buy";
$config['alipay']['check_user_url'] = "http://121.41.113.184:9090/xnpayws/ws/pay/userInfo/queryUserInfos";
$config['alipay']['checkUser_url'] = "http://121.41.113.184:9090/xnpayws/ws/pay/userInfo/queryUserInfo";
$config['alipay']['add_user_url'] = "http://121.41.113.184:9090/xnpayws/ws/pay/userInfo/addUserInfo";
$config['predeposit']['subOrder_url'] = "http://121.41.113.184:9090/xnpayws/ws/pay/balancePay/submitOrder";
$config['predeposit']['payOrder_url'] = "http://121.41.113.184:9090/xnpayws/ws/pay/balancePay/payOrder";
$config['predeposit']['pay_url'] = "http://121.41.113.184:9090/xnpayws/ws/pay/balancePay/payOrder";
/**************************订单发货*****************************/
$config['order']['sellerDelivery'] = "http://121.41.113.184:9090/xnpayws/ws/pay/balancePay/sellerDelivery";
$config['order']['closeOrder'] = 'http://121.41.113.184:9090/xnpayws/ws/pay/balancePay/closeOrder';
//修改邮费
$config['order']['editOrderPostage'] = 'http://121.41.113.184:9090/xnpayws/ws/pay/balancePay/editOrderPostage';

$config['pay']['changeState_url'] = "http://121.41.113.184:9090/xnpayws/ws/pay/balancePay/changeOrderState";
$config['pay']['finshOrder'] = "http://121.41.113.184:9090/xnpayws/ws/pay/balancePay/finshOrder";
$config['pay']['password'] = "http://121.41.113.184:9090/xnpayws/ws/pay/userInfo/editUserPayPassword";
$config['pay']['returnOrder'] = "http://121.41.113.184:9090/xnpayws/ws/pay/balancePay/refundOrder";
$config['pay']['validTrade'] = "http://121.41.113.184:9090/xnpayws/ws/pay/wxPay/getWxpayInfo";
/****************************银联支付参数*************************/
$config['unionpay']['req_url'] = "http://121.41.113.184:9090/xnpayws/ws/pay/unionPay/unionPayGetHtmlResponse";
$config['unionpay']['rep_url'] = "http://121.41.113.184:9090/xnpayws/ws/pay/unionPay/unionPayNotifyResponse";
$config['unionpay']['notify_url'] = "http://121.41.113.184:8724/shop/index.php?act=payment&op=uniNotify";
$config['unionpay']['return_url'] = "http://121.41.113.184:8724/shop/index.php?act=payment&op=payment_success&predeposit=1";
$config['unionpay']['notify_url_buy'] = "http://121.41.113.184:8724/shop/index.php?act=payment&op=uniNotify_buy";
$config['unionpay']['return_url_buy'] = "http://121.41.113.184:8724/shop/index.php?act=payment&op=payment_success";
/****************************微信支付*************************/
$config['weichat']['req_url'] = "http://121.41.113.184:9090/xnpayws/ws/pay/wxPay/getCodeURL/";
$config['weichat']['rep_url'] = "http://121.41.113.184:9090/xnpayws/ws/pay/wxPay/WxpayNotifyResponse";
$config['weichat']['notify_url'] = "http://121.41.113.184:8724/shop/weichat.php";
$config['weichat']['notify_url_buy'] = "http://121.41.113.184:8724/shop/weichat_buy.php";
/****************************建行支付*************************/
$config['constrbank']['req_url'] = "http://121.41.113.184:6060/xnpayws/ws/pay/cbcPay/getCBCURL/";
/****************************福利配置 *************************/
$config['welfare']['findAll_url'] = "http://121.41.113.184:9090/xnpayws/ws/pay/welfare/queryWelfares/";
$config['welfare']['find_url'] = "http://121.41.113.184:9090/xnpayws/ws/pay/welfare/queryUserWelfares";
//获取福利
$config['welfare']['obtainWerfare_url'] = "http://121.41.113.184:9090/xnpayws/ws/pay/welfare/grantUserWelfare/";
?>
