<?php include('include/config.php');?>
<?php 
  $owner_id = (isset($_SESSION['auth']['owner_id']) && $_SESSION['auth']['owner_id'] != '') ? $_SESSION['auth']['owner_id'] : '';
  $admin_id = (isset($_SESSION['auth']['admin_id']) && $_SESSION['auth']['admin_id'] != '') ? $_SESSION['auth']['admin_id'] : '';
  $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : '';
  $financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';
?>
<?php 
    /// kartik ///
    function total_qty($product_id,$product_batch){
      global $conn;
      $query1 = "SELECT * FROM `product_master` WHERE id='".$product_id."' AND batch_no='".$product_batch."'";
      $result1 = mysqli_query($conn,$query1);
      $row1 = ($result1 && mysqli_num_rows($result1) > 0) ? mysqli_fetch_array($result1) : [];


      $query2 = "SELECT SUM(consumption) As c_total FROM `self_consumption` WHERE product_id ='".$product_id."' AND batch='".$product_batch."' GROUP BY purchase_id";
      $result2 = mysqli_query($conn,$query2);
      $row2 = ($result2 && mysqli_num_rows($result2) > 0) ? mysqli_fetch_array($result2) : [];

      $query3 = "SELECT SUM(qty)as a_total FROM `adjustment` WHERE product_id='".$product_id."'AND batch_no='".$product_batch."' AND type='inward'  GROUP BY qty";
      $result3 = mysqli_query($conn,$query3);
      $row3 = ($result3 && mysqli_num_rows($result3) > 0) ? mysqli_fetch_array($result3) : [];

      $query4 = "SELECT SUM(qty)as a_total FROM `adjustment` WHERE product_id='".$product_id."' AND batch_no='".$product_batch."' AND type='outward'  GROUP BY qty";
      $result4 = mysqli_query($conn,$query4);
      $row4 = ($result4 && mysqli_num_rows($result4) > 0) ? mysqli_fetch_array($result4) : [];
      
      $row1['opening_qty'] = (isset($row1['opening_qty']) && $row1['opening_qty'] != '') ? $row1['opening_qty'] : 0;
      $row1['ratio'] = (isset($row1['ratio']) && $row1['ratio'] != '') ? $row1['ratio'] : 0;
      $row2['c_total'] = (isset($row2['c_total']) && $row2['c_total'] != '') ? $row2['c_total'] : 0;
      $row3['a_total'] = (isset($row3['a_total']) && $row3['a_total'] != '') ? $row3['a_total'] : 0;
      $row4['a_total'] = (isset($row4['a_total']) && $row4['a_total'] != '') ? $row4['a_total'] : 0;

      $c_total = ($row1['opening_qty']*$row1['ratio']) - $row2['c_total'] + $row3['a_total'] - $row4['a_total'];
      return $c_total;
    }


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
    
     if($_REQUEST['action'] == "ownergetCityDetails"){
      $id = $_REQUEST['id'];
      $query = 'SELECT * FROM own_cities WHERE state_id = '.$id.' AND status=1 order by name';
      $result = mysqli_query($conn,$query);
      if(mysqli_num_rows($result) > 0){
        $html ='<option value="">Select City</option>';
            while($row = mysqli_fetch_array($result))
            {
              $html .= '<option value="'.$row['id'].'">'.$row['name'].'</option>';
            }
            echo json_encode(array('error'=>0,'message'=>'','html'=>$html));exit;
      } else {
            echo json_encode(array('error'=>0,'message'=>'','html'=>"<option>No City Found</option>"));
            exit;
      }
    }
    
    if ($_REQUEST['action'] == "getcomanyname") {
   $companyname = $_REQUEST['companyname'];
   $getcompanyname = "select pharmacy_name from pharmacy_profile where pharmacy_name = '".$companyname."'"; 
    $company = mysqli_query($conn,$getcompanyname);
     if(mysqli_num_rows($company) > 0){
       $message = 'existing';
     }
     else
     {
       $message = 'notexisting';
     }
     echo $message;
 exit;
  }  
    
    
