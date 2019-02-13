$( document ).ready(function() {
    
    $('body').on('change', '#is_file', function () {
        if ($(this).is(':checked')){
            $('#file_upload_div').show();
        }
        else{
            $('#file_upload_div').hide();
        }
    });
    
    $('body').on('change', '.type', function () {
      var group_id = $(this).val();
      var perticular = $(this).closest('tr').find('.particular');
      if(group_id != ''){
            $.ajax({
                url: "ajax.php",
                data: {'query': group_id,'action': 'getgrouplist'},            
                dataType: "json",
                type: "POST",
               success: function (data) {
                  if(data.status == true){
                   $(perticular).children('option:not(:first)').remove();
                    $.each(data.result, function (i, item) {
                      $(perticular).append($('<option>', { 
                          value: item.id,
                          text : item.name 
                      }));
                    });
                  }else{
                      $(perticular).children('option:not(:first)').remove();
                  }
                },
                error:function(data){
                  $(perticular).children('option:not(:first)').remove();
                }
            });
      }else{
            $(perticular).children('option:not(:first)').remove();
      }
    });
    
    // Remove product button js //
    $('body').on('click', '.btn-remove-product', function(e) {
        e.preventDefault();
        $(this).closest ('tr').remove ();
        var totalproduct = $('.product-tr').length;//for product length
          if(totalproduct <= 1){
            $(".add_show").show();
             $(':input[type="submit"]').prop('enable', true);
          }else{
            $(".remove").hide();
          }
        $('.f_credit').trigger("change");
        $('.f_debit').trigger("change");
    });
    // End Remove product button js //
    
    // ADD MORE ITEM
    $('body').on('click', '.addmore', function() {
          var html = $('#product-tr').html();
          html = html.replace('<table>','');
          html = html.replace('</table>','');
          html = html.replace('<tbody>','');
          html = html.replace('</tbody>','');
          $('#product-tbody').append(html);
    
          $('#product-tbody tr:last').find('.type').select2();
          $('#product-tbody tr:last').find('.particular').select2();
    });
    
    $('body').on('change', '.particular', function () {
  
        var id = $(this).val();
        var $this = $(this);
        
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
                $($this).closest('tr').find('.ledger_running_balance').html(running_balance);
              }else{
                $($this).closest('tr').find('.ledger_running_balance').html('0 Dr');
              }
              
            },
            error: function () {
                $($this).closest('tr').find('.ledger_running_balance').html('0 Dr');
            }
          });
        }else{
          $($this).closest('tr').find('.ledger_running_balance').html(null);
        }
    });
    
   $('body').on('ropertychange change keyup click focusout past update', '.debit,.credit1', function() {
    
     var debit = $(this).val();
     debit = (debit != '') ? parseFloat(debit) : 0;
     if(debit > 0){
          $('#credit1').val(parseFloat(debit));
           $('#credit').val('');
          $('#debit1').val('');
        }
        
        var credit1 = $(this).val();
        credit1 = (credit1 != '') ? parseFloat(credit1) : 0;
        if(credit1 > 0){
          $('#debit').val(parseFloat(credit1));
           $('#credit').val('');
          $('#debit1').val('');
        }
      });

$('body').on('ropertychange change keyup click focusout past update', '.debit1,.credit', function() {
  
    var debit1 = $(this).val();
    debit1 = (debit1 != '') ? parseFloat(debit1) : 0;
    if(debit1 > 0){
          $('#credit').val(parseFloat(debit1));
          $('#credit1').val('');
         $('#debit').val('');
        }
        
        var credit = $(this).val();
        credit = (credit != '') ? parseFloat(credit) : 0;
        if(credit > 0 ){
          $('#debit1').val(parseFloat(credit));
          $('#credit1').val('');
         $('#debit').val('');
        }
        
      });
      
      
    
    // $('body').on('keyup', '.debit', function () {
    //     var val = $(this).val();
    //     val = (val != '') ? parseFloat(val) : 0;
    //     if(val > 0){
    //         $(this).closest('tr').find('.credit').val(0);
    //     }
    // });
    
    // $('body').on('keyup', '.credit', function () {
    //     var val = $(this).val();
    //     val = (val != '') ? parseFloat(val) : 0;
    //     if(val > 0){
    //         $(this).closest('tr').find('.debit').val(0);
    //     }
    // });
});

/*        $(document).ready(function(){
    $(".type1").change(function(){
      alert("i am working");
       //alert("$type1");
       var val = $("input[name='select_type1']:checked").val();
          alert(val);
                if(val == 'type1'){
                  
                  $('.select_type1').show();
                        $('.multiple-company').hide();
                  }else if(val == 'select_type1'){
                        $('.multiple-company').show();      
                        $('.type1').hide();
                  }else{
                        $('.select_type1').hide();
                        $('.multiple-company').hide();
                  }
      })
      
});*/







