<?php $title = "Trading Account Report"; ?>
<?php include('include/usertypecheck.php'); ?>
<?php //include('include/permission.php'); ?>
<?php

$from = (isset($_GET['from']) && $_GET['from'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_GET['from']))) : '';
  $to = (isset($_GET['to']) && $_GET['to'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_GET['to']))) : '';
$pharmacy = getPharmacyDetail();
 $ProfitLoss = profit_loss($from ,$to );

$financial_year =   (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';
$pharmacy_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : '';  
$financial_query = mysqli_fetch_assoc(mysqli_query($conn,'SELECT * FROM `financial` WHERE id = "'.$financial_year.'" AND status = 1'));
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Digibooks | Print Trading / Profit Loss Account Report</title>
  <link rel="shortcut icon" href="images/favicon.png" />
  <!-- <link rel="stylesheet" href="plugins/morris/morris.css"> -->
  <!-- App css -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <!-- <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" /> -->
  <style type="text/css">
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

 #customers {
   font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;
   width:99%;
 }

 #customers td, #customers th {
   font-size:1em;
   padding:3px 8px;
 }

 #customers th {
   font-size:1.1em;
   text-align:left;
   color:#000000;
   padding:10px 12px 8px 15px;
 }

 #customers tr td {
   color:#000000;
   font-size:15px !important;
 }

 .table-bordered>tbody>tr>td {
  border: 0px solid #ddd;
}

.table-bordered>thead>tr>th {
  border: 0px solid #ddd;
}

</style>
</head>
<body>

  <table align="center" cellpadding="0" cellspacing="0" border="1" width="100%" id="customers" style="line-height:30px;">
   <thead>
    <tr>
     <th>
      <center>
       <h2 class="title"><strong>
        <?php echo $pharmacy['pharmacy_name'];?><br>
        <span style="font-size:16px;">GST NO : <?php echo (isset($pharmacy['gst_no'])) ? $pharmacy['gst_no'] : 'Unknown GSTNO'; ?></span>
      </strong></h2>
      <h3 class="sub-title"><strong>Trading / Profit & Loss Account Report <br><br>
       Period <?php echo (isset($from) && $from != '') ? date('d,M Y',strtotime($from)) : ''; ?> to <?php echo (isset($to) && $to != '') ? date('d,M Y',strtotime($to)) : ''; ?> 
       </strong> </h3>
      </center>
    </th> 
  </tr>
