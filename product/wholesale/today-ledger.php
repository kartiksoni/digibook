<?php include('include/usertypecheck.php');?>
<?php 
	
	
	$pharmacy = getPharmacyDetail();
	$type = '';
	$is_ihis = 0;
		if(isset($_GET['ledger']) && $_GET['ledger'] == 'todayGeneralSale'){
			$type = '';
			$is_ihis = 0;
		}elseif(isset($_GET['ledger']) && $_GET['ledger'] == 'todayDebitGeneralSale'){
			$type = 'Debit';
			$is_ihis = 0;
		}elseif(isset($_GET['ledger']) && $_GET['ledger'] == 'todayCashGeneralSale'){
			$type = 'Cash';
			$is_ihis = 0;
		}elseif(isset($_GET['ledger']) && $_GET['ledger'] == 'todayIhisSale'){
			$type = '';
			$is_ihis = 1;
		}elseif(isset($_GET['ledger']) && $_GET['ledger'] == 'todayDebitIhisSale'){
			$type = 'Debit';
			$is_ihis = 1;
		}elseif(isset($_GET['ledger']) && $_GET['ledger'] == 'todayCashIhisSale'){
			$type = 'Cash';
			$is_ihis = 1;
		}

	$data = getTodaySaleLedger($type, $is_ihis);

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Today Ledger</title>
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
 		<h3 class="sub-title"><strong>Ledger for the period <?php echo date('d,M Y'); ?></strong> </h3>
	</center>
	<table align="center" cellpadding="0" cellspacing="0" border="0" width="100%" id="customers" style="line-height:20px;">
  		<tbody>
  			<tr>
    			<td style="border:none;padding:10px;color:#CC0000;font-size:16px;text-align:center;line-height:20px;">
    				<span style="font-size:18px">Ledger Name : <b> <?php echo (isset($_GET['ledger'])) ? ucwords(ltrim(preg_replace('/[A-Z]/', ' $0', $_GET['ledger']))) : 'Unknown Ledger'; ?> </b></span>
      			</td>
  			</tr>
		</tbody>
		</table>

	<span style="float:right;color:#000000;font-size:12px;margin-top: -30px;  margin-right: 30px;">(All Amounts in Rs.)</span>

	
	<table align="center" cellpadding="0" cellspacing="0" border="1" width="100%" id="customers" style="line-height:30px;">
  		<thead>
		    <tr>
		    	<th>Sr No.</th>
		      	<th>Invoice Date</th>
		      	<th>Invoice No.</th>
		      	<th>Party Name</th>
		      	<th>City</th>
		      	<th>Type Of Bill</th>
		      	<th>Doctor Name</th>
		      	<th style="text-align:right">Bill Amount</th>
		    </tr>
  		</thead>
  		<tbody>
  			<?php $total_sale = 0; ?>
    		<?php if(isset($data) && !empty($data)){ ?>
    			<?php foreach ($data as $key => $value) { ?>
		    		<tr>
		      			<td><?php echo $key+1; ?></td>
		      			<td>
		      				<?php echo (isset($value['invoice_date']) && $value['invoice_date'] != '') ? date('d, M Y', strtotime($value['invoice_date'])) : ''; ?>
		      			</td>
		      			<td>
		      				<?php echo (isset($value['invoice_no'])) ? $value['invoice_no'] : ''; ?>
		      			</td>
		      			<td>
		      				<?php echo (isset($value['customer_name'])) ? $value['customer_name'] : ''; ?>
		      			</td>
		      			<td>
		      				<?php echo (isset($value['customer_city'])) ? $value['customer_city'] : ''; ?>
		      			</td>
		      			<td>
		      				<?php echo (isset($value['bill_type'])) ? $value['bill_type'] : ''; ?>
		      			</td>
		      			<td>
		      				<?php echo (isset($value['doctor_name'])) ? $value['doctor_name'] : ''; ?>
		      			</td>
		      			<td style="text-align:right;padding-right:10px;">
		      				<?php
                              $amount = (isset($value['final_amount']) && $value['final_amount'] != '') ? $value['final_amount'] : 0;
                              $total_sale += $amount;
                              echo amount_format(number_format($amount, 2, '.', ''));
                            ?>
		      			</td>
		    		</tr>
		    	<?php } ?>
	    	<?php }else{ ?>
	    		<tr>
	    			<td colspan="8" style="text-align: center;">No Record Found!</td>
	    		</tr>
	    	<?php } ?>
  		</tbody>

  		<tr style="font-size:14px;font-weight:bold;">
			<td colspan="7" style="text-align:center;"> Total / Closing Balance</td>
			<td style="text-align:right;padding-right:10px;"><?php echo (isset($total_sale)) ? amount_format(number_format($total_sale, 2, '.', '')) : 0; ?></td>
  		</tr>

	</table>
</body>
</html>