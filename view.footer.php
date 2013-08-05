<?php
global $t_start;
$t_end = microtime(true);
$t_diff = $t_end-$t_start;
?>
      <div class='row-fluid'>
        <div class='span12 footer'>
          <p>&copy; <?php echo LEGAL_NAME.' '.date('Y');?>.<br><?php echo date("Y-m-d H:i:s")." in ".$t_diff."s";?>.</p>
        </div>
      </div>

    </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="<?php echo SITE_URL;?>/bootstrap/js/bootstrap.min.js"></script>
    <!--<script src="<?php echo SITE_URL;?>/bootstrap/js/wysihtml5-0.3.0_rc3.js"></script>-->
    <!--<script src="<?php echo SITE_URL;?>/bootstrap/js/bootstrap-wysihtml5.js"></script>-->
<!--    <script type='text/javascript'>
    $('.datepicker').datepicker({'format' : 'yyyy-mm-dd'}).on('show', function(){
      var dp = $(this);
      if(dp.val() == '') {
        dp.val('<?php echo date('Y-m-d');?>').datepicker('update');
      }
    });
    //$('.textarea').wysihtml5();
    </script>-->
  </body>
</html>
<?php
ob_flush();
?>
