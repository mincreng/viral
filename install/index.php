<?php
    include dirname(__FILE__).'/functions.php';
    include ROOT.'/lib/engine.php';

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?php echo Webfairy::NAME; ?> <?php echo Webfairy::VERSION; ?> - Installer</title>

    <link href="assets/style.css" rel="stylesheet">

  </head>

  <body>
    <div class="container">
      <div class="outter">
       <div class="form-install">
            <h1 class="form-install-heading">License Agreement</h1>
            <iframe src="license.html" class="license" frameborder="0" scrolling="auto"></iframe>
            <p><a class="btn" style="display: inline;" href="install.php">Install</a> or <a class="btn" style="display: inline;" href="upgrade.php">Upgrade</a></p>
        </div>
      </div>
    </div>
  </body>
  <p class="copyrights">  Copyright  <?php echo date("Y"); ?> &copy;  <a href="http://www.webfairy.net">WebFairy</a></p>

</html>
