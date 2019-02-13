<?php include('include/usertypecheck.php');?>
<?php 

$pharmacy_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : '';
$financial_id = (isset($_SESSION['auth']['financial']) && $_SESSION['auth']['financial'] != '') ? $_SESSION['auth']['financial'] : '';
$pharmacy = getPharmacyDetail();

$first_day_this_month = (isset($_GET['from']) && $_GET['from'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_GET['from']))) : ''; 
$last_day_this_month = (isset($_GET['to']) && $_GET['to'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_GET['to']))) : '';


  $outwardtotal = "SELECT SUM(tbd.totalamount) as taxable_value, SUM(((tbd.qty+tbd.freeqty)*tbd.rate)*tbd.igst/100) as total_igst, SUM(((tbd.qty+tbd.freeqty)*tbd.rate)*tbd.cgst/100) as total_cgst, SUM(((tbd.qty+tbd.freeqty)*tbd.rate)*tbd.sgst/100) as total_sgst FROM tax_billing_details tbd INNER JOIN tax_billing tb ON tb.id = tbd.tax_bill_id WHERE tb.pharmacy_id = '".$pharmacy_id."' AND tbd.gst != 0 AND tb.invoice_date >='".$first_day_this_month."' and tb.invoice_date <='".$last_day_this_month."'";
  $outwardR = mysqli_query($conn ,$outwardtotal);
  $outwardL = mysqli_fetch_assoc($outwardR);
  
  $Ccharge = "SELECT tb.id, tb.couriercharge, tb.couriercharge_val, (tb.couriercharge_val*tb.couriercharge/100) as couriergst, st.state_code_gst as state_code FROM `tax_billing` tb INNER JOIN ledger_master lg ON tb.customer_id = lg.id LEFT JOIN own_states st ON lg.state = st.id WHERE tb.couriercharge != 0 AND tb.couriercharge_val != 0 AND tb.pharmacy_id='".$pharmacy_id."'AND tb.invoice_date >='".$first_day_this_month."' and tb.invoice_date <='".$last_day_this_month."'";
  $CchargeR = mysqli_query($conn ,$Ccharge);
  if($CchargeR && mysqli_num_rows($CchargeR) > 0){
      while($CchargeL = mysqli_fetch_assoc($CchargeR)){
          $outwardL['taxable_value'] += $CchargeL['couriercharge_val'];
          if($_SESSION['state_code'] == $CchargeL['state_code']){
              $gst = $CchargeL['couriergst']/2;
              $outwardL['total_cgst'] += $gst;
              $outwardL['total_sgst'] += $gst;
          }else{
              $outwardL['total_igst'] += $CchargeL['couriergst'];
          }
      }
  }
  
  $salereturn = "SELECT SUM(sr.taxable_amount) as taxable_amount, SUM(((srd.qty+srd.free_qty)*srd.rate)*srd.igst/100) as total_Igst , SUM(((srd.qty+srd.free_qty)*srd.rate)*srd.cgst/100) as total_Cgst , SUM(((srd.qty+srd.free_qty)*srd.rate)*srd.sgst/100) as total_Sgst FROM sale_return sr inner join sale_return_details srd on sr.id = srd.sale_return_id WHERE sr.pharmacy_id = '".$pharmacy_id."'  AND srd.gst != 0 and sr.credit_note_date >= '".$first_day_this_month."' and sr.credit_note_date <='".$last_day_this_month."'";
  $salereturnR = mysqli_query($conn ,$salereturn);
  $salereturnRL = mysqli_fetch_assoc($salereturnR);


  /*$OutwardTotal = "SELECT  sum(tbd.qty *tbd.rate) as amount from tax_billing_details tbd inner join tax_billing tb on tbd.tax_bill_id = tb.id inner join ledger_master lm on lm.id = tb.customer_id WHERE tb.invoice_date >='".$first_day_this_month."' and tb.invoice_date <='".$last_day_this_month."' and tb.pharmacy_id ='".$pharmacy_id."' and lm.customer_type ='GST_Regular'";
  $OutwardR = mysqli_query($conn ,$OutwardTotal);
  $OutwardL = mysqli_fetch_assoc($OutwardR);

   $OutwardTotal1 = "SELECT sum(tb.totalcgst) as totalCGST, sum(tb.totalsgst) as totalSGST, sum(tb.totaligst) as totalIGST from  tax_billing tb  inner join ledger_master lm on lm.id = tb.customer_id WHERE tb.invoice_date >='".$first_day_this_month."' and tb.invoice_date <='".$last_day_this_month."' and tb.pharmacy_id ='".$pharmacy_id."' and lm.customer_type ='GST_Regular'";
  $OutwardR1 = mysqli_query($conn ,$OutwardTotal1);
  $OutwardL1 = mysqli_fetch_assoc($OutwardR1);*/


