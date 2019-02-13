<?php include('include/usertypecheck.php');?>
<?php 
	$pharmacy_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : '';
	$financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';
	$from = (isset($_GET['from']) && $_GET['from'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_GET['from']))) : '';
	$to = (isset($_GET['to']) && $_GET['to'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_GET['to']))) : '';
	$customer_city = (isset($_GET['customer_city']) && $_GET['customer_city'] != '') ? $_GET['customer_city'] : '';
	$customer_name = (isset($_GET['customer_name']) && $_GET['customer_name'] != '') ? $_GET['customer_name'] : '';
	$search_radio = (isset($_GET['search_radio']) && $_GET['search_radio'] != '') ? $_GET['search_radio'] : '';
	$company_name = (isset($_GET['company_id']) && $_GET['company_id'] != '') ? $_GET['company_id'] : '';
	$product_name = (isset($_GET['product_name']) && $_GET['product_name'] != '') ? $_GET['product_name'] : '';
	$view = (isset($_GET['view']) && $_GET['view'] != '') ? $_GET['view'] : '';

	if ($view == 'detail') {
		$reportqry = "SELECT tb.invoice_no, tb.customer_id, tb.invoice_date, pm.product_name, tbd.qty, tbd.tax_bill_id, tbd.totalamount FROM tax_billing AS tb INNER JOIN tax_billing_details AS tbd ON tb.id = tbd.tax_bill_id INNER JOIN product_master AS pm ON pm.id = tbd.product_id WHERE tb.pharmacy_id = '".$pharmacy_id."' AND tb.financial_id = '".$financial_id."' AND tb.invoice_date >= '".$from."' AND tb.invoice_date <= '".$to."' AND tb.customer_id = '".$customer_name."'";

		if(isset($search_radio) && $search_radio == 'company_wise'){
			$reportqry .= " AND pm.company_code = '".$company_name."'";
		}

		if(isset($search_radio) && $search_radio == 'single_product'){
			$reportqry .= " AND pm.product_name = '".$product_name."'";
		}

		$reportqry .= " ORDER BY tb.invoice_date";
	}elseif($view == 'summary'){
		$reportqry = "SELECT  pm.product_name, SUM(tbd.qty) AS qty, SUM(tbd.totalamount) AS totalamount FROM tax_billing AS tb INNER JOIN tax_billing_details AS tbd ON tb.id = tbd.tax_bill_id INNER JOIN product_master AS pm ON pm.id = tbd.product_id WHERE tb.pharmacy_id = '".$pharmacy_id."' AND tb.financial_id = '".$financial_id."' AND tb.invoice_date >= '".$from."' AND tb.invoice_date <= '".$to."' AND tb.customer_id = '".$customer_name."'";

		if(isset($search_radio) && $search_radio == 'company_wise'){
			$reportqry .= " AND pm.company_code = '".$company_name."'";
		}

		if(isset($search_radio) && $search_radio == 'single_product'){
			$reportqry .= " AND pm.product_name = '".$product_name."'";
		}

		$reportqry .= " GROUP BY pm.product_name";
	}   
	
		$reportrun = mysqli_query($conn, $reportqry);

	$getledger =" SELECT l.gstno,l.city,l.name, c.name AS city from ledger_master AS l INNER JOIN own_cities AS c ON l.city = c.id WHERE l.id= '".$customer_name."' ";
	$ledger =mysqli_query($conn, $getledger);
	$data1 = mysqli_fetch_assoc($ledger);
	$pharmacy = getPharmacyDetail();
	//$data = dailysales($from, $to);
