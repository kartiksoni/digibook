<?php $title = "Trading Account Report"; ?>
<?php include('include/usertypecheck.php'); ?>
<?php //include('include/permission.php'); ?>
<?php

$financial_year = (isset($_GET['yearid'])) ? $_GET['yearid'] : '';
$pharmacy_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : '';  
$financial_query = mysqli_fetch_assoc(mysqli_query($conn,'SELECT * FROM `financial` WHERE id = "'.$financial_year.'" AND status = 1'));

$from = date('d/m/Y',strtotime($financial_query['start_date']));
$to =  date('d/m/Y',strtotime($financial_query['end_date']));

$taccount = mysqli_fetch_assoc(mysqli_query($conn,'SELECT * FROM `trading_account` WHERE financial_year = "'.$financial_year.'" AND status = 1'));
$opening_balance = $taccount['opening_balance'];
$closing_balance = $taccount['closing_balance'];

//----------------------------------------------Cash Sales--------------------start----------------------//                    

$qry1 = "SELECT tb.id, SUM(tb.final_amount) as total_amount, tbd.gst FROM tax_billing_details tbd INNER JOIN tax_billing tb ON tbd.tax_bill_id = tb.id WHERE tb.pharmacy_id = ".$pharmacy_id." AND tb.financial_id = ".$financial_year." AND tb.bill_type = 'Cash' GROUP BY tbd.gst"; 
$result1 = mysqli_query($conn,$qry1);
//----------------------------------------------Cash Sales--------------------end----------------------// 

//----------------------------------------------Sales--------------------start----------------------//                    

$qry2 = "SELECT tb.id, SUM(tb.final_amount) as total_amount, tbd.gst FROM tax_billing_details tbd INNER JOIN tax_billing tb ON tbd.tax_bill_id = tb.id WHERE tb.pharmacy_id = ".$pharmacy_id." AND tb.financial_id = ".$financial_year." GROUP BY tbd.gst";  
$result2 = mysqli_query($conn,$qry2);
//----------------------------------------------Sales--------------------end----------------------//  

//----------------------------------------------OGS Sales--------------------start----------------------//               

$qry3 = "SELECT tb.id, SUM(tb.final_amount) as total_amount, tbd.igst FROM tax_billing_details tbd INNER JOIN tax_billing tb ON tbd.tax_bill_id = tb.id WHERE tb.pharmacy_id = ".$pharmacy_id." AND tb.financial_id = ".$financial_year." GROUP BY tbd.igst";  
$result3 = mysqli_query($conn,$qry3);
//----------------------------------------------OGS Sales--------------------end----------------------// 

//----------------------------------------------OGS PURCHASE--------------------start----------------------//              
$qry4 = "SELECT p.id, SUM(p.total_total) as total_amount, pd.f_igst FROM purchase_details pd INNER JOIN  purchase p ON pd.purchase_id = p.id WHERE p.pharmacy_id = ".$pharmacy_id." AND p.financial_id = ".$financial_year."  GROUP BY pd.f_igst";  
$result4 = mysqli_query($conn,$qry4);
//----------------------------------------------OGS PURCHASE--------------------end----------------------//   

//----------------------------------------------PURCHASE--------------------start----------------------//        
$qry5 = "SELECT p.id, SUM(p.total_total) as total_amount, pd.f_cgst FROM purchase_details pd INNER JOIN  purchase p ON pd.purchase_id = p.id WHERE p.pharmacy_id = ".$pharmacy_id." AND p.financial_id = ".$financial_year."  GROUP BY pd.f_cgst";   
$result5 = mysqli_query($conn,$qry5);
//----------------------------------------------PURCHASE--------------------end----------------------// 

//----------------------------------------------PURCHASE RETURN--------------------start----------------------//        
$qry6 = "SELECT prd.id, SUM(prd.amount) as total_amount FROM purchase_return_detail prd INNER JOIN  purchase_return pr ON prd.pr_id = pr.id WHERE pr.pharmacy_id = ".$pharmacy_id." AND pr.financial_id = '".$financial_year."' "; 
$result6 = mysqli_query($conn,$qry6);
$pr = mysqli_fetch_assoc($result6);
//----------------------------------------------PURCHASE RETURN--------------------end----------------------// 

