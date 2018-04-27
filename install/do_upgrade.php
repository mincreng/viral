<?php

    include (dirname(__FILE__).'/functions.php');
    
    include ROOT.'/lib/engine.php';
        $Webfairy = new Webfairy();
            $Webfairy->start(false,false);

    $msgs = array();

    $db_prefix = $Webfairy->getDbInfo('prefix');

    $Queries = SplitSQL(dirname(__FILE__).'/schema.sql',array(
        'table_prefix' => $db_prefix,
        'charset_collate' => 'DEFAULT CHARACTER SET '.DB_CHARSET . ' COLLATE ' . DB_COLLATE
    ));
    
    foreach ($Queries as $Query) {
    	$Webfairy->db->query($Query);
    }

    foreach ($Webfairy->availableLangs() as $lang_key => $lang_title) {
        $Webfairy->db->query(
            addFieldIfNotExists($db_prefix.'langs',"`{$lang_key}`",'id',"mediumtext NOT NULL")
        );	
    }

    foreach (get_data_files() as $data_file) {
    	$data = include($data_file);
            switch ($data['name']){ 
            	case 'options':
            	case 'pages':
                case 'langs':
                    foreach ($data['data'] as $values) {
                        if(!$Webfairy->db->{$data['name']}($data['primary'],$values[$data['primary']])->fetch()){
                            if($Webfairy->db->{$data['name']}()->insert(stripDdFields($values))){
                                $msgs[] = sprintf('<li class="bullet-true">%s INSERTED INTO  %s</li>',$values[$data['primary']],$data['name']);
                            }
                        }
                    }                
            	break;
            }
    }    
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?php echo Webfairy::NAME; ?> <?php echo Webfairy::VERSION; ?> - UPGRADE</title>
    
    <link href="assets/strap.css" rel="stylesheet">
    <link href="assets/style.css" rel="stylesheet">

  </head>

  <body>
    <div class="container">
      <div class="outter">
       <div class="form-install">
            <h1 class="form-install-heading">UPGRADE</h1>
            <?php if(count($msgs) > 0){?>
                <ul>
                    <?php foreach($msgs as $msg){
                        echo $msg;
                    } ?>      
                </ul>
            <?php }?> 
            <p><strong>An Upgrade Has Been Successfully Applied</strong></p>           
        </div>

       
      </div>
    </div>
  </body>
      <p class="copyrights">  Copyright  <?php echo date("Y"); ?> &copy;  <a href="http://www.webfairy.net">WebFairy</a></p>
  
</html>