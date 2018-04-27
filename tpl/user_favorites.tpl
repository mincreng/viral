<div class="container">
  <div class="row">
    <div class="col-md-10 col-md-offset-1">
        {user_profile_header row=$row case='fav'}
  
  
        <div class="posts-header clearfix">
            <nav class="navbar navbar-default" role="navigation">
              <div class="container-fluid">
                <div class="navbar-header">
                  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#posts-filter">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                  </button>
                    <ol class="breadcrumb">
                      <li><a href="#"><i class="fa fa-home"></i> {$config.site_name}</a></li>
                      <li><a href="{$config.base_url}/users/index.html"><i class="fa fa-users"></i> {tr('users')}</a></li>
                      <li><a href="{$row.profileURLs.profile}"><i class="fa fa-user"></i> {$row.displayName}</a></li>
                      <li class="active">{tr('favorites')}</li>
                    </ol>      
                </div>
                {posts_filter}
              </div>
            </nav>
        </div>

        <noscript>
             {if count($posts) > 0}    
                <ul class="posts-list-b">
                    {posts_list posts=$posts style='b'} 
                </ul>
            {else} 
                <div class="col-md-12">
                    <p class="msg"><i class="fa fa-envelope"></i> {tr('no_posts')}</p>
                </div>                       
            {/if}         
            <br />      
            {$pagination}      
        </noscript>

        <ul id="bricks-container"></ul>    
        
        <p id="loaderCircle" class="text-center hide-if-no-js">
            <i class="fa fa-spin fa-refresh"></i>
        </p>       
  
  
    </div>
  </div>
</div>