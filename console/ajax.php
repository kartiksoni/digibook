<?php 
include("include/config.php");
if($_REQUEST['action'] == "updatestatus"){
      $table = (isset($_REQUEST['table'])) ? $_REQUEST['table'] : '';
      $status = (isset($_REQUEST['status'])) ? $_REQUEST['status'] : '';
      $id = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : '';
    
      if($table != '' && $status != '' && $id != ''){
        $qry = "UPDATE ".$table." SET status = '".$status."' WHERE id = '".$id."'";
    
        $res = mysqli_query($conn, $qry);
        if($res){
          if($status == 0){
            $result = array('status' => true, 'message' => 'Status Deactive Success!', 'result' => '');
          }else{
            $result = array('status' => true, 'message' => 'Status Active success!', 'result' => '');
          }
        }else{
          $result = array('status' => false, 'message' => 'Status Update Fail! Try Again.', 'result' => '');
        }
      }else{
        $result = array('status' => false, 'message' => 'Somthing Want Wrong! Try Again.', 'result' => '');
      }
      echo json_encode($result);
      exit;
    }
    
    if($_REQUEST['action'] == "getCountryByState"){
      $country_id = (isset($_REQUEST['country_id'])) ? $_REQUEST['country_id'] : '';
    
      if($country_id != ''){
        $query = 'SELECT id, name FROM state WHERE country_id = '.$country_id.' AND status=1 order by name';
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