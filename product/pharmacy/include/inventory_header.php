<?php
  $sale_header_pagename = basename($_SERVER['PHP_SELF']);
?>
<div class="col-12 col-md-10 col-sm-12">
  <div class="enventory">
    <?php if(isset($user_module) && in_array("Inventory", $user_module) || $_SESSION['auth']['user_type'] == "owner"){ ?>
     <a href="inventory.php?reset=all" class="btn <?php echo (isset($sale_header_pagename) && $sale_header_pagename == 'inventory.php') ? 'btn-success' : 'btn-dark'; ?>">Inventory</a>
    <?php } if(isset($user_sub_module) && in_array("Inventory Adjustment", $user_sub_module) || $_SESSION['auth']['user_type'] == "owner"){ ?>
      <a href="inventory-adjustment.php" class="btn <?php echo (isset($sale_header_pagename) && $sale_header_pagename == 'inventory-adjustment.php') ? 'btn-success' : 'btn-dark'; ?>">Inventory Adjustment</a>
    <?php } if(isset($user_sub_module) && in_array("Update Inventory", $user_sub_module) || $_SESSION['auth']['user_type'] == "owner"){ ?>

      <!--<a href="#" class="btn btn-dark">Update Inventory </a>-->

    <?php } if(isset($user_sub_module) && in_array("Inventory Setting", $user_sub_module) || $_SESSION['auth']['user_type'] == "owner"){ ?>

      <!--<a href="#" class="btn btn-dark">Inventory Setting </a>-->

    <?php } if(isset($user_sub_module) && in_array("Product Master", $user_sub_module) || $_SESSION['auth']['user_type'] == "owner"){ ?>

      <a href="product-master.php" class="btn <?php echo (isset($sale_header_pagename) && $sale_header_pagename == 'product-master.php') ? 'btn-success' : 'btn-dark'; ?>">Product Master </a>

    <?php } if(isset($user_sub_module) && in_array("Self Consumption", $user_sub_module) || $_SESSION['auth']['user_type'] == "owner"){ ?>

      <a href="inventory-self-consumption.php" class="btn <?php echo (isset($sale_header_pagename) && $sale_header_pagename == 'inventory-self-consumption.php') ? 'btn-success' : 'btn-dark'; ?>">Self Consumption </a>

    <?php } ?>
  </div>   
</div>