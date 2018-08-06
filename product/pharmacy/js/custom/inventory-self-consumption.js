$( document ).ready(function() {
  $('body').on('click', '.btn-addmore-product', function() {
        var html = $('#copy-html').html();
        $('#self-more').append(html);
        $( ".tags" ).autocomplete({
            source: function (query, result) {
              $.ajax({
                  url: "ajax.php",
                  data: {'query': query, 'action': 'getproduct_self'},            
                  dataType: "json",
                  type: "POST",
                  success: function (data) {
                    //console.log(data);
                    if(data.length === 0){
                      $(".empty-message0").text("No results found");
                    }else{
                      $(".empty-message0").empty();
                      result($.map(data, function (item) {
                        //console.log(item.id);
                        return {
                            label: item.name,
                            value: item.id,
                            batch: item.batch,
                            purchase_id: item.purchase_id,
                            qty : item.total_qty,
                            expiry: item.expiry,
                            gst: item.gst,
                            unit: item.unit,
                            count_per: item.count_per  // EDIT
                        }
                      }));
                    }
                  }
              });
              },
              focus: function( query, result ) {
                $(this).closest('.self-sub-more').find('.tags').val(result.item.label);
                //$( ".tags" ).val( result.item.label );
                  return false;
                },
              select: function( query, result ) {

                  $(this).closest('.self-sub-more').find('.product_id').val(result.item.value);
                  $(this).closest('.self-sub-more').find('.purchase_id').val(result.item.purchase_id);
                  $(this).closest('.self-sub-more').find('.batch').val(result.item.batch);
                  $(this).closest('.self-sub-more').find('.qty').val(result.item.qty);
                  $(this).closest('.self-sub-more').find('.expiry').val(result.item.expiry);
                  $(this).closest('.self-sub-more').find('.gst').val(result.item.gst);
                  $(this).closest('.self-sub-more').find('.units_strip').val(result.item.unit);
                  $(this).closest('.self-sub-more').find('.price_strip').val(result.item.count_per);
                  
                  return false;
                }
            });
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
                        value: item.id,
                        batch: item.batch,
                        purchase_id: item.purchase_id,
                        qty : item.total_qty,
                        expiry: item.expiry,
                        gst: item.gst,
                        unit: item.unit,
                        count_per: item.count_per  // EDIT
                    }
                  }));
                }
              }
          });
      },
      focus: function( query, result ) {
        $(this).closest('.self-sub-more').find('.tags').val(result.item.label);
        //$( ".tags" ).val( result.item.label );
          return false;
        },
      select: function( query, result ) {
          $(this).closest('.self-sub-more').find('.product_id').val(result.item.value);
          $(this).closest('.self-sub-more').find('.purchase_id').val(result.item.purchase_id);
          $(this).closest('.self-sub-more').find('.batch').val(result.item.batch);
          $(this).closest('.self-sub-more').find('.qty').val(result.item.qty);
          $(this).closest('.self-sub-more').find('.expiry').val(result.item.expiry);
          $(this).closest('.self-sub-more').find('.gst').val(result.item.gst);
          $(this).closest('.self-sub-more').find('.units_strip').val(result.item.unit);
          $(this).closest('.self-sub-more').find('.price_strip').val(result.item.count_per);
          return false;
        }
  });
      
});
