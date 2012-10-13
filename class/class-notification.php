<?php

/**
 * notification CLASS 
 *
 * @link http://www.mdimran.net/
 * @author Imran <imran.aspire@gmail.com>
 *
 */

class notification{
    
    private $wpdb,$current_user;
   
    public function notification(){
         global $wpdb,$current_user;
         $this->wpdb=$wpdb;
         get_current_user();
         $this->current_user=$current_user;
         
    }
   
    public function createTable(){
        
        $table_name = $this->wpdb->prefix . "pbx_notification";
        // status :   notShow => 1 , show = > 2 
        // mstatus :   send => 1 , notsend = > 2          
        if($this->wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name){
            $sql = "CREATE TABLE " . $table_name . " (
            	id          bigint(11) NOT NULL AUTO_INCREMENT,
                title       varchar(555) NOT NULL,
            	body       TEXT,
                author          bigint(11),
                
                create_date    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
                status       tinyint DEFAULT '1',
                mstatus       tinyint DEFAULT '1',
                email       varchar(555) NOT NULL,
                PRIMARY KEY   (id)
                
            );";
            $results = $this->wpdb->query($sql);
        }
       
    
    }
    public function sendNewClientNotification($company,$author,$name,$username,$password){
        
        $projectbasix_link=get_permalink(get_option("projectBasix_page"));
        $user = new user();
        
        
        $title = "You are added to company ".$company;
        $body = " <p>Dear <strong>$name</strong> you are invited to join the new company <strong>$company </strong></p>";
        $body .=" <p><strong>Username : $username</strong></p>";
        $body .=" <p><strong>Password : $password</strong></p>";
        $body .=" <p><a href='$projectbasix_link' >ProjectBasix Login</a></p>";
        
        // insert notification  
        $id=$this->addNotification($title,$body,$author,1);
        
        // send notification email 
        
        if($id){
           
           $subject=$title;
           $to=$user->getUserEmail($author);
            
           $name=get_bloginfo("name"); 
           $from=get_bloginfo("admin_email");   
           $headers = 'From: '.$name.' <'.$from.'>' . "\r\n";
            
           wp_mail( $to, $subject, $body, $headers); 
       
                
        }
    }
    public function sendOldClientNotification($company,$author,$name,$username){
        
       
      
        $projectbasix_link=get_permalink(get_option("projectBasix_page"));
        $user = new user();
        
        
        $title = "You are added to company ".$company;
        $body = " <p>Dear <strong>$name</strong> you are invited to join the new company <strong>$company </strong></p>";
        $body .=" <p><strong>Username : $username</strong></p>";
        $body .=" <p><a href='$projectbasix_link' >ProjectBasix Login</a></p>";
        
        
        // insert notification  
        $id=$this->addNotification($title,$body,$author,1);
        
        
        // send notification email 
        
        if($id){
            
           $subject=$title;
           $to=$user->getUserEmail($author);
            
           $name=get_bloginfo("name"); 
           $from=get_bloginfo("admin_email");   
           $headers = 'From: '.$name.' <'.$from.'>' . "\r\n";
           wp_mail( $to, $subject, $body, $headers); 
       
                
        }
    }
    
    public function newProjectNotification($name,$desc,$deadline,$cid){
        
        $table_name = $this->wpdb->prefix . "pbx_company_clients";
        $user_table = $this->wpdb->prefix . "users";
        $projectbasix_link=get_permalink(get_option("projectBasix_page"));
        
        $sql="select uid,user_email from $table_name,$user_table where $user_table.ID=$table_name.uid and cid=$cid";
        $clientList=$this->wpdb->get_results($sql);
        if($clientList){
            foreach($clientList as $row){
                
                $title = "New Project :".$name;
                $body = " <p>Dear Client A new Project is Added for you </p>";
                $body .=" <p><strong>Project Name : $name </strong></p>";
                $body .=" <p>$desc</p>";
                $body .=" <p><strong>DeadLine : $deadline </strong></p>";
                $body .=" <p><a href='$projectbasix_link' >ProjectBasix Login</a></p>";
                
                $this->addNotification($title,$body,$row->uid,2,$row->user_email);
                       
            }
        }
             
        
    }    
    
    public function addTaskNotification($tname,$desc,$deadline,$author, $pname,$to){
        
        $projectbasix_link=get_permalink(get_option("projectBasix_page"));
        
        $title = "A new task is Added To : ".$pname;
        $body = " <p>Dear Client a new task is added to $pname . Please Login for more details </p>";
        $body .=" <p><strong>Project : $pname</strong></p>";
        $body .=" <p><strong>Task : $tname</strong></p>";
        $body .=" <p>$desc</p>";
        $body .=" <p><strong>DeadLine : $deadline</strong></p>";
        
        $body .=" <p><a href='$projectbasix_link' >ProjectBasix Login</a></p>";
        
        // insert notification  
        $id=$this->addNotification($title,$body,$author,1);
        
        // send notification email 
        
        if($id){
            
           $subject=$title;
           $name=get_bloginfo("name"); 
           $from=get_bloginfo("admin_email");   
           $headers = 'From: '.$name.' <'.$from.'>' . "\r\n";
           wp_mail( $to, $subject, $body, $headers); 
       
                
        }
        
    }
     public function updateTaskNotification($tname,$desc,$deadline,$author, $pname,$to){
        
        $projectbasix_link=get_permalink(get_option("projectBasix_page"));
        
        $title = "Task Updated : ".$tname;
        $body = " <p>Dear Client  task  $tname is updated . Please Login for more details </p>";
        $body .=" <p><strong>Project : $pname</strong></p>";
        $body .=" <p><strong>Task : $tname</strong></p>";
        $body .=" <p>$desc</p>";
        $body .=" <p><strong>DeadLine : $deadline</strong></p>";
        
        $body .=" <p><a href='$projectbasix_link' >ProjectBasix Login</a></p>";
        
        // insert notification  
        $id=$this->addNotification($title,$body,$author,1);
        
        // send notification email 
        
        if($id){
            
           $subject=$title;
           $name=get_bloginfo("name"); 
           $from=get_bloginfo("admin_email");   
           $headers = 'From: '.$name.' <'.$from.'>' . "\r\n";
           wp_mail( $to, $subject, $body, $headers); 
       
                
        }
        
    }
    public function completeTaskNotification($tname,$pname,$pauthor,$tauthor,$update_time){
        
        $projectbasix_link=get_permalink(get_option("projectBasix_page"));
        
        $user = new user();
        $temail=$user->getUserEmail($tauthor);
        $pemail=$user->getUserEmail($pauthor);
        $completedBy = $user->getUserLoginName($this->current_user->ID);
        
        $title = "Task Completed : ".$tname;
        $body .=" <p><strong>Project : $pname</strong></p>";
        $body .=" <p><strong>Task : $tname</strong></p>";
        $body .=" <p><strong>Completed By : $completedBy</strong></p>";
        $body .=" <p><strong>Completed In : $update_time</strong></p>";
        $body .=" <p><a href='$projectbasix_link' >ProjectBasix Login</a></p>";
        
        // insert notification  
        $id=$this->addNotification($title,$body,$pauthor,1);
        $id=$this->addNotification($title,$body,$tauthor,1);
        
        // send notification email 
        
        if($id){
          
           $subject=$title;
           $name=get_bloginfo("name"); 
           $from=get_bloginfo("admin_email");   
           $headers = 'From: '.$name.' <'.$from.'>' . "\r\n";
           $headers .= "CC: $pemail".PHP_EOL;
            
           wp_mail( $temail, $subject, $body, $headers); 
       
        }    
    }
    public function addCommentNotification($tname,$comments,$pname,$pauthor,$tauthor){
        
        $projectbasix_link=get_permalink(get_option("projectBasix_page"));
        
        $user = new user();
        $temail=$user->getUserEmail($tauthor);
        $pemail=$user->getUserEmail($pauthor);
        $commentBy = $user->getUserLoginName($this->current_user->ID);
        
        $title = "New Comemnts ADD to: ".$tname;
        $body .=" <p><strong>Project : $pname</strong></p>";
        $body .=" <p><strong>Task : $tname</strong></p>";
        
        $body .=" <p><strong>Comment By : $commentBy</strong></p>";
        $body .=" <p><strong>Comemnts : $comments</strong></p>";
        $body .=" <p><a href='$projectbasix_link' >ProjectBasix Login</a></p>";
        
        // insert notification  
        $id=$this->addNotification($title,$body,$pauthor,1);
        $id=$this->addNotification($title,$body,$tauthor,1);
        
        // send notification email 
        
        if($id){
          
           $subject=$title;
           $name=get_bloginfo("name"); 
           $from=get_bloginfo("admin_email");   
           $headers = 'From: '.$name.' <'.$from.'>' . "\r\n";
           $headers .= "CC: $pemail".PHP_EOL;
            
           wp_mail( $temail, $subject, $body, $headers); 
       
        }    
    }
    public function addFileNotification($tname,$author,$pname,$fileStr,$to){
        
        $projectbasix_link=get_permalink(get_option("projectBasix_page"));
        
        $title = "A new File is Added To : ".$tname;
        $body = " <p>Dear Client a new file is added to $title . Please Login for more details </p>";
        $body .=" <p><strong>Project : $pname</strong></p>";
        $body .=" <p><strong>Task : $tname</strong></p>";
        $body .="$fileStr";
        
        $body .=" <p><a href='$projectbasix_link' >ProjectBasix Login</a></p>";
        
        // insert notification  
        $id=$this->addNotification($title,$body,$author,1);
        
        // send notification email 
        
        if($id){
            
           $subject=$title;
           $name=get_bloginfo("name"); 
           $from=get_bloginfo("admin_email");   
           $headers = 'From: '.$name.' <'.$from.'>' . "\r\n";
           wp_mail( $to, $subject, $body, $headers); 
       
                
        }    
    }
    
    // cron job in twice in a day 
    public function taskDeadLine(){
        $table_name = $this->wpdb->prefix . "pbx_task";
        $notification_table = $this->wpdb->prefix . "pbx_notification";
        $project_table = $this->wpdb->prefix . "pbx_project";
        $user_table = $this->wpdb->prefix . "users";
        
        $projectbasix_link=get_permalink(get_option("projectBasix_page"));
        
        $sql="SELECT $table_name.id,$table_name.author ,DATE_FORMAT(enddate,'%Y-%m-%d') as deadline,title ,name ,user_email 
                    from $table_name,$project_table,$user_table
                    WHERE
                    $user_table.ID=$table_name.author and 
                    $table_name.pid=$project_table.id and
                    DATE_FORMAT(enddate,'%Y-%m-%d')=DATE_FORMAT(DATE_ADD(CURRENT_DATE, INTERVAL 7 DAY),'%Y-%m-%d') 
                    and nstatus=0";
        
        $deadlinetaskList = $this->wpdb->get_results($sql);
        
        
        if($deadlinetaskList){
            foreach($deadlinetaskList as $row){
                
                //"In order for us to meet our deadline, you need to respond by 2-28-2012"
                $title = "Warning ! Task Deadline Near for : ".$row->name;
                $body = " <p>Dear Client , There is a warning about the following task Please Respond by $row->deadline . Please Login for more details </p>";
                $body .=" <p><strong>Project : $row->name</strong></p>";
                $body .=" <p><strong>Task : $row->title</strong></p>";
                $body .=" <p><strong>Deadline : $row->deadline</strong></p>";
                
                $body .=" <p><a href='$projectbasix_link' >ProjectBasix Login</a></p>";
                
                // insert notification  
                $id=$this->addNotification($title,$body,$row->author,2,$row->user_email);
                if($id){
                    
                    $result = $this->wpdb->update( 
                    $table_name, 
                    array( 
                        'nstatus' => $id
                    ), 
                    array( 'id' => $row->id ), 
                    array( 
                        '%d'
                    ), 
                    array( '%d' ) 
                    );
                                        
                }
                    
            }
        }            
            
    }
    
    public function sendRemindNotification($tid){
        $table_name = $this->wpdb->prefix . "pbx_task";
        $notification_table = $this->wpdb->prefix . "pbx_notification";
        
        $sql= "select nstatus from $table_name where id=$tid";
        $result= $this->wpdb->get_row($sql);
        $nid=$result->nstatus;
        //echo $nid;
        if($nid){
           
           $sql= "select * from $notification_table where id=$nid";
           $result= $this->wpdb->get_row($sql);
         
           $subject=$result->title;
           $name=get_bloginfo("name"); 
           $from=get_bloginfo("admin_email");   
           $headers = 'From: '.$name.' <'.$from.'>' . "\r\n";
           //echo $result->body;
           wp_mail( $result->email, $subject, $result->body, $headers);
           return 1; 
        }
            
    }
    // cron job in 30mins 
    public function sendNotification(){
        $notification_table = $this->wpdb->prefix . "pbx_notification";
        
        $sql= "select * from $notification_table where mstatus=2";
        $result= $this->wpdb->get_results($sql);
        
        $name=get_bloginfo("name"); 
        $from=get_bloginfo("admin_email");   
                   
        
        $count=0;
        if($result){
            foreach($result as $row){
                if($count<20){
                    $subject=$row->title;
                    $headers = 'From: '.$name.' <'.$from.'>' . "\r\n";
                    wp_mail( $row->email, $subject, $row->body, $headers);
                    $this->wpdb->query("update  $notification_table set  mstatus=1 where id=$row->id");
                    //echo $row->body;
                    //echo "update  $notification_table set  mstatus=1 where id=$row->id";
                }
                else
                    break;
                
                $count++;
            }
        }
        
       
    }
    public function addNotification($title,$body,$author,$sendemail,$email=""){
        
        $table_name = $this->wpdb->prefix . "pbx_notification";
        $this->wpdb->insert( $table_name, 
        	array( 
        		'title' => $title, 
        		'body' => $body ,
                "author"=>$author,
                "mstatus"=>$sendemail,
                "email"=>$email
        	), 
        	array( 
        		'%s','%s','%d','%d','%s'
        	) 
        );
        return  $this->wpdb->insert_id;
        
    }
    public function getCurrentUserNotification(){
        $notification_table = $this->wpdb->prefix . "pbx_notification";
        $user_id= $this->current_user->id;
        
        $projectbasix_link=get_permalink(get_option("projectBasix_page"));
        if(get_option('permalink_structure')=="")
            $pageLink=$projectbasix_link."&page=";
        else 
            $pageLink=$projectbasix_link."?page=";  
            
        
        $sql= "select * from $notification_table where status=1 and author=$user_id order by id desc limit 10";
        $result= $this->wpdb->get_results($sql);
        $notificationListStr= "";
        if($result){
            $notificationListStr .="<ul>";
            foreach($result as $row){ 
                
                $datetime = new DateTime($row->create_date);
                $create_date = $datetime->format('Y/m/d'); 
        
                $notificationListStr .= "<li><a href='".$pageLink . "message&amp;action=showMessage&amp;id=" . $row->id . "'>$row->title</a><br /><small>on $create_date</small></li>";
            }
            $notificationListStr .="</ul>";
        }
        else
            $notificationListStr = " <p>No Notifications</p>";
        
        return $notificationListStr;    
    }
    public function getCurrentUserTotalNotification(){
        $notification_table = $this->wpdb->prefix . "pbx_notification";
        $user_id= $this->current_user->id;
        
        
        $sql= "select * from $notification_table where status=1 and author=$user_id ";
        $result= $this->wpdb->get_results($sql);
        if($result)
            return count($result);
        else
            return 0;    
        
    }
}  
?>