//----------------------------------------------SALES RETURN--------------------start----------------------//        
$qry7 = "SELECT id , SUM(amount) as total_amount FROM sale_return where financial_id = ".$financial_year." AND pharmacy_id = ".$pharmacy_id." ";
$result7 = mysqli_query($conn,$qry7);
$sr = mysqli_fetch_assoc($result7);
//----------------------------------------------PURCHASE RETURN--------------------end----------------------//

//-----------------------------------------round of sales------------start------------------------ // 
$qry8 = "SELECT SUM(roundoff_amount) as total_amount FROM tax_billing WHERE pharmacy_id = '".$pharmacy_id."' AND financial_id = '".$financial_year."' AND cancel= '1' AND roundoff_amount!= '0' AND roundoff_amount IS NOT NULL"; 
$result8 = mysqli_query($conn,$qry8);
$ros = mysqli_fetch_assoc($result8);  
//---------------------------------------round of sales-----------------------end-------------------------------//


//-----------------------------------------round of purchase------------start------------------------ // 
$qry9 = "SELECT SUM(round_off) as total_amount FROM purchase WHERE pharmacy_id = '".$pharmacy_id."' AND financial_id = '".$financial_year."' AND cancel= '1' AND round_off!= '0' AND round_off IS NOT NULL"; 
$result9 = mysqli_query($conn,$qry9);
$rop = mysqli_fetch_assoc($result9);   

//-----------------------------------round of purchase------------------------------end--------------------------//

//-----------------------------------------credit note-------------------------start------------------------ // 
$qry10 = "SELECT cn.id,SUM(cn.amount) as total_amount FROM credit_note cn INNER JOIN  purchase_return pr ON cn.pr_id = pr.id WHERE pr.pharmacy_id = ".$pharmacy_id." AND pr.financial_id = ".$financial_year." "; 
$result10 = mysqli_query($conn,$qry10);
$cng = mysqli_fetch_assoc($result10);
//-------------------------------------credit note--------------------------------end-------------------------------//

//-------------------------------------------DISCOUNT RECEIVED--------------------start----------------//
$j = 0;
$qry11 = "SELECT id,name FROM ledger_master WHERE group_id = 16 ";
$AllDirctIncm = mysqli_query($conn,$qry11);
$directIncomeArr = [];
while($rows  = mysqli_fetch_assoc($AllDirctIncm)) {

    $totalExpense = 0;

    $cashreceipt = "SELECT SUM(amount) as total_amount FROM accounting_cash_management WHERE perticular = ".$rows['id']." AND financial_id = ".$financial_year." AND pharmacy_id = '".$pharmacy_id."' AND payment_type = 'cash_receipt'"; 
    $rceiptCash = mysqli_query($conn,$cashreceipt);
    $rceiptTotal = mysqli_fetch_assoc($rceiptCash);
    $receipt = $rceiptTotal['total_amount'];
    $totalExpense = $totalExpense + $receipt ; 

    $chequepayment = "SELECT SUM(amount) as total_amount FROM accounting_cheque WHERE perticular = ".$rows['id']." AND financial_id = ".$financial_year." AND pharmacy_id = '".$pharmacy_id."' AND voucher_type = 'payment'";
    $paymentcheq = mysqli_query($conn,$chequepayment);
    $paymentTotal = mysqli_fetch_assoc($paymentcheq);
    $paymentC = $paymentTotal['total_amount'];
    $totalExpense = $totalExpense + $paymentC ;


    $jvQry = "SELECT SUM(debit) as total_amount FROM journal_vouchar_details jvd INNER JOIN journal_vouchar jv ON jvd.voucher_id = jv.id WHERE jvd.particular = ".$rows['id']." AND jv.financial_id = '".$financial_year."' AND jv.pharmacy_id = '".$pharmacy_id."'";
    $resultJv = mysqli_query($conn,$jvQry);
    $jvTotalDebit = mysqli_fetch_assoc($resultJv);
    $jvTotalDebit = $jvTotalDebit['total_amount'];
    $totalExpense = $totalExpense + $jvTotalDebit ;  



    $directIncomeArr[$j]['expense'] = $totalExpense;
    $j++;  
}


