<div class="row">
  <div class="col-md-12">
    <div class="boxed">
        {include "panel-nav.tpl"}
        <div class="row">
            <div class="col-md-6">
                <p>{tr('webfairy_news')}<p>
                <iframe src="{Webfairy::NEWS_SOURCE}&lang={$config.lang}" style="border: none;height: 120px;width: 100%;overflow-y: scroll;"></iframe>
            </div>
            <div class="col-md-6">
                <p>{tr('supported_providers')} : <a href="#"><strong onclick="BootstrapDialog.show({ title: '{tr('supported_providers')}',message: '<ol>{foreach $info.providers as $provider}<li>{$provider}</li>{/foreach}</ol>' });">{$info.providers|count}+</strong></a></p>   
                <p>{tr('current_version')} : <strong>{$info.version}</strong></p>   
                <p>{tr('phpversion')} : <strong>{$info.php}</strong></p>             
                <p><abbr title="Consistent Interface For Accessing Databases">{tr('dbdriver')}</abbr> : <strong>{$info.db}</strong></p>             
            </div>
        </div>

        <p>
            <i class="fa fa-refresh"></i> {tr('site_cron_job')}
            <pre dir="ltr">wget -O - {$config.site_url}/cron.php > /dev/null 2>&1</pre>
        </p>           

        <p>
            <i class="fa fa-link"></i> {tr('xml_sitemap')}
            <pre dir="ltr">{$config.site_url}/sitemap.xml</pre>
        </p>           

        {''|implode:$messages} 
    </div>  
  </div>
</div>
