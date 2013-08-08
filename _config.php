<?php
//------------------------------------------------------------------------------
// Modify this section to customize your install 
//------------------------------------------------------------------------------
$t_start=microtime(true);
$domain_name=$_SERVER['SERVER_NAME'];

// pagination settings
$per_page=25;
$per_pagination=10;

// url
define('DOMAIN_NAME',$_SERVER['SERVER_NAME']);
define('SITE_URL',"http://$domain_name/VHS");
define('SITE_PATH',dirname(__FILE__));
define("BASE_PATH",getcwd()."/");
define('SITE_NAME',"VHS Admin");
define('LEGAL_NAME','Vancouver Hack Space');

define('PASSWORD_SALT','Q@#(RJasdfj8');

define('UPLOADS_PATH',SITE_PATH."/uploads");
define('UPLOADS_URL',SITE_URL."/uploads");

define('DEFAULT_CONTROLLER','welcome');

// database settings
$db_host='localhost';
$db_name='VHS';
$db_user='root';
$db_pass='root';
$db_prefix='vhs_';

// create a local_config.php to store different settings on the server
if(file_exists("_local_config.php")) {
  include "_local_config.php";
}


//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
// DO NOT EDIT AFTER THIS LINE UNLESS YOU KNOW WHAT YOU ARE DOING
//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
require_once "_db.php";
require_once "_table.php";



//------------------------------------------------------------------------------
// displays all the contents of $x
//------------------------------------------------------------------------------
function d($x) {
  echo "<pre>".print_r($x,1)."</pre>";
}


//------------------------------------------------------------------------------
// save a one-time message to be displayed the next time a page is rendered.
//------------------------------------------------------------------------------
function notice($msg,$type='alert') {
  $_SESSION['notices'][]=array('type'=>$type,'msg'=>date('Y-m-d H:i:s').": ".$msg);
}


//------------------------------------------------------------------------------
// save a one-time message to be displayed the next time a page is rendered.
// also stop rendering this page and redirect to another.
// NOT a good place to add trigger_error() or other things
//------------------------------------------------------------------------------
function error($msg,$redirect='') {
  notice($msg,'error');
  header("location: $redirect");
  die();
}


//------------------------------------------------------------------------------
// for debugging
//------------------------------------------------------------------------------
function myErrorHandler($errno, $errstr, $errfile, $errline) {
  echo "<div class='alert alert-error'>$errfile ($errline): [$errno] $errstr</div>\n";
  return true;
}


//------------------------------------------------------------------------------
// makes sure htmlentities suppports UTF8
//------------------------------------------------------------------------------
function html_encode($x) {
  return htmlentities($x,ENT_QUOTES,"UTF-8");
}


//------------------------------------------------------------------------------
// convert IP to country - http://www.geoplugin.com/
//------------------------------------------------------------------------------
function GetCountryFromIP($ip) {
  $data=unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip=$ip"));
  //echo "<pre>".print_r($data,1)."</pre>";
  $country=$data['geoplugin_countryName'];
  return $country;
}


//------------------------------------------------------------------------------
// Display the breadcrumbs
//------------------------------------------------------------------------------
function Breadcrumbs($crumbs) {
  $decorated='';
  if(count($crumbs)) {
    $last=array_pop($crumbs);
    foreach($crumbs as $k=>$v) {
      $decorated.="<li><a href='$k'>$v</a><span class='divider'>/</span></li>";
    }
    $decorated.="<li class='active'>".$last."</li>";
  }
  echo "<ul class='breadcrumb'>".$decorated."</ul>";
}



//------------------------------------------------------------------------------
// Display a back button
//------------------------------------------------------------------------------
function BackButton() {
  $f=parse_url($_SERVER['REQUEST_URI']);
  $file=$f['path'];
  $sq=empty($f['query'])?"":$f['query'];
  $sq=explode("&",$sq);
  array_pop($sq);
  $sq=implode("&",$sq);
  $sym=(strlen($sq)>0?"?".$sq:"");
  echo "<p><a href='$file$sym'>Back</a></p>";
}


//------------------------------------------------------------------------------
// Redirect the browser to another page
//------------------------------------------------------------------------------
function redirect($url,$num=0){
  static $http = array (
    100 => "HTTP/1.1 100 Continue",
    101 => "HTTP/1.1 101 Switching Protocols",
    200 => "HTTP/1.1 200 OK",
    201 => "HTTP/1.1 201 Created",
    202 => "HTTP/1.1 202 Accepted",
    203 => "HTTP/1.1 203 Non-Authoritative Information",
    204 => "HTTP/1.1 204 No Content",
    205 => "HTTP/1.1 205 Reset Content",
    206 => "HTTP/1.1 206 Partial Content",
    300 => "HTTP/1.1 300 Multiple Choices",
    301 => "HTTP/1.1 301 Moved Permanently",
    302 => "HTTP/1.1 302 Found",
    303 => "HTTP/1.1 303 See Other",
    304 => "HTTP/1.1 304 Not Modified",
    305 => "HTTP/1.1 305 Use Proxy",
    307 => "HTTP/1.1 307 Temporary Redirect",
    400 => "HTTP/1.1 400 Bad Request",
    401 => "HTTP/1.1 401 Unauthorized",
    402 => "HTTP/1.1 402 Payment Required",
    403 => "HTTP/1.1 403 Forbidden",
    404 => "HTTP/1.1 404 Not Found",
    405 => "HTTP/1.1 405 Method Not Allowed",
    406 => "HTTP/1.1 406 Not Acceptable",
    407 => "HTTP/1.1 407 Proxy Authentication Required",
    408 => "HTTP/1.1 408 Request Time-out",
    409 => "HTTP/1.1 409 Conflict",
    410 => "HTTP/1.1 410 Gone",
    411 => "HTTP/1.1 411 Length Required",
    412 => "HTTP/1.1 412 Precondition Failed",
    413 => "HTTP/1.1 413 Request Entity Too Large",
    414 => "HTTP/1.1 414 Request-URI Too Large",
    415 => "HTTP/1.1 415 Unsupported Media Type",
    416 => "HTTP/1.1 416 Requested range not satisfiable",
    417 => "HTTP/1.1 417 Expectation Failed",
    500 => "HTTP/1.1 500 Internal Server Error",
    501 => "HTTP/1.1 501 Not Implemented",
    502 => "HTTP/1.1 502 Bad Gateway",
    503 => "HTTP/1.1 503 Service Unavailable",
    504 => "HTTP/1.1 504 Gateway Time-out"
  );
  if(isset($http[$num])) header($http[$num]);
  header ("Location: $url");
  die();
}


//------------------------------------------------------------------------------
// http://911-need-code-help.blogspot.ca/2009/07/export-mysql-data-to-csv-using-php.html
function echocsv( $fields ) {
  $separator = '';
  foreach ( $fields as $field ) {
    if ( preg_match( '/\\r|\\n|,|"/', $field ) ) {
      $field = '"' . utf8_decode(str_replace( '"', '""', $field )) . '"';
    }
    echo $separator . $field;
    $separator = ',';
  }
  echo "\r\n";
}


//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
// Start
session_start();
ob_start();

// Set up error handling
ini_set('display_errors',"stdout");
error_reporting(E_ALL | E_STRICT);
set_error_handler("myErrorHandler");

qconnect();

//mysqli_set_charset("utf8");
//header('Content-type: text/html; charset=UTF-8') ;
?>
