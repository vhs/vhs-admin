<?php
require_once "controller.php";
require_once "model.inventory.php";

class Inventory_Controller extends Controller {
	function begin() {
		if(isset($_POST['add-now'])) {
			redirect(SITE_URL."/inventory/create");
		}
		Account_Controller::security_check('Inventory','List');
		// list all orders
		$all_orders=array();
		$r=q("SELECT id FROM `inventory` ORDER BY `SKU`");
		while($row=mysqli_fetch_row($r)) {
			$all_inventory[$row[0]]=new Inventory($row[0]);
		}
		require_once "view.header.php";
		require_once "view.inventory_list.php";
		require_once "view.footer.php";
	}

	function read() {
		Account_Controller::security_check('Inventory','Read');
		require_once "view.header.php";
		require_once "view.inventory_read.php";
		require_once "view.footer.php";
	}

	function BOM($id=0) {
		Account_Controller::security_check('Inventory','BOM');
		if($id==0) trigger_error("Inventory_Controller can't find that item.");
		$item=new Inventory($id);

		require_once "view.header_print.php";
		require_once "view.inventory_bom.php";
		require_once "view.footer_print.php";
	}

	function create() {
		$item=new Inventory();
		$item->load_description();
		if(isset($_POST['save-now'])) {
			$item->SKU=$item->get_unique_sku($_POST['SKU-category']);
			$item->save();
			redirect(SITE_URL."/inventory/update/".$item->id);
		}
		require_once "view.header.php";
		require_once "view.inventory_create.php";
		require_once "view.footer.php";
	}

	function update($id=0) {
		Account_Controller::security_check('Inventory','Update');
		$item=new Inventory($id);
		if(isset($_POST['save-now'])) {
			$item->get_post();
		}
    	$save_now=0;
		if(isset($_POST['add-child-now'])) {
			$item->add_child(qsafe($_POST['add-child-id']));
			notice("Child added.",'success');
      		$save_now=1;
		}
		if(isset($_POST['remove-child-now'])) {
			$item->remove_child(qsafe($_POST['remove-child-id']));
			notice("Child removed.",'success');
      		$save_now=1;
		}
		if(isset($_POST['publish-now'])) {
			$item->publish();
			notice($item->SKU." published.",'success');
			$save_now=1;
		}
		if(isset($_POST['discontinue-now'])) {
			$item->discontinue();
			notice($item->SKU." discontinued.",'success');
			$save_now=1;
		}
		if(isset($_POST['copy-now'])) {
			$destination = $item->copy();
			$destination->save();
			$did = $destination->id;
			$dsku = $destination->SKU;
			notice($item->SKU." copied to <a href='".SITE_URL."/inventory/update/$did'>$dsku</a>.",'success');
		}
    	if($save_now==1 || isset($_POST['save-now'])) {
			$item->save();
			notice($item->SKU." saved.",'success');
    	}
		if(isset($_POST['mutate-now'])) {
			$new_item=$item->evolve();
			redirect(SITE_URL."/inventory/update/".$new_item->id);
		}
		require_once "view.header.php";
		require_once "view.inventory_update.php";
		require_once "view.footer.php";
	}
}
?>