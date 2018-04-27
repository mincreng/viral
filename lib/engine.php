<?php
/**
 * @author Webfairy MediaT CMS - www.Webfairy.net
 */

@libxml_use_internal_errors(true);

define('DS', DIRECTORY_SEPARATOR);

final class Webfairy
{
    const NAME = 'MediaT';
    const VERSION = '1.4';

    const NEWS_SOURCE = 'http://webfairy.net/news.php?app=mediat&iframe=true';
    const VERSION_CHECKER = 'http://mediat.webfairy.net/version.php';

    public $driver_name = 'PDO';

    protected $initialized = false;
    protected $sessionState = Webfairy::SESSION_STATE_UNINITIALIZED;

    const SESSION_STATE_UNAVAILABLE = -1;
    const SESSION_STATE_UNINITIALIZED = 0;
    const SESSION_STATE_INITIALIZED = 1;
    const SESSION_STATE_EXTERNAL = 2;

    protected $config = null;
    
    protected $login_providers = array('Facebook','Twitter','Google','Yahoo','Linkedin','Live');
    
    protected $types = array(
        1 => 'image',
        2 => 'code',
        3 => 'flash',
        //4 => 'text',
        5 => 'video',
        6 => 'audio',
        7 => 'file',
        8 => 'link',
        9 => 'gallery'
    );

    protected $langs = array(
        'en' => 'english',
        'ar' => 'arabic',
        'fr' => 'french',
        'de' => 'german',
        'es' => 'spanish',
        'it' => 'italian',
        'cz' => 'czech',
        'gr' => 'greek',
        'eo' => 'esperanto',
        'ir' => 'persian',
        'in' => 'hindi',
        'hr' => 'croatian',
        'is' => 'icelandic',
        'ja' => 'japanese',
        'kk' => 'kazakh',
        'ko' => 'korean',
        'la' => 'latin',
        'ro' => 'romanian',
        'ru' => 'russian',
        'tr' => 'turkish',
        'zh' => 'chinese',        
    );

    protected $userData = array(
        'id' => false,
        'isLoggedIn' => false,
        'isManager' => false,
        'username' => '',
        'unReadMessages' => 0,
    );

    protected $mime_types = array(

        'video' => array(
            'video/mp4' => 'mp4',
            'video/m4v' => 'm4v',
            'video/mov' => 'mov',
            'video/wmv' => 'wmv',
            'video/flv|video/x-flv' => 'flv',
        ),

        'audio' => array(
            'audio/wma' => 'wma',
            'audio/m4a' => 'm4a',
            'audio/mp3' => 'mp3',
            'audio/wav' => 'wav',
            'audio/mpeg' => 'mpeg',
            'audio/flv|audio/x-flv' => 'flv',
        ),

        'image' => array(
            'image/gif' => 'gif',
            'image/jpeg' => 'jpeg',
            'image/jpg' => 'jpg',
            'image/png' => 'png',
            'image/x-ms-bmp' => 'bmp',
        ),

        'flash' => array(
            'application/x-shockwave-flash' => 'swf',
        ),

        'compressed' => array(
            'application/x-bzip' => 'bz',
            'application/x-bzip2' => 'bz2',
            'application/zip' => 'zip',
            'application/x-rar-compressed' => 'rar',
        ),

        'text' => array(
            'application/x-sql' => 'sql',
            'text/plain' => 'txt',
        ),

    );

    protected $html = array(
        'header' => array(),
        'footer' => array(),
    );

    public $catgories = array();
    public $pages = array();

    public $ad_sizes = array(
        array(160,600),
        array(120,600),
        #array(180,600),
        array(200,200),
        array(250,250),
        array(300,250),
        array(336,280),
        array(728,90),
        array(468,60),
        array(300,600),
        array(320,50),
        array(970,90),
    );

    protected $db_info = array();

    protected $dependsOn = array();

    public function __construct()
    {
        Webfairy::protect();

        $config_file = dirname(__FILE__).'/config.php';
        if (file_exists($config_file)) {
            if (include($config_file)) {
                $this->db_info['prefix'] = $db_info['prefix'];
                $this->loadClass('NotORM', 'Db');
                try {
                    $connect = $this->dbConnect($db_info['dsn'], $db_info['user'], $db_info['pass'], array());
                } catch (Exception $e) {
                    exit($e->getMessage());
                }
                $structure = new NotORM_Structure_Convention(
                    $primary = "id",
                    $foreign = "%s_id",
                    $table = "%ss",
                    $prefix = $db_info['prefix']
                );

                $this->db = new NotORM($connect, $structure);

                unset($db_info);
            }
        } else {
            exit(header("Location: install"));
        }
    }

    public static function protect()
    {
        if (isset($_SERVER['QUERY_STRING']) && strpos(urldecode($_SERVER['QUERY_STRING']), chr(0)) !== false) {
            die();
        }
        if (@ ini_get('register_globals') && isset($_REQUEST)) {
            while (list($key, $value) = each($_REQUEST)) {
                $GLOBALS[$key] = null;
                unset($GLOBALS[$key]);
            }
        }
        $targets = array('PHP_SELF', 'HTTP_USER_AGENT', 'HTTP_REFERER', 'QUERY_STRING');
        foreach ($targets as $target) {
            $_SERVER[$target] = isset($_SERVER[$target]) ? htmlspecialchars($_SERVER[$target], ENT_QUOTES) : null;
        }
        if (get_magic_quotes_gpc()) {
            $process = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
            while (list($key, $val) = each($process)) {
                foreach ($val as $k => $v) {
                    unset($process[$key][$k]);
                    if (is_array($v)) {
                        $process[$key][stripslashes($k)] = $v;
                        $process[] = &$process[$key][stripslashes($k)];
                    } else {
                        $process[$key][stripslashes($k)] = stripslashes($v);
                    }
                }
            }
            unset($process);
        }
    }

    public function dbConnect($dsn, $username = '', $password = '', $driver_options = array())
    {
        $driver = strtolower(trim(substr($dsn, 0, strpos($dsn, ':'))));

        if ($driver && class_exists('PDO') && extension_loaded('pdo_'.$driver)) {
            $class = 'PDO';
            $this->driver_name = $class;
        } else {
            require_once dirname(__FILE__).'/db/phppdo.php';
            $class = 'PHPPDO';
        }

        $db = new $class($dsn, $username, $password, $driver_options);

        $this->driver_name = ($class == 'PHPPDO') ? $db->get_driver_name() : $class;

        return $db;
    }

    public static function loadClass($className, $packageName = '', $constructor = '')
    {
        if (empty($constructor)) {
            $constructor = $className;
        }
        if (!is_callable(array($className, $constructor))) {
            $pkg = dirname(__FILE__)."/{$packageName}/";
            if (is_dir($pkg)) {
                $class = $pkg.$className.".php";
                if (file_exists($class)) {
                    include_once $class;
                }
            }
        }

        return $className;
    }

    public function start($_smarty = true, $_session = true, $_nocache = false, $lang_pkgs = array('ROOT'))
    {
        if (!$this->initialized) {
            $this->loadClass('cache', 'tools');
            $this->loadClass('translator', 'tools');
            
            $this->getConfig();

            $this->cache = new Cache_Lite(
                array(
                    'cacheDir' => CACHE_PATH,
                    'lifeTime' => 120,
                    'automaticSerialization' => true,
                    'automaticCleaningFactor' => 1,
                )
            );

            if ($_nocache == true || (boolean) $this->getOption('caching',false) == false) {
                $this->cache->setOption('caching', false);
            }

            $this->getCatgories();
            $this->getPages();
            
            
            Translator::init($this->getLangsArray($lang_pkgs));

            $this->types = array_map('tr', $this->types);

            date_default_timezone_set($this->getOption('timezone', 'Africa/Cairo'));

            if ($_session == true) {
                $this->loadClass('nocsrf','tools');
                
                $this->session_start();
                
                if($this->isLoggedIn() == false && isset($_COOKIE['logged_in']) == true){
                    $cookie =  $_COOKIE['logged_in'];
                    if ($cookie) {
                        list ($user, $token, $mac) = explode(':', $cookie);
                        if ($mac !== hash_hmac('sha256', $user . ':' . $token, SECRET_KEY)) {
                            return false;
                        }
                        if ($userData = $this->db->users[$user]) {
                            if (self::timingSafeCompare($userData['token'], $token)) {
                                self::LogInUser($user);
                            }                            
                        }
                    }
                }

                $this->userData['isLoggedIn'] = false;

                if ($this->isLoggedIn()) {
                    if ($this->userData = $this->FullUserData($this->Userid())) {           
                        $this->userData['isLoggedIn'] = true;   
                        $this->userData['unReadMessages'] = $this->db->messages('user_id', $this->Userid())->where('unread', 1)->count('id');
                    } else {
                        $this->logout();
                    }
                }
            }

            if ($_smarty == true) {
                $this->loadClass('smarty', 'Smarty');

                $this->smarty = new smarty();
                $this->smarty->template_dir = $this->getOption('base_path').'tpl';
                $this->smarty->compile_dir  = $this->getOption('cache_path');
                $this->smarty->plugins_dir  = array(
                    $this->getOption('base_path').'lib/Smarty/plugins',
                );

                $this->smarty->assign('config', $this->config);
                $this->smarty->assign('upload',
                    array(
                       'mime_types_pkgs' => $this->mime_types,
                       'mime_types' => $this->get_mime_types(),
                       'extensions' => $this->get_extensions(),
                       'max_file_size' => (int) $this->getOption('max_file_size', 20) * 1024 * 1024,
                    )
                );

                $this->smarty->assign('logo', $this->logoHtml());
                $this->smarty->assign('user', $this->userData);
                $this->smarty->assign('return_url', $this->return_url());
                $this->smarty->assign('types', $this->get_types());
                $this->smarty->assign('adsArray', $this->adsArray());
                $this->smarty->assign('Navbars', $this->getNavbars());
                $this->smarty->assign('catgories', $this->catgories_tree());
                $this->smarty->assign('db_statistics', $this->db_statistics());
                $this->smarty->assign('isMobile', $this->isMobileDevice());
                $this->smarty->assign('availableLangs', $this->availableLangs());
                $this->smarty->assign('LoginProviders', $this->LoginProviders());
                $this->smarty->assign('EnabledLoginProviders', $this->EnabledLoginProviders());

                $this->smarty->assign('grid',
                    array(
                        'main' => $this->getOption('main_col', 10),
                        'sidebar' => 12 - $this->getOption('main_col', 10),
                    )
                );

                $this->smarty->display('functions.tpl');
            }
            
            if($_session && $_smarty){
                if($this->isLoggedIn()){
                    if((int) $this->userData['status'] == 0){
                        $this->fullMsgPage(tr('account_disabled'));
                    }
                    if((int) $this->userData['status'] == 2 && in_array(basename($_SERVER["SCRIPT_FILENAME"],'.php'),array('account','logout')) == false){
                        $this->go_to('account.html');
                    }                    
                }
            }

            $this->initialized = true;
        }
    }

    public function db_statistics()
    {
        $stats = array(
            'posts' => 0,
            'userposts' => 0,
            'users' => 0

        );
        if (($stats = $this->cache->get('db_statistics')) == false) {
            if((boolean) $this->getOption('public_stats') == true){
                $stats['posts'] = (int) $this->db->posts(array('published' => 1))->count('id');
                $stats['users'] = (int) $this->db->users()->count('id');  
            }

            if ($this->isLoggedIn()) {
                $stats['userposts'] = (int) $this->db->posts('createdby_id', $this->Userid())->count('id');
            }
            $this->cache->save($stats, 'db_statistics');
        }

        return $stats;
    }

    public function logoHtml()
    {
        $logo = '<span>'.$this->getOption('site_name').'</span>';
        $logo_file_id = (int) $this->getOption('logo_file_id');
        if ($logo_file_id > 0) {
            if (($logo = $this->cache->get('logoHtml_'.$logo_file_id)) == false) {
                if ($logo_file_id > 0 && $logo_file = $this->db->files[$logo_file_id]) {
                    $logo = $this->image(
                        $logo_file['file_physical_name'],
                        '',
                        true,
                        array(
                            'title' => $this->getOption('site_name'),
                            'class' => 'img-responsive'
                        )
                    );
                    $this->cache->save($logo, 'logoHtml_'.$logo_file_id);
                }
            }
        }

        return sprintf('<a class="header-logo navbar-brand navbar-brand-top" href="%s">%s</a>', $this->getOption('site_url'), $logo);
    }

