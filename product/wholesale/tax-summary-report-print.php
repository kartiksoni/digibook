<?php include('include/usertypecheck.php');?>
<?php 
	$from = (isset($_GET['from']) && $_GET['from'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_GET['from']))) : '';
	$to = (isset($_GET['to']) && $_GET['to'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_GET['to']))) : '';

	$pharmacy = getPharmacyDetail();
	$searchdata = getTaxSummaryReport($from, $to, 0);
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Digibooks | Print Tax Summary Report</title>
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
    				<span style="font-size:18px"><b> Tax Summary Report </b></span> <br>
      				
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
              	<th>Invoice Date</th>
              	<th>Invoice Number</th> 
              	<th>Party Name</th>
              	<th>Bill Type</th>
              	<th>Sale/Purchase</th>
              	<th style="text-align: right;">CGST</th>
              	<th style="text-align: right;">SGST</th>
              	<th style="text-align: right;">IGST</th>
              	<th style="text-align: right;">Amount</th>
          	</tr> 
  		</thead>
  		 <tbody>
            <?php if(isset($searchdata) && !empty($searchdata)){ ?>
              <?php $totaligstS = 0; $totaligstP = 0; $totalcgstS = 0; $totalcgstP = 0; $totalsgstS = 0; $totalsgstP = 0; $totalamountS = 0; $totalamountP = 0; ?>
              <?php foreach ($searchdata as $key => $value) { ?>
                <tr>
                  <td><?php echo $key+1; ?></td>
                  <td><?php echo (isset($value['invoice_date']) && $value['invoice_date'] != '' && $value['invoice_date'] != '0000-00-00') ? date('d/m/Y', strtotime($value['invoice_date'])) : ''; ?></td>
                  <td><?php echo (isset($value['invoice_no'])) ? $value['invoice_no'] : '';  ?></td>
                  <td><?php echo (isset($value['perticular'])) ? $value['perticular'] : ''; ?></td>
                  <td><?php echo (isset($value['bill_type'])) ? $value['bill_type'] : ''; ?></td>
                  <td><?php echo (isset($value['type'])) ? $value['type'] : ''; ?></td>
                  <td style="text-align: right;">
                    <?php
                      if(isset($value['cgst']) && $value['cgst'] != ''){
                        echo amount_format(number_format($value['cgst'], 2, '.', ''));
                        if(isset($value['type']) && $value['type'] == 'Purchase'){
                          $totalcgstP += $value['cgst'];
                        }else{
                          $totalcgstS += $value['cgst'];
                        }
                      }
                    ?>
                  </td>
                  <td style="text-align: right;">
                    <?php
                      if(isset($value['sgst']) && $value['sgst'] != ''){
                        echo amount_format(number_format($value['sgst'], 2, '.', ''));
                        if(isset($value['type']) && $value['type'] == 'Purchase'){
                          $totalsgstP += $value['sgst'];
                        }else{
                          $totalsgstS += $value['sgst'];
                        }
                      }
                    ?>
                  </td>
                  <td style="text-align: right;">
                    <?php
                      if(isset($value['igst']) && $value['igst'] != ''){
                        echo amount_format(number_format($value['igst'], 2, '.', ''));
                        if(isset($value['type']) && $value['type'] == 'Purchase'){
                          $totaligstP += $value['igst'];
                        }else{
                          $totaligstS += $value['igst'];
                        }
                      }
                    ?>
                  </td>
                  <td style="text-align: right;">
                    <?php
                      if(isset($value['amount']) && $value['amount'] != ''){
                        echo amount_format(number_format($value['amount'], 2, '.', ''));
                        if(isset($value['type']) && $value['type'] == 'Purchase'){
                          $totalamountP += $value['amount'];
                        }else{
                          $totalamountS += $value['amount'];
                        }
                      }
                    ?>
                  </td>
                </tr>
              <?php } ?>
            <?php } ?>
        </tbody>

  		<tr style="font-size:14px;font-weight:bold;">
			<td colspan="6" style="text-align:center;"> Total / Closing Balance</td>

            <td style="text-align:right;padding-right:10px;">
              <?php echo (isset($totalcgstS) && isset($totalcgstP)) ? amount_format(number_format(($totalcgstS-$totalcgstP), 2, '.', '')) : 0; ?>
            </td>
            <td style="text-align:right;padding-right:10px;">
              <?php echo (isset($totalsgstS) && isset($totalsgstP)) ? amount_format(number_format(($totalsgstS-$totalsgstP), 2, '.', '')) : 0; ?>
            </td>
			<td style="text-align:right;padding-right:10px;">
              <?php echo (isset($totaligstS) && isset($totaligstP)) ? amount_format(number_format(($totaligstS-$totaligstP), 2, '.', '')) : 0; ?>
            </td>
            <td style="text-align:right;padding-right:10px;">
              <?php echo (isset($totalamountS) && isset($totalamountP)) ? amount_format(number_format(($totalamountS-$totalamountP), 2, '.', '')) : 0; ?>
            </td>
  		</tr>

	</table>
</body>
</html>