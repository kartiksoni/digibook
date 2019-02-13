<?php $title="Tax Billing"; 
 include('include/usertypecheck.php');
 include('include/permission.php');
 // include('include/config-ihis.php');
  $owner_id = (isset($_SESSION['auth']['owner_id'])) ? $_SESSION['auth']['owner_id'] : '';
  $admin_id = (isset($_SESSION['auth']['admin_id'])) ? $_SESSION['auth']['admin_id'] : '';
  $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
  $financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';
?>
<!-------------------------------------------- CODE FOR ADD AND UPDATE TAX BILLING START ----------------------------------------------->
<?php

  if(isset($_POST['save']) || isset($_POST['saveAndNext']) || isset($_POST['saveAndPrint'])){
    $_SESSION['msg']['fail'] = "Tax Bill Save Fail!";
    header('Location: sales-tax-billing-test.php');exit;
      
    /// kartik Changes ///
    $customer_id = $_POST['customer_id'];
    $customer_u_id = $_POST['customer_u_id'];
    $legerQ = "SELECT customer_id FROM `ledger_master` Where id = '".$customer_id."'";
    $legerR = mysqli_query($conn, $legerQ);
    $row_leger = mysqli_fetch_assoc($legerR);
    if($row_leger['customer_id'] == ''){
      $UpdateQ = "UPDATE `ledger_master` SET `customer_id`='".$customer_u_id  ."' WHERE id='".$customer_id."'";
      $UpdateR = mysqli_query($conn, $UpdateQ);
    }
    /// kartik changes ///

    $tax_billing = [];
    if(isset($_GET['doctor'])){
        $tax_billing['doctor'] = AddIhisDoctor($_POST['doctor'], $_POST['doctor_mobile']);
    }else{
        $tax_billing['doctor'] = (isset($_POST['doctor'])) ? $_POST['doctor'] : NULL;//for general
    }
    
    $tax_billing['city_id'] = (isset($_POST['city_id'])) ? $_POST['city_id'] : NULL;
    $tax_billing['statecode'] = (isset($_POST['statecode'])) ? $_POST['statecode'] : NULL;
    
    $tax_billing['customer_id'] = findCustomer($_POST);
    if($tax_billing['customer_id'] == ''){
        $_SESSION['msg']['fail'] = "Tax Bill Added Fail! Customer Not Found!";
        header('Location: sales-tax-billing.php');exit;
    }
    
    $tax_billing['invoice_date'] = (isset($_POST['invoice_date']) && $_POST['invoice_date'] != '') ? date('Y-m-d',strtotime(str_replace('/','-',$_POST['invoice_date']))) : NULL;
    $tax_billing['c_addr_1'] = (isset($_POST['c_addr_1'])) ? $_POST['c_addr_1'] : '';
    $tax_billing['c_addr_2'] = (isset($_POST['c_addr_2'])) ? $_POST['c_addr_2'] : '';
    $tax_billing['c_addr_3'] = (isset($_POST['c_addr_3'])) ? $_POST['c_addr_3'] : '';
    
    // $tax_billing['invoice_no'] = (isset($_POST['invoice_no'])) ? $_POST['invoice_no'] : '';
    if(isset($_GET['id']) && $_GET['id'] != '' ){
        $tax_billing['invoice_no'] = (isset($_POST['invoice_no'])) ? $_POST['invoice_no'] : '';
    }else{
        $tax_billing['invoice_no'] = getInvoiceNo((isset($_POST['bill_type'])) ? $_POST['bill_type'] : '');
    }
    $tax_billing['bill_type'] = (isset($_POST['bill_type'])) ? $_POST['bill_type'] : '';
    $tax_billing['alltotalamount'] = (isset($_POST['alltotalamount']) && $_POST['alltotalamount'] != '') ? $_POST['alltotalamount'] : 0;
    $tax_billing['total_return_amount'] = (isset($_POST['total_return_amount']) && $_POST['total_return_amount'] != '') ? $_POST['total_return_amount'] : 0;
    $tax_billing['couriercharge'] = (isset($_POST['couriercharge']) && $_POST['couriercharge'] != '') ? $_POST['couriercharge'] : 0;
    $tax_billing['couriercharge_val'] = (isset($_POST['couriercharge_val']) && $_POST['couriercharge_val'] != '') ? $_POST['couriercharge_val'] : 0;
    $tax_billing['totaltaxgst'] = (isset($_POST['totaltaxgst']) && $_POST['totaltaxgst'] != '') ? $_POST['totaltaxgst'] : 0;
    $tax_billing['totaligst'] = (isset($_POST['totaligst']) && $_POST['totaligst'] != '') ? $_POST['totaligst'] : 0;
    $tax_billing['totalcgst'] = (isset($_POST['totalcgst']) && $_POST['totalcgst'] != '') ? $_POST['totalcgst'] : 0;
    $tax_billing['totalsgst'] = (isset($_POST['totalsgst']) && $_POST['totalsgst'] != '') ? $_POST['totalsgst'] : 0;
    $tax_billing['discount_type'] = (isset($_POST['discount_type'])) ? $_POST['discount_type'] : '';
    $tax_billing['discount_per'] = (isset($_POST['discount_per']) && $_POST['discount_per'] != '') ? $_POST['discount_per'] : 0;
    $tax_billing['discount_rs'] = (isset($_POST['discount_rs']) && $_POST['discount_rs'] != '') ? $_POST['discount_rs'] : 0;
    $tax_billing['overalldiscount'] = (isset($_POST['overalldiscount']) && $_POST['overalldiscount'] != '') ? $_POST['overalldiscount'] : 0;
    $tax_billing['cr_db_type'] = (isset($_POST['cr_db_type']) && $_POST['cr_db_type'] != '') ? $_POST['cr_db_type'] : '';
    $tax_billing['cr_db_val'] = (isset($_POST['cr_db_val']) && $_POST['cr_db_val'] != '') ? $_POST['cr_db_val'] : 0;
    $tax_billing['purchase_amount'] = (isset($_POST['purchase_amount']) && $_POST['purchase_amount'] != '') ? $_POST['purchase_amount'] : 0;
    $tax_billing['roundoff_amount'] = (isset($_POST['roundoff_amount']) && $_POST['roundoff_amount'] != '') ? $_POST['roundoff_amount'] : 0;
    $tax_billing['final_amount'] = (isset($_POST['final_amount']) && $_POST['final_amount'] != '') ? $_POST['final_amount'] : 0;
    $tax_billing['remider'] = (isset($_POST['remider']) && $_POST['remider'] != '') ? $_POST['remider'] : 0;
    $tax_billing['time_name'] = (isset($_POST['time_name']) && $_POST['time_name'] != '') ? $_POST['time_name'] : NULL;
    $tax_billing['remiderdate'] = (isset($_POST['remiderdate']) && $_POST['remiderdate'] != '') ? date('Y-m-d',strtotime(str_replace('/','-',$_POST['remiderdate']))) : NULL;
    
    // Changes By kartik 10/10/2018  //
    if($tax_billing['remider'] == "1"){
      if($tax_billing['time_name'] == "Once"){
        $tax_billing['remider_date_update'] = $tax_billing['remiderdate'];
      }
      if($tax_billing['time_name'] == "Weekly"){
        $tax_billing['remider_date_update'] = date('Y-m-d', strtotime('+1 week', strtotime($tax_billing['remiderdate'])));
      }
      if($tax_billing['time_name'] == "15 Days"){
        $tax_billing['remider_date_update'] = date('Y-m-d', strtotime('+15 days', strtotime($tax_billing['remiderdate'])));
      }
      if($tax_billing['time_name'] == "Every 1 Month"){
        $tax_billing['remider_date_update'] = date('Y-m-d', strtotime('+1 month', strtotime($tax_billing['remiderdate'])));
      }
      if($tax_billing['time_name'] == "Every 2 Month"){
        $tax_billing['remider_date_update'] = date('Y-m-d', strtotime('+2 month', strtotime($tax_billing['remiderdate'])));
      }
      if($tax_billing['time_name'] == "Every 3 Month"){
        $tax_billing['remider_date_update'] = date('Y-m-d', strtotime('+3 month', strtotime($tax_billing['remiderdate'])));
      }
      if($tax_billing['time_name'] == "Yearly"){
        $tax_billing['remider_date_update'] = date('Y-m-d', strtotime('+1 year', strtotime($tax_billing['remiderdate'])));
      }
    }
    // Changes By kartik 10/10/2018  //
    
    $tax_billing['is_ihis'] = ((isset($_POST['patient_id']) && $_POST['patient_id'] != '') && (isset($_POST['customer_id']) && $_POST['customer_id'] == '')) ? 1 : 0;
    $tax_billing['prec_group'] = (isset($_POST['ihis_group'])) ? $_POST['ihis_group'] : '';
    $tax_billing['type'] = (isset($_POST['ihis_type'])) ? $_POST['ihis_type'] : '';
    
    $tax_billing['ihis_firm_id'] = (isset($_GET['ihis_firm_id'])) ? $_GET['ihis_firm_id'] : '';
    $tax_billing['ihis_user_id'] = (isset($_GET['ihis_user_id'])) ? $_GET['ihis_user_id'] : '';
    $tax_billing['ihis_ipd_id'] = (isset($_GET['ihis_ipd_id'])) ? $_GET['ihis_ipd_id'] : '';
    $tax_billing['ihis_treatment_by'] = (isset($_GET['ihis_treatment_by'])) ? $_GET['ihis_treatment_by'] : '';
    $tax_billing['ihis_patient_id'] = (isset($_GET['ihis_patient_id'])) ? $_GET['ihis_patient_id'] : '';
    $tax_billing['ihis_followup_id'] = (isset($_GET['ihis_followup_id'])) ? $_GET['ihis_followup_id'] : '';
    
    $tax_billing['register_type'] = (isset($_GET['register_type'])) ? $_GET['register_type'] : '';
    $tax_billing['infertility_register_type'] = (isset($_GET['infertility_register_type'])) ? $_GET['infertility_register_type'] : '';

    if(isset($_GET['id']) && $_GET['id']){
      $query = "UPDATE tax_billing SET ";
    }else{
      $query = "INSERT INTO tax_billing SET financial_id = '".$financial_id."', owner_id = '".$owner_id."', admin_id = '".$admin_id."', pharmacy_id = '".$pharmacy_id."',";
    }

    foreach ($tax_billing as $key => $value) {
       $query .= " ".$key." = '".$value."', ";
    }

    if(isset($_GET['id']) && $_GET['id']){
        $query .= "modified = '".date('Y-m-d H:i:s')."', modifiedby = '".$_SESSION['auth']['id']."' ";
        $query .= "WHERE id = '".$_GET['id']."'";
    }else{
      $query .= "cancel = 1, created = '".date('Y-m-d H:i:s')."', createdby = '".$_SESSION['auth']['id']."'";
    }
    $taxbillingRes = mysqli_query($conn, $query);
    if($taxbillingRes){
      $taxbillid = (isset($_GET['id']) && $_GET['id'] != '') ? $_GET['id'] : mysqli_insert_id($conn);
      if($taxbillid != ''){
          if((isset($_GET['patient']) && $_GET['patient'] != '') && (isset($_GET['group']) && $_GET['group'] != '') && (isset($_GET['type']) && $_GET['type'] != '')){
              updateIhisPrecriptionBillFlag($_GET['patient'], $_GET['group'], $_GET['type']);
          }
          
        $deleteOldItemQ = "DELETE FROM tax_billing_details WHERE tax_bill_id = '".$taxbillid."'";
        mysqli_query($conn, $deleteOldItemQ);

        /*-----------------------------------SAVE TAX BILLING ITEMS START----------------------------------------------*/
        $count = (isset($_POST['product_id']) && !empty($_POST['product_id'])) ? count($_POST['product_id']) : 0;
        if($count != 0){
          for ($i=0; $i < $count; $i++) { 
            $tax_billing_details['tax_bill_id'] = $taxbillid;
            $tax_billing_details['product_id'] = (isset($_POST['product_id'][$i])) ? $_POST['product_id'][$i] : '';
            $tax_billing_details['mrp'] = (isset($_POST['mrp'][$i]) && $_POST['mrp'][$i] != '') ? $_POST['mrp'][$i] : 0;
            $tax_billing_details['mfg_co'] = (isset($_POST['mfg_co'][$i])) ? $_POST['mfg_co'][$i] : '';
            $tax_billing_details['batch'] = (isset($_POST['batch'][$i])) ? $_POST['batch'][$i] : '';
            $tax_billing_details['expiry'] = (isset($_POST['expiry'][$i]) && $_POST['expiry'][$i] != '' && $_POST['expiry'][$i] != '-') ? date('Y-m-d',strtotime(str_replace('/','-',$_POST['expiry'][$i]))) : NULL;
            $tax_billing_details['qty'] = (isset($_POST['qty'][$i]) && $_POST['qty'][$i] != '') ? $_POST['qty'][$i] : 0;
            $tax_billing_details['qty_ratio'] = (isset($_POST['qty_ratio'][$i]) && $_POST['qty_ratio'][$i] != '') ? $_POST['qty_ratio'][$i] : 0;
            $tax_billing_details['freeqty'] = (isset($_POST['freeqty'][$i]) && $_POST['freeqty'][$i] != '') ? $_POST['freeqty'][$i] : 0;
            $tax_billing_details['rate'] = (isset($_POST['rate'][$i]) && $_POST['rate'][$i] != '') ? $_POST['rate'][$i] : 0;
            $tax_billing_details['discount'] = (isset($_POST['discount'][$i]) && $_POST['discount'][$i] != '') ? $_POST['discount'][$i] : 0;
            $tax_billing_details['amount'] = (isset($_POST['amount'][$i]) && $_POST['amount'][$i] != '') ? $_POST['amount'][$i] : 0;
            $tax_billing_details['ptr'] = (isset($_POST['ptr'][$i]) && $_POST['ptr'][$i] != '') ? $_POST['ptr'][$i] : 0;
            $tax_billing_details['gst'] = (isset($_POST['gst'][$i]) && $_POST['gst'][$i] != '') ? $_POST['gst'][$i] : 0;
            $tax_billing_details['igst'] = (isset($_POST['igst'][$i]) && $_POST['igst'][$i] != '') ? $_POST['igst'][$i] : 0;
            $tax_billing_details['cgst'] = (isset($_POST['cgst'][$i]) && $_POST['cgst'][$i] != '') ? $_POST['cgst'][$i] : 0;
            $tax_billing_details['sgst'] = (isset($_POST['sgst'][$i]) && $_POST['sgst'][$i] != '') ? $_POST['sgst'][$i] : 0;
            $tax_billing_details['totalamount'] = (isset($_POST['totalamount'][$i]) && $_POST['totalamount'][$i] != '') ? $_POST['totalamount'][$i] : 0;
            $created = (isset($_POST['created'][$i]) && $_POST['created'][$i] != '' && $_POST['created'][$i] != '0000-00-00 00:00:00') ? date('Y-m-d H:i:s', strtotime($_POST['created'][$i])) : date('Y-m-d H:i:s');
            
            $item = "INSERT INTO tax_billing_details SET ";
            foreach ($tax_billing_details as $k => $v) {
                $item .= " ".$k." = '".$v."', ";
            }
            $item .= "created = '".$created."', createdby = '".$_SESSION['auth']['id']."'";
            mysqli_query($conn, $item);
          }
        }
        /*-----------------------------------SAVE TAX BILLING ITEMS END----------------------------------------------*/

        /*-----------------------------------SAVE TAX BILLING RETURN ITEMS START--------------------------------------------*/
        $deleteOldReturnItemQ = "DELETE FROM sale_return WHERE tax_bill_id = '".$taxbillid."'";
        mysqli_query($conn, $deleteOldReturnItemQ);

        $countReturn = (isset($_POST['r_product_id']) && !empty($_POST['r_product_id'])) ? count($_POST['r_product_id']) : 0;

        if($countReturn > 0){
          for ($j=0; $j < $countReturn; $j++) {
            $sale_return['financial_id'] = (isset($financial_id) && $financial_id != '') ? $financial_id : NULL;
            $sale_return['owner_id'] = (isset($owner_id) && $owner_id != '') ? $owner_id : NULL;
            $sale_return['admin_id'] = (isset($admin_id) && $admin_id != '') ? $admin_id : NULL;
            $sale_return['pharmacy_id'] = (isset($pharmacy_id) && $pharmacy_id != '') ? $pharmacy_id : NULL;
            $sale_return['tax_bill_id'] = (isset($taxbillid)) ? $taxbillid : '';
            $sale_return['customer_id'] = (isset($_POST['customer_id'])) ? $_POST['customer_id'] : NULL;
            $sale_return['product_id'] = (isset($_POST['r_product_id'][$j])) ? $_POST['r_product_id'][$j] : '';
            $sale_return['qty'] = (isset($_POST['r_qty'][$j]) && $_POST['r_qty'][$j] != '') ? $_POST['r_qty'][$j] : 0;
            $sale_return['rate'] = (isset($_POST['r_rate'][$j]) && $_POST['r_rate'][$j] != '') ? $_POST['r_rate'][$j] : 0;
            $sale_return['discount'] = (isset($_POST['r_discount'][$j]) && $_POST['r_discount'][$j] != '') ? $_POST['r_discount'][$j] : 0;
            $sale_return['gst'] = (isset($_POST['r_gst'][$j]) && $_POST['r_gst'][$j] != '') ? $_POST['r_gst'][$j] : 0;
            $sale_return['igst'] = (isset($_POST['r_igst'][$j]) && $_POST['r_igst'][$j] != '') ? $_POST['r_igst'][$j] : 0;
            $sale_return['cgst'] = (isset($_POST['r_cgst'][$j]) && $_POST['r_cgst'][$j] != '') ? $_POST['r_cgst'][$j] : 0;
            $sale_return['sgst'] = (isset($_POST['r_sgst'][$j]) && $_POST['r_sgst'][$j] != '') ? $_POST['r_sgst'][$j] : 0;
            $sale_return['amount'] = (isset($_POST['r_amount'][$j]) && $_POST['r_amount'][$j] != '') ? $_POST['r_amount'][$j] : 0;

            $itemReturn = "INSERT INTO sale_return SET ";
            foreach ($sale_return as $ks => $vs) {
                $itemReturn .= " ".$ks." = '".$vs."', ";
            }
            $itemReturn .= "created = '".date('Y-m-d H:i:s')."', createdby = '".$_SESSION['auth']['id']."'";
            mysqli_query($conn, $itemReturn);

          }
        }
        /*-----------------------------------SAVE TAX BILLING RETURN ITEMS END----------------------------------------------*/

      }

      if(isset($_GET['id']) && $_GET['id'] != ''){
        $_SESSION['msg']['success'] = "Tax Bill Updated Successfully.";
      }else{
            $id = $taxbillid ;
           $sendmail = send_tax_mail($id);
        $_SESSION['msg']['success'] = "Tax Bill Added Successfully.";
      }
      
      if(isset($_POST['save'])){
          header('Location: view-sales-tax-billing.php');exit;
      }elseif(isset($_POST['saveAndNext'])){
          header('Location: sales-tax-billing.php');exit;
      }elseif(isset($_POST['saveAndPrint'])){
          ?>
          <script>
              window.open("view-sales-tax-billing.php","_self");
              window.open("print-sales-tax-billing.php?id=<?php echo $taxbillid; ?>","_blank");
          </script>
          <?php
          exit;
          //header('Location: print-sales-tax-billing.php?id='.$taxbillid);exit;
      }else{
          header('Location: sales-tax-billing.php');exit;
      }
      
    }else{
      if(isset($_GET['id']) && $_GET['id'] != ''){
        $_SESSION['msg']['fail'] = "Tax Bill Updated Fail!";
      }else{
        $_SESSION['msg']['fail'] = "Tax Bill Added Fail!";
      }
    }
  }
