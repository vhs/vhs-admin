<?php
require_once "model.inventory.php";
require_once "_forms.php";

start_set("Orders");
if(count($all_orders)) {
	echo "<table class='table table-condensed'>";
	echo "<thead><tr><th>#</th><th>When</th><th>Who</th><th class='text-right'>$</th><th>Status</th></tr></thead><tbody>";
	foreach($all_orders as $o) {
		if($o->customer_account_id) {
			$a=new Account($o->customer_account_id);
			$name=$a->name;
		} else {
			$name="<em>Unknown</em>";
		}
		echo "<tr>";
		echo "<td><a href='".SITE_URL."/order/update/".$o->id."'>".$o->id."</a></td>";
		echo "<td>".$o->created_on."</td>";
		echo "<td>".$name."</td>";
		echo "<td class='text-right'>$".number_format($o->total,2)."</td>";
		echo "<td>".$o->status."</td>";
		echo "</tr>";
	}
	echo "</tbody></table>";
}
end_set();
?>