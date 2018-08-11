$(document).ready(function(){
$(document).on('keydown', '.auto', function() {
    var message = $(this).closest("div").find('.empty-message');
    $(this).autocomplete({
        source: function (query, result) {
            $.ajax({
                url: "ajax.php",
                data: {'query': query, 'type':  this.element.attr('data-name'),'action': 'getAutoSearchOrderList'},            
                dataType: "json",
                type: "POST",
                success: function (data) {
                   
                 
                    $(".empty-message").empty();
                    if(data.length > 0){
                        result($.map(data, function (item) {
                            
                            return {
                                label: item.name,
                                value: item.id
                            }
                        }));
                    }else{
                        $(message).html("Not Found!");
                        //$(".empty-message").html("Not Found!");
                        return false;
                    }
                 
                }
            });
          },
          focus: function( query, result ) {
            $(this).val( result.item.label );
            return false;
          },
          select: function( query, result ) {
              return false;
          }
      });
});
});