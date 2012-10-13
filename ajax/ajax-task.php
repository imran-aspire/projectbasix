<?php
    include "../../../../wp-load.php";
    global $wpdb;
    
    global $current_user;
    get_currentuserinfo();
    
    
    $task= new task();
    $file = new file();
    $notification = new notification();
    $type=$_POST['type'];
    
    if($type=="addTask"){
        echo $task->addTask($_POST);      
    }
    if($type=="addTaskForm"){
        echo $task->taskAddForm($pid);      
    }
    if($type=="getTaskDetail"){
      //  if($current_user->roles[0]=="administrator")
            echo $task->editTask($_POST['tid']);
      //  else
       //     echo $task->showUserTask($_POST['tid']);    
    }
    
    if($type=="editTask"){
            echo $task->updateTask($_POST);
        
    }
    
    if($type=="deleteFile"){
        echo $file->deleteFile($_POST['pid'],$_POST['filename']);    
    }
    if($type=="sendRemind"){
        echo $notification->sendRemindNotification($_POST['tid']);   
    }
    
    // get all tast for a specific project 
    if($type=="getProjectUserList"){
        
        $client = new client();
        
        $userList= $client->getAllProjectClient($_POST['pid']);
        //$userList=$user->getProjectUser($_POST['pid']);
        
        
        
        session_start();
        $_SESSION['cpid']=$_POST['pid'];
        $userStr="";
        if($userList){
             $userStr .= '<label>Select User   </label>
                     <div class="formBottom">
                       <div class="select-wrapper">
                            <select id="select" name="author" class="requiredField">
                              <option value="">Assign Task To</option>';
                            
            foreach($userList as $row){
                            $userStr  .="<option value='".$row->ID."'>".$row->user_login."</option>";
            }
            $userStr .='    </select>
                        </div>
                     </div>';
            
        }
        echo  $userStr;                 
    }
    
    //-----------------------------
    // add project task in task tab  
    //-----------------------------
    
    if($type=="addProjectTask"){
        echo $task->addTask($_POST);               
    }
    
    
    
    // downlaod file 
    
    if($type=="downloadFile"){
        
        // It will be called downloaded.pdf
        header('Content-Disposition: attachment; filename="downloaded.pdf"');
        readfile($_POST['file']);
        exit();
    }
    
?>