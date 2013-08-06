<?php
require_once "_forms.php";
//d($user);
form_start();
start_set("Details");
readonly($user,'name');
varchar($user,'email');
enum_bool($user,'active');
readonly($user,'created_on');
readonly($user,'karma');
end_set();

// add address button
//echo "<p><input class='btn' type='submit' name='add-badge-now' value='Save &amp; add badge'></p>";
if(count($user->badges)) {
	start_set("Badges");
	$i=0;
	foreach($user->badges as $v) {
		if($i==0) {
			echo "<div class='row'>";
		}
		$badge=new badge($v->badge_id);
		echo "<div class='col-lg-3'>";
		include "view.badge_read.php";
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
	end_set();
}

start_set("Addresses");
// add address button
echo "<p><input class='btn' type='submit' name='add-address-now' value='Save &amp; add address'></p>";
if(count($user->addresses)) {
	$i=0;
	foreach($user->addresses as $v) {
		if($i==0) {
			echo "<div class='row'>";
		}
		$address=new Address($v->address_id);
		echo "<div class='col-lg-3'>";
		echo "<p><a href='".SITE_URL."/account/edit_address/".$address->id."'>Edit</a><p>";
		include "view.address_read.php";
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
end_set();

form_begin_action();
form_save();
form_end_action();
form_end();
?>
