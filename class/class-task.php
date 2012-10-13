<?php

/**
 * Task CLASS 
 *
 * @link http://www.mdimran.net/
 * @author Imran <imran.aspire@gmail.com>
 *
 */

class task{
    
    private $wpdb;
    private $current_user;
   
    public function task(){
         global $wpdb,$current_user;
         $this->wpdb=$wpdb;
         get_current_user();
         $this->current_user=$current_user;
    }
    
    /**
    * Create task table
    *
    * 
    * @return bool 'true' | false(if failed)
    */
    public function createTable(){
        
        
        
        $table_name = $this->wpdb->prefix . "pbx_task";
        // status :   todo => 1 , complete = > 2 , repone =>3 
       
        if($this->wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $sql = "CREATE TABLE " . $table_name . " (
            	id          bigint(11) NOT NULL AUTO_INCREMENT,
                pid          bigint(11),
            	author          bigint(11),
            	
                title       varchar(555) NOT NULL,
            	description       TEXT,
                
                create_date    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
                update_date    TIMESTAMP ,
                startdate    TIMESTAMP,
                enddate    TIMESTAMP,
                status       tinyint DEFAULT '1',
                nstatus       tinyint DEFAULT '0',
                PRIMARY KEY   (id)
                
            );";
            $results = $this->wpdb->query( $sql );
        }
       
    
    }
    
    function taskList($pid){
        $taskListStr='<div id="pbxAddTaskFormBox">';
        if($this->current_user->roles[0]!="subscriber")
            $taskListStr .= $this->taskAddForm($pid); 
        $taskListStr .='</div>';            
       
       $user_id = $this->current_user->ID;
      
       // list of all task of this project
       
       $tasktable = $this->wpdb->prefix . "pbx_task";
       $usertable = $this->wpdb->prefix . "users";
       
       //$user_table_name = $this->wpdb->prefix . "pbx_project_user";
       
       if($this->current_user->roles[0]!="subscriber"){  
            $sql="SELECT $tasktable.id,title,user_login,enddate,`status` 
                    FROM `$tasktable`,$usertable 
                    where $usertable.ID=$tasktable.author and $tasktable.pid=$pid  order by $tasktable.id desc";
       }else{
            $sql="SELECT $tasktable.id,title,user_login,enddate,`status` 
                    FROM `$tasktable`,$usertable 
                    where $usertable.ID=$tasktable.author and $tasktable.pid=$pid and $usertable.ID=$user_id  order by $tasktable.id desc";
        
       } 
       //echo $sql;   
       $tableData="";
       $result=$this->wpdb->get_results($sql);
       if($result){
            $tableData='
            <div class="table">
                <div class="head"><h5 class="iFrames">Task List</h5></div>
                <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Assign To</th>
                            <th>DeadLine</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>';
            foreach($result as $row){
                
                $staus="<span style='color:green;'>Complete</span>";
                if($row->status==1)
                    $staus="<span style='color:red;'>InComplete</span>";
                
                $datetime = new DateTime($row->enddate);
                $enddate = $datetime->format('Y-m-d'); 
                
                
                $tableData.=' 
                        <tr class="gradeA">
                            <td>'.$row->id.'</td>
                            <td><a href="javascript:void(0)" class="taskDetail" rel="'.$row->id.'">'.$row->title.'</a></td>
                            <td>'.$row->user_login.'</td>
                            <td class="center">'.$enddate.'</td>
                            <td class="center">'.$staus.'</td>
                        </tr>';    
            }
            $tableData .= '
                    </tbody>
                </table>
            </div>
            <div id="taskDetailBox"></div>
            ';
       }
       
       $taskListStr .=$tableData;
            
        $script ='
              <script type="text/javascript">
                jQuery(function(){
                    oTable = jQuery(\'#example\').dataTable({
                		"bJQueryUI": true,
                		"sPaginationType": "full_numbers",
                		"sDom": \'<""f>t<"F"lp>\'
                	});        
                });
             </script>       
            ';
       
       return $taskListStr.$script;                 
    }
    /**
     * task  ADD Form
     * */
    function taskAddForm($pid){
                          
        $client= new client();
        
        $userList=$client->getAllProjectClient($pid);
        
        
        $userStr="";
        
        // get the wp user list
         
        if($userList){
            foreach($userList as $user){
                 $userStr .= '<option value="'.$user->ID.'">'.$user->user_login.'</option>';
            }
        } 
        
        $uplaodUrl=PBX_WP_PLUGIN_URL."/projectbasix/ajax/upload.php?pid=$pid";
         $formAddStr=
            '<!-- Another version of text inputs -->
           
                <form action="" id="addTaskForm" method="post" >
                <div class="widget mywidget" >
                    <div class="head">
                        <a class="clickFormBox" rel="addTaskFormBox" href="javascript:void(0);">
                            <h5 class="iList"> ADD Task</h5>
                        </a>
                    </div>
                    <div id="addTaskFormBox">
                        <div class="floatleft twoOne">
                            <div class="rowElem noborder pb0">
                                <label class="topLabel">Title</label>
                                <div class="formBottom"><input type="text" name="title" id="title"/></div>
                                <div class="fix"></div>
                            </div>
                            <div class="rowElem">
                                <label>Deadline</label>
                                <div class="formBottom">
                                    <input type="text" class="datepicker" name="deadline" id="datepicker" style="width: 150px !important;" />
                                </div>
                                <div class="fix"></div>
                            </div>
                  
                           
                            <div class="rowElem">
                                <label>Assign To</label>
                                <div class="formBottom">
                                   <div class="select-wrapper">
                                        <select id="select" name="author">
                                            '.$userStr.'
                                        </select>
                                    </div>
                                 </div>
                            </div>
                           
                        </div>
                        <div class="floatright twoOne">
                            <div class="rowElem noborder">
                                <label class="topLabel">Descrription:</label>
                                <div class="formBottom">
                                    <textarea rows="6" name="description" cols="" name="textarea"></textarea>
                                </div>
                                <div class="fix"></div>
                            </div>
                            
                            <input type="submit" value="Add Task" class="greyishBtn submitForm" /> 
                        </div>
                    </div>
                </div>
                <div class="addTaskFormBox">
                    <div class="widget" style="margin-left: 12px;width: 667px;">    
                        <div class="head"><h5 class="iUpload">File upload</h5></div>
                        <div id="uploader">You browser doesn\'t have HTML 4 support.</div>
                    </div>
                   
                    <input type="hidden" name="type" value="addTask" />
                    <input type="hidden" name="pid" value="'.$pid.'" /> 
                    </form>
                </div>    
            <script type="text/javascript">
                jQuery(function(){
                	jQuery("#uploader").pluploadQueue({
                		runtimes : \'html5,html4\',
                		url : \''.$uplaodUrl.'\',
                		max_file_size : \'10mb\',
                		unique_names : true,
                		filters : [
                			{title : "Image files", extensions : "jpg,gif,png"},
                            {title : "Docs files", extensions : "doc,docx,xls,xlsx,ppt,pttx,pdf"},
                			{title : "Zip files", extensions : "zip,rar"}
                		]
                	});
                });
            </script>';
         
         
         return $formAddStr;
    }
    
        /**
     * task  ADD Form
     * */
    function editTask($tid){
                          
        $userList=get_users_of_blog();
        $userStr="";
        
        $userObj =new user();
        $files= new file();
        
        
        $tasktable = $this->wpdb->prefix . "pbx_task";
        $flietable = $this->wpdb->prefix . "pbx_file";
        
        $sql= "Select *from $tasktable where id=$tid";
        $result=$this->wpdb->get_row($sql);
        
        $completed="";
        if($result->status==2)
        $completed=" checked='checked' ";
        
        
        // get the wp user list
         
        if($userList){
            foreach($userList as $user){
                 $userStr .= '<option value="'.$user->user_id.'">'.$user->display_name.'</option>';
            }
        } 
        
        $datetime = new DateTime($result->enddate);
        $deadline = $datetime->format('Y-m-d'); 
        
        $color="green";
        $remind="";
        if(strtotime(date("Y-m-d"))>strtotime($deadline) && $result->status==1){
            $color="red";
            if($this->current_user->roles[0]!="subscriber")
                $remind ='<p><a class="sendremaind" rel="'.$result->id.'" href="javascript:void(0);">Send Remind Email</a></p>';
        }    
        
        
        // get files table of a task 
        $filesStr=$files->getFileList($tid);
        
        // get the comments list
        $comment = new comment(); 
        $commentList = $comment->showTaskComments($tid);
        
        // title data
        if($this->current_user->roles[0]!="subscriber") 
            $title ='<input value="'.$result->title.'" type="text" name="title"/>';
        else
            $title ='<label> '.$result->title.'</label>'.'<input value="'.$result->title.'" type="hidden" name="title"/>';
        
        // dead line 
        if($this->current_user->roles[0]!="subscriber")
            $deadline= '<input type="text" value="'.$deadline.'" class="datepicker"  style="color:'.$color.';" name="deadline"  style="width: 150px !important;" />';
        else
             $deadline ='<label style="color:'.$color.';" > '.$deadline.'</label>';
        
     
        // description 
       
       if($this->current_user->roles[0]!="subscriber")
            $des= '<textarea rows="6" name="description" cols="" name="textarea">'.$result->description.'</textarea>';       
       else
             $des ='<p> '.$result->description.'</p>';
             
        $uplaodUrl=PBX_WP_PLUGIN_URL."/projectbasix/ajax/upload.php?pid=$result->pid";
         $formAddStr=
            '<!-- Another version of text inputs -->
           
                <form action="" id="editTaskForm" method="post" >
                <div class="widget mywidget" >
                    <div class="head">
                        <a class="clickFormBox" rel="" href="javascript:void(0);">
                            <h5 class="iList"> Update Task</h5>
                        </a>
                    </div>
                    <div>
                        <div class="floatleft twoOne">
                            <div class="rowElem noborder pb0">
                                <label class="topLabel">Title</label>
                                <div class="formBottom">'.$title.'</div>
                                <div class="fix"></div>
                            </div>
                            <div class="rowElem">
                                <label>Deadline</label>
                                <div class="formBottom">'.$deadline.'</div>
                                <div class="fix"></div>
                            </div>
                  
                           
                            <div class="rowElem">
                                <label>Assign To</label>
                                <div class="formBottom">
                                       '.$userObj->getUserLoginName($result->author).'
                                 </div>
                            </div>
                             <div class="rowElem">
                                <label>Task Status</label>
                                <p>
                                    <label>Completed</label>
                                    <input '.$completed.' name="status" type="checkbox" />
                                </p>
                                '.$remind.'
                            </div>
                                                  
                        </div>
                       
                        <div class="floatright twoOne">
                            <div class="rowElem noborder">
                                <label class="topLabel">Description:</label>
                                <div class="formBottom">'.$des.'</div>
                                <div class="fix"></div>
                            </div>
                            <div class="rowElem noborder">
                                <label class="topLabel">Comments:</label>
                                <div class="formBottom"><textarea rows="6" name="comment"></textarea></div>
                                <div class="fix"></div>
                            </div>
                            <input type="submit" value="Update Task" class="greyishBtn submitForm" /> 
                        </div>
                    </div>
                
                <br/>
                <input type="hidden" name="type" value="editTask" />
                <input type="hidden" name="author" value="'.$result->author.'" />
               
             
              '.$filesStr.'
              '.$commentList.'    
              <div class="addTaskFormBox2">
                    <div class="widget" style="margin-left: 12px;width: 667px;">    
                        <div class="head"><h5 class="iUpload">ADD new Attachment</h5></div>
                        <div id="uploader2">You browser doesn\'t have HTML 4 support.</div>
                    </div>
                   
                    
                      <input type="hidden" name="pid" id="pid" value="'.$result->pid.'" /> 
                    <input type="hidden" name="tid" value="'.$tid.'" /> 
                    </form>
              </div>
            </form>
            </div>       
            <script type="text/javascript">
                jQuery(function(){
                	jQuery("#uploader2").pluploadQueue({
                		runtimes : \'html5,html4\',
                		url : \''.$uplaodUrl.'\',
                		max_file_size : \'10mb\',
                		unique_names : true,
                		filters : [
                			{title : "Image files", extensions : "jpg,gif,png"},
                            {title : "Docs files", extensions : "doc,docx,xls,xlsx,ppt,pttx,pdf"},
                			{title : "Zip files", extensions : "zip,rar"}
                		]
                	});
                });
            </script>
              ';
         
         
         return $formAddStr;
    }
        /**
     * task  ADD Form
     * */
    function showUserTask($tid){
                          
        $userList=get_users_of_blog();
        $userStr="";
        
        $userObj =new user();
        
        
        $tasktable = $this->wpdb->prefix . "pbx_task";
        $flietable = $this->wpdb->prefix . "pbx_file";
        
        $sql= "Select *from $tasktable where id=$tid";
        $result=$this->wpdb->get_row($sql);
        
        $completed="";
        if($result->status==2)
        $completed=" checked='checked' ";
        
        
        // get the wp user list
         
        if($userList){
            foreach($userList as $user){
                 $userStr .= '<option value="'.$user->user_id.'">'.$user->display_name.'</option>';
            }
        } 
        
        
        $sql="Select *from $flietable where tid=$tid";
        $fileResult=$this->wpdb->get_results($sql);
        
        $filesStr = "";
        if($fileResult){
          
          $filesStr='<div class="rowElem">
                                <label>Flies </label>
                                    <div class="formBottom">';
           foreach($fileResult as $row){   //'.$filepath.$row->filename.'
            $filepath=PBX_WP_CONTENT_URL."/uploads/projectbasix/$row->pid/";  
            $filesStr .= '<p style="padding:0px "><span>'.$row->title.'</span>    <a rel="nofollow" target="_blank" class="fileDownload"  href="'.$filepath.$row->filename.'" >Download</a></p>'; 
           }
                                    
           $filesStr .='  </div>
                                <div class="fix"></div>
                            </div>';                                     
        }
        
        
        
        
        $uplaodUrl=PBX_WP_PLUGIN_URL."/projectbasix/ajax/upload.php?pid=$pid";
         $formAddStr=
            '<!-- Another version of text inputs -->
           
                <form action="" id="editTaskForm" method="post" >
                <div class="widget mywidget" >
                    <div class="head">
                        <a class="clickFormBox" rel="" href="javascript:void(0);">
                            <h5 class="iList">'.$result->title.'</h5>
                        </a>
                    </div>
                    <div>
                        <div class="floatleft twoOne">
                           
                            <div class="rowElem">
                                <label>Deadline</label>
                                <div class="formBottom">
                                    <label>'.$result->enddate.' </label>
                                </div>
                                <div class="fix"></div>
                            </div>
                  
                            <div class="rowElem">
                                <p>
                                    <label>Completed</label>
                                    <input '.$completed.' name="status" type="checkbox" />
                                </p>
                            </div>
                            '.$filesStr.'                           
                        </div>
                        <div class="floatright twoOne">
                            <div class="rowElem noborder">
                                <label class="topLabel">Descrription:</label>
                                <div class="formBottom">
                                    <p>'.$result->description.'</p>
                                </div>
                                <div class="fix"></div>
                            </div>
                            <input type="submit" value="Update Task" class="greyishBtn submitForm" /> 
                        </div>
                    </div>
                </div>
                <input type="hidden" name="type" value="editTask" />
                <input type="hidden" name="tid" value="'.$tid.'" /> 
                <input type="hidden" name="pid" id="pid" value="'.$result->pid.'" /> 
              </form>';
         
         
         return $formAddStr;
    }
    
