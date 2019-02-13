<?php
$i_pharmacyQ = "SELECT `ihis_hsp_id` FROM `pharmacy_profile` WHERE id='".$_SESSION['auth']['pharmacy_id']."' AND ihis_hsp_id != 0 AND ihis_hsp_id IS NOT NULL";
$i_pharmacyR = mysqli_query($conn, $i_pharmacyQ);
if($i_pharmacyR && mysqli_num_rows($i_pharmacyR) > 0){
    $data_ihis = mysqli_fetch_array($i_pharmacyR);
    if(!defined('ihis_firm_id')){
        define("ihis_firm_id",$data_ihis['ihis_hsp_id']);
    }
}else{
    if(!defined('ihis_firm_id')){
        define("ihis_firm_id",NULL);
    }
}

$ihis_conn = mysqli_connect('localhost', 'yamunxym_ihisusr', 'meditech@321', 'yamunxym_ihims');

if ($ihis_conn->connect_error) {
    die("Connection failed: " . $ihis_conn->connect_error);
}  

mysqli_character_set_name($ihis_conn);
/* change character set to utf8 */
if (!mysqli_set_charset($ihis_conn, "utf8"))
{
    printf("Error loading character set utf8: %s\n", mysqli_error($ihis_conn));
    //exit();
} 
else {
   // printf("Current character set: %s\n", mysqli_character_set_name($ihis_conn));
}	
?>