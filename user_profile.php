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
    
    $Webfairy->loadClass('pagination', 'tools');
    
    $page = (isset($_GET['page']) == true) ? (int) $_GET['page'] : 1;

    $pagination = new pagination(
        $Webfairy->db->wall_posts('user_id',$row['id']),
        10,
        $page
    );

    $posts = $Webfairy->wall_posts_db_rows(
         $pagination->rows()
    ); 

    $Webfairy->smarty->assign('row', $row);
    $Webfairy->smarty->assign('posts', $posts);
    $Webfairy->smarty->assign('pagination', $pagination->display());
    
    $Webfairy->html('<script src="http://maps.googleapis.com/maps/api/js?sensor=false&libraries=places"></script>','footer');
    
    $Webfairy->jsFile(array('js/jquery.form.js','js/jquery.geocomplete.js','js/timeline.js?'.uniqid()));

    $Webfairy->display('user_profile.tpl', array($row['displayName']));
