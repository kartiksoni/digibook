<?php include('include/usertypecheck.php');?>
<?php 
  $pharmacy = getPharmacyDetail();

  $from = (isset($_GET['from']) && $_GET['from'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_GET['from']))) : '';
  $to = (isset($_GET['to']) && $_GET['to'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_GET['to']))) : '';
  $id = (isset($_GET['id'])) ? $_GET['id'] : '';
  $searchdata = investmentReport($id, $from, $to, 0);
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Digibooks | Print Investment Report</title>
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
    .perticular{
      border-top: 1px solid;
      border-bottom: 1px solid;
        padding: 2px;
        font-weight: bold;
    }
    .text-bold{
      font-weight: bold;
    }
  </style>
</head>
<body>
  <center>
    <h3 class="panel-title"><strong> <?php echo (isset($pharmacy['pharmacy_name'])) ? ucwords(strtolower($pharmacy['pharmacy_name'])) : 'Unknown pharmacy'; ?> </strong> <br>
        <span style="font-size:16px;">GST NO : <?php echo (isset($pharmacy['gst_no'])) ? $pharmacy['gst_no'] : 'Unknown GSTNO'; ?></span>
      </h3>
    <h3 class="sub-title"><strong>Ledger for the period 1-Apr-2017 to 31-Mar-2018</strong> </h3>
  </center>
  <table align="center" cellpadding="0" cellspacing="0" border="0" width="100%" id="customers" style="line-height:20px;">
    <tbody>
      <tr>
        <td style="border:none;padding:10px;color:#CC0000;font-size:16px;text-align:center;line-height:20px;">
            <span style="font-size:18px">Report Type : <b> Investment Report </b></span>
        </td>
      </tr>
    </tbody>
  </table>

  <span style="float:right;color:#000000;font-size:12px;margin-top: -30px;  margin-right: 30px;">(All Amounts in Rs.)</span>

  <table align="center" cellpadding="0" cellspacing="0" border="1" width="100%" id="customers" style="line-height:30px;">
    <thead>
      <tr>
        <th>Date</th>
        <th>Particulars</th>
        <th>Vch Type</th>
        <th>Vch No.</th>
        <th colspan="3">Inwards</th>
        <th colspan="3">Outwards</th>
        <th colspan="3">Closing</th>
      </tr>
      <tr>
        <th colspan="4"></th>
        <th>Qty</th>
        <th>Rate</th>
        <th>value</th>
        <th>Qty</th>
        <th>Rate</th>
        <th>value</th>
        <th>Qty</th>
        <th>Rate</th>
        <th>value</th>
      </tr>
    </thead>
    <tbody>
      <?php if(isset($searchdata) && !empty($searchdata)){ ?>
          <?php foreach ($searchdata as $key => $value) { ?>
            <tr>
              <td></td>
              <td><span class="perticular">
                <?php echo (isset($value['name']) && $value['name'] != '') ? ucwords(strtolower($value['name'])) : 'Unknown Ledger'; ?>
              </td>
              <td colspan="2"></td>
              <td colspan="3"></td>
              <td colspan="3"></td>
              <td colspan="3"></td>
            </tr>
            <?php $totalInwardQty = 0; $totalInwardRate = 0; $totalInwardValue = 0; $totalOutwardQty = 0; $totalOutwardRate = 0; $totalOutwardValue = 0; $qtyRunning = 0; $rateRunning = 0; $valueRunning = 0; ?>
            <?php if(isset($value['detail']) && !empty($value['detail'])){ ?>
              <?php foreach ($value['detail'] as $k1 => $v1) { ?>
                  <?php if(isset($v1) && !empty($v1)){ ?>
                    <?php foreach ($v1 as $k2 => $v2) { ?>
                      <tr>
                        <td>
                          <?php
                              if($k2 == 0){
                                echo (isset($k1)) ? $k1 : '';
                              }
                          ?>
                        </td>
                        <td class="text-bold"><?php echo (isset($v2['perticular'])) ? $v2['perticular'] : 'Unknown Perticular'; ?></td>
                        <td class="text-bold"><?php echo (isset($v2['type'])) ? $v2['type'] : ''; ?></td>
                        <td><?php echo (isset($v2['voucherno'])) ? $v2['voucherno'] : ''; ?></td>

                        <td>
                          <?php
                            if(isset($v2['inward']['qty']) && $v2['inward']['qty'] != '' && $v2['inward']['qty'] != 0){
                              echo amount_format(number_format($v2['inward']['qty'], 2, '.', ''));
                              $totalInwardQty += $v2['inward']['qty'];
                              $qtyRunning += $v2['inward']['qty'];
                            }
                          ?>
                        </td>
                        <td>
                          <?php
                            if(isset($v2['inward']['rate']) && $v2['inward']['rate'] != '' && $v2['inward']['rate'] != 0){
                              echo amount_format(number_format($v2['inward']['rate'], 2, '.', ''));
                              $totalInwardRate += $v2['inward']['rate'];
                              $rateRunning += $v2['inward']['rate'];
                            }
                          ?>
                        </td>
                        <td class="text-bold">
                          <?php
                            if(isset($v2['inward']['value']) && $v2['inward']['value'] != '' && $v2['inward']['value'] != 0){
                              echo amount_format(number_format($v2['inward']['value'], 2, '.', ''));
                              $totalInwardValue += $v2['inward']['value'];
                              $valueRunning += $v2['inward']['value'];
                            }
                          ?>
                        </td>

                        <td>
                          <?php
                            if(isset($v2['outward']['qty']) && $v2['outward']['qty'] != '' && $v2['outward']['qty'] != 0){
                              echo amount_format(number_format($v2['outward']['qty'], 2, '.', ''));
                              $totalOutwardQty += $v2['outward']['qty'];
                              $qtyRunning -= $v2['outward']['qty'];
                            }
                          ?>
                        </td>
                        <td>
                          <?php
                            if(isset($v2['outward']['rate']) && $v2['outward']['rate'] != '' && $v2['outward']['rate'] != 0){
                              echo amount_format(number_format($v2['outward']['rate'], 2, '.', ''));
                              $totalOutwardRate += $v2['outward']['rate'];
                              $rateRunning -= $v2['outward']['rate'];
                            }
                          ?>
                        </td>
                        <td class="text-bold">
                          <?php
                            if(isset($v2['outward']['value']) && $v2['outward']['value'] != '' && $v2['outward']['value'] != 0){
                              echo amount_format(number_format($v2['outward']['value'], 2, '.', ''));
                              $totalOutwardValue += $v2['outward']['value'];
                              $valueRunning -= $v2['outward']['value'];
                            }
                          ?>
                        </td>

                        <td>
                          <?php echo (isset($qtyRunning) && $qtyRunning != '' && $qtyRunning != 0) ? amount_format(number_format($qtyRunning, 2, '.', '')) : ''; ?>
                        </td>
                        <td>
                          <?php echo (isset($rateRunning) && $rateRunning != '' && $rateRunning != 0) ? amount_format(number_format($rateRunning, 2, '.', '')) : ''; ?>
                        </td>
                        <td class="text-bold">
                          <?php echo (isset($valueRunning) && $valueRunning != '' && $valueRunning != 0) ? amount_format(number_format($valueRunning, 2, '.', '')) : ''; ?>
                        </td>
                      </tr>
                    <?php } ?>
                  <?php } ?>
              <?php } ?>
            <?php } ?>
            <tr>
              <td colspan="4" class="text-center text-bold">Totals as per 'Default' valuation :</td>

              <td>
                <?php echo (isset($totalInwardQty) && $totalInwardQty != '' && $totalInwardQty != 0) ? amount_format(number_format($totalInwardQty, 2, '.', '')) : ''; ?>
              </td>
              <td>
                <?php echo (isset($totalInwardRate) && $totalInwardRate != '' && $totalInwardRate != 0) ? amount_format(number_format($totalInwardRate, 2, '.', '')) : ''; ?>
              </td>
              <td class="text-bold">
                <?php echo (isset($totalInwardValue) && $totalInwardValue != '' && $totalInwardValue != 0) ? amount_format(number_format($totalInwardValue, 2, '.', '')) : ''; ?>
              </td>

              <td>
                <?php echo (isset($totalOutwardQty) && $totalOutwardQty != '' && $totalOutwardQty != 0) ? amount_format(number_format($totalOutwardQty, 2, '.', '')) : ''; ?>
              </td>
              <td>
                <?php echo (isset($totalOutwardRate) && $totalOutwardRate != '' && $totalOutwardRate != 0) ? amount_format(number_format($totalOutwardRate, 2, '.', '')) : ''; ?>
              </td>
              <td class="text-bold">
                <?php echo (isset($totalOutwardValue) && $totalOutwardValue != '' && $totalOutwardValue != 0) ? amount_format(number_format($totalOutwardValue, 2, '.', '')) : ''; ?>
              </td>
              
              <td>
                <?php echo (isset($qtyRunning) && $qtyRunning != '' && $qtyRunning != 0) ? amount_format(number_format($qtyRunning, 2, '.', '')) : ''; ?>
              </td>
              <td>
                <?php echo (isset($rateRunning) && $rateRunning != '' && $rateRunning != 0) ? amount_format(number_format($rateRunning, 2, '.', '')) : ''; ?>
              </td>
              <td class="text-bold">
                <?php echo (isset($valueRunning) && $valueRunning != '' && $valueRunning != 0) ? amount_format(number_format($valueRunning, 2, '.', '')) : ''; ?>
              </td>
            </tr>
          <?php } ?>
      <?php } ?>
    </tbody>
  </table>  

</body>
</html>