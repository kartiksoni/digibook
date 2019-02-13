<?php 

if(!checkpermission($title)){
    $_SESSION['msg']['fail'] = "You dont't have permission to accesss this location!";
    header('Location:index.php');
    exit;
}
?>