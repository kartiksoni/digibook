<?php
	session_start();
    error_reporting(E_ALL);
	ob_start();
	
	date_default_timezone_set('Asia/Kolkata');
	define('SERVER', 'localhost');
	define('USER', 'root');
	define('PASSWORD', '');
	define('DB', 'digibooks_pharmacy');
	$conn = new mysqli(SERVER, USER, PASSWORD, DB);// CONNECT DATABASE
	if($conn->connect_error){
		die('Connect Error: ' . $mysqli->connect_error);
	}
	/* change character set to utf8 */
	if (!mysqli_set_charset($conn, "utf8")){
		printf("Error loading character set utf8: %s\n", mysqli_error($conn));
		exit();
	}
?>