<div class="row">
  <div class="col-md-12">
    <div class="boxed">
    {include "panel-nav.tpl"}
    {$action = (isset($smarty.get.action) == true) ? $smarty.get.action : null}
    {if $action == 'read'}
        <div class="doc_view">
        	<div class="doc_view_header">
        		<dl class="dl-horizontal">
        			<dt>{tr('sender')}</dt>
        				<dd><span>{$message.sender.email}</span></dd>
        			<dt>{tr('date')}</dt>
        				<dd>{$message.time}</dd>
        		</dl>
        	</div>
        	<div class="doc_view_content">{$message.content}</div>
        	<div class="doc_view_footer clearfix">
        		<div class="pull-right">
        			<a class="btn btn-sm btn-default" onclick="$('#message_replay').toggle();" href="javascript:void(0)"><i class="fa fa-mail-reply"></i> {tr('replay')}</a>
        		</div>
        		<div class="pull-left">
        			<span rel="tooltip" title="{$message.sender.agent}">{$message.sender.ip}</span>
        		</div>
        	</div>
        </div>
        
        <div id="message_replay" style="display:none">{$Form}</div>
    {elseif $action == 'delete'}
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
                            <th class="col-md-12">{tr('message')}</th>                        
                        </tr>
                    </thead>
                    <tbody>
                        {foreach $messages as $message}
                            <tr>
                                <td><input class="checkbox" type="checkbox" name="messages[]" value="{$message.id}" /></td>
                                <td>
                                    {$tag = ((boolean) $message.unread == true) ? 'strong' : 'span'}
                                    <{$tag}><a href="panel.php?case=messages&action=read&id={$message.id}">{$message.content|limit_words:5|strip}</a></{$tag}>
                                </td>
                            </tr>
                        {foreachelse}
                           <tr>
                                <td colspan="2">{tr('panel_no_data')}</td>
                           </tr>
                        {/foreach}
                        
                    </tbody>
                </table>
            </div>
            
            <div class="row">
                <div class="col-md-4">
                    <input type="hidden" name="case" value="messages" />
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