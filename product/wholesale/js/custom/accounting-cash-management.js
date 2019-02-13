$(document).ready(function(){
  
    $("#group").change(function(){
        var group_id = $(this).val();
        if(group_id !== ''){
            $.ajax({
              type: "POST",
              url: 'ajax.php',
              data: {'group_id':group_id, 'action':'getgroup'},
              dataType: "json",
              success: function (data) {console.log(data);
                  if(data.status == true){
                      $('#ledger').children('option:not(:first)').remove();
                      $.each(data.result, function (i, item) {
                        $('#ledger').append($('<option>', { 
                            value: item.id,
                            text : item.name 
                        }));
                    });
                  }else{
                      $('#ledger').children('option:not(:first)').remove();
                  }
              },
              error: function () {
                  $('#ledger').children('option:not(:first)').remove();
              }
              });
        }else{
            $('#ledger').children('option:not(:first)').remove();
        }
        $('#ledger').trigger("change");
    });


    $(".cash").change(function(){
      var cash = $(this).val();
      if(cash == "cash_payment"){
          $(".credit_debit").val("Debit");
      }else if(cash == "cash_receipt"){
          $(".credit_debit").val("Credit");
      }
      if(cash !== ''){
        $.ajax({
          type: "POST",
          url: 'ajax.php',
          data: {'cash':cash, 'action':'getcash'},
          dataType: "json",
          success: function(data) {
            $("#voucherno").val(data.result);
          }
        });
        return false;
      }
    });
    
    $("#ledger").change(function() {
        var id = $(this).val();
        
        if(id !== ''){
          $.ajax({
            type: "POST",
            url: 'ajax.php',
            data: {'id':id, 'action':'perticularRunningBalance'},
            dataType: "json",
            success: function (data) {
              if(data.status == true){
                $('#ledger_running_balance').show();
                var running_balance = (typeof data.result.running_balance !== 'undefined' && data.result.running_balance !== '' && !isNaN(data.result.running_balance)) ? data.result.running_balance : 0;
                running_balance = (running_balance >= 0) ? Math.abs(running_balance).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')+' Dr' : Math.abs(running_balance).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')+' Cr';
                $('#ledger_running_balance').html(running_balance);
              }else{
                $('#ledger_running_balance').html(0);
                $('#ledger_running_balance').hide();
              }
              
            },
            error: function () {
                $('#ledger_running_balance').html(0);
                $('#ledger_running_balance').hide();
            }
          });
        }else{
          $('#ledger_running_balance').html(0);
          $('#ledger_running_balance').hide();
        }
    });
});

$(document).ready(function(){
  $(".state").change(function(){
    var state = $(this).val();
    if(state !== ''){
      $.ajax({
        type: "POST",
        url: 'ajax.php',
        data: {'state':state, 'action':'getstate'},
        datatype: "json",
        success: function(data) {
          $("#state_gst_code").val(data); 
        }
      });
      return false;
    }
  });
  });
  

$(document).ready(function(){
    $(".reversechange").change(function(){
      var val = $("input[name='reversechange']:checked").val();
      if(val == 'yes'){
        $('#reversechangeper').show();
      }else{
        $('#reversechangeper').hide();
      }
    });
});
