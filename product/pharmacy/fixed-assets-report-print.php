<?php include('include/usertypecheck.php');?>
<?php 
  $from = (isset($_GET['from']) && $_GET['from'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_GET['from']))) : '';
  $to = (isset($_GET['to']) && $_GET['to'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_GET['to']))) : '';
  $id = (isset($_GET['id'])) ? $_GET['id'] : '';
  
  
  $pharmacy = getPharmacyDetail();
  $searchdata = fixedAssetsReport($id, $from, $to);

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Digibooks | Print Fixed Assets Report</title>
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
            <span style="font-size:18px">Report Type : <b> Fixed Assets Report </b></span>
        </td>
      </tr>
      <!--<tr>
        <td style="border:none;padding:10px;color:#CC0000;font-size:16px;text-align:center;line-height:20px;">
            <span style="font-size:18px">Name : <b> <?php //echo (isset($data['name']) && $data['name'] != '') ? ucwords(strtolower($data['name'])) : 'Unknown Name'; ?> </b></span>
        </td>
      </tr>-->
    </tbody>
  </table>

  <span style="float:right;color:#000000;font-size:12px;margin-top: -30px;  margin-right: 30px;">(All Amounts in Rs.)</span>

  
    <table align="center" cellpadding="0" cellspacing="0" border="1" width="100%" id="customers" style="line-height:30px;">
        <thead>
            <tr>
                <th>Sr. No</th>
                <th>Date</th>
                <th>Particulars</th>
                <th>Vch Type</th>
                <th>Vch No.</th>
                <th style="text-align:right;">Debit</th>
                <th style="text-align:right;">Credit</th>
            </tr>
        </thead>
        <tbody>
            <?php if(isset($searchdata) && !empty($searchdata)){?>
                <?php foreach($searchdata as $key1 => $value1){ ?>
                    <?php $total_debit = 0;$total_credit = 0;?>
                    <tr>
                        <td colspan="7" style="text-align:center;">
                            <strong style="border-top: 1px solid;border-bottom: 1px solid;padding-top: 2px;padding-bottom: 2px;font-size: 13px;"><?php echo (isset($value1['name']) && $value1['name'] != '') ? ucwords(strtolower($value1['name'])) : 'Unknown Name'; ?></strong>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:center;">1</td>
                        <td style="text-align:center;"><?php echo (isset($value1['date'])) ? $value1['date'] : ''; ?></td>
                        <td>Opening Balance</td>
                        <td></td>
                        <td></td>
                        <td style="text-align:right;">
                            <?php
                                if(isset($value1['type']) && $value1['type'] == 'DB'){
                                    $opening_balance = (isset($value1['opening_balance']) && $value1['opening_balance'] != '') ? $value1['opening_balance'] : 0;
                                    echo amount_format(number_format(($opening_balance), 2, '.', ''));
                                    $total_debit += $opening_balance;
                                }
                            ?>
                        </td>
                        <td style="text-align:right;">
                            <?php
                                if(isset($value1['type']) && $value1['type'] == 'CR'){
                                    $opening_balance = (isset($value1['opening_balance']) && $value1['opening_balance'] != '') ? $value1['opening_balance'] : 0;
                                    echo amount_format(number_format(($opening_balance), 2, '.', ''));
                                    $total_credit += $opening_balance;
                                }
                            ?>
                        </td>
                    </tr>
                    <?php if(isset($value1['detail']) && !empty($value1['detail'])){ ?>
                        <?php foreach($value1['detail'] as $key2 => $value2){ ?>
                            <?php if(isset($value2) && !empty($value2)){ ?>
                                <?php foreach($value2 as $key3 => $value3){ ?>
                                    <tr>
                                        <td style="text-align:center;"><?php echo ($key3+2); ?></td>
                                        <td style="text-align:center;">
                                            <?php
                                                if($key3 == 0){
                                                    echo (isset($key2)) ? $key2 : '';
                                                }
                                            ?>
                                        </td>
                                        <td><?php echo (isset($value3['perticular'])) ? $value3['perticular'] : ''; ?></td>
                                        <td><?php echo (isset($value3['type'])) ? $value3['type'] : ''; ?></td>
                                        <td><?php echo (isset($value3['voucherno'])) ? $value3['voucherno'] : ''; ?></td>
                                        <td style="text-align:right;">
                                            <?php
                                                if(isset($value3['debit']) && $value3['debit'] != ''){
                                                    echo amount_format(number_format($value3['debit'], 2, '.', ''));
                                                    $total_debit += $value3['debit'];
                                                }
                                            ?>
                                        </td>
                                        <td style="text-align:right;">
                                            <?php
                                                if(isset($value3['credit']) && $value3['credit'] != ''){
                                                    echo amount_format(number_format($value3['credit'], 2, '.', ''));
                                                    $total_credit += $value3['credit'];
                                                }
                                            ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>
                    
                    <!-- COUNT TOTAL CLOSING BLANCE START-->
                    <tr>
                        <td colspan="5" style="text-align:center;"><strong>Total</strong></td>
                        <td style="border-top: 2px solid;text-align:right;"><?php echo (isset($total_debit) && $total_debit != '') ? amount_format(number_format($total_debit, 2, '.', '')) : 0; ?></td>
                        <td style="border-top: 2px solid;text-align:right;"><?php echo (isset($total_credit) && $total_credit != '') ? amount_format(number_format($total_credit, 2, '.', '')) : 0; ?></td>
                    </tr>
                    <tr>
                        <td colspan="5" style="text-align:center;"><strong>Closing Balance</strong></td>
                        <?php
                            if($total_debit <= $total_credit){
                                echo '<td style="text-align:right;">'.amount_format(number_format(($total_credit-$total_debit), 2, '.', '')).'</td><td></td>';
                                $total_debit += ($total_credit-$total_debit);
                            }else{
                                echo '<td></td><td style="text-align:right;">'.amount_format(number_format(($total_debit-$total_credit), 2, '.', '')).'</td>';
                                $total_credit += ($total_debit-$total_credit);
                            }
                        ?>
                    </tr>
                    <tr>
                        <td colspan="5"></td>
                        <td style="border-top: 2px solid;border-bottom: 2px solid;text-align:right;"><strong><?php echo (isset($total_debit) && $total_debit != '') ? amount_format(number_format($total_debit, 2, '.', '')) : 0; ?></strong></td>
                        <td style="border-top: 2px solid;border-bottom: 2px solid;text-align:right;"><strong><?php echo (isset($total_credit) && $total_credit != '') ? amount_format(number_format($total_credit, 2, '.', '')) : 0; ?></strong></td>
                    </tr>
                    <!-- COUNT TOTAL CLOSING BLANCE END-->
                    
                <?php } ?>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>