<?php
require_once "_forms.php";
require_once "model.inventory.php";

start_set('Order #'.$order->id);

echo "<div class='row'>";
echo "<div class='span4'>";
if(isset($order->created_on)) readonly($order,'created_on');
echo "</div>";
echo "<div class='span4'>";
echo "<h5>Billing address</h5>";
if($order->billing_address_id!=0) {
	$address=new Address($order->billing_address_id);
	include "view.address_read.php";
} else {
	echo "<p><em>No address given</em></p>";
}
echo "</div>"; // span
echo "<div class='span4'>";
echo "<h5>Shipping address</h5>";
if($order->shipping_address_id!=0) {
	$address=new Address($order->shipping_address_id);
	include "view.address_read.php";
} else {
	echo "<p><em>No address given</em></p>";
}
echo "</div>"; // span
echo "</div>"; // row

echo "<div class='row'>";
echo "<div class='span12'>";
if(count($order->items)) {
	$order->load_bill_of_materials();
	if(count($order->BOM)) {
		echo "<table class='table table-condensed'><thead><tr>";
		echo "<th>SKU</th>";
		echo "<th class='text-right'>Qty</th>";
		echo "<th>Name</th>";
		echo "</tr></thead><tbody>";
		foreach($order->BOM as $BOMi=>$BOMd) {
			$SKU=$BOMd['SKU'];
			$name=$BOMd['name'];
			$qty=$BOMd['qty'];
			echo "<tr>";
			echo "<td>$SKU</td>";
			echo "<td class='text-right'>$qty</td>";
			echo "<td><a target='_blank' href='".SITE_URL."/inventory/update/".$BOMi."'>$name</a></td>";
			echo "</tr>";
		}
		echo "</tbody></table>";
	}
}
echo "</div>"; // span
echo "</div>"; // row

end_set();
?>