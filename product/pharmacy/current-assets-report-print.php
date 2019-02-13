<?php include('include/usertypecheck.php');?>
<?php 
  $from = (isset($_GET['from']) && $_GET['from'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_GET['from']))) : '';
  $to = (isset($_GET['to']) && $_GET['to'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_GET['to']))) : '';
  $id = (isset($_GET['id'])) ? $_GET['id'] : '';
  
  
  $pharmacy = getPharmacyDetail();
  //$data = CurrentAssetsReport($id, $from, $to);
    if(isset($_GET['from']) && isset($_GET['to'])){
        $searchdata = getCurrentAssetsSecond($_GET['from'], $_GET['to']);
    }

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Digibooks | Print Current Assets Report</title>
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
  .font-13{font-size:13px;}
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
                <th>Particulars</th>
                <th style="text-align:right;" width="15%">Debit</th>
                <th style="text-align:right;" width="15%">Credit</th>
            </tr>
        </thead>
        <tbody>
        <?php $grandtotal = 0; ?>
        <?php if(isset($searchdata) && !empty($searchdata)){ ?>
            <?php foreach($searchdata as $key => $value){ ?>
                <tr>
                    <td><strong class="font-13"><?php echo (isset($value['name'])) ? $value['name'] : ''; ?></strong></td>
                    <td style="border-bottom: 2px solid;text-align:right;">
                        <strong>
                            <?php
                                if(isset($value['totaldebit']) && $value['totaldebit'] != '' && $value['totaldebit'] >= 0){
                                    echo amount_format(number_format($value['totaldebit'], 2, '.', ''));
                                    $grandtotal += $value['totaldebit'];
                                }
                            ?>
                        </strong>
                    </td>
                    <td style="border-bottom: 2px solid;text-align:right;">
                        <strong>
                            <?php
                                if(isset($value['totaldebit']) && $value['totaldebit'] != '' && $value['totaldebit'] < 0){
                                    echo amount_format(number_format($value['totaldebit'], 2, '.', ''));
                                    $grandtotal += $value['totaldebit'];
                                }
                            ?>
                        </strong>
                    </td>
                </tr>
                <?php if(isset($value['detail']) && !empty($value['detail'])){ ?>
                    <?php foreach($value['detail'] as $k => $v){ ?>
                        <tr>
                            <td style="padding-left:30px;"><?php echo (isset($v['name'])) ? $v['name'] : ''; ?></td>
                            <td style="text-align:right;">
                                <?php
                                    if(isset($v['debit']) && $v['debit'] != '' && $v['debit'] >= 0){
                                        echo amount_format(number_format($v['debit'], 2, '.', '')); 
                                    }
                                ?>
                            </td>
                            <td style="text-align:right;">
                                <?php
                                    if(isset($v['debit']) && $v['debit'] != '' && $v['debit'] < 0){
                                        echo amount_format(number_format($v['debit'], 2, '.', '')); 
                                    }
                                ?>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } ?>
            <?php } ?>
        <?php } ?>
        <tr style="background-color: #EFEFEF;">
            <td style="text-align:center;"><strong class="font-13">Grand Total</strong></td>
            <td style="text-align:right;">
                <strong class="font-13">
                    <?php
                        if(isset($grandtotal) && $grandtotal >= 0){
                            echo amount_format(number_format($grandtotal, 2, '.', ''));     
                        }
                    ?>
                </strong>
            </td>
            <td style="text-align:right;">
                <strong class="font-13">
                    <?php
                        if(isset($grandtotal) && $grandtotal < 0){
                            echo amount_format(number_format($grandtotal, 2, '.', ''));     
                        }
                    ?>
                </strong>
            </td>
        </tr>
        </tbody>
    </table>
</body>
</html>