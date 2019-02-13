<?php $title = "Tax Liability Report"; ?>
<?php include('include/usertypecheck.php'); ?>
<?php //include('include/permission.php'); ?>
<?php
  $fromdate = '';
  $todate = '';
  if(isset($_POST['search'])){
    $fromdate = (isset($_POST['from'])) ? $_POST['from'] : '';
    $todate = (isset($_POST['to'])) ? $_POST['to'] : '';
    $from = (isset($_POST['from']) && $_POST['from'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_POST['from']))) : ''; 
    $to = (isset($_POST['to']) && $_POST['to'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_POST['to']))) : '';

    $data = getTaxLiabilityReport($from, $to, 0);
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Digibook | Tax Liability Report</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="vendors/iconfonts/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="vendors/iconfonts/puse-icons-feather/feather.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.addons.css">
  <link rel="stylesheet" href="css/parsley.css">
  <!-- endinject -->

  <!-- plugin css for this page -->
  <link rel="stylesheet" href="vendors/icheck/skins/all.css">

  <!-- plugin css for this page -->
  <link rel="stylesheet" href="vendors/iconfonts/font-awesome/css/font-awesome.min.css" />
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="css/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="images/favicon.png" />
  <link rel="stylesheet" href="css/toggle/style.css">
  <style>
  .table-bordered td {
    border: 0px solid #f3f3f3;
  }
  .arrow-right {
    width: 0; 
    height: 0; 
    border-top: 5px solid transparent;
    border-bottom: 5px solid transparent;
    border-left: 5px solid black;
    float: left;
    margin: 1px 12px 0px 8px;
  }
  .arrow-down {
    width: 0; 
    height: 0; 
    border-left: 5px solid transparent;
    border-right: 5px solid transparent;
    border-top: 5px solid black;
    float: left;
    margin: 4px 9px 0px 8px;
  }
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
                  <h4 class="card-title">Tax Summary/Liability Report</h4><hr class="alert-dark">
                  <form class="forms-sample" method="POST" autocomplete="off">
                    <div class="form-group row">

                      <div class="col-12 col-md-2">
                        <label>Month</label>
                        <select class="form-control" name="month" id="month">
                          <option value="" data-start="<?php echo date('d/m/Y'); ?>" data-end="<?php echo date('d/m/Y'); ?>">Select Month</option>
                          <?php
                            $month = [1 => 'January',2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December']; 
                            foreach ($month as $key => $value) {
                          ?>
                            <?php
                              $first_day_this_month = date('01-'.sprintf("%02d", ($key)).'-Y');
                              $last_day_this_month = date("t-m-Y", strtotime($first_day_this_month));
                            ?>
                            <option data-start="<?php echo date('d/m/Y',strtotime($first_day_this_month)); ?>" data-end="<?php echo date('d/m/Y',strtotime($last_day_this_month)); ?>" value="<?php echo $key; ?>" <?php echo (isset($_POST['month']) && $_POST['month'] == $key) ? 'selected' : ''; ?> ><?php echo $value; ?></option>
                          <?php } ?>
                        </select>
                      </div>

                      <div class="col-12 col-md-2">
                        <label for="from">From</label>
                        <input type="text" class="form-control datepicker" name="from" id="from" placeholder="DD/MM/YYYY" value="<?php echo (isset($_POST['from'])) ? $_POST['from'] : date('d/m/Y'); ?>" required>
                      </div>

                      <div class="col-12 col-md-2">
                        <label for="to">To</label>
                        <input type="text" class="form-control datepicker" name="to" id="to" placeholder="DD/MM/YYYY" value="<?php echo (isset($_POST['to'])) ? $_POST['to'] : date('d/m/Y'); ?>" required>
                      </div>



                      <div class="col-12 col-md-5 col-sm-12">
                        <button type="submit" name="search" class="btn btn-success mt-30">Go</button>
                      </div>

                    </div>
                  </form>
                </div>
              </div>
            </div>
            <?php if(isset($data)){ $total_tax = 0; ?>
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Tax Summary Report</h4><hr class="alert-dark">
                  <table class="table table-bordered">
                    <tr class="primary">
                      <th width="50%"></th>
                      <th width="25%" class="text-right"><b>Taxable Amount</b></th>
                      <th width="25%" class="text-right"><b>Tax Amount</b></th>
                    </tr>
                    <tbody>
                      <tr>
                        <td colspan="3"><b>GST</b></td>
                      </tr>
                    </tbody>
                  </table>

                  <table class="table table-bordered">
                    <tr>
                      <td width="50%" class="GST_purchase"><b style=" margin-left: 10px;">Purchase <div class="arrow-down"></div></b></td>
                      <td width="25%" class="text-right">
                        <b style=" margin-left: 10px;">
                          <?php 
                            if(isset($data['gst']['purchase']) && !empty($data['gst']['purchase'])){
                              $sum_gst_purchase_taxable = array_sum(array_column($data['gst']['purchase'], 'taxable_value'));
                              echo ($sum_gst_purchase_taxable != '') ? amount_format(number_format(($sum_gst_purchase_taxable/2), 2, '.', '')) : 0;
                            }else{
                              echo '0.00';
                            }
                          ?>
                        </b>
                      </td>
                      <td width="25%" class="text-right">
                        <b style=" margin-left: 10px;">
                          <?php 
                            if(isset($data['gst']['purchase']) && !empty($data['gst']['purchase'])){
                              $sum_gst_purchase_tax = array_sum(array_column($data['gst']['purchase'], 'tax'));
                              $total_tax += $sum_gst_purchase_tax;
                              echo ($sum_gst_purchase_tax != '') ? amount_format(number_format($sum_gst_purchase_tax, 2, '.', '')) : 0;
                            }else{
                              echo '0.00';
                            }
                          ?>
                        </b>
                      </td>
                    </tr>
                  </table>

                  <div class="GST_purchase_div">
                    <table class="table table-bordered">
                      <?php if(isset($data['gst']['purchase']) && !empty($data['gst']['purchase'])){ ?>
                        <?php foreach ($data['gst']['purchase'] as $key => $value) { ?>
                            <tr>
                              <td width="50%"><span style="margin-left: 15px;"><?php echo (isset($value['desc'])) ? $value['desc'] : ''; ?></span></td> 
                              <td width="25%" class="text-right">
                                <a href="<?php echo (isset($value['url']) && $value['url'] != '') ? $value['url'].'&from='.$fromdate.'&to='.$todate : 'javascript:void(0);'; ?>" target="_blank">
                                  <?php echo (isset($value['taxable_value']) && $value['taxable_value'] != '') ? amount_format(number_format($value['taxable_value'], 2, '.', '')) : 0; ?>
                                </a>
                              </td>
                              <td width="25%" class="text-right">
                                <a href="<?php echo (isset($value['url']) && $value['url'] != '') ? $value['url'].'&from='.$fromdate.'&to='.$todate : 'javascript:void(0);'; ?>" target="_blank">
                                  <?php echo (isset($value['tax']) && $value['tax'] != '') ? amount_format(number_format($value['tax'], 2, '.', '')) : 0; ?>
                                </a>
                              </td> 
                            </tr>
                        <?php } ?>
                      <?php } ?>
                    </table>
                  </div>

                  <table class="table table-bordered">
                    <tr>
                      <td width="50%" class="GST_sale"><b style=" margin-left: 10px;">Sale <div class="arrow-down"></div></b></td>
                      <td width="25%" class="text-right">
                        <b style=" margin-left: 10px;">
                          <?php 
                            if(isset($data['gst']['sale']) && !empty($data['gst']['sale'])){
                              $sum_gst_sale_taxable = array_sum(array_column($data['gst']['sale'], 'taxable_value'));
                              echo ($sum_gst_sale_taxable != '') ? amount_format(number_format(($sum_gst_sale_taxable/2), 2, '.', '')) : 0;
                            }else{
                              echo '0.00';
                            }
                          ?>
                        </b>
                      </td>
                      <td width="25%" class="text-right">
                        <b style=" margin-left: 10px;">
                          <?php 
                            if(isset($data['gst']['sale']) && !empty($data['gst']['sale'])){
                              $sum_gst_sale_tax = array_sum(array_column($data['gst']['sale'], 'tax'));
                              $total_tax += $sum_gst_sale_tax;
                              echo ($sum_gst_sale_tax != '') ? amount_format(number_format($sum_gst_sale_tax, 2, '.', '')) : 0;
                            }else{
                              echo '0.00';
                            }
                          ?>
                        </b>
                      </td>
                    </tr>
                  </table>
                  <div class="GST_sale_div">
                    <table class="table table-bordered">
                      <?php if(isset($data['gst']['sale']) && !empty($data['gst']['sale'])){ ?>
                        <?php foreach ($data['gst']['sale'] as $key => $value) { ?>
                            <tr>
                              <td width="50%"><span style=" margin-left: 15px;"><?php echo (isset($value['desc'])) ? $value['desc'] : ''; ?></span></td> 
                              <td width="25%" class="text-right">
                                <a href="<?php echo (isset($value['url']) && $value['url'] != '') ? $value['url'].'&from='.$fromdate.'&to='.$todate : 'javascript:void(0);'; ?>" target="_blank">
                                  <?php echo (isset($value['taxable_value']) && $value['taxable_value'] != '') ? amount_format(number_format($value['taxable_value'], 2, '.', '')) : 0; ?>
                                </a>
                              </td>
                              <td width="25%" class="text-right">
                                <a href="<?php echo (isset($value['url']) && $value['url'] != '') ? $value['url'].'&from='.$fromdate.'&to='.$todate : 'javascript:void(0);'; ?>" target="_blank">
                                  <?php echo (isset($value['tax']) && $value['tax'] != '') ? amount_format(number_format($value['tax'], 2, '.', '')) : 0; ?>
                                </a>
                              </td> 
                            </tr>
                        <?php } ?>
                      <?php } ?>
                    </table>
                  </div>

                  <table class="table table-bordered">
                    <tbody>
                      <tr>
                        <td colspan="3"><b>IGST</b></td>
                      </tr>
                    </tbody>
                  </table>

                  <table class="table table-bordered">
                    <tr>
                      <td width="50%" class="IGST_purchase"><b style=" margin-left: 10px;">Purchase <div class="arrow-down"></div></b></td>
                      <td width="25%" class="text-right">
                        <b style=" margin-left: 10px;">
                          <?php 
                            if(isset($data['igst']['purchase']) && !empty($data['igst']['purchase'])){
                              $sum_igst_purchase_taxable = array_sum(array_column($data['igst']['purchase'], 'taxable_value'));
                              echo ($sum_igst_purchase_taxable != '') ? amount_format(number_format($sum_igst_purchase_taxable, 2, '.', '')) : 0;
                            }else{
                              echo '0.00';
                            }
                          ?>
                        </b>
                      </td>
                      <td width="25%" class="text-right">
                        <b style=" margin-left: 10px;">
                          <?php 
                            if(isset($data['igst']['purchase']) && !empty($data['igst']['purchase'])){
                              $sum_igst_purchase_tax = array_sum(array_column($data['igst']['purchase'], 'tax'));
                              $total_tax += $sum_igst_purchase_tax;
                              echo ($sum_igst_purchase_tax != '') ? amount_format(number_format($sum_igst_purchase_tax, 2, '.', '')) : 0;
                            }else{
                              echo '0.00';
                            }
                          ?>
                        </b>
                      </td>
                    </tr>
                  </table>

                  <div class="IGST_purchase_div">
                    <table class="table table-bordered">
                      <?php if(isset($data['igst']['purchase']) && !empty($data['igst']['purchase'])){ ?>
                        <?php foreach ($data['igst']['purchase'] as $key => $value) { ?>
                            <tr>
                              <td width="50%"><span style=" margin-left: 15px;"><?php echo (isset($value['desc'])) ? $value['desc'] : ''; ?></span></td> 
                              <td width="25%" class="text-right">
                                <a href="<?php echo (isset($value['url']) && $value['url'] != '') ? $value['url'].'&from='.$fromdate.'&to='.$todate : 'javascript:void(0);'; ?>" target="_blank">
                                  <?php echo (isset($value['taxable_value']) && $value['taxable_value'] != '') ? amount_format(number_format($value['taxable_value'], 2, '.', '')) : 0; ?>
                                </a>
                              </td>
                              <td width="25%" class="text-right">
                                <a href="<?php echo (isset($value['url']) && $value['url'] != '') ? $value['url'].'&from='.$fromdate.'&to='.$todate : 'javascript:void(0);'; ?>" target="_blank">
                                  <?php echo (isset($value['tax']) && $value['tax'] != '') ? amount_format(number_format($value['tax'], 2, '.', '')) : 0; ?>
                                </a>
                              </td> 
                            </tr>
                        <?php } ?>
                      <?php } ?>
                    </table>
                  </div>

                  <table class="table table-bordered">
                    <tr>
                      <td width="50%" class="IGST_sale"><b style=" margin-left: 10px;">Sale <div class="arrow-down"></div></b></td>
                      <td width="25%" class="text-right">
                        <b style=" margin-left: 10px;">
                          <?php 
                            if(isset($data['igst']['sale']) && !empty($data['igst']['sale'])){
                              $sum_igst_sale_taxable = array_sum(array_column($data['igst']['sale'], 'taxable_value'));
                              echo ($sum_igst_sale_taxable != '') ? amount_format(number_format($sum_igst_sale_taxable, 2, '.', '')) : 0;
                            }else{
                              echo '0.00';
                            }
                          ?>
                        </b>
                      </td>
                      <td width="25%" class="text-right">
                        <b style=" margin-left: 10px;">
                          <?php 
                            if(isset($data['igst']['sale']) && !empty($data['igst']['sale'])){
                              $sum_igst_sale_tax = array_sum(array_column($data['igst']['sale'], 'tax'));
                              $total_tax += $sum_igst_sale_tax;
                              echo ($sum_igst_sale_tax != '') ? amount_format(number_format($sum_igst_sale_tax, 2, '.', '')) : 0;
                            }else{
                              echo '0.00';
                            }
                          ?>
                        </b>
                      </td>
                    </tr>
                  </table>

                  <div class="IGST_sale_div">
                    <table class="table table-bordered">
                      <?php if(isset($data['igst']['sale']) && !empty($data['igst']['sale'])){ ?>
                        <?php foreach ($data['igst']['sale'] as $key => $value) { ?>
                            <tr>
                              <td width="50%"><span style=" margin-left: 15px;"><?php echo (isset($value['desc'])) ? $value['desc'] : ''; ?></span></td> 
                              <td width="25%" class="text-right">
                                <a href="<?php echo (isset($value['url']) && $value['url'] != '') ? $value['url'].'&from='.$fromdate.'&to='.$todate : 'javascript:void(0);'; ?>" target="_blank">
                                  <?php echo (isset($value['taxable_value']) && $value['taxable_value'] != '') ? amount_format(number_format($value['taxable_value'], 2, '.', '')) : 0; ?>
                                </a>
                              </td>
                              <td width="25%" class="text-right">
                                <a href="<?php echo (isset($value['url']) && $value['url'] != '') ? $value['url'].'&from='.$fromdate.'&to='.$todate : 'javascript:void(0);'; ?>" target="_blank">
                                  <?php echo (isset($value['tax']) && $value['tax'] != '') ? amount_format(number_format($value['tax'], 2, '.', '')) : 0; ?>
                                </a>
                              </td> 
                            </tr>
                        <?php } ?>
                      <?php } ?>
                    </table>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Tax Liability</h4><hr class="alert-dark">
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th></th>
                        <th width="35%" class="text-right">Tax Payable</th>
                        <th width="35%" class="text-right">ITC Available</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td class="text-center"><strong>Tax</strong></td>
                        <?php if(isset($total_tax)){ ?>
                          <?php if($total_tax >= 0){ ?>
                            <td class="text-right"><strong><?php echo amount_format(number_format($total_tax, 2, '.', '')); ?></strong></td>
                            <td class="text-right" style="padding-right:45px;">-</td>
                          <?php }else{ ?>
                            <td class="text-right" style="padding-right:45px;">-</td>
                            <td class="text-right"><strong><?php echo amount_format(number_format($total_tax, 2, '.', '')); ?></strong></td>
                          <?php } ?>
                        <?php } ?>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <?php } ?>
          </div>
        </div>
        <?php include "include/footer.php" ?>
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
<script src="js/select2.js"></script>


  <!-- toast notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>
<script>


  $(".GST_purchase").click(function(){
    $(".GST_purchase_div").slideToggle( "slow" );
    $("div", this).toggleClass("arrow-right arrow-down");
  });
  $(".GST_sale").click(function(){
    $(".GST_sale_div").slideToggle( "slow" );
    $("div", this).toggleClass("arrow-right arrow-down");
  });
  $(".IGST_purchase").click(function(){
    $(".IGST_purchase_div").slideToggle( "slow" );
    $("div", this).toggleClass("arrow-right arrow-down");
  });
  $(".IGST_sale").click(function(){
    $(".IGST_sale_div").slideToggle( "slow" );
    $("div", this).toggleClass("arrow-right arrow-down");
  });

  $('#month').on('change', function() {
    var startdate = $(this).find(':selected').attr('data-start');
    var enddate = $(this).find(':selected').attr('data-end');

    $('#from').attr('value', startdate);
    $('#to').attr('value', enddate);
    $(".datepicker").datepicker();
  });

</script>
<script>
  $('.datepicker').datepicker({
    enableOnReadonly: true,
    todayHighlight: true,
    format: 'dd/mm/yyyy',
    autoclose : true
  });
</script>
</body>
</html>
