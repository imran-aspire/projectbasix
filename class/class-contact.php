<?php

/**
 * contact CLASS 
 * 
 * @link http://www.mdimran.net/
 * @author Imran <imran.aspire@gmail.com>
 *  
 */

class contact{
    
    private $wpdb,$condition,$current_user;
   
    public function contact(){
         global $wpdb,$current_user;
         get_currentuserinfo();
         $this->current_user=$current_user;
         $this->wpdb=$wpdb;
         $this->condition =false;
         $access = new access();
         $access->chkAccess("contact");
         
         
    }
    function condition(){
        if($_GET['action']=="edit"){
            $this->edit();
            $this->condition=true;
            
        }
       
        
    }
    function updateContact(){
        
        $id=$_GET['id'];
        $data=$_POST;
        $table_name = $this->wpdb->prefix . "pbx_client";
       // print_r($data);die;
        

        $result = $this->wpdb->update( 
        	$table_name, 
        	array( 
        	    'first_name' => $data['first_name'], 
        		'last_name' => $data['last_name'] ,
                'user_email' => $data['user_email'],	
        	    'user_url' => $data['user_url'], 
        		'cell' => $data['cell'] ,
                'office_cell' => $data['office_cell'],	
                'company' => $data['company']	

        	), 
        	array( 'id' => $id ), 
        	array( 
        		'%s',	// value1
        		'%s',
                '%s',
        		'%s',	// value1
        		'%s',
                '%s',
        		'%s'	// value1

            ), 
        	array( '%d' ) 
        );
       // echo $this->wpdb->last_query;
        //die;
       // echo $result;die;
        if($result){
              echo '<div class="nNote nSuccess hideit" style="display:block">
                        <p><strong>SUCCESS: </strong> Client Update Successfully </p>
                      </div>';
        }
           
        else{
              echo '<div class="nNote nSuccess hideit" style="display:block">
                        <p><strong>Failure: </strong> Some Error Happen . Please try angain later .</p>
                      </div>';
        }
        //die;
        
    }
    function edit(){
        $id=$_GET['id'];
        $table_name = $this->wpdb->prefix . "pbx_client";
        $sql ="select *from $table_name where id=$id";
        $result=$this->wpdb->get_row($sql);
        
        $projectbasix_link=get_permalink(get_option("projectBasix_page"));
        if(get_option('permalink_structure')=="")
            $pageLink=$projectbasix_link."&page=";
        else 
            $pageLink=$projectbasix_link."?page=";  
            
        
        $actionUrl=$pageLink . "contact&amp;id=" . $id ;
    ?>  
        <br />
        <form class="mainForm" method="post" action="<?php echo $actionUrl; ?>">
        <fieldset>
            <div class="widget">
                <div class="head">
                        <h5 class="iList">Edit Client</h5>
                 </div>
                <div id="clientBox2"> 
                     <div class="rowElem">
                        <label>Email</label>
                        <div class="formRight">
                            <input type="text" name="user_email" id="user_email" value="<?php echo $result->user_email; ?>" class="regular-text" />
                        </div>
                        <div class="fix"></div>
                                                    
                    </div>
                    <div class="rowElem">
                        <label>First Name:</label>
                        <div class="formRight">
                            <input type="text" name="first_name" id="first_name" value="<?php echo $result->first_name; ?>" class="regular-text" />
                        </div>
                        <div class="fix"></div>
                                                    
                    </div>
                    <div class="rowElem">
                        <label>Last Name:</label>
                        <div class="formRight">
                            <input type="text" name="last_name" id="last_name" value="<?php echo $result->last_name; ?>" class="regular-text" />
                        
                        </div>
                        <div class="fix"></div>
                                                    
                    </div>
                    <div class="rowElem">
                        <label>Website</label>
                        <div class="formRight">
                            <input type="text" name="user_url" id="user_url" value="<?php echo $result->user_url; ?>" class="regular-text" />
                        
                        </div>
                        <div class="fix"></div>
                                                    
                    </div>

                    <div class="rowElem">
                        <label>Phone</label>
                        <div class="formRight">
                            <input type="text" name="cell" id="cell" value="<?php echo  $result->cell; ?>" class="regular-text" />
                        
                        </div>
                        <div class="fix"></div>
                                                    
                    </div>

                    <div class="rowElem">
                        <label>Office Cell</label>
                        <div class="formRight">
                            <input type="text" name="office_cell" id="office_cell" value="<?php echo $result->office_cell; ?>" class="regular-text" />
                        
                        </div>
                        <div class="fix"></div>
                                                    
                    </div>
                    <div class="rowElem">
                        <label>Company</label>
                        <div class="formRight">
                            <input type="text" name="company" id="company" value="<?php echo $result->company; ?>" class="regular-text" />
                        
                        </div>
                        <div class="fix"></div>
                                                    
                    </div>

                    <div class="fix"></div>
                    <input type="submit" value="Update Client" name="updateClient" class="greyishBtn submitForm" />
                    <div class="fix" ></div>
            </div>
        </div>       
        </fieldset>
        </form>     
    <?php
    }
    public function index(){
        $table_name = $this->wpdb->prefix . "pbx_client";
        $this->condition();
        
        $currentRole=  $this->current_user->roles[0];
         
        
        if(isset($_POST['updateClient'])){
           $this->updateContact();
        }
        
        $page="contact";
        $errormsg="";
        if(isset($_POST['addCsvSubmit'])){
            
            $this->addContactCsv($_FILES);     
            echo '<div class="nNote nSuccess hideit" style="display:block">
                        <p><strong>SUCCESS: </strong> File Uplosd is Successfully Complete</p>
                      </div>';
            
                        
        }
        
        // add client
       $errormsg="";
       if(isset($_POST['update'])){
       // print_r($_POST);die;
            $table_name = $this->wpdb->prefix . "pbx_client";
            $first_name = sanitize_text_field( $_POST['first_name'] );
            $last_name = sanitize_text_field( $_POST['last_name'] );
            
            $user_url = sanitize_text_field( $_POST['user_url'] );
            $email = sanitize_text_field( $_POST['user_email'] );
            $cell = sanitize_text_field( $_POST['cell'] );
            $office_cell = sanitize_text_field( $_POST['office_cell'] );
            $company = sanitize_text_field( $_POST['company'] );
            
          // insert clients 
              $this->wpdb->insert( $table_name, 
                array( 
                	'first_name' => $first_name, 
                	'last_name' => $last_name, 
                	'user_email' => $email, 
                    'user_url' => $user_url, 
                	'cell' => $cell, 
                	'office_cell' => $office_cell, 
                	'company' => $company
                ), 
                array( 
                	'%s','%s','%s','%s','%s','%s','%s'
                ) 
                );
            
            
            
            if(empty($this->wpdb->insert_id)){
                echo '<div class="nNote nFailure hideit" style="display:block">
                        <p><strong>FAILURE: Some problem appear . Please try again later</p>
                      </div>';
            }else{
                 echo '<div class="nNote nSuccess hideit" style="display:block">
                        <p><strong>Success: </strong> Client is Successfully Added</p>
                      </div>';
            }
                        
        }
        
        // search clients 
        $searchStr="";
        if(isset($_POST['searchContact'])){
            $name= $_POST['cname'];
            $searchStr = " where (first_name like '%$name%' or last_name like '%$name%' or user_email like '%$name%' ) ";
                
        }
        
        
        $projectbasix_link=get_permalink(get_option("projectBasix_page"));
        if(get_option('permalink_structure')=="")
            $pageLink=$projectbasix_link."&page=";
        else 
            $pageLink=$projectbasix_link."?page=";  
            
        
        //$clientsList=$this->getClientsList();
        
        $per_page=20;
        
        $sql ="Select *from $table_name $searchStr";
        
        
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
        
        
        if( $pagenum > 0 ) $sql .= " order by first_name LIMIT ". (($pagenum-1)*$per_page) .", ". $per_page;
    	//getting results
    	$contact_list = $this->wpdb->get_results($sql);
        
        //print_r($contact_list);
        
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
        if($contact_list){
            $contact_listStr .= '<table width="100%" cellspacing="0" cellpadding="0" class="tableStatic">
                    <tr>
                        <td width="20%"><strong>First Name</strong></td>
                        <td width="20%"><strong>Last Name</strong></td>
                        <td width="20%"><strong>Email</strong></td>
                        <td width="20%"><strong>Phone</strong></td>
                        <td  width="20%" colspan="2" style="text-align:center"><strong>Action</strong></td>
                     </tr>
                     <tbody>';
                
            foreach($contact_list as $row ){
                        $contact_listStr .= 
                                '<tr>
                                    <td>'.$row->first_name.'</td>
                                    <td>'.$row->last_name.'</td>
                                    <td>'.$row->user_email.'</td>
                                    <td>'.$row->cell.'</td>
                                    <td style="text-align:center"><a href="'.$pageLink . "contact&amp;action=edit&amp;id=" . $row->id . '"  class="delete">Edit</a></td>
        		                    <td style="text-align:center"><a href="'. $pageLink . "contact&amp;action=delete&amp;id=" . $row->id . '" onclick="return confirm(\'Are you sure you want to delete this Contact?\');" class="delete">Delete</a></td>
    	                       </tr>';
                
            }
            $contact_listStr .= '</tbody>
                </table>';
        }
     
     
     $currentRole=  $this->current_user->roles[0];
    
      if($this->condition==false){  
    ?>
      
      <form class="mainForm" method="post" enctype="multipart/form-data" action="">
        <fieldset>
        <div class="widget first" style="maring-top: 10px !important;">
                <div class="head">
                    <a class="clickFormBox" rel="importBox" href="javascript:void(0);">
                        <h5 class="iList">Import Contact list</h5>
                    </a>
                </div>
                <div id="importBox" class="taskBox">
                    <div class="rowElem noborder">
                        <label>Upload File</label>
                        <div class="formRight">
                            <input type="file" name="csv_file" id="csv_file"  /> <br />
                            <small> Only Accept Google Outlook CSV format </small>
                        </div>
                        <div class="fix"></div>
                    </div>
                    <div class="fix"></div>
                    <input type="submit" value="Add Contact" name="addCsvSubmit" class="greyishBtn submitForm" />
                    <div class="fix" ></div>
                </div>
         </div>       
        </fieldset>
       </form>
       
       <?php if($currentRole=="administrator"){ ?>  
           
            <fieldset>
            <div class="widget">
                    <div class="head">
                        <a class="clickFormBox" rel="exportBox" href="javascript:void(0);">
                            <h5 class="iList">Export Contact list</h5>
                        </a>    
                     </div>    
                    
                    <div id="exportBox" class="taskBox" style="padding-top: 20px;">    
                    <div class="fix"></div>
                    <a  class="greyishBtn submitForm" style="padding: 2px 10px;" href="<?php echo PBX_WP_PLUGIN_URL; ?>/projectbasix/include/contact-csv.php" >Export In CSV</a>
                    <?php /*<input type="submit" value="Export In CSV" name="addCsvSubmit" id="exportContactList" class="greyishBtn submitForm" />
                    */?>
                    <div class="fix" ></div>
                    </div>
                    
             </div>       
            </fieldset>
           
        <?php } ?>
        <form class="mainForm" method="post" action="">
        <fieldset>
            <div class="widget">
                <div class="head">
                    <a class="clickFormBox" rel="clientBox" href="javascript:void(0);">
                        <h5 class="iList">Add Client</h5>
                    </a>    
                 </div>
                <div id="clientBox"> 
                     <div class="rowElem">
                        <label>Email</label>
                        <div class="formRight">
                            <input type="text" name="user_email" id="user_email" value="<?php echo $_POST['user_email']; ?>" class="regular-text" />
                        </div>
                        <div class="fix"></div>
                                                    
                    </div>
                    <div class="rowElem">
                        <label>First Name:</label>
                        <div class="formRight">
                            <input type="text" name="first_name" id="first_name" value="<?php echo $_POST['first_name']; ?>" class="regular-text" />
                        </div>
                        <div class="fix"></div>
                                                    
                    </div>
                    <div class="rowElem">
                        <label>Last Name:</label>
                        <div class="formRight">
                            <input type="text" name="last_name" id="last_name" value="<?php echo $_POST['last_name']; ?>" class="regular-text" />
                        
                        </div>
                        <div class="fix"></div>
                                                    
                    </div>
                    <div class="rowElem">
                        <label>Website</label>
                        <div class="formRight">
                            <input type="text" name="user_url" id="user_url" value="<?php echo $_POST['user_url']; ?>" class="regular-text" />
                        
                        </div>
                        <div class="fix"></div>
                                                    
                    </div>

                    <div class="rowElem">
                        <label>Phone</label>
                        <div class="formRight">
                            <input type="text" name="cell" id="cell" value="<?php echo $_POST['cell']; ?>" class="regular-text" />
                        
                        </div>
                        <div class="fix"></div>
                                                    
                    </div>

                    <div class="rowElem">
                        <label>Office Cell</label>
                        <div class="formRight">
                            <input type="text" name="office_cell" id="office_cell" value="<?php echo $_POST['office_cell']; ?>" class="regular-text" />
                        
                        </div>
                        <div class="fix"></div>
                                                    
                    </div>
                    <div class="rowElem">
                        <label>Company</label>
                        <div class="formRight">
                            <input type="text" name="company" id="company" value="<?php echo $_POST['company']; ?>" class="regular-text" />
                        
                        </div>
                        <div class="fix"></div>
                                                    
                    </div>

                    <div class="fix"></div>
                    <input type="submit" value="Add Client" name="update" class="greyishBtn submitForm" />
                    <div class="fix" ></div>
            </div>
        </div>       
        </fieldset>
        </form> 
      
       
        <div class="widget">
          <form class="" method="post" enctype="multipart/form-data" action="">  
              <div class="head">
              <h5 class="iList">Search Contact</h5>
                <input type="text" name="cname" id="cname" style="float: left;margin-top: 10px; width: 400px;"  /> <input type="submit" value="Search Contact" name="searchContact" class="greyishBtn submitForm" style="margin-top: 10px;" />
              </div>
          </form>      
        </div>
        
        <div class="widget">
            <div class="head"><h5 class="iUsers">Clients list</h5> <?php echo $paginationStr; ?></div>
            <?php echo $contact_listStr; ?> 
                
        </div>
      <?php echo $paginationStr; ?>                    
    
    <?php
        }    
    }
    
