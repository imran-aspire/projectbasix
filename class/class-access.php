<?php

/**
 * access CLASS 
 *
 * @link http://www.mdimran.net/
 * @author Imran <imran.aspire@gmail.com>
 *
 */

class access{
    
    private $wpdb;
   
    public function access(){
         global $wpdb;
         $this->wpdb=$wpdb;
         
    }
    public function chkAccess($module){
         global $current_user;
         get_currentuserinfo();
         $currentRole=  $current_user->roles[0];
         
         if($module=="manager"){
                     if($currentRole!="administrator"){
                          echo '<div class="nNote nFailure hideit" style="display:block">
                                <p><strong>FAILURE: </strong>Access Denied</p>
                              </div>';
                        exit;
                     }
                     
            
         }
         if($module=="contact"){
                     if($currentRole=="subscriber"){
                          echo '<div class="nNote nFailure hideit" style="display:block">
                                <p><strong>FAILURE: </strong>Access Denied</p>
                              </div>';
                        exit;
                     }
                     
            
         }
         //
    }
    
    public function accesIsDenied(){
        echo '<div class="nNote nFailure hideit" style="display:block">
                                <p><strong>FAILURE: </strong>Access Denied</p>
                              </div>';
                 exit;
    }
    
   
}  
?>