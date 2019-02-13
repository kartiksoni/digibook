<?php
	session_start();

	if(isset($_FILES) && !empty($_FILES)){
		$userid = $_SESSION['auth']['id'];
		if (!file_exists('../attechment')) {
		    mkdir('../attechment', 0777, true);
		}
		if (!file_exists('../attechment/'.$userid)) {
		    mkdir('../attechment/'.$userid, 0777, true);
		}

		$filename = $_FILES['file']['name'];
		$filename = preg_replace('/\\.[^.\\s]{3,4}$/', '', $filename);
		$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

		$pathname = $filename . mt_rand(100000,999999) . "." . $ext;
		$target_path = '../attechment/'.$userid.'/'.$pathname;

		if(move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {  
		    $result = array('status' => true, 'message' => 'File upload successfully!', 'result' => $pathname);
		} else{  
		    $result = array('status' => false, 'message' => 'File upload fail!', 'result' => '');
		} 
	}else{
		$result = array('status' => false, 'message' => 'File upload fail!', 'result' => '');
	}
	echo json_encode($result);
    exit;
?>