<?php include('include/config.php');
	if(!isset($_SESSION['auth']) || empty($_SESSION['auth']) || $_SESSION['auth'] == ''){
		header('Location: http://digibooks.cloud/product/login.php');exit;
	}elseif($_SESSION['auth']['type'] == ''){
		header('Location: http://digibooks.cloud/product/index-dashboard.php');exit;
	} 
?>