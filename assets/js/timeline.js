var page = 1,
    isLoading = false,
    lastRequestTimestamp = 0,
    fadeInDelay = 500,
    $timeline = $('#timeline'),
    uid = $timeline.data('uid'),   
    $window = $(window),
    $document = $(document);

$(document).ready(function(){
  $('form#share-form').ajaxForm({beforeSubmit: showRequest,success: showResponse,resetForm: true,dataType : 'json'});    
  $("#geocomplete").geocomplete({
    map: ".map_canvas",
    //location: "NYC",
    details: "form",
    types: ["geocode", "establishment"]
  }); 
  $('body').delegate('.post-type > a','click', function(e) { 
  	e.preventDefault();
    var positionArray = {};
    positionArray['status'] = 0;
    positionArray['photos'] = 80;
    positionArray['videos'] = 160;
    positionArray['location'] = 231;
    $('.video').addClass('hide');
    $('.image').addClass('hide');
    $('.place').addClass('hide');
    $('input.shareType').val($(this).attr('class'));
  	$('.arrow').css((is_rtl) ? "right" : "left", positionArray[$(this).attr('class')]);
    if($(this).attr('class') == 'videos') {
      $('.video').removeClass('hide');
      $('.image').addClass('hide');
      $('.place').addClass('hide');
    }
    if($(this).attr('class') == 'photos') {
      $('.video').addClass('hide');
      $('.image').removeClass('hide');
      $('.place').addClass('hide');
    }
    if($(this).attr('class') == 'location') {
      $('.video').addClass('hide');
      $('.image').addClass('hide');
      $('.place').removeClass('hide');
    }
  	return false;
  });
  
  loadData();
  
  $(window).scroll(onScroll);      
});
function showRequest(formData, jqForm, options) { 
    type = $('input.shareType').val();
    btn = $('#btn-share');
    btn.button('loading');  
} 
function showResponse(responseText, statusText, xhr, $form)  { 
    if(responseText.success){
        data = $(responseText.data);
        
        if($timeline.find('li:first').children().hasClass('direction-r')){
            data.children().removeClass('direction-r');
            data.children().addClass('direction-l');
        }
        rolesForm(data);
        $timeline.prepend(data);
        $timeline.show(); 
    }else{
        alert(responseText.message);
    }
  
  btn.button('reset');
} 

function loadData() {
    isLoading = true;
    $('#loaderCircle').show();
    $.ajax({
      url: site_url + "/ajax.html?c=load_timeline_posts&user_id=" + uid,
      dataType: 'json',
      data: {page: page},
      success: onLoadData
    });
};

var onScroll = function() {
    if (!isLoading) {
        if  ($(window).scrollTop() == $(document).height() - $(window).height()){
            var currentTime = new Date().getTime();
            if (lastRequestTimestamp < currentTime - 1000) {
              lastRequestTimestamp = currentTime;
              loadData();
            }             
        }               
    }    
}

var onLoadData = function(response) {
    isLoading = false;
    $('#loaderCircle').hide();
    page++;
    if (response.message == 'no_data') {
      $document.off('scroll', onScroll);
    }else{    
        $newposts = $(response.data);
        rolesForm($newposts);       
        
        $timeline.imagesLoaded(function() {  
          $timeline.append($newposts);
        });
        $timeline.show(); 
    }
};
var rolesForm = function(data){
    data.each(function( ) {
        var $self = $(this);
        $self.find('form.delete-post-form').ajaxForm(
            {
                beforeSubmit: function(){
                    if(!confirm('sure?')){
                        return false;
                    }
                },
                success: function(responseText, statusText, xhr, $form){
                    if(responseText.deleted){
                        $self.remove();
                    }
                },
                dataType : 'json'
            }
        );
    });
}