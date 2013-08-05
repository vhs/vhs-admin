<?php

class Model {
	var $id;

	function __construct($id=0) {
		$this->id=$id;
		$this->load();
	}

	function load($id=0) {
		if($id!=0) $this->id=$id;
		if($this->id==0) return;

		$class_name=get_class($this);
		$table=strtolower($class_name);
		$r=q("SELECT * FROM `$table` WHERE id='".qsafe($this->id)."' LIMIT 1");
		if(mysqli_num_rows($r)==1) {
			$row=mysqli_fetch_assoc($r);
			foreach($row as $k=>$v) {
				$this->$k=$v;
			}
			//d($this);
		} else {
			trigger_error("$class_name can't find ".intval($this->id).".");
		}
	}	

	function load_description() {
		$class_name=get_class($this);
		$table=strtolower($class_name);
		$r=q("DESCRIBE `$table`");
		if(mysqli_num_rows($r)>0) {
			while($row=mysqli_fetch_assoc($r)) {
				$field=$row['Field'];
				$this->$field=$row['Default'];			
			}
		} else {
			trigger_error("$class_name can't be described?!");
		}
	}

	function save() {
		$class_name=get_class($this);
		$table=strtolower($class_name);
    	$fields=array_keys(qdescribe("`$table`"));

    	if(!isset($this->created_on)) {
    		$this->created_on=date("Y-m-d h:i:s");
    	}

		if($this->id==0) {
			$start="INSERT INTO `$table` SET ";
			$end  ="";
			unset($this->id);
		} else {
			$start="UPDATE `$table` SET ";
			$end  =" WHERE id='".qsafe($this->id)."' LIMIT 1";
		}

		$divider='';
		$input='';
		foreach($fields as $k) {
			if(isset($this->$k)) {
				$input.=$divider."`$k`='".qsafe($this->$k)."'";
				$divider=", ";
			}
		}
		$query=$start.$input.$end;
		//d($query);
		q($query);
		if(!isset($this->id)) $this->id=qinsertid();
	}	


	function get_post() {
		$class_name=get_class($this);
		$table=strtolower($class_name);
    	$fields=array_keys(qdescribe("`$table`"));

		foreach($fields as $f) {
			if(isset($_POST[$f])) {
				$this->$f = $_POST[$f];
			}
		}
		//d($this);
	}
}
?>