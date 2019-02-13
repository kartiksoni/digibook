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
        $query = "SELECT orders.id, orders.order_no as orderno, orders.created as orderdate, product_master.product_name as productname, ledger_master.name as vendorname, ledger_master.mobile as mobile, ledger_master.email as email from((orders INNER JOIN product_master ON orders.product_id = product_master.id) INNER JOIN ledger_master ON orders.vendor_id = ledger_master.id) WHERE orders.status = '1' AND orders.pharmacy_id = '".$pharmacy_id."' AND orders.financial_id = '".$financial_id."' AND orders.vendor_id = '".$vendor_id."'";
          $res = mysqli_query($conn, $query);
        }

?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title> Order List Print </title>
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
 		<h3 class="sub-title"><strong>Orders Print</strong> </h3>
	</center>

	<table align="center" cellpadding="0" cellspacing="0" border="1" width="100%" id="customers" style="line-height:30px;">
  		<thead>
		    <tr>
		    	<th width="7%">Sr No.</th>
		    	<th style="text-align:center;">Order No</th>
		      	<th style="text-align:center;">Order Date</th>
		      	<th style="text-align:center;">Vendor Name</th>
		      	<th style="text-align:center;">Mobile</th>
		      	<th style="text-align:center;">Email</th>
		    </tr>
  		</thead>
  		<tbody>
  			
    		
    		<?php if($res){ ?>
    			<?php
    			  $i = 1;  
    			while ($row = mysqli_fetch_assoc($res)) { ?>
		    		<tr>
		      			<td style="text-align:center;"><?php echo $i; ?></td>

		      			<td style="text-align:center;padding-right:10px;">
		      				<?php echo (isset($row['orderno'])) ? $row['orderno'] : ''; ?>
		      			</td>

		      			<td style="text-align:center;"><?php echo (isset($row['orderdate']) && $row['orderdate'] != '') ? date('d, M Y', strtotime($row['orderdate'])) : ''; ?></td>

		      			<td style="text-align:center;padding-right:10px;">
		      				<?php echo (isset($row['vendorname'])) ? $row['vendorname'] : ''; ?>
		      			</td>

		      			<td style="text-align:center;padding-right:10px;">
		      				<?php echo (isset($row['mobile'])) ? $row['mobile'] : ''; ?>
		      			</td>

		      			<td style="text-align:center;padding-right:10px;">
		      				<?php echo (isset($row['email'])) ? $row['email'] : ''; ?>
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