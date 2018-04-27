<?php
    /**
     * @author Webfairy MediaT CMS - www.Webfairy.net
     */
    include dirname(__FILE__).'/lib/engine.php';
        $Webfairy = new Webfairy();
            $Webfairy->start();

    $prefix = $Webfairy->plaintext($_GET['prefix']);

    if (($catgory_arr = $Webfairy->getCatgoryByPrefix($prefix)) == false) {
        $Webfairy->redirect(
            tr('err_not_found'),
            $Webfairy->url('index.html')
        );
    }

    $row = $Webfairy->getCatgory($catgory_arr['id']);

    $Webfairy->loadClass('pagination', 'tools');

    $pagination = new pagination(
        $Webfairy->db->posts('cat_id', $row['deep'])->order('`publishedon` DESC'), $Webfairy->getOption('posts_per_page', 16));

    $posts = $Webfairy->post_db_rows(
         $pagination->rows(),
         array(
            'simple' => true,
         )
    );

    $posts_options = array(
        'page' => $pagination->get_page(),
        'itemsStyle' => $Webfairy->getOption('posts_list_style', 'a'),
        'params' => array(
            'get' => array(
                'catgory' => $row['deep'],
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

    $Webfairy->smarty->assign('row', $row);
    $Webfairy->smarty->assign('posts', $posts);
    $Webfairy->smarty->assign('pagination', $pagination->display());

    $Webfairy->display('catgory.tpl', array($row['title']));
