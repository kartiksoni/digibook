<?php include('include/usertypecheck.php'); ?>
<?php 
if(isset($_GET['id']) && $_GET['id'] != ''){
  $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';

 $query = "SELECT tb.*, lg.name as customer_name, lg.addressline1, lg.addressline2, lg.addressline3, lg.gstno,lg.panno,lg.mobile,lg.phone,lg.dl_no1,lg.dl_no2 ,tm.name as transportname, st.name as customer_state, st.state_code_gst as statecode, ct.name as customer_city,dp.name as doctor_name FROM tax_billing tb LEFT JOIN ledger_master lg ON tb.customer_id = lg.id LEFT JOIN own_states st ON lg.state = st.id LEFT JOIN own_cities ct ON lg.city = ct.id LEFT JOIN doctor_profile dp ON tb.doctor = dp.id LEFT JOIN transport_master tm on tb.transporter_id = tm.id WHERE tb.pharmacy_id ='".$pharmacy_id."' AND tb.id='".$_GET['id']."'";
   $res = mysqli_query($conn, $query);
  if($res && mysqli_num_rows($res) > 0){
    $data = mysqli_fetch_assoc($res);

    /*----------------------------------------------PHARMACY QUERY START-------------------------------------------*/
    if(isset($data['pharmacy_id']) && $data['pharmacy_id'] != ''){
      $pharmacyQ = "SELECT p.id, p.pharmacy_name, p.address1, p.address2, p.address3, p.gst_no,p.city_name as city,p.mobile_no,p.email,st.name as state_name,st.state_code_gst as pharmastatecode FROM pharmacy_profile p LEFT JOIN own_states st ON p.stateId = st.id WHERE p.id='".$data['pharmacy_id']."'";
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
      $taxBillingDetailQ = "SELECT tbd.* , tb.*, pm.product_name, pm.mrp as product_mrp, pm.mfg_company as product_mfg_company,pm.hsn_code as hsn, pm.batch_no as product_batch_no, pm.ex_date as product_ex_date FROM tax_billing_details tbd INNER JOIN product_master pm ON tbd.product_id = pm.id INNER JOIN tax_billing tb ON tbd.tax_bill_id = tb.id WHERE tbd.tax_bill_id = '".$data['id']."'";
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
      $salereturnQ = "SELECT sr.*, pm.product_name, pm.mrp as product_mrp, pm.mfg_company as product_mfg_company, pm.batch_no as product_batch_no, pm.ex_date as product_ex_date FROM sale_return sr INNER JOIN product_master pm ON sr.product_id = pm.id WHERE sr.tax_bill_id = '".$data['id']."'";
      $salereturnR = mysqli_query($conn, $salereturnQ);
      if($salereturnR && mysqli_num_rows($salereturnR) > 0){
        while ($salereturnRow = mysqli_fetch_assoc($salereturnR)) {
          $data['sale_return'][] = $salereturnRow;
        }
      }
    }
    /*----------------------------------------------PRODUCT RETURN DETAIL QUERY END-------------------------------------------*/
            // pr($data);exit;
  }
}

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">

  <title> TAX Invoice </title>  
  <link rel="shortcut icon" href="assets/images/favicon.png">          
  <link rel="stylesheet" href="css/billA4half.css">
  <style type="text/css">
  .sub{
    /*width:150px;*/
    float:left;
    line-height: 11px;
    font-size: 11px;
  }

  .sub1{
    /*width: 150px;*/
    float: left;
    line-height: 11px;
    font-size: 11px;
  }
.first:after {
     content: "";
    background-color: #000;
    position: absolute;
    width: 1px;
    height: 97px;
    top: 86px;
    left: 50%;
}
  
  @media print {
    @page { margin: 0; }
    body { margin: 0.5cm;font-family:Verdana, Arial, Helvetica, sans-serif; }
  }

  @page { size: auto;  margin: 0mm; }
  @page { margin: 0; }
  /*@page {
   size: 7in 9.25in;
   margin: 25mm 16mm 27mm 16mm;
 }*/
 @page { margin: 0; }


</style>

