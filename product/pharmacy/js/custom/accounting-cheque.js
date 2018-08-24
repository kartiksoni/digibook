$(document).ready(function(){
  
    $("#group").change(function(){
        var group_id = $(this).val();
        if(group_id !== ''){
            $.ajax({
              type: "POST",
              url: 'accountajax.php',
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
});    