<?php  include('include/config.php'); 
if(!isset($_SESSION['auth']) || empty($_SESSION['auth']) || $_SESSION['auth']=="" ){
  header("Location:login.php");
  exit;
}else{
  header("Location:dashboard.php");
  exit;
}
?>