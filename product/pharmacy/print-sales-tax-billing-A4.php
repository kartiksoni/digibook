<?php include('include/usertypecheck.php'); ?>
<?php 
if(isset($_GET['id']) && $_GET['id'] != ''){
  $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';

  $query = "SELECT tb.*, lg.name as customer_name, lg.addressline1, lg.addressline2, lg.addressline3, lg.gstno,lg.panno,lg.mobile,lg.pincode,lg.email, st.name as customer_state, st.state_code_gst as statecode, ct.name as customer_city,dp.name as doctor_name FROM tax_billing tb LEFT JOIN ledger_master lg ON tb.customer_id = lg.id LEFT JOIN own_states st ON lg.state = st.id LEFT JOIN own_cities ct ON lg.city = ct.id LEFT JOIN doctor_profile dp ON tb.doctor = dp.id WHERE tb.pharmacy_id ='".$pharmacy_id."' AND tb.id='".$_GET['id']."'";
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
   $tr = "select finalamount from sale_return where customer_id = '".$data['customer_id']."'";  
   $trs = mysqli_query($conn, $tr);
   if($trs && mysqli_num_rows($trs) > 0){
       $taxreturnRow = mysqli_fetch_assoc($trs);
    $data['taxreturnAmount'] = $taxreturnRow['finalamount'];
   }
   
  
   
           
           
    /*----------------------------------------------PHARMACY QUERY START-------------------------------------------*/
    if(isset($data['pharmacy_id']) && $data['pharmacy_id'] != ''){
       $pharmacyQ = "SELECT p.id, p.pharmacy_name, p.address1, p.address2, p.address3,p.pin_code ,p.gst_no,p.company_cin_no,ct.name as city,p.mobile_no,p.email,p.logo_url,st.name as state_name,st.state_code_gst as pharmastatecode FROM pharmacy_profile p LEFT JOIN own_states st ON p.stateId = st.id LEFT JOIN own_cities ct ON p.city_name = ct.id WHERE p.id='".$data['pharmacy_id']."'";
      $pharmacyR = mysqli_query($conn, $pharmacyQ);
      if($pharmacyR && mysqli_num_rows($pharmacyR) > 0){
        $data['pharmacy'] = mysqli_fetch_assoc($pharmacyR);
    //   print_r($data['pharmacy']);
       
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
      $taxBillingDetailQ = "SELECT tbd.* , tb.*, pm.product_name,pm.mrp as product_mrp,pm.bill_print_view ,pm.mfg_company as product_mfg_company,pm.hsn_code as hsn, pm.batch_no as product_batch_no, pm.ex_date as product_ex_date FROM tax_billing_details tbd INNER JOIN product_master pm ON tbd.product_id = pm.id INNER JOIN tax_billing tb ON tbd.tax_bill_id = tb.id WHERE tbd.tax_bill_id = '".$data['id']."'";
      $taxBillingDetailR = mysqli_query($conn, $taxBillingDetailQ);
      if($taxBillingDetailR && mysqli_num_rows($taxBillingDetailR) > 0){
        while ($taxBillingDetailRow = mysqli_fetch_assoc($taxBillingDetailR)) {
          $data['tax_billing_detail'][] = $taxBillingDetailRow;
           
          
        }
      }
    }
    /*----------------------------------------------TAX BILLING DETAIL QUERY END-------------------------------------------*/


    /*------------------------------------------------------GROUP BY HSN CODE WISE AMOUNT START-----------------------------------*/
     if(isset($data['id']) && $data['id'] != ''){
     $hsngroupQ = "SELECT sum(tbd.totalamount) as taxablevalue, tbd.sgst,tbd.cgst ,pm.hsn_code as hsn  FROM tax_billing_details tbd INNER JOIN product_master pm ON tbd.product_id = pm.id INNER JOIN tax_billing tb ON tbd.tax_bill_id = tb.id WHERE tbd.tax_bill_id = '".$data['id']."' group by pm.hsn_code";
      $hsngroupR = mysqli_query($conn, $hsngroupQ);
      if($hsngroupR && mysqli_num_rows($hsngroupR) > 0){
        while ($hsngroupRow = mysqli_fetch_assoc($hsngroupR)) {
          $data['hsn_detail'][] = $hsngroupRow;
        //  echo "<pre>";
        //  print_r($data['hsn_detail']);
          
        }
      }
    }    
    /*------------------------------------------------------GROUP BY HSN CODE WISE AMOUNT END-----------------------------------*/

    if(isset($data['id']) && $data['id'] != ''){
       $freight ="select couriercharge , couriercharge_val from tax_billing where id= '".$data['id']."'";
       $freightR = mysqli_query($conn, $freight);
       if($freightR && mysqli_num_rows($freightR) > 0){
       $freightL = mysqli_fetch_assoc($freightR);
       }
        // print_r($freightL);
        
    }


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
}

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">

  <title> TAX Invoice </title>  
  <link rel="shortcut icon" href="assets/images/favicon.png">          
  <link rel="stylesheet" href="css/billA4.css">
  <style type="text/css">
  .sub{
    /*width:122px;*/
    float:left;
        line-height: 13px;
    font-size: 13px
  }
  .sub1{
    /*width:122px;*/
    /*float:left;*/
    line-height: 17px;
    font-size: 13px;
  }
  .detail {
    width:150px;
    float:left;
    line-height: 11px;
    font-size: 11px;
  }
  @media print {
    @page { margin: 0; }
    body { margin: 0.5cm;font-family:Verdana, Arial, Helvetica, sans-serif; }
  }
  @page { margin: 0; }
  @page {
   size: 7in 9.25in;
   margin: 25mm 16mm 27mm 16mm;
 }
 @page { margin: 0; }
 @page { size: auto;  margin: 0mm; }

</style>

</head>
<body>


  <page size="A4-HALF"> 
    <div class="invoice-box">

      <div class="company-title" style="margin-bottom: 10px;margin-top: 11px;">TAX INVOICE</div>

      <table cellpadding="0" border="1"  cellspacing="0">
       <tr>
        <td  style="vertical-align: top; padding: 0;" colspan="2">
          <table  cellpadding="2" cellspacing="0" border="1" style="line-height:10px;font-size:9px;border-bottom: 0;border-top: 0;border-left: 0;border-right: 0;">
            <tr>     
              <td>
                <div style="width:100px;float:left;margin-right: 12px;height: 100px;    margin-bottom: 13px;">
                    <?php $url_img = "http://digibooks.cloud/product/pharmacy/logo_images/".$data['pharmacy']['logo_url'];
                     ?>
                  <img src="<?php echo $url_img?>" style="height: 100px;width: 100px;">
                </div>
                <div>
                  <span style="font-size: 13px;line-height: 16px;"><b><?php echo (isset($data['pharmacy']['pharmacy_name'])) ? $data['pharmacy']['pharmacy_name'] : ''; ?> </b></span><br>
                  
                  <?php 
                                        $customerAddArrs = [];
                                        if(isset($data['pharmacy']['address1']) && $data['pharmacy']['address1'] != ''){
                                            $customerAddArrs[] = $data['pharmacy']['address1'];
                                        }
                                        if(isset($data['pharmacy']['address2']) && $data['pharmacy']['address2'] != ''){
                                            $customerAddArrs[] = $data['pharmacy']['address2'];
                                        }
                                        if(isset($data['pharmacy']['address3']) && $data['pharmacy']['address3'] != ''){
                                            $customerAddArrs[] = $data['pharmacy']['address3'];
                                        }
                                        if(!empty($customerAddArrs)){
                                            $customersAddress = implode(', ', $customerAddArrs);
                                        }
                                    ?>
                  <span style="font-size: 13px;line-height: 15px;"><?php echo (isset($customersAddress)) ? $customersAddress : ''; ?> .<?php echo (isset($data['pharmacy']['city'])) ? $data['pharmacy']['city'] : ''; ?> - <?php echo (isset($data['pharmacy']['pin_code'])) ? $data['pharmacy']['pin_code'] : '';?> </span><br>
                  <!--<span style="font-size: 14px;line-height: 16px;"> Invoice date : <?php //echo (isset($data['invoice_date']) && $data['invoice_date'] != '') ? date('d-M-Y',strtotime($data['invoice_date'])) : ''; ?> </span><br>-->
                  <!--<span style="font-size: 14px;line-height: 16px;"> City : <?php //echo (isset($data['pharmacy']['city'])) ? $data['pharmacy']['city'] : ''; ?> </span><br>-->
                  <span style="font-size: 13px;line-height: 15px;"> GSTIN/UIN : <?php echo (isset($data['pharmacy']['gst_no'])) ? $data['pharmacy']['gst_no'] : ''; ?> </span> <br>
                  <span style="font-size: 13px;line-height: 15px;"> State Name: <?php echo (isset($data['pharmacy']['state_name'])) ? $data['pharmacy']['state_name'] : ''; ?> , Code : <?php echo (isset($data['pharmacy']['pharmastatecode'])) ? $data['pharmacy']['pharmastatecode'] : ''; ?></span> <br>
                  <span style="font-size: 13px;line-height: 15px;"> CIN : <?php echo (isset($data['pharmacy']['company_cin_no'])) ? $data['pharmacy']['company_cin_no'] : ''; ?> </span> <br>
                  <span style="font-size: 13px;line-height: 15px;"> E-Mail : <?php echo (isset($data['pharmacy']['email'])) ? $data['pharmacy']['email'] : ''; ?> </span> <br>  
                  
                    
                  
                </div>
              </td>

            </tr>
            <td>
                <!--<hr>-->
             <table cellpadding="2" cellspacing="0" border="0" style="line-height:10px;font-size:9px;">
              <tr>     
                <td>
                  <div style="font-size: 14px;margin-bottom: 6px;">Customer</div>
                  <b style="width:300px;float:left;line-height: 7px; font-size: 13px;">
                      <?php echo (isset($data['customer_name']) && $data['customer_name'] != '') ? ucwords(strtolower($data['customer_name'])) : ''; ?> 
                      <?php if(isset($data['customer_city']) && $data['customer_city'] != ""){ echo "," ; } ?>
                       <?php echo (isset($data['customer_city'])) ? $data['customer_city'] : '';?>
                       <?php if(isset($data['pincode']) && $data['pincode'] != ""){ echo "-" ; } else { echo "." ; } ?>
                       <?php echo (isset($data['pincode'])) ?  $data['pincode'] : '';?> </b>
                </td>
              </tr>
             <!-- <tr>     -->
             <!--   <td class="sub">-->
             <!--        <?php /*
             <!--                           $customerAddArr = [];-->
             <!--                           if(isset($data['addressline1']) && $data['addressline1'] != ''){-->
             <!--                               $customerAddArr[] = $data['addressline1'];-->
             <!--                           }-->
             <!--                           if(isset($data['addressline2']) && $data['addressline2'] != ''){-->
             <!--                               $customerAddArr[] = $data['addressline2'];-->
             <!--                           }-->
             <!--                           if(isset($data['addressline3']) && $data['addressline3'] != ''){-->
             <!--                               $customerAddArr[] = $data['addressline3'];-->
             <!--                           }-->
             <!--                           if(!empty($customerAddArr)){-->
             <!--                               $customerAddress = implode(', ', $customerAddArr);-->
             <!--                           }-->
             <!--                      */?>-->
             <!--     <?php //echo (isset($customerAddress)) ? $customerAddress : ''; ?> , <?php //echo (isset($data['customer_city'])) ? $data['customer_city'] : '';?>  <?php //echo (isset($data['pincode'])) ? - $data['pincode'] : '';?>-->
                 
                 
             <!--  </td>-->
             <!--</tr>-->
         <!--    <tr>     -->
         <!--   <td class="sub">-->
         <!--    GSTIN/UIN : <?php //echo (isset($data['gstno'])) ? $data['gstno'] : ''; ?>-->
         <!--  </td>-->
         <!--</tr>-->
           <!-- <tr>     -->
           <!--   <td class="sub"> -->
           <!--    State Name : <?php //echo (isset($data['customer_state'])) ? $data['customer_state'] : ''; ?> , Code : <?php echo (isset($data['statecode'])) ? $data['statecode'] : ''; ?>-->
           <!--  </td>-->
           <!--</tr>-->
        <tr>     
          <td class="sub">
           Contact No. : <?php echo (isset($data['mobile'])) ? $data['mobile'] : ''; ?>&nbsp; &nbsp; &nbsp; &nbsp;   Email : <?php echo (isset($data['email'])) ? $data['email'] : ''; ?> 
         </td>
       </tr>
        <!-- <tr>     -->
        <!--  <td class="sub">-->
        <!--    Email : <?php //echo (isset($data['email'])) ? $data['email'] : ''; ?> -->
        <!--  </td>-->
        <!--</tr>-->
      
     </table>
   </td>
   <tr>

   </tr>
 </table>
</td>
<td style="text-align:left;vertical-align: top; padding: 0;" >

 <table  cellpadding="0" cellspacing="0" border="1"  style="line-height:10px;font-size:9px;border-color: #8080809e; border: none;">
  <tr>     
    <td class="sub1">
      Invoice No <br>
      <b><?php echo (isset($data['invoice_no'])) ? $data['invoice_no'] : ''; ?></b>
    </td>
    <td class="sub1">
     Invoice Date <br>
     <b><?php echo (isset($data['invoice_date']) && $data['invoice_date'] != '') ? date('d-M-Y',strtotime($data['invoice_date'])) : ''; ?></b>
   </td>
 </tr>

<!-- <tr>-->
<!--  <td class="sub1">-->
<!--   Delivery Note <br>-->
<!--   <b>Ahmedabad</b>-->
<!-- </td>-->
<!-- <td class="sub1">-->
<!--   Delivery Note Date  <br>-->
<!--   <b>12/2/2018</b>-->
<!-- </td>-->
<!--</tr>-->

<!--<tr>-->
<!--  <td class="sub1">-->
<!--   Mode of Payment <br> -->
<!--   <b>30 Days</b>-->
<!-- </td>-->
<!-- <td class="sub1">-->
<!--    Buyer's Order No <br> -->
<!--    <b><?php //echo (isset($data['buyer_order_no'])) ? $data['buyer_order_no'] : ''; ?></b>-->
<!--  </td>-->
<!--</tr>-->

<tr>
  <!-- <td class="sub1">-->
  <!--  Buyer's Order Date <br> -->
  <!--  <b><?php //echo (isset($data['buyer_date']) && $data['buyer_date'] != '') ? date('d-M-Y',strtotime($data['buyer_date'])) : ''; ?></b>  -->
  <!--</td>-->
   <td class="sub1">
   Doctor name <br> 
   <b><?php echo (isset($data['doctor_name'])) ? $data['doctor_name'] : ''; ?></b>
 </td>
</tr>

<!--<tr>-->
<!-- <td class="sub1">-->
<!--   LR No. <br> -->
<!--   <b><?php //echo (isset($data['lr_no'])) ? $data['lr_no'] : ''; ?></b>-->
<!-- </td>-->
<!--  <td class="sub1">-->
<!--   LR Date <br> -->
<!--   <b><?php //echo (isset($data['lr_date']) && $data['lr_date'] != '') ? date('d-M-Y',strtotime($data['lr_date'])) : ''; ?></b>-->
<!-- </td>-->
<!--</tr>-->

<!--<tr>-->
<!-- <td class="sub1">-->
<!--   Destination <br> -->
<!--   <b><?php //echo (isset($data['customer_city'])) ? $data['customer_city'] : ''; ?></b>-->
<!-- </td>-->
<!--  <td class="sub1">-->
<!--    Motor Vech. No <br> -->
<!--    <b><?php //echo (isset($data['motor_vehicle_no'])) ? $data['motor_vehicle_no'] : ''; ?></b>-->
<!--  </td>-->
<!--</tr>-->

</table>

</td>



</tr>



<tr class="">
 <td colspan="3" style="height:2px;"></td>
</tr>

<!----------------------------------------------FOR PRODUCT SECTION START-------------------------------------------->
<tr class="top">
  <td colspan="3" style="padding:0px;border-bottom:none;border: none;">
   <table cellpadding="9" cellspacing="0" border="1" style="border: 0px solid;font-size:8px;line-height:16px;" frame="below" rules="cols" >					
    <tr class="heading" align="center" style="font-size: 12px;"> 
     <td style="width:10px;" rowspan="2">Sr. No.</td>
     <td rowspan="2">Prouduct Name</td>
     <td rowspan="2">MFG Co.</td>
     <td rowspan="2">HSN</td>
     <td rowspan="2">MRP</td>
     <td rowspan="2">B.No</td>
     <td rowspan="2">Expiry</td>
     <td rowspan="2">Qty</td>
     <!--<td rowspan="2">F.Qty</td>-->
     <td rowspan="2">Rate</td>
     <td rowspan="2">Per</td>
     <td rowspan="2">Dis %</td>
     <td rowspan="2">GST %</td>
     <td rowspan="2" align="center">Amount</td>                            
   </tr>


   <tbody style="margin-top:10px; height:100px;font-size: 12px;" >

  <?php if(isset($data['tax_billing_detail']) && !empty($data['tax_billing_detail'])){ ?>
    <?php 
    $total_qty = 0;$total_free_qty = 0;$total_rate = 0;$total_amount = 0;
    ?>				
 <?php foreach ($data['tax_billing_detail'] as $key => $value) {
 ?>
    <tr class="item" style="line-height: 16px;font-size: 12px;">
     <td><?php echo $key+1; ?></td>
     <td><?php echo (isset($value['product_name'])) ? ucfirst(strtolower($value['product_name'])) : ''; ?></td>
     
     <?php if($value['product_mfg_company'] != ''){   ?>
     <td><?php echo (isset($value['product_mfg_company'])) ? substr($value['product_mfg_company'],0,5)   : ''; ?></td>
     <?php } else {?>
      <td><?php echo (isset($value['bill_print_view'])) ? $value['bill_print_view'] : ''; ?></td>
     <?php } ?>
    
     <td><?php echo (isset($value['hsn'])) ? $value['hsn'] : ''; ?></td>
     <td align="right"><?php echo (isset($value['mrp'])) ? $value['mrp'] : ''; ?></td>
     <td><?php echo (isset($value['product_batch_no'])) ? $value['product_batch_no'] : ''; ?> </td>
     <td align="right"><?php echo (isset($value['product_ex_date']) && $value['product_ex_date'] != '') ? date('m/Y',strtotime($value['product_ex_date'])) : ''; ?></td>
     <td align="right">
      <?php 
            $qty =  (isset($value['qty']) && $value['qty'] != '') ? $value['qty'] : 0;
            $total_qty += $qty;
            echo round($qty, 2); 
       ?>
     </td>
     <!--<td align="right">-->
     <!--     <?php/*
     <!--       $free_qty = (isset($value['freeqty']) && $value['freeqty'] != '') ? $value['freeqty'] : 0;-->
     <!--       $total_free_qty += $free_qty;-->
     <!--       echo round($free_qty, 2);-->
     <!--    */ ?>-->
     <!--</td>-->
     <td align="right">
         <?php
            $rate =  (isset($value['rate']) && $value['rate'] != '') ? $value['rate'] : 0;
            $total_rate += $rate;
            // echo round($rate, 2);
          echo amount_format(number_format((round($rate, 2)), 2, '.', ''));
         ?>
     </td> 
     <td>-</td> 
     <td align="right"><?php echo (isset($value['discount']) && $value['discount'] != '') ? $value['discount'] : 0;?></td> 
     <td align="right"><?php echo (isset($value['gst'])) ? $value['gst'] : ''; ?></td>              
     <td align="right">
          <?php 
            $amount = (isset($value['totalamount']) && $value['totalamount'] != '') ? $value['totalamount'] : 0;
            $total_amount += $amount; 
            // echo round($amount, 2);
            echo amount_format(number_format((round($amount, 2)), 2, '.', ''));
            ?>
     </td> 
   </tr>
    <?php } ?>
   <?php } ?>
     <!------------------------------------EXTRA ROWS----- MAXIMUM ROW 8-------------START-------------------------- -->
   <?php if(isset($key) && $key!=''){  
  $availablerow = $key+1;
   } else{
       $availablerow = 0;
   }
         $addrow = 5- $availablerow;
       for($i =1 ;$i<=$addrow; $i++){ ?>
           <tr class="item" style="line-height: 17px;font-size: 13px;">
     <td></td>
     <td></td>
     <td></td>
     <td></td>
     <td></td>
     <td> </td>
     <td align="right"></td>
     <td align="right"></td>
     <td align="right"></td>
     <td align="right"></td> 
     <td></td> 
     <!--<td></td> -->
     <td></td>              
     <td></td> 
   </tr>
     <?php  }
  ?> 
   <!------------------------------------EXTRA ROWS----- MAXIMUM ROW 8-------------END-------------------------- -->
   <tr class="heading" style="background-color:#D6D6D6;border-top:1px solid #181818;line-height: 14px;"> 
     <td colspan="7" style="text-align: center;"><b>Total</b></td>
     <td style="text-align: right;"><b><?php echo (isset($total_qty)) ? round($total_qty, 2) : 0; ?></b></td>
     <!--<td style="text-align: right;"><b><?php/* echo (isset($total_free_qty)) ? round($total_free_qty, 2) : 0;*/ ?></b></td>-->
     <td style="text-align: right;"><b></b></td>
     <td  style="text-align: right;"><b></b></td>
     <td  style="text-align: right;"><b></b></td>
     <td  style="text-align: right;"><b></b></td>
     <td style="text-align: right;"><b><?php echo (isset($total_amount)) ? amount_format(number_format((round($total_amount, 2)), 2, '.', '')) : 0; ?></b></td>
   </tr>

 </tbody>

 <tr class="heading"  style="font-size: 12px;"> 
  <td colspan="12" style="text-align:left">Amount chargeable (in words)<br>
   <b><?php echo (isset($total_amount) && $total_amount != '') ? number_to_word($total_amount) : 'Zero'; ?></b>
 </td>
 <td  colspan="2" align="center"><br>E. & O.E</td>                            
</tr>

</table>
</td>
</tr>
<!----------------------------------------------FOR PRODUCT SECTION END-------------------------------------------->




<tr class="">
 <td colspan="3" style="height:2px;border: none;"></td>
</tr>

<!----------------------------------------------FOR COUNT TOTAL AMOUNT SECTION START-------------------------------------------->

<tr class="top">
  <td colspan="2" style="padding:0px; vertical-align: top;border: none; " >
   <table cellpadding="0" cellspacing="0" border="1" style="font-size:7px;line-height:18px;" frame="box" rules="cols">

    <tr class="heading" align="center" style="font-size: 11px"> 
      <td rowspan="2"><b>HSN</b></td>
      <td rowspan="2" style="text-align:center; width: 96px;"><b>Taxable Value</b></td>
      <td colspan="2" style="text-align:center"><b>SGST TAX</b></td>
      <td colspan="2" style="text-align:center"><b>CGST TAX</b></td>
      <td rowspan="2" style="text-align:center"><b>Total</b></td>                            
    </tr>
    <tr class="heading" style="font-size: 11px"> 
      <td style= "width: 52px;"><b>Rate</b></td>
      <td><b>Amount</b></td>
      <td><b>Rate</b></td>
      <td><b>Amount</b></td>                                                                   
    </tr> 
    <tbody style="margin-top:10px; height:100px;font-size: 11px;">
    
    <?php if(isset($data['hsn_detail']) && !empty($data['hsn_detail'])){ ?>
     <?php 
       $taxable_value = 0; $cgstamount = 0; $sgstamount = 0; $amount_total = 0; $total_tax_amount=0;
    ?>				
    <?php foreach ($data['hsn_detail'] as $key => $value) { ?>
    <tr class="item" style="line-height: 18px; ">
     <td><?php echo (isset($value['hsn'])) ? $value['hsn'] : ''; ?></td>
     <td align="right">
      <?php
         $r_taxable_amount = (isset($value['taxablevalue']) && $value['taxablevalue'] != '') ? $value['taxablevalue'] : 0; 
         $taxable_value += $r_taxable_amount;
         echo amount_format(number_format(($r_taxable_amount), 2, '.', ''))
         ?>
     </td>
     <td align="right">
        <?php
            $r_sgst = (isset($value['sgst']) && $value['sgst'] != '') ? $value['sgst'] : 0;
            echo round($r_sgst, 2).' %';
        ?>
      </td>
     <td align="right">
         <?php 
             $r_sgst_amount = ($r_taxable_amount*$r_sgst/100);
             $sgstamount += $r_sgst_amount;
              echo $r_sgst_amount;
          ?>
       </td>
     <td>
         <?php
            $r_cgst = (isset($value['cgst']) && $value['cgst'] != '') ? $value['cgst'] : 0;
            echo round($r_cgst, 2).' %';
            ?>
      </td>
     <td>
      <?php 
             $r_cgst_amount = ($r_taxable_amount*$r_cgst/100);
             $cgstamount += $r_cgst_amount;
              echo $r_cgst_amount;
          ?>
     </td>
     <td align="right">
       <?php  $taxamount = $r_sgst_amount + $r_cgst_amount;
           echo amount_format(number_format((round($taxamount, 2)), 2, '.', ''));
           $total_tax_amount += $taxamount;
       ?>
    </td>
   </tr> 
  <?php }?>
  <?php } ?>
    <?php /* if(isset($freightL) && !empty($freightL)){ ?>
   <!-- <tr class="item" style="line-height: 18px; ">-->
   <!--  <td></td>-->
   <!--  <td align="right">-->
   <!--   <?php /*
   <!--      $couriercharge = (isset($freightL['couriercharge_val']) && $freightL['couriercharge_val'] != '') ? $freightL['couriercharge_val'] : 0; -->
   <!--      $taxable_value += $couriercharge;-->
   <!--      echo amount_format(number_format(($couriercharge), 2, '.', ''));-->
   <!--      ?>-->
   <!--  </td>-->
   <!--  <td align="right">-->
   <!--     <?php-->
   <!--         $c_sgst = (isset($freightL['couriercharge']) && $freightL['couriercharge'] != '') ? $freightL['couriercharge']/2 : 0;-->
   <!--         echo round($c_sgst, 2).' %';-->
   <!--     ?>-->
   <!--   </td>-->
   <!--  <td align="right">-->
   <!--      <?php -->
   <!--          $c_sgst_amount = ($couriercharge*$c_sgst/100);-->
   <!--          $sgstamount += $c_sgst_amount;-->
   <!--           echo $c_sgst_amount;-->
   <!--       ?>-->
   <!--    </td>-->
   <!--  <td>-->
   <!--      <?php-->
   <!--         $c_cgst = (isset($freightL['couriercharge']) && $freightL['couriercharge'] != '') ? $freightL['couriercharge']/2 : 0;-->
   <!--         echo round($c_cgst, 2).' %';-->
   <!--         ?>-->
   <!--   </td>-->
   <!--  <td>-->
   <!--   <?php -->
   <!--          $c_cgst_amount = ($couriercharge*$c_cgst/100);-->
   <!--          $cgstamount += $c_cgst_amount;-->
   <!--           echo $c_cgst_amount;-->
   <!--       ?>-->
   <!--  </td>-->
   <!--  <td align="right">-->
   <!--    <?php  $taxamountc = $c_sgst_amount + $c_cgst_amount;-->
   <!--        echo amount_format(number_format((round($taxamountc, 2)), 2, '.', ''));-->
   <!--        $total_tax_amount += $taxamountc;-->
   <!--  */  ?>
   <!-- </td>-->
   <!--</tr> -->
    <?php /* } */?>
   <tr class="heading" style="background-color:#D6D6D6;border-top:1px solid #181818; line-height: 2;"> 
    <td style="text-align: right;"><b>Total</b></td>
    <td style="text-align: right;"><b><?php echo (isset($taxable_value)) ? amount_format(number_format((round($taxable_value, 2)), 2, '.', '')) : 0; ?></b></td>
    <td colspan="2" style="text-align: right;"><b><?php echo (isset($sgstamount)) ? amount_format(number_format((round($sgstamount, 2)), 2, '.', '')) : 0; ?></b></td>
    <td colspan="2" style="text-align: right;"><b><?php echo (isset($cgstamount)) ? amount_format(number_format((round($cgstamount, 2)), 2, '.', '')) : 0; ?></b></td>

    <td colspan="2" style="text-align: right;"><b><?php echo (isset($total_tax_amount)) ? amount_format(number_format((round($total_tax_amount, 2)), 2, '.', '')) : 0; ?></b></td>

  </tr>
  
  
</tbody>

</table>

</td>
           <!--  <td colspan="1">
                

           </td> -->
           <td align="right" style="text-align:right;padding:0px; vertical-align: top; border: none;"  colspan="1">
            <table cellpadding="2" cellspacing="0" border="0" style="font-size:11px;line-height:16px;" frame="box" rules="cols" >
              <tr style="background-color:#D6D6D6">
                <td><div style="width:130px;float:left;">Gross Amount</div></td>
                <td align="right"><?php echo (isset($total_amount)) ? amount_format(number_format((round($total_amount, 2)), 2, '.', '')) : 0; ?></td>
              </tr>
              <!--<tr style="background-color:#D6D6D6">-->
              <!-- <?php/* $disAmount = (isset($data['discount_rs']) && $data['discount_rs'] != '') ? round($data['discount_rs'], 2) : 0; */?>-->
               
              <!--  <td><div style="width:130px;float:left;">Discount</div></td>-->
              <!--  <td align="right"><?php/* echo (isset($disAmount)) ? amount_format(number_format((round($disAmount, 2)), 2, '.', '')) : 0.0; */?></td>-->
              <!--</tr>-->

              <!--<tr>-->
             
              <!--  <td><div style="width:130px;float:left;">Freight</div></td>-->
              <!--  <td align="right">-->
              <!--    <?php/*
              <!--                       $courierValue = (isset($data['couriercharge_val']) && $data['couriercharge_val'] != '') ? $data['couriercharge_val'] : 0;   -->
              <!--                          echo amount_format(number_format((round($courierValue, 2)), 2, '.', ''));-->
              <!--                      */?>-->
              <!--  </td>-->
              <!--</tr>-->
              <!--<tr>-->
              <!--  <td><div style="width:130px;float:left;">SGST</div></td>-->
              <!--  <td align="right">-->
              <!--    <?php //echo (isset($data['totalsgst']) && $data['totalsgst'] != '') ? round($data['totalsgst'], 2) : 0; ?>-->
              <!--  </td>-->
              <!--</tr>-->
              <!--<tr>-->
              <!--  <td><div style="width:130px;float:left;">CGST</div></td>-->
              <!--  <td align="right">-->
              <!--   <?php //echo (isset($data['totalcgst']) && $data['totalcgst'] != '') ? round($data['totalcgst'], 2) : 0; ?>-->
              <!--  </td>-->
              <!--</tr>-->
              <tr style="background-color:#D6D6D6">
                <td><div style="width:130px;float:left;">Return Amount</div></td>
                <td align="right">
                 <?php echo (isset($data['taxreturnAmount']) && $data['taxreturnAmount'] != '') ? round($data['taxreturnAmount'], 2) : 0; ?>   
                </td>
              </tr>
              <!--<tr style="background-color:#DEDEDE;color:#070707">-->
              <!--  <td><div style="width:130px;float:left;font-weight:bold;">Round Off</div></td>-->
              <!--  <td align="right" style="font-weight:bold;"><?php //echo (isset($data['roundoff_amount']) && $data['roundoff_amount'] != '') ? amount_format(number_format((round($data['roundoff_amount'], 2)), 2, '.', '')) : 0; ?></td>-->
              <!--</tr>-->

              <tr style="background-color:#DEDEDE;color:#070707">
                <td><div style="width:130px;float:left;font-weight:bold;">Net Amount</div></td>
                <td align="right" style="font-weight:bold;"><?php echo (isset($data['final_amount']) && $data['final_amount'] != '') ? amount_format(number_format(($data['final_amount']), 2, '.', '')) : 0; ?></td>
              </tr>
            </table>
          </td>

        </tr>

<!--        <tr class="top" >-->
<!--         <td colspan="3" >-->
<!--           <div style="float: left; width: 50%;font-size:13px;line-height:13px;">-->
            <!--<b> Remarks</b> <br>-->
            <?php //echo $data['remarks'];?><p>
              <!--<b>Declaration / Terms</b><br> -->
              <!--This is terms & condition from master   -->
              <!--This is second line-->

<!--            </div>-->

<!--            <div style="float: left; width: 50%;line-height: 16px;">-->
<!--          <?php if( mysqli_num_rows($pharmacyBankDetailR) > 0){ ?>-->
<!--              <span><b>Bank Name</b></span> <br>  -->
<!--              <span>Company name : <?php echo (isset($data['pharmacy_bank_detail'][0]['name'])) ? $data['pharmacy_bank_detail'][0]['name'] : ''; ?></span><br>-->
             
<!--              <span>A/C No. : <?php echo (isset($data['pharmacy_bank_detail'][0]['bank_ac_no'])) ? $data['pharmacy_bank_detail'][0]['bank_ac_no'] : ''; ?></span><br>-->
<!--              <span>Branch & IFS Code :<span style="font-size: 9px;"> <?php echo (isset($data['pharmacy_bank_detail'][0]['branch_name'])) ? $data['pharmacy_bank_detail'][0]['branch_name'] : ''; ?> & <?php echo (isset($data['pharmacy_bank_detail'][0]['ifsc_code'])) ? $data['pharmacy_bank_detail'][0]['ifsc_code'] : ''; ?></span></span><br>-->
<!--            <?php } ?>-->
<!--              <table cellpadding="0" cellspacing="0" border="1"  frame="box" rules="cols" >-->
<!--            <tr>-->
            
            
<!--          <td colspan="1" style="padding:0px; vertical-align: top;border: none; ">-->
              
<!--              <b style="margin-left: 100px;">For, <?php echo (isset($data['pharmacy']['pharmacy_name'])) ? $data['pharmacy']['pharmacy_name'] : ''; ?>  </b><br>-->
<!--              <br>-->
<!--              <center style="font-size: 8px;">Authorised Signatory</center>-->
<!--              </td>-->
<!--</tr>-->
<!--</table>-->

<!--            </div>-->

<!--          </td>-->

<!--        </tr>-->
<tr>
                <td colspan="3" style="border-top: 1px solid #000;padding: 0px;">
                    <table style="width: 100%;">
                        <tr style="height: 18px;">
                            <td></td>
                        </tr>
                        <tr>
                            <td>
                                <span class="term" style= "font-size: 12px;padding-left: 15px;padding-right: 15px;">SUBJECT TO JUNAGADH JURISDICTION</span>
                                <span class="term" style= "font-size: 12px;padding-left: 15px;padding-right: 15px;"><?php echo date('d/m/Y h:i:s A');?></span>
                                <span class="forsign" style ="font-size: 12px;float: right;">For, <?php echo (isset($data['pharmacy']['pharmacy_name'])) ? $data['pharmacy']['pharmacy_name'] : ''; ?></span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

</table>
<!--</td>-->
<!--</tr>-->
<!----------------------------------------------FOR COUNT TOTAL AMOUNT SECTION END-------------------------------------------->

<!--</table>-->
<!--<div style="margin-bottom:3px;margin-top: 3px;text-transform: uppercase;text-align: center;">Subject to <?php echo (isset($data['pharmacy']['city'])) ? $data['pharmacy']['city'] : ''; ?> Jurisdiction &nbsp; &nbsp; <?php echo date("d/m/Y");?> &nbsp; &nbsp; <?php echo date("h:i:s A");?></div>-->

</div>
</page>
</body>
</html>
<script>//window.close();</script>