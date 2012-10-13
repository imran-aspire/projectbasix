<?php
    include "../../../../wp-load.php";
    global $wpdb;
    
    global $current_user;
    get_currentuserinfo();
    
    $type=$_POST['type'];
    

    $projectbasix_link=get_permalink(get_option("projectBasix_page"));
    if(get_option('permalink_structure')=="")
        $pageLink=$projectbasix_link."&page=";
    else 
        $pageLink=$projectbasix_link."?page=";  
    
    $actionUrl=$pageLink . "report" ;
    
    
    // get all tast for a specific project 
    if($type=="getProjectList"){
        
        $project = new project();
        $projectList = $project->getCompanyProjectList($_POST['cid'],$_POST['all'],$_POST['from'],$_POST['to']);
        
        $projectStr="";
        if($projectList){
             $projectStr .="<div class='reportHead'><div class='reportHeadLeft'>";
             $projectStr .='<label class="topLabel">Company : '.$_POST['cname'].'</label> <br />';
             if($_POST['all']==0){
                 $projectStr .='<label class="topLabel">From : '.$_POST['from'].' </label> <br />';
                 $projectStr .='<label class="topLabel">To : '.$_POST['to'].'</label> <br />';
             }
             $projectStr .="</div><div class='reportHeadRight'>
                                <a href='javascript:void(0);' title='Print Report' class='printReport' > <img src='".PBX_WP_PLUGIN_URL."/projectbasix/library/html/images/icons/middlenav/printer.png' /></a>
                                <a 
                                href='".PBX_WP_PLUGIN_URL."/projectbasix/include/report-csv.php?type=companywise&all=".$_POST['all']."&from=".$_POST['from']."&to=".$_POST['to']."&cid=".$_POST['cid']."' 
                                title='Export Report' class='exportReport'  > <img src='".PBX_WP_PLUGIN_URL."/projectbasix/library/html/images/icons/middlenav/excelDoc.png' /> </a>
                            </div></div>";
                
             $projectStr .= ' <div class="widget first">
                            	<div class="head"><h5 class="iFrames">Project List</h5></div>
                                <table width="100%" cellspacing="0" cellpadding="0" class="tableStatic">
                                	<thead>
                                    	<tr>
                                            <td width="5%">ID</td>
                                            <td width="30%">Project</td>
                                            <td width="8%">Create Date</td>
                                            <td width="8%">Deadline</td>
                                            <td width="10%">Complete</td>
                                            <td width="10%">Total Task</td>
                                            <td width="15%">Complete Task</td>
                                            <td width="15%">InComplete Task</td>
                                        </tr>
                                    </thead>
                                    <tbody>';
                            
            foreach($projectList as $row){
                            $taskDetail = $project->getProjectCompletationReport($row->id);
                              
                              $datetime = new DateTime($row->create_date);
                              $row->create_date = $datetime->format('Y/m/d'); 
                              
                              $datetime = new DateTime($row->deadline);
                              $row->deadline = $datetime->format('Y/m/d'); 
        
                            $projectStr  .="<tr>
                                                <td>$row->id</td>
                                                <td><a href='$actionUrl&action=projectdetail&id=$row->id&cname=".$_POST['cname']."&pname=$row->name' >$row->name</a></td>
                                                <td>$row->create_date</td>
                                                <td>$row->deadline</td>
                                                <td>".$taskDetail['complete_percent']."%</td>
                                                <td>".$taskDetail['task']."</td>
                                                <td>".$taskDetail['complete']."</td>
                                                <td>".$taskDetail['incomplete']."</td>

                                            </tr>";
            }
            $projectStr .='        </tbody>          
                            </table>
                        </div>
                     ';
            
        }
        if($projectStr==""){
            $projectStr="<h3>No Reports</h3>"; 
        }
        echo  $projectStr;                 
    }
    
    
?>