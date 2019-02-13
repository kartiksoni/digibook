<?php
  $sale_header_pagename = basename($_SERVER['PHP_SELF']);
?>
<div class="col-md-12 grid-margin stretch-card">
    <div class="card">
    <div class="card-body">
      <h4 class="card-title">Settings</h4>
      <ul class="nav nav-tabs tab-solid  tab-solid-primary" role="tablist">
        <li class="nav-item">
          <a class="nav-link setting <?php echo (isset($sale_header_pagename) && $sale_header_pagename == 'general-setting.php') ? 'active' : ''; ?>" id="setting" data-toggle="tab" href="javascript:void(0);" data-href="general-setting.php" role="tab" aria-controls="home-6-1" aria-selected="true">General</a>
        </li>
        <li class="nav-item">
          <a class="nav-link setting <?php echo (isset($sale_header_pagename) && $sale_header_pagename == 'transaction-setting.php') ? 'active' : ''; ?>" id="setting1" data-toggle="tab" href="javascript:void(0);" data-href="transaction-setting.php" role="tab" aria-controls="profile-6-2" aria-selected="false">Transaction</a>
        </li>
        
        <li class="nav-item">
          <a class="nav-link setting <?php echo (isset($sale_header_pagename) && $sale_header_pagename == 'sell-setting.php') ? 'active' : ''; ?>" id="sell-setting" data-toggle="tab" href="javascript:void(0);" data-href="sell-setting.php" role="tab" aria-controls="profile-6-2" aria-selected="false">Sell</a>
        </li>
      </ul>
    </div>
  </div>
</div> 

