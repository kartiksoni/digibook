<?php include('include/usertypecheck.php');?>
<?php


	$from = (isset($_GET['from']) && $_GET['from'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_GET['from']))) : '';
	$to = (isset($_GET['to']) && $_GET['to'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_GET['to']))) : '';
    $item = (isset($_GET['item'])) ? $_GET['item'] : '';
    $type = (isset($_GET['type'])) ? $_GET['type'] : '';
    $batch = (isset($_GET['batch']) && $type == 'batch_wise') ? $_GET['batch'] : '';
    $view = (isset($_GET['view'])) ? $_GET['view'] : '';

	$pharmacy = getPharmacyDetail();
	$searchdata = itemReport($from, $to, $item, $type, $batch, $view, 1);
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Digibooks | Print Daybook Report</title>
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
		font-size:1em !important;
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

    .inner-table td{
        border: none !important;
    }
    .text-right{
        text-align: right !important;
    }
    .text-center{
        text-align: center !important;
    }
    .pr-15{
        padding-right: 15px !important;
    }
    .pl-15{
        padding-left: 15px !important;
    }
	</style>
</head>
<body>
	<center>
		<h3 class="panel-title"><strong> <?php echo (isset($pharmacy['pharmacy_name'])) ? ucwords(strtolower($pharmacy['pharmacy_name'])) : 'Unknown pharmacy'; ?> </strong> <br>
    		<span style="font-size:16px;">GST NO : <?php echo (isset($pharmacy['gst_no'])) ? $pharmacy['gst_no'] : 'Unknown GSTNO'; ?></span>
    	</h3>
 		<h3 class="sub-title"><strong>Register for the period <?php echo (isset($from) && $from != '') ? date('d,M Y',strtotime($from)) : ''; ?> to <?php echo (isset($to) && $to != '') ? date('d,M Y',strtotime($to)) : ''; ?></strong> </h3>
	</center>
	<table align="center" cellpadding="0" cellspacing="0" border="0" width="100%" id="customers" style="line-height:20px;">
  		<tbody>
  			<tr>
    			<td style="border:none;padding:10px;color:#CC0000;font-size:16px;text-align:center;line-height:20px;">
    				<span style="font-size:18px">Item Report</span><br/>
                    <span style="font-size:18px">View : <?php echo (isset($view) && $view != '')  ? ucwords($view) : ''; ?></span>
      			</td>
  			</tr>
		</tbody>
		</table>

	<span style="float:right;color:#000000;font-size:12px;margin-top: -30px;  margin-right: 30px;">(All Amounts in Rs.)</span>

    <?php if((isset($searchdata)) && (isset($view) && $view == 'detail')){ ?>
        <table align="center" cellpadding="0" cellspacing="0" border="1" width="100%" id="customers" style="line-height:30px;">
            <?php if(!empty($searchdata)){ $i = 1; ?>
                <?php foreach ($searchdata as $key => $value) { ?>
                  <?php if((isset($value['detail']['credit']) && !empty($value['detail']['credit'])) || (isset($value['detail']['debit']) && !empty($value['detail']['debit']))){ ?>
                      <?php $credit_amount = 0; $credit_qty = 0; $debit_amount = 0; $debit_qty = 0; ?>
                      <thead>
                        <?php if($i != 1){ ?>
                          <tr style="height: 30px;">
                            <th colspan="6"></th>
                          </tr>
                        <?php } ?>
                        <tr>
                          <th colspan="3"><?php echo (isset($value['product_name'])) ? $value['product_name'] : ''; ?></th>
                          <th colspan="3">Batch No. : <?php echo (isset($value['batch_no'])) ? $value['batch_no'] : ''; ?></th>
                        </tr>
                        <tr>
                          <th colspan="6" style="text-align: center;">Item Ledger Dated <?php echo (isset($from) && $from != '') ? date('d/m/Y',strtotime($from)) : ''; ?> to <?php echo (isset($to) && $to != '') ? date('d/m/Y',strtotime($to)) : ''; ?></th>
                        </tr>
                        <tr>
                          <th colspan="3">Credit</th>
                          <th colspan="3">Debit</th>
                        </tr>
                        <tr>
                          <th width="15%" class="text-right pr-15">Amount</th>
                          <th width="10%" class="text-right pr-15">Sale Qty.</th>
                          <th width="25%" class="pl-15">Account Head/Description</th>
                          <th width="15%" class="text-right pr-15">Amount</th>
                          <th width="10%" class="text-right pr-15">Purchase Qty.</th>
                          <th width="25%" class="pl-15">Account Head/Description</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td colspan="3" style="vertical-align: top;">
                            <table width="100%" class="inner-table">
                              <tbody>
                                <?php if(isset($value['detail']['credit']) && !empty($value['detail']['credit'])){ ?>
                                  <?php foreach ($value['detail']['credit'] as $key1 => $value1) { ?>
                                    <tr>
                                      <td width="30%" class="text-right pr-15">
                                        <?php 
                                          $amount1 = (isset($value1['amount']) && $value1['amount'] != '') ? $value1['amount'] : '';
                                          $credit_amount += $amount1;
                                          echo amount_format(number_format($amount1, 2, '.', ''));
                                        ?>
                                      </td>
                                      <td width="20%" class="text-right pr-15">
                                        <?php 
                                          $qty1 = (isset($value1['qty']) && $value1['qty'] != '') ? $value1['qty'] : '';
                                          $credit_qty += $qty1;
                                          echo amount_format(number_format($qty1, 2, '.', ''));
                                        ?>
                                      </td>
                                      <td width="50%" class="pl-15"><?php echo (isset($value1['narration'])) ? $value1['narration'] : ''; ?></td>
                                    </tr>
                                  <?php } ?>
                                <?php } ?>
                              </tbody>
                            </table>
                          </td>
                          <td colspan="3" style="vertical-align: top;">
                            <table width="100%" class="inner-table">
                              <tbody>
                                <?php if(isset($value['detail']['debit']) && !empty($value['detail']['debit'])){ ?>
                                  <?php foreach ($value['detail']['debit'] as $key2 => $value2) { ?>
                                    <tr>
                                      <td width="30%" class="text-right pr-15">
                                        <?php 
                                          $amount2 = (isset($value2['amount']) && $value2['amount'] != '') ? $value2['amount'] : '';
                                          $debit_amount += $amount2;
                                          echo amount_format(number_format($amount2, 2, '.', ''));
                                        ?>
                                      </td>
                                      <td width="20%" class="text-right pr-15">
                                        <?php 
                                          $qty2 = (isset($value2['qty']) && $value2['qty'] != '') ? $value2['qty'] : '';
                                          $debit_qty += $qty2;
                                          echo amount_format(number_format($qty2, 2, '.', ''));
                                        ?>
                                      </td>
                                      <td width="50%" class="pl-15"><?php echo (isset($value2['narration'])) ? $value2['narration'] : ''; ?></td>
                                    </tr>
                                  <?php } ?>
                                <?php } ?>
                              </tbody>
                            </table>
                          </td>
                       </tr>
                      </tbody>
                      <tbody>
                        <?php 
                          $credit_amount = (isset($credit_amount) && $credit_amount != '') ? $credit_amount : 0;
                          $debit_amount = (isset($debit_amount) && $debit_amount != '') ? $debit_amount : 0;
                          $credit_qty = (isset($credit_qty) && $credit_qty != '') ? $credit_qty : 0;
                          $debit_qty = (isset($debit_qty) && $debit_qty != '') ? $debit_qty : 0;

                          $cash_on_hand_amount = ($credit_amount-$debit_amount);
                          $cash_on_hand_qty = ($credit_qty-$debit_qty);

                        ?>
                        <tr>
                            <th class="text-right pr-15">
                              <?php echo (isset($credit_amount) && $credit_amount != '') ? amount_format(number_format($credit_amount, 2, '.', '')) : 0; ?>
                            </th>
                            <th class="text-right pr-15">
                              <?php echo (isset($credit_qty) && $credit_qty != '') ? amount_format(number_format($credit_qty, 2, '.', '')) : 0; ?>
                            </th>
                            <th></th>
                            <th style="border-right: 0px;" class="text-right pr-15">
                              <?php echo (isset($debit_amount) && $debit_amount != '') ? amount_format(number_format($debit_amount, 2, '.', '')) : 0; ?>
                            </th>
                            <th class="text-right pr-15">
                              <?php echo (isset($debit_qty) && $debit_qty != '') ? amount_format(number_format($debit_qty, 2, '.', '')) : 0; ?>
                            </th>
                            <th></th>
                        </tr>
                        <tr>
                            <th class="text-right pr-15">
                              <?php 
                                  if($cash_on_hand_amount < 0){
                                      echo amount_format(number_format(abs($cash_on_hand_amount), 2, '.', ''));
                                      $credit_amount += abs($cash_on_hand_amount);
                                  }
                              ?>
                            </th>
                            <th class="text-right pr-15">
                              <?php 
                                  if($cash_on_hand_qty < 0){
                                      echo amount_format(number_format(abs($cash_on_hand_qty), 2, '.', ''));
                                      $credit_qty += abs($cash_on_hand_qty);
                                  }
                              ?>
                            </th>
                            <th>
                              <?php 
                                  if($cash_on_hand_amount < 0 || $cash_on_hand_qty < 0){
                                      echo 'Cash On Hand Db.';
                                  }
                              ?>
                            </th>

                            <th class="text-right pr-15">
                              <?php 
                                  if($cash_on_hand_amount >= 0){
                                      echo amount_format(number_format(abs($cash_on_hand_amount), 2, '.', ''));
                                      $debit_amount += abs($cash_on_hand_amount);
                                  }
                              ?>
                            </th>
                            <th class="text-right pr-15">
                              <?php 
                                  if($cash_on_hand_qty >= 0){
                                      echo amount_format(number_format(abs($cash_on_hand_qty), 2, '.', ''));
                                      $debit_qty += abs($cash_on_hand_qty);
                                  }
                              ?>
                            </th>
                            <th>
                              <?php 
                                  if($cash_on_hand_amount >= 0 || $cash_on_hand_qty >= 0){
                                      echo 'Cash On Hand Cr.';
                                  }
                              ?>
                            </th>
                        </tr>
                        <tr>
                            <th class="text-right pr-15"><?php echo amount_format(number_format(abs($credit_amount), 2, '.', '')); ?></th>
                            <th class="text-right pr-15"><?php echo amount_format(number_format(abs($credit_qty), 2, '.', '')); ?></th>
                            <th></th>
                            <th class="text-right pr-15"><?php echo amount_format(number_format(abs($debit_amount), 2, '.', '')); ?></th>
                            <th class="text-right pr-15"><?php echo amount_format(number_format(abs($debit_qty), 2, '.', '')); ?></th>
                            <th></th>
                        </tr>
                      </tbody>
                  <?php $i++; } ?>
                <?php } ?>
            <?php }else{ ?>
              <h5>No Record Found!</h5>
            <?php } ?>
        </table>
    <?php }elseif((isset($searchdata)) && (isset($view) && $view == 'summary')){ ?>
        <table align="center" cellpadding="0" cellspacing="0" border="1" width="100%" id="customers" style="line-height:30px;">
            <thead>
              <tr>
                <th rowspan="3" width="20%" class="text-center">Item Name</th>
                <th rowspan="3" width="10%" class="text-center">Batch No.</th>
                <th colspan="4" width="35%" class="text-center">Credit</th>
                <th colspan="4" width="35%" class="text-center">Debit</th>
              </tr>
              <tr>
                <th rowspan="2" class="text-right">Amount</th>
                <th rowspan="2" class="text-right">Qty</th>
                <th colspan="2" class="text-center">Cash On Hand Db.</th>
                <th rowspan="2" class="text-right">Amount</th>
                <th rowspan="2" class="text-right">Qty</th>
                <th colspan="2" class="text-center">Cash On Hand Cr.</th>
              </tr>
              <tr>
                <th class="text-right">Amount</th>
                <th class="text-right">Qty</th>
                <th class="text-right">Amount</th>
                <th class="text-right">Qty</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($searchdata)) { ?>
                <?php $c_amount = 0; $c_qty = 0; $c_c_amount = 0; $c_c_qty = 0; $d_amount = 0; $d_qty = 0; $d_c_amount = 0; $d_c_qty = 0; ?>
                <?php foreach ($searchdata as $key => $value) { ?>
                  <tr>
                    <td><?php echo (isset($value['product_name'])) ? $value['product_name'] : ''; ?></td>
                    <td><?php echo (isset($value['batch_no'])) ? $value['batch_no'] : ''; ?></td>

                    <td class="text-right">
                      <?php
                        $cr_amount =  (isset($value['credit']['amount']) && $value['credit']['amount'] != '') ? $value['credit']['amount'] : 0;
                        $c_amount += $cr_amount;
                        echo amount_format(number_format($cr_amount, 2, '.', '')); 
                      ?>
                    </td>
                    <td class="text-right">
                      <?php
                        $cr_qty = (isset($value['credit']['qty']) && $value['credit']['qty'] != '') ? $value['credit']['qty'] : 0;
                        $c_qty += $cr_qty;
                        echo amount_format(number_format($cr_qty, 2, '.', '')); 
                      ?>
                    </td>
                    <td class="text-right">
                      <?php
                        if(isset($value['credit']['cash_on_hand_amount']) && $value['credit']['cash_on_hand_amount'] != ''){
                          $c_c_amount += abs($value['credit']['cash_on_hand_amount']);
                          echo amount_format(number_format(abs($value['credit']['cash_on_hand_amount']), 2, '.', ''));
                        }
                      ?>
                    </td>
                    <td class="text-right">
                      <?php
                        if(isset($value['credit']['cash_on_hand_qty']) && $value['credit']['cash_on_hand_qty'] != ''){
                          $c_c_qty += abs($value['credit']['cash_on_hand_qty']);
                          echo amount_format(number_format(abs($value['credit']['cash_on_hand_qty']), 2, '.', ''));
                        }
                      ?>
                    </td>

                    <td class="text-right">
                      <?php
                        $db_amount = (isset($value['debit']['amount']) && $value['debit']['amount'] != '') ? $value['debit']['amount'] : 0;
                        $d_amount += $db_amount;
                        echo  amount_format(number_format($db_amount, 2, '.', '')); 
                      ?>
                    </td>
                    <td class="text-right">
                      <?php
                        $db_qty = (isset($value['debit']['qty']) && $value['debit']['qty'] != '') ? $value['debit']['qty'] : 0;
                        $d_qty += $db_qty;
                        echo amount_format(number_format($db_qty, 2, '.', '')); 
                      ?>
                    </td>
                    <td class="text-right">
                      <?php
                        if(isset($value['debit']['cash_on_hand_amount']) && $value['debit']['cash_on_hand_amount'] != ''){
                          $d_c_amount += abs($value['debit']['cash_on_hand_amount']);
                          echo amount_format(number_format(abs($value['debit']['cash_on_hand_amount']), 2, '.', ''));
                        }
                      ?>
                    </td>
                    <td class="text-right">
                      <?php
                        if(isset($value['debit']['cash_on_hand_qty']) && $value['debit']['cash_on_hand_qty'] != ''){
                          $d_c_qty += abs($value['debit']['cash_on_hand_qty']);
                          echo amount_format(number_format(abs($value['debit']['cash_on_hand_qty']), 2, '.', ''));
                        }
                      ?>
                    </td>
                  </tr>
                <?php } ?>
                <tr style="font-weight: bold;">
                  <td colspan="2" class="text-center">Total</td>

                  <td class="text-right"><?php echo (isset($c_amount) && $c_amount != '') ? amount_format(number_format($c_amount, 2, '.', '')) : 0; ?></td>
                  <td class="text-right"><?php echo (isset($c_qty) && $c_qty != '') ? amount_format(number_format($c_qty, 2, '.', '')) : 0; ?></td>
                  <td class="text-right"><?php echo (isset($c_c_amount) && $c_c_amount != '') ? amount_format(number_format($c_c_amount, 2, '.', '')) : 0; ?></td>
                  <td class="text-right"><?php echo (isset($c_c_qty) && $c_c_qty != '') ? amount_format(number_format($c_c_qty, 2, '.', '')) : 0; ?></td>

                  <td class="text-right"><?php echo (isset($d_amount) && $d_amount != '') ? amount_format(number_format($d_amount, 2, '.', '')) : 0; ?></td>
                  <td class="text-right"><?php echo (isset($d_qty) && $d_qty != '') ? amount_format(number_format($d_qty, 2, '.', '')) : 0; ?></td>
                  <td class="text-right"><?php echo (isset($d_c_amount) && $d_c_amount != '') ? amount_format(number_format($d_c_amount, 2, '.', '')) : 0; ?></td>
                  <td class="text-right"><?php echo (isset($d_c_qty) && $d_c_qty != '') ? amount_format(number_format($d_c_qty, 2, '.', '')) : 0; ?></td>
                </tr>
              <?php }else{ ?>
                <tr>
                  <td colspan="10">No Record Found!</td>
                </tr>
              <?php } ?>
            </tbody>
        </table>
    <?php } ?>

	
	<!-- <table align="center" cellpadding="0" cellspacing="0" border="1" width="100%" id="customers" style="line-height:30px;">
        <thead>
            <tr>
                <th colspan="2">Credit</th>
                <th colspan="2">Debit</th>
            </tr>
            <tr>
                <th width="15%" class="text-right pr-15">Amount</th>
                <th width="35%" class="pl-15">Account Head/Description</th>
                <th width="15%" class="text-right pr-15">Amount</th>
                <th width="35%" class="pl-15">Account Head/Description</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="2" style="vertical-align: top;">
                    <table class="inner-table" width="100%">
                        <?php if(isset($data['credit']) && !empty($data['credit'])){ ?>
                            <?php foreach($data['credit'] as $key => $value){ ?>
                                <tr>
                                    <td width="30%" class="text-right pr-15">
                                        <?php echo (isset($value['amount']) && $value['amount'] != '') ? amount_format(number_format($value['amount'], 2, '.', '')) : 0.00; ?>
                                    </td>
                                    <td width="70%" class="pl-15">
                                        <?php echo (isset($value['desc'])) ? $value['desc'] : ''; ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                    </table>
                </td>
                <td colspan="2" style="vertical-align: top;">
                    <table class="inner-table" width="100%">
                        <?php if(isset($data['debit']) && !empty($data['debit'])){ ?>
                            <?php foreach($data['debit'] as $key => $value){ ?>
                                <tr>
                                    <td width="30%" class="text-right pr-15">
                                        <?php echo (isset($value['amount']) && $value['amount'] != '') ? amount_format(number_format($value['amount'], 2, '.', '')) : 0.00; ?>
                                    </td>
                                    <td width="70%" class="pl-15">
                                        <?php echo (isset($value['desc'])) ? $value['desc'] : ''; ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                    </table>
                </td>
            </tr>
        </tbody>
        <?php 
            $totalCredit = (isset($data['credit']) && !empty($data['credit'])) ? array_sum(array_column($data['credit'], 'amount')) : 0;
            $totalDebit = (isset($data['debit']) && !empty($data['debit'])) ? array_sum(array_column($data['debit'], 'amount')) : 0;
            $cashOnHand = bcsub($totalCredit,$totalDebit);
        ?>
        <tr style="font-size:14px;font-weight:bold;">
            <td class="text-right pr-15"><?php echo (isset($totalCredit) && $totalCredit != '') ? amount_format(number_format($totalCredit, 2, '.', '')) : 0.00; ?></td>
            <td></td>
            <td style="border-right: 0px;" class="text-right pr-15"><?php echo (isset($totalDebit) && $totalDebit != '') ? amount_format(number_format($totalDebit, 2, '.', '')) : 0.00 ?></td>
            <td></td>
        </tr>
        <?php if(isset($cashOnHand) && $cashOnHand != '' && $cashOnHand != 0){ ?>
            <tr style="font-size:14px;font-weight:bold;">
                <td class="text-right pr-15">
                    <?php 
                        if($cashOnHand < 0){
                            echo amount_format(number_format(abs($cashOnHand), 2, '.', ''));
                            $totalCredit += abs($cashOnHand);
                        }
                    ?>
                </td>
                <td>
                    <?php 
                        if($cashOnHand < 0){
                            echo 'Cash On Hand Db.';
                        }
                    ?>
                </td>
                <td style="border-right: 0px;" class="text-right pr-15">
                    <?php 
                        if($cashOnHand >= 0){
                            echo amount_format(number_format($cashOnHand, 2, '.', ''));
                            $totalDebit += abs($cashOnHand);
                        }
                    ?>
                </td>
                <td>
                    <?php 
                        if($cashOnHand >= 0){
                            echo 'Cash On Hand Cr.';
                        }
                    ?>
                </td>
            </tr>
        <?php } ?>
            <tr style="font-size:14px;font-weight:bold;">
                <td class="text-right pr-15"><?php echo (isset($totalCredit) && $totalCredit != '') ? amount_format(number_format($totalCredit, 2, '.', '')) : 0.00; ?></td>
                <td></td>
                <td class="text-right pr-15"><?php echo (isset($totalDebit) && $totalDebit != '') ? amount_format(number_format($totalDebit, 2, '.', '')) : 0.00 ?></td>
                <td></td>
            </tr>
	</table> -->
</body>
</html>