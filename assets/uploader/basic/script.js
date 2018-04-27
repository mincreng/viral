$(function () {
    'use strict';
    $('#fileupload').fileupload({
        url: site_url + '/ajax.html?c=fileupload'
    }).bind('fileuploadadd', function (e, data) {
        var filesLenth = $('ul#files > li').length;
        if(filesLenth > 0){
            $('#merge_all').removeClass('hidden');
        }
    }).bind('fileuploadstop', function (e, data) {
        $('#fileupload').submit();
    });
});