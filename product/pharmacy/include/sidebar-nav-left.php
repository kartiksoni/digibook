<nav class="sidebar sidebar-offcanvas" id="sidebar">
	<ul class="nav">
        <li class="nav-item nav-profile">
            <div class="nav-link d-flex">
                <div class="profile-image">
                    <?php if(isset($_SESSION['auth']['profile_pic']) && !empty($_SESSION['auth']['profile_pic']) ){ ?>
                        <img src="http://digibooks.cloud/product/user_profile/<?php echo $_SESSION['auth']['profile_pic']; ?>" alt="image"/>
                    <?php }else{ ?>
                        <img src="images/faces/face29.jpg" alt="image"/>
                    <?php } ?>
                </div>
                <div class="profile-name">
                    <p class="name"><?php echo ucfirst(strtolower($_SESSION['auth']['name'])); ?>(<?php echo ucfirst(strtolower($_SESSION['auth']['user_type'])); ?>)
                        <?php 
                            if(isset($_SESSION['auth']['user_role']) && !empty($_SESSION['auth']['user_role'])){
                                $rightsQry = "SELECT * FROM `user_rights` WHERE id='".$_SESSION['auth']['user_role']."'";
                                $rights = mysqli_query($conn,$rightsQry);
                                $rights_data = mysqli_fetch_assoc($rights);
                                echo "(";
                                echo ucfirst($rights_data['catagory_name']);
                                echo")";
                            }
                        ?>
                    </p>
                    <p class="designation"><?php echo ucfirst(strtolower($_SESSION['auth']['type'])); ?></p>
                </div>
            </div>
        </li>

        <li class="nav-item nav-category">
            <input type="text" class="form-control" name="searchsidebar" id="searchsidebar" placeholder="Search Here.." style="width: 100%;line-height: 2.2em;padding: 5px 15px;border: 1px solid #ececec;">
            <i class="fa fa-spin fa-refresh left-sidebar-loader" style="position: relative;left: 89%;top:-26px;font-size: 15px;display: none;"></i>
        </li>
        <div id="sidebar-menu">
            <li class="nav-item">
                <a class="nav-link" href="index.php">
                    <i class="icon-layout menu-icon"></i>
                    <span class="menu-title">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="livechat.php">
                    <i class="icon-speech-bubble menu-icon"></i>
                    <span class="menu-title">Chat </span>
                </a>
            </li>
            
            <?php if($_SESSION['auth']['user_type'] == "admin"){ ?>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#cat" aria-expanded="false" aria-controls="sales">
                        <i class="icon-layers menu-icon"></i>
                        <span class="menu-title">User Catagory</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="collapse" id="cat">
                        <ul class="nav flex-column sub-menu">
                            <li class="nav-item"> <a class="nav-link" href="add_catagory.php">Add Catagory</a></li>
                            <li class="nav-item"> <a class="nav-link" href="view-user-rights.php">View Catagory</a></li>   
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#user_master" aria-expanded="false" aria-controls="sales">
                        <i class="icon-layers menu-icon"></i>
                        <span class="menu-title">User Master</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="collapse" id="user_master">
                        <ul class="nav flex-column sub-menu">
                            <li class="nav-item"> <a class="nav-link" href="user_role_login.php">Add User</a></li>
                            <li class="nav-item"> <a class="nav-link" href="view-admin-user-rights.php">View User</a></li>   
                        </ul>
                    </div>
                </li>
            <?php } if(isset($user_module) && in_array("Configuration", $user_module) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                <li class="nav-item">
                    <a class="nav-link" href="configuration.php">
                    <i class="icon-layers menu-icon"></i>
                    <span class="menu-title">Configuration </span>
                    </a>
                </li>
            <?php } ?>
            <?php if(isset($user_module) && in_array("SMS Notification", $user_module) || $_SESSION['auth']['user_type'] == "owner"){  ?>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#sms_master" aria-expanded="false" aria-controls="sales">
                        <i class="icon-speech-bubble menu-icon"></i>
                        <span class="menu-title">SMS Notification</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="collapse" id="sms_master">
                        <ul class="nav flex-column sub-menu">
                            <?php if(isset($user_sub_module) && in_array("Group Master", $user_sub_module) || $_SESSION['auth']['user_type'] == "owner"){  ?>
                                <li class="nav-item"> <a class="nav-link" href="sms-group-master.php">Group Master</a></li>
                            <?php } ?>
                            <?php if(isset($user_sub_module) && in_array("Phoneboook", $user_sub_module) || $_SESSION['auth']['user_type'] == "owner"){  ?>
                                <li class="nav-item"> <a class="nav-link" href="sms-phonebook.php">Phoneboook</a></li>
                            <?php } ?>
                            <?php if(isset($user_sub_module) && in_array("Send SMS", $user_sub_module) || $_SESSION['auth']['user_type'] == "owner"){  ?>
                                <li class="nav-item"> <a class="nav-link" href="sms-send.php">Send SMS</a></li>
                            <?php } ?>
                        </ul>
                    </div>
                </li>
            <?php } ?>
            <!--Ownwer Condistion Start-->
            <?php if(isset($user_module) && in_array("Inventory", $user_module) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                <li class="nav-item">
                    <a class="nav-link" href="inventory.php?reset=all">
                    <i class="icon-server menu-icon"></i>
                    <span class="menu-title">Inventory </span>
                    </a>
                </li>
            <?php } if(isset($user_module) && in_array("Purchase", $user_module) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                <li class="nav-item">
                    <a class="nav-link" href="purchase.php">
                    <i class="icon-bag menu-icon"></i>
                    <span class="menu-title">Purchase </span>
                    </a>
                </li>
            <?php } if(isset($user_module) && in_array("Delievery Challan", $user_module) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                <!--<li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#delievery-challan" aria-expanded="false" aria-controls="delievery-challan">
                        <i class="icon-layers menu-icon"></i>
                        <span class="menu-title">Delievery Challan</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="collapse" id="delievery-challan">
                        <ul class="nav flex-column sub-menu">
                            <?php //if(isset($user_sub_module) && in_array("Add challan", $user_sub_module) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                                <li class="nav-item"> <a class="nav-link" href="#">Add challan</a></li>
                            <?php //} if(isset($user_sub_module) && in_array("View challan", $user_sub_module) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                                <li class="nav-item"> <a class="nav-link" href="#">View challan</a></li>
                            <?php //} ?>
                        </ul>
                    </div>
                </li>-->
            <?php }  if(isset($user_module) && in_array("Sell", $user_module) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#sales" aria-expanded="false" aria-controls="sales">
                        <i class="icon-layers menu-icon"></i>
                        <span class="menu-title">Sell</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="collapse" id="sales">
                        <ul class="nav flex-column sub-menu">
                            <?php if(isset($user_sub_module) && in_array("Tax Billing", $user_sub_module) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                                <li class="nav-item"> <a class="nav-link" href="sales-tax-billing.php">Tax Billing</a></li>
                                <!--<li class="nav-item"> <a class="nav-link" href="delivery-challan.php">Delivery Challan</a></li>-->
                                <li class="nav-item"> <a class="nav-link" href="view-sales-tax-billing.php">View Sales Bill</a></li>
                            <?php } ?>
                        </ul>
                    </div>
                </li>
            <?php } if(isset($user_module) && in_array("Transaction", $user_module) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#transaction" aria-expanded="false" aria-controls="sales">
                        <i class="icon-repeat menu-icon"></i>
                        <span class="menu-title">Transaction </span>
                        <i class="menu-arrow"></i>
                    </a>
                    <?php
                        $settingq = "SELECT * FROM setting_group WHERE pharmacy_id = '".$_SESSION['auth']['pharmacy_id']."'";
                        $settingr = mysqli_query($conn, $settingq);
                        $settingdata = mysqli_fetch_assoc($settingr);
                    
                    ?>
                    <div class="collapse" id="transaction">
                        <ul class="nav flex-column sub-menu">
                            <?php if(isset($settingdata['transaction_setting']) && $settingdata['transaction_setting'] == 0){ ?>
                                <li class="nav-item"> <a class="nav-link" href="view-cash-payment.php">Cash Payment</a></li>
                                <li class="nav-item"> <a class="nav-link" href="view-cash-receipt.php">Cash Receipt</a></li>
                                <li class="nav-item"> <a class="nav-link" href="view-bank-payment.php">Bank Payment</a></li>
                                <li class="nav-item"> <a class="nav-link" href="view-bank-receipt.php">Bank Receipt</a></li>
                            <?php } else if(isset($settingdata['transaction_setting']) && $settingdata['transaction_setting'] == 1){ ?>
                                <li class="nav-item"> <a class="nav-link" href="view-cash-transaction.php">Cash Transaction</a></li>
                                <li class="nav-item"> <a class="nav-link" href="view-bank-transaction.php">Bank Transaction</a></li>
                            <?php } else if(isset($settingdata['transaction_setting']) && $settingdata['transaction_setting'] == 2){ ?>    
                                <li class="nav-item"> <a class="nav-link" href="view-payment.php">Payment</a></li>
                                <li class="nav-item"> <a class="nav-link" href="view-receipt.php">Receipt</a></li>
                            <?php } ?>    
                                <li class="nav-item"> <a class="nav-link" href="sales-return.php">Credit Note</a></li>
                                <li class="nav-item"> <a class="nav-link" href="purchase-return.php">Debit Note</a></li>
                                <li class="nav-item"> <a class="nav-link" href="view-journal-vouchar.php">Journal Voucher</a></li>
                                <li class="nav-item"> <a class="nav-link" href="view-bank-transfer.php"> Bank Transfer</a></li>
                                
                            <!--<?php //if(isset($user_sub_module) && in_array("Cash Transaction", $user_sub_module) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                                <li class="nav-item"> <a class="nav-link" href="accounting-cash-management.php">Cash Transaction</a></li>
                            <?php //} if(isset($user_sub_module) && in_array("Customer Receipt", $user_sub_module) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                                <li class="nav-item"> <a class="nav-link" href="accounting-customer-receipt.php">Customer Receipt</a></li>
                            <?php //} if(isset($user_sub_module) && in_array("Bank Transaction", $user_sub_module) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                                <li class="nav-item"> <a class="nav-link" href="accounting-cheque.php">Bank Transaction</a></li>
                            <?php //} if(isset($user_sub_module) && in_array("Vendor Payment", $user_sub_module) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                                <li class="nav-item"> <a class="nav-link" href="accounting-vendor-payments.php">Vendor Payment</a></li>
                            <?php //} if(isset($user_sub_module) && in_array("Financial Year Settings", $user_sub_module) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                                <li class="nav-item"> <a class="nav-link" href="financial-year.php">Financial Year Settings</a></li>
                            <?php //} if(isset($user_sub_module) && in_array("Credit Note / Debit Note", $user_sub_module) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                                <li class="nav-item"> <a class="nav-link" href="purchase-return.php">Credit Note / Debit Note</a></li>
                            <?php //} if(isset($user_sub_module) && in_array("Journal Vouchar", $user_sub_module) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                                <li class="nav-item"> <a class="nav-link" href="journal-vouchar.php">Journal Vouchar</a></li>
                            <?php //} ?>-->
                        </ul>
                    </div>
                </li>
            <?php } if(isset($user_module) && in_array("Leads", $user_module) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                <!--<li class="nav-item">
                    <a class="nav-link" href="#">
                    <i class="icon-briefcase menu-icon"></i>
                    <span class="menu-title">Leads </span>
                    </a>
                </li>-->
            <?php } if(isset($user_module) && in_array("Order Place", $user_module) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                <li class="nav-item">
                    <a class="nav-link" href="order.php">
                    <i class="icon-monitor menu-icon"></i>
                    <span class="menu-title">Order Place </span>
                    </a>
                </li>
            <?php } if(isset($user_module) && in_array("Reports", $user_module) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#reports" aria-expanded="false" aria-controls="sales">
                        <i class="icon-pie-graph menu-icon"></i>
                        <span class="menu-title">Reports </span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="collapse" id="reports">
                        <ul class="nav flex-column sub-menu">
                            <li class="nav-item"> <a class="nav-link" href="cash-ledger.php">Account Menus</a></li>
                            <li class="nav-item"> <a class="nav-link" href="sell-report.php">Sales</a></li>
                            <li class="nav-item"> <a class="nav-link" href="purchas-report.php">Purchase</a></li>
                            <li class="nav-item"> <a class="nav-link" href="creditnote-report.php">Credit Note</a></li>
                            <li class="nav-item"> <a class="nav-link" href="debitnote-report.php">Debit Note</a></li>
                            <li class="nav-item"> <a class="nav-link" href="stock-detail-qty-report.php">Stock Report</a></li>
                            <!--<li class="nav-item"> <a class="nav-link" href="customer-sales-report.php">Customer Sales Report</a></li>
                            <li class="nav-item"> <a class="nav-link" href="expiry-date-report.php">Expiry Date Report</a></li>-->
                            
                            
                            
                            
                            
                            
                            
                            <!--<li class="nav-item"> <a class="nav-link" href="daily-sales-report.php">Daily Sales Reports</a></li>-->
                            <!--<li class="nav-item"> <a class="nav-link" href="profit-loss-account-report.php">Trading / P & L Account Reports</a></li>-->
                            <!--<li class="nav-item"> <a class="nav-link" href="iteam-code-report.php">Item Code Report</a></li>-->
                            <!--<li class="nav-item"> <a class="nav-link" href="3b.php">3B Report</a></li>-->
                            <!--<li class="nav-item"> <a class="nav-link" href="3btax-report.php">3B Tax Report</a></li>-->
                            <!--<li class="nav-item"> <a class="nav-link" href="item-registration-report.php">Item Register</a></li>-->
                            <!--<li class="nav-item"> <a class="nav-link" href="purchase-report.php">Purchase Report</a></li>-->
                            <!--<li class="nav-item"> <a class="nav-link" href="doctor-purchase-report.php">Doctor Purchase Report</a></li>-->
                            <!--<li class="nav-item"> <a class="nav-link" href="balance-sheet-report.php">Balance Sheet Report</a></li>-->
                            <!--<li class="nav-item"> <a class="nav-link" href="capital-account-report.php">Capital Account Report</a></li>-->
                            <!--<li class="nav-item"> <a class="nav-link" href="fixed-assets-report.php">Fixed Assets Report</a></li>-->
                            <!--<li class="nav-item"> <a class="nav-link" href="current-assets-report.php">Current Assets Report</a></li>-->
                            <!--<li class="nav-item"><a class="nav-link" href="transport-report.php">Transport Report</a></li>-->
                            <!--<li class="nav-item"><a class="nav-link" href="expense-report.php">Expense Report</a></li>-->
                            <!--<li class="nav-item"><a class="nav-link" href="gstr3b-report.php">GSTR3B Report</a></li>-->
                            <!--<li class="nav-item"><a class="nav-link" href="investment-report.php">Investment Report</a></li>-->
                            <!--<li class="nav-item"><a class="nav-link" href="gstr1-report.php">GSTR 1 Report</a></li>-->
                            <!--<li class="nav-item"><a class="nav-link" href="tax-liability-report.php">Tax Summary/Liability</a></li>-->
                            <!--<li class="nav-item"><a class="nav-link" href="doctor-commistion.php">Doctor Commission Report</a></li>-->
                            <!--<li class="nav-item"><a class="nav-link" href="daybook-report.php">Daybook Report</a></li>-->
                           <!-- <li class="nav-item"><a class="nav-link" href="journal-voucher-register.php">Journal Voucher Register</a></li>-->
                            <!--<li class="nav-item"> <a class="nav-link" href="purchas-report.php">Purchase</a></li>
                            <li class="nav-item"> <a class="nav-link" href="sell-report.php">Sale Report</a></li>
                            <li class="nav-item"><a class="nav-link" href="creditnote-report.php">Credit Note Report</a></li>
                            <li class="nav-item"><a class="nav-link" href="debitnote-report.php">Debit Note Report</a></li>-->
                            <!--<li class="nav-item"><a class="nav-link" href="ledger-summary-report.php">Ledger Summary Report</a></li>-->
                        </ul>
                    </div>
                </li>
                 <li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#other_report" aria-expanded="false" aria-controls="other_report">
                        <i class="icon-pie-graph menu-icon"></i>
                        <span class="menu-title">Other Report </span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="collapse" id="other_report">
                        <ul class="nav flex-column sub-menu">
                            <li class="nav-item"> <a class="nav-link" href="daily-sales-report.php">Daily Sales Reports</a></li>
                            <li class="nav-item"> <a class="nav-link" href="iteam-code-report.php">Item Code Report</a></li>
                            <li class="nav-item"> <a class="nav-link" href="3b.php">3B Report</a></li>
                            <li class="nav-item"> <a class="nav-link" href="3btax-report.php">3B Tax Report</a></li>
                            <!--<li class="nav-item"> <a class="nav-link" href="item-registration-report.php">Item Register</a></li>-->
                            <li class="nav-item"> <a class="nav-link" href="purchase-report.php">Purchase Report</a></li>
                            <li class="nav-item"> <a class="nav-link" href="capital-account-report.php">Capital Account Report</a></li>
                            <li class="nav-item"> <a class="nav-link" href="fixed-assets-report.php">Fixed Assets Report</a></li>
                            <li class="nav-item"> <a class="nav-link" href="current-assets-report.php">Current Assets Report</a></li>
                            <li class="nav-item"><a class="nav-link" href="transport-report.php">Transport Report</a></li>
                            <li class="nav-item"><a class="nav-link" href="expense-report.php">Expense Report</a></li>
                            <li class="nav-item"><a class="nav-link" href="gstr3b-report.php">GSTR3B Report</a></li>
                            <li class="nav-item"><a class="nav-link" href="investment-report.php">Investment Report</a></li>
                            <li class="nav-item"><a class="nav-link" href="gstr1-report.php">GSTR 1 Report</a></li>
                            <li class="nav-item"><a class="nav-link" href="tax-liability-report.php">Tax Summary/Liability</a></li>
                            <li class="nav-item"><a class="nav-link" href="doctor-commistion.php">Doctor Commission Report</a></li>
                            <!--<li class="nav-item"> <a class="nav-link" href="expiry-date-report.php">Expiry Date Report</a></li>
                            <li class="nav-item"> <a class="nav-link" href="customer-sales-report.php">Customer Sales Report</a></li>-->
                            
                        </ul>
                    </div>
                </li>
            <?php } if(isset($user_module) && in_array("Branch", $user_module) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                <li class="nav-item">
                    <a class="nav-link" href="view-branch.php">
                    <i class="icon-link menu-icon"></i>
                    <span class="menu-title">Branch </span>
                    </a>
                </li>
            <?php } if(isset($user_module) && in_array("Courier-Transport", $user_module) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                <li class="nav-item">
                    <a class="nav-link" href="courier-transport.php">
                    <i class="icon-repeat menu-icon"></i> 
                    <span class="menu-title">Courier-Transport </span>
                    </a>
                </li>
            <?php } if(isset($user_module) && in_array("Ledgers", $user_module) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#ledgers" aria-expanded="false" aria-controls="sales">
                        <i class="icon-file menu-icon"></i>
                        <span class="menu-title">Ledgers</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="collapse" id="ledgers">
                        <ul class="nav flex-column sub-menu">
                            <?php if(isset($user_sub_module) && in_array("Customer Ledger", $user_sub_module) || $_SESSION['auth']['user_type'] == "owner"){  ?>
                                <li class="nav-item"> <a class="nav-link" href="customer-ledger.php">Customer Ledger</a></li>
                            <?php } ?>
                                <li class="nav-item"> <a class="nav-link" href="ihis-customer-ledger.php">Ihis Customer Ledger</a></li>
                            <?php if(isset($user_sub_module) && in_array("Vendor Ledger", $user_sub_module) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                                <li class="nav-item"> <a class="nav-link" href="vendor-ledger.php">Vendor Ledger</a></li>
                            <?php } if(isset($user_sub_module) && in_array("Cash Ledger", $user_sub_module) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                                <li class="nav-item"> <a class="nav-link" href="cash-ledger.php">Cash Ledger</a></li>
                            <?php } if(isset($user_sub_module) && in_array("Bank Ledger", $user_sub_module) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                                <li class="nav-item"> <a class="nav-link" href="bank-ledger.php">Bank Ledger</a></li>
                            <?php } if(isset($user_sub_module) && in_array("Round Of Sales Ledger", $user_sub_module) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                                <li class="nav-item"> <a class="nav-link" href="round-off-sales-ledger.php">Round Of Sales Ledger</a></li>
                            <?php } if(isset($user_sub_module) && in_array("Round Of Purchase Ledger", $user_sub_module) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                                <li class="nav-item"> <a class="nav-link" href="round-off-purchase-ledger.php">Round Of Purchase Ledger</a></li>
                            <?php } if(isset($user_sub_module) && in_array("All Ledger", $user_sub_module) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                             <li class="nav-item"> <a class="nav-link" href="all-group-ledger.php">All Ledger</a></li>
                            <?php } ?>
                        </ul>
                    </div>
                </li>
            <?php } if(isset($user_module) && in_array("Credit Note", $user_module) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#credit_note" aria-expanded="false" aria-controls="sales">
                        <i class="icon-book menu-icon"></i>
                        <span class="menu-title">Credit Note</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="collapse" id="credit_note">
                        <ul class="nav flex-column sub-menu">
                            <?php if(isset($user_sub_module) && in_array("Sales Return", $user_sub_module) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                                <li class="nav-item"> <a class="nav-link" href="sales-return.php">Sales Return</a></li>
                            <?php } ?>
                        </ul>
                    </div>
                </li>
            <?php } if(isset($user_module) && in_array("Debit Note", $user_module)){ ?>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#debit_note" aria-expanded="false" aria-controls="sales">
                        <i class="icon-book menu-icon"></i>
                        <span class="menu-title">Debit Note</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="collapse" id="debit_note">
                        <ul class="nav flex-column sub-menu">
                            <?php if(isset($user_sub_module) && in_array("Purchase Retun", $user_sub_module) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                                <li class="nav-item"> <a class="nav-link" href="purchase-return.php">Purchase Retun</a></li>
                            <?php } ?>
                        </ul>
                    </div>
                </li>
            <?php } if(isset($user_module) && in_array("Help Desk", $user_module) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#help-desk" aria-expanded="false" aria-controls="sales">
                        <i class="icon-help menu-icon"></i>
                        <span class="menu-title">Help Desk</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="collapse" id="help-desk">
                        <ul class="nav flex-column sub-menu">
                            <?php if(isset($user_sub_module) && in_array("Add Help Desk", $user_sub_module) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                                <li class="nav-item"> <a class="nav-link" href="help-desk.php">Add Help Desk</a></li>
                            <?php } if(isset($user_sub_module) && in_array("Product Name Wise", $user_sub_module) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                                <li class="nav-item"> <a class="nav-link" href="#">Product Name Wise</a></li>
                            <?php } if(isset($user_sub_module) && in_array("Vendor wise", $user_sub_module) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                                <li class="nav-item"> <a class="nav-link" href="#">Vendor wise</a></li>
                            <?php } if(isset($user_sub_module) && in_array("Company wise", $user_sub_module) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                                <li class="nav-item"> <a class="nav-link" href="#">Company wise</a></li>
                            <?php } ?>
                        </ul>
                    </div>
                </li>
            <?php } if(isset($user_module) && in_array("Stock Report", $user_module) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                <!--<li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#stockreport" aria-expanded="false" aria-controls="sales">
                        <i class="icon-pie-graph menu-icon"></i>
                        <span class="menu-title">Stock Report</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="collapse" id="stockreport">
                        <ul class="nav flex-column sub-menu">
                            <?php if(isset($user_sub_module) && in_array("Stock Detail Quantity", $user_sub_module) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                                <li class="nav-item"><a class="nav-link" href="stock-detail-qty-report.php">Stock Detail Quantity</a></li>
                            <?php } if(isset($user_sub_module) && in_array("Stock Detail Price", $user_sub_module) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                                <li class="nav-item"><a class="nav-link" href="stock-detail-price-report.php">Stock Detail Price</a></li>
                            <?php } ?>
                        </ul>
                    </div>
                </li>-->
            <?php } if(isset($user_module) && in_array("Hospital Prescription", $user_module) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                <?php if((isset($_SESSION['auth']['pharmacy_user_type'])) && ($_SESSION['auth']['pharmacy_user_type'] == 'ihis' || $_SESSION['auth']['pharmacy_user_type'] == 'eclinic')){ ?>
                    <li class="nav-item">
                        <a class="nav-link" href="hospital-list.php">
                        <i class="icon-link menu-icon"></i>
                        <span class="menu-title">Hospital Prescription</span>
                        </a>
                    </li>
                <?php } ?>
            <?php } if(isset($user_module) && in_array("Remider List", $user_module) || $_SESSION['auth']['user_type'] == "owner"){ ?>
                <li class="nav-item">
                    <a class="nav-link" href="remider-list.php">
                    <i class="icon-briefcase menu-icon"></i>
                    <span class="menu-title">Remider List </span>
                    </a>
                </li>
            <?php } if($_SESSION['auth']['user_type'] == "owner"){ ?>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#sms-config" aria-expanded="false" aria-controls="sales">
                        <i class="fa fa-envelope menu-icon"></i>
                        <span class="menu-title">SMS Setup</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="collapse" id="sms-config">
                        <ul class="nav flex-column sub-menu">
                            <li class="nav-item"> <a class="nav-link" href="sms-config.php">SMS Config</a></li>
                            <li class="nav-item"> <a class="nav-link" href="sms-usage-report.php">SMS Usage Report</a></li>
                        </ul>
                    </div>
                </li>
            <?php } ?>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">
                <i class="icon-unlock menu-icon"></i>
                <span class="menu-title">Logout </span>
                </a>
            </li>
        </div>
    </ul>
</nav>
