<?php $transaction_page = basename($_SERVER['PHP_SELF']); ?>
<div class="col-md-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <?php
                        $settingq = "SELECT * FROM setting_group WHERE pharmacy_id = '".$_SESSION['auth']['pharmacy_id']."'";
                        $settingr = mysqli_query($conn, $settingq);
                        $settingdata = mysqli_fetch_assoc($settingr);
                    ?>
                    <div class="purchase-top-btns">
                    <?php if(isset($settingdata['transaction_setting']) && $settingdata['transaction_setting'] == 0){ ?>
                        <a href="view-cash-payment.php" class="btn <?php echo ((isset($transaction_page)) && ($transaction_page == 'view-cash-payment.php' || $transaction_page == 'cash-payment.php')) ? 'btn-success' : 'btn-dark'; ?>">Cash Payment</a>
                        <a href="view-cash-receipt.php" class="btn <?php echo ((isset($transaction_page)) && ($transaction_page == 'view-cash-receipt.php' || $transaction_page == 'cash-receipt.php')) ? 'btn-success' : 'btn-dark'; ?>">Cash Receipt</a>
                        <a href="view-bank-payment.php" class="btn <?php echo ((isset($transaction_page)) && ($transaction_page == 'view-bank-payment.php' || $transaction_page == 'bank-payment.php')) ? 'btn-success' : 'btn-dark'; ?>">Bank Payment</a>
                        <a href="view-bank-receipt.php" class="btn <?php echo ((isset($transaction_page)) && ($transaction_page == 'view-bank-receipt.php' || $transaction_page == 'bank-receipt.php')) ? 'btn-success' : 'btn-dark'; ?>">Bank Receipt</a>
                    <?php } else if(isset($settingdata['transaction_setting']) && $settingdata['transaction_setting'] == 1){ ?>    
                        <a href="view-cash-transaction.php" class="btn <?php echo ((isset($transaction_page)) && ($transaction_page == 'view-cash-transaction.php' || $transaction_page == 'cash-transaction.php')) ? 'btn-success' : 'btn-dark'; ?>">Cash Transaction</a>
                        <a href="view-bank-transaction.php" class="btn <?php echo ((isset($transaction_page)) && ($transaction_page == 'view-bank-transaction.php' || $transaction_page == 'bank-transaction.php')) ? 'btn-success' : 'btn-dark'; ?>">Bank Transaction</a>
                    <?php } else if(isset($settingdata['transaction_setting']) && $settingdata['transaction_setting'] == 2){ ?>    
                        <a href="view-payment.php" class="btn <?php echo ((isset($transaction_page)) && ($transaction_page == 'view-payment.php' || $transaction_page == 'payment.php')) ? 'btn-success' : 'btn-dark'; ?>">Payment</a>
                        <a href="view-receipt.php" class="btn <?php echo ((isset($transaction_page)) && ($transaction_page == 'view-receipt.php' || $transaction_page == 'receipt.php')) ? 'btn-success' : 'btn-dark'; ?>">Receipt</a>
                    <?php } ?>    
                        <a href="sales-return.php" class="btn btn-dark">Credit Note</a>
                        <a href="purchase-return.php" class="btn btn-dark">Debit Note</a>
                        <a href="view-journal-vouchar.php" class="btn <?php echo ((isset($transaction_page)) && ($transaction_page == 'journal-vouchar.php' || $transaction_page == 'view-journal-vouchar.php')) ? 'btn-success' : 'btn-dark'; ?>">Journal Vouchar</a>
                        <a href="view-bank-transfer.php" class="btn <?php echo ((isset($transaction_page)) && ($transaction_page == 'bank-transfer.php' || $transaction_page == 'view-bank-transfer.php')) ? 'btn-success' : 'btn-dark'; ?>">Bank Transfer</a>
                        
                        <!--<?php if(isset($user_sub_module) && in_array("Cash Management", $user_sub_module) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                            <a href="accounting-cash-management.php" class="btn btn-dark active">Cash Transaction</a>
                        <?php } if(isset($user_sub_module) && in_array("Customer Receipt", $user_sub_module) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                            <a href="accounting-customer-receipt.php" class="btn btn-dark btn-fw">Customer Receipt</a>
                        <?php } if(isset($user_sub_module) && in_array("Cheque", $user_sub_module) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                            <a href="accounting-cheque.php" class="btn btn-dark  btn-fw">Bank Transaction</a>
                        <?php } if(isset($user_sub_module) && in_array("Vendor Payment", $user_sub_module) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                            <a href="accounting-vendor-payments.php" class="btn btn-dark  btn-fw">Vendor Payment</a>
                        <?php } if(isset($user_sub_module) && in_array("Financial Year Settings", $user_sub_module) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                            <a href="financial-year.php" class="btn btn-dark  btn-fw">Financial Year Settings</a>
                        <?php } if(isset($user_sub_module) && in_array("Credit Note / Purchase Note", $user_sub_module) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                            <a href="purchase-return.php" class="btn btn-dark  btn-fw">Credit/Purchase Note</a>
                        <?php } if(isset($user_sub_module) && in_array("Journal Vouchar", $user_sub_module) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                            <a href="journal-vouchar.php" class="btn btn-dark  btn-fw">Journal Vouchar</a>
                        <?php } ?>-->
                    </div>   
                </div> 
            </div>
        </div>
    </div>
</div>