<?php include('include/usertypecheck.php');?>
<?php
    $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
    $financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';
    $current_state = (isset($_SESSION['state_code'])) ? $_SESSION['state_code'] : '';
    $type = (isset($_GET['type'])) ? $_GET['type'] : '';
    $gst = (isset($_GET['gst'])) ? $_GET['gst'] : '';
    $rate = (isset($_GET['rate'])) ? $_GET['rate'] : '';
    $from = (isset($_GET['from']) && $_GET['from'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_GET['from']))) : '';
    $to = (isset($_GET['to']) && $_GET['to'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_GET['to']))) : '';

    if($type != '' && $gst != '' && $rate != '' && $from != '' && $to != ''){
        $data = [];
        if($type == 'sale'){
            $gstfield = ($gst == 'cgst') ? "cgst" : (($gst == 'sgst') ? "sgst" : "igst");

            $query = "SELECT tb.id as tb_id, tb.invoice_date, tb.invoice_no, tb.is_general_sale, tb.couriercharge as freight_per, tb.couriercharge_val as freight_amount, tbd.id as tbd_id, tbd.".$gstfield." as tax_rate, SUM(tbd.qty*tbd.rate) as taxable_amount, SUM(((tbd.qty+tbd.freeqty)*(tbd.rate))*tbd.".$gstfield."/100) as tax_amount, lg.name as customer_name FROM tax_billing tb INNER JOIN tax_billing_details tbd ON tb.id = tbd.tax_bill_id LEFT JOIN ledger_master lg ON tb.customer_id = lg.id WHERE tbd.".$gstfield." IS NOT NULL AND tbd.".$gstfield." != 0 AND tbd.".$gstfield." = '".$rate."' AND tb.invoice_date >= '".$from."' AND tb.invoice_date <= '".$to."' AND tb.pharmacy_id = '".$pharmacy_id."' GROUP BY tbd.tax_bill_id";
            $res = mysqli_query($conn, $query);
            if($res && mysqli_num_rows($res) > 0){
                while ($row = mysqli_fetch_assoc($res)){
                    $tmp['tb_id'] = (isset($row['tb_id'])) ? $row['tb_id'] : '';
                    $tmp['tbd_id'] = (isset($row['tbd_id'])) ? $row['tbd_id'] : '';
                    $tmp['transaction_type'] = 'Invoice';
                    $tmp['date'] = (isset($row['invoice_date'])) ? $row['invoice_date'] : '';
                    $tmp['no'] = (isset($row['invoice_no'])) ? $row['invoice_no'] : '';
                    $tmp['name'] = (isset($row['customer_name'])) ? $row['customer_name'] : '';
                    $tmp['tax_name'] = "Sales ".strtoupper($gst)." ".$row['tax_rate']."%";
                    $tmp['tax_rate'] = $row['tax_rate'];
                    
                    $freight_tax = 0;
                    if($row['freight_amount'] != '' && $row['freight_amount'] != 0 && $row['tax_rate'] == $row['freight_per']){
                        $freight_tax = ($row['freight_amount']*$row['freight_per']/100);
                        if($gst != 'igst'){
                            $freight_tax = ($freight_tax/2);
                        }
                    }
                    
                    $tax_amount = (isset($row['tax_amount']) && $row['tax_amount'] != '') ? $row['tax_amount'] : 0;
                    $tmp['tax_amount'] = ($tax_amount+$freight_tax);
                    $tmp['taxable_amount'] = (isset($row['taxable_amount']) && $row['taxable_amount'] != '') ? $row['taxable_amount'] : 0;
                    $tmp['type'] = 'sale';
                    $data[] = $tmp;
                    unset($tmp, $freight_tax, $tax_amount);
                }
            }
            // sale return
            $queryReturn = "SELECT sr.id as sr_id, sr.credit_note_no, sr.credit_note_date, srd.id as srd_id, srd.".$gstfield." as tax_rate, SUM(srd.qty*srd.rate) as taxable_amount, SUM(((srd.qty+srd.free_qty)*(srd.rate))*srd.".$gstfield."/100) as tax_amount, lg.name as customer_name FROM sale_return sr INNER JOIN sale_return_details srd ON sr.id = srd.sale_return_id LEFT JOIN ledger_master lg ON sr.customer_id = lg.id WHERE srd.".$gstfield." IS NOT NULL AND srd.".$gstfield." != 0 AND srd.".$gstfield." = '".$rate."' AND sr.credit_note_date >= '".$from."' AND sr.credit_note_date <= '".$to."' AND sr.pharmacy_id = '".$pharmacy_id."' GROUP BY srd.sale_return_id";

            $resReturn = mysqli_query($conn, $queryReturn);
            if($resReturn && mysqli_num_rows($resReturn) > 0){
                while ($returnrow = mysqli_fetch_assoc($resReturn)) {
                    $tmp1['sr_id'] = (isset($returnrow['sr_id'])) ? $returnrow['sr_id'] : '';
                    $tmp1['srd_id'] = (isset($returnrow['srd_id'])) ? $returnrow['srd_id'] : '';
                    $tmp1['transaction_type'] = 'Credit Note';
                    $tmp1['date'] = (isset($returnrow['credit_note_date'])) ? $returnrow['credit_note_date'] : '';
                    $tmp1['no'] = (isset($returnrow['credit_note_no'])) ? $returnrow['credit_note_no'] : '';
                    $tmp1['name'] = (isset($returnrow['customer_name'])) ? $returnrow['customer_name'] : '';
                    $tmp1['tax_name'] = "Sales Return ".strtoupper($gst)." ".$returnrow['tax_rate']."%";
                    $tmp1['tax_rate'] = $returnrow['tax_rate'];
                    $tmp1['tax_amount'] = (isset($returnrow['tax_amount']) && $returnrow['tax_amount'] != '') ? -$returnrow['tax_amount'] : 0;
                    $tmp1['taxable_amount'] = (isset($returnrow['taxable_amount']) && $returnrow['taxable_amount'] != '') ? -$returnrow['taxable_amount'] : 0;
                    $tmp1['type'] = 'sale_return';
                    $data[] = $tmp1;
                    unset($tmp1);
                }
            }
        }elseif ($type == 'purchase'){
            $gstfield = ($gst == 'cgst') ? "f_cgst" : (($gst == 'sgst') ? "f_sgst" : "f_igst");
            $gstRfield = ($gst == 'cgst') ? "cgst" : (($gst == 'sgst') ? "sgst" : "igst");

            $query = "SELECT p.id as p_id, p.invoice_date, p.invoice_no, p.courier as freight_per, p.total_courier as freight_amount, pd.id as pd_id, pd.".$gstfield." as tax_rate, SUM(pd.qty*pd.f_rate) as taxable_amount, SUM(((pd.qty+pd.free_qty)*(pd.f_rate))*pd.".$gstfield."/100) as tax_amount, lg.name as vendor_name FROM purchase p INNER JOIN purchase_details pd ON p.id = pd.purchase_id INNER JOIN ledger_master lg ON p.vendor = lg.id INNER JOIN own_states st ON lg.state = st.id WHERE pd.".$gstfield." = '".$rate."' AND p.invoice_date >= '".$from."' AND p.invoice_date <= '".$to."' AND p.pharmacy_id = '".$pharmacy_id."'";
            if($gst == 'igst'){
                $query .= " AND st.state_code_gst != '".$current_state."'";
            }else{
                $query .= " AND st.state_code_gst = '".$current_state."'";
            }
            $query .= " GROUP BY pd.purchase_id";
            $res = mysqli_query($conn, $query);
            if($res && mysqli_num_rows($res) > 0){
                while ($row = mysqli_fetch_assoc($res)) {
                    $tmp['p_id'] = (isset($row['p_id'])) ? $row['p_id'] : '';
                    $tmp['pd_id'] = (isset($row['pd_id'])) ? $row['pd_id'] : '';
                    $tmp['transaction_type'] = 'Invoice';
                    $tmp['date'] = (isset($row['invoice_date'])) ? $row['invoice_date'] : '';
                    $tmp['no'] = (isset($row['invoice_no'])) ? $row['invoice_no'] : '';
                    $tmp['name'] = (isset($row['vendor_name'])) ? $row['vendor_name'] : '';
                    $tmp['tax_name'] = "Purchase ".strtoupper($gst)." ".$row['tax_rate']."%";
                    $tmp['tax_rate'] = $row['tax_rate'];
                    
                    $freight_tax = 0;
                    if($row['freight_amount'] != '' && $row['freight_amount'] != 0 && $row['tax_rate'] == $row['freight_per']){
                        $freight_tax = ($row['freight_amount']*$row['freight_per']/100);
                        if($gst != 'igst'){
                            $freight_tax = ($freight_tax/2);
                        }
                    }
                    
                    $tax_amount = (isset($row['tax_amount']) && $row['tax_amount'] != '') ? $row['tax_amount'] : 0;
                    $tmp['tax_amount'] = -($tax_amount+$freight_tax);
                    $tmp['taxable_amount'] = (isset($row['taxable_amount']) && $row['taxable_amount'] != '') ? -$row['taxable_amount'] : 0;
                    $tmp['type'] = 'purchase';
                    $data[] = $tmp;
                    unset($tmp, $freight_tax, $tax_amount);
                }
            }
            $queryReturn = "SELECT pr.id as pr_id, pr.debit_note_date, pr.debit_note_no, prd.id as prd_id, prd.".$gstRfield." as tax_rate, SUM(prd.qty*prd.final_rate) as taxable_amount, SUM(((prd.qty+prd.free_qty)*(prd.final_rate))*prd.".$gstRfield."/100) as tax_amount, lg.name as vendor_name FROM purchase_return pr INNER JOIN purchase_return_detail prd ON pr.id = prd.pr_id LEFT JOIN ledger_master lg ON pr.vendor_id = lg.id INNER JOIN own_states st ON lg.state = st.id WHERE prd.".$gstRfield." = '".$rate."' AND pr.debit_note_date >= '".$from."' AND pr.debit_note_date <= '".$to."' AND pr.pharmacy_id = '".$pharmacy_id."'";
            if($gst == 'igst'){
                $queryReturn .= " AND st.state_code_gst != '".$current_state."'";
            }else{
                $queryReturn .= " AND st.state_code_gst = '".$current_state."'";
            }
            $queryReturn .= " GROUP BY prd.pr_id";

            $resReturn = mysqli_query($conn, $queryReturn);
            if($resReturn && mysqli_num_rows($resReturn) > 0){
                $returnrow = mysqli_fetch_assoc($resReturn);
                $tmp1['pr_id'] = (isset($returnrow['pr_id'])) ? $returnrow['pr_id'] : '';
                $tmp1['prd_id'] = (isset($returnrow['prd_id'])) ? $returnrow['prd_id'] : '';
                $tmp1['transaction_type'] = 'Debit Note';
                $tmp1['date'] = (isset($returnrow['debit_note_date'])) ? $returnrow['debit_note_date'] : '';
                $tmp1['no'] = (isset($returnrow['debit_note_no'])) ? $returnrow['debit_note_no'] : '';
                $tmp1['name'] = (isset($returnrow['vendor_name'])) ? $returnrow['vendor_name'] : '';
                $tmp1['tax_name'] = "Purchase Return ".strtoupper($gst)." ".$returnrow['tax_rate']."%";
                $tmp1['tax_rate'] = $returnrow['tax_rate'];
                $tmp1['tax_amount'] = (isset($returnrow['tax_amount']) && $returnrow['tax_amount'] != '') ? $returnrow['tax_amount'] : 0;
                $tmp1['taxable_amount'] = (isset($returnrow['taxable_amount']) && $returnrow['taxable_amount'] != '') ? $returnrow['taxable_amount'] : 0;
                $tmp1['type'] = 'purchase_return';
                $data[] = $tmp1;
                unset($tmp1);
            }
        }
        
        if(!empty($data)){
            function date_compare($a, $b){

                $t1 = strtotime($a['date']);

                $t2 = strtotime($b['date']);

                return $t1 - $t2;
            }    

            usort($data, 'date_compare');
        }
    }
    $pharmacy = getPharmacyDetail();
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Digibooks | Print Tax Liability Report</title>
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
            <span style="font-size:18px">Report Type : <b> Tax Liability Report </b></span>
        </td>
      </tr>
    </tbody>
  </table>

  <span style="float:right;color:#000000;font-size:12px;margin-top: -30px;  margin-right: 30px;">(All Amounts in Rs.)</span>

  
    <table align="center" cellpadding="0" cellspacing="0" border="1" width="100%" id="customers" style="line-height:30px;">
        <thead>
            <tr>
                <th>Date</th>
                <th>Transaction Type</th>
                <th>No</th>
                <th>Name</th>
                <th>Tax Name</th>
                <th style="text-align:right;">Tax Rate</th>
                <th style="text-align:right;">Tax Amount</th>
                <th style="text-align:right;">Taxable Amount</th>
            </tr>
        </thead>
        <tbody>
        <?php $total_tax_amount = 0; $total_taxable_amount = 0; ?>
        <?php if(isset($data) && !empty($data)){ ?>
            <?php foreach($data as $key => $value){ ?>
                <tr>
                    <td>
                        <?php echo (isset($value['date']) && $value['date'] != '' && $value['date'] != '0000-00-00') ? date('d/m/Y',strtotime($value['date'])) : ''; ?>
                    </td>
                    <td>
                        <?php echo (isset($value['transaction_type'])) ? $value['transaction_type'] : ''; ?>
                    </td>
                    <td>
                        <?php echo (isset($value['no'])) ? $value['no'] : ''; ?>
                    </td>
                    <td>
                        <?php echo (isset($value['name'])) ? $value['name'] : ''; ?>
                    </td>
                    <td>
                        <?php echo (isset($value['tax_name'])) ? $value['tax_name'] : ''; ?>
                    </td>
                    <td style="text-align: right;margin-right: 10px;">
                        <?php echo (isset($value['tax_rate']) && $value['tax_rate'] != '') ? amount_format(number_format($value['tax_rate'], 2, '.', '')).' %' : ''; ?>
                    </td>
                    <td style="text-align: right;margin-right: 10px;">
                        <?php 
                            $taxAmount = (isset($value['tax_amount']) && $value['tax_amount'] != '') ? $value['tax_amount'] : 0;
                            echo amount_format(number_format($taxAmount, 2, '.', ''));
                            $total_tax_amount += $taxAmount;
                        ?>
                    </td>
                    <td style="text-align: right;margin-right: 10px;">
                        <?php 
                            $taxableAmount = (isset($value['taxable_amount']) && $value['taxable_amount'] != '') ? $value['taxable_amount'] : 0;
                            echo amount_format(number_format($taxableAmount, 2, '.', ''));
                            $total_taxable_amount += $taxableAmount;
                        ?>
                    </td>
            <?php } ?>
        <?php } ?>
        <tr style="background-color: #EFEFEF;">
            <td style="text-align:center;" colspan="6"><strong class="font-13">Total</strong></td>
            <td style="text-align:right;margin-right: 10px;">
                <strong class="font-13">
                    <?php
                        if(isset($total_tax_amount)){
                            echo amount_format(number_format($total_tax_amount, 2, '.', ''));     
                        }
                    ?>
                </strong>
            </td>
            <td style="text-align:right;margin-right: 10px;">
                <strong class="font-13">
                    <?php
                        if(isset($total_taxable_amount)){
                            echo amount_format(number_format($total_taxable_amount, 2, '.', ''));     
                        }
                    ?>
                </strong>
            </td>
        </tr>
        </tbody>
    </table>
</body>
</html>