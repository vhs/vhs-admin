<?php
require_once "controller.account.php";
require_once "model.order.php";

class Order_Controller extends Controller {
	var $account_order;

	function __construct() {
		// get the customer account from the account controller
		$account_id=Account_Controller::get_id();
		// find the shopping cart (order=shopping) for the account
		$order_id=Order::find($account_id);
		$this->account_order=new Order($order_id);
		if($order_id==0) {
			// create a new empty order
			$this->account_order->customer_account_id=$account_id;
			$this->account_order->status='shopping';
		}
		$this->account_order->save();
	}

	function begin($id=0) {
		Account_Controller::security_check('Order','List');
		// list all orders
		$all_orders=array();
		$r=q("SELECT id FROM `order` WHERE total>0");
		while($row=mysqli_fetch_row($r)) {
			$all_orders[]=new Order($row[0]);
		}
		require_once "view.header.php";
		require_once "view.order_list.php";
		require_once "view.footer.php";
	}

	function bom($id=0) {
		Account_Controller::security_check('Order','BOM');

		if($id==0) $order = $this->account_order;
		else 	   $order = new Order($id);
		require_once "view.header_print.php";
		require_once "view.order_bom.php";
		require_once "view.footer_print.php";
	}

	function my_cart() {
		$this->checkout();
	}

  function checkout() {
		require_once "view.header.php";
		$this->update($this->account_order->id);
		require_once "view.footer.php";
  }
  
	function update($id=0) {
		Account_Controller::security_check('Order','Update');

		if($id==0) $order = $this->account_order;
		else 	   $order = new Order($id);

		$save_now=0;
		if(isset($_POST['save-now'])) {
			$order->get_post();
			notice("Order saved.",'success');
			$save_now=1;
		}
		if(isset($_POST['add-item-now'])) {
			$x = (isset($_POST['add-item-id'])) ? $_POST['add-item-id'] : $id;
			$order->add_item(qsafe($x));
			notice("Item added.",'success');
			$save_now=1;
		}
		if(isset($_POST['remove-item-now'])) {
			$order->remove_item(qsafe($_POST['remove-item-id']));
			notice("Item removed.",'success');
			$save_now=1;
		}
		if($save_now==1) {
			$order->save();
		}

		require_once "view.header.php";
		require_once "view.order_update.php";
		require_once "view.footer.php";
	}

	function change_address($type,$order_id=0) {
		Account_Controller::security_check('Order','Update');

		if($order_id==0) $order = $this->account_order;
		else 	         $order = new Order($order_id);

		if(!empty($_POST) && count($_POST)==1) {
			$new_id=array_keys($_POST);
			$new_id=$new_id[0];
			if($type=='billing') {
				$order->billing_address_id=$new_id;
				notice("Billing address updated.");
				$order->save();
			} else if($type=='shipping') {
				$order->shipping_address_id=$new_id;
				notice("Shipping address updated.");
				$order->save();
			} else trigger_error("Unknown address type?!");
			redirect(SITE_URL."/order/update/".$order_id);
		}
 
		$account = new Account($order->customer_account_id);
		$list=array();
		foreach($account->addresses as $aa) {
			$list[]=new Address($aa->address_id);
		}

		require_once "view.header.php";
		require_once "view.address_select.php";
		require_once "view.footer.php";
	}
}
?>