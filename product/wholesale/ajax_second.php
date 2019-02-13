<?php include('include/config.php');?>
<?php 
  $owner_id = (isset($_SESSION['auth']['owner_id'])) ? $_SESSION['auth']['owner_id'] : '';
  $admin_id = (isset($_SESSION['auth']['admin_id'])) ? $_SESSION['auth']['admin_id'] : '';
  $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
  $financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';
  
    // SEARCH CITY - GAUTAM MAKWANA - 14-12-18
    if($_REQUEST['action'] == "searchCity"){
        $searchquery = (isset($_REQUEST['query'])) ? $_REQUEST['query'] : '';
        $data = [];
            $query = "SELECT ct.id, ct.name, st.state_code_gst as state_code FROM own_cities ct INNER JOIN own_states st ON ct.state_id = st.id WHERE ct.name like '".$searchquery."%' ORDER BY ct.name";
            $res = mysqli_query($conn, $query);
            if($res && mysqli_num_rows($res) > 0){
                while($row = mysqli_fetch_assoc($res)){
                    $data[] = $row;
                }
            }
        if(!empty($data)){
            $result = array('status' => true, 'message' => 'City found success.', 'result' => $data);
        }else{
            $result = array('status' => false, 'message' => 'City not found!', 'result' => '');
        }
        echo json_encode($result);
        exit;
    }
    
    
    if($_REQUEST['action'] == "getCity"){
        $search = (isset($_REQUEST['searchTerm'])) ? $_REQUEST['searchTerm'] : '';
        $data = [];

        if($search != ''){
            $query = "SELECT id, name as text FROM own_cities WHERE name LIKE '".$search."%'";
            $res = mysqli_query($conn, $query);
            if($res && mysqli_num_rows($res) > 0){
                while ($row = mysqli_fetch_assoc($res)) {
                    $data[] = $row;
                }
            }
        }
        echo json_encode($data);exit;
    }
    
    // added by gautam makwana 30-01-2019
    if($_REQUEST['action'] == "getPaymentVoucherNo"){
        $type = (isset($_REQUEST['type'])) ? $_REQUEST['type'] : '';
        if($type == 'cash'){
            // cash payment
            $voucher = getCashTransactionVoucherNo('payment');
            $result = array('status' => true, 'message' => 'Voucher Number Found Success.', 'result' => $voucher);
        }elseif($type == 'bank'){
            // banbk payment
            $voucher = getBankTransactionVoucherNo('payment');
            $result = array('status' => true, 'message' => 'Voucher Number Found Success.', 'result' => $voucher);
        }else{
            $result = array('status' => false, 'message' => 'Voucher Number Not Found! Type is wrong', 'result' => '');
        }
        echo json_encode($result);exit;
    }
    
    // added by gautam makwana 30-01-2019
    if($_REQUEST['action'] == "getReceiptVoucherNo"){
        $type = (isset($_REQUEST['type'])) ? $_REQUEST['type'] : '';
        if($type == 'cash'){
            // cash receipt
            $voucher = getCashTransactionVoucherNo('receipt');
            $result = array('status' => true, 'message' => 'Voucher Number Found Success.', 'result' => $voucher);
        }elseif($type == 'bank'){
            // banbk receipt
            $voucher = getBankTransactionVoucherNo('receipt');
            $result = array('status' => true, 'message' => 'Voucher Number Found Success.', 'result' => $voucher);
        }else{
            $result = array('status' => false, 'message' => 'Voucher Number Not Found! Type is wrong', 'result' => '');
        }
        echo json_encode($result);exit;
    }
    
    // get product by mrp wise //used for vendor purchase report
    // Gautam Makwana 31-01-2019
    if($_REQUEST['action'] == "getProductMrpWise"){
        $mrp = (isset($_REQUEST['mrp'])) ? $_REQUEST['mrp'] : '';
        $data = [];

        $query = "SELECT id, product_name as name, mrp FROM product_master WHERE pharmacy_id = '".$pharmacy_id."'";
        if($mrp != ''){
           $query .= " AND mrp  = '".$mrp."'"; 
        }
        $query .= "GROUP BY product_name ORDER BY product_name";
        $res = mysqli_query($conn, $query);
        if($res && mysqli_num_rows($res) > 0){
            while ($row = mysqli_fetch_assoc($res)) {
                $data[] = $row;
            }
        }

        if(!empty($data)){
            $result = array('status' => true, 'message' => 'Success!', 'result' => $data);
        }else{
            $result = array('status' => false, 'message' => 'Fail!', 'result' => '');
        }
        echo json_encode($result);exit;
    }
    
    // GET ALL BATCH USING PRODUCT NAME // USED FOR ITEM REPORT
    // GAUTAM MAKWANA 02-02-2019
    if($_REQUEST['action'] == "getAllBatchByProductName"){
        $product_name = (isset($_REQUEST['product_name'])) ? $_REQUEST['product_name'] : '';
        $data = [];

        if($product_name != ''){
            $query = "SELECT id, batch_no, product_name FROM product_master WHERE product_name = '".$product_name."' AND pharmacy_id = '".$pharmacy_id."' AND batch_no != '' AND batch_no != '-'  GROUP BY batch_no ORDER BY batch_no";
            $res = mysqli_query($conn, $query);
            if($res && mysqli_num_rows($res) > 0){
                while ($row = mysqli_fetch_assoc($res)) {
                    $data[] = $row;
                }
            }
        }
        if(!empty($data)){
            $result = array('status' => true, 'message' => 'Success!', 'result' => $data);
        }else{
            $result = array('status' => false, 'message' => 'Fail!', 'result' => '');
        }
        echo json_encode($result);exit;
    }
    
    // CHANGE PASSWORD
    // GAUTAM MAKWANA 06-02-18
    if($_REQUEST['action'] == "changepassword"){
        $data = [];
        parse_str($_REQUEST['data'], $data);
        if(!empty($data)){
            $old_password = (isset($data['old_password'])) ? $data['old_password'] : '';
            $new_password = (isset($data['password'])) ? $data['password'] : '';
            $confirm_password = (isset($data['confirm_password'])) ? $data['confirm_password'] : '';
            
            if($new_password == $confirm_password){
                $checkOldQ = "SELECT id FROM users WHERE id = '".$_SESSION['auth']['id']."' AND password = '".trim(md5($old_password))."'";
                $checkOldR = mysqli_query($webconn, $checkOldQ);
                if($checkOldR && mysqli_num_rows($checkOldR) > 0){
                    $updatePassQ = "UPDATE users SET password = '".trim(md5($new_password))."' WHERE id = '".$_SESSION['auth']['id']."'";
                    $updatePassR = mysqli_query($webconn, $updatePassQ);
                    if($updatePassR){
                        $result = array('status' => true, 'message' => 'Password reset successfully.', 'result' => '');
                    }else{
                        $result = array('status' => false, 'message' => 'Password reset fail! Try again.', 'result' => '');
                    }
                }else{
                    $result = array('status' => false, 'message' => 'Old password is wrong! Please enter valid old password.', 'result' => '');
                }
            }else{
                $result = array('status' => false, 'message' => 'Password and confirm password do not match! Try again.', 'result' => '');
            }
        }else{
            $result = array('status' => false, 'message' => 'Somthing want wrong! Try again.', 'result' => '');
        }
        echo json_encode($result);exit;
    }
  
?>
