$( document ).ready(function() {
    
	$('body').on('change', '.bank_transfer', function () {
        var data_name = $(this).attr('data-name');
        var id = $(this).val();
        var $this = $(this);

          if(data_name == 'first' && id != ''){
            var second_id = $('.bank_transfer:eq(1)').val();
            if(id == second_id){
              $($this).val('').trigger('change');
              showDangerToast('Already Selected This Perticular! Please Select Another Perticular.');
              return false;
            }
          }else if(data_name == 'second' && id != ''){
            var first_id = $('.bank_transfer:eq(0)').val();
            if(id == first_id){
              showDangerToast('Already Selected This Perticular! Please Select Another Perticular.');
              $($this).val('').trigger('change');
              return false;
            }
          }
    });

	$('body').on('propertychange change keyup click focusout past update', '.debit,.credit1', function() {
    
     var debit = $(this).val();
     debit = (debit != '') ? parseFloat(debit) : 0;
     if(debit > 0){
          $('#credit1').val(parseFloat(debit));
           $('#credit').val('');
          $('#debit1').val('');
        }
        
        var credit1 = $(this).val();
        credit1 = (credit1 != '') ? parseFloat(credit1) : 0;
        if(credit1 > 0){
          $('#debit').val(parseFloat(credit1));
           $('#credit').val('');
          $('#debit1').val('');
        }
    });
    $('body').on('propertychange change keyup click focusout past update', '.debit1,.credit', function() {
      var debit1 = $(this).val();
      debit1 = (debit1 != '') ? parseFloat(debit1) : 0;
      if(debit1 > 0){
        $('#credit').val(parseFloat(debit1));
        $('#credit1').val('');
       $('#debit').val('');
      }
          
      var credit = $(this).val();
      credit = (credit != '') ? parseFloat(credit) : 0;
      if(credit > 0 ){
        $('#debit1').val(parseFloat(credit));
        $('#credit1').val('');
       $('#debit').val('');
      } 
  });

  $('body').on('propertychange change keyup click focusout past update', '#remarks', function() {
    var remarks = $(this).val();
    $('#remarks1').val(remarks);
  });
}); 

