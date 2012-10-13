<?php

/**
 * manager CLASS 
 *
 * @link http://www.mdimran.net/
 * @author Imran <imran.aspire@gmail.com>
 *
 */

class manager{
    
    private $wpdb;
   
    public function manager(){
         global $wpdb;
         $this->wpdb=$wpdb;
         
         $access = new access();
         $access->chkAccess("manager");
         
         
    }
    
    public function index(){
      //  print_r($_POST);
        $errormsg="";
        if(isset($_POST['update'])){
            
            $firstname = sanitize_text_field( $_POST['first_name'] );
            $lastname = sanitize_text_field( $_POST['last_name'] );
            $username = sanitize_text_field( $_POST['user_login'] );
            $email = sanitize_text_field( $_POST['user_email'] );
            //Add usernames we don't want used
            $invalid_usernames = array( 'admin' );
            //Do username validation
            
            $username = sanitize_user( $username );
            if ( !validate_username( $username ) || in_array( $username, $invalid_usernames ) ) {
                $errormsg = 'Username is invalid.';
            }
            if ( username_exists( $username ) ) {
                $errormsg = 'Username already exists.';
            }
            //Do e-mail address validation
            if ( !is_email( $email ) ) {
                $errormsg = 'E-mail address is invalid.';
            }
            if (email_exists($email)) {
                $errormsg=  'E-mail address is already in use.';
            }
            
            if($errormsg ==""){
              
              
                $user_pass = wp_generate_password();
                $user = array(
                    'user_login' => $username,
                    'user_pass' => $user_pass,
                    'first_name' => $firstname,
                    'last_name' => $lastname,
                    'user_email' => $email,
                    'role'=>"editor"
                    
                    );
                $user_id = wp_insert_user( $user );
                
                /*Send e-mail to admin and new user - 
                You could create your own e-mail instead of using this function*/
                wp_new_user_notification( $user_id, $user_pass );
            }
            
            
            
            if($errormsg!=""){
                echo '<div class="nNote nFailure hideit" style="display:block">
                        <p><strong>FAILURE: </strong>'.$errormsg.'</p>
                      </div>';
            }else{
                 echo '<div class="nNote nSuccess hideit" style="display:block">
                        <p><strong>Success: </strong> Manager is Successfully Added</p>
                      </div>';
            }
                        
        }
        
        $projectbasix_link=get_permalink(get_option("projectBasix_page"));
        if(get_option('permalink_structure')=="")
            $pageLink=$projectbasix_link."&page=";
        else 
            $pageLink=$projectbasix_link."?page=";  
            
        
        $managerList=$this->getManagerList();
        
    ?>
      <div class="widget first">
            <div class="head"><h5 class="iUsers">Manager list</h5></div>
            <div id="myList-nav"></div>
            <ul id="myList">
                <?php if($managerList){ ?>
                    <?php foreach($managerList as $row){ ?>
                        <li><a href="<?php echo $pageLink; ?>settings&id=<?php echo $row->ID?>"><?php echo $row->user_login;?></a>
                        	<ul class="listData">
                                <li><a href="javascript:void(0)" title=""><?php echo $row->user_email; ?></a></li>
                            	<li><span class="cNote"><?php echo $row->user_nicename; ?></span></li>
                            </ul>
                        </li>
                    <?php } ?>
                <?php } ?>
            
                
           </ul>
    </div>                    
    <form class="mainForm" method="post" action="">
    <fieldset>
    <div class="widget first">
        <div class="head"><h5 class="iList">Add Manager</h5></div>
            <div class="rowElem noborder">
                <label>Username</label>
                <div class="formRight">
                    <input type="text" name="user_login" id="user_login" value="<?php echo $_POST['user_login']; ?>" /> 
                    
                </div>
                <div class="fix"></div>
            </div>
             <div class="rowElem">
                <label>Email</label>
                <div class="formRight">
                    <input type="text" name="user_email" id="user_email" value="<?php echo $_POST['user_email']; ?>" class="regular-text" />
                </div>
                <div class="fix"></div>
                                            
            </div>
            <div class="rowElem">
                <label>First Name:</label>
                <div class="formRight">
                    <input type="text" name="first_name" id="first_name" value="<?php echo $_POST['first_name']; ?>" class="regular-text" />
                </div>
                <div class="fix"></div>
                                            
            </div>
            <div class="rowElem">
                <label>Last Name:</label>
                <div class="formRight">
                    <input type="text" name="last_name" id="last_name" value="<?php echo $_POST['last_name']; ?>" class="regular-text" />
                
                </div>
                <div class="fix"></div>
                                            
            </div>
            
            </div>
            
            <div class="fix"></div>
            <input type="submit" value="Add Manager" name="update" class="greyishBtn submitForm" />
            <div class="fix" ></div>
            
     </div>       
    </fieldset>
    </form> 
    <?php    
    }
    
    function getManagerList(){
         return get_users('blog_id=1&orderby=nicename&role=editor');
    }
}  
?>