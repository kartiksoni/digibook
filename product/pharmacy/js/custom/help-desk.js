$( document ).ready(function() {
  $('body').on('click', '.btn-addmore-bank', function() {
        //var totalproduct = $('.product-tr').length;//for product length
        var html = $('#bank_html').html();
        
        $('.bank-details-section').append(html);
  });
  $('body').on('click', '.btn-remove-bank', function(e) {
      e.preventDefault();
      $(this).closest ('.content-add-bank').remove ();   
  });

  $('body').on('keyup click', '.product_name ', function () {
    var $this = $(this);
    $(this).autocomplete({
        source: function (query, result) {
            $.ajax({
                url: "ajax.php",
                data: {'query': query, 'type': 'product','action': 'getProductMrpGeneric'},            
                dataType: "json",
                type: "POST",
                success: function (data) {
                  if(data.status == true){
                      result($.map(data.result, function (item) {
                        return {
                            label: item.name,
                            value: item.id,
                        }
                    }));
                  }else{
                      console.log(data);
                       $($this).closest('div').find('.product_id').val('');
                  }
                }
            });
          },
        focus: function( query, result ) {
            $($this).closest('div').find('.product_name').val(result.item.label);
            return false;
        },
        select: function( query, result ) {
          $($this).closest('div').find('.product_id').val(result.item.value);
          return false;
        }
    });
  });

  $('body').on('keyup click', '.company ', function () {
    var $this = $(this);
    $(this).autocomplete({
        source: function (query, result) {
            $.ajax({
                url: "ajax.php",
                data: {'query': query, 'type': 'company','action': 'getProductMrpGeneric'},            
                dataType: "json",
                type: "POST",
                success: function (data) {
                  if(data.status == true){
                      result($.map(data.result, function (item) {
                        return {
                            label: item.name,
                            value: item.id,
                        }
                    }));
                  }else{
                       $($this).closest('div').find('.company_id').val('');
                  }
                }
            });
          },
        focus: function( query, result ) {
            $($this).closest('div').find('.company').val(result.item.label);
            return false;
        },
        select: function( query, result ) {
          $($this).closest('div').find('.company_id').val(result.item.value);
          return false;
        }
    });
  });

  $('body').on('keyup click', '.vendor ', function () {
    var $this = $(this);
    $(this).autocomplete({
        source: function (query, result) {
            $.ajax({
                url: "ajax.php",
                data: {'query': query,'action': 'getserchvendor'},            
                dataType: "json",
                type: "POST",
                success: function (data) {
                  if(data.status == true){
                      result($.map(data.result, function (item) {
                        return {
                            label: item.name,
                            value: item.id,
                        }
                    }));
                  }else{
                       $($this).closest('div').find('.vendor_id').val('');
                  }
                }
            });
          },
        focus: function( query, result ) {
            console.log(result);
            $($this).closest('div').find('.vendor').val(result.item.label);
            return false;
        },
        select: function( query, result ) {
          $($this).closest('div').find('.vendor_id').val(result.item.value);
          return false;
        }
    });
  });
  
});