<?php
include('../../../../wp-load.php');
$filename = date("m-d-Y-H-i");
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"$filename.csv\"");

    global $wpdb;
    $table_name = $wpdb->prefix ."pbx_client";

    $sql="SELECT * from ".$table_name; 
                
                
    $result=$wpdb->get_results($sql);
    
    
    
    
    $data="First Name,Last Name,Web Page ,E-mail Address,Mobile Phone,Business Phone,Company \n\n";   
    foreach($result as $row){
        
        $data .=$row->first_name.",".
        $row->last_name.",".
        $row->user_url.",".
        $row->user_email.",".
        $row->cell.",".
        $row->office_cell.",".
        
        $row->company."\n";
    }
    echo $data; 
?>