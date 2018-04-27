<?php /* Smarty version Smarty-3.1.18, created on 2015-05-24 23:32:06
         compiled from "D:\nx\htdocs\Mediat\WebFairy-Mediat\1.4\tpl\functions.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1832055623546c9a6b8-04718660%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2ba1ed6c1a4063bc15828d0658a82bd39e20e5ab' => 
    array (
      0 => 'D:\\nx\\htdocs\\Mediat\\WebFairy-Mediat\\1.4\\tpl\\functions.tpl',
      1 => 1432223864,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1832055623546c9a6b8-04718660',
  'function' => 
  array (
    'resized' => 
    array (
      'parameter' => 
      array (
        'filename' => '',
        's' => 'small',
        'title' => '',
        'attrs' => 
        array (
        ),
        'responsive' => true,
      ),
      'compiled' => '',
    ),
    'post_item' => 
    array (
      'parameter' => 
      array (
        'post' => 
        array (
        ),
        'style' => 'a',
        'url_attrs' => 
        array (
        ),
      ),
      'compiled' => '',
    ),
    'posts_list' => 
    array (
      'parameter' => 
      array (
        'posts' => 
        array (
        ),
        'ad_repeat' => '5',
        'ad_every' => 4,
        'style' => 'a',
        'url_attrs' => 
        array (
        ),
        'ads' => true,
      ),
      'compiled' => '',
    ),
    'timeline_posts' => 
    array (
      'parameter' => 
      array (
        'posts' => 
        array (
        ),
        'ajax' => false,
      ),
      'compiled' => '',
    ),
    'sidebar' => 
    array (
      'parameter' => 
      array (
        'current_category' => 0,
        'current_page' => '',
      ),
      'compiled' => '',
    ),
    'navbar_render' => 
    array (
      'parameter' => 
      array (
        'data' => 
        array (
        ),
        'depth' => 0,
      ),
      'compiled' => '',
    ),
    'source_preview' => 
    array (
      'parameter' => 
      array (
        'data' => 0,
      ),
      'compiled' => '',
    ),
    'navbars_form_inputs' => 
    array (
      'parameter' => 
      array (
        'row' => 
        array (
        ),
      ),
      'compiled' => '',
    ),
    'navbars_builder_item' => 
    array (
      'parameter' => 
      array (
        'item' => 
        array (
        ),
      ),
      'compiled' => '',
    ),
    'navbars_builder' => 
    array (
      'parameter' => 
      array (
        'items' => 
        array (
        ),
      ),
      'compiled' => '',
    ),
    'posts_filter' => 
    array (
      'parameter' => 
      array (
      ),
      'compiled' => '',
    ),
    'user_profile_header' => 
    array (
      'parameter' => 
      array (
        'row' => 
        array (
        ),
        'case' => 'wall',
      ),
      'compiled' => '',
    ),
  ),
  'variables' => 
  array (
    'responsive' => 0,
    'filename' => 0,
    'config' => 0,
    's' => 0,
    'title' => 0,
    'attrs' => 0,
    'attr_key' => 0,
    'attr_value' => 0,
    'style' => 0,
    'post' => 0,
    'url_attrs' => 0,
    'attr_val' => 0,
    'posts' => 0,
    'ads' => 0,
    'ad_every' => 0,
    'ad_repeat' => 0,
    'posts_ad' => 0,
    'ajax' => 0,
    'current_category' => 0,
    'db_statistics' => 0,
    'catgories' => 0,
    'catgory' => 0,
    'subcategory' => 0,
    'current_page' => 0,
    'user' => 0,
    'data' => 0,
    'item' => 0,
    'depth' => 0,
    'catgories_tree' => 0,
    'cat_id' => 0,
    'cat_title' => 0,
    'row' => 0,
    'pages' => 0,
    'page_id' => 0,
    'page_title' => 0,
    'items' => 0,
    'case' => 0,
  ),
  'has_nocache_code' => 0,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_55623548207ca2_12828438',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55623548207ca2_12828438')) {function content_55623548207ca2_12828438($_smarty_tpl) {?><?php if (!function_exists('smarty_template_function_resized')) {
    function smarty_template_function_resized($_smarty_tpl,$params) {
    $saved_tpl_vars = $_smarty_tpl->tpl_vars;
    foreach ($_smarty_tpl->smarty->template_functions['resized']['parameter'] as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);};
    foreach ($params as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);}?><img <?php if ($_smarty_tpl->tpl_vars['responsive']->value) {?>class="img-responsive"<?php }?> src="<?php if (Webfairy::is_absolute_url($_smarty_tpl->tpl_vars['filename']->value)) {?><?php echo $_smarty_tpl->tpl_vars['filename']->value;?>
<?php } else { ?><?php echo $_smarty_tpl->tpl_vars['config']->value['base_url'];?>
/resized/<?php echo $_smarty_tpl->tpl_vars['s']->value;?>
_<?php echo $_smarty_tpl->tpl_vars['filename']->value;?>
<?php }?>" alt="<?php echo $_smarty_tpl->tpl_vars['title']->value;?>
" title="<?php echo $_smarty_tpl->tpl_vars['title']->value;?>
" <?php  $_smarty_tpl->tpl_vars['attr_value'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['attr_value']->_loop = false;
 $_smarty_tpl->tpl_vars['attr_key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['attrs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['attr_value']->key => $_smarty_tpl->tpl_vars['attr_value']->value) {
$_smarty_tpl->tpl_vars['attr_value']->_loop = true;
 $_smarty_tpl->tpl_vars['attr_key']->value = $_smarty_tpl->tpl_vars['attr_value']->key;
?><?php echo $_smarty_tpl->tpl_vars['attr_key']->value;?>
="<?php echo $_smarty_tpl->tpl_vars['attr_value']->value;?>
" <?php } ?>/><?php $_smarty_tpl->tpl_vars = $saved_tpl_vars;
foreach (Smarty::$global_tpl_vars as $key => $value) if(!isset($_smarty_tpl->tpl_vars[$key])) $_smarty_tpl->tpl_vars[$key] = $value;}}?>
<?php if (!is_callable('smarty_function_type2icon')) include 'D:/nx/htdocs/Mediat/WebFairy-Mediat/1.4/lib/Smarty/plugins\function.type2icon.php';
?><?php if (!function_exists('smarty_template_function_post_item')) {
    function smarty_template_function_post_item($_smarty_tpl,$params) {
    $saved_tpl_vars = $_smarty_tpl->tpl_vars;
    foreach ($_smarty_tpl->smarty->template_functions['post_item']['parameter'] as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);};
    foreach ($params as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);}?><?php if ($_smarty_tpl->tpl_vars['style']->value=='a') {?><li class="brick-item post-item post-id-<?php echo $_smarty_tpl->tpl_vars['post']->value['id'];?>
 post-type-<?php echo $_smarty_tpl->tpl_vars['post']->value['type'];?>
 no-js"><div class="post-body"><a href="<?php echo $_smarty_tpl->tpl_vars['post']->value['url'];?>
"><span class="post-title"><?php echo $_smarty_tpl->tpl_vars['post']->value['title'];?>
</span></a><?php if (empty($_smarty_tpl->tpl_vars['post']->value['description'])==false&&empty($_smarty_tpl->tpl_vars['post']->value['thumb'])==true) {?><div class="post-text"><span><?php echo $_smarty_tpl->tpl_vars['post']->value['description'];?>
</span></div><?php } elseif (empty($_smarty_tpl->tpl_vars['post']->value['thumb'])==true) {?><div class="post-icon post-icon-<?php echo $_smarty_tpl->tpl_vars['post']->value['type'];?>
"><a href="<?php echo $_smarty_tpl->tpl_vars['post']->value['url'];?>
"><i class="fa fa-4x fa-<?php echo smarty_function_type2icon(array('type'=>$_smarty_tpl->tpl_vars['post']->value['type'],'provider'=>$_smarty_tpl->tpl_vars['post']->value['provider']),$_smarty_tpl);?>
"></i></a></div><?php } elseif (empty($_smarty_tpl->tpl_vars['post']->value['thumb'])==false) {?><div class="post-image <?php echo $_smarty_tpl->tpl_vars['post']->value['provider'];?>
"><a href="<?php echo $_smarty_tpl->tpl_vars['post']->value['url'];?>
"><?php smarty_template_function_resized($_smarty_tpl,array('filename'=>$_smarty_tpl->tpl_vars['post']->value['thumb'],'s'=>'auto-height-small','title'=>$_smarty_tpl->tpl_vars['post']->value['title']));?>
</a></div><?php }?><div class="post-details"><ul><li><i class="fa fa-clock-o"></i> <?php echo $_smarty_tpl->tpl_vars['post']->value['time'];?>
</li><li><i class="fa fa-comment"></i> <?php echo $_smarty_tpl->tpl_vars['post']->value['comments'];?>
</li><li><i class="fa fa-eye"></i> <?php echo $_smarty_tpl->tpl_vars['post']->value['views'];?>
</li><li><i class="fa fa-thumbs-up"></i> <?php echo $_smarty_tpl->tpl_vars['post']->value['vote']['total'];?>
</li></ul></div></div><div class="post-footer"><ul><li><a class="fb_share" href="#" data-url="<?php echo $_smarty_tpl->tpl_vars['post']->value['absolute_url'];?>
"><i class="fa fa-facebook-square"></i></a></li><li><a class="tw_share" href="#" data-url="<?php echo $_smarty_tpl->tpl_vars['post']->value['absolute_url'];?>
" data-title="<?php echo $_smarty_tpl->tpl_vars['post']->value['title'];?>
"><i class="fa fa-twitter"></i></a></li><li><a class="pint_share" href="#" <?php if (empty($_smarty_tpl->tpl_vars['post']->value['thumb'])==false) {?>data-media="<?php echo $_smarty_tpl->tpl_vars['config']->value['site_url'];?>
/resized/medium_<?php echo $_smarty_tpl->tpl_vars['post']->value['thumb'];?>
"<?php }?> data-url="<?php echo $_smarty_tpl->tpl_vars['post']->value['absolute_url'];?>
" data-title="<?php echo $_smarty_tpl->tpl_vars['post']->value['title'];?>
"><i class="fa fa-pinterest"></i></a></li><li><a class="gplus_share" href="#" data-url="<?php echo $_smarty_tpl->tpl_vars['post']->value['absolute_url'];?>
"><i class="fa fa-google-plus"></i></a></li><li><a class="linkedin_share" href="#" data-url="<?php echo $_smarty_tpl->tpl_vars['post']->value['absolute_url'];?>
"><i class="fa fa-linkedin"></i></a></li><li><a class="digg_share" href="#" data-url="<?php echo $_smarty_tpl->tpl_vars['post']->value['absolute_url'];?>
" data-title="<?php echo $_smarty_tpl->tpl_vars['post']->value['title'];?>
" ><i class="fa fa-digg"></i></a></li></ul></div></li><?php } elseif ($_smarty_tpl->tpl_vars['style']->value=='b') {?><li class="post-item post-id-<?php echo $_smarty_tpl->tpl_vars['post']->value['id'];?>
 post-type-<?php echo $_smarty_tpl->tpl_vars['post']->value['type'];?>
 no-js"><ul class="vote vote-b hide-if-no-js" data-id="<?php echo $_smarty_tpl->tpl_vars['post']->value['id'];?>
"><li class="up upvote <?php if (isset($_smarty_tpl->tpl_vars['post']->value['vote']['upvoted'])&&$_smarty_tpl->tpl_vars['post']->value['vote']['upvoted']==true) {?>upvote-on<?php }?>"><i class="fa fa-sort-up"></i></li><li class="total count" rel="tooltip" title="<?php echo tr('users_voted');?>
: <?php echo $_smarty_tpl->tpl_vars['post']->value['vote']['votes'];?>
"><?php echo $_smarty_tpl->tpl_vars['post']->value['vote']['total'];?>
</li><li class="down downvote <?php if (isset($_smarty_tpl->tpl_vars['post']->value['vote']['downvoted'])&&$_smarty_tpl->tpl_vars['post']->value['vote']['downvoted']==true) {?>downvote-on<?php }?>"><i class="fa fa-sort-down"></i></li></ul><div class="clearfix"><?php if (empty($_smarty_tpl->tpl_vars['post']->value['thumb'])==false) {?><a class="post-thumb overlay" href="<?php echo $_smarty_tpl->tpl_vars['post']->value['url'];?>
"><?php smarty_template_function_resized($_smarty_tpl,array('filename'=>$_smarty_tpl->tpl_vars['post']->value['thumb'],'s'=>'thumb','attrs'=>array('width'=>100,'height'=>80),'title'=>$_smarty_tpl->tpl_vars['post']->value['title']));?>
<i class="fa fa-2x fa-<?php echo smarty_function_type2icon(array('type'=>$_smarty_tpl->tpl_vars['post']->value['type'],'provider'=>$_smarty_tpl->tpl_vars['post']->value['provider']),$_smarty_tpl);?>
"></i></a><?php }?><div class="post-body"><h4 class="post-title"><a href="<?php echo $_smarty_tpl->tpl_vars['post']->value['url'];?>
" <?php  $_smarty_tpl->tpl_vars['attr_val'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['attr_val']->_loop = false;
 $_smarty_tpl->tpl_vars['attr_key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['url_attrs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['attr_val']->key => $_smarty_tpl->tpl_vars['attr_val']->value) {
$_smarty_tpl->tpl_vars['attr_val']->_loop = true;
 $_smarty_tpl->tpl_vars['attr_key']->value = $_smarty_tpl->tpl_vars['attr_val']->key;
?><?php echo $_smarty_tpl->tpl_vars['attr_key']->value;?>
="<?php echo $_smarty_tpl->tpl_vars['attr_val']->value;?>
" <?php } ?>><?php echo $_smarty_tpl->tpl_vars['post']->value['title'];?>
</a></h4><?php if (empty($_smarty_tpl->tpl_vars['post']->value['description'])==false) {?><p class="post-desc"><?php echo $_smarty_tpl->tpl_vars['post']->value['description'];?>
</p><?php }?><p class="post-info"><i class="fa fa-clock-o"></i> <?php echo $_smarty_tpl->tpl_vars['post']->value['time'];?>
 <i class="fa fa-th-list"></i> <a href="<?php echo $_smarty_tpl->tpl_vars['post']->value['cat']['url'];?>
"><?php echo $_smarty_tpl->tpl_vars['post']->value['cat']['title'];?>
</a> <i class="fa fa-comment"></i> <?php echo $_smarty_tpl->tpl_vars['post']->value['comments'];?>
 <?php echo tr('comment');?>
 <i class="fa fa-eye"></i> <?php echo $_smarty_tpl->tpl_vars['post']->value['views'];?>
 <?php echo tr('view');?>
 <i class="fa fa-user"></i> <?php echo $_smarty_tpl->tpl_vars['post']->value['author'];?>
</p></div></div></li><?php } elseif ($_smarty_tpl->tpl_vars['style']->value=='c') {?><li class="post-item post-id-<?php echo $_smarty_tpl->tpl_vars['post']->value['id'];?>
 post-type-<?php echo $_smarty_tpl->tpl_vars['post']->value['type'];?>
 no-js"><div class="social_container"><ul class="socialcount socialcount-large"><li class="facebook"><a class="fb_share" href="#" data-url="<?php echo $_smarty_tpl->tpl_vars['post']->value['absolute_url'];?>
"><span class="fa fa-facebook"></span><span class="count">Share</span></a></li><li class="twitter"><a class="tw_share" href="#" data-url="<?php echo $_smarty_tpl->tpl_vars['post']->value['absolute_url'];?>
" data-title="<?php echo $_smarty_tpl->tpl_vars['post']->value['title'];?>
"><span class="fa fa-twitter"></span><span class="count">Tweet</span></a></li><li class="googleplus"><a class="gplus_share" href="#" data-url="<?php echo $_smarty_tpl->tpl_vars['post']->value['absolute_url'];?>
"><span class="fa fa-google-plus"></span><span class="count">+1</span></a></li><li class="pinterest"><a class="pint_share" href="#" <?php if (empty($_smarty_tpl->tpl_vars['post']->value['thumb'])==false) {?>data-media="<?php echo $_smarty_tpl->tpl_vars['config']->value['site_url'];?>
/resized/medium_<?php echo $_smarty_tpl->tpl_vars['post']->value['thumb'];?>
"<?php }?> data-url="<?php echo $_smarty_tpl->tpl_vars['post']->value['absolute_url'];?>
" data-title="<?php echo $_smarty_tpl->tpl_vars['post']->value['title'];?>
"><span class="fa fa-pinterest"></span><span class="count">Pin It</span></a></li></ul></div><div class="item-large"><div class="single-title"><?php if ($_smarty_tpl->tpl_vars['post']->value['author_row']) {?><a href="<?php echo $_smarty_tpl->tpl_vars['post']->value['author_row']['profileURLs']['profile'];?>
"><img src="<?php echo $_smarty_tpl->tpl_vars['post']->value['author_row']['photoURL'];?>
" class="img-circle user-avatar" alt="<?php echo $_smarty_tpl->tpl_vars['post']->value['author_row']['displayName'];?>
"></a><?php }?><h2 class="item-title"><a href="<?php echo $_smarty_tpl->tpl_vars['post']->value['url'];?>
"><?php echo $_smarty_tpl->tpl_vars['post']->value['title'];?>
</a></h2><ul class="item-details"><?php if ($_smarty_tpl->tpl_vars['post']->value['author_row']) {?><li class="details hidden-xs hidden-sm"><?php echo tr('submitted_by');?>
: <a href="<?php echo $_smarty_tpl->tpl_vars['post']->value['author_row']['profileURLs']['profile'];?>
"><?php echo $_smarty_tpl->tpl_vars['post']->value['author_row']['displayName'];?>
</a></li><?php }?><li class="details"><a href="<?php echo $_smarty_tpl->tpl_vars['post']->value['cat']['url'];?>
"><?php echo $_smarty_tpl->tpl_vars['post']->value['cat']['title'];?>
</a></li><li class="details"><?php echo $_smarty_tpl->tpl_vars['post']->value['time'];?>
</li><li class="like-count"><i class="fa fa-thumbs-o-up"></i> <?php echo $_smarty_tpl->tpl_vars['post']->value['vote']['total'];?>
</li><li class="comment-count"><i class="fa fa-comments"></i> <?php echo $_smarty_tpl->tpl_vars['post']->value['comments'];?>
</li><li class="view-count"><i class="fa fa-eye"></i> <?php echo $_smarty_tpl->tpl_vars['post']->value['views'];?>
 </li></ul><ul class="vote vote-a hide-if-no-js" data-id="<?php echo $_smarty_tpl->tpl_vars['post']->value['id'];?>
"><li class="total count" rel="tooltip" title="<?php echo tr('users_voted');?>
: <?php echo $_smarty_tpl->tpl_vars['post']->value['vote']['votes'];?>
"><?php echo $_smarty_tpl->tpl_vars['post']->value['vote']['total'];?>
</li><li class="up upvote <?php if (isset($_smarty_tpl->tpl_vars['post']->value['vote']['upvoted'])&&$_smarty_tpl->tpl_vars['post']->value['vote']['upvoted']==true) {?>upvote-on<?php }?>"><i class="fa fa-thumbs-o-up"></i></li><li class="down downvote <?php if (isset($_smarty_tpl->tpl_vars['post']->value['vote']['downvoted'])&&$_smarty_tpl->tpl_vars['post']->value['vote']['downvoted']==true) {?>downvote-on<?php }?>"><i class="fa fa-thumbs-o-up fa-rotate-180"></i></li></ul></div><div class="clearfix"></div><div class="post-body"><?php if (empty($_smarty_tpl->tpl_vars['post']->value['description'])==false&&empty($_smarty_tpl->tpl_vars['post']->value['thumb'])==true) {?><div class="post-text"><?php echo $_smarty_tpl->tpl_vars['post']->value['description'];?>
</div><?php } elseif (empty($_smarty_tpl->tpl_vars['post']->value['thumb'])==false) {?><div class="post-image <?php echo $_smarty_tpl->tpl_vars['post']->value['provider'];?>
"><a href="<?php echo $_smarty_tpl->tpl_vars['post']->value['url'];?>
"><?php smarty_template_function_resized($_smarty_tpl,array('filename'=>$_smarty_tpl->tpl_vars['post']->value['thumb'],'s'=>'auto-height-medium','title'=>$_smarty_tpl->tpl_vars['post']->value['title']));?>
</a></div><?php }?></div></div><div class="clearfix"></div></li><?php }?><?php $_smarty_tpl->tpl_vars = $saved_tpl_vars;
foreach (Smarty::$global_tpl_vars as $key => $value) if(!isset($_smarty_tpl->tpl_vars[$key])) $_smarty_tpl->tpl_vars[$key] = $value;}}?>
<?php if (!is_callable('smarty_function_ad_render')) include 'D:/nx/htdocs/Mediat/WebFairy-Mediat/1.4/lib/Smarty/plugins\function.ad_render.php';
?><?php if (!function_exists('smarty_template_function_posts_list')) {
    function smarty_template_function_posts_list($_smarty_tpl,$params) {
    $saved_tpl_vars = $_smarty_tpl->tpl_vars;
    foreach ($_smarty_tpl->smarty->template_functions['posts_list']['parameter'] as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);};
    foreach ($params as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);}?><?php  $_smarty_tpl->tpl_vars['post'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['post']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['posts']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['post']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['post']->key => $_smarty_tpl->tpl_vars['post']->value) {
$_smarty_tpl->tpl_vars['post']->_loop = true;
 $_smarty_tpl->tpl_vars['post']->iteration++;
?><?php smarty_template_function_post_item($_smarty_tpl,array('post'=>$_smarty_tpl->tpl_vars['post']->value,'style'=>$_smarty_tpl->tpl_vars['style']->value,'url_attrs'=>$_smarty_tpl->tpl_vars['url_attrs']->value));?>
<?php if ($_smarty_tpl->tpl_vars['config']->value['posts_ad']&&$_smarty_tpl->tpl_vars['ads']->value==true) {?><?php if (!($_smarty_tpl->tpl_vars['post']->iteration % $_smarty_tpl->tpl_vars['ad_every']->value)) {?><?php echo smarty_function_ad_render(array('assign'=>"posts_ad",'size'=>(($tmp = @$_smarty_tpl->tpl_vars['config']->value["posts_ad_".((string)$_smarty_tpl->tpl_vars['style']->value)])===null||$tmp==='' ? '200x200' : $tmp),'repeat'=>$_smarty_tpl->tpl_vars['ad_repeat']->value),$_smarty_tpl);?>
<?php if (isset($_smarty_tpl->tpl_vars['posts_ad']->value)==true) {?><?php if ($_smarty_tpl->tpl_vars['style']->value=='a') {?><li class="brick-item adbox"><?php echo $_smarty_tpl->tpl_vars['posts_ad']->value;?>
</li><?php } elseif ($_smarty_tpl->tpl_vars['style']->value=='b') {?><li class="adbox"><?php echo $_smarty_tpl->tpl_vars['posts_ad']->value;?>
</li><?php } elseif ($_smarty_tpl->tpl_vars['style']->value=='c') {?><li><?php echo $_smarty_tpl->tpl_vars['posts_ad']->value;?>
</li><?php }?><?php }?><?php }?><?php }?><?php } ?><?php $_smarty_tpl->tpl_vars = $saved_tpl_vars;
foreach (Smarty::$global_tpl_vars as $key => $value) if(!isset($_smarty_tpl->tpl_vars[$key])) $_smarty_tpl->tpl_vars[$key] = $value;}}?>
<?php if (!is_callable('smarty_function_cycle')) include 'D:/nx/htdocs/Mediat/WebFairy-Mediat/1.4/lib/Smarty/plugins\function.cycle.php';
?><?php if (!function_exists('smarty_template_function_timeline_posts')) {
    function smarty_template_function_timeline_posts($_smarty_tpl,$params) {
    $saved_tpl_vars = $_smarty_tpl->tpl_vars;
    foreach ($_smarty_tpl->smarty->template_functions['timeline_posts']['parameter'] as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);};
    foreach ($params as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);}?><?php  $_smarty_tpl->tpl_vars['post'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['post']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['posts']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['post']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['post']->key => $_smarty_tpl->tpl_vars['post']->value) {
$_smarty_tpl->tpl_vars['post']->_loop = true;
 $_smarty_tpl->tpl_vars['post']->iteration++;
?><li <?php if ($_smarty_tpl->tpl_vars['ajax']->value) {?>class="ajax"<?php }?>><div class="direction-<?php echo smarty_function_cycle(array('values'=>"r,l"),$_smarty_tpl);?>
"><div class="flag-wrapper"><span class="flag"><a href="<?php echo $_smarty_tpl->tpl_vars['post']->value['user_data']['profileURLs']['profile'];?>
"><?php echo $_smarty_tpl->tpl_vars['post']->value['user_data']['displayName'];?>
</a></span><span class="time-wrapper"><span class="time"><?php echo $_smarty_tpl->tpl_vars['post']->value['createdon'];?>
</span></span></div><?php if ($_smarty_tpl->tpl_vars['post']->value['roles']) {?><form class="delete-post-form" method="post" action="<?php echo $_smarty_tpl->tpl_vars['config']->value['base_url'];?>
/ajax.php?c=delete_timeline_posts"><input name="id" value="<?php echo $_smarty_tpl->tpl_vars['post']->value['id'];?>
" type="hidden" /><input name="wall_posts_<?php echo $_smarty_tpl->tpl_vars['post']->value['id'];?>
" value="<?php echo $_smarty_tpl->tpl_vars['post']->value['csrf'];?>
" type="hidden" /><button type="submit" class="close" aria-hidden="true">&times;</button></form><?php }?><div class="desc"><?php echo $_smarty_tpl->tpl_vars['post']->value['content'];?>
</div></div></li><?php } ?><?php $_smarty_tpl->tpl_vars = $saved_tpl_vars;
foreach (Smarty::$global_tpl_vars as $key => $value) if(!isset($_smarty_tpl->tpl_vars[$key])) $_smarty_tpl->tpl_vars[$key] = $value;}}?>
<?php if (!function_exists('smarty_template_function_sidebar')) {
    function smarty_template_function_sidebar($_smarty_tpl,$params) {
    $saved_tpl_vars = $_smarty_tpl->tpl_vars;
    foreach ($_smarty_tpl->smarty->template_functions['sidebar']['parameter'] as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);};
    foreach ($params as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);}?><div id="sidebar" <?php if ($_smarty_tpl->tpl_vars['config']->value['fixed_sidebar']) {?>class="fixedbar"<?php }?>><ul class="side-menu" id="#accordion1"><li><a class="accordion-toggle <?php if ($_smarty_tpl->tpl_vars['current_category']->value>0) {?>active<?php }?>" data-toggle="collapse" href="#categories"><span class="fa-stack"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-cloud fa-stack-1x fa-inverse"></i></span> <?php if ($_smarty_tpl->tpl_vars['config']->value['public_stats']) {?> <?php echo $_smarty_tpl->tpl_vars['db_statistics']->value['posts'];?>
 <?php echo tr('post');?>
 <?php } else { ?> <?php echo tr('categories');?>
<?php }?></a><ul class="menu-parent collapse in" id="categories"><li id="accordion2"><div class="accordion-group"><?php  $_smarty_tpl->tpl_vars['catgory'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['catgory']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['catgories']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['catgory']->key => $_smarty_tpl->tpl_vars['catgory']->value) {
$_smarty_tpl->tpl_vars['catgory']->_loop = true;
?><?php if (count($_smarty_tpl->tpl_vars['catgory']->value['subcategories'])>0) {?><a class="<?php if (in_array($_smarty_tpl->tpl_vars['current_category']->value,array_keys($_smarty_tpl->tpl_vars['catgory']->value['subcategories']))==true) {?>active<?php }?>" data-parent="#accordion2" data-toggle="collapse" href="#group-collapse<?php echo $_smarty_tpl->tpl_vars['catgory']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['catgory']->value['title'];?>
</a><ul class="menu-child collapse <?php if (in_array($_smarty_tpl->tpl_vars['current_category']->value,array_keys($_smarty_tpl->tpl_vars['catgory']->value['subcategories']))==true) {?>in<?php }?> show-if-no-js" id="group-collapse<?php echo $_smarty_tpl->tpl_vars['catgory']->value['id'];?>
"><?php  $_smarty_tpl->tpl_vars['subcategory'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['subcategory']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['catgory']->value['subcategories']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['subcategory']->key => $_smarty_tpl->tpl_vars['subcategory']->value) {
$_smarty_tpl->tpl_vars['subcategory']->_loop = true;
?><li><a class="<?php if ($_smarty_tpl->tpl_vars['subcategory']->value['id']==$_smarty_tpl->tpl_vars['current_category']->value) {?>active<?php }?>" href="<?php echo $_smarty_tpl->tpl_vars['subcategory']->value['url'];?>
"><?php echo $_smarty_tpl->tpl_vars['subcategory']->value['title'];?>
</a></li><?php } ?></ul><?php } else { ?><a class="<?php if ($_smarty_tpl->tpl_vars['catgory']->value['id']==$_smarty_tpl->tpl_vars['current_category']->value) {?>active<?php }?>" href="<?php echo $_smarty_tpl->tpl_vars['catgory']->value['url'];?>
"><?php echo $_smarty_tpl->tpl_vars['catgory']->value['title'];?>
</a><?php }?><?php } ?></div></li></ul></li></ul><ul class="side-menu"><li><a <?php if ($_smarty_tpl->tpl_vars['current_page']->value=='users') {?>class="active"<?php }?> href="<?php echo $_smarty_tpl->tpl_vars['config']->value['base_url'];?>
/users/index.html"><span class="fa-stack"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-users fa-stack-1x fa-inverse"></i></span> <?php if ($_smarty_tpl->tpl_vars['config']->value['public_stats']) {?><?php echo $_smarty_tpl->tpl_vars['db_statistics']->value['users'];?>
 <?php echo tr('users');?>
 <?php } else { ?> <?php echo tr('users');?>
 <?php }?></a></li><?php if (($_smarty_tpl->tpl_vars['config']->value['user_posting']&&$_smarty_tpl->tpl_vars['user']->value['isLoggedIn'])||$_smarty_tpl->tpl_vars['user']->value['isManager']) {?><li><a class="accordion-toggle" data-toggle="collapse" href="#add_post"><span class="fa-stack"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-plus fa-stack-1x fa-inverse"></i></span> <?php echo tr('add_post');?>
</a><ul class="menu-parent collapse show-if-no-js" id="add_post"><li><div class="accordion-group"><a href="<?php echo $_smarty_tpl->tpl_vars['config']->value['base_url'];?>
/post.php?via=upload"><i class="fa fa-upload"></i> <?php echo tr('post_upload');?>
</a><a href="<?php echo $_smarty_tpl->tpl_vars['config']->value['base_url'];?>
/post.php?via=write"><i class="fa fa-pencil"></i> <?php echo tr('post_write');?>
</a><a href="<?php echo $_smarty_tpl->tpl_vars['config']->value['base_url'];?>
/post.php?via=link"><i class="fa fa-link"></i> <?php echo tr('post_link');?>
</a></div></li></ul></li><?php }?></ul><?php if ($_smarty_tpl->tpl_vars['user']->value['isLoggedIn']) {?><ul class="side-menu"><li><a href="<?php echo $_smarty_tpl->tpl_vars['config']->value['base_url'];?>
/my_posts.html"><span class="fa-stack"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-briefcase fa-stack-1x fa-inverse"></i></span> <?php echo tr('my_posts');?>
</a></li></ul><?php }?></div><?php $_smarty_tpl->tpl_vars = $saved_tpl_vars;
foreach (Smarty::$global_tpl_vars as $key => $value) if(!isset($_smarty_tpl->tpl_vars[$key])) $_smarty_tpl->tpl_vars[$key] = $value;}}?>
<?php if (!is_callable('smarty_function_url_format')) include 'D:/nx/htdocs/Mediat/WebFairy-Mediat/1.4/lib/Smarty/plugins\function.url_format.php';
?><?php if (!function_exists('smarty_template_function_navbar_render')) {
    function smarty_template_function_navbar_render($_smarty_tpl,$params) {
    $saved_tpl_vars = $_smarty_tpl->tpl_vars;
    foreach ($_smarty_tpl->smarty->template_functions['navbar_render']['parameter'] as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);};
    foreach ($params as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);}?><?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['data']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['item']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
 $_smarty_tpl->tpl_vars['item']->index++;
?><li class="<?php if (isset($_smarty_tpl->tpl_vars['item']->value['children'])==true) {?>dropdown <?php if ($_smarty_tpl->tpl_vars['depth']->value>0) {?>dropdown-sub<?php }?><?php }?> <?php if (Webfairy::is_current_url($_smarty_tpl->tpl_vars['item']->value['url'])) {?>active<?php }?>"><?php if (isset($_smarty_tpl->tpl_vars['item']->value['children'])==true) {?><a href="<?php echo smarty_function_url_format(array('url'=>$_smarty_tpl->tpl_vars['item']->value['url']),$_smarty_tpl);?>
" class="dropdown-toggle <?php if (empty($_smarty_tpl->tpl_vars['item']->value['title'])==true) {?>icon-only<?php }?>" data-toggle="dropdown"><?php echo $_smarty_tpl->tpl_vars['item']->value['icon'];?>
 <?php echo $_smarty_tpl->tpl_vars['item']->value['title'];?>
 <?php if ($_smarty_tpl->tpl_vars['depth']->value>0) {?><?php } else { ?><b class="caret"></b><?php }?></a><?php } else { ?><a href="<?php echo smarty_function_url_format(array('url'=>$_smarty_tpl->tpl_vars['item']->value['url']),$_smarty_tpl);?>
" class="<?php if (empty($_smarty_tpl->tpl_vars['item']->value['title'])==true) {?>icon-only<?php }?>"><?php echo $_smarty_tpl->tpl_vars['item']->value['icon'];?>
 <?php echo $_smarty_tpl->tpl_vars['item']->value['title'];?>
</a><?php }?><?php if (isset($_smarty_tpl->tpl_vars['item']->value['children'])==true) {?><ul class="dropdown-menu <?php if ($_smarty_tpl->tpl_vars['depth']->value>0) {?>sub-menu<?php }?>"><?php smarty_template_function_navbar_render($_smarty_tpl,array('data'=>$_smarty_tpl->tpl_vars['item']->value['children'],'depth'=>$_smarty_tpl->tpl_vars['depth']->value+1));?>
</ul><?php }?></li><?php } ?><?php $_smarty_tpl->tpl_vars = $saved_tpl_vars;
foreach (Smarty::$global_tpl_vars as $key => $value) if(!isset($_smarty_tpl->tpl_vars[$key])) $_smarty_tpl->tpl_vars[$key] = $value;}}?>
<?php if (!function_exists('smarty_template_function_source_preview')) {
    function smarty_template_function_source_preview($_smarty_tpl,$params) {
    $saved_tpl_vars = $_smarty_tpl->tpl_vars;
    foreach ($_smarty_tpl->smarty->template_functions['source_preview']['parameter'] as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);};
    foreach ($params as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);}?><?php if ($_smarty_tpl->tpl_vars['data']->value['result']['quantity']>0) {?><ul id="source-preview-list" class="media-list"><?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['data']->value['result']['items']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['item']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
 $_smarty_tpl->tpl_vars['item']->index++;
?><?php if ($_smarty_tpl->tpl_vars['item']->value['fresh']==true) {?><li class="media"><?php if (empty($_smarty_tpl->tpl_vars['item']->value['row']['thumb_id'])==false) {?><a class="<?php if ($_smarty_tpl->tpl_vars['config']->value['rtl']) {?>pull-right<?php } else { ?>pull-left<?php }?>" href="<?php echo $_smarty_tpl->tpl_vars['item']->value['row']['link'];?>
" target="_blank"><img class="media-object" src="<?php echo $_smarty_tpl->tpl_vars['item']->value['row']['thumb_id'];?>
"></a><?php }?><div class="media-body"><a data-toggle="collapse" data-parent="#accordion" href="#collapse-<?php echo $_smarty_tpl->tpl_vars['item']->index;?>
"><h4 class="media-heading"><?php echo $_smarty_tpl->tpl_vars['item']->value['row']['title'];?>
</h4></a><div class="row"><div class="col-sm-6"><div class="input-group"><select name="cat_id" class="form-control input-sm"><?php  $_smarty_tpl->tpl_vars['cat_title'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['cat_title']->_loop = false;
 $_smarty_tpl->tpl_vars['cat_id'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['catgories_tree']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['cat_title']->key => $_smarty_tpl->tpl_vars['cat_title']->value) {
$_smarty_tpl->tpl_vars['cat_title']->_loop = true;
 $_smarty_tpl->tpl_vars['cat_id']->value = $_smarty_tpl->tpl_vars['cat_title']->key;
?><option <?php if ($_smarty_tpl->tpl_vars['cat_id']->value==(($tmp = @$_smarty_tpl->tpl_vars['item']->value['row']['cat_id'])===null||$tmp==='' ? 0 : $tmp)) {?>selected="true"<?php }?> value="<?php echo $_smarty_tpl->tpl_vars['cat_id']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['cat_title']->value;?>
</option><?php } ?></select><span class="input-group-btn"><button data-id="<?php echo $_smarty_tpl->tpl_vars['item']->value['row']['source_id'];?>
" data-key="<?php echo $_smarty_tpl->tpl_vars['item']->index;?>
" class="btn btn-sm btn-primary fetch-item" type="button"><?php echo tr('fetch');?>
</button></span></div></div></div></div><br class="clearfix"/><div id="collapse-<?php echo $_smarty_tpl->tpl_vars['item']->index;?>
" class="panel-collapse collapse"><div class="well well-lg"><?php echo $_smarty_tpl->tpl_vars['item']->value['row']['content'];?>
</div></div></li><?php } elseif ($_smarty_tpl->tpl_vars['item']->value['fresh']==false) {?><li class="media"><?php if (empty($_smarty_tpl->tpl_vars['item']->value['row']['thumb'])==false) {?><a class="<?php if ($_smarty_tpl->tpl_vars['config']->value['rtl']) {?>pull-right<?php } else { ?>pull-left<?php }?>"><?php smarty_template_function_resized($_smarty_tpl,array('filename'=>$_smarty_tpl->tpl_vars['item']->value['row']['thumb'],'s'=>'thumb','responsive'=>false,'attrs'=>array('class'=>'media-object')));?>
</a><?php }?><div class="media-body"><a href="<?php echo $_smarty_tpl->tpl_vars['item']->value['row']['url'];?>
" target="_blank"><h4 class="media-heading"><?php echo $_smarty_tpl->tpl_vars['item']->value['row']['title'];?>
</h4></a></div></li><?php }?><?php } ?></ul><?php }?><ul class="list-group"><li class="list-group-item"><?php echo tr('items');?>
: <span class="label label-info"><?php echo $_smarty_tpl->tpl_vars['data']->value['result']['quantity'];?>
</span></li><li class="list-group-item"><?php echo tr('started');?>
: <span class="label label-info"><?php echo $_smarty_tpl->tpl_vars['data']->value['info']['started'];?>
</span> <span class="label label-info"><?php echo $_smarty_tpl->tpl_vars['data']->value['info']['time'];?>
</span></li><li class="list-group-item"><?php echo tr('memory_usage');?>
: <span class="label label-info"><?php echo $_smarty_tpl->tpl_vars['data']->value['info']['memory_usage'];?>
</span></li></ul><?php $_smarty_tpl->tpl_vars = $saved_tpl_vars;
foreach (Smarty::$global_tpl_vars as $key => $value) if(!isset($_smarty_tpl->tpl_vars[$key])) $_smarty_tpl->tpl_vars[$key] = $value;}}?>
<?php if (!function_exists('smarty_template_function_navbars_form_inputs')) {
    function smarty_template_function_navbars_form_inputs($_smarty_tpl,$params) {
    $saved_tpl_vars = $_smarty_tpl->tpl_vars;
    foreach ($_smarty_tpl->smarty->template_functions['navbars_form_inputs']['parameter'] as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);};
    foreach ($params as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);}?><input name="type" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['row']->value['type'])===null||$tmp==='' ? 0 : $tmp);?>
" type="hidden" /><?php if ($_smarty_tpl->tpl_vars['row']->value['type']==1) {?><div class="form-group"><label for="url-<?php echo $_smarty_tpl->tpl_vars['row']->value['index'];?>
" class="col-sm-3 control-label"><?php echo tr('url');?>
</label><div class="col-sm-9"><input dir="ltr" type="text" name="url" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['row']->value['url'])===null||$tmp==='' ? 'http://' : $tmp);?>
" class="form-control input-sm" id="url-<?php echo $_smarty_tpl->tpl_vars['row']->value['index'];?>
" placeholder="<?php echo tr('url');?>
"></div></div><?php } elseif ($_smarty_tpl->tpl_vars['row']->value['type']==2) {?><div class="form-group"><label for="page-<?php echo $_smarty_tpl->tpl_vars['row']->value['index'];?>
" class="col-sm-3 control-label"><?php echo tr('page');?>
</label><div class="col-sm-9"><select id="page-<?php echo $_smarty_tpl->tpl_vars['row']->value['index'];?>
" name="page_id" class="form-control input-sm"><?php  $_smarty_tpl->tpl_vars['page_title'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['page_title']->_loop = false;
 $_smarty_tpl->tpl_vars['page_id'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['pages']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['page_title']->key => $_smarty_tpl->tpl_vars['page_title']->value) {
$_smarty_tpl->tpl_vars['page_title']->_loop = true;
 $_smarty_tpl->tpl_vars['page_id']->value = $_smarty_tpl->tpl_vars['page_title']->key;
?><option <?php if ($_smarty_tpl->tpl_vars['page_id']->value==(($tmp = @$_smarty_tpl->tpl_vars['row']->value['page_id'])===null||$tmp==='' ? '' : $tmp)) {?>selected="true"<?php }?> value="<?php echo $_smarty_tpl->tpl_vars['page_id']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['page_title']->value;?>
</option><?php } ?></select></div></div><?php } elseif ($_smarty_tpl->tpl_vars['row']->value['type']==3) {?><div class="form-group"><label for="cat_id-<?php echo $_smarty_tpl->tpl_vars['row']->value['index'];?>
" class="col-sm-3 control-label"><?php echo tr('category');?>
</label><div class="col-sm-9"><select id="cat_id-<?php echo $_smarty_tpl->tpl_vars['row']->value['index'];?>
" name="cat_id" class="form-control input-sm"><?php  $_smarty_tpl->tpl_vars['cat_title'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['cat_title']->_loop = false;
 $_smarty_tpl->tpl_vars['cat_id'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['catgories_tree']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['cat_title']->key => $_smarty_tpl->tpl_vars['cat_title']->value) {
$_smarty_tpl->tpl_vars['cat_title']->_loop = true;
 $_smarty_tpl->tpl_vars['cat_id']->value = $_smarty_tpl->tpl_vars['cat_title']->key;
?><option <?php if ($_smarty_tpl->tpl_vars['cat_id']->value==(($tmp = @$_smarty_tpl->tpl_vars['row']->value['cat_id'])===null||$tmp==='' ? 0 : $tmp)) {?>selected="true"<?php }?> value="<?php echo $_smarty_tpl->tpl_vars['cat_id']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['cat_title']->value;?>
</option><?php } ?></select></div></div><?php }?><div class="form-group"><label for="title-<?php echo $_smarty_tpl->tpl_vars['row']->value['index'];?>
" class="col-sm-3 control-label"><?php echo tr('title');?>
</label><div class="col-sm-9"><div class="input-group"><span class="input-group-btn"><button <?php if ($_smarty_tpl->tpl_vars['row']->value['noicon']=='true') {?>disabled="disabled"<?php }?> name="icon" data-icon="<?php echo $_smarty_tpl->tpl_vars['row']->value['icon'];?>
" class="btn btn-default btn-sm" data-arrow-prev-icon-class="fa fa-chevron-left" data-arrow-next-icon-class="fa fa-chevron-right" role="iconpicker" data-iconset="fontawesome" ></button></span><input type="text" name="title" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['row']->value['title'])===null||$tmp==='' ? '' : $tmp);?>
" class="form-control input-sm" id="title-<?php echo $_smarty_tpl->tpl_vars['row']->value['index'];?>
" placeholder="<?php echo tr('title');?>
"></div><div class="checkbox"><label><input <?php if ($_smarty_tpl->tpl_vars['row']->value['noicon']=='true') {?>checked="checked"<?php }?> name="noicon" type="checkbox"> <?php echo tr('no_icon');?>
</label></div></div></div><?php $_smarty_tpl->tpl_vars = $saved_tpl_vars;
foreach (Smarty::$global_tpl_vars as $key => $value) if(!isset($_smarty_tpl->tpl_vars[$key])) $_smarty_tpl->tpl_vars[$key] = $value;}}?>
<?php if (!function_exists('smarty_template_function_navbars_builder_item')) {
    function smarty_template_function_navbars_builder_item($_smarty_tpl,$params) {
    $saved_tpl_vars = $_smarty_tpl->tpl_vars;
    foreach ($_smarty_tpl->smarty->template_functions['navbars_builder_item']['parameter'] as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);};
    foreach ($params as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);}?><div class="panel panel-default dd-handle"><div class="panel-heading"><a class="panel-title" data-toggle="collapse" data-parent="#nestable" href="#dd-item-<?php echo $_smarty_tpl->tpl_vars['item']->value['index'];?>
"><i class="fa fa-angle-down"></i> <?php echo (($tmp = @$_smarty_tpl->tpl_vars['item']->value['title'])===null||$tmp==='' ? tr('untitled') : $tmp);?>
</a><div class="action-buttons"><a class="delete-navbar" href="#"><i class="fa fa-trash-o"></i></a></div></div><div id="dd-item-<?php echo $_smarty_tpl->tpl_vars['item']->value['index'];?>
" class="dd-nodrag panel-collapse collapse"><div class="panel-body form-horizontal"><?php smarty_template_function_navbars_form_inputs($_smarty_tpl,array('row'=>$_smarty_tpl->tpl_vars['item']->value));?>
</div></div></div><?php $_smarty_tpl->tpl_vars = $saved_tpl_vars;
foreach (Smarty::$global_tpl_vars as $key => $value) if(!isset($_smarty_tpl->tpl_vars[$key])) $_smarty_tpl->tpl_vars[$key] = $value;}}?>
<?php if (!function_exists('smarty_template_function_navbars_builder')) {
    function smarty_template_function_navbars_builder($_smarty_tpl,$params) {
    $saved_tpl_vars = $_smarty_tpl->tpl_vars;
    foreach ($_smarty_tpl->smarty->template_functions['navbars_builder']['parameter'] as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);};
    foreach ($params as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);}?><ol class="dd-list"><?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['items']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['item']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
 $_smarty_tpl->tpl_vars['item']->index++;
?><li class="dd-item" data-index="<?php echo $_smarty_tpl->tpl_vars['item']->value['index'];?>
"><?php smarty_template_function_navbars_builder_item($_smarty_tpl,array('item'=>$_smarty_tpl->tpl_vars['item']->value));?>
<?php if (isset($_smarty_tpl->tpl_vars['item']->value['children'])==true) {?><?php smarty_template_function_navbars_builder($_smarty_tpl,array('items'=>$_smarty_tpl->tpl_vars['item']->value['children']));?>
<?php }?></li><?php } ?></ol><?php $_smarty_tpl->tpl_vars = $saved_tpl_vars;
foreach (Smarty::$global_tpl_vars as $key => $value) if(!isset($_smarty_tpl->tpl_vars[$key])) $_smarty_tpl->tpl_vars[$key] = $value;}}?>
<?php if (!function_exists('smarty_template_function_posts_filter')) {
    function smarty_template_function_posts_filter($_smarty_tpl,$params) {
    $saved_tpl_vars = $_smarty_tpl->tpl_vars;
    foreach ($_smarty_tpl->smarty->template_functions['posts_filter']['parameter'] as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);};
    foreach ($params as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);}?><div id="posts-filter" class="navbar-collapse collapse in <?php if ($_smarty_tpl->tpl_vars['config']->value['full_width']==false) {?>brfilter<?php }?>"><ul class="nav navbar-nav filters hide-if-no-js"><p class="navbar-text"><i class="fa fa-retweet"></i> <?php echo tr('show');?>
: </p><li class="filter active"><a id="all" href="#"><?php echo tr('all');?>
</a></li><li class="filter"><a id="last" href="#"><?php echo tr('last_added');?>
</a></li><li class="filter"><a id="watch" href="#"><?php echo tr('now_playing');?>
</a></li></ul><ul class="nav navbar-nav posts-sorter hide-if-no-js"><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-sort"></i> <?php echo tr('sort_by');?>
 <span class="sort-text"><?php echo tr('date_added');?>
</span> <b class="caret"></b></a><ul class="dropdown-menu filters"><li class="sort active"><a id="date" href="#"><?php echo tr('date_added');?>
</a></li><li class="sort"><a id="vote" href="#"><?php echo tr('most_liked');?>
</a></li><li class="sort"><a id="views" href="#"><?php echo tr('most_popular');?>
</a></li><li class="sort"><a id="comments" href="#"><?php echo tr('most_discussed');?>
</a></li></ul></li></ul></div><?php $_smarty_tpl->tpl_vars = $saved_tpl_vars;
foreach (Smarty::$global_tpl_vars as $key => $value) if(!isset($_smarty_tpl->tpl_vars[$key])) $_smarty_tpl->tpl_vars[$key] = $value;}}?>
<?php if (!function_exists('smarty_template_function_user_profile_header')) {
    function smarty_template_function_user_profile_header($_smarty_tpl,$params) {
    $saved_tpl_vars = $_smarty_tpl->tpl_vars;
    foreach ($_smarty_tpl->smarty->template_functions['user_profile_header']['parameter'] as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);};
    foreach ($params as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);}?><div class="profile"><div class="profile-headerbg"><div class="profile-cover"><img src="<?php echo $_smarty_tpl->tpl_vars['row']->value['coverPhoto'];?>
" alt="<?php echo $_smarty_tpl->tpl_vars['row']->value['displayName'];?>
"/></div><div class="cover-border"></div><div class="profile-photo"><img class="img-responsive" alt="<?php echo $_smarty_tpl->tpl_vars['row']->value['displayName'];?>
" src="<?php echo $_smarty_tpl->tpl_vars['row']->value['photoURL'];?>
"></div><h2 class="profile-name"><?php echo $_smarty_tpl->tpl_vars['row']->value['displayName'];?>
</h2></div><nav class="navbar navbar-default" role="navigation"><div class="row"><div class="col-md-10 col-md-offset-2"><div class="navbar-header"><button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#profile-collapse"><span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button></div><div class="collapse navbar-collapse" id="profile-collapse"><ul class="nav navbar-nav"><li <?php if ($_smarty_tpl->tpl_vars['case']->value=='wall') {?>class="active"<?php }?>><a href="<?php echo $_smarty_tpl->tpl_vars['row']->value['profileURLs']['profile'];?>
"><?php echo tr('wall');?>
</a></li><li <?php if ($_smarty_tpl->tpl_vars['case']->value=='posts') {?>class="active"<?php }?>><a href="<?php echo $_smarty_tpl->tpl_vars['row']->value['profileURLs']['posts'];?>
"><i class="fa fa-cloud-upload"></i> <?php echo tr('posts');?>
 (<?php echo $_smarty_tpl->tpl_vars['row']->value['posts'];?>
)</a></li><li <?php if ($_smarty_tpl->tpl_vars['case']->value=='fav') {?>class="active"<?php }?>><a href="<?php echo $_smarty_tpl->tpl_vars['row']->value['profileURLs']['favorites'];?>
"><i class="fa fa-thumbs-o-up"></i> <?php echo tr('favorites');?>
 (<?php echo $_smarty_tpl->tpl_vars['row']->value['votes']['gave']['up'];?>
)</a></li><?php if ($_smarty_tpl->tpl_vars['user']->value['isLoggedIn']&&$_smarty_tpl->tpl_vars['row']->value['id']==$_smarty_tpl->tpl_vars['user']->value['id']) {?><li><a href="<?php echo $_smarty_tpl->tpl_vars['config']->value['base_url'];?>
/account.html"><i class="fa fa-cogs"></i> <?php echo tr('account_settings');?>
</a></li><?php }?></ul></div></div></div></nav></div><?php $_smarty_tpl->tpl_vars = $saved_tpl_vars;
foreach (Smarty::$global_tpl_vars as $key => $value) if(!isset($_smarty_tpl->tpl_vars[$key])) $_smarty_tpl->tpl_vars[$key] = $value;}}?>
<?php }} ?>
