<?php
require_once "_forms.php";
//d($item);

echo "<div class='row-fluid'>";
echo "<div id='video' class='span12'>";
echo "<h1>".$item->SKU.' '.$item->name."</h1>";
echo "</div>";
echo "</div>";

echo "<div class='row-fluid'>";
echo "<div id='video' class='span10'>";

if($item->video) {
  // video
	echo "<div class='video-container'>";
	echo "<iframe width='560' height='315' src='http://www.youtube.com/embed/".$item->video."' frameborder='0' allowfullscreen></iframe>";
	echo "</div>";
}
// images
$sku=$item->SKU;
//d(SITE_PATH."/images/$sku.*");
foreach (glob(SITE_PATH."/images/$sku*.*") as $filename) {
  $path=SITE_URL.substr($filename,strlen(SITE_PATH));
  if(!strstr($path,"_th.")) {
    echo "<div style='text-align:center'><img src='$path'></div>";
  }
}

echo "</div>";  // span10
echo "<div id='buy' class='span2'>";

if($item->discontinued=='yes') {
	echo "<div class='well'>";
	echo "This item was discontinued ".$item->discontinued_on.".";
	echo "</div>";
} else {
	form_start(SITE_URL.'/order/update');
	echo "<input type='hidden' name='add-item-id' value='".$item->id."'>";
	echo "<div class='product-add-now'>";
	echo "<h3>$".number_format($item->MSRP,2)."</h3>";
	echo "<input type='submit' class='btn' name='add-item-now' value='Add to cart'>";
	echo "</div>";

	echo "<div class='product-prices'>";
	echo "Qty <input class='product-qty' type='number' name='qty' value='1'>";
	//echo "<p>Price breaks go here.</p>";
	echo "</div>";
	form_end();
}
echo "</div>";  // span3
echo "</div>";  // row

echo "<div class='row-fluid'>";
if(count($item->children)) {
	echo "<div class='span6'>";
} else {
	echo "<div class='span12'>";
}

if($item->next_id!=0) {
	$next = new Inventory($item->next_id);
  if($next->published=='yes') {
    echo "<p>This item has been replaced by <a href='".$next->get_published_url()."'>".$next->get_full_name()."</a>.</p>";
  }
}
if($item->prev_id!=0) {
	$prev = new Inventory($item->prev_id);
	echo "<p>This item replaces <a href='".$prev->get_published_url()."'>".$prev->get_full_name()."</a>.</p>";
}

echo "<div id='product_description'>";
$part=preg_split("/(\[\[.*?\]\])/",$item->description, null, PREG_SPLIT_DELIM_CAPTURE);
foreach($part as $p) {
  if(preg_match("/\[\[(.*)\]\]/",$p,$m)) {
    $other_item=new Inventory();
    $other_item->find_by_SKU($m[1]);
    if($other_item->id!=0) {
      echo "<a href='".$other_item->get_published_url()."'>".$other_item->get_full_name()."</a>";
    } else {
      echo "<b>$p</b>";
    }
  } else {
    echo $p;
  }
}
echo "</div>";

//echo "<div id='weight'>".$item->weight." grams</div>";
if($item->data_sheet!='' && file_exists($item->data_sheet)) {
	echo "<div id='data_sheet'><a href='".$item->data_sheet."'>Data sheet</a></div>";
}


if(count($item->children)) {
	echo "</div>";  // span
	echo "<div class='span6'>";

	//$cost=0;
	echo "<h4>Contains:</h4>";
	echo "<table class='table table-condensed'><thead><tr>";
	echo "<th class='text-right'>Qty</th>";
	echo "<th>SKU</th>";
	echo "<th>Name</th>";
	//echo "<th class='text-right'>MSRP</th>";
	//echo "<th class='text-right'>Subtotal</th>";
	echo "</tr></thead><tbody>";
	foreach($item->children as $oi) {
		$i=new Inventory($oi->child_id);
		$name=$i->name;
		$SKU=$i->SKU;
		//$MSRP=$item->MSRP;
		$qty=intval($oi->child_qty);
		//$subtotal=$qty*$MSRP;
		//$cost+=$subtotal;
		//$MSRP='$'.number_format($MSRP,2);
		//$subtotal='$'.number_format($subtotal,2);
		echo "<tr>";
		echo "<td class='text-right'>$qty</td>";
		echo "<td>$SKU</td>";
		echo "<td><a target='_blank' href='".$i->get_published_url()."'>$name</a></td>";
		//echo "<td class='text-right'>$MSRP</td>";
		//echo "<td class='text-right'>$subtotal</td>";
		echo "</tr>";
	}
	//$cost='$'.number_format($cost,2);
	//echo "<tr><th colspan='4' class='text-right'>Total:</th><td class='text-right'>".$cost."</td></tr>";
	echo "</tbody></table>";
}

echo "</div>"; // span
echo "</div>"; // row

if(Account_Controller::is_allowed('Inventory','Edit')) {
  echo "<p><a href='".SITE_URL."/inventory/update/".$item->id."' target='_blank'>Edit this item</a></p>";
}
?>