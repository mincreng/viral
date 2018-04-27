<div class="row">
  <div class="col-md-12">
    <div class="boxed">
        {include "panel-nav.tpl"}
        
        {if isset($Form) == true}
            {$Form}
        {else}
            <p>
                <a class="btn btn-default btn-xs" href="panel.php?case=users&action=create"><i class="fa fa-plus"></i> {tr('add')}</a>
            </p>
            <form method="get">
                <div class="table-responsive">    
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th style="width: 25px;">
                                    <input type="checkbox" onclick="toggleChecked('.checkbox',this.checked)" />
                                </th>
                                <th class="col-md-10">{tr('username')}</th>
                                <th class="col-md-2 text-center">{tr('options')}</th>
                            </tr>
                        </thead>
                        <tbody>
                            {foreach $users as $user}
                                <tr>
                                    <td><input class="checkbox" type="checkbox" name="users[]" value="{$user.id}" /></td>
                                    <td>{if $user.manager}<span class="label label-success">{tr('manager')}</span>{/if} <strong>{$user.username}</strong></td>
                                     <td class="text-center">
                                        <a href="panel.php?case=users&action=edit&id={$user.id}"><i class="fa fa-edit"></i> {tr('edit')}</a>
                                        <a href="panel.php?case=users&action=delete&users[]={$user.id}"><i class="fa fa-trash-o"></i> {tr('delete')}</a>
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
                        <input type="hidden" name="case" value="users" />
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