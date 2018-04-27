<div class="row">
  <div class="col-md-12">
    <div class="boxed">
        {include "panel-nav.tpl"}

        <button class="btn btn-default btn-sm" data-toggle="modal" data-target="#import"><i class="fa fa-upload"></i> {tr('import')}</button>
        
        <div class="modal fade" id="import" tabindex="-1" role="dialog" aria-labelledby="importLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-body">
                <form action="panel.html?case=langs" method="post" role="form" enctype="multipart/form-data">
                  <div class="form-group">
                    <label for="jsonfile">{tr('file')}</label>
                    <input name="jsonfile" type="file" id="jsonfile">
                    
                  </div>
                  <div class="form-group">
                    <label for="lang">{tr('langauge')}</label>
                    <select id="lang" name="lang" class="form-control">
                      {foreach $langs_arr as $lang_key => $lang_arr}  
                        <option value="{$lang_key}">{$lang_arr.title}</option>
                      {/foreach}
                    </select>
                  </div>
                  <div class="checkbox">
                    <label>
                      <input name="replace_all" type="checkbox"> {tr('import_lang_checkbox')}
                    </label>
                  </div>                  
                  <button type="submit" class="btn btn-primary">{tr('import')}</button>
                </form>
              </div>
            </div>
          </div>
        </div>

        <div class="table-responsive">    
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th class="col-md-8">{tr('language')}</th>
                        <th class="col-md-2 text-center">{tr('completion')}</th>
                        <th class="col-md-2 text-center">{tr('options')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach $langs_arr as $lang_key => $lang_arr}
                        <tr>
                            <td>{$lang_arr.title}</td>
                            <td>
                                {progress_bar percent=$lang_arr.percent}                                   
                             </td>
                             <td class="text-center">
                                <a href="panel.php?case=ajax&c=export_lang&lang={$lang_key}"><i class="fa fa-save"></i> {tr('export')}</a>

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

        </div>
    </div>
</div>