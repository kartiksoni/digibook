$( document ).ready(function() {
    $(document).on('click','.delete',function(){
		var id = $(this).attr('data-id');
		var action = $(this).attr('data-action');
		var $this = $(this);
		swal({
	        title: "Are you sure ??",
	        text: "Are you sure want to delete this record?",
	        icon: "warning",
	        buttons: true,
	        dangerMode: true,
	    }).then((willDelete) => {
            if (willDelete) {

                //ajax request
                $.ajax({
                    type: "POST",
                    url: 'deleteAjax.php',
                    data: {'id': id, 'action': action},
                    dataType: "json",
                    success: function (data) {
                        if(data.status == true){
                            swal(data.message, {
                                icon: "success",
                            });
                            //$($this).closest('tr').remove();
                              //refresh page after 2 secs
                            setTimeout(location.reload.bind(location), 2000);
                        }else{
                            swal(data.message, {
                                icon: "error",
                              });
                        }
                    },
                    error: function (xhr, textStatus, errorThrown) {
                        console.log('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
                    }
                });

            }else{
                swal("Your record entry are safe!", {
                    icon: "success",
                });
            }
        });
	});
});