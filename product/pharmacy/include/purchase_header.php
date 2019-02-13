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
                <?php 
                if(isset($user_sub_module) && in_array("Purchase Bill", $user_sub_module) || $_SESSION['auth']['user_type'] == "owner"){ 
                ?>
                <a href="purchase.php" class="btn <?php echo (isset($sale_header_pagename) && $sale_header_pagename == 'purchase.php') ? 'btn-success' : 'btn-dark'; ?>">Purchase Bill</a>
                <?php } 
                if(isset($user_sub_module) && in_array("Purchase Return", $user_sub_module) || $_SESSION['auth']['user_type'] == "owner"){ 
                ?>
                <a href="purchase-return.php" class="btn <?php echo (isset($sale_header_pagename) && $sale_header_pagename == 'purchase-return.php') ? 'btn-success' : 'btn-dark'; ?>">Purchase Return</a>
                <?php }
                if(isset($user_sub_module) && in_array("Purchase Return List", $user_sub_module) || $_SESSION['auth']['user_type'] == "owner"){
                ?>
                <a href="purchase-return-list.php" class="btn <?php echo (isset($sale_header_pagename) && $sale_header_pagename == 'purchase-return-list.php') ? 'btn-success' : 'btn-dark'; ?>">Purchase Return List</a>
                <?php } 
                if(isset($user_sub_module) && in_array("Cancel List", $user_sub_module) || $_SESSION['auth']['user_type'] == "owner"){
                ?>
                <a href="purchase-cancel-list.php" class="btn <?php echo (isset($sale_header_pagename) && $sale_header_pagename == 'purchase-cancel-list.php') ? 'btn-success' : 'btn-dark'; ?> btn-fw">Cancel List</a>
                <?php }
                if(isset($user_sub_module) && in_array("History", $user_sub_module) || $_SESSION['auth']['user_type'] == "owner"){
                ?>
                <a href="purchase-history.php" class="btn <?php echo (isset($sale_header_pagename) && $sale_header_pagename == 'purchase-history.php') ? 'btn-success' : 'btn-dark'; ?> btn-fw">History</a>
                <?php } 
                if(isset($user_sub_module) && in_array("Settings", $user_sub_module) || $_SESSION['auth']['user_type'] == "owner"){
                ?>
                <!--<a href="#" class="btn btn-dark btn-fw">Settings</a>--> 
                <?php } ?>
              </div>   
          </div> 
        </div>
    </div>
  </div>
</div>