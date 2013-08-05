<?php
require_once "model.php";

class Account_Address extends Model {
	static function find($account_id,$address_id) {
		return qone("SELECT id FROM `account_address` WHERE account_id='$account_id' AND address_id='$address_id' LIMIT 1");
	}
}
?>