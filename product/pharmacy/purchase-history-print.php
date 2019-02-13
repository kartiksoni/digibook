<?php
 include('include/usertypecheck.php');
  $vendor_id= $_GET['id'];
  //$group = $_GET['group'];

$pharmacy_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : '';
  $financial_id = (isset($_SESSION['auth']['financial']) && $_SESSION['auth']['financial'] != '') ? $_SESSION['auth']['financial'] : NULL;
$pharmacy = getPharmacyDetail();

/*if(isset($vendor_id)){
$name = "SELECT * FROM `ledger_master` where id = '".$vendor_id."'";
 $getname = mysqli_query($conn, $name);
 $vendor= mysqli_fetch_assoc($getname); 
} */
 if(isset($vendor_id) && $vendor_id != ''){
        $data = [];
        $query = "SELECT pr.id, pr.invoice_no, pr.invoice_date, pr.minimal_radio, pr.vendor, pr.per_discount, pr.rs_discount, pr.purchase_type, pr.total_total as total, pr.cancel,lg.name as vendor_name, lg.mobile FROM purchase pr LEFT JOIN ledger_master lg ON pr.vendor = lg.id WHERE pr.pharmacy_id = '".$pharmacy_id."' AND pr.financial_id = '".$financial_id."' AND pr.vendor = '".$vendor_id."' ORDER BY pr.id DESC";
          $res = mysqli_query($conn, $query);
        }

?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title> Purchase Print </title>
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
 		<h3 class="sub-title"><strong>Purchase Print</strong> </h3>
	</center>

	<table align="center" cellpadding="0" cellspacing="0" border="1" width="100%" id="customers" style="line-height:30px;">
  		<thead>
		    <tr>
		    	<th width="7%">Sr No.</th>
		    	<th style="text-align:center;">Invoice No.</th>
		      	<th style="text-align:center;">Vendor</th>
		      	<th style="text-align:center;">Inv. date</th>
		      	<th style="text-align:center;">Mobile</th>
		      	<th style="text-align:center;">Dis%</th>
		      	<th style="text-align:center;">Type</th>
                <th style="text-align:center;">Total</th>
		    </tr>
  		</thead>
  		<tbody>
  			
    		
    		<?php if($res){ ?>
    			<?php
    			  $i = 1;  
    			while ($row = mysqli_fetch_assoc($res)) { ?>
		    		<tr>
		      			<td style="text-align:center;"><?php echo $i; ?></td>

		      			<td style="text-align:center;">
		      				<?php echo (isset($row['invoice_no'])) ? $row['invoice_no'] : ''; ?>
		      			</td>

		      			<td style="text-align:center;">
		      				<?php echo (isset($row['vendor_name'])) ? $row['vendor_name'] : ''; ?>
		      			</td>
		      			<td style="text-align:center;"><?php echo (isset($row['invoice_date']) && $row['invoice_date'] != '') ? date('d, M Y', strtotime($row['invoice_date'])) : ''; ?></td>
		      			<td style="text-align:center;padding-right:10px;">
		      				<?php echo (isset($row['mobile'])) ? $row['mobile'] : ''; ?>
		      			</td>
		      			<td style="text-align:center;padding-right:10px;">
		      				<?php echo (isset($row['per_discount'])) ? $row['per_discount'] : ''; ?>
		      			</td>
		      			<td style="text-align:center;padding-right:10px;">
		      				<?php echo (isset($row['purchase_type'])) ? $row['purchase_type'] : ''; ?>
		      			</td>
		      			<td style="text-align:right;padding-right:10px;">
		      				<?php echo (isset($row['total']) && $row['total'] != '') ? amount_format(number_format($row['total'], 2, '.', '')) : ''; ?>
		      			</td>
		    		</tr>
		    	<?php 
                $i++;
		    } ?>
	    	<?php } ?>
  		</tbody>

  		

	</table>
</body>
</html>