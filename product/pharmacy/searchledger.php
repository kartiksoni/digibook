<?php include('include/usertypecheck.php'); ?>

<?php
  $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
  $financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';

  if((isset($_GET['vendor']) && $_GET['vendor'] != '') || (isset($_GET['customer']) && $_GET['customer'] != '')){
    $id = (isset($_GET['vendor'])) ? $_GET['vendor'] : $_GET['customer'];
    $type = (isset($_GET['vendor'])) ? 'vendor' : 'customer';

    $ledgerQ = "SELECT lg.id, lg.name, lg.customer_type, lg.opening_balance, lg.opening_balance_type, lg.mobile, lg.email, lg.addressline1, lg.addressline2, lg.addressline3, ctr.name as country, st.name as state, ct.name as city FROM ledger_master lg LEFT JOIN own_countries ctr ON lg.country = ctr.id LEFT JOIN own_states st ON lg.state = st.id LEFT JOIN own_cities ct ON lg.city = ct.id WHERE lg.id = '".$id."' AND lg.pharmacy_id = '".$pharmacy_id."'";
    $ledgerR = mysqli_query($conn, $ledgerQ);
    if($ledgerR && mysqli_num_rows($ledgerR) > 0){
      $ledgerRow = mysqli_fetch_assoc($ledgerR);
      $data['id'] = (isset($ledgerRow['id'])) ? $ledgerRow['id'] : '';
      $data['name'] = (isset($ledgerRow['name']) && $ledgerRow['name'] != '') ? ucwords(strtolower($ledgerRow['name'])) : 'Unknown Ledger';
      $opening_balance = (isset($ledgerRow['opening_balance']) && $ledgerRow['opening_balance'] != '') ? $ledgerRow['opening_balance'] : 0;
      $data['opening_balance'] = (isset($ledgerRow['opening_balance_type']) && $ledgerRow['opening_balance_type'] == 'DB') ? $opening_balance : -$opening_balance;
      if(isset($ledgerRow['customer_type'])){
        if($ledgerRow['customer_type'] == 'GST_Regular'){
          $data['type'] = 'GST Registered - Regular';
        }elseif($ledgerRow['customer_type'] == 'GST_Composition'){
          $data['type'] = 'GST Registered - Composition';
        }elseif($ledgerRow['customer_type'] == 'GST_unregistered'){
          $data['type'] = 'GST Unregistered';
        }elseif($ledgerRow['customer_type'] == 'Consumer'){
          $data['type'] = 'Consumer';
        }elseif($ledgerRow['customer_type'] == 'Overseas'){
          $data['type'] = 'Overseas';
        }elseif($ledgerRow['customer_type'] == 'SEZ'){
          $data['type'] = 'SEZ';
        }elseif($ledgerRow['customer_type'] == 'Deemed'){
          $data['type'] = "Deemed exports- EOU's, STP's EHTP's etc";
        }else{
          $data['type'] = "Unknown Type";
        }
      }
      $data['mobile'] = (isset($ledgerRow['mobile'])) ? $ledgerRow['mobile'] : '';
      $data['email'] = (isset($ledgerRow['email'])) ? $ledgerRow['email'] : '';
        $address = [];
        if(isset($ledgerRow['addressline1']) && $ledgerRow['addressline1'] != ''){ $address[] =  $ledgerRow['addressline1'];}
        if(isset($ledgerRow['addressline2']) && $ledgerRow['addressline2'] != ''){ $address[] =  $ledgerRow['addressline2'];}
        if(isset($ledgerRow['addressline3']) && $ledgerRow['addressline3'] != ''){ $address[] =  $ledgerRow['addressline3'];}
        if(isset($ledgerRow['city']) && $ledgerRow['city'] != ''){ $address[] =  $ledgerRow['city'];}
        if(isset($ledgerRow['state']) && $ledgerRow['state'] != ''){ $address[] =  $ledgerRow['state'];}
        if(isset($ledgerRow['country']) && $ledgerRow['country'] != ''){ $address[] =  $ledgerRow['country'];}
      $data['address'] = (!empty($address)) ? implode(', ', $address) : 'Unknown Address';

      // purchase and sale
      if(isset($type) && $type == 'vendor'){
        $fQ = "SELECT id, invoice_date, invoice_no, purchase_type as bill_type, total_total as amount, 'purchase' as bill FROM purchase WHERE pharmacy_id = '".$pharmacy_id."' AND financial_id = '".$financial_id."' AND vendor = '".$id."'";
      }else{
        $fQ = "SELECT id, invoice_date, invoice_no, bill_type, sale_amount as amount, 'sale' as bill FROM tax_billing WHERE pharmacy_id = '".$pharmacy_id."' AND financial_id = '".$financial_id."' AND customer_id = '".$id."' ORDER BY created DESC";
      }
      $fR = mysqli_query($conn, $fQ);
      if($fR && mysqli_num_rows($fR) > 0){
        while ($fRow = mysqli_fetch_assoc($fR)) {
          $data['first'][] = $fRow;
        }
      }

      // purchase return and sale return
      if(isset($type) && $type == 'vendor'){
        $sQ = "SELECT id, debit_note_date as note_date, debit_note_no as note_no, finalamount as amount, 'purchase_return' as bill FROM purchase_return WHERE pharmacy_id = '".$pharmacy_id."' AND financial_id = '".$financial_id."' AND vendor_id = '".$id."'";
      }else{
         $sQ = "SELECT id, credit_note_no as note_no, credit_note_date as note_date, finalamount as amount, 'sale_return' as bill FROM sale_return WHERE pharmacy_id = '".$pharmacy_id."' AND financial_id = '".$financial_id."' AND customer_id = '".$id."' ORDER BY created DESC";
      }
      $sR = mysqli_query($conn, $sQ);
      if($sR && mysqli_num_rows($sR) > 0){
        while ($sRow = mysqli_fetch_assoc($sR)) {
          $data['second'][] = $sRow;
        }
      }
      // pr($data);exit;
    }
  }
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>DigiBooks | History</title>
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
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="css/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="images/favicon.png" />
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

            <!-- Info -->
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <a href="<?php echo (isset($type) && $type == 'vendor') ? 'vendor-ledger-print.php':'customer-ledger-print.php'; ?>?id=<?php echo (isset($data['id'])) ? $data['id'] : ''; ?>" class="pull-right btn btn btn-xs btn-primary" target="_blank">View Ledger</a>
                  <h4 class="card-title">Info</h4><hr class="alert-dark">
                  <div class="table-responsive">
                    <table width="100%">
                      <tbody>
                        <tr>
                          <td width="50%" style="vertical-align: top;">
                            <table width="100%" class="table table-bordered">
                              <tbody>
                                <tr>
                                  <td width="30%">Name</td>
                                  <td><?php echo (isset($data['name'])) ? $data['name'] : ''; ?></td>
                                </tr>
                                <tr>
                                  <td width="30%">Opening Balance</td>
                                  <td><?php echo (isset($data['opening_balance']) && $data['opening_balance'] != '') ? amount_format(number_format($data['opening_balance'], 2, '.', '')) : ''; ?></td>
                                </tr>
                                 <tr>
                                  <td width="30%">Type</td>
                                  <td><?php echo (isset($data['type'])) ? $data['type'] : ''; ?></td>
                                </tr>
                              </tbody>
                            </table>
                          </td>
                          <td style="vertical-align: top;">
                            <table width="100%" class="table table-bordered">
                              <tbody>
                                <tr>
                                  <td width="30%">Mobile</td>
                                  <td><?php echo (isset($data['mobile'])) ? $data['mobile'] : ''; ?></td>
                                </tr>
                                <tr>
                                  <td width="30%">Email</td>
                                  <td><?php echo (isset($data['email'])) ? $data['email'] : ''; ?></td>
                                </tr>
                                <tr>
                                  <td width="30%">Address</td>
                                  <td><?php echo (isset($data['address'])) ? $data['address'] : ''; ?></td>
                                </tr>
                              </tbody>
                            </table>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Sale AND Purchase -->
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">View <?php echo (isset($type) && $type == 'vendor') ? 'Purchase' : 'Sale'; ?></h4>
                  <hr class="alert-dark">
                  <br>
                  <div class="col">
                       <div class="row">
                            <div class="col-12">
                              <div class="table-responsive">
                                <table class="table datatable">
                                  <thead>
                                    <tr>
                                        <th>Sr No</th>
                                        <th>Invoice Date</th>
                                        <th>Invoice No</th>
                                        <th>Bill Type</th>
                                        <th class="text-right">Amount</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <?php if(isset($data['first']) && !empty($data['first'])){ ?>
                                      <?php foreach ($data['first'] as $key => $value) { ?>
                                        <tr>
                                          <td><?php echo ($key+1); ?></td>
                                          <td><?php echo (isset($value['invoice_date']) && $value['invoice_date'] != '' && $value['invoice_date'] != '0000-00-00') ? date('d/m/Y',strtotime($value['invoice_date'])) : ''; ?></td>
                                          <td><?php echo (isset($value['invoice_no'])) ? $value['invoice_no'] : '';?></td>
                                          <td><?php echo (isset($value['bill_type'])) ? $value['bill_type'] : '';?></td>
                                          <td class="text-right">
                                            <?php echo (isset($value['amount']) && $value['amount'] != '') ? amount_format(number_format($value['amount'], 2, '.', '')) : '';?>
                                          </td>
                                          <td class="text-center">
                                            <a class="btn  btn-behance p-2" href="<?php echo (isset($value['bill']) && $value['bill'] == 'purchase') ? 'purchase' : 'sales-tax-billing'; ?>.php?id=<?php echo (isset($value['id'])) ? $value['id'] : ''; ?>" title="edit" target="_blank"><i class="fa fa-pencil mr-0"></i></a>
                                          </td>
                                        </tr>
                                      <?php } ?>
                                    <?php } ?>
                                  </tbody>
                                </table>
                              </div>
                            </div>
                          </div>
                    </div>
                </div>
              </div>
            </div>

            <!-- Sale AND Purchase -->
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">View <?php echo (isset($type) && $type == 'vendor') ? 'Purchase' : 'Sale'; ?> Return</h4>
                  <hr class="alert-dark">
                  <br>
                  <div class="col">
                       <div class="row">
                            <div class="col-12">
                              <div class="table-responsive">
                                <table class="table datatable">
                                  <thead>
                                    <tr>
                                        <th>Sr No</th>
                                        <th><?php echo (isset($type) && $type == 'vendor') ? 'Debit Note' : 'Credit Note'; ?> Date</th>
                                        <th><?php echo (isset($type) && $type == 'vendor') ? 'Debit Note' : 'Credit Note'; ?> No</th>
                                        <th class="text-right">Amount</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <?php if(isset($data['second']) && !empty($data['second'])){ ?>
                                      <?php foreach ($data['second'] as $key => $value) { ?>
                                        <tr>
                                          <td><?php echo ($key+1); ?></td>
                                          <td>
                                            <?php echo (isset($value['note_date']) && $value['note_date'] != '' && $value['note_date'] != '0000-00-00') ? date('d/m/Y',strtotime($value['note_date'])) : ''; ?>
                                          </td>
                                          <td>
                                            <?php echo (isset($value['note_no'])) ? $value['note_no'] : ''; ?>
                                          </td>
                                          <td class="text-right">
                                            <?php echo (isset($value['amount']) && $value['amount'] != '') ? amount_format(number_format($value['amount'], 2, '.', '')) : '';?>
                                          </td>
                                          <td class="text-center">
                                            <a class="btn  btn-behance p-2" href="<?php echo (isset($value['bill']) && $value['bill'] == 'purchase_return') ? 'purchase-return' : 'sales-return'; ?>.php?id=<?php echo (isset($value['id'])) ? $value['id'] : ''; ?>" title="edit" target="_blank"><i class="fa fa-pencil mr-0"></i></a>
                                          </td>
                                        </tr>
                                      <?php } ?>
                                    <?php } ?>
                                  </tbody>
                                </table>
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
  
  <!-- Custom js for this page-->
  <script src="js/modal-demo.js"></script>
  <!-- Custom js for this page-->
  <script src="js/data-table.js"></script> 
  
  <script>
     $('.datatable').DataTable();
  </script>
  <!-- End custom js for this page-->
</body>


</html>
