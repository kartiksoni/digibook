<?php $title="Tax Billing"; 
 include('include/usertypecheck.php');
 include('include/permission.php');
 //include('include/config-ihis.php');
  $owner_id = (isset($_SESSION['auth']['owner_id'])) ? $_SESSION['auth']['owner_id'] : '';
  $admin_id = (isset($_SESSION['auth']['admin_id'])) ? $_SESSION['auth']['admin_id'] : '';
  $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
  $financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';
?>
<!-------------------------------------------- CODE FOR ADD AND UPDATE TAX BILLING START ----------------------------------------------->
<?php

  if(isset($_POST['save']) || isset($_POST['saveAndNext']) || isset($_POST['saveAndPrint'])){
    $tax_billing['invoice_date'] = (isset($_POST['invoice_date']) && $_POST['invoice_date'] != '') ? date('Y-m-d',strtotime(str_replace('/','-',$_POST['invoice_date']))) : NULL;
    if(isset($_GET['id']) && $_GET['id'] != '' ){
        $tax_billing['invoice_no'] = (isset($_POST['invoice_no'])) ? $_POST['invoice_no'] : '';
    }else{
        $tax_billing['invoice_no'] = getInvoiceNo((isset($_POST['bill_type'])) ? $_POST['bill_type'] : '');
    }
    $tax_billing['city_id'] = (isset($_POST['customer_city'])) ? $_POST['customer_city'] : NULL;
    $tax_billing['statecode'] = (isset($_POST['statecode'])) ? $_POST['statecode'] : NULL;
    
    // $tax_billing['customer_id'] = findCustomer($_POST);
    $tax_billing['customer_name'] = (isset($_POST['customer_name'])) ? $_POST['customer_name'] : '';
    $tax_billing['customer_mobile'] = (isset($_POST['customer_mobile'])) ? $_POST['customer_mobile'] : '';
    $tax_billing['customer_id'] = (isset($_POST['customer_id'])) ? $_POST['customer_id'] : '';
    $tax_billing['is_general_sale'] = (isset($_POST['customer_id']) && $_POST['customer_id'] != '' && $_POST['customer_id'] != '0') ? 0 : 1;
    
    $tax_billing['lr_no'] = (isset($_POST['lr_no'])) ? $_POST['lr_no'] : '';
    $tax_billing['lr_date'] = (isset($_POST['lr_date']) && $_POST['lr_date'] != '') ? date('Y-m-d',strtotime(str_replace('/','-',$_POST['lr_date']))) : '';
    $tax_billing['transporter_id'] = (isset($_POST['transporter_id'])) ? $_POST['transporter_id'] : '';
    $tax_billing['motor_vehicle_no'] = (isset($_POST['motor_vehicle_no'])) ? $_POST['motor_vehicle_no'] : '';
    $tax_billing['buyer_order_no'] = (isset($_POST['buyer_order_no'])) ? $_POST['buyer_order_no'] : '';
    $tax_billing['buyer_date'] = (isset($_POST['buyer_date']) && $_POST['buyer_date'] != '') ? date('Y-m-d',strtotime(str_replace('/','-',$_POST['buyer_date']))) : '';
    
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
    $tax_billing['remarks'] = (isset($_POST['remarks'])) ? $_POST['remarks'] : '';
    
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
    $tax_billing['salesman_id'] = (isset($_POST['salesman_id'])) ? $_POST['salesman_id'] : NULL;

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
              //updateIhisPrecriptionBillFlag($_GET['patient'], $_GET['group'], $_GET['type']);
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
            $tax_billing_details['expiry'] = (isset($_POST['expiry'][$i]) && $_POST['expiry'][$i] != '') ? date('Y-m-d',strtotime(str_replace('/','-',$_POST['expiry'][$i]))) : NULL;
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
            
            $item = "INSERT INTO tax_billing_details SET ";
            foreach ($tax_billing_details as $k => $v) {
                $item .= " ".$k." = '".$v."', ";
            }
            $item .= "created = '".date('Y-m-d H:i:s')."', createdby = '".$_SESSION['auth']['id']."'";
            mysqli_query($conn, $item);
          }
        }
        /*-----------------------------------SAVE TAX BILLING ITEMS END----------------------------------------------*/
      }

      if(isset($_GET['id']) && $_GET['id'] != ''){
        $_SESSION['msg']['success'] = "Tax Bill Updated Successfully.";
      }else{
           $id = $taxbillid ;
           $sendmail = send_tax_mail($id);
        //   print_r($sendmail); exit;
           
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
      $editQ = "SELECT tb.*, lg.name as customer, lg.email as customer_email, lg.city as customer_city, lg.rate_id  FROM tax_billing tb LEFT JOIN ledger_master lg ON tb.customer_id = lg.id WHERE tb.id = '".$_GET['id']."' AND tb.pharmacy_id = '".$pharmacy_id."' AND tb.financial_id = '".$financial_id."'";
      $editR = mysqli_query($conn, $editQ);
      if($editR && mysqli_num_rows($editR) > 0){
        $tmpeditdata = mysqli_fetch_assoc($editR);
        if(isset($tmpeditdata['is_general_sale']) && $tmpeditdata['is_general_sale'] == 1){
            $tmpeditdata['customer_city'] = (isset($tmpeditdata['city_id'])) ? $tmpeditdata['city_id'] : '';
            $tmpeditdata['customer'] = (isset($tmpeditdata['customer_name'])) ? $tmpeditdata['customer_name'] : '';
        }
        $editdata = $tmpeditdata;
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

<!-------------------------------------------- CODE FOR GET CHALLAN DATA START ----------------------------------------------->
<?php 
    if(isset($_GET['challan']) && $_GET['challan'] != ''){
      $editQ = "SELECT dc.*, lg.name as customer_name,lg.mobile as customer_mobile, lg.email as customer_email, lg.city as customer_city, lg.rate_id  FROM delivery_challan dc LEFT JOIN ledger_master lg ON dc.customer_id = lg.id WHERE dc.id = '".$_GET['challan']."' AND dc.pharmacy_id = '".$pharmacy_id."' AND dc.financial_id = '".$financial_id."'";
      $editR = mysqli_query($conn, $editQ);
      if($editR && mysqli_num_rows($editR) > 0){
        $tmpeditdata = mysqli_fetch_assoc($editR);
        $tmpeditdata['invoice_no'] = getInvoiceNo((isset($tmpeditdata['bill_type'])) ? $tmpeditdata['bill_type'] : '');
        $editdata = $tmpeditdata;
        $challan_item_Q = "SELECT dcd.*, pm.product_name FROM delivery_challan_details dcd LEFT JOIN product_master pm ON dcd.product_id = pm.id WHERE dcd.challan_id = '".$editdata['id']."'";
        $challan_item_R = mysqli_query($conn, $challan_item_Q);
        if($challan_item_R && mysqli_num_rows($challan_item_R) > 0){
            while($challan_item_Row = mysqli_fetch_assoc($challan_item_R)){
                $productData = getAllProductWithCurrentStock('','',0,[$challan_item_Row['product_id']]);
                $challan_item_Row['current_qty'] = (isset($productData[0]['currentstock']) && $productData[0]['currentstock'] != '') ? $productData[0]['currentstock'] : 0;
                $editdata['detail'][] = $challan_item_Row;
            }
        }
        
      }
    }
?>
<!-------------------------------------------- CODE FOR GET CHALLAN DATA END ----------------------------------------------->

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

<!--CODE FOR GET PHARMACY DETAIL START-->
<?php
    $getPharmcyQ = "SELECT id, firm_name FROM pharmacy_profile WHERE id = '".$pharmacy_id."' LIMIT 1";
    $getPharmcyR = mysqli_query($conn, $getPharmcyQ);
    if($getPharmcyR && mysqli_num_rows($getPharmcyR) > 0){
        $getPharmcyRow = mysqli_fetch_assoc($getPharmcyR);
        $pharmacy_firm = (isset($getPharmcyRow['firm_name'])) ? $getPharmcyRow['firm_name'] : '';
    }
?>
<!--CODE FOR GET PHARMACY DETAIL END-->
<?php 
    /*------------GET ALL TRANSPORT NAME START-------------*/
    $allTransport = [];
    $getTransportQ = "SELECT id, name, t_code FROM transport_master WHERE pharmacy_id = '".$pharmacy_id."' AND status = 1 ORDER BY name";
    $getTransportR = mysqli_query($conn, $getTransportQ);
    if($getTransportR && mysqli_num_rows($getTransportR) > 0){
        while($getTransportRow = mysqli_fetch_assoc($getTransportR)){
            $allTransport[] = $getTransportRow;
        }
    }
    /*------------GET ALL TRANSPORT NAME START-------------*/
    
    function GetCityID(){
        global $conn;
        global $pharmacy_id;
        $data = [];
        
        $q = "SELECT city FROM ledger_master WHERE group_id = 10 AND pharmacy_id = '".$pharmacy_id."' GROUP BY city";
        $r = mysqli_query($conn, $q);
        if($r && mysqli_num_rows($r) > 0){
            while($row = mysqli_fetch_assoc($r)){
                if(isset($row['city']) && $row['city'] != '' && $row['city'] != 0){
                    $data[] = $row['city']; 
                }
            }
        }
        
        $q1 = "SELECT city_id FROM tax_billing WHERE pharmacy_id = '".$pharmacy_id."' GROUP BY city_id";
        $r1 = mysqli_query($conn, $q1);
        if($r1 && mysqli_num_rows($r1) > 0){
            while($row1 = mysqli_fetch_assoc($r1)){
                if(isset($row1['city_id']) && $row1['city_id'] != '' && $row1['city_id'] != 0){
                    $data[] = $row1['city_id']; 
                }
            }
        }
        
        return array_unique($data);
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Sale Tax Bill - DigiBooks</title>
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
           <!-- Form -->
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <!-- Main Catagory -->
                    <div class="row">
                      <div class="col-12">
                          <div class="purchase-top-btns">
                            <?php if((isset($user_sub_module) && in_array("Tax Billing", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                                <a href="sales-tax-billing.php" class="btn btn-dark active">Sales</a>
                            <?php } if((isset($user_sub_module) && in_array("View Sales Bill", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                                <a href="view-sales-tax-billing.php" class="btn btn-dark active">View Sales Bill</a>
                            <?php } if((isset($user_sub_module) && in_array("Sales Return", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                                <a href="sales-return.php" class="btn btn-dark">Sales Return</a>
                            <?php } if((isset($user_sub_module) && in_array("Sales Return List", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                              <a href="view-sales-return.php" class="btn btn-dark">Sales Return List</a>
                            <?php } if((isset($user_sub_module) && in_array("Cancellation List", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                              <a href="sales-cancellation-list.php" class="btn btn-dark">Cancellation List</a>
                            <?php } if((isset($user_sub_module) && in_array("Order", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                              <a href="#" class="btn btn-dark dropdown-toggle" id="dropdownMenuButton4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Order</a>
                                  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton4">
                                    <a class="dropdown-item" href="sales-order.php">Order/Estimate/Templates</a>
                                    <a class="dropdown-item" href="sales-order-history.php">History</a>
                                  </div>
                            <?php } if((isset($user_sub_module) && in_array("Sales History", $user_sub_module)) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                              <a href="sales-history.php" class="btn btn-dark">History</a>
                            <?php } ?>
                          </div>   
                      </div> 
                    </div>
                </div>
              </div>
            </div>
            <!-- Form -->
            <form method="POST" autocomplete="off">
              <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <br>
                      <div class="form-group row">

                        <div class="col-12 col-md-2">
                          <label for="invoice_date">Bill Date <span class="text-danger">*</span></label>
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
                          <label for="invoice_no">Bill No <span class="text-danger">*</span></label>
                          <input type="text" class="form-control" <?php if(isset($_GET['id'])){echo"readonly";} ?> name="invoice_no" id="invoice_no" placeholder="Invoice No" value="<?php echo (isset($editdata['invoice_no'])) ? $editdata['invoice_no'] : getInvoiceNo('Debit'); ?>" required>
                        </div>

                        <div class="col-12 col-md-2">
                          <label for="customer_city">Customer City</label>
                          <select class="js-example-basic-single" style="width:100%" name="customer_city" id="customer_city">
                            <option value="">Select City</option>
                            <?php
                                $citiides = GetCityID();
                                $getCityQ = "SELECT ct.id, ct.name, st.state_code_gst as statecode FROM own_cities ct INNER JOIN own_states st ON ct.state_id = st.id WHERE ct.id IN(".implode(',',$citiides).")";
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
                        
                        <div class="col-12 col-md-2 customer-name-div">
                          <label for="customer_id">Customer Name <span class="text-danger">*</span></label>
                          <input class="form-control" data-name="name" autocomplete="off" Role="presentation" type="text" value="<?php echo (isset($editdata['customer'])) ? $editdata['customer'] : '' ?>" name="customer_name" id="customer_name" required data-parsley-errors-container="#error-customer_id">
                          <small class="customererror text-danger" style="display:none;"></small>
                          <span id="error-customer_id"></span>
                          <input type="hidden" name="statecode" id="statecode" value="<?php echo (isset($editdata['statecode'])) ? $editdata['statecode'] : ''; ?>">
                          <input type="hidden" name="cur_statecode" id="cur_statecode" value="<?php echo (isset($_SESSION['state_code'])) ? $_SESSION['state_code'] : ''; ?>" >
                          <input type="hidden" name="customer_id" id="customer_id" value="<?php echo (isset($editdata['customer_id'])) ? $editdata['customer_id'] : ''; ?>" >
                          <input type="hidden" name="salesman_id" id="salesman_id" value="<?php echo (isset($editdata['salesman_id'])) ? $editdata['salesman_id'] : ''; ?>" >
                          <input type="hidden" name="rate_id" id="rate_id" value="<?php echo (isset($editdata['rate_id'])) ? $editdata['rate_id'] : ''; ?>" >
                          <input type="hidden" name="customer_mobile" id="customer_mobile" value="<?php echo (isset($editdata['customer_mobile'])) ? $editdata['customer_mobile'] : ''; ?>">
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
                          <a href="javascript:void(0);" class="btn btn-primary btn-xs mt-30" data-toggle="modal" data-target="#add_customer_model" data-whatever="@mdo">Add Customer</a>
                        </div>
                        
                      </div>
                        <div class="form-group row">
                            <div class="col-12 col-md-2">
                                <label for="lr_no">LR No.</label>
                                <input type="text" class="form-control" name="lr_no" id="lr_no" placeholder="LR No." value="<?php echo (isset($editdata['lr_no'])) ? $editdata['lr_no'] : ''; ?>">
                            </div>
                            <div class="col-12 col-md-2">
                                <label for="lr_date">LR Date</label>
                                <input type="text" class="form-control datepicker" name="lr_date" id="lr_date" placeholder="LR Date" value="<?php echo (isset($editdata['lr_date']) && $editdata['lr_date'] != '' && $editdata['lr_date'] != '0000-00-00') ? date('d/m/Y',strtotime($editdata['lr_date'])) : date('d/m/Y'); ?>">
                            </div>
                            <div class="col-12 col-md-2">
                                <label for="transporter_id">Transporter Name</label>
                                <select class="js-example-basic-single" style="width:100%" name="transporter_id" id="transporter_id"> 
                                    <option value="">Select Transport</option>
                                    <?php if(isset($allTransport) && !empty($allTransport)){ ?>
                                        <?php foreach($allTransport as $key => $value){ ?>
                                            <option value="<?php echo $value['id']; ?>" <?php echo (isset($editdata['transporter_id']) && $editdata['transporter_id'] == $value['id']) ? 'selected' : ''; ?> ><?php echo $value['name']; ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-12 col-md-2">
                                <label for="motor_vehicle_no">Motor/Vehicle No.</label>
                                <input type="text" class="form-control" name="motor_vehicle_no" id="motor_vehicle_no" placeholder="Motor/Vehicle No." value="<?php echo (isset($editdata['motor_vehicle_no'])) ? $editdata['motor_vehicle_no'] : ''; ?>">
                            </div>
                            <div class="col-12 col-md-2">
                                <label for="buyer_order_no">Buyer Order No.</label>
                                <input type="text" class="form-control" name="buyer_order_no" id="buyer_order_no" placeholder="Buyer Order No." value="<?php echo (isset($editdata['buyer_order_no'])) ? $editdata['buyer_order_no'] : ''; ?>">
                            </div>
                            <div class="col-12 col-md-2">
                                <label for="buyer_date">Buyer Date</label>
                                <input type="text" class="form-control datepicker" name="buyer_date" id="buyer_date" placeholder="Buyer Date" value="<?php echo (isset($editdata['buyer_date']) && $editdata['buyer_date'] != '' && $editdata['buyer_date'] != '0000-00-00') ? date('d/m/Y',strtotime($editdata['buyer_date'])) : date('d/m/Y'); ?>">
                            </div>
                        </div>
                  </div>
                </div>
              </div>
              <!-- Table -------------->
              <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <div class="col">
                      <div class="row">
                        <div class="col-12">
                            <label class="pull-right sale-rate-lable display-none"></label>
                        </div>
                        <div class="col-12">
                          <table class="table">
                            <thead>
                              <tr>
                                  <th width="5%">Sr No</th>
                                  <th width="20%">Product</th>
                                  <th>MRP</th>
                                  <th>MFG. Co.</th>
                                  <th>Batch</th>
                                  <th>Qty.</th>
                                  <th>Free Qty</th>
                                  <th>Rate</th>
                                  <th>Amount</th>
                                  <th>&nbsp;</th>
                              </tr>
                            </thead>
                            <tbody id="item-tbody">
                              <!-- Row Starts -->
                              <?php if(isset($editdata['detail']) && !empty($editdata['detail'])){ ?>
                                <?php foreach($editdata['detail'] as $key => $value){?>
                                  <tr data-id="<?php echo $key+1; ?>" id="tr-<?php echo $key+1; ?>">
                                    <td><?php echo $key+1; ?></td>
                                    <td>
                                      <input type="text" name="product[]" class="form-control product" placeholder="Product" value="<?php echo (isset($value['product_name'])) ? $value['product_name'] : ''; ?>" required>
                                      <small class="producterror text-danger"></small>
                                      <input type="hidden" class="product_id" name="product_id[]" value="<?php echo (isset($value['product_id'])) ? $value['product_id'] : ''; ?>">
                                    </td>
                                    <td><input type="text" name="mrp[]" class="form-control mrp onlynumber" placeholder="MRP" value="<?php echo (isset($value['mrp'])) ? $value['mrp'] : ''; ?>"></td>
                                    <td><input type="text" name="mfg_co[]" class="form-control mfg" placeholder="MFG. Co." value="<?php echo (isset($value['mfg_co'])) ? $value['mfg_co'] : ''; ?>"></td>
                                    <td>
                                      <input type="text" name="batch[]" class="form-control batch" placeholder="Batch" value="<?php echo (isset($value['batch'])) ? $value['batch'] : ''; ?>">
                                      <input type="hidden" name="expiry[]" class="expiry" value="<?php echo (isset($value['expiry'])) ? $value['expiry'] : ''; ?>">
                                    </td>
                                    <td>
                                      <input type="text" name="qty[]" class="form-control qty onlynumber" placeholder="Qty." value="<?php echo (isset($value['qty'])) ? $value['qty'] : ''; ?>" required>
                                      <input type="hidden" name="qty_ratio[]" class="qty_ratio" value="0" value="<?php echo (isset($value['qty_ratio'])) ? $value['qty_ratio'] : ''; ?>">
                                      <input type="hidden" name="current_qty[]" class="current_qty" value="<?php echo (isset($value['current_qty'])) ? $value['current_qty'] : ''; ?>">
                                      <small class="qty_error text-danger"></small>
                                    </td>
                                    <td>
                                      <input type="text" name="freeqty[]" class="form-control freeqty onlynumber" placeholder="Free Qty" value="<?php echo (isset($value['freeqty'])) ? $value['freeqty'] : ''; ?>">
                                      <input type="hidden" name="ptr[]" class="form-control ptr" placeholder="PTR" value="<?php echo (isset($value['ptr'])) ? $value['ptr'] : ''; ?>">
                                      <input type="hidden" name="discount[]" class="form-control discount" placeholder="Discount(RS)" value="<?php echo (isset($value['discount'])) ? $value['discount'] : ''; ?>">
                                    </td>
                                    
                                    <td>
                                      <input type="text" name="rate[]" class="form-control rate onlynumber" placeholder="Rate" value="<?php echo (isset($value['rate'])) ? $value['rate'] : ''; ?>" required>
                                        <?php if(isset($pharmacy_firm) && $pharmacy_firm != 'Composition'){ ?>
                                          <input type="hidden" name="gst[]" class="gst" value="<?php echo (isset($value['gst']) && $value['gst'] != '') ? $value['gst'] : 0; ?>">
                                          <input type="hidden" name="igst[]" class="c_igst" value="<?php echo (isset($value['igst']) && $value['igst'] != '') ? $value['igst'] : 0; ?>">
                                          <input type="hidden" name="cgst[]" class="c_cgst" value="<?php echo (isset($value['cgst']) && $value['cgst'] != '') ? $value['cgst'] : 0; ?>">
                                          <input type="hidden" name="sgst[]" class="c_sgst" value="<?php echo (isset($value['sgst']) && $value['sgst'] != '') ? $value['sgst'] : 0; ?>">
                                        <?php } ?>
                                    </td>
                                   
                                    <td>
                                      <input type="text" name="totalamount[]" class="form-control totalamount onlynumber" placeholder="0.00" value="<?php echo (isset($value['totalamount']) && $value['totalamount'] != '') ? $value['totalamount'] : 0; ?>" readonly>
                                    </td>
                                    <td>
                                      <button type="button" class="btn btn-primary btn-xs pt-2 pb-2 btn-add-more-item"><i class="fa fa-plus mr-0 ml-0"></i></button>
                                      <?php if($key != 0){ ?>
                                        <button type="button" class="btn btn-danger btn-xs pt-2 pb-2 btn-remove-item" style=""><i class="fa fa-close mr-0 ml-0"></i></button>
                                      <?php } ?>
                                    </td>
                                  </tr>
                                <?php } ?>
                              <?php }else{ ?>
                                <tr data-id="1" id="tr-1">
                                  <td>1</td>
                                  <td>
                                    <input type="text" name="product[]" class="form-control product" placeholder="Product" required>
                                    <small class="producterror text-danger"></small>
                                    <input type="hidden" class="product_id" name="product_id[]"></td>
                                  <td><input type="text" name="mrp[]" class="form-control mrp onlynumber" placeholder="MRP"></td>
                                  <td><input type="text" name="mfg_co[]" class="form-control mfg" placeholder="MFG. Co."></td>
                                  <td>
                                    <input type="text" name="batch[]" class="form-control batch" placeholder="Batch">
                                    <input type="hidden" name="expiry[]" class="expiry" value="">
                                  </td>
                                  <td>
                                    <input type="text" name="qty[]" class="form-control qty onlynumber" placeholder="Qty." required>
                                    <input type="hidden" name="qty_ratio[]" class="qty_ratio" value="0">
                                    <input type="hidden" name="current_qty[]" class="current_qty">
                                    <small class="qty_error text-danger"></small>
                                  </td>
                                  <td>
                                    <input type="text" name="freeqty[]" class="form-control freeqty onlynumber" placeholder="Free Qty">
                                    <input type="hidden" name="ptr[]" class="form-control ptr" placeholder="PTR">
                                    <input type="hidden" name="discount[]" class="form-control discount" placeholder="Discount(RS)">
                                  </td>
                                  
                                  <td>
                                    <input type="text" name="rate[]" class="form-control rate onlynumber" placeholder="Rate" required>
                                    <?php if(isset($pharmacy_firm) && $pharmacy_firm != 'Composition'){ ?>
                                        <input type="hidden" name="gst[]" class="gst" value="0">
                                        <input type="hidden" name="igst[]" class="c_igst" value="0">
                                        <input type="hidden" name="cgst[]" class="c_cgst" value="0">
                                        <input type="hidden" name="sgst[]" class="c_sgst" value="0">
                                    <?php } ?>
                                  </td>
                                 
                                  <td><input type="text" name="totalamount[]" class="form-control totalamount onlynumber" placeholder="0.00" readonly></td>
                                  <td>
                                    <button type="button" class="btn btn-primary btn-xs pt-2 pb-2 btn-add-more-item"><i class="fa fa-plus mr-0 ml-0"></i></button>
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
                      <div class="row form-group">
                        <div class="col-md-8">
                            <label for="remarks">Remarks</label>
                            <textarea name="remarks" id="remarks" class="form-control"><?php echo (isset($editdata['remarks'])) ? $editdata['remarks'] : ''; ?></textarea>
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
                                        <select class="form-control" id="couriercharge" name="couriercharge" style="width:160px;">
                                            <option value="">Freight/Courier Charge </option>
                                            <option value="5" <?php echo (isset($editdata['couriercharge']) && $editdata['couriercharge'] == 5) ? 'selected' : ''; ?> >5%</option>
                                            <option value="12" <?php echo (isset($editdata['couriercharge']) && $editdata['couriercharge'] == 12) ? 'selected' : ''; ?> >12%</option>
                                            <option value="18" <?php echo (isset($editdata['couriercharge']) && $editdata['couriercharge'] == 18) ? 'selected' : ''; ?> >18%</option>
                                        </select>
                                    </td>
                                  <td align="right">
                                    <input type="text" name="couriercharge_val" id="courier" class="form-control onlynumber" placeholder="Freight/Courier Charge" value="<?php echo (isset($editdata['couriercharge_val'])) ? $editdata['couriercharge_val'] : ''; ?>" readonly>
                                  </td>
                                </tr>

                                
                                
                                
                                <tr>
                                  <td align="right">
                                   Taxable Value
                                  </td>
                                  <td align="right">
                                    <input type="text" name="overalldiscount" id="overalldiscount" class="form-control onlynumber" placeholder="0.00" value="<?php echo (isset($editdata['overalldiscount'])) ? $editdata['overalldiscount'] : ''; ?>" readonly>
                                  </td>
                                </tr>
                                <?php if(isset($pharmacy_firm) && $pharmacy_firm != 'Composition'){ ?>
                                    <?php 
                                        if((isset($editdata['statecode']) && $editdata['statecode'] != '') && (isset($_SESSION['state_code']) && $_SESSION['state_code'] != '')){
                                            if($editdata['statecode'] == $_SESSION['state_code']){
                                                $igsttr = 'style="display:none;"';
                                            }else{
                                                $cgsttr = 'style="display:none;"';
                                                $sgsttr = 'style="display:none;"';
                                            }
                                        }
                                    ?>
                                    <tr style="display:none;">
                                      <td align="right">
                                        Total Tax (GST)
                                      </td>
                                      <td align="right">
                                        <input type="text" name="totaltaxgst" id="totaltaxgst" class="form-control onlynumber" placeholder="0.00" value="<?php echo (isset($editdata['totaltaxgst'])) ? $editdata['totaltaxgst'] : ''; ?>" readonly>
                                      </td>
                                    </tr>
                                    
                                    
                                    <tr id="igst-tr" <?php echo (isset($igsttr)) ? $igsttr : ''; ?> >
                                      <td align="right">
                                        IGST
                                      </td>
                                      <td align="right">
                                        <input type="text" name="totaligst" id="totaligst" class="form-control onlynumber" placeholder="0.00" value="<?php echo (isset($editdata['totaligst'])) ? $editdata['totaligst'] : ''; ?>" readonly>
                                      </td>
                                    </tr>
                                    
                                    <tr id="cgst-tr" <?php echo (isset($cgsttr)) ? $cgsttr : ''; ?>>
                                      <td align="right">
                                        CGST
                                      </td>
                                      <td align="right">
                                        <input type="text" name="totalcgst" id="totalcgst" class="form-control onlynumber" placeholder="0.00" value="<?php echo (isset($editdata['totalcgst'])) ? $editdata['totalcgst'] : ''; ?>" readonly>
                                      </td>
                                    </tr>
                                    
                                    <tr id="sgst-tr" <?php echo (isset($sgsttr)) ? $sgsttr : ''; ?> >
                                      <td align="right">
                                        SGST
                                      </td>
                                      <td align="right">
                                        <input type="text" name="totalsgst" id="totalsgst" class="form-control onlynumber" placeholder="0.00" value="<?php echo (isset($editdata['totalsgst'])) ? $editdata['totalsgst'] : ''; ?>" readonly>
                                      </td>
                                    </tr>
                                <?php } ?>
                                
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
                          <a href="view-sales-tax-billing.php" class="btn btn-light pull-left">Back</a>
                          <button type="submit" name="save" id="btn-save" class="btn btn-success mr-2 pull-right">Save</button>
                          <button type="submit" name="saveAndNext" id="btn-saveAndNext" class="btn btn-success mr-2 pull-right">Save & Next</button>
                          <button type="submit" name="saveAndPrint" id="btn-saveAndPrint" class="btn btn-success mr-2 pull-right">Save & Print</button>
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
        <!-- last bill Model -->
        <?php include('popup/customer-last-bill-model.php'); ?>
        <!-- PTR RATE Model -->
        <?php include('popup/ptr-discount-model.php'); ?>
        <!--GENERAL CUSTOMER MODEL-->
        <?php include('popup/general-customer-model.php'); ?>
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
      <tr data-id="##SRNO##" id="tr-##SRNO##">
        <td>##SRNO##</td>
        <td>
          <input type="text" name="product[]" class="form-control product" placeholder="Product" required>
          <small class="producterror text-danger"></small>
          <input type="hidden" class="product_id" name="product_id[]"></td>
        <td><input type="text" name="mrp[]" class="form-control mrp onlynumber" placeholder="MRP"></td>
        <td><input type="text" name="mfg_co[]" class="form-control mfg" placeholder="MFG. Co."></td>
        <td>
          <input type="text" name="batch[]" class="form-control batch" placeholder="Batch">
          <input type="hidden" name="expiry[]" class="expiry" value="">
        </td>
        <td>
          <input type="text" name="qty[]" class="form-control qty onlynumber" placeholder="Qty." required>
          <input type="hidden" name="qty_ratio[]" class="qty_ratio" value="0">
          <input type="hidden" name="current_qty[]" class="current_qty">
          <small class="qty_error text-danger"></small>
        </td>
        <td>
          <input type="text" name="freeqty[]" class="form-control freeqty onlynumber" placeholder="Free Qty">
          <input type="hidden" name="ptr[]" class="form-control ptr" placeholder="PTR">
          <input type="hidden" name="discount[]" class="form-control discount" placeholder="Discount(RS)">
        </td>
        
        <td>
          <input type="text" name="rate[]" class="form-control rate onlynumber" placeholder="Rate" required>
            <?php if(isset($pharmacy_firm) && $pharmacy_firm != 'Composition'){ ?>
              <input type="hidden" name="gst[]" class="gst" value="0">
              <input type="hidden" name="igst[]" class="c_igst" value="0">
              <input type="hidden" name="cgst[]" class="c_cgst" value="0">
              <input type="hidden" name="sgst[]" class="c_sgst" value="0">
            <?php } ?>
        </td>
       
        <td><input type="text" name="totalamount[]" class="form-control totalamount onlynumber" placeholder="0.00" readonly></td>
        <td>
          <button type="button" class="btn btn-primary btn-xs pt-2 pb-2 btn-add-more-item"><i class="fa fa-plus mr-0 ml-0"></i></button>
          <button type="button" class="btn btn-danger btn-xs pt-2 pb-2 btn-remove-item" style=""><i class="fa fa-close mr-0 ml-0"></i></button>
        </td>
      </tr>
    </table>
  </div>
<!-- -------------------------------------------HIDDEN TR END----------------------------------------------------- -->


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
  
  
  <!-- toast notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>
  
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
  
  $(".customer_type").change(function(){
	    var customer_type = $(this).val();
	    $(".gstno").removeAttr('readonly');
	    $(".gst_error").show();
	    $(".gstno").prop('required',true);
	    if(customer_type == "GST_unregistered"){
	        $(".gst_error").hide();
	        $(".gstno").attr('readonly', true);
	        $(".gstno").removeAttr('required');
	    }
	    if(customer_type == "Consumer"){
	        $(".gst_error").hide();
	        $(".gstno").attr('readonly', true);
	        $(".gstno").removeAttr('required');
	    }
	    if(customer_type == "Overseas"){
	        $(".gst_error").hide();
	        $(".gstno").attr('readonly', true);
	        $(".gstno").removeAttr('required');
	    }
	}); 
	
	$('body').on('change keyup past', '#gst_no ', function () {
      var gstno = $(this).val();
   var pan = gstno.substring(2,12);
   $('#pan_num').val(pan);
   
  });
  
  $("#gst_no").keyup(function(){
    var gst_value = $(this).val();
    if(gst_value != ''){
        if (gst_value.match(/^([0-9]{2}[a-zA-Z]{4}([a-zA-Z]{1}|[0-9]{1})[0-9]{4}[a-zA-Z]{1}([a-zA-Z]|[0-9]){3}){0,15}$/)) {
            $("#gst_no").removeClass("parsley-error");
            $("#gst_no").addClass("parsley-success");
        }else{
            $("#gst_no").addClass("parsley-error");  
        }
    }else{
        $("#gst_no").addClass("parsley-error");
    }
    
});

 </script>
<script src="js/jquery-ui.js"></script>


<script src="js/custom/sales-tax-billing.js"></script>
<script src="js/custom/add-customer-popup.js"></script>
<script src="js/custom/onlynumber.js"></script>
<!-- <script src="js/custom/onlyalphabet.js"></script> -->

<script src="js/parsley.min.js"></script>
<script type="text/javascript">
  $('form').parsley();
</script>
  
  <!-- End custom js for this page-->
</body>


</html>
