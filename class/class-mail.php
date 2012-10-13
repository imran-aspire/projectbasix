<?php

/**
 * mail CLASS 
 *
 * @link http://www.mdimran.net/
 * @author Imran <imran.aspire@gmail.com>
 *
 */

class mail{
    
    private $wpdb;
   
    public function mail(){
         global $wpdb;
         $this->wpdb=$wpdb;
         
    }
    
    /**
    * send mail for new project creation 
    * 
    * @return bool 'true' | false(if failed)
    */
    public function newProjectEmail($user_id,$userType){
        
        
    
    }  

}  
?>