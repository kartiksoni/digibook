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

    $(".voucher").change(function(){
        var voucher = $(this).val();
        if(voucher == "payment"){
        $(".credit_debit").val("Credit");
        }else if(voucher == "receipt"){
        $(".credit_debit").val("Debit");
        }    
        if(voucher !== ''){
          $.ajax({
            type: "POST",
            url: 'ajax.php',
            data: {'voucher':voucher, 'action':'getvoucher'},
            datatype: "json",
            success: function(data) {
              $("#voucherno").val(data);
            }
          });
          return false;
        }
      });
      
       $("#ledger").change(function() {
        var id = $(this).val();
        
        if(id != ''){
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

                // show bank detail
                if(typeof data.result.bank_detail !== 'undefined'){
                  $('#bank-detail').show();
                  $('#bank_name').html((typeof data.result.bank_detail.bank_name !== 'undefined') ? data.result.bank_detail.bank_name : '');
                  $('#bank_ac_no').html((typeof data.result.bank_detail.bank_ac_no !== 'undefined') ? data.result.bank_detail.bank_ac_no : '');
                  $('#branch_name').html((typeof data.result.bank_detail.branch_name !== 'undefined') ? data.result.bank_detail.branch_name : '');
                  $('#ifsc_code').html((typeof data.result.bank_detail.ifsc_code !== 'undefined') ? data.result.bank_detail.ifsc_code : '');
                }else{
                  $('#bank-detail').hide();
                }
              }else{
                $('#ledger_running_balance').html(0);
                $('#ledger_running_balance').hide();
                $('#bank-detail').hide();
              }
              
            },
            error: function () {
                $('#ledger_running_balance').html(0);
                $('#ledger_running_balance').hide();
                $('#bank-detail').hide();
            }
          });
        }else{
          $('#ledger_running_balance').html(0);
          $('#ledger_running_balance').hide();
          $('#bank-detail').hide();
        }
    });
    
    var id = $("#ledger").val();

    if(id != ''){
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

                // show bank detail
                if(typeof data.result.bank_detail !== 'undefined'){
                  $('#bank-detail').show();
                  $('#bank_name').html((typeof data.result.bank_detail.bank_name !== 'undefined') ? data.result.bank_detail.bank_name : '');
                  $('#bank_ac_no').html((typeof data.result.bank_detail.bank_ac_no !== 'undefined') ? data.result.bank_detail.bank_ac_no : '');
                  $('#branch_name').html((typeof data.result.bank_detail.branch_name !== 'undefined') ? data.result.bank_detail.branch_name : '');
                  $('#ifsc_code').html((typeof data.result.bank_detail.ifsc_code !== 'undefined') ? data.result.bank_detail.ifsc_code : '');
                }else{
                  $('#bank-detail').hide();
                }
              }else{
                $('#ledger_running_balance').html(0);
                $('#ledger_running_balance').hide();
                $('#bank-detail').hide();
              }
              
            },
            error: function () {
                $('#ledger_running_balance').html(0);
                $('#ledger_running_balance').hide();
                $('#bank-detail').hide();
            }
          });
        }else{
          $('#ledger_running_balance').html(0);
          $('#ledger_running_balance').hide();
          $('#bank-detail').hide();
        }
    
    $("#bank").change(function() {
        var total;
        var bank_id = $(this).val();
        
        if(bank_id !== ''){
          $.ajax({
            type: "POST",
            url: 'ajax.php',
            data: {'id':bank_id, 'action':'bankrunningbalance'},
            dataType: "json",
            success: function (data) {
              if(data.status == true){
                var bank_running = (typeof data.result.bank_running !== 'undefined' && data.result.bank_running !== '' && !isNaN(data.result.bank_running)) ? data.result.bank_running : 0;  
                bank_running = (bank_running >= 0) ? Math.abs(bank_running).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')+' Dr' : Math.abs(bank_running).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')+' Cr';
                
                $('#bank_running').html(bank_running);
              }else{
                $('#bank_running').html(0);
              }
              
            },
            error: function () {
                $('#bank_running').html(0);
            }
          });
        }else{
          $('#bank_running').html(0);
        }
    });
    
    var bank_id = $("#bank").val();
        
        if(bank_id !== ''){
          $.ajax({
            type: "POST",
            url: 'ajax.php',
            data: {'id':bank_id, 'action':'bankrunningbalance'},
            dataType: "json",
            success: function (data) {
              if(data.status == true){
                var bank_running = (typeof data.result.bank_running !== 'undefined' && data.result.bank_running !== '' && !isNaN(data.result.bank_running)) ? data.result.bank_running : 0;  
                bank_running = (bank_running >= 0) ? Math.abs(bank_running).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')+' Dr' : Math.abs(bank_running).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')+' Cr';
                $('#bank_running').html(bank_running);
              }else{
                $('#bank_running').html(0);
              }
              
            },
            error: function () {
                $('#bank_running').html(0);
            }
          });
        }else{
          $('#bank_running').html(0);
        } 
        
    $(".reversechange").change(function(){
      var val = $("input[name='reversechange']:checked").val();
      if(val == 'yes'){
        $('#reversechangeper').show();
      }else{
        $('#reversechangeper').hide();
      }
    });
});    

