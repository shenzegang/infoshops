-- 2015-08-14 匿名购买
ALTER TABLE cor_order_goods ADD column anonymous_status enum('0','1') default '0' COMMENT '0不匿名 1为匿名，默认0' ;

-- 买家身份认证
alter table cor_member add column idcard varchar(18) not null default '' ;
alter table cor_member add column idcard_photo varchar(20) not null default '' ;
alter table cor_member add column idcard_chk tinyint(1) not null default 0  ;

-- 2015-08-19 修改增加enum 退款后交易取消
ALTER TABLE `cor_order`
MODIFY COLUMN `order_state`  enum('0','10','20','30','50','40') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '10'
COMMENT '订单状态：0(已取消)10(默认):未付款;20:已付款;30:已发货;40:已收货;50:有退款交易取消' AFTER `evaluation_state`;

-- 2015-08-20
ALTER TABLE `cor_order`
MODIFY COLUMN `evaluation_state`  enum('0','1','2') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '0' COMMENT '评价状态 0未评价，1已评价，2可追加评价' AFTER `shipping_fee`;

-- 2015-08-24

ALTER TABLE `cor_store_bind_class`
ADD COLUMN `status`  tinyint(1) NULL DEFAULT 0 COMMENT '审核是否通过 0：通过 1：待审核：2拒绝' AFTER `class_3`;


ALTER TABLE `cor_order`
ADD COLUMN `del_state`  tinyint(1) NOT NULL COMMENT '是否删除' AFTER `is_gift`;

DROP TABLE IF EXISTS `cor_deposit_level`;
CREATE TABLE `cor_deposit_level` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `level_name` varchar(60)   DEFAULT NULL COMMENT '保证金等级名称',
  `amount` decimal(18,0) NOT NULL COMMENT '保证金金额',
  `memo` varchar(255)   DEFAULT NULL COMMENT '保证金等级的描述',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商家保证金等级';

DROP TABLE IF EXISTS `cor_seller_deposit`;
CREATE TABLE `cor_seller_deposit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `seller_id` int(10) DEFAULT NULL COMMENT '商家Id',
  `seller_name` varchar(50) DEFAULT NULL,
  `deposit_level` varchar(60) DEFAULT NULL COMMENT '保证金等级',
  `deposit_amount` decimal(18,0) DEFAULT NULL COMMENT '保证金数额',
  `apply_date` date DEFAULT NULL COMMENT '申请日期',
  `paid` enum('N','Y') DEFAULT 'N' COMMENT '是否已到账',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cor_agreement_template`;
CREATE TABLE `cor_agreement_template` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(120) DEFAULT NULL COMMENT '合同模板名称',
  `location` varchar(255) DEFAULT NULL COMMENT '文件在服务器上的路径url',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商家合同模板表';


-- 支付密码
alter table cor_member add column member_paypasswd varchar(32) not null default '' after member_passwd;

ALTER TABLE `cor_store_joinin`
ADD COLUMN `agreement_name`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '合同名称' AFTER `personal`;

ALTER TABLE `cor_agreement_template`
CHANGE COLUMN `name` `type`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '合同模板类型' AFTER `id`,
CHANGE COLUMN `location` `file_name`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '文件在服务器上的名称' AFTER `type`;

ALTER TABLE `cor_agreement_template`
MODIFY COLUMN `type`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '合同模板类型0-企业 1-个体户 2-个人' AFTER `id`;


ALTER TABLE `cor_seller_deposit`
ADD COLUMN `is_show`  tinyint(1) UNSIGNED NOT NULL  COMMENT '保证金是否显示给买家看' AFTER `paid`;

-- 增加支付方式表中：微信支付和银联支付
INSERT INTO `cor_payment` VALUES (6, 'unionpay', '银联支付', 'a:1:{s:0:\"\";s:0:\"\";}', '1');
INSERT INTO `cor_payment` VALUES (7, 'weichat', '微信', 'a:1:{s:0:\"\";s:0:\"\";}', '1');
INSERT INTO `cor_payment` VALUES (8, 'constrbank', '建设银行支付', 'a:1:{s:0:\"\";s:0:\"\";}', '1');
-- 消息增大字段
ALTER TABLE `cor_message`
MODIFY COLUMN `message_body`  varchar(1000) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '短消息内容' AFTER `message_title`;

-- 以上 v20150831

-- 20150901
ALTER TABLE `cor_seller_deposit`
ADD COLUMN `deposit_id`  int(11) NULL COMMENT '保证金等级的id' AFTER `seller_name`;

