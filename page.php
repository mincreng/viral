<?php
    /**
     * @author Webfairy MediaT CMS - www.Webfairy.net
     */
    include dirname(__FILE__).'/lib/engine.php';
        $Webfairy = new Webfairy();
            $Webfairy->start();

    $prefix = $Webfairy->plaintext($_GET['prefix']);

    if (($row = $Webfairy->db->pages('prefix', $Webfairy->seo_str($prefix))->where("type != 1")->fetch()) == false) {
        $Webfairy->redirect(
            tr('err_not_found'), $Webfairy->url('index.html')
        );
    }

    $options = json_decode($row['options'], true);

    if ((boolean) $options['require_auth'] == true) {
        $Webfairy->require_auth();
    }

    $Webfairy->smarty->assign('row', $row);

    $Webfairy->display('page.tpl', array($row['title']));
