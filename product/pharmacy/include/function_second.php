<?php 
    // reference the Dompdf namespace
    use \Dompdf\Dompdf;

    $owner_id = (isset($_SESSION['auth']['owner_id'])) ? $_SESSION['auth']['owner_id'] : '';
    $admin_id = (isset($_SESSION['auth']['admin_id'])) ? $_SESSION['auth']['admin_id'] : '';
    $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
    $financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';
    
    /*----THIS FUNCTION IS USED TO GET LAST THREE RATE FOR SALE PRODUCT - GAUTAM MAKWANA - 22-11-2018 - START----*/
    function getLastSaleRateByProduct($product_id = null, $customer_id = null){
        global $conn;
        global $pharmacy_id;
        $data = [];
        
        $query = "SELECT tbd.id, tbd.rate, tb.created FROM tax_billing_details tbd LEFT JOIN tax_billing tb ON tbd.tax_bill_id = tb.id WHERE tb.pharmacy_id = '".$pharmacy_id."' AND product_id = '".$product_id."' AND tb.customer_id = '".$customer_id."' ORDER BY tb.id DESC";
        $res = mysqli_query($conn, $query);
        if($res && mysqli_num_rows($res) > 0){
            while($row = mysqli_fetch_assoc($res)){
                $data[] = $row;
            }
        }
        return $data;
    }
    /*----THIS FUNCTION IS USED TO GET LAST THREE RATE FOR SALE PRODUCT - GAUTAM MAKWANA - 22-11-2018 - END----*/
    
    /*----THIS FUNCTION IS USED TO GET GROUP WISE CLOSING BALANCE TOTAL  - GAUTAM MAKWANA - 26-11-2018 - START----*/
    function getGroupWiseClosingBalance($group_id = null, $from = null, $to = null, $is_financial = 0){
        global $conn;
        global $pharmacy_id;
        global $financial_id;
        
        $data['opening_balance'] = 0;
        $data['running_balance'] = 0;
        $opening_balance = 0;$total_purchase = 0;$total_bill = 0;$total_customer_receipt = 0;$total_vendor_payment = 0;$total_check_payment = 0;$total_check_receipt;$total_cash_receipt = 0;$total_cash_payment = 0;$total_jurnal_debit = 0;$total_jurnal_credit = 0;
        
        if(isset($group_id) && $group_id != ''){
            $query ="SELECT id, name, opening_balance, opening_balance_type FROM ledger_master WHERE status = 1 AND pharmacy_id = '".$pharmacy_id."' AND group_id = '".$group_id."'";
            $res = mysqli_query($conn, $query);
            if($res && mysqli_num_rows($res) > 0){
                // count total opening balance
                while($row = mysqli_fetch_assoc($res)){
                    $opening = (isset($row['opening_balance']) && $row['opening_balance'] != '') ? $row['opening_balance'] : 0;
                    $opening = (isset($row['opening_balance_type']) && $row['opening_balance_type'] == 'DB') ? $opening : -$opening;
                    $opening_balance += $opening;
                }
                
                /*--------------------------------------------CALCULATE TAX BILLING START---------------------------------------*/
                $taxbillingQ = "SELECT SUM(final_amount) as total_bill FROM tax_billing WHERE pharmacy_id = '".$pharmacy_id."' AND bill_type = 'Debit' AND customer_id != 0 AND customer_id IS NOT NULL";
                if(isset($is_financial) && $is_financial == 1){
                    $taxbillingQ .= " AND financial_id = '".$financial_id."'";
                }
                if((isset($from) && $from != '') && (isset($to) && $to != '')){
                  $taxbillingQ .= " AND invoice_date >= '".$from."' AND invoice_date <= '".$to."'";
                }
                $taxbillingR = mysqli_query($conn, $taxbillingQ);
                if($taxbillingR && mysqli_num_rows($taxbillingR) > 0){
                    $taxbillingRow = mysqli_fetch_assoc($taxbillingR);
                    $total_bill = (isset($taxbillingRow['total_bill']) && $taxbillingRow['total_bill'] != '') ? $taxbillingRow['total_bill'] : 0;
                }
                /*--------------------------------------------CALCULATE TAX BILLING END-----------------------------------------*/
                
                /*--------------------------------------------CALCULATE PURCHASE START---------------------------------------*/
                $purchaseQ = "SELECT SUM(total_total) as total_purchase FROM purchase WHERE pharmacy_id = '".$pharmacy_id."' AND purchase_type = 'Debit' AND vendor != 0 AND vendor IS NOT NULL";
                if(isset($is_financial) && $is_financial == 1){
                    $purchaseQ .= " AND financial_id = '".$financial_id."'";
                }
                if((isset($from) && $from != '') && (isset($to) && $to != '')){
                  $purchaseQ .= " AND invoice_date >= '".$from."' AND invoice_date <= '".$to."'";
                }
                $purchaseR = mysqli_query($conn, $purchaseQ);
                if($purchaseR && mysqli_num_rows($purchaseR) > 0){
                    $purchaseRow = mysqli_fetch_assoc($purchaseR);
                    $total_purchase = (isset($purchaseRow['total_purchase']) && $purchaseRow['total_purchase'] != '') ? $purchaseRow['total_purchase'] : 0;
                }
                /*--------------------------------------------CALCULATE PURCHASE END-----------------------------------------*/
                
                /*--------------------------------------------CALCULATE CUSTOMER RECEIPT START---------------------------------------*/
                $cusromerreceiptQ = "SELECT SUM(amount) as total_receipt FROM cash_receipt WHERE pharmacy_id = '".$pharmacy_id."' AND customer != 0 AND customer IS NOT NULL";
                if(isset($is_financial) && $is_financial == 1){
                    $cusromerreceiptQ .= " AND financial_id = '".$financial_id."'";
                }
                if((isset($from) && $from != '') && (isset($to) && $to != '')){
                  $cusromerreceiptQ .= " AND payment_date >= '".$from."' AND payment_date <= '".$to."'";
                }
                $cusromerreceiptR = mysqli_query($conn, $cusromerreceiptQ);
                if($cusromerreceiptR && mysqli_num_rows($cusromerreceiptR) > 0){
                    $cusromerreceiptRow = mysqli_fetch_assoc($cusromerreceiptR);
                    $total_customer_receipt = (isset($cusromerreceiptRow['total_receipt']) && $cusromerreceiptRow['total_receipt'] != '') ? $cusromerreceiptRow['total_receipt'] : 0;
                }
                /*--------------------------------------------CALCULATE CUSTOMER RECEIPT END-----------------------------------------*/
                
                /*--------------------------------------------CALCULATE VENDOR PAYMENT START---------------------------------------*/
                $vendorPaymentQ = "SELECT SUM(amount) as total_payment FROM accounting_vendor_payment WHERE pharmacy_id = '".$pharmacy_id."' AND vendor != 0 AND vendor IS NOT NULL";
                if(isset($is_financial) && $is_financial == 1){
                    $vendorPaymentQ .= " AND financial_id = '".$financial_id."'";
                }
                if((isset($from) && $from != '') && (isset($to) && $to != '')){
                  $vendorPaymentQ .= " AND payment_date >= '".$from."' AND payment_date <= '".$to."'";
                }
                $vendorPaymentR = mysqli_query($conn, $vendorPaymentQ);
                if($vendorPaymentR && mysqli_num_rows($vendorPaymentR) > 0){
                    $vendorPaymentRow = mysqli_fetch_assoc($vendorPaymentR);
                    $total_vendor_payment = (isset($vendorPaymentRow['total_payment']) && $vendorPaymentRow['total_payment'] != '') ? $vendorPaymentRow['total_payment'] : 0;
                }
                /*--------------------------------------------CALCULATE VENDOR PAYMENT END-----------------------------------------*/
                
                /*----------------------------------------------------COUNT CHECK PAYMENT START-------------------------------------------------------*/
                $checkPaymentQ =  "SELECT SUM(amount) as amount FROM accounting_cheque WHERE pharmacy_id = '".$pharmacy_id."' AND voucher_type = 'payment' AND group_id = '".$group_id."' AND perticular != 0 AND perticular IS NOT NULL";
                if(isset($is_financial) && $is_financial == 1){
                    $checkPaymentQ .= " AND financial_id = '".$financial_id."'";
                }
                if((isset($from) && $from != '') && (isset($to) && $to != '')){
                  $checkPaymentQ .= " AND voucher_date >= '".$from."' AND voucher_date <= '".$to."'";
                }
                $checkPaymentR = mysqli_query($conn, $checkPaymentQ);
                $checkPaymentRow = ($checkPaymentR && mysqli_num_rows($checkPaymentR) > 0) ? mysqli_fetch_assoc($checkPaymentR) : '';
                $total_check_payment = (isset($checkPaymentRow['amount']) && $checkPaymentRow['amount'] != '') ? $checkPaymentRow['amount'] : 0;
                
                /*----------------------------------------------------COUNT CHECK PAYMENT END-------------------------------------------------------*/
                
                /*----------------------------------------------------COUNT CHECK RECEIPT START-------------------------------------------------------*/
                $checkReceiptQ =  "SELECT SUM(amount) as amount FROM accounting_cheque WHERE pharmacy_id = '".$pharmacy_id."' AND voucher_type = 'receipt' AND group_id = '".$group_id."' AND perticular != 0 AND perticular IS NOT NULL";
                if(isset($is_financial) && $is_financial == 1){
                    $checkReceiptQ .= " AND financial_id = '".$financial_id."'";
                }
                if((isset($from) && $from != '') && (isset($to) && $to != '')){
                  $checkReceiptQ .= " AND voucher_date >= '".$from."' AND voucher_date <= '".$to."'";
                }
                $checkReceiptR = mysqli_query($conn, $checkReceiptQ);
                $checkReceiptRow = ($checkReceiptR && mysqli_num_rows($checkReceiptR) > 0) ? mysqli_fetch_assoc($checkReceiptR) : '';
                $total_check_receipt = (isset($checkReceiptRow['amount']) && $checkReceiptRow['amount'] != '') ? $checkReceiptRow['amount'] : 0;
                /*----------------------------------------------------COUNT CHECK RECEIPT END-------------------------------------------------------*/
                
                /*----------------------------------------------------COUNT CASH RECEIPT START-------------------------------------------------------*/
                $cashReceiptQ =  "SELECT SUM(amount) as amount FROM accounting_cash_management WHERE pharmacy_id = '".$pharmacy_id."' AND payment_type = 'cash_receipt' AND group_id = '".$group_id."'  AND perticular != 0 AND perticular IS NOT NULL";
                if(isset($is_financial) && $is_financial == 1){
                    $cashReceiptQ .= " AND financial_id = '".$financial_id."'";
                }
                if((isset($from) && $from != '') && (isset($to) && $to != '')){
                  $cashReceiptQ .= " AND voucher_date >= '".$from."' AND voucher_date <= '".$to."'";
                }
                $cashReceiptR = mysqli_query($conn, $cashReceiptQ);
                $cashReceiptRow = ($cashReceiptR && mysqli_num_rows($cashReceiptR) > 0) ? mysqli_fetch_assoc($cashReceiptR) : '';
                $total_cash_receipt = (isset($cashReceiptRow['amount']) && $cashReceiptRow['amount'] != '') ? $cashReceiptRow['amount'] : 0;
    
                /*----------------------------------------------------COUNT CASH RECEIPT END-------------------------------------------------------*/
                
                /*----------------------------------------------------COUNT CASH PAYMENT START-------------------------------------------------------*/
                $cashPaymentQ =  "SELECT SUM(amount) as amount FROM accounting_cash_management WHERE pharmacy_id = '".$pharmacy_id."' AND payment_type = 'cash_payment' AND group_id = '".$group_id."' AND perticular != 0 AND perticular IS NOT NULL";
                if(isset($is_financial) && $is_financial == 1){
                    $cashPaymentQ .= " AND financial_id = '".$financial_id."'";
                }
                if((isset($from) && $from != '') && (isset($to) && $to != '')){
                  $cashPaymentQ .= " AND voucher_date >= '".$from."' AND voucher_date <= '".$to."'";
                }
                $cashPaymentR = mysqli_query($conn, $cashPaymentQ);
                $cashPaymentRow = ($cashPaymentR && mysqli_num_rows($cashPaymentR) > 0) ? mysqli_fetch_assoc($cashPaymentR) : '';
                $total_cash_payment = (isset($cashPaymentRow['amount']) && $cashPaymentRow['amount'] != '') ? $cashPaymentRow['amount'] : 0;
                /*----------------------------------------------------COUNT CASH PAYMENT END-------------------------------------------------------*/
                
                /*-------------------------------------------------COUNT JOURNAL VOUCHER START----------------------------------------------------*/
                $jurnalQ = "SELECT SUM(jvd.debit) as total_debit, SUM(jvd.credit) as total_credit FROM journal_vouchar_details jvd INNER JOIN journal_vouchar jv ON jvd.voucher_id = jv.id WHERE jv.pharmacy_id = '".$pharmacy_id."' AND jvd.type = '".$group_id."' AND jvd.particular != 0 AND jvd.particular IS NOT NULL";
                if(isset($is_financial) && $is_financial == 1){
                    $jurnalQ .= " AND jv.financial_id = '".$financial_id."'";
                }
                if((isset($from) && $from != '') && (isset($to) && $to != '')){
                  $jurnalQ .= " AND jv.voucher_date >= '".$from."' AND jv.voucher_date <= '".$to."'";
                }
                $jurnalR = mysqli_query($conn, $jurnalQ);
                $jurnalRow = ($jurnalR && mysqli_num_rows($jurnalR) > 0) ? mysqli_fetch_assoc($jurnalR) : '';
                $total_jurnal_debit = (isset($jurnalRow['total_debit']) && $jurnalRow['total_debit'] != '') ? $jurnalRow['total_debit'] : 0;
                $total_jurnal_credit = (isset($jurnalRow['total_credit']) && $jurnalRow['total_credit'] != '') ? $jurnalRow['total_credit'] : 0;
                /*-------------------------------------------------COUNT JOURNAL VOUCHER START----------------------------------------------------*/
                
                if(isset($group_id) && $group_id == 10){
                    // CUSTOMER
                    $running_balance = ($opening_balance) + ($total_bill + $total_cash_payment + $total_check_payment + $total_jurnal_debit) - ($total_customer_receipt + $total_check_receipt + $total_cash_receipt +  $total_jurnal_credit);
                }elseif(isset($group_id) && $group_id == 14){
                    // VENDOR
                    $running_balance = ($opening_balance) + ($total_vendor_payment + $total_check_payment + $total_cash_payment + $total_jurnal_debit) - ($total_purchase + $total_check_receipt + $total_cash_receipt + $total_jurnal_credit);
                }else{
                    // OTHER
                    $running_balance = ($opening_balance) + ($total_cash_payment + $total_check_payment + $total_jurnal_debit) - ($total_check_receipt + $total_cash_receipt + $total_jurnal_credit);
                }
                
                $data['opening_balance'] = (isset($opening_balance) && $opening_balance != '') ? $opening_balance : 0;
                $data['running_balance'] = (isset($running_balance) && $running_balance != '') ? $running_balance : 0;
            }
        }
        return $data;
    }
    /*----THIS FUNCTION IS USED TO GET GROUP WISE CLOSING BALANCE TOTAL  - GAUTAM MAKWANA - 26-11-2018 - END------*/
    
    /*----THIS FUNCTION IS USED TO GET TOTAL CLOSING BALANCE  - GAUTAM MAKWANA - 27-11-2018 - START----*/
    function getTotalClosingBalance($from = null, $to = null, $is_financial = 0){
        global $conn;
        global $pharmacy_id;
        global $financial_id;
        
        $data['opening_balance'] = 0;
        $data['running_balance'] = 0;
        $opening_balance = 0;$total_purchase = 0;$total_bill = 0;$total_customer_receipt = 0;$total_vendor_payment = 0;$total_check_payment = 0;$total_check_receipt;$total_cash_receipt = 0;$total_cash_payment = 0;$total_jurnal_debit = 0;$total_jurnal_credit = 0;
        
        
        $query ="SELECT id, name, opening_balance, opening_balance_type FROM ledger_master WHERE status = 1 AND pharmacy_id = '".$pharmacy_id."' AND group_id != 0 AND group_id IS NOT NULL";
        $res = mysqli_query($conn, $query);
        if($res && mysqli_num_rows($res) > 0){
                // count total opening balance
                while($row = mysqli_fetch_assoc($res)){
                    $opening = (isset($row['opening_balance']) && $row['opening_balance'] != '') ? $row['opening_balance'] : 0;
                    $opening = (isset($row['opening_balance_type']) && $row['opening_balance_type'] == 'DB') ? $opening : -$opening;
                    $opening_balance += $opening;
                }
                
                /*--------------------------------------------CALCULATE TAX BILLING START---------------------------------------*/
                $taxbillingQ = "SELECT SUM(final_amount) as total_bill FROM tax_billing WHERE pharmacy_id = '".$pharmacy_id."' AND bill_type = 'Debit' AND customer_id != 0 AND customer_id IS NOT NULL";
                if(isset($is_financial) && $is_financial == 1){
                    $taxbillingQ .= " AND financial_id = '".$financial_id."'";
                }
                if((isset($from) && $from != '') && (isset($to) && $to != '')){
                  $taxbillingQ .= " AND invoice_date >= '".$from."' AND invoice_date <= '".$to."'";
                }
                $taxbillingR = mysqli_query($conn, $taxbillingQ);
                if($taxbillingR && mysqli_num_rows($taxbillingR) > 0){
                    $taxbillingRow = mysqli_fetch_assoc($taxbillingR);
                    $total_bill = (isset($taxbillingRow['total_bill']) && $taxbillingRow['total_bill'] != '') ? $taxbillingRow['total_bill'] : 0;
                }
                /*--------------------------------------------CALCULATE TAX BILLING END-----------------------------------------*/
                
                /*--------------------------------------------CALCULATE PURCHASE START---------------------------------------*/
                $purchaseQ = "SELECT SUM(total_total) as total_purchase FROM purchase WHERE pharmacy_id = '".$pharmacy_id."' AND purchase_type = 'Debit' AND vendor != 0 AND vendor IS NOT NULL";
                if(isset($is_financial) && $is_financial == 1){
                    $purchaseQ .= " AND financial_id = '".$financial_id."'";
                }
                if((isset($from) && $from != '') && (isset($to) && $to != '')){
                  $purchaseQ .= " AND invoice_date >= '".$from."' AND invoice_date <= '".$to."'";
                }
                $purchaseR = mysqli_query($conn, $purchaseQ);
                if($purchaseR && mysqli_num_rows($purchaseR) > 0){
                    $purchaseRow = mysqli_fetch_assoc($purchaseR);
                    $total_purchase = (isset($purchaseRow['total_purchase']) && $purchaseRow['total_purchase'] != '') ? $purchaseRow['total_purchase'] : 0;
                }
                /*--------------------------------------------CALCULATE PURCHASE END-----------------------------------------*/
                
                /*--------------------------------------------CALCULATE CUSTOMER RECEIPT START---------------------------------------*/
                $cusromerreceiptQ = "SELECT SUM(amount) as total_receipt FROM cash_receipt WHERE pharmacy_id = '".$pharmacy_id."' AND customer != 0 AND customer IS NOT NULL";
                if(isset($is_financial) && $is_financial == 1){
                    $cusromerreceiptQ .= " AND financial_id = '".$financial_id."'";
                }
                if((isset($from) && $from != '') && (isset($to) && $to != '')){
                  $cusromerreceiptQ .= " AND payment_date >= '".$from."' AND payment_date <= '".$to."'";
                }
                $cusromerreceiptR = mysqli_query($conn, $cusromerreceiptQ);
                if($cusromerreceiptR && mysqli_num_rows($cusromerreceiptR) > 0){
                    $cusromerreceiptRow = mysqli_fetch_assoc($cusromerreceiptR);
                    $total_customer_receipt = (isset($cusromerreceiptRow['total_receipt']) && $cusromerreceiptRow['total_receipt'] != '') ? $cusromerreceiptRow['total_receipt'] : 0;
                }
                /*--------------------------------------------CALCULATE CUSTOMER RECEIPT END-----------------------------------------*/
                
                /*--------------------------------------------CALCULATE VENDOR PAYMENT START---------------------------------------*/
                $vendorPaymentQ = "SELECT SUM(amount) as total_payment FROM accounting_vendor_payment WHERE pharmacy_id = '".$pharmacy_id."' AND vendor != 0 AND vendor IS NOT NULL";
                if(isset($is_financial) && $is_financial == 1){
                    $vendorPaymentQ .= " AND financial_id = '".$financial_id."'";
                }
                if((isset($from) && $from != '') && (isset($to) && $to != '')){
                  $vendorPaymentQ .= " AND payment_date >= '".$from."' AND payment_date <= '".$to."'";
                }
                $vendorPaymentR = mysqli_query($conn, $vendorPaymentQ);
                if($vendorPaymentR && mysqli_num_rows($vendorPaymentR) > 0){
                    $vendorPaymentRow = mysqli_fetch_assoc($vendorPaymentR);
                    $total_vendor_payment = (isset($vendorPaymentRow['total_payment']) && $vendorPaymentRow['total_payment'] != '') ? $vendorPaymentRow['total_payment'] : 0;
                }
                /*--------------------------------------------CALCULATE VENDOR PAYMENT END-----------------------------------------*/
                
                /*----------------------------------------------------COUNT CHECK PAYMENT START-------------------------------------------------------*/
                $checkPaymentQ =  "SELECT SUM(amount) as amount FROM accounting_cheque WHERE pharmacy_id = '".$pharmacy_id."' AND voucher_type = 'payment' AND perticular != 0 AND perticular IS NOT NULL";
                if(isset($is_financial) && $is_financial == 1){
                    $checkPaymentQ .= " AND financial_id = '".$financial_id."'";
                }
                if((isset($from) && $from != '') && (isset($to) && $to != '')){
                  $checkPaymentQ .= " AND voucher_date >= '".$from."' AND voucher_date <= '".$to."'";
                }
                $checkPaymentR = mysqli_query($conn, $checkPaymentQ);
                $checkPaymentRow = ($checkPaymentR && mysqli_num_rows($checkPaymentR) > 0) ? mysqli_fetch_assoc($checkPaymentR) : '';
                $total_check_payment = (isset($checkPaymentRow['amount']) && $checkPaymentRow['amount'] != '') ? $checkPaymentRow['amount'] : 0;
                
                /*----------------------------------------------------COUNT CHECK PAYMENT END-------------------------------------------------------*/
                
                /*----------------------------------------------------COUNT CHECK RECEIPT START-------------------------------------------------------*/
                $checkReceiptQ =  "SELECT SUM(amount) as amount FROM accounting_cheque WHERE pharmacy_id = '".$pharmacy_id."' AND voucher_type = 'receipt' AND perticular != 0 AND perticular IS NOT NULL";
                if(isset($is_financial) && $is_financial == 1){
                    $checkReceiptQ .= " AND financial_id = '".$financial_id."'";
                }
                if((isset($from) && $from != '') && (isset($to) && $to != '')){
                  $checkReceiptQ .= " AND voucher_date >= '".$from."' AND voucher_date <= '".$to."'";
                }
                $checkReceiptR = mysqli_query($conn, $checkReceiptQ);
                $checkReceiptRow = ($checkReceiptR && mysqli_num_rows($checkReceiptR) > 0) ? mysqli_fetch_assoc($checkReceiptR) : '';
                $total_check_receipt = (isset($checkReceiptRow['amount']) && $checkReceiptRow['amount'] != '') ? $checkReceiptRow['amount'] : 0;
                /*----------------------------------------------------COUNT CHECK RECEIPT END-------------------------------------------------------*/
                
                /*----------------------------------------------------COUNT CASH RECEIPT START-------------------------------------------------------*/
                $cashReceiptQ =  "SELECT SUM(amount) as amount FROM accounting_cash_management WHERE pharmacy_id = '".$pharmacy_id."' AND payment_type = 'cash_receipt' AND perticular != 0 AND perticular IS NOT NULL";
                if(isset($is_financial) && $is_financial == 1){
                    $cashReceiptQ .= " AND financial_id = '".$financial_id."'";
                }
                if((isset($from) && $from != '') && (isset($to) && $to != '')){
                  $cashReceiptQ .= " AND voucher_date >= '".$from."' AND voucher_date <= '".$to."'";
                }
                $cashReceiptR = mysqli_query($conn, $cashReceiptQ);
                $cashReceiptRow = ($cashReceiptR && mysqli_num_rows($cashReceiptR) > 0) ? mysqli_fetch_assoc($cashReceiptR) : '';
                $total_cash_receipt = (isset($cashReceiptRow['amount']) && $cashReceiptRow['amount'] != '') ? $cashReceiptRow['amount'] : 0;
    
                /*----------------------------------------------------COUNT CASH RECEIPT END-------------------------------------------------------*/
                
                /*----------------------------------------------------COUNT CASH PAYMENT START-------------------------------------------------------*/
                $cashPaymentQ =  "SELECT SUM(amount) as amount FROM accounting_cash_management WHERE pharmacy_id = '".$pharmacy_id."' AND payment_type = 'cash_payment' AND perticular != 0 AND perticular IS NOT NULL";
                if(isset($is_financial) && $is_financial == 1){
                    $cashPaymentQ .= " AND financial_id = '".$financial_id."'";
                }
                if((isset($from) && $from != '') && (isset($to) && $to != '')){
                  $cashPaymentQ .= " AND voucher_date >= '".$from."' AND voucher_date <= '".$to."'";
                }
                $cashPaymentR = mysqli_query($conn, $cashPaymentQ);
                $cashPaymentRow = ($cashPaymentR && mysqli_num_rows($cashPaymentR) > 0) ? mysqli_fetch_assoc($cashPaymentR) : '';
                $total_cash_payment = (isset($cashPaymentRow['amount']) && $cashPaymentRow['amount'] != '') ? $cashPaymentRow['amount'] : 0;
                /*----------------------------------------------------COUNT CASH PAYMENT END-------------------------------------------------------*/
                
                /*-------------------------------------------------COUNT JOURNAL VOUCHER START----------------------------------------------------*/
                $jurnalQ = "SELECT SUM(jvd.debit) as total_debit, SUM(jvd.credit) as total_credit FROM journal_vouchar_details jvd INNER JOIN journal_vouchar jv ON jvd.voucher_id = jv.id WHERE jv.pharmacy_id = '".$pharmacy_id."' AND jvd.particular != 0 AND jvd.particular IS NOT NULL";
                if(isset($is_financial) && $is_financial == 1){
                    $jurnalQ .= " AND jv.financial_id = '".$financial_id."'";
                }
                if((isset($from) && $from != '') && (isset($to) && $to != '')){
                  $jurnalQ .= " AND jv.voucher_date >= '".$from."' AND jv.voucher_date <= '".$to."'";
                }
                $jurnalR = mysqli_query($conn, $jurnalQ);
                $jurnalRow = ($jurnalR && mysqli_num_rows($jurnalR) > 0) ? mysqli_fetch_assoc($jurnalR) : '';
                $total_jurnal_debit = (isset($jurnalRow['total_debit']) && $jurnalRow['total_debit'] != '') ? $jurnalRow['total_debit'] : 0;
                $total_jurnal_credit = (isset($jurnalRow['total_credit']) && $jurnalRow['total_credit'] != '') ? $jurnalRow['total_credit'] : 0;
                /*-------------------------------------------------COUNT JOURNAL VOUCHER START----------------------------------------------------*/
                
                $running_balance = ($opening_balance) + ($total_bill + $total_cash_payment + $total_check_payment + $total_jurnal_debit + $total_vendor_payment) - ($total_purchase + $total_customer_receipt + $total_check_receipt + $total_cash_receipt + $total_jurnal_credit);
                
                $data['opening_balance'] = (isset($opening_balance) && $opening_balance != '') ? $opening_balance : 0;
                $data['running_balance'] = (isset($running_balance) && $running_balance != '') ? $running_balance : 0;
            }
        return $data;
    }
    /*----THIS FUNCTION IS USED TO GET TOTAL CLOSING BALANCE  - GAUTAM MAKWANA - 27-11-2018 - END------*/
    
    /*----THIS FUNCTION IS USED TO GET TOTAL BANK CLOSING BALANCE  - GAUTAM MAKWANA - 27-11-2018 - START------*/
    function allBankRunningBalance($from = null, $to = null, $is_financial = 0){
        global $conn;
        global $pharmacy_id;
        $running_balance = 0;
        
            
        $bankqry = "SELECT id FROM ledger_master WHERE (group_id = 5 OR group_id = 22 OR group_id = 30) AND pharmacy_id = '".$pharmacy_id."'";
        $bankres = mysqli_query($conn, $bankqry);
        if($bankres && mysqli_num_rows($bankres) > 0){
            while($bankrow = mysqli_fetch_assoc($bankres)){
                if((isset($from) && $from != '') && (isset($to) && $to != '')){
                    $data = bankrunningbalance($bankrow['id'], $from, $to, $is_financial);
                    $running_balance += (isset($data['bank_running']) && $data['bank_running'] != '') ? $data['bank_running'] : 0;
                }else{
                    $data = bankrunningbalance($bankrow['id'], '', '', $is_financial);
                    $running_balance += (isset($data['bank_running']) && $data['bank_running'] != '') ? $data['bank_running'] : 0;
                }
            }
        }
        return $running_balance;
    }
    /*----THIS FUNCTION IS USED TO GET TOTAL BANK CLOSING BALANCE  - GAUTAM MAKWANA - 27-11-2018 - END------*/
    
    function getCurrentAssetsSecond($fromdate, $todate, $is_financial = 0){
        global $conn;
        global $pharmacy_id;
        $data = [];
        
            $from = (isset($fromdate) && $fromdate != '') ? date('Y-m-d',strtotime(str_replace("/","-", $fromdate))) : '';
            $to = (isset($todate) && $todate != '') ? date('Y-m-d',strtotime(str_replace("/","-",$todate))) : '';
        
            /*$totalClosingBalance = getTotalClosingBalance($from, $to, 0);
            $tmp['name'] = 'Closing Stock';
            $tmp['totaldebit'] = 0;
            $tmp['detail'][0]['name'] = 'Stock';
            $tmp['detail'][0]['debit'] = (isset($totalClosingBalance['running_balance']) && $totalClosingBalance['running_balance'] != '') ? $totalClosingBalance['running_balance'] : 0;
            $data[] = $tmp;*/
            
            $totalClosingStocke = getTotalClosingStock($from, $to, 0);
            $method = (isset($totalClosingStocke['mothod']) && $totalClosingStocke['mothod'] != '') ? $totalClosingStocke['mothod'] : 'Unknown Method';
            $tmp['name'] = 'Closing Stock';
            $tmp['totaldebit'] = 0;
            $tmp['detail'][0]['name'] = $method;
            $tmp['detail'][0]['debit'] = (isset($totalClosingStocke['closing_stock']) && $totalClosingStocke['closing_stock'] != '') ? $totalClosingStocke['closing_stock'] : 0;
            $data[] = $tmp;
            
            $sundaryDebotors = getGroupWiseClosingBalance(14, $from, $to, 0);
            $tmp1['name'] = 'Sundry Debtors';
            $tmp1['totaldebit'] = 0;
            $tmp1['detail'][0]['name'] = 'Debtors';
            $tmp1['detail'][0]['debit'] = (isset($sundaryDebotors['running_balance']) && $sundaryDebotors['running_balance'] != '') ? $sundaryDebotors['running_balance'] : 0;
            $data[] = $tmp1;
            
            $cashinHand = countCashRunningBalance($from, $to, 0);
            $tmp2['name'] = 'Cash-in-Hand';
            $tmp2['totaldebit'] = 0;
            $tmp2['detail'][0]['name'] = 'Cash';
            $tmp2['detail'][0]['debit'] = (isset($cashinHand['running_balance']) && $cashinHand['running_balance'] != '') ? $cashinHand['running_balance'] : 0;
            $data[] = $tmp2;
            
            $tmp3['name'] = 'Bank Accounts';
            $tmp3['totaldebit'] = 0;
            
            $bankqry = "SELECT id FROM ledger_master WHERE (group_id = 5 OR group_id = 22 OR group_id = 30) AND pharmacy_id = '".$pharmacy_id."'";
            $bankres = mysqli_query($conn, $bankqry);
            if($bankres && mysqli_num_rows($bankres) > 0){
                while($bankrow = mysqli_fetch_assoc($bankres)){
                    $bankdata = bankrunningbalance($bankrow['id'], $fromdate, $todate, 0);
                    $tmpbnk['name'] = (isset($bankdata['bankname'])) ? $bankdata['bankname'] : '';
                    $tmpbnk['debit'] = (isset($bankdata['bank_running']) && $bankdata['bank_running'] != '') ? $bankdata['bank_running'] : 0;
                    $tmp3['detail'][] = $tmpbnk;
                }
            }
            $data[] = $tmp3;
            
        if(!empty($data)){
            foreach($data as $key => $value){
                if(isset($value['detail']) && !empty($value['detail'])){
                    $sum = array_sum(array_column($value['detail'], 'debit'));
                    $data[$key]['totaldebit'] = ($sum != '') ? $sum : 0;
                }
            }
        }
        return $data;
    }
    
    /*---THIS FUNCTION IS USED TO GET INVESTMENT REPORT DATA - GAUTAM MAKWANA - 01-12-2018 - START----*/
    function investmentReport($id = null, $from = null, $to = null, $is_financial = 0){
        global $conn;
        global $pharmacy_id;
        global $financial_id;
        $data = [];

        $getLedgerQ = "SELECT id, name, opening_balance, opening_balance_type, created FROM ledger_master WHERE pharmacy_id = '".$pharmacy_id."' AND group_id = 20";
        if(isset($id) && $id != ''){
            $getLedgerQ .= " AND id = '".$id."'";
        }
        $getLedgerQ .= " ORDER BY name";
        $getLedgerR = mysqli_query($conn, $getLedgerQ);
        if($getLedgerR && mysqli_num_rows($getLedgerR) > 0){
            while ($getLedgerRow = mysqli_fetch_assoc($getLedgerR)) {
                $type = (isset($getLedgerRow['opening_balance_type'])) ? $getLedgerRow['opening_balance_type'] : 'DB';
                $opening_balance = (isset($getLedgerRow['opening_balance']) && $getLedgerRow['opening_balance'] != '') ? $getLedgerRow['opening_balance'] : 0;
                $openingDate = (isset($getLedgerRow['created'])) ? date('d-m-Y',strtotime($getLedgerRow['created'])) : '00-00-0000';
                
                $tmp['id'] = $getLedgerRow['id'];
                $tmp['name'] = $getLedgerRow['name'];
                $tmp['opening_balance'] = abs($opening_balance);
                $tmp['type'] = $type;
                $tmp['date'] = (isset($getLedgerRow['created']) && $getLedgerRow['created'] != '') ? date('d-m-Y',strtotime($getLedgerRow['created'])) : '';
                $tmp['detail'] = [];

                // cash-management
                $cashManagementQ = "SELECT id, payment_type, voucher_no, voucher_date, amount, remark FROM accounting_cash_management WHERE pharmacy_id = '".$pharmacy_id."' AND perticular = '".$getLedgerRow['id']."'";
                if(isset($is_financial) && $is_financial == 1){
                    $cashManagementQ .= " AND financial_id = '".$financial_id."'";
                }
                if((isset($from) && $from != '') && (isset($to) && $to != '')){
                    $cashManagementQ .= " AND voucher_date >= '".$from."' AND voucher_date <= '".$to."' ";
                }
                $cashManagementQ .=" ORDER BY voucher_date";
                $cashManagementR = mysqli_query($conn, $cashManagementQ);
                if($cashManagementR && mysqli_num_rows($cashManagementR) > 0){
                    while ($cashManagementRow = mysqli_fetch_assoc($cashManagementR)) {
                        $tmp1['id'] = $cashManagementRow['id'];
                        $tmp1['date'] = (isset($cashManagementRow['voucher_date']) && $cashManagementRow['voucher_date'] != '') ? date('d-m-Y',strtotime($cashManagementRow['voucher_date'])) : '00-00-0000';
                        $remark = (isset($cashManagementRow['remark']) && $cashManagementRow['remark'] != '') ? ' - '.$cashManagementRow['remark'] : '';
                        if(isset($cashManagementRow['payment_type']) && $cashManagementRow['payment_type'] == 'cash_payment'){
                            $tmp1['perticular'] = 'Cash Payment'.$remark;
                            $tmp1['type'] = 'Payment';
                            $tmp1['inward']['qty']  = '';
                            $tmp1['inward']['rate']  = '';
                            $tmp1['inward']['value'] = (isset($cashManagementRow['amount']) && $cashManagementRow['amount'] != '') ? $cashManagementRow['amount'] : 0;
                            $tmp1['outward']['qty'] = '';
                            $tmp1['outward']['rate'] = '';
                            $tmp1['outward']['value'] = '';
                        }else{
                            $tmp1['perticular'] = 'Cash Receipt'.$remark;
                            $tmp1['type'] = 'Receipt';

                            $tmp1['inward']['qty']  = '';
                            $tmp1['inward']['rate']  = '';
                            $tmp1['inward']['value'] = '';

                            $tmp1['outward']['qty'] = '';
                            $tmp1['outward']['rate'] = '';
                            $tmp1['outward']['value'] = (isset($cashManagementRow['amount']) && $cashManagementRow['amount'] != '') ? $cashManagementRow['amount'] : 0;
                        }
                        $tmp1['voucherno'] = (isset($cashManagementRow['voucher_no'])) ? $cashManagementRow['voucher_no'] : '';
                        $tmp['detail'][$tmp1['date']][] = $tmp1;
                    }
                }

                // cheque
                $chequeQ = "SELECT id, voucher_no, voucher_date, voucher_type, cheque_no, amount, remark FROM accounting_cheque WHERE pharmacy_id = '".$pharmacy_id."' AND perticular = '".$getLedgerRow['id']."'";
                if(isset($is_financial) && $is_financial == 1){
                    $chequeQ .= " AND financial_id = '".$financial_id."'";
                }
                if((isset($from) && $from != '') && (isset($to) && $to != '')){
                    $chequeQ .= " AND voucher_date >= '".$from."' AND voucher_date <= '".$to."' ";
                }
                $chequeQ .= " ORDER BY voucher_date";
                $chequeR = mysqli_query($conn, $chequeQ);
                if($chequeR && mysqli_num_rows($chequeR) > 0){
                    while ($chequeRow = mysqli_fetch_assoc($chequeR)) {
                        $tmp2['id'] = $chequeRow['id'];
                        $tmp2['date'] = (isset($chequeRow['voucher_date']) && $chequeRow['voucher_date'] != '') ? date('d-m-Y',strtotime($chequeRow['voucher_date'])) : '00-00-0000';
                        $remark = (isset($chequeRow['remark']) && $chequeRow['remark'] != '') ? ' - '.$chequeRow['remark'] : '';
                        $cheque_no = (isset($chequeRow['cheque_no']) && $chequeRow['cheque_no'] != '') ? ' - '.$chequeRow['cheque_no'] : '';
                        if(isset($chequeRow['payment_type']) && $chequeRow['payment_type'] == 'payment'){
                            $tmp2['perticular'] = 'Cheque Payment'.$cheque_no.''.$remark;
                            $tmp2['type'] = 'Payment';

                            $tmp2['inward']['qty']  = '';
                            $tmp2['inward']['rate']  = '';
                            $tmp2['inward']['value'] = (isset($chequeRow['amount']) && $chequeRow['amount'] != '') ? $chequeRow['amount'] : 0;

                            $tmp2['outward']['qty'] = '';
                            $tmp2['outward']['rate'] = '';
                            $tmp2['outward']['value'] = '';
                            
                        }else{
                            $tmp2['perticular'] = 'Cheque Receipt'.$cheque_no.''.$remark;
                            $tmp2['type'] = 'Receipt';

                            $tmp2['inward']['qty']  = '';
                            $tmp2['inward']['rate']  = '';
                            $tmp2['inward']['value'] = '';

                            $tmp2['outward']['qty'] = '';
                            $tmp2['outward']['rate'] = '';
                            $tmp2['outward']['value'] = (isset($chequeRow['amount']) && $chequeRow['amount'] != '') ? $chequeRow['amount'] : 0;
                            
                        }
                        $tmp2['voucherno'] = (isset($chequeRow['voucher_no'])) ? $chequeRow['voucher_no'] : '';
                        $tmp['detail'][$tmp2['date']][] = $tmp2;
                    }
                }

                 // journal voucher
                $journalVoucherQ = "SELECT jv.id, jv.voucher_date, jv.remarks, jvd.debit, jvd.qty, jvd.rate, jvd.credit FROM journal_vouchar_details jvd INNER JOIN journal_vouchar jv ON jvd.voucher_id = jv.id WHERE jv.pharmacy_id = '".$pharmacy_id."' AND jvd.particular = '".$getLedgerRow['id']."'";
                if(isset($is_financial) && $is_financial == 1){
                    $journalVoucherQ .= " AND jv.financial_id = '".$financial_id."'";
                }
                if((isset($from) && $from != '') && (isset($to) && $to != '')){
                    $journalVoucherQ .= " AND jv.voucher_date >= '".$from."' AND jv.voucher_date <= '".$to."' ";
                }
                $journalVoucherQ .= " ORDER BY jv.voucher_date";
                $journalVoucherR = mysqli_query($conn, $journalVoucherQ);
                if($journalVoucherR && mysqli_num_rows($journalVoucherR) > 0){
                    while ($journalVoucherRow = mysqli_fetch_assoc($journalVoucherR)) {
                        $tmp3['id'] = $journalVoucherRow['id'];
                        $tmp3['date'] = (isset($journalVoucherRow['voucher_date']) && $journalVoucherRow['voucher_date'] != '') ? date('d-m-Y',strtotime($journalVoucherRow['voucher_date'])) : '00-00-0000';
                        $remark = (isset($journalVoucherRow['remarks']) && $journalVoucherRow['remarks'] != '') ? ' - '.$journalVoucherRow['remarks'] : '';
                        $tmp3['type'] = 'Journal';
                        $tmp3['perticular'] = 'Journal Voucher'.$remark;
                        if(isset($journalVoucherRow['credit']) && $journalVoucherRow['credit'] != '' && $journalVoucherRow['credit'] != 0){
                            $tmp3['inward']['qty']  = '';
                            $tmp3['inward']['rate']  = '';
                            $tmp3['inward']['value'] = '';

                            $tmp3['outward']['qty'] = (isset($journalVoucherRow['qty']) && $journalVoucherRow['qty'] != '' && $journalVoucherRow['qty'] != 0) ? $journalVoucherRow['qty'] : '';
                            $tmp3['outward']['rate'] = (isset($journalVoucherRow['rate']) && $journalVoucherRow['rate'] != '' && $journalVoucherRow['rate'] != 0) ? $journalVoucherRow['rate'] : '';
                            $tmp3['outward']['value'] = (isset($journalVoucherRow['credit']) && $journalVoucherRow['credit'] != '') ? $journalVoucherRow['credit'] : 0;
                            
                        }
                        if(isset($journalVoucherRow['debit']) && $journalVoucherRow['debit'] != '' && $journalVoucherRow['debit'] != 0){
                            $tmp3['inward']['qty']  = (isset($journalVoucherRow['qty']) && $journalVoucherRow['qty'] != '' && $journalVoucherRow['qty'] != 0) ? $journalVoucherRow['qty'] : '';
                            $tmp3['inward']['rate']  = (isset($journalVoucherRow['rate']) && $journalVoucherRow['rate'] != '' && $journalVoucherRow['rate'] != 0) ? $journalVoucherRow['rate'] : '';
                            $tmp3['inward']['value'] = (isset($journalVoucherRow['debit']) && $journalVoucherRow['debit'] != '') ? $journalVoucherRow['debit'] : 0;

                            $tmp3['outward']['qty'] = '';
                            $tmp3['outward']['rate'] = '';
                            $tmp3['outward']['value'] = '';
                        }

                        $tmp3['voucherno'] = '';
                        $tmp['detail'][$tmp3['date']][] = $tmp3;
                    }
                }
                $data[] = $tmp;
            }
        }

        return $data;
    }
    /*---THIS FUNCTION IS USED TO GET INVESTMENT REPORT DATA - GAUTAM MAKWANA - 01-12-2018 - END----*/
    
     /*------------------------------------- TAX BILLING MAIL-------SHREYA----------------10-12-2018-----------------------   */
  function send_tax_mail($id){
     global $conn;
   if(isset($id) && $id != ''){
        $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';

       $query = "SELECT tb.id, tb.customer_name,tb.is_general_sale, tb.city_id, tb.pharmacy_id, tb.invoice_no, tb.invoice_date, tb.bill_type, tb.total_return_amount, tb.alltotalamount, tb.couriercharge, tb.couriercharge_val, tb.totaligst, tb.totalcgst, tb.totalsgst, tb.totaltaxgst, tb.discount_type, tb.discount_per, tb.discount_rs, tb.cr_db_type, tb.cr_db_val, tb.final_amount, lg.name as l_customer_name,lg.email, lg.addressline1, lg.addressline2, lg.addressline3, lg.gstno,st.name as customer_state, ct.name as customer_city,dp.name as doctor_name FROM tax_billing tb LEFT JOIN ledger_master lg ON tb.customer_id = lg.id LEFT JOIN own_states st ON lg.state = st.id LEFT JOIN own_cities ct ON lg.city = ct.id LEFT JOIN doctor_profile dp ON tb.doctor = dp.id  WHERE tb.pharmacy_id ='".$pharmacy_id."' AND tb.id='".$id."'";
        
        $res = mysqli_query($conn, $query);
        if($res && mysqli_num_rows($res) > 0){
            $data = mysqli_fetch_assoc($res);
            
            if(isset($data['is_general_sale']) && $data['is_general_sale'] == 1){
                $data['l_customer_name'] = (isset($data['customer_name'])) ? $data['customer_name'] : '';
                if(isset($data['city_id']) && $data['city_id'] != ''){
                  $getCityQ = "SELECT ct.id, ct.name, st.name as state_name FROM own_cities ct LEFT JOIN own_states st ON ct.state_id = st.id WHERE ct.id = '".$data['city_id']."'";
                    $getCityR = mysqli_query($conn, $getCityQ);
                    if($getCityR && mysqli_num_rows($getCityR) > 0){
                        $getCityRow = mysqli_fetch_assoc($getCityR);
                        $data['customer_city'] = (isset($getCityRow['name'])) ? $getCityRow['name'] : '';
                        $data['customer_state'] = (isset($getCityRow['state_name'])) ? $getCityRow['state_name'] : '';
                    }
                }
                
            }

            /*----------------------------------------------PHARMACY QUERY START-------------------------------------------*/
            if(isset($data['pharmacy_id']) && $data['pharmacy_id'] != ''){
              $pharmacyQ = "SELECT p.id, p.pharmacy_name, p.address1, p.address2, p.address3, p.gst_no,st.name as state_name FROM pharmacy_profile p LEFT JOIN own_states st ON p.stateId = st.id WHERE p.id='".$data['pharmacy_id']."'";
                $pharmacyR = mysqli_query($conn, $pharmacyQ);
                if($pharmacyR && mysqli_num_rows($pharmacyR) > 0){
                    $data['pharmacy'] = mysqli_fetch_assoc($pharmacyR);
                    
                }
            }
            /*----------------------------------------------PHARMACY QUERY END-------------------------------------------*/

            /*----------------------------------------------PHARMACY BANK DETAIL QUERY START-------------------------------------------*/
            if(isset($data['pharmacy_id']) && $data['pharmacy_id'] != ''){
                $pharmacyBankDetailQ = "SELECT id, bank_name, ifsc_code, account_number FROM pharmacy_bank_details WHERE pharmacy_id = '".$data['pharmacy_id']."'";
                $pharmacyBankDetailR = mysqli_query($conn, $pharmacyBankDetailQ);
                if($pharmacyBankDetailR && mysqli_num_rows($pharmacyBankDetailR) > 0){

                    while ($pharmacyBankDetailRow = mysqli_fetch_assoc($pharmacyBankDetailR)) {

                        $data['pharmacy_bank_detail'][] = $pharmacyBankDetailRow;
                    }
                }
            }   
            /*----------------------------------------------PHARMACY BANK DETAIL QUERY START-------------------------------------------*/

            /*----------------------------------------------TAX BILLING DETAIL QUERY START-------------------------------------------*/
                if(isset($data['id']) && $data['id'] != ''){
                  $taxBillingDetailQ = "SELECT tbd.*, pm.product_name, pm.mrp as product_mrp, pm.mfg_company as product_mfg_company, pm.batch_no as product_batch_no, pm.ex_date as product_ex_date FROM tax_billing_details tbd INNER JOIN product_master pm ON tbd.product_id = pm.id WHERE tbd.tax_bill_id = '".$data['id']."'";
                    
                    $taxBillingDetailR = mysqli_query($conn, $taxBillingDetailQ);
                    if($taxBillingDetailR && mysqli_num_rows($taxBillingDetailR) > 0){
                        while ($taxBillingDetailRow = mysqli_fetch_assoc($taxBillingDetailR)) {
                            $data['tax_billing_detail'][] = $taxBillingDetailRow;
                            
                        }
                    }
                }
            /*----------------------------------------------TAX BILLING DETAIL QUERY END-------------------------------------------*/

            /*----------------------------------------------PRODUCT RETURN DETAIL QUERY START-----------------------------------------*/
            if(isset($data['id']) && $data['id'] != ''){
                $salereturnQ = "SELECT sr.*, pm.product_name, pm.mrp as product_mrp, pm.mfg_company as product_mfg_company, pm.batch_no as product_batch_no, pm.ex_date as product_ex_date FROM sale_return sr INNER JOIN product_master pm ON sr.product_id = pm.id WHERE sr.tax_bill_id = '".$data['id']."'";
                $salereturnR = mysqli_query($conn, $salereturnQ);
                if($salereturnR && mysqli_num_rows($salereturnR) > 0){
                    while ($salereturnRow = mysqli_fetch_assoc($salereturnR)) {
                        $data['sale_return'][] = $salereturnRow;
                    }
                }
            }
            /*----------------------------------------------PRODUCT RETURN DETAIL QUERY END-------------------------------------------*/
            
        }

         $html = '<html> <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><style>
      @media print {
    @page { margin: 0; }
    body { margin: 1.5cm;font-family:Verdana, Arial, Helvetica, sans-serif; }
  }
  @page { margin: 0; }
  @page {
     size: 7in 9.25in;
     margin: 25mm 16mm 27mm 16mm;
  }
  @page { margin: 0; }
  
    .invoice-box{
        max-width:595px;
        margin:auto;
        padding:0px;
        /*border:1px solid #eee;
        box-shadow:0 0 10px rgba(0, 0, 0, .15);*/
        font-size:11px;
        line-height:22px;
        font-family:"Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
        color:#555;
      margin-top: 20px;
    line-height:23px;
    }
    
    .invoice-box table{
        width:100%;
        line-height:inherit;
        text-align:left;
    }
    table td.center
  { text-align:center}
  table td.right
  { text-align:right}
  table td.left
  { text-align:left}
    .invoice-box table td{
        padding:2px 5px;
        vertical-align:middle;
    }
     .invoice-box table tr td:nth-child(1){
    /*width:10px;*/
   }
   .invoice-box table tr.information td:nth-child(1){
    width:70%;
    
   }
   .invoice-box table tr.information td:nth-child(2){
    width:50%;  text-align:right;
    
   }
    .invoice-box table tr td:nth-child(5),.invoice-box table tr td:nth-child(6){
        text-align:right;
    }
   .invoice-box table tr.heading td:nth-child(1)
  {
    width:10px !important;
  }
   .invoice-box table tr.item td:nth-child(1)
  {
    width:10px !important;
  }
  
    .invoice-box table tr.top table td{
      
    }
    
    .invoice-box table tr.top table td.title{
        font-size:35px;
        line-height:35px;
        color:#333;
    }
    
    .invoice-box table tr.information table td{
        /*padding-bottom:20px;*/
    }
    
    .invoice-box table tr.heading td{
        background:#D6D6D6;
    color:#000;
        border-bottom:1px solid #000;
    text-align:center;

    }
    
    .invoice-box table tr.details td{
       /* padding-bottom:20px;*/
    }
    
    .invoice-box table tr.item td{
    height:10px;
    }
    
    .invoice-box table tr.item.last td{
       /* border-bottom:none;*/
    }
    
    .invoice-box table tr.total td:nth-child(5), .invoice-box table tr.total td:nth-child(6){
        border-top:2px solid #000;
        font-weight:bold;
    }
    td label 
  {
    width : 50px;
  }
    @media only screen and (max-width: 600px) {
        .invoice-box table tr.top table td{
            width:100%;
            display:block;
            text-align:center;
        }
        
        .invoice-box table tr.information table td{
            width:100%;
            display:block;
            text-align:center;
        }
    }
  
  .company-title
  {
    text-align:center;
    font-size:22px;
    font-weight:bold;
    text-transform:uppercase;
    font-family:Verdana, Arial, Helvetica, sans-serif;
  }
  .company-address
  {
    text-align:center;
    font-size:14px;
    text-transform:capitalize;
    font-family:Verdana, Arial, Helvetica, sans-serif;
    margin:3px 0;
  }
  .company-gst
  {
    text-align:center;
    font-size:14px;
    text-transform:capitalize;
    font-weight:bold;
    font-family:Verdana, Arial, Helvetica, sans-serif;
    margin:2px 0;
  }</style></head>'; 
  
      $pharmacy_name = (isset($data['pharmacy']['pharmacy_name'])) ? $data['pharmacy']['pharmacy_name'] : ''; 
       $html .='<body><div class="invoice-box">
        
    <div class="company-title" style="  text-align:center;
    font-size:22px;
    font-weight:bold;
    text-transform:uppercase;
    font-family:Verdana, Arial, Helvetica, sans-serif;">'.$pharmacy_name.'</div>';
         
            $addressArray = [];
            if(isset($data['pharmacy']['address1']) && $data['pharmacy']['address1'] != ''){
                $addressArray[] = $data['pharmacy']['address1'];
            }
            if(isset($data['pharmacy']['address2']) && $data['pharmacy']['address2'] != ''){
                $addressArray[] = $data['pharmacy']['address2'];
            }
            if(isset($data['pharmacy']['address3']) && $data['pharmacy']['address3'] != ''){
                $addressArray[] = $data['pharmacy']['address3'];
            }
            if(!empty($addressArray)){
                $companyAddress = implode(', ', $addressArray);
                $companyAddress = ucwords(strtolower($companyAddress));
            }
            $CompanyAddress = (isset($companyAddress)) ? $companyAddress : '';
            $gst_no = (isset($data['pharmacy']['gst_no'])) ? $data['pharmacy']['gst_no'] : ''; 
            
            
        $html .='<div class="company-address" style="text-align:center;
    font-size:14px;
    text-transform:capitalize;
    font-family:Verdana, Arial, Helvetica, sans-serif;
    margin:3px 0;">Address:'.$CompanyAddress.'</div>
    
        <div class="company-gst" style= "text-align:center;
    font-size:14px;
    text-transform:capitalize;
    font-weight:bold;
    font-family:Verdana, Arial, Helvetica, sans-serif;
    margin:2px 0;">GSTNo :'.$gst_no.'</div>';
        
       $html .= '<table cellpadding="0" border="1"  cellspacing="0">';
       $html .='<tr class="top">
                <td colspan="2" style="padding:0px;border-bottom:none;">
                    <table  cellpadding="0" cellspacing="0" border="0" style="line-height:13px;font-size:9px;height: 30px;" rules="rows" frame="below">
                        <tr>
              <th rowspan="3" align="center" style="text-align:center;font-size:22px;;font-weight:bold;width:70%;border-left:none;">TAX INVOICE</th>
            </tr>                                      
          </table>
        </td>
        
            </tr>';
            
       $html .='<tr class="" style="line-height:12px;">
                <td  style="vertical-align:top;">
                    <table  cellpadding="0" cellspacing="0" border="0" style="line-height:10px;font-size:9px;">
            <tr>     
                            <td>
                                <b style="width:100px;float:left;">Invoice No. </b> 
                                    <div style="width:150px;float:left;">:'.$data['invoice_no'].'</div>
                            </td>
                        </tr>
                        <tr>     
                            <td>
                                <b style="width:100px;float:left;">Invoice Date </b> 
                                    <div style="width:150px;float:left;">:'.date('d-M-Y',strtotime($data['invoice_date'])) .'</div>
                            </td>
                        </tr>

                        <tr>     
                            <td>
                                    <b style="width:100px;float:left;">Bill Type </b> 
                                    <div style="width:150px;float:left;">:'.$data['bill_type'].'</div>
                            </td>
                        </tr>
                        
                        <tr>     
                            <td>
                                    <b style="width:100px;float:left;">Doctor </b> 
                                    <div style="width:150px;float:left;">:'.$data['doctor_name'].'</div>
                            </td>
                        </tr>

                        <tr>     
                            <td>
                                    <b style="width:100px;float:left;">Pharmacy State </b> 
                                    <div style="width:150px;float:left;">:'.$data['pharmacy']['state_name'].'</div>
                            </td>
                        </tr>
                    </table>
                </td>
        <td style="text-align:left">

                  <table  cellpadding="0" cellspacing="0" border="0"  style="line-height:10px;font-size:9px;">
            <tr>     
                            <td>
                                <b style="width:100px;float:left;">Customer </b> 
                                    <div style="width:150px;float:left;">:'.ucwords(strtolower($data['l_customer_name'])).'</div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                    <b style="width:100px;float:left;">GSTIN </b> 
                                    <div style="width:150px;float:left;">:'.$data['gstno'].'</div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                    <b style="width:100px;float:left;">Address </b>';
                                        $customerAddArr = [];
                                        if(isset($data['addressline1']) && $data['addressline1'] != ''){
                                            $customerAddArr[] = $data['addressline1'];
                                        }
                                        if(isset($data['addressline2']) && $data['addressline2'] != ''){
                                            $customerAddArr[] = $data['addressline2'];
                                        }
                                        if(isset($data['addressline3']) && $data['addressline3'] != ''){
                                            $customerAddArr[] = $data['addressline3'];
                                        }
                                        if(!empty($customerAddArr)){
                                            $customerAddress = implode(', ', $customerAddArr); 
                                        }
                                   
                                    
       $html .= '<div style="width:150px;float:left;">:'.$customerAddress.'</div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                    <b style="width:100px;float:left;">City </b> 
                                    <div style="width:150px;float:left;">:'.$data['customer_city'].'</div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                    <b style="width:100px;float:left;">State </b> 
                                    <div style="width:150px;float:left;">:'.$data['customer_state'].'</div>
                            </td>
                        </tr>
                    </table>
          
        </td>
            </tr>';
           
       $html .='<tr class="">
              <td colspan="2" style="height:13px;"></td>
            </tr>';
           
       $html .='<tr class="top">
                <td colspan="2" style="padding:0px;border-bottom:none;">
                   <table cellpadding="10" cellspacing="0" border="1" style="border: 0px solid;font-size:8px;line-height:10px;" frame="below" rules="cols" >          
            <tr class="heading" align="center"> 
              <td style="width:10px;" rowspan="2">Sr. No.</td>
              <td rowspan="2" style="width:100px;">Prouduct Name</td>
              <td rowspan="2">MRP</td>
                            <td rowspan="2">MFG Co.</td>
                            <td rowspan="2">Batch</td>
                            <td rowspan="2">Expiry</td>
              <td rowspan="2">Qty</td>
                            <td rowspan="2">Free Qty</td>
              <td rowspan="2">Rate</td>
                            <td rowspan="2">Less Discount</td>
                            <td rowspan="2">Taxable Value</td>
                            <td colspan="2" style="text-align:center">CGST</td>
                            <td colspan="2" style="text-align:center">SGST</td>
                            <td colspan="2"  style="text-align:center">IGST</td>
                            <td rowspan="2" align="center">Total</td>                            
            </tr>
                        <tr class="heading">
                          <td>Rate</td>
                            <td>Amount</td>
                          <td>Rate</td>
                            <td>Amount</td>
                          <td>Rate</td>
                            <td>Amount</td>                                                                                    
                        </tr>
            
            <tbody style="margin-top:10px; height:100px;">';

                     if(isset($data['tax_billing_detail']) && !empty($data['tax_billing_detail'])){
                               
                                    $total_qty = 0;$total_free_qty = 0;$total_rate = 0;$total_discount = 0;$total_taxable_amount = 0;$total_cgst_amount = 0;$total_sgst_amount = 0;$total_igst_amount = 0;$total_amount = 0;
                                      
                     foreach ($data['tax_billing_detail'] as $key => $value) {
                  $html .= '<tr class="item">
                          <td>';
                    $html .=  $key+1;
                    $html .='</td>
                          <td>'.$value['product_name'].'</td>
                          <td>'.$value['product_mrp'].'</td>
                                        <td>'.$value['product_mfg_company'].'</td>
                                        <td>'.$value['product_batch_no'].'</td>
                                        <td>'.date('m/Y',strtotime($value['product_ex_date'])).'</td>';
                                           
                                                $qty =  (isset($value['qty']) && $value['qty'] != '') ? $value['qty'] : 0;
                                                $total_qty += $qty;
                                                $QTY = round($qty, 2);
                                                
                            $html .=  '<td align="right">'.$QTY.'</td>';
                                            
                                                $free_qty = (isset($value['freeqty']) && $value['freeqty'] != '') ? $value['freeqty'] : 0;
                                                $total_free_qty += $free_qty;
                                                $Free_Qty = round($free_qty, 2);
                                     
                    $html .= '<td align="right">'.$Free_Qty.'</td>';
                          
                                                $rate =  (isset($value['rate']) && $value['rate'] != '') ? $value['rate'] : 0;
                                                $total_rate += $rate;
                                                $RATE = round($rate, 2);
                                                
                            $html .= '<td align="right">'.$RATE.'</td>';
                          
                                                $discount =  (isset($value['discount']) && $value['discount'] != '') ? $value['discount'] : 0;
                                                $total_discount += $discount;
                                                $DISCOUNT = round($discount, 2);
                                               
                            $html .= '<td align="right">'.$DISCOUNT.'</td>';
                          
                                                $taxable_amount = (isset($value['totalamount']) && $value['totalamount'] != '') ? $value['totalamount'] : 0; 
                                                $total_taxable_amount += $taxable_amount;
                                                $Taxable_AMount = round($taxable_amount, 2);
                                                
                            $html .='<td style="background-color:#D6D6D6" align="right">'.$Taxable_AMount.'</td>';
                                               
                                               $cgst = (isset($value['cgst']) && $value['cgst'] != '') ? $value['cgst'] : 0;
                                                $CGST = round($cgst, 2).' %';
                                               
                            $html .= '<td>'.$CGST.'</td>';
                                        
                                                $cgst_amount = ($taxable_amount*$cgst/100);
                                                $total_cgst_amount += $cgst_amount;
                                                $CGST_Amount = round($cgst_amount, 2);
                                                
                            $html .= '<td align="right">'.$CGST_Amount.'</td>';
                                       
                                                $sgst = (isset($value['sgst']) && $value['sgst'] != '') ? $value['sgst'] : 0;
                                                $SGST = round($sgst, 2).' %';
                                                
                                                
                            $html .= ' <td>'.$SGST.'</td>';
                                    
                                                $sgst_amount = ($taxable_amount*$sgst/100);
                                                $total_sgst_amount += $sgst_amount;
                                                $SGST_AMount = round($sgst_amount, 2);
                                                
                            $html .= '<td align="right">'.$SGST_AMount.'</td>';
                                        
                                                $igst = (isset($value['igst']) && $value['igst'] != '') ? $value['igst'] : 0;
                                                $IGST = round($igst, 2).' %';
                                                
                            $html .= '<td>'.$IGST.'</td>';
                                       
                                                $igst_amount = ($taxable_amount*$igst/100);
                                                $total_igst_amount += $igst_amount;
                                                $IGST_AMount = round($igst_amount, 2);
                                                
                            $html .= ' <td align="right">'.$IGST_AMount.'</td>';
                                        
                                                $amount = (isset($value['totalamount']) && $value['totalamount'] != '') ? $value['totalamount'] : 0;
                                                $amount = $amount + $total_cgst_amount + $total_sgst_amount + $total_igst_amount;
                                                $total_amount += $amount;
                                                $AMOUNT = round($amount, 2);
                                                
                            $html .= '<td style="background-color:#D6D6D6;text-align: right;">'.$AMOUNT.'</td>                            
                        </tr>';
                                } 
                             } 
                                   $total_qty= (isset($total_qty)) ? round($total_qty, 2) : 0 ;
                                   $total_free_qty = (isset($total_free_qty)) ? round($total_free_qty, 2) : 0 ;
                                   $total_rate = (isset($total_rate)) ? round($total_rate, 2) : 0 ;
                                   $total_discount =  (isset($total_discount)) ? round($total_discount, 2) : 0 ;
                                   $total_taxable_amount = (isset($total_taxable_amount)) ? round($total_taxable_amount, 2) : 0 ;
                                   $total_cgst_amount=  (isset($total_cgst_amount)) ? round($total_cgst_amount, 2) : 0 ;
                                   $total_sgst_amount = (isset($total_sgst_amount)) ? round($total_sgst_amount, 2) : 0 ;
                                   $total_igst_amount = (isset($total_igst_amount)) ? round($total_igst_amount, 2) : 0 ;
                                    $total_amount = (isset($total_amount)) ? round($total_amount, 2) : 0 ;
                            $html .= '<tr class="heading" style="background-color:#D6D6D6;border-top:1px solid #181818;"> 
                  <td colspan="6" style="text-align: right;">Total</td>
                  <td style="text-align: right;">'.$total_qty.'</td>
                  <td style="text-align: right;">'.$total_free_qty.'</td>
                  <td style="text-align: right;">'.$total_rate.'</td>
                  <td style="text-align: right;">'.$total_discount.'</td>
                  <td style="text-align: right;">'.$total_taxable_amount.'</td>
                  <td colspan="2" style="text-align: right;">'.$total_cgst_amount.'</td>
                  <td colspan="2" style="text-align: right;">'.$total_sgst_amount.'</td>
                  <td colspan="2" style="text-align: right;">'.$total_igst_amount.'</td>
                  <td style="text-align: right;">'.$total_amount.'</td>
                </tr>
            
            </tbody>
                  </table>
        </td>
            </tr>';
         

          
                    if(isset($data['sale_return']) && !empty($data['sale_return'])){
       $html .=  '<tr class="">
                    <td colspan="2" align="center" style="height:13px;">PRODUCT RETURN</td>
                </tr>
                
                <tr class="top">
                    <td colspan="2" style="padding:0px;border-bottom:none;">
                       <table cellpadding="10" cellspacing="0" border="1" style="border: 0px solid;font-size:8px;line-height:10px;" frame="below" rules="cols" >                    
                            <tr class="heading" align="center"> 
                                <td style="width:10px;" rowspan="2">Sr. No.</td>
                                <td rowspan="2" style="width:100px;">Prouduct Name</td>
                                <td rowspan="2">MRP</td>
                                <td rowspan="2">MFG Co.</td>
                                <td rowspan="2">Batch</td>
                                <td rowspan="2">Expiry</td>
                                <td rowspan="2">Qty</td>
                                <td rowspan="2">Rate</td>
                                <td rowspan="2">Less Discount</td>
                                <td rowspan="2">Taxable Value</td>
                                <td colspan="2" style="text-align:center">CGST</td>
                                <td colspan="2" style="text-align:center">SGST</td>
                                <td colspan="2"  style="text-align:center">IGST</td>
                                <td rowspan="2" align="center">Total</td>                            
                            </tr>
                            <tr class="heading">
                                <td>Rate</td>
                                <td>Amount</td>
                                <td>Rate</td>
                                <td>Amount</td>
                                <td>Rate</td>
                                <td>Amount</td>                                                                                    
                            </tr>
                            
                            <tbody style="margin-top:10px; height:100px;">';
                                foreach ($data['sale_return'] as $key => $value) {
                                    
                                  $r_total_qty = 0;$r_total_free_qty = 0;$r_total_rate = 0;$r_total_discount = 0;$r_total_taxable_amount = 0;$r_total_cgst_amount = 0;$r_total_sgst_amount = 0;$r_total_igst_amount = 0;$r_total_amount = 0;
                                    
                                $html .= '<tr class="item">
                                            <td>'.$key.'+1;</td>
                                            <td>'.(isset($value['product_name'])) ? $value['product_name'] : '' .'</td>
                                            <td>'.(isset($value['product_mrp'])) ? $value['product_mrp'] : '' .'</td>
                                            <td>'.(isset($value['product_mfg_company'])) ? $value['product_mfg_company'] : '' .'</td>
                                            <td>'.(isset($value['product_batch_no'])) ? $value['product_batch_no'] : '' .'</td>
                                            <td>'.(isset($value['product_ex_date']) && $value['product_ex_date'] != '') ? date('d/m/Y',strtotime($value['product_ex_date'])) : '' .'</td>';
                                           
                                           
                                           $r_qty =  (isset($value['qty']) && $value['qty'] != '') ? $value['qty'] : 0;
                                                    $r_total_qty += $r_qty;
                                                    $R_Qty =  round($r_qty, 2); 
                                           
                                $html .= '<td align="right">'.$R_Qty.'</td>';
                                           
                                             $r_rate =  (isset($value['rate']) && $value['rate'] != '') ? $value['rate'] : 0;
                                                    $r_total_rate += $r_rate;
                                                    $R_Rate =  round($r_rate, 2);
                                           
                                $html .='<td align="right">'.$R_Rate.'</td>';
                                          
                                          $r_discount =  (isset($value['discount']) && $value['discount'] != '') ? $value['discount'] : 0;
                                                    $r_total_discount += $r_discount;
                                                    $R_Discount = round($r_discount, 2);
                                          
                                $html .='<td align="right">'.$R_Discount.'</td>';            
                                          
                                            $r_taxable_amount = (isset($value['amount']) && $value['amount'] != '') ? $value['amount'] : 0; 
                                                    $r_total_taxable_amount += $r_taxable_amount;
                                                    $R_Taxable_Amount = round($r_taxable_amount, 2);
                                           
                                $html .='<td style="background-color:#D6D6D6" align="right">'.(isset($r_total_taxable_amount)) ? round($r_total_taxable_amount, 2) : 0 .'</td>';           
                                            
                                               $r_cgst = (isset($value['cgst']) && $value['cgst'] != '') ? $value['cgst'] : 0;
                                                    $R_cgst = round($r_cgst, 2);
                                                    
                                $html .='</td>'.$R_cgst.' % </td>';
                                            
                                               $r_cgst_amount = ($r_taxable_amount*$r_cgst/100);
                                                    $r_total_cgst_amount += $r_cgst_amount;
                                                    $R_Cgst_Amount =  round($r_cgst_amount, 2);
                                                    
                                $html .='<td align="right">'.(isset($r_total_cgst_amount)) ? round($r_total_cgst_amount, 2) : 0 .'</td>';
                                                  
                                              $r_sgst = (isset($value['sgst']) && $value['sgst'] != '') ? $value['sgst'] : 0;
                                                    $R_Sgst = round($r_sgst, 2);
                                                    
                                $html .='<td>'.$R_Sgst.'% </td>'.
                                              
                                               $r_sgst_amount = ($r_taxable_amount*$r_sgst/100);
                                                    $r_total_sgst_amount += $r_sgst_amount;
                                                    $R_Sgst_Amount = round($r_total_sgst_amount, 2);
                                                    
                                $html .='<td align="right">'.(isset($r_total_sgst_amount)) ? round($r_total_sgst_amount, 2) : 0 .'</td>';
                                                 
                                               $r_igst = (isset($value['igst']) && $value['igst'] != '') ? $value['igst'] : 0;
                                                    $R_Igst =  round($r_igst, 2);    
                                                
                                $html .='<td>'.$R_Igst.' % </td>'; 
                                                    
                                               $r_igst_amount = ($r_taxable_amount*$r_igst/100);
                                                    $r_total_igst_amount += $r_igst_amount;
                                                    $R_Igst_Amount = round($r_igst_amount, 2);
                                                    
                                $html .='<td align="right">'.(isset($r_total_igst_amount)) ? round($r_total_igst_amount, 2) : 0 .'</td>';
                                                   
                                                $r_amount = (isset($value['amount']) && $value['amount'] != '') ? $value['amount'] : 0;
                                                    $r_total_amount += $r_amount;
                                                    $R_Total_Amount =  round($r_total_amount, 2);
                                $html .='<td style="background-color:#D6D6D6;text-align: right;">'.(isset($r_total_amount)) ? round($r_total_amount, 2) : 0 .'</td></tr>';
                                             
                                      }
                            
                                 $html .='<tr class="heading" style="background-color:#D6D6D6;border-top:1px solid #181818;"> 
                                    <td colspan="6" style="text-align: right;">Total</td>
                                    <td style="text-align: right;">'.(isset($r_total_qty)) ? round($r_total_qty, 2) : 0 .'</td>
                                    <td style="text-align: right;">'.(isset($r_total_rate)) ? round($r_total_rate, 2) : 0 .'</td>
                                    <td style="text-align: right;">'.(isset($r_total_discount)) ? round($r_total_discount, 2) : 0 .'</td>
                                    <td style="text-align: right;">'.(isset($r_total_taxable_amount)) ? round($r_total_taxable_amount, 2) : 0 .'</td>      
                                    <td colspan="2" style="text-align: right;">'.(isset($r_total_cgst_amount)) ? round($r_total_cgst_amount, 2) : 0 .'</td>
                                    <td colspan="2" style="text-align: right;">'.(isset($r_total_sgst_amount)) ? round($r_total_sgst_amount, 2) : 0 .'</td>
                                    <td colspan="2" style="text-align: right;">'.(isset($r_total_igst_amount)) ? round($r_total_igst_amount, 2) : 0 .'</td>
                                    <td style="text-align: right;">'.(isset($r_total_amount)) ? round($r_total_amount, 2) : 0 .'</td>                            
                                </tr>
                            
                            </tbody>
                        </table>
                    </td>
                </tr>';
             }
            // <!----------------------------------------------FOR PRODUCT RETURN SECTION END-------------------------------------------->
         
          $html .='<tr class="">
              <td colspan="2" style="height:10px;"></td>
            </tr>';
            
            // <!----------------------------------------------FOR COUNT TOTAL AMOUNT SECTION START-------------------------------------------->
            $html .='<tr class="">
                  <td colspan="2" style="text-align:right;padding:0px;">
                      <table cellpadding="0" cellspacing="0" border="1" style="font-size:8px;line-height:10px;" frame="box" rules="all">';
                 
                         $final_amount = (isset($data['final_amount']) && $data['final_amount'] != '') ? number_to_word($data['final_amount']) : 'Zero' ;
                         $alltotalamount = (isset($data['alltotalamount']) && $data['alltotalamount'] != '') ? round($data['alltotalamount'], 2) : 0 ;
                         $total_return_amount = (isset($data['total_return_amount']) && $data['total_return_amount'] != '') ? round($data['total_return_amount'], 2) : 0 ;
                 
                $html .='<tr>         
                  <td align="center" colspan="2">
            "Total Invoice Amount in Words:<br> 
                      <b>'.$final_amount.'</b>
                    </td>
                    <td align="right" style="text-align:right;padding:0px;" rowspan="3">
                      <table cellpadding="10" cellspacing="0" border="1" style="font-size:8px;line-height:10px;" frame="box" rules="cols" >
                            <tr style="background-color:#D6D6D6">
                                <td><div style="width:130px;float:left;">Total Amount Befor Tax</div>:</td>
                                <td align="right">'.$alltotalamount.'</td>
                            </tr>
                            <tr style="background-color:#D6D6D6">
                                <td><div style="width:130px;float:left;">Total Return Amount Befor Tax</div>:</td>
                                <td align="right">'.$total_return_amount.'</td>
                            </tr>
                            <tr>';
                                    $courierPer = (isset($data['couriercharge']) && $data['couriercharge'] != '') ? $data['couriercharge'] : 0;
                                    $courierValue = (isset($data['couriercharge_val']) && $data['couriercharge_val'] != '') ? $data['couriercharge_val'] : 0 ;
                            
                $html .= '<td><div style="width:130px;float:left;">Freight/Courier Charge :'.$courierPer.' % </div>: Amount :'.$courierValue.'</td>';
                                
                                        $calculateCourierPerval = ($courierValue*$courierPer/100);
                                        $finalCourierCharge = ($courierValue+$calculateCourierPerval);
                                       $FinalCourierCharge =  round($finalCourierCharge, 2);
                $html .= '<td align="right">'.$FinalCourierCharge.'</td></tr>';
                                
                              $totalcgst = (isset($data['totalcgst']) && $data['totalcgst'] != '') ? round($data['totalcgst'], 2) : 0 ;
                              $totalsgst = (isset($data['totalsgst']) && $data['totalsgst'] != '') ? round($data['totalsgst'], 2) : 0 ;
                              $totaligst = (isset($data['totaligst']) && $data['totaligst'] != '') ? round($data['totaligst'], 2) : 0 ;
                              $totaltaxgst = (isset($data['totaltaxgst']) && $data['totaltaxgst'] != '') ? round($data['totaltaxgst'], 2) : 0 ;
                   
                $html .='<tr>
                              <td><div style="width:130px;float:left;">Add CGST</div>:</td>
                                <td align="right">'.$totalcgst.'</td>
                            </tr>
                            <tr>
                              <td><div style="width:130px;float:left;">Add SGST</div>:</td>
                                <td align="right">'.$totalsgst.'</td>
                            </tr>
                            <tr>
                              <td><div style="width:130px;float:left;">Add IGST</div>:</td>
                                <td align="right">'.$totaligst.'</td>
                            </tr>
                            <tr style="background-color:#D6D6D6">
                              <td><div style="width:130px;float:left;">Tax Amount GST</div>:</td>
                                <td align="right">'.$totaltaxgst.'</td>
                            </tr>
                            <tr>';
                                    if(isset($data['discount_type']) && $data['discount_type'] == 'rs'){
                                        $disSign = 'Rs';
                                        $disAmount = (isset($data['discount_rs']) && $data['discount_rs'] != '') ? round($data['discount_rs'], 2) : 0;
                                    }elseif(isset($data['discount_type']) && $data['discount_type'] == 'per'){
                                        $disSign = '%';
                                        $disAmount = (isset($data['discount_per']) && $data['discount_per'] != '') ? round($data['discount_per'], 2) : 0;
                                    }
                                      $DisSign  =(isset($disSign)) ? '('.$disSign.')' : '';
                                      
                                       $disAmount =  (isset($disAmount)) ? $disAmount : 0 ;
                                 $html .='<td>
                                    <div style="width:130px;float:left;">Discount'.$DisSign.'</div>:
                                </td>
                                <td align="right">'.$disAmount.'</td>
                            </tr>
                            <tr>'; 
                                    $notevalue = (isset($data['cr_db_type']) && $data['cr_db_type'] == 'credit') ? 'Credit Note' : 'Debit Note';
                                    $noteAmount = (isset($data['cr_db_val']) && $data['cr_db_val'] != '') ? $data['cr_db_val'] : 0;
                                    $final_amount = (isset($data['final_amount']) && $data['final_amount'] != '') ? round($data['final_amount'], 2) : 0 ;
                                    $pharmacy_name = (isset($data['pharmacy']['pharmacy_name'])) ? $data['pharmacy']['pharmacy_name'] : '';
                                    
                    $html .='<td><div style="width:130px;float:left;">'.$notevalue.'</div>:</td>
                                <td align="right">'.$noteAmount.'
                                </td>
                            </tr>
                            <tr style="background-color:#DEDEDE;color:#070707">
                              <td><div style="width:130px;float:left;font-weight:bold;">Total Amount After Tax</div>:</td>
                                <td align="right" style="font-weight:bold;">'.$final_amount.'</td>
                            </tr>
                             <tr >
                              <td colspan="2" style="border:1px solid #DBDBDB;">&nbsp;</td>
                            </tr>
                           
                            <tr>
                              <td colspan="2" style="vertical-align:top;">
                                Certified that the particulars given above are true and correct,
                                <center><b>For,'.$pharmacy_name.'</b></center>
                                <div style="vertical-align:bottom;font-size:8px;text-align:center;margin-top:20px;">Authorised Signatory</div>
                                </td>
                            </tr>
                        </table>
                    </td>
                 </tr>
                 
                 <tr>
                  <td style="font-size:8px;">
                      <center><b>: Bank Details :</b></center>';

                         if(isset($data['pharmacy_bank_detail']) && !empty($data['pharmacy_bank_detail'])){
                            foreach ($data['pharmacy_bank_detail'] as $key => $value) {
                                $account_number = (isset($value['account_number'])) ? $value['account_number'] : ''; 
                                $ifsc_code = (isset($value['ifsc_code'])) ? $value['ifsc_code'] : '';
                    $html .='<div style="margin-top:10px;">
                                  <div style="width:100px;float:left">Bank Account Number</div>: &nbsp;&nbsp;   ['.$account_number.']
                                </div>    
                                <div style="margin-top:10px;clear:both;">
                                   <div style="width:100px;float:left;">Bank Branch IFSC</div>: &nbsp;&nbsp;   ['.$ifsc_code.']
                                </div>';
                                } 
                         }      
                    
                    $html .='</td>
                    <td rowspan="2" style="vertical-align:bottom;font-size:8px;text-align:center">Common Seal</td>                   
                 </tr> 
                 
                 <tr>
                  <td style="height:50px;vertical-align:top;font-size:8px;"><center><b>: Terms and Conditions :</b></center></td>
                 </tr>
                 </table>
                </td>
            </tr>
            <!----------------------------------------------FOR COUNT TOTAL AMOUNT SECTION END-------------------------------------------->
      
        </table> </div></body></html>';
   
         $sent = smtpmail($data['email'], '', '', $pharmacy_name.' / Tax Billing', $html, '', '');
          } 
    }
    /*------------------------------------- TAX BILLING MAIL-----------------------10-12-2018--------END---------------   */
    
    /*---THIS FUNCTION IS USED TO GET TOTAL CLOSING STOCK AS PER PHARMACY METHOD EG. LIFO, FIFO, AVERAGE AND ACTUAL*/
    // AUTHOR : GAUTAM MAKWANA | CREATED DATE : 10-12-2018
    function getTotalClosingStock($from = null, $to = null, $is_financial = 0){
        global $conn;
        global $pharmacy_id;
        global $financial_id;
        $closing_stock = 0;
        $opening_stock = 0;
        $method = '';
        
        $getMethosQ = "SELECT id,company_close_type FROM pharmacy_profile WHERE id = '".$pharmacy_id."'";
        $getMethosR = mysqli_query($conn, $getMethosQ);
        if($getMethosR && mysqli_num_rows($getMethosR) > 0){
            $getMethosRow = mysqli_fetch_assoc($getMethosR);
            $method = (isset($getMethosRow['company_close_type'])) ? $getMethosRow['company_close_type'] : '';
        }
        
        if(isset($method) && $method == 'LIFO'){
            $getAllProductQ = "SELECT id, opening_stock, ratio FROM product_master WHERE pharmacy_id = '".$pharmacy_id."' ORDER BY id";
            $getAllProductR = mysqli_query($conn, $getAllProductQ);
            if($getAllProductR && mysqli_num_rows($getAllProductR) > 0){
                while($getAllProductRow = mysqli_fetch_assoc($getAllProductR)){
                    $opening_stock += (isset($getAllProductRow['opening_stock']) && $getAllProductRow['opening_stock'] != '') ? $getAllProductRow['opening_stock'] : 0;
                    $ratio = (isset($getAllProductRow['ratio']) && $getAllProductRow['ratio'] != '') ? $getAllProductRow['ratio'] : 0;
                    $last_rate = 0; $total_purchase_qty = 0; $total_sale_qty = 0;
                    
                    $purchaseQ = "SELECT p.id, pd.qty, pd.rate FROM purchase_details pd INNER JOIN purchase p ON pd.purchase_id = p.id WHERE p.pharmacy_id = '".$pharmacy_id."' AND pd.product_id = '".$getAllProductRow['id']."'";
                    if(isset($is_financial) && $is_financial == 1){
                        $purchaseQ .= " AND p.financial_id = '".$financial_id."'";
                    }
                    if((isset($from) && $from != '') && (isset($to) && $to != '')){
                      $purchaseQ .= " AND p.invoice_date >= '".$from."' AND p.invoice_date <= '".$to."'";
                    }
                    $purchaseQ .= " ORDER BY p.id DESC";
                    $purchaseR = mysqli_query($conn, $purchaseQ);
                    if($purchaseR && mysqli_num_rows($purchaseR) > 0){
                        $totalpqty = 0;
                        $i=0;
                        while($purchaseRow = mysqli_fetch_assoc($purchaseR)){
                            if($ratio != '' && $ratio != 0){
                                $totalpqty += (isset($purchaseRow['qty']) && $purchaseRow['qty'] != '') ? ($purchaseRow['qty']*$ratio) : 0;
                            }else{
                                $totalpqty += (isset($purchaseRow['qty']) && $purchaseRow['qty'] != '') ? $purchaseRow['qty'] : 0;
                            }
                            if($i == 0){
                                $last_rate = (isset($purchaseRow['rate']) && $purchaseRow['rate'] != '') ? $purchaseRow['rate'] : 0;
                            }
                            $i++;
                        }
                        $total_purchase_qty = $totalpqty;
                    }
                    
                    $saleQ = "SELECT tb.id, SUM(tbd.qty) as totalQty, tbd.rate FROM tax_billing_details tbd INNER JOIN tax_billing tb ON tbd.tax_bill_id = tb.id WHERE tb.pharmacy_id = '".$pharmacy_id."' AND tbd.product_id = '".$getAllProductRow['id']."'";
                    if(isset($is_financial) && $is_financial == 1){
                        $saleQ .= " AND tb.financial_id = '".$financial_id."'";
                    }
                    if((isset($from) && $from != '') && (isset($to) && $to != '')){
                      $saleQ .= " AND tb.invoice_date >= '".$from."' AND tb.invoice_date <= '".$to."'";
                    }
                    $saleR = mysqli_query($conn, $saleQ);
                    if($saleR && mysqli_num_rows($saleR) > 0){
                        $saleRow = mysqli_fetch_assoc($saleR);
                        $total_sale_qty = (isset($saleRow['totalQty']) && $saleRow['totalQty'] != '') ? $saleRow['totalQty'] : 0;
                    }
                    $closing_stock += ($last_rate) * ($total_purchase_qty-$total_sale_qty);
                }
            }
        }elseif(isset($method) && $method == 'FIFO'){
            $getAllProductQ = "SELECT id, opening_stock, ratio FROM product_master WHERE pharmacy_id = '".$pharmacy_id."' ORDER BY id";
            $getAllProductR = mysqli_query($conn, $getAllProductQ);
            if($getAllProductR && mysqli_num_rows($getAllProductR) > 0){
                while($getAllProductRow = mysqli_fetch_assoc($getAllProductR)){
                    $opening_stock += (isset($getAllProductRow['opening_stock']) && $getAllProductRow['opening_stock'] != '') ? $getAllProductRow['opening_stock'] : 0;
                    $ratio = (isset($getAllProductRow['ratio']) && $getAllProductRow['ratio'] != '') ? $getAllProductRow['ratio'] : 0;
                    $first_rate = 0; $total_purchase_qty = 0; $total_sale_qty = 0;
                    
                    $purchaseQ = "SELECT p.id, pd.qty, pd.rate FROM purchase_details pd INNER JOIN purchase p ON pd.purchase_id = p.id WHERE p.pharmacy_id = '".$pharmacy_id."' AND pd.product_id = '".$getAllProductRow['id']."'";
                    if(isset($is_financial) && $is_financial == 1){
                        $purchaseQ .= " AND p.financial_id = '".$financial_id."'";
                    }
                    if((isset($from) && $from != '') && (isset($to) && $to != '')){
                      $purchaseQ .= " AND p.invoice_date >= '".$from."' AND p.invoice_date <= '".$to."'";
                    }
                    $purchaseQ .= " ORDER BY p.id ASC";
                    $purchaseR = mysqli_query($conn, $purchaseQ);
                    if($purchaseR && mysqli_num_rows($purchaseR) > 0){
                        $totalpqty = 0;
                        $j=0;
                        while($purchaseRow = mysqli_fetch_assoc($purchaseR)){
                            if($ratio != '' && $ratio != 0){
                                $totalpqty += (isset($purchaseRow['qty']) && $purchaseRow['qty'] != '') ? ($purchaseRow['qty']*$ratio) : 0;
                            }else{
                                $totalpqty += (isset($purchaseRow['qty']) && $purchaseRow['qty'] != '') ? $purchaseRow['qty'] : 0;
                            }
                            if($j == 0){
                                $first_rate = (isset($purchaseRow['rate']) && $purchaseRow['rate'] != '') ? $purchaseRow['rate'] : 0;
                            }
                            $j++;
                        }
                        $total_purchase_qty = $totalpqty;
                    }
                    
                    $saleQ = "SELECT tb.id, SUM(tbd.qty) as totalQty, tbd.rate FROM tax_billing_details tbd INNER JOIN tax_billing tb ON tbd.tax_bill_id = tb.id WHERE tb.pharmacy_id = '".$pharmacy_id."' AND tbd.product_id = '".$getAllProductRow['id']."'";
                    if(isset($is_financial) && $is_financial == 1){
                        $saleQ .= " AND tb.financial_id = '".$financial_id."'";
                    }
                    if((isset($from) && $from != '') && (isset($to) && $to != '')){
                      $saleQ .= " AND tb.invoice_date >= '".$from."' AND tb.invoice_date <= '".$to."'";
                    }
                    $saleR = mysqli_query($conn, $saleQ);
                    if($saleR && mysqli_num_rows($saleR) > 0){
                        $saleRow = mysqli_fetch_assoc($saleR);
                        $total_sale_qty = (isset($saleRow['totalQty']) && $saleRow['totalQty'] != '') ? $saleRow['totalQty'] : 0;
                    }
                    $closing_stock += ($first_rate) * ($total_purchase_qty-$total_sale_qty);
                }
            }
        }elseif(isset($method) && $method == 'AVERAGE'){
            $getAllProductQ = "SELECT id, opening_stock, ratio FROM product_master WHERE pharmacy_id = '".$pharmacy_id."' ORDER BY id";
            $getAllProductR = mysqli_query($conn, $getAllProductQ);
            if($getAllProductR && mysqli_num_rows($getAllProductR) > 0){
                while($getAllProductRow = mysqli_fetch_assoc($getAllProductR)){
                    $opening_stock += (isset($getAllProductRow['opening_stock']) && $getAllProductRow['opening_stock'] != '') ? $getAllProductRow['opening_stock'] : 0;
                    $ratio = (isset($getAllProductRow['ratio']) && $getAllProductRow['ratio'] != '' && $getAllProductRow['ratio'] != 0) ? $getAllProductRow['ratio'] : 1;
                    $total_purchase_qty = 0; $total_purchase_rate = 0; $total_purchase_row = 0; $total_sale_qty = 0;
                    
                    $purchaseQ = "SELECT SUM(pd.qty) as totalqty, SUM(pd.rate) as totalrate, COUNT(pd.id) as totalrow FROM purchase_details pd INNER JOIN purchase p ON pd.purchase_id = p.id WHERE p.pharmacy_id = '".$pharmacy_id."' AND pd.product_id = '".$getAllProductRow['id']."'";
                    if(isset($is_financial) && $is_financial == 1){
                        $purchaseQ .= " AND p.financial_id = '".$financial_id."'";
                    }
                    if((isset($from) && $from != '') && (isset($to) && $to != '')){
                      $purchaseQ .= " AND p.invoice_date >= '".$from."' AND p.invoice_date <= '".$to."'";
                    }
                    $purchaseR = mysqli_query($conn, $purchaseQ);
                    if($purchaseR && mysqli_num_rows($purchaseR) > 0){
                        $purchaseRow = mysqli_fetch_assoc($purchaseR);
                        $total_purchase_qty = (isset($purchaseRow['totalqty']) && $purchaseRow['totalqty'] != '') ? ($purchaseRow['totalqty']*$ratio) : 0;
                        $total_purchase_rate = (isset($purchaseRow['totalrate']) && $purchaseRow['totalrate'] != '') ? $purchaseRow['totalrate'] : 0;
                        $total_purchase_row = (isset($purchaseRow['totalrow']) && $purchaseRow['totalrow'] != '') ? $purchaseRow['totalrow'] : 0;
                    }
                    
                    $saleQ = "SELECT tb.id, SUM(tbd.qty) as totalQty, tbd.rate FROM tax_billing_details tbd INNER JOIN tax_billing tb ON tbd.tax_bill_id = tb.id WHERE tb.pharmacy_id = '".$pharmacy_id."' AND tbd.product_id = '".$getAllProductRow['id']."'";
                    if(isset($is_financial) && $is_financial == 1){
                        $saleQ .= " AND tb.financial_id = '".$financial_id."'";
                    }
                    if((isset($from) && $from != '') && (isset($to) && $to != '')){
                      $saleQ .= " AND tb.invoice_date >= '".$from."' AND tb.invoice_date <= '".$to."'";
                    }
                    $saleR = mysqli_query($conn, $saleQ);
                    if($saleR && mysqli_num_rows($saleR) > 0){
                        $saleRow = mysqli_fetch_assoc($saleR);
                        $total_sale_qty = (isset($saleRow['totalQty']) && $saleRow['totalQty'] != '') ? $saleRow['totalQty'] : 0;
                    }
                    $average = @($total_purchase_rate/$total_purchase_row);
                    $closing_stock += ($average) * ($total_purchase_qty-$total_sale_qty);
                }
            }
        }elseif(isset($method) && $method == 'ACTUAL'){
            $getAllProductQ = "SELECT id, opening_stock, ratio FROM product_master WHERE pharmacy_id = '".$pharmacy_id."' ORDER BY id";
            $getAllProductR = mysqli_query($conn, $getAllProductQ);
            if($getAllProductR && mysqli_num_rows($getAllProductR) > 0){
                while($getAllProductRow = mysqli_fetch_assoc($getAllProductR)){
                    $opening_stock += (isset($getAllProductRow['opening_stock']) && $getAllProductRow['opening_stock'] != '') ? $getAllProductRow['opening_stock'] : 0;
                    $ratio = (isset($getAllProductRow['ratio']) && $getAllProductRow['ratio'] != '' && $getAllProductRow['ratio'] != 0) ? $getAllProductRow['ratio'] : 1;
                    $total_purchase_qty = 0; $total_purchase_rate = 0; $total_sale_qty = 0;
                    
                    $purchaseQ = "SELECT SUM(pd.qty) as totalqty, SUM(pd.rate) as totalrate, COUNT(pd.id) as totalrow FROM purchase_details pd INNER JOIN purchase p ON pd.purchase_id = p.id WHERE p.pharmacy_id = '".$pharmacy_id."' AND pd.product_id = '".$getAllProductRow['id']."'";
                    if(isset($is_financial) && $is_financial == 1){
                        $purchaseQ .= " AND p.financial_id = '".$financial_id."'";
                    }
                    if((isset($from) && $from != '') && (isset($to) && $to != '')){
                      $purchaseQ .= " AND p.invoice_date >= '".$from."' AND p.invoice_date <= '".$to."'";
                    }
                    $purchaseR = mysqli_query($conn, $purchaseQ);
                    if($purchaseR && mysqli_num_rows($purchaseR) > 0){
                        $purchaseRow = mysqli_fetch_assoc($purchaseR);
                        $total_purchase_qty = (isset($purchaseRow['totalqty']) && $purchaseRow['totalqty'] != '') ? ($purchaseRow['totalqty']*$ratio) : 0;
                        $total_purchase_rate = (isset($purchaseRow['totalrate']) && $purchaseRow['totalrate'] != '') ? $purchaseRow['totalrate'] : 0;
                    }
                    
                    $saleQ = "SELECT tb.id, SUM(tbd.qty) as totalQty, tbd.rate FROM tax_billing_details tbd INNER JOIN tax_billing tb ON tbd.tax_bill_id = tb.id WHERE tb.pharmacy_id = '".$pharmacy_id."' AND tbd.product_id = '".$getAllProductRow['id']."'";
                    if(isset($is_financial) && $is_financial == 1){
                        $saleQ .= " AND tb.financial_id = '".$financial_id."'";
                    }
                    if((isset($from) && $from != '') && (isset($to) && $to != '')){
                      $saleQ .= " AND tb.invoice_date >= '".$from."' AND tb.invoice_date <= '".$to."'";
                    }
                    $saleR = mysqli_query($conn, $saleQ);
                    if($saleR && mysqli_num_rows($saleR) > 0){
                        $saleRow = mysqli_fetch_assoc($saleR);
                        $total_sale_qty = (isset($saleRow['totalQty']) && $saleRow['totalQty'] != '') ? $saleRow['totalQty'] : 0;
                    }
                    $closing = ($total_purchase_qty-$total_sale_qty);
                    $closing_stock += ($total_purchase_rate) * ($closing);
                }
            }
        }
        
        $data['mothod'] = $method;
        $data['closing_stock'] = $closing_stock;
        $data['opening_stock'] = $opening_stock;
        return $data;
    }
    /*-----------------------------------------END getTotalClosingStock FUNCTION-------------------------*/
    /*---THIS FUNCTION IS USED TO DELETE ADMIN - Kartik Champaneriya - 14-12-2018 - START----*/ 
    
    function topbar_pharmacy(){
        global $conn;
        $array_pharmacy = array();
        if($_SESSION['auth']['user_type'] == "owner"){
            $user_id = (isset($_SESSION['auth']['id'])) ? $_SESSION['auth']['id'] : '';
            $pharmacy_query = "SELECT * FROM `pharmacy_profile` WHERE created_by = '".$user_id."'";
            $pharmacy_result = mysqli_query($conn,$pharmacy_query);
            if($pharmacy_result && mysqli_num_rows($pharmacy_result) > 0){
                while ($pharmacy_row = mysqli_fetch_array($pharmacy_result)) {
                    $array_pharmacy[] = array("id"=>$pharmacy_row['id'],"name"=>$pharmacy_row['pharmacy_name']);
                }
            }
        }else if($_SESSION['auth']['user_type'] == "admin"){
            $multiple_pharmacy_id = $_SESSION['auth']['multiple_pharmacy_id'];
            $pharmacy_query = "SELECT * FROM `pharmacy_profile` WHERE id IN ($multiple_pharmacy_id)";
            $pharmacy_result = mysqli_query($conn,$pharmacy_query);
            if($pharmacy_result && mysqli_num_rows($pharmacy_result) > 0){
                while ($pharmacy_row = mysqli_fetch_array($pharmacy_result)) {
                    $array_pharmacy[] = array("id"=>$pharmacy_row['id'],"name"=>$pharmacy_row['pharmacy_name']);
                }
            }
        }else if($_SESSION['auth']['user_type'] == "user"){
            $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
            $pharmacy_query = "SELECT * FROM `pharmacy_profile` WHERE id = '".$pharmacy_id."'";
            $pharmacy_result = mysqli_query($conn,$pharmacy_query);
            if($pharmacy_result && mysqli_num_rows($pharmacy_result) > 0){
                while ($pharmacy_row = mysqli_fetch_array($pharmacy_result)) {
                    $array_pharmacy[] = array("id"=>$pharmacy_row['id'],"name"=>$pharmacy_row['pharmacy_name']);
                }
            }
        }
    return $array_pharmacy;     
    }
    
    /*---THIS FUNCTION IS USED TO DELETE ADMIN - Kartik Champaneriya - 14-12-2018 - END----*/
    // THIS FUNCTION IS USED TO DELETE LEDGER - 15-12-2018 By KARTIK
    function deleteproduct($id = null){
        global $conn;
        $data['status'] = 0;
        $data['message'] = 'Record Delete Fail! Try Again.';
        if(isset($id) && $id != ''){
            $is_delete = 0;
            
            /*-----------------PURCHASE START--------------------*/
            $purchaseQ = "SELECT * FROM `purchase_details` WHERE product_id='".$id."'";
            $purchaseR = mysqli_query($conn, $purchaseQ);
            if($purchaseR && mysqli_num_rows($purchaseR) > 0){
               $is_delete = 1; 
            }
            /*-----------------PURCHASE END--------------------*/
            
            
            /*-----------------SALE BILL START--------------------*/
            $saleQ = "SELECT * FROM `tax_billing_details` WHERE product_id='".$id."'";
            $saleR = mysqli_query($conn, $saleQ);
            if($saleR && mysqli_num_rows($saleR) > 0){
               $is_delete = 1; 
            }
            /*-----------------SALE BILL END--------------------*/
            
            
            /*-----------------Self Consumption START--------------------*/
            $selfQ = "SELECT * FROM `self_consumption` WHERE product_id='".$id."'";
            $selfR = mysqli_query($conn, $selfQ);
            if($selfR && mysqli_num_rows($selfR) > 0){
               $is_delete = 1; 
            }
            /*-----------------Self Consumption END--------------------*/
            
            
            /*-----------------Adjustment START--------------------*/
            $adjustmentQ = "SELECT * FROM `adjustment` WHERE product_id='".$id."'";
            $adjustmentR = mysqli_query($conn, $adjustmentQ);
            if($adjustmentR && mysqli_num_rows($adjustmentR) > 0){
               $is_delete = 1; 
            }
            /*-----------------Adjustment END--------------------*/
            
            /*-----------------Purchase Orders START--------------------*/
            $ordersQ = "SELECT * FROM `orders` WHERE product_id='".$id."'";
            $ordersR = mysqli_query($conn, $ordersQ);
            if($ordersR && mysqli_num_rows($ordersR) > 0){
               $is_delete = 1; 
            }
            /*-----------------Purchase Orders END--------------------*/
            
            /*-----------------Sales Orders START--------------------*/
            $salesQ = "SELECT * FROM `sales_order` WHERE product_id='".$id."'";
            $salesR = mysqli_query($conn, $salesQ);
            if($salesR && mysqli_num_rows($salesR) > 0){
               $is_delete = 1; 
            }
            /*-----------------Sales Orders END--------------------*/
            
            /*-----------------SALE ESTIMATE START--------------------*/
            $estimateQ = "SELECT * FROM `sales_estimate` WHERE product_id='".$id."'";
            $estimateR = mysqli_query($conn, $estimateQ);
            if($estimateR && mysqli_num_rows($estimateR) > 0){
               $is_delete = 1; 
            }
            /*-----------------Sales ESTIMATE END--------------------*/
            
            /*-----------------SALE Templates START--------------------*/
            $templateQ = "SELECT * FROM `sales_template_detail` WHERE product_id='".$id."'";
            $templateR = mysqli_query($conn, $templateQ);
            if($templateR && mysqli_num_rows($templateR) > 0){
               $is_delete = 1; 
            }
            /*-----------------Sales Templates END--------------------*/
            
            /*-----------------PURCHASE RETURN START--------------------*/
            $purchase_returnQ = "SELECT * FROM `purchase_return_detail` WHERE product_id='".$id."'";
            $purchase_returnR = mysqli_query($conn, $purchase_returnQ);
            if($purchase_returnR && mysqli_num_rows($purchase_returnR) > 0){
               $is_delete = 1; 
            }
            /*-----------------PURCHASE RETURN END--------------------*/
            
            /*-----------------SALES RETURN START--------------------*/
            $sale_returnQ = "SELECT * FROM `sale_return` WHERE product_id='".$id."'";
            $sale_returnR = mysqli_query($conn, $sale_returnQ);
            if($sale_returnR && mysqli_num_rows($sale_returnR) > 0){
               $is_delete = 1; 
            }
            /*-----------------SALES RETURN END--------------------*/
            
            /*----------------- DELIVERY CHALLAN START--------------------*/
            $deliveryQ = "SELECT * FROM `delivery_challan_details` WHERE product_id='".$id."'";
            $deliveryR = mysqli_query($conn, $deliveryQ);
            if($deliveryR && mysqli_num_rows($deliveryR) > 0){
               $is_delete = 1; 
            }
            /*-----------------DELIVERY CHALLAN END--------------------*/
            
            /*----------------- BILL OF SUPPLY START--------------------*/
            $bos_productQ = "SELECT * FROM `bos_product_detail` WHERE product_id='".$id."'";
            $bos_productR = mysqli_query($conn, $bos_productQ);
            if($bos_productR && mysqli_num_rows($bos_productR) > 0){
               $is_delete = 1; 
            }
            /*-----------------BILL OF SUPPLY END--------------------*/
        }
        
        if($is_delete == 0){
            $deleteLedgerQ = "DELETE FROM product_master WHERE id = '".$id."'";
            $deleteLedgerR = mysqli_query($conn, $deleteLedgerQ);
            if($deleteLedgerR){
                $data['status'] = 1;
                $data['message'] = 'Record Delete Succcessfully.';
            }
        }else{
            $data['status'] = 0;
            $data['message'] = 'Record Delete Fail! Already Entry';
        }
        return $data;
    }
    /*-----------------------GET DEFULT % - 28-12-2018 - Kartik Champaneriya - START------------------------*/
    function defult_gst_per(){
        global $conn;
        global $pharmacy_id;
        global $financial_id;
        $gstMaster1Q = "SELECT * FROM `gst_master` WHERE pharmacy_id='".$pharmacy_id."' AND pharmacy_id != 0 AND pharmacy_id IS NOT NULL";
	    $getMaster1R = mysqli_query($conn, $gstMaster1Q);
	    if(mysqli_num_rows($getMaster1R) == 0){
	        $defult_gst_f = [
    	        0 => [
    	                'gst_name' => 'GST 5%',
    	                'igst' => '5',
    	                'cgst' => '2.5',
    	                'sgst' => '2.5'
    	            ],
    	        1 => [
    	                'gst_name' => 'GST 12%',
    	                'igst' => '12',
    	                'cgst' => '6',
    	                'sgst' => '6'
    	            ],
    	       2 => [
    	                'gst_name' => 'GST 18%',
    	                'igst' => '18',
    	                'cgst' => '9',
    	                'sgst' => '9'
    	            ],
    	       3 => [
    	                'gst_name' => 'GST 28%',
    	                'igst' => '28',
    	                'cgst' => '14',
    	                'sgst' => '14'
    	            ]    
    	    ];
	        if(mysqli_num_rows($getMaster1R) <= 0){
	            foreach ($defult_gst_f as $key1 => $value1) {
	                $Inster1Q = "INSERT INTO `gst_master`(`pharmacy_id`, `gst_name`, `igst`, `sgst`, `cgst`, `status`) VALUES ('".$pharmacy_id."','".$value1['gst_name']."','".$value1['igst']."','".$value1['sgst']."','".$value1['cgst']."','1')";
	                $Inster1R = mysqli_query($conn, $Inster1Q);
	            }
	        }
	    }
    }
    /*-----------------------GET DEFULT % - 28-12-2018 - Kartik Champaneriya - END------------------------*/
    function defult_leger(){
        global $conn;
        global $account_flag;
        $pharmacyQ = "SELECT * FROM `ledger_master` WHERE pharmacy_id = '".$_SESSION['auth']['pharmacy_id']."' AND pharmacy_id != 0 AND pharmacy_id IS NOT NULL"; 
    	$pharmacyR = mysqli_query($conn, $pharmacyQ);
    	if($pharmacyR){
    	    if(mysqli_num_rows($pharmacyR) <= 0){
    	       //  Sale Account -> Sale Account -> Sale Account
    	        $sale_acc_flag = (isset($account_flag['SALE_ACC'])) ? $account_flag['SALE_ACC'] : '';
    	        $sale_accountQ = "INSERT INTO ledger_master SET owner_id = '".$_SESSION['auth']['owner_id']."', pharmacy_id = '".$_SESSION['auth']['pharmacy_id']."', financial_id = '".$_SESSION['auth']['financial']."', account_type = '2', name = 'Sale Account', opening_balance = '0', opening_balance_type = 'DB', group_id = '27', customer_role = 'Reseller', acc_flag = '".$sale_acc_flag."', status = '1', under = '1'";
    	        $sale_accountR = mysqli_query($conn, $sale_accountQ);
    	        
    	        /// Sale Account -> Sale Account  -> Sale Account OGS
    	        $sale_acc_ogs_flag = (isset($account_flag['SALE_ACC_OGS'])) ? $account_flag['SALE_ACC_OGS'] : '';
    	        $sale_account_ogsQ = "INSERT INTO ledger_master SET owner_id = '".$_SESSION['auth']['owner_id']."', pharmacy_id = '".$_SESSION['auth']['pharmacy_id']."', financial_id = '".$_SESSION['auth']['financial']."', account_type = '2', name = 'Sale Account OGS', opening_balance = '0', opening_balance_type = 'DB', group_id = '27', customer_role = 'Reseller', acc_flag = '".$sale_acc_ogs_flag."', status = '1', under = '1'";
    	        $sale_account_ogsR = mysqli_query($conn, $sale_account_ogsQ);
    	        
    	        /// Purchse Account -> Purchse Account -> Purchase Account
    	        $purchase_acc_flag = (isset($account_flag['PURCHASE_ACC'])) ? $account_flag['PURCHASE_ACC'] : '';
    	        $purchase_accountQ = "INSERT INTO ledger_master SET owner_id = '".$_SESSION['auth']['owner_id']."', pharmacy_id = '".$_SESSION['auth']['pharmacy_id']."', financial_id = '".$_SESSION['auth']['financial']."', account_type = '3',  name = 'Purchase Account', opening_balance = '0', opening_balance_type = 'CR', group_id = '26', under = '1', customer_role = 'Reseller', acc_flag = '".$purchase_acc_flag."', status = '1'";
    	        $sale_account_ogsR = mysqli_query($conn, $purchase_accountQ);
    	        
    	        /// Purchse Account -> Purchase Account -> Purchase Account OGS
    	        $purchase_acc_ogs_flag = (isset($account_flag['PURCHASE_ACC_OGS'])) ? $account_flag['PURCHASE_ACC_OGS'] : '';
    	        $purchse_account_ogsQ = "INSERT INTO ledger_master SET owner_id = '".$_SESSION['auth']['owner_id']."', pharmacy_id = '".$_SESSION['auth']['pharmacy_id']."', financial_id = '".$_SESSION['auth']['financial']."', account_type = '3',  name = 'Purchase Account OGS', opening_balance = '0', opening_balance_type = 'CR', group_id = '26', under = '1', customer_role = 'Reseller', acc_flag = '".$purchase_acc_ogs_flag."', status = '1'";
    	        $purchse_account_ogsR = mysqli_query($conn, $purchse_account_ogsQ);
    	        
    	        /// Tax account -> Duties & taxes -> GST On Purchase
    	        $gst_on_purchase_flag = (isset($account_flag['GST_ON_PURCHASE'])) ? $account_flag['GST_ON_PURCHASE'] : '';
    	        $tax_purchase_gstQ = "INSERT INTO ledger_master SET owner_id = '".$_SESSION['auth']['owner_id']."', pharmacy_id = '".$_SESSION['auth']['pharmacy_id']."', financial_id = '".$_SESSION['auth']['financial']."', account_type = '4', name = 'GST On Purchase', opening_balance = '0', opening_balance_type = 'CR', group_id = '12', under = '1', customer_role = 'Reseller', acc_flag = '".$gst_on_purchase_flag."', status = '1'";
    	        $tax_purchase_gstR = mysqli_query($conn, $tax_purchase_gstQ);
    	        
    	        /// Tax account -> Duties & taxes -> IGST On Purchase
    	        $igst_on_purchase_flag = (isset($account_flag['IGST_ON_PURCHASE'])) ? $account_flag['IGST_ON_PURCHASE'] : '';
    	        $tax_purchase_igstQ = "INSERT INTO ledger_master SET owner_id = '".$_SESSION['auth']['owner_id']."', pharmacy_id = '".$_SESSION['auth']['pharmacy_id']."', financial_id = '".$_SESSION['auth']['financial']."', account_type = '4', name = 'IGST On Purchase', opening_balance = '0', opening_balance_type = 'CR', group_id = '12', under = '1', customer_role = 'Reseller', acc_flag = '".$igst_on_purchase_flag."', status = '1'";
    	        $tax_purchase_igstR = mysqli_query($conn, $tax_purchase_igstQ);
    	        
    	        /// Tax account -> Duties & taxes -> GST On Sales
    	        $gst_on_sale_flag = (isset($account_flag['GST_ON_SALE'])) ? $account_flag['GST_ON_SALE'] : '';
    	        $tax_sale_gstQ = "INSERT INTO ledger_master SET owner_id = '".$_SESSION['auth']['owner_id']."', pharmacy_id = '".$_SESSION['auth']['pharmacy_id']."', financial_id = '".$_SESSION['auth']['financial']."', account_type = '4', name = 'GST On Sales', opening_balance = '0', opening_balance_type = 'DB', group_id = '12', under = '1', customer_role = 'Reseller', acc_flag = '".$gst_on_sale_flag."', status = '1'";
    	        $tax_sale_gstR = mysqli_query($conn, $tax_sale_gstQ);
    	        
    	        /// Tax account -> Duties & taxes -> IGST On Sales
    	        $igst_on_sale_flag = (isset($account_flag['IGST_ON_SALE'])) ? $account_flag['IGST_ON_SALE'] : '';
    	        $tax_sale_igstQ = "INSERT INTO ledger_master SET owner_id = '".$_SESSION['auth']['owner_id']."', pharmacy_id = '".$_SESSION['auth']['pharmacy_id']."', financial_id = '".$_SESSION['auth']['financial']."', account_type = '4', name = 'IGST On Sales', opening_balance = '0', opening_balance_type = 'DB', group_id = '12', under = '1', customer_role = 'Reseller', acc_flag = '".$igst_on_sale_flag."', status = '1'";
    	        $tax_sale_igstR = mysqli_query($conn, $tax_sale_igstQ);
    	        
    	        /// Tax account -> Duties & taxes -> Tax Liability
    	        $tax_liability_flag = (isset($account_flag['TAX_LIABILITY'])) ? $account_flag['TAX_LIABILITY'] : '';
    	        $tax_liability_igstQ = "INSERT INTO ledger_master SET owner_id = '".$_SESSION['auth']['owner_id']."', pharmacy_id = '".$_SESSION['auth']['pharmacy_id']."', financial_id = '".$_SESSION['auth']['financial']."', account_type = '4', name = 'Tax Liability', opening_balance = '0', opening_balance_type = 'CR', group_id = '12', under = '1', customer_role = 'Reseller', acc_flag = '".$tax_liability_flag."', status = '1'";
    	        $tax_liability_igstR = mysqli_query($conn, $tax_liability_igstQ);
    	        
    	        /// Sale Account -> Sale Account  -> General Sale
    	        $general_sale_flag = (isset($account_flag['GENERAL_SALE'])) ? $account_flag['GENERAL_SALE'] : '';
    	        $sale_account_generalQ = "INSERT INTO ledger_master SET owner_id = '".$_SESSION['auth']['owner_id']."', pharmacy_id = '".$_SESSION['auth']['pharmacy_id']."', financial_id = '".$_SESSION['auth']['financial']."', account_type = '2', name = 'General Sale', opening_balance = '0', opening_balance_type = 'DB', group_id = '27', customer_role = 'Reseller', acc_flag = '".$general_sale_flag."', status = '1', under = '1'";
    	        $sale_account_generalR = mysqli_query($conn, $sale_account_generalQ);
    	        
    	        /// Other Account -> Stock In Hand -> Opening Stock
    	        $opening_sale_flag = (isset($account_flag['OPENING_STOCK'])) ? $account_flag['OPENING_STOCK'] : '';
    	        $opening_stockQ = "INSERT INTO ledger_master SET owner_id = '".$_SESSION['auth']['owner_id']."', pharmacy_id = '".$_SESSION['auth']['pharmacy_id']."', financial_id = '".$_SESSION['auth']['financial']."', account_type = '1', name = 'Opening Stock', opening_balance = '0', opening_balance_type = 'DB', group_id = '9', customer_role = 'Reseller', acc_flag = '".$opening_sale_flag."', status = '1', under = '1'";
    	        $opening_stockR = mysqli_query($conn, $opening_stockQ);
    	        
    	        /// Other Account -> Stock In Hand -> Closing Stock
    	        $closing_sale_flag = (isset($account_flag['CLOSING_STOCK'])) ? $account_flag['CLOSING_STOCK'] : '';
    	        $closing_stockQ = "INSERT INTO ledger_master SET owner_id = '".$_SESSION['auth']['owner_id']."', pharmacy_id = '".$_SESSION['auth']['pharmacy_id']."', financial_id = '".$_SESSION['auth']['financial']."', account_type = '1', name = 'Closing Stock', opening_balance = '0', opening_balance_type = 'DB', group_id = '9', customer_role = 'Reseller', acc_flag = '".$closing_sale_flag."', status = '1', under = '1'";
    	        $closing_stockR = mysqli_query($conn, $closing_stockQ);
    	        
    	        // Cash on Hand -> Other Account -> Cash on hand
    	        $cashin_hand_flag = (isset($account_flag['CASH_ON_HAND'])) ? $account_flag['CASH_ON_HAND'] : '';
    	        $cashin_handQ = "INSERT INTO ledger_master SET owner_id = '".$_SESSION['auth']['owner_id']."', pharmacy_id = '".$_SESSION['auth']['pharmacy_id']."', financial_id = '".$_SESSION['auth']['financial']."', account_type = '1', name = 'Cash In Hand', opening_balance = '0', opening_balance_type = 'DB', group_id = '6', customer_role = 'Reseller', acc_flag = '".$cashin_hand_flag."', status = '1', under = '1'";
    	        $cashin_handR = mysqli_query($conn, $cashin_handQ);
    	        
    	        
    	        
    	    }
    	}
    }
    
    /*-----------------------GET MIN QTY OF PRODUCT - 29-12-2018 - GAUTAM MAKWANA - START----------------------*/
    function getMinQtyProduct(){
        global $conn;
        global $pharmacy_id;
        $data = [];

            $query = "SELECT GROUP_CONCAT(id SEPARATOR ',') AS ids, product_name, generic_name, company_code, min_qty FROM product_master WHERE pharmacy_id = '".$pharmacy_id."' GROUP BY product_name ORDER BY product_name";
            $res = mysqli_query($conn, $query);
            if($res && mysqli_num_rows($res) > 0){
                while ($row = mysqli_fetch_assoc($res)) {
                    $min_qty = (isset($row['min_qty']) && $row['min_qty'] != '') ? $row['min_qty'] : 0;
                    $allid = explode(",",$row['ids']);
                    $productData = getAllProductWithCurrentStock('', '', 0, $allid);
                    $currentStock = (!empty($productData)) ? array_sum(array_column($productData, 'currentstock')) : 0;
                    $currentStock = ($currentStock != '') ? $currentStock : 0;
                    
                    if($currentStock < $min_qty){
                        $row['min_qty'] = $min_qty;
                        $row['currentstock'] = $currentStock;
                        $row['suggested_qty'] = ($min_qty-$currentStock);
                        $data[] = $row;
                    }
                }
            }
        return $data;
    }
    /*-----------------------GET MIN QTY OF PRODUCT - 29-12-2018 - GAUTAM MAKWANA - END------------------------*/
    
    /*-----------------------GET TAX LIABILITY REPORT - 31-12-2018 - GAUTAM MAKWANA - START------------------------*/
    function getTaxLiabilityReport($from = null, $to = null, $is_financial = 0){
        global $conn;
        global $pharmacy_id;
        global $financial_id;
        $current_state = (isset($_SESSION['state_code'])) ? $_SESSION['state_code'] : '';
        $data['gst']['purchase'] = [];
        $data['gst']['sale'] = [];
        $data['igst']['purchase'] = [];
        $data['igst']['sale'] = [];


        /*---------------------------SALE CGST START------------------------------------*/
        $tmpArr = [];
        //$saleCgstQ = "SELECT tb.id as tb_id, tb.couriercharge as freight_per, tb.couriercharge_val as freight_amount, tbd.id as tbd_id, tbd.cgst, SUM(tbd.qty*tbd.rate) as taxable_value, SUM(((tbd.qty+tbd.freeqty)*(tbd.rate))*tbd.cgst/100) as tax FROM `tax_billing` tb INNER JOIN tax_billing_details tbd ON tb.id = tbd.tax_bill_id WHERE tbd.cgst IS NOT NULL AND tbd.cgst != 0 AND tb.pharmacy_id = '".$pharmacy_id."'";
        $saleCgstQ = "SELECT tb.id as tb_id, tb.couriercharge as freight_per, tb.couriercharge_val as freight_amount, tbd.id as tbd_id, tbd.cgst, SUM(tbd.totalamount - tbd.gst_tax) as taxable_value, SUM(tbd.gst_tax / 2) as tax FROM `tax_billing` tb INNER JOIN tax_billing_details tbd ON tb.id = tbd.tax_bill_id WHERE tbd.cgst IS NOT NULL AND tbd.cgst != 0 AND tb.pharmacy_id = '".$pharmacy_id."'";
        if(isset($is_financial) && $is_financial == 1){
            $saleCgstQ .= " AND tb.financial_id = '".$financial_id."'";
        }
        if((isset($from) && $from != '') && (isset($to) && $to != '')){
            $saleCgstQ .= " AND tb.invoice_date >= '".$from."' AND tb.invoice_date <= '".$to."'";
        }
        $saleCgstQ .= " GROUP BY tb.id, tbd.cgst";
        //pr($saleCgstQ);exit;
        $saleCgstR = mysqli_query($conn, $saleCgstQ);
        if($saleCgstR && mysqli_num_rows($saleCgstR) > 0){
            while ($saleCgstRow = mysqli_fetch_assoc($saleCgstR)) {
                $gst = (isset($saleCgstRow['cgst']) && $saleCgstRow['cgst'] != '') ? $saleCgstRow['cgst'] : 0;
                $freight_tax = 0;
                if($saleCgstRow['freight_amount'] != 0 && $saleCgstRow['freight_amount'] != '' && ($saleCgstRow['freight_per']/2) == $gst){
                    $freight_tax = ($saleCgstRow['freight_amount']*$saleCgstRow['freight_per']/100);
                }
                $taxable_value = (isset($saleCgstRow['taxable_value']) && $saleCgstRow['taxable_value'] != '') ? $saleCgstRow['taxable_value'] : 0;
                $tax = (isset($saleCgstRow['tax']) && $saleCgstRow['tax'] != '') ? $saleCgstRow['tax'] : 0;
                $tax = ($tax+($freight_tax/2));
                
                if(isset($tmpArr[$gst]['taxable'])){
                    $tmpArr[$gst]['taxable'] += $taxable_value;
                    $tmpArr[$gst]['tax'] += $tax;
                }else{
                    $tmpArr[$gst]['taxable'] = $taxable_value;
                    $tmpArr[$gst]['tax'] = $tax;
                }
            }
            unset($gst, $taxable_value, $tax, $freight_tax);
        }
        //$saleReturnCgstQ = "SELECT sr.id as sr_id, srd.id as srd_id, srd.cgst, SUM(srd.qty*srd.rate) as taxable_value, SUM(((srd.qty+srd.freeqty)*(srd.rate))*srd.cgst/100) as tax FROM `sale_return` sr INNER JOIN sale_return_details srd ON sr.id = srd.sale_return_id WHERE srd.cgst IS NOT NULL AND srd.cgst != 0 AND sr.pharmacy_id = '".$pharmacy_id."'";
        $saleReturnCgstQ = "SELECT sr.id as sr_id, srd.id as srd_id, srd.cgst, SUM(srd.amount - srd.gst_tax) as taxable_value, SUM(srd.gst_tax / 2) as tax FROM `sale_return` sr INNER JOIN sale_return_details srd ON sr.id = srd.sale_return_id WHERE srd.cgst IS NOT NULL AND srd.cgst != 0 AND sr.pharmacy_id = '".$pharmacy_id."'";
        if(isset($is_financial) && $is_financial == 1){
            $saleReturnCgstQ .= " AND sr.financial_id = '".$financial_id."'";
        }
        if((isset($from) && $from != '') && (isset($to) && $to != '')){
            $saleReturnCgstQ .= " AND sr.credit_note_date >= '".$from."' AND sr.credit_note_date <= '".$to."'";
        }
        $saleReturnCgstQ .= " GROUP BY sr.id, srd.cgst";
        $saleReturnCgstR = mysqli_query($conn, $saleReturnCgstQ);
        if($saleReturnCgstR && mysqli_num_rows($saleReturnCgstR) > 0){
            while ($saleReturnCgstRow = mysqli_fetch_assoc($saleReturnCgstR)) {
                $gst = (isset($saleReturnCgstRow['cgst']) && $saleReturnCgstRow['cgst'] != '') ? $saleReturnCgstRow['cgst'] : 0;
                $taxable_value = (isset($saleReturnCgstRow['taxable_value']) && $saleReturnCgstRow['taxable_value'] != '') ? -$saleReturnCgstRow['taxable_value'] : 0;
                $tax = (isset($saleReturnCgstRow['tax']) && $saleReturnCgstRow['tax'] != '') ? -$saleReturnCgstRow['tax'] : 0;

                if(isset($tmpArr[$gst]['taxable'])){
                    $tmpArr[$gst]['taxable'] += $taxable_value;
                    $tmpArr[$gst]['tax'] += $tax;
                }else{
                    $tmpArr[$gst]['taxable'] = $taxable_value;
                    $tmpArr[$gst]['tax'] = $tax;
                }
            }
            unset($gst, $taxable_value, $tax);
        }
        if(!empty($tmpArr)){
            ksort($tmpArr);
            foreach ($tmpArr as $key => $value) {
                $tmp['desc'] = "CGST @".$key."% (".$key."%)";
                $tmp['gst'] = $key;
                $tmp['taxable_value'] = $value['taxable'];
                $tmp['tax'] = $value['tax'];
                $tmp['url'] = 'tax-detail.php?type=sale&gst=cgst&rate='.$key;
                $data['gst']['sale'][] = $tmp;
            }
        }
        unset($tmpArr, $tmp);
        /*---------------------------SALE CGST END------------------------------------*/

        /*---------------------------SALE SGST START------------------------------------*/
        $tmpArr = [];
        //$saleSgstQ = "SELECT tb.id as tb_id, tb.couriercharge as freight_per, tb.couriercharge_val as freight_amount, tbd.id as tbd_id, tbd.sgst, SUM(tbd.qty*tbd.rate) as taxable_value, SUM(((tbd.qty+tbd.freeqty)*(tbd.rate))*tbd.sgst/100) as tax FROM `tax_billing` tb INNER JOIN tax_billing_details tbd ON tb.id = tbd.tax_bill_id WHERE tbd.sgst IS NOT NULL AND tbd.sgst != 0 AND tb.pharmacy_id = '".$pharmacy_id."'";
        $saleSgstQ = "SELECT tb.id as tb_id, tb.couriercharge as freight_per, tb.couriercharge_val as freight_amount, tbd.id as tbd_id, tbd.sgst, SUM(tbd.totalamount - tbd.gst_tax) as taxable_value, SUM(tbd.gst_tax / 2) as tax FROM `tax_billing` tb INNER JOIN tax_billing_details tbd ON tb.id = tbd.tax_bill_id WHERE tbd.sgst IS NOT NULL AND tbd.sgst != 0 AND tb.pharmacy_id = '".$pharmacy_id."'";
        if(isset($is_financial) && $is_financial == 1){
            $saleSgstQ .= " AND tb.financial_id = '".$financial_id."'";
        }
        if((isset($from) && $from != '') && (isset($to) && $to != '')){
            $saleSgstQ .= " AND tb.invoice_date >= '".$from."' AND tb.invoice_date <= '".$to."'";
        }
        $saleSgstQ .= " GROUP BY tb.id, tbd.sgst";
        $saleSgstR = mysqli_query($conn, $saleSgstQ);
        if($saleSgstR && mysqli_num_rows($saleSgstR) > 0){
            while ($saleSgstRow = mysqli_fetch_assoc($saleSgstR)) {
                $gst = (isset($saleSgstRow['sgst']) && $saleSgstRow['sgst'] != '') ? $saleSgstRow['sgst'] : 0;
                $freight_tax = 0;
                if($saleSgstRow['freight_amount'] != 0 && $saleSgstRow['freight_amount'] != '' && ($saleSgstRow['freight_per']/2) == $gst){
                    $freight_tax = ($saleSgstRow['freight_amount']*$saleSgstRow['freight_per']/100);
                }
                $taxable_value = (isset($saleSgstRow['taxable_value']) && $saleSgstRow['taxable_value'] != '') ? $saleSgstRow['taxable_value'] : 0;
                $tax = (isset($saleSgstRow['tax']) && $saleSgstRow['tax'] != '') ? $saleSgstRow['tax'] : 0;
                $tax = ($tax+($freight_tax/2));

                if(isset($tmpArr[$gst]['taxable'])){
                    $tmpArr[$gst]['taxable'] += $taxable_value;
                    $tmpArr[$gst]['tax'] += $tax;
                }else{
                    $tmpArr[$gst]['taxable'] = $taxable_value;
                    $tmpArr[$gst]['tax'] = $tax;
                }
            }
            unset($gst, $taxable_value, $tax, $freight_tax);
        }
        //$saleReturnSgstQ = "SELECT sr.id as sr_id, srd.id as srd_id, srd.sgst, SUM(srd.qty*srd.rate) as taxable_value, SUM(((srd.qty+srd.freeqty)*(srd.rate))*srd.sgst/100) as tax FROM `sale_return` sr INNER JOIN sale_return_details srd ON sr.id = srd.sale_return_id WHERE srd.sgst IS NOT NULL AND srd.sgst != 0 AND sr.pharmacy_id = '".$pharmacy_id."'";
        $saleReturnSgstQ = "SELECT sr.id as sr_id, srd.id as srd_id, srd.sgst, SUM(srd.amount - srd.gst_tax) as taxable_value, SUM(srd.gst_tax / 2) as tax FROM `sale_return` sr INNER JOIN sale_return_details srd ON sr.id = srd.sale_return_id WHERE srd.sgst IS NOT NULL AND srd.sgst != 0 AND sr.pharmacy_id = '".$pharmacy_id."'";
        if(isset($is_financial) && $is_financial == 1){
            $saleReturnSgstQ .= " AND sr.financial_id = '".$financial_id."'";
        }
        if((isset($from) && $from != '') && (isset($to) && $to != '')){
            $saleReturnSgstQ .= " AND sr.credit_note_date >= '".$from."' AND sr.credit_note_date <= '".$to."'";
        }
        $saleReturnSgstQ .= " GROUP BY sr.id, srd.sgst";
        
        $saleReturnSgstR = mysqli_query($conn, $saleReturnSgstQ);
        if($saleReturnSgstR && mysqli_num_rows($saleReturnSgstR) > 0){
            while ($saleReturnSgstRow = mysqli_fetch_assoc($saleReturnSgstR)) {
                $gst = (isset($saleReturnSgstRow['sgst']) && $saleReturnSgstRow['sgst'] != '') ? $saleReturnSgstRow['sgst'] : 0;
                $taxable_value = (isset($saleReturnSgstRow['taxable_value']) && $saleReturnSgstRow['taxable_value'] != '') ? -$saleReturnSgstRow['taxable_value'] : 0;
                $tax = (isset($saleReturnSgstRow['tax']) && $saleReturnSgstRow['tax'] != '') ? -$saleReturnSgstRow['tax'] : 0;

                if(isset($tmpArr[$gst]['taxable'])){
                    $tmpArr[$gst]['taxable'] += $taxable_value;
                    $tmpArr[$gst]['tax'] += $tax;
                }else{
                    $tmpArr[$gst]['taxable'] = $taxable_value;
                    $tmpArr[$gst]['tax'] = $tax;
                }
            }
            unset($gst, $taxable_value, $tax);
        }
        if(!empty($tmpArr)){
            ksort($tmpArr);
            foreach ($tmpArr as $key => $value) {
                $tmp['desc'] = "SGST @".$key."% (".$key."%)";
                $tmp['gst'] = $key;
                $tmp['taxable_value'] = $value['taxable'];
                $tmp['tax'] = $value['tax'];
                $tmp['url'] = 'tax-detail.php?type=sale&gst=sgst&rate='.$key;
                $data['gst']['sale'][] = $tmp;
            }
        }
        unset($tmpArr, $tmp);
        /*---------------------------SALE SGST END------------------------------------*/

        /*---------------------------SALE IGST START------------------------------------*/
        $tmpArr = [];
        //$saleIgstQ = "SELECT tb.id as tb_id, tb.couriercharge as freight_per, tb.couriercharge_val as freight_amount, tbd.id as tbd_id, tbd.igst, SUM(tbd.qty*tbd.rate) as taxable_value, SUM(((tbd.qty+tbd.freeqty)*(tbd.rate))*tbd.igst/100) as tax FROM `tax_billing` tb INNER JOIN tax_billing_details tbd ON tb.id = tbd.tax_bill_id WHERE tbd.igst IS NOT NULL AND tbd.igst != 0 AND tb.pharmacy_id = '".$pharmacy_id."'";
        $saleIgstQ = "SELECT tb.id as tb_id, tb.couriercharge as freight_per, tb.couriercharge_val as freight_amount, tbd.id as tbd_id, tbd.igst, SUM(tbd.totalamount - tbd.gst_tax) as taxable_value, SUM(tbd.gst_tax)) as tax FROM `tax_billing` tb INNER JOIN tax_billing_details tbd ON tb.id = tbd.tax_bill_id WHERE tbd.igst IS NOT NULL AND tbd.igst != 0 AND tb.pharmacy_id = '".$pharmacy_id."'";
        if(isset($is_financial) && $is_financial == 1){
            $saleIgstQ .= " AND tb.financial_id = '".$financial_id."'";
        }
        if((isset($from) && $from != '') && (isset($to) && $to != '')){
            $saleIgstQ .= " AND tb.invoice_date >= '".$from."' AND tb.invoice_date <= '".$to."'";
        }
        $saleIgstQ .= " GROUP BY tb.id, tbd.igst";
        $saleIgstR = mysqli_query($conn, $saleIgstQ);
        if($saleIgstR && mysqli_num_rows($saleIgstR) > 0){
            while ($saleIgstRow = mysqli_fetch_assoc($saleIgstR)) {
                $gst = (isset($saleIgstRow['igst']) && $saleIgstRow['igst'] != '') ? $saleIgstRow['igst'] : 0;
                $freight_tax = 0;
                if($saleIgstRow['freight_amount'] != 0 && $saleIgstRow['freight_amount'] != '' && $saleIgstRow['freight_per'] == $gst){
                    $freight_tax = ($saleIgstRow['freight_amount']*$saleIgstRow['freight_per']/100);
                }
                $taxable_value = (isset($saleIgstRow['taxable_value']) && $saleIgstRow['taxable_value'] != '') ? $saleIgstRow['taxable_value'] : 0;
                $tax = (isset($saleIgstRow['tax']) && $saleIgstRow['tax'] != '') ? $saleIgstRow['tax'] : 0;
                $tax = ($tax+$freight_tax);
                
                if(isset($tmpArr[$gst]['taxable'])){
                    $tmpArr[$gst]['taxable'] += $taxable_value;
                    $tmpArr[$gst]['tax'] += $tax;
                }else{
                    $tmpArr[$gst]['taxable'] = $taxable_value;
                    $tmpArr[$gst]['tax'] = $tax;
                }
            }
            unset($gst, $taxable_value, $tax, $freight_tax);
        }
        //$saleReturnIgstQ = "SELECT sr.id as sr_id, srd.id as srd_id, srd.igst, SUM(srd.qty*srd.rate) as taxable_value, SUM(((srd.qty+srd.freeqty)*(srd.rate))*srd.igst/100) as tax FROM `sale_return` sr INNER JOIN sale_return_details srd ON sr.id = srd.sale_return_id WHERE srd.igst IS NOT NULL AND srd.igst != 0 AND sr.pharmacy_id = '".$pharmacy_id."'";
        $saleReturnIgstQ = "SELECT sr.id as sr_id, srd.id as srd_id, srd.igst, SUM(srd.amount - srd.gst_tax) as taxable_value, SUM(srd.gst_tax) as tax FROM `sale_return` sr INNER JOIN sale_return_details srd ON sr.id = srd.sale_return_id WHERE srd.igst IS NOT NULL AND srd.igst != 0 AND sr.pharmacy_id = '".$pharmacy_id."'";
        if(isset($is_financial) && $is_financial == 1){
            $saleReturnIgstQ .= " AND sr.financial_id = '".$financial_id."'";
        }
        if((isset($from) && $from != '') && (isset($to) && $to != '')){
            $saleReturnIgstQ .= " AND sr.credit_note_date >= '".$from."' AND sr.credit_note_date <= '".$to."'";
        }
        $saleReturnIgstQ .= " GROUP BY sr.id, srd.igst";
        $saleReturnIgstR = mysqli_query($conn, $saleReturnIgstQ);
        if($saleReturnIgstR && mysqli_num_rows($saleReturnIgstR) > 0){
            while ($saleReturnIgstRow = mysqli_fetch_assoc($saleReturnIgstR)) {
                $gst = (isset($saleReturnIgstRow['igst']) && $saleReturnIgstRow['igst'] != '') ? $saleReturnIgstRow['igst'] : 0;
                $taxable_value = (isset($saleReturnIgstRow['taxable_value']) && $saleReturnIgstRow['taxable_value'] != '') ? -$saleReturnIgstRow['taxable_value'] : 0;
                $tax = (isset($saleReturnIgstRow['tax']) && $saleReturnIgstRow['tax'] != '') ? -$saleReturnIgstRow['tax'] : 0;

                if(isset($tmpArr[$gst]['taxable'])){
                    $tmpArr[$gst]['taxable'] += $taxable_value;
                    $tmpArr[$gst]['tax'] += $tax;
                }else{
                    $tmpArr[$gst]['taxable'] = $taxable_value;
                    $tmpArr[$gst]['tax'] = $tax;
                }
            }
            unset($gst, $taxable_value, $tax);
        }
        if(!empty($tmpArr)){
            ksort($tmpArr);
            foreach ($tmpArr as $key => $value) {
                $tmp['desc'] = "IGST @".$key."% (".$key."%)";
                $tmp['gst'] = $key;
                $tmp['taxable_value'] = $value['taxable'];
                $tmp['tax'] = $value['tax'];
                $tmp['url'] = 'tax-detail.php?type=sale&gst=igst&rate='.$key;
                $data['igst']['sale'][] = $tmp;
            }
        }
        unset($tmpArr, $tmp);
        /*---------------------------SALE IGST END------------------------------------*/


        /*---------------------------PURCHASE CGST START------------------------------------*/
        $tmpArr = [];
        $purchaseCgstQ = "SELECT p.id as p_id, pd.id as pd_id, p.courier as freight_per, p.total_courier as freight_amount, pd.f_cgst, SUM(pd.qty*pd.f_rate) as taxable_value, SUM(((pd.qty+pd.free_qty)*(pd.f_rate))*pd.f_cgst/100) as tax FROM `purchase` p INNER JOIN purchase_details pd ON p.id = pd.purchase_id INNER JOIN ledger_master lg ON p.vendor = lg.id INNER JOIN own_states st ON lg.state = st.id WHERE pd.f_cgst IS NOT NULL AND pd.f_cgst != 0 AND st.state_code_gst = '".$current_state."' AND p.pharmacy_id = '".$pharmacy_id."'";
        
        if(isset($is_financial) && $is_financial == 1){
            $purchaseCgstQ .= " AND p.financial_id = '".$financial_id."'";
        }
        if((isset($from) && $from != '') && (isset($to) && $to != '')){
            $purchaseCgstQ .= " AND p.invoice_date >= '".$from."' AND p.invoice_date <= '".$to."'";
        }
        $purchaseCgstQ .= " GROUP BY p.id, pd.f_cgst";
        $purchaseCgstR = mysqli_query($conn, $purchaseCgstQ);
        if($purchaseCgstR && mysqli_num_rows($purchaseCgstR) > 0){
            while ($purchaseCgstRow = mysqli_fetch_assoc($purchaseCgstR)) {
                $gst = (isset($purchaseCgstRow['f_cgst']) && $purchaseCgstRow['f_cgst'] != '') ? $purchaseCgstRow['f_cgst'] : 0;
                $freight_tax = 0;
                if($purchaseCgstRow['freight_amount'] != 0 && $purchaseCgstRow['freight_amount'] != '' && $gst == ($purchaseCgstRow['freight_per']/2)){
                    $freight_tax = ($purchaseCgstRow['freight_amount']*$purchaseCgstRow['freight_per']/100);
                }
                $taxable_value = (isset($purchaseCgstRow['taxable_value']) && $purchaseCgstRow['taxable_value'] != '') ? -$purchaseCgstRow['taxable_value'] : 0;
                $tax = (isset($purchaseCgstRow['tax']) && $purchaseCgstRow['tax'] != '') ? $purchaseCgstRow['tax'] : 0;
                $tax = -($tax+($freight_tax/2));
                
                if(isset($tmpArr[$gst]['taxable'])){
                    $tmpArr[$gst]['taxable'] += $taxable_value;
                    $tmpArr[$gst]['tax'] += $tax;
                }else{
                    $tmpArr[$gst]['taxable'] = $taxable_value;
                    $tmpArr[$gst]['tax'] = $tax;
                }
            }
            unset($gst, $taxable_value, $tax, $freight_tax);
        }
        
        $purchaseReturnCgstQ = "SELECT pr.id as pr_id, prd.id as prd_id, prd.cgst, SUM(prd.qty*prd.final_rate) as taxable_value, SUM(((prd.qty+prd.free_qty)*(prd.final_rate))*prd.cgst/100) as tax FROM `purchase_return` pr INNER JOIN purchase_return_detail prd ON pr.id = prd.pr_id INNER JOIN ledger_master lg ON pr.vendor_id = lg.id INNER JOIN own_states st ON lg.state = st.id WHERE prd.cgst IS NOT NULL AND prd.cgst != 0 AND st.state_code_gst = '".$current_state."' AND pr.pharmacy_id = '".$pharmacy_id."'";
        if(isset($is_financial) && $is_financial == 1){
            $purchaseReturnCgstQ .= " AND pr.financial_id = '".$financial_id."'";
        }
        if((isset($from) && $from != '') && (isset($to) && $to != '')){
            $purchaseReturnCgstQ .= " AND pr.debit_note_date >= '".$from."' AND pr.debit_note_date <= '".$to."'";
        }
        $purchaseReturnCgstQ .= " GROUP BY pr.id, prd.cgst";
        $purchaseReturnCgstR = mysqli_query($conn, $purchaseReturnCgstQ);
        if($purchaseReturnCgstR && mysqli_num_rows($purchaseReturnCgstR) > 0){
            while ($purchaseReturnCgstRow = mysqli_fetch_assoc($purchaseReturnCgstR)) {
                $gst = (isset($purchaseReturnCgstRow['cgst']) && $purchaseReturnCgstRow['cgst'] != '') ? $purchaseReturnCgstRow['cgst'] : 0;
                $taxable_value = (isset($purchaseReturnCgstRow['taxable_value']) && $purchaseReturnCgstRow['taxable_value'] != '') ? $purchaseReturnCgstRow['taxable_value'] : 0;
                $tax = (isset($purchaseReturnCgstRow['tax']) && $purchaseReturnCgstRow['tax'] != '') ? $purchaseReturnCgstRow['tax'] : 0;
                if(isset($tmpArr[$gst]['taxable'])){
                    $tmpArr[$gst]['taxable'] += $taxable_value;
                    $tmpArr[$gst]['tax'] += $tax;
                }else{
                    $tmpArr[$gst]['taxable'] = $taxable_value;
                    $tmpArr[$gst]['tax'] = $tax;
                }
            }
            unset($gst, $taxable_value, $tax);
        }
        if(!empty($tmpArr)){
            ksort($tmpArr);
            foreach ($tmpArr as $key => $value) {
                $tmp['desc'] = "CGST @".$key."% (".$key."%)";
                $tmp['gst'] = $key;
                $tmp['taxable_value'] = $value['taxable'];
                $tmp['tax'] = $value['tax'];
                $tmp['url'] = 'tax-detail.php?type=purchase&gst=cgst&rate='.$key;
                $data['gst']['purchase'][] = $tmp;
            }
            unset($tmpArr, $tmp);
        }
        /*---------------------------PURCHASE CGST END------------------------------------*/

        /*---------------------------PURCHASE SGST START------------------------------------*/
        $tmpArr = [];
        $purchaseSgstQ = "SELECT p.id as p_id, pd.id as pd_id, p.courier as freight_per, p.total_courier as freight_amount, pd.f_sgst, SUM(pd.qty*pd.f_rate) as taxable_value, SUM(((pd.qty+pd.free_qty)*(pd.f_rate))*pd.f_sgst/100) as tax FROM `purchase` p INNER JOIN purchase_details pd ON p.id = pd.purchase_id INNER JOIN ledger_master lg ON p.vendor = lg.id INNER JOIN own_states st ON lg.state = st.id WHERE pd.f_sgst IS NOT NULL AND pd.f_sgst != 0 AND st.state_code_gst = '".$current_state."' AND p.pharmacy_id = '".$pharmacy_id."'";
        if(isset($is_financial) && $is_financial == 1){
            $purchaseSgstQ .= " AND p.financial_id = '".$financial_id."'";
        }
        if((isset($from) && $from != '') && (isset($to) && $to != '')){
            $purchaseSgstQ .= " AND p.invoice_date >= '".$from."' AND p.invoice_date <= '".$to."'";
        }
        $purchaseSgstQ .= " GROUP BY p.id, pd.f_sgst";
        $purchaseSgstR = mysqli_query($conn, $purchaseSgstQ);
        if($purchaseSgstR && mysqli_num_rows($purchaseSgstR) > 0){
            while ($purchaseSgstRow = mysqli_fetch_assoc($purchaseSgstR)) {
                $gst = (isset($purchaseSgstRow['f_sgst']) && $purchaseSgstRow['f_sgst'] != '') ? $purchaseSgstRow['f_sgst'] : 0;
                $freight_tax = 0;
                if($purchaseSgstRow['freight_amount'] != 0 && $purchaseSgstRow['freight_amount'] != '' && $gst == ($purchaseSgstRow['freight_per']/2)){
                    $freight_tax = ($purchaseSgstRow['freight_amount']*$purchaseSgstRow['freight_per']/100);
                }
                $taxable_value = (isset($purchaseSgstRow['taxable_value']) && $purchaseSgstRow['taxable_value'] != '') ? -$purchaseSgstRow['taxable_value'] : 0;
                $tax = (isset($purchaseSgstRow['tax']) && $purchaseSgstRow['tax'] != '') ? $purchaseSgstRow['tax'] : 0;
                $tax = -($tax+($freight_tax/2));

                if(isset($tmpArr[$gst]['taxable'])){
                    $tmpArr[$gst]['taxable'] += $taxable_value;
                    $tmpArr[$gst]['tax'] += $tax;
                }else{
                    $tmpArr[$gst]['taxable'] = $taxable_value;
                    $tmpArr[$gst]['tax'] = $tax;
                }
            }
            unset($gst, $taxable_value, $tax, $freight_tax);
        }
        $purchaseReturnSgstQ = "SELECT pr.id as pr_id, prd.id as prd_id, prd.sgst, SUM(prd.qty*prd.final_rate) as taxable_value, SUM(((prd.qty+prd.free_qty)*(prd.final_rate))*prd.sgst/100) as tax FROM `purchase_return` pr INNER JOIN purchase_return_detail prd ON pr.id = prd.pr_id INNER JOIN ledger_master lg ON pr.vendor_id = lg.id INNER JOIN own_states st ON lg.state = st.id WHERE prd.sgst IS NOT NULL AND prd.sgst != 0 AND st.state_code_gst = '".$current_state."' AND pr.pharmacy_id = '".$pharmacy_id."'";

        if(isset($is_financial) && $is_financial == 1){
            $purchaseReturnSgstQ .= " AND pr.financial_id = '".$financial_id."'";
        }
        if((isset($from) && $from != '') && (isset($to) && $to != '')){
            $purchaseReturnSgstQ .= " AND pr.debit_note_date >= '".$from."' AND pr.debit_note_date <= '".$to."'";
        }
        $purchaseReturnSgstQ .= " GROUP BY pr.id, prd.sgst";
        $purchaseReturnSgstR = mysqli_query($conn, $purchaseReturnSgstQ);
        if($purchaseReturnSgstR && mysqli_num_rows($purchaseReturnSgstR) > 0){
            while ($purchaseReturnSgstRow = mysqli_fetch_assoc($purchaseReturnSgstR)) {
                $gst = (isset($purchaseReturnSgstRow['sgst']) && $purchaseReturnSgstRow['sgst'] != '') ? $purchaseReturnSgstRow['sgst'] : 0;
                $taxable_value = (isset($purchaseReturnSgstRow['taxable_value']) && $purchaseReturnSgstRow['taxable_value'] != '') ? $purchaseReturnSgstRow['taxable_value'] : 0;
                $tax = (isset($purchaseReturnSgstRow['tax']) && $purchaseReturnSgstRow['tax'] != '') ? $purchaseReturnSgstRow['tax'] : 0;

                if(isset($tmpArr[$gst]['taxable'])){
                    $tmpArr[$gst]['taxable'] += $taxable_value;
                    $tmpArr[$gst]['tax'] += $tax;
                }else{
                    $tmpArr[$gst]['taxable'] = $taxable_value;
                    $tmpArr[$gst]['tax'] = $tax;
                }
            }
            unset($gst, $taxable_value, $tax);
        }
        if(!empty($tmpArr)){
            ksort($tmpArr);
            foreach ($tmpArr as $key => $value) {
                $tmp['desc'] = "SGST @".$key."% (".$key."%)";
                $tmp['gst'] = $key;
                $tmp['taxable_value'] = $value['taxable'];
                $tmp['tax'] = $value['tax'];
                $tmp['url'] = 'tax-detail.php?type=purchase&gst=sgst&rate='.$key;
                $data['gst']['purchase'][] = $tmp;
            }
            unset($tmpArr, $tmp);
        }
        /*---------------------------PURCHASE SGST END------------------------------------*/

        /*---------------------------PURCHASE IGST START------------------------------------*/
        $tmpArr = [];
        $purchaseIgstQ = "SELECT p.id as p_id, pd.id as pd_id, p.courier as freight_per, p.total_courier as freight_amount, pd.f_igst, SUM(pd.qty*pd.f_rate) as taxable_value, SUM(((pd.qty+pd.free_qty)*(pd.f_rate))*pd.f_igst/100) as tax FROM `purchase` p INNER JOIN purchase_details pd ON p.id = pd.purchase_id INNER JOIN ledger_master lg ON p.vendor = lg.id INNER JOIN own_states st ON lg.state = st.id WHERE pd.f_igst IS NOT NULL AND pd.f_igst != 0 AND st.state_code_gst != '".$current_state."' AND p.pharmacy_id = '".$pharmacy_id."'";

        if(isset($is_financial) && $is_financial == 1){
            $purchaseIgstQ .= " AND p.financial_id = '".$financial_id."'";
        }
        if((isset($from) && $from != '') && (isset($to) && $to != '')){
            $purchaseIgstQ .= " AND p.invoice_date >= '".$from."' AND p.invoice_date <= '".$to."'";
        }
        $purchaseIgstQ .= " GROUP BY p.id, pd.f_igst";
        $purchaseIgstR = mysqli_query($conn, $purchaseIgstQ);
        if($purchaseIgstR && mysqli_num_rows($purchaseIgstR) > 0){
            while ($purchaseIgstRow = mysqli_fetch_assoc($purchaseIgstR)) {
                $gst = (isset($purchaseIgstRow['f_igst']) && $purchaseIgstRow['f_igst'] != '') ? $purchaseIgstRow['f_igst'] : 0;
                $freight_tax = 0;
                if($purchaseIgstRow['freight_amount'] != 0 && $purchaseIgstRow['freight_amount'] != '' && $gst == $purchaseIgstRow['freight_per']){
                    $freight_tax = ($purchaseIgstRow['freight_amount']*$purchaseIgstRow['freight_per']/100);
                }
                $taxable_value = (isset($purchaseIgstRow['taxable_value']) && $purchaseIgstRow['taxable_value'] != '') ? -$purchaseIgstRow['taxable_value'] : 0;
                $tax = (isset($purchaseIgstRow['tax']) && $purchaseIgstRow['tax'] != '') ? $purchaseIgstRow['tax'] : 0;
                $tax = -($tax+$freight_tax);
                
                if(isset($tmpArr[$gst]['taxable'])){
                    $tmpArr[$gst]['taxable'] += $taxable_value;
                    $tmpArr[$gst]['tax'] += $tax;
                }else{
                    $tmpArr[$gst]['taxable'] = $taxable_value;
                    $tmpArr[$gst]['tax'] = $tax;
                }
            }
            unset($gst, $taxable_value, $tax, $freight_tax);
        }
        $purchaseReturnIgstQ = "SELECT pr.id as pr_id, prd.id as prd_id, prd.igst, SUM(prd.qty*prd.final_rate) as taxable_value, SUM(((prd.qty+prd.free_qty)*(prd.final_rate))*prd.igst/100) as tax FROM `purchase_return` pr INNER JOIN purchase_return_detail prd ON pr.id = prd.pr_id INNER JOIN ledger_master lg ON pr.vendor_id = lg.id INNER JOIN own_states st ON lg.state = st.id WHERE prd.igst IS NOT NULL AND prd.igst != 0 AND st.state_code_gst != '".$current_state."' AND pr.pharmacy_id = '".$pharmacy_id."'";
        if(isset($is_financial) && $is_financial == 1){
            $purchaseReturnIgstQ .= " AND pr.financial_id = '".$financial_id."'";
        }
        if((isset($from) && $from != '') && (isset($to) && $to != '')){
            $purchaseReturnIgstQ .= " AND pr.debit_note_date >= '".$from."' AND pr.debit_note_date <= '".$to."'";
        }
        $purchaseReturnIgstQ .= " GROUP BY pr.id, prd.igst";
        $purchaseReturnIgstR = mysqli_query($conn, $purchaseReturnIgstQ);
        if($purchaseReturnIgstR && mysqli_num_rows($purchaseReturnIgstR) > 0){
            while ($purchaseReturnIgstRow = mysqli_fetch_assoc($purchaseReturnIgstR)) {
                $gst = (isset($purchaseReturnIgstRow['igst']) && $purchaseReturnIgstRow['igst'] != '') ? $purchaseReturnIgstRow['igst'] : 0;
                $taxable_value = (isset($purchaseReturnIgstRow['taxable_value']) && $purchaseReturnIgstRow['taxable_value'] != '') ? $purchaseReturnIgstRow['taxable_value'] : 0;
                $tax = (isset($purchaseReturnIgstRow['tax']) && $purchaseReturnIgstRow['tax'] != '') ? $purchaseReturnIgstRow['tax'] : 0;

                if(isset($tmpArr[$gst]['taxable'])){
                    $tmpArr[$gst]['taxable'] += $taxable_value;
                    $tmpArr[$gst]['tax'] += $tax;
                }else{
                    $tmpArr[$gst]['taxable'] = $taxable_value;
                    $tmpArr[$gst]['tax'] = $tax;
                }
            }
            unset($gst, $taxable_value, $tax);
        }
        if(!empty($tmpArr)){
            ksort($tmpArr);
            foreach ($tmpArr as $key => $value) {
                $tmp['desc'] = "IGST @".$key."% (".$key."%)";
                $tmp['gst'] = $key;
                $tmp['taxable_value'] = $value['taxable'];
                $tmp['tax'] = $value['tax'];
                $tmp['url'] = 'tax-detail.php?type=purchase&gst=igst&rate='.$key;
                $data['igst']['purchase'][] = $tmp;
            }
            unset($tmpArr, $tmp);
        }
        /*---------------------------PURCHASE IGST END------------------------------------*/
        return $data;
    }
    /*-----------------------GET TAX LIABILITY REPORT - 31-12-2018 - GAUTAM MAKWANA - END------------------------*/
    
    /*-----------------------------------GSTR1 REPORT - 31-12-18 - GAUTAM MAKWANA - START---------------------------*/
    function getGSTR1Report($from = null, $to = null, $is_financial = 0){
        $data = [];
        
        // B2B
        $data['B2B'] = getGSTR1B2BReport($from, $to, $is_financial);
        
        // B2CL
        $data['B2CL'] = getGSTR1B2CLReport($from, $to, $is_financial);
        
        // B2CS
        $data['B2CS'] = getGSTR1B2CSReport($from, $to, $is_financial);
        
        // HSN
        $data['HSN'] = getGSTR1HSNReport($from, $to, $is_financial);
        
        // CDNR
        $data['CDNR'] = getGSTR1CDNRReport($from, $to, $is_financial);
        
        // CDNUR
        $data['CDNUR'] = getGSTR1CDNURReport($from, $to, $is_financial);
        
        return $data;
    }
    // B2B
    function getGSTR1B2BReport($from = null, $to = null, $is_financial = 0){
        global $conn;
        global $pharmacy_id;
        global $financial_id;
        $data = [];
        
        //$saleQ = "SELECT tbd.id as tbdid, tbd.gst as rate, SUM(tbd.totalamount) as taxable_amount,tb.id as tbid, tb.invoice_date, tb.invoice_no, tb.final_amount, tb.totaligst, tb.totalcgst, tb.totalsgst, lg.id as customer_id, lg.name as customer_name, lg.gstno as customer_gstno, CONCAT(st.state_code_gst, '-',st.name) AS customer_state FROM tax_billing_details tbd INNER JOIN tax_billing tb ON tbd.tax_bill_id = tb.id LEFT JOIN ledger_master lg ON tb.customer_id = lg.id LEFT JOIN own_states st ON lg.state = st.id WHERE tb.pharmacy_id = '".$pharmacy_id."' AND lg.customer_type = 'GST_Regular'";
        $saleQ = "SELECT tbd.id as tbdid, tbd.gst as rate, SUM(tbd.totalamount - tbd.gst_tax) as taxable_amount,tb.id as tbid, tb.invoice_date, tb.invoice_no, tb.final_amount, IF(tbd.igst != 0,SUM(tbd.gst_tax),0) as totaligst, IF(tbd.cgst != 0,SUM(tbd.gst_tax / 2),0) as totalcgst, IF(tbd.sgst != 0,SUM(tbd.gst_tax / 2),0) as totalsgst, lg.id as customer_id, lg.name as customer_name, lg.gstno as customer_gstno, CONCAT(st.state_code_gst, '-',st.name) AS customer_state FROM tax_billing_details tbd INNER JOIN tax_billing tb ON tbd.tax_bill_id = tb.id LEFT JOIN ledger_master lg ON tb.customer_id = lg.id LEFT JOIN own_states st ON lg.state = st.id WHERE tb.pharmacy_id = '".$pharmacy_id."' AND lg.customer_type = 'GST_Regular'";
        if(isset($is_financial) && $is_financial == 1){
            $saleQ .= " AND tb.financial_id = '".$financial_id."'";
        }
        if((isset($from) && $from != '') && (isset($to) && $to != '')){
            $saleQ .= " AND tb.invoice_date >= '".$from."' AND tb.invoice_date <= '".$to."'";
        }
        $saleQ .= " GROUP BY tbd.tax_bill_id, tbd.gst ORDER BY tb.created";
        $saleR = mysqli_query($conn, $saleQ);
        if($saleR && mysqli_num_rows($saleR) > 0){
            $detail = [];
            $customerid = [];
            $invoiceid = [];
            $totalInvoiceValue = 0;
            $totalTaxableValue = 0;
            $totalIgst = 0;
            $totalCgst = 0;
            $totalSgst = 0;
            
            while($saleRow = mysqli_fetch_assoc($saleR)){
                $tmp['tbdid'] = (isset($saleRow['tbdid'])) ? $saleRow['tbdid'] : '';
                $tmp['tbid'] = (isset($saleRow['tbid'])) ? $saleRow['tbid'] : '';
                $tmp['gstin'] = (isset($saleRow['customer_gstno'])) ? $saleRow['customer_gstno'] : '';
                $tmp['receiver_name'] = (isset($saleRow['customer_name'])) ? $saleRow['customer_name'] : '';
                $tmp['invoice_no'] = (isset($saleRow['invoice_no'])) ? $saleRow['invoice_no'] : '';
                $tmp['invoice_date'] = (isset($saleRow['invoice_date'])) ? $saleRow['invoice_date'] : '';
                $tmp['invoice_value'] = (isset($saleRow['final_amount']) && $saleRow['final_amount'] != '') ? $saleRow['final_amount'] : 0;
                $tmp['place_of_supply'] = (isset($saleRow['customer_state'])) ? $saleRow['customer_state'] : '';
                $tmp['reverse_charge'] = 'N';
                $tmp['applicable_tax'] = '';
                $tmp['invoice_type'] = 'Regular';
                $tmp['e_commerce_gstin'] = '';
                $tmp['rate'] = (isset($saleRow['rate']) && $saleRow['rate'] != '') ? $saleRow['rate'] : 0;
                $tmp['taxable_value'] = (isset($saleRow['taxable_amount']) && $saleRow['taxable_amount'] != '') ? $saleRow['taxable_amount'] : 0;
                $tmp['cess'] = 0;
                
                
                
                $totalTaxableValue += $tmp['taxable_value'];
                if(!in_array($saleRow['customer_id'], $customerid)){
                    $customerid[] = $saleRow['customer_id'];
                }
                if(!in_array($tmp['tbid'], $invoiceid)){
                    $invoiceid[] = $tmp['tbid'];
                    $totalInvoiceValue += $tmp['invoice_value'];
                    $totalIgst += (isset($saleRow['totaligst']) && $saleRow['totaligst'] != '') ? $saleRow['totaligst'] : 0;
                    $totalCgst += (isset($saleRow['totalcgst']) && $saleRow['totalcgst'] != '') ? $saleRow['totalcgst'] : 0;
                    $totalSgst += (isset($saleRow['totalsgst']) && $saleRow['totalsgst'] != '') ? $saleRow['totalsgst'] : 0;
                }
                $detail[] = $tmp;
            }
            $data['total_recipients'] = count($customerid);
            $data['no_of_invoice'] = count($invoiceid);
            $data['total_invoice_value'] = $totalInvoiceValue;
            $data['total_taxable_value'] = $totalTaxableValue;
            $data['total_cess'] = 0;
            $data['total_igst'] = $totalIgst;
            $data['total_cgst'] = $totalCgst;
            $data['total_sgst'] = $totalSgst;
            $data['data'] = $detail;
        }
        
        return $data;
    }
    // B2CL
    function getGSTR1B2CLReport($from = null, $to = null, $is_financial = 0){
        global $conn;
        global $pharmacy_id;
        global $financial_id;
        $state_code = (isset($_SESSION['state_code'])) ? $_SESSION['state_code'] : '';
        $data = [];
        
        $saleQ = "SELECT tbd.id as tbdid, tbd.gst as rate, SUM(tbd.totalamount) as taxable_amount,tb.id as tbid, tb.invoice_date, tb.invoice_no, tb.final_amount, tb.totaligst, tb.totalcgst, tb.totalsgst, lg.id as customer_id, lg.name as customer_name, lg.gstno as customer_gstno, CONCAT(st.state_code_gst, '-',st.name) AS customer_state FROM tax_billing_details tbd INNER JOIN tax_billing tb ON tbd.tax_bill_id = tb.id LEFT JOIN ledger_master lg ON tb.customer_id = lg.id LEFT JOIN own_states st ON lg.state = st.id WHERE tb.pharmacy_id = '".$pharmacy_id."' AND lg.customer_type = 'GST_Regular' AND st.state_code_gst != '".$state_code."' AND tbd.gst IS NOT NULL AND tbd.gst != 0 AND tb.final_amount >= 250000";

        if(isset($is_financial) && $is_financial == 1){
            $saleQ .= " AND tb.financial_id = '".$financial_id."'";
        }
        if((isset($from) && $from != '') && (isset($to) && $to != '')){
            $saleQ .= " AND tb.invoice_date >= '".$from."' AND tb.invoice_date <= '".$to."'";
        }
        $saleQ .= " GROUP BY tbd.tax_bill_id, tbd.gst ORDER BY tb.invoice_date";
        $saleR = mysqli_query($conn, $saleQ);
        if($saleR && mysqli_num_rows($saleR) > 0){
            $detail = [];
            $customerid = [];
            $invoiceid = [];
            $totalInvoiceValue = 0;
            $totalTaxableValue = 0;
            $totalIgst = 0;
            $totalCgst = 0;
            $totalSgst = 0;
            
            while($saleRow = mysqli_fetch_assoc($saleR)){
                $tmp['tbdid'] = (isset($saleRow['tbdid'])) ? $saleRow['tbdid'] : '';
                $tmp['tbid'] = (isset($saleRow['tbid'])) ? $saleRow['tbid'] : '';
                $tmp['gstin'] = (isset($saleRow['customer_gstno'])) ? $saleRow['customer_gstno'] : '';
                $tmp['receiver_name'] = (isset($saleRow['customer_name'])) ? $saleRow['customer_name'] : '';
                $tmp['invoice_no'] = (isset($saleRow['invoice_no'])) ? $saleRow['invoice_no'] : '';
                $tmp['invoice_date'] = (isset($saleRow['invoice_date'])) ? $saleRow['invoice_date'] : '';
                $tmp['invoice_value'] = (isset($saleRow['final_amount']) && $saleRow['final_amount'] != '') ? $saleRow['final_amount'] : 0;
                $tmp['place_of_supply'] = (isset($saleRow['customer_state'])) ? $saleRow['customer_state'] : '';
                $tmp['reverse_charge'] = 'N';
                $tmp['applicable_tax'] = '';
                $tmp['invoice_type'] = 'Regular';
                $tmp['e_commerce_gstin'] = '';
                $tmp['rate'] = (isset($saleRow['rate']) && $saleRow['rate'] != '') ? $saleRow['rate'] : 0;
                $tmp['taxable_value'] = (isset($saleRow['taxable_amount']) && $saleRow['taxable_amount'] != '') ? $saleRow['taxable_amount'] : 0;
                $tmp['cess'] = 0;
                
                
                
                $totalTaxableValue += $tmp['taxable_value'];
                if(!in_array($saleRow['customer_id'], $customerid)){
                    $customerid[] = $saleRow['customer_id'];
                }
                if(!in_array($tmp['tbid'], $invoiceid)){
                    $invoiceid[] = $tmp['tbid'];
                    $totalInvoiceValue += $tmp['invoice_value'];
                    $totalIgst += (isset($saleRow['totaligst']) && $saleRow['totaligst'] != '') ? $saleRow['totaligst'] : 0;
                    $totalCgst += (isset($saleRow['totalcgst']) && $saleRow['totalcgst'] != '') ? $saleRow['totalcgst'] : 0;
                    $totalSgst += (isset($saleRow['totalsgst']) && $saleRow['totalsgst'] != '') ? $saleRow['totalsgst'] : 0;
                }
                $detail[] = $tmp;
            }
            $data['total_recipients'] = count($customerid);
            $data['no_of_invoice'] = count($invoiceid);
            $data['total_invoice_value'] = $totalInvoiceValue;
            $data['total_taxable_value'] = $totalTaxableValue;
            $data['total_cess'] = 0;
            $data['total_igst'] = $totalIgst;
            $data['total_cgst'] = $totalCgst;
            $data['total_sgst'] = $totalSgst;
            $data['data'] = $detail;
        }
        
        return $data;
    }
    // B2CS
    function getGSTR1B2CSReport($from = null, $to = null, $is_financial = 0){
        global $conn;
        global $pharmacy_id;
        global $financial_id;
        $state_code = (isset($_SESSION['state_code'])) ? $_SESSION['state_code'] : '';
        $data = [];
        
        $saleQ = "SELECT tbd.id as tbdid, tbd.gst as rate, SUM((tbd.totalamount)-(tbd.gst_tax)) as taxable_amount,tb.id as tbid, tb.invoice_date, tb.invoice_no, tb.final_amount, IF(tbd.igst != 0,SUM(tbd.gst_tax),0) as totaligst, IF(tbd.cgst != 0,SUM(tbd.gst_tax / 2),0) as totalcgst, IF(tbd.sgst != 0,SUM(tbd.gst_tax / 2),0) as totalsgst, lg.id as customer_id, lg.name as customer_name, lg.gstno as customer_gstno, CONCAT(st.state_code_gst, '-',st.name) AS customer_state FROM tax_billing_details tbd INNER JOIN tax_billing tb ON tbd.tax_bill_id = tb.id LEFT JOIN ledger_master lg ON tb.customer_id = lg.id LEFT JOIN own_states st ON lg.state = st.id WHERE (lg.customer_type = 'GST_unregistered' OR lg.customer_type = 'Consumer' OR lg.is_cash = 1) AND tb.pharmacy_id = '".$pharmacy_id."'";
        if(isset($is_financial) && $is_financial == 1){
            $saleQ .= " AND tb.financial_id = '".$financial_id."'";
        }
        if((isset($from) && $from != '') && (isset($to) && $to != '')){
            $saleQ .= " AND tb.invoice_date >= '".$from."' AND tb.invoice_date <= '".$to."'";
        }
        $saleQ .= " GROUP BY tbd.tax_bill_id, tbd.gst ORDER BY tb.invoice_date";
        $saleR = mysqli_query($conn, $saleQ);
        if($saleR && mysqli_num_rows($saleR) > 0){
            $detail = [];
            $customerid = [];
            $invoiceid = [];
            $totalInvoiceValue = 0;
            $totalTaxableValue = 0;
            $totalIgst = 0;
            $totalCgst = 0;
            $totalSgst = 0;
            
            while($saleRow = mysqli_fetch_assoc($saleR)){
                $tmp['tbdid'] = (isset($saleRow['tbdid'])) ? $saleRow['tbdid'] : '';
                $tmp['tbid'] = (isset($saleRow['tbid'])) ? $saleRow['tbid'] : '';
                $tmp['gstin'] = (isset($saleRow['customer_gstno'])) ? $saleRow['customer_gstno'] : '';
                $tmp['receiver_name'] = (isset($saleRow['customer_name'])) ? $saleRow['customer_name'] : '';
                $tmp['invoice_no'] = (isset($saleRow['invoice_no'])) ? $saleRow['invoice_no'] : '';
                $tmp['invoice_date'] = (isset($saleRow['invoice_date'])) ? $saleRow['invoice_date'] : '';
                $tmp['invoice_value'] = (isset($saleRow['final_amount']) && $saleRow['final_amount'] != '') ? $saleRow['final_amount'] : 0;
                $tmp['place_of_supply'] = (isset($saleRow['customer_state'])) ? $saleRow['customer_state'] : '';
                $tmp['reverse_charge'] = 'N';
                $tmp['applicable_tax'] = '';
                $tmp['invoice_type'] = 'Regular';
                $tmp['e_commerce_gstin'] = '';
                $tmp['rate'] = (isset($saleRow['rate']) && $saleRow['rate'] != '') ? $saleRow['rate'] : 0;
                $tmp['taxable_value'] = (isset($saleRow['taxable_amount']) && $saleRow['taxable_amount'] != '') ? $saleRow['taxable_amount'] : 0;
                $tmp['cess'] = 0;
                
                
                
                $totalTaxableValue += $tmp['taxable_value'];
                if(!in_array($saleRow['customer_id'], $customerid)){
                    $customerid[] = $saleRow['customer_id'];
                }
                if(!in_array($tmp['tbid'], $invoiceid)){
                    $invoiceid[] = $tmp['tbid'];
                    $totalInvoiceValue += $tmp['invoice_value'];
                    $totalIgst += (isset($saleRow['totaligst']) && $saleRow['totaligst'] != '') ? $saleRow['totaligst'] : 0;
                    $totalCgst += (isset($saleRow['totalcgst']) && $saleRow['totalcgst'] != '') ? $saleRow['totalcgst'] : 0;
                    $totalSgst += (isset($saleRow['totalsgst']) && $saleRow['totalsgst'] != '') ? $saleRow['totalsgst'] : 0;
                }
                $detail[] = $tmp;
            }
            $data['total_recipients'] = count($customerid);
            $data['no_of_invoice'] = count($invoiceid);
            $data['total_invoice_value'] = $totalInvoiceValue;
            $data['total_taxable_value'] = $totalTaxableValue;
            $data['total_cess'] = 0;
            $data['total_igst'] = $totalIgst;
            $data['total_cgst'] = $totalCgst;
            $data['total_sgst'] = $totalSgst;
            $data['data'] = $detail;
        }
        return $data;
    }
    // HSN
    function getGSTR1HSNReport($from = null, $to = null, $is_financial = 0){
        global $conn;
        global $pharmacy_id;
        global $financial_id;
        $state_code = (isset($_SESSION['state_code'])) ? $_SESSION['state_code'] : '';
        $data = [];
        $tempdetail = [];
        $no_of_hsn = 0;
        $total_value = 0;
        $total_taxable = 0;
        $total_igst = 0;
        $total_cgst = 0;
        $total_sgst = 0;

            //$saleQ = "SELECT SUM(tbd.qty) as totalqty, SUM(tbd.totalamount) as taxable_amount, SUM(tbd.totalamount*tbd.igst/100) as igst_amount, SUM(tbd.totalamount*tbd.cgst/100) as cgst_amount, SUM(tbd.totalamount*tbd.sgst/100) as sgst_amount, pm.id as product_id, pm.product_name, pm.hsn_code FROM tax_billing_details tbd LEFT JOIN tax_billing tb ON tbd.tax_bill_id = tb.id LEFT JOIN product_master pm ON tbd.product_id = pm.id WHERE tb.pharmacy_id = '".$pharmacy_id."'";
            $saleQ = "SELECT SUM(tbd.qty) as totalqty, SUM(tbd.totalamount - tbd.gst_tax) as taxable_amount, IF(tbd.igst != 0,SUM(tbd.gst_tax),0) as igst_amount, IF(tbd.cgst != 0,SUM(tbd.gst_tax / 2),0) as cgst_amount, IF(tbd.sgst != 0,SUM(tbd.gst_tax / 2),0) as sgst_amount, pm.id as product_id, pm.product_name, pm.hsn_code FROM tax_billing_details tbd LEFT JOIN tax_billing tb ON tbd.tax_bill_id = tb.id LEFT JOIN product_master pm ON tbd.product_id = pm.id WHERE tb.pharmacy_id = '".$pharmacy_id."'";
            
            if(isset($is_financial) && $is_financial == 1){
                $saleQ .= " AND tb.financial_id = '".$financial_id."'";
            }
            if((isset($from) && $from != '') && (isset($to) && $to != '')){
                $saleQ .= " AND tb.invoice_date >= '".$from."' AND tb.invoice_date <= '".$to."'";
            }
            $saleQ .= " GROUP BY pm.product_name, pm.hsn_code ORDER BY pm.hsn_code";
            $saleR = mysqli_query($conn, $saleQ);
            if($saleR && mysqli_num_rows($saleR) > 0){
                while($saleRow = mysqli_fetch_assoc($saleR)){
                    $hsn = (isset($saleRow['hsn_code']) && $saleRow['hsn_code'] != '') ? $saleRow['hsn_code'] : 'UnknownHSN';
                    $p_id = (isset($saleRow['product_id']) && $saleRow['product_id'] != '') ? $saleRow['product_id'] : 'UnknownID';
                    $tempdetail[$hsn.'_'.$p_id] = $saleRow;
                }
                unset($hsn, $p_id);
            }
            
            $saleReturnQ = "SELECT SUM(srd.qty) as totalqty, SUM(srd.amount - srd.gst_tax) as taxable_amount, IF(srd.igst != 0,SUM(srd.gst_tax),0) as igst_amount, IF(srd.cgst != 0,SUM(srd.gst_tax / 2),0) as cgst_amount, IF(srd.sgst != 0,SUM(srd.gst_tax / 2),0) as sgst_amount, pm.id as product_id, pm.product_name, pm.hsn_code FROM sale_return_details srd LEFT JOIN sale_return sr ON srd.sale_return_id = sr.id LEFT JOIN product_master pm ON srd.product_id = pm.id WHERE sr.pharmacy_id = '".$pharmacy_id."'";
            if(isset($is_financial) && $is_financial == 1){
                $saleReturnQ .= " AND sr.financial_id = '".$financial_id."'";
            }
            if((isset($from) && $from != '') && (isset($to) && $to != '')){
                $saleReturnQ .= " AND sr.credit_note_date >= '".$from."' AND sr.credit_note_date <= '".$to."'";
            }
            $saleReturnQ .= " GROUP BY pm.product_name, pm.hsn_code ORDER BY pm.hsn_code";

            $saleReturnR = mysqli_query($conn, $saleReturnQ);
            if($saleReturnR && mysqli_num_rows($saleReturnR) > 0){
                while($saleReturnRow = mysqli_fetch_assoc($saleReturnR)){
                    $hsn = (isset($saleReturnRow['hsn_code']) && $saleReturnRow['hsn_code'] != '') ? $saleReturnRow['hsn_code'] : 'UnknownHSN';
                    $p_id = (isset($saleReturnRow['product_id']) && $saleReturnRow['product_id'] != '') ? $saleReturnRow['product_id'] : 'UnknownID';
                    $saleReturnRow['totalqty'] = (isset($saleReturnRow['totalqty']) && $saleReturnRow['totalqty'] != '') ? -$saleReturnRow['totalqty'] : 0;
                    $saleReturnRow['taxable_amount'] = (isset($saleReturnRow['taxable_amount']) && $saleReturnRow['taxable_amount'] != '') ? -$saleReturnRow['taxable_amount'] : 0;
                    $saleReturnRow['igst_amount'] = (isset($saleReturnRow['igst_amount']) && $saleReturnRow['igst_amount'] != '') ? -$saleReturnRow['igst_amount'] : 0;
                    $saleReturnRow['cgst_amount'] = (isset($saleReturnRow['cgst_amount']) && $saleReturnRow['cgst_amount'] != '') ? -$saleReturnRow['cgst_amount'] : 0;
                    $saleReturnRow['sgst_amount'] = (isset($saleReturnRow['sgst_amount']) && $saleReturnRow['sgst_amount'] != '') ? -$saleReturnRow['sgst_amount'] : 0;
                    if(isset($tempdetail[$hsn.'_'.$p_id])){
                        $tempdetail[$hsn.'_'.$p_id]['totalqty'] += $saleReturnRow['totalqty'];
                        $tempdetail[$hsn.'_'.$p_id]['taxable_amount'] += $saleReturnRow['taxable_amount'];
                        $tempdetail[$hsn.'_'.$p_id]['igst_amount'] += $saleReturnRow['igst_amount'];
                        $tempdetail[$hsn.'_'.$p_id]['cgst_amount'] += $saleReturnRow['cgst_amount'];
                        $tempdetail[$hsn.'_'.$p_id]['sgst_amount'] += $saleReturnRow['sgst_amount'];
                    }else{
                        $tempdetail[$hsn.'_'.$p_id] = $saleReturnRow;
                    }
                }
            }

        if(!empty($tempdetail)){
            $tempdetail = array_values($tempdetail);
            array_multisort(array_column($tempdetail, 'product_name'), SORT_ASC, $tempdetail);
            $detail = [];
            foreach ($tempdetail as $key => $value) {
                if(isset($value['taxable_amount']) && $value['taxable_amount'] != 0){
                    $tmp['hsn'] = (isset($value['hsn_code']) && $value['hsn_code'] != '') ? $value['hsn_code'] : 'Unknown HSN';
                    $tmp['desc'] = (isset($value['product_name']) && $value['product_name'] != '') ? $value['product_name'] : 'Unknown Product';
                    $tmp['uqc'] = '';
                    $tmp['total_qty'] = (isset($value['totalqty']) && $value['totalqty'] != '') ? $value['totalqty'] : 0;
                    $tmp['total_taxable'] = (isset($value['taxable_amount']) && $value['taxable_amount'] != '') ? $value['taxable_amount'] : 0;
                    $tmp['total_igst'] = (isset($value['igst_amount']) && $value['igst_amount'] != '') ? $value['igst_amount'] : 0;
                    $tmp['total_cgst'] = (isset($value['cgst_amount']) && $value['cgst_amount'] != '') ? $value['cgst_amount'] : 0;
                    $tmp['total_sgst'] = (isset($value['sgst_amount']) && $value['sgst_amount'] != '') ? $value['sgst_amount'] : 0;
                    $tmp['total_cess'] = 0;
                    $tmp['total_value'] = ($tmp['total_taxable']+$tmp['total_igst']+$tmp['total_cgst']+$tmp['total_sgst']);
                    
                    $no_of_hsn++;
                    $total_value += $tmp['total_value'];
                    $total_taxable += $tmp['total_taxable'];
                    $total_igst += $tmp['total_igst'];
                    $total_cgst += $tmp['total_cgst'];
                    $total_sgst += $tmp['total_sgst'];
                    
                    $detail[] = $tmp;
                }
            }
            $data['no_of_hsn'] = $no_of_hsn;
            $data['total_value'] = $total_value;
            $data['total_taxable'] = $total_taxable;
            $data['total_igst'] = $total_igst;
            $data['total_cgst'] = $total_cgst;
            $data['total_sgst'] = $total_sgst;
            $data['total_cess'] = 0;
            $data['data'] = $detail;
        }
        return $data;
    }
    // CDNR
    function getGSTR1CDNRReport($from = null, $to = null, $is_financial = 0){
        global $conn;
        global $pharmacy_id;
        global $financial_id;
        $data = [];
        
        //$saleReturnQ = "SELECT sr.id as sr_id, srd.id as srd_id, lg.id as customer_id, lg.name as customer_name, lg.gstno as customer_gstno, CONCAT(st.state_code_gst, '-',st.name) as place_of_supply, tb.invoice_date, tb.invoice_no, tb.id as tax_bill_id, sr.credit_note_date, sr.credit_note_no, sr.finalamount as voucher_value, srd.gst as rate, SUM(srd.amount) as taxable_amount, SUM(srd.amount*srd.igst/100) as total_igst, SUM(srd.amount*srd.cgst/100) as total_cgst, SUM(srd.amount*srd.sgst/100) as total_sgst FROM sale_return_details srd INNER JOIN sale_return sr ON srd.sale_return_id = sr.id LEFT JOIN ledger_master lg ON sr.customer_id = lg.id LEFT JOIN own_states st ON lg.state = st.id LEFT JOIN tax_billing tb ON srd.tax_bill_id = tb.id WHERE sr.pharmacy_id = '".$pharmacy_id."' AND lg.customer_type = 'GST_Regular'";
        $saleReturnQ = "SELECT sr.id as sr_id, srd.id as srd_id, lg.id as customer_id, lg.name as customer_name, lg.gstno as customer_gstno, CONCAT(st.state_code_gst, '-',st.name) as place_of_supply, tb.invoice_date, tb.invoice_no, tb.id as tax_bill_id, sr.credit_note_date, sr.credit_note_no, sr.finalamount as voucher_value, srd.gst as rate, SUM(srd.amount - srd.gst_tax) as taxable_amount, IF(srd.igst != 0,SUM(srd.gst_tax),0) as total_igst, IF(srd.cgst != 0,SUM(srd.gst_tax / 2),0) as total_cgst, IF(srd.sgst != 0,SUM(srd.gst_tax / 2),0) as total_sgst FROM sale_return_details srd INNER JOIN sale_return sr ON srd.sale_return_id = sr.id LEFT JOIN ledger_master lg ON sr.customer_id = lg.id LEFT JOIN own_states st ON lg.state = st.id LEFT JOIN tax_billing tb ON srd.tax_bill_id = tb.id WHERE sr.pharmacy_id = '".$pharmacy_id."' AND lg.customer_type = 'GST_Regular'";
        if(isset($is_financial) && $is_financial == 1){
            $saleReturnQ .= " AND sr.financial_id = '".$financial_id."'";
        }
        if((isset($from) && $from != '') && (isset($to) && $to != '')){
            $saleReturnQ .= " AND sr.credit_note_date >= '".$from."' AND sr.credit_note_date <= '".$to."'";
        }
        $saleReturnQ .= " GROUP BY srd.sale_return_id, srd.gst ORDER BY sr.credit_note_date, srd.tax_bill_id";
        $saleReturnR = mysqli_query($conn, $saleReturnQ);
        if($saleReturnR && mysqli_num_rows($saleReturnR) > 0){
            $detail = [];
            $customerid = [];
            $voucherid = [];
            $totalVoucherValue = 0;
            $totalTaxableValue = 0;
            $taxbillid = [];
            $total_igst = 0;
            $total_cgst = 0;
            $total_sgst = 0;
            while($saleReturnRow = mysqli_fetch_assoc($saleReturnR)){
                $tmp['sr_id'] = (isset($saleReturnRow['sr_id'])) ? $saleReturnRow['sr_id'] : '';
                $tmp['srd_id'] = (isset($saleReturnRow['srd_id'])) ? $saleReturnRow['srd_id'] : '';
                $tmp['gstin'] = (isset($saleReturnRow['customer_gstno'])) ? $saleReturnRow['customer_gstno'] : '';
                $tmp['receiver_name'] = (isset($saleReturnRow['customer_name'])) ? $saleReturnRow['customer_name'] : '';
                $tmp['invoice_no'] = (isset($saleReturnRow['invoice_no'])) ? $saleReturnRow['invoice_no'] : '';
                $tmp['invoice_date'] = (isset($saleReturnRow['invoice_date'])) ? $saleReturnRow['invoice_date'] : '';
                $tmp['voucher_no'] = (isset($saleReturnRow['credit_note_no'])) ? $saleReturnRow['credit_note_no'] : '';
                $tmp['voucher_date'] = (isset($saleReturnRow['credit_note_date'])) ? $saleReturnRow['credit_note_date'] : '';
                $tmp['doc_type'] = 'D';
                $tmp['place_of_supply'] = (isset($saleReturnRow['place_of_supply'])) ? $saleReturnRow['place_of_supply'] : '';
                $tmp['voucher_value'] = (isset($saleReturnRow['voucher_value']) && $saleReturnRow['voucher_value'] != '') ? $saleReturnRow['voucher_value'] : 0;
                $tmp['applicable_tax'] = '';
                $tmp['rate'] = (isset($saleReturnRow['rate']) && $saleReturnRow['rate'] != '') ? $saleReturnRow['rate'] : 0;
                $tmp['taxable_value'] = (isset($saleReturnRow['taxable_amount']) && $saleReturnRow['taxable_amount'] != '') ? $saleReturnRow['taxable_amount'] : 0;
                $tmp['cess'] = 0;
                $tmp['pre_gst'] = '';
                
                $total_igst += (isset($saleReturnRow['total_igst']) && $saleReturnRow['total_igst'] != '') ? $saleReturnRow['total_igst'] : '';
                $total_cgst += (isset($saleReturnRow['total_cgst']) && $saleReturnRow['total_cgst'] != '') ? $saleReturnRow['total_cgst'] : '';
                $total_sgst += (isset($saleReturnRow['total_sgst']) && $saleReturnRow['total_sgst'] != '') ? $saleReturnRow['total_sgst'] : '';
                $totalTaxableValue += $tmp['taxable_value'];
                if(!in_array($saleReturnRow['customer_id'], $customerid)){
                    $customerid[] = $saleReturnRow['customer_id'];
                }
                if(!in_array($tmp['sr_id'], $voucherid)){
                    $voucherid[] = $tmp['sr_id'];
                    $totalVoucherValue += $tmp['voucher_value'];
                }
                if(!in_array($saleReturnRow['tax_bill_id'], $taxbillid)){
                    $taxbillid[] = $saleReturnRow['tax_bill_id'];
                }
                $detail[] = $tmp;
            }
            $data['total_recipients'] = count($customerid);
            $data['no_of_invoice'] = count($taxbillid);
            $data['no_of_voucher'] = count($voucherid);
            $data['total_voucher_value'] = $totalVoucherValue;
            $data['total_taxable_value'] = $totalTaxableValue;
            $data['total_cess'] = 0;
            $data['total_igst'] = $total_igst;
            $data['total_cgst'] = $total_cgst;
            $data['total_sgst'] = $total_sgst;
            $data['data'] = $detail;
        }
        
        return $data;
    }
    // CDNUR
    function getGSTR1CDNURReport($from = null, $to = null, $is_financial = 0){
        global $conn;
        global $pharmacy_id;
        global $financial_id;
        $data = [];
        
        $saleReturnQ = "SELECT sr.id as sr_id, srd.id as srd_id, lg.id as customer_id, lg.name as customer_name, lg.gstno as customer_gstno, CONCAT(st.state_code_gst, '-',st.name) as place_of_supply, tb.invoice_date, tb.invoice_no, tb.id as tax_bill_id, sr.credit_note_date, sr.credit_note_no, sr.finalamount as voucher_value, srd.gst as rate, SUM(srd.amount) as taxable_amount, SUM(srd.amount*srd.igst/100) as total_igst, SUM(srd.amount*srd.cgst/100) as total_cgst, SUM(srd.amount*srd.sgst/100) as total_sgst FROM sale_return_details srd INNER JOIN sale_return sr ON srd.sale_return_id = sr.id LEFT JOIN ledger_master lg ON sr.customer_id = lg.id LEFT JOIN own_states st ON lg.state = st.id LEFT JOIN tax_billing tb ON srd.tax_bill_id = tb.id WHERE sr.pharmacy_id = '".$pharmacy_id."' AND lg.customer_type = 'GST_unregistered'";
        if(isset($is_financial) && $is_financial == 1){
            $saleReturnQ .= " AND sr.financial_id = '".$financial_id."'";
        }
        if((isset($from) && $from != '') && (isset($to) && $to != '')){
            $saleReturnQ .= " AND sr.credit_note_date >= '".$from."' AND sr.credit_note_date <= '".$to."'";
        }
        $saleReturnQ .= " GROUP BY srd.sale_return_id, srd.gst ORDER BY sr.credit_note_date, srd.tax_bill_id";
        $saleReturnR = mysqli_query($conn, $saleReturnQ);
        if($saleReturnR && mysqli_num_rows($saleReturnR) > 0){
            $detail = [];
            $customerid = [];
            $voucherid = [];
            $totalVoucherValue = 0;
            $totalTaxableValue = 0;
            $taxbillid = [];
            $total_igst = 0;
            $total_cgst = 0;
            $total_sgst = 0;
            while($saleReturnRow = mysqli_fetch_assoc($saleReturnR)){
                $tmp['sr_id'] = (isset($saleReturnRow['sr_id'])) ? $saleReturnRow['sr_id'] : '';
                $tmp['srd_id'] = (isset($saleReturnRow['srd_id'])) ? $saleReturnRow['srd_id'] : '';
                $tmp['gstin'] = (isset($saleReturnRow['customer_gstno'])) ? $saleReturnRow['customer_gstno'] : '';
                $tmp['receiver_name'] = (isset($saleReturnRow['customer_name'])) ? $saleReturnRow['customer_name'] : '';
                $tmp['invoice_no'] = (isset($saleReturnRow['invoice_no'])) ? $saleReturnRow['invoice_no'] : '';
                $tmp['invoice_date'] = (isset($saleReturnRow['invoice_date'])) ? $saleReturnRow['invoice_date'] : '';
                $tmp['voucher_no'] = (isset($saleReturnRow['credit_note_no'])) ? $saleReturnRow['credit_note_no'] : '';
                $tmp['voucher_date'] = (isset($saleReturnRow['credit_note_date'])) ? $saleReturnRow['credit_note_date'] : '';
                $tmp['doc_type'] = 'C';
                $tmp['place_of_supply'] = (isset($saleReturnRow['place_of_supply'])) ? $saleReturnRow['place_of_supply'] : '';
                $tmp['voucher_value'] = (isset($saleReturnRow['voucher_value']) && $saleReturnRow['voucher_value'] != '') ? $saleReturnRow['voucher_value'] : 0;
                $tmp['applicable_tax'] = '';
                $tmp['rate'] = (isset($saleReturnRow['rate']) && $saleReturnRow['rate'] != '') ? $saleReturnRow['rate'] : 0;
                $tmp['taxable_value'] = (isset($saleReturnRow['taxable_amount']) && $saleReturnRow['taxable_amount'] != '') ? $saleReturnRow['taxable_amount'] : 0;
                $tmp['cess'] = 0;
                $tmp['pre_gst'] = '';
                
                $total_igst += (isset($saleReturnRow['total_igst']) && $saleReturnRow['total_igst'] != '') ? $saleReturnRow['total_igst'] : '';
                $total_cgst += (isset($saleReturnRow['total_cgst']) && $saleReturnRow['total_cgst'] != '') ? $saleReturnRow['total_cgst'] : '';
                $total_sgst += (isset($saleReturnRow['total_sgst']) && $saleReturnRow['total_sgst'] != '') ? $saleReturnRow['total_sgst'] : '';
                
                $totalTaxableValue += $tmp['taxable_value'];
                if(!in_array($saleReturnRow['customer_id'], $customerid)){
                    $customerid[] = $saleReturnRow['customer_id'];
                }
                if(!in_array($tmp['sr_id'], $voucherid)){
                    $voucherid[] = $tmp['sr_id'];
                    $totalVoucherValue += $tmp['voucher_value'];
                }
                if(!in_array($saleReturnRow['tax_bill_id'], $taxbillid)){
                    $taxbillid[] = $saleReturnRow['tax_bill_id'];
                }
                $detail[] = $tmp;
            }
            $data['total_recipients'] = count($customerid);
            $data['no_of_invoice'] = count($taxbillid);
            $data['no_of_voucher'] = count($voucherid);
            $data['total_voucher_value'] = $totalVoucherValue;
            $data['total_taxable_value'] = $totalTaxableValue;
            $data['total_cess'] = 0;
            $data['total_igst'] = $total_igst;
            $data['total_cgst'] = $total_cgst;
            $data['total_sgst'] = $total_sgst;
            $data['data'] = $detail;
        }
        
        return $data;
    }
    /*-----------------------------------GSTR1 REPORT - 31-12-18 - GAUTAM MAKWANA - END---------------------------*/
    
    /*-----------------------------------GET BANK ONE OR ALL - 01-01-19 - GAUTAM MAKWANA - START---------------------------*/
    function getBank($id = null){
        global $conn;
        global $pharmacy_id;
        $data = [];

        $query = "SELECT id, name, opening_balance, opening_balance_type, bank_ac_no, branch_name, ifsc_code FROM ledger_master WHERE (group_id = 5 OR group_id = 22) AND pharmacy_id = '".$pharmacy_id."'";
        if(isset($id) && $id != ''){
            $query .= " AND id = '".$id."'";    
        }
        $query .= " ORDER BY name";
        $res = mysqli_query($conn, $query);
        if($res && mysqli_num_rows($res) > 0){
            while ($row = mysqli_fetch_assoc($res)) {
                if(isset($id) && $id != ''){
                    $data = $row;
                }else{
                    $data[] = $row;
                }
            }
        }

        return $data;
    }
    /*-----------------------------------GET BANK ONE OR ALL - 01-01-19 - GAUTAM MAKWANA - END---------------------------*/
    
    /*-------------------------------GET BANK ACCOUNT TYPE---18-01-19---RAJESH-----START------------------------------*/
    function getBankaccounttype(){
        global $conn;
        $data = [];
        
        $query = "SELECT id, account_name FROM bank_account_type";
        $res = mysqli_query($conn, $query);
        if($res && mysqli_num_rows($res) > 0){
            while($row = mysqli_fetch_assoc($res)){
                $data[] = $row;
            }
        }
        return $data;
    }
    /*-------------------------------GET BANK ACCOUNT TYPE---18-01-19---RAJESH-----END------------------------------*/
    
    /*-------------------------------GET BANK ACCOUNT TYPE---18-01-19---JIGAR-----START------------------------------*/
    /**
     * Get Under Category For Ledger Managment
     *
     * @param int $type
     * @param int $subtype
     * @return int $under
     */
    function getUnderLedger($type, $subtype){
          
        //under variable
        $under = '';
        
        //defined arr for under category type
        $trading_acc_arr = array(9,15,26,27);
        $profit_loss_acc_arr = array(16,18,19);
        $balance_sheet_acc_arr = array(1,2,3,4,5,6,7,8,10,11,12,13,14,17,20,21,22,23,24,25,28,29);
    
        //check type and subtype 
        if($type == 1){
            //other accounts
            if( (isset($subtype) && in_array($subtype, $trading_acc_arr)) ) {
                //trading account
                $under = '1';
            } elseif( (isset($subtype) && in_array($subtype, $profit_loss_acc_arr))) {
                //profit & loss account
                $under = '2';
            } elseif( (isset($subtype) && in_array($subtype, $balance_sheet_acc_arr))) {
              // balance sheet
              $under = '3';
            }
        } elseif($type == '2') {
            //sales accounts under trading account
            $under = '1';
        } elseif($type == 3) {
            //purchase accounts under trading account
            $under = '1';
        } elseif($type == 4) {
            //tax accounts under balance sheet
            $under = '3';
        } elseif($type == 5) {
            //bank accounts under balance sheet
            $under = '3';
        }
    
        //return under category type
        return $under;
    }
    /*-------------------------------GET BANK ACCOUNT TYPE---18-01-19---JIGAR-----END------------------------------*/
    
    
    // dompdf generate pdf bill function
    function salebillpdf($tax_bill_id,$mode='D'){

    global $conn;

    $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';

    $query = "SELECT tb.*, lg.name as customer_name, lg.addressline1, lg.addressline2, lg.addressline3, lg.gstno,lg.panno,lg.mobile,lg.phone,lg.dl_no1,lg.dl_no2 , st.name as customer_state, st.state_code_gst as statecode, ct.name as customer_city,dp.name as doctor_name , dp.personal_title FROM tax_billing tb LEFT JOIN ledger_master lg ON tb.customer_id = lg.id LEFT JOIN own_states st ON lg.state = st.id LEFT JOIN own_cities ct ON lg.city = ct.id LEFT JOIN doctor_profile dp ON tb.doctor = dp.id WHERE tb.pharmacy_id ='".$pharmacy_id."' AND tb.id='".$tax_bill_id."'";
    $res = mysqli_query($conn, $query);
    if($res && mysqli_num_rows($res) > 0){
        $data = mysqli_fetch_assoc($res);

    /*----------------------------------------------PHARMACY QUERY START-------------------------------------------*/
    if(isset($data['pharmacy_id']) && $data['pharmacy_id'] != ''){
     $pharmacyQ = "SELECT p.id, p.pharmacy_name, p.address1, p.address2, p.address3,p.pin_code ,p.gst_no,p.company_cin_no,ct.name as city,p.mobile_no,p.email,p.logo_url,st.name as state_name,st.state_code_gst as pharmastatecode FROM pharmacy_profile p LEFT JOIN own_states st ON p.stateId = st.id LEFT JOIN own_cities ct ON p.city_name = ct.id WHERE p.id='".$data['pharmacy_id']."'";
     $pharmacyR = mysqli_query($conn, $pharmacyQ);
      if($pharmacyR && mysqli_num_rows($pharmacyR) > 0){
        $data['pharmacy'] = mysqli_fetch_assoc($pharmacyR);
      }
    }
    /*----------------------------------------------PHARMACY QUERY END-------------------------------------------*/

    /*----------------------------------------------PHARMACY BANK DETAIL QUERY START-------------------------------------------*/
    if(isset($data['pharmacy_id']) && $data['pharmacy_id'] != ''){
      $pharmacyBankDetailQ = "SELECT * FROM  ledger_master WHERE group_id IN (5,22) AND pharmacy_id = '".$data['pharmacy_id']."'";
      $pharmacyBankDetailR = mysqli_query($conn, $pharmacyBankDetailQ);
      if($pharmacyBankDetailR && mysqli_num_rows($pharmacyBankDetailR) > 0){
        while ($pharmacyBankDetailRow = mysqli_fetch_assoc($pharmacyBankDetailR)) {
          $data['pharmacy_bank_detail'][] = $pharmacyBankDetailRow;
        }
      }
    }   
    /*----------------------------------------------PHARMACY BANK DETAIL QUERY START-------------------------------------------*/

   /*----------------------------------------------TAX BILLING DETAIL QUERY START-------------------------------------------*/
    if(isset($data['id']) && $data['id'] != ''){
      $taxBillingDetailQ = "SELECT tbd.* , tb.*, pm.product_name, pm.mrp as product_mrp, pm.discount_per, pm.ratio,pm.batch_no, pm.bill_print_view,pm.mfg_company as product_mfg_company,pm.hsn_code as hsn, pm.batch_no as product_batch_no,um.unit_name, pm.ex_date as product_ex_date FROM tax_billing_details tbd INNER JOIN product_master pm ON tbd.product_id = pm.id INNER JOIN tax_billing tb ON tbd.tax_bill_id = tb.id left join unit_master um on pm.unit = um.id WHERE tbd.tax_bill_id = '".$data['id']."'";
      $taxBillingDetailR = mysqli_query($conn, $taxBillingDetailQ);
      if($taxBillingDetailR && mysqli_num_rows($taxBillingDetailR) > 0){
        while ($taxBillingDetailRow = mysqli_fetch_assoc($taxBillingDetailR)) {
          $data['tax_billing_detail'][] = $taxBillingDetailRow;
        //   echo "<pre>";
        //   print_r($data['tax_billing_detail']);
          
        }
      }
    }
    /*----------------------------------------------TAX BILLING DETAIL QUERY END-------------------------------------------*/

    /*----------------------------------------------PRODUCT RETURN DETAIL QUERY START-----------------------------------------*/
    if(isset($data['id']) && $data['id'] != ''){
      $salereturnQ = "SELECT sr.*, pm.product_name, pm.mrp as product_mrp, pm.mfg_company as product_mfg_company, pm.batch_no as product_batch_no, pm.ex_date as product_ex_date FROM sale_return_details sr INNER JOIN product_master pm ON sr.product_id = pm.id WHERE sr.tax_bill_id = '".$data['id']."'";
      $salereturnR = mysqli_query($conn, $salereturnQ);
      if($salereturnR && mysqli_num_rows($salereturnR) > 0){
        while ($salereturnRow = mysqli_fetch_assoc($salereturnR)) {
          $data['sale_return'][] = $salereturnRow;
        }
      }
    }
    /*----------------------------------------------PRODUCT RETURN DETAIL QUERY END-------------------------------------------*/
           
   /*----------------------------------------------Bill Note Remark START-----------------------------------------*/
     $bill_note = "SELECT * FROM bill_note WHERE pharmacy_id = '".$pharmacy_id."'";  
     $bill_noteR = mysqli_query($conn, $bill_note);
      if($bill_noteR && mysqli_num_rows($bill_noteR) > 0){
        while ($bill_noteRow = mysqli_fetch_assoc($bill_noteR)) {
          $data['billnote'] = $bill_noteRow;
          
          
        }  
      }
    /*----------------------------------------------Bill Note Remark END-----------------------------------------*/   
    
    if(isset($data['id']) && $data['id'] != ''){
      $alltotalQ = "SELECT tbd.* , tb.*, pm.product_name, pm.mrp as product_mrp, pm.discount_per, pm.ratio,pm.bill_print_view,pm.mfg_company as product_mfg_company,pm.hsn_code as hsn, pm.batch_no as product_batch_no, pm.ex_date as product_ex_date FROM tax_billing_details tbd INNER JOIN product_master pm ON tbd.product_id = pm.id INNER JOIN tax_billing tb ON tbd.tax_bill_id = tb.id WHERE tbd.tax_bill_id = '".$data['id']."'";
      $alltotalR = mysqli_query($conn, $alltotalQ);
      if($alltotalR && mysqli_num_rows($alltotalR) > 0){
           $grand_total = 0; $product_discount = 0; $Discountcount=0;
        while ($alltotalRow = mysqli_fetch_assoc($alltotalR)) {
        
          $allamount = $alltotalRow['qty'] * $alltotalRow['rate'];
          $grand_total += $allamount;
         
          $Discount = $alltotalRow['discount'];
          $to_discount = ($allamount * $Discount)/100;
          $product_discount += $to_discount; 
          
          $Discountcou = $alltotalRow['discount'];
          $Discountcount += $Discountcou;
        }
      }
    }
    
  }
  $html = '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
  $a = 0;
    //Adding Page Content
    foreach(array_chunk($data['tax_billing_detail'], 12) as $billing ) {

    // including normalize css style
    $html .='<style>html{line-height:1.15;-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%}body{margin:0}article,aside,footer,header,nav,section{display:block}h1{font-size:2em;margin:.67em 0}figcaption,figure,main{display:block}figure{margin:1em 40px}hr{box-sizing:content-box;height:0;overflow:visible}pre{font-family:monospace,monospace;font-size:1em}a{background-color:transparent;-webkit-text-decoration-skip:objects}abbr[title]{border-bottom:none;text-decoration:underline;text-decoration:underline dotted}b,strong{font-weight:inherit}b,strong{font-weight:bolder}code,kbd,samp{font-family:monospace,monospace;font-size:1em}dfn{font-style:italic}mark{background-color:#ff0;color:#000}small{font-size:80%}sub,sup{font-size:75%;line-height:0;position:relative;vertical-align:baseline}sub{bottom:-.25em}sup{top:-.5em}audio,video{display:inline-block}audio:not([controls]){display:none;height:0}img{border-style:none}svg:not(:root){overflow:hidden}button,input,optgroup,select,textarea{font-family:sans-serif;font-size:100%;line-height:1.15;margin:0}button,input{overflow:visible}button,select{text-transform:none}[type=reset],[type=submit],button,html [type=button]{-webkit-appearance:button}[type=button]::-moz-focus-inner,[type=reset]::-moz-focus-inner,[type=submit]::-moz-focus-inner,button::-moz-focus-inner{border-style:none;padding:0}[type=button]:-moz-focusring,[type=reset]:-moz-focusring,[type=submit]:-moz-focusring,button:-moz-focusring{outline:1px dotted ButtonText}fieldset{padding:.35em .75em .625em}legend{box-sizing:border-box;color:inherit;display:table;max-width:100%;padding:0;white-space:normal}progress{display:inline-block;vertical-align:baseline}textarea{overflow:auto}[type=checkbox],[type=radio]{box-sizing:border-box;padding:0}[type=number]::-webkit-inner-spin-button,[type=number]::-webkit-outer-spin-button{height:auto}[type=search]{-webkit-appearance:textfield;outline-offset:-2px}[type=search]::-webkit-search-cancel-button,[type=search]::-webkit-search-decoration{-webkit-appearance:none}::-webkit-file-upload-button{-webkit-appearance:button;font:inherit}details,menu{display:block}summary{display:list-item}canvas{display:inline-block}template{display:none}[hidden]{display:none}
    </style>';

    $html .='<style>.sheet,body{margin:0}@page{margin:0}.sheet{overflow:hidden;position:relative;box-sizing:border-box;page-break-after:always}body.A3 .sheet{width:297mm;height:419mm}body.A3.landscape .sheet{width:420mm;height:296mm}body.A4 .sheet{width:210mm;height:296mm}body.A4Half .sheet{width:210mm;height:148mm}body.A4.landscape .sheet{width:297mm;height:209mm}body.A5 .sheet{width:148mm;height:209mm}body.A5.landscape .sheet{width:210mm;height:147mm}body.letter .sheet{width:216mm;height:279mm}body.letter.landscape .sheet{width:280mm;height:215mm}body.legal .sheet{width:216mm;height:356mm}body.legal.landscape .sheet{width:357mm;height:215mm}.sheet.padding-10mm{padding:10mm}.sheet.padding-15mm{padding:15mm}.sheet.padding-20mm{padding:20mm}.sheet.padding-25mm{padding:25mm}@media screen{body{background:#e0e0e0}.sheet{background:#fff;box-shadow:0 .5mm 2mm rgba(0,0,0,.3);margin:5mm auto}}@media print{body.A3.landscape{width:420mm}body.A3,body.A4.landscape{width:297mm}body.A4,body.A4Half,body.A5.landscape{width:210mm}body.A5{width:148mm}body.legal,body.letter{width:216mm}body.letter.landscape{width:280mm}body.legal.landscape{width:357mm}}</style>';

    $html .= '<style>@page{size:A4Half}</style>';

    $html .= '<style>.customer-info,.invoice-info{padding-left:5px;border-left:1px solid #000}.header-row,.product-row{text-align:center;height:20px}.amount-details,.main-table,.product-details{width:100%;border-collapse:collapse}.font-bold,.header-row,.total-amount,.total-row td{font-weight:700}*{font-family:"Helvetica Neue",Helvetica,Helvetica,Arial,sans-serif}.main-table{border:1px solid #000}.company-logo img{width:100%;max-width:80px}.company-info{padding-left:5px}.customer-info{line-height:20px}.title{font-size:14px}.amount-details,.header-row,.note,.product-row,.sub-title{font-size:12px}.header-row{border-bottom:1px solid #000}.product-row td:last-child{text-align:right}.total-row{height:20px;border-top:1px solid #000}.total-row td{text-align:center}.total-row td:last-child{text-align:right}.product-details{font-size:12px;height:270px}.note{text-align:left;vertical-align:top}.amount-details{line-height:15px}.amount-details td:nth-child(1){text-align:left;font-weight:700}.amount-details td:nth-child(2){text-align:right}.total-amount{font-size:14px}.forsign,.term{font-size:12px}.term{padding-left:15px;padding-right:15px}.forsign{float:right}</style>';

  
    $html .= '<div class="A4Half">
                <section class="sheet padding-10mm">
                    <table class="main-table">';
    
    $addressArray = [];
    if(isset($data['pharmacy']['address1']) && $data['pharmacy']['address1'] != ''){
        $addressArray[] = $data['pharmacy']['address1'];
    }
    if(isset($data['pharmacy']['address2']) && $data['pharmacy']['address2'] != ''){
        $addressArray[] = $data['pharmacy']['address2'];
    }
    if(isset($data['pharmacy']['address3']) && $data['pharmacy']['address3'] != ''){
        $addressArray[] = $data['pharmacy']['address3'];
    }
    if(!empty($addressArray)){
        $companyAddress = implode(', ', $addressArray);
        $companyAddress = strtoupper($companyAddress);
    }

    //phramcy and company address
    if(isset($data['pharmacy']['pharmacy_name'])) { $phramcy_name = $data['pharmacy']['pharmacy_name'];}else{ $phramcy_name = '';}
    $companyAddress = (isset($companyAddress)) ? $companyAddress : '';

    $html .='<tr>
                <td class="company-info">
                    <span class="title font-bold">'.$phramcy_name.'</span><br>';

    $html .='<span class="sub-title">'.$companyAddress;
        if(isset($data['pharmacy']['city']) && $data['pharmacy']['city'] != ""){ $comma = "," ; }else{ $comma = '';}
        $city = (isset($data['pharmacy']['city'])) ? $data['pharmacy']['city'] : '';
        if(isset($data['pharmacy']['pin_code']) && $data['pharmacy']['pin_code'] != ""){ $seprator = "-" ; } else { $seprator = "." ; }
        $pincode = (isset($data['pharmacy']['pin_code'])) ? $data['pharmacy']['pin_code'] : '';
    $html .= $comma.$city.$seprator.$pincode;
    $html .= '</span><br>';
                         
    if (isset($data['pharmacy']['mobile_no']) && $data['pharmacy']['mobile_no'] !=''){
        $mobile_no = (isset($data['pharmacy']['mobile_no'])) ? $data['pharmacy']['mobile_no'] : '';
        $html .= '<span class="sub-title">M: '.$mobile_no.'</span><br>';
    }
    if (isset($data['pharmacy']['gst_no']) && $data['pharmacy']['gst_no'] !=''){
        $pharmacy_gst_no = (isset($data['pharmacy']['gst_no'])) ? $data['pharmacy']['gst_no'] : '';
        $html .= '<span class="sub-title">GSTIN/UIN: '.$pharmacy_gst_no.'</span><br>';
    }
    if (isset($data['pharmacy']['email']) && $data['pharmacy']['email'] !=''){
        $pharmacy_email = (isset($data['pharmacy']['email'])) ? $data['pharmacy']['email'] : '';
        $html .= '<span class="sub-title">E-Mail: '.$pharmacy_email.'</span>';
    } 
    
    $invoice_no = (isset($data['invoice_no'])) ? $data['invoice_no'] : '';
    $invoice_date = (isset($data['invoice_date']) && $data['invoice_date'] != '') ? date('d/m/Y',strtotime($data['invoice_date'])) : '';

    $html .= '</td>
                <td class="invoice-info">
                    <span class="title font-bold">Invoice # '.$invoice_no.'</span><br>
                    <span class="title">Date: '.$invoice_date.'</span>
                </td>';
                $html .= '<td class="customer-info">';
                    if (isset($data['doctor_name']) && $data['doctor_name'] !=''){
                        $personal_title = (isset($data['personal_title'])) ? $data['personal_title'] : '';
                        $doctor_name = (isset($data['doctor_name'])) ? $data['doctor_name'] : '';

                        $html .= '<span class="title">Doctor: <b>'.$personal_title.'. '.$doctor_name.'</b></span><br>';
                    }

                $html .= '<span>&nbsp;</span><br>';

                    if (isset($data['customer_name']) && $data['customer_name'] !=''){
                        $customer_name = (isset($data['customer_name']) && $data['customer_name'] != '') ? ucwords(strtolower($data['customer_name'])) : '';
                        $html .= '<span class="title">Customer: <b>'.$customer_name.'</b></span><br>';
                    }
                    
                    if (isset($data['customer_city']) && $data['customer_city'] !=''){
                        $customer_city = (isset($data['customer_city']) && $data['customer_city'] != '') ? ucwords(strtolower($data['customer_city'])) : '';
                        $html .= '<span class="title"> <b>'.$customer_city.'</b></span><br>';
                    }
                    
                    if (isset($data['mobile']) && $data['mobile'] !=''){
                        $mobile = (isset($data['mobile'])) ? $data['mobile'] : '';
                        $html .= '<span class="sub-title">M: '.$mobile.'</span><br>';
                    }
                    
                    if (isset($data['email']) && $data['email'] !=''){
                        $email = (isset($data['email'])) ? $data['email'] : '';
                        $html .= '<span class="sub-title">E-Mail: '.$email.'</span>';
                    }
    $html .= '</td>
                </tr>
                <tr>
                    <td colspan="3" style="border-top:1px solid #000;border-bottom:1px solid #000;padding:0px;">
                        <table class="product-details">
                            <tr class="header-row">
                                <td>Sr.</td>
                                <td>Particular</td>
                                <td>Pack</td>
                                <td>Mfg.</td>
                                <td>MRP</td>
                                <td>Qty</td>
                                <td>Batch</td>
                                <td>Exp.</td>';
                                if($Discountcount != 0){   
                                    $html .= '<td>Disc%</td>';
                                }
                        $html .= '<td>GST%</td>
                                <td>GST(<span style="font-family:DejaVu Sans;">&#x20b9;</span>)</td>
                                <td style="float:right;">Amount</td>
                            </tr>';
        if(isset($data['tax_billing_detail']) && !empty($data['tax_billing_detail'])){
          
            $total_qty = 0;
            $total_free_qty = 0;
            $total_rate = 0;
            $total_amount = 0;
            $total_gst = 0;
            $all_discount = 0;
        				
            foreach ($billing as $key => $value) {
                $html .= '<tr class="product-row">
                                <td>';$sr_no = $key+1;
                        $html .= $sr_no.'</td>';
                        $html .= '<td>';
                        $product_name = (isset($value['product_name'])) ? ucfirst(strtolower($value['product_name'])) : '';
                                
                        $html .= $product_name.'</td>';
                        $ratio = (isset($value['ratio'])) ? ucfirst(strtolower($value['ratio'])) : '';
                        $unit_name = (isset($value['unit_name'])) ? strtoupper(strtolower($value['unit_name'])) : '';
                        $html .='<td>'.$ratio.$unit_name.'</td>';

                    if($value['product_mfg_company'] != ''){
                        $mfg_company = (isset($value['product_mfg_company'])) ? substr($value['product_mfg_company'],0,5)   : '';
                        $html .='<td>'.$mfg_company.'</td>';
                     } else {
                        $bill_print_view = (isset($value['bill_print_view'])) ? $value['bill_print_view'] : '';
                        $html .= '<td>'.$bill_print_view.'</td>';
                    }
                        $mrp = (isset($value['mrp'])) ? $value['mrp'] : '';
                        $html .= '<td>'.$mrp.'</td>';

                        $qty =  (isset($value['qty']) && $value['qty'] != '') ? $value['qty'] : 0;
                        $total_qty += $qty;
                        $round_qty =  round($qty, 2); 

                        $html .= '<td>'.$round_qty.'</td>';
                        $batch_no = (isset($value['batch_no'])) ? $value['batch_no'] : '-';
                        $html .= '<td>'.$batch_no.'</td>';
                        $product_ex_date = (isset($value['product_ex_date']) && $value['product_ex_date'] != '0000-00-00') ? date('m/y',strtotime($value['product_ex_date'])) : '-';
                        $html .= '<td>'.$product_ex_date.'</td>';
                              
                        if ($Discountcount != 0){
                            $discount = (isset($value['discount'])) ? $value['discount'] : '';
                            $html .= '<td>'.$discount.'</td>';
                        }
                        $gst = (isset($value['gst'])) ? $value['gst'] : '';
                        $html .= '<td>'.$gst.'</td>';

                        $gst_tax = (isset($value['gst_tax']) && $value['gst_tax'] != '' ) ? $value['gst_tax'] : ''; 
                        $total_gst += $gst_tax;

                        $html .= '<td>'.amount_format(number_format((round($gst_tax, 2)), 2, '.', '')).'</td>';

                        $amount = (isset($value['qty']) && $value['qty'] != '' && isset($value['rate']) && $value['rate'] !='') ? $value['qty'] * $value['rate'] : '0';
                        $total_amount += $amount; 
                        
                        $html .= '<td>'.amount_format(number_format((round($amount, 2)), 2, '.', '')).'</td></tr>';
                            
            }
        }
    
        if(isset($key) && $key!=''){  
            $availablerow = $key+1;
        } else{
            $availablerow = 0;
        }
        
        $addrow = 12 - $availablerow;

        for($i =1 ;$i<=$addrow; $i++){
            $html .= '<tr class="product-row">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td> 
                        <td></td>';
        if ($Discountcount != 0){
            $html .= '<td></td>'; 
        }
                $html .='<td></td>              
                        <td></td>
                        <td></td>
                    </tr>';
        }
      
        $html .= '<tfoot>
                    <tr class="total-row">
                        <td colspan="5">
                            Total
                        </td>
                        <td>';
                        $qty2 = (isset($total_qty)) ? round($total_qty, 2) : 0; 
        $html .= $qty2.'</td>
                        <td></td>
                        <td></td>';

                        if ($Discountcount != 0){
                            $html .= '<td></td>';
                        }
        $html .= '<td></td>';
        $round_gst = (isset($total_gst)) ? round($total_gst, 2) : 0;

        $html .= '<td>'.$round_gst.'</td>';
        $amount_format = (isset($total_amount)) ? amount_format(number_format((round($total_amount, 2)), 2, '.', '')) : 0;
        $html .= '<td>'.$amount_format.'</td>
                    </tr>
                </tfoot>
                        </table>        
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <table style="width:100%;">
                            <tr>
                                <td>
                                    <span class="note">GET WELL SOON</span><br>';
                                    $fetched_bill_note = (isset($data['billnote']['bill_note']))? $data['billnote']['bill_note']:'';
                                    $html .= '<span class="note font-bold">'.$fetched_bill_note.'</span><br>    
                                    <span class="note">E. & O.E.</span>
                                </td>
                                <td style="vertical-align: top;width: 150px;">
                                    <table class="amount-details">';
                                       if($product_discount != 0){
                                        $p_discount = (isset($product_discount)) ? $product_discount : '0';
                                        
                                        $html .= '<tr>
                                            <td>Discount :</td>
                                            <td>'.$p_discount.'</td>
                                            </tr>';
                                        }
                                        
                                        if($value['discount_rs'] != 0){
                                            $discount_rs = (isset($value['discount_rs'])) ? $value['discount_rs'] : '';
                                            $html .= '<tr>
                                                        <td>Total Discount :</td>
                                                        <td>'.$discount_rs.'</td>
                                                    </tr>';
                                        }

                                        $grand_total_format = (isset($grand_total)) ? amount_format(number_format((round($grand_total, 2)), 2, '.', '')) : 0;
                                        $html .= '<tr>
                                            <td>Grand Total :</td>
                                            <td class="total-amount">'.$grand_total_format.'</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>';
                $html .= '<tr>
                    <td colspan="3" style="border-top:1px solid #000;padding:0px;">
                        <table style="width:100%;">
                            <tr style="height:23px;">
                                <td></td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="term">SUBJECT TO JUNAGADH JURISDICTION</span>';
                                    $date_display = date('d/m/Y h:i:s A');
                                    $html .= '<span class="term">'.$date_display.'</span>';
                                    $pharmacy_name = (isset($data['pharmacy']['pharmacy_name'])) ? $data['pharmacy']['pharmacy_name'] : '';

                                    $html .= '<span class="forsign" style="margin-top: 5px;">For, '.$pharmacy_name.'</span>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
      </section>
    </div>';

    $a++;
    }

    // include autoloader
    require_once __DIR__.'/../dompdf/autoload.inc.php';
    

    // instantiate and use the dompdf class
    $dompdf = new Dompdf();

    $dompdf->loadHtml($html);

    // (Optional) Setup the paper size and orientation
    $dompdf->setPaper('A4', 'portrait');

    // Render the HTML as PDF
    $dompdf->render();

    $dir_name =__DIR__."/../invoicepdf"; 

    //make directory if not exists
    if (!is_dir($dir_name. "/")) {
        mkdir($dir_name . "/", 0755);
    }

    $pdfoutput = $dompdf->output();

    $filename ='TI-'.$tax_bill_id.'-'.time().'.pdf';

    $filepath = __DIR__."/../invoicepdf/".$filename;

    $fp = fopen($filepath, "w");
    fwrite($fp, $pdfoutput);
    fclose($fp);

    $file_arr = array();
    $file_arr['filepath'] =  $filepath;
    $file_arr['filename'] =  $filename;

    return $file_arr;

}


    /*-----------------------------DAY BOOK REPORT - GAUTAM MAKWANA - 24-01-2018 - START---------------------------*/
    function daybook($from = null, $to = null, $is_financial = 0){
        global $conn;
        global $pharmacy_id;
        global $financial_id;
        $current_state = (isset($_SESSION['state_code'])) ? $_SESSION['state_code'] : '';

        $data['credit'] = [];
        $data['debit'] = [];

        /*---------------------------SALE BILL START---------------------------------*/
        $saleQ = "SELECT tb.id, ROUND(SUM(tbd.totalamount-tbd.gst_tax), 2) as taxable_amount, SUM(tbd.gst_tax) as total_gst, tb.roundoff_amount, tb.created, tb.invoice_no, tb.invoice_date, lg.name as customer_name, st.state_code_gst as state_code FROM tax_billing tb INNER JOIN tax_billing_details tbd ON tb.id = tbd.tax_bill_id LEFT JOIN ledger_master lg ON tb.customer_id = lg.id LEFT JOIN own_states st ON lg.state = st.id WHERE tb.pharmacy_id = '".$pharmacy_id."'";
        if(isset($is_financial) && $is_financial == 1){
            $saleQ .= " AND tb.financial_id = '".$financial_id."'";
        }
        if((isset($from) && $from != '') && (isset($to) && $to != '')){
          $saleQ .= " AND tb.invoice_date >= '".$from."' AND tb.invoice_date <= '".$to."'";
        }
        $saleQ.= " GROUP BY tbd.tax_bill_id";
        $saleR = mysqli_query($conn, $saleQ);
        if($saleR && mysqli_num_rows($saleR) > 0){
            while ($saleRow = mysqli_fetch_assoc($saleR)) {
                
                $taxable_amount = (isset($saleRow['taxable_amount']) && $saleRow['taxable_amount'] != '') ? $saleRow['taxable_amount'] : 0;
                $total_gst = (isset($saleRow['total_gst']) && $saleRow['total_gst'] != '') ? $saleRow['total_gst'] : 0;
                $roundoff_amount = (isset($saleRow['roundoff_amount']) && $saleRow['roundoff_amount'] != '') ? $saleRow['roundoff_amount'] : 0;

                // debit
                $CRtmp['id'] = $saleRow['id'];
                $CRtmp['type'] = "SALE";
                $CRtmp['date'] = $saleRow['invoice_date'];
                $CRtmp['desc'] = $saleRow['customer_name'].', Sale Bill '.$saleRow['invoice_no'];
                $CRtmp['amount'] = ($taxable_amount+$total_gst+$roundoff_amount);
                $data['debit'][] = $CRtmp;

                // credit
                $DBtmp['id'] = $saleRow['id'];
                $DBtmp['type'] = "SALE";
                $DBtmp['date'] = $saleRow['invoice_date'];
                $DBtmp['desc'] = 'Sale Account, Sale Bill '.$saleRow['invoice_no'];
                $DBtmp['amount'] = ($taxable_amount);
                $data['credit'][] = $DBtmp;

                $DBtmp1['id'] = $saleRow['id'];
                $DBtmp1['type'] = "SALE";
                $DBtmp1['date'] = $saleRow['invoice_date'];
                $DBtmp1['desc'] = ($saleRow['state_code'] == $current_state) ? 'GST On Sale, Sale Bill '.$saleRow['invoice_no'] : 'IGST On Sale, Sale Bill '.$saleRow['invoice_no'];
                $DBtmp1['amount'] = ($total_gst);
                $data['credit'][] = $DBtmp1;

                if($roundoff_amount != 0 && $roundoff_amount != ''){
                    $DBtmp2['id'] = $saleRow['id'];
                    $DBtmp2['type'] = "SALE";
                    $DBtmp2['date'] = $saleRow['invoice_date'];
                    $DBtmp2['desc'] = 'Round Off, Sale Bill '.$saleRow['invoice_no'];
                    $DBtmp2['amount'] = abs($roundoff_amount);
                    if($roundoff_amount > 0){
                        $data['credit'][] = $DBtmp2;
                    }else{
                        $data['debit'][] = $DBtmp2;
                    }
                    
                }
            }
            unset($taxable_amount, $total_gst, $roundoff_amount, $CRtmp, $DBtmp, $DBtmp1, $DBtmp2);
        }
        /*---------------------------SALE BILL END---------------------------------*/
        
        /*---------------------------SALE RETURN START-----------------------------*/
        $saleReturnQ = "SELECT sr.id, sr.credit_note_no, sr.credit_note_date, ROUND(SUM(srd.amount-srd.gst_tax), 2) as taxable_amount, SUM(srd.gst_tax) as total_gst, SUM(srd.amount) as total_amount, ROUND((ROUND(SUM(srd.amount))-SUM(srd.amount)), 2) as round_off, lg.name as party_name, st.state_code_gst as state_code FROM `sale_return` sr INNER JOIN sale_return_details srd ON sr.id = srd.sale_return_id LEFT JOIN ledger_master lg On sr.customer_id = lg.id LEFT JOIN own_states st ON lg.state = st.id WHERE sr.pharmacy_id = '".$pharmacy_id."'";
        if(isset($is_financial) && $is_financial == 1){
            $saleReturnQ .= " AND sr.financial_id = '".$financial_id."'";
        }
        if((isset($from) && $from != '') && (isset($to) && $to != '')){
          $saleReturnQ .= " AND sr.credit_note_date >= '".$from."' AND sr.credit_note_date <= '".$to."'";
        }
        $saleReturnQ .="  GROUP BY srd.sale_return_id";
        $saleReturnR = mysqli_query($conn, $saleReturnQ);
        if($saleReturnR && mysqli_num_rows($saleReturnR) > 0){
            while($saleReturnRow = mysqli_fetch_assoc($saleReturnR)){
                $taxable_amount = (isset($saleReturnRow['taxable_amount']) && $saleReturnRow['taxable_amount'] != '') ? $saleReturnRow['taxable_amount'] : 0;
                $total_gst = (isset($saleReturnRow['total_gst']) && $saleReturnRow['total_gst'] != '') ? $saleReturnRow['total_gst'] : 0;
                $roundoff_amount = (isset($saleReturnRow['round_off']) && $saleReturnRow['round_off'] != '') ? $saleReturnRow['round_off'] : 0;

                // credit
                $CRtmp['id'] = $saleReturnRow['id'];
                $CRtmp['type'] = "SALERETURN";
                $CRtmp['date'] = $saleReturnRow['credit_note_date'];
                $CRtmp['desc'] = $saleReturnRow['party_name'].', Credit Note '.$saleReturnRow['credit_note_no'];
                $CRtmp['amount'] = ($taxable_amount+$total_gst+$roundoff_amount);
                $data['credit'][] = $CRtmp;

                // debit
                $DBtmp['id'] = $saleReturnRow['id'];
                $DBtmp['type'] = "SALERETURN";
                $DBtmp['date'] = $saleReturnRow['credit_note_date'];
                $DBtmp['desc'] = 'Sale Account, Credit Note '.$saleReturnRow['credit_note_no'];
                $DBtmp['amount'] = ($taxable_amount);
                $data['debit'][] = $DBtmp;

                $DBtmp1['id'] = $saleReturnRow['id'];
                $DBtmp1['type'] = "SALERETURN";
                $DBtmp1['date'] = $saleReturnRow['credit_note_date'];
                $DBtmp1['desc'] = ($saleReturnRow['state_code'] == $current_state) ? 'GST On Sale, Credit Note '.$saleReturnRow['credit_note_no'] : 'IGST On Sale, Credit Note '.$saleReturnRow['credit_note_no'];
                $DBtmp1['amount'] = ($total_gst);
                $data['debit'][] = $DBtmp1;

                if($roundoff_amount != 0 && $roundoff_amount != ''){
                    $DBtmp2['id'] = $saleReturnRow['id'];
                    $DBtmp2['type'] = "SALERETURN";
                    $DBtmp2['date'] = $saleReturnRow['credit_note_date'];
                    $DBtmp2['desc'] = 'Round Off, Credit Note '.$saleReturnRow['credit_note_no'];
                    $DBtmp2['amount'] = abs($roundoff_amount);
                    if($roundoff_amount > 0){
                        $data['debit'][] = $DBtmp2;
                    }else{
                        $data['credit'][] = $DBtmp2;
                    }
                    
                }
            }
            unset($taxable_amount, $total_gst, $roundoff_amount, $CRtmp, $DBtmp, $DBtmp1, $DBtmp2);
        }
        /*---------------------------SALE RETURN END-----------------------------*/
        
        /*---------------------------PURCHASE BILL START---------------------------*/
        $purchaseQ = "SELECT p.id, p.overall_value as taxable_amount, (p.total_igst+p.total_cgst+p.total_sgst) as gst_tax, p.round_off, p.invoice_date, p.invoice_no, p.created, lg.name as vendor_name, st.state_code_gst as state_code FROM `purchase` p LEFT JOIN ledger_master lg ON p.vendor = lg.id LEFT JOIN own_states st ON lg.state = st.id WHERE p.pharmacy_id = '".$pharmacy_id."'";
        if(isset($is_financial) && $is_financial == 1){
            $purchaseQ .= " AND p.financial_id = '".$financial_id."'";
        }
        if((isset($from) && $from != '') && (isset($to) && $to != '')){
          $purchaseQ .= " AND p.invoice_date >= '".$from."' AND p.invoice_date <= '".$to."'";
        }
        $purchaseR = mysqli_query($conn, $purchaseQ);
        if($purchaseR && mysqli_num_rows($purchaseR) > 0){
            while($purchaseRow = mysqli_fetch_assoc($purchaseR)){
                $taxable_amount = (isset($purchaseRow['taxable_amount']) && $purchaseRow['taxable_amount'] != '') ? $purchaseRow['taxable_amount'] : 0;
                $total_gst = (isset($purchaseRow['gst_tax']) && $purchaseRow['gst_tax'] != '') ? $purchaseRow['gst_tax'] : 0;
                $roundoff_amount = (isset($purchaseRow['round_off']) && $purchaseRow['round_off'] != '') ? $purchaseRow['round_off'] : 0;

                // credit
                $CRtmp['id'] = $purchaseRow['id'];
                $CRtmp['type'] = "PURCHASE";
                $CRtmp['date'] = $purchaseRow['invoice_date'];
                $CRtmp['desc'] = $purchaseRow['vendor_name'].', Purchase Bill '.$purchaseRow['invoice_no'];
                $CRtmp['amount'] = ($taxable_amount+$total_gst+$roundoff_amount);
                $data['credit'][] = $CRtmp;

                // debit
                $DBtmp['id'] = $purchaseRow['id'];
                $DBtmp['type'] = "PURCHASE";
                $DBtmp['date'] = $purchaseRow['invoice_date'];
                $DBtmp['desc'] = 'Purchase Account, Purchase Bill '.$purchaseRow['invoice_no'];
                $DBtmp['amount'] = ($taxable_amount);
                $data['debit'][] = $DBtmp;

                $DBtmp1['id'] = $purchaseRow['id'];
                $DBtmp1['type'] = "PURCHASE";
                $DBtmp1['date'] = $purchaseRow['invoice_date'];
                $DBtmp1['desc'] = ($purchaseRow['state_code'] == $current_state) ? 'GST On Purchase, Purchase Bill '.$purchaseRow['invoice_no'] : 'IGST On Purchase, Purchase Bill '.$purchaseRow['invoice_no'];
                $DBtmp1['amount'] = ($total_gst);
                $data['debit'][] = $DBtmp1;

                if($roundoff_amount != 0 && $roundoff_amount != ''){
                    $DBtmp2['id'] = $purchaseRow['id'];
                    $DBtmp2['type'] = "PURCHASE";
                    $DBtmp2['date'] = $purchaseRow['invoice_date'];
                    $DBtmp2['desc'] = 'Round Off, Purchase Bill '.$purchaseRow['invoice_no'];
                    $DBtmp2['amount'] = abs($roundoff_amount);
                    if($roundoff_amount > 0){
                        $data['debit'][] = $DBtmp2;
                    }else{
                        $data['credit'][] = $DBtmp2;
                    }
                    
                }
            }
            unset($taxable_amount, $total_gst, $roundoff_amount, $CRtmp, $DBtmp, $DBtmp1, $DBtmp2);
        }
        /*---------------------------PURCHASE BILL END-----------------------------*/
        
        /*--------------------------PURCHASE RETURN START--------------------------*/
        $purchaseReturnQ = "SELECT pr.id, pr.debit_note_date, pr.debit_note_no, pr.totalamount as taxable_amount, (pr.igst+pr.cgst+pr.sgst) as gst_tax, ROUND((ROUND(pr.finalamount)-(pr.finalamount)), 2) as round_off, lg.name as vendor_name, st.state_code_gst as state_code FROM `purchase_return` pr LEFT JOIN ledger_master lg ON pr.vendor_id = lg.id LEFT JOIN own_states st ON lg.state = st.id WHERE pr.pharmacy_id = '".$pharmacy_id."'";
        if(isset($is_financial) && $is_financial == 1){
            $purchaseReturnQ .= " AND pr.financial_id = '".$financial_id."'";
        }
        if((isset($from) && $from != '') && (isset($to) && $to != '')){
          $purchaseReturnQ .= " AND pr.debit_note_date >= '".$from."' AND pr.debit_note_date <= '".$to."'";
        }
        $purchaseReturnR = mysqli_query($conn, $purchaseReturnQ);
        if($purchaseReturnR && mysqli_num_rows($purchaseReturnR) > 0){
            while($purchaseReturnRow = mysqli_fetch_assoc($purchaseReturnR)){
                $taxable_amount = (isset($purchaseReturnRow['taxable_amount']) && $purchaseReturnRow['taxable_amount'] != '') ? $purchaseReturnRow['taxable_amount'] : 0;
                $total_gst = (isset($purchaseReturnRow['gst_tax']) && $purchaseReturnRow['gst_tax'] != '') ? $purchaseReturnRow['gst_tax'] : 0;
                $roundoff_amount = (isset($purchaseReturnRow['round_off']) && $purchaseReturnRow['round_off'] != '') ? $purchaseReturnRow['round_off'] : 0;

                // debit
                $CRtmp['id'] = $purchaseReturnRow['id'];
                $CRtmp['type'] = "PURCHASERETURN";
                $CRtmp['date'] = $purchaseReturnRow['debit_note_date'];
                $CRtmp['desc'] = $purchaseReturnRow['vendor_name'].', Debit Note '.$purchaseReturnRow['debit_note_no'];
                $CRtmp['amount'] = ($taxable_amount+$total_gst+$roundoff_amount);
                $data['debit'][] = $CRtmp;

                // credit
                $DBtmp['id'] = $purchaseReturnRow['id'];
                $DBtmp['type'] = "PURCHASERETURN";
                $DBtmp['date'] = $purchaseReturnRow['debit_note_date'];
                $DBtmp['desc'] = 'Purchase Account, Debit Note '.$purchaseReturnRow['debit_note_no'];
                $DBtmp['amount'] = ($taxable_amount);
                $data['credit'][] = $DBtmp;

                $DBtmp1['id'] = $purchaseReturnRow['id'];
                $DBtmp1['type'] = "PURCHASERETURN";
                $DBtmp1['date'] = $purchaseReturnRow['debit_note_date'];
                $DBtmp1['desc'] = ($purchaseReturnRow['state_code'] == $current_state) ? 'GST On Purchase, Debit Note '.$purchaseReturnRow['debit_note_no'] : 'IGST On Purchase, Debit Note '.$purchaseReturnRow['debit_note_no'];
                $DBtmp1['amount'] = ($total_gst);
                $data['credit'][] = $DBtmp1;

                if($roundoff_amount != 0 && $roundoff_amount != ''){
                    $DBtmp2['id'] = $purchaseReturnRow['id'];
                    $DBtmp2['type'] = "PURCHASERETURN";
                    $DBtmp2['date'] = $purchaseReturnRow['debit_note_date'];
                    $DBtmp2['desc'] = 'Round Off, Debit Note '.$purchaseReturnRow['debit_note_no'];
                    $DBtmp2['amount'] = abs($roundoff_amount);
                    if($roundoff_amount > 0){
                        $data['credit'][] = $DBtmp2;
                    }else{
                        $data['debit'][] = $DBtmp2;
                    }
                    
                }
            }
            unset($taxable_amount, $total_gst, $roundoff_amount, $CRtmp, $DBtmp, $DBtmp1, $DBtmp2);
        }
        /*--------------------------PURCHASE RETURN END--------------------------*/
        
        /*---------------------CASH TRANSACTION START------------------------------*/
        $cashTransactionQ = "SELECT ct.id, ct.payment_type, ct.voucher_no, ct.voucher_date, ct.amount, lg.name as party_name FROM cash_transaction ct LEFT JOIN ledger_master lg ON ct.perticular = lg.id WHERE ct.pharmacy_id = '".$pharmacy_id."'";
        if(isset($is_financial) && $is_financial == 1){
            $cashTransactionQ .= " AND ct.financial_id = '".$financial_id."'";
        }
        if((isset($from) && $from != '') && (isset($to) && $to != '')){
          $cashTransactionQ .= " AND ct.voucher_date >= '".$from."' AND ct.voucher_date <= '".$to."'";
        }
        $cashTransactionR = mysqli_query($conn, $cashTransactionQ);
        if($cashTransactionR && mysqli_num_rows($cashTransactionR) > 0){
            while($cashTransactionRow = mysqli_fetch_assoc($cashTransactionR)){
                // credit-debit
                $tmp['id'] = $cashTransactionRow['id'];
                $tmp['type'] = "CASHTRANSACTION";
                $tmp['date'] = $cashTransactionRow['voucher_date'];
                $lable = (isset($cashTransactionRow['payment_type']) && $cashTransactionRow['payment_type'] == 'payment') ? 'Payment' : 'Receipt';
                $tmp['desc'] = $cashTransactionRow['party_name'].',  Cash '.$lable.' '.$cashTransactionRow['voucher_no'];
                $tmp['amount'] = (isset($cashTransactionRow['amount']) && $cashTransactionRow['amount'] != '') ? $cashTransactionRow['amount'] : 0;
                if(isset($cashTransactionRow['payment_type']) && $cashTransactionRow['payment_type'] == 'payment'){
                    $data['credit'][] = $tmp;
                }else{
                    $data['debit'][] = $tmp;
                }
            }
            unset($tmp, $lable);
        }
        /*---------------------CASH TRANSACTION END------------------------------*/
        
        /*-----------------------BANK TRANSACTION START-----------------------------*/
        $bankTransactionQ = "SELECT bt.id, bt.payment_type, bt.voucher_no, bt.voucher_date,bt.amount, bt.payment_mode, bt.payment_mode_date, bt.payment_mode_no, bt.card_name, bt.other_reference, lg.name as party_name FROM bank_transaction bt LEFT JOIN ledger_master lg ON bt.perticular = lg.id WHERE bt.pharmacy_id = '".$pharmacy_id."'";
        if(isset($is_financial) && $is_financial == 1){
            $bankTransactionQ .= " AND bt.financial_id = '".$financial_id."'";
        }
        if((isset($from) && $from != '') && (isset($to) && $to != '')){
          $bankTransactionQ .= " AND bt.voucher_date >= '".$from."' AND bt.voucher_date <= '".$to."'";
        }
        $bankTransactionR = mysqli_query($conn, $bankTransactionQ);
        if($bankTransactionR && mysqli_num_rows($bankTransactionR) > 0){
            while($bankTransactionRow = mysqli_fetch_assoc($bankTransactionR)){
                // credit-debit
                $tmp['id'] = $bankTransactionRow['id'];
                $tmp['type'] = "BANKTRANSACTION";
                $tmp['date'] = $bankTransactionRow['voucher_date'];
                $lable = (isset($bankTransactionRow['payment_type']) && $bankTransactionRow['payment_type'] == 'payment') ? 'Payment' : 'Receipt';
                
                if($bankTransactionRow['payment_mode'] == 'cheque'){
                    $sublable = (isset($bankTransactionRow['payment_mode_no']) && $bankTransactionRow['payment_mode_no'] != '') ? ', Check No: '.$bankTransactionRow['payment_mode_no'] : '';
                }elseif($bankTransactionRow['payment_mode'] == 'dd'){
                    $sublable = (isset($bankTransactionRow['payment_mode_no']) && $bankTransactionRow['payment_mode_no'] != '') ? ', DD No: '.$bankTransactionRow['payment_mode_no'] : '';
                }elseif($bankTransactionRow['payment_mode'] == 'net_banking'){
                    $sublable = (isset($bankTransactionRow['payment_mode_no']) && $bankTransactionRow['payment_mode_no'] != '') ? ', UTR No: '.$bankTransactionRow['payment_mode_no'] : '';
                }elseif($bankTransactionRow['payment_mode'] == 'credit_debit_card'){
                    $sublable = (isset($bankTransactionRow['payment_mode_no']) && $bankTransactionRow['payment_mode_no'] != '') ? ', Card No: '.$bankTransactionRow['payment_mode_no'] : '';
                }elseif($bankTransactionRow['payment_mode'] == 'other'){
                    $sublable = (isset($bankTransactionRow['other_reference']) && $bankTransactionRow['other_reference'] != '') ? ', Other Ref.: '.$bankTransactionRow['other_reference'] : '';
                }else{
                    $sublable = '';
                }
                
                $tmp['desc'] = $bankTransactionRow['party_name'].',  Bank '.$lable.' '.$bankTransactionRow['voucher_no'].''.$sublable;
                $tmp['amount'] = (isset($bankTransactionRow['amount']) && $bankTransactionRow['amount'] != '') ? $bankTransactionRow['amount'] : 0;
                if(isset($bankTransactionRow['payment_type']) && $bankTransactionRow['payment_type'] == 'payment'){
                    $data['credit'][] = $tmp;
                }else{
                    $data['debit'][] = $tmp;
                }
            }
            unset($tmp, $lable, $sublable);
        }
        /*-----------------------BANK TRANSACTION END------------------------------*/
        
        /*---------------------JOURNAL VOUCHER START START-----------------------------*/
        $jvQ = "SELECT jvd.debit, jvd.credit, jv.id, jv.voucher_date, jv.voucher_no, lg.name as party_name FROM `journal_vouchar_details` jvd INNER JOIN journal_vouchar jv ON jvd.voucher_id = jv.id LEFT JOIN ledger_master lg ON jvd.particular = lg.id WHERE jv.pharmacy_id = '".$pharmacy_id."'";
        if(isset($is_financial) && $is_financial == 1){
            $jvQ .= " AND jv.financial_id = '".$financial_id."'";
        }
        if((isset($from) && $from != '') && (isset($to) && $to != '')){
          $jvQ .= " AND jv.voucher_date >= '".$from."' AND jv.voucher_date <= '".$to."'";
        }
        $jvR = mysqli_query($conn, $jvQ);
        if($jvR && mysqli_num_rows($jvR) > 0){
            while($jvRow = mysqli_fetch_assoc($jvR)){
                // credit-debit
                $tmp['id'] = $jvRow['id'];
                $tmp['type'] = "JOURNALVOUCHER";
                $tmp['date'] = $jvRow['voucher_date'];
                $vno = (isset($jvRow['voucher_no']) && $jvRow['voucher_no'] != '') ? ' No: '.$jvRow['voucher_no'] : '';
                $tmp['desc'] = $jvRow['party_name'].',  Journal Voucher'.$vno;
                if(isset($jvRow['debit']) && $jvRow['debit'] != '' && $jvRow['debit'] != 0){
                    $tmp['amount'] = $jvRow['debit'];
                    $data['debit'][] = $tmp;
                }else{
                    $tmp['amount'] = (isset($jvRow['credit']) && $jvRow['credit'] != '') ? $jvRow['credit'] : 0;
                    $data['credit'][] = $tmp;
                }
            }
            unset($tmp, $vno);
        }
        /*---------------------JOURNAL VOUCHER START END-----------------------------*/
        
        function date_compare($a, $b){
    
            $t1 = strtotime($a['date']);

            $t2 = strtotime($b['date']);

            return $t1 - $t2;
        }
        
        if(isset($data['credit']) && !empty($data['credit'])){
            usort($data['credit'], 'date_compare');
        }
        
        if(isset($data['debit']) && !empty($data['debit'])){
            usort($data['debit'], 'date_compare');
        }
        
        /*--------------------CASH ON HAND START-------------------------*/
            $cahOnHandData = daybookCashOnHand($from, $is_financial);
            $cash_on_hand = (isset($cahOnHandData['cash_on_hand']) && $cahOnHandData['cash_on_hand'] != '') ? $cahOnHandData['cash_on_hand'] : 0;
            $arr['id'] = '';
            $arr['type'] = "CASHONHAND";
            $arr['date'] = $from;
            if($cash_on_hand >= 0){
                $arr['desc'] = 'Cash On Hand Db.';
                $arr['amount'] = $cash_on_hand;
                array_unshift($data['debit'],$arr);
            }else{
                $arr['desc'] = 'Cash On Hand Cr.';
                $arr['amount'] = abs($cash_on_hand);
                array_unshift($data['credit'],$arr);
            }
        unset($arr);
        /*--------------------CASH ON HAND END-------------------------*/
        
        return $data;
    }
    /*-----------------------------DAY BOOK REPORT- GAUTAM MAKWANA  - 24-01-2018 - END------------------------------*/
    
    /*-----------------------------DAY BOOK REPORT GET CASH ON HAND - GAUTAM MAKWANA - 24-01-2018 - START---------------------------*/
    function daybookCashOnHand($from = null, $is_financial = 0){
        global $conn;
        global $pharmacy_id;
        global $financial_id;
        $current_state = (isset($_SESSION['state_code'])) ? $_SESSION['state_code'] : '';

        $data['credit'] = 0;
        $data['debit'] = 0;

        /*---------------------------SALE BILL START---------------------------------*/
        $saleQ = "SELECT tb.id, ROUND(SUM(tbd.totalamount-tbd.gst_tax), 2) as taxable_amount, SUM(tbd.gst_tax) as total_gst, tb.roundoff_amount FROM tax_billing tb INNER JOIN tax_billing_details tbd ON tb.id = tbd.tax_bill_id WHERE tb.pharmacy_id = '".$pharmacy_id."'";
        if(isset($is_financial) && $is_financial == 1){
            $saleQ .= " AND tb.financial_id = '".$financial_id."'";
        }
        if(isset($from) && $from != ''){
          $saleQ .= " AND tb.invoice_date < '".$from."'";
        }
        $saleQ.= " GROUP BY tbd.tax_bill_id";
        
        $saleR = mysqli_query($conn, $saleQ);
        if($saleR && mysqli_num_rows($saleR) > 0){
            while ($saleRow = mysqli_fetch_assoc($saleR)) {
                $taxable_amount = (isset($saleRow['taxable_amount']) && $saleRow['taxable_amount'] != '') ? $saleRow['taxable_amount'] : 0;
                $total_gst = (isset($saleRow['total_gst']) && $saleRow['total_gst'] != '') ? $saleRow['total_gst'] : 0;
                $roundoff_amount = (isset($saleRow['roundoff_amount']) && $saleRow['roundoff_amount'] != '') ? $saleRow['roundoff_amount'] : 0;

                // debit
                $data['debit'] += ($taxable_amount+$total_gst+$roundoff_amount);

                // credit
                $data['credit'] += ($taxable_amount+$total_gst);

                if($roundoff_amount != 0 && $roundoff_amount != ''){
                    if($roundoff_amount > 0){
                        $data['credit'] += abs($roundoff_amount);
                    }else{
                        $data['debit'] += abs($roundoff_amount);
                    }
                }
            }
            unset($taxable_amount, $total_gst, $roundoff_amount);
        }
        /*---------------------------SALE BILL END---------------------------------*/

        /*---------------------------SALE RETURN START-----------------------------*/
        $saleReturnQ = "SELECT sr.id, sr.credit_note_no, sr.credit_note_date, ROUND(SUM(srd.amount-srd.gst_tax), 2) as taxable_amount, SUM(srd.gst_tax) as total_gst, SUM(srd.amount) as total_amount, ROUND((ROUND(SUM(srd.amount))-SUM(srd.amount)), 2) as round_off FROM `sale_return` sr INNER JOIN sale_return_details srd ON sr.id = srd.sale_return_id WHERE sr.pharmacy_id = '".$pharmacy_id."'";
        if(isset($is_financial) && $is_financial == 1){
            $saleReturnQ .= " AND sr.financial_id = '".$financial_id."'";
        }
        if(isset($from) && $from != ''){
          $saleReturnQ .= " AND sr.credit_note_date < '".$from."'";
        }
        $saleReturnQ .="  GROUP BY srd.sale_return_id";
        $saleReturnR = mysqli_query($conn, $saleReturnQ);
        if($saleReturnR && mysqli_num_rows($saleReturnR) > 0){
            while($saleReturnRow = mysqli_fetch_assoc($saleReturnR)){
                $taxable_amount = (isset($saleReturnRow['taxable_amount']) && $saleReturnRow['taxable_amount'] != '') ? $saleReturnRow['taxable_amount'] : 0;
                $total_gst = (isset($saleReturnRow['total_gst']) && $saleReturnRow['total_gst'] != '') ? $saleReturnRow['total_gst'] : 0;
                $roundoff_amount = (isset($saleReturnRow['round_off']) && $saleReturnRow['round_off'] != '') ? $saleReturnRow['round_off'] : 0;

                // credit
                $data['credit'] += ($taxable_amount+$total_gst+$roundoff_amount);

                // debit
                $data['debit'] += ($taxable_amount+$total_gst);

                if($roundoff_amount != 0 && $roundoff_amount != ''){
                    if($roundoff_amount > 0){
                        $data['debit'] += abs($roundoff_amount);
                    }else{
                        $data['credit'] += abs($roundoff_amount);
                    }
                }
            }
            unset($taxable_amount, $total_gst, $roundoff_amount);
        }
        /*---------------------------SALE RETURN END-----------------------------*/

        /*---------------------------PURCHASE BILL START---------------------------*/
        $purchaseQ = "SELECT p.id, p.overall_value as taxable_amount, (p.total_igst+p.total_cgst+p.total_sgst) as gst_tax, p.round_off FROM `purchase` p WHERE p.pharmacy_id = '".$pharmacy_id."'";
        if(isset($is_financial) && $is_financial == 1){
            $purchaseQ .= " AND p.financial_id = '".$financial_id."'";
        }
        if(isset($from) && $from != ''){
          $purchaseQ .= " AND p.invoice_date < '".$from."'";
        }
        $purchaseR = mysqli_query($conn, $purchaseQ);
        if($purchaseR && mysqli_num_rows($purchaseR) > 0){
            while($purchaseRow = mysqli_fetch_assoc($purchaseR)){
                $taxable_amount = (isset($purchaseRow['taxable_amount']) && $purchaseRow['taxable_amount'] != '') ? $purchaseRow['taxable_amount'] : 0;
                $total_gst = (isset($purchaseRow['gst_tax']) && $purchaseRow['gst_tax'] != '') ? $purchaseRow['gst_tax'] : 0;
                $roundoff_amount = (isset($purchaseRow['round_off']) && $purchaseRow['round_off'] != '') ? $purchaseRow['round_off'] : 0;

                // credit
                $data['credit'] += ($taxable_amount+$total_gst+$roundoff_amount);

                // debit
                $data['debit'] += ($taxable_amount+$total_gst);

                if($roundoff_amount != 0 && $roundoff_amount != ''){
                    if($roundoff_amount > 0){
                        $data['debit'] += abs($roundoff_amount);
                    }else{
                        $data['credit'] += abs($roundoff_amount);
                    }
                }
            }
            unset($taxable_amount, $total_gst, $roundoff_amount);
        }
        /*---------------------------PURCHASE BILL END-----------------------------*/

        /*--------------------------PURCHASE RETURN START--------------------------*/
        $purchaseReturnQ = "SELECT pr.id, pr.totalamount as taxable_amount, (pr.igst+pr.cgst+pr.sgst) as gst_tax, ROUND((ROUND(pr.finalamount)-(pr.finalamount)), 2) as round_off FROM `purchase_return` pr WHERE pr.pharmacy_id = '".$pharmacy_id."'";
        if(isset($is_financial) && $is_financial == 1){
            $purchaseReturnQ .= " AND pr.financial_id = '".$financial_id."'";
        }
        if(isset($from) && $from != ''){
          $purchaseReturnQ .= " AND pr.debit_note_date < '".$from."'";
        }
        $purchaseReturnR = mysqli_query($conn, $purchaseReturnQ);
        if($purchaseReturnR && mysqli_num_rows($purchaseReturnR) > 0){
            while($purchaseReturnRow = mysqli_fetch_assoc($purchaseReturnR)){
                $taxable_amount = (isset($purchaseReturnRow['taxable_amount']) && $purchaseReturnRow['taxable_amount'] != '') ? $purchaseReturnRow['taxable_amount'] : 0;
                $total_gst = (isset($purchaseReturnRow['gst_tax']) && $purchaseReturnRow['gst_tax'] != '') ? $purchaseReturnRow['gst_tax'] : 0;
                $roundoff_amount = (isset($purchaseReturnRow['round_off']) && $purchaseReturnRow['round_off'] != '') ? $purchaseReturnRow['round_off'] : 0;

                // debit
                $data['debit'] += ($taxable_amount+$total_gst+$roundoff_amount);

                // credit
                $data['credit'] += ($taxable_amount+$total_gst);

                if($roundoff_amount != 0 && $roundoff_amount != ''){
                    if($roundoff_amount > 0){
                        $data['credit'] += abs($roundoff_amount);
                    }else{
                        $data['debit'] += abs($roundoff_amount);
                    }
                }
            }
            unset($taxable_amount, $total_gst, $roundoff_amount);
        }
        /*--------------------------PURCHASE RETURN END--------------------------*/

        /*---------------------CASH TRANSACTION START------------------------------*/
        $cashTransactionQ = "SELECT id, payment_type, amount FROM cash_transaction WHERE pharmacy_id = '".$pharmacy_id."'";
        if(isset($is_financial) && $is_financial == 1){
            $cashTransactionQ .= " AND financial_id = '".$financial_id."'";
        }
        if(isset($from) && $from != ''){
          $cashTransactionQ .= " AND voucher_date < '".$from."'";
        }
        $cashTransactionR = mysqli_query($conn, $cashTransactionQ);
        if($cashTransactionR && mysqli_num_rows($cashTransactionR) > 0){
            while($cashTransactionRow = mysqli_fetch_assoc($cashTransactionR)){
                // credit-debit
                if(isset($cashTransactionRow['payment_type']) && $cashTransactionRow['payment_type'] == 'payment'){
                    $data['credit'] += (isset($cashTransactionRow['amount']) && $cashTransactionRow['amount'] != '') ? $cashTransactionRow['amount'] : 0;
                }else{
                    $data['debit'] += (isset($cashTransactionRow['amount']) && $cashTransactionRow['amount'] != '') ? $cashTransactionRow['amount'] : 0;
                }
            }
        }
        /*---------------------CASH TRANSACTION END------------------------------*/

        /*-----------------------BANK TRANSACTION START-----------------------------*/
        $bankTransactionQ = "SELECT id, payment_type, amount FROM bank_transaction WHERE pharmacy_id = '".$pharmacy_id."'";
        if(isset($is_financial) && $is_financial == 1){
            $bankTransactionQ .= " AND financial_id = '".$financial_id."'";
        }
        if(isset($from) && $from != ''){
          $bankTransactionQ .= " AND voucher_date < '".$from."'";
        }
        $bankTransactionR = mysqli_query($conn, $bankTransactionQ);
        if($bankTransactionR && mysqli_num_rows($bankTransactionR) > 0){
            while($bankTransactionRow = mysqli_fetch_assoc($bankTransactionR)){
                // credit-debit
                if(isset($bankTransactionRow['payment_type']) && $bankTransactionRow['payment_type'] == 'payment'){
                    $data['credit'] += (isset($bankTransactionRow['amount']) && $bankTransactionRow['amount'] != '') ? $bankTransactionRow['amount'] : 0;
                }else{
                    $data['debit'] += (isset($bankTransactionRow['amount']) && $bankTransactionRow['amount'] != '') ? $bankTransactionRow['amount'] : 0;
                }
            }
        }
        /*-----------------------BANK TRANSACTION END------------------------------*/

        /*---------------------JOURNAL VOUCHER START START-----------------------------*/
        $jvQ = "SELECT jvd.debit, jvd.credit, jv.id FROM `journal_vouchar_details` jvd INNER JOIN journal_vouchar jv ON jvd.voucher_id = jv.id WHERE jv.pharmacy_id = '".$pharmacy_id."'";
        if(isset($is_financial) && $is_financial == 1){
            $jvQ .= " AND jv.financial_id = '".$financial_id."'";
        }
        if(isset($from) && $from != ''){
          $jvQ .= " AND jv.voucher_date < '".$from."'";
        }
        $jvR = mysqli_query($conn, $jvQ);
        if($jvR && mysqli_num_rows($jvR) > 0){
            while($jvRow = mysqli_fetch_assoc($jvR)){
                // credit-debit
                if(isset($jvRow['debit']) && $jvRow['debit'] != '' && $jvRow['debit'] != 0){
                    $data['debit'] += $jvRow['debit'];
                }else{
                    $data['credit'] += (isset($jvRow['credit']) && $jvRow['credit'] != '') ? $jvRow['credit'] : 0;
                }
            }
        }
        /*---------------------JOURNAL VOUCHER START END-----------------------------*/

        $data['cash_on_hand'] = ($data['credit']-$data['debit']);

        return $data;
    }
    /*-----------------------------DAY BOOK REPORT GET CASH ON HAND - GAUTAM MAKWANA - 24-01-2018 - END---------------------------*/
    
    /*-----------------------------JV REGISTER REPORT- JIGAR BHALIYA  - 25-01-2018 - START------------------------------*/
    function JournalVoucherRegister($from = null, $to = null, $is_financial = 0){
    
        $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
        $financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';
    
        $data = [];
        global $conn;
        if(isset($pharmacy_id) && $pharmacy_id != ''){
    
            /*-----------------------------JOURNAL VOUCHER DETIALS START--------------------------------*/
                $journalVoucherQ = "SELECT jv.id, jv.voucher_date, jv.voucher_no, jv.remarks as voucher_remarks, jv.is_file, jv.file_name, jv.is_investment, jvd.type, jvd.particular, jvd.qty, jvd.rate, jvd.debit, jvd.credit,jvd.remarks as voucher_details_remarks,IF(jvd.debit !=0, 'debit','credit') as type, IF(jvd.debit !=0, jvd.debit, jvd.credit) as amount,lm.name as customer_name FROM journal_vouchar jv LEFT JOIN journal_vouchar_details jvd ON jv.id = jvd.voucher_id LEFT JOIN ledger_master lm ON jvd.particular = lm.id WHERE jv.pharmacy_id = '".$pharmacy_id."' ";
                if(isset($is_financial) && $is_financial == 1){
                    $journalVoucherQ .= " AND jv.financial_id = '".$financial_id."'";
                }
                if((isset($from) && $from != '') && (isset($to) && $to != '')){
                    $journalVoucherQ .= " AND jv.voucher_date >= '".$from."' AND jv.voucher_date <= '".$to."'";
                }
                // echo $journalVoucherQ;exit;
                $journalVoucherR = mysqli_query($conn, $journalVoucherQ);
                if($journalVoucherR && mysqli_num_rows($journalVoucherR) > 0){
                    while ($journalVoucherRow = mysqli_fetch_assoc($journalVoucherR)) {
    
                        $arr1['id'] = $journalVoucherRow['id'];
                        $arr1['date'] = (isset($journalVoucherRow['voucher_date'])) ? $journalVoucherRow['voucher_date'] : '';
                        $customer = (isset($journalVoucherRow['customer_name']) && $journalVoucherRow['customer_name'] != '') ? $journalVoucherRow['customer_name'] : 'Unknown Customer';
                        $voucher_no = (isset($journalVoucherRow['voucher_no']) && $journalVoucherRow['voucher_no'] != '') ? ' - ('.$journalVoucherRow['voucher_no'].')' : '';
                        $arr1['narration'] = 'Journal Voucher No'.$voucher_no.' - '.$customer;
                        $arr1['type'] = (isset($journalVoucherRow['type'])) ? $journalVoucherRow['type'] : '-';
    
                        $arr1['amount'] = (isset($journalVoucherRow['amount']) && $journalVoucherRow['amount'] != '') ? $journalVoucherRow['amount'] : 0;
                        $arr1['url'] = 'journal-vouchar.php?id='.$journalVoucherRow['id'];
    
                        if($journalVoucherRow['type'] == 'debit'){
                          $data['debit'][] = $arr1;
                        }else{
                          $data['credit'][] = $arr1;
                        }
    
                    }
                }
            /*----------------------------------JOURNAL VOUCHER END----------------------------------*/
    
        }
        if(!empty($data)){
            function date_compare($a, $b){
    
                $t1 = strtotime($a['date']);
    
                $t2 = strtotime($b['date']);
    
                return $t1 - $t2;
            }
            usort($data['debit'], 'date_compare');
            usort($data['credit'], 'date_compare');
        }
    
      return $data;
    }
    /*-----------------------------JV REGISTER REPORT- JIGAR BHALIYA  - 25-01-2018 - END------------------------------*/
    
    /*----------------CREDIT NOTE REPORT - GAUTAM MAKWANA- 28-01-2019 - START------------------------------*/
    function creditnoteReport($from = null, $to = null, $view = null, $is_financial = 0){
        global $conn;
        global $pharmacy_id;
        $cur_statecode = (isset($_SESSION['state_code'])) ? $_SESSION['state_code'] : '';
        $data = [];

        if($view == 'detail'){
            $query = "SELECT sr.id, sr.credit_note_no, sr.credit_note_date, lg.name as party_name, ct.name as city_name, st.state_code_gst as state_code FROM sale_return sr LEFT JOIN ledger_master lg ON sr.customer_id = lg.id LEFT JOIN own_cities ct ON lg.city = ct.id LEFT JOIN own_states st ON lg.state = st.id WHERE sr.pharmacy_id = '".$pharmacy_id."'";
            if(isset($is_financial) && $is_financial == 1){
                $query .= " AND sr.financial_id = '".$financial_id."'";
            }
            if((isset($from) && $from != '') && (isset($to) && $to != '')){
              $query .= " AND sr.credit_note_date >= '".$from."' AND sr.credit_note_date <= '".$to."'";
            }
            $query .= " ORDER BY sr.credit_note_date";
            $res = mysqli_query($conn, $query);
            if($res && mysqli_num_rows($res) > 0){
              while ($row = mysqli_fetch_assoc($res)) {
                  $arr = $row;
                  $arr['bill_amount'] = 0;
                  $arr['gst_on_sale'] = 0;
                  $arr['detail'] = [];
                  $state_code = (isset($row['state_code'])) ? $row['state_code'] : '';

                  $subQuery = "SELECT srd.id, srd.qty, srd.igst, srd.cgst, srd.sgst, srd.amount, pm.product_name FROM sale_return_details srd LEFT JOIN product_master pm ON srd.product_id = pm.id WHERE srd.sale_return_id = '".$row['id']."'";
                  $subRes = mysqli_query($conn, $subQuery);
                  if($subRes && mysqli_num_rows($subRes) > 0){
                    while ($subRow = mysqli_fetch_assoc($subRes)) {
                      $detail['id'] = (isset($subRow['id'])) ? $subRow['id'] : '';
                      $detail['item_name'] = (isset($subRow['product_name'])) ? $subRow['product_name'] : '';
                      $detail['qty'] = (isset($subRow['qty'])) ? $subRow['qty'] : '';
                      $detail['amount'] = (isset($subRow['amount']) && $subRow['amount'] != '') ? $subRow['amount'] : 0;

                      if($state_code == $cur_statecode){
                        $cgst = (isset($subRow['cgst']) && $subRow['cgst'] != '') ? $subRow['cgst'] : 0;
                        $sgst = (isset($subRow['sgst']) && $subRow['sgst'] != '') ? $subRow['sgst'] : 0;
                        $detail['gst_name'] = $cgst.'+'.$sgst;
                        $detail['gst'] = ($cgst+$sgst);

                        $detail['taxable_amount'] = round(($detail['amount']*100)/(100+$detail['gst']), 2);
                        $sgst_amount = round(($detail['taxable_amount']*$sgst/100), 2);
                        $cgst_amount = round(($detail['taxable_amount']*$cgst/100), 2);
                        $detail['gst_tax'] = ($sgst_amount+$cgst_amount);
                      }else{
                        $igst = (isset($subRow['igst']) && $subRow['igst'] != '') ? $subRow['igst'] : 0;
                        $detail['gst_name'] = $igst;
                        $detail['gst'] = $igst;

                        $detail['taxable_amount'] = round(($detail['amount']*100)/(100+$detail['gst']), 2);
                        $detail['gst_tax'] = round(($detail['taxable_amount']*$igst/100), 2);
                      }

                      $arr['bill_amount'] += $detail['amount'];
                      $arr['gst_on_sale'] += $detail['gst_tax'];
                      $arr['detail'][] = $detail;
                    }

                      // ADD ENTRY ON GST ON SALE
                      $tmp['id'] = '';
                      $tmp['item_name'] = ($state_code == $cur_statecode) ? 'GST ON Sale' : 'IGST ON Sale';
                      $tmp['qty'] = '';
                      $tmp['amount'] = (isset($arr['gst_on_sale']) && $arr['gst_on_sale'] != '') ? $arr['gst_on_sale'] : 0;
                      $tmp['gst_name'] = '0+0';
                      $tmp['gst'] = 0;
                      $tmp['taxable_amount'] = (isset($arr['gst_on_sale']) && $arr['gst_on_sale'] != '') ? $arr['gst_on_sale'] : 0;
                      $tmp['gst_tax'] = 0.00;
                      $arr['detail'] [] = $tmp;

                      // ADD ENTRY FOR ROUND OFF AMOUNT
                      $roundoff_amount = (round($arr['bill_amount'])-$arr['bill_amount']);
                      if($roundoff_amount != '' && $roundoff_amount != 0){
                        $tmp1['id'] = '';
                        $tmp1['item_name'] = 'Round Off';
                        $tmp1['qty'] = '';
                        $tmp1['amount'] = $roundoff_amount;
                        $tmp1['gst_name'] = '0+0';
                        $tmp1['gst'] = 0;
                        $tmp1['taxable_amount'] = $roundoff_amount;
                        $tmp1['gst_tax'] = 0.00;
                        $arr['detail'] [] = $tmp1;
                      }

                    // set bill value as round
                    $arr['bill_amount'] = round($arr['bill_amount']);

                  }
                $data[] = $arr;
              }
            }
        }elseif($view == 'summary'){
            $query = "SELECT sr.id, sr.credit_note_no, sr.credit_note_date, lg.name as party_name, lg.gstno, ct.name as city_name, ROUND(SUM((srd.amount*100)/(100+gst)), 2) as taxable_amount, ROUND(SUM(srd.gst_tax), 2) as tax_amount, ROUND(SUM(srd.amount), 2) as total_amount FROM sale_return sr INNER JOIN sale_return_details srd ON sr.id = srd.sale_return_id LEFT JOIN ledger_master lg ON sr.customer_id = lg.id LEFT JOIN own_cities ct ON lg.city = ct.id WHERE sr.pharmacy_id = '".$pharmacy_id."'";
            if(isset($is_financial) && $is_financial == 1){
                $query .= " AND sr.financial_id = '".$financial_id."'";
            }
            if((isset($from) && $from != '') && (isset($to) && $to != '')){
              $query .= " AND sr.credit_note_date >= '".$from."' AND sr.credit_note_date <= '".$to."'";
            }
            $query .= " GROUP BY srd.sale_return_id ORDER BY sr.credit_note_date";
            $res = mysqli_query($conn, $query);
            if($res && mysqli_num_rows($res) > 0){
              while ($row = mysqli_fetch_assoc($res)) {
                $data[] = $row;
              }
            }
        }
        return $data;
    }
    /*----------------CREDIT NOTE REPORT - GAUTAM MAKWANA- 28-01-2019 - END------------------------------*/
    
    /*----------------DEBIT NOTE REPORT - GAUTAM MAKWANA- 29-01-2019 - START------------------------------*/
    function debitnoteReport($from = null, $to = null, $view = null, $is_financial = 0){
        global $conn;
        global $pharmacy_id;
        $cur_statecode = (isset($_SESSION['state_code'])) ? $_SESSION['state_code'] : '';
        $data = [];
        
        if($view == 'detail'){
            $query = "SELECT pr.id, pr.debit_note_date, pr.debit_note_no, lg.name as party_name, ct.name as city_name, st.state_code_gst as state_code FROM `purchase_return` pr LEFT JOIN ledger_master lg ON pr.vendor_id = lg.id LEFT JOIN own_cities ct ON lg.city = ct.id LEFT JOIN own_states st ON lg.state = st.id WHERE pr.pharmacy_id = '".$pharmacy_id."'";
            if(isset($is_financial) && $is_financial == 1){
                $query .= " AND pr.financial_id = '".$financial_id."'";
            }
            if((isset($from) && $from != '') && (isset($to) && $to != '')){
              $query .= " AND pr.debit_note_date >= '".$from."' AND pr.debit_note_date <= '".$to."'";
            }
            $query .= " ORDER BY pr.debit_note_date";
            $res = mysqli_query($conn, $query);
            if($res && mysqli_num_rows($res) > 0){
                while ($row = mysqli_fetch_assoc($res)) {
                    $arr = $row;
                    $arr['bill_amount'] = 0;
                    $arr['gst_on_purchase'] = 0;
                    $arr['detail'] = [];
                    $state_code = (isset($row['state_code'])) ? $row['state_code'] : '';
                    
                    $subQuery = "SELECT prd.id, prd.qty, prd.discount, prd.final_rate as rate, prd.igst, prd.cgst, prd.sgst, ROUND((prd.qty*prd.final_rate), 2) as taxable_amount, pm.product_name FROM purchase_return_detail prd LEFT JOIN product_master pm ON prd.product_id = pm.id WHERE prd.pr_id = '".$row['id']."'";
                    $subRes = mysqli_query($conn, $subQuery);
                    if($subRes && mysqli_num_rows($subRes)){
                        while($subRow = mysqli_fetch_assoc($subRes)){
                            $detail['id'] = (isset($subRow['id'])) ? $subRow['id'] : '';
                            $detail['item_name'] = (isset($subRow['product_name'])) ? $subRow['product_name'] : '';
                            $detail['qty'] = (isset($subRow['qty']) && $subRow['qty'] != '') ? $subRow['qty'] : 0;
                            $detail['taxable_amount'] = (isset($subRow['taxable_amount']) && $subRow['taxable_amount'] != '') ? $subRow['taxable_amount'] : 0;
                            
                            if($state_code == $cur_statecode){
                                $cgst = (isset($subRow['cgst']) && $subRow['cgst'] != '') ? $subRow['cgst'] : 0;
                                $sgst = (isset($subRow['sgst']) && $subRow['sgst'] != '') ? $subRow['sgst'] : 0;
                                
                                $detail['gst_name'] = $cgst.'+'.$sgst;
                                $detail['gst'] = ($cgst+$sgst);
                                $sgst_amount = round(($detail['taxable_amount']*$sgst/100), 2);
                                $cgst_amount = round(($detail['taxable_amount']*$cgst/100), 2);
                                $detail['gst_tax'] = ($sgst_amount+$cgst_amount);
                                $detail['amount'] = round(($detail['taxable_amount']+$detail['gst_tax']), 2);
                            }else{
                                $igst = (isset($subRow['igst']) && $subRow['igst'] != '') ? $subRow['igst'] : 0;
                                $detail['gst_name'] = $igst;
                                $detail['gst'] = $igst;
                                $detail['gst_tax'] = round(($detail['taxable_amount']*$igst/100), 2);
                                $detail['amount'] = round(($detail['taxable_amount']+$detail['gst_tax']), 2);
                            }
                            $arr['bill_amount'] += $detail['amount'];
                            $arr['gst_on_purchase'] += $detail['gst_tax'];
                            $arr['detail'][] = $detail;
                        }
                        
                        // ADD ENTRY ON GST ON PURCHASE
                        $tmp['id'] = '';
                        $tmp['item_name'] = ($state_code == $cur_statecode) ? 'GST ON Purchase' : 'IGST ON Purchase';
                        $tmp['qty'] = '';
                        $tmp['taxable_amount'] = (isset($arr['gst_on_purchase']) && $arr['gst_on_purchase'] != '') ? $arr['gst_on_purchase'] : 0;
                        $tmp['gst_name'] = '0+0';
                        $tmp['gst'] = 0;
                        $tmp['gst_tax'] = 0.00;
                        $tmp['amount'] = (isset($arr['gst_on_purchase']) && $arr['gst_on_purchase'] != '') ? $arr['gst_on_purchase'] : 0;
                        $arr['detail'] [] = $tmp;

                        // ADD ENTRY FOR ROUND OFF AMOUNT
                        $roundoff_amount = (round($arr['bill_amount'])-$arr['bill_amount']);
                        if($roundoff_amount != '' && $roundoff_amount != 0){
                            $tmp1['id'] = '';
                            $tmp1['item_name'] = 'Round Off';
                            $tmp1['qty'] = '';
                            $tmp1['taxable_amount'] = $roundoff_amount;
                            $tmp1['gst_name'] = '0+0';
                            $tmp1['gst'] = 0;
                            $tmp1['gst_tax'] = 0.00;
                            $tmp1['amount'] = $roundoff_amount;
                            $arr['detail'] [] = $tmp1;
                        }
                        
                        // set bill value as round
                        $arr['bill_amount'] = round($arr['bill_amount']);
                    }
                    $data[] = $arr;
                }
            }
            
        }elseif($view == 'summary'){
            $query = "SELECT pr.id, pr.debit_note_date, pr.debit_note_no, lg.name as party_name, lg.gstno, ct.name as city_name, st.state_code_gst as state_code, ROUND(SUM(prd.qty*prd.final_rate), 2) as taxable_amount, ROUNd(SUM(((prd.qty*prd.final_rate)*prd.igst/100)), 2) as total_igst, ROUNd(SUM(((prd.qty*prd.final_rate)*prd.cgst/100)), 2) as total_cgst, ROUNd(SUM(((prd.qty*prd.final_rate)*prd.sgst/100)), 2) as total_sgst FROM purchase_return pr INNER JOIN purchase_return_detail prd ON pr.id = prd.pr_id LEFT JOIN ledger_master lg ON pr.vendor_id = lg.id LEFT JOIN own_cities ct ON lg.city = ct.id LEFT JOIN own_states st ON lg.state = st.id WHERE pr.pharmacy_id = '".$pharmacy_id."'";
            if(isset($is_financial) && $is_financial == 1){
                $query .= " AND pr.financial_id = '".$financial_id."'";
            }
            if((isset($from) && $from != '') && (isset($to) && $to != '')){
              $query .= " AND pr.debit_note_date >= '".$from."' AND pr.debit_note_date <= '".$to."'";
            }
            $query .= " GROUP BY prd.pr_id ORDER BY pr.debit_note_date";
            $res = mysqli_query($conn, $query);
            if($res && mysqli_num_rows($res) > 0){
                while($row = mysqli_fetch_assoc($res)){
                    $state_code = (isset($row['state_code'])) ? $row['state_code'] : '';
                    $row['taxable_amount'] = (isset($row['taxable_amount']) && $row['taxable_amount'] != '') ? $row['taxable_amount'] : 0;
                    if($state_code == $cur_statecode){
                        $sgst_amount = (isset($row['total_sgst']) && $row['total_sgst'] != '') ? $row['total_sgst'] : 0;
                        $cgst_amount = (isset($row['total_cgst']) && $row['total_cgst'] != '') ? $row['total_cgst'] : 0;
                        $row['tax_amount'] = ($sgst_amount+$cgst_amount);
                        $row['total_amount'] = ($row['taxable_amount']+$row['tax_amount']);
                    }else{
                        $igst_amount = (isset($row['total_igst']) && $row['total_igst'] != '') ? $row['total_igst'] : 0;
                        $row['tax_amount'] = $igst_amount;
                        $row['total_amount'] = ($row['taxable_amount']+$row['tax_amount']);
                    }
                    $data[] = $row;
                }
            }
            
        }
        return $data;
    }
    /*----------------DEBIT NOTE REPORT - GAUTAM MAKWANA- 29-01-2019 - END------------------------------*/
    
    /*----------------------LEDGER SUMMARY REPORT - JIGAR BHALIYA -- 29-01-2019----------------------------------*/
    function LedgerSummaryReport($from = null, $to = null, $city_id = null, $ledger_id = null, $is_financial = 0){

    $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
    $financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';
    
        $data = [];
        global $conn;
        if(isset($pharmacy_id) && $pharmacy_id != ''){
    
            /*-----------------------------Ledger Summary Report START--------------------------------*/
                $ledgerSummaryQ = "SELECT id, name, group_id FROM ledger_master WHERE group_id IN (10,14) AND pharmacy_id = '".$pharmacy_id."'";
                if(isset($city_id) && !empty($city_id)){
                  $ledgerSummaryQ .= " AND city = '".$city_id."'";
                } 
                if(isset($ledger_id) && !empty($ledger_id) ){
                  $ledgerSummaryQ .= " AND id = '".$ledger_id."'";
                }
                
                $ledgerSummaryR = mysqli_query($conn, $ledgerSummaryQ);
                if($ledgerSummaryR && mysqli_num_rows($ledgerSummaryR) > 0){
                    while ($ledgerSummaryRow = mysqli_fetch_assoc($ledgerSummaryR)) {

                      $id = $ledgerSummaryRow['id'];

                      //Running Balance
                      $countRunningBalance =  countRunningBalance($id ,$from, $to, $is_financial);
                      $data['running_balance'] = (isset($countRunningBalance['running_balance']) && $countRunningBalance['running_balance'] != '') ? $countRunningBalance['running_balance'] : 0;

                      //Tax Billing 
                      if(isset($ledgerSummaryRow['group_id']) && $ledgerSummaryRow['group_id'] == 10){//for customer only
            
                          $taxbillingQ = "SELECT id, invoice_date, invoice_no, bill_type, final_amount FROM tax_billing WHERE pharmacy_id = '".$pharmacy_id."' AND bill_type = 'Debit' AND customer_id = '".$id."'";
                          if(isset($is_financial) && $is_financial == 1){
                              $taxbillingQ .= " AND financial_id = '".$financial_id."'";
                          }
                          if((isset($from) && $from != '') && (isset($to) && $to != '')){
                            $taxbillingQ .= " AND invoice_date >= '".$from."' AND invoice_date <= '".$to."'";
                          }
                          $taxbillingR = mysqli_query($conn, $taxbillingQ);
                          if($taxbillingR && mysqli_num_rows($taxbillingR) > 0){
                              while ($taxbillingRow = mysqli_fetch_assoc($taxbillingR)) {
                                  $tmp1['id'] = (isset($taxbillingRow['id'])) ? $taxbillingRow['id'] : '';
                                  $tmp1['type'] = 'SALEBILL';
                                  $tmp1['date'] = (isset($taxbillingRow['invoice_date'])) ? $taxbillingRow['invoice_date'] : '';
                                  $tmp1['no'] = (isset($taxbillingRow['invoice_no'])) ? $taxbillingRow['invoice_no'] : '';
                                  $tmp1['narration'] = (isset($taxbillingRow['invoice_no']) && $taxbillingRow['invoice_no'] != '') ? 'Sale Bill - '.$taxbillingRow['invoice_no'] : 'Tax Bill';
                                  $tmp1['debit'] = (isset($taxbillingRow['final_amount']) && $taxbillingRow['final_amount'] != '') ? $taxbillingRow['final_amount'] : 0;
                                  $tmp1['credit'] = '';
                                  $tmp1['url'] = 'sales-tax-billing.php?id='.$tmp1['id'];
                                  $detail[] = $tmp1;
                              }
                          }
                      }

                      //Sale Return
                      if(isset($ledgerSummaryRow['group_id']) && $ledgerSummaryRow['group_id'] == 10){//for customer only
                          $saleReturnQ = "SELECT id, credit_note_no, credit_note_date, remarks, finalamount FROM sale_return WHERE pharmacy_id = '".$pharmacy_id."' AND customer_id = '".$id."'";
                          
                          if(isset($is_financial) && $is_financial == 1){
                              $saleReturnQ .= " AND financial_id = '".$financial_id."'";
                          }
                          if((isset($from) && $from != '') && (isset($to) && $to != '')){
                            $saleReturnQ .= " AND credit_note_date >= '".$from."' AND credit_note_date <= '".$to."'";
                          }
                          $saleReturnR = mysqli_query($conn, $saleReturnQ);
                          if(isset($saleReturnR) && mysqli_num_rows($saleReturnR) > 0){
                              while($saleReturnRow = mysqli_fetch_assoc($saleReturnR)){
                                  $tmp2['id'] = (isset($saleReturnRow['id'])) ? $saleReturnRow['id'] : '';
                                  $tmp2['type'] = 'SALERETURNBILL';
                                  $tmp2['date'] = (isset($saleReturnRow['credit_note_date'])) ? $saleReturnRow['credit_note_date'] : '';
                                  $tmp2['no'] = (isset($saleReturnRow['credit_note_no'])) ? $saleReturnRow['credit_note_no'] : '';
                                  $creditNoteNo = (isset($saleReturnRow['credit_note_no']) && $saleReturnRow['credit_note_no'] != '') ? ' - '.$saleReturnRow['credit_note_no'] : '';
                                  $remarks = (isset($saleReturnRow['remarks']) && trim($saleReturnRow['remarks']) != '') ? ' - '.$saleReturnRow['remarks'] : '';
                                  $tmp2['narration'] = 'Credit Note'.$creditNoteNo.''.$creditNoteNo.''.$remarks;
                                  $tmp2['debit'] = '';
                                  $tmp2['credit'] = (isset($saleReturnRow['finalamount']) && $saleReturnRow['finalamount'] != '') ? $saleReturnRow['finalamount'] : 0;
                                  $tmp2['url'] = 'sales-return.php?id='.$saleReturnRow['id'];
                                  $detail[] = $tmp2;
                              }
                          }
                      }

                      //Purchase
                      if(isset($ledgerSummaryRow['group_id']) && $ledgerSummaryRow['group_id'] == 14){//for vendor only
                          $purchaseQ = "SELECT id, invoice_no, invoice_date, purchase_type, total_total FROM purchase WHERE pharmacy_id = '".$pharmacy_id."' AND purchase_type = 'Debit' AND vendor = '".$id."'";
                          
                          if(isset($is_financial) && $is_financial == 1){
                              $purchaseQ .= " AND financial_id = '".$financial_id."'";
                          }
                          if((isset($from) && $from != '') && (isset($to) && $to != '')){
                            $purchaseQ .= " AND invoice_date >= '".$from."' AND invoice_date <= '".$to."'";
                          }
                          $purchaseR = mysqli_query($conn, $purchaseQ);
                          if($purchaseR && mysqli_num_rows($purchaseR) > 0){
                              while ($purchaseRow = mysqli_fetch_assoc($purchaseR)) {
                                  $tmp3['id'] = (isset($purchaseRow['id'])) ? $purchaseRow['id'] : '';
                                  $tmp3['type'] = 'PURCHASEBILL';
                                  $tmp3['date'] = (isset($purchaseRow['invoice_date'])) ? $purchaseRow['invoice_date'] : '';
                                  $tmp3['no'] = (isset($purchaseRow['invoice_no'])) ? $purchaseRow['invoice_no'] : '';
                                  $tmp3['narration'] = (isset($purchaseRow['invoice_no']) && $purchaseRow['invoice_no'] != '') ? 'Purchase Bill - '.$purchaseRow['invoice_no'] : 'Purchase Bill';
                                  $tmp3['debit'] = '';
                                  $tmp3['credit'] = (isset($purchaseRow['total_total']) && $purchaseRow['total_total'] != '') ? $purchaseRow['total_total'] : 0;
                                  $tmp3['url'] = 'purchase.php?id='.$tmp3['id'];
                                  $detail[] = $tmp3;
                              }
                          }
                      }

                      //Purchase Return
                      if(isset($ledgerSummaryRow['group_id']) && $ledgerSummaryRow['group_id'] == 14){//for vendor only
                          $purchaseReturnQ = "SELECT id,debit_note_date, debit_note_no, remarks, finalamount FROM purchase_return WHERE pharmacy_id = '".$pharmacy_id."' AND vendor = '".$id."'";
                          
                          if(isset($is_financial) && $is_financial == 1){
                              $purchaseReturnQ .= " AND financial_id = '".$financial_id."'";
                          }
                          if((isset($from) && $from != '') && (isset($to) && $to != '')){
                            $purchaseReturnQ .= " AND debit_note_date >= '".$from."' AND debit_note_date <= '".$to."'";
                          }
                          $purchaseReturnR = mysqli_query($conn, $purchaseReturnQ);
                          if($purchaseReturnR && mysqli_num_rows($purchaseReturnR) > 0){
                              while($purchaseReturnRow = mysqli_fetch_assoc($purchaseReturnR)){
                                  $tmp4['id'] = (isset($purchaseReturnRow['id'])) ? $purchaseReturnRow['id'] : '';
                                  $tmp4['type'] = 'PURCHASERETURNBILL';
                                  $tmp4['date'] = (isset($purchaseReturnRow['debit_note_date'])) ? $purchaseReturnRow['debit_note_date'] : '';
                                  $tmp4['no'] = (isset($purchaseReturnRow['debit_note_no'])) ? $purchaseReturnRow['debit_note_no'] : '';
                                  $debitnoteno = (isset($purchaseReturnRow['debit_note_no']) && $purchaseReturnRow['debit_note_no'] != '') ? ' - '.$purchaseReturnRow['debit_note_no'] : '';
                                  $remarks = (isset($purchaseReturnRow['remarks']) && trim($purchaseReturnRow['remarks']) != '') ? ' - '.$purchaseReturnRow['remarks'] : '';
                                  $tmp4['narration'] = 'Debit Note'.$debitnoteno.''.$remarks;
                                  $tmp4['debit'] = (isset($purchaseReturnRow['finalamount']) && $purchaseReturnRow['finalamount'] != '') ? $purchaseReturnRow['finalamount'] : 0;
                                  $tmp4['credit'] = '';
                                  $tmp4['url'] = 'purchase-return.php?id='.$purchaseReturnRow['id'];
                                  $detail[] = $tmp4;
                              }
                          }
                      }

                      //Cash Transaction 
                      $cashTransactionQ = "SELECT id, payment_type, voucher_no, voucher_date, amount, narration FROM cash_transaction WHERE pharmacy_id = '".$pharmacy_id."' AND perticular = '".$id."'";
                      
                      if(isset($is_financial) && $is_financial == 1){
                          $cashTransactionQ .= " AND financial_id = '".$financial_id."'";
                      }
                      if((isset($from) && $from != '') && (isset($to) && $to != '')){
                        $cashTransactionQ .= " AND voucher_date >= '".$from."' AND voucher_date <= '".$to."'";
                      }
                      $cashTransactionR = mysqli_query($conn, $cashTransactionQ);
                      if($cashTransactionR && mysqli_num_rows($cashTransactionR) > 0){
                          while ($cashTransactionRow = mysqli_fetch_assoc($cashTransactionR)) {
                              $tmp5['id'] = (isset($cashTransactionRow['id'])) ? $cashTransactionRow['id'] : '';
                              $tmp5['type'] = 'CASHTRANSACTION';
                              $tmp5['date'] = (isset($cashTransactionRow['voucher_date'])) ? $cashTransactionRow['voucher_date'] : '';
                              $tmp5['no'] = (isset($cashTransactionRow['voucher_no'])) ? $cashTransactionRow['voucher_no'] : '';
                              
                              $cashVoucherNo = (isset($cashTransactionRow['voucher_no']) && $cashTransactionRow['voucher_no'] != '') ? ' - '.$cashTransactionRow['voucher_no'] : '';
                              $cashNarration = (isset($cashTransactionRow['narration']) && $cashTransactionRow['narration'] != '') ? ' - '.$cashTransactionRow['narration'] : '';
                              $cashLable = (isset($cashTransactionRow['payment_type']) && $cashTransactionRow['payment_type'] == 'receipt') ? 'Cash Receipt' : 'Cash Payment';
                              
                              $tmp5['narration'] = $cashLable.''.$cashVoucherNo.''.$cashNarration;
                              if(isset($cashTransactionRow['payment_type']) && $cashTransactionRow['payment_type'] == 'receipt'){
                                  $tmp5['debit'] = '';
                                  $tmp5['credit'] = (isset($cashTransactionRow['amount']) && $cashTransactionRow['amount'] != '') ? $cashTransactionRow['amount'] : 0;
                                  $tmp5['url'] = 'cash-receipt.php?id='.$cashTransactionRow['id'];
                              }else{
                                  $tmp5['debit'] = (isset($cashTransactionRow['amount']) && $cashTransactionRow['amount'] != '') ? $cashTransactionRow['amount'] : 0;
                                  $tmp5['credit'] = '';
                                  $tmp5['url'] = 'cash-payment.php?id='.$cashTransactionRow['id'];
                              }
                              $detail[] = $tmp5;
                          }
                      }

                      //Bank Transaction
                      $bankTransactionQ = "SELECT bt.id, bt.payment_type, bt.voucher_no, bt.voucher_date, bt.amount, bt.payment_mode, bt.payment_mode_date, bt.payment_mode_no, bt.card_name, bt.other_reference, lg.name as bank_name FROM bank_transaction bt LEFT JOIN ledger_master lg ON bt.bank_id = lg.id WHERE bt.pharmacy_id = '".$pharmacy_id."' AND bt.perticular = '".$id."'";
                      
                      if(isset($is_financial) && $is_financial == 1){
                          $bankTransactionQ .= " AND bt.financial_id = '".$financial_id."'";
                      }
                      if((isset($from) && $from != '') && (isset($to) && $to != '')){
                        $bankTransactionQ .= " AND bt.voucher_date >= '".$from."' AND bt.voucher_date <= '".$to."'";
                      }
                      $bankTransactionR = mysqli_query($conn, $bankTransactionQ);
                      if($bankTransactionR && mysqli_num_rows($bankTransactionR) > 0){
                          while ($bankTransactionRow = mysqli_fetch_assoc($bankTransactionR)) {
                              $tmp6['id'] = (isset($bankTransactionRow['id'])) ? $bankTransactionRow['id'] : '';
                              $tmp6['type'] = 'BANKTRANSACTION';
                              $tmp6['date'] = (isset($bankTransactionRow['voucher_date'])) ? $bankTransactionRow['voucher_date'] : '';
                              $tmp6['no'] = (isset($bankTransactionRow['voucher_no'])) ? $bankTransactionRow['voucher_no'] : '';
                              
                              $cashVoucherNo = (isset($bankTransactionRow['voucher_no']) && $bankTransactionRow['voucher_no'] != '') ? ' - '.$bankTransactionRow['voucher_no'] : '';
                              $paymentDate = (isset($bankTransactionRow['payment_mode_date']) && $bankTransactionRow['payment_mode_date'] != '0000-00-00') ? date('d/m/Y',strtotime($bankTransactionRow['payment_mode_date'])) : '';
                              $paymentNo = (isset($bankTransactionRow['payment_mode_no']) && $bankTransactionRow['payment_mode_no'] != '') ? $bankTransactionRow['payment_mode_no'] : '';
                              $cardName = (isset($bankTransactionRow['card_name']) && $bankTransactionRow['card_name'] != '') ? $bankTransactionRow['card_name'] : '';
                              $otherRef = (isset($bankTransactionRow['other_reference']) && $bankTransactionRow['other_reference'] != '') ? $bankTransactionRow['other_reference'] : '';
                              $bankName = (isset($bankTransactionRow['bank_name']) && $bankTransactionRow['bank_name'] != '') ? $bankTransactionRow['bank_name'] : '';
                              $cashLable = (isset($bankTransactionRow['payment_type']) && $bankTransactionRow['payment_type'] == 'receipt') ? 'Bank Receipt' : 'Bank Payment';
                              $paymentMode = (isset($bankTransactionRow['payment_mode'])) ? $bankTransactionRow['payment_mode'] : '';
                              
                              if($paymentMode == 'cheque'){
                                  $narration =  $cashLable.''.$cashVoucherNo.' - '.$bankName.' - Cheque No: '.$paymentNo.' - Cheque Date: '.$paymentDate;
                              }elseif($paymentMode == 'dd'){
                                  $narration =  $cashLable.''.$cashVoucherNo.' - '.$bankName.' - DD No: '.$paymentNo.' - DD Date: '.$paymentDate;
                              }elseif($paymentMode == 'net_banking'){
                                  $narration =  $cashLable.''.$cashVoucherNo.' - '.$bankName.' - Net Banking UTR NO: '.$paymentNo;
                              }elseif($paymentMode == 'credit_debit_card'){
                                  $narration =  $cashLable.''.$cashVoucherNo.' - '.$bankName.' - Card No: '.$paymentNo.' - Card Name: '.$cardName;
                              }elseif($paymentMode == 'other'){
                                  $narration =  $cashLable.''.$cashVoucherNo.' - '.$bankName.' - Other Reference: '.$otherRef;
                              }else{
                                  $narration =  $cashLable.''.$cashVoucherNo.' - '.$bankName;
                              }
                              
                              $tmp6['narration'] = $narration;
                              if(isset($bankTransactionRow['payment_type']) && $bankTransactionRow['payment_type'] == 'receipt'){
                                  $tmp6['debit'] = '';
                                  $tmp6['credit'] = (isset($bankTransactionRow['amount']) && $bankTransactionRow['amount'] != '') ? $bankTransactionRow['amount'] : 0;
                                  $tmp6['url'] = 'bank-receipt.php?id='.$bankTransactionRow['id'];
                              }else{
                                  $tmp6['debit'] = (isset($bankTransactionRow['amount']) && $bankTransactionRow['amount'] != '') ? $bankTransactionRow['amount'] : 0;
                                  $tmp6['credit'] = '';
                                  $tmp6['url'] = 'bank-payment.php?id='.$bankTransactionRow['id'];
                              }
                              $detail[] = $tmp6;
                          }
                      }

                      //Journal Voucher
                      $jurnalQ = "SELECT jv.id, jv.voucher_date, jvd.debit, jvd.credit, jvd.remarks  FROM journal_vouchar_details jvd INNER JOIN journal_vouchar jv ON jvd.voucher_id = jv.id WHERE jv.pharmacy_id = '".$pharmacy_id."' AND jvd.particular = '".$id."'";
                      
                      if(isset($is_financial) && $is_financial == 1){
                          $jurnalQ .= " AND jv.financial_id = '".$financial_id."'";
                      }
                      if((isset($from) && $from != '') && (isset($to) && $to != '')){
                        $jurnalQ .= " AND jv.voucher_date >= '".$from."' AND jv.voucher_date <= '".$to."'";
                      }
                      $jurnalR = mysqli_query($conn, $jurnalQ);
                      if($jurnalR && mysqli_num_rows($jurnalR) > 0){
                          while ($jurnalRow = mysqli_fetch_assoc($jurnalR)) {
                              $tmp7['id'] = (isset($jurnalRow['id'])) ? $jurnalRow['id'] : '';
                              $tmp7['type'] = 'JOURNALVOUCHER';
                              $tmp7['date'] = (isset($jurnalRow['voucher_date'])) ? $jurnalRow['voucher_date'] : '';
                              $tmp7['no'] = '';
                              $remarks7 = (isset($jurnalRow['remarks']) && $jurnalRow['remarks'] != '') ? ' - '.$jurnalRow['remarks'] : '';
                              $tmp7['narration'] = 'Journal Voucher'.$remarks7;
                              if(isset($jurnalRow['debit']) && $jurnalRow['debit'] > 0){
                                  $tmp7['debit'] = (isset($jurnalRow['debit']) && $jurnalRow['debit'] != '') ? $jurnalRow['debit'] : 0;
                                  $tmp7['credit'] = '';
                              }else{
                                  $tmp7['debit'] = '';
                                  $tmp7['credit'] = (isset($jurnalRow['credit']) && $jurnalRow['credit'] != '') ? $jurnalRow['credit'] : 0;
                              }
                              $tmp7['url'] = 'journal-vouchar.php?id='.$tmp7['id'];
                              $detail[] = $tmp7;
                          }
                      }
    
                    }
                }
            /*----------------------------------Ledger Summary Report END----------------------------------*/
    
        }

        if(!empty($detail)){
          function date_compare($a, $b){

              $t1 = strtotime($a['date']);

              $t2 = strtotime($b['date']);

              return $t1 - $t2;
          }
          usort($detail, 'date_compare');
          $data['data'] = $detail;
      }else{
          $data['data'] = [];
      }
    
      return $data;
}
 /*----------------------LEDGER SUMMARY REPORT END - JIGAR BHALIYA -- 29-01-2019----------------------------------*/
 
 /*-----------------------------------Expiry Date Report 29-01-19 Start-Rajesh -----------------------------------------------*/

    function getexpirydatereport($expiry_date , $company_by){
        global $conn;
        global $pharmacy_id;
        $data = [];

        $company_code ='';
        if($company_by == '0'){
          $company_code = (isset($_REQUEST['company'])) ? $_REQUEST['company'] : '';
        }

        $query = "SELECT p.id, p.product_name, p.batch_no, p.ex_date, p.opening_stock , cm.name FROM product_master AS p join company_master as cm On p.company_code = cm.id WHERE p.pharmacy_id = '$pharmacy_id' AND p.status = 1 AND (p.ex_date >= '$expiry_date' OR p.ex_date IS NULL OR p.ex_date = '0000-00-00')";
        if(isset($company_code) && $company_code != ''){
          $query .= "AND p.company_code = '".$company_code."' ";
        }
        $query .= "ORDER BY p.product_name";
        $res = mysqli_query($conn, $query);
        while ($row=mysqli_fetch_array($res)) {
             $getstock = getAllProductWithCurrentStock('','',0, [$row['id']]);
            $row['stock'] = (isset($getstock[0]['currentstock']) && $getstock[0]['currentstock'] != '') ? $getstock[0]  ['currentstock'] :'0';
            $data[]=$row;
        }
        return $data;
    }

    /*------------------------------------Expiry Date 29-01-19 Report End-----------------------------------------------*/
    
    /*-----------------------------------Purchase Report 30-01-19 Start-Rajesh -----------------------------------------------*/

    function getpurchasreport($from , $to, $gst){
        global $conn;
        global $pharmacy_id;
        $data = [];
        $purchasereport = "SELECT p.overall_value as taxable_amount, (p.total_igst+p.total_cgst+p.total_sgst) as tax_amount,total_amount,l.name,p.invoice_date,p.`invoice_no`,l.gstno FROM ledger_master AS l INNER JOIN purchase AS p ON l.id = p.vendor WHERE p.invoice_date >= '$from' AND p.invoice_date <= '$to' AND l.pharmacy_id ='$pharmacy_id' AND group_id='14' AND l.status ='1'";
  
        if(isset($gst) && $gst == 'GST_Regular'){
          $purchasereport .= " AND l.customer_type = '$gst'";
        }


        if(isset($gst) && $gst == 'GST_unregistered'){
           $purchasereport .= " AND (l.customer_type = '$gst' OR l.customer_type IS NULL)";
        }
        $purchasereport .= " ORDER BY p.invoice_date";
        $purchasereportq=mysqli_query($conn,$purchasereport);
        while ($row=mysqli_fetch_array($purchasereportq)) {
            $data[]=$row;
        }
        return $data;
    }

    /*------------------------------------Purchase Report 30-01-19 End-----------------------------------------------*/
    
    /**
     * Change Date Format (JIGAR BHALIYA)
     * @param string $current_format
     * @param string $date
     * @param string $output_format
     * @return string
     */
    function change_date_format($current_format = 'd/m/Y', $date, $output_format = 'Y-m-d')
    {
        $date = DateTime::createFromFormat($current_format, $date);
        return $date->format($output_format);
    }
    
    /*-------------------------------------VENDOR PURCHASE REPORT - GAUTAM MAKWANA - 01-02-2019 - START------------------------------*/
    function vendorPurchaseReport($from = null, $to = null, $vendor = null, $view = null, $type = null, $company = null, $product = null, $mrp = null){
        global $conn;
        global $pharmacy_id;
        $data = [];

        if(isset($vendor) && $vendor != ''){
          if(isset($view) && $view == 'summary'){
            $select = "SUM(pd.qty) as qty, SUM(pd.qty*pd.f_rate) as amount";
          }else{
            $select = "pd.qty , (pd.qty*pd.f_rate) as amount";
          }

          $query = "SELECT p.id as purchase_id, pd.id as purchase_detail_id, p.voucher_no, p.vouchar_date, ".$select.", pm.product_name, pm.mfg_company, cm.name as company_name FROM purchase_details pd INNER JOIN purchase p ON pd.purchase_id = p.id INNER JOIN product_master pm ON pd.product_id = pm.id LEFT JOIN company_master cm ON pm.company_code = cm.id WHERE p.pharmacy_id = '".$pharmacy_id."' AND p.vendor = '".$vendor."'";
          if(isset($is_financial) && $is_financial == 1){
                $query .= " AND p.financial_id = '".$financial_id."'";
          }
          if((isset($from) && $from != '') && (isset($to) && $to != '')){
            $query .= " AND p.vouchar_date >= '".$from."' AND p.vouchar_date <= '".$to."'";
          }
          if((isset($type) && $type == 'company_wise') && (isset($company) && $company != '')){
            $query .= " AND pm.company_code = '".$company."'";
          }
          if((isset($type) && $type == 'single_product') && (isset($product) && $product != '')){
            $query .= " AND pm.product_name = '".$product."'";
            if(isset($mrp) && $mrp != ''){
              $query .= " AND pm.mrp = '".$mrp."'";
            }
          }
          if((isset($view) && $view == 'summary') && ((isset($type)) && ($type == 'all' || $type == 'single_product'))){
            $query .= " GROUP BY pm.product_name";
          }elseif((isset($view) && $view == 'summary') && (isset($type) && $type == 'company_wise')){
            $query .= " GROUP BY pm.company_code";
          }

          $query .=" ORDER BY p.vouchar_date";
          
          $res = mysqli_query($conn, $query);
          if($res && mysqli_num_rows($res) > 0){
            while ($row = mysqli_fetch_assoc($res)) {
              if((isset($view) && $view == 'summary') && (isset($type) && $type == 'company_wise')){
                $row['product_name'] = (isset($row['company_name']) && $row['company_name'] != '') ? $row['company_name'] : 'Unknown Company';
              }
              $data[] = $row;
            }
          }
        }
        return $data;
    }
    /*-------------------------------------VENDOR PURCHASE REPORT - GAUTAM MAKWANA - 01-02-2019 - END------------------------------*/
    
    
    /*------------------------ Get Difference Between Two dates - Jigar Bhaliya --------------------------------------------------*/
    function get_month_diff($start, $end, $format = "Y-m-d"){
    	
    	$start = DateTime::createFromFormat($format, $start);
        $end = DateTime::createFromFormat($format, $end);
    	$diff  = $start->diff($end);
    	return $diff->format('%y') * 12 + $diff->format('%m');
    }
    
    function get_days_diff($start, $end, $format = "Y-m-d"){
    	
    	$start = DateTime::createFromFormat($format, $start);
        $end = DateTime::createFromFormat($format, $end);
    	$diff  = $start->diff($end);
    	return $diff->format('%a');
    }

    /*-----------------------Get Rem String - Jigar Bhaliya - 02-04-2019-------------------------*/
    function get_rem_string($product_id, $closing_stock){
    global $conn;
    global $pharmacy_id;
    global $financial_id;
    $data = array();
    $rem_string = "";
    $today = date('Y-m-d');
    
    $getProductInfoQ = "SELECT id, ex_date, max_qty FROM `product_master` WHERE id = '".$product_id."'";
    $getProductInfoR = mysqli_query($conn, $getProductInfoQ);
    
    if($getProductInfoR && mysqli_num_rows($getProductInfoR) == 1){
        
        $productInfo = mysqli_fetch_assoc($getProductInfoR);
        $expiry_date = (isset($productInfo['ex_date']) && $productInfo['ex_date'] != '0000-00-00') ? $productInfo['ex_date'] : '';
        
        //Nearby Expiry
        if(!empty($expiry_date)){
        
            //check expiry date has passed or not
            $expiry_date_new = DateTime::createFromFormat("Y-m-d", $expiry_date);
            
            if($expiry_date_new < $today){
                $rem_string .= "#";
            } else {
                //check if expiring in 6 months
                $duration_month = get_month_diff($expiry_date, $today);
                
                if($duration_month <= 6){
                    $rem_string .= "#";
                } 
            }
        }
        
        //Non Moving Last sale 30 days
        $getSaleDateQ = "SELECT invoice_date FROM `tax_billing` tb LEFT JOIN `tax_billing_details` tbd ON tb.id = tbd.tax_bill_id WHERE tbd.product_id = '".$product_id."' ORDER BY tb.invoice_date DESC LIMIT 0,1";
        $getSaleDateR = mysqli_query($conn, $getSaleDateQ);
        if($getSaleDateR && mysqli_num_rows($getSaleDateR) == 1){
            $getSaleDateR = mysqli_fetch_assoc($getSaleDateR);
            $last_sale_date = $getSaleDateR['invoice_date'];
            $duration_days = get_days_diff($last_sale_date, $today);
            
            if($duration_days >= 30){
                $rem_string .= "&";
            }
        }
        
        //Over Stock More Than Max Quantity
        $maxQuantity = $productInfo['max_qty'];
        if($closing_stock > $maxQuantity){
            $rem_string .= "?";
        }
    }
   
    return $rem_string;
}
    /*-----------------------Get Rem String END--------------------------------------------------*/
    
    /*------------------------ Reorder Quantity Report - Jigar Bhaliya - 02-04-2019 START --------------*/
    function reorderQuantityReport($type, $company_name = null, $selectedcompany = null, $from = null, $to = null, $stock, $is_financial = 0){
    global $conn;
    global $pharmacy_id;
    global $financial_id;
    $data = array();
    $getProductQ = "";
    $getProductR = "";
    
    if(isset($type) && $type != ""){
        
        if($type == "1"){//Company Wise
        
            if(isset($company_name) && !empty($company_name)){
                
                $getProductQ = "SELECT id, product_name, opening_qty, ex_date, ratio FROM product_master WHERE mfg_company = '".$company_name."' AND pharmacy_id = '".$pharmacy_id."'";
                $getProductR = mysqli_query($conn, $getProductQ);
            }
                 
        } elseif($type == "2"){//all company
        
            $getProductQ = "SELECT id, product_name, opening_qty, ex_date FROM product_master WHERE pharmacy_id = '".$pharmacy_id."'";
            $getProductR = mysqli_query($conn, $getProductQ);
             
        } elseif($type == 3){//selected company
            
            $company_cond = "";
            if(count($selectedcompany) == 1){
                            
                if(!empty($selectedcompany[0])){
                    
                    $company_cond = " AND mfg_company = '".$selectedcompany[0]."'";    
                }    
            } else {
                
                $company_id_string = implode(',', $selectedcompany);
                $company_cond = " AND mfg_company IN ('".$company_id_string."')";
            }
        
            $getProductQ = "SELECT id, product_name, opening_qty, ex_date FROM product_master WHERE pharmacy_id = '".$pharmacy_id."' $company_cond  ";
            $getProductR = mysqli_query($conn, $getProductQ);
             
        }
        
        //Fetch Products
        if($getProductR && mysqli_num_rows($getProductR) > 0){
            while($getProductRow = mysqli_fetch_assoc($getProductR)){
                
                $opening_qty = (isset($getProductRow['opening_qty']) && !empty($getProductRow['opening_qty'])) ? $getProductRow['opening_qty'] : 0;
                $getProductRow['opening_qty'] = $opening_qty;
                $expiry_date = (isset($getProductRow['ex_date']) && $getProductRow['ex_date'] != '0000-00-00') ? change_date_format('Y-m-d', $getProductRow['ex_date'],'m/Y') : '-';
                $getProductRow['ex_date'] = $expiry_date;
                
                //purchase qty
                $total_purchase_qty = 0;
                $ratio = (isset($getProductRow['ratio']) && $getProductRow['ratio'] != '') ? $getProductRow['ratio'] : 0;
                
                $purchaseQ = "SELECT p.id, pd.qty, pd.free_qty, pd.rate FROM purchase_details pd INNER JOIN purchase p ON pd.purchase_id = p.id WHERE p.pharmacy_id = '".$pharmacy_id."' AND pd.product_id = '".$getProductRow['id']."'";
                if(isset($is_financial) && $is_financial == 1){
                    $purchaseQ .= " AND p.financial_id = '".$financial_id."'";
                }
                if((isset($from) && $from != '') && (isset($to) && $to != '')){
                  $purchaseQ .= " AND p.invoice_date >= '".$from."' AND p.invoice_date <= '".$to."'";
                }
                $purchaseQ .= " ORDER BY p.id DESC";
                $purchaseR = mysqli_query($conn, $purchaseQ);
                if($purchaseR && mysqli_num_rows($purchaseR) > 0){
                    while($purchaseRow = mysqli_fetch_assoc($purchaseR)){
                        $purchase_qty = 0;
                        if($ratio != '' && $ratio != 0){
                            $purchase_qty += (isset($purchaseRow['qty']) && $purchaseRow['qty'] != '') ? ($purchaseRow['qty']*$ratio) + $purchaseRow['free_qty']: 0;
                        }else{
                            $purchase_qty += (isset($purchaseRow['qty']) && $purchaseRow['qty'] != '') ? $purchaseRow['qty'] + $purchaseRow['free_qty']: 0;
                        }
                    }
                    $total_purchase_qty = $purchase_qty;
                }
                $getProductRow['purchase_qty'] = $total_purchase_qty;
                
                //sale qty
                $total_sale_qty = 0;
                $saleQ = "SELECT tb.id, tbd.qty, tbd.freeqty, tbd.rate FROM tax_billing_details tbd INNER JOIN tax_billing tb ON tbd.tax_bill_id = tb.id WHERE tb.pharmacy_id = '".$pharmacy_id."' AND tbd.product_id = '".$getProductRow['id']."'";
                if(isset($is_financial) && $is_financial == 1){
                    $saleQ .= " AND tb.financial_id = '".$financial_id."'";
                }
                if((isset($from) && $from != '') && (isset($to) && $to != '')){
                  $saleQ .= " AND tb.invoice_date >= '".$from."' AND tb.invoice_date <= '".$to."'";
                }
                $saleR = mysqli_query($conn, $saleQ);
                if($saleR && mysqli_num_rows($saleR) > 0){
                    $sale_qty = 0;
                    while($saleRow = mysqli_fetch_assoc($saleR)){
                        if($ratio != '' && $ratio != 0){
                            $sale_qty += (isset($saleRow['qty']) && $saleRow['qty'] != '') ? ($saleRow['qty']*$ratio) + $saleRow['freeqty']: 0;
                        }else{
                            $sale_qty += (isset($saleRow['qty']) && $saleRow['qty'] != '') ? ($saleRow['qty'] + $purchaseRow['freeqty']): 0;
                        }
                    }
                    $total_sale_qty = $sale_qty;
                }
                $getProductRow['sale_qty'] = $total_sale_qty;
                
                //closing qty
                $total_closing_qty = $opening_qty + $total_purchase_qty - $total_sale_qty;
                $getProductRow['closing_qty'] = $total_closing_qty;
                
                //rem
                $rem_string = get_rem_string($getProductRow['id'], $total_closing_qty);
                $getProductRow['rem_string'] = $rem_string;
                
                //avg sale
                $month_duration = get_month_diff($from, $to);
                
                if($month_duration >= 3){
                    $avg_month = 3;
                } else {
                    $avg_month = 1;
                }
                
                $average = $total_sale_qty / $avg_month;
                $getProductRow['average_sale'] = $average;
                
                //order
                $order_qty = $total_sale_qty * $stock;
                
                if($order_qty >  $total_closing_qty){
                    $order_qty_new = $order_qty - $total_closing_qty;
                } elseif($order_qty <=  $total_closing_qty){
                    $order_qty_new = 0;
                }
                $getProductRow['order_new'] = $order_qty_new;
                
                $data[] = $getProductRow;
                
            }
        }
     
    }
     return $data;
}
   /*------------------------ Reorder Quantity Report - Jigar Bhaliya - 02-04-2019 END--------------*/
   
   
   
   /*--------------------------------Default Setting Insert Data 04-02-2019 VIRAG RAKHOLIYA - START----------------------*/
    function default_setting(){
        global $conn;
        global $pharmacy_id;
        global $owner_id;
        global $admin_id;
        global $financial_id;
        $settingq = "SELECT * FROM `setting_group` WHERE pharmacy_id = '".$_SESSION['auth']['pharmacy_id']."' AND pharmacy_id != 0 AND pharmacy_id IS NOT NULL"; 
        $settingr = mysqli_query($conn, $settingq);
        if($settingr){
            if(mysqli_num_rows($settingr) <= 0){
                $setting_insert = "INSERT INTO setting_group SET owner_id = '".$owner_id."', admin_id = '".$admin_id."', pharmacy_id = '".$pharmacy_id."', financial_id = '".$financial_id."', transaction_setting = '0', sell_setting = '0', created = '".date('Y-m-d H:i:s')."', createdby = '".$_SESSION['auth']['id']."'";
                $setting_run = mysqli_query($conn, $setting_insert);
            }
        }
        
    }
    /*--------------------------------Default Setting Insert Data 04-02-2019 VIRAG RAKHOLIYA - END----------------------*/
    
    /*-------------------------------------ITEM REPORT - GAUTAM MAKWANA - 04-02-2019 - START----------------------------*/
    function itemReport($from = null, $to = null, $item = null, $type = null, $batch = null, $view = null, $is_financial = 0){
        global $conn;
        global $pharmacy_id;
        global $financial_id;
        $data = [];
        $method = '';
            
          $getMethosQ = "SELECT id,company_close_type FROM pharmacy_profile WHERE id = '".$pharmacy_id."'";
          $getMethosR = mysqli_query($conn, $getMethosQ);
          if($getMethosR && mysqli_num_rows($getMethosR) > 0){
              $getMethosRow = mysqli_fetch_assoc($getMethosR);
              $method = (isset($getMethosRow['company_close_type'])) ? $getMethosRow['company_close_type'] : '';
          }

          //GET ALL BATCHES FOR PRODUCT
          $productQ = "SELECT id, product_name, batch_no FROM product_master WHERE pharmacy_id = '".$pharmacy_id."' AND product_name = '".$item."' AND batch_no != '' AND batch_no != '-'";
          if(isset($batch) && $batch != ''){
            $productQ .= " AND batch_no = '".$batch."'";
          }
          $productQ .= " GROUP BY batch_no ORDER BY batch_no";
          $productR = mysqli_query($conn, $productQ);
          if($productR && mysqli_num_rows($productR) > 0){
            while ($productRow = mysqli_fetch_assoc($productR)) {
              $tmpData = $productRow;
              $tmpData['detail']['credit'] = [];
              $tmpData['detail']['debit'] = [];

              /*-----------------------------------------------SALE START------------------------------------------------*/
              $saleQ = "SELECT tb.id as tb_id, tb.invoice_date, tb.invoice_no, lg.name as party_name, tbd.id as tbd_id, tbd.qty, (tbd.totalamount-tbd.gst_tax) as amount FROM tax_billing_details tbd INNER JOIN tax_billing tb ON tbd.tax_bill_id = tb.id INNER JOIN product_master pm ON tbd.product_id = pm.id LEFT JOIN ledger_master lg ON tb.customer_id = lg.id WHERE tb.pharmacy_id = '".$pharmacy_id."' AND pm.product_name = '".$productRow['product_name']."' AND pm.batch_no = '".$productRow['batch_no']."'";
              if(isset($is_financial) && $is_financial == 1){
                  $saleQ .= " AND tb.financial_id = '".$financial_id."'";
              }
              if((isset($from) && $from != '') && (isset($to) && $to != '')){
                $saleQ .= " AND tb.invoice_date >= '".$from."' AND tb.invoice_date <= '".$to."'";
              }
              $saleR = mysqli_query($conn, $saleQ);
              if($saleR && mysqli_num_rows($saleR) > 0){
                while ($saleRow = mysqli_fetch_assoc($saleR)) {
                  $tmp['type'] = 'SALE';
                  $tmp['id'] = (isset($saleRow['tb_id'])) ? $saleRow['tb_id'] : '';
                  $tmp['sub_id'] = (isset($saleRow['tbd_id'])) ? $saleRow['tbd_id'] : '';
                  $tmp['date'] = (isset($saleRow['invoice_date']) && $saleRow['invoice_date'] != '' && $saleRow['invoice_date'] != '0000-00-00') ? $saleRow['invoice_date'] : '';
                  $invoiceNo = (isset($saleRow['invoice_no']) && trim($saleRow['invoice_no']) != '') ? ', Invoice No.'.$saleRow['invoice_no'] : '';
                  $invoiceDate = (isset($saleRow['invoice_date']) && $saleRow['invoice_date'] != '' && $saleRow['invoice_date'] != '0000-00-00') ? ', Invoice Dt.'.date('d/m/Y',strtotime($saleRow['invoice_date'])) : '';
                  $partyName = (isset($saleRow['party_name']) && trim($saleRow['party_name']) != '') ? $saleRow['party_name'] : 'Unknown Party';
                  $tmp['narration'] = $partyName.''.$invoiceDate.''.$invoiceNo;
                  $tmp['qty'] = (isset($saleRow['qty']) && $saleRow['qty'] != '') ? $saleRow['qty'] : 0;
                  $tmp['amount'] = (isset($saleRow['amount']) && $saleRow['amount'] != '') ? $saleRow['amount'] : 0;

                  $tmpData['detail']['credit'][] = $tmp;
                }
                unset($tmp, $invoiceNo, $invoiceDate, $partyName);
              }
              /*-----------------------------------------------SALE END------------------------------------------------*/

              /*-----------------------------------------------SALE RETURN START------------------------------------------------*/
              $saleReturnQ = "SELECT sr.id as sr_id, sr.credit_note_no, sr.credit_note_date, lg.name as party_name, srd.id as srd_id, srd.qty, (srd.amount-srd.gst_tax) as amount FROM sale_return_details srd INNER JOIN sale_return sr LEFT JOIN ledger_master lg ON sr.customer_id = lg.id INNER JOIN product_master pm ON srd.product_id = pm.id WHERE sr.pharmacy_id = '".$pharmacy_id."' AND pm.product_name = '".$productRow['product_name']."' AND pm.batch_no = '".$productRow['batch_no']."'";
              if(isset($is_financial) && $is_financial == 1){
                  $saleReturnQ .= " AND sr.financial_id = '".$financial_id."'";
              }
              if((isset($from) && $from != '') && (isset($to) && $to != '')){
                $saleReturnQ .= " AND sr.credit_note_date >= '".$from."' AND sr.credit_note_date <= '".$to."'";
              }
              $saleReturnR = mysqli_query($conn, $saleReturnQ);
              if($saleReturnR && mysqli_num_rows($saleReturnR) > 0){
                while ($saleReturnRow = mysqli_fetch_assoc($saleReturnR)){
                  $tmp['type'] = 'SALE_RETURN';
                  $tmp['id'] = (isset($saleReturnRow['sr_id'])) ? $saleReturnRow['sr_id'] : '';
                  $tmp['sub_id'] = (isset($saleReturnRow['srd_id'])) ? $saleReturnRow['srd_id'] : '';
                  $tmp['date'] = (isset($saleReturnRow['credit_note_date']) && $saleReturnRow['credit_note_date'] != '' && $saleReturnRow['credit_note_date'] != '0000-00-00') ? $saleReturnRow['credit_note_date'] : '';
                  $creditNoteNo = (isset($saleReturnRow['credit_note_no']) && trim($saleReturnRow['credit_note_no']) != '') ? ', Credit Note No.'.$saleReturnRow['credit_note_no'] : '';
                  $creditNoteDate = (isset($saleReturnRow['credit_note_date']) && $saleReturnRow['credit_note_date'] != '' && $saleReturnRow['credit_note_date'] != '0000-00-00') ? ', Credit Note Dt.'.date('d/m/Y',strtotime($saleReturnRow['credit_note_date'])) : '';
                  $partyName = (isset($saleReturnRow['party_name']) && trim($saleReturnRow['party_name']) != '') ? $saleReturnRow['party_name'] : 'Unknown Party';
                  $tmp['narration'] = $partyName.''.$creditNoteDate.''.$creditNoteNo;
                  $tmp['qty'] = (isset($saleReturnRow['qty']) && $saleReturnRow['qty'] != '') ? $saleReturnRow['qty'] : 0;
                  $tmp['amount'] = (isset($saleReturnRow['amount']) && $saleReturnRow['amount'] != '') ? $saleReturnRow['amount'] : 0;
                  $tmpData['detail']['debit'][] = $tmp;
                }
                unset($tmp, $creditNoteNo, $creditNoteDate, $partyName);
              }
              /*-----------------------------------------------SALE RETURN END------------------------------------------------*/

              /*-----------------------------------------------PURCHASE START----------------------------------------------*/
              $purchaseQ = "SELECT p.id as p_id, p.invoice_date, p.invoice_no, lg.name as party_name, pd.id as pd_id, pd.qty, pd.f_rate as rate, (pd.qty*pd.f_rate) as amount FROM purchase_details pd INNER JOIN purchase p ON pd.purchase_id = p.id INNER JOIN product_master pm ON pd.product_id = pm.id LEFT JOIN ledger_master lg ON p.vendor = lg.id WHERE p.pharmacy_id = '".$pharmacy_id."' AND pm.product_name = '".$productRow['product_name']."' AND pm.batch_no = '".$productRow['batch_no']."'";
              if(isset($is_financial) && $is_financial == 1){
                  $purchaseQ .= " AND p.financial_id = '".$financial_id."'";
              }
              if((isset($from) && $from != '') && (isset($to) && $to != '')){
                $purchaseQ .= " AND p.invoice_date >= '".$from."' AND p.invoice_date <= '".$to."'";
              }
              if(isset($method) && $method == 'LIFO'){
                $purchaseQ .= " ORDER BY pd.created DESC";
              }else{
                $purchaseQ .= " ORDER BY pd.created ASC";
              }
              //echo $purchaseQ;exit;
              $purchaseR = mysqli_query($conn, $purchaseQ);
              if($purchaseR && mysqli_num_rows($purchaseR) > 0){

                $totalRow = mysqli_num_rows($purchaseR);
                $totalRate = 0;
                $rate = 0;
                $i = 1;
                $totalPurchase = [];

                while ($purchaseRow = mysqli_fetch_assoc($purchaseR)) {
                  $rateAmount  = (isset($purchaseRow['rate']) && $purchaseRow['rate'] != '') ? $purchaseRow['rate'] : 0;
                  $totalRate += $rateAmount;
                  if($i == 1){
                    $rate = $rateAmount;
                  }
                  $i++;
                  $totalPurchase[] = $purchaseRow;
                }

                if(!empty($totalPurchase)){
                  $finalRate = (isset($method) && $method == 'AVERAGE') ? ($totalRate/$totalRow) : $rate;
                  foreach ($totalPurchase as $key => $value) {
                    $tmp['type'] = 'PURCHASE';
                    $tmp['id'] = (isset($value['p_id'])) ? $value['p_id'] : '';
                    $tmp['sub_id'] = (isset($value['pd_id'])) ? $value['pd_id'] : '';
                    $tmp['date'] = (isset($value['invoice_date']) && $value['invoice_date'] != '' && $value['invoice_date'] != '0000-00-00') ? $value['invoice_date'] : '';
                    $invoiceNo = (isset($value['invoice_no']) && trim($value['invoice_no']) != '') ? ', Invoice No.'.$value['invoice_no'] : '';
                    $invoiceDate = (isset($value['invoice_date']) && $value['invoice_date'] != '' && $value['invoice_date'] != '0000-00-00') ? ', Invoice Dt.'.date('d/m/Y',strtotime($value['invoice_date'])) : '';
                    $partyName = (isset($value['party_name']) && trim($value['party_name']) != '') ? $value['party_name'] : 'Unknown Party';
                    $tmp['narration'] = $partyName.''.$invoiceDate.''.$invoiceNo;
                    $tmp['qty'] = (isset($value['qty']) && $value['qty'] != '') ? $value['qty'] : 0;
                    $tmp['amount'] = ($tmp['qty']*$finalRate);
                    $tmpData['detail']['debit'][] = $tmp;
                  }
                  unset($tmp, $invoiceNo, $invoiceDate, $partyName, $finalRate, $rate, $rateAmount, $totalRow, $totalRate, $totalPurchase);
                }

              }
              /*-----------------------------------------------PURCHASE END------------------------------------------------*/

              /*-----------------------------------------------PURCHASE RETURN START----------------------------------------------*/
              $purchaseReturnQ = "SELECT pr.id as pr_id, pr.debit_note_date, pr.debit_note_no, lg.name as party_name, prd.id as prd_id, prd.qty, prd.final_rate as rate, (prd.qty*prd.final_rate) as amount FROM purchase_return_detail prd INNER JOIN purchase_return pr ON prd.pr_id = pr.id INNER JOIN product_master pm ON prd.product_id = pm.id LEFT JOIN ledger_master lg ON pr.vendor_id = lg.id WHERE pr.pharmacy_id = '".$pharmacy_id."' AND pm.product_name = '".$productRow['product_name']."' AND pm.batch_no = '".$productRow['batch_no']."'";
              if(isset($is_financial) && $is_financial == 1){
                  $purchaseReturnQ .= " AND pr.financial_id = '".$financial_id."'";
              }
              if((isset($from) && $from != '') && (isset($to) && $to != '')){
                $purchaseReturnQ .= " AND pr.debit_note_date >= '".$from."' AND pr.debit_note_date <= '".$to."'";
              }
              if(isset($method) && $method == 'LIFO'){
                $purchaseReturnQ .= " ORDER BY prd.created DESC";
              }else{
                $purchaseReturnQ .= " ORDER BY prd.created ASC";
              }
              $purchaseReturnR = mysqli_query($conn, $purchaseReturnQ);
              if($purchaseReturnR && mysqli_num_rows($purchaseReturnR) > 0){

                $totalRow = mysqli_num_rows($purchaseReturnR);
                $totalRate = 0;
                $rate = 0;
                $i = 1;
                $totalPurchaseReturn = [];

                while ($purchaseReturnRow = mysqli_fetch_assoc($purchaseReturnR)) {
                  $rateAmount  = (isset($purchaseReturnRow['rate']) && $purchaseReturnRow['rate'] != '') ? $purchaseReturnRow['rate'] : 0;
                  $totalRate += $rateAmount;
                  if($i == 1){
                    $rate = $rateAmount;
                  }
                  $i++;
                  $totalPurchaseReturn[] = $purchaseReturnRow;
                }

                if(!empty($totalPurchaseReturn)){
                  $finalRate = (isset($method) && $method == 'AVERAGE') ? ($totalRate/$totalRow) : $rate;
                  foreach ($totalPurchaseReturn as $key => $value) {
                    $tmp['type'] = 'PURCHASE_RETURN';
                    $tmp['id'] = (isset($value['pr_id'])) ? $value['pr_id'] : '';
                    $tmp['sub_id'] = (isset($value['prd_id'])) ? $value['prd_id'] : '';
                    $tmp['date'] = (isset($value['debit_note_date']) && $value['debit_note_date'] != '' && $value['debit_note_date'] != '0000-00-00') ? $value['debit_note_date'] : '';
                    $debitNoteNo = (isset($value['debit_note_no']) && trim($value['debit_note_no']) != '') ? ', Debit Note No.'.$value['debit_note_no'] : '';
                    $debitNoteDate = (isset($value['debit_note_date']) && $value['debit_note_date'] != '' && $value['debit_note_date'] != '0000-00-00') ? ', Debit Note Dt.'.date('d/m/Y',strtotime($value['debit_note_date'])) : '';
                    $partyName = (isset($value['party_name']) && trim($value['party_name']) != '') ? $value['party_name'] : 'Unknown Party';
                    $tmp['narration'] = $partyName.''.$debitNoteDate.''.$debitNoteNo;
                    $tmp['qty'] = (isset($value['qty']) && $value['qty'] != '') ? $value['qty'] : 0;
                    $tmp['amount'] = ($tmp['qty']*$finalRate);
                    $tmpData['detail']['credit'][] = $tmp;
                  }
                  unset($tmp, $debitNoteNo, $debitNoteDate, $partyName, $rateAmount, $totalRow, $totalRate, $i, $rate, $totalPurchaseReturn);
                }

              }
              /*-----------------------------------------------PURCHASE RETURN END------------------------------------------------*/
              if((isset($tmpData['detail']['credit']) && !empty($tmpData['detail']['credit'])) || (isset($tmpData['detail']['debit']) && !empty($tmpData['detail']['debit']))){
                if($view == 'summary'){
                    
                    $arr['id'] = (isset($tmpData['id'])) ? $tmpData['id'] : '';
                    $arr['product_name'] = (isset($tmpData['product_name'])) ? $tmpData['product_name'] : '';
                    $arr['batch_no'] = (isset($tmpData['batch_no'])) ? $tmpData['batch_no'] : '';

                    $arr['credit']['amount'] = 0;
                    $arr['credit']['qty'] = 0;
                    $arr['credit']['cash_on_hand_amount'] = '';
                    $arr['credit']['cash_on_hand_qty'] = '';

                    $arr['debit']['amount'] = 0;
                    $arr['debit']['qty'] = 0;
                    $arr['debit']['cash_on_hand_amount'] = '';
                    $arr['debit']['cash_on_hand_qty'] = '';

                    if(isset($tmpData['detail']['credit']) && !empty($tmpData['detail']['credit'])){
                      foreach ($tmpData['detail']['credit'] as $key1 => $value1) {
                        $arr['credit']['amount'] += (isset($value1['amount']) && $value1['amount'] != '') ? $value1['amount'] : 0;
                        $arr['credit']['qty'] += (isset($value1['qty']) && $value1['qty'] != '') ? $value1['qty'] : 0;
                      }
                    }

                    if(isset($tmpData['detail']['debit']) && !empty($tmpData['detail']['debit'])){
                      foreach ($tmpData['detail']['debit'] as $key2 => $value2) {
                        $arr['debit']['amount'] += (isset($value2['amount']) && $value2['amount'] != '') ? $value2['amount'] : 0;
                        $arr['debit']['qty'] += (isset($value2['qty']) && $value2['qty'] != '') ? $value2['qty'] : 0;
                      }
                    }

                    $cash_on_hand_amount = ($arr['credit']['amount']-$arr['debit']['amount']);
                    $cash_on_hand_qty =  ($arr['credit']['qty']-$arr['debit']['qty']);

                    if($cash_on_hand_amount < 0){
                      $arr['credit']['cash_on_hand_amount'] = $cash_on_hand_amount;
                    }else{
                      $arr['debit']['cash_on_hand_amount'] = $cash_on_hand_amount;
                    }

                    if($cash_on_hand_qty < 0){
                      $arr['credit']['cash_on_hand_qty'] = $cash_on_hand_qty;
                    }else{
                      $arr['debit']['cash_on_hand_qty'] = $cash_on_hand_qty;
                    }

                  $data[] = $arr;
                }else{
                  $data[] = $tmpData;
                }
              }
            }
          }
        return $data;
      }
    /*-------------------------------------ITEM REPORT - GAUTAM MAKWANA - 04-02-2019 - END------------------------------*/
    
    /*------------------------------------------Free goods Report -Rajesh -05-02-2019-Start---------------------------*/
    
    function freegoods($from = null , $to = null, $bill_type = null, $ledger = null, $company = null, $type = null, $sub_type = null){
        
        global $pharmacy_id;
        global $financial_id;
        global $conn;
        
        $data = [];
    
        if($bill_type == 'detail'){
            $dfreePurchaseQ = "SELECT p.vouchar_date,p.voucher_no,pm.product_name,cm.name,(pd.free_qty * pd.f_rate) as amount ,pd.free_qty FROM purchase p INNER JOIN purchase_details pd ON p.id=pd.purchase_id LEFT JOIN product_master pm ON pd.product_id = pm.id INNER JOIN company_master cm ON cm.id = pm.company_code WHERE p.pharmacy_id = '".$pharmacy_id."' AND pd.free_qty > 0 AND p.financial_id ='".$financial_id."' ";
            if(isset($company) && !empty($company) ){
                $dfreePurchaseQ .= " AND pm.company_code = '".$company."'";
            }
            if(isset($ledger) && !empty($ledger) ){
                $dfreePurchaseQ .= " AND p.vendor = '".$ledger."'";
            }  
            if((isset($from) && $from != '') && (isset($to) && $to != '')){
                $dfreePurchaseQ .= " AND p.vouchar_date >= '".$from."' AND p.vouchar_date <= '".$to."' ";
            }
            $dfreePurchaseR = mysqli_query($conn, $dfreePurchaseQ);
            
            if($dfreePurchaseR && mysqli_num_rows($dfreePurchaseR) > 0){

                while($dpurchaseRow = mysqli_fetch_assoc($dfreePurchaseR)){
                    $dpurchaseRow['type'] = 'purchase';
                    $dpurchaseRow['date'] = (isset($dpurchaseRow['vouchar_date']) && $dpurchaseRow['vouchar_date'] != '') ? $dpurchaseRow['vouchar_date'] : '';
                    $dpurchaseRow['no'] = (isset($dpurchaseRow['voucher_no']) && $dpurchaseRow['voucher_no'] != '') ? $dpurchaseRow['voucher_no'] : '';
                    $dpurchaseRow['product_name'] = (isset($dpurchaseRow['product_name']) && $dpurchaseRow['product_name'] != '') ? $dpurchaseRow['product_name'] : '';
                    $dpurchaseRow['amount'] = (isset($dpurchaseRow['amount']) && $dpurchaseRow['amount'] != '') ? $dpurchaseRow['amount'] : '';
                    $dpurchaseRow['name'] = (isset($dpurchaseRow['name']) && $dpurchaseRow['name'] != '') ? $dpurchaseRow['name'] : '';
                    $dpurchaseRow['free_qty'] = (isset($dpurchaseRow['free_qty']) && $dpurchaseRow['free_qty'] != '') ? $dpurchaseRow['free_qty'] : '';
                    $data[] = $dpurchaseRow;
                }
                
            }
        }elseif($bill_type == 'summary'){
            $freePurchaseQ = "SELECT pm.product_name,cm.name,(SUM(pd.free_qty) * SUM(pd.f_rate)) as amount ,SUM(pd.free_qty) as free_qty FROM purchase p INNER JOIN purchase_details pd ON p.id=pd.purchase_id INNER JOIN product_master pm ON pd.product_id = pm.id INNER JOIN company_master cm ON cm.id = pm.company_code WHERE p.pharmacy_id = '".$pharmacy_id."' AND pd.free_qty > 0 AND p.financial_id ='".$financial_id."'";
            if(isset($is_financial) && $is_financial == 1){
                $freePurchaseQ .= " AND p.financial_id = '".$financial_id."'";
            }
            if(isset($company) && !empty($company) ){
                $freePurchaseQ .= " AND pm.company_code = '".$company."'";
            }
            if(isset($ledger) && !empty($ledger) ){
                $freePurchaseQ .= " AND p.vendor = '".$ledger."'";
            }  
            if((isset($from) && $from != '') && (isset($to) && $to != '')){
                $freePurchaseQ .= " AND p.vouchar_date >= '".$from."' AND p.vouchar_date <= '".$to."' ";
            }
            if((isset($bill_type) && $bill_type == 'summary') && ((isset($type)) && ($type == 'all')) && ((isset($sub_type)) && ($sub_type == 'all1'))){
                $freePurchaseQ .= " GROUP BY pm.product_name";
              }elseif((isset($bill_type) && $bill_type == 'summary') && (isset($type) && $type == 'company_wise')){
                $freePurchaseQ .= " GROUP BY pm.company_code";
              }
            $freePurchaseR = mysqli_query($conn, $freePurchaseQ);
            if($freePurchaseR && mysqli_num_rows($freePurchaseR) > 0){
                while($purchaseRow = mysqli_fetch_assoc($freePurchaseR)){
                    if((isset($bill_type) && $bill_type == 'summary') && (isset($type) && $type == 'company_wise')){
                        $purchaseRow['product_name'] = (isset($purchaseRow['name']) && $purchaseRow['name'] != '') ? $purchaseRow['name'] : '';
                    }else if((isset($bill_type) && $bill_type == 'summary') && ((isset($type)) && ($type == 'all')) && ((isset($sub_type)) && ($sub_type == 'all1'))){
                        $purchaseRow['product_name'] = (isset($purchaseRow['product_name']) && $purchaseRow['product_name'] != '') ? $purchaseRow['product_name'] : '';
                    }else if ((isset($bill_type) && $bill_type == 'summary') && ((isset($type)) && ($type == 'all')) && ((isset($sub_type)) && ($sub_type == 'party_wise'))){
                        $purchaseRow['product_name'] = (isset($purchaseRow['product_name']) && $purchaseRow['product_name'] != '') ? $purchaseRow['product_name'] : '';
                    }
                    $purchaseRow['free_qty'] = (isset($purchaseRow['free_qty']) && $purchaseRow['free_qty'] != '') ? $purchaseRow['free_qty'] : '';
                    $purchaseRow['amount'] = (isset($purchaseRow['amount']) && $purchaseRow['amount'] != '') ? $purchaseRow['amount'] : '';
                    $data[] = $purchaseRow;
                }
                
            }
        }
         return $data;
    }
    
    /*------------------------------------------Free goods Report -Rajesh -05-02-2019-End---------------------------*/
?>