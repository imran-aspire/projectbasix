<?php
/**
 *  PINGBACKLIST SCHEDULING CLASS FOR ADMIN
 *
 * @link http://www.mdimran.net/
 * @author Imran <imran.aspire@gmail.com>
 *
 */
 
 class pp_pingbackschedule{
    
     /**
     * Default Action of  pingback list scheduling 
     */
    public function index(){
        
        $this->condition();
        global $wpdb;
    	$per_page = get_option('pingbackschedule_perpage');
        $schedule_table=$wpdb->prefix."pingback_schedule";
        
        // check the codition
        
       
        
        
        $sql= "SELECT  *from $schedule_table ";
        
   
        $total_sql=$sql;
        
    	$pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 0;
    	if ( empty($pagenum) ) $pagenum = 1;
    	if( ! isset( $per_page ) || $per_page < 0 ) $per_page = 10;
    	$num_pages = ceil( pingpower_get_numof_records_bysql($sql) / $per_page);
    	
       
    	$app_pagin = paginate_links(array(
    		'base' => add_query_arg( 'pagenum', '%#%' ),
    		'format' => '',
    		'prev_text' => __('&laquo;'),
    		'next_text' => __('&raquo;'),
    		'total' => $num_pages,
    		'current' => $pagenum
    	));
    	
        
    	// Get all the promos from the database
    		
    	if(isset($_REQUEST['orderby'])) {
    		$sql .= " ORDER BY " . $_REQUEST['criteria'] . " " . $_REQUEST['order'];
    		$option_selected[$_REQUEST['criteria']] = " selected=\"selected\"";
    		$option_selected[$_REQUEST['order']] = " selected=\"selected\"";
    	}
    	else {
    		$sql .= " ORDER BY s_id ";
    		$option_selected['s_id'] = " selected=\"selected\"";
    		$option_selected['ASC'] = " selected=\"selected\"";
    	}
    	if( $pagenum > 0 ) $sql .= " LIMIT ". (($pagenum-1)*$per_page) .", ". $per_page;
    	
    	$wpdb->show_errors=true;
    	//getting results
    	$comment_list = $wpdb->get_results($sql);
    
       	if( isset($_GET['pagenum']) ) $pagenum_url='&amp;pagenum='.$_GET['pagenum'];
	       else $pagenum_url = '';
	
    	foreach($comment_list as $row) {
    		if($alternate) $alternate = "";
    		else $alternate = " class=\"alternate\"";
           
    		$elem_list .= "<tr{$alternate}>";
    		$elem_list .= "<th scope=\"row\" class=\"check-column\"><input type=\"checkbox\" name=\"bulkcheck[]\" value=\"" .  $row->s_id . "\" /></th>";
    		$elem_list .= "<td>". $row->s_id .'</td>';
            $elem_list .= "<td>". $row->frequency .'</td>';
            $elem_list .= "<td>". $row->email .'</td>';
            $elem_list .= "<td>". $row->number .'</td>';
            
        	$elem_list .= '<td style="text-align:center"><a href="'. $_SERVER['PHP_SELF'] . "?page=pp_pingbackschedule&amp;action=delete&amp;id=" . $row->s_id . '" onclick="return confirm(\'Are you sure you want to delete this Pingback Url ?\');" class="delete">Delete</a></td>';
    		$elem_list .= "</tr>";
    	}
    
?>
       
       	<div class="wrap">
		
		<?php if($msg): ?><div id="message" class="updated fade"><p><?php echo $msg; ?></p></div><?php endif; ?>
		
		<h2>Pinback Url Harvesting Scheduling</h2>
		
		<?php if($elem_list): ?>
		
		<p>Currently, you have <?php echo pingpower_get_numof_records_bysql($total_sql); ?> Harvesting Scheduling.</p>
		
		<form id="record_form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?page=pp_pingbackschedule">
			<div class="tablenav">
				<div class="alignleft actions">
					<input type="submit" name="bulkaction" value="Delete" onclick="return confirm('Are you sure you want to delete these Pingback Links  ?');" class="button-secondary" />
					&nbsp;&nbsp;Sort by:
					<select name="criteria">
						<option value="s_id"<?php echo $option_selected['s_id']; ?>>ID</option>
                         <option value="frequency"<?php echo $option_selected['frequency']; ?>>Frequency</option>
                        <option value="email"<?php echo $option_selected['email']; ?>>Email</option>
                        <option value="number"<?php echo $option_selected['number']; ?>>Number</option>
                    </select>
					<select name="order">
						<option value="ASC"<?php echo $option_selected['ASC']; ?>>ASC</option>
						<option value="DESC"<?php echo $option_selected['DESC']; ?>>DESC</option>
					</select>
					<input type="submit" name="orderby" value="Go" class="button-secondary" />
                    &nbsp;&nbsp;&nbsp; Schedule Per page <input style="width: 50px;" type="text" value="<?php echo get_option("pingbackschedule_perpage") ?>" name="pingbackschedule_perpage" />
                    <input type="submit" name="pingbackschedule_perpage_submit" value="update" />
                </div>
				<?php if($app_pagin): ?>
				<div class="tablenav-pages">
					<span class="displaying-num">
						Displaying 
						<?php echo ( $pagenum - 1 ) * $per_page + 1; ?> - 
						<?php echo min( $pagenum * $per_page, pingpower_get_numof_records_bysql($total_sql) ); ?> of 
						<?php echo pingpower_get_numof_records_bysql($total_sql); ?>
						<?php echo $app_pagin; ?>
					</span>
				</div>
				<?php endif; ?>
				<div style="clear:both;"><!----></div>
			</div>
				
				<table class="widefat">
					<thead><tr>
						<th class="check-column"><input type="checkbox" onclick="record_form_checkAll(document.getElementById('record_form'));" /></th>
						
                        <th>ID</th>
                        <th>Frequency</th>
                        <th>Email</th>
                        <th>Number</th>
                        <th colspan="1" style="text-align:center">Action</th>
                        
					</tr></thead>
					
					<tbody id="the-list"><?php echo $elem_list; ?></tbody>
				</table>
				
			<div class="tablenav">
				<div class="alignleft actions">
					<input type="submit" name="bulkaction" value="Delete" onclick="return confirm('Are you sure you want to delete these Pingback Links ?');" class="button-secondary" />
				</div>
				<?php if($app_pagin): ?>
				<div class="tablenav-pages">
					<span class="displaying-num">
						Displaying 
						<?php echo ( $pagenum - 1 ) * $per_page + 1; ?> - 
						<?php echo min( $pagenum * $per_page, pingpower_get_numof_records_bysql($total_sql) ); ?> of 
						<?php echo pingpower_get_numof_records_bysql($total_sql); ?>
						<?php echo $app_pagin; ?>
					</span>
				</div>
				<?php endif; ?>
				<div style="clear:both;"><!----></div>
			</div>
			
		</form>
		<?php else: ?>
		<p>No record is in the database</p>
		<?php endif; ?>
		<?php $this->addScheduleBox();?>
	   </div>
       

<?php        
    }
     /**
     *   show the add box of schedule pingback url  
     */
    public function addScheduleBox(){
        ?>
        
         <div class="wrap">
            <div id="poststuff" class="metabox-holder has-right-sidebar">
                <div class="postbox " style=""> 
                    <h3 class="hndle" ><span>ADD Pingback Harvesting Schedule</span></h3>
                    <div class="inside" style="padding: 0px 10px 0 10px">
                       <form id="addscheduleForm" action="" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?page=pp_pingbackschedule"> 
                           <table class="productTable">
                                <tr>
                                    <td><label>Set Frequency</label></td>
                                    <td>
                                        <select name="frequency" id="frequency">
                                            <option value="">Select Frequency</option>
                                            <option value="hourly">Hourly</option>
                                            <option value="twicedaily">Twice daily</option>
                                            <option value="daily">Daily</option>
                                            <option value="weekly">Weekly</option>
                                            <option value="monthly">Monthly</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td><label>Email Harvested PingBacks to</label></td>
                                    <td><input type="text" id="email" style="width: 200px;" name="email"/></td>
                                </tr>
                               
                                <tr>
                                    <td><label>Quantity of PingBacks to Harvest</label> </td>
                                    <td><input type="text" name="number" id="number" style="width: 50px;"  /></td>    
                                </tr>
                                <tr>
                                   <td colspan="2"><input type="submit" value="Add Schedule" name="addschedule" /></td>
                                   
                                </tr>
                               
                            </table>
                        </form>
                    </div>
                </div>
            </div>    
        
        </div>
        
        <?php
        
    }
     /**
     *   condition check of the scheduling module 
     */
    public function condition(){
        if(isset($_POST['addschedule'])){
           $this->add();
        }
      
        if(isset($_POST['pingbackschedule_perpage_submit'])){
          
           if($_POST['pingbackschedule_perpage']!="" ){
              update_option("pingbackschedule_perpage",$_POST['pingbackschedule_perpage']); 
           }
           
             
        }
    }
    
     /**
     *   add the schedule list 
     */
    public function add(){
        global $wpdb;
        $schedule_table=$wpdb->prefix."pingback_schedule";
        
        		
        $frequency         = htmlspecialchars(trim($_POST['frequency']));
        $email   = htmlspecialchars(trim($_POST['email']));
        $number          = htmlspecialchars(trim($_POST['number']));
    
        //$wpdb->show_errors=true;	
        $result = $wpdb->insert( $schedule_table, 
        		  		  array( 'frequency' => $frequency,'email' => $email,"number"=>$number), 
        		  		  array( '%s','%s','%d'  ) );
        
        
        if(FALSE === $result) 
            echo  '<div class="updated fade" id="message"><p>There was an error in the MySQL query</p></div>';
        else     		
            echo '<div class="updated fade" id="message"><p>Pingback Url Harvesting Schedule is successfully added</p></div>';
        
    }
    /**
    *   delete the schedule list 
    */
    public function delete(){
        global $wpdb;
    	$schedule_table=$wpdb->prefix."pingback_schedule";
        
        //print_r($_GET);die;
        $s_id=$_GET['id'];
    	if($s_id){
    	   
    		$sql = "DELETE from $schedule_table WHERE s_id = " . $s_id ;
        
            if(FALSE === $wpdb->query($sql)) 
                echo '<div class="updated fade" id="message"><p>There was an error in the MySQL query</p></div>';		
    		else
                echo '<div class="updated fade" id="message"><p>Pingback Url Harvesting Schedule is successfully  deleted.</p></div>';
                
    		
    	} else  echo '<div class="updated fade" id="message"><p>The record cannot be deleted</p></div>';
        
        $this->index();
    }
        
 
 }?>