<?php
    /**
     * @author Webfairy MediaT CMS - www.Webfairy.net
     */
    include dirname(__FILE__).'/lib/engine.php';
        $Webfairy = new Webfairy();
            $Webfairy->start();

    $db_posts = $Webfairy->db->posts()->order('`id` DESC');

    $Webfairy->loadClass('pagination', 'tools');

    $pagination = new pagination(
        $db_posts,
        $Webfairy->getOption('posts_per_page', 16)
    );

    $posts = $Webfairy->post_db_rows(
         $pagination->rows(),
         array(
            'simple' => true,
         )
    );

    $itemsStyle = $Webfairy->getOption('posts_list_style', 'a');

    if (isset($_GET['view']) == true) {
        $views = array(1 => 'a',2 => 'b',3 => 'c');
        $itemsStyle = $views[(int) $_GET['view']];
    }

    $posts_options = array(
        'page' => $pagination->get_page(),
        'itemsStyle' => $itemsStyle,
        'params' => array(
            'get' => array(
                'filter' => 'all',
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

    $Webfairy->display('index.tpl', array($Webfairy->getOption('site_name')));
