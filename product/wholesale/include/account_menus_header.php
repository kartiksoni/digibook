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
                <a href="cash-ledger.php" class="btn <?php echo (isset($account_header_pagename) && $account_header_pagename == 'cash-ledger.php') ? 'btn-success' : 'btn-dark'; ?>">Cash Book</a>
                <a href="daybook-report.php" class="btn <?php echo (isset($account_header_pagename) && $account_header_pagename == 'daybook-report.php') ? 'btn-success' : 'btn-dark'; ?>">Day Book</a>
                <a href="bank-ledger.php" class="btn <?php echo (isset($account_header_pagename) && $account_header_pagename == 'bank-ledger.php') ? 'btn-success' : 'btn-dark'; ?>">Bank Ledger</a>
                <a href="journal-voucher-register.php" class="btn <?php echo (isset($account_header_pagename) && $account_header_pagename == 'journal-voucher-register.php') ? 'btn-success' : 'btn-dark'; ?>">Journal Register</a>
                <a href="all-group-ledger.php" class="btn <?php echo (isset($account_header_pagename) && $account_header_pagename == 'all-group-ledger.php') ? 'btn-success' : 'btn-dark'; ?>">Ledger</a>
                <a href="ledger-summary-report.php" class="btn <?php echo (isset($account_header_pagename) && $account_header_pagename == 'ledger-summary-report.php') ? 'btn-success' : 'btn-dark'; ?>">Ledger Summary</a>
                <a href="all-group-ledger2.php" class="btn <?php echo (isset($account_header_pagename) && $account_header_pagename == 'all-group-ledger2.php') ? 'btn-success' : 'btn-dark'; ?>">Trial Balance</a>
                <a href="profit-loss-account-report.php" class="btn <?php echo (isset($account_header_pagename) && $account_header_pagename == 'profit-loss-account-report.php') ? 'btn-success' : 'btn-dark'; ?>">Trading / P&L A/c.</a>
                <a href="balance-sheet-report.php" class="btn <?php echo (isset($account_header_pagename) && $account_header_pagename == 'balance-sheet-report.php') ? 'btn-success' : 'btn-dark'; ?>">Balance sheet</a>
              </div>   
          </div> 
        </div>
    </div>
  </div>
</div>