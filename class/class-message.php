<?php

/**
 * message CLASS 
 *
 * @link http://www.mdimran.net/
 * @author Imran <imran.aspire@gmail.com>
 *
 */

class message{
    
    private $wpdb,$condition,$current_user;
   
    public function message(){
         global $wpdb,$current_user;
         get_currentuserinfo();
         $this->current_user=$current_user;
         $this->wpdb=$wpdb;
         $this->condition =false;
         
         
    }
    function condition(){
        if($_GET['action']=="showMessage"){
            $this->showMessage();
            $this->condition=true;
            
        }
       
        
    }
    public function index(){
        $table_name = $this->wpdb->prefix . "pbx_notification";
        $this->condition();
        
        $currentRole=  $this->current_user->roles[0];
        $user_id=  $this->current_user->ID;
         
        
        $projectbasix_link=get_permalink(get_option("projectBasix_page"));
        if(get_option('permalink_structure')=="")
            $pageLink=$projectbasix_link."&page=";
        else 
            $pageLink=$projectbasix_link."?page=";  
            
        
        //$clientsList=$this->getClientsList();
        $page="message";
        $per_page=20;
        
        $sql ="Select *from $table_name where author=$user_id ";
        
        
    	$pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 0;
      
    	if ( empty($pagenum) ) $pagenum = 1;
    	if( ! isset( $per_page ) || $per_page < 0 ) $per_page = 10;
    	$num_pages = ceil( sfy_get_numof_records_bysql($sql) / $per_page);
    	
        //$tab=isset( $_GET['tab'] ) ? "" : "&tab=2";
        $app_pagin = paginate_links(array(
    		'base' => $projectbasix_link.'%_%',
    		'format' => '?pagenum=%#%',
    		'prev_text' => __('&laquo;'),
    		'next_text' => __('&raquo;'),
    		'total' => $num_pages,
            'end_size'=>5,
            'mid_size'=>5,
    		'current' => $pagenum,
            "add_args"=>array("page"=>$page)
    	));
        
        
        if( $pagenum > 0 ) $sql .= " order by id desc LIMIT ". (($pagenum-1)*$per_page) .", ". $per_page;
    	//getting results
    	$message_list = $this->wpdb->get_results($sql);
        
        if( isset($_GET['pagenum']) ) $pagenum_url='&amp;pagenum='.$_GET['pagenum'];
        else $pagenum_url = '';

        
       
        $paginationStr="";
        if($app_pagin){
            $paginationStr .='
            <div class="tablenav">
    				<div class="tablenav-pages">
    					<span class="displaying-num">
    						Displaying 
    						'.(( $pagenum - 1 ) * $per_page + 1).' - 
    						'.min( $pagenum * $per_page, sfy_get_numof_records_bysql($sql) ).' of 
    						'.sfy_get_numof_records_bysql($sql).'
    						'.$app_pagin.'
    					</span>
    				</div>
                    <div style="clear:both;"><!----></div>
  			</div>';
        }     
        
        // contact list
        $contact_listStr=""; 
        if($message_list){
            $contact_listStr .= '<ul>';
                
            foreach($message_list as $row ){
                        $datetime = new DateTime($row->create_date);
                        $create_date = $datetime->format('Y/m/d'); 
                        
                        $stasus="";
                        if($row->status==2)
                        $stasus="moreRead";
                        
                        $contact_listStr .=   "<li><a class='$stasus' href='".$pageLink . "message&amp;action=showMessage&amp;id=" . $row->id . "'>$row->title</a><br /><small>on $create_date</small></li>";
                
            }
            $contact_listStr .= '</ul>';
            
        }
     
     
     $currentRole=  $this->current_user->roles[0];
    
      if($this->condition==false){  
    ?>  
        <div class="widget" style="margin: 10px 0px !important;">
            <div class="head"><a href=""><h5 class="iMail">All Message</h5></a> <?php echo $paginationStr; ?> </div>
            <div class="body">
                <div class="list arrow2Green pt12">
                    <?php
                        echo $contact_listStr;
                    ?>
                </div>
            </div>
        </div>
          <?php echo $paginationStr; ?>                    
    
    <?php
        }    
    }
    
    public function showMessage(){
        $nid=$_GET['id'];
        $user_id=$this->current_user->ID;
        $table_name = $this->wpdb->prefix . "pbx_notification";
        $sql ="Select *from $table_name where author=$user_id and id=$nid";
        $result = $this->wpdb->get_row($sql);
        
        if($result->status==1){
            $this->wpdb->query("update   $table_name set status=2 where author=$user_id and id=$nid");
        }
        
        $datetime = new DateTime($row->create_date);
        $create_date = $datetime->format('Y/m/d'); 
    ?>
     <div class="widget messageBody" style="margin: 10px 0px !important;">
            <div class="head"><a href=""><h5 class="iMail">Message</h5></a> <strong><?php echo $create_date; ?></strong>   </div>
            <div class="body">
                <h4><?php echo $result->title; ?></h4>
                <hr />
                <?php echo $result->body; ?>
                
            </div>
     </div>
    <?php    
    }
    function getClientsList(){
        $table_name = $this->wpdb->prefix . "pbx_client";
        $sql ="Select *from $table_name ";
        return $this->wpdb->get_results($sql);
    }
}  
?>