?>
<!-------------------------------------------- CODE FOR ADD AND UPDATE TAX BILLING END ----------------------------------------------->


<!-------------------------------------------- CODE FOR EDIT ID BY GET ALL DATA START ----------------------------------------------->
<?php 
    if(isset($_GET['id']) && $_GET['id'] != ''){
      $editQ = "SELECT tb.*, lg.customer_id as customer_u_id,lg.name as customer_name,lg.mobile as customer_mobile, lg.email as customer_email, lg.city as customer_city  FROM tax_billing tb LEFT JOIN ledger_master lg ON tb.customer_id = lg.id WHERE tb.id = '".$_GET['id']."' AND tb.pharmacy_id = '".$pharmacy_id."' AND tb.financial_id = '".$financial_id."'";
      $editR = mysqli_query($conn, $editQ);
      if($editR && mysqli_num_rows($editR) > 0){
        $editdata = mysqli_fetch_assoc($editR);
        
        $billing_item_Q = "SELECT tbd.*, pm.product_name FROM tax_billing_details tbd LEFT JOIN product_master pm ON tbd.product_id = pm.id WHERE tbd.tax_bill_id = '".$editdata['id']."'";
        $billing_item_R = mysqli_query($conn, $billing_item_Q);
        if($billing_item_R && mysqli_num_rows($billing_item_R) > 0){
            while($billing_item_Row = mysqli_fetch_assoc($billing_item_R)){
                $productData = getAllProductWithCurrentStock('','',0,[$billing_item_Row['product_id']]);
                $billing_item_Row['current_qty'] = (isset($productData[0]['currentstock']) && $productData[0]['currentstock'] != '') ? $productData[0]['currentstock'] : 0;
                $editdata['detail'][] = $billing_item_Row;
            }
        }
      }
    }
