<?php

/**
 * Project CLASS 
 *
 * @link http://www.mdimran.net/
 * @author Imran <imran.aspire@gmail.com>
 *
 */

class project{
    
    private $wpdb;
    private $mail;
    private $activaty;
    private $current_user;
    private $pageLink;
    private $cpageLink;

    
    public function project(){
         global $wpdb,$current_user;
        
         $this->wpdb=$wpdb;
         $mail=new mail();
         $activaty= new activity();
         
         $this->mail=$mail;
         $this->activaty=$activaty;
         
         get_currentuserinfo();
         $this->current_user= $current_user;
         
         
        $projectbasix_link=get_permalink(get_option("projectBasix_page"));
        if(get_option('permalink_structure')==""){
            $pageLink=$projectbasix_link."&page=project";
            $cpageLink=$projectbasix_link."&page=company";
            
        }
        else{ 
            $pageLink=$projectbasix_link."?page=project";  
            $cpageLink=$projectbasix_link."?page=company";

        }
        $this->pageLink= $pageLink;
        $this->cpageLink=$cpageLink;
            
         
         
    }
    
    /**
    * Create project table
    *
    * 
    * @return bool 'true' | false(if failed)
    */
    public function createTable(){
        
        
        
        $table_name = $this->wpdb->prefix . "pbx_project";
        // status :   active 1 : archive : 0
       
        if($this->wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $sql = "CREATE TABLE " . $table_name . " (
            	id          bigint(11) NOT NULL AUTO_INCREMENT,
            	name       varchar(555) NOT NULL,
            	description       TEXT,
                author       INT NOT NULL,
                create_date    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
                update_date    TIMESTAMP ,
                deadline    TIMESTAMP,
                status       tinyint DEFAULT '1',
                PRIMARY KEY   (id)
                
            );";
            $results = $this->wpdb->query( $sql );
        }
        
        $table_name = $this->wpdb->prefix . "pbx_project_user";
        
