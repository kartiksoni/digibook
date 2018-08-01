<?php 

	include('include/config.php');
	unset($_SESSION['auth']);
	session_destroy();
	header("location:index.php");exit;

?>