<?php

    $owner_id = (isset($_SESSION['auth']['owner_id'])) ? $_SESSION['auth']['owner_id'] : '';
    $admin_id = (isset($_SESSION['auth']['admin_id'])) ? $_SESSION['auth']['admin_id'] : '';
    $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
    $financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';
    
    // added by gautam makwana 30-01-19 for get cash payment/receipt voucher no
    function getCashTransactionVoucherNo($type = null){
        global $conn;
        global $pharmacy_id;
        global $financial_id;
        $voucher_no = '';
        $voucher_prifix = '';
        
        $pefixQ = "SELECT id, cash_payment, cash_receipt FROM `sale_pefix` WHERE pharmacy_id='".$pharmacy_id."'";
        $pefixR = mysqli_query($conn, $pefixQ);
        if($pefixR && mysqli_num_rows($pefixR) > 0){
            $pefixRow = mysqli_fetch_array($pefixR);
            if($type == 'payment'){
                $voucher_prifix = (isset($pefixRow['cash_payment'])) ? $pefixRow['cash_payment'] : '';
            }else{
                $voucher_prifix = (isset($pefixRow['cash_receipt'])) ? $pefixRow['cash_receipt'] : '';
            }
        }
        
        $query = "SELECT voucher_no FROM cash_transaction WHERE pharmacy_id = '".$pharmacy_id."' AND financial_id = '".$financial_id."'";
        if($type == 'payment'){
            $query .= " AND payment_type = 'payment'";
        }else{
            $query .= " AND payment_type = 'receipt'";
        }
        $query .= " ORDER BY id DESC LIMIT 1";
        $res = mysqli_query($conn, $query);
        if($res){
          $count = mysqli_num_rows($res);
            if($count !== '' && $count !== 0){
              $row = mysqli_fetch_array($res);
              $voucher_no = (isset($row['voucher_no'])) ? $row['voucher_no'] : '';
              if($voucher_no != ''){
                    $getno = preg_replace('/[^0-9]/', '', $voucher_no);
                    $finalno = sprintf("%04d", ($getno+1));
                    $voucher_no = $voucher_prifix.''.$finalno;
              }else{
                    $voucher_no = sprintf("%04d", 1);
                    $voucher_no = $voucher_prifix.''.$voucher_no;
              }
            }else{
              $voucher_no = sprintf("%04d", 1);
              $voucher_no = $voucher_prifix.''.$voucher_no;
            }
        }
    
        return $voucher_no;
    }
    
    // added by gautam makwana 30-01-19 for get bank payment/receipt voucher no
    function getBankTransactionVoucherNo($type = null){
        global $conn;
        global $pharmacy_id;
        global $financial_id;
        $voucher_no = '';
        $voucher_prifix = '';
        
        $pefixQ = "SELECT id, bank_payment, bank_receipt FROM `sale_pefix` WHERE pharmacy_id='".$pharmacy_id."'";
        $pefixR = mysqli_query($conn, $pefixQ);
        if($pefixR && mysqli_num_rows($pefixR) > 0){
            $pefixRow = mysqli_fetch_array($pefixR);
            if($type == 'payment'){
                $voucher_prifix = (isset($pefixRow['bank_payment'])) ? $pefixRow['bank_payment'] : '';
            }else{
                $voucher_prifix = (isset($pefixRow['bank_receipt'])) ? $pefixRow['bank_receipt'] : '';
            }
        }
        
        $query = "SELECT voucher_no FROM bank_transaction WHERE pharmacy_id = '".$pharmacy_id."' AND financial_id = '".$financial_id."'";
        if($type == 'payment'){
            $query .= " AND payment_type = 'payment'";
        }else{
            $query .= " AND payment_type = 'receipt'";
        }
        $query .= " ORDER BY id DESC LIMIT 1";
        $res = mysqli_query($conn, $query);
        if($res){
          $count = mysqli_num_rows($res);
            if($count !== '' && $count !== 0){
              $row = mysqli_fetch_array($res);
              $voucher_no = (isset($row['voucher_no'])) ? $row['voucher_no'] : '';
              if($voucher_no != ''){
                    $getno = preg_replace('/[^0-9]/', '', $voucher_no);
                    $finalno = sprintf("%04d", ($getno+1));
                    $voucher_no = $voucher_prifix.''.$finalno;
              }else{
                    $voucher_no = sprintf("%04d", 1);
                    $voucher_no = $voucher_prifix.''.$voucher_no;
              }
            }else{
              $voucher_no = sprintf("%04d", 1);
              $voucher_no = $voucher_prifix.''.$voucher_no;
            }
        }
    
        return $voucher_no;
    }
    
    // added by gautam makwana 30-01-19 for get journal voucher no
    function getJournalVoucherNo(){
        global $conn;
        global $pharmacy_id;
        global $financial_id;
        $voucher_no = '';
        $voucher_prifix = '';
        
        $pefixQ = "SELECT id, journal_voucher FROM `sale_pefix` WHERE pharmacy_id='".$pharmacy_id."'";
        $pefixR = mysqli_query($conn, $pefixQ);
        if($pefixR && mysqli_num_rows($pefixR) > 0){
            $pefixRow = mysqli_fetch_array($pefixR);
            $voucher_prifix = (isset($pefixRow['journal_voucher'])) ? $pefixRow['journal_voucher'] : '';
        }
        
        $query = "SELECT voucher_no FROM journal_vouchar WHERE pharmacy_id = '".$pharmacy_id."' AND financial_id = '".$financial_id."' ORDER BY id DESC LIMIT 1";
        $res = mysqli_query($conn, $query);
        if($res){
          $count = mysqli_num_rows($res);
            if($count !== '' && $count !== 0){
              $row = mysqli_fetch_array($res);
              $voucher_no = (isset($row['voucher_no'])) ? $row['voucher_no'] : '';
              if($voucher_no != ''){
                    $getno = preg_replace('/[^0-9]/', '', $voucher_no);
                    $finalno = sprintf("%04d", ($getno+1));
                    $voucher_no = $voucher_prifix.''.$finalno;
              }else{
                    $voucher_no = sprintf("%04d", 1);
                    $voucher_no = $voucher_prifix.''.$voucher_no;
              }
            }else{
              $voucher_no = sprintf("%04d", 1);
              $voucher_no = $voucher_prifix.''.$voucher_no;
            }
        }
    
        return $voucher_no;
    }
    
    // added by gautam makwana 30-01-19 for get bank transfer voucher no
    function getBankTransferVoucherNo(){
        global $conn;
        global $pharmacy_id;
        global $financial_id;
        $voucher_no = '';
        $voucher_prifix = '';
        
        $pefixQ = "SELECT id, bank_transfer FROM `sale_pefix` WHERE pharmacy_id='".$pharmacy_id."'";
        $pefixR = mysqli_query($conn, $pefixQ);
        if($pefixR && mysqli_num_rows($pefixR) > 0){
            $pefixRow = mysqli_fetch_array($pefixR);
            $voucher_prifix = (isset($pefixRow['bank_transfer'])) ? $pefixRow['bank_transfer'] : '';
        }
        
        $query = "SELECT voucher_no FROM bank_transfer WHERE pharmacy_id = '".$pharmacy_id."' AND financial_id = '".$financial_id."' ORDER BY id DESC LIMIT 1";
        $res = mysqli_query($conn, $query);
        if($res){
          $count = mysqli_num_rows($res);
            if($count !== '' && $count !== 0){
              $row = mysqli_fetch_array($res);
              $voucher_no = (isset($row['voucher_no'])) ? $row['voucher_no'] : '';
              if($voucher_no != ''){
                    $getno = preg_replace('/[^0-9]/', '', $voucher_no);
                    $finalno = sprintf("%04d", ($getno+1));
                    $voucher_no = $voucher_prifix.''.$finalno;
              }else{
                    $voucher_no = sprintf("%04d", 1);
                    $voucher_no = $voucher_prifix.''.$voucher_no;
              }
            }else{
              $voucher_no = sprintf("%04d", 1);
              $voucher_no = $voucher_prifix.''.$voucher_no;
            }
        }
    
        return $voucher_no;
    }
?>