<?php $title = "Creditnote Report"; ?>
<?php include('include/usertypecheck.php');?>
<?php //include('include/permission.php'); ?>
<?php 
  $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : '';

  if(isset($_POST['search'])){
    $from = (isset($_POST['from']) && $_POST['from'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_POST['from']))) : ''; 
    $to = (isset($_POST['to']) && $_POST['to'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_POST['to']))) : '';
    $view = (isset($_POST['view'])) ? $_POST['view'] : '';
    $searchdata = creditnoteReport($from, $to, $view, 0);
  }
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Digibooks | Credit Note Report</title>
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
          <span id="errormsg"></span>

          <div class="row">

            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Credit Note Report</h4><hr class="alert-dark">
                  <form class="forms-sample" method="POST" autocomplete="off">
                    <div class="form-group row">

                      <div class="col-12 col-md-2">
                        <label for="from">From</label>
                        <input type="text" class="form-control datepicker" name="from" id="from" placeholder="DD/MM/YYYY" value="<?php echo (isset($_POST['from'])) ? $_POST['from'] : date('d/m/Y'); ?>" required>
                      </div>

                      <div class="col-12 col-md-2">
                        <label for="to">To</label>
                        <input type="text" class="form-control datepicker" name="to" id="to" placeholder="DD/MM/YYYY" value="<?php echo (isset($_POST['to'])) ? $_POST['to'] : date('d/m/Y'); ?>" required>
                      </div>

                      <div class="col-12 col-md-2">
                        <?php 
                          $detail = 'checked';
                          $summary = '';
                          if(isset($_POST['view']) && $_POST['view'] == 'detail'){
                            $detail = 'checked';
                          }elseif(isset($_POST['view']) && $_POST['view'] == 'summary'){
                            $summary = 'checked';
                          }
                        ?>
                        <label for="view">Select Any One</label>
                        <div class="row no-gutters">
                          <div class="col">
                              <div class="form-radio">
                                <label class="form-check-label">
                                  <input type="radio" class="form-check-input bill_type" name="view" value="detail" data-parsley-multiple="view" <?php echo (isset($detail)) ? $detail : ''; ?> >
                                   Detail
                                <i class="input-helper"></i></label>
                              </div>
                          </div>
                          
                          <div class="col">
                              <div class="form-radio">
                                <label class="form-check-label">
                                  <input type="radio" class="form-check-input bill_type" name="view" value="summary" data-parsley-multiple="view" <?php echo (isset($summary)) ? $summary : ''; ?> >
                                  Summary
                              <i class="input-helper"></i></label>
                              </div>
                          </div>
                        </div>
                      </div>

                      <div class="col-12 col-md-5 col-sm-12">
                        <button type="submit" name="search" class="btn btn-success mt-30">Go</button>
                        <?php if(isset($searchdata)){ ?>
                          <a href="creditnote-report-print.php?from=<?php echo (isset($_POST['from'])) ? $_POST['from'] : ''; ?>&to=<?php echo (isset($_POST['to'])) ? $_POST['to'] : ''; ?>&view=<?php echo (isset($_POST['view'])) ? $_POST['view'] : ''; ?>" class="btn btn-primary mt-30" title="Print" target="_blank">Print</a>
                        <?php } ?>
                        </div>

                      </div>
                    </form>
                  </div>
                </div>
              </div>

             
              <?php if(isset($searchdata) && (isset($_POST['view']) && $_POST['view'] == 'detail')){ ?>
                <div class="col-md-12 grid-margin stretch-card">
                  <div class="card">
                    <div class="card-body">
                      <div class="row">
                        <div class="col-12">
                          <table class="table table-bordered table-striped datatable">
                            <thead>
                              <tr>
                                <th style="display: none;">no</th>
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
                                                echo (isset($value['credit_note_date'])) ? '<br/>'.date('d/m/Y',strtotime($value['credit_note_date'])) : '';
                                              }
                                            ?>
                                          </td>
                                          <td>
                                            <?php
                                              if($k == 0){
                                                echo (isset($value['party_name'])) ? $value['party_name'] : '';
                                                echo (isset($value['city_name'])) ? '<br/>'.$value['city_name'] : '';
                                              }
                                            ?>
                                          </td>
                                          <td class="text-right">
                                            <?php
                                              if($k == 0){
                                                echo (isset($value['bill_amount']) && $value['bill_amount'] != '') ? amount_format(number_format($value['bill_amount'], 2, '.', '')) : 0;
                                                $total_bill += (isset($value['bill_amount']) && $value['bill_amount'] != '') ? $value['bill_amount'] : 0;
                                              }
                                            ?>
                                          </td>
                                          <td class="text-right">
                                            <?php echo (isset($v['gst_name'])) ? $v['gst_name'] : ''; ?>
                                          </td>
                                          <td class="text-right">
                                            <?php echo (isset($v['gst_tax']) && $v['gst_tax'] != '') ? amount_format(number_format($v['gst_tax'], 2, '.', '')) : 0; ?>
                                          </td>
                                          <td class="text-right">
                                            <?php echo (isset($v['taxable_amount']) && $v['taxable_amount'] != '') ? amount_format(number_format($v['taxable_amount'], 2, '.', '')) : 0; ?>
                                          </td>
                                          <td>
                                            <?php echo (isset($v['item_name'])) ? $v['item_name'] : ''; ?>
                                          </td>
                                          <td class="text-right">
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
                              <tr style="background-color: #EFEFEF;">
                                  <th colspan="3" class="text-center">Total</th>
                                  <th class="text-right"><?php echo (isset($total_bill) && $total_bill != '') ? amount_format(number_format($total_bill, 2, '.', '')) : 0; ?></th>
                                  <th colspan="4"></th>
                                  <th class="text-right"><?php echo (isset($total_qty) && $total_qty != '') ? amount_format(number_format($total_qty, 2, '.', '')) : ''; ?></th>
                              </tr>
                            </tfoot>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              <?php }elseif(isset($searchdata) && (isset($_POST['view']) && $_POST['view'] == 'summary')){ ?>
                <div class="col-md-12 grid-margin stretch-card">
                  <div class="card">
                    <div class="card-body">
                      <div class="row">
                        <div class="col-12">
                          <table class="table table-bordered table-striped datatable">
                            <thead>
                              <tr>
                                <th>Sr. No</th>
                                <th>Credit Note Date</th>
                                <th>Credit Note No</th>
                                <th>Party Name</th> 
                                <th>GST No.</th>
                                <th>City</th>
                                <th>Taxable Amount</th>
                                <th>Tax Amount</th>
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
                                      <td class="text-right">
                                        <?php
                                          $taxable_amount = (isset($value['taxable_amount']) && $value['taxable_amount'] != '') ? $value['taxable_amount'] : 0;
                                          echo amount_format(number_format($taxable_amount, 2, '.', ''));
                                          $total_taxable += $taxable_amount;
                                        ?>
                                      </td>
                                      <td class="text-right">
                                        <?php
                                          $tax_amount = (isset($value['tax_amount']) && $value['tax_amount'] != '') ? $value['tax_amount'] : 0; 
                                          echo amount_format(number_format($tax_amount, 2, '.', ''));
                                          $total_tax += $tax_amount;
                                        ?>
                                      </td>
                                      <td class="text-right">
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
                              <tr style="background-color: #EFEFEF;">
                                  <th colspan="6" class="text-center">Total</th>
                                  <th class="text-right"><?php echo (isset($total_taxable) && $total_taxable != '') ? amount_format(number_format($total_taxable, 2, '.', '')) : 0; ?></th>
                                  <th class="text-right"><?php echo (isset($total_tax) && $total_tax != '') ? amount_format(number_format($total_tax, 2, '.', '')) : 0; ?></th>
                                  <th class="text-right"><?php echo (isset($total_amount) && $total_amount != '') ? amount_format(number_format($total_amount, 2, '.', '')) : 0; ?></th>
                              </tr>
                            </tfoot>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              <?php } ?>

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

 
 <!-- toast notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>

</body>


</html>
