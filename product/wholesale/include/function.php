<?php //include('include/usertypecheck.php');
    
    $owner_id = (isset($_SESSION['auth']['owner_id'])) ? $_SESSION['auth']['owner_id'] : '';
    $admin_id = (isset($_SESSION['auth']['admin_id'])) ? $_SESSION['auth']['admin_id'] : '';
    $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
    $financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';
    
    function pr($myArray = array(), $terminate = true) {
        echo "<pre>";
        print_r($myArray);
        if($terminate) {
            die;
        }
        echo "</pre>";
    }
    
    /*  Create By Kartik 24-10-2018 Start  */
    function checkLeger($group_id = null,$name = null,$city = null,$edit_id = null){
        global $conn;
        global $pharmacy_id;
        $query = "SELECT * FROM `ledger_master` WHERE name='".$name."' AND city ='".$city."' AND group_id ='".$group_id."' AND pharmacy_id = '".$pharmacy_id."'";
        if(isset($edit_id) && $edit_id != ''){
            $query .= " AND id != '".$edit_id."'";
        }
        $ledger_run = mysqli_query($conn, $query);
        $count = mysqli_num_rows($ledger_run);
        if($count > 0){
            return false;
        }else{
            return true;
        }
        
    }
    
    /*  Create By Kartik 24-10-2018 End  */
    
    function runningbalance(){
        global $conn;
        $user_id = $_SESSION['auth']['id'];
        $pharmacy_id = $_SESSION['auth']['pharmacy_id'];
        
        $cash_paymentQry = "SELECT *,SUM(amount) as total_amont FROM `accounting_cash_management` WHERE payment_type = 'cash_payment' AND createdby='".$user_id."' AND pharmacy_id='".$pharmacy_id."'";
        $cash_paymentrun = mysqli_query($conn, $cash_paymentQry);
        $cash_paymentdata = mysqli_fetch_array($cash_paymentrun);
        //print_r($cash_paymentdata['total_amont']);exit;
        
        $cash_receiptQry = "SELECT *,SUM(amount) as total_amont FROM `accounting_cash_management` WHERE payment_type = 'cash_receipt' AND createdby='".$user_id."' AND pharmacy_id='".$pharmacy_id."'";
        $cash_receiptrun = mysqli_query($conn, $cash_receiptQry);
        $cash_receiptdata = mysqli_fetch_array($cash_receiptrun);
        
        $total_data = $cash_paymentdata['total_amont'] - $cash_receiptdata['total_amont'];
        return $total_data;
        
    }
    
    /*function getcashpaymentno($type=null){
        global $conn;
        if(!empty($type)){
            $type = $type;
        }else{
            $type = 'cash_payment';
        }
        $voucher_no = '';
        $p_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : '';
        $financial_id = (isset($_SESSION['auth']['financial']) && $_SESSION['auth']['financial'] != '') ? $_SESSION['auth']['financial'] : '';
    
        $voucherqry = "SELECT voucher_no FROM accounting_cash_management WHERE payment_type = '".$type."' AND `pharmacy_id` = '".$p_id."' AND financial_id = '".$financial_id."' ORDER BY id DESC LIMIT 1";
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
              $voucher_no = 'CP-'.$voucherno;
            }
          }else{
            $voucherno = sprintf("%05d", 1);
            $voucher_no = 'CP-'.$voucherno;
          }
        }
        return $voucher_no;
      } */
      
      
    function getcashrecipt(){
        global $conn;
        $voucher_no = '';
        $p_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : NULL;
        $financial_id = (isset($_SESSION['auth']['financial']) && $_SESSION['auth']['financial'] != '') ? $_SESSION['auth']['financial'] : NULL;
        $cashQuery = "SELECT * FROM `cash_receipt` WHERE pharmacy_id = '".$p_id."' AND financial_id = '".$financial_id."' ORDER BY id DESC LIMIT 1";
        $cashRes = mysqli_query($conn, $cashQuery);
        if($cashRes){
          $count = mysqli_num_rows($cashRes);
          if($count !== '' && $count !== 0){
            $row = mysqli_fetch_array($cashRes);
            $voucherno = (isset($row['cash_receipt_no'])) ? $row['cash_receipt_no'] : '';

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
        return $voucher_no;
      }
    
    function checkpermission($title) {
        global $conn;
        if($_SESSION['auth']['user_type'] == "admin"){
            $user_id = $_SESSION['auth']['id'];
            $rightsQry = "SELECT * FROM `admin_rights` WHERE user_id = '".$user_id."'";
            $rights = mysqli_query($conn,$rightsQry);
            $rights_data = mysqli_fetch_assoc($rights);
            $rights_module = explode(",",$rights_data['module']);
            $rights_sub_module = explode(",",$rights_data['sub_module']);
    
            $user_module = array();
            foreach ($rights_module as $value_module) {
                $moduleQry = "SELECT * FROM `module` WHERE id='".$value_module."'";
                $module = mysqli_query($conn,$moduleQry);
                $data_module = mysqli_fetch_assoc($module);
                $user_module[] = $data_module['name'];
            }
            
            $user_sub_module = array();
            foreach ($rights_sub_module as $value_sub_module) {
                $sub_moduleQry = "SELECT * FROM `sub_module` WHERE id='".$value_sub_module."'";
                $sub_module = mysqli_query($conn,$sub_moduleQry);
                $data_sub_module = mysqli_fetch_assoc($sub_module);
                $user_sub_module[] = $data_sub_module['name'];
            }
            
    
            if(in_array($title, $user_module)){
                return true;
            }else if(in_array($title, $user_sub_module)){
                return true;
            }else{
                return false;
            }
        }else if($_SESSION['auth']['user_type'] == "user"){
            $user_role = $_SESSION['auth']['user_role'];
            $rightsQry = "SELECT * FROM `user_rights` WHERE id = '".$user_role."'";
            $rights = mysqli_query($conn,$rightsQry);
            $rights_data = mysqli_fetch_assoc($rights);
            $rights_module = explode(",",$rights_data['module']);
            $rights_sub_module = explode(",",$rights_data['sub_module']);
    
            $user_module = array();
            foreach ($rights_module as $value_module) {
                $moduleQry = "SELECT * FROM `module` WHERE id='".$value_module."'";
                $module = mysqli_query($conn,$moduleQry);
                $data_module = mysqli_fetch_assoc($module);
                $user_module[] = $data_module['name'];
            }
    
            $user_sub_module = array();
            foreach ($rights_sub_module as $value_sub_module) {
                $sub_moduleQry = "SELECT * FROM `sub_module` WHERE id='".$value_sub_module."'";
                $sub_module = mysqli_query($conn,$sub_moduleQry);
                $data_sub_module = mysqli_fetch_assoc($sub_module);
                $user_sub_module[] = $data_sub_module['name'];
            }
    
            if(in_array($title, $user_module)){
                return true;
            }else if(in_array($title, $user_sub_module)){
                return true;
            }else{
                return false;
            }
        }else if($_SESSION['auth']['user_type'] == "owner"){
            return true;
        }
    }
    
    function getAllProductWithCurrentStock($alphabet = null, $like = null, $expired = 0, $id = [], $enddate = null, $mrp = null, $gst_id = null, $onlywithgst = 0){
        
        $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
        $financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';

        global $conn;
        $data = [];
        $query = "SELECT p.*, ROUND(p.mrp, 2) as mrp, cm.name as c_name, cm.code as c_code, gm.gst_name FROM product_master p LEFT JOIN company_master cm ON p.company_code = cm.id LEFT JOIN gst_master gm ON p.gst_id = gm.id ";
        $where = array();
        if(isset($alphabet) && $alphabet != ''){
          $where[] = "LOWER(p.product_name) LIKE '".strtolower($alphabet)."%'";
        }
        if(isset($like) && $like != ''){
          $where[] = "p.product_name LIKE '%".$like."%'";
        }

        if(isset($expired) && $expired == 1){
            $where[] = "(p.ex_date >= '".date('Y-m-d')."' OR p.ex_date IS NULL OR p.ex_date = '0000-00-00')";    /// Changes By kartik >= ///
        }
        
        if(isset($mrp) && $mrp != ''){
            $where[] = "p.mrp = '".$mrp."'";
        }
        
        if(isset($id) && !empty($id)){
            $allid = implode(',', $id);
            $where[] = "p.id IN (".$allid.")";
        }
        if(isset($gst_id) && $gst_id != ''){
            // get only non gst product
            $where[] = "p.gst_id = '".$gst_id."'";
        }elseif(isset($onlywithgst) && $onlywithgst == 1){
            // get only with gst product
            $where[] = "p.gst_id NOT IN(1,2,3)";
        }
        
        $where[] = "p.status = 1";
        $where[] = "p.pharmacy_id = '".$pharmacy_id."'";
        if(!empty($where)){
          $where = implode(" AND ",$where);
          /// Changes By Kartik ///
         // $query .="WHERE ".$where;
          $query .="WHERE ".$where." ORDER BY p.ex_date";
        }
        
        $res = mysqli_query($conn, $query);
        if($res && mysqli_num_rows($res) > 0){
            while ($row = mysqli_fetch_assoc($res)) {
                /* PTR COUNT*/
                /*$row['mrp'] = (isset($row['mrp']) && $row['mrp'] != '' && $row['mrp'] != 0) ? $row['mrp'] : 1;
                $row['igst'] = (isset($row['igst']) && $row['igst'] != '') ? $row['igst'] : 0;
                $row['discount_per'] = (isset($row['discount_per']) && $row['discount_per'] != '') ? $row['discount_per'] : 0;
                $row['discount'] = (isset($row['discount']) && $row['discount'] != '') ? $row['discount'] : 0;*/
                // $row['purchaserate'] =  (isset($row['rate']) && $row['rate'] != '') ? $row['rate'] : 0;
                
                /*$with_gst = ($row['mrp'] * $row['igst'])/100;
                $tolal_gst_amount = $row['mrp'] + $with_gst;
                $v = $row['mrp'] * $row['igst'];
                $ptr_a = $v / $tolal_gst_amount;
                $mrp_ptr = $row['mrp'] - $ptr_a;
                $row['ratio'] = ($row['ratio'] == 0) ? 1 : $row['ratio'];
                $ptr_amount = number_format($mrp_ptr, 2, '.', '') / $row['ratio'];
                $row['ptr'] = $ptr_amount;
                if($row['discount'] == 1){
                    $d_ptr = number_format($ptr_amount, 2, '.', '') * ($row['discount_per'] / 100) ;
                    $p_ptr = number_format($ptr_amount, 2, '.', '') - $d_ptr;
                    $rate = number_format($p_ptr, 2, '.', '');
                    $row['discount'] = (isset($row['discount'])) ? $row['discount'] : '';
                    $row['rate'] = $rate;
                }else{
                    $rate = number_format($ptr_amount, 2, '.', '');
                    $row['discount'] = '';
                    $row['rate'] = $rate;
                }*/
                
                
                
                // FOR COUNT RATIO
                $ratio = (isset($row['ratio']) && $row['ratio'] != '') ? $row['ratio'] : 0;
                $opening_qty = (isset($row['opening_qty']) && $row['opening_qty'] != '') ? $row['opening_qty'] : 0;
        
                // FOR CALCULATE TOTAL QTY AND TOTAL FREE QTY OF PURCHASE THIS PRODUCT 
                $purchaseQ = "SELECT SUM(pd.qty) as total_qty, SUM(pd.free_qty) as total_free_qty FROM purchase_details pd INNER JOIN purchase ps ON pd.purchase_id = ps.id WHERE pd.product_id = '".$row['id']."' AND ps.pharmacy_id = '".$pharmacy_id."' AND ps.financial_id = '".$financial_id."'";
                if(isset($enddate) && $enddate != ''){
                    $purchaseQ .= " AND ps.invoice_date <= '".$enddate."'";
                }
                $purchaseR = mysqli_query($conn, $purchaseQ);
                $total_purchase_qty_row = ($purchaseR) ? mysqli_fetch_array($purchaseR) : '';
                $total_purchase_qty = (isset($total_purchase_qty_row['total_qty']) && $total_purchase_qty_row['total_qty'] != '') ? $total_purchase_qty_row['total_qty'] : 0;
                $total_purchase_freeqty = (isset($total_purchase_qty_row['total_free_qty']) && $total_purchase_qty_row['total_free_qty'] != '') ? $total_purchase_qty_row['total_free_qty'] : 0;
        
                // FOR CALCULATE TOTAL QTY AND TOTAL FREE QTY OF PURCHASE RETURN THIS PRODUCT 
                $purchaseReturnQ = "SELECT SUM(prd.qty) as total_qty, SUM(prd.free_qty) as total_free_qty FROM purchase_return_detail prd INNER JOIN purchase_return pr ON prd.pr_id = pr.id WHERE prd.product_id = '".$row['id']."' AND pr.pharmacy_id = '".$pharmacy_id."' AND pr.financial_id = '".$financial_id."' AND pr.debit_note_settle = 0";
                if(isset($enddate) && $enddate != ''){
                    $purchaseReturnQ .= " AND pr.bill_date <= '".$enddate."'";
                }
                $purchaseReturnR = mysqli_query($conn, $purchaseReturnQ);
                $total_purchasereturn_qty_row = ($purchaseReturnR) ? mysqli_fetch_array($purchaseReturnR) : '';
                $total_purchase_return_qty = (isset($total_purchasereturn_qty_row['total_qty']) && $total_purchasereturn_qty_row['total_qty'] != '') ? $total_purchasereturn_qty_row['total_qty'] : 0;
                $total_purchase_return_freeqty = (isset($total_purchasereturn_qty_row['total_free_qty']) && $total_purchasereturn_qty_row['total_free_qty'] != '') ? $total_purchasereturn_qty_row['total_free_qty'] : 0;
                
                // FOR CALCULATE TOTAL QTY OF SELF CONSUMPTION
                $consumptionQ = "SELECT SUM(consumption) as total_qty FROM self_consumption WHERE pharmacy_id = '".$pharmacy_id."' AND financial_id = '".$financial_id."' AND product_id = '".$row['id']."'";
                if(isset($enddate) && $enddate != ''){
                    $consumptionQ .= " AND DATE_FORMAT(createdat.created,'%Y-%m-%d') <= '".$enddate."'";
                    
                }
                $consumptionR = mysqli_query($conn, $consumptionQ);
                $total_consumption_qty_row = ($consumptionR) ? mysqli_fetch_array($consumptionR) : '';
                $total_consumption_qty = (isset($total_consumption_qty_row['total_qty']) && $total_consumption_qty_row['total_qty'] != '') ? $total_consumption_qty_row['total_qty'] : 0;
                
                // FOR CALCULATE TOTAL QTY OF INVENTORY ADJUSTMENT (INWARD & OUTWARD)
                $inverdQ = "SELECT SUM(qty) as total_inward_qty FROM adjustment WHERE pharmacy_id = '".$pharmacy_id."' AND financial_id = '".$financial_id."' AND type = 'inward' AND product_id = '".$row['id']."'";
                if(isset($enddate) && $enddate != ''){
                    $inverdQ .= " AND DATE_FORMAT(created_at.created,'%Y-%m-%d') <= '".$enddate."'";
                    
                }
                $inverdR = mysqli_query($conn, $inverdQ);
                $inverdRow = ($inverdR) ? mysqli_fetch_array($inverdR) : '';
                $total_inward_qty = (isset($inverdRow['total_inward_qty']) && $inverdRow['total_inward_qty'] != '') ? $inverdRow['total_inward_qty'] : 0;
        
                $outwardQ = "SELECT SUM(qty) as total_outward_qty FROM adjustment WHERE pharmacy_id = '".$pharmacy_id."' AND financial_id = '".$financial_id."' AND type = 'outward' AND product_id = '".$row['id']."'";
                if(isset($enddate) && $enddate != ''){
                    $outwardQ .= " AND DATE_FORMAT(created_at.created,'%Y-%m-%d') <= '".$enddate."'";
                    
                }
                $outwardR = mysqli_query($conn, $outwardQ);
                $outwardRow = ($outwardR) ? mysqli_fetch_array($outwardR) : '';
                $total_outward_qty = (isset($outwardRow['total_outward_qty']) && $outwardRow['total_outward_qty'] != '') ? $outwardRow['total_outward_qty'] : 0;
        
                // FOR CALCULATE TOTAL QTY AND FREE QTY OF SALE THIS PRODUCT
                $saleQ = "SELECT SUM(tbd.qty) as total_qty, SUM(tbd.freeqty) as total_free_qty FROM tax_billing_details tbd INNER JOIN tax_billing tb ON tbd.tax_bill_id = tb.id WHERE tbd.product_id = '".$row['id']."' AND tb.pharmacy_id = '".$pharmacy_id."' AND tb.financial_id = '".$financial_id."'";
                if(isset($enddate) && $enddate != ''){
                    $saleQ .= " AND tb.invoice_date <= '".$enddate."'";
                    
                }
                $saleR = mysqli_query($conn, $saleQ);
                $total_sale_qty_row = ($saleR) ? mysqli_fetch_array($saleR) : '';
                $total_sale_qty = (isset($total_sale_qty_row['total_qty']) && $total_sale_qty_row['total_qty'] != '') ? $total_sale_qty_row['total_qty'] : 0;
                $total_sale_freeqty = (isset($total_sale_qty_row['total_free_qty']) && $total_sale_qty_row['total_free_qty'] != '') ? $total_sale_qty_row['total_free_qty'] : 0;
                
                // FOR CALCULATE TOTAL QTY AND FREE QTY OF SALE RETURN THIS PRODUCT
                $saleReturnQ = "SELECT SUM(srd.qty) as total_qty, SUM(srd.free_qty) as total_free_qty FROM sale_return_details srd INNER JOIN sale_return sr ON srd.sale_return_id = sr.id WHERE srd.product_id = '".$row['id']."' AND sr.pharmacy_id = '".$pharmacy_id."' AND sr.financial_id = '".$financial_id."'";
                if(isset($enddate) && $enddate != ''){
                    $saleReturnQ .= " AND sr.credit_note_date <= '".$enddate."'";
                    
                }
                $saleReturnR = mysqli_query($conn, $saleReturnQ);
                $total_sale_return_qty_row = ($saleReturnR) ? mysqli_fetch_array($saleReturnR) : '';
                $total_sale_return_qty = (isset($total_sale_return_qty_row['total_qty']) && $total_sale_return_qty_row['total_qty'] != '') ? $total_sale_return_qty_row['total_qty'] : 0;
                $total_sale_return_freeqty = (isset($total_sale_return_qty_row['total_free_qty']) && $total_sale_return_qty_row['total_free_qty'] != '') ? $total_sale_return_qty_row['total_free_qty'] : 0;
                
                $currentstock = ($opening_qty) + ($total_purchase_qty) + ($total_purchase_freeqty) - ($total_consumption_qty) + ($total_inward_qty) - ($total_outward_qty) - ($total_sale_qty+$total_sale_freeqty) - ($total_purchase_return_qty+$total_purchase_return_freeqty) + ($total_sale_return_qty+$total_sale_return_freeqty);
                $row['currentstock'] = $currentstock;
                $row['sale'] = ($total_sale_qty+$total_sale_freeqty);
                $row['purchase'] = ($total_purchase_qty+$total_purchase_freeqty);
                $data[] = $row;
            }
        }
        return $data;
    }
    
    function addMinQtyNotification(){
        global $conn;
        $allproduct = getAllProductWithCurrentStock();
        if(!empty($allproduct)){
            $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';

            foreach ($allproduct as $key => $value) {
                if($value['currentstock'] < $value['min_qty']){
                    $existQ = "SELECT id FROM product_master WHERE id = '".$value['id']."' AND pharmacy_id = '".$pharmacy_id."' AND minqty_flag = 1";
                    $existR = mysqli_query($conn, $existQ);
                    $existRow = ($existR) ? mysqli_num_rows($existR) : 0;
                    if($existRow == 0 || $existRow == ''){
                        $query = "UPDATE product_master SET minqty_flag = 1, minqty_noti_flag = 1 WHERE id = '".$value['id']."'";
                        $res = mysqli_query($conn, $query);
                    }
                }else{
                    $existQ = "SELECT id FROM product_master WHERE id = '".$value['id']."' AND pharmacy_id = '".$pharmacy_id."' AND minqty_flag = 0 AND minqty_noti_flag = 0";
                    $existR = mysqli_query($conn, $existQ);
                    $existRow = ($existR) ? mysqli_num_rows($existR) : 0;
                    
                    if($existRow == 0 || $existRow == ''){
                        $query = "UPDATE product_master SET minqty_flag = 0, minqty_noti_flag = 0 WHERE id = '".$value['id']."'";
                        $res = mysqli_query($conn, $query);
                    }
                }
            }

        }
    }

    function getMinQtyNotification(){
        global $conn;
        $data = [];
        $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';

        $query = "SELECT id, product_name, generic_name, mfg_company, batch_no FROM product_master WHERE pharmacy_id = '".$pharmacy_id."' AND minqty_flag = 1 AND minqty_noti_flag = 1";
        $res = mysqli_query($conn, $query);

        if($res && mysqli_num_rows($res) > 0){
            while ($row = mysqli_fetch_assoc($res)) {
                $data[] = $row;
            }
        }
        return $data;
    }
    
    function getAllNotification(){
        global $conn;
        $data = [];
        $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
        $financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';
        $date = date('Y-m-d');

            /*---------------------------------------NOTIFICATION FOR MIN QTY OF PRODUCT START------------------------------------------*/
            $MinQty =  getMinQtyProduct();
            if(!empty($MinQty)){
                foreach($MinQty as $key => $value){
                    $minqty = (isset($value['min_qty']) && $value['min_qty'] != '') ? $value['min_qty'] : 0;
                    $suggested = (isset($value['suggested_qty']) && $value['suggested_qty'] != '') ? $value['suggested_qty'] : 0;
                    $stock = (isset($value['currentstock']) && $value['currentstock'] != '') ? $value['currentstock'] : 0;
                    
                    $arr1['type'] = 'minqty_product';
                    $arr1['typename'] = 'Minimum Qty Of Product';
                    $arr1['name'] = (isset($value['product_name'])) ? $value['product_name'] : '';
                    $arr1['subname'] = 'Current Stock: '.$stock.' Min Qty: '.$minqty.' Suggested Qty: '.$suggested;
                    $arr1['url'] = 'order-by-min-qty.php';
                    $data[] = $arr1;
                }
            }
            /*---------------------------------------NOTIFICATION FOR MIN QTY OF PRODUCT END------------------------------------------*/
            
            if(isset($_SESSION['auth']['user_type']) && $_SESSION['auth']['user_type'] != 'user'){
                /*$notification_day = 0;
                $notiDayQ = "SELECT owner_reminder FROM owner_notification ";
                if($_SESSION['auth']['user_type'] == 'admin'){
                    $notiDayQ .= "WHERE created_by = '".$_SESSION['auth']['owner_id']."' ";
                }else{
                    $notiDayQ .= "WHERE created_by = '".$_SESSION['auth']['id']."' ";
                }
                $notiDayQ .= "ORDER BY id DESC LIMIT 1";
                $notiDayR = mysqli_query($conn, $notiDayQ);
                if($notiDayR && mysqli_num_rows($notiDayR) > 0){
                    $notiDayRow = mysqli_fetch_assoc($notiDayR);
                    $notification_day = (isset($notiDayRow['owner_reminder']) && $notiDayRow['owner_reminder'] != '') ?$notiDayRow['owner_reminder'] : 0;
                }*/
                

                /*---------------------------------------NOTIFICATION FOR EXPIRY DRUG LICENSE START----------------------------------------*/
                $drugLicenseQ = "SELECT id, drug_lic, drug_lic_renewal_date, pharmacy_name FROM pharmacy_profile ";
                if($_SESSION['auth']['user_type'] == 'admin'){
                    $drugLicenseQ .= "WHERE id = '".$pharmacy_id."' ";
                }else{
                    $drugLicenseQ .= "WHERE created_by = '".$_SESSION['auth']['id']."' ";
                }
                //$drugLicenseQ .= " AND drug_lic <= ('".$date."' + INTERVAL ".$notification_day." MONTH)";
                $drugLicenseR = mysqli_query($conn, $drugLicenseQ);
                if($drugLicenseR && mysqli_num_rows($drugLicenseR) > 0){
                    while ($drugLicenseRow = mysqli_fetch_assoc($drugLicenseR)) {
                        $notification_day = 0;
                        if(isset($drugLicenseRow['drug_lic_renewal_date']) && $drugLicenseRow['drug_lic_renewal_date'] == '15days'){
                            $notification_day = 15;
                        }elseif(isset($drugLicenseRow['drug_lic_renewal_date']) && $drugLicenseRow['drug_lic_renewal_date'] == '1month'){
                            $notification_day = 30;
                        }
                        
                        if(isset($drugLicenseRow['drug_lic']) && ($drugLicenseRow['drug_lic'] <= date('Y-m-d', strtotime("+".$notification_day." day", strtotime($date))))){
                            $arr2['type'] = "expiry_drug_license";
                            $arr2['typename'] = "Expire Drug License";
                            $pharmacyname = (isset($drugLicenseRow['pharmacy_name'])) ? $drugLicenseRow['pharmacy_name'].' Pharmacy' : 'Unknown Pharmacy';
                            $arr2['name'] = $pharmacyname;
                            if($drugLicenseRow['drug_lic'] < $date){
                                $arr2['subname'] = 'Drug License is Expired';
                            }elseif($drugLicenseRow['drug_lic'] == $date){
                                $arr2['subname'] = 'Drug License is Expire Today';
                            }else{
                                $arr2['subname'] = 'Drug License is Expire On '.date('d M, Y',strtotime($drugLicenseRow['drug_lic']));
                            }
                            
                            $arr2['url'] = ($_SESSION['auth']['user_type'] == 'owner') ? 'pharmacy-profile.php?id='.$drugLicenseRow['id'] : 'javascript:void(0);';
                            $data[] = $arr2;
                        }
                    }
                }
                /*---------------------------------------NOTIFICATION FOR EXPIRY DRUG LICENSE END------------------------------------------*/


                /*---------------------------------------NOTIFICATION FOR EXPIRY SHOP ACT LICENSE START-------------------------------------*/
                $shopActQ = "SELECT id, exp_date, shop_act_renewal_date, pharmacy_name FROM pharmacy_profile ";
                if($_SESSION['auth']['user_type'] == 'admin'){
                    $shopActQ .= "WHERE id = '".$pharmacy_id."' ";
                }else{
                    $shopActQ .= "WHERE created_by = '".$_SESSION['auth']['id']."' ";
                }
                //$shopActQ .= " AND exp_date <= ('".$date."' + INTERVAL ".$notification_day." MONTH)";
                $shopActR = mysqli_query($conn, $shopActQ);
                if($shopActR && mysqli_num_rows($shopActR) > 0){
                    while ($shopActRow = mysqli_fetch_assoc($shopActR)) {
                        $notification_day = 0;
                        if(isset($shopActRow['shop_act_renewal_date']) && $shopActRow['shop_act_renewal_date'] == '15days'){
                            $notification_day = 15;
                        }elseif(isset($shopActRow['shop_act_renewal_date']) && $shopActRow['shop_act_renewal_date'] == '1month'){
                            $notification_day = 30;
                        }
                        if(isset($shopActRow['exp_date']) && ($shopActRow['exp_date'] <= date('Y-m-d', strtotime("+".$notification_day." day", strtotime($date))))){
                            $arr3['type'] = "expiry_shopact_license";
                            $arr3['typename'] = "Expire Shop Act License";
                            $pharmacyname = (isset($shopActRow['pharmacy_name'])) ? $shopActRow['pharmacy_name'].' Pharmacy' : 'Unknown Pharmacy';
                            $arr3['name'] = $pharmacyname;
                            if($shopActRow['exp_date'] < $date){
                                $arr3['subname'] = 'Shop Act License is Expired';
                            }elseif($shopActRow['exp_date'] == $date){
                                $arr3['subname'] = 'Shop Act License is Expire Today';
                            }else{
                                $arr3['subname'] = 'Shop Act License is Expire On '.date('d M, Y',strtotime($shopActRow['exp_date']));
                            }
                            
                            $arr3['url'] = ($_SESSION['auth']['user_type'] == 'owner') ? 'pharmacy-profile.php?id='.$shopActRow['id'] : 'javascript:void(0);';
                            $data[] = $arr3;
                        }
                    }
                }
                /*---------------------------------------NOTIFICATION FOR EXPIRY SHOP ACT LICENSE END--------------------------------------*/

                /*---------------------------------------NOTIFICATION FOR EXPIRY REG NO START------------------------------------*/
                /*$regnoQ = "SELECT ppd.pharmacy_id, ppd.pharmacist_name, ppd.pharmacist_reg_date, pr.pharmacy_name FROM pharmacy_profile_details ppd INNER JOIN pharmacy_profile pr ON ppd.pharmacy_id = pr.id ";
                if($_SESSION['auth']['user_type'] == 'admin'){
                    $regnoQ .= "WHERE ppd.pharmacy_id = '".$pharmacy_id."' ";
                }else{
                    $regnoQ .= "WHERE pr.created_by = '".$_SESSION['auth']['id']."' ";
                }
                $regnoQ .= " AND ppd.pharmacist_reg_date <= ('".$date."' + INTERVAL ".$notification_day." MONTH)";
                $regnoR = mysqli_query($conn, $regnoQ);
                if($regnoR && mysqli_num_rows($regnoR) > 0){
                    while ($regnoRow = mysqli_fetch_assoc($regnoR)) {
                        $arr4['type'] = "expiry_regno";
                        $arr4['typename'] = "Expire Reg. No";
                        $pharmacyname = (isset($regnoRow['pharmacy_name'])) ? $regnoRow['pharmacy_name'].' Pharmacy' : 'Unknown Pharmacy';
                        $arr4['name'] = $pharmacyname;
                        if($regnoRow['pharmacist_reg_date'] < $date){
                            $arr4['subname'] = 'Pharmacist '.$regnoRow['pharmacist_name'].'`s Reg. No is Expired';
                        }elseif($regnoRow['pharmacist_reg_date'] == $date){
                            $arr4['subname'] = 'Pharmacist '.$regnoRow['pharmacist_name'].'`s Reg. No is Expire Today';
                        }else{
                            $arr4['subname'] = 'Pharmacist '.$regnoRow['pharmacist_name'].'`s Reg. No is Expire On '.date('d M, Y',strtotime($regnoRow['pharmacist_reg_date']));
                        }
                        
                        $arr4['url'] = ($_SESSION['auth']['user_type'] == 'owner') ? 'pharmacy-profile.php?id='.$regnoRow['pharmacy_id'] : 'javascript:void(0);';
                        $data[] = $arr4;
                    }
                }*/
                /*---------------------------------------NOTIFICATION FOR EXPIRY REG NO END--------------------------------------*/
            }
            
        return $data;
    }
    
    function getCustomerPaymentNotification($id = null){
        global $conn;
        $data = [];
            $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
            $financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';

            $query = "SELECT id, name, crdays FROM ledger_master WHERE pharmacy_id = '".$pharmacy_id."' AND group_id = 10 ";
            if(isset($id) && $id != ''){
            	$query .= "AND id = '".$id."' ";
            }
            $query .= "ORDER BY name";
            $res = mysqli_query($conn, $query);
            if($res && mysqli_num_rows($res) > 0){

                $notificationQ = "SELECT customer_reminder FROM notification_master WHERE pharmacy_id = '".$pharmacy_id."' ORDER BY id DESC LIMIT 1";
                $notificationR = mysqli_query($conn, $notificationQ);
                $notificationRow = ($notificationR && mysqli_num_rows($notificationR) > 0) ? mysqli_fetch_assoc($notificationR) : [];
                $notificationDay = (isset($notificationRow['customer_reminder']) && $notificationRow['customer_reminder'] != '') ? $notificationRow['customer_reminder'] : 0;

                while ($row = mysqli_fetch_assoc($res)) {
                    $cr_days = (isset($row['crdays']) && $row['crdays'] != '') ? $row['crdays'] : 0;
                    $total_payment = 0;

                    /*------CASH RECEIPT----------------*/
                    $cashReceiptQ = "SELECT SUM(amount) as cash_receipt FROM cash_transaction WHERE perticular = '".$row['id']."' AND pharmacy_id = '".$pharmacy_id."' AND financial_id = '".$financial_id."' AND payment_type = 'receipt' GROUP BY perticular";
                    $cashReceiptR = mysqli_query($conn, $cashReceiptQ);
                    if($cashReceiptR && mysqli_num_rows($cashReceiptR) > 0){
                        $cashReceiptRow = mysqli_fetch_assoc($cashReceiptR);
                        $total_payment += (isset($cashReceiptRow['cash_receipt']) && $cashReceiptRow['cash_receipt'] != '') ? $cashReceiptRow['cash_receipt'] : 0;;
                    }
                    /*------CASH RECEIPT----------------*/
                    
                    /*----------BANK RECEIPT------------*/
                    $bankReceiptQ = "SELECT SUM(amount) as bank_receipt FROM bank_transaction WHERE perticular = '".$row['id']."' AND pharmacy_id = '".$pharmacy_id."' AND financial_id = '".$financial_id."' AND payment_type = 'receipt' GROUP BY perticular";
                    $bankReceiptR = mysqli_query($conn, $bankReceiptQ);
                    if($bankReceiptR && mysqli_num_rows($bankReceiptR) > 0){
                        $bankReceiptRow = mysqli_fetch_assoc($bankReceiptR);
                        $total_payment += (isset($bankReceiptRow['bank_receipt']) && $bankReceiptRow['bank_receipt'] != '') ? $bankReceiptRow['bank_receipt'] : 0;;
                    }
                    /*----------BANK RECEIPT------------*/

                    /*-------JURNAL VOUCHER-----------*/
                        $journalVouQ = "SELECT SUM(jvd.debit) as total_amount FROM journal_vouchar_details jvd INNER JOIN journal_vouchar jv ON jvd.voucher_id = jv.id WHERE jv.pharmacy_id = '".$pharmacy_id."' AND jv.financial_id = '".$financial_id."' AND jvd.particular = '".$row['id']."' GROUP BY jvd.particular";

                        $journalVouR = mysqli_query($conn, $journalVouQ);
                        if($journalVouR && mysqli_num_rows($journalVouR) > 0){
                          $journalVouRow = mysqli_fetch_assoc($journalVouR);
                          $voucherAmount = (isset($journalVouRow['total_amount']) && $journalVouRow['total_amount'] != '') ? $journalVouRow['total_amount'] : 0;
                          $total_payment += $voucherAmount;
                        }
                    /*-------JURNAL VOUCHER-----------*/


                    $billQ = "SELECT id, invoice_date, invoice_no, bill_type, final_amount FROM tax_billing WHERE bill_type = 'Debit' AND cancel = 1 AND pharmacy_id = '".$pharmacy_id."' AND financial_id = '".$financial_id."' AND customer_id = '".$row['id']."'";
                    
                    $billR = mysqli_query($conn, $billQ);
                    if($billR && mysqli_num_rows($billR) > 0){
                        while ($rows = mysqli_fetch_assoc($billR)) {
                            $total_bill = (isset($rows['final_amount']) && $rows['final_amount'] != '') ? $rows['final_amount'] : 0;
                            if($total_bill < $total_payment){
                                $total_payment = $total_payment-$total_bill;
                            }else{
                                if(isset($id) && $id != ''){
                                	$remainingDay = $cr_days - $notificationDay;
                            	}else{
                            		$remainingDay = $cr_days;
                            	}
                                $day = ($remainingDay > 0) ? $remainingDay : 0;
                                $billdate = date('d-m-Y',strtotime($rows['invoice_date'].' +'.$day.' days'));
                            
                                if(date('d-m-Y') >= $billdate){
                                    $arr = $rows;
                                    $arr['customer'] = $row;
                                    $arr['total_bill'] = $total_bill;
                                    $arr['total_payment'] = $total_payment;
                                    $remaining = ($total_bill-$total_payment);
                                    $arr['total_remaining'] = abs($remaining);
                                    $total_payment = 0;
    
                                    $data[] = $arr;
                                }
                            }
                        }
                    }
                }
            }
        return $data;
    }
    
    function getVendorPaymentNotification(){
        global $conn;
        $data = [];
            $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
            $financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';

            $query = "SELECT id, name, crdays FROM ledger_master WHERE pharmacy_id = '".$pharmacy_id."' AND group_id = 14 ORDER BY name";
            $res = mysqli_query($conn, $query);
            if($res && mysqli_num_rows($res) > 0){

                $notificationQ = "SELECT vender_reminder FROM notification_master WHERE pharmacy_id = '".$pharmacy_id."' ORDER BY id DESC LIMIT 1";
                $notificationR = mysqli_query($conn, $notificationQ);
                $notificationRow = ($notificationR && mysqli_num_rows($notificationR) > 0) ? mysqli_fetch_assoc($notificationR) : [];
                $notificationDay = (isset($notificationRow['vender_reminder']) && $notificationRow['vender_reminder'] != '') ? $notificationRow['vender_reminder'] : 0;

                while ($row = mysqli_fetch_assoc($res)) {
                    $cr_days = (isset($row['crdays']) && $row['crdays'] != '') ? $row['crdays'] : 0;
                    $total_payment = 0;

                    /*------CASH PAYMENT----------------*/
                    $cashReceiptQ = "SELECT SUM(amount) as cash_payment FROM cash_transaction WHERE perticular = '".$row['id']."' AND pharmacy_id = '".$pharmacy_id."' AND financial_id = '".$financial_id."' AND payment_type = 'payment' GROUP BY perticular";
                    $cashReceiptR = mysqli_query($conn, $cashReceiptQ);
                    if($cashReceiptR && mysqli_num_rows($cashReceiptR) > 0){
                        $cashReceiptRow = mysqli_fetch_assoc($cashReceiptR);
                        $total_payment += (isset($cashReceiptRow['cash_payment']) && $cashReceiptRow['cash_payment'] != '') ? $cashReceiptRow['cash_payment'] : 0;;
                    }
                    /*------CASH PAYMENT----------------*/
                    
                    /*----------BANK PAYMENT------------*/
                    $bankReceiptQ = "SELECT SUM(amount) as bank_payment FROM bank_transaction WHERE perticular = '".$row['id']."' AND pharmacy_id = '".$pharmacy_id."' AND financial_id = '".$financial_id."' AND payment_type = 'payment' GROUP BY perticular";
                    $bankReceiptR = mysqli_query($conn, $bankReceiptQ);
                    if($bankReceiptR && mysqli_num_rows($bankReceiptR) > 0){
                        $bankReceiptRow = mysqli_fetch_assoc($bankReceiptR);
                        $total_payment += (isset($bankReceiptRow['bank_payment']) && $bankReceiptRow['bank_payment'] != '') ? $bankReceiptRow['bank_payment'] : 0;;
                    }
                    /*----------BANK PAYMENT------------*/

                    /*-------JURNAL VOUCHER-----------*/
                        $journalVouQ = "SELECT SUM(jvd.credit) as total_amount FROM journal_vouchar_details jvd INNER JOIN journal_vouchar jv ON jvd.voucher_id = jv.id WHERE jv.pharmacy_id = '".$pharmacy_id."' AND jv.financial_id = '".$financial_id."' AND jvd.particular = '".$row['id']."' GROUP BY jvd.particular";
                        $journalVouR = mysqli_query($conn, $journalVouQ);
                        if($journalVouR && mysqli_num_rows($journalVouR) > 0){
                          $journalVouRow = mysqli_fetch_assoc($journalVouR);
                          $voucherAmount = (isset($journalVouRow['total_amount']) && $journalVouRow['total_amount'] != '') ? $journalVouRow['total_amount'] : 0;
                          $total_payment += $voucherAmount;
                        }
                    /*-------JURNAL VOUCHER-----------*/

                    $purchaseBillQ = "SELECT id, vouchar_date, voucher_no, purchase_type, total_total as final_amount FROM purchase WHERE purchase_type = 'Debit' AND cancel = 1 AND pharmacy_id = '".$pharmacy_id."' AND financial_id = '".$financial_id."' AND vendor = '".$row['id']."'";
                    
                    $purchaseBillR = mysqli_query($conn, $purchaseBillQ);
                    if($purchaseBillR && mysqli_num_rows($purchaseBillR) > 0){
                        while ($rows = mysqli_fetch_assoc($purchaseBillR)) {
                            $total_bill = (isset($rows['final_amount']) && $rows['final_amount'] != '') ? $rows['final_amount'] : 0;
                            if($total_bill < $total_payment){
                                $total_payment = $total_payment-$total_bill;
                            }else{
                                $remainingDay = $cr_days - $notificationDay;
                                $day = ($remainingDay > 0) ? $remainingDay : 0;
                                $billdate = date('d-m-Y',strtotime($rows['vouchar_date'].' +'.$day.' days'));
                                
                                if(date('d-m-Y') >= $billdate){
                                    $arr = $rows;
                                    $arr['vendor'] = $row;
                                    $arr['total_bill'] = $total_bill;
                                    $arr['total_payment'] = $total_payment;
                                    $remaining = ($total_bill-$total_payment);
                                    $arr['total_remaining'] = abs($remaining);
                                    $total_payment = 0;
    
                                    $data[] = $arr;
                                }
                            }
                        }
                    }
                }
            }
        return $data;
    }
    
    function getCreditLimitNotification(){
        global $conn;
        $data = [];
            $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
            $financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';

            $query = "SELECT id, name, crdays, crlimit FROM ledger_master WHERE pharmacy_id = '".$pharmacy_id."' AND group_id = 10 ORDER BY name";
            $res = mysqli_query($conn, $query);
            if($res && mysqli_num_rows($res) > 0){
                while ($row = mysqli_fetch_assoc($res)) {
                    $limit = CheckCr($row['id']);
                    $limit = ($limit != '') ? $limit : 0;

                    if($limit <= 0){
                        $row['current_limit'] = $limit;
                        $data[] = $row;
                    }

                }
            }
        return $data;
    }
    
    function countRunningBalance($id = null, $from = null, $to = null, $is_financial = 0){
        global $conn;
        $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
        $financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';
        
        $opening_balance = 0; $total_sale_debit = 0; $total_sale_return = 0; $total_purchase_debit = 0; $total_purchase_return = 0; $total_jurnal_debit = 0; $total_jurnal_credit = 0; $total_cash_receipt = 0; $total_cash_payment = 0; $total_bank_receipt = 0; $total_bank_payment = 0;

        if((isset($id) && $id != '') && (isset($pharmacy_id) && $pharmacy_id != '')){

            /*--------------------------------------------CALCULATE OPENING BALANCE START---------------------------------------*/
            $openingbalanceQ = "SELECT opening_balance, opening_balance_type, bank_name, bank_ac_no, branch_name, ifsc_code,group_id FROM  ledger_master WHERE pharmacy_id = '".$pharmacy_id."' AND id = '".$id."' AND status = 1 LIMIT 1";
            $openingbalanceR = mysqli_query($conn, $openingbalanceQ);
            if($openingbalanceR && mysqli_num_rows($openingbalanceR) > 0){
                $openingbalanceRow = mysqli_fetch_assoc($openingbalanceR);
                $opening_balance = (isset($openingbalanceRow['opening_balance']) && $openingbalanceRow['opening_balance'] != '') ? $openingbalanceRow['opening_balance'] : 0;
                $opening_balance = (isset($openingbalanceRow['opening_balance_type']) && $openingbalanceRow['opening_balance_type'] == 'DB') ? $opening_balance : -$opening_balance;

                if(isset($openingbalanceRow ['bank_name']) && $openingbalanceRow ['bank_name'] != ''){
                    $data['bank_detail']['bank_name'] = $openingbalanceRow ['bank_name'];
                    $data['bank_detail']['bank_ac_no'] = (isset($openingbalanceRow ['bank_ac_no'])) ? $openingbalanceRow ['bank_ac_no'] : '';
                    $data['bank_detail']['branch_name'] = (isset($openingbalanceRow ['branch_name'])) ? $openingbalanceRow ['branch_name'] : '';
                    $data['bank_detail']['ifsc_code'] = (isset($openingbalanceRow ['ifsc_code'])) ? $openingbalanceRow ['ifsc_code'] : '';
                }
            }
            /*--------------------------------------------CALCULATE OPENING BALANCE START---------------------------------------*/
            

            /*--------------------------------------------CALCULATE TAX BILLING START---------------------------------------*/
            $taxbillingQ = "SELECT SUM(final_amount) as total_bill FROM tax_billing WHERE pharmacy_id = '".$pharmacy_id."' AND customer_id = '".$id."' AND bill_type = 'Debit'";
            if(isset($is_financial) && $is_financial == 1){
                $taxbillingQ .= " AND financial_id = '".$financial_id."'";
            }
            if((isset($from) && $from != '') && (isset($to) && $to != '')){
              $taxbillingQ .= " AND invoice_date >= '".$from."' AND invoice_date <= '".$to."'";
            }
            $taxbillingQ .= " GROUP BY customer_id";
            $taxbillingR = mysqli_query($conn, $taxbillingQ);
            if($taxbillingR && mysqli_num_rows($taxbillingR) > 0){
                $taxbillingRow = mysqli_fetch_assoc($taxbillingR);
                $total_sale_debit = (isset($taxbillingRow['total_bill']) && $taxbillingRow['total_bill'] != '') ? $taxbillingRow['total_bill'] : 0;
            }
            /*--------------------------------------------CALCULATE TAX BILLING END-----------------------------------------*/

            /*--------------------------------------------CALCULATE SALE RETURN START---------------------------------------*/
            $saleReturnQ = "SELECT SUM(finalamount) as total_bill FROM sale_return WHERE pharmacy_id = '".$pharmacy_id."' AND customer_id = '".$id."'";
            if(isset($is_financial) && $is_financial == 1){
                $saleReturnQ .= " AND financial_id = '".$financial_id."'";
            }
            if((isset($from) && $from != '') && (isset($to) && $to != '')){
              $saleReturnQ .= " AND credit_note_date >= '".$from."' AND credit_note_date <= '".$to."'";
            }
            $saleReturnQ .= " GROUP BY customer_id";
            $saleReturnR = mysqli_query($conn, $saleReturnQ);
            if($saleReturnR && mysqli_num_rows($saleReturnR) > 0){
                $saleReturnRow = mysqli_fetch_assoc($saleReturnR);
                $total_sale_return = (isset($saleReturnRow['total_bill']) && $saleReturnRow['total_bill'] != '') ? $saleReturnRow['total_bill'] : 0;
            }

            /*--------------------------------------------CALCULATE SALE RETURN END-----------------------------------------*/

            /*--------------------------------------------CALCULATE PURCHASE START---------------------------------------*/
            $purchaseQ = "SELECT SUM(total_total) as total_purchase FROM purchase WHERE pharmacy_id = '".$pharmacy_id."' AND vendor = '".$id."' AND purchase_type = 'Debit'";
            if(isset($is_financial) && $is_financial == 1){
                $purchaseQ .= " AND financial_id = '".$financial_id."'";
            }
            if((isset($from) && $from != '') && (isset($to) && $to != '')){
              $purchaseQ .= " AND invoice_date >= '".$from."' AND invoice_date <= '".$to."'";
            }
            $purchaseQ .= " GROUP BY vendor";
            $purchaseR = mysqli_query($conn, $purchaseQ);
            if($purchaseR && mysqli_num_rows($purchaseR) > 0){
                $purchaseRow = mysqli_fetch_assoc($purchaseR);
                $total_purchase_debit = (isset($purchaseRow['total_purchase']) && $purchaseRow['total_purchase'] != '') ? $purchaseRow['total_purchase'] : 0;
            }
            /*--------------------------------------------CALCULATE PURCHASE END-----------------------------------------*/

            /*--------------------------------------------CALCULATE PURCHASE RETURN START-----------------------------------------*/
            $purchaseReturnQ = "SELECT SUM(finalamount) as total_purchase_return FROM purchase_return WHERE pharmacy_id = '".$pharmacy_id."' AND vendor_id = '".$id."'";
            if(isset($is_financial) && $is_financial == 1){
                $purchaseReturnQ .= " AND financial_id = '".$financial_id."'";
            }
            if((isset($from) && $from != '') && (isset($to) && $to != '')){
              $purchaseReturnQ .= " AND debit_note_date >= '".$from."' AND debit_note_date <= '".$to."'";
            }
            $purchaseReturnQ .= " GROUP BY vendor_id";
            $purchaseReturnR = mysqli_query($conn, $purchaseReturnQ);
            if($purchaseReturnR && mysqli_num_rows($purchaseReturnR) > 0){
                $purchaseReturnRow = mysqli_fetch_assoc($purchaseReturnR);
                $total_purchase_return = (isset($purchaseReturnRow['total_purchase_return']) && $purchaseReturnRow['total_purchase_return'] != '') ? $purchaseReturnRow['total_purchase_return'] : 0;
            }
            /*--------------------------------------------CALCULATE PURCHASE RETURN END-----------------------------------------*/

            /*--------------------------------------------CALCULATE CASH RECEIPT START---------------------------------------*/
            $cashReceiptQ = "SELECT SUM(amount) as cash_receipt FROM cash_transaction WHERE perticular = '".$id."' AND pharmacy_id = '".$pharmacy_id."' AND payment_type = 'receipt'";
            if(isset($is_financial) && $is_financial == 1){
                $cashReceiptQ .= " AND financial_id = '".$financial_id."'";
            }
            if((isset($from) && $from != '') && (isset($to) && $to != '')){
              $cashReceiptQ .= " AND voucher_date >= '".$from."' AND voucher_date <= '".$to."'";
            }
            $cashReceiptQ .= " GROUP BY perticular";
            $cashReceiptR = mysqli_query($conn, $cashReceiptQ);
            if($cashReceiptR && mysqli_num_rows($cashReceiptR) > 0){
                $cashReceiptRow = mysqli_fetch_assoc($cashReceiptR);
                $total_cash_receipt = (isset($cashReceiptRow['cash_receipt']) && $cashReceiptRow['cash_receipt'] != '') ? $cashReceiptRow['cash_receipt'] : 0;
            }
            /*--------------------------------------------CALCULATE CASH RECEIPT END-----------------------------------------*/

            /*--------------------------------------------CALCULATE CASH PAYMENT START---------------------------------------*/
            $cashPaymentQ = "SELECT SUM(amount) as cash_payment FROM cash_transaction WHERE perticular = '".$id."' AND pharmacy_id = '".$pharmacy_id."' AND payment_type = 'payment'";
            if(isset($is_financial) && $is_financial == 1){
                $cashPaymentQ .= " AND financial_id = '".$financial_id."'";
            }
            if((isset($from) && $from != '') && (isset($to) && $to != '')){
              $cashPaymentQ .= " AND voucher_date >= '".$from."' AND voucher_date <= '".$to."'";
            }
            $cashPaymentQ .= " GROUP BY perticular";
            $cashPaymentR = mysqli_query($conn, $cashPaymentQ);
            if($cashPaymentR && mysqli_num_rows($cashPaymentR) > 0){
                $cashPaymentRow = mysqli_fetch_assoc($cashPaymentR);
                $total_cash_payment = (isset($cashPaymentRow['cash_payment']) && $cashPaymentRow['cash_payment'] != '') ? $cashPaymentRow['cash_payment'] : 0;
            }
            /*--------------------------------------------CALCULATE CASH PAYMENT END-----------------------------------------*/

            /*--------------------------------------------CALCULATE BANK RECEIPT START---------------------------------------*/
            $bankReceiptQ = "SELECT SUM(amount) as bank_receipt FROM bank_transaction WHERE perticular = '".$id."' AND pharmacy_id = '".$pharmacy_id."' AND payment_type = 'receipt'";
            if(isset($is_financial) && $is_financial == 1){
                $bankReceiptQ .= " AND financial_id = '".$financial_id."'";
            }
            if((isset($from) && $from != '') && (isset($to) && $to != '')){
              $bankReceiptQ .= " AND voucher_date >= '".$from."' AND voucher_date <= '".$to."'";
            }
            $bankReceiptQ .= " GROUP BY perticular";
            $bankReceiptR = mysqli_query($conn, $bankReceiptQ);
            if($bankReceiptR && mysqli_num_rows($bankReceiptR) > 0){
                $bankReceiptRow = mysqli_fetch_assoc($bankReceiptR);
                $total_bank_receipt = (isset($bankReceiptRow['bank_receipt']) && $bankReceiptRow['bank_receipt'] != '') ? $bankReceiptRow['bank_receipt'] : 0;
            }
            /*--------------------------------------------CALCULATE BANK RECEIPT END-----------------------------------------*/

            /*--------------------------------------------CALCULATE BANK PAYMENT START---------------------------------------*/
            $bankPaymentQ = "SELECT SUM(amount) as bank_payment FROM bank_transaction WHERE perticular = '".$id."' AND pharmacy_id = '".$pharmacy_id."' AND payment_type = 'payment'";
            if(isset($is_financial) && $is_financial == 1){
                $bankPaymentQ .= " AND financial_id = '".$financial_id."'";
            }
            if((isset($from) && $from != '') && (isset($to) && $to != '')){
              $bankPaymentQ .= " AND voucher_date >= '".$from."' AND voucher_date <= '".$to."'";
            }
            $bankPaymentQ .= " GROUP BY perticular";
            $bankPaymentR = mysqli_query($conn, $bankPaymentQ);
            if($bankPaymentR && mysqli_num_rows($bankPaymentR) > 0){
                $bankPaymentRow = mysqli_fetch_assoc($bankPaymentR);
                $total_bank_payment = (isset($bankPaymentRow['bank_payment']) && $bankPaymentRow['bank_payment'] != '') ? $bankPaymentRow['bank_payment'] : 0;
            }
            /*--------------------------------------------CALCULATE BANK PAYMENT END-----------------------------------------*/

            /*-------------------------------------------------COUNT JOURNAL VOUCHER START----------------------------------------------------*/
            $jurnalQ = "SELECT SUM(jvd.debit) as total_debit, SUM(jvd.credit) as total_credit FROM journal_vouchar_details jvd INNER JOIN journal_vouchar jv ON jvd.voucher_id = jv.id WHERE jvd.particular = '".$id."' AND jv.pharmacy_id = '".$pharmacy_id."'";
            if(isset($is_financial) && $is_financial == 1){
                $jurnalQ .= " AND jv.financial_id = '".$financial_id."'";
            }
            if((isset($from) && $from != '') && (isset($to) && $to != '')){
              $jurnalQ .= " AND jv.voucher_date >= '".$from."' AND jv.voucher_date <= '".$to."'";
            }
            $jurnalQ .= " GROUP BY jvd.particular";
            $jurnalR = mysqli_query($conn, $jurnalQ);
            $jurnalRow = ($jurnalR && mysqli_num_rows($jurnalR) > 0) ? mysqli_fetch_assoc($jurnalR) : '';
            $total_jurnal_debit = (isset($jurnalRow['total_debit']) && $jurnalRow['total_debit'] != '') ? $jurnalRow['total_debit'] : 0;
            $total_jurnal_credit = (isset($jurnalRow['total_credit']) && $jurnalRow['total_credit'] != '') ? $jurnalRow['total_credit'] : 0;
            /*-------------------------------------------------COUNT JOURNAL VOUCHER START----------------------------------------------------*/

        }
        if(isset($openingbalanceRow['group_id']) && $openingbalanceRow['group_id'] == 10){
            // CUSTOMER
            $running_balance = ($opening_balance) + ($total_sale_debit + $total_cash_payment + $total_bank_payment + $total_jurnal_debit) - ($total_sale_return + $total_cash_receipt + $total_bank_receipt + $total_jurnal_credit);
        }elseif(isset($openingbalanceRow['group_id']) && $openingbalanceRow['group_id'] == 14){
            // VENDOR
            $running_balance = ($opening_balance) + ($total_purchase_return + $total_cash_payment + $total_bank_payment + $total_jurnal_debit) - ($total_purchase_debit + $total_cash_receipt + $total_bank_receipt + $total_jurnal_credit);
        }else{
            // OTHER
            $running_balance = ($opening_balance) + ($total_cash_payment + $total_bank_payment + $total_jurnal_debit) - ($total_cash_receipt + $total_bank_receipt + $total_jurnal_credit);
        }
        
        $data['total_payment'] = ($total_cash_payment+$total_bank_payment+$total_jurnal_debit);
        $data['total_receipt'] = ($total_cash_receipt+$total_bank_receipt+$total_jurnal_credit);
        $data['total_sale'] = ($total_sale_debit-$total_sale_return);
        $data['total_purchase'] = ($total_purchase_return-$total_purchase_debit);
        $data['opening_balance'] = $opening_balance;
        $data['running_balance'] = (isset($running_balance) && $running_balance != '') ? $running_balance : 0;
        return $data;
    }
    
    // 31/01/2019 Gautam makwana
    function countCashRunningBalance($from = null, $to = null, $is_financial = 0){
        global $conn;
        $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
        $financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';
        $data = [];

        $opening_balance = 0; $total_cash_receipt = 0; $total_cash_payment = 0; $total_cash_bill_sale = 0;$total_cash_bill_purchase = 0;
        /*----------------------------------CALCULATE OPENING BALANCE START----------------------------*/

            $openingBalanceQ = "SELECT SUM(opening_balance) as total_opening_balance FROM ledger_master WHERE group_id = 6 AND pharmacy_id = '".$pharmacy_id."'";
            $openingBalanceR = mysqli_query($conn, $openingBalanceQ);
            if($openingBalanceR && mysqli_num_rows($openingBalanceR) > 0){
                $openingBalanceRow = mysqli_fetch_assoc($openingBalanceR);
                $opening_balance = (isset($openingBalanceRow['total_opening_balance']) && $openingBalanceRow['total_opening_balance'] != '') ? $openingBalanceRow['total_opening_balance'] : 0;
            }
        /*-----------------------------------CALCULATE OPENING BALANCE END--------------------------------*/

        /*----------------------------------CALCULATE TAX BILLING CASH START--------------------------------*/
            $cashBillSaleQ = "SELECT SUM(final_amount) as total_amount FROM tax_billing WHERE pharmacy_id = '".$pharmacy_id."' AND bill_type = 'Cash' AND cancel = 1";
            if(isset($is_financial) && $is_financial == 1){
                $cashBillSaleQ .= " AND financial_id = '".$financial_id."'";
            }
            if((isset($from) && $from != '') && (isset($to) && $to != '')){
              $cashBillSaleQ .= " AND invoice_date >= '".$from."' AND invoice_date <= '".$to."'";
            }
            $cashBillSaleR = mysqli_query($conn, $cashBillSaleQ);
            if($cashBillSaleR && mysqli_num_rows($cashBillSaleR) > 0){
                $cashBillSaleRow = mysqli_fetch_assoc($cashBillSaleR);
                $total_cash_bill_sale = (isset($cashBillSaleRow['total_amount']) && $cashBillSaleRow['total_amount'] != '') ? $cashBillSaleRow['total_amount'] : 0;
            }
        /*----------------------------------CALCULATE TAX BILLING CASH END----------------------------------*/

        /*----------------------------------CALCULATE PURCHASE BILLING CASH START--------------------------------*/
            $cashBillPurchaseQ = "SELECT SUM(total_total) as total_amount FROM purchase WHERE pharmacy_id = '".$pharmacy_id."' AND purchase_type = 'Cash' AND cancel = 1";
            if(isset($is_financial) && $is_financial == 1){
                $cashBillPurchaseQ .= " AND financial_id = '".$financial_id."'";
            }
            if((isset($from) && $from != '') && (isset($to) && $to != '')){
              $cashBillPurchaseQ .= " AND invoice_date >= '".$from."' AND invoice_date <= '".$to."'";
            }
            $cashBillPurchaseR = mysqli_query($conn, $cashBillPurchaseQ);
            if($cashBillPurchaseR && mysqli_num_rows($cashBillPurchaseR) > 0){
                $cashBillPurchaseRow = mysqli_fetch_assoc($cashBillPurchaseR);
                $total_cash_bill_purchase = (isset($cashBillPurchaseRow['total_amount']) && $cashBillPurchaseRow['total_amount'] != '') ? $cashBillPurchaseRow['total_amount'] : 0;
            }
        /*----------------------------------CALCULATE PURCHASE BILLING CASH END----------------------------------*/

        /*----------------------------------CALCULATE CASH PAYMENT START--------------------------------*/
        $cashPaymentQ = "SELECT SUM(amount) AS total_amount FROM cash_transaction WHERE pharmacy_id = '".$pharmacy_id."' AND payment_type = 'payment'";
        if(isset($is_financial) && $is_financial == 1){
            $cashPaymentQ .= " AND financial_id = '".$financial_id."'";
        }
        if((isset($from) && $from != '') && (isset($to) && $to != '')){
          $cashPaymentQ .= " AND voucher_date >= '".$from."' AND voucher_date <= '".$to."'";
        }
        $cashPaymentR = mysqli_query($conn, $cashPaymentQ);
        if($cashPaymentR && mysqli_num_rows($cashPaymentR) > 0){
            $cashPaymentRow = mysqli_fetch_assoc($cashPaymentR);
            $total_cash_payment = (isset($cashPaymentRow['total_amount']) && $cashPaymentRow['total_amount'] != '') ? $cashPaymentRow['total_amount'] : 0;
        }
        /*----------------------------------CALCULATE CASH PAYMENT END----------------------------------*/

        /*----------------------------------CALCULATE CASH RECEIPT START--------------------------------*/
        $cashReceiptQ = "SELECT SUM(amount) AS total_amount FROM cash_transaction WHERE pharmacy_id = '".$pharmacy_id."' AND payment_type = 'receipt'";
        if(isset($is_financial) && $is_financial == 1){
            $cashReceiptQ .= " AND financial_id = '".$financial_id."'";
        }
        if((isset($from) && $from != '') && (isset($to) && $to != '')){
          $cashReceiptQ .= " AND voucher_date >= '".$from."' AND voucher_date <= '".$to."'";
        }
        $cashReceiptR = mysqli_query($conn, $cashReceiptQ);
        if($cashReceiptR && mysqli_num_rows($cashReceiptR) > 0){
            $cashReceiptRow = mysqli_fetch_assoc($cashReceiptR);
            $total_cash_receipt = (isset($cashReceiptRow['total_amount']) && $cashReceiptRow['total_amount'] != '') ? $cashReceiptRow['total_amount'] : 0;
        }
        /*----------------------------------CALCULATE CASH RECEIPT END----------------------------------*/
      
        $data['running_balance'] = ($opening_balance+$total_cash_bill_sale+$total_cash_receipt) - ($total_cash_bill_purchase+$total_cash_payment);
        return $data;
    }

    // 31/01/2019 Gautam makwana
    function cashLedger($from = null, $to = null, $is_financial = 0){
        $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
        $financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';

        $data = [];
        global $conn;

            $opening_balance = 0;
            if((isset($pharmacy_id) && $pharmacy_id != '') && (isset($financial_id) && $financial_id != '')){

                /*----------------------------------CALCULATE OPENING BALANCE START----------------------------*/
                    $openingBalanceQ = "SELECT SUM(opening_balance) as total_opening_balance FROM ledger_master WHERE group_id = 6 AND pharmacy_id = '".$pharmacy_id."'";
                    $openingBalanceR = mysqli_query($conn, $openingBalanceQ);
                    if($openingBalanceR && mysqli_num_rows($openingBalanceR) > 0){
                        $openingBalanceRow = mysqli_fetch_assoc($openingBalanceR);
                        $opening_balance = (isset($openingBalanceRow['total_opening_balance']) && $openingBalanceRow['total_opening_balance'] != '') ? $openingBalanceRow['total_opening_balance'] : 0;
                    }
                /*-----------------------------------CALCULATE OPENING BALANCE END--------------------------------*/

                /*----------------------------------CALCULATE TAX BILLING CASH START--------------------------------*/
                    $cashBillSaleQ = "SELECT tb.id, tb.invoice_date, tb.invoice_no, tb.final_amount as total_amount, lg.name as customer_name FROM tax_billing tb LEFT JOIN ledger_master lg ON tb.customer_id = lg.id WHERE tb.pharmacy_id = '".$pharmacy_id."' AND tb.bill_type = 'Cash' AND tb.cancel = 1";
                    if(isset($is_financial) && $is_financial == 1){
                        $cashBillSaleQ .= " AND tb.financial_id = '".$financial_id."'";
                    }
                    if((isset($from) && $from != '') && (isset($to) && $to != '')){
                      $cashBillSaleQ .= " AND tb.invoice_date >= '".$from."' AND tb.invoice_date <= '".$to."'";
                    }
                    $cashBillSaleR = mysqli_query($conn, $cashBillSaleQ);
                    if($cashBillSaleR && mysqli_num_rows($cashBillSaleR) > 0){
                        while ($cashBillSaleRow = mysqli_fetch_assoc($cashBillSaleR)) {
                            $arr1['type'] = 'taxBill';
                            $arr1['type_name'] = 'Cash Receipt';
                            $arr1['id'] = $cashBillSaleRow['id'];
                            $arr1['date'] = (isset($cashBillSaleRow['invoice_date'])) ? $cashBillSaleRow['invoice_date'] : '';
                            $customer = (isset($cashBillSaleRow['customer_name']) && $cashBillSaleRow['customer_name'] != '') ? $cashBillSaleRow['customer_name'] : 'Unknown Customer';
                            $invoice_no = (isset($cashBillSaleRow['invoice_no'])) ? $cashBillSaleRow['invoice_no'] : '-';
                            $arr1['narration'] = 'Sale Bill Invoice No - '.$invoice_no.' - '.$customer;
                            $arr1['receipt'] = (isset($cashBillSaleRow['total_amount']) && $cashBillSaleRow['total_amount'] != '') ? $cashBillSaleRow['total_amount'] : 0;
                            $arr1['payment'] = '';
                            $arr1['url'] = 'sales-tax-billing.php?id='.$cashBillSaleRow['id'];
                            $data[] = $arr1;
                        }
                    }
                /*----------------------------------CALCULATE TAX BILLING CASH END----------------------------------*/

                /*----------------------------------CALCULATE PURCHASE BILLING CASH START--------------------------------*/
                    $cashBillPurchaseQ = "SELECT pr.id, pr.voucher_no, pr.vouchar_date, pr.total_total as total_amount, lg.name as vendor_name FROM purchase pr LEFT JOIN ledger_master lg ON pr.vendor = lg.id WHERE pr.pharmacy_id = '".$pharmacy_id."' AND pr.purchase_type = 'Cash' AND pr.cancel = 1";
                    if(isset($is_financial) && $is_financial == 1){
                        $cashBillPurchaseQ .= " AND pr.financial_id = '".$financial_id."'";
                    }
                    if((isset($from) && $from != '') && (isset($to) && $to != '')){
                      $cashBillPurchaseQ .= " AND pr.invoice_date >= '".$from."' AND pr.invoice_date <= '".$to."'";
                    }
                    $cashBillPurchaseR = mysqli_query($conn, $cashBillPurchaseQ);
                    if($cashBillPurchaseR && mysqli_num_rows($cashBillPurchaseR) > 0){
                        while ($cashBillPurchaseRow = mysqli_fetch_assoc($cashBillPurchaseR)) {
                            $arr2['type'] = 'purchaseBill';
                            $arr2['type_name'] = 'Cash Payment';
                            $arr2['id'] = $cashBillPurchaseRow['id'];
                            $arr2['date'] = (isset($cashBillPurchaseRow['vouchar_date'])) ? $cashBillPurchaseRow['vouchar_date'] : '';
                            $vendor = (isset($cashBillPurchaseRow['vendor_name']) && $cashBillPurchaseRow['vendor_name'] != '') ? $cashBillPurchaseRow['vendor_name'] : 'Unknown Vendor';
                            $voucher_no = (isset($cashBillPurchaseRow['voucher_no'])) ? $cashBillPurchaseRow['voucher_no'] : '-';
                            $arr2['narration'] = 'Purchase Bill Voucher No - '.$voucher_no.' - '.$vendor;
                            $arr2['receipt'] = '';
                            $arr2['payment'] = (isset($cashBillPurchaseRow['total_amount']) && $cashBillPurchaseRow['total_amount'] != '') ? $cashBillPurchaseRow['total_amount'] : 0;
                            $arr2['url'] = 'purchase.php?id='.$cashBillPurchaseRow['id'];
                            $data[] = $arr2;
                        }
                    }
                /*----------------------------------CALCULATE PURCHASE BILLING CASH END----------------------------------*/

                /*-----------------------------------CASH TRANSACTION START--------------------------------------------*/
                $cashTransactionQ = "SELECT ct.id, ct.payment_type, ct.voucher_no, ct.voucher_date, ct.amount, ct.narration, lg.name as party_name FROM `cash_transaction` ct LEFT JOIN ledger_master lg ON ct.perticular = lg.id WHERE ct.pharmacy_id = '".$pharmacy_id."'";
                if(isset($is_financial) && $is_financial == 1){
                    $cashTransactionQ .= " AND ct.financial_id = '".$financial_id."'";
                }
                if((isset($from) && $from != '') && (isset($to) && $to != '')){
                  $cashTransactionQ .= " AND ct.voucher_date >= '".$from."' AND ct.voucher_date <= '".$to."'";
                }
                $cashTransactionR = mysqli_query($conn, $cashTransactionQ);
                if($cashTransactionR && mysqli_num_rows($cashTransactionR) > 0){
                    while ($cashTransactionRow = mysqli_fetch_assoc($cashTransactionR)) {
                        $cashLable = (isset($cashTransactionRow['payment_type'])) ? ucwords($cashTransactionRow['payment_type']) : '';
                        $arr3['type'] = 'cashTransaction';
                        $arr3['type_name'] = 'Cash '.$cashLable;
                        $arr3['id'] = $cashTransactionRow['id'];
                        $arr3['date'] = (isset($cashTransactionRow['voucher_date'])) ? $cashTransactionRow['voucher_date'] : '';
                        $party = (isset($cashTransactionRow['party_name']) && $cashTransactionRow['party_name'] != '') ? ' - '.$cashTransactionRow['party_name'] : '';
                        $voucher_no = (isset($cashTransactionRow['voucher_no']) && $cashTransactionRow['voucher_no'] != '') ? ' - '.$cashTransactionRow['voucher_no'] : '';
                        $Cnarration = (isset($cashTransactionRow['narration']) && trim($cashTransactionRow['narration']) != '') ? ' - '.$cashTransactionRow['narration'] : '';
                        $arr3['narration'] = 'Cash '.$cashLable.''.$voucher_no.''.$Cnarration.''.$party;
                        if(isset($cashTransactionRow['payment_type']) && $cashTransactionRow['payment_type'] == 'receipt'){
                            $arr3['receipt'] = (isset($cashTransactionRow['amount']) && $cashTransactionRow['amount'] != '') ? $cashTransactionRow['amount'] : 0;
                            $arr3['payment'] = '';
                            $arr3['url'] = 'cash-receipt.php?id='.$cashTransactionRow['id'];
                        }else{
                            $arr3['receipt'] = '';
                            $arr3['payment'] = (isset($cashTransactionRow['amount']) && $cashTransactionRow['amount'] != '') ? $cashTransactionRow['amount'] : 0;
                            $arr3['url'] = 'cash-payment.php?id='.$cashTransactionRow['id'];
                        }
                        $data[] = $arr3;
                    }
                }
                /*-----------------------------------CASH TRANSACTION END----------------------------------------------*/

            }
            $final_array = $data;

            function date_compare($a, $b){

                $t1 = strtotime($a['date']);

                $t2 = strtotime($b['date']);

                return $t1 - $t2;
            }    

            usort($final_array, 'date_compare');

            $finaldata['opening_balance'] = $opening_balance;
            $countRunningBalance = countCashRunningBalance($from, $to, $is_financial);
            $finaldata['running_balance'] = (isset($countRunningBalance['running_balance']) && $countRunningBalance['running_balance'] != '') ? $countRunningBalance['running_balance'] : 0;
            $finaldata['data'] = $final_array;
            return $finaldata;
    }
    
    function customerLedger($customer_id = null, $from = null, $to = null, $is_financial = 0){
        $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
        $financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';

        $data = [];
        global $conn;
        $opening_balance = 0;
        if((isset($pharmacy_id) && $pharmacy_id != '') && (isset($customer_id) && $customer_id != '')){

            /*---------------------------------------------COUNT OPENING BALANCE START-------------------------------------------------*/
            $opening_balance = 0;
            $customerQ =  "SELECT id, name, opening_balance, opening_balance_type FROM ledger_master WHERE id = '".$customer_id."' AND pharmacy_id = '".$pharmacy_id."' LIMIT 1";
            $customerR = mysqli_query($conn, $customerQ);
            if($customerR && mysqli_num_rows($customerR) > 0){
                $customerRow = mysqli_fetch_assoc($customerR);
                $opening_balance = (isset($customerRow['opening_balance']) && $customerRow['opening_balance'] != '') ? $customerRow['opening_balance'] : 0;
                $opening_balance = (isset($customerRow['opening_balance_type']) && $customerRow['opening_balance_type'] == 'DB') ? $opening_balance : -$opening_balance;
            }
            /*-----------------------------------------------COUNT OPENING BALANCE END-------------------------------------------------------*/

            /*-----------------------------------------------COUNT DABIT TAX BILL START-------------------------------------------------------*/
            $billQ =  "SELECT id, invoice_date, invoice_no, final_amount FROM tax_billing WHERE pharmacy_id = '".$pharmacy_id."' AND customer_id = '".$customer_id."' AND bill_type = 'Debit'";
            if(isset($is_financial) && $is_financial == 1){
                $billQ .= " AND financial_id = '".$financial_id."'";
            }
            if((isset($from) && $from != '') && (isset($to) && $to != '')){
              $billQ .= " AND invoice_date >= '".$from."' AND invoice_date <= '".$to."'";
            }
            $billR = mysqli_query($conn, $billQ);
            if($billR && mysqli_num_rows($billR) > 0){
                while ($billRow = mysqli_fetch_assoc($billR)) {
                  $billarr['type'] = 'taxBill';
                  $billarr['id'] = $billRow['id'];
                  $billarr['date'] = (isset($billRow['invoice_date'])) ? $billRow['invoice_date'] : '';
                  $billarr['sr_no'] = (isset($billRow['invoice_no'])) ? $billRow['invoice_no'] : '';
                  $billarr['narration'] = (isset($billRow['invoice_no'])) ? 'Tax Bill | '.$billRow['invoice_no'] : 'Tax Bill';
                  $billarr['debit'] = (isset($billRow['final_amount']) && $billRow['final_amount'] != '') ? $billRow['final_amount'] : 0;
                  $billarr['credit'] = '';
                  $billarr['url'] = 'sales-tax-billing.php?id='.$billRow['id'];
                  $data[] = $billarr;
                }
            }
            /*------------------------------------------------COUNT DABIT TAX BILL END-------------------------------------------------------*/

            /*-----------------------------------------------COUNT SALE RETURN START-------------------------------------------------------*/
            $saleReturnQ =  "SELECT id, credit_note_date, credit_note_no, finalamount FROM sale_return WHERE pharmacy_id = '".$pharmacy_id."' AND customer_id = '".$customer_id."'";
            if(isset($is_financial) && $is_financial == 1){
                $saleReturnQ .= " AND financial_id = '".$financial_id."'";
            }
            if((isset($from) && $from != '') && (isset($to) && $to != '')){
              $saleReturnQ .= " AND credit_note_date >= '".$from."' AND credit_note_date <= '".$to."'";
            }
            $saleReturnR = mysqli_query($conn, $saleReturnQ);
            if($saleReturnR && mysqli_num_rows($saleReturnR) > 0){
                while ($saleReturnRow = mysqli_fetch_assoc($saleReturnR)) {
                  $billReturnarr['type'] = 'taxBillReturn';
                  $billReturnarr['id'] = $saleReturnRow['id'];
                  $billReturnarr['date'] = (isset($saleReturnRow['credit_note_date'])) ? $saleReturnRow['credit_note_date'] : '';
                  $billReturnarr['sr_no'] = (isset($saleReturnRow['credit_note_no'])) ? $saleReturnRow['credit_note_no'] : '';
                  $billReturnarr['narration'] = (isset($saleReturnRow['credit_note_no'])) ? 'Credit Note | '.$saleReturnRow['credit_note_no'] : 'Credit Note';
                  $billReturnarr['debit'] = '';
                  $billReturnarr['credit'] = (isset($saleReturnRow['finalamount']) && $saleReturnRow['finalamount'] != '') ? $saleReturnRow['finalamount'] : 0;
                  $billReturnarr['url'] = 'sales-return.php?id='.$saleReturnRow['id'];
                  $data[] = $billReturnarr;
                }
            }
            /*------------------------------------------------COUNT SALE RETURN END-------------------------------------------------------*/

            /*----------------------------------------------------CASH TRANSACTION START-----------------------------------------*/
            $cashTransactionQ = "SELECT id, payment_type, voucher_no, voucher_date, amount, narration FROM cash_transaction WHERE pharmacy_id = '".$pharmacy_id."' AND perticular = '".$customer_id."'";
            if(isset($is_financial) && $is_financial == 1){
                $cashTransactionQ .= " AND financial_id = '".$financial_id."'";
            }
            if((isset($from) && $from != '') && (isset($to) && $to != '')){
              $cashTransactionQ .= " AND voucher_date >= '".$from."' AND voucher_date <= '".$to."'";
            }
            $cashTransactionR = mysqli_query($conn, $cashTransactionQ);
            if($cashTransactionR && mysqli_num_rows($cashTransactionR) > 0){
                while ($cashTransactionRow = mysqli_fetch_assoc($cashTransactionR)) {
                    $casharr['type'] = 'cashTransaction';
                    $casharr['id'] = $cashTransactionRow['id'];
                    $casharr['date'] = (isset($cashTransactionRow['voucher_date'])) ? $cashTransactionRow['voucher_date'] : '';
                    $casharr['sr_no'] = (isset($cashTransactionRow['voucher_no'])) ? $cashTransactionRow['voucher_no'] : '';
                    $cashVoucherNo = (isset($cashTransactionRow['voucher_no']) && $cashTransactionRow['voucher_no'] != '') ? ' - '.$cashTransactionRow['voucher_no'] : '';
                    $cashNarration = (isset($cashTransactionRow['narration']) && $cashTransactionRow['narration'] != '') ? ' - '.$cashTransactionRow['narration'] : '';
                    $cashLable = (isset($cashTransactionRow['payment_type']) && $cashTransactionRow['payment_type'] == 'receipt') ? 'Cash Receipt' : 'Cash Payment';
                    $casharr['narration'] = $cashLable.''.$cashVoucherNo.''.$cashNarration;
                    if(isset($cashTransactionRow['payment_type']) && $cashTransactionRow['payment_type'] == 'receipt'){
                        $casharr['debit'] = '';
                        $casharr['credit'] = (isset($cashTransactionRow['amount']) && $cashTransactionRow['amount'] != '') ? $cashTransactionRow['amount'] : 0;
                        $casharr['url'] = 'cash-receipt.php?id='.$cashTransactionRow['id'];
                    }else{
                        $casharr['debit'] = (isset($cashTransactionRow['amount']) && $cashTransactionRow['amount'] != '') ? $cashTransactionRow['amount'] : 0;
                        $casharr['credit'] = '';
                        $casharr['url'] = 'cash-payment.php?id='.$cashTransactionRow['id'];
                    }
                    $data[] = $casharr;
                }
            }
            /*----------------------------------------------------CASH TRANSACTION END------------------------------------------*/

            /*----------------------------------------------------BANK TRANSACTION START-----------------------------------------*/
            $bankTransactionQ = "SELECT bt.id, bt.payment_type, bt.voucher_no, bt.voucher_date, bt.amount, bt.payment_mode, bt.payment_mode_date, bt.payment_mode_no, bt.card_name, bt.other_reference, lg.name as bank_name FROM bank_transaction bt LEFT JOIN ledger_master lg ON bt.bank_id = lg.id WHERE bt.pharmacy_id = '".$pharmacy_id."' AND bt.perticular = '".$customer_id."'";
            if(isset($is_financial) && $is_financial == 1){
                $bankTransactionQ .= " AND bt.financial_id = '".$financial_id."'";
            }
            if((isset($from) && $from != '') && (isset($to) && $to != '')){
              $bankTransactionQ .= " AND bt.voucher_date >= '".$from."' AND bt.voucher_date <= '".$to."'";
            }
            $bankTransactionR = mysqli_query($conn, $bankTransactionQ);
            if($bankTransactionR && mysqli_num_rows($bankTransactionR) > 0){
                while ($bankTransactionRow = mysqli_fetch_assoc($bankTransactionR)) {
                    $bankarr['type'] = 'bankTransaction';
                    $bankarr['id'] = $bankTransactionRow['id'];
                    $bankarr['date'] = (isset($bankTransactionRow['voucher_date'])) ? $bankTransactionRow['voucher_date'] : '';
                    $bankarr['sr_no'] = (isset($bankTransactionRow['voucher_no'])) ? $bankTransactionRow['voucher_no'] : '';

                    $cashVoucherNo = (isset($bankTransactionRow['voucher_no']) && $bankTransactionRow['voucher_no'] != '') ? ' - '.$bankTransactionRow['voucher_no'] : '';
                    $paymentDate = (isset($bankTransactionRow['payment_mode_date']) && $bankTransactionRow['payment_mode_date'] != '0000-00-00') ? date('d/m/Y',strtotime($bankTransactionRow['payment_mode_date'])) : '';
                    $paymentNo = (isset($bankTransactionRow['payment_mode_no']) && $bankTransactionRow['payment_mode_no'] != '') ? $bankTransactionRow['payment_mode_no'] : '';
                    $cardName = (isset($bankTransactionRow['card_name']) && $bankTransactionRow['card_name'] != '') ? $bankTransactionRow['card_name'] : '';
                    $otherRef = (isset($bankTransactionRow['other_reference']) && $bankTransactionRow['other_reference'] != '') ? $bankTransactionRow['other_reference'] : '';
                    $bankName = (isset($bankTransactionRow['bank_name']) && $bankTransactionRow['bank_name'] != '') ? $bankTransactionRow['bank_name'] : '';

                    $cashLable = (isset($bankTransactionRow['payment_type']) && $bankTransactionRow['payment_type'] == 'receipt') ? 'Bank Receipt' : 'Bank Payment';
                    $paymentMode = (isset($bankTransactionRow['payment_mode'])) ? $bankTransactionRow['payment_mode'] : '';

                    if($paymentMode == 'cheque'){
                        $narration =  $cashLable.''.$cashVoucherNo.' - '.$bankName.' - Cheque No: '.$paymentNo.' - Cheque Date: '.$paymentDate;
                    }elseif($paymentMode == 'dd'){
                        $narration =  $cashLable.''.$cashVoucherNo.' - '.$bankName.' - DD No: '.$paymentNo.' - DD Date: '.$paymentDate;
                    }elseif($paymentMode == 'net_banking'){
                        $narration =  $cashLable.''.$cashVoucherNo.' - '.$bankName.' - Net Banking UTR NO: '.$paymentNo;
                    }elseif($paymentMode == 'credit_debit_card'){
                        $narration =  $cashLable.''.$cashVoucherNo.' - '.$bankName.' - Card No: '.$paymentNo.' - Card Name: '.$cardName;
                    }elseif($paymentMode == 'other'){
                        $narration =  $cashLable.''.$cashVoucherNo.' - '.$bankName.' - Other Reference: '.$otherRef;
                    }else{
                        $narration =  $cashLable.''.$cashVoucherNo.' - '.$bankName;
                    }


                    $bankarr['narration'] = $narration;
                    if(isset($bankTransactionRow['payment_type']) && $bankTransactionRow['payment_type'] == 'receipt'){
                        $bankarr['debit'] = '';
                        $bankarr['credit'] = (isset($bankTransactionRow['amount']) && $bankTransactionRow['amount'] != '') ? $bankTransactionRow['amount'] : 0;
                        $bankarr['url'] = 'bank-receipt.php?id='.$bankTransactionRow['id'];
                    }else{
                        $bankarr['debit'] = (isset($bankTransactionRow['amount']) && $bankTransactionRow['amount'] != '') ? $bankTransactionRow['amount'] : 0;
                        $bankarr['credit'] = '';
                        $bankarr['url'] = 'bank-payment.php?id='.$bankTransactionRow['id'];
                    }
                    $data[] = $bankarr;
                }
            }
            /*----------------------------------------------------BANK TRANSACTION END------------------------------------------*/


            /*-----------------------------------------------------COUNT JOURNAL VOUCHER START-----------------------------------------------*/
            $jurnalVoucherQ = "SELECT jv.id, jv.voucher_date, jvd.debit, jvd.credit, jvd.remarks FROM journal_vouchar_details jvd INNER JOIN journal_vouchar jv ON jvd.voucher_id = jv.id WHERE jvd.particular = '".$customer_id."' AND jv.pharmacy_id = '".$pharmacy_id."'";
            if(isset($is_financial) && $is_financial == 1){
                $jurnalVoucherQ .= " AND jv.financial_id = '".$financial_id."'";
            }
            if((isset($from) && $from != '') && (isset($to) && $to != '')){
              $jurnalVoucherQ .= " AND jv.voucher_date >= '".$from."' AND jv.voucher_date <= '".$to."'";
            }
            $jurnalVoucherR = mysqli_query($conn, $jurnalVoucherQ);
            if($jurnalVoucherR && mysqli_num_rows($jurnalVoucherR) > 0){
                while ($jurnalVoucherRow = mysqli_fetch_assoc($jurnalVoucherR)) {
                  $jurnalarr['type'] = 'journalVoucher';
                  $jurnalarr['id'] = $jurnalVoucherRow['id'];
                  $jurnalarr['date'] = (isset($jurnalVoucherRow['voucher_date'])) ? $jurnalVoucherRow['voucher_date'] : '';
                  $jurnalarr['sr_no'] = '';
                  $jremarks = (isset($jurnalVoucherRow['remarks']) && $jurnalVoucherRow['remarks'] != '') ? ' - '.$jurnalVoucherRow['remarks'] : '';
                  $jurnalarr['narration'] = 'Journal Voucher'.$jremarks;
                  $jurnalarr['debit'] = (isset($jurnalVoucherRow['debit']) && $jurnalVoucherRow['debit'] != 0) ? $jurnalVoucherRow['debit'] : '';
                  $jurnalarr['credit'] = (isset($jurnalVoucherRow['credit']) && $jurnalVoucherRow['credit'] != 0) ? $jurnalVoucherRow['credit'] : '';
                  $jurnalarr['url'] = 'journal-vouchar.php?id='.$jurnalVoucherRow['id'];
                  
                  $data[] = $jurnalarr;
                }
            }
            /*-----------------------------------------------------COUNT JOURNAL VOUCHER END-------------------------------------------------*/
            
        }

        $final_array = $data;

        function date_compare($a, $b){

            $t1 = strtotime($a['date']);

            $t2 = strtotime($b['date']);

            return $t1 - $t2;
        }    

        usort($final_array, 'date_compare');

        $finaldata['opening_balance'] = $opening_balance;
        $finaldata['data'] = $final_array;
        $countRunningBalance = countRunningBalance($customer_id, $from, $to, 1);
        $finaldata['running_balance'] = (isset($countRunningBalance['running_balance']) && $countRunningBalance['running_balance'] != '') ? $countRunningBalance['running_balance'] : 0;
        return $finaldata;
    }
    
    function vendorLedger($vendor_id = null, $from = null, $to = null, $is_financial = 0){
        $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
        $financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';

        $data = [];
        global $conn;
        $opening_balance = 0;
        if((isset($pharmacy_id) && $pharmacy_id != '') && (isset($vendor_id) && $vendor_id != '')){

            /*----------------------------------------------------COUNT OPENING BALANCE START-------------------------------------------------------*/
            $opening_balance = 0;
            $vendorQ =  "SELECT id, name, opening_balance, opening_balance_type FROM ledger_master WHERE id = '".$vendor_id."' AND pharmacy_id = '".$pharmacy_id."' LIMIT 1";
            $vendorR = mysqli_query($conn, $vendorQ);
            if($vendorR && mysqli_num_rows($vendorR) > 0){
                $vendorRow = mysqli_fetch_assoc($vendorR);
                $opening_balance = (isset($vendorRow['opening_balance']) && $vendorRow['opening_balance'] != '') ? $vendorRow['opening_balance'] : 0;
                $opening_balance = (isset($vendorRow['opening_balance_type']) && $vendorRow['opening_balance_type'] == 'DB') ? $opening_balance : -$opening_balance;
            }
            /*----------------------------------------------------COUNT OPENING BALANCE END-------------------------------------------------------*/

            /*---------------------------------------------COUNT DABIT PURCHASE BILL START------------------------------------------------*/
            $billQ =  "SELECT id, invoice_date, invoice_no, vouchar_date, voucher_no, total_total as final_amount FROM purchase WHERE pharmacy_id = '".$pharmacy_id."' AND vendor = '".$vendor_id."' AND purchase_type = 'Debit'";
            if(isset($is_financial) && $is_financial == 1){
                $billQ .= " AND financial_id = '".$financial_id."'";
            }
            if((isset($from) && $from != '') && (isset($to) && $to != '')){
              $billQ .= " AND invoice_date >= '".$from."' AND invoice_date <= '".$to."'";
            }
            $billR = mysqli_query($conn, $billQ);
            if($billR && mysqli_num_rows($billR) > 0){
                while ($billRow = mysqli_fetch_assoc($billR)) {
                  $billarr['type'] = 'purchaseBill';
                  $billarr['id'] = $billRow['id'];
                  $billarr['date'] = (isset($billRow['vouchar_date'])) ? $billRow['vouchar_date'] : '';
                  $billarr['sr_no'] = (isset($billRow['invoice_no'])) ? $billRow['invoice_no'] : '';
                  
                  $invoiceDate = (isset($billRow['invoice_date']) && $billRow['invoice_date'] != '') ? ' | Invoice Date - '.date('d/m/Y',strtotime($billRow['invoice_date'])) : '';
                  $invoiceNo = (isset($billRow['invoice_no']) && $billRow['invoice_no'] != '') ? ' | Invoice No. - '.$billRow['invoice_no'] : '';
                  
                  $billarr['narration'] = 'Purchase Bill'.$invoiceDate.''.$invoiceNo;
                  $billarr['debit'] = '';
                  $billarr['credit'] = (isset($billRow['final_amount']) && $billRow['final_amount'] != '') ? $billRow['final_amount'] : 0;
                  $billarr['url'] = 'purchase.php?id='.$billRow['id'];
                  $data[] = $billarr;
                }
            }
            /*-----------------------------------------------COUNT DABIT PURCHASE BILL END-----------------------------------------------*/

            /*-----------------------------------------------COUNT PURCHASE RETURN BILL START----------------------------------------------*/
            $purchaseReturnQ = "SELECT id, debit_note_date, debit_note_no, remarks, finalamount FROM purchase_return WHERE pharmacy_id = '".$pharmacy_id."' AND vendor_id = '".$vendor_id."'";
            if(isset($is_financial) && $is_financial == 1){
                $purchaseReturnQ .= " AND financial_id = '".$financial_id."'";
            }
            if((isset($from) && $from != '') && (isset($to) && $to != '')){
              $purchaseReturnQ .= " AND debit_note_date >= '".$from."' AND debit_note_date <= '".$to."'";
            }
            $purchaseReturnR = mysqli_query($conn, $purchaseReturnQ);
            if($purchaseReturnR && mysqli_num_rows($purchaseReturnR) > 0){
                while ($purchaseReturnRow = mysqli_fetch_assoc($purchaseReturnR)) {
                    $billretarr['type'] = 'purchaseReturnBill';
                    $billretarr['id'] = $purchaseReturnRow['id'];
                    $billretarr['date'] = (isset($purchaseReturnRow['debit_note_date'])) ? $purchaseReturnRow['debit_note_date'] : '';
                    $billretarr['sr_no'] = (isset($purchaseReturnRow['debit_note_no'])) ? $purchaseReturnRow['debit_note_no'] : '';
                      
                    $invoiceDate = (isset($purchaseReturnRow['debit_note_date']) && $purchaseReturnRow['debit_note_date'] != '' && $purchaseReturnRow['debit_note_date'] != '0000-00-00') ? ' | Debit Note Date - '.date('d/m/Y',strtotime($purchaseReturnRow['debit_note_date'])) : '';
                    $invoiceNo = (isset($purchaseReturnRow['debit_note_no']) && $purchaseReturnRow['debit_note_no'] != '') ? ' | Debit Note No. - '.$purchaseReturnRow['debit_note_no'] : '';
                    $remarks = (isset($purchaseReturnRow['remarks']) && trim($purchaseReturnRow['remarks']) != '') ? ' - '.$purchaseReturnRow['remarks'] : '';
                      
                    $billretarr['narration'] = 'Debit Note'.$invoiceDate.''.$invoiceNo.''.$remarks;
                    $billretarr['debit'] = (isset($purchaseReturnRow['finalamount']) && $purchaseReturnRow['finalamount'] != '') ? $purchaseReturnRow['finalamount'] : 0;
                    $billretarr['credit'] = '';
                    $billretarr['url'] = 'purchase-return.php?id='.$purchaseReturnRow['id'];
                    $data[] = $billretarr;
                }
            }
            /*-----------------------------------------------COUNT PURCHASE RETURN BILL END-----------------------------------------------*/

            /*----------------------------------------------------CASH TRANSACTION START-----------------------------------------*/
            $cashTransactionQ = "SELECT id, payment_type, voucher_no, voucher_date, amount, narration FROM cash_transaction WHERE pharmacy_id = '".$pharmacy_id."' AND perticular = '".$vendor_id."'";
            if(isset($is_financial) && $is_financial == 1){
                $cashTransactionQ .= " AND financial_id = '".$financial_id."'";
            }
            if((isset($from) && $from != '') && (isset($to) && $to != '')){
              $cashTransactionQ .= " AND voucher_date >= '".$from."' AND voucher_date <= '".$to."'";
            }
            $cashTransactionR = mysqli_query($conn, $cashTransactionQ);
            if($cashTransactionR && mysqli_num_rows($cashTransactionR) > 0){
                while ($cashTransactionRow = mysqli_fetch_assoc($cashTransactionR)) {
                    $casharr['type'] = 'cashTransaction';
                    $casharr['id'] = $cashTransactionRow['id'];
                    $casharr['date'] = (isset($cashTransactionRow['voucher_date'])) ? $cashTransactionRow['voucher_date'] : '';
                    $casharr['sr_no'] = (isset($cashTransactionRow['voucher_no'])) ? $cashTransactionRow['voucher_no'] : '';
                    $cashVoucherNo = (isset($cashTransactionRow['voucher_no']) && $cashTransactionRow['voucher_no'] != '') ? ' - '.$cashTransactionRow['voucher_no'] : '';
                    $cashNarration = (isset($cashTransactionRow['narration']) && $cashTransactionRow['narration'] != '') ? ' - '.$cashTransactionRow['narration'] : '';
                    $cashLable = (isset($cashTransactionRow['payment_type']) && $cashTransactionRow['payment_type'] == 'receipt') ? 'Cash Receipt' : 'Cash Payment';
                    $casharr['narration'] = $cashLable.''.$cashVoucherNo.''.$cashNarration;
                    if(isset($cashTransactionRow['payment_type']) && $cashTransactionRow['payment_type'] == 'receipt'){
                        $casharr['debit'] = '';
                        $casharr['credit'] = (isset($cashTransactionRow['amount']) && $cashTransactionRow['amount'] != '') ? $cashTransactionRow['amount'] : 0;
                        $casharr['url'] = 'cash-receipt.php?id='.$cashTransactionRow['id'];
                    }else{
                        $casharr['debit'] = (isset($cashTransactionRow['amount']) && $cashTransactionRow['amount'] != '') ? $cashTransactionRow['amount'] : 0;
                        $casharr['credit'] = '';
                        $casharr['url'] = 'cash-payment.php?id='.$cashTransactionRow['id'];
                    }
                    $data[] = $casharr;
                }
            }
            /*----------------------------------------------------CASH TRANSACTION END------------------------------------------*/

            /*----------------------------------------------------BANK TRANSACTION START-----------------------------------------*/
            $bankTransactionQ = "SELECT bt.id, bt.payment_type, bt.voucher_no, bt.voucher_date, bt.amount, bt.payment_mode, bt.payment_mode_date, bt.payment_mode_no, bt.card_name, bt.other_reference, lg.name as bank_name FROM bank_transaction bt LEFT JOIN ledger_master lg ON bt.bank_id = lg.id WHERE bt.pharmacy_id = '".$pharmacy_id."' AND bt.perticular = '".$vendor_id."'";
            if(isset($is_financial) && $is_financial == 1){
                $bankTransactionQ .= " AND bt.financial_id = '".$financial_id."'";
            }
            if((isset($from) && $from != '') && (isset($to) && $to != '')){
              $bankTransactionQ .= " AND bt.voucher_date >= '".$from."' AND bt.voucher_date <= '".$to."'";
            }
            $bankTransactionR = mysqli_query($conn, $bankTransactionQ);
            if($bankTransactionR && mysqli_num_rows($bankTransactionR) > 0){
                while ($bankTransactionRow = mysqli_fetch_assoc($bankTransactionR)) {
                    $bankarr['type'] = 'bankTransaction';
                    $bankarr['id'] = $bankTransactionRow['id'];
                    $bankarr['date'] = (isset($bankTransactionRow['voucher_date'])) ? $bankTransactionRow['voucher_date'] : '';
                    $bankarr['sr_no'] = (isset($bankTransactionRow['voucher_no'])) ? $bankTransactionRow['voucher_no'] : '';

                    $cashVoucherNo = (isset($bankTransactionRow['voucher_no']) && $bankTransactionRow['voucher_no'] != '') ? ' - '.$bankTransactionRow['voucher_no'] : '';
                    $paymentDate = (isset($bankTransactionRow['payment_mode_date']) && $bankTransactionRow['payment_mode_date'] != '0000-00-00') ? date('d/m/Y',strtotime($bankTransactionRow['payment_mode_date'])) : '';
                    $paymentNo = (isset($bankTransactionRow['payment_mode_no']) && $bankTransactionRow['payment_mode_no'] != '') ? $bankTransactionRow['payment_mode_no'] : '';
                    $cardName = (isset($bankTransactionRow['card_name']) && $bankTransactionRow['card_name'] != '') ? $bankTransactionRow['card_name'] : '';
                    $otherRef = (isset($bankTransactionRow['other_reference']) && $bankTransactionRow['other_reference'] != '') ? $bankTransactionRow['other_reference'] : '';
                    $bankName = (isset($bankTransactionRow['bank_name']) && $bankTransactionRow['bank_name'] != '') ? $bankTransactionRow['bank_name'] : '';

                    $cashLable = (isset($bankTransactionRow['payment_type']) && $bankTransactionRow['payment_type'] == 'receipt') ? 'Bank Receipt' : 'Bank Payment';
                    $paymentMode = (isset($bankTransactionRow['payment_mode'])) ? $bankTransactionRow['payment_mode'] : '';

                    if($paymentMode == 'cheque'){
                        $narration =  $cashLable.''.$cashVoucherNo.' - '.$bankName.' - Cheque No: '.$paymentNo.' - Cheque Date: '.$paymentDate;
                    }elseif($paymentMode == 'dd'){
                        $narration =  $cashLable.''.$cashVoucherNo.' - '.$bankName.' - DD No: '.$paymentNo.' - DD Date: '.$paymentDate;
                    }elseif($paymentMode == 'net_banking'){
                        $narration =  $cashLable.''.$cashVoucherNo.' - '.$bankName.' - Net Banking UTR NO: '.$paymentNo;
                    }elseif($paymentMode == 'credit_debit_card'){
                        $narration =  $cashLable.''.$cashVoucherNo.' - '.$bankName.' - Card No: '.$paymentNo.' - Card Name: '.$cardName;
                    }elseif($paymentMode == 'other'){
                        $narration =  $cashLable.''.$cashVoucherNo.' - '.$bankName.' - Other Reference: '.$otherRef;
                    }else{
                        $narration =  $cashLable.''.$cashVoucherNo.' - '.$bankName;
                    }


                    $bankarr['narration'] = $narration;
                    if(isset($bankTransactionRow['payment_type']) && $bankTransactionRow['payment_type'] == 'receipt'){
                        $bankarr['debit'] = '';
                        $bankarr['credit'] = (isset($bankTransactionRow['amount']) && $bankTransactionRow['amount'] != '') ? $bankTransactionRow['amount'] : 0;
                        $bankarr['url'] = 'bank-receipt.php?id='.$bankTransactionRow['id'];
                    }else{
                        $bankarr['debit'] = (isset($bankTransactionRow['amount']) && $bankTransactionRow['amount'] != '') ? $bankTransactionRow['amount'] : 0;
                        $bankarr['credit'] = '';
                        $bankarr['url'] = 'bank-payment.php?id='.$bankTransactionRow['id'];
                    }
                    $data[] = $bankarr;
                }
            }
            /*----------------------------------------------------BANK TRANSACTION END------------------------------------------*/

            /*-----------------------------------------------------COUNT JOURNAL VOUCHER START-----------------------------------------------*/
            $jurnalVoucherQ = "SELECT jv.id, jv.voucher_date, jvd.debit, jvd.credit, jvd.remarks FROM journal_vouchar_details jvd INNER JOIN journal_vouchar jv ON jvd.voucher_id = jv.id WHERE jvd.particular = '".$vendor_id."' AND jv.pharmacy_id = '".$pharmacy_id."'";
            if(isset($is_financial) && $is_financial == 1){
                $jurnalVoucherQ .= " AND jv.financial_id = '".$financial_id."'";
            }
            if((isset($from) && $from != '') && (isset($to) && $to != '')){
              $jurnalVoucherQ .= " AND jv.voucher_date >= '".$from."' AND jv.voucher_date <= '".$to."'";
            }
            $jurnalVoucherR = mysqli_query($conn, $jurnalVoucherQ);
            if($jurnalVoucherR && mysqli_num_rows($jurnalVoucherR) > 0){
                while ($jurnalVoucherRow = mysqli_fetch_assoc($jurnalVoucherR)) {
                  $jurnalarr['type'] = 'journalVoucher';
                  $jurnalarr['id'] = $jurnalVoucherRow['id'];
                  $jurnalarr['date'] = (isset($jurnalVoucherRow['voucher_date'])) ? $jurnalVoucherRow['voucher_date'] : '';
                  $jurnalarr['sr_no'] = '';
                  $JVremarks = (isset($jurnalVoucherRow['remarks']) && trim($jurnalVoucherRow['remarks']) != '') ? ' - '.$jurnalVoucherRow['remarks'] : '';
                  $jurnalarr['narration'] = 'Journal Voucher'.$JVremarks;
                  $jurnalarr['debit'] = (isset($jurnalVoucherRow['debit']) && $jurnalVoucherRow['debit'] != 0) ? $jurnalVoucherRow['debit'] : '';
                  $jurnalarr['credit'] = (isset($jurnalVoucherRow['credit']) && $jurnalVoucherRow['credit'] != 0) ? $jurnalVoucherRow['credit'] : '';
                  $jurnalarr['url'] = 'journal-vouchar.php?id='.$jurnalVoucherRow['id'];
                  
                  $data[] = $jurnalarr;
                }
            }
            /*-----------------------------------------------------COUNT JOURNAL VOUCHER END-------------------------------------------------*/
            
        }
        $final_array = $data;

        function date_compare($a, $b){

            $t1 = strtotime($a['date']);

            $t2 = strtotime($b['date']);

            return $t1 - $t2;
        }    

        usort($final_array, 'date_compare');

        $finaldata['opening_balance'] = $opening_balance;
        $finaldata['data'] = $final_array;
        $countRunningBalance = countRunningBalance($vendor_id, $from, $to, $is_financial);
        $finaldata['running_balance'] = (isset($countRunningBalance['running_balance']) && $countRunningBalance['running_balance'] != '') ? $countRunningBalance['running_balance'] : 0;
        return $finaldata;
    }
    
    /*--------------------------------FUNCTION FOR COUNT BANK RUNNING BALANCE-GAUTAM-------------------------------*/
    function bankrunningbalance($id = null, $from = null, $to = null, $is_financial = 0){
        global $conn;
        $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
        $financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';

        $opening_balance = 0; $total_receipt = 0; $total_payment = 0; $bank_name = '';
        
        $from = (isset($from) && $from != '') ? date('Y-m-d',strtotime(str_replace("/","-", $from))) : '';
        $to = (isset($to) && $to != '') ? date('Y-m-d',strtotime(str_replace("/","-",$to))) : '';

        if((isset($id) && $id != '') && (isset($pharmacy_id) && $pharmacy_id != '')){

            /*---------------------------------CALCULATE PHARMACY BANK RUNNING BALANCE START-----------------------------------------------*/
            $ledgerQ = "SELECT id, name, opening_balance, opening_balance_type FROM ledger_master WHERE (group_id = 5 OR group_id = 22 OR group_id = 30) AND pharmacy_id = '".$pharmacy_id."' AND id = '".$id."'";
            $ledgerR = mysqli_query($conn, $ledgerQ);
            if($ledgerR && mysqli_num_rows($ledgerR) > 0){
                $ledgerRow = mysqli_fetch_assoc($ledgerR);
                $bank_name = (isset($ledgerRow['name'])) ? $ledgerRow['name'] : '';
                $opening_balance_tmp = (isset($ledgerRow['opening_balance']) && $ledgerRow['opening_balance'] != '') ? $ledgerRow['opening_balance'] : 0;
                $opening_balance = (isset($ledgerRow['opening_balance_type']) && $ledgerRow['opening_balance_type'] == 'DB') ? $opening_balance_tmp : -$opening_balance_tmp;
            }
            /*---------------------------------CALCULATE PHARMACY BANK RUNNING BALANCE END-----------------------------------------------*/

            /*----------------------------------BANK PAYMENT START----------------------------------------------*/
            $bankPaymentQ = "SELECT SUM(amount) as total_payment FROM bank_transaction WHERE payment_type = 'payment' AND pharmacy_id = '".$pharmacy_id."' AND bank_id = '".$id."'";
            if(isset($is_financial) && $is_financial == 1){
                $bankPaymentQ .= " AND financial_id = '".$financial_id."'";
            }
            if((isset($from) && $from != '') && (isset($to) && $to != '')){
              $bankPaymentQ .= " AND voucher_date >= '".$from."' AND voucher_date <= '".$to."'";
            }
            $bankPaymentR = mysqli_query($conn, $bankPaymentQ);
            if($bankPaymentR && mysqli_num_rows($bankPaymentR) > 0){
                $bankPaymentRow = mysqli_fetch_assoc($bankPaymentR);
                $total_payment = (isset($bankPaymentRow['total_payment']) && $bankPaymentRow['total_payment'] != '') ? $bankPaymentRow['total_payment'] : 0;
            }
            /*----------------------------------BANK PAYMENT END----------------------------------------------*/

            /*----------------------------------BANK RECEIPT START----------------------------------------------*/
            $bankReceiptQ = "SELECT SUM(amount) as total_receipt FROM bank_transaction WHERE payment_type = 'receipt' AND pharmacy_id = '".$pharmacy_id."' AND bank_id = '".$id."'";
            if(isset($is_financial) && $is_financial == 1){
                $bankReceiptQ .= " AND financial_id = '".$financial_id."'";
            }
            if((isset($from) && $from != '') && (isset($to) && $to != '')){
              $bankReceiptQ .= " AND voucher_date >= '".$from."' AND voucher_date <= '".$to."'";
            }
            $bankReceiptR = mysqli_query($conn, $bankReceiptQ);
            if($bankReceiptR && mysqli_num_rows($bankReceiptR) > 0){
                $bankReceiptRow = mysqli_fetch_assoc($bankReceiptR);
                $total_receipt = (isset($bankReceiptRow['total_receipt']) && $bankReceiptRow['total_receipt'] != '') ? $bankReceiptRow['total_receipt'] : 0;
            }
            /*----------------------------------BANK RECEIPT END----------------------------------------------*/
            
            
        }
    
        $data['bankname'] = $bank_name;
        $data['bank_running'] = ($opening_balance+$total_receipt-$total_payment);
        return $data;
    }
    
    /*--------------------------------FUNCTION FOR BANK LEDGER-GAUTAM-------------------------------*/
    function bankLedger($bank_id = null, $from = null, $to = null, $is_financial = 0){
        $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
        $financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';

        $data = [];
        global $conn;
        $opening_balance = 0;
        $bank_name = '';
        if((isset($pharmacy_id) && $pharmacy_id != '') && (isset($bank_id) && $bank_id != '')){

            //Count Opening Balance of Bank
            $bankQ = "SELECT id, name as bank_name, opening_balance, opening_balance_type, bank_ac_no, branch_name, ifsc_code FROM ledger_master WHERE (group_id = 5 OR group_id = 22 OR group_id = 30) AND pharmacy_id = '".$pharmacy_id."' AND id = '".$bank_id."'";
            $bankR = mysqli_query($conn, $bankQ);
            if($bankR && mysqli_num_rows($bankR) > 0){
                $bankRow = mysqli_fetch_assoc($bankR);
                $bank_name = (isset($bankRow['bank_name'])) ? $bankRow['bank_name'] : '';
                $opening_balance = (isset($bankRow['opening_balance']) && $bankRow['opening_balance'] != '') ? $bankRow['opening_balance'] : 0;
                $opening_balance = (isset($bankRow['opening_balance_type']) && $bankRow['opening_balance_type'] == 'DB') ? $opening_balance : -$opening_balance;
            }

            /*---------------------------------------------BANK TRANSACTION START----------------------------------------*/
            $bankTransactionQ = "SELECT bt.id, bt.payment_type, bt.voucher_no, bt.voucher_date, bt.amount, bt.payment_mode, bt.payment_mode_date, bt.payment_mode_no, bt.card_name, bt.other_reference, lg.name as perticular FROM bank_transaction bt LEFT JOIN ledger_master lg ON bt.perticular = lg.id WHERE bt.pharmacy_id = '".$pharmacy_id."' AND bt.bank_id = '".$bank_id."'";
            if(isset($is_financial) && $is_financial == 1){
                $bankTransactionQ .= " AND bt.financial_id = '".$financial_id."'";
            }
            if((isset($from) && $from != '') && (isset($to) && $to != '')){
              $bankTransactionQ .= " AND bt.voucher_date >= '".$from."' AND bt.voucher_date <= '".$to."'";
            }
            $bankTransactionR = mysqli_query($conn, $bankTransactionQ);
            if($bankTransactionR && mysqli_num_rows($bankTransactionR) > 0){
                while ($bankTransactionRow = mysqli_fetch_assoc($bankTransactionR)) {
                    $arr['id'] = (isset($bankTransactionRow['id'])) ? $bankTransactionRow['id'] : '';
                    $arr['type'] = (isset($bankTransactionRow['payment_type']) && $bankTransactionRow['payment_type'] == 'payment') ? 'Bank Payment' : 'Bank Receipt';
                    $arr['date'] =  (isset($bankTransactionRow['voucher_date'])) ? $bankTransactionRow['voucher_date'] : '';

                    $cashVoucherNo = (isset($bankTransactionRow['voucher_no']) && $bankTransactionRow['voucher_no'] != '') ? ' - '.$bankTransactionRow['voucher_no'] : '';
                    $paymentDate = (isset($bankTransactionRow['payment_mode_date']) && $bankTransactionRow['payment_mode_date'] != '0000-00-00') ? date('d/m/Y',strtotime($bankTransactionRow['payment_mode_date'])) : '';
                    $paymentNo = (isset($bankTransactionRow['payment_mode_no']) && $bankTransactionRow['payment_mode_no'] != '') ? $bankTransactionRow['payment_mode_no'] : '';
                    $cardName = (isset($bankTransactionRow['card_name']) && $bankTransactionRow['card_name'] != '') ? $bankTransactionRow['card_name'] : '';
                    $otherRef = (isset($bankTransactionRow['other_reference']) && $bankTransactionRow['other_reference'] != '') ? $bankTransactionRow['other_reference'] : '';
                    $cashLable = (isset($bankTransactionRow['payment_type']) && $bankTransactionRow['payment_type'] == 'receipt') ? 'Bank Receipt' : 'Bank Payment';
                    $paymentMode = (isset($bankTransactionRow['payment_mode'])) ? $bankTransactionRow['payment_mode'] : '';
                    $perticular = (isset($bankTransactionRow['perticular']) && $bankTransactionRow['perticular'] != '') ? ' - '.$bankTransactionRow['perticular'] : ''; 

                    if($paymentMode == 'cheque'){
                        $narration =  $cashLable.''.$cashVoucherNo.' - Cheque No: '.$paymentNo.' - Cheque Date: '.$paymentDate.''.$perticular;
                    }elseif($paymentMode == 'dd'){
                        $narration =  $cashLable.''.$cashVoucherNo.' - DD No: '.$paymentNo.' - DD Date: '.$paymentDate.''.$perticular;
                    }elseif($paymentMode == 'net_banking'){
                        $narration =  $cashLable.''.$cashVoucherNo.' - Net Banking UTR NO: '.$paymentNo.''.$perticular;
                    }elseif($paymentMode == 'credit_debit_card'){
                        $narration =  $cashLable.''.$cashVoucherNo.' - Card No: '.$paymentNo.' - Card Name: '.$cardName.''.$perticular;
                    }elseif($paymentMode == 'other'){
                        $narration =  $cashLable.''.$cashVoucherNo.' - Other Reference: '.$otherRef.''.$perticular;
                    }else{
                        $narration =  $cashLable.''.$cashVoucherNo.''.$perticular;
                    }


                    $arr['narration'] = $narration;
                    if(isset($bankTransactionRow['payment_type']) && $bankTransactionRow['payment_type'] == 'receipt'){
                        $arr['receipt'] = (isset($bankTransactionRow['amount']) && $bankTransactionRow['amount'] != '') ? $bankTransactionRow['amount'] : 0;
                        $arr['payment'] = '';
                        $arr['url'] = 'bank-receipt.php?id='.$bankTransactionRow['id'];
                    }else{
                        $arr['payment'] = (isset($bankTransactionRow['amount']) && $bankTransactionRow['amount'] != '') ? $bankTransactionRow['amount'] : 0;
                        $arr['receipt'] = '';
                        $arr['url'] = 'bank-payment.php?id='.$bankTransactionRow['id'];
                    }
                    $data[] = $arr;
                }
            }
            /*---------------------------------------------BANK TRANSACTION START----------------------------------------*/

        }

        $final_array = $data;

        function date_compare($a, $b){

            $t1 = strtotime($a['date']);

            $t2 = strtotime($b['date']);

            return $t1 - $t2;
        }    

        usort($final_array, 'date_compare');

        $finaldata['opening_balance'] = $opening_balance;
        $finaldata['data'] = $final_array;
        $bankRunningBalance = bankrunningbalance($bank_id, $from, $to, $is_financial);
        $finaldata['running_balance'] = (isset($bankRunningBalance['bank_running']) && $bankRunningBalance['bank_running'] != '') ? $bankRunningBalance['bank_running'] : 0;
        $finaldata['bank_name'] = $bank_name;
        
        return $finaldata;

    }
    
     /**
     * SMTP Mail function using Gmail credentials
     * Using PHPMailer Class Library
     */

    function smtpmail($to, $from='', $replyto='', $subject, $data = '', $username='', $password='', $attachfilepath=''){
        // Loading PHPMailer classs

        require_once __DIR__.'/../PHPMailer/PHPMailerAutoload.php';
        
        $from = 'ihis.yamuna@gmail.com';
        $replyto = 'ihis.yamuna@gmail.com';

        $mail = new PHPMailer;

        $mail->isSMTP();                                        // Set mailer to use SMTP

        //$mail->SMTPDebug = 3;
        
        $mail->Host = 'smtp.gmail.com';                         // Specify main and backup SMTP servers

        $mail->SMTPAuth = true;                                 // Enable SMTP authentication

        $mail->Username = "ihis.yamuna@gmail.com";                              // SMTP username - Insert Email Address
        $mail->Password = "ihis1234";                           // SMTP password - Insert Email Account Password


        //$mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
        //$mail->Port = 465; 

        $mail->SMTPSecure = 'tls';                              // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                      // TCP port to connect to  587 / 465


        $mail->setFrom($from, 'Digibook');
        $mail->addReplyTo($replyto, 'Digibook');

        //Adding Subject
        $mail->Subject = $subject;


        // Adding Recipient
        if(is_array($to)){

            // sending mail to all recipient
            for($m=0; $m < count($to); $m++){
                $mail->addAddress($to[$m]);
            }

        } else {

            $mail->addAddress($to);
        }

        $mail->isHTML(true);  // Set email format to HTML

        

        $mail->Body = $data;
        
        //add Attachment file 
        if( !empty($attchfilepath) ){
            $mail->AddAttachment($attachfilepath);
        }

        if(!$mail->send()) {
            //return $mail->ErrorInfo;
            return false;
        } else {
            return true;
        }

    }
    
    /*------------------------------------CALCULATE DAILY SALES RUNNING BALANCE START-----------------------------------------*/
    function salesrunningbalance($from = null, $to = null){
        global $conn;
        $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
        $financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';
    
        $round_off = 0;
    
        if((isset($pharmacy_id) && $pharmacy_id != '')){
    
            $salesqry = "SELECT sum(final_amount) as final_amount FROM tax_billing WHERE pharmacy_id = '".$pharmacy_id."' AND financial_id = '".$financial_id."'"; 
            if((isset($from) && $from != '') && (isset($to) && $to != '')){
              $salesqry .= " AND invoice_date >= '".$from."' AND invoice_date <= '".$to."'";   
            }
          
            $salesrun = mysqli_query($conn, $salesqry);
            if($salesrun){
                $running = mysqli_fetch_assoc($salesrun);
                $final = $running['final_amount'];
            }
        }
       $data['running_balance'] =  $final;
      return $data;
    }

    /*------------------------------------CALCULATE DAILY SALES RUNNING BALANCE END-----------------------------------------*/
    

    /*------------------------------------DAILYSALES FUNCTION START---------------------------------------------------------*/
    function dailysales($from = null , $to = null){
         
        $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : ''; 
        $financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';
          
        $data = [];
        global $conn;
        if((isset($pharmacy_id) && $pharmacy_id != '')){ 
                   
            $dailysaleqry =  "SELECT l.id,t.id ,t.owner_id,t.invoice_date, t.invoice_no, t.final_amount, t.cr_db_type, l.name, c.name as city, d.name as doctor FROM (((tax_billing AS t INNER JOIN ledger_master AS l ON t.owner_id = l.id) LEFT JOIN doctor_profile AS d ON t.doctor = d.id) LEFT JOIN own_cities AS c ON c.id = l.city) WHERE t.pharmacy_id = '".$pharmacy_id."' AND t.financial_id = '".$financial_id."'"; 
            if((isset($from) && $from != '') && (isset($to) && $to != '')){
              $dailysaleqry .= " AND invoice_date >= '".$from."' AND invoice_date <= '".$to."'";   
            }
            $dailysaleqry .= "ORDER BY t.invoice_date ASC"; 
            $dailysalerun = mysqli_query($conn, $dailysaleqry);
            if($dailysalerun && mysqli_num_rows($dailysalerun) > 0){
                while ($dailysaledata = mysqli_fetch_assoc($dailysalerun)) {
                  //$salearr['type'] = 'taxBill';
                  $salearr['id'] = $dailysaledata['id'];
                  $salearr['name'] = $dailysaledata['name'];
                  $salearr['date'] = (isset($dailysaledata['invoice_date'])) ? $dailysaledata['invoice_date'] : '';
                  $salearr['invoice_no'] = (isset($dailysaledata['invoice_no'])) ? $dailysaledata['invoice_no'] : '';
                  $salearr['bill_type'] = (isset($dailysaledata['cr_db_type'])) ? $dailysaledata['cr_db_type'] : '';
                  $salearr['bill_amount'] = (isset($dailysaledata['final_amount'])) ? $dailysaledata['final_amount'] : '';
                  $salearr['city'] = (isset($dailysaledata['city'])) ? $dailysaledata['city'] : '';
                  $salearr['doctor'] = (isset($dailysaledata['doctor'])) ? $dailysaledata['doctor'] : '';
                  $salearr['url'] = 'sales-tax-billing.php?id='.$salearr['id'];
                  $data[] = $salearr;
                }
            }
               
            $finaldata['data'] = $data;
            $countRunningBalance = salesrunningbalance($from, $to);
            $finaldata['running_balance'] = (isset($countRunningBalance['running_balance']) && $countRunningBalance['running_balance'] != '') ? $countRunningBalance['running_balance'] : 0;
            return $finaldata;
        }
    }

    /*------------------------------------DAILYSALES FUNCTION END-----------------------------------------------------------*/

    /*------------------------------------VIRAG CODE END--------------------------------------------------------------------*/
    
    /**
     * Function used to show amount in words
    */
    function number_to_word( $num = '' ){
            $number = $num;
         $decimal = round($number - ($no = floor($number)), 2) * 100;
            $hundred = null;
            $digits_length = strlen($no);
            $i = 0;
            $str = array();
            $words = array(0 => '', 1 => 'One', 2 => 'Two',
                3 => 'Three', 4 => 'Four', 5 => 'Five', 6 => 'Six',
                7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
                10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve',
                13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',
                16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen',
                19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',
                40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty',
                70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety');
            $digits = array('', 'Hundred','Thousand','Lakh', 'Crore');
            while( $i < $digits_length ) {
                $divider = ($i == 2) ? 10 : 100;
                $number = floor($no % $divider);
                $no = floor($no / $divider);
                $i += $divider == 10 ? 1 : 2;
                if ($number) {
                    $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                    $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                    $str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
                } else $str[] = null;
            }
            $Rupees = implode('', array_reverse($str));
            $paise = ($decimal) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
            return ($Rupees ? $Rupees . 'Rupees ' : '') . $paise;
    }
    
    function getPerticularDetail($id = null){
        global $conn;
        $data = [];
            $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
            $query = "SELECT lg.id, lg.name, lg.gstno, ct.name as city FROM ledger_master lg LEFT JOIN own_cities ct ON lg.city = ct.id WHERE lg.pharmacy_id = '".$pharmacy_id."' AND lg.id = '".$id."'";
            $res = mysqli_query($conn, $query);
            if($res && mysqli_num_rows($res) > 0){
                $data = mysqli_fetch_assoc($res);
            }
        return $data;
    }

    function getPharmacyDetail(){
        global $conn;
        $data = [];
            $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';

            $query = "SELECT id, pharmacy_name, gst_no FROM pharmacy_profile WHERE id = '".$pharmacy_id."'";
            $res = mysqli_query($conn, $query);
            if($res && mysqli_num_rows($res) > 0){
                $data = mysqli_fetch_assoc($res);
            }
        return $data;
    }
    
    function getPharmacyBankDetail($bank_id = null){
        global $conn;
        $data = []; 
            $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';

            $query = "SELECT id, bank_name FROM pharmacy_bank_details WHERE pharmacy_id = '".$pharmacy_id."' AND id = '".$bank_id."' LIMIT 1";
            $res = mysqli_query($conn, $query);
            if($res && mysqli_num_rows($res) > 0){
                $data = mysqli_fetch_assoc($res);
            }
        return $data;
    }
    
    function amount_format($amount = null, $suffix = 1){
        //converting it to string 
        $numToString = (string)$amount;

        //take care of decimal values
        $change = explode('.', $numToString);

        //taking care of minus sign
        $checkifminus =  explode('-', $change[0]);


        //if minus then change the value as per
        $change[0] = (count($checkifminus) > 1)? $checkifminus[1] : $checkifminus[0];

        //store the minus sign for further
        $min_sgn = '';
        $min_sgn = (count($checkifminus) > 1)?'-':'';



        //catch the last three
        $lastThree = substr($change[0], strlen($change[0])-3);



        //catch the other three
        $ExlastThree = substr($change[0], 0 ,strlen($change[0])-3);


        //check whethr empty 
        if($ExlastThree != '')
            $lastThree = ',' . $lastThree;


        //replace through regex
        $res = preg_replace("/\B(?=(\d{2})+(?!\d))/",",",$ExlastThree);

        //main container num
        $lst = '';

        if(isset($change[1]) == ''){
            $lst =  $min_sgn.$res.$lastThree;
        }else{
            $lst =  $min_sgn.$res.$lastThree.".".$change[1];
        }

        //special case if equals to 2 then 
        if(strlen($change[0]) === 2){
            $lst = str_replace(",","",$lst);
        }

        return $lst;
    }
    
    /* --------------------------CODE FOR SHREYA START----------------------  */

    function saleRunningBalance( $from = null, $to = null){
        global $conn;
        $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
        $financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';
    
         $roundoff = 0;
    
        if((isset($pharmacy_id) && $pharmacy_id != '')){
    
            $taxbillingQ =  "SELECT sum(roundoff_amount) as roundoff FROM tax_billing  WHERE pharmacy_id = '".$pharmacy_id."' AND financial_id = '".$financial_id."' AND cancel= '1' AND roundoff_amount!= '0' AND roundoff_amount IS NOT NULL "; 
            if((isset($from) && $from != '') && (isset($to) && $to != '')){
              $taxbillingQ .= " AND invoice_date >= '".$from."' AND invoice_date <= '".$to."'";  
               } 
             
            $taxbillingR = mysqli_query($conn, $taxbillingQ);
            if(mysqli_num_rows($taxbillingR) > 0){
    
                $running_balance = mysqli_fetch_assoc($taxbillingR);
                $roundoff = $running_balance['roundoff'];
            }
        }
        $data['running_balance'] =  $roundoff;
        return $data;
    }

    function purchaseRunningBalance($from = null, $to = null){
        global $conn;
        $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
        $financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';
    
        $round_off = 0;
    
        if((isset($pharmacy_id) && $pharmacy_id != '')){
    
            $purchaseQ = "SELECT sum(round_off) as round_off FROM purchase WHERE pharmacy_id = '".$pharmacy_id."' AND financial_id = '".$financial_id."' AND cancel= '1' AND round_off!= '0' AND round_off IS NOT NULL"; 
            if((isset($from) && $from != '') && (isset($to) && $to != '')){
              $purchaseQ .= " AND invoice_date >= '".$from."' AND invoice_date <= '".$to."'";   
            }
          
            $purchaseR = mysqli_query($conn, $purchaseQ);
            if(mysqli_num_rows($purchaseR) > 0){
                $running_balance = mysqli_fetch_assoc($purchaseR);
                $round_off = $running_balance['round_off'];
            }
        }
       $data['running_balance'] =  $round_off;
      return $data;
       
    }

    function roundoffsales($from = null , $to = null, $financial = null){
         
        $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : ''; 
        $financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';
          
        $data = [];
        global $conn;
        if((isset($pharmacy_id) && $pharmacy_id != '')){ 
                   
        $billQ =  "SELECT l.id,t.id ,t.owner_id,t.invoice_date, t.invoice_no, t.final_amount, t.roundoff_amount ,l.name FROM tax_billing AS t INNER join ledger_master AS l ON t.customer_id = l.id WHERE t.pharmacy_id = '".$pharmacy_id."' AND t.cancel= '1' AND t.roundoff_amount!= '0' AND t.roundoff_amount IS NOT NULL "; 
            if((isset($from) && $from != '') && (isset($to) && $to != '')){
              $billQ .= " AND invoice_date >= '".$from."' AND invoice_date <= '".$to."'";   
            }
            if(isset($_GET['financial']) && $_GET['financial'] != ''){
              $billQ .=" AND t.financial_id = '".$_GET['financial']."'";
            }else{
              $billQ .=" AND t.financial_id = '".$financial_id."'";
            }
            $billQ .= " ORDER BY t.id ASC"; 
            $billR = mysqli_query($conn, $billQ);
            if($billR && mysqli_num_rows($billR) > 0){
                while ($billRow = mysqli_fetch_assoc($billR)) {
                  $billarr['type'] = 'taxBill';
                  $billarr['id'] = $billRow['id'];
                  $billarr['companyname'] = $billRow['name'];
                  $billarr['date'] = (isset($billRow['invoice_date'])) ? $billRow['invoice_date'] : '';
                  $billarr['invoice_no'] = (isset($billRow['invoice_no'])) ? $billRow['invoice_no'] : '';
                //   $billarr['narration'] = (isset($billRow['invoice_no'])) ? 'Tax Bill | '.$billRow['invoice_no'] : 'Tax Bill';
                 $billarr['narration'] =  (isset($billRow['roundoff_amount']) && $billRow['roundoff_amount'] < 0) ? 'Tax Invoice Debit' : 'Tax Invoice Cash';
                  $billarr['debit'] = (isset($billRow['roundoff_amount']) && $billRow['roundoff_amount'] < 0) ? $billRow['roundoff_amount'] : 0;
                  $billarr['credit'] = (isset($billRow['roundoff_amount'] ) && $billRow['roundoff_amount'] > 0)? $billRow['roundoff_amount']:0;
                  $billarr['url'] = 'sales-tax-billing.php?id='.$billarr['id'];
                  $data[] = $billarr;
                }
            }
               
            $finaldata['data'] = $data;
            $countRunningBalance = saleRunningBalance($from, $to);
            $finaldata['running_balance'] = (isset($countRunningBalance['running_balance']) && $countRunningBalance['running_balance'] != '') ? $countRunningBalance['running_balance'] : 0;
            return $finaldata;
        }
    }

    function roundoffpurchase($from = null , $to = null, $financial = null){
         
        $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : ''; 
        $financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';
          
        $data = [];
        global $conn;
        $opening_balance = 0;
        if((isset($pharmacy_id) && $pharmacy_id != '')){ 
                   
            $billQ = "SELECT l.id,p.id, p.owner_id,p.invoice_date, p.vouchar_date,p.invoice_no, p.round_off ,l.name FROM purchase AS p INNER join ledger_master AS l ON p.vendor = l.id WHERE p.pharmacy_id = '".$pharmacy_id."' AND p.cancel= '1' AND p.round_off!= '0' AND p.round_off IS NOT NULL"; 
            if((isset($from) && $from != '') && (isset($to) && $to != '')){
                $billQ .= " AND invoice_date >= '".$from."' AND invoice_date <= '".$to."'";   
            }
            if(isset($_GET['financial']) && $_GET['financial'] != ''){
              $billQ .=" AND p.financial_id = '".$_GET['financial']."'";
            }else{
               $billQ .=" AND p.financial_id = '".$financial_id."' ";
            }
               $billQ .= " ORDER BY p.id ASC"; 
            $billR = mysqli_query($conn, $billQ);
            if($billR && mysqli_num_rows($billR) > 0){
                while ($billRow = mysqli_fetch_assoc($billR)) {
                  $billarr['type'] = 'taxBill';
                  $billarr['id'] = $billRow['id'];
                  $billarr['companyname'] = $billRow['companyname'];
                  $billarr['date'] = (isset($billRow['invoice_date'])) ? $billRow['invoice_date'] : '';
                    $billarr['vouchar_date'] = (isset($billRow['vouchar_date'])) ? $billRow['vouchar_date'] : '';
                  $billarr['invoice_no'] = (isset($billRow['invoice_no'])) ? $billRow['invoice_no'] : '';
                //   $billarr['narration'] = (isset($billRow['invoice_no'])) ? 'Tax Bill | '.$billRow['invoice_no'] : 'Tax Bill';
                 $billarr['narration'] =  (isset($billRow['round_off']) && $billRow['round_off'] > 0) ? 'Tax Invoice Debit' : 'Tax Invoice Cash';
                  $billarr['debit'] = (isset($billRow['round_off']) && $billRow['round_off'] > 0) ? $billRow['round_off'] : 0;
                  $billarr['credit'] = (isset($billRow['round_off'] ) && $billRow['round_off'] < 0)? $billRow['round_off']:0;
                  $billarr['url'] = 'purchase.php?id='.$billarr['id'];
                  $data[] = $billarr;
                }
            }
            $finaldata['data'] = $data;
            $countRunningBalance = purchaseRunningBalance($from, $to);
            $finaldata['running_balance'] = (isset($countRunningBalance['running_balance']) && $countRunningBalance['running_balance'] != '') ? $countRunningBalance['running_balance'] : 0;
            return $finaldata;
        }
    }
    
    
    function allLedgerdetaills($id = null, $from = null, $to = null, $is_financial = 0){
        global $conn;
        global $pharmacy_id;
        global $financial_id;
        global $account_flag;
        $data = [];
        $detail = [];

        $ledgerQ = "SELECT id, name, opening_balance, opening_balance_type, group_id, acc_flag FROM  ledger_master WHERE pharmacy_id = '".$pharmacy_id."' AND id = '".$id."' LIMIT 1";
        $ledgerR = mysqli_query($conn, $ledgerQ);
        if($ledgerR && mysqli_num_rows($ledgerR) > 0){
            $ledgerRow = mysqli_fetch_assoc($ledgerR);
            $opening_balance = (isset($ledgerRow['opening_balance']) && $ledgerRow['opening_balance'] != '') ? $ledgerRow['opening_balance'] : 0;
            $opening_balance = (isset($ledgerRow['opening_balance_type']) && $ledgerRow['opening_balance_type'] == 'DB') ? $opening_balance : -$opening_balance;
            $data = $ledgerRow;
            $data['opening_balance'] = $opening_balance;
            $data['from'] = $from;
            $data['to'] = $to;
            if(isset($ledgerRow['acc_flag']) && $ledgerRow['acc_flag'] == $account_flag['GENERAL_SALE']){
                $detail = getGeneralSaleReport($from, $to, $is_financial);
            }elseif(isset($ledgerRow['acc_flag']) && $ledgerRow['acc_flag'] == $account_flag['GST_ON_SALE']){
                $detail = getGstOnSaleReport($from, $to, $is_financial);
            }elseif(isset($ledgerRow['acc_flag']) && $ledgerRow['acc_flag'] == $account_flag['IGST_ON_SALE']){
                $detail = getIgstOnSaleReport($from, $to, $is_financial);
            }elseif(isset($ledgerRow['acc_flag']) && $ledgerRow['acc_flag'] == $account_flag['SALE_ACC']){
                $detail = getSaleAccReport($from, $to, $is_financial);
            }elseif(isset($ledgerRow['acc_flag']) && $ledgerRow['acc_flag'] == $account_flag['SALE_ACC_OGS']){
                $detail = getSaleAccOgsReport($from, $to, $is_financial);
            }elseif(isset($ledgerRow['acc_flag']) && $ledgerRow['acc_flag'] == $account_flag['PURCHASE_ACC']){
                $detail = getPurchaseAccReport($from, $to, $is_financial);
            }elseif(isset($ledgerRow['acc_flag']) && $ledgerRow['acc_flag'] == $account_flag['PURCHASE_ACC_OGS']){
                $detail = getPurchaseAccOgsReport($from, $to, $is_financial);
            }elseif(isset($ledgerRow['acc_flag']) && $ledgerRow['acc_flag'] == $account_flag['GST_ON_PURCHASE']){
                $detail = getGstOnPurchaseReport($from, $to, $is_financial);
            }elseif(isset($ledgerRow['acc_flag']) && $ledgerRow['acc_flag'] == $account_flag['IGST_ON_PURCHASE']){
                $detail = getIGstOnPurchaseReport($from, $to, $is_financial);
            }elseif(isset($ledgerRow['acc_flag']) && $ledgerRow['acc_flag'] == $account_flag['OPENING_STOCK']){
                $detail = getOpeningStockReport($from, $to, $is_financial);
            }elseif(isset($ledgerRow['acc_flag']) && $ledgerRow['acc_flag'] == $account_flag['CLOSING_STOCK']){
                $detail = getClosingStockReport($from, $to, $is_financial);
            }elseif(isset($ledgerRow['acc_flag']) && $ledgerRow['acc_flag'] == $account_flag['TAX_LIABILITY']){
                $detail = TaxLiabilityReport($from, $to, $is_financial);
            }else{
                $countRunningBalance =  countRunningBalance($id ,$from, $to, $is_financial);
                $data['running_balance'] = (isset($countRunningBalance['running_balance']) && $countRunningBalance['running_balance'] != '') ? $countRunningBalance['running_balance'] : 0;

                /*--------------------------------------------CALCULATE TAX BILLING START---------------------------------------*/
                if(isset($ledgerRow['group_id']) && $ledgerRow['group_id'] == 10){//for customer only
                    $taxbillingQ = "SELECT id, invoice_date, invoice_no, bill_type, final_amount FROM tax_billing WHERE pharmacy_id = '".$pharmacy_id."' AND customer_id = '".$id."' AND bill_type = 'Debit'";
                    if(isset($is_financial) && $is_financial == 1){
                        $taxbillingQ .= " AND financial_id = '".$financial_id."'";
                    }
                    if((isset($from) && $from != '') && (isset($to) && $to != '')){
                      $taxbillingQ .= " AND invoice_date >= '".$from."' AND invoice_date <= '".$to."'";
                    }
                    $taxbillingR = mysqli_query($conn, $taxbillingQ);
                    if($taxbillingR && mysqli_num_rows($taxbillingR) > 0){
                        while ($taxbillingRow = mysqli_fetch_assoc($taxbillingR)) {
                            $tmp1['id'] = (isset($taxbillingRow['id'])) ? $taxbillingRow['id'] : '';
                            $tmp1['type'] = 'SALEBILL';
                            $tmp1['date'] = (isset($taxbillingRow['invoice_date'])) ? $taxbillingRow['invoice_date'] : '';
                            $tmp1['no'] = (isset($taxbillingRow['invoice_no'])) ? $taxbillingRow['invoice_no'] : '';
                            $tmp1['narration'] = (isset($taxbillingRow['invoice_no']) && $taxbillingRow['invoice_no'] != '') ? 'Sale Bill - '.$taxbillingRow['invoice_no'] : 'Tax Bill';
                            $tmp1['debit'] = (isset($taxbillingRow['final_amount']) && $taxbillingRow['final_amount'] != '') ? $taxbillingRow['final_amount'] : 0;
                            $tmp1['credit'] = '';
                            $tmp1['url'] = 'sales-tax-billing.php?id='.$tmp1['id'];
                            $detail[] = $tmp1;
                        }
                    }
                }
                /*--------------------------------------------CALCULATE TAX BILLING END-----------------------------------------*/

                /*-----------------------------------------------COUNT SALE RETURN START-------------------------------------------*/
                if(isset($ledgerRow['group_id']) && $ledgerRow['group_id'] == 10){//for customer only
                    $saleReturnQ =  "SELECT id, credit_note_date, credit_note_no, finalamount FROM sale_return WHERE pharmacy_id = '".$pharmacy_id."' AND customer_id = '".$id."'";
                    if(isset($is_financial) && $is_financial == 1){
                        $saleReturnQ .= " AND financial_id = '".$financial_id."'";
                    }
                    if((isset($from) && $from != '') && (isset($to) && $to != '')){
                      $saleReturnQ .= " AND credit_note_date >= '".$from."' AND credit_note_date <= '".$to."'";
                    }
                    $saleReturnR = mysqli_query($conn, $saleReturnQ);
                    if($saleReturnR && mysqli_num_rows($saleReturnR) > 0){
                        while ($saleReturnRow = mysqli_fetch_assoc($saleReturnR)) {
                            $tmp2['id'] = (isset($saleReturnRow['id'])) ? $saleReturnRow['id'] : '';
                            $tmp2['type'] = 'SALEBILLRETURN';
                            $tmp2['date'] = (isset($saleReturnRow['credit_note_date'])) ? $saleReturnRow['credit_note_date'] : '';
                            $tmp2['no'] = (isset($saleReturnRow['credit_note_no'])) ? $saleReturnRow['credit_note_no'] : '';
                            $tmp2['narration'] = (isset($saleReturnRow['credit_note_no']) && $saleReturnRow['credit_note_no'] != '') ? 'Credit Note | '.$saleReturnRow['credit_note_no'] : 'Credit Note';
                            $tmp2['debit'] = '';
                            $tmp2['credit'] = (isset($saleReturnRow['finalamount']) && $saleReturnRow['finalamount'] != '') ? $saleReturnRow['finalamount'] : 0;
                            $tmp2['url'] = 'sales-return.php?id='.$tmp1['id'];
                            $detail[] = $tmp2;
                        }
                    }
                }
                /*-----------------------------------------------COUNT SALE RETURN END--------------------------------------------*/
    
                /*--------------------------------------------CALCULATE PURCHASE START---------------------------------------*/
                if(isset($ledgerRow['group_id']) && $ledgerRow['group_id'] == 14){//for vendor only
                    $purchaseQ = "SELECT id, invoice_no, invoice_date, purchase_type, total_total FROM purchase WHERE pharmacy_id = '".$pharmacy_id."' AND vendor = '".$id."' AND purchase_type = 'Debit'";
                    if(isset($is_financial) && $is_financial == 1){
                        $purchaseQ .= " AND financial_id = '".$financial_id."'";
                    }
                    if((isset($from) && $from != '') && (isset($to) && $to != '')){
                      $purchaseQ .= " AND invoice_date >= '".$from."' AND invoice_date <= '".$to."'";
                    }
                    $purchaseR = mysqli_query($conn, $purchaseQ);
                    if($purchaseR && mysqli_num_rows($purchaseR) > 0){
                        while ($purchaseRow = mysqli_fetch_assoc($purchaseR)) {
                            $tmp3['id'] = (isset($purchaseRow['id'])) ? $purchaseRow['id'] : '';
                            $tmp3['type'] = 'PURCHASEBILL';
                            $tmp3['date'] = (isset($purchaseRow['invoice_date'])) ? $purchaseRow['invoice_date'] : '';
                            $tmp3['no'] = (isset($purchaseRow['invoice_no'])) ? $purchaseRow['invoice_no'] : '';
                            $tmp3['narration'] = (isset($purchaseRow['invoice_no']) && $purchaseRow['invoice_no'] != '') ? 'Purchase Bill - '.$purchaseRow['invoice_no'] : 'Purchase Bill';
                            $tmp3['debit'] = '';
                            $tmp3['credit'] = (isset($purchaseRow['total_total']) && $purchaseRow['total_total'] != '') ? $purchaseRow['total_total'] : 0;
                            $tmp3['url'] = 'purchase.php?id='.$tmp3['id'];
                            $detail[] = $tmp3;
                        }
                    }
                }
                /*--------------------------------------------CALCULATE PURCHASE END-----------------------------------------*/

                /*--------------------------------------------CALCULATE PURCHASE RETURN START---------------------------------------*/
                if(isset($ledgerRow['group_id']) && $ledgerRow['group_id'] == 14){//for vendor only
                    $purchaseReturnQ = "SELECT id, debit_note_date, debit_note_no, remarks, finalamount FROM purchase_return WHERE pharmacy_id = '".$pharmacy_id."' AND vendor_id = '".$id."'";
                    if(isset($is_financial) && $is_financial == 1){
                        $purchaseReturnQ .= " AND financial_id = '".$financial_id."'";
                    }
                    if((isset($from) && $from != '') && (isset($to) && $to != '')){
                      $purchaseReturnQ .= " AND debit_note_date >= '".$from."' AND debit_note_date <= '".$to."'";
                    }
                    $purchaseReturnR = mysqli_query($conn, $purchaseReturnQ);
                    if($purchaseReturnR && mysqli_num_rows($purchaseReturnR) > 0){
                        while ($purchaseReturnRow = mysqli_fetch_assoc($purchaseReturnR)) {
                            $tmp4['id'] = (isset($purchaseReturnRow['id'])) ? $purchaseReturnRow['id'] : '';
                            $tmp4['type'] = 'PURCHASEBILLRETURN';
                            $tmp4['date'] = (isset($purchaseReturnRow['debit_note_date'])) ? $purchaseReturnRow['debit_note_date'] : '';
                            $tmp4['no'] = (isset($purchaseReturnRow['debit_note_no'])) ? $purchaseReturnRow['debit_note_no'] : '';
                            $tmp4['narration'] = (isset($purchaseReturnRow['debit_note_no']) && $purchaseReturnRow['debit_note_no'] != '') ? 'Debit Note - '.$purchaseReturnRow['debit_note_no'] : 'Debit Note';
                            $tmp4['debit'] = (isset($purchaseReturnRow['finalamount']) && $purchaseReturnRow['finalamount'] != '') ? $purchaseReturnRow['finalamount'] : 0;
                            $tmp4['credit'] = '';
                            $tmp4['url'] = 'purchase-return.php?id='.$tmp4['id'];
                            $detail[] = $tmp4;
                        }
                    }
                }
                /*--------------------------------------------CALCULATE PURCHASE RETURN END---------------------------------------*/
    
                /*-------------------------------------------------CASH TRANSACTION START------------------------------------------*/
                $cashTransactionQ = "SELECT id, payment_type, voucher_no, voucher_date, amount, narration FROM cash_transaction WHERE pharmacy_id = '".$pharmacy_id."' AND perticular = '".$id."'";
                if(isset($is_financial) && $is_financial == 1){
                    $cashTransactionQ .= " AND financial_id = '".$financial_id."'";
                }
                if((isset($from) && $from != '') && (isset($to) && $to != '')){
                  $cashTransactionQ .= " AND voucher_date >= '".$from."' AND voucher_date <= '".$to."'";
                }
                $cashTransactionR = mysqli_query($conn, $cashTransactionQ);
                if($cashTransactionR && mysqli_num_rows($cashTransactionR) > 0){
                    while ($cashTransactionRow = mysqli_fetch_assoc($cashTransactionR)) {
                        $tmp5['id'] = (isset($cashTransactionRow['id'])) ? $cashTransactionRow['id'] : '';
                        $tmp5['type'] = 'CASHTRANSACTION';
                        $tmp5['date'] = (isset($cashTransactionRow['voucher_date'])) ? $cashTransactionRow['voucher_date'] : '';
                        $tmp5['no'] = (isset($cashTransactionRow['voucher_no'])) ? $cashTransactionRow['voucher_no'] : '';
                        
                        $cashVoucherNo = (isset($cashTransactionRow['voucher_no']) && $cashTransactionRow['voucher_no'] != '') ? ' - '.$cashTransactionRow['voucher_no'] : '';
                        $cashNarration = (isset($cashTransactionRow['narration']) && $cashTransactionRow['narration'] != '') ? ' - '.$cashTransactionRow['narration'] : '';
                        $cashLable = (isset($cashTransactionRow['payment_type']) && $cashTransactionRow['payment_type'] == 'receipt') ? 'Cash Receipt' : 'Cash Payment';
                        
                        $tmp5['narration'] = $cashLable.''.$cashVoucherNo.''.$cashNarration;
                        if(isset($cashTransactionRow['payment_type']) && $cashTransactionRow['payment_type'] == 'receipt'){
                            $tmp5['debit'] = '';
                            $tmp5['credit'] = (isset($cashTransactionRow['amount']) && $cashTransactionRow['amount'] != '') ? $cashTransactionRow['amount'] : 0;
                            $tmp5['url'] = 'cash-receipt.php?id='.$cashTransactionRow['id'];
                        }else{
                            $tmp5['debit'] = (isset($cashTransactionRow['amount']) && $cashTransactionRow['amount'] != '') ? $cashTransactionRow['amount'] : 0;
                            $tmp5['credit'] = '';
                            $tmp5['url'] = 'cash-payment.php?id='.$cashTransactionRow['id'];
                        }
                        $detail[] = $tmp5;
                    }
                }
                /*-------------------------------------------------CASH TRANSACTION END------------------------------------------*/
                
                /*-------------------------------------------------BANK TRANSACTION START------------------------------------------*/
                $bankTransactionQ = "SELECT bt.id, bt.payment_type, bt.voucher_no, bt.voucher_date, bt.amount, bt.payment_mode, bt.payment_mode_date, bt.payment_mode_no, bt.card_name, bt.other_reference, lg.name as bank_name FROM bank_transaction bt LEFT JOIN ledger_master lg ON bt.bank_id = lg.id WHERE bt.pharmacy_id = '".$pharmacy_id."' AND bt.perticular = '".$id."'";
                if(isset($is_financial) && $is_financial == 1){
                    $bankTransactionQ .= " AND bt.financial_id = '".$financial_id."'";
                }
                if((isset($from) && $from != '') && (isset($to) && $to != '')){
                  $bankTransactionQ .= " AND bt.voucher_date >= '".$from."' AND bt.voucher_date <= '".$to."'";
                }
                $bankTransactionR = mysqli_query($conn, $bankTransactionQ);
                if($bankTransactionR && mysqli_num_rows($bankTransactionR) > 0){
                    while ($bankTransactionRow = mysqli_fetch_assoc($bankTransactionR)) {
                        $tmp6['id'] = (isset($bankTransactionRow['id'])) ? $bankTransactionRow['id'] : '';
                        $tmp6['type'] = 'BANKTRANSACTION';
                        $tmp6['date'] = (isset($bankTransactionRow['voucher_date'])) ? $bankTransactionRow['voucher_date'] : '';
                        $tmp6['no'] = (isset($bankTransactionRow['voucher_no'])) ? $bankTransactionRow['voucher_no'] : '';
                        
                        $cashVoucherNo = (isset($bankTransactionRow['voucher_no']) && $bankTransactionRow['voucher_no'] != '') ? ' - '.$bankTransactionRow['voucher_no'] : '';
                        $paymentDate = (isset($bankTransactionRow['payment_mode_date']) && $bankTransactionRow['payment_mode_date'] != '0000-00-00') ? date('d/m/Y',strtotime($bankTransactionRow['payment_mode_date'])) : '';
                        $paymentNo = (isset($bankTransactionRow['payment_mode_no']) && $bankTransactionRow['payment_mode_no'] != '') ? $bankTransactionRow['payment_mode_no'] : '';
                        $cardName = (isset($bankTransactionRow['card_name']) && $bankTransactionRow['card_name'] != '') ? $bankTransactionRow['card_name'] : '';
                        $otherRef = (isset($bankTransactionRow['other_reference']) && $bankTransactionRow['other_reference'] != '') ? $bankTransactionRow['other_reference'] : '';
                        $bankName = (isset($bankTransactionRow['bank_name']) && $bankTransactionRow['bank_name'] != '') ? $bankTransactionRow['bank_name'] : '';
                        $cashLable = (isset($bankTransactionRow['payment_type']) && $bankTransactionRow['payment_type'] == 'receipt') ? 'Bank Receipt' : 'Bank Payment';
                        $paymentMode = (isset($bankTransactionRow['payment_mode'])) ? $bankTransactionRow['payment_mode'] : '';
                        
                        if($paymentMode == 'cheque'){
                            $narration =  $cashLable.''.$cashVoucherNo.' - '.$bankName.' - Cheque No: '.$paymentNo.' - Cheque Date: '.$paymentDate;
                        }elseif($paymentMode == 'dd'){
                            $narration =  $cashLable.''.$cashVoucherNo.' - '.$bankName.' - DD No: '.$paymentNo.' - DD Date: '.$paymentDate;
                        }elseif($paymentMode == 'net_banking'){
                            $narration =  $cashLable.''.$cashVoucherNo.' - '.$bankName.' - Net Banking UTR NO: '.$paymentNo;
                        }elseif($paymentMode == 'credit_debit_card'){
                            $narration =  $cashLable.''.$cashVoucherNo.' - '.$bankName.' - Card No: '.$paymentNo.' - Card Name: '.$cardName;
                        }elseif($paymentMode == 'other'){
                            $narration =  $cashLable.''.$cashVoucherNo.' - '.$bankName.' - Other Reference: '.$otherRef;
                        }else{
                            $narration =  $cashLable.''.$cashVoucherNo.' - '.$bankName;
                        }
                        
                        $tmp6['narration'] = $narration;
                        if(isset($bankTransactionRow['payment_type']) && $bankTransactionRow['payment_type'] == 'receipt'){
                            $tmp6['debit'] = '';
                            $tmp6['credit'] = (isset($bankTransactionRow['amount']) && $bankTransactionRow['amount'] != '') ? $bankTransactionRow['amount'] : 0;
                            $tmp6['url'] = 'bank-receipt.php?id='.$bankTransactionRow['id'];
                        }else{
                            $tmp6['debit'] = (isset($bankTransactionRow['amount']) && $bankTransactionRow['amount'] != '') ? $bankTransactionRow['amount'] : 0;
                            $tmp6['credit'] = '';
                            $tmp6['url'] = 'bank-payment.php?id='.$bankTransactionRow['id'];
                        }
                        $detail[] = $tmp6;
                    }
                }
                /*-------------------------------------------------BANK TRANSACTION END------------------------------------------*/
    
                /*---------------------------------------------COUNT JOURNAL VOUCHER START---------------------------------------*/
                $jurnalQ = "SELECT jv.id, jv.voucher_date, jvd.debit, jvd.credit, jvd.remarks  FROM journal_vouchar_details jvd INNER JOIN journal_vouchar jv ON jvd.voucher_id = jv.id WHERE jvd.particular = '".$id."' AND jv.pharmacy_id = '".$pharmacy_id."'";
                if(isset($is_financial) && $is_financial == 1){
                    $jurnalQ .= " AND jv.financial_id = '".$financial_id."'";
                }
                if((isset($from) && $from != '') && (isset($to) && $to != '')){
                  $jurnalQ .= " AND jv.voucher_date >= '".$from."' AND jv.voucher_date <= '".$to."'";
                }
                $jurnalR = mysqli_query($conn, $jurnalQ);
                if($jurnalR && mysqli_num_rows($jurnalR) > 0){
                    while ($jurnalRow = mysqli_fetch_assoc($jurnalR)) {
                        $tmp7['id'] = (isset($jurnalRow['id'])) ? $jurnalRow['id'] : '';
                        $tmp7['type'] = 'JOURNALVOUCHER';
                        $tmp7['date'] = (isset($jurnalRow['voucher_date'])) ? $jurnalRow['voucher_date'] : '';
                        $tmp7['no'] = '';
                        $remarks7 = (isset($jurnalRow['remarks']) && $jurnalRow['remarks'] != '') ? ' - '.$jurnalRow['remarks'] : '';
                        $tmp7['narration'] = 'Journal Voucher'.$remarks7;
                        if(isset($jurnalRow['debit']) && $jurnalRow['debit'] > 0){
                            $tmp7['debit'] = (isset($jurnalRow['debit']) && $jurnalRow['debit'] != '') ? $jurnalRow['debit'] : 0;
                            $tmp7['credit'] = '';
                        }else{
                            $tmp7['debit'] = '';
                            $tmp7['credit'] = (isset($jurnalRow['credit']) && $jurnalRow['credit'] != '') ? $jurnalRow['credit'] : 0;
                        }
                        $tmp7['url'] = 'journal-vouchar.php?id='.$tmp7['id'];
                        $detail[] = $tmp7;
                    }
                }
                /*-------------------------------------------------COUNT JOURNAL VOUCHER START----------------------------------------------------*/
            }
        }
        if(!empty($detail)){
            function date_compare($a, $b){

                $t1 = strtotime($a['date']);

                $t2 = strtotime($b['date']);

                return $t1 - $t2;
            }
            usort($detail, 'date_compare');
            $data['data'] = $detail;
        }else{
            $data['data'] = [];
        }
        return $data;
    }
    
    /*---------------------------15-12-2018 - GAUTAM MAKWANA--------------------------------------------*/
    function getGeneralSaleReport($from = null, $to = null, $is_financial = 0){
        global $conn;
        global $pharmacy_id;
        global $financial_id;
        $data = [];
        
        $query = "SELECT tb.id, tb.customer_name, tb.customer_mobile, tb.invoice_date, tb.invoice_no, tb.bill_type, tb.final_amount, tb.remarks, ct.name as customer_city FROM tax_billing tb LEFT JOIN own_cities ct ON tb.city_id = ct.id WHERE tb.pharmacy_id = $pharmacy_id AND tb.is_general_sale = 1";
        if(isset($is_financial) && $is_financial == 1){
            $query .= " AND tb.financial_id = '".$financial_id."'";
        }
        if((isset($from) && $from != '') && (isset($to) && $to != '')){
          $query .= " AND tb.invoice_date >= '".$from."' AND tb.invoice_date <= '".$to."'";
        }
        $query .= " ORDER BY tb.invoice_date ASC";
        $res = mysqli_query($conn, $query);
        if($res && mysqli_num_rows($res) > 0){
            while($row = mysqli_fetch_assoc($res)){
                $tmp['id'] = $row['id'];
                $tmp['date'] = $row['invoice_date'];
                $tmp['no'] = $row['invoice_no'];
                $tmp['name'] = $row['customer_name'];
                $tmp['mobile'] = $row['customer_mobile'];
                $tmp['city'] = $row['customer_city'];
                $tmp['bill_type'] = $row['bill_type'];
                $tmp['remarks'] = $row['remarks'];
                $tmp['amount'] = (isset($row['final_amount']) && $row['final_amount'] != '') ? $row['final_amount'] : 0;
                $tmp['url'] = 'sales-tax-billing.php?id='.$row['id'];
                $data[] = $tmp;
            }
        }
        return $data;
    }
    
    function getGstOnSaleReport($from = null, $to = null, $is_financial = 0){
        global $conn;
        global $pharmacy_id;
        global $financial_id;
        $data = [];
        
        $query = "SELECT tb.id, tb.customer_name, tb.customer_mobile, tb.invoice_date, tb.invoice_no, tb.bill_type, (tb.totalcgst+tb.totalsgst) as amount, tb.remarks, tb.is_general_sale, ct.name as customer_city, lg.name as l_customer_name, lg.mobile as l_customer_mobile FROM tax_billing tb LEFT JOIN own_cities ct ON tb.city_id = ct.id LEFT JOIN ledger_master lg ON tb.customer_id = lg.id WHERE tb.pharmacy_id = $pharmacy_id";
        if(isset($is_financial) && $is_financial == 1){
            $query .= " AND tb.financial_id = '".$financial_id."'";
        }
        if((isset($from) && $from != '') && (isset($to) && $to != '')){
          $query .= " AND tb.invoice_date >= '".$from."' AND tb.invoice_date <= '".$to."'";
        }
        $query .= " ORDER BY tb.invoice_date ASC";
        $res = mysqli_query($conn, $query);
        if($res && mysqli_num_rows($res) > 0){
            while($row = mysqli_fetch_assoc($res)){
                $tmp['id'] = $row['id'];
                $tmp['date'] = $row['invoice_date'];
                $tmp['no'] = $row['invoice_no'];
                if(isset($row['is_general_sale']) && $row['is_general_sale'] == 1){
                    $tmp['name'] = $row['customer_name'].' (General)';
                    $tmp['mobile'] = $row['customer_mobile'];
                }else{
                    $tmp['name'] = $row['l_customer_name'];
                    $tmp['mobile'] = $row['l_customer_mobile'];
                }
                $tmp['city'] = $row['customer_city'];
                $tmp['bill_type'] = $row['bill_type'];
                $tmp['remarks'] = $row['remarks'];
                $tmp['credit'] = (isset($row['amount']) && $row['amount'] != '') ? $row['amount'] : 0;
                $tmp['debit'] = '';
                $tmp['amount'] = (isset($row['amount']) && $row['amount'] != '') ? $row['amount'] : 0;
                $tmp['url'] = 'sales-tax-billing.php?id='.$row['id'];
                $data[] = $tmp;
            }
        }
        
        $queryGOS = "SELECT id,(totalcgst+totalsgst) as amount , credit_note_date, credit_note_no from sale_return where pharmacy_id ='".$pharmacy_id."'";
        if(isset($is_financial) && $is_financial == 1){
            $queryGOS .= " AND financial_id = '".$financial_id."'";
        }
        if((isset($from) && $from != '') && (isset($to) && $to != '')){
          $queryGOS .= " AND credit_note_date >= '".$from."' AND credit_note_date <= '".$to."'";
        }
        $queryGOS .= " ORDER BY credit_note_date ASC";
        $resR = mysqli_query($conn, $queryGOS);
        if($resR && mysqli_num_rows($resR) > 0){
            while($rows = mysqli_fetch_assoc($resR)){
                $tmps['id'] = $rows['id'];
                $tmps['date'] = $rows['credit_note_date'];
                $tmps['no'] = $rows['credit_note_no'];
                $tmps['debit'] = (isset($rows['amount']) && $rows['amount'] != '') ? $rows['amount'] : 0;
                $tmps['credit'] = '';
                $tmps['amount'] = (isset($rows['amount']) && $rows['amount'] != '') ? $rows['amount'] : 0;
                $data[] = $tmps;
            }
        }
        
        return $data;
    }
    
    function getIgstOnSaleReport($from = null, $to = null, $is_financial = 0){
        global $conn;
        global $pharmacy_id;
        global $financial_id;
        $data = [];
        
        $query = "SELECT tb.id, tb.customer_name, tb.customer_mobile, tb.invoice_date, tb.invoice_no, tb.bill_type, tb.totaligst as amount, tb.remarks, tb.is_general_sale, ct.name as customer_city, lg.name as l_customer_name, lg.mobile as l_customer_mobile FROM tax_billing tb LEFT JOIN own_cities ct ON tb.city_id = ct.id LEFT JOIN ledger_master lg ON tb.customer_id = lg.id WHERE tb.pharmacy_id = $pharmacy_id AND tb.totaligst IS NOT NULL AND tb.totaligst > 0";
        if(isset($is_financial) && $is_financial == 1){
            $query .= " AND tb.financial_id = '".$financial_id."'";
        }
        if((isset($from) && $from != '') && (isset($to) && $to != '')){
          $query .= " AND tb.invoice_date >= '".$from."' AND tb.invoice_date <= '".$to."'";
        }
        $query .= " ORDER BY tb.invoice_date ASC";
        $res = mysqli_query($conn, $query);
        if($res && mysqli_num_rows($res) > 0){
            while($row = mysqli_fetch_assoc($res)){
                $tmp['id'] = $row['id'];
                $tmp['date'] = $row['invoice_date'];
                $tmp['no'] = $row['invoice_no'];
                if(isset($row['is_general_sale']) && $row['is_general_sale'] == 1){
                    $tmp['name'] = $row['customer_name'].' (General)';
                    $tmp['mobile'] = $row['customer_mobile'];
                }else{
                    $tmp['name'] = $row['l_customer_name'];
                    $tmp['mobile'] = $row['l_customer_mobile'];
                }
                $tmp['city'] = $row['customer_city'];
                $tmp['bill_type'] = $row['bill_type'];
                $tmp['remarks'] = $row['remarks'];
                $tmp['amount'] = (isset($row['amount']) && $row['amount'] != '') ? $row['amount'] : 0;
                $tmp['url'] = 'sales-tax-billing.php?id='.$row['id'];
                $data[] = $tmp;
            }
        }
        return $data;
    }
    
    function getSaleAccReport($from = null, $to = null, $is_financial = 0){
        global $conn;
        global $pharmacy_id;
        global $financial_id;
        $data = [];
        
        $query = "SELECT tb.id, tb.customer_name, tb.customer_mobile, tb.invoice_date, tb.invoice_no, tb.bill_type, (tb.alltotalamount+tb.couriercharge_val) as amount, tb.remarks, tb.is_general_sale, ct.name as customer_city, lg.name as l_customer_name, lg.mobile as l_customer_mobile FROM tax_billing tb LEFT JOIN own_cities ct ON tb.city_id = ct.id LEFT JOIN ledger_master lg ON tb.customer_id = lg.id WHERE tb.pharmacy_id = $pharmacy_id AND tb.alltotalamount IS NOT NULL AND tb.alltotalamount > 0";
        if(isset($is_financial) && $is_financial == 1){
            $query .= " AND tb.financial_id = '".$financial_id."'";
        }
        if((isset($from) && $from != '') && (isset($to) && $to != '')){
          $query .= " AND tb.invoice_date >= '".$from."' AND tb.invoice_date <= '".$to."'";
        }
        $query .= " ORDER BY tb.invoice_date ASC";
        $res = mysqli_query($conn, $query);
        if($res && mysqli_num_rows($res) > 0){
            while($row = mysqli_fetch_assoc($res)){
                $tmp['id'] = $row['id'];
                $tmp['date'] = $row['invoice_date'];
                $tmp['no'] = $row['invoice_no'];
                $tmp['remarks'] = $row['remarks'];
                $tmp['credit'] = (isset($row['amount']) && $row['amount'] != '') ? $row['amount'] : 0;
                $tmp['debit'] = '';
                $tmp['amount'] = (isset($row['amount']) && $row['amount'] != '') ? $row['amount'] : 0;
                $tmp['url'] = 'sales-tax-billing.php?id='.$row['id'];
                $data[] = $tmp;
            }
        }
        
         $querysr = "SELECT id , credit_note_date , credit_note_no , taxable_amount as amount, remarks from sale_return where pharmacy_id = '".$pharmacy_id."' and taxable_amount IS NOT NULL AND taxable_amount > 0";
         if(isset($is_financial) && $is_financial == 1){
            $querysr .= " AND financial_id = '".$financial_id."'";
        }
           if((isset($from) && $from != '') && (isset($to) && $to != '')){
          $querysr .= " AND credit_note_date >= '".$from."' AND credit_note_date <= '".$to."'";
        }
        $querysr .= " ORDER BY credit_note_date ASC";
        $querysrR = mysqli_query($conn, $querysr);
        if($querysrR && mysqli_num_rows($querysrR) > 0){
            while($rows = mysqli_fetch_assoc($querysrR)){
                $tmps['id'] = $rows['id'];
                $tmps['date'] = $rows['credit_note_date'];
                $tmps['no'] = $rows['credit_note_no'];
                $tmps['remarks'] = $rows['remarks'];
                $tmps['credit'] = '';
                $tmps['debit'] = (isset($rows['amount']) && $rows['amount'] != '') ? $rows['amount'] : 0;
                $tmps['amount'] = (isset($rows['amount']) && $rows['amount'] != '') ? $rows['amount'] : 0;
                $data[] = $tmps;
            }
        }
        
        
        return $data;
    }
    
    function getSaleAccOgsReport($from = null, $to = null, $is_financial = 0){
        global $conn;
        global $pharmacy_id;
        global $financial_id;
        $current_state_code = (isset($_SESSION['state_code'])) ? $_SESSION['state_code'] : '';
        $data = [];
        
        $query = "SELECT tb.id, tb.customer_name, tb.customer_mobile, tb.invoice_date, tb.invoice_no, tb.bill_type, (tb.alltotalamount-tb.discount_rs) as amount, tb.remarks, tb.is_general_sale, ct.name as customer_city, lg.name as l_customer_name, lg.mobile as l_customer_mobile FROM tax_billing tb LEFT JOIN own_cities ct ON tb.city_id = ct.id LEFT JOIN own_states st ON ct.state_id = st.id LEFT JOIN ledger_master lg ON tb.customer_id = lg.id WHERE tb.pharmacy_id = $pharmacy_id AND tb.alltotalamount IS NOT NULL AND tb.alltotalamount > 0 AND st.state_code_gst != '".$current_state_code."'";
        if(isset($is_financial) && $is_financial == 1){
            $query .= " AND tb.financial_id = '".$financial_id."'";
        }
        if((isset($from) && $from != '') && (isset($to) && $to != '')){
          $query .= " AND tb.invoice_date >= '".$from."' AND tb.invoice_date <= '".$to."'";
        }
        $query .= " ORDER BY tb.invoice_date ASC";
        $res = mysqli_query($conn, $query);
        if($res && mysqli_num_rows($res) > 0){
            while($row = mysqli_fetch_assoc($res)){
                $tmp['id'] = $row['id'];
                $tmp['date'] = $row['invoice_date'];
                $tmp['no'] = $row['invoice_no'];
                if(isset($row['is_general_sale']) && $row['is_general_sale'] == 1){
                    $tmp['name'] = $row['customer_name'].' (General)';
                    $tmp['mobile'] = $row['customer_mobile'];
                }else{
                    $tmp['name'] = $row['l_customer_name'];
                    $tmp['mobile'] = $row['l_customer_mobile'];
                }
                $tmp['city'] = $row['customer_city'];
                $tmp['bill_type'] = $row['bill_type'];
                $tmp['remarks'] = $row['remarks'];
                $tmp['amount'] = (isset($row['amount']) && $row['amount'] != '') ? $row['amount'] : 0;
                $tmp['url'] = 'sales-tax-billing.php?id='.$row['id'];
                $data[] = $tmp;
            }
        }
        return $data;
    }
    
    function getPurchaseAccReport($from = null, $to = null, $is_financial = 0){
        global $conn;
        global $pharmacy_id;
        global $financial_id;
        $data = [];
        
        $query = "SELECT p.id, p.vouchar_date, p.voucher_no, p.invoice_date, p.invoice_no, p.purchase_type, (p.total_amount-p.rs_discount) as amount, lg.name as vendor_name, lg.city as vendor_city, lg.mobile as vendor_mobile FROM purchase p LEFT JOIN ledger_master lg ON p.vendor = lg.id LEFT JOIN own_cities ct ON lg.city = ct.id WHERE p.pharmacy_id = '".$pharmacy_id."' AND p.total_amount > 0 AND p.total_amount IS NOT NULL";
        if(isset($is_financial) && $is_financial == 1){
            $query .= " AND p.financial_id = '".$financial_id."'";
        }
        if((isset($from) && $from != '') && (isset($to) && $to != '')){
          $query .= " AND  p.vouchar_date  >= '".$from."' AND p.vouchar_date  <= '".$to."'";
        }
        $query .= " ORDER BY  p.vouchar_date  ASC";  
        
        $res = mysqli_query($conn, $query);
        if($res && mysqli_num_rows($res) > 0){
            while($row = mysqli_fetch_assoc($res)){
                $tmp['id'] = $row['id'];
                $tmp['date'] = $row['vouchar_date'];
                $tmp['no'] = $row['voucher_no'];
                $tmp['name'] = $row['vendor_name'];
                $tmp['mobile'] = $row['vendor_mobile'];
                $tmp['city'] = $row['vendor_city'];
                $tmp['bill_type'] = $row['purchase_type'];
                $tmp['remarks'] = '';
                $tmp['credit'] = '';
                $tmp['debit'] = (isset($row['amount']) && $row['amount'] != '') ? $row['amount'] : 0;
                $tmp['amount'] = (isset($row['amount']) && $row['amount'] != '') ? $row['amount'] : 0;
                $tmp['url'] = 'purchase.php?id='.$row['id'];
                $data[] = $tmp;
            }
        }
    
      $querypr = "SELECT pr.id, (pr.totalamount) as amount, pr.debit_note_date , pr.debit_note_no from purchase_return pr where pr.pharmacy_id = '".$pharmacy_id."' AND pr.totalamount > 0 AND pr.totalamount IS NOT NULL";    
      if(isset($is_financial) && $is_financial == 1){
            $querypr .= " AND pr.financial_id = '".$financial_id."'";
        }
        if((isset($from) && $from != '') && (isset($to) && $to != '')){
          $querypr .= " AND  pr.debit_note_date  >= '".$from."' AND  pr.debit_note_date  <= '".$to."'";
        }
        $querypr .= " ORDER BY  pr.debit_note_date  ASC"; 
         $resR = mysqli_query($conn, $querypr);
        if($resR && mysqli_num_rows($resR) > 0){
            while($rows = mysqli_fetch_assoc($resR)){
                $tmps['id'] = $rows['id'];
                $tmps['date'] = $rows['debit_note_date'];
                $tmps['no'] = $rows['debit_note_no'];
                $tmps['credit'] =(isset($rows['amount']) && $rows['amount'] != '') ? $rows['amount'] : 0;
                $tmps['debit'] = '';
                $tmps['amount'] = (isset($rows['amount']) && $rows['amount'] != '') ? $rows['amount'] : 0;
               
                $data[] = $tmps;
            }
        }
        
        return $data;
    }
    
    
    function getGstOnPurchaseReport($from = null, $to = null, $is_financial = 0){
        global $conn;
        global $pharmacy_id;
        global $financial_id;
        $data = []; 
        
        $query = "SELECT p.id, p.vouchar_date, p.voucher_no, p.invoice_date, p.invoice_no, p.purchase_type, (p.total_cgst+p.total_sgst) as amount, lg.name as vendor_name, lg.city as vendor_city, lg.mobile as vendor_mobile FROM purchase p LEFT JOIN ledger_master lg ON p.vendor = lg.id LEFT JOIN own_cities ct ON lg.city = ct.id WHERE p.pharmacy_id = '".$pharmacy_id."' AND p.total_cgst > 0 AND p.total_cgst IS NOT NULL";
        if(isset($is_financial) && $is_financial == 1){
            $query .= " AND p.financial_id = '".$financial_id."'";
        }
        if((isset($from) && $from != '') && (isset($to) && $to != '')){
          $query .= " AND  p.vouchar_date  >= '".$from."' AND p.vouchar_date  <= '".$to."'";
        }
        $query .= " ORDER BY  p.vouchar_date  ASC";  
        
        $res = mysqli_query($conn, $query);
        if($res && mysqli_num_rows($res) > 0){
            while($row = mysqli_fetch_assoc($res)){
                $tmp['id'] = $row['id'];
                $tmp['date'] = $row['vouchar_date'];
                $tmp['no'] = $row['voucher_no'];
                $tmp['name'] = $row['vendor_name'];
                $tmp['mobile'] = $row['vendor_mobile'];
                $tmp['city'] = $row['vendor_city'];
                $tmp['bill_type'] = $row['purchase_type'];
                $tmp['remarks'] = '';
                $tmp['credit'] = '';
                $tmp['debit'] = (isset($row['amount']) && $row['amount'] != '') ? $row['amount'] : 0;
                $tmp['amount'] = (isset($row['amount']) && $row['amount'] != '') ? $row['amount'] : 0;
                $tmp['url'] = 'purchase.php?id='.$row['id'];
                $data[] = $tmp;
            }
        }
        
    $querypr = "SELECT pr.id, (pr.cgst+pr.sgst) as amount, pr.debit_note_date , pr.debit_note_no from purchase_return pr where pr.pharmacy_id = '".$pharmacy_id."' AND pr.cgst > 0 AND pr.cgst IS NOT NULL";    
      if(isset($is_financial) && $is_financial == 1){
            $querypr .= " AND pr.financial_id = '".$financial_id."'";
        }
        if((isset($from) && $from != '') && (isset($to) && $to != '')){
          $querypr .= " AND  pr.debit_note_date  >= '".$from."' AND  pr.debit_note_date  <= '".$to."'";
        }
        $querypr .= " ORDER BY  pr.debit_note_date  ASC"; 
         $resR = mysqli_query($conn, $querypr);
        if($resR && mysqli_num_rows($resR) > 0){
            while($rows = mysqli_fetch_assoc($resR)){
                $tmps['id'] = $rows['id'];
                $tmps['date'] = $rows['debit_note_date'];
                $tmps['no'] = $rows['debit_note_no'];
                $tmps['credit'] =(isset($rows['amount']) && $rows['amount'] != '') ? $rows['amount'] : 0;
                $tmps['debit'] = '';
                $tmps['amount'] = (isset($rows['amount']) && $rows['amount'] != '') ? $rows['amount'] : 0;
               
                $data[] = $tmps;
            }
        }
        
        return $data;
    }
    
    function getIGstOnPurchaseReport($from = null, $to = null, $is_financial = 0){
     global $conn;
        global $pharmacy_id;
        global $financial_id;
        $data = []; 

        $query = "SELECT p.id, p.vouchar_date, p.voucher_no, p.invoice_date, p.invoice_no, p.purchase_type, (p.total_igst) as amount, lg.name as vendor_name, lg.city as vendor_city, lg.mobile as vendor_mobile FROM purchase p LEFT JOIN ledger_master lg ON p.vendor = lg.id LEFT JOIN own_cities ct ON lg.city = ct.id WHERE p.pharmacy_id = '".$pharmacy_id."' AND p.total_igst > 0 AND p.total_igst IS NOT NULL";
        if(isset($is_financial) && $is_financial == 1){
            $query .= " AND p.financial_id = '".$financial_id."'";
        }
        if((isset($from) && $from != '') && (isset($to) && $to != '')){
          $query .= " AND  p.vouchar_date  >= '".$from."' AND p.vouchar_date  <= '".$to."'";
        }
        $query .= " ORDER BY  p.vouchar_date  ASC";  

         $res = mysqli_query($conn, $query);
        if($res && mysqli_num_rows($res) > 0){
            while($row = mysqli_fetch_assoc($res)){
                $tmp['id'] = $row['id'];
                $tmp['date'] = $row['vouchar_date'];
                $tmp['no'] = $row['voucher_no'];
                $tmp['credit'] = '';
                $tmp['debit'] = (isset($row['amount']) && $row['amount'] != '') ? $row['amount'] : 0;
                $tmp['amount'] = (isset($row['amount']) && $row['amount'] != '') ? $row['amount'] : 0;
                $data[] = $tmp;
            }
        }


         $querypr = "SELECT pr.id, (pr.igst) as amount, pr.debit_note_date , pr.debit_note_no from purchase_return pr where pr.pharmacy_id = '".$pharmacy_id."' AND pr.igst > 0 AND pr.igst IS NOT NULL";    
      if(isset($is_financial) && $is_financial == 1){
            $querypr .= " AND pr.financial_id = '".$financial_id."'";
        }
        if((isset($from) && $from != '') && (isset($to) && $to != '')){
          $querypr .= " AND  pr.debit_note_date  >= '".$from."' AND  pr.debit_note_date  <= '".$to."'";
        }
        $querypr .= " ORDER BY  pr.debit_note_date  ASC"; 
          $resR = mysqli_query($conn, $querypr);
        if($resR && mysqli_num_rows($resR) > 0){
            while($rows = mysqli_fetch_assoc($resR)){
                $tmps['id'] = $rows['id'];
                $tmps['date'] = $rows['debit_note_date'];
                $tmps['no'] = $rows['debit_note_no'];
                $tmps['credit'] =(isset($rows['amount']) && $rows['amount'] != '') ? $rows['amount'] : 0;
                $tmps['debit'] = '';
                $tmps['amount'] = (isset($rows['amount']) && $rows['amount'] != '') ? $rows['amount'] : 0;
               
                $data[] = $tmps;
            }
        }

        return $data;
}

    function getPurchaseAccOgsReport($from = null, $to = null, $is_financial = 0){
        global $conn;
        global $pharmacy_id;
        global $financial_id;
        $current_state_code = (isset($_SESSION['state_code'])) ? $_SESSION['state_code'] : '';
        $data = [];
        
        $query = "SELECT p.id, p.vouchar_date, p.voucher_no, p.invoice_date, p.invoice_no, p.purchase_type, (p.total_amount-p.rs_discount) as amount, lg.name as vendor_name, lg.city as vendor_city, lg.mobile as vendor_mobile FROM purchase p LEFT JOIN ledger_master lg ON p.vendor = lg.id LEFT JOIN own_cities ct ON lg.city = ct.id LEFT JOIN own_states st ON lg.state = st.id WHERE p.pharmacy_id = '".$pharmacy_id."' AND p.total_amount > 0 AND p.total_amount IS NOT NULL AND st.state_code_gst != '".$current_state_code."'";
        $res = mysqli_query($conn, $query);
        if($res && mysqli_num_rows($res) > 0){
            while($row = mysqli_fetch_assoc($res)){
                $tmp['id'] = $row['id'];
                $tmp['date'] = $row['invoice_date'];
                $tmp['no'] = $row['invoice_no'];
                $tmp['name'] = $row['vendor_name'];
                $tmp['mobile'] = $row['vendor_mobile'];
                $tmp['city'] = $row['vendor_city'];
                $tmp['bill_type'] = $row['purchase_type'];
                $tmp['remarks'] = '';
                $tmp['amount'] = (isset($row['amount']) && $row['amount'] != '') ? $row['amount'] : 0;
                $tmp['url'] = 'purchase.php?id='.$row['id'];
                $data[] = $tmp;
            }
        }
        return $data;
    }
    
     function getOpeningStockReport ($from = null, $to = null, $is_financial = 0){
        global $conn;
        global $pharmacy_id;
        global $financial_id;
         $data = [];

       $stock = getTotalClosingStock($from , $to , 1);
      
       
      if($stock){
                $tmp['amount'] = (isset($stock['opening_stock']) && $stock['opening_stock'] != '') ? $stock['opening_stock'] : 0;
                $data[] = $tmp;
        }

 return $data;
       
}

function getClosingStockReport ($from = null, $to = null, $is_financial = 0){
        global $conn;
        global $pharmacy_id;
        global $financial_id;
         $data = [];
 
       $stock = getTotalClosingStock($from , $to , 1 );

      if($stock){
                $tmp['amount'] = (isset($stock['closing_stock']) && $stock['closing_stock'] != '') ? $stock['closing_stock'] : 0;
                $data[] = $tmp;
        }

 return $data;
       
}
   
   function TaxLiabilityReport ($from = null, $to = null, $is_financial = 0){
   global $conn;
        global $pharmacy_id;
        global $financial_id;
   $data = [];
   $gstonsale = getGstOnSaleReport($from, $to , 1);
   if($gstonsale){
     foreach ($gstonsale as $gstonsaleR) {
        $data[] = $gstonsaleR;
     }
   }
    $igstonsale = getIgstOnSaleReport($from, $to , 1);
    if($igstonsale){
        foreach ($igstonsale as $igstonsaleR) {
        $data[] = $igstonsaleR;
     }
    }
   $gstonpurchase = getGstOnPurchaseReport($from, $to , 1);
   if($gstonpurchase){
        foreach ($gstonpurchase as $igstonsaleR) {
        $data[] = $igstonsaleR;
     }
    }
   $igstonpurchase = getIGstOnPurchaseReport($from, $to , 1);
   if($igstonpurchase){
        foreach ($igstonpurchase as $igstonpurchaseR) {
        $data[] = $igstonpurchaseR;
     }
    }
    
     //accounting cash management ----------  perticular -> tax liability --------------------------
    $acm =  "SELECT id ,voucher_date , voucher_no ,amount FROM `accounting_cash_management` where perticular = 199  AND amount > 0 AND amount IS NOT NULL AND pharmacy_id = '".$pharmacy_id."'";
   if(isset($is_financial) && $is_financial == 1){
            $acm .= " AND financial_id = '".$financial_id."'";
        }
   if((isset($from) && $from != '') && (isset($to) && $to != '')){
          $acm .= " AND  voucher_date  >= '".$from."' AND  voucher_date  <= '".$to."'";
        }
         $acm .= " ORDER BY `id` ASC";

   
     $acmR = mysqli_query($conn, $acm);
        if($acmR && mysqli_num_rows($acmR) > 0){
            while($acmL = mysqli_fetch_assoc($acmR)){ 
                $tmps['id'] = $acmL['id'];
                $tmps['date'] = $acmL['voucher_date'];
                $tmps['no'] = $acmL['voucher_no'];
                $tmps['credit'] = '';
                $tmps['debit'] = (isset($acmL['amount']) && $acmL['amount'] != '') ? $acmL['amount'] : 0;
                $tmps['amount'] = (isset($acmL['amount']) && $acmL['amount'] != '') ? $acmL['amount'] : 0;
                $data[] = $tmps;
       }
   }
    
    //accounting cheque ----------  perticular -> tax liability --------------------------
    $Acheque = "SELECT id , voucher_no , voucher_date, amount FROM `accounting_cheque` WHERE perticular = 199 AND amount > 0 AND amount IS NOT NULL AND pharmacy_id = '".$pharmacy_id."'";
    if(isset($is_financial) && $is_financial == 1){
         $Acheque .= " AND financial_id = '".$financial_id."'";
        }
    if((isset($from) && $from != '') && (isset($to) && $to != '')){
          $Acheque .= " AND  voucher_date  >= '".$from."' AND  voucher_date  <= '".$to."'";
        }
        $Acheque .= " ORDER BY `id` ASC";

     $AchequeR = mysqli_query($conn, $Acheque);
        if($AchequeR && mysqli_num_rows($AchequeR) > 0){
            while($AchequeL = mysqli_fetch_assoc($AchequeR)){ 

                $tmp['id'] = $AchequeL['id'];
                $tmp['date'] = $AchequeL['voucher_date'];
                $tmp['no'] = $AchequeL['voucher_no'];
                $tmp['credit'] = '';
                $tmp['debit'] = (isset($AchequeL['amount']) && $AchequeL['amount'] != '') ? $AchequeL['amount'] : 0;
                $tmp['amount'] = (isset($AchequeL['amount']) && $AchequeL['amount'] != '') ? $AchequeL['amount'] : 0;
                $data[] = $tmp;
       }
   }
   
     $jvd = "SELECT jv.id, jv.voucher_date , jvd.debit , jvd.credit FROM `journal_vouchar` jv INNER JOIN `journal_vouchar_details` jvd WHERE jv.id = jvd.voucher_id AND jvd.particular = 199 AND jv.pharmacy_id = '".$pharmacy_id."'";
 
     if((isset($from) && $from != '') && (isset($to) && $to != '')){
         $jvd .= " AND  jv.voucher_date  >= '".$from."' AND  jv.voucher_date  <= '".$to."'";
        }
       $jvd .= " ORDER BY jv.id ASC";

 $jvdR = mysqli_query($conn, $jvd);
        if($jvdR && mysqli_num_rows($jvdR) > 0){
            while($jvdL = mysqli_fetch_assoc($jvdR)){ 

                $tmpj['id'] = $jvdL['id'];
                $tmpj['date'] = $jvdL['voucher_date'];
                $tmpj['no'] = '';

                  if(isset($jvdL['credit']) && $jvdL['credit'] == 0){
                $tmpj['debit'] = (isset($jvdL['debit']) && $jvdL['debit'] != '') ? $jvdL['debit'] : 0; 
                $tmpj['amount'] = (isset($jvdL['debit']) && $jvdL['debit'] != '') ? $jvdL['debit'] : 0;    
                 }
                 elseif(isset($jvdL['debit']) && $jvdL['debit'] == 0){
                $tmpj['debit'] = (isset($jvdL['credit']) && $jvdL['credit'] != '') ? $jvdL['credit'] : 0; 
                $tmpj['amount'] = (isset($jvdL['credit']) && $jvdL['credit'] != '') ? $jvdL['credit'] : 0;     
                 }
                $tmpj['credit'] = '';
                // $tmpj['amount'] = (isset($jvdL['amount']) && $jvdL['amount'] != '') ? $jvdL['amount'] : 0;
                $data[] = $tmpj;
       }
   }
    
    
    return $data;
} 
    
    /*-----------------------------------------END-----------------------------------------------------*/


    function getAllProductSale($id = [], $startdate = null , $enddate = null){
       $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
            $financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';
    
       global $conn;
    
       $sales = [];
         $allid = implode(',', $id);
         $query = "SELECT id FROM product_master where id IN (".$allid.") ORDER BY ex_date";
          $res = mysqli_query($conn, $query);
            if($res && mysqli_num_rows($res) > 0){
              // $i = 0;
                while ($row = mysqli_fetch_assoc($res)) {
                    $saleQ = "SELECT SUM(tbd.qty) as total_qty, SUM(tbd.freeqty) as total_free_qty FROM tax_billing_details tbd INNER JOIN tax_billing tb ON tbd.tax_bill_id = tb.id WHERE tbd.product_id = '".$row['id']."' AND tb.pharmacy_id = '".$pharmacy_id."' AND tb.financial_id = '".$financial_id."' AND tb.invoice_date <= '".$enddate."' AND tb.invoice_date >= '".$startdate."'";
              
                $saleR = mysqli_query($conn, $saleQ);
                $total_sale_qty_row = ($saleR) ? mysqli_fetch_assoc($saleR) : '';
                
                // print_r($total_sale_qty_row);
                $total_sale_qty = (isset($total_sale_qty_row['total_qty']) && $total_sale_qty_row['total_qty'] != '') ? $total_sale_qty_row['total_qty'] : 0;
                $total_sale_freeqty = (isset($total_sale_qty_row['total_free_qty']) && $total_sale_qty_row['total_free_qty'] != '') ? $total_sale_qty_row['total_free_qty'] : 0;
                   $id = explode(" ", $row['id']);   
                  $row['productsale'] = ($total_sale_qty+$total_sale_freeqty);
                  $datas = getAllProductWithCurrentStock('','','', $id, $enddate);
                 $row['product'] = $datas;
                $sales[] = $row;
              }
              return $sales;
            }
    }



    /* --------------------------CODE FOR SHREYA END----------------------  */
    
     /*  CODE FOR KARTIK START  */
    function update_leger_ob(){
        global $conn;
        $fyear = $_SESSION['auth']['financial'];
        $pharmacy_id = $_SESSION['auth']['pharmacy_id'];
        /*  Lager Get Query Start  */ 
            $legerQ = "SELECT * FROM `ledger_master` WHERE pharmacy_id='".$_SESSION['auth']['pharmacy_id']."'";
            $legerR = mysqli_query($conn,$legerQ);
            //$row_leger = mysqli_fetch_assoc($legerR);
            while($row_leger = mysqli_fetch_assoc($legerR)){
                $finacial_tableQ = "SELECT * FROM `finacial_opening_table` Where leger_id='".$row_leger['id']."' AND pharmacy_id='".$pharmacy_id."' AND finacial_id='".$fyear."'";
                $finacial_tableR = mysqli_query($conn,$finacial_tableQ);
                if(mysqli_num_rows($finacial_tableR) > 0){
                    $update = 1;
                }else{
                    $update = 0;
                }
                $count_data = countRunningBalance($row_leger['id']);
                $opening_balance = (isset($count_data['opening_balance']) && $count_data['opening_balance'] != '') ? $count_data['opening_balance'] : 0;
                $closing_balance = (isset($count_data['running_balance']) && $count_data['running_balance'] != '') ? $count_data['running_balance'] : 0;
                
                if($closing_balance != 0){
                    if($update == 1){
                        mysqli_query($conn,"UPDATE `finacial_opening_table` SET `opening_balance`='".$opening_balance."',`closing_balance`='".$closing_balance."' WHERE finacial_id='".$fyear."' AND leger_id='".$row_leger['id']."' AND pharmacy_id='".$_SESSION['auth']['pharmacy_id']."'");
                    }else{
                        $Insert_findQ = "INSERT INTO `finacial_opening_table`(`pharmacy_id`, `finacial_id`, `leger_id`, `opening_balance`, `closing_balance`, `created`, `createdby`) VALUES ('".$_SESSION['auth']['pharmacy_id']."','".$fyear."','".$row_leger['id']."','".$opening_balance."','".$closing_balance."','".date('Y-m-d H:i:s')."','".$_SESSION['auth']['id']."')";
                        mysqli_query($conn,$Insert_findQ);
                    }
                }
            }
            /*  Lager Get Query End  */
    }

    function year_change_eff(){
        global $conn;
        $fYear = $_SESSION['auth']['financial'];
        $f_dataQ = "SELECT * FROM `financial` Where id < '".$_SESSION['auth']['financial']."' AND pharmacy_id='".$_SESSION['auth']['pharmacy_id']."' LIMIT 1";
        $f_dataR = mysqli_query($conn,$f_dataQ);
        $row_f_data = mysqli_fetch_assoc($f_dataR);
        $previousYear = $row_f_data['id'];
        /*  Lager Get Query Start  */ 
            $legerQ = "SELECT * FROM `ledger_master` WHERE pharmacy_id='".$_SESSION['auth']['pharmacy_id']."'";
            $legerR = mysqli_query($conn,$legerQ);
            //$row_leger = mysqli_fetch_assoc($legerR);
            while($row_leger = mysqli_fetch_assoc($legerR)){
                $getClosing = "SELECT closing_balance FROM finacial_opening_table WHERE finacial_id = '".$previousYear."' AND leger_id = '".$row_leger['id']."'";
                $closingResult = mysqli_query($conn,$getClosing);
                if(mysqli_num_rows($closingResult) > 0){
                    $getClosing = mysqli_fetch_assoc($closingResult);
                    $closing_balance = (isset($getClosing['closing_balance']) && $getClosing['closing_balance'] != '') ? $getClosing['closing_balance'] : 0;
                    if($closing_balance != 0){
                        if($closing_balance > 0){
                            $closing_balance_type = "DB";
                        }else{
                            $closing_balance_type = "CR";
                        }
                        mysqli_query($conn,"UPDATE ledger_master SET opening_balance = ".$closing_balance.",opening_balance_type = '".$closing_balance_type."' WHERE id = ".$row_leger['id']."");
                    }
                }else{
                    $getClosing = "SELECT opening_balance FROM finacial_opening_table WHERE finacial_id = ".$fYear." AND leger_id = ".$row_leger['id']."";
                    $closingResult = mysqli_query($conn,$getClosing);
                    $getClosing = mysqli_fetch_assoc($closingResult);
                    $opening_balance = (isset($getClosing['opening_balance']) && $getClosing['opening_balance'] != '') ? $getClosing['opening_balance'] : 0;
                    if($opening_balance != 0){
                        if($opening_balance > 0){
                            $opening_balance_type = "DB";
                        }else{
                            $opening_balance_type = "CR";
                        }
                        mysqli_query($conn,"UPDATE ledger_master SET opening_balance = ".$opening_balance.",opening_balance_type = '".$opening_balance_type."' WHERE id = ".$row_leger['id']."");
                    }
                }
            }
            /*  Lager Get Query End  */
    }
    
    /*  Sale Remider Date Start  */
    
    function Sale_remider_data(){
        global $conn;
        $pharmacy_id = $_SESSION['auth']['pharmacy_id'];
        $remider_array = array();
        /*  Text Billing Query Start  */
        $tax_billingQ = "SELECT tb.*,lm.name,lm.mobile,lm.email FROM `tax_billing` tb JOIN ledger_master lm ON lm.id = tb.customer_id WHERE tb.pharmacy_id='".$pharmacy_id."' AND tb.remider='1' AND tb.remider_status='0'";
        $tax_billingR = mysqli_query($conn,$tax_billingQ);
        while($row_tax_billing = mysqli_fetch_assoc($tax_billingR)){
            if(date('Y-m-d') == $row_tax_billing['remider_date_update']){
                $remider_array[] = $row_tax_billing;
            }
        }
        return $remider_array;
        /*  Text Billing Query End  */ 
    }
    
    /*  Sale Remider Date END  */
    
    

    /*  Sale Remider Update Start  */
    
    function sale_remider_update(){
        global $conn;
        $pharmacy_id = $_SESSION['auth']['pharmacy_id'];
        $tax_billingQ = "SELECT *, DATE_ADD(remider_date_update, INTERVAL 1 DAY) as update_date FROM `tax_billing` WHERE remider ='1' AND pharmacy_id='".$pharmacy_id."'";
        $tax_billingR = mysqli_query($conn,$tax_billingQ);
        while($row_tax_billing = mysqli_fetch_assoc($tax_billingR)){
            if(date('Y-m-d') == $row_tax_billing['update_date']){
                if($row_tax_billing['time_name'] != "Once"){
                    if($row_tax_billing['time_name'] == "Weekly"){
                        $remider_date_update = date('Y-m-d', strtotime('+1 week', strtotime($row_tax_billing['remider_date_update'])));
                    }
                    if($row_tax_billing['time_name'] == "15 Days"){
                        $remider_date_update = date('Y-m-d', strtotime('+15 days', strtotime($row_tax_billing['remider_date_update'])));
                    }
                    if($row_tax_billing['time_name'] == "Every 1 Month"){
                        $remider_date_update = date('Y-m-d', strtotime('+1 month', strtotime($row_tax_billing['remider_date_update'])));
                    }
                    if($row_tax_billing['time_name'] == "Every 2 Month"){
                        $remider_date_update = date('Y-m-d', strtotime('+2 month', strtotime($row_tax_billing['remider_date_update'])));
                    }
                    if($row_tax_billing['time_name'] == "Every 3 Month"){
                        $remider_date_update = date('Y-m-d', strtotime('+3 month', strtotime($row_tax_billing['remider_date_update'])));
                    }
                    if($row_tax_billing['time_name'] == "Yearly"){
                        $remider_date_update = date('Y-m-d', strtotime('+1 year', strtotime($row_tax_billing['remider_date_update'])));
                    }
                }
                mysqli_query($conn,"UPDATE `tax_billing` SET `remider_date_update`='".$remider_date_update."',`remider_email_sms_status`='0' WHERE id='".$row_tax_billing['id']."' AND pharmacy_id='".$pharmacy_id."'");
            }
        }
    }
    
    /*  Sale Remider Update End  */ 
    /*  Customer ID Get Function Start  */
    function getcustomerID(){
        global $conn;
        $customer_id = '';
        $getcustomerIDQ = "SELECT MAX(customer_id) as max FROM `ledger_master`";
        $getcustomerIDR = mysqli_query($conn, $getcustomerIDQ);
        if($getcustomerIDR){
          $countInvoice = mysqli_num_rows($getcustomerIDR);
          $row = mysqli_fetch_array($getcustomerIDR);
          if($row['max'] !== '' && $row['max'] !== 0 && $row['max'] !== NULL){
            $current_id = $row['max'];
            $sum = $current_id + 1;
            $customer_id = sprintf("%05d", $sum);
          }else{
            $customer_id = sprintf("%05d", 1);
          }
        }

        return $customer_id;
    }
    /*  Customer ID Get Function END  */
    /*  Add financial Wise Qty Start  */
    function addfinacialproductqty(){
        global $conn;
        $pharmacy_id = $_SESSION['auth']['pharmacy_id'];
        $fyear = $_SESSION['auth']['financial'];
        $all_prdoct_data = getAllProductWithCurrentStock();
        foreach ($all_prdoct_data as $key => $value) {
            if(isset($value['currentstock']) && $value['currentstock'] != '' && $value['currentstock'] != 0){
                $finacial_tableQ = "SELECT * FROM `product_qty_finacial_year` Where product_id='".$value['id']."' AND pharmacy_id='".$pharmacy_id."' AND finacial_id='".$fyear."'";
                $finacial_tableR = mysqli_query($conn,$finacial_tableQ);
                if(mysqli_num_rows($finacial_tableR) > 0){
                    $UpdateQ = "UPDATE `product_qty_finacial_year` SET`pharmacy_id`='".$pharmacy_id."',`finacial_id`='".$fyear."',`product_id`='".$value['id']."',`opening_qty`='".$value['opening_qty']."',`closing_qty`='".$value['currentstock']."' WHERE product_id ='".$value['id']."' AND finacial_id='".$fyear."'";
                    mysqli_query($conn,$UpdateQ);
                }else{
                    $Insert_findQ = "INSERT INTO `product_qty_finacial_year`(`pharmacy_id`, `finacial_id`, `product_id`, `opening_qty`, `closing_qty`, `createdby`, `created`) VALUES ('".$pharmacy_id."','".$fyear."','".$value['id']."','".$value['opening_qty']."','".$value['currentstock']."','".$_SESSION['auth']['id']."','".date('Y-m-d H:i:s')."')";
                    mysqli_query($conn,$Insert_findQ);
                }
            }
        }
    }
    /*  Add financial Wise Qty END  */
    /*  Update financial Wise Qty Start  */
    function updatefinacialproductqty(){
        global $conn;
        $fYear = $_SESSION['auth']['financial'];
        $pharmacy_id = $_SESSION['auth']['pharmacy_id'];
        $f_dataQ = "SELECT * FROM `financial` Where id < '".$_SESSION['auth']['financial']."' AND pharmacy_id='".$_SESSION['auth']['pharmacy_id']."' LIMIT 1";
        $f_dataR = mysqli_query($conn,$f_dataQ);
        $row_f_data = mysqli_fetch_assoc($f_dataR);
        $previousYear = $row_f_data['id'];

        $all_prdoct_data = getAllProductWithCurrentStock();

        foreach ($all_prdoct_data as $key => $value) {
            $getClosing = "SELECT closing_qty FROM product_qty_finacial_year WHERE finacial_id = '".$previousYear."' AND product_id = '".$value['id']."' AND pharmacy_id='".$pharmacy_id."'";
            $closingResult = mysqli_query($conn,$getClosing);
            if(mysqli_num_rows($closingResult) > 0){
                $getClosing = mysqli_fetch_assoc($closingResult);
                if(isset($getClosing['closing_qty']) && $getClosing['closing_qty'] != '' && $getClosing['closing_qty'] != 0){
                    $openingQ = "UPDATE product_master SET opening_qty = ".$getClosing['closing_qty']." WHERE id = ".$value['id']." AND pharmacy_id='".$pharmacy_id."'";
                    mysqli_query($conn,$openingQ);
                }
            }else{
                $getClosing = "SELECT opening_qty FROM product_qty_finacial_year WHERE finacial_id = '".$fYear."' AND product_id = '".$value['id']."' AND pharmacy_id='".$pharmacy_id."'";
                $closingResult = mysqli_query($conn,$getClosing);
                $getClosing = mysqli_fetch_assoc($closingResult);
                if(isset($getClosing['opening_qty']) && $getClosing['opening_qty'] != '' && $getClosing['opening_qty'] != 0){
                    $openingQ = "UPDATE product_master SET opening_qty = ".$getClosing['opening_qty']." WHERE id = ".$value['id']." AND pharmacy_id='".$pharmacy_id."'";
                    mysqli_query($conn,$openingQ);
                }
            }
        }
    }
    /*  Update financial Wise Qty END  */
    
    /*  Check Condistion For Cr_days AND Cr_Limit Start  */
    function CheckCr($user_id = null){
        global $conn;
        global $pharmacy_id;
        global $financial_id;
        $limit = 0;

        /*  Lager Data Get Start  */
        $legerQ = "SELECT id, crlimit, crdays FROM `ledger_master` WHERE id='".$user_id."' AND pharmacy_id = '".$pharmacy_id."' AND crlimit > 0";
        $legerR = mysqli_query($conn,$legerQ);
        if($legerR && mysqli_num_rows($legerR) > 0){
            $legerRow = mysqli_fetch_assoc($legerR);
            $cr_limit = (isset($legerRow['crlimit']) && $legerRow['crlimit'] != '') ? $legerRow['crlimit'] : 0;
            $cr_days = (isset($legerRow['crdays']) && $legerRow['crdays'] != '') ? $legerRow['crdays'] : 0;

            $running_data = countRunningBalance($user_id, '','',1);
            $running_balance = (isset($running_data['running_balance']) && $running_data['running_balance'] != '') ? $running_data['running_balance'] : 0;

            if($running_balance > $cr_limit){
                $limit = ($cr_limit-$running_balance);
            }
        }
        return $limit; 
    }
    /*  Check Condistion For Cr_days AND Cr_Limit END  */
    
    
    
     /*  CODE FOR KARTIK END  */
     
    /*--CODE FOR GAUTAM MAKWANA START----*/
    function getUnreadMessage(){
        global $webconn;
        $userid = isset($_SESSION['auth']['id']) ? $_SESSION['auth']['id'] : '';
        $data = [];

        /*---------FOR SINGLE USER CHAT MESSAGE-----------------*/
        $query = "SELECT COUNT(cht.id) as count, rd.send_by, usr.name as send_by_name, usr.profile_pic FROM `userchat` cht INNER JOIN readmessage rd ON cht.id = rd.msg_id INNER JOIN users usr ON rd.send_by = usr.id WHERE cht.groups = 0 AND rd.user_id = '".$userid."' AND rd.flag = 0 GROUP BY rd.send_by";

        $res = mysqli_query($webconn, $query);
        if($res && mysqli_num_rows($res) > 0){
            while ($row = mysqli_fetch_assoc($res)) {
                $row['url'] = '../user_profile/default.jpg';
                if(isset($row['profile_pic']) && $row['profile_pic'] != ''){
                    if(file_exists('../user_profile/'.$row['profile_pic'])){
                        $row['url'] = '../user_profile/'.$row['profile_pic'];
                    }
                }
                $data[] = $row;
            }
        }
        /*---------FOR SINGLE USER CHAT MESSAGE-----------------*/
        
        /*---------FOR GROUP CHAT MESSAGE-----------------*/
        $groupQ = "SELECT gp.id, gp.name FROM groupmembers gpm INNER JOIN groups gp ON gpm.group_id = gp.id WHERE gpm.user_id = '".$userid."' GROUP BY gpm.group_id ORDER BY gp.name";
        $groupR = mysqli_query($webconn, $groupQ);
        if($groupR && mysqli_num_rows($groupR) > 0){
            while ($groupRow = mysqli_fetch_assoc($groupR)) {
                $getMessageQ = "SELECT COUNT(id) as count FROM readmessage WHERE send_by = '".$groupRow['id']."' AND user_id = '".$userid."' AND flag = 0 GROUP BY send_by";
                $getMessageR = mysqli_query($webconn, $getMessageQ);
                if($getMessageR && mysqli_num_rows($getMessageR) > 0){
                    while ($getMessageRow = mysqli_fetch_assoc($getMessageR)) {
                        $arr['count'] = $getMessageRow['count'];
                        $arr['send_by'] = $groupRow['id'];
                        $arr['send_by_name'] = $groupRow['name'];
                        $arr['url'] = '../user_profile/groupdefault.jpg';
                        $data[] = $arr;
                    }
                }
            }
        }
        /*---------FOR GROUP CHAT MESSAGE-----------------*/
        if(!empty($data)){
            $finadata['data'] = $data;
            $finadata['conversation'] = count($data);
            $finadata['messages'] = array_sum(array_column($data, 'count'));
        }
        else{
            $finadata = [];
        }
        return $finadata;
    }
    
    function getSheduleCategory(){
        global $conn;
        $data = [];

        $query = "SELECT * FROM shedule_category ORDER BY id";
        $res = mysqli_query($conn, $query);
        if($res && mysqli_num_rows($res) > 0){
            while ($row = mysqli_fetch_assoc($res)) {
                $data[] = $row;
            }
        }
        return $data;
    }
    
    function getVendorOrderNotification(){
        global $conn;
        $data = [];

        $query = "SELECT orm.id, orm.vendor_id, orm.groups, orm.type, orm.day, orm.date, lgr.name as vendor_name FROM order_reminder orm INNER JOIN ledger_master lgr ON orm.vendor_id = lgr.id WHERE orm.date = '".date('Y-m-d')."' AND orm.status = 0";
        $res = mysqli_query($conn, $query);
        if($res && mysqli_num_rows($res) > 0){
            while ($row = mysqli_fetch_assoc($res)) {

                $subquery = "SELECT COUNT(id) as totalorder, created as order_date FROM orders WHERE vendor_id = '".$row['vendor_id']."' AND groups = '".$row['groups']."' AND type = '".$row['type']."' GROUP BY vendor_id";
                $subres = mysqli_query($conn, $subquery);
                if($subres && mysqli_num_rows($subres) > 0){
                    $subdata = mysqli_fetch_assoc($subres);
                    $row['totalorder'] = (isset($subdata['totalorder']) && $subdata['totalorder'] != '') ? $subdata['totalorder'] : 0;
                    $row['order_date'] = (isset($subdata['order_date']) && $subdata['order_date'] != '') ? date('d/m/Y',strtotime($subdata['order_date'])) : '';
                }
                $data[] = $row;
            }
        }

        return $data;
    }
    
    function getCustomerOrderNotification(){
        global $conn;
        $data = [];

        $query = "SELECT sorm.id, sorm.customer_id, sorm.groups, sorm.day, sorm.date, lgr.name as customer_name FROM sales_order_reminder sorm INNER JOIN ledger_master lgr ON sorm.customer_id = lgr.id WHERE sorm.date = '".date('Y-m-d')."' AND sorm.status = 0";
        $res = mysqli_query($conn, $query);
        if($res && mysqli_num_rows($res) > 0){
            while ($row = mysqli_fetch_assoc($res)) {

                $subquery = "SELECT COUNT(id) as totalorder, created as order_date FROM sales_order WHERE customer_id = '".$row['customer_id']."' AND groups = '".$row['groups']."' GROUP BY customer_id";
                $subres = mysqli_query($conn, $subquery);
                if($subres && mysqli_num_rows($subres) > 0){
                    $subdata = mysqli_fetch_assoc($subres);
                    $row['totalorder'] = (isset($subdata['totalorder']) && $subdata['totalorder'] != '') ? $subdata['totalorder'] : 0;
                    $row['order_date'] = (isset($subdata['order_date']) && $subdata['order_date'] != '') ? date('d/m/Y',strtotime($subdata['order_date'])) : '';
                }
                $data[] = $row;
            }
        }
        return $data;
    }
    
    // This function is used to generate sale invoice number
    // Parameter $bill_type : Cash OR Debit
    function getInvoiceNo($bill_type = null){
        global $conn;
        global $pharmacy_id;
        global $financial_id;
        $invoice_no = '';
        
        $pefixQ = "SELECT id, sale_cash, sale_debit FROM `series_prefix` WHERE pharmacy_id='".$pharmacy_id."'";
        $pefixR = mysqli_query($conn, $pefixQ);
        if($pefixR && mysqli_num_rows($pefixR) > 0){
            $pefixRow = mysqli_fetch_array($pefixR);
            $pefix_row['pefix_name_cash'] = (isset($pefixRow['sale_cash'])) ? $pefixRow['sale_cash'] : '';
            $pefix_row['pefix_name_debit'] = (isset($pefixRow['sale_debit'])) ? $pefixRow['sale_debit'] : '';
        }else{
            $pefix_row['pefix_name_cash'] = '';
            $pefix_row['pefix_name_debit'] = '';
        }
        
        if($bill_type == 'Cash'){
            $query = "SELECT invoice_no FROM tax_billing WHERE bill_type = 'Cash' AND pharmacy_id = '".$pharmacy_id."' AND financial_id = '".$financial_id."' ORDER BY id DESC LIMIT 1";
            $res = mysqli_query($conn, $query);
            if($res){
              $count = mysqli_num_rows($res);
                if($count !== '' && $count !== 0){
                  $row = mysqli_fetch_array($res);
                  $invoice_no = (isset($row['invoice_no'])) ? $row['invoice_no'] : '';
                  if($invoice_no != ''){
                        $getno = preg_replace('/[^0-9]/', '', $invoice_no);
                        $finalno = sprintf("%04d", ($getno+1));
                        $invoice_no = $pefix_row['pefix_name_cash'].''.$finalno;
                  }else{
                        $invoice_no = sprintf("%04d", 1);
                        $invoice_no = $pefix_row['pefix_name_cash'].''.$invoice_no;
                  }
                }else{
                  $invoice_no = sprintf("%04d", 1);
                  $invoice_no = $pefix_row['pefix_name_cash'].''.$invoice_no;
                }
            }
        }else{
            $query = "SELECT invoice_no FROM tax_billing WHERE bill_type = 'Debit' AND pharmacy_id = '".$pharmacy_id."' ORDER BY id DESC LIMIT 1";
            $res = mysqli_query($conn, $query);
            if($res){
              $count = mysqli_num_rows($res);
                if($count !== '' && $count !== 0){
                  $row = mysqli_fetch_array($res);
                  $invoice_no = (isset($row['invoice_no'])) ? $row['invoice_no'] : '';
                  if($invoice_no != ''){
                        $getno = preg_replace('/[^0-9]/', '', $invoice_no);
                        $finalno = sprintf("%04d", ($getno+1));
                        $invoice_no = $pefix_row['pefix_name_debit'].''.$finalno;
                  }else{
                        $invoice_no = sprintf("%04d", 1);
                        $invoice_no = $pefix_row['pefix_name_debit'].''.$invoice_no;
                  }
                }else{
                  $invoice_no = sprintf("%04d", 1);
                  $invoice_no = $pefix_row['pefix_name_debit'].''.$invoice_no;
                }
            }
        }
    
        return $invoice_no;
    }
    
    function getCreditNoteNo(){
        global $conn;
        global $pharmacy_id;
        global $financial_id;
        $invoice_no = '';

        $pefixQ = "SELECT id, credit_note FROM `series_prefix` WHERE pharmacy_id='".$pharmacy_id."'";
        $pefixR = mysqli_query($conn, $pefixQ);
        if($pefixR && mysqli_num_rows($pefixR) > 0){
            $pefixRow = mysqli_fetch_assoc($pefixR);
            $credit_note_prefix = (isset($pefixRow['credit_note']) && $pefixRow['credit_note'] != '') ? $pefixRow['credit_note'] : '';
        }else{
            $credit_note_prefix = '';
        }

        $query = "SELECT credit_note_no FROM sale_return WHERE pharmacy_id = '".$pharmacy_id."' AND financial_id = '".$financial_id."' ORDER BY id DESC LIMIT 1";
        $res = mysqli_query($conn, $query);
        if($res && mysqli_num_rows($res) > 0){
            $row = mysqli_fetch_array($res);
            $invoice_no = (isset($row['credit_note_no'])) ? $row['credit_note_no'] : '';
            if($invoice_no != ''){
                $getno = preg_replace('/[^0-9]/', '', $invoice_no);
                $finalno = sprintf("%04d", ($getno+1));
                $invoice_no = $credit_note_prefix.''.$finalno;
            }else{
                $invoice_no = sprintf("%04d", 1);
                $invoice_no = $credit_note_prefix.''.$invoice_no;
            }
        }else{
            $invoice_no = sprintf("%04d", 1);
            $invoice_no = $credit_note_prefix.''.$invoice_no;
        }

        return $invoice_no;
    }
    
    function getDebitNoteNo(){
        global $conn;
        global $pharmacy_id;
        global $financial_id;
        $invoice_no = '';

        $pefixQ = "SELECT id, debit_note FROM `series_prefix` WHERE pharmacy_id='".$pharmacy_id."'";
        $pefixR = mysqli_query($conn, $pefixQ);
        if($pefixR && mysqli_num_rows($pefixR) > 0){
            $pefixRow = mysqli_fetch_assoc($pefixR);
            $debit_note_prefix = (isset($pefixRow['debit_note']) && $pefixRow['debit_note'] != '') ? $pefixRow['debit_note'] : '';
        }else{
            $debit_note_prefix = '';
        }

        $query = "SELECT debit_note_no FROM purchase_return WHERE pharmacy_id = '".$pharmacy_id."' AND financial_id = '".$financial_id."' ORDER BY id DESC LIMIT 1";
        $res = mysqli_query($conn, $query);
        if($res && mysqli_num_rows($res) > 0){
            $row = mysqli_fetch_array($res);
            $invoice_no = (isset($row['debit_note_no'])) ? $row['debit_note_no'] : '';
            if($invoice_no != ''){
                $getno = preg_replace('/[^0-9]/', '', $invoice_no);
                $finalno = sprintf("%04d", ($getno+1));
                $invoice_no = $debit_note_prefix.''.$finalno;
            }else{
                $invoice_no = sprintf("%04d", 1);
                $invoice_no = $debit_note_prefix.''.$invoice_no;
            }
        }else{
            $invoice_no = sprintf("%04d", 1);
            $invoice_no = $debit_note_prefix.''.$invoice_no;
        }

        return $invoice_no;
    }
    
    function getChallanInvoiceNo($bill_type = null){
        global $conn;
        global $pharmacy_id;
        global $financial_id;
        $invoice_no = '';
        
        $pefixQ = "SELECT id, challan_cash, challan_debit FROM `series_prefix` WHERE pharmacy_id='".$pharmacy_id."'";
        $pefixR = mysqli_query($conn, $pefixQ);
        if($pefixR && mysqli_num_rows($pefixR) > 0){
            $pefixRow = mysqli_fetch_array($pefixR);
            $pefix_row['pefix_name_cash'] = (isset($pefixRow['challan_cash'])) ? $pefixRow['challan_cash'] : '';
            $pefix_row['pefix_name_debit'] = (isset($pefixRow['challan_debit'])) ? $pefixRow['challan_debit'] : '';
        }else{
            $pefix_row['pefix_name_cash'] = '';
            $pefix_row['pefix_name_debit'] = '';
        }
        
        if($bill_type == 'Cash'){
            $query = "SELECT invoice_no FROM delivery_challan WHERE bill_type = 'Cash' AND pharmacy_id = '".$pharmacy_id."' AND financial_id = '".$financial_id."' ORDER BY id DESC LIMIT 1";
            $res = mysqli_query($conn, $query);
            if($res){
              $count = mysqli_num_rows($res);
                if($count !== '' && $count !== 0){
                  $row = mysqli_fetch_array($res);
                  $invoice_no = (isset($row['invoice_no'])) ? $row['invoice_no'] : '';
                  
                  if($invoice_no != ''){
                        $getno = preg_replace('/[^0-9]/', '', $invoice_no);
                        $finalno = sprintf("%04d", ($getno+1));
                        $invoice_no = $pefix_row['pefix_name_cash'].''.$finalno;
                  }else{
                        $invoice_no = sprintf("%04d", 1);
                        $invoice_no = $pefix_row['pefix_name_cash'].''.$invoice_no;
                  }
                }else{
                  $invoice_no = sprintf("%04d", 1);
                  $invoice_no = $pefix_row['pefix_name_cash'].''.$invoice_no;
                }
            }
        }else{
            $query = "SELECT invoice_no FROM delivery_challan WHERE bill_type = 'Debit' AND pharmacy_id = '".$pharmacy_id."' ORDER BY id DESC LIMIT 1";
            $res = mysqli_query($conn, $query);
            if($res){
              $count = mysqli_num_rows($res);
                if($count !== '' && $count !== 0){
                  $row = mysqli_fetch_array($res);
                  $invoice_no = (isset($row['invoice_no'])) ? $row['invoice_no'] : '';
                  
                  if($invoice_no != ''){
                        $getno = preg_replace('/[^0-9]/', '', $invoice_no);
                        $finalno = sprintf("%04d", ($getno+1));
                        $invoice_no = $pefix_row['pefix_name_debit'].''.$finalno;
                  }else{
                        $invoice_no = sprintf("%04d", 1);
                        $invoice_no = $pefix_row['pefix_name_debit'].''.$invoice_no;
                  }
                }else{
                  $invoice_no = sprintf("%04d", 1);
                  $invoice_no = $pefix_row['pefix_name_debit'].''.$invoice_no;
                }
            }
        }
    
        return $invoice_no;
    }
    
    function sale_of_service($bill_type = null){
        global $conn;
        global $pharmacy_id;
        global $financial_id;
        $invoice_no = '';
        
        $pefixQ = "SELECT service_cash, service_debit FROM `series_prefix` WHERE pharmacy_id='".$pharmacy_id."'";
        $pefixR = mysqli_query($conn, $pefixQ);
        if($pefixR && mysqli_num_rows($pefixR) > 0){
            $pefixRow = mysqli_fetch_array($pefixR);
            $pefix_row['pefix_name_cash'] = (isset($pefixRow['service_cash'])) ? $pefixRow['service_cash'] : '';
            $pefix_row['pefix_name_debit'] = (isset($pefixRow['service_debit'])) ? $pefixRow['service_debit'] : '';
        }else{
            $pefix_row['pefix_name_cash'] = '';
            $pefix_row['pefix_name_debit'] = '';
        }
        
        if($bill_type == 'Cash'){
            $query = "SELECT invoice_no FROM sale_of_service WHERE bill_type = 'Cash' AND pharmacy_id = '".$pharmacy_id."' AND financial_id = '".$financial_id."' ORDER BY id DESC LIMIT 1";
            $res = mysqli_query($conn, $query);
            if($res){
              $count = mysqli_num_rows($res);
                if($count !== '' && $count !== 0){
                  $row = mysqli_fetch_array($res);
                  $invoice_no = (isset($row['invoice_no'])) ? $row['invoice_no'] : '';
                  
                  if($invoice_no != ''){
                        $getno = preg_replace('/[^0-9]/', '', $invoice_no);
                        $finalno = sprintf("%04d", ($getno+1));
                        $invoice_no = $pefix_row['pefix_name_cash'].''.$finalno;
                  }else{
                        $invoice_no = sprintf("%04d", 1);
                        $invoice_no = $pefix_row['pefix_name_cash'].''.$invoice_no;
                  }
                }else{
                  $invoice_no = sprintf("%04d", 1);
                  $invoice_no = $pefix_row['pefix_name_cash'].''.$invoice_no;
                }
            }
        }else{
            $query = "SELECT invoice_no FROM sale_of_service WHERE bill_type = 'Debit' AND pharmacy_id = '".$pharmacy_id."' ORDER BY id DESC LIMIT 1";
            $res = mysqli_query($conn, $query);
            if($res){
              $count = mysqli_num_rows($res);
                if($count !== '' && $count !== 0){
                  $row = mysqli_fetch_array($res);
                  $invoice_no = (isset($row['invoice_no'])) ? $row['invoice_no'] : '';
                  
                  if($invoice_no != ''){
                        $getno = preg_replace('/[^0-9]/', '', $invoice_no);
                        $finalno = sprintf("%04d", ($getno+1));
                        $invoice_no = $pefix_row['pefix_name_debit'].''.$finalno;
                  }else{
                        $invoice_no = sprintf("%04d", 1);
                        $invoice_no = $pefix_row['pefix_name_debit'].''.$invoice_no;
                  }
                }else{
                  $invoice_no = sprintf("%04d", 1);
                  $invoice_no = $pefix_row['pefix_name_debit'].''.$invoice_no;
                }
            }
        }
    
        return $invoice_no;
    }
    
    
    // THIS FUNCTION IS USED TO GENERATE INVOICE NO FOR PURCHASE CASH AND DEBIT 
    // PARAMETER : $bill_type : Cash OR Debit

    function getpurchaseinvoiceno($bill_type = null){
        global $conn;
        global $pharmacy_id;
        global $financial_id;
        $invoice_no = '';
        
        $pefixQ = "SELECT id, purchase_cash, purchase_debit FROM `series_prefix` WHERE pharmacy_id='".$pharmacy_id."'";
        $pefixR = mysqli_query($conn, $pefixQ);
        if($pefixR && mysqli_num_rows($pefixR) > 0){
            $pefixRow = mysqli_fetch_array($pefixR);
            $pefix_row['pefix_name_cash'] = (isset($pefixRow['purchase_cash'])) ? $pefixRow['purchase_cash'] : '';
            $pefix_row['pefix_name_debit'] = (isset($pefixRow['purchase_debit'])) ? $pefixRow['purchase_debit'] : '';
        }else{
            $pefix_row['pefix_name_cash'] = '';
            $pefix_row['pefix_name_debit'] = '';
        }
        
        if($bill_type == 'Cash'){
            $query = "SELECT voucher_no FROM purchase WHERE purchase_type = 'Cash' AND pharmacy_id = '".$pharmacy_id."' AND financial_id = '".$financial_id."' ORDER BY id DESC LIMIT 1";
            $res = mysqli_query($conn, $query);
            if($res){
              $count = mysqli_num_rows($res);
                if($count !== '' && $count !== 0){
                  $row = mysqli_fetch_array($res);
                  $invoice_no = (isset($row['voucher_no'])) ? $row['voucher_no'] : '';
                  if($invoice_no != ''){
                        $getno = preg_replace('/[^0-9]/', '', $invoice_no);
                        $finalno = sprintf("%04d", ($getno+1));
                        $invoice_no = $pefix_row['pefix_name_cash'].''.$finalno;
                  }else{
                        $invoice_no = sprintf("%04d", 1);
                        $invoice_no = $pefix_row['pefix_name_cash'].''.$invoice_no;
                  }
                }else{
                  $invoice_no = sprintf("%04d", 1);
                  $invoice_no = $pefix_row['pefix_name_cash'].''.$invoice_no;
                }
            }
        }else{
            $query = "SELECT voucher_no FROM purchase WHERE purchase_type = 'Debit' AND pharmacy_id = '".$pharmacy_id."' ORDER BY id DESC LIMIT 1";
            $res = mysqli_query($conn, $query);
            if($res){
              $count = mysqli_num_rows($res);
                if($count !== '' && $count !== 0){
                  $row = mysqli_fetch_array($res);
                  $invoice_no = (isset($row['voucher_no'])) ? $row['voucher_no'] : '';
                  if($invoice_no != ''){
                        $getno = preg_replace('/[^0-9]/', '', $invoice_no);
                        $finalno = sprintf("%04d", ($getno+1));
                        $invoice_no = $pefix_row['pefix_name_debit'].''.$finalno;
                  }else{
                        $invoice_no = sprintf("%04d", 1);
                        $invoice_no = $pefix_row['pefix_name_debit'].''.$invoice_no;
                  }
                }else{
                  $invoice_no = sprintf("%04d", 1);
                  $invoice_no = $pefix_row['pefix_name_debit'].''.$invoice_no;
                }
            }
        }
    
        return $invoice_no;
    }
    
    
    
    // THIS FUNCTION IS USED TO GENERATE INVOICE NO FOR ACCOUNTING CASH MANAGEMENT
    // PARAMETER : $bill_type : Cash Payments OR Cash Receipts

    function getaccountingcashinvoiceno($bill_type = null){
        global $conn;
        global $pharmacy_id;
        global $financial_id;
        $invoice_no = '';
        
        $pefixQ = "SELECT id, cash_management_cash, cash_management_receipt FROM `series_prefix` WHERE pharmacy_id='".$pharmacy_id."'";
        $pefixR = mysqli_query($conn, $pefixQ);
        if($pefixR && mysqli_num_rows($pefixR) > 0){
            $pefixRow = mysqli_fetch_array($pefixR);
            $pefix_row['pefix_name_cash'] = (isset($pefixRow['cash_management_cash'])) ? $pefixRow['cash_management_cash'] : '';
            $pefix_row['pefix_name_debit'] = (isset($pefixRow['cash_management_receipt'])) ? $pefixRow['cash_management_receipt'] : '';
        }else{
            $pefix_row['pefix_name_cash'] = '';
            $pefix_row['pefix_name_debit'] = '';
        }
        
        if($bill_type == 'cash_payment'){
            $query = "SELECT voucher_no FROM accounting_cash_management WHERE payment_type = 'cash_payment' AND pharmacy_id = '".$pharmacy_id."' AND financial_id = '".$financial_id."' ORDER BY id DESC LIMIT 1";
            $res = mysqli_query($conn, $query);
            if($res){
              $count = mysqli_num_rows($res);
                if($count !== '' && $count !== 0){
                  $row = mysqli_fetch_array($res);
                  $invoice_no = (isset($row['voucher_no'])) ? $row['voucher_no'] : '';
                  if($invoice_no != ''){
                        $getno = preg_replace('/[^0-9]/', '', $invoice_no);
                        $finalno = sprintf("%04d", ($getno+1));
                        $invoice_no = $pefix_row['pefix_name_cash'].''.$finalno;
                  }else{
                        $invoice_no = sprintf("%04d", 1);
                        $invoice_no = $pefix_row['pefix_name_cash'].''.$invoice_no;
                  }
                }else{
                  $invoice_no = sprintf("%04d", 1);
                  $invoice_no = $pefix_row['pefix_name_cash'].''.$invoice_no;
                }
            }
        }else{
            $query = "SELECT voucher_no FROM accounting_cash_management WHERE payment_type = 'cash_receipt' AND pharmacy_id = '".$pharmacy_id."' ORDER BY id DESC LIMIT 1";
            $res = mysqli_query($conn, $query);
            if($res){
              $count = mysqli_num_rows($res);
                if($count !== '' && $count !== 0){
                  $row = mysqli_fetch_array($res);
                  $invoice_no = (isset($row['voucher_no'])) ? $row['voucher_no'] : '';
                  if($invoice_no != ''){
                        $getno = preg_replace('/[^0-9]/', '', $invoice_no);
                        $finalno = sprintf("%04d", ($getno+1));
                        $invoice_no = $pefix_row['pefix_name_debit'].''.$finalno;
                  }else{
                        $invoice_no = sprintf("%04d", 1);
                        $invoice_no = $pefix_row['pefix_name_debit'].''.$invoice_no;
                  }
                }else{
                  $invoice_no = sprintf("%04d", 1);
                  $invoice_no = $pefix_row['pefix_name_debit'].''.$invoice_no;
                }
            }
        }
    
        return $invoice_no;
    }
    
    
    // THIS FUNCTION IS USED TO GENERATE INVOICE NO FOR ACCOUNTING VENDOR PAYMENTS
    function getvendorreceiptno(){
        global $conn;
        global $pharmacy_id;
        global $financial_id;
        $invoice_no = '';

        $pefixQ = "SELECT id, vendor_payments FROM `series_prefix` WHERE pharmacy_id='".$pharmacy_id."'";
        $pefixR = mysqli_query($conn, $pefixQ);
        if($pefixR && mysqli_num_rows($pefixR) > 0){
            $pefixRow = mysqli_fetch_assoc($pefixR);
            $debit_note_prefix = (isset($pefixRow['vendor_payments']) && $pefixRow['vendor_payments'] != '') ? $pefixRow['vendor_payments'] : '';
        }else{
            $debit_note_prefix = '';
        }

        $query = "SELECT vendor_receipt_no FROM accounting_vendor_payment WHERE pharmacy_id = '".$pharmacy_id."' AND financial_id = '".$financial_id."' ORDER BY id DESC LIMIT 1";
        $res = mysqli_query($conn, $query);
        if($res && mysqli_num_rows($res) > 0){
            $row = mysqli_fetch_array($res);
            $invoice_no = (isset($row['vendor_receipt_no'])) ? $row['vendor_receipt_no'] : '';
            if($invoice_no != ''){
                $getno = preg_replace('/[^0-9]/', '', $invoice_no);
                $finalno = sprintf("%04d", ($getno+1));
                $invoice_no = $debit_note_prefix.''.$finalno;
            }else{
                $invoice_no = sprintf("%04d", 1);
                $invoice_no = $debit_note_prefix.''.$invoice_no;
            }
        }else{
            $invoice_no = sprintf("%04d", 1);
            $invoice_no = $debit_note_prefix.''.$invoice_no;
        }

        return $invoice_no;
    }
    
    
    //THIS FUNCTION IS USED TO GENERATE CUSTOMER RECEIPT NO
    function getcustomerreceiptno(){
        global $conn;
        global $pharmacy_id;
        global $financial_id;
        $invoice_no = '';

        $pefixQ = "SELECT id, customer_receipts FROM `series_prefix` WHERE pharmacy_id='".$pharmacy_id."'";
        $pefixR = mysqli_query($conn, $pefixQ);
        if($pefixR && mysqli_num_rows($pefixR) > 0){
            $pefixRow = mysqli_fetch_assoc($pefixR);
            $debit_note_prefix = (isset($pefixRow['customer_receipts']) && $pefixRow['customer_receipts'] != '') ? $pefixRow['customer_receipts'] : '';
        }else{
            $debit_note_prefix = '';
        }

        $query = "SELECT cash_receipt_no FROM cash_receipt WHERE pharmacy_id = '".$pharmacy_id."' AND financial_id = '".$financial_id."' ORDER BY id DESC LIMIT 1";
        $res = mysqli_query($conn, $query);
        if($res && mysqli_num_rows($res) > 0){
            $row = mysqli_fetch_array($res);
            $invoice_no = (isset($row['cash_receipt_no'])) ? $row['cash_receipt_no'] : '';
            if($invoice_no != ''){
                $getno = preg_replace('/[^0-9]/', '', $invoice_no);
                $finalno = sprintf("%04d", ($getno+1));
                $invoice_no = $debit_note_prefix.''.$finalno;
            }else{
                $invoice_no = sprintf("%04d", 1);
                $invoice_no = $debit_note_prefix.''.$invoice_no;
            }
        }else{
            $invoice_no = sprintf("%04d", 1);
            $invoice_no = $debit_note_prefix.''.$invoice_no;
        }

        return $invoice_no;
    }
    
    
    
    //THIS FUNCTION IS USED TO ACCOUNTING CHEQUE VOUCHER NO FOR PAYMENT AND RECEIPT
    function getaccountingchequevoucher($bill_type = null){
        global $conn;
        global $pharmacy_id;
        global $financial_id;
        $invoice_no = '';
        
        $pefixQ = "SELECT id, accounting_bank_cheque, accounting_bank_cheque_receipt FROM `series_prefix` WHERE pharmacy_id='".$pharmacy_id."'";
        $pefixR = mysqli_query($conn, $pefixQ);
        if($pefixR && mysqli_num_rows($pefixR) > 0){
            $pefixRow = mysqli_fetch_array($pefixR);
            $pefix_row['pefix_name_cash'] = (isset($pefixRow['accounting_bank_cheque'])) ? $pefixRow['accounting_bank_cheque'] : '';
            $pefix_row['pefix_name_debit'] = (isset($pefixRow['accounting_bank_cheque_receipt'])) ? $pefixRow['accounting_bank_cheque_receipt'] : '';
        }else{
            $pefix_row['pefix_name_cash'] = '';
            $pefix_row['pefix_name_debit'] = '';
        }
        
        if($bill_type == 'payment'){
            $query = "SELECT voucher_no FROM accounting_cheque WHERE voucher_type = 'payment' AND pharmacy_id = '".$pharmacy_id."' AND financial_id = '".$financial_id."' ORDER BY id DESC LIMIT 1";
            $res = mysqli_query($conn, $query);
            if($res){
              $count = mysqli_num_rows($res);
                if($count !== '' && $count !== 0){
                  $row = mysqli_fetch_array($res);
                  $invoice_no = (isset($row['voucher_no'])) ? $row['voucher_no'] : '';
                  if($invoice_no != ''){
                        $getno = preg_replace('/[^0-9]/', '', $invoice_no);
                        $finalno = sprintf("%04d", ($getno+1));
                        $invoice_no = $pefix_row['pefix_name_cash'].''.$finalno;
                  }else{
                        $invoice_no = sprintf("%04d", 1);
                        $invoice_no = $pefix_row['pefix_name_cash'].''.$invoice_no;
                  }
                }else{
                  $invoice_no = sprintf("%04d", 1);
                  $invoice_no = $pefix_row['pefix_name_cash'].''.$invoice_no;
                }
            }
        }else{
            $query = "SELECT voucher_no FROM accounting_cheque WHERE voucher_type = 'receipt' AND pharmacy_id = '".$pharmacy_id."' ORDER BY id DESC LIMIT 1";
            $res = mysqli_query($conn, $query);
            if($res){
              $count = mysqli_num_rows($res);
                if($count !== '' && $count !== 0){
                  $row = mysqli_fetch_array($res);
                  $invoice_no = (isset($row['voucher_no'])) ? $row['voucher_no'] : '';
                  if($invoice_no != ''){
                        $getno = preg_replace('/[^0-9]/', '', $invoice_no);
                        $finalno = sprintf("%04d", ($getno+1));
                        $invoice_no = $pefix_row['pefix_name_debit'].''.$finalno;
                  }else{
                        $invoice_no = sprintf("%04d", 1);
                        $invoice_no = $pefix_row['pefix_name_debit'].''.$invoice_no;
                  }
                }else{
                  $invoice_no = sprintf("%04d", 1);
                  $invoice_no = $pefix_row['pefix_name_debit'].''.$invoice_no;
                }
            }
        }
    
        return $invoice_no;
    }
    
    
    //THIS FUNCTION IS USED TO ADD PAYMENT VOUCHER NUMBER
    // PARAMETER : $bill_type = tax, tax_free
    function getpaymentvoucher($bill_type = null){
        global $conn;
        global $pharmacy_id;
        global $financial_id;
        $invoice_no = '';
        
        $pefixQ = "SELECT id, payment_tax, payment_tax_free FROM `series_prefix` WHERE pharmacy_id='".$pharmacy_id."'";
        $pefixR = mysqli_query($conn, $pefixQ);
        if($pefixR && mysqli_num_rows($pefixR) > 0){
            $pefixRow = mysqli_fetch_array($pefixR);
            $pefix_row['pefix_name_cash'] = (isset($pefixRow['payment_tax'])) ? $pefixRow['payment_tax'] : '';
            $pefix_row['pefix_name_debit'] = (isset($pefixRow['payment_tax_free'])) ? $pefixRow['payment_tax_free'] : '';
        }else{
            $pefix_row['pefix_name_cash'] = '';
            $pefix_row['pefix_name_debit'] = '';
        }
        
        if($bill_type == 'tax'){
            $query = "SELECT voucher_no FROM add_payment_voucher WHERE voucher_type = 'tax' AND pharmacy_id = '".$pharmacy_id."' AND financial_id = '".$financial_id."' ORDER BY id DESC LIMIT 1";
            $res = mysqli_query($conn, $query);
            if($res){
              $count = mysqli_num_rows($res);
                if($count !== '' && $count !== 0){
                  $row = mysqli_fetch_array($res);
                  $invoice_no = (isset($row['voucher_no'])) ? $row['voucher_no'] : '';
                  if($invoice_no != ''){
                        $getno = preg_replace('/[^0-9]/', '', $invoice_no);
                        $finalno = sprintf("%04d", ($getno+1));
                        $invoice_no = $pefix_row['pefix_name_cash'].''.$finalno;
                  }else{
                        $invoice_no = sprintf("%04d", 1);
                        $invoice_no = $pefix_row['pefix_name_cash'].''.$invoice_no;
                  }
                }else{
                  $invoice_no = sprintf("%04d", 1);
                  $invoice_no = $pefix_row['pefix_name_cash'].''.$invoice_no;
                }
            }
        }else{
            $query = "SELECT voucher_no FROM add_payment_voucher WHERE voucher_type = 'tax_free' AND pharmacy_id = '".$pharmacy_id."' ORDER BY id DESC LIMIT 1";
            $res = mysqli_query($conn, $query);
            if($res){
              $count = mysqli_num_rows($res);
                if($count !== '' && $count !== 0){
                  $row = mysqli_fetch_array($res);
                  $invoice_no = (isset($row['voucher_no'])) ? $row['voucher_no'] : '';
                  if($invoice_no != ''){
                        $getno = preg_replace('/[^0-9]/', '', $invoice_no);
                        $finalno = sprintf("%04d", ($getno+1));
                        $invoice_no = $pefix_row['pefix_name_debit'].''.$finalno;
                  }else{
                        $invoice_no = sprintf("%04d", 1);
                        $invoice_no = $pefix_row['pefix_name_debit'].''.$invoice_no;
                  }
                }else{
                  $invoice_no = sprintf("%04d", 1);
                  $invoice_no = $pefix_row['pefix_name_debit'].''.$invoice_no;
                }
            }
        }
    
        return $invoice_no;
    }
    
    
    //THIS FUNCTION USED FOR COURIER PAYMENT VOUCHER NUMBER GENERATE
    function getcourierpaymentvoucherno(){
        global $conn;
        global $pharmacy_id;
        global $financial_id;
        $invoice_no = '';

        $pefixQ = "SELECT id, courier_payment FROM `series_prefix` WHERE pharmacy_id='".$pharmacy_id."'";
        $pefixR = mysqli_query($conn, $pefixQ);
        if($pefixR && mysqli_num_rows($pefixR) > 0){
            $pefixRow = mysqli_fetch_assoc($pefixR);
            $debit_note_prefix = (isset($pefixRow['courier_payment']) && $pefixRow['courier_payment'] != '') ? $pefixRow['courier_payment'] : '';
        }else{
            $debit_note_prefix = '';
        }

        $query = "SELECT voucher_no FROM courier_payment WHERE pharmacy_id = '".$pharmacy_id."' AND financial_id = '".$financial_id."' ORDER BY id DESC LIMIT 1";
        $res = mysqli_query($conn, $query);
        if($res && mysqli_num_rows($res) > 0){
            $row = mysqli_fetch_array($res);
            $invoice_no = (isset($row['voucher_no'])) ? $row['voucher_no'] : '';
            if($invoice_no != ''){
                $getno = preg_replace('/[^0-9]/', '', $invoice_no);
                $finalno = sprintf("%04d", ($getno+1));
                $invoice_no = $debit_note_prefix.''.$finalno;
            }else{
                $invoice_no = sprintf("%04d", 1);
                $invoice_no = $debit_note_prefix.''.$invoice_no;
            }
        }else{
            $invoice_no = sprintf("%04d", 1);
            $invoice_no = $debit_note_prefix.''.$invoice_no;
        }

        return $invoice_no;
    }

    
    //THIS FUNCTION IS USED TO QUOTATION GENERATE VOUCHER NUMBER
    // PARAMETER : $bill_type = Cash, Debit
    function getquotationvoucher($bill_type = null){
        global $conn;
        global $pharmacy_id;
        global $financial_id;
        $invoice_no = '';
        
        $pefixQ = "SELECT id, quotation_cash, quotation_debit FROM `series_prefix` WHERE pharmacy_id='".$pharmacy_id."'";
        $pefixR = mysqli_query($conn, $pefixQ);
        if($pefixR && mysqli_num_rows($pefixR) > 0){
            $pefixRow = mysqli_fetch_array($pefixR);
            $pefix_row['pefix_name_cash'] = (isset($pefixRow['quotation_cash'])) ? $pefixRow['quotation_cash'] : '';
            $pefix_row['pefix_name_debit'] = (isset($pefixRow['quotation_debit'])) ? $pefixRow['quotation_debit'] : '';
        }else{
            $pefix_row['pefix_name_cash'] = '';
            $pefix_row['pefix_name_debit'] = '';
        }
        
        if($bill_type == 'Cash'){
            $query = "SELECT invoice_no FROM quotation WHERE bill_type = 'Cash' AND pharmacy_id = '".$pharmacy_id."' AND financial_id = '".$financial_id."' ORDER BY id DESC LIMIT 1";
            $res = mysqli_query($conn, $query);
            if($res){
              $count = mysqli_num_rows($res);
                if($count !== '' && $count !== 0){
                  $row = mysqli_fetch_array($res);
                  $invoice_no = (isset($row['invoice_no'])) ? $row['invoice_no'] : '';
                  if($invoice_no != ''){
                        $getno = preg_replace('/[^0-9]/', '', $invoice_no);
                        $finalno = sprintf("%04d", ($getno+1));
                        $invoice_no = $pefix_row['pefix_name_cash'].''.$finalno;
                  }else{
                        $invoice_no = sprintf("%04d", 1);
                        $invoice_no = $pefix_row['pefix_name_cash'].''.$invoice_no;
                  }
                }else{
                  $invoice_no = sprintf("%04d", 1);
                  $invoice_no = $pefix_row['pefix_name_cash'].''.$invoice_no;
                }
            }
        }else{
            $query = "SELECT invoice_no FROM quotation WHERE bill_type = 'Debit' AND pharmacy_id = '".$pharmacy_id."' ORDER BY id DESC LIMIT 1";
            $res = mysqli_query($conn, $query);
            if($res){
              $count = mysqli_num_rows($res);
                if($count !== '' && $count !== 0){
                  $row = mysqli_fetch_array($res);
                  $invoice_no = (isset($row['invoice_no'])) ? $row['invoice_no'] : '';
                  if($invoice_no != ''){
                        $getno = preg_replace('/[^0-9]/', '', $invoice_no);
                        $finalno = sprintf("%04d", ($getno+1));
                        $invoice_no = $pefix_row['pefix_name_debit'].''.$finalno;
                  }else{
                        $invoice_no = sprintf("%04d", 1);
                        $invoice_no = $pefix_row['pefix_name_debit'].''.$invoice_no;
                  }
                }else{
                  $invoice_no = sprintf("%04d", 1);
                  $invoice_no = $pefix_row['pefix_name_debit'].''.$invoice_no;
                }
            }
        }
    
        return $invoice_no;
    }
    
    
    // THIS FUNCTION IS USED TO STOCK DETAIL QUANTITY REPORT
    // PARAMETER : $from : from date, $to : to date, $company_code : Company id which added in product master
    function stockdetailReport($from = null, $to = '', $company_code = null){
        global  $conn;
        global $pharmacy_id;
        $data = [];


          $query = "SELECT id, product_name, batch_no, unit, opening_qty FROM product_master WHERE pharmacy_id = '".$pharmacy_id."' ";
          if(isset($company_code) && $company_code != ''){
            $query .= "AND company_code = '".$company_code."' ";
          }
          $query .= "ORDER BY product_name";

          $res = mysqli_query($conn, $query);

          if($res && mysqli_num_rows($res) > 0){
            while ($row = mysqli_fetch_assoc($res)) {
              $data[] = $row;
            }
          }

          if(isset($data) && !empty($data)){
            foreach ($data as $key => $value) {

              /*--------SALE COUNTING START------------*/
              $saleqty = 0;
              $saleQ = "SELECT SUM(tbd.qty) as saleqty FROM tax_billing_details tbd INNER JOIN tax_billing tb ON tbd.tax_bill_id = tb.id WHERE tbd.product_id = '".$value['id']."' ";
              if(isset($from) && $from != ''){
                $saleQ .= "AND tb.invoice_date >= '".$from."' AND tb.invoice_date <= '".$to."' ";
              }
              $saleQ .= "GROUP BY tbd.product_id";
              $saleR = mysqli_query($conn, $saleQ);
              if($saleR && mysqli_num_rows($saleR) > 0){
                $saleRow = mysqli_fetch_assoc($saleR);
                $saleqty = (isset($saleRow['saleqty']) && $saleRow['saleqty'] != '') ? $saleRow['saleqty'] : 0;
              }
              $data[$key]['saleqty'] = $saleqty;
              /*--------SALE COUNTING END------------*/

              /*--------SALE RETURN COUNTING START------------*/
              $salereturn = 0;
              $salereturnQ = "SELECT SUM(qty) as salereturn FROM sale_return WHERE product_id = '".$value['id']."' ";
              if(isset($from) && $from != ''){
                $salereturnQ .= "AND return_date >= '".$from."' AND return_date <= '".$to."' ";
              }
              $salereturnQ .= "GROUP BY product_id";
              $salereturnR = mysqli_query($conn, $salereturnQ);
              if($salereturnR && mysqli_num_rows($salereturnR) > 0){
                $salereturnRow = mysqli_fetch_assoc($salereturnR);
                $salereturn = (isset($salereturnRow['salereturn']) && $salereturnRow['salereturn'] != '') ? $salereturnRow['salereturn'] : 0;
              }
              $data[$key]['salereturn'] = $salereturn;
              /*--------SALE RETURN COUNTING END------------*/

              /*--------PURCHASE COUNTING START------------*/
              $purchaseqty = 0;
              $purchaseQ = "SELECT SUM(pd.qty*pd.qty_ratio) as purchaseqty FROM purchase_details pd INNER JOIN purchase p ON pd.purchase_id = p.id WHERE pd.product_id = '".$value['id']."' ";
              if(isset($from) && $from != ''){
                $purchaseQ .= "AND p.invoice_date >= '".$from."' AND p.invoice_date <= '".$to."' ";
              }
              $purchaseQ .= "GROUP BY pd.product_id";
              $purchaseR = mysqli_query($conn, $purchaseQ);
              if($purchaseR && mysqli_num_rows($purchaseR) > 0){
                $purchaseRow = mysqli_fetch_assoc($purchaseR);
                $purchaseqty = (isset($purchaseRow['purchaseqty']) && $purchaseRow['purchaseqty'] != '') ? $purchaseRow['purchaseqty'] : 0;
              }
              $data[$key]['purchaseqty'] = $purchaseqty;
              /*--------PURCHASE COUNTING END------------*/

              /*--------PURCHASE RETURN COUNTING START------------*/
              $purchasereturn = 0;
              $purchasereturnQ = "SELECT SUM(prd.qty) as purchasereturn FROM purchase_return_detail prd INNER JOIN purchase_return pr ON prd.pr_id = pr.id WHERE prd.product_id = '".$value['id']."' ";
              if(isset($from) && $from != ''){
                $purchasereturnQ .= "AND pr.bill_date >= '".$from."' AND pr.bill_date <= '".$to."' ";
              }
              $purchasereturnQ .= "GROUP BY prd.product_id";
              $purchasereturnR = mysqli_query($conn, $purchasereturnQ);
              if($purchasereturnR && mysqli_num_rows($purchasereturnR) > 0){
                $purchasereturnRow = mysqli_fetch_assoc($purchasereturnR);
                $purchasereturn = (isset($purchasereturnRow['purchasereturn']) && $purchasereturnRow['purchasereturn'] != '') ? $purchasereturnRow['purchasereturn'] : 0;
              }
              $data[$key]['purchasereturn'] = $purchasereturn;
              /*--------PURCHASE RETURN COUNTING END------------*/

            }
          }
          
        return $data;
    }
    
    // THIS FUNCTION IS USED TO STOCK DETAIL PRICE REPORT
    // PARAMETER : $from : from date, $to : to date, $company_code : Company id which added in product master
    function stockdetailPriceReport($from = null, $to = '', $company_code = null){
        global  $conn;
        global $pharmacy_id;
        $data = [];


          $query = "SELECT id, product_name, batch_no, unit, opening_qty, opening_stock FROM product_master WHERE pharmacy_id = '".$pharmacy_id."' ";
          if(isset($company_code) && $company_code != ''){
            $query .= "AND company_code = '".$company_code."' ";
          }
          $query .= "ORDER BY product_name";

          $res = mysqli_query($conn, $query);

          if($res && mysqli_num_rows($res) > 0){
            while ($row = mysqli_fetch_assoc($res)) {
              $data[] = $row;
            }
          }

          if(isset($data) && !empty($data)){
            foreach ($data as $key => $value) {

              /*--------SALE COUNTING START------------*/
              $saleAmount = 0;
              $saleQ = "SELECT SUM(tbd.totalamount) as saleAmount FROM tax_billing_details tbd INNER JOIN tax_billing tb ON tbd.tax_bill_id = tb.id WHERE tbd.product_id = '".$value['id']."' ";
              if(isset($from) && $from != ''){
                $saleQ .= "AND tb.invoice_date >= '".$from."' AND tb.invoice_date <= '".$to."' ";
              }
              $saleQ .= "GROUP BY tbd.product_id";
              $saleR = mysqli_query($conn, $saleQ);
              if($saleR && mysqli_num_rows($saleR) > 0){
                $saleRow = mysqli_fetch_assoc($saleR);
                $saleAmount = (isset($saleRow['saleAmount']) && $saleRow['saleAmount'] != '') ? $saleRow['saleAmount'] : 0;
              }
              $data[$key]['saleAmount'] = $saleAmount;
              /*--------SALE COUNTING END------------*/

              /*--------SALE RETURN COUNTING START------------*/
              $saleReturnAmount = 0;
              $salereturnQ = "SELECT SUM((amount)-(amount*gst/100)) as saleReturnAmount FROM sale_return WHERE product_id = '".$value['id']."' ";
              if(isset($from) && $from != ''){
                $salereturnQ .= "AND return_date >= '".$from."' AND return_date <= '".$to."' ";
              }
              $salereturnQ .= "GROUP BY product_id";
              $salereturnR = mysqli_query($conn, $salereturnQ);
              if($salereturnR && mysqli_num_rows($salereturnR) > 0){
                $salereturnRow = mysqli_fetch_assoc($salereturnR);
                $saleReturnAmount = (isset($salereturnRow['saleReturnAmount']) && $salereturnRow['saleReturnAmount'] != '') ? $salereturnRow['saleReturnAmount'] : 0;
              }
              $data[$key]['saleReturnAmount'] = $saleReturnAmount;
              /*--------SALE RETURN COUNTING END------------*/

              /*--------PURCHASE COUNTING START------------*/
              $purchaseAmount = 0;
              $purchaseQ = "SELECT SUM((pd.qty*pd.qty_ratio)*(pd.f_rate)) as purchaseAmount FROM purchase_details pd INNER JOIN purchase p ON pd.purchase_id = p.id WHERE pd.product_id = '".$value['id']."' ";
              if(isset($from) && $from != ''){
                $purchaseQ .= "AND p.invoice_date >= '".$from."' AND p.invoice_date <= '".$to."' ";
              }
              $purchaseQ .= "GROUP BY pd.product_id";
              $purchaseR = mysqli_query($conn, $purchaseQ);
              if($purchaseR && mysqli_num_rows($purchaseR) > 0){
                $purchaseRow = mysqli_fetch_assoc($purchaseR);
                $purchaseAmount = (isset($purchaseRow['purchaseAmount']) && $purchaseRow['purchaseAmount'] != '') ? $purchaseRow['purchaseAmount'] : 0;
              }
              $data[$key]['purchaseAmount'] = $purchaseAmount;
              /*--------PURCHASE COUNTING END------------*/

              /*--------PURCHASE RETURN COUNTING START------------*/
              $purchaseReturnAmount = 0;
              $purchasereturnQ = "SELECT SUM(prd.amount) as purchaseReturnAmount FROM purchase_return_detail prd INNER JOIN purchase_return pr ON prd.pr_id = pr.id WHERE prd.product_id = '".$value['id']."' ";
              if(isset($from) && $from != ''){
                $purchasereturnQ .= "AND pr.bill_date >= '".$from."' AND pr.bill_date <= '".$to."' ";
              }
              $purchasereturnQ .= "GROUP BY prd.product_id";
              $purchasereturnR = mysqli_query($conn, $purchasereturnQ);
              if($purchasereturnR && mysqli_num_rows($purchasereturnR) > 0){
                $purchasereturnRow = mysqli_fetch_assoc($purchasereturnR);
                $purchaseReturnAmount = (isset($purchasereturnRow['purchaseReturnAmount']) && $purchasereturnRow['purchaseReturnAmount'] != '') ? $purchasereturnRow['purchaseReturnAmount'] : 0;
              }
              $data[$key]['purchaseReturnAmount'] = $purchaseReturnAmount;
              /*--------PURCHASE RETURN COUNTING END------------*/

            }
          }
        return $data;
    }
    
    // THIS FUNCTION IS USED TO ITEM REGISTRATION REPORT
    /*----------------PARAMETER-----------------------*/
    // $from : from date
    // $to : to date
    // $item : product id
    // $type : 0 =Item Registration In Details & 1 = Item Registration Sale Only & 2 = Item Registration Purchase Only & 3 = Item Registration Batch Wise
    // $item_type : 0 = all & 1 = batch wise
    // $item_id : product batch id for product id
    /*----------------PARAMETER-----------------------*/
    function itemRegistrationReport($from = null, $to = null, $item = null, $type = null, $item_type = null, $item_id = null){
        global  $conn;
        global $pharmacy_id;

        $finaldata = [];

        if($item != '' && $type != ''){
            $product_id = [];

            if($type == 0 || $item_type == 0){
                $findproductQ = "SELECT id, product_name FROM product_master WHERE id = '".$item."' AND pharmacy_id = '".$pharmacy_id."' LIMIT  1";
                $findproductR = mysqli_query($conn, $findproductQ);
                if($findproductR && mysqli_num_rows($findproductR) > 0){
                    $findproductRow = mysqli_fetch_assoc($findproductR);
                    $product_name = (isset($findproductRow['product_name'])) ? $findproductRow['product_name'] : '';
                    
                    $findSubProductQ = "SELECT id FROM product_master WHERE product_name = '".$product_name."' AND pharmacy_id = '".$pharmacy_id."'";
                    $findSubProductR = mysqli_query($conn, $findSubProductQ);
                    if($findSubProductR && mysqli_num_rows($findSubProductR)){
                        while ($findSubProductRow = mysqli_fetch_assoc($findSubProductR)) {
                            $product_id[] = $findSubProductRow['id'];
                        }
                    }
                }
            }else{
                $product_id[] = $item_id;
            }
            
            /*------------------------------PRODUCT DESC START-----------------------------------*/
            if(isset($product_id) && !empty($product_id)){
                $allid  = implode(',', $product_id);
                $productQ = "SELECT id, product_name, SUM(opening_qty) as total_opening_qty FROM product_master WHERE id IN(".$allid.") GROUP BY product_name";
                $productR = mysqli_query($conn, $productQ);
                if($productR && mysqli_num_rows($productR) > 0){
                    
                    $finaldata['product'] = mysqli_fetch_assoc($productR);
                    $finaldata['data'] = [];
                    
                    if(isset($type) && $type != 1){
                        /*--------------------PURCHASE START---------------------------*/
                          $purchaseQ = "SELECT p.id, p.vouchar_date, p.voucher_no, SUM(pd.qty*pd.qty_ratio) as purchaseqty, SUM((pd.qty*pd.qty_ratio)*(pd.f_rate)) as purchaseAmount, pm.id as product_id, pm.product_name, pm.mrp, pm.batch_no, lg.name as party_name FROM purchase_details pd INNER JOIN purchase p ON pd.purchase_id = p.id LEFT JOIN product_master pm ON pd.product_id = pm.id LEFT JOIN ledger_master lg ON p.vendor = lg.id WHERE pd.product_id IN(".$allid.") ";
                          if(isset($from) && $from != ''){
                            $purchaseQ .= "AND p.invoice_date >= '".$from."' AND p.invoice_date <= '".$to."' ";
                          }
                          $purchaseQ .= "GROUP BY pd.product_id, pd.purchase_id";

                          $purchaseR = mysqli_query($conn, $purchaseQ);
                          if($purchaseR && mysqli_num_rows($purchaseR) > 0){
                            while ($purchaseRow = mysqli_fetch_assoc($purchaseR)) {
                                $tmp1['id'] = $purchaseRow['id'];
                                $tmp1['billNo'] = (isset($purchaseRow['voucher_no'])) ? $purchaseRow['voucher_no'] : '';
                                $tmp1['date'] = (isset($purchaseRow['vouchar_date'])) ? $purchaseRow['vouchar_date'] : '';
                                $tmp1['challan'] = '';
                                $tmp1['mrp'] = (isset($purchaseRow['mrp']) && $purchaseRow['mrp'] != '') ? $purchaseRow['mrp'] : 0;
                                $tmp1['batchNo'] = (isset($purchaseRow['batch_no'])) ? $purchaseRow['batch_no'] : '';
                                $tmp1['purchaseQty'] = (isset($purchaseRow['purchaseqty']) && $purchaseRow['purchaseqty'] != '') ? $purchaseRow['purchaseqty'] : 0;
                                $tmp1['purchaseReturnQty'] = '';
                                $tmp1['saleQty'] = '';
                                $tmp1['saleReturnQty'] = '';

                                $tmp1['amount'] = (isset($purchaseRow['purchaseAmount']) && $purchaseRow['purchaseAmount'] != '') ? $purchaseRow['purchaseAmount'] : 0;

                                $tmp1['partyName'] = (isset($purchaseRow['party_name'])) ? $purchaseRow['party_name'] : '';
                                $tmp1['type'] = 'purchase';
                                $finaldata['data'][] = $tmp1;
                            }
                          }
                        /*--------------------PURCHASE END-----------------------------*/

                        /*--------------------PURCHASE RETURN START-------------------------*/
                          $purchasereturnQ = "SELECT pr.id, pr.bill_no, pr.bill_date, SUM(prd.qty) as purchasereturn, SUM(prd.amount) as purchaseReturnAmount, pm.id as product_id, pm.product_name, pm.mrp, pm.batch_no, lg.name as party_name FROM purchase_return_detail prd INNER JOIN purchase_return pr ON prd.pr_id = pr.id LEFT JOIN product_master pm ON prd.product_id = pm.id LEFT JOIN ledger_master lg ON pr.vendor_id = lg.id WHERE prd.product_id IN(".$allid.") ";
                          if(isset($from) && $from != ''){
                            $purchasereturnQ .= "AND pr.bill_date >= '".$from."' AND pr.bill_date <= '".$to."' ";
                          }
                          $purchasereturnQ .= "GROUP BY prd.product_id, prd.pr_id";
                          
                          $purchasereturnR = mysqli_query($conn, $purchasereturnQ);
                          if($purchasereturnR && mysqli_num_rows($purchasereturnR) > 0){
                            while ($purchasereturnRow = mysqli_fetch_assoc($purchasereturnR)) {
                                $tmp2['id'] = $purchasereturnRow['id'];
                                $tmp2['billNo'] = (isset($purchasereturnRow['bill_no'])) ? $purchasereturnRow['bill_no'] : '';
                                $tmp2['date'] = (isset($purchasereturnRow['bill_date'])) ? $purchasereturnRow['bill_date'] : '';
                                $tmp2['challan'] = '';
                                $tmp2['mrp'] = (isset($purchasereturnRow['mrp']) && $purchasereturnRow['mrp'] != '') ? $purchasereturnRow['mrp'] : 0;
                                $tmp2['batchNo'] = (isset($purchasereturnRow['batch_no'])) ? $purchasereturnRow['batch_no'] : '';
                                $tmp2['purchaseQty'] = '';
                                $tmp2['purchaseReturnQty'] = (isset($purchasereturnRow['purchasereturn']) && $purchasereturnRow['purchasereturn'] != '') ? $purchasereturnRow['purchasereturn'] : 0;
                                $tmp2['saleQty'] = '';
                                $tmp2['saleReturnQty'] = '';

                                
                                $tmp2['amount'] = (isset($purchasereturnRow['purchaseReturnAmount']) && $purchasereturnRow['purchaseReturnAmount'] != '') ? $purchasereturnRow['purchaseReturnAmount'] : 0;
                                

                                $tmp2['partyName'] = (isset($purchasereturnRow['party_name'])) ? $purchasereturnRow['party_name'] : '';
                                $tmp2['type'] = 'purchaseReturn';
                                $finaldata['data'][] = $tmp2;
                            }
                          }
                        /*--------------------PURCHASE RETURN END---------------------------*/
                    }

                    if(isset($type) && $type != 2){
                        /*----------------------------SALE START-----------------------------*/
                          $saleQ = "SELECT tb.id, tb.invoice_no, tb.invoice_date,  SUM(tbd.qty) as saleqty, SUM(tbd.totalamount) as saleAmount, pm.id as product_id, pm.product_name, pm.mrp, pm.batch_no, lg.name as party_name FROM tax_billing_details tbd INNER JOIN tax_billing tb ON tbd.tax_bill_id = tb.id LEFT JOIN product_master pm ON tbd.product_id = pm.id LEFT JOIN ledger_master lg ON tb.customer_id = lg.id WHERE tbd.product_id IN(".$allid.") ";
                          if(isset($from) && $from != ''){
                            $saleQ .= "AND tb.invoice_date >= '".$from."' AND tb.invoice_date <= '".$to."' ";
                          }
                          $saleQ .= "GROUP BY tbd.product_id, tbd.tax_bill_id";
                          $saleR = mysqli_query($conn, $saleQ);
                          if($saleR && mysqli_num_rows($saleR) > 0){
                            while ($saleRow = mysqli_fetch_assoc($saleR)) {
                                $tmp3['id'] = $saleRow['id'];
                                $tmp3['billNo'] = (isset($saleRow['invoice_no'])) ? $saleRow['invoice_no'] : '';
                                $tmp3['date'] = (isset($saleRow['invoice_date'])) ? $saleRow['invoice_date'] : '';
                                $tmp3['challan'] = '';
                                $tmp3['mrp'] = (isset($saleRow['mrp']) && $saleRow['mrp'] != '') ? $saleRow['mrp'] : 0;
                                $tmp3['batchNo'] = (isset($saleRow['batch_no'])) ? $saleRow['batch_no'] : '';
                                $tmp3['purchaseQty'] = '';
                                $tmp3['purchaseReturnQty'] = '';
                                $tmp3['saleQty'] = (isset($saleRow['saleqty']) && $saleRow['saleqty'] != '') ? $saleRow['saleqty'] : 0;
                                $tmp3['saleReturnQty'] = '';

                             
                                $tmp3['amount'] = (isset($saleRow['saleAmount']) && $saleRow['saleAmount'] != '') ? $saleRow['saleAmount'] : 0;

                                $tmp3['partyName'] = (isset($saleRow['party_name'])) ? $saleRow['party_name'] : '';
                                $tmp3['type'] = 'sale';
                                $finaldata['data'][] = $tmp3;
                            }
                          }
                        /*----------------------------SALE END-------------------------------*/

                        /*----------------------------SALE RETURN START-----------------------------*/
                          $salereturnQ = "SELECT sr.id, tb.invoice_date, tb.invoice_no, SUM(sr.qty)  as salereturn, SUM((sr.amount)-(sr.amount*sr.gst/100)) as saleReturnAmount, pm.id as product_id, pm.product_name, pm.mrp, pm.batch_no, lg.name as party_name FROM sale_return sr LEFT JOIN tax_billing tb ON sr.tax_bill_id = tb.id LEFT JOIN product_master pm ON sr.product_id = pm.id LEFT JOIN ledger_master lg ON sr.customer_id = lg.id WHERE sr.product_id IN(".$allid.") ";
                          if(isset($from) && $from != ''){
                            $salereturnQ .= "AND sr.return_date >= '".$from."' AND sr.return_date <= '".$to."' ";
                          }
                          $salereturnQ .= "GROUP BY sr.product_id, sr.tax_bill_id, sr.customer_id";
                          $salereturnR = mysqli_query($conn, $salereturnQ);
                          if($salereturnR && mysqli_num_rows($salereturnR) > 0){
                            while ($salereturnRow = mysqli_fetch_assoc($salereturnR)) {
                                $tmp4['id'] = $salereturnRow['id'];
                                $tmp4['billNo'] = (isset($salereturnRow['invoice_no'])) ? $salereturnRow['invoice_no'] : '';
                                $tmp4['date'] = (isset($salereturnRow['invoice_date'])) ? $salereturnRow['invoice_date'] : '';
                                $tmp4['challan'] = '';
                                $tmp4['mrp'] = (isset($salereturnRow['mrp']) && $salereturnRow['mrp'] != '') ? $salereturnRow['mrp'] : 0;
                                $tmp4['batchNo'] = (isset($salereturnRow['batch_no'])) ? $salereturnRow['batch_no'] : '';
                                $tmp4['purchaseQty'] = '';
                                $tmp4['purchaseReturnQty'] = '';
                                $tmp4['saleQty'] = '';
                                $tmp4['saleReturnQty'] = (isset($salereturnRow['salereturn']) && $salereturnRow['salereturn'] != '') ? $salereturnRow['salereturn'] : 0;

                                $tmp4['amount'] = (isset($salereturnRow['saleReturnAmount']) && $salereturnRow['saleReturnAmount'] != '') ? $salereturnRow['saleReturnAmount'] : 0;

                                $tmp4['partyName'] = (isset($salereturnRow['party_name'])) ? $salereturnRow['party_name'] : '';
                                $tmp4['type'] = 'saleReturn';
                                $finaldata['data'][]= $tmp4;
                            }
                          }
                        /*----------------------------SALE RETURN END-------------------------------*/
                    }
                }
            }
            /*------------------------------PRODUCT DESC END-----------------------------------*/
        }
        
        if(isset($finaldata['data']) && !empty($finaldata['data'])){
            function date_compare($a, $b){
                $t1 = strtotime($a['date']);
                $t2 = strtotime($b['date']);
                return $t1 - $t2;
            }
            usort($finaldata['data'], 'date_compare');
        }
        
        return $finaldata;
    }
    
    /*------THIS FUNCTION IS USED TO GET DOCTOR PURCHASE REPORT-------*/
    function doctorPurchaseReport($from = null, $to = null, $doctor = null){
        global $conn;
        global $pharmacy_id;
        $data = [];

        if(isset($doctor) && $doctor != ''){
            $query = "SELECT p.id, p.invoice_date, p.invoice_no, p.purchase_type, p.total_total as amount, lg.name as vendor FROM purchase p LEFT JOIN ledger_master lg ON p.vendor = lg.id WHERE p.doctor = '".$doctor."' AND p.pharmacy_id = '".$pharmacy_id."' AND p.cancel = 1 ";
            if((isset($from) && $from != '') && (isset($to) && $to != '')){
                $query .= "AND p.invoice_date >= '".$from."' AND p.invoice_date <= '".$to."' ";
            }
            $query .= " ORDER BY p.invoice_date ASC";
            $res = mysqli_query($conn, $query);
            if($res && mysqli_num_rows($res) > 0){
                while ($row = mysqli_fetch_assoc($res)) {
                    $data['detail'][] = $row;
                }
            }
            $data['running_balance'] = runningBalanceForDoctorPurchase($from, $to, $doctor);
        }

        return $data;
    }
    
    function runningBalanceForDoctorPurchase($from = null, $to = null, $doctor = null){
        global $conn;
        global $pharmacy_id;
        $runningBalance = 0;
        $totaldebit = 0;
        $totalcash = 0;
        if(isset($doctor) && $doctor != ''){
            $debitQ = "SELECT SUM(total_total) as total_debit FROM purchase WHERE doctor = '".$doctor."' AND pharmacy_id = '".$pharmacy_id."' AND purchase_type = 'Debit' AND cancel = 1 ";
            if((isset($from) && $from != '') && (isset($to) && $to != '')){
                $debitQ .= "AND invoice_date >= '".$from."' AND invoice_date <= '".$to."' ";
            }
            $debitQ .= "GROUP BY doctor";
            $debitR = mysqli_query($conn, $debitQ);
            if($debitR && mysqli_num_rows($debitR) > 0){
                $debitRow = mysqli_fetch_assoc($debitR);
                $totaldebit = (isset($debitRow['total_debit']) && $debitRow['total_debit'] != '') ? $debitRow['total_debit'] : 0;
            }
            
            $cashQ = "SELECT SUM(total_total) as total_cash FROM purchase WHERE doctor = '".$doctor."' AND pharmacy_id = '".$pharmacy_id."' AND purchase_type = 'Cash' AND cancel = 1 ";
            if((isset($from) && $from != '') && (isset($to) && $to != '')){
                $cashQ .= "AND invoice_date >= '".$from."' AND invoice_date <= '".$to."' ";
            }
            $cashQ .= "GROUP BY doctor";
            $cashR = mysqli_query($conn, $cashQ);
            if($cashR && mysqli_num_rows($cashR) > 0){
                $cashRow = mysqli_fetch_assoc($cashR);
                $totalcash = (isset($cashRow['total_cash']) && $cashRow['total_cash'] != '') ? $cashRow['total_cash'] : 0;
            }
        }
        
        return ($totaldebit-$totalcash);
    }
    
    // THIS FUNCTION IS USED TO DELETE LEDGER - 31-10-2018
    function deleteLedger($id = null){
        global $conn;
        $data['status'] = 0;
        $data['message'] = 'Record Delete Fail! Try Again.';
        
        if(isset($id) && $id != ''){
            $is_delete = 0;
            $is_ledger = [];
            
            /*-----------------PURCHASE START--------------------*/
            $purchaseQ = "SELECT id FROM purchase WHERE vendor = '".$id."'";
            $purchaseR = mysqli_query($conn, $purchaseQ);
            if($purchaseR && mysqli_num_rows($purchaseR) > 0){
                $is_delete = 1;
                $is_ledger[] = 'Purchase';
            }
            /*-----------------PURCHASE END--------------------*/
            
            /*-----------------PURCHASE RETURN START--------------------*/
            $purchaseReturnQ = "SELECT id FROM purchase_return WHERE vendor_id = '".$id."'";
            $purchaseReturnR = mysqli_query($conn, $purchaseReturnQ);
            if($purchaseReturnR && mysqli_num_rows($purchaseReturnR) > 0){
                $is_delete = 1;
                $is_ledger[] = 'Purchase Return';
            }
            /*-----------------PURCHASE RETURN END--------------------*/
            
            /*-----------------BY VENDOR AND PRODUCT START--------------------*/
            $byVendorQ = "SELECT id FROM orders WHERE vendor_id = '".$id."'";
            $byVendorR = mysqli_query($conn, $byVendorQ);
            if($byVendorR && mysqli_num_rows($byVendorR) > 0){
                $is_delete = 1;
                $is_ledger[] = 'Vendor Order';
            }
            /*-----------------BY VENDOR AND PRODUCT END--------------------*/
            
            /*-----------------SALE START--------------------*/
            $saleQ = "SELECT id FROM tax_billing WHERE customer_id = '".$id."'";
            $saleR = mysqli_query($conn, $saleQ);
            if($saleR && mysqli_num_rows($saleR) > 0){
                $is_delete = 1;
                $is_ledger[] = 'Sale';
            }
            /*-----------------SALE END--------------------*/
            
            /*-----------------SALE RETURN START--------------------*/
            $saleReturnQ = "SELECT id FROM sale_return WHERE customer_id = '".$id."'";
            $saleReturnR = mysqli_query($conn, $saleReturnQ);
            if($saleReturnR && mysqli_num_rows($saleReturnR) > 0){
                $is_delete = 1;
                $is_ledger[] = 'Sale Return';
            }
            /*-----------------SALE RETURN END--------------------*/
            
            /*-----------------SALE ORDER START--------------------*/
            $saleOrderQ = "SELECT id FROM sales_order WHERE customer_id = '".$id."'";
            $saleOrderR = mysqli_query($conn, $saleOrderQ);
            if($saleOrderR && mysqli_num_rows($saleOrderR) > 0){
                $is_delete = 1;
                $is_ledger[] = 'Sale Order';
            }
            /*-----------------SALE ORDER END--------------------*/
            
            /*-----------------SALE ESTIMATE START--------------------*/
            $saleEstimateQ = "SELECT id FROM sales_estimate WHERE customer_id = '".$id."'";
            $saleEstimateR = mysqli_query($conn, $saleEstimateQ);
            if($saleEstimateR && mysqli_num_rows($saleEstimateR) > 0){
                $is_delete = 1;
                $is_ledger[] = 'Sale Estimate';
            }
            /*-----------------SALE ESTIMATE END--------------------*/
            
            /*-----------------SALE TEMPLATE START--------------------*/
            $saleTemplateQ = "SELECT id FROM sales_estimate WHERE customer_id = '".$id."'";
            $saleTemplateR = mysqli_query($conn, $saleTemplateQ);
            if($saleTemplateR && mysqli_num_rows($saleTemplateR) > 0){
                $is_delete = 1;
                $is_ledger[] = 'Sale Template';
            }
            /*-----------------SALE TEMPLATE END--------------------*/
            
            /*-----------------CASH MANAGEMENT START--------------------*/
            $cashQ = "SELECT id FROM accounting_cash_management WHERE perticular = '".$id."'";
            $cashR = mysqli_query($conn, $cashQ);
            if($cashR && mysqli_num_rows($cashR) > 0){
                $is_delete = 1;
                $is_ledger[] = 'Transaction Cash';
            }
            /*-----------------CASH MANAGEMENT END--------------------*/
            
            /*-----------------CUSTOMER RECEIPT START--------------------*/
            $customerReceiptQ = "SELECT id FROM cash_receipt WHERE customer = '".$id."'";
            $customerReceiptR = mysqli_query($conn, $customerReceiptQ);
            if($customerReceiptR && mysqli_num_rows($customerReceiptR) > 0){
                $is_delete = 1;
                $is_ledger[] = 'Transaction Customer Receipt';
            }
            /*-----------------CUSTOMER RECEIPT END--------------------*/
            
            /*-----------------CHEQUE START--------------------*/
            $chequeQ = "SELECT id FROM accounting_cheque WHERE perticular = '".$id."'";
            $chequeR = mysqli_query($conn, $chequeQ);
            if($chequeR && mysqli_num_rows($chequeR) > 0){
                $is_delete = 1;
                $is_ledger[] = 'Transaction Cheque';
            }
            /*-----------------CHEQUE END--------------------*/
            
            /*-----------------VENDOR PAYMENT START--------------------*/
            $vendorPaymentQ = "SELECT id FROM accounting_vendor_payment WHERE vendor = '".$id."'";
            $vendorPaymentR = mysqli_query($conn, $vendorPaymentQ);
            if($vendorPaymentR && mysqli_num_rows($vendorPaymentR) > 0){
                $is_delete = 1;
                $is_ledger[] = 'Transaction Vendor Payment';
            }
            /*-----------------VENDOR PAYMENT END--------------------*/
            
            /*-----------------JOURNAL VOUCHER START--------------------*/
            $journalVoucherQ = "SELECT id FROM journal_vouchar_details WHERE particular = '".$id."'";
            $journalVoucherR = mysqli_query($conn, $journalVoucherQ);
            if($journalVoucherR && mysqli_num_rows($journalVoucherR) > 0){
                $is_delete = 1;
                $is_ledger[] = 'Transaction Journal Voucher';
            }
            /*-----------------JOURNAL VOUCHER END--------------------*/
            
            if($is_delete == 0){
                $deleteLedgerQ = "DELETE FROM ledger_master WHERE id = '".$id."'";
                $deleteLedgerR = mysqli_query($conn, $deleteLedgerQ);
                if($deleteLedgerR){
                    $data['status'] = 1;
                    $data['message'] = 'Record Delete Succcessfully.';
                }
            }else{
                $entryIn = implode(", ",$is_ledger);
                $data['status'] = 0;
                $data['message'] = 'Record Delete Fail! Already Entry In <b>'.$entryIn.'</b>';
            }
        }
        
        return $data;
    }
    
    // This function is get today sale 05-11-2018
    // $type = debit or cash $is_ihis = 0 = general customer and 1 = ihis customer
    function getTodaySaleLedger($type = null, $is_ihis = 0){
        global $conn;
        global $pharmacy_id;
        global $financial_id;
        $data = [];

            $query = "SELECT tb.id, tb.invoice_date, tb.invoice_no, tb.bill_type, tb.final_amount, lg.name as customer_name, dp.name as doctor_name FROM tax_billing tb LEFT JOIN ledger_master lg ON tb.customer_id = lg.id LEFT JOIN doctor_profile dp ON tb.doctor = dp.id WHERE tb.pharmacy_id = '".$pharmacy_id."' AND tb.financial_id = '".$financial_id."' AND DATE_FORMAT(tb.created,'%Y-%m-%d') = '".date('Y-m-d')."' ";
            if(isset($type) && $type != ''){
                $query .= "AND tb.bill_type = '".$type."' ";
            }
            if(isset($is_ihis)){
                $query .= "AND tb.is_ihis = '".$is_ihis."' ";
            }
            $query .= "ORDER BY tb.created";
            
            $res = mysqli_query($conn, $query);
            if($res && mysqli_num_rows($res) > 0){
                while ($row = mysqli_fetch_assoc($res)) {
                    $data[] = $row;
                }
            }
        return $data;
    }
    /*--CODE FOR GAUTAM MAKWANA END----*/
    
    function getProductNo(){
        global $conn;
        global $pharmacy_id;
        $query = "SELECT `product_code` FROM `product_master` WHERE pharmacy_id = '".$pharmacy_id."'  ORDER BY id DESC LIMIT 1";
        $res = mysqli_query($conn, $query);
        if($res){
            $count = mysqli_num_rows($res);
            if($count !== '' && $count !== 0){
                $row = mysqli_fetch_array($res);
                $product_no = (isset($row['product_code'])) ? $row['product_code'] : '';
                $product_no = $product_no + 1;
                $product_no = sprintf("%05d", $product_no);
            } else {
                $product_no = sprintf("%05d", 1);
            }
        }
        
        return $product_no;
    }
    
    
    
    function purchase_report($from = null,$to = null,$bill_type = null){
        global $conn;
        $pharmacy_id = $_SESSION['auth']['pharmacy_id'];
        $purchase_data = array();
        if(isset($bill_type) && $bill_type != '' && $bill_type == "avg_price"){
          $avgQ = "SELECT SUM(pd.rate) as total_rate, COUNT(pd.product_id) as product_count,pd.product_id, (SUM(pd.rate)/COUNT(pd.product_id)) As A_v,pm.* FROM `purchase_details` pd JOIN purchase p ON p.id = pd.purchase_id JOIN product_master pm ON pd.product_id = pm.id WHERE p.invoice_date >= '".$from."' AND p.invoice_date <= '".$to."' AND p.pharmacy_id = '".$pharmacy_id."' GROUP By pd.product_id";
          $iteam_codeR = mysqli_query($conn,$avgQ);
        }

        if(isset($bill_type) && $bill_type != '' && $bill_type == "original_price"){
          $originalQ = "SELECT pm.*,pd.rate FROM `purchase_details` pd JOIN purchase p ON p.id = pd.purchase_id JOIN product_master pm ON pd.product_id = pm.id WHERE p.invoice_date >= '".$from."' AND p.invoice_date <= '".$to."' AND p.pharmacy_id = '".$pharmacy_id."'";
          $iteam_codeR = mysqli_query($conn,$originalQ);
        }

        if(isset($bill_type) && $bill_type != '' && $bill_type == "last_price"){
          $lastQ = "SELECT pd.product_id,pd.rate,pm.* FROM purchase_details pd JOIN purchase p ON pd.purchase_id = p.id JOIN product_master pm ON pm.id = pd.product_id WHERE pd.id IN (SELECT MAX(id) FROM purchase_details GROUP BY product_id) AND p.invoice_date >= '".$from."' AND p.invoice_date <= '".$to."' AND p.pharmacy_id = '".$pharmacy_id."'";
          $iteam_codeR = mysqli_query($conn,$lastQ);
        }

        while($iteam_data = mysqli_fetch_assoc($iteam_codeR)){
            $purchase_data[] = $iteam_data;
        }

        return $purchase_data;
    }
    
    function IpdPrescriptionData($today = null){
        global $ihis_conn;
        $IpdPrescriptionData = array();
        $ihis_firm_id = ihis_firm_id;
        
        /*  IPD Prescription Start  */ 
        if($today == "1"){
           $ipdQ = "SELECT ipd.group_id, ipd.firm_id as ihis_firm_id, ipd.user_id as ihis_user_id, ipd.ipd_id as ihis_ipd_id, ipd.treatment_by as ihis_treatment_by, ipd.patient_id as ihis_patient_id, ipd.followup_id as ihis_followup_id, ipd.is_pharmacy_bill, ps.patient_id,ps.email,ps.mobile_no , ps.city_id,ps.patient_opd_id,CONCAT(u.fname,' ',u.lname) as doctor_name, u.id as doctor_id, u.mobile_no as doctor_mobile, CONCAT(ps.fname,' ',ps.lname) as p_name, ps.id as patient_primary_id,ipd.created_at as date FROM ipd_prescription_details ipd LEFT JOIN patient_master ps ON ps.id = ipd.patient_id LEFT JOIN users u ON u.id = ipd.user_id WHERE ipd.firm_id = '".$ihis_firm_id."' AND CAST(ipd.created_at as DATE) >= '".date('Y-m-d')."' AND CAST(ipd.created_at as DATE) <= '".date('Y-m-d')."' GROUP BY ipd.group_id"; 
        }else{
           $ipdQ = "SELECT ipd.group_id, ipd.firm_id as ihis_firm_id, ipd.user_id as ihis_user_id, ipd.ipd_id as ihis_ipd_id, ipd.treatment_by as ihis_treatment_by, ipd.patient_id as ihis_patient_id, ipd.followup_id as ihis_followup_id, ipd.is_pharmacy_bill, ps.patient_id,ps.email,ps.mobile_no , ps.city_id,ps.patient_opd_id,CONCAT(u.fname,' ',u.lname) as doctor_name, u.id as doctor_id, u.mobile_no as doctor_mobile, CONCAT(ps.fname,' ',ps.lname) as p_name, ps.id as patient_primary_id,ipd.created_at as date  FROM ipd_prescription_details ipd LEFT JOIN patient_master ps ON ps.id = ipd.patient_id LEFT JOIN users u ON u.id = ipd.user_id WHERE ipd.firm_id = '".$ihis_firm_id."' GROUP BY ipd.group_id";    
        }
        $ipdR = mysqli_query($ihis_conn,$ipdQ);
        while($iteam_data = mysqli_fetch_assoc($ipdR)){
            $iteam_data['type'] = 'IPD';
            $IpdPrescriptionData[] = $iteam_data;
        }
        /*  IPD Prescription END  */
        
        /*  OPD Prescription Start  */ 
        if($today == "1"){
            $opdQ = "SELECT opd.group_id, opd.firm_id as ihis_firm_id, opd.user_id as ihis_user_id, opd.patient_id as ihis_patient_id, opd.followup_id as ihis_followup_id, opd.is_pharmacy_bill, pm.patient_id, pm.mobile_no, pm.email,pm.register_type, pm.city_id, pm.patient_opd_id, CONCAT(u.fname,' ',u.lname) as doctor_name, u.id as doctor_id, u.mobile_no as doctor_mobile, CONCAT(pm.fname,' ',pm.lname) as p_name, pm.id as patient_primary_id, opd.created_at as date, pf.register_type as followup_register_type, pf.infertility_register_type  FROM opd_prescription_details opd LEFT JOIN patient_master pm ON opd.patient_id = pm.id LEFT JOIN users u ON opd.user_id = u.id LEFT JOIN patient_followup pf ON opd.followup_id = pf.id  WHERE opd.firm_id = '".$ihis_firm_id."' AND CAST(opd.created_at as DATE) >= '".date('Y-m-d')."' AND CAST(opd.created_at as DATE) <= '".date('Y-m-d')."' GROUP BY opd.group_id";
        }else{
            $opdQ = "SELECT opd.group_id, opd.firm_id as ihis_firm_id, opd.user_id as ihis_user_id, opd.patient_id as ihis_patient_id, opd.followup_id as ihis_followup_id, opd.is_pharmacy_bill, pm.patient_id, pm.mobile_no, pm.email,pm.register_type, pm.city_id, pm.patient_opd_id, CONCAT(u.fname,' ',u.lname) as doctor_name, u.id as doctor_id, u.mobile_no as doctor_mobile, CONCAT(pm.fname,' ',pm.lname) as p_name, pm.id as patient_primary_id, opd.created_at as date, pf.register_type as followup_register_type, pf.infertility_register_type  FROM opd_prescription_details opd LEFT JOIN patient_master pm ON opd.patient_id = pm.id LEFT JOIN users u ON opd.user_id = u.id LEFT JOIN patient_followup pf ON opd.followup_id = pf.id  WHERE opd.firm_id = '".$ihis_firm_id."' GROUP BY opd.group_id";
        }
        
        $opdR = mysqli_query($ihis_conn, $opdQ);
        if($opdR && mysqli_num_rows($opdR) > 0){
            while($opdRow = mysqli_fetch_assoc($opdR)){
                if($opdRow['ihis_followup_id'] == 0){
                    $opdRow['register_type'] = $opdRow['register_type'];
                    $opdRow['infertility_register_type'] = '';
                }else{
                    $opdRow['register_type'] = $opdRow['followup_register_type'];
                    $opdRow['infertility_register_type'] = $opdRow['infertility_register_type'];
                }
                $opdRow['type'] = 'OPD';
                $IpdPrescriptionData[] = $opdRow;
            }
        }
        /*  OPD Prescription END  */
        
        /*  ICU Prescription Start  */
        if($today == "1"){
           $icuQ = "SELECT itp.group_id, itp.firm_id as ihis_firm_id, itp.user_id as ihis_user_id, itp.patient_id as ihis_patient_id, itp.follow_id as ihis_followup_id,itp.is_pharmacy_bill, pm.patient_id, pm.mobile_no, pm.email, pm.city_id, pm.patient_opd_id, CONCAT(u.fname,' ',u.lname) as doctor_name, u.id as doctor_id, u.mobile_no as doctor_mobile, CONCAT(pm.fname,' ',pm.lname) as p_name, pm.id as patient_primary_id, itp.created_at as date FROM icu_treatment_prescription itp LEFT JOIN patient_master pm ON itp.patient_id = pm.id LEFT JOIN users u ON itp.user_id = u.id WHERE itp.firm_id = '".$ihis_firm_id."' AND CAST(itp.created_at as DATE) >= '".date('Y-m-d')."' AND CAST(itp.created_at as DATE) <= '".date('Y-m-d')."' GROUP BY itp.group_id";
        }else{
           $icuQ = "SELECT itp.group_id, itp.firm_id as ihis_firm_id, itp.user_id as ihis_user_id, itp.patient_id as ihis_patient_id, itp.follow_id as ihis_followup_id,itp.is_pharmacy_bill, pm.patient_id, pm.mobile_no, pm.email, pm.city_id, pm.patient_opd_id, CONCAT(u.fname,' ',u.lname) as doctor_name, u.id as doctor_id, u.mobile_no as doctor_mobile, CONCAT(pm.fname,' ',pm.lname) as p_name, pm.id as patient_primary_id, itp.created_at as date FROM icu_treatment_prescription itp LEFT JOIN patient_master pm ON itp.patient_id = pm.id LEFT JOIN users u ON itp.user_id = u.id WHERE itp.firm_id = '".$ihis_firm_id."' GROUP BY itp.group_id"; 
        }
        
        $icuR = mysqli_query($ihis_conn, $icuQ);
        if($icuR && mysqli_num_rows($icuR) > 0){
            while($icuRow = mysqli_fetch_assoc($icuR)){
                $icuRow['type'] = 'ICU';
                $IpdPrescriptionData[] = $icuRow;
            }
        }
        /*  ICU Prescription END  */
        
        /*  OT Prescription Start  */
        if($today == "1"){
            $otQ = "SELECT otp.group_id, otp.firm_id as ihis_firm_id, otp.user_id as ihis_user_id, otp.patient_id as ihis_patient_id, otp.follow_id as ihis_followup_id,otp.is_pharmacy_bill, pm.patient_id, pm.mobile_no, pm.email, pm.city_id, pm.patient_opd_id, CONCAT(u.fname,' ',u.lname) as doctor_name, u.id as doctor_id, u.mobile_no as doctor_mobile, CONCAT(pm.fname,' ',pm.lname) as p_name, pm.id as patient_primary_id, otp.created_at as date FROM ot_prescription otp LEFT JOIN patient_master pm ON otp.patient_id = pm.id LEFT JOIN users u ON otp.user_id = u.id WHERE otp.firm_id = '".$ihis_firm_id."' AND CAST(otp.created_at as DATE) >= '".date('Y-m-d')."' AND CAST(otp.created_at as DATE) <= '".date('Y-m-d')."' GROUP BY otp.group_id";
        }else{
            $otQ = "SELECT otp.group_id, otp.firm_id as ihis_firm_id, otp.user_id as ihis_user_id, otp.patient_id as ihis_patient_id, otp.follow_id as ihis_followup_id,otp.is_pharmacy_bill, pm.patient_id, pm.mobile_no, pm.email, pm.city_id, pm.patient_opd_id, CONCAT(u.fname,' ',u.lname) as doctor_name, u.id as doctor_id, u.mobile_no as doctor_mobile, CONCAT(pm.fname,' ',pm.lname) as p_name, pm.id as patient_primary_id, otp.created_at as date FROM ot_prescription otp LEFT JOIN patient_master pm ON otp.patient_id = pm.id LEFT JOIN users u ON otp.user_id = u.id WHERE otp.firm_id = '".$ihis_firm_id."' GROUP BY otp.group_id";
        }
        
        $otR = mysqli_query($ihis_conn, $otQ);
        if($otR && mysqli_num_rows($otR) > 0){
            while($otRow = mysqli_fetch_assoc($otR)){
                $otRow['type'] = 'OT';
                $IpdPrescriptionData[] = $otRow;
            }
        }
        /*  OT Prescription END  */
        
        if(!empty($IpdPrescriptionData)){
            function date_compare($a, $b){
                $t1 = strtotime($a['date']);
                $t2 = strtotime($b['date']);
                return $t1 - $t2;
            }    
            
            usort($IpdPrescriptionData, 'date_compare');
        }
        return $IpdPrescriptionData;
    }
    
    function ihiscustomerLedger($customer_id = null, $from = null, $to = null, $type_by= null){
        
        $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
        $financial_id = (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';
        global $conn;
        
        if($type_by == "0"){
            $type_byQ =  "SELECT id FROM `ledger_master` WHERE financial_id ='".$financial_id."' AND pharmacy_id='".$pharmacy_id."' AND is_ihis='1'";
            $type_byR = mysqli_query($conn, $type_byQ);
            while($type_byRow = mysqli_fetch_assoc($type_byR)){
                $type_by_customer[] = $type_byRow['id'];
            }
            $all_customer_id = implode(",",$type_by_customer);
        }else{
            $customer_id = $customer_id;
        }
        

        $data = [];
        $opening_balance = 0;
        if((isset($pharmacy_id) && $pharmacy_id != '')){

            /*----------------------------------------------------COUNT OPENING BALANCE START-------------------------------------------------------*/
            $opening_balance = 0;
            if($type_by == "0"){
                $customerQ =  "SELECT id, name, opening_balance, opening_balance_type FROM ledger_master WHERE id IN (".$all_customer_id.") AND pharmacy_id = '".$pharmacy_id."' LIMIT 1";
            }else{
                $customerQ =  "SELECT id, name, opening_balance, opening_balance_type FROM ledger_master WHERE id = '".$customer_id."' AND pharmacy_id = '".$pharmacy_id."' LIMIT 1";
            }
            $customerR = mysqli_query($conn, $customerQ);
            if($customerR && mysqli_num_rows($customerR) > 0){
                $customerRow = mysqli_fetch_assoc($customerR);
                $opening_balance = (isset($customerRow['opening_balance']) && $customerRow['opening_balance'] != '') ? $customerRow['opening_balance'] : 0;
                $opening_balance = (isset($customerRow['opening_balance_type']) && $customerRow['opening_balance_type'] == 'DB') ? $opening_balance : -$opening_balance;
            }
            /*----------------------------------------------------COUNT OPENING BALANCE END-------------------------------------------------------*/

            /*----------------------------------------------------COUNT DABIT TAX BILL START-------------------------------------------------------*/
            if($type_by == "0"){
                $billQ =  "SELECT id, invoice_date, invoice_no, final_amount FROM tax_billing WHERE pharmacy_id = '".$pharmacy_id."' AND financial_id = '".$financial_id."' AND customer_id IN (".$all_customer_id.") AND bill_type = 'Debit'";
            }else{
                $billQ =  "SELECT id, invoice_date, invoice_no, final_amount FROM tax_billing WHERE pharmacy_id = '".$pharmacy_id."' AND financial_id = '".$financial_id."' AND customer_id = '".$customer_id."' AND bill_type = 'Debit'";   
            }
            
            if((isset($from) && $from != '') && (isset($to) && $to != '')){
              $billQ .= " AND invoice_date >= '".$from."' AND invoice_date <= '".$to."'";
            }
            $billR = mysqli_query($conn, $billQ);
            if($billR && mysqli_num_rows($billR) > 0){
                while ($billRow = mysqli_fetch_assoc($billR)) {
                  $billarr['type'] = 'taxBill';
                  $billarr['id'] = $billRow['id'];
                  $billarr['date'] = (isset($billRow['invoice_date'])) ? $billRow['invoice_date'] : '';
                  $billarr['sr_no'] = (isset($billRow['invoice_no'])) ? $billRow['invoice_no'] : '';
                  $billarr['narration'] = (isset($billRow['invoice_no'])) ? 'Tax Bill | '.$billRow['invoice_no'] : 'Tax Bill';
                  $billarr['debit'] = (isset($billRow['final_amount']) && $billRow['final_amount'] != '') ? $billRow['final_amount'] : 0;
                  $billarr['credit'] = '';
                  $billarr['url'] = 'sales-tax-billing.php?id='.$billRow['id'];
                  $data[] = $billarr;
                }
            }
            /*----------------------------------------------------COUNT DABIT TAX BILL END-------------------------------------------------------*/

            /*----------------------------------------------------COUNT CUSTOMER RECEIPT START-------------------------------------------------------*/
            if($type_by =="0"){
                $cashReceiptQ =  "SELECT id, cash_receipt_no, payment_date, payment_mode, amount FROM cash_receipt WHERE pharmacy_id = '".$pharmacy_id."' AND financial_id = '".$financial_id."' AND customer IN (".$all_customer_id.")";
            }else{
                $cashReceiptQ =  "SELECT id, cash_receipt_no, payment_date, payment_mode, amount FROM cash_receipt WHERE pharmacy_id = '".$pharmacy_id."' AND financial_id = '".$financial_id."' AND customer = '".$customer_id."'";
            }
            
            if((isset($from) && $from != '') && (isset($to) && $to != '')){
              $cashReceiptQ .= " AND payment_date >= '".$from."' AND payment_date <= '".$to."'";
            }
            $cashReceiptR = mysqli_query($conn, $cashReceiptQ);
            if($cashReceiptR && mysqli_num_rows($cashReceiptR) > 0){
                while ($cashReceiptRow = mysqli_fetch_assoc($cashReceiptR)) {
                  $receiptarr['type'] = 'customerReceipt';
                  $receiptarr['id'] = $cashReceiptRow['id'];
                  $receiptarr['date'] = (isset($cashReceiptRow['payment_date'])) ? $cashReceiptRow['payment_date'] : '';
                  $receiptarr['sr_no'] = (isset($cashReceiptRow['cash_receipt_no'])) ? $cashReceiptRow['cash_receipt_no'] : '';
                  $receiptarr['narration'] = (isset($cashReceiptRow['cash_receipt_no'])) ? 'Customer Receipt | '.$cashReceiptRow['cash_receipt_no'] : 'Customer Receipt';
                  $receiptarr['debit'] = '';
                  $receiptarr['credit'] = (isset($cashReceiptRow['amount']) && $cashReceiptRow['amount'] != '') ? $cashReceiptRow['amount'] : 0;
                  $receiptarr['url'] = 'accounting-customer-receipt.php?id='.$cashReceiptRow['id'];
                  $data[] = $receiptarr;
                }
            }
            /*----------------------------------------------------COUNT CUSTOMER RECEIPT END-------------------------------------------------------*/

            /*----------------------------------------------------COUNT CHECK PAYMENT START-------------------------------------------------------*/
            if($type_by =="0"){
               $checkPaymentQ =  "SELECT id, voucher_no, voucher_date, cheque_no, amount FROM accounting_cheque WHERE pharmacy_id = '".$pharmacy_id."' AND financial_id = '".$financial_id."' AND perticular IN (".$all_customer_id.") AND voucher_type = 'payment'"; 
            }else{
               $checkPaymentQ =  "SELECT id, voucher_no, voucher_date, cheque_no, amount FROM accounting_cheque WHERE pharmacy_id = '".$pharmacy_id."' AND financial_id = '".$financial_id."' AND perticular = '".$customer_id."' AND voucher_type = 'payment'"; 
            }
            
            if((isset($from) && $from != '') && (isset($to) && $to != '')){
              $checkPaymentQ .= " AND voucher_date >= '".$from."' AND voucher_date <= '".$to."'";
            }
            $checkPaymentR = mysqli_query($conn, $checkPaymentQ);
            if($checkPaymentR && mysqli_num_rows($checkPaymentR) > 0){
                while ($checkPaymentRow = mysqli_fetch_assoc($checkPaymentR)) {
                  $checkarr['type'] = 'CheckPayment';
                  $checkarr['id'] = $checkPaymentRow['id'];
                  $checkarr['date'] = (isset($checkPaymentRow['voucher_date'])) ? $checkPaymentRow['voucher_date'] : '';
                  $checkarr['sr_no'] = (isset($checkPaymentRow['voucher_no'])) ? $checkPaymentRow['voucher_no'] : '';
                  $checkarr['narration'] = (isset($checkPaymentRow['voucher_no'])) ? 'Check Payment | '.$checkPaymentRow['voucher_no'] : 'Check Payment';
                  $checkarr['debit'] = (isset($checkPaymentRow['amount']) && $checkPaymentRow['amount'] != '') ? $checkPaymentRow['amount'] : 0;
                  $checkarr['credit'] = '';
                  $checkarr['url'] = 'accounting-cheque.php?id='.$checkPaymentRow['id'];
                  $data[] = $checkarr;
                }
            }
            /*----------------------------------------------------COUNT CHECK PAYMENT END-------------------------------------------------------*/

            /*----------------------------------------------------COUNT CHECK RECEIPT START-------------------------------------------------------*/
            if($type_by == "0"){
                $checkReceiptQ =  "SELECT id, voucher_no, voucher_date, cheque_no, amount FROM accounting_cheque WHERE pharmacy_id = '".$pharmacy_id."' AND financial_id = '".$financial_id."' AND perticular IN (".$all_customer_id.") AND voucher_type = 'receipt'";
            }else{
                $checkReceiptQ =  "SELECT id, voucher_no, voucher_date, cheque_no, amount FROM accounting_cheque WHERE pharmacy_id = '".$pharmacy_id."' AND financial_id = '".$financial_id."' AND perticular = '".$customer_id."' AND voucher_type = 'receipt'";
            }
            
            if((isset($from) && $from != '') && (isset($to) && $to != '')){
              $checkReceiptQ .= " AND voucher_date >= '".$from."' AND voucher_date <= '".$to."'";
            }
            $checkReceiptR = mysqli_query($conn, $checkReceiptQ);
            if($checkReceiptR && mysqli_num_rows($checkReceiptR) > 0){
                while ($checkReceiptRow = mysqli_fetch_assoc($checkReceiptR)) {
                  $checkRarr['type'] = 'CheckReceipt';
                  $checkRarr['id'] = $checkReceiptRow['id'];
                  $checkRarr['date'] = (isset($checkReceiptRow['voucher_date'])) ? $checkReceiptRow['voucher_date'] : '';
                  $checkRarr['sr_no'] = (isset($checkReceiptRow['voucher_no'])) ? $checkReceiptRow['voucher_no'] : '';
                  $checkRarr['narration'] = (isset($checkReceiptRow['voucher_no'])) ? 'Check Receipt | '.$checkReceiptRow['voucher_no'] : 'Check Receipt';
                  $checkRarr['debit'] = '';
                  $checkRarr['credit'] = (isset($checkReceiptRow['amount']) && $checkReceiptRow['amount'] != '') ? $checkReceiptRow['amount'] : 0;
                  $checkRarr['url'] = 'accounting-cheque.php?id='.$checkReceiptRow['id'];
                  $data[] = $checkRarr;
                }
            }
            /*----------------------------------------------------COUNT CHECK RECEIPT END-------------------------------------------------------*/

            /*----------------------------------------------------COUNT CASH RECEIPT START-------------------------------------------------------*/
            if($type_by == "0"){
                $cashReceiptQ =  "SELECT id, voucher_no, voucher_date, amount FROM accounting_cash_management WHERE pharmacy_id = '".$pharmacy_id."' AND financial_id = '".$financial_id."' AND perticular IN (".$all_customer_id.") AND payment_type = 'cash_receipt'";
            }else{
                $cashReceiptQ =  "SELECT id, voucher_no, voucher_date, amount FROM accounting_cash_management WHERE pharmacy_id = '".$pharmacy_id."' AND financial_id = '".$financial_id."' AND perticular = '".$customer_id."' AND payment_type = 'cash_receipt'";   
            }
            if((isset($from) && $from != '') && (isset($to) && $to != '')){
              $cashReceiptQ .= " AND voucher_date >= '".$from."' AND voucher_date <= '".$to."'";
            }
            $cashReceiptR = mysqli_query($conn, $cashReceiptQ);
            if($cashReceiptR && mysqli_num_rows($cashReceiptR) > 0){
                while ($cashReceiptRow = mysqli_fetch_assoc($cashReceiptR)) {
                  $cashRarr['type'] = 'cashReceipt';
                  $cashRarr['id'] = $cashReceiptRow['id'];
                  $cashRarr['date'] = (isset($cashReceiptRow['voucher_date'])) ? $cashReceiptRow['voucher_date'] : '';
                  $cashRarr['sr_no'] = (isset($cashReceiptRow['voucher_no'])) ? $cashReceiptRow['voucher_no'] : '';
                  $cashRarr['narration'] = (isset($cashReceiptRow['voucher_no'])) ? 'Cash Receipt | '.$cashReceiptRow['voucher_no'] : 'Cash Receipt';
                  $cashRarr['debit'] = '';
                  $cashRarr['credit'] = (isset($cashReceiptRow['amount']) && $cashReceiptRow['amount'] != '') ? $cashReceiptRow['amount'] : 0;
                  $cashRarr['url'] = 'accounting-cash-management.php?id='.$cashReceiptRow['id'];
                  $data[] = $cashRarr;
                }
            }
            /*----------------------------------------------------COUNT CHECK RECEIPT END-------------------------------------------------------*/

            /*----------------------------------------------------COUNT CASH PAYMENT START-------------------------------------------------------*/
            if($type_by == "0"){
                $cashPaymentQ =  "SELECT id, voucher_no, voucher_date, amount FROM accounting_cash_management WHERE pharmacy_id = '".$pharmacy_id."' AND financial_id = '".$financial_id."' AND perticular IN (".$all_customer_id.") AND payment_type = 'cash_payment'";        
            }else{
                $cashPaymentQ =  "SELECT id, voucher_no, voucher_date, amount FROM accounting_cash_management WHERE pharmacy_id = '".$pharmacy_id."' AND financial_id = '".$financial_id."' AND perticular = '".$customer_id."' AND payment_type = 'cash_payment'";    
            }
            
            if((isset($from) && $from != '') && (isset($to) && $to != '')){
              $cashPaymentQ .= " AND voucher_date >= '".$from."' AND voucher_date <= '".$to."'";
            }
            $cashPaymentR = mysqli_query($conn, $cashPaymentQ);
            if($cashPaymentR && mysqli_num_rows($cashPaymentR) > 0){
                while ($cashPaymentRow = mysqli_fetch_assoc($cashPaymentR)) {
                  $cashParr['type'] = 'cashPayment';
                  $cashParr['id'] = $cashPaymentRow['id'];
                  $cashParr['date'] = (isset($cashPaymentRow['voucher_date'])) ? $cashPaymentRow['voucher_date'] : '';
                  $cashParr['sr_no'] = (isset($cashPaymentRow['voucher_no'])) ? $cashPaymentRow['voucher_no'] : '';
                  $cashParr['narration'] = (isset($cashPaymentRow['voucher_no'])) ? 'Cash Payment | '.$cashPaymentRow['voucher_no'] : 'Cash Payment';
                  $cashParr['debit'] = (isset($cashPaymentRow['amount']) && $cashPaymentRow['amount'] != '') ? $cashPaymentRow['amount'] : 0;
                  $cashParr['credit'] = '';
                  $cashParr['url'] = 'accounting-cash-management.php?id='.$cashPaymentRow['id'];
                  $data[] = $cashParr;
                }
            }
            /*----------------------------------------------------COUNT CHECK PAYMENT END-------------------------------------------------------*/


            /*-----------------------------------------------------COUNT JOURNAL VOUCHER START-----------------------------------------------*/
            if($type_by == "0"){
                $jurnalVoucherQ = "SELECT jv.id, jv.voucher_date, jvd.debit, jvd.credit FROM journal_vouchar_details jvd INNER JOIN journal_vouchar jv ON jvd.voucher_id = jv.id WHERE jvd.particular IN (".$all_customer_id.") AND jv.pharmacy_id = '".$pharmacy_id."' AND jv.financial_id = '".$financial_id."'";
            }else{
                $jurnalVoucherQ = "SELECT jv.id, jv.voucher_date, jvd.debit, jvd.credit FROM journal_vouchar_details jvd INNER JOIN journal_vouchar jv ON jvd.voucher_id = jv.id WHERE jvd.particular = '".$customer_id."' AND jv.pharmacy_id = '".$pharmacy_id."' AND jv.financial_id = '".$financial_id."'";    
            }
            
            if((isset($from) && $from != '') && (isset($to) && $to != '')){
              $jurnalVoucherQ .= " AND jv.voucher_date >= '".$from."' AND jv.voucher_date <= '".$to."'";
            }
            $jurnalVoucherR = mysqli_query($conn, $jurnalVoucherQ);
            if($jurnalVoucherR && mysqli_num_rows($jurnalVoucherR) > 0){
                while ($jurnalVoucherRow = mysqli_fetch_assoc($jurnalVoucherR)) {
                  $jurnalarr['type'] = 'journalVoucher';
                  $jurnalarr['id'] = $jurnalVoucherRow['id'];
                  $jurnalarr['date'] = (isset($jurnalVoucherRow['voucher_date'])) ? $jurnalVoucherRow['voucher_date'] : '';
                  $jurnalarr['sr_no'] = '';
                  $jurnalarr['narration'] = 'Journal Voucher';
                  $jurnalarr['debit'] = (isset($jurnalVoucherRow['debit']) && $jurnalVoucherRow['debit'] != 0) ? $jurnalVoucherRow['debit'] : '';
                  $jurnalarr['credit'] = (isset($jurnalVoucherRow['credit']) && $jurnalVoucherRow['credit'] != 0) ? $jurnalVoucherRow['credit'] : '';
                  $jurnalarr['url'] = 'journal-vouchar.php?id='.$jurnalVoucherRow['id'];
                  
                  $data[] = $jurnalarr;
                }
            }
            /*-----------------------------------------------------COUNT JOURNAL VOUCHER END-------------------------------------------------*/
            
        }

        $final_array = $data;

        function date_compare($a, $b){

            $t1 = strtotime($a['date']);

            $t2 = strtotime($b['date']);

            return $t1 - $t2;
        }    

        usort($final_array, 'date_compare');

        $finaldata['opening_balance'] = $opening_balance;
        $finaldata['data'] = $final_array;
        $countRunningBalance = countRunningBalance($customer_id, $from, $to);
        $finaldata['running_balance'] = (isset($countRunningBalance['running_balance']) && $countRunningBalance['running_balance'] != '') ? $countRunningBalance['running_balance'] : 0;
        return $finaldata;
    }
    
    
    function getTransportCode(){
      global $conn;
      global $pharmacy_id;
      $code = 1;
      
      $query = "SELECT t_code FROM transport_master WHERE pharmacy_id = $pharmacy_id ORDER BY id DESC LIMIT 1";
      $res = mysqli_query($conn, $query);
      if($res && mysqli_num_rows($res) > 0){
          $row = mysqli_fetch_assoc($res);
          $tmpcode = (isset($row['t_code']) && $row['t_code'] != '') ? $row['t_code'] : 0;
          $code = $tmpcode+1;
      }
      return $code;
    }
    
    function profit_loss($from = null, $to = null){
      global $conn;
      $profit_loss = [];
      $financial_year =   (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';
      $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : '';

      $gross = 0;
      $NetProfit = 0;
      $Total = 0;
             // opning stock and closing stock
      /*$taccount =  getTotalClosingBalance($from, $to);
      $opening_balance = $taccount['opening_balance'];
      $closing_balance = $taccount['running_balance'];*/
      
      $taccount =  getTotalClosingStock($from, $to, 0);
      $opening_balance = (isset($taccount['opening_stock']) && $taccount['opening_stock'] != '') ? $taccount['opening_stock'] : 0;
      $closing_balance = (isset($taccount['closing_stock']) && $taccount['closing_stock'] != '') ? $taccount['closing_stock'] : 0;

                  // purchase account
      $p_account = "SELECT  sum(total_amount) as total_amount FROM purchase WHERE pharmacy_id = '".$pharmacy_id."'"; 
      if((isset($from) && $from != '') && (isset($to) && $to != '')){
      $p_account .= " AND vouchar_date >= '".$from."' AND vouchar_date <= '".$to."'";
      }
      $p_accountR = mysqli_query($conn,$p_account);
      $p_accountL =  mysqli_fetch_assoc($p_accountR); 

               //sale account 

      $s_account = "SELECT sum(alltotalamount) as total_amount FROM  tax_billing WHERE pharmacy_id = '".$pharmacy_id."'" ;
      if((isset($from) && $from != '') && (isset($to) && $to != '')){
       $s_account .= " AND invoice_date >= '".$from."' AND invoice_date <= '".$to."'";
     }
     $s_accountR = mysqli_query($conn,$s_account);
     $s_accountL =  mysqli_fetch_assoc($s_accountR); 

          //-------------------------------------------- indirect expenses ----------------------------------------
     $i = 0;
     $inDirectExpensesArr = [];

     $qry12 = "SELECT id,name FROM ledger_master WHERE group_id = 18 ";
     $inDirctExpnc = mysqli_query($conn,$qry12);

     while($rows  = mysqli_fetch_assoc($inDirctExpnc)) {

      $totalinExpense = 0;
      $allExpenseCount = 0;
      $cashreceipts = "SELECT SUM(amount) as total_amount FROM accounting_cash_management WHERE perticular = ".$rows['id']." AND pharmacy_id = '".$pharmacy_id."' AND payment_type = 'cash_receipt'"; 
      if((isset($from) && $from != '') && (isset($to) && $to != '')){
      $cashreceipts .= " AND voucher_date >= '".$from."' AND voucher_date <= '".$to."'";
      }
      $rceiptCashs = mysqli_query($conn,$cashreceipts);
      $rceiptTotals = mysqli_fetch_assoc($rceiptCashs);
      $receipts = $rceiptTotals['total_amount'];
      $totalinExpense = $totalinExpense + $receipts; 

      $chequepayments= "SELECT SUM(amount) as total_amount FROM accounting_cheque WHERE perticular = ".$rows['id']." AND pharmacy_id = '".$pharmacy_id."' AND voucher_type = 'payment'";
      if((isset($from) && $from != '') && (isset($to) && $to != '')){
        $chequepayments .= " AND voucher_date >= '".$from."' AND voucher_date <= '".$to."'";
      }
      $paymentcheqs = mysqli_query($conn,$chequepayments);
      $paymentTotals = mysqli_fetch_assoc($paymentcheqs);
      $paymentCs = $paymentTotals['total_amount'];
      $totalinExpense = $totalinExpense + $paymentCs ;

      $jvQrys = "SELECT SUM(debit) as total_amount FROM journal_vouchar_details jvd INNER JOIN journal_vouchar jv ON jvd.voucher_id = jv.id WHERE jvd.particular = ".$rows['id']." AND jv.pharmacy_id = '".$pharmacy_id."'";
      if((isset($from) && $from != '') && (isset($to) && $to != '')){
        $jvQrys .= " AND jv.voucher_date >= '".$from."' AND jv.voucher_date <= '".$to."'";
      }
      $resultJvs = mysqli_query($conn,$jvQrys);
      $jvTotalDebits = mysqli_fetch_assoc($resultJvs);
      $jvTotalDebits = $jvTotalDebits['total_amount'];
      $totalinExpense = $totalinExpense + $jvTotalDebits ; 

      $inDirectExpensesArr[$i]['id'] = $rows['id'];
      $inDirectExpensesArr[$i]['name'] = $rows['name'];
      $inDirectExpensesArr[$i]['expense'] = $totalinExpense;
      $i++;
    }
    $InDirectExpenseTotal = 0;
    foreach ($inDirectExpensesArr as $expense) {
     $InDirectExpenseTotal = $InDirectExpenseTotal + $expense['expense'];
   }

        //--------------------------------------Direct Expenses------------------------------------------
   $i = 0;
   $DirectExpensesArr = [];

   $qry12 = "SELECT id,name FROM ledger_master WHERE group_id = 15 ";
   $DirctExpnc = mysqli_query($conn,$qry12);

   while($rows  = mysqli_fetch_assoc($DirctExpnc)) {

    $totalExpense = 0;
    $allDExpenseCount = 0;
    $cashreceipts1 = "SELECT SUM(amount) as total_amount FROM accounting_cash_management WHERE perticular = ".$rows['id']." AND pharmacy_id = '".$pharmacy_id."' AND payment_type = 'cash_receipt'"; 
    if((isset($from) && $from != '') && (isset($to) && $to != '')){
      $cashreceipts1 .= " AND voucher_date >= '".$from."' AND voucher_date <= '".$to."'";
    }
    $rceiptCashs1 = mysqli_query($conn,$cashreceipts1);
    $rceiptTotals1 = mysqli_fetch_assoc($rceiptCashs1);
    $receipts1 = $rceiptTotals1['total_amount'];
    $totalExpense = $totalExpense + $receipts1; 

    $chequepayments1= "SELECT SUM(amount) as total_amount FROM accounting_cheque WHERE perticular = ".$rows['id']." AND pharmacy_id = '".$pharmacy_id."' AND voucher_type = 'payment'";
    if((isset($from) && $from != '') && (isset($to) && $to != '')){
      $chequepayments1 .= " AND voucher_date >= '".$from."' AND voucher_date <= '".$to."'";
    }
    $paymentcheqs1 = mysqli_query($conn,$chequepayments1);
    $paymentTotals1 = mysqli_fetch_assoc($paymentcheqs1);
    $paymentCs1 = $paymentTotals1['total_amount'];
    $totalExpense = $totalExpense + $paymentCs1 ;

    $jvQrys1 = "SELECT SUM(debit) as total_amount FROM journal_vouchar_details jvd INNER JOIN journal_vouchar jv ON jvd.voucher_id = jv.id WHERE jvd.particular = ".$rows['id']." AND jv.pharmacy_id = '".$pharmacy_id."'";
    if((isset($from) && $from != '') && (isset($to) && $to != '')){
      $jvQrys1 .= " AND jv.voucher_date >= '".$from."' AND jv.voucher_date <= '".$to."'";
    }
    $resultJvs1 = mysqli_query($conn,$jvQrys1);
    $jvTotalDebits1 = mysqli_fetch_assoc($resultJvs1);
    $jvTotalDebits1 = $jvTotalDebits1['total_amount'];
    $totalExpense = $totalExpense + $jvTotalDebits1 ; 

    $DirectExpensesArr[$i]['id'] = $rows['id'];
    $DirectExpensesArr[$i]['name'] = $rows['name'];
    $DirectExpensesArr[$i]['expense'] = $totalExpense ;
    $i++;
  }
  $DirectExpensesTotal = 0;
  foreach ($DirectExpensesArr as $expense) {
   $DirectExpensesTotal = $DirectExpensesTotal + $expense['expense'];
 }

        //----------------------------------------indirect income ---------------------------------------------

 $i = 0;
 $inDirectIncomeArr = [];

 $qry13 = "SELECT id,name FROM ledger_master WHERE group_id = 19 ";
 $inDirctIncm = mysqli_query($conn,$qry13);

 while($rows  = mysqli_fetch_assoc($inDirctIncm)) {

   $totalinIncome = 0;

   $cashpayment = "SELECT SUM(amount) as total_amount FROM accounting_cash_management WHERE perticular = ".$rows['id']." AND pharmacy_id = '".$pharmacy_id."' AND payment_type = 'cash_payment'";
   if((isset($from) && $from != '') && (isset($to) && $to != '')){
    $cashpayment .= " AND voucher_date >= '".$from."' AND voucher_date <= '".$to."'";
  }
  $paymentCash = mysqli_query($conn,$cashpayment);
  $cashTotal = mysqli_fetch_assoc($paymentCash);
  $payment = $cashTotal['total_amount'];
  $totalinIncome = $totalinIncome + $payment ; 


  $chequereceipt = "SELECT SUM(amount) as total_amount FROM accounting_cheque WHERE perticular = ".$rows['id']." AND pharmacy_id = '".$pharmacy_id."' AND voucher_type = 'receipt'";
  if((isset($from) && $from != '') && (isset($to) && $to != '')){
    $chequereceipt .= " AND voucher_date >= '".$from."' AND voucher_date <= '".$to."'";
  }

  $recpcheque = mysqli_query($conn,$chequereceipt);
  $recpTotal = mysqli_fetch_assoc($recpcheque);
  $recp = $recpTotal['total_amount'];
  $totalinIncome = $totalinIncome + $recp ;

  $jvQry1 = "SELECT SUM(credit) as total_amount FROM journal_vouchar_details jvd INNER JOIN journal_vouchar jv ON jvd.voucher_id = jv.id WHERE jvd.particular = ".$rows['id']."  AND jv.pharmacy_id = '".$pharmacy_id."'";
  if((isset($from) && $from != '') && (isset($to) && $to != '')){
    $jvQry1 .= " AND jv.voucher_date >= '".$from."' AND jv.voucher_date <= '".$to."'";
  }
  $resultJv1 = mysqli_query($conn,$jvQry1);
  $jvTotalCredit = mysqli_fetch_assoc($resultJv1);
  $jvTotalCredit = $jvTotalCredit['total_amount'];
  $totalinIncome = $totalinIncome + $jvTotalCredit ;


  $inDirectIncomeArr[$i]['name'] = $rows['name'];
  $inDirectIncomeArr[$i]['expense'] = $totalinIncome;

  $i++;
}
$InDirectIncomeTotal = 0;
foreach ($inDirectIncomeArr as $income) {
 $InDirectIncomeTotal = $InDirectIncomeTotal + $income['expense'];
}

        //----------------------------------------direct income ---------------------------------------------

$i = 0;
$DirectIncomeArr = [];

$qry13 = "SELECT id,name FROM ledger_master WHERE group_id = 16 ";
$inDirctIncm = mysqli_query($conn,$qry13);

while($rows  = mysqli_fetch_assoc($inDirctIncm)) {

  $totalIncome = 0;

  $cashpayment1 = "SELECT SUM(amount) as total_amount FROM accounting_cash_management WHERE perticular = ".$rows['id']."  AND pharmacy_id = '".$pharmacy_id."' AND payment_type = 'cash_payment'";
  if((isset($from) && $from != '') && (isset($to) && $to != '')){
    $cashpayment1 .= " AND voucher_date >= '".$from."' AND voucher_date <= '".$to."'";
  }
  $paymentCash1 = mysqli_query($conn,$cashpayment1);
  $cashTotal1 = mysqli_fetch_assoc($paymentCash1);
  $payment1 = $cashTotal1['total_amount'];
  $totalIncome = $totalIncome + $payment1 ; 


  $chequereceipt1 = "SELECT SUM(amount) as total_amount FROM accounting_cheque WHERE perticular = ".$rows['id']." AND pharmacy_id = '".$pharmacy_id."' AND voucher_type = 'receipt'";
  if((isset($from) && $from != '') && (isset($to) && $to != '')){
    $chequereceipt1 .= " AND voucher_date >= '".$from."' AND voucher_date <= '".$to."'";
  }

  $recpcheque1 = mysqli_query($conn,$chequereceipt1);
  $recpTotal1 = mysqli_fetch_assoc($recpcheque1);
  $recp1 = $recpTotal1['total_amount'];
  $totalIncome = $totalIncome + $recp1 ;


  $jvQry2 = "SELECT SUM(credit) as total_amount FROM journal_vouchar_details jvd INNER JOIN journal_vouchar jv ON jvd.voucher_id = jv.id WHERE jvd.particular = ".$rows['id']." AND jv.pharmacy_id = '".$pharmacy_id."'";
  if((isset($from) && $from != '') && (isset($to) && $to != '')){
    $jvQry2 .= " AND jv.voucher_date >= '".$from."' AND jv.voucher_date <= '".$to."'";
  }
  $resultJv2 = mysqli_query($conn,$jvQry2);
  $jvTotalCredit2 = mysqli_fetch_assoc($resultJv2);
  $jvTotalCredit2 = $jvTotalCredit2['total_amount'];
  $totalIncome = $totalIncome + $jvTotalCredit2 ;


  $DirectIncomeArr[$i]['name'] = $rows['name'];
  $DirectIncomeArr[$i]['expense'] = $totalIncome;

  $i++;
}
$DirectIncomeTotal = 0;
foreach ($DirectIncomeArr as $income) {
 $DirectIncomeTotal = $DirectIncomeTotal + $income['expense'];
}

$GrossProfit =  $s_accountL['total_amount'] + $DirectIncomeTotal + $closing_balance - $opening_balance - $p_accountL['total_amount'] - $DirectExpensesTotal;
$NetProfit = $GrossProfit - $InDirectExpenseTotal;
$Total = $opening_balance + $p_accountL['total_amount'] + $DirectExpensesTotal + $GrossProfit;


$profit_loss['left'] = array(
  'OpeningStock' => $opening_balance,
  'PurchaseAccounts' => $p_accountL,        
  'IndirectExpenses' => $inDirectExpensesArr,
  'TotalIndirectExpenses' => $InDirectExpenseTotal,
  'DirectExpenses' => $DirectExpensesArr,
  'TotalDirectExpenses' => $DirectExpensesTotal
);
$profit_loss['right'] = array(        
  'ClosingStock' => $closing_balance,
  'SaleAccount' => $s_accountL, 
  'IndirectIncome' => $inDirectIncomeArr,
  'TotalInDirectIncome' => $InDirectIncomeTotal,
  'DirectIncome' => $DirectIncomeArr,
  'TotalDirectIncome' =>  $DirectIncomeTotal
);
$profit_loss['profit'] = array(
  'Grossprofit' => $GrossProfit,
  'NetProfit' => $NetProfit,
  'Total' => $Total);

return $profit_loss; 
}
    
    
    /*--------------------------BALANCE SHEET REPORT - GAUTAM MAKWANA - 12-11-2018 - START---------------------------*/
    function balanceSheetReport($from = null, $to = null, $is_financial = 0){
        global $conn;
        global $pharmacy_id;
        global $financial_id;
        // left array
        $data['left']['capitalAccount']['name'] = 'Capital Account';
        $data['left']['capitalAccount']['totalamount'] = 0;
        $data['left']['capitalAccount']['data'] = [];

        $data['left']['loansLiability']['name'] = 'Loans (Liability)';
        $data['left']['loansLiability']['totalamount'] = 0;
        $data['left']['loansLiability']['data'] = [];

        $data['left']['currentLiability']['name'] = 'Current Liabilities';
        $data['left']['currentLiability']['totalamount'] = 0;
        $data['left']['currentLiability']['data'] = [];

        $data['left']['suspenseAcc']['name'] = 'Suspense A/c';
        $data['left']['suspenseAcc']['totalamount'] = 0;
        $data['left']['suspenseAcc']['data'] = [];

        /*$data['left']['profitAndLossAcc']['name'] = 'Profit & Loss A/c';
        $data['left']['profitAndLossAcc']['totalamount'] = 0;
        $data['left']['profitAndLossAcc']['data'] = [];*/
        // right array
        $data['right']['fixedAssets']['name'] = 'Fixed Assets';
        $data['right']['fixedAssets']['totalamount'] = 0;
        $data['right']['fixedAssets']['data'] = [];

        $data['right']['investment']['name'] = 'Investments';
        $data['right']['investment']['totalamount'] = 0;
        $data['right']['investment']['data'] = [];

        $data['right']['currentAssets']['name'] = 'Current Assets';
        $data['right']['currentAssets']['totalamount'] = 0;
        $data['right']['currentAssets']['data'] = [];
        
        /*----------CAPITAL ACCOUNT START-------*/
            // get all capital account
            $capitalAccQ = "SELECT id, name, opening_balance, opening_balance_type FROM ledger_master WHERE pharmacy_id = '".$pharmacy_id."' AND group_id = 2 ORDER BY name";
            $capitalAccR = mysqli_query($conn, $capitalAccQ);
            if($capitalAccR && mysqli_num_rows($capitalAccR) > 0){
                while ($capitalAccRow = mysqli_fetch_assoc($capitalAccR)) {
                    $tmp1['id'] = $capitalAccRow['id'];
                    $tmp1['name'] = (isset($capitalAccRow['name'])) ? $capitalAccRow['name'] : '';
                    $running1 = countRunningBalance($capitalAccRow['id'], $from, $to, $is_financial);
                    $tmp1['opening_balance'] = (isset($running1['opening_balance']) && $running1['opening_balance'] != '') ? $running1['opening_balance'] : 0;
                    $tmp1['closing_balance'] = (isset($running1['running_balance']) && $running1['running_balance'] != '') ? $running1['running_balance'] : 0;
                    $data['left']['capitalAccount']['totalamount'] += $tmp1['closing_balance'];
                    $data['left']['capitalAccount']['data'][] = $tmp1;
                }
            }
        /*----------CAPITAL ACCOUNT END---------*/


        /*----------LOAN LIABILITY START-------*/
            // get all loan liability account
            $loanLiabilityQ = "SELECT id, name, opening_balance, opening_balance_type FROM ledger_master WHERE pharmacy_id = '".$pharmacy_id."' AND group_id = 21 ORDER BY name";
            $loanLiabilityR = mysqli_query($conn, $loanLiabilityQ);
            if($loanLiabilityR && mysqli_num_rows($loanLiabilityR) > 0){
                while ($loanLiabilityRow = mysqli_fetch_assoc($loanLiabilityR)) {
                    $tmp2['id'] = $loanLiabilityRow['id'];
                    $tmp2['name'] = (isset($loanLiabilityRow['name'])) ? $loanLiabilityRow['name'] : '';
                    $running2 = countRunningBalance($loanLiabilityRow['id'], $from, $to, $is_financial);
                    $tmp2['opening_balance'] = (isset($running2['opening_balance']) && $running2['opening_balance'] != '') ? $running2['opening_balance'] : 0;
                    $tmp2['closing_balance'] = (isset($running2['running_balance']) && $running2['running_balance'] != '') ? $running2['running_balance'] : 0;
                    $data['left']['loansLiability']['totalamount'] += $tmp2['closing_balance'];
                    $data['left']['loansLiability']['data'][] = $tmp2;
                }
            }
        /*----------LOAN LIABILITY END---------*/

        /*----------CURRENT LIABILITY START-------*/
            // COUNT TOTAL SUNDARY CREDITOR RUNNING BALANCE AS PER TALK TO HIRENBHAI
            $tmp3['id'] = '';
            $tmp3['name'] = 'Sundry Creditors';
            $vendorData = getGroupWiseClosingBalance(14,$from,$to,$is_financial);
            
            $tmp3['opening_balance'] = (isset($vendorData['opening_balance']) && $vendorData['opening_balance'] != '') ? $vendorData['opening_balance'] : 0;
            $tmp3['closing_balance'] = (isset($vendorData['running_balance']) && $vendorData['running_balance'] != '') ? $vendorData['running_balance'] : 0;
            $data['left']['currentLiability']['totalamount'] += $tmp3['closing_balance'];
            $data['left']['currentLiability']['data'][] = $tmp3;
        
            // get all current liability account
            /*$currentLiabilityQ = "SELECT id, name, opening_balance, opening_balance_type FROM ledger_master WHERE pharmacy_id = '".$pharmacy_id."' AND group_id = 11 ORDER BY name";
            $currentLiabilityR = mysqli_query($conn, $currentLiabilityQ);
            if($currentLiabilityR && mysqli_num_rows($currentLiabilityR) > 0){
                while ($currentLiabilityRow = mysqli_fetch_assoc($currentLiabilityR)) {
                    $tmp3['id'] = $currentLiabilityRow['id'];
                    $tmp3['name'] = (isset($currentLiabilityRow['name'])) ? $currentLiabilityRow['name'] : '';
                    $running3 = countRunningBalance($currentLiabilityRow['id'], $from, $to);
                    $tmp3['opening_balance'] = (isset($running3['opening_balance']) && $running3['opening_balance'] != '') ? $running3['opening_balance'] : 0;
                    $tmp3['closing_balance'] = (isset($running3['running_balance']) && $running3['running_balance'] != '') ? $running3['running_balance'] : 0;
                    $data['left']['currentLiability']['totalamount'] += $tmp3['closing_balance'];
                    $data['left']['currentLiability']['data'][] = $tmp3;
               }
            }*/
        /*----------CURRENT LIABILITY END---------*/

        /*----------SUNSPENSE ACCOUNT START-------*/
            // get all suspense account
            $sunspenseAccQ = "SELECT id, name, opening_balance, opening_balance_type FROM ledger_master WHERE pharmacy_id = '".$pharmacy_id."' AND group_id = 28 ORDER BY name";
            $sunspenseAccR = mysqli_query($conn, $sunspenseAccQ);
            if($sunspenseAccR && mysqli_num_rows($sunspenseAccR) > 0){
                while ($sunspenseAccRow = mysqli_fetch_assoc($sunspenseAccR)) {
                    $tmp4['id'] = $sunspenseAccRow['id'];
                    $tmp4['name'] = (isset($sunspenseAccRow['name'])) ? $sunspenseAccRow['name'] : '';
                    $running4 = countRunningBalance($sunspenseAccRow['id'], $from, $to, $is_financial);
                    $tmp4['opening_balance'] = (isset($running4['opening_balance']) && $running4['opening_balance'] != '') ? $running4['opening_balance'] : 0;
                    $tmp4['closing_balance'] = (isset($running4['running_balance']) && $running4['running_balance'] != '') ? $running4['running_balance'] : 0;
                    $data['left']['suspenseAcc']['totalamount'] += $tmp4['closing_balance'];
                    $data['left']['suspenseAcc']['data'][] = $tmp4;
                }
            }
        /*----------SUNSPENSE ACCOUNT END---------*/


        /*----------FIXED ASSETS START-------*/
            // get all fixed assets
            $fixedAssetsQ = "SELECT id, name, opening_balance, opening_balance_type FROM ledger_master WHERE pharmacy_id = '".$pharmacy_id."' AND group_id = 17 ORDER BY name";
            $fixedAssetsR = mysqli_query($conn, $fixedAssetsQ);
            if($fixedAssetsR && mysqli_num_rows($fixedAssetsR) > 0){
                while ($fixedAssetsRow = mysqli_fetch_assoc($fixedAssetsR)) {
                    $tmp5['id'] = $fixedAssetsRow['id'];
                    $tmp5['name'] = (isset($fixedAssetsRow['name'])) ? $fixedAssetsRow['name'] : '';
                    $running5 = countRunningBalance($fixedAssetsRow['id'], $from, $to, $is_financial);
                    $tmp5['opening_balance'] = (isset($running5['opening_balance']) && $running5['opening_balance'] != '') ? $running5['opening_balance'] : 0;
                    $tmp5['closing_balance'] = (isset($running5['running_balance']) && $running5['running_balance'] != '') ? $running5['running_balance'] : 0;
                    $data['right']['fixedAssets']['totalamount'] += $tmp5['closing_balance'];
                    $data['right']['fixedAssets']['data'][] = $tmp5;
                }
            }
        /*----------FIXED ASSETS END---------*/


        /*----------INVESTMENT START-------*/
            // get all investment accounts 
            $investmentQ = "SELECT id, name, opening_balance, opening_balance_type FROM ledger_master WHERE pharmacy_id = '".$pharmacy_id."' AND group_id = 20 ORDER BY name";
            $investmentR = mysqli_query($conn, $investmentQ);
            if($investmentR && mysqli_num_rows($investmentR) > 0){
                while ($investmentRow = mysqli_fetch_assoc($investmentR)) {
                    $tmp6['id'] = $investmentRow['id'];
                    $tmp6['name'] = (isset($investmentRow['name'])) ? $investmentRow['name'] : '';
                    $running6 = countRunningBalance($investmentRow['id'], $from, $to, $is_financial);
                    $tmp6['opening_balance'] = (isset($running6['opening_balance']) && $running6['opening_balance'] != '') ? $running6['opening_balance'] : 0;
                    $tmp6['closing_balance'] = (isset($running6['running_balance']) && $running6['running_balance'] != '') ? $running6['running_balance'] : 0;
                    $data['right']['investment']['totalamount'] += $tmp6['closing_balance'];
                    $data['right']['investment']['data'][] = $tmp6;
                }
            }
        /*----------INVESTMENT END---------*/


        /*----------CURRENT ASSETS START-------*/
            
            // COUNT TOTAL CLOSING BALANCE FOR Closing Stock, Cash-in-Hand and Bank Accounts AS PER TALK TO HIRENBHAI
            $tmp7['id'] = '';
            $totalClosingStock = getTotalClosingStock($from, $to, $is_financial);
            $method = (isset($totalClosingStock['mothod']) && $totalClosingStock['mothod'] != '') ? ' ('.$totalClosingStock['mothod'].')' : '';
            $tmp7['name'] = 'Closing Stock'.$method;
            $tmp7['opening_balance'] = 0;
            $tmp7['closing_balance'] = (isset($totalClosingStock['closing_stock']) && $totalClosingStock['closing_stock'] != '') ? $totalClosingStock['closing_stock'] : 0;
            $data['right']['currentAssets']['totalamount'] += $tmp7['closing_balance'];
            $data['right']['currentAssets']['data'][] = $tmp7;
            
            $tmp77['id'] = '';
            $tmp77['name'] = 'Cash-in-Hand';
            $totalcashinhand = countCashRunningBalance($from, $to, $is_financial);
            $tmp77['opening_balance'] = (isset($totalcashinhand['opening_balance']) && $totalcashinhand['opening_balance'] != '') ? $totalcashinhand['opening_balance'] : 0;
            $tmp77['closing_balance'] = (isset($totalcashinhand['running_balance']) && $totalcashinhand['running_balance'] != '') ? $totalcashinhand['running_balance'] : 0;
            $data['right']['currentAssets']['totalamount'] += $tmp77['closing_balance'];
            $data['right']['currentAssets']['data'][] = $tmp77;
            
            $tmp777['id'] = '';
            $tmp777['name'] = 'Bank Accounts';
            $totalbankaccount = allBankRunningBalance($from, $to, $is_financial);
            $tmp777['opening_balance'] = 0;
            $tmp777['closing_balance'] = (isset($totalbankaccount) && $totalbankaccount != '') ? $totalbankaccount : 0;
            $data['right']['currentAssets']['totalamount'] += $tmp777['closing_balance'];
            $data['right']['currentAssets']['data'][] = $tmp777;
        
            // get all current assets accounts 
            /*$currentAssetsQ = "SELECT id, name, opening_balance, opening_balance_type FROM ledger_master WHERE pharmacy_id = '".$pharmacy_id."' AND group_id = 4 ORDER BY name";
            $currentAssetsR = mysqli_query($conn, $currentAssetsQ);
            if($currentAssetsR && mysqli_num_rows($currentAssetsR) > 0){
                while ($currentAssetsRow = mysqli_fetch_assoc($currentAssetsR)) {
                    $tmp7['id'] = $currentAssetsRow['id'];
                    $tmp7['name'] = (isset($currentAssetsRow['name'])) ? $currentAssetsRow['name'] : '';
                    $running7 = countRunningBalance($currentAssetsRow['id'], $from, $to);
                    $tmp7['opening_balance'] = (isset($running7['opening_balance']) && $running7['opening_balance'] != '') ? $running7['opening_balance'] : 0;
                    $tmp7['closing_balance'] = (isset($running7['running_balance']) && $running7['running_balance'] != '') ? $running7['running_balance'] : 0;
                    $data['right']['currentAssets']['totalamount'] += $tmp7['closing_balance'];
                    $data['right']['currentAssets']['data'][] = $tmp7;
                }
            }*/
        /*----------CURRENT ASSETS END---------*/

        return $data;
    }
    /*--------------------------BALANCE SHEET REPORT - GAUTAM MAKWANA - 12-11-2018 - END-----------------------------*/
    
    /*--------------------------CAPITAL ACCOUNT REPORT - GAUTAM MAKWANA - 12-11-2018 - START-----------------------------*/
    function capitalAccountReport($id = null, $from = null, $to = null, $is_financial = 0){
        global $conn;
        global $pharmacy_id;
        global $financial_id;
        $data = [];

        if($id != ''){
            $getLedgerQ = "SELECT id, name, opening_balance, opening_balance_type, created FROM ledger_master WHERE pharmacy_id = '".$pharmacy_id."' AND id = '".$id."'";
            $getLedgerR = mysqli_query($conn, $getLedgerQ);
            if($getLedgerR && mysqli_num_rows($getLedgerR) > 0){
                $getLedgerRow = mysqli_fetch_assoc($getLedgerR);
                $type = (isset($getLedgerRow['opening_balance_type'])) ? $getLedgerRow['opening_balance_type'] : 'DB';
                $opening_balance = (isset($getLedgerRow['opening_balance']) && $getLedgerRow['opening_balance'] != '') ? $getLedgerRow['opening_balance'] : 0;
                $opening_balance = ($type == 'DB') ? $opening_balance : -$opening_balance;
                
                $data['id'] = $getLedgerRow['id'];
                $data['name'] = $getLedgerRow['name'];
                $data['opening_balance'] = $opening_balance;
                $data['date'] = (isset($getLedgerRow['created']) && $getLedgerRow['created'] != '') ? date('d-m-Y',strtotime($getLedgerRow['created'])) : '';
                $data['from'] = $from;
                $data['to'] = $to;


                // cash-management
                $cashManagementQ = "SELECT id, payment_type, voucher_no, voucher_date, amount, remark FROM accounting_cash_management WHERE pharmacy_id = '".$pharmacy_id."' AND perticular = '".$id."'";
                if(isset($is_financial) && $is_financial == 1){
                    $cashManagementQ .= " AND financial_id = '".$financial_id."'";
                }
                if((isset($from) && $from != '') && (isset($to) && $to != '')){
                    $cashManagementQ .= " AND voucher_date >= '".$from."' AND voucher_date <= '".$to."' ";
                }
                $cashManagementR = mysqli_query($conn, $cashManagementQ);
                if($cashManagementR && mysqli_num_rows($cashManagementR) > 0){
                    while ($cashManagementRow = mysqli_fetch_assoc($cashManagementR)) {
                        $tmp1['id'] = $cashManagementRow['id'];
                        $tmp1['date'] = $cashManagementRow['voucher_date'];
                        $remark = (isset($cashManagementRow['remark']) && $cashManagementRow['remark'] != '') ? ' - '.$cashManagementRow['remark'] : '';
                        if(isset($cashManagementRow['payment_type']) && $cashManagementRow['payment_type'] == 'cash_payment'){
                            $tmp1['crdr'] = 'Dr';
                            $tmp1['type'] = 'Payment';
                            $tmp1['credit'] = '';
                            $tmp1['debit'] = (isset($cashManagementRow['amount']) && $cashManagementRow['amount'] != '') ? $cashManagementRow['amount'] : 0;
                            $tmp1['perticular'] = 'Cash Payment'.$remark;
                        }else{
                            $tmp1['crdr'] = 'Cr';
                            $tmp1['type'] = 'Receipt';
                            $tmp1['credit'] = (isset($cashManagementRow['amount']) && $cashManagementRow['amount'] != '') ? $cashManagementRow['amount'] : 0;
                            $tmp1['debit'] = '';
                            $tmp1['perticular'] = 'Cash Receipt'.$remark;
                        }
                        $tmp1['voucherno'] = (isset($cashManagementRow['voucher_no'])) ? $cashManagementRow['voucher_no'] : '';
                        $data['data'][$tmp1['date']][] = $tmp1;
                    }
                }

                // cheque
                $chequeQ = "SELECT id, voucher_no, voucher_date, voucher_type, cheque_no, amount, remark FROM accounting_cheque WHERE pharmacy_id = '".$pharmacy_id."' AND perticular = '".$id."'";
                if(isset($is_financial) && $is_financial == 1){
                    $chequeQ .= " AND financial_id = '".$financial_id."'";
                }
                if((isset($from) && $from != '') && (isset($to) && $to != '')){
                    $chequeQ .= " AND voucher_date >= '".$from."' AND voucher_date <= '".$to."' ";
                }
                $chequeR = mysqli_query($conn, $chequeQ);
                if($chequeR && mysqli_num_rows($chequeR) > 0){
                    while ($chequeRow = mysqli_fetch_assoc($chequeR)) {
                        $tmp2['id'] = $chequeRow['id'];
                        $tmp2['date'] = $chequeRow['voucher_date'];
                        $remark = (isset($chequeRow['remark']) && $chequeRow['remark'] != '') ? ' - '.$chequeRow['remark'] : '';
                        $cheque_no = (isset($chequeRow['cheque_no']) && $chequeRow['cheque_no'] != '') ? ' - '.$chequeRow['cheque_no'] : '';
                        if(isset($chequeRow['payment_type']) && $chequeRow['payment_type'] == 'payment'){
                            $tmp2['crdr'] = 'Dr';
                            $tmp2['type'] = 'Payment';
                            $tmp2['credit'] = '';
                            $tmp2['debit'] = (isset($chequeRow['amount']) && $chequeRow['amount'] != '') ? $chequeRow['amount'] : 0;
                            $tmp2['perticular'] = 'Cheque Payment'.$cheque_no.''.$remark;
                        }else{
                            $tmp2['crdr'] = 'Cr';
                            $tmp2['type'] = 'Receipt';
                            $tmp2['credit'] = (isset($chequeRow['amount']) && $chequeRow['amount'] != '') ? $chequeRow['amount'] : 0;
                            $tmp2['debit'] = '';
                            $tmp2['perticular'] = 'Cheque Receipt'.$cheque_no.''.$remark;
                        }
                        $tmp2['voucherno'] = (isset($chequeRow['voucher_no'])) ? $chequeRow['voucher_no'] : '';
                        $data['data'][$tmp2['date']][] = $tmp2;
                    }
                }

                // journal voucher
                $journalVoucherQ = "SELECT jv.id, jv.voucher_date, jv.remarks, jvd.debit, jvd.credit FROM journal_vouchar_details jvd INNER JOIN journal_vouchar jv ON jvd.voucher_id = jv.id WHERE jv.pharmacy_id = '".$pharmacy_id."' AND jvd.particular = '".$id."'";
                if(isset($is_financial) && $is_financial == 1){
                    $journalVoucherQ .= " AND jv.financial_id = '".$financial_id."'";
                }
                if((isset($from) && $from != '') && (isset($to) && $to != '')){
                    $journalVoucherQ .= " AND jv.voucher_date >= '".$from."' AND jv.voucher_date <= '".$to."' ";
                }
                $journalVoucherR = mysqli_query($conn, $journalVoucherQ);
                if($journalVoucherR && mysqli_num_rows($journalVoucherR) > 0){
                    while ($journalVoucherRow = mysqli_fetch_assoc($journalVoucherR)) {
                        $tmp3['id'] = $journalVoucherRow['id'];
                        $tmp3['date'] = $journalVoucherRow['voucher_date'];
                        $remark = (isset($journalVoucherRow['remarks']) && $journalVoucherRow['remarks'] != '') ? ' - '.$journalVoucherRow['remarks'] : '';
                        $tmp3['type'] = 'Journal';
                        $tmp3['perticular'] = 'Journal Voucher'.$remark;
                        if(isset($journalVoucherRow['credit']) && $journalVoucherRow['credit'] != '' && $journalVoucherRow['credit'] != 0){
                            $tmp3['crdr'] = 'Cr';
                            $tmp3['credit'] = (isset($journalVoucherRow['credit']) && $journalVoucherRow['credit'] != '') ? $journalVoucherRow['credit'] : 0;
                            $tmp3['debit'] = '';
                            
                        }else{
                            $tmp3['crdr'] = 'Dr';
                            $tmp3['credit'] = '';
                            $tmp3['debit'] = (isset($journalVoucherRow['debit']) && $journalVoucherRow['debit'] != '') ? $journalVoucherRow['debit'] : 0;
                        }

                        $tmp3['voucherno'] = '';
                        $data['data'][$tmp3['date']][] = $tmp3;
                    }
                }

                $running = countRunningBalance($id, $from, $to);
                $data['running_balance'] = (isset($running['running_balance']) && $running['running_balance'] != '') ? $running['running_balance'] : 0;

                /*if(isset($data['data']) && !empty($data['data'])){
                    function date_compare($a, $b){

                        $t1 = strtotime($a['date']);

                        $t2 = strtotime($b['date']);

                        return $t1 - $t2;
                    }    

                    usort($data['data'], 'date_compare');
                }*/
            }
        }
        return $data;
    }
    /*--------------------------CAPITAL ACCOUNT REPORT - GAUTAM MAKWANA - 12-11-2018 - END-----------------------------*/
    
    /*--------------------------FIXED ASSETS REPORT - GAUTAM MAKWANA - 17-11-2018 - START-----------------------------*/
    function fixedAssetsReport($id = null, $from = null, $to = null, $is_financial = 0){
        global $conn;
        global $pharmacy_id;
        global $financial_id;
        $data = [];

        
        $getLedgerQ = "SELECT id, name, opening_balance, opening_balance_type, depreciation, created FROM ledger_master WHERE pharmacy_id = '".$pharmacy_id."' AND group_id = 17";
        if(isset($id) && $id != ''){
            $getLedgerQ .= " AND id = '".$id."'";
        }
        $getLedgerQ .= " ORDER BY name";
        $getLedgerR = mysqli_query($conn, $getLedgerQ);
        if($getLedgerR && mysqli_num_rows($getLedgerR) > 0){
            while($getLedgerRow = mysqli_fetch_assoc($getLedgerR)){
                $type = (isset($getLedgerRow['opening_balance_type'])) ? $getLedgerRow['opening_balance_type'] : 'DB';
                $opening_balance = (isset($getLedgerRow['opening_balance']) && $getLedgerRow['opening_balance'] != '') ? $getLedgerRow['opening_balance'] : 0;
                $openingDate = (isset($getLedgerRow['created'])) ? date('d-m-Y',strtotime($getLedgerRow['created'])) : '00-00-0000';
                
                $tmp['id'] = $getLedgerRow['id'];
                $tmp['name'] = $getLedgerRow['name'];
                $tmp['opening_balance'] = abs($opening_balance);
                $tmp['type'] = $type;
                $tmp['date'] = (isset($getLedgerRow['created']) && $getLedgerRow['created'] != '') ? date('d-m-Y',strtotime($getLedgerRow['created'])) : '';
                $tmp['detail'] = [];
                
                // cash-management
                $cashManagementQ = "SELECT id, payment_type, voucher_no, voucher_date, amount, remark FROM accounting_cash_management WHERE pharmacy_id = '".$pharmacy_id."' AND perticular = '".$getLedgerRow['id']."'";
                if(isset($is_financial) && $is_financial == 1){
                    $cashManagementQ .= " AND financial_id = '".$financial_id."'";
                }
                if((isset($from) && $from != '') && (isset($to) && $to != '')){
                    $cashManagementQ .= " AND voucher_date >= '".$from."' AND voucher_date <= '".$to."' ";
                }
                $cashManagementQ .=" ORDER BY voucher_date";
                $cashManagementR = mysqli_query($conn, $cashManagementQ);
                if($cashManagementR && mysqli_num_rows($cashManagementR) > 0){
                    while ($cashManagementRow = mysqli_fetch_assoc($cashManagementR)) {
                        $tmp1['id'] = $cashManagementRow['id'];
                        $tmp1['date'] = (isset($cashManagementRow['voucher_date']) && $cashManagementRow['voucher_date'] != '') ? date('d-m-Y',strtotime($cashManagementRow['voucher_date'])) : '00-00-0000';
                        $remark = (isset($cashManagementRow['remark']) && $cashManagementRow['remark'] != '') ? ' - '.$cashManagementRow['remark'] : '';
                        if(isset($cashManagementRow['payment_type']) && $cashManagementRow['payment_type'] == 'cash_payment'){
                            $tmp1['crdr'] = 'Dr';
                            $tmp1['type'] = 'Payment';
                            $tmp1['credit'] = '';
                            $tmp1['debit'] = (isset($cashManagementRow['amount']) && $cashManagementRow['amount'] != '') ? $cashManagementRow['amount'] : 0;
                            $tmp1['perticular'] = 'Cash Payment'.$remark;
                        }else{
                            $tmp1['crdr'] = 'Cr';
                            $tmp1['type'] = 'Receipt';
                            $tmp1['credit'] = (isset($cashManagementRow['amount']) && $cashManagementRow['amount'] != '') ? $cashManagementRow['amount'] : 0;
                            $tmp1['debit'] = '';
                            $tmp1['perticular'] = 'Cash Receipt'.$remark;
                        }
                        $tmp1['voucherno'] = (isset($cashManagementRow['voucher_no'])) ? $cashManagementRow['voucher_no'] : '';
                        $tmp['detail'][$tmp1['date']][] = $tmp1;
                    }
                }
                
                
                // cheque
                $chequeQ = "SELECT id, voucher_no, voucher_date, voucher_type, cheque_no, amount, remark FROM accounting_cheque WHERE pharmacy_id = '".$pharmacy_id."' AND perticular = '".$getLedgerRow['id']."'";
                if(isset($is_financial) && $is_financial == 1){
                    $chequeQ .= " AND financial_id = '".$financial_id."'";
                }
                if((isset($from) && $from != '') && (isset($to) && $to != '')){
                    $chequeQ .= " AND voucher_date >= '".$from."' AND voucher_date <= '".$to."' ";
                }
                $chequeQ .= " ORDER BY voucher_date";
                $chequeR = mysqli_query($conn, $chequeQ);
                if($chequeR && mysqli_num_rows($chequeR) > 0){
                    while ($chequeRow = mysqli_fetch_assoc($chequeR)) {
                        $tmp2['id'] = $chequeRow['id'];
                        $tmp2['date'] = (isset($chequeRow['voucher_date']) && $chequeRow['voucher_date'] != '') ? date('d-m-Y',strtotime($chequeRow['voucher_date'])) : '00-00-0000';
                        $remark = (isset($chequeRow['remark']) && $chequeRow['remark'] != '') ? ' - '.$chequeRow['remark'] : '';
                        $cheque_no = (isset($chequeRow['cheque_no']) && $chequeRow['cheque_no'] != '') ? ' - '.$chequeRow['cheque_no'] : '';
                        if(isset($chequeRow['payment_type']) && $chequeRow['payment_type'] == 'payment'){
                            $tmp2['crdr'] = 'Dr';
                            $tmp2['type'] = 'Payment';
                            $tmp2['credit'] = '';
                            $tmp2['debit'] = (isset($chequeRow['amount']) && $chequeRow['amount'] != '') ? $chequeRow['amount'] : 0;;
                            $tmp2['perticular'] = 'Cheque Payment'.$cheque_no.''.$remark;
                        }else{
                            $tmp2['crdr'] = 'Cr';
                            $tmp2['type'] = 'Receipt';
                            $tmp2['credit'] = (isset($chequeRow['amount']) && $chequeRow['amount'] != '') ? $chequeRow['amount'] : 0;
                            $tmp2['debit'] = '';
                            $tmp2['perticular'] = 'Cheque Receipt'.$cheque_no.''.$remark;
                        }
                        $tmp2['voucherno'] = (isset($chequeRow['voucher_no'])) ? $chequeRow['voucher_no'] : '';
                        $tmp['detail'][$tmp2['date']][] = $tmp2;
                    }
                }
                
                
                // journal voucher
                $journalVoucherQ = "SELECT jv.id, jv.voucher_date, jv.remarks, jvd.debit, jvd.credit FROM journal_vouchar_details jvd INNER JOIN journal_vouchar jv ON jvd.voucher_id = jv.id WHERE jv.pharmacy_id = '".$pharmacy_id."' AND jvd.particular = '".$getLedgerRow['id']."'";
                if(isset($is_financial) && $is_financial == 1){
                    $journalVoucherQ .= " AND jv.financial_id = '".$financial_id."'";
                }
                if((isset($from) && $from != '') && (isset($to) && $to != '')){
                    $journalVoucherQ .= " AND jv.voucher_date >= '".$from."' AND jv.voucher_date <= '".$to."' ";
                }
                $journalVoucherQ .= " ORDER BY jv.voucher_date";
                $journalVoucherR = mysqli_query($conn, $journalVoucherQ);
                if($journalVoucherR && mysqli_num_rows($journalVoucherR) > 0){
                    while ($journalVoucherRow = mysqli_fetch_assoc($journalVoucherR)) {
                        $tmp3['id'] = $journalVoucherRow['id'];
                        $tmp3['date'] = (isset($journalVoucherRow['voucher_date']) && $journalVoucherRow['voucher_date'] != '') ? date('d-m-Y',strtotime($journalVoucherRow['voucher_date'])) : '00-00-0000';
                        $remark = (isset($journalVoucherRow['remarks']) && $journalVoucherRow['remarks'] != '') ? ' - '.$journalVoucherRow['remarks'] : '';
                        $tmp3['type'] = 'Journal';
                        $tmp3['perticular'] = 'Journal Voucher'.$remark;
                        if(isset($journalVoucherRow['credit']) && $journalVoucherRow['credit'] != '' && $journalVoucherRow['credit'] != 0){
                            $tmp3['crdr'] = 'Cr';
                            $tmp3['credit'] = (isset($journalVoucherRow['credit']) && $journalVoucherRow['credit'] != '') ? $journalVoucherRow['credit'] : 0;
                            $tmp3['debit'] = '';
                            
                        }else{
                            $tmp3['crdr'] = 'Dr';
                            $tmp3['credit'] = '';
                            $tmp3['debit'] = (isset($journalVoucherRow['debit']) && $journalVoucherRow['debit'] != '') ? $journalVoucherRow['debit'] : 0;
                        }

                        $tmp3['voucherno'] = '';
                        $tmp['detail'][$tmp3['date']][] = $tmp3;
                    }
                }
                if(isset($getLedgerRow['depreciation']) && $getLedgerRow['depreciation'] > 0){
                    $ghasaradata = getGhasaro($getLedgerRow['id'], $getLedgerRow['depreciation']);
                    if(isset($ghasaradata['ghasaro']) && $ghasaradata['ghasaro'] > 0){
                        $apply_per = (isset($ghasaradata['apply_per']) && $ghasaradata['apply_per'] != '') ? $ghasaradata['apply_per'] : 0;
                        $tmp4['id'] = '';
                        $tmp4['date'] = (isset($ghasaradata['payment_date'])) ? date('d-m-Y',strtotime($ghasaradata['payment_date'])) : '00-00-0000';
                        $tmp4['type'] = 'depreciation';
                        $tmp4['perticular'] = 'Depreciation of '.$apply_per.'%';
                        $tmp4['crdr'] = 'Dr';
                        $tmp4['debit'] = $ghasaradata['ghasaro'];
                        $tmp4['credit'] = '';
                        $tmp4['voucherno'] = '';
                        $tmp['detail'][$tmp4['date']][] = $tmp4;
                    }
                }
                
                $data[] = $tmp;
            }
        }
        
        return $data;
    }
    /*--------------------------FIXED ACCOUNT REPORT - GAUTAM MAKWANA - 12-11-2018 - END-----------------------------*/
    
    function getGhasaro($id = null, $per = 0){
        global $conn;
        global $pharmacy_id;
        $data = [];
        $ghasaro = 0;
        $dateArray = [];

        // Cash Payment
        $cashPaymentQ = "SELECT id, voucher_date, amount, financial_id FROM accounting_cash_management WHERE perticular = '".$id."' AND payment_type = 'cash_payment' AND pharmacy_id = '".$pharmacy_id."' AND amount != 0 AND amount IS NOT NULL ORDER BY voucher_date ASC LIMIT 1";
        $cashPaymentR = mysqli_query($conn, $cashPaymentQ);
        if($cashPaymentR && mysqli_num_rows($cashPaymentR) > 0){
            $cashPaymentRow = mysqli_fetch_assoc($cashPaymentR);
            if(isset($cashPaymentRow['voucher_date']) && $cashPaymentRow['voucher_date'] != ''){
                $tmp1['date'] = $cashPaymentRow['voucher_date'];
                $tmp1['payment'] = (isset($cashPaymentRow['amount']) && $cashPaymentRow['amount'] != '') ? $cashPaymentRow['amount'] : 0;
                $tmp1['financial_id'] = (isset($cashPaymentRow['financial_id'])) ? $cashPaymentRow['financial_id'] : '';
                $dateArray[] = $tmp1;
            }
        }

        // Cheque Payment
        $chequeQ = "SELECT id, voucher_date, amount, financial_id FROM accounting_cheque WHERE perticular = '".$id."' AND voucher_type = 'payment' AND pharmacy_id = '".$pharmacy_id."' AND amount != 0 AND amount IS NOT NULL ORDER BY voucher_date ASC LIMIT 1";
        $chequeR = mysqli_query($conn, $chequeQ);
        if($chequeR && mysqli_num_rows($chequeR) > 0){
            $chequeRow = mysqli_fetch_assoc($chequeR);
            if(isset($chequeRow['voucher_date']) && $chequeRow['voucher_date'] != ''){
                $tmp2['date'] = $chequeRow['voucher_date'];
                $tmp2['payment'] = (isset($chequeRow['amount']) && $chequeRow['amount'] != '') ? $chequeRow['amount'] : 0;
                $tmp2['financial_id'] = (isset($chequeRow['financial_id'])) ? $chequeRow['financial_id'] : '';
                $dateArray[] = $tmp2;
            }
        }

        // Journal Voucher Payment
        $voucherQ = "SELECT jv.id, jv.voucher_date, jv.financial_id, jvd.debit FROM `journal_vouchar_details` jvd INNER JOIN journal_vouchar jv ON jvd.voucher_id = jv.id WHERE jvd.particular = '".$id."' AND jv.pharmacy_id = '".$pharmacy_id."' AND jvd.debit != 0 AND jvd.debit IS NOT NULL ORDER BY jv.voucher_date ASC LIMIT 1";
        $voucherR = mysqli_query($conn, $voucherQ);
        if($voucherR && mysqli_num_rows($voucherR) > 0){
            $voucherRow = mysqli_fetch_assoc($voucherR);
            if(isset($voucherRow['voucher_date']) && $voucherRow['voucher_date'] != ''){
                $tmp3['date'] = $voucherRow['voucher_date'];
                $tmp3['payment'] = (isset($voucherRow['debit']) && $voucherRow['debit'] != '') ? $voucherRow['debit'] : 0;
                $tmp3['financial_id'] = (isset($voucherRow['financial_id'])) ? $voucherRow['financial_id'] : '';
                $dateArray[] = $tmp3;
            }
        }

        if(isset($dateArray) && !empty($dateArray)){
            function date_compare($a, $b){

                $t1 = strtotime($a['date']);

                $t2 = strtotime($b['date']);

                return $t1 - $t2;
            }    

            usort($dateArray, 'date_compare');

            $paymentDate = date('Y-m-d',strtotime($dateArray[0]['date']));
            $financialStartDate = '';

            // find financial start date
            $financialQ = "SELECT id, start_date FROM financial WHERE id = '".$dateArray[0]['financial_id']."'";
            $financialR = mysqli_query($conn, $financialQ);
            if($financialR && mysqli_num_rows($financialR) > 0){
                $financialRow = mysqli_fetch_assoc($financialR);
                $financialStartDate  = date('Y-m-d',strtotime($financialRow['start_date']));
            }
            
            $financialAfterSixMonthDate =  date('Y-m-d',strtotime($financialStartDate." +6 Months"));

            if($paymentDate < $financialAfterSixMonthDate){
                // full ghasaro
                $paymentAmount = (isset($dateArray[0]['payment']) && $dateArray[0]['payment'] != '') ? $dateArray[0]['payment'] : 0;
                if($paymentAmount != 0){
                    $ghasaro = ($paymentAmount*$per/100);
                    $data['ghasaro'] = $ghasaro;
                    $data['per'] = $per;
                    $data['apply_per'] = $per;
                    $data['payment'] = $paymentAmount;
                    $data['payment_date'] = $paymentDate;
                    $data['f_start_date'] = $financialStartDate;
                    $data['f_after_six_month_date'] = $financialStartDate;
                }
            }else{
                //Half Ghasaro
                 $paymentAmount = (isset($dateArray[0]['payment']) && $dateArray[0]['payment'] != '') ? $dateArray[0]['payment'] : 0;
                if($paymentAmount != 0){
                    $halfper = ($per/2);
                    $ghasaro = ($paymentAmount*$halfper/100);
                    $data['ghasaro'] = $ghasaro;
                    $data['per'] = $per;
                    $data['apply_per'] = $halfper;
                    $data['payment'] = $paymentAmount;
                    $data['payment_date'] = $paymentDate;
                    $data['f_start_date'] = $financialStartDate;
                    $data['f_after_six_month_date'] = $financialStartDate;
                }
            }
        }

        return $data;
    }
    
    /*--------------------------CURRENT ASSETS REPORT - GAUTAM MAKWANA - 17-11-2018 - START-----------------------------*/
    function CurrentAssetsReport($id = null, $from = null, $to = null){
        global $conn;
        global $pharmacy_id;
        global $financial_id;
        $data = [];

        if($id != ''){
            $getLedgerQ = "SELECT id, name, opening_balance, opening_balance_type FROM ledger_master WHERE pharmacy_id = '".$pharmacy_id."' AND id = '".$id."'";
            $getLedgerR = mysqli_query($conn, $getLedgerQ);
            if($getLedgerR && mysqli_num_rows($getLedgerR) > 0){
                $getLedgerRow = mysqli_fetch_assoc($getLedgerR);
                $type = (isset($getLedgerRow['opening_balance_type'])) ? $getLedgerRow['opening_balance_type'] : 'DB';
                $opening_balance = (isset($getLedgerRow['opening_balance']) && $getLedgerRow['opening_balance'] != '') ? $getLedgerRow['opening_balance'] : 0;
                $opening_balance = ($type == 'DB') ? $opening_balance : -$opening_balance;
                
                $data['id'] = $getLedgerRow['id'];
                $data['name'] = $getLedgerRow['name'];
                $data['opening_balance'] = $opening_balance;
                $data['from'] = $from;
                $data['to'] = $to;


                // cash-management
                $cashManagementQ = "SELECT id, payment_type, voucher_no, voucher_date, amount, remark FROM accounting_cash_management WHERE pharmacy_id = '".$pharmacy_id."' AND perticular = '".$id."'";
                if((isset($from) && $from != '') && (isset($to) && $to != '')){
                    $cashManagementQ .= " AND voucher_date >= '".$from."' AND voucher_date <= '".$to."' ";
                }
                $cashManagementR = mysqli_query($conn, $cashManagementQ);
                if($cashManagementR && mysqli_num_rows($cashManagementR) > 0){
                    while ($cashManagementRow = mysqli_fetch_assoc($cashManagementR)) {
                        $tmp1['id'] = $cashManagementRow['id'];
                        $tmp1['date'] = $cashManagementRow['voucher_date'];
                        $remark = (isset($cashManagementRow['remark']) && $cashManagementRow['remark'] != '') ? ' - '.$cashManagementRow['remark'] : '';
                        if(isset($cashManagementRow['payment_type']) && $cashManagementRow['payment_type'] == 'cash_payment'){
                            $tmp1['type'] = 'Payment';
                            $tmp1['credit'] = '';
                            $tmp1['debit'] = (isset($cashManagementRow['amount']) && $cashManagementRow['amount'] != '') ? $cashManagementRow['amount'] : 0;
                            $tmp1['perticular'] = 'Cash Payment'.$remark;
                        }else{
                            $tmp1['type'] = 'Receipt';
                            $tmp1['credit'] = (isset($cashManagementRow['amount']) && $cashManagementRow['amount'] != '') ? $cashManagementRow['amount'] : 0;
                            $tmp1['debit'] = '';
                            $tmp1['perticular'] = 'Cash Receipt'.$remark;
                        }
                        $tmp1['voucherno'] = (isset($cashManagementRow['voucher_no'])) ? $cashManagementRow['voucher_no'] : '';
                        $data['data'][] = $tmp1;
                    }
                }

                // cheque
                $chequeQ = "SELECT id, voucher_no, voucher_date, voucher_type, cheque_no, amount, remark FROM accounting_cheque WHERE pharmacy_id = '".$pharmacy_id."' AND perticular = '".$id."'";
                if((isset($from) && $from != '') && (isset($to) && $to != '')){
                    $chequeQ .= " AND voucher_date >= '".$from."' AND voucher_date <= '".$to."' ";
                }
                $chequeR = mysqli_query($conn, $chequeQ);
                if($chequeR && mysqli_num_rows($chequeR) > 0){
                    while ($chequeRow = mysqli_fetch_assoc($chequeR)) {
                        $tmp2['id'] = $chequeRow['id'];
                        $tmp2['date'] = $chequeRow['voucher_date'];
                        $remark = (isset($chequeRow['remark']) && $chequeRow['remark'] != '') ? ' - '.$chequeRow['remark'] : '';
                        $cheque_no = (isset($chequeRow['cheque_no']) && $chequeRow['cheque_no'] != '') ? ' - '.$chequeRow['cheque_no'] : '';
                        if(isset($chequeRow['payment_type']) && $chequeRow['payment_type'] == 'payment'){
                            $tmp2['type'] = 'Payment';
                            $tmp2['credit'] = '';
                            $tmp2['debit'] = (isset($chequeRow['amount']) && $chequeRow['amount'] != '') ? $chequeRow['amount'] : 0;;
                            $tmp2['perticular'] = 'Cheque Payment'.$cheque_no.''.$remark;
                        }else{
                            $tmp2['type'] = 'Receipt';
                            $tmp2['credit'] = (isset($chequeRow['amount']) && $chequeRow['amount'] != '') ? $chequeRow['amount'] : 0;
                            $tmp2['debit'] = '';
                            $tmp2['perticular'] = 'Cheque Receipt'.$cheque_no.''.$remark;
                        }
                        $tmp2['voucherno'] = (isset($chequeRow['voucher_no'])) ? $chequeRow['voucher_no'] : '';
                        $data['data'][] = $tmp2;
                    }
                }

                // journal voucher
                $journalVoucherQ = "SELECT jv.id, jv.voucher_date, jv.remarks, jvd.debit, jvd.credit FROM journal_vouchar_details jvd INNER JOIN journal_vouchar jv ON jvd.voucher_id = jv.id WHERE jv.pharmacy_id = '".$pharmacy_id."' AND jvd.particular = '".$id."'";
                if((isset($from) && $from != '') && (isset($to) && $to != '')){
                    $journalVoucherQ .= " AND jv.voucher_date >= '".$from."' AND jv.voucher_date <= '".$to."' ";
                }
                $journalVoucherR = mysqli_query($conn, $journalVoucherQ);
                if($journalVoucherR && mysqli_num_rows($journalVoucherR) > 0){
                    while ($journalVoucherRow = mysqli_fetch_assoc($journalVoucherR)) {
                        $tmp3['id'] = $journalVoucherRow['id'];
                        $tmp3['date'] = $journalVoucherRow['voucher_date'];
                        $remark = (isset($journalVoucherRow['remarks']) && $journalVoucherRow['remarks'] != '') ? ' - '.$journalVoucherRow['remarks'] : '';
                        $tmp3['type'] = 'Journal';
                        $tmp3['perticular'] = 'Journal Voucher'.$remark;
                        if(isset($journalVoucherRow['credit']) && $journalVoucherRow['credit'] != ''){
                            $tmp3['credit'] = (isset($journalVoucherRow['credit']) && $journalVoucherRow['credit'] != '') ? $journalVoucherRow['credit'] : 0;
                            $tmp3['debit'] = '';
                            
                        }else{
                            $tmp3['credit'] = '';
                            $tmp3['debit'] = (isset($journalVoucherRow['debit']) && $journalVoucherRow['debit'] != '') ? $journalVoucherRow['debit'] : 0;
                        }

                        $tmp3['voucherno'] = '';
                        $data['data'][] = $tmp3;
                    }
                }

                $running = countRunningBalance($id, $from, $to);
                $data['running_balance'] = (isset($running['running_balance']) && $running['running_balance'] != '') ? $running['running_balance'] : 0;

                if(isset($data['data']) && !empty($data['data'])){
                    function date_compare($a, $b){

                        $t1 = strtotime($a['date']);

                        $t2 = strtotime($b['date']);

                        return $t1 - $t2;
                    }    

                    usort($data['data'], 'date_compare');
                }
            }
        }
        
        return $data;
    }
    /*--------------------------CURRENT ACCOUNT REPORT - GAUTAM MAKWANA - 12-11-2018 - END-----------------------------*/
    
    /*--------------------------DOWNLOAD FILE - GAUTAM MAKWANA - 17-11-2018 - START-----------------------------*/
    function download($path = null){
        if(file_exists($path)){
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename='.basename($path));
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($path));
            ob_clean();
            flush();
            readfile($path);
            exit;
        }
    }
    /*--------------------------DOWNLOAD FILE - GAUTAM MAKWANA - 17-11-2018 - END-----------------------------*/
    
     //-----------------------------Transport Report---------------start-----------------------------------
    function GetTranportPurchase($from = null , $to = null, $transport = null){
        global $conn;
        $financial_id = (isset($_SESSION['auth']['financial']) && $_SESSION['auth']['financial'] != '') ? $_SESSION['auth']['financial'] : '';
        
        $purchase = "SELECT * from purchase WHERE transporter_name = '".$transport."' AND financial_id = '".$financial_id."'";
        if((isset($from) && $from != '') && (isset($to) && $to != '')){
         $purchase .= " AND vouchar_date >= '".$from."' AND vouchar_date <= '".$to."'";  
        } 
        $purchaseR = mysqli_query($conn, $purchase);
        if($purchaseR){
        $transportdata = [];
        while ($purchaseL = mysqli_fetch_assoc($purchaseR)){
         $transportdata[] = $purchaseL;
        }
       }
     return $transportdata;
    }
//----------------------------------Transport Report------------------end----------------------------------
//------------------Expense Report ------------start ----------------
    function GetExpenseData($from = null , $to = null, $transport = null){
    global $conn;

    $financial_id = (isset($_SESSION['auth']['financial']) && $_SESSION['auth']['financial'] != '') ? $_SESSION['auth']['financial'] : '';
    if((isset($transport) && $transport != '')){
      $expense = "SELECT * from expense_master WHERE transport = '".$transport."' AND";
    }else{
      $expense = "SELECT * from expense_master WHERE";
    }
    if((isset($from) && $from != '') && (isset($to) && $to != '')){
      $expense .= " ex_date >= '".$from."' AND ex_date <= '".$to."'";  
     } 

   $expenseR = mysqli_query($conn, $expense);
   if($expenseR){
    $expensedata = [];
    while ($expenseL = mysqli_fetch_assoc($expenseR)){
     $expensedata[] = $expenseL;
   }
 }
 return $expensedata;
}

//------------------Expense Report ------------end ----------------

    
    /*--------------------------GET ALL RATE - GAUTAM MAKWANA - 21-11-2018 - START-----------------------------*/
    function getAllRate(){
        global $conn;
        global $pharmacy_id;
        $data = [];
        
        $query = "SELECT id, name, rate, status FROM rate_master WHERE pharmacy_id = '".$pharmacy_id."' AND status = 1";
        $res = mysqli_query($conn, $query);
        if($res && mysqli_num_rows($res) > 0){
            while($row = mysqli_fetch_assoc($res)){
                $data[] = $row;
            }
        }
        return $data;
    }
    
    function getIdByRate($id = null){
        global $conn;
        global $pharmacy_id;
        $rate = 0;
        
        $query = "SELECT id, name, rate, status FROM rate_master WHERE pharmacy_id = '".$pharmacy_id."' AND id = '".$id."'";
        $res = mysqli_query($conn, $query);
        if($res && mysqli_num_rows($res) > 0){
            $row = mysqli_fetch_assoc($res);
            $rate = (isset($row['rate']) && $row['rate'] != '') ? $row['rate'] : '';
        }
        return $rate;
    }
    /*--------------------------GET ALL RATE - GAUTAM MAKWANA - 21-11-2018 - END-----------------------------*/
    function send_text_message($number,$message,$smsContentType='English'){
		global $conn;
		$pharmacy_id = $_SESSION['auth']['pharmacy_id'];
		$qry = "SELECT * FROM `sms_config` WHERE pharmacy = ".$pharmacy_id." AND status = 1";
		$get = mysqli_query($conn,$qry);
		$cData = mysqli_fetch_assoc($get);
		if(mysqli_num_rows($get) > 0){
	        $senderId = $cData['sender_id'];
	        $serverUrl = $cData['server_url'];
	        $authKey = $cData['auth_key'];
	        $routeId="1";
	        $getData = 'mobileNos='.$number.'&message='.urlencode($message).'&senderId='.$senderId.'&routeId='.$routeId.'&smsContentType='.$smsContentType;
	        //API URL

	        //$url = '';

	        $url="http://".$serverUrl."/rest/services/sendSMS/sendGroupSms?AUTH_KEY=".$authKey."&".$getData;

	        // init the resource
	        $ch = curl_init();
	        curl_setopt_array($ch, array(
	            CURLOPT_URL => $url,
	            CURLOPT_RETURNTRANSFER => true,
	            CURLOPT_SSL_VERIFYHOST => 0,
	            CURLOPT_SSL_VERIFYPEER => 0

	        ));

	        //get response

	        $output = curl_exec($ch);

	        //Print error if any

	        if(curl_errno($ch))

	        {
	            $error = curl_error($ch);
	            print_r($error );exit;
	            //return  $error ;
	        }


	        curl_close($ch);
	    }
	    return true;
}
?>