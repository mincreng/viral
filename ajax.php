<?php
    /**
     * @author Webfairy MediaT CMS - www.Webfairy.net
     */

    //define('AJAX_REQUEST', isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
    //if(!AJAX_REQUEST) {die('BAD_REQUEST');}

    include dirname(__FILE__).'/lib/engine.php';
        $Webfairy = new Webfairy();

    if (isset($_GET['c']) == true) {
        switch ($_GET['c']) {
            case 'delete_timeline_posts':
                $Webfairy->start(false);
                $id = (int) $_POST['id'];
                
                $result = array(
                    'deleted' => false
                );
                
                if(NoCSRF::check( 'wall_posts_'.$id, $_POST, true, 60*60, false )){
                    $Webfairy->delete_wall_post($id);
                    $result['deleted'] = true;
                }
                
                header("Content-type:application/json; charset=UTF-8");
                echo json_encode($result);
            break;
            case 'load_timeline_posts':
                $Webfairy->start();
                
                $Webfairy->loadClass('pagination', 'tools');
                
                $page = (isset($_GET['page']) == true) ? (int) $_GET['page'] : 1;
                $user_id = (int) $_GET['user_id'];
                
                $pagination = new pagination(
                    $Webfairy->db->wall_posts('user_id',$user_id),
                    10,
                    $page
                );

                $posts = $Webfairy->wall_posts_db_rows(
                     $pagination->rows()
                );                
                
                $result = array(
                  'success' => false,
                  'message' => 'no_data',
                  'data' => '',
                );

                if (count($posts) > 0) {
                    $Webfairy->smarty->assign('posts', $posts);

                    ob_start();
                    $Webfairy->smarty->display('string:{timeline_posts posts=$posts ajax=true}');
                    $data = ob_get_contents();
                    ob_end_clean();

                    $result['success'] = true;
                    $result['message'] = '';
                    $result['data'] = $data;
                }

                header("Content-type:application/json; charset=UTF-8");
                echo json_encode($result);

            break;
            
            case 'post_timeline':
                $Webfairy->start();
                
                if($Webfairy->isLoggedIn() == false){
                    exit;
                }

                $result = array(
                  'success' => false,
                  'message' => '',
                  'data' => '',
                );

                if($_POST) {
                    $data = array();
            		$type = $Webfairy->plaintext($_POST['shareType']);
            		$data['message'] = $Webfairy->plaintext($_POST['message']);
            		$data['type'] = $type;
                    
            		$data['location'] = ($type == 'location') ? $Webfairy->plaintext($_POST['location']) : '';
            		$data['lat'] = ($type == 'location') ? (float) $_POST['lat'] : ''; 
            		$data['lng'] = ($type == 'location') ? (float) $_POST['lng'] : ''; 
                    
            		$data['user_id'] = (int) $_GET['uid']; 
            		$data['createdby'] = $Webfairy->Userid(); 
            		$data['createdon'] = time();
                    
                    $data['video_url'] = ($type == 'videos') ? $_POST['videoUrl'] : '';
            		$data['image'] = ''; 
                    
                    if(empty($data['message']) == true){
                        $result['message'] = tr('what_on_mind');
                    }
                    
                    if (empty($_FILES['image']) == false) {
                        
                      require_once dirname(__FILE__).'/lib/Faultier/FileUpload/Autoloader.php';
                      Faultier\FileUpload\Autoloader::register();
                      
                      $max_file_size = 1024 * 1024 * (int) $Webfairy->getOption('max_file_size');            
            
                      $fileUploader = new Faultier\FileUpload\FileUpload($Webfairy->_uploaded(), array(
                        'size' => '<= '.$max_file_size,
                        'type' => '~ image'
                      ));  
                    
                      $fileUploader->error(function(Faultier\FileUpload\UploadError $error) use (&$result) {
                        $result['message'] = $error->getMessage();
                      });                    
                    
                      $filename = '';  
                      $fileUploader->save(function($file) use ($Webfairy,&$data) {
                        $file->setName($Webfairy->UploadedName($file->getOriginalName()));
                        $data['image'] = $file->getName();
                      }); 

                    }
                    if(empty($result['message']) == true){
                        if($inserted = $Webfairy->db->wall_posts()->insert($data)){
                            
                            $posts = $Webfairy->wall_posts_db_rows(
                                $Webfairy->db->wall_posts('id',$inserted['id'])
                            );                     
                            
                            $Webfairy->smarty->assign('posts', $posts);
        
                            ob_start();
                            $Webfairy->smarty->display('string:{timeline_posts posts=$posts ajax=true}');
                            $data = ob_get_contents();
                            ob_end_clean();
        
                            $result['success'] = true;
                            $result['message'] = '';
                            $result['data'] = $data;
                        }                        
                    }
        
                }

                header("Content-type:application/json; charset=UTF-8");
                echo json_encode($result);                    
                            
            break;
            
            case 'posts':
                if (isset($_POST['params']) == false) {
                    exit;
                }

                $Webfairy->start();

                $Webfairy->loadClass('pagination', 'tools');

                $page = (isset($_POST['page']) == true) ? (int) $_POST['page'] : 1;
                $style = (isset($_POST['style']) == true) ? $Webfairy->plaintext($_POST['style']) : 'a';
                $adevery = (isset($_POST['adevery']) == true) ? (int) $_POST['adevery'] : 4;

                $posts_per_page = $Webfairy->getOption('posts_per_page', 16);
                $posts_ad_repeat = $Webfairy->getOption('posts_ad_repeat', 2);
                $max_repeat_perpage = $posts_per_page / $adevery;

                $ad_repeat = $posts_ad_repeat - (($page - 1) * $max_repeat_perpage);

                $params = (array) $_POST['params'];
                $date_field = (array_key_exists('upcoming', $params['get']) == true) ? 'createdon' : 'publishedon';

                $posts_options = array('simple' => true);
                $nocache = false;
                
                if($style == 'c'){
                    $posts_options['author_row'] = true;
                }

                $db_posts = $Webfairy->db->posts();

                foreach ($params['get'] as $param_key => $param_value) {
                    switch ($param_key) {
                        case 'query':
                            $query = $Webfairy->plaintext($param_value, false);
                            if ($Webfairy->getLang() == 'ar') {
                                $Webfairy->loadClass('arabic', 'I18N');

                                $Arabic = new I18N_Arabic('Query');
                                $Arabic->setStrFields('title,description');
                                $Arabic->setMode(0);

                                $strCondition = $Arabic->getWhereCondition($query);
                            } else {
                                $strCondition = "title LIKE '%".implode("%' OR title LIKE '%", explode(' ', $query))."%'";
                                $strCondition .= " OR description LIKE '%".implode("%' OR description LIKE '%", explode(' ', $query))."%'";
                            }
                            $db_posts->where($strCondition);
                        break;
                        case 'catgory':
                            $db_posts->where('cat_id', array_map('intval', $param_value));
                        break;

                        case 'upcoming':
                            $db_posts->where(array('published' => 0, 'publishedon' => 0));
                            $posts_options['all'] = true;
                        break;

                        case 'userid':
                            $db_posts->where('createdby_id', (int) $param_value);
                            $posts_options['all'] = true;
                            $nocache = true;
                        break;
                        
                        case 'user_favorites':
                            $db_posts->where('id', $Webfairy->db->votes('vote',1)->where('user_id',(int) $param_value)->select('DISTINCT(`post_id`)'));
                        break;

                        case 'filter':
                            switch ($param_value) {
                                default :
                                case 'all':

                                break;

                                case 'last':
                                    $db_posts->where("YEARWEEK( FROM_UNIXTIME( {$date_field} ) , 1 ) = YEARWEEK( CURDATE( ) , 1 )");
                                break;

                                case 'watch':
                                    $db_posts->where('`lastview` BETWEEN (DATE_SUB(NOW(),INTERVAL 5 MINUTE)) AND NOW()');
                                break;
                            }
                        break;
                    }
                }

                switch ($params['sort']) {
                    default :
                    case 'date':
                        $db_posts->order($date_field.' DESC');
                    break;

                    case 'vote':
                        $db_posts->order('`votes` DESC');
                    break;

                    case 'comments':
                        $db_posts->order('`comments` DESC');
                    break;

                    case 'views':
                        $db_posts->order('`views` DESC');
                    break;
                }

                $pagination = new pagination(
                    $db_posts,
                    $posts_per_page,
                    $page
                );

                $posts = $Webfairy->post_db_rows(
                     $pagination->rows(),
                     $posts_options,
                     $nocache
                );

                $result = array(
                  'success' => false,
                  'message' => 'no_data',
                  'data' => '',
                );

                if (count($posts) > 0) {
                    $Webfairy->smarty->assign('posts', $posts);

                    ob_start();
                    $Webfairy->smarty->display('string:{posts_list posts=$posts style="'.$style.'" ad_repeat='.$ad_repeat.' ad_every='.$adevery.'}');
                    $data = ob_get_contents();
                    ob_end_clean();

                    $result['success'] = true;
                    $result['message'] = '';
                    $result['data'] = $data;
                }

                header("Content-type:application/json; charset=UTF-8");
                echo json_encode($result);
            break;

            case 'fileupload':
                $Webfairy->start(false);

                $params = $mime_pkgs = array();

                if ((empty($_GET['mime_pkgs']) == false)) {
                    $mime_pkgs = (array) explode(',', $_GET['mime_pkgs']);
                }

                if (isset($_GET['group']) == true) {
                    $params['group'] = (int) $_GET['group'];
                }

                if (isset($_GET['post_id']) == true) {
                    $params['post_id'] = (int) $_GET['post_id'];
                }

                $Webfairy->loadClass('uploadhandler', 'tools');

                $options = array(
                    'script_url' => $Webfairy->getOption('base_url').'/ajax.html?c=fileupload',
                    'upload_dir' => $Webfairy->_uploaded(),
                    'upload_url' => $Webfairy->_uploaded('url'),

                    'accept_file_types' => $Webfairy->get_extensions($mime_pkgs),

                    'max_file_size' => 1024 * 1024 * (int) $Webfairy->getOption('max_file_size'),
                    'readfile_chunk_size' => 1024 * 1024 * 2,

                    'max_width' => 2500,
                    'max_height' => 5000,
                    'min_width' => 0,
                    'min_height' => 0,

                    'params' => $params,
                );

                $upload_handler = new WebfairyUploadHandler($Webfairy, $options);

            break;

            case 'adviews':
                $Webfairy->start(false, false);

                if (count($_POST['ads']) > 0) {
                    $ads = array_map('intval', $_POST['ads']);
                    $ads = array_unique($ads);

                    $Webfairy->db->ads('id', $ads)->update(
                        array(
                            'views' => new NotORM_Literal("views+1"),
                        )
                    );
                }
                exit;
            break;

            case 'adclicks':
                $Webfairy->start(false, false);

                if (isset($_POST['id']) == true) {
                    $id = (int) $_POST['id'];
                    $Webfairy->db->ads('id', $id)->update(
                        array(
                            'clicks' => new NotORM_Literal("clicks+1"),
                        )
                    );
                }
            break;

            case 'vote':
                $Webfairy->start(false, true, true);

                $post_id = (int) $_POST['id'];

                $upvoted =  $_POST['up'];
                $downvoted = $_POST['down'];

                $vote = 0;

                if ($upvoted == 'true') {
                    $vote = 1;
                } elseif ($downvoted == 'true') {
                    $vote = -1;
                }

                $everyone_vote = (boolean) $Webfairy->getOption('everyone_vote', 0);

                if (($everyone_vote == true) || ($everyone_vote == false && $Webfairy->isLoggedIn() == true)) {
                    $user_id = $Webfairy->Userid();
                    $user_ip = $Webfairy->ip();

                    $where = ($Webfairy->isLoggedIn() == true) ? array('user_id' => $user_id) : array('user_ip' => $user_ip);

                    if ($vote_row = $Webfairy->db->votes(array('post_id' => $post_id))->where($where)->fetch()) {
                        $vote_row->update(
                            array(
                                'vote' => $vote,
                                'vote_time' =>  new NotORM_Literal("NOW()"),
                            )
                        );
                    } else {
                        $Webfairy->db->votes()->insert(
                            array(
                                'post_id' => $post_id,
                                'user_id' => $user_id,
                                'user_ip' => $user_ip,
                                'vote' => $vote,
                                'vote_time' =>  new NotORM_Literal("NOW()"),
                            )
                        );
                    }
                } else {
                    exit(tr('login_to_vote'));
                }

                $post_row = $Webfairy->post_db_row(
                    $Webfairy->db->posts('id', $post_id), array(
                        'all' => true,
                        'simple' => true,
                        'update_votes' => true,
                    ),
                    true
                );

                if ($Webfairy->getOption('publish_type', 1) == 2) {
                    $publish_votes = (int) $Webfairy->getOption('publish_votes', 5);
                    $pre_votes = ($vote == -1) ? $post_row['vote']['total'] + 1 : $post_row['vote']['total'] - 1;

                    $records = array();

                    if (($pre_votes == ($publish_votes - 1)) && $vote == 1) {
                        $records['published'] = 1;
                        $records['publishedon'] = $_SERVER["REQUEST_TIME"];
                    } elseif (($pre_votes == ($publish_votes + 1)) && $vote == -1) {
                        $records['published'] = 0;
                        $records['publishedon'] = 0;
                    }

                    if (count($records) > 0) {
                        $Webfairy->db->posts('id', $post_id)->update($records);
                    }
                }

            break;

            case 'file_embed':
                $Webfairy->start(false, false);

                $id = (int) $_GET['id'];
                if (($file = $Webfairy->db->files[$id]) == false) {
                    exit;
                }
                $pkg = $Webfairy->mime_to_pkg($file['file_mime_type']);
                switch ($pkg) {
                    case 'video':
                        $html = $Webfairy->video_object($file);
                    break;

                    case 'audio':
                        $html = $Webfairy->audio_object($file);
                    break;

                    case 'image':
                        $html = $Webfairy->image($file['file_physical_name'], '', true);
                    break;

                    case 'flash':
                        $html = $Webfairy->flash_object($Webfairy->_uploaded('url', $file['file_physical_name']));
                    break;
                }
                header("Content-type:text/plain; charset=UTF-8");
                echo $html;
            break;

            case 'get_post_permalink':
                $Webfairy->start(false, false);

                $name = $Webfairy->plaintext($_POST['name']);
                $post_id = (int) $_POST['post_id'];

                switch ((int) $Webfairy->getOption('permalink_type', 1)) {
                    case 1:
                        $post_name = $Webfairy->db->posts()->max('id') + 1;
                    break;

                    case 2:
                        if (empty($name) == true) {
                            exit;
                        }
                        $post_name = $Webfairy->post_name_gen($name, $post_id);
                    break;
                }

                exit(json_encode(
                    array(
                        'post_name' => $post_name,
                        'tiny_post_name' => $Webfairy->abridgeStr($post_name, 10),
                    )
                ));
            break;
        }
    }