</thead>
<tbody>
 <tr>
  <td>
    <table class="table table-bordered m-0" >
      <tr>
       <td>
        <table class="table table-bordered m-0">
          <thead>
            <tr>
              <th>Particulars</th>             
              <th style="text-align:right;"><?php echo date('d-M-Y',strtotime(str_replace("/","-",$from))) .' to '.date('d-M-Y',strtotime(str_replace("/","-",$to)));?></th>
            </th>
          </tr>
        </thead>
        <tbody>

          <tr>
           <td>
            <b>Opening Stock</b>                                
          </td> 
          <td style="text-align:right;">
           <b><?php 
            $OpeningStock = $ProfitLoss['left']['OpeningStock'];
             echo amount_format(number_format(($OpeningStock), 2, '.', ''));
            ?> 
           </b>                                
          </td> 
        </tr>

        <tr>
         <td>
           <ul class="list-group" style="list-style-type: none;margin-bottom:0px;">
             <li class="list-group" style="margin-left: 20px;"> 
              Stock                     
            </li>
          </ul>                 
        </td>
        <td>
         <ul class="list-group" style="list-style-type: none;margin-bottom:0px;">
           <li class="list-group" style="text-align:right;margin-bottom:5px; margin-right:58%; border-bottom:1px solid">
            <?php 
            $OpeningStock =  $ProfitLoss['left']['OpeningStock'];
            echo amount_format(number_format(($OpeningStock), 2, '.', ''));
            ?>                   
          </li> 
        </ul>
      </td>
    </tr>

    <tr>
     <td>
       <b>Purchase Accounts</b>                                
     </td>   
     <td style="text-align:right;">
       <b><?php 
       $Purchase = $ProfitLoss['left']['PurchaseAccounts']['total_amount']; 
       echo amount_format(number_format(($Purchase), 2, '.', ''));
       ?>   </b>                                
     </td>                              
   </tr>

   <tr>
    <td>
      <ul class="list-group" style="list-style-type: none;margin-bottom:0px;">
        <li class="list-group" style="margin-left: 20px;">
          Purchase
        </li>
      </ul>
    </td>
    <td>
     <ul class="list-group" style="list-style-type: none;margin-bottom:0px;">
       <li class="list-group" style="text-align:right;margin-bottom:5px; margin-right:58%; border-bottom:1px solid">
         <?php 
         $Purchase = $ProfitLoss['left']['PurchaseAccounts']['total_amount']; 
         echo amount_format(number_format(($Purchase), 2, '.', ''));
         ?> 
       </li>
     </ul>                 
   </td>
 </tr>

 <tr>
   <td>
     <b>Direct Expenses</b>                                
   </td>
   <td style="text-align:right;">
     <b><?php 
     $TotalDirectExpenses = $ProfitLoss['left']['TotalDirectExpenses']; 
     echo amount_format(number_format(($TotalDirectExpenses), 2, '.', ''));
     ?> </b>                                
   </td>                                  
 </tr>

 <tr>
  <td>
   <ul class="list-group" style="list-style-type: none;margin-bottom:0px;">
    <?php 
    $DirectIncomeArr= $ProfitLoss['left']['DirectExpenses']; 
    foreach ($DirectIncomeArr as $value){
     ?>
     <li class="list-group" style="margin-bottom:5px; margin-left: 20px;">
      <?php echo $value['name'];?>                             
    </li>
  <?php  }?>
</ul>                             
</td>
<td>
 <ul class="list-group" style="list-style-type: none;margin-bottom:0px;">
   <?php foreach ($DirectIncomeArr as $key => $value){
     ?>
     <li class="list-group" style="text-align:right;margin-right:58%;<?php echo ($key == (count($DirectIncomeArr) - 1)) ? 'border-bottom:1px solid;' : ''; ?>">
      <?php echo amount_format(number_format(($value['expense']), 2, '.', ''));
      ?>   
    </li>
  <?php  }?>
</ul>
</td>
</tr>                 

<tr>
 <td>
  <ul class="list-group">
   <?php  $GrossProfit = $ProfitLoss['profit']['Grossprofit']; 
        if($GrossProfit >= 0){ ?>
            Gross Profit c/o       
    <?php } else { ?>
        Gross Loss c/o    
    <?php }  ?>    
 </ul>                          
</td>
<td style="text-align:right;">
  <ul>
   <b> <?php 
   $GrossProfit = $ProfitLoss['profit']['Grossprofit']; 
   echo amount_format(number_format(($GrossProfit), 2, '.', '')); 
   ?></b>
 </ul></td>                                  
</tr>

<tr>
 <td colspan="2" style="text-align:right; ">
   <b style="border-bottom:1px solid; border-top: 1px solid;"><?php 
   $Total =  $ProfitLoss['profit']['Total']; 
   echo amount_format(number_format(($Total), 2, '.', '')); ?>
 </b>                               
</td>                                
</tr>

<tr>                                                
 <td>
   <b>Indirect Expenses</b>                                
 </td> 
 <td style="text-align:right;">
   <b><?php 
   $TotalIndirectExpenses = $ProfitLoss['left']['TotalIndirectExpenses']; 
   echo amount_format(number_format(($TotalIndirectExpenses), 2, '.', ''));
   ?>
 </b>                                
</td>                                
</tr>

