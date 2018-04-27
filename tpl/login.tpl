<div class="row">
  <div class="col-md-6 col-md-offset-3">
    <div class="boxed">
        <h1><i class="fa fa-user"></i> {tr('login')}</h1>
        
		<div class="omb_social">
    	    {foreach $EnabledLoginProviders as $provider}
                <li>
    		        <a href="{$config.base_url}/auth.html?provider={$provider}&return={$return_url}" class="btn btn-block omb_btn-{$provider|strtolower}">
    			        <i class="fa fa-{$provider|strtolower} visible-xs"></i>
    			        <span class="hidden-xs">{tr($provider)}</span>
    		        </a>
    	        </li>
            {/foreach}
		</div>

		<div class="omb_loginor">
			<hr class="omb_hror">
			<span class="omb_spanor">{tr('or')}</span>
		</div>
 
        {$msg}
        {$renderForm}
    </div>  
  </div>
</div>