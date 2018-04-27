<div class="row">
      <div class="col-md-{$grid.sidebar} {if $config.reverse_col_order}col-md-push-{$grid.main}{/if}">
        {sidebar current_page='users'}
      </div>
      <div class="col-md-{$grid.main} {if $config.reverse_col_order}col-md-pull-{$grid.sidebar}{/if}">
          {if count($rows) > 0}  
            <div class="row">
                {foreach $rows as $row}  
                    <div class="col-sm-4">
                        <div class="mini-vcard clearfix">
                            <div class="vcard-pic">
                                <a href="{$row.profileURLs.profile}">
                                    <img alt="{$row.displayName}" src="{$row.photoURL}" class="avatar" height="50" width="50">
                                </a>
                            </div>
                            <div class="vcard-name">
                                <a href="{$row.profileURLs.profile}">{$row.displayName}</a>  
                                <span class="vcard-date">{$row.created}</span>                                
                            </div>

                            <div class="vcard-info">
                                {if count($row.socialURLs) > 0}
                                    <ul class="vcard-social">
                                        {foreach $row.socialURLs as $key => $url}
                                            <li>
                                                <a href="{$url}" rel="nofollow" class="fa-stack">
                                                    <i class="fa fa-circle fa-stack-2x"></i>
                                                    <i class="fa fa-{$key|strtolower} fa-stack-1x fa-inverse"></i>                                     
                                                </a>
                                            </li>
                                        {/foreach}
                                    </ul>
                                {/if}
                                <div class="vcard-stats">
                                    <a href="{$row.profileURLs.posts}">
                                        <i class="fa fa-rss"></i> {$row.posts} {tr('entries')}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                {/foreach}
            </div>
            {$pagination}
         {/if}
      </div>
</div>