    public function getCatgory($id, $field = '')
    {
        $id = (int) $id;
        if (count($this->catgories) == 0) {
            return false;
        }
        $catgory = (isset($this->catgories[$id]) == true) ? $this->catgories[$id] : $this->catgories[1];
        if ((int) $catgory['parent'] > 0) {
            $catgory['parent_catgory'] = $this->getCatgory($catgory['parent']);
        }
        $catgory['deep'] = array($id);
        foreach (self::search_array_kv($this->catgories, 'parent', $id) as $sub) {
            $catgory['subcategories'][$sub['id']] = $sub;
            $catgory['deep'][] = (int) $sub['id'];
        }

        return (empty($field) == false) ? $catgory[$field] : (array) $catgory;
    }

    public static function search_array_kv($array, $key, $value)
    {
        $results = array();

        if (is_array($array)) {
            if (isset($array[$key]) && $array[$key] == $value) {
                $results[] = $array;
            }

            foreach ($array as $subarray) {
                $results = array_merge($results, self::search_array_kv($subarray, $key, $value));
            }
        }

        return $results;
    }

    public function getCatgoryByPrefix($prefix = '')
    {
        return current(self::search_array_kv($this->catgories, 'prefix', $prefix));
    }

    public function getCatgories()
    {
        if (($catgories = $this->cache->get('catgories')) == false) {
            $catgories = array();
            foreach ($this->db->cats()->order('`title`') as $cat_row) {
                $catgories[$cat_row['id']] = array(
                    'id' => $cat_row['id'],
                    'parent' => (int) $cat_row['parent_id'],
                    'title' => $cat_row['title'],
                    'prefix' => $cat_row['prefix'],
                    'url' => $this->url($cat_row['prefix'], 'index', 'html'),
                    'posts' => 0,
                    'subcategories' => array(),
                );
            }
            $this->cache->save($catgories, 'catgories');
        }

        $this->catgories = $catgories;
    }

    public function catgories_select_tree()
    {
        $cats = array();
        foreach ($this->catgories_tree() as $parent) {
            $cats[$parent['id']] = (count($parent['subcategories']) > 0) ? '+ '.$parent['title'] : $parent['title'];
            foreach ($parent['subcategories'] as $sub) {
                $cats[$sub['id']] = "  ¦    {$sub['title']}";
            }
        }

        return $cats;
    }

    public function catgories_tree()
    {
        $cats = array();
        $data_source = (array) $this->catgories;
        foreach ($this->catsToTree($data_source) as $cat) {
            $sub = array();
            $posts = $cat['posts'];
            foreach ($cat['subcategories'] as $sub) {
                $posts += $sub['posts'];
                $cats[$cat['id']]['subcategories'][$sub['id']] = $sub;
            }
            $cat['posts'] = $posts;
            $cats[$cat['id']] = $cat;
        }

        return $cats;
    }

    public function catsToTree(&$categories)
    {
        $map = array(
            0 => array('subcategories' => array()),
        );

        foreach ($categories as &$category) {
            $category['subcategories'] = array();
            $map[$category['id']] = &$category;
        }

        foreach ($categories as &$category) {
            $map[$category['parent']]['subcategories'][$category['id']] = &$category;
        }

        return $map[0]['subcategories'];
    }

    public function delete_catgories($ids = array(), $delete_posts = false)
    {
        foreach ($this->db->cats('id', $ids) as $cat_row) {
            if ($delete_posts == true) {
                foreach ($this->db->posts('cat_id', $cat_row['id']) as $post_row) {
                    $this->delete_post($post_row['id']);
                }
            }
            $subs = $this->db->cats('parent_id', $cat_row['id'])->fetchPairs('id', 'title');
            if (count($subs) > 0) {
                $this->delete_catgories(array_keys($subs), $delete_posts);
            }
            $cat_row->delete();
        }
    }

    /**
     * Pages
     */

    public function getPages()
    {
        if (($pages = $this->cache->get('pages')) == false) {
            $pages = array();
            foreach ($this->db->pages() as $page_row) {
                $pages[$page_row['id']] = array(
                    'id' => $page_row['id'],
                    'type' => $page_row['type'],
                    'title' => $page_row['title'],
                    'prefix' => $page_row['prefix'],
                );
            }
            $this->cache->save($pages, 'pages');
        }

        $this->pages = $pages;
    }
    
    public function getPage($id)
    {
        $page = array(
            'type' => '',
            'prefix' => ''
        );
        if(isset($this->pages[$id])){
            $page = $this->pages[$id];
        }
        return $page;
    }
    
    /**
     * Navbars
     */

    public function getNavbars()
    {
        $arr = array(
            'header' => array(),
            'footer' => array(),
        );
        $cache_key = $this->cache_key('Navbars', true);
        if (($arr = $this->cache->get($cache_key)) == false) {
            foreach (array('header', 'footer') as $navbar) {
                if ($data = $this->getNavbarData($navbar)) {
                    $arr[$navbar] = $this->getNavbarArray($data);
                }
            }
            $this->cache->save($arr, $cache_key);
        }

        return $arr;
    }

    public function getNavbarArray($data)
    {
        $result = array();
        foreach ($data as $key => $value) {
            $url = $title = '';
            if ($value['type'] == 1) {
                $title = $value['title'];
                $url = $value['url'];
            } elseif ($value['type'] == 2) {
                $title = $value['title'];
                $page = $this->getPage($value['page_id']);

                if ((int) $page['type'] == 1) {
                    if ($page['prefix'] == 'home') {
                        $url = $this->getOption('site_url');
                    } elseif ($page['prefix'] == 'login' || $page['prefix'] == 'register' || $page['prefix'] == 'recover') {
                        if ($this->isLoggedIn() == true || ($page['prefix'] == 'register' && (boolean) $this->getOption('registration',true) == false)) {
                            continue;
                        }
                        $url = sprintf(
                                '%s?return=[return_url]',
                                $this->url($page['prefix'], 'html')
                            );
                    } elseif ($page['prefix'] == 'panel') {
                        if ($page['prefix'] == 'panel' && $this->isManager() == false) {
                            continue;
                        }
                        $url = $this->url($page['prefix'], 'html');
                    } elseif ($page['prefix'] == 'account') {
                        if ($this->isLoggedIn() == false) {
                            continue;
                        }
                        $url = $this->url($page['prefix'], 'html');
                    } elseif ($page['prefix'] == 'logout') {
                        $title = sprintf($value['title'], $this->userData['username']);
                        if ($this->isLoggedIn() == false) {
                            continue;
                        }
                        $url = sprintf(
                                '%s?return=[return_url]',
                                $this->url($page['prefix'], 'html')
                            );
                    } else {
                        $ext = ($page['prefix'] == 'rss') ? 'xml' : 'html';
                        $url = $this->url($page['prefix'], $ext);
                    }
                } elseif ((int) $page['type'] == 2) {
                    $url = $this->url('pages', $page['prefix'], 'html');
                }
            } elseif ($value['type'] == 3) {
                $title = (empty($value['title']) == false) ? $value['title'] : $this->getCatgory($value['cat_id'], 'title');
                $url = $this->getCatgory($value['cat_id'], 'url');
            }
            $result[$key] = array(
                    'title' => ($value['noicon'] == 'true' && empty($title) == true) ? tr('untitled') : $title,
                    'icon' => (empty($value['icon']) == false && $value['noicon'] == 'false') ? sprintf('<i class="fa %s"></i>', $value['icon']) : '',
                    'url' => $url,
                );
            if (isset($value['children']) == true) {
                $result[$key]['children'] = $this->getNavbarArray($value['children']);
            }
        }

        return $result;
    }

    public static function is_current_url($url)
    {
        $relative_current = self::untrailingslashit($_SERVER['REQUEST_URI']);

        $current_url = URL_SCHEME.HTTP_HOST.$relative_current;
        $raw_url = strpos($url, '#') ? substr($url, 0, strpos($url, '#')) : $url;
        $_indexless = self::untrailingslashit(preg_replace('/'.preg_quote('index.php', '/').'$/', '', $current_url));

        $item_url = self::untrailingslashit($raw_url);

        if ($raw_url && in_array($item_url, array( $current_url, $_indexless, $relative_current ))) {
            return true;
        }

        return false;
    }

    public function getNavbarData($prefix = 'header')
    {
        $data = $this->getOption('navbar_'.$prefix);
        if (empty($data) == false) {
            $data = json_decode($data, true);
            if (is_array($data) == true) {
                $this->addIndexRecursive($data);

                return $data;
            }
        }

        return;
    }

    public function addIndexRecursive(&$data)
    {
        static $index = 0;
        foreach ($data as &$item) {
            $index++;
            $item = array('index' => $index) + $item;
            if (isset($item['children']) == true) {
                $this->addIndexRecursive($item['children'], $index);
            }
        }
    }

    public static function untrailingslashit($string)
    {
        return rtrim($string, '/\\');
    }

    /**
     * POSTS
     */

    public function delete_sources($ids = array(), $delete_posts = false)
    {
        foreach ($this->db->sources('id', $ids) as $source_row) {
            if ($delete_posts == true) {
                foreach ($this->db->posts('source_id', $source_row['id']) as $post_row) {
                    $this->delete_post($post_row['id']);
                }
            }
            $source_row->delete();
        }
    }

    public function post_db_row($data, $options = array(), $fresh = false)
    {
        $rows = $this->post_db_rows($data, $options, $fresh);

        return (is_array($rows) == true) ? current($rows) : false;
    }

