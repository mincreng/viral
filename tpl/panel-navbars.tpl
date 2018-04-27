<div class="row">
  <div class="col-md-12">
    <div class="boxed">
        {include "panel-nav.tpl"}

        <div class="well well-sm">
            <form class="form-inline" method="get" role="form">
              <div class="form-group">
                <label for="navbar">{tr('select_to_edit')}</label>
                <input name="case" value="navbars" type="hidden" />
                <select id="navbar" class="form-control input-sm" name="prefix">
                    {foreach ['header','footer'] as $key}
                        <option value="{$key}" {if $navbar_prefix == $key}selected="true"{/if}>{tr($key)}</option>
                    {/foreach}
                </select>
              </div>
              <button type="submit" class="btn btn-sm btn-default">{tr('select')}</button>
            </form>
        </div>
        
        <div class="row">
          <div class="col-md-4">
            <div class="panel-group" id="types-accordion">
              {foreach [1 => ['t' => 'links'],2 => ['t' => 'internal_pages'],3 => ['t' => 'cats']] as $nav_key => $nav_value}  
                  <div class="panel panel-default">
                    <div class="panel-heading">
                      <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#types-accordion" href="#type-{$nav_key}">
                          <i class="fa fa-plus"></i> {tr($nav_value['t'])}
                        </a>
                      </h4>
                    </div>
                    <div id="type-{$nav_key}" class="panel-collapse collapse {if $nav_value@first}in{/if}">
                      <div class="panel-body">
                        <form class="add-navbar form-horizontal" role="form">
                            {navbars_form_inputs row=['index' => 0,'type' => $nav_key,'title' => '','noicon' => 'false','icon' => '']}
                            <p><button type="submit" class="btn btn-sm btn-default">{tr('add')}</button></p>
                        </form>
                      </div>
                    </div>
                  </div>
              {/foreach}
            </div>
          </div>   
          <div class="col-md-8">
            <div class="panel panel-default">
              <div class="panel-heading">
                <i class="fa fa-bars"></i> {tr('menu_structure')} (<strong>{tr($navbar_prefix)}</strong>)
              </div>
              <div class="panel-body">
                <p class="navbar-instructions bg-info {if count($navbar_data) == 0}{else}hidden{/if}">{tr('navbar_ins')}</p>
                <p class="drag-instructions bg-info {if count($navbar_data) == 0}hidden{else}{/if}">{tr('navbar_drag_ins')}</p>
                <form id="navbar_form" class="form-horizontal" role="form">
                    <div id="nestable" class="nestable_list dd">
                        {navbars_builder items=$navbar_data}
                    </div>
                </form>
              </div>
                <div class="panel-footer">
                    <button id="save_navbars" type="submit" class="btn btn-sm btn-default">{tr('save')} </button> <span id="success-indicator" class="text-success" style="display:none"><i class="fa fa-check"></i> {tr('saved')}</span>
                </div>

            </div> 
         
          </div>
        </div>
    </div>
  </div>
</div>  
