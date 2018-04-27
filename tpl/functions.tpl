{strip}
{function resized filename='' s='small' title='' attrs=[] responsive=true}<img {if $responsive}class="img-responsive"{/if} src="{if Webfairy::is_absolute_url($filename)}{$filename}{else}{$config.base_url}/resized/{$s}_{$filename}{/if}" alt="{$title}" title="{$title}" {foreach $attrs as $attr_key => $attr_value}{$attr_key}="{$attr_value}" {/foreach}/>{/function}
{function post_item post=[] style='a' url_attrs=[]}
    {if $style == 'a'}
        <li class="brick-item post-item post-id-{$post.id} post-type-{$post.type} no-js">
            <div class="post-body">
                <a href="{$post.url}">
                    <span class="post-title">{$post.title}</span>
                </a>
                {if empty($post.description) == false && empty($post.thumb) == true}
                    <div class="post-text">
                        <span>{$post.description}</span>
                    </div>
                {elseif empty($post.thumb) == true}
                    <div class="post-icon post-icon-{$post.type}">
                        <a href="{$post.url}">
                            <i class="fa fa-4x fa-{type2icon type=$post.type provider=$post.provider}"></i>
                        </a>
                    </div>
                {elseif empty($post.thumb) == false}
                    <div class="post-image {$post.provider}">
                        <a href="{$post.url}">
                            {resized filename=$post.thumb s='auto-height-small' title=$post.title}
                        </a>                        
                    </div>
                {/if}
                <div class="post-details">
                    <ul>
                        <li><i class="fa fa-clock-o"></i> {$post.time}</li>             
                        {* <li><i class="fa fa-th-list"></i> <a href="{$post.cat.url}">{$post.cat.title}</a></li> *}
                        <li><i class="fa fa-comment"></i> {$post.comments}</li>
                        <li><i class="fa fa-eye"></i> {$post.views}</li>
                        <li><i class="fa fa-thumbs-up"></i> {$post.vote.total}</li>     
                        {* <li><i class="fa fa-user"></i> {$post.author}</li> *}
                    </ul>
                </div>                
            </div>           
            <div class="post-footer">
                <ul>
                    <li><a class="fb_share" href="#" data-url="{$post.absolute_url}"><i class="fa fa-facebook-square"></i></a></li>
                    <li><a class="tw_share" href="#" data-url="{$post.absolute_url}" data-title="{$post.title}"><i class="fa fa-twitter"></i></a></li>
                    <li><a class="pint_share" href="#" {if empty($post.thumb) == false}data-media="{$config.site_url}/resized/medium_{$post.thumb}"{/if} data-url="{$post.absolute_url}" data-title="{$post.title}"><i class="fa fa-pinterest"></i></a></li>
                    <li><a class="gplus_share" href="#" data-url="{$post.absolute_url}"><i class="fa fa-google-plus"></i></a></li>
                    <li><a class="linkedin_share" href="#" data-url="{$post.absolute_url}"><i class="fa fa-linkedin"></i></a></li>
                    <li><a class="digg_share" href="#" data-url="{$post.absolute_url}" data-title="{$post.title}" ><i class="fa fa-digg"></i></a></li>
                </ul>
            </div>                            
        </li>  
    {elseif $style == 'b'}
        <li class="post-item post-id-{$post.id} post-type-{$post.type} no-js">
			 <ul class="vote vote-b hide-if-no-js" data-id="{$post.id}">
				<li class="up upvote {if isset($post.vote.upvoted) && $post.vote.upvoted == true}upvote-on{/if}"><i class="fa fa-sort-up"></i></li>
				<li class="total count" rel="tooltip" title="{tr('users_voted')}: {$post.vote.votes}">{$post.vote.total}</li>
                <li class="down downvote {if isset($post.vote.downvoted) && $post.vote.downvoted == true}downvote-on{/if}"><i class="fa fa-sort-down"></i></li>
			</ul>                           
             <div class="clearfix">
                {if empty($post.thumb) == false}
                    <a class="post-thumb overlay" href="{$post.url}">
                        {resized filename=$post.thumb s='thumb' attrs=['width'=>100,'height'=>80] title=$post.title}
                        <i class="fa fa-2x fa-{type2icon type=$post.type provider=$post.provider}"></i>
                    </a> 
                {/if} 
                <div class="post-body">
                    <h4 class="post-title"><a href="{$post.url}" {foreach $url_attrs as $attr_key => $attr_val}{$attr_key}="{$attr_val}" {/foreach}>{$post.title}</a></h4>
                    {if empty($post.description) == false}
                        <p class="post-desc">{$post.description}</p>
                    {/if}
                    <p class="post-info"><i class="fa fa-clock-o"></i> {$post.time} <i class="fa fa-th-list"></i> <a href="{$post.cat.url}">{$post.cat.title}</a> <i class="fa fa-comment"></i> {$post.comments} {tr('comment')} <i class="fa fa-eye"></i> {$post.views} {tr('view')} <i class="fa fa-user"></i> {$post.author}</p>
                </div>               
             </div>                          
        </li>
    {elseif $style == 'c'}
        <li class="post-item post-id-{$post.id} post-type-{$post.type} no-js">
            <div class="social_container">
            	 <ul class="socialcount socialcount-large">
            		<li class="facebook"><a class="fb_share" href="#" data-url="{$post.absolute_url}"><span class="fa fa-facebook"></span><span class="count">Share</span></a></li>
            		<li class="twitter"><a class="tw_share" href="#" data-url="{$post.absolute_url}" data-title="{$post.title}"><span class="fa fa-twitter"></span><span class="count">Tweet</span></a></li>
            		<li class="googleplus"><a class="gplus_share" href="#" data-url="{$post.absolute_url}"><span class="fa fa-google-plus"></span><span class="count">+1</span></a></li>
            		<li class="pinterest"><a class="pint_share" href="#" {if empty($post.thumb) == false}data-media="{$config.site_url}/resized/medium_{$post.thumb}"{/if} data-url="{$post.absolute_url}" data-title="{$post.title}"><span class="fa fa-pinterest"></span><span class="count">Pin It</span></a></li>						
            	</ul>
            </div>  
                  
            <div class="item-large">
              	<div class="single-title">
                    {if $post.author_row}
                		<a href="{$post.author_row.profileURLs.profile}">
                            <img src="{$post.author_row.photoURL}" class="img-circle user-avatar" alt="{$post.author_row.displayName}">
                        </a>
                    {/if}
                    <h2 class="item-title"><a href="{$post.url}">{$post.title}</a></h2>
            		<ul class="item-details">
                        {if $post.author_row}
            			     <li class="details hidden-xs hidden-sm">{tr('submitted_by')}: <a href="{$post.author_row.profileURLs.profile}">{$post.author_row.displayName}</a></li>
                        {/if}
            			<li class="details"><a href="{$post.cat.url}">{$post.cat.title}</a></li>
                        <li class="details">{$post.time}</li>
            			<li class="like-count"><i class="fa fa-thumbs-o-up"></i> {$post.vote.total}</li>
            			<li class="comment-count"><i class="fa fa-comments"></i> {$post.comments}</li>
            			<li class="view-count"><i class="fa fa-eye"></i> {$post.views} </li>
            		</ul>
            
                    <ul class="vote vote-a hide-if-no-js" data-id="{$post.id}">
                        <li class="total count" rel="tooltip" title="{tr('users_voted')}: {$post.vote.votes}">{$post.vote.total}</li>
                        <li class="up upvote {if isset($post.vote.upvoted) && $post.vote.upvoted == true}upvote-on{/if}"><i class="fa fa-thumbs-o-up"></i></li>
                        <li class="down downvote {if isset($post.vote.downvoted) && $post.vote.downvoted == true}downvote-on{/if}"><i class="fa fa-thumbs-o-up fa-rotate-180"></i></li>
                    </ul>             

            	</div>
                <div class="clearfix"></div>
                <div class="post-body">
                    {if empty($post.description) == false && empty($post.thumb) == true}
                        <div class="post-text">
                            {$post.description}
                        </div>
                    {elseif empty($post.thumb) == false}
                        <div class="post-image {$post.provider}">
                            <a href="{$post.url}">
                                {resized filename=$post.thumb s='auto-height-medium' title=$post.title}
                            </a>
                        </div>
                    {/if}                
                </div>
            </div>        
            <div class="clearfix"></div>
        </li>
    {/if}
{/function}