    public function post_db_rows($data, $options = array(), $fresh = false)
    {
        if ($data instanceof NotORM_Result) {
            $id_arr = $rows = array();

            $cache_id = $this->cache_key((string) $data,true,$options);

            if ((($rows = $this->cache->get($cache_id)) == false) || $fresh == true) {
                $id_arr = $rows = $conditions = array();

                if (isset($options['all']) == false) {
                    $conditions['published'] = 1;
                }

                foreach ($data->where($conditions) as $value) {
                    $id_arr[] = $value['id'];
                    $attrs = array('post_source' => '','files' => '');

                    foreach ($this->db->postattrs('post_id', $value['id']) as $attr_val) {
                        switch ($attr_val['attr_key']){ 
                        	case 'files':
                                $attrs[$attr_val['attr_key']] = json_decode($attr_val['attr_value'],true);
                        	break;
                        
                        	default :
                                $attrs[$attr_val['attr_key']] = $attr_val['attr_value'];
                        }                        
                    }

                    $catgory = $this->getCatgory($value['cat_id']);

                    $title = (empty($value['title']) == false) ? strip_tags($value['title']) : tr('untitled');
                    $type = (int) $value['type'];

                    $permalink_type = (int) $this->getOption('permalink_type', 1);

                    if (empty($value['name']) == true) {
                        $permalink_type = 1;
                    }

                    switch ($permalink_type) {
                        case 1:
                            $url = $this->url($catgory['prefix'], $value['id'], 'html');
                        break;

                        case 2:
                            $url = $this->url($catgory['prefix'], $value['name'], 'html');
                        break;
                    }

                    if ($type == 8) {
                        $url = $value['link'];
                    }

                    if (isset($options['simple']) == true) {
                        $value['description'] = $this->limit_words(
                            $this->strip_tags($value['description']),
                            20,
                            sprintf(
                                '<a href="%s"> ... %s</a>',
                                $url,
                                tr('more')
                            )
                        );
                    }

                    $time = ((int) $value['publishedon'] > 0) ? $value['publishedon'] : $value['createdon'];

                    $rows[$value['id']] = array(
                        'id' => $value['id'],
                        'type' => $type,
                        'provider' => $value['provider'],
                        'link' => $value['link'],
                        'title' => $title,
                        'description' => $value['description'],
                        
                        'post_source' => $attrs['post_source'],
                        
                        'url' => $url,
                        'abridge_url' => ($permalink_type == 2 && empty($value['name']) == false) ? $this->url($catgory['prefix'], $this->abridgeStr($value['name'], 10), 'html') : $url,
                        'cat' => $catgory,

                        'file' => ((int) $value['file_id'] > 0 && $value->file instanceof Traversable) ? iterator_to_array($value->file) : '',

                        'views' => $this->Kformat($value['views']),
                        'comments' => $this->Kformat($value['comments']),

                        'time' => $this->TimeFormat($time, (isset($options['simple']) == false) ? true : false),
                        'unixtime' => $time,

                        'published' => (boolean) $value['published'],
                        'publishedon' => (int) $value['publishedon'],

                        'require_player' => false,

                        'vote' => array(
                            'total' => 0,
                            'votes' => 0,
                            'percentage' => 0,
                            'upvoted' => false,
                            'downvoted' => false,
                        ),
                        'manage' => ((int) $value['createdby_id'] == $this->Userid() || $this->isManager() == true),
                    );

                    if ($value['source_id'] > 0 && isset($attrs['author_name']) == true) {
                        $rows[$value['id']]['author'] = (isset($options['simple']) == true) ? $attrs['author_name'] : $this->AuthorFormat($attrs['author_name'], $attrs['author_url']);
                    } else {
                        if(isset($options['simple']) == true){
                            $rows[$value['id']]['author'] = $value->createdby['username'];
                        }else{
                            $createdby = $this->FullUserData($value['createdby_id'],array(),false);
                            $rows[$value['id']]['author'] = $this->AuthorFormat($createdby['displayName'],$createdby['profileURLs']['profile']);
                        }
                    }

                    if(isset($options['author_row']) == true && $value['createdby_id'] > 0){
                        $rows[$value['id']]['author_row'] = (isset($createdby)) ? $createdby : $this->FullUserData($value['createdby_id'],array(),false);
                    }

                    $thumb = '';
                    switch ($type) {
                        case 1:
                            $thumb =  $value->file['file_physical_name'];
                        break;
                    }
                    if ((int) $value['thumb_id'] > 0) {
                        $thumb = $value->thumb['file_physical_name'];
                    }
                    $rows[$value['id']]['thumb'] = $thumb;

                    $rows[$value['id']]['absolute_url'] = $this->absolute_url($url);

                    if (isset($options['simple']) == false) {
                        $content = '';
                        $post_source = (empty($attrs['post_source']) == false) ? tr('post_source').' : '.$attrs['post_source'] : '';
                        $base_content = (empty($value['content']) == false) ? $this->_embed($value['content']).' '.$post_source : '<p>'.nl2br($value['description']).'</p>';

                        switch ($type) {
                            case 1:
                                $content .= $this->image(
                                    $value->file['file_physical_name'],
                                    '',
                                    true,
                                    array(
                                        'class' => 'img-responsive',
                                        'title' => $title,
                                        'alt' => $title,
                                    )
                                );
                                $content .= $base_content;
                            break;

                            case 2:
                                if (empty($value['provider']) == true) {
                                    $content .= $this->_embed($value['content']);
                                } else {
                                    $content .= $value['content'];
                                    $content .= '<p>'.nl2br($value['description']).'</p>';
                                }
                                if (self::get_xml_tag('object', $content) || self::get_xml_tag('audio', $content) || self::get_xml_tag('video', $content)) {
                                    $rows[$value['id']]['require_player'] = true;
                                }
                            break;

                            case 3:
                                $content .= $this->flash_object($this->_uploaded('url', $value->file['file_physical_name']));
                                $content .= $base_content;
                            break;

                            case 5:
                                $content .= $this->video_object($value->file, $this->_uploaded('url', $thumb));
                                $content .= $base_content;
                               $rows[$value['id']]['require_player'] = true;
                            break;

                            case 6:
                                $content .= $this->audio_object($value->file);
                                $content .= $base_content;
                                $rows[$value['id']]['require_player'] = true;
                            break;

                            case 7:
                                $token_data = array(
                                    'token' => sha1(uniqid()),
                                    'post_id' => $value['id'],
                                );

                                $this->cache->save($token_data, 'file_download_'.$value['file_id']);

                                $content .= $this->file_download_table($value->file, $token_data);
                            break;
                            
                            case 9:
                                $rows[$value['id']]['images'] = $slides = array();
                                foreach ($this->db->files('id',$attrs['files']) as $file) {
                                	$rows[$value['id']]['images'][] = $slides[] = $this->image(
                                        $file['file_physical_name'],
                                        '',
                                        true,
                                        array(
                                            'class' => 'swiper-lazy img-responsive',
                                            'title' => $title,
                                            'alt' => $title,
                                        ),
                                        'data-src'
                                    );
                                }        
                                   
                                $content .= $this->swiper_object(
                                    array('slides' => $slides)
                                );
                                $content .= $base_content;

                            break;
                        }

                        $rows[$value['id']]['content'] = $content;
                        
                        if(isset($options['next_prev']) == true){
                            if ($next = $this->post_db_row(
                                $this->db->posts('id',
                                    $this->db->posts("id > {$value['id']}")->where(array('cat_id' => $value['cat_id']))->min('id')
                                ),
                                array('simple' => true)
                            )) {
                                $rows[$value['id']]['next'] = $next;
                            }
    
                            if ($prev = $this->post_db_row(
                                $this->db->posts('id',
                                    $this->db->posts("id < {$value['id']}")->where(array('cat_id' => $value['cat_id']))->max('id')
                                ),
                                array('simple' => true)
                            )) {
                                $rows[$value['id']]['prev'] = $prev;
                            }                            
                        }
                        if (isset($options['related']) == true) {
                            $rows[$value['id']]['related'] = $this->post_db_rows(
                                $this->db->posts("id != {$value['id']}")->where(array('cat_id' => $value['cat_id']))->order('RAND()')->limit($this->getOption('related_per_page', 16)),
                                array('simple' => true)
                            );
                        }

                        $rows[$value['id']]['count_comments'] = (round((time() - $value['comments_update']) / 3600, 0) > 1) ? true : false;
                    }
                }
                $this->cache->save($rows, $cache_id);
            } else {
                foreach ($rows as $row) {
                    $id_arr[] = $row['id'];
                }
            }

            foreach ($this->db->votes('post_id', $id_arr)->select('`post_id`,COUNT(id) AS `votes` , SUM(`vote`) AS `total`')->group('`post_id`') as $vote_row) {
                $rows[$vote_row['post_id']]['vote'] = array(
                    'total' => $vote_row['total'],
                    'votes' => $vote_row['votes']
                );
                if($vote_row['votes'] > 0){
                    $rows[$vote_row['post_id']]['vote']['percentage'] = round(($vote_row['total'] / $vote_row['votes']) * 5,1);
                }
                if (isset($options['update_votes']) == true) {
                    $value->update(array('votes' => $vote_row['total']));
                }
            }

            if ($this->isLoggedIn() == true) {
                foreach ($this->db->votes('post_id', $id_arr)->where('user_id', $this->Userid())->select('vote,post_id') as $vote_user_row) {
                    $rows[$vote_user_row['post_id']]['vote']['upvoted'] = ((int) $vote_user_row['vote'] == 1);
                    $rows[$vote_user_row['post_id']]['vote']['downvoted'] = ((int) $vote_user_row['vote'] == -1);
                }
            }

            return (is_array($rows) == true) ? array_values($rows) : array();
        }

        return false;
    }

    public function insert_post_to_db($data)
    {
        $attrs = (array) $data['attrs'];
        unset($data['attrs']);

        if ($inserted_post = $this->db->posts()->insert($data)) {
            foreach ($attrs as $attr_key => $attr_val) {
                $this->db->postattrs()->insert(
                    array(
                        'post_id' => $inserted_post['id'],
                        'attr_key' => $attr_key,
                        'attr_value' => $attr_val,
                    )
                );
            }

            return $inserted_post;
        }

        return false;
    }

    public function update_db_post($id, $data)
    {
        $post_data = $this->db_post_array($data, true, $id);

        $attrs = (array) $post_data['attrs'];

        unset($post_data['attrs']);
        
        foreach ($attrs as $attr_key => $attr_value) {
        	if($attr = $this->db->postattrs('post_id',$id)->where('attr_key',$key)->fetch()){
        	   $attr->update(array('attr_value' => $attr_value));
        	}else{
        	   $this->db->postattrs()->insert(
                    array(
                        'post_id' => $id,
                        'attr_key' => $attr_key,
                        'attr_value' => $attr_value
                    )
               );
        	}	
        }

        return $this->db->posts('id', $id)->update($post_data);
    }

    public function db_post_array($data = array(), $update = false, $post_id = 0)
    {
        $result = array('attrs' => array());

        foreach (array('hash', 'title', 'provider', 'link', 'type', 'source_id', 'cat_id', 'thumb_id', 'description', 'content', 'attrs', 'createdby_id') as $field) {
            if (isset($data[$field]) == true) {
                $result[$field] = $data[$field];
            }
        }
        
        if (isset($data['post_source']) == true) {
            $result['attrs']['post_source'] = $data['post_source'];
        }
        
        if (isset($data['files']) == true) {
            $result['attrs']['files'] = json_encode($data['files']);  
             
            $first_file_row = $this->db->files[$data['files'][0]]; 
            if (isset($result['title']) == false) {
                $result['title'] =  $first_file_row['file_clean_name'];
            }  
            $first_file_type = $this->mime_to_posttype($first_file_row['file_mime_type']);
            if ($first_file_type == 1) {
                $result['thumb_id'] = $first_file_row['id'];
                $result['type'] = 9;
            }            
        }

        if (isset($data['file']) == true) {
            if (isset($result['title']) == false) {
                $result['title'] =  $data['file']['file_clean_name'];
            }
            $result['file_id'] = $data['file']['id'];
            $result['type'] = $this->mime_to_posttype($data['file']['file_mime_type']);

            if ($result['type'] == 1) {
                $result['thumb_id'] = $result['file_id'];
            }
        }

        if (isset($data['name']) == true) {
            $result['name'] =  $this->post_name_gen($data['name'], $post_id);
        } elseif (isset($result['title']) == true) {
            $result['name'] = $this->post_name_gen($result['title'], $post_id);
        }

        if (isset($data['published']) == true) {
            $result['published'] = ((boolean) $data['published'] == true) ? 1 : 0;
            if ((boolean) $data['published'] == true) {
                $result['publishedon'] =  time();
                $result['publishedby_id'] = $this->Userid();
            }
        }

        if ($update == false) {
            if (isset($result['hash']) == false) {
                $result['hash'] = (isset($data['link']) == true) ? md5($data['link']) : md5($result['title']);
            }
            if (isset($result['createdby_id']) == false) {
                $result['createdby_id'] = $this->Userid();
            }
            $result['createdon'] = time();
        }

        return $result;
    }

    public function urlinfo_array($url, $process = true)
    {
        require dirname(__FILE__).DS.'Embera'.DS.'Autoload.php';
        $embera = new \Embera\Embera();

        if (($url_info = $embera->getUrlInfo($url)) == false) {
            throw new \Exception(tr('url_not_supprted'));
        }

        $data = current($url_info);

        $records = array(
            'type' => 2,
            'provider' => strtolower($data['provider_name']),
            'link' => $url,
            'title' => '',
            'description' => '',
            'content' => '',
            'thumb_id' => 0,
            'attrs' => array(),
        );

        $content = '';
        if (isset($data['html']) == true) {
            $content = $data['html'];
            if (self::get_xml_tag('object', $content) || self::get_xml_tag('iframe', $content)) {
                $embera = new \Embera\Formatter($embera);
                $embera->setTemplate(self::responsive_embed('{html}'));
                $content = $embera->transform($url);
            }
        }
        $records['content'] = $content;

        if (isset($data['title']) == true) {
            $records['title'] = $this->plaintext($data['title']);
        }

        foreach (array('author_name', 'author_url', 'duration') as $attr_key) {
            if (isset($data[$attr_key]) == true) {
                $records['attrs'][$attr_key] = $data[$attr_key];
            }
        }

        if (isset($data['description']) == true) {
            $records['description'] = $data['description'];
        }

        switch (strtolower($data['provider_name'])) {
            case 'flickr':
            case 'deviantart':
            case 'instagram':
                if (isset($data['url']) == false) {
                    return false;
                }
                $records['file'] = $records['thumb_id'] = $this->grabImage($data['url'], false, true, array('title' => $records['title']), $process);
            break;

            case 'twitter':
                $DOM = $this->HTML_DOMXPath($data['html']);
                $title = $this->strip_tags($this->HTML_XPathNode($DOM, '//p'));
                $records['title'] = $this->plaintext($title);

                $records['content'] = $data['html'];
                $records['type'] = 2;
            break;
        }

        if (isset($data['thumbnail_url']) == true) {
            $thumb_key = 'thumbnail_url';
        } elseif (isset($data['thumbnail']) == true) {
            $thumb_key = 'thumbnail';
        }

        if (isset($thumb_key) == true && $records['thumb_id'] == 0) {
            if ($thumb_arr = $this->grabImage($data[$thumb_key], false, true, array('title' => $records['title']), $process)) {
                $records['thumb_id'] = ($process == true) ? $thumb_arr['id'] : $thumb_arr;
            }
        }

        return $records;
    }

