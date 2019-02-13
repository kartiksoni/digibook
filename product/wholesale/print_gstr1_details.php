<?php include('include/usertypecheck.php');?>
<?php 
$pharmacy_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : '';
$financial_id = (isset($_SESSION['auth']['financial']) && $_SESSION['auth']['financial'] != '') ? $_SESSION['auth']['financial'] : '';
if(isset($_GET['month'])  && isset($_GET['year'])){
  $year = $_GET['year'];
  $month = $_GET['month'];
}else{
  $month = $_POST['month'];
  $year = $_POST['year'];
}

$pharmacy = getPharmacyDetail();

$date = $month .' '.$year;
$first_day_this_month = date('Y-m-01' ,strtotime($date)); 
$last_day_this_month = date('Y-m-t' ,strtotime($date)); 



?>

<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Digibook | Print GSTR1 Report</title>
  <style type="text/css">

  table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
  }
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
  .customers {
    font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;
    width:99%;
    border-collapse:collapse;
  }
  .customers td, .customers th {
    font-size:1em;
    border:1px solid #046998;
    padding:5px 10px;
  }
  .customers th {
    font-size:1.1em;
    text-align:left;
    color:#000000;
    padding:3px 15px 2px 20px;
  }
  .customers tr td {
    color:#000000;
    font-size:12px !important;
  }
  .secondary{background-color: #DDD9C5;}
  .primary{background-color: #89B3DF;}
  .danger{background-color: #EAB8B7;}
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
          <span style="font-size:18px"><b> GSTR1 Report </b></span> <br>

        </td>
      </tr>
    </tbody>
  </table>

  <h3 class="sub-title"><strong>Period <?php echo (isset($first_day_this_month) && $first_day_this_month != '') ? date('d,M Y',strtotime($first_day_this_month)) : ''; ?> to <?php echo (isset($last_day_this_month) && $last_day_this_month != '') ? date('d,M Y',strtotime($last_day_this_month)) : ''; ?></strong> </h3>
</center>


<!-- <span style="float:right;color:#000000;font-size:12px;margin-top: -30px;  margin-right: 30px;">(All Amounts in Rs.)</span> -->
<table align="right" class="customers" border="1" style="width: 50px; margin-bottom: 50px;line-height:30px;">
  <thead>
   <tr>
    <th class="primary">Year</th>
    <td><?php echo $year;?></td>
  </tr>
  <tr>
    <th class="primary">Month</th>
    <td><?php echo $month;?></td>
  </tr>
</thead>
</table>

<table align="center" cellpadding="0" cellspacing="0" border="1" width="100%" class="customers" style="line-height:30px;margin-bottom: 50px;">
  <thead>
   <tr>
    <td width="5%">1</td>
    <td width="25%"><b>GSTINM</b></td>
    <td><?php echo $pharmacy['gst_no'];?></td>
  </tr>
  <tr>
    <td>2</td>
    <td width="40%"><b>(A). LEGAL NAME OF THE REGISTERED PERSON</b></td>
    <td><?php echo $pharmacy['pharmacy_name'];?> </td>
  </tr>
  <tr>
    <td>2</td>
    <td width="40%" style="color:red;"><b>(B). TRADE NAME, IF ANY</b></td>
    <td>-</td>
  </tr>
  <tr>
    <td>3</td>
    <td width="40%" style="color:red;"><b>(A). AGGREGATE TURNOVER IN THE PRECEDING FINANCIAL YEAR</b></td>
    <td>0.00</td>
  </tr>
  <tr>
    <td>4</td>
    <td width="40%" style="color:red;"><b>(B). AGGREGATE TURNOVER -APRIL TO MAY, 2017</b></td>
    <td>3,35,19,167.57</td>
  </tr>
</thead>
</table>


<h4 class="card-title" style="color:red;">4a, 4b, 4c, 6b, 6c - B2B Invoices </h4>
<table align="center" cellpadding="0" cellspacing="0" border="1" width="100%" class="customers" style="line-height:30px;margin-bottom: 50px;">
  <tr class="primary">
    <th><b>NO. OF RECORDS</b></th>
    <th><b>TOTAL INVOICE VALUE</b></th>
    <th><b>TOTAL TAXABLE VALUE</b></th>
    <th><b>TOTAL INTEGRATED TAX</b></th>
    <th><b>TOTAL CENTRAL TAX</b></th>
    <th><b>TOTAL STATE/UT TAX</b></th>
    <th><b>TOTAL CESS</b></th>
  </tr>

  <tbody>
    <tr>
      <td>103</td>
      <td>0.00</td>
      <td>0.00</td>
      <td>0.00</td>
      <td>0.00</td>
      <td>0.00</td>
      <td>0.00</td>
    </tr>

  </tbody>
</table>

<h4 class="card-title" style="color:red;">5A, 5B - B2C (Large) Invoices </h4>
<table align="center" cellpadding="0" cellspacing="0" border="1" width="100%" class="customers" style="line-height:30px;margin-bottom: 50px;">
  <tr class="primary">
                        <th><b>NO. OF RECORDS</b></th>
                        <th><b>TOTAL INVOICE VALUE</b></th>
                        <th><b>TOTAL TAXABLE VALUE</b></th>
                        <th><b>TOTAL INTEGRATED TAX</b></th>
                        <th><b>TOTAL CESS</b></th>
                      </tr>

                      <tbody>
                        <tr>
                          <td>0</td>
                          <td>0.00</td>
                          <td>0.00</td>
                          <td>0.00</td>
                          <td>0.00</td>
                        </tr>

                      </tbody>
</table>

<h4 class="card-title" style="color:red;">9B - Credit / Debit Notes (Registered)  </h4>
<table align="center" cellpadding="0" cellspacing="0" border="1" width="100%" class="customers" style="line-height:30px;margin-bottom: 50px;">
  <tr class="primary">
                        <th><b>NO. OF RECORDS</b></th>
                        <th><b>TOTAL INVOICE VALUE</b></th>
                        <th><b>TOTAL TAXABLE VALUE</b></th>
                        <th><b>TOTAL INTEGRATED TAX</b></th>
                        <th><b>TOTAL CENTRAL TAX</b></th>
                        <th><b>TOTAL STATE/UT TAX</b></th>
                        <th><b>TOTAL CESS</b></th>
                      </tr>

                      <tbody>
                        <tr>
                          <td>0</td>
                          <td>0</td>
                          <td>0</td>
                          <td>0</td>
                          <td>0</td>
                          <td>0</td>
                          <td>0</td>
                        </tr>
                         </tbody>
</table>


<h4 class="card-title" style="color:red;">9B - Credit / Debit Notes (Unregistered) </h4>
<table align="center" cellpadding="0" cellspacing="0" border="1" width="100%" class="customers" style="line-height:30px;margin-bottom: 50px;">
  <tr class="primary">
                        <th><b>NO. OF RECORDS</b></th>
                        <th><b>TOTAL INVOICE VALUE</b></th>
                        <th><b>TOTAL TAXABLE VALUE</b></th>
                        <th><b>TOTAL INTEGRATED TAX</b></th>
                        <th><b>TOTAL CESS</b></th>
                      </tr>

                      <tbody>
                        <tr>
                          <td>0</td>
                          <td>0</td>
                          <td>0</td>
                          <td>0</td>
                          <td>0</td>
                        </tr>
                         </tbody>
</table>


<h4 class="card-title" style="color:red;">6A - Exports Invoices</h4>
<table align="center" cellpadding="0" cellspacing="0" border="1" width="100%" class="customers" style="line-height:30px;margin-bottom: 50px;">
  <tr class="primary">
                        <th><b>NO. OF RECORDS</b></th>
                        <th><b>TOTAL INVOICE VALUE</b></th>
                        <th><b>TOTAL TAXABLE VALUE</b></th>
                        <th><b>TOTAL INTEGRATED TAX</b></th>

                      </tr>

                      <tbody>
                        <tr>
                          <td>0</td>
                          <td>0</td>
                          <td>0</td>
                          <td>0</td>                        
                        </tr>
                         </tbody>

</table>


<h4 class="card-title" style="color:red;">7 - B2CS </h4>
<table align="center" cellpadding="0" cellspacing="0" border="1" width="100%" class="customers" style="line-height:30px;margin-bottom: 50px;">
  <tr class="primary">
                        <th><b>NO. OF RECORDS</b></th>
                        <th><b>TOTAL INVOICE VALUE</b></th>
                        <th><b>TOTAL TAXABLE VALUE</b></th>
                        <th><b>TOTAL INTEGRATED TAX</b></th>
                        <th><b>TOTAL CENTRAL TAX</b></th>
                        <th><b>TOTAL STATE/UT TAX</b></th>
                        <th><b>TOTAL CESS</b></th>
                      </tr>

                      <tbody>
                        <tr>
                          <td>1732</td>
                          <td>1,54,40,932.40</td>
                          <td>1,44,60,406.50</td>
                          <td>0</td>
                          <td>4,90,262.95</td>
                          <td>4,90,262.95</td>
                          <td>0</td>
                        </tr>

                      </tbody>
</table>



<h4 class="card-title" style="color:red;">8 - Nil rated, exempted and non GST outward supplies </h4>
<table align="center" cellpadding="0" cellspacing="0" border="1" width="100%" class="customers" style="line-height:30px;margin-bottom: 50px;">
  <tr class="primary">
                        <th><b>NO. OF RECORDS</b></th>
                        <th><b>TOTAL NIL AMOUNT</b></th>
                        <th><b>TOTAL EXEMPTED AMOUNT</b></th>
                        <th><b>TOTAL NON-GST AMOUNT</b></th>

                      </tr>

                      <tbody>
                        <tr>
                          <td>0</td>
                          <td>0.00</td>
                          <td>0</td>
                          <td>0</td>
                        </tr>

                      </tbody>
</table>



<h4 class="card-title" style="color:red;">11A(1), 11A(2) - Tax Liability (Advances Received)</h4>
<table align="center" cellpadding="0" cellspacing="0" border="1" width="100%" class="customers" style="line-height:30px;margin-bottom: 50px;">
  <tr class="primary">
                        <th><b>NO. OF RECORDS</b></th>
                        <th><b>TOTAL INVOICE VALUE</b></th>
                        <th><b>TOTAL TAXABLE VALUE</b></th>
                        <th><b>TOTAL INTEGRATED TAX</b></th>
                        <th><b>TOTAL CENTRAL TAX</b></th>
                        <th><b>TOTAL STATE/UT TAX</b></th>
                        <th><b>TOTAL CESS</b></th>
                      </tr>

                      <tbody>
                        <tr>
                          <td>1732</td>
                          <td>1,54,40,932.40</td>
                          <td>1,44,60,406.50</td>
                          <td>0</td>
                          <td>4,90,262.95</td>
                          <td>4,90,262.95</td>
                          <td>0</td>
                        </tr>

                      </tbody>
</table>


<h4 class="card-title" style="color:red;">11B(1), 11B(2) - Adjustment of Advances</h4>
<table align="center" cellpadding="0" cellspacing="0" border="1" width="100%" class="customers" style="line-height:30px;margin-bottom: 50px;">
  <tr class="primary">
                        <th><b>NO. OF RECORDS</b></th>
                        <th><b>TOTAL INVOICE VALUE</b></th>
                        <th><b>TOTAL TAXABLE VALUE</b></th>
                        <th><b>TOTAL INTEGRATED TAX</b></th>
                        <th><b>TOTAL CENTRAL TAX</b></th>
                        <th><b>TOTAL STATE/UT TAX</b></th>
                        <th><b>TOTAL CESS</b></th>
                      </tr>

                      <tbody>
                        <tr>
                          <td>1732</td>
                          <td>1,54,40,932.40</td>
                          <td>1,44,60,406.50</td>
                          <td>0</td>
                          <td>4,90,262.95</td>
                          <td>4,90,262.95</td>
                          <td>0</td>
                        </tr>

                      </tbody>
</table>


<h4 class="card-title" style="color:red;">12 - HSN-wise summary of outward supplies</h4>
<table align="center" cellpadding="0" cellspacing="0" border="1" width="100%" class="customers" style="line-height:30px;margin-bottom: 50px;">
  <tr class="primary">
                        <th><b>HSN CODE</b></th>
                        <th><b>DESCRIPTION</b></th>
                        <th><b>UQC</b></th>
                        <th><b>TOTAL QUANTITY</b></th>
                        <th><b>TOTAL VALUE</b></th>
                        <th><b>TAXABLE VALUE</b></th>
                        <th><b>INTEGRATED TAX AMOUNT</b></th>
                        <th><b>CENTRAL TAX AMOUNT</b></th>
                        <th><b>STATE/UT TAX AMOUNT</b></th>
                        <th><b>TOTAL CESS</b></th>
                      </tr>

                      <tbody>
                        <tr>
                          <td></td>
                          <td>5.00%</td>
                          <td></td>
                          <td></td>
                          <td>1,54,40,932.40</td>
                          <td>1,44,60,406.50</td>
                          <td>0.00</td>
                          <td>4,90,262.95</td>
                          <td>4,90,262.95</td>
                          <td></td>
                        </tr>
                        <tr>
                          <td></td>
                          <td>12.00%</td>
                          <td></td>
                          <td></td>
                          <td>41,51,301.49</td>
                          <td>37,06,519.21</td>
                          <td>0.00</td>
                          <td>2,22,391.14</td>
                          <td>2,22,391.14</td>
                          <td></td>
                        </tr>
                        <tr>
                          <td></td>
                          <td>18.00%</td>
                          <td></td>
                          <td></td>
                          <td>1,04,522.48</td>
                          <td>88,578.36</td>
                          <td>0.00</td>
                          <td>7,972.06</td>
                          <td>7,972.06</td>
                          <td></td>
                        </tr>

                      </tbody>
</table>

<h4 class="card-title" style="color:red;">13 - Documents Issued</h4>
<table align="center" cellpadding="0" cellspacing="0" border="1" width="100%" class="customers" style="line-height:30px;margin-bottom: 50px;">
  <tr class="primary">
                        <th><b>NATURE OF DOCUMENT</b></th>
                        <th><b>SR. NO. FROM</b></th>
                        <th><b>SR. NO. TO</b></th>
                        <th><b>TOTAL NUMBER</b></th>
                        <th><b>CANCELLED</b></th>
                      </tr>

                      <tbody>
                        <tr>
                          <td>Debit Memo Invoice</td>
                          <td>TI-D-0164/18-19</td>
                          <td>TI-D-0289/18-19</td>
                          <td>123</td>
                          <td>0</td>
                        </tr>
                        <tr>
                          <td>Cash Memo Invoice</td>
                          <td>TI-C-1579/18-19</td>
                          <td>TI-C-3290/18-19</td>
                          <td>1712</td>
                          <td>0</td>
                        </tr>
                        <tr>
                          <td>Debit Memo Bill of Supply</td>
                          <td>BOS-D-0016/18-19</td>
                          <td>BOS-D-0041/18-19</td>
                          <td>26</td>
                          <td>0</td>
                        </tr>
                        <tr>
                          <td>Cash Memo Bill of Supply</td>
                          <td>BOS-C-0117/18-19</td>
                          <td>BOS-C-0281/18-19</td>
                          <td>165</td>
                          <td>0</td>
                        </tr>

                      </tbody>
</table>


</body>
</html>