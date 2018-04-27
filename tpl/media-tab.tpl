<div class="modal fade" id="media-tab" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-plus"></i> {tr('insert_media')}</h4>
      </div>
      <div class="modal-body">
      
        <ul class="nav nav-tabs media-tabs" role="tablist">
          <li class="active"><a href="#upload_files" role="tab" data-toggle="tab"><i class="fa fa-upload"></i> {tr('upload_files')}</a></li>
          <li><a href="#media_library" role="tab" data-toggle="tab"><i class="fa fa-th"></i> {tr('media_library')}</a></li>
        </ul>

        <div class="tab-content">
          <div class="tab-pane active" id="upload_files">
                <form id="fileupload" method="POST" enctype="multipart/form-data">
                    <div class="drop-wrapper">
                        <div id="dropzone" class="dropzone">
                            <h1>{tr('drop_files')}</h1>
                            <p>{ftr('upload_info',array(implode(tr('comma'),array_map('tr',$media_tab.mime_pkgs)),Webfairy::bytesToSize($upload.max_file_size)))}</p>
                            <span id="browsebutton" class="fileinput-button button pinkish" href="">
                                {tr('browse')}..
                                <input type="file" id="fileinput" name="files[]" class="fileinput" multiple />
                            </span>
                        </div>
                    </div>
                </form>  
          </div>
          <div class="tab-pane" id="media_library">       
            <div id="files" class="panel-group files"></div>          
          </div>
        </div>      
      </div>
    </div>
  </div>
</div>  

<script id="template-upload" type="text/x-tmpl">
[% for (var i=0, file; file=o.files[i]; i++) { %]
    <div class="panel template-upload fade">
     <div class="panel-heading">
        <h4 class="panel-title">
            <a data-toggle="collapse" data-parent="#files" href="#collapse-upload-[% var d=o.staticId(); print(d); %]">
                <span class="thumb">
                    <i class="fa fa-file"></i>
                </span>
              <span class="filename">[%=file.name%]</span>
            </a>
        </h4>
        <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
            <div class="progress-bar progress-bar-success" style="width:0%;"></div>
        </div>
    </div>
    <div id="collapse-upload-[% print(d); %]" class="panel-collapse collapse in">
      <div class="panel-body">
        <div class="thumb">
            <span class="preview"></span>
        </div>
        <div class="file-info">
            <p>
                <strong>{tr('file_name')}</strong>: [%=file.name%]
            </p>
            <p>
                <strong>{tr('file_size')}</strong>: [%=o.formatFileSize(file.size)%]
            </p>
            <p>
                <span class="label label-danger error"></span>
            </p>
            <p>
                [% if (!i && !o.options.autoUpload) { %]
                    <button class="btn btn-xs btn-primary start" disabled>
                        <i class="fa fa-upload"></i>
                        <span>Start</span>
                    </button>
                [% } %]
                [% if (!i) { %]
                    <button class="btn btn-xs btn-warning cancel">
                        <i class="fa fa-ban-circle"></i>
                        <span>Cancel</span>
                    </button>
                [% } %]
            </p>
        </div>
      </div>
    </div>
  </div>
[% } %]
</script>

<script id="template-download" type="text/x-tmpl">
[% for (var i=0, file; file=o.files[i]; i++) { %]
    <div class="panel template-download fade">
        <div class="panel-heading">
            <h4 class="panel-title">
            <a data-toggle="collapse" data-parent="#files" href="#collapse-download-[%=file.id%]-[% var d=o.staticId(); print(d); %]">
                <span class="thumb">
                    [% if (file.thumbnailUrl) { %]
                        <img src="[%=file.thumbnailUrl%]">
                    [% }else if(file.icon){ %]
                        <i class="fa fa-[%=file.icon%]"></i>
                    [% } %]
                </span>
              <span>&nbsp;[%=file.real_name%]</span>
            </a>
          </h4>
    </div>
    <div id="collapse-download-[%=file.id%]-[% print(d); %]" class="panel-collapse collapse">
      <div class="panel-body">
        [% if (file.error) { %]
            <div><span class="label label-danger">{tr('error')}</span> [%=file.error%]</div>
        [% } else { %]
            <div class="thumb">
                [% if (file.thumbnailUrl) { %]
                    <img class="img-responsive" src="[%=file.thumbnailUrl%]">
                [% }else if(file.icon){ %]
                    <i class="fa fa-[%=file.icon%]"></i>
                [% } %]                
            </div>
            <div class="file-info">
                <p>
                    <strong>{tr('file_name')}</strong>: [%=file.real_name%]
                </p>
                <p>
                    <strong>{tr('file_size')}</strong>: [%=o.formatFileSize(file.size)%]
                </p>
                 <p>
                    {foreach $media_tab.buttons as $button}
                        <button type="button" class="btn btn-default btn-xs {$button.class}"{foreach $button.attrs as $attr_key => $attr_val}{$attr_key}="{$attr_val}"{/foreach} data-id="[%=file.id%]" data-thumb="[%=file.thumbnailUrl%]">{$button.text}</button>&nbsp;
                    {/foreach}
                    [% if (file.deleteUrl) { %]
                        <button class="btn btn-xs btn-danger delete" data-type="[%=file.deleteType%]" data-url="[%=file.deleteUrl%]"[% if (file.deleteWithCredentials) { %] data-xhr-fields='{ "withCredentials":true }'[% } %]>
                            <i class="fa fa-trash"></i> {tr('delete')}
                        </button>
                    [% } %]          
                </p>
            </div>
        [% } %]      
      </div>
    </div>
  </div>
[% } %]
</script>