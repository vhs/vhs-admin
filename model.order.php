<?php
require_once "model.php";
require_once "model.order_inventory.php";
require_once "model.inventory.php";


class Order extends Model {
	function __construct($id=0) {
		$this->items=array();
		parent::__construct($id);
	}

	function load() {
		parent::load();
		$this->items=array();
		$r=q("SELECT id FROM `order_inventory` WHERE order_id='".qsafe($this->id)."' AND order_qty>0");
		while($row=mysqli_fetch_row($r)) {
			$this->items[]=new Order_Inventory($row[0]);
		}
		//d($this);
	}

	function save() {
		$this->load_bill_of_materials();

		$this->total = 0;
		foreach($this->BOM as $v) {
			$this->total+=$v['qty']* $v['MSRP'];
		}
		parent::save();
	}

	static function find($account_id,$status='shopping') {
		return qone("SELECT id FROM `order` WHERE customer_account_id='$account_id' AND status='$status' LIMIT 1");
	}

	function add_item($item,$units=1) {
		if(empty($item)) trigger_error('item cannot be empty');
		if(is_a($item,'Inventory')) $item=$item->id;

		foreach($this->items as $c=>$v) {
			if($v->inventory_id==$item) {
				// adjust it and quit
				$this->items[$c]->change_qty($units);
				$this->items[$c]->save();
				return;
			}
		}
		// doesn't exist yet, create this combination
		$oi=new Order_Inventory;
		$oi->order_id=$this->id;
		$oi->inventory_id=$item;
		// adjust it
		$oi->change_qty($units);
		$oi->save();
		// add it
		$this->items[]=$oi;
	}


	function remove_item($item,$units=1) {
		if($item=='') trigger_error('Item cannot be empty');
		if(is_a($item,'Inventory')) $item=$item->id;

		foreach($this->items as $c=>$v) {
			if($v->inventory_id==$item) {
				// adjust it and quit
				$this->items[$c]->change_qty(-$units);
				$this->items[$c]->save();
				if($this->items[$c]->order_qty==0) {
					unset($this->items[$c]);
				}
				return;
			}
		}
		// item item doesn't exist?!
		trigger_error("item item doesn't exist?!");
	}


	function __clone() {
		// remove items with qty=0 from future generations.
		$kids=array();
		foreach($this->items as $c=>$v) {
			if($v->order_qty>0) $kids[]=$v;
		}
		$this->items=array();
		foreach($kids as $v) {
			$this->items[] = clone $v;
		}
	}

	function __collect_BOM($inventory_id) {
		$i=new Inventory($inventory_id);
		if(count($i->children)==0) {
			if(!isset($this->BOM[$i->id])) {
				$this->BOM[$i->id]=array(
					'name'=>$i->name,
					'SKU'=>$i->SKU,
					'MSRP'=>$i->MSRP,
					'qty'=>0,);
			}
			++$this->BOM[$i->id]['qty'];
		} else {
			foreach($i->children as $ic) {
				for($j=0;$j<$ic->child_qty;++$j) {
					$this->__collect_BOM($ic->child_id);
				}
			}
		}
	}

	static function __BOM_sort($a,$b) {
		return strcmp($a['SKU'],$b['SKU']);
	}

	function load_bill_of_materials() {
		$this->BOM=array();
		foreach($this->items as $oi) {
			for($j=0;$j<$oi->order_qty;++$j) {
				$this->__collect_BOM($oi->inventory_id);
			}
		}

		usort($this->BOM,"Order::__BOM_sort");
	}
}
?>
