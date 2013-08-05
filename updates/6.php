<?php
q("alter table inventory add deleted enum('no','yes') default 'no' not null");
q("alter table inventory add published enum('no','draft','yes') default 'no' not null");
q("alter table inventory add created_on datetime not null");
q("alter table inventory_children add created_on datetime not null");
q("alter table account add deleted enum('no','yes') default 'no' not null");
?>