<div class="row">
  <div class="col-md-12">
    <div class="boxed">
        {include "panel-nav.tpl"}
        {if isset($Form) == true}
            {$Form}
        {else}
            <p>
                <a class="btn btn-default btn-xs" href="panel.php?case=pages&action=create"><i class="fa fa-plus"></i> {tr('add')}</a>
            </p>
            <form method="get">
                <div class="table-responsive">            
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" onclick="toggleChecked('.checkbox',this.checked)" />
                                </th>
                                <th class="col-md-10">{tr('title')}</th>
                                <th class="col-md-2 text-center">{tr('options')}</th>
                            </tr>
                        </thead>
                        <tbody>
                            {foreach $pages as $page}
                                <tr>
                                    <td><input class="checkbox" type="checkbox" name="pages[]" value="{$page.id}" /></td>
                                    <td> <strong>{$page.title}</strong> <span class="label label-success">{$page.prefix}</span></td>
                                     <td class="text-center">
                                        <a href="panel.php?case=pages&action=edit&id={$page.id}"><i class="fa fa-edit"></i> {tr('edit')}</a>
                                        <a href="panel.php?case=pages&action=delete&pages[]={$page.id}"><i class="fa fa-trash-o"></i> {tr('delete')}</a>
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
                        <input type="hidden" name="case" value="pages" />
                        <input type="hidden" name="action" value="delete" />
                        <button class="btn btn-danger">{tr('delete_selected')}</button>            
                    </div>
                    <div class="col-md-8">
                        
                    </div>
                </div>            
                
            </form>
        {/if}
    </div>
  </div>
</div>  
