<?php include('include/usertypecheck.php');?>
<?php


	$from = (isset($_GET['from']) && $_GET['from'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_GET['from']))) : '';
	$to = (isset($_GET['to']) && $_GET['to'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_GET['to']))) : '';

	$pharmacy = getPharmacyDetail();
	$data = JournalVoucherRegister($from, $to);
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Digibooks | Print Journal Voucher Register</title>
	<style type="text/css">
	.text-right{
	    text-align: right;
	}
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
 		<h3 class="sub-title"><strong>Register for the period <?php echo (isset($from) && $from != '') ? date('d,M Y',strtotime($from)) : ''; ?> to <?php echo (isset($to) && $to != '') ? date('d,M Y',strtotime($to)) : ''; ?></strong> </h3>
	</center>
	<table align="center" cellpadding="0" cellspacing="0" border="0" width="100%" id="customers" style="line-height:20px;">
  		<tbody>
  			<tr>
    			<td style="border:none;padding:10px;color:#CC0000;font-size:16px;text-align:center;line-height:20px;">
    				<span style="font-size:18px">Journal Voucher Register</span>
      			</td>
  			</tr>
		</tbody>
		</table>

	<span style="float:right;color:#000000;font-size:12px;margin-top: -30px;  margin-right: 30px;">(All Amounts in Rs.)</span>

	
	<table align="center" cellpadding="0" cellspacing="0" border="1" width="100%" id="customers" style="line-height:30px;">
        <thead>
            <tr>
                <th colspan="2">Credit</th>
                <th colspan="2">Debit</th>
            </tr>
            <tr>
                <th width="15%" class="text-right pr-15">Amount</th>
                <th width="35%" class="pl-15">Narration</th>
                <th width="15%" class="text-right pr-15">Amount</th>
                <th width="35%" class="pl-15">Narration</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="2" style="vertical-align: top;">
                    <table class="inner-table" width="100%" cellspacing="10" cellpadding="10">
                        <?php if(isset($data['credit'])){ ?>
                            <?php foreach($data['credit'] as $key => $value){ ?>
                                <tr>
                                    <td width="30%" class="text-right pr-15">
                                        <?php echo (isset($value['amount']) && $value['amount'] != '') ? amount_format(number_format($value['amount'], 2, '.', '')) : 0.00; ?>
                                    </td>
                                    <td width="70%" class="pl-15">
                                        <?php echo (isset($value['narration'])) ? $value['narration'] : ''; ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                    </table>
                </td>
                <td colspan="2" style="vertical-align: top;">
                    <table class="inner-table" width="100%" cellspacing="10" cellpadding="10">
                        <?php if(isset($data['debit'])){ ?>
                            <?php foreach($data['debit'] as $key => $value){ ?>
                                <tr>
                                    <td width="30%" class="text-right pr-15">
                                        <?php echo (isset($value['amount']) && $value['amount'] != '') ? amount_format(number_format($value['amount'], 2, '.', '')) : 0.00; ?>
                                    </td>
                                    <td width="70%" class="pl-15">
                                        <?php echo (isset($value['narration'])) ? $value['narration'] : ''; ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                    </table>
                </td>
            </tr>
        </tbody>
        <?php 
            $totalCredit = (isset($data['credit']) && !empty($data['credit'])) ? array_sum(array_column($data['credit'], 'amount')) : 0;
            $totalDebit = (isset($data['debit']) && !empty($data['debit'])) ? array_sum(array_column($data['debit'], 'amount')) : 0;
        ?>
        <tr style="font-size:14px;font-weight:bold;">
            <td class="text-right pr-15"><?php echo (isset($totalCredit) && $totalCredit != '') ? amount_format(number_format($totalCredit, 2, '.', '')) : 0.00; ?></td>
            <td></td>
            <td style="border-right: 0px;" class="text-right pr-15"><?php echo (isset($totalDebit) && $totalDebit != '') ? amount_format(number_format($totalDebit, 2, '.', '')) : 0.00 ?></td>
            <td></td>
        </tr>
	</table>
</body>
</html>