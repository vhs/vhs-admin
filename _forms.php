<?php
function form_start($action='',$extra="class='form-horizontal'") {
	echo "<form action='$action' method='post' enctype='multipart/form-data' $extra>";
}
function form_save() {
	echo "<button class='btn btn-primary' name='save-now' type='submit'>Save changes</button>";
}
function form_cancel() {
	echo "<button class='btn btn-cancel' name='cancel-now' type='button'>Cancel</button>";
}
function form_begin_action() {
	echo "<div class='form-group'>";
}
function form_end_action() {
	echo "</div>";
}
function form_end() {
	echo "</form>";
}
function row($input_name,$visible_name,$input,$help_text='') {
      echo "<div class='form-group'>"
           ."  <label for='$input_name' class='col-lg-2 control-label'>".ucwords(str_replace('_',' ',$visible_name))."</label>"
           ."  <div class='col-lg-10'>$input<span class='help-inline'>$help_text</span></div>"
           ."</div>";
}
function hidden($item,$e,$after='') {
	// after does nothing.
	if(is_object($item)) $x=$item->$e;
	else $x=$item[$e];
	echo "<input type='hidden' name='$e' value='".html_encode($x)."'>";
}
function varchar($item,$e,$after='') {
	if(is_object($item)) $x=$item->$e;
	else $x=$item[$e];
	row($e,$e,"<input type='text' class='form-control' name='$e' value='".html_encode($x)."'>",$after);
}
function text($item,$e,$after='') {
	if(is_object($item)) $x=$item->$e;
	else $x=$item[$e];
	row($e,$e,"<textarea name='$e' class='form-control' class='span6'>".html_encode($x)."</textarea>",$after);
}
function number($item,$e,$after='') {
	if(is_object($item)) $x=$item->$e;
	else $x=$item[$e];
	row($e,$e,"<input type='number' name='$e' value='".html_encode($x)."'>",$after);
}
function money($item,$e,$after='') {
	if(is_object($item)) $x=$item->$e;
	else $x=$item[$e];
	row($e,$e,"<div class='input-prepend'><span class='add-on'>$</span><input type='number' name='$e' value='".number_format($x,2)."'></div>",$after);
}
function varchar_file($item,$e,$after='') {
  $str='';
	if(is_object($item)) $x=$item->$e;
	else $x=$item[$e];
  if($x!='') $str.="<em><a href='".html_encode($x)."' target='_blank'>".html_encode($x)."</a></em><br>";
	row($e,$e,$str."<input type='file' name='$e'>",$after);
}
function varchar_image($item,$e,$after='') {
  $str='';
	if(is_object($item)) $x=$item->$e;
	else $x=$item[$e];
  if($x!='') $str.="<em><a href='".html_encode($x)."' target='_blank'>".html_encode($x)."</a></em><br>";
	row($e,$e,$str."<input type='file' name='$e'>",$after);
}
function enum_bool($item,$e,$after='') {
	$str="<option value='yes'>Yes</option>"
		."<option value='no'>No</option>";
	if(is_object($item)) $x=$item->$e;
	else $x=$item[$e];
	$str=str_replace("value='$x'","value='$x' selected",$str);
	row($e,$e,"<select name='$e'>$str</select>",$after);
}
function enum_quality($item,$e,$after='') {
	$str="<option value='missing'>Missing</option>"
		."<option value='poor'>Poor</option>"
		."<option value='ok'>OK</option>"
		."<option value='exemplary'>Exemplary</option>";
	if(is_object($item)) $x=$item->$e;
	else $x=$item[$e];
	$str=str_replace("value='$x'","value='$x' selected",$str);
	row($e,$e,"<select name='$e'>$str</select>",$after);
}

function enum_order_status($item,$e,$after='') {
	$str="<option value='shopping'>Shopping</option>"
		."<option value='paying'>Paying</option>"
		."<option value='fulfilling'>Fulfilling</option>"
		."<option value='shipped'>Shipped</option>"
		."<option value='updated'>Updated</option>";
	if(is_object($item)) $x=$item->$e;
	else $x=$item[$e];
	$str=str_replace("value='$x'","value='$x' selected",$str);
	row($e,$e,"<select name='$e'>$str</select>",$after);
}

function readonly($item,$e,$after='') {
	if(is_object($item)) $x=$item->$e;
	else $x=$item[$e];
	row($e,$e,"<p>".html_encode($x)."</p>",$after);
}
function start_set($name) {
	echo "<fieldset><legend>$name</legend>";
}
function end_set() {
	echo "</fieldset>";
}
function next_set($name) {
	end_set();
	start_set($name);
}
?>
