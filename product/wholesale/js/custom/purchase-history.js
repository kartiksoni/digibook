$( document ).ready(function() {

	
	$('#order-listing1').on('click', 'button.btn-cancel-bill', function() {
		var id = $(this).attr('data-id');
		var $this = $(this);

	    if (confirm('Are you sure want to cancel this bill?')) {
		    $.ajax({
	            type: "POST",
	            url: 'ajax.php',
	            data: {'action':'purchasecancelbill', 'id': id},
	            dataType: "json",
	            beforeSend: function() {
	              $($this).html('Cancel <i class="fa fa-spin fa-refresh"></i>');
	              $($this).prop('disabled', true);
	            },
	            success: function (data) {
	              if(data.status == true){
	                setInterval(function () {
				        $($this).closest('td').html('<a href="javascript:void(0);" class="btn btn-danger btn-xs pt-2 pb-2">Cancelled Bill</a>');
				    },2000);
	              }else{
	                $($this).html('Cancel');
	              	$($this).prop('disabled', false);
	              }
	            },
	            error: function () {
	              $($this).html('Cancel');
	              $($this).prop('disabled', false);
	            }
	        });
		} else {
		    return false;
		}

	});

});