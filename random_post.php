<?php
    /**
     * @author Webfairy MediaT CMS - www.Webfairy.net
     */
    include dirname(__FILE__).'/lib/engine.php';
        $Webfairy = new Webfairy();
            $Webfairy->start(false, false);

    if ($row = $Webfairy->post_db_row(
        $Webfairy->db->posts()->order('RAND()'),
        array(
            'simple' => true,
        )
    )) {
        $Webfairy->go_to($row['url'], false);
    }

    $Webfairy->go_to('?NO_POSTS');
