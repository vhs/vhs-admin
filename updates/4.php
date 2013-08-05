<?php
q("alter table `inventory` add `MSRP` float not null default 0;");
q("alter table `inventory` add `bulk_purchase_qty` float not null default 0;");
q("alter table `inventory` add `bulk_purchase_cost` float not null default 0;");
q("alter table `inventory` add `bulk_import_fee` float not null default 0;");
q("alter table `inventory` add `unit_cost` float not null default 0;");
q("alter table `inventory` add `video` varchar(128) not null default '';");
q("alter table `inventory` add `data_sheet` varchar(128) not null default '';");

q("create table `inventory_children` (
  parent_id int(10) NOT NULL default 0,
  child_id int(10) NOT NULL default 0,
  child_qty int(10) NOT NULL default 1
) engine=innodb charset=utf8;");
?>