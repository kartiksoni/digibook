<?php include('include/config.php');?>
<?php 
  $owner_id = (isset($_SESSION['auth']['owner_id'])) ? $_SESSION['auth']['owner_id'] : '';
  $admin_id = (isset($_SESSION['auth']['admin_id'])) ? $_SESSION['auth']['admin_id'] : '';
  $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
  $financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';
  $userid = (isset($_SESSION['auth']['id'])) ? $_SESSION['auth']['id'] : '';
  $created = ('Y-m-d H:i:s');
?>
<?php
	// Added by gautam makwana 09-01-19
	if($_REQUEST['action'] == "addSaleReturn"){
      	if(isset($_REQUEST['data']) && $_REQUEST['data'] != ''){
        	$data = [];
        	parse_str($_REQUEST['data'], $data);

	        if(!empty($data)){
	            $countItem = (isset($data['r_product_id']) && !empty($data['r_product_id'])) ? count($data['r_product_id']) : 0;
	            if($countItem > 0){
	            	$credit_note_no = getCreditNoteNo();
	            	$credit_note_date = (isset($data['credit_note_date']) && $data['credit_note_date'] != '') ? date('Y-m-d',strtotime(str_replace('/','-',$data['credit_note_date']))) : '';
	            	$customer_id = (isset($data['customer_id'])) ? $data['customer_id'] : '';
	            	$city_id = (isset($data['city_id'])) ? $data['city_id'] : '';
	            	$city_id = (isset($data['city_id'])) ? $data['city_id'] : '';
	            	$remarks = (isset($data['remarks'])) ? $data['remarks'] : '';
	            	$finalamount = (isset($data['finalamount']) && $data['finalamount'] != '') ? $data['finalamount'] : 0;

	            	$query = "INSERT INTO sale_return SET financial_id = '".$financial_id."', owner_id = '".$owner_id."', admin_id = '".$admin_id."', pharmacy_id = '".$pharmacy_id."', credit_note_no = '".$credit_note_no."', credit_note_date = '".$credit_note_date."', customer_id = '".$customer_id."', city_id = '".$city_id."', remarks = '".$remarks."', finalamount = '".$finalamount."', created = '".$created."', createdby = '".$userid."'";
	            	$res = mysqli_query($conn, $query);
	            	if($res){
	            		$returnid = mysqli_insert_id($conn);
	            		for ($i=0; $i < $countItem; $i++) {
	            			$return['sale_return_id'] = $returnid;
	            			$return['tax_bill_id'] = (isset($data['r_tax_bill_id'][$i])) ? $data['r_tax_bill_id'][$i] : '';
	            			$return['product_id'] = (isset($data['r_product_id'][$i])) ? $data['r_product_id'][$i] : '';
	            			$return['mrp'] = (isset($data['r_mrp'][$i]) && $data['r_mrp'][$i] != '') ? $data['r_mrp'][$i] : 0;
	            			$return['mfg_co'] = (isset($data['r_mfg_co'][$i])) ? $data['r_mfg_co'][$i] : '';
	            			$return['batch'] = (isset($data['r_batch'][$i])) ? $data['r_batch'][$i] : '';
	            			$return['expiry'] = (isset($data['r_expiry'][$i]) && $data['r_expiry'][$i] != '') ? date('Y-m-d',strtotime(str_replace('/','-',$data['r_expiry'][$i]))) : '';
	            			$return['qty'] = (isset($data['r_qty'][$i]) && $data['r_qty'][$i] != '') ? $data['r_qty'][$i] : 0;
	            			$return['qty_ratio'] = (isset($data['r_qty_ratio'][$i]) && $data['r_qty_ratio'][$i] != '') ? $data['r_qty_ratio'][$i] : 0;
	            			$return['discount'] = (isset($data['r_discount'][$i]) && $data['r_discount'][$i] != '') ? $data['r_discount'][$i] : 0;
	            			$return['rate'] = (isset($data['r_rate'][$i]) && $data['r_rate'][$i] != '') ? $data['r_rate'][$i] : 0;
	            			$return['gst'] = (isset($data['r_gst'][$i]) && $data['r_gst'][$i] != '') ? $data['r_gst'][$i] : 0;
	            			$return['igst'] = (isset($data['r_igst'][$i]) && $data['r_igst'][$i] != '') ? $data['r_igst'][$i] : 0;
	            			$return['cgst'] = (isset($data['r_cgst'][$i]) && $data['r_cgst'][$i] != '') ? $data['r_cgst'][$i] : 0;
	            			$return['sgst'] = (isset($data['r_sgst'][$i]) && $data['r_sgst'][$i] != '') ? $data['r_sgst'][$i] : 0;
	            			$return['gst_tax'] = (isset($data['r_gst_tax'][$i]) && $data['r_gst_tax'][$i] != '') ? $data['r_gst_tax'][$i] : 0;
	            			$return['amount'] = (isset($data['r_amount'][$i]) && $data['r_amount'][$i] != '') ? $data['r_amount'][$i] : 0;
	            			$item = "INSERT INTO sale_return_details SET ";
				            foreach ($return as $k => $v) {
				                $item .= " ".$k." = '".$v."', ";
				            }
				            $item .= "created = '".$created."', createdby = '".$userid."'";
				            mysqli_query($conn, $item);
	            		}
	            		$r_data['id'] = $returnid;
	            		$r_data['total_amount'] = $finalamount;
	            		$result = array('status' => true, 'message' => 'Sale return save successfully.', 'result' => $r_data); 		
	            	}else{
	           			$result = array('status' => false, 'message' => 'Sale return save fail! Try again.', 'result' => ''); 		
	            	}
	            }
	        }else{
	           $result = array('status' => false, 'message' => 'Sale return save fail! Try again.', 'result' => '');   
	        }
      	}else{
        	$result = array('status' => false, 'message' => 'Sale return save fail! Try again.', 'result' => '');
      	}
      	echo json_encode($result);
      	exit;
    }
    
    // added by gautam makwana 10-01-19
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
    
    // added by gautam makwana 10-01-19
    if($_REQUEST['action'] == "getStateByCityId"){
    	$id = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : '';
    	if($id != ''){
    		$query = "SELECT st.state_code_gst as statecode FROM own_cities ct INNER JOIN own_states st ON ct.state_id = st.id WHERE ct.id = '".$id."' LIMIT 1";
    		$res = mysqli_query($conn, $query);
    		if($res && mysqli_num_rows($res) > 0){
    			$data = mysqli_fetch_assoc($res);
    			$statecode = (isset($data['statecode'])) ? $data['statecode'] : '';
    			$result = array('status' => true, 'message' => 'State Found Success', 'result' => $statecode);   	
    		}else{
    			$result = array('status' => false, 'message' => 'State Not Found!', 'result' => '');   	
    		}
    	}else{
    		$result = array('status' => false, 'message' => 'State Not Found!', 'result' => '');   
    	}
    	echo json_encode($result);exit;
    }
    
    // added by gautam makwana 11-01-19
    if($_REQUEST['action'] == "getCustomerVendor"){
    	$search = (isset($_REQUEST['search'])) ? $_REQUEST['search'] : '';
    	if($search != ''){
    		$query = "SELECT id, name, group_id FROM ledger_master WHERE (group_id = 10 || group_id = 14) AND pharmacy_id = '".$pharmacy_id."' AND name LIKE '%".$search."%' ORDER BY name";
    		$res = mysqli_query($conn, $query);
    		if($res && mysqli_num_rows($res) > 0){
    			$data = [];
    			while ($row = mysqli_fetch_assoc($res)) {
    				$data[] = $row;
    			}
    			$result = array('status' => true, 'message' => 'Customer & Vendor Not Found Success.', 'result' => $data);   	
    		}else{
    			$result = array('status' => false, 'message' => 'Customer & Vendor Not Found!', 'result' => '');   	
    		}
    	}else{
    		$result = array('status' => false, 'message' => 'Customer & Vendor Not Found!', 'result' => '');   
    	}
    	echo json_encode($result);exit;
    }
    
    // added by gautam makwana 16-01-2019
    if($_REQUEST['action'] == "getIhisPrescriptionNotification"){
        $data = getIhisPrescriptionNotification();
        if(!empty($data)){
            $result = array('status' => true, 'message' => 'I-HIS Notification Found Success.', 'result' => $data);   	
        }else{
            $result = array('status' => false, 'message' => 'I-HIS Notification Not Found!', 'result' => '');   	
        }
        echo json_encode($result);exit;
    }
    
    // added by gautam makwana 26-01-2019
    if($_REQUEST['action'] == "getEclinicPrescriptionNotification"){
        $data = getEclinicPrescriptionNotification();
        if(!empty($data)){
            $result = array('status' => true, 'message' => 'Eclinic Notification Found Success.', 'result' => $data);   	
        }else{
            $result = array('status' => false, 'message' => 'Eclinic Notification Not Found!', 'result' => '');   	
        }
        echo json_encode($result);exit;
    }
    
    // added by gautam makwana 21-01-2019
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
    
    // added by gautam makwana 21-01-2019
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
    
    // ADDED BY GAUTAM MAKWANA - 22-11-2018
    if($_REQUEST['action'] == "getLastPurchaseRateByProduct"){
        $product_name = (isset($_REQUEST['product_name'])) ? $_REQUEST['product_name'] : '';
        $vendor_id = (isset($_REQUEST['vendor_id'])) ? $_REQUEST['vendor_id'] : '';
      	if($product_name != ''){
            $query = "SELECT pd.id, pd.f_rate as rate, pd.created FROM purchase_details pd INNER JOIN purchase p ON pd.purchase_id = p.id INNER JOIN product_master pm ON pd.product_id = pm.id WHERE p.pharmacy_id = '".$pharmacy_id."' AND p.financial_id = '".$financial_id."' AND p.vendor = '".$vendor_id."' AND pm.product_name = '".$product_name."' ORDER BY pd.created DESC LIMIT 3";
            $res = mysqli_query($conn, $query);
            if($res && mysqli_num_rows($res) > 0){
                $tmpdata = [];
                while($row = mysqli_fetch_assoc($res)){
                    $tmp['id'] = (isset($row['id'])) ? $row['id'] : '';
                    $tmp['rate'] = (isset($row['rate']) && $row['rate'] != '') ? $row['rate'] : 0;
                    $tmp['date'] = (isset($row['created']) && $row['created'] != '') ? date('d M y',strtotime($row['created'])) : '';
                    $tmpdata[] = $tmp;
                }
                $result = array('status' => true, 'message' => 'Success!', 'result' => $tmpdata);
            }else{
                $result = array('status' => false, 'message' => 'Fail!', 'result' => '');
            }
      	}else{
        	$result = array('status' => false, 'message' => 'Fail!', 'result' => '');
      	}
      	echo json_encode($result);
      	exit;
    }
    
    //CITY WITH ALL OPTION
    if($_REQUEST['action'] == "getCityWithAll"){
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

      //all city option add
      $all_arr = array("id"=>"", "text"=>"All");
      array_unshift($data, $all_arr);

    	echo json_encode($data);exit;
    }
   
    //Get Customer Vendor City Wise
    if($_REQUEST['action'] == "getCustomerVendorByCity"){
        
    	$city_id = (isset($_REQUEST['city_id'])) ? $_REQUEST['city_id'] : '';
    	$data = [];
    	
    	//city condition
        $city_cond = "";
    	if(!empty($city_id)){
    	    $city_cond = " AND city = '".$city_id."'";
	    }
	    
	    $query = "SELECT id, name FROM ledger_master WHERE (group_id = 10 || group_id = 14) AND pharmacy_id = '".$pharmacy_id."' $city_cond ORDER BY name";
		$res = mysqli_query($conn, $query);
		if($res && mysqli_num_rows($res) > 0){
			while ($row = mysqli_fetch_assoc($res)) {
				$data[] = $row;
			}
			$result = array('status' => true, 'message' => 'Success!', 'result' => $data);
        }else{
            $result = array('status' => false, 'message' => 'Fail!', 'result' => '');
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

    // Chack gst no
    // GAUTAM MAKWANA 13-02-18
    if($_REQUEST['action'] == "checkgst"){
        
        $gst_no = (isset($_REQUEST['gst_no'])) ? $_REQUEST['gst_no'] : '';
        $gst_no = '';
        if($gst_no != ''){
            exit;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://commonapi.mastersindia.co/commonapis/searchgstin?gstin='.$gst_no);//24AAXCS5451M1ZL
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
                'Content-Type: application/json',
                'Authorization: Bearer 0016ab637e3aa39aa8c689255ddad375c334b621',
                'client_id: mksfqcEASLogkQOpkj'                                                                  
            ));
            $data = curl_exec($ch);
            curl_close($ch);
            if($data){
                $result = json_decode($data, true);
                if(!empty($result)){
                    if(isset($result['error']) && $result['error'] == false){
                        $company = (isset($result['data']['lgnm']) && $result['data']['lgnm'] != '') ? $result['data']['lgnm'] : 'Unknown Company';
                        $status = (isset($result['data']['sts']) && $result['data']['sts'] != '') ? $result['data']['sts'] : 'Deactive';
                        $msg = '<b>Company Name : </b> '.$company.' <br/><b>Status : </b>'.$status;
                        $result = array('status' => true, 'message' => $msg, 'result' => $result);    
                    }else{
                        $msg = (isset($result['data']) && $result['data'] != '') ? $result['data'] : 'Invalid GST No.';
                        $result = array('status' => false, 'message' => $msg, 'result' => '');    
                    }
                }else{
                    $result = array('status' => false, 'message' => 'Somthing Want Wrong! Try Again.', 'result' => '');    
                }
            }else{
                $result = array('status' => false, 'message' => 'Somthing Want Wrong! Try Again.', 'result' => '');
            }
        }else{
            $result = array('status' => false, 'message' => 'Please enter GST No!', 'result' => '');
        }
        echo json_encode($result);exit;
    }
    
?>