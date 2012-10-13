<?php
    include "../../../../wp-load.php";
    global $wpdb;
    $project =new project();
    $task = new task();
    
    $page=$_POST['page'];
    
    if($page=="project"){
        echo $project->projectView();
    }
    if($page=="dashboard"){
        echo ' <div class="title"><h5>Dashboard</h5></div>';
    }
    if($page=="task"){
        echo $task->showTaskTab();
        //echo ' <div class="title"><h5>Task</h5></div>';
    }
    if($page=="reports"){
        echo ' <div class="title"><h5>Reports</h5></div>';
    }
?>