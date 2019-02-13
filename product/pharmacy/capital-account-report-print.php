<?php include('include/usertypecheck.php');?>
<?php 
  $from = (isset($_GET['from']) && $_GET['from'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_GET['from']))) : '';
  $to = (isset($_GET['to']) && $_GET['to'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_GET['to']))) : '';
  $id = (isset($_GET['id'])) ? $_GET['id'] : '';
  
  
  $pharmacy = getPharmacyDetail();
  $data = capitalAccountReport($id, $from, $to, 0);

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Digibooks | Print Capital Account Report</title>
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
            <span style="font-size:18px">Report Type : <b> Capital Account Report </b></span>
        </td>
      </tr>
      <tr>
        <td style="border:none;padding:10px;color:#CC0000;font-size:16px;text-align:center;line-height:20px;">
            <span style="font-size:18px">Name : <b> <?php echo (isset($data['name']) && $data['name'] != '') ? ucwords(strtolower($data['name'])) : 'Unknown Name'; ?> </b></span>
        </td>
      </tr>
    </tbody>
  </table>

  <span style="float:right;color:#000000;font-size:12px;margin-top: -30px;  margin-right: 30px;">(All Amounts in Rs.)</span>

  
  <table align="center" cellpadding="0" cellspacing="0" border="1" width="100%" id="customers" style="line-height:30px;">
      <thead>
        <tr>
            <th>Sr No</th>
            <th>Date</th>
            <th>Cr/Dr</th>
            <th>Particulars</th>
            <th>Vch Type</th>
            <th>Vch No</th>
            <th style="text-align:right;">Debit</th>
            <th style="text-align:right;">Credit</th>
        </tr>
      </thead>
      <tbody>
        <?php $total_debit = 0;$total_credit = 0;?>
        <?php if(isset($data) && !empty($data)){?>
            <tr>
              <?php $opening_balance = (isset($data['opening_balance']) && $data['opening_balance'] != '') ? $data['opening_balance'] : 0; ?>
              <td>1</td>
              <td><?php echo (isset($data['date']) && $data['date'] != '') ? date('d-m-Y',strtotime($data['date'])) : ''; ?></td>
              <td><?php echo (isset($opening_balance) && $opening_balance >= 0) ? 'Dr' : 'Cr'; ?></td>
              <td>Opening Balance</td>
              <td></td>
              <td></td>
              <?php
                if($opening_balance >= 0){
                    echo '<td style="text-align:right;">'.amount_format(number_format((abs($data['opening_balance'])), 2, '.', '')).'</td><td></td>';
                    $total_debit += $data['opening_balance'];
                }else{
                    echo '<td></td><td style="text-align:right;">'.amount_format(number_format((abs($data['opening_balance'])), 2, '.', '')).'</td>';
                    $total_credit += abs($data['opening_balance']);
                }
              ?>
            </tr>
            <?php if(isset($data['data']) && !empty($data['data'])){ $i = 2; ?>
                <?php foreach ($data['data'] as $key => $value) { ?>
                    <?php if(!empty($value)){ ?>
                        <?php foreach($value as $k => $v){ ?>
                            <tr>
                                <td><?php echo $i;$i++; ?></td>
                                <td>
                                    <?php
                                        if($k == 0){
                                            echo (isset($key) && $key != '') ? date('d-m-Y',strtotime($key)) : '';
                                        }
                                    ?>
                                </td>
                                <td><?php echo (isset($v['crdr'])) ? $v['crdr'] : ''; ?></td>
                                <td><?php echo (isset($v['perticular'])) ? $v['perticular'] : ''; ?></td>
                                <td><?php echo (isset($v['type'])) ? $v['type'] : ''; ?></td>
                                <td><?php echo (isset($v['voucherno'])) ? $v['voucherno'] : ''; ?></td>
                                <td style="text-align:right;">
                                    <?php
                                        if(isset($v['debit']) && $v['debit'] != ''){
                                            echo amount_format(number_format($v['debit'], 2, '.', ''));
                                            $total_debit += $v['debit'];
                                        }
                                    ?>
                                </td>
                                <td style="text-align:right;">
                                    <?php
                                        if(isset($v['credit']) && $v['credit'] != ''){
                                            echo amount_format(number_format($v['credit'], 2, '.', ''));
                                            $total_credit += $v['credit'];
                                        }
                                    ?>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                <?php } ?>
            <?php } ?>
        <?php } ?>
      </tbody>
      
      <tr style="font-size:14px;font-weight:bold;">
        <td colspan="6" style="text-align:center;"><strong>Total</strong></td>
        <td style="text-align:right;">
            <?php echo (isset($total_debit) && $total_debit != '') ? amount_format(number_format($total_debit, 2, '.', '')) : 0; ?>
        </td>
        <td style="text-align:right;">
          <?php echo (isset($total_credit) && $total_credit != '') ? amount_format(number_format($total_credit, 2, '.', '')) : 0; ?>
        </td>
        
      </tr>
      <tr style="font-size:14px;font-weight:bold;">
          <td colspan="6" style="text-align:center;"><strong>Closing Balance</strong></td>
          <td style="text-align:right;">
              <?php  
                if($total_debit <= $total_credit){
                    echo amount_format(number_format(($total_credit-$total_debit), 2, '.', ''));
                    $total_debit += ($total_credit-$total_debit);
                }
              ?>
          </td>
          <td style="text-align:right;">
              <?php  
                if($total_credit <= $total_debit){
                    echo amount_format(number_format(($total_debit-$total_credit), 2, '.', ''));
                    $total_credit += ($total_debit-$total_credit);
                }
              ?>
          </td>
      </tr>
      <tr style="font-size:14px;font-weight:bold;">
          <td colspan="6" class="text-center"></td>
          <td style="text-align:right;">
              <?php echo (isset($total_debit) && $total_debit != '') ? amount_format(number_format($total_debit, 2, '.', '')) : 0; ?>
          </td>
          <td style="text-align:right;">
              <?php echo (isset($total_credit) && $total_credit != '') ? amount_format(number_format($total_credit, 2, '.', '')) : 0; ?>
          </td>
      </tr>

  </table>
</body>
</html>