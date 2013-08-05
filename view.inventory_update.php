<?php
require_once "_forms.php";
//d($item);
form_start('',"class='form-horizontal'");

echo "<p><a href='".SITE_URL."/inventory/'>Back to inventory</a></p>";

start_set('Geneaology');
readonly($item,'SKU');
readonly($item,'created_on');
if($item->prev_id!=0) {
	$prev = new Inventory($item->prev_id);
	row('prev_id','Previous evolution',"<p>This item replaces <a href='".SITE_URL."/inventory/update/".$prev->id."'>".$prev->get_full_name()."</a>.</p>");
}
if($item->next_id!=0) {
	$next = new Inventory($item->next_id);
	row('next_id','Next evolution',"<p>This item has been replaced by <a href='".SITE_URL."/inventory/update/".$next->id."'>".$next->get_full_name()."</a>.</p>");
} else {
	row('mutate-now','Next evolution',"<button class='btn btn-success' name='mutate-now' type='submit'>Mutate</button>");
}
readonly($item,'deleted');

if($item->published=='no') {
	row('publish-now','Published',"<button class='btn btn-warning' name='publish-now' type='submit'>Publish</button>");
} else {
	row('publish-now','Published',"Item has been published.  <a href='".$item->get_published_url()."' target='_blank'>Click here to see it</a>.");

	if($item->discontinued=='no') {
		row('discontinue-now','Discontinue',"<button class='btn btn-warning' name='discontinue-now' type='submit'>Discontinue</button>");
	} else {
		row('discontinue-now','Discontinue',"Item has been discontinued.");
	}
}

row('copy-now','Copy',"<button class='btn' name='copy-now' type='submit'>Copy</button>");


start_set('Description');
varchar($item,'name');
number($item,'qty','units');
number($item,'weight','grams');
text($item,'description');
varchar($item,'video','Youtube URL');
varchar_file($item,'data_sheet');
// images?
$sku=$item->SKU;
//d(SITE_PATH."/images/$sku.*");
foreach (glob(SITE_PATH."/images/$sku*.*") as $filename) {
  $path=SITE_URL.substr($filename,strlen(SITE_PATH));
  echo "<div><img src='$path'></div>";
}

next_set('Money');
text($item,'supplier');
number($item,'bulk_purchase_qty','units');
money($item,'bulk_purchase_cost');
money($item,'bulk_import_fee');
money($item,'bulk_total_cost');
money($item,'unit_cost');
money($item,'MSRP');
money($item,'margin');

next_set('Quality');
enum_quality($item,'video_quality');
enum_quality($item,'image_quality');
enum_quality($item,'description_quality');
enum_quality($item,'data_sheet_quality');

next_set('Children');
$remove_options='';
if(count($item->children)) {
	$cost=0;
	echo "<table class='table table-condensed'><thead><tr>";
  echo "<th>SKU</th>";
  echo "<th>Name</th>";
  echo "<th class='text-right'>Qty</th>";
  echo "<th class='text-right'>MSRP</th>";
  echo "<th class='text-right'>Subtotal</th>";
  echo "</tr></thead><tbody>";
	foreach($item->children as $oi) {
		$i=new Inventory($oi->child_id);
		$name=$i->name;
		$SKU=$i->SKU;
		$MSRP=$i->MSRP;
		$qty=intval($oi->child_qty);
		$subtotal=$qty*$MSRP;
		$cost+=$subtotal;
		$MSRP='$'.number_format($MSRP,2);
		$subtotal='$'.number_format($subtotal,2);
		echo "<tr>";
    echo "<td>$SKU</td>";
    echo "<td><a target='_blank' href='".SITE_URL."/inventory/update/".$i->id."'>$name</a></td>";
    echo "<td class='text-right'>$qty</td>";
    echo "<td class='text-right'>$MSRP</td>";
    echo "<td class='text-right'>$subtotal</td>";
    echo "</tr>";
		$remove_options.="<option value='".$i->id."'>$name</option>";
	}
	$cost='$'.number_format($cost,2);
	echo "<tr><th colspan='4' class='text-right'>Total:</th><td class='text-right'>".$cost."</td></tr>";
	echo "</tbody></table>";
}

// list the children that could be added.
// this would be great as an auto-complete ajax field.
$add_options='';
$mask = $item->id;
$r=q("SELECT id,SKU,name FROM `inventory` WHERE next_id IS NULL AND deleted='no' AND id<>'$mask' ORDER BY SKU");
while($row=mysqli_fetch_assoc($r)) {
	$id=$row['id'];
	$SKU=$row['SKU'];
	$name=$row['name'];
	$add_options.="<option value='$id'>[$SKU] $name</option>";
}
if($add_options!='') {
	row('add-child-id','Add',"<select class='span5' name='add-child-id'>$add_options</select>","<button class='btn' name='add-child-now' type='submit'>Save &amp; add child</button>");
}
if(count($item->children)) {
	row('remove-child-id','Remove',"<select class='span5' name='remove-child-id'>$remove_options</select>","<button class='btn' name='remove-child-now' type='submit'>Save &amp; remove child</button>");
}

end_set();
form_begin_action();
form_save();
form_end_action();
form_end();

echo "<p><a href='".SITE_URL."/inventory/bom/".$item->id."'>See printable Bill of Materials</a>.</p>";
echo "<p><a href='".$item->get_published_url()."' target='_blank'>View this item</a>.</p>";

?>