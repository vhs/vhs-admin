<?php
q("CREATE TABLE `address` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created_on` datetime NOT NULL,
  `account_id` int(10) NOT NULL default 0,
  `recipient` varchar(64) NOT NULL DEFAULT '',
  `street` varchar(64) NOT NULL DEFAULT '',
  `city` varchar(64) NOT NULL DEFAULT '',
  `region` varchar(64) NOT NULL DEFAULT '',
  `country` varchar(64) NOT NULL DEFAULT '',
  `postal` varchar(12) NOT NULL DEFAULT '',
  `phone` varchar(16) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");

q("CREATE TABLE `order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created_on` datetime NOT NULL,
  `customer_account_id` int(10) NOT NULL,
  `billing_address_id` int(10) NOT NULL,
  `shipping_address_id` int(10) NOT NULL,
  `subtotal` float NOT NULL default 0,
  `subtotal_tax` float NOT NULL default 0,
  `shipping` float NOT NULL default 0,
  `shipping_tax` float NOT NULL default 0,
  `total` float NOT NULL default 0,
  `status` enum('shopping','paying','fulfilling','shipped','updated'),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");

q("CREATE TABLE `order_inventory` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) NOT NULL,
  `inventory_id` int(10) NOT NULL,
  `order_qty` int(10) NOT NULL,
  `price` float NOT NULL default 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");

q("CREATE TABLE `order_history` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) NOT NULL,
  `created_on` datetime,
  `sender_id` int(10) NOT NULL,
  `message` text,
  `status` enum('shopping','paying','fulfilling','shipped','updated'),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");

?>