function updateTask($data){

    $table_name = $this->wpdb->prefix . "pbx_task";
    $filetable = $this->wpdb->prefix . "pbx_file";
    $project_table = $this->wpdb->prefix . "pbx_project";
    $notification = new notification();
    $pid=$data['pid'];
    $tid=$data['tid'];  
    
    $sql="select name,author from $project_table where id=$pid";
    $presult= $this->wpdb->get_row($sql);
    $pname=$presult->name;
    $pauthor=$presult->author;
    
    $user = new user();
    $to=$user->getUserEmail($data['author']);
    $update_time = date('Y-m-d G:i'); 
    if($this->current_user->roles[0]!="subscriber"){
        
        // update the task detail 
        $status =1;
        if(isset($data['status']))
            $status=2;
            
        $result = $this->wpdb->update( 
            $table_name, 
            array( 
                'title' => $data['title'], 
                'description' => $data['description'] ,
                'enddate' => $data['deadline'],
                'status' => $status	,
                'update_date'=> $update_time
            ), 
            array( 'id' => $data['tid'] ), 
            array( 
                '%s',	// value1
                '%s',
                '%s',
                '%d',
                '%s'
            ), 
            array( '%d' ) 
        );
        
        // add notification 
        if(isset($result) && $status==1)
            $notification->updateTaskNotification($data['title'],$data['description'],$data['deadline'],$data['author'],$pname,$to);
        
        // add complete notification 
        if($status==2)
            $notification->completeTaskNotification($data['title'],$pname,$pauthor,$data['author'],$update_time);
      
        
        
    }
    else{
          // update the task detail 
        $status =1;
        if(isset($data['status']))
        $status=2;
        
        $result = $this->wpdb->update( 
            $table_name, 
            array( 
                'status' => $status	,
                'update_date'=> $update_time
            ), 
            array( 'id' => $data['tid'] ), 
            array( 
                '%d',
                '%s'
            ), 
            array( '%d' ) 
        );
        
        
        $notification->completeTaskNotification($data['title'],$pname,$pauthor,$data['author'],$update_time);
     
    }  
    
    // add comment
    
       
    if($_POST['comment']!=""){
        $comment= new comment();
        $result =$comment->addComment($data['comment'],1,$tid);
        
        $notification->addCommentNotification($data['title'],$data['comment'],$pname,$pauthor,$data['author']);
        
     }
    
    
    // add new attachement file 
    
    $pid=$data['pid'];
    $tid=$data['tid'];
    //  update files 
    $totalFiles=$data['uploader2_count'];
    $filepath=PBX_WP_CONTENT_URL."/uploads/projectbasix/$pid";
    if($totalFiles){
        $insert_str="";
        $fileStr ="";
        for($i=0;$i<$totalFiles;$i++){
            $title=$data['uploader2_'.$i.'_name'];
            $fileName=$data['uploader2_'.$i.'_tmpname'];
            $insert_str .="($pid,$tid,'$fileName','$title'),";   
            
            $fileStr .="<p><a href='$filepath/$fileName' target='_blank' >$title</a></p>";                  
        }
    
        $insert_str = rtrim($insert_str,",");
        
        
        $sql="insert into $filetable (pid,tid,filename,title) values $insert_str";
        $result2 = $this->wpdb->query($sql);
        
        if($result2){
            $notification->addFileNotification($data['title'],$data['author'],$pname,$fileStr,$to);
            return true;
        }
        else
            return false;
    }
    
    return true;
}
    
    
    /**
     * updateUserTask
     * */
     function updateUserTask($data){
        
        $table_name = $this->wpdb->prefix . "pbx_task";
        
        $status =1;
        if(isset($data['status']))
        $status=2;
      
        $result = $this->wpdb->update( 
        	$table_name, 
        	array( 
        	    'status' => $status	
        	), 
        	array( 'id' => $data['tid'] ), 
        	array( 
        	    '%d'
                
            ), 
        	array( '%d' ) 
        );
        //print_r($data);
        //echo $this->wpdb->last_query;
        if($result)
            return true;
        else
            return false;  
        
     }
    /*
    uploader.bind('UploadProgress', function() {
        if (uploader.total.uploaded == uploader.files.length) {
            $(".plupload_buttons").css("display", "inline");
            $(".plupload_upload_status").css("display", "inline");
            $(".plupload_start").addClass("plupload_disabled");
        }
    });
    
    uploader.bind('QueueChanged', function() {
        $(".plupload_start").removeClass("plupload_disabled");
    });
    */
    /**
     * addTask
     * */
   function addTask($data){
    //uploader_0_tmpname
        global $current_user;
        $table_name = $this->wpdb->prefix . "pbx_task";
        $project_table = $this->wpdb->prefix . "pbx_project";
        // insert task 
        $this->wpdb->insert( $table_name, 
        	array( 
        		'title' => $data['title'], 
        		'description' => $data['description'] ,
        		'author' => $data['author'] ,
                'enddate' => $data['deadline'],
                'pid' =>$data['pid']
        	), 
        	array( 
        		'%s','%s','%d','%s','%d' 
        	) 
        );
        $tid=$this->wpdb->insert_id;
        $pid=$data['pid'];
        
        $sql="select name from $project_table where id=$pid";
        $presult= $this->wpdb->get_row($sql);
        $pname=$presult->name;
        //add  acivity 
        
        $user = new user();
        $to=$user->getUserEmail($data['author']);
        
        //  add notification   
        $notification = new notification();
        $notification->addTaskNotification($data['title'],$data['description'],$data['deadline'],$data['author'],$pname,$to);
                
        
        // insert project id 
       
      
        
        if($tid){
            $totalFiles=$data['uploader_count'];
            //echo $totalFiles.">>";
            $filepath=PBX_WP_CONTENT_URL."/uploads/projectbasix/$pid";
            if($totalFiles){
                $insert_str="";
                $fileStr="";
                for($i=0;$i<$totalFiles;$i++){
                 $title=$data['uploader_'.$i.'_name'];
                 $fileName=$data['uploader_'.$i.'_tmpname'];
                 $insert_str .="($pid,$tid,'$fileName','$title'),";
                 $fileStr .="<p><a href='$filepath/$fileName' target='_blank' >$title</a></p>";               
                }
                //die
                $insert_str = rtrim($insert_str,",");
                    
                $table_name = $this->wpdb->prefix . "pbx_file";
                $sql="insert into $table_name (pid,tid,filename,title) values $insert_str";
                
                $result = $this->wpdb->query($sql);
                
                if($result){
                    $notification->addFileNotification($data['title'],$data['author'],$pname,$fileStr,$to);
                    return true;
                }        
                else
                    return false; 
            
            }else
                return true;
        }    
        else
              return false; 
       
   }  
   
    /**
     * task  ADD Form
     * */
    function showTaskTab(){
                          
        $user= new user();
        $userList=$user->getProjectUser($pid);
        
        $project = new project();
        $projectStr="";
        $projectList=$project->getAllProjectList();
        if($projectList){
            $projectStr .='<div class="rowElem">
                                <label>Project</label>
                                <div class="formBottom">
                                   <div class="select-wrapper">
                                        <select id="projectSelect" name="pid" class="requiredField">
                                            <option value="">Select Project</option>';
            foreach($projectList as $row){
                            $projectStr .="<option value='".$row->id."'>".$row->name."</option>";
            }
            $projectStr .= '            </select>
                                    </div>
                                 </div>
                            </div>';
            
        }
        
        
        $userStr="";
        
        //get the wp user list
         
        if($userList){
            foreach($userList as $user){
                 $userStr .= '<option value="'.$user->ID.'">'.$user->user_login.'</option>';
            }
        } 
        
        $uplaodUrl= PBX_WP_PLUGIN_URL."/projectbasix/ajax/upload.php?type=addTask";
        
        
        $formAddStr=
            '<form action="" id="addProjectTaskForm" method="post" >
                <div class="widget mywidget" >
                    <div class="head">
                        <a class="clickFormBox" rel="addTaskFormBox" href="javascript:void(0);">
                            <h5 class="iList"> ADD Task</h5>
                        </a>
                    </div>
                    <div>
                        <div class="floatleft twoOne">
                            <div class="rowElem noborder pb0">
                                <label class="topLabel">Title</label>
                                <div class="formBottom"><input  class="requiredField" type="text" name="title"/></div>
                                <div class="fix"></div>
                            </div>
                            <div class="rowElem">
                                <label>Deadline</label>
                                <div class="formBottom">
                                    <input type="text" class="datepicker requiredField" name="deadline" id="datepicker" style="width: 150px !important;" />
                                </div>
                                <div class="fix"></div>
                            </div>
                  
                           '.$projectStr.'
                            <div class="rowElem taskBox">
                                
                            </div>
                           
                        </div>
                        <div class="floatright twoOne">
                            <div class="rowElem noborder">
                                <label class="topLabel">Descrription:</label>
                                <div class="formBottom">
                                    <textarea rows="6" name="description" cols="" name="textarea"></textarea>
                                </div>
                                <div class="fix"></div>
                            </div>
                            
                            <input type="submit" value="Add Task" class="greyishBtn submitForm" /> 
                        </div>
                    </div>
                    <div class="addTaskFormBox3">
                        <div class="widget" style="margin-left: 12px;width: 690px;">    
                            <div class="head"><h5 class="iUpload">File upload</h5></div>
                            <div id="uploader">You browser doesn\'t have HTML 4 support.</div>
                        </div>
                        <input type="hidden" name="type" value="addProjectTask" />
                        <input type="hidden" id="uploadurl" value="'.$uplaodUrl.'" />
                      
                       
                    </div>
                </div>
              </form>      
            <script type="text/javascript">
                jQuery(function(){
                	jQuery("#uploader").pluploadQueue({
                		runtimes : \'html5,html4\',
                		url : \''.$uplaodUrl.'\',
                		max_file_size : \'10mb\',
                		unique_names : true,
                		filters : [
                			{title : "Image files", extensions : "jpg,gif,png"},
                            {title : "Docs files", extensions : "doc,docx,xls,xlsx,ppt,pttx,pdf"},
                			{title : "Zip files", extensions : "zip,rar"}
                		]
                	});
                });
            </script>';
         
         
         return $formAddStr;
    }   
    
    function getProjectTaskList($pid){
       $table_name = $this->wpdb->prefix . "pbx_task";
       
       $sql="select id,title from $table_name where pid=$pid ";
       $result=$this->wpdb->get_results($sql);
       
       
        
        $taskStr="";
        if($result){
             $taskStr .= '<label>Select Task</label>
                     <div class="formBottom">
                       <div class="select-wrapper">
                            <select id="select" name="author" >';
            foreach($result as $row){
                            $taskStr  .="<option value='".$row->id."'>".$row->title."</option>";
            }
            $taskStr .='    </select>
                        </div>
                     </div>';
            
        }
       
                            
        return $taskStr;                 
            
    }
    
    function clientTaskList(){
        $taskListStr='<div id="pbxAddTaskFormBox">';
        if($this->current_user->roles[0]!="subscriber")
            $taskListStr .= $this->taskAddForm($pid); 
        $taskListStr .='</div>';            
       
       $user_id = $this->current_user->ID;
      
       // list of all task of this project
       
       $tasktable = $this->wpdb->prefix . "pbx_task";
       $usertable = $this->wpdb->prefix . "users";
       
       //$user_table_name = $this->wpdb->prefix . "pbx_project_user";
       
        
        $sql="SELECT $tasktable.id,title,user_login,enddate,`status` 
                FROM `$tasktable`,$usertable 
                where $usertable.ID=$tasktable.author and  $tasktable.author=$user_id  order by $tasktable.id desc";

       
       
       $tableData="";
       $result=$this->wpdb->get_results($sql);
       if($result){
            $tableData='
            <div class="table">
                <div class="head"><h5 class="iFrames">Latest Task List</h5></div>
                <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Assign To</th>
                            <th>DeadLine</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>';
            foreach($result as $row){
                
                $staus="<span style='color:green;'>Complete</span>";
                if($row->status==1)
                    $staus="<span style='color:red;'>InComplete</span>";
                
                $datetime = new DateTime($row->enddate);
                $enddate = $datetime->format('Y-m-d'); 
                
                $tableData.=' 
                        <tr class="gradeA">
                            <td>'.$row->id.'</td>
                            <td><a href="javascript:void(0)" class="taskDetail" rel="'.$row->id.'">'.$row->title.'</a></td>
                            <td>'.$row->user_login.'</td>
                            <td class="center">'.$enddate.'</td>
                            <td class="center">'.$staus.'</td>
                        </tr>';    
            }
            $tableData .= '
                    </tbody>
                </table>
            </div>
            <div id="taskDetailBox"></div>
            ';
       }
       
       $taskListStr .=$tableData;
            
        $script ='
              <script type="text/javascript">
                jQuery(function(){
                    oTable = jQuery(\'#example\').dataTable({
                		"bJQueryUI": true,
                		"sPaginationType": "full_numbers",
                		"sDom": \'<""f>t<"F"lp>\'
                	});        
                });
             </script>       
            ';
       
       return $taskListStr.$script;                 
    }
}  
?>