    public function youTubeRss($developer_key,$data = array()){
        
      if(empty($developer_key) || count($data) == 0){
        return false;
      }  
        
      require_once realpath(dirname(__FILE__) . '/Google/autoload.php');
      
      $client = new Google_Client();
      $client->setDeveloperKey($developer_key);

      $youtube = new Google_Service_YouTube($client);

      try {
        
        $items = array(); 
        
        $terms = array(
          'type' => 'video',  
          'maxResults' => 20
        );
        
        if(isset($data['forUsername'])){
            $channelResponse = $youtube->channels->listChannels('id', array('forUsername' => $data['forUsername']));
            if(isset($channelResponse['items'][0]['id'])){
                $terms['channelId'] = $channelResponse['items'][0]['id'];
            }
        }
        
        if(isset($data['q'])){
            $terms['q'] = $data['q'];
        }

        $searchResponse = $youtube->search->listSearch('id,snippet', $terms);
    
        foreach ($searchResponse['items'] as $searchResult) {
          $items[] = array(
            'title' => $searchResult['snippet']['title'],
            'description' => $searchResult['snippet']['description'],
            'url' => sprintf('https://www.youtube.com/watch?v=%s',$searchResult['id']['videoId']),
            'pubDate' => strtotime($searchResult['snippet']['publishedAt'])
          );
        }
        
        include dirname(__FILE__).DS.'RSSWriter'.DS.'Autoload.php';
        
        $feed = new RSSWriter\Feed();
        $channel = new RSSWriter\Channel();
        $channel->title('youTube')->appendTo($feed);

        foreach ($items as $rss_item) {
            $item = new RSSWriter\Item();
            $item
                ->title($rss_item['title'])
                ->description($rss_item['description'])
                ->url($rss_item['url'])
                ->pubDate($rss_item['pubDate'])
                ->guid($rss_item['url'], true);

            $item->appendTo($channel);
        }
        
        return $feed;

      } catch (Google_Service_Exception $e) {

      } catch (Google_Exception $e) {

      }

      return false;
    }

    public function getFeedURL($type, $data)
    {
        $lang = $this->getLang();
        switch ((int) $type) {
         case 0:
            $url = $data['rss_url'];
         break;
         case 1:
            $url = $this->youTubeRss($data['google_dev_key'],array('q' => $data['youtube_tag']));
         break;
         case 2:
            $url = $this->youTubeRss($data['google_dev_key'],array('forUsername' => $data['youtube_channel']));
         break;
         case 3:
            $url = 'http://api.flickr.com/services/feeds/photos_public.gne?tags='.$data['flickr_tag'].'&format=rss_200_enc';
         break;
         case 4:
            $url = 'http://api.flickr.com/services/feeds/groups_pool.gne?id='.$data['flickr_groupid'].'&format=rss_200';
         break;
         case 5:
            $url = 'http://api.flickr.com/services/feeds/photos_faves.gne?id='.$data['flickr_userid'].'&format=rss_200';
         break;
         case 6:
            $url = 'http://api.flickr.com/services/feeds/photoset.gne?set='.$data['flickr_setid'].'&nsid='.$data['flickr_userid'].'&format=rss_200';
         break;
         case 7:
            $url = 'http://api.flickr.com/services/feeds/photos_public.gne?id='.$data['flickr_userid'].'&tags='.$data['flickr_tag'].'&format=rss_200';
         break;
         case 8:
            $url = 'http://feeds.soundcloud.com/users/soundcloud:users:'.$data['soundcloud_userid'].'/sounds.rss';
         break;
         case 9:
            $url = 'http://gdata.youtube.com/feeds/api/standardfeeds/most_viewed';
         break;
         case 10:
            $url = 'http://gdata.youtube.com/feeds/api/standardfeeds/top_rated?max-results=30&time=today';
         break;
         case 11:
            $url = 'http://vimeo.com/'.$data['username'].'/videos/rss';
         break;
         case 12:
            $url = 'http://vimeo.com/tag:'.$data['hashtag'].'/rss';
         break;

         case 13:
            $url = 'http://www.dailymotion.com/rss/user/'.$data['username'].'/1';
         break;
         case 14:
            $url = 'http://dailymotion.com/rss/us/visited-week/featured/1';
         break;
         case 15:
            $url = 'http://www.funnyordie.com/videos.rss';
         break;
      }

        return $url;
    }

    public function SimplePie($feedurl)
    {
        if (class_exists('SimplePie') == false) {
            $this->loadClass('simplepie', 'tools');
        }

        $feed = new SimplePie();

        $feed->force_feed(true);
        $feed->enable_cache(true);
        $feed->set_cache_duration();
        $feed->set_cache_location(CACHE_PATH);

        $feed->set_timeout(60);

        $feed->set_stupidly_fast(true);
        $feed->enable_order_by_date(true);

        if($feedurl instanceof RSSWriter\Feed){
            $tmpfname = tempnam(CACHE_PATH, "YTRSS_");
            file_put_contents($tmpfname, $feedurl);                    
            $feed->set_file(new SimplePie_File($tmpfname)); 
            @unlink($tmpfname);         
        }elseif($this->is_absolute_url($feedurl)){
            $feed->set_feed_url($feedurl);
        }

        $feed->init();

        return $feed;
    }

    public function FetchRSS($source_id = 0, $items_num = 2, $sleep = 3, $process = true, $item = null)
    {
        if (($source = $this->db->sources[$source_id]) == false) {
            return 0;
        }

        $terms = (array) json_decode($source['terms'], true);

        $new_posts = 0;
        $skipped_posts = 0;
        $started = microtime(true);

        $result = array();

        foreach ($terms as $key => $value) {
            $feedurl = $this->getFeedURL($value['type'], $value['term']);
            $feed = $this->SimplePie($feedurl);
            $item_quantity = ($items_num == 0) ? $feed->Get_item_quantity() : min($items_num, $feed->Get_item_quantity());

            $start = 0;

            if (is_null($item) == false) {
                $start = $item;
                $item_quantity = $item + 1;
            }

            $result[$key] = array(
                'feedurl' => $feedurl,
                'quantity' => $item_quantity,
                'fresh' => false,
                'items' => array(),
            );

            for ($i = $start; $i < $item_quantity; $i++) {
                if (($item = $feed->get_item($i)) == false) {
                    throw new \Exception(tr('item_not_exists'));
                }
                $hash = $item->get_id(true);
                $title = $item->get_title();
                $permalink = $item->get_permalink();

                if ($row = $this->post_db_row(
                    $this->db->posts('hash', $hash),
                    array('simple' => true, 'all' => true),
                    true
                )) {
                    $result[$key]['items'][] = array(
                        'fresh' => false,
                        'row' => $row,
                    );
                    $skipped_posts++;
                } else {
                    if ($post_data1 = $this->urlinfo_array($permalink, $process)) {
                        $post_data2 = array(
                            'hash' => $hash,
                            'source_id' => $source_id,
                            'cat_id' => $value['cat_id'],
                            'createdby_id' => $value['user_id'],
                            'published' => true,
                        );
                        $result[$key]['items'][] = array(
                            'fresh' => true,
                            'row' => $this->array_merge_recursive_distinct($post_data1, $post_data2),
                        );
                        $new_posts++;
                    }
                }
                sleep($sleep);
            }
            $feed->__destruct();
            unset($item);
            unset($feed);
        }

        $info = array(
            'started' => date(DATE_RSS, $started),
            'time' => round((microtime(true) - $started) / 100, 4),
            'new_posts' => $new_posts,
            'skipped_posts' => $skipped_posts,
        );

        if (function_exists('memory_get_usage')) {
            $info['memory_usage'] = $this->bytesToSize(memory_get_usage(true));
        }

        if (function_exists('sys_getloadavg')) {
            $loadavg = sys_getloadavg();
            $info['getloadavg'] = $loadavg[0];
        }

        return array(
            'result' => current($result),
            'info' => $info,
        );
    }

    public function HTML_DOMXPath($html)
    {
        $dom = new DOMDocument();
        $dom->loadHTML('<?xml encoding="utf-8" ?>'.$html);

        return new DOMXPath($dom);
    }

    public function HTML_XPathNode($domxpath, $xpath, $outer = true)
    {
        $html = '';
        foreach ($domxpath->query($xpath) as $tag) {
            $html .= ($outer == true) ? $this->HTML_getNodeOuter($tag) : $this->HTML_getNodeInner($tag);
        }

        return $html;
    }

    public function HTML_getNodeOuter($n)
    {
        $d = new DOMDocument('1.0');
        $b = $d->importNode($n->cloneNode(true), true);
        $d->appendChild($b);

        return html_entity_decode($d->saveHTML(), ENT_COMPAT, 'UTF-8');
    }

    public function HTML_getNodeInner(DOMNode $oNode)
    {
        $oDom = new DOMDocument();
        foreach ($oNode->childNodes as $oChild) {
            $oDom->appendChild($oDom->importNode($oChild, true));
        }

        return html_entity_decode($oDom->saveHTML(), ENT_COMPAT, 'UTF-8');
    }

    public static function get_xml_tag($tag, $xml)
    {
        $tag = preg_quote($tag);
        preg_match_all('{<'.$tag.'[^>]*>(.*?)</'.$tag.'>}', $xml, $matches, PREG_PATTERN_ORDER);

        return $matches[1];
    }

    public function is_time_cron($time, $cron)
    {
        $cron_parts = explode(' ', $cron);
        if (count($cron_parts) != 5) {
            return false;
        }

        list($min, $hour, $day, $mon, $week) = explode(' ', $cron);

        $to_check = array('min' => 'i' , 'hour' => 'G' , 'day' => 'j' , 'mon' => 'n' , 'week' => 'w');

        $ranges = array(
            'min' => '0-59' ,
            'hour' => '0-23' ,
            'day' => '1-31' ,
            'mon' => '1-12' ,
            'week' => '0-6' ,
        );

        foreach ($to_check as $part => $c) {
            $val = $$part;
            $values = array();

            if (strpos($val, '/') !== false) {
                list($range, $steps) = explode('/', $val);

                if ($range == '*') {
                    $range = $ranges[$part];
                }
                list($start, $stop) = explode('-', $range);

                for ($i = $start; $i <= $stop; $i = $i + $steps) {
                    $values[] = $i;
                }
            } else {
                $k = explode(',', $val);

                foreach ($k as $v) {
                    if (strpos($v, '-') !== false) {
                        list($start, $stop) = explode('-', $v);

                        for ($i = $start; $i <= $stop; $i++) {
                            $values[] = $i;
                        }
                    } else {
                        $values[] = $v;
                    }
                }
            }

            if (!in_array(date($c, $time), $values) and (strval($val) != '*')) {
                return false;
            }
        }

        return true;
    }

    public function strip_tags($data)
    {
        return strip_tags($data);
    }

    public function post_name_gen($name, $post_id = 0)
    {
        if ((int) $this->getOption('permalink_type', 1) != 2) {
            return '';
        }

        if ((boolean) $this->getOption('permalink_translate', false) == true && $this->getLang() == 'ar') {
            $this->loadClass('Arabic', 'I18N');
            $Arabic = new I18N_Arabic('Transliteration');
            $name = $Arabic->ar2en($name);
        }

        $name = ($this->isNumber($name) == true) ? 'post_'.$name : $name;

        $post_name = $this->seo_str($name);

        $c = array('name' => $post_name);

        if ($post_id > 0) {
            $c['id != ?'] = $post_id;
        }

        $posts_num = (int) $this->db->posts($c)->count('id');

        if ($posts_num > 0) {
            $post_name .= '_'.($posts_num + 1);
        }

        return $post_name;
    }

    public function uniqueID()
    {
        $uniqueID_file = $this->getOption('base_path').'cache'.DS.'uniqueID.php';

        if (file_exists($uniqueID_file) == true) {
            $uniqueID = include $uniqueID_file;
        } else {
            $uniqueID = substr(number_format(time() * rand(), 0, '', ''), 0, 5);
            file_put_contents($uniqueID_file, sprintf('<?php return %s; ?>', $uniqueID));
        }

        return $uniqueID;
    }

    public function count_disqus_comments($url)
    {
        $count = 0;
        if (DEV_MODE) {
            return $count;
        }     
        self::loadClass('disqusapi','Disqusapi');
        
        $secret_key = $this->getOption('disqus_secret_key');
        $shortname = $this->getOption('disqus_shortname');
        
        if(empty($secret_key) == false && empty($shortname) == false){
            try{
                $disqus = new DisqusAPI($secret_key);
                $obj = $disqus->threads->details(array('forum'=> $shortname,'thread' => 'link:'.$url));
                $count = ($obj->posts) ? $obj->posts : 0;
            } catch (Exception $e) {
                
            }            
        }

        return $count;
    }

    public function count_fb_comments($url)
    {
        $count = 0;
        if (DEV_MODE) {
            return $count;
        }

        try{
            $data = $this->loadUrl("https://graph.facebook.com/v2.1/?fields=share{comment_count}&id=".urlencode($url));
            $json = json_decode($data);
            $count = ($json->share->comment_count) ? $json->share->comment_count : 0;            
        } catch (Exception $e) {
            
        }
        
        return $count;
    }
    
