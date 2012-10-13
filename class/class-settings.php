<?php

/**
 * user CLASS 
 *
 * @link http://www.mdimran.net/
 * @author Imran <imran.aspire@gmail.com>
 *
 */

class settings{
    
    private $wpdb;
    private $current_user;
    private $error;
   
    public function settings(){
         global $wpdb,$current_user;
         $this->wpdb=$wpdb;
         get_current_user();
         $this->current_user=$current_user;
         $this->error="";
         
    }
    
    public function showUserSettingsForm(){
        
        $user_id=$this->current_user->ID ;
        if($_GET['id']!="")
        $user_id=$_GET['id'];
        
        
        if($_POST['update']=="update")
            $update_result=$this->updateSettings($_POST,$user_id);
        
      
        if(!empty( $this->error)){
            echo '<div class="nNote nWarning hideit"  style="display:block">
                    <p>'.$this->error.'</p>
                  </div>';
        }
        if($update_result){ 
            echo '<div class="nNote nSuccess hideit" style="display:block">
                <p><strong>SUCCESS: </strong>User Profile Update Successfully</p>
            </div>';
        }
        
        
        $profileuser = $this->get_user_to_edit($user_id ); 
    ?>
         <style type="text/css">
            .regular-text{
                width:  100% !important;
            }
            .rowElem > label{
                padding: 0px;
                position: relative;
                top:12px
            }
        </style>
            <form class="mainForm" method="post" action="">
             <fieldset>
                <div class="widget first">
                    <?php //wp_nonce_field( 'update-user_' . $current_user->ID ) ?>
                    
                    
                    
                    <div class="head"><h5 class="iList">Name</h5></div>
                        <div class="rowElem noborder">
                            <label>Username</label>
                            <div class="formRight">
                                <input type="text" name="user_login" id="user_login" value="<?php echo esc_attr( $profileuser->user_login ); ?>" disabled="disabled" /> 
                                <span class="description">Your username cannot be changed.</span>
                            </div>
                            <div class="fix"></div>
                        </div>
                        <div class="rowElem">
                            <label>First Name:</label>
                            <div class="formRight">
                                <input type="text" name="first_name" id="first_name" value="<?php echo esc_attr( $profileuser->first_name ) ?>" class="regular-text" />
                            </div>
                            <div class="fix"></div>
                                                        
                        </div>
                        <div class="rowElem">
                            <label>Last Name:</label>
                            <div class="formRight">
                                <input type="text" name="last_name" id="last_name" value="<?php echo esc_attr( $profileuser->last_name ) ?>" class="regular-text" />
                            
                            </div>
                            <div class="fix"></div>
                                                        
                        </div>
                        
                        
                        <div class="rowElem">
                            <label>Display name publicly as</label>
                            <div class="formRight">
                                <select name="display_name" id="display_name">
                				<?php
                					$public_display = array();
                					$public_display['display_nickname']  = $profileuser->nickname;
                					$public_display['display_username']  = $profileuser->user_login;
                					if ( !empty( $profileuser->first_name ) )
                						$public_display['display_firstname'] = $profileuser->first_name;
                					if ( !empty( $profileuser->last_name ) )
                						$public_display['display_lastname'] = $profileuser->last_name;
                					if ( !empty( $profileuser->first_name ) && !empty( $profileuser->last_name ) ) {
                						$public_display['display_firstlast'] = $profileuser->first_name . ' ' . $profileuser->last_name;
                						$public_display['display_lastfirst'] = $profileuser->last_name . ' ' . $profileuser->first_name;
                					}
                					if ( !in_array( $profileuser->display_name, $public_display ) )// Only add this if it isn't duplicated elsewhere
                						$public_display = array( 'display_displayname' => $profileuser->display_name ) + $public_display;
                					$public_display = array_map( 'trim', $public_display );
                					foreach ( $public_display as $id => $item ) {
                						$selected = ( $profileuser->display_name == $item ) ? ' selected="selected"' : '';
                				?>
                						<option id="<?php echo $id; ?>" value="<?php echo esc_attr( $item ); ?>"<?php echo $selected; ?>><?php echo $item; ?></option>
                				<?php } ?>
                				</select>
                            </div>
                            <div class="fix"></div>
                                                        
                        </div>
                        
                        <div class="head"><h5 class="iList">Contact Info</h5></div>
                        
                        
                        <div class="rowElem">
                            <label>Email:</label>
                            <div class="formRight">
                       			<input type="text" name="email" id="email" value="<?php echo esc_attr( $profileuser->user_email ) ?>" class="regular-text" />
                            </div>
                            <div class="fix"></div>
                                                        
                        </div>
                        <div class="rowElem">
                            <label>Website :</label>
                            <div class="formRight">
                            	<input type="text" name="url" id="url" value="<?php echo esc_attr( $profileuser->user_url ) ?>" class="regular-text code" />
    
                            </div>
                            <div class="fix"></div>
                                                        
                        </div>
                        
                        <?php if ( function_exists( '_wp_get_user_contactmethods' ) ) :
                			foreach ( _wp_get_user_contactmethods() as $name => $desc ) {
                		?>
                            <div class="rowElem">
                                <label><?php echo $name; ?></label>
                                <div class="formRight">
                                <input type="text" name="<?php echo $name; ?>" id="<?php echo $name; ?>" value="<?php echo esc_attr( $profileuser->$name ) ?>" class="regular-text" />
                                </div>
                                <div class="fix"></div>
                                                            
                            </div>
                		<?php
                			}
                            endif;
                        ?>
                        <div class="head"><h5 class="iList">About yourself</h5></div>
                        
                        <div class="rowElem">
                            <label>Biographical Info</label>
                            <div class="formRight">
                            <textarea name="description" id="description" rows="5" cols="30"><?php echo esc_html( $profileuser->description ); ?></textarea><br />
    			             <span class="description"><?php _e( 'Share a little biographical information to fill out your profile. This may be shown publicly.', 'theme-my-login' ); ?></span>
                            </div>
                            <div class="fix"></div>
                                                        
                        </div>
                        
                        <div class="rowElem">
                            <?php
                    		$show_password_fields = apply_filters( 'show_password_fields', true, $profileuser );
                    		if ( $show_password_fields ) :
                    		?>
                            <label>New Password</label>
                            <div class="formRight">
                            <input type="password" name="pass1" id="pass1" size="16" value="" autocomplete="off" /> 
                            <br />
                            <span class="description"><?php _e( 'If you would like to change the password type a new one. Otherwise leave this blank.', 'theme-my-login' ); ?></span><br />
    				        <input type="password" name="pass2" id="pass2" size="16" value="" autocomplete="off" />
                            <br /> 
                            <span class="description"><?php _e( 'Type your new password again.', 'theme-my-login' ); ?></span><br />
    				        <div id="pass-strength-result"><?php _e( 'Strength indicator', 'theme-my-login' ); ?>
                            </div>
                            <div class="fix"></div>
                                                        
                            </div>
                            <?php endif; ?>
                            <input type="hidden" name="user_id" id="user_id" value="<?php echo esc_attr( $current_user->ID ); ?>" />
    			          
                            
                        </div>
                        <div class="fix"></div>
                        <input type="submit" value="update" name="update" class="greyishBtn submitForm" />
                        <div class="fix" ></div>
                        
                 </div>       
            </fieldset>
           </form>  
    <?php    
    }
    
