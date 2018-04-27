<!doctype html>
<html xmlns:og="http://ogp.me/ns#" xmlns:fb="https://www.facebook.com/2008/fbml" xml:lang="{$config.lang}" lang="{$config.lang}">
    <head>
        <title>{$title}</title>
    	<meta http-equiv="content-type" content="text/html; charset={$config.charset}" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
         
    {if $page.noindex}
        <meta name="robots" content="noindex" />
    {/if}
                
    {if $page.redirect}        
        <meta http-equiv="refresh" content="{$page.redirect_time};url={$page.redirect_url}"> 
    {/if}        
        
        <meta name="description" content="{$page.description}"/>
        <meta name="keywords" content="{$page.keywords}"/>
        
        <link rel="alternate" type="application/rss+xml" title="{$config.site_name}" href="{$config.site_url}/rss.xml">
        
    {if empty($config.fb_admins) == false}
        <meta property="fb:admins" content="{$config.fb_admins}" />      
    {/if}  
    
    {if empty($config.Facebook_key) == false}
        <meta property="fb:app_id" content="{$config.Facebook_key}"/>
    {/if}  
           
    {foreach $page.property_meta as $meta_key => $meta_value}
        <meta property="{$meta_key}" content="{$meta_value}"/> 
    {/foreach}
    
    {if empty($page.canonical) == false}        
        <link rel="canonical" href="{$page.canonical}"/>
    {/if} 
        <link href='http://fonts.googleapis.com/css?family={$config.font_family|urlencode}' rel='stylesheet' type='text/css' />
        
        <link href='{$config.base_url}/assets/icons/font-awesome.css' rel='stylesheet' type='text/css' />

        <link href="{$config.base_url}/assets/css/bootstrap.min.css" rel="stylesheet" />

        <link href="{$config.base_url}/assets/css/style.css" rel="stylesheet" />
        <link href='http://fonts.googleapis.com/css?family=Hind:600,400,500' rel='stylesheet' type='text/css'>
   
        <!--[if lt IE 9]>
          <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

        <link rel="shortcut icon" href="{$config.base_url}/favicon.ico" type="image/x-icon" />
        <link rel="icon" href="{$config.base_url}/favicon.ico" type="image/x-icon" />

        <script type="text/javascript">
        	var site_url = '{$config.base_url}';
        	var site_filetypes = /(\.|\/)({"|"|implode:$upload.extensions})$/i; 
            var site_maxfilesize = {$upload.max_file_size};
            var is_rtl = {if $config.rtl}true{else}false{/if};
        </script> 
        
    {foreach $html.header as $html_block}{$html_block}{/foreach}

        {if $config.rtl}
            <link href="{$config.base_url}/assets/css/bootstrap-rtl.min.css" rel="stylesheet" />
            <link href="{$config.base_url}/assets/css/style-rtl.css" rel="stylesheet" />
        {/if}
            
    <style type="text/css">
   
   {if $config.full_width == false}
       {if $config.c_tablet_width > 0}
            @media (min-width: 768px) {
              .container {
                width: {$config.c_tablet_width}px;
              }
            }
       {/if}
       {if $config.c_desktop_width > 0}
            @media (min-width: 992px) {
              .container {
                width: {$config.c_desktop_width}px;
              }
            }
       {/if}
       {if $config.c_ldesktop_width > 0}
            @media (min-width: 1200px) {
              .container {
                width: {$config.c_ldesktop_width}px;
              }
            }   
       {/if}
   {/if}
    
