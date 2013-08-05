<?php
q("alter table inventory add bulk_total_cost float not null default 0");
q("alter table inventory add video_quality enum('missing','poor','ok','exemplary') default 'missing' not null");
q("alter table inventory add image_quality enum('missing','poor','ok','exemplary') default 'missing' not null");
q("alter table inventory add description_quality enum('missing','poor','ok','exemplary') default 'missing' not null");
q("alter table inventory add data_sheet_quality enum('missing','poor','ok','exemplary') default 'missing' not null");
q("alter table inventory add margin float not null default 0");

q("alter table inventory_children add id int(10) unsigned auto_increment primary key not null;");
?>