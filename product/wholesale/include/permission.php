<?php 

if(!checkpermission($title)){
    header('Location:index.php');
    exit;
}
?>