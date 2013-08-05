<?php
require_once "model.php";
require_once "model.address.php";
require_once "model.account_address.php";


class Account extends Model {
	function __construct($id=0) {
		$this->addresses=array();
		parent::__construct($id);
	}

	function find($email) {
		return qone("SELECT id FROM `account` WHERE email='$email' LIMIT 1");
	}

	function load($id=0) {
		parent::load($id);
		$this->load_addresses();
	}


	function create_from_post() {
		$this->get_post();
		$this->password=md5(PASSWORD_SALT.post('pass'));
		$this->save();
	}


	/**
	 * uses $_POST data to figure out if your login is valid.
	 * @return 0 on fail
	 * @return 1 on success
	 */
    function login_from_post() {
		$email=post('email');
		$password=md5(PASSWORD_SALT.post('pass'));
		$query="SELECT id FROM `account` WHERE email='$email' AND password='$password' AND active='yes' LIMIT 1";
		$r=q($query);
		if(mysqli_num_rows($r)) {
			// success, load user details.
			$row=mysqli_fetch_assoc($r);
			$id=$row['id'];
			$this->load($id);
			return 1;
		} else {
			// failed
			return 0;
		}
    }


	// one account contains several addresses
	function load_addresses() {
		$this->addresses=array();
		$r=q("SELECT id FROM `account_address` WHERE account_id='".$this->id."' AND deleted='no'");
		while($row=mysqli_fetch_row($r)) {
			$this->addresses[]=new Account_Address($row[0]);
		}
	}


    function update_session_account() {
    	$_SESSION['account']=(array)$this;
    	d($_SESSION);
    }
  
	function add_address($item) {
		if($item=='') trigger_error('item cannot be empty');
		if(is_a($item,'Address')) $item=$item->id;

		foreach($this->addresses as $c=>$v) {
			if($v->address_id==$item) {
        trigger_error("Address already part of this account?!");
				return;
			}
		}
		// doesn't exist yet, create this combination
		$ic=new Inventory_Address;
		$ic->account_id=$this->id;
		$ic->address_id=$item;
		$ic->save();
		// add it
		$this->addresses[]=$ic;
	}


	function remove_address($item) {
		if($item=='') trigger_error('Item cannot be empty');
		if(is_a($item,'Address')) $item=$item->id;

		foreach($this->addresses as $c=>$v) {
			if($v->address_id==$item) {
				// adjust it and quit
				$this->addresses[$c]->delete();
        unset($this->addresses[$c]);
				return;
			}
		}
		trigger_error("Address doesn't exist?!");
	}
}
?>
