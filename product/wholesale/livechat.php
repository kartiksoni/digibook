<?php include('include/usertypecheck.php');?>
<?php 
  /*---------------GET ALL USER FOR PHARMACY-------------------*/
    $allUsers = [];
    $getAllUserData = [];
    $searchuser = (isset($_SESSION['auth']['user_type']) && $_SESSION['auth']['user_type'] == 'owner') ? $_SESSION['auth']['id'] : $_SESSION['auth']['owner_id'];
    $userQ = "SELECT id, name, user_type, profile_pic, is_online FROM users WHERE (owner_id = '".$searchuser."' OR id = '".$searchuser."') AND id != '".$_SESSION['auth']['id']."' ORDER BY is_online DESC";
    $userR = mysqli_query($webconn, $userQ);
    if($userR && mysqli_num_rows($userR) > 0){
      while ($userRow = mysqli_fetch_assoc($userR)) {
        $userRow['fullname'] = (isset($userRow['name']) && $userRow['name'] != '') ? ucwords(strtolower($userRow['name'])) : '';
        $userRow['name'] = (isset($userRow['name']) && strlen($userRow['name']) <= 15) ? $userRow['name'] : substr($userRow['name'], 0, 15) . '...';
        $userRow['is_group'] = 0;
        $userDP = ((isset($userRow['profile_pic']) && $userRow['profile_pic'] != '') && file_exists('../user_profile/'.$userRow['profile_pic'])) ? '../user_profile/'.$userRow['profile_pic'] : '../user_profile/default.jpg';
        $userRow['profile_pic'] = $userDP;
        $userRow['is_online'] = (isset($userRow['is_online']) && $userRow['is_online'] != '') ? $userRow['is_online'] : 0;
        $allUsers[] = $userRow;
        $getAllUserData[] = $userRow;
      }
    }
  /*---------------GET ALL USER FOR PHARMACY-------------------*/

  /*---------------GET ALL GROUP BY USER-------------------*/
  
  $groupQ = "SELECT gp.id, gp.name FROM groupmembers gpm INNER JOIN groups gp ON gpm.group_id = gp.id WHERE gpm.user_id = '".$_SESSION['auth']['id']."' GROUP BY gpm.group_id ORDER BY gp.name";

    $groupR = mysqli_query($webconn, $groupQ);
    if($groupR && mysqli_num_rows($groupR)){
      while ($groupRow = mysqli_fetch_assoc($groupR)) {
        $groupRow['fullname'] = (isset($groupRow['name']) && $groupRow['name'] != '') ? ucwords(strtolower($groupRow['name'])) : '';
        $groupRow['name'] = (isset($groupRow['name']) && strlen($groupRow['name']) <= 15) ? $groupRow['name'] : substr($groupRow['name'], 0, 15) . '...';
        $groupRow['user_type'] = 'Group';
        $groupRow['profile_pic'] = '../user_profile/groupdefault.jpg';
        $groupRow['is_group'] = 1;
        $userRow['is_online'] = (isset($userRow['is_online']) && $userRow['is_online'] != '') ? $userRow['is_online'] : 0;
        $allUsers[] = $groupRow;
      }
    }
  /*---------------GET ALL GROUP BY USER-------------------*/
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Chat</title>
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
  <link rel="stylesheet" href="css/parsley.css">

  <link href="css/chat.css" rel="stylesheet"><!-- add as a new -->

  <link href="emoji/css/jquery.emojipicker.css" rel="stylesheet">
  <link href="emoji/css/jquery.emojipicker.tw.css" rel="stylesheet">
  <link rel="stylesheet" href="emoji/css/jquery.emojipicker.a.css">
  <link rel="stylesheet" href="emoji/css/jquery.emojipicker.g.css">

  <link rel="stylesheet" href="css/bootstrap-select.min.css">
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
          <span id="chat-error"></span>
          <div class="email-wrapper wrapper">
            <div class="row align-items-stretch">

              <div class="mail-sidebar d-none d-lg-block col-md-2 bg-white">
                <div class="row">
                  <div class="col-12 no-padding">
                    <div class="menu-bar">
                      <div class="wrapper">
                        <div class="col-12">
                          <div class="online-status d-flex justify-content-between align-items-center">
                            <p class="chat"><i class="fa fa-list"></i> Users</p> <!-- <span class="status offline online"></span> -->
                            <div class="dropdown pull-right">
                              <a data-toggle="dropdown">
                                <i class="fa fa-cog"></i>
                              </a>
                              <div class="dropdown-menu">
                                <a class="dropdown-item" id="new-group" href="javascript:void(0);" data-toggle="modal" data-target="#new-group-model">New Group</a>
                              </div>
                            </div>
                          </div>
                          <input type="hidden" id="from" value="<?php echo $_SESSION['auth']['id']; ?>">
                        </div>
                      </div>
                      <ul class="profile-list">
                        <?php if(isset($allUsers) && !empty($allUsers)){ ?>
                          <?php foreach ($allUsers as $key => $value) { ?>

                              <?php 
                                if(isset($_GET['conversation']) && $_GET['conversation'] != ''){
                                  $activeuserOnline = ($_GET['conversation'] == $value['id']) ? 'active-user' : '';
                                }elseif($key == 0){
                                  $activeuserOnline = 'active-user';
                                }else{
                                  $activeuserOnline = '';
                                }
                              ?>

                              <li title="<?php echo (isset($value['fullname']) ) ? $value['fullname'] : ''; ?>" class="profile-list-item <?php echo (isset($activeuserOnline)) ? $activeuserOnline : ''; ?>" data-id="<?php echo $value['id']; ?>" data-group = "<?php echo (isset($value['is_group'])) ? $value['is_group'] : ''; ?>" <?php echo ((isset($value['is_group']) && $value['is_group'] == 0) && (isset($value['id']) && $value['id'] != '')) ? 'id="li-'.$value['id'].'"' : ''; ?> > 
                                <a href="javascript:void(0);"> 
                                  <span class="pro-pic"><img src="<?php echo (isset($value['profile_pic'])) ? $value['profile_pic'] : ''; ?>" alt="Profile Picture"></span>
                                  <div class="user">
                                    <p class="u-name">
                                        <?php echo (isset($value['name']) && $value['name'] != '') ? ucwords(strtolower($value['name'])) : ''; ?>
                                        <span class="badge badge-pill badge-success usr-notification" id="<?php echo (isset($value['is_group']) && $value['is_group'] == 0) ? 'notificationuser' : 'notificationgroup'; ?><?php echo $value['id'];?>"></span>
                                    </p>

                                    <p class="u-designation">
                                      <?php if(isset($value['is_group']) && $value['is_group'] == 0){ ?>
                                        <span class="<?php echo (isset($value['is_online']) && $value['is_online'] == 1) ? 'chat-online' : 'chat-offline'; ?>"><i class="fa fa-circle"></i></span>
                                      <?php } ?>
                                      <?php echo (isset($value['user_type']) && $value['user_type'] != '') ? ucwords(strtolower($value['user_type'])) : ''; ?>
                                    </p>
                                  </div> 
                                </a>
                              </li>

                          <?php } ?>
                        <?php }else{ ?>
                          <li title="Online user empty" class=""> 
                            <small style="margin-left: 10px;">No Users</small>
                          </li>
                        <?php } ?>
                      </ul>
                      
                    </div>
                  </div>
                </div>  
              </div>


              <div class="mail-list-container col-md-10 pt-4 pb-4 border-right bg-white">

                <button type="button" id="clearchat" class="clear-btn" title="Clear Chat"> Clear </button>

                <div style="height: 550px;overflow: auto;" id="chatdiv">
                    
                </div>
                <div class="flip-square-loader mx-auto display-none" id="chat-loader" style="position: absolute;top: 35%;left: 40%;"></div>
                <input type="hidden" id="msglength">
                
                <div class="border-bottom pb-4 mb-3 px-3" style="margin-top: 30px;">
                  <div class="form-group" style="position:relative">

                    <div class="progress progress-sm" style="display: none;">
                      <div class="progress-bar bg-success progress-bar-striped progress-bar-animated " role="progressbar" style="width: 0%" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>

                    <!-- <div id="filename-alert" class="alert alert-dark alert-dismissible fade show display-none" role="alert">
                      <strong id="tmp_file_name">IMG_1251815.jpg</strong> <small class="text-danger" id="file-remove-error"></small>
                      <input type="hidden" name="file_name" id="file_name">
                      <button type="button" class="close" id="remove-file" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                      <i class="fa fa-spin fa-refresh display-none pull-right" id="remove-file-loader" style="position: absolute;right: 0px;"></i>
                    </div> -->

                    
                    <div class="s-message-form">
                      <div class="file-upload-wrap">
                          <label for="attechment" id="attechment-lable" title="Attechment">
                              <!-- attachment icon -->
                              <span class="attachment-wrap">
                                <i class="fa fa-paperclip fa-2x attachment-icon" id="attechment-icon"></i>
                                <i class="fa fa-spin fa-refresh fa-2x attachment-icon" id="attechment-loader-icon" style="display: none;"></i>
                              </span>
                              <!-- Filename -->
                              <span class="file-upload-name"> </span>
                              <input type="hidden" name="file_name" id="file_name">
                              
                              <!-- remove file name icon -->
                              <span class="s-file-upload__close-icon" style="position: relative;top: 3px;display: none;" id="remove-file">
                                <a href="javascript:void(0);" style="color:#333;">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="icon"><path d="M21.51 4.87c.65-.66.65-1.72 0-2.38-.66-.65-1.72-.65-2.38 0-.47.48-2.85 2.85-7.13 7.13-4.28-4.28-6.66-6.65-7.13-7.13-.66-.65-1.72-.65-2.38 0-.65.66-.65 1.72 0 2.38.48.47 2.85 2.85 7.13 7.13l-7.13 7.13c-.65.66-.65 1.72 0 2.38.66.65 1.72.65 2.38 0 .47-.48 2.85-2.86 7.13-7.13 4.28 4.27 6.66 6.65 7.13 7.13.66.65 1.72.65 2.38 0 .65-.66.65-1.72 0-2.38L14.38 12c4.28-4.28 6.65-6.66 7.13-7.13z"></path></svg>
                                </a>
                              </span>
                          </label>
                          <input type="file" name="photo" id="attechment" />
                      </div>
          
                      <div class="textarea-wrap">
                          <textarea class="form-control w-100 chat-textarea" placeholder="Start Typing..." id="msg" style="height: auto;padding-right: 11%;"></textarea>
                          <i class="fa fa-spin fa-refresh" id="msg_send_loader" style="position: absolute;left: 50%;top: 30%;display: none;"></i>
                      </div>
                    
                      <div class="file-send-wrap">
                        <button type="button" class="btn btn-outline-primary btn-rounded btn-send" id="btn-send-msg">Send</button>
                      </div>
                    </div>



                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->
        <!-- partial:../../partials/_footer.html -->

        <?php include "include/footer.php"; ?>

        <?php include "popup/new-group-model.php"; ?>

        <!-- partial -->
      </div>
        <!-- main-panel ends -->
      </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->

  <input type="hidden" id="current_userid" value="<?php echo $_SESSION['auth']['id']; ?>">

  
  

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
  <script src="js/moment.js"></script>
  <script src="emoji/js/jquery.emojipicker.js"></script>
  <script src="emoji/js/jquery.emojis.js"></script>
  
  <!-- Custom js for this page Modal Box-->
  <script src="js/modal-demo.js"></script>

<script type="text/javascript">
    $(document).ready(function(e) {
        $('#chatdiv').animate({scrollTop: $('#chatdiv')[0].scrollHeight}, 0);
        $('#msg').emojiPicker({});
    });
</script>

<script src="js/parsley.min.js"></script>
<script type="text/javascript">
  $('form').parsley();
</script>

<script src="js/custom/onlynumber.js"></script>
<script src="js/custom/livechat.js"></script>
<script src="js/bootstrap-select.min.js"></script>


  <!--    Toast Notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>
  <!-- End custom js for this page-->
</body>


</html>
