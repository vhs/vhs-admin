<?php
require_once "controller.php";
require_once "model.inventory.php";

class Start_Controller extends Controller {
	function begin() {
		Account_Controller::security_check('Start','Begin');
		require_once "view.header.php";
		echo "Statistics go here.";
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
		$this->__import_inventory();
		require_once "view.footer.php";
	}

	function __import_inventory() {
		$f=fopen("catalog.csv","rt");
		if($f) {
		  $keys=fgetcsv($f,1024); // eat first line
		  echo "<table><tr><th>".implode($keys,"</th><th>")."</th><th>Found</th></tr>";
		  while($line=fgetcsv($f,1024)) {
		  	$id=qone("SELECT id FROM inventory WHERE SKU='".$line[0]."' LIMIT 1");
		  	if($id>0) {
		  		$start="UPDATE `inventory` SET ";
		  		$end=" WHERE id='$id' LIMIT 1";
		  		$found=1;
		  	} else {
		  		$start="INSERT INTO `inventory` SET ";
		  		$end="";
		  		$found=0;
		  	}
		  	$insert='';
		  	$a='';
		  	for($i=0; $i<count($keys); ++$i) {
		  		$x=str_replace(array('$','#DIV/0!'),'',$line[$i]);
		  		$insert.=$a."`".qsafe($keys[$i])."`='".qsafe($x)."'";
		  		$a=", ";
		  	}
		  	$insert.=$a."`created_on`=NOW()";
		  	q($start.$insert.$end);
		    echo "<tr><td>".implode($line,"</td><td>")."</td><td>$found</td></tr>";
		  }
		  echo "</table>";
		  fclose($f);
		}
	}
}
?>