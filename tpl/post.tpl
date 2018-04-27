<div class="row">
  <div class="col-md-12">
    <div class="boxed">
        {if $smarty.get.via == 'upload'}
            <h1><i class="fa fa-upload"></i> {tr('post_upload')}</h1>
            <form id="fileupload" class="HTML5Uploader" method="POST" action="#" enctype="multipart/form-data">
                <div class="drop-wrapper">
                    <div id="dropzone" class="dropzone">
                        <h1>{tr('drop_files')}</h1>
                        <p>{ftr('upload_info',array(implode(tr('comma'),array_map('tr',array_keys($upload.mime_types_pkgs))),Webfairy::bytesToSize($upload.max_file_size)))}</p>
                            <span id="browsebutton" class="fileinput-button button gray" href="">
                                {tr('browse')}..
                                <input type="file" id="fileinput" name="files[]" class="fileinput" multiple />
                            </span>
                    </div>
                </div>
        
                <!-- upload ui -->
                <div class="upload-wrapper">
                    <div class="info-wrapper" id="info-wrapper">
        
                        <div title="{tr('remaining_time')}" class="time-info" rel="tooltip">
                            <i class="fa fa-clock-o"></i>
                            <span>00:00:00</span>
                        </div>
        
                        <div title="{tr('uploading_speed')}" class="speed-info" rel="tooltip">
                            0 {tr('uploading_unit')}
                        </div>
        
                        <button id="start-button" class="button pinkish" type="submit">{tr('start_upload')}</button>

                          <div id="merge_all" class="checkbox hidden">
                            <label>
                              <input name="merge_all" type="checkbox"> {tr('merge_all')}
                            </label>
                          </div> 

                        <div style="clear:both;"></div>
                    </div>
        
                    <ul id="files" class="files">
        
                    </ul>
        
                   <div class="add-more fileinput-button hidden" id="add-more-button">
                       <i class="fa fa-plus"></i> {tr('add_more_files')} ...
                       <input type="file" name="files[]" class="" multiple />
                   </div>
                </div>
                
            </form>
    
            {literal}
                <script id="template-upload" type="text/x-handlebars-template">
                    {{#each files}}
                        <li class="file-item">
                            <div class="first">
                                <span class="top">
                                    <span class="filename">{{shortenName name}}</span>
                                    <a href="#" class="download-link" target="_blank">
                                        <i class="fa fa-download"></i>
                                    </a>
                                </span>
                                <span class="cancel-single"><i class="fa fa-trash-o"></i></span>
                                <span class="pause"><i class="fa fa-pause"></i></span>
                                {{#if error}}
                                <div class="error">
                                    <i class="fa fa-lock icon"></i>
                                    <span>{{error}}</span>
                                </div>
                                {{/if}}
                            </div>
                            <div class="second">
                                <span class="filesize">{{formatFileSize size}}</span>
                                <div class="progress-wrap">
                                    <div class="progress">
                                            <div class="bar" style="width:0%"></div>
                                    </div>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <div class="aside">
                                <div class="success-tick">
                                    <i class="fa fa-check"></i>
                                </div>
                            </div>
                            <input type="hidden" name="file_id[]"/>
                        </li>
                    {{/each}}
                </script>
            {/literal} 
        {elseif $smarty.get.via == 'link'}
            <h1><i class="fa fa-link"></i> {tr('post_link')}</h1>  
            {$Form}                 
        {elseif $smarty.get.via == 'write'}
            <h1><i class="fa fa-pencil"></i> {tr('post_write')}</h1>  
            {$Form}            
                 
        {/if}
        
    </div>
  </div>
</div>    