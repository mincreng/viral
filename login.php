<?php
    /**
     * @author Webfairy MediaT CMS - www.Webfairy.net
     */
    include dirname(__FILE__).'/lib/engine.php';
        $Webfairy = new Webfairy();
            $Webfairy->start();

    if ($Webfairy->isLoggedIn() == true) {
        $Webfairy->return_to();
    }

    $Webfairy->loadClass('formbuilder', 'tools');

    $Form = new FormBuilder('login', tr('login'), '', true, false);

    $msg = '';
    if (isset($_GET['code']) == true) {
        switch ($_GET['code']) {
            case 'RECOVER_SENT':
               $msg = $Webfairy->alert_html(tr('password_reset_emailed'), 'success');
            break;

            case 'PASS_SENT':
               $msg = $Webfairy->alert_html(tr('new_password_emailed'), 'success');
            break;

            case 'REQUIRE_LOGIN':
                $msg = $Webfairy->alert_html(tr('require_login'), 'danger');
            break;
            
            case 'HYBRID_ERR':
                $err = '';
        		switch( (int) $_GET['err'] ){ 
        			case 0 : $err = "Unspecified error."; break;
        			case 1 : $err = "Hybriauth configuration error."; break;
        			case 2 : $err = "Provider not properly configured."; break;
        			case 3 : $err = "Unknown or disabled provider."; break;
        			case 4 : $err = "Missing provider application credentials."; break;
        			case 5 : $err = "Authentication failed.The user has canceled the authentication or the provider refused the connection."; 
        			case 6 : $err = "User profile request failed. Most likely the user is not connected to the provider and he should to authenticate again."; break;
        			case 7 : $err = "User not connected to the provider.";break;
        		}            
                $msg = $Webfairy->alert_html($err, 'danger');
            break;

        }
    }
    $Webfairy->smarty->assign('msg', $msg);

    $Form->addField(array(
            'id' => 'email',
            'type' => 'textbox',
            'label' => tr('email'),
            'required' => true,
            'maxlength' => 100,
            'headerinjectioncheck' => 'full',
            'validationtype' => 'email',
        )
    );

    $Form->addField(array(
            'id' => 'password',
            'type' => 'password',
            'label' => tr('password'),
            'required' => true,
            'headerinjectioncheck' => 'full',
        )
    );
    
    $Form->addField(array(
            'id' => 'remember',
            'type' => 'radio',
            'inline' => true,
            'label' => tr('remember_me'),
            'instructions' => sprintf('<a href="%s"><small>%s</small></a>', $Webfairy->url('recover.html'), tr('forgot_password')),
            'radiolabels' => array(tr('yes'), tr('no')),
            'radiovalues' => array(1, 0),
            'defaultvalue' => 1,
        )
    );

    if ($Form->formSuccess()) {
        $email = $Webfairy->plaintext($_POST['email']);
        $password = $_POST['password'];
        $remember = (isset($_POST['remember']) == true) ? (boolean) $_POST['remember'] : false;

        if ($userData = $Webfairy->db->users('email', $email)->fetch()) {
            $hash = hash('sha256', $userData['salt'].hash('sha256', $password));
            if ($hash == $userData['password']) {
                $Webfairy->validateUser($userData['id'],$remember);
                $Webfairy->return_to();
            } else {
                $Form->forceErrorMessage('password', tr('incorrect_password'));
            }
        } else {
            $Form->forceErrorMessage('email', ftr('user_not_found', array($email)));
        }
    }

    $Webfairy->smarty->assign('renderForm', $Form->renderForm());

    $options = array(
        'noindex' => true,
    );

    $Webfairy->display('login.tpl', array(tr('login')), $options);
