<?php include('include/usertypecheck.php');?>

<?php 
$financial_id = $_GET['f_id'];
$fnc = mysqli_fetch_assoc(mysqli_query($conn,'SELECT * FROM `financial` WHERE id = "'.$financial_id.'" AND status = 1'));
$pharmacy = getPharmacyDetail();
$pharmacy_id = $pharmacy['id'];

//----------------------------------------------Cash Sales--------------------start----------------------//                    
if(isset($_REQUEST['v_id']) && $_REQUEST['v_id'] !=''){ 

	$ladger = 'CASH SALES';
	$head = $_REQUEST['v_id'];
	$qry = "SELECT tb.id,tb.invoice_date,tb.invoice_no,tb.final_amount as credit,tbd.gst FROM tax_billing_details tbd INNER JOIN tax_billing tb ON tbd.tax_bill_id = tb.id WHERE tb.pharmacy_id = ".$pharmacy_id." AND tb.financial_id = ".$financial_id." AND tb.bill_type = 'Cash' AND tbd.gst = '".$_REQUEST['v_id']."'"; 
	$result = mysqli_query($conn,$qry);

}
//----------------------------------------------Sales--------------------start----------------------//                   

if(isset($_REQUEST['s_id']) && $_REQUEST['s_id'] !=''){ 
	
	$ladger = 'SALES';
	$head = $_REQUEST['s_id'];
	$qry = "SELECT tb.id,tb.invoice_date,tb.invoice_no,tb.final_amount as credit,tbd.gst FROM tax_billing_details tbd INNER JOIN tax_billing tb ON tbd.tax_bill_id = tb.id WHERE tb.pharmacy_id = '".$pharmacy_id."' AND tb.financial_id = '".$financial_id."' AND tbd.gst = '".$_REQUEST['s_id']."'";
	$result = mysqli_query($conn,$qry);
}

//----------------------------------------------OGS Sales--------------------start----------------------//          
if(isset($_REQUEST['ogs_id']) && $_REQUEST['ogs_id'] !=''){ 
	
	$ladger = 'OGS Sales';
	$head = $_REQUEST['ogs_id'];
	$qry = "SELECT tb.id,tb.invoice_date,tb.invoice_no,tb.final_amount as credit,tbd.igst FROM tax_billing_details tbd INNER JOIN tax_billing tb ON tbd.tax_bill_id = tb.id WHERE tb.pharmacy_id = '".$pharmacy_id."' AND tb.financial_id = '".$financial_id."' AND tbd.igst = '".$_REQUEST['ogs_id']."'";
	$result = mysqli_query($conn,$qry);
}     

//----------------------------------------------OGS Purchase--------------------start----------------------//    

if(isset($_REQUEST['p_igs_id']) && $_REQUEST['p_igs_id'] !=''){ 
	
	$ladger = 'OGS PURCHASE';
	$head = $_REQUEST['p_igs_id'];
	$qry = "SELECT p.id, p.invoice_date, p.voucher_no as invoice_no,p.total_total as debit, pd.f_igst FROM purchase_details pd INNER JOIN  purchase p ON pd.purchase_id = p.id WHERE p.pharmacy_id = '".$pharmacy_id."' AND p.financial_id = '".$financial_id."'  AND pd.f_igst ='".$_REQUEST['p_igs_id']."'"; 
	$result = mysqli_query($conn,$qry); 
} 

if(isset($_REQUEST['f_cgs_id']) && $_REQUEST['f_cgs_id'] !=''){ 
	
	$ladger = 'PURCHASE';
	$head = $_REQUEST['f_cgs_id'];
	$qry = "SELECT p.id, p.invoice_date, p.voucher_no as invoice_no,p.total_total as debit, pd.f_igst FROM purchase_details pd INNER JOIN  purchase p ON pd.purchase_id = p.id WHERE p.pharmacy_id = '".$pharmacy_id."' AND p.financial_id = '".$financial_id."'  AND pd.f_cgst ='".$_REQUEST['f_cgs_id']."'"; 
	$result = mysqli_query($conn,$qry);
}

//--------------------------------------------------------------------------------------------------------------------------

