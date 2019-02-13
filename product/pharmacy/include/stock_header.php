<?php
  $account_header_pagename = basename($_SERVER['PHP_SELF']);
?>
<div class="col-md-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <!-- Main Catagory -->
        <div class="row">
          <div class="col-12">
              <div class="purchase-top-btns">
                <a href="stock-detail-qty-report.php" class="btn <?php echo (isset($account_header_pagename) && $account_header_pagename == 'stock-detail-qty-report.php') ? 'btn-success' : 'btn-dark'; ?>">Qty Base</a>
                <a href="stock-detail-price-report.php" class="btn <?php echo (isset($account_header_pagename) && $account_header_pagename == 'stock-detail-price-report.php') ? 'btn-success' : 'btn-dark'; ?>">Price Base</a>
                <a href="stock-detail-price-report1.php" class="btn <?php echo (isset($account_header_pagename) && $account_header_pagename == 'stock-detail-price-report1.php') ? 'btn-success' : 'btn-dark'; ?>">Company Wise</a>
                <a href="item-registration-report.php" class="btn <?php echo (isset($account_header_pagename) && $account_header_pagename == 'item-registration-report.php') ? 'btn-success' : 'btn-dark'; ?>">Stock Report</a>
                <a href="#" class="btn <?php echo (isset($account_header_pagename) && $account_header_pagename == 'item-registration-report123.php') ? 'btn-success' : 'btn-dark'; ?>">Item Ledger</a>
                <a href="customer-sales-report.php" class="btn <?php echo (isset($account_header_pagename) && $account_header_pagename == 'customer-sales-report.php') ? 'btn-success' : 'btn-dark'; ?>">Customer Sales Report</a>
                <a href="vendor-purchase-report.php" class="btn <?php echo (isset($account_header_pagename) && $account_header_pagename == 'vendor-purchase-report.php') ? 'btn-success' : 'btn-dark'; ?>">Vendor Purchase Report</a>
                <a href="expiry-date-report.php" class="btn <?php echo (isset($account_header_pagename) && $account_header_pagename == 'expiry-date-report.php') ? 'btn-success' : 'btn-dark'; ?>">Expiry Date Report</a>
                <a href="item-report.php" class="btn <?php echo (isset($account_header_pagename) && $account_header_pagename == 'item-report.php') ? 'btn-success' : 'btn-dark'; ?>">Item Report</a>
                <a href="free-goods-report.php" class="btn <?php echo (isset($account_header_pagename) && $account_header_pagename == 'free-goods-report.php') ? 'btn-success' : 'btn-dark'; ?>">Free Goods Report</a>
              </div>   
          </div> 
        </div>
    </div>
  </div>
</div>