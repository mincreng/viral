<?php /* Smarty version Smarty-3.1.18, created on 2015-05-24 23:32:09
         compiled from "D:\nx\htdocs\Mediat\WebFairy-Mediat\1.4\tpl\layout.tpl" */ ?>
<?php /*%%SmartyHeaderCode:218555623549022a19-31422190%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '09f95056609304442867b904e1d4bad9b32ddd17' => 
    array (
      0 => 'D:\\nx\\htdocs\\Mediat\\WebFairy-Mediat\\1.4\\tpl\\layout.tpl',
      1 => 1432499407,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '218555623549022a19-31422190',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'config' => 0,
    'title' => 0,
    'page' => 0,
    'meta_key' => 0,
    'meta_value' => 0,
    'upload' => 0,
    'html' => 0,
    'html_block' => 0,
    'logo' => 0,
    'return_url' => 0,
    'user' => 0,
    'EnabledLoginProviders' => 0,
    'provider' => 0,
    'Navbars' => 0,
    'tpl_files' => 0,
    'file' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_5562354987e614_55104373',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5562354987e614_55104373')) {function content_5562354987e614_55104373($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include 'D:/nx/htdocs/Mediat/WebFairy-Mediat/1.4/lib/Smarty/plugins\\modifier.date_format.php';
?><!doctype html>
<html xmlns:og="http://ogp.me/ns#" xmlns:fb="https://www.facebook.com/2008/fbml" xml:lang="<?php echo $_smarty_tpl->tpl_vars['config']->value['lang'];?>
" lang="<?php echo $_smarty_tpl->tpl_vars['config']->value['lang'];?>
">
    <head>
        <title><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</title>
    	<meta http-equiv="content-type" content="text/html; charset=<?php echo $_smarty_tpl->tpl_vars['config']->value['charset'];?>
" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
         
    <?php if ($_smarty_tpl->tpl_vars['page']->value['noindex']) {?>
        <meta name="robots" content="noindex" />
    <?php }?>
                
    <?php if ($_smarty_tpl->tpl_vars['page']->value['redirect']) {?>        
        <meta http-equiv="refresh" content="<?php echo $_smarty_tpl->tpl_vars['page']->value['redirect_time'];?>
;url=<?php echo $_smarty_tpl->tpl_vars['page']->value['redirect_url'];?>
"> 
    <?php }?>        
        
        <meta name="description" content="<?php echo $_smarty_tpl->tpl_vars['page']->value['description'];?>
"/>
        <meta name="keywords" content="<?php echo $_smarty_tpl->tpl_vars['page']->value['keywords'];?>
"/>
        
        <link rel="alternate" type="application/rss+xml" title="<?php echo $_smarty_tpl->tpl_vars['config']->value['site_name'];?>
" href="<?php echo $_smarty_tpl->tpl_vars['config']->value['site_url'];?>
/rss.xml">
        
    <?php if (empty($_smarty_tpl->tpl_vars['config']->value['fb_admins'])==false) {?>
        <meta property="fb:admins" content="<?php echo $_smarty_tpl->tpl_vars['config']->value['fb_admins'];?>
" />      
    <?php }?>  
    
    <?php if (empty($_smarty_tpl->tpl_vars['config']->value['Facebook_key'])==false) {?>
        <meta property="fb:app_id" content="<?php echo $_smarty_tpl->tpl_vars['config']->value['Facebook_key'];?>
"/>
    <?php }?>  
           
    <?php  $_smarty_tpl->tpl_vars['meta_value'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['meta_value']->_loop = false;
 $_smarty_tpl->tpl_vars['meta_key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['page']->value['property_meta']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['meta_value']->key => $_smarty_tpl->tpl_vars['meta_value']->value) {
$_smarty_tpl->tpl_vars['meta_value']->_loop = true;
 $_smarty_tpl->tpl_vars['meta_key']->value = $_smarty_tpl->tpl_vars['meta_value']->key;
?>
        <meta property="<?php echo $_smarty_tpl->tpl_vars['meta_key']->value;?>
" content="<?php echo $_smarty_tpl->tpl_vars['meta_value']->value;?>
"/> 
    <?php } ?>
    
    <?php if (empty($_smarty_tpl->tpl_vars['page']->value['canonical'])==false) {?>        
        <link rel="canonical" href="<?php echo $_smarty_tpl->tpl_vars['page']->value['canonical'];?>
"/>
    <?php }?> 
        <link href='http://fonts.googleapis.com/css?family=<?php echo urlencode($_smarty_tpl->tpl_vars['config']->value['font_family']);?>
' rel='stylesheet' type='text/css' />
        
        <link href='<?php echo $_smarty_tpl->tpl_vars['config']->value['base_url'];?>
/assets/icons/font-awesome.css' rel='stylesheet' type='text/css' />

        <link href="<?php echo $_smarty_tpl->tpl_vars['config']->value['base_url'];?>
/assets/css/bootstrap.min.css" rel="stylesheet" />

        <link href="<?php echo $_smarty_tpl->tpl_vars['config']->value['base_url'];?>
/assets/css/style.css" rel="stylesheet" />
        <link href='http://fonts.googleapis.com/css?family=Hind:600,400,500' rel='stylesheet' type='text/css'>
   
        <!--[if lt IE 9]>
          <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

        <link rel="shortcut icon" href="<?php echo $_smarty_tpl->tpl_vars['config']->value['base_url'];?>
/favicon.ico" type="image/x-icon" />
        <link rel="icon" href="<?php echo $_smarty_tpl->tpl_vars['config']->value['base_url'];?>
/favicon.ico" type="image/x-icon" />

        <script type="text/javascript">
        	var site_url = '<?php echo $_smarty_tpl->tpl_vars['config']->value['base_url'];?>
';
        	var site_filetypes = /(\.|\/)(<?php echo implode("|",$_smarty_tpl->tpl_vars['upload']->value['extensions']);?>
)$/i; 
            var site_maxfilesize = <?php echo $_smarty_tpl->tpl_vars['upload']->value['max_file_size'];?>
;
            var is_rtl = <?php if ($_smarty_tpl->tpl_vars['config']->value['rtl']) {?>true<?php } else { ?>false<?php }?>;
        </script> 
        
    <?php  $_smarty_tpl->tpl_vars['html_block'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['html_block']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['html']->value['header']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['html_block']->key => $_smarty_tpl->tpl_vars['html_block']->value) {
$_smarty_tpl->tpl_vars['html_block']->_loop = true;
?><?php echo $_smarty_tpl->tpl_vars['html_block']->value;?>
<?php } ?>

        <?php if ($_smarty_tpl->tpl_vars['config']->value['rtl']) {?>
            <link href="<?php echo $_smarty_tpl->tpl_vars['config']->value['base_url'];?>
/assets/css/bootstrap-rtl.min.css" rel="stylesheet" />
            <link href="<?php echo $_smarty_tpl->tpl_vars['config']->value['base_url'];?>
/assets/css/style-rtl.css" rel="stylesheet" />
        <?php }?>
            
    <style type="text/css">
   
   <?php if ($_smarty_tpl->tpl_vars['config']->value['full_width']==false) {?>
       <?php if ($_smarty_tpl->tpl_vars['config']->value['c_tablet_width']>0) {?>
            @media (min-width: 768px) {
              .container {
                width: <?php echo $_smarty_tpl->tpl_vars['config']->value['c_tablet_width'];?>
px;
              }
            }
       <?php }?>
       <?php if ($_smarty_tpl->tpl_vars['config']->value['c_desktop_width']>0) {?>
            @media (min-width: 992px) {
              .container {
                width: <?php echo $_smarty_tpl->tpl_vars['config']->value['c_desktop_width'];?>
px;
              }
            }
       <?php }?>
       <?php if ($_smarty_tpl->tpl_vars['config']->value['c_ldesktop_width']>0) {?>
            @media (min-width: 1200px) {
              .container {
                width: <?php echo $_smarty_tpl->tpl_vars['config']->value['c_ldesktop_width'];?>
px;
              }
            }   
       <?php }?>
   <?php }?>
    
body {
    font-family: '<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['config']->value['font_family']);?>
',Arial, Helvetica, sans-serif;
    font-size: <?php echo (($tmp = @$_smarty_tpl->tpl_vars['config']->value['font_size'])===null||$tmp==='' ? 14 : $tmp);?>
px;
} 
a:hover,
a:focus {
  color: <?php echo $_smarty_tpl->tpl_vars['config']->value['theme_color'];?>
;
}
.bold{
    color: <?php echo $_smarty_tpl->tpl_vars['config']->value['theme_color'];?>
;
}
.form-control:focus{
    border-color: <?php echo $_smarty_tpl->tpl_vars['config']->value['theme_color'];?>
;
}
#nprogress .bar {
   background-color: <?php echo $_smarty_tpl->tpl_vars['config']->value['theme_color'];?>
 !important;  
}
#nprogress .peg {
    box-shadow: 0 0 10px <?php echo $_smarty_tpl->tpl_vars['config']->value['theme_color'];?>
, 0 0 5px <?php echo $_smarty_tpl->tpl_vars['config']->value['theme_color'];?>
;
}
.back-to-top,
.navbar-nav > .active > a,
.navbar-nav > .active > a:hover, 
.navbar-nav > .active > a:focus,
.dropdown-menu > .active > a,
.dropdown-menu > li > a:hover,
.dropdown-menu > li > a:focus
{
    background-color: <?php echo $_smarty_tpl->tpl_vars['config']->value['theme_color'];?>
 !important; 
    color:#fff  !important;
}
.header-navbar .nav-main > li + li:hover{
    border-color: <?php echo $_smarty_tpl->tpl_vars['config']->value['theme_color'];?>
;
}
.boxed{
    border-top-color: <?php echo $_smarty_tpl->tpl_vars['config']->value['theme_color'];?>
;  
}
.boxed h1 i{
    color: <?php echo $_smarty_tpl->tpl_vars['config']->value['theme_color'];?>
;
}
.header-body .logo a{
    color: <?php echo $_smarty_tpl->tpl_vars['config']->value['theme_color'];?>
;
}
.navbar-form .btn
{
    background:<?php echo $_smarty_tpl->tpl_vars['config']->value['theme_color'];?>
;
}
.header-top .navbar{
    border-top-color: <?php echo $_smarty_tpl->tpl_vars['config']->value['theme_color'];?>
;
}
.header-top .form-control:focus{
    border-color: <?php echo $_smarty_tpl->tpl_vars['config']->value['theme_color'];?>
;
}
.header-userbar .btn-login,
.btn-share
{
    background:<?php echo $_smarty_tpl->tpl_vars['config']->value['theme_color'];?>
;
    border-color: <?php echo $_smarty_tpl->tpl_vars['config']->value['theme_color'];?>
;
    color:#fff;
}
.header-userbar .checkbox a{
    color:<?php echo $_smarty_tpl->tpl_vars['config']->value['theme_color'];?>
;
}
.menu-parent > li > .accordion-group > a.active{
    background:<?php echo $_smarty_tpl->tpl_vars['config']->value['theme_color'];?>
;
} 
.fbform legend{
    background:<?php echo $_smarty_tpl->tpl_vars['config']->value['theme_color'];?>
;
}
.side-menu a:hover ,
.side-menu > li a.active{
    color: <?php echo $_smarty_tpl->tpl_vars['config']->value['theme_color'];?>
;
}
.posts-list-a .post-item:hover .post-icon{ background: <?php echo $_smarty_tpl->tpl_vars['config']->value['theme_color'];?>
;}

.swiper-pagination-bullet-active { background: <?php echo $_smarty_tpl->tpl_vars['config']->value['theme_color'];?>
;}
.swiper-button-next, .swiper-button-prev{ color:<?php echo $_smarty_tpl->tpl_vars['config']->value['theme_color'];?>
;}
    </style>            
            
    </head>
    <body class="no-js" itemscope itemtype="http://schema.org/WebPage">
    
    <script type="text/javascript">
    	document.body.className = document.body.className.replace('no-js','js');
    </script>   

    <div class="container<?php if ($_smarty_tpl->tpl_vars['config']->value['full_width']) {?>-fluid<?php }?>">
    
        <header>
            <div class="header-top">
                <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
                <div class="container-fluid">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#header-top-navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <?php echo $_smarty_tpl->tpl_vars['logo']->value;?>

				</div>
				
				<div class="collapse navbar-collapse" id="header-top-navbar-collapse">
				    <div class="header-search">
    					<form class="navbar-form" action="<?php echo $_smarty_tpl->tpl_vars['config']->value['base_url'];?>
/search.html" role="search">
    						<div class="form-group">
                                <div class="input-group">
                                  <input type="text" class="form-control input-sm" name="query" value="<?php echo (($tmp = @$_GET['query'])===null||$tmp==='' ? '' : $tmp);?>
" placeholder="<?php echo tr('search_ph');?>
">
                                  <span class="input-group-btn">
                                    <button class="btn btn-sm btn-default" type="submit"><i class="fa fa-search"></i></button>
                                  </span>
                                </div>                          
    						</div>
    					</form>
                    </div>
                    
                    <div class="header-userbar">   
                        <div class="userbar-form hidden-sm">
                        <?php if ($_smarty_tpl->tpl_vars['page']->value['login_form']) {?>
                            <form method="post" class="form-inline" action="<?php echo $_smarty_tpl->tpl_vars['config']->value['base_url'];?>
/login.php?return=<?php echo $_smarty_tpl->tpl_vars['return_url']->value;?>
">
                              <div class="checkbox">
                                <label>
                                  <input name="remember" value="true" type="checkbox">&nbsp;<?php echo tr('remember_me');?>

                                </label>
                                <a href="<?php echo $_smarty_tpl->tpl_vars['config']->value['base_url'];?>
/recover.html"><?php echo tr('forgot_password');?>
 ?</a>
                              </div>
                              
                              <div class="form-group">
                                <label class="sr-only" for="email"><?php echo tr('email');?>
</label>
                                <input type="text" name="email" class="form-control input-sm" id="email" placeholder="<?php echo tr('email');?>
">
                              </div>
                              
                              <div class="form-group">
                                <label class="sr-only" for="password"><?php echo tr('password');?>
</label>
                                <div class="input-group">
                                  <input type="password" name="password" class="form-control input-sm" id="password" placeholder="<?php echo tr('password');?>
">
                                  <span class="input-group-btn">
                                    <button class="btn btn-sm btn-default btn-login" type="submit">
                                        <i class="fa fa-sign-in"></i>
                                    </button>
                                  </span>
                                </div>                                
                              </div>
                              <input type="hidden" value="login" name="form_name" >
                              <input type="hidden" value="<?php echo $_smarty_tpl->tpl_vars['page']->value['login_csrf_str'];?>
" name="login">  
                            </form> 
                        <?php }?>                          
                        </div>                              
                        <div class="userbar-btns">                        
                          <?php if ($_smarty_tpl->tpl_vars['user']->value['isLoggedIn']) {?>              
                                <?php if ($_smarty_tpl->tpl_vars['user']->value['isManager']) {?>
                                    <a class="btn btn-sm btn-default" href="<?php echo $_smarty_tpl->tpl_vars['config']->value['base_url'];?>
/panel.html" role="button"><i class="fa fa-cogs"></i> <?php echo tr('panel');?>
</a>                                                      
                                <?php }?>                                 
                                <?php if (($_smarty_tpl->tpl_vars['config']->value['user_posting']&&$_smarty_tpl->tpl_vars['user']->value['isLoggedIn'])||$_smarty_tpl->tpl_vars['user']->value['isManager']) {?>
                                  <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown">
                                      <i class="fa fa-plus"></i> <?php echo tr('add_post');?>

                                      <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a href="<?php echo $_smarty_tpl->tpl_vars['config']->value['base_url'];?>
/post.php?via=upload"><i class="fa fa-upload"></i> <?php echo tr('post_upload');?>
</a></li>
                                        <li><a href="<?php echo $_smarty_tpl->tpl_vars['config']->value['base_url'];?>
/post.php?via=write"><i class="fa fa-pencil"></i> <?php echo tr('post_write');?>
</a></li>
                                        <li><a href="<?php echo $_smarty_tpl->tpl_vars['config']->value['base_url'];?>
/post.php?via=link"><i class="fa fa-link"></i> <?php echo tr('post_link');?>
</a></li>
                                    </ul>
                                  </div>  
                                <?php }?> 
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown">
                                      <?php echo $_smarty_tpl->tpl_vars['user']->value['displayName'];?>

                                      <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu account-dropdown">
                                        <li><a href="<?php echo $_smarty_tpl->tpl_vars['user']->value['profileURLs']['profile'];?>
"><i class="fa fa-user"></i> <?php echo tr('my_profile');?>
</a></li>
                                        <li><a href="<?php echo $_smarty_tpl->tpl_vars['config']->value['base_url'];?>
/account.html"><i class="fa fa-cogs"></i> <?php echo tr('account_settings');?>
</a></li>
                                        <li><a href="<?php echo $_smarty_tpl->tpl_vars['config']->value['base_url'];?>
/logout.html?return=<?php echo $_smarty_tpl->tpl_vars['return_url']->value;?>
" ><i class="fa fa-sign-out"></i> <?php echo tr('logout');?>
</a></li>
                                    </ul>
                                </div>                                                                                                               
                          <?php } else { ?>
                            <?php if (count($_smarty_tpl->tpl_vars['EnabledLoginProviders']->value)>0) {?>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-login dropdown-toggle" data-toggle="dropdown">
                                      <i class="fa fa-user"></i>
                                      <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu social-logins">
                                        <?php  $_smarty_tpl->tpl_vars['provider'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['provider']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['EnabledLoginProviders']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['provider']->key => $_smarty_tpl->tpl_vars['provider']->value) {
$_smarty_tpl->tpl_vars['provider']->_loop = true;
?>
                                        <li><a class="<?php echo strtolower($_smarty_tpl->tpl_vars['provider']->value);?>
" href="<?php echo $_smarty_tpl->tpl_vars['config']->value['base_url'];?>
/auth.html?provider=<?php echo $_smarty_tpl->tpl_vars['provider']->value;?>
&return=<?php echo $_smarty_tpl->tpl_vars['return_url']->value;?>
"><i class="fa fa-<?php echo strtolower($_smarty_tpl->tpl_vars['provider']->value);?>
"></i></a></li>
                                        <?php } ?>
                                    </ul>
                                </div>  
                            <?php }?>                        
                            <a class="btn btn-sm btn-default visible-sm-inline" href="<?php echo $_smarty_tpl->tpl_vars['config']->value['base_url'];?>
/login.html?return=<?php echo $_smarty_tpl->tpl_vars['return_url']->value;?>
" role="button"><i class="fa fa-sign-in"></i> <?php echo tr('login');?>
</a>                            
                            <?php if ($_smarty_tpl->tpl_vars['config']->value['registration']) {?>
                                <a class="btn btn-sm btn-default" href="<?php echo $_smarty_tpl->tpl_vars['config']->value['base_url'];?>
/register.html?return=<?php echo $_smarty_tpl->tpl_vars['return_url']->value;?>
" role="button"><i class="fa fa-users"></i> <?php echo tr('register');?>
</a>                                                      
                            <?php }?>  
                          <?php }?>  
                        </div>                                                          
                    </div>                    
				</div>
				</div>
			</nav>            
            
            </div>

            <div class="header-bottom">
                <nav class="navbar header-navbar navbar-default" role="navigation">
                  <div class="container-fluid">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#header-navbar-collapse">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>                      
                      <a class="navbar-brand visible-xs" href="<?php echo $_smarty_tpl->tpl_vars['config']->value['site_url'];?>
"><?php echo $_smarty_tpl->tpl_vars['config']->value['site_name'];?>
</a>
                    </div>

                    <div class="collapse navbar-collapse" id="header-navbar-collapse">
                      <ul class="nav navbar-nav nav-main deep-active">
                        <?php smarty_template_function_navbar_render($_smarty_tpl,array('data'=>$_smarty_tpl->tpl_vars['Navbars']->value['header']));?>

                      </ul>
                      
                      <ul class="nav navbar-nav nav-social">
                        <?php if (empty($_smarty_tpl->tpl_vars['config']->value['fb_page_uname'])==false) {?>
                          <li class="n-facebook" rel="tooltip" title="" data-original-title="<?php echo tr('facebook');?>
" data-placement="bottom">
                              <a href="https://www.facebook.com/<?php echo $_smarty_tpl->tpl_vars['config']->value['fb_page_uname'];?>
"><i class="fa fa-facebook"></i> </a>
                          </li>                        
                        <?php }?>
                        <?php if (empty($_smarty_tpl->tpl_vars['config']->value['twitter_uname'])==false) {?>
                             <li class="n-twit" rel="tooltip" title="" data-original-title="<?php echo tr('twitter');?>
" data-placement="bottom">
                                <a href="https://www.twitter.com/<?php echo $_smarty_tpl->tpl_vars['config']->value['twitter_uname'];?>
"><i class="fa fa-twitter"></i></a>
                             </li>
                        <?php }?>
                        <?php if (empty($_smarty_tpl->tpl_vars['config']->value['youtube_uname'])==false) {?>
                             <li class="n-tube" rel="tooltip" title="" data-original-title="<?php echo tr('youtube');?>
" data-placement="bottom">
                                <a href="https://www.youtube.com/<?php echo $_smarty_tpl->tpl_vars['config']->value['youtube_uname'];?>
"><i class="fa fa-youtube"></i></a>
                             </li>
                        <?php }?>                       
                        <?php if (empty($_smarty_tpl->tpl_vars['config']->value['instagram_uname'])==false) {?>
                             <li class="n-instagram" rel="tooltip" title="" data-original-title="<?php echo tr('instagram');?>
" data-placement="bottom">
                                <a href="https://www.instagram.com/<?php echo $_smarty_tpl->tpl_vars['config']->value['instagram_uname'];?>
"><i class="fa fa-instagram"></i></a>
                             </li>
                        <?php }?>                       
                        <?php if (empty($_smarty_tpl->tpl_vars['config']->value['gplus_id'])==false) {?>
                          <li class="n-google" rel="tooltip" title="" data-original-title="<?php echo tr('Google');?>
" data-placement="bottom">
                              <a href="https://plus.google.com/<?php echo $_smarty_tpl->tpl_vars['config']->value['gplus_id'];?>
"><i class="fa fa-google-plus"></i> </a>
                          </li>
                        <?php }?>

                      </ul>
                    </div>
                  </div>
                </nav>            
            </div>
            <?php if ((boolean) $_smarty_tpl->tpl_vars['config']->value['subscription_box']==true) {?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="socialbar hidden-xs hidden-sm">
                            <i class="fa fa-rss"></i> <?php echo tr('subscription_box_1');?>
         
                            <form class="form-inline" onsubmit="window.open('http://feedburner.google.com/fb/a/mailverify?uri=<?php echo $_smarty_tpl->tpl_vars['config']->value['feedburner_uri'];?>
', 'popupwindow', 'scrollbars=yes,width=550,height=520');return true" target="popupwindow" method="post" action="http://feedburner.google.com/fb/a/mailverify">
                                <div class="input-group">
                                    <input type="text" class="form-control input-sm" name="email" placeholder="<?php echo tr('your_email_here');?>
">
                                    <input type="hidden" name="uri" value="<?php echo $_smarty_tpl->tpl_vars['config']->value['feedburner_uri'];?>
" />
                                    <input type="hidden" value="en_US" name="loc" />      
                                    <span class="input-group-btn">
                                        <button class="btn btn-sm btn-default" type="submit"><i class="fa fa-check"></i> <?php echo tr('subscribe');?>
</button>
                                    </span>
                                </div>
                            </form> 
                            &nbsp;<?php echo tr('subscription_box_2');?>

                        </div> 
                        <div class="socialbar visible-xs visible-sm">
                            <form onsubmit="window.open('http://feedburner.google.com/fb/a/mailverify?uri=<?php echo $_smarty_tpl->tpl_vars['config']->value['feedburner_uri'];?>
', 'popupwindow', 'scrollbars=yes,width=550,height=520');return true" target="popupwindow" method="post" action="http://feedburner.google.com/fb/a/mailverify">
                                <div class="input-group">
                                    <input type="text" class="form-control input-sm" name="email" placeholder="<?php echo tr('subscription_sm_ph');?>
">
                                    <input type="hidden" name="uri" value="<?php echo $_smarty_tpl->tpl_vars['config']->value['feedburner_uri'];?>
" />
                                    <input type="hidden" value="en_US" name="loc" />      
                                    <span class="input-group-btn">
                                        <button class="btn btn-sm btn-default" type="submit"><?php echo tr('subscribe');?>
</button>
                                    </span>
                                </div>
                            </form>
                        </div>          
                    </div>
                </div>
            <?php }?>            
        </header>

        <?php  $_smarty_tpl->tpl_vars['file'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['file']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['tpl_files']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['file']->key => $_smarty_tpl->tpl_vars['file']->value) {
$_smarty_tpl->tpl_vars['file']->_loop = true;
?>
            <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['file']->value, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
 
        <?php } ?>

        <footer id="footer">
            <div class="footer">
                <div class="row">
                    <div class="col-md-12">
                        <div>
                            <ul>
                                <?php if (empty($_smarty_tpl->tpl_vars['config']->value['fb_page_uname'])==false) {?>
                                    <li><a href="https://www.facebook.com/<?php echo $_smarty_tpl->tpl_vars['config']->value['fb_page_uname'];?>
"><span class="fa-stack"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-facebook fa-stack-1x fa-inverse"></i></span></a></li>
                                <?php }?>
                                <?php if (empty($_smarty_tpl->tpl_vars['config']->value['twitter_uname'])==false) {?>
                                     <li><a href="https://www.twitter.com/<?php echo $_smarty_tpl->tpl_vars['config']->value['twitter_uname'];?>
"><span class="fa-stack"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-twitter fa-stack-1x fa-inverse"></i></span></a></li>
                                <?php }?>
                                <?php if (empty($_smarty_tpl->tpl_vars['config']->value['youtube_uname'])==false) {?>
                                     <li><a href="https://www.youtube.com/<?php echo $_smarty_tpl->tpl_vars['config']->value['youtube_uname'];?>
"><span class="fa-stack"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-youtube fa-stack-1x fa-inverse"></i></span></a></li>
                                <?php }?> 
                                    <li><a href="<?php echo $_smarty_tpl->tpl_vars['config']->value['base_url'];?>
/rss.xml"><span class="fa-stack"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-rss fa-stack-1x fa-inverse"></i></span></a></li>                           
                            </ul>
                        </div>
                        <div><small><?php echo $_smarty_tpl->tpl_vars['config']->value['site_name'];?>
 &copy; <?php echo smarty_modifier_date_format(time(),"%Y");?>
 · <?php echo $_smarty_tpl->tpl_vars['config']->value['copyrights'];?>
 · <a href="http://www.mediat.org/">Mediat</a></small></div>
                        <div>
                            <ul><?php smarty_template_function_navbar_render($_smarty_tpl,array('data'=>$_smarty_tpl->tpl_vars['Navbars']->value['footer']));?>
</ul>                      
                        </div>
                    </div>
                </div>
            </div>     
        </footer> 
        <a id="back-to-top" href="#" class="back-to-top">
            <span class="fa fa-chevron-up"></span>
        </a>  
    </div>
    
    <?php if (@constant('DEV_MODE')==true) {?>
        <script src="<?php echo $_smarty_tpl->tpl_vars['config']->value['base_url'];?>
/assets/js/jquery-2.1.1.min.js"></script>
        <script src="<?php echo $_smarty_tpl->tpl_vars['config']->value['base_url'];?>
/assets/js/bootstrap.min.js"></script>
    <?php } else { ?>
        <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script type="text/javascript">
        if (typeof jQuery == 'undefined') {
            document.write(unescape("%3Cscript src='<?php echo $_smarty_tpl->tpl_vars['config']->value['base_url'];?>
/assets/js/jquery-2.1.1.min.js' type='text/javascript'%3E%3C/script%3E"));
        }
        </script>        
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <?php }?>
    
    <script src="<?php echo $_smarty_tpl->tpl_vars['config']->value['base_url'];?>
/assets/js/jquery.wookmark.min.js"></script>
    <script src="<?php echo $_smarty_tpl->tpl_vars['config']->value['base_url'];?>
/assets/js/imagesloaded.min.js"></script>
    
    <script src="<?php echo $_smarty_tpl->tpl_vars['config']->value['base_url'];?>
/assets/js/timeago/jquery.timeago.js"></script>
    <script src="<?php echo $_smarty_tpl->tpl_vars['config']->value['base_url'];?>
/assets/js/timeago/locales/jquery.timeago.<?php echo $_smarty_tpl->tpl_vars['config']->value['lang'];?>
.js"></script>
    
    <script src="<?php echo $_smarty_tpl->tpl_vars['config']->value['base_url'];?>
/assets/js/nprogress.js"></script>
    <script src="<?php echo $_smarty_tpl->tpl_vars['config']->value['base_url'];?>
/assets/js/front.js"></script>

    <?php  $_smarty_tpl->tpl_vars['html_block'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['html_block']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['html']->value['footer']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['html_block']->key => $_smarty_tpl->tpl_vars['html_block']->value) {
$_smarty_tpl->tpl_vars['html_block']->_loop = true;
?><?php echo $_smarty_tpl->tpl_vars['html_block']->value;?>
<?php } ?>        
         
    </body>  
</html><?php }} ?>
