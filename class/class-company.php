<?php

/**
 * company CLASS 
 *
 * @link http://www.mdimran.net/
 * @author Imran <imran.aspire@gmail.com>
 *
 */

class company{
    
    private $wpdb;
    private $current_user;
    private $actionUrl;
   
    public function company(){
         global $wpdb;
         global $current_user;
         $this->wpdb=$wpdb;
         get_currentuserinfo();
         $this->current_user=$current_user;
         
         $access = new access();
         $access->chkAccess("contact");
         
         
        $projectbasix_link=get_permalink(get_option("projectBasix_page"));
        if(get_option('permalink_structure')=="")
            $pageLink=$projectbasix_link."&page=";
        else 
            $pageLink=$projectbasix_link."?page=";  
            
        
        $actionUrl=$pageLink . "company" ;
        $this->actionUrl=$actionUrl;
         
         
    }
    
    
    /**
     *  create Table
     * */
     
     
    public function createTable(){
      
        $table_name = $this->wpdb->prefix . "pbx_company";
        // status :   add in contact list  => 1 , add in wp  = > 2 
       
        if($this->wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $sql = "CREATE TABLE " . $table_name . " (
            	id          bigint(11) NOT NULL AUTO_INCREMENT,
                author          bigint(11),
                name       varchar(555) NOT NULL,
            	head       varchar(555) NOT NULL,
            	
                create_date    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
            	
                status       tinyint DEFAULT '1',
                PRIMARY KEY   (id)
                
            );";
          
            $results = $this->wpdb->query( $sql );
        }
        
         $table_name = $this->wpdb->prefix . "pbx_company_clients";
        // status :   add in contact list  => 1 , add in wp  = > 2 
       
        if($this->wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $sql = "CREATE TABLE " . $table_name . " (
            	id          bigint(11) NOT NULL AUTO_INCREMENT,
                cid          bigint(11),
                uid          bigint(11),
               
                create_date    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
               
                
                PRIMARY KEY   (id)
                
            );";
          