$totalSales = 0;
$totalPurchase = 0;
$totalCredit = 0;
$totalDebit = 0;
$total  = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Trading Account Report</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="vendors/iconfonts/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="vendors/iconfonts/puse-icons-feather/feather.css">
    <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="vendors/css/vendor.bundle.addons.css">
    <link rel="stylesheet" href="css/parsley.css">
    <!-- endinject -->

    <!-- plugin css for this page -->
    <link rel="stylesheet" href="vendors/icheck/skins/all.css">

    <!-- plugin css for this page -->
    <link rel="stylesheet" href="vendors/iconfonts/font-awesome/css/font-awesome.min.css" />
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="css/style.css">
    <!-- endinject -->
    <link rel="shortcut icon" href="images/favicon.png" />
    <link rel="stylesheet" href="css/toggle/style.css">
</head>
<body>
    <div class="container-scroller">
        <!-- Topbar -->
        <?php include "include/topbar.php" ?>
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">

            <!-- Right Sidebar -->
            <?php include "include/sidebar-right.php" ?>

            <!-- Left Navigation -->
            <?php include "include/sidebar-nav-left.php" ?>

            <div class="main-panel">
                <div class="content-wrapper">
                    <?php include('include/flash.php'); ?>
                    <span id="errormsg"></span>
                    <div class="row">
                        <div class="col-md-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <!-- Trading Account Report -->
                                    <div class="col mt-3">
                                        <div class="pull-right bg-success color-white p-1"> Financial Year :
                                            <?php if(isset($_GET['yearid'])){
                                                echo $financial_query['f_name'];
                                            }?>
                                        </div>
                                        <h4 class="card-title">Trading Account Report</h4>

                                        <hr class="alert-dark">
                                        <div class="row">
                                            <div class="col-12">

                                                <!--Report Table Start-->
                                                <table class="table table-bordered m-0">
                                                    <tbody>
                                                       <!--calculation-->
                                                       <tr>
                                                        <!--Credit -->
                                                        <td style="vertical-align: initial;">
                                                            <table class="table table-bordered m-0">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Account Name</th>
                                                                        <th style="text-align:right;">Credit</th>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>&nbsp;</th>
                                                                        <th>&nbsp;</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                   <tr>
                                                                       <td colspan="2">
                                                                           <b>Sales</b>                                
                                                                       </td>
                                                                   </tr>
                                                                   <tr>
                                                                    <td>
                                                                       <ul class="list-group">

                                                                        <!--cash sales-->

                                                                        <?php foreach ($result1 as $value){ ?>
                                                                          <li class="list-group-item">
                                                                            <?php echo $value['gst'];?> % Cash Sales								
                                                                        </li>
                                                                    <?php  }?>

                                                                    <!--Other Sales-->
                                                                    <?php foreach ($result2 as $value){ ?>
                                                                      <li class="list-group-item">
                                                                        <?php echo $value['gst'];?> % Sales                                
                                                                    </li>
                                                                <?php  } ?>									

                                                                <!--OGS Sales-->
                                                                <?php foreach ($result3 as $value){ ?>
                                                                  <li class="list-group-item">
                                                                    <?php echo $value['igst'];?> % OGS Sales                                
                                                                </li>
                                                            <?php  } ?>


                                                        </ul>
                                                    </td>
                                                    <td>
                                                       <ul class="list-group">


                                                        <?php foreach ($result1 as $value) {                                           
                                                            ?>
                                                            <li class="list-group-item" style="text-align:right;">
                                                              <a href="all-ledger-details.php?f_id=<?php echo $financial_year ?>& v_id=<?php echo $value['gst'];?>" target="_blank">

                                                                <?php  echo amount_format(number_format($value['total_amount'], 2, '.', ''));

                                                                $totalSales = $totalSales + $value['total_amount'];
                                                                $totalCredit = $totalCredit + $value['total_amount'];
                                                                ?>

                                                            </a>                                                                                    
                                                        </li>
                                                    <?php  } 


                                                    ?>


                                                    <?php foreach ($result2 as $value) {                                           
                                                        ?>
                                                        <li class="list-group-item" style="text-align:right;">
                                                          <a href="all-ledger-details.php?f_id=<?php echo $financial_year ?>&s_id=<?php echo $value['gst'];?>" target="_blank">
                                                            <?php  echo amount_format(number_format($value['total_amount'], 2, '.', ''));
                                                            $totalSales = $totalSales + $value['total_amount'];
                                                            $totalCredit = $totalCredit + $value['total_amount'];

                                                            ?>
                                                        </a>                                                                                    
                                                    </li>
                                                <?php  } 

                                                ?>      

                                                <?php foreach ($result3 as $value) {                                           
                                                    ?>
                                                    <li class="list-group-item" style="text-align:right;">
                                                       <!-- <a href="all-ledger-details.php?f_id=<?php echo $financial_year ?>&ogs_id=<?php echo $value['igst'];?>"> -->
                                                        <?php  echo amount_format(number_format($value['total_amount'], 2, '.', ''));
                                                        $totalSales = $totalSales + $value['total_amount'];
                                                        $totalCredit = $totalCredit + $value['total_amount'];

                                                        ?>
                                                        <!--   </a> -->                                                                                     
                                                    </li>
                                                <?php  } 

                                                ?>  								
                                            </ul>
                                        </td>
                                    </tr>
                                    <tr>
                                       <td colspan="2">
                                           <b>PURCHASE RETURN</b>                                
                                       </td>                                
                                   </tr>
                                   <tr>
                                    <td>
                                       <ul class="list-group">
                                          <li class="list-group-item">
                                             PURCHASE RETURN										
                                         </li>
                                     </ul>                                
                                 </td>
                                 <td>
                                   <ul class="list-group">
                                      <li class="list-group-item" style="text-align:right;">

                                        <?php 


                                        echo amount_format(number_format($pr['total_amount'], 2, '.', ''));	
                                        $totalSales = $totalSales + $pr['total_amount'];
                                        $totalCredit = $totalCredit + $pr['total_amount'];	


                                        ?>						
                                    </li>
                                </ul>                                
                            </td>
                        </tr>
                        <tr>
                           <td colspan="2">
                             <b>DIRECT INCOME</b>                                
                         </td>                                
                     </tr>
                     <tr>
                        <td>
                           <ul class="list-group">
                              <li class="list-group-item">
                                 CREDIT NOTE GST								
                             </li>
                             <li class="list-group-item">
                                 DISCOUNT							
                             </li>
                             <li class="list-group-item">
                                 ROUND OFF SALES								
                             </li>
                             <li class="list-group-item">
                                DISCOUNT RECEIVED					
                            </li>
                        </ul>                                
                    </td>
                    <td>
                       <ul class="list-group">
                          <li class="list-group-item" style="text-align:right;">

                              <?php echo amount_format(number_format($cng['total_amount'], 2, '.', ''));
                              $totalSales = $totalSales + $cng['total_amount'];
                              $totalCredit = $totalCredit + $cng['total_amount'];
                              ?>  

                          </li>
                          <li class="list-group-item" style="text-align:right;">
                             0.00								
                         </li>
                         <li class="list-group-item" style="text-align:right;">
                             <a href="round-off-sales-print.php?financial=<?php echo $financial_year;?>" target="_blank">
                              <?php echo amount_format(number_format($ros['total_amount'], 2, '.', ''));
                              $totalSales = $totalSales + $ros['total_amount'];
                              $totalCredit = $totalCredit + $ros['total_amount'];
                              ?>						

                          </a>
                      </li>
                      <li class="list-group-item" style="text-align:right;">
                        <a href="all-ledger-details.php?group_id=16&f_id=<?php echo $financial_year;?>" target="_blank">
                            <?php 
                            $expense = 0;
                            foreach ($directIncomeArr as $income) {
                               $totalCredit = $totalCredit + $income['expense'];
                               $expense =  $expense + $income['expense'];
                           }
                           echo amount_format(number_format($expense , 2, '.', ''));

                           ?>
                       </a>												
                   </li>
               </ul>                                
           </td>
       </tr>
       <tr>
           <td colspan="2">
               <b>CLOSING STOCK</b>                                
           </td>                                
       </tr>
       <tr>
        <td>
           <ul class="list-group">
              <li class="list-group-item">
                 CLOSING STOCK							
             </li>
         </ul>                                
     </td>
     <td>
       <ul class="list-group">
          <li class="list-group-item" style="text-align:right;">
             <?php echo amount_format(number_format($closing_balance, 2, '.', ''));

             $totalSales = $totalSales +$closing_balance;
             $totalCredit = $totalCredit + $closing_balance;

             ?>								
         </li>
     </ul>                                
 </td>
