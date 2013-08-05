<?php
require_once "model.php";


class Address extends Model {
  function __construct($id=0) {
    $this->created_on=date('Y-m-d h:i:s');
    $this->recipient='';
    $this->street='';
    $this->city='';
    $this->region='';
    $this->country='';
    $this->postal='';
    $this->phone='';
    parent::__construct($id);
  }

	// get list of countries
	
	// get list of regions in each country
	
	// look up country by ip?
  
  function delete() {
    $this->deleted='yes';
    $this->save();
  }
}
?>