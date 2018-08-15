<?php
include('include/usertypecheck.php');

if($_REQUEST['action'] == "getgroup"){
      $group_id = (isset($_REQUEST['group_id'])) ? $_REQUEST['group_id'] : '';
    
      if($group_id != ''){
        $query = 'SELECT id, name FROM `ledger_master` WHERE group_id = '.$group_id.' AND status=1 order by name';
        $result = mysqli_query($conn,$query);
        if($result && mysqli_num_rows($result) > 0){
          $res = [];
            while ($row = mysqli_fetch_array($result)) {
              $arr['id'] = $row['id']; 
              $arr['name'] = $row['name'];
              array_push($res, $arr);
            }
          if(!empty($res)){
            $result = array('status' => true, 'message' => 'Success!', 'result' => $res);
          }else{
            $result = array('status' => false, 'message' => 'Fail!', 'result' => '');
          }
        }else{
          $result = array('status' => false, 'message' => 'Fail!', 'result' => '');
        }
      }else{
        $result = array('status' => false, 'message' => 'Fail!', 'result' => '');
      }
      echo json_encode($result);
      exit;
    }
?>