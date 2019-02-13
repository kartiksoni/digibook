<?php include('include/usertypecheck.php');?>
<?php 
    $expiry_date = (isset($_GET['expiry_date']) && $_GET['expiry_date'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_GET['expiry_date']))) : '';
    $company_by = (isset($_GET['company_by'])) ? $_GET['company_by'] : '';
    $company_code = (isset($_GET['company'])) ? $_GET['company'] : '';

    $pharmacy = getPharmacyDetail();
   	$searchdata = getexpirydatereport($expiry_date , $company_by);
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Digibooks | Print Expiry Date Report</title>
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
    	<table align="center" cellpadding="0" cellspacing="0" border="0" width="100%" id="customers" style="line-height:20px;">
  		<tbody>
  			<tr>
    			<td style="border:none;padding:10px;color:#CC0000;font-size:16px;text-align:center;line-height:20px;">
    				<span style="font-size:18px"> <b> Expiry Date report </b></span> <br>
      				
      			</td>
  			</tr>
		</tbody>
		</table>
 		<h3 class="sub-title"><strong>Period <?php echo (isset($expiry_date) && $expiry_date != '') ? date('d,M Y',strtotime($expiry_date)) : ''; ?></strong> </h3>
	</center>
	
	<table align="center" cellpadding="0" cellspacing="0" border="1" width="100%" id="customers" style="line-height:30px;">
  		<thead>
		    <tr>
		    	<th>Sr.No</th>
                <th>Product Name</th>
                <?php if(isset($_GET['company_by']) && $_GET['company_by'] == '0'){ ?>
                  <th>Company Name</th>
                <?php } ?>
                <th>Ex.Date</th>
                <th>Batch No</th>
                <th>Stock</th>
		    </tr>
  		</thead>
  		<tbody>
    		<?php if(isset($searchdata) &&!empty($searchdata)){ ?>
                 <?php foreach($searchdata as $key => $value){ ?>
		    		<tr>
		  			<td><?php echo $key+1; ?></td>
                    <td><?php echo (isset($value['product_name'])) ? $value['product_name'] : '-';  ?></td>
                    <?php if(isset($_GET['company_by']) && $_GET['company_by'] == '0'){ ?>
                      <td><?php echo (isset($value['name'])) ? $value['name'] : '-';  ?></td>
                    <?php } ?>
                    <td><?php echo (isset($value['ex_date']) && $value['ex_date'] != '' && $value['ex_date'] != '0000-00-00') ? date('d/m/Y', strtotime($value['ex_date'])) : '-'; ?></td>
                    <td><?php echo (isset($value['batch_no'])) ? $value['batch_no'] : '-';  ?></td>
                    <td><?php echo (isset($value['stock'])) ? $value['stock'] : '-';  ?></td>
		    		</tr>
		    	<?php } ?>
	    	<?php } ?>
  		</tbody>
</body>
</html>