// -------------------------------3.1(b-1)---------------------------------------// 

  $ZeroGST = "SELECT sum(tbd.totalamount) as amount ,tbd.gst from tax_billing_details tbd inner join tax_billing tb WHERE tbd.tax_bill_id = tb.id and tbd.gst = '0' and tb.invoice_date >='".$first_day_this_month."' and tb.invoice_date <='".$last_day_this_month."' and tb.pharmacy_id ='".$pharmacy_id."'";
  $ZeroGSTR = mysqli_query($conn ,$ZeroGST);
  $ZeroGSTL = mysqli_fetch_assoc($ZeroGSTR);
  
   $Zerosalereturn = "SELECT SUM(sr.taxable_amount) as taxable_amount, srd.gst FROM sale_return sr inner join sale_return_details srd  WHERE sr.id = srd.sale_return_id and srd.gst = '0' and sr.pharmacy_id = '".$pharmacy_id."' and sr.credit_note_date >= '".$first_day_this_month."' and sr.credit_note_date <='".$last_day_this_month."'";
  $ZerosalereturnR = mysqli_query($conn ,$Zerosalereturn);
  $ZerosalereturnRL = mysqli_fetch_assoc($ZerosalereturnR);

//--------------------------------3.1(d)------4(b)-------------------------------//

   $InwardSuppliesAmount = "SELECT SUM(pd.qty*pd.f_rate) as amount, (0) as totalIGST, (0) as totalCGST,(0) as totalSGST  FROM purchase_details pd INNER JOIN purchase p ON pd.purchase_id = p.id WHERE  p.pharmacy_id = '".$pharmacy_id."' AND pd.f_igst = 0 AND pd.f_cgst = 0 AND pd.f_sgst = 0 AND p.invoice_date >='".$first_day_this_month."' and p.invoice_date <='".$last_day_this_month."'"; 
   $InwardSuppliesAmountR = mysqli_query($conn ,$InwardSuppliesAmount);
   $InwardSuppliesAmountL = mysqli_fetch_assoc($InwardSuppliesAmountR);
  /*$InwardSuppliesAmount = "SELECT sum(tbd.qty *tbd.rate) as amount, sum(tb.totaligst) as totalIGST ,sum(tb.totalcgst) as totalCGST , sum(tb.totalsgst) as totalSGST from tax_billing_details tbd inner join tax_billing tb on tbd.tax_bill_id = tb.id  inner join ledger_master lm on lm.id = tb.customer_id where tb.invoice_date >='".$first_day_this_month."' and tb.invoice_date <='".$last_day_this_month."' and lm.customer_type = 'GST_unregistered' and tb.pharmacy_id ='".$pharmacy_id."'";
  $InwardSuppliesAmountR = mysqli_query($conn ,$InwardSuppliesAmount);
  $InwardSuppliesAmountL = mysqli_fetch_assoc($InwardSuppliesAmountR);*/
  
  $ISpurreturn = "SELECT sum(totalamount) as amount ,(0) as igst, (0) as cgst,(0) as sgst from purchase_return where pharmacy_id ='".$pharmacy_id."' and debit_note_date >='".$first_day_this_month."' and debit_note_date <='".$last_day_this_month."' and igst = 0 and cgst = 0 and sgst = 0";
  $ISpurreturnR = mysqli_query($conn ,$ISpurreturn);
   $ISpurreturnL = mysqli_fetch_assoc($ISpurreturnR);
  
// -------------------------------3.1(e-1)---------------------------------------//
  
    $nongst = "select sum(mrp) as amount from product_master where gst_id= '1' and pharmacy_id ='".$pharmacy_id."' and CAST(created_at as DATE) >= '".$first_day_this_month."' and CAST(created_at as DATE) <= '".$last_day_this_month."'";
    $nongstR = mysqli_query($conn ,$nongst);
    $nongstL = mysqli_fetch_assoc($nongstR); 
  
//------------------------------------3.2(a)--------------------------------//
   $Unregistr = "SELECT sum(tbd.totalamount) as amount, SUM(((tbd.qty+tbd.freeqty)*tbd.rate)*tbd.igst/100) as totalIGST from tax_billing_details tbd inner join tax_billing tb on tbd.tax_bill_id = tb.id  inner join ledger_master lm on lm.id = tb.customer_id inner join own_states os on lm.state = os.id where os.state_code_gst != '".$_SESSION['state_code']."' AND tb.invoice_date >='".$first_day_this_month."' and tb.invoice_date <='".$last_day_this_month."' and lm.customer_type = 'GST_unregistered' and tb.pharmacy_id ='".$pharmacy_id."'"; 
