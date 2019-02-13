<?php
 include('include/usertypecheck.php');
  $vendor_id= $_GET['id'];
  $group = $_GET['group'];

$pharmacy_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : '';
  $financial_id = (isset($_SESSION['auth']['financial']) && $_SESSION['auth']['financial'] != '') ? $_SESSION['auth']['financial'] : NULL;
$pharmacy = getPharmacyDetail();

if(isset($vendor_id)){
$name = "SELECT * FROM `ledger_master` where id = '".$vendor_id."'";
 $getname = mysqli_query($conn, $name);
 $vendor= mysqli_fetch_assoc($getname); 
}
 if((isset($vendor_id) && $vendor_id != '') && (isset($group) && $group != '')){
        $data = [];
        $query = "SELECT ord.id, ord.vendor_id, ord.product_id, ord.purchase_price, ord.gst, ord.unit, ord.qty, lg.name as vendor_name, pm.product_name, pm.mfg_company, pm.generic_name, st.state_code_gst as state FROM orders ord LEFT JOIN ledger_master lg ON ord.vendor_id = lg.id LEFT JOIN product_master pm ON ord.product_id = pm.id LEFT JOIN own_states st ON lg.state = st.id WHERE ord.pharmacy_id = '".$pharmacy_id."' AND ord.financial_id = '".$financial_id."' AND ord.groups = '".$group."' AND ord.vendor_id = '".$vendor_id."'";
          $res = mysqli_query($conn, $query);
        }

?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title> Order Print </title>
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
 		<h3 class="sub-title"><strong>Order Print</strong> </h3>
	</center>
	<table align="center" cellpadding="0" cellspacing="0" border="0" width="100%" id="customers" style="line-height:20px;">
  		<tbody>
  			<tr>
    			<td style="border:none;padding:10px;color:#CC0000;font-size:16px;text-align:center;line-height:20px;">
    				<span style="font-size:18px">Vendor Name : <b> <?php echo (isset($vendor['name'])) ? $vendor['name'] : 'Unknown Name'; ?> </b></span> <br>
      				<span style="font-size:15px;line-height:24px;font-weight:bold;">Email: <?php echo (isset($vendor['email'])) ? $vendor['email'] : 'Unknown email'; ?>  <br>Mobile No : <?php echo (isset($vendor['mobile'])) ? $vendor['mobile'] : 'Unknown mobile'; ?> </span>
      			</td>
  			</tr>
		</tbody>
		</table>



	
	<table align="center" cellpadding="0" cellspacing="0" border="1" width="100%" id="customers" style="line-height:30px;">
  		<thead>
		    <tr>
		    	<th width="7%">Sr No.</th>
		      	<th style="text-align:center;">Vendor Name</th>
		      	<th style="text-align:center;">Product</th>
		      	<th style="text-align:center" width="15%">Purchase Price</th>
	     	 	<th style="text-align:center" width="15%">GST</th>
		      	<th style="text-align:center" width="15%">Unit/ Strip/ Packing</th>
		      	<th style="text-align:center" width="15%">Qty</th>
		    </tr>
  		</thead>
  		<tbody>
  			
    		
    		<?php if($res){ ?>
    			<?php
    			  $i = 1;  
    			while ($row = mysqli_fetch_assoc($res)) { ?>
		    		<tr>
		      			<td><?php echo $i; ?></td>

		      			<td style="text-align:center;">
		      				<?php echo (isset($row['vendor_name'])) ? $row['vendor_name'] : ''; ?>
		      			</td>
		      			<td style="text-align:center;padding-right:10px;">
		      				<?php echo (isset($row['product_name'])) ? $row['product_name'] : ''; ?>
		      			</td>
		      			<td style="text-align:center;padding-right:10px;">
		      				<?php echo (isset($row['purchase_price'])) ? $row['purchase_price'] : ''; ?>
		      			</td>
		      			<td style="text-align:center;padding-right:10px;">
		      				<?php echo (isset($row['gst'])) ? $row['gst'] : ''; ?>
		      			</td>
		      			<td style="text-align:center;padding-right:10px;">
		      				<?php echo (isset($row['unit'])) ? $row['unit'] : ''; ?>
		      			</td>
		      			<td style="text-align:center;padding-right:10px;">
		      				<?php echo (isset($row['qty'])) ? $row['qty'] : ''; ?>
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