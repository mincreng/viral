<div class="row">
  <div class="col-md-12">
    <div class="boxed">
        {include "panel-nav.tpl"}
        
        {if isset($Form) == true}
            {$Form}
        {else}
            <p>
                <a class="btn btn-default btn-xs" href="panel.php?case=fetcher&action=create"><i class="fa fa-plus"></i> {tr('add')}</a>
            </p>        
            <form method="get">
                <div class="table-responsive">            
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" onclick="toggleChecked('.checkbox',this.checked)" />
                                </th>
                                <th class="col-md-9">{tr('title')}</th>
                                <th class="col-md-3 text-center">{tr('options')}</th>
                            </tr>
                        </thead>
                        <tbody>
                            {foreach $sources as $source}
                                <tr>
                                    <td><input class="checkbox" type="checkbox" name="sources[]" value="{$source.id}" /></td>
                                    <td><strong>{$source.title}</strong></td>
                                    <td class="text-center">
                                        <a data-id="{$source.id}" class="scan-source" href="#"><i class="fa fa-search"></i> {tr('scan_fetch')}</a>
                                        <a href="panel.php?case=fetcher&action=edit&id={$source.id}"><i class="fa fa-edit"></i> {tr('edit')}</a>
                                        <a href="panel.php?case=fetcher&action=delete&sources[]={$source.id}"><i class="fa fa-trash-o"></i> {tr('delete')}</a>
                                    </td>
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
                    <div class="col-md-4">
                        <input type="hidden" name="case" value="fetcher" />
                        <input type="hidden" name="action" value="delete" />
                        <button class="btn btn-danger">{tr('delete_selected')}</button>            
                    </div>
                    <div class="col-md-8">
                        {$pagination}
                    </div>
                </div>            
    
            </form>
        {/if}
        </div>
    </div>
</div>