</tr>
</tbody>
</table>
</td>


<!--Debit-->
<td>
    <table class="table table-bordered m-0">
        <thead>
          <tr>
            <th>Account Name</th>
            <th style="text-align:right;">Debit</th>
        </tr>
        <tr>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
       <tr>
           <td colspan="2"><b>Opening Stock</b></td>
       </tr>
       <tr>

        <td>
           <ul class="list-group">
              <li class="list-group-item">
                 Opening Stock										
             </li>
         </ul>                               									
     </td>

     <td>
       <ul class="list-group">
          <li class="list-group-item" style="text-align:right;">
             <?php echo amount_format(number_format($opening_balance, 2, '.', ''));
             $totalDebit = $totalDebit + $opening_balance;
             $totalPurchase = $totalPurchase + $opening_balance;

             ?>       									
         </li>
     </ul>                               
 </td>
</tr>

<tr>
    <td colspan="2"><b>Purchase</b></td>
</tr>
<tr>
    <td>
       <ul class="list-group">
        <?php foreach ($result4 as $value){

           ?>
           <li class="list-group-item">
            <?php echo $value['f_igst'];?> % OGS PURCHASE                                
        </li>
    <?php  }?>


    <?php foreach ($result5 as $value){                                    
       ?>
       <li class="list-group-item">
        <?php echo $value['f_cgst'];?> % PURCHASE                                
    </li>
<?php  }?>




