$( document ).ready(function() {
  $('body').on('click', '.btn-addmore-product', function() {
        var html = $('#copy-html').html();
        $('#self-more').append(html);
  });

  $('body').on('click', '.btn-remove-product', function(e) {
        e.preventDefault();
        $(this).closest ('.self-sub-more').remove ();
  });

  $( ".tags" ).autocomplete({
     source: function (query, result) {
          $.ajax({
              url: "ajax.php",
              data: {'query': query, 'action': 'getproduct_self'},            
              dataType: "json",
              type: "POST",
              success: function (data) {
                console.log(data);
                if(data.length === 0){
                  $(".empty-message0").text("No results found");
                }else{
                  $(".empty-message0").empty();
                  result($.map(data, function (item) {
                    return {
                      label: item.name,
                      value: item.id

                      /*  label: item.name,
                        value: item.id,
                        ratio: item.ratio,
                        igst: item.igst,
                        cgst: item.cgst,
                        sgst: item.sgst */   // EDIT
                    }
                  }));
                }
              }
          });
      },
      focus: function( query, result ) {
          $(this).closest('div').find('.tags').val(result.item.value);
          $( ".tags" ).val( result.item.value );
          return false;
        }//*,
      /*select: function( query, result ) {

          $(this).closest('tr').find('.product-id').val(result.item.value);
          $(this).closest('tr').find('.qty-value').val(result.item.ratio);
          $(this).closest('tr').find('.f_igst').val(result.item.igst);
          $(this).closest('tr').find('.f_cgst').val(result.item.cgst);
          $(this).closest('tr').find('.f_sgst').val(result.item.sgst);
          return false;
        }*/
    });
      
});