ALTER TABLE `cor_seller_deposit`
ADD COLUMN `deposit_voucher`  varchar(50) NULL COMMENT '保证金凭证图片' ;

-- 20150907
ALTER TABLE `cor_order_goods`
ADD COLUMN `is_refund`  enum('1','0') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '0'
COMMENT '0：表示该商品未退款 1:表示已退款' AFTER `anonymous_status`;


-- 20150911 石健
ALTER TABLE `cor_evaluate_goods`
MODIFY COLUMN `geval_content`  varchar(510) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL
COMMENT '信誉评价内容' AFTER `geval_scores`;

-- 20150911 陳田田 增加短信驗證註冊，保存驗證碼
CREATE TABLE `cor_sendsms` (
  `id`  int(10) NOT NULL ,
  `mobile_phone`  int(12) NULL COMMENT '发送验证码的手机号码' ,
  `code`  int(10) NULL COMMENT '短信验证码' ,
  `sender`  varchar(255) NULL COMMENT '发送者' ,
  `send_date`  datetime NULL COMMENT '生成时间' ,
  PRIMARY KEY (`id`)
);

-- 20150914 修改验证码表的字段类型
ALTER TABLE `cor_sendsms`
MODIFY COLUMN `mobile_phone`  varchar(12) NULL DEFAULT NULL COMMENT '发送验证码的手机号码' AFTER `id`;


ALTER TABLE `cor_sendsms`
MODIFY COLUMN `id`  int(10) NOT NULL AUTO_INCREMENT FIRST ;

-- 2015-09-20
DROP TABLE IF EXISTS `cor_order_welfare`;
CREATE TABLE `cor_order_welfare` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned NOT NULL,
  `points_num` smallint(5) NOT NULL DEFAULT '0',
  `points_amount` decimal(10,0) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
alter table cor_order_welfare add column pay_id int(10) not null default 0 after order_id;
alter table cor_order_welfare modify points_amount decimal(10,2) not null default '0';
alter table cor_order_welfare add column welfare_code varchar(20) not null default '' after id;
 
-- 20150924 投诉仲裁 sj
ALTER TABLE `cor_complain`
MODIFY COLUMN `complain_state`  tinyint(4) NOT NULL COMMENT '投诉状态(10-新投诉/20-投诉通过转给被投诉人/30-被投诉人已申诉/40-提交仲裁/50-买家胜/60-卖家胜/99-已关闭)' AFTER `final_handle_member_id`;

ALTER TABLE `cor_complain`
ADD COLUMN `refund_type`  tinyint(1) NOT NULL DEFAULT 1 COMMENT '申请类型:1为退款,2为退货,默认为1' AFTER `complain_active`;

ALTER TABLE `cor_complain`
ADD COLUMN `return_type`  tinyint(1) NOT NULL DEFAULT 1 COMMENT '退货类型:1为不用退货,2为需要退货,默认为1' AFTER `refund_type`;

insert into cor_mail_msg_temlates values('<strong>[给用户]</strong>用邮箱注册的新用户发送邮箱确认','{$site_name}查收验证码注册','sms_toseller_new_vip','尊敬的用户: 欢迎您即将成为新用户，您的验证码是：{$seller_name}，请及时完成注册！',0,1);=======

-- 2015-09-23
DROP TABLE IF EXISTS `cor_member_welfare`;
CREATE TABLE `cor_member_welfare` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `member_id` int(10) NOT NULL DEFAULT '0',
  `welfare_id` varchar(32) NOT NULL DEFAULT '' COMMENT '用户福利ID',
  `welfare_code` varchar(20) NOT NULL DEFAULT '',
  `welfare_name` varchar(20) NOT NULL DEFAULT '',
  `welfare_number` decimal(10,0) NOT NULL DEFAULT '0',
  `create_time` int(10) NOT NULL DEFAULT '0',
  `upddate_time` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

alter table cor_order add column welfare_amount decimal(10,2) not null default '0' after pd_amount;
-- 2015-09-24
alter table cor_order add column is_detach tinyint(1) not null default '1' comment '标明订单是否可拆' after order_state;
alter table cor_order_goods modify is_refund enum('0', '1', '2') not null default '0' comment '0表示正常状态，1表示已经退款，2表示退款中。' after anonymous_status;
<<<<<<< .mine

=======
>>>>>>> .r682
-- 2015-09-30
alter table cor_order_pay modify pay_sn varchar(32) not null default '';
alter table cor_order modify pay_sn varchar(32) not null default '';