    function dataTableLoader($id,$columns = array()){
        
        $this->jsFile(
            array(
                'datatable/js/jquery.dataTables.min.js',
                'datatable/js/dataTables.bootstrap.js', 
                'datatable/js/dataTables.responsive.min.js'               
            )
        )->cssFile(
            array(
                //'datatable/css/jquery.dataTables.css',
                'datatable/css/dataTables.bootstrap.css',
                'datatable/css/dataTables.responsive.css',
            )
        );        
        
        $options = array(
            //'processing' => true,
            'serverSide' => true,
            'responsive' => true,
            'ajax' => array(
                'url' => 'panel.html?case=ajax&c=dataTable',
                //'type' => 'POST'
            ),
            'columns' => array()
        );
        
        foreach ($columns as $column_id => $column_val) {
        	$options['columns'][] = array('data' => $column_id);
        }        
        
        $this->html(
            $this->jQuery_code(
                sprintf(
                    '$("#'.$id.'").dataTable(%s);',
                    json_encode($options)
                )
            ),
        'footer');  
        
        $html = '<table id="'.$id.'" class="table table-striped table-bordered table-hover dt-responsive" cellspacing="0" width="100%">';       
        $html .= '<thead>';
            $html .= '<tr>';
                foreach ($columns as $key => $value) {
                	$html .= '<th>'.$value['title'].'</th>';
                }        
            $html .= '</tr>';
        $html .= '</thead>';
 
        $html .= '<tfoot>';
            $html .= '<tr>';
                foreach ($columns as $key => $value) {
                	$html .= '<th>'.$value['title'].'</th>';
                }  
            $html .= '</tr>';
        $html .= '</tfoot>';
        $html .= '</table>';
        
        return $html;
    }

    public function media_tab_loader($options = array())
    {
        $options = array_merge(array(
            'mime_pkgs' => array('image'),
            'group' => 1,
            'buttons' => array(
                array(
                    'class' => 'set-featured-image',
                    'text' => tr('set_featured_image'),
                    'attrs' => array(),
                ),
            ),
        ), $options);

        $this->jsLangFile(array('UPLOAD'));

        $this->jsFile(
            array(
                'uploader/plus/jquery.ui.widget.js',
                'uploader/plus/tmpl.min.js',
                'uploader/plus/load-image.all.min.js',
                'uploader/plus/jquery.iframe-transport.js',
                'uploader/basic/jquery.fileupload.js',
                'uploader/plus/jquery.fileupload-process.js',
                'uploader/plus/jquery.fileupload-audio.js',
                'uploader/plus/jquery.fileupload-image.js',
                'uploader/plus/jquery.fileupload-video.js',
                'uploader/basic/jquery.fileupload-validate.js',
                'uploader/plus/jquery.fileupload-ui.js',
                'uploader/plus/script.js',
            )
        )->cssFile(
            array(
                'uploader/plus/jquery.fileupload.css',
            )
        );

        $ajax_url = $this->getOption('base_url').'/ajax.html?c=fileupload';

        if (isset($options['mime_pkgs']) == true) {
            $ajax_url .= '&mime_pkgs='.implode(',', $options['mime_pkgs']);
        }

        if (isset($options['group']) == true) {
            $ajax_url .= '&group='.$options['group'];
        }

        if (isset($options['post_id']) == true) {
            $ajax_url .= '&post_id='.$options['post_id'];
        }

        $upload_options = array(
            'url' => $ajax_url,
        );

        $this->html(
            $this->javascript_code(
                sprintf(
                    "$(document).ready(function() { $('#fileupload').fileupload(%s); });",
                    json_encode($upload_options)
                )
            ), 'footer');

        $this->smarty->assign('media_tab', $options);
    }

    public function jqCron_loader($selector)
    {
        $lang = (in_array($this->getLang(), array('bg', 'en', 'de', 'ar', 'fr', 'ja', 'pl')) == true) ? $this->getLang() : 'en';

        $this->jsFile('js/jqcron/jqCron.js')
             ->jsFile('js/jqcron/jqCron.'.$lang.'.js')
             ->cssFile('css/jqCron.css')
             ->html(
                 $this->jQuery_Code('var $selector = $("'.$selector.'");$selector.attr("type","hidden");$( "<div class=\'jqCron-obj\'></div>" ).insertBefore($selector).jqCron({enabled_minute: true,multiple_dom: true,multiple_month: true,multiple_mins: true,multiple_dow: true,multiple_time_hours: true,multiple_time_minutes: true,default_period: "week",no_reset_button: false,lang: "'.$lang.'",default_value: $selector.val(),numeric_zero_pad: true,bind_to: $selector,bind_method: {set: function($element, value) {$element.val(value);}}});'), 'footer');
    }

    public function loadUrl($url, array $params = array())
    {
        if (class_exists('Http_Request') == false) {
            require dirname(__FILE__).DS.'tools'.DS.'httprequest.php';
        }
        $Request = new Http_Request();

        return $Request->fetch($url, $params);
    }

    public function grabImage($url, $local = false, $db_save = false, $attrs = array(), $process = true)
    {
        if ($process == false) {
            return $url;
        }
        if (empty($url)) {
            return false;
        }

        if ($local == true) {
            if (($raw = file_get_contents($url)) == false) {
                return false;
            }
        } else {
            if (($raw = $this->loadUrl($url,
                array(
                    'curl' => array(
                        CURLOPT_HEADER => 0,
                        CURLOPT_BINARYTRANSFER => 1,
                    ),
                )
            )) == false) {
                return false;
            }
        }

        $tmp_file_hash = substr(md5(time()*rand()), 0, 10);
        $tmp_file_path = $this->_uploaded('path', $tmp_file_hash);

        $file_handle = fopen($tmp_file_path, 'w');
        fwrite($file_handle, $raw);
        fclose($file_handle);

        $file_size = filesize($tmp_file_path);

        if (function_exists('exif_imagetype') == true) {
            $exif_ob = exif_imagetype($tmp_file_path);
            $mime_type = image_type_to_mime_type($exif_ob);
            $extension = image_type_to_extension($exif_ob, false);
        } elseif ($image_info = getimagesize($tmp_file_path)) {
            $mime_type = $image_info['mime'];
            $extension = $this->mime_type_to_extension($mime_type);
        }

        if (isset($extension) && isset($mime_type)) {
            $file_new_name = $tmp_file_path.'.'.$extension;
            if (rename($tmp_file_path, $file_new_name)) {
                if ($db_save) {
                    $title = (isset($attrs['title']) == true) ? $attrs['title'] : basename($url);
                    $image_obj = $this->db->files()->insert(
                        array(
                            'user_id' => $this->Userid(),
                            'file_real_name' => $title,
                            'file_clean_name' => $title,
                            'file_physical_name' => $tmp_file_hash.'.'.$extension,
                            'file_size' => $file_size,
                            'file_mime_type' => $mime_type,
                            'file_extension' => $extension,
                            'file_time' => time(),
                        )
                    );

                    return iterator_to_array($image_obj);
                }

                return $file_new_name;
            }
        }
        if (is_file($tmp_file_path)) {
            unlink($tmp_file_path);
        }

        return false;
    }

    protected function upcountNameCallback($matches)
    {
        $index = isset($matches[1]) ? intval($matches[1]) + 1 : 1;
        $ext = isset($matches[2]) ? $matches[2] : '';

        return '_('.$index.')'.$ext;
    }

    protected function upcountName($name)
    {
        return preg_replace_callback(
            '/(?:(?: \(([\d]+)\))?(\.[^.]+))?$/',
            array($this, 'upcountNameCallback'),
            $name,
            1
        );
    }

    function UploadedName($name){
        switch ($this->getOption('uploaded_file_name','random')){ 
            default :
        	case 'real':
                //$name = preg_replace("([^\w\s\d\-_~,;:\[\]\(\).])", '', $name);
                //$name = preg_replace("([\.]{2,})", '', $name);            
                
                while (is_file($this->_uploaded('path',$name))) {
                    $name = $this->upcountName($name);
                }             
        	break;
        
        	case 'random':
                $name = $this->randFileName($name);
        	break;	
        }        
        return $name;
    }

    static function randFileName($name){
        $extension = pathinfo($name, PATHINFO_EXTENSION);
        return substr(md5(time()*rand()), 0, 10).'.'.$extension;    	
    }

    public function get_types()
    {
        return (array) $this->types;
    }

    public function get_type_name($type)
    {
        return $this->types[(int) $type];
    }

    public function AuthorFormat($name, $url = '')
    {
        if (empty($url) == true) {
            $url = $this->absolute_url($this->getOption('base_url'));
        }

        return sprintf(
            '<span itemprop="author" itemscope="" itemtype="http://schema.org/Person"><span itemprop="name"><a href="%s" itemprop="url" rel="author">%s</a></span></span>',
            $url,
            $name
        );
    }

    public function Kformat($number)
    {
        $prefixes = 'kMGTPEZY';
        if ($number >= 1000) {
            for ($i = -1; $number >= 1000; ++$i) {
                $number /= 1000;
            }

            return floor($number).tr('kformat_'.$prefixes[$i]);
        }

        return $number;
    }

    public function TimeFormat($timestamp, $publish = false)
    {
        $attrs = array(
            'class' => 'timeago',
            'datetime' => date('c', $timestamp),
            //'rel' => 'tooltip',
        );
        if ($publish == true) {
            $attrs['itemprop'] = 'datePublished';
        }

        return sprintf(
            '<time %s>%s</time>',
            implode(' ', array_map(function ($k, $v) {return $k.'="'.$v.'"';}, array_keys($attrs), $attrs)),
            $this->date($timestamp)
        );
    }

    public function timezone_list()
    {
        $zones_array = array();
        $timestamp = time();
        foreach (timezone_identifiers_list() as $key => $zone) {
            date_default_timezone_set($zone);
            $zones_array[$key]['zone'] = $zone;
            $zones_array[$key]['diff_from_GMT'] = 'UTC/GMT '.date('P', $timestamp);
        }

        return $zones_array;
    }

    public function date($timestamp, $format = '', $mode = 0)
    {
        $date_format = (empty($format) == false) ? $format : $this->getOption('date_format');
        if ($this->getLang() == 'ar') {
            if (isset($this->I18N_date) == false) {
                $this->loadClass('arabic', 'I18N');
                $this->I18N_date = new I18N_Arabic('Date');
            }
            $this->I18N_date->setMode(
                ($mode > 0) ? $mode : $this->getOption('date_mode')
            );

            return $this->I18N_date->date(
                $date_format,
                $timestamp
             );
        }

        return date($date_format, $timestamp);
    }

    public static function bytesToSize($bytes, $precision = 2)
    {
        $kilobyte = 1024;
        $megabyte = $kilobyte * 1024;
        $gigabyte = $megabyte * 1024;
        $terabyte = $gigabyte * 1024;

        if (($bytes >= 0) && ($bytes < $kilobyte)) {
            return $bytes.' '.tr('byte');
        } elseif (($bytes >= $kilobyte) && ($bytes < $megabyte)) {
            return round($bytes / $kilobyte, $precision).' '.tr('kb');
        } elseif (($bytes >= $megabyte) && ($bytes < $gigabyte)) {
            return round($bytes / $megabyte, $precision).' '.tr('mb');
        } elseif (($bytes >= $gigabyte) && ($bytes < $terabyte)) {
            return round($bytes / $gigabyte, $precision).' '.tr('gb');
        } else {
            return $bytes.tr('byte');
        }
    }

    public function abridgeStr($str, $len = 14)
    {
        $str_abridged = '';
        if (function_exists('mb_strlen')) {
            if (mb_strlen(utf8_decode($str)) > 30) {
                $str_abridged = mb_substr($str, 0, $len).'&hellip;'.mb_substr($str, -$len);
            } else {
                $str_abridged = $str;
            }
        } else {
            if (strlen(utf8_decode($str)) > 30) {
                $str_abridged = substr($str, 0, $len).'&hellip;'.substr($str, -$len);
            } else {
                $str_abridged = $str;
            }
        }

        return $str_abridged;
    }

    public function isNumber($integer)
    {
        return (boolean) preg_match("/^\-?\+?[0-9e1-9]+$/", $integer);
    }

    /**
     * TRANSLATIONS
     */

    public function jsLangFile($pkgs = array())
    {
        $this->html(
            $this->javascript_file(
                sprintf('%s/lang_pkgs.js?pkgs=%s', $this->getOption('base_url'), implode(',', $pkgs))
            ),
        'header');
    }

    public function getLangsArray($pkg = array('ROOT'))
    {
        $arr = array();
        if (count($pkg) == 0) {
            return $arr;
        }
        $cache_id = implode('_', $pkg).$this->getLang();
        if (($arr = $this->cache->get($cache_id)) == false) {
            foreach ($this->db->langs('CONCAT( ",", pkg, "," )REGEXP ",('.implode('|', $pkg).'),"') as $value) {
                $arr[$value['var_key']] = $value[$this->getLang()];
            }
            $this->cache->save($arr, $cache_id);
        }

        return (array) $arr;
    }

    public function getLang()
    {
        return $this->getOption('lang', 'en');
    }

    public function availableLangs()
    {
        return (array) $this->langs;
    }

