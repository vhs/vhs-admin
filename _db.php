<?php
//------------------------------------------------------------------------------
// database access
// © 2011 dan@marginallyclever.com
//------------------------------------------------------------------------------

/**
 * database input sanity checking
 */
function qsafe($string) {
  global $db_link;

  $string=trim($string);
  // If magic_quotes_gpc is enabled, first apply stripslashes() to the data. 
  // Using mysqli_real_escape_string function on data which has already been escaped 
  // will escape the data twice so we are reversing it first 
  if(get_magic_quotes_gpc()) {
    $string = stripslashes($string);
  } 
    
  // mysqli_real_escape_string escapes special chars such as quotes. 
  return mysqli_real_escape_string($db_link,$string);
}


function post($str) {
  if(isset($_POST[$str])) {
    return qsafe($_POST[$str]);
  }
  return null;
}


function get($str) {
  if(isset($_GET[$str])) {
    return qsafe($_GET[$str]);
  }
  return null;
}


function request($str) {
  if(isset($_REQUEST[$str])) {
    return qsafe($_REQUEST[$str]);
  }
  return null;
}


/**
 * Connect to DB
 */
function qconnect() {
  global $db_host, $db_user, $db_pass, $db_name,$db_link;
  
  $db_link=mysqli_connect($db_host,$db_user,$db_pass) or trigger_error(mysqli_error($db_link),E_USER_ERROR);
  mysqli_select_db($db_link,$db_name) or trigger_error(mysqli_error($db_link),E_USER_ERROR);
}


/**
 * performs a query and checks for errors
 */
function q($q,$debug=0) {
  global $db_prefix, $db_link;
  
  $q=preg_replace("/`(.*)`/","`".$db_prefix."$1`",$q);
  
  if($debug==1) echo "<div>$q</div>";
  
  $r=mysqli_query($db_link,$q) or trigger_error(mysqli_error($db_link)."<p><i>$q</i></p>",E_USER_ERROR);
  
  return $r;
}


/**
 * return the insert id of the last query
 */
function qinsertid() {
  global $db_link;
  return mysqli_insert_id($db_link);
}


/**
 * return a single value from a table
 */
function qone($query) {
  $r=q($query,0);
  $row=mysqli_fetch_row($r);
  mysqli_free_result($r);
  return $row[0];
}


/**
 * count the results of a query
 */
function qcount($query) {
  return qone("SELECT COUNT(*) FROM $query");
}


/**
 * Reset the cache (because the structure of an existing table has changed)
 */
function qcachereset() {
  unset($_SESSION['tables']);
}


/**
 * describe a table
 */
function qdescribe($query) {
  global $db_prefix;
  
  $q=preg_match("/`(.*)`/",$query,$matches);
  $table=$matches[1];

  if(isset($_SESSION['tables'][$table]['desc'])) {
    return $_SESSION['tables'][$table]['desc'];
  }
  
  $results=array();
  $r=q("DESCRIBE `$table`",0);
  while($row=mysqli_fetch_row($r)) {
    $results[$row[0]]=$row;
  }
  mysqli_free_result($r);
  
  $_SESSION['tables'][$table]['desc'] = $results;
  
  return $results;
}



/**
 * find the primary key of a table
 **/
function qkey($query) {
  global $db_prefix;
  
  $q=preg_match("/`(.*)`/",$query,$matches);
  $table=$matches[1];
  
  if(isset($_SESSION['tables'][$table]['key'])) {
    return $_SESSION['tables'][$table]['key'];
  }
  
  $results=array();
  $r=q("DESCRIBE `$table`",0);
  while($row=mysqli_fetch_row($r)) {
    if($row[3]=='PRI') {
      mysqli_free_result($r);

      $_SESSION['tables'][$table]['key']=$row[0];

      return $row[0];
    }
  }
  mysqli_free_result($r);

  $_SESSION['tables'][$table]['key']=null;

  return null;
}


/**
 * update the database if necessary
 */
function qupdate() {
  // only do this once per login or on special request
  if(isset($_SESSION['db_version'])) return;
  // what was the last patch applied?
  $last_version=intval(qone("SELECT `config_value` FROM `config` WHERE `config_key`='version' LIMIT 1"));
  //echo "last version = $last_version<br>";
  // look for newer patches in sequence

  while(file_exists("updates/$last_version.php")) {
    echo "<p>Parsing $last_version.php...</p>";
    require_once "updates/$last_version.php";
    ++$last_version;
    //echo "last version = $last_version<br>";
  }
  // save the version info for next time
  $_SESSION['db_version']=$last_version;
  q("UPDATE `config` SET `config_value`='$last_version' WHERE `config_key`='version' LIMIT 1");
}

?>