</head>
<body>


  <page size="A4-HALF"> 
    <div class="invoice-box">
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
    
      <table cellpadding="0" border="1"  cellspacing="0">
          <tr class="top">
          <td colspan="3" style="padding:0px;border-bottom:none;">
               <div rowspan="3"  style="font-size:17px;border-left:none;"><span style ="margin-right: 192px;margin-left: 315px;">TAX INVOICE</span> <span>Original Copy</span></div>
               <div class="company-title"><?php echo (isset($data['pharmacy']['pharmacy_name'])) ? $data['pharmacy']['pharmacy_name'] : ''; ?></div>
                <div class="company-address" style="margin-left: 5px;"> <?php echo (isset($companyAddress)) ? $companyAddress : ''; ?> Tel.<?php echo (isset($data['pharmacy']['mobile_no'])) ? $data['pharmacy']['mobile_no'] : ''; ?> email:<?php echo (isset($data['pharmacy']['email'])) ? $data['pharmacy']['email'] : ''; ?></div>
                  <div class="company-gst" style="margin-left: 5px;">GSTNo : <?php echo (isset($data['pharmacy']['gst_no'])) ? $data['pharmacy']['gst_no'] : ''; ?></div>
         </td>

       </tr>
      

       <tr class="" style="line-height:12px;">

            <td  style="vertical-align:top;border:1;" colspan="3">
            <div class="first" style="width: 50%;float: left;">
              <table  cellpadding="0" cellspacing="0" border="0" style="line-height:10px;font-size:9px;" >
 
                <tr>     
                  <td>
                    <span class="sub"><?php echo (isset($data['customer_name']) && $data['customer_name'] != '') ? ucwords(strtolower($data['customer_name'])) : ''; ?> </span>
                  </td>
                </tr>
                <tr>     
                  <td>
                       <?php 
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
                                    ?>
                                    
                    <span class="sub"><?php echo (isset($customerAddress)) ? $customerAddress : ''; ?></span> 

                  </td>
                </tr>
                
                <tr>     
                  <td>
                    <span class="sub">PH: <?php echo (isset($data['mobile'])) ? $data['mobile'] : ''; ?> , <?php echo (isset($data['phone'])) ? $data['phone'] : ''; ?></span>  

                  </td>
                </tr>
                <tr> 
                <td></td>   
                  <td>
                    <span class="sub">D.L. <?php echo (isset($data['dl_no1'])) ? $data['dl_no1'] : ''; ?></span>

                  </td>
                </tr>
                <tr>     
                  <td>
                    <span class="sub">GST NO. <?php echo (isset($data['gstno'])) ? $data['gstno'] : ''; ?> </span>
                  </td>
                  <td>
                    <span class="sub">D.L. <?php echo (isset($data['dl_no1'])) ? $data['dl_no1'] : ''; ?></span>

                  </td>
                </tr> 

              </table>
            </div>
            
            <div style="width: 49%; float: left; margin-left: 4px;">
             
              <table  cellpadding="0" cellspacing="0" border="0"  style="line-height:10px;font-size:9px;">
              <tr>     
                <td class="sub1">
                 Invoice No : <?php echo (isset($data['invoice_no'])) ? $data['invoice_no'] : ''; ?>
               </td>
               <td class="sub1" style="float:right;">
                 Dated : <?php echo (isset($data['invoice_date']) && $data['invoice_date'] != '') ? date('d-M-Y',strtotime($data['invoice_date'])) : ''; ?>
               </td>
             </tr>

             <tr>
              <td class="sub1">
                CHALLAN NO.  : 1234
              </td>
              <td class="sub1" style="float:right;">
               Dated : 10/12/2018
             </td>
           </tr>

           <tr>
            <td class="sub1">
             TRANSPORT  : <?php echo (isset($data['transportname'])) ? $data['transportname'] : ''; ?>
           </td>
         </tr>


         <tr>
          <td class="sub1">
           LR.NO & DATE   : <?php echo (isset($data['lr_no'])) ? $data['lr_no'] : ''; ?> , <?php echo (isset($data['lr_date']) && $data['lr_date'] != '') ? date('d-M-Y',strtotime($data['lr_date'])) : ''; ?>
         </td>
       </tr>

       <tr>
        <td class="sub1">
          <b> DUE DATE   : 10/12/2018 </b>
        </td>
      </tr>

      <tr>
        <td class="sub1">
          <b> NARRATION   : 10/12/2018 </b>
        </td>
      </tr>

    </table>


            </div>
            </td>
