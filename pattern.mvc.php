<?php
require_once "_config.php";
require_once "controller.account.php";

class MVC {
	function MVC() {
		global $no_login;

		// find out what class & action was called.
		// if the system is installed in a sub folder, make sure that doesn't confuse the results.
		$uri=$_SERVER['REQUEST_URI'];
		$self_path=dirname($_SERVER['PHP_SELF']);
		$uri_path=dirname($uri);
		$commands=substr($uri,strlen($self_path)+1);

		// defaults
		//$controller = qone("SELECT config_value FROM `config` WHERE config_value='start_controller' LIMIT 1");
		$controller = DEFAULT_CONTROLLER;
		$action = 'begin';
		$vars = explode('/',$commands);
		if(count($vars)) {
			$temp = array_shift($vars);
			if(trim($temp)!='') $controller=$temp;
		}
		$has_action=false;
		if(count($vars)) {
			$temp = array_shift($vars);
			if(trim($temp)!='' && substr($temp,0,1)!='_') {
				$action=$temp;
				$has_action=true;
			}
		}

		// now check if that class/action pair actually exists.
		$error='';
		if(!file_exists("controller.$controller.php")) {
			if($has_action===true) {
				array_unshift($vars,$action);
			}
			$action=$controller;
			$controller = DEFAULT_CONTROLLER;
		}

		require_once "controller.$controller.php";
		$controller_name=$controller."_Controller";
		$c=new $controller_name;
		if(method_exists($c,$action)) {
			call_user_func_array(array($c,$action),$vars);
		} else if(method_exists($c,'begin')) {
			// catch all exists
			array_unshift($vars,$action);
			call_user_func_array(array($c,'begin'),$vars);
		} else {
			// no catch all exists
			$error = "$controller_name doesn't know what '$action' means.";
		}

		if($error) {
			// this is a view
			notice($error,'error');
			require_once "view.header.php";
			require_once "view.footer.php";
		}
	}
}

new MVC();
?>