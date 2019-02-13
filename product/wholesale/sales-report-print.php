<?php include('include/usertypecheck.php');?>
<?php 
	$from = (isset($_GET['from']) && $_GET['from'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_GET['from']))) : '';
	$to = (isset($_GET['to']) && $_GET['to'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_GET['to']))) : '';

	$pharmacy = getPharmacyDetail();
	$data = dailysales($from, $to);
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Print Sales Report</title>
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
    		<span><strong>DAILY SALES REPORT</strong></span>
    	</h3>
 		<h3 class="sub-title"><strong>Ledger for the period <?php echo (isset($from) && $from != '') ? date('d,M Y',strtotime($from)) : ''; ?> to <?php echo (isset($to) && $to != '') ? date('d,M Y',strtotime($to)) : ''; ?></strong> </h3>
	</center>

	<span style="float:right;color:#000000;font-size:12px;margin-top: -30px;  margin-right: 30px;">(All Amounts in Rs.)</span>

	
	<table align="center" cellpadding="0" cellspacing="0" border="1" width="100%" id="customers" style="line-height:30px;">
  		<thead>
		    <tr>
		    	<th width="7%">Sr. No</th>
                <th width="8%">Invoice Date</th>
                <th width="15%">Invoice Number</th> 
                <th style="text-align:right" width="15%">Party Name</th>
                <th style="text-align:right" width="15%">City</th>
                <th style="text-align:right" width="15%">Type Of Bill</th>
                <th style="text-align:right" width="15%">Doctor Name</th>
                <th style="text-align:right" width="15%">Bill Amount</th>
		    </tr>
  		</thead>
  		<tbody>
    		<?php if(isset($data['data']) && !empty($data['data'])){ $total = 0; ?>
    			<?php foreach ($data['data'] as $key => $value) { ?>
		    		<tr>
                    	<td><?php echo $key+1; ?></td>
                        <td><?php echo (isset($value['date']) && $value['date'] != '') ? date('d, M Y', strtotime($value['date'])) : ''; ?></td>
                        <td><?php echo (isset($value['invoice_no'])) ? $value['invoice_no'] : '';  ?></td>
                        <td style="text-align:right;padding-right:10px;"><?php echo (isset($value['name'])) ? $value['name'] : ''; ?></td>
                        <td style="text-align:right;padding-right:10px;"><?php echo (isset($value['city'])) ? $value['city'] : ''; ?></td>
                        <td style="text-align:right;padding-right:10px;"><?php echo (isset($value['bill_type'])) ? $value['bill_type'] : ''; ?></td>
                        <td style="text-align:right;padding-right:10px;"><?php echo (isset($value['doctor'])) ? $value['doctor'] : ''; ?></td>
                        <td style="text-align:right;padding-right:10px;"><?php echo (isset($value['bill_amount'])) ? amount_format(number_format($value['bill_amount'], 2, '.', '')) : ''; ?></td>
                        <?php $total += $value['bill_amount']; ?>
                    </tr>
		    	<?php } ?>
	    	<?php } ?>
  		</tbody>

  		<tr style="font-size:14px;font-weight:bold;">
			<td colspan="7" style="text-align:center;"> Total / Closing Balance</td>
			<td style="text-align:right;padding-right:10px;">
				<?php
	            	echo (isset($total)) ? amount_format(number_format($total, 2, '.', '')) : 0;
	            ?>
			</td>
  		</tr>

	</table>
</body>
</html>