<?php $title = "All Ledger"; ?>
<?php include('include/usertypecheck.php');?>
<?php include('include/permission.php'); ?>
<?php 
  $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : '';

  if(isset($_POST['search'])){

    $id = (isset($_POST['ladger'])) ? $_POST['ladger'] : '';
    $from = (isset($_POST['from']) && $_POST['from'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_POST['from']))) : ''; 
    $to = (isset($_POST['to']) && $_POST['to'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_POST['to']))) : '';
    
    $searchdata = capitalAccountReport($id, $from, $to, 0);
    // pr($searchdata);exit;
  }
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Digibooks | Capital Account Report</title>
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
                    <h4 class="card-title">Capital Account Report</h4><hr class="alert-dark">
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

                        <div class="col-12 col-md-3">
                          <label>Perticular<span class="text-danger">*</span></label>
                          <select class="js-example-basic-single" style="width:100%" name="ladger" id="ladger_list" data-parsley-errors-container="#error-ladger" required >  
                              <option value="">Select Perticular</option>
                              <?php 
                                $queryCapitalAcc = "SELECT name,id FROM ledger_master where pharmacy_id = '".$pharmacy_id."' AND group_id = 2 ORDER BY name";
                                $resultCapitalAcc = mysqli_query($conn,$queryCapitalAcc);
                                if($resultCapitalAcc && mysqli_num_rows($resultCapitalAcc) > 0){
                                  while ($rowCapitalAcc = mysqli_fetch_array($resultCapitalAcc)) {
                              ?>
                                <option value="<?php echo $rowCapitalAcc['id']; ?>" <?php echo (isset($_POST['ladger']) && $_POST['ladger'] == $rowCapitalAcc['id']) ? 'selected' : ''; ?>><?php echo $rowCapitalAcc['name']; ?></option>
                              <?php
                                  }
                                }
                              ?>
                          </select>
                          <span id="error-ladger"></span>
                        </div>

                          <div class="col-12 col-md-2">
                            <button type="submit" name="search" class="btn btn-success mt-30">Go</button>
                            <?php if(isset($searchdata)){ ?>
                              <a href="capital-account-report-print.php?id=<?php echo (isset($_POST['ladger'])) ? $_POST['ladger'] : ''; ?>&from=<?php echo (isset($_POST['from'])) ? $_POST['from'] : ''; ?>&to=<?php echo (isset($_POST['to'])) ? $_POST['to'] : ''; ?>" class="btn btn-primary mt-30" title="Print" target="_blank">Print</a>
                            <?php } ?>
                           
                          </div>

                          <label class="pull-right bg-success color-white p-2 <?php echo (isset($searchdata)) ? ' display-block' : ' display-none'; ?>" style="margin-top: 30px;" id="running_balance"> Running Balance : <?php echo (isset($searchdata['running_balance'])) ? amount_format(number_format(abs($searchdata['running_balance']), 2, '.', '')) : 0; 
                          echo (isset($searchdata['running_balance']) && $searchdata['running_balance'] >= 0) ? ' Dr' : ' Cr';
                           ?></label>

                      </div>
                    </form>
                  </div>
                </div>
              </div>

              <?php if(isset($searchdata)){?>
                <div class="col-md-12 grid-margin stretch-card">
                  <div class="card">
                    <div class="card-body">
                      <div class="row">
                        <div class="col-12">
                          <table class="table table-bordered table-striped datatable">
                            <thead>
                              <tr>
                                  <th width="7%">Sr No</th>
                                  <th width="10%">Date</th>
                                  <th width="5%">Cr/Dr</th>
                                  <th>Particulars</th>
                                  <th width="8%">Vch Type</th>
                                  <th width="10%">Vch No</th>
                                  <th class="text-right">Debit</th>
                                  <th class="text-right">Credit</th>
                              </tr> 
                            </thead>
                            <tbody>
                              <?php $total_debit = 0;$total_credit = 0;?>
                               <tr>
                                  <?php $opening_balance = (isset($searchdata['opening_balance']) && $searchdata['opening_balance'] != '') ? $searchdata['opening_balance'] : 0; ?>
                                  <td>1</td>
                                  <td><?php echo (isset($searchdata['date']) && $searchdata['date'] != '') ? date('d-m-Y',strtotime($searchdata['date'])) : ''; ?></td>
                                  <td><?php echo (isset($opening_balance) && $opening_balance >= 0) ? 'Dr' : 'Cr'; ?></td>
                                  <td>Opening Balance</td>
                                  <td></td>
                                  <td></td>
                                  <?php
                                    if($opening_balance >= 0){
                                        echo '<td style="text-align:right;">'.amount_format(number_format((abs($searchdata['opening_balance'])), 2, '.', '')).'</td><td></td>';
                                        $total_debit += $searchdata['opening_balance'];
                                    }else{
                                        echo '<td></td><td style="text-align:right;">'.amount_format(number_format((abs($searchdata['opening_balance'])), 2, '.', '')).'</td>';
                                        $total_credit += abs($searchdata['opening_balance']);
                                    }
                                  ?>
                                </tr>
                                <?php if(isset($searchdata['data']) && !empty($searchdata['data'])){ $i = 2; ?>
                                    <?php foreach ($searchdata['data'] as $key => $value) { ?>
                                        <?php if(!empty($value)){ ?>
                                            <?php foreach($value as $k => $v){ ?>
                                                <tr>
                                                    <td><?php echo $i;$i++; ?></td>
                                                    <td>
                                                        <?php
                                                            if($k == 0){
                                                                echo (isset($key) && $key != '') ? date('d-m-Y',strtotime($key)) : '';
                                                            }
                                                        ?>
                                                    </td>
                                                    <td><?php echo (isset($v['crdr'])) ? $v['crdr'] : ''; ?></td>
                                                    <td><?php echo (isset($v['perticular'])) ? $v['perticular'] : ''; ?></td>
                                                    <td><?php echo (isset($v['type'])) ? $v['type'] : ''; ?></td>
                                                    <td><?php echo (isset($v['voucherno'])) ? $v['voucherno'] : ''; ?></td>
                                                    <td class="text-right">
                                                        <?php
                                                            if(isset($v['debit']) && $v['debit'] != ''){
                                                                echo amount_format(number_format($v['debit'], 2, '.', ''));
                                                                $total_debit += $v['debit'];
                                                            }
                                                        ?>
                                                    </td>
                                                    <td class="text-right">
                                                        <?php
                                                            if(isset($v['credit']) && $v['credit'] != ''){
                                                                echo amount_format(number_format($v['credit'], 2, '.', ''));
                                                                $total_credit += $v['credit'];
                                                            }
                                                        ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        <?php } ?>
                                    <?php } ?>
                                <?php } ?>
                            </tbody>
                            <tfoot>
                              <tr style="background-color: #EFEFEF;">
                                <th colspan="6" class="text-center"><strong>Total</strong></th>
                                <th class="text-right">
                                    <?php echo (isset($total_debit) && $total_debit != '') ? amount_format(number_format($total_debit, 2, '.', '')) : 0; ?>
                                </th>
                                <th class="text-right">
                                  <?php echo (isset($total_credit) && $total_credit != '') ? amount_format(number_format($total_credit, 2, '.', '')) : 0; ?>
                                </th>
                                
                              </tr>
                              <tr style="background-color: #EFEFEF;">
                                  <th colspan="6" class="text-center"><strong>Closing Balance</strong></th>
                                  <th class="text-right">
                                      <?php  
                                        if($total_debit <= $total_credit){
                                            echo amount_format(number_format(($total_credit-$total_debit), 2, '.', ''));
                                            $total_debit += ($total_credit-$total_debit);
                                        }
                                      ?>
                                  </th>
                                  <th class="text-right">
                                      <?php  
                                        if($total_credit <= $total_debit){
                                            echo amount_format(number_format(($total_debit-$total_credit), 2, '.', ''));
                                            $total_credit += ($total_debit-$total_credit);
                                        }
                                      ?>
                                  </th>
                              </tr>
                              <tr style="background-color: #EFEFEF;">
                                  <th colspan="6" class="text-center"></th>
                                  <th class="text-right">
                                      <?php echo (isset($total_debit) && $total_debit != '') ? amount_format(number_format($total_debit, 2, '.', '')) : 0; ?>
                                  </th>
                                  <th class="text-right">
                                      <?php echo (isset($total_credit) && $total_credit != '') ? amount_format(number_format($total_credit, 2, '.', '')) : 0; ?>
                                  </th>
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
<script src="js/custom/capital-account-report.js"></script>

 
 <!-- toast notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>
  <!-- End custom js for this page-->


</script>
 
</body>


</html>