<tr>
  <td>
    <ul class="list-group" style="list-style-type: none;margin-bottom:0px;">
      <?php  $IndirectExpensesArr = $ProfitLoss['left']['IndirectExpenses'];
      foreach ($IndirectExpensesArr as $value){
       ?>

       <li class="list-group" style="margin-bottom:5px; margin-left: 20px;">
         <?php echo $value['name'];?>  
       </li>

     <?php  }?>
   </ul>                          
 </td>
 <td>
  <ul class="list-group" style="list-style-type: none;margin-bottom:0px;">
   <?php foreach ($IndirectExpensesArr as $key => $value){
     ?>
     <li class="list-group" style="text-align:right;margin-right:58%;<?php echo ($key == (count($IndirectExpensesArr) - 1)) ? 'border-bottom:1px solid;' : ''; ?>">
       <?php echo amount_format(number_format(($value['expense']), 2, '.', ''));

       ?>   
     </li>
   <?php  }?>
 </ul>
</td>
</tr>

<tr>
  <td>
    <ul class="list-group">
    <?php  $netProfit = $ProfitLoss['profit']['NetProfit']; 
                       if($netProfit >= 0){ ?>
                        NET PROFIT       
                         <?php } else { ?>
                         NET LOSS  
                        <?php }  ?> 
   </ul>                          
 </td>
 <td style="text-align:right;">
  <ul class="list-group">
   <b><?php $netProfit = $ProfitLoss['profit']['NetProfit'];
   echo amount_format(number_format($netProfit, 2, '.', ''));
   ?> </b>
 </ul>                                  
</td>
</tr>

</tbody>
</table>
</td>
<td>
  <table class="table table-bordered m-0">
    <thead>
      <tr>
        <th>Particulars</th>
         <th style="text-align:right;"><?php echo date('d-M-Y',strtotime(str_replace("/","-",$from))) .' to '.date('d-M-Y',strtotime(str_replace("/","-",$to)));?></th>
      </tr>
    </thead>

    <tbody>
     <tr>
       <td >
         <b>Sales Accounts</b>                                
       </td>   
       <td style="text-align:right;">
         <b><?php 
         $sales = $ProfitLoss['right']['SaleAccount']['total_amount'];
         echo amount_format(number_format(($sales), 2, '.', ''));
         ?>
       </b>                                
       </td>                              
     </tr>

     <tr>
      <td>
       <ul class="list-group" style="list-style-type: none;margin-bottom:0px;">
        <li class="list-group" style="margin-left: 20px;">
         Sales                                      
       </li>
     </ul>
   </td>
   <td>
    <ul class="list-group" style="list-style-type: none;margin-bottom:0px;">
     <li class="list-group" style="text-align:right;margin-bottom:5px; margin-right:58%; border-bottom:1px solid">
      <?php 
      $sales = $ProfitLoss['right']['SaleAccount']['total_amount'];
      echo amount_format(number_format(($sales), 2, '.', ''));  
      ?>                      
    </li>
  </ul>
</td>
</tr>

<tr>
 <td >
   <b>Direct Incomes</b>                                
 </td>
 <td style="text-align:right;">
   <b><?php 
   $TotalDirectIncome = $ProfitLoss['right']['TotalDirectIncome']; 
   echo amount_format(number_format(($TotalDirectIncome), 2, '.', ''));
   ?>
 </b>                                
</td>                                  
</tr>

<tr>
  <td>
   <ul class="list-group" style="list-style-type: none;margin-bottom:0px;">
    <?php 
    $DirectIncomeArr= $ProfitLoss['right']['DirectIncome']; 
    foreach ($DirectIncomeArr as $value){
     ?>
     <li class="list-group" style="margin-bottom:5px; margin-left: 20px;">
       <?php echo $value['name'];?>  
     </li>
   <?php  }?>
 </ul>                     
</td>
<td>
 <ul class="list-group" style="list-style-type: none;margin-bottom:0px;">
   <?php foreach ($DirectIncomeArr as $key => $value){
     ?>
     <li class="list-group" style="text-align:right;margin-right:58%;<?php echo ($key == (count($DirectIncomeArr) - 1)) ? 'border-bottom:1px solid;' : ''; ?>">
      <?php echo amount_format(number_format(($value['expense']), 2, '.', ''));

      ?>   
    </li>
  <?php  }?>   
</ul>
</td>
</tr>

