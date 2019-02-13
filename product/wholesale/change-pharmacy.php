<?php
include('include/usertypecheck.php'); 
//session_start();
if(isset($_REQUEST['id']) && $_REQUEST['id'] != ''){
	$_SESSION['auth']['pharmacy_id'] = $_REQUEST['id'];
	$selectQ = "SELECT * FROM `financial` WHERE pharmacy_id = '".$_SESSION['auth']['pharmacy_id']."' ORDER BY `id` DESC LIMIT 1";
	$changes_finacial = mysqli_query($conn,$selectQ);
    $changes_finacial_data = mysqli_fetch_assoc($changes_finacial);
    $_SESSION['auth']['financial'] = $changes_finacial_data['id'];
}
header('location:'.$_SERVER['HTTP_REFERER']);exit;

?>