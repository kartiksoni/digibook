<?php include('include/usertypecheck.php');?>

<?php 
 
    $id = (isset($_GET['ladger'])) ? $_GET['ladger'] : '';
    $from = (isset($_GET['from']) && $_GET['from'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_GET['from']))) : '';
	$to = (isset($_GET['to']) && $_GET['to'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_GET['to']))) : '';

  $customer = getPerticularDetail($id);
  $pharmacy = getPharmacyDetail();
  $data = allLedgerdetaills($id, $from, $to, 1);


?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Digibooks | Print All Group Ledger</title>
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
    	<table align="center" cellpadding="0" cellspacing="0" border="0" width="100%" id="customers" style="line-height:20px;">
  		<tbody>
  			<tr>
    			<td style="border:none;padding:10px;color:#CC0000;font-size:16px;text-align:center;line-height:20px;">
    				<span style="font-size:18px">Ladger: <b> <?php echo (isset($data['name'])) ? $data['name'] : ''; ?> </b></span> <br>
      				
      			</td>
  			</tr>
		</tbody>
		</table>

 		<h3 class="sub-title"><strong>Period <?php echo (isset($from) && $from != '') ? date('d,M Y',strtotime($from)) : ''; ?> to <?php echo (isset($to) && $to != '') ? date('d,M Y',strtotime($to)) : ''; ?></strong> </h3>
	</center>
	

	<span style="float:right;color:#000000;font-size:12px;margin-top: -30px;  margin-right: 30px;">(All Amounts in Rs.)</span>
    
    <?php if(isset($data)){ ?>
	    <?php if(isset($data['acc_flag']) && $data['acc_flag'] != '' && $data['acc_flag'] == 1){ ?>
	        <?php $total_amount = 0; ?>
	        <table align="center" cellpadding="0" cellspacing="0" border="1" width="100%" id="customers" style="line-height:30px;">
          		<thead>
        		    <tr>
        		    	<th width="7%">Sr No.</th>
        		      	<th>Invoice Date</th>
        		      	<th>Invoice No.</th>
        		      	<th>Narration</th>
        		      	<th>Debit</th>
        		      	<th>Credit</th>
        	     	 	<th style="text-align:right">Balance</th>
        		    </tr>
          		</thead>
          		<tbody>
          		    <?php if(!empty($data['data'])){ ?>
          		    <?php $running_balance = 0;$total_debit = 0;$total_credit = 0; ?>
          		        <?php foreach($data['data'] as $key => $value){ ?>
          		            <tr>
                                <td><?php echo ($key+1); ?></td>
                                <td><?php echo (isset($value['date']) && $value['date'] != '' && $value['date'] != '0000-00-00') ? date('d/m/Y',strtotime($value['date'])) : ''; ?></td>
                                <td><?php echo (isset($value['no'])) ? $value['no'] : ''; ?></td>
                                <td><?php echo (isset($value['remarks'])) ? $value['remarks'] : ''; ?></td>
                                <td style="text-align:right">
                                                     <?php
                                                    if(isset($value['debit']) && $value['debit'] != ''){
                                                        echo amount_format(number_format($value['debit'], 2, '.', ''));
                                                        $total_debit += $value['debit'];
                                                       $running_balance -= $value['debit'];
                                                    }
                                                ?>
                                 </td>
                                                
                                 <td style="text-align:right">
                                                    <?php
                                                    if(isset($value['credit']) && $value['credit'] != ''){
                                                        echo amount_format(number_format($value['credit'], 2, '.', ''));
                                                         $total_credit += $value['credit'];
                                                        $running_balance += $value['credit'];
                                                    }
                                                ?>
                                 </td>
                                <td style="text-align:right">
                                                        <?php
                                                            echo amount_format(number_format($running_balance, 2, '.', ''));
                                                        ?>
                                 </td>
                            </tr>
          		        <?php } ?>
          		    <?php } ?>
          		</tbody>
          		<tfoot>
          		    <tr style="font-size:14px;font-weight:bold;">
            			<td colspan="4" style="text-align:center;"> Total</td>
            			<th style="text-align:right">
                            <?php echo (isset($total_debit) && $total_debit != '') ? amount_format(number_format($total_debit, 2, '.', '')) : 0; ?>
                        </th>
                        <th style="text-align:right">
                           <?php echo (isset($total_credit) && $total_credit != '') ? amount_format(number_format($total_credit, 2, '.', '')) : 0; ?>
                        </th>
                        <th style="text-align:right">
                           <?php echo (isset($running_balance) && $running_balance != '') ? amount_format(number_format($running_balance, 2, '.', '')) : 0; 
                           ?>
                         </th>
                                        
            			<!--<td style="text-align:right;padding-right:10px;"><?php //echo (isset($total_amount)) ? amount_format(number_format($total_amount, 2, '.', '')) : 0; ?></td>-->
              		</tr>
          		</tfoot>
          		</table>
          		
          		<?php }else if(isset($data['acc_flag']) && $data['acc_flag'] != '' && $data['acc_flag'] == 3){?>
                               <table align="center" cellpadding="0" cellspacing="0" border="1" width="100%" id="customers" style="line-height:30px;">
                                    <thead>
                                      <tr>
                                          <th width="7%" class="text-center">Sr. No</th>
                                          <th>Voucher Date</th>
                                          <th>Voucher No.</th>
                                          <th>Narration</th>
                                          <th >Debit</th>
                                          <th>Credit</th>
                                          <th style="text-align:right">Balance</th>
                                      </tr> 
                                    </thead>
                                    <tbody>
                                          
                                        <?php if(isset($data['data']) && !empty($data['data'])){?>
                                        <?php $running_balance = 0;$total_debit = 0;$total_credit = 0; ?>
                                            <?php foreach($data['data'] as $key => $value){ ?>
                                             
                                                <tr>
                                                    <td><?php echo ($key+1); ?></td>
                                                    <td><?php echo (isset($value['date']) && $value['date'] != '' && $value['date'] != '0000-00-00') ? date('d/m/Y',strtotime($value['date'])) : ''; ?></td>
                                                    <td><?php echo (isset($value['no'])) ? $value['no'] : ''; ?></td>
                                                    <td><?php echo (isset($value['date']) && $value['date'] != '' && $value['date'] != '0000-00-00') ? date('d/m/Y',strtotime($value['date'])) : ''; ?> - <?php echo (isset($value['no'])) ? $value['no'] : ''; ?></td>
                                                    <td style="text-align:right">
                                                     <?php
                                                    if(isset($value['debit']) && $value['debit'] != ''){
                                                        echo amount_format(number_format($value['debit'], 2, '.', ''));
                                                        $total_debit += $value['debit'];
                                                       $running_balance += $value['debit'];
                                                    }
                                                ?>
                                                </td>
                                                    <td style="text-align:right">
                                                    <?php
                                                    if(isset($value['credit']) && $value['credit'] != ''){
                                                        echo amount_format(number_format($value['credit'], 2, '.', ''));
                                                         $total_credit += $value['credit'];
                                                        $running_balance -= $value['credit'];
                                                    }
                                                ?>
                                                </td>
                                                    
                                                    <td style="text-align:right">
                                                        <?php
                                                            echo amount_format(number_format($running_balance, 2, '.', ''));
                                                        ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        <?php } ?>
                                    </tbody>
                                    <tfoot>
                                      <tr style="background-color: #EFEFEF;">
                                        <th colspan="4" style="text-align:center;"><strong>Total</strong></th>
                                        <th style="text-align:right">
                                            <?php echo (isset($total_debit) && $total_debit != '') ? amount_format(number_format($total_debit, 2, '.', '')) : 0; ?>
                                        </th>
                                        <th style="text-align:right">
                                          <?php echo (isset($total_credit) && $total_credit != '') ? amount_format(number_format($total_credit, 2, '.', '')) : 0; ?>
                                        </th>
                                        <th style="text-align:right">
                                            <?php echo (isset($running_balance) && $running_balance != '') ? amount_format(number_format($running_balance, 2, '.', '')) : 0; ?>
                                        </th>
                                       
                                      </tr>
                                    </tfoot>
                                </table>
                       <?php }else if(isset($data['acc_flag']) && $data['acc_flag'] != '' && $data['acc_flag'] == 5){?>
                                  <table align="center" cellpadding="0" cellspacing="0" border="1" width="100%" id="customers" style="line-height:30px;">
                                    <thead>
                                      <tr>
                                          <th width="7%" class="text-center">Sr. No</th>
                                          <th>Invoice Date</th>
                                          <th>Invoice No.</th>
                                          <th class="text-center" width="12%">Debit</th>
                                          <th class="text-center" width="12%">Credit</th>
                                          <th class="text-center" width="12%">Balance</th>
                                      </tr> 
                                    </thead>
                                    <tbody>
                                          
                                        <?php if(isset($data['data']) && !empty($data['data'])){?>
                                        <?php $running_balance = 0;$total_debit = 0;$total_credit = 0; ?>
                                            <?php foreach($data['data'] as $key => $value){ ?>
                                             
                                                <tr>
                                                    <td><?php echo ($key+1); ?></td>
                                                    <td><?php echo (isset($value['date']) && $value['date'] != '' && $value['date'] != '0000-00-00') ? date('d/m/Y',strtotime($value['date'])) : ''; ?></td>
                                                    <td><?php echo (isset($value['no'])) ? $value['no'] : ''; ?></td>
                                                    
                                                    <td style="text-align:right">
                                                     <?php
                                                    if(isset($value['debit']) && $value['debit'] != ''){
                                                        echo amount_format(number_format($value['debit'], 2, '.', ''));
                                                        $total_debit += $value['debit'];
                                                       $running_balance -= $value['debit'];
                                                    }
                                                ?>
                                                </td>
                                                    <td style="text-align:right">
                                                    <?php
                                                    if(isset($value['credit']) && $value['credit'] != ''){
                                                        echo amount_format(number_format($value['credit'], 2, '.', ''));
                                                         $total_credit += $value['credit'];
                                                        $running_balance += $value['credit'];
                                                    }
                                                ?>
                                                </td>
                                                    
                                                    <td style="text-align:right">
                                                        <?php
                                                            echo amount_format(number_format($running_balance, 2, '.', ''));
                                                        ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        <?php } ?>
                                    </tbody>
                                    <tfoot>
                                      <tr style="background-color: #EFEFEF;">
                                        <th colspan="3" style="text-align:center;"><strong>Total</strong></th>
                                        <th style="text-align:right">
                                            <?php echo (isset($total_debit) && $total_debit != '') ? amount_format(number_format($total_debit, 2, '.', '')) : 0; ?>
                                        </th>
                                        <th style="text-align:right">
                                          <?php echo (isset($total_credit) && $total_credit != '') ? amount_format(number_format($total_credit, 2, '.', '')) : 0; ?>
                                        </th>
                                        <th style="text-align:right">
                                            <?php echo (isset($running_balance) && $running_balance != '') ? amount_format(number_format($running_balance, 2, '.', '')) : 0; ?>
                                        </th>
                                       
                                      </tr>
                                    </tfoot>
                                </table>  
                                
                                 <?php }else if(isset($data['acc_flag']) && $data['acc_flag'] != '' && $data['acc_flag'] == 8){?>
                                  <table align="center" cellpadding="0" cellspacing="0" border="1" width="100%" id="customers" style="line-height:30px;">
                                    <thead>
                                      <tr>
                                        <th width="7%" class="text-center">Sr. No</th>
                                        <th>Narration</th>
                                        <th class="text-center" width="12%">Balance</th>
                                      </tr> 
                                    </thead>
                                    <tbody>
                
                                      <?php if(isset($data['data']) && !empty($data['data'])){?>
                                        <?php foreach($data['data'] as $key => $value){ ?>
                                          <tr>
                                            <td><?php echo ($key+1); ?></td>
                                            <td>Opening Stock</td>
                                          <td style="text-align:right">
                                            <?php
                                             echo amount_format(number_format($value['amount'], 2, '.', ''));
                                            ?>
                                          </td>
                                        </tr>
                                      <?php } ?>
                                    <?php } ?>
                                  </tbody>
                                  <tfoot>
                                    <tr style="background-color: #EFEFEF;">
                                      <th colspan="2" style="text-align:center;"><strong>Total</strong></th>
                                      <th style="text-align:right">
                                        <?php
                                             echo amount_format(number_format($value['amount'], 2, '.', '')); ?>
                                      </th>
                                    </tr>
                                  </tfoot>
                                </table>
                                
                                <?php }else if(isset($data['acc_flag']) && $data['acc_flag'] != '' && $data['acc_flag'] == 9){?>
                                 <table align="center" cellpadding="0" cellspacing="0" border="1" width="100%" id="customers" style="line-height:30px;">
                                    <thead>
                                      <tr>
                                        <th width="7%" class="text-center">Sr. No</th>
                                        <th>Narration</th>
                                        <th class="text-center" width="12%">Balance</th>
                                      </tr> 
                                    </thead>
                                    <tbody>
                
                                      <?php if(isset($data['data']) && !empty($data['data'])){?>
                                        <?php foreach($data['data'] as $key => $value){ ?>
                                          <tr>
                                            <td><?php echo ($key+1); ?></td>
                                            <td>Closing Stock</td>
                                          <td style="text-align:right">
                                            <?php
                                             echo amount_format(number_format($value['amount'], 2, '.', ''));
                                            ?>
                                          </td>
                                        </tr>
                                      <?php } ?>
                                    <?php } ?>
                                  </tbody>
                                  <tfoot>
                                    <tr style="background-color: #EFEFEF;">
                                      <th colspan="2" style="text-align:center;"><strong>Total</strong></th>
                                      <th style="text-align:right">
                                        <?php
                                             echo amount_format(number_format($value['amount'], 2, '.', '')); ?>
                                      </th>
                                    </tr>
                                  </tfoot>
                                </table>
                                
                <?php }else if(isset($data['acc_flag']) && $data['acc_flag'] != '' && $data['acc_flag'] == 10){?>
                                  <table align="center" cellpadding="0" cellspacing="0" border="1" width="100%" id="customers" style="line-height:30px;">
                                    <thead>
                                      <tr>
                                          <th width="7%" class="text-center">Sr. No</th>
                                          <th>Invoice Date</th>
                                          <th>Invoice No.</th>
                                          <th class="text-center" width="12%">Debit</th>
                                          <th class="text-center" width="12%">Credit</th>
                                          <th class="text-center" width="12%">Running Balance</th>
                                      </tr> 
                                    </thead>
                                    <tbody>
                                          
                                        <?php if(isset($data['data']) && !empty($data['data'])){?>
                                        <?php $running_balance = 0;$total_debit = 0;$total_credit = 0; ?>
                                            <?php foreach($data['data'] as $key => $value){ ?>
                                             
                                                <tr>
                                                    <td><?php echo ($key+1); ?></td>
                                                    <td><?php echo (isset($value['date']) && $value['date'] != '' && $value['date'] != '0000-00-00') ? date('d/m/Y',strtotime($value['date'])) : ''; ?></td>
                                                    <td><?php echo (isset($value['no'])) ? $value['no'] : ''; ?></td>
                                                    
                                                    <td style="text-align:right">
                                                     <?php
                                                    if(isset($value['debit']) && $value['debit'] != ''){
                                                        echo amount_format(number_format($value['debit'], 2, '.', ''));
                                                        $total_debit += $value['debit'];
                                                       $running_balance += $value['debit'];
                                                    }
                                                ?>
                                                </td>
                                                    <td style="text-align:right">
                                                    <?php
                                                    if(isset($value['credit']) && $value['credit'] != ''){
                                                        echo amount_format(number_format($value['credit'], 2, '.', ''));
                                                         $total_credit += $value['credit'];
                                                        $running_balance -= $value['credit'];
                                                    }
                                                ?>
                                                </td>
                                                    
                                                    <td style="text-align:right">
                                                        <?php
                                                            echo amount_format(number_format($running_balance, 2, '.', ''));
                                                        ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        <?php } ?>
                                    </tbody>
                                    <tfoot>
                                      <tr style="background-color: #EFEFEF;">
                                        <th colspan="3" style="text-align:center"><strong>Total</strong></th>
                                        <th style="text-align:right">
                                            <?php echo (isset($total_debit) && $total_debit != '') ? amount_format(number_format($total_debit, 2, '.', '')) : 0; ?>
                                        </th>
                                        <th style="text-align:right">
                                          <?php echo (isset($total_credit) && $total_credit != '') ? amount_format(number_format($total_credit, 2, '.', '')) : 0; ?>
                                        </th>
                                        <th style="text-align:right">
                                            <?php echo (isset($running_balance) && $running_balance != '') ? amount_format(number_format($running_balance, 2, '.', '')) : 0; ?>
                                        </th>
                                       
                                      </tr>
                                    </tfoot>
                                </table>                
                                
                <?php }else if(isset($data['acc_flag']) && $data['acc_flag'] != '' && $data['acc_flag'] == 11){?>
                                  <table align="center" cellpadding="0" cellspacing="0" border="1" width="100%" id="customers" style="line-height:30px;">
                                    <thead>
                                      <tr>
                                        <th width="7%" class="text-center">Sr. No</th>
                                        <th>Invoice Date</th>
                                        <th>Invoice No.</th>
                                        <th class="text-center" width="12%">Debit</th>
                                        <th class="text-center" width="12%">Credit</th>
                                        <th class="text-center" width="12%">Balance</th>
                                      </tr> 
                                    </thead>
                                    <tbody>
                
                                      <?php if(isset($data['data']) && !empty($data['data'])){?>
                                        <?php $running_balance = 0;$total_debit = 0;$total_credit = 0; ?>
                                        <?php foreach($data['data'] as $key => $value){ ?>
                
                                          <tr>
                                            <td><?php echo ($key+1); ?></td>
                                            <td><?php echo (isset($value['date']) && $value['date'] != '' && $value['date'] != '0000-00-00') ? date('d/m/Y',strtotime($value['date'])) : ''; ?></td>
                                            <td><?php echo (isset($value['no'])) ? $value['no'] : ''; ?></td>
                
                                            <td style="text-align:right">
                                             <?php
                                             if(isset($value['debit']) && $value['debit'] != ''){
                                              echo amount_format(number_format($value['debit'], 2, '.', ''));
                                              $total_debit += $value['debit'];
                                              $running_balance += $value['debit'];
                                            }
                                            ?>
                                          </td>
                                          <td style="text-align:right">
                                            <?php
                                            if(isset($value['credit']) && $value['credit'] != ''){
                                              echo amount_format(number_format($value['credit'], 2, '.', ''));
                                              $total_credit += $value['credit'];
                                              $running_balance -= $value['credit'];
                                            }
                                            ?>
                                          </td>
                
                                          <td style="text-align:right">
                                            <?php
                                            echo amount_format(number_format($running_balance, 2, '.', ''));
                                            ?>
                                          </td>
                                        </tr>
                                      <?php } ?>
                                    <?php } ?>
                                  </tbody>
                                  <tfoot>
                                    <tr style="background-color: #EFEFEF;">
                                      <th colspan="3" style="text-align:center"><strong>Total</strong></th>
                                      <th style="text-align:right">
                                        <?php echo (isset($total_debit) && $total_debit != '') ? amount_format(number_format($total_debit, 2, '.', '')) : 0; ?>
                                      </th>
                                      <th style="text-align:right">
                                        <?php echo (isset($total_credit) && $total_credit != '') ? amount_format(number_format($total_credit, 2, '.', '')) : 0; ?>
                                      </th>
                                      <th style="text-align:right">
                                        <?php echo (isset($running_balance) && $running_balance != '') ? amount_format(number_format($running_balance, 2, '.', '')) : 0; ?>
                                      </th>
                
                                    </tr>
                                  </tfoot>
                                </table>
                             
                    <?php }else if(isset($data['acc_flag']) && $data['acc_flag'] != '' && $data['acc_flag'] == 12){?>
                                  <table align="center" cellpadding="0" cellspacing="0" border="1" width="100%" id="customers" style="line-height:30px;">
                                    <thead>
                                      <tr>
                                        <th width="7%" class="text-center">Sr. No</th>
                                        <th>Invoice Date</th>
                                        <th>Invoice No.</th>
                                        <th class="text-center" width="12%">Debit</th>
                                        <th class="text-center" width="12%">Credit</th>
                                        <th class="text-center" width="12%">Balance</th>
                                      </tr> 
                                    </thead>
                                    <tbody>
                
                                      <?php if(isset($data['data']) && !empty($data['data'])){?>
                                        <?php $running_balance = 0;$total_debit = 0;$total_credit = 0; $count = 1; ?>
                                        <?php foreach($data['data'] as $key => $value){ 
                                            
                                             ?>
                
                                          <tr>
                                            <td><?php echo $count ; ?></td>
                                            <td><?php echo (isset($value['date']) && $value['date'] != '' && $value['date'] != '0000-00-00') ? date('d/m/Y',strtotime($value['date'])) : ''; ?></td>
                                            <td><?php echo (isset($value['no'])) ? $value['no'] : ''; ?></td>
                
                                            <td style="text-align:right">
                                             <?php
                                             if(isset($value['debit']) && $value['debit'] != ''){
                                              echo amount_format(number_format($value['debit'], 2, '.', ''));
                                              $total_debit += $value['debit'];
                                              $running_balance += $value['debit'];
                                            }
                                            ?>
                                          </td>
                                          <td style="text-align:right">
                                            <?php
                                            if(isset($value['credit']) && $value['credit'] != ''){
                                              echo amount_format(number_format($value['credit'], 2, '.', ''));
                                              $total_credit += $value['credit'];
                                              $running_balance -= $value['credit'];
                                            }
                                            ?>
                                          </td>
                
                                          <td style="text-align:right">
                                            <?php
                                            echo amount_format(number_format($running_balance, 2, '.', ''));
                                            ?>
                                          </td>
                                        </tr>
                
                                      <?php $count ++; }?>
                                    <?php } ?>
                                  </tbody>
                                  <tfoot>
                                    <tr style="background-color: #EFEFEF;">
                                      <th colspan="3" style="text-align:center"><strong>Total</strong></th>
                                      <th style="text-align:right">
                                        <?php echo (isset($total_debit) && $total_debit != '') ? amount_format(number_format($total_debit, 2, '.', '')) : 0; ?>
                                      </th>
                                      <th style="text-align:right">
                                        <?php echo (isset($total_credit) && $total_credit != '') ? amount_format(number_format($total_credit, 2, '.', '')) : 0; ?>
                                      </th>
                                      <th style="text-align:right">
                                        <?php echo (isset($running_balance) && $running_balance != '') ? amount_format(number_format($running_balance, 2, '.', '')) : 0; ?>
                                      </th>
                
                                    </tr>
                                  </tfoot>
                                </table>
                                
                         
                         <?php }else if(isset($data['acc_flag']) && $data['acc_flag'] != '' && $data['acc_flag'] == 7 || $data['acc_flag'] == 6 || $data['acc_flag'] == 2 || $data['acc_flag'] == 4){?>  
                              
                               <table align="center" cellpadding="0" cellspacing="0" border="1" width="100%" id="customers" style="line-height:30px;">
                                    <thead>
                                      <tr>
                                          <th width="7%" class="text-center">Sr. No</th>
                                          <th>Invoice Date</th>
                                          <th>Invoice No.</th>
                                          <th>Perticular</th>
                                          <th>Mobile</th>
                                          <th>City</th>
                                          <th>Type</th>
                                          <th class="text-right">Balance</th>
                                      </tr> 
                                    </thead>
                                    <tbody>
                                        <?php if(isset($data['data']) && !empty($data['data'])){ $total_amount = 0;?>
                                            <?php foreach($data['data'] as $key => $value){ ?>
                                                <tr>
                                                    <td><?php echo ($key+1); ?></td>
                                                    <td><?php echo (isset($value['date']) && $value['date'] != '' && $value['date'] != '0000-00-00') ? date('d/m/Y',strtotime($value['date'])) : ''; ?></td>
                                                    <td><?php echo (isset($value['no'])) ? $value['no'] : ''; ?></td>
                                                    <td><?php echo (isset($value['name'])) ? $value['name'] : ''; ?></td>
                                                    <td><?php echo (isset($value['mobile'])) ? $value['mobile'] : ''; ?></td>
                                                    <td><?php echo (isset($value['city'])) ? $value['city'] : ''; ?></td>
                                                    <td><?php echo (isset($value['bill_type'])) ? $value['bill_type'] : ''; ?></td>
                                                    <td style="text-align:right">
                                                        <?php
                                                            $amount = (isset($value['amount']) && $value['amount'] != '') ? $value['amount'] : 0;
                                                            $total_amount += $amount;
                                                            echo amount_format(number_format($amount, 2, '.', ''));
                                                        ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        <?php } ?>
                                    </tbody>
                                    <tfoot>
                                      <tr style="background-color: #EFEFEF;">
                                        <th colspan="7" class="text-center"><strong>Total</strong></th>
                                        <th style="text-align:right">
                                            <?php echo (isset($total_amount) && $total_amount != '') ? amount_format(number_format($total_amount, 2, '.', '')) : 0; ?>
                                        </th>
                                       
                                      </tr>
                                    </tfoot>
                                </table>
                    
                                
	            <?php }else{ ?>
        	<table align="center" cellpadding="0" cellspacing="0" border="1" width="100%" id="customers" style="line-height:30px;">
          		<thead>
        		    <tr>
        		    	<th width="7%">Sr No.</th>
        		      	<th width="7%">Date</th>
        		      	<th>Narration</th>
        		      	<th style="text-align:right" width="15%">Debit</th>
        	     	 	<th style="text-align:right" width="15%">Credit</th>
        		      	<th style="text-align:right" width="15%">Running Balance</th>
        		    </tr>
          		</thead>
          		<tbody>
          			<?php $running_balance = 0;$total_debit = 0;$total_credit = 0; ?>
            		<tr>
              			<td style="text-align:center"></td>
              			<td></td>
              			<td>Opening Balance</td>
          				<td></td>
              			<td></td>
              			<td style="text-align:right;padding-right:10px;">
              				<?php
                              $opening_balance = (isset($data['opening_balance']) && $data['opening_balance'] != '') ? $data['opening_balance'] : 0;
                              echo amount_format(number_format(abs($opening_balance), 2, '.', ''));echo ($opening_balance > 0) ? ' Dr' : ' Cr';
                              $running_balance += $opening_balance;
                            ?>
              			</td>
            		</tr>
            		<?php if(!empty($data['data'])){ ?>
            			<?php foreach ($data['data'] as $key => $value) { ?>
        		    		<tr>
        		      			<td><?php echo $key+1; ?></td>
        		      			<td >
        		      				<?php echo (isset($value['date']) && $value['date'] != '') ? date('d/m/Y', strtotime($value['date'])) : ''; ?>
        		      			</td>
        		      			<td>
        		      				<?php echo (isset($value['narration'])) ? $value['narration'] : ''; ?>
        		      			</td>
        		      			<td style="text-align:right;padding-right:10px;">
        		      				
                                     <?php
                                           if(isset($value['debit']) && $value['debit'] != ''){
                                              echo  amount_format(number_format($value['debit'], 2, '.', ''));
                                              $total_debit += $value['debit'];
                                              $running_balance += $value['debit'];
                                            }
                                        ?>
        		      			</td>
        		      			<td style="text-align:right;padding-right:10px;">
        	                        <?php
        	                           if(isset($value['credit']) && $value['credit'] != ''){
                                          echo  amount_format(number_format($value['credit'], 2, '.', ''));
                                          $total_credit += $value['credit'];
                                          $running_balance -= $value['credit'];
                                        }
                                     ?>
        		      			</td>
        		      			<td style="text-align:right;padding-right:10px;">
        		      				<?php
        	                            echo (isset($running_balance)) ? amount_format(number_format(abs($running_balance), 2, '.', '')) : 0;
        	                            echo (isset($running_balance) && $running_balance > 0) ? ' Dr' : ' Cr';
        	                        ?>
        		      			</td>
        		    		</tr>
        		    	<?php } ?>
        	    	<?php } ?>
          		</tbody>
        
          		<tr style="font-size:14px;font-weight:bold;">
        			<td colspan="3" style="text-align:center;"> Total / Closing Balance</td>
        			
        			<td style="text-align:right;padding-right:10px;"><?php echo (isset($total_debit)) ? amount_format(number_format($total_debit, 2, '.', '')) : 0; ?></td>
        			<td style="text-align:right;padding-right:10px;"><?php echo (isset($total_credit)) ? amount_format(number_format($total_credit, 2, '.', '')) : 0; ?></td>
        			<td style="text-align:right;padding-right:10px;">
        	            <?php
                            echo (isset($running_balance)) ? amount_format(number_format(abs($running_balance), 2, '.', '')) : 0;
                            echo (isset($running_balance) && $running_balance > 0) ? ' Dr' : ' Cr';
                        ?>
        			</td>
          		</tr>
        
        	</table>
        <?php } ?>
    <?php } ?>
</body>
</html>