<?php include('include/usertypecheck.php'); ?>
<?php 
    if(isset($_GET['id']) && $_GET['id'] != ''){
        $pharmacy_id = (isset($_SESSION['auth']['pharmacy_id'])) ? $_SESSION['auth']['pharmacy_id'] : '';

        $query = "SELECT sos.id, sos.customer_name, sos.city_id, sos.is_general_sale, sos.pharmacy_id, sos.invoice_no, sos.invoice_date, sos.bill_type, sos.total_return_amount, sos.alltotalamount, sos.couriercharge, sos.couriercharge_val, sos.totaligst, sos.totalcgst, sos.totalsgst, sos.totaltaxgst, sos.discount_type, sos.discount_per, sos.discount_rs, sos.cr_db_type, sos.cr_db_val, sos.final_amount, lg.name as l_customer_name, lg.addressline1, lg.addressline2, lg.addressline3, lg.gstno,st.name as customer_state, ct.name as customer_city FROM sale_of_service sos LEFT JOIN ledger_master lg ON sos.customer_id = lg.id LEFT JOIN own_states st ON lg.state = st.id LEFT JOIN own_cities ct ON lg.city = ct.id WHERE sos.pharmacy_id ='".$pharmacy_id."' AND sos.id='".$_GET['id']."'";
        
        $res = mysqli_query($conn, $query);
        if($res && mysqli_num_rows($res) > 0){
            $data = mysqli_fetch_assoc($res);
            
            if(isset($data['is_general_sale']) && $data['is_general_sale'] == 1){
                $data['l_customer_name'] = (isset($data['customer_name'])) ? $data['customer_name'] : '';
                if(isset($data['city_id']) && $data['city_id'] != ''){
                    $getCityQ = "SELECT ct.id, ct.name, st.name as state_name FROM own_cities ct LEFT JOIN own_states st ON ct.state_id = st.id WHERE ct.id = '".$data['city_id']."'";
                    $getCityR = mysqli_query($conn, $getCityQ);
                    if($getCityR && mysqli_num_rows($getCityR) > 0){
                        $getCityRow = mysqli_fetch_assoc($getCityR);
                        $data['customer_city'] = (isset($getCityRow['name'])) ? $getCityRow['name'] : '';
                        $data['customer_state'] = (isset($getCityRow['state_name'])) ? $getCityRow['state_name'] : '';
                    }
                }
                
            }

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

            /*----------------------------------------------TAX BILLING DETAIL QUERY START-------------------------------------------*/
                if(isset($data['id']) && $data['id'] != ''){
                    $taxBillingDetailQ = "SELECT sosd.*, sm.name, sm.inward_rate as rate, sm.sac_code as sac_code  FROM sale_of_service_details sosd INNER JOIN service_master sm ON sosd.service_id = sm.id WHERE sosd.service_bill_id = '".$data['id']."'";
                    
                    $taxBillingDetailR = mysqli_query($conn, $taxBillingDetailQ);
                    if($taxBillingDetailR && mysqli_num_rows($taxBillingDetailR) > 0){
                        while ($taxBillingDetailRow = mysqli_fetch_assoc($taxBillingDetailR)) {
                            $data['tax_billing_detail'][] = $taxBillingDetailRow;
                        }
                    }
                }
            /*----------------------------------------------TAX BILLING DETAIL QUERY END-------------------------------------------*/

            /*----------------------------------------------PRODUCT RETURN DETAIL QUERY START-----------------------------------------*/
            if(isset($data['id']) && $data['id'] != ''){
                $salereturnQ = "SELECT sr.*, pm.product_name, pm.mrp as product_mrp, pm.mfg_company as product_mfg_company, pm.batch_no as product_batch_no, pm.ex_date as product_ex_date FROM sale_return sr INNER JOIN product_master pm ON sr.product_id = pm.id WHERE sr.tax_bill_id = '".$data['id']."'";
                $salereturnR = mysqli_query($conn, $salereturnQ);
                if($salereturnR && mysqli_num_rows($salereturnR) > 0){
                    while ($salereturnRow = mysqli_fetch_assoc($salereturnR)) {
                        $data['sale_return'][] = $salereturnRow;
                    }
                }
            }
            /*----------------------------------------------PRODUCT RETURN DETAIL QUERY END-------------------------------------------*/
            
        }
    }

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
   
    <title> TAX Invoice </title>  
    <link rel="shortcut icon" href="assets/images/favicon.png">          
    <link rel="stylesheet" href="css/bill.css">