?>
<!-------------------------------------------- CODE FOR EDIT ID BY GET ALL DATA END ----------------------------------------------->

<!-------------------------------------------- CODE FOR GET ALL DOCTOR START -------------------------------------------->
    <?php
        $allDoctor = [];
        if(isset($_GET['doctor'])){
            $doctorQ = "SELECT id, CONCAT('Dr. ', fname,' ',lname) as doctor_name FROM users WHERE user_type = 'D' ORDER BY fname";
            $docrotR = mysqli_query($ihis_conn, $doctorQ);
            if($docrotR && mysqli_num_rows($docrotR) > 0){
                while($doctorRow = mysqli_fetch_array($docrotR)){
                    $dtr['id'] = (isset($doctorRow['id'])) ? $doctorRow['id'] : '';
                    $dtr['name'] = (isset($doctorRow['doctor_name'])) ? $doctorRow['doctor_name'] : '';
                    $allDoctor[] = $dtr;
                }
            }
        }else{
            $doctorQ = "SELECT id, name FROM doctor_profile WHERE pharmacy_id = '".$pharmacy_id."' AND status = 1 ORDER BY name";
            $docrotR = mysqli_query($conn, $doctorQ);
            if($docrotR && mysqli_num_rows($docrotR) > 0){
                while ($doctorRow = mysqli_fetch_array($docrotR)) {
                    $dtr['id'] = (isset($doctorRow['id'])) ? $doctorRow['id'] : '';
                    $dtr['name'] = (isset($doctorRow['name'])) ? 'Dr. '.$doctorRow['name'] : '';
                    $allDoctor[] = $dtr;
                }
            }
        }
    ?>
<!-------------------------------------------- CODE FOR GET ALL DOCTOR END -------------------------------------------->

<!-------------------------------------------- CODE FOR I-HIS PRECRIPTION DATA START ----------------------------------------------->
<?php
    if(isset($_GET['patient']) && $_GET['patient'] != ''){
        $currentState = (isset($_SESSION['state_code'])) ? $_SESSION['state_code'] : '';
        $pation_id = $_GET['patient'];
        $group = (isset($_GET['group'])) ? $_GET['group'] : '';
        $patientQ = "SELECT pm.id, pm.patient_id, CONCAT(pm.fname,' ',pm.mname,' ',pm.lname) as patient_name, pm.mobile_no, pm.email, pm.city_id,st.state_code_gst as statecode FROM patient_master pm LEFT JOIN own_states st ON pm.state_id = st.id WHERE pm.id = '".$pation_id."'";
        $patientR = mysqli_query($ihis_conn, $patientQ);
        if($patientR && mysqli_num_rows($patientR) > 0){
            $patientRow = mysqli_fetch_assoc($patientR);
            
            $editdata['patient_id'] = (isset($patientRow['id'])) ? $patientRow['id'] : '';
            $editdata['customer_name'] = (isset($patientRow['patient_name'])) ? $patientRow['patient_name'] : '';
            $editdata['customer_mobile'] = (isset($patientRow['mobile_no'])) ? $patientRow['mobile_no'] : '';
            $editdata['customer_email'] = (isset($patientRow['email'])) ? $patientRow['email'] : '';
            $editdata['customer_city'] = (isset($patientRow['city_id'])) ? $patientRow['city_id'] : '';
            $editdata['statecode'] = (isset($patientRow['statecode'])) ? $patientRow['statecode'] : '';
            $editdata['detail'] = [];
            
            /*-------------FOR IHIS PRECRITION START--------------*/
            $table = '';
            if(isset($_GET['type']) && $_GET['type'] == 'OPD'){
                $table = 'opd_prescription_details';
            }elseif(isset($_GET['type']) && $_GET['type'] == 'IPD'){
                $table = 'ipd_prescription_details';
            }elseif(isset($_GET['type']) && $_GET['type'] == 'ICU'){
                $table = 'icu_treatment_prescription';
            }elseif(isset($_GET['type']) && $_GET['type'] == 'OT'){
                $table = 'ot_prescription';
            }elseif(isset($_GET['type']) && $_GET['type'] == 'ICU Drugs'){
                $table = 'icu_treatment_drugs';
            }
            
            
            $precQ = "SELECT id, products, qty FROM ".$table." WHERE patient_id = '".$pation_id."' AND group_id = '".$group."'";
            $precR = mysqli_query($ihis_conn, $precQ);
            if($precR && mysqli_num_rows($precR) > 0){
                while($precRow = mysqli_fetch_assoc($precR)){
                    $productData = getAllProductWithCurrentStock('','',0,[$precRow['products']]);
                    if(!empty($productData[0])){
                        $productFinalData = $productData[0];
                        
                        $tmp['product_id'] = (isset($productFinalData['id'])) ? $productFinalData['id'] : '';
                        $tmp['product_name'] = (isset($productFinalData['product_name'])) ? $productFinalData['product_name'] : '';
                        $tmp['mrp'] = (isset($productFinalData['mrp']) && $productFinalData['mrp'] != '') ? $productFinalData['mrp'] : 0;
                        $tmp['mfg_co'] = (isset($productFinalData['mfg_company'])) ? $productFinalData['mfg_company'] : '';
                        $tmp['batch'] = (isset($productFinalData['batch_no'])) ? $productFinalData['batch_no'] : '';
                        $tmp['expiry'] = (isset($productFinalData['ex_date']) && $productFinalData['ex_date'] != '') ? $productFinalData['ex_date'] : '';
                        $tmp['qty'] = (isset($precRow['qty']) && $precRow['qty'] != '') ? $precRow['qty'] : 0;
                        $tmp['qty_ratio'] = (isset($productFinalData['ratio']) && $productFinalData['ratio'] != '') ? $productFinalData['ratio'] : 0;
                        $tmp['current_qty'] = (isset($productFinalData['currentstock']) && $productFinalData['currentstock'] != '') ? $productFinalData['currentstock'] : 0;
                        $tmp['ptr'] = (isset($productFinalData['ptr']) && $productFinalData['ptr'] != '') ? $productFinalData['ptr'] : 0;
                        $tmp['discount'] = (isset($productFinalData['discount'])) ? $productFinalData['discount'] : '';
                        $tmp['rate'] = (isset($productFinalData['rate']) && $productFinalData['rate'] != '') ? $productFinalData['rate'] : 1;
                        $tmp['totalamount'] = ($tmp['qty']*$tmp['rate']);
                        $productIgst = (isset($productFinalData['igst']) && $productFinalData['igst'] != '') ? $productFinalData['igst'] : 0;
                        $productCgst = (isset($productFinalData['cgst']) && $productFinalData['cgst'] != '') ? $productFinalData['cgst'] : 0;
                        $productSgst = (isset($productFinalData['sgst']) && $productFinalData['sgst'] != '') ? $productFinalData['sgst'] : 0;
                        if($editdata['statecode'] == $currentState){
                            $tmp['gst'] = ($productCgst + $productSgst);
                            $tmp['igst'] = 0;
                            $tmp['cgst'] = $productCgst;
                            $tmp['sgst'] = $productSgst;
                        }else{
                            $tmp['gst'] = $productIgst;
                            $tmp['igst'] = $productIgst;
                            $tmp['cgst'] = 0;
                            $tmp['sgst'] = 0;
                        }
                        $editdata['detail'][] = $tmp;
                    }
                }
            }
            /*-------------FOR IHIS PRECRITION START--------------*/
        }
    }
