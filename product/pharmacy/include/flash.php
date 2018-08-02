
<div class="row">
	<div class="col-md-12">
		<?php
			if(isset($_SESSION['msg']) && !empty($_SESSION['msg'])){
				if(isset($_SESSION['msg']['success']) && $_SESSION['msg']['success'] != ''){
					echo '<div class="alert alert-icon alert-success alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-check-all"></i>'.$_SESSION['msg']['success'].'</div>';
				}elseif(isset($_SESSION['msg']['fail']) && $_SESSION['msg']['fail'] != ''){
					echo '<div class="alert alert-icon alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-block-helper"></i>'.$_SESSION['msg']['fail'].'</div>';
				}elseif(isset($_SESSION['msg']['info']) && $_SESSION['msg']['info'] != ''){
					echo '<div class="alert alert-icon alert-info alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-information"></i>'.$_SESSION['msg']['info'].'</div>';
				}elseif(isset($_SESSION['msg']['warning']) && $_SESSION['msg']['warning'] != ''){
					echo '<div class="alert alert-icon alert-warning alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="mdi mdi-alert"></i>'.$_SESSION['msg']['warning'].'</div>';
				}
				unset($_SESSION['msg']);
			}
		?>
	</div>
</div>
