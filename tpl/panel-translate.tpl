<div class="row">
  <div class="col-md-12">
    <div class="boxed">
        {include "panel-nav.tpl"}
        {if isset($Form) == true}
            {$Form}
        {else}
            <div class="row">
                <div class="col-md-3 col-xs-12">
                    <a class="btn btn-default btn-sm" href="panel.php?case=translate&action=create"><i class="fa fa-plus"></i> {tr('add')}</a> 
                </div>
                <div class="col-md-3 col-xs-12 col-md-offset-6">
                    <form method="get">
                        <input type="hidden" name="case" value="translate" />
                        <div class="input-group">
                          <input type="text" name="q" value="{$smarty.get.q|default:''}" class="form-control input-sm">
                          <span class="input-group-btn">
                            <button class="btn btn-default btn-sm" type="submit">{tr('search_btn')}</button>
                          </span>
                        </div>
                    </form>
                </div>
            </div>
            <div class="table-responsive"> 
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th class="col-md-1">{tr('key')}</th>
                            {foreach $availableLangs as $lang_key => $lang_title}
                                <th><span rel="tooltip" title="{$lang_title}">{$lang_key|strtoupper}</span></th>                                
                            {/foreach}                                
                            <th class="col-md-1 text-center">{tr('options')}</th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach $langs as $lang}
                            <tr>
                                <td><span dir="ltr" class="label label-primary">{$lang.var_key}</span></td>
                                {foreach $availableLangs as $lang_key => $lang_title}
                                    <td>
                                        {if empty($lang[$lang_key]) == false}
                                            <i class="fa fa-check text-success" rel="tooltip" title="{Webfairy::limit_words($lang[$lang_key],10)}"></i>
                                        {else}
                                            <i class="fa fa-times text-danger"></i>
                                        {/if}
                                    </td>                                
                                {/foreach}                                      
                                <td class="text-center"><a href="panel.php?case=translate&action=edit&id={$lang.id}&return={$return_url}"><i class="fa fa-edit"></i> {tr('edit')}</a></td>
                            </tr>
                        {foreachelse}
                           <tr>
                                <td colspan="3">{tr('panel_no_data')}</td>
                           </tr>
                        {/foreach}                        
                    </tbody>
                </table>
            </div>
            
            <div class="row">
                <div class="col-md-12">
                    {$pagination}
                </div>
            </div>            

        {/if}
    </div>
  </div>
</div>  
