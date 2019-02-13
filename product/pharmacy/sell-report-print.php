<?php include('include/usertypecheck.php');?>
<?php 
	$from = (isset($_GET['from']) && $_GET['from'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_GET['from']))) : '';
	$to = (isset($_GET['to']) && $_GET['to'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_GET['to']))) : '';
	$customer_type = (isset($_GET['customer_type']) && $_GET['customer_type'] != '') ? $_GET['customer_type'] : '';

    $reportqry = "SELECT tb.invoice_date, tb.invoice_no, lm.name, lm.gstno, tbd.gst_tax, SUM(tbd.totalamount) as total_amount, SUM((tbd.totalamount)-(tbd.gst_tax)) as taxable_amount FROM tax_billing AS tb INNER JOIN ledger_master AS lm ON lm.id = tb.customer_id INNER JOIN tax_billing_details tbd ON tb.id = tbd.tax_bill_id WHERE tb.pharmacy_id = '".$pharmacy_id."' AND tb.invoice_date >= '".$from."' AND tb.invoice_date <= '".$to."' AND lm.group_id = 10";

    if(isset($customer_type) && $customer_type == 'GST_Regular'){
       $reportqry .= " AND lm.customer_type = '".$customer_type."'";
    }

    if(isset($customer_type) && $customer_type == 'GST_unregistered'){
       $reportqry .= " AND (lm.customer_type = '".$customer_type."' OR lm.customer_type IS NULL)";
    }

   	$reportqry .= " GROUP BY tbd.tax_bill_id ORDER BY tb.invoice_date";

    $reportrun = mysqli_query($conn, $reportqry);   

	$pharmacy = getPharmacyDetail();
	//$data = dailysales($from, $to);
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Digibooks | Print Sales Report</title>
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
    		<span><strong>SALES REPORT</strong></span>
    	</h3>
 		<h3 class="sub-title"><strong>Ledger for the period <?php echo (isset($from) && $from != '') ? date('d,M Y',strtotime($from)) : ''; ?> to <?php echo (isset($to) && $to != '') ? date('d,M Y',strtotime($to)) : ''; ?></strong> </h3>
	</center>

	<span style="float:right;color:#000000;font-size:12px;margin-top: -30px;  margin-right: 30px;">(All Amounts in Rs.)</span>

	
	<table align="center" cellpadding="0" cellspacing="0" border="1" width="100%" id="customers" style="line-height:30px;">
  		<thead>
		    <tr>
		    	<th width="7%">Sr. No</th>
                <th width="8%">Date</th>
                <th width="15%">Invoice Number</th> 
                <th style="text-align:right" width="15%">Party Name</th>
                <th style="text-align:right" width="15%">Gst No.</th>
                <th style="text-align:right" width="15%">Taxable Amount</th>
                <th style="text-align:right" width="15%">Tax Amount</th>
                <th style="text-align:right" width="15%">Total Amount</th>
		    </tr>
  		</thead>
  		<tbody>
    			<?php 
    			$count = 1;
    			$total = 0;
    			while($data = mysqli_fetch_assoc($reportrun)) { ?>
		    		<tr>
                    	<td><?php echo $count; ?></td>
                        <td><?php echo (isset($data['invoice_date']) && $data['invoice_date'] != '') ? date('d, M Y', strtotime($data['invoice_date'])) : ''; ?></td>
                        <td><?php echo (isset($data['invoice_no'])) ? $data['invoice_no'] : '';  ?></td>
                        <td style="text-align:right;padding-right:10px;"><?php echo (isset($data['name'])) ? $data['name'] : ''; ?></td>
                        <td style="text-align:right;padding-right:10px;"><?php echo (isset($data['gstno'])) ? $data['gstno'] : ''; ?></td>
                        <td style="text-align:right;padding-right:10px;"><?php echo (isset($data['taxable_amount'])) ? amount_format(number_format($data['taxable_amount'], 2, '.', '')): ''; ?></td>
                        <td style="text-align:right;padding-right:10px;"><?php echo (isset($data['gst_tax'])) ? amount_format(number_format($data['gst_tax'], 2, '.', '')): ''; ?></td>
                        <td style="text-align:right;padding-right:10px;"><?php echo (isset($data['total_amount'])) ? amount_format(number_format($data['total_amount'], 2, '.', '')) : ''; ?></td>
                        <?php $total += $data['total_amount']; ?>
                    </tr>
		    	<?php $count++; } ?>
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