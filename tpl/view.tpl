<div class="posts-header clearfix">
    <nav class="navbar navbar-default" role="navigation">
      <div class="container-fluid">
        <div class="navbar-header">
            <ol class="breadcrumb" itemprop="breadcrumb">
              <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="{$config.site_url}" itemprop="url"><i class="fa fa-home"></i> <span itemprop="title">{$config.site_name}</span></a></li>
              {if isset($post.cat.parent_catgory) == true}
                <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="{$post.cat.parent_catgory.url}" itemprop="url"><span itemprop="title">{$post.cat.parent_catgory.title}</span></a></li>
              {/if}
                <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="{$post.cat.url}" itemprop="url"><span itemprop="title">{$post.cat.title}</span></a></li>              
                <li class="active" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="{$post.url}" itemprop="url"><span itemprop="title">{$post.title}</span></a></li>
            </ol>      
        </div>
      </div>
    </nav>
</div>

<div class="row">
      <div class="col-md-{$grid.main} {if $config.reverse_col_order}col-md-push-{$grid.sidebar}{/if}">
    
        {if $isMobile}
            {ad_render size='336x280'}
        {else}
            {ad_render size='970x90'}
        {/if} 
 
        <section class="post-item no-js" itemscope itemtype="http://schema.org/Blog">
            <article class="single-post" itemprop="blogPost">
                <div class="item-large">
                  	<div class="single-title">
                        {if $post.author_row}
                    		<a href="{$post.author_row.profileURLs.profile}">
                                <img src="{$post.author_row.photoURL}" class="img-circle user-avatar" alt="{$post.author_row.displayName}">
                            </a>
                        {/if}
                        <h1 class="item-title" itemprop="name headline">{$post.title}</h1>  
                		<ul class="item-details">
                            {if $post.author_row}
                			     <li class="details hidden-xs hidden-sm">{tr('submitted_by')}: <a href="{$post.author_row.profileURLs.profile}">{$post.author_row.displayName}</a></li>
                            {/if}
                			<li class="details"><a href="{$post.cat.url}">{$post.cat.title}</a></li>
                            <li class="details">{$post.time}</li>
                			<li class="like-count"><i class="fa fa-thumbs-o-up"></i> {$post.vote.total}</li>
                			<li class="comment-count"><i class="fa fa-comments"></i> {$post.comments}</li>
                			<li class="view-count"><i class="fa fa-eye"></i> {$post.views} </li>
                            
                            {if $post.manage}   
                                <li class="manage">  
                                    <div class="btn-group">
                                      <div class="btn-group">
                                        <button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown">
                                          <i class="fa fa-pencil"></i> {tr('edit')}
                                          <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu">
                                          <li><a href="{$config.base_url}/edit.php?id={$post.id}&return={$return_url}"><i class="fa fa-pencil"></i> {tr('post_details')}</a></li> 
                                          <li><a href="{$config.base_url}/edit.php?id={$post.id}&mode=content&return={$return_url}"><i class="fa fa-pencil"></i> {tr('post_content')}</a></li>
                                          {if $post.type == 9}  
                                            <li><a href="#" data-toggle="modal" data-target="#media-tab"><i class="fa fa-navicon"></i> {tr('post_files')}</a></li>
                                          {/if} 
                                        </ul>
                                      </div>
                                      <a href="{$config.base_url}/panel.php?case=posts&action=delete&posts[]={$post.id}" class="btn btn-xs btn-danger"><i class="fa fa-times"></i> {tr('delete')}</a>
                                    </div>  
                                </li>         
                            {/if}                      
                            
                		</ul>
                
                        <div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
                            <meta itemprop="ratingValue" content="{$post.vote.percentage}" />
                            <meta itemprop="ratingCount" content="{$post.vote.votes}" />                
                            <ul class="vote vote-a hide-if-no-js" data-id="{$post.id}">
                                <li class="total count" rel="tooltip" title="{tr('users_voted')}: {$post.vote.votes}">{$post.vote.total}</li>
                                <li class="up upvote {if isset($post.vote.upvoted) && $post.vote.upvoted == true}upvote-on{/if}"><i class="fa fa-thumbs-o-up"></i></li>
                                <li class="down downvote {if isset($post.vote.downvoted) && $post.vote.downvoted == true}downvote-on{/if}"><i class="fa fa-thumbs-o-up fa-rotate-180"></i></li>
                            </ul>             
                        </div>
                	</div>
                    <div class="clearfix"></div>
        
                    <div class="row">
                        <div class="{if $config.full_width}col-md-8 col-sm-12 col-md-offset-2 col-sm-offset-0{else}col-sm-12{/if}">
                            <div class="post-content">
                                <div itemprop="articleBody">
                                {$post.content}
                                {ad_render size='336x280'}
                                </div>
                            </div> 
                                           
                            {if $isMobile}
                                <div class="socialbar-inline">
                                    <div class="fb-like" data-href="{$post.absolute_url}" data-layout="standard" data-action="like" data-show-faces="false" data-share="true"></div>
                                </div>                      
                            {else}
                                <ul class="socialbar">
                                    <li>
                                        <div class="fb-like" data-href="{$post.absolute_url}" data-layout="box_count" data-action="like" data-show-faces="false" data-share="false"></div> 
                                    </li>
                                     <li>             
                                        <script src="https://apis.google.com/js/platform.js" async defer>
                                          { lang: '{$config.lang}'}
                                        </script>
                                        <div class="g-plusone" data-size="tall"></div>                                       
                                    </li>  
                                     <li>                    
                                        <a class="twitter-share-button"
                                           href="https://twitter.com/share"
                                          data-url="{$post.absolute_url}"
                                          {if empty($config.twitter_uname) == false}data-via="{$config.twitter_uname}"{/if}
                                          data-text="{$post.title}"
                                          data-lang="{$config.lang}"
                                          data-count="vertical">
                                        Tweet
                                        </a>
                                        <script>
                                        window.twttr=(function(d,s,id){ var js,fjs=d.getElementsByTagName(s)[0],t=window.twttr||{ };if(d.getElementById(id))return;js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);t._e=[];t.ready=function(f){ t._e.push(f);};return t;}(document,"script","twitter-wjs"));
                                        </script>                    
                                     </li>    
                                </ul> 
                            {/if}                    
        
                            {if (boolean) $config.comments_box == true}
                                <div id="post-comments">
                                    {if $config.comments_sys == 'facebook'}
                                        <div class="fb-comments" data-width="100%" data-href="{$post.absolute_url}" data-numposts="10" data-colorscheme="light"></div>
                                    {elseif $config.comments_sys == 'disqus'}
                                        <div id="disqus_thread"></div>
                                        <script type="text/javascript">
                                            var disqus_shortname = '{$config.disqus_shortname}';
                                            var disqus_identifier = '{$post.id}';
                                            var disqus_title = '{$post.title|escape}';
                                            var disqus_url = '{$post.absolute_url|escape}';
                                            (function() {
                                                var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
                                                dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
                                                (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
                                            })();
                                        </script>
                                        <noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
                                    {/if}
                                </div>
                            {/if}             
                                            
                        </div>
                    </div>        
        
                </div>
        
            </article> 
        </section> 
         
        {if count($post.related) > 0}
            <h2>{tr('related_posts')}</h2>
            <ul class="posts-list-b" itemscope itemtype="http://schema.org/WebPage">{posts_list posts=$post.related style='b' ads=false url_attrs=['itemprop' => 'relatedLink']}</ul>  
        {/if}  

      </div>
      <div class="col-md-{$grid.sidebar} {if $config.reverse_col_order}col-md-pull-{$grid.main}{/if}">
        {sidebar}
      </div>      
</div>