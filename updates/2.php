<?php
q("CREATE TABLE `inventory` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `SKU` varchar(32) NOT NULL,
  `prev_id` int(10),
  `next_id` int(10),
  `name` varchar(64) NOT NULL,
  `qty` float DEFAULT 0 NOT NULL,
  `supplier` text,
  `weight` float not null default 0,
  `description` text,
  PRIMARY KEY (`id`,`SKU`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");
?>