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
        $query = "SELECT so.id, so.created, so.discount, so.mrp, lgr.name, lgr.mobile, lgr.email FROM sales_order so INNER JOIN ledger_master lgr ON so.customer_id = lgr.id WHERE so.pharmacy_id = '".$pharmacy_id."' AND so.financial_id = '".$financial_id."' AND so.customer_id = '".$vendor_id."' ORDER BY so.id DESC";
          $res = mysqli_query($conn, $query);
        }

?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title> Sales Print </title>
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
 		<h3 class="sub-title"><strong>Sales Print</strong> </h3>
	</center>

	<table align="center" cellpadding="0" cellspacing="0" border="1" width="100%" id="customers" style="line-height:30px;">
  		<thead>
		    <tr>
		    	<th width="7%">Sr No.</th>
		    	<th style="text-align:center;">Order Date</th>
		      	<th style="text-align:center;">Customer</th>
		      	<th style="text-align:center;">Mobile</th>
		      	<th style="text-align:center;">Doctor</th>
		      	<th style="text-align:center;">Delivery Status</th>
		      	<th style="text-align:center;">Disc</th>
                <th style="text-align:center;">Price</th>
		    </tr>
  		</thead>
  		<tbody>
  			
    		
    		<?php if($res){ ?>
    			<?php
    			  $i = 1;  
    			while ($row = mysqli_fetch_assoc($res)) { ?>
		    		<tr>
		      			<td style="text-align:center;"><?php echo $i; ?></td>

		      			<td style="text-align:center;"><?php echo (isset($row['created']) && $row['created'] != '') ? date('d, M Y', strtotime($row['created'])) : ''; ?></td>

		      			<td style="text-align:center;padding-right:10px;">
		      				<?php echo (isset($row['name'])) ? $row['name'] : ''; ?>
		      			</td>

		      			<td style="text-align:center;padding-right:10px;">
		      				<?php echo (isset($row['mobile'])) ? $row['mobile'] : ''; ?>
		      			</td>

		      			<td></td>
		      			<td></td>

		      			<td style="text-align:center;padding-right:10px;">
		      				<?php echo (isset($row['discount'])) ? $row['discount'] : ''; ?>
		      			</td>

		      			<td style="text-align:center;padding-right:10px;">
		      				<?php echo (isset($row['mrp'])) ? $row['mrp'] : ''; ?>
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