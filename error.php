<?php
    /**
     * @author Webfairy MediaT CMS - www.Webfairy.net
     */
    include dirname(__FILE__).'/lib/engine.php';
        $Webfairy = new Webfairy();
            $Webfairy->start();

    $options = array(
        'noindex' => true,
    );

    switch ((int) $_GET['code']) {
        case 404:
        default :
            $title = '404';
    }

    $Webfairy->display('error.tpl', array($Webfairy->getOption('site_name'), $title), $options);