{function posts_list posts=[] ad_repeat=$config.posts_ad_repeat ad_every=4 style='a' url_attrs=[] ads=true}
    {foreach $posts as $post}
        {post_item post=$post style=$style url_attrs=$url_attrs}
        {if $config.posts_ad && $ads == true}
            {if $post@iteration is div by $ad_every}
                {ad_render assign="posts_ad" size=$config["posts_ad_$style"]|default:'200x200' repeat=$ad_repeat}
                {if isset($posts_ad) == true}
                    {if $style == 'a'}
                        <li class="brick-item adbox">{$posts_ad}</li>
                    {elseif $style == 'b'}
                        <li class="adbox">{$posts_ad}</li>
                    {elseif $style == 'c'}
                        <li>{$posts_ad}</li>
                    {/if}
                {/if}
            {/if}
        {/if}
    {/foreach} 
{/function}

{function timeline_posts posts=[] ajax=false}
    {foreach $posts as $post}
        <li {if $ajax}class="ajax"{/if}>
    		<div class="direction-{cycle values="r,l"}">
    			<div class="flag-wrapper">
    				<span class="flag"><a href="{$post.user_data.profileURLs.profile}">{$post.user_data.displayName}</a></span>
    				<span class="time-wrapper"><span class="time">{$post.createdon}</span></span>
    			</div>
                {if $post.roles}
                    <form class="delete-post-form" method="post" action="{$config.base_url}/ajax.php?c=delete_timeline_posts">
                        <input name="id" value="{$post.id}" type="hidden" />
                        <input name="wall_posts_{$post.id}" value="{$post.csrf}" type="hidden" />
                        <button type="submit" class="close" aria-hidden="true">&times;</button>
                    </form>
                {/if}
    			<div class="desc">{$post.content}</div>
    		</div>
    	</li>    
    {/foreach}
{/function}


