<?php
    include "../../../../wp-load.php";
    global $wpdb;
    $project =new project();
    $activity= new activity();
    $task =new task();
    $file = new file();
    
    
    $data= $_POST;
    //print_r($data);
    $type=$_POST['ajaxtype'];
    
    // add new project 
    
    if($type=="addProject"){
        echo $project->addProject($data);
    }
    // get the project summery 
    if($type=="getSummeryList"){
        echo $activity->getProjectSummary($_POST['pid']);
    }
    // get the project detail
    if($type=="getProjectDetail"){
        $pid=$_POST['pid']; 
        echo $project->showProjectDetail($pid);   
    }
    
    // get the project tab content 
    
    if($type=="getProjectTabContent"){
        $tabType=$_POST['tabtype'];
        $pid=$_POST['pid'];
        //echo $tabType.">>>";
       
        if($tabType=="user"){
            echo $project->showProjectUsers($pid);                
        }
        
        // 
        if($tabType=="settings"){
            echo $project->showProjectSettings($pid);            
        }
        
        if($tabType=="tasks"){
            echo $task->taskList($pid);  
        }
        if($tabType=="files"){
            echo $file->getProjectFileList($pid);
        }
    }
    
    // edit user detail 
    
    if($type=="editProjectUserList"){
        echo $project->updateProjectUser($_POST) ;
    }
    
    // update project settings 
    if($type=="editProjectSettings"){
        echo $project->updateProjectSettings($_POST);        
    }
?>