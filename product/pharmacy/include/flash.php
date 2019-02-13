<script>
		<?php
			if(isset($_SESSION['msg']) && !empty($_SESSION['msg'])){
				if(isset($_SESSION['msg']['success']) && $_SESSION['msg']['success'] != ''){
					$msg = $_SESSION['msg']['success'];
					echo 'showSuccessToast("'.$msg.'");';
				}elseif(isset($_SESSION['msg']['fail']) && $_SESSION['msg']['fail'] != ''){
					$msg = $_SESSION['msg']['fail'];
					echo 'showDangerToast("'.$msg.'");';
				}elseif(isset($_SESSION['msg']['info']) && $_SESSION['msg']['info'] != ''){
					$msg = $_SESSION['msg']['info'];
					echo 'showInfoToast("'.$msg.'");';
				}elseif(isset($_SESSION['msg']['warning']) && $_SESSION['msg']['warning'] != ''){
					$msg = $_SESSION['msg']['warning'];
					echo 'showWarningToast("'.$msg.'");';
				}
				unset($_SESSION['msg']);
			}
		?>
</script>