<?php 
include('../include/config.php');
function isValidMd5($md5 ='')
{
    return preg_match('/^[a-f0-9]{32}$/', $md5);
}

$action = $_REQUEST['action'];
switch ($action) {
	case 'add_user':
		$name = $_REQUEST['name'];
		$owner_id = $_REQUEST['owner_id'];
		$pharmacy_id = $_REQUEST['pharmacy_id'];
		$multiple_pharmacy_id = $_REQUEST['multiple_pharmacy_id'];
		$user_type = $_REQUEST['user_type'];
		$user_name = $_REQUEST['user_name'];
		$email = $_REQUEST['email'];
		$mobile = $_REQUEST['mobile'];
		$password = (isset($_REQUEST['password'])) ? $_REQUEST['password'] : '';
		$type = $_REQUEST['type'];
		$status = $_REQUEST['status']; 
		$inQry = "INSERT INTO `users`(`name`, `owner_id`, `pharmacy_id`,`multiple_pharmacy_id`, `user_type`,`username`, `email`, `mobile`, `password`, `type`, `status`) VALUES ('".$name."','".$owner_id."','".$pharmacy_id."','".$multiple_pharmacy_id."','".$user_type."','".$user_name."','".$email."','".$mobile."','".$password."','".$type."','".$status."')";
		$res = mysqli_query($conn, $inQry);

		if($res){
			$status = 1;
		}else{
			$status = 0;
		}
		echo json_encode($status);exit;
	break;

	case 'view_user':
		//$pharmacy_id = $_REQUEST['pharmacy_id'];
		$owner_id = $_REQUEST['owner_id'];
		$selectQry = 'SELECT * FROM `users` WHERE owner_id = "'.$owner_id.'" AND user_type ="admin"';
		$res = mysqli_query($conn, $selectQry);
		$data = array();
		while($row = mysqli_fetch_assoc($res)){
			$data[] = $row;
		}
		echo json_encode($data);exit;
	break;

	case 'edit_user':
		$id = $_REQUEST['id'];
		$selectQry = 'SELECT * FROM `users` WHERE id='.$id.'';
		$res = mysqli_query($conn, $selectQry);
		$row = mysqli_fetch_assoc($res);
		echo json_encode($row);exit;
	break;

	case 'edit_user_data':
		
		$name = $_REQUEST['name'];
		$user_name = $_REQUEST['user_name'];
		$email = $_REQUEST['email'];
		$mobile = $_REQUEST['mobile'];
		$password = (isset($_REQUEST['password'])) ? $_REQUEST['password'] : '';
		if(!isValidMd5($password)){
            $password = md5($password);
        }else{
            $password = $password;
        }
		$status = $_REQUEST['status'];
		$pharmacy_id = $_REQUEST['pharmacy_id'];
		$mutiple_pharmacy_id = $_REQUEST['mutiple_pharmacy_id'];
		$id = $_REQUEST['id'];

		$updateQry = "UPDATE `users` SET `name`='".$name."',`username`='".$user_name."',`email`='".$email."',`mobile`='".$mobile."',`pharmacy_id`='".$pharmacy_id."',`multiple_pharmacy_id`='".$mutiple_pharmacy_id."',`password`='".$password."',`status`='".$status."' WHERE id='".$_GET['id']."'";
  		$updateInsert = mysqli_query($conn,$updateQry);
  		if($updateInsert){
  			$status = 1;
  		}else{
  			$status = 0;
  		}
  		echo json_encode($status);exit;
	break;

	case 'admin_details':
		$admin_id = $_REQUEST['admin_id'];
		$selectQry = 'SELECT * FROM `users` WHERE id='.$admin_id.'';
		$res = mysqli_query($conn, $selectQry);
		$row = mysqli_fetch_assoc($res);
		echo json_encode($row);exit;
	break;

	case 'admin_user_add':
		$name = $_REQUEST['name'];
		$owner_id = $_REQUEST['owner_id'];
		$pharmacy_id = $_REQUEST['pharmacy_id'];
		$admin_id = $_REQUEST['admin_id'];
		$user_type = $_REQUEST['user_type'];
		$user_role = $_REQUEST['user_role'];
		$user_name = $_REQUEST['user_name'];
		$email = $_REQUEST['email'];
		$mobile = $_REQUEST['mobile'];
		$password = (isset($_REQUEST['password'])) ? $_REQUEST['password'] : '';
		$type = $_REQUEST['type'];
		$status = $_REQUEST['status'];

		$inQry = "INSERT INTO `users`(`name`, `owner_id`, `pharmacy_id`, `admin_id`, `user_type`,`user_role`,`username`, `email`, `mobile`, `password`, `type`, `status`) VALUES ('".$name."','".$owner_id."','".$pharmacy_id."','".$admin_id."','".$user_type."','".$user_role."','".$user_name."','".$email."','".$mobile."','".$password."','".$type."','".$status."')";
		$res = mysqli_query($conn, $inQry);

		if($res){
			$status = 1;
		}else{
			$status = 0;
		}
		echo json_encode($status);exit;
	break;

	case 'admin_user_list':
		$admin_id = $_REQUEST['admin_id'];
		$selectQry = 'SELECT * FROM `users` WHERE admin_id='.$admin_id.'';
		$res = mysqli_query($conn, $selectQry);
		$data = array();
		while($row = mysqli_fetch_assoc($res)){
			$data[] = $row;
		}
		echo json_encode($data);exit;
	break;

	case 'edit_admin_user':
		$id = $_REQUEST['id'];
		$selectQry = 'SELECT * FROM `users` WHERE id='.$id.'';
		$res = mysqli_query($conn, $selectQry);
		$row = mysqli_fetch_assoc($res);
		echo json_encode($row);exit;
	break;

	case 'edit_admin_user_data':

		$name = $_REQUEST['name'];
		$user_name = $_REQUEST['user_name'];
		$email = $_REQUEST['email'];
		$mobile = $_REQUEST['mobile'];
		$password = (isset($_REQUEST['password'])) ? $_REQUEST['password'] : '';

		if(!isValidMd5($password)){
            $password = md5($password);
        }else{
            $password = $password;
        }
        $user_role = $_REQUEST['user_role'];
		$status = $_REQUEST['status'];
		$id = $_REQUEST['id'];

		$updateQry = "UPDATE `users` SET `name`='".$name."',`username`='".$user_name."',`email`='".$email."',`mobile`='".$mobile."',`password`='".$password."',`user_role` = '".$user_role."',`status`='".$status."' WHERE id='".$_GET['id']."'";
  		$updateInsert = mysqli_query($conn,$updateQry);
  		if($updateInsert){
  			$status = 1;
  		}else{
  			$status = 0;
  		}
  		echo json_encode($status);exit;
	break;
	
	case 'profile':
	    $id = $_REQUEST['id'];
	    $user_name = $_REQUEST['user_name'];
	    $email = $_REQUEST['email'];
	    $mobile = $_REQUEST['mobile'];
	    $name = $_REQUEST['name'];
	   /* $password = $_REQUEST['password'];
	    if(!isValidMd5($password)){
            $password = md5($password);
        }else{
            $password = $password;
        }*/
        if(empty($name)){
            $updateQry = "UPDATE `users` SET `username`='".$user_name."',`email`='".$email."',`mobile`='".$mobile."' WHERE id='".$id."'";
        }else{
           $updateQry = "UPDATE `users` SET `username`='".$user_name."',`email`='".$email."',`mobile`='".$mobile."',`profile_pic`='".$name."' WHERE id='".$id."'"; 
        }
        
  		$updateInsert = mysqli_query($conn,$updateQry);
  		if($updateInsert){
  			$status = 1;
  		}else{
  			$status = 0;
  		}
  		echo json_encode($status);exit;
	break;
	
	default:
		# code...
		break;
}
?>