$( document ).ready(function() {
    $('table').on('click', '.changestatus', function() {
      var table = $(this).attr('data-table');
      var id = $(this).attr('data-id');
      var status = 0;
      // var htmlsuccess = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-success alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';
      // var htmlerror = '<div class="row"><div class="col-md-12"><div class="alert alert-icon alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>##MSG##</div></div></div>';

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
              // htmlsuccess = htmlsuccess.replace("##MSG##", data.message);
             // $('#errormsg').html(htmlsuccess);
              $("html, body").animate({ scrollTop: 0 }, "slow");
            }else{
                showDangerToast(data.message);
             // htmlerror =  htmlerror.replace("##MSG##", data.message);
             // $('#errormsg').html(htmlerror);
              $("html, body").animate({ scrollTop: 0 }, "slow");
            }
          },
          error: function () {
                showDangerToast('Somthing Want Wrong!');
           // htmlerror =  htmlerror.replace("##MSG##", 'Somthing Want Wrong!');
            // $('#errormsg').html(htmlerror);
            $("html, body").animate({ scrollTop: 0 }, "slow");
          }
      });

  });
});