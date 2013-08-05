<?php
require_once "model.php";
require_once "model.inventory_children.php";


class Inventory extends Model {
	function __construct($id=0) {
		$this->children=array();
		parent::__construct($id);
	}
	
	function load() {
		parent::load();
		$this->children=array();
		$r=q("SELECT id FROM `inventory_children` WHERE parent_id='".qsafe($this->id)."' AND child_qty>0");
		while($row=mysqli_fetch_row($r)) {
			$this->children[]=new Inventory_Children($row[0]);
		}
		//d($this);
	}

	/**
	 * add child inventory items
	 */
	function add_child($item,$units=1) {
		if($item=='') trigger_error('item cannot be empty');
		if(is_a($item,'Inventory')) $item=$item->id;

		foreach($this->children as $c=>$v) {
			if($v->child_id==$item) {
				// adjust it and quit
				$this->children[$c]->change_qty($units);
				$this->children[$c]->save();
				return;
			}
		}
		// doesn't exist yet, create this combination
		$ic=new Inventory_Children;
		$ic->parent_id=$this->id;
		$ic->child_id=$item;
		// adjust it
		$ic->change_qty($units);
		$ic->save();
		// add it
		$this->children[]=$ic;
	}


	function remove_child($item,$units=1) {
		if($item=='') trigger_error('Item cannot be empty');
		if(is_a($item,'Inventory')) $item=$item->id;

		foreach($this->children as $c=>$v) {
			if($v->child_id==$item) {
				// adjust it and quit
				$this->children[$c]->change_qty(-$units);
				$this->children[$c]->save();
				if($this->children[$c]->child_qty==0) {
					unset($this->children[$c]);
				}
				return;
			}
		}
		// child item doesn't exist?!
		trigger_error("Child item doesn't exist?!");
	}


	function get_unique_sku($category) {
		if($category=='') trigger_error('category cannot be empty.');

		$category=explode('-',$category);
		$category=$category[0];
		// get the largest sku code in this category
		$last=qone("SELECT `SKU` FROM `inventory` WHERE `SKU` LIKE '".qsafe($category)."-%' ORDER BY `SKU` DESC LIMIT 1");
		// get the number part of the SKU
		$num=substr($last,strlen($category)+1);
		// remember how many zeros we had at the front of the SKU number
		$original_length=strlen($num);
		// turn it into a number
		$num=intval(ltrim($num,'0'));
		// add one
		++$num;
		// put the zeros back in, if necessary
		$num=str_pad($num,$original_length,'0',STR_PAD_LEFT);
		// return the reassembled code
		return $category.'-'.$num;
	}


	function __clone() {
		// remove children with qty=0 from future generations.
		$kids=array();
		foreach($this->children as $c=>$v) {
			if($v->child_qty>0) $kids[]=$v;
		}
		$this->children=array();
		foreach($kids as $v) {
			$this->children[] = clone $v;
		}
	}

	/**
	 * create a derivative of this inventory item.
	 */ 
	function evolve() {
		if($this->next_id!=0) trigger_error("Only the last evolution can continue to evolve.");

		$i=clone $this;
		$i->id=0;
		$i->SKU=$this->get_unique_sku($i->SKU);
		$i->prev_id=$this->id;
    	$i->published='no';
		$i->save();
		foreach($this->children as $c=>$v) {
			$i->children[$c]->id=0;
			$i->children[$c]->parent_id=$i->id;
			$i->children[$c]->save();
		}
		$this->next_id=$i->id;
		$this->save();
		//d($this);
		//d($i);
		return $i;
	}
    
    // create a new instance of this item (not a derivative)
	function copy() {
		$i=clone $this;
		$i->id=0;
		$i->SKU=$this->get_unique_sku($i->SKU);
		$i->prev_id=0;
		$i->next_id=0;
    	$i->published='no';
		$i->save();
		foreach($this->children as $c=>$v) {
			$i->children[$c]->id=0;
			$i->children[$c]->parent_id=$i->id;
			$i->children[$c]->save();
		}

		return $i;
	}

	function get_full_name() {
		return "[".$this->SKU."] ".$this->name;
	}

    function get_published_url() {
    	return SITE_URL."/shop/product/".$this->id;
    }
    
	function publish() {
		$this->published='yes';
		$this->publication_time=date('Y-m-d h:i:s');
	}
    
	function discontinue() {
		$this->discontinued='yes';
		$this->discontinue_time=date('Y-m-d h:i:s');
	}
  
	function find_by_SKU($sku) {
		$this->id=qone("SELECT id FROM `inventory` WHERE SKU='".qsafe($sku)."' LIMIT 1");
		if($this->id!=0) $this->load();
	}
  
	function find_by_name($name) {
		$this->id=qone("SELECT id FROM `inventory` WHERE name='".qsafe($name)."' LIMIT 1");
		if($this->id!=0) $this->load();
	}
}
?>