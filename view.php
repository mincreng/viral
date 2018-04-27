<?php
    /**
     * @author Webfairy MediaT CMS - www.Webfairy.net
     */
    include dirname(__FILE__).'/lib/engine.php';
        $Webfairy = new Webfairy();
            $Webfairy->start();

    if ($Webfairy->isNumber($_GET['id']) == true) {
        $w = array(
        'id' => (int) $_GET['id'],
      );
    } else {
        $w = array(
        'name' => $Webfairy->plaintext($_GET['id']),
      );
    }

    $db_row = $Webfairy->db->posts($w);
    $tpl = array('view.tpl');

    if (($post = $Webfairy->post_db_row($db_row, array('all' => true, 'author_row' => true, 'related' => true))) == false) {
        $Webfairy->redirect(
            tr('err_not_found'),
            $Webfairy->url('index.html')
        );
    }

    $db_row->fetch();

    $db_row->update(
        array(
            'views' => new NotORM_Literal("views+1"),
            'lastview' => new NotORM_Literal("CURRENT_TIMESTAMP()"),
        )
    );

    if ($post['count_comments'] == true) {
        $count = 0;
        switch ($Webfairy->getOption('comments_sys','facebook')){ 
            default :
        	case 'facebook':
                $count = $Webfairy->count_fb_comments($post['absolute_url']);
        	break;
        
        	case 'disqus':
                $count = $Webfairy->count_disqus_comments($post['absolute_url']);
        	break;
        }        
        
        $db_row->update(array(
            'comments' => (int) $count,
            'comments_update' => new NotORM_Literal("UNIX_TIMESTAMP()"),
        ));
    }

    if ($post['require_player'] == true) {
        $Webfairy
            ->cssFile('player/mediaelementplayer.min.css', 'header')
            ->jsFile('player/mediaelement-and-player.min.js', 'footer')
            ->html(
                $Webfairy->javascript_code("$('audio,video').mediaelementplayer();"), 'footer'
            );
    }

    if ($post['type'] == 2 && empty($post['provider']) == true) {
        $Webfairy
            ->cssFile('css/prism.css', 'header')
            ->jsFile('js/prism.js', 'footer');
    }

    if ($post['type'] == 9) {
        $Webfairy->cssFile('swiper/css/swiper.min.css')->jsFile('swiper/js/swiper.min.js');
        $Webfairy->html(
            $Webfairy->jQuery_code("var swiper = new Swiper('.swiper-container', {pagination: '.swiper-pagination',paginationClickable: true,nextButton: '.swiper-button-next',prevButton: '.swiper-button-prev',preloadImages: false,lazyLoading: true});")
            ,'footer'
        );	
    }
    
    if($post['manage']){
        $tpl[] = 'media-tab.tpl';
        $Webfairy->media_tab_loader(
            array(
                'post_id' => $post['id'],
                'mime_pkgs' => array('image'),
                'buttons' => array(),
            )
        );        
    }

    $Webfairy->smarty->assign('post', $post);

    $Webfairy->html($Webfairy->facebook_sdk(), 'footer');

    $description = $Webfairy->OneLine($Webfairy->plaintext((empty($post['description']) == false) ? $post['description'] : $post['title'], false));

    $property_meta = array(
        'og:url' => $Webfairy->absolute_url($post['url']),
        'og:description' => $description
    );
    
    if(empty($post['thumb']) == false){
        $property_meta['og:image'] = $Webfairy->absolute_url($Webfairy->image($post['thumb'],'medium'));
    }

    $options = array(
        'description' => $description,
        'keywords' => $Webfairy->keywords($post['title']),
        'property_meta' => $property_meta,
        'canonical' => $Webfairy->absolute_url($post['url']),
    );

    $Webfairy->display($tpl,$post['title'], $options);
