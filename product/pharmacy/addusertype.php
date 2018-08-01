<?php 
	include('include/config.php');

	if($_POST){
		$type = (isset($_POST['type'])) ? $_POST['type'] : '';
		$authid = $_SESSION['auth']['id'];
		if($type != '' && $authid != ''){
			$qry = "UPDATE `users` SET `type` = '".$type."' WHERE id = '".$authid."' ";
			$res = mysqli_query($conn, $qry);
			if($res){
				$_SESSION['auth']['type'] = $type;
				$_SESSION['msg']['success'] = "Type update successfully.";
				header("Location:dashboard.php");exit;
			}else{
				header("Location:dashboard.php");exit;	
			}
		}else{
			header("Location:dashboard.php");exit;
		}
	}else{
		header("Location:dashboard.php");exit;
	}
?>