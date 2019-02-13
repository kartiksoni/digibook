<?php $title = "Trading Account Report"; ?>
<?php include('include/usertypecheck.php'); ?>
<?php //include('include/permission.php'); ?>
<?php
  $pharmacy = getPharmacyDetail();
?>

<?php 
  $owner_id = (isset($_SESSION['auth']['owner_id']) && $_SESSION['auth']['owner_id'] != '') ? $_SESSION['auth']['owner_id'] : '';
  $admin_id = (isset($_SESSION['auth']['admin_id']) && $_SESSION['auth']['admin_id'] != '') ? $_SESSION['auth']['admin_id'] : '';
  $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : '';
  $financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';
?>
<?php 
    /*-----------------------GET FINANCIAL YEAR START-----------------------------*/
    /*$financialYearQ = "SELECT id, f_name, start_date, end_date FROM financial WHERE id = '".$financial_id."'";
    $financialYearR = mysqli_query($conn, $financialYearQ);
    if($financialYearR && mysqli_num_rows($financialYearR) > 0){
        $financialYearRow = mysqli_fetch_assoc($financialYearR);
    }*/
    /*-----------------------GET FINANCIAL YEAR END-----------------------------*/
    $from = (isset($_GET['from']) && $_GET['from'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_GET['from']))) : ''; 
    $to = (isset($_GET['to']) && $_GET['to'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_GET['to']))) : '';
    $data = balanceSheetReport($from, $to, 1);
    $profit_los = profit_loss($from, $to);
    $netprofit = (isset($profit_los['profit']['NetProfit']) && $profit_los['profit']['NetProfit'] != '') ? $profit_los['profit']['NetProfit'] : 0;

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

  <title>Digibooks | Balance Sheet Report Print</title>

  <link rel="shortcut icon" href="images/favicon.png" />
  <!-- <link rel="stylesheet" href="plugins/morris/morris.css"> -->

  <!-- App css -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <!-- <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" /> -->

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

 }

 #customers td, #customers th {

   font-size:1em;
   padding:3px 8px;
 }

 #customers th {

   font-size:1.1em;
   text-align:left;
   color:#000000;
   padding:8px 12px 8px 12px;

 }

 #customers tr td {

   color:#000000;
   font-size:14px !important;
   padding:8px;
 }


