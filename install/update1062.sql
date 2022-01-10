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
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;