    function updateSettings($data,$user_id){
        
        //$user_id=$this->current_user->ID;
      //echo "DFD";
       
    	global $wp_roles, $wpdb;
    	$user = new stdClass;
    	if ( $user_id ) {
    		$update = true;
    		$user->ID = (int) $user_id;
    		$userdata = get_userdata( $user_id );
    		$user->user_login = $wpdb->escape( $userdata->user_login );
    	} else {
    		$update = false;
    	}
    
    	if ( !$update && isset( $_POST['user_login'] ) )
    		$user->user_login = sanitize_user($_POST['user_login'], true);
    
    	$pass1 = $pass2 = '';
    	if ( isset( $_POST['pass1'] ))
    		$pass1 = $_POST['pass1'];
    	if ( isset( $_POST['pass2'] ))
    		$pass2 = $_POST['pass2'];
    
    	if ( isset( $_POST['role'] ) && current_user_can( 'edit_users' ) ) {
    		$new_role = sanitize_text_field( $_POST['role'] );
    		$potential_role = isset($wp_roles->role_objects[$new_role]) ? $wp_roles->role_objects[$new_role] : false;
    		// Don't let anyone with 'edit_users' (admins) edit their own role to something without it.
    		// Multisite super admins can freely edit their blog roles -- they possess all caps.
    		if ( ( is_multisite() && current_user_can( 'manage_sites' ) ) || $user_id != get_current_user_id() || ($potential_role && $potential_role->has_cap( 'edit_users' ) ) )
    			$user->role = $new_role;
    
    		// If the new role isn't editable by the logged-in user die with error
    		$editable_roles = get_editable_roles();
    		if ( ! empty( $new_role ) && empty( $editable_roles[$new_role] ) )
    			wp_die(__('You can&#8217;t give users that role.'));
    	}
    
    	if ( isset( $_POST['email'] ))
    		$user->user_email = sanitize_text_field( $_POST['email'] );
    	if ( isset( $_POST['url'] ) ) {
    		if ( empty ( $_POST['url'] ) || $_POST['url'] == 'http://' ) {
    			$user->user_url = '';
    		} else {
    			$user->user_url = esc_url_raw( $_POST['url'] );
    			$user->user_url = preg_match('/^(https?|ftps?|mailto|news|irc|gopher|nntp|feed|telnet):/is', $user->user_url) ? $user->user_url : 'http://'.$user->user_url;
    		}
    	}
    	if ( isset( $_POST['first_name'] ) )
    		$user->first_name = sanitize_text_field( $_POST['first_name'] );
    	if ( isset( $_POST['last_name'] ) )
    		$user->last_name = sanitize_text_field( $_POST['last_name'] );
    	if ( isset( $_POST['nickname'] ) )
    		$user->nickname = sanitize_text_field( $_POST['nickname'] );
    	if ( isset( $_POST['display_name'] ) )
    		$user->display_name = sanitize_text_field( $_POST['display_name'] );
    
    	if ( isset( $_POST['description'] ) )
    		$user->description = trim( $_POST['description'] );
    
    	foreach ( _wp_get_user_contactmethods( $user ) as $method => $name ) {
    		if ( isset( $_POST[$method] ))
    			$user->$method = sanitize_text_field( $_POST[$method] );
    	}
    
    	if ( $update ) {
    		$user->rich_editing = isset( $_POST['rich_editing'] ) && 'false' == $_POST['rich_editing'] ? 'false' : 'true';
    		$user->admin_color = isset( $_POST['admin_color'] ) ? sanitize_text_field( $_POST['admin_color'] ) : 'fresh';
    		$user->show_admin_bar_front = isset( $_POST['admin_bar_front'] ) ? 'true' : 'false';
    		$user->show_admin_bar_admin = isset( $_POST['admin_bar_admin'] ) ? 'true' : 'false';
    	}
    
    	$user->comment_shortcuts = isset( $_POST['comment_shortcuts'] ) && 'true' == $_POST['comment_shortcuts'] ? 'true' : '';
    
    	$user->use_ssl = 0;
    	if ( !empty($_POST['use_ssl']) )
    		$user->use_ssl = 1;
    
    	$errors = new WP_Error();
    
    	/* checking that username has been typed */
    	if ( $user->user_login == '' )
    		$errors->add( 'user_login', __( '<strong>ERROR</strong>: Please enter a username.' ));
            
       
    	/* checking the password has been typed twice */
    	do_action_ref_array( 'check_passwords', array ( $user->user_login, & $pass1, & $pass2 ));
    
    	if ( $update ) {
    		if ( empty($pass1) && !empty($pass2) ){
    			$this->error = '<strong>ERROR</strong>: You entered your new password only once.' ;
                return;
            }    
    		elseif ( !empty($pass1) && empty($pass2) ){
    			$this->error = '<strong>ERROR</strong>: You entered your new password only once.';
                return;
            }    
    	} else {
    		if ( empty($pass1) ){
    			$this->error = '<strong>ERROR</strong>: Please enter your password.' ;
                return;
         }   
    		elseif ( empty($pass2) ){
    			$this->error = '<strong>ERROR</strong>: Please enter your password twice.';
                return ;
            }   
    	}
    
    	/* Check for "\" in password */
    	if ( false !== strpos( stripslashes($pass1), "\\" ) ){
    		$this->error =  '<strong>ERROR</strong>: Passwords may not contain the character "\\".' ;
            return;
        }
    
    	/* checking the password has been typed twice the same */
    	if ( $pass1 != $pass2 ){
    		$this->error = '<strong>ERROR</strong>: Please enter the same password in the two password fields.' ;
            return;
        }
    
    	if ( !empty( $pass1 ) )
    		$user->user_pass = $pass1;
    
    	if ( !$update && isset( $_POST['user_login'] ) && !validate_username( $_POST['user_login'] ) ){
    		$this->error =  '<strong>ERROR</strong>: This username is invalid because it uses illegal characters. Please enter a valid username.';
            return ;
        }
    
    	if ( !$update && username_exists( $user->user_login ) ){
    		$this->error =  '<strong>ERROR</strong>: This username is already registered. Please choose another one.';
            return;
        }    
    
    	/* checking e-mail address */
    	if ( empty( $user->user_email ) ) {
    		$this->error = '<strong>ERROR</strong>: Please enter an e-mail address.' ;
            return;
           
    	} elseif ( !is_email( $user->user_email ) ) {
    		$this->error = '<strong>ERROR</strong>: The e-mail address isn&#8217;t correct.' ;
            return;
    	} elseif ( ( $owner_id = email_exists($user->user_email) ) && ( !$update || ( $owner_id != $user->ID ) ) ) {
    		$this->error = '<strong>ERROR</strong>: This email is already registered, please choose another one.';
            return;
    	}
    
    	// Allow plugins to return their own errors.
    	//do_action_ref_array('user_profile_update_errors', array ( &$errors, $update, &$user ) );
    
    	if ( $errors->get_error_codes() )
    		return $errors;
        

    	if ( $update ) {
    		$user_id = wp_update_user( get_object_vars( $user ) );
    	} else {
    		$user_id = wp_insert_user( get_object_vars( $user ) );
    		wp_new_user_notification( $user_id, isset($_POST['send_password']) ? $pass1 : '' );
    	}
      
        return true;
    
    }
    
