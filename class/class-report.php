<?php

/**
 * report CLASS 
 *
 * @link http://www.mdimran.net/
 * @author Imran <imran.aspire@gmail.com>
 *
 */

class report{
    
    private $wpdb,$current_user;
   
    public function report(){
         global $wpdb,$current_user;
         $this->wpdb=$wpdb;
         
         get_currentuserinfo();
         $this->current_user= $current_user;
         
    }
    
    public function index(){
        
        $currentRole=  $this->current_user->roles[0];
        $user_id=$this->current_user->id;
        if($_GET['action']=="projectdetail"){
            $this->projectdetail();
            return;
        }
        
        if($currentRole!="subscriber"){
            
            $company = new company();
            $companyList = $company->getCompanyList();
        }    
        $companyStr="";
        if($companyList){
            foreach($companyList as $row){
                $companyStr.="<option value='".$row->id."'>".$row->name."</option>";
            }
            
        }
    ?>
    <fieldset>
        <div class="widget" style="margin: 10px 0px !important;">
            <div class="head">
                <a class="clickFormBox" rel="companyWise" href="javascript:void(0);">
                    <h5 class="iChart">Company Wise Report</h5>
                </a>      
            </div>
            <div class="body" id="companyWise">
                <div class="list arrow2Green pt12">
                    <div class="rowElem noborder" id="allProject2">
                                <label class="topLabel"> All Project </label> <input  type="checkbox" name="allProject" id="allProject"  />
                                <div class="fix"></div>
                    </div>
                     <div class="rowElem" id="projectDate">
                                <div class="formBottom">
                                <p>
                                    <label class="topLabel">Project Form</label>
                                    <input type="text" id="projectFrom" style="width: 150px !important;" class="datepicker" /> 
                                    <label class="topLabel">to</label>
                                    <input type="text" id="projectTo" style="width: 150px !important;" class="datepicker" /></p>
                                </div>
                                <div class="fix"></div>
                    </div>
                    <?php  if($currentRole!="subscriber"){ ?>
                        <div class="rowElem" id="CompanyBox">
                            <label class="topLabel">Company</label>
                            <div class="formBottom">
                                <div class="select-wrapper">
                                    <select name="cid" id="companyList" >
                                        <option value="">Select Company</option>
                                        <?php echo $companyStr; ?>
                                    </select>
                                </div>    
                            </div>
                            <div class="fix"></div>
                        </div>
                    <?php }else{
                           $projectBasix = new projectbasix();
                           $cresult = $projectBasix->getCompanyName($user_id);
                    ?>
                        <input type="hidden"  name="cid" id="companyList" value="<?php echo $cresult->id ?>" />
                        <input type="hidden" id="cname" value="<?php echo $cresult->name; ?>" />
                        <input type="button" name="clickreport" id="clickReport" value="Report" />
                    <?php }?>    
                    <div class="rowElem" id="projectListBox">
                        
                    </div>
                    
                </div>
            </div>
        </div>
    </fieldset>    
    <?php    
    }
    
