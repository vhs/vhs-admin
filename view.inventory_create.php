<?php
require_once "_forms.php";
//d($item);
form_start('',"class='form-horizontal'");

start_set('Geneaology');
$name="SKU-category";
row($name,"SKU category","<input type='text' name='$name' value=''>",
	"The category is the letter code left of the hyphen.  ELE, ROB, MOT, etc.  See other inventory items for examples.");

end_set();
form_begin_action();
form_save();
form_end_action();
form_end();

?>