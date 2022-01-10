SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS `safwl_accpass`;
CREATE TABLE `safwl_accpass` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pass` varchar(50) DEFAULT NULL,
  `remarks` varchar(200) DEFAULT NULL,
  `addtime` datetime DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `safwl_accpasslogin`;
CREATE TABLE `safwl_accpasslogin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT NULL,
  `pass` varchar(30) DEFAULT NULL,
  `ip` varchar(30) DEFAULT NULL,
  `time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `safwl_blacklist`;
CREATE TABLE `safwl_blacklist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `data` varchar(200) DEFAULT NULL,
  `remarks` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `safwl_config`;
CREATE TABLE `safwl_config` (
  `safwl_k` varchar(255) NOT NULL DEFAULT '',
  `safwl_v` text,
  PRIMARY KEY (`safwl_k`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
INSERT INTO `safwl_config` VALUES ('title', '52发卡系统');
INSERT INTO `safwl_config` VALUES ('keywords', '个人发卡网,52发卡网');
INSERT INTO `safwl_config` VALUES ('description', 'QQ群：185720544，下载地址：qin52.com');
INSERT INTO `safwl_config` VALUES ('zzqq', '123456');
INSERT INTO `safwl_config` VALUES ('notice2', '付款后按提示点击确定跳转到提取页面，不可提前关闭窗口！否则无法提取到卡密！');
INSERT INTO `safwl_config` VALUES ('notice3', '提取码是订单编号 或者 您的联系方式！');
INSERT INTO `safwl_config` VALUES ('notice1', '提取卡密后请尽快激活使用或保存好，系统定期清除被提取的卡密');
INSERT INTO `safwl_config` VALUES ('foot', 'Copyright © 2019 52站长论坛');
INSERT INTO `safwl_config` VALUES ('dd_notice', '1.联系方式也可以作为你的提卡凭证<br>2.必须等待付款完成自动跳转，不可提前关闭页面，否则会导致订单失效，后果自负');
INSERT INTO `safwl_config` VALUES ('admin', 'admin');
INSERT INTO `safwl_config` VALUES ('pwd', 'f3b4e3b975e0484835e90514f8318e61');
INSERT INTO `safwl_config` VALUES ('web_url', '');
INSERT INTO `safwl_config` VALUES ('payapi', '5');
INSERT INTO `safwl_config` VALUES ('epay_id', '123456');
INSERT INTO `safwl_config` VALUES ('epay_key', '');
INSERT INTO `safwl_config` VALUES ('epay_alipay_id', '123456');
INSERT INTO `safwl_config` VALUES ('epay_alipay_pic', '/w/fc.png');
INSERT INTO `safwl_config` VALUES ('epay_wxpay_id', '123456');
INSERT INTO `safwl_config` VALUES ('epay_wxpay_pic', '/w/wx.png');
INSERT INTO `safwl_config` VALUES ('showKc', '1');
INSERT INTO `safwl_config` VALUES ('CC_Defender', '2');
INSERT INTO `safwl_config` VALUES ('txprotect', '2');
INSERT INTO `safwl_config` VALUES ('qqtz', '0');
INSERT INTO `safwl_config` VALUES ('sqlv', '1064');
INSERT INTO `safwl_config` VALUES ('cyapi', '1');
INSERT INTO `safwl_config` VALUES ('cyid', '');
INSERT INTO `safwl_config` VALUES ('cykey', '');
INSERT INTO `safwl_config` VALUES ('cygg', '');
INSERT INTO `safwl_config` VALUES ('share', '0');
INSERT INTO `safwl_config` VALUES ('syslog', '1');
INSERT INTO `safwl_config` VALUES ('showImgs', '1');
INSERT INTO `safwl_config` VALUES ('submit', '修改');
INSERT INTO `safwl_config` VALUES ('switch_alipay', '1');
INSERT INTO `safwl_config` VALUES ('switch_wxpay', '1');
INSERT INTO `safwl_config` VALUES ('switch_qqpay', '1');
INSERT INTO `safwl_config` VALUES ('switch_tenpay', '0');
INSERT INTO `safwl_config` VALUES ('epay_url', '');
INSERT INTO `safwl_config` VALUES ('ftitle', '52站长论坛：qin52.com');
INSERT INTO `safwl_config` VALUES ('view', 'g15');
INSERT INTO `safwl_config` VALUES ('sendemail', '0');
INSERT INTO `safwl_config` VALUES ('mail_stmp', 'smtp.qq.com');
INSERT INTO `safwl_config` VALUES ('mail_port', '465');
INSERT INTO `safwl_config` VALUES ('mail_name', '123456@qq.com');
INSERT INTO `safwl_config` VALUES ('mail_pwd', '');
INSERT INTO `safwl_config` VALUES ('mail_title', '发卡网');
INSERT INTO `safwl_config` VALUES ('tradenotype', '2');
INSERT INTO `safwl_config` VALUES ('paypasstype', '2');
INSERT INTO `safwl_config` VALUES ('isaccvtion', '0');
INSERT INTO `safwl_config` VALUES ('accvtion_notice', '<b>请勿共享自己的访问密码。</b><br>\r\n<b>泄漏密码一律停封。</b><br>');
INSERT INTO `safwl_config` VALUES ('accvtion_notice2', ' <b>请保存好自己的密码不要泄露！后台IP记录。</b>\r\n                       \r\n			<br>\r\n                    ');
INSERT INTO `safwl_config` VALUES ('mail_content', '您已成功购买商品[{@goodsname}],订单编号：{@out_trade_no},您的卡密为：{@kmlist}。');
INSERT INTO `safwl_config` VALUES ('sendphonedx', '0');
INSERT INTO `safwl_config` VALUES ('dx_appid', '');
INSERT INTO `safwl_config` VALUES ('dx_appkey', '');
INSERT INTO `safwl_config` VALUES ('dx_content', '【订单信息】[{@qqnickname}]您好，恭喜您下单成功！您的订单编号为：{@out_trade_no},卡密信息为：{@kmlist}！');
INSERT INTO `safwl_config` VALUES ('coupon_ka_num', '8');
INSERT INTO `safwl_config` VALUES ('iscoupon', '0');
DROP TABLE IF EXISTS `safwl_coupon`;
CREATE TABLE `safwl_coupon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `coupon_remarks` varchar(100) DEFAULT NULL,
  `coupon_ka` varchar(100) DEFAULT NULL,
  `coupon_addtime` datetime DEFAULT NULL,
  `coupon_endtime` datetime DEFAULT NULL,
  `coupon_sytime` datetime DEFAULT NULL,
  `coupon_type` int(11) DEFAULT '1',
  `coupon_value` double(10,2) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `coupon_goods_id` int(11) DEFAULT '0',
  `coupon_status` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `safwl_goods`;
CREATE TABLE `safwl_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gName` varchar(255) DEFAULT NULL,
  `gInfo` text,
  `imgs` varchar(110) DEFAULT NULL,
  `tpId` int(11) NOT NULL COMMENT '',
  `price` decimal(10,2) DEFAULT NULL,
  `price_way` decimal(10,2) DEFAULT NULL,
  `state` int(11) DEFAULT '1',
  `stapp` int(11) DEFAULT '0',
  `sotr` int(4) DEFAULT '1',
  `sales` int(11) DEFAULT '0',
  `ycss` varchar(100) DEFAULT NULL,
  `gobv` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `safwl_km`;
CREATE TABLE `safwl_km` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gid` int(11) NOT NULL,
  `km` varchar(1000000) DEFAULT NULL,
  `benTime` datetime DEFAULT NULL,
  `endTime` datetime DEFAULT NULL,
  `out_trade_no` varchar(100) DEFAULT NULL,
  `trade_no` varchar(100) DEFAULT NULL,
  `rel` varchar(50) DEFAULT NULL,
  `stat` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `safwl_order`;
CREATE TABLE `safwl_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `out_trade_no` varchar(100) DEFAULT NULL,
  `trade_no` varchar(100) DEFAULT NULL,
  `md5_trade_no` varchar(100) DEFAULT NULL,
  `paypass` varchar(100) DEFAULT NULL,
  `gid` int(11) DEFAULT NULL,
  `money` decimal(10,2) DEFAULT NULL,
  `rel` varchar(30) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL,
  `benTime` datetime DEFAULT NULL,
  `endTime` datetime DEFAULT NULL,
  `number` int(11) DEFAULT NULL,
  `coupon_id` varchar(100) DEFAULT NULL,
  `phone` varchar(11) DEFAULT NULL,
  `fcwfid` varchar(50) DEFAULT NULL,
  `sta` int(11) DEFAULT '0',
  `sendE` int(11) DEFAULT '0',
  `qkpb` varchar(255) DEFAULT NULL,
  `jnyt` varchar(50) DEFAULT NULL,
  `ffgh` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `safwl_syslog`;
CREATE TABLE `safwl_syslog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `log_name` varchar(20) DEFAULT NULL,
  `log_time` datetime DEFAULT NULL,
  `log_txt` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `safwl_type`;
CREATE TABLE `safwl_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tName` varchar(100) DEFAULT NULL,
  `sort` int(11) DEFAULT '0',
  `state` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;