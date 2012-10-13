<?php

/**
 * Activity CLASS 
 *
 * @link http://www.mdimran.net/
 * @author Imran <imran.aspire@gmail.com>
 *
 */

class activity{
    
    private $wpdb;
   
    public function activity(){
         global $wpdb;
         $this->wpdb=$wpdb;
         
    }
    
    /**
    * Create activity table
    *
    * 
    * @return bool 'true' | false(if failed)
    */
    public function createTable(){
        
        
        
        $table_name = $this->wpdb->prefix . "pbx_activity";
        // type :   task => 1 , files = > 2  
       
        if($this->wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $sql = "CREATE TABLE " . $table_name . " (
            	id          bigint(11) NOT NULL AUTO_INCREMENT,
              	pid          bigint(11),
            	
                status       TEXT,
                create_date    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
                
                PRIMARY KEY   (id)
                
            );";
            $results = $this->wpdb->query( $sql );
        }
       
    
    }
    
    /**
     *  create activaty 
     * */
    function createActivaty($pid,$status){
        
        $table_name = $this->wpdb->prefix . "pbx_activity";
        
        // insert activity
        $this->wpdb->insert( $table_name, 
            array( 
            	'pid' => $pid, 
            	'status' => $status 
            ), 
            array( 
            	'%d','%s'
            ) 
        );
        
        // insert project id 
        $pid=$this->wpdb->insert_id;
    }
    
    /**
     *  get the project activity 
     * */
    function getProjectSummary($pid){
        $table_name = $this->wpdb->prefix . "pbx_activity";
        $sql="select * from $table_name where pid=$pid order by create_date desc";
        $result=$this->wpdb->get_results($sql);
        
        $summaryStr='<div class="head">
                            <h5 class="iImageList">Activity Stream</h5>
                            <a class="closeSummery" rel="'.$row->id.'" href="#"><img src="'.PBX_WP_PLUGIN_URL.'/projectbasix/library/html/images/close.png" /></a>
                    </div>
                    <div class="body">
                        <div class="list plusBlue">';
        if($result){
                $summaryStr .="<ul>";
            foreach($result as $row){
                
                $datetime = new DateTime($row->create_date);
                $create_date = $datetime->format('Y-m-d'); 
                
                $summaryStr .="<li><strong>".getTwoDaysDiff($create_date,date("Y-m-d",time()))." </strong> : $row->status</li>";
            }
                $summaryStr .="</ul>";

            $summaryStr .="</div></div>";
            return $summaryStr;
        }
        
       
    }     

}  
?>