</ul>                                
</td>

<td>

    <ul class="list-group">


       <?php foreach ($result4 as $value) {                                           
        ?>
        <li class="list-group-item" style="text-align:right;">

            <a href="all-ledger-details.php?f_id=<?php echo $financial_year ?>&p_igs_id=<?php echo $value['f_igst'];?>" target="_blank">

                <?php echo amount_format(number_format($value['total_amount'], 2, '.', ''));

                $totalDebit = $totalDebit + $value['total_amount'];
                $totalPurchase = $totalPurchase + $value['total_amount'];
                ?>
            </a>                                                                                    
        </li>
    <?php  } ?>

    <?php foreach ($result5 as $value) {                                           
        ?>
        <li class="list-group-item" style="text-align:right;">
          <a href="all-ledger-details.php?f_id=<?php echo $financial_year ?>&f_cgs_id=<?php echo $value['f_cgst'];?>" target="_blank">

            <?php echo amount_format(number_format($value['total_amount'], 2, '.', ''));
            $totalDebit = $totalDebit + $value['total_amount'];
            $totalPurchase = $totalPurchase + $value['total_amount'];

            ?>
        </a>                                                                                    
    </li>
<?php  } ?>



</ul>                                
</td>
</tr>

<tr>                            	
    <td colspan="2"><b>SALES RETURN</b></td>