    public function projectdetail(){
       $pid=$_GET['id'];
       
       $tasktable = $this->wpdb->prefix . "pbx_task";
       $usertable = $this->wpdb->prefix . "users";
       
        $sql="SELECT $tasktable.id,title,user_login,enddate,`status` 
            FROM `$tasktable`,$usertable 
            where $usertable.ID=$tasktable.author and $tasktable.pid=$pid  order by $tasktable.id desc";
        
       
        $result=$this->wpdb->get_results($sql);
        
        
        
        $projectDetailStr="";
        if($result){
             $projectDetailStr .="<br /><div class='reportHead'><div class='reportHeadLeft'>";
             $projectDetailStr .='<label class="topLabel">Company Name: '.$_GET['cname'].'</label> <br />';
             $projectDetailStr .='<label class="topLabel">Project Name : '.$_GET['pname'].'</label> <br />';
            
             $projectDetailStr .="</div><div class='reportHeadRight'>
                                <a href='javascript:void(0);' title='Print Report' class='printReport' > <img src='".PBX_WP_PLUGIN_URL."/projectbasix/library/html/images/icons/middlenav/printer.png' /></a>
                                <a 
                                href='".PBX_WP_PLUGIN_URL."/projectbasix/include/report-csv.php?type=projectDetail&pid=$pid' 
                                title='Export Report' class='exportReport'  > <img src='".PBX_WP_PLUGIN_URL."/projectbasix/library/html/images/icons/middlenav/excelDoc.png' /> </a>
                            </div></div>";
             //--------
                
             $projectDetailStr .= ' <div class="widget first">
                            	<div class="head"><h5 class="iFrames">Task List</h5></div>
                                <table width="100%" cellspacing="0" cellpadding="0" class="tableStatic">
                                	<thead>
                                    	<tr>
                                            <td width="5%">ID</td>
                                            <td width="35%">Title</td>
                                            <td width="20%">Assign To</td>
                                            <td width="20%">Assign date</td>
                                            <td width="20%">DeadLine</td>
                                            <td width="10%">Status</td>
                                        </tr>
                                    </thead>
                                    <tbody>';
                            
            foreach($result as $row){
                        
                        $staus="<span style='color:green;'>Complete</span>";
                        if($row->status==1)
                            $staus="<span style='color:red;'>InComplete</span>";
                        
                        $datetime = new DateTime($row->enddate);
                        $enddate = $datetime->format('Y-m-d'); 
                        
                        $datetime = new DateTime($row->create_date);
                        $createdate = $datetime->format('Y-m-d'); 
                        
                        
                        $projectDetailStr.=' 
                                <tr class="gradeA">
                                    <td>'.$row->id.'</td>
                                    <td>'.$row->title.'</td>
                                    <td>'.$row->user_login.'</td>
                                    <td class="center">'.$createdate.'</td>
                                    <td class="center">'.$enddate.'</td>
                                    <td class="center">'.$staus.'</td>
                                </tr>';    
                        
            }
            $projectDetailStr .='        </tbody>          
                            </table>
                        </div>
                     ';
            
        }
        if($projectDetailStr==""){
            $projectDetailStr="<h3>No Reports</h3>"; 
        }
        echo  $projectDetailStr;      
            
    }
    
     public function clinetWiseReport(){
        
          
        /*
        if($_GET['action']=="projectdetail"){
            $this->projectdetail();
            return;
        }
        */
        $reportsResult="";
        if(isset($_POST['clientReportSubmit'])){
            $reportsResult = $this->getClientReport($_POST);
        }
          
        $projectbasix_link=get_permalink(get_option("projectBasix_page"));
        $currentRole=  $this->current_user->roles[0];
        $user_id=$this->current_user->id;
        if(get_option('permalink_structure')=="")
            $pageLink=$projectbasix_link."&page=";
        else 
            $pageLink=$projectbasix_link."?page=";  
            
        
        $client = new client();
        $clintList =  $client->getAllClient();
        $clientStr="";
        if($clintList){
            foreach($clintList as $row){
                $clientStr.="<option value='".$row->ID."'>".$row->user_login."</option>";
            }
            
        }
    ?>
    <fieldset>
        <form method="post" id="clientReportForm" action="<?php echo $pageLink."report&tab=client"; ?>" >
        <div class="widget" style="margin: 10px 0px !important;">
            <div class="head">
                <a class="clickFormBox" rel="companyWise" href="javascript:void(0);">
                    <h5 class="iChart">Client Wise Report</h5>
                </a>      
            </div>
            <?php if($reportsResult){?>
                <div class="body">
                    <?php  echo $reportsResult; ?>
                </div>
            <?php }else{ ?>
                <div class="body" id="companyWise">
                   
                        <div class="rowElem noborder" id="allProject2">
                                    <label class="topLabel"> All Project </label> <input  type="checkbox" name="allProject" id="allProject"  />
                                    <div class="fix"></div>
                        </div>
                         <div class="rowElem" id="projectDate">
                                    <div class="formBottom">
                                    <p>
                                        <label class="topLabel">Project Form</label>
                                        <input type="text" id="projectFrom" name="projectFrom" style="width: 150px !important;" class="datepicker" /> 
                                        <label class="topLabel">to</label>
                                        <input type="text" id="projectTo" name="projectTo" style="width: 150px !important;" class="datepicker" /></p>
                                    </div>
                                    <div class="fix"></div>
                        </div>
                        <?php if($currentRole!="subscriber"){ ?>
                            <div class="rowElem">
                                <label class="topLabel">Clients</label>
                                <div class="formRight">
                                   
                                        <select id="clientList" name="clientList" data-placeholder="Choose Clients..." class="chzn-select" style="width:540px;" tabindex="2">
                                            <option value="">Select Client</option>
                                            <?php echo $clientStr; ?>
                                        </select>
                                   
                                </div>
                                <div class="fix"></div>
                            </div>
                         <?php }else{ ?>
                         <input type="hidden" name="clientList" value="<?php echo $user_id; ?>" />
                         <?php } ?>   
                         <div class="rowElem">
                            <input type="submit" name="clientReportSubmit" value="Genarate Roport" />
                            <div class="fix"></div>
                        </div>
                        <div class="rowElem" id="projectListBox">
                            
                        </div>
                        
                   
                </div>
            <?php } ?>    
        </div>
      </form>  
    </fieldset>    
    <?php    
    }
    
