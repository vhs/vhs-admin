<?php
require_once "controller.php";
require_once "model.inventory.php";

class Start_Controller extends Controller {
	function begin() {
		Account_Controller::security_check('Start','Begin');
		require_once "view.header.php";
		qupdate();
		require_once "view.footer.php";
	}

	function reset() {
		Account_Controller::security_check('Start','Reset');
		require_once "view.header.php";
		unset($_SESSION['db_version']);
		$r=q("SHOW TABLES");
		while($row=mysqli_fetch_row($r)) {
			q("DROP TABLE `".$row[0]."`");
		}
		qupdate();
		require_once "view.footer.php";
	}
}
?>