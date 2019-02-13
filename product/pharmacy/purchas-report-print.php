<?php include('include/usertypecheck.php');?>
<?php 
	$from = (isset($_GET['from']) && $_GET['from'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_GET['from']))) : ''; 
  	$to = (isset($_GET['to']) && $_GET['to'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_GET['to']))) : '';
  	$gst = (isset($_GET['gst'])) ? $_GET['gst'] : '';

    $searchdata = getpurchasreport($from , $to, $gst);


    $pharmacy = getPharmacyDetail();
	
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Digibooks | Print Transport Report</title>
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
    				<span style="font-size:18px"> <b> Purchase report </b></span> <br>
      				
      			</td>
  			</tr>
		</tbody>
		</table>
 		<h3 class="sub-title"><strong>Period <?php echo (isset($from) && $from != '') ? date('d,M Y',strtotime($from)) : ''; ?> to <?php echo (isset($to) && $to != '') ? date('d,M Y',strtotime($to)) : ''; ?></strong> </h3>
	</center>
	
	<table align="center" cellpadding="0" cellspacing="0" border="1" width="100%" id="customers" style="line-height:30px;">
  		<thead>
		    <tr>
		    	<th>Sr. No</th>
                <th>Date</th>
                <th>Invoice No.</th>
                <th>Party Name</th>
                <th>GST NO.</th>
                <th>Taxable Amount</th> 
                <th>Tax Amount</th>
                <th>Total Amount</th>
		    </tr>
  		</thead>
  		<tbody>
  			<?php
               $total = 0;
               if(!empty($searchdata)){
                foreach($searchdata as $key => $value){
                  ?>
                  <tr>
                    <td width="7%"><?php echo $key+1; ?></td>
                    <td width="8%"><?php echo (isset($value['invoice_date']) && $value['invoice_date'] != '') ? date('d/m/Y', strtotime($value['invoice_date'])) : ''; ?></td>
                    <td><?php echo (isset($value['invoice_no'])) ? $value['invoice_no'] : '';  ?></td>
                    <td class="text-center"><?php echo (isset($value['name'])) ? $value['name'] : '';  ?></td>
                    <td class="text-center"><?php echo (isset($value['gstno'])) ? $value['gstno'] : '';  ?></td>
                    <td class="text-center"><?php echo (isset($value['taxable_amount'])) ? $value['taxable_amount'] : '';  ?></td>
                    <td class="text-center"><?php echo (isset($value['tax_amount'])) ? $value['tax_amount'] : '';  ?></td>
                    <td class="text-center"><?php echo (isset($value['total_amount'])) ? $value['total_amount'] : '';  ?></td>
                    <?php $total += $value['total_amount']; ?>
                  </tr>
                <?php } } ?>
              </tbody>
              <tfoot>
                <tr style="background-color: #EFEFEF;">
                  <th colspan="7" class="text-center"><strong>Total / Purchase Balance</strong></th>

                  <th class="text-center"><?php echo (isset($total)) ? amount_format(number_format($total, 2, '.', '')) : 0; ?></th>
                </tr>
              </tfoot>
  	</table>
</body>
</html>