<?php
include('include/usertypecheck.php'); 
if(isset($_REQUEST['id']) && $_REQUEST['id'] != ''){
    update_leger_ob();
    
    addfinacialproductqty();
	$_SESSION['auth']['financial'] = $_REQUEST['id'];
}
year_change_eff();

updatefinacialproductqty();

header('location:'.$_SERVER['HTTP_REFERER']);exit;

?>