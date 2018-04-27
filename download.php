<?php
    /**
     * @author Webfairy MediaT CMS - www.Webfairy.net
     */
    include dirname(__FILE__).'/lib/engine.php';
        $Webfairy = new Webfairy();
            $Webfairy->start(false, false);

    $file_id = (int) $_GET['id'];

    if (($token_data = $Webfairy->cache->get('file_download_'.$file_id)) == false) {
        exit('expired link');
    }

    if (isset($_GET["token"]) && preg_match('/^[0-9A-F]{40}$/i', $_GET["token"])) {
        if ($_GET["token"] != $token_data['token']) {
            exit('expired link');
        }
    } else {
        exit('Valid token not provided');
    }

    if (($row = $Webfairy->db->files[$file_id]) == false) {
        $Webfairy->redirect(
            tr('err_not_found'),
            $Webfairy->url('index.html')
        );
    }

   $file_path = $Webfairy->_uploaded('path', $row['file_physical_name']);

    header('X-Content-Type-Options: nosniff');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'.$row['file_real_name'].'"');
    header('Content-Length: '.$row['file_size']);
    header('Last-Modified: '.gmdate('D, d M Y H:i:s T', filemtime($file_path)));

    $Webfairy->readfile($file_path);
