<link rel="stylesheet" href="css/custom.css">

<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row navbar-success" id="navbar-top" data-pharmacyUserType="<?php echo (isset($_SESSION['auth']['pharmacy_user_type'])) ? $_SESSION['auth']['pharmacy_user_type'] : ''; ?>">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="navbar-brand brand-logo" href="index.php"><img src="images/digi-logo.svg" alt="logo"/></a>
        <a class="navbar-brand brand-logo-mini" href="index.php"><img src="images/digi-small.svg" alt="logo"/></a>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-stretch">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
          <span class="mdi mdi-menu"></span>
        </button>
        <ul class="navbar-nav">
          <li class="nav-item d-none d-lg-block">
            <a class="nav-link">
              <i class="mdi mdi-fullscreen" id="fullscreen-button"></i>
            </a>
          </li>
          
          
              <li style="width: 140px;">
                <select class="js-example-basic-single" style="width:100%" onchange="changeFinancial(this)"> 
                    <option value="">Select Financial</option>
                    <?php
                      
                      $financ_pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
                      $financ_query = 'SELECT * FROM `financial` WHERE pharmacy_id = "'.$financ_pharmacy_id.'" AND status = 1';
                      $financ_result = mysqli_query($conn,$financ_query);
                      if($financ_result && mysqli_num_rows($financ_result) > 0)
                        while ($financ_row = mysqli_fetch_array($financ_result)) {
                          ?>
                          <option <?php if(isset($_SESSION['auth']['financial']) && $_SESSION['auth']['financial'] == $financ_row['id']){echo "selected";} ?> value="<?php echo $financ_row['id']; ?>"><?php echo $financ_row['f_name']; ?></option>
                          <?php
                        }
                    ?>
                </select>
                <script type="text/javascript">
                  function changeFinancial(data){
                    window.location = "change-financial.php?id="+data.value;
                  }
                </script>
              </li>
            
          <!--<li class="nav-item">
            <form class="nav-link form-inline mt-2 mt-md-0 d-none d-lg-flex search">
              <input type="text" class="form-control" placeholder="Search for something...">
            </form>
          </li>-->
        </ul>
        <?php 
        function pharmacyname($id){
          global $conn;

          $pharmacy_id = $id;
          $pharmacyQry = "SELECT * FROM `pharmacy_profile` WHERE id='".$pharmacy_id."'";
          $pharmacy = mysqli_query($conn,$pharmacyQry);
          $pharmacy_data = mysqli_fetch_assoc($pharmacy);
          return $pharmacy_data['pharmacy_name'];
        }
        ?>

        <ul class="navbar-nav navbar-nav-right">
          <?php 
          $topbar_pharmacy = topbar_pharmacy();
          $count = count($topbar_pharmacy);
          if($count == "1"){
              ?>
              <li class="nav-item dropdown d-none d-lg-flex">
                  <span class="nav-link"><?php echo $topbar_pharmacy[0]['name']; ?></span>
              </li>
              <?php
          }else{
              ?>
               <li style="width: 200px;margin-right:10px;">
                <select class="js-example-basic-single" style="width:100%;" onchange="changepharmacy(this)"> 
                    <option value="">Select Pharmacy</option>
                    <?php
                    foreach ($topbar_pharmacy as $key => $value) {
                        ?>
                          <option <?php if(isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] == $value['id']){echo "selected";} ?> value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
                    <?php } ?>      
                    ?>
                </select>
                <script type="text/javascript">
                  function changepharmacy(data){
                    window.location = "change-pharmacy.php?id="+data.value;
                  }
                </script>
              </li>
              <?php
          }
          ?>
          
            <?php $allMessage = getUnreadMessage(); ?>

            <li class="nav-item dropdown">
                <a class="nav-link count-indicator dropdown-toggle" id="messageDropdown" href="javascript:void(0);" data-toggle="dropdown" aria-expanded="false">
                  <i class="mdi mdi-email-outline"></i>
                  <?php if(isset($allMessage['messages']) && $allMessage['messages'] > 0){ ?>
                    <span class="count bg-warning"><?php echo $allMessage['messages']; ?></span>
                  <?php } ?>            
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="messageDropdown">
                    <h6 class="p-3 mb-0"><?php echo (!isset($allMessage['data']) || empty($allMessage['data'])) ? 'Empty' : ''; ?> Messages</h6>

                    <?php if(isset($allMessage['data']) && !empty($allMessage['data'])){ ?>
                        <?php foreach ($allMessage['data'] as $key => $value) { ?>
                            <div class="dropdown-divider"></div>
                                <a class="dropdown-item preview-item" href="livechat.php?conversation=<?php echo (isset($value['send_by'])) ? $value['send_by'] : ''; ?>">
                                    <div class="preview-thumbnail">
                                        <img src="<?php echo (isset($value['url']) && $value['url'] != '') ? $value['url'] : 'javascript:void(0);'; ?>" alt="Profile Picture" class="profile-pic">                </div>
                                        <div class="preview-item-content">
                                        <h6 class="preview-subject ellipsis"><?php echo (isset($value['send_by_name'])) ? ucwords(strtolower($value['send_by_name'])) : ''; ?></h6>
                                        <p class="text-muted"><?php echo (isset($value['count']) && $value['count'] != '') ? $value['count'] : 0; ?> New Message</p>
                                    </div>
                                </a>
                            <?php if($key == 4){break;} ?>
                        <?php } ?>
    
                        <div class="dropdown-divider"></div>
                        <a href="livechat.php"><h6 class="p-3 mb-0 text-center"><?php echo (isset($allMessage['conversation']) && $allMessage['conversation'] != '') ? $allMessage['conversation'] : 0; ?> New Conversation</h6></a>
    
                    <?php } ?>

                </div>
            </li>
            
            <li class="nav-item dropdown">
              <?php 
                  $allnotification = getAllNotification();
                  $total_notification = (isset($allnotification)) ? count($allnotification) : 0;
              ?>
              <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="javascript:void(0);" data-toggle="dropdown">
                <i class="mdi mdi-bell-outline"></i>
                <?php if(isset($total_notification) && $total_notification > 0){ ?>
                  <span class="count bg-danger"><?php echo $total_notification; ?></span>
                <?php } ?>
              </a>
              <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown" style="overflow-y:auto;max-height:496px;">
                  <?php if(isset($allnotification) && !empty($allnotification)){ ?>
                      <h6 class="p-3 mb-0">Notifications</h6>
                      <div class="dropdown-divider"></div>
                        <div id="top-notification-box">
                            <?php foreach ($allnotification as $key => $value) { ?>
                              
                              <a href="<?php echo (isset($value['url']) && $value['url'] != '') ? $value['url'] : 'javascript:void(0);'; ?>" class="dropdown-item preview-item <?php echo (isset($value['class'])) ? $value['class'] : ''; ?>">
                                  <!--<div class="preview-thumbnail">
                                      <div class="preview-icon bg-success">
                                          <i class="mdi mdi-file-powerpoint-box icon-md"></i>                  
                                      </div>
                                  </div>-->
                                  <div class="preview-item-content">
                                      <h6 class="preview-subject">
                                        <?php echo (isset($value['name'])) ? $value['name'] : ''; ?>
                                      </h6>
                                      <p class="text-muted"><!--class = ellipsis-->
                                        <?php echo (isset($value['subname'])) ? $value['subname'] : ''; ?>
                                      </p>
                                  </div>
                              </a>
                              <div class="dropdown-divider"></div>
                              
                            <?php } ?>
                        </div>
                        
                      <a href="all-notification.php" style="text-decoration: none;"><h6 class="p-3 mb-0 text-center">See all notifications</h6></a>    
                  <?php }else{ ?>
                    <h6 class="p-3 mb-0">Notifications</h6>
                    <div class="dropdown-divider"></div>
                    <div id="top-notification-box">
                        <a href="javascript:void(0);" class="dropdown-item preview-item empty-notification">
                            <div class="preview-item-content">
                                <h6 class="preview-subject">empty</h6>
                            </div>
                        </a>
                    </div>
                  <?php } ?>
              </div>
            </li>
            
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle nav-profile" id="profileDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
              <img src="images/faces/face1.jpg" alt="image">
              <span class="d-none d-lg-inline"><?php echo (isset($_SESSION['auth']['name'])) ? $_SESSION['auth']['name'] : 'Unknow'; ?></span>
            </a>
            <div class="dropdown-menu navbar-dropdown w-100" aria-labelledby="profileDropdown">
            <a class="dropdown-item" href="profile.php">
              <i class="mdi mdi-account mr-2 text-success"></i>
              Profile
            </a>
              <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="logout.php"><i class="mdi mdi-logout mr-2 text-primary"></i>Signout</a>
              </div>
          </li>
          
          <li class="nav-item">
            <a class="nav-link" href="general-setting.php">
              <i class="mdi mdi-settings mr-2"></i>
            </a>
          </li>
      <?php include "sidebarright.php"; ?>
      </div>
    </nav>