if(isset($_REQUEST['group_id']) && $_REQUEST['group_id'] !=''){
	$ladger = 'DISCOUNT RECEIVED';
	$j = 0;
	$final = [];
	$qry11 = "SELECT id,name FROM ledger_master WHERE group_id = '".$_REQUEST['group_id']."' ";
	$AllDirctIncm = mysqli_query($conn,$qry11);

	while($rows  = mysqli_fetch_assoc($AllDirctIncm)) {
		$data = [];

		$cashreceipt = "SELECT voucher_date,voucher_no,amount as debit FROM accounting_cash_management WHERE perticular = ".$rows['id']." AND financial_id = ".$financial_id." AND pharmacy_id = '".$pharmacy_id."' AND payment_type = 'cash_receipt'"; 
		$rceiptCash = mysqli_query($conn,$cashreceipt);
		if($rceiptCash && mysqli_num_rows($rceiptCash) > 0){
			while($rceiptTotal = mysqli_fetch_assoc($rceiptCash)){

				$aa['invoice_date'] = (isset($rceiptTotal['voucher_date'])) ? $rceiptTotal['voucher_date'] : '';
				$aa['invoice_no'] = (isset($rceiptTotal['voucher_no'])) ? $rceiptTotal['voucher_no'] : '';
				$aa['narration'] = '';
				$aa['debit'] = (isset($rceiptTotal['debit'])) ? $rceiptTotal['debit'] : '';
				$data[] = $aa;

  // $totalExpense = $totalExpense + $receipt ; 
			}}
			$chequepayment = "SELECT voucher_date,voucher_no,amount as debit FROM accounting_cheque WHERE perticular = ".$rows['id']." AND financial_id = ".$financial_id." AND pharmacy_id = '".$pharmacy_id."' AND voucher_type = 'payment'";
			$paymentcheq = mysqli_query($conn,$chequepayment);
			if($paymentcheq && mysqli_num_rows($paymentcheq) > 0){
				while($paymentTotal = mysqli_fetch_assoc($paymentcheq)){

					$bb['invoice_date'] = (isset($paymentTotal['voucher_date'])) ? $paymentTotal['voucher_date'] : '';
					$bb['invoice_no'] = (isset($paymentTotal['voucher_no'])) ? $paymentTotal['voucher_no'] : '';
					$bb['narration'] = '';
					$bb['debit'] = (isset($paymentTotal['debit'])) ? $paymentTotal['debit'] : '';
					$data[] = $bb;
   // $totalExpense = $totalExpense + $paymentC ;
				}}

				$jvQry = "SELECT voucher_date, SUM(debit) as debit FROM journal_vouchar_details jvd INNER JOIN journal_vouchar jv ON jvd.voucher_id = jv.id WHERE jvd.particular = ".$rows['id']." AND jv.financial_id = '".$financial_id."' AND jv.pharmacy_id = '".$pharmacy_id."'";
				$resultJv = mysqli_query($conn,$jvQry);
				if($resultJv && mysqli_num_rows($resultJv) > 0){
					while($jvTotalDebit = mysqli_fetch_assoc($resultJv)){

						$cc['invoice_date'] = (isset($jvTotalDebit['voucher_date'])) ? $jvTotalDebit['voucher_date'] : '';
						$cc['invoice_no'] = (isset($jvTotalDebit['voucher_no'])) ? $jvTotalDebit['voucher_no'] : '';
						$cc['narration'] = '';
						$cc['debit'] = (isset($jvTotalDebit['debit'])) ? $jvTotalDebit['debit'] : '';
						$data[] = $cc;

					}}
					
					$final[] = $data;
					$j++;
				}
				$discount['data'] = $final;
			}

			?>

			<!DOCTYPE html>
			<html>
			<head>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				<title>Print All Group Ledger</title>
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
					border-collapse:collapse;
				}
				#customers td, #customers th {
					font-size:1em;
					border:1px solid #046998;
					padding:5px 10px;
				}
				#customers th {
					font-size:1.1em;
					text-align:left;
					color:#000000;
					padding:3px 15px 2px 20px;
				}
				#customers tr td {
					color:#000000;
					font-size:12px !important;
				}
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
								<?php if(isset($_REQUEST['group_id']) && $_REQUEST['group_id'] !=''){ ?>
									<span style="font-size:18px">Ladger: <b> <?php echo $ladger?>  </b></span> <br>
								<?php } else {?>
									<span style="font-size:18px">Ladger: <b> <?php echo $head;?>% <?php echo $ladger?>  </b></span> <br>
								<?php  } ?>
							</td>
						</tr>
					</tbody>
				</table>

				<h3 class="sub-title"><strong>Period <?php echo date('d,M Y',strtotime($fnc['start_date'])); ?> to <?php echo date('d,M Y',strtotime($fnc['end_date'])) ; ?></strong> </h3>
			</center>


			<span style="float:right;color:#000000;font-size:12px;margin-top: -30px;  margin-right: 30px;">(All Amounts in Rs.)</span>


			<table align="center" cellpadding="0" cellspacing="0" border="1" width="100%" id="customers" style="line-height:30px;">
				<thead>
					<tr>
						<th width="7%">Sr No.</th>
						<th width="7%">Invoice Date</th>
						<th style="text-align:center;">Narration</th>
						<th style="text-align:right" width="15%">Debit</th>
						<th style="text-align:right" width="15%">Credit</th>
						<th style="text-align:right" width="15%">Running Balance</th>
					</tr>
				</thead>
				<tbody>
					<?php $running_balance = 0;$total_debit = 0;$total_credit = 0; ?>
					<tr>
						<td style="text-align:center"></td>
						<td></td>
						<td style="text-align:center;">Opening Balance</td>
						<td></td>
						<td></td>
						<td style="text-align:right;padding-right:10px;">
							0
						</td>
					</tr>
					<?php if(isset($result) && !empty($result)){ ?>

						<?php 
						$count = 1;
						foreach ($result as $value) { 
							?>
							<tr>
								<td><?php echo $count; ?></td>
								<td>
									<?php echo (isset($value['invoice_date']) && $value['invoice_date'] != '') ? date('d, M Y', strtotime($value['invoice_date'])) : ''; ?>
								</td>
								<td style="text-align:center;">
									<?php echo $head ;?>% <?php echo $ladger?> Amount Of Voucher No - <?php echo $value['invoice_no'].' / '. $fnc['f_name'];?>
								</td>
								<td style="text-align:right;padding-right:10px;">
									<?php
									if(isset($value['debit']) && $value['debit'] != ''){
										echo  $value['debit'];
										$total_debit += $value['debit'];
										$running_balance += $value['debit'];
									}
									?>
								</td>
								<td style="text-align:right;padding-right:10px;">
									<?php
									if(isset($value['credit']) && $value['credit'] != ''){
										echo $value['credit'];
										$total_credit += $value['credit'];
										$running_balance += $value['credit'];
									}
									?>
								</td>
								<td style="text-align:right;padding-right:10px;">
									<?php 
									echo (isset($running_balance)) ? abs($running_balance) : 0;

									?>
								</td>
							</tr>
							<?php 
							$count++;
						} ?>
					<?php } ?>

     <!-- ---------------------------------------DISCOUNT RECEIVED--------------start---------------------------------           -->


					<?php if(isset($discount) && !empty($discount)){ ?>

						<?php 
						$count = 1;
						
						foreach ($discount as $value) {
							foreach ($value as $values) {
								foreach ($values as $final) {

									?>
									<tr>
										<td><?php echo $count; ?></td>
										<td>
											<?php echo (isset($final['invoice_date']) && $final['invoice_date'] != '') ? date('d, M Y', strtotime($final['invoice_date'])) : ''; ?>
										</td>
										<td style="text-align:center;">
											Amount Of Voucher No - <?php echo $final['invoice_no'];?>
										</td>
										<td style="text-align:right;padding-right:10px;">
											<?php
											if(isset($final['debit']) && $final['debit'] != ''){
												echo  $final['debit'];
												$total_debit += $final['debit'];
												$running_balance += $final['debit'];
											}
											?>
										</td>
										<td style="text-align:right;padding-right:10px;">
											<?php
											if(isset($final['credit']) && $final['credit'] != ''){
												echo $final['credit'];
												$total_credit += $final['credit'];
												$running_balance -= $final['credit'];
											}
											?>
										</td>
										<td style="text-align:right;padding-right:10px;">
											<?php 
											echo (isset($running_balance)) ? abs($running_balance) : 0;

											?>
										</td>
									</tr>
									<?php 
									$count++;
								}
							}
						} 
						?>
						<?php 
					} ?>



<!-- ---------------------------------------DISCOUNT RECEIVED ---------------------------END------------------- -->


				</tbody>

				<tr style="font-size:14px;font-weight:bold;">
					<td colspan="3" style="text-align:center;"> Total / Closing Balance</td>

					<td style="text-align:right;padding-right:10px;"><?php echo (isset($total_debit)) ? $total_debit : 0; ?></td>
					<td style="text-align:right;padding-right:10px;"><?php echo (isset($total_credit)) ? $total_credit : 0; ?></td>
					<td style="text-align:right;padding-right:10px;">
						<?php
						echo (isset($running_balance)) ? abs($running_balance) : 0;
	                // echo (isset($running_balance) && $running_balance >= 0) ? ' Dr' : ' Cr';
						?>
					</td>
				</tr>

			</table>
		</body>
		</html> 