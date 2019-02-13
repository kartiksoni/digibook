$(document).ready(function(){
    $(".state").change(function(){
      var state = $(this).val();
      if(state !== ''){
        $.ajax({
          type: "POST",
          url: 'accountajax.php',
          data: {'state':state, 'action':'getstate'},
          datatype: "json",
          success: function(data) {
            $("#state_gst_code").val(data); 
          }
        });
        return false;
      }
    });
    
    $('body').on('ropertychange change keyup focusout past update', '.taxable', function() {
      var taxable = $("#taxable").val();
      
      if(taxable){
        var sgst = $("#sgst").val();
        var totalsgst = (sgst / 100) * taxable;
        $("#total_sgst").val(parseFloat(totalsgst).toFixed(2));
      }

      $('body').on('ropertychange change keyup focusout past update', '#sgst', function() {
        var sgst = $("#sgst").val();
        if(sgst > 0){
        $("#igst").val(0);
        $("#total_igst").val(0);
        }
      });

      if(taxable){
        var cgst = $("#cgst").val();
        var totalcgst = (cgst / 100) * taxable;
        $("#total_cgst").val(parseFloat(totalcgst).toFixed(2));
      } 

      $('body').on('ropertychange change keyup focusout past update', '#cgst', function() { 
        var cgst = $("#cgst").val();
        if(cgst > 0){
        $("#igst").val(0);
        $("#total_igst").val(0);
        }
      });

      if(taxable){
        var igst = $("#igst").val();
        var totaligst = (igst / 100) * taxable;
        $("#total_igst").val(parseFloat(totaligst).toFixed(2));
      }

      $('body').on('ropertychange change keyup focusout past update', '#igst', function() {  
        var igst = $("#igst").val();
        if(igst > 0){
        $("#sgst").val(0);
        $("#total_sgst").val(0);
        $("#cgst").val(0);
        $("#total_cgst").val(0);
        }
      });
    });

    $('#sgst').trigger("change");
    $('#cgst').trigger("change");
    $('#igst').trigger("change");
});
  