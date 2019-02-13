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
      
      if($('.investment-item').is(":hidden")){
        if(group_id == 20){
          $('#addinvestment-model').modal('show');
        }else{
          $('.investment-item').hide();
          $('#is_investment').val(0);
        }
      }
    });

    $('body').on('change', '.type1', function () {
      if($(this).val() != 20){
        $('.investment-item').hide();
        $('#is_investment').val(0);
      }
    });

    $('body').on('click', '#btn-investment-yes', function () {
      $('.investment-item').show();
      $('#is_investment').val(1);
    });

    $('body').on('click', '#btn-investment-no', function () {
      $('.investment-item').hide();
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
        var data_name = $(this).attr('data-name');
        var id = $(this).val();
        var $this = $(this);

          if(data_name == 'first' && id != ''){
            var second_id = $('.particular:eq(1)').val();
            if(id == second_id){
              $($this).val('').trigger('change');
              showDangerToast('Already Selected This Perticular! Please Select Another Perticular.');
              return false;
            }
          }else if(data_name == 'second' && id != ''){
            var first_id = $('.particular:eq(0)').val();
            if(id == first_id){
              showDangerToast('Already Selected This Perticular! Please Select Another Perticular.');
              $($this).val('').trigger('change');
              return false;
            }
          }
        
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
    
   $('body').on('propertychange change keyup click focusout past update', '.debit,.credit1', function() {
    
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

  $('body').on('propertychange change keyup click focusout past update', '.debit1,.credit', function() {
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

  $('body').on('propertychange change keyup click focusout past update', '#remarks', function() {
    var remarks = $(this).val();
    $('#remarks1').val(remarks);
  });

  $('body').on('propertychange change keyup click focusout past update', '#qty, #rate', function() {
    var qty = $('#qty').val();
    qty = (typeof qty !== 'undefined' && !isNaN(qty) && qty !== '') ? parseFloat(qty) : 0;
    $('#qty1').val(qty);

    var rate = $('#rate').val();
    rate = (typeof rate !== 'undefined' && !isNaN(rate) && rate !== '') ? parseFloat(rate) : 0;
    $('#rate1').val(rate);

    var amount = (qty*rate)

    $('#debit').val(amount);
    $('#credit1').val(amount);
  });

  $('body').on('propertychange change keyup click focusout past update', '#qty1, #rate1', function() {
    var qty = $('#qty1').val();
    qty = (typeof qty !== 'undefined' && !isNaN(qty) && qty !== '') ? parseFloat(qty) : 0;
    $('#qty').val(qty);

    var rate = $('#rate1').val();
    rate = (typeof rate !== 'undefined' && !isNaN(rate) && rate !== '') ? parseFloat(rate) : 0;
    $('#rate').val(rate);

    var amount = (qty*rate)

    $('#debit').val(amount);
    $('#credit1').val(amount);
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







