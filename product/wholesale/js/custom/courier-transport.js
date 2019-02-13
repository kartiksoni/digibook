// for get state wise city
$("#state").change(function(){
    var state_id = $(this).val();
    if(state_id !== ''){
        $.ajax({
          type: "POST",
          url: 'ajax.php',
          data: {'state_id':state_id, 'action':'getStateByCity'},
          dataType: "json",
          success: function (data) {
              if(data.status == true){
                  $('#city').children('option:not(:first)').remove();
                  $.each(data.result, function (i, item) {
                    $('#city').append($('<option>', { 
                        value: item.id,
                        text : item.name 
                    }));
                });
              }else{
                  $('#city').children('option:not(:first)').remove();
              }
          },
          error: function () {
              $('#city').children('option:not(:first)').remove();
          }
          });
    }else{
        $('#city').children('option:not(:first)').remove();
}
});
