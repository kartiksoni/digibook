<?php include('include/usertypecheck.php');?>
<?php
    $from = (isset($_GET['from']) && $_GET['from'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_GET['from']))) : ''; 
    $to = (isset($_GET['to']) && $_GET['to'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_GET['to']))) : '';
    $company = (isset($_GET['company'])) ? $_GET['company'] : '';
    $ledger = (isset($_GET['ledger'])) ? $_GET['ledger'] : '';
    $type = (isset($_GET['type'])) ? $_GET['type'] : '';
    $sub_type = (isset($_GET['sub_type'])) ? $_GET['sub_type'] : '';
    $bill_type = (isset($_GET['bill_type'])) ? $_GET['bill_type'] : '';

    $searchdata = freegoods($from, $to, $bill_type, $ledger, $company, $type, $sub_type);

    $getledger =" SELECT l.gstno,l.city,l.name, c.name AS city from ledger_master AS l INNER JOIN own_cities AS c ON l.city = c.id WHERE l.id= '".$ledger."' ";

	$ledger1 =mysqli_query($conn, $getledger);
	$data1 = mysqli_fetch_assoc($ledger1);

	$pharmacy = getPharmacyDetail();
?>
<!DOCTYPE html> 
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Digibooks | Print Free Goods Report</title>
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
</style>
</head>
<body>
	<center>
		<h3 class="panel-title"><strong> <?php echo (isset($pharmacy['pharmacy_name'])) ? ucwords(strtolower($pharmacy['pharmacy_name'])) : 'Unknown pharmacy'; ?> </strong> <br>
			<span style="font-size:16px;">GST NO : <?php echo (isset($pharmacy['gst_no'])) ? $pharmacy['gst_no'] : 'Unknown GSTNO'; ?></span>
		</h3>
		<?php if((isset($from) && $from != '') && (isset($to) && $to != '')){ ?>
			<h3 class="sub-title"><strong>Free Goods Report for the period <?php echo (isset($from) && $from != '') ? date('d,M Y',strtotime($from)) : ''; ?> to <?php echo (isset($to) && $to != '') ? date('d,M Y',strtotime($to)) : ''; ?></strong> </h3>
		<?php } ?>
	</center>
	<table align="center" cellpadding="0" cellspacing="0" border="0" width="100%" id="customers" style="line-height:20px;">
		<tbody>
			<tr>
				<td style="border:none;padding:10px;color:#CC0000;font-size:16px;text-align:center;line-height:20px;">
					<span style="font-size:18px">Party 	Name : <b> <?php echo (isset($data1['name'])) ? $data1['name'] : 'Unknown Name'; ?> </b></span> <br>
					<span style="font-size:15px;line-height:24px;font-weight:bold;">City: <?php echo (isset($data1['city'])) ? $data1['city'] : 'Unknown City'; ?>
					<?php if(isset($data1['gstno']) && $data1['gstno'] != ''){ ?>
						<br>GST No: <?php echo (isset($data1['gstno'])) ? $data1['gstno'] : 'Unknown GSTNO'; ?> </span>
					<?php } ?>
					<br/><span style="font-size:15px;line-height:24px;font-weight:bold;">View: <?php echo (isset($bill_type)) ? ucwords($bill_type) : 'Unknown View'; ?>
					<br/><span style="font-size:15px;line-height:24px;font-weight:bold;">Type: <?php echo (isset($_GET['type'])) ? ucwords(str_replace("_"," ", $_GET['type'])) : 'Unknown Type'; ?>
					<br/><span style="font-size:15px;line-height:24px;font-weight:bold;">Sub Type: <?php echo (isset($_GET['sub_type'])) ? ucwords(str_replace("_"," ", $_GET['sub_type'])) : 'Unknown Type'; ?>
				</td>
			</tr>
		</tbody>
	</table>

	<span style="float:right;color:#000000;font-size:12px;margin-top: -30px;  margin-right: 30px;">(All Amounts in Rs.)</span>

	
	<table align="center" cellpadding="0" cellspacing="0" border="1" width="100%" id="customers" style="line-height:30px;">
		<thead>
          <tr>
            <th>Sr. No</th>
            <?php if(isset($_POST['bill_type']) && $_POST['bill_type'] == 'detail'){ ?>
            <th>Voucher/Debit Note Date</th>
            <th>Voucher/Debit Note No</th>
             <?php } ?>
             <th><?php if((isset($bill_type) && $bill_type == 'summary') && (isset($type) && $type == 'company_wise')){echo "Company Name"; } else if((isset($bill_type) && $bill_type == 'summary') && (isset($sub_type) && $sub_type == 'all1')){ echo "Product Name"; } else if((isset($bill_type) && $bill_type == 'summary') && (isset($sub_type) && $sub_type == 'party_wise')){ echo "Party Name"; }else{ echo "Product Name";}?></th>
            <th>Free Qty.</th>
            <th>Amount</th>
          </tr> 
        </thead>
		<tbody>
         <?php $total_amount = 0; $total_freeqty = 0; ?>
          <?php if(!empty($searchdata)){ ?>
            <?php foreach ($searchdata as $key => $value) { ?>
              <tr>
                <td><?php echo $key+1; ?></td>
                <?php if(isset($_POST['bill_type']) && $_POST['bill_type'] == 'detail'){ ?>
                <td><?php echo (isset($value['date']) && $value['date'] != '') ? date('d/m/Y',strtotime($value['date'])) : '';?></td>
                <td><?php echo (isset($value['no'])) ? $value['no'] : ''; ?></td>
                <?php } ?>
                <td><?php echo (isset($value['product_name'])) ? $value['product_name'] : '';?></td>
                <td><?php echo (isset($value['free_qty'])) ? $value['free_qty'] : ''; ?></td>
                <td><?php echo (isset($value['amount'])) ? $value['amount'] : ''; ?></td>
              </tr>
              <?php
                $freeqty = (isset($value['free_qty']) && $value['free_qty'] != '') ? $value['free_qty'] : '';
                $total_freeqty += $freeqty;
                $amount = (isset($value['amount']) && $value['amount'] != '') ? $value['amount'] : '';
                  $total_amount += $amount;
              ?>
            <?php } ?>
          <?php } ?>
        </tbody>
        <tfoot>
	      <tr>
	        <th colspan="<?php echo (isset($_POST['bill_type']) && $_POST['bill_type'] == 'detail') ? 4 : 2; ?>" class="text-center"><strong>Total</strong></th>
	        <th>
	          <?php echo (isset($total_freeqty)) ? $total_freeqty : 0; ?>
	        </th>
	        <th class="text-right">
	          <?php echo (isset($total_amount)) ? amount_format(number_format($total_amount, 2, '.', '')) : 0; ?>
	        </th>
	      </tr>
	    </tfoot>
	</table>
</body>
</html>