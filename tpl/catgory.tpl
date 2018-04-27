<div class="row">
      <div class="col-md-{$grid.main} {if $config.reverse_col_order}col-md-push-{$grid.sidebar}{/if}">

        <div class="posts-header clearfix">
            <nav class="navbar navbar-default" role="navigation">
              <div>
                <div class="navbar-header">
                  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#posts-filter">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                  </button>
                    <ol class="breadcrumb" itemprop="breadcrumb">
                      <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="{$config.site_url}" itemprop="url"><i class="fa fa-home"></i> <span itemprop="title">{$config.site_name}</span></a></li>
                      {if isset($row.parent_catgory) == true}
                        <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="{$row.parent_catgory.url}" itemprop="url"><span itemprop="title">{$row.parent_catgory.title}</span></a></li>
                      {/if}
                        <li class="active" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="{$row.url}" itemprop="url"><span itemprop="title">{$row.title}</span></a></li>
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
      <div class="col-md-{$grid.sidebar} {if $config.reverse_col_order}col-md-pull-{$grid.main}{/if}">
        {sidebar current_category=$row.id}
      </div>      
  </div>