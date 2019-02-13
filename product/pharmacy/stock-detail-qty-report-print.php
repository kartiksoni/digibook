<?php include('include/usertypecheck.php');?>
<?php 
  $from = (isset($_GET['from']) && $_GET['from'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_GET['from']))) : '';
  $to = (isset($_GET['to']) && $_GET['to'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_GET['to']))) : '';
  $is_batch = (isset($_GET['batch'])) ? $_GET['batch'] : 0;
  $company_code = (isset($_GET['company'])) ? $_GET['company'] : '';
  
  $pharmacy = getPharmacyDetail();
  $data = stockdetailReport($from, $to, $company_code);
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Digibooks | Print Stock Detail Qty Report</title>
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
        <span style="font-size:18px">Report Type : <b> Stock Detail Quantity </b></span>
        </td>
      </tr>
    </tbody>
  </table>

  <span style="float:right;color:#000000;font-size:12px;margin-top: -30px;  margin-right: 30px;">(All Amounts in Rs.)</span>

  
  <table align="center" cellpadding="0" cellspacing="0" border="1" width="100%" id="customers" style="line-height:30px;">
      <thead>
        <tr>
            <th>Sr. No</th>
                <th>Product Name</th>
                <?php if(isset($is_batch) && $is_batch == 1){ ?>
                  <th>Batch No</th>
                <?php } ?>
                <th>Unit</th>
                <th style="text-align:right">Opening Qty</th>
                <th style="text-align:right">Purchase Qty</th>
                <th style="text-align:right">Purchase Retu Qty</th>
                <th style="text-align:right">Sale Qty</th>
                <th style="text-align:right">Sale Retu Qty</th>
                <th style="text-align:right">Total Qty</th>
        </tr>
      </thead>
      <tbody>
        <?php 
            $totalOpeningQty = 0; $totalPurchaseQty = 0; $totalPurchaseReturnQty = 0; $totalSaleQty = 0; $totalSaleReturnQty = 0; $totalQty = 0;
          ?>
        <?php if(isset($data) && !empty($data)){ ?>
          <?php foreach ($data as $key => $value) { $total = 0;?>
                    <tr>
                      <td><?php echo $key+1; ?></td>
                      <td><?php echo (isset($value['product_name'])) ? $value['product_name'] : ''; ?></td>
                      <?php if(isset($is_batch) && $is_batch == 1){ ?>
                        <td><?php echo (isset($value['batch_no'])) ? $value['batch_no'] : '';  ?></td>
                      <?php } ?>
                      <td><?php echo (isset($value['unit'])) ? $value['unit'] : ''; ?></td>
                      <td style="text-align:right;padding-right: 10px;">
                        <?php
                          $openingQty = (isset($value['opening_qty']) && $value['opening_qty'] != '') ? $value['opening_qty'] : 0;
                          $totalOpeningQty += $openingQty;
                          $total += $openingQty;
                          echo amount_format(number_format($openingQty, 2, '.', ''));
                        ?>
                      </td>
                      <td style="text-align:right;padding-right: 10px;">
                        <?php
                          $purchaseQty = (isset($value['purchaseqty']) && $value['purchaseqty'] != '') ? $value['purchaseqty'] : 0;
                          $totalPurchaseQty += $purchaseQty;
                          $total += $purchaseQty;
                          echo amount_format(number_format($purchaseQty, 2, '.', ''));
                        ?>
                      </td>
                      <td style="text-align:right;padding-right: 10px;">
                        <?php
                          $purchaseReturnQty = (isset($value['purchasereturn']) && $value['purchasereturn'] != '') ? $value['purchasereturn'] : 0;
                          $totalPurchaseReturnQty += $purchaseReturnQty;
                          $total -= $purchaseReturnQty;
                          echo amount_format(number_format($purchaseReturnQty, 2, '.', ''));
                        ?>
                      </td>
                      <td style="text-align:right;padding-right: 10px;">
                        <?php
                          $saleQty = (isset($value['saleqty']) && $value['saleqty'] != '') ? $value['saleqty'] : 0;
                          $totalSaleQty += $saleQty;
                          $total -= $saleQty;
                          echo amount_format(number_format($saleQty, 2, '.', ''));
                        ?>
                      </td>
                      <td style="text-align:right;padding-right: 10px;">
                        <?php
                          $saleReturnQty = (isset($value['salereturn']) && $value['salereturn'] != '') ? $value['salereturn'] : 0;
                          $totalSaleReturnQty += $saleReturnQty;
                          $total += $saleReturnQty;
                          echo amount_format(number_format($saleReturnQty, 2, '.', ''));
                        ?>
                      </td>
                      <td style="text-align:right;padding-right: 10px;">
                        <?php
                          echo (isset($total)) ? amount_format(number_format($total, 2, '.', '')) : 0;
                          $totalQty += $total;
                        ?>
                      </td>
                    </tr>
                <?php } ?>
        <?php } ?>
      </tbody>

      <tr style="font-size:14px;font-weight:bold;">
      <td colspan="<?php echo (isset($is_batch) && $is_batch == 1) ? 4 : 3; ?>" class="text-center"><strong>Total</strong></td>
                             
            <td style="text-align:right;padding-right: 10px;">
              <?php echo (isset($totalOpeningQty)) ? amount_format(number_format($totalOpeningQty, 2, '.', '')) : 0; ?>
            </td>
            <td style="text-align:right;padding-right: 10px;">
              <?php echo (isset($totalPurchaseQty)) ? amount_format(number_format($totalPurchaseQty, 2, '.', '')) : 0; ?>
            </td>
            <td style="text-align:right;padding-right: 10px;">
              <?php echo (isset($totalPurchaseReturnQty)) ? amount_format(number_format($totalPurchaseReturnQty, 2, '.', '')) : 0; ?>
            </td>
            <td style="text-align:right;padding-right: 10px;">
              <?php echo (isset($totalSaleQty)) ? amount_format(number_format($totalSaleQty, 2, '.', '')) : 0; ?>
            </td>
            <td style="text-align:right;padding-right: 10px;">
              <?php echo (isset($totalSaleReturnQty)) ? amount_format(number_format($totalSaleReturnQty, 2, '.', '')) : 0; ?>
            </td>
            <td style="text-align:right;padding-right: 10px;">
              <?php echo (isset($totalQty)) ? amount_format(number_format($totalQty, 2, '.', '')) : 0; ?>
            </td>
      </tr>

  </table>
</body>
</html>