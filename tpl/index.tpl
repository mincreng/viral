<div class="row">
      <div class="col-md-{$grid.main} {if $config.reverse_col_order}col-md-push-{$grid.sidebar}{/if}">
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
        {sidebar}
      </div>      
  </div>