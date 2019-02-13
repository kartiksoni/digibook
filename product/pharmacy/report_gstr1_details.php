<?php $title = "Customer Ledger"; ?>
<?php include('include/usertypecheck.php');?>
<?php include('include/permission.php'); ?>

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
<html lang="en">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Digibook | GSTR 3B Report</title>
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
	<!-- <link rel="stylesheet" href="vendors/iconfonts/simple-line-icon/css/simple-line-icons.css"> -->
	<!-- End plugin css for this page -->
	<!-- inject:css -->
	<link rel="stylesheet" href="css/style.css">
	<!-- endinject -->
	<link rel="shortcut icon" href="images/favicon.png" />
	<link rel="stylesheet" href="css/parsley.css">
	<style type="text/css">

	.secondary{background-color: #DDD9C5;}
	.primary{background-color: #89B3DF;}
	.danger{background-color: #EAB8B7;}
</style>
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
					<span id="errormsg"></span>

					<div class="row">
						<div class="col-md-12 grid-margin stretch-card">
							<div class="card">
								<div class="card-body">
									<h4 class="card-title">GSTR 3B Report</h4><hr class="alert-dark">

									<div class="col-lg-12">
										<div class="demo-box">
											<table class="table table-bordered m-0 pull-right" style="width:200px;">
												<tbody>
													<tr>
														<td class="primary"><b>Year</b></td>
														<td><?php echo $year;?></td>
													</tr>
													<tr>
														<td class="primary"><b>Month</b></td>
														<td><?php echo $month;?></td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-12 grid-margin stretch-card">
							<div class="card">
								<div class="card-body">
									<table class="table table-bordered">
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
								</div>

								<div class="card-body">
									<h5 class="card-title" style="color:red;">4a, 4b, 4c, 6b, 6c - B2B Invoices 
										<form name="export" action="" method="post" class="pull-right" target="_blank">
											<input type="hidden" name="month" value="<?php echo $month; ?>">
											<input type="hidden" name="year" value="<?php echo $year; ?>">
											<!--<input type="submit" value="Export To PDF" name="submit" class="btn btn-danger">-->
										</form>

										<form name="export" method="post" class="pull-right" target="_blank">
											<input type="hidden" name="month" value="<?php echo $month; ?>">
											<input type="hidden" name="year" value="<?php echo $year; ?>">
											<input type="hidden" name="type" value="b2c">
											<input type="submit" value="Export To CSV" name="exportCSVB2B" class="btn btn-success" style="margin-right:10px;">
										</form></h5>
										<table class="table table-bordered m-50">
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
									</div>

									<div class="card-body">
										<h5 class="card-title" style="color:red;">5A, 5B - B2C (Large) Invoices
											<form name="export" action="" method="post" class="pull-right" target="_blank">
												<input type="hidden" name="month" value="<?php echo $month; ?>">
												<input type="hidden" name="year" value="<?php echo $year; ?>">
												<input type="hidden" name="type" value="b2c">
												<!--<input type="submit" value="Export To PDF" name="exportPDFB2B" class="btn btn-danger">-->
											</form>

											<form name="export" method="post" class="pull-right" target="_blank">
												<input type="hidden" name="month" value="<?php echo $month; ?>">
												<input type="hidden" name="year" value="<?php echo $year; ?>">
												<input type="submit" value="Export To CSV" name="submit" class="btn btn-success" style="margin-right:10px;">
											</form>
										</h5>
										<table class="table table-bordered m-50">

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
									</div>

									<div class="card-body">
										<h5 class="card-title" style="color:red;">9B - Credit / Debit Notes (Registered) </h5>
										<table class="table table-bordered m-50">
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
									</div>

									<div class="card-body">
										<h5 class="card-title" style="color:red;">9B - Credit / Debit Notes (Unregistered)</h5>
										<table class="table table-bordered m-50">

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
									</div>

									<div class="card-body">
										<h5 class="card-title" style="color:red;">6A - Exports Invoices</h5>
										<table class="table table-bordered m-50">

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
									</div>

									<div class="card-body">
										<h5 class="card-title" style="color:red;">7 - B2CS
											<form name="export" action="" method="post" class="pull-right" target="_blank">
												<input type="hidden" name="month" value="<?php echo $month; ?>">
												<input type="hidden" name="year" value="<?php echo $year; ?>">
												<!--<input type="submit" value="Export To PDF" name="submit" class="btn btn-danger">-->
											</form>

											<form name="export" method="post" class="pull-right" target="_blank">
												<input type="hidden" name="month" value="<?php echo $month; ?>">
												<input type="hidden" name="year" value="<?php echo $year; ?>">
												<input type="hidden" name="type" value="b2c">
												<input type="submit" value="Export To CSV" name="exportCSVB2CS" class="btn btn-success" style="margin-right:10px;">
											</form>
										</h5>
										<table class="table table-bordered m-50">
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
									</div>

									<div class="card-body">
										<h5 class="card-title" style="color:red;">8 - Nil rated, exempted and non GST outward supplies
											<form name="export" action="" method="post" class="pull-right" target="_blank">
												<input type="hidden" name="month" value="<?php echo $month; ?>">
												<input type="hidden" name="year" value="<?php echo $year; ?>">
												<!--<input type="submit" value="Export To PDF" name="submit" class="btn btn-danger">-->
											</form>

											<form name="export" method="post" class="pull-right" target="_blank">
												<input type="hidden" name="month" value="<?php echo $month; ?>">
												<input type="hidden" name="year" value="<?php echo $year; ?>">
												<input type="submit" value="Export To CSV" name="exportCSVNillRated" class="btn btn-success" style="margin-right:10px;">
											</form>
										</h5>
										<table class="table table-bordered m-50">
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
									</div>


									<div class="card-body">
										<h5 class="card-title" style="color:red;">11A(1), 11A(2) - Tax Liability (Advances Received)</h5>
										<table class="table table-bordered m-50">
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
									</div>

									<div class="card-body">
										<h5 class="card-title" style="color:red;">11B(1), 11B(2) - Adjustment of Advances</h5>
										<table class="table table-bordered m-50">
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
									</div>

									<div class="card-body">
										<h5 class="card-title" style="color:red;">12 - HSN-wise summary of outward supplies
											<form name="export" action="" method="post" class="pull-right" target="_blank">
												<input type="hidden" name="month" value="<?php echo $month; ?>">
												<input type="hidden" name="year" value="<?php echo $year; ?>">
												<!--<input type="submit" value="Export To PDF" name="submit" class="btn btn-danger">-->
											</form>

											<form name="export" method="post" class="pull-right" target="_blank">
												<input type="hidden" name="month" value="<?php echo $month; ?>">
												<input type="hidden" name="year" value="<?php echo $year; ?>">
												<input type="hidden" name="type" value="b2c">
												<input type="submit" value="Export To CSV" name="exportCSVHSN" class="btn btn-success" style="margin-right:10px;">
											</form>
										</h5>
										<table class="table table-bordered m-50">
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
									</div>

									<div class="card-body">
										<h5 class="card-title" style="color:red;">13 - Documents Issued
											<form name="export" action="" method="post" class="pull-right" target="_blank">
												<input type="hidden" name="month" value="<?php echo $month; ?>">
												<input type="hidden" name="year" value="<?php echo $year; ?>">
												<!--<input type="submit" value="Export To PDF" name="submit" class="btn btn-danger">-->
											</form>

											<form name="export" method="post" class="pull-right" target="_blank">
												<input type="hidden" name="month" value="<?php echo $month; ?>">
												<input type="hidden" name="year" value="<?php echo $year; ?>">
												<input type="submit" value="Export To CSV" name="exportCSVDocs" class="btn btn-success" style="margin-right:10px;">
											</form>
										</h5>
										<table class="table table-bordered m-50">
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


										<!--Report Table End-->
										<div class="hidden-print" style="margin-top:10px;">
											<div class="pull-right">
												<a href="print_gstr1_details.php?year=<?php echo $year;?>&month=<?php echo $month;?>" target="_blank" class="btn btn-behance p-2 waves-effect waves-light"><i class="fa fa-print mr-0"></i>
												</a>
												<a href="gstr1-report.php" class="btn btn-behance">Back</a>
											</div>
										</div>

									</div>



								</div>
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

 
 <!-- toast notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>


</body>


</html>





























