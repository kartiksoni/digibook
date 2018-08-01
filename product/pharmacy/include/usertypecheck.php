<?php include('include/config.php'); ?>

<?php if($_SESSION['auth']['type'] == ''){ 
echo'<script>window.location="index-dashboard.php";</script>';
} ?>

  <!-- <script type="text/javascript">
            $('#usertype-model').modal({
                    backdrop: 'static',
                    keyboard: false, 
                    show: true
            });



            $('#btn-saveusertype').on('click', function(e) {
			  var $form = $(this).closest('form');
			  e.preventDefault();
			  $('#usertype-model').css("display","none");
			  $('#confirm-usertype').modal({
			      backdrop: 'static',
			      keyboard: false,
			      show: true
			    })
			    .one('click', '#btn-confirmusertype', function(e) {
			      $form.trigger('submit');
			    });
			});

			$('#btn-notconfirmusertype').on('click', function(e) {
			  	$('#usertype-model').css("display","block");
			});
        </script> -->