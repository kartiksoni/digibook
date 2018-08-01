<?php  include('include/config.php'); 
if(!isset($_SESSION['auth']) || empty($_SESSION['auth']) || $_SESSION['auth'] == ""){
  header("Location:login.php");
  exit;
}else{
  	if($_SESSION['auth']['type'] != '' && !empty($_SESSION['auth']['type'])){
	    if($_SESSION['auth']['type'] == "PHARMACY"){
	      echo'<script>window.location="pharmacy/index.php";</script>';
	    }

	    if($_SESSION['auth']['type'] == "IPD"){
	      echo'<script>window.location="ipd/index.php";</script>';
	    }

	    if($_SESSION['auth']['type'] == "FINANCE"){
	      echo'<script>window.location="finance/index.php";</script>';
	    }

	    if($_SESSION['auth']['type'] == "GENERAL"){
	      echo'<script>window.location="general/index.php";</script>';
	    }
	}else{
	  echo'<script>window.location="index-dashboard.php";</script>';
	}
}
?>