            $results = $this->wpdb->query( $sql );
        }
       
    
    }
    
    function index(){
        
        $chk=0;
        
        if($_POST['addCompany']=="Add Company"){
            $this->addCompany();    
        }
    
        if($_POST['addCompany']=="Update Company"){
            $this->updateCompany();    
        }
        
        if($_GET['action']=="editCompany"){
            $this->showAddCompanyForm($_GET['id']);
            $chk=1;
        }
        
        if($chk==0){
            $this->showAddCompanyForm();
            $this->showCompanyList();
        }     
    }  
    public function showAddCompanyForm($id=0){
        
        
        $submitValue="Add Company";
        $actionUrl=$this->actionUrl;
        $access = new access();
        $client = new client();
        $clientList = $client->getValidClientList();
        
        $currentRole = $this->current_user->roles[0];
        $user_id = $this->current_user->id;
        
        if($id){
            
            $submitValue = "Update Company";
            $table_name = $this->wpdb->prefix . "pbx_company";
            $qStr="";
            
            if($currentRole=="editor")
                $qStr=" and author=$user_id";
            
            $sql = "Select *from $table_name where id=$id $qStr";
            $result = $this->wpdb->get_row($sql);
            
            if(empty($result))
                $access->accesIsDenied();     
                 
        }
       
    ?>
    <br />
    <fieldset> 
    <div class="widget">
        <div class="head">
            <a class="clickFormBox" rel="clientBox" href="javascript:void(0);">
                <h5 class="iList"><?php echo $submitValue; ?></h5>
            </a>    
         </div>
        
        <div id="clientBox" <?php if($submitValue=="Update Company"); echo 'style="display: block;"'; ?>  >
            <form class="mainForm"  id="companyAddForm" method="post" action="<?php echo $actionUrl; ?>">  
             <div class="rowElem">
                <label>Company Name</label>
                <div class="formRight">
                    <input type="text" name="cname" id="cname" value="<?php echo $result->name; ?>" class="regular-text" />
                </div>
                <div class="fix"></div>
                                            
            </div>
            <div class="rowElem">
                <label>Company Heading</label>
                <div class="formRight">
                    <input type="text" name="head" id="head" value="<?php echo $result->head; ?>"  class="regular-text" />
                </div>
                <div class="fix"></div>
                                            
            </div>
            <?php
              //echo "LL";die;
            // get project user list with avater
            if($id){                        
                $table_name = $this->wpdb->prefix . "pbx_company_clients";
                $sql="select *from $table_name where cid =$id";
                $result2=$this->wpdb->get_results($sql);
                
                $userArray=array();
                
                $userlistStr="";
                if($result2){
                    $userlistStr .='<div class="avaterBox">';
                    foreach($result2 as $row){ 
                        $user_info = get_userdata($row->uid); //print_r($user_info->user_login);die;
                        $userlistStr .= ' <a id="au'.$row->uid.'" rel="'.$row->uid.'" class="basicBtn topDir mr40 ml40 companyUser" value="top direction" original-title="'.$user_info->user_login.'" href="javascript:void(0);">';
                        $userlistStr .= get_avatar( $row->uid, 32 ); 
                        $userlistStr .= '</a>';
                        $userArray[]=$row->author;
                    }
                    $userlistStr .= "</div>";
                }
                
                           
                // get the wp user list for multiselect 
               
                $usercontentStr=$userlistStr;
                if($result2){
                    echo '
                    <div class="rowElem">
                        <label>Existing Clients</label>
                        <div class="formRight">
                            '.$usercontentStr.' 
                            <br/> <small>Click To Delete Client</small>
                        </div>
                        <div class="fix"></div>
                                                    
                    </div>';
                }    
            }    
            ?>
            <div class="rowElem">
                <label>Add Clients</label>
                <div class="formRight">
                    <?php if($clientList){
                        echo '<select name="clientList[]" id="clientList" data-placeholder="Add Clients..." class="chzn-select" multiple style="width:540px;" tabindex="4">';
                        foreach($clientList as $row){
                            echo '<option value="'.$row->id.'">'.$row->first_name." ".$row->last_name.'</option>';
                        }
                        echo '</select>';
                    } ?>
                    <br />
                    <small>10 Client can add in a Company at a time</small>
                </div>
                <div class="fix"></div>
                                            
            </div>
            
    
            <div class="fix"></div>
            <input type="submit" value="<?php echo $submitValue; ?>" name="addCompany" class="greyishBtn submitForm" />
            <input type="hidden" name="id" value="<?php echo $result->id; ?>" />
            <div class="fix" ></div>
       </form>       
    </div>
               
    </div>       
    </fieldset>
    <?php    
    }
    public function showCompanyList(){
         $table_name = $this->wpdb->prefix . "pbx_company";
         $client_table = $this->wpdb->prefix . "pbx_company_clients";
         $currentRole=  $this->current_user->roles[0];
         $user_id = $this->current_user->id;
         
         $qStr="";
         if($currentRole=="editor")
            $qStr=" and $table_name.author=$user_id ";
         
         $sql="Select 
            $table_name.id,
            $table_name.name,
            count(cid) as clients 
            from 
            $table_name,
            $client_table 
            where 
            $client_table.cid=$table_name.id $qStr group by cid";
            
         $companyList=$this->wpdb->get_results($sql);  
         
      
        
           
    ?>
     <div class="table">
            <div class="head"><h5 class="iFrames">Company List</h5></div>
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Company Name</th>
                        <th>Clients</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($companyList){
                        foreach($companyList as $row){
                            echo '<tr>
                                    <td>'.$row->id.'</td>
                                    <td><a href="'.$this->actionUrl.'&action=editCompany&id='.$row->id.'">'.$row->name.'</a></td>
                                    <td>'.$row->clients.'</td> 
                                 </tr>';
                        }
                    } ?>
                </tbody>
            </table>
        </div>
    <?php    
    }
    function addCompany(){
        
        $table_name = $this->wpdb->prefix . "pbx_company";
        
        $author=$this->current_user->id;
        
        $data=$_POST;
        $this->wpdb->insert( $table_name, 
        	array( 
        		'name' => $data['cname'], 
        		'head' => $data['head'] ,
                "author"=>$author
        	), 
        	array( 
        		'%s','%s','%d'
        	) 
        );
        $id=$this->wpdb->insert_id;
        
        $clientObj = new client();
        
        if($id){
            $clientList=$_POST['clientList'];
            if($clientList){
                $totalErrormsg="";
                foreach($clientList as $row){
                    
                    $client = $clientObj->getClientDetail($row);
                    
                    if($client->user_id==0){
                    
                        $firstname = sanitize_text_field( $client->first_name);
                        $lastname = sanitize_text_field( $client->last_name );
                        $username = sanitize_text_field( $client->user_email );
                        $email = sanitize_text_field( $client->user_email );
                        //Add usernames we don't want used
                        $invalid_usernames = array( 'admin' );
                        //Do username validation
                        
                        $username = sanitize_user( $username );
                        if ( !validate_username( $username ) || in_array( $username, $invalid_usernames ) ) {
                        $errormsg = $username.' is invalid.';
                        }
                        if ( username_exists( $username ) ) {
                        $errormsg = $username.' already exists.';
                        }
                        //Do e-mail address validation
                        if ( !is_email( $email ) ) {
                        $errormsg = $email.' address is invalid.';
                        }
                        if (email_exists($email)) {
                        $errormsg=  $email.' address is already in use.';
                        }
                        
                        if($errormsg ==""){
                            $user_pass = wp_generate_password();
                            $user = array(
                            'user_login' => $username,
                            'user_pass' => $user_pass,
                            'first_name' => $firstname,
                            'last_name' => $lastname,
                            'user_email' => $email,
                            'role'=>"subscriber"
                            
                            );
                            $user_id = wp_insert_user( $user );
                            $clientObj->updateClientStatus($row,$user_id);
                            $this->addClient($id,$user_id);
                            
                            //wp_new_user_notification( $user_id, $user_pass );
                            $notification = new notification();
                            $notification->sendNewClientNotification($data['cname'],$user_id,$firstname." ".$lastname,$username,$user_pass);
                            
                        }else{
                            $totalErrormsg .="<p>$errormsg</p>";       
                        }
            
                    }
                    else{
                         $clientObj->updateClientStatus($row,$client->user_id);
                         $this->addClient($id,$client->user_id);    
                    }
            
                
                }    
            }
            
        }

         if($totalErrormsg!=""){
                echo '<div class="nNote nFailure hideit" style="display:block">
                        <p><strong>FAILURE: </strong>'.$totalErrormsg.'</p>
                      </div>';
            }else{
                 echo '<div class="nNote nSuccess hideit" style="display:block">
                        <p><strong>Success: </strong> Client is Successfully Added</p>
                      </div>';
            }
    }
    function updateCompany(){
        
        $table_name = $this->wpdb->prefix . "pbx_company";
        
        $author=$this->current_user->id;
        
        $cid=$_POST['id'];
        $data=$_POST;
        
        $sql="update  $table_name set name='".$data['cname']."', head='".$data['head']."' where id=$cid";
        //echo $sql;die;
        $this->wpdb->query($sql);
        
        $clientObj = new client();
        $notification = new notification();
        //print_r($_POST);die;
        
        $clientList=$_POST['clientList'];
        $totalErrormsg="";
        if($clientList){
            foreach($clientList as $row){
                $client = $clientObj->getClientDetail($row);
                //print_r($client);die;
                
                $firstname = sanitize_text_field( $client->first_name);
                $lastname = sanitize_text_field( $client->last_name );
                $username = sanitize_text_field( $client->user_email );
                $email = sanitize_text_field( $client->user_email );
                    
                if($client->user_id==0){
                    
                
                    //Add usernames we don't want used
                    $invalid_usernames = array( 'admin' );
                    //Do username validation
                    
                    $username = sanitize_user( $username );
                    if ( !validate_username( $username ) || in_array( $username, $invalid_usernames ) ) {
                    $errormsg = $username.' is invalid.';
                    }
                    if ( username_exists( $username ) ) {
                    $errormsg = $username.' already exists.';
                    }
                    //Do e-mail address validation
                    if ( !is_email( $email ) ) {
                    $errormsg = $email.' address is invalid.';
                    }
                    if (email_exists($email)) {
                    $errormsg=  $email.' address is already in use.';
                    }
                    
                    if($errormsg ==""){
                        $user_pass = wp_generate_password();
                        $user = array(
                        'user_login' => $username,
                        'user_pass' => $user_pass,
                        'first_name' => $firstname,
                        'last_name' => $lastname,
                        'user_email' => $email,
                        'role'=>"subscriber"
                        
                        );
                        $user_id = wp_insert_user( $user );
                        //echo $user_id.">>>";die;
                        $clientObj->updateClientStatus($row,$user_id);
                        $this->addClient($cid,$user_id);
                        
                        //wp_new_user_notification( $user_id, $user_pass );
                        
                       
                        $notification->sendNewClientNotification($data['cname'],$user_id,$firstname." ".$lastname,$username,$user_pass);
    
                        
                    }else{
                        $totalErrormsg .="<p>$errormsg</p>";       
                    }
                
                }else{
                    
                    $clientObj->updateClientStatus($row,$client->user_id);
                    $this->addClient($cid,$client->user_id);
        
                    $notification->sendOldClientNotification($data['cname'],$client->user_id,$firstname." ".$lastname,$username);
        
                }
        
        
            
            }    
        
         }
         
            
        

         if($totalErrormsg!=""){
                echo '<div class="nNote nFailure hideit" style="display:block">
                        <p><strong>FAILURE: </strong>'.$totalErrormsg.'</p>
                      </div>';
            }else{
                 echo '<div class="nNote nSuccess hideit" style="display:block">
                        <p><strong>Success: </strong> Company is Successfully update</p>
                      </div>';
         }
        
    }
    function addClient($cid,$uid){
        
        $table_name = $this->wpdb->prefix . "pbx_company_clients";
        
        $this->wpdb->insert( $table_name, 
        	array( 
        		'cid' => $cid, 
        		'uid' => $uid 
        	), 
        	array( 
        		'%d','%d'
        	) 
        );
        
    }
    
    function getCompanyList(){
        
        $currentRole=  $this->current_user->roles[0];
        $table_name = $this->wpdb->prefix . "pbx_company";
        $user_id= $this->current_user->id;
        $Qstr="";
        if($currentRole=="editor")
            $Qstr=" where author=$user_id";
        $sql="Select *from $table_name $Qstr";
        
        return $this->wpdb->get_results($sql);    
    }
    
    
}  
?>