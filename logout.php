<?php
    /**
     * @author Webfairy MediaT CMS - www.Webfairy.net
     */
    include dirname(__FILE__).'/lib/engine.php';
        $Webfairy = new Webfairy();
            $Webfairy->start();

    if ($Webfairy->isLoggedIn() == true) {
        $Webfairy->logout();
    }

    $Webfairy->return_to();