    function get_user_to_edit( $user_id ) {
    	$user = new WP_User( $user_id );
    
    	$user_contactmethods = _wp_get_user_contactmethods( $user );
    	foreach ($user_contactmethods as $method => $name) {
    		if ( empty( $user->{$method} ) )
    			$user->{$method} = '';
    	}
    
    	if ( empty($user->description) )
    		$user->description = '';
    
    	$user = sanitize_user_object($user, 'edit');
    
    	return $user;
    }
    /**
    * get all user list 
    *
    * 
    * 
    */
    public function settings2(){
        $blogusers = get_users_of_blog();

        
        $tableName=$this->wpdb->usermeta; 
        echo "SELECT * $tableName from WHERE meta_key = 'wp_capabilities' AND meta_value = 'a:1:{s:10:\"subscriber\";b:1;}'";die;
        return $this->wpdb->get_results("SELECT * $tableName from WHERE meta_key = 'wp_capabilities' AND meta_value = 'a:1:{s:10:\"subscriber\";b:1;}'");
	    
        /*
        foreach ( (array) $author_subscriper as $author ) {
		$author    = get_userdata( $author->user_id );
		$userlevel = $author->wp2_user_level;
		$name      = $author->nickname;
		if ( $show_fullname && ($author->first_name != '' && $author->last_name != '') ) {
			$name = "$author->first_name $author->last_name";
		}
		$link = '<li>' . $name . '</li>';
		echo $link;
        
        
        
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
                PRIMARY KEY   (id)
                
            );";
            $results = $this->wpdb->query( $sql );
        }
       
    */
    }  
   function getUserLoginName($author){
       $user = get_userdata($author);
       return $user->user_login;
   }
   function getUserEmail($author){
       $user = get_userdata($author);
       return $user->user_email;
   }
    function getProjectUser($pid){
        $usertable = $this->wpdb->prefix . "users";
        $user_table_name = $this->wpdb->prefix . "pbx_project_user";
        
        $sql= "select 
                    $usertable.ID,
                    $usertable.user_login,
                    $usertable.user_email
                     
                from $usertable,$user_table_name 
                where $user_table_name.author=$usertable.ID and $user_table_name.pid=$pid";
        return $this->wpdb->get_results($sql);        
    }
    
}  
?>