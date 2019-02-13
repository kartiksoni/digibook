<?php include('include/usertypecheck.php'); ?>

<!-------------------------------------------Quation EMAIL---- STRAT--------------------------->
<?php


if((isset($_REQUEST['id']) && $_REQUEST['id'] != '')) {
    $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';
  $quation_id = $_REQUEST['id'];
  
   $query = "SELECT qt.*,lg.name as customer_name, lg.email, lg.addressline1, lg.addressline2, lg.addressline3, lg.gstno,st.name as customer_state, ct.name as customer_city FROM quotation qt LEFT JOIN ledger_master lg ON qt.customer_id = lg.id LEFT JOIN own_states st ON lg.state = st.id LEFT JOIN own_cities ct ON lg.city = ct.id  WHERE qt.pharmacy_id ='".$pharmacy_id."' AND qt.id='".$quation_id."'";
      $res = mysqli_query($conn, $query);
        if($res && mysqli_num_rows($res) > 0){
            $data = mysqli_fetch_assoc($res);
            
            
             /*----------------------------------------------PHARMACY QUERY START-------------------------------------------*/
            if(isset($data['pharmacy_id']) && $data['pharmacy_id'] != ''){
                $pharmacyQ = "SELECT p.id, p.pharmacy_name, p.address1, p.address2, p.address3, p.gst_no,st.name as state_name FROM pharmacy_profile p LEFT JOIN own_states st ON p.stateId = st.id WHERE p.id='".$data['pharmacy_id']."'";
                $pharmacyR = mysqli_query($conn, $pharmacyQ);
                if($pharmacyR && mysqli_num_rows($pharmacyR) > 0){
                    $data['pharmacy'] = mysqli_fetch_assoc($pharmacyR);
                   
                    
                }
            }
            /*----------------------------------------------PHARMACY QUERY END-------------------------------------------*/

            /*----------------------------------------------PHARMACY BANK DETAIL QUERY START-------------------------------------------*/
            if(isset($data['pharmacy_id']) && $data['pharmacy_id'] != ''){
                $pharmacyBankDetailQ = "SELECT id, bank_name, ifsc_code, account_number FROM pharmacy_bank_details WHERE pharmacy_id = '".$data['pharmacy_id']."'";
                $pharmacyBankDetailR = mysqli_query($conn, $pharmacyBankDetailQ);
                if($pharmacyBankDetailR && mysqli_num_rows($pharmacyBankDetailR) > 0){
                    while ($pharmacyBankDetailRow = mysqli_fetch_assoc($pharmacyBankDetailR)) {
                        $data['pharmacy_bank_detail'][] = $pharmacyBankDetailRow;
                       
                        
                    }
                }
            }   
            /*----------------------------------------------PHARMACY BANK DETAIL QUERY START-------------------------------------------*/

            /*----------------------------------------------Quotation Details QUERY START-------------------------------------------*/
                if(isset($_REQUEST['id']) && $_REQUEST['id'] != ''){
                    $taxBillingDetailQ = "SELECT qd.*, pm.product_name, pm.mrp as product_mrp, pm.mfg_company as product_mfg_company, pm.batch_no as product_batch_no, pm.ex_date as product_ex_date FROM quotation_details qd INNER JOIN product_master pm ON qd.product_id = pm.id WHERE qd.tax_bill_id = '".$quation_id."'";
                    
                    $taxBillingDetailR = mysqli_query($conn, $taxBillingDetailQ);
                    if($taxBillingDetailR && mysqli_num_rows($taxBillingDetailR) > 0){
                        while ($taxBillingDetailRow = mysqli_fetch_assoc($taxBillingDetailR)) {
                            $data['tax_billing_detail'][] = $taxBillingDetailRow;
                           
                        }
                    }
                }
            /*----------------------------------------------Quotation Details QUERY END-------------------------------------------*/

            /*----------------------------------------------PRODUCT RETURN DETAIL QUERY START-----------------------------------------*/
            if(isset($_REQUEST['id']) && $_REQUEST['id'] != ''){
                $salereturnQ = "SELECT sr.*, pm.product_name, pm.mrp as product_mrp, pm.mfg_company as product_mfg_company, pm.batch_no as product_batch_no, pm.ex_date as product_ex_date FROM sale_return sr INNER JOIN product_master pm ON sr.product_id = pm.id WHERE sr.tax_bill_id = '".$quation_id."'";
                $salereturnR = mysqli_query($conn, $salereturnQ);
                if($salereturnR && mysqli_num_rows($salereturnR) > 0){
                    while ($salereturnRow = mysqli_fetch_assoc($salereturnR)) {
                        $data['sale_return'][] = $salereturnRow;
                       
                    }
                }
            }
            /*----------------------------------------------PRODUCT RETURN DETAIL QUERY END-------------------------------------------*/
            
        }
      $html = '<html> <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><style>
      @media print {
	  @page { margin: 0; }
	  body { margin: 1.5cm;font-family:Verdana, Arial, Helvetica, sans-serif; }
	}
	@page { margin: 0; }
	@page {
	   size: 7in 9.25in;
	   margin: 25mm 16mm 27mm 16mm;
	}
	@page { margin: 0; }
	
    .invoice-box{
        max-width:595px;
        margin:auto;
        padding:0px;
        /*border:1px solid #eee;
        box-shadow:0 0 10px rgba(0, 0, 0, .15);*/
        font-size:11px;
        line-height:22px;
        font-family:"Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
        color:#555;
	    margin-top: 20px;
		line-height:23px;
    }
    
    .invoice-box table{
        width:100%;
        line-height:inherit;
        text-align:left;
    }
    table td.center
	{ text-align:center}
	table td.right
	{ text-align:right}
	table td.left
	{ text-align:left}
    .invoice-box table td{
        padding:2px 5px;
        vertical-align:middle;
    }
     .invoice-box table tr td:nth-child(1){
	 	/*width:10px;*/
	 }
	 .invoice-box table tr.information td:nth-child(1){
	 	width:70%;
		
	 }
	 .invoice-box table tr.information td:nth-child(2){
	 	width:50%;  text-align:right;
		
	 }
    .invoice-box table tr td:nth-child(5),.invoice-box table tr td:nth-child(6){
        text-align:right;
    }
   .invoice-box table tr.heading td:nth-child(1)
	{
		width:10px !important;
	}
	 .invoice-box table tr.item td:nth-child(1)
	{
		width:10px !important;
	}
	
    .invoice-box table tr.top table td{
      
    }
    
    .invoice-box table tr.top table td.title{
        font-size:35px;
        line-height:35px;
        color:#333;
    }
    
    .invoice-box table tr.information table td{
        /*padding-bottom:20px;*/
    }
    
    .invoice-box table tr.heading td{
        background:#D6D6D6;
		color:#000;
        border-bottom:1px solid #000;
		text-align:center;

    }
    
    .invoice-box table tr.details td{
       /* padding-bottom:20px;*/
    }
    
    .invoice-box table tr.item td{
		height:10px;
    }
    
    .invoice-box table tr.item.last td{
       /* border-bottom:none;*/
    }
    
    .invoice-box table tr.total td:nth-child(5), .invoice-box table tr.total td:nth-child(6){
        border-top:2px solid #000;
        font-weight:bold;
    }
    td label 
	{
		width : 50px;
	}
    @media only screen and (max-width: 600px) {
        .invoice-box table tr.top table td{
            width:100%;
            display:block;
            text-align:center;
        }
        
        .invoice-box table tr.information table td{
            width:100%;
            display:block;
            text-align:center;
        }
    }
	
	.company-title
	{
		text-align:center;
		font-size:22px;
		font-weight:bold;
		text-transform:uppercase;
		font-family:Verdana, Arial, Helvetica, sans-serif;
	}
	.company-address
	{
		text-align:center;
		font-size:14px;
		text-transform:capitalize;
		font-family:Verdana, Arial, Helvetica, sans-serif;
		margin:3px 0;
	}
	.company-gst
	{
		text-align:center;
		font-size:14px;
		text-transform:capitalize;
		font-weight:bold;
		font-family:Verdana, Arial, Helvetica, sans-serif;
		margin:2px 0;
	}</style></head>'; 
	
	        $pharmacy_name = (isset($data['pharmacy']['pharmacy_name'])) ? $data['pharmacy']['pharmacy_name'] : ''; 
       $html .='<body><div class="invoice-box">
        
		<div class="company-title" style="	text-align:center;
		font-size:22px;
		font-weight:bold;
		text-transform:uppercase;
		font-family:Verdana, Arial, Helvetica, sans-serif;">'.$pharmacy_name.'</div>';
         
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
                $companyAddress = ucwords(strtolower($companyAddress));
            }
            $CompanyAddress = (isset($companyAddress)) ? $companyAddress : '';
            $gst_no = (isset($data['pharmacy']['gst_no'])) ? $data['pharmacy']['gst_no'] : ''; 
            
            
        $html .='<div class="company-address" style="text-align:center;
		font-size:14px;
		text-transform:capitalize;
		font-family:Verdana, Arial, Helvetica, sans-serif;
		margin:3px 0;">Address:'.$CompanyAddress.'</div>
		
        <div class="company-gst" style= "text-align:center;
		font-size:14px;
		text-transform:capitalize;
		font-weight:bold;
		font-family:Verdana, Arial, Helvetica, sans-serif;
		margin:2px 0;">GSTNo :'.$gst_no.'</div>';
        
       $html .= '<table cellpadding="0" border="1"  cellspacing="0">';
       $html .='<tr class="top">
                <td colspan="2" style="padding:0px;border-bottom:none;">
                    <table  cellpadding="0" cellspacing="0" border="0" style="line-height:13px;font-size:9px;height: 30px;" rules="rows" frame="below">
                        <tr>
							<th rowspan="3" align="center" style="text-align:center;font-size:22px;;font-weight:bold;width:70%;border-left:none;">TAX INVOICE</th>
						</tr>                                      
					</table>
				</td>
				
            </tr>';
            
       $html .='<tr class="" style="line-height:12px;">
                <td  style="vertical-align:top;">
                    <table  cellpadding="0" cellspacing="0" border="0" style="line-height:10px;font-size:9px;">
						<tr>     
                            <td>
                            		<b style="width:100px;float:left;">Invoice No. </b> 
                                    <div style="width:150px;float:left;">:'.$data['invoice_no'].'</div>
                            </td>
                        </tr>
                        <tr>     
                            <td>
                            		<b style="width:100px;float:left;">Invoice Date </b> 
                                    <div style="width:150px;float:left;">:'.date('d-M-Y',strtotime($data['invoice_date'])) .'</div>
                            </td>
                        </tr>

                        <tr>     
                            <td>
                                    <b style="width:100px;float:left;">Bill Type </b> 
                                    <div style="width:150px;float:left;">:'.$data['bill_type'].'</div>
                            </td>
                        </tr>
                      
                        <tr>     
                            <td>
                                    <b style="width:100px;float:left;">Pharmacy State </b> 
                                    <div style="width:150px;float:left;">:'.$data['pharmacy']['state_name'].'</div>
                            </td>
                        </tr>
                    </table>
                </td>
				<td style="text-align:left">

                	<table  cellpadding="0" cellspacing="0" border="0"  style="line-height:10px;font-size:9px;">
						<tr>     
                            <td>
                            		<b style="width:100px;float:left;">Customer </b> 
                                    <div style="width:150px;float:left;">:'.ucwords(strtolower($data['customer_name'])).'</div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                    <b style="width:100px;float:left;">GSTIN </b> 
                                    <div style="width:150px;float:left;">:'.$data['gstno'].'</div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                    <b style="width:100px;float:left;">Address </b>';
                                        $customerAddArr = [];
                                        if(isset($data['addressline1']) && $data['addressline1'] != ''){
                                            $customerAddArr[] = $data['addressline1'];
                                        }
                                        if(isset($data['addressline2']) && $data['addressline2'] != ''){
                                            $customerAddArr[] = $data['addressline2'];
                                        }
                                        if(isset($data['addressline3']) && $data['addressline3'] != ''){
                                            $customerAddArr[] = $data['addressline3'];
                                        }
                                        if(!empty($customerAddArr)){
                                            $customerAddress = implode(', ', $customerAddArr); 
                                        }
                                    
       $html .= '<div style="width:150px;float:left;">:'.$customerAddress.'</div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                    <b style="width:100px;float:left;">City </b> 
                                    <div style="width:150px;float:left;">:'.$data['customer_city'].'</div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                    <b style="width:100px;float:left;">State </b> 
                                    <div style="width:150px;float:left;">:'.$data['customer_state'].'</div>
                            </td>
                        </tr>
                    </table>
					
				</td>
            </tr>';
           
       $html .='<tr class="">
            	<td colspan="2" style="height:13px;"></td>
            </tr>';
           
       $html .='<tr class="top">
                <td colspan="2" style="padding:0px;border-bottom:none;">
                   <table cellpadding="10" cellspacing="0" border="1" style="border: 0px solid;font-size:8px;line-height:10px;" frame="below" rules="cols" >					
						<tr class="heading" align="center"> 
							<td style="width:10px;" rowspan="2">Sr. No.</td>
							<td rowspan="2" style="width:100px;">Prouduct Name</td>
							<td rowspan="2">MRP</td>
                            <td rowspan="2">MFG Co.</td>
                            <td rowspan="2">Batch</td>
                            <td rowspan="2">Expiry</td>
							<td rowspan="2">Qty</td>
                            <td rowspan="2">Free Qty</td>
							<td rowspan="2">Rate</td>
                            <td rowspan="2">Less Discount</td>
                            <td rowspan="2">Taxable Value</td>
                            <td colspan="2" style="text-align:center">CGST</td>
                            <td colspan="2" style="text-align:center">SGST</td>
                            <td colspan="2"  style="text-align:center">IGST</td>
                            <td rowspan="2" align="center">Total</td>                            
						</tr>
                        <tr class="heading">
                        	<td>Rate</td>
                            <td>Amount</td>
                        	<td>Rate</td>
                            <td>Amount</td>
                        	<td>Rate</td>
                            <td>Amount</td>                                                                                    
                        </tr>
						
						<tbody style="margin-top:10px; height:100px;">';

                   	 if(isset($data['tax_billing_detail']) && !empty($data['tax_billing_detail'])){
                               
                                    $total_qty = 0;$total_free_qty = 0;$total_rate = 0;$total_discount = 0;$total_taxable_amount = 0;$total_cgst_amount = 0;$total_sgst_amount = 0;$total_igst_amount = 0;$total_amount = 0;
                              				
    						     foreach ($data['tax_billing_detail'] as $key => $value) {
            			$html .= '<tr class="item">
            							<td>';
            				$html .=  $key+1;
            				$html .='</td>
            							<td>'.$value['product_name'].'</td>
            							<td>'.$value['product_mrp'].'</td>
                                        <td>'.$value['product_mfg_company'].'</td>
                                        <td>'.$value['product_batch_no'].'</td>
                                        <td>'.date('m/Y',strtotime($value['product_ex_date'])).'</td>';
                                           
                                                $qty =  (isset($value['qty']) && $value['qty'] != '') ? $value['qty'] : 0;
                                                $total_qty += $qty;
                                                $QTY = round($qty, 2);
                                                
                            $html .=  '<td align="right">'.$QTY.'</td>';
                                            
                                                $free_qty = (isset($value['freeqty']) && $value['freeqty'] != '') ? $value['freeqty'] : 0;
                                                $total_free_qty += $free_qty;
                                                $Free_Qty = round($free_qty, 2);
                                     
            				$html .= '<td align="right">'.$Free_Qty.'</td>';
            							
                                                $rate =  (isset($value['rate']) && $value['rate'] != '') ? $value['rate'] : 0;
                                                $total_rate += $rate;
                                                $RATE = round($rate, 2);
                                                
                            $html .= '<td align="right">'.$RATE.'</td>';
            							
                                                $discount =  (isset($value['discount']) && $value['discount'] != '') ? $value['discount'] : 0;
                                                $total_discount += $discount;
                                                $DISCOUNT = round($discount, 2);
                                               
                            $html .= '<td align="right">'.$DISCOUNT.'</td>';
            							
                                                $taxable_amount = (isset($value['totalamount']) && $value['totalamount'] != '') ? $value['totalamount'] : 0; 
                                                $total_taxable_amount += $taxable_amount;
                                                $Taxable_AMount = round($taxable_amount, 2);
                                                
                            $html .='<td style="background-color:#D6D6D6" align="right">'.$Taxable_AMount.'</td>';
                                               
                                               $cgst = (isset($value['cgst']) && $value['cgst'] != '') ? $value['cgst'] : 0;
                                                $CGST = round($cgst, 2).' %';
                                               
                            $html .= '<td>'.$CGST.'</td>';
                                        
                                                $cgst_amount = ($taxable_amount*$cgst/100);
                                                $total_cgst_amount += $cgst_amount;
                                                $CGST_Amount = round($cgst_amount, 2);
                                                
                            $html .= '<td align="right">'.$CGST_Amount.'</td>';
                                       
                                                $sgst = (isset($value['sgst']) && $value['sgst'] != '') ? $value['sgst'] : 0;
                                                $SGST = round($sgst, 2).' %';
                                                
                                                
                            $html .= ' <td>'.$SGST.'</td>';
                                    
                                                $sgst_amount = ($taxable_amount*$sgst/100);
                                                $total_sgst_amount += $sgst_amount;
                                                $SGST_AMount = round($sgst_amount, 2);
                                                
                            $html .= '<td align="right">'.$SGST_AMount.'</td>';
                                        
                                                $igst = (isset($value['igst']) && $value['igst'] != '') ? $value['igst'] : 0;
                                                $IGST = round($igst, 2).' %';
                                                
                            $html .= '<td>'.$IGST.'</td>';
                                       
                                                $igst_amount = ($taxable_amount*$igst/100);
                                                $total_igst_amount += $igst_amount;
                                                $IGST_AMount = round($igst_amount, 2);
                                                
                            $html .= ' <td align="right">'.$IGST_AMount.'</td>';
                                        
                                                $amount = (isset($value['totalamount']) && $value['totalamount'] != '') ? $value['totalamount'] : 0;
                                                $amount = $amount + $total_cgst_amount + $total_sgst_amount + $total_igst_amount;
                                                $total_amount += $amount;
                                                $AMOUNT = round($amount, 2);
                                                
                            $html .= '<td style="background-color:#D6D6D6;text-align: right;">'.$AMOUNT.'</td>                            
            						</tr>';
                                } 
                             } 
                                   $total_qty= (isset($total_qty)) ? round($total_qty, 2) : 0 ;
                                   $total_free_qty = (isset($total_free_qty)) ? round($total_free_qty, 2) : 0 ;
                                   $total_rate = (isset($total_rate)) ? round($total_rate, 2) : 0 ;
                                   $total_discount =  (isset($total_discount)) ? round($total_discount, 2) : 0 ;
                                   $total_taxable_amount = (isset($total_taxable_amount)) ? round($total_taxable_amount, 2) : 0 ;
                                   $total_cgst_amount=  (isset($total_cgst_amount)) ? round($total_cgst_amount, 2) : 0 ;
                                   $total_sgst_amount = (isset($total_sgst_amount)) ? round($total_sgst_amount, 2) : 0 ;
                                   $total_igst_amount = (isset($total_igst_amount)) ? round($total_igst_amount, 2) : 0 ;
                                    $total_amount = (isset($total_amount)) ? round($total_amount, 2) : 0 ;
                            $html .= '<tr class="heading" style="background-color:#D6D6D6;border-top:1px solid #181818;"> 
    							<td colspan="6" style="text-align: right;">Total</td>
    							<td style="text-align: right;">'.$total_qty.'</td>
    							<td style="text-align: right;">'.$total_free_qty.'</td>
    							<td style="text-align: right;">'.$total_rate.'</td>
    							<td style="text-align: right;">'.$total_discount.'</td>
    							<td style="text-align: right;">'.$total_taxable_amount.'</td>
    							<td colspan="2" style="text-align: right;">'.$total_cgst_amount.'</td>
    							<td colspan="2" style="text-align: right;">'.$total_sgst_amount.'</td>
    							<td colspan="2" style="text-align: right;">'.$total_igst_amount.'</td>
    							<td style="text-align: right;">'.$total_amount.'</td>
    						</tr>
						
						</tbody>
              		</table>
				</td>
            </tr>';
         

          
                    if(isset($data['sale_return']) && !empty($data['sale_return'])){
       $html .=  '<tr class="">
                    <td colspan="2" align="center" style="height:13px;">PRODUCT RETURN</td>
                </tr>
                
                <tr class="top">
                    <td colspan="2" style="padding:0px;border-bottom:none;">
                       <table cellpadding="10" cellspacing="0" border="1" style="border: 0px solid;font-size:8px;line-height:10px;" frame="below" rules="cols" >                    
                            <tr class="heading" align="center"> 
                                <td style="width:10px;" rowspan="2">Sr. No.</td>
                                <td rowspan="2" style="width:100px;">Prouduct Name</td>
                                <td rowspan="2">MRP</td>
                                <td rowspan="2">MFG Co.</td>
                                <td rowspan="2">Batch</td>
                                <td rowspan="2">Expiry</td>
                                <td rowspan="2">Qty</td>
                                <td rowspan="2">Rate</td>
                                <td rowspan="2">Less Discount</td>
                                <td rowspan="2">Taxable Value</td>
                                <td colspan="2" style="text-align:center">CGST</td>
                                <td colspan="2" style="text-align:center">SGST</td>
                                <td colspan="2"  style="text-align:center">IGST</td>
                                <td rowspan="2" align="center">Total</td>                            
                            </tr>
                            <tr class="heading">
                                <td>Rate</td>
                                <td>Amount</td>
                                <td>Rate</td>
                                <td>Amount</td>
                                <td>Rate</td>
                                <td>Amount</td>                                                                                    
                            </tr>
                            
                            <tbody style="margin-top:10px; height:100px;">';
                                foreach ($data['sale_return'] as $key => $value) {
                                    
                                  $r_total_qty = 0;$r_total_free_qty = 0;$r_total_rate = 0;$r_total_discount = 0;$r_total_taxable_amount = 0;$r_total_cgst_amount = 0;$r_total_sgst_amount = 0;$r_total_igst_amount = 0;$r_total_amount = 0;
                                    
                                $html .= '<tr class="item">
                                            <td>'.$key.'+1;</td>
                                            <td>'.(isset($value['product_name'])) ? $value['product_name'] : '' .'</td>
                                            <td>'.(isset($value['product_mrp'])) ? $value['product_mrp'] : '' .'</td>
                                            <td>'.(isset($value['product_mfg_company'])) ? $value['product_mfg_company'] : '' .'</td>
                                            <td>'.(isset($value['product_batch_no'])) ? $value['product_batch_no'] : '' .'</td>
                                            <td>'.(isset($value['product_ex_date']) && $value['product_ex_date'] != '') ? date('d/m/Y',strtotime($value['product_ex_date'])) : '' .'</td>';
                                           
                                           
                                           $r_qty =  (isset($value['qty']) && $value['qty'] != '') ? $value['qty'] : 0;
                                                    $r_total_qty += $r_qty;
                                                    $R_Qty =  round($r_qty, 2); 
                                           
                                $html .= '<td align="right">'.$R_Qty.'</td>';
                                           
                                             $r_rate =  (isset($value['rate']) && $value['rate'] != '') ? $value['rate'] : 0;
                                                    $r_total_rate += $r_rate;
                                                    $R_Rate =  round($r_rate, 2);
                                           
                                $html .='<td align="right">'.$R_Rate.'</td>';
                                          
                                          $r_discount =  (isset($value['discount']) && $value['discount'] != '') ? $value['discount'] : 0;
                                                    $r_total_discount += $r_discount;
                                                    $R_Discount = round($r_discount, 2);
                                          
                                $html .='<td align="right">'.$R_Discount.'</td>';            
                                          
                                            $r_taxable_amount = (isset($value['amount']) && $value['amount'] != '') ? $value['amount'] : 0; 
                                                    $r_total_taxable_amount += $r_taxable_amount;
                                                    $R_Taxable_Amount = round($r_taxable_amount, 2);
                                           
                                $html .='<td style="background-color:#D6D6D6" align="right">'.(isset($r_total_taxable_amount)) ? round($r_total_taxable_amount, 2) : 0 .'</td>';           
                                            
                                               $r_cgst = (isset($value['cgst']) && $value['cgst'] != '') ? $value['cgst'] : 0;
                                                    $R_cgst = round($r_cgst, 2);
                                                    
                                $html .='</td>'.$R_cgst.' % </td>';
                                            
                                               $r_cgst_amount = ($r_taxable_amount*$r_cgst/100);
                                                    $r_total_cgst_amount += $r_cgst_amount;
                                                    $R_Cgst_Amount =  round($r_cgst_amount, 2);
                                                    
                                $html .='<td align="right">'.(isset($r_total_cgst_amount)) ? round($r_total_cgst_amount, 2) : 0 .'</td>';
                                                  
                                              $r_sgst = (isset($value['sgst']) && $value['sgst'] != '') ? $value['sgst'] : 0;
                                                    $R_Sgst = round($r_sgst, 2);
                                                    
                                $html .='<td>'.$R_Sgst.'% </td>'.
                                              
                                               $r_sgst_amount = ($r_taxable_amount*$r_sgst/100);
                                                    $r_total_sgst_amount += $r_sgst_amount;
                                                    $R_Sgst_Amount = round($r_total_sgst_amount, 2);
                                                    
                                $html .='<td align="right">'.(isset($r_total_sgst_amount)) ? round($r_total_sgst_amount, 2) : 0 .'</td>';
                                                 
                                               $r_igst = (isset($value['igst']) && $value['igst'] != '') ? $value['igst'] : 0;
                                                    $R_Igst =  round($r_igst, 2);    
                                                
                                $html .='<td>'.$R_Igst.' % </td>'; 
                                                    
                                               $r_igst_amount = ($r_taxable_amount*$r_igst/100);
                                                    $r_total_igst_amount += $r_igst_amount;
                                                    $R_Igst_Amount = round($r_igst_amount, 2);
                                                    
                                $html .='<td align="right">'.(isset($r_total_igst_amount)) ? round($r_total_igst_amount, 2) : 0 .'</td>';
                                                   
                                                $r_amount = (isset($value['amount']) && $value['amount'] != '') ? $value['amount'] : 0;
                                                    $r_total_amount += $r_amount;
                                                    $R_Total_Amount =  round($r_total_amount, 2);
                                $html .='<td style="background-color:#D6D6D6;text-align: right;">'.(isset($r_total_amount)) ? round($r_total_amount, 2) : 0 .'</td></tr>';
                                             
                                      }
                            
                                 $html .='<tr class="heading" style="background-color:#D6D6D6;border-top:1px solid #181818;"> 
                                    <td colspan="6" style="text-align: right;">Total</td>
                                    <td style="text-align: right;">'.(isset($r_total_qty)) ? round($r_total_qty, 2) : 0 .'</td>
                                    <td style="text-align: right;">'.(isset($r_total_rate)) ? round($r_total_rate, 2) : 0 .'</td>
                                    <td style="text-align: right;">'.(isset($r_total_discount)) ? round($r_total_discount, 2) : 0 .'</td>
                                    <td style="text-align: right;">'.(isset($r_total_taxable_amount)) ? round($r_total_taxable_amount, 2) : 0 .'</td>      
                                    <td colspan="2" style="text-align: right;">'.(isset($r_total_cgst_amount)) ? round($r_total_cgst_amount, 2) : 0 .'</td>
                                    <td colspan="2" style="text-align: right;">'.(isset($r_total_sgst_amount)) ? round($r_total_sgst_amount, 2) : 0 .'</td>
                                    <td colspan="2" style="text-align: right;">'.(isset($r_total_igst_amount)) ? round($r_total_igst_amount, 2) : 0 .'</td>
                                    <td style="text-align: right;">'.(isset($r_total_amount)) ? round($r_total_amount, 2) : 0 .'</td>                            
                                </tr>
                            
                            </tbody>
                        </table>
                    </td>
                </tr>';
             }
            // <!----------------------------------------------FOR PRODUCT RETURN SECTION END-------------------------------------------->
         
         
        	$html .='<tr class="">
            	<td colspan="2" style="height:10px;"></td>
            </tr>';
            
            // <!----------------------------------------------FOR COUNT TOTAL AMOUNT SECTION START-------------------------------------------->
            $html .='<tr class="">
                	<td colspan="2" style="text-align:right;padding:0px;">
                      <table cellpadding="0" cellspacing="0" border="1" style="font-size:8px;line-height:10px;" frame="box" rules="all">';
                 
                         $final_amount = (isset($data['final_amount']) && $data['final_amount'] != '') ? number_to_word($data['final_amount']) : 'Zero' ;
                         $alltotalamount = (isset($data['alltotalamount']) && $data['alltotalamount'] != '') ? round($data['alltotalamount'], 2) : 0 ;
                         $total_return_amount = (isset($data['total_return_amount']) && $data['total_return_amount'] != '') ? round($data['total_return_amount'], 2) : 0 ;
                 
                $html .='<tr>					
                 	<td align="center" colspan="2">
						"Total Invoice Amount in Words:<br> 
                    	<b>'.$final_amount.'</b>
                    </td>
                    <td align="right" style="text-align:right;padding:0px;" rowspan="3">
                    	<table cellpadding="10" cellspacing="0" border="1" style="font-size:8px;line-height:10px;" frame="box" rules="cols" >
                            <tr style="background-color:#D6D6D6">
                                <td><div style="width:130px;float:left;">Total Amount Befor Tax</div>:</td>
                                <td align="right">'.$alltotalamount.'</td>
                            </tr>
                            <tr style="background-color:#D6D6D6">
                                <td><div style="width:130px;float:left;">Total Return Amount Befor Tax</div>:</td>
                                <td align="right">'.$total_return_amount.'</td>
                            </tr>
                            <tr>';
                                    $courierPer = (isset($data['couriercharge']) && $data['couriercharge'] != '') ? $data['couriercharge'] : 0;
                                    $courierValue = (isset($data['couriercharge_val']) && $data['couriercharge_val'] != '') ? $data['couriercharge_val'] : 0 ;
                            
                $html .= '<td><div style="width:130px;float:left;">Freight/Courier Charge :'.$courierPer.' % </div>: Amount :'.$courierValue.'</td>';
                                
                                        $calculateCourierPerval = ($courierValue*$courierPer/100);
                                        $finalCourierCharge = ($courierValue+$calculateCourierPerval);
                                       $FinalCourierCharge =  round($finalCourierCharge, 2);
                $html .= '<td align="right">'.$FinalCourierCharge.'</td></tr>';
                                
                              $totalcgst = (isset($data['totalcgst']) && $data['totalcgst'] != '') ? round($data['totalcgst'], 2) : 0 ;
                              $totalsgst = (isset($data['totalsgst']) && $data['totalsgst'] != '') ? round($data['totalsgst'], 2) : 0 ;
                              $totaligst = (isset($data['totaligst']) && $data['totaligst'] != '') ? round($data['totaligst'], 2) : 0 ;
                              $totaltaxgst = (isset($data['totaltaxgst']) && $data['totaltaxgst'] != '') ? round($data['totaltaxgst'], 2) : 0 ;
                   
                $html .='<tr>
                            	<td><div style="width:130px;float:left;">Add CGST</div>:</td>
                                <td align="right">'.$totalcgst.'</td>
                            </tr>
                            <tr>
                            	<td><div style="width:130px;float:left;">Add SGST</div>:</td>
                                <td align="right">'.$totalsgst.'</td>
                            </tr>
                            <tr>
                            	<td><div style="width:130px;float:left;">Add IGST</div>:</td>
                                <td align="right">'.$totaligst.'</td>
                            </tr>
                            <tr style="background-color:#D6D6D6">
                            	<td><div style="width:130px;float:left;">Tax Amount GST</div>:</td>
                                <td align="right">'.$totaltaxgst.'</td>
                            </tr>
                            <tr>';
                                    if(isset($data['discount_type']) && $data['discount_type'] == 'rs'){
                                        $disSign = 'Rs';
                                        $disAmount = (isset($data['discount_rs']) && $data['discount_rs'] != '') ? round($data['discount_rs'], 2) : 0;
                                    }elseif(isset($data['discount_type']) && $data['discount_type'] == 'per'){
                                        $disSign = '%';
                                        $disAmount = (isset($data['discount_per']) && $data['discount_per'] != '') ? round($data['discount_per'], 2) : 0;
                                    }
                                      $DisSign  =(isset($disSign)) ? '('.$disSign.')' : '';
                                      
                                       $disAmount =  (isset($disAmount)) ? $disAmount : 0 ;
                                 $html .='<td>
                                    <div style="width:130px;float:left;">Discount'.$DisSign.'</div>:
                                </td>
                                <td align="right">'.$disAmount.'</td>
                            </tr>
                            <tr>'; 
                                    $notevalue = (isset($data['cr_db_type']) && $data['cr_db_type'] == 'credit') ? 'Credit Note' : 'Debit Note';
                                    $noteAmount = (isset($data['cr_db_val']) && $data['cr_db_val'] != '') ? $data['cr_db_val'] : 0;
                                    $final_amount = (isset($data['final_amount']) && $data['final_amount'] != '') ? round($data['final_amount'], 2) : 0 ;
                                    $pharmacy_name = (isset($data['pharmacy']['pharmacy_name'])) ? $data['pharmacy']['pharmacy_name'] : '';
                                    
                    $html .='<td><div style="width:130px;float:left;">'.$notevalue.'</div>:</td>
                                <td align="right">'.$noteAmount.'
                                </td>
                            </tr>
                            <tr style="background-color:#DEDEDE;color:#070707">
                            	<td><div style="width:130px;float:left;font-weight:bold;">Total Amount After Tax</div>:</td>
                                <td align="right" style="font-weight:bold;">'.$final_amount.'</td>
                            </tr>
                             <tr >
                            	<td colspan="2" style="border:1px solid #DBDBDB;">&nbsp;</td>
                            </tr>
                           
                            <tr>
                            	<td colspan="2" style="vertical-align:top;">
                                Certified that the particulars given above are true and correct,
                                <center><b>For,'.$pharmacy_name.'</b></center>
                                <div style="vertical-align:bottom;font-size:8px;text-align:center;margin-top:20px;">Authorised Signatory</div>
                                </td>
                            </tr>
                        </table>
                    </td>
                 </tr>
                 
                 <tr>
                 	<td style="font-size:8px;">
                    	<center><b>: Bank Details :</b></center>';

                         if(isset($data['pharmacy_bank_detail']) && !empty($data['pharmacy_bank_detail'])){
                            foreach ($data['pharmacy_bank_detail'] as $key => $value) {
                                $account_number = (isset($value['account_number'])) ? $value['account_number'] : ''; 
                                $ifsc_code = (isset($value['ifsc_code'])) ? $value['ifsc_code'] : '';
                    $html .='<div style="margin-top:10px;">
        	                        <div style="width:100px;float:left">Bank Account Number</div>: &nbsp;&nbsp;   ['.$account_number.']
                                </div>    
                                <div style="margin-top:10px;clear:both;">
        	                         <div style="width:100px;float:left;">Bank Branch IFSC</div>: &nbsp;&nbsp;   ['.$ifsc_code.']
                                </div>';
                                } 
                         }      
                    
                    $html .='</td>
                    <td rowspan="2" style="vertical-align:bottom;font-size:8px;text-align:center">Common Seal</td>                   
                 </tr>   
                 
                 
                 <tr>
                 	<td style="height:50px;vertical-align:top;font-size:8px;"><center><b>: Terms and Conditions :</b></center></td>
                 </tr>
                 </table>
                </td>
            </tr>
            <!----------------------------------------------FOR COUNT TOTAL AMOUNT SECTION END-------------------------------------------->
			
        </table> </div></body></html>';
        
    
        $sent = smtpmail($data['email'], '', '', 'Quotation', $html, '', '');
                        
                      if($sent){
                        $_SESSION['msg']['success'] = 'Mail send successfully.';
                      header('location:view-quotation.php');exit;
                      }else{
                         $_SESSION['msg']['fail'] = 'Mail Send Fail! Please Try Again.';
                     header('location:view-quotation.php');exit;
                      }
 
          } 
 ?>
 
  <!-------------------------------------------Quation EMAIL---- END--------------------------->

