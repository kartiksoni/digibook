<?php include('include/usertypecheck.php');

$city_id = (isset($_GET['city_id'])) ? $_GET['city_id'] : '';
$ledger_id = (isset($_GET['ledger_id'])) ? $_GET['ledger_id'] : '';
$from = (isset($_GET['from']) && $_GET['from'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_GET['from']))) : '';
$to = (isset($_GET['to']) && $_GET['to'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_GET['to']))) : '';

if(!empty($ledger_id)){
    $customer = getPerticularDetail($ledger_id);
} else {
    $customer = "";
}
$pharmacy = getPharmacyDetail();
$data = LedgerSummaryReport($from, $to, $city_id, $ledger_id);

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Digibooks | Print Ledger Summary Report</title>
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
    				<span style="font-size:18px">Ledger Summary Report</b></span> <br>
      				
      			</td>
  			</tr>
		</tbody>
		</table>

 		<h3 class="sub-title"><strong>Period <?php echo (isset($from) && $from != '') ? date('d,M Y',strtotime($from)) : ''; ?> to <?php echo (isset($to) && $to != '') ? date('d,M Y',strtotime($to)) : ''; ?></strong> </h3>
	</center>
	

	<span style="float:right;color:#000000;font-size:12px;margin-top: -30px;  margin-right: 30px;">(All Amounts in Rs.)</span>
	<?php if(isset($data)){ ?>
        	<table align="center" cellpadding="0" cellspacing="0" border="1" width="100%" id="customers" style="line-height:30px;">
          		<thead>
        		    <tr>
        		    	<th width="7%">Sr No.</th>
        		      	<th width="7%">Date</th>
        		      	<th>Narration</th>
        		      	<th style="text-align:right" width="15%">Debit</th>
        	     	 	<th style="text-align:right" width="15%">Credit</th>
        		      	<th style="text-align:right" width="15%">Running Balance</th>
        		    </tr>
          		</thead>
          		<tbody>
          			<?php $running_balance = 0;$total_debit = 0;$total_credit = 0; ?>
            		<tr>
              			<td style="text-align:center"></td>
              			<td></td>
              			<td>Opening Balance</td>
          				<td></td>
              			<td></td>
              			<td style="text-align:right;padding-right:10px;">
              				<?php
                              $opening_balance = (isset($data['opening_balance']) && $data['opening_balance'] != '') ? $data['opening_balance'] : 0;
                              echo amount_format(number_format(abs($opening_balance), 2, '.', ''));echo ($opening_balance > 0) ? ' Dr' : ' Cr';
                              $running_balance += $opening_balance;
                            ?>
              			</td>
            		</tr>
            		<?php if(!empty($data['data'])){ ?>
            			<?php foreach ($data['data'] as $key => $value) { ?>
        		    		<tr>
        		      			<td><?php echo $key+1; ?></td>
        		      			<td >
        		      				<?php echo (isset($value['date']) && $value['date'] != '') ? date('d/m/Y', strtotime($value['date'])) : ''; ?>
        		      			</td>
        		      			<td>
        		      				<?php echo (isset($value['narration'])) ? $value['narration'] : ''; ?>
        		      			</td>
        		      			<td style="text-align:right;padding-right:10px;">
        		      				
                                     <?php
                                           if(isset($value['debit']) && $value['debit'] != ''){
                                              echo  amount_format(number_format($value['debit'], 2, '.', ''));
                                              $total_debit += $value['debit'];
                                              $running_balance += $value['debit'];
                                            }
                                        ?>
        		      			</td>
        		      			<td style="text-align:right;padding-right:10px;">
        	                        <?php
        	                           if(isset($value['credit']) && $value['credit'] != ''){
                                          echo  amount_format(number_format($value['credit'], 2, '.', ''));
                                          $total_credit += $value['credit'];
                                          $running_balance -= $value['credit'];
                                        }
                                     ?>
        		      			</td>
        		      			<td style="text-align:right;padding-right:10px;">
        		      				<?php
        	                            echo (isset($running_balance)) ? amount_format(number_format(abs($running_balance), 2, '.', '')) : 0;
        	                            echo (isset($running_balance) && $running_balance > 0) ? ' Dr' : ' Cr';
        	                        ?>
        		      			</td>
        		    		</tr>
        		    	<?php } ?>
        	    	<?php } ?>
          		</tbody>
        
          		<tr style="font-size:14px;font-weight:bold;">
        			<td colspan="3" style="text-align:center;"> Total / Closing Balance</td>
        			
        			<td style="text-align:right;padding-right:10px;"><?php echo (isset($total_debit)) ? amount_format(number_format($total_debit, 2, '.', '')) : 0; ?></td>
        			<td style="text-align:right;padding-right:10px;"><?php echo (isset($total_credit)) ? amount_format(number_format($total_credit, 2, '.', '')) : 0; ?></td>
        			<td style="text-align:right;padding-right:10px;">
        	            <?php
                            echo (isset($running_balance)) ? amount_format(number_format(abs($running_balance), 2, '.', '')) : 0;
                            echo (isset($running_balance) && $running_balance > 0) ? ' Dr' : ' Cr';
                        ?>
        			</td>
          		</tr>
        
        	</table>
  <?php } ?>
</body>
</html>