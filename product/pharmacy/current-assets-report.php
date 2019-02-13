<?php $title = "All Ledger"; ?>
<?php include('include/usertypecheck.php');?>
<?php include('include/permission.php'); ?>
<?php 
  $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : '';

  if(isset($_POST['search'])){
    //$id = (isset($_POST['ladger'])) ? $_POST['ladger'] : '';
    //$from = (isset($_POST['from']) && $_POST['from'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_POST['from']))) : ''; 
    //$to = (isset($_POST['to']) && $_POST['to'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_POST['to']))) : '';
    
    // $searchdata = CurrentAssetsReport($id, $from, $to);
    $searchdata = getCurrentAssetsSecond($_POST['from'], $_POST['to']);
  }
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Digibooks | Current Assets Report</title>
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
                    <h4 class="card-title">Current Assets Report</h4><hr class="alert-dark">
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

                        <!--<div class="col-12 col-md-3">
                          <label>Perticular</label>
                          <select class="js-example-basic-single" style="width:100%" name="ladger" id="ladger_list" data-parsley-errors-container="#error-ladger" required >  
                              <option value="">Select Perticular</option>
                              <?php 
                               /* $queryCapitalAcc = "SELECT name,id FROM ledger_master where pharmacy_id = '".$pharmacy_id."' AND group_id = 4 ORDER BY name";
                                $resultCapitalAcc = mysqli_query($conn,$queryCapitalAcc);
                                if($resultCapitalAcc && mysqli_num_rows($resultCapitalAcc) > 0){
                                  while ($rowCapitalAcc = mysqli_fetch_array($resultCapitalAcc)) {*/
                              ?>
                                <option value="<?php //echo $rowCapitalAcc['id']; ?>" <?php //echo (isset($_POST['ladger']) && $_POST['ladger'] == $rowCapitalAcc['id']) ? 'selected' : ''; ?>><?php echo $rowCapitalAcc['name']; ?></option>
                              <?php
                                  //}
                                //}
                              ?>
                          </select>
                          <span id="error-ladger"></span>
                        </div>-->

                          <div class="col-12 col-md-2">
                            <button type="submit" name="search" class="btn btn-success mt-30">Go</button>
                            <?php if(isset($searchdata)){ ?>
                              <a href="current-assets-report-print.php?from=<?php echo (isset($_POST['from'])) ? $_POST['from'] : ''; ?>&to=<?php echo (isset($_POST['to'])) ? $_POST['to'] : ''; ?>" class="btn btn-primary mt-30" title="Print" target="_blank">Print</a>
                            <?php } ?>
                           
                          </div>

                          <!--<label class="pull-right bg-success color-white p-2 <?php //echo (isset($searchdata)) ? ' display-block' : ' display-none'; ?>" style="margin-top: 30px;" id="running_balance"> Running Balance : <?php echo (isset($searchdata['running_balance'])) ? amount_format(number_format(abs($searchdata['running_balance']), 2, '.', '')) : 0; 
                          //echo (isset($searchdata['running_balance']) && $searchdata['running_balance'] >= 0) ? ' Dr' : ' Cr';
                           ?></label>-->

                      </div>
                    </form>
                  </div>
                </div>
              </div>

              <?php if(isset($searchdata)){ 
                     
                ?>
                <div class="col-md-12 grid-margin stretch-card">
                  <div class="card">
                    <div class="card-body">
                      <div class="row">
                        <div class="col-12">
                          <table class="table table-bordered table-striped">
                            <thead>
                              <tr>
                                  <th>Particulars</th>
                                  <th width="15%" class="text-right">Debit</th>
                                  <th width="15%" class="text-right">Credit</th>
                              </tr> 
                            </thead>
                            <tbody>
                                <?php $grandtotal = 0; ?>
                                <?php if(!empty($searchdata)){ ?>
                                    <?php foreach($searchdata as $key => $value){ ?>
                                        <tr>
                                            <td><strong><?php echo (isset($value['name'])) ? $value['name'] : ''; ?></strong></td>
                                            <td class="text-right" style="border-bottom: 1px solid;">
                                                <strong>
                                                    <?php
                                                        if(isset($value['totaldebit']) && $value['totaldebit'] != '' && $value['totaldebit'] >= 0){
                                                            echo amount_format(number_format($value['totaldebit'], 2, '.', ''));
                                                            $grandtotal += $value['totaldebit'];
                                                        }
                                                    ?>
                                                </strong>
                                            </td>
                                            <td class="text-right" style="border-bottom: 1px solid;">
                                                <strong>
                                                    <?php
                                                        if(isset($value['totaldebit']) && $value['totaldebit'] != '' && $value['totaldebit'] < 0){
                                                            echo amount_format(number_format($value['totaldebit'], 2, '.', ''));
                                                            $grandtotal += $value['totaldebit'];
                                                        }
                                                    ?>
                                                </strong>
                                            </td>
                                        </tr>
                                        <?php if(isset($value['detail']) && !empty($value['detail'])){ ?>
                                            <?php foreach($value['detail'] as $k => $v){ ?>
                                                <tr>
                                                    <td><?php echo (isset($v['name'])) ? $v['name'] : ''; ?></td>
                                                    <td class="text-right">
                                                        <?php
                                                            if(isset($v['debit']) && $v['debit'] != '' && $v['debit'] >= 0){
                                                                echo amount_format(number_format($v['debit'], 2, '.', '')); 
                                                            }
                                                        ?>
                                                    </td>
                                                    <td class="text-right">
                                                        <?php
                                                            if(isset($v['debit']) && $v['debit'] != '' && $v['debit'] < 0){
                                                                echo amount_format(number_format($v['debit'], 2, '.', '')); 
                                                            }
                                                        ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        <?php } ?>
                                    <?php } ?>
                                <?php } ?>
                                <tr style="background-color: #EFEFEF;">
                                    <td class="text-center"><strong>Grand Total</strong></td>
                                    <td class="text-right">
                                        <strong>
                                            <?php
                                                if(isset($grandtotal) && $grandtotal >= 0){
                                                    echo amount_format(number_format($grandtotal, 2, '.', ''));     
                                                }
                                            ?>
                                        </strong>
                                    </td>
                                    <td class="text-right">
                                        <strong>
                                            <?php
                                                if(isset($grandtotal) && $grandtotal < 0){
                                                    echo amount_format(number_format($grandtotal, 2, '.', ''));     
                                                }
                                            ?>
                                        </strong>
                                    </td>
                                </tr>
                            </tbody>
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
<script src="js/custom/current-assets-report.js"></script>

 
 <!-- toast notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>
  <!-- End custom js for this page-->


</script>
 
</body>


</html>
