CREATE DATABASE IF NOT EXISTS zf2_skeleton;
USE zf2_skeleton;

CREATE TABLE IF NOT EXISTS  `auth` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user` varchar(25) NOT NULL,
  `password` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `auth` VALUES (1,'jorts',"6f4cd1d1cc4af385366685713a42415aaa6b874b");