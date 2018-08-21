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


$(".cash").change(function(){
  var cash = $(this).val();
  if(cash !== ''){
    $.ajax({
      type: "POST",
      url: 'accountajax.php',
      data: {'cash':cash, 'action':'getcash'},
      datatype: "json",
      success: function(data) {
        $("#voucherno").val(data);
      }
    });
    return false;
  }
});
});

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
  });
  

$(document).ready(function(){
    $(".reversechange").change(function(){
      var val = $("input[name='reversechange']:checked").val();
      if(val == 'yes'){
        $('#reversechangeper').show();
      }else{
        $('#reversechangeper').hide();
      }
    });
});