</tr>
<tr>
    <td>
       <ul class="list-group">
          <li class="list-group-item">

             SALES RETURN									
         </li>
     </ul>                                
 </td>

 <td>
    <ul class="list-group">
      <li class="list-group-item" style="text-align:right;">
       <?php echo amount_format(number_format($sr['total_amount'], 2, '.', ''));
       $totalDebit = $totalDebit + $sr['total_amount'];
       $totalPurchase = $totalPurchase + $sr['total_amount'];

       ?>					  		
   </li>
</ul>                                
</td>
</tr>

<tr>
    <td colspan="2"><b>DIRECT EXPENES</b></td>
</tr>
<tr>
    <td>
       <ul class="list-group">
          <li class="list-group-item">
             DEBIT NOTE GST									
         </li>
         <li class="list-group-item">
             ROUND OFF PURCHASE								
         </li>
         <li class="list-group-item">
            TRANSPORTATION CHARGES					
        </li>
    </ul>                                
</td>

<td>
    <ul class="list-group">

     <li class="list-group-item" style="text-align:right;">
         
            0.00
        
    </li>
    <li class="list-group-item" data-toggle="modal" data-target="#myModal" style="cursor: pointer;text-align:right;">
     <a href="round-off-purchase-print.php?financial=<?php echo $financial_year;?>" target="_blank">
         <?php echo amount_format(number_format($rop['total_amount'], 2, '.', ''));
         $totalDebit = $totalDebit + $rop['total_amount'];
       $totalPurchase = $totalPurchase + $rop['total_amount'];


         ?>                
     </a>
 </li>
 <li class="list-group-item" style="text-align:right;">
    
        0.00
    
</li>
</ul>                                
</td>
</tr>
<tr>
    <td colspan="2"><b>GROSS PROFIT</b></td>
</tr>
<tr>
    <td>
       <ul class="list-group">
          <li class="list-group-item">
             GROSS PROFIT									
         </li>
     </ul>                                
 </td>
 <td>
    <ul class="list-group">
      <li class="list-group-item" style="text-align:right;">
         <?php 

         $totalDebit = $totalDebit + $rop['total_amount'];
        //  $totalPurchase = $totalPurchase + $rop['total_amount'];
         $total = $totalCredit - $totalPurchase ;

         echo amount_format(number_format(($total), 2, '.', ''));
         ?>	
          <input type="hidden" value="<?php echo amount_format(number_format(($totalPurchase), 2, '.', ''));?>">		
     </li>
 </ul>                                
</td>
</tr>

</tbody>
</table>
</td>
</tr>

<!--Total sum-->
<tr>
    <td>
        <ul class="list-group">
           <li class="list-group-item" style="text-align:right;">
              <b>

                <?php echo amount_format(($totalCredit), 2, '.', '');?>

            </b>
        </li>
    </ul>
</td>
<td>
    <ul class="list-group">
       <li class="list-group-item" style="text-align:right;">
          <b>
            <?php  $totalPurchase = $totalPurchase +  $total; 
            echo amount_format(($totalPurchase), 2, '.', '');
            ?>

        </b>											
    </li>
</ul>
</td>
</tr>
</tbody>

</table>
<!--Report Table End-->
   <div class="hidden-print" style="margin-top:10px;">
      <div class="pull-right"> <a href="trading-account-report-print.php?yearid=<?php echo $financial_year; ?>" target="_blank" class="btn btn-behance p-2 waves-effect waves-light"><i class="fa fa-print mr-0"></i></a>
      </div>
   </div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<!-- content-wrapper ends -->

<!-- partial:partials/_footer.php -->
<?php include "include/footer.php" ?>
<!-- partial -->
</div>
<!-- main-panel ends -->
</div>
<!-- page-body-wrapper ends -->
</div>
<!-- container-scroller -->


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
<script src="js/select2.js"></script>
<!-- endinject -->

<!-- End custom js for this page-->
</body>


</html>