body {
    font-family: '{$config.font_family|htmlspecialchars}',Arial, Helvetica, sans-serif;
    font-size: {$config.font_size|default:14}px;
} 
a:hover,
a:focus {
  color: {$config.theme_color};
}
.bold{
    color: {$config.theme_color};
}
.form-control:focus{
    border-color: {$config.theme_color};
}
#nprogress .bar {
   background-color: {$config.theme_color} !important;  
}
#nprogress .peg {
    box-shadow: 0 0 10px {$config.theme_color}, 0 0 5px {$config.theme_color};
}
.back-to-top,
.navbar-nav > .active > a,
.navbar-nav > .active > a:hover, 
.navbar-nav > .active > a:focus,
.dropdown-menu > .active > a,
.dropdown-menu > li > a:hover,
.dropdown-menu > li > a:focus
{
    background-color: {$config.theme_color} !important; 
    color:#fff  !important;
}
.header-navbar .nav-main > li + li:hover{
    border-color: {$config.theme_color};
}
.boxed{
    border-top-color: {$config.theme_color};  
}
.boxed h1 i{
    color: {$config.theme_color};
}
.header-body .logo a{
    color: {$config.theme_color};
}
.navbar-form .btn
{
    background:{$config.theme_color};
}
.header-top .navbar{
    border-top-color: {$config.theme_color};
}
.header-top .form-control:focus{
    border-color: {$config.theme_color};
}
.header-userbar .btn-login,
.btn-share
{
    background:{$config.theme_color};
    border-color: {$config.theme_color};
    color:#fff;
}
.header-userbar .checkbox a{
    color:{$config.theme_color};
}
.menu-parent > li > .accordion-group > a.active{
    background:{$config.theme_color};
} 
.fbform legend{
    background:{$config.theme_color};
}
.side-menu a:hover ,
.side-menu > li a.active{
    color: {$config.theme_color};
}
.posts-list-a .post-item:hover .post-icon{ background: {$config.theme_color};}

