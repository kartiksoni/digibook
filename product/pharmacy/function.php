<?php //include('include/usertypecheck.php');

function runningbalance(){
    global $conn;
    $user_id = $_SESSION['auth']['id'];
    $runningqry = "select sum(amount) - (SELECT SUM(amount) from accounting_cash_management where credit_debit = 'debit' and createdby = '".$user_id."') AS amount from accounting_cash_management where credit_debit = 'credit' and createdby = '".$user_id."'";
    $runningrun = mysqli_query($conn, $runningqry);
    $runningdata = mysqli_fetch_array($runningrun);
    
    if($runningdata){
    return $runningdata['amount'];
    }
    else{
      echo Error;
    }
    exit;
}

function getcashpaymentno(){
    global $conn;
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
    return $voucher_no;
  }
?>