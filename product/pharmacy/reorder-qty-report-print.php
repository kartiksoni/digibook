<?php include('include/usertypecheck.php');

$type = (isset($_REQUEST['type'])) ? $_REQUEST['type'] : '';
$company_name = (isset($_REQUEST['company_name'])) ? $_REQUEST['company_name'] : '';
$company_id = (isset($_REQUEST['company_id'])) ? $_REQUEST['company_id'] : '';
$selectedcompany = (isset($_REQUEST['selectedcompany'])) ? $_REQUEST['selectedcompany'] : '';
$from = (isset($_REQUEST['from']) && $_REQUEST['from'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_REQUEST['from']))) : ''; 
$to = (isset($_REQUEST['to']) && $_REQUEST['to'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_REQUEST['to']))) : '';
$stock = $_REQUEST['stock'];
$data = reorderQuantityReport($type,$company_name,$selectedcompany,$from,$to,$stock);
$pharmacy = getPharmacyDetail();

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Digibooks | Print Reorder Quantity Report</title>
	<style type="text/css">
	.panel-title {
		font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;
		color:#000000;
		font-size:1.5em;
	}
	.sub-title {
		font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;
		color:#000033;
		font-size:1.1em;
	}
	#customers {
		font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;
		width:99%;
		border-collapse:collapse;
	}
	#customers td, #customers th {
		font-size:1em;
		border:1px solid #046998;
		padding:5px 10px;
	}
	#customers th {
		font-size:1.1em;
		text-align:left;
		color:#000000;
		padding:3px 15px 2px 20px;
	}
	#customers tr td {
		color:#000000;
		font-size:12px !important;
	}

    .inner-table td{
        border: none !important;
    }
	</style>
</head>
<body>
	<center>
		<h3 class="panel-title"><strong> <?php echo (isset($pharmacy['pharmacy_name'])) ? ucwords(strtolower($pharmacy['pharmacy_name'])) : 'Unknown pharmacy'; ?> </strong> <br>
    		<span style="font-size:16px;">GST NO : <?php echo (isset($pharmacy['gst_no'])) ? $pharmacy['gst_no'] : 'Unknown GSTNO'; ?></span>
    	</h3>
 		<h3 class="sub-title"><strong>Stock Statement for the period <?php echo (isset($from) && $from != '') ? date('d,M Y',strtotime($from)) : ''; ?> to <?php echo (isset($to) && $to != '') ? date('d,M Y',strtotime($to)) : ''; ?></strong> </h3>
	</center>
	<table align="center" cellpadding="0" cellspacing="0" border="0" width="100%" id="customers" style="line-height:20px;">
  		<tbody>
  			<tr>
    			<td style="border:none;padding:10px;color:#CC0000;font-size:16px;text-align:center;line-height:20px;">
    				<span style="font-size:18px">Reorder Quantity Report</span>
      			</td>
  			</tr>
		</tbody>
		</table>

	<!-- <span style="float:right;color:#000000;font-size:12px;margin-top: -30px;  margin-right: 30px;">(All Amounts in Rs.)</span> -->

	
	<table align="center" cellpadding="0" cellspacing="0" border="1" width="100%" id="customers" style="line-height:30px;">
        <thead>
            <tr>
                <th>Sr No</th>
                <th>Product Name</th>
                <th>Opening Qty</th>
                <th>Purchase Qty</th>
                <th>Sale Qty</th>
                <th>Closing Qty</th>
                <th>Expiry Date</th>
                <th>Rem</th>
                <th>Average Sale</th>
                <th>Order Qty</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($data)){?>
                <?php foreach($data as $i => $dataRow){?>
                <tr>
                    <td style="text-align: center;"><?php echo $i + 1;?></td>
                    <td><?php echo $dataRow['product_name'];?></td>
                    <td style="text-align: right;"><?php echo number_format($dataRow['opening_qty'], 2, '.', '');?></td>
                    <td style="text-align: right;"><?php echo number_format($dataRow['purchase_qty'], 2, '.', '');?></td>
                    <td style="text-align: right;"><?php echo number_format($dataRow['sale_qty'], 2, '.', '');?></td>
                    <td style="text-align: right;"><?php echo number_format($dataRow['closing_qty'], 2, '.', '');?></td>
                    <td style="text-align: right;"><?php echo $dataRow['ex_date'];?></td>
                    <td style="text-align: center;"><?php echo $dataRow['rem_string'];?></td>
                    <td style="text-align: right;"><?php echo number_format($dataRow['average_sale'], 2, '.', '');?></td>
                    <td style="text-align: right;"><?php echo number_format($dataRow['order_new'], 2, '.', '');?></td>
                </tr>
                <?php }?>
            <?php } else {?>
                <tr style="text-align: center;">
                    <td colspan="9"><?php echo "No Data Found.";?></td>                                            
                </tr>
            <?php }?>
        </tbody>
	</table>
</body>
</html>