<?php
require_once "_forms.php";
//d($user);
form_start();
start_set("Details");
varchar($user,'name');
varchar($user,'email');
enum_bool($user,'active');
readonly($user,'created_on');
end_set();

start_set("Addresses");
if(count($user->addresses)) {
	$i=0;
	foreach($user->addresses as $v) {
		if($i==0) {
			echo "<div class='row'>";
		}
		$address=new Address($v->address_id);
		echo "<div class='span3'>";
		include "view.address_read.php";
		echo "<p><a href='".SITE_URL."/account/edit_address/".$address->id."'>Edit</a><p>";
		echo "</div>";
		++$i;
		if($i==4) {
			echo "</div>";
			$i=0;
		}
	}
	if($i!=0) {
		echo "</div>";
	}
}
// add address button
echo "<p><input class='btn' type='submit' name='add-address-now' value='Save &amp; add address'></p>";
end_set();

form_begin_action();
form_save();
form_end_action();
form_end();
?>
