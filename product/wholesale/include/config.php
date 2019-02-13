<?php
session_start();
error_reporting(E_ALL);
ob_start();
	   
	 /*----------FOR PHARMACY DB START---------*/
    	date_default_timezone_set('Asia/Kolkata');
    	define('SERVER', 'localhost');
    	define('USER', 'root');
    	define('PASSWORD', '');
    	define('DB', 'yamunxym_digibooks_wholesale');
    	$conn = new mysqli(SERVER, USER, PASSWORD, DB);// CONNECT DATABASE
    	if($conn->connect_error){
    		die('Connect Error: ' . $mysqli->connect_error);
    	}
    /*----------FOR PHARMACY DB END---------*/
	
	/*----------FOR WEB DB START---------*/
		define('WEBSERVER', 'localhost');
		define('WEBUSER', 'root');
		define('WEBPASSWORD', '');
		define('WEBDB', 'yamunxym_digibooks_website');
		$webconn = new mysqli(WEBSERVER, WEBUSER, WEBPASSWORD, WEBDB);// CONNECT DATABASE
		if($webconn->connect_error){
			die('Connect Error: ' . $mysqli->connect_error);
		}
	/*----------FOR WEB DB END---------*/
	
	if(((!isset($_SESSION['state_code'])) || $_SESSION['state_code'] == '') || ((!isset($_SESSION['state_id'])) || $_SESSION['state_id'] == '')){
	    if(isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != ''){
	        $getSteteCodeQ = "SELECT ph.stateId, st.state_code_gst, st.id as state_id FROM pharmacy_profile ph INNER JOIN own_states st ON ph.stateId = st.id WHERE ph.id = '".$_SESSION['auth']['pharmacy_id']."'"; 
	        $getSteteCodeR = mysqli_query($conn, $getSteteCodeQ);
	        if($getSteteCodeR && mysqli_num_rows($getSteteCodeR) > 0){
	            $getSteteCodeRow = mysqli_fetch_assoc($getSteteCodeR);
	            $_SESSION['state_code'] = (isset($getSteteCodeRow['state_code_gst'])) ? $getSteteCodeRow['state_code_gst'] : '';
	            $_SESSION['state_id'] = (isset($getSteteCodeRow['state_id'])) ? $getSteteCodeRow['state_id'] : '';
	        }else{
	            $_SESSION['state_code'] = '';
	            $_SESSION['state_id'] = '';
	        }
	    }else{
	        $_SESSION['state_code'] = '';
	        $_SESSION['state_id'] = '';
	    }
	}
	
	/* change character set to utf8 */
	if (!mysqli_set_charset($conn, "utf8")){
		printf("Error loading character set utf8: %s\n", mysqli_error($conn));
		exit();
	}
	
	/*------------------DEFAULT LEDGER ACCOUNT FLAG ARRAY START------------------*/
	    $account_flag = [
	            'SALE_ACC' => 1,
	            'SALE_ACC_OGS' => 2,
	            'PURCHASE_ACC' => 3,
	            'PURCHASE_ACC_OGS' => 4,
	            'GST_ON_SALE' => 5,
	            'IGST_ON_SALE' => 6,
	            'GENERAL_SALE' => 7,
	            'OPENING_STOCK' => 8,
	            'CLOSING_STOCK'=> 9,
	            'GST_ON_PURCHASE' => 10,
	            'IGST_ON_PURCHASE' => 11,
	            'TAX_LIABILITY' => 12
	        ];
	/*------------------DEFAULT LEDGER ACCOUNT FLAG ARRAY END------------------*/
	
	include('include/function.php');
	include('include/function_second.php');
	include('include/function_generate_number.php');
	//define('BASE_URL', 'http://localhost/digibook/product/api/api.php' );
	define('BASE_URL', 'http://digibooks.cloud/product/api/api.php' );
	
	/*--------------------DEFINE PURCHASE DOCTOR START----------------------*/
	$purchaseDoctor = [
	        0 => [
	                'id' => 1,
	                'name' => 'Dr. Divyang Patel'
	            ],
	        1 => [
	                'id' => 2,
	                'name' => 'Dr. Pravin Dudhat'
	            ]
	    ];
	/*--------------------DEFINE PURCHASE DOCTOR END------------------------*/
	
	/*--------------------PURCHASE COURIER CHARGE (%) START-----------------------*/
	    $purchaseCourierCharge = [5,12,18];
	/*--------------------PURCHASE COURIER CHARGE (%) END-----------------------*/
	
	/*--------------------GST MASTER START-----------------------*/
	    
	    
	    $gstMasterQ = "SELECT * FROM `gst_master`"; 
	    $getMasterR = mysqli_query($conn, $gstMasterQ);
	    if($getMasterR){
	        $defult_gst = array("Non Gst","Exempted","Nil Rated");
	        
	        if(mysqli_num_rows($getMasterR) <= 0){
	            foreach ($defult_gst as $key => $value) {
                	# code...
                	$InsterQ = "INSERT INTO `gst_master`( `gst_name`,`edit_status`,`status`) VALUES ('".$value."','1','1')";
                	$InsterR = mysqli_query($conn, $InsterQ);
                }
	        }
	    }
	/*--------------------GST MASTER END-----------------------*/
	
	/*--------------------GST MASTER % START-----------------------*/
	defult_gst_per();
	/*--------------------GST MASTER % END-----------------------*/
	
	/*--------------------DEFULT LEAGER CREATE START-----------------------*/
	defult_leger();
	/*--------------------DEFULT LEAGER CREATE END-----------------------*/
	
	
	/*-------------------DEFAULT SETTING CREATE START---------------------*/
	default_setting();
	/*-------------------DEFAULT SETTING CREATE END---------------------*/
?>