<?php
    /**
     * @author Webfairy MediaT CMS - www.Webfairy.net
     */
    include dirname(__FILE__).'/lib/engine.php';
        $Webfairy = new Webfairy();
            $Webfairy->start();

    $query = $Webfairy->plaintext($_GET['query'], false);

    if (empty($query) == true) {
        $Webfairy->redirect(
            tr('search_empty_query'),
            $Webfairy->url('index.html')
        );
    }

    $Webfairy->loadClass('pagination', 'tools');

    $strCondition = "title LIKE '%".implode("%' OR title LIKE '%", explode(' ', $query))."%'";
    $strCondition .= " OR description LIKE '%".implode("%' OR description LIKE '%", explode(' ', $query))."%'";
    
    $strOrderBy = '`publishedon` DESC';

    if ($Webfairy->getLang() == 'ar') {
        $Webfairy->loadClass('arabic', 'I18N');

        $Arabic = new I18N_Arabic('Query');

        $Arabic->setStrFields('title,description');
        $Arabic->setMode(0);

        $strCondition = $Arabic->getWhereCondition($query);
        $strOrderBy = $Arabic->getOrderBy($query);
    }

    $pagination = new pagination(
        $Webfairy->db->posts($strCondition)->order($strOrderBy), $Webfairy->getOption('posts_per_page', 16));

    $posts = $Webfairy->post_db_rows(
         $pagination->rows(),
         array(
            'simple' => true,
         )
    );

    $Webfairy->smarty->assign('query', $query);
    $Webfairy->smarty->assign('posts', $posts);
    $Webfairy->smarty->assign('pagination', $pagination->display());

    $posts_options = array(
        'page' => $pagination->get_page(),
        'itemsStyle' => $Webfairy->getOption('posts_list_style', 'a'),
        'params' => array(
            'get' => array(
                'filter' => 'all',
                'query' => $query,
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

    $Webfairy->display('search.tpl', array($Webfairy->getOption('site_name'), $query));