?>
<!DOCTYPE html> 
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Digibooks | Print Customer Sales</title>
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
		<?php if((isset($from) && $from != '') && (isset($to) && $to != '')){ ?>
			<h3 class="sub-title"><strong>Custome Sales for the period <?php echo (isset($from) && $from != '') ? date('d,M Y',strtotime($from)) : ''; ?> to <?php echo (isset($to) && $to != '') ? date('d,M Y',strtotime($to)) : ''; ?></strong> </h3>
		<?php } ?>
	</center>
	<table align="center" cellpadding="0" cellspacing="0" border="0" width="100%" id="customers" style="line-height:20px;">
		<tbody>
			<tr>
				<td style="border:none;padding:10px;color:#CC0000;font-size:16px;text-align:center;line-height:20px;">
					<span style="font-size:18px">Party 	Name : <b> <?php echo (isset($data1['name'])) ? $data1['name'] : 'Unknown Name'; ?> </b></span> <br>
					<span style="font-size:15px;line-height:24px;font-weight:bold;">City: <?php echo (isset($data1['city'])) ? $data1['city'] : 'Unknown City'; ?>
					<?php if(isset($data1['gstno']) && $data1['gstno'] != ''){ ?>
						<br>GST No: <?php echo (isset($data1['gstno'])) ? $data1['gstno'] : 'Unknown GSTNO'; ?> </span>
					<?php } ?>
					<br/><span style="font-size:15px;line-height:24px;font-weight:bold;">View: <?php echo (isset($view)) ? ucwords($view) : 'Unknown View'; ?>
					<br/><span style="font-size:15px;line-height:24px;font-weight:bold;">Type: <?php echo (isset($search_radio)) ? ucwords(str_replace("_"," ", $search_radio)) : 'Unknown Type'; ?>
				</td>
			</tr>
		</tbody>
	</table>

	<span style="float:right;color:#000000;font-size:12px;margin-top: -30px;  margin-right: 30px;">(All Amounts in Rs.)</span>

	
	<table align="center" cellpadding="0" cellspacing="0" border="1" width="100%" id="customers" style="line-height:30px;">
		<thead>
			<tr>
				<th>Sr. No</th>
				<?php if(isset($view) && $view == 'detail'){ ?>
					<th>Bill No</th>
					<th>Bill Date</th>
				<?php } ?>
				<th>Item Name</th>
				<th style="text-align:right;padding-right:10px;">Qty.</th>
				<th style="text-align:right;padding-right:10px;">Amount</th>
			</tr> 
		</thead>
		<tbody>
			<?php 
                if($reportrun){
                  $count = 1;
                  $total_qty = 0;
                  $total_amount1 = 0;
                  while($data = mysqli_fetch_assoc($reportrun)){
                   ?>
					<tr>
						<td><?php echo $count; ?></td>
						<?php if(isset($view) && $view == 'detail'){  ?>
							<td><?php echo (isset($data['invoice_no'])) ? $data['invoice_no'] : ''; ?></td>
							<td><?php echo (isset($data['invoice_date']) && $data['invoice_date'] != '' && $data['invoice_date'] != '0000-00-00') ? date('d/m/Y',strtotime($data['invoice_date'])) : ''; ?></td>
						<?php } ?>
						<td><?php echo (isset($data['product_name'])) ? $data['product_name'] : ''; ?></td>
						<td style="text-align:right;padding-right:10px;">
							<?php
							$qty = (isset($data['qty']) && $data['qty'] != '') ? $data['qty'] : '';
							$total_qty += $qty;
							echo $qty;
							?>
						</td>
						<td style="text-align:right;padding-right:10px;">
							<?php
							$totalamount = (isset($data['totalamount']) && $data['totalamount'] != '') ? $data['totalamount'] : '';
							$total_amount1 += $totalamount;
							echo amount_format(number_format($totalamount, 2, '.', ''));
							?>
						</td>
					</tr>
				<?php $count++; } ?>
			<?php } ?>
		</tbody>
		<tfoot>
			<tr style="font-size:14px;font-weight:bold;">
				<td colspan="<?php echo (isset($view) && $view == 'detail') ? 4 : 2; ?>" style="text-align:center;"> Total </td>
				<td style="text-align:right;padding-right:10px;"><?php echo (isset($total_qty)) ? $total_qty : 0; ?></td>
				<td style="text-align:right;padding-right:10px;"><?php echo (isset($total_amount1)) ? amount_format(number_format($total_amount1, 2, '.', '')) : 0; ?></td>
			</tr>
		</tfoot>
	</table>
</body>
</html>