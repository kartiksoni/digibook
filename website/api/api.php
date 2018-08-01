<?php 
include("../include/config.php"); 

$action = $_REQUEST['action'];
switch ($action) {
	case 'get_trial_data':
		$data_trial = array();
		$get_trial_data = "SELECT * FROM `users`";
		$get_trial_data = mysqli_query($conn,$get_trial_data);
		while($row = mysqli_fetch_assoc($get_trial_data)){
			$data_trial['data'][] = $row;
		}
		$data_trial['message'] = "sucessfully get";
		$data_trial['action'] = "get_trial_data";
		$data_trial['count'] = mysqli_num_rows($get_trial_data);
		echo json_encode($data_trial);
		break;
	
	default:
		echo"pls enter action name right";
		break;
}
?>
