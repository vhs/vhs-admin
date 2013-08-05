<?php
require_once "model.php";

class Order_Inventory extends Model {
	static function find($order_id,$inventory_id) {
		return qone("SELECT id FROM `inventory_children` WHERE order_id='$order_id' AND inventory_id='$inventory_id' LIMIT 1");
	}

	function change_qty($amnt) {
		if(!isset($this->order_qty)) $this->order_qty=0;
		$this->order_qty+=$amnt;
		if($this->order_qty<0) $this->order_qty=0;
	}
}
?>