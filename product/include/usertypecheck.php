<?php include('include/config.php'); ?>

<?php

	if(!isset($_SESSION['auth']) || empty($_SESSION['auth']) || $_SESSION['auth'] == ''){
		header('Location: ../index.php');exit;
	}elseif($_SESSION['auth']['type'] == ''){ 
		header('Location: ../index-dashboard.php');exit;
	} 
?>