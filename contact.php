<?php
    /**
     * @author Webfairy MediaT CMS - www.Webfairy.net
     */
    include dirname(__FILE__).'/lib/engine.php';
        $Webfairy = new Webfairy();
            $Webfairy->start();

    $Webfairy->loadClass('formbuilder', 'tools');

    $Form = new FormBuilder('contact', tr('send'), tr('reach_us'), true, true);

    $Form->addField(array(
            'id' => 'email',
            'type' => 'textbox',
            'label' => tr('email'),
            'required' => true,
            'validationtype' => 'email',
        )
    );

    $Form->addField(array(
            'id' => 'content',
            'type' => 'textarea',
            'minlength' => 15,
            'label' => tr('message'),
            'required' => true,
        )
    );

    if ($Form->formSuccess()) {
        $sender_data = array(
            'email' => $Webfairy->plaintext($_POST['email']),
            'ip' => $Webfairy->ip(),
            'user_agent' => $_SERVER["HTTP_USER_AGENT"],
        );

        $records = array(
            'content' => $Webfairy->plaintext($_POST['content']),
            'sender_data' => json_encode($sender_data),
            'msgtime' => time(),
        );

        foreach ($Webfairy->getManagers() as $user_id => $username) {
            $records['user_id'] = $user_id;
            $Webfairy->db->messages()->insert($records);
        }
        $Webfairy->redirect(
            tr('message_sent'),
            $Webfairy->url('index.html')
        );
    }

    $Webfairy->smarty->assign('renderForm', $Form->renderForm());

    $Webfairy->display('contact.tpl', array(tr('reach_us')));