</style>
</head>
<body>

  <table align="center" cellpadding="0" cellspacing="0" border="1" width="100%" id="customers" style="line-height:30px;">
    <thead>
      <tr>
        <th>
          <center>
            <h2 class="title">
              <strong><?php echo $pharmacy['pharmacy_name'];?>
                <br>
                <span style="font-size:16px;">GST NO : <?php echo (isset($pharmacy['gst_no'])) ? $pharmacy['gst_no'] : 'Unknown GSTNO'; ?></span>
              </strong>
            </h2>
            <h3 class="sub-title">
              <strong>Balance Sheet Report</strong> <br><br>
              <strong>Ledger for the period <?php echo (isset($from) && $from != '') ? date('d,M Y',strtotime($from)) : ''; ?> to <?php echo (isset($to) && $to != '') ? date('d,M Y',strtotime($to)) : ''; ?></strong>
          </center>
        </th> 
      </tr>
    </thead>
    <tbody>
      <?php 
          $totalLeft = 0;
          $totalRight = 0;
      ?>
      <tr>
        <td>
          <table class="table table-bordered m-0" >
            <tr>
              <td>
                <table class="table table-bordered m-0">
                  <thead>
                    <tr>
                      <th>Liabilities</th>
                      <th style="text-align:right;width:200px;"><?php echo (isset($financialYearRow['end_date']) && $financialYearRow['end_date'] != '') ? 'As At '.date('d-M-Y',strtotime($financialYearRow['end_date'])) : ''; ?></th>
                    </tr>
                    <tr>
                      <th>&nbsp;</th>
                      <th>&nbsp;</th>
                    </tr>
                  </thead>
                  <tbody>
                      <?php if(isset($data['left']) && !empty($data['left'])){ ?>
                        <?php foreach ($data['left'] as $key => $value) { ?>
                            <tr>
                              <td colspan="2">
                                <b><?php echo (isset($value['name'])) ? $value['name'] : ''; ?></b>
                                <b style="float:right;"><?php echo (isset($value['totalamount']) && $value['totalamount'] != '') ? amount_format(number_format($value['totalamount'], 2, '.', '')) : ''; ?></b>
                              </td>                                
                            </tr>
                            <?php if(isset($value['data']) && !empty($value['data'])){ ?>
                                <?php if(isset($value['data']) && !empty($value['data'])){ ?>
                                    <tr>
                                        <td>
                                            <ul class="list-group" style="list-style-type: none;margin-bottom:0px;">
                                                <?php foreach ($value['data'] as $kk => $vv) { ?>
                                        
                                                    <li class="list-group" style="margin-bottom:5px;position:relative;left:25px;">
                                                        <?php echo (isset($vv['name'])) ? $vv['name'] : ''; ?>
                                                    </li>
                                                <?php } ?>
                                            </ul>
                                        </td>
                                        
                                        <td>
                                            <ul class="list-group" style="list-style-type: none;margin-bottom:0px;">
                                                <?php foreach ($value['data'] as $k => $v) { ?>
                                        
                                                    <li class="list-group" style="text-align:right;margin-bottom:5px;margin-right:40%;<?php echo ($k == (count($value['data']) - 1)) ? 'border-bottom:1px solid;' : ''; ?>">
                                                        <?php
                                                            $amountLeft = (isset($v['closing_balance']) && $v['closing_balance'] != '') ? $v['closing_balance'] : 0;
                                                            echo amount_format(number_format($amountLeft, 2, '.', ''));
                                                            $totalLeft += $amountLeft;
                                                        ?>
                                                    </li>
                                                <?php } ?>
                                            </ul>
                                        </td>
                                    </tr>
                                <?php } ?>
                                
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>
                    <tr>
                        <td colspan="2">
                          <b>Profit &amp; Loss A/c</b>
                        </td>                                
                    </tr>
                    <tr>
                        <td>
                            <ul class="list-group" style="list-style-type: none;margin-bottom:0px;">                                                        
                                    <li class="list-group" style="margin-bottom:5px;position:relative;left:25px;">Opening Balance</li>                           
                                    <li class="list-group" style="margin-bottom:5px;position:relative;left:25px;">Current Period</li>
                                    <li class="list-group" style="margin-bottom:5px;position:relative;left:25px;">Less: Transferred</li>
                            </ul>
                        </td>
                        <td>
                           <ul class="list-group" style="list-style-type: none;margin-bottom:0px;">                                                                      
                                <li class="list-group" style="text-align:right;margin-bottom:5px;margin-right:40%;"></li>        
                                <li class="list-group" style="text-align:right;margin-bottom:5px;margin-right:40%;"><?php echo (isset($netprofit)) ? amount_format(number_format($netprofit, 2, '.', '')) : 0; ?></li>
                                <li class="list-group" style="text-align:right;margin-bottom:5px;margin-right:40%;border-bottom:1px solid;"><?php echo (isset($netprofit)) ? amount_format(number_format($netprofit, 2, '.', '')) : 0; ?></li>
                            </ul>
                        </td>
                    </tr>
                  </tbody>
                </table>
              </td>
              <td>
                <table class="table table-bordered m-0">
                  <thead>
                    <tr>
                      <th>Assets</th>
                      <th style="text-align:right;width:200px;"><?php echo (isset($financialYearRow['end_date']) && $financialYearRow['end_date'] != '') ? 'As At '.date('d-M-Y',strtotime($financialYearRow['end_date'])) : ''; ?></th>
                    </tr>
                    <tr>
                      <th>&nbsp;</th>
                      <th>&nbsp;</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if(isset($data['right']) && !empty($data['right'])){ ?>
                      <?php foreach ($data['right'] as $key => $value) { ?>
                          <tr>
                            <td colspan="2">
                              <b><?php echo (isset($value['name'])) ? $value['name'] : ''; ?></b>
                              <b class="pull-right"><?php echo (isset($value['totalamount']) && $value['totalamount'] != '') ? amount_format(number_format($value['totalamount'], 2, '.', '')) : ''; ?></b>
                            </td>                                
                          </tr>
                          <?php if(isset($value['data']) && !empty($value['data'])){ ?>
                            <tr>
                                <td>
                                    <ul class="list-group" style="list-style-type: none;margin-bottom:0px;">
                                        <?php foreach ($value['data'] as $kk1 => $vv1) { ?>
                                
                                            <li class="list-group" style="margin-bottom:5px;position:relative;left:25px;">
                                                <?php echo (isset($vv1['name'])) ? $vv1['name'] : ''; ?>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </td>
                                
                                <td>
                                    <ul class="list-group" style="list-style-type: none;margin-bottom:0px;">
                                        <?php foreach ($value['data'] as $k1 => $v1) { ?>
                                
                                            <li class="list-group" style="text-align:right;margin-bottom:5px;margin-right:40%;<?php echo ($k1 == (count($value['data']) - 1)) ? 'border-bottom:1px solid;' : ''; ?>">
                                                <?php
                                                    $amountRight = (isset($v1['closing_balance']) && $v1['closing_balance'] != '') ? $v1['closing_balance'] : 0;
                                                    echo amount_format(number_format($amountRight, 2, '.', ''));
                                                    $totalRight += $amountRight;
                                                ?>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </td>
                            </tr>
                        <?php } ?>
                          
                      <?php } ?>
                  <?php } ?>
                  </tbody>
                </table>
              </td>
            </tr>
            <tr>
              <td>
                <ul class="list-group" style="list-style-type: none;margin-bottom:0px;">
                  <li class="list-group" style="text-align:right;margin-bottom:5px;">
                    <b>
                      <?php echo 'Total : '.amount_format(number_format(($totalLeft), 2, '.', '')); ?>
                    </b>
                  </li>                   
                </ul>                                
              </td>
              <td>
                <ul class="list-group" style="list-style-type: none;margin-bottom:0px;">
                  <li class="list-group" style="text-align:right;margin-bottom:5px;">
                    <b>
                     <?php echo 'Total : '.amount_format(number_format(($totalRight), 2, '.', '')); ?>
                    </b>                      
                  </li>
                </ul>                                
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </tbody>
  </table>

<bR />
<bR />


</body>
</html>

