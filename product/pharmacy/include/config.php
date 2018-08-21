<?php
	session_start();
    error_reporting(E_ALL);
	ob_start();
	
	date_default_timezone_set('Asia/Kolkata');
	define('SERVER', 'localhost');
	define('USER', 'root');
	define('PASSWORD', '');
	define('DB', 'digibook_pharmacy');
	$conn = new mysqli(SERVER, USER, PASSWORD, DB);// CONNECT DATABASE
	if($conn->connect_error){
		die('Connect Error: ' . $mysqli->connect_error);
	}
	/* change character set to utf8 */
	if (!mysqli_set_charset($conn, "utf8")){
		printf("Error loading character set utf8: %s\n", mysqli_error($conn));
		exit();
	}

	/*$module = array(
		'Dashboard'=>array(),
		'Configuration'=>array('Ledger Management','Product Master','Product Type Master','Product Category Master','Financial Year Master','Pharmacy Master','Bill Notes','Notification Master','Near Expiry Reminder','Comapnay Code Master','Docter Profile','Docter Profile','Admin Rights'),
		'Inventory'=>array('Inventory','Inventory Adjustment','Update Inventory','Inventory Setting','Product Master','Self Consumption'),
		'Purchase'=>array('Purchase Bill','Purchase Return','Purchase Return List','Cancel List','History','Settings'),
		'Delievery Challan'=>array('Add challan','View challan'),
		'Sell'=>array('Tax Billing','BOS','Service Billing'),
		'Transfer'=>array('Cash Management','Customer Receipt','Cheque','Vendor Payment','Financial Year Settings','Credit Note / Purchase Note','Quotation / Estimate / Proformo Invoice'),
		'Leads'=>array(),
		'Order Place'=>array('Order','List','Missed Sales Order','Settings'),
		'Reports'=>array(),
		'Branch'=>array(),
		'Accounts'=>array(),
		'Users'=>array());*/


	$module = [
	    'Dashboard' => [],
	    'Configuration' => ['Ledger Management','Product Master','Product Type Master','Product Category Master','Financial Year Master','Pharmacy Master','Bill Notes','Notification Master','Near Expiry Reminder','Comapnay Code Master','Docter Profile','Docter Profile','Admin Rights'],
	    'Inventory' => ['Inventory','Inventory Adjustment','Update Inventory','Inventory Setting','Product Master','Self Consumption'],
	    'Purchase' => ['Purchase Bill','Purchase Return','Purchase Return List','Cancel List','History','Settings'],
	    'Delievery Challan' => ['Add challan','View challan'],
	    'Sell'=> ['Tax Billing','BOS','Service Billing'],
	    'Transfer' => ['Cash Management','Customer Receipt','Cheque','Vendor Payment','Financial Year Settings','Credit Note / Purchase Note','Quotation / Estimate / Proformo Invoice'],
	    'Leads' => [],
	    'Order Place' =>['Order','List','Missed Sales Order','Settings'],
	    'Reports' =>[],
	    'Branch' => [],
	    'Accounts' =>[],
	    'Users' => []



	];

	

	

/*	echo"<pre>";
	print_r($module);exit;*/
?>