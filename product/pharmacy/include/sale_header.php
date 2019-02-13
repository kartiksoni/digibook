<?php
  $sale_header_pagename = basename($_SERVER['PHP_SELF']);
?>
<div class="col-md-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <!-- Main Catagory -->
        <div class="row">
          <div class="col-12">
              <div class="purchase-top-btns">
                <?php if(isset($user_sub_module) && in_array("Tax Billing", $user_sub_module) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                    <a href="sales-tax-billing.php" class="btn <?php echo (isset($sale_header_pagename) && $sale_header_pagename == 'sales-tax-billing.php') ? 'btn-success' : 'btn-dark'; ?>">Sales</a>
                <?php } if(isset($user_sub_module) && in_array("View Sales Bill", $user_sub_module) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                    <a href="view-sales-tax-billing.php" class="btn <?php echo (isset($sale_header_pagename) && $sale_header_pagename == 'view-sales-tax-billing.php') ? 'btn-success' : 'btn-dark'; ?>">View Sales Bill</a>
                <?php } if(isset($user_sub_module) && in_array("Sales Return", $user_sub_module) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                  <a href="sales-return.php" class="btn <?php echo (isset($sale_header_pagename) && $sale_header_pagename == 'sales-return.php') ? 'btn-success' : 'btn-dark'; ?>">Sales Return</a>
                <?php } if(isset($user_sub_module) && in_array("Sales Return List", $user_sub_module) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                  <a href="view-sales-return.php" class="btn <?php echo (isset($sale_header_pagename) && $sale_header_pagename == 'view-sales-return.php') ? 'btn-success' : 'btn-dark'; ?>">Sales Return List</a>
                <?php } if(isset($user_sub_module) && in_array("Cancellation List", $user_sub_module) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                  <a href="sales-cancellation-list.php" class="btn <?php echo (isset($sale_header_pagename) && $sale_header_pagename == 'sales-cancellation-list.php') ? 'btn-success' : 'btn-dark'; ?>">Cancellation List</a>
                <?php } if(isset($user_sub_module) && in_array("Order", $user_sub_module) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                  <a href="#" class="btn <?php echo ((isset($sale_header_pagename)) && ($sale_header_pagename == 'sales-order.php' || $sale_header_pagename == 'sales-order-estimate.php' || $sale_header_pagename == 'sales-order-templates.php' || $sale_header_pagename == 'sales-order-history.php')) ? 'btn-success' : 'btn-dark'; ?> dropdown-toggle" id="dropdownMenuButton4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Order</a>
                      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton4">
                        <a class="dropdown-item" href="sales-order.php">Order/Estimate/Templates</a>
                        <a class="dropdown-item" href="sales-order-history.php">History</a>
                      </div>
                <?php } if(isset($user_sub_module) && in_array("Sales History", $user_sub_module) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                    <a href="sales-history.php" class="btn <?php echo (isset($sale_header_pagename) && $sale_header_pagename == 'sales-history.php') ? 'btn-success' : 'btn-dark'; ?>">History</a>
                <?php } ?>
              </div>   
          </div> 
        </div>
    </div>
  </div>
</div>