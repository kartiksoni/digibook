$( document ).ready(function() {
    $('table').on('click', '.changestatus', function() {
      var table = $(this).attr('data-table');
      var id = $(this).attr('data-id');
      var status = 0;
      
      if(!$(this).hasClass('active')){
        status = 1;
      }

      $.ajax({
          type: "POST",
          url: 'ajax.php',
          data: {'id':id, 'status': status, 'table': table, 'action': 'updatestatus'},
          dataType: "json",
          success: function (data) {
            if(data.status == true){
              showSuccessToast(data.message);
              $('#errormsg').html(htmlsuccess);
              $("html, body").animate({ scrollTop: 0 }, "slow");
            }else{
                showDangerToast(data.message);
                $("html, body").animate({ scrollTop: 0 }, "slow");
            }
          },
          error: function () {
                showDangerToast('Somthing Want Wrong!');
                $("html, body").animate({ scrollTop: 0 }, "slow");
          }
      });

  });
});