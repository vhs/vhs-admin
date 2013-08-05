<!--
<ul class='breadcrumb'><li><a href='./'>Home</a><span class='divider'>/</span></li><li class='active'>My Account</li></ul>
<h1>My Account</h1>
-->
<?php
require_once "_forms.php";
form_start('',"class='form-horizontal'");

start_set("Edit Address");
hidden($address,'id');
readonly($address,'created_on');
varchar($address,'recipient');
varchar($address,'street');
varchar($address,'city');
varchar($address,'region','<em>province or state</em>');
varchar($address,'country');
varchar($address,'postal');
varchar($address,'phone');

end_set();
form_begin_action();
form_save();
form_end_action();
form_end();
?>