//   $Unregistr = "SELECT sum(tbd.qty *tbd.rate) as amount, sum(tb.totaligst) as totalIGST from tax_billing_details tbd inner join tax_billing tb on tbd.tax_bill_id = tb.id  inner join ledger_master lm on lm.id = tb.customer_id where tb.invoice_date >='".$first_day_this_month."' and tb.invoice_date <='".$last_day_this_month."' and lm.customer_type = 'GST_unregistered' and tb.pharmacy_id ='".$pharmacy_id."'";
  $UnregistrR = mysqli_query($conn ,$Unregistr);
  $UnregistrL = mysqli_fetch_assoc($UnregistrR);

  $unregsalereturn = "SELECT sum(pr.totalamount) as amount , sum(pr.igst) as igst  FROM purchase_return pr INNER JOIN purchase_return_detail prd on pr.id = prd.pr_id INNER JOIN ledger_master lm on lm.id = pr.vendor_id INNER JOIN own_states os ON os.id = lm.state WHERE os.state_code_gst != '".$_SESSION['state_code']."'  and pr.debit_note_date >='".$first_day_this_month."' and lm.customer_type = 'GST_unregistered' and pr.debit_note_date <='".$last_day_this_month."' and pr.pharmacy_id ='".$pharmacy_id."'";
  $unregsalereturnR = mysqli_query($conn ,$unregsalereturn);
  $unregsalereturnL = mysqli_fetch_assoc($unregsalereturnR);
 
  
  
  //------------------------------------3.2(b)--------------------------------//
  $Composition = "SELECT sum(tbd.totalamount) as amount, SUM(((tbd.qty+tbd.freeqty)*tbd.rate)*tbd.igst/100) as totalIGST from tax_billing_details tbd inner join tax_billing tb on tbd.tax_bill_id = tb.id  inner join ledger_master lm on lm.id = tb.customer_id inner join own_states os on lm.state = os.id where os.state_code_gst != '".$_SESSION['state_code']."' AND tb.invoice_date >='".$first_day_this_month."' and tb.invoice_date <='".$last_day_this_month."' and lm.customer_type = 'GST_Composition' and tb.pharmacy_id ='".$pharmacy_id."'";
  $CompositionR = mysqli_query($conn ,$Composition);
  $CompositionL = mysqli_fetch_assoc($CompositionR);

 $compsalereturn = "SELECT sum(pr.totalamount) as amount , sum(pr.igst) as igst  FROM purchase_return pr INNER JOIN purchase_return_detail prd on pr.id = prd.pr_id INNER JOIN ledger_master lm on lm.id = pr.vendor_id INNER JOIN own_states os ON os.id = lm.state WHERE os.state_code_gst != '".$_SESSION['state_code']."'  and pr.debit_note_date >='".$first_day_this_month."' and lm.customer_type = 'GST_Composition' and pr.debit_note_date <='".$last_day_this_month."' and pr.pharmacy_id ='".$pharmacy_id."'";
  $compsalereturnR = mysqli_query($conn ,$compsalereturn);
  $compsalereturnL = mysqli_fetch_assoc($compsalereturnR);
  
  
//------------------------------------3.2(c)--------------------------------//

//  $holders = "SELECT sum(tbd.totalamount) as amount, SUM(((tbd.qty+tbd.freeqty)*tbd.rate)*tbd.igst/100) as totalIGST from tax_billing_details tbd inner join tax_billing tb on tbd.tax_bill_id = tb.id  inner join ledger_master lm on lm.id = tb.customer_id inner join own_states os on lm.state = os.id where os.state_code_gst != '".$_SESSION['state_code']."' AND tb.invoice_date >='".$first_day_this_month."' and tb.invoice_date <='".$last_day_this_month."' and lm.customer_type = 'GST_unregistered' and tb.pharmacy_id ='".$pharmacy_id."'";
//  $holdersR = mysqli_query($conn ,$holders);
//  $holdersL = mysqli_fetch_assoc($holdersR);
   