{function sidebar current_category=0 current_page=''}
    <div id="sidebar" {if $config.fixed_sidebar}class="fixedbar"{/if}>
        <ul class="side-menu" id="#accordion1">
        	<li>
        		<a class="accordion-toggle {if $current_category > 0}active{/if}" data-toggle="collapse" href="#categories"><span class="fa-stack"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-cloud fa-stack-1x fa-inverse"></i></span> {if $config.public_stats} {$db_statistics.posts} {tr('post')} {else} {tr('categories')}{/if}</a>
        		<ul class="menu-parent collapse in" id="categories">
        			<li id="accordion2">
        				<div class="accordion-group">
                            {foreach $catgories as $catgory}
                                {if count($catgory.subcategories) > 0}
                                    <a class="{if in_array($current_category,array_keys($catgory.subcategories)) == true}active{/if}" data-parent="#accordion2" data-toggle="collapse" href="#group-collapse{$catgory.id}">{$catgory.title}</a>
                					<ul class="menu-child collapse {if in_array($current_category,array_keys($catgory.subcategories)) == true}in{/if} show-if-no-js" id="group-collapse{$catgory.id}">
                                        {foreach $catgory.subcategories as $subcategory}
                    						<li>
                    							<a class="{if $subcategory.id == $current_category}active{/if}" href="{$subcategory.url}">{$subcategory.title}</a>
                    						</li>
                                        {/foreach}
                					</ul>
                                {else} 
                                   <a class="{if $catgory.id == $current_category}active{/if}" href="{$catgory.url}">{$catgory.title}</a>
                                {/if}
                            {/foreach}
        				</div>
        			</li>
        		</ul>
        	</li>
        </ul>
        <ul class="side-menu">
        	<li>
        		<a {if $current_page == 'users'}class="active"{/if} href="{$config.base_url}/users/index.html">
                    <span class="fa-stack"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-users fa-stack-1x fa-inverse"></i></span> {if $config.public_stats}{$db_statistics.users} {tr('users')} {else} {tr('users')} {/if}
                </a>
        	</li>
            {if ($config.user_posting && $user.isLoggedIn) || $user.isManager}
                <li>
            		<a class="accordion-toggle" data-toggle="collapse" href="#add_post"><span class="fa-stack"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-plus fa-stack-1x fa-inverse"></i></span> {tr('add_post')}</a>
            		<ul class="menu-parent collapse show-if-no-js" id="add_post">
            			<li>
            				<div class="accordion-group">
                                <a href="{$config.base_url}/post.php?via=upload"><i class="fa fa-upload"></i> {tr('post_upload')}</a>
                                <a href="{$config.base_url}/post.php?via=write"><i class="fa fa-pencil"></i> {tr('post_write')}</a>
                                <a href="{$config.base_url}/post.php?via=link"><i class="fa fa-link"></i> {tr('post_link')}</a>
            				</div>
            			</li>
            		</ul>
            	</li> 
            {/if}              
        </ul>
        {if $user.isLoggedIn}
            <ul class="side-menu">
                <li>
            		<a href="{$config.base_url}/my_posts.html"><span class="fa-stack"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-briefcase fa-stack-1x fa-inverse"></i></span> {tr('my_posts')}</a>
            	</li>      
            </ul>   
        {/if}
    </div>   
{/function}

