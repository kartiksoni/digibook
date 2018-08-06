<?php include('include/config.php');?>
<?php 
    /// kartik ///
    if($_REQUEST['action'] == "ownergetStateDetails"){
      $id = $_REQUEST['id'];
      $query = 'SELECT * FROM own_states WHERE country_id = '.$id.' AND status=1 order by name ';
      $result = mysqli_query($conn,$query);
      if(mysqli_num_rows($result) > 0){
        $html ='<option value="">Select State</option>';
            while($row = mysqli_fetch_array($result))
            {
              if($row['name'] == 'Gujarat'){
                $selected = "selected='selected'";
              }else{
                $selected = '';
              }
              $html .= '<option value="'.$row['id'].'" '.$selected.'>'.$row['name'].'</option>';
            }
            echo json_encode(array('error'=>0,'message'=>'','html'=>$html));exit;
      } else {
            echo json_encode(array('error'=>0,'message'=>'','html'=>"<option>No State Found</option>"));
            exit;
      }
    }
    
    
if($_REQUEST['action'] == "getproduct"){
  $getproduct = array();
  $query = "SELECT * FROM `product_master` WHERE product_name LIKE '%".$_REQUEST['query']['term']."%'";
  
  $result = mysqli_query($conn,$query);
  $num = mysqli_num_rows($result);

  while($row = mysqli_fetch_array($result)){
    if(empty($row['igst'])){
      $igst = "0";
    }else{
      $igst = $row['igst'];
    }

    if(empty($row['cgst'])){
      $cgst = "0";
    }else{
      $cgst = $row['cgst'];
    }

    if(empty($row['sgst'])){
      $sgst = "0";
    }else{
      $sgst = $row['sgst'];
    }
    $getproduct[] = array(
      'id' => $row['id'],
      'name' => $row['product_name'],
      'ratio' => $row['ratio'],
      'igst'=> $igst,
      'cgst' => $cgst,
      'sgst' => $sgst
    );
  }
  echo json_encode($getproduct);
  exit;
}

 if($_REQUEST['action'] == "getproduct_self"){
      $getproduct_self = array();
      $query = "SELECT * FROM `product_master` WHERE product_name LIKE '%".$_REQUEST['query']['term']."%'";
      
      $result = mysqli_query($conn,$query);
      $num = mysqli_num_rows($result);

      while($row = mysqli_fetch_array($result)){
        $query1 = "SELECT * from (SELECT expiry,mrp,SUM(f_cgst+f_cgst)as gst,SUM(qty*qty_ratio+free_qty)as total_qty,batch,product_id,id FROM `purchase_details` GROUP BY batch) as t WHERE t.product_id='".$row['id']."'";
        $result1 = mysqli_query($conn,$query1);
          while($row1 = mysqli_fetch_array($result1)){
            $count_per = $row1['mrp'] / $row['ratio'];
            $getproduct_self[] = array(
              'id' => $row['id'],
              'name' => $row['product_name'].'-'.$row1['batch'],
              'purchase_id' => $row1['id'],
              'batch' => $row1['batch'],
              'expiry' => $row1['expiry'],
              'total_qty' => $row1['total_qty'],
              'unit' => $row['unit'],
              'gst' => $row['igst'],
              'count_per' => $count_per
            );
          }
       /* $getproduct_self[] =array(
          'id' => $row['id'],
          'name' => $row['product_name'].'-'.$row['batch_no'],
          'batch' => $row['batch_no']
        );*/
      }
      echo json_encode($getproduct_self);
      exit;
  }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    //Add By : Gautam Makwana///

    if($_REQUEST['action'] == "getCountryByState"){
      $country_id = (isset($_REQUEST['country_id'])) ? $_REQUEST['country_id'] : '';
    
      if($country_id != ''){
        $query = 'SELECT id, name FROM own_states WHERE country_id = '.$country_id.' AND status=1 order by name';
        $result = mysqli_query($conn,$query);
        if($result && mysqli_num_rows($result) > 0){
          $res = [];
            while ($row = mysqli_fetch_array($result)) {
              $arr['id'] = $row['id']; 
              $arr['name'] = $row['name'];
              array_push($res, $arr);
            }
          if(!empty($res)){
            $result = array('status' => true, 'message' => 'Success!', 'result' => $res);
          }else{
            $result = array('status' => false, 'message' => 'Fail!', 'result' => '');
          }
        }else{
          $result = array('status' => false, 'message' => 'Fail!', 'result' => '');
        }
      }else{
        $result = array('status' => false, 'message' => 'Fail!', 'result' => '');
      }
      echo json_encode($result);
      exit;
    }

    if($_REQUEST['action'] == "getStateByCity"){
      $state_id = (isset($_REQUEST['state_id'])) ? $_REQUEST['state_id'] : '';
    
      if($state_id != ''){
        $query = 'SELECT id, name FROM own_cities WHERE state_id = '.$state_id.' AND status=1 order by name';
        $result = mysqli_query($conn,$query);
        if($result && mysqli_num_rows($result) > 0){
          $res = [];
            while ($row = mysqli_fetch_array($result)) {
              $arr['id'] = $row['id']; 
              $arr['name'] = $row['name'];
              array_push($res, $arr);
            }
          if(!empty($res)){
            $result = array('status' => true, 'message' => 'Success!', 'result' => $res);
          }else{
            $result = array('status' => false, 'message' => 'Fail!', 'result' => '');
          }
        }else{
          $result = array('status' => false, 'message' => 'Fail!', 'result' => '');
        }
      }else{
        $result = array('status' => false, 'message' => 'Fail!', 'result' => '');
      }
      echo json_encode($result);
      exit;
    }
    
    // get all cities for vendor
    if($_REQUEST['action'] == "getAllVendorCity"){
      $query = "SELECT lgr.city,ct.id as cityid,ct.name as cityname FROM  `ledger_master` lgr INNER JOIN `own_cities` ct ON lgr.city = ct.id where lgr.group_id = '14' order by ct.name ASC";
      $res = mysqli_query($conn, $query);
      if($res && mysqli_num_rows($res) > 0){
        $data = [];
          while ($row = mysqli_fetch_array($res)) {
            $arr['id'] = $row['cityid'];
            $arr['name'] = $row['cityname'];
            array_push($data, $arr);
          }
        $result = array('status' => true, 'message' => 'Success!', 'result' => $data);
      }else{
        $result = array('status' => false, 'message' => 'Fail!', 'result' => '');
      }
      echo json_encode($result);
      exit;
    }
    
    if($_REQUEST['action'] == "getCityByVendor"){
      $city_id = (isset($_REQUEST['city_id'])) ? $_REQUEST['city_id'] : '';
      if($city_id != ''){
        $query = 'SELECT id, name FROM ledger_master WHERE city = '.$city_id.' AND status=1 AND group_id=14 order by name';
        $result = mysqli_query($conn,$query);
        if($result && mysqli_num_rows($result) > 0){
          $res = [];
            while ($row = mysqli_fetch_array($result)) {
              $arr['id'] = $row['id']; 
              $arr['name'] = $row['name'];
              array_push($res, $arr);
            }
          if(!empty($res)){
            $result = array('status' => true, 'message' => 'Success!', 'result' => $res);
          }else{
            $result = array('status' => false, 'message' => 'Fail!', 'result' => '');
          }
        }else{
          $result = array('status' => false, 'message' => 'Fail!', 'result' => '');
        }
      }else{
        $result = array('status' => false, 'message' => 'Fail!', 'result' => '');
      }
      echo json_encode($result);
      exit;
    }

    if($_REQUEST['action'] == "updatestatus"){
      $table = (isset($_REQUEST['table'])) ? $_REQUEST['table'] : '';
      $status = (isset($_REQUEST['status'])) ? $_REQUEST['status'] : '';
      $id = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : '';
    
      if($table != '' && $status != '' && $id != ''){
        $qry = "UPDATE ".$table." SET status = '".$status."' WHERE id = '".$id."'";
    
        $res = mysqli_query($conn, $qry);
        if($res){
          if($status == 0){
            $result = array('status' => true, 'message' => 'Status Deactive Success!', 'result' => '');
          }else{
            $result = array('status' => true, 'message' => 'Status Active success!', 'result' => '');
          }
        }else{
          $result = array('status' => false, 'message' => 'Status Update Fail! Try Again.', 'result' => '');
        }
      }else{
        $result = array('status' => false, 'message' => 'Somthing Want Wrong! Try Again.', 'result' => '');
      }
      echo json_encode($result);
      exit;
    }
    
    if($_REQUEST['action'] == "addvendor"){
      
      if(isset($_REQUEST['data']) && !empty($_REQUEST['data'])){
        
        $query = "INSERT INTO ledger_master SET";
        foreach ($_REQUEST['data'] as $key => $value) {
          $query .= " ".$value['name']." = '".$value['value']."', ";
        }
        $query .= "group_id = '14', account_type = '1', status = '1',created = '".date('Y-m-d H:i:s')."', createdby = '".$_SESSION['auth']['id']."'";
        $result = mysqli_query($conn, $query);
        if($result){
          $result = array('status' => true, 'message' => 'Vendor Added Success.', 'result' => '');
        }else{
          $result = array('status' => false, 'message' => 'Vendor Added Fail! Try Again.', 'result' => '');
        }
      }else{
        $result = array('status' => false, 'message' => 'Field is required!', 'result' => '');
      }
      echo json_encode($result);
      exit;
    }
    
    if($_REQUEST['action'] == "addproduct"){
      if(isset($_REQUEST['data']) && !empty($_REQUEST['data'])){
        $data = [];
        parse_str($_REQUEST['data'], $data);
        if(!empty($data)){
          $res['user_id'] = $_SESSION['auth']['id'];
          $res['product_name'] = (isset($data['product_name'])) ? $data['product_name'] : '';
          $res['generic_name'] = (isset($data['generic_name'])) ? $data['generic_name'] : '';
          $res['mfg_company'] = (isset($data['mfg_company'])) ? $data['mfg_company'] : '';
          $res['schedule_cat'] = (isset($data['schedule_cat'])) ? $data['schedule_cat'] : '';
          $res['product_type'] = (isset($data['product_type'])) ? $data['product_type'] : NULL;
          $res['product_cat'] = (isset($data['product_cat'])) ? $data['product_cat'] : NULL;
          $res['sub_cat'] = (isset($data['sub_cat'])) ? $data['sub_cat'] : '';
          $res['hsn_code'] = (isset($data['hsn_code'])) ? $data['hsn_code'] : '';
          $res['batch_no'] = (isset($data['batch_no'])) ? $data['batch_no'] : '';
          $res['ex_date'] = (isset($data['ex_date']) && $data['ex_date'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$data['ex_date']))) : NULL;
          $res['opening_qty'] = (isset($data['opening_qty']) && $data['opening_qty'] != '') ? $data['opening_qty'] : 0;
          $res['opening_qty_godown'] = (isset($data['opening_qty_godown']) && $data['opening_qty_godown'] != '') ? $data['opening_qty_godown'] : 0;
          $res['give_mrp'] = (isset($data['give_mrp']) && $data['give_mrp'] != '') ? $data['give_mrp'] : 0;
          $res['ex_duty'] = (isset($data['ex_duty']) && $data['ex_duty'] != '') ? $data['ex_duty'] : NULL;
          $res['igst'] = (isset($data['igst']) && $data['igst'] != '') ? $data['igst'] : 0;
          $res['cgst'] = (isset($data['cgst']) && $data['cgst'] != '') ? $data['cgst'] : 0;
          $res['sgst'] = (isset($data['sgst']) && $data['sgst'] != '') ? $data['sgst'] : 0;
          $res['inward_rate'] = (isset($data['inward_rate']) && $data['inward_rate'] != '') ? $data['inward_rate'] : 0;
          $res['distributor_rate'] = (isset($data['distributor_rate']) && $data['distributor_rate'] != '') ? $data['distributor_rate'] : 0;
          $res['sale_rate_local'] = (isset($data['sale_rate_local']) && $data['sale_rate_local'] != '') ? $data['sale_rate_local'] : 0;
          $res['sale_rate_out'] = (isset($data['sale_rate_out']) && $data['sale_rate_out'] != '') ? $data['sale_rate_out'] : 0;
          $res['rack_no'] = (isset($data['rack_no']) && $data['rack_no'] != '') ? $data['rack_no'] : NULL;
          $res['self_no'] = (isset($data['self_no']) && $data['self_no'] != '') ? $data['self_no'] : NULL;
          $res['box_no'] = (isset($data['box_no']) && $data['box_no'] != '') ? $data['box_no'] : NULL;
          $res['company_code'] = (isset($data['company_code']) && $data['company_code'] != '') ? $data['company_code'] : '';
          $res['opening_stock'] = (isset($data['opening_stock']) && $data['opening_stock'] != '') ? $data['opening_stock'] : 0;
          $res['unit'] = (isset($data['unit']) && $data['unit'] != '') ? $data['unit'] : 0;
          $res['min_qty'] = (isset($data['min_qty']) && $data['min_qty'] != '') ? $data['min_qty'] : 0;
          $res['max_qty'] = (isset($data['max_qty']) && $data['max_qty'] != '') ? $data['max_qty'] : 0;
          $res['ratio'] = (isset($data['ratio']) && $data['ratio'] != '') ? $data['ratio'] : 0;
          $res['status'] = 1;

          $query = "INSERT INTO product_master SET";
          foreach ($res as $key => $value) {
            $query .= " ".$key." = '".$value."', ";
          }
          $query .= "created_at = '".date('Y-m-d H:i:s')."', created_by = '".$_SESSION['auth']['id']."'";

          $result = mysqli_query($conn, $query);
          if($result){
            $result = array('status' => true, 'message' => 'Product Added Success.', 'result' => '');
          }else{
            $result = array('status' => false, 'message' => 'Product Added Fail! Try Again.', 'result' => '');
          }

        }else{
          $result = array('status' => false, 'message' => 'Field is required!', 'result' => '');
        }
      }else{
        $result = array('status' => false, 'message' => 'Field is required!', 'result' => '');
      }
      echo json_encode($result);
      exit;
    }
    
    if($_REQUEST['action'] == "getStatecodeByVendor"){
      $vendorid = (isset($_REQUEST['vendor_id']) && $_REQUEST['vendor_id'] != '') ? $_REQUEST['vendor_id'] : '';

      if($vendorid != ''){
        $query = "SELECT st.id, st.name,st.state_code_gst, lgr.name as vendor_name FROM ledger_master lgr INNER JOIN own_states st ON lgr.state = st.id WHERE lgr.id = '".$vendorid."'";
        $res = mysqli_query($conn, $query);
        if($res){
          $row = mysqli_fetch_array($res);
          $arr['statename'] = (isset($row['name'])) ? $row['name'] : '';
          $arr['statecode'] = (isset($row['state_code_gst'])) ? $row['state_code_gst'] : '';
          $arr['vendor_name'] = (isset($row['vendor_name'])) ? $row['vendor_name'] : '';
          $result = array('status' => true, 'message' => 'Success!', 'result' => $arr);
        }else{
          $result = array('status' => false, 'message' => 'Fail!', 'result' => '');
        }
      }else{
        $result = array('status' => false, 'message' => 'Fail!', 'result' => '');
      }
      echo json_encode($result);
      exit;
    }
    
    if($_REQUEST['action'] == "getVoucherNoByType"){
      $purchase_type = (isset($_REQUEST['purchase_type'])) ? $_REQUEST['purchase_type'] : '';
      if($purchase_type != ''){
        $query = "SELECT voucher_no FROM purchase WHERE purchase_type = '".$purchase_type."' ORDER BY id DESC LIMIT 1";
        $res = mysqli_query($conn, $query);
        if($res){
          $voucher_no = '';
          $code = ($purchase_type == 'Cash') ? 'CV-' : 'DV-';
          $count = mysqli_num_rows($res);
          if($count !== '' && $count !== 0){
            $row = mysqli_fetch_array($res);
            $voucherno = (isset($row['voucher_no'])) ? $row['voucher_no'] : '';

            if($voucherno != ''){
              $vouchernoarr = explode('-',$voucherno);
              $voucherno = $vouchernoarr[1];
              $voucherno = $voucherno + 1;
              $voucherno = sprintf("%05d", $voucherno);
              $voucher_no = $code.''.$voucherno;
            }else{
              $voucherno = sprintf("%05d", 1);
              $voucher_no = $code.''.$voucherno;
            }

          }else{
            $voucherno = sprintf("%05d", 1);
            $voucher_no = $code.''.$voucherno;
          }
          $result = array('status' => true, 'message' => 'Success!', 'result' => $voucher_no);
        }else{
          $result = array('status' => false, 'message' => 'Fail!', 'result' => '');
        }
      }else{
        $result = array('status' => false, 'message' => 'Fail!', 'result' => '');
      }
      echo json_encode($result);
      exit;
    }
    
    
    if($_REQUEST['action'] == "getProductMrpGeneric"){
      $searchquery = (isset($_REQUEST['query']['term'])) ? $_REQUEST['query']['term'] : '';
      $type = (isset($_REQUEST['type'])) ? $_REQUEST['type'] : '';

      if($searchquery != '' && $type != ''){
        $query = "SELECT id,product_name, generic_name, mfg_company, give_mrp, igst, cgst, sgst, unit FROM product_master ";
        if($type == 'product'){
          $query .="WHERE product_name like '%".$searchquery."%' AND product_name IS NOT NULL";
        }elseif($type == 'mrp'){
          $query .="WHERE give_mrp like '%".$searchquery."%' AND give_mrp IS NOT NULL";
        }else{
          $query .="WHERE generic_name like '%".$searchquery."%' AND generic_name IS NOT NULL";
        }
        $res = mysqli_query($conn, $query);

        if($res && mysqli_num_rows($res) > 0){
          $finalres = [];
            while ($row = mysqli_fetch_array($res)) {
              $arr['id'] = $row['id'];
              $arr['productname'] = $row['product_name'];
              $arr['generic_name'] = $row['generic_name'];
              $arr['menufacturer_name'] = $row['mfg_company'];
              $arr['igst'] = ($row['igst'] != '') ? $row['igst'] : 0;
              $arr['cgst'] = ($row['cgst'] != '') ? $row['cgst'] : 0;
              $arr['sgst'] = ($row['sgst'] != '') ? $row['sgst'] : 0;
              $arr['unit'] = ($row['unit'] != '') ? $row['unit'] : 0;
              $arr['mrp'] = ($row['give_mrp'] != '') ? $row['give_mrp'] : 0;
              if($type == 'product'){
                $arr['name'] = $row['product_name'];
              }elseif ($type == 'mrp') {
                $arr['name'] = $row['give_mrp'];
              }else{
                $arr['name'] = $row['generic_name'];
              }
              array_push($finalres, $arr);
            }
          $result = array('status' => true, 'message' => 'Success!', 'result' => $finalres);
        }else{
          $result = array('status' => false, 'message' => 'Fail!', 'result' => '');
        }
      }else{
        $result = array('status' => false, 'message' => 'Fail!', 'result' => '');
      }

      echo json_encode($result);
      exit;
    }
    
    // 08-04-2018 - GAUTAM MAKWANA
    if($_REQUEST['action'] == "addByVendor"){
      $data = [];
      parse_str($_REQUEST['data'], $data);

      if(!empty($data)){
        $length = count($data['vendor_id']);
        if($length > 0){
          for ($i=0; $i < $length; $i++) { 
            $query = "INSERT INTO byvendor SET vendor_id = '".$data['vendor_id'][$i]."', product_id = '".$data['product_id'][$i]."', purchase_price = '".$data['purchase_price'][$i]."', gst = '".$data['gst'][$i]."', unit = '".$data['unit'][$i]."', qty = '".$data['qty'][$i]."', created = '".date('Y-m-d H:i:s')."', createdby = '".$_SESSION['auth']['id']."'";
            $res = mysqli_query($conn, $query);
          }
          $result = array('status' => true, 'message' => 'Data Save successfully.', 'result' => '');
        }else{
          $result = array('status' => false, 'message' => 'Data Save Failed! Try Again.', 'result' => '');
        }
      }else{
        $result = array('status' => false, 'message' => 'Data Save Failed! Try Again.', 'result' => '');
      }
      echo json_encode($result);
      exit;
    }
    // 08-04-2018 - GAUTAM MAKWANA
    if($_REQUEST['action'] == "getAllByVendor"){
     $data = [];
        $query = "SELECT bv.id, bv.purchase_price, bv.gst, bv.unit, bv.qty, pm.product_name as product_name, pm.id as product_id, lgr.id as vendor_id, lgr.name as vendor_name FROM byvendor bv INNER JOIN product_master pm ON bv.product_id = pm.id INNER JOIN ledger_master lgr ON bv.vendor_id = lgr.id ORDER BY bv.id DESC";
        $res = mysqli_query($conn, $query);
        if($res && mysqli_num_rows($res) > 0){
          $i = 1;
          while ($row = mysqli_fetch_array($res)) {
            $arr['id'] = $row['id'];
            $arr['no'] = $i;
            $arr['vendor_name'] = $row['vendor_name'];
            $arr['product_name'] = $row['product_name'];
            $arr['purchase_price'] = $row['purchase_price'];
            $arr['gst'] = $row['gst'];
            $arr['unit'] = $row['unit'];
            $arr['qty'] = $row['qty'];
            array_push($data, $arr);
            $i++;
          }
        }
        
      $finaldata['data'] = $data;
      echo json_encode($finaldata);
      exit;
    }
    
    if($_REQUEST['action'] == "deleteByVendor"){
      if(isset($_REQUEST['id']) && $_REQUEST['id'] != ''){
        $query = "DELETE FROM byvendor WHERE id = '".$_REQUEST['id']."'";
        $res = mysqli_query($conn, $query);
        if($res){
          $result = array('status' => true, 'message' => 'Record deleted successfully.', 'result' => '');
        }else{
          $result = array('status' => false, 'message' => 'Record delete fail! Try again.', 'result' => '');
        }
      }else{
        $result = array('status' => false, 'message' => 'Record delete fail! Try again.', 'result' => '');
      }
      echo json_encode($result);
      exit;
    }

    if($_REQUEST['action'] == "getVendorByProduct"){
      $searchquery = (isset($_REQUEST['query']['term'])) ? $_REQUEST['query']['term'] : '';
      $type = (isset($_REQUEST['type'])) ? $_REQUEST['type'] : '';
      $vendor_id = (isset($_REQUEST['vendor_id'])) ? $_REQUEST['vendor_id'] : '';

      if($searchquery != '' && $type != '' && $vendor_id != ''){
        $query = "SELECT pm.id, pm.product_name, pm.generic_name, pm.mfg_company, pm.give_mrp, pm.igst, pm.cgst, pm.sgst, pm.unit FROM product_master pm INNER JOIN purchase_details pd ON pm.id = pd.product_id INNER JOIN purchase prc ON prc.id = pd.purchase_id WHERE prc.vendor = '".$vendor_id."' ";
        if($type == 'product'){
          $query .="AND pm.product_name like '%".$searchquery."%' AND pm.product_name IS NOT NULL";
        }elseif($type == 'mrp'){
          $query .="AND pm.give_mrp like '%".$searchquery."%' AND pm.give_mrp IS NOT NULL";
        }else{
          $query .="AND pm.generic_name like '%".$searchquery."%' AND pm.generic_name IS NOT NULL";
        }
        $res = mysqli_query($conn, $query);

        if($res && mysqli_num_rows($res) > 0){
          $finalres = [];
            while ($row = mysqli_fetch_array($res)) {
              $arr['id'] = $row['id'];
              $arr['productname'] = $row['product_name'];
              $arr['generic_name'] = $row['generic_name'];
              $arr['menufacturer_name'] = $row['mfg_company'];
              $arr['igst'] = ($row['igst'] != '') ? $row['igst'] : 0;
              $arr['cgst'] = ($row['cgst'] != '') ? $row['cgst'] : 0;
              $arr['sgst'] = ($row['sgst'] != '') ? $row['sgst'] : 0;
              $arr['unit'] = ($row['unit'] != '') ? $row['unit'] : 0;
              $arr['mrp'] = ($row['give_mrp'] != '') ? $row['give_mrp'] : 0;
              if($type == 'product'){
                $arr['name'] = $row['product_name'];
              }elseif ($type == 'mrp') {
                $arr['name'] = $row['give_mrp'];
              }else{
                $arr['name'] = $row['generic_name'];
              }
              array_push($finalres, $arr);
            }
          $result = array('status' => true, 'message' => 'Success!', 'result' => $finalres);
        }else{
          $result = array('status' => false, 'message' => 'Fail!', 'result' => '');
        }
      }else{
        $result = array('status' => false, 'message' => 'Fail!', 'result' => '');
      }

      echo json_encode($result);
      exit;
    }

?>