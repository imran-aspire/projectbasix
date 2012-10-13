<?php

/**
 * login CLASS 
 *
 * @link http://www.mdimran.net/
 * @author Imran <imran.aspire@gmail.com>
 *
 */

class login{
    
    private $wpdb;
   
    public function login(){
         global $wpdb;
         $this->wpdb=$wpdb;
         
    }
    
    public function index(){
        $projectBasix = new projectbasix();
        $projectBasix->header();
   ?>
   <!-- Top navigation bar -->
<div id="topNav">
    <div class="fixed">
        <div class="wrapper">
            <div class="backTo"><a href="#" title=""><img src="images/icons/topnav/mainWebsite.png" alt="" /><span>Main website</span></a></div>
            <div class="userNav">
                <ul>
                    <li><a href="#" title=""><img src="images/icons/topnav/register.png" alt="" /><span>Register</span></a></li>
                    <li><a href="#" title=""><img src="images/icons/topnav/contactAdmin.png" alt="" /><span>Contact admin</span></a></li>
                    <li><a href="#" title=""><img src="images/icons/topnav/help.png" alt="" /><span>Help</span></a></li>
                </ul>
            </div>
            <div class="fix"></div>
        </div>
    </div>
</div>

<!-- Login form area -->
<div class="loginWrapper">
	<div class="loginLogo"><img src="images/loginLogo.png" alt="" /></div>
    <div class="loginPanel">
        <div class="head"><h5 class="iUser">Login</h5></div>
        
            <?php 
            global $error;
            print_r($error);
             $args = array(
        'echo' => true,
        'redirect' => "http://localhost/home/larry/projectBasix/projectbasix/", 
        'form_id' => 'loginform',
        'label_username' => __( 'Username' ),
        'label_password' => __( 'Password' ),
        'label_remember' => __( 'Remember Me' ),
        'label_log_in' => __( 'Log In' ),
        'id_username' => 'user_login',
        'id_password' => 'user_pass',
        'id_remember' => 'rememberme',
        'id_submit' => 'wp-submit',
        'remember' => true,
        'value_username' => NULL,
        'value_remember' => false );  
        wp_login_form($args);
        
            /*
            <fieldset>
                <div class="loginRow noborder">
                    <label for="req1">Username:</label>
                    <div class="loginInput"><input type="text" name="login" class="validate[required]" id="req1" /></div>
                    <div class="fix"></div>
                </div>
                
                <div class="loginRow">
                    <label for="req2">Password:</label>
                    <div class="loginInput"><input type="password" name="password" class="validate[required]" id="req2" /></div>
                    <div class="fix"></div>
                </div>
                
                <div class="loginRow">
                    <div class="rememberMe"><input type="checkbox" id="check2" name="chbox" /><label>Remember me</label></div>
                    <input type="submit" value="Log me in" class="greyishBtn submitForm" />
                    <div class="fix"></div>
                </div>
            </fieldset>
            */?>
        
    </div>
</div>
   <?php     
        $projectBasix->footer();
    ?>
   
    <?php    
    }
   
}  
?>