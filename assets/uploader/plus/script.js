$(function () {
    'use strict';

    $('#media-tab').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget);
      var modal = $(this)
        $.ajax({
            url: $('#fileupload').fileupload('option', 'url'),
            dataType: 'json',
            context: $('#fileupload')[0]
        }).always(function () {
            //$(this).find('.fileupload-process').addClass('hidden');
        }).done(function (result) {
            var filesContainer = $(this).fileupload('option', 'filesContainer');
            filesContainer.empty();
            
            $(this).fileupload('option', 'done').call(this, $.Event('done'), {result: result});

            filesContainer.off('click','.set-featured-image').on('click','.set-featured-image',function(){
                var parent = button.parent();
                parent.find('input').val($(this).data('id'));
                parent.find(".featured-image").html(
                    $('<img />',{src: $(this).data('thumb') , class : 'img-thumbnail'})
                )
                parent.removeClass('input-new');
                modal.modal('hide')
            });
            filesContainer.off('click','.insert-ckeditor-html').on('click','.insert-ckeditor-html',function(){
                var file_id = $(this).data('id');
                var ckeditorid =  $(this).data('ckeditorid');
                $.ajax({
                    url: site_url + '/ajax.html?c=file_embed',
                    data: {id:file_id},
                }).done(function (result) {
                	var editor = CKEDITOR.instances[ckeditorid];
                	if ( editor.mode == 'wysiwyg' ){
                        var fragment = editor.getSelection().getRanges()[0].extractContents()
                        var container = CKEDITOR.dom.element.createFromHtml(result, editor.document)
                        fragment.appendTo(container);
                        editor.insertElement(container);    
                	}else{
                	   alert( 'You must be in WYSIWYG mode!' ); 
                	}            
                });
 
                modal.modal('hide');
                
            });      
        });      
    }) 

    $('.featured-image-input').on('click','.cancel',function(){
        var parent = $(this).parent();
        parent.find('input').val('');
        parent.find(".featured-image").empty();
        parent.addClass('input-new');
    })
});