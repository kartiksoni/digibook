<?php
	include('../include/config.php');
	include('../api/function.php');

	if($_POST){
		if(isset($_POST['email']) && $_POST['email'] != ''){
			$queryEmailExist = 'SELECT * FROM users WHERE email = "'.$_POST['email'].'"';
			$resemail = mysqli_query($conn,$queryEmailExist);
			$row = mysqli_num_rows($resemail);
			if($row == 0){
				$name = (isset($_POST['name'])) ? $_POST['name'] : '';
				$company = (isset($_POST['company'])) ? $_POST['company'] : '';
				$city = (isset($_POST['city'])) ? $_POST['city'] : '';
				$state = (isset($_POST['state'])) ? $_POST['state'] : '';
				$email = (isset($_POST['email'])) ? $_POST['email'] : '';
				$mobile = (isset($_POST['mobile'])) ? $_POST['mobile'] : '';
				$generatepassword = mt_rand(100000,999999);
				$password = md5($generatepassword);
				$status = 1;
				$type = '';
				$payment_type = "FREE";
				$startdate = date('Y-m-d');
				$enddate = date('Y-m-d',strtotime('+30 days'));

				$query = "INSERT INTO users SET 
		                                    name = '".$name."',
		                                    company= '".$company."',
		                                    city= '".$city."',
		                                    state = '".$state."',
		                                    email = '".$email."',
		                                    mobile = '".$mobile."',
		                                    password = '".$password."',
		                                    type= '".$type."',
		                                    payment_type= '".$payment_type."',
		                                    status = '".$status."',
		                                    startdate = '".$startdate."',
		                                    enddate = '".$enddate."' ";
		        $result = mysqli_query($conn,$query);

		        if($result){
		        	$data = "<p>Thanks For Subscription Digibook Free Trial</p>";
		        	$data .= "<p>Username : ".$email."</p>";
		        	$data .= "<p>Password : ".$generatepassword."</p>";
		        	$data .= "<a href='http://yamunameditech.com/digibooks-admin/' target='_blank'>Click Here to login.</a>";

		        	$r = smtpmail($email, '', '', 'Thanks for subscription digibook', $data, '', '');
		        	$result = array('status' => true, 'message' => 'Success!', 'result' => '');
		        }else{
		        	$result = array('status' => false, 'message' => 'Somthing Want wrong! Please try again.', 'result' => '');
		        }
		    }else{
		    	$result = array('status' => false, 'message' => 'Email already exist! Please enter another email.', 'result' => '');
		    }
	    }else{
	    	$result = array('status' => false, 'message' => 'Please enter email!', 'result' => '');
	    }
	}else{
		$result = array('status' => false, 'message' => 'Somthing Want wrong! Please try again.', 'result' => '');
	}
	echo json_encode($result);
    exit;


?>