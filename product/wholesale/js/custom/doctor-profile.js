$("#country").change(function(){
    var country_id = $(this).val();
    // $('#city').children('option:not(:first)').remove();
    if(country_id !== ''){
        $.ajax({
          type: "POST",
          url: 'ajax.php',
          data: {'country_id':country_id, 'action':'getCountryByState'},
          dataType: "json",
          success: function (data) {console.log(data);
              if(data.status == true){
                  $('#state').children('option:not(:first)').remove();
                  $.each(data.result, function (i, item) {
                    $('#state').append($('<option>', { 
                        value: item.id,
                        text : item.name 
                    }));
                });
              }else{
                  $('#state').children('option:not(:first)').remove();
              }
          },
          error: function () {
              $('#state').children('option:not(:first)').remove();
          }
          });
    }else{
        $('#state').children('option:not(:first)').remove();
    }
    $('#state').trigger("change");
});

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
