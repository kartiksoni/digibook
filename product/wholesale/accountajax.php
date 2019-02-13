<?php
include('include/config.php');

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


if($_REQUEST['action'] == "getcash"){
  $cash = (isset($_REQUEST['cash'])) ? $_REQUEST['cash'] : '';

  if($cash == 'cash_payment'){
    //function getcashpaymentno(){
      //global $conn;
      $voucher_no = '';

     $voucherqry = "SELECT voucher_no FROM accounting_cash_management WHERE payment_type = 'cash_payment' ORDER BY id DESC LIMIT 1";
      $voucherrun = mysqli_query($conn, $voucherqry);
      if($voucherrun){
        $count = mysqli_num_rows($voucherrun);
        if($count !== '' && $count !== 0){
          $row = mysqli_fetch_assoc($voucherrun);
          $voucherno = (isset($row['voucher_no'])) ? $row['voucher_no'] : '';

          if($voucherno != ''){
            $vouchernoarr = explode('-',$voucherno);
            $voucherno = $vouchernoarr[1];
            $voucherno = $voucherno + 1;
            $voucherno = sprintf("%05d", $voucherno);
            $voucher_no = 'CP-'.$voucherno;
          }
        }else{
          $voucherno = sprintf("%05d", 1);
          $voucher_no = 'CP-'.$voucherno;
        }
      }
      echo $voucher_no;
      exit;
    }

  if($cash == "cash_receipt"){
    $voucher_no = '';

    $voucherqry = "SELECT voucher_no FROM accounting_cash_management WHERE payment_type = 'cash_receipt' ORDER BY id DESC LIMIT 1";
     $voucherrun = mysqli_query($conn, $voucherqry);
     if($voucherrun){
       $count = mysqli_num_rows($voucherrun);
       if($count !== '' && $count !== 0){
         $row = mysqli_fetch_assoc($voucherrun);
         $voucherno = (isset($row['voucher_no'])) ? $row['voucher_no'] : '';

         if($voucherno != ''){
           $vouchernoarr = explode('-',$voucherno);
           $voucherno = $vouchernoarr[1];
           $voucherno = $voucherno + 1;
           $voucherno = sprintf("%05d", $voucherno);
           $voucher_no = 'CR-'.$voucherno;
         }
       }else{
         $voucherno = sprintf("%05d", 1);
         $voucher_no = 'CR-'.$voucherno;
       }
     }
     echo $voucher_no;
     exit;
  }
  
  }
//}

if($_REQUEST['action'] == "getstate"){
    $state = (isset($_REQUEST['state'])) ? $_REQUEST['state'] : '';
    
    $stateqry = "SELECT state FROM  ledger_master WHERE id = '".$state."'";
    $staterun = mysqli_query($conn, $stateqry);
    $statedata = mysqli_fetch_assoc($staterun);
    $vendorstate = $statedata['state'];
    
    if($vendorstate != ''){
    $state = "SELECT state_code_gst FROM own_states WHERE id = '".$vendorstate."'";
    $run = mysqli_query($conn, $state);
    $data = mysqli_fetch_assoc($run);
    $stategst = $data['state_code_gst'];
    echo $stategst;exit;
}
}
?>