{function navbar_render data=[] depth=0}
    {foreach $data as $item}
        <li class="{if isset($item.children) == true}dropdown {if $depth > 0}dropdown-sub{/if}{/if} {if Webfairy::is_current_url($item.url)}active{/if}">
            {if isset($item.children) == true}
                    <a href="{url_format url=$item.url}" class="dropdown-toggle {if empty($item.title) == true}icon-only{/if}" data-toggle="dropdown">{$item.icon} {$item.title} {if $depth > 0}{else}<b class="caret"></b>{/if}</a>
                {else}
                    <a href="{url_format url=$item.url}" class="{if empty($item.title) == true}icon-only{/if}">{$item.icon} {$item.title}</a>
            {/if}
            {if isset($item.children) == true}
                <ul class="dropdown-menu {if $depth > 0}sub-menu{/if}">
                    {navbar_render data=$item.children depth=$depth+1}
                </ul>
            {/if}            
        </li>
    {/foreach}
{/function}

{function source_preview data=0}
    {if $data.result.quantity > 0}
        <ul id="source-preview-list" class="media-list">
          {foreach $data.result.items as $item}  
              {if $item.fresh == true}
                <li class="media">
                    {if empty($item.row.thumb_id) == false}
                        <a class="{if $config.rtl}pull-right{else}pull-left{/if}" href="{$item.row.link}" target="_blank">
                          <img class="media-object" src="{$item.row.thumb_id}">
                        </a>
                    {/if}
                    <div class="media-body">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapse-{$item@index}">
                        <h4 class="media-heading">{$item.row.title}</h4>
                      </a>
                                          
                    <div class="row">
                      <div class="col-sm-6">
                        <div class="input-group">
                          <select name="cat_id" class="form-control input-sm">
                            {foreach $catgories_tree as $cat_id => $cat_title}
                                <option {if $cat_id == $item.row.cat_id|default:0}selected="true"{/if} value="{$cat_id}">{$cat_title}</option>
                            {/foreach}
                          </select>
                          <span class="input-group-btn">
                            <button data-id="{$item.row.source_id}" data-key="{$item@index}" class="btn btn-sm btn-primary fetch-item" type="button">{tr('fetch')}</button>
                          </span>
                        </div>    
                      </div>
                    </div>                

                    </div>
                    <br class="clearfix"/>
                    <div id="collapse-{$item@index}" class="panel-collapse collapse">
                      <div class="well well-lg">
                      {$item.row.content}
                      </div>
                    </div>
                </li>              
              {elseif $item.fresh == false}
                <li class="media">
                    {if empty($item.row.thumb) == false}
                        <a class="{if $config.rtl}pull-right{else}pull-left{/if}">
                          {resized filename=$item.row.thumb s='thumb' responsive=false attrs=['class'=>'media-object']}
                        </a>
                    {/if}
                    <div class="media-body">
                      <a href="{$item.row.url}" target="_blank">
                        <h4 class="media-heading">{$item.row.title}</h4>
                      </a>
                    </div>
                </li>              
              {/if}
          {/foreach}
        </ul>
    {/if}
    <ul class="list-group">
      <li class="list-group-item">
        {tr('items')}: <span class="label label-info">{$data.result.quantity}</span>
      </li>
      <li class="list-group-item">
        {tr('started')}: <span class="label label-info">{$data.info.started}</span> <span class="label label-info">{$data.info.time}</span>
      </li>
      <li class="list-group-item">
        {tr('memory_usage')}: <span class="label label-info">{$data.info.memory_usage}</span>
      </li>

    </ul>    
{/function}