<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>View Quotation</title>
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
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="css/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="images/favicon.png" />
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
          <span id="errormsg"></span>
          <div class="row">
        
            
            <!-- Product Master Form -->
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">View Quotation</h4>
                  <hr class="alert-dark">
                  <br>
                  <div class="col mt-3">
                       <div class="row">
                            <div class="col-12">
                              <div class="table-responsive">
                                <table class="table datatable">
                                  <thead>
                                    <tr>
                                        <th>Sr No</th>
                                        <th>Invoice Date</th>
                                        <th>Name</th>
                                        <th>Invoice No</th>
                                        <th>Bill Type</th>
                                        <th>Amount</th>
                                        <th>Action</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <?php
                                    $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : NULL;
                                    $financial_id = (isset($_SESSION['auth']['financial']) && $_SESSION['auth']['financial'] != '') ? $_SESSION['auth']['financial'] : NULL;
                                      $qry = "SELECT tb.id, tb.invoice_date, tb.invoice_no, tb.bill_type, tb.final_amount, lgr.name as customer_name FROM quotation tb INNER JOIN ledger_master lgr ON tb.customer_id = lgr.id WHERE tb.pharmacy_id = '".$pharmacy_id."' AND tb.financial_id = '".$financial_id."' ORDER BY tb.id DESC";
                                      $res = mysqli_query($conn, $qry);
                                      if($res){
                                        $i = 1;
                                        while ($row = mysqli_fetch_array($res)) {
                                    ?>
                                      <tr>
                                          <td><?php echo $i; ?></td>
                                          <td><?php echo (isset($row['invoice_date']) && $row['invoice_date'] != '') ? date('d/m/Y',strtotime($row['invoice_date'])) : ''; ?></td>
                                          <td><?php echo (isset($row['customer_name']) && $row['customer_name'] != '') ? ucwords(strtolower($row['customer_name'])) : ''; ?></td>
                                          <td><?php echo (isset($row['invoice_no'])) ? $row['invoice_no'] : ''; ?></td>
                                          <td><?php echo (isset($row['bill_type'])) ? $row['bill_type'] : ''; ?></td>
                                          <td><?php echo (isset($row['final_amount'])) ? $row['final_amount'] : ''; ?></td>
                                          <td>
                                            <a class="btn btn-behance p-2" href="quotation.php?id=<?php echo $row['id']; ?>" title="edit"><i class="fa fa-pencil mr-0"></i></a>
                                            <a class="btn btn-primary p-2" href="print_quotation.php?id=<?php echo $row['id']; ?>"  title="Print" target="_blank"><i class="fa fa-print mr-0"></i></a>
                                            <a class="btn btn-primary p-2" href="view-quotation.php?id=<?php echo $row['id']; ?>"  title="Email"><i class="fa fa-envelope mr-0"></i></a>
                                          </td>
                                      </tr>
                                    <?php
                                      $i++;
                                        }
                                      }
                                    ?>
                                  </tbody>
                                </table>
                              </div>
                            </div>
                          </div>
                    </div>
                </div>
              </div>
            </div>
            
         
       
            
          </div>
        </div>
        <!-- content-wrapper ends -->
        
        <!-- partial:partials/_footer.php -->
        <?php include "include/footer.php" ?>
        <!-- partial -->
        
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  
  

  
  
  

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
  
  <!-- Custom js for this page-->
  <script src="js/modal-demo.js"></script>
  <!-- Custom js for this page-->
  <script src="js/data-table.js"></script> 
  
  <script>
     $('.datatable').DataTable();
  </script>
  
  
  <!--    Toast Notification -->
  <script src="js/toast.js"></script>
  <?php include('include/flash.php'); ?>
  <!-- End custom js for this page-->
</body>


</html>
