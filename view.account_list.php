<?php
require_once "_forms.php";
require_once "model.account.php";

form_start();
start_set("Accounts");

form_begin_action();
echo "<a href='".SITE_URL."/account/login' class='btn btn-primary' name='add-now' type='submit'>Register new account</a>";
form_end_action();

if(count($all_accounts)) {
	echo "<table class='table table-condensed table-hover'>";
	echo "<thead><tr>";
	echo "<th>ID</th>";
	echo "<th>Name</th>";
	echo "<th>Email</th>";
	echo "</tr></thead><tbody>";
	foreach($all_accounts as $i) {
		if($i->active=='no') {
			$add=" class='error'";
			$pre_name=$post_name="";
		} else /*if($i->next_id!=0) {
			$add=" class='error'";
			$pre_name=$post_name="";
		} else if($i->published=='yes') {
			$add=" class='success'";
			$pre_name=$post_name="";
		} else*/ {
			$add=$pre_name=$post_name='';
		}
		echo "<tr$add>";
		echo "<td><a href='".SITE_URL."/account/update/".$i->id."'>".$i->id."</a></td>";
		echo "<td><a href='".SITE_URL."/account/update/".$i->id."'>".$pre_name.$i->name.$post_name."</a></td>";
		echo "<td>".$i->email."</td>";
		echo "</tr>";
	}
	echo "</tbody></table>";
}
end_set();
form_end();
?>