<?php $title = "Trading Account Report"; ?>
<?php include('include/usertypecheck.php'); ?>
<?php //include('include/permission.php'); ?>
<?php

  
$pharmacy = getPharmacyDetail();

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
$directIncomeArr = [];
$qry11 = "SELECT id,name FROM ledger_master WHERE group_id = 16 ";
$AllDirctIncm = mysqli_query($conn,$qry11);

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


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Trading Account Report Print</title>

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
	padding:3px 12px 2px 15px;

}

#customers tr td {

	color:#000000;
	font-size:11px !important;
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
				<h3 class="sub-title"><strong>Trading Account Report <br>
                                        Financial Year - 
				                        <?php if(isset($_GET['yearid'])){
                                                echo $financial_query['f_name'];
                                            }?></strong> </h3>
				</center>
			</th>	
		</tr>
	</thead>
	<tbody>
	<tr>
		<td>
		<table class="table table-bordered m-0" >
                        <tr>
                        	<td><table class="table table-bordered m-0">
                        <thead>
                          <tr>
                            <th>Account Name</th>
                            <th style="text-align:right;width:130px;">Credit</th>
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
										<?php foreach ($result1 as $value){ ?>
                                                                          <li class="list-group-item">
                                                                            <?php echo $value['gst'];?> % Cash Sales								
                                                                        </li>
                                                                    <?php  }?>



										<?php foreach ($result2 as $value){ ?>
                                                                      <li class="list-group-item">
                                                                        <?php echo $value['gst'];?> % Sales                                
                                                                    </li>
                                                                <?php  } ?>	
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

                                                                <?php 
                                                                echo amount_format(number_format($value['total_amount'], 2, '.', ''));
                                                                $totalSales = $totalSales + $value['total_amount'];
                                                                $totalCredit = $totalCredit + $value['total_amount'];
                                                                ?>

                                                                                                                                           
                                                        </li>
                                                    <?php  }
                                                    ?>

										<?php foreach ($result2 as $value) {                                           
                                                        ?>
                                                        <li class="list-group-item" style="text-align:right;">
                                                          
                                                            <?php 
                                                            echo 
                                                            amount_format(number_format($value['total_amount'], 2, '.', ''));
                                                            $totalSales = $totalSales + $value['total_amount'];
                                                            $totalCredit = $totalCredit + $value['total_amount'];

                                                            ?>
                                                                                                                                     
                                                    </li>
                                                <?php  } 

                                                ?>      


										 <?php foreach ($result3 as $value) {                                           
                                                    ?>
                                                    <li class="list-group-item" style="text-align:right;">
                                                       <!-- <a href="all-ledger-details.php?f_id=<?php echo $financial_year ?>&ogs_id=<?php echo $value['igst'];?>"> -->
                                                        <?php echo 
                                                          amount_format(number_format($value['total_amount'], 2, '.', ''));
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
                            
                              <?php echo amount_format(number_format($ros['total_amount'], 2, '.', ''));
                              $totalSales = $totalSales + $ros['total_amount'];
                              $totalCredit = $totalCredit + $ros['total_amount'];
                              ?>						

                         
                      </li>
                      <li class="list-group-item" style="text-align:right;">
                        
                            <?php 
                            $expense = 0;
                            foreach ($directIncomeArr as $income) {
                               $totalCredit = $totalCredit + $income['expense'];
                               $expense =  $expense + $income['expense'];
                           }
                           echo amount_format(number_format($expense , 2, '.', ''));

                           ?>
                     										
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
											<?php 
                                            echo amount_format(number_format($closing_balance, 2, '.', ''));

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
                      <td>
						<table class="table table-bordered m-0">
						
                        <thead>
                          <tr>
                            <th>Account Name</th>
                            <th style="text-align:right;width:130px;">Debit</th>
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
											<?php 
                                                 echo amount_format(number_format($opening_balance, 2, '.', ''));

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

           

                <?php 
                  echo amount_format(number_format($value['total_amount'], 2, '.', ''));

                $totalDebit = $totalDebit + $value['total_amount'];
                $totalPurchase = $totalPurchase + $value['total_amount'];
                ?>
                                                                                            
        </li>
    <?php  } ?>
										<?php foreach ($result5 as $value) {                                           
        ?>
        <li class="list-group-item" style="text-align:right;">
         
            <?php  
              echo amount_format(number_format($value['total_amount'], 2, '.', ''));
            $totalDebit = $totalDebit + $value['total_amount'];
            $totalPurchase = $totalPurchase + $value['total_amount'];

            ?>
                                                                                     
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
    
         <?php echo amount_format(number_format($rop['total_amount'], 2, '.', ''));
         $totalDebit = $totalDebit + $rop['total_amount'];
         $totalPurchase = $totalPurchase + $rop['total_amount'];


         ?>                
     
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
										</li>
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
											 <?php echo amount_format(number_format(($totalCredit), 2, '.', ''));
                                             ?>
										</b>
									</li>										
								</ul>                                
							</td>
							
							<td>
								<ul class="list-group">
									<li class="list-group-item" style="text-align:right;">
										<b>
											<?php  $totalPurchase = $totalPurchase +  $total; 
                                                echo amount_format(number_format(($totalPurchase), 2, '.', ''));
                                                ?>
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

