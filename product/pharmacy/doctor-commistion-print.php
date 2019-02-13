<?php include('include/usertypecheck.php');?>
<?php 
	$from = (isset($_GET['from']) && $_GET['from'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_GET['from']))) : '';
	$to = (isset($_GET['to']) && $_GET['to'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_GET['to']))) : '';
	$doctor = (isset($_GET['doctor'])) ? $_GET['doctor'] : '';
    $pharmacy = getPharmacyDetail();
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Digibooks | Print Doctor Commission Report</title>
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
    				<span style="font-size:18px">Ladger: <b> Doctor Commission Report </b></span> <br>
      			</td>
  			</tr>
  			
  			<tr>
  			    <?php 
      			    $doctor_name = "SELECT personal_title, name FROM doctor_profile WHERE id = '".$doctor."'";
      			    $run = mysqli_query($conn, $doctor_name);
      			    $data = mysqli_fetch_assoc($run);
      			?>
      			<td style="border:none;padding:10px;color:#CC0000;font-size:16px;text-align:center;line-height:20px;">
    				<span style="font-size:18px">Doctor: <b> <?php if($data['personal_title'] != ''){ echo $data['personal_title'].". ".$data['name']; } else{ echo $data['name']; } ?> </b></span> <br>
      			</td>
  			</tr>
		</tbody>
		</table>
 		<h3 class="sub-title"><strong>Period <?php echo (isset($from) && $from != '') ? date('d,M Y',strtotime($from)) : ''; ?> to <?php echo (isset($to) && $to != '') ? date('d,M Y',strtotime($to)) : ''; ?></strong> </h3>
	</center>
	

	<!--<span style="float:right;color:#000000;font-size:12px;margin-top: -30px;  margin-right: 30px;">(All Amounts in Rs.)</span>!-->

  <?php
    $doctorqry = "SELECT tb.id as tb_id, tb.invoice_date, doctor_profile.commission, SUM((tbd.totalamount)-((tbd.totalamount*tbd.gst)/(tbd.gst+100))) as taxable_amount FROM `tax_billing` tb INNER JOIN tax_billing_details tbd ON tb.id = tbd.tax_bill_id INNER JOIN doctor_profile ON doctor_profile.id = tb.doctor WHERE tb.doctor = '".$doctor."' AND tb.invoice_date >= '".$from."' AND tb.invoice_date <= '".$to."' GROUP BY tbd.tax_bill_id";
    $doctorrun = mysqli_query($conn, $doctorqry);
    //$doctor_data = mysqli_fetch_assoc($doctorrun);
  ?>
	
	<table align="center" cellpadding="0" cellspacing="0" border="1" width="100%" id="customers" style="line-height:30px;">
  		<thead>
		    <tr>
		      <th style="text-align: center;">Sr. No</th>
          <th style="text-align: center;">Bill Date</th>
          <th style="text-align: center;">Bill Amount</th>
          <th style="text-align: center;">Commission Amount</th> 
		    </tr>
  		</thead>
  		<tbody>
      <?php 
      $total_amount = 0;
      $i = 0;
      while($doctor_data = mysqli_fetch_assoc($doctorrun)) {
      $i++;
      ?>
        <tr>
          <td style="text-align: center;"><?php echo $i; ?></td>
          <td style="text-align: center;"><?php echo date('d-m-Y', strtotime(str_replace("/", "-", $doctor_data['invoice_date']))); ?></td>
          <td style="text-align: center;"><?php echo amount_format(number_format(abs($doctor_data['taxable_amount']), 2, '.', '')); ?></td>
          <?php $commission = $doctor_data['taxable_amount'] * $doctor_data['commission'] / 100 ; ?>
          <td style="text-align: center;"><?php //echo amount_format(number_format(abs($commission), 2, '.', '')); ?>
            <?php
              if(isset($commission)){
                echo  number_format($commission, 2, '.', '');
                $total_amount += $commission;
              }
            ?>
          </td>
          <?php //$total += $commission; ?>
      <?php } ?>
        </tbody>

  <tr style="font-size:14px;font-weight:bold;">
    <td colspan="3" style="text-align:center;">Total</td>
    <td style="text-align:center;padding-right:10px;">
      <?php
      echo number_format($total_amount, 2, '.', '');
      ?>
    </td>
  </tr>

	</table>
</body>
</html>