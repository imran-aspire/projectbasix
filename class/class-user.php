<?php

/**
 * user CLASS 
 *
 * @link http://www.mdimran.net/
 * @author Imran <imran.aspire@gmail.com>
 *
 */

class user{
    
    private $wpdb;
   
    public function user(){
         global $wpdb;
         $this->wpdb=$wpdb;
         
    }
    
    /**
    * get all user list 
    *
    * 
    * 
    */
    public function getAllUserList(){
        $blogusers = get_users_of_blog();
        print_r($blogusers);
        
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