</head>
<body>
    <div class="invoice-box">
        
		<div class="company-title"><?php echo (isset($data['pharmacy']['pharmacy_name'])) ? $data['pharmacy']['pharmacy_name'] : ''; ?></div>
        <?php 
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
        ?>
        <div class="company-address">Address: <?php echo (isset($companyAddress)) ? $companyAddress : ''; ?></div>
        <div class="company-gst">GSTNo : <?php echo (isset($data['pharmacy']['gst_no'])) ? $data['pharmacy']['gst_no'] : ''; ?></div>
        
        
        
        
        
        
        
        <table cellpadding="0" border="1"  cellspacing="0">
            <tr class="top">
                <td colspan="2" style="padding:0px;border-bottom:none;">
                    <table  cellpadding="0" cellspacing="0" border="0" style="line-height:13px;font-size:9px;height: 30px;"  rules="rows" frame="below">
                        <tr>
							<th rowspan="3" align="center" style="text-align:center;font-size:22px;;font-weight:bold;width:70%;border-left:none;">TAX INVOICE</th>
							<!-- <th style="width:10px;"></th>
                            <th>Original for Receipient</th>   -->                          
						</tr>
                       <!--  <tr>							
							<th></th>
                            <th>Duplicate for Suppiler/Transporter</th>                            
						</tr>                        
                        <tr>							
							<th></th>
                            <th>Triplicate for Suppiler</th>                            
						</tr> -->                                                
					</table>
				</td>
				
            </tr>
            
            <tr class="" style="line-height:12px;">
                <td  style="vertical-align:top;">
                    <table  cellpadding="0" cellspacing="0" border="0" style="line-height:10px;font-size:9px;">
						<tr>     
                            <td>
                            		<b style="width:100px;float:left;">Invoice No. </b> 
                                    <div style="width:150px;float:left;">: <?php echo (isset($data['invoice_no'])) ? $data['invoice_no'] : ''; ?></div>
                            </td>
                        </tr>
                        <tr>     
                            <td>
                            		<b style="width:100px;float:left;">Invoice Date </b> 
                                    <div style="width:150px;float:left;">: <?php echo (isset($data['invoice_date']) && $data['invoice_date'] != '') ? date('d-M-Y',strtotime($data['invoice_date'])) : ''; ?></div>
                            </td>
                        </tr>

                        <tr>     
                            <td>
                                    <b style="width:100px;float:left;">Bill Type </b> 
                                    <div style="width:150px;float:left;">: <?php echo (isset($data['bill_type'])) ? $data['bill_type'] : ''; ?></div>
                            </td>
                        </tr>
                       
                        <tr>     
                            <td>
                                    <b style="width:100px;float:left;">Pharmacy State </b> 
                                    <div style="width:150px;float:left;">: <?php echo (isset($data['pharmacy']['state_name'])) ? $data['pharmacy']['state_name'] : ''; ?></div>
                            </td>
                        </tr>
                    </table>
                </td>
				<td style="text-align:left">

                	<table  cellpadding="0" cellspacing="0" border="0"  style="line-height:10px;font-size:9px;">
						<tr>     
                            <td>
                            		<b style="width:100px;float:left;">Customer </b> 
                                    <div style="width:150px;float:left;">: <?php echo (isset($data['l_customer_name']) && $data['l_customer_name'] != '') ? ucwords(strtolower($data['l_customer_name'])) : ''; ?></div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                    <b style="width:100px;float:left;">GSTIN </b> 
                                    <div style="width:150px;float:left;">: <?php echo (isset($data['gstno'])) ? $data['gstno'] : ''; ?></div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                    <b style="width:100px;float:left;">Address </b>
                                    <?php 
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
                                    ?>
                                    <div style="width:150px;float:left;">: <?php echo (isset($customerAddress)) ? $customerAddress : ''; ?></div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                    <b style="width:100px;float:left;">City </b> 
                                    <div style="width:150px;float:left;">: <?php echo (isset($data['customer_city'])) ? $data['customer_city'] : ''; ?></div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                    <b style="width:100px;float:left;">State </b> 
                                    <div style="width:150px;float:left;">: <?php echo (isset($data['customer_state'])) ? $data['customer_state'] : ''; ?></div>
                            </td>
                        </tr>
                    </table>
					
				</td>
            </tr>
           
            <tr class="">
            	<td colspan="2" style="height:13px;"></td>
            </tr>
           
            <!----------------------------------------------FOR PRODUCT SECTION START-------------------------------------------->
            <tr class="top">
                <td colspan="2" style="padding:0px;border-bottom:none;">
                   <table cellpadding="10" cellspacing="0" border="1" style="border: 0px solid;font-size:8px;line-height:10px;" frame="below" rules="cols" >					
						<tr class="heading" align="center"> 
							<td style="width:10px;" rowspan="2">Sr. No.</td>
							<td rowspan="2" style="width:100px;">Service Name</td>
							<td rowspan="2">Rate</td>
                            <td rowspan="2">SAC Code</td>
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
						
						<tbody style="margin-top:10px; height:100px;">

                            <?php if(isset($data['tax_billing_detail']) && !empty($data['tax_billing_detail'])){ ?>
                                <?php 
                                    $total_qty = 0;$total_free_qty = 0;$total_rate = 0;$total_discount = 0;$total_taxable_amount = 0;$total_cgst_amount = 0;$total_sgst_amount = 0;$total_igst_amount = 0;$total_amount = 0;
                                ?>				
    						    <?php foreach ($data['tax_billing_detail'] as $key => $value) { ?>
            						<tr class="item">
            							<td><?php echo $key+1; ?></td>
            							<td><?php echo (isset($value['name'])) ? $value['name'] : ''; ?></td>
            							<td><?php echo (isset($value['rate'])) ? $value['rate'] : ''; ?></td>
                                        <td><?php echo (isset($value['sac_code'])) ? $value['sac_code'] : ''; ?></td>
                                       
            							<td style="background-color:#D6D6D6" align="right">
                                            <?php
                                                $taxable_amount = (isset($value['totalamount']) && $value['totalamount'] != '') ? $value['totalamount'] : 0; 
                                                $total_taxable_amount += $taxable_amount;
                                                echo round($taxable_amount, 2);
                                            ?>
                                        </td>
                                        <td>
                                            <?php 
                                                $cgst = (isset($value['cgst']) && $value['cgst'] != '') ? $value['cgst'] : 0;
                                                echo round($cgst, 2).' %';
                                            ?>
                                        </td>
                                        <td align="right">
                                            <?php 
                                                $cgst_amount = ($taxable_amount*$cgst/100);
                                                $total_cgst_amount += $cgst_amount;
                                                echo round($cgst_amount, 2);
                                            ?>
                                        </td>
                                        <td>
                                            <?php 
                                                $sgst = (isset($value['sgst']) && $value['sgst'] != '') ? $value['sgst'] : 0;
                                                echo round($sgst, 2).' %';
                                            ?>
                                        </td>
                                        <td align="right">
                                            <?php 
                                                $sgst_amount = ($taxable_amount*$sgst/100);
                                                $total_sgst_amount += $sgst_amount;
                                                echo round($sgst_amount, 2);
                                            ?>
                                        </td>
                                        <td>
                                            <?php 
                                                $igst = (isset($value['igst']) && $value['igst'] != '') ? $value['igst'] : 0;
                                                echo round($igst, 2).' %';
                                            ?>
                                        </td>
                                        <td align="right">
                                            <?php 
                                                $igst_amount = ($taxable_amount*$igst/100);
                                                $total_igst_amount += $igst_amount;
                                                echo round($igst_amount, 2);
                                            ?>
                                        </td>
                                        <td style="background-color:#D6D6D6;text-align: right;">
                                            <?php 
                                                $amount = (isset($value['totalamount']) && $value['totalamount'] != '') ? $value['totalamount'] : 0;
                                                $amount = $amount + $total_cgst_amount + $total_sgst_amount + $total_igst_amount;
                                                $total_amount += $amount;
                                                echo round($amount, 2);
                                            ?>
                                        </td>                            
            						</tr>
                                <?php } ?>
                            <?php } ?>
                        
                            <tr class="heading" style="background-color:#D6D6D6;border-top:1px solid #181818;"> 
    							<td colspan = "4" style="text-align: right;">Total</td>
                                <td style="text-align: right;"><?php echo (isset($total_taxable_amount)) ? round($total_taxable_amount, 2) : 0; ?></td>      
                                <td colspan="2" style="text-align: right;"><?php echo (isset($total_cgst_amount)) ? round($total_cgst_amount, 2) : 0; ?></td>
                                <td colspan="2" style="text-align: right;"><?php echo (isset($total_sgst_amount)) ? round($total_sgst_amount, 2) : 0; ?></td>
                                <td colspan="2" style="text-align: right;"><?php echo (isset($total_igst_amount)) ? round($total_igst_amount, 2) : 0; ?></td>
                                <td style="text-align: right;"><?php echo (isset($total_amount)) ? round($total_amount, 2) : 0; ?></td>
    						</tr>
						
						</tbody>
              		</table>
				</td>
            </tr>
            <!----------------------------------------------FOR PRODUCT SECTION END-------------------------------------------->
         
        	 <tr class="">
            	<td colspan="2" style="height:10px;"></td>
            </tr>
            
            <!----------------------------------------------FOR COUNT TOTAL AMOUNT SECTION START-------------------------------------------->
            <tr class="">
            	<td colspan="2" style="text-align:right;padding:0px;">
                 <table cellpadding="0" cellspacing="0" border="1" style="font-size:8px;line-height:10px;" frame="box" rules="all">
                  <tr>					
                 	<td align="center" colspan="2">
						"Total Invoice Amount in Words:<br> 
                    	<b><?php echo (isset($data['final_amount']) && $data['final_amount'] != '') ? number_to_word($data['final_amount']) : 'Zero'; ?></b>
                    </td>
                    <td align="right" style="text-align:right;padding:0px;" rowspan="3">
                    	<table cellpadding="10" cellspacing="0" border="1" style="font-size:8px;line-height:10px;" frame="box" rules="cols" >
                            <tr style="background-color:#D6D6D6">
                                <td><div style="width:130px;float:left;">Total Amount Befor Tax</div>:</td>
                                <td align="right"><?php echo (isset($data['alltotalamount']) && $data['alltotalamount'] != '') ? round($data['alltotalamount'], 2) : 0; ?></td>
                            </tr>
                            <tr style="background-color:#D6D6D6">
                                <td><div style="width:130px;float:left;">Total Return Amount Befor Tax</div>:</td>
                                <td align="right"><?php echo (isset($data['total_return_amount']) && $data['total_return_amount'] != '') ? round($data['total_return_amount'], 2) : 0; ?></td>
                            </tr>
                            <tr>
                                <?php 
                                    $courierPer = (isset($data['couriercharge']) && $data['couriercharge'] != '') ? $data['couriercharge'] : 0;
                                    $courierValue = (isset($data['couriercharge_val']) && $data['couriercharge_val'] != '') ? $data['couriercharge_val'] : 0;
                                ?>
                                <td><div style="width:130px;float:left;">Freight/Courier Charge : <?php echo $courierPer.' %'; ?></div>: Amount : <?php echo $courierValue; ?></td>
                                <td align="right">
                                    <?php 
                                        $calculateCourierPerval = ($courierValue*$courierPer/100);
                                        $finalCourierCharge = ($courierValue+$calculateCourierPerval);
                                        echo round($finalCourierCharge, 2);
                                    ?>
                                </td>
                            </tr>
                            <tr>
                            	<td><div style="width:130px;float:left;">Add CGST</div>:</td>
                                <td align="right">
                                    <?php echo (isset($data['totalcgst']) && $data['totalcgst'] != '') ? round($data['totalcgst'], 2) : 0; ?>
                                </td>
                            </tr>
                            <tr>
                            	<td><div style="width:130px;float:left;">Add SGST</div>:</td>
                                <td align="right">
                                    <?php echo (isset($data['totalsgst']) && $data['totalsgst'] != '') ? round($data['totalsgst'], 2) : 0; ?>
                                </td>
                            </tr>
                            <tr>
                            	<td><div style="width:130px;float:left;">Add IGST</div>:</td>
                                <td align="right">
                                    <?php echo (isset($data['totaligst']) && $data['totaligst'] != '') ? round($data['totaligst'], 2) : 0; ?>
                                </td>
                            </tr>
                            <tr style="background-color:#D6D6D6">
                            	<td><div style="width:130px;float:left;">Tax Amount GST</div>:</td>
                                <td align="right">
                                    <?php echo (isset($data['totaltaxgst']) && $data['totaltaxgst'] != '') ? round($data['totaltaxgst'], 2) : 0; ?>
                                </td>
                            </tr>
                            <tr>
                                <?php 
                                    if(isset($data['discount_type']) && $data['discount_type'] == 'rs'){
                                        $disSign = 'Rs';
                                        $disAmount = (isset($data['discount_rs']) && $data['discount_rs'] != '') ? round($data['discount_rs'], 2) : 0;
                                    }elseif(isset($data['discount_type']) && $data['discount_type'] == 'per'){
                                        $disSign = '%';
                                        $disAmount = (isset($data['discount_per']) && $data['discount_per'] != '') ? round($data['discount_per'], 2) : 0;
                                    }
                                ?>
                                <td>
                                    <div style="width:130px;float:left;">Discount <?php echo (isset($disSign)) ? '('.$disSign.')' : ''; ?></div>:
                                </td>
                                <td align="right">
                                    <?php echo (isset($disAmount)) ? $disAmount : 0; ?>
                                </td>
                            </tr>
                            <tr>
                                <?php 
                                    $notevalue = (isset($data['cr_db_type']) && $data['cr_db_type'] == 'credit') ? 'Credit Note' : 'Debit Note';
                                    $noteAmount = (isset($data['cr_db_val']) && $data['cr_db_val'] != '') ? $data['cr_db_val'] : 0;
                                ?>
                                <td><div style="width:130px;float:left;"><?php echo $notevalue; ?></div>:</td>
                                <td align="right">
                                    <?php echo $noteAmount; ?>
                                </td>
                            </tr>
                            <tr style="background-color:#DEDEDE;color:#070707">
                            	<td><div style="width:130px;float:left;font-weight:bold;">Total Amount After Tax</div>:</td>
                                <td align="right" style="font-weight:bold;"><?php echo (isset($data['final_amount']) && $data['final_amount'] != '') ? round($data['final_amount'], 2) : 0; ?></td>
                            </tr>
                             <tr >
                            	<td colspan="2" style="border:1px solid #DBDBDB;">&nbsp;</td>
                            </tr>
                            <!-- <tr style="">
                            	<td><div style="width:130px;float:left;font-weight:bold;">GST Payable on Reverse Charge</div>:</td>
                                <td align="right" style="font-weight:bold;background-color:#DEDEDE;color:#070707">N.A.</td>
                            </tr> -->
                            <tr>
                            	<td colspan="2" style="vertical-align:top;">
                                Certified that the particulars given above are true and correct,
                                <center><b>For, <?php echo (isset($data['pharmacy']['pharmacy_name'])) ? $data['pharmacy']['pharmacy_name'] : ''; ?></b></center>
                                <div style="vertical-align:bottom;font-size:8px;text-align:center;margin-top:20px;">Authorised Signatory</div>
                                </td>
                            </tr>
                        </table>
                    </td>
                 </tr>
                 
                 <tr>
                 	<td style="font-size:8px;">
                    	<center><b>: Bank Details :</b></center>

                        <?php if(isset($data['pharmacy_bank_detail']) && !empty($data['pharmacy_bank_detail'])){ ?>
                            <?php foreach ($data['pharmacy_bank_detail'] as $key => $value) { ?>
                                <div style="margin-top:10px;">
        	                        <div style="width:100px;float:left">Bank Account Number</div>: &nbsp;&nbsp;   [<?php echo (isset($value['account_number'])) ? $value['account_number'] : ''; ?>]
                                </div>    
                                <div style="margin-top:10px;clear:both;">
        	                         <div style="width:100px;float:left;">Bank Branch IFSC</div>: &nbsp;&nbsp;   [<?php echo (isset($value['ifsc_code'])) ? $value['ifsc_code'] : ''; ?>]
                                </div>
                            <?php } ?>
                        <?php } ?>     
                    
                    </td>
                    <td rowspan="2" style="vertical-align:bottom;font-size:8px;text-align:center">Common Seal</td>                   
                 </tr>   
                 
                 
                 <tr>
                 	<td style="height:50px;vertical-align:top;font-size:8px;"><center><b>: Terms and Conditions :</b></center></td>
                 </tr>
                 </table>
                </td>
            </tr>
            <!----------------------------------------------FOR COUNT TOTAL AMOUNT SECTION END-------------------------------------------->
			
        </table>
    </div>
</body>
</html>
<script>//window.close();</script>