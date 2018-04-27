<?php
    /**
     * @author Webfairy MediaT CMS - www.Webfairy.net
     */
    include dirname(__FILE__).'/lib/engine.php';
        $Webfairy = new Webfairy();
            $Webfairy->start(true, true, false, array('ROOT', 'RECOVER'));

    if ($Webfairy->isLoggedIn() == true) {
        $Webfairy->return_to();
    }

    if (isset($_GET['hash']) == true && isset($_GET['email']) == true) {
        $hash = $Webfairy->plaintext($_GET['hash']);
        $email = $Webfairy->plaintext($_GET['email']);

        $user = $Webfairy->db->users('email', $email)->fetch();

        if ($user['recover_hash'] == $hash) {
            $password = substr(md5(uniqid(rand())), 0, 8);
            $salt = $Webfairy->createSalt();
            $hash = hash('sha256', $password);

            if ($user->update(array(
                'password' => hash('sha256', $salt.$hash),
                'salt' => $salt,
                'recover_hash' => '',
            ))) {
                $email_body = ftr(
                    'password_changed_msg',
                    array(
                        $password,
                        $user['username'],
                    )
                );

                $Webfairy->mail(
                    array('email' => $user['email'], 'name' => $user['username']),
                    tr('password'),
                    $email_body
                );

                $Webfairy->go_to('login.html?code=PASS_SENT');
            }
        } else {
            $Webfairy->redirect(
                tr('expired_link_msg'),
                $Webfairy->url('recover.html')
            );
        }
    }

    $Webfairy->loadClass('formbuilder', 'tools');

    $Form = new FormBuilder('recover', tr('send'), tr('recover'), true);

    $Form->addField(array(
            'id' => 'email',
            'type' => 'textbox',
            'label' =>  tr('email'),
            'required' => true,
            'validationtype' => 'email',
        )
    );

    if ($Form->formSuccess()) {
        $email = $Webfairy->plaintext($_POST['email']);

        if ($user = $Webfairy->db->users('email', $email)->fetch()) {
            $hash = substr(md5(uniqid(rand())), 0, 10);
            $user->update(
                array(
                    'recover_hash' => $hash,
                )
            );

            $email_body = ftr(
                'password_link_sent_msg',
                array(
                    $Webfairy->getOption('site_name'),
                    $Webfairy->absolute_url(
                        $Webfairy->url(
                            sprintf(
                                'recover.html?hash=%s&email=%s',
                                $hash,
                                $email
                            )
                        )
                    ),
                )
            );

            $Webfairy->mail(
                array('email' => $user['email'], 'name' => $user['username']),
                tr('recover'),
                $email_body
            );

            $Webfairy->go_to('login.html?code=RECOVER_SENT');
        } else {
            $Form->forceErrorMessage('email', 'email_not_exists');
        }
    }

    $Webfairy->smarty->assign('renderForm', $Form->renderForm());

    $options = array(
        'noindex' => true,
    );

    $Webfairy->display('recover.tpl', array(tr('recover')), $options);
