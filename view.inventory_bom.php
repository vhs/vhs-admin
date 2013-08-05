<?php
require_once "_forms.php";

echo "<h1>".$item->get_full_name()."</h1>";

form_start();

if(count($item->children)) {
	echo "<table class='table table-condensed'>";
  echo "<thead><tr>";
  echo "<th>SKU</th>";
  echo "<th>Name</th>";
  echo "<th class='text-right'>Qty</th>";
  echo "</tr></thead><tbody>";
	foreach($item->children as $oi) {
		$i=new Inventory($oi->child_id);
		$name=$i->name;
		$SKU=$i->SKU;
		$MSRP=$i->MSRP;
		$qty=intval($oi->child_qty);
		echo "<tr>";
    echo "<td>$SKU</td>";
    echo "<td><a target='_blank' href='".SITE_URL."/inventory/update/".$i->id."'>$name</a></td>";
    echo "<td class='text-right'>$qty</td>";
    echo "</tr>";
	}
	echo "</tbody></table>";
}

form_end();
?>