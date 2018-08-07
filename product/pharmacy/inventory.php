<?php include('include/usertypecheck.php');
    /* SET SESSION START */

        // set alphabet in session
        if(isset($_GET['alphabet']) && $_GET['alphabet'] != ''){
          $_SESSION['inventory_alphabet'] = $_GET['alphabet'];
        }
        // set product in session [All, Available, Expiry, Zero Stock, Over Stock, Non Moving Stock, Reorder]
        if(isset($_GET['product']) && $_GET['product'] != ''){
          unset($_SESSION['search']);
          unset($_SESSION['searchid']);
          unset($_SESSION['selectsearch']);
          unset($_SESSION['inventory_alphabet']);
          $_SESSION['product'] = $_GET['product'];
        }
        // set selectsearch in session [Product, MRP, Generic]
        if(isset($_GET['selectsearch']) && $_GET['selectsearch'] != ''){
          $_SESSION['selectsearch'] = $_GET['selectsearch'];
        }
        // set search and search ID in session
        if(isset($_GET['search']) && $_GET['search'] != ''){
          $_SESSION['search'] = $_GET['search'];
          if(isset($_GET['searchid']) && $_GET['searchid'] != ''){
            $_SESSION['searchid'] = $_GET['searchid'];
            unset($_SESSION['product']);
          }
        }elseif(isset($_GET['search']) && $_GET['search'] == ''){
          unset($_SESSION['search']);
          unset($_SESSION['searchid']);
        }
        // set month in session
        if(isset($_GET['month']) && $_GET['month'] != ''){
          $_SESSION['month'] = $_GET['month'];
        }elseif (isset($_GET['month']) && $_GET['month'] == '') {
          unset($_SESSION['month']);
        }

        // set percentage in session
        if(isset($_GET['percentage']) && $_GET['percentage'] != ''){
          $_SESSION['percentage'] = $_GET['percentage'];
        }elseif (isset($_GET['percentage']) && $_GET['percentage'] == '') {
          unset($_SESSION['percentage']);
        }

        /* if(isset($_GET['alphabet']) || isset($_GET['product']) || isset($_GET['selectsearch']) || isset($_GET['search']) || isset($_GET['searchid']) || isset($_GET['month']) || isset($_GET['percentage'])){
          header('Location: inventory.php');
          echo "<script>window.location.href='inventory.php';</script>";
        } */
    /* SET SESSION END */

    /* RESET ALL SEARCH SESSION START */
      if(isset($_GET['reset']) && $_GET['reset'] == 'all'){
        unset($_SESSION['inventory_alphabet']);
        unset($_SESSION['product']);
        unset($_SESSION['selectsearch']);
        unset($_SESSION['search']);
        unset($_SESSION['searchid']);
        unset($_SESSION['month']);
        unset($_SESSION['percentage']);
      }
    /* RESET ALL SEARCH SESSION END */
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>DigiBooks</title>
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
  <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
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
          <div class="row">
             <!-- Inventory Form ------------------------------------------------------------------------------------------------------>
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Inventory</h4>
                    <hr class="alert-dark">
                    <br>
                
                    <div class="row">
                    
                      <div class="col-12 col-md-10 col-sm-12">
                          <div class="enventory">
                              <a href="#" class="btn btn-dark active">Inventory</a>
                              <a href="#" class="btn btn-dark">Update Inventory </a>
                              <a href="#" class="btn btn-dark">Inventory Setting </a>
                              <a href="product-master.php" class="btn btn-dark">Product Master </a>
                              <a href="inventory-self-consumption.php" class="btn btn-dark">Self Consumption </a>
                          </div>          
                      </div> 
                      
                      <div class="col-12 col-md-2">
                        <button type="button" class="btn btn-grey-1 btn-rounded pull-right"><strong>*HSN Code Reference</strong></button>
                      </div>
                    
                    </div>

                    <hr>
                    
                    
                    <div class="row">
                    
                      <div class="col-md-6">
                        <form method="GET">
                            <div class="form-group row">
                            
                              <div class="col-12 col-md-5">
                                <label>Select anyone</label>
                                  <select class="js-example-basic-single" name="selectsearch" style="width:100%" id="selectsearch"> 
                                      <option value="product" <?php echo (isset($_SESSION['selectsearch']) && $_SESSION['selectsearch'] == 'product') ? 'selected' : ''; ?>>Product Name </option>
                                      <option value="mrp" <?php echo (isset($_SESSION['selectsearch']) && $_SESSION['selectsearch'] == 'mrp') ? 'selected' : ''; ?>>MRP</option>
                                      <option value="generic" <?php echo (isset($_SESSION['selectsearch']) && $_SESSION['selectsearch'] == 'generic') ? 'selected' : ''; ?>>Generic Name</option>
                                  </select>
                              </div>
                              
                              <div class="col-12 col-md-4">
                                  <?php 
                                    if(isset($_SESSION['selectsearch']) && $_SESSION['selectsearch'] == 'product'){
                                      $lbl = 'Enter Product Name';
                                    }elseif(isset($_SESSION['selectsearch']) && $_SESSION['selectsearch'] == 'mrp'){
                                      $lbl = 'Enter MRP';
                                    }elseif (isset($_SESSION['selectsearch']) && $_SESSION['selectsearch'] == 'generic') {
                                      $lbl = 'Enter Generic Name';
                                    }else{
                                      $lbl = 'Enter Product Name';
                                    }
                                  ?>
                                  <label id="search-lable"><?php echo $lbl; ?></label>
                                  <div id="bloodhound">
                                    <input class="form-control" name="search" id="search" type="text" placeholder="Start typing.." value="<?php echo (isset($_SESSION['search'])) ? $_SESSION['search'] : ''; ?>" required>
                                    <input type="hidden" name="searchid" id="searchid" value="<?php echo (isset($_SESSION['searchid'])) ? $_SESSION['searchid'] : ''; ?>">
                                  </div>
                                  <small class="empty-message text-danger"></small>
                              </div>
                              
                              <div class="col-12 col-md-2">
                                <button type="submit" class="btn btn-success mt-30">Search</button>
                              </div>
                            
                            </div>
                        </form>
                      </div>
                      
                      <div class="col-md-6">
                        <div class="row">
                          <div class="col-12">  
                            <label class="">Search by alphabet</label>
                          </div>
                          <div class="col-12">  
                            <a href="?alphabet=a" class="btn <?php echo (isset($_SESSION['inventory_alphabet']) && $_SESSION['inventory_alphabet'] == 'a') ? 'btn-success' : 'btn-primary'; ?> filter-alphabet btn-xs">A</a>
                            <a href="?alphabet=b" class="btn <?php echo (isset($_SESSION['inventory_alphabet']) && $_SESSION['inventory_alphabet'] == 'b') ? 'btn-success' : 'btn-primary'; ?> filter-alphabet btn-xs">B</a>
                            <a href="?alphabet=c" class="btn <?php echo (isset($_SESSION['inventory_alphabet']) && $_SESSION['inventory_alphabet'] == 'c') ? 'btn-success' : 'btn-primary'; ?> filter-alphabet btn-xs">C</a>
                            <a href="?alphabet=d" class="btn <?php echo (isset($_SESSION['inventory_alphabet']) && $_SESSION['inventory_alphabet'] == 'd') ? 'btn-success' : 'btn-primary'; ?> filter-alphabet btn-xs">D</a>
                            <a href="?alphabet=e" class="btn <?php echo (isset($_SESSION['inventory_alphabet']) && $_SESSION['inventory_alphabet'] == 'e') ? 'btn-success' : 'btn-primary'; ?> filter-alphabet btn-xs">E</a>
                            <a href="?alphabet=f" class="btn <?php echo (isset($_SESSION['inventory_alphabet']) && $_SESSION['inventory_alphabet'] == 'f') ? 'btn-success' : 'btn-primary'; ?> filter-alphabet btn-xs">F</a>
                            <a href="?alphabet=g" class="btn <?php echo (isset($_SESSION['inventory_alphabet']) && $_SESSION['inventory_alphabet'] == 'g') ? 'btn-success' : 'btn-primary'; ?> filter-alphabet btn-xs">G</a>
                            <a href="?alphabet=h" class="btn <?php echo (isset($_SESSION['inventory_alphabet']) && $_SESSION['inventory_alphabet'] == 'h') ? 'btn-success' : 'btn-primary'; ?> filter-alphabet btn-xs">H</a>
                            <a href="?alphabet=i" class="btn <?php echo (isset($_SESSION['inventory_alphabet']) && $_SESSION['inventory_alphabet'] == 'i') ? 'btn-success' : 'btn-primary'; ?> filter-alphabet btn-xs">I</a>
                            <a href="?alphabet=j" class="btn <?php echo (isset($_SESSION['inventory_alphabet']) && $_SESSION['inventory_alphabet'] == 'j') ? 'btn-success' : 'btn-primary'; ?> filter-alphabet btn-xs">J</a>
                            <a href="?alphabet=k" class="btn <?php echo (isset($_SESSION['inventory_alphabet']) && $_SESSION['inventory_alphabet'] == 'k') ? 'btn-success' : 'btn-primary'; ?> filter-alphabet btn-xs">K</a>
                            <a href="?alphabet=l" class="btn <?php echo (isset($_SESSION['inventory_alphabet']) && $_SESSION['inventory_alphabet'] == 'l') ? 'btn-success' : 'btn-primary'; ?> filter-alphabet btn-xs">L</a>
                            <a href="?alphabet=m" class="btn <?php echo (isset($_SESSION['inventory_alphabet']) && $_SESSION['inventory_alphabet'] == 'm') ? 'btn-success' : 'btn-primary'; ?> filter-alphabet btn-xs">M</a>
                            <a href="?alphabet=n" class="btn <?php echo (isset($_SESSION['inventory_alphabet']) && $_SESSION['inventory_alphabet'] == 'n') ? 'btn-success' : 'btn-primary'; ?> filter-alphabet btn-xs">N</a>
                            <a href="?alphabet=o" class="btn <?php echo (isset($_SESSION['inventory_alphabet']) && $_SESSION['inventory_alphabet'] == 'o') ? 'btn-success' : 'btn-primary'; ?> filter-alphabet btn-xs">O</a>
                            <a href="?alphabet=p" class="btn <?php echo (isset($_SESSION['inventory_alphabet']) && $_SESSION['inventory_alphabet'] == 'p') ? 'btn-success' : 'btn-primary'; ?> filter-alphabet btn-xs">P</a>
                            <a href="?alphabet=q" class="btn <?php echo (isset($_SESSION['inventory_alphabet']) && $_SESSION['inventory_alphabet'] == 'q') ? 'btn-success' : 'btn-primary'; ?> filter-alphabet btn-xs">Q</a>
                            <a href="?alphabet=r" class="btn <?php echo (isset($_SESSION['inventory_alphabet']) && $_SESSION['inventory_alphabet'] == 'r') ? 'btn-success' : 'btn-primary'; ?> filter-alphabet btn-xs">R</a>
                            <a href="?alphabet=s" class="btn <?php echo (isset($_SESSION['inventory_alphabet']) && $_SESSION['inventory_alphabet'] == 's') ? 'btn-success' : 'btn-primary'; ?> filter-alphabet btn-xs">S</a>
                            <a href="?alphabet=t" class="btn <?php echo (isset($_SESSION['inventory_alphabet']) && $_SESSION['inventory_alphabet'] == 't') ? 'btn-success' : 'btn-primary'; ?> filter-alphabet btn-xs">T</a>
                            <a href="?alphabet=u" class="btn <?php echo (isset($_SESSION['inventory_alphabet']) && $_SESSION['inventory_alphabet'] == 'u') ? 'btn-success' : 'btn-primary'; ?> filter-alphabet btn-xs">U</a>
                            <a href="?alphabet=v" class="btn <?php echo (isset($_SESSION['inventory_alphabet']) && $_SESSION['inventory_alphabet'] == 'v') ? 'btn-success' : 'btn-primary'; ?> filter-alphabet btn-xs">V</a>
                            <a href="?alphabet=w" class="btn <?php echo (isset($_SESSION['inventory_alphabet']) && $_SESSION['inventory_alphabet'] == 'w') ? 'btn-success' : 'btn-primary'; ?> filter-alphabet btn-xs">W</a>
                            <a href="?alphabet=x" class="btn <?php echo (isset($_SESSION['inventory_alphabet']) && $_SESSION['inventory_alphabet'] == 'x') ? 'btn-success' : 'btn-primary'; ?> filter-alphabet btn-xs">X</a>
                            <a href="?alphabet=y" class="btn <?php echo (isset($_SESSION['inventory_alphabet']) && $_SESSION['inventory_alphabet'] == 'y') ? 'btn-success' : 'btn-primary'; ?> filter-alphabet btn-xs">Y</a>
                            <a href="?alphabet=z" class="btn <?php echo (isset($_SESSION['inventory_alphabet']) && $_SESSION['inventory_alphabet'] == 'z') ? 'btn-success' : 'btn-primary'; ?> filter-alphabet btn-xs">Z</a>
                          </div>
                        </div>
                      </div>
                    
                    </div>
                
                  </div>
                </div>
            </div>
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <!-- TABLE Filters btn -->
                    <div class="col mt-4">
                        <?php $product_all = 0; $product_available = 0; $product_expiry = 0 ?>
                        <?php
                          $allQuery = "SELECT id FROM product_master WHERE status = 1";
                          $allRes = mysqli_query($conn, $allQuery);
                          $product_all = ($allRes) ? mysqli_num_rows($allRes) : 0;
                        ?>
                        <a href="?product=all" class="btn btn-sm <?php echo (!isset($_SESSION['product']) || (isset($_SESSION['product']) && $_SESSION['product'] == '') || (isset($_SESSION['product']) && $_SESSION['product'] == 'all')) ? 'btn-success' : 'btn-outline-success'; ?>">All (<?php echo $product_all; ?>)</a>
                        <?php
                          $availableQuery = "SELECT id FROM product_master WHERE status = 1 AND ex_date >= '".date('Y-m-d')."'";
                          $availableRes = mysqli_query($conn, $availableQuery);
                          $product_available = ($availableRes) ? mysqli_num_rows($availableRes) : 0;
                        ?>
                        <a href="?product=available" class="btn btn-sm <?php echo (isset($_SESSION['product']) && $_SESSION['product'] == 'available') ? 'btn-success' : 'btn-outline-success'; ?>">Available (<?php echo $product_available; ?>)</a>

                        <a href="?product=nearexpiry" class="btn btn-sm <?php echo (isset($_SESSION['product']) && $_SESSION['product'] == 'nearexpiry') ? 'btn-success' : 'btn-outline-success'; ?>">Near Expiry (0)</a>

                        <?php
                          $expiryQuery = "SELECT id FROM product_master WHERE status = 1 AND ex_date < '".date('Y-m-d')."'";
                          $expiryRes = mysqli_query($conn, $expiryQuery);
                          $product_expiry = ($expiryRes) ? mysqli_num_rows($expiryRes) : 0;
                        ?> 
                        <a href="?product=expiry" class="btn btn-sm <?php echo (isset($_SESSION['product']) && $_SESSION['product'] == 'expiry') ? 'btn-success' : 'btn-outline-success'; ?>">Expired (<?php echo $product_expiry; ?>)</a>

                        <a href="?product=zerostock" class="btn btn-sm <?php echo (isset($_SESSION['product']) && $_SESSION['product'] == 'zerostock') ? 'btn-success' : 'btn-outline-success'; ?>">Zero Stock (0)</a>   
                        <a href="?product=overstock" class="btn btn-sm <?php echo (isset($_SESSION['product']) && $_SESSION['product'] == 'overstock') ? 'btn-success' : 'btn-outline-success'; ?>">Over Stock (0)</a>
                        <a href="?product=nonmovingstock" class="btn btn-sm <?php echo (isset($_SESSION['product']) && $_SESSION['product'] == 'nonmovingstock') ? 'btn-success' : 'btn-outline-success'; ?>">Non Moving Stock</a>   
                        <a href="?product=reorder" class="btn btn-sm <?php echo (isset($_SESSION['product']) && $_SESSION['product'] == 'reorder') ? 'btn-success' : 'btn-outline-success'; ?>">Reorder</a>
                    </div>
                    <hr>
                    
                    <!-- NON Moving Filter Extra Section -->
                      <!-- Show hide on non-moving filter btn -->
                    <!-- <div class="col">
                        <form method="GET">
                          <div class="form-group row">
                            <div class="col-12 col-md-4">
                              <label>Select anyone</label>
                              <select class="js-example-basic-single" name="month" style="width:100%"> 
                                  <option value="">Please select</option>
                                  <?php 
                                    for ($i=1; $i < 13; $i++) { 
                                  ?>
                                    <option value="<?php echo $i; ?>" <?php echo ($_SESSION['month'] && $_SESSION['month'] == $i) ? 'selected' : ''; ?> ><?php echo $i; ?></option>
                                  <?php } ?>
                              </select>
                            </div>
                            <div class="col-6 col-md-4 mt-4">
                              <button type="submit" class="btn btn-success mt-1">Search</button>
                            </div>
                          </div>
                        </form> 
                    </div> --><!-- End NON Moving Filter Extra Section -->
                    <!-- <hr> -->
                    
                    <!-- OVER STOCK Filter Extra Section -->
                      <!-- Show hide on OVER STOCK  filter btn -->
                    <!-- <div class="col">
                        <form method="GET">
                          <div class="form-group row">
                            <div class="col-12 col-md-4">
                              <label>Sales Percentage wise</label>
                              <select class="js-example-basic-single" name="percentage" style="width:100%"> 
                                  <option value="">Please select</option>
                                  <option value="60" <?php echo ($_SESSION['percentage'] && $_SESSION['percentage'] == 60) ? 'selected' : ''; ?> >60</option>
                                  <option value="75" <?php echo ($_SESSION['percentage'] && $_SESSION['percentage'] == 75) ? 'selected' : ''; ?> >75</option>
                                  <option value="90" <?php echo ($_SESSION['percentage'] && $_SESSION['percentage'] == 90) ? 'selected' : ''; ?> >90</option>
                              </select>
                            </div>
                            <div class="col-6 col-md-4 mt-4">
                              <button type="submit" class="btn btn-success mt-1">Search</button>
                            </div>
                          </div>
                        </form> 
                    </div> --><!-- End OVER STOCK  Filter Extra Section -->
                    <!-- <hr> -->
                    
                    
                    
                    <!-- INVENTORY TABLE STARTS -->
                    <div class="col mt-3">
                      <h4 class="card-title">Common Table</h4>
                      <hr class="alert-dark">
                        <div class="row">
                          <div class="col-12">
                            <table class="table datatable">
                              <thead>
                                <tr>
                                    <th>Sr No</th>
                                    <th>Product</th>
                                    <th>MRP</th>
                                    <th>MFG. Co.</th>
                                    <th>Batch</th>
                                    <th>Expiry</th>
                                    <th>Qty.</th>
                                    <th>Rack No.</th>
                                    <th>Self No.</th>
                                    <th>Box No.</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php
                                    $allProductQry = "SELECT id, product_name, generic_name, mfg_company, batch_no, ex_date, give_mrp, ex_date, rack_no, self_no, box_no, min_qty, max_qty FROM product_master ";
                                    $where = array();
                                      if(isset($_SESSION['searchid']) && $_SESSION['searchid'] != ''){
                                        $where[] = "id = '".$_SESSION['searchid']."'";
                                      }
                                      if(isset($_SESSION['inventory_alphabet']) && $_SESSION['inventory_alphabet'] != ''){
                                        $where[] = "LOWER(product_name) LIKE '".strtolower($_SESSION['inventory_alphabet'])."%'";
                                      }
                                      if(isset($_SESSION['product']) && $_SESSION['product'] == 'available'){
                                        $where[] = "ex_date >= '".date('Y-m-d')."'";
                                      }
                                      if(isset($_SESSION['product']) && $_SESSION['product'] == 'expiry'){
                                        $where[] = "ex_date < '".date('Y-m-d')."'";
                                      }
                                      $where[] = "status = 1";


                                      if(!empty($where)){
                                        $where = implode(" AND ",$where);
                                        $allProductQry .="WHERE ".$where;
                                      }
                                      $allProductQry .=' ORDER BY id DESC';
                                    $allProductRes = mysqli_query($conn, $allProductQry);
                                ?>
                                  <?php if($allProductRes && mysqli_num_rows($allProductRes)){ ?>
                                    <?php
                                        $countproduct = 1;
                                        while ($productRow = mysqli_fetch_array($allProductRes)) { 
                                    ?>
                                      <tr>
                                        <td><?php echo $countproduct; ?></td>
                                        <td><?php echo (isset($productRow['product_name'])) ? $productRow['product_name'] : ''; ?></td>
                                        <td><?php echo (isset($productRow['give_mrp'])) ? $productRow['give_mrp'] : ''; ?></td>
                                        <td><?php echo (isset($productRow['mfg_company'])) ? $productRow['mfg_company'] : ''; ?></td>
                                        <td><?php echo (isset($productRow['batch_no'])) ? $productRow['batch_no'] : ''; ?></td>
                                        <td><?php echo (isset($productRow['ex_date']) && $productRow['ex_date'] != '') ? date('d/m',strtotime($productRow['ex_date'])) : ''; ?></td>
                                        <td><?php echo (isset($productRow['max_qty'])) ? $productRow['max_qty'] : ''; ?></td>
                                        <td><?php echo (isset($productRow['rack_no'])) ? $productRow['rack_no'] : ''; ?></td>
                                        <td><?php echo (isset($productRow['self_no'])) ? $productRow['self_no'] : ''; ?></td>
                                        <td><?php echo (isset($productRow['box_no'])) ? $productRow['box_no'] : ''; ?></td>
                                      </tr>
                                    <?php $countproduct++; } ?>
                                  <?php } ?>
                              </tbody>
                            </table>
                          </div>
                        </div>
                    </div>
                    
                    <!-- <hr> -->
                     <!-- OVER STOCK TABLE STARTS -->
                    <!-- <div class="col mt-3">
                      <h4 class="card-title">Over stock Table</h4>
                      <hr class="alert-dark">
                      <div class="row">
                        <div class="col-12">
                          <table class="table datatable">
                            <thead>
                              <tr>
                                  <th>Sr No</th>
                                  <th>Product</th>
                                  <th>MRP</th>
                                  <th>MFG. Co.</th>
                                  <th>Batch</th>
                                  <th>Expiry</th>
                                  <th>Qty.</th>
                                  <th>Rack No.</th>
                                  <th>Self No.</th>
                                  <th>Box No.</th>
                                  <th>Min Qty.</th>
                                  <th>Reorder</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                  <td>1267</td>
                                  <td>501 SOAP 300gm</td>
                                  <td>94.40</td>
                                  <td>&nbsp;</td>
                                  <td>456</td>
                                  <td>1/21</td>
                                  <td>2</td>
                                  <td>
                                    5
                                  </td>
                                  <td>
                                    3
                                  </td>
                                  <td>
                                    6
                                  </td>
                                  <td>2</td>
                                  <td>2</td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div> -->
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
  <script src="js/custom/inventory.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <!-- End custom js for this page-->

  <!-- script for custom validation -->
<script src="js/parsley.min.js"></script>
<script type="text/javascript">
  $('form').parsley();
</script>
</body>


</html>
