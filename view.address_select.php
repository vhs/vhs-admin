<!--
<ul class='breadcrumb'><li><a href='./'>Home</a><span class='divider'>/</span></li><li class='active'>My Account</li></ul>
<h1>My Account</h1>
-->
<?php
require_once "_forms.php";
form_start();
start_set("Select ".$type." address");
foreach($list as $address) {
	echo "<div class='span3'>";
	include "view.address_read.php";
	echo "<p><input type='submit' class='btn align-center' name='".$address->id."' value='Select'></p>";
	echo "</div>";
}
end_set();
form_end();
?>