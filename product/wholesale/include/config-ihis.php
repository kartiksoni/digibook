<?php

define("ihis_firm_id",5);
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