<?php
require_once "model.php";

class Inventory_Children extends Model {
	static function find($parent_id,$child_id) {
		return qone("SELECT id FROM `inventory_children` WHERE parent_id='$parent_id' AND child_id='$child_id' LIMIT 1");
	}

	function change_qty($amnt) {
		if(!isset($this->child_qty)) $this->child_qty=0;
		$this->child_qty+=$amnt;
		if($this->child_qty<0) $this->child_qty=0;
	}
}
?>