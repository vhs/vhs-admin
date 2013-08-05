<?php
require_once "controller.php";

class Welcome_Controller extends Controller {
	function begin($category_name='',$product_name='') {
		require_once "view.header.php";
		require_once "view.front_page.php";
		require_once "view.footer.php";
	}
}
?>
