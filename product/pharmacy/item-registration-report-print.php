<?php include('include/usertypecheck.php');?>
<?php



  $from = (isset($_GET['from']) && $_GET['from'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_GET['from']))) : '';
  $to = (isset($_GET['to']) && $_GET['to'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_GET['to']))) : '';
  //product id
  $item = (isset($_GET['item'])) ? $_GET['item'] : '';

  // type 0 =Item Registration In Details & 1 = Item Registration Sale Only & 2 = Item Registration Purchase Only & 3 = Item Registration Batch Wise
  $type = (isset($_GET['type'])) ? $_GET['type'] : '';
  
  // item_type 0 = all & 1 = batch wise
  $item_type = (isset($_GET['item_type'])) ? $_GET['item_type'] : '';

  // item_id = product batch id for product id
  $item_id = (isset($_GET['item_id'])) ? $_GET['item_id'] : '';
  
  $pharmacy = getPharmacyDetail();

  $data = itemRegistrationReport($from, $to, $item, $type, $item_type, $item_id);
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Digibooks | Print Item Register Report</title>
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
          <span style="font-size:18px">Report Type : <b> Item Register Report </b></span>
        </td>
      </tr>
      <tr>
      <td style="border:none;padding:10px;color:#CC0000;font-size:16px;text-align:center;line-height:20px;">
          <span style="font-size:18px">Item Name : <b> <?php echo (isset($data['product']['product_name'])) ? $data['product']['product_name'] : ''; ?> </b></span>
        </td>
      </tr>
    </tbody>
  </table>

  <span style="float:right;color:#000000;font-size:12px;margin-top: -30px;  margin-right: 30px;">(All Amounts in Rs.)</span>

  <?php if(isset($type) && ($type == 0 || $type == 3)){ ?>
    <table align="center" cellpadding="0" cellspacing="0" border="1" width="100%" id="customers" style="line-height:30px;">
        <thead>
          <tr>
              <th>Sr. No</th>
              <th>Bill No.</th>
              <th>Bill Date</th>
              <th>Challan</th>
              <th style="text-align:right">MRP</th>
              <th>Batch No.</th>
              <th style="text-align:right">Purchase Qty</th>
              <th style="text-align:right">Purchase Retu Qty</th>
              <th style="text-align:right">Sale Qty</th>
              <th style="text-align:right">Sale Retu Qty</th>
              <th style="text-align:right">Closing Qty</th>
              <th>Party Name</th>
          </tr>
        </thead>
        <tbody>
          <?php $closingStock = 0; $totalPurchase = 0; $totalPurchaseReturn = 0; $totalSale = 0; $totalSaleReturn = 0; ?>
          <?php if(isset($data) && !empty($data)){ ?>
            <tr>
              <td colspan="10" style="text-align: center;">---------- Item Opening Stock ---------</td>
              <td style="text-align:right;padding-right: 10px;">
                <?php 
                  $openingstock = (isset($data['product']['total_opening_qty']) && $data['product']['total_opening_qty'] != '') ? $data['product']['total_opening_qty'] : 0;
                  echo amount_format(number_format($openingstock, 2, '.', ''));
                  $closingStock += $openingstock;
                ?>
              </td>
              <td></td>
            </tr>
            <?php if(isset($data['data']) && !empty($data['data'])){ ?>
                <?php foreach ($data['data'] as $key => $value) {?>
                  <tr>
                      <td><?php echo $key+1; ?></td>
                      <td><?php echo (isset($value['billNo'])) ? $value['billNo'] : ''; ?></td>
                      <td><?php echo (isset($value['date']) && $value['date'] != '') ? date('d/m/Y',strtotime($value['date'])) : ''; ?></td>
                      <td></td>
                      <td style="text-align:right;padding-right: 10px;">
                        <?php echo (isset($value['mrp']) && $value['mrp'] != '') ? amount_format(number_format($value['mrp'], 2, '.', '')) : ''; ?>
                      </td>
                      <td>
                        <?php echo (isset($value['batchNo'])) ? $value['batchNo'] : ''; ?>
                      </td>

                      <td style="text-align:right;padding-right: 10px;">
                        <?php 
                          if(isset($value['purchaseQty']) && $value['purchaseQty'] != ''){
                            echo amount_format(number_format($value['purchaseQty'], 2, '.', ''));
                            $totalPurchase += $value['purchaseQty'];
                            $closingStock += $value['purchaseQty'];
                          }
                        ?>  
                      </td>

                      <td style="text-align:right;padding-right: 10px;">
                        <?php 
                          if(isset($value['purchaseReturnQty']) && $value['purchaseReturnQty'] != ''){
                            echo amount_format(number_format($value['purchaseReturnQty'], 2, '.', ''));
                            $totalPurchaseReturn += $value['purchaseReturnQty'];
                            $closingStock -= $value['purchaseReturnQty'];
                          }
                        ?>  
                      </td>

                    
                      <td style="text-align:right;padding-right: 10px;">
                        <?php 
                          if(isset($value['saleQty']) && $value['saleQty'] != ''){
                            echo amount_format(number_format($value['saleQty'], 2, '.', ''));
                            $totalSale += $value['saleQty'];
                            $closingStock -= $value['saleQty'];
                          }
                        ?>
                      </td>

                      <td style="text-align:right;padding-right: 10px;">
                        <?php 
                          if(isset($value['saleReturnQty']) && $value['saleReturnQty'] != ''){
                            echo amount_format(number_format($value['saleReturnQty'], 2, '.', ''));
                            $totalSaleReturn += $value['saleReturnQty'];
                            $closingStock += $value['saleReturnQty'];
                          }
                        ?>
                      </td>
                    

                      <td style="text-align:right;padding-right: 10px;">
                        <?php
                          echo (isset($closingStock)) ? amount_format(number_format($closingStock, 2, '.', '')) : 0;
                        ?>
                      </td>

                      <td>
                        <?php echo (isset($value['partyName'])) ? $value['partyName'] : ''; ?>
                      </td>
                  </tr>
              <?php } ?>
          <?php } ?>
          <?php } ?>
        </tbody>

        <tr style="font-size:14px;font-weight:bold;">
        <td colspan="6" style="text-align:center;"><strong>Total</strong></td>
              
                          
              <td style="text-align:right;padding-right: 10px;">
                <?php echo (isset($totalPurchase)) ? amount_format(number_format($totalPurchase, 2, '.', '')) : 0; ?>
              </td>
              <td style="text-align:right;padding-right: 10px;">
                <?php echo (isset($totalPurchaseReturn)) ? amount_format(number_format($totalPurchaseReturn, 2, '.', '')) : 0; ?>
              </td>
          
              <td style="text-align:right;padding-right: 10px;">
                <?php echo (isset($totalSale)) ? amount_format(number_format($totalSale, 2, '.', '')) : 0; ?>
              </td>
              <td style="text-align:right;padding-right: 10px;">
                <?php echo (isset($totalSaleReturn)) ? amount_format(number_format($totalSaleReturn, 2, '.', '')) : 0; ?>
              </td>

              <td style="text-align:right;padding-right: 10px;">
                <?php echo (isset($closingStock)) ? amount_format(number_format($closingStock, 2, '.', '')) : 0; ?>
              </td>
              <td></td>
        </tr>

    </table>
  <?php } ?>

  <?php if(isset($type) && ($type == 1 || $type == 2)){ ?>
    <table align="center" cellpadding="0" cellspacing="0" border="1" width="100%" id="customers" style="line-height:30px;">
        <thead>
          <tr>
              <th>Sr. No</th>
              <th>Bill No.</th>
              <th>Bill Date</th>
              <th>Party Name</th>
              <th>Batch No.</th>
              <?php if(isset($type) && $type == 2){ ?>
                <th style="text-align:right">Purchase Qty</th>
                <th style="text-align:right">Purchase Retu Qty</th>
              <?php } ?>
              <?php if(isset($type) && $type == 1){ ?>
                <th style="text-align:right">Sale Qty</th>
                <th style="text-align:right">Sale Retu Qty</th>
              <?php } ?>
              <th style="text-align:right"><?php echo (isset($type) && $type == 1) ? 'Sales Value' : 'Purchase Value'; ?></th>
          </tr>
        </thead>
        <tbody>
          <?php $closingStock = 0; $totalPurchaseqty = 0; $totalPurchaseReturnqty = 0; $totalSaleqty = 0; $totalSaleReturnqty = 0; ?>
          
            <?php if(isset($data['data']) && !empty($data['data'])){ ?>
                <?php foreach ($data['data'] as $key => $value) {?>
                  <tr>
                      <td><?php echo $key+1; ?></td>
                      <td><?php echo (isset($value['billNo'])) ? $value['billNo'] : ''; ?></td>
                      <td><?php echo (isset($value['date']) && $value['date'] != '') ? date('d/m/Y',strtotime($value['date'])) : ''; ?></td>
                      <td>
                        <?php echo (isset($value['partyName'])) ? $value['partyName'] : ''; ?>
                      </td>
                      <td>
                        <?php echo (isset($value['batchNo'])) ? $value['batchNo'] : ''; ?>
                      </td>

                      <?php if(isset($type) && $type == 2){ ?>
                        <td style="text-align:right;padding-right: 10px;">
                          <?php 
                            if(isset($value['purchaseQty']) && $value['purchaseQty'] != ''){
                              echo amount_format(number_format($value['purchaseQty'], 2, '.', ''));
                              $totalPurchaseqty += $value['purchaseQty'];
                            }
                          ?>  
                        </td>

                        <td style="text-align:right;padding-right: 10px;">
                          <?php 
                            if(isset($value['purchaseReturnQty']) && $value['purchaseReturnQty'] != ''){
                              echo amount_format(number_format($value['purchaseReturnQty'], 2, '.', ''));
                              $totalPurchaseReturnqty += $value['purchaseReturnQty'];
                            }
                          ?>  
                        </td>
                      <?php } ?>

                      <?php if(isset($type) && $type == 1){ ?>
                        <td style="text-align:right;padding-right: 10px;">
                          <?php 
                            if(isset($value['saleQty']) && $value['saleQty'] != ''){
                              echo amount_format(number_format($value['saleQty'], 2, '.', ''));
                              $totalSaleqty += $value['saleQty'];
                            }
                          ?>
                        </td>

                        <td style="text-align:right;padding-right: 10px;">
                          <?php 
                            if(isset($value['saleReturnQty']) && $value['saleReturnQty'] != ''){
                              echo amount_format(number_format($value['saleReturnQty'], 2, '.', ''));
                              $totalSaleReturnqty += $value['saleReturnQty'];
                            }
                          ?>
                        </td>
                      <?php } ?>
                    

                      <td style="text-align:right;padding-right: 10px;">
                        <?php
                          $amount = (isset($value['amount']) && $value['amount'] != '') ? $value['amount'] : 0;
                          if(isset($value['type']) && ($value['type'] == 'purchase' || $value['type'] == 'sale')){
                            $closingStock += $amount;
                          }else{
                            $closingStock -= $amount;
                          }
                          echo (isset($amount)) ? amount_format(number_format($amount, 2, '.', '')) : 0;
                        ?>
                      </td>

                  </tr>
                <?php } ?>
            <?php } ?>
        </tbody>

        <tr style="font-size:14px;font-weight:bold;">
        <td colspan="5" style="text-align:center;"><strong>Total</strong></td>
              
              <?php if(isset($type) && $type == 2){ ?>
                <td style="text-align:right;padding-right: 10px;">
                  <?php echo (isset($totalPurchaseqty)) ? amount_format(number_format($totalPurchaseqty, 2, '.', '')) : 0; ?>
                </td>
                <td style="text-align:right;padding-right: 10px;">
                  <?php echo (isset($totalPurchaseReturnqty)) ? amount_format(number_format($totalPurchaseReturnqty, 2, '.', '')) : 0; ?>
                </td>
              <?php } ?>

              <?php if(isset($type) && $type == 1){ ?>
                <td style="text-align:right;padding-right: 10px;">
                  <?php echo (isset($totalSaleqty)) ? amount_format(number_format($totalSaleqty, 2, '.', '')) : 0; ?>
                </td>
                <td style="text-align:right;padding-right: 10px;">
                  <?php echo (isset($totalSaleReturnqty)) ? amount_format(number_format($totalSaleReturnqty, 2, '.', '')) : 0; ?>
                </td>
              <?php } ?>

              <td style="text-align:right;padding-right: 10px;">
                <?php echo (isset($closingStock)) ? amount_format(number_format($closingStock, 2, '.', '')) : 0; ?>
              </td>
              
        </tr>

    </table>
  <?php } ?>
</body>
</html>