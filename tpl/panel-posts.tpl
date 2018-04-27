<div class="row">
  <div class="col-md-12">
    <div class="boxed">
        {include "panel-nav.tpl"}
        {if isset($Form) == true}
            {$Form}
        {else}
            <form method="get">
                <div class="table-responsive"> 
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" onclick="toggleChecked('.checkbox',this.checked)" />
                                </th>
                                <th class="col-md-10">{tr('title')}</th>
                                <th class="col-md-2  text-center">{tr('options')}</th>
                            </tr>
                        </thead>
                        <tbody>
                            {foreach $posts as $post}
                                <tr{if $post.published == false} class="warning"{/if}>
                                    <td><input class="checkbox" type="checkbox" name="posts[]" value="{$post.id}" /></td>
                                    <td>{poststatus post=$post} ({$types[$post.type]}) <a href="{$post.url}"><strong>{$post.title}</strong></a> - <a href="{$post.cat.url}">{$post.cat.title}</a> - {$post.time}</td>
                                    <td class="text-center">
                                        <a href="edit.php?id[]={$post.id}&return={$return_url}"><i class="fa fa-edit"></i> {tr('edit')}</a>
                                        <a href="panel.php?case=posts&action=delete&posts[]={$post.id}"><i class="fa fa-trash-o"></i> {tr('delete')}</a>
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
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <input type="hidden" name="case" value="posts" />
                        <div class="input-group">
                            <select name="action" class="form-control input-sm">
                              <option value="delete">{tr('delete')}</option>
                              <option value="publish">{tr('publish')}</option>
                              <option value="unpublish">{tr('unpublish')}</option>
                            </select>
                          <span class="input-group-btn">
                            <button class="btn btn-default btn-sm" type="submit">{tr('submit')}</button>
                          </span>
                        </div>
                    </div>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        {$pagination}
                    </div>
                </div>
        
            </form>
        {/if}    
        </div>
    </div>
</div>