<?php
$i_pharmacyQ = "SELECT `ec_hsp_id` FROM `pharmacy_profile` WHERE id='".$_SESSION['auth']['pharmacy_id']."' AND ec_hsp_id != 0 AND ec_hsp_id IS NOT NULL";
$i_pharmacyR = mysqli_query($conn, $i_pharmacyQ);
if($i_pharmacyR && mysqli_num_rows($i_pharmacyR) > 0){
    $data_ihis = mysqli_fetch_array($i_pharmacyR);
    define("ec_hsp_id",$data_ihis['ec_hsp_id']);
}else{
    define("ec_hsp_id",NULL);
}

$eclinic_conn = mysqli_connect('localhost', 'yamunxym_eclini', 'eclinic123@', 'yamunxym_eclinic');

if ($eclinic_conn->connect_error) {
    die("Connection failed: " . $eclinic_conn->connect_error);
}  

mysqli_character_set_name($eclinic_conn);
/* change character set to utf8 */
if (!mysqli_set_charset($eclinic_conn, "utf8"))
{
    printf("Error loading character set utf8: %s\n", mysqli_error($eclinic_conn));
    //exit();
} 
else {
   // printf("Current character set: %s\n", mysqli_character_set_name($eclinic_conn));
}	
?>