<?php include('include/usertypecheck.php');?>
<?php 
	$from = (isset($_GET['from']) && $_GET['from'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_GET['from']))) : '';
	$to = (isset($_GET['to']) && $_GET['to'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_GET['to']))) : '';
	$bill_type = (isset($_GET['bill_type'])) ? $_GET['bill_type'] : '';
	$pharmacy = getPharmacyDetail();
	$purchse_data = purchase_report($from,$to,$bill_type);
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Print Customer Ledger</title>
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
    				<span style="font-size:18px">Ladger: <b> Purchse Report </b></span> <br>
      				
      			</td>
  			</tr>
		</tbody>
		</table>
 		<h3 class="sub-title"><strong>Period <?php echo (isset($from) && $from != '') ? date('d,M Y',strtotime($from)) : ''; ?> to <?php echo (isset($to) && $to != '') ? date('d,M Y',strtotime($to)) : ''; ?></strong> </h3>
	</center>
	

	<span style="float:right;color:#000000;font-size:12px;margin-top: -30px;  margin-right: 30px;">(All Amounts in Rs.)</span>

	
	<table align="center" cellpadding="0" cellspacing="0" border="1" width="100%" id="customers" style="line-height:30px;">
  		<thead>
		    <tr>
		      <th width="7%">Sr. No</th>
              <th width="8%">Iteam Code</th>
              <th class="text-center">Iteam Name</th>
              <th class="text-center">MFG. Company</th> 
              <th class="text-center">Generic Name</th>
              <th width="8%">Opening Qty</th>
              <?php 
              if(isset($bill_type) && $bill_type != '' && $bill_type == "avg_price"){
              ?>
              <th class="text-center" width="12%">Avg Price</th>
              <?php } ?>
              <?php 
              if(isset($bill_type) && $bill_type != '' && $bill_type == "original_price"){
              ?>
              <th class="text-center" width="12%">Original Price</th>
              <?php } ?>
              <?php 
              if(isset($bill_type) && $bill_type != '' && $bill_type == "last_price"){
              ?>
              <th class="text-center" width="12%">Last Price</th>
              <?php } ?>
		    </tr>
  		</thead>
  		<tbody>
      <?php 
      $i = 1;
      foreach ($purchse_data as $key => $value) {
      ?>
        <tr>
          <td><?php echo $key+1; ?></td>
          <td><?php echo $value['product_code']; ?></td>
          <td class="text-center"><?php echo $value['product_name']; ?></td>
          <td class="text-center"><?php echo $value['mfg_company']; ?></td>
          <td class="text-center"><?php echo $value['generic_name']; ?></td>
          <td style="text-align: right;"><?php echo amount_format(number_format($value['opening_qty'], 2, '.', '')); ?></td>
          <?php 
          if(isset($bill_type) && $bill_type != '' && $bill_type == "avg_price"){
          ?>
          <td style="text-align: right;"><?php echo amount_format(number_format($value['A_v'], 2, '.', '')); ?></td>
        </tr>
        <?php } ?>
          <?php 
          if(isset($bill_type) && $bill_type != '' && $bill_type == "original_price"){
          ?>
          <td class="text-right" style="text-align: right;"><?php echo amount_format(number_format($value['rate'], 2, '.', '')); ?></td>
          <?php } ?>
          <?php 
          if(isset($bill_type) && $bill_type != '' && $bill_type == "last_price"){
          ?>
          <td class="text-right" style="text-align: right;"><?php echo amount_format(number_format($value['rate'], 2, '.', '')); ?></td>
          <?php } ?>
      <?php $i++; } ?>
        </tbody>

  		

	</table>
</body>
</html>