    public function getClientReport($data){
        
        $all=0;
        if(isset($data['allProject']))
            $all=1;
        
        $table_name = $this->wpdb->prefix . "pbx_project";
        $task_table = $this->wpdb->prefix . "pbx_task";
        
        $from =$data['projectFrom'];
        $to=$data['projectTo'];
        $author=$data['clientList'];
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
    
        $result=$this->wpdb->get_results($sql);
        
        $resultStr="";
        if($result){
             $resultStr .="<div class='reportHead'><div class='reportHeadLeft'>";
             
             /*   
             $resultStr .='<label class="topLabel">Company : '.$_POST['cname'].'</label> <br />';
             if($_POST['all']==0){
                 $resultStr .='<label class="topLabel">From : '.$_POST['from'].' </label> <br />';
                 $resultStr .='<label class="topLabel">To : '.$_POST['to'].'</label> <br />';
             }
             */
             $resultStr .="</div><div class='reportHeadRight'>
                                <a href='javascript:void(0);' title='Print Report' class='printReport' > <img src='".PBX_WP_PLUGIN_URL."/projectbasix/library/html/images/icons/middlenav/printer.png' /></a>
                                <a 
                                href='".PBX_WP_PLUGIN_URL."/projectbasix/include/report-csv.php?type=clientWise&all=$all&from=$from&to=$to&uid=$author' 
                                title='Export Report' class='exportReport'  > <img src='".PBX_WP_PLUGIN_URL."/projectbasix/library/html/images/icons/middlenav/excelDoc.png' /> </a>
                            </div></div>";
                
             $resultStr .= ' <div class="widget first">
                            	<div class="head"><h5 class="iFrames">Task List</h5></div>
                                <table width="100%" cellspacing="0" cellpadding="0" class="tableStatic">
                                	<thead>
                                    	<tr>
                                            <td width="5%">ID</td>
                                            <td width="20%">Project</td>
                                            <td width="30%">Task</td>
                                            
                                            <td width="18%">Create Date</td>
                                            <td width="18%">Deadline</td>
                                            <td width="18%">Status</td>
                                            
                                            
                                        </tr>
                                    </thead>
                                    <tbody>';
            
            $count=1;                
            foreach($result as $row){
                            
                            $staus="<span style='color:green;'>Complete</span>";
                            if($row->status==1)
                                $staus="<span style='color:red;'>InComplete</span>";
                            
                            $datetime = new DateTime($row->enddate);
                            $enddate = $datetime->format('Y-m-d'); 
                            
                            $datetime = new DateTime($row->create_date);
                            $createdate = $datetime->format('Y-m-d'); 
                        
                            
                            $resultStr  .="<tr>
                                                <td>$count</td>
                                                <td>$row->name</td>
                                                 <td>$row->title</td>
                                                <td>$createdate</td>
                                                <td>$enddate</td>
                                                <td>$staus</td>

                                            </tr>";
                                       $count++;     
            }
            $resultStr .='        </tbody>          
                            </table>
                        </div>
                     ';
            
        }
        if($resultStr==""){
            $resultStr="<h3>No Reports</h3>"; 
        }
        return  $resultStr;         
        
        
        
    }
    public function getCompanyWiseProjectList($cid,$all,$from,$to){
        
        
    }
}  
?>