    function langsInfo(){
        $l = array_fill_keys(array_keys($this->availableLangs()), 0);;
        $total = 1;
        foreach ($this->db->langs() as $lang_row) {
            foreach ($this->availableLangs() as $lang_code => $lang_title) {
            	if(empty($lang_row[$lang_code]) == false){
            	   $l[$lang_code]++;
            	}
            }
            $total++;	
        }
        
        $langs = array();
        foreach ($this->availableLangs() as $lang_code => $lang_title) {
            $percent = ceil($l[$lang_code]/$total * 100);
        	$langs[$lang_code]['title'] = tr($lang_title);
        	$langs[$lang_code]['num'] = $l[$lang_code];
        	$langs[$lang_code]['percent'] = $percent;
        }

        return $langs;    	
    }

    public function get_locale()
    {
        $lang = $this->getLang();
        switch ($lang) {
            case 'en':
                $locale = 'en_US';
            break;

            default:
                $locale = $lang.'_'.strtoupper($lang);
        }

        return $locale;
    }

    /**
     *  UPLOADING & FILES
     */

    public function _uploaded($type = 'path', $filename = '')
    {
        switch ($type) {
            case 'path':
                $r = $this->getOption('base_path').'uploads/';
            break;

            case 'url':
                $r = $this->getOption('site_url')."/uploads/";
            break;

            case 'resized':
                $r = $this->getOption('site_url')."/resized/";
            break;
        }
        if (empty($filename) == false) {
            $r .= $filename;
        }

        return $r;
    }

    public function delete_uploaded($file_name)
    {
        $file = $this->_uploaded('path', $file_name);
        if (file_exists($file) == true) {
            unlink($file);
        }
    }

    public function delete_post($id)
    {
        if ($row = $this->db->posts[$id]) {
            $this->delete_files($row['file_id'], $row['thumb_id']);
            $this->db->votes('id', $id)->delete();
            $row->delete();
        }
    }

    public function delete_files()
    {
        foreach (func_get_args() as $id) {
            if ($file = $this->db->files[$id]) {
                $path = $this->_uploaded('path', $file['file_physical_name']);
                if (is_file($path) == true) {
                    unlink($path);
                }
            }
        }
    }

    public static function copy($source, $dest)
    {
        if (function_exists('copy') == true) {
            return copy($source, $dest);
        } else {
            $status = true;

            $contentx = file_get_contents($source);
            $openedfile = fopen($dest, "w");
            fwrite($openedfile, $contentx);
            fclose($openedfile);

            if ($contentx === false) {
                $status = false;
            }

            return $status;
        }
    }

    public function mime_to_posttype($mime)
    {
        $type = 0;
        $pkg = $this->mime_to_pkg($mime);
        if (empty($pkg) == false) {
            switch ($pkg) {
                case 'video':
                    $type = 5;
                break;

                case 'audio':
                    $type = 6;
                break;

                case 'image':
                    $type = 1;
                break;

                case 'flash':
                    $type = 3;
                break;

                default:
                    $type = 7;
            }
        } else {
            $mime_type = explode('/', $mime);
            switch ($mime_type[0]) {
                case 'video':
                    $type = 5;
                break;

                case 'audio':
                    $type = 6;
                break;

                case 'image':
                    $type = 1;
                break;
            }
        }

        return $type;
    }

    public function mime_to_pkg($mime)
    {
        foreach ($this->mime_types as $pkg => $mime_types) {
            foreach (array_keys($mime_types) as $mime_type) {
                if (strpos($mime_type, $mime) !== false) {
                    return $pkg;
                }
            }
        }

        return '';
    }

    public function get_mime_types()
    {
        $mime_types = array();
        foreach (call_user_func_array('array_merge', $this->mime_types) as $mime_type => $extension) {
            if (strpos($mime_type, '|') === false) {
                $mime_types[$mime_type] = $extension;
            } else {
                foreach (explode('|', $mime_type) as $sub_mime_type) {
                    $mime_types[$sub_mime_type] = $extension;
                }
            }
        }

        return $mime_types;
    }

    public function mime_types_pkgs()
    {
        return array_keys($this->mime_types);
    }

    public function mime_type_to_extension($mime_type)
    {
        $mime_types = $this->get_mime_types();

        return (isset($mime_types[$mime_type]) == true) ? $mime_types[$mime_type] : '';
    }

    public function get_extensions($pkgs = array())
    {
        $extensions = array();
        if (count($pkgs) > 0) {
            foreach ($pkgs as $pkg) {
                $extensions = array_merge($extensions, array_values($this->mime_types[$pkg]));
            }
        } else {
            foreach ($this->mime_types as $pkg => $mime_types) {
                $extensions = array_merge($extensions, array_values($mime_types));
            }
        }

        return $extensions;
    }

    public static function cleanTmp($dir, $exclude = array(), $expire_time = 12)
    {
        $result = true;
        foreach (glob($dir.DS.'*') as $filename) {
            if (in_array(basename($filename), $exclude) == true) {
                continue;
            }
            if ((time() - @filectime($filename)) > ($expire_time * 60)) {
                $result = ($result and (@unlink($filename)));
            }
        }

        return $result;
    }

    public function readfile($file_path, $chunk_size = 1048576)
    {
        $file_size = filesize($file_path);
        if ($chunk_size && $file_size > $chunk_size) {
            $handle = fopen($file_path, 'rb');
            while (!feof($handle)) {
                echo fread($handle, $chunk_size);
                @ob_flush();
                @flush();
            }
            fclose($handle);

            return $file_size;
        }

        return readfile($file_path);
    }

    /**
     * HTML
     */

    public function image($filename = '', $size = '', $img = false, $atts = array(),$src_atrr = 'src')
    {
        if (empty($filename) == true) {
            return;
        }
        $url = (empty($size) == false) ? $this->_uploaded('resized', $size.'_'.$filename) : $this->_uploaded('url', $filename);

        if (isset($atts['title']) == true && isset($atts['alt']) == false) {
            $atts['alt'] = $atts['title'];
        }

        if ($img) {
            $url = self::image_object($url,$atts,$src_atrr);
        }

        return $url;
    }
    
    static function image_object($url = '',$atts = array(),$src_atrr = 'src'){
        $html = '<img '.$src_atrr.'="'.$url.'"';
        foreach ($atts as $key => $val) {
            $html .= ' '.$key.'="'.$val.'"';
        }
        $html .= ' />';  
        return $html;  	
    }

    public function flash_object($src, $width = 640, $height = 360)
    {
        $html = sprintf(
            '<object id="player" width="%s" height="%s" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" ><param name="movie" value="%s" ></param><param name="allowFullScreen" value="true" ></param><param name="allowscriptaccess" value="always"></param><embed src="%s" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="%s" height="%s"></embed></object>',
            $width,
            $height,
            $src,
            $src,
            $width,
            $height
        );

        return self::responsive_embed($html);
    }

    public function swiper_object($data = array()){
        
        $dir = ((boolean) $this->getOption('rtl')) ? 'rtl' : 'ltr';
        
        $html = '<div class="swiper-container" dir="'.$dir.'">';
            $html .= '<div class="swiper-wrapper">';
                foreach($data['slides'] as $slide){
                    $html .= '<div class="swiper-slide">'.$slide.'<div class="swiper-lazy-preloader"></div></div>';
                }
            $html .= '</div>';
        
            $html .= '<div class="swiper-pagination"></div>';
            $html .= '<div class="swiper-button-prev"><i class="fa"></i></div>';
            $html .= '<div class="swiper-button-next"><i class="fa"></i></div>';
        $html .= '</div>';  
        
        return $html; 	
    }

    public function file_download_table($file, $token_data)
    {
        $html = '<dl class="dl-horizontal">';

        $html .= '<dt>'.tr('file_name').'</dt>';
        $html .= '<dd>'.$file['file_real_name'].'</dd>';

        $html .= '<dt>'.tr('file_size').'</dt>';
        $html .= '<dd>'.$this->bytesToSize($file['file_size']).'</dd>';

        $html .= '<dt>'.tr('date').'</dt>';
        $html .= '<dd>'.$this->TimeFormat($file['file_time']).'</dd>';

        $html .= '<dt></dt>';
        $html .= sprintf(
                '<dd><a class="btn btn-sm btn-primary" href="%s">%s</a></dd>',
                $this->url('download.php?id='.$file['id'].'&token='.$token_data['token']),
                tr('download')
            );

        $html .= '</dl>';

        return $html;
    }

    public function audio_object($file)
    {
        return sprintf(
            '<audio id="player" type="%s" src="%s" controls="controls"></audio>',
            $file['file_mime_type'],
            $this->_uploaded('url', $file['file_physical_name'])
        );
    }

    public function video_object($file, $poster = '')
    {
        $swf_player = $this->assets_file('player/flashmediaelement.swf');
        $file_source = $this->_uploaded('url', $file['file_physical_name']);

        $player = '<video width="640" height="360" style="width: 100%; height: 100%;" poster="poster.jpg" controls="controls" preload="none">';

        $player .= sprintf(
            '<source type="%s" src="%s" />',
            $file['file_mime_type'],
            $file_source
        );

        $player .= sprintf('<object width="320" height="240" type="application/x-shockwave-flash" data="%s">', $swf_player);
        $player .= sprintf('<param name="movie" value="%s" />', $swf_player);
        $player .= sprintf(
                '<param name="flashvars" value="%s" />',
                http_build_query(
                    array(
                        'controls' => 'true',
                        'poster' => $poster,
                        'file' => $file_source,
                    )
                )
            );
        if (empty($poster) == false) {
            $player .= sprintf('<img src="%s" width="320" height="240" title="%s" />', $poster, $file['file_real_name']);
        }
        $player .= '</object>';

        $player .= '</video>';

        return self::responsive_embed($player);
    }

    public static function responsive_embed($html)
    {
        return '<div class="embed-responsive embed-responsive-16by9 ">'.$html.'</div>';
    }

    public function supported_providers()
    {
        $providers = array_map(function ($provider) {
            $provider = basename($provider, ".php");

            return $provider;
        },
            glob(dirname(__FILE__).DS.'Embera'.DS.'Providers'.DS.'*.php')
        );

        return $providers;
    }

    public function _embed($content)
    {
        include dirname(__FILE__).DS.'Embera'.DS.'Autoload.php';
        $embera = new \Embera\Embera();

        $embera = new \Embera\Formatter($embera);
        $embera->setTemplate(self::responsive_embed('{html}'));
        //$content = $embera->transform($content);

        return $embera->transform($content);
    }

    public function adsArray()
    {
        if (($ads = $this->cache->get('adsArray')) == false) {
            $ads = array();
            foreach ($this->db->ads() as $ad_row) {
                $ads[$ad_row['size']][] = array(
                    'id' => $ad_row['id'],
                    'code' => $ad_row['code'],
                );
            }
            $this->cache->save($ads, 'adsArray');
        }

        return (array) $ads;
    }

    /**
     * AUTH
     */

    function delete_wall_post($id){
    	if($post = $this->db->wall_posts[$id]){
    	   if(empty($post['image']) == false){
    	       $this->delete_uploaded($post['image']);
    	   }
           $post->delete();
    	}
    }
    
    function wall_posts_db_rows($data, $options = array(), $fresh = false)
    {
        if ($data instanceof NotORM_Result) {
            $id_arr = $uid_arr = $rows = array();
    
            $cache_id = $this->cache_key((string) $data,true,$options);
    
            if ((($rows = $this->cache->get($cache_id)) == false) || $fresh == true) {
                $id_arr = $uid_arr = $rows = $conditions = array();
    
                foreach ($data->where($conditions)->order('id DESC') as $value) {
                    $uid_arr[] = $value['createdby'];
                    $id_arr[] = $value['id'];
                    $rows[$value['id']] = array(
                        'id' => $value['id'],
                        'type' => $value['type'],
                        'message' => $value['message'],
                        'location' => $value['location'],
                        'lat' => $value['lat'],
                        'lng' => $value['lng'],
                        'image' => $value['image'],
                        'video_url' => $value['video_url'],
                        'createdon' => $this->TimeFormat($value['createdon']),
                        'createdby' => (int) $value['createdby'],
                        'user_data' => array(),                        
                    );
                    
                    $content = '<p>'.$value['message'].'</p>';
                    
                    if($value['type'] == 'photos'){
                        $content .= $this->image($value['image'],'auto-height-medium',true,array('class' => 'img-responsive'));
                    }
                    
                    if($value['type'] == 'videos'){
                        $content .= $this->_embed($value['video_url']);
                    }
                    
                    if($value['type'] == 'location'){
                        $content .= self::image_object('http://maps.googleapis.com/maps/api/staticmap?size=500x250&maptype=roadmap\&markers=size:mid%7Ccolor:red%7C'.$value['lat'].','.$value['lng'].'&zoom=7&sensor=false',array('class' => 'img-responsive'));
                    }
                    
                    $rows[$value['id']]['content'] = $content;
                }
                
                $uid_arr = array_unique($uid_arr);
                
                $users = $this->UserDataRows($this->db->users('id',$uid_arr),array(),true);

                foreach($id_arr as $id){
                    $rows[$id]['user_data'] = $users[$rows[$id]['createdby']];
                }
                
           }
           
           if($this->isLoggedIn() == true){
                foreach ((array) $rows as $row) {
                    $roles = ($this->Userid() == $row['createdby'] || $this->isManager());
                	$rows[$row['id']]['roles'] = $roles;
                    if($roles == true){
                        $rows[$row['id']]['csrf'] = NoCSRF::generate( 'wall_posts_'.$row['id'] );
                    }
                }            
           }
           return $rows;
        }
    }


