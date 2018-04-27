<?php

    include dirname(__FILE__).'/functions.php';
    include ROOT.'/lib/engine.php';

    include ROOT.'/lib/tools/translator.php';

    Translator::init(
        array(
            'form_intro' => 'Fields marked %s are required.',
            'err_processing_form' => 'Error processing form!',
            'form_textbox_req' => 'The field %s is required',
            'form_textbox_len' => 'The field %s should be (%s) long',
            'form_textbox_lenmin' => 'The field %s should be at least %s characters',
            'form_textbox_lenmax' => "The field %s can't exceed %s characters",
            'form_selectbox_not' => 'Select a valid option for the field %s',
            'form_numletcheck' => 'Type only numbers or letters for the field %s',
            'form_numberscheck' => 'Type only numbers for the field %s',
            'form_letterscheck' => 'Type only letters for the field %s',
            'form_emailcheck' => 'Type a valid e-mail address for the field %s',
        )
    );

    $randomPassword = randomPassword();

    $errors = array();

    webfairy::loadClass('formbuilder', 'tools');

    $Form = new FormBuilder('install', 'Next', 'General Configuration', false, false);

    $Form->NoCSRF = false;

    $Form->addFieldSet('MySQL database configuration');

    $Form->addField(array(
            'id' => 'database_server',
            'type' => 'textbox',
            'label' => 'MySQL Hostname',
            'required' => true,
            'defaultvalue' => 'localhost',
        )
    );

    $Form->addField(array(
            'id' => 'database_user',
            'type' => 'textbox',
            'label' => 'MySQL User Name',
            'required' => true,
        )
    );

    $Form->addField(array(
            'id' => 'database_password',
            'type' => 'textbox',
            'label' => 'MySQL Password',
        )
    );

    $Form->addField(array(
            'id' => 'database_name',
            'type' => 'textbox',
            'label' => 'MySQL Database Name',
            'required' => true,
        )
    );

    $Form->addField(array(
            'id' => 'table_prefix',
            'type' => 'textbox',
            'label' => 'MySQL Prefix',
            'required' => true,
            'defaultvalue' => 'webfairy_',
        )
    );

    $Form->addFieldSet('Common configuration');

    $Form->addField(array(
            'id' => 'site_name',
            'type' => 'textbox',
            'label' => 'Site Name',
            'required' => true,
            'maxlength' => 50,
            'defaultvalue' => webfairy::NAME,
        )
    );

    $Form->addField(array(
            'id' => 'site_email',
            'type' => 'textbox',
            'label' => 'Site Email',
            'required' => true,
            'validationtype' => 'email',
        )
    );

    $Form->addField(array(
            'id' => 'install_data',
            'type' => 'radio',
            'label' => 'Install sample data',
            'radiolabels' => array('Yes', 'No'),
            'radiovalues' => array(1, 0),
            'defaultvalue' => 1,
        )
    );

    $Form->addFieldSet('Administrator configuration');

    $Form->addField(array(
            'id' => 'username',
            'type' => 'textbox',
            'label' => 'Admin Username',
            'required' => true,
            'maxlength' => 30,
            'headerinjectioncheck' => 'full',
        )
    );

    $Form->addField(array(
            'id' => 'email',
            'type' => 'textbox',
            'label' => 'Admin E-mail',
            'required' => true,
            'validationtype' => 'email',
        )
    );

    $Form->addField(array(
            'id' => 'password',
            'type' => 'password',
            'label' => 'Admin Password',
            'required' => true,
            'maxlength' => 50,
            'headerinjectioncheck' => 'full',
        )
    );

    $Form->addField(array(
            'id' => 'confirm',
            'type' => 'password',
            'label' => 'Admin Password[confirm]',
            'required' => true,
            'maxlength' => 50,
            'headerinjectioncheck' => 'full',
        )
    );

    if ($Form->formSuccess()) {
        $isvalid = true;

        $db_server = webfairy::plaintext($_POST['database_server']);
        $db_user = webfairy::plaintext($_POST['database_user']);
        $db_name = webfairy::plaintext($_POST['database_name']);
        $db_password = $_POST['database_password'];
        $table_prefix = webfairy::plaintext($_POST['table_prefix']);

        if ($db = mysql_connect($db_server, $db_user, $db_password)) {
            if (function_exists('mysql_set_charset') == true) {
                mysql_set_charset('utf8');
            }
            if (mysql_select_db($db_name, $db) == false) {
                $isvalid = false;
                $Form->forceErrorMessage('database_name', "Could not select database <strong>{$database_name}</strong>");
            }
        } else {
            $isvalid = false;
            $Form->forceErrorMessage('database_server', 'Could not connect to MySQL server');
        }

        if (isset($_POST['password']) == true && empty($_POST['password']) == false) {
            $admin_username = webfairy::plaintext($_POST['username']);
            $admin_email = webfairy::plaintext($_POST['email']);
            $password = $_POST['password'];
            $confirm = $_POST['confirm'];

            if ($password == $confirm) {
                $salt = webfairy::createSalt();
                $hash = hash('sha256', $password);

                $admin_password = hash('sha256', $salt.$hash);
                $admin_salt = $salt;
            } else {
                $isvalid = false;
                $Form->forceErrorMessage('confirm', "Entered passwords do not match");
            }
        }

        if ($isvalid == true) {
            if (writeConfig(array(
                'database_dsn' => dsn($db_server, $db_name),
                'table_prefix' => $table_prefix,
                'database_user' => $db_user,
                'database_password' => $db_password,
                'db_charset' => DB_CHARSET,
                'secret_key' => webfairy::RandomToken(6),
                'db_collate' => DB_COLLATE,
                'web_path' => ROOT.'/',
                'web_url' => WEBURL,
                'date' => date(DATE_RSS),
            ))) {
                $Queries = SplitSQL(dirname(__FILE__).'/schema.sql', array(
                    'table_prefix' => $table_prefix,
                    'charset_collate' => 'DEFAULT CHARACTER SET '.DB_CHARSET.' COLLATE '.DB_COLLATE,
                ));

                foreach ($Queries as $Query) {
                    mysql_query($Query);
                }

                foreach (get_data_files() as $data_file) {
                    $data = include $data_file;
                    dbRowInsert($table_prefix.$data['name'], $data['data']);
                }

                $webfairy = new webfairy();

                $webfairy->updateOption('site_name', webfairy::plaintext($_POST['site_name']));
                $webfairy->updateOption('site_email', webfairy::plaintext($_POST['site_email']));

                if ((boolean) $_POST['install_data'] == true) {
                    include dirname(__FILE__).'/sampledata.php';
                }

                $webfairy->db->users()->insert(array(
                    'manager' => 1,
                    'username' => $admin_username,
                    'email' => $admin_email,
                    'password' => $admin_password,
                    'salt' => $admin_salt,
                    'created' => new NotORM_Literal("CURRENT_TIMESTAMP()")
                ));

                header('location: ?done=true');
            }
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?php echo webfairy::NAME; ?> <?php echo webfairy::VERSION; ?> - Installer</title>

    <link href="assets/style.css" rel="stylesheet">

  </head>

  <body>
    <div class="container">
      <div class="outter">
       <div class="form-install">
            <?php if (isset($_GET['done']) == true) {
    ?>
                <h1 class="form-install-heading">Installation completed</h1>
                <p><strong>Now you MUST completely remove 'install' directory from your server</strong></p>
                <p>Please for security reasons chmod your /lib/ directory to 0755.</p>
            <?php 
} else {
    ?>
               <form method="post">
                    <h1 class="form-install-heading"><?php echo webfairy::NAME;
    ?> <?php echo webfairy::VERSION;
    ?> - Installer</h1>
                    <fieldset>
                    	<legend>Pre-installation check</legend>
                        <ul>
                            <?php
                                $check = array();

    if (function_exists("gd_info") && extension_loaded('gd')) {
        echo '<li class="bullet-true"><strong>(GD)</strong> Available</li>';
    } else {
        echo '<li class="bullet-false"><strong>(GD)</strong> Required</li>';
        $check[] = true;
    }

    $configFilePath = ROOT.'/lib/'.CONFIG_KEY;
    if (file_exists($configFilePath) == false) {
        $hnd = fopen($configFilePath, 'w');
        fwrite($hnd, '<?php // www.webfairy.com ?>');
        fclose($hnd);
    }

    if (@ini_get('allow_url_fopen') == false) {
        echo '<li class="bullet-false"><strong>allow_url_fopen</strong> Not Available</li>';
    }

    if (is_writable($configFilePath)) {
        echo '<li class="bullet-true"><strong>('.CONFIG_KEY.')</strong> Writeable</li>';
    } else {
        echo '<li class="bullet-false"><strong>('.CONFIG_KEY.')</strong> Unwriteable</li>';
        $check[] = true;
    }

    $paths = array(
                                    '/resized/',
                                    '/cache/',
                                    '/uploads/',
                                );

    foreach ($paths as $path) {
        if (is_writable_deep(ROOT.$path)) {
            echo '<li class="bullet-true"><strong>(['.$path.'])</strong> Writeable</li>';
        } else {
            echo '<li class="bullet-false"><strong>(['.$path.'])</strong> Unwriteable</li>';
            $check[] = true;
        }
    }
    ?>
                        </ul>
                    </fieldset>
                    </form>
                    <?php if (count($check) > 0) {
    ?>
                        <p class="error"><strong>Can't be installed because your host doesnt meet the minimum requirements</strong></p>
                    <?php 
} else {
    ?>
                    <?php echo $Form->renderForm();
    ?>
                    <?php 
}
    ?>


            <?php 
}?>
        </div>


      </div>
    </div>
  </body>
      <p class="copyrights">  Copyright  <?php echo date("Y"); ?> &copy;  <a href="http://www.webfairy.net">WebFairy</a></p>

</html>
