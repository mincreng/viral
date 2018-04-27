<?php
    /**
     * @author Webfairy MediaT CMS - www.Webfairy.net
     */
    include dirname(__FILE__).'/lib/engine.php';
        $Webfairy = new Webfairy();
            $Webfairy->start();

    $Webfairy->require_auth();

    $Webfairy->loadClass('formbuilder', 'tools');

    $mode = (isset($_GET['mode']) == true) ? $_GET['mode'] : 'simple';
    $id_arr = (is_array($_GET['id']) == true) ? array_map('intval', array_filter($_GET['id'])) : array((int) $_GET['id']);

    $save_arr = array();

    $cats = $Webfairy->catgories_select_tree();

    $Form = new FormBuilder('posts', tr('save'), tr('posts'), false, true);

    $i = 1;
    foreach ($id_arr as $id) {
        $db_row = $Webfairy->db->posts('id', $id);
        if (($row = $db_row->fetch()) == false) {
            continue;
        }

        if (isset($_GET['fresh']) == true) {
            $row['published'] = 1;
        }

        $post_row = $Webfairy->post_db_row(
            $Webfairy->db->posts('id', $id),
            array(
                'simple' => true,
                'all' => true,
            ),
            true
        );

        if ($post_row['manage'] == false) {
            continue;
        }

        $posttype = ($post_row['type'] > 0) ? $post_row['type'] : $Webfairy->mime_to_posttype($post_row['file']['file_mime_type']);

        $Form->addFieldSet(
            sprintf('%s #%s', $Webfairy->get_type_name($posttype), $i)
        );

        if ($mode == 'simple') {
            $Form->addField(array(
                    'id' => 'title_'.$row['id'],
                    'type' => 'textbox',
                    'label' => tr('title'),
                    'required' => true,
                    'maxlength' => 150,
                    'headerinjectioncheck' => 'full',
                    'defaultvalue' => $row['title'],
                )
            );

            $Form->addField(array(
                    'id' => 'cat_id_'.$row['id'],
                    'type' => 'select',
                    'label' => tr('category'),
                    'required' => true,
                    'optionlabels' => array_values($cats),
                    'optionvalues' => array_keys($cats),
                    'defaultvalue' => $row['cat_id'],
                )
            );

            $Form->addField(array(
                    'id' => 'thumb_id_'.$row['id'],
                    'type' => 'imagefile',
                    'label' => tr('preview_image'),
                    'defaultvalue' => (isset($row['thumb_id']) == true) ? $row['thumb_id'] : '',
                    'defaultimage' => (isset($post_row['thumb']) == true) ? $Webfairy->_uploaded('resized', 'thumb_'.$post_row['thumb']) : '',
                )
            );

            $Webfairy->media_tab_loader(
                array(

                )
            );

            $Form->addCollapse('advanced_'.$id, tr('advanced_options'));

            $Form->addField(array(
                    'id' => 'description_'.$row['id'],
                    'type' => 'textarea',
                    'label' => tr('description'),
                    'maxlength' => 2500,
                    'rows' => 2,
                    'defaultvalue' => $row['description'],
                )
            );

            $Form->addField(array(
                    'id' => 'post_source_'.$row['id'],
                    'type' => 'textbox',
                    'label' => tr('post_source'),
                    'maxlength' => 500,
                    'defaultvalue' => $post_row['post_source'],
                )
            );

            if ((int) $Webfairy->getOption('permalink_type', 1) == 2) {
                $Form->addField(array(
                        'id' => 'name_'.$row['id'],
                        'type' => 'textbox',
                        'label' => tr('permalink_name'),
                        'required' => true,
                        'defaultvalue' => $row['name'],
                    )
                );
            }

            if ($Webfairy->isManager() == true) {
                $Form->addField(
                    array(
                        'id' => 'published_'.$row['id'],
                        'type' => 'radio',
                        'label' => tr('published'),
                        'radiolabels' => array(tr('yes'), tr('no')),
                        'radiovalues' => array(1, 0),
                        'defaultvalue' => $row['published'],
                    )
                );
            }

            $Webfairy->html(
                $Webfairy->javascript_code(
                    '$(document).ready(function () {permalink_ajax();});'
                ),
                'footer'
            );
        } elseif ($mode == 'content' && isset($_GET['fresh']) == false) {
            $row['content'] = str_replace('&', '&amp;', $row['content']);
            $Form->addField(array(
                    'id' => 'content',
                    'type' => 'textarea',
                    'label' => tr('content'),
                    'rows' => 8,
                    'required' => true,
                    'toolbar' => true,
                    'defaultvalue' => $row['content'],
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
        }

        $i++;
        $save_arr[] = $id;
    }

    if (count($save_arr) == 0) {
        $Webfairy->go_to('?CAN_NOT_EDIT');
    }

    if ($Form->formSuccess()) {
        $isvalid = true;

        foreach ($save_arr as $id) {
            $db_row = $Webfairy->db->posts('id', $id);
            if (($row = $db_row->fetch()) == false) {
                continue;
            }
            if ($mode == 'simple') {
                $records = array(
                    'title' => $Webfairy->plaintext($Webfairy->getValueIfExists('title_'.$id,$_POST)),
                    'description' => $Webfairy->getValueIfExists('description_'.$id,$_POST),
                    'post_source' => $Webfairy->getValueIfExists('post_source_'.$id,$_POST),
                    'name' => $Webfairy->plaintext($Webfairy->getValueIfExists('name_'.$id,$_POST)),
                    'cat_id' => (int) $_POST['cat_id_'.$id],
                    'thumb_id' => (int) $_POST['thumb_id_'.$id],
                );

                if (isset($_POST['published_'.$id]) == true) {
                    $records['published'] = (boolean) $_POST['published_'.$id];
                }
            } elseif ($mode == 'content' && isset($_GET['fresh']) == false) {
                $records = array('content' => $_POST['content']);
            }

            foreach ($records as $record_key => $record_value) {
                if ($record_value == $row[$record_key]) {
                    unset($records[$record_key]);
                }
            }

            $Webfairy->update_db_post($id, $records);
        }
        if (isset($_GET['return']) == true) {
            $Webfairy->return_to();
        } else {
            $Webfairy->go_to('my_posts.html');
        }
    }

    $Webfairy->smarty->assign('Form', $Form->renderForm());

    $title = array(
        tr('edit_post'),
    );

     $Webfairy->display(array('edit.tpl', 'media-tab.tpl'), $title);