// -------------------------------4(a-1)---------------------------------------// 

  $IntegratedTax ="SELECT sum(total_igst) as IgstAmount FROM purchase WHERE vouchar_date >='".$first_day_this_month."' and vouchar_date <='".$last_day_this_month."' and pharmacy_id ='".$pharmacy_id."' ORDER BY total_igst";
  $IntegratedTaxR = mysqli_query($conn ,$IntegratedTax);
  $IntegratedTaxL = mysqli_fetch_assoc($IntegratedTaxR);
  
  $ITsalereturn = "SELECT SUM(igst) as totalIgst FROM purchase_return WHERE pharmacy_id = '".$pharmacy_id."' and debit_note_date >= '".$first_day_this_month."' and debit_note_date <='".$last_day_this_month."' ORDER BY igst";
  $ITsalereturnR = mysqli_query($conn ,$ITsalereturn);
  $ITsalereturnRL = mysqli_fetch_assoc($ITsalereturnR);

  // -------------------------------4(a-2)---------------------------------------// 

  $CentralTax ="SELECT sum(total_cgst) as CgstAmount FROM purchase WHERE vouchar_date >='".$first_day_this_month."' and vouchar_date <='".$last_day_this_month."' and pharmacy_id ='".$pharmacy_id."' ORDER BY total_cgst";
  $CentralTaxR = mysqli_query($conn ,$CentralTax);
  $CentralTaxL = mysqli_fetch_assoc($CentralTaxR);

   $CTsalereturn = "SELECT SUM(cgst) as totalCgst FROM purchase_return WHERE pharmacy_id = '".$pharmacy_id."' and debit_note_date >= '".$first_day_this_month."' and debit_note_date <='".$last_day_this_month."' ORDER BY cgst";
  $CTsalereturnR = mysqli_query($conn ,$CTsalereturn);
  $CTsalereturnRL = mysqli_fetch_assoc($CTsalereturnR);
  // -------------------------------4(a-3)---------------------------------------// 

  $StateTax ="SELECT sum(total_sgst) as SgstAmount FROM purchase WHERE vouchar_date >='".$first_day_this_month."' and vouchar_date <='".$last_day_this_month."' and pharmacy_id ='".$pharmacy_id."' ORDER BY total_sgst";
  $StateTaxR = mysqli_query($conn ,$StateTax);
  $StateTaxL = mysqli_fetch_assoc($StateTaxR);
  
  $STsalereturn = "SELECT SUM(sgst) as totalSgst FROM purchase_return WHERE pharmacy_id = '".$pharmacy_id."' and debit_note_date >= '".$first_day_this_month."' and debit_note_date <='".$last_day_this_month."' ORDER BY sgst";
  $STsalereturnR = mysqli_query($conn ,$STsalereturn);
  $STsalereturnRL = mysqli_fetch_assoc($STsalereturnR);
  
  //----------------------------------5(2 -INTRASTAET)----------------------------------------//
  
  $intrastatesupply = "SELECT sum(pd.qty* pd.rate) as amount FROM purchase_details pd INNER JOIN purchase p on p.id = pd.purchase_id INNER JOIN product_master pm on pm.id = pd.product_id INNER JOIN ledger_master lm on lm.id = p.vendor INNER JOIN own_states os ON os.id = lm.state WHERE os.state_code_gst = '".$_SESSION['state_code']."' and pm.gst_id = '1' and p.vouchar_date >='".$first_day_this_month."' and p.vouchar_date <='".$last_day_this_month."' and pm.pharmacy_id ='".$pharmacy_id."'"; 
  $intrastatesupplyR = mysqli_query($conn ,$intrastatesupply);
  $intrastatesupplyL = mysqli_fetch_assoc($intrastatesupplyR);
  
  $intrasalereturn = "SELECT sum(pr.totalamount) as amount FROM purchase_return pr INNER JOIN purchase_return_detail prd on pr.id = prd.pr_id INNER JOIN product_master pm on pm.id = prd.product_id INNER JOIN ledger_master lm on lm.id = pr.vendor_id INNER JOIN own_states os ON os.id = lm.state WHERE os.state_code_gst = '".$_SESSION['state_code']."' and pm.gst_id = '1' and pr.debit_note_date >='".$first_day_this_month."' and  pr.debit_note_date <='".$last_day_this_month."' and pr.pharmacy_id ='".$pharmacy_id."'"; 
  $intrasalereturnR = mysqli_query($conn ,$intrasalereturn);
  $intrasalereturnRL = mysqli_fetch_assoc($intrasalereturnR);

 //----------------------------------5(2 -InterSTAET)----------------------------------------//
  
  $interstatesupply = "SELECT sum(pd.qty* pd.rate) as amount FROM purchase_details pd INNER JOIN purchase p on p.id = pd.purchase_id INNER JOIN product_master pm on pm.id = pd.product_id INNER JOIN ledger_master lm on lm.id = p.vendor INNER JOIN own_states os ON os.id = lm.state WHERE os.state_code_gst != '".$_SESSION['state_code']."' and pm.gst_id = '1' and p.vouchar_date >='".$first_day_this_month."' and p.vouchar_date <='".$last_day_this_month."' and pm.pharmacy_id ='".$pharmacy_id."'"; 
  $interstatesupplyR = mysqli_query($conn ,$interstatesupply);
  $interstatesupplyL = mysqli_fetch_assoc($interstatesupplyR);    
    
  $intersalereturn = "SELECT sum(pr.totalamount) as amount FROM purchase_return pr INNER JOIN purchase_return_detail prd on pr.id = prd.pr_id INNER JOIN product_master pm on pm.id = prd.product_id INNER JOIN ledger_master lm on lm.id = pr.vendor_id INNER JOIN own_states os ON os.id = lm.state WHERE os.state_code_gst != '".$_SESSION['state_code']."' and pm.gst_id = '1' and pr.debit_note_date >='".$first_day_this_month."' and  pr.debit_note_date <='".$last_day_this_month."' and pr.pharmacy_id ='".$pharmacy_id."'"; 
  $intersalereturnR = mysqli_query($conn ,$intersalereturn);
  $intersalereturnRL = mysqli_fetch_assoc($intersalereturnR);  
?>