</tr>

<tr class="">
 <td colspan="3" style="height:3px;"></td>
</tr>

<!----------------------------------------------FOR PRODUCT SECTION START-------------------------------------------->
<tr class="top">
  <td colspan="3" style="padding:0px;border-bottom:none;">
   <table cellpadding="10" cellspacing="0" border="1" style="border: 0px solid;font-size:8px;line-height:16px;" frame="below" rules="cols" >					
    <tr class="heading" align="center" style="font-size: 12px;"> 
     <td style="width:10px;" rowspan="2">Sr. No.</td>
     <td rowspan="2" style="width:100px;">Prouduct Name</td>
     <td rowspan="2">MFG Co.</td>
     <td rowspan="2">HSN</td>
     <td rowspan="2">MRP</td>
     <td rowspan="2">Batch No.</td>
     <td rowspan="2">Expiry</td>
     <td rowspan="2">Qty</td>
     <td rowspan="2">Free Qty</td>
     <td rowspan="2">Rate</td>
     <td rowspan="2">Per</td>
     <td rowspan="2">Dis %</td>
     <td rowspan="2">GST %</td>
     <td rowspan="2" align="center">Amount</td>                            
   </tr>


   <tbody style="margin-top:10px; height:100px;font-size: 12px;" >

   <?php if(isset($data['tax_billing_detail']) && !empty($data['tax_billing_detail'])){ ?>
    <?php 
    $total_qty = 0;$total_free_qty = 0;$total_rate = 0;$total_discount = 0;$total_tax_amount = 0;$total_cgst_amount = 0;$total_sgst_amount = 0;$total_igst_amount = 0;$total_amount = 0;
    ?>				

    <?php foreach ($data['tax_billing_detail'] as $key => $value) { ?>
    <tr class="item" style="line-height: 16px;font-size: 12px;">
     <td><?php echo $key+1; ?></td>
     <td><?php echo (isset($value['product_name'])) ?ucfirst(strtolower($value['product_name'])) : ''; ?></td>
     <td><?php echo (isset($value['product_mfg_company'])) ? $value['product_mfg_company'] : ''; ?></td>
     <td><?php echo (isset($value['hsn'])) ? $value['hsn'] : ''; ?></td>
     <td align="right"><?php echo (isset($value['mrp'])) ? $value['mrp'] : ''; ?></td>
     <td><?php echo (isset($value['product_batch_no'])) ? $value['product_batch_no'] : ''; ?> </td>
     <td align="right"><?php echo (isset($value['product_ex_date']) && $value['product_ex_date'] != '') ? date('d/m/Y',strtotime($value['product_ex_date'])) : ''; ?></td>
     <td align="right">
      <?php 
            $qty =  (isset($value['qty']) && $value['qty'] != '') ? $value['qty'] : 0;
            $total_qty += $qty;
            echo round($qty, 2); 
       ?>
     </td>
     <td align="right">
          <?php
            $free_qty = (isset($value['freeqty']) && $value['freeqty'] != '') ? $value['freeqty'] : 0;
            $total_free_qty += $free_qty;
            echo round($free_qty, 2);
          ?>
     </td>
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
            $total_tax_amount += ($amount*$value['gst']/100);
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
         $addrow = 8- $availablerow;
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
     <td></td> 
     <td></td>              
     <td></td> 
   </tr>
     <?php  }
  ?> 
   <!------------------------------------EXTRA ROWS----- MAXIMUM ROW 8-------------END-------------------------- -->


 <tr class="heading" style="background-color:#D6D6D6;border-top:1px solid #181818;line-height:18px;;"> 
   <td colspan="7" style="text-align: center; border: 0;"><b>Sub Total</b></td>
   <td style="text-align: center; border: 0;"><b><?php echo (isset($total_qty)) ? round($total_qty, 2) : 0; ?></b></td>
   <td style="text-align: center; border: 0;"><b><?php echo (isset($total_free_qty)) ? round($total_free_qty, 2) : 0; ?></b></td>
   <td colspan="4" style="text-align: center; border: 0;"><b>Total Tax : <?php echo (isset($total_tax_amount)) ? amount_format(number_format(($total_tax_amount), 2, '.', '')) : ''; ?></b></td>
   <td style="text-align: right;"><b><?php echo (isset($total_amount)) ? amount_format(number_format(($total_amount), 2, '.', '')) : ''; ?></b></td>

 </tr>
 <tr  style="line-height: 18px;"> 
   <td colspan="13" style="text-align: center;">Add : Round off(+)</td>
   <td style="text-align: right;"><?php echo (isset($data['roundoff_amount']) && $data['roundoff_amount'] != '') ? amount_format(number_format((round($data['roundoff_amount'], 2)), 2, '.', '')) : 0; ?></td>
 </tr>
</tbody>

<tr class="heading"  style="font-size: 12px;border-top:1px solid #181818;     line-height: 10px;"> 
  <td colspan="10" style="text-align:left; border: 0;">Rs. <?php echo (isset($data['final_amount']) && $data['final_amount'] != '') ? number_to_word($data['final_amount']) : 'Zero'; ?></td>
  <td colspan="3" style="text-align:left; border: 0;"><b> Grand Total</b></td>
  <td align="center"><br><b><?php echo (isset($data['final_amount']) && $data['final_amount'] != '') ? amount_format(number_format(($data['final_amount']), 2, '.', '')) : 0; ?></b></td>                            
</tr>
<tr>
    <?php $taxablvalue = (isset($total_amount)) ? amount_format(number_format(($total_amount), 2, '.', '')) : '';
          $gstamount1 = $total_tax_amount/2;
          $cgstamount = (isset($gstamount1)) ? amount_format(number_format(($gstamount1), 2, '.', '')) : '';
          $sgstamount = (isset($gstamount1)) ? amount_format(number_format(($gstamount1), 2, '.', '')) : '';
          
    ?>
  <td colspan="14" style="font-size: 13px; line-height:15px;"><b>Supply@12%=<?php echo $taxablvalue;?> CGST=<?php echo $cgstamount;?> SGST=<?php echo $sgstamount;?> Total Supply=<?php echo $taxablvalue;?> CGST=<?php echo $cgstamount;?> SGST=<?php echo $sgstamount;?></b></td>
</tr>

</table>
</td>
</tr>
<!----------------------------------------------FOR PRODUCT SECTION END-------------------------------------------->




<tr class="">
 <td colspan="3" style="height:6px;"></td>
</tr>

<!----------------------------------------------FOR COUNT TOTAL AMOUNT SECTION START-------------------------------------------->


<tr class="top" >
  <td colspan="2">
    <div style="float: left; width: 43%;font-size:13px;">
      <b> Party - <?php echo (isset($data['final_amount']) && $data['final_amount'] != '') ? amount_format(number_format(($data['final_amount']), 2, '.', '')) : 0; ?></b> 
    </div>

    <div style="float: left; width: 57%;">
      <b> TOTAL OUTSTANDING RS.   66239.00 Dr</b> 
    </div>
  </td> 
  <td colspan="1" style="border: 0;">
  </tr>

  <tr class="top" style="line-height: 16px;">
    <td colspan="2">
      <b>* <?php echo (isset($data['pharmacy_bank_detail'][0]['name'])) ? $data['pharmacy_bank_detail'][0]['name'] : ''; ?> A/C.NO.<?php echo (isset($data['pharmacy_bank_detail'][0]['bank_ac_no'])) ? $data['pharmacy_bank_detail'][0]['bank_ac_no'] : ''; ?> IFSC-<?php echo (isset($data['pharmacy_bank_detail'][0]['ifsc_code'])) ? $data['pharmacy_bank_detail'][0]['ifsc_code'] : ''; ?> *</b> <br>
      * Our risk & responsibity cease on delivery of goods to the carries at AHMEDABAD <br>
      * Subject to 'AHMEDABAD' Jurisdiction only. * <br> 
    </td>
    <td colspan="1" style="border: 0;">
      <br><br> <center><b>FOR, <?php echo (isset($data['pharmacy']['pharmacy_name'])) ? $data['pharmacy']['pharmacy_name'] : ''; ?> </b></center>
    </td>
  </tr>



<!--</tr>-->

</table>
<!--</td>-->
<!--</tr>-->
<!----------------------------------------------FOR COUNT TOTAL AMOUNT SECTION END-------------------------------------------->

<!--</table>-->
</div>
</page>
</body>
</html>
<script>//window.close();</script>