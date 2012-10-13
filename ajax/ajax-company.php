<?php
    include "../../../../wp-load.php";
    global $wpdb;
    
    global $current_user;
    get_currentuserinfo();
    
    $clinet= new client();
    $type=$_POST['type'];
    
    if($type=="deleteClient"){
        echo $clinet->deleteClient($_POST['uid']);
    }
    
    
    
?>