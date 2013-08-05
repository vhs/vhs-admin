<?php
require_once "_forms.php";
require_once "model.inventory.php";

form_start('',"class='form-horizontal'");

start_set('Order #'.$order->id);
if(isset($order->created_on)) readonly($order,'created_on');
enum_order_status($order,'status');

echo "<ul class='nav nav-tabs' id='myTab'>
<li class='active'><a href='#items' data-toggle='tab'>Items</a></li>
<li><a href='#addresses' data-toggle='tab'>Addresses</a></li>
<li><a href='#bom' data-toggle='tab'>Bill of Materials</a></li>
</ul>
<div class='tab-content'>
<div class='tab-pane active' id='items'>";

$remove_options='';
if(count($order->items)) {
	$cost=0;
	echo "<table class='table table-condensed'><thead><tr><th>SKU</th><th>Name</th><th class='text-right'>Qty</th><th class='text-right'>MSRP</th><th class='text-right'>Subtotal</th></tr></thead><tbody>";
	foreach($order->items as $oi) {
		$item=new Inventory($oi->inventory_id);
		$name=$item->name;
		$SKU=$item->SKU;
		$MSRP=$item->MSRP;
		$qty=intval($oi->order_qty);
		$subtotal=$qty*$MSRP;
		$cost+=$subtotal;
		$MSRP='$'.number_format($MSRP,2);
		$subtotal='$'.number_format($subtotal,2);
		echo "<tr><td>$SKU</td><td><a target='_blank' href='".SITE_URL."/inventory/update/".$item->id."'>$name</a></td><td class='text-right'>$qty</td><td class='text-right'>$MSRP</td><td class='text-right'>$subtotal</td></tr>";
		$remove_options.="<option value='".$item->id."'>$name</option>";
	}
	$cost='$'.number_format($cost,2);
	echo "<tr><th colspan='4' class='text-right'>Total:</th><td class='text-right'>".$cost."</td></tr>";
	echo "</tbody></table>";
}

// list the items that could be added.
// this would be great as an auto-complete ajax field.
$add_options='';
$mask = $order->id;
$r=q("SELECT id,SKU,name FROM `inventory` WHERE next_id IS NULL AND deleted='no' AND id<>'$mask' ORDER BY SKU");
while($row=mysqli_fetch_assoc($r)) {
	$id=$row['id'];
	$SKU=$row['SKU'];
	$name=$row['name'];
	$name="[$SKU] $name";
	$add_options.="<option value='$id'>$name</option>";
}
if($add_options!='') {
	row('add-item-id','Add',"<select class='span5' name='add-item-id'>$add_options</select>","<button class='btn' name='add-item-now' type='submit'>Save &amp; add item</button>");
}
if(count($order->items)) {
	row('remove-item-id','Remove',"<select class='span5' name='remove-item-id'>$remove_options</select>","<button class='btn' name='remove-item-now' type='submit'>Save &amp; remove item</button>");
}

echo "</div>"; // tabs


echo "<div class='tab-pane' id='addresses'>";
echo "<div class='row'>";
echo "<div class='span6'>";
echo "<h3>Billing address</h3>";
if($order->billing_address_id!=0) {
	$address=new Address($order->billing_address_id);
	include "view.address_read.php";
} else {
	echo "<p><em>No address given</em></p>";
}
echo "<p><a href='".SITE_URL."/order/change_address/billing/".$order->id."'>Change address</a></p>";
echo "</div>"; // span
echo "<div class='span6'>";
echo "<h3>Shipping address</h3>";
if($order->shipping_address_id!=0) {
	$address=new Address($order->shipping_address_id);
	include "view.address_read.php";
} else {
	echo "<p><em>No address given</em></p>";
}
echo "<p><a href='".SITE_URL."/order/change_address/shipping/".$order->id."'>Change address</a></p>";
echo "</div>"; // span
echo "</div>"; // row
echo "</div>"; // tabs

if(count($order->items)) {
	echo "<div class='tab-pane' id='bom'>";

	$order->load_bill_of_materials();
	if(count($order->BOM)) {
		$cost=0;
		echo "<table class='table table-condensed'><thead><tr><th>SKU</th><th>Name</th><th class='text-right'>Qty</th><th class='text-right'>Price</th><th class='text-right'>Subtotal</th></tr></thead><tbody>";
		foreach($order->BOM as $BOMi=>$BOMd) {
			$SKU=$BOMd['SKU'];
			$name=$BOMd['name'];
			$qty=$BOMd['qty'];
			$MSRP=$BOMd['MSRP'];
			$subtotal=$qty*$MSRP;
			$cost+=$subtotal;
			$MSRP='$'.number_format($MSRP,2);
			$subtotal='$'.number_format($subtotal,2);
			echo "<tr><td>$SKU</td><td><a target='_blank' href='".SITE_URL."/inventory/update/".$BOMi."'>$name</a></td><td class='text-right'>$qty</td><td class='text-right'>$MSRP</td><td class='text-right'>$subtotal</td></tr>";
		}
		$cost='$'.number_format($cost,2);
		echo "<tr><th colspan='4' class='text-right'>Total:</th><td class='text-right'>".$cost."</td></tr>";
		echo "</tbody></table>";
	}
	echo "</div>";  // tab
}
echo "</div>";  // tabs

end_set();

echo "<div class='row'>";
echo "<div class='span12'>";
echo "<p><a href='".SITE_URL."/order/bom/".$order->id."'>Printable version</a></p>";
echo "</div>";  // span
echo "</div>";  // tabs

form_end();
?>