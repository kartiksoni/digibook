<?php include('include/usertypecheck.php');?>
<?php 
	$from = (isset($_GET['from']) && $_GET['from'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_GET['from']))) : '';
	$to = (isset($_GET['to']) && $_GET['to'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_GET['to']))) : '';

	$pharmacy = getPharmacyDetail();
	$searchdata = getFreightCourierLedger($from, $to, 0);
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Digibooks | Print Freight/Courier Ledger</title>
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
    				<span style="font-size:18px">Ladger: <b> Freight/Courier </b></span> <br>
      				
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
          	<th>Date</th>
          	<th>Voucher No.</th>
          	<th style="text-align: right;">Debit</th>
          	<th style="text-align: right;">Credit</th>
      	</tr> 
  		</thead>
  		 <tbody>
            <?php if(isset($searchdata) && !empty($searchdata)){ ?>
              <?php $totaldebit = 0; $totalcredit = 0; ?>
              <?php foreach ($searchdata as $key => $value) { ?>
                <tr>
                  <td><?php echo $key+1; ?></td>
                  <td><?php echo (isset($value['date']) && $value['date'] != '' && $value['date'] != '0000-00-00') ? date('d/m/Y', strtotime($value['date'])) : ''; ?></td>
                  <td><?php echo (isset($value['no'])) ? $value['no'] : '';  ?></td>
                  <td style="text-align: right;">
                    <?php
                      if(isset($value['debit']) && $value['debit'] != ''){
                        echo amount_format(number_format($value['debit'], 2, '.', ''));
                        $totaldebit += $value['debit'];
                      }
                    ?>
                  </td>
                  <td style="text-align: right;">
                    <?php
                      if(isset($value['credit']) && $value['credit'] != ''){
                        echo amount_format(number_format($value['credit'], 2, '.', ''));
                        $totalcredit += $value['credit'];
                      }
                    ?>
                  </td>
                </tr>
              <?php } ?>
            <?php } ?>
        </tbody>

  		<tr style="font-size:14px;font-weight:bold;">
			<td colspan="3" style="text-align:center;"> Total / Closing Balance</td>
            <td style="text-align:right;padding-right:10px;">
              <?php echo (isset($totaldebit)) ? amount_format(number_format(($totaldebit), 2, '.', '')) : 0; ?>
            </td>
            <td style="text-align:right;padding-right:10px;">
              <?php echo (isset($totalcredit)) ? amount_format(number_format(($totalcredit), 2, '.', '')) : 0; ?>
            </td>
  		</tr>

	</table>
</body>
</html>