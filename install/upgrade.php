<?php
    include dirname(__FILE__).'/functions.php';
    include ROOT.'/lib/engine.php';

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?php echo Webfairy::NAME; ?> <?php echo Webfairy::VERSION; ?> - Upgrader</title>

    <link href="assets/style.css" rel="stylesheet">

  </head>

  <body>
    <div class="container">
      <div class="outter">
       <div class="form-install">
            <h1 class="form-install-heading">Attention Please</h1>
            <iframe src="upgrade_info.html" class="license" frameborder="0" scrolling="auto"></iframe>
            <p><a class="btn" style="display: inline;" href="do_upgrade.php">Upgrade Now</a></p>
        </div>
      </div>
    </div>
  </body>
  <p class="copyrights">  Copyright  <?php echo date("Y"); ?> &copy;  <a href="http://www.webfairy.net">WebFairy</a></p>

</html>
