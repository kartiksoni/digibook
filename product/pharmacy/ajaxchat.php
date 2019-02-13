<?php include('include/usertypecheck.php');?>
<?php 
	$owner_id = (isset($_SESSION['auth']['owner_id'])) ? $_SESSION['auth']['owner_id'] : '';
  	$admin_id = (isset($_SESSION['auth']['admin_id'])) ? $_SESSION['auth']['admin_id'] : '';
  	$pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
  	$financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';
?>


<?php 

	/*---------------------------CHAT AJAX START [AUTHOR : GAUTAM MAKWANA]-----------------------------*/

	if($_REQUEST['action'] == 'sendMessage'){
		$from = (isset($_SESSION['auth']['id'])) ? $_SESSION['auth']['id'] : '';
		$to = (isset($_REQUEST['to'])) ? $_REQUEST['to'] : '';
		$msg = (isset($_REQUEST['msg'])) ? $_REQUEST['msg'] : '';
		$file_name = (isset($_REQUEST['file_name'])) ? $_REQUEST['file_name'] : '';
		$is_group = (isset($_REQUEST['is_group']) && $_REQUEST['is_group'] != '') ? $_REQUEST['is_group'] : 0;
		$file = (isset($_REQUEST['file']) && $_REQUEST['file'] != '') ? $_REQUEST['file'] : 0;

		if($from != '' && $to != '' && ($msg != '' || $file_name != '')){
			$query = "INSERT INTO userchat SET pharmacy_id = '".$pharmacy_id."', fromid = '".$from."', toid = '".$to."', text = '".$msg."', file_name = '".$file_name."', file = '".$file."', groups = '".$is_group."', created = '".date('Y-m-d H:i:s')."'";
			$res = mysqli_query($webconn, $query);
			if($res){
				$lastid = mysqli_insert_id($webconn);
				/*---------------READ MESSAGE ENTRY-----------*/
				if($is_group == 0){
					$readQ = "INSERT INTO readmessage SET msg_id = '".$lastid."', send_by = '".$from."', user_id = '".$to."', flag = 0, created = '".date('Y:m:d H:i:s')."'";
					mysqli_query($webconn, $readQ);
				}else{
					$getGroupuserQ = "SELECT id, user_id FROM groupmembers WHERE group_id = '".$to."' AND user_id != '".$from."'";
					$getGroupuserR = mysqli_query($webconn, $getGroupuserQ);
					if($getGroupuserR && mysqli_num_rows($getGroupuserR) > 0){
						while ($row = mysqli_fetch_assoc($getGroupuserR)) {
							$readQ = "INSERT INTO readmessage SET msg_id = '".$lastid."', send_by = '".$to."', user_id = '".$row['user_id']."', flag = 0, created = '".date('Y:m:d H:i:s')."'";
							mysqli_query($webconn, $readQ);
						}
					}
				}
				/*---------------READ MESSAGE ENTRY-----------*/
				$result = array('status' => true, 'message' => 'Message send succfully.', 'result' => $lastid);
			}else{
				$result = array('status' => false, 'message' => 'Message Sand Fail! Try again.', 'result' => '');
			}
		}else{
			$result = array('status' => false, 'message' => 'Somthing Want Wrong! Try again.', 'result' => '');
		}
      	
      	echo json_encode($result);
      	exit;
    }

    if($_REQUEST['action'] == 'getAllMessage'){
		$from = (isset($_SESSION['auth']['id'])) ? $_SESSION['auth']['id'] : '';
		$to = (isset($_REQUEST['to'])) ? $_REQUEST['to'] : '';
		$is_group = (isset($_REQUEST['is_group'])) ? $_REQUEST['is_group'] : '';

		if($from != '' && $to != ''){
			$data = [];
			$lastClearChatId = getLastClearChat($to);
			$query = "SELECT cht.id, cht.fromid, cht.toid, cht.text, cht.file_name, cht.file,DATE_FORMAT(cht.created, '%d/%m/%Y %h:%i') AS msgdate, usr.name as fromname FROM userchat cht INNER JOIN users usr ON cht.fromid = usr.id WHERE cht.id > '".$lastClearChatId."' ";
			if($is_group == 0){
				$query .= "AND cht.groups = 0 AND ((cht.fromid = '".$from."' AND cht.toid = '".$to."') OR (cht.fromid = '".$to."' AND cht.toid = '".$from."')) ";
			}else{
				$query .= "AND cht.toid = '".$to."' AND cht.groups = 1 ";
			}
			
			$query .= "ORDER BY cht.id";
			$res = mysqli_query($webconn, $query);
			if($res && mysqli_num_rows($res) > 0){
				while ($row = mysqli_fetch_assoc($res)) {
				    $row['text'] = (isset($row['text']) && $row['text'] != '') ? $row['text'] : '';
				    $row['file_name'] = (isset($row['file_name']) && $row['file_name'] != '') ? $row['file_name'] : '';
					if($row['file'] == 1){
						$row['url'] = (file_exists('../attechment/'.$row['fromid'].'/'.$row['file_name'])) ? '../attechment/'.$row['fromid'].'/'.$row['file_name'] : 'javascript:void(0);';
					}
					$data[] = $row;
				}

				/*---MARK AS A READ MESSAGE START--*/
					mysqli_query($webconn, "UPDATE readmessage SET flag = 1 WHERE send_by = '".$to."' AND user_id = '".$from."'");
				/*---MARK AS A READ MESSAGE END---*/
			}

			$unread = getUserUnreadCounter();

			if(isset($data) && !empty($data)){
				$result = array('status' => true, 'message' => 'Data found success.', 'result' => $data, 'unread' => $unread);
			}else{
				$result = array('status' => false, 'message' => 'Data not found!', 'result' => '', 'unread' => $unread);	
			}
		}else{
			$result = array('status' => false, 'message' => 'Somthing Want Wrong! Try again.', 'result' => '');
		}
      	
      	echo json_encode($result);
      	exit;
    }

    if($_REQUEST['action'] == 'getLiveMessage'){
		$from = (isset($_SESSION['auth']['id'])) ? $_SESSION['auth']['id'] : '';
		$to = (isset($_REQUEST['to'])) ? $_REQUEST['to'] : '';
		$length = (isset($_REQUEST['length']) && $_REQUEST['length'] != '') ? $_REQUEST['length'] : 0;
		$is_group = (isset($_REQUEST['is_group'])) ? $_REQUEST['is_group'] : '';

		if($from != '' && $to != '' && $length >= 0){
			$data = [];
			$lastClearChatId = getLastClearChat($to);
			$query = "SELECT cht.id, cht.fromid, cht.toid, cht.text, cht.file_name, cht.file, DATE_FORMAT(cht.created, '%d/%m/%Y %h:%i') AS msgdate, usr.name as fromname FROM userchat cht INNER JOIN users usr ON cht.fromid = usr.id WHERE cht.id > '".$lastClearChatId."' ";
			if($is_group == 0){
				$query .= "AND cht.groups = 0 AND ((cht.fromid = '".$from."' AND cht.toid = '".$to."') OR (cht.fromid = '".$to."' AND cht.toid = '".$from."')) ";
			}else{
				$query .= "AND cht.toid = '".$to."' AND cht.groups = 1 ";
			}
			$query .= "ORDER BY cht.id LIMIT 100 OFFSET ".$length;
			
			$res = mysqli_query($webconn, $query);
			if($res && mysqli_num_rows($res) > 0){
				while ($row = mysqli_fetch_assoc($res)) {
					if($row['fromid'] != $from){
					    $row['text'] = (isset($row['text']) && $row['text'] != '') ? $row['text'] : '';
					    $row['file_name'] = (isset($row['file_name']) && $row['file_name'] != '') ? $row['file_name'] : '';
						if($row['file'] == 1){
							$row['url'] = (file_exists('../attechment/'.$row['fromid'].'/'.$row['file_name'])) ? '../attechment/'.$row['fromid'].'/'.$row['file_name'] : 'javascript:void(0);';
						}
						$data[] = $row;

						/*---MARK AS A READ MESSAGE START--*/
						mysqli_query($webconn, "UPDATE readmessage SET flag = 1 WHERE send_by = '".$to."' AND user_id = '".$from."' AND msg_id = '".$row['id']."'");
						/*---MARK AS A READ MESSAGE END---*/
					}
				}
			}

			$unread = getUserUnreadCounter();

			if(isset($data) && !empty($data)){
				$result = array('status' => true, 'message' => 'Data found success.', 'result' => $data, 'unread' => $unread);
			}else{
				$result = array('status' => false, 'message' => 'Data not found!', 'result' => '', 'unread' => $unread);	
			}
		}else{
			$result = array('status' => false, 'message' => 'Data not found!', 'result' => '');
		}
      	
      	echo json_encode($result);
      	exit;
    }

    if($_REQUEST['action'] == 'addNewGroup'){
    	$data = [];
      	parse_str($_REQUEST['data'], $data);

      	if(!empty($data) && (isset($data['name']) && $data['name'] != '') && (isset($data['user']) && !empty($data['user']))){
      		$query = "INSERT INTO groups SET pharmacy_id = '".$pharmacy_id."', name = '".$data['name']."', status = 1, created = '".date('Y-m-d H:i:s')."', createdby = '".$_SESSION['auth']['id']."'";
      		$res = mysqli_query($webconn, $query);
      		if($res){
      			$lastid = mysqli_insert_id($webconn);
      			foreach ($data['user'] as $key => $value) {
      				$subquery = "INSERT INTO groupmembers SET group_id = '".$lastid."', user_id = '".$value."'";
      				mysqli_query($webconn, $subquery);
      			}
      			$thirdquery = "INSERT INTO groupmembers SET group_id = '".$lastid."', user_id = '".$_SESSION['auth']['id']."'";
      			mysqli_query($webconn, $thirdquery);

      			$result = array('status' => true, 'message' => 'Group added successfully.', 'result' => $lastid);
      		}else{
      			$result = array('status' => false, 'message' => 'Group added fail! Try again.', 'result' => '');
      		}
		}else{
			$result = array('status' => false, 'message' => 'Group added fail! Try again.', 'result' => '');
		}
		echo json_encode($result);
      	exit;
    }

    if($_REQUEST['action'] == 'clearChat'){
    	$from = (isset($_SESSION['auth']['id'])) ? $_SESSION['auth']['id'] : '';
		$to = (isset($_REQUEST['to'])) ? $_REQUEST['to'] : '';
		$is_group = (isset($_REQUEST['is_group'])) ? $_REQUEST['is_group'] : '';

		if($from != '' && $to){
			if($is_group == 0){
				$queryForLastid = "SELECT id FROM userchat WHERE ((fromid = '".$from."' AND toid = '".$to."') OR (fromid = '".$to."' AND toid = '".$from."')) ORDER BY id DESC LIMIT 1";
			}else{
				$queryForLastid = "SELECT id FROM userchat WHERE toid = '".$to."' ORDER BY id DESC LIMIT 1";
			}
			
			$resForLastid = mysqli_query($webconn, $queryForLastid);
			if($resForLastid && mysqli_num_rows($resForLastid) > 0){
				$resForLastidRow = mysqli_fetch_assoc($resForLastid);

				$existQ = "SELECT id FROM clearchat WHERE fromid = '".$from."' AND toid = '".$to."'";
				$existR = mysqli_query($webconn, $existQ);
				if($existR && mysqli_num_rows($existR) > 0){
					$row = mysqli_fetch_assoc($existR);
					$query = "UPDATE clearchat SET last_time = '".date('Y-m-d H:i:s')."', last_id = '".$resForLastidRow['id']."' WHERE id = '".$row['id']."'";
				}else{
					$query = "INSERT INTO clearchat SET fromid = '".$from."', toid = '".$to."', last_time = '".date('Y-m-d H:i:s')."', last_id = '".$resForLastidRow['id']."'";
				}
				$result = mysqli_query($webconn, $query);
				if($result){
					$result = array('status' => true, 'message' => 'Chat clear successfully.', 'result' => '');
				}else{
					$result = array('status' => false, 'message' => 'Chat clear fail! Try again.', 'result' => '');
				}
			}else{
				$result = array('status' => true, 'message' => 'Chat clear successfully.', 'result' => '');
			}
		}else{
			$result = array('status' => false, 'message' => 'Chat clear fail! Try again.', 'result' => '');
		}
		echo json_encode($result);
      	exit;
    }

    
    if($_REQUEST['action'] == 'removeAttechment'){
    	$file_name = (isset($_REQUEST['name'])) ? $_REQUEST['name'] : '';
    	$uid = (isset($_SESSION['auth']['id'])) ? $_SESSION['auth']['id'] : '';
    	if($file_name != '' && $uid != ''){
    		if(file_exists('../attechment/'.$uid.'/'.$file_name)){
    			if (unlink('../attechment/'.$uid.'/'.$file_name)) {   
			        $result = array('status' => true, 'message' => 'File remove successfully.', 'result' => $file_name);
			    } else {
			        $result = array('status' => false, 'message' => 'File remove fail!', 'result' => $file_name);
			    }  
    		}else{
    			$result = array('status' => true, 'message' => 'File does not exist!', 'result' => $file_name);
    		}
    	}else{
    		$result = array('status' => false, 'message' => 'File name not found!', 'result' => '');
    	}
    	echo json_encode($result);
      	exit;
    }
    
    if($_REQUEST['action'] == 'isOnline'){
    	$data = [];

    	$searchuser = (isset($_SESSION['auth']['user_type']) && $_SESSION['auth']['user_type'] == 'owner') ? $_SESSION['auth']['id'] : $_SESSION['auth']['owner_id'];
    	$query = "SELECT id, is_online FROM users WHERE (owner_id = '".$searchuser."' OR id = '".$searchuser."') AND id != '".$_SESSION['auth']['id']."' ORDER BY is_online DESC";
    	$res = mysqli_query($webconn, $query);
    	if($res && mysqli_num_rows($res) > 0){
    		while ($row = mysqli_fetch_assoc($res)) {
    			$data[] = $row;
    		}
    	}
    	if(isset($data) && !empty($data)){
    		$result = array('status' => true, 'message' => 'users found success', 'result' => $data);
    	}else{
    		$result = array('status' => false, 'message' => 'users not found!', 'result' => '');
    	}
    	echo json_encode($result);
      	exit;
    }

    function getUserUnreadCounter(){
    	global $webconn;
    	$data = [];

    	$uid = (isset($_SESSION['auth']['id'])) ? $_SESSION['auth']['id'] : '';

    	$query = "SELECT COUNT(cht.id) as count, cht.groups, rd.send_by FROM `userchat` cht INNER JOIN readmessage rd ON cht.id = rd.msg_id WHERE rd.user_id = '".$uid."' AND rd.flag = 0 GROUP BY rd.send_by";
    	$res = mysqli_query($webconn, $query);
    	if($res && mysqli_num_rows($res) > 0){
    		while ($row = mysqli_fetch_assoc($res)) {
    			$data[] = $row;
    		}
    	}
    	return $data;
    }

    function getLastClearChat($to = null){
    	global $webconn;
    	$uid = (isset($_SESSION['auth']['id'])) ? $_SESSION['auth']['id'] : '';

	    	$query = "SELECT id, last_time, last_id FROM clearchat WHERE fromid = '".$uid."' AND toid = '".$to."' ORDER BY id DESC LIMIT 1";
	    	$res = mysqli_query($webconn, $query);
	    	if($res && mysqli_num_rows($res)){
	    		$row = mysqli_fetch_assoc($res);
	    		$last_id = $row['last_id'];
	    	}else{
	    		$last_id = '';
	    	}
	    return $last_id;
    }
	/*---------------------------CHAT AJAX END [AUTHOR : GAUTAM MAKWANA]-----------------------------*/
?>