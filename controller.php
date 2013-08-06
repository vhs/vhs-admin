<?php

class Controller {
	function __construct() {}

	function create() {		trigger_error(get_class($this)." does not implement create().");	}
	function read()   {		trigger_error(get_class($this)." does not implement read().");  	}
	function update() {		trigger_error(get_class($this)." does not implement update().");	}
	function delete() {		trigger_error(get_class($this)." does not implement delete().");	}
}
?>