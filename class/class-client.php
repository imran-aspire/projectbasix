<?php

/**
 * client CLASS 
 *
 * @link http://www.mdimran.net/
 * @author Imran <imran.aspire@gmail.com>
 *
 */

class client{
    
    private $wpdb;
   
    public function client(){
         global $wpdb;
         $this->wpdb=$wpdb;
         
    }
    
    
    /**
     *  create Table
     * */
     
     
    public function createTable(){
      
        $table_name = $this->wpdb->prefix . "pbx_client";
        // status :   add in contact list  => 1 , add in wp  = > 2 
       
        if($this->wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $sql = "CREATE TABLE " . $table_name . " (
            	id          bigint(11) NOT NULL AUTO_INCREMENT,
                user_id          bigint(11),
                first_name       varchar(555) NOT NULL,
            	last_name       varchar(555) NOT NULL,
            	user_email       varchar(555) NOT NULL,
            	user_url       varchar(555) NOT NULL,
            	cell       varchar(555) NOT NULL,
            	office_cell       varchar(555) NOT NULL,
            	company       varchar(555) NOT NULL,
            	
                status       tinyint DEFAULT '1',
                PRIMARY KEY   (id)
                
            );";
          
            $results = $this->wpdb->query( $sql );
        }
       
    
    }  
  
    function getValidClientList(){
         $table_name = $this->wpdb->prefix . "pbx_client"; 
         $sql="Select *from $table_name where user_email!='' and status=1";
         return $this->wpdb->get_results($sql);   
    }
    
    function getClientDetail($id){
         $table_name = $this->wpdb->prefix . "pbx_client"; 
         $sql="Select *from $table_name where id=$id";
         return $this->wpdb->get_row($sql);   
    }
    function updateClientStatus($id,$user_id){
         $table_name = $this->wpdb->prefix . "pbx_client"; 
         $sql="update  $table_name set status=2,user_id=$user_id where id=$id";
         $this->wpdb->query($sql);   
         //echo $sql;die;
         
    }
    
    function deleteClient($uid){
        
        $clients_table = $this->wpdb->prefix . "pbx_company_clients"; 
        
        $sql="delete from $clients_table where uid=$uid";
        $result = $this->wpdb->query($sql);
        if($result){
            $table_name = $this->wpdb->prefix . "pbx_client"; 
            $sql="update  $table_name set status=1 where user_id=$uid";
            $this->wpdb->query($sql);
            return 1;
                  
        }
        
             
    }
    function getAllProjectClient($pid){
        
        $clients_table = $this->wpdb->prefix . "pbx_company_clients"; 
        $project_table = $this->wpdb->prefix . "pbx_project";
        
        $usertable = $this->wpdb->prefix . "users";
       
        
        $sql= "select 
                    $usertable.ID,
                    $usertable.user_login,
                    $usertable.user_email
                     
                from $usertable,$project_table,$clients_table 
                where $clients_table.cid=$project_table.cid  and $project_table.id=$pid and $usertable.ID=$clients_table.uid";
        return $this->wpdb->get_results($sql);  
        
        
        
        
    }
    
    
    function getAllClient(){
        
        global $current_user;
        get_currentuserinfo();
        
        $company_table = $this->wpdb->prefix . "pbx_company"; 
        $company_clients_table = $this->wpdb->prefix . "pbx_company_clients"; 
        $clients_table = $this->wpdb->prefix . "pbx_client";
        $usertable = $this->wpdb->prefix . "users";
        
        
        $currentRole=  $current_user->roles[0];
        $user_id= $current_user->id;
        $qSql="";
        
        if($currentRole=="editor"){
            
             $qSql = "and $clients_table.user_id in(
                            select $company_clients_table.uid 
                            from  $company_table,
                            $company_clients_table 
                            where  $company_table.id = $company_clients_table.cid 
                            and $company_table.author=$user_id )";
                            
        }
           
        $sql= "select 
                    $usertable.ID,
                    $usertable.user_login
                    
                from $usertable,
                    $clients_table 
                where $clients_table.user_id=$usertable.ID  
                and $clients_table.status =2 
                and $clients_table.user_id !=0 $qSql";
                
                    
        return $this->wpdb->get_results($sql);  
        
    }
}  
?>