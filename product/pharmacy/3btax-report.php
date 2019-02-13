<?php $title = "Customer Ledger"; ?>
<?php include('include/usertypecheck.php');?>
<?php include('include/permission.php'); ?>

<?php 
$pharmacy_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : '';
$financial_id = (isset($_SESSION['auth']['financial']) && $_SESSION['auth']['financial'] != '') ? $_SESSION['auth']['financial'] : '';

if(isset($_POST['search'])){

 $from = (isset($_POST['from']) && $_POST['from'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_POST['from']))) : ''; 
 $to = (isset($_POST['to']) && $_POST['to'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_POST['to']))) : '';

  //-------------------------GST ON SALES--------------------------start---------------------------//
 $qry1 = "SELECT  SUM(tbd.amount) as total_amount, SUM(tbd.freeqty * tbd.rate) as freeamount ,tbd.sgst ,tbd.cgst  FROM tax_billing_details tbd INNER JOIN tax_billing tb ON tbd.tax_bill_id = tb.id WHERE tb.pharmacy_id = '".$pharmacy_id."'";
   if((isset($from) && $from != '') && (isset($to) && $to != '')){
          $qry1 .=  " AND tb.invoice_date >= '".$from."' AND tb.invoice_date <= '".$to."'";  
      } 
  $qry1 .= "GROUP BY tbd.sgst ,tbd.cgst"; 
 $result1 = mysqli_query($conn,$qry1);
if($result1){
 $credit= 0;  $debit= 0; $totalcr_dr = 0;
 while ($sum = mysqli_fetch_assoc($result1)) {
  if(isset($sum['sgst'])){
    $sgst = $sum['sgst'];
    $totalSGST = $sum['total_amount'] + (($sum['total_amount'] * $sgst) / 100);
     $credit += number_format($totalSGST, 2, '.', '');
     $debit += number_format($debit, 2, '.', '');
     $totalcr_dr = $credit + $debit;

  }
if(isset($sum['cgst'])){
    $cgst = $sum['cgst'];
    $totalCGST = $sum['total_amount'] + (($sum['total_amount'] * $sgst) / 100);
     $credit += number_format($totalCGST, 2, '.', '');
     $debit += number_format($debit, 2, '.', '');
     $totalcr_dr = $credit + $debit;

  }
}}

//-------------------------GST ON PURCHASE--------------------------start---------------------------//
$qry2 = "SELECT pd.id, SUM(pd.ammout) as total_amount , SUM(pd.free_qty * pd.f_rate) as freeamount , pd.f_cgst , pd.f_sgst FROM purchase_details pd INNER JOIN  purchase p ON pd.purchase_id = p.id WHERE p.pharmacy_id = '".$pharmacy_id."'";
  if((isset($from) && $from != '') && (isset($to) && $to != '')){
    $qry2 .=  " AND p.vouchar_date >= '".$from."' AND p.vouchar_date <= '".$to."'";  
       } 
   $qry2 .= " GROUP BY pd.f_cgst ,pd.f_sgst";   
$result2 = mysqli_query($conn,$qry2);
if($result2){
$credit1= 0;  $debit1= 0; $totalcr_dr1 = 0;
while ($sum1 = mysqli_fetch_assoc($result2)) {

  if(isset($sum1['f_cgst'])){
   $f_cgst =  $sum1['f_cgst'];
   $totalF_CGST = $sum1['total_amount'] + (($sum1['total_amount'] * $f_cgst) / 100);
    $credit1 += number_format($credit1, 2, '.', '');
    $debit1 += number_format($totalF_CGST, 2, '.', '');
    $totalcr_dr1 = $credit1 + $debit1;
  }
 if(isset($sum1['f_sgst'])){
   $f_sgst =  $sum1['f_sgst'];
   $totalF_SGST = $sum1['total_amount'] + (($sum1['total_amount'] * $f_cgst) / 100);
    $credit1 += number_format($credit1, 2, '.', '');
    $debit1 += number_format($totalF_SGST, 2, '.', '');
    $totalcr_dr1 = $credit1 + $debit1;
  }
}}

