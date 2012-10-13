<?php
include('../../../../wp-load.php');
$filename = date("m-d-Y-H-i");
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"$filename.csv\"");

global $wpdb;    
    if($_GET['type']=="companywise"){
        $project = new project();
        $projectList = $project->getCompanyProjectList($_GET['cid'],$_GET['all'],$_GET['from'],$_GET['to']);   
        
        $data ="ID,Project,Create Date,Deadline,Complete,Total Task,Complete Task,InComplete Task \n\n"; 
        foreach($projectList as $row){
            $datetime = new DateTime($row->create_date);
            $row->create_date = $datetime->format('d-m-Y'); 
            
            $datetime = new DateTime($row->deadline);
            $row->deadline = $datetime->format('d-m-Y'); 
            
            $data  .="$row->id,$row->name,$row->create_date,$row->deadline,".$taskDetail['complete_percent']."%,".$taskDetail['task'].",".$taskDetail['complete'].",".$taskDetail['incomplete']."\n";
        }
            
    }
    if($_GET['type']=="clientWise"){
        
        $all=0;
        if(isset($_GET['all']))
            $all=1;
        
        $table_name = $wpdb->prefix . "pbx_project";
        $task_table = $wpdb->prefix . "pbx_task";
        
        $from =$_GET['from'];
        $to=$_GET['to'];
        $author=$_GET['uid'];
        $qStr="";
        if($all==0)
        $qStr =" and DATE_FORMAT($task_table,'%Y-%m-%d') BETWEEN '$from' and '$to' ";
        
        $sql="SELECT $table_name.name,
                $task_table.title,
                $task_table.create_date,
                $task_table.enddate,
                $task_table.`status`
        
        from $table_name,$task_table 
        where 
        $table_name.id=$task_table.pid
        and $task_table.author=$author	$qStr";
    
        $result=$wpdb->get_results($sql);
        
        
        $data ="ID,Project,Task , Create Date,Deadline,Status \n\n"; 
        $count=1;
        foreach($result as $row){
            $datetime = new DateTime($row->create_date);
            $row->create_date = $datetime->format('d-m-Y'); 
            
            $datetime = new DateTime($row->deadline);
            $row->deadline = $datetime->format('d-m-Y'); 
            $staus="Complete";
                if($row->status==1)
                    $staus="InComplete";
                    
            
            $data  .="$count,$row->name,$row->title,$row->create_date,$row->deadline,$staus\n";
            $count++;
        }
            
    }
    if($_GET['type']=="projectDetail"){
        $tasktable = $wpdb->prefix . "pbx_task";
        $usertable = $wpdb->prefix . "users";
        
        $pid=$_GET['pid'];
        
        $sql="SELECT $tasktable.id,title,user_login,enddate,`status` 
        FROM `$tasktable`,$usertable 
        where $usertable.ID=$tasktable.author and $tasktable.pid=$pid  order by $tasktable.id desc";
        
        $result=$wpdb->get_results($sql);
        if($result){
             $data ="ID,Title,Aggign To,Assign Date ,Deadline,Status \n\n"; 
             
             foreach($result as $row){
                
                $staus="Complete";
                if($row->status==1)
                    $staus="InComplete";
                            
                
                $datetime = new DateTime($row->create_date);
                $row->create_date = $datetime->format('d-m-Y'); 
                
                $datetime = new DateTime($row->deadline);
                $row->deadline = $datetime->format('d-m-Y'); 
                
                $data  .="$row->id,$row->title,$row->user_login,$row->create_date,$row->deadline,$staus \n";
                
             }
        } 
    }
    
   
    echo $data; 
?>