        if($this->wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $sql = "CREATE TABLE " . $table_name . " (
            	id          bigint(11) NOT NULL AUTO_INCREMENT,
            	pid          bigint(11) ,
            	author          bigint(11) ,
            	PRIMARY KEY   (id)
                
            );";
            $results = $this->wpdb->query( $sql );
        }
    
    }
    
    /**
     * project ADD
     * */
    public function addProject($data){
        
        global $current_user;
        $table_name = $this->wpdb->prefix . "pbx_project";
        //print_r($data);
        // insert project 
        $this->wpdb->insert( $table_name, 
        	array( 
        		'name' => $data['pname'], 
        		'description' => $data['description'] ,
        		'author' => $data['pauthor'] ,
                'deadline' => $data['deadline'],
                'cid' => $data['cid']
        	), 
        	array( 
        		'%s','%s','%d','%s' ,'%d'
        	) 
        );
        //echo $this->wpdb->last_query;die;
        // insert project id 
        $pid=$this->wpdb->insert_id;
        wp_mkdir_p( ABSPATH . "wp-content/uploads/projectbasix/$pid/" );
        
        if($pid){
            
            // send project owner to initial email 
            //$this->mail->newProjectEmail($data['author'],"owner");
            
            // create activaty 
            $status="Project :".$data['name']." created by $current_user->display_name";
            $this->activaty->createActivaty($pid,$status);
            
            
            // add project initial notification  to all clients
            
            $notification = new notification();
            $notification->newProjectNotification($data['name'],$data['description'],$data['deadline'],$data['cid']);
            
             
            
            
            /*
            if($data['userlist']){
                $insert_str="";
                foreach($data['userlist'] as $row){
                    $insert_str .="($pid,$row),";
                    $this->mail->newProjectEmail($row,"user");
                }
                $insert_str = rtrim($insert_str,",");
                
                $table_name = $this->wpdb->prefix . "pbx_project_user";
                $sql="insert into $table_name (pid,author) values $insert_str";
                
                $result = $this->wpdb->query($sql);
                
                if($result)
                    return true;
                else
                    return false;    
            }
            */
             return true;
        }
        else
            return false;
        
        
    }  
    
    
    /**
     * project view 
     * */
     
    function projectView(){
       
        if($_GET['action']=="addProject"){
            $result= $this->addProject($_POST);
            if($result){
                echo '<div class="nNote nSuccess hideit" style="display: block;" >
                        <p><strong>SUCCESS: </strong>Project Added Successully</p>
                      </div>';
            }
            else{
                echo '<div class="nNote nFailure hideit" style="display: block;" >
                         <p><strong>FAILURE: </strong>Oops sorry. Some problem happen .Try again later</p>
                      </div>';
                
            }
        }    
        
        $projectContent='<div class="title"><h5>Projects</h5></div>';
        
        $temp=0;
        if($this->current_user->roles[0]!="subscriber"){
            $projectContent .= self::projectAddForm();
            $projectContent .= self::showAllProjects(0);
            
        }else{
            $projectContent .="<br />";
            $projectContent .= self::showAllProjects(0);
        }    
            
        
        
        return $projectContent;
    } 
    
    /**
     * project ADD Form
     * */
    function projectAddForm(){
                          
        $userList=get_users_of_blog();
        $userStr="";
        
        // get the wp user list
         
        if($userList){
            foreach($userList as $user){
                 $userStr .= '<option value="'.$user->user_id.'">'.$user->display_name.'</option>';
            }
        } 
        
        $company = new company();
        $companyList = $company->getCompanyList();
        $companyStr="";
        if($companyList){
            foreach($companyList as $row){
                $companyStr.="<option value='".$row->id."'>".$row->name."</option>";
            }
            
        }
         $formAddStr=
            '<!-- Another version of text inputs -->
            <fieldset>
                <div class="widget" style="margin:10px 0 10px 0;">
                    <div class="head">
                        <a class="clickFormBox" rel="pbxAddFormBox" href="javascript:void(0);">
                            <h5 class="iList"> ADD Project</h5>
                        </a>
                    </div>
                    <form method="post" id="addProjectForm" action="'.$this->pageLink.'&action=addProject" >
                    <div id="pbxAddFormBox">
                        
                            <div class="rowElem noborder">
                                <label class="topLabel">Project Name:</label>
                                <div class="formBottom">
                                    <input type="text" name="pname" id="pname" />
                                </div>
                                <div class="fix"></div>
                            </div>
                            <div class="rowElem">
                                <label class="topLabel">Description</label>
                                <div class="formBottom">
                                    <textarea rows="8" cols="" class="auto" id="description"  name="description"></textarea>
                                </div>
                                <div class="fix"></div>
                            </div>
                            <div class="rowElem">
                                <label class="topLabel">Company</label>
                                <div class="formBottom">
                                    <select name="cid" id="companyList" style="width:300px">
                                        <option value="">Select Company</option>
                                        '.$companyStr.'
                                    </select>
                                </div>
                                <div class="fix"></div>
                            </div>
                            <div class="rowElem">
                                <label>Deadline</label>
                                <div class="formRight">
                                    <input type="text" class="datepicker" name="deadline" id="datepicker" />
                                </div>
                                <div class="fix"></div>
                            </div>
                            <input type="hidden" name="pauthor" value="'.$this->current_user->ID.'" />
                            <input type="hidden" name="ajaxtype" value="addProject" />
                            <input type="submit" value="ADD Project" class="greyishBtn submitForm" />
                            <div class="fix"></div>
                        
                     </div>
                     </form>      
                </div>
                
            </fieldset>
            <script type="text/javascript">
                jQuery(function(){
                 jQuery("#simpleCountries").multiSelect({});
                });
            </script>
            ';
         
         
         return $formAddStr;
         
         /*
        <div class="rowElem companyListBox">
            <label>ADD User to this project:</label>
            <div class="fix"></div>
            <div class="formRight" style="float:left">
                <select name="userlist[]" multiple="multiple" id="simpleCountries" title="Click to Select Users">
                '.$userStr.'
                </select>
            </div>
            <div class="fix"></div>
        </div>
         */
         
    }
    
    /**
     *  show all project list 
     * */
    
    public function showAllProjects($start){
        
        $current_role=$this->current_user->roles[0];
        $current_id=$this->current_user->id;
        
        $company_table = $this->wpdb->prefix . "pbx_company"; 
        $company_client_table = $this->wpdb->prefix . "pbx_company_clients"; 
        $table_name = $this->wpdb->prefix . "pbx_project";
        //$user_table_name = $this->wpdb->prefix . "pbx_project_user";
         
        $sql="select $table_name.id,$table_name.name as pname,$table_name.status,$table_name.author,$table_name.create_date,$company_table.name,$company_table.id as cid from $table_name,$company_table  where $table_name.status=1 and $company_table.id=$table_name.cid order by $table_name.id desc";
        if($current_role=="editor")
            $sql="select $table_name.id,$table_name.name  as pname,$table_name.status,$table_name.author,$table_name.create_date,$company_table.name ,$company_table.id as cid from $table_name,$company_table  where $table_name.status=1 and $company_table.id=$table_name.cid and $table_name.author=$current_id order by $table_name.id desc";
       
       if($current_role=="subscriber")
            $sql="select 
                        $table_name.id,$table_name.name  as pname,$table_name.status,$table_name.author,$table_name.create_date,$company_table.name 
                        from $table_name,$company_table,$company_client_table  
                        where $table_name.status=1 
                        and $company_table.id=$table_name.cid 
                        and $company_client_table.cid=$table_name.cid 
                        and $company_client_table.uid=$current_id  
                        order by $table_name.id desc";
       
       //echo $sql;
       //     $sql="select $table_name.id,$table_name.name,$table_name.status,$table_name.author,$table_name.create_date from $table_name,$user_table_name where status=1  and pid=$table_name.id and $user_table_name.author=$current_id order by $table_name.id desc";
        
        //echo $sql;
        //<!-- ajax pagination test !-->
       
       
        $obj = new pagination_class($sql,$start,20,"projectListDetail");		
        $result = $obj->result;
        
        //$result=$this->wpdb->get_results($sql);
        $totalProject=count($result);
        
        
        $paginationStr="";
        if($totalProject>20)
        $paginationStr=$obj->anchors;
        // add the all project heading 
        $allProjectString ='<div id="projectListDetail">'.$paginationStr.'<div class="widget" style="margin:10px 0 10px 0;">
                                <div class="head">
                                    <a class="clickFormBox" rel="pbxAllProject" href="javascript:void(0);">
                                        <h5 class="iMoney">Active Projects</h5>
                                    </a>    
                                    <div class="num"><a href="#" class="blueNum">+'.$obj->totalRow.'</a></div>
                                </div>
                                <div id="pbxAllProject">';
      
        
        $i=0;
       
        // add all project ownerwise
        if($result){
            foreach($result as $row){
                $bdr="";
                if($i==0)
                    $bdr="nobg";
                
                $datetime = new DateTime($row->create_date);
                $create_date = $datetime->format('Y-m-d'); 
               
                $userData=get_userdata($row->author); 
                $active="";
                if($row->status==1)
                $active=" Active";
                
                $archive="";
                if($this->current_user->roles[0]!="subscriber")
                $archive ='<li class="even">&nbsp; <a href="#">archive</a> </li>';
                
                $allProjectString .=
                '<div class="supTicket '.$bdr.'">
                	<div class="issueType">
                    	<span class="issueInfo"><a  class="projectDetail" rel="'.$row->id.'" href="javascript:void(0);" >'.$row->pname.'</a></span>
                        <span class="issueNum"><a href="#" title="">[ '.getTwoDaysDiff($create_date,date("Y-m-d",time())).']</a></span>
                        <div class="fix"></div>
                    </div>
                    
                    <div class="issueSummary">
                   		<a href="javascript:void(0);" title=""  class="floatleft basicBtn topDir mr40 ml40" value="top direction" original-title="'.$userData->user_login.'" >'.get_avatar( $row->author, 30 ).'</a>	
                        <div class="ticketInfo" style="float: left;">
                        	<ul>
                            	<li><strong class="green">'.$this->getProjectCompletation($row->id).'%</strong> Complete</li>
                                <li class="even"><a class="projectSummeryClick" pid="'.$row->id.'" rel="projectSummery'.$row->id.'" href="javascript:void(0);">History</a></li>
                               
                                <li>Company:</li>
                                <li class="even"><a href="'.$this->cpageLink.'&action=editCompany&id='.$row->cid.'" title="">'.$row->name.'</a></li>
                                <li> <a class="projectDetail" rel="'.$row->id.'" href="javascript:void(0);">Details</a> </li>
                                '.$archive.'
                              
                            </ul>
                            <div class="fix"></div>
                        </div>
                        <div class="projectSummery" id="projectSummery'.$row->id.'" style="">
                            <div class="widget">
                               
                            </div>
                        </div>
                       
                
                        <div class="fix"></div>
                    </div> 
                </div> ';
                
                $i++;    
            }
        }
       
        $allProjectString .="</div></div>$paginationStr</div> <br/>";
        
        return $allProjectString;    
      
    }
    
    
      
    
    public function showProjectSettings($pid){
        $table_name = $this->wpdb->prefix . "pbx_project";
         
        $sql="select *from $table_name where id=$pid";
        
        $result=$this->wpdb->get_row($sql);
        
         $datetime = new DateTime($result->deadline);
         $deadline = $datetime->format('Y-m-d'); 
               
        
        
        $contentStr='
        <form method="action" action="post" id="ediitProjectSettings">
            <div class="rowElem noborder">
                <label class="topLabel">Project Name:</label>
                <div class="formBottom">
                    <input type="text" name="name" value="'.$result->name.'" id="name" />
                </div>
                <div class="fix"></div>
            </div>
            <div class="rowElem">
                <label class="topLabel">Description</label>
                <div class="formBottom">
                    <textarea rows="8" cols="" class="auto" id="description"  name="description">'.$result->description.' </textarea>
                </div>
                <div class="fix"></div>
            </div>
            
            <div class="rowElem">
                <label>Deadline</label>
                <div class="formRight">
                    <input type="text" class="datepicker" name="deadline" value="'.$deadline.'"  />
                </div>
                <div class="rowElem" style="margin-left: -30px;">
                           <input class="greyishBtn submitForm" type="submit" name="submitUser" value="Update" />
                </div>
                <div class="fix"></div>
                 <input type="hidden" name="ajaxtype" value="editProjectSettings" />
                 <input type="hidden" name="pid" value="'.$pid.'" />
            </div>
        </form>';
        
        return $contentStr;
    }
    /**
     * show project detail 
     * */
    public function showProjectDetail($pid){
        
        $table_name = $this->wpdb->prefix . "pbx_project";
        $company_table = $this->wpdb->prefix . "pbx_company";
        $sql="select $table_name.create_date,$table_name.deadline,$table_name.name,$company_table.name as cname from $table_name,$company_table where $company_table.id =$table_name.cid and $table_name.status=1 and $table_name. id =$pid";
        $result=$this->wpdb->get_row($sql);
        //echo $sql;
        //print_r($result);die;
        $task = new task();
        $datetime = new DateTime($result->create_date);
        $create_date = $datetime->format('Y/m/d'); 
        
        $datetime = new DateTime($result->deadline);
        $deadline = $datetime->format('Y/m/d'); 
        
        $completeTask=$this->getProjectCompletation($pid);
        $settingStr="";
        if($this->current_user->roles[0]!="subscriber")
            $settingStr='<li><a class="projectTabClick" rel="settings"  href="#tab4">Settings</a></li>';
       
        // <li><a class="projectTabClick" rel="user"  href="#tab3">User</a></li>
        
        $script=' <script type="text/javascript" >
                    jQuery(function() {
                        	jQuery( "#progressbar" ).progressbar({value: '.$completeTask.'});
                            jQuery("div[class^=\'widget\']").simpleTabs();
                            jQuery("#userSelectList").multiSelect({}); 
                            jQuery(".topDir").tipsy({fade: true, gravity: "s"}); 
                    });
                  </script>';  
        
        $projectDetailStr="";
        
        $projectDetailStr .='
        <div class="title">
            <h5 style="float:none">'.$result->name.'<small style="float:right;color:#79B4E0">'.$result->cname.'</small> </h5>
            <h5 class="complete">'.$completeTask.'% complete</h5> 
            <div class="formRight">
                <div id="progressbar"></div>
            </div>
            <div class="fix"></div>
            <h5 class="complete pstart">Project Start : '.$create_date.'</h5>
            <h5 class="complete deadline">Dead Line: '.$deadline.'</h5>
            </p>
        </div>
        <!-- Tabs -->
        <div class="widget">       
        <ul class="tabs">
           
            <li><a class="projectTabClick" rel="tasks"  href="#tab1" >Tasks</a></li>
            <li><a class="projectTabClick" rel="files"  href="#tab2">Files</a></li>
           
            '.$settingStr.'
        </ul>
        
        <div class="tab_container">
         
            <div id="tab1" class="tab_content">'.$task->taskList($pid).'</div>
            <div id="tab2" class="tab_content">Widget2</div>
            <div id="tab3" class="tab_content">Widget2</div>
            <div id="tab4" class="tab_content">
            </div>
        </div>	
        <div class="fix"></div>	 
        <input type="hidden" name="pid" id="pid" value="'.$pid.'" />
        </div>';

      return $projectDetailStr.$script;       
    }
    
    public function showProjectUsers($pid){
        
         //echo "LL";die;
        // get project user list with avater
                                
        $table_name = $this->wpdb->prefix . "pbx_project_user";
        $sql="select *from $table_name where pid =$pid";
        $result2=$this->wpdb->get_results($sql);
       
        $userArray=array();
        
        $userlistStr="";
        if($result2){
            $userlistStr .='<div class="avaterBox">';
            foreach($result2 as $row){ 
                $user_info = get_userdata($row->author);
                $userlistStr .= ' <a  class="basicBtn topDir mr40 ml40" value="top direction" original-title="'.$user_info->user_login.'" href="javascript:void(0);">';
                $userlistStr .= get_avatar( $row->author, 32 ); 
                $userlistStr .= '</a>';
                $userArray[]=$row->author;
            }
            $userlistStr .= "</div>";
        }
        
                   
        // get the wp user list for multiselect 
        
        $userList=get_users_of_blog();
        $userStr="";
        
        if($userList){
            foreach($userList as $user){
                 $selected=""; 
                 if(in_array($user->user_id, $userArray))
                    $selected ='selected=""';
                 $userStr .= '<option '.$selected.' value="'.$user->user_id.'">'.$user->display_name.'</option>';
            }
        }  

        $script=' <script type="text/javascript" >
            jQuery(function() {
                	jQuery( "#progressbar" ).progressbar({value: 70});
                    jQuery("div[class^=\'widget\']").simpleTabs();
                    jQuery("#userSelectList").multiSelect({}); 
                    jQuery(".topDir").tipsy({fade: true, gravity: "s"}); 
            });
          </script>';  
        
        
        $usercontentStr=$script.$userlistStr;
        
        if($this->current_user->roles[0]!="administrator")
            return $usercontentStr;
        
        $usercontentStr.='           
                 <div class="rowElem">
                    <label style="padding-right: 45px;">ADD User to this project:</label> <label style="padding-left: 30px;">Existing User in this project</label>
                    <div class="fix"></div>
                    <form method="post" id="editProjectUserForm" action="">
                        <div class="formRight" style="float:left">
                            <select name="userlist[]" multiple="multiple" id="userSelectList" title="Click to Select Users">
                            '.$userStr.'
                            </select>
                        </div>
                        <div class="rowElem" style="margin-left: -30px;">
                           <input class="greyishBtn submitForm" type="submit" name="submitUser" value="update user" />
                        </div>
                        <input type="hidden" name="ajaxtype" value="editProjectUserList" />
                        <input type="hidden" name="pid" value="'.$pid.'" />
                    </form>    
                    <div class="fix"></div>
                </div>'; 
                
         return $usercontentStr;          
    }
    
    public function updateProjectUser($data){
            
            $table_name = $this->wpdb->prefix . "pbx_project_user";
            $pid=$data['pid'];
            // create activaty 
            
          
            /*
            $status="Project  :".$data['name']." created by $current_user->display_name";
            $this->activaty->createActivaty($pid,$status);
            */
            if($data['userlist']){
                
                $sql="delete from $table_name where pid =$pid";
                $result = $this->wpdb->query($sql);
                
                $insert_str="";
                foreach($data['userlist'] as $row){
                    $insert_str .="($pid,$row),";
                    $this->mail->newProjectEmail($row,"user");
                }
                $insert_str = rtrim($insert_str,",");
                
             
                $sql="insert into $table_name (pid,author) values $insert_str";
                
                $result = $this->wpdb->query($sql);
                
                if($result)
                    return true;
                else
                    return false;    
            }
        
        
    }
    
    /** 
    * updateProjectSettings
    * */
    
    function updateProjectSettings($data){
        $table_name = $this->wpdb->prefix . "pbx_project";
        
      
        $result = $this->wpdb->update( 
        	$table_name, 
        	array( 
        	    'name' => $data['name'], 
        		'description' => $data['description'] ,
                'deadline' => $data['deadline']	
        	), 
        	array( 'id' => $data['pid'] ), 
        	array( 
        		'%s',	// value1
        		'%s',
                '%s'
            ), 
        	array( '%d' ) 
        );
        
        if($result)
            return true;
        else
            return false;   
        
    }
    
    function getProjectCompletation($pid){
       $table_name = $this->wpdb->prefix . "pbx_task";
       $sql="select count(*) as total_task from $table_name where pid=$pid";
       $result=$this->wpdb->get_row($sql);
       
       $totalTask=$result->total_task;
       
       $sql="select count(*) as complete_task from $table_name where pid=$pid and status=2";
       $result=$this->wpdb->get_row($sql);
       $completeTask=$result->complete_task;
       
       if($completeTask>0)
        return  round(($completeTask*100)/$totalTask,0);
       else
        return 0;  
    }
    function getProjectCompletationReport($pid){
        
       $table_name = $this->wpdb->prefix . "pbx_task";
       $sql="select count(*) as total_task from $table_name where pid=$pid";
       $result=$this->wpdb->get_row($sql);
       
       $totalTask=$result->total_task;
       
       $sql="select count(*) as complete_task from $table_name where pid=$pid and status=2";
       $result=$this->wpdb->get_row($sql);
       $completeTask=$result->complete_task;
       
       if($completeTask>0){
            $cresult = array();
            $cresult['complete_percent']=  round(($completeTask*100)/$totalTask,0);
            $cresult['complete']=$completeTask;
            $cresult['task']=$totalTask;
            $cresult['incomplete']=$totalTask-$completeTask;
            
            return $cresult;

       } 
       else{
            $cresult = array();
            $cresult['complete_percent']=  0;
            $cresult['complete']=0;
            $cresult['task']=$totalTask;
            $cresult['incomplete']=$totalTask;
            
            return $cresult;
        
       }   
    }
    function getAllProjectList(){
        
           
        $currentRole=  $this->current_user->roles[0];
        $user_id= $this->current_user->id;
        $Qstr="";
        
        $table_name = $this->wpdb->prefix . "pbx_project";
        if($currentRole=="editor")
            $Qstr=" where author=$user_id";
        
        $sql="select id,name from $table_name $Qstr";
        $result=$this->wpdb->get_results($sql);
        
        return  $result;  
    }
    function getCompanyProjectList($cid,$all,$from,$to){
        $table_name = $this->wpdb->prefix . "pbx_project";
        
        $qStr="";
        if($all==0)
        $qStr =" and DATE_FORMAT(create_date,'%Y-%m-%d') BETWEEN '$from' and '$to' ";
        
        $sql="select * from $table_name where cid=$cid $qStr";
        $result=$this->wpdb->get_results($sql);
        
        return  $result;  
    }  
}  
?>