<?php include('include/usertypecheck.php'); ?>
<?php

/**
 * Function used to generate invoice pdf file
 * $user_id - client id for fetching his invoice 
 * $mode - 'D' for download, 'I" for inline open in browser and 'F' for save
 */

function salebillpdf($tax_bill_id,$mode='D'){

    global $conn;

    $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';

    $query = "SELECT tb.*, lg.name as customer_name, lg.addressline1, lg.addressline2, lg.addressline3, lg.gstno,lg.panno,lg.mobile,lg.phone,lg.dl_no1,lg.dl_no2 , st.name as customer_state, st.state_code_gst as statecode, ct.name as customer_city,dp.name as doctor_name , dp.personal_title FROM tax_billing tb LEFT JOIN ledger_master lg ON tb.customer_id = lg.id LEFT JOIN own_states st ON lg.state = st.id LEFT JOIN own_cities ct ON lg.city = ct.id LEFT JOIN doctor_profile dp ON tb.doctor = dp.id WHERE tb.pharmacy_id ='".$pharmacy_id."' AND tb.id='".$tax_bill_id."'";
    $res = mysqli_query($conn, $query);
    if($res && mysqli_num_rows($res) > 0){
        $data = mysqli_fetch_assoc($res);

    /*----------------------------------------------PHARMACY QUERY START-------------------------------------------*/
    if(isset($data['pharmacy_id']) && $data['pharmacy_id'] != ''){
     $pharmacyQ = "SELECT p.id, p.pharmacy_name, p.address1, p.address2, p.address3,p.pin_code ,p.gst_no,p.company_cin_no,ct.name as city,p.mobile_no,p.email,p.logo_url,st.name as state_name,st.state_code_gst as pharmastatecode FROM pharmacy_profile p LEFT JOIN own_states st ON p.stateId = st.id LEFT JOIN own_cities ct ON p.city_name = ct.id WHERE p.id='".$data['pharmacy_id']."'";
     $pharmacyR = mysqli_query($conn, $pharmacyQ);
      if($pharmacyR && mysqli_num_rows($pharmacyR) > 0){
        $data['pharmacy'] = mysqli_fetch_assoc($pharmacyR);
      }
    }
    /*----------------------------------------------PHARMACY QUERY END-------------------------------------------*/

    /*----------------------------------------------PHARMACY BANK DETAIL QUERY START-------------------------------------------*/
    if(isset($data['pharmacy_id']) && $data['pharmacy_id'] != ''){
      $pharmacyBankDetailQ = "SELECT * FROM  ledger_master WHERE group_id IN (5,22) AND pharmacy_id = '".$data['pharmacy_id']."'";
      $pharmacyBankDetailR = mysqli_query($conn, $pharmacyBankDetailQ);
      if($pharmacyBankDetailR && mysqli_num_rows($pharmacyBankDetailR) > 0){
        while ($pharmacyBankDetailRow = mysqli_fetch_assoc($pharmacyBankDetailR)) {
          $data['pharmacy_bank_detail'][] = $pharmacyBankDetailRow;
        }
      }
    }   
    /*----------------------------------------------PHARMACY BANK DETAIL QUERY START-------------------------------------------*/

   /*----------------------------------------------TAX BILLING DETAIL QUERY START-------------------------------------------*/
    if(isset($data['id']) && $data['id'] != ''){
      $taxBillingDetailQ = "SELECT tbd.* , tb.*, pm.product_name, pm.mrp as product_mrp, pm.discount_per, pm.ratio,pm.batch_no, pm.bill_print_view,pm.mfg_company as product_mfg_company,pm.hsn_code as hsn, pm.batch_no as product_batch_no,um.unit_name, pm.ex_date as product_ex_date FROM tax_billing_details tbd INNER JOIN product_master pm ON tbd.product_id = pm.id INNER JOIN tax_billing tb ON tbd.tax_bill_id = tb.id left join unit_master um on pm.unit = um.id WHERE tbd.tax_bill_id = '".$data['id']."'";
      $taxBillingDetailR = mysqli_query($conn, $taxBillingDetailQ);
      if($taxBillingDetailR && mysqli_num_rows($taxBillingDetailR) > 0){
        while ($taxBillingDetailRow = mysqli_fetch_assoc($taxBillingDetailR)) {
          $data['tax_billing_detail'][] = $taxBillingDetailRow;
        //   echo "<pre>";
        //   print_r($data['tax_billing_detail']);
          
        }
      }
    }
    /*----------------------------------------------TAX BILLING DETAIL QUERY END-------------------------------------------*/

    /*----------------------------------------------PRODUCT RETURN DETAIL QUERY START-----------------------------------------*/
    if(isset($data['id']) && $data['id'] != ''){
      $salereturnQ = "SELECT sr.*, pm.product_name, pm.mrp as product_mrp, pm.mfg_company as product_mfg_company, pm.batch_no as product_batch_no, pm.ex_date as product_ex_date FROM sale_return_details sr INNER JOIN product_master pm ON sr.product_id = pm.id WHERE sr.tax_bill_id = '".$data['id']."'";
      $salereturnR = mysqli_query($conn, $salereturnQ);
      if($salereturnR && mysqli_num_rows($salereturnR) > 0){
        while ($salereturnRow = mysqli_fetch_assoc($salereturnR)) {
          $data['sale_return'][] = $salereturnRow;
        }
      }
    }
    /*----------------------------------------------PRODUCT RETURN DETAIL QUERY END-------------------------------------------*/
           
   /*----------------------------------------------Bill Note Remark START-----------------------------------------*/
     $bill_note = "SELECT * FROM bill_note WHERE pharmacy_id = '".$pharmacy_id."'";  
     $bill_noteR = mysqli_query($conn, $bill_note);
      if($bill_noteR && mysqli_num_rows($bill_noteR) > 0){
        while ($bill_noteRow = mysqli_fetch_assoc($bill_noteR)) {
          $data['billnote'] = $bill_noteRow;
          
          
        }  
      }
    /*----------------------------------------------Bill Note Remark END-----------------------------------------*/   
    
    if(isset($data['id']) && $data['id'] != ''){
      $alltotalQ = "SELECT tbd.* , tb.*, pm.product_name, pm.mrp as product_mrp, pm.discount_per, pm.ratio,pm.bill_print_view,pm.mfg_company as product_mfg_company,pm.hsn_code as hsn, pm.batch_no as product_batch_no, pm.ex_date as product_ex_date FROM tax_billing_details tbd INNER JOIN product_master pm ON tbd.product_id = pm.id INNER JOIN tax_billing tb ON tbd.tax_bill_id = tb.id WHERE tbd.tax_bill_id = '".$data['id']."'";
      $alltotalR = mysqli_query($conn, $alltotalQ);
      if($alltotalR && mysqli_num_rows($alltotalR) > 0){
           $grand_total = 0; $product_discount = 0; $Discountcount=0;
        while ($alltotalRow = mysqli_fetch_assoc($alltotalR)) {
        
          $allamount = $alltotalRow['qty'] * $alltotalRow['rate'];
          $grand_total += $allamount;
         
          $Discount = $alltotalRow['discount'];
          $to_discount = ($allamount * $Discount)/100;
          $product_discount += $to_discount; 
          
          $Discountcou = $alltotalRow['discount'];
          $Discountcount += $Discountcou;
        }
      }
    }
    
  }

  
    //include tcpdf library    
    require 'TCPDF-master/tcpdf.php';

    // create new PDF document
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Yamuna Meditech');
    $pdf->SetTitle('Invoice#TI-'.$tax_bill_id);
    $pdf->SetSubject('Tax Invoice');
    $pdf->SetKeywords('Tax Invoice');

    // set default header data
    // $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 061', PDF_HEADER_STRING);

    // remove default header/footer
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);

    // set header and footer fonts
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

    // set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // set margins
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

    // set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    // set image scale factor
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);


    // ------------------ Making Page ------------------

    // set font
    $pdf->SetFont('helvetica', '', 10);
  
    //creating html string
    $html = '';

    $a = 0;

    //Adding Page Content
    foreach(array_chunk($data['tax_billing_detail'], 12) as $billing ) {
    
        //add page after 2 half bill
        if (($a % 2) == 0){ 
            $pdf->AddPage();
        }


    // including normalize css style
    $html .='<style>html{line-height:1.15;-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%}body{margin:0}article,aside,footer,header,nav,section{display:block}h1{font-size:2em;margin:.67em 0}figcaption,figure,main{display:block}figure{margin:1em 40px}hr{box-sizing:content-box;height:0;overflow:visible}pre{font-family:monospace,monospace;font-size:1em}a{background-color:transparent;-webkit-text-decoration-skip:objects}abbr[title]{border-bottom:none;text-decoration:underline;text-decoration:underline dotted}b,strong{font-weight:inherit}b,strong{font-weight:bolder}code,kbd,samp{font-family:monospace,monospace;font-size:1em}dfn{font-style:italic}mark{background-color:#ff0;color:#000}small{font-size:80%}sub,sup{font-size:75%;line-height:0;position:relative;vertical-align:baseline}sub{bottom:-.25em}sup{top:-.5em}audio,video{display:inline-block}audio:not([controls]){display:none;height:0}img{border-style:none}svg:not(:root){overflow:hidden}button,input,optgroup,select,textarea{font-family:sans-serif;font-size:100%;line-height:1.15;margin:0}button,input{overflow:visible}button,select{text-transform:none}[type=reset],[type=submit],button,html [type=button]{-webkit-appearance:button}[type=button]::-moz-focus-inner,[type=reset]::-moz-focus-inner,[type=submit]::-moz-focus-inner,button::-moz-focus-inner{border-style:none;padding:0}[type=button]:-moz-focusring,[type=reset]:-moz-focusring,[type=submit]:-moz-focusring,button:-moz-focusring{outline:1px dotted ButtonText}fieldset{padding:.35em .75em .625em}legend{box-sizing:border-box;color:inherit;display:table;max-width:100%;padding:0;white-space:normal}progress{display:inline-block;vertical-align:baseline}textarea{overflow:auto}[type=checkbox],[type=radio]{box-sizing:border-box;padding:0}[type=number]::-webkit-inner-spin-button,[type=number]::-webkit-outer-spin-button{height:auto}[type=search]{-webkit-appearance:textfield;outline-offset:-2px}[type=search]::-webkit-search-cancel-button,[type=search]::-webkit-search-decoration{-webkit-appearance:none}::-webkit-file-upload-button{-webkit-appearance:button;font:inherit}details,menu{display:block}summary{display:list-item}canvas{display:inline-block}template{display:none}[hidden]{display:none}
    </style>';

    $html .='<style>.sheet,body{margin:0}@page{margin:0}.sheet{overflow:hidden;position:relative;box-sizing:border-box;page-break-after:always}body.A3 .sheet{width:297mm;height:419mm}body.A3.landscape .sheet{width:420mm;height:296mm}body.A4 .sheet{width:210mm;height:296mm}body.A4Half .sheet{width:210mm;height:148mm}body.A4.landscape .sheet{width:297mm;height:209mm}body.A5 .sheet{width:148mm;height:209mm}body.A5.landscape .sheet{width:210mm;height:147mm}body.letter .sheet{width:216mm;height:279mm}body.letter.landscape .sheet{width:280mm;height:215mm}body.legal .sheet{width:216mm;height:356mm}body.legal.landscape .sheet{width:357mm;height:215mm}.sheet.padding-10mm{padding:10mm}.sheet.padding-15mm{padding:15mm}.sheet.padding-20mm{padding:20mm}.sheet.padding-25mm{padding:25mm}@media screen{body{background:#e0e0e0}.sheet{background:#fff;box-shadow:0 .5mm 2mm rgba(0,0,0,.3);margin:5mm auto}}@media print{body.A3.landscape{width:420mm}body.A3,body.A4.landscape{width:297mm}body.A4,body.A4Half,body.A5.landscape{width:210mm}body.A5{width:148mm}body.legal,body.letter{width:216mm}body.letter.landscape{width:280mm}body.legal.landscape{width:357mm}}</style>';

    // $html .= '<style>@page{size:A4Half}</style>';

    $html .= '<style>.customer-info,.invoice-info{padding-left:5px;border-left:1px solid #000}.header-row,.product-row{text-align:center;height:20px}.amount-details,.product-details{width:100%;border-collapse:collapse}.font-bold,.header-row,.total-amount,.total-row td{font-weight:700}*{font-family:"Helvetica Neue",Helvetica,Helvetica,Arial,sans-serif}.company-logo img{width:100%;max-width:80px}.company-info{padding-left:5px}.customer-info{line-height:20px}.amount-details,.header-row,.note,.product-row{font-size:12px}.header-row{border-bottom:1px solid #000}.product-row td:last-child{text-align:right}.total-row{height:20px;border-top:1px solid #000}.total-row td{text-align:center}.total-row td:last-child{text-align:right}.product-details{font-size:12px;height:270px}.note{text-align:left;vertical-align:top}.amount-details{line-height:15px}.amount-details td:nth-child(1){text-align:left;font-weight:700}.amount-details td:nth-child(2){text-align:right}.total-amount{font-size:14px}.forsign,.term{font-size:12px}.term{padding-left:15px;padding-right:15px}.forsign{float:right}</style>';

  
    $html .= '<div style="width: 210mm; height: 148mm;">
                <section class="sheet padding-10mm">
                    <table style="width:100%;border: 1px solid #000;">';
    
    $addressArray = [];
    if(isset($data['pharmacy']['address1']) && $data['pharmacy']['address1'] != ''){
        $addressArray[] = $data['pharmacy']['address1'];
    }
    if(isset($data['pharmacy']['address2']) && $data['pharmacy']['address2'] != ''){
        $addressArray[] = $data['pharmacy']['address2'];
    }
    if(isset($data['pharmacy']['address3']) && $data['pharmacy']['address3'] != ''){
        $addressArray[] = $data['pharmacy']['address3'];
    }
    if(!empty($addressArray)){
        $companyAddress = implode(', ', $addressArray);
        $companyAddress = strtoupper($companyAddress);
    }

    //phramcy and company address
    if(isset($data['pharmacy']['pharmacy_name'])) { $phramcy_name = $data['pharmacy']['pharmacy_name'];}else{ $phramcy_name = '';}
    $companyAddress = (isset($companyAddress)) ? $companyAddress : '';

    $html .='<tr>
                <td class="company-info">
                    <span style="font-size:14px; font-weight: bold;">'.$phramcy_name.'</span><br>';

    $html .='<span style="font-size:12px">'.$companyAddress;
        if(isset($data['pharmacy']['city']) && $data['pharmacy']['city'] != ""){ $comma = "," ; }else{ $comma = '';}
        $city = (isset($data['pharmacy']['city'])) ? $data['pharmacy']['city'] : '';
        if(isset($data['pharmacy']['pin_code']) && $data['pharmacy']['pin_code'] != ""){ $seprator = "-" ; } else { $seprator = "." ; }
        $pincode = (isset($data['pharmacy']['pin_code'])) ? $data['pharmacy']['pin_code'] : '';
    $html .= $comma.$city.$seprator.$pincode;
    $html .= '</span><br>';
                         
    if (isset($data['pharmacy']['mobile_no']) && $data['pharmacy']['mobile_no'] !=''){
        $mobile_no = (isset($data['pharmacy']['mobile_no'])) ? $data['pharmacy']['mobile_no'] : '';
        $html .= '<span style="font-size:12px">M: '.$mobile_no.'</span><br>';
    }
    if (isset($data['pharmacy']['gst_no']) && $data['pharmacy']['gst_no'] !=''){
        $pharmacy_gst_no = (isset($data['pharmacy']['gst_no'])) ? $data['pharmacy']['gst_no'] : '';
        $html .= '<span style="font-size:12px">GSTIN/UIN: '.$pharmacy_gst_no.'</span><br>';
    }
    if (isset($data['pharmacy']['email']) && $data['pharmacy']['email'] !=''){
        $pharmacy_email = (isset($data['pharmacy']['email'])) ? $data['pharmacy']['email'] : '';
        $html .= '<span style="font-size:12px">E-Mail: '.$pharmacy_email.'</span>';
    } 
    
    $invoice_no = (isset($data['invoice_no'])) ? $data['invoice_no'] : '';
    $invoice_date = (isset($data['invoice_date']) && $data['invoice_date'] != '') ? date('d/m/Y',strtotime($data['invoice_date'])) : '';

    $html .= '</td>
                <td class="invoice-info">
                    <span style="font-size:14px; font-weight: bold;">Invoice # '.$invoice_no.'</span><br>
                    <span style="font-size:14px">Date: '.$invoice_date.'</span>
                </td>';
                    if (isset($data['doctor_name']) && $data['doctor_name'] !=''){
                        $personal_title = (isset($data['personal_title'])) ? $data['personal_title'] : '';
                        $doctor_name = (isset($data['doctor_name'])) ? $data['doctor_name'] : '';

                        $html .= '<span style="font-size:14px">Doctor: <b>'.$personal_title.'. '.$doctor_name.'</b></span><br>';
                    }

                    $html .= '<td style="text-align: right;" class="customer-info"><span>&nbsp;</span><br>';
                                    
                    if (isset($data['customer_name']) && $data['customer_name'] !=''){
                        $customer_name = (isset($data['customer_name']) && $data['customer_name'] != '') ? ucwords(strtolower($data['customer_name'])) : '';
                        $html .= '<span style="font-size:14px">Customer: <b>'.$customer_name.'</b></span><br>';
                    }
                    
                    if (isset($data['customer_city']) && $data['customer_city'] !=''){
                        $customer_city = (isset($data['customer_city']) && $data['customer_city'] != '') ? ucwords(strtolower($data['customer_city'])) : '';
                        $html .= '<span style="font-size:14px"> <b>'.$customer_city.'</b></span><br>';
                    }
                    
                    if (isset($data['mobile']) && $data['mobile'] !=''){
                        $mobile = (isset($data['mobile'])) ? $data['mobile'] : '';
                        $html .= '<span style="font-size:12px">M: '.$mobile.'</span><br>';
                    }
                    
                    if (isset($data['email']) && $data['email'] !=''){
                        $email = (isset($data['email'])) ? $data['email'] : '';
                        $html .= '<span style="font-size:12px">E-Mail: '.$email.'</span>';
                    }
    $html .= '</td>
                </tr>
                <tr>
                    <td colspan="3" style="border-top: 1px solid #000;border-bottom: 1px solid #000;padding: 0px;">
                        <table class="product-details">
                            <tr class="header-row">
                                <td>Sr.</td>
                                <td>Particular</td>
                                <td>Pack</td>
                                <td>Mfg.</td>
                                <td>MRP</td>
                                <td>Qty</td>
                                <td>Batch</td>
                                <td>Exp.</td>';
                                if($Discountcount != 0){   
                                    $html .= '<td>Disc%</td>';
                                }
                        $html .= '<td>GST%</td>
                                <td>GST(&#8377)</td>
                                <td style="float: right;">Amount</td>
                            </tr>';
        if(isset($data['tax_billing_detail']) && !empty($data['tax_billing_detail'])){
          
            $total_qty = 0;
            $total_free_qty = 0;
            $total_rate = 0;
            $total_amount = 0;
            $total_gst = 0;
            $all_discount = 0;
        				
            foreach ($billing as $key => $value) {
                $html .= '<tr class="product-row">
                                <td>';$sr_no = $key+1;
                        $html .= $sr_no.'</td>';
                        $html .= '<td>';
                        $product_name = (isset($value['product_name'])) ? ucfirst(strtolower($value['product_name'])) : '';
                                
                        $html .= $product_name.'</td>';
                        $ratio = (isset($value['ratio'])) ? ucfirst(strtolower($value['ratio'])) : '';
                        $unit_name = (isset($value['unit_name'])) ? strtoupper(strtolower($value['unit_name'])) : '';
                        $html .='<td>'.$ratio.$unit_name.'</td>';

                    if($value['product_mfg_company'] != ''){
                        $mfg_company = (isset($value['product_mfg_company'])) ? substr($value['product_mfg_company'],0,5)   : '';
                        $html .='<td>'.$mfg_company.'</td>';
                     } else {
                        $bill_print_view = (isset($value['bill_print_view'])) ? $value['bill_print_view'] : '';
                        $html .= '<td>'.$bill_print_view.'</td>';
                    }
                        $mrp = (isset($value['mrp'])) ? $value['mrp'] : '';
                        $html .= '<td>'.$mrp.'</td>';

                        $qty =  (isset($value['qty']) && $value['qty'] != '') ? $value['qty'] : 0;
                        $total_qty += $qty;
                        $round_qty =  round($qty, 2); 

                        $html .= '<td>'.$round_qty.'</td>';
                        $batch_no = (isset($value['batch_no'])) ? $value['batch_no'] : '-';
                        $html .= '<td>'.$batch_no.'</td>';
                        $product_ex_date = (isset($value['product_ex_date']) && $value['product_ex_date'] != '0000-00-00') ? date('m/y',strtotime($value['product_ex_date'])) : '-';
                        $html .= '<td>'.$product_ex_date.'</td>';
                              
                        if ($Discountcount != 0){
                            $discount = (isset($value['discount'])) ? $value['discount'] : '';
                            $html .= '<td>'.$discount.'</td>';
                        }
                        $gst = (isset($value['gst'])) ? $value['gst'] : '';
                        $html .= '<td>'.$gst.'</td>';

                        $gst_tax = (isset($value['gst_tax']) && $value['gst_tax'] != '' ) ? $value['gst_tax'] : ''; 
                        $total_gst += $gst_tax;

                        $html .= '<td>'.amount_format(number_format((round($gst_tax, 2)), 2, '.', '')).'</td>';

                        $amount = (isset($value['qty']) && $value['qty'] != '' && isset($value['rate']) && $value['rate'] !='') ? $value['qty'] * $value['rate'] : '0';
                        $total_amount += $amount; 
                        
                        $html .= '<td>'.amount_format(number_format((round($amount, 2)), 2, '.', '')).'</td></tr>';
                            
            }
        }
    
        if(isset($key) && $key!=''){  
            $availablerow = $key+1;
        } else{
            $availablerow = 0;
        }
        
        $addrow = 12 - $availablerow;

        for($i =1 ;$i<=$addrow; $i++){
            $html .= '<tr class="product-row">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td> 
                        <td></td>';
        if ($Discountcount != 0){
            $html .= '<td></td>'; 
        }
                $html .='<td></td>              
                        <td></td>
                        <td></td>
                    </tr>';
        }
      
        $html .= '<tfoot>
                    <tr class="total-row">
                        <td colspan="5">
                            Total
                        </td>
                        <td>';
                        $qty2 = (isset($total_qty)) ? round($total_qty, 2) : 0; 
        $html .= $qty2.'</td>
                        <td></td>
                        <td></td>';

                        if ($Discountcount != 0){
                            $html .= '<td></td>';
                        }
        $html .= '<td></td>';
        $round_gst = (isset($total_gst)) ? round($total_gst, 2) : 0;

        $html .= '<td>'.$round_gst.'</td>';
        $amount_format = (isset($total_amount)) ? amount_format(number_format((round($total_amount, 2)), 2, '.', '')) : 0;
        $html .= '<td>'.$amount_format.'</td>
                    </tr>
                </tfoot>
                        </table>        
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <table style="width: 100%;">
                            <tr>
                                <td>
                                    <span class="note">GET WELL SOON</span><br>';
                                    $fetched_bill_note = (isset($data['billnote']['bill_note']))? $data['billnote']['bill_note']:'';
                                    $html .= '<span class="note font-bold">'.$fetched_bill_note.'</span><br>    
                                    <span class="note">E. & O.E.</span>
                                </td>
                                <td style="vertical-align: top;width: 150px;">
                                    <table class="amount-details">';
                                       if($product_discount != 0){
                                        $p_discount = (isset($product_discount)) ? $product_discount : '0';
                                        
                                        $html .= '<tr>
                                            <td>Discount :</td>
                                            <td>'.$p_discount.'</td>
                                            </tr>';
                                        }
                                        
                                        if($value['discount_rs'] != 0){
                                            $discount_rs = (isset($value['discount_rs'])) ? $value['discount_rs'] : '';
                                            $html .= '<tr>
                                                        <td>Total Discount :</td>
                                                        <td>'.$discount_rs.'</td>
                                                    </tr>';
                                        }

                                        $grand_total_format = (isset($grand_total)) ? amount_format(number_format((round($grand_total, 2)), 2, '.', '')) : 0;
                                        $html .= '<tr>
                                            <td>Grand Total :</td>
                                            <td class="total-amount">'.$grand_total_format.'</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>';
                $html .= '<tr>
                    <td colspan="3" style="border-top: 1px solid #000;padding: 0px;">
                        <table style="width: 100%;">
                            <tr style="height: 23px;">
                                <td></td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="term">SUBJECT TO JUNAGADH JURISDICTION</span>';
                                    $date_display = date('d/m/Y h:i:s A');
                                    $html .= '<span class="term">'.$date_display.'</span>';
                                    $pharmacy_name = (isset($data['pharmacy']['pharmacy_name'])) ? $data['pharmacy']['pharmacy_name'] : '';

                                    $html .= '<span class="forsign" style="margin-top: 5px;">For, '.$pharmacy_name.'</span>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
      </section>
    </div>';

    $a++;
    }

    // output the HTML content
    $pdf->writeHTML($html);

    $pdf->lastPage();

    //Close and output PDF document I - for opening in browser, D for download and F for save to path
    ob_clean();
    $filename ='TI-'.$tax_bill_id.'-'.time().'.pdf';

    //F for saving a file, D for download and I for inline view
    if($mode == 'F'){

        $dir_name =__DIR__."/invoicepdf"; 

        //make directory if not exists
        if (!is_dir($dir_name. "/")) {
            mkdir($dir_name . "/", 0755);
        }

		$path = __DIR__."/invoicepdf/".$filename;
        $pdf->Output($path, 'F');
        return $path;
    }elseif($mode == 'D'){
        $pdf->Output($filename, 'D');
    }elseif($mode == 'I'){
        $pdf->Output($filename, 'I');
    }

}

echo $salebill = salebillpdf($_GET['id'],$mode='F');
