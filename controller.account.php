<?php
require_once "controller.php";
require_once "model.account.php";
require_once "model.account_address.php";


class Account_Controller extends Controller {
	function __construct() {
		parent::__construct();
		if(!isset($_SESSION['account']['id'])) {
			$_SESSION['account']['id']=0;
		}
	}
	
	function begin() {
		if(isset($_POST['add-now'])) {
			redirect(SITE_URL."/account/create");
		}
		Account_Controller::security_check('Account','List');

		$all_accounts=array();
		$r=q("SELECT id FROM `account` ORDER BY name");
		while($row=mysqli_fetch_row($r)) {
			$all_accounts[]=new Account($row[0]);
		}
		mysqli_free_result($r);

		require_once "view.header.php";
		require_once "view.account_list.php";
		require_once "view.footer.php";
	}


	function login() {
		$last_name='';
		$last_pass='';
		$last_email='';

		if(isset($_POST['login-now'])) {
			$this->_login_now();
		}

		if(isset($_POST['register-now'])) {
			// verify account details are all good.
			$last_name=post('name');
			$last_email=post('email');
			$last_pass=post('pass');
			$valid=1;

			$used_name=qone("SELECT id FROM `account` WHERE name='$last_name' LIMIT 1");
			if($used_name!=0) {
				$valid=0;
				notice('That name is already in use.  Please pick another.','warning');
				$last_name='';
			}
			if(strlen($last_email)<6) {
				$valid=0;
				notice('You must provide an email to log in.','warning');
			}
			if(strlen($last_pass)<6) {
				$valid=0;
				notice('Password must be 6 or more symbols long.','warning');
			}
			if(post('pass')!=post('pass2')) {
				$valid=0;
				notice('Passwords do not match.','warning');
			}
			if(!isset($_POST['agree_membership'])) {
				$valid=0;
				notice('Please make sure you read and understand the membership agreement.','warning');
			}
			if(!isset($_POST['agree_liability'])) {
				$valid=0;
				notice('Please make sure you read and understand the liability waiver.','warning');
			}

			if($valid==1) {
				// test passed.  Create account
				$user = new Account();
				$user->create_from_post();
				notice('Account created.  Welcome, '.$user->name.'!','success');
				// login
				$this->_login_now();
			}
		}

		require_once "view.header.php";
		require_once "view.account.login.php";
		require_once "view.footer.php";
	}


	function _login_now() {
		$user = new Account();
		if($user->login_from_post()==1) {
			// user has logged in OK.
	 		$user->update_session_account();

			if(!isset($_SESSION['login-redirect']) || $_SESSION['login-redirect']='SITE_URL."/account/login.php') {
				// @TODO: Get default on-login redirect setting?
				$_SESSION['login-redirect']=SITE_URL."/account/my_account";
			}
			$x=$_SESSION['login-redirect'];
			unset($_SESSION['login-redirect']);
			header("location: ".$x);
			die();
		}
	}

	function logout() {
		if(isset($_SESSION['account'])) unset($_SESSION['account']);
		if(isset($_SESSION['db_version'])) unset($_SESSION['db_version']);
		if(isset($_SESSION['tables'])) unset($_SESSION['tables']);
		require_once "view.header.php";
		require_once "view.account.logout.php";
		require_once "view.footer.php";
	}

	function my_account() {
		$this->update(Account_Controller::get_id());
	}

	function create() {
		Account_Controller::security_check('Account','Create');
		$user = new Account();
		$user->save();
		redirect(SITE_URL."/account/update/".$item->id);
	}


	function read() {
	}


	function update($id=0) {
		if($id==0 || $id!=Account_Controller::get_id()) {
			Account_Controller::security_check('Account','Update');
		}
		$user=new Account($id);
	    if(isset($_POST['save-now'])) {
	    	$user->get_post();
	    	$user->save();
	    	if($_SESSION['account']['id']==$id) {
	    		$user->update_session_account();
	    	}
	    	notice("Account saved.",'success');
	    }
		if(isset($_POST['add-address-now'])) {
			redirect(SITE_URL."/account/add_address/$id");
		}

		require_once "view.header.php";
		$user->load();
		require_once "view.my_account.php";
		require_once "view.footer.php";
	}


	function delete() {}


    function add_address($account_id=0) {
		Account_Controller::security_check('Address','Update');
    	if($account_id==0) $account_id=Account_Controller::get_id();
	    $address=new Address();
	    if(isset($_POST['save-now'])) {
	    	$address->get_post();
	    	$address->save();
	    	$aa=new Account_Address();
	    	$aa->address_id=$address->id;
	    	$aa->account_id=$account_id;
	    	$aa->save();
	    	notice("Address added.",'success');
	    	redirect(SITE_URL."/account/my_account/");
	    }
		require_once "view.header.php";
	    require_once "view.address_update.php";
		require_once "view.footer.php";
    }

    function edit_address($address_id) {
		Account_Controller::security_check('Address','Update');
	    $address=new Address($address_id);
    	if(isset($_POST['save-now'])) {
	    	$address->get_post();
	    	$address->save();
	    	notice("Address saved.",'success');
	    	redirect(SITE_URL."/account/my_account/");
	    }
		require_once "view.header.php";
	    require_once "view.address_update.php";
		require_once "view.footer.php";
    }

	static function get_id() {
		return $_SESSION['account']['id'];
	}

	static function is_allowed($class_name,$action_name) {
    // @TODO: finish the RBAC!
    if(!isset($_SESSION['account'])) return 0;
    if(!isset($_SESSION['account']['id'])) return 0;
    if($_SESSION['account']['id']==0) return 0;
    
		return 1;
	}

	static function security_check($class_name='',$action_name='') {
		if(!Account_Controller::is_allowed($class_name,$action_name)) {
		    $_SESSION['login-redirect']=$_SERVER['REQUEST_URI'];
		    redirect(SITE_URL."/account/login");
		}
	}
}
?>
