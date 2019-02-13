<?php $title = "Free Goods Reports"; ?>
<?php include('include/usertypecheck.php');?>
<?php //include('include/permission.php'); ?>
<?php 

  if(isset($_POST['search'])){

    $from = (isset($_POST['from']) && $_POST['from'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_POST['from']))) : ''; 
    $to = (isset($_POST['to']) && $_POST['to'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_POST['to']))) : '';
    $company = (isset($_POST['company'])) ? $_POST['company'] : '';
    $ledger = (isset($_POST['ledger'])) ? $_POST['ledger'] : '';
    $type = (isset($_POST['type'])) ? $_POST['type'] : '';
    $bill_type = (isset($_POST['bill_type'])) ? $_POST['bill_type'] : '';
    $sub_type = (isset($_POST['sub_type'])) ? $_POST['sub_type'] : '';
    $searchdata = freegoods($from, $to, $bill_type, $ledger, $company, $type, $sub_type);

  }
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Digibooks | Free Goods Report</title>
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
           <?php include('include/stock_header.php'); ?>
              <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Free Goods Report</h4><hr class="alert-dark">
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
                        <?php 
                          $detail = 'checked';
                          $summary = '';
                          if(isset($_POST['bill_type']) && $_POST['bill_type'] == 'summary'){
                           $summary = 'checked';
                          }elseif(isset($_POST['bill_type']) && $_POST['bill_type'] == 'detail'){
                            $detail = 'checked';
                          }
                        ?>
                        <?php 
                          $company_wise = '';
                          $all = 'checked';
                          if(isset($_POST['type']) && $_POST['type'] == 'company_wise'){
                           $company_wise = 'checked';
                           $all = '';
                          }elseif(isset($_POST['type']) && $_POST['type'] == 'all'){
                            $all = 'checked';
                          $company_wise = '';
                          }
                        ?>
                       <div class="col-12 col-md-5">
                        <label for="bill_type">Select Any One</label>
                        <div class="row no-gutters">
                            
                            <div class="col">
                                <div class="form-radio">
                                  <label class="form-check-label">
                                    <input type="radio" class="form-check-input bill_type" name="bill_type" value="detail" data-parsley-multiple="bill_type" <?php echo (isset($detail)) ? $detail : ''; ?>>
                                     Detail
                                     <i class="input-helper"></i>
                                </label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-radio">
                                  <label class="form-check-label">
                                    <input type="radio" class="form-check-input bill_type" name="bill_type" value="summary" data-parsley-multiple="bill_type" <?php echo (isset($summary)) ? $summary : ''; ?>>
                                    Summary
                                    <i class="input-helper"></i>
                                  </label>
                                </div>
                            </div>
                            
                            <div class="col">
                                <div class="form-radio">
                                  <label class="form-check-label">
                                    <input type="radio" class="form-check-input type" name="type"  value="all" <?php echo (isset($all)) ? $all : ''; ?> data-parsley-errors-container="#error-all" required>
                                    All
                                    <i class="input-helper"></i>
                                </label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-radio">
                                  <label class="form-check-label">
                                    <input type="radio" class="form-check-input type" id="type" name="type" value="company_wise" <?php echo (isset($company_wise)) ? $company_wise : ''; ?> data-parsley-errors-container="#error-company-wise" required>
                                    Company Wise
                                    <i class="input-helper"></i>
                                </label>
                                </div>
                            </div>
                          </div>       
                       </div>
                       <div class="col-md-3" id="company_name" <?php if(isset($_POST['type']) && $_POST['type'] == "company_wise"){}else{ ?> style="display: none;<?php } ?>">
                        <label>Company Name</label>
                        <select class="js-example-basic-single" style="width:100%" name="company" id="company" data-parsley-errors-container="#error-company" required>
                          <option value="">Please Select Company</option>
                          <?php
                          $companyQ = "SELECT * FROM company_master WHERE pharmacy_id = '".$pharmacy_id."' ORDER BY name";
                          $companyRes = mysqli_query($conn, $companyQ); 

                          ?>
                          <?php if($companyRes && mysqli_fetch_row($companyRes)){ ?>
                            <?php while ($companyRow = mysqli_fetch_array($companyRes)) { ?>
                              <option value="<?php echo $companyRow['id'] ?>"<?php echo (isset($_POST['company']) && $_POST['company'] == $companyRow['id']) ? 'selected' : ''; ?>><?php echo $companyRow['name']; ?></option>
                            <?php } ?>
                          <?php } ?>
                        </select>
                        <span id="error-company"></span>
                      </div>
                      </div>
                      <div id="ledger_name" <?php if(isset($_POST['type']) && $_POST['type'] == 'company_wise'){ ?> style="display: none;" <?php } ?>>
                        <hr/>

                        <div class="form-group row">
                         
                          <div class="col-md-2">
                            <div class="col">
                                <div class="form-radio">
                                  <label class="form-check-label">
                                    <input type="radio" class="form-check-input type" name="sub_type" id="sub_type1" value="all1" checked <?php if(isset($_POST['sub_type']) && $_POST['sub_type'] == 'all1'){ echo "checked"; } ?>>
                                    All
                                    <i class="input-helper"></i>
                                  </label>
                                </div>
                            </div>
                          </div>
                          <div class="col-md-3">
                            <div class="col">
                                <div class="form-radio">
                                  <label class="form-check-label">
                                    <input type="radio" class="form-check-input type" name="sub_type" id="sub_type2" value="party_wise" <?php if(isset($_POST['sub_type']) && $_POST['sub_type'] == 'party_wise'){ echo "checked"; } ?>>
                                    Party Wise
                                    <i class="input-helper"></i>
                                  </label>  
                                </div>
                            </div>
                          </div>
                        

                          <div class="col-md-3" id="ledger_name2" <?php if(isset($_POST['sub_type']) && $_POST['sub_type']=="party_wise"){}else{ ?> style="display:none;<?php } ?>">
                              <label>Select Ledger</label>
                                <select class="js-example-basic-single" style="width:100%" name="ledger" id="ledger" data-parsley-errors-container="#error-ledger" required> 
                                  <option value="">Please select</option>
                                  <?php 
                                    $query = "SELECT id, name FROM ledger_master WHERE status=1 AND pharmacy_id = '".$pharmacy_id."' AND (group_id = 10 OR group_id = 14) ORDER BY name";
                                    $allResult = mysqli_query($conn, $query);
                                  ?>
                                  <?php if($allResult && mysqli_num_rows($allResult) > 0){ ?>
                                    <?php while ($allRow = mysqli_fetch_array($allResult)) { ?>
                                      <option value="<?php echo $allRow['id']; ?>" <?php echo (isset($_POST['ledger']) && $_POST['ledger'] == $allRow['id']) ? 'selected' : ''; ?> ><?php echo $allRow['name']; ?></option>
                                   <?php } ?>
                                  <?php } ?>
                                </select>
                                <span id="error-ledger"></span>
                            </div>
                          </div>
                      </div>

                      <div class="col-12 col-md-5 col-sm-12">
                          <button type="submit" name="search" class="btn btn-success mt-30">Go</button>
                          <?php if(isset($_POST['search'])){ ?>
                              <a href="free-goods-report-print.php?from=<?php echo (isset($_POST['from'])) ? $_POST['from'] : ''; ?>&company=<?php echo (isset($_POST['company'])) ? $_POST['company'] : '';?>&to=<?php echo (isset($_POST['to'])) ? $_POST['to'] : ''; ?>&ledger=<?php echo (isset($_POST['ledger'])) ? $_POST['ledger'] : ''; ?>&bill_type=<?php echo (isset($_POST['bill_type'])) ? $_POST['bill_type'] : '';?>&type=<?php echo (isset($_POST['type'])) ? $_POST['type'] : '';?>&sub_type=<?php echo (isset($_POST['sub_type'])) ? $_POST['sub_type'] : '';?>" class="btn btn-primary mt-30" title="Print" target="_blank">Print</a>
                            <?php } ?>
                      </div>

                    </form>
                  </div>
                </div>
              </div>

              <?php if(isset($searchdata)){ ?>
                <div class="col-md-12 grid-margin stretch-card">
                  <div class="card">
                    <div class="card-body">
                      <div class="row">
                        <div class="col-12">
                          <table class="table table-bordered table-striped datatable">
                            <thead>
                              <tr>
                                <th class="text-center" rowspan="2">Sr. No</th>
                                <?php if(isset($_POST['bill_type']) && $_POST['bill_type'] == 'detail'){ ?>
                                <th class="text-center" rowspan="2">Voucher/Debit Note Date</th>
                                <th class="text-center" rowspan="2">Voucher/Debit Note No</th>
                                 <?php } ?>
                                 <th class="text-center" rowspan="2"><?php if((isset($bill_type) && $bill_type == 'summary') && (isset($type) && $type == 'company_wise')){echo "Company Name"; } else if((isset($bill_type) && $bill_type == 'summary') && (isset($sub_type) && $sub_type == 'all1')){ echo "Product Name"; }else{ echo "Product Name";}?></th>
                                 <th class="text-center" colspan="2" rowspan="1">Purchase</th>
                                 <th class="text-center" colspan="2" rowspan="1">Sale</th>
                              </tr>
                              <tr>
                                <th class="text-center">Free Qty.</th>
                                <th class="text-center">Amount</th>
                                <th class="text-center">Free Qty.</th>
                                <th class="text-center">Amount</th>
                              </tr> 
                            </thead>
                            <tbody>
                              <?php $total_amount = 0; $total_freeqty = 0; $total_amount1 = 0; $total_freeqty1 = 0; ?>
                              <?php if(!empty($searchdata)){ ?>
                                <?php foreach ($searchdata as $key => $value) { ?>
                                  <tr>
                                    <td class="text-center"><?php echo $key+1; ?></td>
                                    <?php if(isset($_POST['bill_type']) && $_POST['bill_type'] == 'detail'){ ?>
                                    <td class="text-center"><?php echo (isset($value['date']) && $value['date'] != '') ? date('d/m/Y',strtotime($value['date'])) : '';?></td>
                                    <td class="text-center"><?php echo (isset($value['no'])) ? $value['no'] : ''; ?></td>
                                    <?php } ?>
                                    <td class="text-center"><?php echo (isset($value['product_name'])) ? $value['product_name'] : '';?></td>
                                    <td class="text-center"><?php echo (isset($value['free_qty'])) ? $value['free_qty'] : ''; ?></td>
                                    <td class="text-center"><?php echo (isset($value['amount'])) ? $value['amount'] : ''; ?></td>
                                    <td class="text-center"><?php echo (isset($value['free_qty1'])) ? $value['free_qty1'] : ''; ?></td>
                                    <td class="text-center"><?php echo (isset($value['amount1'])) ? $value['amount1'] : ''; ?></td>
                                  </tr>
                                  <?php
                                    $freeqty = (isset($value['free_qty']) && $value['free_qty'] != '') ? $value['free_qty'] : '-';
                                    $total_freeqty += $freeqty;
                                    $amount = (isset($value['amount']) && $value['amount'] != '') ? $value['amount'] : '-';
                                      $total_amount += $amount;

                                    $freeqty1 = (isset($value['free_qty1']) && $value['free_qty1'] != '') ? $value['free_qty1'] : '';
                                    $total_freeqty1 += $freeqty1;
                                    $amount1 = (isset($value['amount1']) && $value['amount1'] != '') ? $value['amount1'] : '';
                                      $total_amount1 += $amount1;
                                  ?>
                                <?php } ?>
                              <?php } ?>
                            </tbody>
                            <tfoot>
                              <tr style="background-color: #EFEFEF;">
                                <th colspan="<?php echo (isset($_POST['bill_type']) && $_POST['bill_type'] == 'detail') ? 4 : 2; ?>" class="text-center"><strong>Total</strong></th>
                                <th class="text-center">
                                  <?php echo (isset($total_freeqty)) ? $total_freeqty : 0; ?>
                                </th>
                                <th class="text-center">
                                  <?php echo (isset($total_amount)) ? amount_format(number_format($total_amount, 2, '.', '')) : 0; ?>
                                </th>
                                <th class="text-center">
                                  <?php echo (isset($total_freeqty1)) ? $total_freeqty1 : 0; ?>
                                </th>
                                <th class="text-center">
                                  <?php echo (isset($total_amount1)) ? amount_format(number_format($total_amount1, 2, '.', '')) : 0; ?>
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
  <!--<script src="js/dropzone.js"></script>-->
  <script src="js/jquery-file-upload.js"></script>
  <script src="js/formpickers.js"></script>
  <script src="js/form-repeater.js"></script>
  
  <!-- Custom js for this page Modal Box-->
  <!--<script src="js/modal-demo.js"></script>-->
  
  
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
  $('form').parsley({
  excluded: ':hidden'
});
</script>
<!--<script src="js/custom/onlynumber.js"></script>-->
<script src="js/custom/free-goods-report.js"></script>
<!--<script src="js/custom/customer_ledger.js"></script>-->

  <!-- End custom js for this page-->
</body>


</html>
