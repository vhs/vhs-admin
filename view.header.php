<?php
//if(Account_Controller::is_allowed('Account','List')) {}
if( !isset($_SESSION['account']) || $_SESSION['account']['id']==0 ) {
  $links[] = array("url"=>SITE_URL."/account/login","name"=>"Login/Register");
} else {
  $links=array(
    array("url"=>SITE_URL."/#","name"=>"Admin",'submenu'=>array(
      array("url"=>SITE_URL."/account/list","name"=>"Accounts"),
      array("url"=>SITE_URL."/badge/list","name"=>"Badges"),
    )),
  );
  $links[]=array("url"=>SITE_URL."/#","name"=>"Me",'submenu'=>array(
    array("url"=>SITE_URL."/account/my_account","name"=>"My Account"),
    array("url"=>SITE_URL."/account/logout","name"=>"Logout"),
  ));
  if(isset($_SESSION['db_version'])) {
    $links[] = array("url"=>SITE_URL."/start/reset","name"=>"v".$_SESSION['db_version']);
  }
}
?><!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?php echo SITE_NAME; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="<?php echo SITE_URL;?>/bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="<?php echo SITE_URL;?>/bootstrap/css/datepicker.css" rel="stylesheet">
    <link href="<?php echo SITE_URL;?>/bootstrap/css/bootstrap-wysihtml5.css" rel="stylesheet">
    <link href="<?php echo SITE_URL;?>/bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
    <link href="<?php echo SITE_URL;?>/bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
    <link href="<?php echo SITE_URL;?>/style.css" rel="stylesheet" media='screen'>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="navbar navbar-static-top">
      <div class="container">
        <div>
          <a class="navbar-toggle navbar-inverse" type='button' data-toggle="collapse" data-target=".nav-responsive-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
         
          <a class="navbar-brand" href="<?php echo SITE_URL;?>/"><?php echo SITE_NAME;?></a>
          <?php
          if(isset($_SESSION['account']) && $_SESSION['account']['id']!=0) {
            echo "<p class='navbar-text pull-right'>Signed in as <a href='".SITE_URL."/account/my_account' class='navbar-link'>".$_SESSION['account']['name']."</a></p>";
	        }
          ?>
          <div class="nav-collapse collapse navbar-responsive-collapse">
            <ul class="nav navbar-nav">
            <?php
              $f=parse_url($_SERVER['REQUEST_URI']);
              $f=$f['path'];
              function nav_links($links,$f) {
                foreach($links as $k=>$v) {
                  if( isset($v['submenu']) && count($v['submenu']) ) {
                    echo "<li class='dropdown'><a class='dropdown-toggle' data-toggle='dropdown' href='".$v['url']."''>".$v['name']."<b class='caret'></b></a><ul class='dropdown-menu'>";
                    nav_links($v['submenu'],$f);
                    echo "</ul>";
                  } else {
                    $v2=parse_url($v['url']);

                    $a=($f==$v2['path'])? "active":"";
                    echo "<li class='$a'><a href='".$v['url']."''>".$v['name']."</a></li>";
                  }
                }
              }
              nav_links($links,$f);
            ?>
            </ul>
          </div>
        </div>
      </div>
    </div>
    
    <div class='container'>
      <div class='row-fluid'><div class='span12'>
<?php
if(isset($_SESSION['notices'])) {
  foreach($_SESSION['notices'] as $note) {
    echo "<div class='alert alert-".$note['type']."'>".$note['msg']."</div>";
  }
  $_SESSION['notices']=array();
}
?>
      </div></div>
