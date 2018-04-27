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

    $msg = '';

    $Webfairy->loadClass('formbuilder', 'tools');

    $Form = new FormBuilder('register', tr('register'), tr('register'), true, false);

    $Form->addField(array(
            'id' => 'username',
            'type' => 'textbox',
            'label' => tr('username'),
            'required' => true,
            'maxlength' => 20,
            'headerinjectioncheck' => 'full',
        )
    );

    $Form->addField(array(
            'id' => 'email',
            'type' => 'textbox',
            'label' => tr('email'),
            'required' => true,
            'validationtype' => 'email',
        )
    );

    $Form->addField(array(
            'id' => 'password',
            'type' => 'password',
            'label' => tr('password'),
            'required' => true,
            'maxlength' => 50,
            'headerinjectioncheck' => 'full',
        )
    );

    $Form->addField(array(
            'id' => 'confirm',
            'type' => 'password',
            'label' => tr('password_confirm'),
            'required' => true,
            'maxlength' => 50,
            'headerinjectioncheck' => 'full',
        )
    );

    if ($Form->formSuccess()) {
        $isvalid = true;

        $username = $Webfairy->plaintext($_POST['username']);
        $email = $Webfairy->plaintext($_POST['email']);

        $records = array(
            'username' => $username,
            'email' => $email,
            'manager' => 0,
            'created' => new NotORM_Literal("CURRENT_TIMESTAMP()")
        );

        if ($Webfairy->db->users("username", $username)->fetch()) {
            $Form->forceErrorMessage('username', ftr('err_already_exists', array($username)));
            $isvalid = false;
        }

        if ($Webfairy->db->users("email", $email)->fetch()) {
            $Form->forceErrorMessage('email', ftr('err_already_exists', array($email)));
            $isvalid = false;
        }

        $password = $_POST['password'];
        $confirm = $_POST['confirm'];

        if ($password == $confirm) {
            $salt = $Webfairy->createSalt();
            $hash = hash('sha256', $password);

            $records['password'] = hash('sha256', $salt.$hash);
            $records['salt'] = $salt;
        } else {
            $isvalid = false;
            $Form->forceErrorMessage('confirm', tr('password_not_match'));
        }

        if ($isvalid == true) {
            if ($row = $Webfairy->db->users()->insert($records)) {
                $Webfairy->validateUser($row['id']);
                $Webfairy->return_to();
            }
        }
    }

    $renderForm = $Form->renderForm();

    if((boolean) $Webfairy->getOption('registration',true) == false){
        $msg = $Webfairy->alert_html(tr('registration_closed'), 'info');
        $renderForm = '';
    }

    $Webfairy->smarty->assign('renderForm', $renderForm);
    $Webfairy->smarty->assign('msg', $msg);
    
    $options = array(
        'noindex' => true,
    );

    $Webfairy->display('register.tpl', array(tr('register')), $options);
