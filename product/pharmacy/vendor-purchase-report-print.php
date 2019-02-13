<?php include('include/usertypecheck.php');?>
<?php 
	$from = (isset($_GET['from']) && $_GET['from'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_GET['from']))) : '';
	$to = (isset($_GET['to']) && $_GET['to'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_GET['to']))) : '';
	$vendor_id = (isset($_GET['vendor'])) ? $_GET['vendor'] : '';
  	$view = (isset($_GET['view'])) ? $_GET['view'] : '';
  	$type = (isset($_GET['type'])) ? $_GET['type'] : '';
  	$company = (isset($_GET['company'])) ? $_GET['company'] : '';
  	$product = (isset($_GET['product'])) ? $_GET['product'] : '';
  	$mrp = (isset($_GET['mrp'])) ? $_GET['mrp'] : '';

	$vendor = getPerticularDetail($vendor_id);
	$pharmacy = getPharmacyDetail();
	$data = vendorPurchaseReport($from, $to, $vendor_id, $view, $type, $company, $product, $mrp);
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Digibooks | Print Vendor Ledger</title>
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
 		    <h3 class="sub-title"><strong>Ledger for the period <?php echo (isset($from) && $from != '') ? date('d,M Y',strtotime($from)) : ''; ?> to <?php echo (isset($to) && $to != '') ? date('d,M Y',strtotime($to)) : ''; ?></strong> </h3>
	    <?php } ?>
	</center>
	<table align="center" cellpadding="0" cellspacing="0" border="0" width="100%" id="customers" style="line-height:20px;">
  		<tbody>
  			<tr>
    			<td style="border:none;padding:10px;color:#CC0000;font-size:16px;text-align:center;line-height:20px;">
    				<span style="font-size:18px">Party 	Name : <b> <?php echo (isset($vendor['name'])) ? $vendor['name'] : 'Unknown Name'; ?> </b></span> <br>
      				<span style="font-size:15px;line-height:24px;font-weight:bold;">City: <?php echo (isset($vendor['city'])) ? $vendor['city'] : 'Unknown City'; ?>
      				<?php if(isset($vendor['gstno']) && $vendor['gstno'] != ''){ ?>
      				    <br>GST No: <?php echo (isset($vendor['gstno'])) ? $vendor['gstno'] : 'Unknown GSTNO'; ?> </span>
      				<?php } ?>
      				<br/><span style="font-size:15px;line-height:24px;font-weight:bold;">View: <?php echo (isset($view)) ? ucwords($view) : 'Unknown View'; ?>
      				<br/><span style="font-size:15px;line-height:24px;font-weight:bold;">Type: <?php echo (isset($type)) ? ucwords(str_replace("_"," ", $type)) : 'Unknown Type'; ?>
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
                	<th>Voucher Date</th>
                	<th>Voucher No</th>
              	<?php } ?>
              	<th><?php echo ((isset($view) && $view == 'summary') && (isset($type) && $type == 'company_wise')) ? 'Company' : 'Item'; ?> Name</th>
              	<th style="text-align:right;padding-right:10px;">Qty.</th>
              	<th style="text-align:right;padding-right:10px;">Amount</th>
          </tr> 
  		</thead>
  		<tbody>
  			<?php $total_amount = 0; $total_qty = 0; ?>
          	<?php if(!empty($data)){ ?>
            	<?php foreach ($data as $key => $value) { ?>
              		<tr>
	                    <td><?php echo $key+1; ?></td>
	                    <?php if(isset($view) && $view == 'detail'){ ?>
	                      <td><?php echo (isset($value['vouchar_date']) && $value['vouchar_date'] != '' && $value['vouchar_date'] != '0000-00-00') ? date('d/m/Y',strtotime($value['vouchar_date'])) : ''; ?></td>
	                      <td><?php echo (isset($value['voucher_no'])) ? $value['voucher_no'] : ''; ?></td>
	                    <?php } ?>
	                    <td><?php echo (isset($value['product_name'])) ? $value['product_name'] : ''; ?></td>
	                    <td style="text-align:right;padding-right:10px;">
	                      <?php
	                        $qty = (isset($value['qty']) && $value['qty'] != '') ? $value['qty'] : '';
	                        $total_qty += $qty;
	                        echo $qty;
	                      ?>
	                    </td>
	                    <td style="text-align:right;padding-right:10px;">
	                      <?php
	                        $amount = (isset($value['amount']) && $value['amount'] != '') ? $value['amount'] : '';
	                        $total_amount += $amount;
	                        echo amount_format(number_format($amount, 2, '.', ''));
	                      ?>
	                    </td>
	                </tr>
            	<?php } ?>
          	<?php } ?>
  		</tbody>
  		<tfoot>
  			<tr style="font-size:14px;font-weight:bold;">
				<td colspan="<?php echo (isset($view) && $view == 'detail') ? 4 : 2; ?>" style="text-align:center;"> Total </td>
				<td style="text-align:right;padding-right:10px;"><?php echo (isset($total_qty)) ? $total_qty : 0; ?></td>
				<td style="text-align:right;padding-right:10px;"><?php echo (isset($total_amount)) ? amount_format(number_format($total_amount, 2, '.', '')) : 0; ?></td>
	  		</tr>
  		</tfoot>
	</table>
</body>
</html>