if($_REQUEST['action'] == "getproduct"){
  $getproduct = array();
  $query = "SELECT * FROM `product_master` WHERE pharmacy_id = '".$pharmacy_id."' AND status = 1 AND product_name LIKE '%".$_REQUEST['query']['term']."%'";
  
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

if($_REQUEST['action'] == "getproduct_purchase"){
      $getproduct_self = array();
      $query = "SELECT * FROM `product_master` WHERE pharmacy_id = '".$pharmacy_id."' AND status = 1 AND product_name LIKE '%".$_REQUEST['query']."%'";
      
      $result = mysqli_query($conn,$query);
      $num = mysqli_num_rows($result);

      while($row = mysqli_fetch_array($result)){

        $query1 = "SELECT * from (SELECT expiry,mrp,SUM(f_cgst+f_cgst)as gst,SUM(qty*qty_ratio+free_qty)as total_qty,batch,product_id,id FROM `purchase_details` GROUP BY batch) as t WHERE t.product_id='".$row['id']."' ORDER BY t.expiry ASC ";
        $result1 = mysqli_query($conn,$query1);
        $rowcount=mysqli_num_rows($result1);
        if($rowcount > 0){
          while($row1 = mysqli_fetch_array($result1)){
            $query2 = "SELECT SUM(consumption) As c_total FROM `self_consumption` WHERE product_id ='".$row['id']."' AND batch='".$row1['batch']."' GROUP BY purchase_id";
            $result2 = mysqli_query($conn,$query2);
            $row2 = mysqli_fetch_array($result2);
            $query3 = "SELECT SUM(qty)as a_total FROM `adjustment` WHERE product_id='".$row['id']."'AND batch_no='".$row1['batch']."' AND type='inward'  GROUP BY qty";
            $result3 = mysqli_query($conn,$query3);
            $row3 = mysqli_fetch_array($result3);
            $query4 = "SELECT SUM(qty)as a_total FROM `adjustment` WHERE product_id='".$row['id']."' AND batch_no='".$row1['batch']."' AND type='outward'  GROUP BY qty";
            $result4 = mysqli_query($conn,$query4);
            $row4 = mysqli_fetch_array($result4);
            $c_total = $row1['total_qty'] - $row2['c_total'] + $row3['a_total'] - $row4['a_total'];
            $count_per = $row1['mrp'] / $row['ratio'];
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
            $getproduct_self[] = array(
              'id' => $row['id'],
              'name' => $row['product_name'],
              'purchase_id' => $row1['id'],
              'batch' => $row1['batch'],
              'mfg_company'=> $row['mfg_company'],
              'expiry' => date("d/m/Y",strtotime(str_replace("-","/",$row1['expiry']))),
              'total_qty' => $c_total,
              'unit' => $row['unit'],
              'mrp' => $row['give_mrp'],
              'generic_name' => $row['generic_name'],
              'gst' => $row['igst'],
              'count_per' => $count_per,
              'ratio' => $row['ratio'],
              'igst'=> $igst,
              'cgst' => $cgst,
              'sgst' => $sgst
            );
          }
        }else{
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
          $getproduct_self[] = array(
              'id' => $row['id'],
              'name' => $row['product_name'],
              'purchase_id' => '',
              'batch' => '-',
              'expiry' => '-',
              'total_qty' => 0,
              'unit' => $row['unit'],
              'mrp' => $row['give_mrp'],
              'generic_name' => $row['generic_name'],
              'gst' => $row['igst'],
              'ratio' => $row['ratio'],
              'igst'=> $igst,
              'cgst' => $cgst,
              'sgst' => $sgst
            );
        }
      }
      echo json_encode($getproduct_self);
      exit;
}

if($_REQUEST['action'] == "getproduct_purchase_return"){
    $getproduct_self = array();
    $query = "SELECT * FROM `product_master` WHERE pharmacy_id = '".$pharmacy_id."' AND status = 1 AND product_name LIKE '%".$_REQUEST['query']."%'";
    $result = mysqli_query($conn,$query);
    $num = mysqli_num_rows($result);
    
    while($row = mysqli_fetch_array($result)){
      $c_total = total_qty($row['id'],$row['batch_no']);
      //echo total_qty($row['id'],$row['batch_no']);exit;
      if(empty($row['batch_no'])){
        $batch_no = "-";
      }else{
        $batch_no = $row['batch_no'];
      }
      $getproduct_self[] = array(
              'id' => $row['id'],
              'name' => $row['product_name'],
              'batch' => $batch_no,
              'expiry' => (isset($row['ex_date']) && $row['ex_date'] != '') ? date('d/m/Y',strtotime($row['ex_date'])) : '',
              'total_qty' =>$c_total,
              'unit' => $row['unit'],
              'mrp' => $row['mrp'],
              'generic_name' => $row['generic_name'],
              'gst' => $row['igst'],
              'ratio' => $row['ratio'],
              'igst'=> $row['igst'],
              'cgst' => $row['cgst'],
              'sgst' => $row['sgst'],
              'mfg_company' => $row['mfg_company']
            );
    }
    echo json_encode($getproduct_self);
      exit;
}

if($_REQUEST['action'] == "getproduct_purchase_return_list"){

  $getproduct_purchase_return_list = array();
  $query = "SELECT * FROM `purchase_return_detail` pu JOIN product_master pm ON pu.product_id = pm.id WHERE pm.product_name LIKE '%".$_REQUEST['query']."%' AND pm.pharmacy_id = '".$pharmacy_id."' GROUP BY pu.product_id";
  $result = mysqli_query($conn,$query);
  $num = mysqli_num_rows($result);
  while($row = mysqli_fetch_array($result)){
    $c_total = total_qty($row['id'],$row['batch_no']);
    //echo total_qty($row['id'],$row['batch_no']);exit;
    if(empty($row['batch_no'])){
      $batch_no = "-";
    }else{
      $batch_no = $row['batch_no'];
    }

    $c_total = total_qty($row['id'],$row['batch_no']);
    //echo total_qty($row['id'],$row['batch_no']);exit;
    if(empty($row['batch_no'])){
      $batch_no = "-";
    }else{
      $batch_no = $row['batch_no'];
    }
    $getproduct_purchase_return_list[] = array(
            'id' => $row['id'],
            'name' => $row['product_name'],
            'batch' => $batch_no,
            'expiry' => $row['ex_date'],
            'total_qty' =>$c_total,
            'unit' => $row['unit'],
            'mrp' => $row['mrp'],
            'generic_name' => $row['generic_name'],
            'gst' => $row['igst'],
            'ratio' => $row['ratio'],
            'igst'=> $row['igst'],
            'cgst' => $row['cgst'],
            'sgst' => $row['sgst'],
            'mfg_company' => $row['mfg_company'],
            'pr_id' =>$row['pr_id']
          );
  }
  echo json_encode($getproduct_purchase_return_list);
  exit;

}

if($_REQUEST['action'] == "getproduct_self"){
      $getproduct_self = array();
      $query = "SELECT * FROM `product_master` WHERE product_name LIKE '%".$_REQUEST['query']."%'";
      
      $result = mysqli_query($conn,$query);
      $num = mysqli_num_rows($result);

      while($row = mysqli_fetch_array($result)){
        $query1 = "SELECT * from (SELECT expiry,mrp,SUM(f_cgst+f_cgst)as gst,SUM(qty*qty_ratio+free_qty)as total_qty,batch,product_id,id FROM `purchase_details` GROUP BY batch) as t WHERE t.product_id='".$row['id']."' ORDER BY t.expiry ASC ";
        $result1 = mysqli_query($conn,$query1);
          while($row1 = mysqli_fetch_array($result1)){

            $query2 = "SELECT SUM(consumption) As c_total FROM `self_consumption` WHERE product_id ='".$row['id']."' AND batch='".$row1['batch']."' GROUP BY purchase_id";
            $result2 = mysqli_query($conn,$query2);
            $row2 = mysqli_fetch_array($result2);
            $query3 = "SELECT SUM(qty)as a_total FROM `adjustment` WHERE product_id='".$row['id']."'AND batch_no='".$row1['batch']."' AND type='inward'  GROUP BY qty";
            $result3 = mysqli_query($conn,$query3);
            $row3 = mysqli_fetch_array($result3);
            $query4 = "SELECT SUM(qty)as a_total FROM `adjustment` WHERE product_id='".$row['id']."' AND batch_no='".$row1['batch']."' AND type='outward'  GROUP BY qty";
            $result4 = mysqli_query($conn,$query4);
            $row4 = mysqli_fetch_array($result4);
            $c_total = $row1['total_qty'] - $row2['c_total'] + $row3['a_total'] - $row4['a_total'];
            $count_per = $row1['mrp'] / $row['ratio'];
            $getproduct_self[] = array(
              'id' => $row['id'],
              'name' => $row['product_name'].'-'.$row1['batch'],
              'purchase_id' => $row1['id'],
              'batch' => $row1['batch'],
              'expiry' => $row1['expiry'],
              'total_qty' => $c_total,
              'unit' => $row['unit'],
              'mrp' => $row['give_mrp'],
              'generic_name' => $row['generic_name'],
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

if($_REQUEST['action'] == "getproduct_self_changes"){
      $getproduct_self = array();
      $query = "SELECT * FROM `product_master` WHERE product_name LIKE '%".$_REQUEST['query']."%'";
      
      $result = mysqli_query($conn,$query);
      $num = mysqli_num_rows($result);

      while($row = mysqli_fetch_array($result)){
        $c_total = total_qty($row['id'],$row['batch_no']);
        $count_per = $row['mrp'] / $row['ratio'];
        $getproduct_self[] = array(
              'id' => $row['id'],
              'name' => $row['product_name'],
              'batch' => $row['batch_no'],
              'expiry' => $row['ex_date'],
              'total_qty' => $c_total,
              'unit' => $row['unit'],
              'mrp' => $row['mrp'],
              'generic_name' => $row['generic_name'],
              'gst' => $row['igst'],
              'count_per' => $count_per
            );
       /* $getproduct_self[] =array(
          'id' => $row['id'],
          'name' => $row['product_name'].'-'.$row['batch_no'],
          'batch' => $row['batch_no']
        );*/
      }
      echo json_encode($getproduct_self);
      exit;
}

if($_REQUEST['action'] == "getproduct_adjustment"){
      $getproduct_self = array();
      $query = "SELECT * FROM `product_master` WHERE product_name LIKE '%".$_REQUEST['query']."%'";
      
      $result = mysqli_query($conn,$query);
      $num = mysqli_num_rows($result);

      while($row = mysqli_fetch_array($result)){
        $query1 = "SELECT * from (SELECT expiry,mrp,SUM(f_cgst+f_cgst)as gst,SUM(qty*qty_ratio+free_qty)as total_qty,batch,product_id,id FROM `purchase_details` GROUP BY batch) as t WHERE t.product_id='".$row['id']."'";
        $result1 = mysqli_query($conn,$query1);
          while($row1 = mysqli_fetch_array($result1)){
           $query2 = "SELECT SUM(consumption) As c_total FROM `self_consumption` WHERE product_id ='".$row['id']."' AND batch='".$row1['batch']."' GROUP BY purchase_id";
            $result2 = mysqli_query($conn,$query2);
            $row2 = mysqli_fetch_array($result2);
            $query3 = "SELECT SUM(qty)as a_total FROM `adjustment` WHERE product_id='".$row['id']."'AND batch_no='".$row1['batch']."' AND type='inward'  GROUP BY qty";
            $result3 = mysqli_query($conn,$query3);
            $row3 = mysqli_fetch_array($result3);
            $query4 = "SELECT SUM(qty)as a_total FROM `adjustment` WHERE product_id='".$row['id']."' AND batch_no='".$row1['batch']."' AND type='outward'  GROUP BY qty";
            $result4 = mysqli_query($conn,$query4);
            $row4 = mysqli_fetch_array($result4);
            $c_total = $row1['total_qty'] - $row2['c_total'] + $row3['a_total'] - $row4['a_total'];
            $count_per = $row1['mrp'] / $row['ratio'];
            $getproduct_self[] = array(
              'id' => $row['id'],
              'name' => $row['product_name'].'-'.$row1['batch'],
              'purchase_id' => $row1['id'],
              'mrp' => $row['give_mrp'],
              'generic_name' => $row['generic_name'],
              'mfg_company' => $row['mfg_company'],
              'batch' => $row1['batch'],
              'expiry' => $row1['expiry'],
              'total_qty' => $c_total
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

if($_REQUEST['action'] == "getproduct_adjustment_changes"){
      $getproduct_self = array();
      $query = "SELECT * FROM `product_master` WHERE product_name LIKE '%".$_REQUEST['query']."%'";
      
      $result = mysqli_query($conn,$query);
      $num = mysqli_num_rows($result);

      while($row = mysqli_fetch_array($result)){
        $c_total = total_qty($row['id'],$row['batch_no']);
        $getproduct_self[] = array(
              'id' => $row['id'],
              'name' => $row['product_name'],
              'mrp' => $row['mrp'],
              'generic_name' => $row['generic_name'],
              'mfg_company' => $row['mfg_company'],
              'batch' => $row['batch_no'],
              'expiry' => $row['ex_date'],
              'total_qty' => $c_total
            );
      }
      echo json_encode($getproduct_self);
      exit;
}

if($_REQUEST['action'] == "customerrunning"){
    echo"<pre>";
    print_r($_REQUEST);exit;
}
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    //Add By : Gautam Makwana///
    
    if($_REQUEST['action'] == "getProductById"){
      $product_id = (isset($_REQUEST['product_id'])) ? $_REQUEST['product_id'] : '';
      if($product_id != ''){
        $data = getAllProductWithCurrentStock('', '', 0, [$product_id]);
        if(isset($data[0]) && !empty($data[0])){
          $result = array('status' => true, 'message' => 'Success!', 'result' => $data[0]);
        }else{
          $result = array('status' => false, 'message' => 'Fail!', 'result' => '');  
        }
      }else{
        $result = array('status' => false, 'message' => 'Fail!', 'result' => '');
      }
      echo json_encode($result);
      exit;
    }

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
        $query = 'SELECT id, name FROM ledger_master WHERE pharmacy_id = '.$pharmacy_id.' AND city = '.$city_id.' AND status=1 AND group_id=14 order by name';
        $result = mysqli_query($conn,$query);
        if($result && mysqli_num_rows($result) > 0){
          $res = [];
            while ($row = mysqli_fetch_array($result)) {
              $arr['id'] = $row['id']; 
              $arr['name'] = $row['name'];
              array_push($res, $arr);
            }
             $select = array('id' => 'add_new_vendor', 'name'=>'+ Add new Vendor') ;
           array_push($res, $select);
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
        $owner_id = (isset($owner_id) && $owner_id != '') ? $owner_id : NULL;
        $admin_id = (isset($admin_id) && $admin_id != '') ? $admin_id : NULL;
        $pharmacy_id = (isset($pharmacy_id) && $pharmacy_id != '') ? $pharmacy_id : NULL;
        $financial_id = (isset($financial_id) && $financial_id != '') ? $financial_id : NULL;
        
        //$query = "INSERT INTO ledger_master SET";
        $query = "INSERT INTO ledger_master SET financial_id = '".$financial_id."', owner_id = '".$owner_id."', admin_id = '".$admin_id."', pharmacy_id = '".$pharmacy_id."',";
        foreach ($_REQUEST['data'] as $key => $value) {
          $query .= " ".$value['name']." = '".$value['value']."', ";
        }
        $query .= "group_id = '14', account_type = '1', status = '1',created = '".date('Y-m-d H:i:s')."', createdby = '".$_SESSION['auth']['id']."'";
        $result = mysqli_query($conn, $query);
        if($result){
          $last_id = mysqli_insert_id($conn);
          $result = array('status' => true, 'message' => 'Vendor Added Success.', 'result' => $last_id);
        }else{
          $result = array('status' => false, 'message' => 'Vendor Added Fail! Try Again.', 'result' => '');
        }
      }else{
        $result = array('status' => false, 'message' => 'Field is required!', 'result' => '');
      }
      echo json_encode($result);
      exit;
    }
    
    if($_REQUEST['action'] == "addTransport"){
      if(isset($_REQUEST['data']) && $_REQUEST['data'] != ''){
        $data = [];
        parse_str($_REQUEST['data'], $data);

        if(!empty($data)){
            if(isset($data['name']) && $data['name'] != ''){
                $name = $data['name'];
                $t_code = getTransportCode();
                $query = "INSERT INTO transport_master SET financial_id = '".$financial_id."', owner_id = '".$owner_id."', admin_id = '".$admin_id."', pharmacy_id = '".$pharmacy_id."', t_code = '".$t_code."', name = '".$name."', status = 1, created = '".date('Y-m-d H:i:s')."', createdby = '".$_SESSION['auth']['id']."'";
                $res = mysqli_query($conn, $query);
                if($res){
                    $return['name'] = $name;
                    $return['t_code'] = $t_code;
                    $return['id'] = mysqli_insert_id($conn);
                    $result = array('status' => true, 'message' => 'Transport save successfully.', 'result' => $return);
                }else{
                    $result = array('status' => false, 'message' => 'Transport save fail!.', 'result' => '');
                }  
            }else{
                $result = array('status' => false, 'message' => 'Please Enter Transport Name!', 'result' => '');   
            }
        }else{
           $result = array('status' => false, 'message' => 'Transport save fail! Try again.', 'result' => '');   
        }
      }else{
        $result = array('status' => false, 'message' => 'Transport save fail! Try again.', 'result' => '');
      }
      echo json_encode($result);
      exit;
    }
    
    if($_REQUEST['action'] == "addproduct"){
      if(isset($_REQUEST['data']) && !empty($_REQUEST['data'])){
        $data = [];
        parse_str($_REQUEST['data'], $data);
        if(!empty($data)){
          $res['owner_id'] = (isset($owner_id) && $owner_id != '') ? $owner_id : NULL;
          $res['admin_id'] = (isset($admin_id) && $admin_id != '') ? $admin_id : NULL;
          $res['pharmacy_id'] = (isset($pharmacy_id) && $pharmacy_id != '') ? $pharmacy_id : NULL;
          $res['finance_year_id'] = (isset($financial_id) && $financial_id != '') ? $financial_id : NULL;
          $res['user_id'] = $_SESSION['auth']['id'];
          $res['product_code'] = getProductNo();
          $res['product_name'] = (isset($data['product_name'])) ? $data['product_name'] : '';
          $res['generic_name'] = (isset($data['generic_name'])) ? $data['generic_name'] : '';
          $res['mfg_company'] = (isset($data['mfg_company'])) ? $data['mfg_company'] : '';
          $res['bill_print_view'] = (isset($data['bill_print_view'])) ? $data['bill_print_view'] : '';
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
          $res['mrp'] = (isset($data['mrp']) && $data['mrp'] != '') ? $data['mrp'] : 0;
          $res['serial_no'] = (isset($data['serial_no']) && $data['serial_no'] != '') ? $data['serial_no'] : NULL;
          $res['gst_id'] = (isset($data['gst_id']) && $data['gst_id'] != '') ? $data['gst_id'] : NULL;
          $res['igst'] = (isset($data['igst']) && $data['igst'] != '') ? $data['igst'] : 0;
          $res['cgst'] = (isset($data['cgst']) && $data['cgst'] != '') ? $data['cgst'] : 0;
          $res['sgst'] = (isset($data['sgst']) && $data['sgst'] != '') ? $data['sgst'] : 0;
          $res['inward_rate'] = (isset($data['inward_rate']) && $data['inward_rate'] != '') ? $data['inward_rate'] : 0;
          //$res['distributor_rate'] = (isset($data['distributor_rate']) && $data['distributor_rate'] != '') ? $data['distributor_rate'] : 0;
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
          $res['sale_rate_local'] = (isset($data['sale_rate_local']) && $data['sale_rate_local'] != '') ? $data['sale_rate_local'] : '';
          $res['sale_rate_out'] = (isset($data['sale_rate_out']) && $data['sale_rate_out'] != '') ? $data['sale_rate_out'] : '';
          $res['discount'] = (isset($data['discount']) && $data['discount'] != '') ? $data['discount'] : 0;
          $res['discount_per'] = (isset($data['discount_per']) && $data['discount_per'] != '') ? $data['discount_per'] : 0;
          $res['status'] = 1;

          $query = "INSERT INTO product_master SET";
          foreach ($res as $key => $value) {
            $query .= " ".$key." = '".$value."', ";
          }
          $query .= "created_at = '".date('Y-m-d H:i:s')."', created_by = '".$_SESSION['auth']['id']."'";

          $result = mysqli_query($conn, $query);
          if($result){
            $id = mysqli_insert_id($conn);
            $result = array('status' => true, 'message' => 'Product Added Success.', 'result' => $id);
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
      $voucher_no = getpurchaseinvoiceno($purchase_type);
      
      $result = array('status' => true, 'message' => 'Success', 'result' => $voucher_no);
      echo json_encode($result);
      exit;
    }
    
    if($_REQUEST['action'] == "searchProduct"){
        $searchquery = (isset($_REQUEST['query'])) ? $_REQUEST['query'] : '';
        $data = [];
            $query = "SELECT * FROM product_master WHERE pharmacy_id = '".$pharmacy_id."' AND product_name like '%".$searchquery."%' ORDER BY product_name";
            $res = mysqli_query($conn, $query);
            if($res && mysqli_num_rows($res) > 0){
                while($row = mysqli_fetch_assoc($res)){
                    $data[] = $row;
                }
            }
        if(!empty($data)){
            $result = array('status' => true, 'message' => 'Product found success.', 'result' => $data);
        }else{
            $result = array('status' => false, 'message' => 'Product not found!', 'result' => '');
        }
        echo json_encode($result);
        exit;
    }
    
    if($_REQUEST['action'] == "getProductMrpGeneric"){
      $searchquery = (isset($_REQUEST['query']['term'])) ? $_REQUEST['query']['term'] : '';
      $type = (isset($_REQUEST['type'])) ? $_REQUEST['type'] : '';

      if($searchquery != '' && $type != ''){
        $query = "SELECT id,product_name, generic_name, mfg_company, give_mrp, mrp,igst, cgst, sgst, unit FROM product_master ";
        if($type == 'product'){
          $query .="WHERE product_name like '%".$searchquery."%' AND product_name IS NOT NULL";
        }elseif($type == 'mrp'){
          $query .="WHERE mrp like '%".$searchquery."%' AND mrp IS NOT NULL";
        }elseif($type == 'company'){
          $query .="WHERE mfg_company like '%".$searchquery."%' AND mfg_company IS NOT NULL";
        }else{
          $query .="WHERE generic_name like '%".$searchquery."%' AND generic_name IS NOT NULL";
        }
        $query .= " AND pharmacy_id = '".$pharmacy_id."' AND status = 1";
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
              $arr['mrp'] = ($row['mrp'] != '') ? $row['mrp'] : 0;
              if($type == 'product'){
                $arr['name'] = $row['product_name'];
              }elseif ($type == 'mrp') {
                $arr['name'] = $row['mrp'];
              }elseif ($type == 'company') {
                $arr['name'] = $row['mfg_company'];
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
            $query = "INSERT INTO byvendor SET order_no = '".mt_rand(100000,999999)."', vendor_id = '".$data['vendor_id'][$i]."', product_id = '".$data['product_id'][$i]."', purchase_price = '".$data['purchase_price'][$i]."', gst = '".$data['gst'][$i]."', unit = '".$data['unit'][$i]."', qty = '".$data['qty'][$i]."', created = '".date('Y-m-d H:i:s')."', createdby = '".$_SESSION['auth']['id']."'";
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
        $query = "SELECT bv.id, bv.purchase_price, bv.gst, bv.unit, bv.qty, pm.product_name as product_name, pm.id as product_id, lgr.id as vendor_id, lgr.name as vendor_name FROM byvendor bv INNER JOIN product_master pm ON bv.product_id = pm.id INNER JOIN ledger_master lgr ON bv.vendor_id = lgr.id WHERE bv.status = 0 ORDER BY bv.id DESC";
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

    /*if($_REQUEST['action'] == "getVendorByProduct"){
      $searchquery = (isset($_REQUEST['query']['term'])) ? $_REQUEST['query']['term'] : '';
      $type = (isset($_REQUEST['type'])) ? $_REQUEST['type'] : '';
      $vendor_id = (isset($_REQUEST['vendor_id'])) ? $_REQUEST['vendor_id'] : '';

      if($searchquery != '' && $type != '' && $vendor_id != ''){
        $query = "SELECT DISTINCT pm.id, pm.product_name, pm.generic_name, pm.mfg_company, pm.give_mrp, pm.mrp, pm.igst, pm.cgst, pm.sgst, pm.unit FROM product_master pm INNER JOIN purchase_details pd ON pm.id = pd.product_id INNER JOIN purchase prc ON prc.id = pd.purchase_id WHERE prc.vendor = '".$vendor_id."' AND prc.pharmacy_id = '".$pharmacy_id."' AND prc.financial_id = '".$financial_id."'";
        if($type == 'product'){
          $query .="AND pm.product_name like '%".$searchquery."%' AND pm.product_name IS NOT NULL";
        }elseif($type == 'mrp'){
          $query .="AND pm.mrp like '%".$searchquery."%' AND pm.mrp IS NOT NULL";
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
              $arr['mrp'] = ($row['mrp'] != '') ? $row['mrp'] : 0;
              if($type == 'product'){
                $arr['name'] = $row['product_name'];
              }elseif ($type == 'mrp') {
                $arr['name'] = $row['mrp'];
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
    }*/
    
    if($_REQUEST['action'] == "getVendorByProduct"){
      // $searchquery = (isset($_REQUEST['query']['term'])) ? $_REQUEST['query']['term'] : '';
      $type = (isset($_REQUEST['type'])) ? $_REQUEST['type'] : '';
      $vendor_id = (isset($_REQUEST['vendor_id'])) ? $_REQUEST['vendor_id'] : '';

      if($type != '' && $vendor_id != ''){
        $query = "SELECT DISTINCT pm.id, pm.product_name, pm.generic_name, pm.mfg_company, pm.give_mrp, pm.mrp, pm.igst, pm.cgst, pm.sgst, pm.unit FROM product_master pm INNER JOIN purchase_details pd ON pm.id = pd.product_id INNER JOIN purchase prc ON prc.id = pd.purchase_id WHERE prc.vendor = '".$vendor_id."' AND prc.pharmacy_id = '".$pharmacy_id."' AND prc.financial_id = '".$financial_id."'";
        if($type == 'product'){
          $query .=" ORDER BY pm.product_name";
        }elseif($type == 'mrp'){
          $query .=" ORDER BY pm.mrp";
        }else{
          $query .=" ORDER BY pm.generic_name";
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
              $arr['mrp'] = ($row['mrp'] != '') ? $row['mrp'] : 0;
              if($type == 'product'){
                $arr['name'] = (isset($row['product_name']) && $row['product_name'] != '') ? $row['product_name'] : 'Unknown Product Name';
              }elseif ($type == 'mrp') {
                $arr['name'] = (isset($row['mrp']) && $row['mrp'] != '') ? $row['mrp'] : '0';
              }else{
                $arr['name'] = (isset($row['generic_name']) && $row['generic_name'] != '') ? $row['generic_name'] : 'Unknown Generic Name';
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
    
    // This function is used to get all purchase order item by vendor
    // 06-08-2018
    if($_REQUEST['action'] == "getPoiByVendor"){
      $vendor_id = (isset($_REQUEST['vendor_id'])) ? $_REQUEST['vendor_id'] : '';
      if($vendor_id != ''){
        $data = [];

          /* FOR GET DATA IN BYVENDOR TABLE START */
          $query = "SELECT poi.id, poi.order_no, poi.purchase_price, poi.gst, poi.unit, poi.qty, poi.created, pm.id as product_id, pm.product_name as product_name, pm.generic_name as generic_name, pm.mfg_company as mfg_company  FROM byvendor poi INNER JOIN product_master pm ON poi.product_id = pm.id WHERE poi.vendor_id = '".$vendor_id."' AND poi.status = 0";
          $res = mysqli_query($conn, $query);
          if($res && mysqli_num_rows($res)){
              while ($row = mysqli_fetch_array($res)) {
                $arr['id'] = $row['id'];
                $arr['order_no'] = (isset($row['order_no']) && $row['order_no'] != '') ? $row['order_no'] : '';
                $arr['purchase_price'] = $row['purchase_price'];
                $arr['gst'] = $row['gst'];
                $arr['unit'] = $row['unit'];
                $arr['qty'] = $row['qty'];
                $arr['date'] = (isset($row['created']) && $row['created'] != '') ? date('d/m/Y',strtotime($row['created'])) : '';
                $arr['product_id'] = (isset($row['product_id'])) ? $row['product_id'] : '';
                $arr['product_name'] = (isset($row['product_name'])) ? $row['product_name'] : '';
                $arr['generic_name'] = (isset($row['generic_name'])) ? $row['generic_name'] : '';
                $arr['mfg_company'] = (isset($row['mfg_company'])) ? $row['mfg_company'] : '';
                $arr['table'] = 'byvendor';
                array_push($data, $arr);
              }
          }
          /* FOR GET DATA IN BYVENDOR TABLE END */

          /* FOR GET DATA IN BYPRODUCT TABLE START */
          $query1 = "SELECT bp.id, bp.order_no, bp.created,pm.id as product_id, pm.product_name as product_name, pm.generic_name as generic_name, pm.mfg_company as mfg_company FROM byproduct bp INNER JOIN product_master pm ON bp.product_id = pm.id WHERE bp.vendor_id = '".$vendor_id."' AND bp.status = 0";
          $res1 = mysqli_query($conn, $query1);
          if($res1 && mysqli_num_rows($res1)){
              while ($row1 = mysqli_fetch_array($res1)) {
                $arr1['id'] = $row1['id'];
                $arr1['order_no'] = (isset($row1['order_no']) && $row1['order_no'] != '') ? $row1['order_no'] : '';
                $arr1['purchase_price'] = '';
                $arr1['gst'] = '';
                $arr1['unit'] = '';
                $arr1['qty'] = '';
                $arr1['date'] = (isset($row1['created']) && $row1['created'] != '') ? date('d/m/Y',strtotime($row1['created'])) : '';
                $arr1['product_id'] = (isset($row1['product_id'])) ? $row1['product_id'] : '';
                $arr1['product_name'] = (isset($row1['product_name'])) ? $row1['product_name'] : '';
                $arr1['generic_name'] = (isset($row1['generic_name'])) ? $row1['generic_name'] : '';
                $arr1['mfg_company'] = (isset($row1['mfg_company'])) ? $row1['mfg_company'] : '';
                $arr1['table'] = 'byproduct';
                array_push($data, $arr1);
              }
          }
          /* FOR GET DATA IN BYPRODUCT TABLE END */

        if(!empty($data)){
          foreach ($data as $key => $part) {
               $sort[$key] = strtotime($part['date']);
          }
          array_multisort($sort, SORT_DESC, $data);
          $result = array('status' => true, 'message' => 'Data Not Success.', 'result' => $data);
        }else{
          $result = array('status' => false, 'message' => 'Data Not Found!', 'result' => '');
        }
      }else{
          $result = array('status' => false, 'message' => 'Vendor ID Not Found!', 'result' => '');
      }
      echo json_encode($result);
      exit;
    }
    
    // This function is used to get all vendor which is purchase any product
    // 07-08-2018

    if($_REQUEST['action'] == 'getVendorByProductId'){
      $product_id = (isset($_REQUEST['product_id'])) ? $_REQUEST['product_id'] : '';
      $data = [];
        if($product_id != ''){
          $query = "SELECT DISTINCT lgr.id, lgr.name FROM ledger_master lgr INNER JOIN purchase prcs ON lgr.id = prcs.vendor INNER JOIN purchase_details pd ON prcs.id = pd.purchase_id WHERE lgr.group_id = 14 AND lgr.status = 1 AND pd.product_id = '".$product_id."' AND lgr.pharmacy_id = '".$pharmacy_id."' ORDER BY lgr.name";
          $res = mysqli_query($conn, $query);

          if($res && mysqli_num_rows($res) > 0){
            while ($row = mysqli_fetch_array($res)) {
              $arr['id'] = $row['id'];
              $arr['name'] = $row['name'];
              array_push($data, $arr);
            }
          }
        }else{
          $query = "SELECT id, name FROM ledger_master WHERE group_id = 14 AND status = 1 ORDER BY name";
          $res = mysqli_query($conn, $query);
          if($res && mysqli_num_rows($res) > 0){
            while ($row = mysqli_fetch_array($res)) {
              $arr['id'] = $row['id'];
              $arr['name'] = $row['name'];
              array_push($data, $arr);
            }
          }
        }
        if(!empty($data)){
          $result = array('status' => true, 'message' => 'Data found success!', 'result' => $data);
        }else{
          $result = array('status' => false, 'message' => 'Data not found!', 'result' => '');
        }
        echo json_encode($result);
        exit;
    }

    // This function is used to get all datails for vendor by id
    // 07-08-2018
    if($_REQUEST['action'] == 'getVendorDetailByVendorId'){
      $id = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : '';
        if($id != ''){
          $query = "SELECT lgr.id, lgr.name, lgr.email, lgr.mobile, st.state_code_gst as statecode FROM ledger_master lgr LEFT JOIN own_states st ON lgr.state = st.id WHERE lgr.id = '".$id."' LIMIT 1";
          $res = mysqli_query($conn, $query);
          if($res && mysqli_num_rows($res) > 0){
              $row = mysqli_fetch_array($res);

              $data['id'] = (isset($row['id'])) ? $row['id'] : '';
              $data['name'] = isset($row['name']) ? $row['name'] : '';
              $data['email'] = (isset($row['email'])) ? $row['email'] : '';
              $data['mobile'] = (isset($row['mobile'])) ? $row['mobile'] : '';
              $data['statecode'] = (isset($row['statecode']) && $row['statecode'] != '') ? $row['statecode'] : '';

              $result = array('status' => true, 'message' => 'Data found success', 'result' => $data);
          }
        }else{
          $result = array('status' => false, 'message' => 'Data not found!', 'result' => '');
        }
        echo json_encode($result);
        exit;
    }

    // 08-08-2018 - GAUTAM MAKWANA
    if($_REQUEST['action'] == "addByProduct"){
      $data = [];
      parse_str($_REQUEST['data'], $data);

      if(!empty($data)){
        $length = count($data['product_id']);
        if($length > 0){
          for ($i=0; $i < $length; $i++) {
              $product_id = (isset($data['product_id'][$i])) ? $data['product_id'][$i] : '';
              $vendor_id = '';
              if(isset($data['vendor_id'][$i]) && is_numeric($data['vendor_id'][$i])){
                $vendor_id = $data['vendor_id'][$i];
              }elseif(isset($data['vendor_id'][$i])){
                $addVendorQuery = "INSERT INTO ledger_master SET account_type = 1, name = '".$data['vendor_id'][$i]."', mobile = '".$data['mobile'][$i]."', email = '".$data['email'][$i]."', group_id = 14, opening_balance_type = 'DB',status = 1, created = '".date('Y-m-d H:i:s')."', createdby = '".$_SESSION['auth']['id']."'";
                mysqli_query($conn, $addVendorQuery);
                $vendor_id = mysqli_insert_id($conn);
              }
            $query = "INSERT INTO byproduct SET order_no = '".mt_rand(100000,999999)."', product_id = '".$product_id."', vendor_id = '".$vendor_id."', status = 0, created = '".date('Y-m-d H:i:s')."', createdby = '".$_SESSION['auth']['id']."'";
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

    // 08-08-2018 - GAUTAM MAKWANA
    if($_REQUEST['action'] == 'getAllByProduct'){
      $data = [];
        $query = "SELECT bp.id, pm.product_name as product_name, pm.id as product_id, pm.generic_name, pm.mfg_company,lgr.id as vendor_id, lgr.name as vendor_name, lgr.email as vendor_email, lgr.mobile as vendor_mobile  FROM byproduct bp INNER JOIN product_master pm ON bp.product_id = pm.id INNER JOIN ledger_master lgr ON bp.vendor_id = lgr.id WHERE bp.status = 0 ORDER BY bp.id DESC";
        $res = mysqli_query($conn, $query);
        if($res && mysqli_num_rows($res) > 0){
          $i = 1;
          while ($row = mysqli_fetch_array($res)) {
            $arr['id'] = (isset($row['id']) ? $row['id'] : '');
            $arr['no'] = $i;
            $arr['vendor_name'] = (isset($row['vendor_name'])) ? $row['vendor_name'] : '';
            $arr['email'] = (isset($row['vendor_email'])) ? $row['vendor_email'] : '';
            $arr['mobile'] = (isset($row['vendor_mobile'])) ? $row['vendor_mobile'] : '';
            $arr['product_name'] = (isset($row['product_name'])) ? $row['product_name'] : '';
            $arr['generic_name'] = (isset($row['generic_name'])) ? $row['generic_name'] : '';
            $arr['mfg_co'] = (isset($row['mfg_company'])) ? $row['mfg_company'] : '';
            array_push($data, $arr);
            $i++;
          }
        }
        
      $finaldata['data'] = $data;
      echo json_encode($finaldata);
      exit;
    }

    // 09-08-2018 - GAUTAM MAKWANA
    if($_REQUEST['action'] == "deleteByProduct"){
      if(isset($_REQUEST['id']) && $_REQUEST['id'] != ''){
        $query = "DELETE FROM byproduct WHERE id = '".$_REQUEST['id']."'";
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
    
    // 10-08-2018 - GAUTAM MAKWANA
    if($_REQUEST['action'] == "getCompanyCode"){
      $searchquery = (isset($_REQUEST['query']['term'])) ? $_REQUEST['query']['term'] : '';
      $data = [];
      if($searchquery != ''){
        $query = "SELECT id, name, code FROM company_master WHERE status = 1 AND pharmacy_id = '".$pharmacy_id."' AND code like '%".$searchquery."%' ORDER BY name";
        
        $res = mysqli_query($conn, $query);

        if($res && mysqli_num_rows($res) > 0){
          while ($row = mysqli_fetch_array($res)) {
            $arr['id'] = $row['id'];
            $arr['name'] = (isset($row['name'])) ? $row['name'] : '';
            array_push($data, $arr);
          }
        }
      }

      if(!empty($data)){
        $result = array('status' => true, 'message' => 'Data Found Success!', 'result' => $data);
      }else{
        $result = array('status' => false, 'message' => 'Data Not Found!', 'result' => '');
      }
      echo json_encode($result);
      exit;
    }

     //25-12-2018 - RAJESH

    if($_REQUEST['action'] == "addunit"){
      $name = (isset($_REQUEST['data']['name'])) ? $_REQUEST['data']['name'] : '';
      if($name != ''){
        $query = "INSERT INTO unit_master SET pharmacy_id = '".$pharmacy_id."', unit_name = '".$name."', status = 1, created_at = '".date('Y-m-d H:i:s')."', created_by ='".$_SESSION['auth']['id']."'";
        $res = mysqli_query($conn, $query);
        if($res){
          $last_id = mysqli_insert_id($conn);
          $result = array('status' => true, 'message' => 'Unit Added successfully.', 'result' => $last_id);
        }else{
          $result = array('status' => false, 'message' => 'Unit Added fail! Try again.', 'result' => '');  
        }
      }else{
        $result = array('status' => false, 'message' => 'All fields is required!', 'result' => '');
      }
      echo json_encode($result);
      exit;
    }
    
    // 26-12-2018 - rajesh
    if($_REQUEST['action'] == "addarea"){
       
      $name = (isset($_REQUEST['data']['name'])) ? $_REQUEST['data']['name'] : '';
      
      if($name != ''){
        $query = "INSERT INTO area_master SET  pharmacy_id = '".$pharmacy_id."', area_name= '".$name."', status = 1, created_at = '".date('Y-m-d H:i:s')."', created_by ='".$_SESSION['auth']['id']."'";
        $res = mysqli_query($conn, $query);
        if($res){
          $last_id = mysqli_insert_id($conn);
          $result = array('status' => true, 'message' => 'Area Added successfully.', 'result' => $last_id);
        }else{
          $result = array('status' => false, 'message' => 'Area Added fail! Try again.', 'result' => '');  
        }
      }else{
        $result = array('status' => false, 'message' => 'All fields is required!', 'result' => '');
      }
      echo json_encode($result);
      exit;
    }
    
    // 10-08-2018 - GAUTAM MAKWANA
    if($_REQUEST['action'] == "addcompany"){
      $name = (isset($_REQUEST['data']['name'])) ? $_REQUEST['data']['name'] : '';
      if($name != ''){
        $query = "INSERT INTO company_master SET owner_id = '".$owner_id."', admin_id = '".$admin_id."', pharmacy_id = '".$pharmacy_id."', name = '".$name."', status = 1, created = '".date('Y-m-d H:i:s')."', createdby ='".$_SESSION['auth']['id']."'";
        $res = mysqli_query($conn, $query);
        if($res){
          $last_id = mysqli_insert_id($conn);
          $result = array('status' => true, 'message' => 'Product Added successfully.', 'result' => $last_id);
        }else{
          $result = array('status' => false, 'message' => 'Product Added fail! Try again.', 'result' => '');  
        }
      }else{
        $result = array('status' => false, 'message' => 'All fields is required!', 'result' => '');
      }
      echo json_encode($result);
      exit;
    }
    
    if($_REQUEST['action'] == "addgst"){
        
       $gst_name = (isset($_REQUEST['data']['gst_name'])) ? $_REQUEST['data']['gst_name'] : '';
      $igst = (isset($_REQUEST['data']['igst'])) ? $_REQUEST['data']['igst'] : '';
      $sgst = (isset($_REQUEST['data']['sgst'])) ? $_REQUEST['data']['sgst'] : '';
      $cgst = (isset($_REQUEST['data']['cgst'])) ? $_REQUEST['data']['cgst'] : ''; 
      if($gst_name != '' && $igst != '' && $sgst!= '' && $cgst!= ''){
          
      $query = "INSERT INTO gst_master SET pharmacy_id = '".$pharmacy_id."', gst_name = '".$gst_name."', igst = '".$igst."',sgst = '".$sgst."',cgst = '".$cgst."',status = 1, created_at = '".date('Y-m-d H:i:s')."', created_by ='".$_SESSION['auth']['id']."'"; 
        $res = mysqli_query($conn, $query);
        if($res){
          $last_id = mysqli_insert_id($conn);
          $result = array('status' => true, 'message' => 'GST Added successfully.', 'result' => $last_id);
        }else{
          $result = array('status' => false, 'message' => 'GST Added fail! Try again.', 'result' => '');  
        }
      }else{
        $result = array('status' => false, 'message' => 'All fields is required!', 'result' => '');
      }
      echo json_encode($result);
      exit;
    }
    
    // 08-08-2018 - GAUTAM MAKWANA
    /*if($_REQUEST['action'] == "addOrder"){
      $data = [];
      $type = (isset($_REQUEST['type'])) ? $_REQUEST['type'] : '';

      $groups = getOrderGroup($pharmacy_id); //group number

      parse_str($_REQUEST['data'], $data);
      if(!empty($data) && (isset($pharmacy_id) && $pharmacy_id != '')){
        $length = count($data['product_id']);
        if($length > 0){
          for ($i=0; $i < $length; $i++) {
              $product_id = (isset($data['product_id'][$i])) ? $data['product_id'][$i] : '';
              $vendor_id = '';
              if(isset($data['vendor_id'][$i]) && is_numeric($data['vendor_id'][$i])){
                $vendor_id = $data['vendor_id'][$i];
              }elseif(isset($data['vendor_id'][$i])){
                $addVendorQuery = "INSERT INTO ledger_master SET financial_id = '".$financial_id."', owner_id = '".$owner_id."', admin_id = '".$admin_id."', pharmacy_id = '".$pharmacy_id."', account_type = 1, name = '".$data['vendor_id'][$i]."', mobile = '".$data['mobile'][$i]."', email = '".$data['email'][$i]."', group_id = 14, opening_balance_type = 'DB',status = 1, created = '".date('Y-m-d H:i:s')."', createdby = '".$_SESSION['auth']['id']."'";
                mysqli_query($conn, $addVendorQuery);
                $vendor_id = mysqli_insert_id($conn);
              }
            $query = "INSERT INTO orders SET financial_id = '".$financial_id."', owner_id = '".$owner_id."', admin_id = '".$admin_id."', pharmacy_id = '".$pharmacy_id."', groups = '".$groups."', order_no = '".mt_rand(100000,999999)."', vendor_id = '".$vendor_id."', product_id = '".$product_id."', purchase_price = '".$data['purchase_price'][$i]."', gst = '".$data['gst'][$i]."', unit = '".$data['unit'][$i]."', qty = '".$data['qty'][$i]."', type = '".$type."', status = 1, created = '".date('Y-m-d H:i:s')."', createdby = '".$_SESSION['auth']['id']."'";
            $res = mysqli_query($conn, $query);
          }
          //sendOrderMailToVendor($groups);
          $result = array('status' => true, 'message' => 'Data Save successfully.', 'result' => '');
        }else{
          $result = array('status' => false, 'message' => 'Data Save Failed! Try Again.', 'result' => '');
        }
      }else{
        $result = array('status' => false, 'message' => 'Data Save Failed! Try Again.', 'result' => '');
      }
      echo json_encode($result);
      exit;
    }*/
    
    if($_REQUEST['action'] == "addOrder"){
      $data = [];
      $type = (isset($_REQUEST['type'])) ? $_REQUEST['type'] : '';

      $groups = getOrderGroup($pharmacy_id); //group number

      parse_str($_REQUEST['data'], $data);
      
      $vendor_ids = (isset($data['vendor_id']) && !empty($data['vendor_id'])) ? array_unique($data['vendor_id']) : [];
      $reminderday = (isset($data['day'])) ? $data['day'] : '';
      
      if(!empty($data) && (isset($pharmacy_id) && $pharmacy_id != '')){
        $length = count($data['product_id']);
        if($length > 0){
          
          $newGroup = $groups;
          if(isset($data['editid'][0]) && $data['editid'][0] != ''){
            $grpQ = "SELECT groups FROM orders WHERE id =".$data['editid'][0];
            $grpR = mysqli_query($conn, $grpQ);
            if($grpR){
              $grpRow = mysqli_fetch_assoc($grpR);
              $newGroup = $grpRow['groups'];
            }
          }


          for ($i=0; $i < $length; $i++) {
              $product_id = (isset($data['product_id'][$i])) ? $data['product_id'][$i] : '';
              $vendor_id = '';
              if(isset($data['vendor_id'][$i]) && is_numeric($data['vendor_id'][$i])){
                $vendor_id = $data['vendor_id'][$i];
              }elseif(isset($data['vendor_id'][$i])){
                $addVendorQuery = "INSERT INTO ledger_master SET financial_id = '".$financial_id."', owner_id = '".$owner_id."', admin_id = '".$admin_id."', pharmacy_id = '".$pharmacy_id."', account_type = 1, name = '".$data['vendor_id'][$i]."', mobile = '".$data['mobile'][$i]."', email = '".$data['email'][$i]."', group_id = 14, opening_balance_type = 'DB',status = 1, created = '".date('Y-m-d H:i:s')."', createdby = '".$_SESSION['auth']['id']."'";
                mysqli_query($conn, $addVendorQuery);
                $vendor_id = mysqli_insert_id($conn);
              }

              if(isset($data['editid'][$i]) && $data['editid'][$i] != ''){
                $query = "UPDATE orders SET vendor_id = '".$vendor_id."', product_id = '".$product_id."', purchase_price = '".$data['purchase_price'][$i]."', gst = '".$data['gst'][$i]."', unit = '".$data['unit'][$i]."', qty = '".$data['qty'][$i]."', modified = '".date('Y-m-d H:i:s')."', modifiedby = '".$_SESSION['auth']['id']."' WHERE id = '".$data['editid'][$i]."'";
              }else{
                $query = "INSERT INTO orders SET financial_id = '".$financial_id."', owner_id = '".$owner_id."', admin_id = '".$admin_id."', pharmacy_id = '".$pharmacy_id."', groups = '".$newGroup."', order_no = '".mt_rand(100000,999999)."', vendor_id = '".$vendor_id."', product_id = '".$product_id."', purchase_price = '".$data['purchase_price'][$i]."', gst = '".$data['gst'][$i]."', unit = '".$data['unit'][$i]."', qty = '".$data['qty'][$i]."', type = '".$type."', status = 1, created = '".date('Y-m-d H:i:s')."', createdby = '".$_SESSION['auth']['id']."'";
              }
            
            $res = mysqli_query($conn, $query);
          }
        //   sendOrderMailToVendor($newGroup, $vendor_ids);
            setOrderReminder($vendor_ids, $newGroup, $type, $reminderday);
          
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
    
    function setOrderReminder($vendor_ids = [], $groups = null, $type = null, $reminderday = null){
      if(($reminderday != '') && (!empty($vendor_ids))){
        global $conn;
        global $financial_id;
        global $owner_id;
        global $admin_id;
        global $pharmacy_id;

        foreach ($vendor_ids as $key => $value) {
          $vendor_id = $value;
          $date = date('Y-m-d', strtotime('+'.$reminderday.' days'));

          $existQ = "SELECT id FROM order_reminder WHERE vendor_id = '".$vendor_id."' AND groups = '".$groups."' AND type = '".$type."' LIMIT 1";
          $existR = mysqli_query($conn, $existQ);
          if($existR && mysqli_num_rows($existR) > 0){
            $existRow = mysqli_fetch_assoc($existR);
            $reminderQuery = "UPDATE order_reminder SET day = '".$reminderday."', date = '".$date."', modified = '".date('Y-m-d H:i:s')."', modifiedby = '".$_SESSION['auth']['id']."' WHERE id = '".$existRow['id']."'";
          }else{
            $reminderQuery = "INSERT INTO order_reminder SET financial_id = '".$financial_id."', owner_id = '".$owner_id."', admin_id = '".$admin_id."', pharmacy_id = '".$pharmacy_id."', vendor_id = '".$vendor_id."', groups = '".$groups."', type = '".$type."', day = '".$reminderday."', date = '".$date."', created = '".date('Y-m-d H:i:s')."', createdby = '".$_SESSION['auth']['id']."'";
          }
          $res = mysqli_query($conn, $reminderQuery);
        }
      }
      return true;
    }
    
    function getOrderGroup($pharmacy_id = null){
      global $conn;
      $groups = '';
        $queryno = "SELECT groups FROM orders WHERE pharmacy_id = '".$pharmacy_id."' ORDER BY id DESC LIMIT 1";
        $resno = mysqli_query($conn, $queryno);
        if($resno){
          $countrow = mysqli_num_rows($resno);
          if($countrow !== '' && $countrow !== 0){
            $row = mysqli_fetch_array($resno);
            $groups = (isset($row['groups'])) ? $row['groups'] : 0;
            
            $groups = $groups + 1;
            $groups = sprintf("%05d", $groups);
          }else{
            $groups = sprintf("%05d", 1);
          }
        }else{
          $groups = sprintf("%05d", 1);
        }
      return $groups;
    }
    
    function sendOrderMailToVendor($groups = null, $vendor_id = []){

      if((isset($groups) && $groups != '') && (isset($vendor_id) && !empty($vendor_id))){
        global $conn;

        foreach ($vendor_id as $key => $value) {
          $findvendorQ = "SELECT id, name, email FROM ledger_master WHERE id = '".$value."' LIMIT 1";
          $findvendorR = mysqli_query($conn, $findvendorQ);
          if($findvendorR && mysqli_num_rows($findvendorR) > 0){
            $findvendorRow = mysqli_fetch_assoc($findvendorR);
            $vendor_email = (isset($findvendorRow['email'])) ? $findvendorRow['email'] : '';
            if($vendor_email != ''){
                $vendororderQ = "SELECT ord.purchase_price, ord.gst, ord.unit, ord.qty, pm.product_name FROM orders ord INNER JOIN product_master pm ON ord.product_id = pm.id WHERE ord.vendor_id = '".$value."' AND ord.groups = '".$groups."'";
                $vendororderR = mysqli_query($conn, $vendororderQ);

                if($vendororderR && mysqli_num_rows($vendororderR) > 0){
                  $html = "<center><h3>Digibook Order Summary</h3><table border='1' cellpadding='10' cellspacing='0'><thead><th>Sr. No</th><th>Product Name</th><th>Purchase Price</th><th>GST(%)</th><th>Unit</th><th>Qty</th></thead><tbody>";
                  $count = 1;
                    while ($vendororderRow = mysqli_fetch_assoc($vendororderR)) {
                      $html .= "<tr>";
                      $html .= "<td>".$count."</td>";
                      $html .= "<td>".$vendororderRow['product_name']."</td>";
                      $html .= "<td>".$vendororderRow['purchase_price']."</td>";
                      $html .= "<td>".$vendororderRow['gst']."</td>";
                      $html .= "<td>".$vendororderRow['unit']."</td>";
                      $html .= "<td>".$vendororderRow['qty']."</td>";

                      $html .= "<tr/>";
                      $count++;
                    }
                  $html .="</tbody></table></center>";
                  // FOR SEND EMAIL TO VENDOR
                  $r = smtpmail($vendor_email, '', '', 'Digibook Order', $html, '', '');
                }

            }
          }
        }
      }
      return;
    }
    
    /*function sendOrderMailToVendor($groups = null){
      if(isset($groups) && $groups != ''){
        global $conn;
        $findvendorQ = "SELECT ord.vendor_id, lgr.email FROM orders ord INNER JOIN ledger_master lgr ON ord.vendor_id = lgr.id WHERE ord.groups = '".$groups."' GROUP BY ord.vendor_id";
        $findvendorR = mysqli_query($conn, $findvendorQ);

        if($findvendorR && mysqli_num_rows($findvendorR) > 0){
          while ($vendorRow = mysqli_fetch_assoc($findvendorR)) {

            if((isset($vendorRow['vendor_id']) && $vendorRow['vendor_id'] != '') && (isset($vendorRow['email']) && $vendorRow['email'] != '')){
              $vendororderQ = "SELECT ord.purchase_price, ord.gst, ord.unit, ord.qty, pm.product_name FROM orders ord INNER JOIN product_master pm ON ord.product_id = pm.id WHERE ord.vendor_id = '".$vendorRow['vendor_id']."' AND ord.groups = '".$groups."'";
              $vendororderR = mysqli_query($conn, $vendororderQ);

              if($vendororderR && mysqli_num_rows($vendororderR) > 0){
                $html = "<center><h3>Digibook Order Summary</h3><table border='1' cellpadding='10' cellspacing='0'><thead><th>Sr. No</th><th>Product Name</th><th>Purchase Price</th><th>GST(%)</th><th>Unit</th><th>Qty</th></thead><tbody>";
                $count = 1;
                  while ($vendororderRow = mysqli_fetch_assoc($vendororderR)) {
                    $html .= "<tr>";
                    $html .= "<td>".$count."</td>";
                    $html .= "<td>".$vendororderRow['product_name']."</td>";
                    $html .= "<td>".$vendororderRow['purchase_price']."</td>";
                    $html .= "<td>".$vendororderRow['gst']."</td>";
                    $html .= "<td>".$vendororderRow['unit']."</td>";
                    $html .= "<td>".$vendororderRow['qty']."</td>";

                    $html .= "<tr/>";
                    $count++;
                  }
                $html .="</tbody></table></center>";

                // FOR SEND EMAIL TO VENDOR
                $r = smtpmail($vendorRow['email'], '', '', 'Digibook Order', $html, '', '');
              }

            }
          }
        }
      }
      return;
    }*/
    
    
    
    // 22-08-2018
    if($_REQUEST['action'] == 'updateOrder'){
      $data = [];
      parse_str($_REQUEST['data'], $data);
      if(isset($data) && !empty($data) && $data != '' && (isset($data['id']) && $data['id'] != '')){
        $id = $data['id'];
        $post['vendor_id'] = (isset($data['vendor_id'])) ? $data['vendor_id'] : '';
        $post['product_id ']= (isset($data['product_id'])) ? $data['product_id'] : '';
        $post['purchase_price ']= (isset($data['purchase_price']) && $data['purchase_price'] != '') ? $data['purchase_price'] : 0;
        $post['gst ']= (isset($data['gst']) && $data['gst'] != '') ? $data['gst'] : 0 ;
        $post['unit ']= (isset($data['unit']) && $data['unit'] != '') ? $data['unit'] : 0 ;
        $post['qty ']= (isset($data['qty']) && $data['qty'] != '') ? $data['qty'] : 0 ;


        if(!is_numeric($post['vendor_id']) && $post['vendor_id'] != ''){
          $ledgername = $post['vendor_id'];
          $mobile = (isset($data['mobile'])) ? $data['mobile'] : '';
          $email = (isset($data['email'])) ? $data['email'] : '';

          $addVendorQuery = "INSERT INTO ledger_master SET account_type = 1, name = '".$ledgername."', mobile = '".$mobile."', email = '".$email."', group_id = 14, opening_balance_type = 'DB',status = 1, created = '".date('Y-m-d H:i:s')."', createdby = '".$_SESSION['auth']['id']."'";
          mysqli_query($conn, $addVendorQuery);
         $post['vendor_id'] = mysqli_insert_id($conn);
        }

        $query = "UPDATE orders SET";
        foreach ($post as $key => $value) {
           $query .= " ".$key." = '".$value."', ";
        }
        $query .= "modified = '".date('Y-m-d H:i:s')."', modifiedby = '".$_SESSION['auth']['id']."' WHERE id = '".$id."'";
        $res = mysqli_query($conn, $query);
        if($res){
          $result = array('status' => true, 'message' => 'Order update successfully.', 'result' => '');
        }else{
          $result = array('status' => false, 'message' => 'Order update fail! Try again.', 'result' => '');
        }
      }else{
        $result = array('status' => false, 'message' => 'Somthing want wrong! Try again.', 'result' => '');
      }
      echo json_encode($result);
      exit;
    }

    // 08-08-2018 - GAUTAM MAKWANA
    /*if($_REQUEST['action'] == 'getOrder'){
      $type = (isset($_REQUEST['type'])) ? $_REQUEST['type'] : '';
      $data = [];
        $query = "SELECT ord.id, ord.order_no, ord.purchase_price, ord.gst, ord.unit, ord.unit, ord.qty, ord.created, pm.product_name, lgr.name as vendor_name FROM orders ord LEFT JOIN product_master pm ON ord.product_id = pm.id LEFT JOIN ledger_master lgr ON ord.vendor_id = lgr.id WHERE ord.status = 1 AND ord.type = '".$type."' AND ord.pharmacy_id = '".$pharmacy_id."' AND ord.financial_id = '".$financial_id."' ORDER BY ord.id DESC";
        $res = mysqli_query($conn, $query);
        if($res && mysqli_num_rows($res) > 0){
          $i = 1;
          while ($row = mysqli_fetch_array($res)) {
            $arr['id'] = $row['id'];
            $arr['no'] = $i;
            $arr['vendor_name'] = (isset($row['vendor_name'])) ? $row['vendor_name'] : '';
            $arr['product_name'] = (isset($row['product_name'])) ? $row['product_name'] : '';
            $arr['purchase_price'] = (isset($row['purchase_price'])) ? $row['purchase_price'] : '';
            $arr['gst'] = (isset($row['gst'])) ? $row['gst'] : '';
            $arr['unit'] = (isset($row['unit'])) ? $row['unit'] : '';
            $arr['qty'] = (isset($row['qty'])) ? $row['qty'] : '';
            $arr['date'] = (isset($row['created']) && $row['created'] != '') ? date('d/m/Y',strtotime($row['created'])) : '';
            array_push($data, $arr);
            $i++;
          }
        }
        
      $finaldata['data'] = $data;
      echo json_encode($finaldata);
      exit;
    }*/
    
    if($_REQUEST['action'] == 'getOrder'){
        $type = (isset($_REQUEST['type'])) ? $_REQUEST['type'] : '';
        $data['data'] = [];

        $query = "SELECT ord.id, ord.vendor_id, ord.type, GROUP_CONCAT(ord.id SEPARATOR ',') as ids, DATE_FORMAT(ord.created, '%d/%m/%Y') as order_date, ord.groups, lg.name as vendor_name, COUNT(ord.id) as total_order  FROM orders ord LEFT JOIN ledger_master lg ON ord.vendor_id = lg.id WHERE ord.pharmacy_id = '".$pharmacy_id."' AND ord.financial_id = '".$financial_id."' AND ord.type = '".$type."' GROUP BY ord.vendor_id, ord.groups"; //DATE_FORMAT(ord.created, '%y-%m-%d'),
        $res = mysqli_query($conn, $query);
        if($res && mysqli_num_rows($res) > 0){
          $i = 1;
          while ($row = mysqli_fetch_assoc($res)) {
            $reminderday = '';
              /* GET REMINDER DAY START */
                $subquery = "SELECT id, day FROM order_reminder WHERE vendor_id = '".$row['vendor_id']."' AND groups = '".$row['groups']."' AND type = '".$row['type']."' AND pharmacy_id = '".$pharmacy_id."'";
                $subres = mysqli_query($conn, $subquery);
                if($subres && mysqli_num_rows($subres) > 0){
                  $subrow = mysqli_fetch_assoc($subres);
                  $reminderday = (isset($subrow['day'])) ? $subrow['day'] : '';
                }
              /* GET REMINDER DAY END */
            $tmp['no'] = $i;
            $tmp['order_date'] = $row['order_date'];
            $tmp['vendor_name'] = $row['vendor_name'];
            $tmp['total_order'] = $row['total_order'];
            $tmp['id']['group'] = $row['groups'];
            $tmp['id']['vendor_id'] = $row['vendor_id'];
            $tmp['id']['reminder_day'] = $reminderday;
            $data['data'][] = $tmp;
            $i++;
          }
        }
        echo json_encode($data);
        exit;
    }

    // 10-08-2018 - GAUTAM MAKWANA
    if($_REQUEST['action'] == "deleteOrder"){
      if(isset($_REQUEST['id']) && $_REQUEST['id'] != ''){
        $query = "DELETE FROM orders WHERE id = '".$_REQUEST['id']."'";
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
    
    // 10-08-2018 - GAUTAM MAKWANA
    if($_REQUEST['action'] == "getAllOrdersByVendorID"){
      $vendor_id = (isset($_REQUEST['vendor_id'])) ? $_REQUEST['vendor_id'] : '';
      if($vendor_id != ''){
        $data = [];

          /* FOR GET DATA IN ORDER TABLE START */
          $query = "SELECT ord.id, ord.order_no, ord.purchase_price, ord.gst, ord.unit, ord.qty, ord.created, pm.id as product_id, pm.product_name as product_name, pm.generic_name as generic_name, pm.mfg_company as mfg_company  FROM orders ord LEFT JOIN product_master pm ON ord.product_id = pm.id WHERE ord.vendor_id = '".$vendor_id."' AND ord.status = 1 AND ord.pharmacy_id = '".$pharmacy_id."' AND ord.financial_id = '".$financial_id."' ORDER BY ord.id DESC";
          $res = mysqli_query($conn, $query);
          if($res && mysqli_num_rows($res)){
              while ($row = mysqli_fetch_array($res)) {
                $arr['id'] = $row['id'];
                $arr['order_no'] = (isset($row['order_no']) && $row['order_no'] != '') ? $row['order_no'] : '';
                $arr['purchase_price'] = $row['purchase_price'];
                $arr['gst'] = $row['gst'];
                $arr['unit'] = $row['unit'];
                $arr['qty'] = $row['qty'];
                $arr['date'] = (isset($row['created']) && $row['created'] != '') ? date('d/m/Y',strtotime($row['created'])) : '';
                $arr['product_id'] = (isset($row['product_id'])) ? $row['product_id'] : '';
                $arr['product_name'] = (isset($row['product_name'])) ? $row['product_name'] : '';
                $arr['generic_name'] = (isset($row['generic_name'])) ? $row['generic_name'] : '';
                $arr['mfg_company'] = (isset($row['mfg_company'])) ? $row['mfg_company'] : '';
                array_push($data, $arr);
              }
          }
          /* FOR GET DATA IN ORDER TABLE END */
        if(!empty($data)){
          $result = array('status' => true, 'message' => 'Data Not Success.', 'result' => $data);
        }else{
          $result = array('status' => false, 'message' => 'Data Not Found!', 'result' => '');
        }
      }else{
          $result = array('status' => false, 'message' => 'Vendor ID Not Found!', 'result' => '');
      }
      echo json_encode($result);
      exit;
    }
    
    // 13-08-2018
    if($_REQUEST['action'] == "getGroupByAccountType"){
      $type = (isset($_REQUEST['type'])) ? $_REQUEST['type'] : '';
      if($type != ''){
        $query = "SELECT id, name FROM `group` WHERE type='".$type."'";
        $res = mysqli_query($conn, $query);
        if($res && mysqli_num_rows($res) > 0){
          $data = [];
          while ($row = mysqli_fetch_array($res)) {
            $arr['id'] = (isset($row['id'])) ? $row['id'] : '';
            $arr['name'] = (isset($row['name'])) ? $row['name'] : '';
            array_push($data, $arr);
          }
          $result = array('status' => true, 'message' => 'Success Found!', 'result' => $data);
        }else{
          $result = array('status' => false, 'message' => 'Not Found!', 'result' => '');
        }
      }else{
        $result = array('status' => false, 'message' => 'Not Found!', 'result' => '');
      }
      echo json_encode($result);
      exit;
    }
    
    // 14-08-2018
    if($_REQUEST['action'] == "getCreditNoteNo"){
      $cr_no = 'CR-'.sprintf("%05d", 1);
        $query = "SELECT cr_no FROM credit_note WHERE pharmacy_id = '".$pharmacy_id."' ORDER BY id DESC LIMIT 1";
        $res = mysqli_query($conn, $query);
        if($res){
          if(mysqli_num_rows($res) > 0){
            $row = mysqli_fetch_array($res);
            if(isset($row['cr_no']) && $row['cr_no'] != ''){
              $crarr = explode('-',$row['cr_no']);
              $cr_no = $crarr[1];
              $cr_no = $cr_no + 1;
              $cr_no = sprintf("%05d", $cr_no);
              $cr_no = 'CR-'.$cr_no;
            }
          }
        }
      $result = array('status' => true, 'message' => 'success', 'result' => $cr_no);
      echo json_encode($result);
      exit;
    }

    // 14-08-2018
    if($_REQUEST['action'] == "addCreditNote"){
      $data = [];
      if(isset($_REQUEST['data'])){
        parse_str($_REQUEST['data'], $data);
      }
      
      if(!empty($data)){
        $owner_id = (isset($owner_id) && $owner_id != '') ? $owner_id : NULL;
        $admin_id = (isset($admin_id) && $admin_id != '') ? $admin_id : NULL;
        $pharmacy_id = (isset($pharmacy_id) && $pharmacy_id != '') ? $pharmacy_id : NULL;
        $purchase_id = (isset($data['purchase_return_id'])) ? $data['purchase_return_id'] : '';
        $cr_no = (isset($data['cr_no'])) ? $data['cr_no'] : '';
        $cr_date = (isset($data['cr_date']) && $data['cr_date'] != '') ? date('Y-m-d',strtotime(str_replace('/','-',$data['cr_date']))) : '';
        $remarks = (isset($data['remarks'])) ? $data['remarks'] : '';
        $type = (isset($data['type'])) ? $data['type'] : '';
        $amount = (isset($data['amount']) && $data['amount'] != '') ? $data['amount'] : 0;

        $query = "INSERT INTO credit_note SET owner_id = '".$owner_id."', admin_id = '".$admin_id."', pharmacy_id = '".$pharmacy_id."', pr_id = '".$purchase_id."', cr_no = '".$cr_no."', cr_date = '".$cr_date."', type = '".$type."', amount ='".$amount."', remarks = '".$remarks."', created = '".date('Y-m-d H:i:s')."', createdby = '".$_SESSION['auth']['id']."'";
        $res = mysqli_query($conn, $query);
        if($res){
          $updateStatus = "UPDATE purchase_return SET debit_note_settle = 2 WHERE id = '".$purchase_id."'";
          mysqli_query($conn, $updateStatus);
          $result = array('status' => true, 'message' => 'Credit Note Apply Successfully.', 'result' => '');    
        }else{
          $result = array('status' => false, 'message' => 'Credit Note Apply Fail! Try Again.', 'result' => '');    
        }
      }else{
        $result = array('status' => false, 'message' => 'All Field is required!', 'result' => '');  
      }
      echo json_encode($result);
      exit;
    }
    
    /*--------------------------------------------CUSTOMER RELATED AJAX START------------------------------------------------*/
    // 15-08-2018
    if($_REQUEST['action'] == "getAllCustomerByCity"){
      $city_id = (isset($_REQUEST['city_id'])) ? $_REQUEST['city_id'] : '';
      if($city_id != ''){
        $query = "SELECT id, name FROM ledger_master WHERE group_id = 10 AND status = 1 AND city = '".$city_id."' AND pharmacy_id = '".$pharmacy_id."' ORDER BY name";
        $res = mysqli_query($conn, $query);
        if($res && mysqli_num_rows($res) > 0){
          $data = [];
          while ($row = mysqli_fetch_array($res)) {
            $arr['id'] = $row['id'];
            $arr['name'] = $row['name'];
            array_push($data, $arr);
          }
          $result = array('status' => true, 'message' => 'Data Found Success.', 'result' => $data);  
        }else{
          $result = array('status' => false, 'message' => 'Data Not Found!', 'result' => '');  
        }
      }else{
        $result = array('status' => false, 'message' => 'City ID Not Found!', 'result' => '');
      }
      echo json_encode($result);
      exit;
    }

    // 15-08-2018
    if($_REQUEST['action'] == "getCustomerAddressById"){
      $customer_id = (isset($_REQUEST['customer_id'])) ? $_REQUEST['customer_id'] : '';
      if($customer_id){
        $query = "SELECT lgr.id, lgr.addressline1, lgr.addressline2, lgr.addressline3, st.state_code_gst as statecode FROM ledger_master lgr LEFT JOIN own_states st ON lgr.state = st.id WHERE lgr.id = '".$customer_id."'";
        $res = mysqli_query($conn, $query);
        if($res && mysqli_num_rows($res)){
          $row = mysqli_fetch_array($res);
          $data['id'] = (isset($row['id'])) ? $row['id'] : '';
          $data['addressline1'] = (isset($row['addressline1'])) ? $row['addressline1'] : '';
          $data['addressline2'] = (isset($row['addressline2'])) ? $row['addressline2'] : '';
          $data['addressline3'] = (isset($row['addressline3'])) ? $row['addressline3'] : '';
          $data['statecode'] = (isset($row['statecode'])) ? $row['statecode'] : '';
          $result = array('status' => true, 'message' => 'Data Found Success!', 'result' => $data);
        }else{
          $result = array('status' => false, 'message' => 'Data Not Found!', 'result' => '');
        }
      }else{
        $result = array('status' => false, 'message' => 'Customer ID Not Found!', 'result' => '');
      }
      echo json_encode($result);
      exit;
    }

    // 15-08-2018
    if($_REQUEST['action'] == "addcustomer"){
      if(isset($_REQUEST['data']) && !empty($_REQUEST['data'])){
        $owner_id = (isset($owner_id) && $owner_id != '') ? $owner_id : NULL;
        $admin_id = (isset($admin_id) && $admin_id != '') ? $admin_id : NULL;
        $pharmacy_id = (isset($pharmacy_id) && $pharmacy_id != '') ? $pharmacy_id : NULL;
        $financial_id = (isset($financial_id) && $financial_id != '') ? $financial_id : NULL;
        
        $query = "INSERT INTO ledger_master SET financial_id = '".$financial_id."', owner_id = '".$owner_id."', admin_id = '".$admin_id."', pharmacy_id = '".$pharmacy_id."',";
        foreach ($_REQUEST['data'] as $key => $value) {
          $query .= " ".$value['name']." = '".$value['value']."', ";
        }
        $query .= "group_id = '10', account_type = '1', status = '1',created = '".date('Y-m-d H:i:s')."', createdby = '".$_SESSION['auth']['id']."'";
        $result = mysqli_query($conn, $query);
        if($result){
          $data = [];
          $lastid = mysqli_insert_id($conn);
          $lastQ = "SELECT l.id, l.name, l.mobile, l.email, l.city, l.salesman_id, l.rate_id, st.state_code_gst as statecode FROM ledger_master l LEFT JOIN own_states st ON l.state = st.id WHERE l.id = '".$lastid."'";
          $lastR = mysqli_query($conn, $lastQ);
          if($lastR){
            $data = mysqli_fetch_assoc($lastR);
          }
            
          $result = array('status' => true, 'message' => 'Customer Added Success.', 'result' => $data);
        }else{
          $result = array('status' => false, 'message' => 'Customer Added Fail! Try Again.', 'result' => '');
        }
      }else{
        $result = array('status' => false, 'message' => 'Field is required!', 'result' => '');
      }
      echo json_encode($result);
      exit;
    }

    /*--------------------------------------------CUSTOMER RELATED AJAX END-----------------------------------------------------------------------------*/

    // 15-08-2018
    // This ajax is used to get onvoice no on tax billing
    if($_REQUEST['action'] == "getInvoiceNo"){
      $bill_type = (isset($_REQUEST['bill_type'])) ? $_REQUEST['bill_type'] : '';
      $invoice_no = getInvoiceNo($bill_type);
      
      $result = array('status' => true, 'message' => 'Success', 'result' => $invoice_no);
      echo json_encode($result);
      exit;
    }
    
    if($_REQUEST['action'] == "getChallanInvoiceNo"){
      $bill_type = (isset($_REQUEST['bill_type'])) ? $_REQUEST['bill_type'] : '';
      $invoice_no = getChallanInvoiceNo($bill_type);
      
      $result = array('status' => true, 'message' => 'Success', 'result' => $invoice_no);
      echo json_encode($result);
      exit;
    }
    
    if($_REQUEST['action'] == "getInvoiceNoForBillOfSupply"){
      $bill_type = (isset($_REQUEST['bill_type'])) ? $_REQUEST['bill_type'] : '';
      $invoice_no = getInvoiceNoForBillOfSupply($bill_type);
      
      $result = array('status' => true, 'message' => 'Success', 'result' => $invoice_no);
      echo json_encode($result);
      exit;
    }
    
    if($_REQUEST['action'] == "sale_of_service"){
      $bill_type = (isset($_REQUEST['bill_type'])) ? $_REQUEST['bill_type'] : '';
      $invoice_no = sale_of_service($bill_type);
      
      $result = array('status' => true, 'message' => 'Success', 'result' => $invoice_no);
      echo json_encode($result);
      exit;
    }
    
    // 22-08-2018
    // This ajax is used to get invoice no on quotation
    if($_REQUEST['action'] == "getInvoiceNoForQuatation"){
      $bill_type = (isset($_REQUEST['bill_type'])) ? $_REQUEST['bill_type'] : '';
      $invoice_no = getquotationvoucher($bill_type);

      $result = array('status' => true, 'message' => 'Success', 'result' => $invoice_no);
      echo json_encode($result);
      exit;
    }
    
     // 20/08/18
    if($_REQUEST['action'] == "addMissOrder"){
      if(isset($_REQUEST['data']) && $_REQUEST['data'] != ''){
        $data = [];
        parse_str($_REQUEST['data'], $data);

        if(!empty($data)){
          $count = count($data['product_id']);
          if($count > 0){
            for ($i=0; $i < $count; $i++) {
              $financial_id = (isset($financial_id) && $financial_id != '') ? $financial_id : NULL;
              $owner_id = (isset($owner_id) && $owner_id != '') ? $owner_id : NULL;
              $admin_id = (isset($admin_id) && $admin_id != '') ? $admin_id : NULL;
              $pharmacy_id = (isset($pharmacy_id) && $pharmacy_id != '') ? $pharmacy_id : NULL;
              $product_id = (isset($data['product_id'][$i])) ? $data['product_id'][$i] : NULL;
              $qty = (isset($data['qty'][$i])) ? $data['qty'][$i] : 0;
              $unit = (isset($data['unit'][$i])) ? $data['unit'][$i] : 0;
              $created = date('Y-m-d H:i:s');
              $createdby = $_SESSION['auth']['id'];

              $query = "INSERT INTO missed_order SET financial_id = '".$financial_id."', owner_id = '".$owner_id."', admin_id = '".$admin_id."', pharmacy_id = '".$pharmacy_id."', product_id = '".$product_id."', qty = '".$qty."', unit = '".$unit."', created = '".$created."', createdby = '".$createdby."'";
              mysqli_query($conn, $query);
            }
            $result = array('status' => true, 'message' => 'Missed order save successfully.', 'result' => '');   
          }else{
            $result = array('status' => false, 'message' => 'Please Enter at least one product!', 'result' => '');   
          }
        }else{
           $result = array('status' => false, 'message' => 'Missed order save fail! Try again.', 'result' => '');   
        }
      }else{
        $result = array('status' => false, 'message' => 'Missed order save fail! Try again.', 'result' => '');
      }
      echo json_encode($result);
      exit;
    }
    
    // 21-08-2018 // Thiis function is used to get data on edit by vendor order
    /*if($_REQUEST['action'] == 'getDataEditByvendor'){
      if(isset($_REQUEST['id']) && $_REQUEST['id'] != ''){
        $query = "SELECT ord.id, ord.vendor_id, ord.product_id, ord.purchase_price, ord.gst, ord.unit, ord.qty, pm.product_name, pm.generic_name, pm.mfg_company, lgr.name, st.state_code_gst FROM orders ord INNER JOIN product_master pm ON ord.product_id = pm.id LEFT JOIN ledger_master lgr ON ord.vendor_id = lgr.id LEFT JOIN own_states st ON lgr.state = st.id WHERE ord.id = '".$_REQUEST['id']."'";
        $res = mysqli_query($conn, $query);
        if($res && mysqli_num_rows($res) > 0){
          $row = mysqli_fetch_array($res);
          $arr['id'] = (isset($row['id'])) ? $row['id'] : '';
          $arr['vendor_id'] = (isset($row['vendor_id'])) ? $row['vendor_id'] : '';
          $arr['state_code'] = (isset($row['state_code_gst'])) ? $row['state_code_gst'] : '';
          $arr['product_id'] = (isset($row['product_id'])) ? $row['product_id'] : '';
          $arr['product_name'] = (isset($row['product_name'])) ? $row['product_name'] : '';
          $arr['purchase_price'] = (isset($row['purchase_price'])) ? $row['purchase_price'] : '';
          $arr['gst'] = (isset($row['gst'])) ? $row['gst'] : '';
          $arr['unit'] = (isset($row['unit'])) ? $row['unit'] : '';
          $arr['qty'] = (isset($row['qty'])) ? $row['qty'] : '';
          $arr['generic_name'] = (isset($row['generic_name'])) ? $row['generic_name'] : '';
          $arr['mfg_company'] = (isset($row['mfg_company'])) ? $row['mfg_company'] : '';

          $result = array('status' => true, 'message' => 'Data get success.', 'result' => $arr);
        }else{
          $result = array('status' => false, 'message' => 'Somthing Want Wrong! Try again.', 'result' => '');  
        }
      }else{
        $result = array('status' => false, 'message' => 'Somthing Want Wrong! Try again.', 'result' => '');
      }
      echo json_encode($result);
      exit;
    }*/
    
    if($_REQUEST['action'] == 'getDataEditByvendor'){
        if((isset($_REQUEST['vendor_id']) && $_REQUEST['vendor_id'] != '') && (isset($_REQUEST['group']) && $_REQUEST['group'] != '')){
          $data = [];
          $query = "SELECT ord.id, ord.vendor_id, ord.product_id, ord.purchase_price, ord.gst, ord.unit, ord.qty, lg.name as vendor_name, pm.product_name, pm.mfg_company, pm.generic_name, st.state_code_gst as state FROM orders ord LEFT JOIN ledger_master lg ON ord.vendor_id = lg.id LEFT JOIN product_master pm ON ord.product_id = pm.id LEFT JOIN own_states st ON lg.state = st.id WHERE ord.pharmacy_id = '".$pharmacy_id."' AND ord.financial_id = '".$financial_id."' AND ord.groups = '".$_REQUEST['group']."' AND ord.vendor_id = '".$_REQUEST['vendor_id']."'";
          $res = mysqli_query($conn, $query);
          if($res){
            while ($row = mysqli_fetch_assoc($res)) {
              $data[] = $row;
            }
          }
        }

        if(isset($data) && !empty($data)){
          $finaldata['data'] = $data;
          $finaldata['reminder_day'] = '';

          /*Reminder day start*/
            $remQ = "SELECT day FROM order_reminder WHERE vendor_id = '".$_REQUEST['vendor_id']."' AND groups = '".$_REQUEST['group']."' AND type = 1 LIMIT 1";
            $remR = mysqli_query($conn, $remQ);
            if($remR && mysqli_num_rows($remR) > 0){
              $remRow = mysqli_fetch_assoc($remR);
              $finaldata['reminder_day'] = (isset($remRow['day'])) ? $remRow['day'] : '';
            }
          /*Reminder day end*/
          
          $result = array('status' => true, 'message' => 'Data found!', 'result' => $finaldata);
        }else{
          $result = array('status' => false, 'message' => 'Data not found!', 'result' => '');
        }
      echo json_encode($result);
      exit;
    }
    
    // 22-08-2018 // Thiis function is used to get data on edit by product order
    if($_REQUEST['action'] == 'getDataEditByproduct'){
      $data = [];
      if((isset($_REQUEST['group']) && $_REQUEST['group'] != '') && (isset($_REQUEST['vendor_id']) && $_REQUEST['vendor_id'] != '')){
          $query = "SELECT ord.id, ord.vendor_id, ord.product_id, ord.purchase_price, ord.gst, ord.unit, ord.qty, pm.product_name, pm.generic_name, pm.mfg_company, lgr.name as vendor_name, lgr.email as vendor_email, lgr.mobile as vendor_mobile, st.state_code_gst as state_code FROM orders ord INNER JOIN product_master pm ON ord.product_id = pm.id INNER JOIN ledger_master lgr ON ord.vendor_id = lgr.id LEFT JOIN own_states st ON lgr.state = st.id WHERE ord.pharmacy_id = '".$pharmacy_id."' AND ord.financial_id = '".$financial_id."' AND ord.groups = '".$_REQUEST['group']."' AND ord.vendor_id = '".$_REQUEST['vendor_id']."'";
          $res = mysqli_query($conn, $query);
          if($res && mysqli_num_rows($res) > 0){
            while ($row = mysqli_fetch_assoc($res)) {
              $arr['id'] = $row['id'];
              $arr['product_id'] = (isset($row['product_id'])) ? $row['product_id'] : '';
              $arr['product_name'] = (isset($row['product_name'])) ? $row['product_name'] : '';
              $arr['vendor_id'] = (isset($row['vendor_id'])) ? $row['vendor_id'] : '';
              $arr['vendor_name'] = (isset($row['vendor_name'])) ? $row['vendor_name'] : '';
              $arr['state_code'] = (isset($row['state_code'])) ? $row['state_code'] : '';
              $arr['email'] = (isset($row['vendor_email'])) ? $row['vendor_email'] : '';
              $arr['mobile'] = (isset($row['vendor_mobile'])) ? $row['vendor_mobile'] : '';
              $arr['generic_name'] = (isset($row['generic_name'])) ? $row['generic_name'] : '';
              $arr['mfg_company'] = (isset($row['mfg_company'])) ? $row['mfg_company'] : '';
              $arr['purchase_price'] = (isset($row['purchase_price'])) ? $row['purchase_price'] : '';
              $arr['gst'] = (isset($row['gst'])) ? $row['gst'] : '';
              $arr['unit'] = (isset($row['unit'])) ? $row['unit'] : '';
              $arr['qty'] = (isset($row['qty'])) ? $row['qty'] : '';
              $data[] = $arr;
            }
          }
          if(isset($data) && !empty($data)){
            $finaldata['data'] = $data;
            $finaldata['reminder_day'] = '';

            /*Reminder day start*/
            $remQ = "SELECT day FROM order_reminder WHERE vendor_id = '".$_REQUEST['vendor_id']."' AND groups = '".$_REQUEST['group']."' AND type = 2 LIMIT 1";
            $remR = mysqli_query($conn, $remQ);
            if($remR && mysqli_num_rows($remR) > 0){
              $remRow = mysqli_fetch_assoc($remR);
              $finaldata['reminder_day'] = (isset($remRow['day'])) ? $remRow['day'] : '';
            }
            /*Reminder day end*/
            $result = array('status' => true, 'message' => 'Data found success!', 'result' => $finaldata);  
          }else{
            $result = array('status' => false, 'message' => 'Data not found', 'result' => '');
          }
      }else{
        $result = array('status' => false, 'message' => 'Somthing Want Wrong! Try again.', 'result' => '');
      }
      echo json_encode($result);
      exit;
    }
    
    // 23-08-2018 //This ajax is used to search alternative product under tax billing
    if($_REQUEST['action'] == 'getAlternativeProduct'){
      $search = (isset($_REQUEST['query']['term'])) ? $_REQUEST['query']['term'] : '';
      $type = (isset($_REQUEST['type'])) ? $_REQUEST['type'] : '';
      if($search != '' && $type != ''){
        if($type == 'product'){ $field = 'product_name';}elseif($type == 'generic'){$field = 'generic_name';}elseif($type == 'manufacturer'){$field = 'mfg_company';}
        if(isset($field) && $field != ''){
          $query = "SELECT id, product_name, generic_name, mfg_company, group_concat(`id` separator ',') as `ids` FROM product_master WHERE ".$field." like '%".$search."%' AND pharmacy_id = '".$pharmacy_id."' AND ex_date >='".date('Y-m-d')."' GROUP BY ".$field;
          $res = mysqli_query($conn, $query);
          if($res && mysqli_num_rows($res) > 0){
            $data = [];
            $i = 0;
            while ($row = mysqli_fetch_array($res)) {
              $mainarr['id'] = $row['id'];
              $mainarr['name'] = (isset($row[$field])) ? $row[$field] : '';
              $mainarr['totalproducts'] = [];
              if(isset($row['ids']) && $row['ids'] != ''){
                $ids = $row['ids'];
                $subquery = "SELECT id, product_name, generic_name, mfg_company, batch_no, ex_date, mrp, igst, cgst, sgst, unit, ratio,discount_per,discount FROM product_master WHERE id IN (".$ids.") AND ex_date >='".date('Y-m-d')."' ORDER BY id";
                $subres = mysqli_query($conn, $subquery);
                if($subres && mysqli_num_rows($subres) > 0){
                  $subdata = [];
                  while ($subrow = mysqli_fetch_array($subres)) {
                      /// Changes By Kartik /// 
                      $with_gst = ($subrow['mrp'] * $subrow['igst'])/100;
                      $tolal_gst_amount = $subrow['mrp'] + $with_gst;
                      $v = $subrow['mrp'] * $subrow['igst'];
                      $ptr_a = $v / $tolal_gst_amount;
                      $mrp_ptr = $subrow['mrp'] - $ptr_a;
                      $ptr_amount = number_format($mrp_ptr, 2, '.', '') / $subrow['ratio'];
                      
        
                      if($subrow['discount'] == 1){
                        $d_ptr = number_format($ptr_amount, 2, '.', '') * ($subrow['discount_per'] / 100) ;
                        $p_ptr = number_format($ptr_amount, 2, '.', '') - $d_ptr;
                        $rate = number_format($p_ptr, 2, '.', '');
                        $arr['discount'] = (isset($subrow['discount_per'])) ? $subrow['discount_per'] : '';
                      }else{
                        $rate = number_format($ptr_amount, 2, '.', '');
                        $arr['discount'] = '';
                      }
                      
                      /// Changes By Kartik ///
                    $arr['id'] = $subrow['id'];
                    $arr['product_name'] = (isset($subrow['product_name'])) ? $subrow['product_name'] : '';
                    $arr['generic_name'] = (isset($subrow['generic_name'])) ? $subrow['generic_name'] : '';
                    $arr['mfg_company'] = (isset($subrow['mfg_company'])) ? $subrow['mfg_company'] : '';
                    $arr['batch_no'] = (isset($subrow['batch_no'])) ? $subrow['batch_no'] : '';
                    if(isset($subrow['ex_date']) && $subrow['ex_date'] != ''){
                        $expirydate = date('Y-m-d',strtotime($subrow['ex_date']));
                        $todaydate = date('Y-m-d');
                        if($todaydate > $expirydate){
                            $arr['expired'] = 1;
                        }else{
                            $arr['expired'] = 0;
                        }
                    }else{
                        $arr['expired'] = 0;
                    }
                    $arr['ex_date'] = (isset($subrow['ex_date']) && $subrow['ex_date'] != '') ? date('d/m/Y',strtotime($subrow['ex_date'])) : '';
                    $arr['mrp'] = (isset($subrow['mrp']) && $subrow['mrp'] != '') ? $subrow['mrp'] : 0;
                    $arr['igst'] = (isset($subrow['igst']) && $subrow['igst'] != '') ? $subrow['igst'] : 0;
                    $arr['cgst'] = (isset($subrow['cgst']) && $subrow['cgst'] != '') ? $subrow['cgst'] : 0;
                    $arr['sgst'] = (isset($subrow['sgst']) && $subrow['sgst'] != '') ? $subrow['sgst'] : 0;
                    $arr['unit'] = (isset($subrow['unit']) && $subrow['unit'] != '') ? $subrow['unit'] : 0;
                    $arr['ratio'] = (isset($subrow['ratio']) && $subrow['ratio'] != '') ? $subrow['ratio'] : 0;
                    $arr['ptr'] = number_format($ptr_amount, 2, '.', '');
                    $arr['rate'] = number_format($rate, 2, '.', '');
                    $stock = getAllProductWithCurrentStock('', '', 0, [$subrow['id']]);
                    $arr['stock'] = (isset($stock[0]['currentstock']) && $stock[0]['currentstock'] != '') ? $stock[0]['currentstock'] : 0;
                    array_push($subdata, $arr);
                  }
                  $mainarr['totalproducts'] = $subdata;
                }
              }
              array_push($data, $mainarr);
            }
            $result = array('status' => true, 'message' => 'Data found success.', 'result' => $data);
          }else{
            $result = array('status' => false, 'message' => 'Somthing Want Wrong! Try again.', 'result' => '');
          }
        }else{
          $result = array('status' => false, 'message' => 'Somthing Want Wrong! Try again.', 'result' => '');  
        }
      }else{
        $result = array('status' => false, 'message' => 'Somthing Want Wrong! Try again.', 'result' => '');
      }
      echo json_encode($result);
      exit;
    }
    
    // 25-08-2018 This ajax is used to search save and return product in tax billing section
    /*if($_REQUEST['action'] == 'getProductForCustomerReturn'){
      $search = (isset($_REQUEST['query'])) ? $_REQUEST['query'] : '';
      $customer_id = (isset($_REQUEST['customer_id'])) ? $_REQUEST['customer_id'] : '';

      if($customer_id != '' && $search){
        $query = "SELECT pm.id, pm.product_name, pm.generic_name, pm.mfg_company, pm.batch_no, pm.ex_date, tbd.mrp,tbd.qty, tbd.qty_ratio, tbd.freeqty,tbd.rate, tbd.discount, tbd.amount, tbd.gst, tbd.igst, tbd.cgst, tbd.sgst, tbd.totalamount,tb.invoice_no ,tb.invoice_date FROM product_master pm INNER JOIN tax_billing_details tbd ON pm.id = tbd.product_id INNER JOIN tax_billing tb ON tbd.tax_bill_id = tb.id  WHERE tb.customer_id = '".$customer_id."' AND pm.product_name LIKE '%".$search."%' AND tb.pharmacy_id = '".$pharmacy_id."' AND tb.financial_id = '".$financial_id."' ORDER BY pm.product_name";
        $res = mysqli_query($conn,$query);
        if ($res && mysqli_num_rows($res)) {
          $data = [];
          while($row = mysqli_fetch_array($res)){
            $arr['id'] = (isset($row['id'])) ? $row['id'] : '';
            $arr['product_name'] = (isset($row['product_name'])) ? $row['product_name'] : '';
            $arr['generic_name'] = (isset($row['generic_name'])) ? $row['generic_name'] : '';
            $arr['mfg_company'] = (isset($row['mfg_company'])) ? $row['mfg_company'] : '';
            $arr['batch_no'] = (isset($row['batch_no'])) ? $row['batch_no'] : '';
            $arr['ex_date'] = (isset($row['ex_date']) && $row['ex_date'] != '') ? date('d/m/Y',strtotime($row['ex_date'])) : '';
            $arr['mrp'] = (isset($row['mrp']) && $row['mrp'] != '') ? $row['mrp'] : 0;
            $arr['qty'] = (isset($row['qty']) && $row['qty'] != '') ? $row['qty'] : 0;
            $arr['qty_ratio'] = (isset($row['qty_ratio']) && $row['qty_ratio'] != '') ? $row['qty_ratio'] : 0;
            $arr['freeqty'] = (isset($row['freeqty']) && $row['freeqty'] != '') ? $row['freeqty'] : 0;
            $arr['rate'] = (isset($row['rate']) && $row['rate'] != '') ? $row['rate'] : 0;
            $arr['discount'] = (isset($row['discount']) && $row['discount'] != '') ? $row['discount'] : 0;
            $arr['amount'] = (isset($row['amount']) && $row['amount'] != '') ? $row['amount'] : 0;
            $arr['gst'] = (isset($row['gst']) && $row['gst'] != '') ? $row['gst'] : 0;
            $arr['igst'] = (isset($row['igst']) && $row['igst'] != '') ? $row['igst'] : 0;
            $arr['cgst'] = (isset($row['cgst']) && $row['cgst'] != '') ? $row['cgst'] : 0;
            $arr['sgst'] = (isset($row['sgst']) && $row['sgst'] != '') ? $row['sgst'] : 0;
            $arr['totalamount'] = (isset($row['totalamount']) && $row['totalamount'] != '') ? $row['totalamount'] : 0;
            $arr['invoice_date'] = (isset($row['invoice_date']) && $row['invoice_date'] != '') ? date('d/m/Y',strtotime($row['invoice_date'])) : '';
            $arr['invoice_no'] = (isset($row['invoice_no']) && $row['invoice_no'] != '') ? $row['invoice_no'] : 0;
            $data[] = $arr;
          }
          $result = array('status' => true, 'message' => 'Data found success.', 'result' => $data);
        }else{
          $result = array('status' => false, 'message' => 'Data not found!', 'result' => '');
        } 
      }else{
        $result = array('status' => false, 'message' => 'Customer ID and Query is required!', 'result' => '');
      }
      echo json_encode($result);
          exit;
    }*/
    
    if($_REQUEST['action'] == 'getProductForCustomerReturn'){
      $search = (isset($_REQUEST['query'])) ? $_REQUEST['query'] : '';
      $customer_id = (isset($_REQUEST['customer_id'])) ? $_REQUEST['customer_id'] : '';

      if($customer_id != '' && $search){
        $query = "SELECT pm.id as product_id, pm.product_name, pm.generic_name, pm.mfg_company, pm.batch_no, pm.mrp, tbd.qty, tbd.freeqty, tbd.rate, tbd.gst, tbd.igst, tbd.cgst, tbd.sgst, tbd.totalamount, tb.id as tax_bill_id, tb.invoice_no, tb.invoice_date, tb.final_amount FROM product_master pm INNER JOIN tax_billing_details tbd ON pm.id = tbd.product_id INNER JOIN tax_billing tb ON tbd.tax_bill_id = tb.id  WHERE tb.customer_id = '".$customer_id."' AND pm.product_name LIKE '%".$search."%' AND tb.pharmacy_id = '".$pharmacy_id."' AND tb.financial_id = '".$financial_id."' ORDER BY pm.product_name";
        
        $res = mysqli_query($conn,$query);
        if ($res && mysqli_num_rows($res)) {
          $data = [];
          while($row = mysqli_fetch_array($res)){
            $arr['product_id'] = (isset($row['product_id'])) ? $row['product_id'] : '';
            $arr['product_name'] = (isset($row['product_name'])) ? $row['product_name'] : '';
            $arr['tax_bill_id'] = (isset($row['tax_bill_id'])) ? $row['tax_bill_id'] : '';
            $arr['generic_name'] = (isset($row['generic_name'])) ? $row['generic_name'] : '';
            $arr['mfg_company'] = (isset($row['mfg_company'])) ? $row['mfg_company'] : '';
            $arr['batch_no'] = (isset($row['batch_no'])) ? $row['batch_no'] : '';
            $arr['mrp'] = (isset($row['mrp']) && $row['mrp'] != '') ? $row['mrp'] : 0;
            $arr['qty'] = (isset($row['qty']) && $row['qty'] != '') ? $row['qty'] : 0;
            $arr['freeqty'] = (isset($row['freeqty']) && $row['freeqty'] != '') ? $row['freeqty'] : 0;
            $arr['rate'] = (isset($row['rate']) && $row['rate'] != '') ? $row['rate'] : 0;
            $arr['gst'] = (isset($row['gst']) && $row['gst'] != '') ? $row['gst'] : 0;
            $arr['igst'] = (isset($row['igst']) && $row['igst'] != '') ? $row['igst'] : 0;
            $arr['cgst'] = (isset($row['cgst']) && $row['cgst'] != '') ? $row['cgst'] : 0;
            $arr['sgst'] = (isset($row['sgst']) && $row['sgst'] != '') ? $row['sgst'] : 0;
            $arr['totalamount'] = (isset($row['totalamount']) && $row['totalamount'] != '') ? $row['totalamount'] : 0;
            $arr['invoice_no'] = (isset($row['invoice_no'])) ? $row['invoice_no'] : '';
            $arr['invoice_date'] = (isset($row['invoice_date']) && $row['invoice_date'] != '' && $row['invoice_date'] != '0000-00-00') ? date('d/m/Y',strtotime($row['invoice_date'])) : '';
            $arr['final_amount'] = (isset($row['final_amount']) && $row['final_amount'] != '') ? $row['final_amount'] : 0;
            $data[] = $arr;
          }
          $result = array('status' => true, 'message' => 'Data found success.', 'result' => $data);
        }else{
          $result = array('status' => false, 'message' => 'Data not found!', 'result' => '');
        } 
      }else{
        $result = array('status' => false, 'message' => 'Customer ID and Query is required!', 'result' => '');
      }
      echo json_encode($result);
      exit;
    }
    
    if($_REQUEST['action'] == 'searchProductPurchaseReturn'){
      $search = (isset($_REQUEST['query'])) ? $_REQUEST['query'] : '';
      $vendor_id = (isset($_REQUEST['vendor'])) ? $_REQUEST['vendor'] : '';

      if($vendor_id != '' && $search){
        $query = "SELECT p.id as purchase_id, p.invoice_date, p.invoice_no, p.total_total as invoice_amount, pd.id as pdid,pd.qty, pd.free_qty, pd.rate, pd.discount, pd.f_rate, pd.ammout, pd.f_igst, pd.f_cgst, pd.f_sgst, pm.id as product_id, pm.product_name, pm.mfg_company, pm.batch_no, pm.ex_date, pm.mrp FROM purchase_details pd INNER JOIN purchase p ON pd.purchase_id = p.id INNER JOIN product_master pm ON pd.product_id = pm.id WHERE p.vendor = '".$vendor_id."' AND p.pharmacy_id = '".$pharmacy_id."' AND p.financial_id = '".$financial_id."' AND pm.product_name LIKE '%".$search."%' ORDER BY pm.product_name";
        $res = mysqli_query($conn,$query);
        if ($res && mysqli_num_rows($res) > 0) {
          $data = [];
          while($row = mysqli_fetch_assoc($res)){
            $row['ex_date'] = (isset($row['ex_date']) && $row['ex_date'] != '' && $row['ex_date'] != '0000-00-00') ? date('d/m/Y',strtotime($row['ex_date'])) : '';
            $row['invoice_date'] = (isset($row['invoice_date']) && $row['invoice_date'] != '' && $row['invoice_date'] != '0000-00-00') ? date('d/m/Y',strtotime($row['invoice_date'])) : '';
            $data[] = $row;
          }
          $result = array('status' => true, 'message' => 'Data found success.', 'result' => $data);
        }else{
          $result = array('status' => false, 'message' => 'Data not found!', 'result' => '');
        } 
      }else{
        $result = array('status' => false, 'message' => 'Vendor ID and Query is required!', 'result' => '');
      }
      echo json_encode($result);
      exit;
    }
    
    // 29-08-2018 // This ajax is used to cancel bill
    if($_REQUEST['action'] == 'cancelbill'){
      if(isset($_REQUEST['id']) && $_REQUEST['id'] != ''){
        $query = "UPDATE tax_billing SET cancel = 0 WHERE id = '".$_REQUEST['id']."'";
        $res = mysqli_query($conn, $query);
        if($res){
          $result = array('status' => true, 'message' => 'Bill Cancel successfully.', 'result' => '');
        }else{
          $result = array('status' => false, 'message' => 'Bill Cancel fail!', 'result' => '');  
        }
      }else{
        $result = array('status' => false, 'message' => 'Bill id not found!', 'result' => '');
      }
      echo json_encode($result);
      exit;
    }
    
    // 30-08-2018 This ajax is used to add sales order
    if($_REQUEST['action'] == 'addSalesOrder'){
      if(isset($_REQUEST['data']) && $_REQUEST['data'] != ''){
        $data = [];
        parse_str($_REQUEST['data'], $data);
        $group = getSaleOrderGroup();
        $customer_ids = (isset($data['customer_id']) && !empty($data['customer_id'])) ? array_unique($data['customer_id']) : [];
        $reminderday = (isset($data['day'])) ? $data['day'] : '';

        if(!empty($data)){
          $length = count($data['customer_id']);
          if($length > 0){

            $finalgroup = $group;
            if(isset($data['id'][0]) && $data['id'][0] != ''){
              $findgrpQ = "SELECT groups FROM sales_order WHERE id = '".$data['id'][0]."'";
              $findgrpR = mysqli_query($conn, $findgrpQ);
              if($findgrpR && mysqli_num_rows($findgrpR) > 0){
                $findgrpRow = mysqli_fetch_assoc($findgrpR);
                $finalgroup = (isset($findgrpRow['groups'])) ? $findgrpRow['groups'] : '';
              }
            }

            for ($i=0; $i < $length; $i++) {
              $financial_id = (isset($financial_id) && $financial_id != '') ? $financial_id : NULL;
              $owner_id = (isset($owner_id) && $owner_id != '') ? $owner_id : NULL;
              $admin_id = (isset($admin_id) && $admin_id != '') ? $admin_id : NULL;
              $pharmacy_id = (isset($pharmacy_id) && $pharmacy_id != '') ? $pharmacy_id : NULL;
              $customer_id = (isset($data['customer_id'][$i])) ? $data['customer_id'][$i] : NULL;
              $product_id = (isset($data['product_id'][$i])) ? $data['product_id'][$i] : NULL;
              $qty = (isset($data['qty'][$i]) && $data['qty'][$i] != '') ? $data['qty'][$i] : 0;
              $discount = (isset($data['discount'][$i]) && $data['discount'][$i] != '') ? $data['discount'][$i] : 0;
              $mrp = (isset($data['mrp'][$i]) && $data['mrp'][$i] != '') ? $data['mrp'][$i] : 0;
              $id = (isset($data['id'][$i]) && $data['id'][$i] != '') ? $data['id'][$i] : '';

              if($id != ''){
                $query = "UPDATE sales_order SET customer_id = '".$customer_id."', product_id = '".$product_id."', qty = '".$qty."', discount = '".$discount."', mrp = '".$mrp."', modified = '".date('Y-m-d H:i:s')."', modifiedby = '".$_SESSION['auth']['id']."' WHERE id = '".$id."'";
              }else{
                
                $query = "INSERT INTO sales_order SET financial_id = '".$financial_id."', owner_id = '".$owner_id."', admin_id = '".$admin_id."', pharmacy_id = '".$pharmacy_id."', groups = '".$finalgroup."', customer_id = '".$customer_id."', product_id = '".$product_id."', qty = '".$qty."', discount = '".$discount."', mrp = '".$mrp."', created = '".date('Y-m-d H:i:s')."', createdby = '".$_SESSION['auth']['id']."'";
              }

              $res = mysqli_query($conn, $query);
            }
            setSaleOrderReminder($customer_ids, $finalgroup, $reminderday);
            // sendOrderMailToCustomer($finalgroup, $customer_ids);
            $result = array('status' => true, 'message' => 'Order added successfully.', 'result' => '');
          }else{
            $result = array('status' => false, 'message' => 'Order added fail! Try again', 'result' => '');
          }
        }else{
          $result = array('status' => false, 'message' => 'Order added fail! Try again', 'result' => '');
        }
      }else{
        $result = array('status' => false, 'message' => 'Somthing Want Wrong! Try Again.', 'result' => '');
      }
      echo json_encode($result);
      exit;
    }
    
    function setSaleOrderReminder($customer_ids = [], $groups = null, $reminderday = null){

      if(($reminderday != '') && (!empty($customer_ids))){
        global $conn;
        global $financial_id;
        global $owner_id;
        global $admin_id;
        global $pharmacy_id;

        foreach ($customer_ids as $key => $value) {
          $customer_id = $value;
          $date = date('Y-m-d', strtotime('+'.$reminderday.' days'));

          $existQ = "SELECT id FROM sales_order_reminder WHERE customer_id = '".$customer_id."' AND groups = '".$groups."' LIMIT 1";
          $existR = mysqli_query($conn, $existQ);
          if($existR && mysqli_num_rows($existR) > 0){
            $existRow = mysqli_fetch_assoc($existR);
            $reminderQuery = "UPDATE sales_order_reminder SET day = '".$reminderday."', date = '".$date."', modified = '".date('Y-m-d H:i:s')."', modifiedby = '".$_SESSION['auth']['id']."' WHERE id = '".$existRow['id']."'";
          }else{
            $reminderQuery = "INSERT INTO sales_order_reminder SET financial_id = '".$financial_id."', owner_id = '".$owner_id."', admin_id = '".$admin_id."', pharmacy_id = '".$pharmacy_id."', customer_id = '".$customer_id."', groups = '".$groups."', day = '".$reminderday."', date = '".$date."', created = '".date('Y-m-d H:i:s')."', createdby = '".$_SESSION['auth']['id']."'";
          }
          $res = mysqli_query($conn, $reminderQuery);
        }
      }
      return true;
    }

    function getSaleOrderGroup(){
      global $conn;
      global $pharmacy_id;
      $groups = '';
        $queryno = "SELECT groups FROM sales_order WHERE pharmacy_id = '".$pharmacy_id."' ORDER BY id DESC LIMIT 1";
        $resno = mysqli_query($conn, $queryno);
        if($resno){
          $countrow = mysqli_num_rows($resno);
          if($countrow !== '' && $countrow !== 0){
            $row = mysqli_fetch_array($resno);
            $groups = (isset($row['groups'])) ? $row['groups'] : 0;
            
            $groups = $groups + 1;
            $groups = sprintf("%05d", $groups);
          }else{
            $groups = sprintf("%05d", 1);
          }
        }else{
          $groups = sprintf("%05d", 1);
        }
      return $groups;
    }
    
    function sendOrderMailToCustomer($groups = null, $customer_id = []){

      if((isset($groups) && $groups != '') && (isset($customer_id) && !empty($customer_id))){
        global $conn;

        foreach ($customer_id as $key => $value) {
          $findcustomerQ = "SELECT id, name, email FROM ledger_master WHERE id = '".$value."' LIMIT 1";
          $findcustomerR = mysqli_query($conn, $findcustomerQ);
          if($findcustomerR && mysqli_num_rows($findcustomerR) > 0){
            $findcustomerRow = mysqli_fetch_assoc($findcustomerR);
            $customer_email = (isset($findcustomerRow['email'])) ? $findcustomerRow['email'] : '';
            if($customer_email != ''){
                $customerOrderQ = "SELECT so.id, so.qty, so.discount, so.mrp, pm.product_name FROM sales_order so INNER JOIN product_master pm ON so.product_id = pm.id WHERE so.customer_id = '".$value."' AND so.groups = '".$groups."'";
                $customerOrderR = mysqli_query($conn, $customerOrderQ);

                if($customerOrderR && mysqli_num_rows($customerOrderR) > 0){
                  $html = "<center><h3>Digibook Order Summary</h3><table border='1' cellpadding='10' cellspacing='0'><thead><th>Sr. No</th><th>Product Name</th><th>Qty</th><th>Discount(%)</th><th>MRP</th></thead><tbody>";
                  $count = 1;
                    while ($vendororderRow = mysqli_fetch_assoc($customerOrderR)) {
                      $html .= "<tr>";
                      $html .= "<td>".$count."</td>";
                      $html .= "<td>".$vendororderRow['product_name']."</td>";
                      $html .= "<td>".$vendororderRow['qty']."</td>";
                      $html .= "<td>".$vendororderRow['discount']."</td>";
                      $html .= "<td>".$vendororderRow['mrp']."</td>";

                      $html .= "<tr/>";
                      $count++;
                    }
                  $html .="</tbody></table></center>";
                  
                  // FOR SEND EMAIL TO VENDOR
                  $r = smtpmail($customer_email, '', '', 'Digibook Order', $html, '', '');
                }

            }
          }
        }
      }
      return;
    }

    // 30-08-2018 This ajax is used to get all sales order
    if($_REQUEST['action'] == 'getSalesOrder'){
      $data = [];
        // $query = "SELECT so.id, so.qty, so.discount, so.mrp, lgr.name as customer_name, pm.product_name as product_name FROM sales_order so INNER JOIN ledger_master lgr ON so.customer_id = lgr.id INNER JOIN product_master pm ON so.product_id = pm.id WHERE so.pharmacy_id = '".$pharmacy_id."' AND so.financial_id = '".$financial_id."' ORDER BY so.id DESC";
        $query = "SELECt so.groups, so.customer_id, DATE_FORMAT(so.created, '%d/%m/%Y') as order_date, COUNT(so.id) as totalorder, lgr.name as customer_name FROM sales_order so INNER JOIN ledger_master lgr ON so.customer_id = lgr.id WHERE so.pharmacy_id = '".$pharmacy_id."' AND so.financial_id = '".$financial_id."' GROUP BY so.customer_id, so.groups";
        $res = mysqli_query($conn, $query);
        if($res && mysqli_num_rows($res) > 0){
          $i = 1;
          while ($row = mysqli_fetch_array($res)) {
            $tmp['no'] = $i;
            $tmp['order_date'] = $row['order_date'];
            $tmp['customer_name'] = $row['customer_name'];
            $tmp['totalorder'] = $row['totalorder'];
            $tmp['id']['group'] = $row['groups'];
            $tmp['id']['customer_id'] = $row['customer_id'];
            $data[] = $tmp;
            $i++;
          }
        }
        
      $finaldata['data'] = $data;
      echo json_encode($finaldata);
      exit;
    }
    
    // 31-08-2018 This ajax is used to delete sales order
    if($_REQUEST['action'] == 'deleteSalesOrder'){
      if((isset($_REQUEST['id']) && $_REQUEST['id'] != '')){
        $query = "DELETE FROM sales_order WHERE id = '".$_REQUEST['id']."'";
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

    // 31-08-2018 This ajax is used to get edit sales order data
    if($_REQUEST['action'] == 'getEditDataForSalesOrder'){
      if((isset($_REQUEST['id']) && $_REQUEST['id'] != '') && (isset($_REQUEST['group']) && $_REQUEST['group'] != '')){
        $query = "SELECT so.id, so.customer_id, lgr.name as customer_name,so.product_id, so.qty, so.discount, so.mrp, pm.product_name FROM sales_order so INNER JOIN product_master pm ON so.product_id = pm.id INNER JOIN ledger_master lgr ON so.customer_id = lgr.id WHERE so.customer_id = '".$_REQUEST['id']."' AND so.groups = '".$_REQUEST['group']."'";
        $res = mysqli_query($conn, $query);
        if($res && mysqli_num_rows($res) > 0){
          $finaldata = [];
          while ($row = mysqli_fetch_array($res)) {
            $data['id'] = (isset($row['id'])) ? $row['id'] : '';
            $data['customer_id'] = (isset($row['customer_id'])) ? $row['customer_id'] : '';
            $data['customer_name'] = (isset($row['customer_name'])) ? $row['customer_name'] : '';
            $data['product_id'] = (isset($row['product_id'])) ? $row['product_id'] : '';
            $data['product_name'] = (isset($row['product_name'])) ? $row['product_name'] : '';
            $data['qty'] = (isset($row['qty'])) ? $row['qty'] : '';
            $data['discount'] = (isset($row['discount'])) ? $row['discount'] : '';
            $data['mrp'] = (isset($row['mrp'])) ? $row['mrp'] : '';
            $finaldata[] = $data;
          }
          $fulldata['data'] = $finaldata;
          $fulldata['reminder_day'] = '';

            /*Reminder day start*/
            $remQ = "SELECT day FROM sales_order_reminder WHERE customer_id = '".$_REQUEST['id']."' AND groups = '".$_REQUEST['group']."' LIMIT 1";
            $remR = mysqli_query($conn, $remQ);
            if($remR && mysqli_num_rows($remR) > 0){
              $remRow = mysqli_fetch_assoc($remR);
              $fulldata['reminder_day'] = (isset($remRow['day'])) ? $remRow['day'] : '';
            }
            /*Reminder day end*/

          $result = array('status' => true, 'message' => 'Data fetched successfully.', 'result' => $fulldata);  
        }else{
          $result = array('status' => false, 'message' => 'Somthing want wrong! Try again.', 'result' => '');  
        }
      }else{
        $result = array('status' => false, 'message' => 'Record not found! Try again.', 'result' => '');
      }
      echo json_encode($result);
      exit;
    }

    // 31-08-2018 This ajax is used to update sales order
    if($_REQUEST['action'] == 'updateSalesOrder'){
      if(isset($_REQUEST['data']) && $_REQUEST['data'] != ''){
        $data = [];
        parse_str($_REQUEST['data'], $data);

        if(!empty($data) && (isset($data['id']) && $data['id'] != '')){
          $customer_id = (isset($data['customer_id'])) ? $data['customer_id'] : NULL;
          $product_id = (isset($data['product_id'])) ? $data['product_id'] : NULL;
          $qty = (isset($data['qty']) && $data['qty'] != '') ? $data['qty'] : 0;
          $discount = (isset($data['discount']) && $data['discount'] != '') ? $data['discount'] : 0;
          $mrp = (isset($data['mrp']) && $data['mrp'] != '') ? $data['mrp'] : 0;

          $query = "UPDATE sales_order SET customer_id = '".$customer_id."', product_id = '".$product_id."', qty = '".$qty."', discount = '".$discount."', mrp = '".$mrp."', modified = '".date('Y-m-d H:i:s')."', modifiedby = '".$_SESSION['auth']['id']."' WHERE id = '".$data['id']."'";
          $res = mysqli_query($conn, $query);
          if($res){
            $result = array('status' => true, 'message' => 'Order update successfully.', 'result' => mysqli_insert_id($conn));
          }else{
            $result = array('status' => false, 'message' => 'Order added fail! Try again', 'result' => '');
          }
        }else{
          $result = array('status' => false, 'message' => 'Order added fail! Try again', 'result' => '');
        }
      }else{
        $result = array('status' => false, 'message' => 'Somthing Want Wrong! Try Again.', 'result' => '');
      }
      echo json_encode($result);
      exit;
    }
    
    
    
    // 31-08-2018 This ajax is used to add sales estimate order
    if($_REQUEST['action'] == 'addSalesEstimate'){
      if(isset($_REQUEST['data']) && $_REQUEST['data'] != ''){
        $data = [];
        parse_str($_REQUEST['data'], $data);

        if(!empty($data)){
          $length = count($data['customer_id']);
          if($length > 0){
            for ($i=0; $i < $length; $i++) {
              $financial_id = (isset($financial_id) && $financial_id != '') ? $financial_id : NULL;
              $owner_id = (isset($owner_id) && $owner_id != '') ? $owner_id : NULL;
              $admin_id = (isset($admin_id) && $admin_id != '') ? $admin_id : NULL;
              $pharmacy_id = (isset($pharmacy_id) && $pharmacy_id != '') ? $pharmacy_id : NULL;
              $customer_id = (isset($data['customer_id'][$i])) ? $data['customer_id'][$i] : NULL;
              $product_id = (isset($data['product_id'][$i])) ? $data['product_id'][$i] : NULL;
              $qty = (isset($data['qty'][$i]) && $data['qty'][$i] != '') ? $data['qty'][$i] : 0;
              $discount = (isset($data['discount'][$i]) && $data['discount'][$i] != '') ? $data['discount'][$i] : 0;
              $mrp = (isset($data['mrp'][$i]) && $data['mrp'][$i] != '') ? $data['mrp'][$i] : 0;
                
              $query = "INSERT INTO sales_estimate SET financial_id = '".$financial_id."', owner_id = '".$owner_id."', admin_id = '".$admin_id."', pharmacy_id = '".$pharmacy_id."', customer_id = '".$customer_id."', product_id = '".$product_id."', qty = '".$qty."', discount = '".$discount."', mrp = '".$mrp."', created = '".date('Y-m-d H:i:s')."', createdby = '".$_SESSION['auth']['id']."'";
              $res = mysqli_query($conn, $query);
            }
            $result = array('status' => true, 'message' => 'Estimate added successfully.', 'result' => '');
          }else{
            $result = array('status' => false, 'message' => 'Estimate added fail! Try again', 'result' => '');
          }
        }else{
          $result = array('status' => false, 'message' => 'Estimate added fail! Try again', 'result' => '');
        }
      }else{
        $result = array('status' => false, 'message' => 'Somthing Want Wrong! Try Again.', 'result' => '');
      }
      echo json_encode($result);
      exit;
    }

    // 31-08-2018 This ajax is used to get all sales estimate
    if($_REQUEST['action'] == 'getSalesEstimate'){
      $data = [];
        $query = "SELECT se.id, se.qty, se.discount, se.mrp, lgr.name as customer_name, pm.product_name as product_name, pm.ex_date as expiry, pm.batch_no as batch_no FROM sales_estimate se INNER JOIN ledger_master lgr ON se.customer_id = lgr.id INNER JOIN product_master pm ON se.product_id = pm.id WHERE se.pharmacy_id = '".$pharmacy_id."' AND se.financial_id = '".$financial_id."' ORDER BY se.id DESC";
        $res = mysqli_query($conn, $query);
        if($res && mysqli_num_rows($res) > 0){
          $i = 1;
          while ($row = mysqli_fetch_array($res)) {
            $arr['id'] = $row['id'];
            $arr['no'] = $i;
            $arr['customer_name'] = (isset($row['customer_name'])) ? $row['customer_name'] : '';
            $arr['product_name'] = (isset($row['product_name'])) ? $row['product_name'] : '';
            $arr['batch_no'] = (isset($row['batch_no'])) ? $row['batch_no'] : '';
            $arr['expiry'] = (isset($row['expiry']) && $row['expiry'] != '') ? date('d/m/Y',strtotime($row['expiry'])) : '';
            $arr['qty'] = (isset($row['qty'])) ? $row['qty'] : '';
            $arr['mrp'] = (isset($row['mrp'])) ? $row['mrp'] : '';
            $arr['discount'] = (isset($row['discount'])) ? $row['discount'] : '';
            array_push($data, $arr);
            $i++;
          }
        }
        
      $finaldata['data'] = $data;
      echo json_encode($finaldata);
      exit;
    }

    // 31-08-2018 This ajax is used to delete sales estimate
    if($_REQUEST['action'] == 'deleteSalesEstimate'){
      if(isset($_REQUEST['id']) && $_REQUEST['id'] != ''){
        $query = "DELETE FROM sales_estimate WHERE id = '".$_REQUEST['id']."'";
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

     // 31-08-2018 This ajax is used to get edit sales estimate data
    if($_REQUEST['action'] == 'getEditDataForSalesEstimate'){
      if(isset($_REQUEST['id']) && $_REQUEST['id'] != ''){
        $query = "SELECT se.id, se.customer_id, se.product_id, se.qty, se.discount, se.mrp, pm.product_name FROM sales_estimate se INNER JOIN product_master pm ON se.product_id = pm.id WHERE se.id = '".$_REQUEST['id']."' LIMIT 1";
        $res = mysqli_query($conn, $query);
        if($res && mysqli_num_rows($res) > 0){
          $row = mysqli_fetch_array($res);
          $data['id'] = (isset($row['id'])) ? $row['id'] : '';
          $data['customer_id'] = (isset($row['customer_id'])) ? $row['customer_id'] : '';
          $data['product_id'] = (isset($row['product_id'])) ? $row['product_id'] : '';
          $data['product_name'] = (isset($row['product_name'])) ? $row['product_name'] : '';
          $data['qty'] = (isset($row['qty'])) ? $row['qty'] : '';
          $data['discount'] = (isset($row['discount'])) ? $row['discount'] : '';
          $data['mrp'] = (isset($row['mrp'])) ? $row['mrp'] : '';
          $result = array('status' => true, 'message' => 'Data fetched successfully.', 'result' => $data);  
        }else{
          $result = array('status' => false, 'message' => 'Somthing want wrong! Try again.', 'result' => '');  
        }
      }else{
        $result = array('status' => false, 'message' => 'Record not found! Try again.', 'result' => '');
      }
      echo json_encode($result);
      exit;
    }

    // 31-08-2018 This ajax is used to update sales estimate
    if($_REQUEST['action'] == 'updateSalesEstimate'){
      if(isset($_REQUEST['data']) && $_REQUEST['data'] != ''){
        $data = [];
        parse_str($_REQUEST['data'], $data);

        if(!empty($data) && (isset($data['id']) && $data['id'] != '')){
          $customer_id = (isset($data['customer_id'])) ? $data['customer_id'] : NULL;
          $product_id = (isset($data['product_id'])) ? $data['product_id'] : NULL;
          $qty = (isset($data['qty']) && $data['qty'] != '') ? $data['qty'] : 0;
          $discount = (isset($data['discount']) && $data['discount'] != '') ? $data['discount'] : 0;
          $mrp = (isset($data['mrp']) && $data['mrp'] != '') ? $data['mrp'] : 0;

          $query = "UPDATE sales_estimate SET customer_id = '".$customer_id."', product_id = '".$product_id."', qty = '".$qty."', discount = '".$discount."', mrp = '".$mrp."', modified = '".date('Y-m-d H:i:s')."', modifiedby = '".$_SESSION['auth']['id']."' WHERE id = '".$data['id']."'";
          $res = mysqli_query($conn, $query);
          if($res){
            $result = array('status' => true, 'message' => 'Estimate update successfully.', 'result' => mysqli_insert_id($conn));
          }else{
            $result = array('status' => false, 'message' => 'Estimate update fail! Try again', 'result' => '');
          }
        }else{
          $result = array('status' => false, 'message' => 'Estimate update fail! Try again', 'result' => '');
        }
      }else{
        $result = array('status' => false, 'message' => 'Somthing Want Wrong! Try Again.', 'result' => '');
      }
      echo json_encode($result);
      exit;
    }
    
    // 31-08-2018 Thi ajax is used to add new template
    if($_REQUEST['action'] == 'addNewTemplate'){
      if(isset($_REQUEST['data']) && $_REQUEST['data'] != ''){
        $data = [];
        parse_str($_REQUEST['data'], $data);
        
        if(!empty($data) && (isset($data['name']) && $data['name'] != '')){
            $tmpno = '';
            $no = '';

            $lastnoQ = "SELECT no FROM sales_template WHERE pharmacy_id = '".$pharmacy_id."' ORDER BY id DESC LIMIT 1";
            $lastnoR = mysqli_query($conn, $lastnoQ);
            if($lastnoR && mysqli_num_rows($lastnoR)){
              $row = mysqli_fetch_array($lastnoR);
              $tmpno = (isset($row['no']) && $row['no'] != '') ? $row['no'] : '';
            }

            if($tmpno != ''){
              $no = sprintf("%03d", $tmpno+1);
            }else{
              $no = sprintf("%03d", 1);
            }

            $query = "INSERT INTO sales_template SET financial_id = '".$financial_id."', owner_id = '".$owner_id."', admin_id = '".$admin_id."', pharmacy_id = '".$pharmacy_id."', no = '".$no."', name = '".$data['name']."', status = 1, created = '".date('Y-m-d H:i:s')."', createdby = '".$_SESSION['auth']['id']."'";
            $res = mysqli_query($conn, $query);

            if($res){
              $result = array('status' => true, 'message' => 'Template added successfully.', 'result' => mysqli_insert_id($conn));
            }else{
              $result = array('status' => false, 'message' => 'Template added fail! Try again', 'result' => '');
            }
        }else{
          $result = array('status' => false, 'message' => 'Template added fail! Try again', 'result' => '');
        }
      }else{
        $result = array('status' => false, 'message' => 'Somthing Want Wrong! Try Again.', 'result' => '');
      }
      echo json_encode($result);
      exit;
    }

    // 31-08-2018 Thi ajax is used to get all template
    if($_REQUEST['action'] == 'getAllTemplate'){
      $data = [];
        $query = "SELECT * FROM sales_template WHERE pharmacy_id = '".$pharmacy_id."' AND financial_id = '".$financial_id."' ORDER BY id DESC";
        $res = mysqli_query($conn, $query);
        if($res && mysqli_num_rows($res) > 0){
          $i = 1;
          while ($row = mysqli_fetch_array($res)) {
            $arr['status']['id'] = $row['id'];
            $arr['sr_no'] = $i;
            $arr['no'] = (isset($row['no'])) ? $row['no'] : '';
            $arr['name'] = (isset($row['name'])) ? $row['name'] : '';
            $arr['status']['status'] = (isset($row['status'])) ? $row['status'] : '';
            $arr['date'] = (isset($row['created']) && $row['created'] != '') ? date('d/m/Y',strtotime($row['created'])) : '';
            array_push($data, $arr);
            $i++;
          }
        }
        
      $finaldata['data'] = $data;
      echo json_encode($finaldata);
      exit;
    }
    
     // 05-09-2018 This ajax is used to add template detail(order) save to db
    if($_REQUEST['action'] == 'addTemplateDetail'){
      if(isset($_REQUEST['data']) && $_REQUEST['data'] != ''){
        $data = [];
        parse_str($_REQUEST['data'], $data);

        if(!empty($data)){
          $length = count($data['template_id']);
          if($length > 0){
            for ($i=0; $i < $length; $i++) {
              $template_id = (isset($data['template_id'][$i])) ? $data['template_id'][$i] : NULL;
               $customer_id = (isset($data['customer_id'][$i])) ? $data['customer_id'][$i] : NULL;
              $product_id = (isset($data['product_id'][$i])) ? $data['product_id'][$i] : NULL;
              $qty = (isset($data['qty'][$i]) && $data['qty'][$i] != '') ? $data['qty'][$i] : 0;
                
              $query = "INSERT INTO sales_template_detail SET template_id = '".$template_id."',customer_id = '".$customer_id."', product_id = '".$product_id."', qty = '".$qty."', created = '".date('Y-m-d H:i:s')."', createdby = '".$_SESSION['auth']['id']."'";
              $res = mysqli_query($conn, $query);
            }
            $result = array('status' => true, 'message' => 'Product added successfully.', 'result' => '');
          }else{
            $result = array('status' => false, 'message' => 'Product added fail! Try again', 'result' => '');
          }
        }else{
          $result = array('status' => false, 'message' => 'Product added fail! Try again', 'result' => '');
        }
      }else{
        $result = array('status' => false, 'message' => 'Somthing Want Wrong! Try Again.', 'result' => '');
      }
      echo json_encode($result);
      exit;
    }

    // 05-09-2018 This ajax is used to get all template orders
    if($_REQUEST['action'] == 'getAllTemplateDetail'){
      $data = [];
         $query = "SELECT std.id, std.qty, pm.product_name, pm.batch_no,lm.name ,st.name as template_name FROM sales_template_detail std INNER JOIN product_master pm ON std.product_id = pm.id INNER JOIN sales_template st ON std.template_id = st.id   INNER JOIN  ledger_master lm ON std.customer_id = lm.id WHERE st.pharmacy_id = '".$pharmacy_id."' AND st.financial_id = '".$financial_id."' ORDER BY std.id DESC";
        $res = mysqli_query($conn, $query);
        if($res && mysqli_num_rows($res) > 0){
          $i = 1;
          while ($row = mysqli_fetch_array($res)) {
            $arr['no'] = $i;
            $arr['id'] = $row['id'];
             $arr['customer_name'] = (isset($row['name'])) ? $row['name'] : '';
            $arr['template_name'] = (isset($row['template_name'])) ? $row['template_name'] : '';
            $arr['product_name'] = (isset($row['product_name'])) ? $row['product_name'] : '';
            $arr['batch_no'] = (isset($row['batch_no'])) ? $row['batch_no'] : '';
            $arr['qty'] = (isset($row['qty'])) ? $row['qty'] : '';
            array_push($data, $arr);
            $i++;
          }
        }
        
      $finaldata['data'] = $data;
      echo json_encode($finaldata);
      exit;
    }

    // 05-09-2018 This ajax is used to delete sales template order
    if($_REQUEST['action'] == 'deleteTemplateOrder'){
      if(isset($_REQUEST['id']) && $_REQUEST['id'] != ''){
        $query = "DELETE FROM sales_template_detail WHERE id = '".$_REQUEST['id']."'";
        $res = mysqli_query($conn, $query);
        if($res){
          $result = array('status' => true, 'message' => 'Order deleted successfully.', 'result' => '');
        }else{
          $result = array('status' => false, 'message' => 'Order delete fail! Try again.', 'result' => '');
        }
      }else{
        $result = array('status' => false, 'message' => 'Order delete fail! Try again.', 'result' => '');
      }
      echo json_encode($result);
      exit;
    }

    // 05-09-2018 Thi ajax is used to get edit template order data
    if($_REQUEST['action'] == 'getEditDataForTemplateOrder'){
      if(isset($_REQUEST['id']) && $_REQUEST['id'] != ''){

        $query = "SELECT std.id, std.template_id, std.qty,std.customer_id ,std.product_id, pm.product_name,lm.name FROM sales_template_detail std INNER JOIN product_master pm ON std.product_id = pm.id INNER JOIN ledger_master lm ON std.customer_id = lm.id WHERE std.id = '".$_REQUEST['id']."' LIMIT 1";
        $res = mysqli_query($conn, $query);
        if($res && mysqli_num_rows($res) > 0){
          $row = mysqli_fetch_array($res);
          $data['id'] = (isset($row['id'])) ? $row['id'] : '';
          $data['template_id'] = (isset($row['template_id'])) ? $row['template_id'] : '';
          $data['customer_id'] = (isset($row['customer_id'])) ? $row['customer_id'] : '';
          $data['customer_name'] = (isset($row['name'])) ? $row['name'] : '';
          $data['product_id'] = (isset($row['product_id'])) ? $row['product_id'] : '';
          $data['product_name'] = (isset($row['product_name'])) ? $row['product_name'] : '';
          $data['qty'] = (isset($row['qty'])) ? $row['qty'] : '';
          $result = array('status' => true, 'message' => 'Data fetched successfully.', 'result' => $data);  
        }else{
          $result = array('status' => false, 'message' => 'Somthing want wrong! Try again.', 'result' => '');  
        }
      }else{
        $result = array('status' => false, 'message' => 'Record not found! Try again.', 'result' => '');
      }
      echo json_encode($result);
      exit;
    }

    // 05-09-2018 Thi ajax is used to update sales order
    if($_REQUEST['action'] == 'updateTemplateOrder'){
      if(isset($_REQUEST['data']) && $_REQUEST['data'] != ''){
        $data = [];
        parse_str($_REQUEST['data'], $data);

        if(!empty($data) && (isset($data['id']) && $data['id'] != '')){
          $template_id = (isset($data['template_id'])) ? $data['template_id'] : NULL;
          $customer_id = (isset($data['customer_id'])) ? $data['customer_id'] : NULL;
          $product_id = (isset($data['product_id'])) ? $data['product_id'] : NULL;
          $qty = (isset($data['qty']) && $data['qty'] != '') ? $data['qty'] : 0;

          $query = "UPDATE sales_template_detail SET template_id = '".$template_id."', customer_id = '".$customer_id."', product_id = '".$product_id."', qty = '".$qty."', modified = '".date('Y-m-d H:i:s')."', modifiedby = '".$_SESSION['auth']['id']."' WHERE id = '".$data['id']."'";
          $res = mysqli_query($conn, $query);
          if($res){
            $result = array('status' => true, 'message' => 'Order update successfully.', 'result' => mysqli_insert_id($conn));
          }else{
            $result = array('status' => false, 'message' => 'Order update fail! Try again', 'result' => '');
          }
        }else{
          $result = array('status' => false, 'message' => 'Order update fail! Try again', 'result' => '');
        }
      }else{
        $result = array('status' => false, 'message' => 'Somthing Want Wrong! Try Again.', 'result' => '');
      }
      echo json_encode($result);
      exit;
    }
    
    // 06-09-2018 This ajax is used to get all product related to template
    if($_REQUEST['action'] == 'getAllTemplateProduct'){
      $template_id = (isset($_REQUEST['template_id'])) ? $_REQUEST['template_id'] : '';
      $customer_id = (isset($_REQUEST['customer_id'])) ? $_REQUEST['customer_id'] : '';
      if($template_id != ''){
        $query = "SELECT std.qty, pm.id as product_id, pm.product_name FROM sales_template_detail std INNER JOIN product_master pm ON std.product_id = pm.id WHERE std.template_id = '".$template_id."' AND std.customer_id = '".$customer_id."'";
        $res = mysqli_query($conn, $query);
        if($res){
          if(mysqli_num_rows($res) > 0){
            $data = [];
            while ($row = mysqli_fetch_assoc($res)) {
              $productQ = "SELECT * FROM product_master WHERE product_name = '".$row['product_name']."' AND ex_date >= '".date('Y-m-d')."' ORDER BY ex_date";
              $productR = mysqli_query($conn, $productQ);
              if($productR && mysqli_num_rows($productR) > 0){
                  while($productRow = mysqli_fetch_assoc($productR)){
                      $stock = getAllProductWithCurrentStock('', '', 0, [$productRow['id']]);
                      $currentstock = (isset($stock[0]['currentstock']) && $stock[0]['currentstock'] != '') ? $stock[0]['currentstock'] : 0;
                      $ptr = (isset($stock[0]['ptr']) && $stock[0]['ptr'] != '') ? $stock[0]['ptr'] : 0;
                      $rate = (isset($stock[0]['rate']) && $stock[0]['rate'] != '') ? $stock[0]['rate'] : 0;
                      $qty = (isset($row['qty']) && $row['qty'] != '') ? $row['qty'] : 0;
                      if($currentstock > $qty){
                          $detail['id'] = (isset($productRow['id'])) ? $productRow['id'] : 0;
                          $detail['product_name'] = (isset($productRow['product_name'])) ? $productRow['product_name'] : '';
                          $detail['generic_name'] = (isset($productRow['generic_name'])) ? $productRow['generic_name'] : '';
                          $detail['mfg_company'] = (isset($productRow['mfg_company'])) ? $productRow['mfg_company'] : '';
                          $detail['batch_no'] = (isset($productRow['batch_no'])) ? $productRow['batch_no'] : '';
                          $detail['ex_date'] = (isset($productRow['ex_date']) && $productRow['ex_date'] != '') ? date('d/m/Y',strtotime($productRow['ex_date'])) : '';
                          $detail['mrp'] = (isset($productRow['mrp']) && $productRow['mrp'] != '') ? $productRow['mrp'] : 0;
                          $detail['igst'] = (isset($productRow['igst']) && $productRow['igst'] != '') ? $productRow['igst'] : 0;
                          $detail['cgst'] = (isset($productRow['cgst']) && $productRow['cgst'] != '') ? $productRow['cgst'] : 0;
                          $detail['sgst'] = (isset($productRow['sgst']) && $productRow['sgst'] != '') ? $productRow['sgst'] : 0;
                          $detail['ratio'] = (isset($productRow['ratio']) && $productRow['ratio'] != '') ? $productRow['ratio'] : 0;
                          $detail['qty'] = $qty;
                          $detail['discount'] = (isset($productRow['discount']) && $productRow['discount'] != '') ? $productRow['discount'] : 0;
                          if(isset($productRow['discount']) && $productRow['discount'] == 1){
                              $detail['discount_per'] = (isset($productRow['discount_per']) && $productRow['discount_per'] != '' && $productRow['discount_per'] != 0) ? $productRow['discount_per'] : '';
                          }else{
                              $detail['discount_per'] = '';
                          }
                          $detail['ptr'] = number_format($ptr, 2, '.', '');
                          $detail['rate'] = $rate;
                          $detail['stock'] = $currentstock;
                          $data[] = $detail;
                          break;
                      }
                  }
              }
            }
            
            if(!empty($data)){
                $result = array('status' => true, 'message' => 'Product found successfully', 'result' => $data);
            }else{
                $result = array('status' => false, 'message' => 'Product not found in this template!', 'result' => '');
            }
          }else{
            $result = array('status' => false, 'message' => 'Product not found in this template!', 'result' => '');  
          }
        }else{
          $result = array('status' => false, 'message' => 'Somthing want wrong! Try again.', 'result' => '');
        }
      }else{
        $result = array('status' => false, 'message' => 'Please select template!', 'result' => '');
      }
      echo json_encode($result);
      exit;
    }
    
    // 13-09-2018 // ADD NEW DOCTOR
    if($_REQUEST['action'] == "adddoctor"){
      $data = [];
      parse_str($_REQUEST['data'], $data);
      
      if(!empty($data) && (isset($data['doctor_name']) && $data['doctor_name'] != '')){
        $post['financial_id'] = (isset($financial_id)) ? $financial_id : NULL;
        $post['owner_id'] = (isset($owner_id)) ? $owner_id : NULL;
        $post['admin_id'] = (isset($admin_id)) ? $admin_id : NULL;
        $post['pharmacy_id'] = (isset($pharmacy_id)) ? $pharmacy_id : NULL;
        $post['name'] = (isset($data['doctor_name'])) ? $data['doctor_name'] : '';
        $post['mobile'] = (isset($data['mobile_no'])) ? $data['mobile_no'] : '';
        $post['commission'] = (isset($data['doctor_commossion']) && $data['doctor_commossion'] != '') ? $data['doctor_commossion'] : 0;
        $post['country'] = (isset($data['country'])) ? $data['country'] : NULL;
        $post['state'] = (isset($data['state'])) ? $data['state'] : NULL;
        $post['city'] = (isset($data['city'])) ? $data['city'] : NULL;
        $post['address'] = (isset($data['address'])) ? $data['address'] : '';
        $post['pincode'] = (isset($data['pincode'])) ? $data['pincode'] : '';
        //$post['gstno'] = (isset($data['gst'])) ? $data['gst'] : '';
        $post['status'] = 1;

        $query = "INSERT INTO doctor_profile SET";
        foreach ($post as $key => $value) {
          $query .= " ".$key." = '".$value."', ";
        }
        $query .=" created = '".date('Y-m-d H:i:s')."', createdby = '".$_SESSION['auth']['id']."'";
        $res = mysqli_query($conn, $query);
        if($res){
          $inserted_id = mysqli_insert_id($conn);
          $result = array('status' => true, 'message' => 'Doctor added successfully.', 'result' => $inserted_id);
        }else{
          $result = array('status' => false, 'message' => 'Doctor added fail! Try again.', 'result' => '');
        }
      }else{
        $result = array('status' => false, 'message' => 'Some field is requirerd!', 'result' => '');
      }
      echo json_encode($result);
      exit;
    }
    
    // 15-09-2018 calculate customer running balance
    if($_REQUEST['action'] == "customerRunningBalance"){
      if(isset($_REQUEST['id']) && $_REQUEST['id'] != ''){
        $data = countRunningBalance($_REQUEST['id'], '', '', 1);
        $result = array('status' => true, 'message' => 'success', 'result' => $data);
      }else{
        $result = array('status' => false, 'message' => 'id not found!', 'result' => '');
      }
      echo json_encode($result);
      exit;
    }
    
    // 15-09-2018 calculate vendor running balance
    if($_REQUEST['action'] == "vendorRunningBalance"){
      if(isset($_REQUEST['id']) && $_REQUEST['id'] != ''){
        $data = countRunningBalance($_REQUEST['id'], '', '', 1);
        $result = array('status' => true, 'message' => 'success', 'result' => $data);
      }else{
        $result = array('status' => false, 'message' => 'id not found!', 'result' => '');
      }
      echo json_encode($result);
      exit;
    }
    
    // 15-09-2018 calculate perticulars running balance
    if($_REQUEST['action'] == "perticularRunningBalance"){
      if(isset($_REQUEST['id']) && $_REQUEST['id'] != ''){
        $from = (isset($_REQUEST['from']) && $_REQUEST['from'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_REQUEST['from']))) : '';
        $to = (isset($_REQUEST['to']) && $_REQUEST['to'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_REQUEST['to']))) : '';
        
        $data = countRunningBalance($_REQUEST['id'], $from, $to, 1);
        $result = array('status' => true, 'message' => 'success', 'result' => $data);
      }else{
        $result = array('status' => false, 'message' => 'id not found!', 'result' => '');
      }
      echo json_encode($result);
      exit;
    }
    
    // 29-09-2018 calculate cash running balance
    if($_REQUEST['action'] == "cashRunningBalance"){
      if((isset($_REQUEST['from']) && $_REQUEST['from'] != '') && (isset($_REQUEST['to']) && $_REQUEST['to'] != '')){
        $from = date('Y-m-d',strtotime(str_replace("/","-",$_REQUEST['from'])));
        $to = date('Y-m-d',strtotime(str_replace("/","-",$_REQUEST['to'])));
        
        $data = countCashRunningBalance($from, $to, 1);
        $result = array('status' => true, 'message' => 'success', 'result' => $data);
      }else{
        $result = array('status' => false, 'message' => 'id not found!', 'result' => '');
      }
      echo json_encode($result);
      exit;
    }
    
    // 17-09-2018 //FOR SEARCH PRODUCT WITH CURRENT STOCK
    if($_REQUEST['action'] == "searchProductWithExpired"){
      if(isset($_REQUEST['query']) && $_REQUEST['query'] != ''){
        $mrp = (isset($_REQUEST['mrp'])) ? $_REQUEST['mrp'] : '';
        $vendor_id = (isset($_REQUEST['vendor'])) ? $_REQUEST['vendor'] : '';
       
        $data = getAllProductWithCurrentStock('',$_REQUEST['query'], 0, [], '', $mrp, $vendor_id);
        if(!empty($data)){
          $allproduct = [];
            foreach ($data as $key => $value) {
              $arr['id'] = $value['id'];
              $arr['name'] = (isset($value['product_name'])) ? $value['product_name'] : '';
              $arr['batch'] = (isset($value['batch_no']) && $value['batch_no'] != '') ? $value['batch_no'] : '-';
                if(isset($value['ex_date']) && $value['ex_date'] != '' && $value['ex_date'] != '0000-00-00'){
                    $expirydate = date('Y-m-d',strtotime($value['ex_date']));
                    $todaydate = date('Y-m-d');
                    if($todaydate > $expirydate){
                        $arr['expired'] = 1;
                    }else{
                        $arr['expired'] = 0;
                    }
                }else{
                    $arr['expired'] = 0;
                }
              $arr['expiry'] = (isset($value['ex_date']) && $value['ex_date'] != '' && $value['ex_date'] != '0000-00-00') ? date('d/m/Y',strtotime($value['ex_date'])) : '';
              $arr['total_qty'] = ((isset($value['currentstock']) && $value['currentstock'] != '')) ? $value['currentstock'] : 0;
              $arr['unit'] = ((isset($value['unit']) && $value['unit'] != '')) ? $value['unit'] : 0;
              $arr['mrp'] = ((isset($value['mrp']) && $value['mrp'] != '')) ? $value['mrp'] : 0;
              $arr['generic_name'] = (isset($value['generic_name'])) ? $value['generic_name'] : '';
              $arr['mfg_company'] = (isset($value['mfg_company'])) ? $value['mfg_company'] : '';
              $arr['ratio'] = ((isset($value['ratio']) && $value['ratio'] != '')) ? $value['ratio'] : 0;
              $arr['igst'] = ((isset($value['igst']) && $value['igst'] != '')) ? $value['igst'] : 0;
              $arr['cgst'] = ((isset($value['cgst']) && $value['cgst'] != '')) ? $value['cgst'] : 0;
              $arr['sgst'] = ((isset($value['sgst']) && $value['sgst'] != '')) ? $value['sgst'] : 0;
              $arr['rate'] = ((isset($value['purchaserate']) && $value['purchaserate'] != '')) ? $value['purchaserate'] : 0;
              
              /*-----------------------------FIND INWART RATE START-------------------------*/
                $findLastRateQ = "SELECT pd.f_rate as last_rate FROM purchase_details pd INNER JOIN purchase ps ON pd.purchase_id = ps.id WHERE pd.product_id = '".$value['id']."' AND ps.pharmacy_id = '".$pharmacy_id."' ORDER BY ps.id DESC LIMIT 1";
                $findLastRateR = mysqli_query($conn, $findLastRateQ);
                if($findLastRateR && mysqli_num_rows($findLastRateR) > 0){
                    $findLastRateRow = mysqli_fetch_assoc($findLastRateR);
                    $arr['inward_rate'] = (isset($findLastRateRow['last_rate']) && $findLastRateRow['last_rate'] != '') ? $findLastRateRow['last_rate'] : 0;
                }else{
                    $arr['inward_rate'] = (isset($value['inward_rate']) && $value['inward_rate'] != '') ? $value['inward_rate'] : 0;
                }
              /*-----------------------------FIND INWART RATE END---------------------------*/
              $allproduct[] = $arr;
            }
            $result = array('status' => true, 'message' => 'Product found success.', 'result' => $allproduct);
        }else{
          $result = array('status' => false, 'message' => 'Product not found!', 'result' => '');
        }
      }else{
        $result = array('status' => false, 'message' => 'Query not found!', 'result' => '');
      }
      echo json_encode($result);exit;
    }
    
    // 17-09-2018 //FOR SEARCH PRODUCT WITH CURRENT STOCK AND WITHOUT EXPIRED PRODUCT
    if($_REQUEST['action'] == "searchProductWithoutExpired"){
      if(isset($_REQUEST['query']) && $_REQUEST['query'] != ''){
        $mrp = (isset($_REQUEST['mrp'])) ? $_REQUEST['mrp'] : '';
        $data = getAllProductWithCurrentStock('',$_REQUEST['query'],1, [], '', $mrp);
        if(!empty($data)){
          $allproduct = [];
            foreach ($data as $key => $value) {
                
                /// Changes By Kartik /// 
              $with_gst = ($value['mrp'] * $value['igst'])/100;
              $tolal_gst_amount = $value['mrp'] + $with_gst;
              $v = $value['mrp'] * $value['igst'];
              $ptr_a = $v / $tolal_gst_amount;
              $mrp_ptr = $value['mrp'] - $ptr_a;
              $ptr_amount = number_format($mrp_ptr, 2, '.', '') / $value['ratio'];
              
               if($value['discount'] == 1){
                $d_ptr = number_format($ptr_amount, 2, '.', '') * ($value['discount_per'] / 100) ;
                $p_ptr = number_format($ptr_amount, 2, '.', '') - $d_ptr;
                $rate = number_format($p_ptr, 2, '.', '');
                $arr['discount'] = (isset($value['discount_per'])) ? $value['discount_per'] : '';
              }else{
                $rate = number_format($ptr_amount, 2, '.', '');
                $arr['discount'] = '';
              }
              
              /// Changes By Kartik ///
              
              
              $arr['id'] = $value['id'];
              $arr['name'] = (isset($value['product_name'])) ? $value['product_name'] : '';
              $arr['batch'] = (isset($value['batch_no']) && $value['batch_no'] != '') ? $value['batch_no'] : '-';
              $arr['expiry'] = (isset($value['ex_date']) && $value['ex_date'] != '') ? date('d/m/Y',strtotime($value['ex_date'])) : '';
              $arr['total_qty'] = ((isset($value['currentstock']) && $value['currentstock'] != '')) ? $value['currentstock'] : 0;
              $arr['unit'] = ((isset($value['unit']) && $value['unit'] != '')) ? $value['unit'] : 0;
              $arr['mrp'] = ((isset($value['mrp']) && $value['mrp'] != '')) ? $value['mrp'] : 0;
              $arr['generic_name'] = (isset($value['generic_name'])) ? $value['generic_name'] : '';
              $arr['mfg_company'] = (isset($value['mfg_company'])) ? $value['mfg_company'] : '';
              $arr['ratio'] = ((isset($value['ratio']) && $value['ratio'] != '')) ? $value['ratio'] : 0;
              $arr['igst'] = ((isset($value['igst']) && $value['igst'] != '')) ? $value['igst'] : 0;
              $arr['cgst'] = ((isset($value['cgst']) && $value['cgst'] != '')) ? $value['cgst'] : 0;
              $arr['sgst'] = ((isset($value['sgst']) && $value['sgst'] != '')) ? $value['sgst'] : 0;
              $arr['ptr'] = number_format($ptr_amount, 2, '.', '');
              $arr['rate'] = $rate;
              $allproduct[] = $arr;
            }
            $result = array('status' => true, 'message' => 'Product found success.', 'result' => $allproduct);
        }else{
          $result = array('status' => false, 'message' => 'Product not found!', 'result' => '');
        }
      }else{
        $result = array('status' => false, 'message' => 'Query not found!', 'result' => '');
      }
      echo json_encode($result);exit;
    }
    
    // 16-11-2018 //FOR SEARCH PRODUCT WITH CURRENT STOCK AND WITHOUT EXPIRED AND WITHOUT ZERO STOCK
    if($_REQUEST['action'] == "searchProductWithoutExpiredAndWithoutZeroStock"){
      if(isset($_REQUEST['query']) && $_REQUEST['query'] != ''){
        $mrp = (isset($_REQUEST['mrp'])) ? $_REQUEST['mrp'] : '';
        $rate_id = (isset($_REQUEST['rate_id'])) ? $_REQUEST['rate_id'] : '';
        $gst_id = (isset($_REQUEST['gst_id'])) ? $_REQUEST['gst_id'] : '';
        $withgst = (isset($_REQUEST['withgst']) && $_REQUEST['withgst'] != '') ? $_REQUEST['withgst'] : 0;
        $data = getAllProductWithCurrentStock('',$_REQUEST['query'],1, [], '', $mrp, $gst_id, $withgst);
        if(!empty($data)){
          $allproduct = [];
          $rate = 0;
            /*--------------COUNT RATE START-------------*/
            if(isset($rate_id) && $rate_id != ''){
                $rate = getIdByRate($rate_id);
            }
            /*--------------COUNT RATE END--------------*/
          
            foreach ($data as $key => $value) {
                if(isset($value['currentstock']) && $value['currentstock'] > 0){
                  /// Changes By Kartik /// 
                  /*$with_gst = ($value['mrp'] * $value['igst'])/100;
                  $tolal_gst_amount = $value['mrp'] + $with_gst;
                  $v = $value['mrp'] * $value['igst'];
                  $ptr_a = $v / $tolal_gst_amount;
                  $mrp_ptr = $value['mrp'] - $ptr_a;
                  $ptr_amount = number_format($mrp_ptr, 2, '.', '') / $value['ratio'];*/
                  
                   if($value['discount'] == 1){
                    /*$d_ptr = number_format($ptr_amount, 2, '.', '') * ($value['discount_per'] / 100) ;
                    $p_ptr = number_format($ptr_amount, 2, '.', '') - $d_ptr;*/
                    //$rate = number_format($p_ptr, 2, '.', '');
                    $arr['discount'] = (isset($value['discount_per'])) ? $value['discount_per'] : '';
                  }else{
                    //$rate = number_format($ptr_amount, 2, '.', '');
                    $arr['discount'] = '';
                  }
                  
                  /// Changes By Kartik ///
                  
                  
                  $arr['id'] = $value['id'];
                  $arr['name'] = (isset($value['product_name'])) ? $value['product_name'] : '';
                  $arr['batch'] = (isset($value['batch_no']) && $value['batch_no'] != '') ? $value['batch_no'] : '-';
                  $arr['expiry'] = (isset($value['ex_date']) && $value['ex_date'] != '') ? date('d/m/Y',strtotime($value['ex_date'])) : '';
                  $arr['total_qty'] = ((isset($value['currentstock']) && $value['currentstock'] != '')) ? $value['currentstock'] : 0;
                  $arr['unit'] = ((isset($value['unit']) && $value['unit'] != '')) ? $value['unit'] : 0;
                  $arr['mrp'] = ((isset($value['mrp']) && $value['mrp'] != '')) ? $value['mrp'] : 0;
                  $arr['generic_name'] = (isset($value['generic_name'])) ? $value['generic_name'] : '';
                  $arr['mfg_company'] = (isset($value['mfg_company'])) ? $value['mfg_company'] : '';
                  $arr['ratio'] = ((isset($value['ratio']) && $value['ratio'] != '')) ? $value['ratio'] : 0;
                  $arr['igst'] = ((isset($value['igst']) && $value['igst'] != '')) ? $value['igst'] : 0;
                  $arr['cgst'] = ((isset($value['cgst']) && $value['cgst'] != '')) ? $value['cgst'] : 0;
                  $arr['sgst'] = ((isset($value['sgst']) && $value['sgst'] != '')) ? $value['sgst'] : 0;
                  $arr['ptr'] = 0;//number_format($ptr_amount, 2, '.', '');
                  
                  /*-----------------------------FIND INWART RATE START-------------------------*/
                    $findLastRateQ = "SELECT pd.f_rate as last_rate FROM purchase_details pd INNER JOIN purchase ps ON pd.purchase_id = ps.id WHERE pd.product_id = '".$value['id']."' AND ps.pharmacy_id = '".$pharmacy_id."' ORDER BY ps.id DESC LIMIT 1";
                    $findLastRateR = mysqli_query($conn, $findLastRateQ);
                    if($findLastRateR && mysqli_num_rows($findLastRateR) > 0){
                        $findLastRateRow = mysqli_fetch_assoc($findLastRateR);
                        $inward_rate = (isset($findLastRateRow['last_rate']) && $findLastRateRow['last_rate'] != '') ? $findLastRateRow['last_rate'] : 0;
                    }else{
                        $inward_rate = (isset($value['inward_rate']) && $value['inward_rate'] != '') ? $value['inward_rate'] : 0;
                    }
                  /*-----------------------------FIND INWART RATE END---------------------------*/
                  
                  if(isset($rate) && $rate != '' && $rate != 0){
                      $tmprate = $inward_rate*$rate/100;
                      $arr['rate'] = $inward_rate+$tmprate;
                  }else{
                    //   $arr['rate'] = $inward_rate;
                      $arr['rate'] = (isset($value['sale_price']) && $value['sale_price'] != '') ? $value['sale_price'] : 0;
                  }
                  
                  $allproduct[] = $arr;
                }
            }
            if(isset($allproduct) && !empty($allproduct)){
                $result = array('status' => true, 'message' => 'Product found success.', 'result' => $allproduct);   
            }else{
                $result = array('status' => false, 'message' => 'Product not found!', 'result' => '');
            }
        }else{
          $result = array('status' => false, 'message' => 'Product not found!', 'result' => '');
        }
      }else{
        $result = array('status' => false, 'message' => 'Query not found!', 'result' => '');
      }
      echo json_encode($result);exit;
    }
    
    /*----09-10-2018 Get last 6 bill all product--------*/
    if($_REQUEST['action'] == "ShowCustomerBill"){
      $customer_id = (isset($_REQUEST['customer_id'])) ? $_REQUEST['customer_id'] : '';
      if($customer_id != ''){
        $data = [];
        $query = "SELECT id FROM tax_billing WHERE pharmacy_id = '".$pharmacy_id."' AND financial_id = '".$financial_id."' AND customer_id = '".$customer_id."' ORDER BY id DESC LIMIT 6";
        $res = mysqli_query($conn, $query);
        if($res && mysqli_fetch_assoc($res) > 0){
          while ($row = mysqli_fetch_assoc($res)) {
            $subquery = "SELECT tbd.*, pm.product_name FROM tax_billing_details tbd INNER JOIN product_master pm ON tbd.product_id = pm.id WHERE tbd.tax_bill_id = '".$row['id']."'";
            $subres = mysqli_query($conn, $subquery);
            if($subres && mysqli_num_rows($res) > 0){
              while ($subrow = mysqli_fetch_assoc($subres)) {
                if(isset($subrow['expiry']) && $subrow['expiry'] != ''){
                    $expirydate = date('Y-m-d',strtotime($subrow['expiry']));
                    $todaydate = date('Y-m-d');
                    if($todaydate > $expirydate){
                        $subrow['expired'] = 1;
                    }else{
                        $subrow['expired'] = 0;
                    }
                }else{
                    $subrow['expired'] = 0;
                }
                  
                $stock = getAllProductWithCurrentStock('', '', 0, [$subrow['product_id']]);
                $subrow['currentstock'] = (isset($stock[0]['currentstock']) && $stock[0]['currentstock'] != '') ? $stock[0]['currentstock'] : 0;
                $subrow['expiry'] = (isset($subrow['expiry']) && $subrow['expiry'] != '') ? date('d/m/Y',strtotime($subrow['expiry'])) : '';
                
                $data[] = $subrow;
              }
            }
          }
        }
        if(!empty($data)){
          $result = array('status' => true, 'message' => 'Data found success.', 'result' => $data);
        }else{
          $result = array('status' => false, 'message' => 'Data Not found!', 'result' => '');
        }
      }else{
        $result = array('status' => false, 'message' => 'Customer ID Not found!', 'result' => '');
      }
      echo json_encode($result);exit;
    }
    
    /*----25-10-2018 Get last 3 purchase bill all product--------*/
    if($_REQUEST['action'] == "getLastPurchaseBill"){
        $vendor_id = (isset($_REQUEST['vendor_id'])) ? $_REQUEST['vendor_id'] : '';
        if($vendor_id != ''){
          $data = [];

          $query = "SELECT id FROM purchase WHERE pharmacy_id = '".$pharmacy_id."' AND financial_id = '".$financial_id."' AND vendor = '".$vendor_id."' ORDER BY id DESC LIMIT 3";
          $res = mysqli_query($conn , $query);
          if($res && mysqli_num_rows($res) > 0){
            while ($row = mysqli_fetch_assoc($res)) {
              $subquery = "SELECT pd.*, pm.product_name, pm.ex_date FROM purchase_details pd INNER JOIN product_master pm ON pd.product_id = pm.id WHERE pd.purchase_id = '".$row['id']."'";
              $subres = mysqli_query($conn, $subquery);
              if($subres && mysqli_num_rows($subres) > 0){
                while ($subrow = mysqli_fetch_assoc($subres)) {

                  if(isset($subrow['ex_date']) && $subrow['ex_date'] != ''){
                      $expirydate = date('Y-m-d',strtotime($subrow['ex_date']));
                      $todaydate = date('Y-m-d');
                      if($todaydate > $expirydate){
                          $subrow['expired'] = 1;
                      }else{
                          $subrow['expired'] = 0;
                      }
                  }else{
                      $subrow['expired'] = 0;
                  }
                  $subrow['expiry'] = (isset($subrow['expiry']) && $subrow['expiry'] != '') ? date('d/m/Y',strtotime($subrow['expiry'])) : '';
                  $data[] = $subrow;
                }
              }
            }
          }
          if(isset($data) && !empty($data)){
            $result = array('status' => true, 'message' => 'Data found success!', 'result' => $data);
          }else{
            $result = array('status' => false, 'message' => 'Data Not found!', 'result' => '');  
          }
        }else{
          $result = array('status' => false, 'message' => 'Vendor ID Not found!', 'result' => '');
        }
      echo json_encode($result);
      exit;
    }
    
    if($_REQUEST['action'] == "addOrderReminder"){
        $vendor_id = (isset($_REQUEST['vendor_id'])) ? $_REQUEST['vendor_id'] : '';
        $groups = (isset($_REQUEST['groups'])) ? $_REQUEST['groups'] : '';
        $day = (isset($_REQUEST['day']) && $_REQUEST['day'] != '') ? $_REQUEST['day'] : 0;
        $type = (isset($_REQUEST['type'])) ? $_REQUEST['type'] : '';
        $date = date('Y-m-d', strtotime('+'.$day.' days'));
        

        if($vendor_id != '' && $groups != '' && $type != ''){
          $existQ = "SELECT id FROM order_reminder WHERE vendor_id = '".$vendor_id."' AND groups = '".$groups."' AND type = '".$type."' LIMIT 1";
          $existR = mysqli_query($conn, $existQ);
          if($existR && mysqli_num_rows($existR) > 0){
            $query = "UPDATE order_reminder SET day = '".$day."', date = '".$date."', modified = '".date('Y-m-d H:i:s')."', modifiedby = '".$_SESSION['auth']['id']."'";
          }else{
            $query = "INSERT INTO order_reminder SET financial_id = '".$financial_id."', owner_id = '".$owner_id."', admin_id = '".$admin_id."', pharmacy_id = '".$pharmacy_id."', vendor_id = '".$vendor_id."', groups = '".$groups."', type = '".$type."', day = '".$day."', date = '".$date."', created = '".date('Y-m-d H:i:s')."', createdby = '".$_SESSION['auth']['id']."'";
          }
          $res = mysqli_query($conn, $query);

          if($res){
            $result = array('status' => true, 'message' => 'Reminder added successfully.', 'result' => '');
          }else{
            $result = array('status' => false, 'message' => 'Reminder added fail!', 'result' => '');
          }
        }else{
          $result = array('status' => false, 'message' => 'Some value is not found!', 'result' => '');
        }
        echo json_encode($result);exit;
    }
    
     if($_REQUEST['action'] == "cancelOrder"){
        $id = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : '';
        if($id != ''){
          $query = "UPDATE order_reminder SET status = 1 WHERE id = '".$id."'";
          $res = mysqli_query($conn, $query);
          if($res){
            $result = array('status' => true, 'message' => 'Order Cancel success.', 'result' => '');
          }else{
            $result = array('status' => false, 'message' => 'Order Cancel fail!', 'result' => '');  
          }
        }else{
          $result = array('status' => false, 'message' => 'Id not found!', 'result' => '');
        }
        echo json_encode($result);exit;
    }
    
    if($_REQUEST['action'] == "cancelSaleOrder"){
        $id = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : '';
        if($id != ''){
          $query = "UPDATE sales_order_reminder SET status = 1 WHERE id = '".$id."'";
          $res = mysqli_query($conn, $query);
          if($res){
            $result = array('status' => true, 'message' => 'Order Cancel success.', 'result' => '');
          }else{
            $result = array('status' => false, 'message' => 'Order Cancel fail!', 'result' => '');  
          }
        }else{
          $result = array('status' => false, 'message' => 'Id not found!', 'result' => '');
        }
        echo json_encode($result);exit;
    }

    if($_REQUEST['action'] == "resendorder"){
        $id = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : '';
        $day = (isset($_REQUEST['day'])) ? $_REQUEST['day'] : 0;
        if($id != ''){
          $query = "SELECT orm.*, lgr.name as vendor_name, lgr.email as vendor_email FROM order_reminder orm INNER JOIN ledger_master lgr ON orm.vendor_id = lgr.id WHERE orm.id = '".$id."'";
          $res = mysqli_query($conn, $query);
          if($res && mysqli_num_rows($res) > 0){
            $data = mysqli_fetch_assoc($res);
            // $day = (isset($data['day'])) ? $data['day'] : 0;
            $vendor_email = (isset($data['vendor_email'])) ? $data['vendor_email'] : '';
            if($vendor_email != ''){
              $subquery = "SELECT ord.*, pm.product_name FROM orders ord INNER JOIN product_master pm ON ord.product_id = pm.id WHERE ord.vendor_id = '".$data['vendor_id']."' AND ord.groups = '".$data['groups']."' AND ord.type = '".$data['type']."'";
              $subres = mysqli_query($conn, $subquery);
              if($subres && mysqli_num_rows($subres) > 0){
                $html = "<center><h3>Digibook Order Summary</h3><table border='1' cellpadding='10' cellspacing='0'><thead><th>Sr. No</th><th>Product Name</th><th>Purchase Price</th><th>GST(%)</th><th>Unit</th><th>Qty</th></thead><tbody>";
                $count = 1;
                while ($row = mysqli_fetch_assoc($subres)) {
                  $html .= "<tr>";
                  $html .= "<td>".$count."</td>";
                  $html .= "<td>".$row['product_name']."</td>";
                  $html .= "<td>".$row['purchase_price']."</td>";
                  $html .= "<td>".$row['gst']."</td>";
                  $html .= "<td>".$row['unit']."</td>";
                  $html .= "<td>".$row['qty']."</td>";

                  $html .= "<tr/>";
                  $count++;
                }
                $html .="</tbody></table></center>";
                $r = smtpmail($vendor_email, '', '', 'Digibook Order Reminder', $html, '', '');
                if($r){
                  $date = date('Y-m-d', strtotime('+'.$day.' days'));
                  mysqli_query($conn, "UPDATE order_reminder SET date = '".$date."', day = '".$day."' WHERE id = '".$data['id']."'");

                  $result = array('status' => true, 'message' => 'Order resend success.', 'result' => '');  
                }else{
                  $result = array('status' => false, 'message' => 'Order resend Fail!', 'result' => '');  
                }
              }else{
                $result = array('status' => false, 'message' => 'Order not found!', 'result' => '');  
              }
            }else{
              $result = array('status' => false, 'message' => 'Vendor email not found!', 'result' => '');    
            }
          }else{
            $result = array('status' => false, 'message' => 'Id not found!', 'result' => '');  
          }
        }else{
          $result = array('status' => false, 'message' => 'Id not found!', 'result' => '');
        }
        echo json_encode($result);exit;
    }
    
    if($_REQUEST['action'] == "resendSaleorder"){
        $id = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : '';
        $day = (isset($_REQUEST['day'])) ? $_REQUEST['day'] : 0;
        if($id != ''){
          $query = "SELECT sor.*, lgr.name as customer_name, lgr.email as customer_email FROM sales_order_reminder sor INNER JOIN ledger_master lgr ON sor.customer_id = lgr.id WHERE sor.id = '".$id."'";
          $res = mysqli_query($conn, $query);
          if($res && mysqli_num_rows($res) > 0){
            $data = mysqli_fetch_assoc($res);
            // $day = (isset($data['day'])) ? $data['day'] : 0;
            $customer_email = (isset($data['customer_email'])) ? $data['customer_email'] : '';
            if($customer_email != ''){
              $subquery = "SELECT so.id, so.product_id, so.qty, so.discount, so.mrp, pm.product_name FROM sales_order so INNER JOIN product_master pm ON so.product_id = pm.id WHERE so.customer_id = '".$data['customer_id']."' AND so.groups = '".$data['groups']."'";
              $subres = mysqli_query($conn, $subquery);
              if($subres && mysqli_num_rows($subres) > 0){

                $html = "<center><h3>Digibook Order Summary</h3><table border='1' cellpadding='10' cellspacing='0'><thead><th>Sr. No</th><th>Product Name</th><th>Qty</th><th>Discount(%)</th><th>MRP</th></thead><tbody>";
                $count = 1;
                while ($row = mysqli_fetch_assoc($subres)) {
                  $html .= "<tr>";
                  $html .= "<td>".$count."</td>";
                  $html .= "<td>".$row['product_name']."</td>";
                  $html .= "<td>".$row['qty']."</td>";
                  $html .= "<td>".$row['discount']."</td>";
                  $html .= "<td>".$row['mrp']."</td>";

                  $html .= "<tr/>";
                  $count++;
                }
                $html .="</tbody></table></center>";
                $r = smtpmail($customer_email, '', '', 'Digibook Order Reminder', $html, '', '');
                if($r){
                  $date = date('Y-m-d', strtotime('+'.$day.' days'));
                  mysqli_query($conn, "UPDATE sales_order_reminder SET date = '".$date."', day = '".$day."' WHERE id = '".$data['id']."'");

                  $result = array('status' => true, 'message' => 'Order resend success.', 'result' => '');  
                }else{
                  $result = array('status' => false, 'message' => 'Order resend Fail!', 'result' => '');  
                }
              }else{
                $result = array('status' => false, 'message' => 'Order not found!', 'result' => '');  
              }
            }else{
              $result = array('status' => false, 'message' => 'Vendor email not found!', 'result' => '');    
            }
          }else{
            $result = array('status' => false, 'message' => 'Id not found!', 'result' => '');  
          }
        }else{
          $result = array('status' => false, 'message' => 'Id not found!', 'result' => '');
        }
        echo json_encode($result);exit;
    }
    
    /*---------------------ITEM REGISTRATION REPORT AJAX START - GAUTAM MAKWANA - 26-10-2018------------------------*/
    if($_REQUEST['action'] == "getProductForItemRegistration"){
      // product_by = 0 => mrp wise and 1 = all product
      $product_by = (isset($_REQUEST['product_by'])) ? $_REQUEST['product_by'] : '';
      $mrpAmount = (isset($_REQUEST['mrp']) && $_REQUEST['mrp'] != '') ? $_REQUEST['mrp'] : 0;
      if($product_by != ''){
        $data = [];
          $query = "SELECT p.id, p.product_name, p.batch_no, p.mrp, cm.name as c_name, cm.code as c_code FROM product_master p LEFT JOIN company_master cm ON p.company_code = cm.id WHERE p.pharmacy_id = '".$pharmacy_id."' ";
          if($product_by == 0){
            $query .= "AND p.mrp = '".$mrpAmount."' ";
          }
          $query .= "GROUP BY p.product_name ORDER BY p.product_name";
          $res = mysqli_query($conn, $query);
          if($res && mysqli_num_rows($res) > 0){
            while ($row = mysqli_fetch_assoc($res)) {
                $company_code = (isset($row['c_code']) && $row['c_code'] != '') ? $row['c_code'].' - ' : '';
                $row['product_name'] = $company_code.''.$row['product_name'];
                $data[] = $row;
            }
          }

          if(!empty($data)){
            $result = array('status' => true, 'message' => 'Item found success', 'result' => $data);
          }else{
            $result = array('status' => false, 'message' => 'Item not found!', 'result' => '');    
          }
      }else{
        $result = array('status' => false, 'message' => 'Item not found!', 'result' => '');
      }
      echo json_encode($result);exit;
    }

    if($_REQUEST['action'] == 'getAllBatchByProduct'){
      $product_id = (isset($_REQUEST['product_id'])) ? $_REQUEST['product_id'] : '';
      if($product_id != ''){
        $query = "SELECT id, product_name FROM product_master WHERE id = '".$product_id."' LIMIT 1";
        $res = mysqli_query($conn, $query);
        if($res && mysqli_num_rows($res) > 0){
            $row = mysqli_fetch_assoc($res);
            $product_name = (isset($row['product_name'])) ? $row['product_name'] : '';

            $subQuery = "SELECT id, product_name, batch_no, ex_date FROM product_master WHERE product_name = '".$product_name."' ORDER BY batch_no";
            $subRes = mysqli_query($conn, $subQuery);
            if($subRes && mysqli_num_rows($subRes) > 0){
              $data = [];
              while ($subRow = mysqli_fetch_assoc($subRes)) {
                $subRow['ex_date'] = (isset($subRow['ex_date']) && $subRow['ex_date'] != '') ? date('d/m/Y',strtotime($subRow['ex_date'])) : '';
                $stock = getAllProductWithCurrentStock('','',0,[$subRow['id']]);
                $subRow['currentstock'] = (isset($stock[0]['currentstock']) && $stock[0]['currentstock'] != '') ? $stock[0]['currentstock'] : 0;
                $data[] = $subRow;
              }
              $result = array('status' => true, 'message' => 'Product found successfully.', 'result' => $data);
            }else{
              $result = array('status' => false, 'message' => 'Product not found!', 'result' => '');
            }
        }else{
          $result = array('status' => false, 'message' => 'Product not found!', 'result' => '');
        }
      }else{
        $result = array('status' => false, 'message' => 'Product id not found!', 'result' => '');
      }
      echo json_encode($result);exit;
    }
    /*---------------------ITEM REGISTRATION REPORT AJAX END - GAUTAM MAKWANA - 26-10-2018--------------------------*/
    
    
    if($_REQUEST['action'] == 'getDoctorPurchaseRunnningBalance'){
        if(isset($_REQUEST['id']) && $_REQUEST['id'] != ''){
            $from = (isset($_REQUEST['from']) && $_REQUEST['from'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_REQUEST['from']))) : '';
            $to = (isset($_REQUEST['to']) && $_REQUEST['to'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_REQUEST['to']))) : '';
        
            $data = runningBalanceForDoctorPurchase($from, $to, $_REQUEST['id']);
            $result = array('status' => true, 'message' => 'success', 'result' => $data);
        }else{
            $result = array('status' => false, 'message' => 'id not found!', 'result' => '');
        }
        echo json_encode($result);
        exit;
    }
    
    if($_REQUEST['action'] == 'addGeneralCustomer'){
        if(isset($_REQUEST['data']) && $_REQUEST['data'] != ''){
            $data = [];
            parse_str($_REQUEST['data'], $data);
            
            $name = (isset($data['customer_name'])) ? $data['customer_name'] : '';
            $mobile = (isset($data['customer_mobile'])) ? $data['customer_mobile'] : '';
            $city = (isset($data['customer_city'])) ? $data['customer_city'] : '';
            
            $query = "INSERT INTO ledger_master SET financial_id = '".$financial_id."', owner_id = '".$owner_id."', admin_id = '".$admin_id."', pharmacy_id = '".$pharmacy_id."', account_type = 1, name = '".$name."', mobile = '".$mobile."', city = '".$city."', opening_balance = 0, opening_balance_type = 'DB', group_id = 29, status = 1, created = '".date('Y-m-d H:i:s')."', createdby = '".$_SESSION['auth']['id']."'";
            $res = mysqli_query($conn, $query);
            if($res){
                $data['id'] = mysqli_insert_id($conn);
                $data['name'] = $name;
                $data['city'] = $city;
                $data['mobile'] = $mobile;
                $data['state_code'] = $_SESSION['state_code'];
                $result = array('status' => true, 'message' => 'General Customer added successfully.', 'result' => $data);
            }else{
                $result = array('status' => false, 'message' => 'General Customer added fail!', 'result' => '');
            }
        }else{
            $result = array('status' => false, 'message' => 'Somthing Want Wrong!', 'result' => '');
        }
        echo json_encode($result);
        exit;
    }
    
    if($_REQUEST['action'] == "searchCustomerForSale"){
        $query = (isset($_REQUEST['query'])) ? $_REQUEST['query'] : '';
        $field = (isset($_REQUEST['field'])) ? $_REQUEST['field'] : '';
        $city_id = (isset($_REQUEST['city'])) ? $_REQUEST['city'] : '';
        
        if($query != '' && $field != ''){
            $customerQ = "SELECT l.id, l.name, l.mobile, l.email,l.crlimit, l.customer_id, l.city,l.salesman_id, l.rate_id, st.state_code_gst as statecode FROM ledger_master l LEFT JOIN own_states st ON l.state = st.id WHERE l.pharmacy_id = '".$pharmacy_id."' AND l.group_id = 10 AND l.status = 1 ";
            if($city_id != ''){
                $customerQ .= " AND l.city = '".$city_id."' ";
            }
            $customerQ .= "AND l.".$field." LIKE '%".$query."%' ";
            $customerR = mysqli_query($conn, $customerQ);
            if($customerR && mysqli_num_rows($customerR) > 0){
                $data = [];
                while($row = mysqli_fetch_assoc($customerR)){
                    if(empty($row['crlimit']) || $row['crlimit'] == 0){
                        $limit_total = "0";    
                    }else{
                        $limit_total = CheckCr($row['id']);
                    }
                    
                    $arr['id'] = $row['id'];
                    $arr['name'] = $row['name'];
                    $arr['mobile'] = $row['mobile'];
                    $arr['email'] = $row['email'];
                    $arr['state'] = $row['statecode'];
                    $arr['city'] = $row['city'];
                    $arr['salesman_id'] = $row['salesman_id'];
                    $arr['rate_id'] = (isset($row['rate_id'])) ? $row['rate_id'] : '';
                    $arr['limit_total'] = $limit_total;
                    if(isset($row['customer_id']) && $row['customer_id'] != ''){
                        $arr['customer_id'] = $row['customer_id'];
                        $arr['temp_customer'] = $row['customer_id'];
                    }else{
                        $arr['customer_id'] = '';
                        $arr['temp_customer'] = getcustomerID();
                    }
                    $data[] = $arr;
                }
                $result = array('status' => true, 'message' => 'success found!', 'result' => $data);
            }else{
                $result = array('status' => false, 'message' => 'not found!', 'result' => '');
            }
        }else{
            $result = array('status' => false, 'message' => 'not found!', 'result' => '');
        }
        echo json_encode($result);
        exit;
    }

    /*--------------------------------------------VIRAG BHAI AJAX START-----------------------------------------------------------------------------*/
    // Date   : 11-08-18
    if($_REQUEST['action'] == "getAutoSearchOrderList"){
      $search = (isset($_REQUEST['query']['term'])) ? $_REQUEST['query']['term'] : '';
      $type = (isset($_REQUEST['type'])) ? $_REQUEST['type'] : '';
      $vender_id = (isset($_REQUEST['vender_id'])) ? $_REQUEST['vender_id'] : '';
      $data = [];
        if($type != '' && $type != ''){
          if(isset($type) && ($type == 'mobile' || $type == 'email')){
            $field = ($type == 'email') ? 'email' : 'mobile';
            if($_REQUEST['vender_id'] == ''){
              $query = "SELECT DISTINCT lgr.".$field." as name, lgr.id as id  FROM orders ord INNER JOIN ledger_master lgr ON ord.vendor_id = lgr.id WHERE ord.pharmacy_id = '".$pharmacy_id."' AND ord.financial_id = '".$financial_id."' AND ord.status = 1 AND lgr.".$field." LIKE '%".$search."%' ORDER BY ".$field;
            }else{
              $query = "SELECT DISTINCT lgr.".$field." as name, lgr.id as id  FROM orders ord INNER JOIN ledger_master lgr ON ord.vendor_id = lgr.id WHERE ord.pharmacy_id = '".$pharmacy_id."' AND ord.financial_id = '".$financial_id."' AND ord.status = 1 AND lgr.id='".$vender_id."' AND lgr.".$field." LIKE '%".$search."%' ORDER BY ".$field;
            }
          }elseif(isset($type) && $type == 'orderno'){
            if($_REQUEST['vender_id'] == ''){
              $query = "SELECT id, order_no as name FROM orders WHERE pharmacy_id = '".$pharmacy_id."' AND financial_id = '".$financial_id."' AND status = 1 AND order_no LIKE '%".$search."%' ORDER BY order_no";
            }else{
              $query = "SELECT id, order_no as name FROM orders WHERE pharmacy_id = '".$pharmacy_id."' AND financial_id = '".$financial_id."' AND status = 1 AND vendor_id='".$vender_id."' AND order_no LIKE '%".$search."%' ORDER BY order_no";
            }
          }

          if(isset($query) && $query != ''){
            $res = mysqli_query($conn, $query);
            if($res && mysqli_num_rows($res) > 0){
              while($row = mysqli_fetch_array($res)){
                $arr['id'] = (isset($row['id'])) ? $row['id'] : '';
                $arr['name'] = (isset($row['name'])) ? $row['name'] : '';
                array_push($data, $arr);
              }
            }
          }
        }
        echo json_encode($data);
        exit; 
    }
    
    if($_REQUEST['action'] == "getgroup"){
      $group_id = (isset($_REQUEST['group_id'])) ? $_REQUEST['group_id'] : '';
    
      	if($group_id != ''){
      	    $p_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : '';
            $query = 'SELECT id, name FROM `ledger_master` WHERE group_id = '.$group_id.' AND pharmacy_id = '.$p_id.' AND status=1 AND is_cash = 0 order by name';
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


    if($_REQUEST['action'] == "getcash"){
        $cash = (isset($_REQUEST['cash'])) ? $_REQUEST['cash'] : '';
      	$voucher_no = getaccountingcashinvoiceno($cash);
      
      $result = array('status' => true, 'message' => 'Success', 'result' => $voucher_no);
      echo json_encode($result);
      exit;
        }
    
        /*if($cash == "cash_receipt"){
            $voucher_no = '';
            $p_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : '';
        
            $voucherqry = "SELECT voucher_no FROM accounting_cash_management WHERE payment_type = 'cash_receipt' AND `pharmacy_id` = '".$p_id."' AND financial_id = '".$financial_id."' ORDER BY id DESC LIMIT 1";
             $voucherrun = mysqli_query($conn, $voucherqry);
             if($voucherrun){
               $count = mysqli_num_rows($voucherrun);
               if($count !== '' && $count !== 0){
                 $row = mysqli_fetch_assoc($voucherrun);
                 $voucherno = (isset($row['voucher_no'])) ? $row['voucher_no'] : '';
        
                 if($voucherno != ''){
                   $vouchernoarr = explode('-',$voucherno);
                   $voucherno = $vouchernoarr[1];
                   $voucherno = $voucherno + 1;
                   $voucherno = sprintf("%05d", $voucherno);
                   $voucher_no = 'CR-'.$voucherno;
                 }
               }else{
                 $voucherno = sprintf("%05d", 1);
                 $voucher_no = 'CR-'.$voucherno;
               }
            }
            echo $voucher_no;
            exit;
        } */
        

    if($_REQUEST['action'] == "getstate"){
        $state = (isset($_REQUEST['state'])) ? $_REQUEST['state'] : '';
        
        $stateqry = "SELECT state FROM  ledger_master WHERE id = '".$state."'";
        $staterun = mysqli_query($conn, $stateqry);
        $statedata = mysqli_fetch_assoc($staterun);
        $vendorstate = $statedata['state'];
        
        if($vendorstate != ''){
    	    $state = "SELECT state_code_gst FROM own_states WHERE id = '".$vendorstate."'";
    	    $run = mysqli_query($conn, $state);
    	    $data = mysqli_fetch_assoc($run);
    	    $stategst = $data['state_code_gst'];
    	    echo $stategst;exit;
    	}
    }
    
     // 30-08-2018 // This ajax is used to cancel purchase history bill
    if($_REQUEST['action'] == 'purchasecancelbill'){
      if(isset($_REQUEST['id']) && $_REQUEST['id'] != ''){
        $query = "UPDATE purchase SET cancel = 0 WHERE id = '".$_REQUEST['id']."'";
        $res = mysqli_query($conn, $query);
        if($res){
          $result = array('status' => true, 'message' => 'Bill Cancel successfully.', 'result' => '');
        }else{
          $result = array('status' => false, 'message' => 'Bill Cancel fail!', 'result' => '');  
        }
      }else{
        $result = array('status' => false, 'message' => 'Bill id not found!', 'result' => '');
      }
      echo json_encode($result);
      exit;
    }
    
    //CHANGES VOUCHER NUMBER FOR ACCOUNTING-CHEQUE.PHP CREATED BY:- VIRAG RAKHOLIYA

    if($_REQUEST['action'] == "getvoucher"){
        $voucher = (isset($_REQUEST['voucher'])) ? $_REQUEST['voucher'] : '';
        $no = getaccountingchequevoucher($voucher);
        echo $no;exit;
    }

// AJAX FOR CHANGE VOUCHER NUMBER IN ADD-PAYMENT-VOUCHER.PHP CREATED BY VIRAG RAKHOLIYA
if($_REQUEST['action'] == "gettax"){
  $tax = (isset($_REQUEST['tax'])) ? $_REQUEST['tax'] : '';
  $voucher_no = getpaymentvoucher($tax);
      
  $result = array('status' => true, 'message' => 'Success', 'result' => $voucher_no);
  echo json_encode($result);
  exit;
  }
  
  
  if($_REQUEST['action'] == "bankrunningbalance"){
      if(isset($_REQUEST['id']) && $_REQUEST['id'] != ''){
        $from = (isset($_REQUEST['from']) && $_REQUEST['from'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_REQUEST['from']))) : '';
        $to = (isset($_REQUEST['to']) && $_REQUEST['to'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_REQUEST['to']))) : '';
        $data = bankrunningbalance($_REQUEST['id'], $from, $to);
        $result = array('status' => true, 'message' => 'success', 'result' => $data);
      }else{
        $result = array('status' => false, 'message' => 'id not found!', 'result' => '');
      }
      echo json_encode($result);
      exit;
    }
    
    
    if($_REQUEST['action'] == "dailysalesrunningbalance"){
        if((isset($_REQUEST['to']) && $_REQUEST['to'] != '') && (isset($_REQUEST['from']) && $_REQUEST['from'] != '')){
        $from = (isset($_REQUEST['from']) && $_REQUEST['from'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_REQUEST['from']))) : '';
        $to = (isset($_REQUEST['to']) && $_REQUEST['to'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_REQUEST['to']))) : '';
        
        $data = salesrunningbalance($from, $to);
        $result = array('status' => true, 'message' => 'success', 'result' => $data);
      }else{
        $result = array('status' => false, 'message' => 'not found!', 'result' => '');
      }
      echo json_encode($result);
      exit;
    }
    
    
    if($_REQUEST['action'] == "partyaddress"){
    $id = $_REQUEST['id'];


    $returnaddress = "";
    $addressqry = "SELECT ct.address , ct.address_line1 , ct.address_line2 , ct.mobile_no , ct.pincode , s.name as statename, c.name as cityname FROM courier_transport ct JOIN own_states s ON ct.state = s.id JOIN own_cities c ON ct.city = c.id WHERE ct.id = '".$id."'";
    $addressrun = mysqli_query($conn,$addressqry); 
    if(mysqli_num_rows($addressrun) > 0){
    $partyaddress = mysqli_fetch_assoc($addressrun);
   
   $returnaddress .= "
   
   <p> ".$partyaddress['address']."</p>
   <p>".$partyaddress['address_line1']."</p>
   <p> ".$partyaddress['address_line2']."</p>
   <p>".$partyaddress['cityname']." - ".$partyaddress['pincode'].",&nbsp;
   <p>".$partyaddress['statename']." &nbsp;</p>
   <p> <abbr title='Phone'>M : </abbr>".$partyaddress['mobile_no']."</p>
   ";
   
    }else{
    $returnaddress .= "<span class='address-head mb-5'>No address available</span>";
    }
    echo json_encode(array('error'=>0,'message'=>'','address'=>$returnaddress));
    exit();
    }
    
    
    if($_REQUEST['action'] == "courier_amount"){
    $id = $_REQUEST['id'];


    $Qtotl = "SELECT SUM(taxable_value) AS total FROM courier_payment WHERE party = '".$id."' AND pharmacy_id = '".$pharmacy_id."' AND  financial_id = '".$financial_id."'" ;
    $toResult = mysqli_query($conn,$Qtotl);
    $rcmvalue = mysqli_fetch_assoc($toResult);
    $totalAmount = $rcmvalue['total'];
  
    if($totalAmount == NULL){
    $totalAmount = 0;
    }
    $totalAmount = number_format($totalAmount, 2, '.', ''); 


    $returnAddress = "";
    $address = "SELECT ct.address , ct.address_line1 , ct.address_line2 , ct.mobile_no , ct.pincode , s.name as statename, c.name as cityname FROM courier_transport ct JOIN own_states s ON ct.state = s.id JOIN own_cities c ON ct.city = c.id WHERE ct.id = '".$id."'";
    $Qaddress = mysqli_query($conn,$address); 
    if(mysqli_num_rows($Qaddress) > 0){
    $Raddress = mysqli_fetch_assoc($Qaddress);
   
    $returnAddress .= "
   
    <p> ".$Raddress['address']."</p>
    <p>".$Raddress['address_line1']."</p>
    <p> ".$Raddress['address_line2']."</p>
    <p>".$Raddress['cityname']." - ".$Raddress['pincode'].",&nbsp;
    <p>".$Raddress['statename']." &nbsp;</p>
    <p> <abbr title='Phone'>M : </abbr>".$Raddress['mobile_no']."</p>
   ";
   
    }else{
    $returnAddress .= "<span class='address-head mb-5'>No address available</span>";
    }
    echo json_encode(array('error'=>0,'message'=>'','address'=>$returnAddress,'total'=>$totalAmount));
    exit();
    }
    
    
    
    //date:- 29/01/2019 CREATED BY:- VIRAG RAKHOLIYA
    //get customer for city wise ajax start
    if($_REQUEST['action'] == "getcustomer"){
      $customer_city = (isset($_REQUEST['customer_city'])) ? $_REQUEST['customer_city'] : '';
    
      if($customer_city != ''){
        $query = "SELECT id, name FROM ledger_master WHERE city = '".$customer_city."' AND status=1 AND group_id = 10 AND pharmacy_id = '".$pharmacy_id."' order by name";
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
    //get customer for city wise ajax end



    /*--------------------------------------------VIRAG BHAI AJAX END-----------------------------------------------------------------------------*/
    
    /*------------------------------------------------Journal Vouchar[Rinku AJAX start]------------------*/
    if($_REQUEST['action'] == "getgrouplist"){

      $group_id = (isset($_REQUEST['query'])) ? $_REQUEST['query'] : '';
        
        if($group_id != ''){
            $query = 'SELECT id, name FROM `ledger_master` WHERE group_id = '.$group_id.' AND status=1 AND pharmacy_id = '.$pharmacy_id.' AND is_cash = 0 order by name';
            
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
    /*------------------------------------------------Journal Vouchar[Rinku AJAX end]------------------*/
    
    /*---------------------------------CODE FOR SHREYA START---------------------------------*/
    if($_REQUEST['action'] == "roundoffsaleRunningBalance"){
      
            if((isset($_REQUEST['to']) && $_REQUEST['to'] != '') && (isset($_REQUEST['from']) && $_REQUEST['from'] != '')){
        $from = (isset($_REQUEST['from']) && $_REQUEST['from'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_REQUEST['from']))) : '';
        $to = (isset($_REQUEST['to']) && $_REQUEST['to'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_REQUEST['to']))) : '';
        
         $data = saleRunningBalance($from, $to);
         
        $result = array('status' => true, 'message' => 'success', 'result' => $data);
      }else{
        $result = array('status' => false, 'message' => 'not found!', 'result' => '');
      }
      echo json_encode($result);
      exit;
    }

    if($_REQUEST['action'] == "roundoffpurchaseRunningBalance"){
        if((isset($_REQUEST['to']) && $_REQUEST['to'] != '') && (isset($_REQUEST['from']) && $_REQUEST['from'] != '')){
        $from = (isset($_REQUEST['from']) && $_REQUEST['from'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_REQUEST['from']))) : '';
        $to = (isset($_REQUEST['to']) && $_REQUEST['to'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_REQUEST['to']))) : '';
        
        $data = purchaseRunningBalance($from, $to);
     // print_r($data);exit;
        $result = array('status' => true, 'message' => 'success', 'result' => $data);
      }else{
        $result = array('status' => false, 'message' => 'not found!', 'result' => '');
      }
      echo json_encode($result);
      exit;
    }
    
    if($_REQUEST['action'] == "getLedgersListByGroup") {
        $type =  (isset($_POST['type']) && $_POST['type']!='')?$_POST['type']:'';
        $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';

        $query = 'SELECT name,id FROM ledger_master  WHERE status = 1 AND pharmacy_id = '.$pharmacy_id.'  AND group_id = '.$_POST['type'].' order by name ';
        $result = mysqli_query($conn,$query);
        ?>
            <option value="">Select Perticular</option>
        <?php
            foreach ($result as $row) {
        ?>
                <option value="<?php echo $row['id']; ?>"><?php echo $row['name'] ;?></option>
        <?php 
            }  
        exit;
    }
 

    if($_REQUEST['action'] == "allLedgerCalculation"){

        if(isset($_REQUEST['id']) && $_REQUEST['id'] != ''){
  
            $from = (isset($_REQUEST['from']) && $_REQUEST['from'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_REQUEST['from']))) : '';
            $to = (isset($_REQUEST['to']) && $_REQUEST['to'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_REQUEST['to']))) : '';

            $data = countRunningBalance($_REQUEST['id'], $from, $to);
      
            $result = array('status' => true, 'message' => 'success', 'result' => $data);
        }else{
            $result = array('status' => false, 'message' => 'not found!', 'result' => '');
        }
        echo json_encode($result);
        exit;
    }
   
   if($_REQUEST['action'] == "searchCompany"){

  $arrs = [];  
  $querys = $_REQUEST['query'];
  if((isset($querys)) && $querys !=''){

   $cmpny = "SELECT id , mfg_company FROM `product_master` WHERE pharmacy_id = '".$pharmacy_id."' AND mfg_company LIKE '%".$querys."%' GROUP by mfg_company";
   $compny = mysqli_query($conn, $cmpny);
   if($compny && mysqli_num_rows($compny) > 0){
     $arr = [];
     while($row = mysqli_fetch_assoc($compny)){

      $arrs['id'] = $row['id'];
      $arrs['mfg_company'] = $row['mfg_company'];
      array_push($arr, $arrs);
    }

    $result = array('status' => true, 'message' => 'success', 'result' => $arr);

  }
  else{
    $result = array('status' => false, 'message' => 'not found!', 'result' => '');
  }
}
else{
 $result = array('status' => false, 'message' => 'not found!', 'result' => '');
}
echo json_encode($result);
exit;
}


if($_REQUEST['action'] == "SearchByTranstition"){
 $prd = []; 
 $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
 $financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';

 if(isset($_REQUEST['data']) && !empty($_REQUEST['data'])){
  $data = [];
  parse_str($_REQUEST['data'], $data);

  if($data['type'] == 1){    
   $qw = [];
    $pod = "SELECT id FROM product_master WHERE mfg_company like '%".$data['company_name']."%' AND pharmacy_id = '".$pharmacy_id."' AND finance_year_id = '".$financial_id."'";
    $product = mysqli_query($conn, $pod);
     if(mysqli_num_rows($product) > 0){ 
    $qwe = [];
    foreach ($product as  $value) {
      $prd = $value['id'];
      array_push($qwe, $prd);
    } 

    $startdate = date('Y-m-d',strtotime(str_replace("/","-",$data['from'])));
    $enddate = date('Y-m-d',strtotime(str_replace("/","-",$data['to'])));
    $sale = getAllProductSale($qwe, $startdate, $enddate);
    
   $gets = [];
 
      foreach ($sale as  $values) {
         
          
         $qw['id'] = $values['id'];
         $qw['productsale'] = $values['productsale'];
           foreach ($values['product'] as $product) {
         $qw['currentstock'] = $product['currentstock'];
         $qw['unit'] = $product['unit'];
         $qw['ratio'] = $product['ratio'];
         $qw['product_name'] = $product['product_name'];
         $qw['mfg_company'] = $product['mfg_company'];
         $qw['batch_no'] = $product['batch_no'];
         $qw['sgst'] = $product['sgst'];
         $qw['cgst'] = $product['cgst'];
      
        }
        $gets[] = $qw;
      }
      $result = array('status' => true, 'message' => 'Company wise Product', 'result' => $gets , 'stockper' => $data['stock']);
     }else{
         $result = array('status' => true, 'message' => 'Company wise Product', 'result' => '' , 'stockper' => $data['stock']);
     }
    
  }

  if($data['type'] == 2){
   $qw = [];
   $pod = "SELECT id , mfg_company FROM `product_master` where pharmacy_id = '".$pharmacy_id."' AND finance_year_id = '".$financial_id."' ORDER BY id ASC";

   $product = mysqli_query($conn, $pod);
     if(mysqli_num_rows($product) > 0){ 
   $qwe = [];
   foreach ($product as  $value) {
    $prd = $value['id'];
    array_push($qwe, $prd);
  } 

  $startdate = date('Y-m-d',strtotime(str_replace("/","-",$data['from'])));
    $enddate = date('Y-m-d',strtotime(str_replace("/","-",$data['to'])));
    $sale = getAllProductSale($qwe, $startdate, $enddate); 
    $gets = [];
 
      foreach ($sale as  $values) {
         
         $qw['id'] = $values['id'];
         $qw['productsale'] = $values['productsale'];
           foreach ($values['product'] as $product) {
         
         $qw['currentstock'] = $product['currentstock'];
         $qw['unit'] = $product['unit'];
         $qw['ratio'] = $product['ratio'];
         $qw['product_name'] = $product['product_name'];
         $qw['mfg_company'] = $product['mfg_company'];
         $qw['batch_no'] = $product['batch_no'];
         $qw['sgst'] = $product['sgst'];
         $qw['cgst'] = $product['cgst'];
        }
        $gets[] = $qw;
      }
     $result = array('status' => true, 'message' => 'All Company wise Product', 'result' => $gets , 'stockper' => $data['stock']);
}else{
    $result = array('status' => true, 'message' => 'All Company wise Product', 'result' => '' , 'stockper' => $data['stock']); 
}
      
  }

if($data['type'] == 3){
  $allid = implode("','", $data['selectedcompany']);
  $pod = "SELECT id , mfg_company FROM product_master WHERE mfg_company IN ( '".$allid."' ) AND pharmacy_id = '".$pharmacy_id."' AND finance_year_id = '".$financial_id."'";
  $product = mysqli_query($conn, $pod);
   if(mysqli_num_rows($product) > 0){ 
  $qwe = [];
  foreach ($product as  $value) {
    $prd = $value['id'];
    array_push($qwe, $prd);
  } 

    $startdate = date('Y-m-d',strtotime(str_replace("/","-",$data['from'])));
    $enddate = date('Y-m-d',strtotime(str_replace("/","-",$data['to']))); 
    $sale = getAllProductSale($qwe, $startdate, $enddate); 
    $gets = [];
 
      foreach ($sale as  $values) {
         $qw['id'] = $values['id'];
         $qw['productsale'] = $values['productsale'];
           foreach ($values['product'] as $product) {
         $qw['currentstock'] = $product['currentstock'];
         $qw['unit'] = $product['unit'];
         $qw['ratio'] = $product['ratio'];
         $qw['product_name'] = $product['product_name'];
         $qw['mfg_company'] = $product['mfg_company'];
         $qw['batch_no'] = $product['batch_no'];
         $qw['sgst'] = $product['sgst'];
         $qw['cgst'] = $product['cgst'];
        }
        $gets[] = $qw;
      }
     $result = array('status' => true, 'message' => 'Selected Company wise Product', 'result' => $gets , 'stockper' => $data['stock']);
   }else{
     $result = array('status' => true, 'message' => 'Selected Company wise Product', 'result' => '' , 'stockper' => $data['stock']); 
}
}

if($data['type'] == 4){

 $pod = "SELECT id , product_name FROM `product_master`where pharmacy_id = '".$pharmacy_id."' AND finance_year_id = '".$financial_id."' ORDER BY id ASC";
 $product = mysqli_query($conn, $pod);
 if(mysqli_num_rows($product) > 0){ 
 $qwe = [];
 foreach ($product as  $value) {
  $prd = $value['id'];
  array_push($qwe, $prd);
} 
 $startdate = date('Y-m-d',strtotime(str_replace("/","-",$data['from'])));
    $enddate = date('Y-m-d',strtotime(str_replace("/","-",$data['to']))); 
    $sale = getAllProductSale($qwe, $startdate, $enddate); 
    $gets = [];
 
      foreach ($sale as  $values) {
         $qw['id'] = $values['id'];
         $qw['productsale'] = $values['productsale'];
           foreach ($values['product'] as $product) {
         $qw['currentstock'] = $product['currentstock'];
         $qw['unit'] = $product['unit'];
         $qw['ratio'] = $product['ratio'];
         $qw['product_name'] = $product['product_name'];
         $qw['mfg_company'] = $product['mfg_company'];
         $qw['batch_no'] = $product['batch_no'];
         $qw['sgst'] = $product['sgst'];
         $qw['cgst'] = $product['cgst'];
        }
        $gets[] = $qw;
      }
$result = array('status' => true, 'message' => 'Selected Company wise Product', 'result' => $gets , 'stockper' => $data['stock']);
}else{
   $result = array('status' => true, 'message' => 'Selected Company wise Product', 'result' => '' , 'stockper' => $data['stock']); 
}
}
}
echo json_encode($result);
exit;

}


if($_REQUEST['action'] == "AmountOfCourier"){
  $id = $_REQUEST['id'];


  $Qtotl = "SELECT SUM(taxable_value) AS total FROM courier_party WHERE particular = '".$id."' AND pharmacy_id = '".$pharmacy_id."' AND  financial_id = '".$financial_id."'" ;
  $toResult = mysqli_query($conn,$Qtotl);
  $rcmvalue = mysqli_fetch_assoc($toResult);
  $totalAmount = $rcmvalue['total'];
  
  if($totalAmount == NULL){
   $totalAmount = 0;
 }
 $totalAmount = number_format($totalAmount, 2, '.', ''); 


 $returnAddress = "";
 $address = "SELECT ct.address , ct.address_line1 , ct.address_line2 , ct.mobile_no , ct.pincode , s.name as statename, c.name as cityname FROM courier_transport ct JOIN own_states s ON ct.state = s.id JOIN own_cities c ON ct.city = c.id WHERE ct.id = '".$id."'";
 $Qaddress = mysqli_query($conn,$address); 
 if(mysqli_num_rows($Qaddress) > 0){
   $Raddress = mysqli_fetch_assoc($Qaddress);
   
   $returnAddress .= "
   
   <p> ".$Raddress['address']."</p>
   <p>".$Raddress['address_line1']."</p>
   <p> ".$Raddress['address_line2']."</p>
   <p>".$Raddress['cityname']." - ".$Raddress['pincode'].",&nbsp;
   <p>".$Raddress['statename']." &nbsp;</p>
   <p> <abbr title='Phone'>M : </abbr>".$Raddress['mobile_no']."</p>
   ";
   
 }else{
  $returnAddress .= "<span class='address-head mb-5'>No address available</span>";
}
echo json_encode(array('error'=>0,'message'=>'','address'=>$returnAddress,'total'=>$totalAmount));
exit();
}




if($_REQUEST['action'] == "AddNewParty"){
 
  if(isset($_REQUEST['data']) && !empty($_REQUEST['data'])){
    
   $user_id = (isset($_SESSION['auth']['id']) && $_SESSION['auth']['id'] != '') ? $_SESSION['auth']['id'] : NULL ;
   $owner_id = (isset($_SESSION['auth']['owner_id']) && $_SESSION['auth']['owner_id'] != '') ? $_SESSION['auth']['owner_id'] : NULL;
   $admin_id = (isset($_SESSION['auth']['admin_id']) && $_SESSION['auth']['admin_id'] != '') ? $_SESSION['auth']['admin_id'] : NULL;
   $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : NULL;
   $financial_id = (isset($_SESSION['auth']['financial']) && $_SESSION['auth']['financial'] != '') ? $_SESSION['auth']['financial'] : NULL;

   $query = "INSERT INTO courier_transport SET financial_id = '".$financial_id."', owner_id = '".$owner_id."', admin_id = '".$admin_id."', pharmacy_id = '".$pharmacy_id."',";
   foreach ($_REQUEST['data'] as $key => $value) {
     $query .= " ".$value['name']." = '".$value['value']."', ";
   }
   $query .= "created = '".date('Y-m-d H:i:s')."', createdby = '".$user_id."'";
   
   $addparty = mysqli_query($conn, $query);
   
   if($addparty){
    $result = array('status' => true, 'message' => 'Courier-transport Added Success.', 'result' => '');
  }else{
    $result = array('status' => false, 'message' => 'Courier-transport Added Fail! Try Again.', 'result' => '');
  }
}
else{
  $result = array('status' => false, 'message' => 'Field is required!', 'result' => '');
}
echo json_encode($result);
exit;
}


    /*---------------------------------CODE FOR SHREYA END---------------------------------*/
    
    // Add By Kartik ///
    
    if($_REQUEST['action'] == "searchCustomer"){
      $query = $_REQUEST['query'];
      $arr = array();
      if((isset($_REQUEST['query']) && $_REQUEST['query'] != '') && (isset($_REQUEST['query']) && $_REQUEST['query'] != '')){
        $pharmacy_id = $_SESSION['auth']['pharmacy_id'];
        $customerQ = "SELECT id, name, mobile,customer_id,state FROM ledger_master WHERE pharmacy_id = '".$pharmacy_id."' AND group_id = 10 AND status = 1 AND name LIKE '%".$query."%'";
        
        $customerR = mysqli_query($conn, $customerQ);
        if($customerR && mysqli_num_rows($customerR) > 0){
          $res = [];
          while($row = mysqli_fetch_assoc($customerR)){
            $stateQ = "SELECT state_code_gst FROM `own_states` Where id='".$row['state']."'";
            $stateR = mysqli_query($conn, $stateQ);
            $row_state = mysqli_fetch_assoc($stateR);
            $limit_total = CheckCr($row['id']);
            $arr['id'] = $row['id'];
            $arr['name'] = $row['name'];
            $arr['mobile'] = $row['mobile'];
            $arr['state'] = $row_state['state_code_gst'];
            $arr['limit_total'] = $limit_total;
            $arr['getcustmerID'] = getcustomerID();
            if(is_null($row['customer_id'])){
              $row['customer_id'] = '';
              $row['temp_customer'] = getcustomerID();
            }else{
              $row['customer_id'] = $row['customer_id'];
              $row['temp_customer'] = $row['customer_id'];
            }
            $arr['customer_id'] = $row['customer_id'];
            $arr['temp_customer'] = $row['temp_customer'];
            array_push($res, $arr);
          }
          $result = array('status' => true, 'message' => 'success', 'result' => $res);
        }else{
          $result = array('status' => false, 'message' => 'not found!', 'result' => '');
        }
      }else{
        $result = array('status' => false, 'message' => 'not found!', 'result' => '');
      }
      echo json_encode($result);
      exit;
    }

    if($_REQUEST['action'] == "getdoctornumber"){
      if((isset($_REQUEST['doctor_id']) && $_REQUEST['doctor_id'] != '') && (isset($_REQUEST['doctor_id']) && $_REQUEST['doctor_id'] != '')){
        $doctorQ = "SELECT mobile FROM `doctor_profile` WHERE id='".$_REQUEST['doctor_id']."'";
        $doctorR = mysqli_query($conn, $doctorQ);
        $row = mysqli_fetch_assoc($doctorR);
        $data = $row['mobile'];
      }else{
        $data = "";
      }
      echo $data;exit;
    }
    
    if($_REQUEST['action'] == "getserchvendor"){
      $searchquery = (isset($_REQUEST['query']['term'])) ? $_REQUEST['query']['term'] : '';

      if($searchquery != ''){
        $query = "SELECT name,id FROM `ledger_master` WHERE name like '%".$searchquery."%' AND name IS NOT NULL AND pharmacy_id='".$pharmacy_id."' AND status ='1' AND group_id='14'";
        $res = mysqli_query($conn, $query);

        if($res && mysqli_num_rows($res) > 0){
          $finalres = [];
            while ($row = mysqli_fetch_array($res)) {
              $arr['id'] = $row['id'];
              $arr['name'] = $row['name'];
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
    
    if($_REQUEST['action'] == "PanddingBillAmount"){
      $customer_id = $_REQUEST['customer_id'];
      $final_data = array();
      if(isset($customer_id) && $customer_id != ''){
        $customer_data = getCustomerPaymentNotification($customer_id);
        foreach ($customer_data as $key => $value) {
          # code...
          $arr['invoice_no'] = $value['invoice_no'];
          $arr['total_bill'] = $value['total_bill'];
          $arr['total_payment'] = $value['total_payment'];
          $arr['total_remaining'] = $value['total_remaining'];
          $final_data[] = $arr;
        }
        echo json_encode($final_data);
      }else{
        echo json_encode(array());
      }
      exit;
    }
    
    if($_REQUEST['action'] == "getGST"){
        $gst_id = $_REQUEST['gst_id'];
        if(isset($gst_id) && $gst_id != ''){
            $query = "SELECT * FROM `gst_master` WHERE id ='".$gst_id."' AND pharmacy_id='".$_SESSION['auth']['pharmacy_id']."'";
            $res = mysqli_query($conn, $query);
            $row = mysqli_fetch_array($res);
            
            $arr['igst'] = $row['igst'];
            $arr['sgst'] = $row['sgst'];
            $arr['cgst'] = $row['cgst'];
            $result = array('status' => true, 'message' => 'Success!', 'result' => $arr);
        }else{
            $result = array('status' => false, 'message' => 'Fail!', 'result' => '');
        }
        
        echo json_encode($result);
        exit;
    }
    
    /*----ADDED BY GAUTAM MAKWANA - 22-11-2018 - START-----*/
    if($_REQUEST['action'] == "getLastSaleRateByProduct"){
        $product_id = (isset($_REQUEST['product_id'])) ? $_REQUEST['product_id'] : '';
        $customer_id = (isset($_REQUEST['customer_id'])) ? $_REQUEST['customer_id'] : '';
      	if($product_id != ''){
            $data = getLastSaleRateByProduct($product_id, $customer_id);
            if(!empty($data)){
                $tmpdata = [];
                    foreach($data as $key => $value){
                        $tmp['id'] = (isset($value['id'])) ? $value['id'] : '';
                        $tmp['rate'] = (isset($value['rate']) && $value['rate'] != '') ? $value['rate'] : 0;
                        $tmp['date'] = (isset($value['created']) && $value['created'] != '') ? date('d M y',strtotime($value['created'])) : '';
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
    /*----ADDED BY GAUTAM MAKWANA - 22-11-2018 - START-----*/
    
    /*----ADDED BY Kartik Champaneriya - 28-11-2018 - START-----*/
    
    /*if($_REQUEST['action'] == "getSaleofService"){
        $type = $_REQUEST['type'];
        $query = "SELECT id,product_name FROM `service_master_data` WHERE product_name LIKE '%$type%' AND gst_id NOT IN(1,2,3)";
        $res = mysqli_query($conn, $query);
        while()
        
    }*/
    
    /*----ADDED BY Kartik Champaneriya - 28-11-2018 - END-----*/
    
    if($_REQUEST['action'] == "searchService"){
        $query = (isset($_REQUEST['query'])) ? $_REQUEST['query'] : '';
        // $gst_id = (isset($_REQUEST['gst_id'])) ? $_REQUEST['gst_id'] : '';
        $data = [];
         $query = "SELECT * FROM `service_master` WHERE pharmacy_id = '".$pharmacy_id."' AND status = 1 AND name LIKE '%".$query."%'";
            // if($gst_id != ''){
            //     $query .= " AND gst_id = '".$gst_id."'";
            // }else{
            //     $query .= " AND gst_id NOT IN(1,2,3)";
            // }
           $query .= " ORDER BY name";
            $res = mysqli_query($conn, $query);
            if($res && mysqli_num_rows($res) > 0){
                while($row = mysqli_fetch_assoc($res)){
                    $data[] = $row;
                }
            }
        if(!empty($data)){
            $result = array('status' => true, 'message' => 'Success!', 'result' => $data);
        }else{
            $result = array('status' => false, 'message' => 'Fail!', 'result' => '');
        }
        echo json_encode($result);
      	exit;
    }
    
    
    // ------------------------Get Mrp Wise Product for costomer sale report--------Rajesh-------01/02/2019-----------Start----------
  if($_REQUEST['action'] == "getMrpWiseProduct"){
      // product_by = 0 => mrp wise and 1 = all product
      //$product_by = (isset($_REQUEST['product_by'])) ? $_REQUEST['product_by'] : '';
      $mrp = (isset($_REQUEST['mrp'])) ? $_REQUEST['mrp'] : '';
      $data = [];
          $query = "SELECT id,product_name,mrp FROM product_master WHERE pharmacy_id = '".$pharmacy_id."' ";
          if($mrp != ''){
            $query .= "AND mrp = '".$mrp."' ";
          }
          $query .= "GROUP BY product_name ORDER BY product_name";
          $res = mysqli_query($conn, $query);
          if($res && mysqli_num_rows($res) > 0){
            while ($row = mysqli_fetch_assoc($res)) {
                //$row['product_name'] = $row['product_name'];
                $data[] = $row;
            }
          }

          if(!empty($data)){
            $result = array('status' => true, 'message' => 'Product found success', 'result' => $data);
          }else{
            $result = array('status' => false, 'message' => 'Product not found!', 'result' => '');    
          }
      
      echo json_encode($result);exit;
  }
    
  // ------------------------Get Mrp Wise Product for costomer sale report--------Rajesh-------01/02/2019-----------End-----------

?>