<tr>
 <td>
   <b>Closing Stock</b>                                
 </td>   
 <td style="text-align:right;">
   <b><?php 
   $ClosingStock = $ProfitLoss['right']['ClosingStock']; 
   echo amount_format(number_format(($ClosingStock), 2, '.', ''));
   ?> </b>                                
 </td>                               
</tr>
<tr>
  <td>
    <ul class="list-group" style="list-style-type: none;margin-bottom:0px;">
      <li class="list-group" style="margin-left: 20px;">
       Stock                                      
     </li>
   </ul>
 </td>

 <td>
   <ul class="list-group" style="list-style-type: none;margin-bottom:0px;">
     <li class="list-group" style="text-align:right;margin-bottom:5px; margin-right:58%; border-bottom:1px solid">
      <?php 
      $ClosingStock =  $ProfitLoss['right']['ClosingStock'];
      echo amount_format(number_format(($ClosingStock), 2, '.', ''));
      ?>                      
    </li>
  </ul>
</td>
</tr>

<tr>
 <td colspan="2" style="text-align:right; ">
   <b style="border-bottom:1px solid; border-top: 1px solid;"><?php 
   $Total =  $ProfitLoss['profit']['Total']; 
   echo amount_format(number_format(($Total), 2, '.', '')); ?>
 </b>                               
</td>                                
</tr>

<tr>
 <td>
  <ul class="list-group">
   <?php  $GrossProfit = $ProfitLoss['profit']['Grossprofit']; 
         if($GrossProfit >= 0){ ?>
           Gross Profit b/f       
         <?php } else { ?>
        Gross Loss b/f    
      <?php }  ?>  
 </ul>                          
</td>
<td style="text-align:right;">
  <ul>
    <b>  <?php   
    $GrossProfit = $ProfitLoss['profit']['Grossprofit']; 
    echo amount_format(number_format(($GrossProfit), 2, '.', ''));  ?></b>
  </ul></td>                                  
</tr>
<tr>
 <td >
   <b>Indirect Incomes</b>                                
 </td>
 <td style="text-align:right;">
   <b>
    <?php 
    $TotalInDirectIncome = $ProfitLoss['right']['TotalInDirectIncome']; 
    echo amount_format(number_format(($TotalInDirectIncome), 2, '.', ''));
    ?> </b>                                
  </td>                                  
</tr>

<tr>
  <td>
    <ul class="list-group" style="list-style-type: none;margin-bottom:0px;">
      <?php 
      $IndirectIncome= $ProfitLoss['right']['IndirectIncome']; 
      foreach ($IndirectIncome as $value){

       ?>
       <li class="list-group" style="margin-bottom:5px; margin-left: 20px;">
         <?php echo $value['name'];?></li>                               

       <?php  }?>
     </ul>                            
   </td>
   <td>
    <ul class="list-group" style="list-style-type: none;margin-bottom:0px;">
     <?php foreach ($IndirectIncome as $key => $value){
       ?>
       <li class="list-group" style="text-align:right;margin-right:58%;<?php echo ($key == (count($IndirectIncome) - 1)) ? 'border-bottom:1px solid;' : ''; ?>">
         <?php echo amount_format(number_format(($value['expense']), 2, '.', ''));
         
         ?>   </li>

       <?php  }?>
     </ul>
   </td>
 </tr>

</tbody>
</table>
</td>
</tr>
<tr>
 <td>
  <ul class="list-group">
   <li class="list-group-item" style="text-align:right;">
    <b>
      Total : <?php 
      $Total =  $ProfitLoss['profit']['Total']; 
      echo amount_format(number_format(($Total), 2, '.', '')); ?>
    </b>
  </li>                   
</ul>                                
</td>

<td>
  <ul class="list-group">
   <li class="list-group-item" style="text-align:right;">
    <b>
     Total :  <?php 
     $Total =  $ProfitLoss['profit']['Total']; 
     echo amount_format(number_format(($Total), 2, '.', '')); ?>
   </b>                     
 </li>
</ul>                                
</td>
</tr>
</table>
</td>
</tr>
</tbody>
</table>

<bR />
<bR />


</body>
</html>

