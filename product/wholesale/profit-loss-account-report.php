<?php $title = "Trading Account Report"; ?>
<?php include('include/usertypecheck.php'); ?>
<?php //include('include/permission.php'); ?>
<?php

if(isset($_POST['search'])){

 $from = (isset($_POST['from']) && $_POST['from'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_POST['from']))) : ''; 
 $to = (isset($_POST['to']) && $_POST['to'] != '') ? date('Y-m-d',strtotime(str_replace("/","-",$_POST['to']))) : '';
 $ProfitLoss = profit_loss($from ,$to );
}


$financial_year =   (isset($_SESSION['auth']['financial'])) ? $_SESSION['auth']['financial'] : '';
$pharmacy_id = (isset($_SESSION['auth']['pharmacy_id']) && $_SESSION['auth']['pharmacy_id'] != '') ? $_SESSION['auth']['pharmacy_id'] : '';  
$financial_query = mysqli_fetch_assoc(mysqli_query($conn,'SELECT * FROM `financial` WHERE id = "'.$financial_year.'" AND status = 1'));

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Digibooks | Trading / Profit & Loss Account Report</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="vendors/iconfonts/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="vendors/iconfonts/puse-icons-feather/feather.css">
    <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="vendors/css/vendor.bundle.addons.css">
    <link rel="stylesheet" href="css/parsley.css">
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
    <link rel="stylesheet" href="css/toggle/style.css">
    <style>
    .table-bordered td {
    border: 0px solid #f3f3f3;
}
</style>
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
                        <?php include "include/account_menus_header.php"; ?>
                        <div class="col-md-12 grid-margin stretch-card">
                            <div class="card">
                              <div class="card-body">
                                <h4 class="card-title">Trading / Profit & Loss Account Report</h4><hr class="alert-dark">
                                <form class="forms-sample" method="POST" autocomplete="off">
                                  <div class="form-group row">
                                   
                                    <div class="col-12 col-md-2">
                                        <label>Month</label>
                                        <select class="form-control" name="month" id="month">
                                          <option value="" data-start="<?php echo date('d/m/Y'); ?>" data-end="<?php echo date('d/m/Y'); ?>">Select Month</option>
                                          <?php
                                            $month = [1 => 'January',2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December']; 
                                            foreach ($month as $key => $value) {
                                          ?>
                                            <?php
                                              $first_day_this_month = date('01-'.sprintf("%02d", ($key)).'-Y');
                                              $last_day_this_month = date("t-m-Y", strtotime($first_day_this_month));
                                            ?>
                                            <option data-start="<?php echo date('d/m/Y',strtotime($first_day_this_month)); ?>" data-end="<?php echo date('d/m/Y',strtotime($last_day_this_month)); ?>" value="<?php echo $key; ?>" <?php echo (isset($_POST['month']) && $_POST['month'] == $key) ? 'selected' : ''; ?> ><?php echo $value; ?></option>
                                          <?php } ?>
                                        </select>
                                    </div>
                                    
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
                                      <?php if(isset($ProfitLoss)){ ?>
                                        <a href="profit-loss-account-report-print.php?from=<?php echo (isset($_POST['from'])) ? $_POST['from'] : ''; ?>&to=<?php echo (isset($_POST['to'])) ? $_POST['to'] : ''; ?>" class="btn btn-primary mt-30" title="Print" target="_blank">Print</a>
                                      <?php } ?>
                                    </div>
            
                                  </div>
                                </form>
                              </div>
                            </div>
                        </div>
              <?php if(isset($ProfitLoss)) { ?>
                        <div class="col-md-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <!-- Trading Account Report -->
                                    <div class="col mt-3">
                                       
                                        <div class="row">
                                            <div class="col-12">

                                                <!--Report Table Start-->
                                                <table class="table table-bordered m-0">
                                                    <tbody>
                                                       <!--calculation-->
                                                       <tr>
                                                        <!--Credit -->
                                                        <td style="vertical-align: initial;">
                                                            <table class="table table-bordered m-0">
                                                                <thead>
                                                                    <tr>
                                                                        <th><b>Particulars</b></th>
                                                                        <th style="text-align:right;"><?php echo date('d-M-Y',strtotime(str_replace("/","-",$_POST['from']))) .' to '.date('d-M-Y',strtotime(str_replace("/","-",$_POST['to'])));?></th>
                                                                    </tr>
                                                                </thead>

                                                                <tbody>
                                                                    <tr>
                                                                       <td>
                                                                           <b>Opening Stock</b>                                
                                                                       </td>
                                                                       <td style="text-align:right;">
                                                                         <b><?php 
                                                                           $OpeningStock = $ProfitLoss['left']['OpeningStock'];
                                                                           echo amount_format(number_format(($OpeningStock), 2, '.', ''));
                                                                            ?> 
                                                                         </b>                                
                                                                        </td> 
                                                                   </tr>
                                                                   <tr>
                                                                    <td>
                                                                       <ul class="list-group">
                                                                          <li class="list-group" style="margin-left: 20px;">
                                                                             Stock                                      
                                                                         </li>
                                                                     </ul>                                
                                                                 </td>
                                                                 <td>
                                                                   <ul class="list-group">
                                                                      <li class="list-group" style="text-align:right; margin-right:58%; border-bottom:1px solid;">
                                                                        <?php 
                                                                        $OpeningStock =  $ProfitLoss['left']['OpeningStock'];
                                                                        echo amount_format(number_format(($OpeningStock), 2, '.', ''));
                                                                        ?>  
                                                                    </li>
                                                                </ul>                                
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                           <td >
                                                               <b>Purchase Accounts</b>                                
                                                           </td>   
                                                           <td style="text-align:right;">
                                                               <b><?php 
                                                               $Purchase = $ProfitLoss['left']['PurchaseAccounts']['total_amount']; 
                                                               echo amount_format(number_format(($Purchase), 2, '.', ''));
                                                               ?> 
                                                               </b>                                
                                                           </td>                              
                                                       </tr>

                                                       <tr>
                                                        <td>
                                                           <ul class="list-group">
                                                              <li class="list-group" style="margin-left: 20px;">
                                                                 Purchase                                      
                                                             </li>
                                                         </ul>                                
                                                     </td>
                                                     <td>
                                                       <ul class="list-group">
                                                          <li class="list-group" style="text-align:right; margin-right:58%; border-bottom:1px solid;">
                                                            <?php 
                                                            $Purchase = $ProfitLoss['left']['PurchaseAccounts']['total_amount']; 
                                                            echo amount_format(number_format(($Purchase), 2, '.', ''));
                                                            ?> 
                                                        </li>
                                                    </ul>                                
                                                </td>
                                            </tr>

                                            <tr>
                                               <td>
                                                   <b>Direct Expenses</b>                                
                                               </td>
                                               <td style="text-align:right;">
                                                   <b><?php 
                                                   $TotalDirectExpenses = $ProfitLoss['left']['TotalDirectExpenses']; 
                                                   echo amount_format(number_format(($TotalDirectExpenses), 2, '.', ''));
                                                   ?> 
                                               </b> 
                                             </td>                                  
                                           </tr>

                                           <tr>
                                            <td>
                                               <ul class="list-group">
                                                <?php 
                                                $DirectExpensesArr= $ProfitLoss['left']['DirectExpenses']; 
                                                foreach ($DirectExpensesArr as $value){
                                                   ?>
                                                   <li class="list-group-item" style="margin-left: 20px;">
                                                    <?php echo $value['name'];?>                              
                                                </li>
                                            <?php  }?>
                                        </ul>                                  
                                    </td>

                                    <td>
                                       <ul class="list-group">
                                         <?php foreach ($DirectExpensesArr as $key => $value){
                                             ?>
                                             <li class="list-group" style="text-align:right;margin-right:58%;<?php echo ($key == (count($DirectExpensesArr) - 1)) ? 'border-bottom:1px solid;' : ''; ?>">
                                                <?php echo amount_format(number_format(($value['expense']), 2, '.', ''));
                                                ?>
                                            </li>
                                        <?php  }?>
                                    </ul>                                
                                </td>
                            </tr>

                            <tr>
                               <td>
                                <ul class="list-group">
                                    <?php  $GrossProfit = $ProfitLoss['profit']['Grossprofit']; 
                                         if($GrossProfit >= 0){ ?>
                                           Gross Profit c/o       
                                         <?php } else { ?>
                                        Gross Loss c/o    
                                      <?php }  ?>
                                    
                               </ul>                          
                           </td>
                           <td style="text-align:right;">
                            <ul>
                             <b> <?php 
                             $GrossProfit = $ProfitLoss['profit']['Grossprofit']; 
                             echo amount_format(number_format(($GrossProfit), 2, '.', '')); 
                             ?></b>
                         </ul></td>                                  
                     </tr>

                     <tr>
                         <td colspan="2" style="text-align:right; ">
                             <b style="border-bottom:1px solid; border-top: 1px solid;"><?php 
                             $Total =  $ProfitLoss['profit']['Total']; 
                             echo amount_format(number_format(($Total), 2, '.', '')); ?>
                         </b>                               
                     </td>                                
                 </tr>

                 <tr>                                                
                   <td>
                       <b>Indirect Expenses</b>                                
                   </td> 
                   <td style="text-align:right;">
                       <b><?php 
                       $TotalIndirectExpenses = $ProfitLoss['left']['TotalIndirectExpenses']; 
                       echo amount_format(number_format(($TotalIndirectExpenses), 2, '.', ''));
                       ?></b>                                
                   </td>                                
               </tr>
               <tr>
                <td>

                    <ul class="list-group">
                        <?php     $IndirectExpensesArr = $ProfitLoss['left']['IndirectExpenses'];
                        foreach ($IndirectExpensesArr as $value){
                           ?>
                           <li class="list-group" style="margin-left: 20px;">
                            <?php echo $value['name'];?>                              
                        </li>
                    <?php  }?>
                </ul>                                  
            </td>

            <td>
               <ul class="list-group">
                 <?php foreach ($IndirectExpensesArr as $key => $value){
                     ?>
                     <li class="list-group" style="text-align:right;margin-right:58%;<?php echo ($key == (count($IndirectExpensesArr) - 1)) ? 'border-bottom:1px solid;' : ''; ?>">
                        <?php echo amount_format(number_format(($value['expense']), 2, '.', ''));
                        ?>   
                    </li>
                <?php  }?>
            </ul>                                
        </td>
    </tr>

    <tr>
        <td>
           <ul class="list-group">
              <li class="list-group">
                   <?php  $netProfit = $ProfitLoss['profit']['NetProfit']; 
                       if($netProfit >= 0){ ?>
                        NET PROFIT       
                         <?php } else { ?>
                         NET LOSS  
                        <?php }  ?>
                                                 
             </li>
         </ul>                                
     </td>

     <td>
        <ul class="list-group">
          <li class="list-group" style="text-align:right;">
             <b><?php $netProfit = $ProfitLoss['profit']['NetProfit'];
             echo amount_format(number_format($netProfit, 2, '.', ''));
             ?> </b>                         
         </li>
     </ul>                                
 </td>
</tr>

</tbody>
</table>
</td>


<!--Debit-->
<td style="vertical-align: initial;">
    <table class="table table-bordered m-0">
        <thead>
          <tr>
            <th><b>Particulars</b></th>
            <th style="text-align:right;"><?php echo date('d-M-Y',strtotime(str_replace("/","-",$_POST['from']))) .' to '.date('d-M-Y',strtotime(str_replace("/","-",$_POST['to'])));?></th>
        </tr>
    </thead>
    <tbody>

        <tr>
           <td>
               <b>Sales Accounts</b>                                
           </td>   
           <td style="text-align:right;">
               <b><?php 
               $sales = $ProfitLoss['right']['SaleAccount']['total_amount'];
               echo amount_format(number_format(($sales), 2, '.', ''));
               ?>
             </b>                                
           </td>                              
       </tr>

       <tr>
        <td>
           <ul class="list-group">
              <li class="list-group" style="margin-left: 20px;">
                 Sales                                      
             </li>
         </ul>                                
     </td>

     <td>
       <ul class="list-group">
         <li class="list-group" style="text-align:right; margin-right:58%; border-bottom:1px solid;">
            <?php 
            $sales = $ProfitLoss['right']['SaleAccount']['total_amount'];
            echo amount_format(number_format(($sales), 2, '.', ''));
            ?>                      
        </li>
    </ul>                                
</td>
</tr>

<tr>
   <td>
       <b>Direct Incomes</b>                                
   </td>
   <td style="text-align:right;">
       <b><?php 
       $TotalDirectIncome = $ProfitLoss['right']['TotalDirectIncome']; 
       echo amount_format(number_format(($TotalDirectIncome), 2, '.', ''));

       ?> </b>                                
   </td>                                  
</tr>

<tr>
    <td>
       <ul class="list-group">
        <?php 
        $DirectIncomeArr= $ProfitLoss['right']['DirectIncome']; 
        foreach ($DirectIncomeArr as $value){
           ?>
           <li class="list-group" style="margin-left: 20px;">
            <?php echo $value['name'];?>                              
        </li>
    <?php  }?>
</ul>                                  
</td>
<td>
   <ul class="list-group">
     <?php foreach ($DirectIncomeArr as $key => $value){
         ?>
         <li class="list-group" style="text-align:right;margin-right:58%;<?php echo ($key == (count($DirectIncomeArr) - 1)) ? 'border-bottom:1px solid;' : ''; ?>">
            <?php echo amount_format(number_format(($value['expense']), 2, '.', ''));
            ?>   
        </li>
    <?php  }?>
</ul>                                
</td>
</tr>

<tr>
   <td>
       <b>Closing Stock</b>                                
   </td>  
   <td style="text-align:right;">
       <b><?php 
       $ClosingStock = $ProfitLoss['right']['ClosingStock']; 
       echo amount_format(number_format(($ClosingStock), 2, '.', ''));

       ?> </b>                                
   </td>                               
</tr>
<tr>
    <td>
       <ul class="list-group">
          <li class="list-group" style="margin-left: 20px;">
             Stock                                      
         </li>
     </ul>                                
 </td>
 <td>
   <ul class="list-group">
       <li class="list-group" style="text-align:right; margin-right:58%; border-bottom:1px solid;">
        <?php 
        $ClosingStock =  $ProfitLoss['right']['ClosingStock'];
        echo amount_format(number_format(($ClosingStock), 2, '.', ''));
        ?>                      
    </li>
</ul>                                
</td>
</tr>

<tr>
 <td colspan="2" style="text-align:right; ">
     <b style="border-bottom:1px solid; border-top: 1px solid;"><?php 
     $Total =  $ProfitLoss['profit']['Total']; 
     echo amount_format(number_format(($Total), 2, '.', '')); ?>
 </b>                               
</td>                                
</tr>

<tr>
   <td>
    <ul class="list-group">
      <?php  $GrossProfit = $ProfitLoss['profit']['Grossprofit']; 
         if($GrossProfit >= 0){ ?>
           Gross Profit b/f       
         <?php } else { ?>
        Gross Loss b/f    
      <?php }  ?>
       
   </ul>                          
</td>
<td style="text-align:right;">
    <ul>
        <b>  <?php   
        $GrossProfit = $ProfitLoss['profit']['Grossprofit']; 
        echo amount_format(number_format(($GrossProfit), 2, '.', ''));  ?></b>
    </ul></td>                                  
</tr>

<tr>
   <td >
       <b>Indirect Incomes</b>                                
   </td>
   <td style="text-align:right;">
       <b><?php 
       $TotalInDirectIncome = $ProfitLoss['right']['TotalInDirectIncome']; 
       echo amount_format(number_format(($TotalInDirectIncome), 2, '.', ''));

       ?> </b>                                
   </td>                                  
</tr>

<tr>
    <td>
       <ul class="list-group">
        <?php 
        $IndirectIncome= $ProfitLoss['right']['IndirectIncome']; 
        foreach ($IndirectIncome as $value){
           ?>
           <li class="list-group" style="margin-left: 20px;">
            <?php echo $value['name'];?>                              
        </li>
    <?php  }?>
</ul>                                  
</td>
<td>
   <ul class="list-group">
     <?php foreach ($IndirectIncome as $key => $value){
         ?>
         <li class="list-group" style="text-align:right;margin-right:58%;<?php echo ($key == (count($IndirectIncome) - 1)) ? 'border-bottom:1px solid;' : ''; ?>">
            <?php echo amount_format(number_format(($value['expense']), 2, '.', ''));

            ?>   
        </li>
    <?php  }?>

</ul>                                
</td>
</tr>

</tbody>
</table>
</td>
</tr>

<tr>
    <td>
        <ul class="list-group">
           <li class="list-group-item" style="text-align:right;">
              <b>
                  Total : <?php 
                  $Total =  $ProfitLoss['profit']['Total']; 
                  echo amount_format(number_format(($Total), 2, '.', '')); ?>
              </b>
          </li>
      </ul>
  </td>

  <td>
    <ul class="list-group">
       <li class="list-group-item" style="text-align:right;">
          <b>
           Total :  <?php 
           $Total =  $ProfitLoss['profit']['Total']; 
           echo amount_format(number_format(($Total), 2, '.', '')); ?>
       </b>                                            
   </li>
</ul>
</td>
</tr>
</tbody>

</table>
<!--Report Table End-->
<!--<div class="hidden-print" style="margin-top:10px;">-->
<!--  <div class="pull-right"> <a href="profit-loss-account-report-print.php" target="_blank" class="btn btn-behance p-2 waves-effect waves-light"><i class="fa fa-print mr-0"></i></a>-->
<!--  </div>-->
<!--</div>-->
</div>
</div>
</div>
</div>
</div>
</div>
<?php } ?>
</div>
</div>
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
<script src="js/select2.js"></script>
<script>
    $('.datepicker').datepicker({
      enableOnReadonly: true,
      todayHighlight: true,
      format: 'dd/mm/yyyy',
      autoclose : true
    });
    
     $('#month').on('change', function() {
        var startdate = $(this).find(':selected').attr('data-start');
        var enddate = $(this).find(':selected').attr('data-end');

        $('#from').attr('value', startdate);
        $('#to').attr('value', enddate);
        $(".datepicker").datepicker();
      });
      
 </script>
<!-- endinject -->
<!-- End custom js for this page-->
</body>
</html>
