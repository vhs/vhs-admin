<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Marignally Clever Catalog</title>
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
      </div>
    </div>