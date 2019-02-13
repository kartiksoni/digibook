$( document ).ready(function() {


    var htmlsuccess = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-success alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
    var htmlerror = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
    var msglength = 0;

    getAllMessage();

    $('body').on('click', '.profile-list-item', function() {
        var $this = $(this);
        $("li").removeClass("active-user");
        $($this).addClass("active-user");
        $('.usr-notification').html(null);
        getAllMessage();
    });

    /*-------------------SEND MESSAGE TO USER START---------------*/
    $('body').on('click', '#btn-send-msg', function() {
        var message = $('#msg').val();
        message = message.trim();
        var file_name = $('#file_name').val();
        var to = $('.active-user').attr('data-id');
        var is_group = $('.active-user').attr('data-group');

        if((message !== '' || file_name !== '') && to !== ''){
            var is_file = (file_name != '') ? 1 : 0;
            sendMessage(to, message, file_name, is_group, is_file);
        }else{
            return false;
        }
    });

    $("#msg").keypress(function (evt) {
        if (evt.keyCode == 13 && !evt.shiftKey) {

            var message = $('#msg').val();
            message = message.trim();
            var file_name = $('#file_name').val();
            var to = $('.active-user').attr('data-id');
            var is_group = $('.active-user').attr('data-group');

            if((message !== '' || file_name !== '') && to !== ''){
                var is_file = (file_name != '') ? 1 : 0;
                sendMessage(to, message, file_name, is_group, is_file);
            }else{
                return false;
            }
        }
    });
    /*-------------------SEND MESSAGE TO USER END----------------*/

    /*------------UPLOAD ATTECHMENT START--------------------*/
    $('body').on('click', '#attechment-lable', function(event) {
        $('#attechment').val(null);
        $(".progress-bar").css("width", "0%");
    });
    $('body').on('change', '#attechment', function(event) {

        var tmp_success = htmlsuccess;
        var tmp_error = htmlerror;

        var file_data = $('#attechment').prop('files')[0];
        var form_data = new FormData();
        form_data.append('file', file_data);
        
        $.ajax({
            url: 'upload-attechment.php',
            dataType: 'json', // what to expect back from the PHP script
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            xhr: function(){
                //upload Progress
                var xhr = $.ajaxSettings.xhr();
                if (xhr.upload) {
                    xhr.upload.addEventListener('progress', function(event) {
                        var percent = 0;
                        var position = event.loaded || event.position;
                        var total = event.total;
                        if (event.lengthComputable) {
                            percent = Math.ceil(position / total * 100);
                        }
                        //update progressbar
                        $(".progress-bar").css("width", + percent +"%");
                        // $(progress_bar_id + " .status").text(percent +"%");
                    }, true);
                }
                return xhr;
            },
            beforeSend: function() {
                 $('.progress').show();
                 $('.profile-list').css('pointer-events', 'none');
                 $('#attechment-loader-icon').show();
                 $('#attechment-icon').hide();
            },
            success: function (data) {
                if(data.status == true){
                    var to = $('.active-user').attr('data-id');
                    var is_group = $('.active-user').attr('data-group');
                    var text = data.result;
                    
                    $('#remove-file').show();
                    $('.file-upload-name').html(text);
                    $('#file_name').val(text);
                    $('#msg').focus();
                }else{
                    showDangerToast(data.message);
                    // tmp_error = tmp_error.replace("##MSG##", data.message);
                    // $('#chat-error').html(tmp_error);
                }
                $('.progress').hide();
                $('.profile-list').css('pointer-events', 'auto');
                $('#attechment-loader-icon').hide();
                $('#attechment-icon').show();
            },
            error: function () {
                $('.progress').hide();
                showDangerToast( 'File upload fail! Try again.');
                // tmp_error = tmp_error.replace("##MSG##", 'File upload fail! Try again.');
                // $('#chat-error').html(tmp_error);

                $('#attechment-loader-icon').hide();
                $('#attechment-icon').show();
                return false;
            }
        });
    });
    /*------------UPLOAD ATTECHMENT END--------------------*/


    /*-----------REMOVE ATTECHMENT START-------------------*/
     $('#remove-file').on('click', function () {
        var $this = $(this);
        var file_name = $('#file_name').val();
        if(file_name !== ''){
            $.ajax({
                type: "POST",
                url: 'ajaxchat.php',
                data: {'action':'removeAttechment', 'name': file_name},
                dataType: "json",
                beforeSend: function() {
                    $($this).hide();
                    $('#attechment-loader-icon').show();
                    $('#attechment-icon').hide();
                },
                success: function (data) {
                    $('#attechment-loader-icon').hide();
                    $('#attechment-icon').show();

                    if(data.status == true){
                        $('#file_name').val(null);
                        $('.file-upload-name').html('');
                        $('#msg').focus();
                    }else{
                        $($this).show();
                        $('.file-upload-name').html('<small class="text-danger">Try Again!</small>');
                    }
                },
                error: function () {
                    $('#attechment-loader-icon').hide();
                    $('#attechment-icon').show();
                    $($this).show();
                    $('.file-upload-name').html('<small class="text-danger">Try Again!</small>');
                }
            });
        }else{
            $($this).hide();
            $('#msg').focus();
            $('#file_name').val(null);
            $('.file-upload-name').html('');
            $('#attechment-loader-icon').hide();
            $('#attechment-icon').show();
        }
    });
    /*-----------REMOVE ATTECHMENT START-------------------*/


    /*------------ADD NEW GROUP START-----------------*/
    $("#newgroup-form").on("submit", function(event){
        event.preventDefault();
        // var tmp_success = htmlsuccess;
        // var tmp_error = htmlerror;
        var data = $(this).serialize();
        var group_name = $('#newgroup-form').find('#group_name').val();

        $.ajax({
            type: "POST",
            url: 'ajaxchat.php',
            data: {'action':'addNewGroup', 'data': data},
            dataType: "json",
            beforeSend: function() {
              $('#btn-addgroup').html('Wait.. <i class="fa fa-spin fa-refresh"></i>');
              $('#btn-addgroup').prop('disabled', true);
            },
            success: function (data) {
              if(data.status == true){
                showSuccessToast(data.message);
                // tmp_success = tmp_success.replace("##MSG##", data.message);
                // $('#newgroup-errormsg').html(tmp_success);
                $('#newgroup-form')[0].reset();
                var appendHTML = '<li class="profile-list-item " data-id="'+data.result+'" data-group="1"><a href="javascript:void(0);"><span class="pro-pic"><img src="../user_profile/groupdefault.jpg" alt=""></span><div class="user"><p class="u-name">'+group_name+'<span class="badge badge-pill badge-success usr-notification" id="notification'+data.result+'"></span></p><p class="u-designation">Group</p></div></a></li>';
                $('.profile-list').append(appendHTML);

                setTimeout(function(){
                    $('#new-group-model').modal('toggle');
                    $('#newgroup-errormsg').empty();
                }, 1000);
              }else{
                showDangerToast(data.message);
                // tmp_error =  tmp_error.replace("##MSG##", data.message);
                // $('#newgroup-errormsg').html(tmp_error);
              }
              $('#btn-addgroup').html('Add');
              $('#btn-addgroup').prop('disabled', false);
            },
            error: function () {
              showDangerToast('Somthing Want Wrong! Try again.');
              // tmp_error =  tmp_error.replace("##MSG##", 'Somthing Want Wrong!');
              // $('#newgroup-errormsg').html(tmp_error);

              $('#btn-addgroup').html('Add');
              $('#btn-addgroup').prop('disabled', false);
            }
        });
    });
    /*------------ADD NEW GROUP END-------------------*/

    /*---------------CLEAR CHAT START--------------*/
    $('body').on('click', '#clearchat', function() {
        // var tmp_success = htmlsuccess;
        // var tmp_error = htmlerror;
        var res = confirm("Are you sure want to clear chat?");
        if(res){
            var to = $('.active-user').attr('data-id');
            var is_group = $('.active-user').attr('data-group');
            $.ajax({
                type: "POST",
                url: 'ajaxchat.php',
                data: {'to':to, 'is_group':is_group,'action':'clearChat'},
                dataType: "json",
                success: function (data) {
                    if(data.status == true){
                        getAllMessage();
                    }else{
                        showDangerToast(data.message);
                        // tmp_error = tmp_error.replace("##MSG##", data.message);
                        // $('#chat-error').html(tmp_error);
                        return false;
                    }
                },
                error: function () {
                    showDangerToast('Somthing Want Wrong! Try Again.');
                    // tmp_error = tmp_error.replace("##MSG##", 'Somthing Want Wrong! Try Again.');
                    // $('#chat-error').html(tmp_error);
                    return false;
                }
            });
        }else{
            return false;
        }
    });
    /*---------------CLEAR CHAT END----------------*/

    /*------------------------FUNCTION START-----------------------*/
    /*to = toUserID, msg = messageText, file_name = file name, is_group = if chat is group then pass 1 other wise 0, file = is send file then pass 1 other wise 0 */
    
    function sendMessage(to = null, msg = null, file_name = null, is_group = 0, file = 0){
        var tmp_success = htmlsuccess;
        var tmp_error = htmlerror;
        var from = $('#current_userid').val();
        $.ajax({
            type: "POST",
            url: 'ajaxchat.php',
            data: {'to':to, 'msg': msg, 'file_name':file_name, 'is_group':is_group,'file':file, 'action':'sendMessage'},
            dataType: "json",
            beforeSend: function() {
                $('#msg_send_loader').show();
                // $("#msg").prop( "disabled", true );
            },
            success: function (data) {
                $('#msg_send_loader').hide();
                $('#chat-error').empty();
                // $( "#msg" ).prop( "disabled", false );

                if(data.status == true){
                    $('#msg').val(null);
                    msglength++;

                    var file_name_org = (file == 1) ? '<i class="fa fa-paperclip icon-sm"></i> <a href="../attechment/'+from+'/'+file_name+'" target="_blank">'+file_name+'</a><br/>' : '';
                    var text = file_name_org+' '+msg;
                    var append = '<div class="mail-list"><div class="content"><p class="sender-name"><span class="pull-right">You</span><small class="pull-left text-muted">'+moment().format('MM-DD-YYYY hh:mm')+'</small></p><p class="message_text text-right">'+text+'</p></div></div>';
                    $('#chatdiv').append(append);
                    scrollDown();

                    // remove filealert
                    if(file == 1){
                        removeFileAfterSuccess();
                    }
                    $('#msg').focus();
                    return false;
                }else{
                    showDangerToast(data.message);
                    // tmp_error = tmp_error.replace("##MSG##", data.message);
                    // $('#chat-error').html(tmp_error);
                    return false;
                }
          },
          error: function () {
                showDangerToast('Somthing Want Wrong! Try Again.');
            // tmp_error = tmp_error.replace("##MSG##", 'Somthing Want Wrong! Try Again.');
            // $('#chat-error').html(tmp_error);
            $('#msg_send_loader').hide();
            // $("#msg").prop( "disabled", false );
            return false;
          }
        });
    }

    function removeFileAfterSuccess(){
        $('#remove-file').hide();
        $('#file_name').val(null);
        $('.file-upload-name').html('');
        $('#attechment-loader-icon').hide();
        $('#attechment-icon').show();
    }
    
    function getAllMessage(){
        var to = $('.active-user').attr('data-id');
        var is_group = $('.active-user').attr('data-group');
        if(typeof to != 'undefined' && to != ''){
            $.ajax({
                type: "POST",
                url: 'ajaxchat.php',
                data: {'to':to, 'is_group':is_group, 'action':'getAllMessage'},
                dataType: "json",
                beforeSend: function() {
                    $('#chat-loader').show();
                    $('#chatdiv').html(null);
                    $( "#msg" ).prop( "disabled", true );
                    $('.profile-list').css('pointer-events', 'none');
                },
                success: function (data) {
                    // set unread notification
                    if(typeof data.unread !== 'undefined'){
                        unreadNotification(data.unread);
                    }
                    $('.profile-list').css('pointer-events', 'auto');

                    if(data.status == true){
                        var current_userid = $('#current_userid').val();
                        var html = '';

                        $.each(data.result, function (key, value) {
                            var align = (value.fromid == current_userid) ? 'right' : 'left';
                            var textalign = (value.fromid == current_userid) ? 'left' : 'right';
                            var file_name = (value.file == 1) ? '<i class="fa fa-paperclip icon-sm"></i> <a href = "'+value.url+'" target="_blank">'+value.file_name+'</a><br/>' : '';
                            var text = file_name+' '+value.text;
                            var unm = (value.fromid == current_userid) ? 'You' : value.fromname;
                            html = html + '<div class="mail-list"><div class="content"><p class="sender-name"><span class="pull-'+align+'">'+unm+'</span><small class="pull-'+textalign+' text-muted">'+value.msgdate+'</small></p><p class="message_text text-'+align+'">'+text+'</p></div></div>';
                        });
                        
                        msglength = (data.result.length != '') ? data.result.length : 0;
                        
                        setTimeout(function(){
                            $('#chatdiv').html(html);
                            scrollDown();
                            $('#chat-loader').hide();
                            $( "#msg" ).prop( "disabled", false );
                        }, 500);
                    }else{
                        $('#chatdiv').empty();
                        $('#chat-loader').hide();
                        $( "#msg" ).prop( "disabled", false );
                        msglength = 0;
                        return false;
                    }
              },
              error: function () {
                $('#chatdiv').empty();
                $('#chat-loader').hide();
                $( "#msg" ).prop( "disabled", false );
                $('.profile-list').css('pointer-events', 'auto');
                return false;
              }
            });
        }else{
            return false;
        }
    }

    setInterval(getLiveMessage,2000);
    function getLiveMessage(){
        var totalmsg_length = (msglength != '') ? msglength : 0;
        var to = $('.active-user').attr('data-id');
        var is_group = $('.active-user').attr('data-group');

        if(to != ''){
            $.ajax({
                type: "POST",
                url: 'ajaxchat.php',
                data: {'to':to, 'length': totalmsg_length, 'is_group':is_group, 'action':'getLiveMessage'},
                dataType: "json",
                success: function (data) {
                    // set unread notification
                    if(typeof data.unread !== 'undefined'){
                        unreadNotification(data.unread);
                    }

                    if(data.status == true){
                        
                        $.each(data.result, function (key, value) {
                            var file_name = (value.file == 1) ? '<i class="fa fa-paperclip icon-sm"></i> <a href = "'+value.url+'" target="_blank">'+value.file_name+'</a><br/>' : '';
                            var text = file_name+' '+value.text;
                            var html = '<div class="mail-list"><div class="content"><p class="sender-name"><span class="pull-left">'+value.fromname+'</span><small class="pull-right text-muted">'+value.msgdate+'</small></p><p class="message_text">'+text+'</p></div></div>';
                            $('#chatdiv').append(html);
                        });
                        var datamsglength = (data.result.length != '') ? data.result.length : 0;
                        msglength = (msglength + datamsglength);
                        scrollDown();
                    }else{
                        return false;
                    }
                  },
              error: function () {
                return false;
              }
            });
        }else{
            return false;
        }
    }

    function scrollDown(){
        $('#chatdiv').animate({scrollTop: $('#chatdiv')[0].scrollHeight}, 0);
    }

    function unreadNotification(arr = []){
        if(typeof arr != 'undefined' && arr != '' && arr.length > 0){
            $.each(arr, function (key, value) {
                if(typeof value.send_by !== 'undefined' && typeof value.count !== 'undefined' && value.count > 0){
                    if(typeof value.groups !== 'undefined' && value.groups == 0){
                        $('#notificationuser'+value.send_by).html(value.count);
                    }else{
                        $('#notificationgroup'+value.send_by).html(value.count);
                    }
                    
                }
            });
        }
    }

    setInterval(isOnline,60000);
    function isOnline(){
        $.ajax({
            type: "POST",
            url: 'ajaxchat.php',
            data: {'action':'isOnline'},
            dataType: "json",
            success: function (data) {
                if(data.status == true){
                    $.each(data.result, function (key, value) {
                        if(value.is_online == 1){
                            $('#li-'+value.id).find('.u-designation').find('span').addClass('chat-online').removeClass('chat-offline');
                        }else{
                            $('#li-'+value.id).find('.u-designation').find('span').addClass('chat-offline').removeClass('chat-online');
                        }
                    });
                }else{
                    return false;
                }
              },
          error: function () {
            return false;
          }
        });
    }
    /*------------------------FUNCTION START-----------------------*/
});