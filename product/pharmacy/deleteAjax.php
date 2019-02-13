<?php include('include/config.php');?>
<?php 
  $owner_id = (isset($_SESSION['auth']['owner_id']) && $_SESSION['auth']['owner_id'] != '') ? $_SESSION['auth']['owner_id'] : '';
  $admin_id = (isset($_SESSION['auth']['admin_id']) && $_SESSION['auth']['admin_id'] != '') ? $_SESSION['auth']['admin_id'] : '';
  $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : '';
  $financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';
?>
<?php 

	if($_REQUEST['action'] == "deleteLedger"){
      if(isset($_REQUEST['id']) && $_REQUEST['id'] != ''){
        $delete = deleteLedger($_REQUEST['id']);
      
        
        if(!empty($delete)){
        	if(isset($delete['status']) && $delete['status'] == 0){
                $result = array('status' => false, 'message' => 'Error ! Record not deleted!', 'result' => '');
            }else{
                $result = array('status' => true, 'message' => 'Record deleted!', 'result' => $_REQUEST['id']);
            }
        }else{
        	$result = array('status' => false, 'message' => 'Error ! Record not deleted!', 'result' => '');
        }
      }else{
        $result = array('status' => false, 'message' => 'Error ! Record not deleted!', 'result' => '');
      }
      echo json_encode($result);
      exit;
    }
    
    if($_REQUEST['action'] == "deleteAdmin"){
      if(isset($_REQUEST['id']) && $_REQUEST['id'] != ''){
        $delete = deleteAdmin($_REQUEST['id']);
        if(!empty($delete)){
        	if(isset($delete['status']) && $delete['status'] == 0){
                $result = array('status' => false, 'message' => 'Error ! Record not deleted!', 'result' => '');
            }else{
                $result = array('status' => true, 'message' => 'Record deleted!', 'result' => $_REQUEST['id']);
            }
        }else{
        	$result = array('status' => false, 'message' => 'Error ! Record not deleted!', 'result' => '');
        }
      }else{
        $result = array('status' => false, 'message' => 'Error ! Record not deleted!', 'result' => '');
      }
      echo json_encode($result);
      exit;
    }
    
    if($_REQUEST['action'] == "deleteProduct"){
        if(isset($_REQUEST['id']) && $_REQUEST['id'] != ''){
            $delete = deleteproduct($_REQUEST['id']);
            if(!empty($delete)){
            	if(isset($delete['status']) && $delete['status'] == 0){
                    $result = array('status' => false, 'message' => 'Error ! Record not deleted!', 'result' => '');
                }else{
                    $result = array('status' => true, 'message' => 'Record deleted!', 'result' => $_REQUEST['id']);
                }
            }else{
            	$result = array('status' => false, 'message' => 'Error ! Record not deleted!', 'result' => '');
            }
        }else{
            $result = array('status' => false, 'message' => 'Error ! Record not deleted!', 'result' => '');
        }
        echo json_encode($result);
        exit;
    }
    
    if($_REQUEST['action'] == "deleteJournalVoucher"){
        if(isset($_REQUEST['id']) && $_REQUEST['id'] != ''){
            $deleteQ = "DELETE FROM journal_vouchar WHERE id = '".$_REQUEST['id']."' AND pharmacy_id = '".$pharmacy_id."'";
            $deleteR = mysqli_query($conn, $deleteQ);
            if($deleteR){
              $deleteSubQ = "DELETE FROM journal_vouchar_details WHERE voucher_id = '".$_REQUEST['id']."'";
              $deleteSubR = mysqli_query($conn, $deleteSubQ);
              
              $result = array('status' => true, 'message' => 'Record deleted!', 'result' => $_REQUEST['id']);
            }else{
              $result = array('status' => false, 'message' => 'Error ! Record not deleted!', 'result' => '');
            }
        }else{
            $result = array('status' => false, 'message' => 'Error ! Record not deleted!', 'result' => '');
        }
        echo json_encode($result);
        exit;
    }
    
    if($_REQUEST['action'] == "deleteCashTransaction"){
        if(isset($_REQUEST['id']) && $_REQUEST['id'] != ''){
            $query = "DELETE FROM cash_transaction WHERE pharmacy_id = '".$pharmacy_id."' AND id = '".$_REQUEST['id']."'";
            $res = mysqli_query($conn, $query);
            if($res){
              $result = array('status' => true, 'message' => 'Record deleted!', 'result' => $_REQUEST['id']);
            }else{
              $result = array('status' => false, 'message' => 'Error ! Record not deleted!', 'result' => '');
            }
        }else{
            $result = array('status' => false, 'message' => 'Error ! Record not deleted!', 'result' => '');
        }
        echo json_encode($result);
        exit;
    }

    if($_REQUEST['action'] == "deleteBankTransaction"){
        if(isset($_REQUEST['id']) && $_REQUEST['id'] != ''){
            $query = "DELETE FROM bank_transaction WHERE pharmacy_id = '".$pharmacy_id."' AND id = '".$_REQUEST['id']."'";
            $res = mysqli_query($conn, $query);
            if($res){
              $result = array('status' => true, 'message' => 'Record deleted!', 'result' => $_REQUEST['id']);
            }else{
              $result = array('status' => false, 'message' => 'Error ! Record not deleted!', 'result' => '');
            }
        }else{
            $result = array('status' => false, 'message' => 'Error ! Record not deleted!', 'result' => '');
        }
        echo json_encode($result);
        exit;
    }
    
    if($_REQUEST['action'] == "deleteBankTransfer"){
        if(isset($_REQUEST['id']) && $_REQUEST['id'] != ''){
            $deleteQ = "DELETE FROM bank_transfer WHERE id = '".$_REQUEST['id']."' AND pharmacy_id = '".$pharmacy_id."'";
            $deleteR = mysqli_query($conn, $deleteQ);
            if($deleteR){
              $deleteSubQ = "DELETE FROM bank_transfer_details WHERE voucher_id = '".$_REQUEST['id']."'";
              $deleteSubR = mysqli_query($conn, $deleteSubQ);
              
              $result = array('status' => true, 'message' => 'Record deleted!', 'result' => $_REQUEST['id']);
            }else{
              $result = array('status' => false, 'message' => 'Error ! Record not deleted!', 'result' => '');
            }
        }else{
            $result = array('status' => false, 'message' => 'Error ! Record not deleted!', 'result' => '');
        }
        echo json_encode($result);
        exit;
    }
   
?>