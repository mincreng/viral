<?php
    /**
     * @author Webfairy MediaT CMS - www.Webfairy.net
     */
    include dirname(__FILE__).'/lib/engine.php';
        $Webfairy = new Webfairy();
            $Webfairy->start();

    $Webfairy->loadClass('pagination', 'tools');

    $pagination = new pagination(
        $Webfairy->db->posts(array('published' => 0, 'publishedon' => 0))->order('`publishedon` DESC'), $Webfairy->getOption('posts_per_page', 16));

    $posts = $Webfairy->post_db_rows(
         $pagination->rows(),
         array(
            'simple' => true,
         )
    );

    $posts_options = array(
        'page' => $pagination->get_page(),
        'itemsStyle' => 'b',
        'params' => array(
            'get' => array(
                'upcoming' => 'true',
            ),
            'sort' => 'date',
        ),
    );

    $Webfairy->html(
        $Webfairy->javascript_code(
            sprintf(
                "$(document).ready(function() { $('ul#bricks-container').posts(%s); });",
                json_encode($posts_options)
            )
        ), 'footer');

    $Webfairy->smarty->assign('posts', $posts);
    $Webfairy->smarty->assign('pagination', $pagination->display());

    $Webfairy->display('upcoming.tpl', array(tr('upcoming')));