{function navbars_form_inputs row=[]}
  <input name="type" value="{$row.type|default:0}" type="hidden" />   
  {if $row.type == 1}    
      <div class="form-group">
        <label for="url-{$row.index}" class="col-sm-3 control-label">{tr('url')}</label>
        <div class="col-sm-9">
          <input dir="ltr" type="text" name="url" value="{$row.url|default:'http://'}" class="form-control input-sm" id="url-{$row.index}" placeholder="{tr('url')}">
        </div>
      </div>
  {elseif $row.type == 2} 
      <div class="form-group">
        <label for="page-{$row.index}" class="col-sm-3 control-label">{tr('page')}</label>
        <div class="col-sm-9">
          <select id="page-{$row.index}" name="page_id" class="form-control input-sm">
            {foreach $pages as $page_id => $page_title}
                <option {if $page_id == $row.page_id|default:''}selected="true"{/if} value="{$page_id}">{$page_title}</option>
            {/foreach}
          </select>
        </div>
      </div>  

  {elseif $row.type == 3} 
      <div class="form-group">
        <label for="cat_id-{$row.index}" class="col-sm-3 control-label">{tr('category')}</label>
        <div class="col-sm-9">
          <select id="cat_id-{$row.index}" name="cat_id" class="form-control input-sm">
            {foreach $catgories_tree as $cat_id => $cat_title}
                <option {if $cat_id == $row.cat_id|default:0}selected="true"{/if} value="{$cat_id}">{$cat_title}</option>
            {/foreach}
          </select>
        </div>
      </div>        
  {/if} 
      <div class="form-group">
        <label for="title-{$row.index}" class="col-sm-3 control-label">{tr('title')}</label>
        <div class="col-sm-9">
          <div class="input-group">
                <span class="input-group-btn">
                    <button {if $row.noicon == 'true'}disabled="disabled"{/if} name="icon" data-icon="{$row.icon}" class="btn btn-default btn-sm" data-arrow-prev-icon-class="fa fa-chevron-left" data-arrow-next-icon-class="fa fa-chevron-right" role="iconpicker" data-iconset="fontawesome" ></button>
                </span>
                <input type="text" name="title" value="{$row.title|default:''}" class="form-control input-sm" id="title-{$row.index}" placeholder="{tr('title')}">
           </div>
           <div class="checkbox">
            <label>
              <input {if $row.noicon == 'true'}checked="checked"{/if} name="noicon" type="checkbox"> {tr('no_icon')}
            </label>
           </div>                    
        </div>
      </div>  
{/function}

