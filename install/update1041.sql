alter table safwl_order add number int after endTime;
update safwl_config set safwl_k = 'epay_id' where safwl_k = 'xq_id';
update safwl_config set safwl_k = 'epay_key' where safwl_k = 'xq_key';
update safwl_config set safwl_k = 'payapi' where safwl_k = 'paiapi';
DROP TABLE IF EXISTS `safwl_blacklist`;
CREATE TABLE `safwl_blacklist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `data` varchar(200) DEFAULT NULL,
  `remarks` text,
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