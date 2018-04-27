<?php
    /**
     * @author Webfairy MediaT CMS - www.Webfairy.net
     */

    include dirname(__FILE__).'/lib/engine.php';
        $Webfairy = new Webfairy();
            $Webfairy->start(true, true, true, array('ROOT', 'PANEL'));

    $Webfairy->require_auth();

    if ($Webfairy->isManager() == false) {
        $Webfairy->return_to();
    }

    $Webfairy->jsFile(
        array(
            'js/dialog.min.js',
            'js/panel.js',
        )
    );

    $display_options = array();

    function adTitle($size)
    {
        return ftr('ad_title', array($size[0], $size[1]));
    }

    function caseUrl($c)
    {
        return 'panel.html?case='.$c;
    }

    function icon($i)
    {
        return sprintf('<i class="fa fa-%s"></i>', $i);
    }

    $Webfairy->loadClass('formbuilder', 'tools');
    $Webfairy->loadClass('navbar', 'tools');
    $Webfairy->loadClass('Arabic', 'I18N');

    $panel_nav = array(
        array(
            'title' => tr('overview'),
            'icon' => icon('home'),
            'url' => caseUrl('overview'),
        ),
        array(
            'title' => tr('content'),
            'icon' => icon('cubes'),
            'url' => '#',
            'children' => array(
                array(
                    'title' => tr('posts'),
                    'icon' => icon('book'),
                    'url' => caseUrl('posts'),
                ),
                array(
                    'title' => tr('cats'),
                    'icon' => icon('th-large'),
                    'url' => caseUrl('cats'),
                ),
                array(
                    'title' => tr('post_fetcher'),
                    'icon' => icon('refresh'),
                    'url' => caseUrl('fetcher'),
                ),
            ),
        ),
        array(
            'title' => tr('pages'),
            'icon' => icon('files-o'),
            'url' => caseUrl('pages'),
        ),
        array(
            'title' => tr('ads'),
            'icon' => icon('dollar'),
            'url' => caseUrl('ads'),
        ),
        array(
            'title' => tr('users'),
            'icon' => icon('users'),
            'url' => caseUrl('users'),
        ),
        array(
            'title' => ftr('inbox', array($Webfairy->getUserData('unReadMessages'))),
            'icon' => icon('inbox'),
            'url' => caseUrl('messages'),
        ),
        array(
            'title' => tr('themes'),
            'icon' => icon('magic'),
            'url' => caseUrl('#'),
            'children' => array(
                array(
                    'title' => tr('navbars'),
                    'icon' => icon('navicon'),
                    'url' => caseUrl('navbars'),
                ),
                array(
                    'title' => tr('langs'),
                    'icon' => icon('globe'),
                    'url' => caseUrl('langs'),
                ),
                array(
                    'title' => tr('translation'),
                    'icon' => icon('pencil'),
                    'url' => caseUrl('translate'),
                ),
            ),
        ),
        array(
            'title' => tr('settings'),
            'icon' => icon('cog'),
            'url' => caseUrl('options'),
            'children' => array(
                array('title' => tr('siteinfo'),'url' => caseUrl('options&o=info'),'icon' => icon('cube')),
                array('title' => tr('themes'),'url' => caseUrl('options&o=themes'),'icon' => icon('magic')),
                array('title' => tr('posting'),'url' => caseUrl('options&o=posting'),'icon' => icon('plus')),
                array('title' => tr('post_fetcher'),'url' => caseUrl('options&o=fetcher'),'icon' => icon('rss')),
                array('title' => tr('content'),'url' => caseUrl('options&o=content'),'icon' => icon('archive')),
                array('title' => tr('users'),'url' => caseUrl('options&o=users'),'icon' => icon('users')),
                array('title' => tr('mail'),'url' => caseUrl('options&o=mail'),'icon' => icon('envelope')),
                array('title' => tr('datetime'),'url' => caseUrl('options&o=datetime'),'icon' => icon('clock-o')),
                array('title' => tr('ads'),'url' => caseUrl('options&o=ads'),'icon' => icon('dollar')),
                array('title' => tr('social'),'url' => caseUrl('options&o=social'),'icon' => icon('facebook-square')),
                array('title' => tr('files'),'url' => caseUrl('options&o=files'),'icon' => icon('upload')),
                array('title' => tr('caching'),'url' => caseUrl('options&o=cache'),'icon' => icon('clock-o')),
                array('title' => tr('comments'),'url' => caseUrl('options&o=comments'),'icon' => icon('comments')),
                array('title' => tr('analytics'),'url' => caseUrl('options&o=analytics'),'icon' => icon('bar-chart-o')),
            ),
        ),
    );

    $Webfairy->smarty->assign('panel_nav', $panel_nav);

    $catgories_tree = $Webfairy->catgories_select_tree();

    $Webfairy->smarty->assign('catgories_tree', $catgories_tree);

    $pages = array();
    foreach ($Webfairy->db->pages() as $page) {
        $pages[$page['id']] = ((int) $page['type'] == 1) ? tr($page['prefix']) : $page['title'];
    }

    $Webfairy->smarty->assign('pages', $pages);

    $case = (isset($_GET['case']) == true) ? $_GET['case'] : '';

    $breadcrumb = array(
        array(
            'url' => caseUrl($case),
            'text' => tr($case),
        ),
    );

    if (isset($_GET['action']) == true) {
        $breadcrumb[] = array(
            'text' => tr($_GET['action']),
        );
    }

    switch ($case) {
        case 'ajax':
            switch ($_GET['c']) {
                case 'export_lang':
                   $lang_key = $_GET['lang'];
                
                   $langs = array();
                   foreach ($Webfairy->db->langs()->select("var_key,`{$lang_key}` as value")->order('`id` ASC') as $lang_row) {
                       $langs[$lang_row['var_key']] = $lang_row['value'];
                   }

                   $json = json_encode($langs);
                   
                   header('Content-disposition: attachment; filename='.Webfairy::NAME.'('.Webfairy::VERSION.')-'.$lang_key.'-pkg.json');
                   header('Content-type: application/json');                  
                   
                   echo $json;
                   
                break;
                
                case 'sourcepreview':
                    $id = (int) $_GET['id'];
                    $items_num = (isset($_GET['items_num']) == true) ? (int) $_GET['items_num'] : 3;

                    try {
                        $data = $Webfairy->FetchRSS($id, $items_num, 0, false);
                        $Webfairy->smarty->assign('data', $data);
                        $Webfairy->smarty->display('string:{source_preview data=$data}');
                    } catch (Exception $e) {
                        echo $e->getMessage();
                    }

                break;

                case 'fetchitem':
                    $id = (int) $_POST['id'];
                    $cat_id = (int) $_POST['cat'];
                    $item_key = (int) $_POST['key'];

                    try {
                        $data = $Webfairy->FetchRSS($id, 0, 0, true, $item_key);
                        foreach ($data['result']['items'] as $item) {
                            if ($item['fresh'] == false) {
                                throw new \Exception(tr('post_already_exists'));
                            }
                            if ($cat_id > 0) {
                                $item['row']['cat_id'] = $cat_id;
                            }
                            $records = $Webfairy->db_post_array($item['row']);
                            if ($Webfairy->insert_post_to_db($records)) {
                                echo '<i class="fa fa-check"></i> '.tr('done');
                            }
                        }
                    } catch (Exception $e) {
                        echo $e->getMessage();
                    }

                break;

                case 'navbars':
                    $fields = array(
                        1 => array(
                            'title','url',
                        ),
                    );
                    switch ($_GET['o']) {
                        case 'insert':
                            $index = (int) $_GET['index'];
                            $type = (int) $_POST['type'];

                            $item = array(
                                'index' => $index,
                                'type' => $type,
                            );

                            $html = '';
                            $errors = array();
                            $errorFound = false;

                            $Webfairy->loadClass('validaforms', 'tools');
                            $vFs = new validaForms();

                            switch ($type) {
                                case 1:
                                    if (isset($_POST['noicon']) == true) {
                                        if (!$vFs->textBox(tr('title'), $_POST['title'], true)) {
                                            $errorFound = true;
                                        }
                                    }
                                    $item['title'] = $Webfairy->plaintext($_POST['title']);
                                    $item['url'] = $Webfairy->plaintext($_POST['url']);
                                break;

                                case 2:
                                    $page_id =  (int) $_POST['page_id'];
                                    $item['page_id'] = $page_id;
                                    $item['title'] = (empty($_POST['title']) == false) ? $Webfairy->plaintext($_POST['title']) : $pages[$page_id];
                                break;

                                case 3:
                                    $cat_id = (int) $_POST['cat_id'];
                                    $item['cat_id'] = $cat_id;
                                    $item['title'] = (empty($_POST['title']) == false) ? $Webfairy->plaintext($_POST['title']) : $Webfairy->getCatgory($cat_id, 'title');
                                break;
                            }

                            $item['noicon'] = (isset($_POST['noicon'])) ? 'true' : 'false';
                            $item['icon'] = $Webfairy->plaintext($_POST['icon']);

                            if ($errorFound == true) {
                                $errors = $vFs->erro;
                            } else {
                                $Webfairy->smarty->assign('item', $item);

                                ob_start();
                                $Webfairy->smarty->display('string:<li class="dd-item" data-index="'.$index.'">{navbars_builder_item item=$item}</li>');
                                $html = ob_get_contents();
                                ob_end_clean();
                            }

                            header("Content-type:application/json; charset: UTF-8");

                            exit(json_encode(
                                array(
                                    'html' => $html,
                                    'errors' => $errors,
                                )
                            ));
                        break;

                        case 'save':
                            $data = (is_array($_POST['data']) == true) ? $_POST['data'] : array();
                            $Webfairy->updateOption(
                                'navbar_'.$_GET['navbar_prefix'],
                                json_encode($data)
                            );
                            $Webfairy->cache->remove($Webfairy->cache_key('Navbars', true));
                        break;

                    }
                break;
             }
             exit;
        break;

        case 'posts':
            if (isset($_GET['action']) == true) {
                $action = $_GET['action'];

                $posts = (isset($_GET['posts']) == true) ? array_map('intval', $_GET['posts']) : array();
                if (count($posts) == 0) {
                    $Webfairy->redirect(
                        tr('select_then_delete'),
                        $Webfairy->url('panel.html?case=posts')
                    );
                }
                $posts_array = $Webfairy->db->posts('id', $posts)->order("`id` DESC")->fetchPairs('id', 'title');

                $action_txt = array(
                      "delete" => tr('delete'),
                      "publish" => tr('publish'),
                      "unpublish" => tr('unpublish'),
                );

                $Form = new FormBuilder('posts', $action_txt[$action], tr('confirm'), true);

                $Form->addField(array(
                        'id' => 'posts',
                        'type' => 'checkbox',
                        'label' => tr('posts'),
                        'required' => true,
                        'checkboxlabels' => array_values($posts_array),
                        'checkboxvalues' => array_keys($posts_array),
                        'checkboxchecked' => array_fill(0, count($posts_array), true),
                    )
                );

                if ($Form->formSuccess()) {
                    switch ($action) {
                        case 'delete':
                            foreach ((array) $_POST['posts_arr'] as $post_id) {
                                $Webfairy->delete_post($post_id);
                            }
                        break;

                        case 'publish':
                        case 'unpublish':
                            $values = array(
                                'publish' => 1,
                                'unpublish' => 0,
                            );

                            foreach ((array) $_POST['posts_arr'] as $post_id) {
                                $post = $Webfairy->db->posts[$post_id];
                                $post['published'] = $values[$action];
                                if ($action == 'publish') {
                                    $post['publishedon'] = time();
                                }
                                $post->update();
                            }
                        break;
                    }
                    $Webfairy->go_to('panel.html?case=posts&msg=ACTION_DONE');
                }

                $Webfairy->smarty->assign('Form', $Form->renderForm());
            } else {
                $Webfairy->loadClass('pagination', 'tools');

                $filters = array();

                if (isset($_GET['filter']) == true) {
                    switch ($_GET['filter']) {
                        case 'wating_approval':
                            $filters['published'] = 0;
                            $filters['publishedon'] = 0;
                        break;
                    }
                }

                $pagination = new pagination(
                    $Webfairy->db->posts($filters)->order("id DESC"), 10);

                $Webfairy->smarty->assign('posts',
                    $Webfairy->post_db_rows(
                        $pagination->rows(),
                        array(
                            'simple' => true,
                            'all' => true,
                        ),
                        true
                    )
                );

                $Webfairy->smarty->assign('pagination', $pagination->display());
                /*
                $Webfairy->smarty->assign('dataTable',
                    $Webfairy->dataTableLoader('posts',
                        array(
                            'id' => array(
                                'title' => 'xx'
                            )
                        )
                    )
                );
                */

            }
            $tpl = 'panel-posts.tpl';
        break;

        case 'navbars':
            $navbar_prefix = (isset($_GET['prefix']) == true) ? $_GET['prefix'] : 'header';

            $Webfairy
                ->jsFile('js/iconpicker/fontawesome-4.2.0.min.js')
                ->jsFile('js/iconpicker/iconpicker.js')
                ->jsFile('js/nestable.js')
                ->cssFile('css/nestable.css');

            $Webfairy->html(
                $Webfairy->jQuery_code('navbarsPanel( "'.$navbar_prefix.'" );'),
                'footer'
            );

            $Webfairy->smarty->assign('navbar_prefix', $navbar_prefix);
            $Webfairy->smarty->assign('navbar_data', $Webfairy->getNavbarData($navbar_prefix));

            $tpl = 'panel-navbars.tpl';
        break;

        case 'cats':
            if (isset($_GET['action']) == true) {
                $action = $_GET['action'];

                if ($action == 'delete') {
                    $cats = (isset($_GET['cats']) == true) ? array_map('intval', $_GET['cats']) : array();

                    if (count($cats) == 0) {
                        $Webfairy->redirect(
                            tr('select_then_delete'),
                            $Webfairy->url('panel.html?case=cats')
                        );
                    }

                    $cats_array = $Webfairy->db->cats('id', $cats)->order("`id` DESC")->fetchPairs('id', 'title');

                    $Form = new FormBuilder('cats', tr('delete'), tr('cats'), true);

                    $Form->addField(array(
                            'id' => 'cats',
                            'type' => 'checkbox',
                            'label' => tr('cats'),
                            'required' => true,
                            'checkboxlabels' => array_values($cats_array),
                            'checkboxvalues' => array_keys($cats_array),
                            'checkboxchecked' => array_fill(0, count($cats_array), true),
                        )
                    );

                    $Form->addField(array(
                            'id' => 'delete_posts',
                            'type' => 'radio',
                            'label' => tr('delete_posts'),
                            'required' => true,
                            'instructions' => tr('delete_posts_ins'),
                            'radiolabels' => array(tr('yes'), tr('no')),
                            'radiovalues' => array(1, 0),
                            'defaultvalue' => 0,
                        )
                    );

                    if ($Form->formSuccess()) {
                        $Webfairy->delete_catgories((array) $_POST['cats_arr'], (boolean) $_POST['delete_posts']);
                        $Webfairy->go_to('panel.html?case=cats');
                    }
                } else {
                    $row = array(
                        'parent_id' => 0,
                        'title' => '',
                        'prefix' => '',
                    );
                    if ($action == 'edit' && isset($_GET['id'])) {
                        $id = (int) $_GET['id'];
                        if (($row = $Webfairy->db->cats[$id]) == false) {
                            $Webfairy->go_to('panel.html?case=cats');
                        }
                    }

                    $Form = new FormBuilder('cats', tr('save'), tr('cats'), true);

                    $Form->addField(array(
                            'id' => 'title',
                            'type' => 'textbox',
                            'label' => tr('title'),
                            'required' => true,
                            'maxlength' => 150,
                            'headerinjectioncheck' => 'full',
                            'defaultvalue' => $row['title'],
                        )
                    );

                    $Form->addField(array(
                            'id' => 'prefix',
                            'type' => 'textbox',
                            'label' => tr('permalink_name'),
                            'required' => true,
                            'maxlength' => 50,
                            'headerinjectioncheck' => 'full',
                            'defaultvalue' => $row['prefix'],
                        )
                    );

                    $cats = array(0 => tr('root_cat'));

                    $c = array('parent_id' => 0);

                    if ($action == 'edit') {
                        $c["id != ?"] = $id;
                    }

                    foreach ($Webfairy->db->cats($c)->order('id ASC') as $cat) {
                        $cats[$cat['id']] = $cat['title'];
                    }

                    $Form->addField(array(
                            'id' => 'parent_id',
                            'type' => 'select',
                            'label' => tr('parent_cat'),
                            'optionlabels' => array_values($cats),
                            'optionvalues' => array_keys($cats),
                            'defaultvalue' => $row['parent_id'],
                        )
                    );

                    if ($Form->formSuccess()) {
                        $isvalid = true;

                        $parent_id = (int) $_POST['parent_id'];

                        $title = $Webfairy->plaintext($_POST['title']);
                        $prefix = $Webfairy->plaintext($_POST['prefix']);

                        $records = array(
                            'parent_id' => $parent_id,
                            'title' => $title,
                            'prefix' => $Webfairy->seo_str($prefix),
                        );

                        $_where = ($action == 'edit') ? "prefix = '{$prefix}' AND id != {$id}" : "prefix = '{$prefix}'";

                        if ($Webfairy->db->cats($_where)->fetch()) {
                            $Form->forceErrorMessage('prefix', ftr('err_already_exists', array($prefix)));
                            $isvalid = false;
                        }

                        if ($isvalid == true) {
                            $Webfairy->cache->remove('catgories');
                            switch ($_GET['action']) {
                                case 'edit':
                                    $row->update($records);
                                    $Form->renderThanks();

                                    $display_options['redirect'] = true;
                                    $display_options['redirect_url'] = $Webfairy->url('panel.html?case=cats');
                                break;

                                case 'create':
                                    if ($id = $Webfairy->db->cats()->insert($records)) {
                                        $Webfairy->go_to('panel.html?case=cats');
                                    }
                                break;
                            }
                        }
                    }
                }

                $Webfairy->smarty->assign('Form', $Form->renderForm());
            } else {
                $cats = array();
                foreach ($Webfairy->catgories_tree() as $parent) {
                    $cats[$parent['id']] = $parent;
                    $cats[$parent['id']]['title'] = (count($parent['subcategories']) > 0) ? '+ '.$parent['title'] : '- '.$parent['title'];
                    foreach ($parent['subcategories'] as $sub) {
                        $cats[$sub['id']] = $sub;
                        $cats[$sub['id']]['title'] = "¦ ------ {$sub['title']}";
                    }
                }

                $Webfairy->smarty->assign('cats', $cats);
            }
            $tpl = 'panel-cats.tpl';
        break;

        case 'pages':
            if (isset($_GET['action']) == true) {
                $action = $_GET['action'];

                if ($action == 'delete') {
                    $pages = (isset($_GET['pages']) == true) ? array_map('intval', $_GET['pages']) : array();

                    if (count($pages) == 0) {
                        $Webfairy->redirect(
                            tr('select_then_delete'),
                            $Webfairy->url('panel.html?case=pages')
                        );
                    }

                    $pages_array = $Webfairy->db->pages('id', $pages)->where("type != 1")->order("`id` DESC")->fetchPairs('id', 'title');

                    $Form = new FormBuilder('pages', tr('delete'), tr('pages'), true);

                    $Form->addField(array(
                            'id' => 'pages',
                            'type' => 'checkbox',
                            'label' => tr('pages'),
                            'required' => true,
                            'checkboxlabels' => array_values($pages_array),
                            'checkboxvalues' => array_keys($pages_array),
                            'checkboxchecked' => array_fill(0, count($pages_array), true),
                        )
                    );

                    if ($Form->formSuccess()) {
                        foreach ($pages as $page_id) {
                            $Webfairy->db->pages('id', $page_id)->delete();
                        }
                        $Webfairy->go_to('panel.html?case=pages');
                    }
                } else {
                    $row = array(
                        'type' => 0,
                        'title' => '',
                        'prefix' => '',
                        'options' => array(
                            'require_auth' => 0,
                        ),
                        'content' => '',
                    );

                    if ($action == 'edit' && isset($_GET['id'])) {
                        $id = (int) $_GET['id'];
                        if (($row = $Webfairy->db->pages('id', $id)->where("type != 1")->fetch()) == false) {
                            $Webfairy->go_to('panel.html?case=pages');
                        }

                        $row['options'] = json_decode($row['options'], true);
                    }

                    $Form = new FormBuilder('pages', tr('save'), tr('pages'), true);

                    $Form->addField(array(
                            'id' => 'title',
                            'type' => 'textbox',
                            'label' => tr('title'),
                            'required' => true,
                            'maxlength' => 250,
                            'headerinjectioncheck' => 'full',
                            'defaultvalue' => $row['title'],
                        )
                    );

                    $Form->addField(array(
                            'id' => 'prefix',
                            'type' => 'textbox',
                            'label' => tr('permalink_name'),
                            'required' => true,
                            'maxlength' => 50,
                            'headerinjectioncheck' => 'full',
                            'defaultvalue' => $row['prefix'],
                        )
                    );

                    $Form->addField(array(
                            'id' => 'content',
                            'type' => 'textarea',
                            'label' => tr('content'),
                            'rows' => 8,
                            'required' => true,
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

                    $Form->addField(array(
                            'id' => 'options[require_auth]',
                            'type' => 'radio',
                            'label' => tr('require_auth'),
                            'radiolabels' => array(tr('yes'), tr('no')),
                            'radiovalues' => array(1, 0),
                            'defaultvalue' => $row['options']['require_auth'],
                        )
                    );

                    if ($Form->formSuccess()) {
                        $isvalid = true;

                        $prefix = $Webfairy->plaintext($_POST['prefix']);
                        $prefix = $Webfairy->seo_str($prefix);

                        $records = array(
                            'title' => $Webfairy->plaintext($_POST['title']),
                            'prefix' => $prefix,
                            'content' => $_POST['content'],
                        );

                        if (count($_POST['options']) > 0) {
                            $records['options'] = json_encode($_POST['options']);
                        }

                        $_where = ($action == 'edit') ? "prefix = '{$prefix}' AND id != {$id}" : "prefix = '{$prefix}'";

                        if ($Webfairy->db->pages($_where)->fetch()) {
                            $Form->forceErrorMessage('prefix', ftr('err_already_exists', array($prefix)));
                            $isvalid = false;
                        }

                        if ($isvalid == true) {
                            switch ($_GET['action']) {
                                case 'edit':
                                    $row->update($records);
                                    $Form->renderThanks();

                                    $display_options['redirect'] = true;
                                    $display_options['redirect_url'] = $Webfairy->url('panel.html?case=pages');
                                break;

                                case 'create':
                                    if ($id = $Webfairy->db->pages()->insert($records)) {
                                        $Webfairy->go_to('panel.html?case=pages');
                                    }
                                break;
                            }
                        }
                    }
                }

                $Webfairy->smarty->assign('Form', $Form->renderForm());
            } else {
                $Webfairy->loadClass('pagination', 'tools');

                $pagination = new pagination(
                    $Webfairy->db->pages("type != 1")->order("`id` DESC"), 10);

                $Webfairy->smarty->assign('pages',
                    $pagination->rows()
                );

                $Webfairy->smarty->assign('pagination', $pagination->display());
            }
            $tpl = 'panel-pages.tpl';
        break;

        case 'users':
            if (isset($_GET['action']) == true) {
                $action = $_GET['action'];

                if ($action == 'delete') {
                    $users = (isset($_GET['users']) == true) ? array_map('intval', $_GET['users']) : array();

                    if (count($users) == 0) {
                        $Webfairy->redirect(
                            tr('select_then_delete'),
                            $Webfairy->url('panel.html?case=users')
                        );
                    }

                    $users_array = $Webfairy->db->users('id', $users)->order("`id` DESC")->fetchPairs('id', 'username');

                    $Form = new FormBuilder('users', tr('delete'), tr('users'), true);

                    $Form->addField(array(
                            'id' => 'users',
                            'type' => 'checkbox',
                            'label' => tr('users'),
                            'required' => true,
                            'checkboxlabels' => array_values($users_array),
                            'checkboxvalues' => array_keys($users_array),
                            'checkboxchecked' => array_fill(0, count($users_array), true),
                        )
                    );

                    if ($Form->formSuccess()) {
                        $isvalid = true;

                        $users = (array) $_POST['users_arr'];

                        if (in_array($Webfairy->Userid(), $users) == true) {
                            $Form->forceErrorMessage('users', tr('delete_user_login'));
                            $isvalid = false;
                        }

                        if ($isvalid == true) {
                            foreach ($users as $user_id) {
                                $Webfairy->db->users('id', $user_id)->delete();
                            }
                            $Webfairy->go_to('panel.html?case=users');
                        }
                    }
                } else {
                    $row = array(
                        'status' => 1,
                        'username' => '',
                        'email' => '',
                        'manager' => 0,
                    );
                    if ($_GET['action'] == 'edit' && isset($_GET['id'])) {
                        $id = (int) $_GET['id'];
                        if (($row = $Webfairy->db->users[$id]) == false) {
                            $Webfairy->go_to('panel.html?case=users');
                        }
                    }

                    $Form = new FormBuilder('users', tr('save'), tr('users'), true);

                    $Form->addField(array(
                            'id' => 'username',
                            'type' => 'textbox',
                            'label' => tr('username'),
                            'required' => true,
                            'maxlength' => 20,
                            'headerinjectioncheck' => 'full',
                            'defaultvalue' => $row['username'],
                        )
                    );

                    $Form->addField(array(
                            'id' => 'email',
                            'type' => 'textbox',
                            'label' => tr('email'),
                            'required' => true,
                            'validationtype' => 'email',
                            'defaultvalue' => $row['email'],
                        )
                    );

                    $Form->addField(array(
                            'id' => 'password',
                            'type' => 'password',
                            'label' => tr('password'),
                            'maxlength' => 50,
                            'headerinjectioncheck' => 'full',
                        )
                    );

                    $Form->addField(array(
                            'id' => 'confirm',
                            'type' => 'password',
                            'label' => tr('password_confirm'),
                            'maxlength' => 50,
                            'headerinjectioncheck' => 'full',
                        )
                    );

                    $Form->addField(array(
                            'id' => 'manager',
                            'type' => 'radio',
                            'inline' => true,
                            'label' => tr('manager_roles'),
                            'radiolabels' => array(tr('yes'), tr('no')),
                            'radiovalues' => array(1, 0),
                            'defaultvalue' => $row['manager'],
                        )
                    );
                    
                    $Form->addField(array(
                            'id' => 'status',
                            'type' => 'radio',
                            'inline' => true,
                            'label' => tr('status'),
                            'radiolabels' => array(tr('active'), tr('banned')),
                            'radiovalues' => array(1, 0),
                            'defaultvalue' => $row['status'],
                        )
                    );

                    if ($Form->formSuccess()) {
                        $isvalid = true;

                        $username = $Webfairy->plaintext($_POST['username']);
                        $email = $Webfairy->plaintext($_POST['email']);

                        $records = array(
                            'status' => (int) $_POST['status'],
                            'username' => $username,
                            'email' => $email,
                            'manager' => (int) $_POST['manager'],
                        );

                        if ($Webfairy->db->users(
                            ($action == 'edit') ? "username = ? AND id != {$id}" : "username = ?",
                            $username
                           )->fetch()) {
                            $Form->forceErrorMessage('username', ftr('err_already_exists', array($username)));
                            $isvalid = false;
                        }

                        if ($Webfairy->db->users(
                            ($action == 'edit') ? "email = ? AND id != {$id}" : "email = ?",
                            $email
                           )->fetch()) {
                            $Form->forceErrorMessage('email', ftr('err_already_exists', array($email)));
                            $isvalid = false;
                        }

                        if (isset($_POST['password']) == true && empty($_POST['password']) == false) {
                            $password = $_POST['password'];
                            $confirm = $_POST['confirm'];

                            if ($password == $confirm) {
                                $salt = $Webfairy->createSalt();
                                $hash = hash('sha256', $password);

                                $records['password'] = hash('sha256', $salt.$hash);
                                $records['salt'] = $salt;
                            } else {
                                $isvalid = false;
                                $Form->forceErrorMessage('confirm', tr('password_not_match'));
                            }
                        }

                        if ($isvalid == true) {
                            switch ($_GET['action']) {
                                case 'edit':
                                    $row->update($records);
                                    $Form->renderThanks();

                                    $display_options['redirect'] = true;
                                    $display_options['redirect_url'] = $Webfairy->url('panel.html?case=users');

                                break;

                                case 'create':
                                    $records['created'] = new NotORM_Literal("CURRENT_TIMESTAMP()");
                                    if ($id = $Webfairy->db->users()->insert($records)) {
                                        $Webfairy->go_to('panel.html?case=users');
                                    }
                                break;
                            }
                        }
                    }
                }

                $Webfairy->smarty->assign('Form', $Form->renderForm());
            } else {
                $Webfairy->loadClass('pagination', 'tools');

                $pagination = new pagination(
                    $Webfairy->db->users()->order("`id` DESC"), 10);

                $Webfairy->smarty->assign('users',
                    $pagination->rows()
                );

                $Webfairy->smarty->assign('pagination', $pagination->display());
            }
            $tpl = 'panel-users.tpl';
    break;

    case 'langs':
        
        if (empty($_FILES['jsonfile']['name']) == false && empty($_POST['lang']) == false) {
            $file = $_FILES['jsonfile']['tmp_name'];
            $replace_all = (isset($_POST['replace_all']));
            $lang = $_POST['lang'];
            
            $jsondata = file_get_contents($file); 
            $data = json_decode($jsondata,true);
            
            if(is_array($data) && count($data) > 0){
            	if($replace_all){
                    foreach ($data as $key => $value) {
                        $Webfairy->db->langs()->insert_update(
                            array('var_key' => $key),
                            array('`'.$lang.'`' => $value),
                            array('`'.$lang.'`' => $value)
                        );
                    }                 	   
            	}else{
            	   $xkeys = $Webfairy->db->langs("`{$lang}` != ''")->fetchPairs('var_key','var_key');
                   $xvar_keys = array_values((array) $xkeys);
                    
                    foreach ($data as $key => $value) {
                    	if(in_array($key,$xvar_keys) == false){
                            $Webfairy->db->langs()->insert_update(
                                array('var_key' => $key),
                                array('`'.$lang.'`' => $value)
                            );                    	                      	   
                    	}
                    }                   
            	}                
            }           
        }
        
    
        $Webfairy->smarty->assign('langs_arr', $Webfairy->langsInfo() );
    
        $tpl = 'panel-langs.tpl';
    break;

    case 'translate':
            if (isset($_GET['action']) == true) {
                $action = $_GET['action'];

                $row = array(
                        'var_key' => '',
                    );
                if ($action == 'edit' && isset($_GET['id'])) {
                    $id = (int) $_GET['id'];
                    if (($row = $Webfairy->db->langs[$id]) == false) {
                        $Webfairy->go_to('panel.html?case=translate');
                    }
                }

                $Form = new FormBuilder('translate', tr('save'), tr('translate'), true);

                if ($action == 'create') {
                    $Form->addField(array(
                                'id' => 'var_key',
                                'type' => 'textbox',
                                'label' => tr('key'),
                                'required' => true,
                                'maxlength' => 50,
                                'defaultvalue' => $row['var_key'],
                            )
                        );
                }

                foreach ($Webfairy->availableLangs() as $key => $value) {
                    $Form->addField(array(
                                'id' => $key,
                                'type' => 'textarea',
                                'label' => tr($value),
                                'rows' => 2,
                                'defaultvalue' => ($action == 'edit') ? $row[$key] : '',
                            )
                        );
                }

                if ($Form->formSuccess()) {
                    $isvalid = true;

                    $records = array(
                            'version' => (int) Webfairy::VERSION,
                            'modified' => new NotORM_Literal("CURRENT_TIMESTAMP()"),
                        );

                    foreach ($Webfairy->availableLangs() as $key => $value) {
                        if (empty($_POST[$key]) == false) {
                            $records[$key] = ($key != 'ar') ? ucfirst($_POST[$key]) : $_POST[$key];
                        }
                    }

                    if ($action == 'create') {
                        $var_key = $Webfairy->plaintext($_POST['var_key']);
                        $var_key = strtolower($var_key);
                        $var_key = preg_replace('/[^\da-z_]/i', '_', $var_key);

                        if ($Webfairy->db->langs(
                               "var_key = ?", $var_key
                               )->fetch()) {
                            $Form->forceErrorMessage('var_key', ftr('err_already_exists', array($var_key)));
                            $isvalid = false;
                        }
                    }

                    if ($isvalid == true) {
                        switch ($_GET['action']) {
                                case 'edit':
                                    $row->update($records);
                                    $Form->renderThanks();

                                    $display_options['redirect'] = true;
                                    $display_options['redirect_url'] = (isset($_GET['return']) == true) ?  $Webfairy->return_to('return', true) : $Webfairy->url('panel.html?case=langs');

                                break;

                                case 'create':
                                    $records['var_key'] = $var_key;
                                    if ($id = $Webfairy->db->langs()->insert($records)) {
                                        $Webfairy->go_to('panel.html?case=translate');
                                    }
                                break;
                            }
                    }
                }
                $Webfairy->smarty->assign('Form', $Form->renderForm());
            } else {
                $Webfairy->loadClass('pagination', 'tools');
                $Webfairy->loadClass('arabic', 'I18N');

                $db_rows = $Webfairy->db->langs();
                $strOrderBy = '`var_key`';

                if (isset($_GET['q']) == true && empty($_GET['q']) == false) {
                    $q = $Webfairy->plaintext($_GET['q']);
                    
                    $sFields = array_merge(
                        array('var_key'),
                        array_keys($Webfairy->availableLangs())
                    );
                    
                    if($Webfairy->getLang() == 'ar'){
                        $sFields = array_map(function($f){return "`{$f}`";},$sFields);
                        
                        $Arabic_Query = new I18N_Arabic('Query');
                        $Arabic_Query->setArrFields($sFields);
                        $Arabic_Query->setMode(0);
    
                        $strCondition = $Arabic_Query->getWhereCondition($q);
                        $strOrderBy = $Arabic_Query->getOrderBy($q);                        
                    }else{
                        $sql = array();
                        
                        foreach ($sFields as $field) {
                        	$sql[] = "`{$field}` LIKE '%{$q}%'";
                        }                        
                        
                        $strCondition = implode(' OR ',$sql);
                    }

                    $db_rows->where($strCondition);
                }

                $db_rows->order($strOrderBy);

                $pagination = new pagination(
                    $db_rows, 25);

                $Webfairy->smarty->assign('langs',
                    $pagination->rows()
                );

                $Webfairy->smarty->assign('pagination', $pagination->display());
            }
            $tpl = 'panel-translate.tpl';
        break;

        case 'messages':

             if (isset($_GET['action']) == true) {
                 $action = $_GET['action'];

                 if ($action == 'delete') {
                     $messages = (isset($_GET['messages']) == true) ? array_map('intval', $_GET['messages']) : array();

                     if (count($messages) == 0) {
                         $Webfairy->redirect(
                            tr('select_then_delete'),
                            $Webfairy->url('panel.html?case=messages')
                        );
                     }

                     $messages_array = array();

                     foreach ($Webfairy->db->messages('id', $messages)->order("`id` DESC")->fetchPairs('id', 'content') as $message_id => $message_content) {
                         $messages_array[$message_id] = Webfairy::limit_words($message_content, 5);
                     }

                     $Form = new FormBuilder('messages', tr('delete'), tr('messages'), true);

                     $Form->addField(array(
                            'id' => 'messages',
                            'type' => 'checkbox',
                            'label' => tr('messages'),
                            'required' => true,
                            'checkboxlabels' => array_values($messages_array),
                            'checkboxvalues' => array_keys($messages_array),
                            'checkboxchecked' => array_fill(0, count($messages_array), true),
                        )
                    );

                     if ($Form->formSuccess()) {
                         $isvalid = true;

                         $messages = (array) $_POST['messages_arr'];

                         if ($isvalid == true) {
                             foreach ($messages as $message_id) {
                                 if ($msg = $Webfairy->db->messages('id', $message_id)->where('user_id', $Webfairy->Userid())) {
                                     $msg->delete();
                                 }
                             }
                             $Webfairy->go_to('panel.html?case=messages');
                         }
                     }
                 } elseif ($action == 'read' && isset($_GET['id'])) {
                     $message = array();

                     $id = (int) $_GET['id'];
                     if (($row = $Webfairy->db->messages('id', $id)->where('user_id', $Webfairy->Userid())->fetch()) == false) {
                         $Webfairy->go_to('panel.html?case=messages');
                     }

                     $row['sender_data'] = json_decode($row['sender_data'], true);
                     $row->update(array('unread' => 0));

                     $message = array(
                        'sender' => array(
                            'email' => $row['sender_data']['email'],
                            'ip' => long2ip($row['sender_data']['ip']),
                            'agent' => $row['sender_data']['user_agent'],
                        ),
                        'content' => nl2br($row['content']),
                        'time' => $Webfairy->TimeFormat($row['msgtime']),
                    );

                     $Form = new FormBuilder('messages', tr('send'), tr('replay'), true);

                     $Form->addField(array(
                            'id' => 'replay',
                            'type' => 'textarea',
                            'label' => tr('replay'),
                            'required' => true,
                            'minlength' => 15,
                        )
                    );

                     if ($Form->formSuccess()) {
                         $replay = $Webfairy->plaintext($_POST['replay']);

                         $email_body = ftr("replay_body", array($replay, $message['content']));

                         $Webfairy->mail(
                            array('email' => $message['sender']['email'], 'name' => $message['sender']['email']),
                            tr('replay').' '.$Webfairy->getOption('site_name'),
                            $email_body
                        );

                         $Webfairy->redirect(
                            tr('message_sent'),
                            $Webfairy->url('panel.html?case=messages')
                        );
                     }
                     $Webfairy->smarty->assign('message', $message);
                 }

                 $Webfairy->smarty->assign('Form', $Form->renderForm());
             } else {
                 $Webfairy->loadClass('pagination', 'tools');

                 $pagination = new pagination(
                    $Webfairy->db->messages('user_id', $Webfairy->Userid())->order("`id` DESC"), 10);

                 $Webfairy->smarty->assign('messages',
                    $pagination->rows()
                );

                 $Webfairy->smarty->assign('pagination', $pagination->display());
             }

            $tpl = 'panel-messages.tpl';
        break;

        case 'ads':

            if (isset($_GET['action']) == true) {
                $action = $_GET['action'];

                if ($action == 'delete') {
                    $ads = (isset($_GET['ads']) == true) ? array_map('intval', $_GET['ads']) : array();

                    if (count($ads) == 0) {
                        $Webfairy->redirect(
                            tr('select_then_delete'),
                            $Webfairy->url('panel.html?case=ads')
                        );
                    }

                    $ads_array = array();

                    foreach ($Webfairy->db->ads('id', $ads)->order("`id` DESC")->fetchPairs('id', 'size') as $ad_id => $ad_size) {
                        $ads_array[$ad_id] = adTitle(explode('x', $ad_size));
                    }

                    $Form = new FormBuilder('ads', tr('delete'), tr('ads'), true);

                    $Form->addField(array(
                            'id' => 'ads',
                            'type' => 'checkbox',
                            'label' => 'ads',
                            'required' => true,
                            'checkboxlabels' => array_values($ads_array),
                            'checkboxvalues' => array_keys($ads_array),
                            'checkboxchecked' => array_fill(0, count($ads_array), true),
                        )
                    );

                    if ($Form->formSuccess()) {
                        foreach ((array) $_POST['ads_arr'] as $ad_id) {
                            $Webfairy->db->ads('id', $ad_id)->delete();
                        }
                        $Webfairy->go_to('panel.html?case=ads');
                    }
                } else {
                    $row = array(
                        'code' => '',
                        'size' => '',
                    );
                    if ($_GET['action'] == 'edit' && isset($_GET['id'])) {
                        $id = (int) $_GET['id'];
                        if (($row = $Webfairy->db->ads[$id]) == false) {
                            $Webfairy->go_to('panel.html?case=ads');
                        }
                    }

                    $Form = new FormBuilder('ads', tr('save'), 'ads', true);

                    $ad_sizes = array();
                    foreach ($Webfairy->ad_sizes as $size) {
                        $ad_sizes["{$size[0]}x{$size[1]}"] = adTitle($size);
                    }

                    $Form->addField(array(
                            'id' => 'size',
                            'type' => 'select',
                            'label' => tr('size'),
                            'required' => true,
                            'optionlabels' => array_values($ad_sizes),
                            'optionvalues' => array_keys($ad_sizes),
                            'defaultvalue' => $row['size'],
                        )
                    );

                    $Form->addField(array(
                            'id' => 'code',
                            'type' => 'textarea',
                            'label' => tr('code'),
                            'defaultvalue' => $row['code'],
                        )
                    );

                    if ($Form->formSuccess()) {
                        $isvalid = true;

                        $size = $Webfairy->plaintext($_POST['size']);

                        $records = array(
                            'size' => $size,
                            'code' => $_POST['code'],
                        );

                        if ($isvalid == true) {
                            $Webfairy->cache->remove('adsArray');
                            switch ($_GET['action']) {
                                case 'edit':
                                    $row->update($records);
                                    $Form->renderThanks();

                                    $display_options['redirect'] = true;
                                    $display_options['redirect_url'] = $Webfairy->url('panel.html?case=ads');
                                break;

                                case 'create':
                                    if ($id = $Webfairy->db->ads()->insert($records)) {
                                        $Webfairy->go_to('panel.html?case=ads');
                                    }
                                break;
                            }
                        }
                    }
                }

                $Webfairy->smarty->assign('Form', $Form->renderForm());
            } else {
                $Webfairy->loadClass('pagination', 'tools');

                $pagination = new pagination(
                    $Webfairy->db->ads()->order("`id` DESC"), 10);

                $ads = array();

                foreach ($pagination->rows() as $row) {
                    $ads[$row['id']] = array(
                        'id' => $row['id'],
                        'title' => adTitle(explode('x', $row['size'])),
                        'code' => $row['code'],
                        'views' => $Webfairy->Kformat($row['views']),
                        'clicks' => $Webfairy->Kformat($row['clicks']),
                    );
                }

                $Webfairy->smarty->assign('ads', $ads);

                $Webfairy->smarty->assign('pagination', $pagination->display());
            }
            $tpl = 'panel-ads.tpl';
        break;

        case 'fetcher':
            $feedtypes = array();

            foreach (array(0, 1, 2, 3, 4, 5, 6, 7, 8, 11, 12, 13, 14) as $feedtype) {
                $feedtypes[$feedtype] = tr('feedtype_'.$feedtype);
            }

            if (isset($_GET['action']) == true) {
                $action = $_GET['action'];

                if ($action == 'delete') {
                    $sources = (isset($_GET['sources']) == true) ? array_map('intval', $_GET['sources']) : array();

                    if (count($sources) == 0) {
                        $Webfairy->redirect(
                            tr('select_then_delete'),
                            $Webfairy->url('panel.html?case=fetcher')
                        );
                    }

                    $sources_array = $Webfairy->db->sources('id', $sources)->order("`id` DESC")->fetchPairs('id', 'title');

                    $Form = new FormBuilder('sources', tr('delete'), tr('delete_sources'), true);

                    $Form->addField(array(
                            'id' => 'sources',
                            'type' => 'checkbox',
                            'label' => tr('sources'),
                            'required' => true,
                            'checkboxlabels' => array_values($sources_array),
                            'checkboxvalues' => array_keys($sources_array),
                            'checkboxchecked' => array_fill(0, count($sources_array), true),
                        )
                    );

                    $Form->addField(array(
                            'id' => 'delete_posts',
                            'type' => 'radio',
                            'label' => tr('delete_posts'),
                            'required' => true,
                            'instructions' => tr('delete_posts_ins'),
                            'radiolabels' => array(tr('yes'), tr('no')),
                            'radiovalues' => array(1, 0),
                            'defaultvalue' => 0,
                        )
                    );

                    if ($Form->formSuccess()) {
                        $Webfairy->delete_sources((array) $_POST['sources_arr'], (boolean) $_POST['delete_posts']);
                        $Webfairy->go_to('panel.html?case=fetcher');
                    }
                } else {
                    $row = array(
                        'title' => '',
                        'terms' => array(
                            'term' => '',
                        ),
                    );
                    if ($_GET['action'] == 'edit' && isset($_GET['id'])) {
                        $id = (int) $_GET['id'];
                        if (($row = $Webfairy->db->sources[$id]) == false) {
                            $Webfairy->go_to('panel.html?case=fetcher');
                        }
                        $row['terms'] = json_decode($row['terms'], true);
                        $row['terms'] = $row['terms'][0];
                    }

                    $Form = new FormBuilder('sources', tr('save_changes'), tr('post_fetcher'));

                    $Form->addField(array(
                            'id' => 'title',
                            'type' => 'textbox',
                            'label' => tr('title'),
                            'required' => true,
                            'maxlength' => 150,
                            'headerinjectioncheck' => 'full',
                            'defaultvalue' => $row['title'],
                        )
                    );

                    $Form->addField(array(
                            'id' => 'cat_id',
                            'type' => 'select',
                            'label' => tr('category'),
                            'required' => true,
                            'optionlabels' => array_values($catgories_tree),
                            'optionvalues' => array_keys($catgories_tree),
                            'defaultvalue' => $Webfairy->getValueIfExists('cat_id', $row['terms']),
                        )
                    );

                    $managers = $Webfairy->getManagers();

                    $Form->addField(array(
                            'id' => 'user_id',
                            'type' => 'select',
                            'label' => tr('author'),
                            'required' => true,
                            'optionlabels' => array_values($managers),
                            'optionvalues' => array_keys($managers),
                            'defaultvalue' => $Webfairy->getValueIfExists('user_id', $row['terms']),
                        )
                    );

                    $Form->addField(array(
                            'id' => 'type',
                            'type' => 'select',
                            'label' => tr('source_type'),
                            'required' => true,
                            'optionlabels' => array_values($feedtypes),
                            'optionvalues' => array_keys($feedtypes),
                            'defaultvalue' => $Webfairy->getValueIfExists('type', $row['terms']),
                        )
                    );

                    $Form->addField(array(
                            'id' => 'rss_url',
                            'type' => 'textbox',
                            'label' => tr('rss_url'),
                            'maxlength' => 500,
                            'instructions' => tr('rss_url_ins'),
                            'headerinjectioncheck' => 'full',
                            'defaultvalue' => $Webfairy->getValueIfExists('rss_url', $row['terms']['term']),
                        )
                    );

                    $Webfairy->setDependsOn('rss_url', 'type', array('0'));

                    $Form->addField(array(
                            'id' => 'google_dev_key',
                            'type' => 'textbox',
                            'label' => tr('google_dev_key'),
                            'maxlength' => 150,
                            'instructions' => tr('google_dev_key_ins'),
                            'headerinjectioncheck' => 'full',
                            'defaultvalue' => $Webfairy->getValueIfExists('google_dev_key', $row['terms']['term']),
                        )
                    );

                    $Webfairy->setDependsOn('google_dev_key', 'type', array('1','2'));

                    $Form->addField(array(
                            'id' => 'youtube_tag',
                            'type' => 'textbox',
                            'label' => tr('tag'),
                            'maxlength' => 150,
                            'instructions' => tr('youtube_tag_ins'),
                            'headerinjectioncheck' => 'full',
                            'defaultvalue' => $Webfairy->getValueIfExists('youtube_tag', $row['terms']['term']),
                        )
                    );

                    $Webfairy->setDependsOn('youtube_tag', 'type', array('1'));

                    $Form->addField(array(
                            'id' => 'youtube_channel',
                            'type' => 'textbox',
                            'label' => tr('channel'),
                            'maxlength' => 150,
                            'instructions' => tr('youtube_channel_ins'),
                            'headerinjectioncheck' => 'full',
                            'defaultvalue' => $Webfairy->getValueIfExists('youtube_channel', $row['terms']['term']),
                        )
                    );

                    $Webfairy->setDependsOn('youtube_channel', 'type', array('2'));

                    $Form->addField(array(
                            'id' => 'flickr_userid',
                            'type' => 'textbox',
                            'label' => tr('flickr_userid'),
                            'maxlength' => 150,
                            'headerinjectioncheck' => 'full',
                            'defaultvalue' => $Webfairy->getValueIfExists('flickr_userid', $row['terms']['term']),
                        )
                    );

                    $Webfairy->setDependsOn('flickr_userid', 'type', array('7', '6', '5'));

                    $Form->addField(array(
                            'id' => 'flickr_setid',
                            'type' => 'textbox',
                            'label' => tr('flickr_setid'),
                            'maxlength' => 150,
                            'headerinjectioncheck' => 'full',
                            'defaultvalue' => $Webfairy->getValueIfExists('flickr_setid', $row['terms']['term']),
                        )
                    );

                    $Webfairy->setDependsOn('flickr_setid', 'type', array('6'));

                    $Form->addField(array(
                            'id' => 'flickr_groupid',
                            'type' => 'textbox',
                            'label' => tr('flickr_groupid'),
                            'maxlength' => 150,
                            'headerinjectioncheck' => 'full',
                            'defaultvalue' => $Webfairy->getValueIfExists('flickr_groupid', $row['terms']['term']),
                        )
                    );

                    $Webfairy->setDependsOn('flickr_groupid', 'type', array('4'));

                    $Form->addField(array(
                            'id' => 'flickr_tag',
                            'type' => 'textbox',
                            'label' => tr('flickr_tag'),
                            'maxlength' => 150,
                            'headerinjectioncheck' => 'full',
                            'defaultvalue' => $Webfairy->getValueIfExists('flickr_tag', $row['terms']['term']),
                        )
                    );

                    $Webfairy->setDependsOn('flickr_tag', 'type', array('7', '3'));

                    $Form->addField(array(
                            'id' => 'soundcloud_userid',
                            'type' => 'textbox',
                            'label' => tr('soundcloud_userid'),
                            'maxlength' => 150,
                            'headerinjectioncheck' => 'full',
                            'defaultvalue' => $Webfairy->getValueIfExists('soundcloud_userid', $row['terms']['term']),
                        )
                    );

                    $Webfairy->setDependsOn('soundcloud_userid', 'type', array('8'));

                    $Form->addField(array(
                            'id' => 'username',
                            'type' => 'textbox',
                            'label' => tr('username'),
                            'maxlength' => 150,
                            'headerinjectioncheck' => 'full',
                            'defaultvalue' => $Webfairy->getValueIfExists('username', $row['terms']['term']),
                        )
                    );

                    $Webfairy->setDependsOn('username', 'type', array('11', '13'));

                    $Form->addField(array(
                            'id' => 'hashtag',
                            'type' => 'textbox',
                            'label' => tr('hashtag'),
                            'maxlength' => 150,
                            'headerinjectioncheck' => 'full',
                            'defaultvalue' => $Webfairy->getValueIfExists('hashtag', $row['terms']['term']),
                        )
                    );

                    $Webfairy->setDependsOn('hashtag', 'type', array('12'));

                    if ($Form->formSuccess()) {
                        $isvalid = true;

                        $title = $Webfairy->plaintext($_POST['title']);

                        $records = array(
                            'title' => $title,
                            'terms' => json_encode(
                                array(
                                    0 => array(
                                        'cat_id' => (int) $_POST['cat_id'],
                                        'user_id' => (int) $_POST['user_id'],
                                        'term' => $Webfairy->getDependsOnValues('type', $_POST),
                                        'type' => (int) $_POST['type'],
                                    ),
                                )
                            ),
                            'status' => 1,
                        );

                        if ($isvalid == true) {
                            switch ($_GET['action']) {
                                case 'edit':
                                    $row->update($records);

                                    $Form->renderThanks();

                                    $display_options['redirect'] = true;
                                    $display_options['redirect_url'] = (isset($_GET['return']) == true) ?  $Webfairy->return_to('return', true) : $Webfairy->url('panel.html?case=fetcher');

                                break;

                                case 'create':
                                    if ($id = $Webfairy->db->sources()->insert($records)) {
                                        $Webfairy->go_to('panel.html?case=fetcher');
                                    }
                                break;
                            }
                        }
                    }
                }

                $Webfairy->smarty->assign('Form', $Form->renderForm());
            } else {
                $Webfairy->loadClass('pagination', 'tools');

                $pagination = new pagination(
                    $Webfairy->db->sources()->order("`id` DESC"), 10);

                $sources = array();

                foreach ($pagination->rows() as $row) {
                    $sources[$row['id']] = array(
                        'id' => $row['id'],
                        'title' => $row['title'],
                    );
                }

                $Webfairy->smarty->assign('sources', $sources);

                $Webfairy->smarty->assign('pagination', $pagination->display());

                $Webfairy->html(
                    $Webfairy->jQuery_code('postsFetcher();'),
                    'footer'
                );
            }
            $tpl = 'panel-fetcher.tpl';
        break;

        case 'options';
            $tpl = array('panel-options.tpl');

            $options = array(

                'info' => array(
                    'site_name',
                    'title_separator',
                    'description',
                    'keywords',
                    'site_email',
                    'copyrights',
                ),
                'posting' => array(
                    'user_posting',
                    'publish_type',
                    'publish_votes',
                ),
                'fetcher' => array(
                    'fetcher_status',
                    'fetcher_cron',
                ),
                'mail' => array(
                    'mailer',
                    'smtp_host',
                    'smtp_port',
                    'smtp_username',
                    'smtp_password',
                ),
                'themes' => array(
                    'font_family',
                    'font_size',
                    'rtl',
                    'lang',
                    'theme_color',
                    'full_width',
                    'c_tablet_width',
                    'c_desktop_width',
                    'c_ldesktop_width',
                    'main_col',
                    'reverse_col_order',
                    'fixed_sidebar',
                    'logo_file_id',
                    'public_stats'
                ),
                'datetime' => array(
                    'timezone',
                    'date_mode',
                    'date_format',
                ),
                'content' => array(
                    'permalink_type',
                    'permalink_translate',
                    'posts_list_style',
                    'posts_per_page',
                    'related_per_page',
                    'everyone_vote',
                ),
                'users' => array(
                    'registration',
                ),
                'ads' => array(
                    'posts_ad',
                    'posts_ad_a',
                    'posts_ad_b',
                    'posts_ad_c',
                    'posts_ad_repeat',
                ),
                'social' => array(
                    'fb_page_uname',
                    'twitter_uname',
                    'youtube_uname',
                    'gplus_id',
                    'instagram_uname',
                    'subscription_box',
                    'feedburner_uri',
                ),
                'watermark' => array(
                    'watermark',
                    'watermark_position',
                    'watermark_file_id',
                ),
                'files' => array(
                    'uploaded_file_name',
                    'max_file_size',
                ),
                'cache' => array(
                    'caching',
                ),
                'comments' => array(
                    'comments_box',
                    'comments_sys',
                    'fb_admins',
                    'disqus_secret_key',
                    'disqus_shortname'
                ),
                'analytics' => array(
                    'googleanalytics_id',
                    'histats_id',
                ),
            );

            $o = (isset($_GET['o']) == true) ? $_GET['o'] : 'info';

            $breadcrumb[] = array('text' => tr($o));

            if($o == 'users'){
                foreach ($Webfairy->LoginProviders() as $provider) {
                	$options['users'][] = $provider.'_login';
                	$options['users'][] = $provider.'_key';
                	$options['users'][] = $provider.'_secret';
                }                
            }

            $values = array();

            foreach ($options[$o] as $okey) {
            	$values[$okey] = '';
            }

            foreach ($Webfairy->db->options('name', $options[$o]) as $option) {
                $values[$option['name']] = $option['value'];
            }

            $ad_sizes = array();
            foreach ($Webfairy->ad_sizes as $size) {
                $ad_sizes["{$size[0]}x{$size[1]}"] = adTitle($size);
            }

            $Form = new FormBuilder('options_'.$o, tr('save_changes'), tr('settings'), false);

            switch ($o) {
                case 'info':

                    $Form->addField(array(
                            'id' => 'site_name',
                            'type' => 'textbox',
                            'label' => tr('site_name'),
                            'required' => true,
                            'maxlength' => 50,
                            'headerinjectioncheck' => 'full',
                            'defaultvalue' => $values['site_name'],
                        )
                    );

                    $Form->addField(array(
                            'id' => 'title_separator',
                            'type' => 'textbox',
                            'label' => tr('title_separator'),
                            'required' => true,
                            'maxlength' => 5,
                            'headerinjectioncheck' => 'full',
                            'instructions' => tr('title_separator_ins'),
                            'defaultvalue' => $values['title_separator'],
                        )
                    );

                    $Form->addField(array(
                            'id' => 'site_email',
                            'type' => 'textbox',
                            'label' => tr('site_email'),
                            'required' => true,
                            'headerinjectioncheck' => 'full',
                            'validationtype' => 'email',
                            'defaultvalue' => $values['site_email'],
                        )
                    );

                    $Form->addField(array(
                            'id' => 'description',
                            'type' => 'textarea',
                            'label' => tr('meta_description'),
                            'required' => true,
                            'headerinjectioncheck' => 'full',
                            'defaultvalue' => $values['description'],
                        )
                    );

                    $Form->addField(array(
                            'id' => 'keywords',
                            'type' => 'textbox',
                            'label' => tr('meta_keywords'),
                            'required' => true,
                            'headerinjectioncheck' => 'full',
                            'defaultvalue' => $values['keywords'],
                        )
                    );

                    $Form->addField(array(
                            'id' => 'copyrights',
                            'type' => 'textbox',
                            'label' => tr('copyrights'),
                            'required' => true,
                            'headerinjectioncheck' => 'full',
                            'instructions' => tr('copyrights_ins'),
                            'defaultvalue' => $values['copyrights'],
                        )
                    );
                break;

                case 'posting':

                    $Form->addField(
                        array(
                            'id' => 'user_posting',
                            'type' => 'select',
                            'label' => tr('who_can_post'),
                            'optionlabels' => array(tr('managers_users'), tr('managers_only')),
                            'optionvalues' => array(1, 0),
                            'defaultvalue' => $values['user_posting'],
                        )
                    );

                    $Form->addField(
                        array(
                            'id' => 'publish_type',
                            'type' => 'select',
                            'label' => tr('publish_type'),
                            'optionlabels' => array(tr('by_managers'), tr('by_voting')),
                            'optionvalues' => array(1, 2),
                            'defaultvalue' => $values['publish_type'],
                        )
                    );

                    $Webfairy->setDependsOn('publish_votes', 'publish_type', array('2'));

                    $Form->addField(array(
                            'id' => 'publish_votes',
                            'type' => 'textbox',
                            'label' => tr('publish_votes'),
                            'instructions' => tr('publish_votes_ins'),
                            'validationtype' => 'numeric',
                            'defaultvalue' => $values['publish_votes'],
                        )
                    );

                break;

                case 'fetcher':

                    $Form->addField(array(
                            'id' => 'fetcher_status',
                            'type' => 'select',
                            'label' => tr('fetcher_status'),
                            'optionlabels' => array(tr('enabled'), tr('disabled')),
                            'optionvalues' => array(1, 0),
                            'defaultvalue' => $values['fetcher_status'],
                        )
                    );

                    $Form->addField(array(
                            'id' => 'fetcher_cron',
                            'type' => 'textbox',
                            'label' => tr('fetcher_cron'),
                            'defaultvalue' => $values['fetcher_cron'],
                        )
                    );

                    $Webfairy->jqCron_loader('input#fetcher_cron');

                break;

                case 'mail':

                    $Form->addField(array(
                            'id' => 'mailer',
                            'type' => 'select',
                            'label' => tr('default_mailer'),
                            'instructions' => tr('default_mailer_ins'),
                            'optionlabels' => array(tr('php_mailer'), tr('smtp_mailer')),
                            'optionvalues' => array('php', 'smtp'),
                            'defaultvalue' => $values['mailer'],
                        )
                    );

                    $Form->addField(array(
                            'id' => 'smtp_host',
                            'type' => 'textbox',
                            'label' => tr('smtp_host'),
                            'instructions' => tr('smtp_host_ins'),
                            'defaultvalue' => $values['smtp_host'],
                        )
                    );

                    $Webfairy->setDependsOn('smtp_host', 'mailer', array('smtp'));

                    $Form->addField(array(
                            'id' => 'smtp_port',
                            'type' => 'textbox',
                            'label' => tr('smtp_port'),
                            'instructions' => tr('smtp_port_ins'),
                            'defaultvalue' => $values['smtp_port'],
                        )
                    );

                    $Webfairy->setDependsOn('smtp_port', 'mailer', array('smtp'));

                    $Form->addField(array(
                            'id' => 'smtp_username',
                            'type' => 'textbox',
                            'label' => tr('smtp_username'),
                            'defaultvalue' => $values['smtp_username'],
                        )
                    );

                    $Webfairy->setDependsOn('smtp_username', 'mailer', array('smtp'));

                    $Form->addField(array(
                            'id' => 'smtp_password',
                            'type' => 'textbox',
                            'label' => tr('smtp_password'),
                            'defaultvalue' => $values['smtp_password'],
                        )
                    );

                    $Webfairy->setDependsOn('smtp_password', 'mailer', array('smtp'));

                break;

                case 'themes':
                    $fonts_arr = include dirname(__FILE__).'/lib/tools/googlefonts.php';

                    $langs = $disabledlangs = array();
                    foreach ($Webfairy->langsInfo() as $lang_code => $lang_ar) {
                        $langs[$lang_code] = $lang_ar['title'].' ('.$lang_ar['percent'].'%)';
                        if($lang_ar['percent'] <= 50){
                            $disabledlangs[] = $lang_code;
                        }
                    }
                    
                    $Form->addField(array(
                            'id' => 'lang',
                            'type' => 'select',
                            'label' => tr('lang'),
                            'instructions' => tr('select_lang_ins'),
                            'optionlabels' => array_values($langs),
                            'optionvalues' => array_keys($langs),
                            'defaultvalue' => $values['lang'],
                            'disabledvalues' => $disabledlangs
                        )
                    );

                    $Form->addField(array(
                            'id' => 'rtl',
                            'type' => 'radio',
                            'label' => tr('rtl_mode'),
                            'radiolabels' => array(tr('yes'), tr('no')),
                            'radiovalues' => array(1, 0),
                            'defaultvalue' => $values['rtl'],
                        )
                    );

                    $Form->addField(array(
                            'id' => 'font_family',
                            'type' => 'select',
                            'label' => tr('font_family'),
                            'optionlabels' => $fonts_arr,
                            'optionvalues' => $fonts_arr,
                            'defaultvalue' => $values['font_family'],
                        )
                    );

                    $Form->addField(array(
                            'id' => 'font_size',
                            'type' => 'select',
                            'label' => tr('font_size'),
                            'optionlabels' => range(10, 40),
                            'optionvalues' => range(10, 40),
                            'defaultvalue' => $values['font_size'],
                        )
                    );

                    $Form->addField(array(
                            'id' => 'theme_color',
                            'type' => 'textbox',
                            'label' => tr('theme_color'),
                            'defaultvalue' => $values['theme_color'],
                        )
                    );

                    $Form->addField(array(
                            'id' => 'full_width',
                            'type' => 'select',
                            'label' => tr('container_width'),
                            'optionlabels' => array(tr('full_width'), tr('fixed_width')),
                            'optionvalues' => array(1, 0),
                            'defaultvalue' => $values['full_width'],
                        )
                    );
                    
                    foreach(array('tablet','desktop','ldesktop') as $key){
                        $Form->addField(array(
                                'id' => 'c_'.$key.'_width',
                                'type' => 'textbox',
                                'label' => tr('c_'.$key.'_width'),
                                'defaultvalue' => $values['c_'.$key.'_width'],
                            )
                        );                    
                        
                        $Webfairy->setDependsOn('c_'.$key.'_width', 'full_width', array('0'));                        
                    }                                        

                    $Form->addField(array(
                            'id' => 'main_col',
                            'type' => 'select',
                            'label' => tr('main_col'),
                            'optionlabels' => array('10/2', '9/3', '8/4'),
                            'optionvalues' => array(10, 9, 8),
                            'defaultvalue' => $values['main_col'],
                        )
                    );

                    $Form->addField(array(
                            'id' => 'reverse_col_order',
                            'type' => 'radio',
                            'label' => tr('reverse_col_order'),
                            'instructions' => tr('reverse_col_order_inst'),
                            'radiolabels' => array(tr('yes'), tr('no')),
                            'radiovalues' => array(1, 0),
                            'defaultvalue' => $values['reverse_col_order'],
                        )
                    );
                    
                    $Form->addField(array(
                            'id' => 'fixed_sidebar',
                            'type' => 'radio',
                            'label' => tr('fixed_sidebar'),
                            'radiolabels' => array(tr('yes'), tr('no')),
                            'radiovalues' => array(1, 0),
                            'defaultvalue' => $values['fixed_sidebar'],
                        )
                    );



                    $tpl[] = 'media-tab.tpl';

                    $Form->addField(array(
                            'id' => 'logo_file_id',
                            'type' => 'imagefile',
                            'label' => tr('logo_file'),
                            'defaultvalue' => $values['logo_file_id'],
                            'defaultimage' => ($logo_file = $Webfairy->db->files[$values['logo_file_id']]) ? $Webfairy->_uploaded('resized', 'thumb_'.$logo_file['file_physical_name']) : '',
                        )
                    );

                    $Webfairy->media_tab_loader(
                        array(
                            'group' => 2,
                            'buttons' => array(
                                array(
                                    'class' => 'set-featured-image',
                                    'text' => tr('set_logo_image'),
                                    'attrs' => array(),
                                ),
                            ),
                        )
                    );
                    
                    $Form->addField(array(
                            'id' => 'public_stats',
                            'type' => 'radio',
                            'label' => tr('public_stats'),
                            'radiolabels' => array(tr('yes'), tr('no')),
                            'radiovalues' => array(1, 0),
                            'defaultvalue' => $values['public_stats'],
                        )
                    );                    

                break;

                case 'datetime':

                    $timezone_list = array();

                    foreach ($Webfairy->timezone_list() as $t) {
                        $timezone_list[$t['zone']] = $t['diff_from_GMT'].' - '.$t['zone'];
                    }

                    $Form->addField(array(
                            'id' => 'timezone',
                            'type' => 'select',
                            'label' => tr('default_time_zone'),
                            'optionlabels' => array_values($timezone_list),
                            'optionvalues' => array_keys($timezone_list),
                            'defaultvalue' => $values['timezone'],
                        )
                    );

                    $Form->addField(array(
                            'id' => 'date_format',
                            'type' => 'textbox',
                            'label' => tr('date_format'),
                            'required' => true,
                            'defaultvalue' => $values['date_format'],
                        )
                    );

                    if ($Webfairy->getLang() == 'ar') {
                        $date_modes = array();

                        for ($i = 1; $i <= 7; $i++) {
                            $date_modes[$i] = $Webfairy->date(time(), $Webfairy->getOption('date_format'), $i);
                        }

                        $Form->addField(array(
                                'id' => 'date_mode',
                                'type' => 'select',
                                'label' => tr('date_mode'),
                                'optionlabels' => array_values($date_modes),
                                'optionvalues' => array_keys($date_modes),
                                'defaultvalue' => $values['date_mode'],
                            )
                        );
                    }

                break;

                case 'content':

                    $Form->addField(array(
                            'id' => 'posts_list_style',
                            'type' => 'select',
                            'label' => tr('posts_list_style'),
                            'optionlabels' => array(tr('grid'), tr('list'),tr('blog_view')),
                            'optionvalues' => array('a', 'b', 'c'),
                            'defaultvalue' => $values['posts_list_style'],
                        )
                    );

                    $Form->addField(array(
                            'id' => 'posts_per_page',
                            'type' => 'textbox',
                            'label' => tr('posts_per_page'),
                            'instructions' => tr('posts_per_page_ins'),
                            'defaultvalue' => $values['posts_per_page'],
                        )
                    );

                    $Form->addField(array(
                            'id' => 'related_per_page',
                            'type' => 'textbox',
                            'label' => tr('related_per_page'),
                            'defaultvalue' => $values['related_per_page'],
                        )
                    );

                    $Form->addField(array(
                            'id' => 'permalink_type',
                            'type' => 'radio',
                            'label' => tr('permalink_type'),
                            'instructions' => tr('permalink_type_ins'),
                            'radiolabels' => array(
                                    ftr('permalink_type_1', array($Webfairy->absolute_url($Webfairy->url(tr('category'), 10, 'html')))),
                                    ftr('permalink_type_2', array($Webfairy->absolute_url($Webfairy->url(tr('category'), tr('post'), 'html')))),
                                ),
                            'radiovalues' => array(1, 2),
                            'defaultvalue' => $values['permalink_type'],
                        )
                    );

                     if ($Webfairy->getLang() == 'ar') {
                         $Form->addField(array(
                                'id' => 'permalink_translate',
                                'type' => 'radio',
                                'label' => tr('permalink_translate'),
                                'instructions' => tr('permalink_translate_ins'),
                                'radiolabels' => array(tr('yes'), tr('no')),
                                'radiovalues' => array(1, 0),
                                'defaultvalue' => $values['permalink_translate'],
                            )
                        );
                     }

                    $Form->addField(array(
                            'id' => 'everyone_vote',
                            'type' => 'radio',
                            'label' => tr('who_can_vote'),
                            'radiolabels' => array(tr('all'), tr('users')),
                            'radiovalues' => array(1, 0),
                            'defaultvalue' => $values['everyone_vote'],
                        )
                    );

                break;

                case 'ads':

                    $Form->addField(array(
                            'id' => 'posts_ad',
                            'type' => 'select',
                            'label' => tr('posts_ad'),
                            'instructions' => tr('posts_ad_ins'),
                            'optionlabels' => array(tr('enabled'), tr('disabled')),
                            'optionvalues' => array(1, 0),
                            'defaultvalue' => $values['posts_ad'],
                        )
                    );

                    $Form->addField(array(
                            'id' => 'posts_ad_a',
                            'type' => 'select',
                            'label' => tr('ad_size_grid'),
                            'optionlabels' => array_values($ad_sizes),
                            'optionvalues' => array_keys($ad_sizes),
                            'defaultvalue' => $values['posts_ad_a'],
                        )
                    );

                    $Webfairy->setDependsOn('grid_ad_size', 'posts_ad', array('1'));

                    $Form->addField(array(
                            'id' => 'posts_ad_b',
                            'type' => 'select',
                            'label' => tr('ad_size_list'),
                            'optionlabels' => array_values($ad_sizes),
                            'optionvalues' => array_keys($ad_sizes),
                            'defaultvalue' => $values['posts_ad_b'],
                        )
                    );

                    $Webfairy->setDependsOn('posts_ad_size', 'posts_ad', array('1'));

                    $Form->addField(array(
                            'id' => 'posts_ad_c',
                            'type' => 'select',
                            'label' => tr('ad_size_blog'),
                            'optionlabels' => array_values($ad_sizes),
                            'optionvalues' => array_keys($ad_sizes),
                            'defaultvalue' => $values['posts_ad_c'],
                        )
                    );

                    $Webfairy->setDependsOn('posts_ad_size', 'posts_ad', array('1'));

                    $Form->addField(array(
                            'id' => 'posts_ad_repeat',
                            'type' => 'textbox',
                            'label' => tr('ad_repeat'),
                            'validationtype' => 'numeric',
                            'defaultvalue' => $values['posts_ad_repeat'],
                        )
                    );

                    $Webfairy->setDependsOn('posts_ad_repeat', 'posts_ad', array('1'));
                break;

                case 'social':

                    $Form->addField(array(
                            'id' => 'fb_page_uname',
                            'type' => 'textbox',
                            'label' => tr('fb_page_uname'),
                            'maxlength' => 50,
                            'headerinjectioncheck' => 'full',
                            'defaultvalue' => $values['fb_page_uname'],
                        )
                    );

                    $Form->addField(array(
                            'id' => 'twitter_uname',
                            'type' => 'textbox',
                            'label' => tr('twitter_uname'),
                            'maxlength' => 50,
                            'headerinjectioncheck' => 'full',
                            'defaultvalue' => $values['twitter_uname'],
                        )
                    );

                    $Form->addField(array(
                            'id' => 'youtube_uname',
                            'type' => 'textbox',
                            'label' => tr('youtube_uname'),
                            'maxlength' => 50,
                            'headerinjectioncheck' => 'full',
                            'defaultvalue' => $values['youtube_uname'],
                        )
                    );

                    $Form->addField(array(
                            'id' => 'gplus_id',
                            'type' => 'textbox',
                            'label' => tr('gplus_id'),
                            'maxlength' => 50,
                            'headerinjectioncheck' => 'full',
                            'defaultvalue' => $values['gplus_id'],
                        )
                    );

                    $Form->addField(array(
                            'id' => 'instagram_uname',
                            'type' => 'textbox',
                            'label' => tr('instagram_uname'),
                            'maxlength' => 50,
                            'headerinjectioncheck' => 'full',
                            'defaultvalue' => $values['instagram_uname'],
                        )
                    );

                    $Form->addField(array(
                            'id' => 'subscription_box',
                            'type' => 'radio',
                            'label' => tr('subscription_box'),
                            'radiolabels' => array(tr('yes'), tr('no')),
                            'radiovalues' => array(1, 0),
                            'defaultvalue' => $values['subscription_box'],
                        )
                    );

                    $Form->addField(array(
                            'id' => 'feedburner_uri',
                            'type' => 'textbox',
                            'label' => tr('feedburner_uri'),
                            'maxlength' => 50,
                            'headerinjectioncheck' => 'full',
                            'instructions' =>  tr('feedburner_uri_ins'),
                            'defaultvalue' => $values['feedburner_uri'],
                        )
                    );

                break;

                case 'cache':

                    $Form->addField(array(
                            'id' => 'caching',
                            'type' => 'radio',
                            'label' => tr('caching'),
                            'radiolabels' => array(tr('yes'), tr('no')),
                            'radiovalues' => array(1, 0),
                            'defaultvalue' => $values['caching'],
                        )
                    );
                    
                break;

                case 'users':

                    $Form->addField(array(
                            'id' => 'registration',
                            'type' => 'radio',
                            'label' => tr('allow_registration'),
                            'radiolabels' => array(tr('yes'), tr('no')),
                            'radiovalues' => array(1, 0),
                            'defaultvalue' => $values['registration'],
                        )
                    );
                    
                    foreach ($Webfairy->LoginProviders() as $provider) {
                        $Form->addField(array(
                                'id' => $provider.'_login',
                                'type' => 'radio',
                                'inline' => true,
                                'label' => ftr('enable_x',array(tr($provider))),
                                'radiolabels' => array(tr('yes'), tr('no')),
                                'radiovalues' => array(1, 0),
                                'defaultvalue' => $values[$provider.'_login'],
                            )
                        );                        
                        $Form->addField(array(
                                'id' => $provider.'_key',
                                'type' => 'textbox',
                                'label' => ftr('app_key_x',array(tr($provider))),
                                //'maxlength' => 800,
                                'defaultvalue' => $values[$provider.'_key'],
                            )
                        );
                        
                        $Form->addField(array(
                                'id' => $provider.'_secret',
                                'type' => 'textbox',
                                'label' => ftr('app_secret_x',array(tr($provider))),
                                //'maxlength' => 500,
                                'defaultvalue' => $values[$provider.'_secret'],
                            )
                        );                        
                    }                    
                    
                    
                    
                break;

                case 'files':

                    $Form->addField(array(
                            'id' => 'uploaded_file_name',
                            'type' => 'radio',
                            'label' => tr('uploaded_file_name'),
                            'radiolabels' => array(tr('uploaded_real'), tr('uploaded_random')),
                            'radiovalues' => array('real', 'random'),
                            'defaultvalue' => $values['uploaded_file_name'],
                        )
                    );
                    
                    $Form->addField(array(
                            'id' => 'max_file_size',
                            'type' => 'textbox',
                            'label' => tr('max_file_size'),
                            'validationtype' => 'numeric',
                            'required' => true,
                            'defaultvalue' => $values['max_file_size'],
                        )
                    );

                break;

                case 'comments':

                    $Form->addField(array(
                            'id' => 'comments_box',
                            'type' => 'radio',
                            'label' => tr('comments_box'),
                            'radiolabels' => array(tr('enabled'), tr('disabled')),
                            'radiovalues' => array(1, 0),
                            'defaultvalue' => $values['comments_box'],
                        )
                    );

                    $Form->addField(array(
                            'id' => 'comments_sys',
                            'type' => 'select',
                            'label' => tr('comments_sys'),
                            'optionlabels' => array(tr('facebook'), tr('disqus')),
                            'optionvalues' => array('facebook', 'disqus'),
                            'defaultvalue' => $values['comments_sys'],
                        )
                    );

                    $Form->addField(array(
                            'id' => 'fb_admins',
                            'type' => 'textbox',
                            'label' => tr('fb_admins'),
                            'instructions' => tr('fb_admins_ins'),
                            'defaultvalue' => $values['fb_admins'],
                        )
                    );
                    
                    $Webfairy->setDependsOn('fb_admins', 'comments_sys', array('facebook'));

                    $Form->addField(array(
                            'id' => 'disqus_secret_key',
                            'type' => 'textbox',
                            'label' => tr('disqus_secret_key'),
                            'instructions' => tr('disqus_secret_key_ins'),
                            'defaultvalue' => $values['disqus_secret_key'],
                        )
                    );
                    
                    $Webfairy->setDependsOn('disqus_secret_key', 'comments_sys', array('disqus'));
                    
                    $Form->addField(array(
                            'id' => 'disqus_shortname',
                            'type' => 'textbox',
                            'label' => tr('disqus_shortname'),
                            'instructions' => tr('disqus_shortname_ins'),
                            'defaultvalue' => $values['disqus_shortname'],
                        )
                    );
                    
                    $Webfairy->setDependsOn('disqus_shortname', 'comments_sys', array('disqus'));
                    
                    
                    
                    
                break;

                case 'analytics':

                    $Form->addField(array(
                            'id' => 'googleanalytics_id',
                            'type' => 'textbox',
                            'label' => tr('googleanalytics_id'),
                            'instructions' => tr('googleanalytics_id_ins'),
                            'defaultvalue' => $values['googleanalytics_id'],
                        )
                    );

                    $Form->addField(array(
                            'id' => 'histats_id',
                            'type' => 'textbox',
                            'label' => tr('histats_id'),
                            'instructions' => tr('histats_id_ins'),
                            'defaultvalue' => $values['histats_id'],
                        )
                    );
                break;
            }

            if ($Form->formSuccess()) {
                $isvalid = true;

                foreach ($options[$o] as $option) {
                    if (isset($_POST[$option])) {
                        $Webfairy->updateOption($option, $Webfairy->plaintext($_POST[$option]));
                    }
                }

                if ($isvalid == true && $Form->formSuccess()) {
                    $Webfairy->cache->remove('options');
                    $Webfairy->redirect(
                        tr('form_processed'),
                        $Webfairy->url('panel.html?case=options&o='.$o)
                    );
                }
            }

            $Webfairy->smarty->assign('Form', $Form->renderForm());

        break;

        default :
            $info = array();
            $info['providers'] = $Webfairy->supported_providers();
            $info['version'] = Webfairy::VERSION;
            $info['php'] = (function_exists('phpversion') == true) ? phpversion() : tr('unkown');
            $info['db'] = strtoupper($Webfairy->driver_name);

            $Webfairy->smarty->assign('info', $info);

            $messages = array();

            $wating_approval_count = $Webfairy->db->posts(array('published' => 0, 'publishedon' => 0))->count('id');

            if ($wating_approval_count > 0) {
                $messages[] = $Webfairy->alert_html(
                    sprintf(
                        '<a href="%s">%s</a>',
                        'panel.html?case=posts&filter=wating_approval',
                        ftr('posts_wating_approval', array($wating_approval_count)
                    )
                ), 'warning');
            }

            try {
                $response = $Webfairy->loadUrl(Webfairy::VERSION_CHECKER);
                $data = json_decode($response, true);
                $last_version = (float) $data['version'];
                $current_version = Webfairy::VERSION;
                if ($current_version < $last_version) {
                    $messages[] = $Webfairy->alert_html(ftr('upgrade_last_version', array($last_version)), 'danger');
                } else {
                    $messages[] = $Webfairy->alert_html(tr('run_last_version'), 'success');
                }
            } catch (Exception $e) {
                $messages[] = $Webfairy->alert_html($e->getMessage(), 'danger');
            }

            if (is_dir($Webfairy->getOption('base_path').'install/')) {
                $messages[] = $Webfairy->alert_html(tr('delete_install_dir'));
            }

            $Webfairy->smarty->assign('messages', $messages);

            $tpl = 'panel.tpl';
    }

    $Webfairy->smarty->assign('breadcrumb', $breadcrumb);

    $Webfairy->display($tpl, array(tr('panel')), $display_options);