{function navbars_builder_item item=[]}
  <div class="panel panel-default dd-handle">
    <div class="panel-heading">
        <a class="panel-title" data-toggle="collapse" data-parent="#nestable" href="#dd-item-{$item.index}">
            <i class="fa fa-angle-down"></i> {$item.title|default:tr('untitled')}       
        </a>
        <div class="action-buttons">                      
			<a class="delete-navbar" href="#">
				<i class="fa fa-trash-o"></i>
			</a>
		</div>                      
    </div>
    <div id="dd-item-{$item.index}" class="dd-nodrag panel-collapse collapse">
      <div class="panel-body form-horizontal">
        {navbars_form_inputs row=$item}                      
      </div>
    </div>
  </div>        
{/function}

{function navbars_builder items=[]}
    <ol class="dd-list">
        {foreach $items as $item}
            <li class="dd-item" data-index="{$item.index}">
                {navbars_builder_item item=$item}
                {if isset($item.children) == true}
                    {navbars_builder items=$item.children}
                {/if}
            </li>                
        {/foreach} 
    </ol>
{/function}

{function posts_filter}
<div id="posts-filter" class="navbar-collapse collapse in {if $config.full_width == false}brfilter{/if}">
  <ul class="nav navbar-nav filters hide-if-no-js">
    <p class="navbar-text"><i class="fa fa-retweet"></i> {tr('show')}: </p>
    <li class="filter active"><a id="all" href="#">{tr('all')}</a></li>
    <li class="filter"><a id="last" href="#">{tr('last_added')}</a></li>
    <li class="filter"><a id="watch" href="#">{tr('now_playing')}</a></li>
  </ul>
  <ul class="nav navbar-nav posts-sorter hide-if-no-js">
    <li class="dropdown">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-sort"></i> {tr('sort_by')} <span class="sort-text">{tr('date_added')}</span> <b class="caret"></b></a>
      <ul class="dropdown-menu filters">
        <li class="sort active"><a id="date" href="#">{tr('date_added')}</a></li>
        <li class="sort"><a id="vote" href="#">{tr('most_liked')}</a></li>
        <li class="sort"><a id="views" href="#">{tr('most_popular')}</a></li>
        <li class="sort"><a id="comments" href="#">{tr('most_discussed')}</a></li>
      </ul>
    </li>
  </ul>
</div>
{/function}

{function user_profile_header row=[] case='wall'}
    <div class="profile">
        <div class="profile-headerbg">
            <div class="profile-cover">
                <img src="{$row.coverPhoto}" alt="{$row.displayName}"/>
            </div>
            <div class="cover-border"></div>
            <div class="profile-photo">
                <img class="img-responsive" alt="{$row.displayName}" src="{$row.photoURL}">
            </div>
            <h2 class="profile-name">{$row.displayName}</h2>
        </div>
        <nav class="navbar navbar-default" role="navigation">
            <div class="row">
                <div class="col-md-10 col-md-offset-2">
                    <div class="navbar-header">
                      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#profile-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                      </button>
                    </div>
                    <div class="collapse navbar-collapse" id="profile-collapse">
                      <ul class="nav navbar-nav">
                        <li {if $case == 'wall'}class="active"{/if}><a href="{$row.profileURLs.profile}">{tr('wall')}</a></li>
                        <li {if $case == 'posts'}class="active"{/if}><a href="{$row.profileURLs.posts}"><i class="fa fa-cloud-upload"></i> {tr('posts')} ({$row.posts})</a></li>
                        <li {if $case == 'fav'}class="active"{/if}><a href="{$row.profileURLs.favorites}"><i class="fa fa-thumbs-o-up"></i> {tr('favorites')} ({$row.votes.gave.up})</a></li>
                        {if $user.isLoggedIn && $row.id == $user.id}
                            <li><a href="{$config.base_url}/account.html"><i class="fa fa-cogs"></i> {tr('account_settings')}</a></li>
                        {/if}
                      </ul>
                    </div> 
                </div>
            </div>        
        </nav>        
    </div>
{/function}

{/strip}