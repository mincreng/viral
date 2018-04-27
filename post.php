<?php
    /**
     * @author Webfairy MediaT CMS - www.Webfairy.net
     */
    include dirname(__FILE__).'/lib/engine.php';
        $Webfairy = new Webfairy();
            $Webfairy->start();

    $Webfairy->require_auth();

    if (((boolean) $Webfairy->getOption('user_posting', 1) == false) && $Webfairy->isManager() == false) {
        $Webfairy->go_to('?USERS_CAN_NOT_POST');
    }

    $Webfairy->loadClass('formbuilder', 'tools');

    $title = array();
    $tpl = array('post.tpl');

    switch ($_GET['via']) {
        case 'upload':

            $_SESSION['tmp_files'] = array();

            if (isset($_POST['file_id']) == true && count($_POST['file_id']) > 0) {
                $id_arr = array();
                $merge_all = (isset($_POST['merge_all'])) ? (boolean) $_POST['merge_all'] : false;
                
                if($merge_all){
                    $records = $Webfairy->db_post_array(
                        array(
                            'files' => array_map('intval',$_POST['file_id']),
                        )
                    );

                    if ($inserted_row = $Webfairy->insert_post_to_db($records)) {
                        $id_arr[] = 'id[]='.$inserted_row['id'];
                    }                   
                }else{
                    foreach ($_POST['file_id'] as $file_id) {
                        if ($file_row = $Webfairy->db->files[$file_id]) {
                            $records = $Webfairy->db_post_array(
                                array(
                                    'file' => $file_row,
                                )
                            );
    
                            if ($inserted_row = $Webfairy->insert_post_to_db($records)) {
                                $id_arr[] = 'id[]='.$inserted_row['id'];
                            }
                        }
                    }                    
                }

                if (count($id_arr) > 0) {
                    $Webfairy->go_to('edit.php?fresh=true&'.implode('&', $id_arr));
                }
            }

            $Webfairy->jsLangFile(array('UPLOAD'));

            $Webfairy->jsFile(
                array(
                    'uploader/basic/handlebars.min.js',
                    'uploader/basic/jquery.ui.widget.js',
                    'uploader/basic/jquery.iframe-transport.js',
                    'uploader/basic/jquery.fileupload.js',
                    'uploader/basic/jquery.fileupload-process.js',
                    'uploader/basic/jquery.fileupload-validate.js',
                    'uploader/basic/jquery.fileupload-ui.js',
                    'uploader/basic/script.js',
                )
            )->cssFile(
                array(
                    'uploader/basic/defaultTheme.css',
                )
            );

            $title[] = tr('post_upload');
        break;

        case 'write':
            $Form = new FormBuilder('write', tr('save'), tr('add_post'), false, true);

            $Form->addField(array(
                    'id' => 'title',
                    'type' => 'textbox',
                    'label' => tr('title'),
                    'required' => true,
                    'maxlength' => 150,
                )
            );

            $Form->addField(array(
                    'id' => 'content',
                    'type' => 'textarea',
                    'label' => tr('content'),
                    'rows' => 8,
                    'required' => true,
                    'toolbar' => true,
                )
            );

            $Webfairy->jsFile('ckeditor/ckeditor.js');
            $Webfairy->jsFile('ckeditor/adapters/jquery.js');

            $ckeditor_options = array(
                'language' => $Webfairy->getLang(),
            );

            $Webfairy->html(
                $Webfairy->javascript_code(
                    '$(document).ready(function () {
                        $( "#content" ).ckeditor('.json_encode($ckeditor_options).');
                    });'
                ),
                'footer'
            );

            $Webfairy->media_tab_loader(
                array(
                    'mime_pkgs' => array('image', 'video', 'audio', 'flash'),
                    'buttons' => array(
                        array(
                            'class' => 'insert-ckeditor-html',
                            'text' => tr('insert'),
                            'attrs' => array(
                                'data-ckeditorid' => 'content',
                            ),
                        ),
                    ),
                )
            );

            if ($Form->formSuccess()) {
                $records = $Webfairy->db_post_array(
                    array(
                        'type' => 2,
                        'title' => $Webfairy->plaintext($_POST['title']),
                        'content' => $_POST['content'],
                    )
                );
                if ($inserted_row = $Webfairy->insert_post_to_db($records)) {
                    $Webfairy->go_to('edit.php?fresh=true&id[]='.$inserted_row['id']);
                }
            }

            $Webfairy->smarty->assign('Form', $Form->renderForm());

            $title[] = tr('post_write');
            $tpl[] = 'media-tab.tpl';
        break;

        case 'link':

            $Form = new FormBuilder('add_url', tr('save'), tr('add_post'), false, true);

            $Form->addField(array(
                    'id' => 'url',
                    'type' => 'textbox',
                    'label' => tr('url'),
                    'required' => true,
                    'maxlength' => 150,
                )
            );

            if ($Form->formSuccess()) {
                $url = $Webfairy->plaintext($_POST['url']);

                try {
                    $urlinfo_array = $Webfairy->urlinfo_array($url);

                    $records = $Webfairy->db_post_array($urlinfo_array);

                    if ($row = $Webfairy->insert_post_to_db($records)) {
                        $Webfairy->go_to('edit.php?fresh=true&id[]='.$row['id']);
                    }
                } catch (Exception $e) {
                    $isvalid = true;

                    try {
                        $url_html = $Webfairy->loadUrl($url);
                    } catch (Exception $e) {
                        $Form->forceErrorMessage('url', $e->getMessage());
                        $isvalid = false;
                    }

                    if ($isvalid == true) {
                        $records = array(
                                'type' => 8,
                                'link' => $url,
                            );

                        $DOM = $Webfairy->HTML_DOMXPath($url_html);
                        if ($title = $Webfairy->HTML_XPathNode($DOM, '//title', false)) {
                            $records['title'] = $title;
                        }

                        if ($row = $Webfairy->insert_post_to_db($Webfairy->db_post_array($records))) {
                            $Webfairy->go_to('edit.php?fresh=true&id[]='.$row['id']);
                        }
                    }
                }
            }

            $Webfairy->smarty->assign('Form', $Form->renderForm());

            $title[] = tr('post_link');
        break;
    }

    $Webfairy->display($tpl, $title);
