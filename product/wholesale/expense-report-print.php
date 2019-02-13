<?php include('include/usertypecheck.php');?>
<?php 
$from = (isset($_GET['from']) && $_GET['from'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_GET['from']))) : '';
$to = (isset($_GET['to']) && $_GET['to'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_GET['to']))) : '';
$transport = (isset($_GET['transport'])) ? $_GET['transport'] : '';
$searchdata = GetExpenseData($from, $to, $transport); 
$pharmacy = getPharmacyDetail();

?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Digibooks | Print Expense Report</title>
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
            <span style="font-size:18px"><b> Expense Report </b></span> <br>
            
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
        <th>Sr. No</th>
        <th>Expense Date</th>
        <th>Transport Name</th> 
        <th>Remark</th>
        <th>Amount</th>
      </tr>
    </thead>
    <tbody>
      <?php $total_amount = 0; ?>
      
      <?php if(isset($searchdata) && !empty($searchdata)){ ?>
        <?php foreach ($searchdata as $key => $value) { ?>
          <tr>
            <td><?php echo $key+1; ?></td>
            <td><?php echo (isset($value['ex_date']) && $value['ex_date'] != '') ? date('d/m/Y', strtotime($value['ex_date'])) : ''; ?></td>

            
            <td><?php
            $transport = "SELECT name from transport_master where id ='".$value['transport']."'";
            $transportR = mysqli_query($conn,$transport);
            $transportL = mysqli_fetch_assoc($transportR);

            echo $transportL['name'];
            ?>
          </td>
          <td><?php echo (isset($value['remarks']) && $value['remarks'] != '') ? $value['remarks'] : ''; ?></td>
          <td style="text-align:right;">
            <?php
            if(isset($value['amount']) && $value['amount'] != ''){
              echo  number_format($value['amount'], 2, '.', '');
              $total_amount += $value['amount'];
            }
            ?>
          </td>
        </tr>
      <?php } ?>
    <?php } ?>
  </tbody>

  <tr style="font-size:14px;font-weight:bold;">
    <td colspan="4" style="text-align:center;">Total</td>
    <td style="text-align:right;padding-right:10px;">
      <?php
      echo number_format($total_amount, 2, '.', '');
      ?>
    </td>
  </tr>

</table>
</body>
</html>