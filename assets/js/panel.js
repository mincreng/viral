/*
 * @author Webfairy MediaT CMS - www.Webfairy.net
 */

(function($) {
    
  $.fn.dependsOn = function(element, value) {
    var elements = this;
    
    $(element).change(function(){
      var $this = $(this);
      var showEm = false;
      if ( $this.is('input[type="checkbox"]') ) {
        showEm = $this.is(':checked');
      } else if ( $this.is('select') ) {
        var fieldValue = $this.find('option:selected').val();
        var showEm = false;
        if ( !value ) {
          showEm = fieldValue && $.trim(fieldValue) != '';
        } else if (typeof(value) === 'string') {
          showEm = value == fieldValue;
        } else if ($.isArray(value)) {
          showEm = ($.inArray(fieldValue, value) !== -1);
        }
      }
      elements.closest('.form-group').toggle(showEm);
    });

    elements.each(function(){
      var $this = $(this);
      var selected = $(element).find('option:selected').val();
      
      $this.closest('.form-group').hide();
      if ($.isArray(value)) {
        if($.inArray(selected, value) !== -1){
            $this.closest('.form-group').show();
        }
      } 
           
    });
    
    
  };
})( jQuery );

function toggleChecked(selector,status) {
    $(selector).each( function() {
        $(this).prop("checked",status);
    })
}

ajaxDialog = function(title,url){
    BootstrapDialog.show(
        { 
            title: title,
            /*cssClass: 'modal-fullscreen',*/
            message: $('<div><p class="text-center"><i class="fa fa-spin fa-refresh"></i></p></div>').load(url) 
        }
    );
}


navbarsPanel = function(navbar_prefix){
    $('.dd').nestable();
    $('form.add-navbar').on('submit',function(e){
        var form = $(this),
            index = [],
            data = form.serialize();
        $('.dd').find('li').each(function(){index.push($(this).data('index'))});

       $.post(site_url + '/panel.html?case=ajax&c=navbars&navbar_prefix=' + navbar_prefix +'&o=insert&index=' + ( Math.max.apply(Math, index) + 1), 
            data, 
            function(data) {}
        ,'json')
       .done(function(data) {
        
            if(data.errors.length == 0){
                target = $('.dd > .dd-list');
                if(target.find('li').length == 0){
                    target.html(data.html);
                }else{
                    target.append(data.html);
                }  
                form[0].reset(); 
                             
            }else{
                var dialog = new BootstrapDialog({ message:  data.errors});
                    dialog.realize();
                    dialog.getModalHeader().hide();
                    dialog.getModalFooter().hide();
                    dialog.open();                    
            }
            $('button[role="iconpicker"]').iconpicker();
       })

        return false;
    });
    
    
    $('#save_navbars').on('click',function(){
        var data = $('.dd').nestable('serialize');
       $.post(site_url + '/panel.html?case=ajax&c=navbars&navbar_prefix=' + navbar_prefix +'&o=save', { 'data' : data},'json')
       .done(function(data) { 
            $( "#success-indicator" ).fadeIn(100).delay(1000).fadeOut();
        });  
    });
    
	$('.dd').on('mousedown', '.dd-handle a', function(e) {e.stopPropagation();}).on('click','.delete-navbar',function(){
        $(this).closest('li').fadeOut( "slow", function() {$(this).remove();});
        
        return false;     
    });
    
    $(document).on('click', 'input[name=noicon]',function(){
        $(this).parent().parent().prev().find('.btn').attr('disabled',$(this).prop('checked'));
    });
    
}

postsFetcher = function(){
    $('.scan-source').on('click',function(e){
        e.preventDefault();
         var $btn = $(this),
             source_id = $btn.data('id'),
             items_num = 25,
             scan_url = 'panel.html?case=ajax&c=sourcepreview&items_num=' + items_num +'&id=' + source_id;
         
        ajaxDialog($btn.html(),scan_url);         

    });
    
    $(document).on( 'click','button.fetch-item', function(e) {
        e.preventDefault();
         var $btn = $(this),
             source_id = $btn.data('id'),
             cat_id = $btn.parent().prev().val(),
             item_key = $btn.data('key'),
             fetch_url = 'panel.html?case=ajax&c=fetchitem';
             
        $btn.attr("disabled", true).prepend('<i class="fa fa-spin fa-refresh"></i> ');;
        $.post(fetch_url, {'id' : source_id ,'cat' : cat_id , 'key' : item_key}).done(function(data){
            $btn.closest('.row').removeClass('row').html(data);
        });
    });     
}