<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Digibook | Print GSTR 3B Report</title>
  <style type="text/css">

  table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
  }
  .panel-title {
    font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;
    color:#000000;
    font-size:1.5em;
  }
  .sub-title {
    font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;
    color:#000033;
    font-size:1.1em;
  }
  .customers {
    font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;
    width:99%;
    border-collapse:collapse;
  }
  .customers td, .customers th {
    font-size:1em;
    border:1px solid #046998;
    padding:5px 10px;
  }
  .customers th {
    font-size:1.1em;
    text-align:left;
    color:#000000;
    padding:3px 15px 2px 20px;
  }
  .customers tr td {
    color:#000000;
    font-size:12px !important;
  }
  .secondary{background-color: #DDD9C5;}
  .primary{background-color: #89B3DF;}
  .danger{background-color: #EAB8B7;}
</style>
</head>
<body>
	<center>
		<h3 class="panel-title"><strong> <?php echo (isset($pharmacy['pharmacy_name'])) ? ucwords(strtolower($pharmacy['pharmacy_name'])) : 'Unknown pharmacy'; ?> </strong> <br>
      <span style="font-size:16px;">GST NO : <?php echo (isset($pharmacy['gst_no'])) ? $pharmacy['gst_no'] : 'Unknown GSTNO'; ?></span>
    </h3>
    <table align="center" cellpadding="0" cellspacing="0" border="0" width="100%" id="customers" style="line-height:20px;">
      <tbody>
       <tr>
         <td style="border:none;padding:10px;color:#CC0000;font-size:16px;text-align:center;line-height:20px;">
          <span style="font-size:18px"><b> GSTR 3B Report </b></span> <br>

        </td>
      </tr>
    </tbody>
  </table>

  <h3 class="sub-title"><strong>Period <?php echo (isset($first_day_this_month) && $first_day_this_month != '') ? date('d,M Y',strtotime($first_day_this_month)) : ''; ?> to <?php echo (isset($last_day_this_month) && $last_day_this_month != '') ? date('d,M Y',strtotime($last_day_this_month)) : ''; ?></strong> </h3>
</center>


<!-- <span style="float:right;color:#000000;font-size:12px;margin-top: -30px;  margin-right: 30px;">(All Amounts in Rs.)</span> -->
<table align="right" class="customers" border="1" style="width: 200px; margin-bottom: 50px;line-height:30px;">
  <thead>
   <tr>
    <th class="primary">From</th>
    <td style="width: 200px;"><?php echo date('d-M-Y',strtotime(str_replace("/","-",$_GET['from'])));?></td>
  </tr>
  <tr>
    <th class="primary">To</th>
    <td style="width: 200px;"><?php echo date('d-M-Y',strtotime(str_replace("/","-",$_GET['to'])));?></td>
  </tr>
</thead>
</table>

<table align="center" cellpadding="0" cellspacing="0" border="1" width="100%" class="customers" style="line-height:30px;margin-bottom: 50px;">
  <thead>
    <tr>
      <td width="5%">1</td>
      <td width="25%"><b>GSTINM</b></td>
      <td><?php echo $pharmacy['gst_no'];?></td>
    </tr>
    <tr>
      <td>2</td>
      <td width="25%"><b>Legal name of the registered person</b></td>
      <td><?php echo $pharmacy['pharmacy_name'];?> </td>
    </tr>
  </thead>
</table>

<h4>3.1 Detail of Outward Supplies and Inward supplies liable to reverse charges	</h4>
<table align="center" cellpadding="0" cellspacing="0" border="1" width="100%" class="customers" style="line-height:30px;margin-bottom: 50px;">

  <thead>
    <tr class="primary">
      <th><b>Nature of Supplies</b></th>
      <th><b>Total Taxable Value</b></th>
      <th><b>Integrated Tax</b></th>
      <th><b>Central Tax</b></th>
      <th><b>State/UT Tax</b></th>
      <th><b>Cess</b></th>
    </tr>
    <tr class="secondary">
      <th><b>1</b></th>
      <th><b>2</b></th>
      <th><b>3</b></th>
      <th><b>4</b></th>
      <th><b>5</b></th>
      <th><b>6</b></th>
    </tr>
  </thead>
  <tbody>
   <tr>
    <td>(a) Outward taxable supplies (other than zero rated, nil rated and exempted)</td>
     <td><?php echo (isset($outwardL['taxable_value']) ? number_format(($outwardL['taxable_value']), 2, '.', '') : 0 ) - (isset($salereturnRL['taxable_amount']) ? number_format(($salereturnRL['taxable_amount']), 2, '.', '') : 0 );?></td>
                      <td><?php echo (isset($outwardL['total_igst']) ? number_format(($outwardL['total_igst']), 2, '.', '') : 0 ) - (isset($salereturnRL['total_Igst']) ? number_format(($salereturnRL['total_Igst']), 2, '.', '') : 0 );?></td>
                      <td><?php echo (isset($outwardL['total_cgst']) ? number_format(($outwardL['total_cgst']), 2, '.', '') : 0 ) - (isset($salereturnRL['total_Cgst']) ? number_format(($salereturnRL['total_Cgst']), 2, '.', '') : 0 );?></td>
                      <td><?php echo (isset($outwardL['total_sgst']) ? number_format(($outwardL['total_sgst']), 2, '.', '') : 0 ) - (isset($salereturnRL['total_Sgst']) ? number_format(($salereturnRL['total_Sgst']), 2, '.', '') : 0 );?></td>
                      <td>0</td>
  </tr>
  <tr>
    <td>(b) Outward taxable supplies (zero rated)</td>
    <td><?php echo (isset($ZeroGSTL['amount']) ? number_format(($ZeroGSTL['amount']), 2, '.', '') :0 ) - (isset($ZerosalereturnRL['taxable_amount']) ? number_format(($ZerosalereturnRL['taxable_amount']), 2, '.', '') : 0); ?></td>
    <td>0</td>
    <td>0</td>
    <td>0</td>
    <td>0</td>
  </tr>
  <tr>
    <td>(c) Other outward supplies (Nil rated, exempted)</td>
    <td>-</td>
    <td>-</td>
    <td>-</td>
    <td>-</td>
    <td>-</td>
  </tr>
  <tr>
    <td>(d) Inward supplies (liable to reverse charge)</td>
     <td><?php echo (isset($InwardSuppliesAmountL['amount']) ? number_format(($InwardSuppliesAmountL['amount']), 2, '.', '') : 0 ) - (isset($ISpurreturnL['amount']) ? number_format(($ISpurreturnL['amount']), 2, '.', '') : 0 );?></td>
     <td><?php echo (isset($InwardSuppliesAmountL['totalIGST']) ? number_format(($InwardSuppliesAmountL['totalIGST']), 2, '.', '') : 0 ) - (isset($ISpurreturnL['igst']) ? number_format(($ISpurreturnL['igst']), 2, '.', '') : 0 );?></td>
     <td><?php echo (isset($InwardSuppliesAmountL['totalCGST']) ? number_format(($InwardSuppliesAmountL['totalCGST']), 2, '.', '') : 0 ) - (isset($ISpurreturnL['cgst']) ? number_format(($ISpurreturnL['cgst']), 2, '.', '') : 0 );?></td>
     <td><?php echo (isset($InwardSuppliesAmountL['totalSGST']) ? number_format(($InwardSuppliesAmountL['totalSGST']), 2, '.', '') : 0 ) - (isset($ISpurreturnL['sgst']) ? number_format(($ISpurreturnL['sgst']), 2, '.', '') : 0 );?></td>
     <td>0</td>
  </tr>
  <tr>
    <td>(e) Non-GST outward supplies</td>
    <td><?php echo (isset($nongstL['amount']) ? number_format(($nongstL['amount']), 2, '.', '') : 0); ?></td>
     <td>-</td>
     <td>-</td>
     <td>-</td>
     <td>-</td>
  </tr>
</tbody>
</table>


<h4>3.2 3.2 Of the supplies shown in 3.1 (a) above, details of inter-State supplies made to unregistered persons,												
composition taxable persons and UIN holders	</h4>
<table align="center" cellpadding="0" cellspacing="0" border="1" width="100%" class="customers" style="line-height:30px;margin-bottom: 50px;">
  <thead>
    <tr class="primary">
      <th><b>Nature of Supplies</b></th>
      <th><b>Total Taxable Value</b></th>
      <th><b>Integrated Tax</b></th>
    </tr>
    <tr class="secondary">
      <th><b>1</b></th>
      <th><b>2</b></th>
      <th><b>3</b></th>
    </tr>
  </thead>
  <tbody>
   <tr>
    <td>Supplies made to Unregistered Persons</td>
    <td><?php echo (isset($UnregistrL['amount']) ? number_format(($UnregistrL['amount']), 2, '.', '')  : 0 ) - (isset($unregsalereturnL['amount']) ? number_format(($unregsalereturnL['amount']), 2, '.', '')  : 0 );?></td>
    <td><?php echo (isset($UnregistrL['totalIGST']) ? number_format(($UnregistrL['totalIGST']), 2, '.', '') : 0 ) - (isset($unregsalereturnL['igst']) ? number_format(($unregsalereturnL['igst']), 2, '.', '')  : 0 );?></td>
    </tr>
  <tr>
    <td>Supplies made to Composition Taxable Persons</td>
   <td><?php echo (isset($CompositionL['amount']) ? number_format(($CompositionL['amount']), 2, '.', '') : 0 ) - (isset($compsalereturnL['amount']) ? number_format(($compsalereturnL['amount']), 2, '.', '') : 0 );?></td>
   <td><?php echo (isset($CompositionL['totalIGST']) ? number_format(($CompositionL['totalIGST']), 2, '.', '') : 0 ) - (isset($compsalereturnL['igst']) ? number_format(($compsalereturnL['igst']), 2, '.', '') : 0 );?></td>
   </tr>
  <tr>
    <td>Supplies made to UIN holders</td>
    <td>0.00</td>
    <td>0.00</td>
  </tr>
</tbody>
</table>

<h4>4. Eligible ITC</h4>
<table align="center" cellpadding="0" cellspacing="0" border="1" width="100%" class="customers" style="line-height:30px;margin-bottom: 50px;">
  <thead>
    <tr class="primary">
      <th><b>Details</b></th>
      <th><b>Integrated Tax</b></th>
      <th><b>Central Tax</b></th>
      <th><b>State/UI Tax</b></th>
      <th><b>Cess</b></th>
    </tr>
    <tr class="secondary">
      <th><b>1</b></th>
      <th><b>2</b></th>
      <th><b>3</b></th>
      <th><b>4</b></th>
      <th><b>5</b></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>(A) ITC Available</td>
     <td><?php echo number_format(($IntegratedTaxL['IgstAmount'] - $ITsalereturnRL['totalIgst']), 2, '.', '') ;?></td>
     <td><?php echo number_format(($CentralTaxL['CgstAmount'] - $CTsalereturnRL['totalCgst']), 2, '.', '') ;?></td>
     <td><?php echo number_format(($StateTaxL['SgstAmount'] - $STsalereturnRL['totalSgst']), 2, '.', '') ;?></td>
      <td>0</td>
    </tr>
    <tr>
      <td>(B) ITC Reversed</td>
       <td><?php echo (isset($InwardSuppliesAmountL['totalIGST']) ? number_format(($InwardSuppliesAmountL['totalIGST']), 2, '.', '') : 0 ) -  (isset($ISpurreturnL['igst']) ? number_format(($ISpurreturnL['igst']), 2, '.', '') : 0 );?></td>
      <td><?php echo (isset($InwardSuppliesAmountL['totalCGST']) ? number_format(($InwardSuppliesAmountL['totalCGST']), 2, '.', '') : 0 ) - (isset($ISpurreturnL['cgst']) ? number_format(($ISpurreturnL['cgst']), 2, '.', '') : 0 );?></td>
      <td><?php echo (isset($InwardSuppliesAmountL['totalSGST']) ? number_format(($InwardSuppliesAmountL['totalSGST']), 2, '.', '') : 0 ) - (isset($ISpurreturnL['sgst']) ? number_format(($ISpurreturnL['sgst']), 2, '.', '') : 0 );?></td>
      <td>0</td>
    </tr>
    <tr>
      <td>(C) Net ITC Available (A) – (B)</td>
      <td><?php echo number_format(($IntegratedTaxL['IgstAmount'] - $ITsalereturnRL['totalIgst'] - $InwardSuppliesAmountL['totalIGST']), 2, '.', '');?></td>
      <td><?php echo number_format(($CentralTaxL['CgstAmount'] - $CTsalereturnRL['totalCgst'] - $InwardSuppliesAmountL['totalCGST']), 2, '.', '');?></td>
      <td><?php echo number_format(($StateTaxL['SgstAmount'] - $STsalereturnRL['totalSgst'] - $InwardSuppliesAmountL['totalSGST']), 2, '.', '') ;?></td>
      <td>0</td>
    </tr>
    <tr>
      <td>(D) Ineligible ITC</td>
      <td>-</td>
      <td>-</td>
      <td>-</td>
      <td>-</td>
    </tr>
  </tbody>
</table>

<h4>5. Values of exempt, nil-rated and non-GST inward supplies </h4>
<table align="center" cellpadding="0" cellspacing="0" border="1" width="100%" class="customers" style="line-height:30px;margin-bottom: 50px;">
  <thead>
    <tr class="primary">
      <th><b>Nature of Supplies</b></th>
      <th><b>Inter-state supplies</b></th>
      <th><b>Intra-state supplies</b></th>
    </tr>
    <tr class="secondary">
      <th><b>1</b></th>
      <th><b>2</b></th>
      <th><b>3</b></th>
    </tr>
  </thead>
  <tbody>
   <tr>
    <td>From a supplier under composition scheme, Exempt and Nil rated supply</td>
    <td>0</td>
    <td>0</td>
  </tr>
  <tr>
    <td>Non-GST supply</td>
    <td><?php echo (isset($interstatesupplyL['amount']) ? number_format(($interstatesupplyL['amount']), 2, '.', '') : 0 ) - (isset($intersalereturnRL['amount']) ? number_format(($intersalereturnRL['amount']), 2, '.', '') : 0 );?></td>
    <td><?php echo (isset($intrastatesupplyL['amount']) ? number_format(($intrastatesupplyL['amount']), 2, '.', '') : 0 ) - (isset($intrasalereturnRL['amount']) ? number_format(($intrasalereturnRL['amount']), 2, '.', '') : 0 );?></td>
    </tr>
</tbody>
</table>


<h4>6.1 Payment of Tax</h4>
<table  align="center" cellpadding="0" cellspacing="0" border="1" width="100%" class="customers" style="line-height:30px;margin-bottom: 50px;">

  <tr class="primary">
    <th rowspan="2"><b>Description</b></th>
    <th rowspan="2"><b>Total tax payable</b></th>
    <th colspan="4" style="text-align: center;"><b>Tax paid through ITC</b></th>
    <th rowspan="2"><b>Tax paid TDS/TCS</b></th>
    <th rowspan="2"><b>Tax/Cess paid in cash</b></th>
    <th rowspan="2"><b>Interest paid in cash</b></th>
    <th rowspan="2"><b>Late fee paid in cash</b></th>
  </tr>
  <tr class="primary">
    <th><b>Integrated Tax</b></th>
    <th><b>Central Tax</b></th>
    <th><b>State/UT Tax</b></th>
    <th><b>Cess</b></th>
  </tr>
  <tr class="secondary">
    <th><b>1</b></th>
    <th><b>2</b></th>
    <th><b>3</b></th>
    <th><b>4</b></th>
    <th><b>5</b></th>
    <th><b>6</b></th>
    <th><b>7</b></th>
    <th><b>8</b></th>
    <th><b>9</b></th>
    <th><b>10</b></th>
  </tr>            
  <tbody>
   <tr class="secondary">
    <td colspan="10"><b>Other than reverse charge</b></td>
  </tr>
  <tr>
    <td style="color: red;">Integrated Tax</td>
    <td class="danger"></td>
     <td class="danger"></td>
      <td class="danger"></td>
      <td class="danger"></td>
      <td class="danger"></td>
      <td class="danger"></td>
      <td class="danger"></td>
      <td class="danger"></td>
      <td class="danger"></td>
  </tr>
  <tr>
    <td style="color: red;">Central Tax</td>
      <td class="danger"></td>
     <td class="danger"></td>
      <td class="danger"></td>
      <td class="danger"></td>
      <td class="danger"></td>
      <td class="danger"></td>
      <td class="danger"></td>
     <td class="danger"></td>
      <td class="danger"></td>
  </tr>
  <tr>
     <td style="color: red;">State/UT Tax</td>
      <td class="danger"></td>
      <td class="danger"></td>
      <td class="danger"></td>
      <td class="danger"></td>
      <td class="danger"></td>
      <td class="danger"></td>
      <td class="danger"></td>
      <td class="danger"></td>
      <td class="danger"></td>
  </tr>
  <tr>
    <td style="color: red;">Cess</td>
      <td class="danger"></td>
      <td class="danger"></td>
      <td class="danger"></td>
      <td class="danger"></td>
      <td class="danger"></td>
      <td class="danger"></td>
      <td class="danger"></td>
      <td class="danger"></td>
      <td class="danger"></td>
  </tr>

  <tr>
    <td class="secondary" colspan="10"><b>Reverse charge</b></td>
  </tr>
  <tr>
    <td style="color: red;">Integrated Tax</td>
    <td class="danger"></td>
      <td class="danger"></td>
      <td class="danger"></td>
      <td class="danger"></td>
      <td class="danger"></td>
      <td class="danger"></td>
      <td class="danger"></td>
      <td class="danger"></td>
      <td class="danger"></td>
  </tr>
  <tr>
    <td style="color: red;">Central Tax</td>
     <td class="danger"></td>
      <td class="danger"></td>
      <td class="danger"></td>
      <td class="danger"></td>
      <td class="danger"></td>
      <td class="danger"></td>
      <td class="danger"></td>
      <td class="danger"></td>
      <td class="danger"></td>
  </tr>
  <tr>
    <td style="color: red;">State/UT Tax</td>
     <td class="danger"></td>
      <td class="danger"></td>
      <td class="danger"></td>
      <td class="danger"></td>
      <td class="danger"></td>
      <td class="danger"></td>
      <td class="danger"></td>
      <td class="danger"></td>
      <td class="danger"></td>
  </tr>
  <tr>
    <td style="color: red;">Cess</td>
    <td class="danger"></td>
      <td class="danger"></td>
      <td class="danger"></td>
      <td class="danger"></td>
      <td class="danger"></td>
      <td class="danger"></td>
      <td class="danger"></td>
      <td class="danger"></td>
      <td class="danger"></td>
  </tr>


</tbody>
</table>

 <h4>6.2 TDS/TCS Credit</h4>
       <table align="center" cellpadding="0" cellspacing="0" border="1" width="100%" class="customers" style="line-height:30px;margin-bottom: 50px;">
        <thead>
          <tr class="primary">
            <th><b>Details</b></th>
            <th><b>Integrated Tax</b></th>
            <th><b>Central Tax</b></th>
            <th><b>State/UI Tax</b></th>
          </tr>
          <tr class="secondary">
    <th><b>1</b></th>
    <th><b>2</b></th>
    <th><b>3</b></th>
    <th><b>4</b></th>
    </tr>            
        </thead>
        <tbody>
         <tr>
    <td style="color: red;">TDS</td>
    <td class="danger">0</td>
    <td class="danger">0</td>
    <td class="danger">0</td>
  </tr>
  <tr>
    <td style="color: red;">TCS</td>
    <td class="danger">0</td>
    <td class="danger">0</td>
    <td class="danger">0</td>
  </tr>
      </tbody>
    </table>
  

</body>
</html>