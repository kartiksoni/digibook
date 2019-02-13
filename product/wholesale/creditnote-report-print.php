<?php include('include/usertypecheck.php');?>
<?php 
$from = (isset($_GET['from']) && $_GET['from'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_GET['from']))) : '';
$to = (isset($_GET['to']) && $_GET['to'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_GET['to']))) : '';
$view = (isset($_GET['view'])) ? $_GET['view'] : '';
$searchdata = $searchdata = creditnoteReport($from, $to, $view, 0);
$pharmacy = getPharmacyDetail();

?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Digibooks | Print Credit Note Report</title>
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
            <span style="font-size:18px"><b> Credit Note Report - <?php echo (isset($_GET['view'])) ? ucwords($_GET['view']) : ''; ?> </b></span>
          </td>
        </tr>
      </tbody>
    </table>
    <h3 class="sub-title"><strong>Period <?php echo (isset($from) && $from != '') ? date('d,M Y',strtotime($from)) : ''; ?> to <?php echo (isset($to) && $to != '') ? date('d,M Y',strtotime($to)) : ''; ?></strong> </h3>
  </center>
  

  <span style="float:right;color:#000000;font-size:12px;margin-top: -30px;  margin-right: 30px;">(All Amounts in Rs.)</span>


  <?php if((isset($searchdata)) && (isset($_GET['view']) && $_GET['view'] == 'detail')){ ?>
    <table align="center" cellpadding="0" cellspacing="0" border="1" width="100%" id="customers" style="line-height:30px;">
      <thead>
        <tr>
          <th>Sr. No</th>
          <th>Credit Note No/Date</th>
          <th>Party Name/City</th> 
          <th>Bill Amount</th>
          <th>GST%</th>
          <th>GST Rs.</th>
          <th>Amount</th>
          <th>Item Name</th>
          <th>Qty.</th>
        </tr>
      </thead>
      <tbody>
        <?php
          $total_bill = 0;
          $total_qty = 0;
          if(!empty($searchdata)){
            $i = 1;
            foreach ($searchdata as $key => $value) {
              if(isset($value['detail']) && !empty($value['detail'])){
                foreach ($value['detail'] as $k => $v) {
        ?>

                  <tr>
                    <td style="display: none;"><?php echo $i;$i++; ?></td>
                    <td><?php echo ($k == 0) ? $key+1 : ''; ?></td>
                    <td>
                      <?php
                        if($k == 0){
                          echo (isset($value['credit_note_no'])) ? $value['credit_note_no'] : '';
                          echo (isset($value['credit_note_date'])) ? ' | '.$value['credit_note_date'] : '';
                        }
                      ?>
                    </td>
                    <td>
                      <?php
                        if($k == 0){
                          echo (isset($value['party_name'])) ? $value['party_name'] : '';
                          echo (isset($value['city_name'])) ? ' | '.$value['city_name'] : '';
                        }
                      ?>
                    </td>
                    <td style="text-align:right;padding-right:10px;">
                      <?php
                        if($k == 0){
                          echo (isset($value['bill_amount']) && $value['bill_amount'] != '') ? amount_format(number_format($value['bill_amount'], 2, '.', '')) : 0;
                          $total_bill += (isset($value['bill_amount']) && $value['bill_amount'] != '') ? $value['bill_amount'] : 0;
                        }
                      ?>
                    </td>
                    <td style="text-align:right;padding-right:10px;">
                      <?php echo (isset($v['gst_name'])) ? $v['gst_name'] : ''; ?>
                    </td>
                    <td style="text-align:right;padding-right:10px;">
                      <?php echo (isset($v['gst_tax'])) ? amount_format(number_format($v['gst_tax'], 2, '.', '')) : ''; ?>
                    </td>
                    <td style="text-align:right;padding-right:10px;">
                      <?php echo (isset($v['taxable_amount']) && $v['taxable_amount'] != '') ? amount_format(number_format($v['taxable_amount'], 2, '.', '')) : 0; ?>
                    </td>
                    <td>
                      <?php echo (isset($v['item_name'])) ? $v['item_name'] : ''; ?>
                    </td>
                    <td style="text-align:right;padding-right:10px;">
                      <?php 
                        echo (isset($v['qty']) && $v['qty'] != '') ? amount_format(number_format($v['qty'], 2, '.', '')) : ''; 
                        $total_qty += (isset($v['qty']) && $v['qty'] != '') ? $v['qty'] : 0;
                      ?>
                    </td>
                  </tr>

        <?php
                }
              }
            }
          } 
        ?>
      </tbody>
      <tfoot>
        <tr style="font-size:14px;font-weight:bold;">
          <td colspan="3" style="text-align:center;">Total</td>
          <td style="text-align:right;padding-right:10px;">
            <?php
              echo (isset($total_bill) && $total_bill != '') ? amount_format(number_format($total_bill, 2, '.', '')) : 0;
            ?>
          </td>
          <td colspan="4"></td>
          <td style="text-align:right;padding-right:10px;">
            <?php
              echo (isset($total_qty) && $total_qty != '') ? amount_format(number_format($total_qty, 2, '.', '')) : 0;
            ?>
          </td>
        </tr>
      </tfoot>
    </table>
  <?php }elseif ((isset($searchdata)) && (isset($_GET['view']) && $_GET['view'] == 'summary')) { ?>
    <table align="center" cellpadding="0" cellspacing="0" border="1" width="100%" id="customers" style="line-height:30px;">
      <thead>
        <tr>
          <th>Sr. No</th>
          <th>Credit Note Date</th>
          <th>Credit Note No</th>
          <th>Party Name</th> 
          <th>GST No.</th>
          <th>City</th>
          <th>Taxable Amount</th>
          <th>tax Amount</th>
          <th>Total Amount</th>
        </tr>
      </thead>
      <tbody>
        <?php $total_taxable = 0; $total_tax = 0; $total_amount = 0; ?>
        <?php if(!empty($searchdata)){ ?>
            <?php foreach ($searchdata as $key => $value) { ?>
              <tr>
                <td><?php echo $key+1; ?></td>
                <td>
                  <?php 
                    echo (isset($value['credit_note_date']) && $value['credit_note_date'] != '' && $value['credit_note_date'] != '0000-00-00') ? date('d/m/Y',strtotime($value['credit_note_date'])) : ''; 
                  ?>
                </td>
                <td><?php echo (isset($value['credit_note_no'])) ? $value['credit_note_no'] : ''; ?></td>
                <td><?php echo (isset($value['party_name'])) ? $value['party_name'] : ''; ?></td>
                <td><?php echo (isset($value['gstno'])) ? $value['gstno'] : ''; ?></td>
                <td><?php echo (isset($value['city_name'])) ? $value['city_name'] : ''; ?></td>
                <td style="text-align:right;padding-right:10px;">
                  <?php
                    $taxable_amount = (isset($value['taxable_amount']) && $value['taxable_amount'] != '') ? $value['taxable_amount'] : 0;
                    echo amount_format(number_format($taxable_amount, 2, '.', ''));
                    $total_taxable += $taxable_amount;
                  ?>
                </td>
                <td style="text-align:right;padding-right:10px;">
                  <?php
                    $tax_amount = (isset($value['tax_amount']) && $value['tax_amount'] != '') ? $value['tax_amount'] : 0; 
                    echo amount_format(number_format($tax_amount, 2, '.', ''));
                    $total_tax += $tax_amount;
                  ?>
                </td>
                <td style="text-align:right;padding-right:10px;">
                  <?php
                    $amount =  (isset($value['total_amount']) && $value['total_amount'] != '') ? $value['total_amount'] : 0;
                    echo amount_format(number_format($amount, 2, '.', ''));
                    $total_amount += $amount;
                  ?>
                </td>
              </tr>
            <?php } ?>
        <?php } ?>
      </tbody>
      <tfoot>
        <tr style="font-size:14px;font-weight:bold;">
          <td colspan="6" style="text-align:center;">Total</td>
          <td style="text-align:right;padding-right:10px;">
            <?php
              echo (isset($total_taxable) && $total_taxable != '') ? amount_format(number_format($total_taxable, 2, '.', '')) : 0;
            ?>
          </td>
          <td style="text-align:right;padding-right:10px;">
            <?php
              echo (isset($total_tax) && $total_tax != '') ? amount_format(number_format($total_tax, 2, '.', '')) : 0;
            ?>
          </td>
          <td style="text-align:right;padding-right:10px;">
            <?php
              echo (isset($total_amount) && $total_amount != '') ? amount_format(number_format($total_amount, 2, '.', '')) : 0;
            ?>
          </td>
        </tr>
      </tfoot>
  <?php } ?>
</body>
</html>