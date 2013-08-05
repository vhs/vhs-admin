<?php
require_once "_forms.php";
//d($category);

echo "<div class='row-fluid'>";
echo "<div id='video' class='span12'>";
echo "<h1>".$category->name."</h1>";
echo "</div>";
echo "</div>";

echo "<div class='row-fluid'>";
echo "<div class='span12'>";

echo "<div id='category_description'>";
$part=preg_split("/(\[\[.*?\]\])/",$category->description, null, PREG_SPLIT_DELIM_CAPTURE);
foreach($part as $p) {
  if(preg_match("/\[\[(.*)\]\]/",$p,$m)) {
    $other_item=new Inventory();
    $other_item->find_by_SKU($m[1]);
    if($other_item->id!=0) {
      echo "<a href='".SITE_URL."/shop/".$m[1]."'>".$other_item->get_full_name()."</a>";
    } else {
      echo $p;
    }
  } else {
    echo $p;
  }
}
echo "</div>";
echo "</div>";  // span
echo "</div>";

if(count($category->inventory)) {
	echo "<h4>Items</h4>";
	echo "<div class='row-fluid'>";
	$j=0;
	foreach($category->inventory as $oi) {
		$i=new Inventory($oi->inventory_id);
		$name=$i->name;
		$SKU=$i->SKU;
    $i->icon= $SKU."_th.jpg";
		echo "<div class='span2 product_icon'><a href='".$i->get_published_url()."'><img src='".SITE_URL."/images/".$i->icon."' alt='$SKU $name' title='$SKU $name'> $name</a> $".number_format($i->MSRP,2)."</div>";
		if(++$j==6) {
			echo "</div><div class='row-fluid'>";
			$j=0;
		}
	}
	echo "</div>"; // row
}

if(count($category->subcategories)) {
	echo "<h4>Subcategories</h4>";
	echo "<div class='row-fluid'>";
	foreach($category->subcategories as $oi) {
		$i=new Category($oi);
		$name=$i->name;
		echo "<div class='span2 category_icon'><a href='".SITE_URL."/shop/$name'><img src='".SITE_URL."/images/category/".$i->icon."' alt='$name' title='$name'></a></div>";
	}
	echo "</div>"; // row
}


if(Account_Controller::is_allowed('Category','Edit')) {
  echo "<p><a href='".SITE_URL."/category/update/".$category->id."'>Edit this item</a></p>";
}
?>