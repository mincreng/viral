<?php
    /**
     * @author Webfairy MediaT CMS - www.Webfairy.net
     */
    include dirname(__FILE__).'/lib/engine.php';
        $Webfairy = new Webfairy();
            $Webfairy->start();

    $Webfairy->loadClass('pagination', 'tools');

    $pagination = new pagination(
        $Webfairy->db->users('status', 1)->order('`id` DESC'),
        20
    );

    $Webfairy->smarty->assign('rows',
        $Webfairy->UserDataRows($pagination->rows(),array('posts' => true))
     );
     
    $Webfairy->smarty->assign('pagination', $pagination->display());

    $Webfairy->display('users.tpl', array(tr('users')));