.swiper-pagination-bullet-active { background: {$config.theme_color};}
.swiper-button-next, .swiper-button-prev{ color:{$config.theme_color};}
    </style>            
            
    </head>
    <body class="no-js" itemscope itemtype="http://schema.org/WebPage">
    
    <script type="text/javascript">
    	document.body.className = document.body.className.replace('no-js','js');
    </script>   

    <div class="container{if $config.full_width}-fluid{/if}">
    
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
                    {$logo}
				</div>
				
				<div class="collapse navbar-collapse" id="header-top-navbar-collapse">
				    <div class="header-search">
    					<form class="navbar-form" action="{$config.base_url}/search.html" role="search">
    						<div class="form-group">
                                <div class="input-group">
                                  <input type="text" class="form-control input-sm" name="query" value="{$smarty.get.query|default:''}" placeholder="{tr('search_ph')}">
                                  <span class="input-group-btn">
                                    <button class="btn btn-sm btn-default" type="submit"><i class="fa fa-search"></i></button>
                                  </span>
                                </div>                          
    						</div>
    					</form>
                    </div>
                    
                    <div class="header-userbar">   
                        <div class="userbar-form hidden-sm">
                        {if $page.login_form}
                            <form method="post" class="form-inline" action="{$config.base_url}/login.php?return={$return_url}">
                              <div class="checkbox">
                                <label>
                                  <input name="remember" value="true" type="checkbox">&nbsp;{tr('remember_me')}
                                </label>
                                <a href="{$config.base_url}/recover.html">{tr('forgot_password')} ?</a>
                              </div>
                              
                              <div class="form-group">
                                <label class="sr-only" for="email">{tr('email')}</label>
                                <input type="text" name="email" class="form-control input-sm" id="email" placeholder="{tr('email')}">
                              </div>
                              
                              <div class="form-group">
                                <label class="sr-only" for="password">{tr('password')}</label>
                                <div class="input-group">
                                  <input type="password" name="password" class="form-control input-sm" id="password" placeholder="{tr('password')}">
                                  <span class="input-group-btn">
                                    <button class="btn btn-sm btn-default btn-login" type="submit">
                                        <i class="fa fa-sign-in"></i>
                                    </button>
                                  </span>
                                </div>                                
                              </div>
                              <input type="hidden" value="login" name="form_name" >
                              <input type="hidden" value="{$page.login_csrf_str}" name="login">  
                            </form> 
                        {/if}                          
                        </div>                              
                        <div class="userbar-btns">                        
                          {if $user.isLoggedIn}              
                                {if $user.isManager}
                                    <a class="btn btn-sm btn-default" href="{$config.base_url}/panel.html" role="button"><i class="fa fa-cogs"></i> {tr('panel')}</a>                                                      
                                {/if}                                 
                                {if ($config.user_posting && $user.isLoggedIn) || $user.isManager}
                                  <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown">
                                      <i class="fa fa-plus"></i> {tr('add_post')}
                                      <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a href="{$config.base_url}/post.php?via=upload"><i class="fa fa-upload"></i> {tr('post_upload')}</a></li>
                                        <li><a href="{$config.base_url}/post.php?via=write"><i class="fa fa-pencil"></i> {tr('post_write')}</a></li>
                                        <li><a href="{$config.base_url}/post.php?via=link"><i class="fa fa-link"></i> {tr('post_link')}</a></li>
                                    </ul>
                                  </div>  
                                {/if} 
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown">
                                      {$user.displayName}
                                      <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu account-dropdown">
                                        <li><a href="{$user.profileURLs.profile}"><i class="fa fa-user"></i> {tr('my_profile')}</a></li>
                                        <li><a href="{$config.base_url}/account.html"><i class="fa fa-cogs"></i> {tr('account_settings')}</a></li>
                                        <li><a href="{$config.base_url}/logout.html?return={$return_url}" ><i class="fa fa-sign-out"></i> {tr('logout')}</a></li>
                                    </ul>
                                </div>                                                                                                               
                          {else}
                            {if count($EnabledLoginProviders) > 0}
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-login dropdown-toggle" data-toggle="dropdown">
                                      <i class="fa fa-user"></i>
                                      <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu social-logins">
                                        {foreach $EnabledLoginProviders as $provider}
                                        <li><a class="{$provider|strtolower}" href="{$config.base_url}/auth.html?provider={$provider}&return={$return_url}"><i class="fa fa-{$provider|strtolower}"></i></a></li>
                                        {/foreach}
                                    </ul>
                                </div>  
                            {/if}                        
                            <a class="btn btn-sm btn-default visible-sm-inline" href="{$config.base_url}/login.html?return={$return_url}" role="button"><i class="fa fa-sign-in"></i> {tr('login')}</a>                            
                            {if $config.registration}
                                <a class="btn btn-sm btn-default" href="{$config.base_url}/register.html?return={$return_url}" role="button"><i class="fa fa-users"></i> {tr('register')}</a>                                                      
                            {/if}  
                          {/if}  
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
                      <a class="navbar-brand visible-xs" href="{$config.site_url}">{$config.site_name}</a>
                    </div>

                    <div class="collapse navbar-collapse" id="header-navbar-collapse">
                      <ul class="nav navbar-nav nav-main deep-active">
                        {navbar_render data=$Navbars.header}
                      </ul>
                      
                      <ul class="nav navbar-nav nav-social">
                        {if empty($config.fb_page_uname) == false}
                          <li class="n-facebook" rel="tooltip" title="" data-original-title="{tr('facebook')}" data-placement="bottom">
                              <a href="https://www.facebook.com/{$config.fb_page_uname}"><i class="fa fa-facebook"></i> </a>
                          </li>                        
                        {/if}
                        {if empty($config.twitter_uname) == false}
                             <li class="n-twit" rel="tooltip" title="" data-original-title="{tr('twitter')}" data-placement="bottom">
                                <a href="https://www.twitter.com/{$config.twitter_uname}"><i class="fa fa-twitter"></i></a>
                             </li>
                        {/if}
                        {if empty($config.youtube_uname) == false}
                             <li class="n-tube" rel="tooltip" title="" data-original-title="{tr('youtube')}" data-placement="bottom">
                                <a href="https://www.youtube.com/{$config.youtube_uname}"><i class="fa fa-youtube"></i></a>
                             </li>
                        {/if}                       
                        {if empty($config.instagram_uname) == false}
                             <li class="n-instagram" rel="tooltip" title="" data-original-title="{tr('instagram')}" data-placement="bottom">
                                <a href="https://www.instagram.com/{$config.instagram_uname}"><i class="fa fa-instagram"></i></a>
                             </li>
                        {/if}                       
                        {if empty($config.gplus_id) == false}
                          <li class="n-google" rel="tooltip" title="" data-original-title="{tr('Google')}" data-placement="bottom">
                              <a href="https://plus.google.com/{$config.gplus_id}"><i class="fa fa-google-plus"></i> </a>
                          </li>
                        {/if}

                      </ul>
                    </div>
                  </div>
                </nav>            
            </div>
            {if (boolean) $config.subscription_box == true}
                <div class="row">
                    <div class="col-md-12">
                        <div class="socialbar hidden-xs hidden-sm">
                            <i class="fa fa-rss"></i> {tr('subscription_box_1')}         
                            <form class="form-inline" onsubmit="window.open('http://feedburner.google.com/fb/a/mailverify?uri={$config.feedburner_uri}', 'popupwindow', 'scrollbars=yes,width=550,height=520');return true" target="popupwindow" method="post" action="http://feedburner.google.com/fb/a/mailverify">
                                <div class="input-group">
                                    <input type="text" class="form-control input-sm" name="email" placeholder="{tr('your_email_here')}">
                                    <input type="hidden" name="uri" value="{$config.feedburner_uri}" />
                                    <input type="hidden" value="en_US" name="loc" />      
                                    <span class="input-group-btn">
                                        <button class="btn btn-sm btn-default" type="submit"><i class="fa fa-check"></i> {tr('subscribe')}</button>
                                    </span>
                                </div>
                            </form> 
                            &nbsp;{tr('subscription_box_2')}
                        </div> 
                        <div class="socialbar visible-xs visible-sm">
                            <form onsubmit="window.open('http://feedburner.google.com/fb/a/mailverify?uri={$config.feedburner_uri}', 'popupwindow', 'scrollbars=yes,width=550,height=520');return true" target="popupwindow" method="post" action="http://feedburner.google.com/fb/a/mailverify">
                                <div class="input-group">
                                    <input type="text" class="form-control input-sm" name="email" placeholder="{tr('subscription_sm_ph')}">
                                    <input type="hidden" name="uri" value="{$config.feedburner_uri}" />
                                    <input type="hidden" value="en_US" name="loc" />      
                                    <span class="input-group-btn">
                                        <button class="btn btn-sm btn-default" type="submit">{tr('subscribe')}</button>
                                    </span>
                                </div>
                            </form>
                        </div>          
                    </div>
                </div>
            {/if}            
        </header>

        {foreach $tpl_files as $file}
            {include $file} 
        {/foreach}

        <footer id="footer">
            <div class="footer">
                <div class="row">
                    <div class="col-md-12">
                        <div>
                            <ul>
                                {if empty($config.fb_page_uname) == false}
                                    <li><a href="https://www.facebook.com/{$config.fb_page_uname}"><span class="fa-stack"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-facebook fa-stack-1x fa-inverse"></i></span></a></li>
                                {/if}
                                {if empty($config.twitter_uname) == false}
                                     <li><a href="https://www.twitter.com/{$config.twitter_uname}"><span class="fa-stack"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-twitter fa-stack-1x fa-inverse"></i></span></a></li>
                                {/if}
                                {if empty($config.youtube_uname) == false}
                                     <li><a href="https://www.youtube.com/{$config.youtube_uname}"><span class="fa-stack"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-youtube fa-stack-1x fa-inverse"></i></span></a></li>
                                {/if} 
                                    <li><a href="{$config.base_url}/rss.xml"><span class="fa-stack"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-rss fa-stack-1x fa-inverse"></i></span></a></li>                           
                            </ul>
                        </div>
                        <div><small>{$config.site_name} &copy; {$smarty.now|date_format:"%Y"} · {$config.copyrights} · #mediat#</small></div>
                        <div>
                            <ul>{navbar_render data=$Navbars.footer}</ul>                      
                        </div>
                    </div>
                </div>
            </div>     
        </footer> 
        <a id="back-to-top" href="#" class="back-to-top">
            <span class="fa fa-chevron-up"></span>
        </a>  
    </div>
    
    {if $smarty.const.DEV_MODE == true}
        <script src="{$config.base_url}/assets/js/jquery-2.1.1.min.js"></script>
        <script src="{$config.base_url}/assets/js/bootstrap.min.js"></script>
    {else}
        <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script type="text/javascript">
        if (typeof jQuery == 'undefined') {
            document.write(unescape("%3Cscript src='{$config.base_url}/assets/js/jquery-2.1.1.min.js' type='text/javascript'%3E%3C/script%3E"));
        }
        </script>        
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    {/if}
    
    <script src="{$config.base_url}/assets/js/jquery.wookmark.min.js"></script>
    <script src="{$config.base_url}/assets/js/imagesloaded.min.js"></script>
    
    <script src="{$config.base_url}/assets/js/timeago/jquery.timeago.js"></script>
    <script src="{$config.base_url}/assets/js/timeago/locales/jquery.timeago.{$config.lang}.js"></script>
    
    <script src="{$config.base_url}/assets/js/nprogress.js"></script>
    <script src="{$config.base_url}/assets/js/front.js"></script>

    {foreach $html.footer as $html_block}{$html_block}{/foreach}        
         
    </body>  
</html>