<?php
q("CREATE TABLE `config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `config_key` varchar(64),
  `config_value` varchar(128),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");

q("CREATE TABLE `account` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(256) NOT NULL,
  `password` varchar(128) NOT NULL,
  `active` enum('no','yes') DEFAULT 'yes' NOT NULL,
  `access_type` varchar(32) DEFAULT 'admin',
  `created_on` datetime NOT NULL,
  `name` varchar(64) DEFAULT NULL,
  `deleted` enum('no','yes') DEFAULT 'no' NOT NULL,
  `karma` int not null default 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");

q("INSERT INTO `account` SET 
  `email`='dan@marginallyclever.com',
  `password`=md5('Q@#(RJasdfj843jkdf71'),
  `active`='yes',
  `access_type`='admin',
  `created_on`=NOW(),
  `name`='Dan'");
?>
