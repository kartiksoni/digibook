$( document ).ready(function() {
    $(".btn-changepassword-modal").click(function(){
        $('#change-password-model').modal('show');
    });
    
    $("#change-password-form").on("submit", function(event){
        event.preventDefault();
        var data = $(this).serialize();
        
        $.ajax({
            type: "POST",
            url: 'ajax_second.php',
            data: {'action':'changepassword', 'data': data},
            dataType: "json",
            beforeSend: function() {
                $('#btn-change-password').html('Wait.. <i class="fa fa-spin fa-refresh"></i>');
                $('#btn-change-password').prop('disabled', true);
            },
            success: function (data) {
                if(data.status == true){
                    showSuccessToast(data.message);
                    $('#change-password-form')[0].reset();
                    $('#change-password-model').modal('hide');
                }else{
                    showDangerToast(data.message);
                }
                $('#btn-change-password').html('Reset');
                $('#btn-change-password').prop('disabled', false);
            },
            error: function () {
                showDangerToast('Somthing Want Wrong!');

                $('#btn-change-password').html('Reset');
                $('#btn-change-password').prop('disabled', false);
            }
        });

    });
});