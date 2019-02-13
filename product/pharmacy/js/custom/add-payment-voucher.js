$(document).ready(function(){

    // Add Product button js // 

    $('body').on('click', '.btn-addmore-product', function() {
        var totalproduct = $('.product-tr').length;//for product length
        if(totalproduct > 0){
          $(':input[type="submit"]').prop('disabled', false);
          $(".add_show").hide();
        }
        var html = $('#html-copy').html();
        
        html = html.replace('##SRNO##',totalproduct);
        //html = html.replace('##SRPRODUCT##',totalproduct);
        //html = html.replace('##PRODUCTCOUNT##',totalproduct);
        html = html.replace('<table>','');
        html = html.replace('</table>','');
        html = html.replace('<tbody>','');
        html = html.replace('</tbody>','');
        $('#product-tbody').append(html);
        if(totalproduct <= '2'){
          $('.remove_last').show();
        }
        $(".product-select"+totalproduct).select2();
    });
    // End  Add Product button js //

    // Remove product button js //

    $('body').on('click', '.btn-remove-product', function(e) {
        e.preventDefault();
        $(this).closest ('tr').remove ();
        var totalproduct = $('.product-tr').length;//for product length
          if(totalproduct <= 1){
            $(".add_show").show();
             $(':input[type="submit"]').prop('disabled', true);
          }else{
            $(".add_show").hide();
          }
        $('.taxable').trigger("change");
    });

    // End Remove product button js //

    $(".tax").change(function(){
        var tax = $(this).val();

        if(tax == 'tax_free'){
          $('.tax_free').hide();
          $('#tax-free').show();
          $('#tax').hide();
        }else{
          $('.tax_free').show();
          $('#tax-free').hide();
          $('#tax').show();
        }

        if(tax !== ''){
          $.ajax({
            type: "POST",
            url: 'ajax.php',
            data: {'tax':tax, 'action':'gettax'},
            dataType: "json",
            success: function(data) {
              $("#voucherno").val(data.result);
            }
          });
          return false;
        }
      });
      
      $('body').on('ropertychange change keyup focusout past update', '.taxable', function() {
        var totalamount = 0;
        $('.taxable').each(function(){
          var val = $.trim( $(this).val() );
          if(val){
            val = parseFloat( val.replace( /^\$/, "" ) );
            totalamount += !isNaN( val ) ? val : 0;
          }
        });
        $('#total_amount').val(parseFloat(totalamount).toFixed(2));

        $('#sgst').trigger("change");
        $('#cgst').trigger("change");
        $('#igst').trigger("change");    
      });

      $('body').on('ropertychange change keyup focusout past update', '#sgst, #cgst', function() {
        if($(this).val() != '' && $(this).val() > 0){
          var sgst = $('#sgst').val();
          sgst = (typeof sgst !== 'undefined' && sgst != '' && !isNaN(sgst)) ? parseFloat(sgst) : 0;
          var cgst = $('#cgst').val();
          cgst = (typeof cgst !== 'undefined' && cgst != '' && !isNaN(cgst)) ? parseFloat(cgst) : 0;

          var totalamount = $('#total_amount').val();
          totalamount = (typeof totalamount !== 'undefined' && totalamount != '' && !isNaN(totalamount)) ? parseFloat(totalamount) : 0;

          $("#total_igst").val(0);
          $("#igst").val(0);

          var sgst_amount = (totalamount*sgst)/100;
          var cgst_amount = (totalamount*cgst)/100;
          $("#total_sgst").val(parseFloat(sgst_amount).toFixed(2));
          $("#total_cgst").val(parseFloat(cgst_amount).toFixed(2));
        }else{
          if($('#sgst').val() == '' || $('#sgst').val() <= 0){
            $('#total_sgst').val(0);
          }
          if($('#cgst').val() == '' || $('#cgst').val() <= 0){
            $('#total_cgst').val(0);
          }
        }
      });

      $('body').on('ropertychange change keyup focusout past update', '#igst', function() {
        if($(this).val() != '' && $(this).val() > 0){
            var igst = $(this).val();
            igst = (typeof igst !== 'undefined' && igst != '' && !isNaN(igst)) ? parseFloat(igst) : 0;

            var totalamount = $('#total_amount').val();
            totalamount = (typeof totalamount !== 'undefined' && totalamount != '' && !isNaN(totalamount)) ? parseFloat(totalamount) : 0;

            var igst_amount = (totalamount*igst)/100;
            $("#total_igst").val(parseFloat(igst_amount).toFixed(2));

            $("#total_sgst").val(0);
            $("#total_cgst").val(0);
            $("#cgst").val(0);
            $("#sgst").val(0);
        }else{
          $("#total_igst").val(0);
        }
      });
});