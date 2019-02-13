<?php include('include/config.php');
	if(!isset($_SESSION['auth']) || empty($_SESSION['auth']) || $_SESSION['auth'] == ''){
		header('Location: http://digibooks.cloud/product/login.php');exit;
	}elseif($_SESSION['auth']['type'] == ''){
		header('Location: http://digibooks.cloud/product/index-dashboard.php');exit;
	}
	
	// Setup last pharmacy ID on session 
	if(!isset($_SESSION['auth']['pharmacy_id']) || $_SESSION['auth']['pharmacy_id'] == ''){
	    $user_id = (isset($_SESSION['auth']['id'])) ? $_SESSION['auth']['id'] : '';
	    $pharmacy_query = "SELECT * FROM `pharmacy_profile` WHERE created_by = '".$user_id."'";
        $pharmacy_result = mysqli_query($conn,$pharmacy_query);
        if($pharmacy_result && mysqli_num_rows($pharmacy_result) > 0){
            $pharmacy_row = mysqli_fetch_array($pharmacy_result);
            $_SESSION['auth']['pharmacy_id'] = (isset($pharmacy_row['id'])) ? $pharmacy_row['id'] : '';
        }
        
	}
	
// 	Setup last financial year on session
	if(!isset($_SESSION['auth']['financial']) || $_SESSION['auth']['financial'] == ''){
        $financialPhrmcy = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
        $financialQry = "SELECT * FROM `financial` WHERE pharmacy_id = '".$financialPhrmcy."' AND status = 1 ORDER BY id DESC LIMIT 1";
        $financialRes = mysqli_query($conn,$financialQry);
        if($financialRes){
            $financial_data = mysqli_fetch_assoc($financialRes);
            $_SESSION['auth']['financial'] = (isset($financial_data['id'])) ? $financial_data['id'] : '';
        }
        
    }

if($_SESSION['auth']['user_type'] == "admin"){	
    $user_id = $_SESSION['auth']['id'];
    $rightsQry = "SELECT * FROM `admin_rights` WHERE user_id='".$user_id."'";
    $rights = mysqli_query($conn,$rightsQry);
    $rights_data = mysqli_fetch_assoc($rights);
    $module_data = $rights_data['module'];
    $module_data_array = explode(",",$module_data);
    $sub_module_data = $rights_data['sub_module'];
    $sub_module_data_array = explode(",",$sub_module_data);

    $user_module = array();
    foreach ($module_data_array as $value_module) {
        $moduleQry = "SELECT * FROM `module` WHERE id='".$value_module."'";
        $module = mysqli_query($conn,$moduleQry);
        $data_module = mysqli_fetch_assoc($module);
        $user_module[] = $data_module['name'];
    }
    

    $user_sub_module = array();
    foreach ($sub_module_data_array as $value_sub_module) {
        $sub_moduleQry = "SELECT * FROM `sub_module` WHERE id='".$value_sub_module."'";
        $sub_module = mysqli_query($conn,$sub_moduleQry);
        $data_sub_module = mysqli_fetch_assoc($sub_module);
        $user_sub_module[] = $data_sub_module['name'];
    }

}else if($_SESSION['auth']['user_type'] == "user"){
    $user_role = $_SESSION['auth']['user_role'];
    $user_id = $_SESSION['auth']['id'];
    $rightsQry = "SELECT * FROM `user_rights` WHERE id='".$user_role."'";
    $rights = mysqli_query($conn,$rightsQry);
    $rights_data = mysqli_fetch_assoc($rights);
    $module_data = $rights_data['module'];
    $module_data_array = explode(",",$module_data);
    $sub_module_data = $rights_data['sub_module'];
    $sub_module_data_array = explode(",",$sub_module_data);

    $user_module = array();
    foreach ($module_data_array as $value_module) {
        $moduleQry = "SELECT * FROM `module` WHERE id='".$value_module."'";
        $module = mysqli_query($conn,$moduleQry);
        $data_module = mysqli_fetch_assoc($module);
        $user_module[] = $data_module['name'];
    }
    $user_sub_module = array();
    foreach ($sub_module_data_array as $value_sub_module) {
        $sub_moduleQry = "SELECT * FROM `sub_module` WHERE id='".$value_sub_module."'";
        $sub_module = mysqli_query($conn,$sub_moduleQry);
        $data_sub_module = mysqli_fetch_assoc($sub_module);
        $user_sub_module[] = $data_sub_module['name'];
    }
}

/*if(basename($_SERVER['PHP_SELF']) != "index.php"){
    
}*/


   // $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

    
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