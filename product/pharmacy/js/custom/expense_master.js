$( document ).ready(function() {
    $('body').on('change', '#is_file', function () {
        if ($(this).is(':checked')){
            $('#file_upload_div').show();
        }
        else{
            $('#file_upload_div').hide();
        }
    });
});