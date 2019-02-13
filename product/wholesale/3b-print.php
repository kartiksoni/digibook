<?php include('include/usertypecheck.php');?>
<?php 
$from = (isset($_GET['from']) && $_GET['from'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_GET['from']))) : '';
$to = (isset($_GET['to']) && $_GET['to'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_GET['to']))) : '';
$pharmacy = getPharmacyDetail();

$pharmacy_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : '';
$financial_id = (isset($_SESSION['auth']['financial']) && $_SESSION['auth']['financial'] != '') ? $_SESSION['auth']['financial'] : '';


	//-------------------------MEDICINE SALES ACCOUNT--------------------------start---------------------------//
$qry1 = "SELECT  SUM(tbd.amount) as total_amount, SUM(tbd.freeqty * tbd.rate) as freeamount ,tbd.gst  FROM tax_billing_details tbd INNER JOIN tax_billing tb ON tbd.tax_bill_id = tb.id WHERE tb.pharmacy_id = '".$pharmacy_id."'";
if((isset($from) && $from != '') && (isset($to) && $to != '')){
	$qry1 .=  " AND tb.invoice_date >= '".$from."' AND tb.invoice_date <= '".$to."'";  
} 
$qry1 .= "GROUP BY tbd.gst"; 
$result1 = mysqli_query($conn,$qry1);
if($result1){
	$credit= 0;  $debit= 0; $totalcr_dr = 0;
	while ($sum = mysqli_fetch_assoc($result1)) {
		$credit += number_format($sum['total_amount'], 2, '.', '');
		$debit += number_format($debit, 2, '.', '');
		$totalcr_dr = $credit + $debit;
	}}

	$qry2 = "SELECT pd.id, SUM(pd.ammout) as total_amount , SUM(pd.free_qty * pd.f_rate) as freeamount , pd.f_cgst FROM purchase_details pd INNER JOIN  purchase p ON pd.purchase_id = p.id WHERE p.pharmacy_id = '".$pharmacy_id."'";
	if((isset($from) && $from != '') && (isset($to) && $to != '')){
		$qry2 .=  " AND p.vouchar_date >= '".$from."' AND p.vouchar_date <= '".$to."'";  
	} 
	$qry2 .= " GROUP BY pd.f_cgst" ;   
	$result2 = mysqli_query($conn,$qry2);
	if($result2){
		$credit1= 0;  $debit1= 0; $totalcr_dr1 = 0;
		while ($sum1 = mysqli_fetch_assoc($result2)) {
			$credit1 += number_format($credit1, 2, '.', '');
			$debit1 += number_format($sum1['total_amount'], 2, '.', '');
			$totalcr_dr1 = $credit1 + $debit1;
		}}


		$qry3 = "SELECT pd.id, SUM(pd.ammout) as total_amount , SUM(pd.free_qty * pd.f_rate) as freeamount , pd.f_igst FROM purchase_details pd INNER JOIN  purchase p ON pd.purchase_id = p.id WHERE p.pharmacy_id = '".$pharmacy_id."'";  
		if((isset($from) && $from != '') && (isset($to) && $to != '')){
			$qry3 .=  " AND p.vouchar_date >= '".$from."' AND p.vouchar_date <= '".$to."'";  
		} 
		$qry3 .= "GROUP BY pd.f_igst" ; 
		$result3 = mysqli_query($conn,$qry3);
		if($result3){
			$credit2= 0;  $debit2= 0; $totalcr_dr2 = 0;
			while ($sum2 = mysqli_fetch_assoc($result3)) {
				$credit2 += number_format($credit2, 2, '.', '');
				$debit2 += number_format($sum2['total_amount'], 2, '.', '');
				$totalcr_dr2 = $credit2 + $debit2;
			} }


	// $data = roundoffsales($from, $to);
			?>
			<!DOCTYPE html>
			<html>
			<head>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				<title>3B Report Print</title>
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
								<span style="font-size:18px"> <b> Purchase-Sales Figur </b></span> <br>

							</td>
						</tr>
					</tbody>
				</table>
				<h3 class="sub-title"><strong>Period <?php echo (isset($from) && $from != '') ? date('d,M Y',strtotime($from)) : ''; ?> to <?php echo (isset($to) && $to != '') ? date('d,M Y',strtotime($to)) : ''; ?></strong> </h3>
			</center>


			<span style="float:right;color:#000000;font-size:12px;margin-top: -30px;  margin-right: 30px;">(All Amounts in Rs.)</span>


			<table align="center" cellpadding="0" cellspacing="0" border="1" width="100%" id="customers" style="line-height:30px;">
				<thead>
					<tr>
						<th colspan="3">Account Head/Narration</th>
						<th width="8%">Debit</th>
						<th width="8%">Credit</th> 
						<th class="text-center">Debit-Credit</th>
						<th class="text-center">Free Goods Amt</th>
					</tr>
				</thead>
				<tbody>
					<?php $total_debit = 0;$total_credit = 0; $freegoods = 0;?>
					<tr>
						<td colspan="3"><b>MEDICINE SALES ACCOUNT</b></td>
						<td style="text-align:right;"><?php if(isset($debit)){
							echo number_format($debit,2,'.','');
						}?></td>
						<td style="text-align:right;"><?php if(isset($credit)){
							echo number_format($credit,2,'.',''); }?></td>
							<td style="text-align:right;"><?php if(isset($totalcr_dr)){
								echo number_format($totalcr_dr,2,'.','').' Cr';}?></td>
								<td class="text-center"></td>                                

							</tr>
							<?php 

							if ($result1 && mysqli_num_rows($result1) > 0){
								foreach ($result1 as $row ) { ?>
									<tr>
										<td><?php echo number_format($row['gst'], 2, '.', '').'%' ;?></td>
										<td style="text-align:right;">
											<?php echo '0.00';
											$total_debit += '0.00';
											?></td>
											<td style="text-align:right;">
												<?php
												if(isset($row['total_amount']) && $row['total_amount'] != ''){
													echo  number_format($row['total_amount'], 2, '.', '');
													$total_credit += $row['total_amount'];
												}
												?>
											</td>
											<td></td>
											<td></td>
											<td></td>
											<td style="text-align:right;">
												<?php	if(isset($row['freeamount']) && $row['freeamount'] != ''){
													echo number_format($row['freeamount'], 2, '.', '');
													$freegoods += $row['freeamount'];
												} ?></td>
											</tr>
										<?php }  }?>

										<tr>
											<td colspan="3"><b>MEDICINE PURCHASE ACCOUNT</b></td>
											<td style="text-align:right;"><?php if(isset($debit1)){
												echo number_format($debit1,2,'.',''); }?></td>
												<td style="text-align:right;"><?php if(isset($credit1)){
													echo number_format($credit1,2,'.',''); }?></td>
													<td style="text-align:right;"><?php if(isset($totalcr_dr1)){
														echo number_format($totalcr_dr1,2,'.','').' Dr';}?></td>

														<td class="text-center"></td>  
													</tr>
													<?php 
													if (isset($result2)){
														foreach ($result2 as $row ) { 

															?>
															<tr>
																<td><?php echo number_format($row['f_cgst'] *2, 2, '.', '').'%' ;?></td>
																<td style="text-align:right;">
																	<?php
																	if(isset($row['total_amount']) && $row['total_amount'] != ''){
																		echo  number_format($row['total_amount'], 2, '.', '');
																		$total_debit += $row['total_amount'];
																	}
																	?>
																</td>
																<td style="text-align:right;"><?php echo '0.00';
																$total_credit += '0.00';

																?></td>
																<td></td>
																<td></td>
																<td></td>
																<td style="text-align:right;"> 
																	
																	<?php	if(isset($row['freeamount']) && $row['freeamount'] != ''){
																		echo number_format($row['freeamount'], 2, '.', '');
																		$freegoods += $row['freeamount'];
																	} ?>	

																</td>
															</tr>
														<?php }  }?>

														<tr>
															<td colspan="3"><b>MEDICINE PURCHASE O.G.S.</b></td>
															<td style="text-align:right;"><?php if(isset($debit2)){
																echo number_format($debit2,2,'.',''); }?></td>
																<td style="text-align:right;"><?php if(isset($credit2)){
																	echo number_format($credit2,2,'.',''); }?></td>
																	<td style="text-align:right;"><?php if(isset($totalcr_dr2)){
																		echo number_format($totalcr_dr2,2,'.','').' Dr';}?></td>

																		<td class="text-center"></td>  
																	</tr>
																	<?php 
																	if (isset($result3)){
																		foreach ($result3 as $row ) { 
																			?>
																			<tr>
																				<td><?php echo number_format($row['f_igst'], 2, '.', '').'%' ;?></td>
																				<td style="text-align:right;">
																					<?php
																					
																					if(isset($row['total_amount']) && $row['total_amount'] != ''){

																						echo  number_format($row['total_amount'], 2, '.', '');
																						$total_debit += $row['total_amount'];
																					}
																					?>
																				</td>
																				<td style="text-align:right;"><?php echo '0.00';
																				$total_credit += '0.00';

																				?></td>

																				<td></td>
																				<td></td>
																				<td></td>
																				<td style="text-align:right;">
																					<?php	if(isset($row['freeamount']) && $row['freeamount'] != ''){
																						echo number_format($row['freeamount'], 2, '.', '');
																						$freegoods += $row['freeamount'];
																					} ?>
																				</td>
																			</tr>
																		<?php }  }?>

																	</tbody>

																	<tr style="font-size:14px;font-weight:bold;">
																		<td colspan="3" style="text-align:center;"> </td>

																		<td style="text-align:right;padding-right:10px;"><?php echo (isset($total_debit)) ? number_format($total_debit, 2, '.', '') : 0.00; ?></td>
																		<td style="text-align:right;padding-right:10px;"><?php echo (isset($total_credit)) ? number_format($total_credit, 2, '.', '') : 0; ?></td>
																		<td style="text-align:right;padding-right:10px;">
																		</td>
																		<td style="text-align:right;padding-right:10px;"><?php echo (isset($freegoods)) ? number_format($freegoods, 2, '.', '') : 0; ?></td>
																	</tr>

																</table>
															</body>
															</html>