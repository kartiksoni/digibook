<?php 

	/////////////////////////////////////////////////
	//Author : Gautam Makwana
	//Date : 14-07-2018
	/////////////////////////////////////////////////

	date_default_timezone_set('Asia/Kolkata');
	
	define('SERVER', 'localhost');
	define('USER', 'root');
	define('PASSWORD', '');
	define('DB', 'digibook_website');
	
	$conn = mysqli_connect(SERVER, USER, PASSWORD, DB);// CONNECT DATABASE

	if($conn->connect_error){
		die('Connect Error: ' . $mysqli->connect_error);
	}
	mysqli_character_set_name($conn);

	/* change character set to utf8 */
	if (!mysqli_set_charset($conn, "utf8")){
	    printf("Error loading character set utf8: %s\n", mysqli_error($conn));
	    exit();
	} else { /*printf("Current character set: %s\n", mysqli_character_set_name($conn));exit;*/}

	error_reporting(0);

	session_start();


?>