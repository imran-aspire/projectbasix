<?php
    include "../../../../wp-load.php";
    global $wpdb;
    $project =new project();
    
    $type=$_POST['ajaxtype'];
    $start=$_POST['start'];
    if($type=="getProjectList"){
        echo $project->showAllProjects($start);
    }
    
    
?>