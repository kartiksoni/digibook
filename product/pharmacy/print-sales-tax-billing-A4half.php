<?php include('include/usertypecheck.php'); ?>
<?php 
if(isset($_GET['id']) && $_GET['id'] != ''){
  $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';

  $query = "SELECT tb.*, lg.name as customer_name, lg.addressline1, lg.addressline2, lg.addressline3, lg.gstno,lg.panno,lg.mobile,lg.phone,lg.dl_no1,lg.dl_no2 , st.name as customer_state, st.state_code_gst as statecode, ct.name as customer_city,dp.name as doctor_name , dp.personal_title FROM tax_billing tb LEFT JOIN ledger_master lg ON tb.customer_id = lg.id LEFT JOIN own_states st ON lg.state = st.id LEFT JOIN own_cities ct ON lg.city = ct.id LEFT JOIN doctor_profile dp ON tb.doctor = dp.id WHERE tb.pharmacy_id ='".$pharmacy_id."' AND tb.id='".$_GET['id']."'";
   $res = mysqli_query($conn, $query);
  if($res && mysqli_num_rows($res) > 0){
    $data = mysqli_fetch_assoc($res);

    /*----------------------------------------------PHARMACY QUERY START-------------------------------------------*/
    if(isset($data['pharmacy_id']) && $data['pharmacy_id'] != ''){
    //  $pharmacyQ = "SELECT p.id, p.pharmacy_name, p.address1, p.address2, p.address3, p.pin_code,p.gst_no,p.city_name as city,p.mobile_no,p.email,st.name as state_name,st.state_code_gst as pharmastatecode FROM pharmacy_profile p LEFT JOIN own_states st ON p.stateId = st.id WHERE p.id='".$data['pharmacy_id']."'";
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
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>Sale tax bill Print</title>
  <link rel="shortcut icon" href="images/favicon.png" />

  <!-- Normalize or reset CSS with your favorite library -->
  <link rel="stylesheet" href="css/normalize.min.css">

  <!-- Load paper.css for happy printing -->
  <link rel="stylesheet" href="css/billA4half.css">

  <!-- Set page size here: A5, A4 or A3 -->
  <!-- Set also "landscape" if you need -->
  <style>@page { size: A4Half }</style>

<!-- custom css -->
  <style>
    *{font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;}
    .main-table{
        border-collapse: collapse;
        border: 1px solid #000;
        width:100%;
    }
    .main-table tr td{
        /* border: 1px solid #000; */
    }
    .company-logo img{
        width:100%; 
        max-width:80px;
    }
    .company-info{
        padding-left: 5px;
    }
    .invoice-info{
       /* vertical-align: top; */
       padding-left: 5px;
       border-left: 1px solid #000;
    }
    .customer-info{
        /* vertical-align: top; */
        padding-left: 5px;
        border-left: 1px solid #000;
        line-height: 20px;
    }
    .title{
        font-size: 14px;
    }
    .sub-title{
        font-size: 12px;
    }
    .font-bold{
        font-weight: bold;
    }
    .header-row{
        height: 20px;
        font-size: 12px;
        text-align: center;
        font-weight: bold;
        border-bottom: 1px solid #000;
    }
    .product-row{
        font-size: 12px;
        text-align: center;
        height: 20px;
    }
    .product-row td:last-child{
        text-align: right;
    }
    .total-row{
        height: 20px;
        border-top: 1px solid #000;
    }
    .total-row td{
       font-weight: bold;
       text-align: center;
   }
   .total-row td:last-child{
        text-align: right;
   }
    .product-details{
        font-size: 12px;
        width: 100%;
        border-collapse: collapse;
        height: 270px;
    }
    .note{
        font-size: 12px;
        text-align: left;
        vertical-align: top;
    }
    .amount-details{
        font-size: 12px;
        width: 100%;
        border-collapse: collapse;
        line-height: 15px;
    }
    .amount-details td:nth-child(1){
        text-align: left;
        font-weight: bold;
    }
    .amount-details td:nth-child(2){
        text-align: right;
    }
    .total-amount{
        font-size: 14px;
        font-weight: bold;
    }
    .term{
        font-size: 12px;
        padding-left: 15px;
        padding-right: 15px;
    }
    .forsign{
        font-size: 12px;
        float: right;
       
    }
  </style>
</head>

<!-- Set "A5", "A4" or "A3" for class name -->
<!-- Set also "landscape" if you need -->

<?php foreach(array_chunk($data['tax_billing_detail'], 12) as $billing ) { ?> 
  
<body class="A4Half">

  <!-- Each sheet element should have the class "sheet" -->
  <!-- "padding-**mm" is optional: you can set 10, 15, 20 or 25 -->
  <section class="sheet padding-10mm">
        <!-- Main Table -->
        <table class="main-table">
             <?php  
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
       ?>
            <tr>
                <td class="company-info">
                    <span class="title font-bold"><?php echo (isset($data['pharmacy']['pharmacy_name'])) ? $data['pharmacy']['pharmacy_name'] : ''; ?></span><br>
                    <span class="sub-title"><?php echo (isset($companyAddress)) ? $companyAddress : ''; ?>
                         <?php if(isset($data['pharmacy']['city']) && $data['pharmacy']['city'] != ""){ echo "," ; } ?>
                     <?php echo (isset($data['pharmacy']['city'])) ? $data['pharmacy']['city'] : ''; ?> 
                       <?php if(isset($data['pharmacy']['pin_code']) && $data['pharmacy']['pin_code'] != ""){ echo "-" ; } else { echo "." ; } ?>
                     <?php echo (isset($data['pharmacy']['pin_code'])) ? $data['pharmacy']['pin_code'] : ''; ?> 
                     </span><br>
                     
                      <?php if (isset($data['pharmacy']['mobile_no']) && $data['pharmacy']['mobile_no'] !=''){ ?>
                       <span class="sub-title">M: <?php echo (isset($data['pharmacy']['mobile_no'])) ? $data['pharmacy']['mobile_no'] : ''; ?></span><br>
                     <?php } ?>
                     
                     
                     <?php if (isset($data['pharmacy']['gst_no']) && $data['pharmacy']['gst_no'] !=''){ ?>
                       <span class="sub-title">GSTIN/UIN: <?php echo (isset($data['pharmacy']['gst_no'])) ? $data['pharmacy']['gst_no'] : ''; ?></span><br>
                     <?php } ?> 
                     
                     <?php /* if (isset($data['pharmacy']['state_name']) && $data['pharmacy']['state_name'] !=''){ ?>
                      <span class="sub-title">State Name: <?php echo (isset($data['pharmacy']['state_name'])) ? $data['pharmacy']['state_name'] : ''; ?> Code: <?php echo (isset($data['pharmacy']['pharmastatecode'])) ? $data['pharmacy']['pharmastatecode'] : ''; ?></span><br>
                     <?php } */ ?>
                     
                    <!--<span class="sub-title">CIN: </span><br>-->
                    
                    <?php if (isset($data['pharmacy']['email']) && $data['pharmacy']['email'] !=''){ ?>
                    <span class="sub-title">E-Mail: <?php echo (isset($data['pharmacy']['email'])) ? $data['pharmacy']['email'] : ''; ?></span>
                    <?php } ?>
                </td>
                <td class="invoice-info">
                    <span class="title font-bold">Invoice # <?php echo (isset($data['invoice_no'])) ? $data['invoice_no'] : ''; ?></span><br>
                    <span class="title">Date: <?php echo (isset($data['invoice_date']) && $data['invoice_date'] != '') ? date('d/m/Y',strtotime($data['invoice_date'])) : ''; ?></span>
                </td>
                <td class="customer-info">
                     <?php if (isset($data['doctor_name']) && $data['doctor_name'] !=''){ ?>
                    <span class="title">Doctor: <b><?php echo (isset($data['personal_title'])) ? $data['personal_title'] : '';  ?>. <?php echo (isset($data['doctor_name'])) ? $data['doctor_name'] : ''; ?></b></span><br>
                    <?php } ?>
                    
                    <span>&nbsp;</span><br>
                    
                    <?php if (isset($data['customer_name']) && $data['customer_name'] !=''){ ?>
                    <span class="title">Customer: <b><?php echo (isset($data['customer_name']) && $data['customer_name'] != '') ? ucwords(strtolower($data['customer_name'])) : ''; ?></b></span><br>
                    <?php } ?>
                    
                    <?php if (isset($data['customer_city']) && $data['customer_city'] !=''){ ?>
                    <span class="title"> <b><?php echo (isset($data['customer_city']) && $data['customer_city'] != '') ? ucwords(strtolower($data['customer_city'])) : ''; ?></b></span><br>
                    <?php } ?>
                    
                    
                    <?php if (isset($data['mobile']) && $data['mobile'] !=''){ ?>
                    <span class="sub-title">M: <?php echo (isset($data['mobile'])) ? $data['mobile'] : ''; ?></span><br>
                    <?php } ?>
                    
                    <?php if (isset($data['email']) && $data['email'] !=''){ ?>
                    <span class="sub-title">E-Mail: <?php echo (isset($data['email'])) ? $data['email'] : ''; ?></span>
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <td colspan="3" style="border-top: 1px solid #000;border-bottom: 1px solid #000;padding: 0px;">
                    <table class="product-details">
                        <tr class="header-row">
                            <td>Sr.</td>
                            <td>Particular</td>
                            <td>Pack</td>
                            <td>Mfg.</td>
                            <td>MRP</td>
                            <td>Qty</td>
                            <td>Batch</td>
                            <td>Exp.</td>
                          <?php if ($Discountcount != 0){ ?>
                            <td>Disc%</td>
                            <?php } ?>
                            <td>GST%</td>
                            <td>GST(&#8377)</td>
                            <td style="float: right;">Amount</td>
                        </tr>
    <?php if(isset($data['tax_billing_detail']) && !empty($data['tax_billing_detail'])){ ?>
      <?php 
       $total_qty = 0;$total_free_qty = 0;$total_rate = 0;$total_amount = 0; $total_gst = 0; $all_discount = 0;
      ?>				
    <?php foreach ($billing as $key => $value) { ?>
                        <tr class="product-row">
                            <td><?php echo $key+1; ?></td>
                            <td><?php echo (isset($value['product_name'])) ? ucfirst(strtolower($value['product_name'])) : ''; ?></td>
                            <td><?php echo (isset($value['ratio'])) ? ucfirst(strtolower($value['ratio'])) : ''; ?><?php echo (isset($value['unit_name'])) ? strtoupper(strtolower($value['unit_name'])) : ''; ?></td>
                <?php if($value['product_mfg_company'] != ''){   ?>
                            <td><?php echo (isset($value['product_mfg_company'])) ? substr($value['product_mfg_company'],0,5)   : ''; ?></td>
                <?php } else {?>
                            <td><?php echo (isset($value['bill_print_view'])) ? $value['bill_print_view'] : ''; ?></td>
                <?php } ?>
                            
                            <td><?php echo (isset($value['mrp'])) ? $value['mrp'] : ''; ?></td>
                            <td><?php 
                               $qty =  (isset($value['qty']) && $value['qty'] != '') ? $value['qty'] : 0;
                                $total_qty += $qty;
                                echo round($qty, 2); 
                            ?></td>
                            <td><?php echo (isset($value['batch_no'])) ? $value['batch_no'] : '-'; ?></td>
                              
                            <td><?php echo (isset($value['product_ex_date']) && $value['product_ex_date'] != '0000-00-00') ? date('m/y',strtotime($value['product_ex_date'])) : '-'; ?></td>
                          
                           <?php if ($Discountcount != 0){ ?>
                            <td><?php echo (isset($value['discount'])) ? $value['discount'] : ''; ?></td>
                             <?php } ?>
                             
                            <td><?php echo (isset($value['gst'])) ? $value['gst'] : ''; ?></td>
                            <td><?php $gst_tax = (isset($value['gst_tax']) && $value['gst_tax'] != '' ) ? $value['gst_tax'] : ''; 
                                  $total_gst += $gst_tax;
                                  echo amount_format(number_format((round($gst_tax, 2)), 2, '.', ''));
                            ?></td>
                            <td>
                            <?php 
                                $amount = (isset($value['qty']) && $value['qty'] != '' && isset($value['rate']) && $value['rate'] !='') ? $value['qty'] * $value['rate'] : '0';
                                $total_amount += $amount; 
                                // echo round($amount, 2);
                                echo amount_format(number_format((round($amount, 2)), 2, '.', ''));
                           ?></td>
                           
                           <?php /*
                           $totalamount = (isset($value['qty']) && $value['qty'] != '' && isset($value['rate']) && $value['rate'] !='') ? $value['qty'] * $value['rate'] : '0';
                           $Discount = (isset($value['discount'])) ? $value['discount'] : '0';
                           $to_discount = ($totalamount * $Discount)/100;
                           $all_discount += $to_discount;  
                          */ ?>
                           
                        </tr>
    <?php } ?>
    <?php } ?>
                      <!------------------------------------EXTRA ROWS----- MAXIMUM ROW 8-------------START-------------------------- -->
   <?php if(isset($key) && $key!=''){  
  $availablerow = $key+1;
   } else{
       $availablerow = 0;
   }
         $addrow = 12- $availablerow;
       for($i =1 ;$i<=$addrow; $i++){ ?>
           <tr class="product-row">
     <td></td>
     <td></td>
     <td></td>
     <td></td>
     <td></td>
     <td></td>
     <td></td> 
     <td></td>
     <?php if ($Discountcount != 0){ ?>
     <td></td> 
     <?php } ?>
     <td></td>              
     <td></td>
      <td></td>
   </tr>
     <?php  }
  ?> 
  
                        <tfoot>
                            <tr class="total-row">
                                <td colspan="5">
                                    Total
                                </td>
                                <td><?php echo (isset($total_qty)) ? round($total_qty, 2) : 0; ?></td>
                                <td></td>
                                <td></td>
                                <?php if ($Discountcount != 0){ ?>
                                <td></td>
                                <?php } ?>
                                <td></td>
                                <td> <?php echo (isset($total_gst)) ? round($total_gst, 2) : 0; ?></td>
                                <td><?php echo (isset($total_amount)) ? amount_format(number_format((round($total_amount, 2)), 2, '.', '')) : 0; ?></td>
                            </tr>
                        </tfoot>
                    </table>        
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <table style="width: 100%;">
                        <tr>
                            <td>
                                <span class="note">GET WELL SOON</span><br>
                              
                                <span class="note font-bold"><?php echo(isset($data['billnote']['bill_note']))? $data['billnote']['bill_note']:'';  ?></span><br>    
                                
                                <span class="note">E. & O.E.</span>
                            </td>
                            <td style="vertical-align: top;width: 150px;">
                                <table class="amount-details">
                                   <?php  if($product_discount != 0){ ?>
                                    <tr>
                                        <td>Discount :</td>
                                        <td><?php echo (isset($product_discount)) ? $product_discount : '0'; ?></td>
                                    </tr>
                                    <?php } ?>
                                    
                                    <?php if($value['discount_rs'] != 0){ ?>
                                    <tr>
                                        <td>Total Discount :</td>
                                        <td><?php echo (isset($value['discount_rs'])) ? $value['discount_rs'] : ''; ?></td>
                                    </tr>
                                    <?php } ?>
                                    <tr>
                                        <td>Grand Total :</td>
                                        <td class="total-amount"><?php echo (isset($grand_total)) ? amount_format(number_format((round($grand_total, 2)), 2, '.', '')) : 0; ?></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="3" style="border-top: 1px solid #000;padding: 0px;">
                    <table style="width: 100%;">
                        <tr style="height: 23px;">
                            <td></td>
                        </tr>
                        <tr>
                            <td>
                                <span class="term">SUBJECT TO JUNAGADH JURISDICTION</span>
                                <span class="term"><?php echo date('d/m/Y h:i:s A');?></span>
                                <span class="forsign" style="margin-top: 5px;">For, <?php echo (isset($data['pharmacy']['pharmacy_name'])) ? $data['pharmacy']['pharmacy_name'] : ''; ?></span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
  </section>
</body>

<?php }?>

</html>

