<?php
    /**
     * @author Webfairy MediaT CMS - www.Webfairy.net
     */
    include dirname(__FILE__).'/lib/engine.php';
        $Webfairy = new Webfairy();
            $Webfairy->start();

    $id = (int) $_GET['id'];
    
    if(($row = $Webfairy->FullUserData($id,array('votes' => true,'posts' => true,'cover' => true))) == false){
        $Webfairy->redirect(
            tr('err_not_found'),
            $Webfairy->url('/users/index.html')
        );        
    }

    $Webfairy->smarty->assign('row', $row);

    $uid = $row['id'];

    $Webfairy->loadClass('pagination', 'tools');

    $pagination = new pagination(
        $Webfairy->db->posts('id',
            $Webfairy->db->votes('vote',1)->where('user_id',$uid)->select('DISTINCT(`post_id`)')
        )->order('`id` DESC'),
        $Webfairy->getOption('posts_per_page', 16)
    );

    $posts = $Webfairy->post_db_rows(
         $pagination->rows(),
         array(
            'simple' => true,
            'all' => true,
         )
    );

    $posts_options = array(
        'page' => $pagination->get_page(),
        'itemsStyle' => 'b',
        'params' => array(
            'get' => array(
                'user_favorites' => $uid,
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

    $Webfairy->display('user_favorites.tpl', array($row['displayName']));
