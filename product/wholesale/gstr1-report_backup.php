<?php $title = "GSTR1 Report"; ?>
<?php include('include/usertypecheck.php');?>
<?php //include('include/permission.php'); ?>

<?php 
  $month = '';
  $year = '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Digibook | GSTR 1 Report</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="vendors/iconfonts/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="vendors/iconfonts/puse-icons-feather/feather.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.addons.css">
  <!-- endinject -->
  
  <!-- plugin css for this page -->
  <link rel="stylesheet" href="vendors/icheck/skins/all.css">
  
  <!-- plugin css for this page -->
  <link rel="stylesheet" href="vendors/iconfonts/font-awesome/css/font-awesome.min.css" />
  <link rel="stylesheet" href="vendors/iconfonts/simple-line-icon/css/simple-line-icons.css">
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="css/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="images/favicon.png" />
  <link rel="stylesheet" href="css/parsley.css">
</head>
<body>
  <div class="container-scroller">
    <!-- Topbar -->
    <?php include "include/topbar.php" ?>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- Right Sidebar -->
      <?php include "include/sidebar-right.php" ?>
      <!-- Left Navigation -->
      <?php include "include/sidebar-nav-left.php" ?>
      <div class="main-panel">

        <div class="content-wrapper">
          <?php include('include/flash.php'); ?>
          <span id="errormsg"></span>

          <div class="row"> 
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">GSTR 1 Report</h4><hr class="alert-dark">
                  <form class="forms-sample" action="gstr1-report-print.php" target="_blank" method="GET" autocomplete="off">
                    <div class="form-group row">
                      <div class="col-12 col-md-2">
                          <label for="from">From</label>
                          <input type="text" class="form-control datepicker" name="from" id="from" placeholder="DD/MM/YYYY" value="<?php echo (isset($_POST['from'])) ? $_POST['from'] : date('d/m/Y'); ?>" required>
                      </div>

                      <div class="col-12 col-md-2">
                          <label for="to">To</label>
                          <input type="text" class="form-control datepicker" name="to" id="to" placeholder="DD/MM/YYYY" value="<?php echo (isset($_POST['to'])) ? $_POST['to'] : date('d/m/Y'); ?>" required>
                      </div>
                      <div class="col-12 col-md-4">
                        <button type="submit" name="search" class="btn btn-success mt-30">Go</button>
                        <a href="gstr1-report-print.php?type=ALL&from=<?php echo (isset($from)) ? $from : ''; ?>&to=<?php echo (isset($to)) ? $to : ''; ?>" class="btn btn-success mt-30">Export To All</a>
                        <?php if(isset($searchdata)){ ?>
                          <a href="fixed-assets-report-print.php?id=<?php echo (isset($_POST['ladger'])) ? $_POST['ladger'] : ''; ?>&from=<?php echo (isset($_POST['from'])) ? $_POST['from'] : ''; ?>&to=<?php echo (isset($_POST['to'])) ? $_POST['to'] : ''; ?>" class="btn btn-primary mt-30" title="Print" target="_blank">Print</a>
                        <?php } ?>
                       
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <!-- <div class="card-body">
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <td width="5%">1</td>
                        <td width="25%"><b>GSTINM</b></td>
                        <td><?php echo (isset($pharmacy['gst_no'])) ? $pharmacy['gst_no'] : ''; ?></td>
                      </tr>
                      <tr>
                        <td>2</td>
                        <td width="40%"><b>(A). LEGAL NAME OF THE REGISTERED PERSON</b></td>
                        <td><?php echo (isset($pharmacy['pharmacy_name'])) ? $pharmacy['pharmacy_name'] : '';?> </td>
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
                </div> -->

                <div class="card-body">
                  <h5 class="card-title pull-left text-danger">1 - B2B(4)</h5>
                  <a href="gstr1-report-print.php?type=B2B&from=<?php echo (isset($from)) ? $from : ''; ?>&to=<?php echo (isset($to)) ? $to : ''; ?>" class="btn btn-success pull-right">Export To CSV</a>
                  <table class="table table-bordered m-50">
                    <thead>
                      <tr class="primary">
                        <th><b>NO. OF RECEIPIENT</b></th>
                        <th><b>NO OF INVOICES</b></th>
                        <th><b>TOTAL INVOICE VALUE</b></th>
                        <th><b>TOTAL TAXABLE VALUE</b></th>
                        <th><b>TOTAL CESS</b></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>12</td>
                        <td>62</td>
                        <td>1803769.00</td>
                        <td>1609046.33</td>
                        <td>0.00</td>
                      </tr>
                    </tbody>
                  </table>
                </div>

                <div class="card-body">
                  <h5 class="card-title pull-left text-danger">2 - BTBA</h5>
                  <a href="gstr1-report-print.php?type=B2BA&from=<?php echo (isset($from)) ? $from : ''; ?>&to=<?php echo (isset($to)) ? $to : ''; ?>" class="btn btn-success pull-right">Export To CSV</a>
                  <table class="table table-bordered m-50">
                    <thead>
                      <tr class="primary">
                        <th><b>NO. OF RECEIPIENTS</b></th>
                        <th><b>NO OF INVOICE</b></th>
                        <th><b>TOTAL INVOICE VALUE</b></th>
                        <th><b>TOTAL TAXABLE VALUE</b></th>
                        <th><b>TOTAL CESS</b></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>0</td>
                        <td>0</td>
                        <td>0.00</td>
                        <td>0.00</td>
                        <td>0.00</td>
                      </tr>
                    </tbody>
                  </table>
                </div>

                <div class="card-body">
                  <h5 class="card-title pull-left text-danger">3 - B2CL(5)</h5>
                  <a href="gstr1-report-print.php?type=B2CL&from=<?php echo (isset($from)) ? $from : ''; ?>&to=<?php echo (isset($to)) ? $to : ''; ?>" class="btn btn-success pull-right">Export To CSV</a>
                  <table class="table table-bordered m-50">
                    <thead>
                      <tr class="primary">
                        <th><b>NO. OF INVOICES</b></th>
                        <th><b>TOTAL INV VALUE</b></th>
                        <th><b>TOTAL TAXABLE VALUE</b></th>
                        <th><b>TOTAL CESS</b></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>0</td>
                        <td>0.00</td>
                        <td>0.00</td>
                        <td>0.00</td>
                      </tr>
                    </tbody>
                  </table>
                </div>

                <div class="card-body">
                  <h5 class="card-title pull-left text-danger">4 - B2CLA</h5>
                  <a href="gstr1-report-print.php?type=B2CLA&from=<?php echo (isset($from)) ? $from : ''; ?>&to=<?php echo (isset($to)) ? $to : ''; ?>" class="btn btn-success pull-right">Export To CSV</a>
                  <table class="table table-bordered m-50">
                    <thead>
                      <tr class="primary">
                        <th><b>NO. OF INVOICES</b></th>
                        <th><b>TOTAL INV VALUE</b></th>
                        <th><b>TOTAL TAXABLE VALUE</b></th>
                        <th><b>TOTAL CESS</b></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>0</td>
                        <td>0.00</td>
                        <td>0.00</td>
                        <td>0.00</td>
                      </tr>
                    </tbody>
                  </table>
                </div>

                <div class="card-body">
                  <h5 class="card-title pull-left text-danger">5 - B2CS(7)</h5>
                  <a href="gstr1-report-print.php?type=B2CS&from=<?php echo (isset($from)) ? $from : ''; ?>&to=<?php echo (isset($to)) ? $to : ''; ?>" class="btn btn-success pull-right">Export To CSV</a>
                  <table class="table table-bordered m-50">
                    <thead>
                      <tr class="primary">
                        <th width="50%"><b>TOTAL TAXABLE VALUE</b></th>
                        <th><b>TOTAL CESS</b></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>1982417.65</td>
                        <td>0.00</td>                      
                      </tr>
                    </tbody>
                  </table>
                </div>

                <div class="card-body">
                  <h5 class="card-title text-danger pull-left">6 - B2CSA</h5>
                  <a href="gstr1-report-print.php?type=B2CSA&from=<?php echo (isset($from)) ? $from : ''; ?>&to=<?php echo (isset($to)) ? $to : ''; ?>" class="btn btn-success pull-right">Export To CSV</a>
                  <table class="table table-bordered m-50">
                    <thead>
                      <tr class="primary">
                        <th width="50%"><b>TOTAL TAXABLE VALUE</b></th>
                        <th><b>TOTAL CESS</b></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>0.00</td>
                        <td>0.00</td>
                      </tr>
                    </tbody>
                  </table>
                </div>

                <div class="card-body">
                  <h5 class="card-title pull-left text-danger">7 - CDNR(9B)</h5>
                  <a href="gstr1-report-print.php?type=CDNR&from=<?php echo (isset($from)) ? $from : ''; ?>&to=<?php echo (isset($to)) ? $to : ''; ?>" class="btn btn-success pull-right">Export To CSV</a>
                  <table class="table table-bordered m-50">
                    <thead>
                      <tr class="primary">
                        <th><b>NO. OF RECIPIENTS</b></th>
                        <th><b>NO. OF INVOICES</b></th>
                        <th><b>NO. OF NOTES/VOUCHERS</b></th>
                        <th><b>TOTAL NOTE/REFUND VOUCHER VALUE</b></th>
                        <th><b>TOTAL TAXABLE VALUE</b></th>
                        <th><b>TOTAL CESS</b></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0.00</td>
                        <td>0.00</td>
                        <td>0.00</td>
                      </tr>
                    </tbody>
                  </table>
                </div>

                <div class="card-body">
                  <h5 class="card-title text-danger pull-left">8 - CDNRA</h5>
                  <a href="gstr1-report-print.php?type=CDNRA&from=<?php echo (isset($from)) ? $from : ''; ?>&to=<?php echo (isset($to)) ? $to : ''; ?>" class="btn btn-success pull-right">Export To CSV</a>
                  <table class="table table-bordered m-50">
                    <thead>
                      <tr class="primary">
                        <th><b>NO. OF RECIPIENTS</b></th>
                        <th><b>NO. OF NOTES/VOUCHERS</b></th>
                        <th><b>NO. OF INVOICES</b></th>
                        <th><b>TOTAL NOTE/REFUND VOUCHER VALUE</b></th>
                        <th><b>TOTAL TAXABLE VALUE</b></th>
                        <th><b>TOTAL CESS</b></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0.00</td>
                        <td>0.00</td>
                        <td>0.00</td>
                      </tr>
                    </tbody>
                  </table>
                </div>

                <div class="card-body">
                  <h5 class="card-title pull-left text-danger">9 - CDNUR(9B)</h5>
                  <a href="gstr1-report-print.php?type=CDNUR&from=<?php echo (isset($from)) ? $from : ''; ?>&to=<?php echo (isset($to)) ? $to : ''; ?>" class="btn btn-success pull-right">Export To CSV</a>
                  <table class="table table-bordered m-50">
                    <thead>
                      <tr class="primary">
                        <th><b>NO. OF NOTES/VOUCHERS</b></th>
                        <th><b>NO. OF INVOICES</b></th>
                        <th><b>TOTAL NOTE VALUE</b></th>
                        <th><b>TOTAL TAXABLE VALUE</b></th>
                        <th><b>TOTAL CESS</b></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>0</td>
                        <td>0</td>
                        <td>0.00</td>
                        <td>0.00</td>
                        <td>0.00</td>
                      </tr>
                    </tbody>
                  </table>
                </div>

                <div class="card-body">
                  <h5 class="card-title pull-left text-danger">10 - CDNURA</h5>
                  <a href="gstr1-report-print.php?type=CDNURA&from=<?php echo (isset($from)) ? $from : ''; ?>&to=<?php echo (isset($to)) ? $to : ''; ?>" class="btn btn-success pull-right">Export To CSV</a>

                  <table class="table table-bordered m-50">
                    <thead>
                      <tr class="primary">
                        <th><b>NO. OF NOTES/VOUCHERS</b></th>
                        <th><b>NO. OF INVOICES</b></th>
                        <th><b>TOTAL NOTE VALUE</b></th>
                        <th><b>TOTAL TAXABLE VALUE</b></th>
                        <th><b>TOTAL CESS</b></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>0</td>
                        <td>0</td>
                        <td>0.00</td>
                        <td>0.00</td>
                        <td>0.00</td>
                      </tr>
                    </tbody>
                  </table>
                </div>

                <div class="card-body">
                  <h5 class="card-title text-danger pull-left">11 - EXP(6)</h5>
                  <a href="gstr1-report-print.php?type=EXP&from=<?php echo (isset($from)) ? $from : ''; ?>&to=<?php echo (isset($to)) ? $to : ''; ?>" class="btn btn-success pull-right">Export To CSV</a>
                  <table class="table table-bordered m-50">
                    <thead>
                      <tr class="primary">
                        <th><b>NO. OF INVOICES</b></th>
                        <th><b>TOTAL INVOICE VALUE</b></th>
                        <th><b>NO. OF SHIPPING BILL</b></th>
                        <th><b>TOTAL TAXABLE VALUE</b></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>0</td>
                        <td>0.00</td>
                        <td>0</td>
                        <td>0.00</td>
                      </tr>
                    </tbody>
                  </table>
                </div>

                <div class="card-body">
                  <h5 class="card-title pull-left text-danger">12 - EXPA</h5>
                  <a href="gstr1-report-print.php?type=EXPA&from=<?php echo (isset($from)) ? $from : ''; ?>&to=<?php echo (isset($to)) ? $to : ''; ?>" class="btn btn-success pull-right">Export To CSV</a>
                  <table class="table table-bordered m-50">
                    <thead>
                      <tr class="primary">
                        <th><b>NO. OF INVOICES</b></th>
                        <th><b>TOTAL INVOICE VALUE</b></th>
                        <th><b>NO. OF SHIPPING BILL</b></th>
                        <th><b>TOTAL TAXABLE VALUE</b></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>0</td>
                        <td>0.00</td>
                        <td>0</td>
                        <td>0.00</td>
                      </tr>
                    </tbody>
                  </table>
                </div>

                <div class="card-body">
                  <h5 class="card-title text-danger pull-left">13 - AT - ADVANCE RECEIVED(11B)</h5>
                  <a href="gstr1-report-print.php?type=AT&from=<?php echo (isset($from)) ? $from : ''; ?>&to=<?php echo (isset($to)) ? $to : ''; ?>" class="btn btn-success pull-right">Export To CSV</a>
                  <table class="table table-bordered m-50">
                    <thead>
                      <tr class="primary">
                        <th width="50%"><b>TOTAL ADVANCE RECEIVED</b></th>
                        <th><b>TOTAL CESS</b></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>0.00</td>
                        <td>0.00</td>
                      </tr>
                    </tbody>
                  </table>
                </div>

                <div class="card-body">
                  <h5 class="card-title pull-left text-danger">14 - ATA - AMENDED TAX LIABILITY(ADVENCE RECEIVED)</h5>
                  <a href="gstr1-report-print.php?type=ATA&from=<?php echo (isset($from)) ? $from : ''; ?>&to=<?php echo (isset($to)) ? $to : ''; ?>" class="btn btn-success pull-right">Export To CSV</a>
                  <table class="table table-bordered m-50">
                    <thead>
                      <tr class="primary">
                        <th width="50%"><b>TOTAL ADVANCE RECEIVED</b></th>
                        <th><b>TOTAL CESS</b></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>0.00</td>
                        <td>0.00</td>
                      </tr>
                    </tbody>
                  </table>
                </div>

                <div class="card-body">
                  <h5 class="card-title pull-left text-danger">15 - ATADJ - AMENDED ADJUSTED(11B)</h5>
                  <a href="gstr1-report-print.php?type=ATADJ&from=<?php echo (isset($from)) ? $from : ''; ?>&to=<?php echo (isset($to)) ? $to : ''; ?>" class="btn btn-success pull-right">Export To CSV</a>
                  <table class="table table-bordered m-50">
                    <thead>
                      <tr class="primary">
                        <th width="50%"><b>TOTAL ADVANCE ADJUSTED</b></th>
                        <th><b>TOTAL CESS</b></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>0.00</td>
                        <td>0.00</td>
                      </tr>
                    </tbody>
                  </table>
                </div>

                <div class="card-body">
                  <h5 class="card-title pull-left text-danger">16 -ATADJA - AMENDEMENT OF ADJUSTEMENT ADVANCES</h5>
                  <a href="gstr1-report-print.php?type=ATADJA&from=<?php echo (isset($from)) ? $from : ''; ?>&to=<?php echo (isset($to)) ? $to : ''; ?>" class="btn btn-success pull-right">Export To CSV</a>
                  <table class="table table-bordered m-50">
                    <thead>
                      <tr class="primary">
                        <th width="50%"><b>TOTAL ADVANCE ADJUSTED</b></th>
                        <th><b>TOTAL CESS</b></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>0.00</td>
                        <td>0.00</td>
                      </tr>
                    </tbody>
                  </table>
                </div>

                <div class="card-body">
                  <h5 class="card-title pull-left text-danger">17 - EXEMP - NIL RATED, EXEMPTED AND NON GST SUPPLIES(8)</h5>
                  <a href="gstr1-report-print.php?type=EXEMP&from=<?php echo (isset($from)) ? $from : ''; ?>&to=<?php echo (isset($to)) ? $to : ''; ?>" class="btn btn-success pull-right">Export To CSV</a>
                  <table class="table table-bordered m-50">
                    <thead>
                      <tr class="primary">
                        <th><b>TOTAL NIL RATED SUPPLIES</b></th>
                        <th><b>TOTAL EXEMPTED SUPPLIES</b></th>
                        <th><b>TOTAL NON-GST SUPPLIES</b></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>0.00</td>
                        <td>0.00</td>
                        <td>0.00</td>
                      </tr>
                    </tbody>
                  </table>
                </div>

                <div class="card-body">
                  <h5 class="card-title pull-left text-danger">18 - HSN(12)</h5>
                  <a href="gstr1-report-print.php?type=HSN&from=<?php echo (isset($from)) ? $from : ''; ?>&to=<?php echo (isset($to)) ? $to : ''; ?>" class="btn btn-success pull-right">Export To CSV</a>
                  <table class="table table-bordered m-50">
                    <thead>
                      <tr class="primary">
                        <th><b>NO. OF HSN</b></th>
                        <th><b>TOTAL VALUE</b></th>
                        <th><b>TOTAL TAXABLE VALUE</b></th>
                        <th><b>TOTAL INTEGRATED TAX</b></th>
                        <th><b>TOTAL CENTRAL TAX</b></th>
                        <th><b>TOTAL STATE/UT TAX</b></th>
                        <th><b>TOTAL CESS</b></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>21</td>
                        <td>4028396.08</td>
                        <td>3591463.98</td>
                        <td>0.00</td>
                        <td>218466.05</td>
                        <td>218466.05</td>
                        <td>0.00</td>
                      </tr>
                    </tbody>
                  </table>
                </div>

                <div class="card-body">
                  <h5 class="card-title pull-left text-danger">19 - DOCS - DOCUMENTS ISSUED DURING THE TAX PERIOUD(13)</h5>
                  <a href="gstr1-report-print.php?type=DOCS&from=<?php echo (isset($from)) ? $from : ''; ?>&to=<?php echo (isset($to)) ? $to : ''; ?>" class="btn btn-success pull-right">Export To CSV</a>
                  <table class="table table-bordered m-50">
                    <thead>
                      <tr class="primary">
                        <th><b>TOTAL NUMBER</b></th>
                        <th><b>TOTAL CANCELLED</b></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>349</td>
                        <td>0</td>
                      </tr>
                    </tbody>
                  </table>
                </div>

                <div class="card-body">
                  <h5 class="card-title text-danger pull-left">20 -MASTER</h5>
                  <a href="gstr1-report-print.php?type=MASTER&from=<?php echo (isset($from)) ? $from : ''; ?>&to=<?php echo (isset($to)) ? $to : ''; ?>" class="btn btn-success pull-right">Export To CSV</a>
                  <table class="table table-bordered m-50">
                    <thead>
                      <tr class="primary">
                        <th style="font-size: 12px;"><b>UQC</b></th>
                        <th style="font-size: 12px;"><b>EXPORT TYPR</b></th>
                        <th style="font-size: 12px;"><b>REVERSE CHARGE/PROVISIONAL ASSESSMENT</b></th>
                        <th style="font-size: 12px;"><b>NOTE TYPE</b></th>
                        <th style="font-size: 12px;"><b>TYPE</b></th>
                        <th style="font-size: 12px;"><b>TAX RATE</b></th>
                        <th style="font-size: 12px;"><b>POS</b></th>
                        <th style="font-size: 12px;"><b>INVOICE TYPE</b></th>
                        <th style="font-size: 12px;"><b>REASON FOR ISSUING NOTE</b></th>
                        <th style="font-size: 12px;"><b>NATURE OF DUCEMENT</b></th>
                        <th style="font-size: 12px;"><b>UR TYPE</b></th>
                        <th style="font-size: 12px;"><b>SUPPLY TYPE</b></th>
                        <th style="font-size: 12px;"><b>MONTH</b></th>
                        <th style="font-size: 12px;"><b>FINANCIAL YEAR</b></th>
                        <th style="font-size: 12px;"><b>DIFFERENTIAL PERECENTAGE</b></th>
                      </tr>
                    </thead>
                  </table>
                </div>
              </div>
            </div>
          </div>

        </div>
        <!-- content-wrapper ends -->

        <!-- partial:partials/_footer.php -->
        <?php include "include/footer.php" ?>
        <!-- partial -->

        </div>
        <!-- main-panel ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="vendors/js/vendor.bundle.base.js"></script>
    <script src="vendors/js/vendor.bundle.addons.js"></script>
    <!-- endinject -->
    <!-- inject:js -->
    <script src="js/off-canvas.js"></script>
    <script src="js/hoverable-collapse.js"></script>
    <script src="js/misc.js"></script>
    <script src="js/settings.js"></script>
    <script src="js/todolist.js"></script>
    <!-- endinject -->
    <!-- Custom js for this page-->
    <script src="js/file-upload.js"></script>
    <script src="js/iCheck.js"></script>
    <script src="js/typeahead.js"></script>
    <script src="js/select2.js"></script>

    <!-- Custom js for this page-->
    <script src="js/formpickers.js"></script>
    <script src="js/form-addons.js"></script>
    <script src="js/x-editable.js"></script>
    <script src="js/dropify.js"></script>
    <script src="js/dropzone.js"></script>
    <script src="js/jquery-file-upload.js"></script>
    <script src="js/formpickers.js"></script>
    <script src="js/form-repeater.js"></script>

    <!-- Custom js for this page Modal Box-->
    <script src="js/modal-demo.js"></script>


    <!-- Datepicker Initialise-->
    <script>
      $('.datepicker').datepicker({
        enableOnReadonly: true,
        todayHighlight: true,
        format: 'dd/mm/yyyy',
        autoclose : true
      });
    </script>

    <!-- Custom js for this page Datatables-->
    <script src="js/data-table.js"></script> 
    <script>
     $('.datatable').DataTable( {
      fixedHeader: {
        header: true,
        footer: true
      }
    } );
  </script>
  <!-- script for custom validation -->
  <script src="js/parsley.min.js"></script>
  <script type="text/javascript">
    $('form').parsley();
  </script>
  <script src="js/custom/onlynumber.js"></script>
  <!-- <script src="js/custom/customer_ledger.js"></script> -->
  <!-- End custom js for this page-->


</body>


</html>