?>
<!-------------------------------------------- CODE FOR I-HIS PRECRIPTION DATA END-------------------------------------------------->

<?php
    /*function checkAndAddCustomer($patient_id = null, $customer_id = null, $type = null){
        global $conn;
        global $ihis_conn;
        global $financial_id;
        global $owner_id;
        global $admin_id;
        global $pharmacy_id;
        
        $id = '';
        if($patient_id != ''){
            $checkQ = "SELECT id FROM ledger_master WHERE ihis_patient_id = '".$patient_id."' LIMIT 1";
            $checkR = mysqli_query($conn, $checkQ);
            if($checkR && mysqli_num_rows($checkR) > 0){
                $checkRow = mysqli_fetch_assoc($checkR);
                $id = $checkRow['id'];
            }else{
                $findInIhisQ = "SELECT *, CONCAT(fname,' ',mname,' ',lname) as patient_name FROM patient_master WHERE id = '".$patient_id."' LIMIT 1";
                $findInIhisR = mysqli_query($ihis_conn, $findInIhisQ);
                if($findInIhisR && mysqli_num_rows($findInIhisR) > 0){
                    $findInIhisRow = mysqli_fetch_assoc($findInIhisR);
                    
                    $inserCustomerQ = "INSERT INTO ledger_master SET financial_id = '".$financial_id."', owner_id = '".$owner_id."', admin_id = '".$admin_id."', pharmacy_id = '".$pharmacy_id."', account_type = 1, name = '".$findInIhisRow['patient_name']."', customer_id = '".$customer_id."', mobile = '".$findInIhisRow['mobile_no']."', email = '".$findInIhisRow['email']."', addressline1 = '".$findInIhisRow['address1']."', addressline2 = '".$findInIhisRow['address2']."', addressline3 = '".$findInIhisRow['address3']."', country = '".$findInIhisRow['country_id']."', state = '".$findInIhisRow['state_id']."', city = '".$findInIhisRow['city_id']."', pincode = '".$findInIhisRow['pincode']."', faxno = '".$findInIhisRow['fax']."', group_id = '10', panno = '".$findInIhisRow['pan_no']."', adharno = '".$findInIhisRow['aadhar_no']."', is_ihis = 1, ihis_patient_id = '".$patient_id."', type = '".$type."', status = '1', created = '".date('Y-m-d H:i:s')."', createdby = '".$_SESSION['auth']['id']."'";
                    $inserCustomerR = mysqli_query($conn, $inserCustomerQ);
                    if($inserCustomerR){
                        $id = mysqli_insert_id($conn);
                    }
                }
            }
        }
        return $id;
    }*/
    
    function AddIhisDoctor($doctor_id = null, $doctor_mobile = null){
        global $conn;
        global $ihis_conn;
        global $financial_id;
        global $owner_id;
        global $admin_id;
        global $pharmacy_id;
        $id = '';
        if($doctor_id != ''){
            $existDoctorQ = "SELECT id FROM doctor_profile WHERE ihis_doctor_id = '".$doctor_id."' LIMIT 1";
            $existDoctorR = mysqli_query($conn, $existDoctorQ);
            if($existDoctorR && mysqli_num_rows($existDoctorR) > 0){
                $existDoctorRow = mysqli_fetch_assoc($existDoctorR);
                $id = $existDoctorRow['id'];
            }else{
                // insert new doctor
                $findDoctorQ = "SELECT *, CONCAT(fname,' ',lname) as doctor_name FROM users WHERE id = '".$doctor_id."' AND user_type = 'D' LIMIT 1";
                $findDoctorR = mysqli_query($ihis_conn, $findDoctorQ);
                if($findDoctorR && mysqli_num_rows($findDoctorR) > 0){
                    $findDoctorRow = mysqli_fetch_assoc($findDoctorR);
                    $doctormobile = ($doctor_mobile != '') ? $doctor_mobile : $findDoctorRow['mobile_no'];
                    
                    $newDoctorQ = "INSERT INTO doctor_profile SET 
                                        financial_id = '".$financial_id."',
                                        owner_id = '".$owner_id."',
                                        admin_id = '".$admin_id."',
                                        pharmacy_id = '".$pharmacy_id."',
                                        personal_title = 'Dr',
                                        name = '".$findDoctorRow['doctor_name']."',
                                        mobile = '".$doctormobile."',
                                        country = '".$findDoctorRow['country_id']."',
                                        state = '".$findDoctorRow['state_id']."',
                                        city = '".$findDoctorRow['city']."',
                                        address = '".$findDoctorRow['address']."',
                                        pincode = '".$findDoctorRow['pincode']."',
                                        is_ihis = 1,
                                        ihis_doctor_id = '".$doctor_id."',
                                        status = 1,
                                        created = '".date('Y-m-d H:i:s')."',
                                        createdby = '".$_SESSION['auth']['id']."'";
                    $newDoctorR = mysqli_query($conn, $newDoctorQ);
                    if($newDoctorR){
                        $id = mysqli_insert_id($conn);
                    }
                }
            }
        }
        return $id;
    }
    
    function updateIhisPrecriptionBillFlag($patient = null, $group = null, $type = null){
        global $ihis_conn;
        $table = '';
        if($type == 'OPD'){
            $table = 'opd_prescription_details';
        }elseif($type == 'IPD'){
            $table = 'ipd_prescription_details';
        }elseif($type == 'ICU'){
            $table = 'icu_treatment_prescription';
        }elseif($type == 'OT'){
            $table = 'ot_prescription';
        }elseif($type == 'ICU Drugs'){
            $table = 'icu_treatment_drugs';
        }
        
        $updateQ = "UPDATE ".$table." SET is_pharmacy_bill = 1 WHERE patient_id = '".$patient."' AND group_id = '".$group."'";
        $updateR = mysqli_query($ihis_conn, $updateQ);
        if($updateR){
            return true;
        }else{
            return false;
        }
    }
    
    function findCustomer($post = []){
        global $conn;
        global $ihis_conn;
        global $financial_id;
        global $owner_id;
        global $admin_id;
        global $pharmacy_id;
        $customer_id = '';
        
        if(isset($post) && !empty($post)){
            $name = (isset($post['customer_name'])) ? $post['customer_name'] : '';
            $customer_u_id = (isset($post['customer_u_id'])) ? $post['customer_u_id'] : '';
            $mobile = (isset($post['customer_mobile'])) ? $post['customer_mobile'] : '';
            $email = (isset($post['customer_email'])) ? $post['customer_email'] : '';
            $city = (isset($post['customer_city'])) ? $post['customer_city'] : '';
            $bill_type = (isset($post['bill_type'])) ? $post['bill_type'] : '';
            $patient_id = (isset($post['patient_id'])) ? $post['patient_id'] : '';
            $type = (isset($post['ihis_type'])) ? $post['ihis_type'] : '';//IPD, OPD
            $is_cash = ($bill_type == 'Cash') ? 1 : 0;
            
            if($patient_id != ''){
                $getCustomerQ = "SELECT * FROM ledger_master WHERE ihis_patient_id = '".$patient_id."' LIMIT 1";
                $getCustomerR = mysqli_query($conn, $getCustomerQ);
                if($getCustomerR && mysqli_num_rows($getCustomerR) > 0){
                    $getCustomerRow = mysqli_fetch_assoc($getCustomerR);
                    $customer_id = $getCustomerRow['id'];
                    if($bill_type == 'Cash' && (isset($getCustomerRow['is_cash']) && $getCustomerRow['is_cash'] != 0)){
                        mysqli_query($conn, "UPDATE ledger_master SET is_cash = 1 WHERE id = '".$id."'");
                    }else{
                        mysqli_query($conn, "UPDATE ledger_master SET is_cash = 0 WHERE id = '".$id."'");
                    }
                }else{
                    $findInIhisQ = "SELECT *, CONCAT(fname,' ',mname,' ',lname) as patient_name FROM patient_master WHERE id = '".$patient_id."' LIMIT 1";
                    $findInIhisR = mysqli_query($ihis_conn, $findInIhisQ);
                    if($findInIhisR && mysqli_num_rows($findInIhisR) > 0){
                        $findInIhisRow = mysqli_fetch_assoc($findInIhisR);
                        
                        $inserCustomerQ = "INSERT INTO ledger_master SET financial_id = '".$financial_id."', owner_id = '".$owner_id."', admin_id = '".$admin_id."', pharmacy_id = '".$pharmacy_id."', account_type = 1, name = '".$findInIhisRow['patient_name']."', customer_id = '".$customer_u_id."', mobile = '".$findInIhisRow['mobile_no']."', email = '".$findInIhisRow['email']."', addressline1 = '".$findInIhisRow['address1']."', addressline2 = '".$findInIhisRow['address2']."', addressline3 = '".$findInIhisRow['address3']."', country = '".$findInIhisRow['country_id']."', state = '".$findInIhisRow['state_id']."', city = '".$findInIhisRow['city_id']."', pincode = '".$findInIhisRow['pincode']."', faxno = '".$findInIhisRow['fax']."', group_id = '10', panno = '".$findInIhisRow['pan_no']."', adharno = '".$findInIhisRow['aadhar_no']."', is_ihis = 1, ihis_patient_id = '".$patient_id."', type = '".$type."', is_cash = '".$is_cash."', status = '1', created = '".date('Y-m-d H:i:s')."', createdby = '".$_SESSION['auth']['id']."'";
                        $inserCustomerR = mysqli_query($conn, $inserCustomerQ);
                        if($inserCustomerR){
                            $customer_id = mysqli_insert_id($conn);
                        }
                    }
                }
            }else{
                if(isset($post['customer_id']) && $post['customer_id'] == ''){
                    $query = "INSERT INTO ledger_master SET financial_id = '".$financial_id."', owner_id = '".$owner_id."', admin_id = '".$admin_id."', pharmacy_id = '".$pharmacy_id."', account_type = 1, name = '".$name."', customer_id = '".$customer_u_id."', mobile = '".$mobile."', email = '".$email."', country = '101', state = '12', city = '".$city."', group_id = '10', is_cash = '".$is_cash."', status = '1', created = '".date('Y-m-d H:i:s')."', createdby = '".$_SESSION['auth']['id']."'";
                    $res = mysqli_query($conn, $query);
                    if($res){
                        $customer_id = mysqli_insert_id($conn);
                    }
                }else{
                    $id = (isset($post['customer_id'])) ? $post['customer_id'] : '';
                    $getCustomerQ = "SELECT * FROM ledger_master WHERE id = '".$id."'";
                    $getCustomerR = mysqli_query($conn, $getCustomerQ);
                    if($getCustomerR && mysqli_num_rows($getCustomerR) > 0){
                        $getCustomerRow = mysqli_fetch_assoc($getCustomerR);
                        $customer_id = $getCustomerRow['id'];
                        if($bill_type == 'Cash' && (isset($getCustomerRow['is_cash']) && $getCustomerRow['is_cash'] != 0)){
                            mysqli_query($conn, "UPDATE ledger_master SET is_cash = 1 WHERE id = '".$id."'");
                        }else{
                            mysqli_query($conn, "UPDATE ledger_master SET is_cash = 0 WHERE id = '".$id."'");
                        }
                    }
                }
            }
        }
        return $customer_id;
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>DigiBooks | <?php echo (isset($_GET['id']) && $_GET['id'] != '') ? 'Edit' : 'Add'; ?> Sale Tax Bill</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="vendors/iconfonts/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="vendors/iconfonts/puse-icons-feather/feather.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.addons.css">
  <!-- endinject -->
  
   <!-- plugin css for this page -->
  <link rel="stylesheet" href="vendors/icheck/skins/all.css">
  
  <!-- plugin css for this page -->
  <link rel="stylesheet" href="vendors/iconfonts/font-awesome/css/font-awesome.min.css" />
  <link rel="stylesheet" href="vendors/iconfonts/simple-line-icon/css/simple-line-icons.css">
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="css/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="images/favicon.png" />
  <link rel="stylesheet" href="css/parsley.css">
  <link rel="stylesheet" href="css/messagebox.css">
  <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
  <style type="text/css">
     .ui-autocomplete { z-index:2147483647 !important; }
  </style>
</head>
<body>
  <div class="container-scroller">
  
    <!-- Topbar -->
    <?php include "include/topbar.php"; ?>
    
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">

      <!-- Right Sidebar -->
      <?php include "include/sidebar-right.php" ?>
        
       
      <!-- Left Navigation -->
      <?php include "include/sidebar-nav-left.php" ?>
      
      
      <div class="main-panel">
      
        <div class="content-wrapper">
          <span id="errormsg"></span>
          <div class="row">
            <?php include "include/sale_header.php"; ?>
            <!-- Form -->
            <form method="POST" autocomplete="off">
              <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <br>
                      <div class="form-group row">
                        
                        <div class="col-12 col-md-2">
                          <label for="customer_id">Customer Name <span class="text-danger">*</span></label>
                          <input class="form-control" data-name="name" autocomplete="nope" type="text" value="<?php echo (isset($editdata['customer_name'])) ? $editdata['customer_name'] : '' ?>" name="customer_name" id="customer_name" required data-parsley-errors-container="#error-customer_id">
                          <small class="customererror text-danger"></small>
                          <span id="error-customer_id"></span>
                          <input type="hidden" name="statecode" id="statecode" value="<?php echo (isset($editdata['statecode'])) ? $editdata['statecode'] : ''; ?>">
                          <input type="hidden" name="cur_statecode" id="cur_statecode" value="<?php echo (isset($_SESSION['state_code'])) ? $_SESSION['state_code'] : ''; ?>" >
                          <input type="hidden" name="customer_id" id="customer_id" value="<?php echo (isset($editdata['customer_id'])) ? $editdata['customer_id'] : ''; ?>" >
                            <!--ONLY FOR IHIS START-->
                              <input type="hidden" name="patient_id" id="patient_id" value="<?php echo (isset($editdata['patient_id'])) ? $editdata['patient_id'] : ''; ?>">
                              <input type="hidden" name="ihis_type" id="ihis_type" value="<?php echo (isset($_GET['type'])) ? $_GET['type'] : ''; ?>">
                              <input type="hidden" name="ihis_group" id="ihis_group" value="<?php echo (isset($_GET['group'])) ? $_GET['group'] : ''; ?>">
                            <!--ONLY FOR IHIS END-->
                          <input type="hidden" name="limit_total" id="limit_total" value="<?php echo (isset($_GET['id'])) ? CheckCr($editdata['customer_id']) : '0'; ?>">
                          <input type="hidden" name="limit_status" id="limit_status" value="0">
                          <input type="hidden" name="popup_status" id="popup_status" value="0">
                           <i class="fa fa-spin fa-refresh" id="customer_loader" style="position: absolute; top: 40px; right: 40px; display: none;"></i>
                        </div>

                        <div class="col-12 col-md-2">
                          <label for="customer_id">Customer ID</label>
                          <input type="text" readonly class="form-control" name="customer_u_id" id="customer_u_id" placeholder="Customer ID" value="<?php echo (isset($editdata['customer_u_id'])) ? $editdata['customer_u_id'] : getcustomerID(); ?>">
                        </div>

                        <div class="col-12 col-md-2">
                          <label for="customer_mobile">Customer Mobile</label>
                          <input type="text" class="form-control onlynumber" maxlength="10" autocomplete="nope" data-name="mobile" name="customer_mobile" id="customer_mobile" placeholder="Customer Mobile" value="<?php echo (isset($editdata['customer_mobile'])) ? $editdata['customer_mobile'] : ''; ?>">
                          <small class="customererror text-danger"></small>
                        </div>
                        
                        <div class="col-12 col-md-2">
                          <label for="customer_city">Customer City</label>
                          <select class="js-example-basic-single" style="width:100%" name="customer_city" id="customer_city">
                            <option value="">Select City</option>
                            <?php 
                                $getCityQ = "SELECT ct.id, ct.name, st.state_code_gst as statecode FROM ledger_master lg INNER JOIN own_cities ct ON lg.city = ct.id LEFT JOIN own_states st ON ct.state_id = st.id WHERE lg.group_id = 10 AND lg.pharmacy_id = '".$pharmacy_id."' GROUP BY lg.city ORDER BY ct.name";
                                $getCityR = mysqli_query($conn, $getCityQ);
                                if($getCityR && mysqli_num_rows($getCityR) > 0){
                                    while($getCityRow = mysqli_fetch_assoc($getCityR)){
                            ?>
                                <option data-code = "<?php echo (isset($getCityRow['statecode'])) ? $getCityRow['statecode'] : ''; ?>" value="<?php echo $getCityRow['id']; ?>" <?php echo (isset($editdata['customer_city']) && $editdata['customer_city'] == $getCityRow['id']) ? 'selected' : ''; ?> ><?php echo $getCityRow['name']; ?></option>
                            <?php 
                                    }
                                }
                            ?>
                          </select>
                        </div>
                        
                        <div class="col-12 col-md-2">
                          <label for="customer_email">Customer Email</label>
                          <input type="text" class="form-control" autocomplete="nope" data-name="email" name="customer_email" id="customer_email" placeholder="Customer Email" value="<?php echo (isset($editdata['customer_email'])) ? $editdata['customer_email'] : ''; ?>">
                          <small class="customererror text-danger"></small>
                        </div>
                        
                        <div class="col-12 col-md-2">
                          <a href="javascript:void(0);" class="btn btn-primary btn-xs mt-30 pull-right" data-toggle="modal" data-target="#add_customer_model" data-whatever="@mdo">Add Customer</a>
                        </div>
                        
                      </div>

                      <div class="form-group row">
                          
                        <div class="col-12 col-md-2">
                          <label for="doctor">Doctor</label>
                          <select class="js-example-basic-single" style="width:100%" name="doctor" id="doctor">
                            <option value="">Select Doctor</option>
                            <?php
                              if(isset($_GET['doctor']) && $_GET['doctor'] != ''){
                                  $editdata['doctor'] = $_GET['doctor'];
                              }
                              if(isset($allDoctor) && !empty($allDoctor)){
                                foreach ($allDoctor as $key => $value) {
                            ?>
                              <option value="<?php echo $value['id'] ?>" <?php echo (isset($editdata['doctor']) && $editdata['doctor'] == $value['id']) ? 'selected' : ''; ?> ><?php echo $value['name']; ?></option>
                            <?php 
                                }
                              }
                            ?>
                          </select>
                        </div>
                        <?php
                            if(isset($editdata['doctor']) && isset($_GET['id'])){
                                $doctorQ = "SELECT mobile FROM doctor_profile WHERE pharmacy_id = '".$pharmacy_id."' AND status = 1 AND id='".$editdata['doctor']."'";
                                $docrotR = mysqli_query($conn, $doctorQ);
                                $doctorRow = mysqli_fetch_array($docrotR);
                            }
                            if(isset($_GET['doctormobile']) && $_GET['doctormobile'] != ''){
                                $doctorRow['mobile'] = $_GET['doctormobile'];
                            }
                        ?>

                        <div class="col-12 col-md-2">
                          <label for="doctor_mobile">Doctor Mobile</label>
                          <input type="text" class="form-control" name="doctor_mobile" id="doctor_mobile" placeholder="Doctor Mobile" value="<?php echo (isset($doctorRow['mobile'])) ? $doctorRow['mobile'] : ''; ?>">
                        </div>

                        <div class="col-12 col-md-2">
                          <label for="exampleInputName1">Invoice Date <span class="text-danger">*</span></label>
                          <div class="input-group date <?php if(!isset($_GET['id'])){echo"datepicker";} ?>">
                            <?php 
                              if(isset($_GET['id']) && $_GET['id'] != ''){
                                $invoicedate = (isset($editdata['invoice_date']) && $editdata['invoice_date'] != '') ? date('d/m/Y', strtotime($editdata['invoice_date'])) : '';
                              }else{
                                $invoicedate = date('d/m/Y');
                              }
                            ?>
                            <input type="text" class="form-control border" name="invoice_date" autocomplete="off" <?php if(isset($_GET['id'])){echo"readonly";} ?> value="<?php echo (isset($invoicedate)) ? $invoicedate : ''; ?>" required data-parsley-errors-container="#error-date">
                            <span class="input-group-addon input-group-append border-left">
                              <span class="mdi mdi-calendar input-group-text"></span>
                            </span>
                          </div>
                          <span id="error-date"></span>
                        </div>
                        <div class="col-12 col-md-2">
                          <label for="exampleInputName1">Invoice No <span class="text-danger">*</span></label>
                          <input type="text" class="form-control" <?php if(isset($_GET['id'])){echo"readonly";} ?> name="invoice_no" id="invoice_no" placeholder="Invoice No" value="<?php echo (isset($editdata['invoice_no'])) ? $editdata['invoice_no'] : getInvoiceNo('Debit'); ?>" required>
                        </div>
                        
                        <div class="col-12 col-md-2">
                          <label for="exampleInputName1">Bill Type</label>
                          <div class="row no-gutters">
                            <div class="col">
                                <div class="form-radio">
                                  <label class="form-check-label">
                                    <input type="radio" class="form-check-input bill_type" name="bill_type" value="Cash" <?php echo (isset($editdata['bill_type']) && $editdata['bill_type'] == 'Cash') ? 'checked' : ''; ?> >
                                     CASH
                                  </label>
                                </div>
                            </div>
                            
                            <div class="col">
                                <div class="form-radio">
                                  <label class="form-check-label">
                                     <?php 
                                        if(isset($editdata['bill_type'])){
                                            $cashchecked = ($editdata['bill_type'] == 'Debit') ? 'checked' : '';
                                        }else{
                                            $cashchecked = 'checked';
                                        }
                                    ?>
                                    <input type="radio" class="form-check-input bill_type" name="bill_type" value="Debit" <?php echo (isset($cashchecked)) ? $cashchecked : ''; ?> >
                                    DEBIT
                                </label>
                                </div>
                            </div>
                          </div>
                        </div>
                        
                        <div class="col-12 col-md-2">
                           <a href="javascript:void(0);" data-toggle="modal" data-target="#add_doctor_model" class="btn btn-primary btn-xs mt-30 pull-right">Add Doctor</a>
                        </div>
                        
                      </div>
                  </div>
                </div>
              </div>
              <!-- Table -------------->
              <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <div class="col mt-3">
                      <div class="row">
                        <div class="col-12">
                        <?php 
                            $QueryP = "SELECT firm_name FROM `pharmacy_profile` WHERE id='".$pharmacy_id."'";
                            $ResultP = mysqli_query($conn,$QueryP);
                            $RowP = mysqli_fetch_array($ResultP);
                            $firm_name = $RowP['firm_name'];
                        ?>
                          <table class="table">
                            <thead>
                              <tr>
                                  <th>Sr No</th>
                                  <th width="15%">Product</th>
                                  <th>MFG. Co.</th>
                                  <th>Batch</th>
                                  <th>Expiry</th>
                                  <th>MRP</th>
                                  <th>Qty.</th>
                                  <th>Discount</th>
                                  <th>GST%</th>
                                  <th>GST</th>
                                  <th>Amount</th>
                                  <th width="8%">&nbsp;</th>
                              </tr>
                            </thead>
                            <tbody id="item-tbody">
                              <?php if(isset($editdata['detail']) && !empty($editdata['detail'])){ ?>
                                <?php foreach($editdata['detail'] as $key => $value){?>
                                  <tr>
                                    <td><?php echo $key+1; ?></td>
                                    <td>
                                      <input type="text" name="product[]" class="form-control product" placeholder="Product" value="<?php echo (isset($value['product_name'])) ? $value['product_name'] : ''; ?>" required>
                                      <small class="producterror text-danger"></small>
                                      <input type="hidden" class="product_id" name="product_id[]" value="<?php echo (isset($value['product_id'])) ? $value['product_id'] : ''; ?>"></td>
                                    <td><input type="text" name="mrp[]" class="form-control mrp onlynumber" placeholder="MRP" value="<?php echo (isset($value['mrp'])) ?$value['mrp'] : ''; ?>"></td>
                                    <td><input type="text" name="mfg_co[]" class="form-control mfg" placeholder="MFG. Co." value="<?php echo(isset($value['mfg_co'])) ? $value['mfg_co'] : ''; ?>"></td>
                                    <td><input type="text" name="batch[]" class="form-control batch" placeholder="Batch" value="<?php echo (isset($value['batch'])) ? $value['batch'] : ''; ?>"></td>
                                    <td>
                                        <input type="text" name="expiry[]" class="form-control expiry datepicker" placeholder="Expiry" value="<?php echo (isset($value['expiry']) && $value['expiry'] != '') ? date('d/m/Y',strtotime($value['expiry'])) : ''; ?>">
                                        <small class="expired text-danger"></small>
                                    </td>
                                    <td>
                                      <input type="text" name="qty[]" class="form-control qty onlynumber" placeholder="Qty." value="<?php echo (isset($value['qty'])) ? $value['qty'] : ''; ?>" required>
                                      <input type="hidden" name="qty_ratio[]" class="qty_ratio" value="<?php echo (isset($value['qty_ratio'])) ? $value['qty_ratio'] : 0; ?>">
                                      <input type="hidden" name="current_qty[]" class="current_qty" value="<?php echo (isset($value['current_qty']) && $value['current_qty'] != '') ? $value['current_qty'] : 0; ?>">
                                      <small class="qty_error text-danger">
                                          <?php 
                                            $qty = (isset($value['qty']) && $value['qty'] != '') ? $value['qty'] : 0;
                                            $stock = (isset($value['current_qty']) && $value['current_qty'] != '') ? $value['current_qty'] : 0;
                                            if($stock < $qty){
                                                echo 'Only available Stock '.$stock;
                                            }
                                          ?>
                                      </small>
                                    </td>
                                    <td><input type="text" name="freeqty[]" class="form-control freeqty onlynumber" placeholder="Free Qty" value="<?php echo (isset($value['freeqty'])) ? $value['freeqty'] : ''; ?>"></td>
                                    <td><input type="text" readonly name="ptr[]" class="form-control ptr" placeholder="PTR" value="<?php echo (isset($value['ptr'])) ? $value['ptr'] : ''; ?>"></td>
                                    <td><input type="text" name="discount[]" class="form-control discount onlynumber" placeholder="Discount(RS)" value="<?php echo (isset($value['discount'])) ? $value['discount'] : ''; ?>"></td>
                                    <td><input type="text" name="rate[]" class="form-control rate onlynumber" placeholder="Rate" value="<?php echo (isset($value['rate'])) ? $value['rate'] : ''; ?>" required></td>
                                    <?php 
                                    if($firm_name != "Composition"){
                                    ?>
                                    <td>
                                      <input type="text" name="gst[]" class="form-control gst onlynumber" placeholder="GST(%)" value="<?php echo (isset($value['gst'])) ? $value['gst'] : ''; ?>">
                                      <input type="hidden" name="igst[]" class="c_igst" value="<?php echo (isset($value['igst'])) ? $value['igst'] : 0; ?>">
                                      <input type="hidden" name="cgst[]" class="c_cgst" value="<?php echo (isset($value['cgst'])) ? $value['cgst'] : 0; ?>">
                                      <input type="hidden" name="sgst[]" class="c_sgst" value="<?php echo (isset($value['sgst'])) ? $value['sgst'] : 0; ?>">
                                    </td>
                                    <?php } ?>
                                    <td>
                                        <input type="text" name="totalamount[]" class="form-control totalamount onlynumber" placeholder="0.00" value="<?php echo (isset($value['totalamount'])) ? $value['totalamount'] : ''; ?>" readonly>
                                        <input type="hidden" name="created[]" value="<?php echo (isset($value['created']) && $value['created'] != '' && $value['created'] != '0000-00-00 00:00:00') ? $value['created'] : ''; ?>">
                                    </td>
                                    <td>
                                      <button type="button" class="btn btn-primary btn-xs pt-2 pb-2 btn-add-more-item"><i class="fa fa-plus mr-0 ml-0"></i></button>
                                    </td>
                                  </tr>
                                <?php } ?>
                              <?php }else{ ?>
                                <tr>
                                  <td>1</td>
                                  <td>
                                    <input type="text" name="product[]" class="form-control product" placeholder="Product" required>
                                    <small class="producterror text-danger"></small>
                                    <input type="hidden" class="product_id" name="product_id[]">
                                  </td>
                                  <td>
                                    <input type="text" name="mfg_co[]" class="form-control mfg" placeholder="MFG. Co.">
                                  </td>
                                  <td>
                                    <input type="text" name="batch[]" class="form-control batch" placeholder="Batch">
                                  </td>
                                  <td>
                                      <input type="text" name="expiry[]" class="form-control expiry datepicker" placeholder="Expiry">
                                      <small class="expired text-danger"></small>
                                  </td>
                                  <td>
                                    <input type="text" name="mrp[]" class="form-control mrp onlynumber" placeholder="MRP" readonly>
                                  </td>
                                  <td>
                                    <input type="text" name="qty[]" class="form-control qty onlynumber" placeholder="Qty." required>
                                    <input type="hidden" name="qty_ratio[]" class="qty_ratio">
                                    <input type="hidden" name="rate[]" class="rate">
                                    <input type="hidden" name="current_qty[]" class="current_qty">
                                    <small class="qty_error text-danger"></small>
                                  </td>
                                  <td>
                                    <input type="text" name="discount[]" class="form-control discount onlynumber" placeholder="Discount(RS)">
                                  </td>
                                  <td>
                                    <input type="text" name="gst[]" class="form-control gst onlynumber" placeholder="GST(%)" readonly>
                                    <input type="hidden" name="igst[]" class="igst" value="0">
                                    <input type="hidden" name="cgst[]" class="cgst" value="0">
                                    <input type="hidden" name="sgst[]" class="sgst" value="0">
                                  </td>
                                  <td>
                                    <input type="text" name="gst_tax[]" class="form-control gst_tax onlynumber" placeholder="GST" readonly>
                                  </td>
                                  <td>
                                    <input type="text" name="totalamount[]" class="form-control totalamount onlynumber" placeholder="0.00" readonly>
                                  </td>
                                  <td>
                                    <button type="button" class="btn btn-primary btn-xs pt-2 pb-2 btn-add-more-item">
                                      <i class="fa fa-plus mr-0 ml-0"></i>
                                    </button>
                                  </td>
                                </tr>
                            <?php } ?>
                              <!-- End Row -->  
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                    <hr>
                    <div class="col-12">
                      <div class="row">
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="sales-filter-btns-right display-3" style="display:inline-block">
                                        <a href="#" class="btn btn-primary-light-green btn-xs" id="btn-alternate" data-toggle="modal" data-target="#alternate_product_model" data-whatever="">Alternates</a>
                                        <a href="#" class="btn btn-primary-light-green btn-xs" data-toggle="modal" data-target="#missed-order-model" data-whatever="">Missed Order</a>
                                        <a href="#" class="btn btn-primary-light-green btn-xs" id="btn-saveandreturn" data-toggle="modal" data-target="#save_and_return_model" data-whatever="">Save &amp; Return </a>
                                        <a href="#" class="btn btn-primary-light-green btn-xs" data-toggle="modal" data-target="#apply_template_model" data-whatever="">Template </a>
                                        <a href="javascript:void(0);" class="btn btn-primary-light-green btn-xs" id="show_previous_bill_product">Show Previous Bill Product <i class="fa fa-spin fa-refresh" id="previous_bill_product_loader" style="margin-left:10px; display: none;"></i></a>
                                        <!--<a href="#" class="btn btn-primary-light-green btn-xs">By Min Reorder</a>
                                        <a href="#" class="btn btn-primary-light-green btn-xs">By Product</a> -->
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="margin-top: 15px;">
                                <div class="col-md-3">
                                    <div class="sales-filter-btns-right display-3">
                                        <div class="form-check">
                                          <label class="form-check-label">
                                            <input <?php if(isset($editdata['remider']) && $editdata['remider'] == "1"){echo "checked"; } ?> type="checkbox"  id="remider" value="1" class="form-check-input ModuleChange" name="remider">
                                            Remider
                                          </label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-3 remider_div <?php echo (isset($editdata['remider']) && $editdata['remider'] == 1) ? 'display-block' : 'display-none'; ?>">
                                    <lable></lable>
                                    <select name="time_name" class="js-example-basic-single" style="width:100%"> 
                                        <option value="">Select Time</option>
                                        <option <?php if(isset($editdata['time_name']) && $editdata['time_name'] == "Once"){ echo "selected"; } ?> value="Once">Once</option>
                                        <option <?php if(isset($editdata['time_name']) && $editdata['time_name'] == "Weekly"){ echo "selected"; } ?> value="Weekly">Weekly</option>
                                        <option <?php if(isset($editdata['time_name']) && $editdata['time_name'] == "15 Days"){ echo "selected"; } ?> value="15 Days">15 Days</option>
                                        <option <?php if(isset($editdata['time_name']) && $editdata['time_name'] == "Every 1 Month"){ echo "selected"; } ?> value="Every 1 Month">Every 1 Month</option>
                                        <option <?php if(isset($editdata['time_name']) && $editdata['time_name'] == "Every 2 Month"){ echo "selected"; } ?> value="Every 2 Month">Every 2 Month</option>
                                        <option <?php if(isset($editdata['time_name']) && $editdata['time_name'] == "Every 3 Month"){ echo "selected"; } ?> value="Every 3 Month">Every 3 Month</option>
                                        <option <?php if(isset($editdata['time_name']) && $editdata['time_name'] == "Yearly"){ echo "selected"; } ?> value="Yearly">Yearly</option>
                                    </select>
                                </div>
                                <div class="col-md-3 remider_div <?php echo (isset($editdata['remider']) && $editdata['remider'] == 1) ? 'display-block' : 'display-none'; ?>">
                                    <div class="input-group date datepicker">
                                        <?php 
                                          if(isset($_GET['id']) && $_GET['id'] != ''){
                                            $remiderdate = (isset($editdata['remiderdate']) && $editdata['remiderdate'] != '') ? date('d/m/Y', strtotime($editdata['remiderdate'])) : '';
                                          }else{
                                            $remiderdate = date('d/m/Y');
                                          }
                                        ?>
                                        <input type="text" class="form-control border" name="remiderdate" autocomplete="off" value="<?php echo (isset($remiderdate)) ? $remiderdate : ''; ?>">
                                        <span class="input-group-addon input-group-append border-left">
                                          <span class="mdi mdi-calendar input-group-text"></span>
                                        </span>
                                      </div>
                                  <span id="error-date"></span>
                                </div>
                                
                            </div>
                          
                          
                        </div>
                          
                        <div class="col-md-4">
                          <div class="form-group row">
                            <table class="table table-striped" width="100%">
                              <tbody>
                              
                                <tr>
                                  <td align="right" style="width:100px;">
                                    Total
                                  </td>
                                  <td align="right">
                                    <input type="text" name="alltotalamount" id="alltotalamount" class="form-control onlynumber" placeholder="Total Amount" value="<?php echo (isset($editdata['alltotalamount'])) ? $editdata['alltotalamount'] : ''; ?>" readonly>
                                  </td>
                                </tr>

                                <tr>
                                    <td style="width: 190px;">
                                        <div class="input-group">
                                          <input type="text" class="form-control onlynumber" name="discount_per" id="discount_per" aria-label="Amount (to the nearest dollar)" placeholder="Discount &#37;" value="<?php echo (isset($editdata['discount_per']) && $editdata['discount_per'] != 0) ? $editdata['discount_per'] : ''; ?>">
                                          <div class="input-group-append">
                                            <span class="input-group-text"><i class="fa fa-percent"></i></span>
                                          </div>
                                        </div>
                                    </td>
                                         
                                    <td>
                                        <div class="input-group">
                                            <input type="text" class="form-control onlynumber" name="discount_rs" id="discount_rs" aria-label="Amount (to the nearest dollar)" placeholder="Discount &#8377;" value="<?php echo (isset($editdata['discount_rs']) && $editdata['discount_rs'] != 0) ? $editdata['discount_rs'] : ''; ?>">
                                            <div class="input-group-append">
                                                <span class="input-group-text"><i class="fa fa-rupee"></i></span>
                                            </div>
                                        </div>  
                                    </td>
                                </tr>
                                
                                <tr>
                                  <td align="right">
                                    IGST
                                  </td>
                                  <td align="right">
                                    <input type="text" name="totaligst" id="totaligst" class="form-control onlynumber" placeholder="0.00" value="<?php echo (isset($editdata['totaligst'])) ? $editdata['totaligst'] : ''; ?>" readonly>
                                  </td>
                                </tr>
                                
                                <tr>
                                  <td align="right">
                                    CGST
                                  </td>
                                  <td align="right">
                                    <input type="text" name="totalcgst" id="totalcgst" class="form-control onlynumber" placeholder="0.00" value="<?php echo (isset($editdata['totalcgst'])) ? $editdata['totalcgst'] : ''; ?>" readonly>
                                  </td>
                                </tr>
                                
                                <tr>
                                  <td align="right">
                                    SGST
                                  </td>
                                  <td align="right">
                                    <input type="text" name="totalsgst" id="totalsgst" class="form-control onlynumber" placeholder="0.00" value="<?php echo (isset($editdata['totalsgst'])) ? $editdata['totalsgst'] : ''; ?>" readonly>
                                  </td>
                                </tr>
                                
                                <tr style="background:#ececec;">
                                  <td align="right">
                                    Total Amount
                                  </td>
                                  <td align="right">
                                    <input type="text" name="purchase_amount" id="purchase_amount" class="form-control onlynumber" placeholder="0.00" value="<?php echo (isset($editdata['purchase_amount'])) ? number_format($editdata['purchase_amount'], 2, '.', '') : ''; ?>" readonly>
                                  </td>
                                </tr>
                                
                                <tr style="background:#e0e0e0;">
                                  <td align="right">
                                    Round off
                                  </td>
                                  <td align="right">
                                    <input type="text" name="roundoff_amount" id="roundoff_amount" class="form-control onlynumber" placeholder="0.00" value="<?php echo (isset($editdata['roundoff_amount'])) ? $editdata['roundoff_amount'] : ''; ?>" readonly>
                                  </td>
                                </tr>
                                
                                <tr style="background:#0062ab;color:#fff;">
                                  <td align="right">
                                    <strong>NET VALUE (<i class="fa fa-rupee"></i>)</strong>
                                  </td>
                                  <td align="right">
                                   <strong><input type="text" name="final_amount" id="final_amount" class="form-control onlynumber" placeholder="0.00" value="<?php echo (isset($editdata['final_amount'])) ? $editdata['final_amount'] : ''; ?>" readonly></strong>
                                  </td>
                                </tr>
                                
                              </tbody>
                            </table>
                          </div>
                        </div>

                      </div>
                    </div>
                    <div class="col-12" style="padding: 0px;">
                      <div class="row">
                        <div class="col-md-12">
                          <a href="view-sales-tax-billing.php" class="btn btn-light pull-left">Cancel</a>
                          <button type="submit" name="save" class="btn btn-success mr-2 pull-right">Save</button>
                          <button type="submit" name="saveAndNext" class="btn btn-success mr-2 pull-right">Save & Next</button>
                          <button type="submit" name="saveAndPrint" class="btn btn-success mr-2 pull-right">Save & Print</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
        <!-- content-wrapper ends -->
        
        <!-- partial:partials/_footer.php -->
        <?php include "include/footer.php"?>
        <!-- partial -->
        <?php include "popup/add-customer-model.php"?>
        
        <?php include "popup/limit-show-model.php"?>
        <!-- Alternates Model -->
        <?php include("popup/alternate-product-model.php");?>
        <!-- Missed Order Model -->
        <?php include('popup/missed-order-model.php');?>
         <!-- Missed order sub - Add new product Model -->
        <?php include('include/addproductmodel.php');?>
        <!-- Save & Return Model -->
        <?php include('popup/save-and-return-model.php'); ?>
        <!-- template Model -->
        <?php include('popup/apply-template-model.php'); ?>
        <!-- Add doctor Model -->
        <?php include('popup/add-doctor-model.php'); ?>
        <!-- last bill Model -->
        <?php include('popup/customer-last-bill-model.php'); ?>
        
        <!-- Area Model -->
        <?php include "include/addarea-model.php";?>
        
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  
<!-- -------------------------------------------HIDDEN TR START----------------------------------------------------- -->
  <div id="hiddenItemHtml" style="display: none;">
    <table>
      <tr>
        <td>##SRNO##</td>
        <td>
          <input type="text" name="product[]" class="form-control product" placeholder="Product" required>
          <small class="producterror text-danger"></small>
          <input type="hidden" class="product_id" name="product_id[]">
        </td>
        <td>
          <input type="text" name="mfg_co[]" class="form-control mfg" placeholder="MFG. Co.">
        </td>
        <td>
          <input type="text" name="batch[]" class="form-control batch" placeholder="Batch">
        </td>
        <td>
            <input type="text" name="expiry[]" class="form-control expiry datepicker" placeholder="Expiry">
            <small class="expired text-danger"></small>
        </td>
        <td>
          <input type="text" name="mrp[]" class="form-control mrp onlynumber" placeholder="MRP" readonly>
        </td>
        <td>
          <input type="text" name="qty[]" class="form-control qty onlynumber" placeholder="Qty." required>
          <input type="hidden" name="qty_ratio[]" class="qty_ratio">
          <input type="hidden" name="rate[]" class="rate">
          <input type="hidden" name="current_qty[]" class="current_qty">
          <small class="qty_error text-danger"></small>
        </td>
        <td>
          <input type="text" name="discount[]" class="form-control discount onlynumber" placeholder="Discount(RS)">
        </td>
        <td>
          <input type="text" name="gst[]" class="form-control gst onlynumber" placeholder="GST(%)" readonly>
          <input type="hidden" name="igst[]" class="igst" value="0">
          <input type="hidden" name="cgst[]" class="cgst" value="0">
          <input type="hidden" name="sgst[]" class="sgst" value="0">
        </td>
        <td>
          <input type="text" name="gst_tax[]" class="form-control gst_tax onlynumber" placeholder="GST" readonly>
        </td>
        <td>
          <input type="text" name="totalamount[]" class="form-control totalamount onlynumber" placeholder="0.00" readonly>
        </td>
        <td>
          <button type="button" class="btn btn-primary btn-xs pt-2 pb-2 btn-add-more-item"><i class="fa fa-plus mr-0 ml-0"></i></button>
          <button type="button" class="btn btn-danger btn-xs pt-2 pb-2 btn-remove-item" style=""><i class="fa fa-close mr-0 ml-0"></i></button>
        </td>
    </tr>
    </table>
  </div>
<!-- -------------------------------------------HIDDEN TR END----------------------------------------------------- -->
  
<!-- HIDDEN RETURN PRODUCT HTML START -->
<div id="hiddenReturnItemHtml" style="display: none;">
  <table>
    <tr>
      <td>##SRNO##</td>
      <td>
        <input type="text" name="r_product[]" class="form-control r_product" placeholder="Product" readonly>
        <small class="producterror text-danger"></small>
        <input type="hidden" class="r_product_id" name="r_product_id[]"></td>
      <td><input type="text" name="r_mfg_co[]" class="form-control r_mfg_co" placeholder="MFG. Co." readonly></td>
      <td><input type="text" name="r_batch[]" class="form-control r_batch" placeholder="Batch" readonly></td>
      <td><input type="text" name="r_expiry[]" class="form-control r_expiry" placeholder="Expiry" readonly></td>
      <td>
        <input type="text" name="r_qty[]" class="form-control r_qty onlynumber" placeholder="Qty." readonly>
      </td>
      <td><input type="text" name="r_rate[]" class="form-control r_rate onlynumber" placeholder="Rate" readonly></td>
      <td><input type="text" name="r_discount[]" class="form-control r_discount onlynumber" placeholder="Discount(RS)" readonly></td>
      <td>
        <input type="text" name="r_gst[]" class="form-control r_gst onlynumber" placeholder="GST(%)" readonly>
        <input type="hidden" name="r_igst[]" class="r_igst" value="0">
        <input type="hidden" name="r_cgst[]" class="r_cgst" value="0">
        <input type="hidden" name="r_sgst[]" class="r_sgst" value="0">
      </td>
      <td><input type="text" name="r_amount[]" class="form-control r_amount onlynumber" placeholder="0.00" readonly></td>
      <td>
        <button type="button" class="btn btn-danger btn-xs pt-2 pb-2 btn-remove-item" style=""><i class="fa fa-close mr-0 ml-0"></i></button>
      </td>
    </tr>
  </table>
</div>


  <!-- plugins:js -->
  <script src="vendors/js/vendor.bundle.base.js"></script>
  <script src="vendors/js/vendor.bundle.addons.js"></script>
  <!-- endinject -->
  <!-- inject:js -->
  <script src="js/off-canvas.js"></script>
  <script src="js/hoverable-collapse.js"></script>
  <script src="js/misc.js"></script>
  <script src="js/settings.js"></script>
  <script src="js/todolist.js"></script>
  <!-- endinject -->
  <!-- Custom js for this page-->
  <script src="js/file-upload.js"></script>
  <script src="js/iCheck.js"></script>
  <script src="js/typeahead.js"></script>
  <script src="js/select2.js"></script>
  
  <!-- Custom js for this page-->
  <script src="js/formpickers.js"></script>
  <script src="js/messagebox.js"></script>
  <script src="js/form-addons.js"></script>
  <script src="js/x-editable.js"></script>
  <script src="js/dropify.js"></script>
  <script src="js/dropzone.js"></script>
  <script src="js/jquery-file-upload.js"></script>
  <script src="js/formpickers.js"></script>
  <script src="js/form-repeater.js"></script>
  
  <!-- Custom js for this page Modal Box-->
  <script src="js/modal-demo.js"></script>
  
  
  <!-- Datepicker Initialise-->
 <script>
    $(document).on('focus',".datepicker", function(){ //bind to all instances of class "date". 
      $(this).datepicker({
        enableOnReadonly: true,
        todayHighlight: true,
        format: 'dd/mm/yyyy',
        autoclose : true
      });
      $(this).datepicker("refresh");
  });
 </script>
 
 
 </script>
<script src="js/jquery-ui.js"></script>


<script src="js/custom/sales-tax-billing-test.js"></script>
<script src="js/custom/add-customer-popup.js"></script>
  <script src="js/custom/product-gst-change.js"></script>

  <!--    Toast Notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>
  
<script src="js/custom/onlynumber.js"></script>
<!-- <script src="js/custom/onlyalphabet.js"></script> -->

<script src="js/parsley.min.js"></script>
<script type="text/javascript">
  $('form').parsley();
</script>
  
  <!-- End custom js for this page-->
</body>


</html>