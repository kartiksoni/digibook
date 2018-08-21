<?php //include('include/usertypecheck.php');

function runningbalance(){
    global $conn;
    $user_id = $_SESSION['auth']['id'];
    $runningqry = "select sum(amount) - (SELECT SUM(amount) from accounting_cash_management where credit_debit = 'debit' and createdby = '".$user_id."') AS amount from accounting_cash_management where credit_debit = 'credit' and createdby = '".$user_id."'";
    $runningrun = mysqli_query($conn, $runningqry);
    $runningdata = mysqli_fetch_array($runningrun);
    
    /*$qry = "select sum(amount) AS amount, credit_debit from accounting_cash_management where credit_debit = 'credit' and createdby = '".$user_id."'";
    $run = mysqli_query($conn, $qry);
    $data = mysqli_fetch_assoc($run);
    
    if($data['credit_debit'] == "credit"){
      $sum = $data['amount'];
    }
    echo $sum;

    $qry1 = "'".$sum."' - select sum(amount) AS amount, credit_debit from accounting_cash_management where credit_debit = 'debit' and createdby = '".$user_id."'";
    $run1 = mysqli_query($conn, $qry1);
    $data1 = mysqli_fetch_assoc($run1);

    if($data1['credit_debit'] == "debit"){
      $total = $sum - $data1['amount']; 
    }
    
    echo $total; */

    return $runningdata['amount'];
    /*if($runningdata['credit_debit'] == "credit"){
    $sum = $runningdata['amount'];
    echo $sum;
    } */

    /*if($runningdata['credit_debit'] == "debit"){
    $user_id = $_SESSION['auth']['id'];
    $runningqry = "select sum(amount), credit_debit from accounting_cash_management where credit_debit = 'debit' and createdby = '".$user_id."'";
    $runningrun = mysqli_query($conn, $runningqry);
    $runningdata = mysqli_fetch_assoc($runningrun);

    $total = $sum - $runningdata['amount']; 
    } */

    /*while($runningdata = mysqli_fetch_assoc($runningrun)){
        $sum = $runningdata['amount'];   
    } */
    
    
    //$sum = $runningdata['amount'];

    /*if($runningdata['credit_debit'] == 'credit'){
        $totalsum = $sum + $sum;
        echo $totalsum;
    }

    $subrunningqry = "SELECT * FROM `accounting_cash_management` WHERE credit_debit = 'debit' AND createdby = '$user_id'";
    $subrunningrun = mysqli_query($conn, $subrunningqry);
    $subrunningdata = mysqli_fetch_assoc($subrunningrun);

    $sub = $subrunningdata['amount'];
    
    if($subrunningdata['credit_debit'] == 'debit'){
        $totalsub = $totalsum - $sub;
        echo $totalsub;
    }  */
    exit;
}

function getcashpaymentno(){
    global $conn;
    $voucher_no = '';

    $voucherqry = "SELECT voucher_no FROM accounting_cash_management WHERE payment_type = 'cashpayment' ORDER BY id DESC LIMIT 1";
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