    protected function session_start()
    {
        if (!in_array($this->getSessionState(), array(Webfairy::SESSION_STATE_INITIALIZED, Webfairy::SESSION_STATE_EXTERNAL, Webfairy::SESSION_STATE_UNAVAILABLE), true)) {
            $cookieDomain = $this->getOption('session_cookie_domain', '');
            $cookiePath = $this->getOption('session_cookie_path', '/');
            $cookieSecure = (boolean) $this->getOption('session_cookie_secure', false);
            $cookieLifetime = (integer) $this->getOption('session_cookie_lifetime', 0);
            $gcMaxlifetime = (integer) $this->getOption('session_gc_maxlifetime', $cookieLifetime);

            if ($gcMaxlifetime > 0) {
                ini_set('session.gc_maxlifetime', $gcMaxlifetime);
            }
            $site_sessionname = $this->getOption('session_name', null);
            if (!empty($site_sessionname)) {
                session_name($site_sessionname);
            }
            session_set_cookie_params($cookieLifetime, $cookiePath, $cookieDomain, $cookieSecure);
            session_start();
            $this->sessionState = Webfairy::SESSION_STATE_INITIALIZED;
            $cookieExpiration = 0;
            if (isset($_SESSION['Webfairy.session.cookie.lifetime'])) {
                $sessionCookieLifetime = (integer) $_SESSION['Webfairy.session.cookie.lifetime'];
                if ($sessionCookieLifetime !== $cookieLifetime) {
                    if ($sessionCookieLifetime) {
                        $cookieExpiration = time() + $sessionCookieLifetime;
                    }
                    setcookie(session_name(), session_id(), $cookieExpiration, $cookiePath, $cookieDomain, $cookieSecure);
                }
            }
        }
    }
    
    
    function FullUserData($id,$options = array(),$fresh = true){
        $rows = $this->UserDataRows(
            $this->db->users('id',$id),
            $options,
            $fresh
        );
        return current((array) $rows);    
    }    
    
    public function UserDataRows($data, $options = array(), $fresh = false)
    {
        $id_arr = $rows = array();
        
        if ($data instanceof NotORM_Result) {
            $cache_id = $this->cache_key((string) $data,true,$options);

            if ((($rows = $this->cache->get($cache_id)) == false) || $fresh == true) {
                $users = $attrs = $posts = array();
              
                foreach ($data as $row) {
                	$id_arr[] = $row['id'];
                    $rows[$row['id']] = $users[$row['id']] = array(
                        'id' => (int) $row['id'],
                        'status' => $row['status'],
                        'isManager' => (boolean) $row['manager'],
                        'username' => $row['username'],
                        'email' => $row['email'],
                        'created' => $this->TimeFormat(strtotime($row['created'])),
                        'photoURL' => 'https://lh5.googleusercontent.com/-b0-k99FZlyE/AAAAAAAAAAI/AAAAAAAAAAA/eu7opA4byxI/photo.jpg?sz=100',
                        'coverPhoto' => $this->getOption('base_url').'/assets/img/user-cover.jpg',
                        'profileURLs' => array(
                            'profile' => $this->url('users',$row['id'],'profile','html'),
                            'posts' => $this->url('users',$row['id'],'posts','html'),
                            'favorites' => $this->url('users',$row['id'],'favorites','html'),
                        ),
                        'socialURLs' => array()
                    );
                } 

                foreach ($id_arr as $id) {
                	$attrs[$id] = array(
                        'photoType' => 'email'
                    );
                    $posts[$id] = array();
                }

                foreach ($this->db->userattrs('user_id',$id_arr) as $attr) {
                	$attrs[$attr['user_id']][$attr['attr_key']] = $attr['attr_value'];
                } 
                if(isset($options['posts']) == true){
                    foreach ($this->db->posts('createdby_id',$id_arr)->fetchPairs('id','createdby_id') as $post_id => $user_id) {
                    	$posts[$user_id][] = $post_id;
                    }                    
                }

                foreach ($id_arr as $id) {
                    if(isset($options['cover']) == true){
                        if(isset($attrs[$id]['coverFile']) == true && (int) $attrs[$id]['coverFile'] > 0){
                            $coverFile = $this->db->files[$attrs[$id]['coverFile']];
                            $rows[$id]['coverPhoto'] = $this->_uploaded('url',$coverFile['file_physical_name']);
                        }                        
                    }

                    if(empty($attrs[$id]['displayName']) == false){
                        switch ($attrs[$id]['displayName']){ 
                        	case 'username':
                                $rows[$id]['displayName'] = $users[$id]['username'];
                        	break;
                        
                        	case 'firstlast':
                                $rows[$id]['displayName'] = implode(' ',array_filter(array($attrs[$id]['firstName'],$attrs[$id]['lastName'])));
                        	break;
                        
                        	case 'lastfirst':
                                $rows[$id]['displayName'] = implode(' ',array_filter(array($attrs[$id]['firstName'],$attrs[$id]['lastName'])));
                        	break;
                        }            
                    }else{
                        $rows[$id]['displayName'] = $users[$id]['username'];
                    }
                    
                    if($attrs[$id]['photoType'] == 'email' && empty($users[$id]['email']) == false){
                        $rows[$id]['photoURL'] = self::gravatar($users[$id]['email']);
                    }elseif(empty($attrs[$id][$attrs[$id]['photoType'].'_photoURL']) == false){
                        $rows[$id]['photoURL'] = $attrs[$id][$attrs[$id]['photoType'].'_photoURL'];
                    }
                    
                    foreach ($this->LoginProviders() as $provider) {
                    	if(empty($attrs[$id][$provider.'_profileURL']) == false){
                    	   $rows[$id]['socialURLs'][$provider] = $attrs[$id][$provider.'_profileURL'];
                    	}
                    }                    
            
                    foreach (array('webSiteURL','gender','description','phone','address','country','region','city','zip') as $key) {
                        if(empty($attrs[$id][$key]) == false){
                            $rows[$id][$key] = $attrs[$id][$key];
                        }        	
                    }

                    $rows[$id]['posts'] = count($posts[$id]);
            
                    if(isset($options['votes']) == true && isset($options['posts']) == true){
                        $votes = array(
                            'gave' => array(
                                'up' => 0,
                                'down' => 0    
                            ),
                            'received' => array(
                                'up' => 0,
                                'down' => 0    
                            )
                        );
                        
                        $votes['gave']['up'] = $this->db->votes('user_id',$id)->where('vote',1)->count('id');
                        $votes['gave']['down'] = $this->db->votes('user_id',$id)->where('vote',-1)->count('id');
                        
                        $votes['received']['up'] = $this->db->votes(array('post_id' => $posts[$id],'vote' => 1))->where('user_id != ?',$id)->count('id');
                        $votes['received']['down'] = $this->db->votes(array('post_id' => $posts[$id],'vote' => -1))->where('user_id != ?',$id)->count('id');
                        
                        $rows[$id]['votes'] = $votes;            
                    }
                    
                }
            }    
        }
        return $rows;
    }
    
    
    static function gravatar( $email, $s = 150, $d = 'mm', $r = 'g', $img = false, $atts = array() ) {
    	$url = 'http://www.gravatar.com/avatar/';
    	$url .= md5( strtolower( trim( $email ) ) );
    	$url .= "?s=$s&d=$d&r=$r";
    	if ( $img ) {
    		$url = self::image_object($url,$atts);
    	}
    	return $url;
    }    
    
    function LoginProviders(){
    	return (array) $this->login_providers;
    }
    
    function EnabledLoginProviders(){
        $providers = array();
        foreach ($this->LoginProviders() as $provider) {
            
            $enabled = $this->getOption($provider.'_login');
            $key = $this->getOption($provider.'_key');
            $secret = $this->getOption($provider.'_secret');
            
            if( (boolean) $enabled && empty($key) == false && empty($secret) == false)
            {
                $providers[] = $provider;
            }
            unset($enabled,$key,$secret);
            
        }
        return $providers;  	
    }    
    
    
    function timingSafeCompare($safe, $user) {
        $safe .= chr(0);
        $user .= chr(0);
    
        $safeLen = strlen($safe);
        $userLen = strlen($user);
    
        $result = $safeLen - $userLen;

        for ($i = 0; $i < $userLen; $i++) {
            $result |= (ord($safe[$i % $safeLen]) ^ ord($user[$i]));
        }

        return $result === 0;
    }
    
    public function RandomToken($n) {
        $str='';
        for ($i=0; $i<$n; $i++) {
            $str.=base_convert(mt_rand(1,36),10,36);
        }
        return $str;
    }

    public function getDbInfo($key)
    {
        return $this->db_info[$key];
    }

    public function getSessionState()
    {
        if ($this->sessionState !== Webfairy::SESSION_STATE_INITIALIZED) {
            if (headers_sent()) {
                $this->sessionState = Webfairy::SESSION_STATE_UNAVAILABLE;

                $this->sessionState = Webfairy::SESSION_STATE_EXTERNAL;
            }
        }

        return $this->sessionState;
    }

    public function createSalt()
    {
        $string = md5(uniqid(rand(), true));

        return substr($string, 0, 3);
    }

    public function validateUser($userid,$rememberme = false)
    {
        self::LogInUser($userid);

        if($rememberme){
            $token = self::RandomToken(16);
            $this->db->users('id',$userid)->update(array('token' => $token));
            $cookie = $userid . ':' . $token;
            $mac = hash_hmac('sha256', $cookie, SECRET_KEY);
            $cookie .= ':' . $mac;
            setcookie('logged_in', $cookie,strtotime( '+30 days' ));            
        }
        
    }

    function LogInUser($userid){
        session_regenerate_id();
        $_SESSION['valid'] = 1;
        $_SESSION['userid'] = (int) $userid;    	
    }

    public function getUserData($key)
    {
        return $this->userData[$key];
    }

    public function Userid()
    {
        return ($this->isLoggedIn() == true) ? (int) $_SESSION['userid'] : 0;
    }

    public function isManager()
    {
        return $this->userData['isManager'];
    }

    public function getManagers()
    {
        return $this->db->users('manager', 1)->fetchPairs('id', 'username');
    }

    public function isLoggedIn()
    {
        if (isset($_SESSION['valid']) && $_SESSION['valid'] && isset($_SESSION['userid'])) {
            return true;
        }

        return false;
    }

    public function logout()
    {
        if (isset($_COOKIE['logged_in'])) {
            unset($_COOKIE['logged_in']);
            setcookie('logged_in', null, -1);
        }        
        $_SESSION = array();
        session_destroy();
    }

    public function cache_key($key, $user = false,$o = array())
    {
        $arr = array($key);
        if ($user) {
            $arr[] = $this->Userid();
        }

        foreach ($o as $k => $v) {
        	$arr[] = $k.'_'.$v;
        }

        return implode('_', $arr);
    }

    public function mail($to = array(), $subject = '', $content = '')
    {
        require_once dirname(__FILE__).'/Mailer/class.phpmailer.php';

        $mail = new PHPMailer();

        $mail->CharSet = 'UTF-8';

        if ($this->config["mailer"] == 'smtp') {
            $mail->SMTPAuth = true;
            $mail->IsSMTP();
            $mail->Debugoutput = 'html';
            $mail->Host       = $this->config["smtp_host"];
            $mail->Port       = $this->config["smtp_port"];
            $mail->SMTPSecure = 'ssl';
            $mail->Username   = $this->config["smtp_username"];
            $mail->Password   = $this->config["smtp_password"];
        }

        $mail->SetFrom($this->config["site_email"], $this->config["site_name"]);

        $mail->AddAddress($to['email'], $to['name']);
        $mail->Subject = $subject;

        $content = nl2br($content);
        $mail->MsgHTML($content);

        if (!$mail->Send()) {
            echo $mail->ErrorInfo;
        }
    }

