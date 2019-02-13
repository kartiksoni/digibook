<?php 

	include('include/config.php');
	
	//update logout status
	if(isset($_SESSION['auth']['id']) && $_SESSION['auth']['id'] != ''){
		mysqli_query($webconn, "UPDATE users SET is_online = 0 WHERE id = '".$_SESSION['auth']['id']."'");
	}
	
	unset($_SESSION['auth']);
	session_destroy();
	header("location:../index.php");exit;

?>