    function addContactCsv($files){
        if($files['csv_file']['size']!=0){
           
            $csvFileData = fopen($files['csv_file']['tmp_name'], "r");
            $firstData = fgetcsv($csvFileData, 1000, ",");
            
            $first_name= array_search("First Name",$firstData);
            $last_name= array_search("Last Name",$firstData);
            $user_url= array_search("Web Page",$firstData);
            $user_email= array_search("E-mail Address",$firstData);
            $cell= array_search("Mobile Phone",$firstData);
            $office_cell= array_search("Business Phone",$firstData);
            $company= array_search("Company",$firstData);
            
            
            $table_name = $this->wpdb->prefix . "pbx_client";
                
            $t=0;
            while (($data = fgetcsv($csvFileData, 1000, ",")) !== FALSE) {
                if($t!=0){
                    // insert clients 
                    $this->wpdb->insert( $table_name, 
                    array( 
                    	'first_name' => $data[$first_name], 
                    	'last_name' => $data[$last_name], 
                    	'user_url' => $data[$user_url], 
                    	'user_email' => $data[$user_email], 
                    	'cell' => $data[$cell], 
                    	'office_cell' => $data[$office_cell], 
                    	'company' => $data[$company]
                    ), 
                    array( 
                    	'%s','%s','%s','%s','%s','%s','%s'
                    ) 
                    );
                }
                
                $t++;
            }
        }
       
    }
        
    function getClientsList(){
        $table_name = $this->wpdb->prefix . "pbx_client";
        $sql ="Select *from $table_name ";
        return $this->wpdb->get_results($sql);
    }
}  
?>