    public function ip()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return ip2long($ip);
    }

    function isMobileDevice(){
        $aMobileUA = array(
            '/iphone/i' => 'iPhone', 
            '/ipod/i' => 'iPod', 
            '/ipad/i' => 'iPad', 
            '/android/i' => 'Android', 
            '/blackberry/i' => 'BlackBerry', 
            '/webos/i' => 'Mobile'
        );
    
        //Return true if Mobile User Agent is detected
        foreach($aMobileUA as $sMobileKey => $sMobileOS){
            if(preg_match($sMobileKey, $_SERVER['HTTP_USER_AGENT'])){
                return true;
            }
        }
        //Otherwise return false..  
        return false;
    }

    public function plaintext($str, $quotes = true)
    {
        $str = trim($str);

        return ($quotes == true) ? htmlspecialchars($str, ENT_NOQUOTES, "UTF-8") : htmlspecialchars($str, ENT_QUOTES, "UTF-8");
    }

    public function OneLine($str)
    {
        return preg_replace("/\r?\n/", ' ', $str);
    }

    public static function array_merge_recursive_distinct(array &$array1, array &$array2)
    {
        $merged = $array1;

        foreach ($array2 as $key => &$value) {
            if (is_array($value) && isset($merged [$key]) && is_array($merged [$key])) {
                $merged [$key] = self::array_merge_recursive_distinct($merged [$key], $value);
            } else {
                $merged [$key] = $value;
            }
        }

        return $merged;
    }

    /**
     *  STR FUNCTIONS
     */

    public static function strlen($str)
    {
        return strlen(utf8_decode($str));
    }

    public static function limit_words($string, $word_limit = 5, $tail = '...')
    {
        $words = explode(" ", $string);
        $result = implode(" ", array_splice($words, 0, $word_limit));
        if (self::strlen($string) > self::strlen($result)) {
            $result .= $tail;
        }

        return $result;
    }

    public function keywords($str)
    {
        $str = $this->OneLine($str);

        $search = array('.',':','"',"'");

        $str = str_replace($search, '', $str);

        $tags = array();
        foreach (explode(" ", $str) as $tag) {
            if ($this->strlen($tag) >= 5) {
                $tags[] = $tag;
            }
        }

        return implode(",", $tags);
    }

    /**
     * OPTIONS FUNCTIONS
     */

    public function getOption($key, $default = null)
    {
        $option = $default;
        if (is_string($key) && !empty($key)) {
            if (is_array($this->config) && !empty($this->config) && array_key_exists($key, $this->config)) {
                $option = ($this->config[$key] !== '') ? $this->config[$key] : $default;
            }
        }

        return $option;
    }

    public function updateOption($key, $value)
    {
        return $this->db->options()->insert_update(
            array('name' => $key),
            array('name' => $key, 'value' => $value),
            array('value' => $value)
        );
    }

    public function getConfig()
    {
        if (!$this->initialized || !is_array($this->config) || empty($this->config)) {
            if (!isset($this->config['base_url'])) {
                $this->config['base_url'] = BASE_URL;
            }
            if (!isset($this->config['base_path'])) {
                $this->config['base_path'] = BASE_PATH;
            }
            if (!isset($this->config['cache_path'])) {
                $this->config['cache_path'] = CACHE_PATH;
            }
            if (!isset($this->config['url_scheme'])) {
                $this->config['url_scheme'] = URL_SCHEME;
            }
            if (!isset($this->config['http_host'])) {
                $this->config['http_host'] = HTTP_HOST;
            }
            if (!isset($this->config['site_url'])) {
                $this->config['site_url'] = SITE_URL;
            }
            if (!isset($this->config['https_port'])) {
                $this->config['https_port'] = isset($GLOBALS['https_port']) ? $GLOBALS['https_port'] : 443;
            }

            foreach ($this->db->options()->fetchPairs('name', 'value') as $name => $value) {
                $config[$name] = $this->plaintext($value);
            }

            if (is_array($config)) {
                $this->config = array_merge($this->config, $config);
            }

            foreach ($this->config as $key => $value) {
                if (isset($_COOKIE[$key]) == true) {
                    $this->config[$key] = $_COOKIE[$key];
                }
            }
        }

        return $this->config;
    }

    /**
     * FORMS FUNCTIONS
     */

    public function getValueIfExists($key, $data, $default = '')
    {
        if (is_array($data) == true) {
            if (array_key_exists($key, (array) $data) == true) {
                return $data[$key];
            }
        }

        return $default;
    }

    public function setDependsOn($field_id = '', $element_id = '', $values = array())
    {
        foreach ($values as $value) {
            $this->dependsOn[$element_id][$value][] = $field_id;
        }
        $this->html(
            $this->javascript_code(sprintf('$("#%s").dependsOn("#%s", %s );', $field_id, $element_id, json_encode($values))),
        'footer');
    }

    public function getDependsOnValues($element_id = '', $data = array())
    {
        if (isset($this->dependsOn[$element_id][$data[$element_id]]) == false) {
            return '';
        }
        $fields = array();
        foreach ($this->dependsOn[$element_id][$data[$element_id]] as $field) {
            $fields[$field] = $data[$field];
        }

        return $fields;
    }

    /**
     * URL RELATED FUNCTIONS
     */

    public function require_auth($code = 'REQUIRE_LOGIN')
    {
        if ($this->isLoggedIn() == false) {
            $return = $this->return_url();
            $this->go_to("login.html?return={$return}&code={$code}");
        }
    }

    public function return_url($return = true, $var = 'return')
    {
        $URI = urlencode($_SERVER['REQUEST_URI']);
        if ($return) {
            return (isset($_GET[$var])) ? $_GET[$var] : $URI;
        } else {
            return $URI;
        }
    }

    public function return_to($var = 'return', $print = false)
    {
        $path = (isset($_GET[$var]) == true) ? $_GET[$var] : null;

        return ($this->is_absolute_url($path) == false) ? $this->go_to($path, false, $print) : null;
    }

    public function go_to($path = '/', $base = true, $print = false)
    {
        $path = ($base == true || empty($path) == true) ? "{$this->config['base_url']}/{$path}" : $path;
        //$path = $this->plaintext($path);
        if ($print == true) {
            return $path;
        }
        header("Location: {$path}");
        exit;
    }

    public static function is_absolute_url($url)
    {
        return preg_match("~^(?:f|ht)tps?://~i", $url);
    }

    public function absolute_url($url)
    {
        if (self::is_absolute_url($url) == true) {
            return $url;
        }

        return $this->config['url_scheme'].$this->config['http_host'].$url;
    }

    public function self_url()
    {
        return URL_SCHEME.HTTP_HOST.$_SERVER['REQUEST_URI'];
    }

    public function url()
    {
        $input = array();
        $input[] = $this->config['base_url'];

        $args = func_get_args();
        $last = end($args);

        $tail = '';
        if ($last == 'html' || $last == 'xml') {
            array_pop($args);
            $tail = '.'.$last;
        }

        foreach ($args as $item) {
            $input[] = $item;
        }

        return implode("/", $input).$tail;
    }

    public function removeUrls($str)
    {
        return preg_replace('#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#', '', $str);
    }

    public function seo_str($text)
    {
        if (function_exists('mb_strtolower') == true) {
            $text = mb_strtolower($text, 'UTF-8');
        }
        $text = html_entity_decode($text);
        $text = $this->removeUrls($text);
        $special_chars = array("%","—","+","?", "[", "]", "/", "\\", "=", "<", ">", ":", ";", ",", "'", "\"", "&", "$", "#", "*", "(", ")", "|", "~", ".", "`", "!", "{", "}", chr(0));
        $text = str_replace($special_chars, '', $text);
        $text = preg_replace('/[\s-]+/', '_', $text);

        return trim($text, '.-_');
    }

    /**
     *   PAGE LAYOUT FUNCTIONS
     */

    public function alert_html($alert, $type = 'info')
    {
        return "<div class='alert alert-{$type}'><button type='button' class='close' data-dismiss='alert'>&times;</button>{$alert}</div>";
    }

    public function facebook_sdk()
    {
        $locale = $this->get_locale();

        return '<div id="fb-root"></div>
        <script>
            (function(d, s, id) {
              var js, fjs = d.getElementsByTagName(s)[0];
              if (d.getElementById(id)) return;
              js = d.createElement(s); js.id = id;
              js.src = "//connect.facebook.net/'.$locale.'/sdk.js#xfbml=1&version=v2.0";
              fjs.parentNode.insertBefore(js, fjs);
            }(document, "script", "facebook-jssdk"));
        </script>';
    }

    public function googleanalytics_code($id)
    {
        return $this->javascript_code("var _gaq = _gaq || []; _gaq.push(['_setAccount', '{$id}']); _gaq.push(['_trackPageview']); (function() {var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);})();");
    }

    public function histats_code($id)
    {
        return '<script type="text/javascript">document.write(unescape("%3Cscript src=%27http://s10.histats.com/js15.js%27 type=%27text/javascript%27%3E%3C/script%3E"));</script><a href="http://www.histats.com" target="_blank" title="free stats" ><script  type="text/javascript" >try {Histats.start(1,'.$id.',4,0,0,0,"");Histats.track_hits();} catch(err){};</script></a><noscript><a href="http://www.histats.com" target="_blank"><img  src="http://sstatic1.histats.com/0.gif?'.$id.'&101" alt="free stats" border="0"></a></noscript>';
    }

    public function cssFile($file, $place = 'header')
    {
        if (is_array($file) == true) {
            foreach ($file as $f) {
                $this->cssFile($f, $place);
            }
        } else {
            $this->html(
                $this->css_file(
                    $this->assets_file($file)
                ), $place);
        }

        return $this;
    }

    public function jsFile($file, $place = 'footer')
    {
        if (is_array($file) == true) {
            foreach ($file as $f) {
                $this->jsFile($f, $place);
            }
        } else {
            $this->html(
                $this->javascript_file(
                    $this->assets_file($file)
                ), $place);
        }

        return $this;
    }

    public function assets_file($file)
    {
        return "{$this->config['base_url']}/assets/{$file}";
    }

    public function javascript_file($file)
    {
        return "<script type='text/javascript' src='{$file}'></script>\n";
    }

    public function css_file($file)
    {
        return "<link href='{$file}' rel='stylesheet' type='text/css' />";
    }

    public function jQuery_code($code)
    {
        return $this->javascript_code('$(function(){ '.$code.' });');
    }

    public function javascript_code($code)
    {
        return "<script type='text/javascript'>{$code}</script>";
    }

    public function html($code, $place = 'header')
    {
        $this->html[$place][] = $code;

        return $this;
    }

    public function redirect($msg = '', $url = '', $time = 3)
    {
        if(empty($url) == true){
            $url = $this->getOption('site_url');
        }
        
        $this->smarty->assign('msg', $msg);
        $this->display('msg.tpl', tr('message'), array(
            'noindex' => true,
            'redirect' => true,
            'redirect_url' => $url,
            'redirect_time' => $time,
        ));
        exit;
    }

    public function fullMsgPage($msg = '')
    {
        $this->smarty->assign('msg', $msg);
        $this->display('msg.tpl', tr('message'), array(
            'noindex' => true
        ));
        exit;
    }

    final public function display($tpl_files = array(), $title = 'غير مسمى', $options = array())
    {
        
        $tpl_files = (is_array($tpl_files) == true) ? $tpl_files : array($tpl_files);

        if ($this->isLoggedIn() == true || in_array('login.tpl',$tpl_files) == true) {
            $options['login_form'] = false;
            
        }else{
            $options['login_csrf_str'] = NoCSRF::generate( 'login' );
        }

        foreach (array('googleanalytics', 'histats') as $service) {
            $service_id = $this->getOption("{$service}_id");
            $service_func = "{$service}_code";
            if (empty($service_id) == false) {
                $this->html($this->{$service_func}($service_id), 'footer');
            }
        }

        if (is_array($title)) {
            $title = implode(' '.$this->getOption('title_separator', '-').' ', $title);
        }

        $defaults = array(
            'description' => $this->getOption('description'),
            'keywords' => $this->getOption('keywords'),
            'noindex' => false,
            'redirect' => false,
            'redirect_url' => null,
            'redirect_time' => 2,
            'canonical' => null,
            'property_meta' => array(
                'og:title' => $title,
                'og:site_name' => $this->getOption('site_name'),
                'og:description' => $this->getOption('description'),
                'og:url' => $this->self_url(),
            ),
            'login_form' => true,
            'login_csrf_str' => ''
            
        );

        $this->smarty->assign('title', $title);
        $this->smarty->assign('tpl_files',
            is_array($tpl_files) == true ? $tpl_files : array($tpl_files)
        );
        $this->smarty->assign('html', $this->html);
        $this->smarty->assign('page',
            self::array_merge_recursive_distinct($defaults, $options)
        );

        $this->smarty->display('layout.tpl');
        
        unset($this->db, $this->smarty);smarty_catch_error();
        
        echo sprintf('<!-- T:%s - Q:%s -->', get_execution_time(), $GLOBALS['nquery']);
    }
}
