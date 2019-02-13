<?php include('include/usertypecheck.php');?>
<?php 
  $from = (isset($_GET['from']) && $_GET['from'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_GET['from']))) : '';
  $to = (isset($_GET['to']) && $_GET['to'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_GET['to']))) : '';
  $doctor = (isset($_GET['doctor'])) ? $_GET['doctor'] : '';
  
  
  $pharmacy = getPharmacyDetail();
  $data = doctorPurchaseReport($from, $to, $doctor);
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Print Doctor Purchase Report</title>
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
    <h3 class="sub-title"><strong>Ledger for the period <?php echo (isset($from) && $from != '') ? date('d,M Y',strtotime($from)) : ''; ?> to <?php echo (isset($to) && $to != '') ? date('d,M Y',strtotime($to)) : ''; ?></strong> </h3>
  </center>
  <table align="center" cellpadding="0" cellspacing="0" border="0" width="100%" id="customers" style="line-height:20px;">
    <tbody>
      <tr>
        <td style="border:none;padding:10px;color:#CC0000;font-size:16px;text-align:center;line-height:20px;">
            <span style="font-size:18px">Report Type : <b> Doctor - Purchase Report </b></span>
        </td>
      </tr>
      <tr>
        <td style="border:none;padding:10px;color:#CC0000;font-size:16px;text-align:center;line-height:20px;">
            <span style="font-size:18px">Name : <b> <?php echo (isset($_GET['doctorname']) && $_GET['doctorname'] != '') ? ucwords(strtolower($_GET['doctorname'])) : 'Unknown Doctor'; ?> </b></span>
        </td>
      </tr>
    </tbody>
  </table>

  <span style="float:right;color:#000000;font-size:12px;margin-top: -30px;  margin-right: 30px;">(All Amounts in Rs.)</span>

  
  <table align="center" cellpadding="0" cellspacing="0" border="1" width="100%" id="customers" style="line-height:30px;">
      <thead>
        <tr>
            <th>Sr. No</th>
            <th>Invoice Date</th>
            <th>Invoice No.</th>
            <th>Vendor Name</th>
            <th>Debit</th>
            <th>Cash</th>
            <th>Running Balance</th>
        </tr>
      </thead>
      <tbody>
        <?php $runningBalance = 0; $debit = 0; $cash = 0; ?>
        <?php if(isset($data['detail']) && !empty($data['detail'])){?>
          <?php foreach ($data['detail'] as $key => $value) { ?>
            <tr>
              <td><?php echo $key+1; ?></td>
              <td><?php echo (isset($value['invoice_date']) && $value['invoice_date'] != '') ? date('d/m/Y',strtotime($value['invoice_date'])) : ''; ?></td>
              <td><?php echo (isset($value['invoice_no'])) ? $value['invoice_no'] : ''; ?></td>
              <td><?php echo (isset($value['vendor'])) ? $value['vendor'] : ''; ?></td>
              <td style="text-align:right;padding-right:10px;">
                <?php
                  if((isset($value['purchase_type']) && $value['purchase_type'] == 'Debit') && (isset($value['amount']) && $value['amount'] != '')){
                      echo amount_format(number_format($value['amount'], 2, '.', ''));
                      $debit += $value['amount'];
                      $runningBalance += $value['amount'];
                  }
                ?>
              </td>
              <td style="text-align:right;padding-right:10px;">
                <?php
                  if((isset($value['purchase_type']) && $value['purchase_type'] == 'Cash') && (isset($value['amount']) && $value['amount'] != '')){
                      echo amount_format(number_format($value['amount'], 2, '.', ''));
                      $cash += $value['amount'];
                      $runningBalance -= $value['amount'];
                  }
                ?>
              </td>
              <td style="text-align:right;padding-right:10px;">
                <?php
                    echo (isset($runningBalance)) ? amount_format(number_format(abs($runningBalance), 2, '.', '')) : 0;
                    echo ($runningBalance >= 0) ? ' Dr' : ' Cr';
                ?>
              </td>
            </tr>
          <?php } ?>
        <?php } ?>
      </tbody>

      <tr style="font-size:14px;font-weight:bold;">
        <td colspan="4" style="text-align:center;"><strong>Total</strong></td>
        <td style="text-align:right;padding-right: 10px;">
          <?php echo (isset($debit)) ? amount_format(number_format($debit, 2, '.', '')) : 0; ?>
        </td>
        <td style="text-align:right;padding-right: 10px;">
          <?php echo (isset($cash)) ? amount_format(number_format($cash, 2, '.', '')) : 0; ?>
        </td>
        <td style="text-align:right;padding-right: 10px;">
            <?php 
                echo (isset($runningBalance)) ? amount_format(number_format(abs($runningBalance), 2, '.', '')) : 0;
                echo ($runningBalance >= 0) ? ' Dr' : ' Cr';
            ?>
        </td>
      </tr>

  </table>
</body>
</html>