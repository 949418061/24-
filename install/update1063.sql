alter table safwl_order add coupon_id varchar(100) after number;
SET FOREIGN_KEY_CHECKS=0;
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