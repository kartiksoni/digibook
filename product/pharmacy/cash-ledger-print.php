<?php include('include/usertypecheck.php');?>
<?php 
	$from = (isset($_GET['from']) && $_GET['from'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_GET['from']))) : '';
	$to = (isset($_GET['to']) && $_GET['to'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_GET['to']))) : '';

	$pharmacy = getPharmacyDetail();
	$data = cashLedger($from, $to);
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Digibooks | Print Cash Ledger</title>
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
 		<h3 class="sub-title"><strong>Ledger for the period <?php echo (isset($from) && $from != '') ? date('d,M Y',strtotime($from)) : ''; ?> to <?php echo (isset($to) && $to != '') ? date('d,M Y',strtotime($to)) : ''; ?></strong> </h3>
	</center>
	<table align="center" cellpadding="0" cellspacing="0" border="0" width="100%" id="customers" style="line-height:20px;">
  		<tbody>
  			<tr>
    			<td style="border:none;padding:10px;color:#CC0000;font-size:16px;text-align:center;line-height:20px;">
    				<span style="font-size:18px">Ledger Type : <b> Cash </b></span>
      			</td>
  			</tr>
		</tbody>
		</table>

	<span style="float:right;color:#000000;font-size:12px;margin-top: -30px;  margin-right: 30px;">(All Amounts in Rs.)</span>

	
	<table align="center" cellpadding="0" cellspacing="0" border="1" width="100%" id="customers" style="line-height:30px;">
  		<thead>
		    <tr>
		    	<th width="7%">Sr No.</th>
		      	<th width="7%">Date</th>
		      	<th width="10%">Type</th>
		      	<th style="text-align:left;">Narration</th>
		      	<th style="text-align:right" width="15%">Receipt</th>
	     	 	<th style="text-align:right" width="15%">Payment</th>
		      	<th style="text-align:right" width="15%">Cash in Hand</th>
		    </tr>
  		</thead>
  		<tbody>
  			<?php $running_balance = 0;$total_receipt = 0;$total_payment = 0; ?>
    		<tr>
      			<td style="text-align:center"></td>
      			<td></td>
      			<td></td>
      			<td>Opening Balance</td>
  				<td></td>
      			<td></td>
      			<td style="text-align:right;padding-right:10px;">
      				<?php 
                      echo (isset($data['opening_balance']) && $data['opening_balance'] != '') ? amount_format(number_format(abs($data['opening_balance']), 2, '.', '')) : 0;
                      $opening_balance = (isset($data['opening_balance']) && $data['opening_balance'] != '') ? $data['opening_balance'] : 0;
                      echo ($opening_balance >= 0) ? ' Dr' : ' Cr';
                      $running_balance += $opening_balance;
                    ?>
      			</td>
    		</tr>
    		<?php if(isset($data['data']) && !empty($data['data'])){ ?>
    			<?php foreach ($data['data'] as $key => $value) { ?>
		    		<tr>
		      			<td><?php echo $key+1; ?></td>
		      			<td>
		      				<?php echo (isset($value['date']) && $value['date'] != '') ? date('d, M Y', strtotime($value['date'])) : ''; ?>
		      			</td>
		      			<td><?php echo (isset($value['type_name'])) ? $value['type_name'] : ''; ?></td>
		      			<td style="vertical-align:middle;">
		      				<?php echo (isset($value['narration'])) ? $value['narration'] : ''; ?>
		      			</td>
		      			<td style="text-align:right;padding-right:10px;">
		      				<?php 
                              echo (isset($value['receipt']) && $value['receipt'] != '') ? amount_format(number_format($value['receipt'], 2, '.', '')) : '';
                              $receipt = (isset($value['receipt']) && $value['receipt'] != '') ? $value['receipt'] : 0;
                              $total_receipt += $receipt;
                              $running_balance += $receipt;
                            ?>
		      			</td>
		      			<td style="text-align:right;padding-right:10px;">
		      				<?php 
	                          echo (isset($value['payment']) && $value['payment'] != '') ? amount_format(number_format($value['payment'], 2, '.', '')) : '';
	                          $payment = (isset($value['payment']) && $value['payment'] != '') ? $value['payment'] : 0;
	                          $total_payment += $payment;
	                          $running_balance -= $payment;
	                        ?>
		      			</td>
		      			<td style="text-align:right;padding-right:10px;">
		      				<?php 
	                          echo (isset($running_balance)) ? amount_format(number_format(abs($running_balance), 2, '.', '')) : 0;
	                          echo ($running_balance >= 0) ? ' Dr' : ' Cr';
	                        ?>
		      			</td>
		    		</tr>
		    	<?php } ?>
	    	<?php } ?>
  		</tbody>

  		<tr style="font-size:14px;font-weight:bold;">
			<td colspan="4" style="text-align:center;"> Total / Closing Balance</td>
			<td style="text-align:right;padding-right:10px;"><?php echo (isset($total_receipt)) ? amount_format(number_format($total_receipt, 2, '.', '')) : 0; ?></td>
			<td style="text-align:right;padding-right:10px;"><?php echo (isset($total_payment)) ? amount_format(number_format($total_payment, 2, '.', '')) : 0; ?></td>
			<td style="text-align:right;padding-right:10px;">
				<?php
	            	echo (isset($running_balance)) ? amount_format(number_format(abs($running_balance), 2, '.', '')) : 0;
	                echo (isset($running_balance) && $running_balance >= 0) ? ' Dr' : ' Cr';
	            ?>
			</td>
  		</tr>

	</table>
</body>
</html>