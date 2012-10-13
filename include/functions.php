<?php

/*  function list for wp-plugin 
if( !function_exists('WpText2Ad_get_numof_records_bysql') ):
function WpText2Ad_get_numof_records_bysql($sql){
    global $wpdb;
	$resultset = $wpdb->get_results($sql);
	return count( $resultset );
}
endif;

if( !function_exists('WpText2Ad_get_numof_records') ):
function WpText2Ad_get_numof_records($tbl_name='', $condition=''){
	global $wpdb;
	
	if( $tbl_name=='' ) return -1;
	
	$sql = "SELECT * FROM ". $wpdb->prefix . $tbl_name;
	if( $condition!='' )
		$sql .= ' WHERE '.$condition;
		
	$resultset = $wpdb->get_results($sql);
	return count( $resultset );
}
endif;
*/


/*----------------------------------------------------------------------------------------------------
---------------------I N S T A L L A T I O N     F U N C T I O N  (  D A T A B A S E  ) -------------
----------------------------------------------------------------------------------------------------*/

if( !function_exists('sfy_get_numof_records_bysql') ):
function sfy_get_numof_records_bysql($sql){
    global $wpdb;
	$resultset = $wpdb->get_results($sql);
	return count( $resultset );
}
endif;
function PBX_db_install(){
	echo "DFDF"; die;
    // install the database table 
    
    $project = new project();
    $project->createTable();
    
    $task= new task();
    $task->createTable();
    
    $comment= new comment();
    $comment->createTable();
    
    $file = new file();
    $file->createTable();
    
    $activity=new activity();
    $activity->createTable();
    
   
    echo "DFD";die;
    // add projectBasix Page
    
    $page = get_page_by_title( 'ProjectBasix' );
    if(empty($page)){
        
        // Create page object
        
        $my_post = array(
        'post_title' => 'ProjectBasix', 
        'post_status' => 'publish',
        'post_type' => "page"
        );
        
        // Insert the post into the database
        $projectBasixID = wp_insert_post( $my_post );
        
        add_option("projectBasix_page",$projectBasixID);
    }
    
    
    // make directory  
    
    wp_mkdir_p( ABSPATH . 'wp-content/uploads/' );
    wp_mkdir_p( ABSPATH . 'wp-content/uploads/projectbasix/' );
    
 
}


// get 2 days diff 

function getTwoDaysDiff($date1,$date2){ //echo $date1;die;
        
    $diff = abs(strtotime($date2) - strtotime($date1));
    $years = floor($diff / (365*60*60*24));
    $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
    
    $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
    
    if($days)
        return " $days day ago";    
}

?>