<?php
require_once "_forms.php";
require_once "model.inventory.php";

form_start();
start_set("Inventory");

form_begin_action();
echo "<button class='btn btn-primary' name='add-now' type='submit'>Add new item</button>";
form_end_action();

if(count($all_inventory)) {
	echo "<table class='table table-condensed table-hover'>";
	echo "<thead><tr>";
	echo "<th>SKU</th>";
	echo "<th>Name</th>";
	echo "<th class='text-right'>MSRP</th>";
	echo "<th>Qty</th>";
	echo "</tr></thead><tbody>";
	foreach($all_inventory as $i) {
		if($i->next_id) {
			$add=" class='warning'";
			$pre_name="<strike>";
			$post_name="</strike> Replaced by ".$all_inventory[$i->next_id]->SKU.".";
		} else if($i->discontinued=='yes') {
			$add=" class='error'";
			$pre_name=$post_name="";
		} else if($i->next_id!=0) {
			$add=" class='error'";
			$pre_name=$post_name="";
		} else if($i->published=='yes') {
			$add=" class='success'";
			$pre_name=$post_name="";
		} else {
			$add=$pre_name=$post_name='';
		}
		echo "<tr$add>";
		echo "<td><a href='".SITE_URL."/inventory/update/".$i->id."'>".$i->SKU."</a></td>";
		echo "<td>".$pre_name.$i->name.$post_name."</td>";
		echo "<td class='text-right'>$".number_format($i->MSRP,2)."</td>";
		echo "<td>".$i->qty."</td>";
		echo "</tr>";
	}
	echo "</tbody></table>";
}
end_set();
form_begin_action();
echo "<button class='btn btn-primary' name='add-now' type='submit'>Add new item</button>";
form_end_action();
form_end();
?>