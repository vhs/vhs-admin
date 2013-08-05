<?php
// list all inventory
$i=new Inventory;
$fields=array('id'=>'&nbsp;','SKU'=>'SKU','name'=>'Name','qty'=>'Qty','MSRP'=>'MSRP');
showtable("`inventory`",$fields);
?>