//-------------------------IGST ON PURCHASE--------------------------start---------------------------//
$qry3 = "SELECT pd.id, SUM(pd.ammout) as total_amount , SUM(pd.free_qty * pd.f_rate) as freeamount , pd.f_igst FROM purchase_details pd INNER JOIN  purchase p ON pd.purchase_id = p.id WHERE p.pharmacy_id = '".$pharmacy_id."'";  
if((isset($from) && $from != '') && (isset($to) && $to != '')){
            $qry3 .=  " AND p.vouchar_date >= '".$from."' AND p.vouchar_date <= '".$to."'";  
        } 
  $qry3 .= "GROUP BY pd.f_igst" ; 
$result3 = mysqli_query($conn,$qry3);
if($result3){
$credit2= 0;  $debit2= 0; $totalcr_dr2 = 0;
while ($sum2 = mysqli_fetch_assoc($result3)) {
  $f_igst = $sum2['f_igst'];
  $totalF_IGST = $sum2['total_amount'] + (($sum2['total_amount'] * $f_igst) / 100);
  $credit2 += number_format($credit2, 2, '.', '');
  $debit2 += number_format($totalF_IGST, 2, '.', '');
  $totalcr_dr2 = $credit2 + $debit2;
}
}
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Digibooks | 3B Tax Report</title>
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
  <link rel="stylesheet" href="vendors/iconfonts/simple-line-icon/css/simple-line-icons.css">
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="css/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="images/favicon.png" />
  <link rel="stylesheet" href="css/parsley.css">
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
          <?php include('include/flash.php'); ?>
          <span id="errormsg"></span>

          <div class="row">

            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">3B Tax Report</h4><hr class="alert-dark">
                  <form class="forms-sample" method="POST" autocomplete="off">
                    <div class="form-group row">

                      <div class="col-12 col-md-2">
                        <label for="from">From</label>
                        <input type="text" class="form-control datepicker" name="from" id="from" placeholder="DD/MM/YYYY" value="<?php echo (isset($_POST['from'])) ? $_POST['from'] : date('d/m/Y'); ?>" required>
                      </div>

                      <div class="col-12 col-md-2">
                        <label for="to">To</label>
                        <input type="text" class="form-control datepicker" name="to" id="to" placeholder="DD/MM/YYYY" value="<?php echo (isset($_POST['to'])) ? $_POST['to'] : date('d/m/Y'); ?>" required>
                      </div>



                      <div class="col-12 col-md-5 col-sm-12">
                        <button type="submit" name="search" class="btn btn-success mt-30">Go</button>
                        <?php if(isset($_POST['search'])){ ?> 
                          <a href="3btax-report-print.php?from=<?php echo (isset($_POST['from'])) ? $_POST['from'] : ''; ?>&to=<?php echo (isset($_POST['to'])) ? $_POST['to'] : ''; ?>" class="btn btn-primary mt-30" title="Print" target="_blank">Print</a>
                        <?php } ?> 
                        
                       </div>

                     </div>
                   </form>
                 </div>
               </div>
             </div>

              <?php if(isset($_POST['search'])) {?>
             <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <div class="row">

                    <div class="col-12">
                      <table class="table table-bordered table-striped datatable">
                        <thead>
                          <tr>
                            <th colspan="3">Account Head/Narration</th>
                            <th width="8%">Debit</th>
                            <th width="8%">Credit</th> 
                            <th class="text-center">Debit-Credit</th>
                            <th class="text-center">Free Goods Amt</th>

                          </tr> 
                        </thead>
                       
                        <tbody>
                          <?php $total_debit = 0; $total_credit = 0; $freegoods = 0;?>
                          <tr>
                            <td colspan="3">GST ON SALES</td>
                            <td class="text-right"><?php if(isset($debit)){
                                      echo number_format($debit,2,'.','');
                                    }?></td>
                            <td class="text-right"><?php if(isset($credit)){
                                  echo number_format($credit,2,'.',''); }?></td>
                            <td class="text-right"><?php if(isset($totalcr_dr)){
                              echo number_format($totalcr_dr,2,'.','').' Cr';}?></td>
                            <td class="text-center"></td>                                

                          </tr>
                          <?php 
                          
                          if ($result1 && mysqli_num_rows($result1) > 0){
                            foreach ($result1 as $row ) { 
                             
                              ?>
                              <tr>
                                <td>SGST : <?php echo number_format($row['sgst'], 2, '.', '').'%' ;?></td>
                                <td class="text-right">
                                  <?php echo '0.00';
                                  $total_debit += '0.00';
                                  ?></td>
                                  <td class="text-right">
                                    <?php
                                    if(isset($row['total_amount']) && $row['total_amount'] != ''){
                                      $sgst = $row['sgst'];
                                      $totalSGST = $row['total_amount'] + (($row['total_amount'] * $sgst) / 100);
                                      echo  number_format($totalSGST, 2, '.', '');
                                      $total_credit += $totalSGST;
                                    }
                                    ?>
                                  </td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td style="text-align:right;">
                                    <?php if(isset($row['freeamount']) && $row['freeamount'] != ''){
                                      echo number_format($row['freeamount'], 2, '.', '');
                                      $freegoods += $row['freeamount'];
                                    } ?></td>
                                </tr>
                                <tr>
                                <td>CGST : <?php echo number_format($row['cgst'], 2, '.', '').'%' ;?></td>
                                <td class="text-right">
                                  <?php echo '0.00';
                                  $total_debit += '0.00';
                                  ?></td>
                                  <td class="text-right">
                                    <?php
                                    if(isset($row['total_amount']) && $row['total_amount'] != ''){
                                        $cgst = $row['cgst'];
                                      $totalCGST = $row['total_amount'] + (($row['total_amount'] * $cgst) / 100);
                                      echo  number_format($totalCGST, 2, '.', '');
                                      $total_credit += $totalCGST;
                                    }
                                    ?>
                                  </td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td style="text-align:right;">
                                    <?php if(isset($row['freeamount']) && $row['freeamount'] != ''){
                                      echo number_format($row['freeamount'], 2, '.', '');
                                      $freegoods += $row['freeamount'];
                                    } ?></td>
                                </tr>
                              <?php }  }?>

                              <tr>
                                <td colspan="3">GST ON PURCHASE</td>
                                <td class="text-right"><?php if(isset($debit1)){
                                      echo number_format($debit1,2,'.',''); }?></td>
                                <td class="text-right"><?php if(isset($credit1)){
                                    echo number_format($credit1,2,'.',''); }?></td>
                                 <td class="text-right"><?php if(isset($totalcr_dr1)){
                                    echo number_format($totalcr_dr1,2,'.','').' Dr';}?></td>
                             
                                <td class="text-center"></td>  
                              </tr>
                              <?php 
                              if (isset($result2)){
                                foreach ($result2 as $row) { 
                                  ?>
                                  <tr>
                                    <td>SGST : <?php echo number_format($row['f_sgst'] *2, 2, '.', '').'%' ;?></td>
                                    <td class="text-right">
                                      <?php
                                      if(isset($row['total_amount']) && $row['total_amount'] != ''){
                                         $sgst = $row['f_sgst'];
                                          $totalSGST = $row['total_amount'] + (($row['total_amount'] * $sgst) / 100);
                                        echo  number_format($totalSGST, 2, '.', '');
                                        $total_debit += $totalSGST;
                                      }
                                      ?>
                                    </td>
                                    <td class="text-right"><?php echo '0.00';
                                    $total_credit += '0.00';

                                    ?></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td style="text-align:right;">
                                    <?php if(isset($row['freeamount']) && $row['freeamount'] != ''){
                                      echo number_format($row['freeamount'], 2, '.', '');
                                      $freegoods += $row['freeamount'];
                                    } ?></td>
                                  </tr>
                                  <tr>
                                    <td>CGST : <?php echo number_format($row['f_cgst'] *2, 2, '.', '').'%' ;?></td>
                                    <td class="text-right">
                                      <?php
                                      if(isset($row['total_amount']) && $row['total_amount'] != ''){
                                        $cgst = $row['f_cgst'];
                                        $totalCGST = $row['total_amount'] + (($row['total_amount'] * $cgst) / 100);
                                        echo  number_format($totalCGST, 2, '.', '');
                                        $total_debit += $totalCGST;
                                      }
                                      ?>
                                    </td>
                                    <td class="text-right"><?php echo '0.00';
                                    $total_credit += '0.00';
                                    ?></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td style="text-align:right;">
                                    <?php if(isset($row['freeamount']) && $row['freeamount'] != ''){
                                      echo number_format($row['freeamount'], 2, '.', '');
                                      $freegoods += $row['freeamount'];
                                    } ?></td>
                                  </tr>
                                <?php }  }?>
                                <tr>
                                  <td colspan="3">IGST ON PURCHASE</td>
                                   <td class="text-right"><?php if(isset($debit2)){
                                      echo number_format($debit2,2,'.',''); }?></td>
                                   <td class="text-right"><?php if(isset($credit2)){
                                    echo number_format($credit2,2,'.',''); }?></td>
                                   <td class="text-right"><?php if(isset($totalcr_dr2)){
                                    echo number_format($totalcr_dr2,2,'.','').' Dr';}?></td>
                                  <td class="text-center"></td>  
                                </tr>
                                <?php 
                                if (isset($result3)){
                                  foreach ($result3 as $row ) { ?>
                                    <tr>
                                      <td>IGST : <?php echo number_format($row['f_igst'], 2, '.', '').'%' ;?></td>
                                      <td class="text-right">
                                        <?php
                                        if(isset($row['total_amount']) && $row['total_amount'] != ''){
                                          $igst = $row['f_igst'];
                                          $totalIGST = $row['total_amount'] + (($row['total_amount'] * $igst) / 100);
                                          echo  number_format($totalIGST, 2, '.', '');
                                          $total_debit += $totalIGST;
                                        }?>
                                      </td>
                                      <td class="text-right"><?php echo '0.00';
                                      $total_credit += '0.00';?>
                                      </td>
                                      <td></td>
                                      <td></td>
                                      <td></td>
                                      <td style="text-align:right;">
                                    <?php if(isset($row['freeamount']) && $row['freeamount'] != ''){
                                      echo number_format($row['freeamount'], 2, '.', '');
                                      $freegoods += $row['freeamount'];
                                    } ?></td>
                                      </tr>
                                    <?php }  }?>
                                  </tbody>
                                  <tfoot>
                                    <tr style="background-color: #EFEFEF;">
                                      <th colspan="3" class="text-center"></th>
                                      <th class="text-right">
                                        <?php 
                                        echo number_format($total_debit, 2,'.', '');
                                        ?>
                                      </th>
                                      <th class="text-right">
                                        <?php 
                                        echo number_format($total_credit, 2, '.', '');
                                        ?>                                          
                                        </th>

                                        <th class="text-right">
                                        </th>
                                        <th class="text-right">
                                          <?php 
                                        echo number_format($freegoods, 2, '.', '');
                                        ?> 
                                        </th>
                                      </tr>
                                    </tfoot>
                                      
                                  </table>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
 <?php }?>

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

              <!-- Custom js for this page Modal Box-->
              <script src="js/modal-demo.js"></script>


              <!-- Datepicker Initialise-->
              <script>
                $('.datepicker').datepicker({
                  enableOnReadonly: true,
                  todayHighlight: true,
                  format: 'dd/mm/yyyy',
                  autoclose : true
                });
              </script>

              <!-- Custom js for this page Datatables-->
              <script src="js/data-table.js"></script> 
              <script>
               $('.datatable').DataTable( {
                fixedHeader: {
                  header: true,
                  footer: true
                }
              } );
            </script>
            <!-- script for custom validation -->
            <script src="js/parsley.min.js"></script>
            <script type="text/javascript">
              $('form').parsley();
            </script>
            <script src="js/custom/onlynumber.js"></script>
            <!-- <script src="js/custom/customer_ledger.js"></script> -->
            <!-- End custom js for this page-->


          </body>


          </html>
