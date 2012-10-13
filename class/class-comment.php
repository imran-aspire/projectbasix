<?php

/**
 * Comment CLASS 
 *
 * @link http://www.mdimran.net/
 * @author Imran <imran.aspire@gmail.com>
 *
 */

class comment{
    
    private $wpdb;
    private $current_user;
   
    public function comment(){
         global $wpdb,$current_user;
         $this->wpdb=$wpdb;
         get_current_user();
         $this->current_user=$current_user;
         
    }
    
    /**
    * Create comment table
    *
    * 
    * @return bool 'true' | false(if failed)
    */
    public function createTable(){
        
        
        
        $table_name = $this->wpdb->prefix . "pbx_comment";
        // type :   task => 1 , files = > 2  
       
        if($this->wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $sql = "CREATE TABLE " . $table_name . " (
            	id          bigint(11) NOT NULL AUTO_INCREMENT,
              	author          bigint(11),
            	
                comment       TEXT,
                type       tinyint,
                type_value  bigint(11),
                create_date    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
                
                PRIMARY KEY   (id)
                
            );";
            $results = $this->wpdb->query( $sql );
        }
       
    
    }  
    function addComment($comment,$type,$value){
        $current_user;
        
        $table_name = $this->wpdb->prefix . "pbx_comment";
        
        $pid = $this->current_user->id;
        // insert project 
        return $this->wpdb->insert( $table_name, 
        	array( 
        		'author' => $pid, 
        		'comment' => $comment,
        		'type' => $type ,
                'type_value' => $value
        	), 
        	array( 
        		'%d','%s','%d','%d' 
        	) 
        );
        
        
    }
    function showTaskComments($tid){
        $commenttable = $this->wpdb->prefix . "pbx_comment";
        $usertable = $this->wpdb->prefix . "users";
        
        $sql="Select $usertable.user_login,comment,create_date 
                from $commenttable,$usertable where $commenttable.author=$usertable.id and type=1 and type_value=$tid";
        $commentResult=$this->wpdb->get_results($sql);
        //return $sql;
        $CommentStr = "";
        
        if($commentResult){
             $CommentStr='<div class="widget"  style="width:665px;margin-left:3px">
                    <div class="head">
                        <a class="clickFormBox" rel="CommentsBox" href="javascript:void(0);">
                            <h5 class="iList"> Comments</h5>
                        </a>
                    </div>
                    <div id="CommentsBox">
                        <table width="100%" cellspacing="0" cellpadding="0" class="tableStatic">
                            <thead>
                                <tr>
                                  <td width="21%">Author</td>
                                  <td>Comments</td>
                                  <td width="21%">Date</td>
                                </tr>
                            </thead>
                            <tbody>';
            foreach($commentResult as $row){
                            $datetime = new DateTime($row->create_date);
                            $create_date= $datetime->format('Y-m-d'); 
                $CommentStr .='  <tr>
                                    <td align="center">'.$row->user_login.'</td>
                                    <td>'.$row->comment.'</td>
                                    <td><span>'.$create_date.'</span></td>
                                </tr>';
            }
            $CommentStr .='</tbody>
                        </table>
                     </div>                       
                </div>';    
        }
        
       
                              
                                
                            
       return $CommentStr;    
    }
}  
?>